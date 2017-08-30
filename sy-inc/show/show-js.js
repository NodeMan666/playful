/*
$(function() {
    $.fn.scrollBottom = function() {
        return $(document).height() - this.scrollTop() - this.height();
    }; 
	
	var tmmb = $("#tmmb").offset().top;

	var $el = $('#headerAndMenuInner');
	var $window = $(window);
	$window.bind("scroll resize load", function() {
        var gap = $window.height() - $el.height() - 10;
        var visibleFoot = 1 - $window.scrollBottom();
        var scrollTop = $window.scrollTop();
		mb = Math.abs(scrollTop) + Math.abs($window.height());
		mtop = Math.abs(tmmb) - $window.height();
		//$("#log").show().append(mb+" >  tmmb: "+tmmb+" whL "+$window.height()+" Top:  "+mtop+" ||");
        if(mb > tmmb){
            $("#headerAndMenu").css({

                top: "-"+mtop+"px",
					"position":"fixed",
					"height":"100%"
            });


        } else {

            $("#headerAndMenu").css({
                top:  "0px",
                bottom: "auto",
					"position":"absolute",
					"height":"auto"
            });
        }
    });
});
*/

showminimenu = "1";
var mm;
$(document).ready(function(){
	clfmenumenu();

	$(".showclfthumbs").unbind().bind( "click", function() {showclfthumbs() });
	$("#showclfmenumain").unbind().bind( "click", function() {showclfmenumain() });
	$(".buyclfphoto").unbind().bind( "click", function() { clfbuyphoto()});
	$(".clffree").unbind().bind( "click", function() { clffreephoto()});
/*
$("#homefeaturerecent").hover(function() { 
		checkrecentscroll();
	}, function() { 
		$("#homefeaturerecent").css({"overflow-y":"hidden"});
	});
*/


	checkrecentscroll();
		if(showminimenu == "1" && main_full_screen == "1") { 
			 if($("body").width() > 800) { 
				 np = "fixed";
			 } else { 
				 np = "relative";
			 }
			if($("#shopmenucontainer").css("display")!=="none") { 
				shopmenuheight = $("#shopmenucontainer").height();
			} else { 
				shopmenuheight = 0;
			}
			if($("#clfmenu").css("display")!=="none" && $("#clfmenu").css("display")!=="undefined") { 
				clfmenuheight = $("#clfmenu").height();
			} else { 
				clfmenuheight = 0;
			}	

			$("#mainfeature").css({"top": Math.abs($("#headerAndMenu").height()) + Math.abs(clfmenuheight)+"px", "height":$(window).height() - Math.abs($("#headerAndMenu").height()) - Math.abs(shopmenuheight) - Math.abs(clfmenuheight)+"px" });
			placeCLFnav();
			$(".homefeaturerecent").css({"top":$("#headerAndMenu").height() + Math.abs(clfmenuheight)+"px"});
		} else { 
			if(main_full_screen == "1") { 
				// $("#headerAndMenu").hide(); 
			}
		}
		 sizecatphoto('catphoto');
		 sizecatphoto('mainfeature');
		 sizemainfeature();
		sizecatphoto('featsidemenuitem');

		$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"=1&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
			$("#mainfeature").append(data);
			sizecatphoto('mainfeature');
			sizemainfeature();
			if(norightclick == '1') { 
				disablerightclick();
			}


			$("#main-1").imagesLoaded( function() {
				if($("#main-1").find(".homephotos").attr("blurbg") == "1") { 
			//	$("#main-1").find(".homephotos").parent().parent().parent().parent().prepend('<div class="mainphotobg" id="mainphotobg-1" style="position: absolute; left: 0; display: block; background: url(\''+$("#main-1").find(".homephotos").attr("src")+'\') no-repeat center center fixed; background-size: cover;"></div><div class="mainphotobggrid"></div>');
				}

				addclfswipe("main-1");

				
				
				$("#loadingstuff").fadeOut(200);

				setTimeout(function(){ $("#main-1").fadeIn(400)}, 200 );
				sizemainfeature();
				if(totalmslides > 1) { 
					startmainfeature();
					setTimeout(function() { 
						$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"=2&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
							$("#mainfeature").append(data);
							sizecatphoto('mainfeature');
							sizemainfeature();
							if(norightclick == '1') { 
								disablerightclick();
							}

						});
					}, slide_speed);

					setTimeout(function() { 

						if($("#main-"+totalmslides).length == 0) {
							$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"="+totalmslides+"&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
								$("#mainfeature").append(data);
								sizecatphoto('mainfeature');
								sizemainfeature();
								if(norightclick == '1') { 
									disablerightclick();
								}

							});
						}
					}, slide_speed);


				}
			});
	// sideitemsposition();

		if(titleplacement == "br") { 
			if(logoplacement == "br") {
				$("#pagefeattitletext").css({"bottom":$("#mainfeaturelogo").height() + 8+"px","right":"0px","text-align":"right"});
			} else { 
				$("#pagefeattitletext").css({"bottom":"8px","right":"0px","text-align":"right"});
			}
		}
		if(titleplacement == "tl") { 
			if(logoplacement == "tl") {
				$("#pagefeattitletext").css({"top":$("#mainfeaturelogo").height()+"px","left":"0px"});
			} else { 
				$("#pagefeattitletext").css({"top":"8px","left":"0px"});
			}
		}
		if(titleplacement == "bl") { 
			if(logoplacement == "bl") {
				$("#pagefeattitletext").css({"bottom":$("#mainfeaturelogo").height() + 8+"px","left":"0px"});
			} else { 
				$("#pagefeattitletext").css({"bottom":"8px","left":"0px"});
			}
		}
		if(titleplacement == "tr") { 
			if(logoplacement == "tr") {
				$("#pagefeattitletext").css({"top":$("#mainfeaturelogo").height() + 8+"px","right":"0px","text-align":"right"});
			} else { 
				$("#pagefeattitletext").css({"top":"8px","right":"0px","text-align":"right"});
			}
		}


	});

	 $(".mainfeaturenav").click(function() { 
		clearInterval(mainfeatrun);
		$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	 });
 
  $(".homephotos").hover(function(){
        $(this).stop().animate({"opacity":hoveropacity}, 200);
    }, function(){
        $(this).stop().animate({"opacity":initialopacity}, 200);
    });
	$(window).resize(function() {
		if($("#clfthumbs").attr("data-showing") !== "1") { 
			clfmenumenu();
		}
		sizecatphoto('catphoto');
 		sizecatphoto('mainfeature');
		setTimeout(function() { 
			sideitemsposition();
			sizecatphoto('featsidemenuitem');
		}, 200);
		placeCLFnav();
		sizemainfeature();
	});
	$(".featmenu").click(function() { 
		if($("#featmenumain").height() > ($(window).height() - $("#mainfeaturelogo").height())) { 
			$("#featmenumain").css({"height":$(window).height() - $("#mainfeaturelogo").height()+"px", "overflow-y":"scroll" });

		}
		$("#featmenumain").css({"top":$("#mainfeaturelogo").height() + 8+"px","left":"0px"});

		$("#featmenumain").toggle(200);
	});
	showchecksmall();

	$(window).resize(function() {
		showchecksmall();
	});
});

