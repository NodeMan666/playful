window.last_pic_selected = 0;
window.shifton = 0;
var timerlen = 5;
var slideAniLen = 250;

var timerID = new Array();
var startTime = new Array();
var obj = new Array();
var endHeight = new Array();
var moving = new Array();
var dir = new Array();

$(document).keydown(function (e) {
    if (e.keyCode == 16) {
			shifton = 1;
    }
});

$(document).keyup(function (e) {
    if (e.keyCode == 16) {
			shifton = 0;
		//	alert("no up?");
    }
});

function setpeopleinfo() { 
	$("#book_first_name").val($('option:selected', $("#book_account")).attr('first_name'));
	$("#book_last_name").val($('option:selected', $("#book_account")).attr('last_name'));
	$("#book_email").val($('option:selected', $("#book_account")).attr('email'));
	$("#book_phone").val($('option:selected', $("#book_account")).attr('phone'));
}


function getCalendar(m,y) { 
	$("#savingdata").show();
 	$.get("booking/booking-calendar.php?noclose=1&nofonts=1&nojs=1&month="+m+"&year="+y, function(data) {
		$("#bookingcalendar").html(data).fadeIn(400);
		$("#bookingcalendar").attr("data-year",y);
		$("#bookingcalendar").attr("data-month",m);
		$("#savingdata").hide();
		$("#adminfooter").show();

	});

}
function deletebooking(id) { 
	$("#savingdata").show();
 	$.get("index.php?noclose=1&nofonts=1&nojs=1&do=booking&delete_booking="+id, function(data) {
		getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
		sideeditclose();
	});

}

function dateunavailable(d) { 
	$("#savingdata").show();
 	$.get("index.php?noclose=1&nofonts=1&nojs=1&do=booking&date_unavailable="+d, function(data) {
		getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
		$("#savingdata").hide();
		viewday('',d);

	});

}
function dateavailable(d) { 
	$("#savingdata").show();
 	$.get("index.php?noclose=1&nofonts=1&nojs=1&do=booking&date_available="+d, function(data) {
		getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
		$("#savingdata").hide();
		viewday('',d);

	});

}

function updateavday(d) { 
	if($("#"+d).attr("checked")) { 
		$("."+d).show();
	} else { 
		$("#"+d+"_ado").attr("checked",false);
		$("."+d).hide();
	}
}

function updateado(d) { 
	if($("#"+d).attr("checked")) { 
		$("."+d).hide();
	} else { 
		$("."+d).show();
	}
}

function updateavtime(d) { 
	$("#savingdata").show();
 	$.get("index.php?noclose=1&nofonts=1&nojs=1&do=booking&action=available_time&d="+d+"&"+d+"_start_time="+$("#"+d+"_start_time").val()+"&"+d+"_end_time="+$("#"+d+"_end_time").val()+"&"+d+"_time_blocks="+$("#"+d+"_time_blocks").val(), function(data) {
		$("#savingdata").hide();
	});
}

function editbonuscoupon(code_id) { 
	pagewindowedit("store/coupon-credit-edit.php?noclose=1&nofonts=1&nojs=1&code_id="+code_id);
}
function editgiftcertificate(id) { 
	pagewindowedit("people/people-gift-certificate.php?noclose=1&nofonts=1&nojs=1&id="+id);
}


function cronedit(cron_id) { 
	pagewindowedit("settings/cron-edit.php?noclose=1&nofonts=1&nojs=1&cron_id="+cron_id);
}
function galleryfreedownload(date_id,sub_id) { 
	pagewindowedit("news/news-gallery-free-download.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id+"&sub_id="+sub_id);
}

function confirmbooking(id) { 
	pagewindowedit("booking/booking-confirm.php?noclose=1&nofonts=1&nojs=1&book_id="+id);
}
function bookingreminder(id) { 
	pagewindowedit("booking/booking-reminder.php?noclose=1&nofonts=1&nojs=1&book_id="+id);
}

function bookinginvoice(id) { 
	pagewindowedit("booking/booking-invoice.php?noclose=1&nofonts=1&nojs=1&book_id="+id);
}
function bookingemailinvoice(order_id,book_id) { 
	pagewindowedit("booking/booking-email-invoice.php?noclose=1&nofonts=1&nojs=1&order_id="+order_id+"&book_id="+book_id);
}
function invoiceemailreminder(id) { 
	pagewindowedit("store/orders/order-email-reminder.php?noclose=1&nofonts=1&nojs=1&id="+id);
}

function editunavailable(book_id,edit) { 
	sideedit("booking/booking-unavailable.php?do=editBooking&action="+edit+"&noclose=1&nofonts=1&nojs=1&book_id="+book_id);
}

function editbooking(book_id,edit,y,m,d,p) { 
	sideedit("booking/booking-edit.php?do=editBooking&action="+edit+"&noclose=1&nofonts=1&nojs=1&book_id="+book_id+"&year="+y+"&month="+m+"&day="+d+"&p_id="+p);
}
function viewday(book_id,date,when,back,backto,p_id,editing) { 
	if(book_id > 0) { 
		$("body").attr("book-id",book_id);
		sideedit("booking/booking-view-day.php?noclose=1&nofonts=1&nojs=1&book_id="+book_id+"&p_id="+p_id+"&back="+back+"&backto="+backto);
	} else if(date !== "0") { 
		if(editing !== 1) { 
			$("body").attr("data-back-to","date").attr("data-date",date);
		}
		sideedit("booking/booking-view-day.php?noclose=1&nofonts=1&nojs=1&date="+date+"&back="+back+"&backto="+backto);
	} else if(when == "unconfirmed") { 
		if(editing !== 1) { 
			$("body").attr("data-back-to","unconfirmed");
		}
		sideedit("booking/booking-view-day.php?noclose=1&nofonts=1&nojs=1&when="+when+"&back="+back+"&backto="+backto);
	} else if(when == "unconfirmedinvoiced") { 
		if(editing !== 1) { 
			$("body").attr("data-back-to","unconfirmedinvoiced");
		}
		sideedit("booking/booking-view-day.php?noclose=1&nofonts=1&nojs=1&when="+when+"&back="+back+"&backto="+backto);

	} else if(p_id !== "0") { 
		if(editing !== 1) { 
			$("body").attr("data-back-to","person").attr("p-id",p_id);
		}
		sideedit("booking/booking-view-day.php?noclose=1&nofonts=1&nojs=1&p_id="+p_id+"&back="+back+"&backto="+backto);
	}
}

function bookingback(){ 
	if($("body").attr("data-back-to") == "unconfirmed") {
		viewday("0","0","unconfirmed");
	}
	if($("body").attr("data-back-to") == "unconfirmedinvoiced") {
		viewday("0","0","unconfirmedinvoiced");
	}

	if($("body").attr("data-back-to") == "date") {
		viewday("0",$("body").attr("data-date"),"");
	}
	if($("body").attr("data-back-to") == "person") {
		viewday("0","0","","","",$("body").attr("p-id"));
	}

}


function editserviceoption(opt_service,opt_id) { 
	pagewindowedit("w-product-options.php?do=editOption&noclose=1&nofonts=1&nojs=1&opt_service="+opt_service+"&opt_id="+opt_id);
}

function sideedit(url) {
	$("#sideeditbg").fadeIn(100, function() { 
		$.get(url, function(data) {
		$("#sideeditinner").html(data);
		if($(window).scrollTop() < +$("#adminHeader").outerHeight()) {
			add_header = +$("#adminHeader").outerHeight() - $(window).scrollTop();
		} else { 
			add_header = 0;
		}
		 $( "#sideedit" ).css({"top":$(window).scrollTop()+add_header+$("#topmenu").height()+"px"}).show("slide", { direction: "right" }, 300, function() { 
			$("#sideeditbg").bind('click', function() {sideeditclose() });
	 
		 });
		});
	});
}

function sideeditclose() { 
	$( "#sideedit" ).hide("slide", { direction: "right" }, 300, function() { 
		$("#sideeditinner").html("");
		$("#sideeditbg").fadeOut(100);
		$("body").attr("data-back-to","");
		$("body").attr("book-id","");
		$("#sideeditbg").unbind('click');

	});
}


function showmobilemenu() { 
	$("#mobilemenulinks").slideToggle(200);
}

function updatebooknotes(id) { 
	$("#noteloading").show();
	var fields = {};
	fields['book_notes'] = $("#book_notes").html();
	fields['book_id'] = id;
	fields['updatenotes'] = "yes";
	$.post('booking/booking-edit.php', fields,	function (data) { 
		$("#noteloading").hide();
		$("#noteupdated").show();
	});

}

function cancelbooking(id) { 
	$("#noteloading").show();
	var fields = {};
	fields['book_cancel_notes'] = $("#book_cancel_notes").val();
	fields['book_id'] = id;
	fields['cancelbooking'] = "yes";
	$.post('booking/booking-edit.php', fields,	function (data) { 
		viewday(id,"0","");
		getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
	});

}


function searchpeople(div,field_name) { 
 	$.get("admin.actions.php?action=searchpeople&pq="+$("#pq").val()+"&field_name="+field_name, function(data) {
		$("#"+div).html("").append(data);
	});


}
function nofloatsmall() { 
	if($("body").width() <= 800) { 
		$(".nofloatsmall").addClass("nofloat");
		$(".nofloatsmallleft").addClass("nofloatlefttext");
		$(".smallfull").addClass("smallfullshow");
		$(".smallfull .inner").addClass("smallnomargin");
	} else { 
		$(".nofloatsmall").removeClass("nofloat");
		$(".nofloatsmallleft").removeClass("nofloatlefttext");
		$(".smallfull").removeClass("smallfullshow");
		$(".smallfull .inner").removeClass("smallnomargin");

	}
}
function gototop() { 
   $('html').animate({scrollTop:0}, 'slow'); 
    $('body').animate({scrollTop:0}, 'slow'); 
}

function adjustsite() { 
	placemenus();
	nofloatsmall();
	hidesmall();
}
function hidesmall() { 
	if($("body").width() <= 800) { 
		$(".hidesmall").css({"display":"none"});
		$("#windowedit").removeClass("pform").addClass("pformsmall");
		if (!$('.mobilesidemenu').length) {
			$("#pageTitle").prepend('<div class="left the-icons icon-menu mobilesidemenu" onclick="showhidesidemenu();"></div>'); 
		};
	} else { 
		$(".hidesmall").css({"display":"block"});
		$(".mobilesidemenu").remove();
		$("#windowedit").removeClass("pformsmall").addClass("pform");

	}
	showsmall();
}

function showhidesidemenu() { 
	if($("#sidemenubgcontainer").attr("data-side-open") == "1") { 
		$("#sidemenuclose").hide();
		$("#leftside").addClass("hidesmall").removeClass("leftsidemobile").hide('ease', function() { 
			$("#sidemenubgcontainer").fadeOut(400);
		});
		$("#sidemenubgcontainer").attr("data-side-open","0");
	} else { 
		$("#sidemenubgcontainer").show();
		$("#sidemenuclose").show();
		$("#leftside").removeClass("hidesmall").addClass("leftsidemobile").show('ease', function() { 
			
		});
		$("#sidemenubgcontainer").attr("data-side-open","1");
	}

}
function showsmall() { 
	if($("body").width() <= 800) { 
		$(".showsmall").css({"display":"block"});
	} else { 
		$(".showsmall").css({"display":"none"});
	}

}

function placemenus() { 
	/*
	if(hmt <= 0) { 
		hmt = Math.abs($("#header").width()) + Math.abs($("#topMainMenu").width());
	}
	mm = false;

			// $("#log").show().append("header: "+$("#header").width()+" menu: "+$("#topMainMenu").width()+" window: "+$("#headerAndMenuInner").width()+" total: "+hmt+" |");
		
		if(menup !== "below") { 
			if($("#mobilemenu").css("background-color") !== "transparent") { 
				 $("#mobilemenulinks").css({"background":$("#mobilemenu").css("background-color")});
			}
			mmmt = Math.abs(($("#headerAndMenu").height() - $("#mobilemenu").height()) / 2);
			if($("#mobilemenulinks").css("display") == "none") { 
				 if(menup == "right") { 
					 $("#mobilemenubutton").css({"text-align":"right"});
					$("#mobilemenu").css({"position":"absolute", "top":mmmt+"px", "right":"0px", "background":"transparent","border":"none"});
				 }
				 if(menup == "left") { 
					 $("#mobilemenubutton").css({"text-align":"left"});
					$("#mobilemenu").css({"position":"absolute", "top":mmmt+"px", "left":"0px", "background":"transparent","border":"none"});
				 }
			}
			if(do_not_mobile_menu_when_menu_runs_into_header !== 1) { 
				if(hmt >= $("#headerAndMenuInner").width()) { 
					mm = true;
				}
			}
		}
		if($("body").width() <= 800) { 
			mm = true;
		}

	if(mm == true) { 
		$("#topMainMenuContainer").hide();
		$("#mobilemenu").show();
		//$("#main_container").css({"margin-top":$("#headerAndMenu").height()+"px"}); 
	} else { 
		$("#topMainMenuContainer").show();
		$("#mobilemenu").hide();
		//$("#main_container").css({"margin-top":"auto"}); 
	}
	*/
}

$(document).ready(function(){
	$(".defaultfield").bind('focus', function() { 
		if($(this).val() == $(this).attr("title")) { 
			$(this).val("");
		}
	});
	$('.defaultfield').bind('blur', function() { 
		if($(this).val() == "") { 
			$(this).val($(this).attr("title"));
		}
	});



	$(".confirmdelete").click(function() { 
		$("#confirm-link").attr("href",$(this).attr("href"));
		$("#confirm-link").attr("target",$(this).attr("target"));
		$("#confirm-title").html($(this).attr("confirm-title"));
		$("#confirm-message").html($(this).attr("confirm-message"));

		$("#pagewindowbgcontainer").fadeIn(100, function() { 
			$("#confirm-link").focus();
				$("#confirmdelete").css({"display":"none", "visibility":"visible", "top":"300px", "z-index":"500"});
				$("#confirmdelete").fadeIn(100);
		});

		return false;
	});


	$(".confirmdeleteoptions").click(function() { 
		$("#confirm-options-title").html($(this).attr("confirm-title"));
		$("#confirm-options-message").html($(this).attr("confirm-message"));
		$("#option-link-1").attr("href",$(this).attr("option-link-1"));
		$("#option-link-1-text").html($(this).attr("option-link-1-text"));
		$("#option-link-2").attr("href",$(this).attr("option-link-2"));
		$("#option-link-2-text").html($(this).attr("option-link-2-text"));

		$("#pagewindowbgcontainer").fadeIn(100, function() { 
				$("#confirmdeleteoptions").css({"display":"none", "visibility":"visible", "top":"300px", "z-index":"500"});
				$("#confirmdeleteoptions").fadeIn(100);
		});

		return false;
	});


});

function newwizard() { 
	windowloading();
	$("#pagewindowbgcontainer").fadeIn(100);
	$("#windowedit").css({"top":$(window).scrollTop()+50+"px"});
		$.get("new-wiz.php?noclose=1&nofonts=1&nojs=1", function(data) {
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				windowloadingdone();
			});
		});

}
function createorder() { 
	pagewindowedit("store/order-create.php?noclose=1&nofonts=1&nojs=1");
}
function sytistupdate() { 
	pagewindowedit("sytist-update.php?noclose=1&nofonts=1&nojs=1");
}
function duplicatepage(date_id) { 
	pagewindowedit("news/news-duplicate-page.php?do=editOption&noclose=1&nofonts=1&date_id="+date_id);
}
function editcontract(contract_id,p_id,template) { 
	pagewindowedit("people/contract-edit.php?do=editPeople&noclose=1&nofonts=1&nojs=1&contract_id="+contract_id+"&p_id="+p_id+"&template="+template);
}
function editcontracttemplate(contract_id,p_id,template) { 
	pagewindowedit("people/contract-edit-template.php?do=editPeople&noclose=1&nofonts=1&nojs=1&contract_id="+contract_id+"&p_id="+p_id+"&template="+template);
}
function peoplenotes(note_id,p_id) { 
	pagewindowedit("people/people-note.php?do=editPeople&noclose=1&nofonts=1&nojs=1&note_id="+note_id+"&p_id="+p_id);
}

