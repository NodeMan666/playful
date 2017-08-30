$(document).ready(function(){
	$(".pageopen").unbind().click(function() { 
		fixbackground();
		// pageLoading();
		$("#buybackground").fadeIn(50);

		if($(this).attr("data-max-width") > 0) { 
			
			$("#photoprods").css({"top":50+"px", "max-width":$(this).attr("data-max-width")+"px","width":"100%","margin-left":"-"+$(this).attr("data-max-width") / 2+"px"});
		}
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=getpagecontent&did="+$(this).attr("page-id"), function(data) {	
			$("#photoprodsinner").html(data);
			//$("#buybackground").fadeIn(50, function() { 
				$("#photoprods").css({"top":50+"px"});
				$("#photoprods").fadeIn(100, function() { 
					$("#closebuyphoto").show();
					// pageDoneLoading();
					$('html').click(function() {
						 closebuyphoto();
					 });
					 $('#photoprods').click(function(event){
						 event.stopPropagation();
					 });
				});

		//	});
		});
	return false;
	});
});


/* For Booking Calendar */


function showbookingcalendar(id) { 
	$("#gallerysharebg").attr("data-window","booking");
	$("#accloading").show();


	$("#gallerysharebg").prop('onclick',null).off('click');
	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#booking").length == 0) {
			$("#gallerysharebg").after('<div id="booking" class="gallerypopup"></div>');
		}
		$.get(tempfolder+'/sy-inc/booking-calendar-options.php?book_service='+id, function(data) {
			$("#accloading").hide();
			$("#booking").html(data);
			$("#booking").css({"top":to+"px"}).fadeIn(200);
			$("#book_service").val(id);
			$("#gallerysharebg").bind('click', function() {closewindowpopup() });
			//getCalendar('06','2016','13');
		});
	});
}

function getCalendar(m,y,d) { 
 	$.get(tempfolder+"/sy-inc/booking-calendar-options.php?action=getcalendar&month="+m+"&year="+y+"&day="+d+"&book_service="+$("#book_service").val(), function(data) {
		$("#calendarselect").attr("data-year",y);
		$("#calendarselect").attr("data-month",m);
		$("#book_year").val(y);
		$("#book_month").val(m);
		$("#book_day").val(d);
		if((d > 0) && ($("#book_all_day").val() == "1" || $("#book_once_a_day").val() == "1")) { 
			picktime('1');
		} else { 
			$("#calendarselect").html(data).fadeIn(410);
			//$("#thecalendar").hide();

		}			
	});
}

function picktime(t) { 
	$("#calendarselect").slideUp(200, function() { 
		$.get(tempfolder+"/sy-inc/booking-calendar-options.php?action=getdatetime&book_service="+$("#book_service").val()+"&book_month="+$("#book_month").val()+"&book_year="+$("#book_year").val()+"&book_day="+$("#book_day").val()+"&book_time="+t, function(data) {
			$("#bookingdatetime").html(data).slideDown(200, function() { 
				$("#book_time").val(t);
				$('html, body').animate({
					scrollTop: $("#booking").offset().top
				}, 200, function() { 

					if($("#bookingoptions").attr("data-total-options") > 0) { 
						bookingoptions();
					} else { 
						bookinginfo();
					}
				});
			});
		});
	});

}

function bookingoptions() { 
	$("#bookingoptions").slideDown(200);
}

function bookingcheckoptions() { 
	var fields = {};
	var rf = false;
	var mes;
	// $("#changeemailresponse").hide();
	$(".bookoptionrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			if($("#"+this_id).hasClass("inputtabs")) { 
				$("#"+this_id).parent().addClass("inputError");
			} else { 
				$('#'+this_id).addClass('inputError');
			}
			rf = true;
		} else {
			if($("#"+this_id).hasClass("inputtabs")) { 
				$("#"+this_id).parent().removeClass("inputError");
			} else { 
				$('#'+this_id).removeClass('inputError');
			}
		}
	} );
	if(rf == true) { 
	return false;
	} else { 
		$('html, body').animate({
			scrollTop: $("#booking").offset().top
		}, 200, function() { 
			bookinginfo();
			});
	}


}


function bookinginfo() { 
	$("#bookingoptions").slideUp(200);
	if($("#book_require_deposit").val() == "1") { 
		if(Math.abs($("#book_require_deposit").attr("data-flat-rate")) > 0) { 
			$("#depositamount").html(priceFormat(Math.abs($("#book_require_deposit").attr("data-flat-rate"))));
		} else { 
			$("#depositamount").html(priceFormat(Math.abs($("#bookingprice").attr("data-total-price")) * (Math.abs($("#book_require_deposit").attr("data-amount")) / 100)));
		}
		$("#bookdeposit").slideDown(200);
	} else { 
		$("#bookinginfo").slideDown(200);
	}
}

function confirmbooking(classname) { 

	var fields = {};
	var rf = false;
	var mes;
	// $("#changeemailresponse").hide();
	$(".itemrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );
	if(rf == true) { 
		return false;
	} else { 

	$("#sendloading").show();
	$("#sendrequest").hide();
		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
				}
			} else { 
				fields[$this.attr('id')] = $this.val(); 
			}
		});
		$.post(tempfolder+'/sy-inc/booking-calendar-options.php', fields,	function (data) { 
			$("#bookinginfo").slideUp(200, function() { 
				$("#bookingcomplete").slideDown(200);
			});

		});
	}

}
function bookingdeposit(classname) { 

	var fields = {};
	var rf = false;
	var mes;
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] += ","+$this.val(); 
			}
		} else { 
			fields[$this.attr('id')] = $this.val(); 
		}
	});
	$.post(tempfolder+'/sy-inc/booking-calendar-options.php', fields,	function (data) { 
		window.location.href=tempfolder+"/index.php?view=checkout";

	});

}


function updatebookingprice() { 
	var addprice = 0;
	$('.bookoption').each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				addprice = addprice + Math.abs($this.attr("price"));
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				addprice = addprice + Math.abs($this.attr("price"));
			}
		} else if($this.hasClass('dropdown') ) { 
				addprice = addprice + Math.abs($('option:selected', $this).attr('price'));
		} else if($this.hasClass('inputtabs') ) { 
				addprice = addprice + Math.abs($this.attr("price"));
			//	alert($this.attr("price"));
		} else { 
			// fields[$this.attr('id')] = $this.val(); 
		}
	});

	newprice = Math.abs(addprice) + Math.abs($("#bookingprice").attr("data-default-price"));
	$("#bookingprice").attr("data-total-price",newprice)
	if(newprice > 0) { 
		$("#book_total").val(newprice);
		$("#bookingprice").show().html(priceFormat(newprice));
	} else { 
		$("#bookingprice").hide();
	}
}


/* From gallery exclusive */
function closewindowpopup() { 
	$("#"+$("#gallerysharebg").attr("data-window")).fadeOut(200, function() { 
		$("#"+$("#gallerysharebg").attr("data-window")).html("");
		$("#gallerysharebg").fadeOut(100);
	});
}


function showgallerylogin(view,sub,photo,form) { 
	if(photo !== "") { 
		window.location.href="#photo=thumbs";
		setTimeout(function(){
			showgalleryloginaction(view,sub,photo,form);
		}, 500 );
	} else { 
		showgalleryloginaction(view,sub,photo,form);
	}
}

function showgalleryloginaction(view,sub,photo,form) { 
	$("#gallerysharebg").attr("data-window","gallerylogin");
	$("#accloading").show();

	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#gallerylogin").length == 0) {
			$("#gallerysharebg").after('<div id="gallerylogin" class="gallerypopup"></div>');
		}
		$.get(tempfolder+'/sy-inc/gallery.menu.login.php?view='+view+'&sub='+sub+'&photo='+photo+'&form='+form, function(data) {
			$("#accloading").hide();
			$("#gallerylogin").html(data);
			if(view == "favorites") { 
				$("#favoritesmessage").show();
			} else { 
				$("#favoritesmessage").hide();
			}
			$("#gallerylogin").css({"top":to+"px"}).fadeIn(200);
		});
	});
}
/* end from gallery exclusive */


function showmobilemenu() { 
	$("#mobilemenulinks").slideToggle(200);
}


function freephoto(pic,date_id,sub_id,free_id) { 
	$("#gallerysharebg").attr("data-window","freedownload");
	$("#accloading").show();

	$("#gallerysharebg").fadeIn(100, function() { 
		if($("#vinfo").attr("view-photo-fixed") == "1") { 
			to = $("#ssheader").height() + $(window).scrollTop() + 24;
		} else { 
			if($(window).scrollTop() < 80) { 
				to = 80;
			} else { 
				to = $(window).scrollTop() + 24;
			}
		}
		if($("#freedownload").length == 0) {
			$("#ssheader").after('<div id="freedownload" class="gallerypopup"></div>');
		}
		
		if(isslideshow == true) { 
			stopSlideshow();
		}

		if(!pic) { 
			pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		}
		if(!date_id) { 
			date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
		}
		if(!free_id) { 
			free_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("fd");
		}
		if(!sub_id) { 
			sub_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("subid");
		}
		gsbgphoto = $("#gs-bgimage-id").val();

		$.get(tempfolder+"/sy-inc/store/store_photos_free.php?pid="+pic+"&date_id="+date_id+"&sub_id="+sub_id+"&free_id="+free_id+"&color_id="+$("#filter").attr("color_id")+"&gsbgphoto="+gsbgphoto, function(data) {
			$("#accloading").hide();
			$("#freedownload").html(data);
			 $("#freedownload").css({"top":to+"px"}).fadeIn(200);

		});
	});
}

function freedownloadall(date_id,sub_id,view) { 
	$("#gallerysharebg").attr("data-window","freedownloadall");
	$("#accloading").show();

	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop() + 24;
		}
		if($("#freedownloadall").length == 0) {
			$("#ssheader").after('<div id="freedownloadall" class="gallerypopup"></div>');
		}
		
		if(isslideshow == true) { 
			stopSlideshow();
		}

		gsbgphoto = $("#gs-bgimage-id").val();

		$.get(tempfolder+"/sy-inc/store/store_download_all_free_window.php?date_id="+date_id+"&sub_id="+sub_id+"&view="+view+"&gsbgphoto="+gsbgphoto, function(data) {
			$("#accloading").hide();
			$("#freedownloadall").html(data);
			 $("#freedownloadall").css({"top":to+"px"}).fadeIn(200);
			$(".gs-bgimage-id-free").val($("#gs-bgimage-id").val());

		});
	});
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function emailsignup(classname,requirename,formname,errorid,emailfieldclass,formcontainer,formsuccess) { 
	var fields = {};

	var rf = false;
	var mes;
	var stop;
	$("#"+errorid).html($("#"+formname).attr("data-required-missing")).slideUp(150);

	$("."+requirename).each(function(i){
		var this_id = this.id;
		if($(this).val() == "") { 
			$(this).addClass('inputError');
			rf = true;
		} else if($(this).val() == $('#'+this_id).attr("default")){
			$(this).addClass('inputError');
			rf = true;
		} else { 
			$(this).removeClass('inputError');
		}
	} );
	if($("."+emailfieldclass).val() !== $("."+emailfieldclass).attr("default")) { 
		if( !isValidEmailAddress($("."+emailfieldclass).val())) { 
			$("."+emailfieldclass).addClass('inputError');

			alert($("#"+formname).attr("data-invalid-email"));
			stop = true;
		} else { 
			$("."+emailfieldclass).removeClass('inputError');
		}
	}
	if(rf == true || stop == true) {
		if(rf == true) {
			 $("#"+errorid).html($("#"+formname).attr("data-required-missing")).slideDown();
		}
		return false;
	} else { 
		$(".submitdiv").hide();
		$(".submitsaving").show();

		$('.'+classname).each(function(){
			var $this = $(this);
			if($this.val() == $this.attr("default")){
				$this.val('');
			}
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
				}

			} else { 
				fields[$this.attr('id')] = $this.val(); 
			}
		});

			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

			data = $.trim(data);
			// alert(data);
			if(data == "good") { 
				$("#"+formcontainer).slideUp(200, function() { 
					$("#"+formsuccess).slideDown();
				});
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}



