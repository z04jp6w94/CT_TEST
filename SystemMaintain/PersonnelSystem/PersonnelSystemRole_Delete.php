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
//		程式功能：ct / 角色權限 / 角色權限刪除
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：news_m
//		　　　　　del：None
//		　　　　　upt：None
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
//	ChkFunc_BeforeRunPG(3, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey"));
//驗證資料
		if (is_numeric($DataKey)!= '1'){
			echo '<script>';
			echo 'alert("參數錯誤，請稍後再執行一次");';
			echo 'window.history.back();';
			echo '</script>';
			exit();			
			}
		
		if ($DataKey != '') {
            //若有使用者還有角色權限不給刪除
            $MyWhere = " Where RoleId = $DataKey ";
            $Sql = " Select RoleId from Personnel $MyWhere ";
            $initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
            $RowCount = $MySql -> db_num_rows($initRun);

            if($RowCount > 0) {
                echo '<script>';
                echo 'alert("有使用者套用中，無法刪除");';
                echo 'window.history.back();';
                echo '</script>';
                exit();	
            }

            //刪除角色權限
            $MyWhere = " Where RoleId = $DataKey ";
            $Sql = " Delete from RoleFunctions $MyWhere ";
            $MySql -> db_query($Sql) or die("查詢 Query 錯誤");

            //刪除角色
            $MyWhere = " Where Id = $DataKey ";
            $Sql = " Delete from Role $MyWhere ";
            $MySql -> db_query($Sql) or die("查詢 Query 錯誤");		
		}
//關閉資料庫連線
	$MySql -> db_close();
//狀態回執
	if($rtnURL != ""){	
		header("location:$rtnURL");
	}else{
		header("location:" . $_COOKIE["FilePath"] . "?");
	}
?>
