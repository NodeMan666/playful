function deleteroomphoto(id,pid) { 
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=deleteroomphoto&room_id="+id+"&pid="+pid, function(data) {
		$("#croom-"+id).slideUp(200);
	});
}

function setmeasurement() { 
	w = Math.abs($("#w").val());
	h = Math.abs($("#h").val());
	if(w <=0 && h<=0) { 

		$("#roommeasureselecterror").slideDown(200);
		$("#roommeasuresizeerror").slideUp(200);
		$("#roommeasuretitle").slideUp(200);

	} else if($("#rm_h").val() <= 0 && $("#rm_w").val() <= 0) { 
		$("#roommeasureselecterror").slideUp(200);
		$("#roommeasuresizeerror").slideDown(200);
		$("#roommeasuretitle").slideUp(200);

	} else { 
		wroompixels = $("#roombackground").width();
		hroompixels = $("#roombackground").height();
		if($("#rm_w").val() > 0) { 
			p = w / Math.abs($("#rm_w").val());
			total = wroompixels / p;
			// $("#log").show().append(total+" : "+(total / 12)+"<br>");
			$("#room_width").val(total);
		}
		if($("#rm_h").val() > 0) { 
			p = h / Math.abs($("#rm_h").val());
			total = wroompixels / p;
		// 	$("#log").show().append(total+" : "+(total / 12)+"<br>");
			$("#room_width").val(total);
		}
		$("#roomcontain").attr("data-room-width",total);
		disableselection();
		sizeroomphoto();
		photourl = $("#roomcontain").attr("data-room-background-photo");
		$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=saveroommeasurement&photourl="+photourl+"&total="+total, function(data) {

		});
		$(".roomphotocontainer").show();
		$("#roommeasureselecterror").slideUp(200);
		$("#roommeasuresizeerror").slideUp(200);
		$("#roommeasuretitle").slideDown(200);
		$(".roomphotocontainer").show();

	}
}

function showCoords(c) {
	$("#w").val(c.w);
	$("#h").val(c.h);
};

function showRoomMeasure() { 
	$("#roommeasurements").slideDown(200);
	$("#roommenu").slideUp(200);

}
function hideRoomMeasure() { 
	$("#roommeasurements").slideUp(200);
	$("#roommenu").slideDown(200);
	$("#roommeasureselecterror").slideUp(200);
	$("#roommeasuresizeerror").slideUp(200);
	$("#roommeasuretitle").slideDown(200);
}

function enableselection() { 
	jcrop_api.enable();
}
function disableselection() { 
	jcrop_api.release();
	jcrop_api.disable();
	html = $("#roombackground").html();
	$("#roomcontain").append('<div id="roombackground">'+html+'</div>');
	$(".jcrop-holder").remove();
	hideRoomMeasure();
	backgrounddeselectphoto();
	$(".roomphotocontainer").show();
	$("#roomcontain").attr("data-measuring",0);

}

function addmeasureing() { 
	$(".roomphotocontainer").hide();
	$("#roomcontain").attr("data-measuring",1);
	$("#roommeasurements").slideDown(200);
	$("#roommenu").slideUp(200);
	$('#roombackground').Jcrop({
		onSelect:    showRoomMeasure,
		onChange:    showCoords,
		bgColor:     'black',
		bgOpacity:   .2
	}, function () {
		jcrop_api = this;
	});
}

$(document).ready(function(){
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	$("#rm_w").focus(function() { 
		$("#rm_h").val("")
	});
	$("#rm_h").focus(function() { 
		$("#rm_w").val("")
	});

});
$(window).bind('resize', function(e){
    window.resizeEvt;
    $(window).resize(function(){
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function(){
			if($("#roomcontain").attr("data-measuring") == "1") { 
				disableselection();
				addmeasureing();
			}
		}, 250);
    });
});


/* ################ End room measurement functions ################ */

function roomaddtocart(listid) { 
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}
	$("#accloading").hide();
	$("#roommenu").slideUp(200);
	$("#roomuploadbackground").attr("data-window","roomaddtocart");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#roomaddtocart").css({"top":"0px"})
	$("#roomaddtocart").show();
	otop = $("#roomaddtocart").offset().top;
	$("#roomaddtocart").hide();
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		
		// $("#log").show().append(totalphotoitems()+" = "+totalphotofiles()+"<br>");
		$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=addtocartoptions&listid="+listid+"&subtotal="+getwallprice()+"&totalitems="+totalphotoitems()+"&totalphotofiles="+totalphotofiles()+"&files="+photofiles(), function(data) {
			 $("#roomaddtocartcontent").html(data);
			$("#roomaddtocart").css({"top":to+"px"}).fadeIn(200, function() {  });
			$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
			$("#accloading").hide();
		});
	});
}
function roomaddtocartsuccess() { 
	$("#roomaddtocart").slideUp(200, function() { 
		$("#accloading").hide();
		$("#roomuploadbackground").attr("data-window","roomaddtocartsuccess");
		$("#roomuploadbackground").prop('onclick',null).off('click');
		$("#roomaddtocartsuccess").css({"top":"0px"})
		$("#roomaddtocartsuccess").show();
		otop = $("#roomaddtocartsuccess").offset().top;
		$("#roomaddtocartsuccess").hide();
		$("#roomuploadbackground").fadeIn(100, function() { 
			to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;


			$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=getcarttotal", function(data) {
				 $("#roomaddtocartsuccesscontent").html(data);
				$("#roomaddtocartsuccess").css({"top":to+"px"}).fadeIn(200, function() {  });
				$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
				$("#accloading").hide();
			});
		});

	});

}


function opensizemenu() { 
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}
	$("#accloading").hide();
	$("#roommenu").slideUp(200);
	$("#roomuploadbackground").attr("data-window","sizemenu");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#sizemenu").css({"top":"0px"})
	$("#sizemenu").show();
	otop = $("#sizemenu").offset().top;
	$("#sizemenu").hide();
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		$("#sizemenu").css({"top":to+"px"}).fadeIn(200);
		$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
		$("#accloading").hide();
	});
	closehelpbubble();
}

function openroommenu() { 
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}
	$("#accloading").hide();
	$("#roommenu").slideUp(200);
	$("#roomuploadbackground").attr("data-window","roomchoicemenu");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#roomchoicemenu").css({"top":"0px"})
	$("#roomchoicemenu").show();
	otop = $("#roomchoicemenu").offset().top;
	$("#roomchoicemenu").hide();
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		$("#roomchoicemenu").css({"top":to+"px"}).fadeIn(200, function() { 
		});
		$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
		$("#accloading").hide();
	});
	closehelpbubble();
}

function closesstuffwindow() { 
	$("#"+$("#roomuploadbackground").attr("data-window")).fadeOut(50, function() { 
		$("#linkcopied").hide();
		$("#roomuploadbackground").fadeOut(50);
		$("#roommenu").slideDown(200);
	});
}

function openframemenu() { 
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}

	$("#accloading").hide();
	$("#roommenu").slideUp(200);
	$("#roomuploadbackground").attr("data-window","framechoicemenu");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#framechoicemenu").css({"top":"0px"})
	$("#framechoicemenu").show();
	otop = $("#framechoicemenu").offset().top;
	$("#framechoicemenu").hide();
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		$("#framechoicemenu").css({"top":to+"px"}).fadeIn(200);
		$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
		$("#accloading").hide();
	});
	closehelpbubble();
}


function opencollagemenu() { 
	$("#roomphotoloading").show();
	$("#roommenu").slideUp(200);
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}

	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=getwallcollections", function(data) {
		$("#accloading").hide();
		$("#roomuploadbackground").attr("data-window","collagemenu");
		$("#roomuploadbackground").prop('onclick',null).off('click');
		$("#collagemenu").css({"top":"0px"})
		$("#collagemenu").show();
		otop = $("#collagemenu").offset().top;
		$("#collagemenu").hide();
		$("#roomuploadbackground").fadeIn(100, function() { 
			to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
			$("#collagemenucontent").html(data);
			$("#collagemenu").css({"top":to+"px"}).fadeIn(200);
			$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
			$("#accloading").hide();
			colloagepreview();
			setTimeout(function(){
				colloagepreview();
			},20);

		});
		$("#roomphotoloading").hide();
	});
	closehelpbubble();
}

function selectframestyle(file,width,height,framewidth,matwidth,corner,frameid,styleid,colorid,price) { 
	$("#roomphotoloading").show();
	lid = $("#roomcontain").attr("data-last-selected-photo");
	$(".frameselections").removeClass("selectedprintsize");
	$(".framecolorselections").removeClass("selectedframecolor");
	$(".matcolorselections").removeClass("selectedmatcolor");
	$("#roomphotocontainer-"+lid).attr("data-color-id",0);
	$("#roomphotocontainer-"+lid).attr("data-frame-mat-size",0);
	$("#roomphotocontainer-"+lid).attr("data-mat-color","");
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=getframestyleoptions&styleid="+styleid, function(data) {
		 $("#frameoptionsmenu").show().html(data);
		changeframe(file,width,height,framewidth,matwidth,corner,frameid,styleid,colorid,price,'1');
	 $("#roomphotoloading").hide();
	});
	closehelpbubble();
}

