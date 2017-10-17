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
//定義一般參數
	$Client_ID = '';
	$DrugallergyStatus = trim(GetxRequest("DrugallergyStatus"));
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
//接收疾病史
	$OptionalID = trim(GetxRequest("OptionalID_TEXT"));
	$Med = explode(",",$OptionalID);	
//更新資料
	$Sql = " Select * from Personnel where Id = $DataKey ";
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
				case 'IdentityTypeId':
				case 'Enabled':
				case 'DeleteStatus':
				case 'Language':
				case 'CreatorId':
				case 'CreateDate':
					break;
			//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
				case 'PWD':
					$val[$initAry -> name]= $SecretPwd;
					break;
				case 'Drugallergy':
					$val[$initAry -> name]= $Drugallergy;
					break;
				case 'UseApp':
					$val[$initAry -> name]= $UseApp_TEXT;
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
		$MySql -> setTable('Personnel');
		$MySql -> updateOne($val, $where);
		
		if($OptionalID != ''){
			//刪除
				$Sql = " DELETE from DiseaseT WHERE Id = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			//存入疾病史
			for($i=0;$i<count($Med);$i++){
				$Sql = " insert into DiseaseT (No, Id, OptionalID)";
				$Sql .= " values('','" .$DataKey. "','" .$Med[$i]. "')";
				$MedRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
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
