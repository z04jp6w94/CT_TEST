        <div class="addSystemUser">
        	<ul class="switcher">
            	<li><label><input type="radio" id="btn_child" name="switchForm" class="switch current" onclick="switchTabContent(this);" current-ctx="ctx_child" hide-btn="btn_teacher" hide-ctx="ctx_teacher" checked><span class="sized-text-1">幼兒園</span></label></li>
                <li><label><input type="radio" id="btn_teacher" name="switchForm" class="switch" onclick="switchTabContent(this);" current-ctx="ctx_teacher" hide-btn="btn_child" hide-ctx="ctx_child"><span class="sized-text-1">老師／家長</span></label></li>
            </ul>
            <div class="clearFloat"></div>
            
            <div class="spacingBlock"></div>
            <h2><span class="sized-text-1">客戶資料</span></h2>
            <div class="spacingBlock"></div>
            
        	<table cellpadding="0" cellspacing="0" class="leftHeader" id="ctx_child">
            	<!-- 幼兒園 -->
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號類型：</span></th>
                    <td valign="middle">
                    	<select class="halfWidth sized-text-1">
                        	<option>正式帳號</option>
                            <option>測試帳號</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">幼兒園名稱＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class=needMark "hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="Email" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">Email＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">地區（國家／縣市）＊：</span></th>
                    <td valign="middle">
                    	<select class="smallWidth sized-text-1">
                        	<option>台灣</option>
                            <option>日本</option>
                        </select>
                        <select class="smallWidth sized-text-1">
                        	<option>台北市</option>
                            <option>新北市</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">所屬幼園連鎖名稱：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio"><span class="sized-text-1">有</span></label>
                    	<input type="text" class="smallWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">教具（無／有）＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio" checked><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 begin -->
                <tr>
                	<th><span class="needMark hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle">
                    	<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">&nbsp;<span class="sized-text-1">套</span>
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具來源：</span>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">自購</span></label>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">他人送</span></label>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具名稱：</span>&nbsp;
                        <input type="text" class="halfWidth sized-text-normal" placeholder="必填" value="">
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 end -->
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">學生數量：</span></th>
                    <td valign="middle">
                    	<span class="sized-text-1">本園</span>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">
                        &nbsp;
                        <span class="sized-text-1">集團學生總數</span>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡電話：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡傳真：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人手機：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 wechat ID：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 Email：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">資訊來源：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">書</span></label>&nbsp;
                        <label><input type="radio"><span class="sized-text-1">電子媒體</span></label>&nbsp;
                        <label><input type="radio"><span class="sized-text-1">朋友介紹</span></label><br>
                        <label><input type="radio"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" class="smallWidth sized-text-normal" value="">
                    </td>
                </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" class="leftHeader" id="ctx_teacher" style="display:none;">
            	<!-- 老師／家長 -->
            	<tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">帳號類型：</span></th>
                    <td valign="middle">
                    	<select class="halfWidth sized-text-1">
                        	<option>正式帳號</option>
                            <option>測試帳號</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">名稱／姓名、暱稱＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class=needMark "hor-box-text-normal">帳號＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="Email" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">密碼＊：</span></th>
                    <td valign="middle"><input type="password" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">Email＊：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">地區（國家／縣市）＊：</span></th>
                    <td valign="middle">
                    	<select class="smallWidth sized-text-1">
                        	<option>台灣</option>
                            <option>日本</option>
                        </select>
                        <select class="smallWidth sized-text-1">
                        	<option>台北市</option>
                            <option>新北市</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">從事幼教行業＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio"><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="needMark hor-box-text-normal">教具（無／有）＊：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">無</span></label>&nbsp;
                    	<label><input type="radio"><span class="sized-text-1">有</span></label>
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 begin -->
                <tr>
                	<th><span class="needMark hor-box-text-normal">&nbsp;</span></th>
                    <td valign="middle">
                    	<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">&nbsp;<span class="sized-text-1">套</span>
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具來源：</span>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">自購</span></label>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">他人送</span></label>&nbsp;
                        <label><input type="checkbox"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">
                        
                        <div class="spacingBlock"></div>
                        
                        <span class="sized-text-1">教具名稱：</span>&nbsp;
                        <input type="text" class="halfWidth sized-text-normal" placeholder="必填" value="">
                    </td>
                </tr>
                <!-- 當教具（無／有）　的＂有＂被選取時出現 end -->
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">學生數量：</span></th>
                    <td valign="middle">
                    	<span class="sized-text-1">本園</span>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value="">
                        &nbsp;
                        <span class="sized-text-1">集團學生總數</span>&nbsp;<input type="text" class="miniWidth sized-text-normal" placeholder="必填" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡電話：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡傳真：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人手機：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 wechat ID：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">聯絡人 Email：</span></th>
                    <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">資訊來源：</span></th>
                    <td valign="middle">
                    	<label><input type="radio"><span class="sized-text-1">書</span></label>&nbsp;
                        <label><input type="radio"><span class="sized-text-1">電子媒體</span></label>&nbsp;
                        <label><input type="radio"><span class="sized-text-1">朋友介紹</span></label><br>
                        <label><input type="radio"><span class="sized-text-1">其他</span></label>&nbsp;<input type="text" class="smallWidth sized-text-normal" value="">
                    </td>
                </tr>
            </table>
            
            <div class="spacingBlock"></div>
            <h2><span class="sized-text-1">授權管理</span></h2>
            <div class="spacingBlock"></div>
            
            <ul class="switcher">
            	<li><button type="button" id="btn_basicAuth" class="optionDoBtn current" onclick="switchTabContent(this);" current-ctx="ctx_basicAuth" hide-btn="btn_extendAuth" hide-ctx="ctx_extendAuth"><span class="sized-text-1">基礎帳號授權</span></button></li>
            	<li><button type="button" id="btn_extendAuth" class="optionDoBtn" onclick="switchTabContent(this);" current-ctx="ctx_extendAuth" hide-btn="btn_basicAuth" hide-ctx="ctx_basicAuth"><span class="sized-text-1">選購授權</span></button></li>
            </ul>
            <div class="clearFloat"></div>
            <div class="spacingBlock"></div>
            
            <div class="authInfo" id="ctx_basicAuth">
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">教師帳號數＊：</span></th>
                        <td valign="middle">
							<select class="miniWidth sized-text-1">
                        		<option>0</option>
                            	<option>1</option>
								<option>2</option>
                        	</select>
						</td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">寶貝帳號數＊：</span></th>
                        <td valign="middle">
							<select class="miniWidth sized-text-1">
                        		<option>0</option>
                            	<option>1</option>
								<option>2</option>
                        	</select>
						</td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權期間＊：</span></th>
                        <td valign="middle"><input type="text" class="smallWidth sized-text-normal" value="">&nbsp;到&nbsp;<input type="text" class="smallWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權類別＊：</span></th>
                        <td valign="middle">
                        	<br>
                            <table cellpadding="0" cellspacing="0" class="itemMover">
                                <tr>
                                    <td>
                                    	<div class="box">
                                            <p class="title"><span class="sized-text-1">Full List</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td valign="middle">
                                    	<div class="btns">
                                        	<button type="button" class="toRight"><span class="hiddenItem">向右移</span></button>
                                            <br>
                                            <br>
                                            <button type="button" class="toLeft"><span class="hiddenItem">向左移</span></button>
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="box">
	                                        <p class="title"><span class="sized-text-1">My Items</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="authInfo" id="ctx_extendAuth" style="display:none;">
                <!-- 資料檢視表格 begin -->
                <div class="dataIndexList themeSet1">
                	<p><span class="hor-box-text-normal">歷史紀錄</span></p>
                    <table cellpadding="0" cellspacing="0" class="topAndLeftHeader">
                        <tr>
                            <th>&nbsp;</th>
                            <th class="topHeader"><span class="hor-box-text-normal">形態</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">數量／內容</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">授權期間</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立人員</span></th>
                            <th class="topHeader"><span class="hor-box-text-normal">建立時間</span></th>
                        </tr>
                        <tr class="odd">
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">教師帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>
                        <tr class="even">
                            <th valign="middle">
                                <ul class="operations">
                                    <li><a href="#" class="modify"><span class="hiddenItem">編輯</span></a></li>
                                    <li><a href="#" class="remove"><span class="hiddenItem">移除</span></a></li>
                                </ul>
                            </th>
                            <td><span class="hor-box-text-normal">寶貝帳號數</span></td>
                            <td><span class="hor-box-text-normal">5</span></td>
                            <td><span class="hor-box-text-normal">2015/01/01 10:25 - 2015/12/31 22:25</span></td>
                            <td><span class="hor-box-text-normal">ANDY LEE</span></td>
                            <td class="leftAlignText"><span class="hor-box-text-normal">2015/01/01 14:00</span></td>
                        </tr>
                    </table>
                </div>
                <!-- 資料檢視表格 end -->
                
                <div class="spacingBlock"></div>
                <p><span class="hor-box-text-normal">新增</span></p>
                <table cellpadding="0" cellspacing="0" class="leftHeader">
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">教師帳號數＊：</span></th>
                        <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">寶貝帳號數＊：</span></th>
                        <td valign="middle"><input type="text" class="halfWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權期間＊：</span></th>
                        <td valign="middle"><input type="text" class="smallWidth sized-text-normal" value="">&nbsp;到&nbsp;<input type="text" class="smallWidth sized-text-normal" value=""></td>
                    </tr>
                    <tr>
                        <th valign="top"><span class="needMark hor-box-text-normal">授權類別＊：</span></th>
                        <td valign="middle">
                        	<br>
                            <table cellpadding="0" cellspacing="0" class="itemMover">
                                <tr>
                                    <td>
                                    	<div class="box">
                                            <p class="title"><span class="sized-text-1">Full List</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td valign="middle">
                                    	<div class="btns">
                                        	<button type="button" class="toRight"><span class="hiddenItem">向右移</span></button>
                                            <br>
                                            <br>
                                            <button type="button" class="toLeft"><span class="hiddenItem">向左移</span></button>
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="box">
	                                        <p class="title"><span class="sized-text-1">My Items</span></p>
                                            <select multiple>
                                                <option>AAA</option>
                                                <option>BBB</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="spacingBlock"></div>
            
            <div><!-- 若無須使用可整段移除 -->
            	<ul class="finalControl">
                	<li><button type="button" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></li>
                	<li><button type="button" class="optionDoBtn"><span class="sized-text-1">返回上頁</span></button></li>
                </ul>
                <div class="clearFloat"></div>
            </div>
        </div>