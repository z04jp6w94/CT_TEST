<?php
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
	date_default_timezone_set('Asia/Taipei');
	
	$mail = "i62116@yahoo.com.tw";
//	$mail = "jiafuxyz@gmail.com";
/*	
	$subject = "ebooking帳號啟用信"; //信件標題 
	$msg = "Dear ：\r\n";
	$msg .= "系統已收到您的帳號申請資料。\r\n";
	$msg .= "請點選以下網址啟用帳號。\r\n"."http://HTTP_HOST/CommonPage/Valid_OPEN.php?ID=newKey\r\n";
	$msg .= "\r\n以下是您的申請資料：\r\n";
	$msg .= "<登入APP使用>\r\n";
	$msg .= "此資料用來登入App使用\r\n";
	$msg .= "如果您尚未下載eBooking APP，可以到下列網址下載\r\n";
	$msg .= "https://itunes.apple.com/tw/app/ebooking/id823471271?l=zh&mt=8 \r\n";
	$msg .= "店號：newKey\r\n"."帳號：user\r\n"."密碼：password\r\n"."\r\n";
	$msg .= "<登入網站管理使用>\r\n";
	$msg .= "此資料為登入網站管理使用\r\n";
	$msg .= "登入位置：\r\n";
	$msg .= "http://HTTP_HOST/Maintain/Menu/Member_login.php \r\n";
	$msg 
	.= "店號：newKey\r\n"."帳號：admin\r\n"."密碼：password\r\n";
*/	
	$subject = "測試";
	$msg = "test content";
	//信件內容 
	$headers = "From: test@gmail.com.tw"; //寄件者
	if(mail("$mail", "$subject", "$msg", "$headers"))
		echo "信件已經發送成功。";//寄信成功就會顯示的提示訊息
	else
		echo "信件發送失敗！";//寄信失敗顯示的錯誤訊息	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
</body>
</html>