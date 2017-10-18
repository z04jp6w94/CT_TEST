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
//		撰寫人員：t
//		撰寫日期：20161229
//		程式功能：luckysparks / Level / Get
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
	session_start();
	date_default_timezone_set('Asia/Taipei');	
//定義全域參數
	$val = array();																			//寫入欄位名稱及值的陣列
	$ExDate = date("Ymd");																	//操作 日期
	$ExTime = date("His");																	//操作 時間
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//定義一般參數
	$success = false;
	$LevelKey = GetxRequest("T");

	
	//資料庫連線
		$MySql = new mysql();
	
		$Sql = " select Id, Description from SystemParameter Where 1 = 1 ";
		if($LevelKey != ''){
		$Sql .= " and LevelKey = '".$LevelKey."' " ;			
		}
		$Sql .= " and Comment = '000000000000229' ";
		$Sql .= " and DeleteStatus = 'N' ";
		$Area =$MySql -> db_query($Sql) or die("查詢 Query 錯誤Area");
		$AreaArr = $MySql -> db_array($Sql,2);
		$success = true;


	//關閉資料庫連線
		$MySql -> db_close();

	
//狀態回執
	header('Content-type: application/xml');
	echo '<?xml version="1.0"?>';
	echo '<response>';
	if ($success){ 
			echo '<resu>1</resu>';
		for($i=0;$i<count($AreaArr);$i++){
			echo '<data>';
			echo '<code>'.$AreaArr[$i][0].'</code>';
			echo '<desc>'.$AreaArr[$i][1].'</desc>';
			echo '</data>';
		}
	}
	echo '</response>';
?>