function closeemailsignup() { 
	$("#mlsignuppopup").fadeOut(200);
}

function showpopupemailjoin() { 
	$("#mlsignuppopup").fadeIn(200);
}



function disablerightclick() { 
	$('img').bind('contextmenu', function(e) {
		return false;
	}); 
}
function sharepage(type,url,appid,name,siteurl,pub,pic,did) { 
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=logshare&did="+did+"&where="+type, function(data) {	});

	// alert(data);
	
		descr = encodeURIComponent($("#sharetext").attr("content"));

		if(url.indexOf("?")>0) { 
			sep = "&";
		} else {
			sep = "?";
		}
		if(pub == "0") { 
			sep = "#";
			imgvar = "photo";
		} else { 
			imgvar = "image";
		}

		if(type == "email") { 
			PopupCenter("mailto:?subject="+name+"&body="+url+"%0D%0A"+descr, type, 640, 480)
		}

		if(type == "facebook") { 
			PopupCenter("https://www.facebook.com/dialog/share?app_id="+appid+"&href="+url+"&picture="+pic+"&name="+name+"&description="+descr+"&display=popup&close=true", type, 800, 480)
		}

		if(type == "pinterest") { 
			PopupCenter("//pinterest.com/pin/create/button/?url="+url+"&media="+pic+"&description="+name, type, 640, 480)
		}

		if(type == "twitter") { 
			PopupCenter("https://twitter.com/intent/tweet?text="+name+"&url="+url, type, 640, 480)
		}
}

function placeonphoto(onphotoheightperc,onphotominwidth,onphotoperrow,onphotomargin,onphotonewwidth,onphotonewheight) { 
	// onphotoperrow = 4;

	$("#listing-onphoto .preview").css({"width":onphotonewwidth+"px", "height":onphotonewheight+"px"});
	$(".onphotophoto").each(function(i){
		var this_id = this.id;
		var this_src =  $("#"+this_id).attr("src"); 
		var divwidth = onphotonewwidth;
		var divheight =  onphotonewheight;
			pic_width = Math.abs($("#"+this_id).attr("width"));
			pic_height = Math.abs($("#"+this_id).attr("height"));
			newwidthpercent = Math.abs(divwidth) / Math.abs(pic_width);
			newheightpercent = Math.abs(divheight) / Math.abs(pic_height);
			if(newwidthpercent > newheightpercent) { 
				newheight =  pic_height * newwidthpercent;
				newwidth = divwidth;
					if(newheight < divheight) { 
						newheight = divheight;
						newwidth = pic_width * (divheight  / pic_height);
					}
				margintop = (newheight - divheight) / 2;
				$("#"+this_id).css({
					"height":newheight+"px",
					"width":newwidth+"px",
					"margin-top":"-"+margintop+"px"
				});

			} else { 
				newwidth =  pic_width * newheightpercent;
				newheight = divheight;
				if(pic_height < divheight) { 
					newheight = divheight;
					newwidth = pic_width * (divheight  / pic_height);
				}
				marginleft = (newwidth - divwidth) / 2;
				$("#"+this_id).css({
					"width":newwidth+"px",
					"height":newheight+"px",
					"margin-left":"-"+marginleft+"px"
				});
			}
			// $("#log").show().html(onphotoperrow+" - "+marginleft+"... "+divwidth+" x "+divheight);

			$("#"+this_id).fadeIn('fast', function() { });
	  });
}


function placethumblisting(tlnewwidth) { 
	$("#listing-thumbnail .preview").css({"width":tlnewwidth+"px"});
}
function placestyledthumbs(tlnewwidth) { 
	$("#displayThumbnailPage .styledthumbs").css({"width":tlnewwidth+"px"});
	resizestyledthumbphotoheight(tlnewwidth);
}
function placestackedthumbs(tlnewwidth) { 
	$("#displayThumbnailPage .stackedthumbs").css({"width":tlnewwidth+"px"});
	resizestackedthumbphotoheight();
}

function placestackedlisting(tlnewwidth) { 
	$("#listing-stacked .preview").css({"width":tlnewwidth+"px"});
	resizephotoheight();
}


function resizephotoheight() {
  $("#listing-stacked .preview .thumbnail").each(function () {
	var originalWidth = $(this).attr('width');
	var originalHeight = $(this).attr('height');
	var ratio = originalWidth/originalHeight;
	var width = $(this).width();
	var height = width/ratio;
	$(this).height(height);
  });
}

function resizestackedthumbphotoheight() {
  $("#displayThumbnailPage .stackedthumbs .thumbnail").each(function () {
	var originalWidth = $(this).attr('width');
	var originalHeight = $(this).attr('height');
	var ratio = originalWidth/originalHeight;
	var width = $(this).width();
	var height = width/ratio;
	$(this).height(height);
  });
}

function resizestyledthumbphotoheight(tlnewwidth) {
  $("#displayThumbnailPage .styledthumbs .thumbnail").each(function () {
	var originalWidth = $(this).attr('width');
	var originalHeight = $(this).attr('height');
	var ratio = originalWidth/originalHeight;
	// $("#log").show().append($(this).parent().parent().width()+" x "+$(this).parent().parent().height()+" | ");
		if($(this).height() > $(this).parent().parent().height()) { 
			$(this).css({"height":$(this).parent().parent().height()+"px","width":"auto"});
		}
		if($(this).width() > $(this).parent().parent().width()) { 
			$(this).css({"width":$(this).parent().parent().width()+"px","height":"auto"});
		}
  });
}

function jthumbs() { 
	var row_width = 0;
	var r = 1;
	var br = " ";
	var row = new Array();
	var rowwidth = new Array();
	var tpics = 1;
	var rowheight = jrowheight;
	var container_width = $("#thumbsj").width() - 1;
	$(".jthumb").each(function(i){
		thiswidth = Math.abs($(this).attr("aw"));
		row_width = row_width + thiswidth;
	$(this).removeClass (function (index, css) {
		return (css.match (/\jrow\S+/g) || []).join(' ');
	});
		if(row_width < (container_width + $("#thumbsj").width() * .1)) { 
			$(this).addClass("jrow"+r);
			var br = " ";
			row[r] = tpics++;
			rowwidth[r] = row_width;
		} else { 
			r++;
			$(this).addClass("jrow"+r);
			var br = "<br>";
			row_width = thiswidth;
			tpics = 1;
			row[r] = tpics++;
			rowwidth[r] = row_width;
		}
		var br = "";
	});
	
	var a = 1;
	var twidth = 0;
	var totalpics = 0;
	var thisrowwidth = 0;
	var cmargin = jcmargin;
	while(a<=r - 1) { 
		$(".jrow"+a).each(function(i){
			thiswidth = Math.abs($(this).attr("aw"));
			twidth = twidth + thiswidth;
			border = $(this).parent().css("border-left-width").replace("px", "");
			border = Math.abs(border) * 2;
			thisrowwidth += ((container_width * ((thiswidth)  / rowwidth[a])) - (cmargin * 2)) + (cmargin * 2);
			$(this).parent().css({"width":((container_width * ((thiswidth)  / rowwidth[a])) - (cmargin * 2))-border,"height":rowheight+"px"});

			if(thiswidth < $(this).parent().width()) { 
				$(this).css({"width":$(this).parent().width()+"px","height":"auto"});
			}
			if($(this).height() < $(this).parent().height())  { 
			//	$(this).css({"height":$(this).parent().height()+"px","width":"auto"});
			}

			totalpics++;
		});
		thisrowwidth = 0;
		a++;
		twidth = 0;
		totalpics = 0;
	}
}



/* ############################# COMMENTS  #################################### */


function showcomments() { 
	//fixbackground();
	$("#commentscontainer").removeClass('commentsshowpage').addClass("commentsshowwindow");

	$("#commentsbackground").fadeIn(100, function() { 
		$("#commentscontainer").css({"top":$(window).scrollTop()+"px"});
		$("#commentscontainer").fadeIn(200, function() { 
			$("#closebuyphoto").show();
		});
	});
}
function getcommenttotal(date_id) {
	$.get("/sy-inc/sy-count-comments.php?date_id="+date_id, function(data) {
		$("#commenttotal-"+date_id).html(data);
	});
}


function closecomments() { 
	// unfixbackground();
	$("#commentscontainer").removeClass('commentsshowpage').removeClass("commentsshowwindow");
	$("#commentsbackground").fadeOut(50);
}



function getcommentpostime(){
	setInterval("getcommentpos()",500);
}

function getcommentpos() { 
	var height = $(window).height();
	var scrollTop = $(window).scrollTop();
	var compos = $("#commentspos").position().top;
	if((compos - scrollTop) < height) { 
		if (!$("#commentscontainer").hasClass("commentsshowwindow")) {
			$("#commentscontainer").show().removeClass('commentsshowwindow').addClass('commentsshowpage');
		}
	}
}


function makecomment(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	// $("#changeemailresponse").hide();
	$(".comrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );
	if(rf == true) { 
		return false;
	} else { 
		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
				}
			} else { 
				fields[$this.attr('name')] = $this.val(); 
			}
		});
		$.post(tempfolder+'/sy-inc/sy-make-comment.php', fields,	function (data) { 
			data = $.trim(data);
			 $("#commentapproved").slideUp(200);
			 $("#commenterror").slideUp(200);
			 $("#commentpending").slideUp(200);

		//	alert(data);
			if(data == "good") { 
				$("#commentsform").slideUp(200, function() { 
					 $("#commentapproved").slideDown(200, function() { 
					 $("#listStandardCommentsFull").append('<div class="showComment"><div class="pc"><h3 style="display: inline;">'+$("#d_n").val()+'</h3> Just Now</div><div class="pc">'+$("#d_m").val()+'</div></div>');
					 });
				});

			} else if(data == "pending") {  
				$("#commentsform").slideUp(200, function() { 
					 $("#commentpending").slideDown(200, function() { 
					// $("#listStandardCommentsFull").append('<div class="showComment"><div class="pc"><h3>'+$("#d_n").val()+'</h3></div><div class="pc">Just Now</div><div class="pc">'+$("#d_m").val()+'</div></div>');
					 });
				});

			} else { 
				 $("#commenterror").html(data);
				 $("#commenterror").slideDown(200);
			} 
		});
	return false;
	}
}



/* ############################# onchange #################################### */

