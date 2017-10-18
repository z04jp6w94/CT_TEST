// 載入需要套件
	$.getScript("/Js/blur.js");
// 預設值
	var _UsWidth = "";																		//User 螢幕寬
	var _UsHeight = "";																		//User 螢幕高
	var _UsDocWidth = "";																	//Html 寬
	var _UsDocHeight = "";																	//Html 高
	var _PathBackOffice = "/UpLoad/BackOffice/UI/";											//後台相關檔案位置
	var _FirstAnimate = true;																//是否已執行載入動畫
	var _BoxAppVisible = false;																//應用服務面板是否已開啟
	var _BoxThemeVisible = false;															//配色方案面板是否已開啟
	var _BoxThemeSelected = false;
// document ready
	$(document).ready(function(){
	//預設參數
		_UsWidth = $(window).width();														//User 螢幕寬
		_UsHeight = $(window).height();														//User 螢幕高
		_UsDocWidth = $(document).width();													//Html 寬
		_UsDocHeight = $(document).height();												//Html 高
		SetHW();																			//設定 UI 寬高
	//config Plugin
		$("#tipBtnF, #tipBtnP, #tipBtnN, #tipBtnE").tipsy({gravity: 'n'});					//Set Tip
		$("#BtnLeftFun, #BtnAppFun, #BtnThemeFun, #BtnSettingsFun, .xs-tiles").tipsy({gravity: 'w'});		//Set Tip
	//移除連結虛線
		$("a").focus(function(){
			$(this).blur();
		});
	//box-theme.click
		$("#BtnThemeFun").click(function(){
			MoveTheme();
		});
		$(".xs-tiles").click(function(){
			_BoxThemeSelected = true;
			var themesName = $(this).attr("data-toggle");
			var themesTree = $(this).attr("data-treeicon");			
			var $iframe = $("#Left"), $LeftTreeFrame = $iframe.contents();
//			$("#StyleThemes").attr("href", filePath + fileName).load(function(){});
			$("#FrameTopLeft").fadeOut(0);
			$("#StyleThemes").attr("href", "/Css/MaintainThemes/" + themesName + "/" + themesName + ".css");
			$LeftTreeFrame.find("#StyleThemes").attr("href", "/Css/MaintainThemes/" + themesName + "/" + themesName + ".css");
			$LeftTreeFrame.find(".TreeViewIcon").attr("src", "/Upload/BackOffice/UI/Icon_TreeView01_" + themesTree + ".png");
			$("#FrameTopLeft").fadeIn(0);
			setTimeout(function () {
				_BoxThemeSelected = false;
			}, 1000);
		//紀錄
			$.post(
				"/Maintain/Settings/Settings_SetUSERThemes.php",
				{Themes: themesName},
				function(xml){
					if($('resu', xml).text() == '1'){
						
					}
				}
			);
		});
	//appbox.click
		$("#BtnAppFun").click(function(){
			MoveApp();
		});
		$(".appcol a").click(function(){
			var _ProdID = $(this).attr("data-prod");
			$.post(
				"/Maintain/Settings/Settings_SetProd.php",
				{ProdID: _ProdID},
				function(xml){
					if($('resu', xml).text() == '1'){
						//$("#Left").location.reload();
						window.parent.Left.document.location.reload();
					}
				}
			);
		});
	//html.click
		$("html").click(function(){
			switch(window.location.pathname.split('/').pop()){
				case 'Frm_Menu.php':
//					if($(".box-theme").click()){
//						alert('a');
//					}
					if(_BoxAppVisible){
						parent.CloseApp();
						_BoxAppVisible = false;
					}
					if(_BoxThemeVisible && !_BoxThemeSelected){
						parent.CloseTheme();
						_BoxThemeVisible = false;
					}
					break;
				default:
					parent.CloseApp();
					parent.CloseTheme();
			};
		});
	//設定treeview高度
		$("#FrameLineImg, #FrameLine, #BtnLeftFun").click(function(){
			MoveW();
		});
	//TreeView - 顯示控制	
		$numberSpan = $("#TreeView").find('.TreeviewSpan');
		$numberSysFile = $("#TreeView").find('.TreeviewSysFile');
		$numberFncFile = $("#TreeView").find('.TreeviewFncFile');
	//TreeView - 設定功能選項點選狀態
		$numberSpan.click(function(){
		//	$(".TreeviewSpan").css({background: ''});
		//	$numberSpan.eq($(".TreeviewSpan").index(this)).css({background: 'url(/Images/Bg_02.gif)'});
		//	$(".TreeviewSpan a").css({color: '#656d78'});
			$(".TreeviewSpan a").addClass("FunLink");
			$(".TreeviewSpan a").removeClass("FunLink, FunLinkhover");
			$(".TreeviewSpan a").hover(function(){
				//$(this).css("color", "#fff");
				$(this).removeClass("FunLink").addClass("FunLinkhover");
			}, function(){
				//$(this).css("color", "#656d78");
				$(this).removeClass("FunLinkhover").addClass("FunLink");
			});
		/*
			$(".TreeviewSpan a").css({color: '#656d78'});
			$(".TreeviewSpan a").hover(function(){
				$(this).css("color","#fff")
			});
			*/
			
			$numberSpan.eq($(".TreeviewSpan").index(this)).find("a").addClass("FunLinkhover");
			$numberSpan.eq($(".TreeviewSpan").index(this)).find("a").hover(function(){
				$(this).removeClass("FunLink").addClass("FunLinkhover");
			}, function(){
				$(this).removeClass("FunLink").addClass("FunLinkhover");
			});
/*
			$numberSpan.eq($(".TreeviewSpan").index(this)).find("a").css("color", "#fff");
			$numberSpan.eq($(".TreeviewSpan").index(this)).find("a").hover(function(){
				$(this).css("color", "#fff");
			}, function(){
				$(this).css("color", "#fff");
			});
*/
		});
	//TreeView - 設定系統資料夾開啟關閉
		$numberSysFile.click(function(){
			$TreeId = $("#TreeView").find($(this).attr('treeid'));
			$TreeImgId = $("#TreeView").find($(this).attr('treeimgid'));
			if($(this).attr('class') == "TreeviewSysFile"){
				if($TreeId.css('display') == 'none'){
					$TreeId.stop().slideDown(250);
//					$TreeId.css({display: "block"});
					$TreeImgId.attr({src: "/Images/s4-14.gif"});
				}else{
					$TreeId.stop().slideUp(250);
//					$TreeId.css({display: "none"});
					$TreeImgId.attr({src: "/Images/s4-15.gif"});
				}
			}
		});
	//TreeView - 設定功能資料夾開啟關閉
		$numberFncFile.click(function(){
			$TreeId = $("#TreeView").find($(this).attr('treeid'));
			$TreeImgId = $("#TreeView").find($(this).attr('treeimgid'));
			$TreeFileimgid = $("#TreeView").find($(this).attr('treeFileimgid'));
//			$(".TreeviewFncFile").css({background: ''});
			$(".TreeviewFncFile").removeClass("TreeSel");
			if($TreeId.css('display') == 'none'){
//				$TreeId.css({display: "block"});
				$TreeId.stop().slideDown(250);
//				$(this).css({background: '#2c3439'});
			// 資料夾變色 20160418 jimmychao 取消
				//$(this).addClass("TreeSel");
				//$TreeImgId.attr({src: _PathBackOffice + "Icon_arrowOn.png"});
				$TreeImgId.removeClass('fa-angle-right').addClass('fa-angle-down');
//				$TreeFileimgid.attr({src: "/Images/s4-18.gif"});
			}else{
//				$TreeId.css({display: "none"});
				$TreeId.stop().slideUp(250);
				//$TreeId.css({height: 800});
//				$TreeImgId.attr({src: _PathBackOffice + "Icon_arrowOff.png"});
				$TreeImgId.removeClass('fa-angle-down').addClass('fa-angle-right');
//				$TreeFileimgid.attr({src: "/Images/s4-17.gif"});
			}
		});
	//TreeView - 功能選項移過
//		$numberSpan.hover(function(){
//			if($numberSpan.eq($(".TreeviewSpan").index(this)).css('background-image') == 'none'){
//				$numberSpan.eq($(".TreeviewSpan").index(this)).css({background: '#e9eef5'});
//			}
//		}, function(){
//			if($numberSpan.eq($(".TreeviewSpan").index(this)).css('background-image') == 'none'){
//				$numberSpan.eq($(".TreeviewSpan").index(this)).css({background: ''});
//			}
//		});
	//TreeView - 系統資料夾移過
		$numberSysFile.hover(function(){
			$(".TreeviewSysFile").css({background: ''});
	//		$numberSysFile.eq($(".TreeviewSysFile").index(this)).css({background: '#f6f6f6'});
			$numberSysFile.eq($(".TreeviewSysFile").index(this)).css({background: 'url(/Images/Bg_01.gif)'});
		}, function(){
			$(".TreeviewSysFile").css({background: ''});
		});
	//TreeView - 功能資料夾移過
		$numberFncFile.hover(function(){
			//$(".TreeviewFncFile").css({background: ''});
			//$numberFncFile.eq($(".TreeviewFncFile").index(this)).css({background: '#f6f6f6'});
			//$numberFncFile.eq($(".TreeviewFncFile").index(this)).css({background: 'url(/Images/Bg_01.gif)'});
		}, function(){
			//$(".TreeviewFncFile").css({background: ''});
		});
	//一般畫面控制
	//取得 #MainTopMenu 及其 top 值
		var $MainTopMenu = $('#MainTopMenu');
		var	_top = 0;
		if ($('#MainTopMenu').length > 0) {
			_top = $MainTopMenu.offset().top;
		}
//			_ShHeight = $("#MainTip").height(),
		var	$MainTitle = $('#MainTitle');
	//網頁捲軸捲動時
		var $win = $(window).scroll(function(){
		// 如果現在的 scrollTop 大於原本 #MainTopMenu 的 top 時
			if($win.scrollTop() > _top){
			// 如果 $cart 的座標系統不是 fixed 的話
				if($MainTopMenu.css('position') != 'fixed'){
				// 設定座標系統為 fixed
					$MainTopMenu.css({
						position: 'fixed',
						top: 0
					})/*,
					$MainTitle.css({
						position: 'fixed',
						top: 46
					});*/
				// 設定 #MainTip 座標系統為 fixed
//					if($('#MainTip').css('display') == 'block'){
//						$('#MainTip').css({
//							position: 'fixed',
//							top: 76							
//						}),
//						$('#MainDesc').css({
//							'padding-top': 76 + _ShHeight
//						});
//					}
				}
			}else{
			// 還原 #cart 的座標系統為 absolute
				$MainTopMenu.css({
					position: 'absolute'
				}),
				$MainTitle.css({
					position: 'absolute'
				}),
//				$('#MainTip').css({
//					position: 'relative'
//				}),
				$('#MainDesc').css({
					'padding-top': 46
				});
			}
		});
	//查詢 UI
		$('#MainTip').css("left", (_UsWidth - $('#MainTip').width()) / 2);
		$('#MainTip').css("top", -($('#MainTip').height() + 30));
		$("body").append('<div id="MainTipBg"></div>');
		var _opacity = .6;
		$("#MainTipBg").css({
			height: _UsDocHeight,
			width: _UsWidth,
			opacity: _opacity,
			display: 'none'
		});
		$('#CmBtnSearch').click(function(){
			openTipUI();
		});
		$("#MainTipBg, #TxtTipBtnCnl").click(function(){
			closeTipUI();
		});
	//選單 UI
	/*
		$(".TypeMenu-Title").css("background", "url(../Images/TypeMenuBtnBg-A.png) 0 0 repeat-x");
		$(".TypeMenu-Title").hover(function(){
			$(".TypeMenu-Title").css("background", "url(../Images/TypeMenuBtnBg-Hover.png) 0 0 repeat-x");
			$("ul.TypeMenu .TypeMenu-Top").css("background", "url(../Images/Btn.png) -16px -87px no-repeat");
			$("ul.TypeMenu .TypeMenu-Bottom").css("background", "url(../Images/Btn.png) -24px -87px no-repeat");
		}, function(){
			$(".TypeMenu-Title").css("background", "url(../Images/TypeMenuBtnBg-A.png) 0 0 repeat-x");
			$("ul.TypeMenu .TypeMenu-Top").css("background", "url(../Images/Btn.png) 0 -87px no-repeat");
			$("ul.TypeMenu .TypeMenu-Bottom").css("background", "url(../Images/Btn.png) -8px -87px no-repeat");
		});
		var TypeMenu = false;
		$(".TypeMenu-Title").click(function(){
			$(".TypeMenu-Content").slideDown(150, function(){
				TypeMenu = true;
			});
		})
		$("html").click(function(){
			if(TypeMenu){
				$(".TypeMenu-Content").slideUp(150);
				TypeMenu = false;
			}
		});
	*/
	//頁籤 UI
		var lilength = $(".Tag").find("li").length;
		$(".Tag").find("li").click(function(i){
			var lieq = $(this).index();
			$(".Tag").find("a").removeClass().addClass("SetCursor");
			$(this).find("a").addClass("Select");
			if(lilength - 1 == lieq){
				$(this).find("a").addClass("Selectlast");
			}
			$(".Tag li").find("div").hide().eq(lieq).show();
			$(".Tag").find("a").eq(lilength - 1).addClass("last");
			
			$(".TagContent").find("li.TagContent").hide();
			$(".TagContent").find("li.TagContent").eq(lieq).show();
		}).hover(function(){
			if($(this).find(".Select").length <= 0){
				$(".Tag li").find("div").eq($(this).index()).fadeIn(100);
			}
		},function(){
			if($(this).find(".Select").length <= 0){
				$(".Tag li").find("div").eq($(this).index()).fadeOut();
			}
		});
		$(".TagContent").find("li.TagContent").hide().eq(0).show();
		$(".Tag li").find("div").hide().eq(0).show();
	});
