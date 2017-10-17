        <table cellpadding="0" cellspacing="0" class="topMenu item-5">
        	<tr>
				<?php for($i=0;$i<count($FunctionArr);$i++){?>
            	<td><a href="<?php echo $FunctionArr[$i][1];?>" class="hor-box-text"><span><?php echo $FunctionArr[$i][0];?></span></a></td>
				<?php }?>
                <!--<td><a href="#" class="hor-box-text"><span>課表範本管理</span></a></td>
                <td><a href="#" class="hor-box-text"><span>使用者管理－客戶</span></a></td>
                <td><a href="#" class="hor-box-text"><span>使用者管理－劍聲</span></a></td>
                <td><a href="#" class="hor-box-text"><span>系統參數</span></a></td>-->
            </tr>
        </table>