<?php
/*****************************************************************************************
'		撰寫人員：Juso
'		撰寫日期：20140115
'		程式功能：ebooking / 系統管理 / 登出
'		使用參數：None
'		資　　料：sel：power_m
'		　　　　　ins：None
'		　　　　　del：None
'		　　　　　upt：None
'		修改人員：
'		修改日期：
'		註　　解：
'****************************************************************************************/
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
	
	$_SESSION["M_USER_ID"] = '';
	$_SESSION["M_USER_NUM"] = '';
	$rtnMsg="登出完成";
	header("Location:MemberLogin.php?rtnMsg=" . $rtnMsg);	
?>
