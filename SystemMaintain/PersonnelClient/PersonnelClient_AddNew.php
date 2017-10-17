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
	$USER_NM = $_SESSION["M_USER_NM"];
	$USER_ROLE = $_SESSION["M_USER_ROLE"];
//資料庫連線
	$MySql = new mysql();
//搜尋Functions_T找尋所屬代碼
	$Now_MenuArr = NowFunction($_SERVER['PHP_SELF'],$MySql);
	$Now_Menu = $Now_MenuArr[0][0];
	$BackPage = $Now_MenuArr[0][1];
	$MSG = $Now_MenuArr[0][2];
//使用權限
	Chk_Login($USER_ID);															//檢查是否有登入後台，並取得允許執行的權限
	GetTreeView($USER_ROLE,$MySql);
	RoleFunction($USER_ROLE,$Now_Menu,$MySql);
	if($result1 == 'N'){
		$_SESSION['MSG'] = $MSG;
		header("Location:".$BackPage);
	}else{
	//定義一般參數
		$UserForm = trim(GetxRequest("UserForm")); 
		$Account = trim(GetxRequest("Account"));
		$KindergartenName = trim(GetxRequest("KName"));
		$PWD = trim(GetxRequest("PWD"));
	//密碼加密	
		$SecretPwd = ENCCode($PWD);
	//Status1	
		$StartDate = trim(GetxRequest("StartDate"));
		$EndDate = trim(GetxRequest("EndDate"));
		$TeacherNumber = trim(GetxRequest("TeacherNumber"));
		$StudentNumber1 = trim(GetxRequest("StudentNumber1"));
		
		$List2_TEXT = trim(GetxRequest("List2_TEXT"));	//TeachingPlan in 
		
		$SystemId = explode(",",$List2_TEXT);
	//Status2
		$StartDate3 = trim(GetxRequest("StartDate3"));
		$EndDate3 = trim(GetxRequest("EndDate3"));
		$TeacherNumber2 = trim(GetxRequest("TeacherNumber2"));
		$StudentNumber2 = trim(GetxRequest("StudentNumber2"));
		
		$List4_TEXT = trim(GetxRequest("List4_TEXT"));
		
		$SystemId2 = explode(",",$List4_TEXT);	
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
		$Sql = " Select * from Personnel where 1 = 1 and Account= '$Account' and IdentityTypeId = '000000000000001' and DeleteStatus = 'N' ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		if($RowCount >= 1){
			echo '<script>';
			echo 'alert("帳號重複，請重新輸入");';
			echo 'window.location.href="/SystemMaintain/PersonnelClient/PersonnelClient_Input.php";';
			echo '</script>';
			exit();
		}else{
		//Auto Insert
			while ($initAry = mysql_fetch_field($initRun)){
				switch($initAry -> name){
				//不處理的欄位
//					case '':
//					break;
				//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
					case 'Id':
						$val[$initAry -> name] = $Id;
						break;
					case 'PWD':
						$val[$initAry -> name] = $SecretPwd;
						break;
					case 'IdentityTypeId':
						$val[$initAry -> name] = '000000000000001';
						break;
					case 'KindergartenName':
						$val[$initAry -> name] = $KindergartenName;
						break;
					case 'UseApp':
						$val[$initAry -> name] = '1';
						break;
					case 'Language':
						$val[$initAry -> name] = 'zh-tw';
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
	
	//License編號
	if($List2_TEXT != ''){
		$Sql = " select Id from License ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " order by Id desc ";
		$Sql .= " limit 1";
		$LicenseNum = $MySql -> db_query($Sql) or die("查詢錯誤");
		$LicenseId = $MySql -> db_result($LicenseNum);
		
		if($LicenseId==''){
			$LicenseId = '000000000000001';	
		}else{
			$LicenseId +=1 ;
			$LicenseId=str_pad($LicenseId,15,"0",STR_PAD_LEFT);
		}
				
		//寫入License	Status1		
			$Sql = " insert into License ";
			$Sql .= " values";
			$Sql .= " (";
			$Sql .= " '".$LicenseId."', '".$Id."', '000000000000041', 'Key', '".$StartDate."', ";
			$Sql .= " '".$EndDate."', '".$TeacherNumber."', '".$StudentNumber1."', 'Y', 'N', '', '".$ExID."', '".$ExDate.$ExTime."', '".$ExID."', '".$ExDate.$ExTime."'";
			$Sql .= " )";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");			
		//寫入LicenseTeachingPlan
			for($i=0;$i<count($SystemId);$i++){
				$Sql = " insert into LicenseTeachingPlan ";
				$Sql .= " values";
				$Sql .= " (";
				$Sql .= " '', '".$LicenseId."', '".$SystemId[$i]."' ";
				$Sql .= " )";
				$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
			}
	}
	//License編號
	if($List4_TEXT != ''){
		$Sql = " select Id from License ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " order by Id desc ";
		$Sql .= " limit 1";
		$LicenseNum2 = $MySql -> db_query($Sql) or die("查詢錯誤");
		$LicenseId = $MySql -> db_result($LicenseNum2);
		
		if($LicenseId==''){
			$LicenseId = '000000000000001';	
		}else{
			$LicenseId +=1 ;
			$LicenseId=str_pad($LicenseId,15,"0",STR_PAD_LEFT);
		}
				
		//寫入License	Status2	
			$Sql = " insert into License ";
			$Sql .= " values";
			$Sql .= " (";
			$Sql .= " '".$LicenseId."', '".$Id."', '000000000000042', 'Key', '".$StartDate3."', ";
			$Sql .= " '".$EndDate3."', '".$TeacherNumber2."', '".$StudentNumber2."', 'Y', 'N', '', '".$ExID."', '".$ExDate.$ExTime."', '".$ExID."', '".$ExDate.$ExTime."'";
			$Sql .= " )";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");			
		//寫入LicenseTeachingPlan
			for($i=0;$i<count($SystemId2);$i++){
				$Sql = " insert into LicenseTeachingPlan ";
				$Sql .= " values";
				$Sql .= " (";
				$Sql .= " '', '".$LicenseId."', '".$SystemId2[$i]."' ";
				$Sql .= " )";
				$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
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
	}
?>