function emailcontract(contract_id,p_id) { 
	pagewindowedit("people/people-email-contract.php?do=editPeople&noclose=1&nofonts=1&nojs=1&contract_id="+contract_id+"&p_id="+p_id);
}

function changepricelist(date_id) { 
	pagewindowedit("w-change-pricelist.php?do=editOption&noclose=1&nofonts=1&date_id="+date_id);
}
function sweetness(show_id,date_id,cat_id) { 
	pagewindowedit("sweetness.php?noclose=1&nofonts=1&nojs=1&feat_page_id="+date_id+"&show_id="+show_id+"&feat_cat_id="+cat_id);
}
function sendregistry(date_id) { 
	pagewindowedit("news/news-email-registry.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}

function editcoupon(code_id,date_id) { 
	pagewindowedit("store/discounts/coupon-edit.php?noclose=1&nofonts=1&nojs=1&code_id="+code_id+"&code_date_id="+date_id);
}
function productphotos(pp_id) { 
	pagewindowedit("store/photoprods/w-product-photos.php?noclose=1&nofonts=1&nojs=1&pp_id="+pp_id);
}

function editlink(link_id) { 
	pagewindowedit("w-edit-link.php?noclose=1&nofonts=1&nojs=1&link_id="+link_id);
}
function uploadfavicon() { 
	pagewindowedit("look/look-favicon.php?noclose=1&nofonts=1&nojs=1");
}

function movetoamazon(date_id) { 
	pagewindowedit("w-move-amazon.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}
function uploadpasscodes(date_id) { 
	pagewindowedit("w-photos-passcodes.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}

function editstoreoption(opt_date,opt_id) { 
	pagewindowedit("w-product-options.php?do=editOption&noclose=1&nofonts=1&nojs=1&opt_date="+opt_date+"&opt_id="+opt_id);
}
function dpmenu() { 
   $(".dpmenu").hover(function(){    
	   $(this).css({"z-index": "20"});
		$(this).children("div").stop(true,true).slideDown(100);
	
	}, function(){
   	   $(this).css({"z-index": "10"});

		$(this).children("div").stop(true,true).slideUp(100);
	
	});

   $(".dpmenub").hover(function(){    
	   $(this).css({"z-index": "20"});
		$(this).children("div").stop(true,true).slideDown(100);
	
	}, function(){
   	   $(this).css({"z-index": "10"});

		$(this).children("div").stop(true,true).slideUp(100);
	
	});

}

function editsubscription(sub_id) { 
	pagewindowedit("subscriptions/sub-edit.php?noclose=1&nofonts=1&nojs=1&sub_id="+sub_id);
}
function subscriptioninvoice(sub_id) { 
	pagewindowedit("subscriptions/sub-invoice.php?noclose=1&nofonts=1&nojs=1&sub_id="+sub_id);
}

function orderstatus(status_id,order_id) { 
	pagewindowedit("store/order-status.php?noclose=1&nofonts=1&nojs=1&status_id="+status_id+"&order_id="+order_id,600);
}
function paymentschedule(order_id) { 
	pagewindowedit("store/orders/order-payment-schedule.php?noclose=1&nofonts=1&nojs=1&order_id="+order_id,600);
}

function editorder(order_id) { 
	pagewindowedit("store/order-edit.php?noclose=1&nofonts=1&nojs=1&order_id="+order_id,900);
}

function editorderitem(cart_id,order_id) { 
	pagewindowedit("store/order-edit-item.php?noclose=1&nofonts=1&nojs=1&cart_id="+cart_id+"&order_id="+order_id,900);
}

function editpeople(p_id) { 
	pagewindowedit("people/people-edit.php?do=editPeople&noclose=1&nofonts=1&nojs=1&p_id="+p_id);
}
function editmaillist(em_id) { 
	pagewindowedit("people/people-mail-list-edit.php?do=editPeople&noclose=1&nofonts=1&nojs=1&em_id="+em_id);
}

function photocartclients() { 
	pagewindowedit("people/people-from-photocart.php?noclose=1&nofonts=1&nojs=1&p_id="+p_id);
}

function editcredit(p_id,credit_id) { 
	pagewindowedit("people/people-credit.php?noclose=1&nofonts=1&nojs=1&p_id="+p_id+"&credit_id="+credit_id);
}
function addprintcredit(p_id) { 
	pagewindowedit("people/people-print-credit.php?noclose=1&nofonts=1&nojs=1&p_id="+p_id);
}

function editexpense(exp_id,order_id) { 
	pagewindowedit("reports/expense-edit.php?noclose=1&nofonts=1&nojs=1&exp_id="+exp_id+"&exp_order="+order_id);
}
function viewcustomercart(cart_client,cart_session) { 
	pagewindowedit("store/store_admin_view_cart.php?noclose=1&nofonts=1&nojs=1&cart_client="+cart_client+"&cart_session="+cart_session);
}

function editfield(ff_form,ff_id) { 
	pagewindowedit("forms/form-field-edit.php?do=editField&noclose=1&nofonts=1&nojs=1&ff_id="+ff_id+"&ff_form="+ff_form);
}
function editform(form_id) { 
	pagewindowedit("forms/form-edit.php?do=editForm&noclose=1&nofonts=1&nojs=1&form_id="+form_id);
}

function confirmdeletecancel() {
	$("#confirmdelete").fadeOut(100, function() { 
	$("#pagewindowbgcontainer").fadeOut(100);
	});

}
function confirmdeleteoptionscancel() {
	$("#confirmdeleteoptions").fadeOut(100, function() { 
	$("#pagewindowbgcontainer").fadeOut(100);
	});

}

function deleteProductFile(date_id) { 
	$.get("admin.actions.php?action=deleteProductFile&date_id="+date_id+"", function(data) {
		$("#downloadfile").html(data);
	});
	
}

function addFont(name,css_id) { 
	$.get("admin.actions.php?action=addFont&font="+name+"&css_id="+css_id+"", function(data) {
		$("#googlefonts").html(data);
		$("#savefontchanges").slideDown(200);
		$("#changesmade").val("1");
	});
}
function rotatephotos(dir) { 
	$("#rotatephotos").html("<img src='graphics/loading2.gif'>");
	$.get("admin.actions.php?action=rotate&direction="+dir+"", function(data) {
	location.reload();
	});
}

function removeFont(id,css_id) { 
	$.get("admin.actions.php?action=removeFont&font="+id+"&css_id="+css_id+"", function(data) {
		$("#googlefonts").html(data);
		$("#savefontchanges").slideDown(200);
		$("#changesmade").val("1");
	});
}

function makePreviewPhoto(pic_id,date_id,sub_id) { 
	$.get("admin.actions.php?action=makePreviewPhoto&pic_id="+pic_id+"&date_id="+date_id+"&sub_id="+sub_id+"", function(data) {
		showSuccessMessage("Preview Photo Updated");
	});


}


function viewPhoto(pic_id,gal_id,width,height,position) { 
	$("#photoBGContainer").fadeIn('fast');
	$("#photo-main").fadeIn('fast');
	var currentphoto = $("#vinfo").attr("currentViewPhoto");
	$("#vinfo").attr("disableNav","1");

	if(document.getElementById('viewPhotoContainer').style.display !== "block") { 
		$("#viewPhotoContainer").fadeIn('fast');
	}
	var moveleft = width / 2;
	loadthis = 'photo.php?image='+pic_id+'';
	stillload  = setTimeout("pageLoading()",500);
	var totalphotos = $("#vinfo").attr("totalPhotos");
	var perpage = $("#vinfo").attr("thumbsPerPage");
	page = Math.ceil(position / perpage);
	if(document.getElementById('photoview-'+pic_id+'-container')) {
		resizeImgToContainer($("#photoview-"+pic_id),"viewPhoto");
		$("#photoview-"+currentphoto+"-container").fadeOut('fast');
		$("#photoview-"+pic_id+"-container").fadeIn('fast', function() {
		$("#vinfo").attr("disableNav","0");
			loadPhotoEdit(pic_id,gal_id);
		  });
		$("#vinfo").attr("currentViewPhoto",pic_id);
		pageDoneLoading();
		if (typeof stillload!=="undefined") {
			clearInterval(stillload);
		 }
		resizeImgToContainer($("#photoview-"+pic_id),"viewPhoto");

	} else {
		$.get("admin.actions.php?action=getphotofile&pic_id="+pic_id, function(data) {
			imgsrc = data;
			$('#viewPhoto').append('<div id="photoview-'+pic_id+'-container" class="photoViewContainer"><img class="photoView" id="photoview-'+pic_id+'" src="'+imgsrc+'"></div>');
			$('#photoview-'+pic_id).load(function() {
				loadPhotoEdit(pic_id,gal_id,true);
				pageDoneLoading();
				if (typeof stillload!=="undefined") {
					clearInterval(stillload);
				 }
			});
		});

		}
	}
function pageDoneLoading() { 
//	alert("done");
	$("#loadingPage").fadeOut('fast');
}

function loadPhotoEdit(pic_id,gal_id,isnew) { 
	var curPage = $("#vinfo").attr("thumbPageID");
	curPage = parseInt(curPage);
	var date_id = $("#vinfo").attr("did");
	var sub_id = $("#vinfo").attr("sub_id");
	var keyWord = $("#vinfo").attr("keyWord");
	var key_id = $("#vinfo").attr("key_id");
	var orderBy = $("#vinfo").attr("orderBy");
	var pic_camera_model = $("#vinfo").attr("pic_camera_model");
	var pic_upload_session = $("#vinfo").attr("pic_upload_session");
	var orientation = $("#vinfo").attr("orientation");
	var untagged = $("#vinfo").attr("untagged");
	var acdc = $("#vinfo").attr("acdc");
	var view = $("#vinfo").attr("view");
	var p_id = $("#vinfo").attr("p_id");
	var nextPage = parseInt(curPage + 1);
	var pic_client = $("#vinfo").attr("pic_client");
	$.get("photo.edit.frame.php?pic_id="+pic_id+"&pic_client="+pic_client+"&p_id="+p_id+"&date_id="+date_id+"&sub_id="+sub_id+"&pic_camera_model="+pic_camera_model+"&key_id="+key_id+"&keyWord="+keyWord+"&orderBy="+orderBy+"&passcode="+$("#vinfo").attr("passcode")+"&view="+view+"&pic_upload_session="+pic_upload_session+"&orientation="+orientation+"&acdc="+acdc+"", function(data) {
		$("#viewPhotoInfo").html(data);
		navStatus();

		if(isnew == true) { 
			if($("#vinfo").attr("currentViewPhoto") > 0) { 
				if($("#vinfo").attr("currentViewPhoto")!==pic_id) { 
					$("#photoview-"+$("#vinfo").attr("currentViewPhoto")+"-container").fadeOut(300);
				}
			}

			$("#photoview-"+pic_id).attr('ww',$("#photoview-"+pic_id).width());
			$("#photoview-"+pic_id).attr('hh',$("#photoview-"+pic_id).height());
			resizeImgToContainer($("#photoview-"+pic_id),"viewPhoto");
			$("#photoview-"+pic_id+"").fadeIn(300, function() {
//			$('#photoThumb-'+pic_id).scrollView();
			$("#vinfo").attr("disableNav","0");
			  });
			$("#vinfo").attr("currentViewPhoto",pic_id);
		}
	});
}
function closeBGContainer() { 

	if($("#vinfo").attr("view") == "tray") { 
			$("#vinfo").attr("view","");
	}
	$("#photoBGContainer").fadeOut('fast');
	$("#photo-main").fadeOut('fast');
	$("#viewPhotoContainer").fadeOut('fast');
	window.location.href = '#page=thumbs';
}

function resizeImgToContainer(bgImg,where) {
	noupsize = 1;
	captionPhotoOn = 0;
	showScroll = 0;
	landscapeLeft = 0;
	portraitLeft = 0;
	containheight = 1;
	containwidth = 1;
	fullscreenmenu = 0;
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
		var winwidth = $("#"+where).width() - 10;
		var winheight = $("#viewPhotoContainer").height() - 10;
		var thewinheight = $("#viewPhotoContainer").height();
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
	if(showScroll == 1) { 
		if(disableshowscroll == false) { 
			$("#slideDown").show();
			$("#slideUp").show();
			$("#slideCenter").show();
			$("#slideCenter").unbind('click');
			$("#slideCenter").bind('click', function() { fitToScreen() });
		}
		
	} else {
		$("#slideDown").hide();
		$("#slideUp").hide();
		$("#slideCenter").hide();
	}
	if(captionPhotoOn == 1) { 
		setTimeout("moveCaption()", 500);
	}
	newml = $(bgImg).outerWidth() / 2;
	newmt = ((thewinheight -  $(bgImg).height()) / 2);
//	bgImg.css('margin-left','-'+newml+'px');
	bgImg.css('margin-top',''+newmt+'px');
//	alert(newmt+' - '+winheight +' - '+ $(bgImg).outerHeight());

} 

function showLoadingMore() { 
		$("#loadingMore").fadeIn(300);
}

function hideLoadingMore() { 
		$("#loadingMore").fadeOut(300);
}

function pageLoading() { 
	$("#loadingPage").fadeIn('fast');
}

function navPhotos(n) { 
	var disablenav = 	$("#vinfo").attr("disableNav");

	if(disablenav!== '1') { 
	//	alert(disablenav);
		if(n == "prev") { 
			w = "prevPhotoInfo";
		}
		if(n == "next") { 
			w = "nextPhotoInfo";
		}
		var pic_id = parseInt($("#"+w).attr("pid") );
		var gal_id = parseInt($("#"+w).attr("gid") );
		var imgwidth = parseInt($("#"+w).attr("ww") );
		var imgheight = parseInt($("#"+w).attr("hh") );
		var position = parseInt($("#"+w).attr("position") );
		if(pic_id > 0) { 
			window.location.href = '#image='+pic_id+'';
		}
	}
}

function navStatus() { 
	if($("#prevPhotoInfo").attr("pid") <= 0) {
		$("#prevPhoto").hide();
	} else { 
		$("#prevPhoto").show();
	}
	if($("#nextPhotoInfo").attr("pid") <= 0) {
		$("#nextPhoto").hide();
	} else { 
		$("#nextPhoto").show();
	}
}

function arrowNav() { 
	document.onkeydown = function(evt) {
		evt = evt || window.event;
		switch (evt.keyCode) {
			case 39:
			navPhotos('next');
				break;
			case 37:
			navPhotos('prev');
			break;
			case 27:
			closeBGContainer();
			break;
			case 80:
		//	toggleSpaceBar();
			break;
		}
	};
}



function openLargeFrame(link) {
	$("#shadepagecontainer").fadeIn('fast');
	$("#shadepagecontent").fadeIn('fast');
	$("#shadepageenter").fadeIn('fast');
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowlargeframe id=windowlargeframe src='+link+' frameborder=0>';
}
function openFrame(link) {
	$("#shadepagecontainer").fadeIn('fast');
	$("#shadepagecontent").fadeIn('fast');
	$("#shadepageenter").fadeIn('fast');
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframe id=windowframe src='+link+' frameborder=0>';
	$("#uploadButton").hide();
}
function openFrameFull(link) {
	$("#shadepagecontainer").fadeIn('fast');
	$("#shadepagecontent").fadeIn('fast');
	$("#shadepageenter").fadeIn('fast');
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframefull id=windowframefull src='+link+' frameborder=0>';
	$("#uploadButton").hide();
}


function closeFrame() {
	window.parent.$("#shadepagecontainer").fadeOut('fast');
	window.parent.$("#shadepagecontent").fadeOut('fast');
	window.parent.$("#shadepageenter").fadeOut('fast');
	window.parent.$("#windowframe").fadeOut('fast');
}
function closeWindow() {
	window.parent.$("#shadepagecontainer").fadeOut('fast');
	window.parent.$("#shadepagecontent").fadeOut('fast');
	window.parent.$("#shadepageenter").fadeOut('fast');
	window.parent.$("#windowframe").fadeOut('fast');
	window.parent.$("#uploadButton").show();
}


function checkForm(classname,buttonid) { 
	var rf = false;
	var mes;
	if(!classname) { 
		classname = ".required";
	}
	if(!buttonid) { 
		buttonid = "submitButton";
	}

	$(classname).each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('requiredFieldEmpty');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('requiredFieldEmpty');
		}
	} );
	if(rf == true) { 
		showErrorMessage("You have required fields empty which are highlighted.");	
		setTimeout("hideErrorMessage()", 4000);
		return false;
	} else { 
		if($("#submited").val() == "1") { 
			return false;
		} else { 
			$("#submited").val('1');


			$("#savingdata").show();
			$('#'+buttonid).val("saving...");
		//	$('#'+buttonid).removeClass("submit").addClass("submitsaving");
//			$('#submitButtonLoading').show();
			//$("theForm").submit();
			$('#'+buttonid).attr('disabled', 'disabled');
			return true;
		}
	}
}

function showSuccessMessage(message) { 
//	alert(window.parent.$("#successMessage").css('display') );
	if(window.parent.$("#successMessage").css('display') !== "none") { 
		window.parent.$("#successMessage").hide();
	}

	if(message!=='') { 
		window.parent.$("#successMessage").html(message);
	}
	window.parent.$("#successMessage").fadeIn(300);
}

function hideSuccessMessage() { 
		window.parent.$("#successMessage").fadeOut(300,function() {
		window.parent.$("#successMessage").empty();
		  });
}
function showErrorMessage(message) { 
	if(message!=='') { 
		window.parent.$("#errorMessage").html(message);
	}
	window.parent.$("#errorMessage").fadeIn(300);
}

function hideErrorMessage() { 
		window.parent.$("#errorMessage").fadeOut(300);
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
			tposition = tposition - $("#"+new_id).height() -12;
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


function myinputtips(theclass, cssclass){

	$(theclass).on({

		mouseover: function() {
//			alert(this.id);
			this.tip = this.title;

		var new_id = this.id+'-tip';
		var lposition = $(this).offset().left - $(window).scrollLeft();
		var tposition = $(this).offset().top- $(window).scrollTop();
		tposition = tposition - ($(this).height() / 2);
//		lposition = lposition + ($(this).width() / 2);
		//alert(lposition);
		if((!document.getElementById(new_id))&&(this.title!=='')){
			this.tip = this.title;
			$("body").append(
				'<div class="'+cssclass+'" id="'+this.id+'-tip">'
					+'<div>'
						+this.tip
					+'</div>'
				+'</div>'
			);
			this.width = $(this).width();
			this.title = "";
			old_title = this.tip;
			$("#"+new_id).fadeIn(200);
			tposition = tposition - $("#"+new_id).height() + $("#"+new_id).height() + 40;
//			lposition = lposition - ($("#"+new_id).width() / 2);
			if(lposition < 0) { 
				lposition = 0;
			}
//			alert(lposition+" X "+tposition+" "+old_title);

			$("#"+this.id+"-tip").css({left:lposition, top:tposition});
			}
		},
		mouseout: function() {
			$("#"+this.id+"-tip").hide();
			$("#"+this.id+"-tip").remove();
//			$(this).find('div').hide();
				this.title = old_title;
			}
		});
}


function setOrderNumber() { 
	var new_number = $("#new_number").val();
	$.get("admin.actions.php?action=changeOrderNumber&new_number="+new_number+"", function(data) {
		data = $.trim(data);
		if(data == "fail") { 
			alert("That  number is less than your last order number. You must enter a number higher than the last order number.");
		} else { 
			alert("Success. The next order will have the order number of "+new_number+". ");
		}
	});
	return false;
}


function removeNoNotes() { 
	$("#nonotes").remove();
	clearInterval(homerefresh);
}
function addnotes(id) { 
	$("#noteloading").show();
	var notes = $("#order_note").html();
	$.post("admin.actions.php?action=ordernotes",{ order_notes: notes, who: "tim", order_id:id },	function (data) { 
		$("#noteloading").hide();
		$("#noteupdated").show();
//		alert(data);
//		$("#"+divid).html(data);
	 } );
	return false;
}
function addhomenotes() { 
	$("#noteloading").show();
	var notes = $("#admin_notes").html();
	$.post("admin.actions.php?action=homenotes",{ notes: notes },	function (data) { 
		$("#noteloading").hide();
		$("#noteupdated").show();
//		alert(data);
//		$("#"+divid).html(data);
	 } );
	return false;
}


	function checkTag(tag_id) { 
		if($("#e-tag-"+tag_id).attr("checked")) { 
			$("#span-tag-"+tag_id).attr("class", "tagselected");
		} else { 
			$("#span-tag-"+tag_id).attr("class", "tagunselected");
		}
}

function removePhotoFromBlog(pic_id,bp_id,next) { 
	$.get("admin.actions.php?do=photos&action=removeBlogPhoto&pic_id="+bp_id+"", function(data) {
		$("#photoThumb-"+pic_id).fadeOut(200);
	});
	if(next == "close") { 
		hideBlogPhoto();
	}
	confirmdeleteoptionscancel();
}
function submitPopupForm(file,classname) { 
	var fields = {};
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] += ","+$this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}

		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}
	});
	$.post(file, fields,	function (data) { 
		$("#submitinfo").html(data);
	 } );
	return false;
}
function sortItems(id,order_id,action) { 
	var sortInput = jQuery('#'+order_id);
	var submit = jQuery('#autoSubmit');
	var messageBox = jQuery('#message-box');
	var list = jQuery('#'+id);
	/* create requesting function to avoid duplicate code */
	var request = function() {
		jQuery.ajax({
			beforeSend: function() {
				messageBox.text('Updating .....');
			},
			complete: function() {
				messageBox.text('Display order updated.');
			},
			data: 'sort_order=' + sortInput[0].value + '&amp;ajax=' + submit[0].checked + '&amp;do_submit=1&amp;byajax=1&amp;action='+action+'', //need [0]?
			type: 'post',
			url: 'admin.actions.php?action='+action+''
		}).done(function( msg ) {
 // alert( "Data Saved: " + msg );
	});

	};
	/* worker function */
	var fnSubmit = function(save) {
		var sortOrder = [];
		list.children('li').each(function(){
			sortOrder.push(jQuery(this).data('id'));
		});
		sortInput.val(sortOrder.join(','));
	//	console.log(sortInput.val());
		if(save) {
			request();
		}
	};
	/* store values */
	list.children('li').each(function() {
		var li = jQuery(this);
		li.data('id',li.attr('title')).attr('title','');
	});
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function() {
			fnSubmit(submit[0].checked);
		}
	});
