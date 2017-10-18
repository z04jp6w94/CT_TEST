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
	header ('Content-Type: text/html; charset=utf-8');
//	Function Index
//	日期
//		MonthChEn				: 取得月份英文名稱
//		OnWeek					: 取得指定時間的星期(幾)字串 / 輸出：日-六 String
//		DspDate					: 將日期字串YYYYMMDD轉換成YYYY/MM/DD / 輸出：ex:YYYY/MM/DD
//		DspTime					: 將時間字串HHMMSS轉換成HH:MM:SS
//	資訊
//		generatorPassword		: 亂數密碼
//		sql replace				: sql replace
//		ChkUrl					: 轉換 https 為 http
//		GetxRequest				: 篩選 Request 內容
//		GetSort					: 顯示排序圖示
//	媒體
//		DspVideoiframe			: 重組 Video iframe (目前包含 Youtube, vimeo 影音平台)
//		GetVideoImg				: 取得 Video 影像圖	(目前包含 Youtube, vimeo 影音平台)
//		GetVideoID				: 取得 Video ID 	(目前包含 Youtube, vimeo 影音平台)
//		GetYoutubeTitile		: 取得 Youtube 標題
//	文字
//		GetTitle				: 指定要擷取的最少字數, 低於最少字數會以最少字數抓取
//		DspString				: replace 標籤 (簡易編輯器使用)
//	數值
//	系統
//		DateLog					: Log
//		logActype				: DateLog 副程式(解譯 log 動作)
//	圖片處理
//		ImageResize				: 壓縮圖片
//		getResizePercent		: ImageResize 副程式 (抓取要縮圖的比例)
//	後台
//		GetPG_NM				: 取得後台應用程式名稱
//		GetCookie				: 讀取Cookie值，使用此Func可避免Cookie值為Null時的錯誤


//function SetPage($wDataCnt, $wPageSize){
function SetPage($RowCount, $wPageSize, $Sql, $MySql){
	global $gPageCnt, $gCurrentPage, $initRun, $gPageFirst, $gPageLast;
//isset 在此是判斷後方參數是否存在
	if(!isset($_GET["ToPage"])){
	//設定起始頁
		$gCurrentPage = 1;
	} else {
	//確認頁數只能夠是數值資料
		$gCurrentPage = intval($_GET["ToPage"]);
	}
	if($gCurrentPage == 0){
		$gCurrentPage = 1;
	}
//計算總頁數
	$gPageCnt = ceil($RowCount / $wPageSize);
	if($gCurrentPage > $gPageCnt){
		$gCurrentPage = $gPageCnt;
	}
//每頁起始資料序號,以便分次藉由sql語法去取得資料
	$gPageFirst = ($gCurrentPage - 1) * $wPageSize;
	if($gPageFirst < 0){
		$gPageFirst = 0;
	}
//每點任一分頁便執行取得該頁數的資料筆數	
	$Sql .= " LIMIT $gPageFirst, $wPageSize ";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯111誤");
	$gPageLast = $MySql -> db_num_rows($initRun) * $gCurrentPage;
	if($gCurrentPage == $gPageCnt){
		$gPageLast = $RowCount;
	}
	return $initRun;
	
	
	/*
	global $gDataStart, $gDataEnd;
	$wToPage = 0;
	if(GetxRequest("ToPage") != ""){
		$wToPage = int(GetxRequest("ToPage"));
	}
//取得總頁數
	if($wDataCnt % $wPageSize == 0){
		$gPageCnt = $wDataCnt / $wPageSize;
	}else{
		$gPageCnt = ($wDataCnt / $wPageSize) + 1;
	}
	if(GetCookie("ToPage" . $gMainFile) == "0"){
		$gCurrentPage = 1;
	}else if(GetxRequest("ToPage") == "" && GetCookie("ToPage" . $gMainFile) != "0" && GetCookie("ToPage" . $gMainFile) != ""){
		$gCurrentPage = GetCookie("ToPage" . $gMainFile);
	}else if($wToPage < 1){
		$gCurrentPage = 1;
	}else if($wToPage > $gPageCnt){
		$gCurrentPage = $gPageCnt;
	}else{
		$gCurrentPage = $wToPage;
	}
	if($gCurrentPage > $gPageCnt){
		$gCurrentPage = $gPageCnt;
	}
	setcookie("ToPage" . $gMainFile, $gCurrentPage, time() + 3600 * 24);
	$gDataStart = (($gCurrentPage - 1) * $wPageSize) + 1;
	if(($gDataStart + $wPageSize - 1) <= $wDataCnt){
		$gDataEnd = $gDataStart + $wPageSize - 1;
	}else{
		$gDataEnd = $wDataCnt;
	}
	if($gPageCnt <= 0){
		$gDataStart = 0;
		$gDataEnd = -1;
	}
	*/
}

