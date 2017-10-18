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
//代碼 訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'ClassFrequency' ";
	$Sql .= " and Language = 'zh-tw'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
	
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
<title>擴思訊 Cross Think 後台 課表範本管理 新增課表範本</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="/Js/ComFun.js"></script>
    <script type="text/javascript" src="/Js/read-csv.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".inNote").hide();
			
			$("#DownLoad").click(function() {
					
					
			});
			
		});

		function ChkForm(_Form){
						
			if(IsEmpty(_Form.TemplateName,'課表名稱')){
			}else if(IsEmpty(_Form.ClassFrequencyId,'上課頻率')){
				alert('請勾選 上課頻率');	
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
                <li class="title"><span class="hor-box-text">課表範本管理</span></li>
                <li class="current"><span class="hor-box-text">新增課表範本</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
        <div class="timeTableOperation themeSet1">
        <form name="DataForm" id="DataForm" method="post" action="Template_AddNew.php" autocomplete="off" enctype="multipart/form-data">
        	<input id="rows" name="rows" value="" type="hidden">
            <input id="cols" name="cols" value="" type="hidden">
        	<table cellpadding="0" cellspacing="0" class="leftHeader import">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">課表名稱：</span></th>
                    <td valign="middle"><input type="text" id="TemplateName" name="TemplateName" class="name sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<label><input type="radio" id="ClassFrequencyId" name="ClassFrequencyId" value="<?php echo $OptionAry[$i][0];?>"><span class="sized-text-1"><?php echo $OptionAry[$i][4]?></span>&nbsp;</label>
                        <?php }?>
                        <!--<label><input type="radio"><span class="sized-text-1">基礎60天（一週至少3次）</span></label>
                        <label><input type="radio"><span class="sized-text-1">精要16天（一週至少1次）</span></label>
                        <label><input type="radio"><span class="sized-text-1">菁英10天（一天1次）</span></label>-->
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">匯入檔案：</span></th>
                    <td valign="middle">
                    	<div class="fileSelect">
                        	
                            <input type="file" name="importFile" id="importFile" class="trueFile" onChange="handleFiles(this.files)" accept=".csv"><!-- 注意：將input[file] 選取檔案後的URI字串導出 -->
                            <label for="importFile" class="customBtn"><span class="hiddenItem">匯入</span></label><!-- 注意：input[file] 的id與name 須與label[for]內相同 -->
                            <input type="text" id="fileContent" class="url sized-text-normal" readonly>
                            <span class="fakeBtn sized-text-normal">匯入</span>
                        	<!--<input type="file" name="importFile" id="importFile" class="trueFile" onchange="test2();">-->
                    		
                            <div class="clearFloat"></div>
                        </div>
					</td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">範例下載</span></th>
                    <td valign="middle">
						<a href="CT.csv"><input type="button" id="DownLoad" name="DownLoad" value="下載"></a>
					</td>
                </tr>
            </table>
            
            <div class="spacingBlock hr"></div>
            
            <ul class="importState">
            	<li><span class="hor-box-text-normal">匯入比對結果</span></li>
                <li id="inNote" class="inNote"><span class="hor-box-text-normal note">*紅字代表無法比對到正確到足以建立的教案名稱（Ex:名稱錯誤、有空格、全形半形...等差異），須重新確認後重新整筆匯入</span></li><!-- 如果匯入正確，不寫入這個LI -->
                <!--<li class="inError"><a href="#" class="hor-box-text-normal">資料不正確</a></li>
                <li class="inRight"><a href="#" class="hor-box-text-normal">資料正確</a></li>-->
            </ul>
            <div class="clearFloat"></div>
			
            <div class="spacingBlock"></div>
            <div class="spacingBlock hr-dot"></div>
            <div class="spacingBlock"></div>
            <div id="output">
            </div>
            <table id="timeTable" cellpadding="0" cellspacing="0" class="topAndLeftHeader timeTable">
            	<!--<tr>
                	<th><span class="hor-box-text-normal">&nbsp;</span></th>
                    <th><span class="hor-box-text-normal">教案1</span></th>
                    <th><span class="hor-box-text-normal">教案2</span></th>
                    <th><span class="hor-box-text-normal">教案3</span></th>
                </tr>
                <tr class="odd">
                	<td><span class="hor-box-text-normal">Day1</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>
                <tr class="even">
                	<td><span class="hor-box-text-normal">Day2</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>
                <tr class="odd">
                	<td><span class="hor-box-text-normal">Day3</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal"><strong class="errorItem">轉球運動(一)</strong></span></td>
                </tr>
                <tr class="even">
                	<td><span class="hor-box-text-normal">Day4</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal"><strong class="errorItem">模擬運動(一)</strong></span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>-->
            </table>
		</form>
            <div class="spacingBlock"></div>
            <div><span class="hor-box-text-normal"><button type="button" name="Go" id="Go" onClick="ChkForm(DataForm);" class="optionSaveBtn btnDisabled"><span class="sized-text-1">儲存</span></button></span></div><!-- 按鈕不可使用 -->
            <!-- <div><span class="hor-box-text-normal"><button type="button" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></span></div> 按鈕可以使用 -->
        </div>
    </div>
</div>
</body>
</html>
