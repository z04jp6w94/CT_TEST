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
//	$PG_ID = $_COOKIE["M_PG_ID"];															//程式	 ID (GPID)
//資料庫連線
	$MySql = new mysql();
//使用權限
//	Chk_Login($USER_ID, $PG_ID);															//檢查是否有登入後台，並取得允許執行的權限
//	ChkFunc_BeforeRunPG(2, $PG_ID, $USER_ID, $MySql);										//程式使用權限 1.查詢 2.新增 3.修改 4.刪除
//應用程式名稱
//	$PG_NM = GetPG_NM($PG_ID, $MySql);	
//代碼 訓練目標
	$Sql = " select * from OptionalItem ";
	$Sql .= " where 1 = 1 ";
	$Sql .= " and OptionalId = 'ClassFrequency' ";
	$Sql .= " and Language = 'zh-tw'";
	$initRun = $MySql -> db_query($Sql) or die("查詢 Query 錯誤");
	$OptionAry = $MySql -> db_array($Sql,6);
	
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
<title>擴思訊 Cross Think 後台 使用者管理-客戶 檢視使用者</title>
<?php include_once(root_path . "/SystemMaintain/CommonPage/mutual_css.php");?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="../../Js/ComFun.js"></script>
    
    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
    <!-- Ignite UI Required Combined JavaScript Files -->
    <script src="http://cdn-na.infragistics.com/igniteui/2016.2/latest/js/infragistics.core.js"></script>
    <script src="http://cdn-na.infragistics.com/igniteui/2016.2/latest/js/infragistics.lob.js"></script>
    <!-- Javascript Excel Library -->
    <script src="http://cdn-na.infragistics.com/igniteui/2016.2/latest/js/modules/infragistics.documents.core.js"></script>
    <script src="http://cdn-na.infragistics.com/igniteui/2016.2/latest/js/modules/infragistics.excel.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			
		});

		function ChkForm(){
			
			if(IsEmpty(_Form.Account,'帳號')){
			}else{
				_Form.submit();
			}
		}
		


	</script>
</head>

<body>
<div class="main">
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
                <li class="current"><span class="hor-box-text">新增課表範本</span></li><!-- 如果沒有，就不寫入這個LI -->
