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
	$IdentityTypeId = trim(GetxRequest("IdentityTypeId"));			//000000000000003 家長代碼
	$PWD = trim(GetxRequest("PWD"));
	$Name = trim(GetxRequest("Name"));
	$NickName = trim(GetxRequest("NickName"));
	$Email = trim(GetxRequest("Email"));
	$WechatId = trim(GetxRequest("WechatId"));
	$Mobile = trim(GetxRequest("Mobile"));
	
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

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
	
	if($Account == '' || $PWD == '' || $IdentityTypeId == '' || $Name == '' || $Email == '' || $Mobile == '' && $ExcuteSuccess===true){
		$ExcuteSuccess = false;
		$msg = '參數錯誤';
		$Status = "F";	
	}		
//資料庫連線
	$MySql = new mysql();	
//
	$Sql = " select * from Personnel";
	$Sql .= " where 1 = 1";
	$Sql .= " and Account = '$Account'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);	
	
		if ($RowCount >= 1 && $ExcuteSuccess == true){
			$msg = '此帳號已註冊';
			$Status = "F";
			$ExcuteSuccess = false;
		}	
//使用者編號
	$Sql = " select Id from Personnel";
	$Sql .= " where 1 = 1 ";
	$Sql .= " order by Id desc ";
	$Sql .= " limit 1";	
	$Num = $MySql -> db_query($Sql) or die("查詢錯誤");
	$Id = $MySql -> db_result($Num);
	
	if($Id==''){
		$Id = '000000000000001';	
	}else{
		$Id +=1 ;
		$Id=str_pad($Id,15,"0",STR_PAD_LEFT);
	}
if($Status == "S"){		
	$SecretPwd = ENCCode($PWD);
//寫入家長資料 Personnel	
	$Sql = " insert into Personnel ";
	$Sql .= " ( Id, Account, IdentityTypeId, PWD, FullName, NickName, Mobile, Email, WechatId, UseApp, Enabled, DeleteStatus) ";
	$Sql .= " values";
	$Sql .= " ( '".$Id."', '".$Account."', '".$IdentityTypeId."', '".$SecretPwd."', '".$Name."', '".$NickName."', '".$Mobile."', '".$Email."', '".$WechatId."', '2', 'Y', 'N' )";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
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
