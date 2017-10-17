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
//		撰寫日期：20150324
//		程式功能：網站模組 / 公用程式 / 警告視窗
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
//定義全域參數

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$CbFN = trim(GetxRequest("CbFN"));														//callback function
	$CbIndex = trim(GetxRequest("CbIndex"));												//callback 物件序號
	$CbDelUrl = trim(GetxRequest("CbDelUrl"));												//callback 執行 URL
	$no = trim(GetxRequest("no"));															//callback 執行 no
	$MsgType = trim(GetxRequest("MsgType"));												//共用預設訊息
	$MsgIcon = trim(GetxRequest("MsgIcon"));												//共用預設圖案
	$MsgContent1 = trim(GetxRequest("MsgContent1"));										//共用預設訊息 : 自訂訊息 1
	$MsgContent2 = trim(GetxRequest("MsgContent2"));										//共用預設訊息 : 自訂訊息 2

//共用預設訊息
	switch($MsgType){
	//刪除確認
		case '1':
			$PopupMsg = '<td style="padding: 30px 20px 0px 0px; font:20px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif; font-weight: 800;">您確定要永久刪除此資料？<br /><span style="font:16px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif;">這個動作無法還原。</span></td>';
			$BtnType = '1';
			break;
	//資料刪除失敗
		case '2':
			$PopupMsg = '<td style="padding: 30px 20px 0px 0px; font:20px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif; font-weight: 800;">資料刪除失敗！<br /><span style="font:16px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif;">系統忙碌中請稍候再試。</span></td>';
	//自訂
		case 'options':
			$PopupMsg = '<td style="padding: 30px 20px 0px 0px; font:20px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif; font-weight: 800;">' . $MsgContent1 . '<br /><span style="font:16px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif;">' . $MsgContent2 . '</span></td>';
			break;
	//預設值
		default:
			$PopupMsg = '<td style="padding: 30px 20px 0px 0px; font:20px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif; font-weight: 800;">您確定要永久刪除此資料？<br /><span style="font:16px/22px \'微軟正黑體\', Arial, Helvetica, sans-serif;">這個動作無法還原。</span></td>';
			$BtnType = '1';
	}
//共用預設圖案
	switch($MsgIcon){
	//刪除確認
		case '1':
			$PopupIcon = '<td style="padding: 30px 25px 0px 25px;"><img src="' . BackOfficeUIImg . 'Icon_Warning01.png" width="58" height="41" /></td>';
			break;
	//資料刪除失敗
		case '2':
			$PopupIcon = '<td style="padding: 30px 25px 0px 50px;"><img src="' . BackOfficeUIImg . 'Icon_DeleteError01.png" width="39" height="41" /></td>';
	//預設值
		default:
			$PopupIcon = '<td style="padding: 30px 25px 0px 25px;"><img src="' . BackOfficeUIImg . 'Icon_Warning01.png" width="58" height="41" /></td>';
	}
//共用預設按鈕型式
	switch($BtnType){
	//刪除 + 取消
		case '1':
			//$PopupBtn = '<li class="red"><a onclick="parent.' . $CbFN . '(' . $CbIndex . ', \'' . $CbDelUrl . '\');" class="LFFFFFF SetCursor">刪除</a></li>';
			$PopupBtn = '<li class="red"><a onclick="parent.' . $CbFN . '(' . $CbIndex . ', \'' . $CbDelUrl . '\', \'' . $no . '\');" class="LFFFFFF SetCursor">刪除</a></li>';
			$PopupBtn .= '<li class="gray"><a onclick="window.parent.Shadowbox.close()" class="L333333 SetCursor">取消</a></li>';
			break;
	//確認
		case '2':
			$PopupBtn .= '<li class="ble"><a onclick="window.parent.Shadowbox.close()" class="LFFFFFF SetCursor">確認</a></li>';
			break;
		default:
			$PopupBtn = '<li class="red"><a onclick="parent.' . $CbFN . '(' . $CbIndex . ', \'' . $CbDelUrl . '\', ' . $no . ');" class="LFFFFFF SetCursor">刪除</a></li>';
			$PopupBtn .= '<li class="gray"><a onclick="window.parent.Shadowbox.close()" class="L333333 SetCursor">取消</a></li>';
	}
//關閉資料庫連線
	$MySql -> db_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo SysTitle; ?></title>
	<?php include_once(account_path . "/CommonPage/MaintainMeta.php");?>
</head>
	<link rel="stylesheet" type="text/css" href="/Css/shadowboxByBackOffice.css" />
    <link rel="stylesheet" type="text/css" href="/Css/Maintain.css" />
    <link rel="stylesheet" type="text/css" href="/Css/tipsy.css" />
    <link rel="stylesheet" type="text/css" href="/Css/Grid.css" />
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="/Css/MaintainThemes/Default/Default.css" id="StyleThemes" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
    <script type="text/javascript" src="/Js/Grid.js"></script>
    <script type="text/javascript" src="/Js/SysPageControl.js"></script>
    <script type="text/javascript" src="/Js/ComFun.js"></script>
    <script type="text/javascript" src="/Js/jquery.tipsy.js"></script>
    <script type="text/javascript" src="/Js/jquery.ChiliUpload.js"></script>
    <script type="text/javascript" src="/Js/shadowboxByBackOffice.js"></script>
<body>
<div id="PopUpMain">
	<div id="PopUpMainDesc">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>				
				<?php echo $PopupIcon . $PopupMsg; ?>
			</tr>
		</table>
	</div>
</div>
<div id="PopUpMenu">
	<span class="MenuRight">
		<ul class="Menunav">
			<?php echo $PopupBtn; ?>
		</ul>
	</span>
</div>
</body>
</html>