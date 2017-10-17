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
//婚姻
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'MaritalStatus' ";
	$Sql .= " and Language = 'zh-tw' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
//病史
	$Sql = " select * from OptionalItem";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'MedicalHistory'";
	$Sql .= " and Language = 'zh-tw' ";	
	$initRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$MedAry = $MySql -> db_array($Sql,6);
	
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
<title>Web_園方 教師 自購家長(管理者介面) 教師與課表管理 教師管理 新增教師</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){
			
			$('#Birthday').datepicker({
				changeYear : true,
				yearRange : "-60:+10",
                changeMonth : true,
				showOn: "button",
				buttonImage: "../../Images/calendar.png",
				buttonImageOnly: true,
				dateFormat:'yymmdd'
			});	
		
/*			$("input[name=UseApp]").click( function () {
				var Selected = $(this).val();
					$("input[name=UseApp]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});*/
			
			$("input[name=DrugallergyStatus]").click( function () {
				var Selected = $(this).val();
					$("input[name=DrugallergyStatus]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});
			
			$("input[name=Smoke]").click( function () {
				var Selected = $(this).val();
					$("input[name=Smoke]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});
			
			$("input[name=Areca]").click( function () {
				var Selected = $(this).val();
					$("input[name=Areca]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});
			
			$("input[name=IsDrink]").click( function () {
				var Selected = $(this).val();
					$("input[name=IsDrink]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});
		
		});

		function ChkForm(_Form){
			
			get_Checked_Checkbox_By_Name('OptionalID');
			get_Checked_Checkbox_By_Name('UseApp');
			
			if(IsEmpty(_Form.FullName,'姓名')){
			}else if(IsEmpty(_Form.EmergencyContact,'緊急聯絡人')){
			}else if(IsEmpty(_Form.Tel,'緊急聯絡人電話')){
			}else if(IsEmpty(_Form.Account,'帳號')){
			}else if(IsEmpty(_Form.PWD,'密碼')){													
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
                <li class="title"><span class="hor-box-text">教師管理</span></li>>
                <li class="current"><span class="hor-box-text">新增教師</span></li><!-- 如果沒有，就不寫入這個LI - -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->  
        <!-- 資料新增功能 begin -->
<form name="DataForm" id="DataForm" method="post" action="<?php echo $gMainFile?>_AddNew.php" autocomplete="off">
	<input class="textInput" name="QNum" type="hidden" id="QNum" size="10" value="<?php echo count($MedAry);?>" />
    <input class="textInput" name="OptionalID_TEXT" type="hidden" id="OptionalID_TEXT" size="100" tabindex="1" value="" />	
    <input class="textInput" name="UseApp_TEXT" type="hidden" id="UseApp_TEXT" size="100" tabindex="1" value="" />
        <div class="addNewTeacher">
        	<div class="spacingBlock"></div>
        	<h2><span class="sized-text-1">基本資料區</span></h2>
            <div class="spacingBlock"></div>
        	<table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">姓名＊：</span></th>
                    <td valign="middle"><input type="text" id="FullName" name="FullName" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">性別：</span></th>
                    <td valign="middle">
                    	<select id="Sex" name="Sex" class="halfWidth sized-text-1">
                        	<option value="男">男</option>
                            <option value="女">女</option>
                            <option value="其他">其他</option>
                        </select>
                    </td>
                </tr>
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話1：</span></th>
                    <td valign="middle"><input type="text" id="Tel1" name="Tel1" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">生日：</span></th>
                    <td valign="middle"><input type="text" id="Birthday" readonly name="Birthday" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話2：</span></th>
                    <td valign="middle"><input type="text" id="Tel2" name="Tel2" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">年齡：</span></th>
                    <td valign="middle"><input type="text" id="Age" name="Age" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">行動電話：</span></th>
                    <td valign="middle"><input type="text" id="Mobile" name="Mobile" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">籍貫：</span></th>
                    <td valign="middle"><input type="text" id="BirthPlace" name="BirthPlace" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">現居地址：</span></th>
                    <td valign="middle"><input type="text" id="LivingAddress" name="LivingAddress" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">婚姻狀況：</span></th>
                    <td valign="middle">
                    	<select id="MaritalStatus" name="MaritalStatus" class="halfWidth sized-text-1">
                        	<option></option>
                        <?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<option value="<?php echo $OptionAry[$i][0]?>"><?php echo $OptionAry[$i][4]?></option>
                        <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">緊急聯絡人＊：</span></th>
                    <td valign="middle"><input type="text" id="EmergencyContact" name="EmergencyContact" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">子女：</span></th>
                    <td valign="middle"><input type="text" id="Child" name="Child" class="smallWidth sized-text-normal" value="">個</td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">緊急聯絡人電話＊：</span></th>
                    <td valign="middle"><input type="text" id="Tel" name="Tel" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">家庭狀況（描述）：</span></th>
                    <td valign="middle"><input type="text" id="FamilyDescription" name="FamilyDescription" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" id="Account" name="Account" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">畢業學校／系所：</span></th>
                    <td valign="middle"><input type="text" id="GraduatedSchool" name="GraduatedSchool" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" id="PWD" name="PWD" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">畢業時間：</span></th>
                    <td valign="middle"><input type="text" id="GraduatedTime" name="GraduatedTime" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">可用系統功能：</span></th>
                    <td valign="middle"><label><input type="checkbox" id="UseApp" name="UseApp" value="1"><span class="sized-text-1">Web</span></label>&nbsp;&nbsp;<label><input type="checkbox" id="UseApp" name="UseApp" value="2"><span class="sized-text-1">APP</span></label></td>
                    <th valign="top"><span class="hor-box-text-normal">入行時間：</span></th>
                    <td valign="middle"><input type="text" id="EntryTime" name="EntryTime" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top">&nbsp;</th>
                    <td valign="middle">&nbsp;</td>
                    <th valign="top"><span class="hor-box-text-normal">飲食禁忌：</span></th>
                    <td valign="middle">
                    	<select id="FoodTaboos" name="FoodTaboos" class="smallWidth sized-text-1">
                        	<option value="葷">葷</option>
                            <option value="素">素</option>
                            <option value="無">無</option>
                        </select>
                    	<input type="text" id="FoodTaboosNote" name="FoodTaboosNote" class="smallWidth sized-text-normal" value=""></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">疾病史：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($MedAry);$i++){?>
                        	<label><input type="checkbox" id="OptionalID" name="OptionalID" value="<?php echo $MedAry[$i][0];?>"><span class="sized-text-1"><?php echo $MedAry[$i][4];?></span></label>&nbsp;
                            <?php if($i%6==0 && $i!=0){?>
                            	<div class="spacingBlock"></div>
                            <?php }?>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">藥物過敏：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="1"><span class="sized-text-1">不清楚</span></label>&nbsp;
                    	<label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="2"><span class="sized-text-1">無</span></label>&nbsp;
                        <label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="3"><span class="sized-text-1">有</span></label>&nbsp;<input type="text" id="Drugallergy" name="Drugallergy" class="halfWidth sized-text-normal" value="">
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">抽菸：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="Smoke" name="Smoke" value="N"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="Smoke" name="Smoke" value="Y"><span class="sized-text-1">有，一天</span></label>&nbsp;<input type="text" id="SmokeUseNumber" name="SmokeUseNumber" class="miniWidth sized-text-normal" value="">根，&nbsp;
                        <span class="sized-text-1">菸齡</span></label>&nbsp;<input type="text" id="SmokeAge" name="SmokeAge" class="miniWidth sized-text-normal" value="">年&nbsp;<label><input type="checkbox" id="Smoke" name="Smoke" value="O"><span class="sized-text-1">
                        已戒</span></label>&nbsp;<input type="text" id="SmokeQuitAge" name="SmokeQuitAge" class="miniWidth sized-text-normal" value="">年
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">檳榔：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="Areca" name="Areca" value="N"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="Areca" name="Areca" value="Y"><span class="sized-text-1">有，一天</span></label>&nbsp;<input type="text" id="ArecaUseNumber" name="ArecaUseNumber" class="miniWidth sized-text-normal" value="">顆，&nbsp;
                        <span class="sized-text-1">已嚼</span></label>&nbsp;<input type="text" id="ArecaAge" name="ArecaAge" class="miniWidth sized-text-normal" value="">年&nbsp;<label><input type="checkbox" id="Areca" name="Areca" value="O"><span class="sized-text-1">
                        已戒</span></label>&nbsp;<input type="text" id="ArecaQuitAge" name="ArecaQuitAge" class="miniWidth sized-text-normal" value="">年
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">喝酒：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="IsDrink" name="IsDrink" value="1"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="IsDrink" name="IsDrink" value="2"><span class="sized-text-1">偶而喝</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" value="3"><span class="sized-text-1">習慣性喝</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" value="4"><span class="sized-text-1">已戒酒</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" value="5"><span class="sized-text-1">不清楚</span></label>&nbsp;
					</td>
                </tr>
            </table>
</form>           
            <div class="spacingBlock"></div>
            
            <div><!-- 若無須使用可整段移除 -->
            	<ul class="finalControl">
                	<li><button type="button" onClick="ChkForm(DataForm)" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" onClick="history.go(-1)" class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
        </div>

        <!-- 資料新增功能 end -->
        
        <!-- 功能內容 end -->
    </div>
</body>
</html>