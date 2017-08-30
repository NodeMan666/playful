function galleryheaderfixed() { 
	$("#galleryheader").removeClass("galleryheader").addClass("galleryheaderfixed").css({"top":-$("#galleryheader").height()+"px"});
	$("body").css({"padding-top":$("#galleryheader").height()+"px"});
	$("#photoproductsnexttophoto").css({"margin-top":-$("#galleryheader").height()+"px"});
	$("#galleryheader").stop().animate({'top' : '0px'}, 200);
}

$(window).resize(function() {
	if($("body").width() <= 800) { 
		$("#galleryheader").removeClass("galleryheaderfixed").addClass("galleryheader");
		$("body").css({"padding-top":"0px"});

	}
});
function galleryloginphoto() { 
	$("#photo-header").stop(true,true).animate({"height":"150px"},1000, "easeOutQuart");
}

$(document).ready(function(){
	// $("#galleryheader .inner .menuouter .menu ul li").css({"line-height":$("#galleryheader").height()+"px"});
	// $("#galleryheader .inner .menuouter .menu").show();
	// $(".photo-header").css({"height":"700px"});
	if($("#galleryheader").attr("data-wall-designer") !== "1") { 
		$(".photo-header").css({"height":$(window).height()+"px"});
		$(window).scroll(function(){
			if($("body").width() > 800) { 
				clearTimeout($.data(this, 'scrollTimer'));
				$.data(this, 'scrollTimer', setTimeout(function() {
					if($("#galleryheader").height() < $(window).scrollTop()) { 
						if($("#galleryheader").hasClass("galleryheader")) { 
							galleryheaderfixed();
						}
					}
				}, 250));
			}
		});
	}

	$('#bigphotoholder').imagesLoaded(function() {
		$("#bigphotoloading").hide();
		$("#bigphoto1").fadeIn(1000, function() { 
			nt = ($(window).height() / 2) - ($("#bigphoto1").parent().find(".title").height());

			$("#bigphoto1").parent().find(".title").css({"top":nt+"px"}).animate({"opacity":"1", "top":"-=40"},600, "easeOutBack");
			setTimeout(function() { 
				$("#bigphoto1").parent().find(".title p").fadeIn(1000);
			}, 600);
			setTimeout(function() { 
				$("#bigphoto1").parent().find(".title .the-icons").fadeIn(1000);
			}, 1600);

			if($("#photo-header").attr("data-gallery-login") == "1") { 
				setTimeout(function() { 
					galleryloginphoto();
				}, 600);
			}
		});
	});

	$( ".menu ul li" )
		.mouseenter(function() {
		$(this).find(".title").css({"left":-($(this).find(".title").width()+($(this).find(".the-icons").width() / 2))+"px", "top":$(this).height()+"px"}).show().stop( true, true ).animate({'top' : '+=12px', "opacity":"1"}, 100);;
		})
		.mouseleave(function() {
		$(this).find(".title").css({"opacity":"0"}).hide();
	});
});

function scrolltophotos() { 
   $('html').animate({scrollTop:$("#bigphoto1").height()}, 'slow'); 
    $('body').animate({scrollTop:$("#bigphoto1").height()}, 'slow'); 
}
function changeslide(next,prev) { 
	$("#bigphoto"+prev).fadeOut(2000);
	$("#bigphoto"+next).fadeIn(2000);
}

$(document).ready(function(){
	var numItems = $('.bigphotopic').length
	if(numItems > 1) { 
		$("#photo-header").attr("slides-num","1");
		setInterval(function() { changebigphoto()}, 5000);
	}
});

function changebigphoto() { 
	var numItems = $('.bigphotopic').length
	var prev = Math.abs($("#photo-header").attr("slides-num"));

	if(prev < numItems) { 
		next = prev + 1;
	} else { 
		next = 1;
	}
	$("#photo-header").attr("slides-num",next);
	changeslide(next,prev);
}

function showgallerymobilemenu() { 
	$("#gallerymobilemenu .menu").slideToggle(200);

}
function showgallerysubs() { 
	$("#gallerysharebg").attr("data-window","gallerysubs");
	$("#gallerysharebg").fadeIn(100, function() { 
		$("#accloading").hide();
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		$("#gallerysubs").css({"top":to+"px"}).fadeIn(200);
	});
}

function showgalleryshare() { 
	$("#gallerysharebg").attr("data-window","galleryshare");
	$("#gallerysharebg").fadeIn(100, function() { 
		$("#accloading").hide();
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		$("#galleryshare").css({"top":to+"px"}).fadeIn(200);
	});
}
