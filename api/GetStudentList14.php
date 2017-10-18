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
	$ClassId = trim(GetxRequest("ClassId"));

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
	$Sql = " select Id from Personnel ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '$PersonnelId' ";
	$Sql .= " and IdentityTypeId = '$IdentityType' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$PersonId = $MySql -> db_result($initRun);	
//班級資訊		
	$Sql = " select Id, ClassName, ClassLevelId, TeacherId, TemplateId, ClassWeek, StartTime, Cast((Length(ClassWeek)+1)/2 as SIGNED) from Class";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeacherId = '$PersonId'";
	$Sql .= " and Id = '$ClassId'";
	$Sql .= " and Enabled = 'Y'";
	$Sql .= " and DeleteStatus = 'N'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);		
	$Class = $MySql -> db_array($Sql,9);

	if ($RowCount == 0 && $ExcuteSuccess == true){
		$msg = '參數錯誤';
		$Status = "F";
		$ExcuteSuccess = false;
	}
//學生資訊
	$Sql = " select M.Id, T.Name, T.NickName, T.Id from ClassMember M ";
	$Sql .= " left join Student T on T.Id = M.StudentId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and ClassId = '$ClassId'";
	$Sql .= " and T.DeleteStatus = 'N' ";
	$Sql .= " and T.Enabled = 'Y' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Student = $MySql -> db_array($Sql,4);

//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			$json .= '"Class": [';
			for($i=1;$i<=count($Class);$i++){
				$json .= '{';
				$json .= '"Id": "'.$Class[$i-1][0].'",';
				$json .= '"Name": "'.$Class[$i-1][1].'",';
				$json .= '"Level": "'.$Class[$i-1][2].'",';
				$json .= '"Semester": "100",';
				$json .= '"Date": "'.DspDate($Class[$i-1][6],"/").'",';
				$json .= '"Day": "'.$Class[$i-1][7].'"';
				if($i != count($Class)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';
			
			$json .= '"Data": [';
			for($i=1;$i<=count($Student);$i++){
				$json .= '{';
				$json .= '"StudentId": "'.$Student[$i-1][3].'",';
				$json .= '"Student": "'.$Student[$i-1][1].'",';
				$json .= '"NickName": "'.$Student[$i-1][2].'",';
				
				$Sql = " select count(*) from Course B ";
				$Sql .= " left join Message T on T.CourseId = B.Id ";
				$Sql .= " where 1 = 1 ";
				$Sql .= " and T.IsRead = 'N' ";
				$Sql .= " and B.PersonnelId = '".$PersonId."' ";
				$Sql .= " and B.StudentId = '".$Student[$i-1][3]."'";
				$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
				$CountCourse = $MySql -> db_result($SqlRun);
				
				$json .= '"Number": "'.$CountCourse.'"';
				if($i != count($Student)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= ']';
					
		}else{
			$json .= '"Class":"",';	
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