function changeframe(file,width,height,framewidth,matwidth,corner,frameid,styleid,colorid,price,scroll,selectingsize) { 
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	showwidth = width;
	showheight = height;
	newwidth = parseFloat(width) + (framewidth * 2);
	newheight = parseFloat(height) + (framewidth * 2);
	corners = corner.split('-');
	lid = $("#roomcontain").attr("data-last-selected-photo");
	if(colorid <= 0) { 
		colorid = $("#roomphotocontainer-"+lid).attr("data-color-id");
	}
	matprintwidth = $("#frame-"+styleid+"-"+frameid).attr("data-mat-print-width");
	matprintheight = $("#frame-"+styleid+"-"+frameid).attr("data-mat-print-height");
	framecanmat = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));

	$("#roomphotocontainer-"+lid).attr("data-default","0")

	if(framecanmat <= 0) { 
		$("#mat-options-"+styleid).hide();
		$(".matcolorselections").removeClass("selectedmatcolor");
		$("#roomphotocontainer-"+lid).attr("data-frame-mat-size","");
	} else { 
		$("#mat-options-"+styleid).show();
	}

	thisprice = $("#frame-"+styleid+"-"+frameid).attr("data-frame-price");
	if($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-frame-mat-size") > 0) { 
		matwidth = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
		thisprice = $("#frame-"+styleid+"-"+frameid).attr("data-frame-mat-price");
	}
	$(".frameselections").removeClass("selectedprintsize");
	$("#frame-"+styleid+"-"+frameid).addClass("selectedprintsize");
	$(".framecolorselections").removeClass("selectedframecolor");
	$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
	if($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).width() >$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).height()) { 
		if(parseFloat(width) < parseFloat(height)) { 
			newwidth = parseFloat(height) + (framewidth * 2);
			newheight = parseFloat(width) + (framewidth * 2);
		}
	}
	if(parseFloat($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-color-id")) <= 0) { 

		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).addClass("borderframe").css({"border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-webkit-border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-o-border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round","border-width":framewidth* pixels+"px"}).removeClass("canvasshadow").addClass("frameshadow").attr("data-color-id",colorid);
		// $("#log").show().append(file+" - "+$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).css("border-image-source")+"<br>");
	}
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"padding":matwidth * pixels+"px"});
	addmatwidth = matwidth * 2;
	width = newwidth - (framewidth * 2) - addmatwidth; 
	height = newheight - (framewidth * 2) - addmatwidth;
	$("#roomphotocontainer-"+lid).attr("data-width",width).attr("data-height",height).attr("data-style-id",styleid).attr("data-price",thisprice).attr("data-canvas-id","");
	$("#roomphotocontainer-"+lid).attr("data-show-width",showwidth).attr("data-show-height",showheight).attr("data-frame-id",frameid).attr("data-frame-mat-size",matwidth).attr("data-frame-width",framewidth).attr("data-mat-print-width",matprintwidth).attr("data-mat-print-height",matprintheight).attr("data-frame-corners",corner);

	$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).addClass("photoshadow");
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").addClass("matshadow"); 
	if(selectingsize !== "1") { 
		$("#roomphotocontainer-"+lid).attr("data-frame-file",file);
	}
	if(scroll == "1") { 
		off = $("#wallprice").offset();
		scrollto = off.top;
		$('html').animate({scrollTop:scrollto},200); 
		$('body').animate({scrollTop:scrollto},200); 
	}
	closesstuffwindow();
	sizeroomphoto();
	showphotoinfo();
}

function changeframecolor(colorid,file,corner) { 
	corners = corner.split('-');
	styleid = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-style-id")
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).css({"border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-webkit-border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-o-border-image":"url('"+file+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round"});
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-color-id",colorid).attr("data-frame-file",file).attr("data-frame-corners",corner);
	$(".framecolorselections").removeClass("selectedframecolor");
	$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
}
 
function changemat(color,id) { 
	lid = $("#roomcontain").attr("data-last-selected-photo");
	styleid = $("#roomphotocontainer-"+lid).attr("data-style-id");
	frameid = $("#roomphotocontainer-"+lid).attr("data-frame-id");
	matwidth = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	$("#roomphotocontainer-"+lid).find(".roomphotomatte").css({"padding":matwidth * pixels+"px","background":"#"+color}).addClass("matshadow");
	addmatwidth = matwidth * 2;
	width = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-width")) - addmatwidth;
	height = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-height")) - addmatwidth;
	if($("#roomphotocontainer-"+lid).width() > $("#roomphotocontainer-"+lid).height()) { 
		if(height > width) { 
			tw = width;
			width = height;
			height = tw;
		}
	}
	$("#roomphotocontainer-"+lid).attr("data-width",width).attr("data-height",height).attr("data-frame-mat-size",matwidth).attr("data-mat-color",color).attr("data-mat-color-id",id).attr("data-price",$("#frame-"+styleid+"-"+frameid).attr("data-frame-mat-price"));
	sizeroomphoto();
	showphotoinfo();
	$(".matcolorselections").removeClass("selectedmatcolor");
	$("#matcolor-"+styleid+"-"+color).addClass("selectedmatcolor");
}

function removemat() { 
	lid = $("#roomcontain").attr("data-last-selected-photo");
	styleid = $("#roomphotocontainer-"+lid).attr("data-style-id");
	frameid = $("#roomphotocontainer-"+lid).attr("data-frame-id");
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	$("#roomphotocontainer-"+lid).find(".roomphotomatte").css({"padding":"0px"});
	$("#roomphotocontainer-"+lid).attr("data-frame-mat-size",0);
	framewidth = parseFloat($("#roomphotocontainer-"+lid).attr("data-frame-width"));
	width = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-width"));
	height = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-height"));
	width = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-width"));
	height = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-frame-height"));
	if($("#roomphotocontainer-"+lid).width() > $("#roomphotocontainer-"+lid).height()) { 
		if(height > width) { 
			tw = width;
			width = height;
			height = tw;
		}
	}
	$("#roomphotocontainer-"+lid).attr("data-width",width).attr("data-height",height).attr("data-mat-color","").attr("data-mat-color-id","").attr("data-price",$("#frame-"+styleid+"-"+frameid).attr("data-frame-price"));
	$(".matcolorselections").removeClass("selectedmatcolor");
	showphotoinfo();
	sizeroomphoto();
}

function sizeroomphoto() { 
	// addframe();
	// addmatte();
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	// Here we are going to go through each photo and set the size 
	$("#roomcontain .roomphotocontainer").each(function(i){
		photoheight = Math.abs($(this).attr("data-height")) * pixels;
		photowidth = Math.abs($(this).attr("data-width")) * pixels;
		$(this).find(".roomphoto").css({"width":photowidth+"px","height":photoheight+"px"});
		frame = (Math.abs($(this).css('border-left-width').replace("px", "")));
		matte = (Math.abs($(this).find(".roomphotomatte").css('padding-left').replace("px", "")));
		photoleft = (Math.abs($("#roomcontain").attr("data-center")) * $("#roombackground").width()) - (photowidth  / 2) - frame - matte;
		phototop = (Math.abs($("#roomcontain").attr("data-base")) * $("#roombackground").height()) - photoheight - (frame * 2) - (matte * 2);
		photoleft = photoleft + (parseFloat($(this).attr("data-from-center")) * pixels);
		phototop = phototop + (parseFloat($(this).attr("data-from-base")) * pixels);
		// $("#log").show().append(photoleft+" x "+phototop+" frame: "+frame+" mat: "+matte+" <br>");
		if($(this).attr("data-frame-id") > 0) { 
			framewidth = parseFloat($(this).attr("data-frame-width"));
			$(this).addClass("borderframe").css({"border-width":framewidth* pixels+"px"});
			if($(this).attr("data-frame-mat-size") > 0) { 
				matwidth = parseFloat($(this).attr("data-frame-mat-size"));
				$(this).find(".roomphotomatte").css({"padding":matwidth * pixels+"px"});
			}
		}
		if($("#roomcontain").attr("data-center-photo") == "1") { 
			$(this).css({"left":photoleft+"px","top":phototop+"px"});
		}
	});

	photoleft = (Math.abs($("#roomcontain").attr("data-center")) * $("#roombackground").width()) - (photowidth / 2) - frame - matte;
	phototop = (Math.abs($("#roomcontain").attr("data-base")) * $("#roombackground").height()) - photoheight - (frame * 2) - (matte * 2);
	
	if($("#roomcontain").attr("data-center-photo") == "1") { 
		// $("#roomphotocontainer").css({"left":photoleft+"px","top":phototop+"px"});
	}
	roomphotopercentage = $("#roomcontain").width() / Math.abs($("#roomcontain").attr("data-room-photo-width"));
	$("#roomcontain").css({"height":Math.abs($("#roomcontain").attr("data-room-photo-height")) * roomphotopercentage+"px"});
	doubleclickopenphotos();
	showwallprice();
	positionallroomphotos();
	totalprints = 0;
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			totalprints = totalprints + 1;
			if(totalprints <= 1) { 
				$("#removelink").hide();
			} else { 
				$("#removelink").show();
			}
		}
	});
}