function clfmenumenu() {
	menuwidth = 48;
	$("#clfmoremenu li").hide();
	$( "#clfmenuleft li" ).each(function( index ) {
		if (!$(this).hasClass("moresub")) {
			mainmenuwidth = Math.round($(window).width()) - Math.round($("#clfmenuright").outerWidth()) -  Math.round($("#clfmoremenubutton").outerWidth());  
			checkmenuwidth = menuwidth + Math.round($(this).outerWidth());
			if($(this).attr("id")!=="clfmoremenubutton") { 
				if(Math.round(checkmenuwidth) < Math.round(mainmenuwidth)){ 
					// $("#log").show().append("show: "+$(this).attr("data-menu-item")+" = "+checkmenuwidth+" > "+mainmenuwidth+" - "+$(window).width()+" more: "+Math.round($("#clfmoremenubutton").outerWidth())+"<br> ");
					$("#clfmenuleft").children("[data-menu-item='" +$(this).attr("data-menu-item") + "']").show();
					$("#clfmoremenu").children("[data-menu-item='" +$(this).attr("data-menu-item") + "']").hide();
				} else { 
					mm = true;

					// $("#log").show().append("hide: "+$(this).attr("data-menu-item")+" = "+checkmenuwidth+" > "+mainmenuwidth+" - "+$(window).width()+" more: "+Math.round($("#clfmoremenubutton").outerWidth())+" <br> ");
					$("#clfmenuleft").children("[data-menu-item='" +$(this).attr("data-menu-item") + "']").hide();
					$("#clfmoremenu").children("[data-menu-item='" +$(this).attr("data-menu-item") + "']").show();
				}
			}
			menuwidth = checkmenuwidth;
		}
});
	if(mm == true) { 
		$("#clfmoremenubutton").show();
	} else {
		$("#clfmoremenubutton").hide();
	}
}
function clfbuyphoto() { 
	clearInterval(mainfeatrun);
	$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	buyphoto('',$("#main-"+$("#mainfeature").attr("data-cur")).attr("data-picid"));
}
function clffreephoto() { 
	clearInterval(mainfeatrun);
	$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	freephoto($("#main-"+$("#mainfeature").attr("data-cur")).attr("data-picid"),$("#main-"+$("#mainfeature").attr("data-cur")).attr("data-did"),$("#main-"+$("#mainfeature").attr("data-cur")).attr("data-free"));
}
function fixclfdisplay() { 
	$("#clfdisplay").css({"position":"fixed", "top":"0px","left":"0px","width":"100%"});
}
function unfixclfdisplay() { 
	$("#clfdisplay").css({"position":"relative", "top":"auto","left":"auto","width":"auto"});

	clfmenumenu();
}
function showclfmenumain() { 
	clearInterval(mainfeatrun);
	$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	fixclfdisplay();
	gototop();
	$("#clfmenumain").css({"top":$("#clfmenu").height()+"px"}).slideDown(300);	
	$("#showclfmenumain").unbind().bind( "click", function() {closeclfmenumain() });
}
function closeclfmenumain() { 
	unfixclfdisplay();
	$("#clfmenumain").css({"top":$("#clfmenu").height()+"px"}).slideUp(300);	
	$("#showclfmenumain").unbind().bind( "click", function() {showclfmenumain() });
}



