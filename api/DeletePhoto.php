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
	$ExDate = date("Ymd");																									//操作 日期
	$ExTime = date("His");																									//操作 時間
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));							
	$Action = trim(GetxRequest("Action"));		
	$Action = 'D';								
	$CourseId = trim(GetxRequest("CourseId"));
	$Index = trim(GetxRequest("Index"));
	$FileName = trim(GetxRequest("FileName"));																//檔案名稱
//	$Base64String = trim(GetxRequest("Base64String"));
//	$Base64String = base64_decode($Base64String);															//解析BASE64
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 														//test 需要測試開啟
	$Path = Course;
	$UploadDir = root_path . $Path; 
		
	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$Status = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
	
	if (FileExist(root_path.$Path.$FileName)){
		//echo 'success';
	}else{
		$Status = "F";
		$msg = '無檔案';
		$ExcuteSuccess = false;		
	}
	
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
//參數檢測
	if($PersonnelId =='' || $IdentityType =='' || $Action =='' || $Index =='' || $FileName =='' && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
//檢驗身份 ?
	
//資料庫連線
	$MySql = new mysql();
	
//新增OR刪除 Action (A新增，D刪除
	if($ExcuteSuccess == true && $Status="S"){
		$Sql = " Update Course set ";
		$Sql .= " Pic".$Index."L = '',";
		$Sql .= " LastEditorId = '".$PersonnelId."', ";
		$Sql .= " LastEditDate = '".$Date.$Time."' ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Id = '".$CourseId."'";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
		
		DeleteFile($UploadDir . $FileName);	//刪除檔案
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
