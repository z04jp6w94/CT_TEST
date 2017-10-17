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
//親屬關係Kinship
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and OptionalId = 'Kinship' ";
	$Sql .= " and Language = 'zh-tw' ";
	$initRun4 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
	$KinshipAry = $MySql -> db_array($Sql,6);		
//曾患疾病
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'Vaccination' ";
	$Sql .= " and Language = 'zh-tw' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
//曾患疾病
	$Sql = " select * from OptionalItem";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'OnceIllness'";
	$Sql .= " and Language = 'zh-tw' ";	
	$initRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$MedAry = $MySql -> db_array($Sql,6);
//常患疾病	
	$Sql = " select * from OptionalItem";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'OftenIllness'";
	$Sql .= " and Language = 'zh-tw' ";	
	$initRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OftenAry = $MySql -> db_array($Sql,6);
//家族病史	
	$Sql = " select * from OptionalItem";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'FamilyHistory'";
	$Sql .= " and Language = 'zh-tw' ";	
	$initRun3 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$FamAry = $MySql -> db_array($Sql,6);
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
<title>Web_園方 教師 自購家長(管理者介面) 班級與學生管理 學生管理 新增學生</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
	<link rel="stylesheet" type="text/css" href="/Css/shadowboxByBackOffice.css" />
    <link rel="stylesheet" type="text/css" href="/Css/Maintain.css" />
    <link rel="stylesheet" type="text/css" href="/Css/tipsy.css" />
    <link rel="stylesheet" type="text/css" href="/Css/Grid.css" />
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="/Css/MaintainThemes/Default/Default.css" id="StyleThemes" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    
    <script type="text/javascript" src="/Js/ComFun.js"></script>
    <script type="text/javascript" src="/Js/Grid.js"></script>
    <script type="text/javascript" src="/Js/SysPageControl.js"></script>
    <script type="text/javascript" src="/Js/jquery.tipsy.js"></script>
    <script type="text/javascript" src="/Js/jquery.ChiliUpload.js"></script>
    <script type="text/javascript" src="/Js/shadowboxByBackOffice.js"></script> 
    
	<script type="text/javascript">
		var UploadLimit = 1;
		var UploadType = 1;
		var UploadCount = 0;
		var UploadImgListH = 100;
		
		$(document).ready(function(){
			
		// - Set Shadowbox
			Shadowbox.init({
				players:    ["img", "iframe", "html"]
			});	
			
			var now_count = 1;
			
			$('#AdmissionDate').datepicker({
				changeYear : true,
				yearRange : "-60:+10",
                changeMonth : true,
				showOn: "button",
				buttonImage: "../../Images/calendar.png",
				buttonImageOnly: true,
				dateFormat:'yymmdd'
			});	
			
			$('#Birth').datepicker({
				changeYear : true,
				yearRange : "-18:+0",
				maxDate: "today",
                changeMonth : true,
				showOn: "button",
				buttonImage: "../../Images/calendar.png",
				buttonImageOnly: true,
				dateFormat:'yymmdd',
				onSelect: function(selectedDate){
					switch(this.id){
						case 'Birth':
							var dateMin = $('#Birth').datepicker('getDate');
							//var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1);
							var Today = new Date();
							Date_substr(dateMin, Today);
							//$('#EricNewsM_EdDate').datepicker('option', 'minDate', rMin);
							break;
					}
				}
			});
			
		
			$('#Btn_ADD3').click(function(){
				now_count = now_count + 1
				$('#now_count').val(now_count);
				var Html = '';
							
				Html += '<table cellpadding="0" cellspacing="0" class="leftHeader">';
                //Html += '<textarea id="hard' + now_count + '" name="hard' + now_count + '" class="fullWidth sized-text-1"></textarea>';
                Html += '<tr>';
                Html += '<th valign="top"><span class="needMark hor-box-text-normal">家長姓名＊：</span></th>';
                Html += '<td valign="middle"><input type="text" id="Name'+ now_count +'" name="Name'+ now_count +'" class="fullWidth sized-text-normal" value=""></td>';
                Html += '<th valign="top"><span class="hor-box-text-normal">身分描述：</span></th>';
                Html += '<td valign="middle">';
				Html += '<select id="KinshipId'+ now_count +'" name="KinshipId'+ now_count +'" class="fullWidth sized-text-1">'
				<?php for($i=0;$i<count($KinshipAry);$i++){?>
				Html += '<option value="<?php echo $KinshipAry[$i][0];?>"><?php echo $KinshipAry[$i][4];?></option>'
				<?php }?>
				Html += '</select>';
				Html += '</td>';
				Html += '</tr>';
				Html += '<tr>';
				Html += '<th valign="top"><span class="needMark hor-box-text-normal">聯絡電話1＊：</span></th>';
				Html += '<td valign="middle"><input type="text" id="Tel1'+ now_count +'" name="Tel1'+ now_count +'" class="fullWidth sized-text-normal" value=""></td>';
				Html += '<th valign="top"><span class="hor-box-text-normal">聯絡電話2：</span></th>';
				Html += '<td valign="middle"><input type="text" id="Tel2'+ now_count +'" name="Tel2'+ now_count +'" class="fullWidth sized-text-normal" value=""></td>';
				Html += '</tr>';
				Html += '<tr>';
				Html += '<th valign="top"><span class="needMark hor-box-text-normal">行動電話1＊：</span></th>';
				Html += '<td valign="middle"><input type="text" id="Mobile1'+ now_count +'" name="Mobile1'+ now_count +'" class="fullWidth sized-text-normal" value=""></td>';
				Html += '<th valign="top"><span class="hor-box-text-normal">行動電話2：</span></th>';
				Html += '<td valign="middle"><input type="text" id="Mobile2'+ now_count +'" name="Mobile2'+ now_count +'" class="fullWidth sized-text-normal" value=""></td>';
				Html += '</tr>';
				Html += '<tr>';
				Html += '<td colspan="4">';
				Html += '<label><input type="checkbox" id="IsCarer'+ now_count +'" name="IsCarer'+ now_count +'" value="Y"><span class="sized-text-1">主要照顧者</span></label>&nbsp;';
				Html += '<label><input type="checkbox" id="IsEmergency'+ now_count +'" name="IsEmergency'+ now_count +'" value="Y"><span class="sized-text-1">緊急聯絡人</span></label>';
				Html += '</td>';
				Html += '</tr>';
                Html += '</table>';
								
				$(".addTable").append(Html);
			});
			
			$("#btn_uploadfile").chiliupload({
				flasID	: 'FileUpLoad',
				path	: '/Js/FileUpLoad.swf',
				type	: UploadType,
				q		: UploadLimit,	
				ex		: "*.jpg;*.jpeg;*.gif;*.png",	
				size	: "",
				cb		: "1",
				url		: "/UpLoad/FileUpload.php?Path=<?php echo temp;?>"
			});
			ChkRunning()
			
		// other
			$(".loadingdel").find("a").live("click", function(){
				var _index = $(this).parent().parent().index();
				var _DelUrl = '/UpLoad/FileDelete.php';
				Shadowbox.open({
					player	: 'iframe',
					content	: '/CommonPage/PopupWarningWindow.php?CbFN=DeleteUpLoadFile&CbIndex=' + _index + '&CbDelUrl=' + _DelUrl,
					width	: 390,
					height	: 150
				});
			});		
			
		});
		
		
		function Date_substr(Choose, NowDate)
		{
		//定義起始 年月日
			var StartDate=Choose;
		//定義結束 年月日
			var EndDate= NowDate;
		
			var gapDay = DateDifference(StartDate,EndDate);
			
			var gapMonth = Math.floor(gapDay / 30) ;
			
			var gapYear = Math.floor(gapMonth / 12); 
			
			if(gapYear > 0 ){
				gapMonth = gapMonth - ( gapYear * 12 )
			}
			
			$("#Age_Y").val(gapYear)
			$("#Age_M").val(gapMonth) 

		}


		function DateDifference(StartDate,EndDate)
		{
			
		   var myStartDate = new Date(StartDate);
		   var myEndDate = new Date(EndDate);
		
		   // 天數，86400000是24*60*60*1000，除以86400000就是有幾天
			 return (myEndDate - myStartDate)/ 86400000 ;
		
		  
		}

		function ChkRunning(){
			switch(UploadType){
				case 1:
					if ($('li.img').length >= 1){
						$("#btn_uploadfile").hide();
						$("#btn_uploading").hide();
						$("#FileUpLoad").css({'visibility' : 'hidden'});
					}else{
						$("#btn_uploadfile").show();
						$("#btn_uploading").hide();
						$("#FileUpLoad").css({'visibility' : 'visible'});
					}
				break;
				case 2:				
					if (UploadCount <= 0){
						UploadCount = 0;
						$("#btn_uploadfile").show();
						$("#btn_uploading").hide();
						$(".chiliupload").show();
						$("#FileUpLoad").css({'visibility' : 'hidden'});
					}else {
						$("#btn_uploadfile").hide();
						$("#btn_uploading").show();
						$(".chiliupload").hide();
						$("#FileUpLoad").css({'visibility' : 'visible'});
					}
				break;
			}
		}
		
		function DelTR(n){
			$('.TR'+n).remove();
		}

		function ChkForm(_Form){
			
			get_Checked_Checkbox_By_Name('Vaccination');
			get_Checked_Checkbox_By_Name('OnceIllness');
			get_Checked_Checkbox_By_Name('OftenIllness');
			get_Checked_Checkbox_By_Name('FamilyHistory');
			
			if(IsEmpty(_Form.Name,'學生姓名')){
			}else if(IsEmpty(_Form.NickName,'學生暱稱')){
			}else if(IsEmpty(_Form.Birth,'生日')){
			}else if(IsEmpty(_Form.Age_Y,'年齡(年)')){
			}else if(IsEmpty(_Form.Age_M,'年齡(月)')){
			}else if(IsEmpty(_Form.Age_M,'年齡(月)')){
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
		
		function sameaspay(f1){
			if(f1.Same.checked){		
				f1.ResidenceAddress.value = f1.MailingAddress.value;
				//$("#City2").change();
				//f1.Area2.length = 0;
				//$('select#Area2')[0].selectedIndex = 3;
				//f1.Area2.options[0] = new Option(f1.Area.value,f1.Area.value);
				//alert('<option value="'+$("#Area").val()+'">'+$("#Area").text()+'</option>');
				//$("#Area2 option").remove();
				//$("#Area2").append('<option value="'+$("#Area").val()+'">'+$('select#Area option:selected').text()+'</option>');
				//$('select#Area2')[0].selectedIndex = 1;	 
				f1.ResidenceAddress.value = f1.MailingAddress.value;
				$('.lock').attr('disabled', true);
			}else{		
				$('.lock').attr('disabled', false);
				//$('select#City2')[0].selectedIndex = 0;
				$("#MailingAddress").change();	
				//$('select#Area2')[0].selectedIndex = 0;	
			
			}
		} 
	</script>
<style>
.SetCursor{
	cursor: pointer;
}
.gray a {
    min-width: 38px;
    height: 27px;
    background-color: #f5f5f5;
    border: 1px solid #c6c6c6;
    border-radius: 3px;
    padding: 0 10px;
}
.MenuLeft ul.Menunav a, .TipBtn a, .MenuRight ul.Menunav a, .TipBtn a {
    float: left;
    text-decoration: none;
    height: 27px;
    line-height: 27px;
}


</style>
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
                <li class="current"><span class="hor-box-text">學生管理</span></li><!-- 如果沒有，就不寫入這個LI -->
                <li class="current"><span class="hor-box-text">新增學生</span></li><!-- 如果沒有，就不寫入這個LI -->
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
    <input class="textInput" name="Vaccination_TEXT" type="hidden" id="Vaccination_TEXT" size="100" tabindex="1" value="" />	
    <input class="textInput" name="OnceIllness_TEXT" type="hidden" id="OnceIllness_TEXT" size="100" tabindex="1" value="" />
    <input class="textInput" name="OftenIllness_TEXT" type="hidden" id="OftenIllness_TEXT" size="100" tabindex="1" value="" />
    <input class="textInput" name="FamilyHistory_TEXT" type="hidden" id="FamilyHistory_TEXT" size="100" tabindex="1" value="" />
    <input id="now_count" name="now_count" type="hidden" value="1">
        <div class="addSystemUser newStudent">
        	<div class="spacingBlock"></div> 
        	<h2><span class="sized-text-1">基本資料區</span></h2>
            <div class="spacingBlock"></div>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">學生姓名＊：</span></th>
                    <td valign="middle"><input type="text" id="Name" name="Name" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">性別：</span></th>
                    <td valign="middle">
                    	<select id="Sex" name="Sex" class="halfWidth sized-text-1">
                        	<option value="男">男</option>
                            <option value="女">女</option>
                            <option value="其他">其他</option>
                        </select>
                    </td>
                 
                    <td valign="top" rowspan="5">
                        <div class="profilePhoto" id="uploadzone">
                            <span class="MenuLeft">
                                <ul class="Menunav">
                                    <li class="gray"><a class="L333333 SetCursor" id="btn_uploadfile">選擇檔案</a></li>
                                    <li class="gray"><a class="L333333 " id="btn_uploading" style="display: none;">檔案上傳中, 請稍候</a></li>
                                </ul>
                            </span>
                            <ul class="uploadnav">
                            </ul>
                        </div>
                    </td>
                </tr>
                
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">寶寶暱稱：</span></th>
                    <td valign="middle"><input type="text" id="NickName" name="NickName" class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">身高（cm）：</span></th>
                    <td valign="middle"><input type="text" id="Height" name="Height" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">出生日期＊：</span></th>
                    <td valign="middle"><input type="text" id="Birth" name="Birth" readonly class="halfWidth sized-text-normal" value=""></td>
                    <th valign="top"><span class="hor-box-text-normal">體重（kg）：</span></th>
                    <td valign="middle"><input type="text" id="Weight" name="Weight" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">年齡＊：</span></th>
                    <td valign="middle"><input type="text" id="Age_Y" name="Age_Y" class="miniWidth sized-text-normal" readonly value=""><span class="sized-text-normal">歲</span><input type="text" id="Age_M" name="Age_M" readonly class="miniWidth sized-text-normal" value=""><span class="sized-text-normal">個月</span></td>
                    <th valign="top"><span class="hor-box-text-normal">頭圍（cm）：</span></th>
                    <td valign="middle"><input type="text" id="HeadCircumference" name="HeadCircumference" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle"><span class="needMark hor-box-text-normal">&nbsp;</span></td>
                    <th valign="top"><span class="hor-box-text-normal">入園日期：</span></th>
                    <td valign="middle"><input type="text" id="AdmissionDate" name="AdmissionDate" readonly class="halfWidth sized-text-normal" value=""></td>
                </tr>
			</table>
            
            <div class="spacingBlock"></div>
            
            <div class="increasableData">
            	<div class="addBtn"><button id="Btn_ADD3" name="Btn_ADD3" type="button"><span class="hiddenItem">增加</span></button></div>
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                	<tr>
                    	<th valign="top"><span class="needMark hor-box-text-normal">家長姓名＊：</span></th>
                        <td valign="middle"><input type="text" id="Name1" name="Name1" class="fullWidth sized-text-normal" value=""></td>
                        <th valign="top"><span class="hor-box-text-normal">身分描述：</span></th>
                        <td valign="middle">
                        	<select id="KinshipId1" name="KinshipId1" class="fullWidth sized-text-1">
                            	<?php for($i=0;$i<count($KinshipAry);$i++){?>
                                	<option value="<?php echo $KinshipAry[$i][0];?>"><?php echo $KinshipAry[$i][4];?></option>
                                <?php }?>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                    	<th valign="top"><span class="needMark hor-box-text-normal">聯絡電話1＊：</span></th>
                        <td valign="middle"><input type="text" id="Tel11" name="Tel11" class="fullWidth sized-text-normal" value=""></td>
                        <th valign="top"><span class="hor-box-text-normal">聯絡電話2：</span></th>
                        <td valign="middle"><input type="text" id="Tel11" name="Tel11" class="fullWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                    	<th valign="top"><span class="needMark hor-box-text-normal">行動電話1＊：</span></th>
                        <td valign="middle"><input type="text" id="Mobile11" name="Mobile11" class="fullWidth sized-text-normal" value=""></td>
                        <th valign="top"><span class="hor-box-text-normal">行動電話2：</span></th>
                        <td valign="middle"><input type="text" id="Mobile21" name="Mobile21" class="fullWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                    	<td colspan="4">
                        	<label><input type="checkbox" id="IsCarer1" name="IsCarer1" value="Y"><span class="sized-text-1">主要照顧者</span></label>&nbsp;
                            <label><input type="checkbox" id="IsEmergency1" name="IsEmergency1" value="Y"><span class="sized-text-1">緊急聯絡人</span></label>
                        </td>
                    </tr>
				</table>
				<div class="addTable">
                
                </div>
                <!-- 按下新增後新增的範圍 begin -->
             	
                <!-- 按下新增後新增的範圍 end -->
            </div>
            
            <div class="spacingBlock"></div>
            
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">通訊地址：</span></th>
                    <td valign="middle">
                    	<input type="text" id="MailingAddress" name="MailingAddress" class="fullWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">戶籍地址：</span></th>
                    <td valign="middle">
                    	<label><input type="checkbox" id="Same" name="Same" onclick="sameaspay(this.form)" value="Y"><span class="sized-text-1">同通訊地址</span></label><br>
                        <input type="text" id="ResidenceAddress" name="ResidenceAddress" class="fullWidth sized-text-normal lock" value="">
					</td>
                </tr>
			</table>
            
            <div class="spacingBlock"></div>
            
            <table cellpadding="0" cellspacing="0" class="leftHeader">
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">預防接種：</span></th>
                    <td valign="middle">
						<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<label><input type="checkbox" id="Vaccination" name="Vaccination" value="<?php echo $OptionAry[$i][0];?>"><span class="sized-text-1"><?php echo $OptionAry[$i][4];?></span></label>&nbsp;
                            <?php if($i%5==0 && $i!=0){?>
                            	<br>
                            <?php }?>
                        <?php }?>
                        <input type="text" id="VaccinationInput" name="VaccinationInput" class="miniWidth sized-text-normal" value="">
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">曾患疾病：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($MedAry);$i++){?>
                        	<label><input type="checkbox" id="OnceIllness" name="OnceIllness" value="<?php echo $MedAry[$i][0];?>"><span class="sized-text-1"><?php echo $MedAry[$i][4];?></span></label>&nbsp;
                        <?php }?>
                    </td>
				</tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">常患疾病：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($OftenAry);$i++){?>
                        	<label><input type="checkbox" id="OftenIllness" name="OftenIllness" value="<?php echo $OftenAry[$i][0];?>"><span class="sized-text-1"><?php echo $OftenAry[$i][4];?></span></label>&nbsp;
                        <?php }?>
                    </td>
				</tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">家族病史：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($FamAry);$i++){?>
                        	<label><input type="checkbox" id="FamilyHistory" name="FamilyHistory" value="<?php echo $FamAry[$i][0];?>"><span class="sized-text-1"><?php echo $FamAry[$i][4];?></span></label>&nbsp;
                            <?php if($i%5==0 && $i!=0){?>
                            	<br>
                            <?php }?>
                        <?php }?>
                        <input type="text" id="FamilyHistoryInput" name="FamilyHistoryInput" class="miniWidth sized-text-normal" value="">
                    </td>
				</tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">藥物過敏：</span></th>
                    <td valign="middle">
                        <label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="1"><span class="sized-text-1">不清楚</span></label>&nbsp;
                        <label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="2"><span class="sized-text-1">無</span></label>&nbsp;
                        <label><input type="checkbox" id="DrugallergyStatus" name="DrugallergyStatus" value="3"><span class="sized-text-1">有</span></label>&nbsp;
                        <input type="text" id="Drugallergy" name="Drugallergy" class="miniWidth sized-text-normal" placeholder="過敏藥物說明" value="">
                    </td>
				</tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">備註：</span></th>
                    <td valign="middle">
						<textarea id="Remark" name="Remark" class="fullWidth hor-box-text-normal"></textarea>
                    </td>
				</tr>
			</table>
</form>            
            <div class="spacingBlock"></div>
            
            <div><!-- 若無須使用可整段移除 -->
            	<ul class="finalControl">
                	<li><button type="button" onClick="ChkForm(DataForm)" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" onClick="history.go(-1)"class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
        </div>
        <!-- 資料新增功能 end -->
        
        <!-- 功能內容 end -->
    </div>
</body>
</html>