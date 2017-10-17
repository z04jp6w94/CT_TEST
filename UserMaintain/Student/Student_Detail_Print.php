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
	$ExID = $_SESSION["C_USER_ID"];															//操作 ID
	$ExDate = date("Ymd");																	//操作 日期
	$ExTime = date("His");																	//操作 時間
	$USER_NM = $_SESSION["C_USER_NM"];	
//資料庫連線
	$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(1, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
//	$PG_NM = GetPG_NM($PG_ID, $MySql);
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey"));			//學生KEY	
//班級名稱
	$Sql = " select T.ClassName from ClassMember M ";
	$Sql .= " left join Class T on T.Id = M.ClassId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.StudentId = '".$DataKey."' ";
	$Run = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClassNM = $MySql -> db_result($Run);
//學生姓名
	$Sql = " select Name from Student ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$DataKey."' ";
	$SqlRun = $MySql -> db_query($Sql) or die("");
	$Name = $MySql -> db_result($SqlRun);		
//取得亂數QRCODE
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}		
	
	$QRCODE = generateRandomString();
	
//寫入 Authorization
	$Sql = " insert into Authorization ";
	$Sql .= " (Id, StudentId, Enable, QRCode, DeleteStatus, CreatorId, CreateDate, LastEditorId, LastEditDate, Used ) ";
	$Sql .= " values";
	$Sql .= " ('', '".$DataKey."', 'Y', '".$QRCODE."', 'N', '".$ExID."', '".$ExDate.$ExTime."', '".$ExID."', '".$ExDate.$ExTime."', 'N' ) ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");

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
<title>邀請函</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){

		});
		window.print();
		setTimeout ("CloseWin()" , 1000);
		function CloseWin()
		{
		window.opener=null;
		window.close();
		}
	</script>
<style type="text/css">
<!--
.clearFloat {
  clear: both;
  margin: 0;
  padding: 0;
  line-height: 0;
  font-size: 0;
  height: 0;
}
body{
	background:#ff;
	margin:50px 0;
}
.A4{
	width: 210mm/*2480px*/;
	height: 297mm/*3508px*/;
	/*
	width: 2480px210mm;
	height: 3508px297mm;
	*/
	/*overflow:hidden;*/
}
.page{
	display: block;
	margin: 0 auto;
	box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	background: transparent;
}
.breakPage{
	display:none;
}
.wrapper{
	/*padding:0.5cm;*/
	position:relative;
	width:100%;
	height:100%;
	/*border:#747263 solid 1px;*/
}
.header,
.header h1,
.header > div,
.hrLine,
.ctx,
.ctx .steps dt,
.ctx .steps dd{
	position:absolute;
}
/* 區塊尺寸與定位 brgin */
.header{
	top:0;
	left:0;
	width:100%;
	height:9.7%;/*326/3350*/
}
.header h1{
	top:19.3%;/*78/404*/
	left:4.2%;/*105/2480*/
	width:56.4%;/*1400/2480*/
	height:100%;
}
.header > div{
	top:19.3%;/*78/404*/
	right:4.2%;/*105/2480*/
	width:34.2%;/*850/2480*/
	height:100%;
}
.hrLine{
	top:9.7%;/*326/3350*/
	left:0;
	width:100%;
	height:2%;/*70/3350*/
}
.ctx{
	top:13.7%;/*459/3350*/
	left:0;
	width:100%;
	height:86.3%;/*2893/3350*/
}
.ctx .article,
.ctx .steps,
.ctx .rules{
	position:absolute;
	
}
.ctx .article{
	top:3.4;/*100/2893*/
	left:4.4%;/*101/2270*/
	width:91.1%;/*2068/2270*/
	height:12.7%;/*370/2893*/
}
.ctx .sp1{
	top:17.5%;/*508/2893*/
	left:4.4%;/*101/2270*/
	width:53.3%;/*1210/2270*/
	height:24.7%;/*717/2893*/
}
.ctx .sp2{
	top:17.5%;/*508/2893*/
	right:4.4%;/*101/2270*/
	width:34.5%;/*783/2270*/
	height:24.7%;/*717/2893*/
}
.ctx .sp3{
	top:44.4%;/*1287/2893*/
	left:4.4%;/*101/2270*/
	width:91.1%;/*2068/2270*/
	height:24.7%;/*717/2893*/
}
.ctx .steps dt{
	top:0;
	left:0;
	width:100%;
	height:23.6%;/*168/709*/
}
.ctx .steps dd{
	top:23.6%;
	left:0;
	width:100%;
	height:76.4%;
}
.ctx .rules{
	top:74.0%;/*2142/2893*/
	left:4.4%;/*101/2270*/
	width:91.1%;/*2068/2270*/
	height:22.1%;/*640/2893*/
}
/* 區塊尺寸與定位 end */

/* 區塊細節 begin */
.hrLine{
	background-color:#f8cee0;
}
.header > div img{
	display:block;
	width:100%;
	height:auto;
}
.ctx .steps,
.ctx .rules{
	border:#747263 solid 1px;
}
.ctx .steps dt{
	text-align:center;
	color:#fff;
	background-color:#9fa0a0;
}
.ctx .sp1 .qrcodeList{
	/*display:block;
	margin:0 auto;
	*/
	margin:0 auto;
	width:70%;
}

