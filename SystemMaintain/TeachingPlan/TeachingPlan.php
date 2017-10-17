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
//		撰寫日期：20140726
//		程式功能：ct / 教師管理 / 列表
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
//	$CanAddNew = " disabled";
//	$CanUpdate = " disabled";
//	$CanDelete = " disabled";
//	$gCurrentPage;																			//目前頁碼
//	$gPageCnt;																				//總頁數
//	$gPageFirst;																			//目前顯示筆數 first
//	$gPageLast;																				//目前顯示筆數 last
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
//搜尋條件
	if($sNM != ""){
		$PageCond .= "&sNM=" . $sNM;
		$myWhere .= " and M.Name like '%" . $sNM . "%' ";
	}
//主檔	: 最新消息
	$Sql = " Select DISTINCT M.Id, M.Name, A.Description, A2.Description, A3.Description, (select Description from TeachingAdvanced D where 1 = 1 and D.TeachingPlanId = M.Id limit 1) '精益求精', M.Enable From TeachingPlan M ";
	$Sql .= " left join SystemParameter A on A.Id = M.CategoryId ";
	$Sql .= " left join SystemParameter A2 on A2.Id = M.LevelId ";
	$Sql .= " left join SystemParameter A3 on A3.Id = M.SubLevelId ";
	$Sql .= $myWhere;
	$Sql .= " and M.DeleteStatus = 'N'";
	$Sql .= " order by M.Id desc ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);

//關閉資料庫連線
	$MySql -> db_close();
?>
<!DOCTYPE HTML>
<!--[if lt IE 9]>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>擴思訊 Cross Think 後台 教案管理 本功能第一頁(首頁)</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#TxtTipBtnSrh').click(function(){
				var str = ''
				if($('#sNM').val() != ''){
					str = str + 'sNM=' + $('#sNM').val() + '&'
				}
				window.location = '<?php echo $gMainFile; ?>.php?' + str ;
			});
			
		});
		function SetKey(pVal){
			DataForm.DataKey.value = pVal;
		}
		function Excute(wFunc){
			switch(wFunc){
				case 'Add':
					DataForm.action='<?php echo $gMainFile; ?>_Input.php';
					DataForm.submit();
					break;
				case 'View':
					DataForm.action='<?php echo $gMainFile; ?>_View.php';
					DataForm.submit();
					break;	
				case 'Update':
					DataForm.action='<?php echo $gMainFile; ?>_Modify.php';
					DataForm.submit();
					break;
				case 'Delete':
					if(confirm("確認刪除?")){
						DataForm.action='<?php echo $gMainFile; ?>_Delete.php';
						DataForm.submit();
						break;
					}
					break;
				case 'Open':
					if(confirm("是否開啟該項目?")){
						DataForm.action='<?php echo $gMainFile; ?>_Open.php';
						DataForm.submit();
						break;
					}
					break;
				case 'Close':
					if(confirm("是否關閉該項目?")){
						DataForm.action='<?php echo $gMainFile; ?>_Open.php';
						DataForm.submit();
						break;
					}
					break;
				default:
			}
		}
	</script>
</head>

<body>
<div class="main">
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_helper.php");?>                 

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
                <li class="title"><span class="hor-box-text">教案管理</span></li>
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料管理操作 begin -->
        <div class="mainOptionTool">
            <ul>
                <li class="title"><span class="sized-text-normal">教案名稱</span> <input type="text" name="sNM" id="sNM" class="search sized-text-normal"></li>
                <li class="find"><button type="button"><span id="TxtTipBtnSrh" class="sized-text-normal">搜尋</span></button></li>
                <?php if($result1 == 'Y'){?><li class="new"><button type="button" onClick="Excute('Add');"><span class="sized-text-normal">新增教案</span></button></li><?php }?>
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 資料管理操作 end -->
        
        <div class="spacingBlock"></div>
        
        <!-- 資料檢視表格 begin -->
        <div class="dataIndexList themeSet1">
        	<table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
            <form name="DataForm" id="DataForm" method="post">
            	<input type="hidden" id="DataKey" name="DataKey" value="" />
            	<tr>
                	<th>&nbsp;</th>
                    <th class="topHeader"><span class="hor-box-text-large">教案名稱</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">類別</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">級別</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">訓練目標</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">精益求精</span></th>
                </tr>
                <?php
					$lineCount = 0;
					while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
				?>
                <tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                	<th valign="middle">
                    	<ul class="operations">
							<?php if($result2 == 'Y'){?>
                        	<li><a href="#" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('View');return false;" class="view"><span class="hiddenItem">檢視</span></a></li>
							<?php }?>
							<?php if($result3 == 'Y'){?>
                            <li><a href="#" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('Update');return false;" class="modify"><span class="hiddenItem">編輯</span></a></li>
							<?php }?>
							<?php if($result4 == 'Y'){?>
                            <li><a href="#" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('Delete');return false;" class="remove"><span class="hiddenItem">移除</span></a></li>
							<?php }?>
							<?php if($result3 == 'Y'){?>
                            <li><a href="#" onClick="SetKey('<?php echo $initAry[0];?>');<?php if($initAry[6] == "Y"){ ?>Excute('Close');<?php ?><?php }else{?>Excute('Open');<?php }?>" <?php if($initAry[6] == "Y"){ echo 'class="check"'?><?php }else{ echo 'class="disable"'?><?php }?>><span class="hiddenItem">啟用</span></a></li>
							<?php }?>
                        </ul>
                    </th>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[1]; ?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[2]; ?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[3]; ?></span></td>
					<? 
						//資料庫連線
							$MySql = new mysql();
							$Sql = " select TeachingPlanId,Comment from TeachingObjectives M ";
							$Sql .= " left join OptionalItem T on T.Id = M.OptionalId ";
							$Sql .= " where 1 = 1 ";
							$Sql .= " and TeachingPlanId = '".$initAry[0]."'";
							$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
							$GoalAry = $MySql -> db_array($Sql,2);
							$GoalStr = "";
							for($i=0;$i<count($GoalAry);$i++){
								if($GoalStr ==''){								
									$GoalStr = $GoalAry[$i][1] . "<br>" ;
								}else{
									$GoalStr = $GoalStr . $GoalAry[$i][1] . "<br>";
								}
							}						
						//關閉資料庫連線
							$MySql -> db_close();	
					?>
                    <td><span class="hor-box-text-normal"><?php echo $GoalStr; ?></span></td>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo GetTitle($initAry[5],20); ?></span></td>
                </tr>
                <?php }?>
                </form>
            </table>
        </div>
        <!-- 資料檢視表格 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
