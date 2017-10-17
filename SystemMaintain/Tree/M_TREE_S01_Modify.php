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
//		程式功能：連丰 / 員工權限管理 / 列表
//		使用參數：None
//		資　　料：sel：MUM01
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
//路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
//資料庫連線
	$MySql = new mysql();
//使用權限
	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
	ChkFunc_BeforeRunPG(3, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
	$PG_NM = GetPG_NM($PG_ID, $MySql);
//定義一般參數
	$GP_ID = trim(GetxRequest("DataKey"));
//系統群組名稱
	$Sql = " Select GP_NM From mum02 ";
	$Sql .= " Where GP_ID = '$GP_ID' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$GP_NM = $MySql -> db_result($SqlRun);
//刪除已從MUM03刪除但仍M_TREE_S01中的程式
	$Sql = " Delete m_tree_s01 ";
	$Sql .= " From m_tree_s01 ";
	$Sql .= " Left join mus02 on m_tree_s01.GP_ID = mus02.GP_ID and m_tree_s01.PG_ID = mus02.PG_ID ";
	$Sql .= " Left join mum03 on m_tree_s01.PG_ID = mum03.PG_ID";
	$Sql .= " Where m_tree_s01.GP_ID = '$GP_ID' ";
	$Sql .= " and m_tree_s01.TREE_TYPE = 'P' ";
	$Sql .= " and (mus02.PG_ID is null or mum03.PG_ID is null) ";
	$MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
//讀取可供該群組使用的程式，且該程式尚未位於目錄結構內
	$Sql = " Select a.PG_ID, b.PG_NM ";
	$Sql .= " From mus02 a ";
	$Sql .= " Left join mum03 b on a.PG_ID = b.PG_ID ";
	$Sql .= " Left join m_tree_s01 c on a.GP_ID = c.GP_ID and b.PG_ID = c.PG_ID";
	$Sql .= " Where a.GP_ID = '$GP_ID' ";
	$Sql .= " and c.GP_ID is null ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	while ($MUS02Ary = $MySql -> db_fetch_array($SqlRun)){
		$Sql = " insert into m_tree_s01 ";
		$Sql .= " (GP_ID, TREE_ID, TREE_NM, TREE_TYPE, PREV_TREE_ID, PG_ID, SORT_NO ";
		$Sql .= " , ENTRY_ID, ENTRY_DATE, ENTRY_TIME, MODIFY_ID, MODIFY_DATE, MODIFY_TIME) ";
		$Sql .= " Select '$GP_ID' ";
		$Sql .= " , ifnull(max(ifnull(TREE_ID,0)),0) + 1 ";
		$Sql .= " , '' ";		//程式不用給名稱
		$Sql .= " , 'P' ";		//指定該目錄型態為程式
		$Sql .= " , '0' ";		//根目錄
		$Sql .= " , '$MUS02Ary[0]' ";
		$Sql .= " , (Select ifnull(max(ifnull(SORT_NO,0)),0) + 1 From m_tree_s01 Where GP_ID = '$GP_ID' and PREV_TREE_ID = '0') ";
		$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
		$Sql .= " , '" . $_SESSION["M_USER_ID"] . "', DATE_FORMAT(CURDATE(), '%Y%m%d'), DATE_FORMAT(now(),'%H%i%S') ";
		$Sql .= " From m_tree_s01 Where GP_ID = '$GP_ID' ";
		$MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
	}
//讀取目前的目錄結構
	$TreeStr = ReadTree(0, $GP_NM, $MySql, $GP_ID);

