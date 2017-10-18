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

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
	$USER_ID = $_SESSION["C_USER_ID"];														//管理員 ID
	$USER_NM = $_SESSION["C_USER_NM"];
//資料庫連線
	$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(1, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
//	$PG_NM = GetPG_NM($PG_ID, $MySql);
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey"));
//主檔	: 連接家長
	$Sql = " select M.Id, M.QRCode, T.Account, T.FullName, O.Comment, M.LastEditDate from Authorization M ";
	$Sql .= " left join Personnel T on T.Id = M.ParentId";
	$Sql .= " left join OptionalItem O on O.Id = M.KinshipId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.DeleteStatus = 'N' ";
	$Sql .= " and M.ParentId != '' ";
	$Sql .= " and StudentId = '$DataKey' "; 
	$Sql .= " order by M.Id desc ";
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
<title>Web_園方 教師 自購家長(管理者介面) 班級與學生管理 學生管理 已關聯家長帳號</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){

		});
		
		function varitext(text){
			var Url = '<?php echo $gMainFile; ?>_Print.php?DataKey=' +text
			window.open(Url);
		}
	</script>
</head>

<body>
<div class="main">
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_size_tag.php");?>
	<div class="header themeSet2">
    	<!-- logo與使用者功能 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_logo_user_2.php");?>        
        <!-- logo與使用者功能 end -->
        
        <!-- 主選單 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_topmenu_item_4.php");?>        
        <!-- 主選單 end -->
        
        <!-- 功能軌跡（麵包屑） begin -->
        <div class="breadCrumb">
            <ul>
                <li class="title"><span class="hor-box-text">班級與學生管理</span></li>
                <li class="current"><span class="hor-box-text">學生管理</span></li><!-- 如果沒有，就不寫入這個LI -->
                <li class="current"><span class="hor-box-text">已關聯家長帳號</span></li><!-- 如果沒有，就不寫入這個LI --> 
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料管理操作 begin -->
        <div class="mainOptionTool">
            <ul>
                <li class="new"><button type="button" onclick="varitext('<?php echo $DataKey;?>');"><span class="sized-text-normal">新增授權碼</span></button></li>
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 資料管理操作 end -->
        
        <div class="spacingBlock"></div>
        
        <!-- 資料檢視表格 begin -->
        <div class="dataIndexList themeSet2">
        	<table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
             <form name="DataForm" id="DataForm" method="post">
            	<input type="hidden" id="DataKey" name="DataKey" value="" />
            	<tr>
                	<th>&nbsp;</th>
                    <th class="topHeader"><span class="hor-box-text-large">家長帳號</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">身份</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">家長姓名</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">家長暱稱</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">聯結時間</span></th>
                </tr>
                <?php
					$lineCount = 0;
					while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
				?>
                <tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                	<th valign="middle">
                    	<ul class="operations size-single">
							<li><a href="#" class="remove"><span class="hiddenItem">停用</span></a></li>
                            <!--<li><a href="#" onClick="SetKey('<?php echo $initAry[0];?>');Excute('Open');" <?php if($initAry[5] == "Y"){ echo 'class="check"'?><?php }else{ echo 'class="disable"'?><?php }?>><span class="hiddenItem">啟用</span></a></li>-->
                        </ul>
                    </th>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[2];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[4];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[3];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[3];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo DspDate(substr($initAry[5],0,8),"/").' '. DspTime(substr($initAry[5],8,6),":")?></span></td>
                </tr>
                <?php }?>
            </form>
            </table>
        </div>
        <!-- 資料檢視表格 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