//window load
	$(window).load(function(){
	//圖片縮小
		ImgWH();
	});
//window resize
	$(window).resize(function(){
		_UsWidth = $(window).width();														//User 螢幕寬
		_UsHeight = $(window).height();														//User 螢幕高
		_UsDocWidth = $(document).width();													//Html 寬
		_UsDocHeight = $(document).height();												//Html 高
		SetHW();
	});
//document keydown
	$(document).keydown(function(event){
		switch(event.keyCode){
		//Enter
			case 13:
				switch(window.location.pathname.split('/').pop()){
					case 'Member_login.php':
						$("#BtnSubmit").click();
						break;
					default:
						var position = $('#MainTip').position();
						if(position.top == 0){
							$("#TxtTipBtnSrh").click();
						}
						return false;
						break;
				}
				break;
		//Esc
			case 27:
				closeTipUI();
				parent.CloseApp();
				return false;
				break;
		//F
			case 70:
				if(event.ctrlKey || event.metaKey){
					$('#CmBtnSearch').click();
					return false;
				}
				break;
		//F4
			case 115:
				parent.MoveW();
				return false;
				break;
		//F5
			case 116:
				break;
		//F6
			case 117:
				parent.MoveTheme();
				return false;
				break;
		//F7
			case 118:
				parent.MoveApp();
				return false;
				break;
		}
	});