function showclfthumbs() { 
	clearInterval(mainfeatrun);
	$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	fixclfdisplay();
	$(".hideonthumbs").hide();
	$("#clfthumbsbg").fadeIn(100, function() { 
		if($("#clfthumbs").attr("data-populated") == "1") { 
			$("#clfthumbs").fadeIn(300, function() { 
				$("#clfthumbs").attr("data-showing","1");
				  getclfthumbposition('thumbendpage');				
			});

		} else { 
			$("#clfthumbs").css({"top":$("#clfmenu").height()+"px"}).append('<div style="margin: auto; margin-top: 180px; text-align: center;"><img src="'+tempfolder+'/sy-graphics/loading-page.gif"></div>').show();
			$("#clfthumbsmenu").show();
			setTimeout(function() { 
			$.get(tempfolder+"/sy-inc/show/show-thumbnails.php?page=1&date_id="+$("#vinfo").attr("did")+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
				$("#clfthumbs").html("").append('<div id="displayThumbnailPage">'+data+'</div><div class="clear"></div><div id="thumbendpage"></div>');
				setTimeout(hideLoadingMore,1000);
				$("#clfthumbs").attr("data-populated","1");
				$("#clfthumbs").attr("data-showing","1");
				  getclfthumbposition('thumbendpage');
			});
			},500);
		}
	});
	$(".showclfthumbs").unbind().bind( "click", function() {closeclfthumbs(200) });

}