/**
* jQuery.observeHashChange (Version: 1.0)
*
* http://finnlabs.github.com/jquery.observehashchange/
*
* Copyright (c) 2009, Gregor Schmidt, Finn GmbH
*
* Permission is hereby granted, free of charge, to any person obtaining a
* copy of this software and associated documentation files (the "Software"),
* to deal in the Software without restriction, including without limitation
* the rights to use, copy, modify, merge, publish, distribute, sublicense,
* and/or sell copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
* DEALINGS IN THE SOFTWARE.
**/
(function($) {
  $.fn.hashchange = function(fn) {
    $(window).bind("jQuery.hashchange", fn);
    return this;
  };

  $.observeHashChange = function(options) {
    var opts = $.extend({}, $.observeHashChange.defaults, options);
    if (isHashChangeEventSupported()) {
      nativeVersion();
    }
    else {
      setIntervalVersion(opts);
    }
  };

  var locationHash = null;
  var functionStore = null;
  var interval = 0;

  $.observeHashChange.defaults = {
    interval : 500
  };

  function isHashChangeEventSupported() {
    return "onhashchange" in window;
  }

  function nativeVersion() {
    locationHash = document.location.hash;
    window.onhashchange = onhashchangeHandler;
  }

  function onhashchangeHandler(e, data) {
    var oldHash = locationHash;
    locationHash = document.location.hash;
    $(window).trigger("jQuery.hashchange", {before: oldHash, after: locationHash});
  }

  function setIntervalVersion(opts) {
    if (locationHash == null) {
      locationHash = document.location.hash;
    }
    if (functionStore != null) {
      clearInterval(functionStore);
    }
    if (interval != opts.interval) {
      functionStore = setInterval(checkLocationHash, opts.interval);
      interval = opts.interval;
    }
  }

  function checkLocationHash() {
    if (locationHash != document.location.hash) {
      var oldHash = locationHash;
      locationHash = document.location.hash;
      $(window).trigger("jQuery.hashchange", {before: oldHash, after: locationHash});
    }
  }

  $.observeHashChange();
})(jQuery);

/* ############################# jcolor #################################### */

/*
 Color animation jQuery-plugin
 http://www.bitstorm.org/jquery/color-animation/
 Copyright 2011 Edwin Martin <edwin@bitstorm.org>
 Released under the MIT and GPL licenses.
*/
(function(d){function i(){var b=d("script:first"),a=b.css("color"),c=false;if(/^rgba/.test(a))c=true;else try{c=a!=b.css("color","rgba(0, 0, 0, 0.5)").css("color");b.css("color",a)}catch(e){}return c}function g(b,a,c){var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(b[0]+c*(a[0]-b[0]),10)+","+parseInt(b[1]+c*(a[1]-b[1]),10)+","+parseInt(b[2]+c*(a[2]-b[2]),10);if(d.support.rgba)e+=","+(b&&a?parseFloat(b[3]+c*(a[3]-b[3])):1);e+=")";return e}function f(b){var a,c;if(a=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(b))c=
[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16),1];else if(a=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(b))c=[parseInt(a[1],16)*17,parseInt(a[2],16)*17,parseInt(a[3],16)*17,1];else if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))c=[parseInt(a[1]),parseInt(a[2]),parseInt(a[3]),1];else if(a=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(b))c=[parseInt(a[1],10),parseInt(a[2],10),parseInt(a[3],10),parseFloat(a[4])];return c}
d.extend(true,d,{support:{rgba:i()}});var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];d.each(h,function(b,a){d.fx.step[a]=function(c){if(!c.init){c.a=f(d(c.elem).css(a));c.end=f(c.end);c.init=true}c.elem.style[a]=g(c.a,c.end,c.pos)}});d.fx.step.borderColor=function(b){if(!b.init)b.end=f(b.end);var a=h.slice(2,6);d.each(a,function(c,e){b.init||(b[e]={a:f(d(b.elem).css(e))});b.elem.style[e]=g(b[e].a,b.end,b.pos)});b.init=true}})(jQuery);

/* ############################# /jcolor #################################### */



/* ############################  wookmark  ############################# */
/*!
jQuery wookmark plugin
@name jquery.wookmark.js
@author Christoph Ono (chri@sto.ph or @gbks)
@author Sebastian Helzle (sebastian@helzle.net or @sebobo)
@version 1.4.8
@date 07/08/2014
@category jQuery plugin
@copyright (c) 2009-2014 Christoph Ono (www.wookmark.com)
@license Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
*/
(function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)})(function(t){function i(t){n(function(){var i,e;for(i=0;t.length>i;i++)e=t[i],e.obj.css(e.css)})}function e(i){return t.trim(i).toLowerCase()}var s,h,o;o=function(t,i){return function(){return t.apply(i,arguments)}},h={align:"center",autoResize:!1,comparator:null,container:t("body"),direction:void 0,ignoreInactiveItems:!0,itemWidth:0,fillEmptySpace:!1,flexibleWidth:0,offset:2,outerOffset:0,onLayoutChanged:void 0,possibleFilters:[],resizeDelay:50,verticalOffset:void 0};var n=window.requestAnimationFrame||function(t){t()},r=t(window);s=function(){function s(i,e){this.handler=i,this.columns=this.containerWidth=this.resizeTimer=null,this.activeItemCount=0,this.itemHeightsDirty=!0,this.placeholders=[],t.extend(!0,this,h,e),this.verticalOffset=this.verticalOffset||this.offset,this.update=o(this.update,this),this.onResize=o(this.onResize,this),this.onRefresh=o(this.onRefresh,this),this.getItemWidth=o(this.getItemWidth,this),this.layout=o(this.layout,this),this.layoutFull=o(this.layoutFull,this),this.layoutColumns=o(this.layoutColumns,this),this.filter=o(this.filter,this),this.clear=o(this.clear,this),this.getActiveItems=o(this.getActiveItems,this),this.refreshPlaceholders=o(this.refreshPlaceholders,this),this.sortElements=o(this.sortElements,this),this.updateFilterClasses=o(this.updateFilterClasses,this),this.updateFilterClasses(),this.autoResize&&r.bind("resize.wookmark",this.onResize),this.container.bind("refreshWookmark",this.onRefresh)}return s.prototype.updateFilterClasses=function(){for(var t,i,s,h,o=0,n=0,r=0,a={},l=this.possibleFilters;this.handler.length>o;o++)if(i=this.handler.eq(o),t=i.data("filterClass"),"object"==typeof t&&t.length>0)for(n=0;t.length>n;n++)s=e(t[n]),a[s]===void 0&&(a[s]=[]),a[s].push(i[0]);for(;l.length>r;r++)h=e(l[r]),h in a||(a[h]=[]);this.filterClasses=a},s.prototype.update=function(i){this.itemHeightsDirty=!0,t.extend(!0,this,i)},s.prototype.onResize=function(){clearTimeout(this.resizeTimer),this.itemHeightsDirty=0!==this.flexibleWidth,this.resizeTimer=setTimeout(this.layout,this.resizeDelay)},s.prototype.onRefresh=function(){this.itemHeightsDirty=!0,this.layout()},s.prototype.filter=function(i,s,h){var o,n,r,a,l,f=[],u=t();if(i=i||[],s=s||"or",h=h||!1,i.length){for(n=0;i.length>n;n++)l=e(i[n]),l in this.filterClasses&&f.push(this.filterClasses[l]);if(o=f.length,"or"==s||1==o)for(n=0;o>n;n++)u=u.add(f[n]);else if("and"==s){var c,d,m,p=f[0],g=!0;for(n=1;o>n;n++)f[n].length<p.length&&(p=f[n]);for(p=p||[],n=0;p.length>n;n++){for(d=p[n],g=!0,r=0;f.length>r&&g;r++)if(m=f[r],p!=m){for(a=0,c=!1;m.length>a&&!c;a++)c=m[a]==d;g&=c}g&&u.push(p[n])}}h||this.handler.not(u).addClass("inactive")}else u=this.handler;return h||(u.removeClass("inactive"),this.columns=null,this.layout()),u},s.prototype.refreshPlaceholders=function(i,e){for(var s,h,o,n,r,a,l=this.placeholders.length,f=this.columns.length,u=this.container.innerHeight();f>l;l++)s=t('<div class="wookmark-placeholder"/>').appendTo(this.container),this.placeholders.push(s);for(a=this.offset+2*parseInt(this.placeholders[0].css("borderLeftWidth"),10),l=0;this.placeholders.length>l;l++)if(s=this.placeholders[l],o=this.columns[l],l>=f||!o[o.length-1])s.css("display","none");else{if(h=o[o.length-1],!h)continue;r=h.data("wookmark-top")+h.data("wookmark-height")+this.verticalOffset,n=u-r-a,s.css({position:"absolute",display:n>0?"block":"none",left:l*i+e,top:r,width:i-a,height:n})}},s.prototype.getActiveItems=function(){return this.ignoreInactiveItems?this.handler.not(".inactive"):this.handler},s.prototype.getItemWidth=function(){var t=this.itemWidth,i=this.container.width()-2*this.outerOffset,e=this.handler.eq(0),s=this.flexibleWidth;if(void 0===this.itemWidth||0===this.itemWidth&&!this.flexibleWidth?t=e.outerWidth():"string"==typeof this.itemWidth&&this.itemWidth.indexOf("%")>=0&&(t=parseFloat(this.itemWidth)/100*i),s){"string"==typeof s&&s.indexOf("%")>=0&&(s=parseFloat(s)/100*i);var h=i+this.offset,o=~~(.5+h/(s+this.offset)),n=~~(h/(t+this.offset)),r=Math.max(o,n),a=Math.min(s,~~((i-(r-1)*this.offset)/r));t=Math.max(t,a),this.handler.css("width",t)}return t},s.prototype.layout=function(t){if(this.container.is(":visible")){var i,e=this.getItemWidth()+this.offset,s=this.container.width(),h=s-2*this.outerOffset,o=~~((h+this.offset)/e),n=0,r=0,a=0,l=this.getActiveItems(),f=l.length;if(this.itemHeightsDirty||!this.container.data("itemHeightsInitialized")){for(;f>a;a++)i=l.eq(a),i.data("wookmark-height",i.outerHeight());this.itemHeightsDirty=!1,this.container.data("itemHeightsInitialized",!0)}o=Math.max(1,Math.min(o,f)),n=this.outerOffset,"center"==this.align&&(n+=~~(.5+(h-(o*e-this.offset))>>1)),this.direction=this.direction||("right"==this.align?"right":"left"),r=t||null===this.columns||this.columns.length!=o||this.activeItemCount!=f?this.layoutFull(e,o,n):this.layoutColumns(e,n),this.activeItemCount=f,this.container.css("height",r),this.fillEmptySpace&&this.refreshPlaceholders(e,n),void 0!==this.onLayoutChanged&&"function"==typeof this.onLayoutChanged&&this.onLayoutChanged()}},s.prototype.sortElements=function(t){return"function"==typeof this.comparator?t.sort(this.comparator):t},s.prototype.layoutFull=function(e,s,h){var o,n,r=0,a=0,l=t.makeArray(this.getActiveItems()),f=l.length,u=null,c=null,d=[],m=[],p="left"==this.align?!0:!1;for(this.columns=[],l=this.sortElements(l);s>d.length;)d.push(this.outerOffset),this.columns.push([]);for(;f>r;r++){for(o=t(l[r]),u=d[0],c=0,a=0;s>a;a++)u>d[a]&&(u=d[a],c=a);o.data("wookmark-top",u),n=h,(c>0||!p)&&(n+=c*e),(m[r]={obj:o,css:{position:"absolute",top:u}}).css[this.direction]=n,d[c]+=o.data("wookmark-height")+this.verticalOffset,this.columns[c].push(o)}return i(m),Math.max.apply(Math,d)},s.prototype.layoutColumns=function(t,e){for(var s,h,o,n,r=[],a=[],l=0,f=0,u=0;this.columns.length>l;l++){for(r.push(this.outerOffset),h=this.columns[l],n=l*t+e,s=r[l],f=0;h.length>f;f++,u++)o=h[f].data("wookmark-top",s),(a[u]={obj:o,css:{top:s}}).css[this.direction]=n,s+=o.data("wookmark-height")+this.verticalOffset;r[l]=s}return i(a),Math.max.apply(Math,r)},s.prototype.clear=function(){clearTimeout(this.resizeTimer),r.unbind("resize.wookmark",this.onResize),this.container.unbind("refreshWookmark",this.onRefresh),this.handler.wookmarkInstance=null},s}(),t.fn.wookmark=function(t){return this.wookmarkInstance?this.wookmarkInstance.update(t||{}):this.wookmarkInstance=new s(this,t||{}),this.wookmarkInstance.layout(!0),this.show()}});