// function zone
// + 設定資料列表
	function SetGrid(gridColSortTypes, gridColAlign){
		(function(window, document, undefined) {
			"use strict";
			var onColumnSort = function(newIndexOrder, columnIndex, lastColumnIndex) {
				var doc = document;
				if (columnIndex !== lastColumnIndex) {
					if (lastColumnIndex > -1) {
						doc.getElementById("Hdr" + (lastColumnIndex - 1)).parentNode.style.backgroundColor = "";
					}
					doc.getElementById("Hdr" + (columnIndex - 1)).parentNode.style.backgroundColor = "#F1F5FA";
				}
			};
			
			var onResizeGrid = function(newWidth, newHeight) {
				var demoDivStyle = document.getElementById("MainDesc").style;
				demoDivStyle.width = newWidth + "px";
				demoDivStyle.height = newHeight + "px";
			};
			/*
			for (var i=0, col; col=gridColSortTypes[i]; i++) {
				gridColAlign[i] = (col === "number") ? "right" : "left";
			}
			*/
			var myGrid = new Grid("MainGrid", {
					srcType : "dom", 
					srcData : "dataTable", 
					allowGridResize : false,
					allowColumnResize : true, 
					allowClientSideSorting : true, 
					allowSelections : false, 
					allowMultipleSelections : true, 
					showSelectionColumn : true, 
					onColumnSort : onColumnSort, 
					onResizeGrid : onResizeGrid, 
					colAlign : gridColAlign, 
					colBGColors : ["#fafafa", "#fafafa", "#fafafa"], 
					colSortTypes : gridColSortTypes, 
					fixedCols : 3
				});
		})(this, this.document);
	}