//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 設定時區為台北, 適用 php4,ph5
//	撰寫日期	: 20131122
//	撰寫人員	: JimmyChao 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	if (function_exists('date_default_timezone_set')){
		date_default_timezone_set('Asia/Taipei');										//PHP5設定時區, 在PHP4無法使用 
	} else { 
		putenv("TZ=Asia/Taipei");														//PHP4設定時區的用法 
	} 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 讀取Cookie值，使用此Func可避免Cookie值為Null時的錯誤
//	撰寫日期	: 20131126
//	撰寫人員	: JimmyChao
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function GetCookie($CookieName){
	if($_COOKIE[$CookieName] == ""){
		return "";
	}else{
		return $_COOKIE[$CookieName];
	}
}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得後台應用程式名稱
//	撰寫日期	: 20131126
//	撰寫人員	: JimmyChao
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function GetPG_NM($PG_ID, $MySql){
		$Sql = " Select PG_NM ";
		$Sql .= " From mum03 ";
		$Sql .= " Where PG_ID = '$PG_ID' ";
		$SqlRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
		return $MySql -> db_result($SqlRun);;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 亂數密碼
//	撰寫日期	: 20131122
//	撰寫人員	: JimmyChao 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function generatorPassword(){
		$password_len = 7;
		$password = '';
		$word = 'abcdefghijkmnpqrstuvwxyz!@#$%&ABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
		$len = strlen($word);
		for ($i = 0; $i < $password_len; $i++) {
			$password .= $word[rand() % $len];
		}
		return $password;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: sql replace
//	撰寫日期	: 20131122
//	撰寫人員	: JimmyChao 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function sql_string( $String ){
		if (get_magic_quotes_gpc()) {
			return str_replace("\\'", "''", $String);
		}else {
			return str_replace("'", "''", $String);
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: DateLog
//	撰寫日期	: 20131122
//	撰寫人員	: JimmyChao 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function datelog($AType, $SType, $SNo){
		//$AType	執行動作 (1=新增,2=修改,3=刪除)
		//$SType	資料來源 (01=會員管理,02=管理員管理)
		//$SNo		來源ID
		$fnMySql = new mysql();
		mysql_query('SET NAMES UTF8');
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		$fnSql = " insert into Data_Log (DataLog_No, DataLog_PowerId, DataLog_Date, DataLog_Time, DataLog_Ip, DataLog_AcType, ";
		$fnSql .= " DataLog_Source, DataLog_SourceNo) ";
		$fnSql .= " values (NULL, '" . $_SESSION["OP_Id"] . "', '" . date("Ymd") . "', '" . date("Hms") . "', '$ip', '$AType', ";
		$fnSql .= " '$SType', $SNo) ";
		$fnSqlRun = $fnMySql -> db_query($fnSql) or die("查詢 Query 錯誤fn1");
		mysql_close();
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: DateLog 副程式(解譯 log 動作)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function logActype($i){
		switch($i){
			case '1':
				return '新增';
				break;
			case '2':
				return '修改';
				break;
			case '3':
				return '刪除';
				break;
			case '4':
				return '重設密碼';
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 將日期字串YYYYMMDD轉換成YYYY/MM/DD / 輸出：ex:YYYY/MM/DD
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: MyDateStr		/ String	/ 日期
//				: SymbolStr		/ String	/ 符號樣式例如 : / 或 - (預設值為 / )
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function DspDate($MyDateStr, $MyTypeStr){
		switch (strlen($MyDateStr)){
			case '6':
			case '7':
			case '8':
				return substr($MyDateStr , 0 , 4) . $MyTypeStr . substr($MyDateStr , 4 , 2) . $MyTypeStr . substr($MyDateStr , 6 , 2);
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 將時間字串HHmmSS轉換成HH:mm:SS / 輸出：ex:HH:MM:SS
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: MyTimeStr		/ String	/ 時間
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function DspTime($MyTimeStr,$MyTypeStr){
		switch (strlen($MyTimeStr)){
			case '4':
			case '6':
				return substr($MyTimeStr , 0 , 2) . $MyTypeStr . substr($MyTimeStr , 2 , 2) . $MyTypeStr . substr($MyTimeStr , 4 , 2);
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 指定要擷取的最少字數, 低於最少字數會以最少字數抓取
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: pStr				/ String	/ 原始字串
//				: minChars			/ long		/ 最低字數
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function GetTitle($pStr, $minChars){
		if(strlen($pStr) <= $minChars){
			return $pStr;
		}else{
			return iconv_substr($pStr, 0, $minChars, 'utf-8') . '...'; //substr($pStr , 0 , $minChars); 
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得月份英文名稱
//	撰寫日期	: 20130517
//	撰寫人員	: JimmyChao
//	參數說明	: M				/ String	/ 月份
//				: type			/ Integer	/ 顯示方式 (1=全部, 2=簡寫)
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function MonthChEn($M, $type){
		switch($type){
			case 1:
				switch($M){
					case '01':
						return 'January';
					case '02':
						return 'February';
					case '03':
						return 'March';
					case '04':
						return 'April';
					case '05':
						return 'May';
					case '06':
						return 'June';
					case '07':
						return 'July';
					case '08':
						return 'August';
					case '09':
						return 'September';
					case '10':
						return 'October';
					case '11':
						return 'November';
					case '12':
						return 'December';
				}
				break;
			case 2:
				switch($M){
					case '01':
						return 'Jan';
					case '02':
						return 'Feb';
					case '03':
						return 'Mar';
					case '04':
						return 'Apr';
					case '05':
						return 'May';
					case '06':
						return 'Jun';
					case '07':
						return 'Jul';
					case '08':
						return 'Aug';
					case '09':
						return 'Sep';
					case '10':
						return 'Oct';
					case '11':
						return 'Nov';
					case '12':
						return 'Dec';
				}
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得指定時間的星期(幾)字串 / 輸出：日-六 String
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: StrDateW		/ Integer	/ 星期
//				: lang			/ Integer	/ 顯示方式 (ch=中文, en=英文)
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function OnWeek($StrDateW, $lang){
		switch($lang){
			case 'ch':	
				switch($StrDateW){
					case '0':
						return '日';
					case '1':
						return '一';
					case '2':
						return '二';
					case '3':
						return '三';
					case '4':
						return '四';
					case '5':
						return '五';
					case '6':	
						return '六';		
					default:
						return '';													
				}
			case 'en':
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 重組 Video iframe (目前包含 Youtube, vimeo 影音平台)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: YouTubePath	/ String	/ Video 嵌入碼
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function DspVideoiframe($VideoPath){
		switch(true){
			case strpos($VideoPath, "youtu"):
				switch(true){
					case iconv_substr($VideoPath, 0, 15, 'utf-8') == "http://youtu.be":
						return '<iframe width="380" height="300" src="http://www.youtube.com/embed/' . iconv_substr($VideoPath , 16 , 11, 'utf-8' ) . '" frameborder="0" ></iframe>';
						break;
					case iconv_substr($VideoPath , 0 , 22, 'utf-8' ) == "http://www.youtube.com":
						return '<iframe width="380" height="300" src="http://www.youtube.com/embed/' . iconv_substr($VideoPath , 31 , 11, 'utf-8' ) . '" frameborder="0" ></iframe>';
						break;
					case iconv_substr($VideoPath , 0 , 23, 'utf-8' ) == "https://www.youtube.com":
						return '<iframe width="380" height="300" src="http://www.youtube.com/embed/' . iconv_substr($VideoPath , 31 , 11, 'utf-8' ) . '" frameborder="0" ></iframe>';
						break;
					case iconv_substr($VideoPath , 0 , 7, 'utf-8' ) == "<iframe":
						return '<iframe width="380" height="300" src="http://www.youtube.com/embed/' . GetVideoID($VideoPath) . '" frameborder="0" ></iframe>';
						break;
					default:
						return '';
						break;
				}
				break;
			case strpos($VideoPath, "vimeo"):
				switch(true){
					case iconv_substr($VideoPath, 0, 17, 'utf-8' ) == "http://vimeo.com/":
						return '<iframe src="//player.vimeo.com/video/' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '?title=0&amp;byline=0&amp;portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
						break;
					case iconv_substr($VideoPath, 0, 7, 'utf-8') == "<iframe":
						return '<iframe src="//player.vimeo.com/video/' . GetVideoID($VideoPath) . '?title=0&amp;byline=0&amp;portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
						//return $VideoPath;
						break;
					default:
						return '<iframe src="//player.vimeo.com/video/' . $VideoPath . '?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
						break;
				}
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得 Video 影像圖 (目前包含 Youtube, vimeo 影音平台)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: YouTubePath	/ String	/ Video 嵌入碼
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function GetVideoImg($VideoPath){
		switch(true){
			case strpos($VideoPath, "youtu"):
				switch(true){
					case iconv_substr($VideoPath, 0, 15, 'utf-8') == "http://youtu.be":
//						return 'http://img.youtube.com/vi/' . iconv_substr($VideoPath , 16 , 11, 'utf-8' ) .'/0.jpg';
						return '<img alt="" src="http://i.ytimg.com/vi/' . iconv_substr($VideoPath , 16 , 11, 'utf-8' ) . '/0.jpg" />';
						break;
					case iconv_substr($VideoPath , 0 , 22, 'utf-8' ) == "http://www.youtube.com":
//						return 'http://img.youtube.com/vi/' . iconv_substr($VideoPath , 31 , 11, 'utf-8' ) .'/0.jpg';
						return '<img alt="" src="http://i.ytimg.com/vi/' . iconv_substr($VideoPath , 31 , 11, 'utf-8' ) . '/0.jpg" />';
						break;
					case iconv_substr($VideoPath , 0 , 7, 'utf-8' ) == "<iframe":
//						return 'http://img.youtube.com/vi/' . GetVideoID($VideoPath) .'/0.jpg';
						return '<img alt="" src="http://i.ytimg.com/vi/' . GetVideoID($VideoPath) . '/0.jpg" />';
						break;
					default:
						return '<img alt="" src="http://i.ytimg.com/vi/' . $VideoPath . '/0.jpg" />';
						break;
				}
				break;
			case strpos($VideoPath, "vimeo"):
				switch(true){
					case iconv_substr($VideoPath, 0, 17, 'utf-8' ) == "http://vimeo.com/":
						$imgid =iconv_substr($VideoPath, 37, 9, 'utf-8');
						$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
						return $hash[0]['thumbnail_large']; 
//						return '<img src="https://i.vimeocdn.com/video' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '_640.jpg" data-src-deferred="https://i.vimeocdn.com/video/' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '_640.jpg" data-srcset-deferred="https://i.vimeocdn.com/video/' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '_1280.jpg 2x" alt="">';
						break;
					case iconv_substr($VideoPath, 0, 7, 'utf-8') == "<iframe":
						$imgid = GetVideoID($VideoPath);
						$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
						return $hash[0]['thumbnail_large']; 
//						return '<img src="https://i.vimeocdn.com/video' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '_640.jpg" data-src-deferred="https://i.vimeocdn.com/video/' . GetVideoID($VideoPath) . '_640.jpg" data-srcset-deferred="https://i.vimeocdn.com/video/' . GetVideoID($VideoPath) . '_1280.jpg 2x" alt="">';
						break;
					default:
						$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$VideoPath.php"));
						return $hash[0]['thumbnail_large']; 
//						return '<img src="https://i.vimeocdn.com/video' . iconv_substr($VideoPath, 37, 9, 'utf-8') . '_640.jpg" data-src-deferred="https://i.vimeocdn.com/video/' . $VideoPath . '_640.jpg" data-srcset-deferred="https://i.vimeocdn.com/video/' . $VideoPath . '_1280.jpg 2x" alt="">';
						break;
				}
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得 Video ID (目前包含 Youtube, vimeo 影音平台)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: YouTubePath	/ String	/ Video 嵌入碼
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function GetVideoID($VideoPath){
		switch(true){
			case strpos($VideoPath, "youtu"):
				switch(true){
					case iconv_substr($VideoPath, 0, 7, 'utf-8') == "<iframe":
						$str1 = strpos($VideoPath, 'src="');
						$str2 = strpos(iconv_substr($VideoPath , $str1 + 5 , strlen($VideoPath) , 'utf-8' ),'/embed/');
						$str3 = strpos(iconv_substr($VideoPath , $str1 + $str2 + 5 + 7 , strlen($VideoPath) , 'utf-8' ),'"');
						$ID = iconv_substr($VideoPath , $str1 + $str2 + 5 + 7 , $str3 , 'utf-8');
						$ID = str_replace("?rel=0", "", $ID);
						return $ID;
						break;
					case iconv_substr($VideoPath, 0, 15, 'utf-8') == "http://youtu.be":
						return iconv_substr($VideoPath , 16 , 11, 'utf-8');
						break;
					case iconv_substr($VideoPath , 0 , 22, 'utf-8' ) == "http://www.youtube.com":
						return iconv_substr($VideoPath , 31 , 11, 'utf-8');
						break;
					default:
						return '';
				}
				break;
			case strpos($VideoPath, "vimeo"):
			/*
				switch(true){
					case iconv_substr($VideoPath, 0, 7, 'utf-8') == "<iframe":
						$str1 = strpos($VideoPath, '/video/') + 7;
						$ID = iconv_substr($VideoPath, $str1, 8, 'utf-8');
						return $ID;
						break;
					case iconv_substr($VideoPath, 0, 17, 'utf-8' ) == "http://vimeo.com/":
						return iconv_substr($VideoPath, 37, 9, 'utf-8');
						break;
				}
			*/
				$regex = '~
				# Match Vimeo link and embed code
				(?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
				(?:                             # Group vimeo url
						https?:\/\/             # Either http or https
						(?:[\w]+\.)*            # Optional subdomains
						vimeo\.com              # Match vimeo.com
						(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
						\/                      # Slash before Id
						([0-9]+)                # $1: VIDEO_ID is numeric
						[^\s]*                  # Not a space
				)                               # End group
				"?                              # Match end quote if part of src
				(?:[^>]*></iframe>)?            # Match the end of the iframe
				(?:<p>.*</p>)?                  # Match any title information stuff
				~ix';	
				preg_match( $regex, $VideoPath, $matches );
				return $matches[1];
				break;
		}
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得 Youtube 標題
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: str			/ String	/ YouTube 嵌入碼
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function GetYoutubeTitile($str){
		$YoutubeStr = DspVideoiframe($str);
		$YoutubeStr = GetVideoID($str);
			$YoutubeStr = file_get_contents('http://www.youtube.com/watch?v=' . $YoutubeStr);
			$Youtubehtml = htmlspecialchars($YoutubeStr);
			$str1 = strpos($Youtubehtml,'title');
			$str2 = substr($Youtubehtml,$str1 + 5 ,strlen($Youtubehtml));
			$str3 = strpos($str2,'/title');
			$str4 = substr($Youtubehtml,$str1 + 5,$str3);
			$str4 = str_replace('&lt;','',$str4);
			$str4 = str_replace('&gt;','',$str4);
			return $str4;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: replace 標籤 (簡易編輯器使用)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: str			/ String	/ replace 文字
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function DspString($str){
		$str = str_replace('[b]','<b>',$str);
		$str = str_replace('[/b]','</b>',$str);
		$str = str_replace('[i]','<i>',$str);
		$str = str_replace('[/i]','</i>',$str);
		$str = str_replace('[u]','<u>',$str);
		$str = str_replace('[/u]','</u>',$str);
		$str = str_replace('[strike]','<strike>',$str);
		$str = str_replace('[/strike]','</strike>',$str);
		$str = nl2br($str);
		return $str;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取代 PHP Request 物件使用
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: getStr		/ String	/ replace 文字
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function Xrequest($getStr){
		if(trim($getStr) != ''){
			$getStr = str_replace("'","＂",$getStr);
			$getStr = str_replace("'--","",$getStr);
			$getStr = str_replace("' --","",$getStr);
		}else if(trim($getStr) == ''){
			$getStr = trim($getStr);
		}
		return $getStr;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 壓縮圖片
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: from_filename	/ String	/ 來源路徑, 檔名, ex: /tmp/xxx.jpg
//				: save_filename	/ String	/ 縮圖完要存的路徑, 檔名, ex: /tmp/ooo.jpg
//				: in_width		/ Integer	/ 縮圖預定寬度
//				: in_height		/ Integer	/ 縮圖預定高度
//				: quality		/ Integer	/ 縮圖品質(1~100)
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
/**
 The MIT License

 Copyright (c) 2007 <Tsung-Hao>

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 *
 * 抓取要縮圖的比例, 下述只處理 jpeg
 * $from_filename : 來源路徑, 檔名, ex: /tmp/xxx.jpg
 * $save_filename : 縮圖完要存的路徑, 檔名, ex: /tmp/ooo.jpg
 * $in_width : 縮圖預定寬度
 * $in_height: 縮圖預定高度
 * $quality  : 縮圖品質(1~100)
 *
 * Usage:
 *   ImageResize('ram/xxx.jpg', 'ram/ooo.jpg');
 */
function ImageResize($from_filename, $save_filename, $in_width=400, $in_height=300, $quality=100){
    $allow_format = array('jpeg', 'png', 'gif');
    $sub_name = $t = '';

    // Get new dimensions
    $img_info = getimagesize($from_filename);
    $width    = $img_info['0'];
    $height   = $img_info['1'];
    $imgtype  = $img_info['2'];
    $imgtag   = $img_info['3'];
    $bits     = $img_info['bits'];
    $channels = $img_info['channels'];
    $mime     = $img_info['mime'];

    list($t, $sub_name) = split('/', $mime);
    if ($sub_name == 'jpg') {
        $sub_name = 'jpeg';
    }

    if (!in_array($sub_name, $allow_format)) {
        return false;
    }

    // 取得縮在此範圍內的比例
    $percent = getResizePercent($width, $height, $in_width, $in_height);
    $new_width  = $width * $percent;
    $new_height = $height * $percent;

    // Resample
    $image_new = imagecreatetruecolor($new_width, $new_height);

    // $function_name: set function name
    //   => imagecreatefromjpeg, imagecreatefrompng, imagecreatefromgif
    /*
    // $sub_name = jpeg, png, gif
    $function_name = 'imagecreatefrom' . $sub_name;

    if ($sub_name=='png')
        return $function_name($image_new, $save_filename, intval($quality / 10 - 1));

    $image = $function_name($from_filename); //$image = imagecreatefromjpeg($from_filename);
    */
    $image = imagecreatefromjpeg($from_filename);

    imagecopyresampled($image_new, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    return imagejpeg($image_new, $save_filename, $quality);
}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 壓縮圖片副程式 (抓取要縮圖的比例)
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: source_w		/ Integer	/ 來源圖片寬度
//				: source_h		/ Integer	/ 來源圖片高度
//				: inside_w		/ Integer	/ 縮圖預定寬度
//				: inside_h		/ Integer	/ 縮圖預定高度
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
/*
 * Test:
 *   $v = (getResizePercent(1024, 768, 400, 300));
 *   echo 1024 * $v . "\n";
 *   echo  768 * $v . "\n";
*/
function getResizePercent($source_w, $source_h, $inside_w, $inside_h)
{
    if ($source_w < $inside_w && $source_h < $inside_h) {
        return 1; // Percent = 1, 如果都比預計縮圖的小就不用縮
    }
    $w_percent = $inside_w / $source_w;
    $h_percent = $inside_h / $source_h;
    return ($w_percent > $h_percent) ? $h_percent : $w_percent;
}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 轉換 https 為 http
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: Url			/ String	/ 網址
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function ChkUrl($Url){
	if($Url != ''){
		$Url = str_replace('https://', 'http://', $Url);
		if(substr($Url, 0, 7) != 'http://'){
			return 'http://' . $Url;
		}else{
			return $Url;
		}
	}
}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 篩選 Request 內容
//	撰寫日期	: 20131125
//	撰寫人員	: JimmyChao 整理
//	參數說明	: T				/ String	/ 參數名稱
//				: len			/ Integer	/ 限制長度
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function GetxRequest($T){//, $len
//	if($len == ''){
		return sql_string(Xrequest($_REQUEST[$T]));
//	}else{
//		return substr(sql_string(Xrequest($_REQUEST[$T])),0,$len);
//		return iconv_substr(sql_string(Xrequest($_REQUEST[$T])),0,$len,'utf-8');
//	}

}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 顯示排序圖示
//	撰寫日期	: 20140616
//	撰寫人員	: JimmyChao 整理
//	參數說明	: gMainFile		/ String	/ 連結檔名
//				: C				/ String	/ 排序欄位
//				: sC			/ String	/ 排序欄位
//				: sDs			/ String	/ 排序方式
//				: coni			/ String	/ 網頁其他參數
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function GetSort($gMainFile, $C, $sC, $sDs, $coni){
	if ($C == $sC){
		if ($sDs == ''){
			echo '<a href="' . $gMainFile . '.php?' . $coni . '&sC=' . $C . '&sDs=desc" class="down"></a>';
		}else{
			echo '<a href="' . $gMainFile . '.php?' . $coni . '&sC=' . $C . '" class="up"></a>';
		}
	}else{
		echo '<a href="' . $gMainFile . '.php?' . $coni . '&sC=' . $C . '" class="up"></a>';								
	}
}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 取得 random 檔名 (SY + 日期 + 時間 + 隨機三位數字)
//	撰寫日期	: 20151117
//	撰寫人員	: Momo 整理
//	參數說明	: 
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function getRndFileName(){
		$getRndFileName = "SY";
		$getRndFileName .= date("Ymd");
		$getRndFileName .= date("His");
		$getRndFileName .= '-'.substr(sprintf("%04d",rand(1, 1000)),-3);
		return $getRndFileName;
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 建立cookie
//	撰寫日期	: 20151119
//	撰寫人員	: Momo 整理
//	參數說明	: cookieNM	/	String	/	cookie命名
//			： cookieStr	/	String	/	cookie內容	(刪除時給空值)
//			： cookieTime	/	Integer	/	cookie存留時間
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function CreateCookie($cookieNM,$cookieStr,$cookieTime){
		setcookie($cookieNM, $cookieStr,time()+36000,'/');//, time()+$cookieTime
	}
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//	功能簡述	: 偵測轉跳URL
//	撰寫日期	: 20151119
//	撰寫人員	: Momo 整理
//	參數說明	: ID	/	String	/	流程編號
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	function ProcessURL($ID){
		switch($ID){
			case 1://前測
			//$URL = "/account-class-lqa.php";
				$URL = "/account-class-exam.php";
			break;
			case 2://影片
				$URL = "/account-class-single.php";
			break;
			case 3://後測
				$URL = "/account-class-exam.php";
			break;
			case 4://作業
				$URL = "/account-reply.php";
			break;
			case 5://問卷
				$URL = "/account-class-qqa.php";
			break;
			case 6://
				$URL = "/account-class-lqa.php";
			break;
			case 7://
				$URL = "/account-score.php";
			break;
			case 8://
				$URL = "/account-class-lqa2.php";
			break;
			case ''://課程主頁
				$URL = "/account-class.php";
			break;
		}
			return $URL;
	} 

//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//    功能簡述    : 加密
//    撰寫日期    : 20170712
//    撰寫人員    : T 整理
//    參數說明    : Str    /    String    /    
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function ENCCode($Str) {
    $secretKey = pack('H*', SECRETKEY);
	$encrypted = base64_encode(trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secretKey, $Str, MCRYPT_MODE_ECB)));
	return trim($encrypted);
}

//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
//    功能簡述    : 解密
//    撰寫日期    : 20170712
//    撰寫人員    : T 整理
//    參數說明    : Str    /    String    /    
//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
function DECCode($Str) {
	$secretKey = pack('H*', SECRETKEY);
	$decoded = base64_decode($Str);
	$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secretKey, $decoded, MCRYPT_MODE_ECB));
	return trim($decrypted);
}

