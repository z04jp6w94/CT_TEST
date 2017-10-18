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
	$ClassId = trim(GetxRequest("ClassId"));
	$StudentId = trim(GetxRequest("StudentId"));															//今日工作S2 NoNeEd
	$WebDate = trim(GetxRequest("Date"));																	//當天

//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$Status = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
	$WebDate = DspDate($Date,"-");
//	$WebDate = '2017-01-12';
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
//上課天數
	$Sql = " select Cast((Length(M.ClassWeek)+1)/2 as SIGNED) from Class M ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$ClassId."' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
	$ClassWeek = $MySql -> db_result($SqlRun);	
//課表資料//////////////////////
	$Sql = " select TemplateId from Class ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$ClassId."' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤1");
	$Template = $MySql -> db_result($SqlRun);	
//當日教案
	$Sql = " select ";
	$Sql .= " T.Id, ";
	$Sql .= " T.Name, ";	//教案名稱
	$Sql .= " T.VideoUrl, ";
	$Sql .= " X.Description, ";
	$Sql .= " Y.Description, ";
	$Sql .= " Z.Description, ";
	$Sql .= " C.*, ";
	$Sql .= " D.Day";
	$Sql .= " from Class M ";
	$Sql .= " left join ClassTime C on C.ClassId = M.Id  ";
	$Sql .= " left join TemplateDetail D on D.Id = C.TemplateDetailId ";
	$Sql .= " left join TeachingPlan T on T.Id = D.TeachingPlanId ";
//級別	
	$Sql .= " left join SystemParameter X on X.Id = T.CategoryId ";
	$Sql .= " left join SystemParameter Y on Y.Id = T.LevelId ";
	$Sql .= " left join SystemParameter Z on Z.Id = T.SubLevelId ";
	
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Id = '".$ClassId."' ";
	$Sql .= " and T.Id != '' ";	//課表空值判斷
	$Sql .= " and C.ClassTime = '".$WebDate."' ";
	$Sql .= " order by C.TemplateDetailId ";

	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$TeachingPlanAry = $MySql -> db_array($Sql,20);	

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
				$json .= '"Date": "'.DspDate($Date,"/").'",';
				$json .= '"Day": "'.$TeachingPlanAry[0][10].'"';
				if($i != count($Class)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';
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
