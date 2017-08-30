var timeron = true;
var bbrecent = false;
var stopbb;
var bbslidetimer;

$(document).ready(function(){
	if(Math.abs($("#neatbbslides").attr("numslides")) > 0) { 
		$('#slide1g').imagesLoaded(function() {
			$("#loadingbb").hide();
			resizeImgToBillboard($("#slide1g"),"neatbb");
			$("#slide1").fadeIn(Math.abs($("#neatbbslides").attr("bbspeed")), function() {
				previewEffects('1');		  
				});

			$("#neatbbslides").attr("cbb","1");
			if(Math.abs($("#neatbbslides").attr("numslides")) > 1) { 
				bbslidetimer = setTimeout(function(){   neatbbswap(2) },$("#neatbbslides").attr("bbtime"));
			}
		});
	}

	if($("#neatbbslides").attr("data-admin") !== "1") { 
		if($("#neatbbslides").attr("bill_placement") !== "full") { 
			sizebillboardheight();
		} 
		sizebillboardfonts();
		sizebillboardfonttop();
	}
 });


	$(window).resize(function() {
	if($("#neatbbslides").attr("bill_placement") !== "full") { 
		sizebillboardheight();
	}
	sizebillboardfonts();
	sizebillboardfonttop();
	});

	var lastScrollTop = 0;

	$(window).scroll(function(){
		if($("body").width() > 800) { 
			if($("#neatbbslides").attr("data-parallax") == "1") { 
				 $(".billboardphoto").css({"transform": "translate3d(0px, "+$(document).scrollTop().valueOf() / 2 +"px, 0px)"});
			}
		}
	  });


/* #############################  BILLBOARD FUNCTIONS ####################################### */

function sizebillboardheight() { 
	if($("#neatbbslides").attr("bill_placement") == "insidecontainer") { 
		if($("#billtext").parent().width() > Math.abs($("#neatbbslides").attr("innermaxwidth"))) { 
			pl = ($("#billtext").parent().width() - Math.abs($("#neatbbslides").attr("innermaxwidth"))) / 2;
			$("#billtext").css({"width":Math.abs($("#neatbbslides").attr("innermaxwidth"))+"px", "left":pl+"px"});
		} else { 
			$("#billtext").css({"width":"100%", "left":"0px"});
		}
	} else { 
		$("#billtext").css({"width":"100%", "left":"0px"});
	}
	hp = Math.abs($("#neatbbslides").attr("bh")) / 1024;
	nh = $("#billtext").width() * hp;
	$("#neatbbslides").css({"height":nh+"px"});

}
function sizebillboardfonttop() { 
	$(".billtextinner").each(function(i){
	divtop = $(this).attr("tp");
	divleft = $(this).attr("lp");
	tpix = ($("#neatbbslides").height() * divtop) / 100;
	lpix = ($("#neatbbslides").width() * divleft) / 100;
		 $(this).css({"top":tpix+"px", "left":lpix+"px"});
	});
}


	function sizebillboardfonts() { 
		$(".billtextinner div").each(function(i){
			fs = Math.abs($(this).attr("font-size").replace("px", ""));
			fp = fs / 1024;
			nfs = $("#billtext").width() * fp;
			$(this).css({"font-size":nfs+"px"});
		});
	}
	function previewEffects(x) { 
		to = 0;
		var timer = new Array();
		$(".billtextinner").hide();
		$(".thebbtext"+x).each(function() {
			tid = $(this).attr("textid");
			time = Math.abs($(this).attr("slide_text_1_time"));
			effect = $(this).attr("slide_text_1_effect");
	//		alert(effect+" "+time);
			previeweffecttimeout(tid,to,time,effect,x)
			to = to + time;
		});

	}

function previeweffecttimeout(tid,to,time,effect,x) { 
	var timer = new Array();
	timer[tid] = setTimeout(function() {
		showAnimation(tid,time,effect,x);

	}, to);
}


