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
	$ExcuteSuccess = true;	
//setting
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式
	$Account = trim(GetxRequest("Account"));
	$PWD = trim(GetxRequest("PWD"));
	$Language = trim(GetxRequest("Language"));
	$IdentityType = trim(GetxRequest("IdentityType"));
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
	
	if($Account == '' && $PWD == '' && $ExcuteSuccess===true){
		$ExcuteSuccess = false;
		$msg = '帳號或密碼錯誤';
		$Status = "F";	
	}	
	$SecretPwd = ENCCode($PWD);
	
//資料庫連線
	$MySql = new mysql();			
//資訊		
		$Sql = " select Account, FullName, NickName, Id, ClientId, ";
		$Sql .= " Email,Mobile,WechatId"; 
		$Sql .= " from Personnel ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Account = '$Account' ";
		$Sql .= " and PWD = '$SecretPwd' ";
		$Sql .= " and Enabled = 'Y' ";
		$Sql .= " and DeleteStatus = 'N' ";
		$Sql .= " and LOCATE('2', UseApp) > 0 ";
//		$Sql .= " and Language = '$Language' ";
		$Sql .= " and IdentityTypeId = '$IdentityType' ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		
		$MemAry = $MySql -> db_array($Sql,8);
		
		$Sql = " select KindergartenName from Personnel ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Id = '".$MemAry[0][4]."' ";
		$Sql .= " and IdentityTypeId = '000000000000001' ";
	
		$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
		$KindergartenName = $MySql -> db_result($SqlRun);
		
		if ($RowCount != '1' && $ExcuteSuccess == true){
			$msg = '帳號或密碼錯誤';
			$Status = "F";
			$ExcuteSuccess = false;
		}
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
			$json .= '"DateTime": "'.$SystemDate.'"';
		}
		if($ExcuteSuccess == true){
			$json .= '"DateTime": "'.$SystemDate.'",';
			$json .= '"Account": "'.$MemAry[0][0].'",';
			$json .= '"Name": "'.$MemAry[0][1].'",';
			$json .= '"NickName": "'.$MemAry[0][2].'",';
			$json .= '"SchoolNM": "'.$KindergartenName.'",';
			$json .= '"Email": "'.$MemAry[0][5].'",';
			$json .= '"Mobile": "'.$MemAry[0][6].'",';
			$json .= '"WechatId": "'.$MemAry[0][7].'",';
			$json .= '"PersonnelId": "'.$MemAry[0][3].'"';
		}
		$json .= '}';	
//關閉資料庫連線
	$MySql -> db_close();
		echo $json;
		exit ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo MetaTitle; ?></title>
	<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script language="JavaScript">
		$(function(){
		
		})
	</script>
</head>
<body>

</body>
</html>
