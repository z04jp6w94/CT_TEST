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
	$TeachingPlanId = trim(GetxRequest("TeachingPlanId"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));
	$ClassId = trim(GetxRequest("ClassId"));

//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$ClassDate = DspDate($Date,"-");
	
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
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$RowCount = $MySql -> db_num_rows($initRun);
	$PersonId = $MySql -> db_result($initRun);
//教案資訊
	$Sql = " select * from TeachingPlan M";
	$Sql .= " left join SystemParameter a on a.Id = M.CategoryId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Id = '$TeachingPlanId'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$rs = $MySql -> db_fetch_array($initRun);	
//分解動作 TeachingPlanAction
	$Sql = " select Id, Description, ImageURL from TeachingPlanAction";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeachingPlanId = '$TeachingPlanId'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$Action = $MySql -> db_array($Sql,3);
//深入解說 TeachingComment
	$Sql = " select Id, Description from TeachingComment";
	$Sql .= " where 1 =1 ";
	$Sql .= " and TeachingPlanId = '$TeachingPlanId'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
	$Comment = $MySql -> db_array($Sql,2);
//訓練目標 TeachingObjectives
	$Sql = " select M.Id, T.Comment from TeachingObjectives M";
	$Sql .= " left join OptionalItem T on T.Id = M.OptionalId ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and TeachingPlanId = '$TeachingPlanId'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
	$Objectives = $MySql -> db_array($Sql,2);
//精益求精 TeachingAdvanced
	$Sql = " select Id, Description from TeachingAdvanced ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and TeachingPlanId = '$TeachingPlanId'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Advanced = $MySql -> db_array($Sql,2);
//	
//班級資訊	
		$Sql = " select DISTINCT M.Id, ClassName, ClassLevelId, TeacherId, M.TemplateId, ClassWeek, StartTime, Cast((Length(ClassWeek)+1)/2 as SIGNED), C.Day ";
		$Sql .= " from Class M";	
		$Sql .= " left join ClassTime D on D.ClassId = M.Id and D.ClassTime = '".$ClassDate."' ";
		$Sql .= " left join TemplateDetail C on C.Id = D.TemplateDetailId ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and M.Id = '".$ClassId."' ";
		$Sql .= " and TeacherId = '$PersonId'";
		$Sql .= " and Enabled = 'Y'";
		$Sql .= " and DeleteStatus = 'N'";
		
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$Class = $MySql -> db_array($Sql,9);
		if ($RowCount == 0 && $ExcuteSuccess == true){
			$msg = '參數錯誤';
			$Status = "F";
			$ExcuteSuccess = false;
		}
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
				$json .= '"ClassId": "'.$Class[$i-1][0].'",';
				$json .= '"Name": "'.$Class[$i-1][1].'",';
				$json .= '"Level": "'.$Class[$i-1][2].'",';
				$json .= '"Semester": "100",';
				$json .= '"Date": "'.DspDate($Class[$i-1][6],"/").'",';
				$json .= '"Day": "'.$Class[$i-1][8].'"';
				if($i != count($Class)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
//		}else{
//			$json .= '"Class":""';	
//		}
		$json .= '],';
		//
		$json .= '"TeachingPlanName": "'.$rs["Name"].'", ';
		$json .= '"Category": "'.$rs["Description"].'", ';
		$json .= '"VideoUrl": "'.$rs["VideoUrl"].'", ';
		$json .= '"Suggest": "'.str_replace("\r\n","#",$rs["Suggest"]).'", ';
		$json .= '"TeachingPlanAction": [';
			for($i=1;$i<=count($Action);$i++){
				$json .= '{';
				$json .= '"Description": "'.trim(str_replace("\r\n","",$Action[$i-1][1])).'",';
				$json .= '"ImageURL": "http://'.$_SERVER['HTTP_HOST'].TeachingPlan.$Action[$i-1][2].'"';
				if($i != count($Action)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
		$json .= '],';

		$json .= '"TeachingComment": [';
			for($i=1;$i<=count($Comment);$i++){
				$json .= '{';
				$json .= '"TeachingComment": "'.$Comment[$i-1][1].'"';
				if($i != count($Comment)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
		$json .= '],';
		
		$json .= '"TeachingAdvanced": [';
			for($i=1;$i<=count($Advanced);$i++){
				$json .= '{';
				$json .= '"Description": "'.trim(str_replace("\r\n","",$Advanced[$i-1][1])).'"';
				if($i != count($Advanced)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
		$json .= '],';
		
		$json .= '"TeachingObjectives": [';
			for($i=1;$i<=count($Objectives);$i++){
				$json .= '{';
				$json .= '"Description": "'.$Objectives[$i-1][1].'"';
				if($i != count($Objectives)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
		$json .= ']';
		
		}else{
			$json .= '"Class":""';	
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