//關閉資料庫連線
	$MySql -> db_close();
	function ReadTree($preTreeID, $TreeNM, $MySql, $GP_ID){
		$Sql = " Select TREE_ID, TREE_TYPE, case TREE_TYPE when 'D' then TREE_NM else b.PG_NM end as TREE_NM ";
		$Sql .= " ,'','' ";
		$Sql .= " From m_tree_s01 a ";
		$Sql .= " Left join mum03 b on a.PG_ID = b.PG_ID ";
		$Sql .= " Where PREV_TREE_ID = $preTreeID ";
		$Sql .= " and a.GP_ID = '$GP_ID' ";
		$Sql .= " order by SORT_NO ";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
		$RowCount = $MySql -> db_num_rows($SqlRun);
		$xstr = "";
		if($preTreeID == 0){
		//根目錄
			$funStr = ' noDrag="true" noSiblings="true" noDelete="true" noRename="true" ';
		}else{
		//其他的目錄
			$funStr = "";
		}
		$xstr .= '<li id="node' . $preTreeID . '"' . $funStr . '><a href="#">' . $TreeNM . '</a>';
		if($RowCount > 0){
			$xstr .= "<ul>";
			while ($nodeAry = $MySql -> db_fetch_array($SqlRun)){
				if($nodeAry[1] == "P"){
				//程式
					$spcFun = "";
					$iconStr = "";
					if(strlen(trim($nodeAry[3])) == 0 && strlen(trim($nodeAry[4])) == 0){
					}else if(trim($nodeAry[3]) == "Y"){
						$spcFun = ' class="NoM" ';
						$iconStr = 'icon="dhtmlgoodies_sheet2.gif" ';
					}else if(trim($nodeAry[4]) == "Y"){
						$spcFun = ' class="NoS"';
						$iconStr = 'icon="dhtmlgoodies_sheet3.gif"';
					}
					$funStr = 'noDelete="true" noRename="true" noChildren="true" noAdd="true" ';
					$xstr .= '<li id="node' . $nodeAry[0] . '"' . $funStr . $iconStr . '><a href="#"' .  $spcFun . ' onclick="event.returnValue=false;">' . $nodeAry[2] . '</a></li> ';
				}else{
				//其他的目錄
					$funStr = "";
					$xstr .= ReadTree($nodeAry[0], $nodeAry[2], $MySql, $GP_ID);
				}
			}
			$xstr .= "</ul>";
		}
		$xstr .= "</li>";

		return $xstr;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>無標題文件</title>
	<link href="/Css/drag-drop-folder-tree.css" type="text/css" rel="stylesheet" />
	<link href="/Css/context-menu.css" type="text/css" rel="stylesheet" />
	<style type="text/css">
		#Info{
			width:200px;
			border:1px #666666 solid;
			float:left;
			text-align:left;
			padding:1em;
		}
		
		#Info li{
			list-style:decimal;
		}
		
		#tree{
			width:auto;
			float:left;
		}
		.dhtmlgoodies_tree li a.NoM,#floatingContainer li a.NoM{	/* Node links */
			color:#903;
			text-decoration:none;
			font-size:1em;
			padding-left:1px;
			line-height:13px;
			margin-left:2px;
			
		}
	
		.dhtmlgoodies_tree li a.NoS,#floatingContainer li a.NoS{	/* Node links */
			color:#3543FF;
			text-decoration:none;
			font-size:1em;
			padding-left:1px;
			line-height:13px;
			margin-left:2px;
			
		}
	
	</style>
	<script language="JavaScript" src="/Js/ComFun.js" type="text/javascript"></script>
	<script language="JavaScript" src="/Js/ajax.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script language="JavaScript" src="/Js/context-menu.js" type="text/javascript"></script>
	<script language="JavaScript" src="/Js/drag-drop-folder-tree.js" type="text/javascript">
		/************************************************************************************************************
		(C) www.dhtmlgoodies.com, July 2006
		
		Update log:
		
		
		This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
		
		Terms of use:
		You are free to use this script as long as the copyright message is kept intact.
		
		For more detailed license information, see http://www.dhtmlgoodies.com/index.html?page=termsOfUse 
		
		Thank you!
		
		www.dhtmlgoodies.com
		Alf Magne Kalleland
		
		************************************************************************************************************/
	</script>
	<script language="JavaScript" type="text/javascript">
		//--------------------------------
		// Save functions
		//--------------------------------
		var ajaxObjects = new Array();
		var TreeHandlePage='folderTree_updateItem.php'
		// Use something like this if you want to save data by Ajax.
		function saveMyTree()
		{
				var saveString = treeObj.getNodeOrders();
				var ajaxIndex = ajaxObjects.length;
				ajaxObjects[ajaxIndex] = new sack();
				
				ajaxObjects[ajaxIndex].method = "POST";
				ajaxObjects[ajaxIndex].setVar("saveOrder", saveString);
				ajaxObjects[ajaxIndex].setVar("GI", '<?php echo $GP_ID; ?>');
				ajaxObjects[ajaxIndex].setVar("ST", 'M');
				ajaxObjects[ajaxIndex].requestFile = TreeHandlePage
				ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function
		}		
		function saveComplete(index)
		{
			//alert(ajaxObjects[index].response);
			//$("#test").text(ajaxObjects[index].response);
			if(ajaxObjects[index].response!='SUCCESS'){
				alert('儲存資料夾順序時發生錯誤！'+ajaxObjects[index].response);
			}
			alert('儲存完畢！');
			ReloadPage();
		}
	
		
		// Call this function if you want to save it by a form.
	//	function saveMyTree_byForm()
	//	{
	//		document.myForm.elements['saveString'].value = treeObj.getNodeOrders();
	//		document.myForm.submit();		
	//	}
		
		
		function ReloadPage(){
			document.location.href='<?php echo basename(__FILE__, '.php'); ?>.php?DataKey=<?php echo $GP_ID; ?>';
		}
		
		function BackToMain(){
			document.location.href='<?php echo $gMainFile; ?>.php?PG_ID=<?php echo $PG_ID; ?>';
		}
	
	</script>