//
	function LeftLogo(Path){
		$("#FrameTopLeft img").fadeOut(500, function(){
			$("#FrameTopLeft img").attr("src", Path).fadeIn(1000);
		});
	}
// + 開啟提示視窗
	function openTipUI(){
		var position = $('#MainTip').position();
		if(position.top < 0){
			$('#MainTip').stop().animate({top: 0}, 250);
		}else{
//			$('#MainTip').stop().animate({top: -($('#MainTip').height() + 30)}, 250);
			$('#MainTip').stop().animate({top: 0}, 250);
		}
//		$("#MainTipBg").fadeIn();
		$("#MainTipBg").show();
	// 
		$("#MainTip").find("input").eq(0).focus();
	// 隱藏 select Object 
		$("#Main").find("select").hide();
	// blurjs
		$('#Main').blurjs({
			overlay: 'rgba(255,255,255,0.1)',
			radius: 5
		});
	}
// + 關閉提示視窗
	function closeTipUI(){
		$("#Main").find("select").show();
//		$("#MainTipBg").fadeOut();
		$("#MainTipBg").hide();
		$("#MainTip").stop().animate({top: -($('#MainTip').height() + 30)}, 250);
		$.blurjs('reset');
	}
// + TreeView 隱藏時調整頁面寬度
	function MoveW(){
		if($("#Left").width() == 0){
			MoveInt = 250;
		}else{
			MoveInt = 0;
		}
		$("#FrameLeft").stop().animate({
			width: MoveInt
		},150);
		$("#FrameLine").stop().animate({
			left: MoveInt
		},150);
		$("#FrameRight").stop().animate({
			left: MoveInt,
			width: $(window).width() - MoveInt
		},150);
		$("#main").stop().animate({
			width: $(window).width() - MoveInt
		},150);
		$("#FrameLineImg").stop().animate({
			left: MoveInt
		},150);
		$("#Left").stop().animate({
			width: MoveInt
		},150);
		if($(".box-theme, .box-app").is(":visible")){
			$(".box-theme, .box-app").stop().animate({
				left: MoveInt
			},150);
		}
	}