function removeframe() { 
	$("#roomphoto").css({"background-image":"url("+$("#roomcontain").attr("data-photo")+")"});
	$("#roomphotoinsert").hide();
	$("#framechoicemenu").slideUp(100);
}

function showphotoupload() { 
	$("#roomchoicemenu").hide();
	$("#accloading").hide();

	$("#roomuploadbackground").attr("data-window","roomphotoupload");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#roomuploadbackground").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		$("#roomphotoupload").css({"top":to+"px"}).fadeIn(200);
		$("#roomuploadbackground").bind('click', function() {closeshowphotoupload() });
		$("#accloading").hide();

	});
}

function closeshowphotoupload() { 
	$("#"+$("#roomuploadbackground").attr("data-window")).fadeOut(200, function() { 
		$("#roomuploadbackground").fadeOut(100);
		$("#roommenu").slideDown(200);

	});

}

 function convertImage(dataurl) { 
	// alert(dataurl);
	$.ajax({
	  type: "POST",
	  url: tempfolder+"/sy-inc/room-view/dataurl-image.php",
	  data: { 
		"img": dataurl,
		"id": $("#id").val(),
		"pid":$("#pid").val()
	  }
	}).done(function(o) {
	// 	alert(o);
	  console.log('saved'); 
		data = o.split('|');
	  $("#roombackgroundphoto").attr("src",tempfolder+data[0]);
		$("#roombackgroundphoto").imagesLoaded(function() {
		$("#roomcontain").attr("data-center-photo","1").attr("data-room-background-photo",data[0]);
			$("#roomcontain").attr("data-room-photo-width",data[1]).attr("data-room-photo-height",data[2]).attr("data-room-width",112).attr("data-center",.500).attr("data-base",.500);
			$("#roombackgroundphoto").css({"max-width":data[1]+"px"});
			$("#roombackgroundphoto").fadeIn(200, function() { 
				sizeroomphoto();
				addmeasureing();
				$("#roommeasurelink").show();
				 $("#roomphotoloading").hide();
				 $("#roomphotocontainer").show();
			});
		});
	});
}


function selectphotosize(w,h,price,id) { 
	if($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-frame-id") > 0) { 
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).removeClass("borderframe").removeClass("frameshadow").addClass("canvasshadow").css({"border-image":"none","border-width":"0px"}).attr("data-frame-id",0).attr("data-frame-mat-size","0").attr("data-style-id","0");
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"padding":"0px"});
	}

	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-default","0")

	newwidth = w;
	newheight = h;
	if($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).height() >$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).width()) { 
		orientation = "v";
	} else { 
		orientation = "h";
	}
	if(parseFloat(w) < parseFloat(h) && orientation == "h") { 
		newwidth = h;
		newheight = w;
	}
	if(parseFloat(w) > parseFloat(h) && orientation == "v") { 
		newwidth = h;
		newheight = w;
	}
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-width",newwidth).attr("data-height",newheight).attr("data-show-width",newwidth).attr("data-show-height",newheight).attr("data-price",price).attr("data-canvas-id",id);
	$("#roomphoto").css({"background-image":"url("+$("#roomcontain").attr("data-photo")+")"});
	$("#roomphotoinsert").hide();
	off = $("#wallprice").offset();
	scrollto = off.top;
	$('html').animate({scrollTop:scrollto},200); 
	$('body').animate({scrollTop:scrollto},200); 
	$("#frameoptionsmenu").hide();
	showphotoinfo();
	sizeroomphoto();
	closesstuffwindow();
}

function showphotoinfo() { 
	if($("#roomcontain").attr("data-no-price") !== "1") { 

		lid = $("#roomcontain").attr("data-last-selected-photo");
		$(".roomphotoinfo").hide();
		showwidth = $("#roomphotocontainer-"+lid).attr("data-show-width") * 1;
		showheight = $("#roomphotocontainer-"+lid).attr("data-show-height") * 1;
		showprice = priceFormat($("#roomphotocontainer-"+lid).attr("data-price"));
		var showtotalwidth;
		var showtotalheight;
		var showmatprintwidth;
		var showmatprintheight;
		if($("#roomphotocontainer-"+lid).attr("data-frame-id") > 0) { 
			showtotalwidth = parseFloat(showwidth) + (parseFloat($("#roomphotocontainer-"+lid).attr("data-frame-width")) * 2);
			showtotalheight = parseFloat(showheight) + (parseFloat($("#roomphotocontainer-"+lid).attr("data-frame-width")) * 2);
			matwidth = parseFloat($("#roomphotocontainer-"+lid).attr("data-frame-mat-size"));
			if(matwidth > 0) { 
				showmatprintwidth = parseFloat($("#roomphotocontainer-"+lid).attr("data-mat-print-width")) * 1;
				showmatprintheight = parseFloat($("#roomphotocontainer-"+lid).attr("data-mat-print-height")) * 1;
				$("#photoinfo").html(showwidth+" x "+showheight+" "+showprice+" "+$("#roomcontain").attr("data-approx-width")+" "+showtotalwidth+" x "+showtotalheight+" | "+$("#roomcontain").attr("data-print-size")+" "+showmatprintwidth+" x "+showmatprintheight).show();
			} else { 
				$("#photoinfo").html(showwidth+" x "+showheight+" "+showprice+" "+$("#roomcontain").attr("data-approx-width")+" "+showtotalwidth+" x "+showtotalheight).show();
			}
		} else { 
			$("#photoinfo").html(showwidth +" x "+showheight+" "+showprice).show();
		}
	}
}

function turnselectedphoto() { 
	oldw = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-width");
	oldh = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-height");
	oldshoww = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-show-width");
	oldshowh = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-show-height");
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-width",oldh).attr("data-height",oldw).attr("data-show-width",oldshowh).attr("data-show-height",oldshoww);
	sizeroomphoto();
	showphotoinfo();
}


/*
function addframe() { 
	if($("#addframe").attr("checked")) { 	
		pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).css({"padding":$("#addframe").val() * pixels+"px"});
		$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css({"box-shadow":"inset 0px 0px 4px rgba(0,0,0,.5)"});
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"box-shadow":"inset 0px 0px 4px rgba(0,0,0,.5) "}); 
	} else { 
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).css({"padding":"0px"});
		$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css({"box-shadow":"none"});
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"box-shadow":"none"});
	}
}
*/

function addframe() { 
	if($("#addframe").attr("checked")) { 
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).addClass("borderframe");
		$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).addClass("photoshadow");
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").addClass("matshadow");
	} else { 
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).removeClass("borderframe");
		$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).removeClass("photoshadow");
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").removeClass("matshadow");

	}
}

function addmatte() { 
	if($("#addmatte").attr("checked")) { 
		pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"padding":$("#addmatte").val() * pixels+"px"});
	} else { 
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).find(".roomphotomatte").css({"padding":"0px"});
	}
}


function changeroom(photo,photowidth,photoheight,inches,top,bottom,measure) { 
	// $("#roomcontain").attr("data-center-photo","1");
	$("#roombackgroundphoto").hide();
	$("#roomphotoloading").show();
	// $("#roomchoicemenu").slideUp(100);
	$("#roombackgroundphoto").attr("src",tempfolder+photo).css({"max-width":photowidth+"px"});
	$("#roombackgroundphoto").imagesLoaded(function() {
		$("#roombackgroundphoto").css({"max-width":photowidth+"px"});
		$("#roomcontain").attr("data-room-photo-width",photowidth).attr("data-room-photo-height",photoheight).attr("data-room-width",inches).attr("data-center",top).attr("data-base",bottom).attr("data-room-background-photo",photo);
		maxwidth = 1200;
		if(photowidth < 1200) { 
			maxwidth = photowidth;
		}
		$("#roomcontain").css({"max-width":maxwidth+"px"});
		$("#roombackgroundphoto").fadeIn(200, function() { 
			if(measure == "1") { 
				$("#roommeasurelink").show();
			} else { 
				$("#roommeasurelink").hide();
			}
			$("#roomphotoloading").hide();
			sizeroomphoto();
			closesstuffwindow();
			off = $("#wallprice").offset();
			scrollto = off.top;
			$('html').animate({scrollTop:scrollto},200); 
			$('body').animate({scrollTop:scrollto},200); 
			$("#missingroomphoto").hide();
		});
	});
}

function changeroominches(dir) { 
	if(dir == "down") { 
		newval = Math.abs($("#roominches").val()) - 1;
	}
	if(dir == "up") { 
		newval = Math.abs($("#roominches").val()) + 1;
	}
	$("#roominches").val(newval);
	newinches = newval * 12;
	$("#roomcontain").attr("data-room-width",newinches);
	sizeroomphoto();
}

function showchangeroominches() { 
	$("#roomsize").slideDown(200);
}

function hidechangeroominches() { 
	$("#roomsize").slideUp(200);
}

