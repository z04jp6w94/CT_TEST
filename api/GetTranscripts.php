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
	$StudentId = trim(GetxRequest("StudentId"));
	$CompareMode = trim(GetxRequest("CompareMode"));
	$PersonnelId = trim(GetxRequest("PersonnelId"));
//	$ClassId = trim(GetxRequest("ClassId"));				//可有
	$IdentityType = trim(GetxRequest("IdentityType"));
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟
	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	$Status = "S";
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
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
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$PersonId = $MySql -> db_result($initRun);
//訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1  ";
	$Sql .= " and OptionalId = 'TeachingObjectives'";
	$Sql .= " and Language = 'zh-tw' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ObjAry = $MySql -> db_array($Sql,6);
//學生取班級
	$Sql = " select ClassId from ClassMember ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and StudentId = '".$StudentId."' ";	
	$ClassRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClassId = $MySql -> db_result($ClassRun);
//學生取學校
	$Sql = " select ClientId from Student";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and Id = '".$StudentId."' ";	
	$SLRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClientId = $MySql -> db_result($SLRun);
//取得全校班級
	$Sql = " select Id from Class";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and ClientId = '".$ClientId."' ";
	$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$ClassAry = $MySql -> db_array($Sql,2);
	
	for($i=0;$i<count($ClassAry);$i++){
		if($i<count($ClassAry)){
			$AClass = $AClass.$ClassAry[$i][0]."','";
		}else{
			$AClass .= '';
		}	
	}	
//資訊		

