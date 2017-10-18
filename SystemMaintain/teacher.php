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
//		撰寫日期：20161111
//		程式功能：ct / 教師管理 / 列表
//		使用參數：None
//		資　　料：sel：per
//		　　　　　ins：None
//		　　　　　del：None
//		　　　　　upt：None
//		修改人員：
//		修改日期：
//		註　　解：
//*****************************************************************************************
	header ('Content-Type: text/html; charset=utf-8');
	date_default_timezone_set('Asia/Taipei');
	session_start();
//定義全域參數

//函式庫
	include_once($_SERVER['DOCUMENT_ROOT'] . "/config.ini.php");	
//資料庫連線
	$MySql = new mysql();
//定義一般參數

//資料庫
//選項代碼
	$Sql = " Select * from Optional ";
	$T1 = $MySql -> db_query($Sql) or die("查詢錯誤");
	$T1Ary = $MySql -> db_array($Sql,2);
//選項代碼明細
	$Sql = " Select * from OptionalItem ";
	$T2 = $MySql -> db_query($Sql) or die("查詢錯誤");
	$T2Ary = $MySql -> db_array($Sql,2);
	
//關閉資料庫連線
	$MySql -> db_close();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>教師管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="resources/css/jquery-ui-themes.css" type="text/css" rel="stylesheet"/>
    <link href="resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="files/教師管理_1/styles.css" type="text/css" rel="stylesheet"/>
    <script src="resources/scripts/jquery-1.7.1.min.js"></script>
    <script src="resources/scripts/jquery-ui-1.8.10.custom.min.js"></script>
    <script src="resources/scripts/axure/axQuery.js"></script>
    <script src="resources/scripts/axure/globals.js"></script>
    <script src="resources/scripts/axutils.js"></script>
    <script src="resources/scripts/axure/annotation.js"></script>
    <script src="resources/scripts/axure/axQuery.std.js"></script>
    <script src="resources/scripts/axure/doc.js"></script>
    <script src="data/document.js"></script>
    <script src="resources/scripts/messagecenter.js"></script>
    <script src="resources/scripts/axure/events.js"></script>
    <script src="resources/scripts/axure/recording.js"></script>
    <script src="resources/scripts/axure/action.js"></script>
    <script src="resources/scripts/axure/expr.js"></script>
    <script src="resources/scripts/axure/geometry.js"></script>
    <script src="resources/scripts/axure/flyout.js"></script>
    <script src="resources/scripts/axure/ie.js"></script>
    <script src="resources/scripts/axure/model.js"></script>
    <script src="resources/scripts/axure/repeater.js"></script>
    <script src="resources/scripts/axure/sto.js"></script>
    <script src="resources/scripts/axure/utils.temp.js"></script>
    <script src="resources/scripts/axure/variables.js"></script>
    <script src="resources/scripts/axure/drag.js"></script>
    <script src="resources/scripts/axure/move.js"></script>
    <script src="resources/scripts/axure/visibility.js"></script>
    <script src="resources/scripts/axure/style.js"></script>
    <script src="resources/scripts/axure/adaptive.js"></script>
    <script src="resources/scripts/axure/tree.js"></script>
    <script src="resources/scripts/axure/init.temp.js"></script>
    <script src="files/教師管理_1/data.js"></script>
    <script src="resources/scripts/axure/legacy.js"></script>
    <script src="resources/scripts/axure/viewer.js"></script>
    <script src="resources/scripts/axure/math.js"></script>
    <script type="text/javascript">
      $axure.utils.getTransparentGifPath = function() { return 'resources/images/transparent.gif'; };
      $axure.utils.getOtherPath = function() { return 'resources/Other.html'; };
      $axure.utils.getReloadPath = function() { return 'resources/reload.html'; };
    </script>
  </head>
  <body>
    <div id="base" class="">

      <!-- Unnamed (Rectangle) -->
      <div id="u7562" class="ax_default shape">
        <img id="u7562_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7563" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7564" class="ax_default shape">
        <img id="u7564_img" class="img " src="images/教師管理_1/u7564.png"/>
        <!-- Unnamed () -->
        <div id="u7565" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7566" class="ax_default text_field">
        <input id="u7566_input" type="search" value=""/>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7567" class="ax_default shape">
        <div id="u7567_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7568" class="text" style="visibility: visible;">
          <p><span>新增教師</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7569" class="ax_default heading_2">
        <div id="u7569_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7570" class="text" style="visibility: visible;">
          <p><span>教師姓名</span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7571" class="ax_default shape">
        <div id="u7571_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7572" class="text" style="visibility: visible;">
          <p><span>Search</span></p>
        </div>
      </div>

      <!-- Unnamed (Table) -->
      <div id="u7573" class="ax_default table">

        <!-- Unnamed (Table Cell) -->
        <div id="u7574" class="ax_default table_cell">
          <img id="u7574_img" class="img " src="images/教師管理_1/u7574.png"/>
          <!-- Unnamed () -->
          <div id="u7575" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7576" class="ax_default table_cell">
          <img id="u7576_img" class="img " src="images/教師管理_1/u7576.png"/>
          <!-- Unnamed () -->
          <div id="u7577" class="text" style="visibility: visible;">
            <p><span>老師姓名</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7578" class="ax_default table_cell">
          <img id="u7578_img" class="img " src="images/教師管理_1/u7578.png"/>
          <!-- Unnamed () -->
          <div id="u7579" class="text" style="visibility: visible;">
            <p><span>連絡電話1</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7580" class="ax_default table_cell">
          <img id="u7580_img" class="img " src="images/教師管理_1/u7578.png"/>
          <!-- Unnamed () -->
          <div id="u7581" class="text" style="visibility: visible;">
            <p><span>聯絡電話2</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7582" class="ax_default table_cell">
          <img id="u7582_img" class="img " src="images/教師管理_1/u7582.png"/>
          <!-- Unnamed () -->
          <div id="u7583" class="text" style="visibility: visible;">
            <p><span>建立者</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7584" class="ax_default table_cell">
          <img id="u7584_img" class="img " src="images/教師管理_1/u7582.png"/>
          <!-- Unnamed () -->
          <div id="u7585" class="text" style="visibility: visible;">
            <p><span>最後修改者</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7586" class="ax_default table_cell">
          <img id="u7586_img" class="img " src="images/教師管理_1/u7586.png"/>
          <!-- Unnamed () -->
          <div id="u7587" class="text" style="visibility: visible;">
            <p><span>最後修改日期</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7588" class="ax_default table_cell">
          <img id="u7588_img" class="img " src="images/教師管理_1/u7588.png"/>
          <!-- Unnamed () -->
          <div id="u7589" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7590" class="ax_default table_cell">
          <img id="u7590_img" class="img " src="images/教師管理_1/u7590.png"/>
          <!-- Unnamed () -->
          <div id="u7591" class="text" style="visibility: visible;">
            <p><span>老師A</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7592" class="ax_default table_cell">
          <img id="u7592_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7593" class="text" style="visibility: visible;">
            <p><span>0212345678</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7594" class="ax_default table_cell">
          <img id="u7594_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7595" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7596" class="ax_default table_cell">
          <img id="u7596_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7597" class="text" style="visibility: visible;">
            <p><span>有權限者A</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7598" class="ax_default table_cell">
          <img id="u7598_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7599" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7600" class="ax_default table_cell">
          <img id="u7600_img" class="img " src="images/教師管理_1/u7600.png"/>
          <!-- Unnamed () -->
          <div id="u7601" class="text" style="visibility: visible;">
            <p><span>2016/03/03 9:41</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7602" class="ax_default table_cell">
          <img id="u7602_img" class="img " src="images/教師管理_1/u7588.png"/>
          <!-- Unnamed () -->
          <div id="u7603" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7604" class="ax_default table_cell">
          <img id="u7604_img" class="img " src="images/教師管理_1/u7590.png"/>
          <!-- Unnamed () -->
          <div id="u7605" class="text" style="visibility: visible;">
            <p><span>老師B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7606" class="ax_default table_cell">
          <img id="u7606_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7607" class="text" style="visibility: visible;">
            <p><span>0212345678</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7608" class="ax_default table_cell">
          <img id="u7608_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7609" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7610" class="ax_default table_cell">
          <img id="u7610_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7611" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7612" class="ax_default table_cell">
          <img id="u7612_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7613" class="text" style="visibility: visible;">
            <p><span>有權限者A</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7614" class="ax_default table_cell">
          <img id="u7614_img" class="img " src="images/教師管理_1/u7600.png"/>
          <!-- Unnamed () -->
          <div id="u7615" class="text" style="visibility: visible;">
            <p><span>2016/03/02 9:41</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7616" class="ax_default table_cell">
          <img id="u7616_img" class="img " src="images/教師管理_1/u7588.png"/>
          <!-- Unnamed () -->
          <div id="u7617" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7618" class="ax_default table_cell">
          <img id="u7618_img" class="img " src="images/教師管理_1/u7590.png"/>
          <!-- Unnamed () -->
          <div id="u7619" class="text" style="visibility: visible;">
            <p><span>老師C</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7620" class="ax_default table_cell">
          <img id="u7620_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7621" class="text" style="visibility: visible;">
            <p><span>0212345678</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7622" class="ax_default table_cell">
          <img id="u7622_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7623" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7624" class="ax_default table_cell">
          <img id="u7624_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7625" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7626" class="ax_default table_cell">
          <img id="u7626_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7627" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7628" class="ax_default table_cell">
          <img id="u7628_img" class="img " src="images/教師管理_1/u7600.png"/>
          <!-- Unnamed () -->
          <div id="u7629" class="text" style="visibility: visible;">
            <p><span>2016/03/01 9:41</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7630" class="ax_default table_cell">
          <img id="u7630_img" class="img " src="images/教師管理_1/u7588.png"/>
          <!-- Unnamed () -->
          <div id="u7631" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7632" class="ax_default table_cell">
          <img id="u7632_img" class="img " src="images/教師管理_1/u7590.png"/>
          <!-- Unnamed () -->
          <div id="u7633" class="text" style="visibility: visible;">
            <p><span>老師D</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7634" class="ax_default table_cell">
          <img id="u7634_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7635" class="text" style="visibility: visible;">
            <p><span>0212345678</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7636" class="ax_default table_cell">
          <img id="u7636_img" class="img " src="images/教師管理_1/u7592.png"/>
          <!-- Unnamed () -->
          <div id="u7637" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7638" class="ax_default table_cell">
          <img id="u7638_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7639" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7640" class="ax_default table_cell">
          <img id="u7640_img" class="img " src="images/教師管理_1/u7596.png"/>
          <!-- Unnamed () -->
          <div id="u7641" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7642" class="ax_default table_cell">
          <img id="u7642_img" class="img " src="images/教師管理_1/u7600.png"/>
          <!-- Unnamed () -->
          <div id="u7643" class="text" style="visibility: visible;">
            <p><span>2016/03/01 9:40</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7644" class="ax_default table_cell">
          <img id="u7644_img" class="img " src="images/教師管理_1/u7644.png"/>
          <!-- Unnamed () -->
          <div id="u7645" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7646" class="ax_default table_cell">
          <img id="u7646_img" class="img " src="images/教師管理_1/u7646.png"/>
          <!-- Unnamed () -->
          <div id="u7647" class="text" style="visibility: visible;">
            <p><span>老師E</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7648" class="ax_default table_cell">
          <img id="u7648_img" class="img " src="images/教師管理_1/u7648.png"/>
          <!-- Unnamed () -->
          <div id="u7649" class="text" style="visibility: visible;">
            <p><span>0212345678</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7650" class="ax_default table_cell">
          <img id="u7650_img" class="img " src="images/教師管理_1/u7648.png"/>
          <!-- Unnamed () -->
          <div id="u7651" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7652" class="ax_default table_cell">
          <img id="u7652_img" class="img " src="images/教師管理_1/u7652.png"/>
          <!-- Unnamed () -->
          <div id="u7653" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7654" class="ax_default table_cell">
          <img id="u7654_img" class="img " src="images/教師管理_1/u7652.png"/>
          <!-- Unnamed () -->
          <div id="u7655" class="text" style="visibility: visible;">
            <p><span>有權限者B</span></p>
          </div>
        </div>

        <!-- Unnamed (Table Cell) -->
        <div id="u7656" class="ax_default table_cell">
          <img id="u7656_img" class="img " src="images/教師管理_1/u7656.png"/>
          <!-- Unnamed () -->
          <div id="u7657" class="text" style="visibility: visible;">
            <p><span>2016/03/01 9:39</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7658" class="ax_default image">
        <img id="u7658_img" class="img " src="images/登入成功_1/u3368.png"/>
        <!-- Unnamed () -->
        <div id="u7659" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7660" class="ax_default image">
        <img id="u7660_img" class="img " src="images/登入成功_1/u3372.png"/>
        <!-- Unnamed () -->
        <div id="u7661" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7662" class="ax_default image">
        <img id="u7662_img" class="img " src="images/登入成功_1/u3356.png"/>
        <!-- Unnamed () -->
        <div id="u7663" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7664" class="ax_default image">
        <img id="u7664_img" class="img " src="images/登入成功_1/u3360.png"/>
        <!-- Unnamed () -->
        <div id="u7665" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7666" class="ax_default image">
        <img id="u7666_img" class="img " src="images/登入成功_1/u3364.png"/>
        <!-- Unnamed () -->
        <div id="u7667" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- 彈出確認視窗 (Dynamic Panel) -->
      <div id="u7668" class="ax_default ax_default_hidden" data-label="彈出確認視窗" style="display: none; visibility: hidden">
        <div id="u7668_state0" class="panel_state" data-label="是否確定刪除">
          <div id="u7668_state0_content" class="panel_state_content">

            <!-- Unnamed (Group) -->
            <div id="u7669" class="ax_default">

              <!-- Unnamed (Rectangle) -->
              <div id="u7670" class="ax_default shape">
                <div id="u7670_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7671" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7672" class="ax_default shape">
                <div id="u7672_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7673" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7674" class="ax_default paragraph">
                <div id="u7674_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7675" class="text" style="visibility: visible;">
                  <p><span>是否確定刪除?</span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7676" class="ax_default shape">
                <div id="u7676_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7677" class="text" style="visibility: visible;">
                  <p><span>確定</span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7678" class="ax_default shape">
                <div id="u7678_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7679" class="text" style="visibility: visible;">
                  <p><span>取消</span></p>
                </div>
              </div>

              <!-- Unnamed (Image) -->
              <div id="u7680" class="ax_default image">
                <img id="u7680_img" class="img " src="images/教案管理/u3534.png"/>
                <!-- Unnamed () -->
                <div id="u7681" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="u7668_state1" class="panel_state" data-label="是否確定停用">
          <div id="u7668_state1_content" class="panel_state_content">

            <!-- Unnamed (Group) -->
            <div id="u7682" class="ax_default">

              <!-- Unnamed (Rectangle) -->
              <div id="u7683" class="ax_default shape">
                <div id="u7683_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7684" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7685" class="ax_default shape">
                <div id="u7685_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7686" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7687" class="ax_default paragraph">
                <div id="u7687_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7688" class="text" style="visibility: visible;">
                  <p><span>是否確定停用?</span></p>
                </div>
              </div>

              <!-- Unnamed (Image) -->
              <div id="u7689" class="ax_default image">
                <img id="u7689_img" class="img " src="images/教案管理/u3534.png"/>
                <!-- Unnamed () -->
                <div id="u7690" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7691" class="ax_default shape">
                <div id="u7691_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7692" class="text" style="visibility: visible;">
                  <p><span>確定</span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7693" class="ax_default shape">
                <div id="u7693_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7694" class="text" style="visibility: visible;">
                  <p><span>取消</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="u7668_state2" class="panel_state" data-label="請先將寶寶設定至其他教師">
          <div id="u7668_state2_content" class="panel_state_content">

            <!-- Unnamed (Group) -->
            <div id="u7695" class="ax_default">

              <!-- Unnamed (Rectangle) -->
              <div id="u7696" class="ax_default shape">
                <div id="u7696_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7697" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7698" class="ax_default shape">
                <div id="u7698_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7699" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7700" class="ax_default shape">
                <div id="u7700_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7701" class="text" style="visibility: visible;">
                  <p><span>確定</span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7702" class="ax_default paragraph">
                <div id="u7702_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7703" class="text" style="visibility: visible;">
                  <p><span>此教師名下還有負責班級A，請先將班級設定至其他教師</span></p>
                </div>
              </div>

              <!-- Unnamed (Image) -->
              <div id="u7704" class="ax_default image">
                <img id="u7704_img" class="img " src="images/教案管理/u3534.png"/>
                <!-- Unnamed () -->
                <div id="u7705" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="u7668_state3" class="panel_state" data-label="是否確定啟用">
          <div id="u7668_state3_content" class="panel_state_content">

            <!-- Unnamed (Group) -->
            <div id="u7706" class="ax_default">

              <!-- Unnamed (Rectangle) -->
              <div id="u7707" class="ax_default shape">
                <div id="u7707_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7708" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7709" class="ax_default shape">
                <div id="u7709_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7710" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7711" class="ax_default paragraph">
                <div id="u7711_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7712" class="text" style="visibility: visible;">
                  <p><span>是否確定啟用?</span></p>
                </div>
              </div>

              <!-- Unnamed (Image) -->
              <div id="u7713" class="ax_default image">
                <img id="u7713_img" class="img " src="images/教案管理/u3534.png"/>
                <!-- Unnamed () -->
                <div id="u7714" class="text" style="display: none; visibility: hidden">
                  <p><span></span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7715" class="ax_default shape">
                <div id="u7715_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7716" class="text" style="visibility: visible;">
                  <p><span>確定</span></p>
                </div>
              </div>

              <!-- Unnamed (Rectangle) -->
              <div id="u7717" class="ax_default shape">
                <div id="u7717_div" class=""></div>
                <!-- Unnamed () -->
                <div id="u7718" class="text" style="visibility: visible;">
                  <p><span>取消</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7719" class="ax_default shape">
        <img id="u7719_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7720" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7721" class="ax_default image">
        <img id="u7721_img" class="img " src="images/登入成功_1/u3368.png"/>
        <!-- Unnamed () -->
        <div id="u7722" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7723" class="ax_default image">
        <img id="u7723_img" class="img " src="images/登入成功_1/u3372.png"/>
        <!-- Unnamed () -->
        <div id="u7724" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7725" class="ax_default image">
        <img id="u7725_img" class="img " src="images/登入成功_1/u3356.png"/>
        <!-- Unnamed () -->
        <div id="u7726" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7727" class="ax_default image">
        <img id="u7727_img" class="img " src="images/登入成功_1/u3360.png"/>
        <!-- Unnamed () -->
        <div id="u7728" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7729" class="ax_default image">
        <img id="u7729_img" class="img " src="images/登入成功_1/u3368.png"/>
        <!-- Unnamed () -->
        <div id="u7730" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7731" class="ax_default image">
        <img id="u7731_img" class="img " src="images/登入成功_1/u3372.png"/>
        <!-- Unnamed () -->
        <div id="u7732" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7733" class="ax_default image">
        <img id="u7733_img" class="img " src="images/登入成功_1/u3356.png"/>
        <!-- Unnamed () -->
        <div id="u7734" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7735" class="ax_default image">
        <img id="u7735_img" class="img " src="images/登入成功_1/u3360.png"/>
        <!-- Unnamed () -->
        <div id="u7736" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7737" class="ax_default image">
        <img id="u7737_img" class="img " src="images/登入成功_1/u3368.png"/>
        <!-- Unnamed () -->
        <div id="u7738" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7739" class="ax_default image">
        <img id="u7739_img" class="img " src="images/登入成功_1/u3372.png"/>
        <!-- Unnamed () -->
        <div id="u7740" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7741" class="ax_default image">
        <img id="u7741_img" class="img " src="images/登入成功_1/u3356.png"/>
        <!-- Unnamed () -->
        <div id="u7742" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7743" class="ax_default image">
        <img id="u7743_img" class="img " src="images/登入成功_1/u3364.png"/>
        <!-- Unnamed () -->
        <div id="u7744" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7745" class="ax_default image">
        <img id="u7745_img" class="img " src="images/登入成功_1/u3368.png"/>
        <!-- Unnamed () -->
        <div id="u7746" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7747" class="ax_default image">
        <img id="u7747_img" class="img " src="images/登入成功_1/u3372.png"/>
        <!-- Unnamed () -->
        <div id="u7748" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7749" class="ax_default image">
        <img id="u7749_img" class="img " src="images/登入成功_1/u3356.png"/>
        <!-- Unnamed () -->
        <div id="u7750" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- 停用時系統判斷 (Dynamic Panel) -->
      <div id="u7751" class="ax_default ax_default_hidden" data-label="停用時系統判斷" style="display: none; visibility: hidden">
        <div id="u7751_state0" class="panel_state" data-label="名下有無負責班級">
          <div id="u7751_state0_content" class="panel_state_content">

            <!-- Unnamed (Rectangle) -->
            <div id="u7752" class="ax_default shape">
              <img id="u7752_img" class="img " src="images/教師管理_1/u7752.png"/>
              <!-- Unnamed () -->
              <div id="u7753" class="text" style="display: none; visibility: hidden">
                <p><span style="text-decoration:underline;"></span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7754" class="ax_default heading_2">
              <div id="u7754_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7755" class="text" style="visibility: visible;">
                <p><span style="text-decoration:underline;">名下有負責班級</span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7756" class="ax_default heading_2">
              <div id="u7756_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7757" class="text" style="visibility: visible;">
                <p><span style="text-decoration:underline;">名下無負責班級</span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7758" class="ax_default heading_2">
              <div id="u7758_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7759" class="text" style="visibility: visible;">
                <p><span>系統判斷：</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 刪除時系統判斷 (Dynamic Panel) -->
      <div id="u7760" class="ax_default ax_default_hidden" data-label="刪除時系統判斷" style="display: none; visibility: hidden">
        <div id="u7760_state0" class="panel_state" data-label="名下有無負責班級">
          <div id="u7760_state0_content" class="panel_state_content">

            <!-- Unnamed (Rectangle) -->
            <div id="u7761" class="ax_default shape">
              <img id="u7761_img" class="img " src="images/教師管理_1/u7761.png"/>
              <!-- Unnamed () -->
              <div id="u7762" class="text" style="display: none; visibility: hidden">
                <p><span></span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7763" class="ax_default heading_2">
              <div id="u7763_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7764" class="text" style="visibility: visible;">
                <p><span style="text-decoration:underline;">名下有負責班級</span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7765" class="ax_default heading_2">
              <div id="u7765_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7766" class="text" style="visibility: visible;">
                <p><span style="text-decoration:underline;">名下無負責班級</span></p>
              </div>
            </div>

            <!-- Unnamed (Rectangle) -->
            <div id="u7767" class="ax_default heading_2">
              <div id="u7767_div" class=""></div>
              <!-- Unnamed () -->
              <div id="u7768" class="text" style="visibility: visible;">
                <p><span>系統判斷：</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7769" class="ax_default shape">
        <img id="u7769_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7770" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7771" class="ax_default shape">
        <img id="u7771_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7772" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7773" class="ax_default shape">
        <img id="u7773_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7774" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7775" class="ax_default shape">
        <img id="u7775_img" class="img " src="resources/images/transparent.gif"/>
        <!-- Unnamed () -->
        <div id="u7776" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Header) -->

      <!-- Unnamed (Group) -->
      <div id="u7778" class="ax_default">

        <!-- Unnamed (Placeholder) -->
        <div id="u7779" class="ax_default shape">
          <img id="u7779_img" class="img " src="images/登入成功_1/u3323.png"/>
          <!-- Unnamed () -->
          <div id="u7780" class="text" style="visibility: visible;">
            <p><span>擴思訊 LOGO</span></p>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u7781" class="ax_default heading_2">
          <div id="u7781_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7782" class="text" style="visibility: visible;">
            <p><span>Hi,陳老師</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7783" class="ax_default shape">
        <div id="u7783_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7784" class="text" style="visibility: visible;">
          <p><span>Log out</span></p>
        </div>
      </div>

      <!-- Unnamed (Menu) -->

      <!-- Unnamed (Rectangle) -->
      <div id="u7786" class="ax_default box_2">
        <div id="u7786_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7787" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u7788" class="ax_default image">
        <img id="u7788_img" class="img " src="images/登入成功_1/u3346.png"/>
        <!-- Unnamed () -->
        <div id="u7789" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (按鈕說明-CT管理者) -->

      <!-- Unnamed (Group) -->
      <div id="u7791" class="ax_default ax_default_hidden">

        <!-- Unnamed (Rectangle) -->
        <div id="u7792" class="ax_default shape">
          <div id="u7792_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7793" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u7794" class="ax_default shape">
          <div id="u7794_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7795" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 刪除 (Rectangle) -->
        <div id="u7796" class="ax_default heading_2" data-label="刪除">
          <div id="u7796_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7797" class="text" style="visibility: visible;">
            <p><span>刪除</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7798" class="ax_default image">
          <img id="u7798_img" class="img " src="images/登入成功_1/u3356.png"/>
          <!-- Unnamed () -->
          <div id="u7799" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 目前狀態圍啟用，點選可停用 (Rectangle) -->
        <div id="u7800" class="ax_default heading_2" data-label="目前狀態圍啟用，點選可停用">
          <div id="u7800_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7801" class="text" style="visibility: visible;">
            <p><span>目前狀態圍啟用，點選可停用</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7802" class="ax_default image">
          <img id="u7802_img" class="img " src="images/登入成功_1/u3360.png"/>
          <!-- Unnamed () -->
          <div id="u7803" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 目前狀態為停用，點選可啟用 (Rectangle) -->
        <div id="u7804" class="ax_default heading_2" data-label="目前狀態為停用，點選可啟用">
          <div id="u7804_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7805" class="text" style="visibility: visible;">
            <p><span>目前狀態為停用，點選可啟用</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7806" class="ax_default image">
          <img id="u7806_img" class="img " src="images/登入成功_1/u3364.png"/>
          <!-- Unnamed () -->
          <div id="u7807" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 檢視內容 (Rectangle) -->
        <div id="u7808" class="ax_default heading_2" data-label="檢視內容">
          <div id="u7808_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7809" class="text" style="visibility: visible;">
            <p><span>檢視內容</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7810" class="ax_default image">
          <img id="u7810_img" class="img " src="images/登入成功_1/u3368.png"/>
          <!-- Unnamed () -->
          <div id="u7811" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u7812" class="ax_default heading_2" data-label="編輯內容">
          <div id="u7812_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7813" class="text" style="visibility: visible;">
            <p><span>編輯內容</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7814" class="ax_default image">
          <img id="u7814_img" class="img " src="images/登入成功_1/u3372.png"/>
          <!-- Unnamed () -->
          <div id="u7815" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 按鈕說明 (Rectangle) -->
        <div id="u7816" class="ax_default heading_2" data-label="按鈕說明">
          <div id="u7816_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7817" class="text" style="visibility: visible;">
            <p><span>按鈕說明</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7818" class="ax_default image">
          <img id="u7818_img" class="img " src="images/成長圈_new_s3-檢視/u2437.png"/>
          <!-- Unnamed () -->
          <div id="u7819" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7820" class="ax_default image">
          <img id="u7820_img" class="img " src="images/登入成功_1/u3346.png"/>
          <!-- Unnamed () -->
          <div id="u7821" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7822" class="ax_default image">
          <img id="u7822_img" class="img " src="images/登入成功_1/u3380.png"/>
          <!-- Unnamed () -->
          <div id="u7823" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u7824" class="ax_default heading_2" data-label="編輯內容">
          <div id="u7824_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7825" class="text" style="visibility: visible;">
            <p><span>產出班級授權碼</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u7826" class="ax_default image">
          <img id="u7826_img" class="img " src="images/登入成功_1/u3384.png"/>
          <!-- Unnamed () -->
          <div id="u7827" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u7828" class="ax_default heading_2" data-label="編輯內容">
          <div id="u7828_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7829" class="text" style="visibility: visible;">
            <p><span>檢視已連結家長帳號</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Menu) -->
      <div id="u7830" class="ax_default">
        <img id="u7830_menu" class="img " src="images/登入成功_1/u3332_menu.png" alt="u7830_menu"/>

        <!-- Unnamed (Table) -->
        <div id="u7831" class="ax_default table">

          <!-- Unnamed (Menu Item) -->
          <div id="u7832" class="ax_default table_cell">
            <img id="u7832_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u7833" class="text" style="visibility: visible;">
              <p><span>教師管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u7834" class="ax_default table_cell">
            <img id="u7834_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u7835" class="text" style="visibility: visible;">
              <p><span>班級與學生管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u7836" class="ax_default table_cell">
            <img id="u7836_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u7837" class="text" style="visibility: visible;">
              <p><span>課程記錄審核</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u7838" class="ax_default table_cell">
            <img id="u7838_img" class="img " src="images/登入成功_2/u7534.png"/>
            <!-- Unnamed () -->
            <div id="u7839" class="text" style="visibility: visible;">
              <p><span>系統資訊</span></p>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u7840" class="ax_default sub_menu">
          <img id="u7840_menu" class="img " src="images/登入成功_2/u7536_menu.png" alt="u7840_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u7841" class="ax_default table">

            <!-- Unnamed (Menu Item) -->
            <div id="u7842" class="ax_default table_cell">
              <img id="u7842_img" class="img " src="images/登入成功_2/u7538.png"/>
              <!-- Unnamed () -->
              <div id="u7843" class="text" style="visibility: visible;">
                <p><span>學生管理</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u7844" class="ax_default table_cell">
              <img id="u7844_img" class="img " src="images/登入成功_2/u7540.png"/>
              <!-- Unnamed () -->
              <div id="u7845" class="text" style="visibility: visible;">
                <p><span>班級管理</span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u7846" class="ax_default sub_menu">
          <img id="u7846_menu" class="img " src="images/登入成功_2/u7542_menu.png" alt="u7846_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u7847" class="ax_default">

            <!-- Unnamed (Menu Item) -->
            <div id="u7848" class="ax_default table_cell">
              <img id="u7848_img" class="img " src="images/登入成功_2/u7544.png"/>
              <!-- Unnamed () -->
              <div id="u7849" class="text" style="visibility: visible;">
                <p><span>帳號資訊</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u7850" class="ax_default table_cell">
              <img id="u7850_img" class="img " src="images/登入成功_2/u7534.png"/>
              <!-- Unnamed () -->
              <div id="u7851" class="text" style="visibility: visible;">
                <p><span>變更密碼</span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u7852" class="ax_default sub_menu">
          <img id="u7852_menu" class="img " src="images/登入成功_2/u7542_menu.png" alt="u7852_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u7853" class="ax_default">

            <!-- Unnamed (Menu Item) -->
            <div id="u7854" class="ax_default table_cell">
              <img id="u7854_img" class="img " src="images/登入成功_2/u7544.png"/>
              <!-- Unnamed () -->
              <div id="u7855" class="text" style="visibility: visible;">
                <p><span>教師管理</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u7856" class="ax_default table_cell">
              <img id="u7856_img" class="img " src="images/登入成功_2/u7534.png"/>
              <!-- Unnamed () -->
              <div id="u7857" class="text" style="visibility: visible;">
                <p><span>教師工作進度</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7858" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7859" class="ax_default">

          <!-- Unnamed (Rectangle) -->
          <div id="u7860" class="ax_default paragraph">
            <div id="u7860_div" class=""></div>
            <!-- Unnamed () -->
            <div id="u7861" class="text" style="visibility: visible;">
              <p><span>教師與課表管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Rectangle) -->
          <div id="u7862" class="ax_default paragraph">
            <div id="u7862_div" class=""></div>
            <!-- Unnamed () -->
            <div id="u7863" class="text" style="visibility: visible;">
              <p><span>&gt;</span></p>
            </div>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u7864" class="ax_default paragraph">
          <div id="u7864_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7865" class="text" style="visibility: visible;">
            <p><span>教師管理</span></p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
