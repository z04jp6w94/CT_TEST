<?php
// 資料庫
	@define (Host,'chiliman-php-dep.chiliman.com.tw:3306');																		// 資料庫主機
	@define (DBName,'ctdb');																// 資料庫名稱
	@define (UserName,'ctUser');															// 資料庫帳號
	@define (PassWord,'s5emfe96uftm');																// 資料庫密碼
// 目錄
	@define('root_path', dirname(__FILE__));														// root 路徑
	@define('TimeZone', date_default_timezone_set('Asia/Taipei'));								// 時區
	@define('temp','/UpLoad/temp/');
	@define('BackOfficeUIImg','/UpLoad/BackOffice/UI/');
	//ckeditor 路徑
	@define('Template', "/UpLoad/Template/");														    //課表檔案 路徑
	@define('Course', "/UpLoad/Course/");														    	//課程記錄 路徑
	@define('Student', "/UpLoad/Student/");														    	//學生圖片 路徑
	@define('TeachingPlan', "/UpLoad/TeachingPlan/");													//教案圖片 路徑
	
// 系統
	@define('MainSite', 'http://' . $_SERVER['HTTP_HOST'] . '/');									// 網址
	@define('MetaTitle', "擴思訊");															// 網頁標題
// Mail
	@define('SendMail', "bin@chiliman.com.tw");														// 寄件mail
	@define('SendMailBCC', "binbin.gm@gmail.com");													// 信件副本
//
	define('SECRETKEY', 'a9c4dbac460019a74b256c207278e1e2');	
	
// 載入函式庫
	if(file_exists(root_path . "/Include/mysql.class.php")){
		include root_path . "/Include/mysql.class.php";
	}
	if(file_exists(root_path . "/Include/ComFun.php")){
		include root_path . "/Include/ComFun.php";
	}
	if(file_exists(root_path . "/Include/ComFun_Security.php")){
		include root_path . "/Include/ComFun_Security.php";
	}
	if(file_exists(root_path . "/Include/ComFun_IO.php")){
		include root_path . "/Include/ComFun_IO.php";
	}
	if(file_exists(root_path . "/Maintain/Menu/GetTreeView.php")){
		include root_path . "/Maintain/Menu/GetTreeView.php";
	}