function handleKeys(e) {
    var position, 
        draggable = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")),
        container = $("#roomcontain"),
        distance = 1; // Distance in pixels the draggable should be moved
    position = draggable.position();
    // Reposition if one of the directional keys is pressed
    switch (e.keyCode) {
        case 37: position.left -= distance; break; // Left
        case 38: position.top  -= distance; break; // Up
        case 39: position.left += distance; break; // Right
        case 40: position.top  += distance; break; // Down
        default: return true; // Exit and bubble
    }

    // Keep draggable within container
    if (position.left >= 0 && position.top >= 0 &&
        position.left + draggable.width() <= container.width() &&
        position.top + draggable.height() <= container.height()) {
        draggable.css(position);
    }

    // Don't scroll page
    e.preventDefault();
	return false;
}

function firstphoto() { 
	$("#roomcontain").attr("data-selected-photo",1);
	lid = 1;
	
	$("#photomenuactions").show();
	$("#photomenuactionsclickphoto").hide();

	$("#roomphotocontainer-"+lid).addClass("selectedroomphoto");

	if($("#roomphotocontainer-"+lid).find(".roomphoto").attr("data-bw") !== "1") { 
		$("#bwbuttonoriginal").hide();
		$("#bwbutton").show();
	} else { 
		$("#bwbuttonoriginal").show();
		$("#bwbutton").hide();
	}

		if($("#roomphotocontainer-"+lid).attr("data-frame-id") > 0) { 
			styleid = $("#roomphotocontainer-"+lid).attr("data-style-id");
			frameid = $("#roomphotocontainer-"+lid).attr("data-frame-id");
			colorid = $("#roomphotocontainer-"+lid).attr("data-color-id");
			matcolor = $("#roomphotocontainer-"+lid).attr("data-mat-color");
			if($("#frame-options-"+styleid).length <= 0) { 
				$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=getframestyleoptions&styleid="+$("#roomphotocontainer-"+lid).attr("data-style-id"), function(data) {
					$("#frameoptionsmenu").show().html(data);
					$(".frameselections").removeClass("selectedprintsize");
					$("#frame-"+styleid+"-"+frameid).addClass("selectedprintsize");
					$(".framecolorselections").removeClass("selectedframecolor");
					$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
					$(".matcolorselections").removeClass("selectedmatcolor");
					framecanmat = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
					if(framecanmat <= 0) { 
						$("#mat-options-"+styleid).hide();
						$(".matcolorselections").removeClass("selectedmatcolor");
					} else { 
						$("#mat-options-"+styleid).show();
						$("#matcolor-"+styleid+"-"+matcolor).addClass("selectedmatcolor");
					}
				});
			} else { 
				$(".frameselections").removeClass("selectedprintsize");
				$(".framecolorselections").removeClass("selectedframecolor");
				$(".matcolorselections").removeClass("selectedmatcolor");
				$("#frame-"+styleid+"-"+frameid).addClass("selectedprintsize");
				$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
				framecanmat = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
				if(framecanmat <= 0) { 
					$("#mat-options-"+styleid).hide();
					$(".matcolorselections").removeClass("selectedmatcolor");
				} else { 
					$("#mat-options-"+styleid).show();
					$("#matcolor-"+styleid+"-"+matcolor).addClass("selectedmatcolor");
				}
			}
			$("#frameoptionsmenu").show();
		} else { 
			$("#frameoptionsmenu").hide();
		}
		$("#roomphotocontainer-"+lid).addClass("selectedroomphoto");

		if($("#roomphotocontainer-"+lid).find(".roomphoto").attr("data-bw") !== "1") { 
			$("#bwbuttonoriginal").hide();
			$("#bwbutton").show();
		} else { 
			$("#bwbuttonoriginal").show();
			$("#bwbutton").hide();
		}
	$("#roomcontain").attr("data-last-selected-photo",$("#roomphotocontainer-"+lid).attr("data-photo-number"));
	backgrounddeselectphoto();
}



function selectingphoto() { 
	$("#roomcontain .roomphotocontainer").mousedown(function() {
		$("#roomcontain").attr("data-selected-photo",$(this).attr("data-photo-number"));
		$("#roomcontain .roomphotocontainer").removeClass("selectedroomphoto");
		$("#photomenuactions").show();
		$("#photomenuactionsclickphoto").hide();
		if($(this).attr("data-frame-id") > 0) { 
			styleid = $(this).attr("data-style-id");
			frameid = $(this).attr("data-frame-id");
			colorid = $(this).attr("data-color-id");
			matcolor = $(this).attr("data-mat-color");
			if($("#frame-options-"+styleid).length <= 0) { 
				$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=getframestyleoptions&styleid="+$(this).attr("data-style-id"), function(data) {
					$("#frameoptionsmenu").show().html(data);
					$(".frameselections").removeClass("selectedprintsize");
					$("#frame-"+styleid+"-"+frameid).addClass("selectedprintsize");
					$(".framecolorselections").removeClass("selectedframecolor");
					$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
					$(".matcolorselections").removeClass("selectedmatcolor");
					framecanmat = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
					if(framecanmat <= 0) { 
						$("#mat-options-"+styleid).hide();
						$(".matcolorselections").removeClass("selectedmatcolor");
					} else { 
						$("#mat-options-"+styleid).show();
						$("#matcolor-"+styleid+"-"+matcolor).addClass("selectedmatcolor");
					}
				});
			} else { 
				$(".frameselections").removeClass("selectedprintsize");
				$(".framecolorselections").removeClass("selectedframecolor");
				$(".matcolorselections").removeClass("selectedmatcolor");
				$("#frame-"+styleid+"-"+frameid).addClass("selectedprintsize");
				$("#framecolor-"+styleid+"-"+colorid).addClass("selectedframecolor");
				framecanmat = parseFloat($("#frame-"+styleid+"-"+frameid).attr("data-mat-width"));
				if(framecanmat <= 0) { 
					$("#mat-options-"+styleid).hide();
					$(".matcolorselections").removeClass("selectedmatcolor");
				} else { 
					$("#mat-options-"+styleid).show();
					$("#matcolor-"+styleid+"-"+matcolor).addClass("selectedmatcolor");
				}
			}
			$("#frameoptionsmenu").show();
		} else { 
			$("#frameoptionsmenu").hide();
		}
		$(this).addClass("selectedroomphoto");

		if($(this).find(".roomphoto").attr("data-bw") !== "1") { 
			$("#bwbuttonoriginal").hide();
			$("#bwbutton").show();
		} else { 
			$("#bwbuttonoriginal").show();
			$("#bwbutton").hide();
		}
		$("#roomcontain").attr("data-last-selected-photo",$(this).attr("data-photo-number"));
		if($(this).attr("data-default") !== "1") { 
			showphotoinfo();
		}
	});
	backgrounddeselectphoto();
}

function backgrounddeselectphoto() { 
	// Click outside and undo selected photo
	$("#roombackgroundphoto").click(function() {
		$("#roomcontain .roomphotocontainer").removeClass("selectedroomphoto");
		$(".roomphotoinfo").hide();
		$("#photomenuactions").hide();
		$("#photomenuactionsclickphoto").show();
		$("#frameoptionsmenu").hide();
		$("#photoinfo").html("&nbsp;");
		$("#roomcontain").attr("data-selected-photo","0");
	});
}
function sidethumbs(sub_id,change,favs,date_id) {
	fixbackground();
	if(sub_id > 0) { 
		$("#sidethumbs").attr("sub_id",sub_id);
	} else { 
		$("#sidethumbs").attr("sub_id",0);
	}
	if($("#roomcontain").attr("data-from") == "favorites" || favs == "1") { 
		view = "favorites";
		$("#sidethumbs").attr("view","favorites");
	} else { 
		view = "";
		$("#sidethumbs").attr("view","");
	}
	$("#sidethumbs").attr("data-open","1");
	if($("#sidethumbs").attr("data-opened") =="1" && change !== "1") { 
		$( "#sidethumbs" ).css({"top":$(window).scrollTop()+add_header+"px"}).show("slide", { direction: "right" }, 300, function() { 
			$("#sidethumbsclose").css({"position":"fixed"});
			$( "#sidethumbs").css({"top":"40px"});
		 });
	} else { 
		$.get(tempfolder+"/sy-inc/sy-side-thumbnails.php?date_id="+$("#vinfo").attr("did")+"&sub_id="+sub_id+"&view="+view, function(data) {
			$("#sidethumbsinner").html(data);
			add_header = 0;
			$("#sidethumbs").attr("data-opened","1");
			if(change !== "1") { 
				$( "#sidethumbs" ).css({"top":$(window).scrollTop()+add_header+"px"}).show("slide", { direction: "right" }, 300, function() { 
					$("#sidethumbsclose").css({"position":"fixed"});
					$( "#sidethumbs").css({"top":"40px"});
				 });
			}
			sidethumbposition();
		});
	}
}

function sidethumbsclose() { 
	$("#sidethumbsclose").css({"position":"relative"});
	$( "#sidethumbs").css({"top":"0px"});
	$("#sidethumbs").attr("data-open","0");

	$( "#sidethumbs" ).stop(true,true).hide("slide", { direction: "right" }, 200, function() { 
		// $("#sidethumbsinner").html("");
		$("#sidethumbsbg").fadeOut(100, function() { 
			// $("#sidethumbsbg").unbind('click');
			unfixbackground();
		});
	});
}
var sidethumbpopulate
function sidethumbposition() {
	sidethumbpopulate = setInterval("getsidethumbposition()",500);
}

