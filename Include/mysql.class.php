<?php
	header ('Content-Type: text/html; charset=utf-8');
class mysql{
	var $num;
	var $link;
	var $host = Host;
	var $user_name = UserName;
	var $database = DBName;
	var $passwd = PassWord;
	var $debug = false;
	function mysql(){
		set_time_limit('0');
		$this -> link = mysql_connect($this -> host, $this -> user_name, $this -> passwd) or die('Connect Error');
		mysql_select_db($this -> database,$this -> link) or die ('select DB Error');
		mysql_query('SET NAMES UTF8');
	}
	function db_escape_string($str){
		if(is_array($str)){
			foreach ($str as $k => $v){
				$str[$k] = $this -> db_escape_string($v);
			}
		}else{
			$str = str_replace("'","&#039;",$str);
			$str = str_replace("\"","&quot;",$str);
//			$str = str_replace("\\","\\\\",$str);
			$str = str_replace("&","&amp;",$str);
			$str = str_replace("<","&lt;",$str);
			$str = str_replace(">","&gt;",$str);
		}
		Return $str;
	}
	function db_query($str){
//		echo("$qty_str<BR>");
//		$this -> num = @mssql_num_rows(mssql_query($qty_str));
//		$qty_str= $this ->db_escape_string($qty_str);
	  return @mysql_query($str);
	}
	function db_get_num($str){
		$res = $this -> db_query($str);
		$num = $this -> db_num_rows($res);
		return $num;
	}
	function db_num_rows($res){
		return @mysql_num_rows($res);
	}
	function db_dataChang($val, $where = ''){
		if($where){
			$res = $this -> updateOne($val, $where);
		}else{
			$res = $this -> insertVal($val);
		}
		return $res;
	}
	function setTable($table_name){
		$this -> table_name = $table_name;
	}
	function getAvailArray($fields,$values){
		foreach($fields as $v){
			if(array_key_exists($v,$values)){
				$new[$v] = $values[$v]{0} == "~"?substr($values[$v],1):"'".$values[$v]."'";
			}
		}
		return $new;
	}	
	//private function insertVal($val){					//for php5
	function insertVal($val){							//for php4
		foreach($val as $k=>$v){
			$val[$k] = $v{0} == "~"?$v:"'".$v."'";
//			$val[$k] = $k{0} == "~"?$k:"`".$v."`";
		}
		$str = " insert into " . $this -> table_name . " ";
		$str .= " ( " . implode(array_keys($val),",") . " ) ";
		$str .= " values( " . implode(array_values($val), ",") . " ) ";
		if($this -> debug == true){	echo $str;}
		return $this ->db_query($str);
	}
	//private function updateOne($pairs,$where=""){		// for php5
	function updateOne($pairs, $where = ""){			//for php4
		foreach($pairs as $k => $v){
			$pairs[$k] = $v{0} == "~"?$v:"'".$v."'";
		}	
		$str = "update " . $this -> table_name;
		$data = array_walk($pairs, create_function('&$value,&$key','$value = $key . "=".$value;') );
		$str .= " set ".join(array_values($pairs),",");
		$str .= " where ".$where;
		if($this -> debug == true){echo $str;}
		return $this -> db_query($str);
	}
	function db_fetch_array($res) {
		return @mysql_fetch_array($res);
	}
	function db_fetch_row($result){
		return @mysql_fetch_row($result);
	}
	function db_fetch_assoc($str){
		$res = $this -> db_query($str);
		return @mysql_fetch_assoc($res);
	}
	function db_result($result){
		return @mysql_result($result, 0);
	}
	function db_data_seek($result, $row){
		return @mysql_data_seek($result, $row);
	}
	function getRows($str){
		$this -> res = $this ->db_query($str); 
//		$this ->feildNum=$this -> db_num_rows($this ->res);
		$i = 0;
		while ($rows = @mysql_fetch_assoc($this -> res)){
			foreach ($rows as $key => $value){
				$col[$i][$key] = $value;
			}
			$i++;	
		}
		return $col;
	}
	function getDatas($str){
		$res = $this -> db_query($str); 
//		$this ->feildNum=$this -> db_num_rows($this ->res);
		$i = 0;
		while ($rows = @mysql_fetch_assoc($res)){
			foreach ($rows as $key => $value){
				$col[$key][$i]=$value;
			}
			$i++;	
		}
		return $col;
	}
	/*function db_fetch_object($res) {
		  return mysql_fetch_object($res);
	}*/
	function db_limit($sql,$page=1,$PageSize=10,$other='') {
//		$page_num=ceil($);
		$result = $this -> db_query($sql);
		$TotalPage = $this -> db_num_rows($result);
		@$PageNum = ceil($PageSize/$TotalPage);
		($page - 1) < 0 ? $start = 0:$start = ($page - 1);
		@$start = $start * $PageSize;
		@$PageRowNum = mysql_query($sql);
		$resultres = $this -> db_query($sql." limit $start, $PageSize");
//		echo $sql." limit $start, $PageSize";
//		for($i=0; $i<$PageSize; $i++) {
		while ($row = $this -> db_fetch_array($resultres)){
//			$row=$this -> db_fetch_array($resultres);
			$rows[] = $row;
		}
		$MaxPage = @(int)ceil($TotalPage/$PageSize);
		$page != 1 ? $HoemPage = "<a href='?page=1" . $other . "'>第一頁</a>" : $HoemPage = "第一頁";
		$page - 1 >= 1 ? $BackPage = "<a href='?page=" . ($page - 1) . $other . "'>上一頁</a>" : $BackPage = "上一頁";
		$MenuListStart = (@(((ceil($PageSize/$TotalPage) - 1) * $PageSize) + 1));
		$MenuListEnd = ($MenuListStart + $PageSize);
		$MenuListEnd >= $MaxPage ? $MenuListEnd = "$MaxPage" + 1 : "";
		if($$TotalPage){
			for($ii = $MenuListStart; $ii < $MenuListEnd; $ii++) {
				$page == $ii ? $pageMenu .= " $page" : $pageMenu .= " <a href='?page=$ii" . $other . "'>$ii</a> ";
			}
		}
		$page < 10 ? $page = '0' . $page : "";
		$pageMenu .= $page . "&nbsp;&nbsp;/&nbsp;&nbsp;" . $MaxPage;
		$page + 1 <= $MaxPage ? $NextPage = "<a href='?page=" . ($page + 1) . $other . "'>下一頁</a>" : $NextPage = "下一頁";
		$page != $MaxPage ? $EndPage .= "<a href='?page=$MaxPage" . $other . "'>最末頁</a>" : $EndPage = "最末頁";
		$TotalPage = '共' . $TotalPage . '筆';
		$Menu = ' | ' . $HoemPage . ' | ' . $BackPage . ' | &nbsp;' . $pageMenu . '&nbsp; | ' . $TotalPage . ' | ' . $NextPage . ' | ' . $EndPage . ' | ';
		$rows = array('rows' => $rows, 'menu' => $Menu);
		return $rows;
	}
	function db_insert_id() {
		return mysql_insert_id();   
//  	return mymysql_insert_id($res);
	}
	function db_error ($res) {
//		mysql service �M��insert_id()���	  
	$mysql = "select @@ERROR as code";
	$result = @mssql_query($mysql, $con);
	$row = @mssql_fetch_array($result);
	$code = $row["code"]; // error code
	$mysql = "select cast (description as varchar(255)) as errtxt from master.dbo.sysmessages where error = $code and msglangid = 1031"; // german
	$result = @mssql_query($mysql, $con);
	$row = @mssql_fetch_array($result);
	if ($row)
		$text = $row["errtxt"]; // error text (with placeholders)
	else
		$text = "onknown error";
		@mssql_free_result($result);
	return "[$code] $text";
	}
	function db_del($where){
		$str = "delete from `" . $this -> table_name . "`";
		$str .= " where " . $where ;
		if($this -> debug == true){echo $str;}
		if(mysql_query($str)){
			return true;
		}else {
			return false;
		}
	}
	function db_close() {
		@mysql_close();
	}
	function db_array($sql,$n=1){
		$result = $this -> db_query($sql);
		$Ary = array();
		$Ary2 = array();
		$lineCount = 0;
		while ($Ary2 = $this -> db_fetch_array($result)){
			$lineCount ++;
			if($n>1){
				for($i=0;$i<$n;$i++){
					$Ary[$lineCount-1][$i]=$Ary2[$i];
				}
			}else{
				$Ary[$lineCount-1]=$Ary2[0];
			}
		}
		return $Ary;
	}
}	
