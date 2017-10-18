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
//		撰寫人員：小夯
//		撰寫日期：20140728
//		程式功能：luckysparks / 最新消息 / 寫入
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：ad_m
//		　　　　　del：None
//		　　　　　upt：None
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
	$now_count = trim(GetxRequest("now_count")); 											//家長數目
//預防接種
	$Vaccination = trim(GetxRequest("Vaccination_TEXT"));
	$VacAry = explode(",",$Vaccination);
//曾患疾病	
	$OnceIllness = trim(GetxRequest("OnceIllness_TEXT"));
	$OnceAry = explode(",",$OnceIllness);
//常患疾病
	$OftenIllness = trim(GetxRequest("OftenIllness_TEXT"));
	$OftenAry = explode(",",$OftenIllness);
//家族病史
	$FamilyHistory = trim(GetxRequest("FamilyHistory_TEXT"));
	$FamAry = explode(",",$FamilyHistory);
//地址
	$Same = trim(GetxRequest("Same"));
	if($Same =='Y'){
		$ResidenceAddress = trim(GetxRequest("MailingAddress"));	
	}else{
		$ResidenceAddress = trim(GetxRequest("ResidenceAddress"));		
	}
	$Client_ID = $USER_Client_ID;
//編號
	$Sql = " select Id from Student ";
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
//接收疾病史
	$OptionalID = trim(GetxRequest("OptionalID_TEXT"));
	$Med = explode(",",$OptionalID);
//寫入資料
	$Sql = " Select * from Student where 1 = 2 ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
	if($RowCount >= 1){
		echo '<script>';
		echo 'alert("KEY值重複，請重新輸入");';
		echo 'window.history.back();';
		echo '</script>';
		exit();
	}else{
	//Auto Insert
		while ($initAry = mysql_fetch_field($initRun)){
			switch($initAry -> name){
			//不處理的欄位
//				case 'Id':
//					break;
			//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
//				case 'NewsM_Sort':
//					$val[$initAry -> name] = 0;
//					break;
				case 'Id':
					$val[$initAry -> name]= $Id;
					break;
				case 'ResidenceAddress':
					$val[$initAry -> name]= $ResidenceAddress;
					break;
				case 'Larger':
					if ($_POST["upText"][0] != ""){
						MMkDir(root_path . Student);
						rename(root_path . temp . $_POST["upText"][0], root_path . Student . $_POST["upText"][0]);
//						RunReSize($FilePath, $FilePath . "ReSize/",$_POST["upText"][0],"390_" . $_POST["upText"][0],"390","");
						$val[$initAry -> name] = $_POST["upText"][0];
//						UploadLog($MySql, $FilePath, $_POST["upText"][0], $PG_ID);
					}
					break;	
				case 'Small':
					$ext = end(explode('.', root_path . Student . $_POST["upText"][0]));
					$SmallFileNm = getRndFileName().".".$ext ;
					$ImgMin = ImageResize(root_path . Student . $_POST["upText"][0], root_path . Student . $SmallFileNm, '400', '300', '100');

					$val[$initAry -> name] = $SmallFileNm;
					break;	
				case 'Enabled':
				case 'DeleteStatus':
					$val[$initAry -> name] = 'N';
					break;
				case 'CreatorId':
				case 'LastEditorId':
					$val[$initAry -> name] = $USER_NM;
					break;
				case 'CreateDate':
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
		$MySql -> setTable("Student");
		$MySql -> insertVal($val);
	//存入家長資料
		for($i=1;$i<=$now_count;$i++){
			$Sql = " insert into Parent";
			$Sql .= " values('','" .$Id. "','".GetxRequest("Name".$i."")."', '".GetxRequest("Tel1".$i."")."', '".GetxRequest("Tel2".$i."")."', '".GetxRequest("Mobile1".$i."")."', '".GetxRequest("Mobile2".$i."")."' ";
			$Sql .= " , '".GetxRequest("KinshipId".$i."")."', '".GetxRequest("IsCarer".$i."")."', '".GetxRequest("IsEmergency".$i."")."', '', '', '', '', '', '', '' )";
			$ParRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");			
		}
	//存入預防接種資料
		for($i=0;$i<count($VacAry);$i++){
			$Sql = " insert into Vaccination_T (No, Id, VaccinationId)";
			$Sql .= " values('','" .$Id. "','" .$VacAry[$i]. "')";
			$VacRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
		}
	//存入曾患疾病資料
		for($i=0;$i<count($OnceAry);$i++){
			$Sql = " insert into OnceIllness_T (No, Id, OnceIllnessId)";
			$Sql .= " values('','" .$Id. "','" .$OnceAry[$i]. "')";
			$OnceRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
		}
	//存入常患疾病資料
		for($i=0;$i<count($OftenAry);$i++){
			$Sql = " insert into OftenIllness_T (No, Id, OftenIllnessId)";
			$Sql .= " values('','" .$Id. "','" .$OftenAry[$i]. "')";
			$OftenRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
		}
	//存入家族病史資料
		for($i=0;$i<count($FamAry);$i++){
			$Sql = " insert into FamilyHistory_T (No, Id, FamilyHistoryId)";
			$Sql .= " values('','" .$Id. "','" .$FamAry[$i]. "')";
			$FamRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
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
