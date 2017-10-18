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
	$WebDate = trim(GetxRequest("Date"));
	
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟
	$WebDate = date("Ymd");
	$ClassDate = DspDate($WebDate,"-");
		
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
//班級
	$todayCode = date("w");	//0是星期日
	if($todayCode == '0'){
		$todayCode = '7';
	}
//用WEEK 來判斷是否上完課
//Sql 
	$Sql = " select DISTINCT M.Id, ClassName, ClassLevelId, TeacherId, M.TemplateId, ClassWeek, StartTime, Cast((Length(ClassWeek)+1)/2 as SIGNED), C.Day ";
	$Sql .= " from Class M";
	$Sql .= " left join ClassTime D on D.ClassId = M.Id and D.ClassTime = '".$ClassDate."' ";
	$Sql .= " left join TemplateDetail C on C.Id = D.TemplateDetailId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.TeacherId = '$PersonId'";
	$Sql .= " and M.Enabled = 'Y'";
	$Sql .= " and M.DeleteStatus = 'N'";
	$Sql .= " and LOCATE(".$todayCode.", M.ClassWeek) > 0 ";
	$Sql .= " and ".$Date." >= StartTime";
	$Sql .= " and ".$Date." <= REPLACE((select ClassTime from ClassTime C where 1 = 1 and C.ClassId = M.Id order by ClassTime desc limit 1),'-','') ";
//	echo $Sql;
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$RowCount = $MySql -> db_num_rows($initRun);	
	$Class = $MySql -> db_array($Sql,12);	

	if ($RowCount == 0 && $ExcuteSuccess == true){
		$msg = '無資料';
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
			for($i=1;$i<=count($Class);$i++){
				$json .= '{';
				$json .= '"Id": "'.$Class[$i-1][0].'",';
				$json .= '"Name": "'.$Class[$i-1][1].'",';
				$json .= '"Level": "'.$Class[$i-1][2].'",';
				$json .= '"Semester": "106",';
				$json .= '"Date": "'.DspDate($Date,"/").'",';
				$json .= '"Day": "'.$Class[$i-1][8].'"';
				if($i != count($Class)){
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
