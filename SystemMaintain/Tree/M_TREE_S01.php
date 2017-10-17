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
//		撰寫日期：20131126
//		程式功能：連丰 / 系統使用者 / 列表
//		使用參數：None
//		資　　料：sel：MUM01, MUM02, CODE_VALID
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
	$PG_ID = trim(GetxRequest("PG_ID"));													//程式	 ID (GPID)
	if($PG_ID != ""){
		setcookie("M_PG_ID", $PG_ID, time() + 3600 * 24);
		setcookie("ToPage" . $gMainFile, 0, time() + 3600 * 24);
	}else{
		$PG_ID = $_COOKIE["M_PG_ID"];
	}
//資料庫連線
	$MySql = new mysql();
//使用權限
	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
	ChkFunc_BeforeRunPG(1, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
	$PG_NM = GetPG_NM($PG_ID, $MySql);
//定義一般參數
	$sUSER_ID = trim(GetxRequest("sUSER_ID"));
	$sUSER_NM = trim(GetxRequest("sUSER_NM"));
	$sGP_ID = trim(GetxRequest("sGP_ID"));
	$sToPage = trim(GetxRequest("ToPage"));
	$PageCond = "";
	$myWhere = " Where 1 = 1 ";
//後台帳號群組主檔
/*
	$Sql = " Select GP_ID, GP_NM ";
	$Sql .= " From MUM02 ";
	$Sql .= " order by GP_ID ";
	$MUM02Run = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
*/
//代碼檔 啟用狀態
/*
	$Sql = " Select VALID_STATUS, VALID_STATUS_DESC ";
	$Sql .= " From code_valid ";
	$Sql .= " order by VALID_STATUS desc ";
	$CodeValidRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
*/
//程式功能目錄
	$Sql = " Select a.GP_ID, a.GP_NM, Case When b.GP_ID is null Then 'Y' Else 'N' End ";
	$Sql .= " From mum02 a ";
	$Sql .= " Left join ( ";
	$Sql .= " Select a.GP_ID From mus02 a ";
	$Sql .= " Left join m_tree_s01 b on a.GP_ID = b.GP_ID and a.PG_ID = b.PG_ID ";
	$Sql .= " Left join mum03 c on a.PG_ID = c.PG_ID ";
	$Sql .= " Where b.GP_ID is null ";
	$Sql .= " Group by a.GP_ID) b on a.GP_ID = b.GP_ID ";
	$Sql .= " Order by a.GP_ID ";

/*
	$Sql = " Select ";
	$Sql .= " a.USER_ID, a.USER_NM, a.USER_NICK, ifnull(b.GP_ID,''), VALID ";
	$Sql .= " From MUM01 a ";
	$Sql .= " Left join MUS01 b on a.USER_ID = b.USER_ID ";
	$Sql .= $myWhere;
	$Sql .= " order by a.USER_ID ";
*/
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
//分頁設定
	$wPageSize = 20;
	SetPage($RowCount, $wPageSize, $Sql, $MySql);
//關閉資料庫連線
	$MySql -> db_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo MetaTitle; ?></title>
	<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		function SetKey(pVal){
			DataForm.DataKey.value = pVal;
		}
		function Excute(wFunc){
			switch(wFunc){
				case 'Modify':
					DataForm.action='<?php echo $gMainFile; ?>_Modify.php';
					DataForm.submit();
					break;
				case 'Delete':
					DataForm.action='<?php echo $gMainFile; ?>_Delete.php';
					checkBeforeSubmit(DataForm,'delVal','刪除')
					break;
				default:
			}
		}
	</script>
</head>
<body>
<div id="MainTip">
</div>
<div id="Main">
	<div id="MainTopMenu">
		<span class="MenuLeft">
			<ul class="Menunav">
				<?php if($CanDelete == ""){ ?><li class="gray" id="CmBtnDel" style="display: none;"><a class="L333333 SetCursor" onClick="Excute('Delete');">刪除</a></li><?php }?>
				<li class="gray"><a class="SetCursor" onClick="ToOtherPage('F');" title="第一頁" id="tipBtnF"><img src="/Images/Icon_05.png" width="28" height="28" border="0" align="absmiddle"></a></li>
				<li class="gray"><a class="SetCursor" onClick="ToOtherPage('P');" title="上一頁" id="tipBtnP"><img src="/Images/Icon_06.png" width="28" height="28" border="0" align="absmiddle"></a></li>
				<li class="gray"><a class="SetCursor" onClick="ToOtherPage('N');" title="下一頁" id="tipBtnN"><img src="/Images/Icon_07.png" width="28" height="28" border="0" align="absmiddle"></a></li>
				<li class="gray"><a class="SetCursor" onClick="ToOtherPage('E');" title="最後頁" id="tipBtnE"><img src="/Images/Icon_08.png" width="28" height="28" border="0" align="absmiddle"></a></li>               
			</ul>
		</span>
		<span class="MenuRight">
			到第 <select id="PageNum" name="PageNum" onChange="ToOtherPage(this.value);"><?php for($j=1;$j<=$gPageCnt;$j++){?><option value="<?php echo $j;?>"<?php if($gCurrentPage == $j){ echo " selected";}?>><?php echo $j ;?></option><?php }?></select> 頁
		</span>
	</div>
	<div id="MainTitle">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" background="/Images/TdBg01.gif">
			<tr class="power-Font03" height="29">
				<td width="50" class="TRMM-td03"><input type="checkbox" id="checkAll" name="checkAll" onClick="changCheckboxV(DataForm,'delVal',this.checked);" value="ALL"></td>
				<?php if($CanUpdate == ""){ ?><td width="50" class="TRMM-td01">修改</td><?php }?>
                <td width="50" class="TRMM-td01">已設定</td> 
                <td width="100"class="TRMM-td01">群組ID</td>
                <td class="TRMM-td01">群組名稱</td>   
			</tr>
			<tr>
				<td height="1" colspan="5" bgcolor="#E2E2E2"></td>
			</tr>
		</table>
	</div>    
    <div id="MainDesc">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="power-Font03">
		<form name="DataForm" id="DataForm" method="post">
			<input type="hidden" id="ToPage" name="ToPage" value="<?php echo $gCurrentPage; ?>" />
			<input type="hidden" id="DataKey" name="DataKey" value="" />
			<input type="hidden" id="DataCnt" name="DataCnt" value="<?php echo $gPageLast - $gPageFirst; ?>" />
			<?php
				$lineCount = 0;
				while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
			?>
			<tr<?php if($lineCount % 2 == 1){ echo ' bgcolor="#F1F5FA"';}?> onMouseOut="cbar(this)" onMouseOver="sbar(this)">
				<td width="50" height="28" class="TRMM-td03"><input type="checkbox" id="delVal<?php echo $lineCount?>" name="delVal<?php echo $lineCount?>" value="<?php echo $initAry[0]; ?>" onClick="SetMenunavBtn();" /></td>
				<?php if($CanUpdate == ""){ ?><td width="50" class="TRMM-td01"><a href="#" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('Modify');return false;">
				<img src="/Images/editicon.gif" width="16" height="15" border="0">
                </a></td><?php }?>
                <td width="50" class="TRMM-td01"><?php echo $initAry[2]; ?></td>
                <td width="100" class="TRMM-td01"><?php echo $initAry[0]; ?></td>
                <td class="TRMM-td01 TRMM-alignLeft"><?php echo $initAry[1]; ?></td>
			</tr>
			<?php }?>
			<tr>
				<td height="1" colspan="18" bgcolor="#E2E2E2"></td>
			</tr>
		</form>
		</table>
	</div>
</div>

</body>
</html>

