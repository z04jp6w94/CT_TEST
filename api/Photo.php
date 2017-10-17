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
//		撰寫人員：t
//		撰寫日期：20140728
//		程式功能：api 
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//定義全域參數

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");	
//
	$ExcuteSuccess = true;	
//setting
	$ExDate = date("Ymd");																					//操作 日期
	$ExTime = date("His");																					//操作 時間
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式
	$CourseId = trim(GetxRequest("CourseId"));
	$Index = trim(GetxRequest("Index"));
	
	$Path = Course;
	
	$Status = "S";
	
//上傳圖片
	if($_FILES["fileToUpload"]['name'] != ''){
	//相關設定
		$UploadDir = root_path . $Path; //account_path . $Path;																// 上傳路徑
		MMkDir($UploadDir);																									// 建立資料夾
		$limitedext = array(".gif",".jpg",".jpeg",".png");														// 副檔名限制
	//檔案資訊
		$File_defName = $_FILES["fileToUpload"]["name"];																		// 上傳檔案的原始名稱
		$File_newName = $ExDate . $ExTime . floor(microtime()*1000) . substr($File_defName , strrpos($File_defName, "."));	// 存入暫存區的檔名
		$File_tmpName = $_FILES["fileToUpload"]["tmp_name"];																	// 上傳檔案後的暫存資料夾位置。
		$File_size = $_FILES["fileToUpload"]["size"];																			// 上傳的檔案原始大小。
		
		$ext = strrchr($File_newName, '.');
		if (!in_array(strtolower($ext), $limitedext)) {
			$Status = "F";
			$msg = '檔案格式不正確';
			$ExcuteSuccess = false;
		//	return false;
		}else{
			if (move_uploaded_file($File_tmpName, $UploadDir . $File_newName)) {
				//UploadLog($MySql,$Path,$File_newName,$PG_ID);
			}
		}
	}	

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
	
	if($File_newName == ''){
		$Status = "F";
		$msg = '無上傳檔案';
		$ExcuteSuccess = false;	
	}
//檢驗身份 ?
	
//資料庫連線
	$MySql = new mysql();
	
	if($CourseId != '' && $Index != '' && $ExcuteSuccess=true){
	
		$Sql = " update Course ";
		$Sql .= " set Pic".$Index."L = '".$File_newName."' ";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Id = '".$CourseId."' ";		
		$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		
	}
//http://'.$_SERVER['HTTP_HOST'].Course	
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}else{
			$json .= '"FileName": "'.$File_newName.'",';	
		}
			$json .= '"DateTime": "'.$SystemDate.'"';			
		$json .= '}';
		
//關閉資料庫連線
	$MySql -> db_close();
		
		echo $json;
		exit ;
			
?>