// + 設定應用服務
	function MoveApp(){
		if($("#Left").width() == 0 && $(".box-app").is(":hidden")){
			$(".box-app").css("left", -200);
		}
		if($("#Left").width() == 250 && $(".box-app").is(":hidden")){
			$(".box-app").css("left", 0);
		}
		if($(".box-app").css("left") == "0px"){
			if($("#Left").width() == 0){
				if($(".box-app").is(":visible")){
					MoveInt = -200;
				}else{
					MoveInt = 0;
				}
			}else{
				MoveInt = 250;
			}
		}else{
			MoveInt = 0;
		}
		$(".box-app").show().stop().animate({
			left: MoveInt
		}, 300, function(){
			if(MoveInt == 0 && $("#Left").width() == 250 || MoveInt == -200){
				$(this).hide();
				_BoxAppVisible = false;
			}else{
				_BoxAppVisible = true;
			}
		});
	}
// + 關閉應用服務
	function CloseApp(){
		if(_BoxAppVisible){
			if($(".box-app").css("left") == "0px"){
				if($("#Left").width() == 0){
					if($(".box-app").is(":visible")){
						MoveInt = -200;
					}else{
						MoveInt = 0;
					}
				}else{
					MoveInt = 250;
				}
			}else{
				MoveInt = 0;
			}
			$(".box-app").show().stop().animate({
				left: MoveInt
			}, 300, function(){
				if(MoveInt == 0 && $("#Left").width() == 250 || MoveInt == -200){
					$(this).hide();
					_BoxAppVisible = false;
				}else{
					_BoxAppVisible = true;
				}
			});
		}
	}