function showAnimation(tid,time,effect,x) { 
		left = Math.abs($("#text"+x+""+tid).attr("x"));
		tp = Math.abs($("#text"+x+""+tid).attr("y"));



		width = $("#text"+x+""+tid).width();
		$("#billtext"+x+""+tid).css({"display":"none", "visibility":"none"});

		mid = ((1024 * left) / 100) + (width / 2);
		midperc = (mid / 1024); 

		nl = ($("#billtext").width() * midperc) - (width / 2);

		dif = (1024 / $("#billtext"+x+""+tid).parent().width());
		addwidth = (((width * dif) / 100) / 100) * width;
		l = Math.abs($("#billtext"+x+""+tid).parent().width()) * (Math.abs(left) / 100);
		l = left + dif ;
		t = Math.abs($("#billtext"+x+""+tid).parent().height()) * (Math.abs(tp) / 100);
		if(left <=1) { 
			nl = 1;
		}

		// $("#log").show().html("left: "+left+" width: "+width+"    &nbsp; parent width: "+$("#billtext"+x+""+tid).parent().width()+" difference: "+dif+" L: "+l+" middle: "+mid+" mid percent: "+midperc+" new left: "+nl+"");
		//$("#log").show().html($("#billtext").parent().width());

		if(bbrecent !== true) { 
			//$("#billtext"+x+""+tid).css({ "left":nl+"px","top":t+"px"});
		}
		
		if(effect == "fadeIn") {
			$("#billtext"+x+""+tid).stop(true,true).fadeIn(time);
		}
		if(effect == "slideDown") {
			$("#billtext"+x+""+tid).stop(true,true).slideDown(time);
		}
		if(effect == "showleft") {
			$("#billtext"+x+""+tid).css({"display":"inline", "visibility":"visible", "left":"-=60"});
			$("#billtext"+x+""+tid).stop(true,true).animate({"opacity":"1", "left":"+=60"},time, "easeOutBack");
		}

		if(effect == "showright") {
			$("#billtext"+x+""+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "left":"+=60"});
			$("#billtext"+x+""+tid).stop(true,true).animate({"opacity":"1", "left":"-=60"},time, "easeOutBack");
		}
		if(effect == "showtop") {
			$("#billtext"+x+""+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "top":"-=60"});
			$("#billtext"+x+""+tid).stop(true,true).animate({"opacity":"1", "top":"+=60"},time, "easeOutBack");
		}

		if(effect == "showbottom") {
			$("#billtext"+x+""+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "top":"+=60"});
			$("#billtext"+x+""+tid).stop(true,true).animate({"opacity":"1", "top":"-=60"},time, "easeOutBack");
		}

}


function removebbtext() { 
	$(".billtextinner").fadeOut(200);
}


function neatbbswapclick(x) { 
	window['timeron'] = false;
	window.clearInterval(bbslidetimer);
	$("#neatbbslides").attr("bbspeed",100)
		$(".neatbbslide").each(function(i){
			var this_id = this.id;
				$("#"+this_id+"g").removeClass("burnsin").removeClass("burnsout");	
		});
	$(".bbtext").hide();
	if(x !== $("#neatbbslides").attr("cbb")) { 
		neatbbswap(x);
	}

}

