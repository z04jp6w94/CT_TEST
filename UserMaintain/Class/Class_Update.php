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
	$USER_NM = $_SESSION["C_USER_NM"];	
	$USER_Client_ID = $_SESSION["M_USER_Num"];													//園方Id
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$DataKey = trim(GetxRequest("Id", 30));
//接收參數
	$ClassWeeklID = trim(GetxRequest("ClassWeek_TEXT"));
	$Client_ID = $USER_Client_ID;
	$StudentId_TEXT = trim(GetxRequest("StudentId_TEXT"));
	$StudentId = explode(",",$StudentId_TEXT);
	$TemplateId = trim(GetxRequest("TemplateId"));
	$StartTime = trim(GetxRequest("StartTime", 30));
	$StartTime = DspDate($StartTime, "-");
//抓出課表天數 上幾天
	$Sql = " select count(*) from TemplateDetail ";
	$Sql .= " where 1 = 1";
	$Sql .= " and TemplateTeachingPlanId = (select D.Id from TemplateTeachingPlan D where 1 = 1 and D.TemplateId = '".$TemplateId."' limit 1) ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$CountDay = $MySql -> db_result($SqlRun);
//更新資料
	$Sql = " Select * from Class where Id = $DataKey ";
	$MUM01Run = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$RowCount = $MySql -> db_num_rows($MUM01Run);
	if($RowCount <= 0){
		echo '<script>';
		echo 'alert("此筆資料不存在，可能已被其他使用者刪除，請重新操作");';
		echo 'window.history.back();';
		echo '</script>';
		exit();
	}else{
	//Auto Update
		while ($initAry = mysql_fetch_field($MUM01Run)){
			switch($initAry -> name){
			//不處理的欄位
				case 'Id':
				case 'Enabled':
				case 'DeleteStatus':
//				case 'AdM_Img':
//				case 'NewsM_PIC2':
//				case 'NewsM_PIC3':
//				case 'NewsM_PIC4':
//				case 'NewsM_Sort':
				case 'CreatorId':
				case 'CreateDate':
					break;
			//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
				case 'ClassWeek':
					$val[$initAry -> name]= $ClassWeeklID;
					break;
				case 'LastEditorId':
					$val[$initAry -> name] = $USER_NM;
					break;
				case 'LastEditDate':
					$val[$initAry -> name] = $ExDate.$ExTime;
					break;
				case 'ClientId':
					$val[$initAry -> name] = $Client_ID;
					break;	
				default:
					$val[$initAry -> name] = trim(GetxRequest($initAry -> name));
					break;
			}
		}
		$where = "Id = " . $DataKey;
		$MySql -> setTable('Class');
		$MySql -> updateOne($val, $where);

		//上課結束時間(Week	
			$Sql = " select DISTINCT ";
			$Sql .= " DATE_ADD(StartTime,INTERVAL 
					case when (select count(*) from TemplateDetail where 1 = 1 and TemplateTeachingPlanId = (select D.Id from TemplateTeachingPlan D where 1 = 1 and D.TemplateId = M.TemplateId limit 1))%Cast((Length(ClassWeek)+1)/2 as SIGNED) = 0 then floor((select count(*) from TemplateDetail where 1 = 1 and TemplateTeachingPlanId = (select D.Id from TemplateTeachingPlan D where 1 = 1 and D.TemplateId = B.TemplateId limit 1))/Cast((Length(ClassWeek)+1)/2 as 
						  SIGNED))
						 when (select count(*) from TemplateDetail where 1 = 1 and TemplateTeachingPlanId = (select D.Id from TemplateTeachingPlan D where 1 = 1 and D.TemplateId = M.TemplateId limit 1))%Cast((Length(ClassWeek)+1)/2 as SIGNED) > 0 then floor((select count(*) from TemplateDetail where 1 = 1 and TemplateTeachingPlanId = (select D.Id from TemplateTeachingPlan D where 1 = 1 and D.TemplateId = B.TemplateId limit 1))/Cast((Length(ClassWeek)+1)/2 as 
						  SIGNED))+1
					End
					Week) ";
			$Sql .= " from Class M ";
			$Sql .= " left join Template A on A.Id = M.TemplateId ";
			$Sql .= " left join TemplateTeachingPlan B on B.TemplateId = A.Id ";
			$Sql .= " where 1 = 1 ";
			$Sql .= " and M.Id = '".$DataKey."' ";
		
			$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
			$EndWeek = $MySql -> db_result($SqlRun);
		//抓出Detail 時間
			$Sql = " select Id from TemplateDetail ";
			$Sql .= " where 1 = 1 ";
			$Sql .= " and TemplateTeachingPlanId in (select Id from TemplateTeachingPlan where 1 = 1 and TemplateId = '".$TemplateId."') ";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
			$DetailAry = $MySql -> db_array($Sql,2);
		
		//存入時間ClassTime
			$ClassWeek = explode(",",$ClassWeeklID);			//一個禮拜上幾天
		
			$begin = strtotime($StartTime);
		
			$end = strtotime($EndWeek);
		
			$wdayBegin = date('w', $begin);
			if($wdayBegin == '0'){
				$wdayBegin = '7';
			}
			
			$DateAry = array();					//array
		
			for($c=0;$c<count($ClassWeek);$c++){
				
				if($ClassWeek[$c] == '1'){
					$DayNM = 'next Monday';
				}else if($ClassWeek[$c] == '2'){
					$DayNM = 'next Tuesday';
				}else if($ClassWeek[$c] == '3'){
					$DayNM = 'next Wednesday';
				}else if($ClassWeek[$c] == '4'){
					$DayNM = 'next Thursday';		
				}else if($ClassWeek[$c] == '5'){
					$DayNM = 'next Friday';
				}else if($ClassWeek[$c] == '6'){
					$DayNM = 'next Saturday';
				}else if($ClassWeek[$c] == '7'){
					$DayNM = 'next Sunday';			
				}
						
				if ($wdayBegin === $ClassWeek[$c]) {
					$currentTime = $begin;
				} else {
					$currentTime = strtotime($DayNM, $begin);
					//echo date('Y-m-d', $currentTime);
				}
				
				while ($currentTime <= $end) {
					//echo date('Y-m-d', $currentTime);	
					array_push($DateAry, date('Y-m-d', $currentTime));
					
					$currentTime += 604800;
				}
			}
		
			usort($DateAry, function ($a, $b){
				return strtotime($a) - strtotime($b);
			});
			print_r($DateAry);
		
//			echo $DateAry[0];
//			echo $DateAry[1];
//			echo $DateAry[2];	
//			echo $DateAry[3];
//			echo $DateAry[4];
			
			//Delete
				$Sql = " Delete from ClassTime ";
				$Sql .= " where 1 = 1 ";
				$Sql .= " and ClassId = '" .$DataKey. "' ";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			
			//寫入
			for($i=0;$i<count($DetailAry);$i++){
					
				$j = $i % $CountDay ; 	
				$Sql = " insert into ClassTime";
				$Sql .= " values ";
				$Sql .= " ( ";
				$Sql .= " '', '".$DataKey."', '".$DateAry[$j]."', '".$DetailAry[$i][0]."' ";
				$Sql .= " ) ";	
				$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
				
			}
//學生		
		if($StudentId != ''){
			//刪除
				$Sql = " DELETE from ClassMember WHERE ClassId = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			//存入
			if($StudentId_TEXT != ''){
				for($i=0;$i<count($StudentId);$i++){
					$Sql = " insert into ClassMember (Id, ClassId, StudentId, DeleteStatus)";
					$Sql .= " values('','" .$DataKey. "','" .$StudentId[$i]. "', 'N')";
					$MedRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
				}
			}
		}
		
		
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