</head>
<body>
	<div class="Fun-Bar" style="text-align:center;">
		<input type="button" id="OK" name="OK" class="inputBTN" value="儲存結果" onClick="saveMyTree();" />
		<input type="button" id="Cancel" name="Cancel" class="inputBTN" value="取消" onClick="BackToMain();" />
		<input type="button" id="Reload" name="Reload" class="inputBTN" value="重新整理" onClick="ReloadPage();" />
</div>	
<div id="Input_Content">
		<div id="Info">
			操作說明：<br />
			<ul>
				<li>在根目錄名稱上按右鍵可新增一層目錄。</li>
				<li>在目錄名稱上按右鍵可進行新增/刪除/更名等操作。</li>
				<li>最多可設定三層目錄(包含根目錄)。第四層目錄將無法放置任何程式。</li>
			</ul>
		</div>
		<div id="tree" style="text-align:left;">
			<ul id="<?php echo $GP_ID; ?>" class="dhtmlgoodies_tree">
			<?php echo $TreeStr; ?>
			</ul>
		</div>
	</div>

</body>
</html>
<script type="text/javascript">	
	treeObj = new JSDragDropTree();
	treeObj.setTreeId('<?php echo $GP_ID; ?>');
	treeObj.setRootName('<?php echo $GP_NM; ?>');
	treeObj.setMaximumDepth(4);
	treeObj.setMessageMaximumDepthReached('您僅能設定最多3層目錄(包含根目錄)！'); // If you want to show a message when maximum depth is reached, i.e. on drop.
	treeObj.setAdditionalRenameRequestParameters({GI:'<?php echo $GP_ID; ?>',ST:'M'});
	treeObj.setAdditionalDeleteRequestParameters({GI:'<?php echo $GP_ID; ?>',ST:'M'});
	treeObj.setAdditionalAddRequestParameters({GI:'<?php echo $GP_ID; ?>',ST:'M'});
	treeObj.initTree();
	treeObj.expandAll();
</script>
