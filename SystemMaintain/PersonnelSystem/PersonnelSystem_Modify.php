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
//角色
	$Sql = " select * from Role ";
	$Sql .= " where 1 = 1 ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RoleAry = $MySql -> db_array($Sql,4);
		
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
			$('#EricNewsM_StDate, #EricNewsM_EdDate').datepicker({
				dateFormat:'yymmdd',
				changeYear: true,
				changeMonth: true,
				onSelect: function(selectedDate){
					switch(this.id){
						case 'EricNewsM_StDate':
							var dateMin = $('#EricNewsM_StDate').datepicker('getDate');
							var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 1);
							$('#EricNewsM_EdDate').datepicker('option', 'minDate', rMin);
							break;
					}
				}
			});
			
			
		});

		function ChkForm(_Form){
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
                <li class="title"><span class="hor-box-text">使用者管理-劍聲</span></li>
                <li class="current"><span class="hor-box-text">編輯使用者</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
        <div class="addSystemUser">
        	<table cellpadding="0" cellspacing="0" class="leftHeader">
            <form name="DataForm" id="DataForm" method="post" action="<?php echo $gMainFile?>_Update.php" autocomplete="off">
				<input name="Id" type="hidden" id="Id" size="60" maxlength="20" value="<?php echo $DataKey?>" />
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">姓名＊：</span></th>
                    <td valign="middle"><input type="text" id="FullName" name="FullName" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["FullName"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class=needMark "hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" id="Account" name="Account" class="halfWidth sized-text-normal" placeholder="Email" value="<?php echo $rs["Account"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" id="PWD" name="PWD" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["PWD"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話1：</span></th>
                    <td valign="middle"><input type="text" id="Tel1" name="Tel1" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Tel1"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">連絡電話2：</span></th>
                    <td valign="middle"><input type="text" id="Tel2" name="Tel2" class="halfWidth sized-text-normal" placeholder="必填" value="<?php echo $rs["Tel2"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">角色：</span></th>
                    <td valign="middle">
                    	<select id="RoleId" name="RoleId" class="halfWidth sized-text-1">
                        <?php for($i=0;$i<count($RoleAry);$i++){?>
                        	<option value="<?php echo $RoleAry[$i][0];?>" <?php if($RoleAry[$i][0] == $rs["RoleId"]){?>selected<?php }?>><?php echo $RoleAry[$i][1];?></option>                       
                        <?php }?>
                        </select>
                    </td>
                </tr>
            </form>    
            </table>
            
            <div class="spacingBlock"></div>
            
            <div>
            	<ul class="finalControl">
                	<li><button type="button" onClick="ChkForm(DataForm)"class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" onClick="history.go(-1)"class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
        </div>
            
    </div>
</div>
</body>
</html>
