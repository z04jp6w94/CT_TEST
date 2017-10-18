		<script language="javascript" type="text/javascript">
        	/**
			頁籤切換功能
			*/
			function switchTabContent(_btn){
				var currentBtn = _btn;
				var currentCtx = document.getElementById(_btn.getAttribute('current-ctx'));
				var hideBtn = document.getElementById(_btn.getAttribute('hide-btn'));
				var hideCtx = document.getElementById(_btn.getAttribute('hide-ctx'));
				var tmp = currentBtn.getAttribute('class').split(' ');
				currentBtn.setAttribute('class' , tmp[0] + ' current');
				currentCtx.setAttribute('style' , '');
				tmp = hideBtn.getAttribute('class').split(' ');
				hideBtn.setAttribute('class' , tmp[0]);
				hideCtx.setAttribute('style' , 'display:none;');
			}
        </script>