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
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));							// 000000000000003
	$QRCode = trim(GetxRequest("QRCode"));										// test:1234567
	$KinshipId = trim(GetxRequest("KinshipId"));

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
//參數檢測
	if($PersonnelId =='' || $IdentityType =='' || $QRCode =='' || $KinshipId =='' && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
if($QRCode != ''){	
//資料庫連線
	$MySql = new mysql();
//檢驗QRCODE 是否被使用
	$Sql = " select QRCode from Authorization ";
	$Sql .= " where 1 = 1";
	$Sql .= " and QRCode = '".$QRCode."' ";
	$Sql .= " and Used = 'N' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
	$QR = $MySql -> db_result($initRun);
	
	if ($RowCount == 0 && $ExcuteSuccess = true){
		$msg = 'QRCODE已被使用';
		$Status = "F";
		$ExcuteSuccess = false;
	}
//QR找and Id, StudentId 	
	$Sql = " select Id,StudentId from Authorization ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and QRCode = '".$QR."'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($initRun);	
}
	$Sql = " select count(*) from Authorization ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and ParentId = '".$PersonnelId."' ";
	$Sql .= " and StudentId = '".$rs["StudentId"]."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$CountAuth = $MySql -> db_result($initRun);	

	if ($CountAuth == 1 && $ExcuteSuccess == true){
		$msg = '已綁定身份';
		$Status = "F";
		$ExcuteSuccess = false;
	}
//更新資訊
if($ExcuteSuccess = true){			
	$Sql = " update Authorization set";
	$Sql .= " ParentId = '".$PersonnelId."' ,";
	$Sql .= " Used = 'Y', ";
	$Sql .= " LastEditorId = '".$PersonnelId."' ,";
	$Sql .= " LastEditDate = '".$Date.$Time."' ,";
	$Sql .= " KinshipId = '".$KinshipId."' ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '".$rs["Id"]."'";
	$Sql .= " and StudentId = '".$rs["StudentId"]."'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
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
