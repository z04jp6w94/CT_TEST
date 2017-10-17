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
//		撰寫日期：20140728
//		程式功能：luckysparks / 最新消息 / 新增
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//
	
	$MaxFileSize=1024*1024*1024;//預設最大檔案大小 2M bytes
	$MaxSize=1500; //預設最大圖片長寬
	$ObjNM = GetxRequest("ObjNM");
	$UpLoadBtnNM = GetxRequest("UpLoadBtnNM");
	$DeleteBtnNM = GetxRequest("DeleteBtnNM");

		if(GetxRequest("ObjNM")== '' || GetxRequest("UpLoadBtnNM")=='' || GetxRequest("DeleteBtnNM")==''){
			echo '<script>';
			echo 'alert("參數錯誤");';
			echo 'window.close();';
			echo '</script>';
			exit();	
		}
		
		if (GetxRequest("MaxFileSize") != '' ){
			$MaxFileSize=GetxRequest("MaxFileSize") * 1024; //(單位 K bytes)
		}
		
		if(GetxRequest("MaxSize")<>""){
			$MaxSize=GetxRequest("MaxSize");
		}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>檔案上傳</title>

<link href="/Css/Maintain/Maintain.css" rel="stylesheet" type="text/css">

<SCRIPT language=JavaScript> 

　　<!-- 

　　var requestsubmitted=false; 

　　function submit_Validator(theForm){ 

	　　//檢查是否從新提交 

	　　if (requestsubmitted==true){ 

	　　	alert("檔案上傳中，請等待伺服器應答！"); 

	　　	return(false); 

	　　} 

	　　requestsubmitted=true; 

	

	　　return (true); 

　　} 

	function setMsg(){

		if(jMsg) jMsg.innerHTML='<font color=red>檔案上傳中請稍候...</font>';

	}

	function ClearjMsg(){

		if(jMsg) jMsg.innerText.text='';

	}



　　//--> 

</SCRIPT> 

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

	margin:2px 2px 0px;

}

-->



</style></head>



<body onLoad="window.focus();" >

<table width="100%" border="0" align="center" cellpadding="0" class="CSS04">

<form method="post" enctype="multipart/form-data" name="form1" action="UpLoad_Write.php" runat="server">
<input id="ObjNM" name="ObjNM" type="hidden" value="<?php echo $ObjNM?>">
<input id="UpLoadBtnNM" name="UpLoadBtnNM" type="hidden" value="<?php echo $UpLoadBtnNM?>">
<input id="DeleteBtnNM" name="DeleteBtnNM" type="hidden" value="<?php echo $DeleteBtnNM?>">
  <tr >

    <td colspan="2" class="TableTitle01"><div align="center">檔案上傳</div></td>

    </tr>

  <tr class="CSS04">

    <td colspan="2">　

      <asp:Label ID="Msg" runat="server" class="KeyText" /></td>

    </tr>

  <tr class="CSS04">

    <td colspan="2">　請選擇要上傳的檔案(最大檔案<span class="style2"><?php echo ($MaxFileSize/1024)/1024?>MB</span> bytes)<br>

<input name="hiddenField" type="hidden" value="0"></td>

    </tr>

  <tr class="CSS04">

    <td width="21%"><div align="right">檔案：</div></td>

    <td width="79%"><input name="FileUp" id="FileUp" type="file" class="TextField01" runat="server"></td>

  </tr>

  <tr class="CSS04">

    <td>&nbsp;</td>

    <td class="Fun-Bar"><!--<asp:Button runat="server" text="上傳" OnClick="UploadFile" CssClass="inputBTN" />-->
      <input type="submit" name="Submit1" value="上傳" class="inputBTN">
      <input type="button" name="Submit2" value="取消" class="inputBTN" onClick="window.close();">

     <!-- <asp:CheckBox ID="IsCover" Text='覆蓋同檔名檔案' TextAlign="right" runat="server" Enabled="true" />--><br>        

      </td>

  </tr>

  <tr class="CSS04">

    <td>&nbsp;</td>

    <td><span id="jMsg"></span></td>

  </tr>

  <tr class="CSS04">

    <td>&nbsp;</td>

    <td>&nbsp;</td>

  </tr>

</form>

</table>



</body>

</html>

