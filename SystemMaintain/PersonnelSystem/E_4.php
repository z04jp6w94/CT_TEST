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
//		撰寫日期：20140726
//		程式功能：ct / 教師管理 / 列表
//		使用參數：None
//		資　　料：sel：ad_m
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
	$CanAddNew = " disabled";
	$CanUpdate = " disabled";
	$CanDelete = " disabled";
	$gCurrentPage;																			//目前頁碼
	$gPageCnt;																				//總頁數
	$gPageFirst;																			//目前顯示筆數 first
	$gPageLast;																				//目前顯示筆數 last
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
//	$PG_ID = trim(GetxRequest("PG_ID"));													//程式	 ID (GPID)
//	if($PG_ID != ""){
//		setcookie("M_PG_ID", $PG_ID, time() + 3600 * 24);
//		setcookie("ToPage" . $gMainFile, 0, time() + 3600 * 24);
//	}else{
//		$PG_ID = $_COOKIE["M_PG_ID"];
//	}
//資料庫連線
	$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(1, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
//	$PG_NM = GetPG_NM($PG_ID, $MySql);
//定義一般參數
	$sNM = trim(GetxRequest("sNM"));
	$sToPage = trim(GetxRequest("ToPage"));
	$PageCond = "";
	$myWhere = " Where 1 = 1 ";
//搜尋條件
	if($sNM != ""){
		$PageCond .= "&sNM=" . $sNM;
		$myWhere .= " and ( FullName like '%" . $sNM . "%' or Account like '%" . $sNM . "%' )";
	}
//主檔	: 最新消息
	$Sql = " 
		Select
			Id, FullName, Account, CreateDate, LastEditorId, LastEditDate
		From Personnel
	";
	$Sql .= $myWhere;
	$Sql .= " and DeleteStatus = 'N'";
	$Sql .= " and IdentityTypeId not in ('000000000000000', '000000000000001', '000000000000002', '000000000000003', '000000000000004')";
	$Sql .= "
		order by Id desc
	";
//	echo $Sql;	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
//分頁設定
//	$wPageSize = 20;
//	SetPage($RowCount, $wPageSize, $Sql, $MySql);
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
<title>擴思訊 Cross Think 後台 使用者管理-劍聲 角色權限</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
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
                <li class="title"><span class="hor-box-text">使用者管理-劍聲</span></li>
                <li class="current"><span class="hor-box-text">角色權限</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>
<!-- #include file="../incs/header_system_helper.inc" -->
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 使用者管理-劍聲　角色權限 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/doc_system_definiton_user_jiansheng_themeset1.php");?>
<!-- #include file="../incs/doc_system_definiton_user_jiansheng_themeset1.inc" -->
        <!-- 使用者管理-劍聲　角色權限 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