//	list.disableSelection();
	/* ajax form submission */
	jQuery('#dd-form').bind('submit',function(e) {
		if(e) e.preventDefault();
		fnSubmit(true);
	});
}

function pagewindowedit(page,width,outerclose) { 
	windowloading();
	$("#pagewindowbgcontainer").fadeIn(100);
	$("#windowedit").css({"top":$(window).scrollTop()+50+"px"});
	if(width > 0) { 
		$("#windowedit").css({"width":width+"px","margin-left":"-"+width / 2+"px"});
	}
		$.get(page, function(data) {
			if(outerclose == 1) { 
				$('#pagewindowbgcontainer').click(function() {
					closewindowedit()
				 });

				 $('#windowedit').click(function(event){
					 event.stopPropagation();
				 });
			}
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				$("#windoweditclose").show();
				windowloadingdone();
			});
		});
}

function pagewindoweditnoloading(page,width,outerclose) { 
	$("#pagewindowbgcontainer").fadeIn(100);
	$("#windowedit").css({"top":$(window).scrollTop()+50+"px"});
	if(width > 0) { 
		$("#windowedit").css({"width":width+"px","margin-left":"-"+width / 2+"px"});
	}
		$.get(page, function(data) {
			if(outerclose == 1) { 
				$('#pagewindowbgcontainer').click(function() {
					closewindowedit()
				 });

				 $('#windowedit').click(function(event){
					 event.stopPropagation();
				 });
			}
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				$("#windoweditclose").show();

			});
		});
}

