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
	$Sql = " Select * From Template where Id = '$DataKey' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$rs = $MySql -> db_fetch_array($initRun);
//代碼 訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'ClassFrequency' ";
	$Sql .= " and Language = 'zh-tw'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤2");
	$OptionAry = $MySql -> db_array($Sql,6);
//教案名稱
	$Sql = " select * from TemplateTeachingPlan ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TemplateId = '".$rs["Id"]."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤3");
	$TeachingPlanAry = $MySql -> db_array($Sql,3);
//課堂天數
//	$Sql = " select count(*) from TemplateDetail where TemplateTeachingPlanId = (select Id from TemplateTeachingPlan where 1 = 1 and TemplateId = '".$rs["Id"]."' limit 1 ) ";
//	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
//	$DayCount = $MySql -> db_result($initRun);
//課表名稱
	$Sql = " select m.*, p.Name from TemplateDetail m ";
	$Sql .= " left join TemplateTeachingPlan t on t.Id = m.TemplateTeachingPlanId ";
	$Sql .= " left join TeachingPlan p on p.Id = m.TeachingPlanId";
	$Sql .= " where 1 = 1";
	$Sql .= " and TemplateId = '".$rs["Id"]."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤4");
	$DetailAry = $MySql -> db_array($Sql,4);
//取出檔案名稱
	$Sql = "select * from TemplateLog";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TemplateId = '".$rs["Id"]."' ";
	$Sql .= " order by Id desc ";
	$Sql .= " limit 1 ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤5");
	$rs2 = $MySql -> db_fetch_array($initRun);	
//
	$row = 0;
	if (($handle = fopen(root_path . Template .$rs2["ImportFileUrl"], "r")) !== FALSE) {
		while (($data = __fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			for ($c=0; $c < $num; $c++) {
				//echo $data[$c] . "<br />\n";
			}
		$row++;	
		}
		fclose($handle);
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
<title>擴思訊 Cross Think 後台 課表範本管理 檢視課表範本</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="/Js/ComFun.js"></script>
    <script type="text/javascript" src="/Js/read-csv.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".inNote").hide();

			
		});

		function ChkForm(_Form){
						
			if(IsEmpty(_Form.TemplateName,'課表名稱')){
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
                <li class="current"><span class="hor-box-text">檢視課表範本</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
        <div class="timeTableOperation themeSet1">
        <form name="DataForm" id="DataForm" method="post" action="Template_Update.php" autocomplete="off" enctype="multipart/form-data">
        	<input id="Id" name="Id" type="hidden" value="<?php echo $DataKey?>">
        	<table cellpadding="0" cellspacing="0" class="leftHeader import">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">課表名稱：</span></th>
                    <td valign="middle"><input type="text" id="TemplateName" name="TemplateName" readonly class="name sized-text-normal" value="<?php echo $rs["TemplateName"];?>"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<label><input type="radio" id="ClassFrequencyId" name="ClassFrequencyId" disabled value="<?php echo $OptionAry[$i][0];?>" <?php if($rs["ClassFrequencyId"]==$OptionAry[$i][0]){?>checked<?php }?>><span class="sized-text-1"><?php echo $OptionAry[$i][4]?></span>&nbsp;</label>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">匯入檔案：</span></th>
                    <td valign="middle">
                    	<div class="fileSelect">
                        	
                            <input type="file" name="importFile" id="importFile" class="trueFile" onChange="handleFiles(this.files)" disabled accept=".csv"><!-- 注意：將input[file] 選取檔案後的URI字串導出 -->
                            <label for="importFile" class="customBtn"><span class="hiddenItem">匯入</span></label><!-- 注意：input[file] 的id與name 須與label[for]內相同 -->
                            <input type="text" id="fileContent" value="<?php echo $rs2["ImportFileUrl"]?>" class="url sized-text-normal" readonly>
                            <span class="fakeBtn sized-text-normal">匯入</span>
                        	<!--<input type="file" name="importFile" id="importFile" class="trueFile" onchange="test2();">-->
                    		
                            <div class="clearFloat"></div>
                        </div>
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
            	<input id="rows" name="rows" value="<?php echo $row+1;?>" type="hidden">
            	<input id="cols" name="cols" value="<?php echo $num;?>" type="hidden">
            	<?php 
				$row = 0;
					if (($handle = fopen(root_path . Template .$rs2["ImportFileUrl"], "r")) !== FALSE) {
						while (($data = __fgetcsv($handle, 1000, ",")) !== FALSE) {
							$num = count($data);
							if($row==0){
								echo '<tr>';
							}else if($row%2==0){
								echo '<tr class="even">';
							}else{
								echo '<tr class="odd">';
							}

							for ($c=0; $c < $num; $c++) {
								if($row==0){	
									echo '<th><span class="hor-box-text-normal">'.$data[$c].'<input id="TeachingPlan'.$c.'" name="TeachingPlan'.$c.'" type="hidden" value="'.$data[$c].'" ></span></th>';						
								}else{
									echo '<td><span class="hor-box-text-normal" style="color: #514f4c;">'.$data[$c].'<input id="TeachingPlan'.$row.$c.'" name="TeachingPlan'.$row.$c.'" value="'.$data[$c].'" type="hidden"></span></td>';	
								}
								//echo $data[$c] . "<br />\n";
							}
						$row++;	
						}
						fclose($handle);
					}	
				?>    
            </table>
		</form>
            <div class="spacingBlock"></div>
            <div><span class="hor-box-text-normal"><button type="button" onClick="history.back(-1)" class="optionSaveBtn btnDisabled"><span class="sized-text-1">返回上頁</span></button></span></div><!-- 按鈕不可使用 -->
        </div>
    </div>
</div>
</body>
</html>