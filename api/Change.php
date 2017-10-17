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
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 									//正式
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));												//身分別
	$NewPWD = trim(GetxRequest("NewPWD"));
	$OldPWD = trim(GetxRequest("OldPWD"));
	$Nickname = trim(GetxRequest("Nickname"));
	$Email = trim(GetxRequest("Email"));															//教師才需要
	$Mobile = trim(GetxRequest("Mobile"));
	$WechatId= trim(GetxRequest("WechatId"));
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 												//test 需要測試開啟
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
	if($IdentityType == '' && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '請傳送身分類別參數';
		$ExcuteSuccess = false;	
	}
	if($NewPWD == '' || $OldPWD == '' || $Nickname == '' && $ExcuteSuccess===true){
		$ExcuteSuccess = false;
		$msg = '請鍵入欲修改之密碼或暱稱！';
		$Status = "F";	
	}
		
	if($IdentityType == '000000000000003' && $Email == '' && $ExcuteSuccess===true){
		$ExcuteSuccess = false;
		$msg = '請輸入信箱';
		$Status = "F";
	}
	if ($IdentityType == '000000000000003' && !filter_var($Email, FILTER_VALIDATE_EMAIL) && $ExcuteSuccess===true) {
		$ExcuteSuccess = false;
		$msg = '請輸入正確信箱格式';
		$Status = "F";
	}
//OldPassWord
	$SecretOldPwd = ENCCode($OldPWD);
//資料庫連線
	$MySql = new mysql();			
//檢視帳號	
	$Sql = " select * from Personnel ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$PersonnelId."' ";
	$Sql .= " and IdentityTypeId = '".$IdentityType."' ";
	$Sql .= " and PWD = '".$SecretOldPwd."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
//	
		if ($RowCount != '1' && $ExcuteSuccess == true){
			$msg = '查無此帳號！';
			$Status = "F";
			$ExcuteSuccess = false;
		}	
//帳號資訊		
		if($RowCount == '1' && $ExcuteSuccess == true){
			$SecretPwd = ENCCode($NewPWD);

			$Sql = " Update Personnel set ";
			$Sql .= " NickName = '".$Nickname."',";
			$Sql .= " Email = '".$Email."', ";
			$Sql .= " Mobile = '".$Mobile."', ";
			$Sql .= " WechatId = '".$WechatId."', ";
			$Sql .= " PWD = '".$SecretPwd."'";
			$Sql .= " where 1 = 1 ";
			$Sql .= " and Id = '$PersonnelId' ";
			if($NewPWD != '' && $OldPWD != ''){
				$Sql .= " and PWD = '$SecretOldPwd' ";
			}
			$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
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
