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
//		撰寫日期：20131122
//		程式功能：連丰 / 登入頁 / 查詢權限
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
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//參數
	$success = false;
	$Account = trim(GetxRequest("Account", 20));
	$Password = trim(GetxRequest("Password", 20));
	$rtnMsg = "";
	$URL = "";
//檢核
	if($Account == ''){
		$rtnMsg = "請輸入系統帳號";
	}elseif($Password == ''){
		$rtnMsg = "請輸入密碼";
	}else{
		$success = true;
	}
	
	if($success){
	//資料庫連線
		$MySql = new mysql();
	//登入查詢
		$Sql = " Select Account, PWD, FullName, NickName ";
		$Sql .= " From Personnel ";
		$Sql .= " Where 1 = 1 ";
		$Sql .= " and Account = '$Account'";
		$Sql .= " and PWD = '$Password'";
		$Sql .= " and IdentityTypeId in ('000000000000001', '000000000000002', '000000000000003')";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($SqlRun);
		$rs = $MySql -> db_fetch_array($SqlRun);
	//查無資料
		if(!$rs){
			$success = false;
			$rtnMsg = "帳號或密碼錯誤請重新輸入1！";
		}
	//參數查詢回傳不只一個
		if($RowCount != 1){
			$success = false;
			$rtnMsg = "帳號或密碼錯誤請重新輸入2！";
		}	
		
	//登入成功參數
		if($success){
			$rtnMsg = "登入成功";
		}
//		$URL = "teacher.php";
	//設定Session
		$_SESSION["M_USER_ID"] = $rs["Account"];
		$_SESSION["M_USER_NM"] = $rs["FullName"];
		
	//關閉資料庫連線
		$MySql -> db_close();
	}	
//狀態回執
	header('Content-type: application/xml');
	echo '<?xml version="1.0"?>';
	echo '<response>';
	if ($success){ 
		echo '<resu>1</resu>';
		echo '<rtmsg>'.$rtnMsg.'</rtmsg>';
	}elseif ($success == false ){ 
		echo '<resu>0</resu>';
		echo '<rtmsg>'.$rtnMsg.'</rtmsg>';
	}
	echo '</response>';	
?>