.ctx .sp1 .qrcodeList li{
	width:48%;
	position:relative;
}
.ctx .sp1 .qrcodeList li.android{
	float:left;}
.ctx .sp1 .qrcodeList li.ios{
	float:right;}
.ctx .sp1 .qrcodeList li span{
}
.ctx .sp1 .qrcodeList li img{
	position:absolute; width:100%; height:auto; top:40px; left:0;
}
.ctx .sp2 dd{
	text-align:center;
}
.ctx .sp2 dd p{
	margin-top:85px;
}
.ctx .sp3 dd .infoList{
	margin:0 auto;
	margin-top:20px;
	width:90%;
	height:80%;
}
.ctx .sp3 dd .infoList li{
	width:100%;
	height:100%;
}
.ctx .sp3 dd .infoList li.pic{
	float:left;
	text-align:center;
	border-right:#747263 solid 1px;
}
.ctx .sp3 dd .infoList li img{
	width:auto; height:100%;
}
.ctx .sp3 dd .infoList li.text{
	float:inherit;
	text-align:center;
}
.ctx .rules p{
	margin:0 2.5%;
	margin-top:1%;
	margin-bottom:0;
}
/* 區塊細節 end */

/* 區塊文字設定 begin */
.header h1{
	font-size:24pt;
	padding-top:15px;
}
.article p{
	font-size:16pt;
	line-height:1.6;
}
.steps dt span{
	font-size:20pt;
}
.steps dd p{
	font-size:18pt;
}
.ctx .sp1 .qrcodeList li span{
	display:block;
	padding-top:10px;
	text-align:center;
	font-size:18pt;
}
.ctx .rules p{
	font-size:13pt;
	line-height:1.5;
}
/* 區塊文字設定 end */

/* 區塊文字垂直置中 begin */
.header h1:before,
.ctx .steps dt:before,
.ctx .sp3 dd .infoList li.text:before/*,
.ctx .sp2 dd:before*/{
  content: ' ';
  display: inline-block;
  vertical-align: middle;
  width: 0;
  height: 100%;
  /*搭配:before 擬元素的垂直置中效果part1*/
}
.header h1 span,
.ctx .steps dt span,
.ctx .sp3 dd .infoList li.text p/*,
.ctx .sp2 dd p*/{
  display: inline-block;
  vertical-align: middle;
  /*搭配:before 擬元素的垂直置中效果part2*/
}
/* 區塊文字垂直置中 end */

@media print {
  body,
  .page {
    margin: 0;
    box-shadow: 0;
  }
  .breakPage{
	  display:block;
	  page-break-after: always;
	}
  .ctx .steps dt{
		color:#000;
		background-color:transparent;
		border-bottom:#747263 solid 1px;
	}
	.hrLine{
		background-color:transparent;
	}
}
-->
</style>
</head>

<body>
<div class="page A4">
	<div class="wrapper">
    	<div class="header">
        	<h1><span>【<?php echo $ClassNM;?>】邀請函</span></h1>
            <div><img src="images/print_state_logo.gif"></div>
        </div>
        <div class="hrLine" id="hrLine"></div>
        <div class="ctx">
        	<div class="article">
            	<p>學生姓名 : <?php echo $Name;?><br>
            	誠摰地邀請您一起來使用，由劍聲集團所開發的《擴思訊3~6 APP系統》！
根據手機系統的不同，掃描並安裝合適的版本，即可加入使用！
1對多的體貼設計，爸爸、媽媽、爺爺、奶奶、外公、外婆等可同時安裝，一起來關心寶貝！
</p>
            </div>
            <dl class="steps sp1">
            	<dt>
                	<span>Step1 下載家長版APP</span>
                </dt>
                <dd>
                	<ul class="qrcodeList">
                    	<li class="android">
                        	<span>Android</span>
                            <img src="images/170012174746.jpg">
                        </li>
                        <li class="ios">
                        	<span>iOS</span>
                            <img src="images/170012174746.jpg">
                        </li>
                        <br class="clearFloat">
                    </ul>
                </dd>
            </dl>
            <dl class="steps sp2">
            	<dt>
                	<span>Step2</span>
                </dt>
                <dd>
                	<p>註冊家長帳號</p>
                </dd>
            </dl>
            <dl class="steps sp3">
            	<dt>
                	<span>Step3 與寶貝連結</span>
                </dt>
                <dd>
                	<ul class="infoList">
                    	<!--<li class="pic"><img src="http://chart.googleapis.com/chart?cht=qr&chl=<?php echo $QRCODE;?>&chld=L|0&chs=30x30" border="0"></li>-->
                        <li class=" text"><p>帳戶授權碼<br><?php echo $QRCODE;?></p></li>
                        <br class="clearFloat">
                    </ul>
                </dd>
            </dl>
            <div class="rules">
            	<p>《擴思訊3~6 APP系統》是以福祿貝爾和蒙台梭利教學法為核心所開發，透過APP的使用，讓您輕鬆掌握您家寶貝的學習進度，以及在肌肉運動、認知記憶、數學邏輯、人際溝通、創意想像五大領域的發展表現哦！現在，就跟我們一起來！</p>
            </div>
        </div>
    </div>
</div>
<div class="breakPage"></div>
</body>
</html>