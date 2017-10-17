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
//導師
	$Sql = " select Id, FullName from Personnel ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and IdentityTypeId = '000000000000002' ";
	$Sql .= " and ClientId = '".$USER_NUM."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TeachAry = $MySql -> db_array($Sql,2);
//課表
	$Sql = " select Id, TemplateName from Template ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Enabled = 'Y' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$initRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TemplateAry = $MySql -> db_array($Sql,2);	
//班級級別
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'ClassLevel' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$CLevelAry = $MySql -> db_array($Sql,6);	
//
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
//學生
	$Sql = " select Id, Name from Student ";
	$Sql .= " where 1 = 1";
	$Sql .= " and DeleteStatus = 'N'";
	$Sql .= " and Enabled = 'Y'";
	$Sql .= " and ClientId = '".$USER_NUM."' ";
	$Sql .= " and Id not in('$Stu')";
	$initRun3 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$StudentAry = $MySql -> db_array($Sql,2);		
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
<title>Web_園方 教師 自購家長(管理者介面) 班級與學生管理 班級管理 新增班級</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){
			
			$("#TemplateId").change(function(){				
				$.ajax({
					url: "Freq.php",
					data: {Key: $("#TemplateId").val()},
					type:"POST",
					success: function(xml){	
						if($('resu', xml).text() == '1'){
							$("#Feq").html($('rtmsg', xml).text());
						}else{
							alert($('rtmsg', xml).text());
						}					
					},			
					 error:function(xhr, ajaxOptions, thrownError){ 
						alert(xhr.status); 
						alert(thrownError); 
					}
				});	
			});
			
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
			$(".toRight").click(function() { //左→右 
				var $option = $("#StudentId1 option:selected"); 
				$option.appendTo("#StudentId"); 
			}); 
			$(".toLeft").click(function() {//右→左 
				var $option = $("#StudentId option:selected"); 
				$option.appendTo("#StudentId1"); 
			}); 
	
		});

		function ChkForm(_Form){
			 
			$('#StudentId option').attr('selected','selected'); 			//轉selected防止沒存取	 
		
			get_Checked_Checkbox_By_Name('ClassWeek');
			get_Select_By_Name('StudentId');
			
			
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
		
		function get_Select_By_Name(name) {

			var arr = [];
			
			$("#"+name+" option").each  ( function() {
			   arr.push ( $(this).val() );
			});

            $('#'+name+'_TEXT').val(arr);
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
                <li class="current"><span class="hor-box-text">新增班級</span></li><!-- 如果沒有，就不寫入這個LI -->
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
    <input class="textInput" name="ClassWeek_TEXT" type="hidden" id="ClassWeek_TEXT" size="100" tabindex="1" value="" />
    <input class="textInput" name="StudentId_TEXT" type="hidden" id="StudentId_TEXT" size="100" tabindex="1" value="" />	
        <div class="addSystemUser newClass">
            <table cellpadding="0" cellspacing="0" class="leftHeader">
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">班級名稱：</span></th>
                    <td valign="middle"><input type="text" id="ClassName" name="ClassName" class="halfWidth sized-text-normal" placeholder="例：105 擴思訊之福祿貝爾1-10 20天班" value=""></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">班級級別：</span></th>
                    <td valign="middle">
                    	<select id="ClassLevelId" name="ClassLevelId" class="halfWidth sized-text-1">
                        <?php for($i=0;$i<count($CLevelAry);$i++){?>
                        	<option value="<?php echo $CLevelAry[$i][0];?>"><?php echo $CLevelAry[$i][4];?></option>
                        <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">導師：</span></th>
                    <td valign="middle">
                    	<select id="TeacherId" name="TeacherId" class="halfWidth sized-text-1">
                        	<?php for($i=0;$i<count($TeachAry);$i++){?>
                            	<option value="<?php echo $TeachAry[$i][0];?>"><?php echo $TeachAry[$i][1];?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">使用課表：</span></th>
                    <td valign="middle">
                    	<select id="TemplateId" name="TemplateId" class="halfWidth sized-text-1">
                        	<option></option>
                        	<?php for($i=0;$i<count($TemplateAry);$i++){?>
                            	<option value="<?php echo $TemplateAry[$i][0];?>"><?php echo $TemplateAry[$i][1];?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle"><span id="Feq" name="Feq" class="hor-box-text-normal"></span></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">排課開始時間：</span></th>
                    <td valign="middle"><input type="text" id="StartTime" name="StartTime" class="halfWidth sized-text-normal datepicker" readonly value=""></td>
                </tr>
                <tr>
                    <th valign="top"><span class="hor-box-text-normal">上課日期：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="1"><span class="sized-text-1">週一</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="2"><span class="sized-text-1">週二</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="3"><span class="sized-text-1">週三</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="4"><span class="sized-text-1">週四</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="5"><span class="sized-text-1">週五</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="6"><span class="sized-text-1">週六</span></label>&nbsp;
                        <label><input type="checkbox" id="ClassWeek" name="ClassWeek" value="7"><span class="sized-text-1">週日</span></label>
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
                                        <select id="StudentId1" name="StudentId1" multiple>
                                        	<?php for($i=0;$i<count($StudentAry);$i++){?>
                                            <option value="<?php echo $StudentAry[$i][0];?>"><?php echo $StudentAry[$i][1];?></option>
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
                                        <select id="StudentId" name="StudentId" multiple>
                                            <!--<option value="AAA">AAA</option>
                                            <option>BBB</option>-->
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
                	<li><button type="button" onClick="ChkForm(DataForm)" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" onClick="history.go(-1)" class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>

        <!-- 資料新增功能 end -->
        
        <!-- 功能內容 end -->
    </div>
</body>
</html>