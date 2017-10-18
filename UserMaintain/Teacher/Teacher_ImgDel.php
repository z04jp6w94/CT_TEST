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
//		撰寫人員：Jimmy
//		撰寫日期：20140728
//		程式功能：luckysparks /最新消息 / 刪除圖檔
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
	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
//資料庫連線
	$MySql = new mysql();
//使用權限
	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
	ChkFunc_BeforeRunPG(3, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//定義一般參數
	$DataKey = trim(GetxRequest("MN", 30));
	$PId = trim(GetxRequest("PId", 30));
	$ToPage = trim(GetxRequest("ToPage", 30));
	$UploadDir = root_path . AdMFile;														//上傳路徑
//取圖檔資訊
	$Sql = " Select * From ad_m Where AdM_No = $DataKey ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$rs = $MySql -> db_fetch_array($initRun);
//移除圖檔
	DeleteFile($UploadDir . $rs["AdM_Img" . $PId]);
//更新資料
	$Sql = " Update ad_m Set AdM_Img" . $PId . " = NULL Where AdM_No = $DataKey ";
	$MySql ->db_query($Sql) or die("查詢 Query 錯誤2");
//關閉資料庫連線
	$MySql -> db_close();
//狀態回執
	if($rtnURL != ""){	
		header("location:$rtnURL");
	}else{
		header("location:" . $gMainFile . "_Modify.php?PG_ID=$PG_ID&DataKey=$DataKey&ToPage=$ToPage");
	}
?>