function closeclfthumbs(closespeed) { 
	$("#clfthumbs").fadeOut(closespeed, function() { 
		unfixclfdisplay();
		window.clearInterval(thumbpopulate);
		$("#clfthumbs").attr("data-showing","0");
		$("#clfthumbsbg").fadeOut(100, function() { 
			$(".hideonthumbs").show();	
			clfmenumenu();
		});
	});
	$(".showclfthumbs").unbind().bind( "click", function() {showclfthumbs() });
}


function getclfthumbposition(d){
	thumbpopulate = setInterval("getclfthumbpositionhere('thumbendpage')",500);
}

function getclfthumbpositionhere(d){
	divEnd = document.getElementById(d).offsetTop;
	var screenWidth = GetScreenWidth();
	var screenHeight = GetScreenHeight();
	var divY = findPosY(document.getElementById(d))
    var pageHeight = document.documentElement.scrollHeight;
    var clientHeight = document.documentElement.clientHeight;
	if(window.pageYOffset) { 
		scrollPos = window.pageYOffset;
	} else {
		scrollPos = document.documentElement.scrollTop;
	}
	if(scrollPos > 1000) { 
		$("#gototop").fadeIn(400);
	} else { 
		$("#gototop").fadeOut(400);
	}
	// $("#log").show().append(scrollPos+' - '+screenHeight+' - '+divEnd+' <br>');

	if((scrollPos + screenHeight) > (divEnd - 400)) { 
		var curPage = $("#vinfo").attr("thumbPageID");
		curPage = parseInt(curPage);
		var date_id = $("#vinfo").attr("did");
		var sub_id = $("#vinfo").attr("sub_id");
		var keyWord = $("#vinfo").attr("keyWord");
		var kid = $("#vinfo").attr("kid");
		var orderBy = $("#vinfo").attr("orderBy");
		var pic_camera_model = $("#vinfo").attr("pic_camera_model");
		var pic_upload_session = $("#vinfo").attr("pic_upload_session");
		var orientation = $("#vinfo").attr("orientation");
		var untagged = $("#vinfo").attr("untagged");
		var acdc = $("#vinfo").attr("acdc");
		var view = $("#vinfo").attr("view");
		var cat_id = $("#vinfo").attr("cat_id");
		var plid = $("#vinfo").attr("plid");
		var nextPage = parseInt(curPage + 1);
		var pic_client = $("#vinfo").attr("pic_client");
		var search_date = $("#vinfo").attr("search_date");
		var search_length = $("#vinfo").attr("search_length");
		var from_time = $("#vinfo").attr("from_time");
		if(document.getElementById("page-"+nextPage)) { 
			if(document.getElementById("page-"+nextPage)){ 
				if(curPage % thumb_limit === 0) { 
					$("#navthumbpages").show();
					window.clearInterval(thumbpopulate);
				} else { 

					showLoadingMore();
					$.get(tempfolder+"/sy-inc/show/show-thumbnails.php?page="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&cat_id="+cat_id+"&pic_client="+pic_client+"&keyWord="+keyWord+"&kid="+kid+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&price_list="+plid+"&mobile="+ismobile+"&search_date="+search_date+"&search_length="+search_length+"&from_time="+from_time, function(data) {
						$("#displayThumbnailPage").append(data);
						setTimeout(hideLoadingMore,1000);
					});
					$("#vinfo").attr("thumbPageID",nextPage);
				}
			}
		}
	}

}



