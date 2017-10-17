<?php
    header ('Content-Type: text/html; charset=utf-8');
    session_start(); 
    //函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
    //路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
    //	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
    //資料庫連線
	$MySql = new mysql();

    $result = GetRoleFunctionsEnabled("000000000000001","000000000000001",1,$MySql);
    //關閉資料庫連線
	$MySql -> db_close();
    echo $result;
?>