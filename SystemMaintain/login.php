<?php
//
//　      _    (_)_(_)
//　 ___ | |__  _| |_ _ _ _ __ _ _ __
//　/ __\| __ \| | | | ' ' / _` | '_ \     
//　| |_ | | | | | | | | |  (_| | | | |	 taipei 
//　\___/|_| |_|_|_|_|_|_|_\__,_|_| |_|     2013
//　www.chiliman.com.tw
// 
//*****************************************************************************************
//		撰寫人員：JUSO
//		撰寫日期：20130120
//		程式功能：ebooking / 後台登入
//		使用參數：None
//		資　　料：sel：power_m
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
//去掉路徑及副檔名
	$gMainFile = basename(__FILE__, '.php');
	
	$rtnMsg = trim(GetxRequest("rtnMsg", ""));
?>
<!DOCTYPE HTML>
<!--[if lt IE 9]>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>擴思訊 Cross Think 後台 登入</title>
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script language="JavaScript">
	//document ready
		$(document).ready(function() {           
            
		});
		function ChkForm(){		
			$.post(
				$("#DataForm").attr('action'),
				$("#DataForm").serialize(),
				function(xml){
					//alert($('resu', xml).text());
					if($('resu', xml).text() == '1'){
						alert($('rtmsg', xml).text());
						window.location.replace("A_1.html");
					}else{
						alert($('rtmsg', xml).text());
					}
				}
			);			
		}
	</script>
</head>

<body><div class="cover">
    	<div class="logo"><img src="images/login_logo_main.jpg"></div>
        <form name="DataForm" id="DataForm" method="post" action="login_Action.php">
        <div class="account"><input type="text" id="Account" name="Account" placeholder="帳號" value=""></div>
        <div class="pwd"><input type="password" id="Password" name="Password" placeholder="密碼" value=""></div>
        </form>
        <button type="button" class="login mainLogin" onClick="ChkForm()"><span>登入</span></button>
        <button type="button" class="findPwd smallSize"><span>忘記密碼</span></button>
    </div>
    
</body>
</html>