function placeCLFnav() { 
	if($("#clfmenu").css("display")!=="none") { 
		clfmenuheight = $("#clfmenu").height();
	} else { 
		clfmenuheight = 0;
	}

	if(navplacement == "tr" || navplacement == "tl") { 
		$(".mainfeaturenavcontainer").css({"top":$("#headerAndMenu").height() + Math.abs(clfmenuheight)+"px"});
	} else { 
		$(".mainfeaturenavcontainer").css({"top":$("#mainfeature").height()+$("#headerAndMenu").outerHeight(true) - $(".mainfeaturenavcontainer").height() - 8+"px"});
	}
}
function showchecksmall() { 
	if($("body").width() <= 800) { 
		$("#mainfeature").css({ "width":"100%"});
		$(".homefeaturerecent").css({"position":"relative", "width":"100%", "height":"auto", "overflow":"auto", "top":"auto"});
	//	$(".mainfeaturenavcontainer").css({"right":"0"});
	} else { 
		$("#mainfeature").css({ "width":$("#mainfeature").attr("data-width")+"%", "position":"absolute"});
		sizemainfeature();
		$(".homefeaturerecent").css({"top":$("#headerAndMenu").height()+"px"});

	//	$(".mainfeaturenavcontainer").css({"right":100 - $("#mainfeature").attr("data-width")+"%"});
		checkrecentscroll();
	}
}
function checkrecentscroll() { 
	if($("body").width() <= 800) { 

	} else { 
		if($("#shopmenucontainer").css("display")!=="none") { 
			shopmenuheight = $("#shopmenucontainer").height();
		} else { 
			shopmenuheight = 0;
		}
			if($("#clfmenu").css("display")!=="none") { 
				clfmenuheight = $("#clfmenu").height();
			} else { 
				clfmenuheight = 0;
			}

		$("#homefeaturerecent").css({"top":$("#headerAndMenu").height() + Math.abs(clfmenuheight)+"px","height":$(window).height() - Math.abs($("#headerAndMenu").height())  - Math.abs(shopmenuheight)  - Math.abs(clfmenuheight)+"px",  "position":"absolute", "width":100 - $("#mainfeature").attr("data-width")+"%"});
		if($(window).height() - $("#headerAndMenu").height()   - Math.abs(shopmenuheight)   - Math.abs(clfmenuheight) < $(".homefeaturerecent .inner").height()) { 
			$("#homefeaturerecent").css({"height":$(window).height() - Math.abs($("#headerAndMenu").height())  - Math.abs(shopmenuheight)  - Math.abs(clfmenuheight)+"px","overflow-y":"scroll"});
		}
	}
}
function startmainfeature() { 
	mainfeatrun = setInterval(function() { changem('next','','')}, mainfeaturetimer);
}
function stopmainfeature() { 
	clearInterval(mainfeatrun);
}
function changepp() { 
	if($("#mainfeaturepp").hasClass("icon-pause")) { 
		stopmainfeature();
		$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
	} else { 
		slide_speed = slide_show_slide_speed;
		change_effect = slide_show_change_effect;
		changem('next','','');
		startmainfeature();
		$("#mainfeaturepp").removeClass("icon-play").addClass("icon-pause");
	}
}



function addclfswipe(id) { 
org_change_effect = change_effect;
	$("#"+id).swipe( {
		swipeLeft: function() {
			change_effect = 'slide';
			slide_speed = 300;
			changem('next','','');
			clearInterval(mainfeatrun);
			$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
			change_effect = org_change_effect;

		},
		swipeRight: function() {
			change_effect = 'slide';
			slide_speed = 300;
			changem('prev','','');
			clearInterval(mainfeatrun);
			$("#mainfeaturepp").removeClass("icon-pause").addClass("icon-play");
			change_effect = org_change_effect;
	   },
		allowPageScroll: 'vertical',
		excludedElements: "button, input, select, textarea, .noSwipe",
		  tap: function(event, target) {
			window.open($(target).closest('.carusel-cnt').find('carusel-cnt-link').attr('href'), '_self');
		  },
	//	  threshold:0


	});
}

function clickclfthumb(id) { 
	$('html').animate({scrollTop:0}, 0); 
   $('body').animate({scrollTop:0}, 0); 

	changem('',id,'click');
	closeclfthumbs(0);
}

function changem(tmi,id,click) { 
	if(click == "click") { 
		change_effect = 'fade';
		slide_speed = 100;
	}
	if(tmi == "next") { 
		thisslide = Math.abs($("#mainfeature").attr("data-cur")) + 1;
		dira = 'left';
		dirb = 'right';
		if(thisslide > totalmslides) { 
			thisslide = 1;
		}
	}
	if(tmi == "prev") { 
		thisslide = Math.abs($("#mainfeature").attr("data-cur")) - 1;
		dira = 'right';
		dirb = 'left';
		if(thisslide <= 0) { 
			thisslide = totalmslides;
		}
	}
	if(id > 0) { 
		thisslide = id;
	}


	if($("#main-"+thisslide).length == 0) {
		$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"="+thisslide+"&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
			$("#mainfeature").append(data);
			sizecatphoto('mainfeature');
			sizemainfeature();
			change_effect = "fade";
			slide_speed = 100;
			changeslide();
			if(norightclick == '1') { 
				disablerightclick();
			}

		});

	} else { 

		changeslide();

	}
}