function closewindowedit() { 
	$("#windowedit").slideUp(200, function() { 
		$("#pagewindowbgcontainer").fadeOut(100);
		$("#windoweditclose").hide();

	});
}


function windowloading() { 
	$("#windowloading").fadeIn(100);
}
function windowloadingdone() { 
	$("#windowloading").hide();
}

function regkey(key_id) { 
	pagewindowedit("customers/key-edit.php?noclose=1&nofonts=1&nojs=1&key_id="+key_id);
}

























function swapDisplay(id) { 
	if(document.getElementById(id).style.display == "none") { 
		document.getElementById(id).style.display = "block";
	} else { 
		document.getElementById(id).style.display = "none";
	}
}


function slidedown(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display != "none")
                return; // cannot slide down something that is already visible

        moving[objname] = true;
        dir[objname] = "down";
        startslide(objname);
}

function slideup(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display == "none")
                return; // cannot slide up something that is already hidden

        moving[objname] = true;
        dir[objname] = "up";
        startslide(objname);
}

function startslide(objname){
        obj[objname] = document.getElementById(objname);

        endHeight[objname] = parseInt(obj[objname].style.height);
        startTime[objname] = (new Date()).getTime();

        if(dir[objname] == "down"){
                obj[objname].style.height = "1px";
        }

        obj[objname].style.display = "block";

        timerID[objname] = setInterval('slidetick(\'' + objname + '\');',timerlen);
}

