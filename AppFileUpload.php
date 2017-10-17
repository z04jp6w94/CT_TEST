<?php
//
//　      _    (_)_(_)
//　 ___ | |__  _| |_ _ _ _ __ _ _ __
//　/ __\| __ \| | | | ' ' / _` | '_ \     
//　| |_ | | | | | | | | |  (_| | | | |	 taipei 
//　\___/|_| |_|_|_|_|_|_|_\__,_|_| |_|    2014
//　www.chiliman.com.tw
// 
//*****************************************************************************************
//		撰寫人員：JimmyChao
//		撰寫日期：20150317
//		程式功能：網站模組 / 公用上傳程式 / 上傳檔案
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
	$ExDate = date("Ymd");																									//操作 日期
	$ExTime = date("His");																									//操作 時間
//定義一般參數
//	$Id = GetxRequest("id", 20);
//	$Path = GetxRequest("Path");
//	$PG_ID = GetxRequest("PG_ID");
	$Path = Course;
//資料庫連線
	$MySql = new mysql();
//上傳圖片
	if($_FILES["Filedata"]['name'] != ''){
	//相關設定
		$UploadDir = root_path . $Path; //account_path . $Path;																					// 上傳路徑
		MMkDir($UploadDir);																									// 建立資料夾
	//檔案資訊
		$File_defName = $_FILES["Filedata"]["name"];																		// 上傳檔案的原始名稱
		$File_newName = $ExDate . $ExTime . floor(microtime()*1000) . substr($File_defName , strrpos($File_defName, "."));	// 存入暫存區的檔名
		$File_tmpName = $_FILES["Filedata"]["tmp_name"];																	// 上傳檔案後的暫存資料夾位置。
		$File_size = $_FILES["Filedata"]["size"];																			// 上傳的檔案原始大小。
		if (move_uploaded_file($File_tmpName, $UploadDir . $File_newName)) {
			//UploadLog($MySql,$Path,$File_newName,$PG_ID);
		}
	}
//關閉資料庫連線
	$MySql -> db_close();
//狀態回執
	echo trim($Id . ',' . $Path . ',' . $File_newName);
?>