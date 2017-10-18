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
	$PersonnelId = trim(GetxRequest("PersonnelId"));		//教師ID
	$CourseId = trim(GetxRequest("CourseId"));
	$IdentityType = trim(GetxRequest("IdentityType"));
	$StudentId = trim(GetxRequest("StudentId"));																//家長留言用
//	$TeachingPlanId = trim(GetxRequest("TeachingPlanId"));														//無 CourseId 抓這個
//	$ValidateCode = strtoupper(md5(date("Ymd")));	 															//test 需要測試開啟

	$Date = date("Ymd");
	$TomorrowDate = date("Ymd", time() + 3600*24); 													//
	$Time = date("His");		

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
	if($PersonnelId =='' || $IdentityType =='' && $ExcuteSuccess=true){
		$WebStatus = "F";
		$msg = '參數缺少';
		$ExcuteSuccess = false;	
	}
	
//資料庫連線
	$MySql = new mysql();
if($CourseId != ''){
//家長留言	
	$Sql = " select M.Comment, T.FullName, M.CreateDate, M.CourseId from Message M ";
	$Sql .= " left join Personnel T on T.Id = M.CreatorId ";
	$Sql .= " where 1 = 1";	
	$Sql .= " and CourseId = '".$CourseId."'";
	$Sql .= " and IsRead = 'N'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Message = $MySql -> db_array($Sql,4);	
	
//課程計錄	
	$Sql = " select M.Id, Stars, Comment, Pic1L, Pic1S, Pic2L, Pic2S, Pic3L, Pic3S, Number, Status, B.Name, M.TeachingPlanId ";
	$Sql .= " from Course M ";
	$Sql .= " left join TeachingPlan B on B.Id = M.TeachingPlanId ";
	$Sql .= " where 1 = 1 ";	
	$Sql .= " and M.Id = '".$CourseId."'";
	$Sql .= " and M.StudentId = '$StudentId'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Course = $MySql -> db_array($Sql,15);
	
}else{
//學生班級	
	$Sql = " select ClassId from ClassMember ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and StudentId = '".$StudentId."' ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤1");
	$ClassId = $MySql -> db_result($initRun);
//取課程計錄Id
	$Sql = " select DISTINCT M.CourseId from Message M " ;
	$Sql .= " where 1 = 1 ";
	$Sql .= " and M.IsRead = 'N' ";
	$Sql .= " and CourseId in (select Id from Course where 1 = 1 and PersonnelId = '".$PersonnelId."' and StudentId = '".$StudentId."')";
	$Sql .= " order by CourseId ";
///	$Sql .= " limit 1 ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
///	$CourseId = $MySql -> db_result($initRun);
	$CourseIdAry = $MySql -> db_array($Sql,2);	
	
	for($i=0;$i<count($CourseIdAry);$i++){
		if($i<count($CourseIdAry)){
			$CourseId = $CourseId.$CourseIdAry[$i][0]."','";
		}else{
			$CourseId .= '';
		}	
	}
	
//家長留言	
	$Sql = " select M.Comment, T.FullName, M.CreateDate, M.CourseId from Message M ";
	$Sql .= " left join Personnel T on T.Id = M.CreatorId ";
	$Sql .= " where 1 = 1";	
	$Sql .= " and CourseId in ( '".$CourseId."' ) ";
	$Sql .= " and IsRead = 'N'";
	$Sql .= " order by CourseId ";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Message = $MySql -> db_array($Sql,4);	
	