function getsidethumbposition() { 
	$(".sidethumbpopulate").each(function(i){
		loadit =$(window).scrollTop() + $(window).height() + 400;

		if(!$("#sidethumbpage-"+$(this).attr("data-next-page")).length) { 
			nextpage =  parseFloat($(this).attr("data-next-page"));
			totalpages = parseFloat($(this).attr("data-total-pages"));
			if(totalpages > parseFloat($(this).attr("data-this-page"))) { 
				if(loadit > $("#sidethumbs").height()) { 
						$("#sidethumbpage-"+$(this).attr("data-this-page")).remove();
					$.get(tempfolder+"/sy-inc/sy-side-thumbnails.php?date_id="+$("#vinfo").attr("did")+"&sub_id="+$("#sidethumbs").attr("sub_id")+"&view="+$("#sidethumbs").attr("view")+"&page="+$(this).attr("data-next-page"), function(data) {
						$("#sidethumbsinner").append(data);
					});
					if(nextpage == totalpages) { 
						clearInterval(sidethumbpopulate);
						sidethumbpopulate = 0;
					}
				}
			}
		}
	});
}

function doubleclickopenphotos() { 
	$('.roomphoto').unbind('dblclick');
	$( ".roomphoto" ).dblclick(function() {
		if($("#sidethumbs").css("display") == "none") { 
		  sidethumbs('0');
		}
	});
	doubleclickclosephotos();
}

function doubleclickclosephotos() { 
	$('#roombackgroundphoto').unbind('dblclick');
	$("#roombackgroundphoto").dblclick(function() {
		if($("#sidethumbs").css("display") !== "none") { 
			sidethumbsclose();
		}
	});
}

function selectcollage(total,vals) { 
	existingphotos = new Array();
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			//$("#log").show().append($(this).find(".roomphoto").attr("data-pic-key")+"<br>");
			existingphotos.push($(this).find(".roomphoto").attr("data-photo-file")+"|"+$(this).find(".roomphoto").attr("data-pic-key"));
			$(this).remove();
		}
	});

	$("#roomcontain").attr("data-center-photo","1");
	data = vals.split('|');
	$("#roomcontain .roomphotocontainer").hide();
	for (i = 1; i <= total; i++) {
		val = data[i -1].split(',');
		timestamp = new Date().getTime() + i;
		html = $("#roomphotocode").html();
		html = html.replace(/0000/g,timestamp);
		$("#roomcontain").append(html);
		$("#roomphotocontainer-"+timestamp).attr("data-width",val[0]).attr("data-height",val[1]).attr("data-show-width",val[0]).attr("data-show-height",val[1]).attr("data-from-center",val[2]).attr("data-from-base",val[3]).attr("data-price",val[4]).show();

		$("#log").show().append(i+" ---| ---"+existingphotos[i - 1].split("|")+"<br>");

		if(i <= existingphotos.length) { 
			addphoto = existingphotos[i - 1].split("|");
			$("#roomphoto-"+timestamp).css({"background-image":"url('"+addphoto[0]+"')"}).attr("data-pic-key",addphoto[1]).attr("data-photo-file",addphoto[0]);
		}
		$( "#roomphotocontainer-"+timestamp).draggable({grid: [ 4, 4 ],
		  start: function() {
			$("#roomcontain").attr("data-center-photo","0");
		  }
		});
	}
	$("#roomcontain .roomphotocontainer").removeClass("selectedroomphoto");
	$("#photomenuactions").hide();
	$("#photomenuactionsclickphoto").show();
	$("#frameoptionsmenu").hide();
	$(".roomphotoinfo").hide();
	$("#roomcontain").attr("data-selected-photo","0");
	closesstuffwindow();
	selectingphoto();
	sizeroomphoto();
}

function addnewprint() { 
	timestamp = new Date().getTime();
	html = $("#roomphotocode").html();
	html = html.replace(/0000/g,timestamp);
	if($("#roomcontain").attr("data-last-selected-photo") !== "") { 
		//	$("#log").show().append($("#roomphotocontainer-"+$("#roomcontain").attr("data-last-selected-photo")).css("display")+" | "+$("#roomphotocontainer-"+$("#roomcontain").attr("data-last-selected-photo")).length+"<br>");
		if($("#roomphotocontainer-"+$("#roomcontain").attr("data-last-selected-photo")).css("display") == "none" || $("#roomphotocontainer-"+$("#roomcontain").attr("data-last-selected-photo")).css("display") == "undefined"  || $("#roomphotocontainer-"+$("#roomcontain").attr("data-last-selected-photo")).length <= 0) { 
			$("#roomcontain").attr("data-last-selected-photo","")
		}
	}
	if($("#roomcontain").attr("data-last-selected-photo") == "") { 
		$("#roomcontain .roomphotocontainer").each(function(i){
			if($(this).css("display") !== "none") { 
				$("#roomcontain").attr("data-last-selected-photo",$(this).attr("data-photo-number"));
			}
		});
	}
	$("#roomcontain .roomphotocontainer").removeClass("selectedroomphoto");
	$("#roomcontain").append(html);
	$("#roomcontain").attr("data-selected-photo",timestamp);
	if($("#roomcontain").attr("data-last-selected-photo") !== "") { 
		lid = $("#roomcontain").attr("data-last-selected-photo");

		if($("#roomphotocontainer-"+lid).css("display") !== "none") { 
			$("#roomphotocontainer-"+timestamp).attr("data-width",$("#roomphotocontainer-"+lid).attr("data-width")).attr("data-height",$("#roomphotocontainer-"+lid).attr("data-height")).attr("data-show-width",$("#roomphotocontainer-"+lid).attr("data-show-width")).attr("data-show-height",$("#roomphotocontainer-"+lid).attr("data-show-height")).attr("data-price",$("#roomphotocontainer-"+lid).attr("data-price"));
			/* Adding frame if frame selected for last photo */
			if($("#roomphotocontainer-"+lid).attr("data-frame-id") > 0) { 
				$("#roomphotocontainer-"+timestamp).addClass("borderframe").css({"border-image":$("#roomphotocontainer-"+lid).css("border-image-source")+" "+$("#roomphotocontainer-"+lid).css("border-image-slice")+" "+$("#roomphotocontainer-"+lid).css("border-image-repeat"), "border-width":$("#roomphotocontainer-"+lid).css("border-left-width")});

				$("#roomphotocontainer-"+timestamp).attr("data-frame-id",$("#roomphotocontainer-"+lid).attr("data-frame-id")).attr("data-frame-mat-size",$("#roomphotocontainer-"+lid).attr("data-frame-mat-size")).attr("data-frame-width",$("#roomphotocontainer-"+lid).attr("data-frame-width")).attr("data-frame-id",$("#roomphotocontainer-"+lid).attr("data-frame-id")).attr("data-frame-file",$("#roomphotocontainer-"+lid).attr("data-frame-file")).attr("data-frame-corners",$("#roomphotocontainer-"+lid).attr("data-frame-corners")).attr("data-mat-print-width",$("#roomphotocontainer-"+lid).attr("data-mat-print-width")).attr("data-mat-print-height",$("#roomphotocontainer-"+lid).attr("data-mat-print-height"));
				$("#roomphotocontainer-"+timestamp).attr("data-style-id",$("#roomphotocontainer-"+lid).attr("data-style-id")).attr("data-color-id",$("#roomphotocontainer-"+lid).attr("data-color-id")).attr("data-mat-color",$("#roomphotocontainer-"+lid).attr("data-mat-color")).removeClass("canvasshadow").addClass("frameshadow");
				$("#roomphoto-"+timestamp).addClass("photoshadow");
				$("#roomphotocontainer-"+timestamp).find(".roomphotomatte").css({"background":"#"+$("#roomphotocontainer-"+lid).attr("data-mat-color")});
			} else { 
				$("#roomphotocontainer-"+timestamp).attr("data-canvas-id",$("#roomphotocontainer-"+lid).attr("data-canvas-id"))
			}
		}
	}
	$("#roomcontain").attr("data-center-photo","0");
	$("#roomphotocontainer-"+timestamp).show().css({"left":"12px","top":"12px"});
	$("#roomphotocontainer-"+timestamp).addClass("selectedroomphoto").addClass("move");
	$("#photomenuactions").show();
	$("#photomenuactionsclickphoto").hide();

	$("#roomphotocontainer-"+timestamp).draggable({grid: [ 4, 4 ],
	  start: function() {
		$("#roomcontain").attr("data-center-photo","0");
	  }
	});
	$("#roomcontain").attr("data-last-selected-photo",timestamp);
	showphotoinfo();
	selectingphoto();
	sizeroomphoto();
}

function removeprint() { 
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).remove();
	$("#photomenuactions").hide();
	$("#photomenuactionsclickphoto").show();
	$("#frameoptionsmenu").hide();
	sizeroomphoto();
}

