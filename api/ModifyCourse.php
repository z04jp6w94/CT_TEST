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
	$CourseId = trim(GetxRequest("CourseId"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));		//教師ID
	$StudentId = trim(GetxRequest("StudentId"));			//學生ID
	$TeachingPlanId = trim(GetxRequest("TeachingPlanId"));	//教案ID
	$IdentityType = trim(GetxRequest("IdentityType"));
	$Stars = trim(GetxRequest("Stars"));
	$Comment = trim(GetxRequest("Comment"));
	$Pic1L = trim(GetxRequest("Pic1L"));
	$Pic1S = trim(GetxRequest("Pic1S"));
	$Pic2L = trim(GetxRequest("Pic2L"));
	$Pic2S = trim(GetxRequest("Pic2S"));
	$Pic3L = trim(GetxRequest("Pic3L"));
	$Pic3S = trim(GetxRequest("Pic3S"));
	$Number = trim(GetxRequest("Number"));
	$Status = trim(GetxRequest("Status"));
	$Date = trim(GetxRequest("Date"));																			//
	$TeachingPlanTime = trim(GetxRequest("TeachingPlanTime"));													//教案上課時間
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		

	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$WebStatus = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
//參數檢測
	if($Status =='' || $PersonnelId =='' || $StudentId =='' || $TeachingPlanId =='' || $TeachingPlanTime =='' && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
	
//資料庫連線
	$MySql = new mysql();
//課程編號
	$Sql = " select Id from Course";
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
//新增課程計錄	
if($WebStatus =='S'){
	
	if($CourseId ==''){	
		
		$Sql = " insert into Course ";
		$Sql .= " values";
		$Sql .= "(";
		$Sql .= " '".$Id."', '".$PersonnelId."', '".$StudentId."', '".$Stars."', '".$Comment."', '".$Pic1L."', '".$Pic1S."', '".$Pic2L."', '".$Pic2S."', '".$Pic3L."', '".$Pic3S."', ".$Number.", '' ";
		$Sql .= " , '', '".$Status."', '".$PersonnelId."', '".$Date.$Time."', '".$PersonnelId."', '".$Date.$Time."','".$TeachingPlanId."', '".$TeachingPlanTime."' ";
		$Sql .= ")";
		//echo $Sql;
		$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
		
	}else{
//修改課程計錄
	$Sql = " update Course set ";
	$Sql .= " Stars = '$Stars' ,";
	$Sql .= " Comment = '$Comment' ,";
	$Sql .= " Pic1L = '$Pic1L' ,";
	$Sql .= " Pic1S = '$Pic1S' ,";
	$Sql .= " Pic2L = '$Pic2L' ,";
	$Sql .= " Pic2S = '$Pic2S' ,";
	$Sql .= " Pic3L = '$Pic3L' ,";
	$Sql .= " Pic3S = '$Pic3S' ,";
	$Sql .= " Number = ".$Number." ,";
	$Sql .= " Status = '$Status' ,";
	$Sql .= " LastEditorId = '$PersonnelId', ";
	$Sql .= " LastEditDate = '".$Date.$Time."', ";
	$Sql .= " TeachingPlanTime = '".$TeachingPlanTime."' ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '$CourseId'";	
	//echo $Sql;
	$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
	
	
	}
}
//json				
		$json = '{';
		$json .= '"Status": "'.$WebStatus.'",';
		if($WebStatus =='F'){
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
