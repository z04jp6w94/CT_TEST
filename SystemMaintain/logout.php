<?php 	
include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");

	$_SESSION["M_USER_ID"] = '';
	$_SESSION["M_USER_NM"] = '';
	
	header("Location:/SystemMaintain/Menu/MemberLogin.php"); 
	
?>
