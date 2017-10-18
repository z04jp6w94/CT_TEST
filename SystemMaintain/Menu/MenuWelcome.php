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
//		撰寫人員：Momo
//		撰寫日期：20151111
//		程式功能：psbfrich / 課程管理 / 列表
//		使用參數：None
//		資　　料：sel：manager_m
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//定義全域參數
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
	$USER_NM = $_SESSION["M_USER_NM"];
	$USER_ROLE = $_SESSION["M_USER_ROLE"];
//參數
//資料庫連線
	$MySql = new mysql();
//使用權限
	Chk_Login($USER_ID);															//檢查是否有登入後台，並取得允許執行的權限
	//ChkFunc_BeforeRunPG(1, $PG_ID, $USER_ID, $MySql);								//程式使用權限
	GetTreeView($USER_ROLE,$MySql);

	$MSG = $_SESSION['MSG'];
	$_SESSION['MSG'] = '';
//關閉資料庫連線
	$MySql -> db_close();
?>
<!DOCTYPE HTML>
<!--[if lt IE 9]>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>擴思訊 Cross Think 後台 登入成功</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){
			JumpMSG('<?php echo $MSG;?>');
		});
	</script>
<!-- #include file="../incs/mutual_css.inc" -->
</head>

<body>
<div class="main">
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_size_tag.php");?>
<!-- #include file="../incs/header_size_tag.inc" -->
	<div class="header themeSet1">
    	<!-- logo與使用者功能 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_logo_user.php");?>
<!-- #include file="../incs/header_logo_user.inc" -->
        <!-- logo與使用者功能 end -->
        
        <!-- 主選單 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_topmenu_item_5.php");?>
<!-- #include file="../incs/header_topmenu_item_5.inc" -->
        <!-- 主選單 end -->
        
        <!-- 功能軌跡（麵包屑） begin -->
        <div class="breadCrumb">
            <ul>
                <!--<li class="title"><span class="hor-box-text">教案管理</span></li>-->
                <!-- <li class="current"><span class="hor-box-text">新增教案</span></li>如果沒有，就不寫入這個LI --> 
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>
<!-- #include file="../incs/header_system_helper.inc" -->
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