/* ############################  /wookmark  ############################# */





var timeout;
var mainmenus=new Array("areports");
var mainmenuso=new Array("areportso");
var n;
function selectFAQ(id) { 
$(".viewfaqcontainer").each(function(i){
	var this_id = this.id;
	if("faq-"+id != this_id) { 
		$("#"+this_id).slideUp(300);
	}
})
	$("#faq-"+id).slideToggle(300);


}
function getsplash() { 
	// fixbackground();
	$("#splashbackground").show(); 
	$("#splashcontainer").css({"top":$(window).scrollTop()+50+"px"});
	$("#splashinner").html($("#splashtext").html());
	$("#splashcontainer").fadeIn(400);
}		
function closesplash() { 
	// unfixbackground();
	$("#splashcontainer").fadeOut(200, function() { 
		$("#splashinner").html("");
		$("#splashbackground").fadeOut(200);
	});

}

function adjustheader() { 
	if($("#headerAndMenu").hasClass("adjustheight")) { 
		if($("body").width() <= 800) { 
			$("#headerContainer").removeClass("headerheight").addClass("headerheightmobile");
			if($("#headerAndMenu").hasClass("hlr")) { 
				$("#headerAndMenu").removeClass("headerheight").addClass("headerheightmobile");
			}
		} else { 
			$("#headerContainer").removeClass("headerheightmobile").addClass("headerheight");
			if($("#headerAndMenu").hasClass("hlr")) { 
				$("#headerAndMenu").removeClass("headerheightmobile").addClass("headerheight");
			}
		}
	}
}

function adjustsite() { 
	adjustheader();
	placemenus();
	nofloatsmall();
	hidesmall();
	removesidebar();
	productplacement();
	$("#ssClose").css({"top":$("#ssheader").height()+"px"});
}


function productplacement() { 
	if($("body").width() <= lppw) { 
		checkforpackages();
		$("#vinfo").attr("prodplace","0");
		if($("#vinfo").attr("viewing_prods") == "1") { 
			$("#photoproductsnexttophoto").hide();
			$("#photoproductsnexttophotobg").hide();
			$("#slideshow").removeClass("photowithproducts");
			$("#ssheader").removeClass("ssheaderwithproducts");
			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pl")) > 0) { 
				$("#buy-photo").show();

				if($("#vinfo").attr("has_package")  > 0 || $("#vinfo").attr("viewing-store-photo-prod") > 0) { 
			if($("body").width() <= lppw || itablet == true) { 
					headerheight = Math.abs($("#ssheader").height()) + 12;
					$("#photopackagetab").css({"top":headerheight+"px"});
					// $("#photopackagetab").html("Please rotate your device to add photos to your package");
						$("#photopackagetab").show();
					}
				}
			} else { 
				$("#buy-photo").hide();
			}

			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pkgs")) > 0) { 
				$("#buy-packages").show();
			} else { 
				$("#buy-packages").hide();
			}
		}

		$("#ssPrevPhoto").css({"position":"fixed","top":"45%", "z-index":"30", "left":"0px"});
		$("#ssNextPhoto").css({"position":"fixed",   "top":"45%", "z-index":"30", "left":$("#slideshow").width() - $("#ssNextPhoto").width() - 16+"px"});
		$("#controls").css({"position":"fixed",   "top":"45%", "z-index":"30"});
					if($("#photoproductsnexttophoto").css('display') == "none") { 
						sscloseright = 0;
					} else { 
						sscloseright = $("#photoproductsnexttophoto").css('width');
					}
					$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
	
	} else { 
		$("#photopackagetab").hide();

		$("#vinfo").attr("prodplace",$("#vinfo").attr("prodplacedefault"));
		if($("#vinfo").attr("viewing_prods") == "1") { 
			$("#photoproductsnexttophoto").show();
			$("#photoproductsnexttophotobg").show();
			$("#slideshow").addClass("photowithproducts");
			$("#ssheader").addClass("ssheaderwithproducts");
			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pl")) > 0 && $("#vinfo").attr("prodplace")=="0") { 
				$("#buy-photo").show();
			} else { 
				$("#buy-photo").hide();
				$("#photopackagetab").hide();
				$("#singlephotopackagetab").hide();
			}

			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pkgs")) > 0) { 
				$("#buy-packages").show();
			} else { 
				$("#buy-packages").hide();
			}
		 if($("#slideshow").attr("fullscreen") == 1) { 
			$("#ssPrevPhoto").css({"position":"fixed","top":"45%", "z-index":"30", "left":"0px"});
			$("#ssNextPhoto").css({"position":"fixed",   "top":"45%", "z-index":"30", "left":$("#slideshow").width() - $("#ssNextPhoto").width() - 16+"px"});
			$("#controls").css({"position":"fixed",   "top":"45%", "z-index":"30"});
					if($("#photoproductsnexttophoto").css('display') == "none") { 
						sscloseright = 0;
					} else { 
						sscloseright = $("#photoproductsnexttophoto").css('width');
					}
					$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
		 } 
		} else { 
			$("#ssPrevPhoto").css({"position":"fixed","top":"45%", "z-index":"30", "left":"0px"});
			$("#ssNextPhoto").css({"position":"fixed",   "top":"45%", "z-index":"30", "left":$("#slideshow").width() - $("#ssNextPhoto").width() - 16+"px"});
			$("#controls").css({"position":"fixed",   "top":"45%", "z-index":"30"});
					if($("#photoproductsnexttophoto").css('display') == "none") { 
						sscloseright = 0;
					} else { 
						sscloseright = $("#photoproductsnexttophoto").css('width');
					}
					$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
		}
	}


}

function placemenus() { 


}


function nofloatsmall() { 

}

function removesidebar() { 
	if($("body").width() <= 800) { 
		$("#sideMenuContainer").addClass("hidesidebar");
		$("#pageContentContainer").addClass("hidesidebarmain");
	} else { 
		$("#sideMenuContainer").removeClass("hidesidebar");
		$("#pageContentContainer").removeClass("hidesidebarmain");
	}
}


function hidesmall() { 

}
function showsmall() { 

}


function gototop() { 
   $('html').animate({scrollTop:0}, 'slow'); 
    $('body').animate({scrollTop:0}, 'slow'); 
}

function botdetect() { 
	if($("#from_message_to").val()!=="") { 
		//alert("Sorry, you are a bot");
		return false;
	}
}
function getmenuPosition(s,t){
	setInterval( function() { menuPosition(s,t); }, 100 );
}

 function menuPosition(s,t) { 
	var p = $("#"+t).offset().top;
	var w = $(window).scrollTop();
	var h = $("#"+s).height();
	if (s == "headerAndMenu") {
		w = w + $("#shopmenucontainer").height();
	}
	var tmh = $("#shopmenucontainer").height();
	if($("body").width() > 800) { 
		$("#"+s).css({'position': 'fixed', 'width':'100%'});
		if (s == "headerAndMenu") {
			if(tmh > 0) { 
				$("#"+s).css({"top":tmh+"px"});
			} else { 
				$("#"+s).css({"top":"0px"});
			}
		} else { 
			$("#"+s).css('top', '0');
		}
		$("#"+t).height(h);
	} else {
		$("#"+s).css('position', 'relative');
		$("#"+s).css('top', 'auto');		
		$("#"+t).height(0);
	}
 }

function accesspage() { 
	if($("#pagepass").val() !== "") { 
		$("#accesspagebad").slideUp(50);
		$.get(tempfolder+"/sy-inc/access_page.php?pagepass="+encodeURIComponent($("#pagepass").val())+"", function(data) {
			if(data == "bad") { 
				$("#accesspagebad").slideDown(200);
			} else { 
				$("#findsubmit").val($("#accesspageform").attr("data-one-moment"));
				$("#accesspagebad").slideUp(50);
				$("body").css("overflow: hidden");
				window.location.href =data;
			}
		});
	}
}



function getCaption(id) { 
	p = $("#"+id).position();
	add = ($("#"+id).outerWidth() - $("#"+id).width()) / 2;
	var border = $("#"+id).css("border-left-width");
	var padding = $("#"+id).css('padding-top');
	var margin = $("#"+id).css('margin-top');
	border = border.replace("px", "");
	padding = padding.replace("px", "");
	bottom = Math.abs(border) + Math.abs(padding) + 4;
	var fleft = p.left+add;
	$("#log").html(p.left+" + "+add+" bottom: "+bottom);
	$("#"+id).parent().children().children(".photocaptioncontainer").css({"width":$("#"+id).width()+"px","margin":"auto", "left":fleft+"px", "bottom":bottom+"px"});
	$("#"+id).parent().children().children(".photocaptioncontainer").fadeIn(200);

}


function photopreview(theclass){
	$(theclass).hoverIntent(
		function() {
		if($(this).attr("mpic_id")) { 
			var this_pic = $(this).attr("mpic_id");
			var this_src = $(this).attr("pic_pic");
			var th_src = $(this).attr("src");
			zindex = parseInt($(this).css('z-index'))-1;
			$("#photo-preview").css('z-index', zindex);
			$("#photo-preview").stop(true,true).fadeIn(200);
			$("#loaded").remove();
			$("#photo-preview-photo").html('');
			$("#photo-preview-photo").css({"background-image":"url('"+th_src+"')", "background-size":"100%","width":""+$(this).attr("hw")+"px", "height": ""+$(this).attr("hh")+"px"}); 
			$("#photo-preview-title").html("<h3>"+$(this).attr("ptitle")+"</h3>");
			$("#photo-preview-caption").html($(this).attr("pcaption"));
			$("#photo-preview-filename").html($(this).attr("pic_org"));
			$("#photo-preview-keywords").html($(this).attr("keywords"));
			var tposition =  Math.abs($(this).offset().top) - Math.abs($(this).height()) - Math.abs($("#photo-preview").height() / 2) + 16;
			if(tposition < $(window).scrollTop()) { 
				tposition = $(window).scrollTop();
			}
			lposition =  Math.abs($(this).offset().left) -  Math.abs($("#photo-preview").width() + 8);
			if(lposition < 0) { 
				lposition = Math.abs($(this).offset().left) +  $(this).width() + 8;
			}

			$("#photo-preview").stop(true,true).animate(
				{opacity: 1,left:lposition, top:tposition }, 
				{ duration: 0,	complete: function() {
					$("#photo-preview").removeClass('photo-previewloading');
					$("#photo-preview-photo").html('<img src="'+this_src+'" id="loaded-'+this_pic+'" style="display: none;">');
					$("#loaded-"+this_pic).load(function() {
						$("#loaded-"+this_pic).stop(true,true).fadeIn(0);
						$("#photo-preview-photo").css({"background-image":"none"}); 
					 });
				}
		  });
		}
	},
	function() {
		$("#photo-preview").stop(true,true).fadeOut(0);
		$("#loaded").stop(true,true).fadeOut(100);
		$("#photo-preview").clearQueue();
		$("#photo-preview").removeClass('photo-previewloading');
		}
	);
}

