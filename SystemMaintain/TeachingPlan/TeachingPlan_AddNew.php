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
		$DescriptionAry = $_POST ["Description"];
		$hardAry = $_POST ["hard"];
		$talkAry = $_POST ["talk"]; 
		$Name = trim(GetxRequest("Name"));
		$Teaching = trim(GetxRequest("Teaching_TEXT"));
		$TeachingAry = explode(",",$Teaching);		
	//編號
		$Sql = " select Id from TeachingPlan ";
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
	//TeachingObjectives編號
		$Sql = " select Id from TeachingObjectives ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " order by Id desc ";
		$Sql .= " limit 1";	
		$ObjectivesNum = $MySql -> db_query($Sql) or die("查詢錯誤");
		$ObjectivesId = $MySql -> db_result($ObjectivesNum);
		
		if($ObjectivesId==''){
			$ObjectivesId = '000000000000001';	
		}else{
			$ObjectivesId +=1 ;
			$ObjectivesId = str_pad($ObjectivesId,15,"0",STR_PAD_LEFT);
		}	
		
	//寫入資料
		$Sql = " Select * from TeachingPlan where 1 = 1 and Name = '".$Name."'  ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		if($RowCount >= 1){
			echo '<script>';
			echo 'alert("教案名稱重複，請重新輸入");';
			echo 'window.history.back();';
			echo '</script>';
			exit();
		}else{
		//Auto Insert
			while ($initAry = mysql_fetch_field($initRun)){
				switch($initAry -> name){
				//不處理的欄位
				//	case '':
				//		break;
				//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
					case 'Id':
						$val[$initAry -> name] = $Id;
						break;
					case 'Enable':
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
			$MySql -> setTable("TeachingPlan");
			$MySql -> insertVal($val);
		//上傳圖片說名
			for($i=0;$i<count($DescriptionAry);$i++){
				
				if($_FILES["imgupload"]["name"][$i] != ''){
				//相關設定
					$UploadDir = root_path . TeachingPlan;																		// 上傳路徑
					MMkDir($UploadDir);																						// 建立資料夾
					$Sizebytes = 1024 * 1024 * intval(100);																	// 上傳檔大小限制，此處限制為2 * 1024KB (2MB)
					$limitedext = array(".gif",".jpg",".jpeg",".png");														// 副檔名限制
				//檔案資訊
					$File_defName = $_FILES["imgupload"]["name"][$i];														// 上傳檔案的原始名稱
					$File_newName = $ExDate . $ExTime . $i . "." . substr($File_defName , strrpos($File_defName, ".") + 1);	// 存入暫存區的檔名
					$File_tmpName = $_FILES["imgupload"]["tmp_name"][$i];													// 上傳檔案後的暫存資料夾位置。
					$File_size = $_FILES["imgupload"]["size"][$i];															// 上傳的檔案原始大小。
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
									//寫入TeachingPlanAction
									$count = $i+1;
									$Sql = " insert into TeachingPlanAction ";
									$Sql .= " values";
									$Sql .= "(";
									$Sql .= " '', '".$Id."', '".trim($DescriptionAry[$i])."', '".$File_newName."', '".$count."', 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
									$Sql .= ")";	
									$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤TPA");
										
								}else{
								}
							}
						}
					}
				}else{
					
					$count = $i+1;
					$Sql = " insert into TeachingPlanAction ";
					$Sql .= " values ";
					$Sql .= " ( ";
					$Sql .= " '', '".$Id."', '".trim($DescriptionAry[$i])."', '', '".$count."', 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
					$Sql .= " ) ";
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤TPA");
					
				}
	
			}	
		//訓練目標	TeachingObjectives /**/
			for($i=0;$i<count($TeachingAry);$i++){
				$ObjectivesId = $ObjectivesId + $i;
				$ObjectivesId = str_pad($ObjectivesId,15,"0",STR_PAD_LEFT);	
				$Sql = " insert into TeachingObjectives";
				$Sql .= " values(";
				$Sql .= " '".$ObjectivesId."', '".$Id."', '".$TeachingAry[$i]."' ";
				$Sql .= ")";
	
				$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤TO");		
			}			
		//深入解說	talk
			for($i=0;$i<count($talkAry);$i++){
				$sort = $i+1;
				$Sql = " insert into TeachingComment";
				$Sql .= " values(";
				$Sql .= " '', '".$Id."', '".trim($talkAry[$i])."', ".$sort.", 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
				$Sql .= ")";
				$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			}
			
		//精益求精 TeachingAdvanced	hard
			for($i=0;$i<count($hardAry);$i++){
				$sort = $i+1;
				$Sql = " insert into TeachingAdvanced";
				$Sql .= " values(";
				$Sql .= " '', '".$Id."', '".trim($hardAry[$i])."', ".$sort.", 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
				$Sql .= ")";
				$SqlRunh = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
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

