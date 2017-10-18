<?php
	function GetTreeCont(){
		$InLevel = -1;
		$xFlgEnd = false;
		$UID = $_SESSION["M_USER_ID"];
		$TreeCont = "";
		if($UID == ""){
			Disp($UNM & ",root,Menu_Welcome.php,mainFrame");
		}else{
			$MySql = new mysql();
			$GP_ID = IniUserTree($MySql, $UID);
			//echo $GP_ID;
			if($GP_ID != ''){
			GetSubItem($MySql, 0, true, $GP_ID, $InLevel);
			}
			mysql_close();
		}
	}
// 用來補樹狀目錄設定檔	
	function IniUserTree($MySql, $UID){
	//自群組程式對應檔刪除已不存在的程式
		$Sql = " Delete mus02 from mus02 ";
		$Sql .= " Left join mum03 on mus02.PG_ID = mum03.PG_ID ";
		$Sql .= " Where mum03.PG_ID is null ";
		$MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	//自系統--樹狀目錄設定刪除已不存在的程式
		$Sql = " Delete m_tree_s01 from m_tree_s01 ";
		$Sql .= " Left join mum03 on m_tree_s01.PG_ID = mum03.PG_ID ";
		$Sql .= " Where mum03.PG_ID is null ";
		$Sql .= " And m_tree_s01.TREE_TYPE = 'P' ";
		$MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	//系統管理者登入
		$Sql = " select GP_ID from mus01 where USER_ID = '$UID'";
		//echo $UID;
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
		$GP_ID = $MySql -> db_result($SqlRun);
		if($GP_ID!=''){
		//讀取可供該群組使用的程式，且該程式尚未位於目錄結構內
			$Sql = " Select a.PG_ID, b.PG_NM ";
			$Sql .= " From mus02 a ";
			$Sql .= " Left join mum03 b on a.PG_ID = b.PG_ID ";
			$Sql .= " Left join m_tree_s01 c on a.GP_ID = c.GP_ID and b.PG_ID = c.PG_ID ";
			$Sql .= " Where a.GP_ID = '$GP_ID' ";
			$Sql .= " And c.GP_ID is null ";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
		//若有程式程式未位於目錄結構內，則自動寫入該程式到目錄結構內，並設定其為最外層程式
			while ($MUM03 = $MySql -> db_fetch_array($SqlRun)){
				$Sql = " insert into m_tree_s01 ";
				$Sql .= " (GP_ID, TREE_ID, TREE_NM, TREE_TYPE, PREV_TREE_ID, PG_ID, SORT_NO ";
				$Sql .= " , ENTRY_ID, ENTRY_DATE, ENTRY_TIME, MODIFY_ID, MODIFY_DATE, MODIFY_TIME) ";
				$Sql .= " Select '$GP_ID' ";
				$Sql .= " , ifnull(max(ifnull(TREE_ID, 0)), 0) + 1 ";
				$Sql .= " , '' ";	//程式不用給名稱
				$Sql .= " , 'P' ";	//指定該目錄型態為程式
				$Sql .= " , '0' ";	//根目錄
				$Sql .= " , '" . $MUM03[0] . "' ";
				$Sql .= " , (Select ifnull(max(ifnull(SORT_NO, 0)), 0) + 1 From m_tree_s01 Where GP_ID = '$GP_ID' and PREV_TREE_ID = '0') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " From m_tree_s01 Where GP_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
			}
		}else{
		$Sql = " select GP_ID from oprt_t where OprtT_UserID = '$UID' and MemM_ID = '".$_SESSION["M_USER_NUM"]."' ";
		//echo $UID;
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤6");
		$GP_ID = $MySql -> db_result($SqlRun);
		//讀取可供該群組使用的程式，且該程式尚未位於目錄結構內
			/*$Sql = " Select a.PG_ID, b.PG_NM ";
			$Sql .= " From oprt_d a ";
			$Sql .= " Left join mum03 b on a.PG_ID = b.PG_ID ";
			$Sql .= " Left join m_tree_s01 c on a.GP_ID = c.GP_ID and b.PG_ID = c.PG_ID ";
			$Sql .= " Where a.GP_ID = 'Manger' ";
			$Sql .= " And c.GP_ID is null ";
			$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		//若有程式程式未位於目錄結構內，則自動寫入該程式到目錄結構內，並設定其為最外層程式
			while ($MUM03 = $MySql -> db_fetch_array($SqlRun)){
				$Sql = " insert into m_tree_s01 ";
				$Sql .= " (GP_ID, TREE_ID, TREE_NM, TREE_TYPE, PREV_TREE_ID, PG_ID, SORT_NO ";
				$Sql .= " , ENTRY_ID, ENTRY_DATE, ENTRY_TIME, MODIFY_ID, MODIFY_DATE, MODIFY_TIME) ";
				$Sql .= " Select '$GP_ID' ";
				$Sql .= " , ifnull(max(ifnull(TREE_ID, 0)), 0) + 1 ";
				$Sql .= " , '' ";	//程式不用給名稱
				$Sql .= " , 'P' ";	//指定該目錄型態為程式
				$Sql .= " , '0' ";	//根目錄
				$Sql .= " , '" . $MUM03[0] . "' ";
				$Sql .= " , (Select ifnull(max(ifnull(SORT_NO, 0)), 0) + 1 From m_tree_s01 Where GP_ID = '$GP_ID' and PREV_TREE_ID = '0') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
				$Sql .= " From m_tree_s01 Where GP_ID = '$GP_ID' ";
				$MySql -> db_query($Sql) or die("查詢 Query 錯誤");
			}*/
		}
		return $GP_ID;
	}
