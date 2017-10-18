                <li class="systemHelp" style="position:relative;">
                	<span class="hor-box-text"><a id="Helper" href="javascript:;"><span class="hiddenItem">小幫手</span></a></span>
                    
                </li>
                <script type="text/javascript">
				
					$(document).ready(function(){
						var rePosition = function (){
							var _top = $("#Helper").offset().top - 12;
							var _right = $(".breadCrumb").width() * 0.06 - 70;
							if(_right < 0) _right = 0;
							$(".helpBox").css({'top':_top + 'px' , 'right':_right + 'px'});
						};
						$(window).on('resize' , rePosition);
						rePosition();
						$("#Helper").click(function() { 
							$(".helpBox").css("display","block");						
						}); 
						
						$("#CloseBox").click(function() { 
							$(".helpBox").css("display","none");						
						}); 
						
						
					});
				
				</script>
