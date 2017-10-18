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
	header ('Content-Type: text/html; charset=utf-8');
	session_start(); 
//	此頁存放有關頁面安全性檢查的公用程式
	function Chk_Login($pUSER_ID){
	//檢查是否有登入後台
		if($pUSER_ID == ""){
//			$_SESSION["rtnMsg"] = "您有一段時間沒有使用，系統已自動為您登出，請重新登入";
//			$_SESSION["rtnJsp"] = "top.location.href='/Maintain/Menu/Member_Login.aspx';";
//			$_SESSION["rtnJSPtxt"] = "重新登入";
			$FoldAry = explode("/",$_SERVER['PHP_SELF']);
			header("location:http://" . $_SERVER ['HTTP_HOST'] .'/'. $FoldAry[1] . "/Menu/MemberLogin.php");
		}
	}
	function ChkFunc_BeforeRunPG($pFunc, $pPG_ID, $pUSER_ID, $MySql){
		
	}
	function GetTreeView($pUSER_ROLE,$MySql){
		global $FunctionArr;
			//取得角色權限
		$Sql = " Select a.Name, c.FunPage, IF(M.FunctionsDetailMId,'M','T') as FunctionType ";
		$Sql .= " From RoleFunctions M ";
		$Sql .= " left join FunctionsDetail_M a on a.Id = M.FunctionsDetailMId ";
		//$Sql .= " left join FunctionsDetail_T b on b.Id = M.FunctionsDetailTId ";
		$Sql .= " left join Functions c on c.Id = a.FunctionsId or c.Id = a.FunctionsId ";
		$Sql .= " Where 1 = 1 ";   
		$Sql .= " and RoleId = '".$pUSER_ROLE."'";
		$Sql .= " and M.FunctionsDetailMId is not Null ";   
		//$Sql .= " or M.FunctionsDetailTId is not Null) ";   
		//$TimeRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$FunctionArr = $MySql -> db_array($Sql,3);
	}

    //$pUSER_ROLE 角色編號
    //$pUSER_Menu 選單編號
    //$pUSER_Func 功能代號
    //$MySql 資料庫變數
    function GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,$pUSER_Func,$MySql)
    {
        //echo $pUSER_Func;
        switch($pUSER_Func){
            case 1://新增
                $pUSER_FuncName = "G_ADD";
                break;
            case 2://檢視
                $pUSER_FuncName = "G_VIEW";
                break;
            case 3://編輯
                $pUSER_FuncName = "G_MOD";
                break;
            case 4://刪除
                $pUSER_FuncName = "G_DEL";
                break;
            case 5://列表
                $pUSER_FuncName = "G_List";
                break;
            case 6://角色
                $pUSER_FuncName = "G_Role";
                break;
            case 7://系統參數
                $pUSER_FuncName = "G_Sys";
                break;
        }

        $Sql = " Select " . $pUSER_FuncName . " as Result";
		$Sql .= " From RoleFunctions RF ";
		$Sql .= " Where 1 = 1 ";   
		$Sql .= " and RF.RoleId = '".$pUSER_ROLE."'";
		$Sql .= " and RF.FunctionsDetailMId = '".$pUSER_Menu."'";

        $initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
        return $MySql -> db_result($initRun);        
    }
	function RoleFunction($pUSER_ROLE,$pUSER_Menu,$MySql){
		global $result1,$result2,$result3,$result4,$result5;
		
		$result1 = GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,1,$MySql);
		$result2 = GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,2,$MySql);
		$result3 = GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,3,$MySql);
		$result4 = GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,4,$MySql);
		$result5 = GetRoleFunctionsEnabled($pUSER_ROLE,$pUSER_Menu,5,$MySql);
			
	}
	function NowFunction($Path,$MySql){
		$Sql = "Select FunctionsId, FunctionsT_Back, FunctionsT_MSG From Functions_T ";
		$Sql .= " Where FunctionsT_Page = '".$Path."' ";
		$FunctionsRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		return $MySql -> db_array($Sql,3);
			
	}