//json				
		$json = '{';
		$json .= '"Status": "'.$Status.'",';
		if($Status =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			$json .= '"Data": [';
			for($i=1;$i<=count($ObjAry);$i++){
				$json .= '{';
				$json .= '"TeachingObjectives": "'.$ObjAry[$i-1][4].'",';
				//計算
				if($CompareMode == 1){
					$Sql = " select DISTINCT M.Id, M.Comment, sum(B.Score), C.Description from OptionalItem M ";
					$Sql .= " left join TeachingObjectives A on A.OptionalId = M.Id ";
					$Sql .= " inner join Transcripts B on B.TeachingObjectivesId = A.Id and B.StudentId = '".$StudentId."' ";
					$Sql .= " left join SystemParameter C on C.Id = B.Category ";
					$Sql .= " where 1 = 1 ";
					$Sql .= " and M.OptionalId = 'TeachingObjectives' ";
					$Sql .= " and Language = 'zh-tw' ";
					$Sql .= " and M.Id = '".$ObjAry[$i-1][0]."' ";
					$Sql .= " group by M.Id ";		
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$RowCount = $MySql -> db_num_rows($SqlRun);
					$TransAry = $MySql -> db_array($Sql,6);
				}else if($CompareMode == 2){
					$Sql = " select DISTINCT M.Id, M.Comment, sum(B.Score), C.Description";
					$Sql .= " from OptionalItem M  ";
					$Sql .= " left join TeachingObjectives A on A.OptionalId = M.Id  ";
					$Sql .= " inner join Transcripts B on B.TeachingObjectivesId = A.Id and B.ClassId = '".$ClassId."'";
					$Sql .= " left join SystemParameter C on C.Id = B.Category ";
					$Sql .= " where 1 = 1 "; 
					$Sql .= " and M.OptionalId = 'TeachingObjectives'";
					$Sql .= " and Language = 'zh-tw' "; 
					$Sql .= " and M.Id = '".$ObjAry[$i-1][0]."' ";
					$Sql .= " and B.ClassId = '".$ClassId."' ";
					$Sql .= " group by M.Id";
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$RowCount = $MySql -> db_num_rows($SqlRun);
					$TransAry = $MySql -> db_array($Sql,6);	
				}else if($CompareMode == 3){			
					$Sql = " select DISTINCT M.Id, M.Comment, ";
					$Sql .= " Cast(sum((";
					$Sql .= " select sum(Score) from Transcripts where 1 = 1 and TeachingObjectivesId = A.Id ";
					$Sql .= " )) as SIGNED) 'Score'";
					$Sql .= " , C.Description ";
					$Sql .= " from OptionalItem M  ";
					$Sql .= " left join TeachingObjectives A on A.OptionalId = M.Id  ";
					$Sql .= " inner join Transcripts B on B.TeachingObjectivesId = A.Id ";
					$Sql .= " left join SystemParameter C on C.Id = B.Category ";
					$Sql .= " where 1 = 1 "; 
					$Sql .= " and M.OptionalId = 'TeachingObjectives'";
					$Sql .= " and Language = 'zh-tw' "; 
					$Sql .= " and M.Id = '".$ObjAry[$i-1][0]."' ";
					$Sql .= " and B.ClassId in ('$AClass')";
					$Sql .= " group by M.Id";
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$RowCount = $MySql -> db_num_rows($SqlRun);
					$TransAry = $MySql -> db_array($Sql,6);
				}else if($CompareMode == 4){
					$Sql = " select DISTINCT M.Id, M.Comment, ";
					$Sql .= " Cast(sum((";
					$Sql .= " select sum(Score) from Transcripts where 1 = 1 and TeachingObjectivesId = A.Id ";
					$Sql .= " )) as SIGNED) 'Score'";
					$Sql .= " , C.Description ";
					$Sql .= " from OptionalItem M  ";
					$Sql .= " left join TeachingObjectives A on A.OptionalId = M.Id  ";
					$Sql .= " inner join Transcripts B on B.TeachingObjectivesId = A.Id ";
					$Sql .= " left join SystemParameter C on C.Id = B.Category ";
					$Sql .= " where 1 = 1 "; 
					$Sql .= " and M.OptionalId = 'TeachingObjectives'";
					$Sql .= " and Language = 'zh-tw' "; 
					$Sql .= " and M.Id = '".$ObjAry[$i-1][0]."' ";
					$Sql .= " group by M.Id";
					$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
					$RowCount = $MySql -> db_num_rows($SqlRun);
					$TransAry = $MySql -> db_array($Sql,6);	
				}											
					if(count($TransAry) >= 1 ){		
						for($j=1;$j<=count($TransAry);$j++){
							if($ObjAry[$i-1][0] == $TransAry[$j-1][0]){
							//資料庫總學生數	
								if($CompareMode == 1){
									$Sql = " select count(*) from Transcripts M ";
									$Sql .= " left join TeachingObjectives A on A.Id = M.TeachingObjectivesId ";
									$Sql .= " left join OptionalItem B on B.Id = A.OptionalId ";
									$Sql .= " where 1 = 1";
									$Sql .= " and A.OptionalId = '".$TransAry[$j-1][0]."' ";
									$Sql .= " and M.StudentId = '".$StudentId."'";
									$Sql .= " and M.ClassId = '".$ClassId."' ";	
									$SqlRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
									$AllStudent = $MySql -> db_result($SqlRun2);					
								}else if($CompareMode == 2){
									$Sql = " select count(*) from Transcripts M ";
									$Sql .= " left join TeachingObjectives A on A.Id = M.TeachingObjectivesId ";
									$Sql .= " left join OptionalItem B on B.Id = A.OptionalId ";
									$Sql .= " where 1 = 1";
									$Sql .= " and A.OptionalId = '".$TransAry[$j-1][0]."' ";
									$Sql .= " and M.ClassId = '".$ClassId."' ";	
									$SqlRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
									$AllStudent = $MySql -> db_result($SqlRun2);
								}else if($CompareMode == 3){
									$Sql = " select count(*) from Transcripts M ";
									$Sql .= " left join TeachingObjectives A on A.Id = M.TeachingObjectivesId ";
									$Sql .= " left join OptionalItem B on B.Id = A.OptionalId ";
									$Sql .= " where 1 = 1";
									$Sql .= " and A.OptionalId = '".$TransAry[$j-1][0]."' ";	
									$Sql .= " and M.ClassId in ('$AClass')";
									$SqlRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
									$AllStudent = $MySql -> db_result($SqlRun2);
								}else if($CompareMode == 4){
									$Sql = " select count(*) from Transcripts M ";
									$Sql .= " left join TeachingObjectives A on A.Id = M.TeachingObjectivesId ";
									$Sql .= " left join OptionalItem B on B.Id = A.OptionalId ";
									$Sql .= " where 1 = 1";
									$Sql .= " and A.OptionalId = '".$TransAry[$j-1][0]."' ";
									$SqlRun2 = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
									$AllStudent = $MySql -> db_result($SqlRun2);
								}
							$json .= '"Category": "'.$TransAry[$j-1][3].'",';
							$json .= '"Score": "'.round($AllStudent/$TransAry[$j-1][2],2)*'100'.'"';
							
							}
						}
					}else{
						$json .= '"Category": "0",';
						$json .= '"Score": "0"';	
	
					}
				
				if($i != count($ObjAry)){
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
