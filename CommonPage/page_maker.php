                    <div class="pageMaker">
                        <div class="shell">
                            <ul class="fast">
								
                                <li class="first">
									<a href="<?php if($PAGE == 1){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=1<?php }?>" title="前一頁">
										<span <?php if($PAGE == 1){?>class="disable"<?php }?>>＜＜</span><span class="hideItem">第一頁</span>
									</a>
								</li>
                                <li class="prev">
									<a href="<?php if($PAGE == 1){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo ((int)$PAGE-1);?><?php }?>" title="前一頁">
										<span <?php if($PAGE == 1){?>class="disable"<?php }?>>＜</span><span class="hideItem">前一頁</span>
									</a>
								</li>
                                <li class="next">
									<a href="<?php if($PAGE == $PAGE_ALL){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo ((int)$PAGE+1);?><?php }?>" title="下一頁">
										<span <?php if($PAGE == $PAGE_ALL){?>class="disable"<?php }?>>＞</span><span class="hideItem">下一頁</span>
									</a>
								</li>
                                <li class="last">
									<a href="<?php if($PAGE == $PAGE_ALL){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $PAGE_ALL;?><?php }?>" title="最末頁">
										<span <?php if($PAGE == $PAGE_ALL){?>class="disable"<?php }?>>＞＞</span><span class="hideItem">最末頁</span>
									</a>
								</li>
                            </ul>
                            <ul class="num">
								<?php if($PAGE_ALL > $MAX_PAGE){ ?><!--8頁以上要收起 LR_NUM-->
									<?php if(($PAGE + $LR_NUM -1) + 1 >= ($PAGE_ALL - $LR_NUM) + 1){ ?><!--最後幾頁點點點移到前面-->
										<li><span class="hint">...</span></li>
										<?php for($i=($PAGE_ALL - $LR_NUM *2);$i<=$PAGE_ALL;$i++){ ?>
											<li>
											<a href="<?php if($i == $PAGE){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $i;?><?php }?>">
											<span <?php if($i == $PAGE){ ?>class="current"<?php }?>><?php echo $i;?></span>
											</a>
											</li>
										<?php } ?>
									<?php }else if($PAGE <= ($LR_NUM *2)){?><!--前幾頁點點點在後面-->
										<?php for($i=1;$i<=($LR_NUM *2)+1;$i++){ ?>
											<li>
											<a href="<?php if($i == $PAGE){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $i;?><?php }?>">
											<span <?php if($i == $PAGE){ ?>class="current"<?php }?>><?php echo $i;?></span>
											</a>
											</li>
										<?php }?>
										<li><span class="hint">...</span></li>
									<?php }else{?>
										<?php for($i=$PAGE;$i<=$PAGE + $LR_NUM -1;$i++){?>
											<li>
											<a href="<?php if($i == $PAGE){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $i;?><?php }?>">
											<span <?php if($i == $PAGE){ ?>class="current"<?php }?>><?php echo $i;?></span>
											</a>
											</li>
										<?php }?>
										<?php if(($PAGE + $LR_NUM -1) + 1 < ($PAGE_ALL - $LR_NUM) + 1){ ?>
											<li><span class="hint">...</span></li>
										<?php }?>
										<?php for($i=($PAGE_ALL - $LR_NUM)+1;$i<=$PAGE_ALL;$i++){ ?>
											<li>
											<a href="<?php if($i == $PAGE){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $i;?><?php }?>">
											<span <?php if($i == $PAGE){ ?>class="current"<?php }?>><?php echo $i;?></span>
											</a>
											</li>
										<?php }?>
									<?php }?>
								<?php }else{ ?>
									<?php for($i=1;$i<=$PAGE_ALL;$i++){ ?>
										<li>
										<a href="<?php if($i == $PAGE){?>javascript:void();<?php }else{?><?php echo $URL_STR;?>&PAGE=<?php echo $i;?><?php }?>">
										<span <?php if($i == $PAGE){ ?>class="current"<?php }?>><?php echo $i;?></span>
										</a>
										</li>
									<?php }?>
								<?php }?>
                            </ul>
                        </div>
                    </div>