<?php include_once(root_path . "/SystemMaintain/CommonPage/header_system_helper.php");?>                
            </ul>
            <div class="clearFloat"></div>
        </div>
        <!-- 功能軌跡（麵包屑） end -->
    </div>
    <div class="doc">
        <div class="timeTableOperation themeSet1">
        <form method="post" enctype="multipart/form-data">
        	<table cellpadding="0" cellspacing="0" class="leftHeader import">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">課表名稱：</span></th>
                    <td valign="middle"><input type="text" id="TemplateName" name="TemplateName" class="name sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle">
                    	<?php for($i=0;$i<count($OptionAry);$i++){?>
                        	<label><input type="radio" id="ClassFrequencyId" name="ClassFrequencyId" value="<?php echo $OptionAry[$i][0];?>"><span class="sized-text-1"><?php echo $OptionAry[$i][4]?></span>&nbsp;</label>
                        <?php }?>
                        <!--<label><input type="radio"><span class="sized-text-1">基礎60天（一週至少3次）</span></label>
                        <label><input type="radio"><span class="sized-text-1">精要16天（一週至少1次）</span></label>
                        <label><input type="radio"><span class="sized-text-1">菁英10天（一天1次）</span></label>-->
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">匯入檔案：</span></th>
                    <td valign="middle">
                    	<div class="fileSelect">
                        	<!--<input type="file" name="importFile" id="importFile" class="trueFile" onchange="test2();">-->
                    		<!--<input type="file" name="importFile" id="importFile" class="trueFile" onchange="javascript:document.getElementById('fileContent').value = document.getElementById('importFile').value;"><!-- 注意：將input[file] 選取檔案後的URI字串導出 -->
                            <!--<label for="importFile" class="customBtn"><span class="hiddenItem">匯入</span></label><!-- 注意：input[file] 的id與name 須與label[for]內相同 -->
                            <!--<input type="text" id="fileContent" class="url sized-text-normal" readonly>-->
                            <input type="file" name="fileUpload" id="fileUpload" />

                            <!--<span class="fakeBtn sized-text-normal">匯入</span>-->
                            <div class="clearFloat"></div>
                        </div>
					</td>
                </tr>
            </table>
    <script>
        $(function () {
            $("#fileUpload").on("change", function () {
                var excelFile,
                    fileReader = new FileReader();

                $("#result").hide();

                fileReader.onload = function (e) {
                    var buffer = new Uint8Array(fileReader.result);
					
                    $.ig.excel.Workbook.load(buffer, function (workbook) {
                        var column, row, newRow, cellValue, columnIndex, i,
                            worksheet = workbook.worksheets(0),
                            columnsNumber = 0,
                            gridColumns = [],
                            data = [],
                            worksheetRowsCount;

                        // Both the columns and rows in the worksheet are lazily created and because of this most of the time worksheet.columns().count() will return 0
                        // So to get the number of columns we read the values in the first row and count. When value is null we stop counting columns:
                        while (worksheet.rows(0).getCellValue(columnsNumber)) {
                            columnsNumber++;
                        }

                        // Iterating through cells in first row and use the cell text as key and header text for the grid columns
                        for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                            column = worksheet.rows(0).getCellText(columnIndex);
                            gridColumns.push({ headerText: column, key: column });
                        }

                        // We start iterating from 1, because we already read the first row to build the gridColumns array above
                        // We use each cell value and add it to json array, which will be used as dataSource for the grid
                        for (i = 1, worksheetRowsCount = worksheet.rows().count() ; i < worksheetRowsCount; i++) {
                            newRow = {};
                            row = worksheet.rows(i);

                            for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                                cellValue = row.getCellText(columnIndex);
                                newRow[gridColumns[columnIndex].key] = cellValue;
                            }

                            data.push(newRow);
                        }

                        // we can also skip passing the gridColumns use autoGenerateColumns = true, or modify the gridColumns array
                        createGrid(data, gridColumns);
                    }, function (error) {
                        $("#result").text("The excel file is corrupted.");
                        $("#result").show(1000);
                    });
                }

                if (this.files.length > 0) {
                    excelFile = this.files[0];
                    if (excelFile.type === "application/vnd.ms-excel" || excelFile.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || (excelFile.type === "" && (excelFile.name.endsWith("xls") || excelFile.name.endsWith("xlsx")))) {
                        fileReader.readAsArrayBuffer(excelFile);
                    } else {
                        $("#result").text("The format of the file you have selected is not supported. Please select a valid Excel file ('.xls, *.xlsx').");
                        $("#result").show(1000);
                    }
                }

            })
        })

        function createGrid(data, gridColumns) {
            if ($("#grid1").data("igGrid") !== undefined) {
                $("#grid1").igGrid("destroy");
            }

            $("#grid1").igGrid({
                columns: gridColumns,
                autoGenerateColumns: true,
                dataSource: data,
                width: "100%"
            });
        }
    </script>            
            <div class="spacingBlock hr"></div>
            
            <ul class="importState">
            	<li><span class="hor-box-text-normal">匯入比對結果</span></li>
                <li class="inNote"><span class="hor-box-text-normal note">*紅字代表無法比對到正確到足以建立的教案名稱（Ex:名稱錯誤、有空格、全形半形...等差異），須重新確認後重新整筆匯入</span></li><!-- 如果匯入正確，不寫入這個LI -->
                <li class="inError"><a href="#" class="hor-box-text-normal">資料不正確</a></li>
                <li class="inRight"><a href="#" class="hor-box-text-normal">資料正確</a></li>
            </ul>
            <div class="clearFloat"></div>
			
            <div class="spacingBlock"></div>
            <div class="spacingBlock hr-dot"></div>
            <div class="spacingBlock"></div>
            
            <div id="result"></div>
        	<table id="grid1"></table>
             
<!--            	<tr>
                	<th><span class="hor-box-text-normal">&nbsp;</span></th>
                    <th><span class="hor-box-text-normal">教案1</span></th>
                    <th><span class="hor-box-text-normal">教案2</span></th>
                    <th><span class="hor-box-text-normal">教案3</span></th>
                </tr>
                <tr class="odd">
                	<td><span class="hor-box-text-normal">Day1</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>
                <tr class="even">
                	<td><span class="hor-box-text-normal">Day2</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>
                <tr class="odd">
                	<td><span class="hor-box-text-normal">Day3</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal">模擬運動(一)</span></td>
                    <td><span class="hor-box-text-normal"><strong class="errorItem">轉球運動(一)</strong></span></td>
                </tr>
                <tr class="even">
                	<td><span class="hor-box-text-normal">Day4</span></td>
                    <td><span class="hor-box-text-normal">三段式(一)</span></td>
                    <td><span class="hor-box-text-normal"><strong class="errorItem">模擬運動(一)</strong></span></td>
                    <td><span class="hor-box-text-normal">轉球運動(一)</span></td>
                </tr>-->
            </table>
            </form>
            <div class="spacingBlock"></div>
            <div><span class="hor-box-text-normal"><button type="button" class="optionSaveBtn btnDisabled" disabled><span class="sized-text-1">儲存</span></button></span></div><!-- 按鈕不可使用 -->
            <!-- <div><span class="hor-box-text-normal"><button type="button" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></span></div> 按鈕可以使用 -->
        </div>
    </div>
</div>
</body>
</html>
