<?php
/*****************************************************************************************
'		撰寫人員：Juso
'		撰寫日期：20140115
'		程式功能：eBooking / 系統管理 / 首頁
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
//去掉路徑及副檔名
	$gMainFile = basename(__FILE__, '.php');
	
	if(trim($_SESSION["M_USER_ID"]) == ''){
		header("Location:Member_Logout.php");
	}
	if(trim($_SESSION["M_USER_NM"]) == ''){
		header("Location:Member_Logout.php");		
	}
?>
<html>
<head>
<title><?php echo MetaTitle; ?> 使用者維護介面</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
</head>
<body>
	<div id="DivMain">
		<div id="FrameTop">
			<span id="FrameTopLeft">目前使用者為：<?php echo $_SESSION["M_USER_NM"] . '(' . $_SESSION["M_USER_ID"] . ')'; //if($_SESSION["OprtT_USER_NM"]!=''){echo $_SESSION["OprtT_USER_NM"].'('. $_SESSION["OprtT_USER_ID"] . ')';}else{ echo $_SESSION["M_USER_NM"].'('. $_SESSION["M_USER_ID"] . ')';} ?>　<!--最後登入時間：<?php //echo date("M, d", $MKTime); ?>--></span>
			<span id="FrameTopRight"><img src="/Images/Icon_01.png" align="absmiddle"><a href="Member_Logout.php" target="_parent" class="LFFFFFF" onFocus="this.blur();">登出系統</a></span>
		</div>
		<div id="FrameLeft"><iframe frameborder="0" width="208" src="User_Menu.php" marginheight="0" marginwidth="0" scrolling="no" allowtransparency="no" name="Left" id="Left"></iframe></div>
		<div id="FrameLine"></div>
		<div id="FrameLineImg" align="center"><a href="#" class="FrameLineImgLink" onFocus="this.blur();"><img src="/Images/icon_25.gif" width="3" height="45" border="0"></a></div>
		<div id="FrameRight"><iframe frameborder="0" src="Menu_Welcome.php" marginheight="0" marginwidth="0" scrolling="auto" allowtransparency="no" name="main" id="main"></iframe></div>
	</div>
</body>
</html>


