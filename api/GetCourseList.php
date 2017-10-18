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
	$TeachingPlanId = trim(GetxRequest("TeachingPlanId"));	//教案ID
	$PersonnelId = trim(GetxRequest("PersonnelId"));		//教師ID
//	$CourseId = trim(GetxRequest("CourseId"));				//應該拿掉
	$IdentityType = trim(GetxRequest("IdentityType"));
	$Date = date("Ymd");
	$WebDate = DspDate($Date,"-");
//170124新增
	$ClassId = trim(GetxRequest("ClassId"));

//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		

	$ClassDate = DspDate($Date,"-");
	$SystemDate = DspDate($Date,'/') .' '. DspTime($Time,':');										//SysTime
	
	$WebStatus = "S" ;
	
	$SysValidatecode1 = strtoupper(md5($Date));
	$SysValidatecode2 = strtoupper(md5($TomorrowDate));
		
//驗證	
	if($ValidateCode != $SysValidatecode1 && $ValidateCode != $SysValidatecode2 && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '驗證碼錯誤';
		$ExcuteSuccess = false;
	}
//參數檢測
	if($TeachingPlanId =='' || $PersonnelId =='' || $IdentityType =='' && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
	
//資料庫連線
	$MySql = new mysql();

//課程計錄	
	$Sql = " select ifnull(A.Id,''),  ";
	$Sql .= " Case when Pic1L != '' then 'Y' else 'N' END, ";
	$Sql .= " Case when Stars != '' then 'Y' else 'N' END, ";
	$Sql .= " Case when Comment != '' then 'Y' else 'N' END, ";
	$Sql .= " ifnull(A.Status,'0'), S.Id, S.NickName ";
	$Sql .= " from ClassMember C ";
	$Sql .= " left join Student S on S.Id = C.StudentId ";
	$Sql .= " left join Course A on A.StudentId = S.Id and A.TeachingPlanTime = '".$WebDate."' and TeachingPlanId = '".$TeachingPlanId."' ";
	$Sql .= " where 1 = 1 ";	
	$Sql .= " and ClassId = '".$ClassId."'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Course = $MySql -> db_array($Sql,10);
//班級資料
	$Sql = " select DISTINCT M.Id, ClassName, ClassLevelId, TeacherId, M.TemplateId, ClassWeek, StartTime, Cast((Length(ClassWeek)+1)/2 as SIGNED), A.Comment, C.Day ";
	$Sql .= " from Class M ";
	$Sql .= " left join OptionalItem A on A.Id = M.ClassLevelId ";
	$Sql .= " left join ClassTime D on D.ClassId = M.Id and D.ClassTime = '".$ClassDate."' ";
	$Sql .= " left join TemplateDetail C on C.Id = D.TemplateDetailId ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and TeacherId = '".$PersonnelId."'";
	$Sql .= " and Enabled = 'Y'";
	$Sql .= " and DeleteStatus = 'N'";
	$Sql .= " and M.Id = '".$ClassId."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Class = $MySql -> db_array($Sql,20);	
	
//json				
		$json = '{';
		$json .= '"Status": "'.$WebStatus.'",';
		if($WebStatus =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){
			$json .= '"Class": [';
			for($i=1;$i<=count($Class);$i++){
				$json .= '{';
				$json .= '"ClassId": "'.$Class[$i-1][0].'",';
				$json .= '"Name": "'.$Class[$i-1][1].'",';
				$json .= '"Level": "'.$Class[$i-1][8].'",';
				$json .= '"Semester": "106",';
				$json .= '"Date": "'.DspDate($Date,"/").'",';
				$json .= '"Day": "'.$Class[$i-1][9].'"';
				if($i != count($Class)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';
			
			$json .= '"Data": [';
			for($i=1;$i<=count($Course);$i++){
				$json .= '{';
				$json .= '"CourseId": "'.$Course[$i-1][0].'",';
				$json .= '"StudentId": "'.$Course[$i-1][5].'",';
				$json .= '"NickName": "'.$Course[$i-1][6].'",';
				$json .= '"Pic1L": "'.$Course[$i-1][1].'",';
				$json .= '"Star": "'.$Course[$i-1][2].'",';
				$json .= '"Comment": "'.$Course[$i-1][3].'",';
				$json .= '"Status": "'.$Course[$i-1][4].'"';
				if($i != count($Course)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= ']';
		}else{
			$json .= '"Class":"",';	
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
