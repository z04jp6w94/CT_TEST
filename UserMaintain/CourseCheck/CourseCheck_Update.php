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
//		撰寫人員：JimmyChao
//		撰寫日期：20140728
//		程式功能：luckysparks / 最新消息 / 更新
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：ad_m
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
	date_default_timezone_set('Asia/Taipei');	
//定義全域參數
	$val = array();																			//寫入欄位名稱及值的陣列
	$ExID = $_SESSION["C_USER_ID"];															//操作 ID
	$ExDate = date("Ymd");																	//操作 日期
	$ExTime = date("His");																	//操作 時間
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["C_USER_ID"];														//管理員 ID
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey", 30));											//CourseId
//取出資料寫入Transcripts
	$Sql = " select M.*, A.CategoryId from Course M ";
	$Sql .= " left join TeachingPlan A on A.Id = M.TeachingPlanId  ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.Id = '".$DataKey."'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$rs = $MySql -> db_fetch_array($initRun);
//取得班級資料	
	$Sql = " select M.Id, M.StartTime from Class M ";
	$Sql .= " left join ClassMember T on T.ClassId = M.Id ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and T.StudentId = '".$rs["StudentId"]."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$rs2 = $MySql -> db_fetch_array($initRun);
//取得課程計錄教案 訓練目標
	$Sql = " select Id from TeachingObjectives ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeachingPlanId = '".$rs["TeachingPlanId"]."'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
/*array 0629 修改*/
	$ObjectivesAry = $MySql -> db_array($Sql,1);
//取得Transcripts編號
	$Sql = " select Id from Transcripts ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " order by Id desc ";
	$Sql .= " limit 1";	
	$Num = $MySql -> db_query($Sql) or die("查詢錯誤");
	$Id = $MySql -> db_result($Num);
		
	if($Id==''){
		$Id = '000000000000001';	
	}else{
		$Id +=1 ;
		$Id=str_pad($Id,15,"0",STR_PAD_LEFT);
	}
//更新資料
	$Sql = " update Course set";
	$Sql .= " Status = '2'";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$DataKey."'";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");	
//寫入Transcripts (Category 從教案取
/*0629 修改*/
	for($i=0;$i<count($ObjectivesAry);$i++){
		$Id +=$i ;
		$Id=str_pad($Id,15,"0",STR_PAD_LEFT);
		$Sql = " insert into Transcripts ";
		$Sql .= " values ";
		$Sql .= " (";
		$Sql .= " '".$Id."', '".$rs2["Id"]."', '".$rs2["StartTime"]."', '".$rs["Id"]."', '".$rs["StudentId"]."', '".$ObjectivesAry[$i]."', '".$rs["CategoryId"]."', '".$rs["Stars"]."', '".$ExID."', '".$ExDate.$ExTime."'  ";
		$Sql .= " )";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤5");	
	}
//關閉資料庫連線
	$MySql -> db_close();
//狀態回執
	if($rtnURL != ""){	
		header("location:$rtnURL");
	}else{
		header("location:" . $_COOKIE["FilePath"] . "?");
	}
?>
