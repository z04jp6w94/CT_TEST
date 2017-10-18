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
		$Comment = trim(GetxRequest("Comment", 30));
		$Description = trim(GetxRequest("Description", 30));
		$Level = trim(GetxRequest("Level", 30));
		if($Level == ''){
			$Level = '';
		}
		$success = true;
	//判斷名字重複
		$Sql = " select * from SystemParameter";
		$Sql .= " where 1 = 1 ";
		$Sql .= " and Comment = '".$Comment."' ";	
		$Sql .= " and Description = '".$Description."' ";
		if($Level != ''){
		$Sql .= " and LevelKey = '".$Level."' ";
		}
		$Sql .= " and DeleteStatus = 'N' ";
		$SRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
		$RowCount = $MySql -> db_num_rows($SRun);
		
		if($RowCount <= 0 ){	
		//編號
			$Sql = " select Id from SystemParameter ";
			$Sql .= " where 1 = 1 ";
			$Sql .= " order by Id desc ";
			$Sql .= " limit 1";
			$SysNum = $MySql -> db_query($Sql) or die("查詢錯誤");
			$SysId = $MySql -> db_result($SysNum);
			
			if($SysId==''){
				$SysId = '000000000000001';	
			}else{
				$SysId +=1 ;
				$SysId=str_pad($SysId,15,"0",STR_PAD_LEFT);
			}		
		
		//判斷編號	
			$Sql = " select Id from SystemParameter ";
			$Sql .= " where 1 = 1 ";
			$Sql .= " order by Id desc ";
			$Sql .= " limit 1";
			$SysNum = $MySql -> db_query($Sql) or die("查詢錯誤");
			$SysId = $MySql -> db_result($SysNum);
			
			if($SysId==''){
				$SysId = '000000000000001';	
			}else{
				$SysId +=1 ;
				$SysId=str_pad($SysId,15,"0",STR_PAD_LEFT);
			}				
			
			
			$Sql = " insert into SystemParameter ";
			$Sql .= " values";
			$Sql .= " (";
			$Sql .= " '".$SysId."', '".$Comment."', '".$Description."', '".$Level."', 'N' ";
			$Sql .= " )";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");		
		}else{
			$success = false;
			$rtmsg = '重複命名';	
		}
	//關閉資料庫連線
		$MySql -> db_close();
	}
	//狀態回執
		header('Content-type: application/xml');
		echo '<?xml version="1.0"?>';
		echo '<response>';
		if ($success){ 
			echo '<resu>1</resu>';
		}elseif ($success == false ){ 
			echo '<resu>0</resu>';
			echo '<rtmsg>'.$rtmsg.'</rtmsg>';
		}
		echo '</response>';	
?>
