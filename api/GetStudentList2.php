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
	$ClassId = trim(GetxRequest("ClassId"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟
	$Date = date("Ymd");
	$WebDate = DspDate($Date,"-");
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
//班級學生
	$Sql = " select M.StudentId, T.Name, T.NickName from ClassMember M ";
	$Sql .= " left join Student T on T.Id = M.StudentId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and ClassId = '".$ClassId."' ";
	$Sql .= " and T.DeleteStatus = 'N' ";
	$Sql .= " and T.Enabled = 'Y' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$StuAry = $MySql -> db_array($Sql,10);
//取得班級課表	
	$Sql = " select TemplateId from Class ";
	$Sql .= " where 1 = 1  ";
	$Sql .= " and Id = '".$ClassId."' ";
	$TempRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TemplateId = $MySql -> db_result($TempRun);	
//班級教案數	//0215增加判斷課表可能有空值
//	$Sql = " select ";
//	$Sql .= " (select count(DISTINCT TeachingPlanId) from TemplateDetail where 1 = 1 and TeachingPlanId != '' and TemplateTeachingPlanId in (select B.Id from TemplateTeachingPlan B where 1 = 1 and B.TemplateId = M.TemplateId )) '教案數' ";
//	$Sql .= " from Class M ";
//	$Sql .= " where 1 = 1 ";
//	$Sql .= " and TeacherId = '".$PersonId."' ";
//	$Sql .= " and Id = '".$ClassId."' ";
//	$Sql .= " and Enabled = 'Y' ";
//	$Sql .= " and DeleteStatus = 'N' ";

	$Sql = " select count(*) from Class M ";
	$Sql .= " left join ClassTime C on C.ClassId = M.Id ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeacherId = '".$PersonId."' ";
	$Sql .= " and Id = '".$ClassId."' ";
	$Sql .= " and Enabled = 'Y' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$Sql .= " and C.ClassTime <= '".$WebDate."' ";
	
	$TeaRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$SumTeaching = $MySql -> db_result($TeaRun);	
//班級資訊		
	$Sql = " select Id, Cast((Length(ClassWeek)+1)/2 as SIGNED) from Class ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeacherId = '".$PersonId."'";
	$Sql .= " and Id = '".$ClassId."'";
	$Sql .= " and Enabled = 'Y'";
	$Sql .= " and DeleteStatus = 'N'";
		
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
	$Class = $MySql -> db_array($Sql,2);

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
			$json .= '"Data": [';
			for($i=1;$i<=count($StuAry);$i++){
				$json .= '{';
				$json .= '"StudentId": "'.$StuAry[$i-1][0].'",';
				$json .= '"Student": "'.$StuAry[$i-1][1].'",';
				$json .= '"Total": "'.$SumTeaching.'",';
				//完成數量
					$Sql = " select count(*) from Course ";
					$Sql .= " where 1 = 1 ";
					$Sql .= " and PersonnelId = '".$PersonId."' ";
					$Sql .= " and StudentId = '".$StuAry[$i-1][0]."' ";
					$Sql .= " and Status in ('1','2') ";			//03/23 三角型 跟打勾
					$CouRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$SumCou = $MySql -> db_result($CouRun);		
					
				$json .= '"Completed": "'.$SumCou.'", ';
				$json .= '"Result": "'.round($SumCou/$SumTeaching,2).'" ';
					
				if($i != count($StuAry)){
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
