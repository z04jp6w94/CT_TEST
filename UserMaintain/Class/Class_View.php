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
	$USER_NUM = $_SESSION["M_USER_Num"];													//管理員序號
//資料庫連線
	$MySql = new mysql();
//定義一般參數
	$DataKey = trim(GetxRequest("DataKey"));
//資料
	$Sql = " Select A.*, C.Comment From Class A";
	$Sql .= " left join Template B on B.Id = A.TemplateId ";
	$Sql .= " left join OptionalItem C on C.Id = B.ClassFrequencyId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and A.Id = $DataKey";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($initRun);
//左
	$Sql = " select StudentId from ClassMember ";
	$Sql .= " where 1 =1 ";
	$DT = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$DTAry = $MySql -> db_array($Sql,1);
	for($i=0;$i<count($DTAry);$i++){
		if($i<count($DTAry)){
			$Stu = $Stu.$DTAry[$i]."','";
		}else{
			$Stu = '';
		}
	}
//右
	$Sql = " select StudentId from ClassMember ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and ClassId = '" .$rs["Id"]. "'";
	$DT = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$DTAry2 = $MySql -> db_array($Sql,1);
	for($i=0;$i<count($DTAry2);$i++){
		if($i<count($DTAry2)){
			$Stu2 = $Stu2.$DTAry2[$i]."','";
		}else{
			$Stu2 = '';
		}
	}		
//導師
	$Sql = " select Id, FullName from Personnel ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and IdentityTypeId = '000000000000002' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TeachAry = $MySql -> db_array($Sql,2);
//課表
	$Sql = " select Id, TemplateName from Template ";
	$Sql .= " where 1 = 1 ";
	$initRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TemplateAry = $MySql -> db_array($Sql,2);	
//學生左
	$Sql = " select Id, Name from Student ";
	$Sql .= " where 1 = 1";
	$Sql .= " and ClientId = '".$USER_NUM."' ";
	$Sql .= " and Id not in('$Stu')";
	
	$initRun3 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$StudentAry1 = $MySql -> db_array($Sql,2);
//學生右
	$Sql = " select Id, Name from Student ";
	$Sql .= " where 1 = 1";
	$Sql .= " and ClientId = '".$USER_NUM."' ";
	$Sql .= " and Id in('$Stu2')";
	$initRun4 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$StudentAry2 = $MySql -> db_array($Sql,2);
//處理上課日期
	$Week = str_replace(",","",$rs["ClassWeek"]);
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
			$('.datepicker').datepicker({
				changeYear : true,
				yearRange : "-60:+10",
                changeMonth : true,
				showOn: "button",
				buttonImage: "../../Images/calendar.png",
				buttonImageOnly: true,
				dateFormat:'yymmdd'
			});	
			//學生選擇框
				
		});

		function ChkForm(_Form){
			 
			$('#StudentId option').attr('selected','selected'); 			//轉selected防止沒存取
			
			get_Checked_Checkbox_By_Name('ClassWeek');
			
			if(IsEmpty(_Form.ClassName,'班級名稱')){
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
                <li class="title"><span class="hor-box-text">班級與學生管理</span></li>
                <li class="current"><span class="hor-box-text">班級管理</span></li><!-- 如果沒有，就不寫入這個LI -->
                <li class="current"><span class="hor-box-text">檢視班級</span></li><!-- 如果沒有，就不寫入這個LI -->
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
                    <th valign="top"><span class="hor-box-text-normal">班級名稱：</span></th>
                    <td valign="middle"><input type="text" id="ClassName" name="ClassName" class="halfWidth sized-text-normal" readonly placeholder="例：105 擴思訊之福祿貝爾1-10 20天班" value="<?php echo $rs["ClassName"];?>"></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">班級級別：</span></th>
                    <td valign="middle">
                    	<select id="ClassLevelId" name="ClassLevelId" disabled class="halfWidth sized-text-1">
                        	<option value="大班" <?php if($rs["ClassLevelId"]=='大班'){?>selected<?php }?>>大班</option>
                            <option value="中班" <?php if($rs["ClassLevelId"]=='中班'){?>selected<?php }?>>中班</option>
                            <option value="小班" <?php if($rs["ClassLevelId"]=='小班'){?>selected<?php }?>>小班</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">導師：</span></th>
                    <td valign="middle">
                    	<select id="TeacherId" name="TeacherId" disabled class="halfWidth sized-text-1">
                        	<?php for($i=0;$i<count($TeachAry);$i++){?>
                            	<option value="<?php echo $TeachAry[$i][0];?>" <?php if($rs["TeacherId"]==$TeachAry[$i][0]){?>selected<?php }?>><?php echo $TeachAry[$i][1];?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">使用課表：</span></th>
                    <td valign="middle">
                    	<select id="TemplateId" name="TemplateId" disabled class="halfWidth sized-text-1">
                        	<?php for($i=0;$i<count($TemplateAry);$i++){?>
                            	<option value="<?php echo $TemplateAry[$i][0];?>" <?php if($rs["TemplateId"]==$TemplateAry[$i][0]){?>selected<?php }?>><?php echo $TemplateAry[$i][1];?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle"><span class="hor-box-text-normal"><?php echo $rs["Comment"];?></span></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">排課開始時間：</span></th>
                    <td valign="middle"><input type="text" id="StartTime" name="StartTime" class="halfWidth sized-text-normal" readonly value="<?php echo $rs["StartTime"];?>"></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">上課日期：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="1" <?php if(strstr($Week,"1")){?>checked<?php }?>><span class="sized-text-1">週一</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="2" <?php if(strstr($Week,"2")){?>checked<?php }?>><span class="sized-text-1">週二</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="3" <?php if(strstr($Week,"3")){?>checked<?php }?>><span class="sized-text-1">週三</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="4" <?php if(strstr($Week,"4")){?>checked<?php }?>><span class="sized-text-1">週四</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="5" <?php if(strstr($Week,"5")){?>checked<?php }?>><span class="sized-text-1">週五</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="6" <?php if(strstr($Week,"6")){?>checked<?php }?>><span class="sized-text-1">週六</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" disabled value="7" <?php if(strstr($Week,"7")){?>checked<?php }?>><span class="sized-text-1">週日</span></label>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">班級學生：</span></th>
                    <td valign="middle">
                        <br>
                        <table cellpadding="0" cellspacing="0" class="itemMover">
                            <tr>
                                <td>
                                    <div class="box">
                                        <p class="title"><span class="sized-text-1">可選學生</span></p>
                                        <select id="StudentId1" name="StudentId1" disabled multiple>
                                        	<?php for($i=0;$i<count($StudentAry1);$i++){?>
                                            <option value="<?php echo $StudentAry1[$i][0];?>"><?php echo $StudentAry1[$i][1];?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </td>
                                <td valign="middle">
                                    <div class="btns">
                                        <button type="button" class="toRight"><span class="hiddenItem">向右移</span></button>
                                        <br>
                                        <br>
                                        <button type="button" class="toLeft"><span class="hiddenItem">向左移</span></button>
                                    </div>
                                </td>
                                <td>
                                    <div class="box">
                                        <p class="title"><span class="sized-text-1">已選學生</span></p>
                                        <select id="StudentId" name="StudentId" disabled multiple>
                                            <?php for($i=0;$i<count($StudentAry2);$i++){?>
                                            <option value="<?php echo $StudentAry2[$i][0];?>" selected><?php echo $StudentAry2[$i][1];?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>
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