function changeslide() { 
	$("#main-"+$("#mainfeature").attr("data-cur")).imagesLoaded( function() {
		$("#main-"+$("#mainfeature").attr("data-cur")).find("img").fadeIn(400);
	});

	if(thisslide !== $("#mainfeature").attr("data-cur")) { 
		var refW = $("#mainfeature").width();
		if(change_effect == "fade") { 
			$("#main-"+$("#mainfeature").attr("data-cur")).fadeOut(slide_speed);
			$("#main-"+thisslide).fadeIn(slide_speed, function() { 
				$(this).find(".svg-image-blur").fadeIn(400);	
			});
		//	if ($("#mainfeature").attr("data-cur") == "1") { $("#mainphotobg-1").fadeOut(slide_speed); } 
		} else { 
			$("#main-"+$("#mainfeature").attr("data-cur")).hide('slide', {direction: dira, easing: 'easeOutExpo'}, slide_speed);
		//	if ($("#mainfeature").attr("data-cur") == "1") { $("#mainphotobg-1").hide('slide', {direction: dira, easing: 'easeOutExpo'}, slide_speed); } 

			$("#main-"+thisslide).show('slide', {direction: dirb, easing: 'easeOutExpo'}, slide_speed, function() { 
			});
		}



		addclfswipe("main-"+thisslide);
		$("#mainfeature").attr("data-cur",thisslide)
		$("#mscount").html("").append(thisslide);
		nextslide = thisslide + 1;
		if(nextslide > totalmslides) { 
			nextslide = 1;
		}
		prevslide = thisslide - 1;
		if(prevslide <= 0) { 
			prevslide = totalmslides;
		}
		setTimeout(function() { 
			if($("#main-"+nextslide).length == 0) {
				$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"="+nextslide+"&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
					$("#mainfeature").append(data);
					sizecatphoto('mainfeature');
					sizemainfeature();
					if(norightclick == '1') { 
						disablerightclick();
					}
				});
			}
		}, slide_speed);

		setTimeout(function() { 
			if($("#main-"+prevslide).length == 0) {
				$.get(tempfolder+"/sy-inc/show/show-actions.php?"+gettingfeature+"="+prevslide+"&featid="+featid+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
					$("#mainfeature").append(data);
					sizecatphoto('mainfeature');
					sizemainfeature();
					if(norightclick == '1') { 
						disablerightclick();
					}
				});
			}
		}, slide_speed);
	  $(".homephotos").hover(function(){
			$(this).stop().animate({"opacity":hoveropacity}, 200);
		}, function(){
			$(this).stop().animate({"opacity":initialopacity}, 200);
		});

	}
}
function sizecatphoto(classname) { 
	$("."+classname).each(function(){
		if(classname == "catphoto") { 
			var ratio = catphotoratio;
			var sizecontainer = "catphotocontainerinner";
		}
		if(classname == "mainfeature") { 
			var ratio = mainphotoratio;
			var sizecontainer = "mainfeature";
			if(main_full_screen == 1) { 
			if(showminimenu == "1") { 
			if($("#shopmenucontainer").css("display")!=="none") { 
				shopmenuheight = $("#shopmenucontainer").height();
			} else { 
				shopmenuheight = 0;
			}
			if($("#clfmenu").css("display")!=="none") { 
				clfmenuheight = $("#clfmenu").height();
			} else { 
				clfmenuheight = 0;
			}

				wh = $(window).height() - Math.abs($("#headerAndMenu").height()) - Math.abs(shopmenuheight) - Math.abs(clfmenuheight);
			} else { 
				wh = $(window).height();
			}

				var ratio = wh / $("#mainfeature").width();
			}
		}
		if(classname == "featsidemenuitem") { 
			ratio = side_menu_photo_ratio;
		}

		var refW = $(this).width();
		refH = refW * ratio;
		if(classname == "mainphoto") { 
			// $("#mainfeature").css({"height":refH+"px","min-height":refH+"px"});
		}

		$(this).css({"height":refH+"px","min-height":refH+"px"});
		var refRatio = refW/refH;
		var imgH = Math.abs($(this).find(".homephotos").attr("hh"));
		var imgW = Math.abs($(this).find(".homephotos").attr("ww"));


		if(imgH > refH) { 
			mt = (imgH - refH) / 2;
		} else { 
			mt = 0;
		}
		pid = $(this).find("img").attr("id");

		if ( (imgW/imgH) < refRatio ) { 
			newH = imgH * (refW / imgW);
			mt = (newH - refH) / 2;
			$(this).find(".homephotos").css({"width":refW+"px","height":"auto", "margin-top":"-"+mt+"px"});		
		} else {
			newW = imgW * (refH / imgH);
			ml = (newW - refW) / 2;
			$(this).find(".homephotos").css({"height":refH+"px","width":"auto", "margin-left":"-"+ml+"px"});
		}
	})

}