function slidetick(objname){
        var elapsed = (new Date()).getTime() - startTime[objname];

        if (elapsed > slideAniLen)
                endSlide(objname)
        else {
                var d =Math.round(elapsed / slideAniLen * endHeight[objname]);
                if(dir[objname] == "up")
                        d = endHeight[objname] - d;

                obj[objname].style.height = d + "px";
        }

        return;
}

function endSlide(objname){
        clearInterval(timerID[objname]);

        if(dir[objname] == "up")
                obj[objname].style.display = "none";

        obj[objname].style.height = endHeight[objname] + "px";

        delete(moving[objname]);
        delete(timerID[objname]);
        delete(startTime[objname]);
        delete(endHeight[objname]);
        delete(obj[objname]);
        delete(dir[objname]);

        return;
}

function toggleSlide(objname){
  if(document.getElementById(objname).style.display == "none"){
    // div is hidden, so let's slide down
    slidedown(objname);
  }else{
    // div is not hidden, so slide up
    slideup(objname);
  }
}

   function checkAll(theForm, cName) {
    for (i=0,n=theForm.elements.length;i<n;i++)
     if (theForm.elements[i].className.indexOf(cName) !=-1)
      if (theForm.elements[i].checked == true) {
       theForm.elements[i].checked = false;
      } else {
       theForm.elements[i].checked = true;
      }
   }





function createBillboardSlideshow(bill_id) {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
	if(document.getElementById('uploadButton')) { 
	    document.getElementById('uploadButton').style.display = 'none';
	}
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframe id=windowframe src=look/billboard.create.php?bill_id='+bill_id+' frameborder=0>';
}


function reviseCaption(pic_id) {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
    document.getElementById('uploadButton').style.display = 'none';
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframe id=windowframe src=photos/photo.edit.caption.php?pic_id='+pic_id+' frameborder=0>';
}

function editCSS(css_id,snip_id) {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframe id=windowframe src=look/look.edit.css.snippet.php?css_id='+css_id+'&snip_id='+snip_id+' frameborder=0>';
}



function manageTags() {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
    document.getElementById('uploadButton').style.display = 'none';
	document.getElementById('shadepageenter').innerHTML = '<iframe name=windowframe id=windowframe src=tags.manage.php frameborder=0>';
}


function addCheck(theForm)

{ 
    Form=document.theForm; 
	Form.submission.disabled = true;
	Form.submission.value = 'please wait .....';
	Form.submit();  
}

function justOpen(div1,div2,cl,row) {
    document.getElementById(div1).style.display = 'block';
    document.getElementById(div2).style.display = 'block';
    document.getElementById(cl).style.display = 'none';
	document.getElementById(row).style.fontWeight = 'bold';

}
function justClose(div1,div2,cl,row) {
    document.getElementById(div1).style.display = 'none';
    document.getElementById(div2).style.display = 'block';
    document.getElementById(cl).style.display = 'none';
	document.getElementById(row).style.fontWeight = 'normal';
}

function openHomeStats(openDiv,closeDivs) {
    document.getElementById(openDiv).style.display = 'block';
    document.getElementById(openDiv + "Selected").style.display = 'block';
    document.getElementById(openDiv + "Link").style.display = 'none';
	var closeDiv=closeDivs.split("|"); 
	for ( var i in closeDiv ) {
	 document.getElementById(closeDiv[i]).style.display = 'none';
	 document.getElementById(closeDiv[i]+"Selected").style.display = 'none';
	 document.getElementById(closeDiv[i]+"Link").style.display = 'block';
	}
}

function showThumbMenu(menDiv,nameDiv) {
    document.getElementById(menDiv).style.display = 'block';
}
function hideThumbMenu(menDiv,nameDiv) {
    document.getElementById(menDiv).style.display = 'none';
}

function showHide(div) { 
	$("#"+div).slideToggle(200);
}


function showPhotoData(pic_id) {
    document.getElementById('photo-data-'+pic_id).style.display = 'block';
}
function hidePhotoData(pic_id) {
    document.getElementById('photo-data-'+pic_id).style.display = 'none';
}


function photoColors(pic_id) {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
	document.getElementById('shadepageenter').innerHTML = '<iframe name=cropframe id=cropframe src=photo_colors.php?pic_id='+pic_id+' frameborder=0>';
}

function closePhotoColors() {
    parent.document.getElementById('shadepagecontainer').style.display = 'none';
    parent.document.getElementById('shadepagecontent').style.display = 'none';
    parent.document.getElementById('shadepageenter').style.display = 'none';
    parent.document.getElementById('cropframe').style.display = 'none';
	if(parent.document.getElementById('uploadButton')){
		parent.document.getElementById('uploadButton').style.display = 'block';
	}
}


function cropBlogPreview(pic_id,date_id) {
	 if(document.getElementById('shadepagecontainer').style.display == "none") {
    document.getElementById('shadepagecontainer').style.display = 'block';
    document.getElementById('shadepagecontent').style.display = 'block';
    document.getElementById('shadepageenter').style.display = 'block';
	}
	document.getElementById('shadepageenter').innerHTML = '<iframe name=cropframe id=cropframe src=blog_crop_preview.php?pic_id='+pic_id+'&date_id='+date_id+' frameborder=0>';
}

function closeBlogCropping() {
    parent.document.getElementById('shadepagecontainer').style.display = 'none';
    parent.document.getElementById('shadepagecontent').style.display = 'none';
    parent.document.getElementById('shadepageenter').style.display = 'none';
    parent.document.getElementById('cropframe').style.display = 'none';
	if(parent.document.getElementById('uploadButton')){
		parent.document.getElementById('uploadButton').style.display = 'block';
	}
}


function showBlogPhoto(menDiv,photoURL,maxWidth,maxHeight,start,vars) {
	var add_string = "";
document.onkeydown = function(evt) {
    evt = evt || window.event;
    switch (evt.keyCode) {
        case 39:
		if( parent.document.getElementById('prev-thumb-1')) {
			if(parent.document.getElementById('photo-loading').style.display == "none") { 

				if(parent.document.getElementById('formfocus').value !=='1') { 

					a = parent.document.getElementById('prev-thumb-1').value;
					b = parent.document.getElementById('prev-thumb-2').value;
					c = parent.document.getElementById('prev-thumb-3').value;
					d = parent.document.getElementById('prev-thumb-4').value;
					e = parent.document.getElementById('prev-thumb-5').value;
					f = parent.document.getElementById('prev-thumb-6').value;
					nextPrevBlogPhoto(a,b,c,d,e,f);
				}
			}
		}

//            alert(act);
			break;
        case 37:
		if( parent.document.getElementById('next-thumb-1')) {
			if(parent.document.getElementById('photo-loading').style.display == "none") { 
				if(parent.document.getElementById('formfocus').value !=='1') { 
					a = parent.document.getElementById('next-thumb-1').value;
					b = parent.document.getElementById('next-thumb-2').value;
					c = parent.document.getElementById('next-thumb-3').value;
					d = parent.document.getElementById('next-thumb-4').value;
					e = parent.document.getElementById('next-thumb-5').value;
					f = parent.document.getElementById('next-thumb-6').value;
					nextPrevBlogPhoto(a,b,c,d,e,f);
				}
			}
		}
  //          rightArrowPressed();
            break;

        case 27:
		if(parent.document.getElementById("photo-insert").innerHTML !== '') { 
			hideBlogPhoto();
		}
//            alert(act);
			break;
        case 37:

    }
};


	vars_split = vars.split("|");
	for(i = 0; i < vars_split.length; i++){
		var_each = vars_split[i].split("=");
		if(var_each[0]!=="") { 
			add_string += "&"+var_each[0]+"="+var_each[1];
		}
	}
	if(start == '1') { 
		fadeIN("photo-main");
		fadeIN("photoBGContainer");
	}
if(parent.document.getElementById('uploadButton')) { 
	if(parent.document.getElementById('uploadButton').style.display == 'block') { 
		parent.document.getElementById('uploadButton').style.display = 'none';
	}
}
	parent.document.getElementById('photo-loading').style.display = 'inline';

	var image = new Image();
	image.onload = function() {
		checkPhoto();
	};
	image.src =photoURL;

	function checkPhoto() { 

		parent.document.getElementById("photo-insert").innerHTML="<img id=\"photo-the-"+menDiv+"\" src=\""+photoURL+"\" class=\"blogPhotoView\" style=\"max-height: "+maxHeight+"px; max-width: "+maxWidth+"px; cursor: pointer; opacity: 0;\" onclick=\"hideBlogPhoto('"+menDiv+"');\">";
		javascript:ajaxpage("photo.edit.frame.php?pic_id="+menDiv+""+add_string+"", "photo-info");
		var picWidth = parent.document.getElementById("photo-the-"+menDiv+"").offsetWidth;
		var picHeight = parent.document.getElementById("photo-the-"+menDiv+"").offsetHeight;
		parent.document.getElementById("photo-the-"+menDiv+"").style.visibility="hidden";
		picHeight = parent.document.getElementById("photo-the-"+menDiv+"").offsetHeight;
		parent.document.getElementById("photo-the-"+menDiv+"").style.visibility="visible";
		fade("photo-the-"+menDiv+"");

		parent.document.getElementById('photo-loading').style.display = 'none';

		var divWidth = parent.document.getElementById('photo-insert').offsetWidth;
		var divHeight = parent.document.getElementById('photo-insert').offsetHeight;
		var wp = divWidth / maxWidth;
		var hp = divHeight / maxHeight;

		if(wp <= hp) { 
			var styleWidth = "100";
			var styleHeight = "auto";

			parent.document.getElementById('photo-the-'+menDiv).style.height= 'auto';
			parent.document.getElementById('photo-the-'+menDiv).style.width= '100%';
		 }

		if(wp > hp) { 
			parent.document.getElementById('photo-the-'+menDiv).style.height= '100%';
			parent.document.getElementById('photo-the-'+menDiv).style.width= 'auto';
			var styleWidth = "auto";
			var styleHeight = "100";
		}
		var imageDisplayWidth = parent.document.getElementById('photo-the-'+menDiv).offsetWidth;
		var imageDisplayHeight = parent.document.getElementById('photo-the-'+menDiv).offsetHeight;
//		alert(imageDisplayWidth+" X "+imageDisplayHeight+" | "+maxWidth+"X"+maxHeight);
//		parent.document.getElementById("photo-insert").style.position="relative";

	
	}

}

