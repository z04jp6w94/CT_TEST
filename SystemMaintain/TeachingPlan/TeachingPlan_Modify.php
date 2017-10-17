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
	$DataKey = trim(GetxRequest("DataKey"));
//資料
	$Sql = " Select * From TeachingPlan where Id = $DataKey ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$rs = $MySql -> db_fetch_array($initRun);
//代碼 訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'TeachingObjectives' ";
	$Sql .= " and Language = 'zh-tw' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
//分解動作
	$Sql = " select Id, TeachingPlanId, Description, ImageURL from TeachingPlanAction ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeachingPlanId = '" .$DataKey. "'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$PlanAction = $MySql -> db_array($Sql,4);	
		
//深入解說
	$Sql = " select Id, TeachingPlanId, Description from TeachingComment ";
	$Sql .= " where 1 =1 ";
	$Sql .= " and TeachingPlanId = '" .$DataKey. "'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$CommentAry = $MySql -> db_array($Sql,3);	
//精益求精
	$Sql = " select Id, TeachingPlanId, Description from TeachingAdvanced ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeachingPlanId = '" .$DataKey. "'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$AdvancedAry = $MySql -> db_array($Sql,3);	
//訓練目標
	$Sql = " select OptionalId from TeachingObjectives ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeachingPlanId = '" .$DataKey. "'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ObjectivesAry = $MySql -> db_array($Sql,1);	
