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
//		撰寫日期：20140728
//		程式功能：api 
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
//
	$ExcuteSuccess = true;	
//setting
	$ValidateCode = strtoupper(trim(GetxRequest("validatecode"))); 											//正式
	$PersonnelId = trim(GetxRequest("PersonnelId"));
	$IdentityType = trim(GetxRequest("IdentityType"));
	$StudentId = trim(GetxRequest("StudentId"));
	$ClassId = trim(GetxRequest("ClassId"));
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$Status = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
	$WebDate = DspDate($Date,"-");
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$Status = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}

//資料庫連線
	$MySql = new mysql();
//檢驗身份
	$Sql = " select Id from Personnel ";
	$Sql .= " where 1 = 1";
	$Sql .= " and Id = '$PersonnelId' ";
	$Sql .= " and IdentityTypeId = '$IdentityType' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$PersonId = $MySql -> db_result($initRun);
//取出級別L1 ~ L6
	$Sql = " select Id, Description from SystemParameter ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Comment = '000000000000226' ";
	$Sql .= " and DeleteStatus = 'N' ";
	$Sql .= " order by Description ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$LevelAry = $MySql -> db_array($Sql,3);
//學生的班級
	$Sql = " select ClassId from ClassMember ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and StudentId = '".$StudentId."' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$ClassId = $MySql -> db_result($SqlRun);
//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			$json .= '"Data": [';
			for($i=1;$i<=count($LevelAry);$i++){									//L1~L6
				$json .= '{';
				$json .= '"Level": "'.$LevelAry[$i-1][1].'",';
				$json .= '"SubLevel":[';
				
				$Sql = " select DISTINCT M.*, count(A.SubLevelId) from SystemParameter M ";
				$Sql .= " left join TeachingPlan A on A.SubLevelId = M.Id ";
				$Sql .= " where 1 = 1 ";
				$Sql .= " and Comment = '000000000000229' ";
				$Sql .= " and M.DeleteStatus = 'N'";
				$Sql .= " and LevelKey = '".$LevelAry[$i-1][0]."'";
				$Sql .= " group by A.SubLevelId ";
				$Sql .= " order by M.Description "; //17-03-15 
				
				$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
				$SubLevelAry = $MySql -> db_array($Sql,6);
					
				for($j=1;$j<=count($SubLevelAry);$j++){				
								//L1-1 1-2 ...
					if($LevelAry[$i-1][0] == $SubLevelAry[$j-1][3]){	
					
					$Sql = " select count(*) ";
					$Sql .= " from Class M ";
					$Sql .= " left join ClassTime C on C.ClassId = M.Id and C.ClassTime ";
					$Sql .= " left join TemplateDetail T on T.Id = C.TemplateDetailId ";
					$Sql .= " left join TeachingPlan P on P.Id = T.TeachingPlanId ";
					$Sql .= " where 1 = 1 ";
					$Sql .= " and P.SubLevelId = '".$SubLevelAry[$j-1][0]."' ";
					$Sql .= " and M.Id = '".$ClassId."' ";
					$Sql .= " and M.StartTime <= '".$Date."' ";
					$Sql .= " and C.ClassTime <= '".$WebDate."' ";
					$Sql .= " and T.TeachingPlanId != ''";	
					$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
					$Count = $MySql -> db_result($SqlRun);
					
//					$Sql = " select DISTINCT ";
//					$Sql .= " ( ";
//					$Sql .= " select count(DISTINCT TeachingPlanId) from TemplateDetail ";
//					$Sql .= " left join TeachingPlan on TeachingPlan.Id = TemplateDetail.TeachingPlanId where 1 = 1 and TeachingPlan.SubLevelId = '".$SubLevelAry[$j-1][0]."' ";
//					$Sql .= " and TemplateTeachingPlanId in ";
//					$Sql .= " ( ";
//					$Sql .= " (select B.Id from TemplateTeachingPlan B where 1 = 1 and B.TemplateId in ((select DISTINCT TemplateId from Class A where 1 = 1 and A.Id in (M.ClassId)) and A.StartTime <= '".$Date."') ) ";
//					$Sql .= " ) ";
//					$Sql .= " ) '教案數' ";
//					$Sql .= " from ClassMember M ";
//					$Sql .= " left join Class A on A.Id= M.ClassId ";
//					$Sql .= " where 1 = 1  ";
//					$Sql .= " and M.StudentID = '".$StudentId."' ";		
//					
//					$SqlRun = $MySql -> db_query($Sql) or die("查詢錯誤");
//					$StudentTeaching = $MySql -> db_array($Sql,3);
					
//					for($k=0;$k<count($StudentTeaching);$k++){
//						$count = $count + $StudentTeaching[$k][0];
//					}
					
					$json .= '{';
					$json .= '"Name": "'.$SubLevelAry[$j-1][2].'",';
					$json .= '"Completed": "'.$Count.'",';
					$json .= '"Undoe": "'.$SubLevelAry[$j-1][5].'"';
					
						if($j != count($SubLevelAry)){
							$json .= '},';
						}else{
							$json .= '}';
						}					
					}
				}
				
					$json .= ']';	
					
				if($i != count($LevelAry)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			
			$json .= ']';
			
		}else{
			$json .= '"Data":""';	
		}
		
		$json .= '}';
		
//關閉資料庫連線
	$MySql -> db_close();
		
		echo $json;
		exit ;
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo MetaTitle; ?></title>
	<?php include_once(root_path . "/CommonPage/MaintainMeta.php");?>
	<script language="JavaScript">
		$(function(){
		
		})
	</script>
</head>
<body>

</body>
</html>