function showwallprice() { 
	if($("#roomcontain").attr("data-no-price") == "1") { 
		$("#purchaselink").hide();
		$("#wallprice").hide();
	} else { 
		if(getwallprice() > 0) { 
			$("#wallprice").html(priceFormat(getwallprice()));
			$("#purchaselink").show();
		} else { 
			$("#wallprice").html("&nbsp;");
			$("#purchaselink").hide();
		}
	}
}
function totalphotoitems() { 
	totalitems = 0;
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			totalitems = totalitems + 1;
		}
	});
	return totalitems;
}

function photofiles() { 
	files = "";
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			files = files+"|"+$(this).find(".roomphoto").attr("data-pic-key");
		}
	});
	return files;
}

function totalphotofiles() { 
	totalphotos = 0;
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			if($(this).find(".roomphoto").attr("data-pic-key") !== "") { 
				totalphotos = totalphotos + 1;
			}
		}
	});
	return totalphotos;
}

function getwallprice() { 
	price = 0;
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			thisprice = parseFloat($(this).attr("data-price"));
			if(thisprice > 0) { 
				price = price + thisprice;
			}
		}
	});
	return price;
}

function savedialog() { 
	$("#accloading").hide();
	$("#roommenu").slideUp(200);
	$("#roomuploadbackground").attr("data-window","savedialog");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#savedialog").css({"top":"0px"})
	$("#savedialog").show();
	otop = $("#savedialog").offset().top;
	$("#savedialog").hide();
	if($("#wall_id").val() == "") { 
		$("#saveasoption").hide();
	} else { 
		$("#saveasoption").show();
	}
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		$("#savedialog").css({"top":to+"px"}).fadeIn(200, function() { 
			
		});
		$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
		$("#accloading").hide();
	});

}


function deletesavedwall(link) { 
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=deletesaved&walllink="+link, function(data) {
		$("#mysaved-"+link).slideUp(200);
	});
}
function deletewallcollage(link) { 
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=deletesaved&walllink="+link, function(data) {
		closesstuffwindow();
	});
}


function showmysavedrooms(pid,ret,sub) { 
	$("#roomphotoloading").show();
	$("#roommenu").slideUp(200);
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=mysaved&pid="+pid+"&ret="+ret+"&sub="+sub, function(data) {
		$("#accloading").hide();
		$("#roomuploadbackground").attr("data-window","myrooms");
		$("#roomuploadbackground").prop('onclick',null).off('click');
		$("#myrooms").css({"top":"0px"})
		$("#myrooms").show();
		otop = $("#myrooms").offset().top;
		$("#myrooms").hide();
		$("#roomuploadbackground").fadeIn(100, function() { 
			to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
			$("#myroomscontent").html(data);
			$("#myrooms").css({"top":to+"px"}).fadeIn(200);
			$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
			$("#accloading").hide();
		});
		$("#roomphotoloading").hide();
	});
}


function showsaveddialog() { 
	$("#roommenu").slideUp(200);
	$("#"+$("#roomuploadbackground").attr("data-window")).fadeOut(50, function() { 
		$("#accloading").hide();
		$("#roomuploadbackground").attr("data-window","saveddialog");
		$("#roomuploadbackground").prop('onclick',null).off('click');
		$("#saveddialog").css({"top":"0px"})
		$("#saveddialog").show();
		otop = $("#saveddialog").offset().top;
		$("#saveddialog").hide();
		$("#roomuploadbackground").fadeIn(100, function() { 
			to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
			$("#saveddialog").css({"top":to+"px"}).fadeIn(200);
			$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
			$("#accloading").hide();
		});
	});
		
}

function showsaveddialogloggedin() { 
	$("#roommenu").slideUp(200);
	$("#accloading").hide();
	$("#roomuploadbackground").attr("data-window","saveddialog");
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$("#saveddialog").css({"top":"0px"})
	$("#saveddialog").show();
	otop = $("#saveddialog").offset().top;
	$("#saveddialog").hide();
	$("#roomuploadbackground").fadeIn(100, function() { 
		to = $("#shopmenucontainer").height() + $(window).scrollTop() - otop + 30;
		$("#saveddialog").css({"top":to+"px"}).fadeIn(200, function() { 
			
		});
		$("#roomuploadbackground").bind('click', function() {closesstuffwindow() });
		$("#accloading").hide();
	});			
}

function saveroom(login,addtocart) { 
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	center = (Math.abs($("#roomcontain").attr("data-center")) * $("#roombackground").width());
	topcenter = (Math.abs($("#roomcontain").attr("data-base")) * $("#roombackground").height());
	roomdata = "data-room-photo-width="+$("#roomcontain").attr("data-room-photo-width")+",data-room-photo-height="+$("#roomcontain").attr("data-room-photo-height")+",data-room-width="+$("#roomcontain").attr("data-room-width")+",data-center="+$("#roomcontain").attr("data-center")+",data-base="+$("#roomcontain").attr("data-base")+",data-room-background-photo="+$("#roomcontain").attr("data-room-background-photo");
	
	var wallitems = "";
	var fav_date_id;
	var fav_sub_id;
	$("#roomcontain .roomphotocontainer").each(function(i){
		if($(this).css("display") !== "none") { 
			width = $(this).attr("data-width");
			height = $(this).attr("data-height");
			framewidth = "";
			matwidth = "";
			if($(this).attr("data-frame-id") > 0) { 
				framewidth =  parseFloat($(this).attr("data-frame-width"));
			}
			cleft = parseFloat($(this).css("left")) + ($(this).width() / 2);
			ctop = parseFloat($(this).css("top")) + ($(this).height());
			cleftinches = Math.round(cleft - center) / pixels;
			ctopinches = Math.round(ctop - topcenter) / pixels;

			ctopinches = ctopinches + (framewidth * 2);
			cleftinches = cleftinches + framewidth;

			wallitems += "data-width="+$(this).attr("data-width")+",\
			data-height="+$(this).attr("data-height")+",\
			data-show-width="+$(this).attr("data-show-width")+",\
			data-show-height="+$(this).attr("data-show-height")+",\
			data-from-center="+cleftinches+",\
			data-from-base="+ctopinches+",\
			data-photo-number="+$(this).attr("data-photo-number")+",\
			data-price="+$(this).attr("data-price")+",\
			data-style-id="+$(this).attr("data-style-id")+",\
			data-frame-id="+$(this).attr("data-frame-id")+",\
			data-color-id="+$(this).attr("data-color-id")+",\
			data-frame-width="+$(this).attr("data-frame-width")+",\
			data-frame-mat-size="+$(this).attr("data-frame-mat-size")+",\
			data-mat-color="+$(this).attr("data-mat-color")+",\
			data-mat-color-id="+$(this).attr("data-mat-color-id")+",\
			data-mat-print-width="+$(this).attr("data-mat-print-width")+",\
			data-mat-print-height="+$(this).attr("data-mat-print-height")+",\
			data-frame-corners="+$(this).attr("data-frame-corners")+",\
			data-canvas-depth="+$(this).attr("data-canvas-depth")+",\
			data-canvas-id="+$(this).attr("data-canvas-id")+",\
			data-frame-file="+$(this).attr("data-frame-file")+",\
			data-print-product-id="+$(this).attr("data-print-product-id")+",\
			data-photo-file="+$(this).find(".roomphoto").attr("data-photo-file")+",\
			data-pic-key="+$(this).find(".roomphoto").attr("data-pic-key")+",\
			data-bw="+$(this).find(".roomphoto").attr("data-bw")+",\
			data-date-id="+$(this).find(".roomphoto").attr("data-date-id")+",\
			data-sub-id="+$(this).find(".roomphoto").attr("data-sub-id")+",\
			data-zoom="+$(this).find(".roomphoto").attr("data-zoom")+",\
			data-y-pos="+$(this).find(".roomphoto").attr("data-y-pos")+",\
			data-x-pos="+$(this).find(".roomphoto").attr("data-x-pos")+",\
			data-canvas-edge="+$(this).attr("data-canvas-edge")+"||";

			if(fav_date_id == null) { 
				fav_date_id = $(this).find(".roomphoto").attr("data-date-id");
				fav_sub_id = $(this).find(".roomphoto").attr("data-sub-id");
			}
		}
	});





	var fields = {};
	fields['roomdata'] = roomdata;
	fields['wallitems'] = wallitems;
	fields['savename'] = $("#wall_name").val();
	if($("#roomcontain").attr("data-date-id") == "") { 
		fields['date_id'] = fav_date_id;
		fields['sub_id'] = fav_sub_id;
	} else { 
		fields['date_id'] = $("#roomcontain").attr("data-date-id");
		fields['sub_id'] = $("#roomcontain").attr("data-sub-id");
	}
	fields['save_as_collection'] = "";
	fields['wall_no_edit'] = "";
	fields['wall_no_price'] = "";
	if($("#save_as_collection").attr("checked")) { 
		fields['save_as_collection'] = "1";
		$("#collectionsmenu").show();
	}
	if($("#wall_no_edit").attr("checked")) { 
		fields['wall_no_edit'] = "1";
	}
	if($("#wall_no_price").attr("checked")) { 
		fields['wall_no_price'] = "1";
	}
	fields['plid'] = $("#vinfo").attr("plid");

	if(addtocart == "1") { 
		imageoptions = '';
		$(".imageoption").each(function(i){
			if($(this).attr("checked")) { 
				imageoptions += $(this).attr("data-opt-id")+","+$(this).attr("data-pic")+"|";
			}
		});
		// $("#log").show().append(imageoptions+"<br>");
		fields['imageoptions'] = imageoptions;
		fields['action'] = "addalltocart";
	} else { 
		fields['action'] = "saveroom";
	}
	fields['pid'] = $("#pid").val();
	fields['wallprice'] = getwallprice();

	if($("#wall_id").val() == "") { 
		fields['saveas'] = "new";
	} else { 
		fields['wall_id'] = $("#wall_id").val();
		if($("#saveassave").attr("checked")) { 
			fields['saveas'] = "save";
		}
		if($("#saveasnew").attr("checked")) { 
			fields['saveas'] = "new";
		}
	}
	if(login == "1") { 
		fields['saveas'] = "new";
		fields['saveandlogin'] = "1";
		fields['savename'] = "My wall collection";
	}

	$.post(tempfolder+'/sy-inc/room-view/room-view-actions.php', fields,	function (data) { 
		data = $.trim(data);
		$("#wall_id").val(data);
		if($("#save_as_collection").attr("checked")) { 
			$("#wdsharelink").hide();
			$("#collectionsaved").show();
		} else { 
			$("#collectionsaved").hide();
			$("#room_link").attr("href",$("#roomcontain").attr("data-url")+"?wd="+data);
			$("#room_link").html($("#roomcontain").attr("data-url")+"?wd="+data);
			$("#wdsharelink").show();
		}
		if(login == "1") { 
			showgallerylogin('room','','','login');
		} else { 
			if(addtocart == "1") { 
				roomaddtocartsuccess();
			} else { 
				showsaveddialog();
			}
		}
		$("#saveassave").attr("checked",true)
	});
}