function deletePost(date_id) { 
var r=confirm("Are you sure you want to delete this?")
if (r==true)  {
	javascript:ajaxpage("admin.actions.php?action=deleteDateInline&deleteDate="+date_id+"", "photo-actions");
    document.getElementById('dateid-'+date_id).style.display = 'none';
}
}



function nextPrevBlogPhoto(oldPhoto,newPhoto,photoURL,maxWidth,maxHeight,vars) {
	start = 0;
	showBlogPhoto(newPhoto,photoURL,maxWidth,maxHeight,start,vars);
}

function hideBlogPhoto(menDiv,nameDiv,leaveBG) {
	parent.document.getElementById("photo-insert").innerHTML= "";
	fadeOUT("photoBGContainer");
    parent.document.getElementById("photo-main").style.display = 'none';
	fadeOUT("photo-main");

if(parent.document.getElementById('uploadButton')) { 
	if(parent.document.getElementById('uploadButton').style.display == 'none') { 
		parent.document.getElementById('uploadButton').style.display = 'block';
	}
}
}
function refreshRecentPages() { 
	document.getElementById('homeRecentPages').innerHTML= "<img src='graphics/loading2.gif'>";
		javascript:ajaxpage('home_recent_pages.php', 'homeRecentPages');
}

function deletePhoto(pic_id,next) { 
	javascript:ajaxpage("admin.actions.php?do=photos&action=deletePic2&pic_id="+pic_id+"", "photo-actions");
    document.getElementById('photoThumb-'+pic_id).style.display = 'none';
	if(next == "close") { 
		closeBGContainer();
	}
	if(document.getElementById('photo-tags')) { 
		javascript:ajaxpage('tags.php', 'photo-tags');
	}
	confirmdeleteoptionscancel();
}



function batchSelectPhotos(pics,on,off) { 
	vars_split = pics.split(",");
	for(i = 0; i < vars_split.length; i++){
		if(vars_split[i] !== "") { 
		javascript:ajaxpage("batch.sessions.php?do=photos&action=holdPhoto&pic_id="+vars_split[i]+"", "thePhotos");
			if(document.getElementById('select-photo-'+vars_split[i]+'')) { 

				document.getElementById('selectedPhotos').style.display = 'block';
				document.getElementById('select-photo-'+vars_split[i]+'').style.display = 'none';
				document.getElementById('select-photo-'+vars_split[i]+'-on').style.display = 'inline';
				document.getElementById('heldPhotos').value = document.getElementById('heldPhotos').value +vars_split[i]+",";
			
			}
		}
	}
	javascript:ajaxpage("admin.actions.php?do=photos&action=showHeldPhotos", "thePhotos");
}


function defaultPhoto(pic_id,large,noshow) { 
	javascript:ajaxpage("admin.actions.php?do=photos&action=defaultPhoto&pic_id="+pic_id+"&pic_id="+pic_id+"", "defaultPhotoSelect");
	javascript:ajaxpage("admin.actions.php?do=photos&action=defaultPhoto&pic_id="+pic_id+"&pic_id="+pic_id+"", "defaultPhotoSelect");
	document.getElementById('default-photo-'+pic_id+'').style.display = 'none';
	document.getElementById('default-photo-'+pic_id+'-on').style.display = 'inline';
}


function selectPhoto(pic_id,large,picnum) { 
	var selectbatch;
	var pics;
	if(shifton == 1) { 
	//	$("#theActions").html();
	if(last_pic_selected < picnum) { 
		while (last_pic_selected <= picnum) { 
			pics += $('#'+last_pic_selected+'-pic').attr('pic_id')+",";
			$('#select-photo-'+$("#"+last_pic_selected+'-pic').attr('pic_id')).hide();
			$('#select-photo-'+$("#"+last_pic_selected+'-pic').attr('pic_id')+"-on").show();
			last_pic_selected++;
		}
	} else { 
		while (last_pic_selected >= picnum) { 
			pics += $('#'+last_pic_selected+'-pic').attr('pic_id')+",";
			last_pic_selected--;
		}


	}
		javascript:ajaxpage("admin.actions.php?do=photos&action=holdPhoto&pics="+pics+"", "thePhotos");
	//	$("#theActions").append(pics);
	document.getElementById('heldPhotos').value = document.getElementById('heldPhotos').value + pics+",";

	} else { 
		javascript:ajaxpage("admin.actions.php?do=photos&action=holdPhoto&pic_id="+pic_id+"&picnum="+picnum+"", "thePhotos");
		document.getElementById('heldPhotos').value = document.getElementById('heldPhotos').value + pic_id+",";

	}
	$('#select-photo-'+pic_id).hide();
	$('#select-photo-'+pic_id+"-on").show();

    document.getElementById('selectedPhotos').style.display = 'block';
	if(large == 1) { 
		document.getElementById('select-photo-'+pic_id+'-large').style.display = 'none';
		document.getElementById('select-photo-'+pic_id+'-on-large').style.display = 'inline';
	}
	last_pic_selected = picnum;

}

function unSelectPhoto(pic_id,large) { 
	var add_string = "";
	javascript:ajaxpage("admin.actions.php?do=photos&action=unSelectPhoto&pic_id="+pic_id+"", "thePhotos");
	var held_photos = document.getElementById('heldPhotos').value;
	vars_split = held_photos.split(",");
	for(i = 0; i < vars_split.length; i++){
		if(vars_split[i] !== pic_id) { 
			if(vars_split[i] !== "") { 
				add_string += vars_split[i]+",";
//				alert(vars_split[i]);
			}
		}
	}
	document.getElementById('heldPhotos').value = add_string;
	if(document.getElementById('select-photo-'+pic_id+'')) { 
		document.getElementById('select-photo-'+pic_id+'').style.display = 'inline';
		document.getElementById('select-photo-'+pic_id+'-on').style.display = 'none';
	}

	if(add_string == "") { 
	 document.getElementById('selectedPhotos').style.display = 'none';
	}

	if(large == 1) { 
		document.getElementById('select-photo-'+pic_id+'-large').style.display = 'inline';
		document.getElementById('select-photo-'+pic_id+'-on-large').style.display = 'none';
	}

}

function clearHeldPhotos() {
	javascript:ajaxpage("admin.actions.php?do=photos&action=clearHeldPhotos", "thePhotos");

	var held_photos = document.getElementById('heldPhotos').value;
	vars_split = held_photos.split(",");
	for(i = 0; i < vars_split.length; i++){
		if(vars_split[i] !== "") { 
			if(document.getElementById('select-photo-'+vars_split[i]+'')) { 
				document.getElementById('select-photo-'+vars_split[i]+'').style.display = 'inline';
				document.getElementById('select-photo-'+vars_split[i]+'-on').style.display = 'none';
			}
		}
	}
	document.getElementById('heldPhotos').value = "";
    document.getElementById('selectedPhotos').style.display = 'none';

}

function updateView(towhat) { 
	$("#vinfo").attr("view",towhat);
}


function deleteHeldPhotos() {
	javascript:ajaxpage("admin.actions.php?do=photos&action=deleteHeldPhotos", "thePhotos");
	confirmdeletecancel();


	var held_photos = document.getElementById('heldPhotos').value;
	vars_split = held_photos.split(",");
	for(i = 0; i < vars_split.length; i++){
		if(vars_split[i] !== "") { 
			if(document.getElementById('photoThumb-'+vars_split[i]+'')) { 
				document.getElementById('photoThumb-'+vars_split[i]+'').style.display = 'none';
			}
		}
	}
	 setTimeout("closeDiv('selectedPhotos')",800);
}

function closeDiv(div) { 
	  document.getElementById(div).style.display = 'none';
}

function getDivPosition(d){
	setInterval("getDivPositionHere('endpage')",500);
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

	if((scrollPos + screenHeight) > (divEnd - 200)) { 
		var curPage = $("#vinfo").attr("thumbPageID");
		curPage = parseInt(curPage);
		var date_id = $("#vinfo").attr("did");
		var sub_id = $("#vinfo").attr("sub_id");
		var keyWord = $("#vinfo").attr("keyWord");
		var key_id = $("#vinfo").attr("key_id");
		var orderBy = $("#vinfo").attr("orderBy");
		var pic_camera_model = $("#vinfo").attr("pic_camera_model");
		var pic_upload_session = $("#vinfo").attr("pic_upload_session");
		var orientation = $("#vinfo").attr("orientation");
		var untagged = $("#vinfo").attr("untagged");
		var acdc = $("#vinfo").attr("acdc");
		var view = $("#vinfo").attr("view");
		var nextPage = parseInt(curPage + 1);
		var pic_client = $("#vinfo").attr("pic_client");
		var p_id = $("#vinfo").attr("p_id");
		var search_date = $("#vinfo").attr("search_date");
		var search_length = $("#vinfo").attr("search_length");
		var from_time = $("#vinfo").attr("from_time");


		if(document.getElementById("page-"+nextPage)) { 
			if(document.getElementById("page-"+nextPage)){ 
			showLoadingMore();
			$.get("thumbnails.php?page="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&pic_client="+pic_client+"&keyWord="+keyWord+"&key_id="+key_id+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&p_id="+p_id+"&search_date="+search_date+"&search_length="+search_length+"&from_time="+from_time+"", function(data) {
				$("#showThumbnails").append(data);
				setTimeout(hideLoadingMore,1000);
			});
			$("#vinfo").attr("thumbPageID",nextPage);
			}
		}
	}

}