function checkBBText(x) { 
	var thisspeed1 = Math.abs($("#billtextinner"+x).attr("tm1"));
	var thisspeed2 = Math.abs($("#billtextinner"+x).attr("tm2"));

	if($("#billtextinner"+x).attr("eff1") == "fadeIn") { 
			$("#text"+x+"a").stop(true,true).fadeIn(thisspeed1);
	}
	if($("#billtextinner"+x).attr("eff1") == "slideDown") { 
			$("#text"+x+"a").stop(true,true).slideDown(thisspeed1);
	}

	setTimeout(function() {
	if($("#billtextinner"+x).attr("eff2") == "fadeIn") { 
		$("#text"+x+"b").stop(true,true).fadeIn(thisspeed2);
	}
	if($("#billtextinner"+x).attr("eff2") == "slideDown") { 
		$("#text"+x+"b").stop(true,true).slideDown(thisspeed2);
	}

	}, thisspeed1);

}

 function neatbbswap(x) { 
	removebbtext();

	$(".slidenav li").removeClass("bnon");

	$("#bn"+x).addClass("bnon");
	resizeImgToBillboard($("#slide"+x+"g"),"neatbb");

	if($("#neatbbslides").attr("trans") == 'neatbbfade') { 
		neatbbfade(x);
	}
	if($("#neatbbslides").attr("trans") == 'neatbbslidedown') { 
		neatbbslidedown(x);
	}

	if($("#neatbbslides").attr("trans") == 'neatbbslideswap') { 
		neatbbslideswap(x);
	}
	if($("#neatbbslides").attr("trans") == 'neatbbslidelr') { 
		neatbbslidelr(x);
	}
	// alert(stopbb+" + "+timeron+" + "+$("#neatbbslides").attr("bbtime")+" + "+x+" + "+loop);
	if(timeron == true) { 
		if(x == Math.abs($("#neatbbslides").attr("numslides"))) { 
			n = 1;
			if($("#neatbbslides").attr("loopslides") !== "true") { 
				var stopbb = true;
			}
		} else { 
			n = x+1;
		}
		if(stopbb !== true) { 
			if(timeron == true) { 
				bbslidetimer = window.setTimeout(function(){   neatbbswap(n) },$("#neatbbslides").attr("bbtime"));
			}
		}
	}
 }


 function neatbbfade(x) { 
	$(".bbtext").each(function() {
		$(this).fadeOut(Math.abs($("#neatbbslides").attr("bbspeed")));
	});

	$(".neatbbslide").each(function(i){
		var this_id = this.id;
		$("#"+this_id).fadeOut(Math.abs($("#neatbbslides").attr("bbspeed")), function() { 
				// $("#"+this_id+"g").removeClass("burnsin").removeClass("burnsout");	
				// $("#log").show().append(this_id+" | ");
		});
	});
	if(window['timeron'] == true) { 
		$("#slide"+x).addClass($("#slide"+x).attr("data-effect"));
	}
	$("#slide"+x).fadeIn(Math.abs($("#neatbbslides").attr("bbspeed")), function() { 
		 previewEffects(x);

		$(".neatbbslide").each(function(i){
			var this_id = this.id;
			if(this_id !== "slide"+x) { 
				// $("#"+this_id+"g").removeClass("burnsin").removeClass("burnsout");	
				// $("#log").show().append(this_id+" - "+x+" | ");
			}
		});

	 });

	$("#neatbbslides").attr("cbb",x);

 }