function blackwhite() { 
	pickey = $("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).attr("data-pic-key");
	$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css({"background-image":"url('"+tempfolder+"/sy-photo.php?thephoto="+pickey+"|84b70c81232d746dbd3c18d1f036be7f|0|1')"});
	$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).attr("data-bw","1");
	$("#bwbuttonoriginal").show();
	$("#bwbutton").hide();
}

function blackwhiteoriginal() { 
	$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css({"background-image":"url('"+$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).attr("data-photo-file")+"')"});
	$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).attr("data-bw","0");
	$("#bwbuttonoriginal").hide();
	$("#bwbutton").show();
}

function changephoto(photo,key,date_id,sub_id,width,height) { 
	lid = $("#roomcontain").attr("data-last-selected-photo");
	setroomphotoposition();
	$("#roomphoto-"+lid).css({"background-image":"url('"+photo+"')"}).attr("data-photo-file",photo).attr("data-pic-key",key).attr("data-date-id",date_id).attr("data-sub-id",sub_id).attr("data-pic-width",width).attr("data-pic-height",height);
	$("#roomphoto-"+lid).attr("data-zoom","0");
	$("#roomphoto-"+lid).attr("data-y-pos","50");
	$("#roomphoto-"+lid).attr("data-x-pos","50");
	$("#roomphoto-"+lid).attr("data-bw","0");
	$("#bwbuttonoriginal").hide();
	$("#bwbutton").show();

	if($(window).width() < 800) { 
		sidethumbsclose();
	}
	sizeroomphoto();
}

function zoomphotoadjust() { 
	if($("#sidethumbs").attr("data-open") == "1") { 
		sidethumbsclose();
	}
	$("#roomuploadbackground").fadeIn(100, function() { 
	$("#roomuploadbackground").prop('onclick',null).off('click');
	$('.roomphoto').unbind('dblclick');
		lid = $("#roomcontain").attr("data-last-selected-photo");
		ww = $("#roomcontain").width();
		wh = $("#roomcontain").height();
		thiswidth = $("#roomphotocontainer-"+lid).width() * 2.5;
		thisleft = $("#roomphotocontainer-"+lid).css("left").replace("px", "");
		pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
		$("#roomphotocontainer-"+lid).css({"left":"50%","margin-left":"-"+thiswidth / 2+"px","z-index":"200"}).attr("data-adjusting-left",thisleft);
		$("#roomphoto-"+lid).css({"width":($("#roomphoto-"+lid).width() * 2.5)+"px","height":($("#roomphoto-"+lid).height() * 2.5)+"px"});
		positionroomphoto(lid);
		if($(window).width() < 800) { 
			$("#mobileadjust").show();
		} else { 
			$("#roomphotocontainer-"+lid).find(".adjustphoto").show();
		}
		$("#roommenu").slideUp(200, function() { 
			$("#adjustmenu").slideDown(200);
		});
	});
}

function unzoomphotoadjust() { 
	$("#roomuploadbackground").fadeOut(100, function() { 
		lid = $("#roomcontain").attr("data-last-selected-photo");
		backleft = $("#roomphotocontainer-"+lid).attr("data-adjusting-left");
		pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
		$("#roomphotocontainer-"+lid).css({"left":backleft+"px","z-index":"1","margin-left":"0px"});
		$("#roomphotocontainer-"+lid).find(".adjustphoto").hide();
		$("#mobileadjust").hide();

		sizeroomphoto();
		doubleclickopenphotos();
		$("#adjustmenu").slideUp(200, function() { 
			$("#roommenu").slideDown(200);
		});
	});
}

function positionallroomphotos() { 
	$("#roomcontain .roomphotocontainer").each(function(i){
	lid = $(this).attr("data-photo-number");
		positionroomphoto(lid)
	});
}

function positionroomphoto(lid) { 
	if(lid <= 0) { 
		lid = $("#roomcontain").attr("data-last-selected-photo");
	}
	pw = parseFloat($("#roomphoto-"+lid).attr("data-pic-width"));
	ph = parseFloat($("#roomphoto-"+lid).attr("data-pic-height"));
	zoom = parseFloat($("#roomphoto-"+lid).attr("data-zoom"));
	xPos = parseFloat($("#roomphoto-"+lid).attr("data-x-pos"));
	yPos = parseFloat($("#roomphoto-"+lid).attr("data-y-pos"));
	divw = $("#roomphoto-"+lid).width();
	divh= $("#roomphoto-"+lid).height();
	neww = divw;
	perc = divw / pw;
	newh = ph * perc;
	if(newh < divh) { 
		newh = divh;
		perc = divh / ph;
		neww = pw * perc;
	}
	if(zoom > 0) { 
		z = zoom / 10;
		$("#roomphoto-"+lid).attr("data-zoom",zoom);
		neww = neww + (neww * z);
		newh = newh + (newh * z);
	}
	neww = neww + 2;
	newh = newh + 2;
	$("#roomphoto-"+lid).css({"background-size":neww+"px "+newh+"px","background-position":xPos+"% "+yPos+"%"});
}

function setroomphotoposition() { 
	lid = $("#roomcontain").attr("data-last-selected-photo");
	pw = parseFloat($("#roomphoto-"+lid).attr("data-pic-width"));
	ph = parseFloat($("#roomphoto-"+lid).attr("data-pic-height"));
	zoom = parseFloat($("#roomphoto-"+lid).attr("data-zoom"));
	xPos = parseFloat($("#roomphoto-"+lid).attr("data-x-pos"));
	yPos = parseFloat($("#roomphoto-"+lid).attr("data-y-pos"));
	divw = $("#roomphoto-"+lid).width();
	divh= $("#roomphoto-"+lid).height();
	neww = divw;
	perc = divw / pw;
	newh = ph * perc;
	if(newh < divh) { 
		newh = divh;
		perc = divh / ph;
		neww = pw * perc;
	}
	$("#roomphoto-"+lid).attr("data-zoom",0).attr("data-y-pos",50).attr("data-x-pos",50);

	$("#roomphoto-"+lid).css({"background-size":neww+"px "+newh+"px"});
}

function adjustzoom(dir) { 
	lid = $("#roomcontain").attr("data-selected-photo");
	pw = parseFloat($("#roomphoto-"+lid).attr("data-pic-width"));
	ph = parseFloat($("#roomphoto-"+lid).attr("data-pic-height"));
	divw = $("#roomphoto-"+lid).width();
	divh= $("#roomphoto-"+lid).height();
	neww = divw;
	perc = divw / pw;
	newh = ph * perc;
	if(newh < divh) { 
		newh = divh;
		perc = divh / ph;
		neww = pw * perc;
	}
	if($("#roomphoto-"+lid).attr("data-zoom") == "") { 
		$("#roomphoto-"+lid).attr("data-zoom","0");
	}
	if(dir == "in") { 
		zoom = parseFloat($("#roomphoto-"+lid).attr("data-zoom")) + 1;
	} 
	if(dir == "out") { 
		zoom = parseFloat($("#roomphoto-"+lid).attr("data-zoom")) - 1;
	} 
	if(zoom > 0) { 
		z = zoom / 10;
		$("#roomphoto-"+lid).attr("data-zoom",zoom);
		neww = neww + (neww * z);
		newh = newh + (newh * z);
	}
	$("#roomphoto-"+lid).css({"background-size":neww+"px "+newh+"px"});
}

