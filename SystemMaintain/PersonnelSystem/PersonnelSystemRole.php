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
//		撰寫人員：BochiHuang
//		撰寫日期：20161214
//		程式功能：ct / 角色權限 / 角色權限列表
//		使用參數：None
//		資　　料：sel：ad_m
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
	$CanAddNew = " disabled";
	$CanUpdate = " disabled";
	$CanDelete = " disabled";
	$gCurrentPage;																			//目前頁碼
	$gPageCnt;																				//總頁數
	$gPageFirst;																			//目前顯示筆數 first
	$gPageLast;																				//目前顯示筆數 last
    $defautId = "";
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
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
	}	
//定義一般參數
	$sNM = trim(GetxRequest("sNM"));
	$sToPage = trim(GetxRequest("ToPage"));
	$PageCond = "";
	$myWhere = " Where 1 = 1 ";

//主檔	: 角色權限列表
	$Sql = " 
		Select Id, RoleName
		From Role
	";
	$Sql .= $myWhere;
	$Sql .= "
        order by Id asc
	";

	//echo $Sql;	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	//$RowCount = $MySql -> db_num_rows($initRun);
//分頁設定
//	$wPageSize = 20;
//	SetPage($RowCount, $wPageSize, $Sql, $MySql);
//關閉資料庫連線
	$MySql -> db_close();

    $_SESSION["RoleTreeStatus"] = "View";							                                    //權限樹狀態
?>
<!DOCTYPE HTML>
<!--[if lt IE 9]>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>擴思訊 Cross Think 後台 使用者管理-劍聲 角色權限</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
<!-- #include file="../incs/mutual_css.inc" -->
<script src="/Js/drag-drop-folder-tree.js" type="text/javascript" ></script>
    	<script type="text/javascript">
    	$(document).ready(function () {
    	    CheckRoleGroup();
    	});

        function SetKey(pVal) {
            DataForm.DataKey.value = pVal;
        }

        function CheckRoleGroup() {
            var arrRoleGroup = new Array();
            $("input[type='checkbox']").each(function () {
                if (this.id.indexOf("FDM") != -1) {
                    if (this.id == "FDM000000000000005") return;
                    var objInput = undefined;
                    var objUL = this.nextElementSibling;

                    if (objUL == undefined) return;
                    var cnt = 0;
                    var cntChildren = objUL.children.length;

                    for (var i = 0; i < cntChildren; i++) {
                        objInput = objUL.children[i].getElementsByTagName("input");
                        if (objInput == undefined) continue;
                        if ($(objInput).prop("checked") == true) cnt++;
                    }

                    if (cnt == cntChildren) $(this).prop("checked", true);
                }
            });
        }

        function Excute(wFunc) {
            switch (wFunc) {
                case 'Add':
                    DataForm.action = '<?php echo $gMainFile; ?>_Input.php';
				    DataForm.submit();
				    break;
                case 'Update':
                    DataForm.action = '<?php echo $gMainFile; ?>_Modify.php';
				    DataForm.submit();
				    break;
                case 'Delete':
                    if (confirm("確認刪除?")) {
                        DataForm.action = '<?php echo $gMainFile; ?>_Delete.php';
					    DataForm.submit();
					    //					checkBeforeSubmit(DataForm,'delVal','刪除')
					    break;
					}
                    break;
                case "Change":
                    DataForm.action = '<?php echo $gMainFile; ?>.php?sNM=' + DataForm.DataKey.value;
                    DataForm.submit();
                    break;
                default:
            }
        }
	</script>
</head>

<body>
<div class="main">
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_size_tag.php");?>
	<div class="header themeSet1">
    	<!-- logo與使用者功能 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_logo_user.php");?>        
        <!-- logo與使用者功能 end -->
        
        <!-- 主選單 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_topmenu_item_5.php");?>        

        <!-- 主選單 end -->
        
        <!-- 功能軌跡（麵包屑） begin -->
        <div class="breadCrumb">
            <ul>
                <li class="title"><span class="hor-box-text">使用者管理-劍聲</span></li>
                <li class="current"><span class="hor-box-text">角色權限</span></li><!--如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
            <form name="DataForm" id="DataForm" method="post">
        <!-- 使用者管理-劍聲　角色權限 begin -->
        <div class="definitionSystemUser">
        	<div class="memberControl">
        		<ul>
                	<li class="title"><span class="needMark sized-text-normal">角色名稱＊</span></li>
                    <li class="type">
                    	<select class="fullWidth sized-text-1" onchange="SetKey(this.value);Excute('Change');">
                <?php
                while ($initAry = $MySql -> db_fetch_array($initRun)){
                    if($defautId == '' && $sNM =='') $defautId=$initAry[0];
                    if($sNM == $initAry[0]){
                        $defautId = $initAry[0];
                ?>
                            <option value="<?php echo $initAry[0]; ?>" selected><?php echo $initAry[1]; ?></option>
                <?php 
                    }else{
                ?>
                        	<option value="<?php echo $initAry[0]; ?>"><?php echo $initAry[1]; ?></option>
                <?php
                    }
                }
                ?>
                        </select>
                    </li>
                    <li><button type="button" onClick="Excute('Add');return false;" class="new"><span class="sized-text-normal">新增角色</span></button></li>
                    <li><button type="button" onClick="Excute('Update');return false;" class="modify"><span class="sized-text-normal">修改角色</span></button></li>
                    <li><button type="button" onClick="Excute('Delete');return false;" class="kill"><span class="sized-text-normal">刪除角色</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
            <p><span class="hor-box-text-normal">角色功能</span></p>
            
            <div class="spacingBlock"></div>
            
            <div class="memberTree hor-box-text-normal">
			    <?php include_once(root_path . "/SystemMaintain/CommonPage/doc_system_definiton_user_jiansheng_themeset1.php");?>
            </div>
        </div>
        <!-- 使用者管理-劍聲　角色權限 end -->
            	<input type="hidden" id="DataKey" name="DataKey" value="<?php echo $defautId ?>" />
            </form>
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
