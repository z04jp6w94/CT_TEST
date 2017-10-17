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
//		撰寫人員：t
//		撰寫日期：20140726
//		程式功能：ct / 教師管理 / 列表
//		使用參數：None
//		資　　料：sel：ad_m
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
	setcookie("FilePath", $_SERVER['SCRIPT_NAME'], time() + 3600 * 24);						//取得本檔案路徑檔名
	$gMainFile = basename(__FILE__, '.php');												//去掉路徑及副檔名
	$USER_ID = $_SESSION["C_USER_ID"];														//管理員 ID
	$USER_NM = $_SESSION["C_USER_NM"];
	$USER_NUM = $_SESSION["M_USER_Num"];													//管理員序號
				
//資料庫連線
	$MySql = new mysql();
//定義一般參數	
	$S = trim(GetxRequest("S"));
	if($S == 1){
		$S2 = '2';
	}else{
		$S2 = '1';
	}
	$T = trim(GetxRequest("T"));
	if($T == 1){
		$T2 = '2';
	}else{
		$T2 = '1';
	}
	$C = trim(GetxRequest("C"));
	if($C == 1){
		$C2 = '2';
	}else{
		$C2 = '1';
	}
	$M = trim(GetxRequest("M"));
	if($M == 1){
		$M2 = '2';
	}else{
		$M2 = '1';
	}
	$myWhere = " Where 1 = 1 ";
//主檔
	$Sql = " select M.Id, M.Comment, M.LastEditDate, T.Name, A.FullName, M.Pic1L, M.Pic2L, M.Pic3L, M.Stars from Course M ";
	$Sql .= " left join Student T on T.Id = M.StudentId ";
	$Sql .= " left join Personnel A on A.Id = M.PersonnelId ";
	$Sql .= $myWhere;
	$Sql .= " and Status = '1' ";
	$Sql .= " and A.ClientId = '".$USER_NUM."'";
	if($S != '' && $S == '1'){
		$Sql .= " order by T.Name desc ";
	}else if($S != '' && $S == '2'){
		$Sql .= " order by T.Name ";	
	}else if($T != '' && $T == '1'){
		$Sql .= " order by A.FullName desc ";
	}else if($T != '' && $T == '2'){
		$Sql .= " order by A.FullName ";	
	}else if($C != '' && $C == '1'){
		$Sql .= " order by M.Comment desc ";
	}else if($C != '' && $C == '2'){
		$Sql .= " order by M.Comment ";	
	}else if($M != '' && $M == '1'){
		$Sql .= " order by M.LastEditDate desc ";
	}else if($M != '' && $M == '2'){
		$Sql .= " order by M.LastEditDate ";	
	}else{
		$Sql .= " order by LastEditDate desc ";	
	}
	//echo $Sql;
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$RowCount = $MySql -> db_num_rows($initRun);
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
<title>Web_園方 教師 自購家長(管理者介面) 班級與學生管理 學生管理 已關聯家長帳號</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script type="text/javascript">
		$(document).ready(function(){

			$("#Student").click(function(){
				window.location = '<?php echo $gMainFile; ?>.php?S=<?php echo $S2?>' ;
			});
			$("#Teacher").click(function(){
				window.location = '<?php echo $gMainFile; ?>.php?T=<?php echo $T2?>' ;
			});
			$("#Comment").click(function(){
				window.location = '<?php echo $gMainFile; ?>.php?C=<?php echo $C2?>' ;
			});
			$("#Modify").click(function(){
				window.location = '<?php echo $gMainFile; ?>.php?M=<?php echo $M2?>' ;
			});	
			
		});
		function SetKey(pVal){
			DataForm.DataKey.value = pVal;
		}
		function Excute(wFunc){
			switch(wFunc){	
				case 'Cofirm':
					if(confirm("是否確認送出?")){
						DataForm.action='<?php echo $gMainFile; ?>_Update.php';
						DataForm.submit();
						break;
					}
					break;
				default:
			}
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
                <li class="title"><span class="hor-box-text">課程記錄審核</span></li>
                <!-- <li class="current"><span class="hor-box-text">新增教案</span></li>如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                 
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
    	<!-- 功能內容 begin -->
        
        <!-- 資料檢視表格 begin -->
        <div class="dataIndexList themeSet2">
        	<table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
             <form name="DataForm" id="DataForm" method="post">
            	<input type="hidden" id="DataKey" name="DataKey" value="" />
                <input type="hidden" id="" name="" value="" />
            	<tr>
                	<th>&nbsp;</th>
                    <th class="topHeader"><span id="Student" <?php if($S2 == '1'){?>value="1"<?php }else{ ?>value="2"<?php }?> class="hor-box-text-large">學生姓名</span></th>
                    <th class="topHeader"><span id="Teacher" <?php if($T2 == '1'){?>value="1"<?php }else{ ?>value="2"<?php }?> class="hor-box-text-large">老師姓名</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">星星數</span></th>
                    <th class="topHeader"><span class="hor-box-text-large">照片數</span></th>
                    <th class="topHeader"><span id="Comment" <?php if($C2 == '1'){?>value="1"<?php }else{ ?>value="2"<?php }?> class="hor-box-text-large">課程記錄文字評語</span></th>
                    <th class="topHeader"><span id="Modify" <?php if($M2 == '1'){?>value="1"<?php }else{ ?>value="2"<?php }?> class="hor-box-text-large">最後修改時間</span></th>
                </tr>
                <?php
					$lineCount = 0;
					while ($initAry = $MySql -> db_fetch_array($initRun)){
					$lineCount ++;
					$count = 0;
					if($initAry[5] != ''){
						$count = $count +1 ;
					}
					if($initAry[6] != ''){
						$count = $count +1 ;
					}
					if($initAry[7] != ''){
						$count = $count +1 ;
					}
				?>
                <tr <?php if($lineCount % 2 == 1){ echo ' class="odd"';}else{ echo ' class="even"' ;}?>>
                	<th valign="middle">
                    	<ul class="operations size-single">
                        	<li><a href="javascript:;" onClick="SetKey('<?php echo $initAry[0]; ?>');Excute('Cofirm');return false;" class="submit"><span class="hiddenItem">送出</span></a></li>
                        </ul>
                    </th>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[3];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $initAry[4];?></span></td>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[8];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo $count;?></span></td>
                    <td class="leftAlignText"><span class="hor-box-text-normal"><?php echo $initAry[1];?></span></td>
                    <td><span class="hor-box-text-normal"><?php echo DspDate(substr($initAry[2],0,8),"/").' '. DspTime(substr($initAry[2],8,6),":")?></span></td>
                </tr>
                <?php }?>
            </form>
            </table>
        </div>
        <!-- 資料檢視表格 end -->
        
        <!-- 功能內容 end -->
    </div>
</div>
</body>
</html>
