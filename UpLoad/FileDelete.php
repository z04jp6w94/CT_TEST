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
//		撰寫日期：20150317
//		程式功能：網站模組 / 公用上傳程式 / 刪除檔案
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
	$Success = true;																										//資料是否成功
	$path = GetxRequest("path");																							//檔案路徑
	$name = GetxRequest("name");																							//檔名
	$PG_ID = $_COOKIE["M_PG_ID"];																							//程式	 ID (GPID)	
//資料庫連線
	$MySql = new mysql();
//刪除圖片
	if($path != "" && $name != ""){
		DeleteFile(root_path . $path . "ReSize/134_" . $name);
		DeleteFile(root_path . $path . "ReSize/390_" . $name);
		DeleteFile(root_path . $path . "ReSize/294_" . $name);		
		DeleteFile(root_path . $path . $name);
//		UploadLogDel($MySql, $path, $name, $PG_ID);
	}else{
		$Success = false;
	}
//狀態回執
	$Xml = '';
	$Xml .= '<?xml version="1.0"?>';
	$Xml .= '<response>';
	if($Success){$Xml .= '<resu>1</resu>';}else{$Xml .= '<resu>0</resu>';}
	$Xml .= '</response>';
	echo $Xml;
?>