function getSubGalleries(d){
	setInterval("getSubGalleriesHere('endsubgalleries')",500);
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

	if((scrollPos + screenHeight) > (divEnd - 200)) { 
		var curPage = $("#vinfo").attr("subGalleryPageID");
		curPage = parseInt(curPage);
		var date_id = $("#vinfo").attr("did");
		var sub_id = $("#vinfo").attr("sub_id");
		var keyWord = $("#vinfo").attr("keyWord");
		var key_id = $("#vinfo").attr("key_id");
		var orderBy = $("#vinfo").attr("orderBy");
		var pic_camera_model = $("#vinfo").attr("pic_camera_model");
		var pic_upload_session = $("#vinfo").attr("pic_upload_session");
		var orientation = $("#vinfo").attr("orientation");
		var untagged = $("#vinfo").attr("untagged");
		var acdc = $("#vinfo").attr("acdc");
		var view = $("#vinfo").attr("view");
		var nextPage = parseInt(curPage + 1);
		var pic_client = $("#vinfo").attr("pic_client");
		var p_id = $("#vinfo").attr("p_id");
		var search_date = $("#vinfo").attr("search_date");
		var search_length = $("#vinfo").attr("search_length");
		var from_time = $("#vinfo").attr("from_time");


		if(document.getElementById("sub-gallery-page-"+nextPage)) { 
			if(document.getElementById("sub-gallery-page-"+nextPage)){ 
			showLoadingMore();
			$.get("sub-galleries.php?page="+nextPage+"&date_id="+date_id+"&sub_id="+sub_id+"&pic_client="+pic_client+"&keyWord="+keyWord+"&key_id="+key_id+"&orderBy="+orderBy+"&acdc="+acdc+"&view="+view+"&pic_upload_session="+pic_upload_session+"&untagged="+untagged+"&pic_camera_model="+pic_camera_model+"&orientation="+orientation+"&acdc="+acdc+"&p_id="+p_id+"&search_date="+search_date+"&search_length="+search_length+"&from_time="+from_time+"", function(data) {
				$("#sub-gallery-list").append(data);
				setTimeout(hideLoadingMore,1000);
			});
			$("#vinfo").attr("subGalleryPageID",nextPage);
			}
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





function stopArrowNav() { 
	parent.document.getElementById('formfocus').value = '1';
}

function saveInlineDataTab(field_id,table,table_field,table_update_id,table_field_id,org_value_id,save_id) {
	parent.document.getElementById('formfocus').value = '0';
	saveInlineData(field_id,table,table_field,table_update_id,table_field_id,org_value_id,save_id);
}

function saveInlineData(field_id,table,table_field,table_update_id,table_field_id,org_value_id,save_id) {
	var field_value =  escape(encodeURI(document.getElementById(field_id).value));
	var org_value =  escape(encodeURI(document.getElementById(org_value_id).value));
	if(field_value != org_value) { 
		javascript:ajaxpage("admin.actions.php?action=saveInlineData&table="+table+"&table_field="+table_field+"&field_value="+field_value+"&table_update_id="+table_update_id+"&table_field_id="+table_field_id+"&field_id="+field_id+"", save_id);
		setTimeout("fadeOUT('"+save_id+"')",2500);
		setTimeout("resetFade('"+save_id+"')",3000);
		setTimeout("removeInner('"+save_id+"')",3000);
		document.getElementById(org_value_id).value = escape(document.getElementById(field_id).value);
	}
}


function addKeyWords(fields,divname,andwhere) {

   var poststr = new Array();
   if(fields!="null") {
	   var formFields=fields.split(",");
	   var i = 0;
		for ( keyVar in formFields ) {
			i++;
			if(document.getElementById(formFields[keyVar])) {
				poststr[i] = formFields[keyVar] +"="+ escape(encodeURI( document.getElementById(formFields[keyVar]).value ));
			}
		}
		poststring = poststr.join("&");
   } else {
	   var poststring="";
   }
	makePOSTRequest("admin.actions.php?action=addKeyWords&pic_upload_session="+andwhere+"", poststring, divname);
	setTimeout("fadeOUT('"+divname+"')",2500);
	setTimeout("resetFade('"+divname+"')",3000);
	setTimeout("removeInner('"+divname+"')",3000);
	document.getElementById(fields).value = '';
	javascript:ajaxpage('tags.php', 'photo-tags');


}


function removeInner(eid) { 
	document.getElementById(eid).innerHTML="";
}
function editTag() { 
    document.getElementById('edit_ex_tag').style.display = 'inline';
    document.getElementById('ex_tag').style.display = 'none';
}
function cancelEditTag() { 
    document.getElementById('edit_ex_tag').style.display = 'none';
    document.getElementById('ex_tag').style.display = 'inline';
}
function saveEditBlogTag(tag_id,val_id,div_id,replace_with) {
	var field_value =  document.getElementById(val_id).value;
	field_value = field_value .toLowerCase();
	field_value = field_value.replace(/\s+/g,replace_with);
	javascript:ajaxpage("admin.actions.php?action=editTag&tag_id="+tag_id+"&new_link="+field_value+"", "savedLink");
	document.getElementById('ex_link_hl').innerHTML = field_value;            
	cancelEditTag();

}



function editBlogLink() { 
    document.getElementById('edit_ex_link').style.display = 'inline';
    document.getElementById('ex_link').style.display = 'none';
}
function cancelEditBlogLink() { 
    document.getElementById('edit_ex_link').style.display = 'none';
    document.getElementById('ex_link').style.display = 'inline';
}
function saveEditBlogLink(date_id,val_id,div_id,replace_with) {
	var field_value =  document.getElementById(val_id).value;
	field_value = field_value .toLowerCase();
	field_value = field_value.replace(/\s+/g,replace_with);
	javascript:ajaxpage("admin.actions.php?action=editBlogLink&date_id="+date_id+"&new_link="+field_value+"", "savedLink");
	document.getElementById('ex_link_hl').innerHTML = field_value;            
	cancelEditBlogLink();

}

function saveEditProdLink(prod_id,val_id,div_id,replace_with) {
	var field_value =  document.getElementById(val_id).value;
	field_value = field_value .toLowerCase();
	field_value = field_value.replace(/\s+/g,replace_with);
	javascript:ajaxpage("admin.actions.php?action=editProdLink&prod_id="+prod_id+"&new_link="+field_value+"", "savedLink");
	document.getElementById('ex_link_hl').innerHTML = field_value;            
	cancelEditBlogLink();
}




function updatePhoto(pic_id,fields,checkboxes,divname) {
    document.getElementById('success').style.display = 'none';
	document.getElementById('success').style.opacity = '0';
   var poststr = new Array();
   if(fields!="null") {
	   var formFields=fields.split(",");
	   var i = 0;
		for ( keyVar in formFields ) {
			i++;
			if(document.getElementById(formFields[keyVar])) {
				poststr[i] = formFields[keyVar] +"="+ escape(encodeURI( document.getElementById(formFields[keyVar]).value ));
			}
		}
		poststring = poststr.join("&");
   } else {
	   var poststring="";
   }
      if(checkboxes!="null") {
	   var formCheckboxes=checkboxes.split(",");
		for ( keyVar in formCheckboxes ) {
			i++;
			if(document.getElementById(formCheckboxes[keyVar])) {
//			alert(formFields[keyVar]);
				if( document.getElementById(formCheckboxes[keyVar]).checked) {
					poststr[i] = formCheckboxes[keyVar] +"="+ escape(encodeURI( document.getElementById(formCheckboxes[keyVar]).value ));
				}
			}
		}
		poststring = poststr.join("&");
   } 


	makePOSTRequest("photo.edit.frame.php?submitit=yep", poststring, divname);
    document.getElementById('success').style.display = 'block';
	document.getElementById('success').style.opacity = '0';
	if(typeof(fader) !=='undefined') { 
		clearTimeout ( fader );
		clearTimeout ( fading);
	}
	 fadeIN('success');
	setTimeout("fadeOUT('success')",1000);

	javascript:ajaxpage('tags.php', 'photo-tags');


}


function submitPhotoForm(form) {
    Form=form; 
			Form.submission.disabled = true;
		Form.submission.value = '---------------';
  return true ;
}

var TimeToFade = 300.0;


function fadeIN(eid) {
  var element = document.getElementById(eid);
	element.style.display=="none"
	element.style.opacity =  0;
     element.FadeState = -2;
	fade(eid);
}
function fadeOUT(eid) {
  var element = document.getElementById(eid);
     element.FadeState = 2;
	fade(eid);
}

function resetFade(eid) {
  var element = document.getElementById(eid);
   element.style.filter = 'alpha(opacity = 1.0)';
	document.getElementById(eid).style.opacity = '1.0';
	document.getElementById(eid).style.display = 'inline';
}


function fade(eid)
{
  var element = document.getElementById(eid);
  if(element == null)
    return;
//	 alert(element + '- ' + eid + ' display ' + element.style.display);
	if(element.style.display=="none") {
		  element.style.display="block";
	}
  if(element.FadeState == null)
  {
    if(element.style.opacity == null
        || element.style.opacity == ''
        || element.style.opacity == '1')
    {
      element.FadeState = 2;
    }
    else
    {
      element.FadeState = -2;
	
	
	}
  }
  //	  	element.style.visibility="visible";
//    alert(eid +" visibilty "+ element.style.visibility + "  fade state" + element.FadeState + " Opacity" + element.style.opacity);

  if(element.FadeState == 1 || element.FadeState == -1)
  {
    element.FadeState = element.FadeState == 1 ? -1 : 1;
    element.FadeTimeLeft = TimeToFade - element.FadeTimeLeft;
  }
  else
  {
    element.FadeState = element.FadeState == 2 ? -1 : 1;
    element.FadeTimeLeft = TimeToFade;
   fader =  setTimeout("animateFade(" + new Date().getTime() + ",'" + eid + "')", 33);
  }  

}
function animateFade(lastTick, eid)
{  
  var curTick = new Date().getTime();
  var elapsedTicks = curTick - lastTick;
 
  var element = document.getElementById(eid);
   

  if(element.FadeTimeLeft <= elapsedTicks)
  {
    element.style.opacity = element.FadeState == 1 ? '1' : '0';
    element.style.filter = 'alpha(opacity = ' + (element.FadeState == 1 ? '100' : '0') + ')';
    element.FadeState = element.FadeState == 1 ? 2 : -2;
 
	if( element.FadeState == -2) {
	 element.style.display="none";
   }
   return;
  }
  element.FadeTimeLeft -= elapsedTicks;
  var newOpVal = element.FadeTimeLeft/TimeToFade;
  if(element.FadeState == 1)
    newOpVal = 1 - newOpVal;

  element.style.opacity = newOpVal;

  element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';

  fading = setTimeout("animateFade(" + curTick + ",'" + eid + "')", 33);
}










function openClose(div1,div2) {
    document.getElementById(div2).style.display = 'none';
    document.getElementById(div1).style.display = 'block';
}

function selectFieldEdit(div1,div2,fn) {
    document.getElementById(div2).style.display = 'none';
    document.getElementById(div1).style.display = 'block';
	if(fn!='' ) {
		document.getElementById(fn).focus();
	document.getElementById(fn).select();

	}
}

function doneEditCheckbox(div1,div2,ajaxlink,ajaxdiv) {
    document.getElementById(div2).style.display = 'none';
    document.getElementById(div1).style.display = 'block';
	document.getElementById(ajaxdiv).innerHTML="<img src=\"graphics/loading-green.gif\" width=16 height=16>";
	ajaxpage(ajaxlink,ajaxdiv);
//	return false;
}

function doneNewCheckbox(div1,div2,ajaxlink,ajaxdiv,newfieldname) {

//	 alert(document.getElementById(newfieldname).value);
    document.getElementById(div2).style.display = 'none';
	ajaxlink = ajaxlink + "&new_name=" + document.getElementById(newfieldname).value;
    document.getElementById(div1).style.display = 'block';
	document.getElementById(ajaxdiv).innerHTML="<img src=\"graphics/loading-green.gif\" width=16 height=16>";
	ajaxpage(ajaxlink,ajaxdiv);
}


var loadedobjects=""
var rootdomain="http://"+window.location.hostname

function ajaxpage(url, containerid){
var page_request = false
if (window.XMLHttpRequest) // if Mozilla, Safari etc
page_request = new XMLHttpRequest()
else if (window.ActiveXObject){ // if IE
try {
page_request = new ActiveXObject("Msxml2.XMLHTTP")
} 
catch (e){
try{
page_request = new ActiveXObject("Microsoft.XMLHTTP")
}
catch (e){}
}
}
else
return false
page_request.onreadystatechange=function(){
loadpage(page_request, containerid)
}
page_request.open('GET', url, true)
page_request.send(null)
}

function loadpage(page_request, containerid){
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)) {
//		var enableCache = false;
		document.getElementById(containerid).innerHTML=page_request.responseText
	}
}

function loadimage(){
if (!document.getElementById)
return
for (i=0; i<arguments.length; i++){
var file=arguments[i]
var fileref=""
if (loadedobjects.indexOf(file)==-1){ //Check to see if this object has not already been added to page before proceeding
}
if (fileref!=""){
document.getElementsByTagName("head").item(0).appendChild(fileref)
loadedobjects+=file+" " //Remember this object as being already added to page
}
}
}


/* FROM masterjs in forms */

function showNotes(div_id) {
    // hide all the divs
	document.getElementById('notes').style.display = 'none';
    // show the requested div
    document.getElementById('notes').style.display = 'block';
}

function hideOptions(div_id) {
    document.getElementById(div_id).style.display = 'none';
}
function TFDDSelectOther(div1,div2,fn,fnr) {
	document.getElementById(fnr).value='' ;
    document.getElementById(div2).style.display = 'none';
    document.getElementById(div1).style.display = 'block';
	document.getElementById(fn).focus();

}

function showTagSearch(div_id) {
    // hide all the divs
	document.getElementById('tagsearch').style.display = 'none';
    // show the requested div
    document.getElementById('tagsearch').style.display = 'block';
}


   var http_request = false;
   function makePOSTRequest(url, parameters, divname) {
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      
      http_request.onreadystatechange =function(){
	alertContents(divname)
	}
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
	  http_request.setRequestHeader("Cache-Control", "no-store, no-cache,must-revalidate");
	  http_request.setRequestHeader("Cache-Control", "no-cache");
	http_request.setRequestHeader("Cache-Control", "no-store");
	http_request.setRequestHeader("Pragma", "no-cache");
      http_request.send(parameters);
   }

   function alertContents(divname) {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById(divname).innerHTML = result;            
         } else {
            alert('There was a problem with the request.');
         }
      }
   }


