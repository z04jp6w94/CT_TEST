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
	$ClassId = trim(GetxRequest("ClassId"));							//班級Id
	$StudentId = trim(GetxRequest("StudentId"));						//學生Id
	$Date = trim(GetxRequest("Date"));
	
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
//課表資料//////////////////////
	$Sql = "  ";
	$Sql .= "  ";
	$Sql .= "  ";
	$Sql .= "  ";	
//資訊		
	$Sql = " select Id, ClassName, ClassLevelId, TeacherId, TemplateId, ClassWeek, StartTime, Cast((Length(ClassWeek)+1)/2 as SIGNED) from Class";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeacherId = '$PersonId'";
	$Sql .= " and Id = '".$ClassId."' ";
	$Sql .= " and Enabled = 'Y'";
	$Sql .= " and DeleteStatus = 'N'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$rs = $MySql -> db_fetch_array($initRun);
	$RowCount = $MySql -> db_num_rows($initRun);	
	$Class = $MySql -> db_array($Sql,17);
	
	if ($RowCount == 0 && $ExcuteSuccess == true){
		$msg = '找不到班級';
		$Status = "F";
		$ExcuteSuccess = false;
	}
//班級教案
	$Sql = " select DISTINCT(B.TeachingPlanId) ";
	$Sql .= " from Class M";
	$Sql .= " left join Template A on M.TemplateId = A.Id ";
	$Sql .= " left join TemplateDetail B on B.TemplateTeachingPlanId in (select DISTINCT Id from TemplateTeachingPlan C where 1 = 1 and M.TemplateId = C.TemplateId) ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Enabled = 'Y' ";
	$Sql .= " and M.DeleteStatus = 'N' ";
	$Sql .= " and TeacherId = '".$PersonId."' ";
	$Sql .= " and M.Id = '".$ClassId."'";
	
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$TeachPAry = $MySql -> db_array($Sql,2);
	for($i=0;$i<count($TeachPAry);$i++){
		if($i<count($TeachPAry)){
			$TPlan = $TPlan.$TeachPAry[$i][0]."','";
		}else{
			$TPlan .= '';
		}
	}		
//教案ARRAY
	$Sql = " select M.Id, M.Name, M.VideoUrl, A.Description, B.Description, C.Description, ifnull(D.Status,'')  from TeachingPlan M ";
	$Sql .= " left join SystemParameter A on A.Id = M.CategoryId ";
	$Sql .= " left join SystemParameter B on B.Id = M.LevelId ";
	$Sql .= " left join SystemParameter C on C.Id = M.SubLevelId ";
	$Sql .= " left join Course D on D.TeachingPlanId = M.Id and D.StudentId = '".$StudentId."' ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Id in ('$TPlan')";

	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$TeachingPlanAry = $MySql -> db_array($Sql,7);

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
				$json .= '"Semester": "106",';
				$json .= '"Date": "'.DspDate($Class[$i-1][6],"/").'",';
				$json .= '"Day": "'.$Class[$i-1][7].'"';
				if($i != count($Class)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';
			$json .= '"TeachingPlan": [';
			for($i=1;$i<=count($TeachingPlanAry);$i++){
				
				////////////////
				$Sql = " ";
				$Sql .= " ";
				$Sql .= " ";
				
				
				$json .= '{';
				$json .= '"Id": "'.$TeachingPlanAry[$i-1][0].'",';
				$json .= '"Name": "'.$TeachingPlanAry[$i-1][1].'",';
				$json .= '"Status": "'.$TeachingPlanAry[$i-1][6].'"';
				
				if($i != count($TeachingPlanAry)){
					$json .= '},';
				}else{
					$json .= '}';
				}
						
			}
			$json .= ']';
		}else{
			$json .= '"Class":"",';	
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