function adjustselectedphotoposition(d) { 
	backgroundPos = $("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css('backgroundPosition').split(" ");
//now contains an array like ["0%", "50px"]
	xPos = parseFloat(backgroundPos[0]);
    yPos = parseFloat(backgroundPos[1]);
	if(d == "up") { 
		yPos = yPos + 4;
	}
	if(d == "down") { 
		yPos = yPos - 4;
	}
	if(d == "left") { 
		xPos = xPos + 4;
	}
	if(d == "right") { 
		xPos = xPos - 4;
	}
	if((xPos > 2 && xPos < 100) && (yPos > 2 && yPos < 100)) { 
		$("#roomphoto-"+$("#roomcontain").attr("data-selected-photo")).css({"background-position":xPos+"% "+yPos+"%"});
		$("#roomphoto-"+lid).attr("data-y-pos",yPos).attr("data-x-pos",xPos);

	}
}


function copylink() { 

	clipboard.copy($("#room_link").html());
	$("#linkcopied").fadeIn(100);
}
function showhelpbubble() { 
	roommenutop = $("#roommenu").offset().top;
	roommenuheight = $("#roommenu").height();
	bubblewidth = $("#helpbubble").width();
	$("#helpbubble").css({"bottom":roommenuheight + 16+"px","left":"50%","margin-left":"-"+(parseFloat(bubblewidth / 2)) - (16)+"px"}).fadeIn(400);
}
function closehelpbubble() { 
	$("#helpbubble").fadeOut(200);
}
    
function selectcollagenew(num) { 
	existingphotos = new Array();
	$("#roomcontain .roomphotocontainer").each(function(p){
		if($(this).css("display") !== "none") { 
			if($(this).find(".roomphoto").attr("data-pic-key") !== "") { 
				 // $("#log").show().append(p+" "+$(this).find(".roomphoto").attr("data-pic-key")+"<br>");
				existingphotos.push($(this).find(".roomphoto").attr("data-photo-file")+"|"+$(this).find(".roomphoto").attr("data-pic-key")+"|"+$(this).find(".roomphoto").attr("data-pic-width")+"|"+$(this).find(".roomphoto").attr("data-pic-height")+"|"+$(this).find(".roomphoto").attr("data-date-id")+"|"+$(this).find(".roomphoto").attr("data-sub-id"));
			}
			$(this).remove();
		}
	});

	$("#roomcontain").attr("data-center-photo","1");
	$("#roomcontain .roomphotocontainer").hide();

	$("#roompreviewcontain-"+num+" .roomphotocontainer").each(function(i){
		timestamp = new Date().getTime() + i;
		html = $("#roomphotocode").html();
		html = html.replace(/0000/g,timestamp);
		$("#roomcontain").append(html);
		$("#roomphotocontainer-"+timestamp).attr("data-width",$(this).attr("data-width")).attr("data-height",$(this).attr("data-height")).attr("data-show-width",$(this).attr("data-show-width")).attr("data-show-height",$(this).attr("data-show-height")).attr("data-from-center",$(this).attr("data-from-center")).attr("data-from-base",$(this).attr("data-from-base")).attr("data-price",$(this).attr("data-price"));

		$("#roomphotocontainer-"+timestamp).attr("data-style-id",$(this).attr("data-style-id")).attr("data-frame-id",$(this).attr("data-frame-id")).attr("data-color-id",$(this).attr("data-color-id"));
		
		$("#roomphotocontainer-"+timestamp).attr("data-frame-width",$(this).attr("data-frame-width")).attr("data-frame-mat-size",$(this).attr("data-frame-mat-size")).attr("data-mat-color",$(this).attr("data-mat-color"));
		$("#roomphotocontainer-"+timestamp).attr("data-mat-print-width",$(this).attr("data-mat-print-width")).attr("data-mat-print-height",$(this).attr("data-mat-print-height")).attr("data-frame-corners",$(this).attr("data-frame-corners"));
		$("#roomphotocontainer-"+timestamp).attr("data-canvas-depth",$(this).attr("data-canvas-depth")).attr("data-canvas-id",$(this).attr("data-canvas-id")).attr("data-frame-file",$(this).attr("data-frame-file")).attr("data-print-product-id",$(this).attr("data-print-product-id")).attr("data-mat-color-id",$(this).attr("data-mat-color-id")).addClass("move").show();

		if($(this).attr("data-frame-id") > 0) { 
			corners = $(this).attr("data-frame-corners").split('-');
			$("#roomphotocontainer-"+timestamp).addClass("borderframe").css({"border-image":"url('"+$(this).attr("data-frame-file")+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-webkit-border-image":"url('"+$(this).attr("data-frame-file")+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round", "-o-border-image":"url('"+$(this).attr("data-frame-file")+"') "+corners[0]+"% "+corners[1]+"% "+ corners[2]+"% "+ corners[3]+"% round","border-width":framewidth* pixels+"px"}).removeClass("canvasshadow").addClass("frameshadow");
			$("#roomphoto-"+timestamp).addClass("photoshadow");

			if($(this).attr("data-frame-mat-size") > 0) { 
				$("#roomphotocontainer-"+timestamp).find(".roomphotomatte").addClass("matshadow");
			}
		}
		if(i <= existingphotos.length) { 
			if(existingphotos[i]) { 
				// $("#log").show().append(i+"<br>");
				addphoto = existingphotos[i].split("|");
				$("#roomphoto-"+timestamp).css({"background-image":"url('"+addphoto[0]+"')"}).attr("data-pic-key",addphoto[1]).attr("data-photo-file",addphoto[0]).attr("data-pic-width",addphoto[2]).attr("data-pic-height",addphoto[3]).attr("data-date-id",addphoto[4]).attr("data-sub-id",addphoto[5]);
			}
		}
		$( "#roomphotocontainer-"+timestamp).draggable({grid: [ 4, 4 ],
		  start: function() {
			$("#roomcontain").attr("data-center-photo","0");
		  }
		});
	});

	$("#roomcontain .roomphotocontainer").removeClass("selectedroomphoto");
	$("#photomenuactions").hide();
	$("#photomenuactionsclickphoto").show();
	$("#frameoptionsmenu").hide();
	$(".roomphotoinfo").hide();
	$("#roomcontain").attr("data-selected-photo","0");
	closesstuffwindow();
	selectingphoto();
	sizeroomphoto();

	setTimeout(function(){
	sizeroomphoto();
	},5);

}



function colloagepreview() { 
	$(".roompreviewcontain").each(function(i){
		pixels = $(this).find(".roompreviewbackground").width() / Math.abs($(this).attr("data-room-width")); // Pixel to inch ratio
		roompreviewbackgroundwidth = $(this).find(".roompreviewbackground").width();
		roompreviewbackgroundheight = $(this).find(".roompreviewbackground").height();
		datacenter = $(this).attr("data-center");
		database = $(this).attr("data-base")
		// Here we are going to go through each photo and set the size 
		$(this).find(".roomphotocontainer").each(function(i){




		photoheight = Math.abs($(this).attr("data-height")) * pixels;
		photowidth = Math.abs($(this).attr("data-width")) * pixels;
		$(this).find(".roomphoto").css({"width":photowidth+"px","height":photoheight+"px"});
		frame = (Math.abs($(this).css('border-left-width').replace("px", "")));
		matte = (Math.abs($(this).find(".roomphotomatte").css('padding-left').replace("px", "")));
					photoleft = (Math.abs(datacenter) * roompreviewbackgroundwidth) - (photowidth  / 2) - frame - matte;
					phototop = (Math.abs(database) * roompreviewbackgroundheight) - photoheight - (frame * 2) - (matte * 2);
		photoleft = photoleft + (parseFloat($(this).attr("data-from-center")) * pixels);
		phototop = phototop + (parseFloat($(this).attr("data-from-base")) * pixels);
		// $("#log").show().append(photoleft+" x "+phototop+" frame: "+frame+" mat: "+matte+" <br>");
		if($(this).attr("data-frame-id") > 0) { 
			framewidth = parseFloat($(this).attr("data-frame-width"));
			$(this).addClass("borderframe").css({"border-width":framewidth* pixels+"px"});
			if($(this).attr("data-frame-mat-size") > 0) { 
				matwidth = parseFloat($(this).attr("data-frame-mat-size"));
				$(this).find(".roomphotomatte").css({"padding":matwidth * pixels+"px"});
			}
		}
			$(this).css({"left":photoleft+"px","top":phototop+"px"});



			$(this).show();
		});
		if($(this).attr("data-center-photo") == "1") { 
			// $("#roomphotocontainer").css({"left":photoleft+"px","top":phototop+"px"});
		}
		roomphotopercentage = $(this).width() / Math.abs($(this).attr("data-room-photo-width"));
		$(this).css({"height":Math.abs($(this).attr("data-room-photo-height")) * roomphotopercentage+"px"});
	});
}