// + 設定配色面板
	function MoveTheme(){
		if($("#Left").width() == 0 && $(".box-theme").is(":hidden")){
			$(".box-theme").css("left", -200);
		}
		if($("#Left").width() == 250 && $(".box-theme").is(":hidden")){
			$(".box-theme").css("left", 0);
		}
		if($(".box-theme").css("left") == "0px"){
			if($("#Left").width() == 0){
				if($(".box-theme").is(":visible")){
					ThemeMoveInt = -200;
				}else{
					ThemeMoveInt = 0;
				}
			}else{
				ThemeMoveInt = 250;
			}
		}else{
			ThemeMoveInt = 0;
		}
		$(".box-theme").show().stop().animate({
			left: ThemeMoveInt
		}, 300, function(){
			if(ThemeMoveInt == 0 && $("#Left").width() == 250 || ThemeMoveInt == -200){
				$(this).hide();
				_BoxThemeVisible = false;
			}else{
				_BoxThemeVisible = true;
			}
		});
	}
// + 關閉配色面板
	function CloseTheme(){
		if(_BoxThemeVisible){
			if($(".box-theme").css("left") == "0px"){
				if($("#Left").width() == 0){
					if($(".box-theme").is(":visible")){
						ThemeMoveInt = -200;
					}else{
						ThemeMoveInt = 0;
					}
				}else{
					ThemeMoveInt = 250;
				}
			}else{
				ThemeMoveInt = 0;
			}
			$(".box-theme").show().stop().animate({
				left: ThemeMoveInt
			}, 300, function(){
				if(ThemeMoveInt == 0 && $("#Left").width() == 250 || ThemeMoveInt == -200){
					$(this).hide();
					_BoxThemeVisible = false;
				}else{
					_BoxThemeVisible = true;
				}
			});
		}
	}
