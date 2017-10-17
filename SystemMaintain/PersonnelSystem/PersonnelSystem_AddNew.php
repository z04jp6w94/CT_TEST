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
	$ExID = $_SESSION["M_USER_ID"];															//操作 ID
	$ExDate = date("Ymd");																	//操作 日期
	$ExTime = date("His");																	//操作 時間
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$Account = trim(GetxRequest("Account"));
	$PWD = trim(GetxRequest("PWD"));
//密碼加密	
	$SecretPwd = ENCCode($PWD);
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
			
//寫入資料
	$Sql = " Select * from Personnel where 1 = 1 and Account= '$Account'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
	if($RowCount >= 1){
		echo '<script>';
		echo 'alert("帳號重複，請重新輸入");';
		echo 'window.location.href="/SystemMaintain/PersonnelSystem/PersonnelSystem_Input.php";';
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
					$val[$initAry -> name] = $Id;
					break;
				case 'PWD':
					$val[$initAry -> name] = $SecretPwd;
					break;
				case 'IdentityTypeId':
					$val[$initAry -> name] = '000000000000000';
					break;
				case 'Enabled':
				case 'DeleteStatus':
					$val[$initAry -> name] = 'N';
					break;
				case 'CreatorId':
				case 'LastEditorId':
					$val[$initAry -> name] = $ExID;
					break;
				case 'CreateDate':
				case 'LastEditDate':
					$val[$initAry -> name] = $ExDate.$ExTime;
					break;
				default:
					$val[$initAry -> name] = trim(GetxRequest($initAry -> name));
					break;
			}
		}
		$MySql -> setTable("Personnel");
		$MySql -> insertVal($val);
	//取得序號
		$Sql = " Select LAST_INSERT_ID() as 'LastID' ";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
		$LastID = $MySql -> db_result($SqlRun);
	}
//關閉資料庫連線
	$MySql -> db_close();
//狀態回執
	if($rtnURL != ""){	
		header("location:$rtnURL");
	}else{
		header("location:PersonnelSystem.php?");
	}
?>