function mytips(theclass, cssclass){

	$(theclass).on({

		mouseover: function() {
		var currentTime = new Date();
		var hours = currentTime.getHours();
		var minutes = currentTime.getMinutes();
		var seconds= currentTime.getSeconds();
		var milliseconds= currentTime.getMilliseconds();
		var add_id = hours+''+minutes+''+seconds+''+milliseconds;;
		var new_id = cssclass+''+add_id
		var lposition = $(this).offset().left - $(window).scrollLeft();
		var tposition = $(this).offset().top- $(window).scrollTop();
		tposition = tposition - ($(this).height() / 2);
		lposition = lposition + ($(this).width() / 2);
		//alert(lposition);
		if((!document.getElementById(new_id))&&(this.title!=='')){
			this.tip = this.title;
			$(this).append(
				'<div class="'+cssclass+'" id="'+cssclass+''+add_id+'">'
					+'<div>'
						+this.tip
					+'</div>'
				+'</div>'
			);
			this.width = $(this).width();
			this.title = "";
			old_title = this.tip;
//			$("#"+new_id).fadeIn(175);
			$("#"+new_id).fadeIn(200);
			tposition = tposition - $("#"+new_id).height();
			lposition = lposition - ($("#"+new_id).width() / 2);
			if(lposition < 0) { 
				lposition = 0;
			}
//			alert(lposition+" X "+tposition+" "+old_title);

			$("#"+new_id).css({left:lposition, top:tposition});
			}
		},
		mouseout: function() {
			$(this).find('div').hide();
			$(this).children().empty();
				this.title = old_title;
			}
		});
}
function showLoadingMore() { 
		$("#loadingMore").fadeIn(300);
}
function showLoadingMorePages() { 
		$("#loadingMorePages").fadeIn(300);
}

function hideLoadingMore() { 
		$("#loadingMore").fadeOut(300);
}

function hideLoadingMorePages() { 
		$("#loadingMorePages").fadeOut(300);
}

function showImageProducts() { 
	$("#image-products").slideDown(200);
	$("#viewPhotoContainer").css('width', '45%');
	resizeImgToContainer($("#photoview-"+$("#vinfo").attr("currentViewPhoto")),'viewPhoto');

}
function closeImageProducts() { 
	$("#image-products").fadeOut(200, function() {
		$("#viewPhotoContainer").css('width', '90%');
		resizeImgToContainer($("#photoview-"+$("#vinfo").attr("currentViewPhoto")),'viewPhoto');
	});

}

function getDivPosition(d){
	thumbpopulate = setInterval("getDivPositionHere('endpage')",500);
}

function getDivPositionHere(d){
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
	/*if(scrollPos > 1000) { 
		$("#gototop").fadeIn(400);
	} else { 
		$("#gototop").fadeOut(400);
	}*/
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
					$.get(tempfolder+"/sy-inc/sy-thumbnails.php?page="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&cat_id="+cat_id+"&pic_client="+pic_client+"&keyWord="+keyWord+"&kid="+kid+"&passcode="+$("#vinfo").attr("passcode")+"&kid="+kid+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&price_list="+plid+"&mobile="+ismobile+"&search_date="+search_date+"&search_length="+search_length+"&from_time="+from_time, function(data) {
						$("#displayThumbnailPage").append(data);
						setTimeout(hideLoadingMore,1000);
						if(norightclick == '1') { 
							disablerightclick();
						}

					});
					$("#vinfo").attr("thumbPageID",nextPage);
				}
			}
		}
	}

}





function getSubGalleries(d){
	thumbpopulate = setInterval("getSubGalleriesHere('endsubgalleries')",500);
}

function getSubGalleriesHere(d){
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
	if((scrollPos + screenHeight) > (divEnd - 400)) { 
		var curPage = $("#vinfo").attr("subPageID");
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
		if(document.getElementById("subpage-"+nextPage)) { 
			if(document.getElementById("subpage-"+nextPage)){ 
				showLoadingMore();
				$.get(tempfolder+"/sy-inc/sy-sub-galleries.php?subpage="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&cat_id="+cat_id+"&pic_client="+pic_client+"&keyWord="+keyWord+"&kid="+kid+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&price_list="+plid+"&mobile="+ismobile+"&search_date="+search_date+"&passcode="+$("#vinfo").attr("passcode")+"&search_length="+search_length+"&from_time="+from_time, function(data) {
					$(".sub-galleries-populate").append(data);
					resizelistings();
					setTimeout(hideLoadingMore,1000);
				});
				$("#vinfo").attr("subPageID",nextPage);
			}
		}
	}

}

function getPageListings(d){
	pagepopulate = setInterval("getPageListingsHere('endpagelistings')",500);
}

function getPageListingsHere(d){
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
	if((scrollPos + screenHeight) > (divEnd - 400)) { 
		var curPage = $("#vinfo").attr("listingpageid");
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
		// $("#log").show().append(divEnd);
		if(document.getElementById("listingpage-"+nextPage)) { 
			if(document.getElementById("listingpage-"+nextPage)){ 
				if(nextPage > 2) { 
					showLoadingMorePages();
				}
				$.get(tempfolder+"/sy-inc/sy-listing.php?vp="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&cat_id="+cat_id+"&pic_client="+pic_client+"&passcode="+$("#vinfo").attr("passcode")+"&keyWord="+keyWord+"&kid="+kid+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&price_list="+plid+"&mobile="+ismobile+"&search_date="+search_date+"&search_length="+search_length+"&from_time="+from_time+"&page_home="+$("#vinfo").attr("page-home"), function(data) {
					data = $.trim(data);
					if(data == "done") { 
						$("#loadingMorePages").html("").append("All Done");
						setTimeout(hideLoadingMorePages,2000);
					} else { 
						$(".listingpagepopulate").append(data);
						resizelistings();
						setTimeout(hideLoadingMorePages,200);
					}
				});
				$("#vinfo").attr("listingpageid",nextPage);
			}
		}
	}

}

function pageLoading() { 
	$("#loadingPage").fadeIn('fast');
}



function pageDoneLoading() { 
//	alert("done");
	$("#loadingPage").fadeOut('fast');
}



$.fn.scrollView = function () {
    return this.each(function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top - 150
        }, 500);
    });
}




function validateEmail($email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,14})?$/;
	return emailReg.test( $email );
}

function checkContactForm() { 
	var rf = false;
	var empty = false;
	var em = false;
	var mq = false;
	$("#contactresponse").slideUp(100);
	$(".required").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
			empty = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}



		if(rf == false ){ 
			if($('#'+this_id).hasClass("email")) { 
				if(!validateEmail($('#'+this_id).val())) { 
					$('#'+this_id).addClass('inputError');
					rf = true;
					em = true;
					// alert('Your email address does not seem to be formatted correctly.\r\n"'+$('#'+this_id).val()+'"'); 
				}

			}
		}
		if(rf == false) { 
			if($('#'+this_id).hasClass("mathq")) { 
				if($('#'+this_id).val() !== $('#'+this_id).attr('data-total')) { 
					rf = true;
					mq = true;
					// alert('Total incorrect'); 
				}
			}
		}
	} );

	if(rf == true) { 
		if(empty == true) { 
			$("#contactresponse").addClass("error").html($("#contactresponse").attr("emptymessage")).slideDown(100);
		} else if(em == true) { 
			$("#contactresponse").addClass("error").html($("#contactresponse").attr("invalidemail")).slideDown(100);
		} else if(mq == true) { 
			$("#contactresponse").addClass("error").html($("#contactresponse").attr("mathincorrect")).slideDown(100);
		}
		return false;

		var stop = true;
	}
	if(botdetect() == false) { 
		 $("#contactresponse").html('<div class="pc"><div class="error" style="font-size: 21px;">I\m sorry, but our spam bot protection is thinking you might be a spam bot. If this is in error, please email us directly at ('+$("#from_message_to").attr("em")+'). Sorry for any inconvenience</div></div>');
		var stop = true;
		return false;

	}
	if($("#contactform").attr("submitted") == "1") { 
		return false;
	} else { 
		if(stop !== true) { 
			$("#contactform").attr("submitted","1");
			$('#submitButton').val("...........");
			$('#submitButton').attr('disabled', 'disabled');
			$("#contactform").submit();
			return true;
		}
	}
}



function GetScreenWidth(){
        var x = 0;
        if (self.innerHeight)
        {
                x = self.innerWidth;
        }
        else if (document.documentElement && document.documentElement.clientHeight)
        {
                x = document.documentElement.clientWidth;
        }
        else if (document.body)
        {
                x = document.body.clientWidth;
        }
        return x;
}

function GetScreenHeight(){
        var x = 0;
        if (self.innerHeight)
        {
                x = self.innerHeight;
        }
        else if (document.documentElement && document.documentElement.clientHeight)
        {
                x = document.documentElement.clientHeight;
        }
        else if (document.body)
        {
                x = document.body.clientHeight;
        }
        return x;
}

 function findPosX(obj)
  {
    var curleft = 0;
    if(obj.offsetParent)
        while(1) 
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
  }

  function findPosY(obj)
  {
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
  }

function addslashes(str) {
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\0/g,'\\0');
return str;
}
function stripslashes(str) {
str=str.replace(/\\'/g,'\'');
str=str.replace(/\\"/g,'"');
str=str.replace(/\\0/g,'\0');
str=str.replace(/\\\\/g,'\\');
return str;
}

function sendFacebookNotify(type,link,ses) {
	$.get(tempfolder+"/sy-inc/sy-fb-notify.php?link=" + link +'&type='+type+'&ses='+ses, function(data) {

	});
}







var timerlen = 5;
var slideAniLen = 250;

var timerID = new Array();
var startTime = new Array();
var obj = new Array();
var endHeight = new Array();
var moving = new Array();
var dir = new Array();




/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);



