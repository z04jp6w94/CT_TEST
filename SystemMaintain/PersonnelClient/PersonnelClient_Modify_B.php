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
	$USER_ID = $_SESSION["M_USER_ID"];														//管理員 ID
//	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
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
//代碼 訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'Counties' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
//歷史紀錄
	$Sql = " select * from License";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and PersonnelId = '".$DataKey."' ";	
	
	
	
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
<title>擴思訊 Cross Think 後台 使用者管理-客戶 編輯使用者</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		// numericOnly config

		// datepicker config
			$('#StartDate, #EndDate').datepicker({
				dateFormat:'yymmdd',
				changeYear: true,
				changeMonth: true,
				onSelect: function(selectedDate){
					switch(this.id){
						case 'StartDate':
							var dateMin = $('#StartDate').datepicker('getDate');
							var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1);
							$('#EndDate').datepicker('option', 'minDate', rMin);
							break;
					}
				}
			});
			
			
		});

		function ChkForm(){
			if($("#btn_child").hasClass("current")){
				_Form = DataForm;	
			}
			if($("#btn_teacher").hasClass("current")){
				_Form = DataForm2;	
			}
//			alert($("#btn_child").hasClass("current"));
//			alert($("#btn_teacher").hasClass("current"));
			
			if(IsEmpty(_Form.Account,'帳號')){
			}else{
				_Form.submit();
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
                <li class="title"><span class="hor-box-text">使用者管理-客戶</span></li>
                <li class="current"><span class="hor-box-text">編輯使用者</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
        <!-- 使用者新增功能 begin -->
		<script language="javascript" type="text/javascript">
        	/**
			頁籤切換功能
			*/
			function switchTabContent(_btn){
				var currentBtn = _btn;
				var currentCtx = document.getElementById(_btn.getAttribute('current-ctx'));
				var hideBtn = document.getElementById(_btn.getAttribute('hide-btn'));
				var hideCtx = document.getElementById(_btn.getAttribute('hide-ctx'));
				var tmp = currentBtn.getAttribute('class').split(' ');
				currentBtn.setAttribute('class' , tmp[0] + ' current');
				currentCtx.setAttribute('style' , '');
				tmp = hideBtn.getAttribute('class').split(' ');
				hideBtn.setAttribute('class' , tmp[0]);
				hideCtx.setAttribute('style' , 'display:none;');
			}
        </script>
        
        <div class="addSystemUser">
        	<ul class="switcher">
            	<li><label><input type="radio" id="btn_child" name="switchForm" value="1" disabled <?php if($rs["IdentityTypeId"]=='000000000000001'){ echo 'checked';?><?php }?> class="switch <?php if($rs["IdentityTypeId"]=='000000000000002'){ echo 'current';?><?php }?>" onclick="switchTabContent(this);" current-ctx="ctx_child" hide-btn="btn_teacher" hide-ctx="ctx_teacher" checked><span class="sized-text-1">幼兒園</span></label></li>
                <li><label><input type="radio" id="btn_teacher" name="switchForm" value="2" disabled <?php if($rs["IdentityTypeId"]=='000000000000002'){ echo 'checked';?><?php }?> class="switch <?php if($rs["IdentityTypeId"]=='000000000000003'){ echo 'current';?><?php }?>" onclick="switchTabContent(this);" current-ctx="ctx_teacher" hide-btn="btn_child" hide-ctx="ctx_child"><span class="sized-text-1">老師／家長</span></label></li>
            </ul>
            <div class="clearFloat"></div>
            
            <div class="spacingBlock"></div>
            <h2><span class="sized-text-1">客戶資料</span></h2>
            <div class="spacingBlock"></div>
            <?php if($rs["IdentityTypeId"]=='000000000000002'){?>
        	<table cellpadding="0" cellspacing="0" class="leftHeader" id="ctx_child">
            <form name="DataForm" id="DataForm" method="post" action="<?php echo $gMainFile?>_Update.php" autocomplete="off">
				<input name="Id" type="hidden" id="Id" size="60" maxlength="20" value="<?php echo $DataKey?>" />
            	<!-- 幼兒園 -->
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號類型：</span></th>
                    <td valign="middle">
                    	<select id="AccountTypeId" name="AccountTypeId" class="halfWidth sized-text-1">
                        	<option value="000000000000025" <?php if($rs["AccountTypeId"]=='000000000000025'){ echo 'selected';?><?php }?>>正式帳號</option>
                            <option value="000000000000026" <?php if($rs["AccountTypeId"]=='000000000000026'){ echo 'selected';?><?php }?>>測試帳號</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">幼兒園名稱＊：</span></th>
                    <td valign="middle"><input type="text" id="" name="" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs[""];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" id="Account" name="Account" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Account"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" id="PWD" name="PWD" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["PWD"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">Email＊：</span></th>
                    <td valign="middle"><input type="text" id="Email" name="Email" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Email"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">地區（國家／縣市）＊：</span></th>
                    <td valign="middle">
                    	<select id="Country" name="Country" class="smallWidth sized-text-1">
                        	<option value="000000000000054" <?php if($rs["Country"]=='000000000000054'){ echo 'selected';?><?php }?>>台灣</option>
                            <option value="000000000000055" <?php if($rs["Country"]=='000000000000055'){ echo 'selected';?><?php }?>>中國</option>
                        </select>
                        <select id="Counties" name="Counties" class="smallWidth sized-text-1">
                        	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<option value="<?php echo $OptionAry[$i][0]?>" <?php if($rs["Counties"]==$OptionAry[$i][0]){ echo 'selected';?><?php }?>><?php echo $OptionAry[$i][4]?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">所屬幼園連鎖名稱：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="NM" name="NM" value="N"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio" id="NM" name="NM" value="Y"><span class="sized-text-1">有</span></label>
                    	<input type="text" id="KindergartenName" name="KindergartenName" class="smallWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["KindergartenName"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">教具（無／有）＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="HaveAid" name="HaveAid" value="N" <?php if($rs["HaveAid"]=='N'){ echo 'checked';?><?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio" id="HaveAid" name="HaveAid" value="Y" <?php if($rs["HaveAid"]=='Y'){ echo 'checked';?><?php }?>><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 begin -->
                <tr>
                	<th><span class="needMark hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle">
                    	<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">&nbsp;<span class="sized-text-1">套</span>
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具來源：</span>&nbsp;
                        <label><input type="checkbox" id="" name=""><span class="sized-text-1">自購</span></label>&nbsp;
                        <label><input type="checkbox" id="" name=""><span class="sized-text-1">他人送</span></label>&nbsp;
                        <label><input type="checkbox" id="" name=""><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" id="TeachingAidSource" name="TeachingAidSource" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["TeachingAidSource"];?>">
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具名稱：</span>&nbsp;
                        <input type="text" id="TeachingAidName" name="TeachingAidName" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["TeachingAidName"];?>">
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 end -->
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">學生數量：</span></th>
                    <td valign="middle">
                    	<span class="sized-text-1">本園</span>&nbsp;<input type="text" id="StudentNumber" name="StudentNumber" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["StudentNumber"];?>">
                        &nbsp;
                        <span class="sized-text-1">集團學生總數</span>&nbsp;<input type="text" id="GroupNumber" name="GroupNumber" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["GroupNumber"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人：</span></th>
                    <td valign="middle"><input type="text" id="Contact" name="Contact" class="halfWidth sized-text-normal" value="<?php echo $rs["Contact"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡電話：</span></th>
                    <td valign="middle"><input type="text" id="Tel1" name="Tel1" class="halfWidth sized-text-normal" value="<?php echo $rs["Tel1"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡傳真：</span></th>
                    <td valign="middle"><input type="text" id="Tel2" name="Tel2" class="halfWidth sized-text-normal" value="<?php echo $rs["Tel2"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人手機：</span></th>
                    <td valign="middle"><input type="text" id="Mobile" name="Mobile" class="halfWidth sized-text-normal" value="<?php echo $rs["Mobile"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 wechat ID：</span></th>
                    <td valign="middle"><input type="text" id="WechatId" name="WechatId" class="halfWidth sized-text-normal" value="<?php echo $rs["WechatId"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 Email：</span></th>
                    <td valign="middle"><input type="text" id="ContactPersonEmail" name="ContactPersonEmail" class="halfWidth sized-text-normal" value="<?php echo $rs["ContactPersonEmail"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">資訊來源：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000027'){ echo 'checked';?><?php }?> value="000000000000027"><span class="sized-text-1">書</span></label>&nbsp;
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000028'){ echo 'checked';?><?php }?> value="000000000000028"><span class="sized-text-1">電子媒體</span></label>&nbsp;
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000029'){ echo 'checked';?><?php }?> value="000000000000029"><span class="sized-text-1">朋友介紹</span></label><br>
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000030'){ echo 'checked';?><?php }?> value="000000000000030"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" id="SourceOther" name="SourceOther" class="smallWidth sized-text-normal" value="<?php echo $rs["SourceOther"];?>">
                    </td>
                </tr>    
            </form>
            </table>
            <?php }else{?>
            <table cellpadding="0" cellspacing="0" class="leftHeader" id="ctx_teacher">
            <form name="DataForm2" id="DataForm2" method="post" action="<?php echo $gMainFile?>_Update.php" autocomplete="off">
				<input name="Id" type="hidden" id="Id" size="60" maxlength="20" value="<?php echo $DataKey?>" />
            	<!-- 老師／家長 -->
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號類型：</span></th>
                    <td valign="middle">
                    	<select id="AccountTypeId" name="AccountTypeId" class="halfWidth sized-text-1">
                        	<option value="000000000000025" <?php if($rs["Country"]=='000000000000025'){ echo 'selected';?><?php }?>>正式帳號</option>
                            <option value="000000000000026" <?php if($rs["Country"]=='000000000000026'){ echo 'selected';?><?php }?>>測試帳號</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">名稱／姓名、暱稱＊：</span></th>
                    <td valign="middle"><input type="text" id="" name="" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" id="Account" name="Account" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Account"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" id="PWD" name="PWD" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["PWD"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">Email＊：</span></th>
                    <td valign="middle"><input type="text" id="Email" name="Email" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Email"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">地區（國家／縣市）＊：</span></th>
                    <td valign="middle">
                    	<select id="Country" name="Country" class="smallWidth sized-text-1">
                        	<option value="000000000000054" <?php if($rs["Country"]=='000000000000054'){ echo 'selected';?><?php }?>>台灣</option>
                            <option value="000000000000055" <?php if($rs["Country"]=='000000000000055'){ echo 'selected';?><?php }?>>中國</option>
                        </select>
                        <select id="Counties" name="Counties" class="smallWidth sized-text-1">
                        	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<option value="<?php echo $OptionAry[$i][0]?>"><?php echo $OptionAry[$i][4]?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">從事幼教行業＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="IsECE" name="IsECE" value="N" <?php if($rs["IsECE"]=='N'){ echo 'selected';?><?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio" id="IsECE" name="IsECE" value="Y" <?php if($rs["IsECE"]=='Y'){ echo 'selected';?><?php }?>><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">教具（無／有）＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="HaveAid" name="HaveAid" value="N" <?php if($rs["HaveAid"]=='N'){ echo 'checked';?><?php }?>><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio" id="HaveAid" name="HaveAid" value="Y" <?php if($rs["HaveAid"]=='Y'){ echo 'checked';?><?php }?>><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 begin -->
                <tr>
                	<th><span class="needMark hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle">
                    	<input type="text" id="" name="" class="miniWidth sized-text-normal" placeholder="必填" value="">&nbsp;<span class="sized-text-1">套</span>
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具來源：</span>&nbsp;
                        <label><input type="checkbox" id="TeachingAidSource" name="TeachingAidSource"><span class="sized-text-1">自購</span></label>&nbsp;
                        <label><input type="checkbox" id="TeachingAidSource" name="TeachingAidSource"><span class="sized-text-1">他人送</span></label>&nbsp;
                        <label><input type="checkbox" id="TeachingAidSource" name="TeachingAidSource"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" id="TeachingAidSource" name="TeachingAidSource" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["TeachingAidSource"];?>">
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具名稱：</span>&nbsp;
                        <input type="text" id="TeachingAidName" name="TeachingAidName" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["TeachingAidName"];?>">
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 end -->
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">學生數量：</span></th>
                    <td valign="middle">
                    	<span class="sized-text-1">本園</span>&nbsp;<input type="text" id="StudentNumber" name="StudentNumber" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["StudentNumber"];?>">
                        &nbsp;
                        <span class="sized-text-1">集團學生總數</span>&nbsp;<input type="text" id="GroupNumber" name="GroupNumber" class="miniWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["GroupNumber"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人：</span></th>
                    <td valign="middle"><input type="text" id="Contact" name="Contact" class="halfWidth sized-text-normal" value="<?php echo $rs["Contact"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡電話：</span></th>
                    <td valign="middle"><input type="text" id="Tel1" name="Tel1" class="halfWidth sized-text-normal" value="<?php echo $rs["Tel1"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡傳真：</span></th>
                    <td valign="middle"><input type="text" id="Tel2" name="Tel2" class="halfWidth sized-text-normal" value="<?php echo $rs["Tel2"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人手機：</span></th>
                    <td valign="middle"><input type="text" id="Mobile" name="Mobile" class="halfWidth sized-text-normal" value="<?php echo $rs["Mobile"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 wechat ID：</span></th>
                    <td valign="middle"><input type="text" id="WechatId" name="WechatId" class="halfWidth sized-text-normal" value="<?php echo $rs["WechatId"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 Email：</span></th>
                    <td valign="middle"><input type="text" id="ContactPersonEmail" name="ContactPersonEmail" class="halfWidth sized-text-normal" value="<?php echo $rs["ContactPersonEmail"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">資訊來源：</span></th>
                    <td valign="middle">
                    	<label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000027'){ echo 'checked';?><?php }?> value="000000000000027"><span class="sized-text-1">書</span></label>&nbsp;
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000028'){ echo 'checked';?><?php }?> value="000000000000028"><span class="sized-text-1">電子媒體</span></label>&nbsp;
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000029'){ echo 'checked';?><?php }?> value="000000000000029"><span class="sized-text-1">朋友介紹</span></label><br>
                        <label><input type="radio" id="SourceOfInfoId" name="SourceOfInfoId" <?php if($rs["SourceOfInfoId"]=='000000000000030'){ echo 'checked';?><?php }?> value="000000000000030"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" id="SourceOther" name="SourceOther" class="smallWidth sized-text-normal" value="<?php echo $rs["SourceOther"];?>">
                    </td>
                </tr>
            </form>    
            </table>
            <?php }?>
            <div class="spacingBlock"></div>
            
            <div class="authInfo" id="ctx_extendAuth" style="display:none;">
                <!-- 資料檢視表格 begin -->
                <div class="dataIndexList themeSet1">
                	<p><span class="hor-box-text-normal">歷史紀錄</span></p>
                    <table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
                        <tr>
                            <th>&nbsp;</th>
                            <th class="topHeader"><span class="hor-box-text-normal">形態</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">數量／內容</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">授權期間</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立人員</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立時間</span></th>
                        </tr>
                        <tr class="odd">
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">教師帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>
                        <tr class="even">
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">寶貝帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>
                    </table>
                </div>
                <!-- 資料檢視表格 end -->
                
                <div class="spacingBlock"></div>
                <p><span class="hor-box-text-normal">新增</span></p>
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">教師帳號數＊：</span></th>
                        <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">寶貝帳號數＊：</span></th>
                        <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權期間＊：</span></th>
                        <td valign="middle"><input type="text" class="smallWidth sized-text-normal" value="">&nbsp;到&nbsp;<input type="text" class="smallWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權類別＊：</span></th>
                        <td valign="middle">
                        	<br>
                            <table cellpadding="0" cellspacing="0" class="itemMover">
                                <tr>
                                    <td>
                                    	<div class="box">
                                            <p class="title"><span class="sized-text-1">Full List</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
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
	                                        <p class="title"><span class="sized-text-1">My Items</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            <div class="spacingBlock"></div>
            <h2><span class="sized-text-1">授權管理</span></h2>
            <div class="spacingBlock"></div>
            
            <ul class="switcher">
            	<li><button type="button" id="btn_basicAuth" class="optionDoBtn current" onclick="switchTabContent(this);" current-ctx="ctx_basicAuth" hide-btn="btn_extendAuth" hide-ctx="ctx_extendAuth"><span class="sized-text-1">基礎帳號授權</span></button></li>
            	<li><button type="button" id="btn_extendAuth" class="optionDoBtn" onclick="switchTabContent(this);" current-ctx="ctx_extendAuth" hide-btn="btn_basicAuth" hide-ctx="ctx_basicAuth"><span class="sized-text-1">選購授權</span></button></li>
            </ul>
            <div class="clearFloat"></div>
            <div class="spacingBlock"></div>
            
            <div class="authInfo" id="ctx_basicAuth">
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">教師帳號數＊：</span></th>
                        <td valign="middle">
                        	<input type="text" id="TeacherNum" name="TeacherNum" class="halfWidth sized-text-normal" value="">
						</td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">寶貝帳號數＊：</span></th>
                        <td valign="middle">
                        	<input type="text" id="StudentNum" name="StudentNum" class="halfWidth sized-text-normal" value="">
						</td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權期間＊：</span></th>
                        <td valign="middle"><input type="text" id="StartDate2" name="StartDate2" readonly class="smallWidth sized-text-normal" value="">&nbsp;到&nbsp;<input type="text" id="EndDate2" name="EndDate2" readonly class="smallWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權類別＊：</span></th>
                        <td valign="middle">
                        	<br>
                            <table cellpadding="0" cellspacing="0" class="itemMover">
                                <tr>
                                    <td>
                                    	<div class="box">
                                            <p class="title"><span class="sized-text-1">Full List</span></p>
                                            <select id="List1" name="List1" multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td valign="middle">
                                    	<div class="btns">
                                        	<button type="button" id="Right1" class="toRight"><span class="hiddenItem">向右移</span></button>
                                            <br>
                                            <br>
                                            <button type="button" id="Left1" class="toLeft"><span class="hiddenItem">向左移</span></button>
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="box">
	                                        <p class="title"><span class="sized-text-1">My Items</span></p>
                                            <select id="List2" name="List2" multiple>
                                                <option value="AAA">AAA</option>
                                                <option value="BBB">BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
                        
            <div class="authInfo" id="ctx_extendAuth" style="display:none;">
                <!-- 資料檢視表格 begin -->
                <div class="dataIndexList themeSet1">
                	<p><span class="hor-box-text-normal">歷史紀錄</span></p>
                    <table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
                        <tr>
                            <th>&nbsp;</th>
                            <th class="topHeader"><span class="hor-box-text-normal">形態</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">數量／內容</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">授權期間</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立人員</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立時間</span></th>
                        </tr>
                        <?php
							$lineCount = 0;
							while ($initAry = $MySql -> db_fetch_array($initRun)){
							$lineCount ++;
						?>
						<tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">教師帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>
                        <?php }?>
                        <!--<tr class="even">
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">寶貝帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>-->
                    </table>
                </div>
                <!-- 資料檢視表格 end -->
                
                <div class="spacingBlock"></div>
                <p><span class="hor-box-text-normal">新增</span></p>
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">教師帳號數＊：</span></th>
                        <td valign="middle"><input type="text" id="TeacherNum2" name="TeacherNum2" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">寶貝帳號數＊：</span></th>
                        <td valign="middle"><input type="text" id="StudentNum2" name="StudentNum2" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權期間＊：</span></th>
                        <td valign="middle"><input type="text" id="StartDate4" name="StartDate4" class="smallWidth sized-text-normal" value="">&nbsp;到&nbsp;<input type="text" id="EndDate4" name="EndDate4" class="smallWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權類別＊：</span></th>
                        <td valign="middle">
                        	<br>
                            <table cellpadding="0" cellspacing="0" class="itemMover">
                                <tr>
                                    <td>
                                    	<div class="box">
                                            <p class="title"><span class="sized-text-1">Full List</span></p>
                                            <select id="List3" name="List3" multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td valign="middle">
                                    	<div class="btns">
                                        	<button type="button" id="Right2" class="toRight"><span class="hiddenItem">向右移</span></button>
                                            <br>
                                            <br>
                                            <button type="button" id="Left2" class="toLeft"><span class="hiddenItem">向左移</span></button>
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="box">
	                                        <p class="title"><span class="sized-text-1">My Items</span></p>
                                            <select id="List4" name="List4" multiple>
                                                <option value="A1">AAA</option>
                                                <option value="B1">BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>    
                </table>
            </div>
            
            <div class="spacingBlock"></div>
            
            <div><!-- 若無須使用可整段移除 -->
            	<ul class="finalControl">
                	<li><button type="button" onClick="ChkForm();" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" onClick="history.back(-1)" class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
        </div>

        <!-- 使用者新增功能 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
