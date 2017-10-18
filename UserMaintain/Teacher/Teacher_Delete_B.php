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
//		撰寫人員：JimmyChao
//		撰寫日期：20140728
//		程式功能：luckysparks / 最新消息 / 刪除
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：news_m
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//定義全域參數
	
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
//	ChkFunc_BeforeRunPG(4, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//參數
	$DataCnt = trim(GetxRequest("DataCnt", 10));
	$Del_Value = "";
	$UploadDir = root_path . AdMFile;																		// 上傳路徑
	for($i = 1; $i <= $DataCnt; $i++){
		if(trim(GetxRequest("delVal" . $i, 10)) != ''){
			if($Del_Value == ""){
				$Del_Value = trim(GetxRequest("delVal" . $i, 10));
			}else{
				$Del_Value .= "," . trim(GetxRequest("delVal" . $i, 10));
			}
		}
	}
	$Del_Value = "'" . str_replace(",", "','", $Del_Value) . "'";
//刪除圖檔
	$Sql = " Select AdM_Img From ad_m Where AdM_No in (" . $Del_Value . ") ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	while ($initAry = $MySql -> db_fetch_array($initRun)){
		if($initAry[0] != ""){
			DeleteFile($UploadDir . $initAry[0]);
		}
//		if($initAry[1] != ""){
//			DeleteFile($UploadDir . $initAry[1]);
//		}
//		if($initAry[2] != ""){
//			DeleteFile($UploadDir . $initAry[2]);
//		}
//		if($initAry[3] != ""){
//			DeleteFile($UploadDir . $initAry[3]);
//		}
	}
//刪除資料
	if($Del_Value != ""){
		$MyWhere = " Where AdM_No in (" . $Del_Value . ") ";
	//帳號主檔
		$Sql = " Delete from ad_m $MyWhere ";
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
