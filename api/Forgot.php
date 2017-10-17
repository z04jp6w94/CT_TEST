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
//		撰寫日期：20140728
//		程式功能：api 
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
//定義全域參數

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");	
//
	$ExcuteSuccess = true;	
//setting
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式
	$Account = trim(GetxRequest("Account"));
	$IdentityType = trim(GetxRequest("IdentityType"));														//02 03
	$Language = trim(GetxRequest("Language"));
	$Email = trim(GetxRequest("Email"));

	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$Status = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
	
	if($Account == '' && $Language == '' && $ExcuteSuccess===true){
		$ExcuteSuccess = false;
		$msg = '缺少參數';
		$Status = "F";	
	}	
//隨機密碼	
	function generatorPassword2(){
		$password_len = 7;
		$password = '';
		$word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
		$len = strlen($word);
		for ($i = 0; $i < $password_len; $i++) {
			$password .= $word[rand() % $len];
		}
		return $password;
	}	
//資料庫連線
	$MySql = new mysql();					
//帳號資訊		
		$Sql = " select Account, FullName, NickName, Id, Email from Personnel";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Account = '$Account' ";
		$Sql .= " and IdentityTypeId = '$IdentityType' ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		$rs = $MySql -> db_fetch_array($initRun);

		if ($RowCount < '1' && $ExcuteSuccess == true){
			$msg = '找不到該帳號';
			$Status = "F";
			$ExcuteSuccess = false;
		}		
		if($RowCount == '1' && $ExcuteSuccess == true){
			
			$newpass = generatorPassword2();
			
			/*tai add */
			$SecretPwd = ENCCode($newpass);

			$Sql = " update Personnel set ";
			$Sql .= " PWD = '".$SecretPwd."' ";
			$Sql .= " where  1 = 1 ";
			$Sql .= " and Account = '$Account' ";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
			
			$mail = $rs["Email"];	
			$subject = "CT 更新密碼通知";
			$msg = " Dear ： ".$rs["FullName"]."\r\n";
			$msg .= " 更新的密碼為: ".$newpass." ";
			//信件內容 
			$headers = "From: ct@gmail.com.tw"; //寄件者
			
			if(mail("$mail", "$subject", "$msg", "$headers"))
				echo "";//寄信成功就會顯示的提示訊息
			else
				echo "";//寄信失敗顯示的錯誤訊息
			}
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'"';
		$json .= '}';
		
//關閉資料庫連線
	$MySql -> db_close();
		
		echo $json;
		exit ;
			
?>
