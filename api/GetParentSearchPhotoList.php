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
	$SearchMode = trim(GetxRequest("SearchMode"));								//查詢方式	1日期 ，2全部
	$StartDate = trim(GetxRequest("Date"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));							//
	$IdentityType = trim(GetxRequest("IdentityType"));							//03
	$StudentId = trim(GetxRequest("StudentId"));								//
	$StartDate = DspDate($StartDate,"-");
//

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$WebStatus = "S" ;
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
//參數檢測
	if($SearchMode =='' && $ExcuteSuccess=true){
		$WebStatus = "F";
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
	if($PersonId == ''){
		$WebStatus = "F";
		$msg = '帳號身份錯誤';
		$ExcuteSuccess = false;
	}
//課程計錄	
	$Sql = " select DISTINCT M.Id, M.Pic1L, M.Pic2L, M.Pic3L, T.NickName, A.Name from Course M ";
	$Sql .= " left join Student T on T.Id = M.StudentId";
	$Sql .= " left join ClassMember B on B.StudentId = M.StudentId";
	$Sql .= " left join TeachingPlan A on A.Id = M.TeachingPlanId";
	$Sql .= " where 1 = 1 ";	
	if($SearchMode == '1'){
		$Sql .= " and M.TeachingPlanTime = '".$StartDate."' ";
	}
	$Sql .= " and M.StudentId = '".$StudentId."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Course = $MySql -> db_array($Sql,6);	
//json				
		$json = '{';
		$json .= '"Status": "'.$WebStatus.'",';
		if($WebStatus =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			$json .= '"Data": [';
			for($i=1;$i<=count($Course);$i++){
				$json .= '{';
				$json .= '"NickName": "'.$Course[$i-1][4].'",'; 	/**/
				$json .= '"CourseId": "'.$Course[$i-1][0].'",';
				if($Course[$i-1][1] != ''){
					$json .= '"Pic1": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][1].'",';
				}else{
					$json .= '"Pic1": "",';
				}
				if($Course[$i-1][2] != ''){
					$json .= '"Pic2": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][2].'",';
				}else{
					$json .= '"Pic2": "",';
				}
				if($Course[$i-1][3] != ''){
					$json .= '"Pic3": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][3].'",';
				}else{
					$json .= '"Pic3": "",';
				}
				$json .= '"Name": "'.$Course[$i-1][5].'" ';		/**/
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