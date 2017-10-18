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
//		撰寫日期：20131128
//		程式功能：連丰 / 系統程式 / 更新
//		使用參數：None
//		資　　料：sel：None
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：MUM03
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	session_start();
//定義全域參數
	$val = array();																			//寫入欄位名稱及值的陣列
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
//資料庫連線
	$MySql = new mysql();
//使用權限
	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
	ChkFunc_BeforeRunPG(3, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//定義一般參數ST
	$ExID = $_SESSION["M_USER_ID"];															//操作 ID
	$ExDate = date("Ymd");																	//操作 日期
	$ExTime = date("His");																	//操作 時間
	$SetType = trim(GetxRequest("ST"));
	$GP_ID = trim(GetxRequest("GI"));
	$IsSuccess = true;
	$OrderStr = trim(GetxRequest("saveOrder"));
	$PREV_TREE_ID = trim(GetxRequest("addDir"));
	$delId = trim(GetxRequest("deleteIds"));
	$renameId = trim(GetxRequest("renameId"));
	$newName = trim(GetxRequest("newName"));

	$a = $newName;
//	echo unescape( $a ); /* 輸出結果(UTF-8格式喔) --> ※此郵件由系統發出請勿直接回覆此郵件 */
	function unescape($str){ 
	$str= rawurldecode($str); 
	preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r); 
	$ar= $r[0]; 
	
	foreach( $ar as $k=>$v ) { 
	/* 下面的 UTF-8 可針對你的網頁編碼方式作變更 */ 
	if(substr($v,0,2)=="%u"){ 
	$ar[$k]=iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));} 
	elseif(substr($v,0,3)=="&#x"){ 
	$ar[$k]=iconv("UCS-2","UTF-8",pack("H4",substr($v,3,-1)));} 
	elseif(substr($v,0,2)=="&#"){ 
	$ar[$k]=iconv("UCS-2","UTF-8",pack("n",substr($v,2,-1)));} 
	} 
	return join("",$ar); 
	} 
	$newText = unescape($a);

//
	if($SetType == "" || $GP_ID == ""){
		exit();
	}else{
		if($SetType =="M"){
			if(strlen($OrderStr) > 0){
			//假如有傳回資料排序,則先更新目錄順序
				$Sql = " Delete from tmptree where MAIN_ID = '$GP_ID' ";
//				$Sql .= " ON DUPLICATE KEY ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
				$xAry = explode(",", $OrderStr);
				for($j = 0; $j < count($xAry); $j++){
					$yAry = explode("-", $xAry[$j]);
					$Sql = " insert into tmptree(MAIN_ID, TREE_ID, PREV_TREE_ID, SORT_NO)";
					$Sql .= " Select '$GP_ID' ";
					$Sql .= " , $yAry[0] ";
					$Sql .= " , $yAry[1] ";
					$Sql .= " , ifnull(max(ifnull(SORT_NO, 0)), 0) + 1 ";
					$Sql .= " From tmptree ";
					$Sql .= " Where MAIN_ID = '$GP_ID' and PREV_TREE_ID = $yAry[1] ";
					$MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
				}
				$Sql = " Update m_tree_s01 ";
				$Sql .= " Left join tmptree on m_tree_s01.GP_ID = tmptree.MAIN_ID and m_tree_s01.TREE_ID = tmptree.TREE_ID ";
				$Sql .= " set m_tree_s01.PREV_TREE_ID = tmptree.PREV_TREE_ID ";
				$Sql .= " , m_tree_s01.SORT_NO = tmptree.SORT_NO ";
				$Sql .= " Where m_tree_s01.GP_ID = '$GP_ID' ";
				$Sql .= " and tmptree.MAIN_ID is not null ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
				$Sql = " Delete from tmptree where MAIN_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
			}
			if(strlen($PREV_TREE_ID) > 0){
			//新增一層目錄
				$Sql = " Select count(*) ";
				$Sql .= " From m_tree_s01 Where GP_ID = '$GP_ID' and TREE_TYPE = 'D' and TREE_NM like '新資料夾%' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
				$xVal = $MySql -> db_result($SqlRun);

				$Sql = " insert into m_tree_s01 ";
				$Sql .= " (GP_ID, TREE_ID, TREE_NM, TREE_TYPE, PREV_TREE_ID, PG_ID, SORT_NO ";
				$Sql .= " , ENTRY_ID, ENTRY_DATE, ENTRY_TIME, MODIFY_ID, MODIFY_DATE, MODIFY_TIME) ";
				$Sql .= " Select '$GP_ID' ";
				$Sql .= " , ifnull(max(ifnull(TREE_ID, 0)), 0) + 1 ";
				if($xVal > 0){
					$Sql .= " ,(select '新資料夾' + convert(max(convert(replace(ifnull(TREE_NM, ''), '新資料夾', ''), SIGNED)) + 1, CHAR) from m_tree_s01 where GP_ID = '$GP_ID' and TREE_TYPE = 'D' and TREE_NM like '新資料夾%')";		//資料夾名稱
				}else{
					$Sql .= " ,'新資料夾1'";
				}
				$Sql .= " , 'D'";							//指定該目錄型態為程式
				$Sql .= " , '$PREV_TREE_ID' ";				//建在那層目錄下
				$Sql .= " , ''";
				$Sql .= " , (select ifnull(max(ifnull(SORT_NO, 0)), 0) + 1 from m_tree_s01 where GP_ID = '$GP_ID' and PREV_TREE_ID = '$PREV_TREE_ID') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " From m_tree_s01 Where GP_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤6");
			}
			if(strlen($delId) > 0){
			//刪除一層目錄
			//將屬於該層目錄下的所有項目轉移到根目錄下
				$Sql = " update m_tree_s01 ";
				$Sql .= " set PREV_TREE_ID = 0 ";
				$Sql .= " Where PREV_TREE_ID = '$delId' ";
				$Sql .= " and GP_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤7");
				$Sql = " delete from m_tree_s01 ";
				$Sql .= " where TREE_ID = '$delId' ";
				$Sql .= " and GP_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤8");
			}
			if(strlen($renameId) > 0){
				if(strlen($newName) > 0){
					$Sql = " update m_tree_s01 ";
					$Sql .= " set TREE_NM = '$newText' ";
					$Sql .= " where TREE_ID = '$renameId' ";
					$Sql .= " and GP_ID = '$GP_ID' ";
					$MySql -> db_query($Sql) or die("查詢 Query 錯誤9");
				}
			}
		}elseif($SetType == "U"){
		}
	}
	if($IsSuccess){
		echo "SUCCESS";
	}else{
		echo "FAILED";
	}
?>