//課程計錄	
	$Sql = " select M.Id, Stars, Comment, Pic1L, Pic1S, Pic2L, Pic2S, Pic3L, Pic3S, Number, Status, B.Name, M.TeachingPlanId ";
	$Sql .= " from Course M ";
	$Sql .= " left join TeachingPlan B on B.Id = M.TeachingPlanId ";
	$Sql .= " where 1 = 1 ";	
	$Sql .= " and M.Id in ( '".$CourseId."' )";
	$Sql .= " and M.StudentId = '$StudentId'";
	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$Course = $MySql -> db_array($Sql,15);
	
}
//
//json				
		$json = '{';
		$json .= '"Status": "'.$WebStatus.'",';
		if($WebStatus =='F'){
			$json .= '"Reason": "'.$msg.'",';
		}
			$json .= '"DateTime": "'.$SystemDate.'",';
		if($ExcuteSuccess == true){			
			$json .= '"Course": [';
			for($i=1;$i<=count($Course);$i++){
				
				$a = 0;
				if($Course[$i-1][3] != ''){	
					$a = $a +1;
				}
				if($Course[$i-1][5] != ''){
					$a = $a +1;
				}
				if($Course[$i-1][7] != ''){
					$a = $a +1;
				}
				
				//$MessDate = DspDate(substr($Course[$i-1][12],0,8),"/");
				//$M = substr($Message[$i-1][2],4,2);
				//$D = substr($Message[$i-1][2],6,2);
				//$Y = substr($Message[$i-1][2],0,4);
				//$week=Array("日","一","二","三","四","五","六");				
				//$DateW = $week[date("w", mktime(0,0,0,$M,$D,$Y))];
				
				$json .= '{';
				$json .= '"CourseId": "'.$Course[$i-1][0].'",';
				$json .= '"TeachingPlanId": "'.$Course[$i-1][12].'",';
				$json .= '"Name": "'.$Course[$i-1][11].'",';
				//$json .= '"Date": "'.$MessDate.'('.$DateW.')'.'",';
				$json .= '"Stars": "'.$Course[$i-1][1].'",';
				$json .= '"Comment": "'.$Course[$i-1][2].'",';
				if($Course[$i-1][3]!= ''){
					$json .= '"Pic1L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][3].'",';
				}else{
					$json .= '"Pic1L": "",';
				}
				if($Course[$i-1][4]!= ''){
					$json .= '"Pic1S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][4].'",';
				}else{
					$json .= '"Pic1S": "",';
				}
				if($Course[$i-1][5]!= ''){
					$json .= '"Pic2L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][5].'",';
				}else{
					$json .= '"Pic2L": "",';
				}
				if($Course[$i-1][6]!= ''){
					$json .= '"Pic2S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][6].'",';
				}else{
					$json .= '"Pic2S": "",';
				}
				if($Course[$i-1][7]!= ''){
					$json .= '"Pic3L": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][7].'",';
				}else{
					$json .= '"Pic3L": "",';
				}
				if($Course[$i-1][8]!= ''){
					$json .= '"Pic3S": "http://'.$_SERVER['HTTP_HOST'].Course.$Course[$i-1][8].'",';
				}else{
					$json .= '"Pic3S": "",';
				}
				$json .= '"Number": "'.$a.'",';
				if($Course[$i-1][10] ==''){
					$Course[$i-1][10] = 0;
				}
				$json .= '"Status": "'.$Course[$i-1][10].'"';
				if($i != count($Course)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= '],';
			
			$json .= '"Message": [';
			for($i=1;$i<=count($Message);$i++){
				
				$MessDate = DspDate(substr($Message[$i-1][2],0,8),"/");
				$M = substr($Message[$i-1][2],4,2);
				$D = substr($Message[$i-1][2],6,2);
				$Y = substr($Message[$i-1][2],0,4);
				
				$week=Array("日","一","二","三","四","五","六");
				
				$DateW = $week[date("w", mktime(0,0,0,$M,$D,$Y))];
				
				$json .= '{';
				$json .= '"CourseId": "'.$Message[$i-1][3].'",';
				$json .= '"Name": "'.$Message[$i-1][1].'",';
				$json .= '"Comment": "'.$Message[$i-1][0].'",';
				$json .= '"CreateDate": "'.$MessDate.'('.$DateW.')'.'"';
				if($i != count($Message)){
					$json .= '},';
				}else{
					$json .= '}';
				}
			}
			$json .= ']';	
		}else{
			$json .= '"Course":"",';	
			$json .= '"Message":""';
		}
			
		$json .= '}';
		
//關閉資料庫連線
	$MySql -> db_close();
		echo $json;
		exit ;			
?>

