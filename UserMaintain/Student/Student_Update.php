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
	$now_count = trim(GetxRequest("now_count")); 											//家長數目	

/*	if($_POST["upText"][0] == ''){
		echo '<script>';
		echo 'alert("請上傳圖片");';
		echo 'window.history.back();';
		echo '</script>';
		exit();
	}*/
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
//取出修改資料
	$Sql = " Select * from Student where Id = $DataKey ";
	$MDAdM01ModifyRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($MDAdM01ModifyRun);
//更新資料
	$Sql = " Select * from Student where Id = $DataKey ";
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
				case 'Larger':
					if ($_POST["upText"][0] != "" & $_POST["upText"][0] != $rs["Larger"]){
						MMkDir(root_path . Student);
						rename(root_path . temp . $_POST["upText"][0], root_path . Student . $_POST["upText"][0]);
//						RunReSize($FilePath, $FilePath . "ReSize/",$_POST["upText"][0],"390_" . $_POST["upText"][0],"390","");
						$val[$initAry -> name] = $_POST["upText"][0];
//						UpLoadLog($MySql, $FilePath, $_POST["upText"][0], $PG_ID);
					}else{
						if (!FileExist(root_path . Student . $rs["Larger"])){
							$val[$initAry -> name] = "";
						}
					}
					break;
				case 'Small':
					if ($_POST["upText"][0] != "" & $_POST["upText"][0] != $rs["Larger"]){
						$ext = end(explode('.', root_path . Student . $_POST["upText"][0]));
						$SmallFileNm = getRndFileName().".".$ext ;
						$ImgMin = ImageResize(root_path . Student . $_POST["upText"][0], root_path . Student . $SmallFileNm, '400', '300', '100');
						$val[$initAry -> name] = $SmallFileNm;
					}else{
						$val[$initAry -> name] = $rs["Small"]; 
					}
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
		$MySql -> setTable('Student');
		$MySql -> updateOne($val, $where);
		
		if($now_count != ''){
			//刪除
				$Sql = " DELETE from Parent WHERE StudentId = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");			
			//存入家長資料
				for($i=1;$i<=$now_count;$i++){
					$Sql = " insert into Parent";
					$Sql .= " values('','" .$DataKey. "','".GetxRequest("Name".$i."")."', '".GetxRequest("Tel1".$i."")."', '".GetxRequest("Tel2".$i."")."', '".GetxRequest("Mobile1".$i."")."', '".GetxRequest("Mobile2".$i."")."' ";
					$Sql .= " , '".GetxRequest("KinshipId".$i."")."', '".GetxRequest("IsCarer".$i."")."', '".GetxRequest("IsEmergency".$i."")."', '', '', '', '', '', '', '' )";
					$ParRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");			
				}
		}
		if($Vaccination != ''){
			//刪除
				$Sql = " DELETE from Vaccination_T WHERE Id = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			//存入預防接種資料
				for($i=0;$i<count($VacAry);$i++){
					$Sql = " insert into Vaccination_T (No, Id, VaccinationId)";
					$Sql .= " values('','" .$DataKey. "','" .$VacAry[$i]. "')";
					$VacRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
				}
		}
		if($OnceIllness != ''){
			//刪除
				$Sql = " DELETE from OnceIllness_T WHERE Id = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
			//存入曾患疾病資料
				for($i=0;$i<count($OnceAry);$i++){
					$Sql = " insert into OnceIllness_T (No, Id, OnceIllnessId)";
					$Sql .= " values('','" .$DataKey. "','" .$OnceAry[$i]. "')";
					$OnceRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
				}
		}
		if($OftenIllness != ''){
			//刪除
				$Sql = " DELETE from OftenIllness_T WHERE Id = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");		
			//存入常患疾病資料
				for($i=0;$i<count($OftenAry);$i++){
					$Sql = " insert into OftenIllness_T (No, Id, OftenIllnessId)";
					$Sql .= " values('','" .$DataKey. "','" .$OftenAry[$i]. "')";
					$OftenRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
				}
		}
		if($FamilyHistory != ''){
			//刪除
				$Sql = " DELETE from FamilyHistory_T WHERE Id = '" .$DataKey. "'";
				$DeleteRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");		
			//存入家族病史資料
				for($i=0;$i<count($FamAry);$i++){
					$Sql = " insert into FamilyHistory_T (No, Id, FamilyHistoryId)";
					$Sql .= " values('','" .$DataKey. "','" .$FamAry[$i]. "')";
					$FamRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
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