//TreeView
	function GetSubItem($MySql, $wPG_PREV, $IsEnd, $GP_ID, $InLevel){
		$spwhere = "";
		$InLevel++;
		$Sql = " Select case a.TREE_TYPE when 'D' then a.TREE_NM else b.PG_NM end as PG_NM ";
		$Sql .= " , a.TREE_TYPE, a.TREE_ID, b.PG_PATH, a.PG_ID, a.PREV_TREE_ID ";
		$Sql .= " From m_tree_s01 a ";
		$Sql .= " Left join mum03 b on a.PG_ID = b.PG_ID ";
		$Sql .= " Left join ( ";
		$Sql .= " Select PREV_TREE_ID, count(*) as CNT From m_tree_s01 ";
		$Sql .= " Where GP_ID = '$GP_ID' ";
		$Sql .= " and TREE_TYPE = 'P' ";
		$Sql .= " group by PREV_TREE_ID ";
		$Sql .= " ) f on a.TREE_ID = f.PREV_TREE_ID ";
		$spwhere = " and not (a.TREE_TYPE = 'D' and f.CNT <= 0) ";
		$Sql .= " Where 1 = 1 ";
		$Sql .= " and a.GP_ID = '$GP_ID' ";
		$Sql .= $spwhere;
		$Sql .= " and a.PREV_TREE_ID = " . $wPG_PREV;
		$Sql .= " order by SORT_NO ";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤6");
		$RowCount = $MySql -> db_num_rows($SqlRun);
		// chiliman System
		while ($wAry = $MySql -> db_fetch_array($SqlRun)){
			$Rlt = "";
			for($i = 1; $i <= $InLevel; $i++){
				$Rlt .= '<img src="/Images/s4-19.gif" border="0" width="20" height="22" align="absmiddle">';
			}
			if($wAry[1] == "P"){
				echo "<div class=\"TreeviewSpan\" onclick=\"window.parent.main.document.location.href='" . $wAry[3] . "?PG_ID=" . $wAry[4] . "';window.parent.main.focus();\">" . $Rlt . "<img src=\"/Images/s4-19.gif\" border=\"0\" width=\"20\" height=\"22\" align=\"absmiddle\"><img src=\"/Images/s4-21.gif\" border=\"0\" width=\"20\" height=\"22\" align=\"absmiddle\"><a href=\"" . $wAry[3] . "?PG_ID=" . $wAry[4] . "\" target=\"main\" class=\"black9\" onfocus=\"this.blur()\">" . $wAry[0] . "</a></div>";
			}else{
				echo "<div class=\"TreeviewFncFile\" treeimgid=\"#Sys" . $wAry[2] . trim($wAry[4]) . "A\" treeFileimgid=\"#Sys" . $wAry[2] . trim($wAry[4]) . "B\" treeid=\"#Sys" . $wAry[2] . trim($wAry[4]) . "\">" . $Rlt . "<img src=\"/Images/s4-19.gif\" border=\"0\" width=\"20\" height=\"22\" align=\"absmiddle\"><img src=\"/Images/s4-16.gif\" border=\"0\" width=\"20\" height=\"22\" align=\"absmiddle\" id=\"Sys" . $wAry[2] . trim($wAry[4]) . "A\"><img src=\"/Images/s4-17.gif\" style=\"cursor:Hand\" class=\"Outline\" align=\"absmiddle\" id=\"Sys" . $wAry[2] . trim($wAry[4]) . "B\" width=\"20\" height=\"22\">" . $wAry[0] . "</div>";
				echo "<div id=\"Sys" . $wAry[2] . trim($wAry[4]) . "\" style=\"display:None\">";
			}
			if(trim($wAry[1]) == "D"){
				GetSubItem($MySql, $wAry[2],  $i == $RowCount, $GP_ID, $InLevel);
				echo "</div>";
			}
			
		}
		$InLevel--;
	}
