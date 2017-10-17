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
	$CourseId = trim(GetxRequest("CourseId"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));														//教師ID, 家長ID
	$IdentityType = trim(GetxRequest("IdentityType"));														//身分別: 教師or家長
	$Comment = trim(GetxRequest("Comment"));
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 														//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													
	$Time = date("His");		

	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');												//SysTime
	
	$WebStatus = "S";
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
//參數檢測
	if($CourseId =='' || $PersonnelId =='' || $IdentityType =='' || $Comment =='' && $ExcuteSuccess=true){
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
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$PersonId = $MySql -> db_result($initRun);
	
	if($PersonnelId != $PersonId){
		$msg = '身份錯誤';
		$WebStatus = "F";
		$ExcuteSuccess = false;
	}
//根據身分別做不同存取 (2: 教師-單一,3: 家長-多筆)
	if($IdentityType=='000000000000002'){
//檢查是否有該筆資料
		$Sql = " select Count(*) from Course";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Id = '".$CourseId."' ";
		$initRun = $MySql -> db_query($Sql) or die("查詢錯誤1");
		$RowCount = $MySql -> db_result($initRun);
		
		if ($RowCount == 0 && $ExcuteSuccess == true){
			$msg = '課程ID參數錯誤';
			$WebStatus = "F";
			$ExcuteSuccess = false;
		}
//檢查留言新增OR更新
		$Sql = " select * from Message ";
		$Sql .= " where 1 = 1 "; 
		$Sql .= " and CourseId = '".$CourseId."' ";
		$Sql .= " and CreatorId = '".$PersonId."' ";
		$initRun = $MySql -> db_query($Sql) or die("查詢錯誤2");
		$RowCount2 = $MySql -> db_num_rows($initRun);			
//新增課程計錄	
		if($RowCount == 1){
			if($RowCount2 == 0){
				$Sql = " insert into Message ";
				$Sql .= " values";
				$Sql .= "(";
				$Sql .= " '', '".$CourseId."', '".$Comment."', 'N', '', '".$PersonId."', '".$Date.$Time."', '".$PersonId."', '".$Date.$Time."' ";
				$Sql .= ")";
				$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤3");
			}else{
				$Sql = " update Message set";
				$Sql .= " Comment = '".$Comment."', ";
				$Sql .= " IsRead = 'Y', ";										//是否閱讀過 
				$Sql .= " ReadTime = '".$Date.$Time."', ";
				$Sql .= " LastEditorId = '".$PersonId."',";
				$Sql .= " LastEditDate = '".$Date.$Time."'";
				$Sql .= " where 1 = 1 ";
				$Sql .= " and CourseId = '".$CourseId."'";
				$Sql .= " and CreatorId = '".$PersonId."'";
				$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤4");		
			}
		}					
						
	}else if($IdentityType=='000000000000003'){
		$Sql = " insert into Message ";
		$Sql .= " values";
		$Sql .= "(";
		$Sql .= " '', '".$CourseId."', '".$Comment."', 'N', '', '".$PersonId."', '".$Date.$Time."', '".$PersonId."', '".$Date.$Time."' ";
		$Sql .= ")";
		
		$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤3");	
	}		
//json				
		$json = '{';
		$json .= '"Status": "'.$WebStatus.'",';
		if($WebStatus =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'"';
		$json .= '}';
		
//關閉資料庫連線
	$MySql -> db_close();
		
		echo $json;
		exit ;
			
?>
