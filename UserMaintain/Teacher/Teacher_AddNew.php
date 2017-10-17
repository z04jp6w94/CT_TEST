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
	$Client_ID = '';
	$DrugallergyStatus = trim(GetxRequest("DrugallergyStatus"));
	$Account = trim(GetxRequest("Account"));
	$PWD = trim(GetxRequest("PWD"));
	//密碼加密	
		$SecretPwd = ENCCode($PWD);	
	//
	if($DrugallergyStatus == '3'){
		$Drugallergy = trim(GetxRequest("Drugallergy"));
	}else{
		$Drugallergy = '';
	}
	$Smoke = trim(GetxRequest("Smoke"));
	if($Smoke == 'Y'){
		$SmokeUseNumber = trim(GetxRequest("SmokeUseNumber"));
		$SmokeAge = trim(GetxRequest("SmokeAge"));
	}else if($Smoke =='O'){
		$SmokeQuitAge = trim(GetxRequest("SmokeQuitAge"));
	}
	$Areca = trim(GetxRequest("Areca"));
	if($Areca == 'Y'){
		$ArecaUseNumber = trim(GetxRequest("ArecaUseNumber"));
		$ArecaAge = trim(GetxRequest("ArecaAge"));
	}else if($Areca =='O'){
		$ArecaQuitAge = trim(GetxRequest("ArecaQuitAge"));
	}
	
	$UseApp_TEXT = trim(GetxRequest("UseApp_TEXT"));
//	if(stristr($UseApp_TEXT, "1")){
		$Client_ID = $USER_Client_ID;
//	}
//編號
	$Sql = " select Id from Personnel ";
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
	$Sql = " Select * from Personnel where 1 = 1 and Account = '$Account' and IdentityTypeId = '000000000000002' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
	if($RowCount >= 1){
		echo '<script>';
		echo 'alert("帳號重複，請重新輸入");';
		echo 'window.location.href="/UserMaintain/Teacher/Teacher_Input.php";';
		echo '</script>';
		exit();
	}else{
	//Auto Insert
		while ($initAry = mysql_fetch_field($initRun)){
			switch($initAry -> name){
			//不處理的欄位
//				case '':
//					break;
			//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
				case 'Id':
					$val[$initAry -> name]= $Id;
					break;
				case 'PWD':
					$val[$initAry -> name]= $SecretPwd;
					break;
				case 'IdentityTypeId':
					$val[$initAry -> name]= '000000000000002';
					break;
				case 'UseApp':
					$val[$initAry -> name]= $UseApp_TEXT;
					break;
				case 'Drugallergy':
					$val[$initAry -> name]= $Drugallergy;
					break;
				case 'DrugallergyStatus':
					$val[$initAry -> name]= $DrugallergyStatus;
					break;
				case 'SmokeAge':
					$val[$initAry -> name]= $SmokeAge;
					break;
				case 'SomkeUseNumber':
					$val[$initAry -> name]= $SmokeUseNumber;
					break;
				case 'SmokeQuitAge':
					$val[$initAry -> name]= $SmokeQuitAge;
					break;
				case 'ArecaUseNumber':
					$val[$initAry -> name]= $ArecaUseNumber;
					break;
				case 'ArecaAge':
					$val[$initAry -> name]= $ArecaAge;
					break;
				case 'ArecaQuitAge':
					$val[$initAry -> name]= $ArecaQuitAge;
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
		$MySql -> setTable("Personnel");
		$MySql -> insertVal($val);
	//存入疾病史
		for($i=0;$i<count($Med);$i++){
			$Sql = " insert into DiseaseT (No, Id, OptionalID)";
			$Sql .= " values('','" .$Id. "','" .$Med[$i]. "')";
			$MedRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
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