function resizeBg(bgImg,where) {
	showScroll = 0;
	var imgwidth = parseInt(bgImg.attr("ww") );
	imgwidth = imgwidth;
	var imgheight = parseInt(bgImg.attr("hh"));
	imgheight = imgheight;

//	var winwidth = $("#"+where).width();
//	var winheight = $("#"+where).height();
//	alert(imgwidth +" X "+imgheight+" window: "+winwidth+" X "+winheight);
	var winwidth = $(window).width();
	var winheight = $(window).height();

	var widthratio = winwidth / imgwidth;
	var heightratio = winheight / imgheight;
	var widthdiff = heightratio * imgwidth;
	var heightdiff = widthratio * imgheight;
	if(heightdiff>winheight) {
		borderW = parseInt(bgImg.css("border-left-width"));
		bgImg.css({
			width: winwidth+'px',
			'margin-left': '-'+borderW+'px',
			height: heightdiff+'px'
		});
		mt = (heightdiff - winheight) / 2;


		if(mt > 0) {
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


function resizeVG(bgImg,where) {
	showScroll = 0;
	var imgwidth = parseInt(bgImg.attr("ww") );
	imgwidth = imgwidth;
	var imgheight = parseInt(bgImg.attr("hh"));
	imgheight = imgheight;

	var winwidth = $(window).width();
	var winheight = $(window).height();
	// alert(imgwidth +" X "+imgheight+" window: "+winwidth+" X "+winheight +" Window height: "+$(window).width() +" X "+$(window).height() );
		bgImg.css({
			width: winwidth+'px',
			height: winheight+'px'
		});
} 


function showLikeBox() { 
		move = $("#likeBoxInner").width();
		$("#facebookLikeBoxFS").animate(
		{  'margin-left': '0px'}, 
		{ duration: aspeed, 
		complete: function() {
		}
	  });

	$("#facebookTabInnerTab").unbind('click');
	$("#facebookTabInnerTab").bind('click', function() {hideLikeBox() });
}

function hideLikeBox() { 
		move = $("#likeBoxInner").width();
		$("#facebookLikeBoxFS").animate(
		{  'margin-left': '-'+move+'px'}, 
		{ duration: aspeed, 
		complete: function() {
		}
	  });

	$("#facebookTabInnerTab").unbind('click');
	$("#facebookTabInnerTab").bind('click', function() {showLikeBox() });
}
function loading() { 
	$("#loading").fadeIn(100);
}
function loadingdone() { 
	$("#loading").fadeOut(50);
}




/* TOUCHSWIPE */
(function(a){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],a)}else{a(jQuery)}}(function(f){var p="left",o="right",e="up",x="down",c="in",z="out",m="none",s="auto",l="swipe",t="pinch",A="tap",j="doubletap",b="longtap",y="hold",D="horizontal",u="vertical",i="all",r=10,g="start",k="move",h="end",q="cancel",a="ontouchstart" in window,v=window.navigator.msPointerEnabled&&!window.navigator.pointerEnabled,d=window.navigator.pointerEnabled||window.navigator.msPointerEnabled,B="TouchSwipe";var n={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,hold:null,triggerOnTouchEnd:true,triggerOnTouchLeave:false,allowPageScroll:"auto",fallbackToMouseEvents:true,excludedElements:"label, button, input, select, textarea, a, .noSwipe"};f.fn.swipe=function(G){var F=f(this),E=F.data(B);if(E&&typeof G==="string"){if(E[G]){return E[G].apply(this,Array.prototype.slice.call(arguments,1))}else{f.error("Method "+G+" does not exist on jQuery.swipe")}}else{if(!E&&(typeof G==="object"||!G)){return w.apply(this,arguments)}}return F};f.fn.swipe.defaults=n;f.fn.swipe.phases={PHASE_START:g,PHASE_MOVE:k,PHASE_END:h,PHASE_CANCEL:q};f.fn.swipe.directions={LEFT:p,RIGHT:o,UP:e,DOWN:x,IN:c,OUT:z};f.fn.swipe.pageScroll={NONE:m,HORIZONTAL:D,VERTICAL:u,AUTO:s};f.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:i};function w(E){if(E&&(E.allowPageScroll===undefined&&(E.swipe!==undefined||E.swipeStatus!==undefined))){E.allowPageScroll=m}if(E.click!==undefined&&E.tap===undefined){E.tap=E.click}if(!E){E={}}E=f.extend({},f.fn.swipe.defaults,E);return this.each(function(){var G=f(this);var F=G.data(B);if(!F){F=new C(this,E);G.data(B,F)}})}function C(a4,av){var az=(a||d||!av.fallbackToMouseEvents),J=az?(d?(v?"MSPointerDown":"pointerdown"):"touchstart"):"mousedown",ay=az?(d?(v?"MSPointerMove":"pointermove"):"touchmove"):"mousemove",U=az?(d?(v?"MSPointerUp":"pointerup"):"touchend"):"mouseup",S=az?null:"mouseleave",aD=(d?(v?"MSPointerCancel":"pointercancel"):"touchcancel");var ag=0,aP=null,ab=0,a1=0,aZ=0,G=1,aq=0,aJ=0,M=null;var aR=f(a4);var Z="start";var W=0;var aQ=null;var T=0,a2=0,a5=0,ad=0,N=0;var aW=null,af=null;try{aR.bind(J,aN);aR.bind(aD,a9)}catch(ak){f.error("events not supported "+J+","+aD+" on jQuery.swipe")}this.enable=function(){aR.bind(J,aN);aR.bind(aD,a9);return aR};this.disable=function(){aK();return aR};this.destroy=function(){aK();aR.data(B,null);return aR};this.option=function(bc,bb){if(av[bc]!==undefined){if(bb===undefined){return av[bc]}else{av[bc]=bb}}else{f.error("Option "+bc+" does not exist on jQuery.swipe.options")}return null};function aN(bd){if(aB()){return}if(f(bd.target).closest(av.excludedElements,aR).length>0){return}var be=bd.originalEvent?bd.originalEvent:bd;var bc,bb=a?be.touches[0]:be;Z=g;if(a){W=be.touches.length}else{bd.preventDefault()}ag=0;aP=null;aJ=null;ab=0;a1=0;aZ=0;G=1;aq=0;aQ=aj();M=aa();R();if(!a||(W===av.fingers||av.fingers===i)||aX()){ai(0,bb);T=at();if(W==2){ai(1,be.touches[1]);a1=aZ=au(aQ[0].start,aQ[1].start)}if(av.swipeStatus||av.pinchStatus){bc=O(be,Z)}}else{bc=false}if(bc===false){Z=q;O(be,Z);return bc}else{if(av.hold){af=setTimeout(f.proxy(function(){aR.trigger("hold",[be.target]);if(av.hold){bc=av.hold.call(aR,be,be.target)}},this),av.longTapThreshold)}ao(true)}return null}function a3(be){var bh=be.originalEvent?be.originalEvent:be;if(Z===h||Z===q||am()){return}var bd,bc=a?bh.touches[0]:bh;var bf=aH(bc);a2=at();if(a){W=bh.touches.length}if(av.hold){clearTimeout(af)}Z=k;if(W==2){if(a1==0){ai(1,bh.touches[1]);a1=aZ=au(aQ[0].start,aQ[1].start)}else{aH(bh.touches[1]);aZ=au(aQ[0].end,aQ[1].end);aJ=ar(aQ[0].end,aQ[1].end)}G=a7(a1,aZ);aq=Math.abs(a1-aZ)}if((W===av.fingers||av.fingers===i)||!a||aX()){aP=aL(bf.start,bf.end);al(be,aP);ag=aS(bf.start,bf.end);ab=aM();aI(aP,ag);if(av.swipeStatus||av.pinchStatus){bd=O(bh,Z)}if(!av.triggerOnTouchEnd||av.triggerOnTouchLeave){var bb=true;if(av.triggerOnTouchLeave){var bg=aY(this);bb=E(bf.end,bg)}if(!av.triggerOnTouchEnd&&bb){Z=aC(k)}else{if(av.triggerOnTouchLeave&&!bb){Z=aC(h)}}if(Z==q||Z==h){O(bh,Z)}}}else{Z=q;O(bh,Z)}if(bd===false){Z=q;O(bh,Z)}}function L(bb){var bc=bb.originalEvent;if(a){if(bc.touches.length>0){F();return true}}if(am()){W=ad}a2=at();ab=aM();if(ba()||!an()){Z=q;O(bc,Z)}else{if(av.triggerOnTouchEnd||(av.triggerOnTouchEnd==false&&Z===k)){bb.preventDefault();Z=h;O(bc,Z)}else{if(!av.triggerOnTouchEnd&&a6()){Z=h;aF(bc,Z,A)}else{if(Z===k){Z=q;O(bc,Z)}}}}ao(false);return null}function a9(){W=0;a2=0;T=0;a1=0;aZ=0;G=1;R();ao(false)}function K(bb){var bc=bb.originalEvent;if(av.triggerOnTouchLeave){Z=aC(h);O(bc,Z)}}function aK(){aR.unbind(J,aN);aR.unbind(aD,a9);aR.unbind(ay,a3);aR.unbind(U,L);if(S){aR.unbind(S,K)}ao(false)}function aC(bf){var be=bf;var bd=aA();var bc=an();var bb=ba();if(!bd||bb){be=q}else{if(bc&&bf==k&&(!av.triggerOnTouchEnd||av.triggerOnTouchLeave)){be=h}else{if(!bc&&bf==h&&av.triggerOnTouchLeave){be=q}}}return be}function O(bd,bb){var bc=undefined;if(I()||V()){bc=aF(bd,bb,l)}else{if((P()||aX())&&bc!==false){bc=aF(bd,bb,t)}}if(aG()&&bc!==false){bc=aF(bd,bb,j)}else{if(ap()&&bc!==false){bc=aF(bd,bb,b)}else{if(ah()&&bc!==false){bc=aF(bd,bb,A)}}}if(bb===q){a9(bd)}if(bb===h){if(a){if(bd.touches.length==0){a9(bd)}}else{a9(bd)}}return bc}function aF(be,bb,bd){var bc=undefined;if(bd==l){aR.trigger("swipeStatus",[bb,aP||null,ag||0,ab||0,W,aQ]);if(av.swipeStatus){bc=av.swipeStatus.call(aR,be,bb,aP||null,ag||0,ab||0,W,aQ);if(bc===false){return false}}if(bb==h&&aV()){aR.trigger("swipe",[aP,ag,ab,W,aQ]);if(av.swipe){bc=av.swipe.call(aR,be,aP,ag,ab,W,aQ);if(bc===false){return false}}switch(aP){case p:aR.trigger("swipeLeft",[aP,ag,ab,W,aQ]);if(av.swipeLeft){bc=av.swipeLeft.call(aR,be,aP,ag,ab,W,aQ)}break;case o:aR.trigger("swipeRight",[aP,ag,ab,W,aQ]);if(av.swipeRight){bc=av.swipeRight.call(aR,be,aP,ag,ab,W,aQ)}break;case e:aR.trigger("swipeUp",[aP,ag,ab,W,aQ]);if(av.swipeUp){bc=av.swipeUp.call(aR,be,aP,ag,ab,W,aQ)}break;case x:aR.trigger("swipeDown",[aP,ag,ab,W,aQ]);if(av.swipeDown){bc=av.swipeDown.call(aR,be,aP,ag,ab,W,aQ)}break}}}if(bd==t){aR.trigger("pinchStatus",[bb,aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchStatus){bc=av.pinchStatus.call(aR,be,bb,aJ||null,aq||0,ab||0,W,G,aQ);if(bc===false){return false}}if(bb==h&&a8()){switch(aJ){case c:aR.trigger("pinchIn",[aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchIn){bc=av.pinchIn.call(aR,be,aJ||null,aq||0,ab||0,W,G,aQ)}break;case z:aR.trigger("pinchOut",[aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchOut){bc=av.pinchOut.call(aR,be,aJ||null,aq||0,ab||0,W,G,aQ)}break}}}if(bd==A){if(bb===q||bb===h){clearTimeout(aW);clearTimeout(af);if(Y()&&!H()){N=at();aW=setTimeout(f.proxy(function(){N=null;aR.trigger("tap",[be.target]);if(av.tap){bc=av.tap.call(aR,be,be.target)}},this),av.doubleTapThreshold)}else{N=null;aR.trigger("tap",[be.target]);if(av.tap){bc=av.tap.call(aR,be,be.target)}}}}else{if(bd==j){if(bb===q||bb===h){clearTimeout(aW);N=null;aR.trigger("doubletap",[be.target]);if(av.doubleTap){bc=av.doubleTap.call(aR,be,be.target)}}}else{if(bd==b){if(bb===q||bb===h){clearTimeout(aW);N=null;aR.trigger("longtap",[be.target]);if(av.longTap){bc=av.longTap.call(aR,be,be.target)}}}}}return bc}function an(){var bb=true;if(av.threshold!==null){bb=ag>=av.threshold}return bb}function ba(){var bb=false;if(av.cancelThreshold!==null&&aP!==null){bb=(aT(aP)-ag)>=av.cancelThreshold}return bb}function ae(){if(av.pinchThreshold!==null){return aq>=av.pinchThreshold}return true}function aA(){var bb;if(av.maxTimeThreshold){if(ab>=av.maxTimeThreshold){bb=false}else{bb=true}}else{bb=true}return bb}function al(bb,bc){if(av.allowPageScroll===m||aX()){bb.preventDefault()}else{var bd=av.allowPageScroll===s;switch(bc){case p:if((av.swipeLeft&&bd)||(!bd&&av.allowPageScroll!=D)){bb.preventDefault()}break;case o:if((av.swipeRight&&bd)||(!bd&&av.allowPageScroll!=D)){bb.preventDefault()}break;case e:if((av.swipeUp&&bd)||(!bd&&av.allowPageScroll!=u)){bb.preventDefault()}break;case x:if((av.swipeDown&&bd)||(!bd&&av.allowPageScroll!=u)){bb.preventDefault()}break}}}function a8(){var bc=aO();var bb=X();var bd=ae();return bc&&bb&&bd}function aX(){return !!(av.pinchStatus||av.pinchIn||av.pinchOut)}function P(){return !!(a8()&&aX())}function aV(){var be=aA();var bg=an();var bd=aO();var bb=X();var bc=ba();var bf=!bc&&bb&&bd&&bg&&be;return bf}function V(){return !!(av.swipe||av.swipeStatus||av.swipeLeft||av.swipeRight||av.swipeUp||av.swipeDown)}function I(){return !!(aV()&&V())}function aO(){return((W===av.fingers||av.fingers===i)||!a)}function X(){return aQ[0].end.x!==0}function a6(){return !!(av.tap)}function Y(){return !!(av.doubleTap)}function aU(){return !!(av.longTap)}function Q(){if(N==null){return false}var bb=at();return(Y()&&((bb-N)<=av.doubleTapThreshold))}function H(){return Q()}function ax(){return((W===1||!a)&&(isNaN(ag)||ag<av.threshold))}function a0(){return((ab>av.longTapThreshold)&&(ag<r))}function ah(){return !!(ax()&&a6())}function aG(){return !!(Q()&&Y())}function ap(){return !!(a0()&&aU())}function F(){a5=at();ad=event.touches.length+1}function R(){a5=0;ad=0}function am(){var bb=false;if(a5){var bc=at()-a5;if(bc<=av.fingerReleaseThreshold){bb=true}}return bb}function aB(){return !!(aR.data(B+"_intouch")===true)}function ao(bb){if(bb===true){aR.bind(ay,a3);aR.bind(U,L);if(S){aR.bind(S,K)}}else{aR.unbind(ay,a3,false);aR.unbind(U,L,false);if(S){aR.unbind(S,K,false)}}aR.data(B+"_intouch",bb===true)}function ai(bc,bb){var bd=bb.identifier!==undefined?bb.identifier:0;aQ[bc].identifier=bd;aQ[bc].start.x=aQ[bc].end.x=bb.pageX||bb.clientX;aQ[bc].start.y=aQ[bc].end.y=bb.pageY||bb.clientY;return aQ[bc]}function aH(bb){var bd=bb.identifier!==undefined?bb.identifier:0;var bc=ac(bd);bc.end.x=bb.pageX||bb.clientX;bc.end.y=bb.pageY||bb.clientY;return bc}function ac(bc){for(var bb=0;bb<aQ.length;bb++){if(aQ[bb].identifier==bc){return aQ[bb]}}}function aj(){var bb=[];for(var bc=0;bc<=5;bc++){bb.push({start:{x:0,y:0},end:{x:0,y:0},identifier:0})}return bb}function aI(bb,bc){bc=Math.max(bc,aT(bb));M[bb].distance=bc}function aT(bb){if(M[bb]){return M[bb].distance}return undefined}function aa(){var bb={};bb[p]=aw(p);bb[o]=aw(o);bb[e]=aw(e);bb[x]=aw(x);return bb}function aw(bb){return{direction:bb,distance:0}}function aM(){return a2-T}function au(be,bd){var bc=Math.abs(be.x-bd.x);var bb=Math.abs(be.y-bd.y);return Math.round(Math.sqrt(bc*bc+bb*bb))}function a7(bb,bc){var bd=(bc/bb)*1;return bd.toFixed(2)}function ar(){if(G<1){return z}else{return c}}function aS(bc,bb){return Math.round(Math.sqrt(Math.pow(bb.x-bc.x,2)+Math.pow(bb.y-bc.y,2)))}function aE(be,bc){var bb=be.x-bc.x;var bg=bc.y-be.y;var bd=Math.atan2(bg,bb);var bf=Math.round(bd*180/Math.PI);if(bf<0){bf=360-Math.abs(bf)}return bf}function aL(bc,bb){var bd=aE(bc,bb);if((bd<=45)&&(bd>=0)){return p}else{if((bd<=360)&&(bd>=315)){return p}else{if((bd>=135)&&(bd<=225)){return o}else{if((bd>45)&&(bd<135)){return x}else{return e}}}}}function at(){var bb=new Date();return bb.getTime()}function aY(bb){bb=f(bb);var bd=bb.offset();var bc={left:bd.left,right:bd.left+bb.outerWidth(),top:bd.top,bottom:bd.top+bb.outerHeight()};return bc}function E(bb,bc){return(bb.x>bc.left&&bb.x<bc.right&&bb.y>bc.top&&bb.y<bc.bottom)}}}));
/* /TOUCHSWIPE */





/*!
 * imagesLoaded PACKAGED v3.0.2
 * JavaScript is all like "You images are done yet or what?"
 */

/*!
 * EventEmitter v4.1.0 - git.io/ee
 * Oliver Caldwell
 * MIT license
 * @preserve
 */

(function (exports) {
	// Place the script in strict mode
	'use strict';

	/**
	 * Class for managing events.
	 * Can be extended to provide event functionality in other classes.
	 *
	 * @class Manages event registering and emitting.
	 */
	function EventEmitter() {}

	// Shortcuts to improve speed and size

	// Easy access to the prototype
	var proto = EventEmitter.prototype,
		nativeIndexOf = Array.prototype.indexOf ? true : false;

	/**
	 * Finds the index of the listener for the event in it's storage array.
	 *
	 * @param {Function} listener Method to look for.
	 * @param {Function[]} listeners Array of listeners to search through.
	 * @return {Number} Index of the specified listener, -1 if not found
	 * @api private
	 */
	function indexOfListener(listener, listeners) {
		// Return the index via the native method if possible
		if (nativeIndexOf) {
			return listeners.indexOf(listener);
		}

		// There is no native method
		// Use a manual loop to find the index
		var i = listeners.length;
		while (i--) {
			// If the listener matches, return it's index
			if (listeners[i] === listener) {
				return i;
			}
		}

		// Default to returning -1
		return -1;
	}

	/**
	 * Fetches the events object and creates one if required.
	 *
	 * @return {Object} The events storage object.
	 * @api private
	 */
	proto._getEvents = function () {
		return this._events || (this._events = {});
	};

	/**
	 * Returns the listener array for the specified event.
	 * Will initialise the event object and listener arrays if required.
	 * Will return an object if you use a regex search. The object contains keys for each matched event. So /ba[rz]/ might return an object containing bar and baz. But only if you have either defined them with defineEvent or added some listeners to them.
	 * Each property in the object response is an array of listener functions.
	 *
	 * @param {String|RegExp} evt Name of the event to return the listeners from.
	 * @return {Function[]|Object} All listener functions for the event.
	 */
	proto.getListeners = function (evt) {
		// Create a shortcut to the storage object
		// Initialise it if it does not exists yet
		var events = this._getEvents(),
			response,
			key;

		// Return a concatenated array of all matching events if
		// the selector is a regular expression.
		if (typeof evt === 'object') {
			response = {};
			for (key in events) {
				if (events.hasOwnProperty(key) && evt.test(key)) {
					response[key] = events[key];
				}
			}
		}
		else {
			response = events[evt] || (events[evt] = []);
		}

		return response;
	};

	/**
	 * Fetches the requested listeners via getListeners but will always return the results inside an object. This is mainly for internal use but others may find it useful.
	 *
	 * @param {String|RegExp} evt Name of the event to return the listeners from.
	 * @return {Object} All listener functions for an event in an object.
	 */
	proto.getListenersAsObject = function (evt) {
		var listeners = this.getListeners(evt),
			response;

		if (listeners instanceof Array) {
			response = {};
			response[evt] = listeners;
		}

		return response || listeners;
	};

	/**
	 * Adds a listener function to the specified event.
	 * The listener will not be added if it is a duplicate.
	 * If the listener returns true then it will be removed after it is called.
	 * If you pass a regular expression as the event name then the listener will be added to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to attach the listener to.
	 * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.addListener = function (evt, listener) {
		var listeners = this.getListenersAsObject(evt),
			key;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key) &&
				indexOfListener(listener, listeners[key]) === -1) {
				listeners[key].push(listener);
			}
		}

		// Return the instance of EventEmitter to allow chaining
		return this;
	};

	/**
	 * Alias of addListener
	 */
	proto.on = proto.addListener;

	/**
	 * Defines an event name. This is required if you want to use a regex to add a listener to multiple events at once. If you don't do this then how do you expect it to know what event to add to? Should it just add to every possible match for a regex? No. That is scary and bad.
	 * You need to tell it what event names should be matched by a regex.
	 *
	 * @param {String} evt Name of the event to create.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.defineEvent = function (evt) {
		this.getListeners(evt);
		return this;
	};

	/**
	 * Uses defineEvent to define multiple events.
	 *
	 * @param {String[]} evts An array of event names to define.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.defineEvents = function (evts)
	{
		for (var i = 0; i < evts.length; i += 1) {
			this.defineEvent(evts[i]);
		}
		return this;
	};

	/**
	 * Removes a listener function from the specified event.
	 * When passed a regular expression as the event name, it will remove the listener from all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to remove the listener from.
	 * @param {Function} listener Method to remove from the event.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeListener = function (evt, listener) {
		var listeners = this.getListenersAsObject(evt),
			index,
			key;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key)) {
				index = indexOfListener(listener, listeners[key]);

				if (index !== -1) {
					listeners[key].splice(index, 1);
				}
			}
		}

		// Return the instance of EventEmitter to allow chaining
		return this;
	};

	/**
	 * Alias of removeListener
	 */
	proto.off = proto.removeListener;

	/**
	 * Adds listeners in bulk using the manipulateListeners method.
	 * If you pass an object as the second argument you can add to multiple events at once. The object should contain key value pairs of events and listeners or listener arrays. You can also pass it an event name and an array of listeners to be added.
	 * You can also pass it a regular expression to add the array of listeners to all events that match it.
	 * Yeah, this function does quite a bit. That's probably a bad thing.
	 *
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add to multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to add.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.addListeners = function (evt, listeners) {
		// Pass through to manipulateListeners
		return this.manipulateListeners(false, evt, listeners);
	};

	/**
	 * Removes listeners in bulk using the manipulateListeners method.
	 * If you pass an object as the second argument you can remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
	 * You can also pass it an event name and an array of listeners to be removed.
	 * You can also pass it a regular expression to remove the listeners from all events that match it.
	 *
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to remove from multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to remove.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeListeners = function (evt, listeners) {
		// Pass through to manipulateListeners
		return this.manipulateListeners(true, evt, listeners);
	};

	/**
	 * Edits listeners in bulk. The addListeners and removeListeners methods both use this to do their job. You should really use those instead, this is a little lower level.
	 * The first argument will determine if the listeners are removed (true) or added (false).
	 * If you pass an object as the second argument you can add/remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
	 * You can also pass it an event name and an array of listeners to be added/removed.
	 * You can also pass it a regular expression to manipulate the listeners of all events that match it.
	 *
	 * @param {Boolean} remove True if you want to remove listeners, false if you want to add.
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add/remove from multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to add/remove.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.manipulateListeners = function (remove, evt, listeners) {
		// Initialise any required variables
		var i,
			value,
			single = remove ? this.removeListener : this.addListener,
			multiple = remove ? this.removeListeners : this.addListeners;

		// If evt is an object then pass each of it's properties to this method
		if (typeof evt === 'object' && !(evt instanceof RegExp)) {
			for (i in evt) {
				if (evt.hasOwnProperty(i) && (value = evt[i])) {
					// Pass the single listener straight through to the singular method
					if (typeof value === 'function') {
						single.call(this, i, value);
					}
					else {
						// Otherwise pass back to the multiple function
						multiple.call(this, i, value);
					}
				}
			}
		}
		else {
			// So evt must be a string
			// And listeners must be an array of listeners
			// Loop over it and pass each one to the multiple method
			i = listeners.length;
			while (i--) {
				single.call(this, evt, listeners[i]);
			}
		}

		// Return the instance of EventEmitter to allow chaining
		return this;
	};

	/**
	 * Removes all listeners from a specified event.
	 * If you do not specify an event then all listeners will be removed.
	 * That means every event will be emptied.
	 * You can also pass a regex to remove all events that match it.
	 *
	 * @param {String|RegExp} [evt] Optional name of the event to remove all listeners for. Will remove from every event if not passed.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeEvent = function (evt) {
		var type = typeof evt,
			events = this._getEvents(),
			key;

		// Remove different things depending on the state of evt
		if (type === 'string') {
			// Remove all listeners for the specified event
			delete events[evt];
		}
		else if (type === 'object') {
			// Remove all events matching the regex.
			for (key in events) {
				if (events.hasOwnProperty(key) && evt.test(key)) {
					delete events[key];
				}
			}
		}
		else {
			// Remove all listeners in all events
			delete this._events;
		}

		// Return the instance of EventEmitter to allow chaining
		return this;
	};

	/**
	 * Emits an event of your choice.
	 * When emitted, every listener attached to that event will be executed.
	 * If you pass the optional argument array then those arguments will be passed to every listener upon execution.
	 * Because it uses `apply`, your array of arguments will be passed as if you wrote them out separately.
	 * So they will not arrive within the array on the other side, they will be separate.
	 * You can also pass a regular expression to emit to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
	 * @param {Array} [args] Optional array of arguments to be passed to each listener.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.emitEvent = function (evt, args) {
		var listeners = this.getListenersAsObject(evt),
			i,
			key,
			response;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key)) {
				i = listeners[key].length;

				while (i--) {
					// If the listener returns true then it shall be removed from the event
					// The function is executed either with a basic call or an apply if there is an args array
					response = args ? listeners[key][i].apply(null, args) : listeners[key][i]();
					if (response === true) {
						this.removeListener(evt, listeners[key][i]);
					}
				}
			}
		}

		// Return the instance of EventEmitter to allow chaining
		return this;
	};

	/**
	 * Alias of emitEvent
	 */
	proto.trigger = proto.emitEvent;

	/**
	 * Subtly different from emitEvent in that it will pass its arguments on to the listeners, as opposed to taking a single array of arguments to pass on.
	 * As with emitEvent, you can pass a regex in place of the event name to emit to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
	 * @param {...*} Optional additional arguments to be passed to each listener.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.emit = function (evt) {
		var args = Array.prototype.slice.call(arguments, 1);
		return this.emitEvent(evt, args);
	};

	// Expose the class either via AMD or the global object
	if (typeof define === 'function' && define.amd) {
		define(function () {
			return EventEmitter;
		});
	}
	else {
		exports.EventEmitter = EventEmitter;
	}
}(this));
/*!
 * eventie v1.0.3
 * event binding helper
 *   eventie.bind( elem, 'click', myFn )
 *   eventie.unbind( elem, 'click', myFn )
 */

/*jshint browser: true, undef: true, unused: true */
/*global define: false */

( function( window ) {

'use strict';

var docElem = document.documentElement;

var bind = function() {};

if ( docElem.addEventListener ) {
  bind = function( obj, type, fn ) {
    obj.addEventListener( type, fn, false );
  };
} else if ( docElem.attachEvent ) {
  bind = function( obj, type, fn ) {
    obj[ type + fn ] = fn.handleEvent ?
      function() {
        var event = window.event;
        // add event.target
        event.target = event.target || event.srcElement;
        fn.handleEvent.call( fn, event );
      } :
      function() {
        var event = window.event;
        // add event.target
        event.target = event.target || event.srcElement;
        fn.call( obj, event );
      };
    obj.attachEvent( "on" + type, obj[ type + fn ] );
  };
}

var unbind = function() {};

if ( docElem.removeEventListener ) {
  unbind = function( obj, type, fn ) {
    obj.removeEventListener( type, fn, false );
  };
} else if ( docElem.detachEvent ) {
  unbind = function( obj, type, fn ) {
    obj.detachEvent( "on" + type, obj[ type + fn ] );
    try {
      delete obj[ type + fn ];
    } catch ( err ) {
      // can't delete window object properties
      obj[ type + fn ] = undefined;
    }
  };
}

var eventie = {
  bind: bind,
  unbind: unbind
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( eventie );
} else {
  // browser global
  window.eventie = eventie;
}

})( this );

/*!
 * imagesLoaded v3.0.2
 * JavaScript is all like "You images are done yet or what?"
 */

( function( window ) {

'use strict';

var $ = window.jQuery;
var console = window.console;
var hasConsole = typeof console !== 'undefined';

// -------------------------- helpers -------------------------- //

// extend objects
function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

var objToString = Object.prototype.toString;
function isArray( obj ) {
  return objToString.call( obj ) === '[object Array]';
}

// turn element or nodeList into an array
function makeArray( obj ) {
  var ary = [];
  if ( isArray( obj ) ) {
    // use object if already an array
    ary = obj;
  } else if ( typeof obj.length === 'number' ) {
    // convert nodeList to array
    for ( var i=0, len = obj.length; i < len; i++ ) {
      ary.push( obj[i] );
    }
  } else {
    // array of single index
    ary.push( obj );
  }
  return ary;
}

// --------------------------  -------------------------- //

function defineImagesLoaded( EventEmitter, eventie ) {

  /**
   * @param {Array, Element, NodeList, String} elem
   * @param {Object or Function} options - if function, use as callback
   * @param {Function} onAlways - callback function
   */
  function ImagesLoaded( elem, options, onAlways ) {
    // coerce ImagesLoaded() without new, to be new ImagesLoaded()
    if ( !( this instanceof ImagesLoaded ) ) {
      return new ImagesLoaded( elem, options );
    }
    // use elem as selector string
    if ( typeof elem === 'string' ) {
      elem = document.querySelectorAll( elem );
    }

    this.elements = makeArray( elem );
    this.options = extend( {}, this.options );

    if ( typeof options === 'function' ) {
      onAlways = options;
    } else {
      extend( this.options, options );
    }

    if ( onAlways ) {
      this.on( 'always', onAlways );
    }

    this.getImages();

    if ( $ ) {
      // add jQuery Deferred object
      this.jqDeferred = new $.Deferred();
    }

    // HACK check async to allow time to bind listeners
    var _this = this;
    setTimeout( function() {
      _this.check();
    });
  }

  ImagesLoaded.prototype = new EventEmitter();

  ImagesLoaded.prototype.options = {};

  ImagesLoaded.prototype.getImages = function() {
    this.images = [];

    // filter & find items if we have an item selector
    for ( var i=0, len = this.elements.length; i < len; i++ ) {
      var elem = this.elements[i];
      // filter siblings
      if ( elem.nodeName === 'IMG' ) {
        this.addImage( elem );
      }
      // find children
      var childElems = elem.querySelectorAll('img');
      // concat childElems to filterFound array
      for ( var j=0, jLen = childElems.length; j < jLen; j++ ) {
        var img = childElems[j];
        this.addImage( img );
      }
    }
  };

  /**
   * @param {Image} img
   */
  ImagesLoaded.prototype.addImage = function( img ) {
    var loadingImage = new LoadingImage( img );
    this.images.push( loadingImage );
  };

  ImagesLoaded.prototype.check = function() {
    var _this = this;
    var checkedCount = 0;
    var length = this.images.length;
    this.hasAnyBroken = false;
    // complete if no images
    if ( !length ) {
      this.complete();
      return;
    }

    function onConfirm( image, message ) {
      if ( _this.options.debug && hasConsole ) {
        console.log( 'confirm', image, message );
      }

      _this.progress( image );
      checkedCount++;
      if ( checkedCount === length ) {
        _this.complete();
      }
      return true; // bind once
    }

    for ( var i=0; i < length; i++ ) {
      var loadingImage = this.images[i];
      loadingImage.on( 'confirm', onConfirm );
      loadingImage.check();
    }
  };

  ImagesLoaded.prototype.progress = function( image ) {
    this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
    this.emit( 'progress', this, image );
    if ( this.jqDeferred ) {
      this.jqDeferred.notify( this, image );
    }
  };

  ImagesLoaded.prototype.complete = function() {
    var eventName = this.hasAnyBroken ? 'fail' : 'done';
    this.isComplete = true;
    this.emit( eventName, this );
    this.emit( 'always', this );
    if ( this.jqDeferred ) {
      var jqMethod = this.hasAnyBroken ? 'reject' : 'resolve';
      this.jqDeferred[ jqMethod ]( this );
    }
  };

  // -------------------------- jquery -------------------------- //

  if ( $ ) {
    $.fn.imagesLoaded = function( options, callback ) {
      var instance = new ImagesLoaded( this, options, callback );
      return instance.jqDeferred.promise( $(this) );
    };
  }


  // --------------------------  -------------------------- //

  var cache = {};

  function LoadingImage( img ) {
    this.img = img;
  }

  LoadingImage.prototype = new EventEmitter();

  LoadingImage.prototype.check = function() {
    // first check cached any previous images that have same src
    var cached = cache[ this.img.src ];
    if ( cached ) {
      this.useCached( cached );
      return;
    }
    // add this to cache
    cache[ this.img.src ] = this;

    // If complete is true and browser supports natural sizes,
    // try to check for image status manually.
    if ( this.img.complete && this.img.naturalWidth !== undefined ) {
      // report based on naturalWidth
      this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
      return;
    }

    // If none of the checks above matched, simulate loading on detached element.
    var proxyImage = this.proxyImage = new Image();
    eventie.bind( proxyImage, 'load', this );
    eventie.bind( proxyImage, 'error', this );
    proxyImage.src = this.img.src;
  };

  LoadingImage.prototype.useCached = function( cached ) {
    if ( cached.isConfirmed ) {
      this.confirm( cached.isLoaded, 'cached was confirmed' );
    } else {
      var _this = this;
      cached.on( 'confirm', function( image ) {
        _this.confirm( image.isLoaded, 'cache emitted confirmed' );
        return true; // bind once
      });
    }
  };

  LoadingImage.prototype.confirm = function( isLoaded, message ) {
    this.isConfirmed = true;
    this.isLoaded = isLoaded;
    this.emit( 'confirm', this, message );
  };

  // trigger specified handler for event type
  LoadingImage.prototype.handleEvent = function( event ) {
    var method = 'on' + event.type;
    if ( this[ method ] ) {
      this[ method ]( event );
    }
  };

  LoadingImage.prototype.onload = function() {
    this.confirm( true, 'onload' );
    this.unbindProxyEvents();
  };

  LoadingImage.prototype.onerror = function() {
    this.confirm( false, 'onerror' );
    this.unbindProxyEvents();
  };

  LoadingImage.prototype.unbindProxyEvents = function() {
    eventie.unbind( this.proxyImage, 'load', this );
    eventie.unbind( this.proxyImage, 'error', this );
  };

  // -----  ----- //

  return ImagesLoaded;
}

// -------------------------- transport -------------------------- //

if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( [
      'eventEmitter',
      'eventie'
    ],
    defineImagesLoaded );
} else {
  // browser global
  window.imagesLoaded = defineImagesLoaded(
    window.EventEmitter,
    window.eventie
  );
}

})( window );
