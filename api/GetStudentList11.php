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
	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

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
	$RowCount = $MySql -> db_num_rows($initRun);
	$PersonId = $MySql -> db_result($initRun);
//Get班級 -> 學生
	$Sql = " select M.Id from Class M ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.TeacherId = '".$PersonId."' ";
	$Sql .= " and M.Id = '".$ClassId."' ";
	$Sql .= " and M.Enabled = 'Y' ";
	$Sql .= " and M.DeleteStatus = 'N' ";
	
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClassId = $MySql -> db_array($Sql,2);
	
	for($i=0;$i<count($ClassId);$i++){
		if($i<count($ClassId)){
			$CId = $CId.$ClassId[$i][0]."','";
		}else{
			$CId .= '';
		}
	}
//取學生
	$Sql = " select B.Id, B.NickName, B.Larger, B.Small from ClassMember M ";
	$Sql .= " left join Student B on B.Id = M.StudentId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and B.Enabled = 'Y' ";
	$Sql .= " and B.DeleteStatus = 'N' ";
	$Sql .= " and M.ClassId in ('$CId') ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");	
	$Student = $MySql -> db_array($Sql,5);

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
			for($i=1;$i<=count($Student);$i++){
				$json .= '{';
				$json .= '"Id": "'.$Student[$i-1][0].'",';
				$json .= '"NickName": "'.$Student[$i-1][1].'",';
				
				if($Student[$i-1][2] != ''){
					$json .= '"Larger": "http://'.$_SERVER['HTTP_HOST'].Student.$Student[$i-1][2].'",';
				}else{
					$json .= '"Larger": "",';
				}
				if($Student[$i-1][3] != ''){
					$json .= '"Small": "http://'.$_SERVER['HTTP_HOST'].Student.$Student[$i-1][3].'"';
				}else{
					$json .= '"Small": ""';
				}
				if($i != count($Student)){
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
