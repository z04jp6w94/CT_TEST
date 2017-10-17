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
//		程式功能：ct / 新增教師
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
    <title>新增教師</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="resources/css/jquery-ui-themes.css" type="text/css" rel="stylesheet"/>
    <link href="resources/css/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="data/styles.css" type="text/css" rel="stylesheet"/>
    <link href="files/新增教師/styles.css" type="text/css" rel="stylesheet"/>
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
    <script src="files/新增教師/data.js"></script>
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
      <div id="u7866" class="ax_default shape">
        <img id="u7866_img" class="img " src="images/新增教師/u7866.png"/>
        <!-- Unnamed () -->
        <div id="u7867" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7868" class="ax_default paragraph">
        <div id="u7868_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7869" class="text" style="visibility: visible;">
          <p><span style="color:#CC3300;">姓名*</span></p><p><span>聯絡電話1</span></p><p><span>聯絡電話2</span></p><p><span>行動電話</span></p><p><span>現居地址</span></p><p><span style="color:#FF0000;">緊急聯絡人*</span></p><p><span style="color:#FF0000;">緊急連絡人電話*</span></p><p><span style="color:#CC3300;">帳號*</span></p><p><span style="color:#CC3300;">密碼*</span></p><p><span>可用系統功能</span></p><p><span><br></span></p><p><span>疾病史</span></p><p><span><br></span></p><p><span>藥物過敏</span></p><p><span>抽菸</span></p><p><span>檳榔</span></p><p><span>喝酒</span></p>
        </div>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7870" class="ax_default text_field">
        <input id="u7870_input" name="FullName" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7871" class="ax_default text_field">
        <input id="u7871_input" name="Account" type="email" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7872" class="ax_default text_field">
        <input id="u7872_input" name="PWD" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7873" class="ax_default text_field">
        <input id="u7873_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7874" class="ax_default text_field">
        <input id="u7874_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7875" class="ax_default text_field">
        <input id="u7875_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Droplist) -->
      <div id="u7876" class="ax_default droplist">
        <select id="u7876_input">
          <option value="已婚">已婚</option>
          <option value="未婚">未婚</option>
          <option value="離婚">離婚</option>
          <option value="其他">其他</option>
        </select>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7877" class="ax_default text_field">
        <input id="u7877_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7878" class="ax_default text_field">
        <input id="u7878_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7879" class="ax_default text_field">
        <input id="u7879_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7880" class="ax_default shape">
        <img id="u7880_img" class="img " src="images/新增教師/u7880.png"/>
        <!-- Unnamed () -->
        <div id="u7881" class="text" style="visibility: visible;">
          <p><span>基本資料區</span></p>
        </div>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7882" class="ax_default text_field">
        <input id="u7882_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Droplist) -->
      <div id="u7883" class="ax_default droplist">
        <select id="u7883_input">
          <option value="男">男</option>
          <option value="女">女</option>
          <option value="其他">其他</option>
        </select>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7884" class="ax_default text_field">
        <input id="u7884_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7885" class="ax_default text_field">
        <input id="u7885_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7886" class="ax_default text_field">
        <input id="u7886_input" type="text" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7887" class="ax_default text_field">
        <input id="u7887_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7888" class="ax_default paragraph">
        <div id="u7888_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7889" class="text" style="visibility: visible;">
          <p><span>個</span></p>
        </div>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7890" class="ax_default text_field">
        <input id="u7890_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7891" class="ax_default text_field">
        <input id="u7891_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7892" class="ax_default text_field">
        <input id="u7892_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Text Field) -->
      <div id="u7893" class="ax_default text_field">
        <input id="u7893_input" type="tel" value=""/>
      </div>

      <!-- Unnamed (Droplist) -->
      <div id="u7894" class="ax_default droplist">
        <select id="u7894_input">
          <option value="葷">葷</option>
          <option value="素">素</option>
          <option value="無">無</option>
        </select>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7895" class="ax_default paragraph">
        <div id="u7895_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7896" class="text" style="visibility: visible;">
          <p><span>性別</span></p><p><span>生日</span></p><p><span>年齡</span></p><p><span>籍貫</span></p><p><span>婚姻狀況</span></p><p><span>子女</span></p><p><span>家庭狀況(描述)</span></p><p><span>畢業學校/系所</span></p><p><span>畢業時間</span></p><p><span>入行時間</span></p><p><span>飲食禁忌</span></p>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7897" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7898" class="ax_default">

          <!-- Unnamed (Checkbox) -->
          <div id="u7899" class="ax_default checkbox">
            <label for="u7899_input">
              <!-- Unnamed () -->
              <div id="u7900" class="text" style="visibility: visible;">
                <p><span>無</span></p>
              </div>
            </label>
            <input id="u7899_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7901" class="ax_default checkbox">
            <label for="u7901_input">
              <!-- Unnamed () -->
              <div id="u7902" class="text" style="visibility: visible;">
                <p><span>不清楚</span></p>
              </div>
            </label>
            <input id="u7901_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7903" class="ax_default checkbox">
            <label for="u7903_input">
              <!-- Unnamed () -->
              <div id="u7904" class="text" style="visibility: visible;">
                <p><span>痛風</span></p>
              </div>
            </label>
            <input id="u7903_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7905" class="ax_default checkbox">
            <label for="u7905_input">
              <!-- Unnamed () -->
              <div id="u7906" class="text" style="visibility: visible;">
                <p><span>高血壓</span></p>
              </div>
            </label>
            <input id="u7905_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7907" class="ax_default checkbox">
            <label for="u7907_input">
              <!-- Unnamed () -->
              <div id="u7908" class="text" style="visibility: visible;">
                <p><span>糖尿病&nbsp;&nbsp; </span></p>
              </div>
            </label>
            <input id="u7907_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7909" class="ax_default checkbox">
            <label for="u7909_input">
              <!-- Unnamed () -->
              <div id="u7910" class="text" style="visibility: visible;">
                <p><span>心血管疾病 </span></p>
              </div>
            </label>
            <input id="u7909_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7911" class="ax_default checkbox">
            <label for="u7911_input">
              <!-- Unnamed () -->
              <div id="u7912" class="text" style="visibility: visible;">
                <p><span>B型肝炎 </span></p>
              </div>
            </label>
            <input id="u7911_input" type="checkbox" value="checkbox"/>
          </div>
        </div>

        <!-- Unnamed (Group) -->
        <div id="u7913" class="ax_default">

          <!-- Unnamed (Checkbox) -->
          <div id="u7914" class="ax_default checkbox">
            <label for="u7914_input">
              <!-- Unnamed () -->
              <div id="u7915" class="text" style="visibility: visible;">
                <p><span>高血脂症 </span></p>
              </div>
            </label>
            <input id="u7914_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7916" class="ax_default checkbox">
            <label for="u7916_input">
              <!-- Unnamed () -->
              <div id="u7917" class="text" style="visibility: visible;">
                <p><span>中風 </span></p>
              </div>
            </label>
            <input id="u7916_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7918" class="ax_default checkbox">
            <label for="u7918_input">
              <!-- Unnamed () -->
              <div id="u7919" class="text" style="visibility: visible;">
                <p><span>腎臟病</span></p>
              </div>
            </label>
            <input id="u7918_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7920" class="ax_default checkbox">
            <label for="u7920_input">
              <!-- Unnamed () -->
              <div id="u7921" class="text" style="visibility: visible;">
                <p><span>肝硬化</span></p>
              </div>
            </label>
            <input id="u7920_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7922" class="ax_default checkbox">
            <label for="u7922_input">
              <!-- Unnamed () -->
              <div id="u7923" class="text" style="visibility: visible;">
                <p><span>消化性潰瘍 </span></p>
              </div>
            </label>
            <input id="u7922_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7924" class="ax_default checkbox">
            <label for="u7924_input">
              <!-- Unnamed () -->
              <div id="u7925" class="text" style="visibility: visible;">
                <p><span>C型肝炎&nbsp;&nbsp; </span></p>
              </div>
            </label>
            <input id="u7924_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7926" class="ax_default checkbox">
            <label for="u7926_input">
              <!-- Unnamed () -->
              <div id="u7927" class="text" style="visibility: visible;">
                <p><span>結核病</span></p>
              </div>
            </label>
            <input id="u7926_input" type="checkbox" value="checkbox"/>
          </div>
        </div>

        <!-- Unnamed (Group) -->
        <div id="u7928" class="ax_default">

          <!-- Unnamed (Checkbox) -->
          <div id="u7929" class="ax_default checkbox">
            <label for="u7929_input">
              <!-- Unnamed () -->
              <div id="u7930" class="text" style="visibility: visible;">
                <p><span>乳癌</span></p>
              </div>
            </label>
            <input id="u7929_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7931" class="ax_default checkbox">
            <label for="u7931_input">
              <!-- Unnamed () -->
              <div id="u7932" class="text" style="visibility: visible;">
                <p><span>肝癌 </span></p>
              </div>
            </label>
            <input id="u7931_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7933" class="ax_default checkbox">
            <label for="u7933_input">
              <!-- Unnamed () -->
              <div id="u7934" class="text" style="visibility: visible;">
                <p><span>鼻咽癌</span></p>
              </div>
            </label>
            <input id="u7933_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7935" class="ax_default checkbox">
            <label for="u7935_input">
              <!-- Unnamed () -->
              <div id="u7936" class="text" style="visibility: visible;">
                <p><span>結腸直腸癌</span></p>
              </div>
            </label>
            <input id="u7935_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7937" class="ax_default checkbox">
            <label for="u7937_input">
              <!-- Unnamed () -->
              <div id="u7938" class="text" style="visibility: visible;">
                <p><span>子宮頸癌</span></p>
              </div>
            </label>
            <input id="u7937_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7939" class="ax_default checkbox">
            <label for="u7939_input">
              <!-- Unnamed () -->
              <div id="u7940" class="text" style="visibility: visible;">
                <p><span>口腔癌</span></p>
              </div>
            </label>
            <input id="u7939_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7941" class="ax_default checkbox">
            <label for="u7941_input">
              <!-- Unnamed () -->
              <div id="u7942" class="text" style="visibility: visible;">
                <p><span>其他</span></p>
              </div>
            </label>
            <input id="u7941_input" type="checkbox" value="checkbox"/>
          </div>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7943" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7944" class="ax_default">

          <!-- Unnamed (Checkbox) -->
          <div id="u7945" class="ax_default checkbox">
            <label for="u7945_input">
              <!-- Unnamed () -->
              <div id="u7946" class="text" style="visibility: visible;">
                <p><span>不清楚</span></p>
              </div>
            </label>
            <input id="u7945_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7947" class="ax_default checkbox">
            <label for="u7947_input">
              <!-- Unnamed () -->
              <div id="u7948" class="text" style="visibility: visible;">
                <p><span>無</span></p>
              </div>
            </label>
            <input id="u7947_input" type="checkbox" value="checkbox"/>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7949" class="ax_default checkbox">
            <label for="u7949_input">
              <!-- Unnamed () -->
              <div id="u7950" class="text" style="visibility: visible;">
                <p><span>有</span></p>
              </div>
            </label>
            <input id="u7949_input" type="checkbox" value="checkbox"/>
          </div>
        </div>

        <!-- Unnamed (Text Field) -->
        <div id="u7951" class="ax_default text_field">
          <input id="u7951_input" type="tel" value=""/>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7952" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7953" class="ax_default">

          <!-- Unnamed (Group) -->
          <div id="u7954" class="ax_default">

            <!-- Unnamed (Group) -->
            <div id="u7955" class="ax_default">

              <!-- Unnamed (Checkbox) -->
              <div id="u7956" class="ax_default checkbox">
                <label for="u7956_input">
                  <!-- Unnamed () -->
                  <div id="u7957" class="text" style="visibility: visible;">
                    <p><span>無</span></p>
                  </div>
                </label>
                <input id="u7956_input" type="checkbox" value="checkbox"/>
              </div>

              <!-- Unnamed (Checkbox) -->
              <div id="u7958" class="ax_default checkbox">
                <label for="u7958_input">
                  <!-- Unnamed () -->
                  <div id="u7959" class="text" style="visibility: visible;">
                    <p><span>有，一天&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 根，菸齡&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 年</span></p>
                  </div>
                </label>
                <input id="u7958_input" type="checkbox" value="checkbox"/>
              </div>
            </div>

            <!-- Unnamed (Text Field) -->
            <div id="u7960" class="ax_default text_field">
              <input id="u7960_input" type="text" value=""/>
            </div>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7961" class="ax_default checkbox">
            <label for="u7961_input">
              <!-- Unnamed () -->
              <div id="u7962" class="text" style="visibility: visible;">
                <p><span>已誡&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 年</span></p>
              </div>
            </label>
            <input id="u7961_input" type="checkbox" value="checkbox"/>
          </div>
        </div>

        <!-- Unnamed (Text Field) -->
        <div id="u7963" class="ax_default text_field">
          <input id="u7963_input" type="text" value=""/>
        </div>

        <!-- Unnamed (Text Field) -->
        <div id="u7964" class="ax_default text_field">
          <input id="u7964_input" type="text" value=""/>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7965" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7966" class="ax_default">

          <!-- Unnamed (Group) -->
          <div id="u7967" class="ax_default">

            <!-- Unnamed (Group) -->
            <div id="u7968" class="ax_default">

              <!-- Unnamed (Checkbox) -->
              <div id="u7969" class="ax_default checkbox">
                <label for="u7969_input">
                  <!-- Unnamed () -->
                  <div id="u7970" class="text" style="visibility: visible;">
                    <p><span>無</span></p>
                  </div>
                </label>
                <input id="u7969_input" type="checkbox" value="checkbox"/>
              </div>

              <!-- Unnamed (Checkbox) -->
              <div id="u7971" class="ax_default checkbox">
                <label for="u7971_input">
                  <!-- Unnamed () -->
                  <div id="u7972" class="text" style="visibility: visible;">
                    <p><span>有，一天&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 顆，已嚼&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 年</span></p>
                  </div>
                </label>
                <input id="u7971_input" type="checkbox" value="checkbox"/>
              </div>
            </div>

            <!-- Unnamed (Text Field) -->
            <div id="u7973" class="ax_default text_field">
              <input id="u7973_input" type="text" value=""/>
            </div>
          </div>

          <!-- Unnamed (Checkbox) -->
          <div id="u7974" class="ax_default checkbox">
            <label for="u7974_input">
              <!-- Unnamed () -->
              <div id="u7975" class="text" style="visibility: visible;">
                <p><span>已誡&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 年</span></p>
              </div>
            </label>
            <input id="u7974_input" type="checkbox" value="checkbox"/>
          </div>
        </div>

        <!-- Unnamed (Text Field) -->
        <div id="u7976" class="ax_default text_field">
          <input id="u7976_input" type="text" value=""/>
        </div>

        <!-- Unnamed (Text Field) -->
        <div id="u7977" class="ax_default text_field">
          <input id="u7977_input" type="text" value=""/>
        </div>
      </div>

      <!-- Unnamed (Group) -->
      <div id="u7978" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u7979" class="ax_default">

          <!-- Unnamed (Group) -->
          <div id="u7980" class="ax_default">

            <!-- Unnamed (Checkbox) -->
            <div id="u7981" class="ax_default checkbox">
              <label for="u7981_input">
                <!-- Unnamed () -->
                <div id="u7982" class="text" style="visibility: visible;">
                  <p><span>無</span></p>
                </div>
              </label>
              <input id="u7981_input" type="checkbox" value="checkbox"/>
            </div>

            <!-- Unnamed (Checkbox) -->
            <div id="u7983" class="ax_default checkbox">
              <label for="u7983_input">
                <!-- Unnamed () -->
                <div id="u7984" class="text" style="visibility: visible;">
                  <p><span>偶爾喝</span></p>
                </div>
              </label>
              <input id="u7983_input" type="checkbox" value="checkbox"/>
            </div>

            <!-- Unnamed (Checkbox) -->
            <div id="u7985" class="ax_default checkbox">
              <label for="u7985_input">
                <!-- Unnamed () -->
                <div id="u7986" class="text" style="visibility: visible;">
                  <p><span>習慣性喝</span></p>
                </div>
              </label>
              <input id="u7985_input" type="checkbox" value="checkbox"/>
            </div>
          </div>
        </div>

        <!-- Unnamed (Checkbox) -->
        <div id="u7987" class="ax_default checkbox">
          <label for="u7987_input">
            <!-- Unnamed () -->
            <div id="u7988" class="text" style="visibility: visible;">
              <p><span>已戒酒</span></p>
            </div>
          </label>
          <input id="u7987_input" type="checkbox" value="checkbox"/>
        </div>

        <!-- Unnamed (Checkbox) -->
        <div id="u7989" class="ax_default checkbox">
          <label for="u7989_input">
            <!-- Unnamed () -->
            <div id="u7990" class="text" style="visibility: visible;">
              <p><span>不清楚</span></p>
            </div>
          </label>
          <input id="u7989_input" type="checkbox" value="checkbox"/>
        </div>
      </div>

      <!-- Unnamed (Header) -->

      <!-- Unnamed (Group) -->
      <div id="u7992" class="ax_default">

        <!-- Unnamed (Placeholder) -->
        <div id="u7993" class="ax_default shape">
          <img id="u7993_img" class="img " src="images/登入成功_1/u3323.png"/>
          <!-- Unnamed () -->
          <div id="u7994" class="text" style="visibility: visible;">
            <p><span>擴思訊 LOGO</span></p>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u7995" class="ax_default heading_2">
          <div id="u7995_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u7996" class="text" style="visibility: visible;">
            <p><span>Hi,陳老師</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u7997" class="ax_default shape">
        <div id="u7997_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u7998" class="text" style="visibility: visible;">
          <p><span>Log out</span></p>
        </div>
      </div>

      <!-- Unnamed (教師與課表管理-Menu) -->

      <!-- Unnamed (Rectangle) -->
      <div id="u8000" class="ax_default shape">
        <img id="u8000_img" class="img " src="images/新增教師/u8000.png"/>
        <!-- Unnamed () -->
        <div id="u8001" class="text" style="visibility: visible;">
          <p><span>教師與課表管理</span></p>
        </div>
      </div>

      <!-- Unnamed (Menu) -->

      <!-- Unnamed (Rectangle) -->
      <div id="u8003" class="ax_default box_2">
        <div id="u8003_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u8004" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (Image) -->
      <div id="u8005" class="ax_default image">
        <img id="u8005_img" class="img " src="images/登入成功_1/u3346.png"/>
        <!-- Unnamed () -->
        <div id="u8006" class="text" style="display: none; visibility: hidden">
          <p><span></span></p>
        </div>
      </div>

      <!-- Unnamed (按鈕說明-CT管理者) -->

      <!-- Unnamed (Group) -->
      <div id="u8008" class="ax_default ax_default_hidden">

        <!-- Unnamed (Rectangle) -->
        <div id="u8009" class="ax_default shape">
          <div id="u8009_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8010" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u8011" class="ax_default shape">
          <div id="u8011_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8012" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 刪除 (Rectangle) -->
        <div id="u8013" class="ax_default heading_2" data-label="刪除">
          <div id="u8013_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8014" class="text" style="visibility: visible;">
            <p><span>刪除</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8015" class="ax_default image">
          <img id="u8015_img" class="img " src="images/登入成功_1/u3356.png"/>
          <!-- Unnamed () -->
          <div id="u8016" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 目前狀態圍啟用，點選可停用 (Rectangle) -->
        <div id="u8017" class="ax_default heading_2" data-label="目前狀態圍啟用，點選可停用">
          <div id="u8017_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8018" class="text" style="visibility: visible;">
            <p><span>目前狀態圍啟用，點選可停用</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8019" class="ax_default image">
          <img id="u8019_img" class="img " src="images/登入成功_1/u3360.png"/>
          <!-- Unnamed () -->
          <div id="u8020" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 目前狀態為停用，點選可啟用 (Rectangle) -->
        <div id="u8021" class="ax_default heading_2" data-label="目前狀態為停用，點選可啟用">
          <div id="u8021_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8022" class="text" style="visibility: visible;">
            <p><span>目前狀態為停用，點選可啟用</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8023" class="ax_default image">
          <img id="u8023_img" class="img " src="images/登入成功_1/u3364.png"/>
          <!-- Unnamed () -->
          <div id="u8024" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 檢視內容 (Rectangle) -->
        <div id="u8025" class="ax_default heading_2" data-label="檢視內容">
          <div id="u8025_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8026" class="text" style="visibility: visible;">
            <p><span>檢視內容</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8027" class="ax_default image">
          <img id="u8027_img" class="img " src="images/登入成功_1/u3368.png"/>
          <!-- Unnamed () -->
          <div id="u8028" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u8029" class="ax_default heading_2" data-label="編輯內容">
          <div id="u8029_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8030" class="text" style="visibility: visible;">
            <p><span>編輯內容</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8031" class="ax_default image">
          <img id="u8031_img" class="img " src="images/登入成功_1/u3372.png"/>
          <!-- Unnamed () -->
          <div id="u8032" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 按鈕說明 (Rectangle) -->
        <div id="u8033" class="ax_default heading_2" data-label="按鈕說明">
          <div id="u8033_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8034" class="text" style="visibility: visible;">
            <p><span>按鈕說明</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8035" class="ax_default image">
          <img id="u8035_img" class="img " src="images/成長圈_new_s3-檢視/u2437.png"/>
          <!-- Unnamed () -->
          <div id="u8036" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8037" class="ax_default image">
          <img id="u8037_img" class="img " src="images/登入成功_1/u3346.png"/>
          <!-- Unnamed () -->
          <div id="u8038" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8039" class="ax_default image">
          <img id="u8039_img" class="img " src="images/登入成功_1/u3380.png"/>
          <!-- Unnamed () -->
          <div id="u8040" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u8041" class="ax_default heading_2" data-label="編輯內容">
          <div id="u8041_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8042" class="text" style="visibility: visible;">
            <p><span>產出班級授權碼</span></p>
          </div>
        </div>

        <!-- Unnamed (Image) -->
        <div id="u8043" class="ax_default image">
          <img id="u8043_img" class="img " src="images/登入成功_1/u3384.png"/>
          <!-- Unnamed () -->
          <div id="u8044" class="text" style="display: none; visibility: hidden">
            <p><span></span></p>
          </div>
        </div>

        <!-- 編輯內容 (Rectangle) -->
        <div id="u8045" class="ax_default heading_2" data-label="編輯內容">
          <div id="u8045_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8046" class="text" style="visibility: visible;">
            <p><span>檢視已連結家長帳號</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Menu) -->
      <div id="u8047" class="ax_default">
        <img id="u8047_menu" class="img " src="images/登入成功_1/u3332_menu.png" alt="u8047_menu"/>

        <!-- Unnamed (Table) -->
        <div id="u8048" class="ax_default table">

          <!-- Unnamed (Menu Item) -->
          <div id="u8049" class="ax_default table_cell">
            <img id="u8049_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u8050" class="text" style="visibility: visible;">
              <p><span>教師管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u8051" class="ax_default table_cell">
            <img id="u8051_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u8052" class="text" style="visibility: visible;">
              <p><span>班級與學生管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u8053" class="ax_default table_cell">
            <img id="u8053_img" class="img " src="images/登入成功_2/u7528.png"/>
            <!-- Unnamed () -->
            <div id="u8054" class="text" style="visibility: visible;">
              <p><span>課程記錄審核</span></p>
            </div>
          </div>

          <!-- Unnamed (Menu Item) -->
          <div id="u8055" class="ax_default table_cell">
            <img id="u8055_img" class="img " src="images/登入成功_2/u7534.png"/>
            <!-- Unnamed () -->
            <div id="u8056" class="text" style="visibility: visible;">
              <p><span>系統資訊</span></p>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u8057" class="ax_default sub_menu">
          <img id="u8057_menu" class="img " src="images/登入成功_2/u7536_menu.png" alt="u8057_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u8058" class="ax_default table">

            <!-- Unnamed (Menu Item) -->
            <div id="u8059" class="ax_default table_cell">
              <img id="u8059_img" class="img " src="images/登入成功_2/u7538.png"/>
              <!-- Unnamed () -->
              <div id="u8060" class="text" style="visibility: visible;">
                <p><span>學生管理</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u8061" class="ax_default table_cell">
              <img id="u8061_img" class="img " src="images/登入成功_2/u7540.png"/>
              <!-- Unnamed () -->
              <div id="u8062" class="text" style="visibility: visible;">
                <p><span>班級管理</span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u8063" class="ax_default sub_menu">
          <img id="u8063_menu" class="img " src="images/登入成功_2/u7542_menu.png" alt="u8063_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u8064" class="ax_default">

            <!-- Unnamed (Menu Item) -->
            <div id="u8065" class="ax_default table_cell">
              <img id="u8065_img" class="img " src="images/登入成功_2/u7544.png"/>
              <!-- Unnamed () -->
              <div id="u8066" class="text" style="visibility: visible;">
                <p><span>帳號資訊</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u8067" class="ax_default table_cell">
              <img id="u8067_img" class="img " src="images/登入成功_2/u7534.png"/>
              <!-- Unnamed () -->
              <div id="u8068" class="text" style="visibility: visible;">
                <p><span>變更密碼</span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Unnamed (Menu) -->
        <div id="u8069" class="ax_default sub_menu">
          <img id="u8069_menu" class="img " src="images/登入成功_2/u7542_menu.png" alt="u8069_menu"/>

          <!-- Unnamed (Table) -->
          <div id="u8070" class="ax_default">

            <!-- Unnamed (Menu Item) -->
            <div id="u8071" class="ax_default table_cell">
              <img id="u8071_img" class="img " src="images/登入成功_2/u7544.png"/>
              <!-- Unnamed () -->
              <div id="u8072" class="text" style="visibility: visible;">
                <p><span>教師管理</span></p>
              </div>
            </div>

            <!-- Unnamed (Menu Item) -->
            <div id="u8073" class="ax_default table_cell">
              <img id="u8073_img" class="img " src="images/登入成功_2/u7534.png"/>
              <!-- Unnamed () -->
              <div id="u8074" class="text" style="visibility: visible;">
                <p><span>教師工作進度</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u8075" class="ax_default paragraph">
        <div id="u8075_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u8076" class="text" style="visibility: visible;">
          <p><span>新增教師</span></p>
        </div>
      </div>

      <!-- Unnamed (麵包削>教師與課表管理>教師管理) -->

      <!-- Unnamed (Group) -->
      <div id="u8078" class="ax_default">

        <!-- Unnamed (Group) -->
        <div id="u8079" class="ax_default">

          <!-- Unnamed (Rectangle) -->
          <div id="u8080" class="ax_default paragraph">
            <div id="u8080_div" class=""></div>
            <!-- Unnamed () -->
            <div id="u8081" class="text" style="visibility: visible;">
              <p><span>教師與課表管理</span></p>
            </div>
          </div>

          <!-- Unnamed (Rectangle) -->
          <div id="u8082" class="ax_default paragraph">
            <div id="u8082_div" class=""></div>
            <!-- Unnamed () -->
            <div id="u8083" class="text" style="visibility: visible;">
              <p><span>&gt;</span></p>
            </div>
          </div>
        </div>

        <!-- Unnamed (Rectangle) -->
        <div id="u8084" class="ax_default paragraph">
          <div id="u8084_div" class=""></div>
          <!-- Unnamed () -->
          <div id="u8085" class="text" style="visibility: visible;">
            <p><span>教師管理</span></p>
          </div>
        </div>
      </div>

      <!-- Unnamed (Rectangle) -->
      <div id="u8086" class="ax_default paragraph">
        <div id="u8086_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u8087" class="text" style="visibility: visible;">
          <p><span>&gt;</span></p>
        </div>
      </div>

      <!-- Unnamed (Checkbox) -->
      <div id="u8088" class="ax_default checkbox">
        <label for="u8088_input">
          <!-- Unnamed () -->
          <div id="u8089" class="text" style="visibility: visible;">
            <p><span>Web</span></p>
          </div>
        </label>
        <input id="u8088_input" type="checkbox" value="checkbox"/>
      </div>

      <!-- Unnamed (Checkbox) -->
      <div id="u8090" class="ax_default checkbox">
        <label for="u8090_input">
          <!-- Unnamed () -->
          <div id="u8091" class="text" style="visibility: visible;">
            <p><span>APP</span></p>
          </div>
        </label>
        <input id="u8090_input" type="checkbox" value="checkbox"/>
      </div>

      <!-- Unnamed (儲存) -->

      <!-- Unnamed (Rectangle) -->
      <div id="u8093" class="ax_default shape">
        <div id="u8093_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u8094" class="text" style="visibility: visible;">
          <p><span>儲存</span></p>
        </div>
      </div>

      <!-- Unnamed (返回上頁) -->

      <!-- Unnamed (Rectangle) -->
      <div id="u8096" class="ax_default shape">
        <div id="u8096_div" class=""></div>
        <!-- Unnamed () -->
        <div id="u8097" class="text" style="visibility: visible;">
          <p><span>返回上頁</span></p>
        </div>
      </div>
    </div>
  </body>
</html>
