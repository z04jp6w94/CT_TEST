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
	$IdentityType = trim(GetxRequest("IdentityType"));
	$Date = trim(GetxRequest("Date"));
	$KeyWord = trim(GetxRequest("KeyWord"));

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

//資料庫連線
	$MySql = new mysql();
//檢驗身份
	$Sql = " select * from Personnel ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '$PersonnelId' ";
	$Sql .= " and IdentityTypeId = '$IdentityType'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$PersonId = $MySql -> db_result($initRun);		
//取得園方的Id 跟 授權權限	
	$Sql = " select ClientId from Personnel ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '$PersonnelId' ";
	$Sql .= " and IdentityTypeId = '$IdentityType'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClientId = $MySql -> db_result($initRun);	
//授權	時間判斷
	$Sql = " select Id from License";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and PersonnelId = '".$ClientId."' ";
	$Sql .= " and StartDate <= '".$Date."' ";
	$Sql .= " and EndDate >= '".$Date."' ";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$LicIdAry = $MySql -> db_array($Sql,2);
	
	for($i=0;$i<count($LicIdAry);$i++){
		if($i<count($LicIdAry)){
			$LicId = $LicId.$LicIdAry[$i][0]."','";
		}else{
			$LicId .= '';
		}
	}	
//GET 	PS:不是教案Id 
	$Sql = " select TeachingPlanId from LicenseTeachingPlan ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and LicenseId in ('$LicId') ";

	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ProductAry = $MySql -> db_array($Sql,2);
	
	for($i=0;$i<count($ProductAry);$i++){
		if($i<count($ProductAry)){
			$ProductId = $ProductId.$ProductAry[$i][0]."','";
		}else{
			$ProductId .= '';
		}
	}	
//教案
	$Sql = " select DISTINCT(B.TeachingPlanId) ";
	$Sql .= " from Class M";
	$Sql .= " left join Template A on M.TemplateId = A.Id ";
	$Sql .= " left join TemplateDetail B on B.TemplateTeachingPlanId in (select DISTINCT Id from TemplateTeachingPlan C where 1 = 1 and M.TemplateId = C.TemplateId) ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Enabled = 'Y' ";
	$Sql .= " and M.DeleteStatus = 'N' ";
	$Sql .= " and TeacherId = '".$PersonId."' ";
	
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$TeachPAry = $MySql -> db_array($Sql,1);
	for($i=0;$i<count($TeachPAry);$i++){
		if($i<count($TeachPAry)){
			$TPlan = $TPlan.$TeachPAry[$i]."','";
		}else{
			$TPlan = '';
		}
	}		
//教案ARRAY
	$Sql = " select M.Id, M.Name, M.VideoUrl, A.Description, B.Description, C.Description from TeachingPlan M ";
	$Sql .= " left join SystemParameter A on A.Id = M.CategoryId ";
	$Sql .= " left join SystemParameter B on B.Id = M.LevelId ";
	$Sql .= " left join SystemParameter C on C.Id = M.SubLevelId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and CategoryId in ('$ProductId') ";	//授權判斷
	$Sql .= " and M.Id in ('$TPlan')";

	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$TeachingPlanAry = $MySql -> db_array($Sql,6);
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){

			$json .= '"TeachingPlan": [';
			for($i=1;$i<=count($TeachingPlanAry);$i++){
				$json .= '{';
				$json .= '"Id": "'.$TeachingPlanAry[$i-1][0].'",';
				$json .= '"Name": "'.$TeachingPlanAry[$i-1][1].'",';
				$json .= '"Category": "'.$TeachingPlanAry[$i-1][3].'",';
				$json .= '"Level": "'.$TeachingPlanAry[$i-1][4].'",';
				$json .= '"SubLevel": "'.$TeachingPlanAry[$i-1][5].'",';
				$json .= '"VideoUrl": "'.$TeachingPlanAry[$i-1][2].'"';
				
				if($i != count($TeachingPlanAry)){
					$json .= '},';
				}else{
					$json .= '}';
				}
						
			}
			$json .= ']';
		}else{	
			$json .= '"TeachingPlan":""';
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
