    	<div class="logoAndUser">
        	<h1><img src="../images/cross_think_logo.gif" alt="Cross Think" width="296" height="72"></h1>
            <ul>
            	<li class="name"><span class="sized-text"><?php echo $USER_NM;?></span></li><!-- 使用者 -->
                <li class="btn"><button type="button" onclick="javascript:location.href='/SystemMaintain/ChangePWD/ChangePWD_Modify.php'"><span class="sized-text">變更密碼</span></button></li><!-- 如果沒有，就不寫入這個LI -->
                <li class="btn"><button type="button" onclick="javascript:location.href='/SystemMaintain/Menu/Member_Logout.php'"><span class="sized-text">LOG OUT</span></button></li><!-- 如果沒有，就不寫入這個LI -->
            </ul>
        </div>
        <div class="clearFloat"></div>