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
//		撰寫人員：小夯
//		撰寫日期：20140728
//		程式功能：luckysparks / 最新消息 / 寫入
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
			$Sql = " Select DeleteStatus From Template where Id = $DataKey ";
			$Focus = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
			$rs = $MySql -> db_fetch_array($Focus);
			
//更新狀態
			if ($rs["DeleteStatus"] != "Y"){
				$Sql = " Update Template Set DeleteStatus = 'Y' Where Id = $DataKey ";
				$Focus1 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
				$rs1 = $MySql -> db_fetch_array($Focus1);
			}else{
				$Sql = " Update Template Set DeleteStatus = 'N' Where Id = $DataKey ";	
				$Focus2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
				$rs2 = $MySql -> db_fetch_array($Focus2);
				
			}			
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
