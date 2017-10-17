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
//		程式功能：ct / Upload / 列表
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
//接收參數
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式驗證碼
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));							
	$Action = trim(GetxRequest("Action"));										
	$CourseId = trim(GetxRequest("CourseId"));
	$Index = trim(GetxRequest("Index"));	
//資料庫連線
	$MySql = new mysql();
	
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
<title>擴思訊 Cross Think 後台 使用者管理-客戶</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){

		});

	</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-size: 13px;
}
.style1 {color: #FF3399}
.style2 {color: #FF6699}

.Title01 {
	border: 1px solid #000000;
	font-size: 16px;
	font-weight: bolder;
}
.CSS02 {
	font-size: 12px;
	line-height: 20px;
	height: 1px;
}
.CSS03 {
	background-color: #F7FFE7;
}
.CSS04 {
	background-color: #F7EFD6;
}
.CSS05 {
	background-color: #000000;
}
.TableTitle01 {
	background-color: #0099FF;
	font-size: 15px;
	font-weight: bold;
	color: #003366;
	filter:progid:dximagetransform.microsoft.gradient(gradienttype='0', startcolorstr='#A1D0A1', endcolorstr='#ffffff',starty='0',finishy='10');
	height: 20px;
	text-align:center;
	letter-spacing:1em;
	padding-top:2px;
	border: 1px solid #000000;
}
.Text12 {
	font-size: 12px;
}
a:link {
	font-size: 15px;
	color: #003366;
	text-decoration: none;
}

a:visited {
	color: #003366;
	text-decoration: none;
	font-size: 15px;
}
a:hover {
	color: #FFFFFF;
	background-color: #003366;
	font-size: 15px;
}

.TextField_Locked {
	border: 1px none #FFAE18;
	background-color: #F7EFD6;
}
.CursorHand {
	cursor: hand;
}
.KeyText {
	color: #FF0000;
}
.Filed_Locked {
	background-color: #E7E7E7;
}
.CSS06 {
	background-color: #FFC993;
	font-size: 16px;
}
.TextField01{
	border:1px #99CCFF solid;
}
.Fun-Bar{
	height:30px;
}
.Fun-Bar .inputBTN{
	border:1px solid #666666;
	FILTER:progid:DXImageTransform.Microsoft.Gradient(gradientType='0',startColorstr='#999999',endColorstr='#FFFFFF');
	height:1.8em;
	line-height:1.2em;
	padding:3px;
	text-align:center;
	vertical-align:middle;
	cursor:pointer;
	margin:0 2px;
}
-->

</style>    
</head>
<body>

<table width="100%" border="0" align="center" class="CSS04">
<form method="post" enctype="multipart/form-data" name="form1" action="AppFileUpload.php" runat="server">
	<input id="validatecode" name="validatecode" type="hidden" value="<?php echo $ValidateCode?>">
    <input id="PersonnelId" name="PersonnelId" type="hidden" value="<?php echo $PersonnelId?>">
    <input id="IdentityType" name="IdentityType" type="hidden" value="<?php echo $IdentityType?>">
    <input id="Action" name="Action" type="hidden" value="<?php echo $Action?>">
    <input id="CourseId" name="CourseId" type="hidden" value="<?php echo $CourseId?>">
    <input id="Index" name="Index" type="hidden" value="<?php echo $Index?>">
    <tr>
    	<td colspan="2" class="TableTitle01"><div align="center">upload</div></td>
    </tr>
    <tr class="CSS04">
    	<td>&nbsp;</td>
    	<td><asp:Label runat="server" ID="Msg" class="KeyText" />      </td>
    </tr>
    <tr class="CSS04">
    	<td><div align="right" style="font-size:13PX;">檔案：</div></td>
    	<td><input name="Filedata" id="Filedata" type="file" class="TextField01" runat="server" ></td>
    </tr>
    <tr class="CSS04">
    	<td>&nbsp;</td>
    	<td class="Fun-Bar">
        <input type="submit" value="上傳">
        </td>
    </tr>
    <tr class="CSS04">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</form>
</table>
</body>
</html>