function neatbbslidedown(x) { 
	$("#slide"+$("#neatbbslides").attr("cbb")).stop(true,true).animate({ 	  
		marginTop:$("#neatbbslides").attr("bbheight")+'px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() {  });

	$("#slide"+x).css('margin-top', '-'+$("#neatbbslides").attr("bbheight")+'px');
	$("#slide"+x).css('display', 'inline');

	$("#slide"+x).stop(true,true).animate({ 	  
		marginTop:'0px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() { 	
			$("#neatbbslides").attr("cbb",x);  
	});
}

function neatbbslideswap(x) { 
	$("#slide"+$("#neatbbslides").attr("cbb")).stop(true,true).animate({ 	  
		marginTop:$("#neatbbslides").attr("bbheight")+'px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() {  });

	$("#slide"+x).css('margin-top', $("#neatbbslides").attr("bbheight")+'px');
	$("#slide"+x).css('display', 'inline');

	$("#slide"+x).stop(true,true).animate({ 	  
		marginTop:'0px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() { 	
			$("#neatbbslides").attr("cbb",x);  
	});
}


function neatbbslidelr(x) { 
	$("#slide"+$("#neatbbslides").attr("cbb")).stop(true,true).animate({ 	  
		marginLeft:'-'+$("#neatbbslides").attr("bbwidth")+'px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() {  });

	$("#slide"+x).css('margin-left', $("#neatbbslides").attr("bbwidth")+'px');
	$("#slide"+x).css('display', 'inline');

	$("#slide"+x).stop(true,true).animate({ 	  
		marginLeft:'0px'
		}, Math.abs($("#neatbbslides").attr("bbspeed")), function() { 	
			$("#neatbbslides").attr("cbb",x);  
	});

}


function resizeImgToBillboard(bgImg,where) {
	noupsize = 0;
	captionPhotoOn = 0;
	showScroll = 0;
	landscapeLeft = 0;
	portraitLeft = 0;
	containheight = 0;
	containwidth = 0;
	fullscreenmenu = 0;
	disableshowscroll = true;
//	alert("ok");
//	add_pad = parseInt(bgImg.css("paddingLeft").replace("px", "") )*2;
//	add_border = parseInt(bgImg.css("border-left-width").replace("px", "")) * 2;
	add_pad = 0;
	add_border = 0;
//	alert(add_pad+" "+add_border);
	var imgwidth = parseInt(bgImg.attr("ww") );
	var imgheight = parseInt(bgImg.attr("hh"));

//	var imgwidth = bgImg.width();
//	var imgheight = bgImg.height();

//	alert(imgwidth +" X "+imgheight);
	if(where == "window") { 
		var winwidth = $(window).width();
		if((add_menu_height == true)&&((imgheight < ($(window).height() - $("#blogFullScreenMenu").outerHeight())))&&(noupsize == 1)) { 
			addMenu = true;
		}
		if(containheight == "1" &&imgheight > imgwidth) { 
			addMenu = true;
		}
		if(containwidth == "1" &&imgwidth > imgheight) { 
			addMenu = true;
		}
//			alert(imgheight +" < "+ $(window).height() +" - "+$("#blogFullScreenMenu").outerHeight());
		if(addMenu == true) {
			winheight = $(window).height() - $("#blogFullScreenMenu").outerHeight();
		} else { 
			winheight = $(window).height();
		}
	} else {
		var winwidth = $("#"+where).width();
		var winheight = $("#"+where).height() ;
		var thewinheight = $("#"+where).height();
	}

	var widthratio = winwidth / imgwidth;
	var heightratio = winheight / imgheight;
	var widthdiff = heightratio * imgwidth;
	var heightdiff = widthratio * imgheight;
//	 alert(winwidth+' X '+winheight+' image: '+imgwidth+' X '+imgheight);
	if((noupsize == '1')&&((imgwidth<winwidth)||(imgheight<winheight))) {
//	 alert("AAAAAA");
		if(imgheight > winheight) { 
			if(imgheight > imgwidth) {
				leftmove = portraitLeft;
			} else {
				leftmove = landscapeLeft;
			}

			mt = 0;
			if(fullscreenmenu == true) { 
				menuTop = $("#blogFullScreenMenu").css('top').replace("px", "");
			} else {
				menuTop = false;
			}

			if(menuTop <= 0) { 
				menuHeight = $("#blogFullScreenMenu").outerHeight();
				mt = menuHeight;
			}
			bgImg.css({
			  width:'auto',
			  height: winheight+'px',
			marginLeft: leftmove

			});
		} else {
	 // alert("BBBBBB");

		if(imgheight > imgwidth) {
			leftmove = portraitLeft;
		} else {
			leftmove = landscapeLeft;
		}
			mt = (winheight - imgheight) / 2;
			if(fullscreenmenu == true) { 
				menuTop = $("#blogFullScreenMenu").css('top').replace("px", "");
			} else {
				menuTop = false;
			}

			if(menuTop <= 0) { 
				menuHeight = $("#blogFullScreenMenu").outerHeight();
				mt = mt + menuHeight;
			}



			if(imgwidth > winwidth) { 
						newwidth  = winwidth;
//			alert(newwidth);
			height = imgheight *widthratio;

			bgImg.css({
			width:newwidth+'px',
			height: height+'px',
		marginLeft: landscapeLeft

			});
			} else { 

	//			alert("WTF");
				bgImg.css('margin-top',''+mt+'px');
				bgImg.css('margin-left',leftmove);
			}
		}
	} else if((imgheight > imgwidth)&&(containheight =='1')) { 
//	 alert("CCCCCCC");

			mt = 0;
			if(fullscreenmenu == true) { 
				menuTop = $("#blogFullScreenMenu").css('top').replace("px", "");
			} else {
				menuTop = false;
			}

			if(menuTop <= 0) { 
				menuHeight = $("#blogFullScreenMenu").outerHeight();
				mt = menuHeight;
			}

        bgImg.css({
          width:'auto',
		'margin-top': ''+mt+'px',

          height: winheight- add_pad - add_border+'px',
		marginLeft: portraitLeft
        });
	} else if((imgwidth >= imgheight)&&(containwidth =='1')) { 
//	 alert("DDDDDDD");

		if(heightratio >widthratio ) {
			height = imgheight *widthratio;
			if(height < winheight) { 
				mt = Math.abs((winheight - height) / 2);
			} else {
				mt = '0';
			}
			bgImg.css({
				width:winwidth- add_pad - add_border+'px',
				height: height- add_pad - add_border+'px', 
				marginTop: mt+'px',
				marginLeft: landscapeLeft

			});
		} else { 
			mt = 0;
			if(fullscreenmenu == true) { 
				menuTop = $("#blogFullScreenMenu").css('top').replace("px", "");
			} else { 
				menuTop = 0;
			}

			if(menuTop <= 0) { 
				menuHeight = $("#blogFullScreenMenu").outerHeight();
				mt = menuHeight;
			}

		newwidth  = (winheight- add_pad - add_border) * (imgwidth / imgheight);
//			alert(newwidth);

			bgImg.css({
			width:newwidth+'px',
			'margin-top': ''+mt+'px',
			height: winheight- add_pad - add_border+'px',
		marginLeft: landscapeLeft

			});
		}


	} else { 


		if(heightdiff>winheight) {
			borderW = parseInt(bgImg.css("border-left-width"));
			bgImg.css({
				width: winwidth+'px',
				'margin-left': '-'+borderW+'px',
				height: heightdiff+'px'
			});
			mt = (heightdiff - winheight) / 2;


			if(mt > 0) {
			//	alert(mt);
				bgImg.css('margin-top','-'+mt+'px');
				showScroll = 1;
			}
		} else {

			if(widthdiff > winwidth) {
				ml = Math.abs((widthdiff - winwidth )/ 2);
			} else {
				ml= '0';
			}

			bgImg.css({
				width: widthdiff+'px',
				height: winheight+'px',
				marginLeft: '-'+ml+'px'
			});		
		}
	}
	newml = $(bgImg).outerWidth() / 2;
	newmt = ((thewinheight -  $(bgImg).height()) / 2);
//	bgImg.css('margin-left','-'+newml+'px');
	bgImg.css('margin-top',''+newmt+'px');

//	alert(newmt+' - '+winheight +' - '+ $(bgImg).outerHeight());
	if(bgImg.attr("data-margin-top") == "null") { 
		bgImg.attr("data-margin-top",newmt)
	}
	// alert(bgImg.attr("data-margin-top"));
} 



function scrolltocontent() { 
   $('html').animate({scrollTop:$("#neatbbslides").height()}, 'slow'); 
    $('body').animate({scrollTop:$("#neatbbslides").height()}, 'slow'); 
}



/*########################### END BILLBOARD FUCTIONS ########################################## */