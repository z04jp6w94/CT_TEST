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
//		程式功能：luckysparks / 最新消息 / 新增
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
//	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//
		$FileNM=$_FILES["FileUp"]["name"];

		$ObjNM=GetxRequest("ObjNM");

		$UpLoadBtnNM=GetxRequest("UpLoadBtnNM");

		$DeleteBtnNM=GetxRequest("DeleteBtnNM");
		
			if($_FILES["FileUp"]["name"] != ''){
			//相關設定
				$UploadDir = root_path . TeacherIMG;																		// 上傳路徑
				MMkDir($UploadDir);																						// 建立資料夾
				$Sizebytes = 1024 * 1024 * intval(100);																	// 上傳檔大小限制，此處限制為2 * 1024KB (2MB)
				$limitedext = array(".gif",".jpg",".jpeg",".png");														// 副檔名限制
			//檔案資訊
				$File_defName = $_FILES["FileUp"]["name"];														// 上傳檔案的原始名稱
				$File_newName = $ExDate . $ExTime . "." . substr($File_defName , strrpos($File_defName, ".") + 1);	// 存入暫存區的檔名
				$File_tmpName = $_FILES["FileUp"]["tmp_name"];													// 上傳檔案後的暫存資料夾位置。
				$File_size = $_FILES["FileUp"]["size"];															// 上傳的檔案原始大小。
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
//							if (move_uploaded_file($File_tmpName, $UploadDir . $File_newName)) {
//								$Sql = " Update ad_m Set FileUp = '$File_newName' ";
//								$Sql .= " where 1 = 1 and AdM_No = $LastID ";	
//								$MySql ->db_query($Sql) or die("查詢 Query 錯誤9");
//							}else{
//								
//							}
						}
					}
				}
			}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<title>檔案上傳</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="/Css/maintain/maintain.css" rel="stylesheet" type="text/css">

<script src="/Js/comFunc.js"></script>

<script language="javascript">

	FileNM="<?php echo $FileNM ?>";

	ObjNM="<?php echo $ObjNM ?>";

	UpLoadBtnNM="<?php echo $UpLoadBtnNM ?>";

	DeleteBtnNM="<?php echo $DeleteBtnNM ?>";

	if (FileNM!="" && ObjNM!=""){

		var wObj=opener.window.document.getElementById(ObjNM)

		wObj.value=FileNM;

		try{

			//IE

			wObj.fireEvent('onchange');

			wObj.focus();

		}catch(e){

			try{

				//FF

				wObj.onchange();

				wObj.focus();

			}catch(e1){

			}

		}

		wObj=opener.window.document.getElementById(UpLoadBtnNM)

		if (wObj.name == UpLoadBtnNM){wObj.disabled = true;}

		wObj=opener.window.document.getElementById(DeleteBtnNM)

		if (wObj.name == DeleteBtnNM){wObj.disabled = false;}

		window.close();

	}else{

		alert('請選擇檔案');

		window.history.back();

	}

</script>

</head>

<body>

</body>

</html>



