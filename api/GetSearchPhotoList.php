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
	$StudentId = trim(GetxRequest("StudentId"));
	$StartDate = trim(GetxRequest("StartDate"));		//Class
	$EndDate = trim(GetxRequest("EndDate"));
	
	if($PhotoIndex ==''){
		$PhotoIndex = 1 ;
	}
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		

	$StartDate = DspDate($StartDate,"-");
	$EndDate = DspDate($EndDate,"-");
	
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
	if($PersonnelId =='' || $IdentityType =='' || $StudentId =='' && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
	
//資料庫連線
	$MySql = new mysql();

//檢驗身份
	$Sql = " select Id from Personnel ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '$PersonnelId' ";
	$Sql .= " and IdentityTypeId = '$IdentityType' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$PersonId = $MySql -> db_result($initRun);
//班級資料	
	$Sql = " select M.Id, ClassName, ClassLevelId, Semester, A.NickName, O.Comment from Class M ";
	$Sql .= " left join ClassMember C on C.ClassId = M.Id ";
	$Sql .= " left join Student A on A.Id = C.StudentId ";
	$Sql .= " left join OptionalItem O on O.Id = M.ClassLevelId";
	$Sql .= " where 1 = 1 ";	
	$Sql .= " and M.TeacherId = '".$PersonId."'";
	$Sql .= " and C.StudentId = '".$StudentId."'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($initRun);	
//課程計錄
	$Sql = " select DISTINCT M.Id, M.Pic1L, M.Pic2L, M.Pic3L, T.Name, M.TeachingPlanTime from Course M ";
	$Sql .= " left join TeachingPlan T on T.Id = M.TeachingPlanId ";
	$Sql .= " left join ClassMember A on A.StudentId = M.StudentId ";
	$Sql .= " left join Class B on B.Id = A.ClassId";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and PersonnelId = '".$PersonId."' ";
	$Sql .= " and M.StudentId = '".$StudentId."'";
	if($StartDate != ''){
	$Sql .= " and TeachingPlanTime >= '".$StartDate."' ";
	}
	if($EndDate != ''){
	$Sql .= " and TeachingPlanTime <= '".$EndDate."' ";
	}
	$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
	$Course = $MySql -> db_array($Sql,6);

//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			//Class
			$json .= '"ClassName": "'.$rs["ClassName"].'",'; 			
			$json .= '"ClassLevel": "'.$rs["Comment"].'",';
			$json .= '"ClassSemester": "106",';
			$json .= '"NickName": "'.$rs["NickName"].'",';
			//Course
			$json .= '"Data": [';
			for($i=1;$i<=count($Course);$i++){
				$json .= '{';
				$json .= '"CourseId": "'.$Course[$i-1][0].'",';
				if($Course[$i-1][1] !=''){
					$json .= '"Pic1": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][1].'",';
					$json .= '"PicName1":"'.$Course[$i-1][5].'-1",';
				}else{
					$json .= '"Pic1":"",';
					$json .= '"PicName1":"",';
				}
				$json .= '"Index1": "1", ';
				if($Course[$i-1][2] !=''){
					$json .= '"Pic2": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][2].'",';
					$json .= '"PicName2":"'.$Course[$i-1][5].'-2",';
				}else{
					$json .= '"Pic2":"",';
					$json .= '"PicName2":"",';
				}
				$json .= '"Index2": "2",';
				if($Course[$i-1][3] !=''){
					$json .= '"Pic3": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][3].'",';
					$json .= '"PicName3":"'.$Course[$i-1][5].'-2",';
				}else{
					$json .= '"Pic3":"",';
					$json .= '"PicName3":"",';
				}
				$json .= '"Index3": "3",';
				
				$json .= '"Name": "'.$Course[$i-1][4].'"';
				if($i != count($Course)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= ']';
			
		}else{
			$json .= '"Data":""';	
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