function sizemainfeature() { 

	if($("#shopmenucontainer").css("display")!=="none") { 
		shopmenuheight = $("#shopmenucontainer").height();
	} else { 
		shopmenuheight = 0;
	}
	if($("#clfmenu").css("display")!=="none") { 
		clfmenuheight = $("#clfmenu").height();
	} else { 
		clfmenuheight = 0;
	}
	$("#maincol").css({"height":$(window).height() - Math.abs($("#headerAndMenu").height()) - Math.abs(shopmenuheight)+"px"});
	$(".mainphoto").each(function(){
		var ratio = mainphotoratio;
		if(main_full_screen == 1) { 
			var ratio = $("#mainfeature").height() / $("#mainfeature").width();
		}
		var sizecontainer = "maincontainerinner";
		var refW = $("#mainfeature").width();
		refH = refW * ratio;
		// $("#mainfeature").css({"height": refH+"px"});
		$(this).css({"height":refH+"px","min-height":refH+"px"});
		var refRatio = refW/refH;
		var imgH = Math.abs($(this).find(".homephotos").attr("hh"));
		var imgW = Math.abs($(this).find(".homephotos").attr("ww"));
		if(imgH > refH) { 
			mt = (imgH - refH) / 2;
		} else { 
			mt = 0;
		}
		pid = $(this).find(".homephotos").attr("id");

		if ( (imgW/imgH) < refRatio ) { 
			newH = imgH * (refW / imgW);
			mt = (newH - refH) / 2;
			$(this).find(".homephotos").css({"width":refW+"px","height":"auto", "margin-top":"-"+mt+"px", "margin-left":"0px"});		
		} else {
			newW = imgW * (refH / imgH);
			ml = (newW - refW) / 2;

			$(this).find(".homephotos").css({"height":refH+"px","width":"auto", "margin-left":"-"+ml+"px", "margin-top":"0px"});
		}
			var contain = false;
			if(contain_portrait == "1" && imgH >= imgW) { 
				contain = true;
			}
			if(contain_landscape== "1" && imgW > imgH) { 
				contain = true;
			}



			if(contain == true) { 
				newH = imgH * (refW / imgW);
				newW = imgW * (refH / imgH);
				/*
				if($(this).find(".homephotos").attr("blurbg") == "1") { 
					$(this).find(".homephotos").parent().prepend('<div class="mainphotobg" style="position: absolute; left: 0; display: block; background: url(\''+$(this).find(".homephotos").attr("src")+'\') no-repeat center center fixed; background-size: cover;"></div>');
				}
			*/
				if(newW > refW) { 
					newW = refW;
					$(this).find(".homephotos").css({"width":newW+"px", "height":"auto","margin-left":"0px", "margin-top":(refH - newH) / 2+"px",  "box-shadow":"0px 0px 24px rgba(0,0,0,.6)"});
				} else { 
					$(this).find(".homephotos").css({"width":"auto", "height":refH+"px","margin-left":(refW - newW) / 2 +"px", "margin-top":"0px",  "box-shadow":"0px 0px 24px rgba(0,0,0,.6)"});
				}
				$(this).find(".homephotos").attr("blurbg","1");
			}
			
			//  $("#log").show().append(newW +" x "+ newH+" | ref: "+refW+" ");


		if(titleplacement == "br") { 
			if(logoplacement == "br") {
				$(".mainphotoheadlinetext").css({"bottom":$("#mainfeaturelogo").height() + 8+"px","right":"0px","text-align":"right"});
			} else { 
				$(".mainphotoheadlinetext").css({"bottom":"8px","right":"0px","text-align":"right"});
			}
		}
		if(titleplacement == "tl") { 
			if(logoplacement == "tl") {
				$(".mainphotoheadlinetext").css({"top":$("#mainfeaturelogo").height() + 8+"px","left":"0px"});
			} else { 
				$(".mainphotoheadlinetext").css({"top":"8px","left":"0px"});
			}
		}
		if(titleplacement == "bl") { 
			if(logoplacement == "bl") {
				$(".mainphotoheadlinetext").css({"bottom":$("#mainfeaturelogo").height() + 8+"px","left":"0px"});
			} else { 
				$(".mainphotoheadlinetext").css({"bottom":"8px","left":"0px"});
			}
		}
		if(titleplacement == "tr") { 
			if(logoplacement == "tr") {
				$(".mainphotoheadlinetext").css({"top":$("#mainfeaturelogo").height() + 8+"px","right":"0px","text-align":"right"});
			} else { 
				$(".mainphotoheadlinetext").css({"top":"8px","right":"0px","text-align":"right"});
			}
		}



	})

}

