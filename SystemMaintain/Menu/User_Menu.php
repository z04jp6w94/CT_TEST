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
//		撰寫日期：20130114
//		程式功能：eBooking / 後台登入
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
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>選單</title>
	<base target="main">
	<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<style type="text/css">
	<!--
	.style1 {color: #8DC63F}
	#TreeView {
		position:relative;
		width:200px;
		text-overflow: ellipsis;
		overflow: auto;
		overflow-x:hidden;
		font-family: Verdana, Arial, "新細明體", "標楷體";
		font-size: 9pt;
		vertical-align: baseline;
		border: 1px dotted #CCC;
	}
	.TreeviewSysFile, .TreeviewFncFile{
		cursor: pointer;
	} 
	.TreeviewSpan{
		cursor:pointer;
		width:200px;
		text-overflow: ellipsis;
		overflow: hidden;
	} 
	#Space{
		padding-top: 5px;
	}
	A.black9:link   {color: #000000; font-size: 9pt; font-family:"arial";text-decoration:none;}
	A.black9:visited{color: #000000; font-size: 9pt; font-family:"arial";text-decoration:none;}
	A.black9:hover  {color: #CC3333; font-size: 9pt; font-family:"arial";text-decoration:underline;}
	A.black9:active {color: #000000; font-size: 9pt; font-family:"arial";text-decoration:none;}
	.TableLine {
		border-right-width: 1px;
		border-right-style: solid;
		border-right-color: #CCCCCC;
	}
	-->
	</style>
</head>
<body ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=true" onselectstart="event.returnValue=false">
<table width="208" height="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="TableLine">
  <tr>
    <td height="30"><a href="Menu_Welcome.php" target="main" onFocus="this.blur()"><img src="../../Images/Logo.png"  height="30" border="0"></a></td>
  </tr>
  <tr>
	<td valign="top">
		<!-- 選單開始 -->
		<div id="TreeView">
			<div id="Space"></div>
			<nobr>
			<?php echo GetTreeCont();?>
			</nobr>
		</div>
		</td>
      </tr>
      <tr>
        <td height="8"></td>
      </tr>
	</table>
</body>
</html>