// + 設定頁面寬高
	function SetHW(){
		switch(window.location.pathname.split('/').pop()){
			case 'Frm_Menu.php':
				if(_FirstAnimate){
					_FirstAnimate = false;
					$("#FrameRight").css({
						height	: 25,
						width	: 1,
						top		: (_UsDocHeight / 2) - 12,
						left	: 250
					}).animate({
						width	: 25
					}, 500).delay(300).animate({
						height	: _UsDocHeight - 60,
						top		: 60
					}, 250).delay(125).animate({
						width	: _UsWidth - 250
					}, 500, function(){
						$(this).html('<iframe frameborder="0" src="Menu_Welcome.php" marginheight="0" marginwidth="0" scrolling="auto" allowtransparency="no" name="main" id="main"></iframe>');
						$("#main").width(_UsWidth - 250);
						$("#main").height(_UsDocHeight - 60).hide().delay(300).fadeIn(500);
					});
					$("#FrameLeft").css({
						height	: 25,
						width	: 1,
						top		: (_UsDocHeight / 2) - 12,
						left	: 249
					}).animate({
						width	: 25,
						left	: 225
					}, 500).delay(300).animate({
						height	: _UsDocHeight - 60,
						top		: 60
					}, 250).delay(125).animate({
						width	: 250,
						left 	: 0
					}, 500, function(){
						$(this).html('<iframe frameborder="0" width="250" src="User_Menu.php" marginheight="0" marginwidth="0" scrolling="no" allowtransparency="no" name="Left" id="Left"></iframe>');
						$("#Left").height(_UsDocHeight - 60).hide().delay(300).fadeIn(500);
					});
					$("#comlogo").css({
						"margin-right"	: -360
					}).delay(900).animate({
						"margin-right"	: 0
					}, 750);
					/*
					$("#BtnLeftFun").css({
						width	: 0,
						height	: 0
					}).delay(2100).animate({
						width	: 15,
						height	: 15
					}, 250)
					$("#BtnAppFun img").css({
						width	: 0,
						height	: 0
					}).delay(2350).animate({
						width	: 15,
						height	: 15
					}, 250);
					$("#BtnThemeFun img").css({
						width	: 0,
						height	: 0
					}).delay(2600).animate({
						width	: 15,
						height	: 15
					}, 250);
					*/
					$("#FrameTop").delay(750).animate({
						"margin-top"	: 0
					});
					$("#DivMain").height(_UsDocHeight);
					$("#MainDesc").height(_UsDocHeight - 55);
					$("#MainList, .box-theme, .box-app").height(_UsDocHeight - 45);
				//Set TreeView
					$("#TreeView").height(_UsDocHeight);
				//Set Other
					$('#MainTip').css("left", (_UsWidth - $('#MainTip').width()) / 2);
					$("#MainTipBg").css({
						height: _UsDocHeight,
						width: _UsWidth
					});
				}else{
					$("#DivMain").height(_UsDocHeight),
					$("#FrameLeft").height(_UsDocHeight - 60),
					$("#FrameRight").height(_UsDocHeight - 60),
					$("#main").height(_UsDocHeight - 60),
					$("#Left").height(_UsDocHeight - 60);
					if($("#FrameLeft").width() == 0){
						$("#FrameRight").width(_UsWidth),
						$("#main").width(_UsWidth);
					}else{
						$("#FrameRight").width(_UsWidth - 250),
						$("#main").width(_UsWidth - 250),
						$("#FrameLeft").width(250);
					}
					$("#MainDesc").height(_UsDocHeight - 55);
					$("#MainList, .box-theme, .box-app").height(_UsDocHeight - 45);
				//Set TreeView
					$("#TreeView").height(_UsDocHeight);
				//Set Other
					$('#MainTip').css("left", (_UsWidth - $('#MainTip').width()) / 2);
					$("#MainTipBg").css({
						height: _UsDocHeight,
						width: _UsWidth
					});
				}
				break;
			default:
				$("#DivMain").height(_UsDocHeight),
				$("#FrameLeft").height(_UsDocHeight - 60),
		//		$("#FrameLine").height(_UsHeight - 30),
				$("#FrameRight").height(_UsDocHeight - 60),
				$("#main").height(_UsDocHeight - 60),
				$("#Left").height(_UsDocHeight - 60);
		//		$("#FrameLineImg").css({top: (_UsHeight / 2) - 15});
				if($("#FrameLeft").width() == 0){
					$("#FrameRight").width(_UsWidth),
					$("#main").width(_UsWidth);
				}else{
					$("#FrameRight").width(_UsWidth - 250),
					$("#main").width(_UsWidth - 250),
					$("#FrameLeft").width(250);
				}
				$("#MainDesc").height(_UsDocHeight - 55);
				$("#MainList").height(_UsDocHeight - 45);
			//Set TreeView
				$("#TreeView").height(_UsDocHeight);
			//Set Other
				$('#MainTip').css("left", (_UsWidth - $('#MainTip').width()) / 2);
				$("#MainTipBg").css({
					height: _UsDocHeight,
					width: _UsWidth
				});
		}
	}
// + td color
	function sbar(st) {
		st.style.backgroundColor = '#FFF1F7';
	}
	function cbar(st) {
		st.style.backgroundColor = '';
	}
