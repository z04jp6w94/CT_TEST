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
//資料庫連線
	$MySql = new mysql();
//主檔	: 最新消息
	$Sql = " select Id, Name from TeachingPlan ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and DeleteStatus = 'N'";
	$Sql .= " and Enable = 'Y'"; 
	$Sql .= " order by Id desc ";
//	echo $Sql;	
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$TAry = $MySql -> db_array($Sql,2);
	
	$NameAry = array();
	$IdAry = array();
	
	for($i=0;$i<count($TAry);$i++){
		array_push($NameAry, $TAry[$i][1]);
	}
	for($i=0;$i<count($TAry);$i++){
		array_push($IdAry, $TAry[$i][0]);
	}
		
//	echo $NameAry[0];
	$data = json_decode(stripslashes($_POST['data']));

//	echo count($data);
	
	$i=count($data);
	for($x=0;$x<$i-1;$x++){					//1212
		if($x==0){
			echo '<tr>';
		}else if($x%2==0){
			echo '<tr class="even">';
		}else{
			echo '<tr class="odd">';
		}
		
		$j=count($data[$x]);
		for ($y=0;$y<$j;$y++){
			if($x==0){	
				echo '<th><span class="hor-box-text-normal">'.$data[$x][$y].'<input id="TeachingPlan'.$y.'" name="TeachingPlan'.$y.'" type="hidden" value="'.$data[$x][$y].'" ></span></th>';			
			}else if(in_array($data[$x][$y], $NameAry, true) || $y==0){
				echo '<td><span class="hor-box-text-normal">'.$data[$x][$y].'<input id="TeachingPlan'.$x.$y.'" name="TeachingPlan'.$x.$y.'" type="hidden" value="'.$data[$x][$y].'"></span></td>';	
			}else if($data[$x][$y]==''){
				echo '<td><span class="hor-box-text-normal">'.$data[$x][$y].'<input id="TeachingPlan'.$x.$y.'" name="TeachingPlan'.$x.$y.'" type="hidden" value="'.$data[$x][$y].'"></span></td>';
			}else{
				echo '<input id="ExcuteFalse" name="ExcuteFalse" type="hidden" value="N">';
				echo '<td><span class="hor-box-text-normal" style="color: #ff0000;">'.$data[$x][$y].'<input id="TeachingPlan'.$x.$y.'" name="TeachingPlan'.$x.$y.'" type="hidden"></span></td>';	
			}
		}
		echo '</tr>';
	}	
//關閉資料庫連線
	$MySql -> db_close();
?>
