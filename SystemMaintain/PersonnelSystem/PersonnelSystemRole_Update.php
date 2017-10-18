<?php
//
//　      _    (_)_(_)
//　 ___ | |__  _| |_ _ _ _ __ _ _ __
//　/ __\| __ \| | | | ' ' / _` | '_ \     
//　| |_ | | | | | | | | |  (_| | | | |	 taipei 
//　\___/|_| |_|_|_|_|_|_|_\__,_|_| |_|    2013
//　www.chiliman.com.tw
// 
//*****************************************************************************************
//		撰寫人員：BochiHuang
//		撰寫日期：20161214
//		程式功能：ct / 角色權限 / 角色權限更新
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：ad_m
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
header ('Content-Type: text/html; charset=utf-8');
session_start();
date_default_timezone_set('Asia/Taipei');	
//定義全域參數
$val = array();																			//寫入欄位名稱及值的陣列
$ExID = $_SESSION["M_USER_ID"];															//操作 ID
$ExDate = date("Ymd");																	//操作 日期
$ExTime = date("His");																	//操作 時間
//函式庫
include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
//	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
//資料庫連線
$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(2, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//定義一般參數
$DataKey = trim(GetxRequest("DataKey"));
$RoleGroup = trim(GetxRequest("RoleGroup"));
$initRoleGroup = trim(GetxRequest("initRoleGroup"));
$RoleName = trim(GetxRequest("RoleName"));

//更新角色資料
$Sql = " Select * from Role where 1 = 1 and Id = '$DataKey'";
$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
$RowCount = $MySql -> db_num_rows($initRun);

if($RowCount <= 0){
    echo '<script>';
    echo 'alert("此筆資料不存在，可能已被其他使用者刪除，請重新操作");';
    echo 'window.history.back();';
    echo '</script>';
    exit();
}else{
	//Auto Update
    while ($initAry = mysql_fetch_field($initRun)){
        switch($initAry -> name){
			//不處理的欄位
            case 'Id':
            case 'DefaultRole':
                break;
			//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
            //				case 'NewsM_Sort':
            //					$val[$initAry -> name] = 0;
            //					break;

            case 'RoleName':
                $val[$initAry -> name] = $RoleName;
                break;
            default:
                $val[$initAry -> name] = trim(GetxRequest($initAry -> name));
                break;
        }
    }
    $where = "Id = " . $DataKey;
    $MySql -> setTable("Role");
    $MySql -> updateOne($val, $where);
}

if ($initRoleGroup !=""){
    $arrID = explode(',', $initRoleGroup);
    for($i=0;$i<sizeof($arrID);$i++){
        initRoleFunctions($DataKey,$arrID[$i],$MySql);
    }    
}

if($RoleGroup !=""){
    //更新角色權限資料
    $arrID = explode(',', $RoleGroup);
    for($i=0;$i<sizeof($arrID);$i++){
        updateFunctionsDetail($DataKey,$arrID[$i],$MySql);
    }    
}

//關閉資料庫連線
$MySql -> db_close();

//狀態回執
header("location:PersonnelSystemRole.php");

function initRoleFunctions($RoleId,$Id,$MySql){
    //優先處理系統參數例外
    if ($Id == "FDM000000000000005"){
        $FDMId = str_replace("FDM","",$Id);
        $Type = 7; //特例    
    }else{
        //取得FDM編號
        $Sql = " select FunctionsDetailMId,Type from FunctionsDetail_R ";
        $Sql .= " where 1 = 1 and Id='$Id'";

        $initRun = $MySql -> db_query($Sql) or die("查詢錯誤");
        $rs = $MySql -> db_fetch_array($initRun);

        $FDMId = $rs["FunctionsDetailMId"];
        $Type = $rs["Type"];        
    }

    //更新角色權限資料
    $Sql = " Select * from RoleFunctions where 1 = 1 and FunctionsDetailMId= '$FDMId' and RoleId= '$RoleId'";

    $initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
    $initRFAry = $MySql -> db_fetch_array($initRun);
    $RowCount = $MySql -> db_num_rows($initRun);

    if($RowCount <= 0){
        echo '<script>';
        echo 'alert("此筆資料不存在，可能已被其他使用者刪除，請重新操作");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }else{

        $G_ADD = "N";
        $G_MOD = "N";
        $G_DEL = "N";
        $G_VIEW = "N";
        $G_List = "N";
        $G_Role = "N";
        $G_Sys = "N";         

        //Auto Update
        while ($initAry = mysql_fetch_field($initRun)){
            //echo $initAry['G_ADD'];
            
            switch($initAry -> name){
                //不處理的欄位
                case 'Id':
                case 'RoleId':
                case 'FunctionsDetailMId':
                case 'CreatorId':
                case 'CreateDate':
                    break;
                //此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
                //				case 'NewsM_Sort':
                //					$val[$initAry -> name] = 0;
                //					break;
                case "G_ADD":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_ADD;
                    break;
                case "G_MOD":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_MOD;
                    break;
                case "G_DEL":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_DEL;
                    break;
                case "G_VIEW":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_VIEW;
                    break;
                case "G_List":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_List;
                    break;
                case "G_Role":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_Role;
                    break;
                case "G_Sys":
                    if ($initRFAry[$initAry -> name] == "Y") $val[$initAry -> name] = $G_Sys;
                    break;
                case 'LastEditorId':
                    $val[$initAry -> name] = $ExID;
                    break;
                case 'LastEditDate':
                    $val[$initAry -> name] = $ExDate.$ExTime;
                    break;
                default:
                    $val[$initAry -> name] = trim(GetxRequest($initAry -> name));
                    break;
            }

        }
        
        $MySql -> setTable("RoleFunctions");
        $where = "FunctionsDetailMId = '$FDMId' and RoleId = '$RoleId'";
        $MySql -> updateOne($val, $where);
    }
}

function updateFunctionsDetail($RoleId,$Id,$MySql){ 
    //優先處理系統參數例外
    if ($Id == "FDM000000000000005"){
        $FDMId = str_replace("FDM","",$Id);
        $Type = 7; //特例    
    }else{
        //取得FDM編號
        $Sql = " select FunctionsDetailMId,Type from FunctionsDetail_R ";
        $Sql .= " where 1 = 1 and Id='$Id'";

        $initRun = $MySql -> db_query($Sql) or die("查詢錯誤");
        $rs = $MySql -> db_fetch_array($initRun);

        $FDMId = $rs["FunctionsDetailMId"];
        $Type = $rs["Type"];            
    }

    //更新角色權限資料
    $Sql = " Select * from RoleFunctions where 1 = 1 and FunctionsDetailMId= '$FDMId' and RoleId= '$RoleId'";

    $initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
    $RowCount = $MySql -> db_num_rows($initRun);

    if($RowCount <= 0){
        echo '<script>';
        echo 'alert("此筆資料不存在，可能已被其他使用者刪除，請重新操作");';
        echo 'window.history.back();';
        echo '</script>';
        exit();
    }else{
        //判斷是否權限
        switch($Type){
            case 1://新增
                $G_ADD = "Y";
                break;
            case 2://檢視
                $G_VIEW = "Y";
                break;
            case 3://編輯
                $G_MOD = "Y";
                break;
            case 4://刪除
                $G_DEL = "Y";
                break;
            case 5://列表
                $G_List = "Y";
                break;
            case 6://角色
                $G_Role = "Y";
                break;
            case 7://系統參數
                $G_Sys = "Y";
                break;
        }
        
        //Auto Update
        while ($initAry = mysql_fetch_field($initRun)){
            switch($initAry -> name){
                //不處理的欄位
                case 'Id':
                case 'RoleId':
                case 'FunctionsDetailMId':
                case 'CreatorId':
                case 'CreateDate':
                    break;
                //此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
                //				case 'NewsM_Sort':
                //					$val[$initAry -> name] = 0;
                //					break;
                case "G_ADD":
                    if ($G_ADD == "Y") $val[$initAry -> name] = $G_ADD;
                    break;
                case "G_MOD":
                    if ($G_MOD == "Y") $val[$initAry -> name] = $G_MOD;
                    break;
                case "G_DEL":
                    if ($G_DEL == "Y") $val[$initAry -> name] = $G_DEL;
                    break;
                case "G_VIEW":
                    if ($G_VIEW == "Y") $val[$initAry -> name] = $G_VIEW;
                    break;
                case "G_List":
                    if ($G_List == "Y") $val[$initAry -> name] = $G_List;
                    break;
                case "G_Role":
                    if ($G_Role == "Y") $val[$initAry -> name] = $G_Role;
                    break;
                case "G_Sys":
                    if ($G_Sys == "Y") $val[$initAry -> name] = $G_Sys;
                    break;
                case 'LastEditorId':
                    $val[$initAry -> name] = $ExID;
                    break;
                case 'LastEditDate':
                    $val[$initAry -> name] = $ExDate.$ExTime;
                    break;
                default:
                    $val[$initAry -> name] = trim(GetxRequest($initAry -> name));
                    break;
            }

        }
        
        $MySql -> setTable("RoleFunctions");
        $where = "FunctionsDetailMId = '$FDMId' and RoleId = '$RoleId'";
        $MySql -> updateOne($val, $where);
    }
}
?>
