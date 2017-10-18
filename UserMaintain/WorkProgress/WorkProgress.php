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
	$CanAddNew = " disabled";
	$CanUpdate = " disabled";
	$CanDelete = " disabled";
	$gCurrentPage;																			//目前頁碼
	$gPageCnt;																				//總頁數
	$gPageFirst;																			//目前顯示筆數 first
	$gPageLast;																				//目前顯示筆數 last
//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
	$USER_ID = $_SESSION["C_USER_ID"];														//管理員 ID
	$USER_NM = $_SESSION["C_USER_NM"];
	$USER_NUM = $_SESSION["M_USER_Num"];													//管理員序號	
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$Start = trim(GetxRequest("Start"));
	$End = trim(GetxRequest("End"));
	$ClickDate = trim(GetxRequest("ClickDate"));
	$ClickWeek = trim(GetxRequest("ClickWeek"));
	$ClickMonth = trim(GetxRequest("ClickMonth"));
	
	if ($ClickDate =='' && $ClickWeek =='' && $ClickMonth =='' && $Start =='' && $End ==''){
		$ClickDate = date("Ymd");
	}
	if($ClickWeek != ''){
		$d = strtotime("today");
		$start_week = strtotime("last monday midnight",$d);
		$end_week = strtotime("next sunday",$d);
		$startDate = date("Ymd",$start_week);		
		$endDate = date("Ymd",$end_week);
	}
	$myWhere = " Where 1 = 1 ";
//取得今日禮拜幾	
	$todayCode = date("w");	//0是星期日
	if($todayCode == '0'){
		$todayCode = '7';
	}
//當月第一天
	$firstday = date("Ym01");
//搜尋條件
	if($ClickDate != "" && $Start == "" && $End == ""){
		$myWhere .= " and LOCATE(".$todayCode.", B.ClassWeek) > 0 ";
		$myWhere .= " and ".$ClickDate." >= StartTime ";
		$myWhere .= " and ".$ClickDate." = REPLACE(T.T,'-','') ";
	}
	if($ClickWeek != ""){
		$myWhere .= " and ".$startDate." >= StartTime ";
		$myWhere .= " and REPLACE(T.T,'-','') >= ".$startDate." ";
		$myWhere .= " and ( REPLACE(T.T,'-','') >= ".$endDate." ";
		$myWhere .= " or ".$endDate." >= REPLACE(T.T,'-','') )";
	}
	if($ClickMonth != ""){
		$myWhere .= " and StartTime >= ".$firstday." ";
	}
	if($Start != ""){
		$myWhere .= " and ".$Start." <= StartTime";
	}
	if($End != ""){
		$myWhere .= " and REPLACE(T.T,'-','') <= ".$End." ";
	}		
	
//主檔	: 最新消息
	$Sql = " select A.Id, A.FullName, B.ClassName, C.Id, C.TemplateName ";
	$Sql .= " , ( select count(DISTINCT TeachingPlanId) from TemplateDetail D where 1 = 1 and D.TemplateTeachingPlanId in (select Id from TemplateTeachingPlan C where 1 = 1 and B.TemplateId = C.TemplateId) )'教案數' ";
	$Sql .= " ,( select count(*) from ClassMember E where 1 = 1 and E.ClassId = B.Id ) '學生數' ";
	$Sql .= " ,(select count(*) from Course F where 1 = 1 and F.PersonnelId = A.Id and F.StudentId in ( select StudentId from ClassMember where 1 = 1 and ClassId = B.Id  ) 
				  and TeachingPlanId in ( select DISTINCT TeachingPlanId from TemplateDetail D 
				  where 1 = 1 
				  and D.TemplateTeachingPlanId in ( select Id from TemplateTeachingPlan C where 1 = 1 and B.TemplateId = C.TemplateId )) 
				) '教師批改完'";
	$Sql .= " , sum(( select count(DISTINCT TeachingPlanId) from TemplateDetail D where 1 = 1 and D.TemplateTeachingPlanId in (select Id from TemplateTeachingPlan C where 1 = 1 and B.TemplateId = C.TemplateId) )) '教案總和' ";
	$Sql .= " , sum(( select count(*) from ClassMember E where 1 = 1 and E.ClassId = B.Id )) '學生總和' ";
	$Sql .= " , sum((select count(*) from Course F where 1 = 1 and F.PersonnelId = A.Id and F.StudentId in ( select StudentId from ClassMember where 1 = 1 and ClassId = B.Id  ) 
			  and TeachingPlanId in ( select DISTINCT TeachingPlanId from TemplateDetail D 
			  where 1 = 1 
			  and D.TemplateTeachingPlanId in ( select Id from TemplateTeachingPlan C where 1 = 1 and B.TemplateId = C.TemplateId )) 
			  )) '批改總和' ";
	$Sql .= " , REPLACE(T.T,'-','')";
	
	$Sql .= " from Personnel A ";
	$Sql .= " left join Class B on B.TeacherId = A.Id ";
	$Sql .= " left join Template C on C.Id = B.TemplateId ";
	$Sql .= " left join ( select ClassId, MAX(ClassTime)'T' from ClassTime C where 1 = 1 group by ClassId ) AS T on B.Id = T.ClassId ";
	$Sql .= $myWhere;
//	本日 	
	$Sql .= " and A.DeleteStatus = 'N'";
	$Sql .= " and B.ClientId = '".$USER_NUM."' ";
	$Sql .= " and B.Enabled = 'Y' ";
	$Sql .= " and B.DeleteStatus= 'N' ";
	$Sql .= " group by A.Id";
	$Sql .= " order by A.Id desc ";