function findphotostoggle() {
	$("#homefindphotos").toggle(400);
}



function sideitemsposition(){
	setInterval("getsideitemsposition('siderecentpagesmarker')",500);
}
function getsideitemsposition(d){
	if(document.getElementById(d)) { 
		divEnd = document.getElementById(d).offsetTop;
		var screenWidth = GetScreenWidth();
		var screenHeight = GetScreenHeight();
		var divY = findPosY(document.getElementById(d))
		var pageHeight = document.documentElement.scrollHeight;
		var clientHeight = document.documentElement.clientHeight;
		if(window.pageYOffset) { 
			scrollPos = window.pageYOffset;
		} else {
			scrollPos = document.documentElement.scrollTop;
		}
		if(scrollPos > 1000) { 
			$("#gototop").fadeIn(400);
		} else { 
			$("#gototop").fadeOut(400);
		}
		hrfh = $(".homefeaturerecent").scrollTop() + $(".homefeaturerecent").height();
		if($("body").width() > 800) { 
			cks = hrfh;
		} else { 
			cks = scrollPos;
		}
		if((divEnd) < (cks + 300)) { 

			var curPage = $("#siderecentpagesmarker").attr("curpage");
			curPage = parseInt(curPage);

			var nextPage = parseInt(curPage + 1);
			var total_topics = $("#siderecentpagesmarker").attr("total");
			var pages = $("#siderecentpagesmarker").attr("pages");
			var category = $("#siderecentpagesmarker").attr("category");
		//	$("#log").show().append(scrollPos+" + "+screenHeight+" > "+divEnd+" pages: "+pages+" nextpage "+nextPage+" | ");

			if(nextPage<=pages) { 
				$("#loadingmoreitems").fadeIn(200);
				$.get(tempfolder+"/sy-inc/show/show-actions.php?action=getpages&sp="+nextPage+"&category="+category+"&show="+$("#siderecentpagesmarker").attr("show")+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
					$("#sidefeaturepages").append(data);
					$("#loadingmoreitems").fadeOut(200);
					checkrecentscroll();
					sizecatphoto('featsidemenuitem');

				});
				$("#siderecentpagesmarker").attr("curpage",nextPage);
			}
		}
	}
}