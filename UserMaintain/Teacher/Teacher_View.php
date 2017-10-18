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
//資料
	$Sql = " Select * From Personnel where Id = $DataKey ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($initRun);
//
	$Sql = " select OptionalID from DiseaseT ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and Id = '" .$rs["Id"]. "'";	
	$DT = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$DTAry = $MySql -> db_array($Sql,1);
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
<title>擴思訊 Cross Think 後台 教案管理 檢視教案</title>
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
                <li class="current"><span class="hor-box-text">檢視教師</span></li><!-- 如果沒有，就不寫入這個LI - -->
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
        <div class="addNewTeacher">
        	<div class="spacingBlock"></div>
        	<h2><span class="sized-text-1">基本資料區</span></h2>
            <div class="spacingBlock"></div>
        	<table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">姓名＊：</span></th>
                    <td valign="middle"><input type="text" id="FullName" name="FullName" class="halfWidth sized-text-normal" placeholder="必填" readonly value="<?php echo $rs["FullName"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">性別：</span></th>
                    <td valign="middle">
                    	<select id="Sex" name="Sex" disabled class="halfWidth sized-text-1">
                        	<option value="男" <?php if($rs["Sex"] == '男'){?>selected<?php }?>>男</option>
                            <option value="女" <?php if($rs["Sex"] == '女'){?>selected<?php }?>>女</option>
                            <option value="其他" <?php if($rs["Sex"] == '其他'){?>selected<?php }?>>其他</option>
                        </select>
                    </td>
                </tr>
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話1：</span></th>
                    <td valign="middle"><input type="text" id="Tel1" name="Tel1" class="halfWidth sized-text-normal" readonly value="<?php echo $rs["Tel1"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">生日：</span></th>
                    <td valign="middle"><input type="text" id="Birthday" readonly name="Birthday" class="halfWidth sized-text-normal" value="<?php echo $rs["Birthday"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話2：</span></th>
                    <td valign="middle"><input type="text" id="Tel2" name="Tel2" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Tel2"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">年齡：</span></th>
                    <td valign="middle"><input type="text" id="Age" name="Age" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Age"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">行動電話：</span></th>
                    <td valign="middle"><input type="text" id="Mobile" name="Mobile" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Mobile"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">籍貫：</span></th>
                    <td valign="middle"><input type="text" id="BirthPlace" name="BirthPlace" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["BirthPlace"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">現居地址：</span></th>
                    <td valign="middle"><input type="text" id="LivingAddress" name="LivingAddress" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["LivingAddress"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">婚姻狀況：</span></th>
                    <td valign="middle">
                    	<select id="MaritalStatus" name="MaritalStatus" disabled readonly class="halfWidth sized-text-1">
                        	<option></option>
                        <?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<option value="<?php echo $OptionAry[$i][0]?>" <?php if($rs["MaritalStatus"] == $OptionAry[$i][0]){?>selected<?php }?>><?php echo $OptionAry[$i][4]?></option>
                        <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">緊急聯絡人＊：</span></th>
                    <td valign="middle"><input type="text" id="EmergencyContact" name="EmergencyContact" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["EmergencyContact"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">子女：</span></th>
                    <td valign="middle"><input type="text" id="Child" name="Child" readonly class="smallWidth sized-text-normal" value="<?php echo $rs["Child"];?>">個</td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">緊急聯絡人電話＊：</span></th>
                    <td valign="middle"><input type="text" id="Tel" name="Tel" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Tel"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">家庭狀況（描述）：</span></th>
                    <td valign="middle"><input type="text" id="FamilyDescription" name="FamilyDescription" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["FamilyDescription"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" id="Account" name="Account" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Account"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">畢業學校／系所：</span></th>
                    <td valign="middle"><input type="text" id="GraduatedSchool" name="GraduatedSchool" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["GraduatedSchool"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" id="PWD" name="PWD" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["PWD"];?>"></td>
                    <th valign="top"><span class="hor-box-text-normal">畢業時間：</span></th>
                    <td valign="middle"><input type="text" id="GraduatedTime" name="GraduatedTime" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["GraduatedTime"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">可用系統功能：</span></th>
                    <td valign="middle"><label><input type="checkbox" id="UseApp" name="UseApp" disabled value="1" <?php if(stristr($rs["UseApp"], "1")){?>checked<?php }?>><span class="sized-text-1">Web</span></label>&nbsp;&nbsp;<label><input type="checkbox" id="UseApp" name="UseApp" disabled value="2" <?php if(stristr($rs["UseApp"], "2")){?>checked<?php }?>><span class="sized-text-1">APP</span></label></td>
                    <th valign="top"><span class="hor-box-text-normal">入行時間：</span></th>
                    <td valign="middle"><input type="text" id="EntryTime" name="EntryTime" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["EntryTime"];?>"></td>
                </tr>
                <tr>
                	<th valign="top">&nbsp;</th>
                    <td valign="middle">&nbsp;</td>
                    <th valign="top"><span class="hor-box-text-normal">飲食禁忌：</span></th>
                    <td valign="middle">
                    	<select id="FoodTaboos" name="FoodTaboos" disabled class="smallWidth sized-text-1">
                        	<option value="葷" <?php if($rs["FoodTaboos"] =='葷'){?><?php }?>>葷</option>
                            <option value="素" <?php if($rs["FoodTaboos"] =='素'){?><?php }?>>素</option>
                            <option value="無" <?php if($rs["FoodTaboos"] =='無'){?><?php }?>>無</option>
                        </select>
                    	<input type="text" id="FoodTaboosNote" name="FoodTaboosNote" readonly class="smallWidth sized-text-normal" value="<?php echo $rs["FoodTaboosNote"];?>"></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">疾病史：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($MedAry);$i++){?>
                        	<label><input type="checkbox" id="OptionalID" name="OptionalID" disabled value="<?php echo $MedAry[$i][0];?>" <?php if(in_array( $MedAry[$i][0], $DTAry, true)){?>checked<?php }?>><span class="sized-text-1"><?php echo $MedAry[$i][4];?></span></label>&nbsp;
                            <?php if($i%6==0 && $i!=0){?>
                            	<div class="spacingBlock"></div>
                            <?php }?>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">藥物過敏：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" <?php if($rs["DrugallergyStatus"]=='1'){?>checked<?php }?> value="1" disabled><span class="sized-text-1">不清楚</span></label>&nbsp;
                    	<label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" <?php if($rs["DrugallergyStatus"]=='2'){?>checked<?php }?> value="2" disabled><span class="sized-text-1">無</span></label>&nbsp;
                        <label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" <?php if($rs["DrugallergyStatus"]=='3'){?>checked<?php }?> value="3" disabled><span class="sized-text-1">有</span></label>&nbsp;<input type="text" id="Drugallergy" name="Drugallergy" readonly class="halfWidth sized-text-normal" value="<?php echo $rs["Drugallergy"];?>">
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">抽菸：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="Smoke" name="Smoke" disabled value="N" <?php if($rs["Smoke"] =='N'){?>checked<?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="Smoke" name="Smoke" disabled value="Y" <?php if($rs["Smoke"] =='Y'){?>checked<?php }?>><span class="sized-text-1">有，一天</span></label>&nbsp;<input type="text" id="SmokeUseNumber" name="SmokeUseNumber" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["SmokeUseNumber"];?>">根，&nbsp;
                        <span class="sized-text-1">菸齡</span></label>&nbsp;<input type="text" id="SmokeAge" name="SmokeAge" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["SmokeAge"];?>">年&nbsp;<label><input type="checkbox" id="Smoke" name="Smoke" disabled value="O" <?php if($rs["Smoke"] =='O'){?>checked<?php }?>><span class="sized-text-1">
                        已戒</span></label>&nbsp;<input type="text" id="SmokeQuitAge" name="SmokeQuitAge" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["SmokeQuitAge"];?>">年
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">檳榔：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="Areca" name="Areca" disabled value="N" <?php if($rs["Areca"] =='N'){?>checked<?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="Areca" name="Areca" disabled value="Y" <?php if($rs["Areca"] =='Y'){?>checked<?php }?>><span class="sized-text-1">有，一天</span></label>&nbsp;<input type="text" id="ArecaUseNumber" name="ArecaUseNumber" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["ArecaUseNumber"];?>">顆，&nbsp;
                        <span class="sized-text-1">已嚼</span></label>&nbsp;<input type="text" id="ArecaAge" name="ArecaAge" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["ArecaAge"];?>">年&nbsp;<label><input type="checkbox" id="Areca" name="Areca" disabled value="O" <?php if($rs["Areca"] =='O'){?>checked<?php }?>><span class="sized-text-1">
                        已戒</span></label>&nbsp;<input type="text" id="ArecaQuitAge" name="ArecaQuitAge" readonly class="miniWidth sized-text-normal" value="<?php echo $rs["ArecaQuitAge"];?>">年
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">喝酒：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="IsDrink" name="IsDrink" disabled value="1" <?php if($rs["IsDrink"] =='1'){?>checked<?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="checkbox" id="IsDrink" name="IsDrink" disabled value="2" <?php if($rs["IsDrink"] =='2'){?>checked<?php }?>><span class="sized-text-1">偶而喝</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" disabled value="3" <?php if($rs["IsDrink"] =='3'){?>checked<?php }?>><span class="sized-text-1">習慣性喝</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" disabled value="4" <?php if($rs["IsDrink"] =='4'){?>checked<?php }?>><span class="sized-text-1">已戒酒</span></label>&nbsp;
                        <label><input type="checkbox" id="IsDrink" name="IsDrink" disabled value="5" <?php if($rs["IsDrink"] =='5'){?>checked<?php }?>><span class="sized-text-1">不清楚</span></label>&nbsp;
					</td>
                </tr>
            </table>
</form>           
            <div class="spacingBlock"></div>
            
            <div><!-- 若無須使用可整段移除 -->
            	<ul class="finalControl">
                	<!--<li><button type="button" onClick="ChkForm(DataForm)" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>-->
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