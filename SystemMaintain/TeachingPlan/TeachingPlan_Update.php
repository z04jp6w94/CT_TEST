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
//使用權限
	Chk_Login($USER_ID);															//檢查是否有登入後台，並取得允許執行的權限
	GetTreeView($USER_ROLE,$MySql);
	RoleFunction($USER_ROLE,$Now_Menu,$MySql);
	if($result1 == 'N'){
		$_SESSION['MSG'] = $MSG;
		header("Location:".$BackPage);
	}else{
	//定義一般參數
		$DataKey = trim(GetxRequest("Id", 30));
		$DescriptionAry = $_POST ["Description"];
		$FileNM = $_POST ["FileNM"];
		$uploadAry = $_FILES["imgupload"]["name"];
		$hardAry = $_POST ["hard"];
		$talkAry = $_POST ["talk"];
		$SubLevelId = trim(GetxRequest("SubLevelId"));
		$Teaching = trim(GetxRequest("Teaching_TEXT"));
		$TeachingAry = explode(",",$Teaching);		
	//取出TeachingObjectives最大編號
		$Sql = " select Id from TeachingObjectives ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " order by Id desc ";
		$Sql .= " limit 1";	
		$Num = $MySql -> db_query($Sql) or die("查詢錯誤");
		$Id = $MySql -> db_result($Num);	
	//驗證教案名稱
		$Sql = " Select * from TeachingPlan where 1 = 1 and Name = '".$Name."'  ";
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		$RowCount = $MySql -> db_num_rows($initRun);
		if($RowCount >= 1){	
			echo '<script>';
			echo 'alert("教案名稱重複，請重新輸入");';
			echo 'window.history.back();';
			echo '</script>';
			exit();		
		}
	//更新資料
		$Sql = " Select * from TeachingPlan where Id = $DataKey ";
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
					case 'Enable':
					case 'DeleteStatus':
					case 'CreatorId':
					case 'CreateDate':
						break;
				//此處處理欄位特殊情形(含不在畫面上的欄位，需要格式化的欄位)
					case 'SubLevelId':
						$val[$initAry -> name] = $SubLevelId;
						break;
					case 'LastEditorId':
						$val[$initAry -> name] = $ExID;
						break;
					case 'LastEditDate':
						$val[$initAry -> name] = $ExDate.$ExTime;
						break;
					default:
						$val[$initAry -> name] = trim(GetxRequest($initAry -> name));
						break;
				}
			}
			$where = "Id = " . $DataKey;
			$MySql -> setTable('TeachingPlan');
			$MySql -> updateOne($val, $where);
			
		//刪除	
			$Sql = " Delete from TeachingPlanAction";
			$Sql .= " where TeachingPlanId ='" .$DataKey. "'";	
			$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
			
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
									$Sql .= " '', '".$DataKey."', '".trim($DescriptionAry[$i])."', '".$File_newName."', '".$count."', 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
									$Sql .= ")";	
									$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
								// 移除舊檔
									DeleteFile($UploadDir . trim(GetxRequest("OldAction_Img".$count)));		
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
					$Sql .= " '', '".$DataKey."', '".trim($DescriptionAry[$i])."', '".$FileNM[$i]."', '".$count."', 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
					$Sql .= " ) ";					
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
				}
			}
		//刪除訓練目標		
			$Sql = " Delete from TeachingObjectives";
			$Sql .= " where TeachingPlanId ='" .$DataKey. "'";	
			$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");		
		//訓練目標	TeachingObjectives
			for($i=0;$i<count($TeachingAry);$i++){
				$Id = $Id+$i+1;
				$Id = str_pad($Id,15,"0",STR_PAD_LEFT);	
				$Sql = " insert into TeachingObjectives";
				$Sql .= " values(";
				$Sql .= " '".$Id."', '".$DataKey."', '".$TeachingAry[$i]."' ";
				$Sql .= ")";
				$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤TO");		
			}
		//刪除深入解說
			$Sql = " Delete from TeachingComment";
			$Sql .= " where TeachingPlanId ='" .$DataKey. "'";	
			$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");		
		//深入解說	talk
			for($i=0;$i<count($talkAry);$i++){
				$sort = $i+1;
				$Sql = " insert into TeachingComment";
				$Sql .= " values(";
				$Sql .= " '', '".$DataKey."', '".trim($talkAry[$i])."', ".$sort.", 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
				$Sql .= ")";
				$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
			}
		//刪除精益求精
			$Sql = " Delete from TeachingAdvanced ";
			$Sql .= " where TeachingPlanId ='" .$DataKey. "'";	
			$SqlRunt = $MySql -> db_query($Sql) or die("查詢 Query 錯誤6");			
		//精益求精 TeachingAdvanced	hard
			for($i=0;$i<count($hardAry);$i++){
				$sort = $i+1;
				$Sql = " insert into TeachingAdvanced";
				$Sql .= " values(";
				$Sql .= " '', '".$DataKey."', '".trim($hardAry[$i])."', ".$sort.", 'N', '$ExID', '".$ExDate.$ExTime."', '$ExID', '".$ExDate.$ExTime."' ";
				$Sql .= ")";
				$SqlRunh = $MySql -> db_query($Sql) or die("查詢 Query 錯誤7");
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
