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
//定義全域參數

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");
//路徑及帳號控管
	$gMainFile = basename($_COOKIE["FilePath"], '.php');									//去掉路徑及副檔名
	$USER_ID = $_SESSION["C_USER_ID"];														//管理員 ID
	$USER_NM = $_SESSION["C_USER_NM"];
//資料庫連線
	$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(2, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
//	$PG_NM = GetPG_NM($PG_ID, $MySql);	
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey"));
	$Status = trim(GetxRequest("Status"));
//
	$myWhere = " Where 1 = 1 ";
	if($Status == "Y"){
		$myWhere .= " and Comment != '' and Pic1L != '' and Stars != '' ";
	}else if($Status == "N"){
		$myWhere .= " and (Comment is null or Pic1L is null or Stars is null) ";
	}
//主檔
	$Sql = " select DISTINCT A.ClassName, A.TemplateId, C.Name, I.Name ";
	$Sql .= " , J.Comment, J.Pic1L, J.Stars ";
	$Sql .= " from Class A ";
	$Sql .= " left join ClassMember B on B.ClassId = A.Id  ";
	$Sql .= " left join Student C on C.Id = B.StudentId ";
	$Sql .= " left join Template D on D.Id = A.TemplateId ";
//	$Sql .= " left join TemplateTeachingPlan E on E.TemplateId = D.Id ";
//	$Sql .= " left join TemplateDetail F on F.TemplateTeachingPlanId = E.Id ";
	$Sql .= " left join TemplateDetail F on F.TemplateTeachingPlanId in (select Id from TemplateTeachingPlan where 1 = 1 and TemplateId = D.Id ) ";
	$Sql .= " left join TeachingPlan I on I.Id = F.TeachingPlanId ";
	$Sql .= " left join Course J on J.PersonnelId = A.TeacherId and J.TeachingPlanId = I.Id ";
	$Sql .= $myWhere;
	$Sql .= " and A.TeacherId = '".$DataKey."' ";
	$Sql .= " order by A.Id desc ";
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
<title>Web_園方 教師 自購家長(管理者介面) 教師工作進度 檢視進度內容</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){

			
		});

		function ChkForm(_Form){
			
			get_Checked_Checkbox_By_Name('OptionalID');
			
			if(IsEmpty(_Form.FullName,'姓名')){
			}else{
				_Form.submit();
			}
		}
		
		function get_Checked_Checkbox_By_Name(Input_Name) {

			var arr = [];
            $("input[name='" + Input_Name + "']:checked:enabled").each(function () {
                //str = str + $(this).val();
				arr.push($(this).val());
            });
            $('#'+Input_Name+'_TEXT').val(arr);
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
                <li class="current"><span class="hor-box-text">檢視進度內容</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料新增功能 begin -->
<form name="DataForm" id="DataForm" method="post" action="<?php echo $gMainFile?>_Update.php" autocomplete="off">
	<input id="Id" name="Id" type="hidden" value="<?php echo $DataKey?>">
	<input class="textInput" name="QNum" type="hidden" id="QNum" size="10" value="<?php echo count($MedAry);?>" />
    <input class="textInput" name="OptionalID_TEXT" type="hidden" id="OptionalID_TEXT" size="10" tabindex="1" value="" />	
        <div class="dataIndexList themeSet2 workProgress">
        	<div class="timeSelect">
            	<ul>
                    <li><button type="button" <?php if($Status == ""){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>_View.php?DataKey=<?php echo $DataKey?>&Status=';"><span class="sized-text-normal">全部</span></button></li><!-- class="current" 表示作用中項目 -->
                    <li><button type="button" id="Finish" name="Finish" value="Y" <?php if($Status == "Y"){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>_View.php?DataKey=<?php echo $DataKey?>&Status=' + this.value;"><span class="sized-text-normal">已完成</span></button></li>
                    <li><button type="button" id="UnFinish" name="UnFinish" value="N" <?php if($Status == "N"){?>class="current"<?php }?> onclick="window.location.href='<?php echo $gMainFile; ?>_View.php?DataKey=<?php echo $DataKey?>&Status=' + this.value;"><span class="sized-text-normal">未完成</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
            
            <div class="spacingBlock"></div>
            
            <table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
            	<tr>
                    <th><span class="hor-box-text-large">班級名稱</span></th>
                    <th><span class="hor-box-text-large">教案名稱</span></th>
                    <th><span class="hor-box-text-large">學生姓名</span></th>
                    <th><span class="hor-box-text-large">照片</span></th>
                    <th><span class="hor-box-text-large">星星</span></th>
                    <th><span class="hor-box-text-large">文字</span></th>
                </tr>
                <?php
					$lineCount = 0;
					while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
				?>
                <tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                	<td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[0];?></span></td>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[3];?></span></td>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[2];?></span></td>
                    <td><span class="hor-box-text-normal"><?php if($initAry[4] != ''){ ?>V<?php }?></span></td>
                    <td><span class="hor-box-text-normal"><?php if($initAry[5] != ''){ ?>V<?php }?></span></td>
                    <td><span class="hor-box-text-normal"><?php if($initAry[6] != ''){ ?>V<?php }?></span></td>
                </tr>
                <?php }?>
            </table>
        </div>
</form>        
        <!-- 資料檢視表格(web 教師工作進度 檢視進度內容) end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
