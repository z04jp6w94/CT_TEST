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
	$WebDate = trim(GetxRequest("WebDate"));
	$WebDate = DspDate($WebDate,"-");//YYYY-MM-DD

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
//是否有連結學生
//	$Sql = " select A.StudentId, S.Name from Authorization A ";
//	$Sql .= " left join Student S on S.Id = A.StudentId ";
//	$Sql .= " where 1 = 1 ";
//	$Sql .= " and ParentId = '".$PersonId."' ";
//	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
//	$RowCount = $MySql -> db_num_rows($initRun);
//	$Student = $MySql -> db_array($Sql,2);	
//組合字串
//	$Student_Value = "";																		
//	for($i = 0; $i < count($Student); $i++){
//		if($Student[$i][0] != ''){
//			if($Student_Value == ""){
//				$Student_Value = $Student[$i][0];
//			}else{
//				$Student_Value .= "," . $Student[$i][0];
//			}
//		}
//	}
//	$Student_Value = "'" . str_replace(",", "','", $Student_Value) . "'";
//留言板
	$Sql = " select M.Comment,T.FullName,C.Id,M.CreatorId,SUBSTR(M.CreateDate,1,8) from Message M "; //留言,人名,課程ID,人ID,建立時間YYYYMMDD
	$Sql .= " left join Personnel T on T.Id = M.CreatorId ";
	$Sql .= " left join Course C on C.Id = M.CourseId ";
	$Sql .= " where 1 = 1";
//	$Sql .= " and M.CreatorId = '".$PersonId."'";
	$Sql .= " and C.StudentId = '".$StudentId."' ";
	$Sql .= " and C.TeachingPlanTime = '".$WebDate."'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Message = $MySql -> db_array($Sql,5);	

//資訊
	$Sql = " select C.Id, Stars, Comment, Pic1L, Pic1S, ";
	$Sql .= " Pic2L, Pic2S, Pic3L, Pic3S, Number, ";
	$Sql .= " Status , T.Name, REPLACE(TeachingPlanTime, '-', '/'), ";
	$Sql .= " case WEEKDAY(TeachingPlanTime)
				WHEN 0 THEN '(一)'
				WHEN 1 THEN '(二)'
				WHEN 2 THEN '(三)'
				WHEN 3 THEN '(四)'
				WHEN 4 THEN '(五)'
				WHEN 5 THEN '(六)'
				WHEN 6 THEN '(日)'
				end ";
	$Sql .= " from Course C ";
	$Sql .= " left join TeachingPlan T on T.Id = C.TeachingPlanId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and StudentId = '".$StudentId."' ";
	$Sql .= " and C.TeachingPlanTime = '".$WebDate."'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Course = $MySql -> db_array($Sql,14);
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true ){
			$json .= '"Data": [';
			for($i=1;$i<=count($Course);$i++){
				$json .= '{';
				$json .= '"CourseId": "'.$Course[$i-1][0].'",';
				$json .= '"TeachingPlanName": "'.$Course[$i-1][11].'",';
				$json .= '"TeachingPlanTime": "'.$Course[$i-1][12].$Course[$i-1][13].'",';
				$json .= '"Stars": "'.$Course[$i-1][1].'",';
				$json .= '"Comment": "'.$Course[$i-1][2].'",';
				$json .= '"Pic1L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][3].'",';
				$json .= '"Pic1S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][4].'",';
				$json .= '"Pic2L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][5].'",';
				$json .= '"Pic2S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][6].'",';
				$json .= '"Pic3L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][7].'",';
				$json .= '"Pic3S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][8].'",';
				$json .= '"Number": "'.$Course[$i-1][9].'",';
				$json .= '"Status": "'.$Course[$i-1][10].'" ';
				if($i != count($Course)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';

			$json .= '"Message": [';
			for($i=1;$i<=count($Message);$i++){
				$json .= '{';
				$json .= '"CourseId": "'.$Message[$i-1][2].'",';
				$json .= '"PersonnelId": "'.$Message[$i-1][3].'",';
				$json .= '"Name": "'.$Message[$i-1][1].'",';
				$json .= '"Comment": "'.$Message[$i-1][0].'",';
				$json .= '"CreateDate": "'.$Message[$i-1][4].'" ';
				if($i != count($Message)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= ']';
		}else{
			$json .= '"Data":"",';
			$json .= '"Message":"",';	
		}
		$json .= '}';
//關閉資料庫連線
	$MySql -> db_close();
		echo $json;
		exit ;		
?>
