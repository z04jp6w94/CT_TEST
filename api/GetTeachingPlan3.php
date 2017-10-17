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
	$StudentId = trim(GetxRequest("StudentId"));
	$IdentityType = trim(GetxRequest("IdentityType"));
	$WebDate = trim(GetxRequest("Date"));																	//記錄管理S1 選日期用
	
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 														//test 需要測試開啟
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
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$RowCount = $MySql -> db_num_rows($initRun);
	$PersonId = $MySql -> db_result($initRun);
//學生取得班級
	$Sql = " select ClassId FROM ClassMember ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and StudentId = '".$StudentId."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$ClassId = $MySql -> db_result($initRun);
		
//當日教案
	$Sql = " select ";
	$Sql .= " P.Id, ";
	$Sql .= " P.Name, ";
	$Sql .= " ifnull(E.Stars,''), ";
	$Sql .= " case when E.Comment != '' then 'V' else '' end ";
	$Sql .= " , ifnull(E.Pic1L,'') ";
	$Sql .= " , ifnull(E.Pic2L,'') ";
	$Sql .= " , ifnull(E.Pic3L,'') ";
	$Sql .= " , ifnull(E.Status,'') ";
	$Sql .= " , ifnull(E.Id,'') ";
	$Sql .= " from Class M ";
	$Sql .= " left join ClassTime D on D.ClassId = M.Id ";
	$Sql .= " left join TemplateDetail T on T.Id = D.TemplateDetailId ";
	$Sql .= " left join TeachingPlan P on P.Id = T.TeachingPlanId ";
	$Sql .= " left join Course E on E.TeachingPlanId = P.Id and E.StudentId = '".$StudentId."' and TeachingPlanTime = '".$WebDate."' ";		//YYYY-MM-DD
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Id = '".$ClassId."' ";
	$Sql .= " and M.TeacherId = '".$PersonId."' ";
	$Sql .= " and T.TeachingPlanId != '' ";	//課表空值判斷
	$Sql .= " and D.ClassTime = '".$WebDate."' ";
	$Sql .= " and D.ClassId = '".$ClassId."' ";
	$Sql .= " order by D.TemplateDetailId ";
	
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");	
	$TeachingAry = $MySql -> db_array($Sql,9);
		
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
			$json .= '"TeachingPlan": [';
			for($i=1;$i<=count($TeachingAry);$i++){
				$a = 0;
				if($TeachingAry[$i-1][4] != ''){	
					$a = $a +1;
				}
				if($TeachingAry[$i-1][5] != ''){
					$a = $a +1;
				}
				if($TeachingAry[$i-1][6] != ''){
					$a = $a +1;
				}
				$json .= '{';
				$json .= '"CourseId": "'.$TeachingAry[$i-1][8].'",';
				$json .= '"TeachingPlanId": "'.$TeachingAry[$i-1][0].'",';
				$json .= '"TeachingPlanName": "'.$TeachingAry[$i-1][1].'",';
				$json .= '"Pic": "'.$a.'",';
				$json .= '"Stars": "'.$TeachingAry[$i-1][2].'",';
				$json .= '"Comment": "'.$TeachingAry[$i-1][3].'",';
				$json .= '"Status": "'.$TeachingAry[$i-1][7].'" ';
				if($i != count($TeachingAry)){
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
