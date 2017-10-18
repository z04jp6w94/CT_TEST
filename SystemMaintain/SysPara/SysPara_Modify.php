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
	$USER_NM = $_SESSION["M_USER_NM"];
	$USER_ROLE = $_SESSION["M_USER_ROLE"];													//程式	 ID (GPID)
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
	$Msg = trim(GetxRequest("Msg"));
	$Comment = trim(GetxRequest("Comment"));
	$Level = trim(GetxRequest("Level"));
//項目 OptionalItem
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'SystemParameter' ";	
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);	
	if($Comment == ''){
		$Comment = $OptionAry[0][0]; 
	}	
//等級
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '000000000000226' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$LAry = $MySql -> db_array($Sql,6);	
	if($Level == ''){
		$Level = $LAry[0][0]; 
	}		
//教案級別分支
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '000000000000226' ";	
	$Sql .= " and DeleteStatus = 'N'";
	$LevelRrn = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$LevelAry = $MySql -> db_array($Sql,5);	
if($Comment != '000000000000229'){	
//代碼資料
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '".$Comment."' ";
	$Sql .= " and DeleteStatus = 'N'";
	$Sys = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$SysAry = $MySql -> db_array($Sql,5);	
}else{
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '".$Comment."' ";
	$Sql .= " and LevelKey = '".$Level."' ";
	$Sql .= " and DeleteStatus = 'N'";
	$Sys = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$SysAry = $MySql -> db_array($Sql,5);
	
}
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
<title>擴思訊 Cross Think 後台 系統參數</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
	<script type="text/javascript">
	var C = '<?php echo $Comment?>';
		$(document).ready(function(){
			
			$(".addItem").click(function() { 		
				if($("#Name").val() != ''){
					$.ajax({
						url: "SysPara_AddNew.php",
						data: {Comment : C, Description: $("#Name").val(), Level: $("#Level").val()},
						type:"POST",
						success: function(xml){	
							if($('resu', xml).text() == '1'){
								$("#Description").append($('<option></option>').val($("#Name").val()).text($("#Name").val()));
								$("#Name").val('');
							}else{
								alert($('rtmsg', xml).text());
							}					
						},			
						 error:function(xhr, ajaxOptions, thrownError){ 
							alert(xhr.status); 
							alert(thrownError); 
						}
					});
			
				}else{
					alert('請輸入代碼名稱');
				}
			});
			
			$(".removeItem").click(function() { 
				//$('#Description option:selected').length;	//選取幾個
				//alert($('#now_count').val());
				
				if($('#Description option:selected').length != 0){	
					
					var arr = new Array(); 
					var s = document.forms[0].Description; 
					for(var i=0;i<s.options.length;i++) 
						if(s.options[i].selected) 
							arr.push(s.options[i].value);
					
					if(confirm("確定刪除?")){
						$.ajax({
							url: "SysPara_Delete.php",
							data: {Key : arr.join()},
							type:"POST",
							success: function(msg){						
							},			
							 error:function(xhr, ajaxOptions, thrownError){ 
								alert(xhr.status); 
								alert(thrownError); 
							}
						});
						$('#Description option:selected').remove();
					}	
											
						//170410$('#Description option:selected').remove();
				}else{
					alert('請選取要刪除代碼');
				}
			});
				
		});

		function ChkForm(_Form){
			
			$('#Description option').attr('selected','selected'); 			//轉selected防止沒存取

			_Form.submit();
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
                <li class="title"><span class="hor-box-text">系統參數</span></li>
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<div class="systemSetting"> 
        	<table cellpadding="0" cellspacing="0" class="leftHeader">
            <form name="DataForm" id="DataForm" method="post" action="ChangePWD_Update.php" autocomplete="off">
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">項目</span></th>
                    <td valign="middle">
                    	<div class="itemEdit">
                            <p>
                                <select id="Comment" name="Comment" onchange="window.location.href='SysPara_Modify.php?Comment=' + this.value;" class="fullWidth sized-text-1">
                                <?php for($i=0;$i<count($OptionAry);$i++){?>
                                    <option value="<?php echo $OptionAry[$i][0]?>" <?php if($Comment == $OptionAry[$i][0]){?>selected<?php }?>><?php echo $OptionAry[$i][4];?></option>
                                <?php }?>
                                </select>
                            </p>
                            <?php if($Comment == '000000000000229'){?>
                            <br>
                            <p>
                                <select id="Level" name="Level" onchange="window.location.href='SysPara_Modify.php?Comment=<?php echo $Comment?>&Level=' + this.value;" class="fullWidth sized-text-1">
                                <?php for($i=0;$i<count($LevelAry);$i++){?>
                                    <option value="<?php echo $LevelAry[$i][0]?>" <?php if($Level == $LevelAry[$i][0]){?>selected<?php }?>><?php echo $LevelAry[$i][2];?></option>
                                <?php }?>
                                </select>
                            </p>
                            <br>
                            <?php }?>
                            <br>
                            <ul class="btns">
                                <li class="search"><input id="Name" name="Name" type="text" class="sized-text-normal" value=""></li>
                                <li><button type="button" class="addItem"><span class="hiddenItem">加入</span></button></li>
                                <li><button type="button" class="removeItem"><span class="hiddenItem">刪除</span></button></li>
                                
                            </ul>
                            <div class="clearFloat"></div>
                            <br>
                            <p>
                                <select id="Description" name="Description" multiple class="fullWidth listMode sized-text-1">
                                <?php for($i=0;$i<count($SysAry);$i++){?>
                                	<option value="<?php echo $SysAry[$i][0]?>" selected><?php echo $SysAry[$i][2]?></option>
                                <?php }?>
                                </select>
                            </p>
                        </div>
                    </td>
                </tr>
           	</form>   
            </table> 
            <div class="spacingBlock"></div>
            
        </div>
     </div>
</div>
</body>
</html>       