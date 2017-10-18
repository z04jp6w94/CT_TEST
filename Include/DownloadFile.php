<?php
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
if($_GET['f'] != null){
	$file = $_GET['f'];																		// 檔案實體名稱
	$DnfileName = $_GET['v'];																// 下載用檔名
	$url = MainSite . $_GET['r'];															// 路徑位置
	header("Content-type:application");
	header("Content-Disposition: attachment; filename=" . $DnfileName);	
	readfile($url . "/" . str_replace("@", "", $file));
	exit(0);
}else{
	echo "找不到相關檔案....";
}