//	echo $Sql;
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
<title>Web_園方 教師 自購家長(管理者介面) 教師工作進度</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
	var St = '<?php echo $Start?>';
	var Ed = '<?php echo $End?>';
		$(document).ready(function(){
			if(St == '' && Ed == ''){
				$("#ChooseDate").hide();
				$("#TxtTipBtnSrh").hide();
			}
			
			$('#TxtTipBtnSrh').click(function(){
				var str = ''
				if($('#Start').val() != ''){
					str = str + 'Start=' + $('#Start').val() + '&'
				}
				if($('#End').val() != ''){
					str = str + 'End=' + $('#End').val() + '&'
				}
				window.location = '<?php echo $gMainFile; ?>.php?' + str ;
			});
			
			$('#Start, #End').datepicker({
				dateFormat:'yymmdd',
				changeYear: true,
				changeMonth: true,
				onSelect: function(selectedDate){
					switch(this.id){
						case 'Start':
							var dateMin = $('#Start').datepicker('getDate');
							var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1);
							$('#End').datepicker('option', 'minDate', rMin);
							break;
					}
				}
			});
			
			$('#Custom').click(function(){
				$("#ChooseDate").show();
				$("#TxtTipBtnSrh").show();
			});
		});
		
		function ADDclassName(id){		
			$("#"+id).addClass("current");	
			$("#Day").removeClass("current");
			$("#Week").removeClass("current");
			$("#Month").removeClass("current");
		}
		
		function SetKey(pVal){
			DataForm.DataKey.value = pVal;
		}
		function Excute(wFunc){
			switch(wFunc){
				case 'View':
					DataForm.action='<?php echo $gMainFile; ?>_View.php';
					DataForm.submit();
					break;	
				default:
			}
		}
	</script>
</head>

<body>
<div class="main">
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_helper2.php");?> 

<?php include_once(root_path . "/SystemMaintain/CommonPage/header_size_tag.php");?>
	<div class="header themeSet2">
    	<!-- logo與使用者功能 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_logo_user_2.php");?>        
        <!-- logo與使用者功能 end -->
        
        <!-- 主選單 begin -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_topmenu_item_4.php");?>        
        <!-- 主選單 end -->
        
        <!-- 功能軌跡（麵包屑） begin -->
        <div class="breadCrumb">
            <ul>
                <li class="title"><span class="hor-box-text">教師管理</span></li>
                <li class="current"><span class="hor-box-text">教師工作進度</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料管理操作 begin -->
        <div class="dataIndexList themeSet2 workProgress">
        	<div class="timeSelect">
            	<ul>
                	<li class="title"><span class="sized-text-normal">顯示區間</span></li>
                    <li><button type="button" id="Day" value="<?php echo date("Ymd");?>" <?php if($ClickDate == date("Ymd")){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>.php?ClickDate=' + this.value;" ><span class="sized-text-normal">本日</span></button></li>
                    <li><button type="button" id="Week" value="<?php echo date('Ymd',strtotime($date_day . "+1 week"));?>" <?php if($ClickWeek == date('Ymd',strtotime($date_day . "+1 week"))){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>.php?ClickWeek=' + this.value;" ><span class="sized-text-normal">本週</span></button></li>
                    <li><button type="button" id="Month" value="<?php echo date("Ymt");?>" <?php if($ClickMonth == date("Ymt")){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>.php?ClickMonth=' + this.value;"><span class="sized-text-normal">本月</span></button></li><!-- class="current" 表示該按鈕使用中 -->
                    <li><button type="button" id="Custom" name="Custom" <?php if($Start != '' || $End != ''){?>class="current"<?php }?> onClick="ADDclassName(this.id)"><span class="sized-text-normal">自訂</span></button></li>
                    <li id="ChooseDate"><input type="text" id="Start" name="Start" readonly class="smallWidth sized-text-normal" value="<?php echo $Start?>"><span class="sized-text-normal">至</span><input type="text" id="End" name="End" readonly class="smallWidth sized-text-normal" value="<?php echo $End?>"></li><!-- 預設隱藏，點了自訂後出現 -->
                    <li><button type="button" id="TxtTipBtnSrh" name="TxtTipBtnSrh" class="search"><span class="sized-text-normal">查詢</span></button></li><!-- 預設隱藏，點了自訂後出現 -->
                </ul>
                <div class="clearFloat"></div>
            </div>
            
            <div class="spacingBlock"></div>
        
        <!-- 資料檢視表格 begin -->
        	<table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
            <form name="DataForm" id="DataForm" method="post">
            	<input type="hidden" id="DataKey" name="DataKey" value="" />
            	<tr>
                	<th>&nbsp;</th>
                    <th class="topHeader"><span class="hor-box-text-large">老師姓名</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">已達成（已完成學生數）</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">應達成（應達成學生數）</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">達成率</span></th>
                </tr>
                <?php
					$lineCount = 0;
					while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
				?>
                <tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                	<th valign="middle">
                    	<ul class="operations">
                        	<li><a href="#" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('View');return false;" class="view"><span class="hiddenItem">檢視</span></a></li>
                        </ul>
                    </th>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[1];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[7];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[5]*$initAry[6];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo round(($initAry[7] / ($initAry[5]*$initAry[6])) * 100);?>%</span></td>
                </tr>
                <?php }?>
            </form>
            </table>
        <!-- 資料檢視表格 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
