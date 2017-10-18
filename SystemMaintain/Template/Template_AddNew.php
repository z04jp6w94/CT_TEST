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
		$rows = trim(GetxRequest("rows"));
		$cols = trim(GetxRequest("cols"));
		
	//編號
		$Sql = " select Id from Template ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " order by Id desc ";
		$Num = $MySql -> db_query($Sql) or die("查詢錯誤");
		$Id = $MySql -> db_result($Num);
		
		if($Id==''){
			$Id = '000000000000001';	
		}else{
			$Id +=1 ;
			$Id=str_pad($Id,15,"0",STR_PAD_LEFT);
		}	
		//第一行不處理
		$IdAry = array();
	
		for($j=0;$j<$cols;$j++){
			for($i=1;$i<$rows-1;$i++){
				if($j!=0){
					${"TeachingPlan".$i.$j} = $_POST ["TeachingPlan".$i.$j];				
					$Sql = " select Id from TeachingPlan ";
					$Sql .= " where 1 = 1 ";
					$Sql .= " and DeleteStatus = 'N'";
					$Sql .= " and Enable = 'Y'"; 
					$Sql .= " and Name = '".${"TeachingPlan".$i.$j}."' ";		
					//echo $Sql ;	
					$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$TeachId = $MySql -> db_result($initRun);;
					//$TAry = $MySql -> db_array($Sql,2);
			
					array_push($IdAry, $TeachId);			
				}
			}
		}
			
	//	exit ;
	//寫入資料
		$Sql = " Select * from Template where 1 = 2 ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		if($RowCount >= 1){
			echo '<script>';
			echo 'alert("帳號重複，請重新輸入");';
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
					case 'Id':
						$val[$initAry -> name] = $Id;
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
			$MySql -> setTable("Template");
			$MySql -> insertVal($val);
		//寫入TemplateTeachingPlan	j-->i
			$a = 0;
			
			for($j=0;$j<$cols;$j++){
				if($j != 0){
					$Sql = " insert into TemplateTeachingPlan ";
					$Sql .= " values";
					$Sql .= " (";
					$Sql .= " '', '".$Id."', '".$_POST ["TeachingPlan".$j]."'";
					$Sql .= " )";
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
					
					//取得序號
						$Sql = " Select LAST_INSERT_ID() as 'LastID' ";
						$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
						$LastID = $MySql -> db_result($SqlRun);	
						
					for($i=1;$i<$rows-1;$i++){
					//	$d= $i+1;						
						//寫入TemplateDetail	
						$Sql = " insert into TemplateDetail";
						$Sql .= " values";
						$Sql .= "(";
						$Sql .= " '', '$LastID', '".$i."', '".$IdAry[$a]."' ";
						$Sql .= ")";
						
						$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
						$a = $a+1 ;
					}
				}
			}
		
		//上傳檔案
			if($_FILES["importFile"]["name"] != ''){
				//相關設定
					$UploadDir = root_path . Template;																		// 上傳路徑
					MMkDir($UploadDir);																						// 建立資料夾
					$Sizebytes = 1024 * 1024 * intval(100);																	// 上傳檔大小限制，此處限制為2 * 1024KB (2MB)
					$limitedext = array(".csv",".xlsx",".xls");														// 副檔名限制
				//檔案資訊
					$File_defName = $_FILES["importFile"]["name"];														// 上傳檔案的原始名稱
					//$File_newName = $ExDate . $ExTime . "." . substr($File_defName , strrpos($File_defName, ".") + 1);	// 存入暫存區的檔名
					$File_newName = $Id . "." . substr($File_defName , strrpos($File_defName, ".") + 1);	// 存入暫存區的檔名
					$File_tmpName = $_FILES["importFile"]["tmp_name"];													// 上傳檔案後的暫存資料夾位置。
					$File_size = $_FILES["importFile"]["size"];															// 上傳的檔案原始大小。
				//判斷欄位是否有上傳檔案
					if(!is_uploaded_file($File_tmpName)){
					//	echo 'f';
					//	return false;
					}else{
					// 若有上傳檔，則取出該檔案的副檔名
						$ext = strrchr($File_newName, '.');
						if (!in_array(strtolower($ext), $limitedext)) {
							echo "over limit";
						//	return false;
						}else{
							if ($File_size > $Sizebytes){
							//	return false;
							//	echo "欄位 $j: ($file_name) 無法上傳，請檢查檔案是否小於 ". $size_bytes / 1024 ." KB。<br />";
							}else{
								if (move_uploaded_file($File_tmpName, $UploadDir . $File_newName)) {
									$Sql = " insert into TemplateLog ";
									$Sql .= " values"; 
									$Sql .= "(";
									$Sql .= " '', '".$Id."', 'Y', '".$File_newName."', 'ok', '$ExID', '".$ExDate.$ExTime."'";
									$Sql .= ")";
									$MySql ->db_query($Sql) or die("查詢 Query 錯誤9");	
								//	$Sql = " Update ad_m Set AdM_Img = '$File_newName' ";
								//	$Sql .= " where 1 = 1 and AdM_No = $LastID ";	
								//	$MySql ->db_query($Sql) or die("查詢 Query 錯誤9");
								}else{
								}
							}
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
			header("location:Template.php?");
		}
	}
?>
