        <div class="timeTableOperation themeSet1">
        	<table cellpadding="0" cellspacing="0" class="leftHeader import">
            	<tr>
                	<th valign="top"><span class="hor-box-text-normal">課表名稱：</span></th>
                    <td valign="middle"><input type="text" class="name sized-text-normal" value="擴思訊十六週興趣班-Level 1-1"></td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">建議上課頻率：</span></th>
                    <td valign="middle">
                        <label><input type="radio"><span class="sized-text-1">基礎60天（一週至少3次）</span></label>
                        &nbsp;<label><input type="radio"><span class="sized-text-1">精要16天（一週至少1次）</span></label>
                        &nbsp;<label><input type="radio"><span class="sized-text-1">菁英10天（一天1次）</span></label>
                    </td>
                </tr>
                <tr>
                	<th valign="top"><span class="hor-box-text-normal">匯入檔案：</span></th>
                    <td valign="middle">
                    	<div class="fileSelect">
                    		<input type="file" name="importFile" id="importFile" class="trueFile" onchange="javascript:document.getElementById('fileContent').value = document.getElementById('importFile').value;"><!-- 注意：將input[file] 選取檔案後的URI字串導出 -->
                            <label for="importFile" class="customBtn"><span class="hiddenItem">匯入</span></label><!-- 注意：input[file] 的id與name 須與label[for]內相同 -->
                            <input type="text" id="fileContent" class="url sized-text-normal" readonly>
                            <span class="fakeBtn sized-text-normal">匯入</span>
                            <div class="clearFloat"></div>
                        </div>
					</td>
                </tr>
            </table>
            
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
            
            <table cellpadding="0" cellspacing="0" class="topAndLeftHeader timeTable">
            	<tr>
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
                </tr>
            </table>
            <div class="spacingBlock"></div>
            <div><span class="hor-box-text-normal"><button type="button" class="optionSaveBtn btnDisabled" disabled><span class="sized-text-1">儲存</span></button></span></div><!-- 按鈕不可使用 -->
            <!-- <div><span class="hor-box-text-normal"><button type="button" class="optionSaveBtn"><span class="sized-text-1">儲存</span></button></span></div> 按鈕可以使用 -->
        </div>