var loadedobjects=""
var rootdomain="http://"+window.location.hostname



var to
var mainmenus=new Array("amgalleries","amproducts","areports","amsettings","amcustomers");
var mainmenuso=new Array("amgallerieso","amproductso","areportso","amsettingso","amcustomerso");
var n;

function TFshowentry(fn,fnt) {
	newval = document.getElementById(fn).value;
	if(newval!=='') {
		document.getElementById(fn).type='hidden';
		document.getElementById(fn).value=newval;
		document.getElementById(fnt).style.display = 'inline';
		document.getElementById(fnt).innerHTML = document.getElementById(fn).value;
	}
//	alert( document.getElementById(fn).value);
}
function TFeditThis(fn,fnt) {
	newval = document.getElementById(fn).value;
	document.getElementById(fn).type='text';
	document.getElementById(fn).value=newval;
	document.getElementById(fnt).style.display = 'none';
	document.getElementById(fnt).innerHTML = '';
	document.getElementById(fn).focus();

//	alert( document.getElementById(fn).value);
}


function selectDDOption(opt_display_name,opt_select_name,opt_select_id,opt_field_id,div_name,div_option,ddmdiv) {
	document.getElementById(div_name).style.display = 'inline';
	document.getElementById(div_name).innerHTML = opt_display_name;
	document.getElementById(opt_select_name).value=escape(opt_select_id) ;
	document.getElementById(div_option + "o").style.display = 'inline';
	document.getElementById(ddmdiv).style.display = 'none';
}


function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    
		    return false
		 }

 		 return true					
	}

	   function updateFormField(obj,fields,divname) {
		   var poststr = new Array();
	//	   	javascript:ajaxpage("message.page.php?ck_secure=<?php print $_SESSION['secure_page'];?>&image=loading.gif", divname);

		   if(fields!="null") {
			   var formFields=fields.split(",");
			   var i = 0;
				for ( keyVar in formFields ) {
					i++;
	//alert(formFields[keyVar]);
		if(document.getElementById(formFields[keyVar])) {
		    poststr[i] = formFields[keyVar] +"="+ escape(encodeURI( document.getElementById(formFields[keyVar]).value ));
		}
				}
	
				poststring = poststr.join("&");
		   } else {
			   var poststring="";
		   }
//      var poststr = formFields[0] +"="+ escape(encodeURI( document.getElementById(formFields[0]).value )) +
  //                  "&" + formFields[1] +"="+ escape(encodeURI( document.getElementById(formFields[1]).value ));
		document.getElementById(divname).innerHTML="<img src=\"graphics/loading-green.gif\" width=16 height=16>";
      makePOSTRequest('../ms_manage/field.edit.php', poststring, divname);
//	document.getElementById(obj+'submission').disabled = true;
//	document.getElementById(obj+'submission').value = '<?php print $tr['adding_submit'];?>';

   }

	   function updateNote(obj,fields,divname) {
		   var poststr = new Array();
	//	   	javascript:ajaxpage("message.page.php?ck_secure=<?php print $_SESSION['secure_page'];?>&image=loading.gif", divname);

		   if(fields!="null") {
			   var formFields=fields.split(",");
			   var i = 0;
				for ( keyVar in formFields ) {
					i++;
	//alert(formFields[keyVar]);
		if(document.getElementById(formFields[keyVar])) {
		    poststr[i] = formFields[keyVar] +"="+ escape(encodeURI( document.getElementById(formFields[keyVar]).value ));
		}
				}
	
				poststring = poststr.join("&");
		   } else {
			   var poststring="";
		   }
//      var poststr = formFields[0] +"="+ escape(encodeURI( document.getElementById(formFields[0]).value )) +
  //                  "&" + formFields[1] +"="+ escape(encodeURI( document.getElementById(formFields[1]).value ));
		document.getElementById(divname).innerHTML="<img src=\"graphics/loading-green.gif\" width=16 height=16>";
      makePOSTRequest('../manage/notes.php', poststring, divname);
//	document.getElementById(obj+'submission').disabled = true;
//	document.getElementById(obj+'submission').value = '<?php print $tr['adding_submit'];?>';

   }


/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);

(function(c,q){var m="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function n(){var b=c(j),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function p(b){k(b.target,"error"===b.type)}function k(b,a){b.src===m||-1!==c.inArray(b,l)||(l.push(b),a?h.push(b):j.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),r&&d.notifyWith(c(b),[a,e,c(j),c(h)]),e.length===l.length&&(setTimeout(n),e.unbind(".imagesLoaded",
p)))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():0,r=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),l=[],j=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",p).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)k(a,e.isBroken);else if(a.complete&&a.naturalWidth!==q)k(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=m,a.src=d}):
n();return d?d.promise(g):g}})(jQuery);