//教案類別 000000000000225
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '000000000000225' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$SysAry = $MySql -> db_array($Sql,6);
//教案級別 000000000000226
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '000000000000226' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$SysAry2 = $MySql -> db_array($Sql,6);
//次級別 000000000000229
	$Sql = " select * from SystemParameter ";
	$Sql .= " where 1 = 1 ";	
	$Sql .= " and LevelKey = '".$rs["LevelId"]."' ";
	$Sql .= " and Comment = '000000000000229' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$SysAry3 = $MySql -> db_array($Sql,6);
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
<title>擴思訊 Cross Think 後台 教案管理 編輯教案</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="/Js/ComFun.js"></script>
    <script type="text/javascript" src="/Js/jquery.ChiliUpload.js"></script>
    <script type="text/javascript" src="/Js/jcomFunc.js"></script>
    
	<script type="text/javascript">
		var now_count = <?php echo count($PlanAction)?>;
		var now_count2 = <?php echo count($CommentAry)?>;
		var now_count3 = <?php echo count($AdvancedAry)?>;
		$(document).ready(function(){
			
			$("#LevelId").change(function(){				
				$("#SubLevelId option").remove();				
				getRelativeOption($(this), 'SubLevelId', '../../Excute/GetLevel.php',{T:$(this).val()},true,'');
			});
			
			$('#Btn_ADD').click(function(){
				now_count = now_count + 1
				$('#now_count').val(now_count);
				var Html = '';

				Html += '<li class="Description' + now_count + '">';
                Html += '<textarea id="Description' + now_count + '" name="Description[]" class="fullWidth sized-text-1"></textarea>';
                Html += '<div class="tools withUpload">';
				Html += '<input type="file" id="imgupload' + now_count + '" name="imgupload[]" value="" style="display:none"/>';
                Html += '<a href="javascript:;" id="OpenImgUpload' + now_count + '" class="OpenImgUpload" onclick="Upload(this.id)"><img id="blah' + now_count + '" src="#" style="width:122px; height:81px; margin-left:-21.5px;"></a>';
                Html += '<button type="button" onClick="moveUp3(this)" class="moveUp"><span class="hiddenItem">上移</span></button>';
                Html += '<button type="button" onClick="moveDown(this)" class="moveDown"><span class="hiddenItem">上移</span></button>';
				Html += '<button type="button" onClick="DelDescriptionLi(' + now_count + ')" class="removeRow"><span class="hiddenItem">移除</span></button>';
                Html += '</div>';
                Html += '<div class="clearFloat"></div>';
                Html += '</li>';
					
				$(".upload").append(Html);
			});
			
			$('#Btn_ADD2').click(function(){
				now_count2 = now_count2 + 1
				$('#now_count2').val(now_count2);
				var Html = '';
				
				Html += '<li class="talk' + now_count2 + '">';
                Html += '<textarea id="talk' + now_count2 + '" name="talk[]" class="fullWidth sized-text-1"></textarea>';
                Html += '<div class="tools">';
                Html += '<button type="button" onClick="moveUp(this)" class="moveUp"><span class="hiddenItem">上移</span></button>';
                Html += '<button type="button" onClick="moveDown(this)" class="moveDown"><span class="hiddenItem">上移</span></button>';
				Html += '<button type="button" onClick="DeltalkLi(' + now_count2 + ')" class="removeRow"><span class="hiddenItem">移除</span></button>';
                Html += '</div>';
                Html += '<div class="clearFloat"></div>';
                Html += '</li>';
			
				$(".talk").append(Html);
			});
			
			$('#Btn_ADD3').click(function(){
				now_count3 = now_count3 + 1
				$('#now_count3').val(now_count3);
				var Html = '';
							
				Html += '<li class="hard' + now_count3 + '">';
                Html += '<textarea id="hard' + now_count3 + '" name="hard[]" class="fullWidth sized-text-1"></textarea>';
                Html += '<div class="tools">';
                Html += '<button type="button" onClick="moveUp2(this)" class="moveUp"><span class="hiddenItem">上移</span></button>';
                Html += '<button type="button" onClick="moveDown2(this)" class="moveDown"><span class="hiddenItem">上移</span></button>';
				Html += '<button type="button" onClick="DelhardLi(' + now_count3 + ')" class="removeRow"><span class="hiddenItem">移除</span></button>';
                Html += '</div>';
                Html += '<div class="clearFloat"></div>';
                Html += '</li>';
			
				$(".hard").append(Html);
			});
		});
		
		function DelDescriptionLi(n){
			$('.Description'+n).remove();
		}
		
		function DeltalkLi(n){
			$('.talk'+n).remove();
		}

		function DelhardLi(n){
			$('.hard'+n).remove();
		}

		function ChkForm(_Form){
			
			get_Checked_Checkbox_By_Name('Teaching');
			
			if(IsEmpty(_Form.Name,'教案名稱')){
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
		
			
		function chk(input)
			/* another $("input[name=DrugallergyStatus]").click( function () {
				var Selected = $(this).val();
					$("input[name=DrugallergyStatus]").each(function(i){
					if($(this).val() == Selected) $(this).prop("checked", true);
					else $(this).prop("checked", false);
				});
			});*/
		{
			for(var i=0;i<document.DataForm.Teaching.length;i++)
			{
			  document.DataForm.Teaching[i].checked = false;
			}
			
			input.checked = true;
			return true;
		}
		
		function Upload(id){
			
			var a = '';
			a = id.replace(/OpenImgUpload/, ""); 
			
			$('#imgupload'+a).trigger('click'); 
		
			$("#imgupload"+a).change(function(){
				
				if (this.files && this.files[0]) {
					var reader = new FileReader();
					
					reader.onload = function (e) {
						$('#blah'+a).attr('src', e.target.result);
					}
					
					reader.readAsDataURL(this.files[0]);
				}
			});
						
		}
		
		//上移  
		function moveUp(obj) {  
			var $li = $(obj).parents("li");
			if ($li.index() != 0) {
				$li.fadeOut().fadeIn();
				$li.prev().before($li);	
			} 
		}  
		 
		//下移  
		function moveDown(obj) {  
			var len = $(".talk li").size();  
			var $li = $(obj).parents("li");
			if ($li.index() != len - 1) {
				$li.fadeOut().fadeIn();
				$li.next().after($li);
			}
		}
		
		//上移  
		function moveUp2(obj) {  
			var $li = $(obj).parents("li");
			if ($li.index() != 0) {
				$li.fadeOut().fadeIn();
				$li.prev().before($li);
				
			} 
		}  
		 
		//下移  
		function moveDown2(obj) {  
			var len = $(".hard li").size();  
			var $li = $(obj).parents("li");
			if ($li.index() != len - 1) {
				$li.fadeOut().fadeIn();
				$li.next().after($li);
			}
		}
		
		//上移  
		function moveUp3(obj) {  
			var $li = $(obj).parents("li");
			if ($li.index() != 0) {
				$li.fadeOut().fadeIn();
				$li.prev().before($li);
				
			} 
		}  
		 
		//下移  
		function moveDown3(obj) {  
			var len = $(".upload li").size();  
			var $li = $(obj).parents("li");
			if ($li.index() != len - 1) {
				$li.fadeOut().fadeIn();
				$li.next().after($li);
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
                <li class="title"><span class="hor-box-text">教案管理</span></li>
                <li class="current"><span class="hor-box-text">修改教案</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料新增功能 begin -->
<?php //include_once(root_path . "/SystemMaintain/CommonPage/doc_data_edit_list_themeset1.php");?>   
<form name="DataForm" id="DataForm" method="post" action="<?php echo $gMainFile?>_Update.php" autocomplete="off" enctype="multipart/form-data">
	<input name="Id" type="hidden" id="Id" size="60" maxlength="20" value="<?php echo $DataKey?>" />
    <input class="textInput" name="Teaching_TEXT" type="hidden" id="Teaching_TEXT" size="100" tabindex="1" value="" />    
    <?php for($i=0;$i<count($PlanAction);$i++){?>
    <input name="OldAction_Img<?php echo $i+1?>" type="hidden" id="OldAction_Img<?php echo $i+1?>" size="100" tabindex="1" value="<?php echo $PlanAction[$i][3];?>" />
    <?php }?>
	<div class="dataEditList">
        	<table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">教案類別：</span></th>
                    <td valign="middle">
                    	<select id="CategoryId" name="CategoryId" class="fullWidth sized-text-1">
                        <?php for($i=0;$i<count($SysAry);$i++){?>
                        	<option value="<?php echo $SysAry[$i][0];?>" <?php if($SysAry[$i][0] == $rs["CategoryId"]){?>selected<?php }?>><?php echo $SysAry[$i][2];?></option>
                        <?php }?>
                        </select>
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">教案級別：</span></th>
                    <td valign="middle">
                    	<select id="LevelId" name="LevelId" class="fullWidth sized-text-1">
                       	<?php for($i=0;$i<count($SysAry2);$i++){?>
                        	<option value="<?php echo $SysAry2[$i][0];?>" <?php if($SysAry2[$i][0] == $rs["LevelId"]){?>selected<?php }?>><?php echo $SysAry2[$i][2];?></option>      
                        <?php }?>
                        </select>
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle">
                    	<select id="SubLevelId" name="SubLevelId" class="fullWidth sized-text-1">
						<?php for($i=0;$i<count($SysAry3);$i++){?>
                        	<option value="<?php echo $SysAry3[$i][0]?>" <?php if($SysAry3[$i][0] == $rs["SubLevelId"]){?>selected<?php }?>><?php echo $SysAry3[$i][2];?></option>
                        <?php }?>
                        </select>
					</td>
                </tr>
            </table>
        <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">教案名稱：</span></th>
                    <td valign="middle"><input type="text" id="Name" name="Name" value="<?php echo $rs["Name"]?>" class="fullWidth sized-text-1"></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">分解動作：</span></th>
                    <td valign="middle">
                    	<ul class="stepper upload">
                        	<?php for($i=0;$i<count($PlanAction);$i++){?>
                            	<li class="Description<?php echo $i+1; ?>">
                                    <textarea class="fullWidth sized-text-1" id="Description<?php echo $i+1?>" name="Description[]"><?php echo $PlanAction[$i][2];?></textarea>
                                    <div class="tools withUpload">
                                    <input type="file" id="imgupload<?php echo $i+1;?>" name="imgupload[]" value="<?php echo $PlanAction[$i][3];?>" style="display:none"/> 
                                    <!--Modify-->
                                    <input type="hidden" id="FileNM<?php echo $i+1;?>" name="FileNM[]" value="<?php echo $PlanAction[$i][3];?>">
                                    <!--Modify-->
                                    <input type="hidden" id="img<?php echo $i+1;?>" name="img[]" value="<?php echo $PlanAction[$i][3];?>"/> 
                                        <a href="javascript:;" id="OpenImgUpload<?php echo $i+1?>" class="OpenImgUpload" onclick="Upload(this.id)"><img id="blah<?php echo $i+1?>" name="blah<?php echo $i+1?>" src="<?php if($PlanAction[$i][3] != ''){ echo TeachingPlan . $PlanAction[$i][3];}?>" style="width:122px; height:81px; margin-left:-21.5px;"></a><!-- 以這個A 的click 事件驅動檔案上傳功能，上傳後調整尺寸更新到期內的IMG -->
                                        <button type="button" onClick="moveUp3(this)" class="moveUp"><span class="hiddenItem">上移</span></button>
                                        <button type="button" onClick="moveDown3(this)" class="moveDown"><span class="hiddenItem">上移</span></button>
										<button type="button" onClick="DelDescriptionLi(<?php echo $i+1; ?>)" class="removeRow"><span class="hiddenItem">移除</span></button>
                                    </div>
                                    <div class="clearFloat"></div>
                                </li>
                            <?php }?>
                        </ul>
                    </td>
                </tr>
                <tr>
                	<th>&nbsp;</th>
                    <td valign="middle"><span class="hor-box-text-normal"><button type="button" name="Btn_ADD" id="Btn_ADD" class="optionMoreBtn"><span class="sized-text-1">＋新增分解動作</span></button></span></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">深入解說：</span></th>
                    <td valign="middle">
                    	<ul class="stepper talk">
                        	<?php for($i=0;$i<count($CommentAry);$i++){?>
                            	<li class="talk<?php echo $i+1;?>">
                            	<textarea id="talk<?php echo $i+1;?>" name="talk[]" class="fullWidth sized-text-1"><?php echo $CommentAry[$i][2];?></textarea>
                                <div class="tools">
                            		<button type="button" onClick="moveUp(this)" class="moveUp"><span class="hiddenItem">上移</span></button>
                            		<button type="button" onClick="moveDown(this)" class="moveDown"><span class="hiddenItem">上移</span></button>
									<button type="button" onClick="DeltalkLi(<?php echo $i+1; ?>)" class="removeRow"><span class="hiddenItem">移除</span></button>
                        		</div>
                                <div class="clearFloat"></div>
                            </li>
                            <?php }?>
                        </ul>
                    </td>
				</tr>
                <tr>
                	<th>&nbsp;</th>
                    <td valign="middle"><span class="hor-box-text-normal"><button type="button" name="Btn_ADD2" id="Btn_ADD2" class="optionMoreBtn"><span class="sized-text-1">＋新增深入解說</span></button></span></td>
                </tr>
			</table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">訓練目標：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<label><input type="checkbox" id="Teaching" name="Teaching" value="<?php echo $OptionAry[$i][0]?>" <?php if(in_array( $OptionAry[$i][0], $ObjectivesAry, true)){?>checked<?php }?>><span class="sized-text-1"><?php echo $OptionAry[$i][4]?></span></label>&nbsp;
                        <?php }?>
                    </td>
                </tr>
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">精益求精：</span></th>
                    <td valign="middle">
                    	<ul class="stepper hard">
                        	<?php for($i=0;$i<count($AdvancedAry);$i++){?>
                            	<li class="hard<?php echo $i+1;?>">
                                    <textarea id="hard<?php echo $i+1;?>" name="hard[]" class="fullWidth sized-text-1"><?php echo $AdvancedAry[$i][2];?></textarea>
                                    <div class="tools">
                                        <button type="button" onClick="moveUp2(this)" class="moveUp"><span class="hiddenItem">上移</span></button>
                                        <button type="button" onClick="moveDown2(this)" class="moveDown"><span class="hiddenItem">上移</span></button>
										<button type="button" onClick="DelhardLi(<?php echo $i+1; ?>)" class="removeRow"><span class="hiddenItem">移除</span></button>
                                    </div>
                                    <div class="clearFloat"></div>
                                </li>
                            <?php }?>
                        </ul>
                    </td>
				</tr>
                <tr>
                	<th>&nbsp;</th>
                    <td valign="middle"><span class="hor-box-text-normal"><button type="button" name="Btn_ADD3" id="Btn_ADD3" class="optionMoreBtn"><span class="sized-text-1">＋新增精益求精</span></button></span></td>
                </tr>
			</table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">嵌入影片連結：</span></th>
                    <td valign="middle"><input type="text" id="VideoUrl" name="VideoUrl" value="<?php echo $rs["VideoUrl"]?>" class="fullWidth sized-text-1"></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="leftHeader">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">評量建議：</span></th>
                    <td valign="middle"><textarea class="fullWidth sized-text-1" id="Suggest" name="Suggest" value="" style="height:10em;"><?php echo $rs["Suggest"]?></textarea></td>
                </tr>
            </table>
            <div class="spacingBlock"></div>
            
            <div>
            	<ul class="finalControl">
                	<li><button type="button" onClick="ChkForm(DataForm);" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                    <li><button type="button" onClick="history.back(-1)" class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
      </div>  
</form>          

        <!-- 資料新增功能 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>