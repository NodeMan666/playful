$(document).ready(function(){
	$(".defaultfield").bind('focus', function() { 
		if($(this).val() == $(this).attr("default")) { 
			$(this).val("");
		}
	});
	$('.defaultfield').bind('blur', function() { 
		if($(this).val() == "") { 
			$(this).val($(this).attr("default"));
		}
	});
});


function openroomview(pid) { 
	hideMiniCart();
	fixbackground();
	$("#buybackground").fadeIn(50, function() { 
		if(isslideshow) { 
			if(isslideshow == true) { 
				stopSlideshow();
			}
		}
		loading();

		$.get(tempfolder+"/sy-inc/room-view/room-view.php?pid="+pid, function(data) {

			$("#photoprodsinner").html(data);
			$("#photoprods").slideDown(200, function() { 
				if($("body").width() <= 800) { 
					$("#closebuyphototab").show();
				} else { 
					$("#closebuyphoto").show();
				}
				loadingdone();
			});
		});
	});
}


function showstoreitem(did) { 
	$("#storeitembackground").show(); 
	$.get(tempfolder+"/sy-inc/store-item.php?did="+did, function(data) {
		$('#storeitembackground').click(function() {
			closestoreitem()
		 });

		 $('#storeitemcontainer').click(function(event){
			 event.stopPropagation();
		 });

		$("#storeitemcontainer").css({"top":$(window).scrollTop()+50+"px"});
		$("#storeiteminner").html(data);
				adjustsite();

		$("#storeitemcontainer").fadeIn(200, function() { 
			$("#closestoreitem").fadeIn(100);
		});
	});
	
}		
function closestoreitem() { 
	$("#storeitemcontainer").fadeOut(200, function() { 
		$("#closestoreitem").fadeOut(100);

		$("#storeiteminner").html("");
		$("#storeitembackground").fadeOut(200);
	});

}

function showrequireremove(id) { 
	$("#remove-"+id).slideToggle(200);
}

function showpackageremove(id) { 
	$("#remove-package-"+id).slideToggle(200);
}

function openoptions(id) { 
	// $(".photoprod").fadeOut(200);
	$(".options-"+id).slideToggle(200);
}

function openpackageoptions(id) { 
	$(".options-"+id).slideToggle(200);
}

function addordernotes() { 
	$("#order_notes").slideToggle(200);
}

function photokeywords() { 
	hideMiniCart();
	fixbackground();
	$("#buybackground").fadeIn(50);
	if(isslideshow) { 
		if(isslideshow == true) { 
			stopSlideshow();
		}
	}
	loading();
	$("#photoprods").css({"top":$(window).scrollTop()+50+"px"});
		$.get(tempfolder+"/sy-inc/photo_keywords.php?did="+$("#vinfo").attr("did")+"&sub_id="+$("#vinfo").attr("sub_id"), function(data) {
			$("#photoprodsinner").html(data);
			$("#photoprods").slideDown(200, function() { 
				if($("body").width() <= 800) { 
					$("#closebuyphoto").show();
				} else { 
					$("#closebuyphototab").show();
				}
				sizeBuyPhoto();
				loadingdone();

				$('html').click(function() {
				 closebuyphoto();
				 });

			 $('#photoprods').click(function(event){
				 event.stopPropagation();
			 });

			});
		});
}
function giftcertificate(id) { 
	$("#gallerysharebg").attr("data-window","giftcertificate");
	$("#accloading").show();
	closebuyphoto();
	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#giftcertificate").length == 0) {
			$("#gallerysharebg").after('<div id="giftcertificate" class="gallerypopup"></div>');
		}
			$.get(tempfolder+"/sy-inc/store/store_gift_certificate.php?cart_id="+id, function(data) {
			$("#accloading").hide();
			$("#giftcertificate").html(data);
			$("#giftcertificate").css({"top":to+"px"}).fadeIn(200);
		});
	});
}
function giftcertificateredeem(where) { 
	$("#gallerysharebg").attr("data-window","giftcertificateredeem");
	$("#accloading").show();
	closebuyphoto();
	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#giftcertificateredeem").length == 0) {
			$("#gallerysharebg").after('<div id="giftcertificateredeem" class="gallerypopup"></div>');
		}
			$.get(tempfolder+"/sy-inc/store/store_redeem_gift_certificate.php", function(data) {
			$("#accloading").hide();
			$("#giftcertificateredeem").html(data);
			$("#giftcertificateredeem").css({"top":to+"px"}).fadeIn(200);
		});
	});
}


function redeemprintcredit(pc_code) { 
	$("#gallerysharebg").attr("data-window","printcredit");
	$("#accloading").show();
	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#printcredit").length == 0) {
			$("#gallerysharebg").after('<div id="printcredit" class="gallerypopup"></div>');
		}
			$.get(tempfolder+"/sy-inc/store/store_redeem_print_credit.php?pc_code="+pc_code, function(data) {
			$("#accloading").hide();
			$("#printcredit").html(data);
			$("#printcredit").css({"top":to+"px"}).fadeIn(200);
		});
	});
}

function redeemcoupon(where,code) { 
	timeout = 0;
	if($("#page-wrapper").css("position") == "fixed") { 
		timeout = 300;
		closebuyphoto();
	}
	setTimeout(function(){
		$("#gallerysharebg").attr("data-window","redeemcoupon");
		$("#accloading").show();

		$("#gallerysharebg").fadeIn(100, function() { 
			if($(window).scrollTop() < 80) { 
				to = 80;
			} else { 
				to = $(window).scrollTop();
			}
			if($("#redeemcoupon").length == 0) {
				$("#gallerysharebg").after('<div id="redeemcoupon" class="gallerypopup"></div>');
			}
				$.get(tempfolder+"/sy-inc/store/store_redeem_coupon.php?where="+where+"&code="+code, function(data) {
				$("#accloading").hide();
				$("#redeemcoupon").html(data);
				$("#redeemcoupon").css({"top":to+"px"}).fadeIn(200);
			});
		});
	}, timeout);
}



function findphotos() { 
	hideMiniCart();
	fixbackground();
	$("#buybackground").fadeIn(50);
	if(isslideshow) { 
		if(isslideshow == true) { 
			stopSlideshow();
		}
	}
	loading();
	$("#photoprods").css({"top":$(window).scrollTop()+50+"px"});
		$.get(tempfolder+"/sy-inc/find_photos_window.php", function(data) {
			$("#photoprodsinner").html(data);
			$("#photoprods").slideDown(200, function() { 
				$("#closebuyphoto").show();
				sizeBuyPhoto();
				loadingdone();

				$('html').click(function() {
				 closebuyphoto();
				 });

			 $('#photoprods').click(function(event){
				 event.stopPropagation();
			 });

			});
		});
}


function getstates(country,gettax,shiponly) { 
	$.get(tempfolder+'/sy-inc/store/store_cart_actions.php?action=getstatelist&country='+country, function (data) { 
		if(shiponly == "1") { 
			$("#ship_state").html(data);
			getTax();
		} else { 
			$("#state").html(data);
			if(gettax == "1") { 
				$("#ship_state").html(data);
				getTax();
			}
		}
	});
}
function sendtocart(classname,date_id) { 
	var fields = {};
	if(!date_id) {
		var date_id = "";
	}
	var rf = false;
	var stop;
	var mes;

	$("#select_option_message").slideUp(200);




	$(".itemrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			if($("#"+this_id).attr("opttype") == "tabs") { 
				$('#'+this_id).parent().addClass('inputError');
			} else { 
				$('#'+this_id).addClass('inputError');
			}
			rf = true;
		} else { 
			if($("#"+this_id).attr("opttype") == "tabs") { 
				$('#'+this_id).parent().removeClass('inputError');
			} else { 
				$('#'+this_id).removeClass('inputError');
			}
		}
	} );

	if(rf == true || stop == true) {
		if(rf == true) {
			$("#select_option_message").slideDown(200);
		}
		return false;
	} else { 
		$("#min_qty_message").slideUp(200);

		 $("#addtocart"+date_id).hide();
		 $("#addtocartloading"+date_id).show();

		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += $this.val(); 
					// alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('name')] = $this.val(); 
			//	alert($this.attr('name') +" = "+ $this.val() );
			}
		});

		fields['curphoto'] = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		fields['curphotodid'] = $("#vinfo").attr("did");
		fields['cart_photo_bg'] = $("#gs-bgimage-id").val();

		if(Math.abs(fields['prod_qty']) < Math.abs(fields['qty_min']))  { 
			$("#min_qty_message").slideDown(200);
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
		} else if(fields['prod_qty'] < 0) { 
			$("#min_qty_message").slideDown(200);
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
		} else { 
			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
				// alert(data);

				if($("#addaction").attr("data-redirect") =="1") { 
					window.location.href=$("#addaction").val();
				}

				if(fields['from_photo'] > 0) { 
					showMiniCart('','1');
				} else { 
					showMiniCart();
				}
				 $("#submitinfo").html(data);
				updateCartMenu();
				setTimeout(function(){
					if(fields['from_photo'] > 0) { 
						$("#vinfo").attr("viewing-store-photo-prod","1");
						storeproductnexttophoto($("#slideshow").attr("curphoto"));
					}
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
				},500)
			 } );
		}
	}
	return false;
}




function sendtocartlist(classname,date_id) { 
	var fields = {};
	if(!date_id) {
		var date_id = "";
	}
	var rf = false;
	var stop;
	var mes;

	$("#select_option_message").slideUp(200);
	$(".itemrequired-"+date_id).each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			if($("#"+this_id).attr("opttype") == "tabs") { 
				$('#'+this_id).parent().addClass('inputError');
			} else { 
				$('#'+this_id).addClass('inputError');
			}
			rf = true;
		} else { 
			if($("#"+this_id).attr("opttype") == "tabs") { 
				$('#'+this_id).parent().removeClass('inputError');
			} else { 
				$('#'+this_id).removeClass('inputError');
			}
		}
	} );

	if(rf == true || stop == true) {
		if(rf == true) {
			$("#select_option_message").slideDown(200);
		}
		return false;
	} else { 
		$("#min_qty_message").slideUp(200);

		 $("#addtocart"+date_id).hide();
		 $("#addtocartloading"+date_id).show();

		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += $this.val(); 
					// alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('name')] = $this.val(); 
			//	alert($this.attr('name') +" = "+ $this.val() );
			}
		});

		fields['curphoto'] = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		fields['curphotodid'] = $("#vinfo").attr("did");

		if(Math.abs(fields['prod_qty']) < Math.abs(fields['qty_min']))  { 
			$("#min_qty_message").slideDown(200);
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
		} else if(fields['prod_qty'] < 0) { 
			$("#min_qty_message").slideDown(200);
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
		} else { 
			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
				// alert(data);

				if($("#addaction-"+date_id).attr("data-redirect") =="1") { 
					window.location.href=$("#addaction-"+date_id).val();
				}

				if(fields['from_photo'] > 0) { 
					showMiniCart('','1');
				} else { 
					showMiniCart();
				}
				 $("#submitinfo").html(data);
				updateCartMenu();
				setTimeout(function(){
					if(fields['from_photo'] > 0) { 
						$("#vinfo").attr("viewing-store-photo-prod","1");
						storeproductnexttophoto($("#slideshow").attr("curphoto"));
					}
				 $("#addtocart"+date_id).show();
				 $("#addtocartloading"+date_id).hide();
				},500)
			 } );
		}
	}
	return false;
}
function updateCartMenu() { 
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=updatecartmenu", function(data) {
		$("#viewcartlink").show();
		$("#checkoutlink").show();
		$(".showcartmenus").show();
		$("#cartlinktotal").html(data);
		$(".cartlinktotal").html(data);
		$("#photobuycarttotal").html(data).show();
		$("#viewphotocarttotal").show();
		$("#viewcartpagetotal").html(data);
		$("#viewcartpagesubtotal").html(data);
		$("#no_message").hide();
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=updatecartmenuitems", function(data) {
			$(".carttotalcircle").html(data);
		});
	});

}
/*
function showMiniCart(pck,from_photo) { 
	if(!pck) { 
		pck = "";
	}
	if(!from_photo) { 
		from_photo = "";
	}

	$("#gallerysharebg").attr("data-window","minicart");
	$("#accloading").show();
	closebuyphoto();
	$("#gallerysharebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop();
		}
		if($("#minicart").length == 0) {
			$("#gallerysharebg").after('<div id="minicart" class="gallerypopup"></div>');
		}
		$.get(tempfolder+"/sy-inc/store/store_view_cart_mini.php?package="+pck+"&from_photo="+from_photo, function(data) {
			$("#accloading").hide();
			$("#minicart").html(data);
			$("#minicart").css({"top":to+"px"}).fadeIn(200);
		});
	});
}
*/
function showMiniCart(pck,from_photo) { 
	if(!pck) { 
		pck = "";
	}
	if(!from_photo) { 
		from_photo = "";
	}

	$.get(tempfolder+"/sy-inc/store/store_view_cart_mini.php?package="+pck+"&from_photo="+from_photo, function(data) {
		$("#viewcartinner").html(data);
		$("#viewcarttop").slideDown(200);
		$('html').bind('click', function() {
			hideMiniCart();
		});
		$('#viewcartinner').click(function(event){
			event.stopPropagation();
		});
	});
}
function hideMiniCart() { 
	$("#viewcarttop").fadeOut();
	//$("#viewcarttop").slideUp(200);
	//$("#viewcartinner").html("");
	closestoreitem();
	$("#footercheckout").slideDown(400);
}

function removeFromCart(cid) { 
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removefromcart&cid="+cid+"", function(data) {
		$("#cart-"+cid).slideUp(200);
		if(data == "remove") { 
			$(".checkoutpagebutton").hide();
			$("#cartisempty").show();
			$("#storecarttext").hide();
		}
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=updateTotal", function(data) {
			$("#expressprice").val(data);
			updateCartMenu();
		});
		

	});

}

function createaccount(classname,loggedin,ckpass) { 
	var fields = {};
//	 $("#addtocart").hide();
//	 $("#addtocartloading").show();
	var rf = false;
	var stop;
	var mes;
	$("#accresponse").slideUp(10).removeClass("error");
	$(".required").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );
		if($("#checkout").attr("loggedin") == "0") { 
			if($("#email_address").val() !== $("#email_address_2").val()) {
				 $("#accresponse").addClass("error").html($("#accresponse").attr("mismatchemail")).slideDown(100);
				 stop = true;
				 return false;
			}
		}

		if($("#checkout").attr("loggedin") == "0" && $("#checkout").attr("ckpass") == "1" && $("#checkout").attr("account") !== "disabled") { 
			if($("#newpassword").val() !== $("#renewpassword").val()) {
				$("#newpassword").val(""); 
				$("#renewpassword").val(""); 
				 $("#accresponse").addClass("error").html($("#accresponse").attr("passwordsnomatch")).slideDown(100);
				stop = true;
				 return false;
			}
		}

	if(rf == true || stop == true) {
		if(rf == true) {
			 $("#accresponse").addClass("error").html($("#accresponse").attr("emptyfields")).slideDown(100);
		}
		return false;
	} else { 


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
		if($("#checkout").attr("loggedin") == "1" || $("#no_account").attr("checked") ||  $("#checkout").attr("account") == "disabled") { 
			$("#maininfo").slideUp();
			if($("#checkout").attr("ship") == "1") { 
				getShipping();
				fields['action'] = "updateaccount";
				$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

				// alert("here, add customer info: "+data);
				});

				$("#progressshipping").removeClass("checkoutprogress").addClass("checkoutprogressdone");
				setTimeout(function(){
					$('#buttonsShipping').css('display', 'block');
				}, 1200);
			} else { 
				getgrandtotal();
				fields['action'] = "updateaccount";
				$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

				// alert("here, add customer info: "+data);
				});
				setTimeout(function(){
					if($("#grand_total").val()<=0) { 
						$(".paymentoptionsavailable").hide();
						$("#pay_total_zero").val("1");
						$("#payzerototal").show();
						$("#stripe-sel").attr("checked", false);
						$("#checkout").attr("action",$("#checkout").attr("data-action"));

					}				
					$("#paymentselect").slideDown();
					$("#progresspayment").removeClass("checkoutprogress").addClass("checkoutprogressdone");
					$("#stripe-name").val($("#first_name").val()+" "+ $("#last_name").val());
					if($("#square-sel").attr("checked")) { 
						sqPaymentForm.setPostalCode($("#zip").val());
					}

				},1000);
		}
		if($("#checkoutinfo").hasClass("hidesmall")) {
			
		} else { 
			$("#checkoutinfo").slideDown();
		}
			$("#accresponse").html("");
			$("#pagelogin").slideUp();
				off = $("#progressinfo").offset();
				scrollto = off.top - $("#shopmenucontainer").height();
			   $('html').animate({scrollTop:scrollto}, 'slow'); 
			$('body').animate({scrollTop:scrollto}, 'slow'); 


		} else { 
			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

			data = $.trim(data);
			if(data == "good") { 
				$("#checkout").attr("loggedin", "1");
				$("#checkout").attr("ckpass", "0");
				$("#emails").hide();
				$("#passes").hide();
				$("#editinfo").show();
				$("#maininfo").slideUp();
				if($("#checkout").attr("ship") == "1") { 
					getShipping();
					$("#progressshipping").removeClass("checkoutprogress").addClass("checkoutprogressdone");
				} else { 
					getgrandtotal();
					setTimeout(function(){

						if($("#grand_total").val()<=0) { 
							$(".paymentoptionsavailable").hide();
							$("#pay_total_zero").val("1");
							$("#payzerototal").show();
							$("#stripe-sel").attr("checked", false);
							$("#checkout").attr("action",$("#checkout").attr("data-action"));

						}				
						$("#paymentselect").slideDown();
						$("#stripe-name").val($("#first_name").val()+" "+ $("#last_name").val());
						if($("#square-sel").attr("checked")) { 
							sqPaymentForm.setPostalCode($("#zip").val());
						}
						$("#progresspayment").removeClass("checkoutprogress").addClass("checkoutprogressdone");
						},1000);

				}
				$("#pagelogin").slideUp();
				$("#shippingrow").slideDown();

				 $("#accresponse").html("");
				off = $("#progressinfo").offset();
				scrollto = off.top - $("#shopmenucontainer").height();
			   $('html').animate({scrollTop:scrollto}, 'slow'); 
			$('body').animate({scrollTop:scrollto}, 'slow'); 

			} else { 
				// alert(data);
			 $("#accresponse").show().html(data);
			}
		});

		}
	
	return false;
	}

}

function swapStates() {

	$(".allstates").hide();
	ct = $("#country").val();
	ctn = ct.replace(" ","_");
	$(".ct-"+ctn).show();


}
function getTax() { 
	$(".checkout").hide();
	$(".checkoutnavloading").show();

	setTimeout(function(){
	var pickup = $("input[name=ship_select]:checked").attr("data-pickup");
	if($("#checkout").attr("taxwhere") == "shipping") { 
		var state = $("#ship_state").val();
		var zip = encodeURIComponent($("#ship_zip").val());
		var country = $("#ship_country").val();
	} else { 
		var state = $("#state").val();
		var zip = encodeURIComponent($("#zip").val());
		var country = $("#country").val();
	}
	var amount = $("#taxable_amount").attr("data-org-tax-amount"); 
	if(tax_shipping == 1) { 
		amount = Math.abs(amount) + Math.abs($("#shipping_price").val());
		$("#taxable_amount").val(amount);
	}


	$.get(tempfolder+'/sy-inc/store/store_cart_actions.php?action=getTax&sid='+state+'&zip='+zip+'&pickup='+pickup+'&country='+country+'&total='+amount, function(data) {
	// alert(amount+" - "+$("#shipping_price").val()+" state: "+state+" : data: "+data)

		tax = data.split('|');
		taxprice = priceFormat(tax[0]);
		vatprice = priceFormat(tax[2]);

		$("#vattotal").html(vatprice);
		$("#vatonpercent").html(tax[3]);


		$("#taxtotal").html(taxprice);
		$("#taxonpercent").html(tax[1]);
		$("#tax_price").val(tax[0]);
		$("#tax_percentage").val(tax[1]);
		if(tax[2] > 0) { 
			$("#vat_price").val(tax[2]);
			$("#vat_percentage").val(tax[3]);
			$("#vatrow").slideDown();
		} else { 
			$("#vat_price").val(0);
			$("#vat_percentage").val(0);
			$("#vatrow").slideUp(100);
		}

		if(tax[0] > 0) { 
			$("#taxrow").fadeIn(100);
		} else { 
			$("#taxrow").fadeOut(100);
		}
		$(".checkout").fadeIn(200);
		$(".checkoutnavloading").hide();

	});
	},200);
}

function getShipping() { 
	var amount = $("#ship_on").val();
	$.get(tempfolder+'/sy-inc/store/store_cart_actions.php?action=getShipping&view=checkout&state='+$("#ship_state").val()+'&country='+$("#ship_country").val()+'&total='+amount, function(data) {
	data = $.trim(data);
	$("#shippingselect").html(data);
	$("#shippingcontainer").slideDown();
	var shipValue = Math.abs($("#shipdefault").attr("price"));
	shipprice = priceFormat(shipValue);
	/* Grab address to send to shipping fields */
	getTax();


	$("#shippingtotal").html(shipprice);
	$("#shippingtotal").attr("price",shipValue);
	$("#shipping_price").val(shipValue);


	$("#shippingrow").slideDown();
	getgrandtotal();

});
}

function editInfo() { 
	$("#editinfo").hide();
	$("#maininfo").slideDown();
	$("#paymentselect").slideUp();
	$("#shippingcontainer").slideUp();
	$("#grandtotalrow").hide();
	$("#pay_total_zero").val("0");
	$("#progresspayment").removeClass("checkoutprogressdone").addClass("checkoutprogress");
	$("#progressshipping").removeClass("checkoutprogressdone").addClass("checkoutprogress");
	$('#buttonsShipping').css('display', 'none');
}
function saveshipping() { 
	$("#paymentselect").slideDown();
	$("#stripe-name").val($("#first_name").val()+" "+ $("#last_name").val());
	if($("#square-sel").attr("checked")) { 
		sqPaymentForm.setPostalCode($("#zip").val());
	}

	if($("#grand_total").val()<=0) { 
		$(".paymentoptionsavailable").hide();
		$("#pay_total_zero").val("1");
		$("#payzerototal").show();
		$("#stripe-sel").attr("checked", false);
		$("#checkout").attr("action",$("#checkout").attr("data-action"));

	}
	$("#shippingcontainer").slideUp();
	$("#progresspayment").removeClass("checkoutprogress").addClass("checkoutprogressdone");
	$('#buttonsShipping').css('display', 'none');
}

function getgrandtotal() { 
	$("#grandtotal").hide();
	$("#grandtotalloading").show();
	$(".checkout").hide();
	$(".checkoutnavloading").show();

	setTimeout(function(){

		var subtotal = Math.abs($("#subtotal").attr("price"));
		if($("#checkout").attr("ship") == "1") { 
			var shipping = Math.abs($("#shippingtotal").attr("price"));
		} else { 
			var shipping = 0;
		}
		var discount = Math.abs($("#discount_amount").val());
		var eb = Math.abs($("#eb_amount").val());
		var tax = Math.abs($("#tax_price").val());
		var vat = Math.abs($("#vat_price").val());
		var credit = Math.abs($("#credit_amount").val());
		var gift_certificate = Math.abs($("#gift_certificate_amount").val());


		// $("#log").show().html(subtotal +" - "+eb +" - "+discount+" + "+shipping+" + "+tax+" + "+vat);
		gtotal = subtotal - eb - discount + shipping + tax + vat;
		if(gtotal <= credit) { 
			gtotal = 0;
		} else { 
			gtotal = gtotal - credit;
		}
		if(gtotal < gift_certificate) {
			gtotal = 0;
		} else { 
			gtotal = gtotal - gift_certificate;
		}
		gtotal = gtotal.toFixed(2);
		$("#grand_total").val(gtotal);
		gtotal = priceFormat(gtotal);
		$("#grandtotal").html(gtotal);
		$("#grandtotalrow").slideDown();
		$("#grandtotal").show();
		$("#grandtotalloading").hide();
		$(".checkout").fadeIn(200);
		$(".checkoutnavloading").hide();

		$.get(tempfolder+'/sy-inc/store/store_cart_actions.php?action=checkCoupon&email='+$("#email_address").val()+"&noredirect=1", function(data) {
	//		alert(data);
			if(data == "nogood") { 
					window.location.href=tempfolder+"/index.php?view=cart";
			}
	//		alert(data);
		});
	},1000);

}


function addshipping() { 
	selValue = $('input[name=ship_select]:checked').attr("price"); 
	shipprice = priceFormat(selValue);
	$("#shippingtotal").html(shipprice);
	$("#shippingtotal").attr("price",selValue);
	$("#shipping_price").val(selValue);

	$("#shippingrow").slideDown();
	getTax();
	getgrandtotal();

//	alert(selValue);
}


function showLogin() { 
	$("#accountlogin").toggle();	
	$("#forgotpassword").hide();
	$(".forgotpassword").show();
	$("#accountloginresponse").hide();
}


function forgotpasswordform() {
	$("#accountlogin").hide();
	$("#forgotpassword").show();
	$(".forgotpassword").hide();
}
function forgotpasswordpageform() {
	$("#pagelogin").hide();
	$("#forgotpasswordpage").show();
	$("#forgotpasswordpagelink").hide();
	$("#loginresponse").html("");
}
function cancelforgotpassword() { 
	 $("#pagelogin").show();
	 $("#forgotpasswordpage").hide();
	 $("#forgotemail").val("");
	 $("#forgotemailmessagepage").show();
	 $("#forgotloginresponsepage").hide();
	$("#forgotpasswordpagelink").show();
}
function accountlogin(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#accountloginresponse").hide();

	$(".lrequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				window.location.href="";
			} else { 
				 $("#accountloginresponse").show();
				 $("#accountloginresponse").html(data);
			}
		});
	return false;
	}
}




function customerlogin(classname,view,indexpage) { 
	var fields = {};
	var rf = false;
	var mes;
	$(".loginrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );
	if(rf == true) { 
	//	showErrorMessage("You have required fields empty which are highlighted.");	
	//	setTimeout("hideErrorMessage()", 4000);
		return false;
	} else { 
	$("#loginsubmitpage").hide();
	$("#loginloadingpage").show();


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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				if($("#sub").val() !== "") { 
					window.location.href="index.php?sub="+$("#sub").val();
				} else { 
					window.location.href=indexpage+"?view="+view;
				}
			} else { 
				 $("#loginresponse").html(data);
					$("#loginsubmitpage").show();
					$("#loginloadingpage").hide();

			}
		});
	return false;
	}
}

function newpassword(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#forgotloginresponse").hide();

	$(".fprequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				 $("#forgotloginresponse").show();
				 $("#forgotloginresponse").html($("#forgotloginresponse").attr("success"));
				 $("#forgotemailmessage").hide();
				 $("#forgotemail").val("");
			} else { 
				 $("#forgotloginresponse").show();
				 $("#forgotloginresponse").html(data);
				 $("#forgotemailmessage").hide();
			}
		});
	return false;
	}
}


function newpasswordpage(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#forgotloginresponsepage").hide();

	$(".feprequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				 $("#forgotloginresponsepage").show();
				 $("#forgotloginresponsepage").addClass("success").html($("#forgotloginresponsepage").attr("success"));
				 $("#pagelogin").show();
				 $("#forgotpasswordpage").hide();
				 $("#forgotemail").val("");
			} else { 
				 $("#forgotloginresponsepage").show();
				 $("#forgotloginresponsepage").html(data);
				 $("#forgotemailmessagepage").hide();
			}
		});
	return false;
	}
}



function changepassword(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#changepasswordresponse").hide();
	$(".cprequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				 $("#changepasswordresponse").show();
				 $("#changepasswordresponse").addClass("success").html($("#changepasswordresponse").attr("success"));
				 $("#forgotemailmessage").hide();
				 $("#forgotemail").val("");
			} else { 
				$("#newpass").val("");
				$("#renewpass").val("");
				 $("#changepasswordresponse").show();
				 $("#changepasswordresponse").html(data);
				 $("#forgotemailmessage").hide();
			}
		});
	return false;
	}
}

function changeemailaddress(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#changeemailresponse").hide();
	$(".emrequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				 $("#changeemailresponse").show();
				 $("#changeemailresponse").addClass("success").html($("#changeemailresponse").attr("success"));
			} else { 
				 $("#changeemailresponse").show();
				 $("#changeemailresponse").html(data);
			}
		});
	return false;
	}
}
function changeaddress(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#changeaddressresponse").hide();
	$(".required").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
				 $("#changeaddressresponse").show();
				 $("#changeaddressresponse").addClass("success").html($("#changeaddressresponse").attr("success"));
			} else { 
				 $("#changeaddressresponse").show();
				 $("#changeaddressresponse").html(data);
			}
		});
	return false;
	}
}



function priceFormat(price) { 
	price = parseFloat(price).toFixed(2);
	fprice  = pformat.replace("[CURRENCY_SIGN]", cursign);
	fprice  = fprice.replace("[PRICE]", price);
	return fprice;
}

function toggleShippingAddress() { 
	if($("#ship_billing").attr("checked")) { 
		$(".required").each(function(i){
			var this_id = this.id;
			$("#ship_"+this_id).val($('#'+this_id).val());
		} );
		
		$(".shiprequired").attr("readonly", "readonly");
		$(".shiprequired").addClass("disabledinput");
	} else { 
		$(".shiprequired").removeAttr("readonly");
		$(".shiprequired").removeClass("disabledinput");

	}
	getTax();
}
function updateCheckoutAddress()  {
	var fname = $("#first_name").val();	
	$("#ck_first_name").html(fname);
 	$("#ck_last_name").html($("#last_name").val());
 	$("#ck_email").html($("#email_address").val());
 	$("#ck_address").html($("#address").val());
 	$("#ck_city").html($("#city").val());
 	$("#ck_state").html($("#state").val());
 	$("#ck_zip").html($("#zip").val());
	if($("#ship_billing").attr("checked")) { 
		$("#ship_first_name").val(fname);
		$("#ship_last_name").val($("#last_name").val());
		$("#ship_business").val($("#business_name").val());
		$("#ship_address").val($("#address").val());
		$("#ship_city").val($("#city").val());
		$("#ship_state").val($("#state").val());
		$("#ship_zip").val($("#zip").val());
		$("#ship_country").val($("#country").val());
	}

	$("#ship_ck_first_name").html($("#ship_first_name").val());
 	$("#ship_ck_last_name").html($("#ship_last_name").val());
 	$("#ship_ck_address").html($("#ship_address").val());
 	$("#ship_ck_city").html($("#ship_city").val());
 	$("#ship_ck_state").html($("#ship_state").val());
 	$("#ship_ck_zip").html($("#ship_zip").val());

}


function noAccount() { 
	if($("#no_account").attr("checked")) { 
		$("#accountpasswords").hide();
		$("#newpassword").removeClass("required");
		$("#renewpassword").removeClass("required");
	} else { 
		$("#newpassword").addClass("required");
		$("#renewpassword").addClass("required");
		$("#accountpasswords").show();

	}
//	$("#shippingaddress").slideToggle();
}


function checkForm(classname,terms) { 
	var noreturn = false;
	var er_mess = "Your have errors \r\n";
	
	$("."+classname).each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			er_mess += $('#'+this_id).attr("errormessage")+"\r\n";
			noreturn = true;

			$('#'+this_id).addClass('inputError');
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );


	if(terms == "1") { 
		if($("#agreeterms").attr("checked")) { 

		} else { 
			er_mess += "You did not accept the terms & conditions \r\n";
			noreturn = true;

		}
	}

	if(noreturn == true) {
		alert(er_mess);
		return false;
	} else {

	$("#cardSubmit-"+classname).hide();
	$("#cardSubmitLoading-"+classname).show();


	$('#checkout').submit(function() {
		return true;
	});

		return true;
	}
	$('#checkout').submit(function() {
		return true;
	});
	return true;
}
function createaccountonly(classname,loggedin,ckpass,redirectindex,walldesigner,wdid) { 
	var fields = {};
	if(redirectindex == 1) { 
		 $("#nasubmit").hide();
		 $("#nasubmitloading").show();
	}
	var rf = false;
	var mes;
	var stop;
	$("#accresponse").slideUp(100).removeClass("error");
	$(".required").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );
	if($("#email_address").attr("data-check") == "1") { 
		if($("#email_address").val() !== $("#email_address_2").val()) {
			 $("#accresponse").addClass("error").html($("#accresponse").attr("mismatchemail")).slideDown(100);
			if(redirectindex == 1) { 
				 $("#nasubmit").show();
				 $("#nasubmitloading").hide();
			}

			 stop = true;
			 return false;
		}
	}
	$("#email_address").val($("#email_address").val().trim())
		if( !isValidEmailAddress($("#email_address").val())) { 
			$("#email_address").addClass('inputError');
			 $("#accresponse").addClass("error").html($("#email_address").attr("data-invalid-email")).slideDown(100);
			if(redirectindex == 1) { 
				 $("#nasubmit").show();
				 $("#nasubmitloading").hide();
			}

			stop = true;
			 return false;

		} else { 
			$("#email_address").removeClass('inputError');
		}


		if($("#first_name").val() !== "") { 
			if($("#first_name").val() == $("#last_name").val()) {
				 $("#accresponse").addClass("error").html($("#accresponse").attr("samefirstlastname")).slideDown(100);
					if(redirectindex == 1) { 
						 $("#nasubmit").show();
						 $("#nasubmitloading").hide();
					}

				 stop = true;
				 return false;
			}
		}

	if($("#newpassword").attr("data-check") == "1") { 
		if($("#newpassword").val() !== $("#renewpassword").val()) {
			$("#newpassword").val(""); 
			$("#renewpassword").val(""); 
			 $("#accresponse").addClass("error").html($("#accresponse").attr("passwordsnomatch")).slideDown(100);
			if(redirectindex == 1) { 
				 $("#nasubmit").show();
				 $("#nasubmitloading").hide();
			}

			stop = true;
			return false;
		}
	}
	if(rf == true || stop == true) {
		if(rf == true) {
			 $("#accresponse").addClass("error").html($("#accresponse").attr("emptyfields")).slideDown(100);
			if(redirectindex == 1) { 
				 $("#nasubmit").show();
				 $("#nasubmitloading").hide();
			}

		}
		return false;
	} else { 

		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('id')] = $this.val(); 
				//fields[$this.attr('name')] = $this.val(); 
			}
		});

		if(botdetect() == false) { 
			 $("#accresponse").html('<div class="pc"><div class="error" style="font-size: 21px;">I\m sorry, but our spam bot protection is thinking you might be a spam bot. If this is in error, please email us directly at ('+$("#from_message_to").attr("em")+'). Sorry for any inconvenience</div></div>');
		} else { 
			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

			data = $.trim(data);
			if(data == "good") { 
				if(walldesigner == 1) { 
					window.location.href="index.php?view=room&rw="+wdid+"";
				} else if(redirectindex == 1) { 
					window.location.href="index.php";
				} else { 
					$("#newaccount").slideUp(200, function() { 
						$("#newaccountsuccess").slideDown();
					});
				}
			} else { 
				if(redirectindex == 1) { 
					 $("#nasubmit").show();
					 $("#nasubmitloading").hide();
				}

			 $("#accresponse").addClass("error").html(data).slideDown(100);
			}
		});
		}
	}
	return false;
}

function newAccountExpress(classname) { 
	var fields = {};
	var rf = false;
	var mes;
	$("#newaccountexpressresponse").hide();

	$(".narequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			data = $.trim(data);
			if(data == "good") { 
	//			alert("GOOD");
				window.location.href=tempfolder+"/index.php?view=account";
			} else { 
				 $("#newaccountexpressresponse").show();
				 $("#newaccountexpressresponse").html(data);
			}
		});
	return false;
	}
}

function expressPlaceOrder() { 
	$("#placeexpress").hide();
	$("#expressloading").show();
}

function fixbackground() { 
	p = $('#page-wrapper').offset().top - $(window).scrollTop();
	w = $('#page-wrapper').width();
	st = $("#page-wrapper").attr("st");
	if(st == "0") { 
		$("#page-wrapper").attr("st", $(window).scrollTop());
	}
	$("#page-wrapper").css({"position":"fixed", "top":p+"px", "left":"50%", "width":w+"px", "margin-left":"-"+w/2+"px"});
}

function unfixbackground() { 
	$("#page-wrapper").css({"position":"relative", "top":"auto", "width":"100%", "left":"auto","margin-left":"auto"});
	$("body").scrollTop($("#page-wrapper").attr("st"));
	$("html").scrollTop($("#page-wrapper").attr("st"));
	$("#page-wrapper").attr("st", "0");
	adjustsite();

}

function closebuyphoto() { 
	$('html').unbind('click');
	$("#closebuyphoto").hide();

	$("#photoprods").fadeOut(20, function() { 
		if($("#vinfo").attr("view-photo-fixed") == "0") { 
			unfixbackground();
		}
		if($("#vinfo").attr("package_thumb_photo")!="") { 
			$("#vinfo").attr("package_from_thumb","");
			$("#vinfo").attr("package_thumb_photo","");
			unfixbackground();
		}

		$("#photoprodsinner").html("");
		$("#thumbpreview").hide();
		$("#buybackground").fadeOut(20, function() { 
		 if($("#slideshow").attr("fullscreen") == 1) { 
			$(window).scrollTop($(window).scrollTop()+1);			
		 } else { 
			 $('#th-'+$("#slideshow").attr("curphoto")).scrollView();
		 }
		});
	});
}


function viewtermsconditions() { 
	fixbackground();
	$("#buybackground").fadeIn(50, function() { 
		$("#termsandconditions").css({"top":$(window).scrollTop()+50+"px"});
		if($("body").width() <= 800) { 
			$("#termsandconditions").css({"width":"100%","left":"0px","margin-left":"0px"});
		}
		$("#termsandconditions").fadeIn(200, function() { 
			$("#closebuyphoto").show();
		});
	});
}

function agreetoterms() { 
	unfixbackground();
	$("#agreeterms").attr("checked", "checked");
	$("#termsandconditions").css({"top":$(window).scrollTop()+50+"px"});
	$("#termsandconditions").fadeOut(100, function() { 
	$("#buybackground").fadeOut(50);
	});
}
function donotagreetoterms() { 
	unfixbackground();
	$("#agreeterms").attr("checked",false);
	$("#termsandconditions").css({"top":$(window).scrollTop()+50+"px"});
	$("#termsandconditions").fadeOut(100, function() { 
	$("#buybackground").fadeOut(50);
	});
}



function viewcart() { 
	hideMiniCart();
	fixbackground();
	$("#buybackground").fadeIn(50, function() { 
		if(isslideshow) { 
			if(isslideshow == true) { 
				stopSlideshow();
			}
		}
		loading();
		pt = $("body").css("padding-top").replace("px", "");
		pt = Math.abs(pt);
		$("#photoprods").css({"top":50 + pt+"px"});
			$.get(tempfolder+"/sy-inc/store/store_view_cart_window.php", function(data) {
				$("#photoprodsinner").html(data);
				$("#photoprods").slideDown(200, function() { 
					if($("body").width() <= 800) { 
						$("#closebuyphototab").show();
					} else { 
						$("#closebuyphoto").show();
					}
					sizeBuyPhoto();
					loadingdone();

					$('html').click(function() {
					 closebuyphoto();
					 });

				 $('#photoprods').click(function(event){
					 event.stopPropagation();
				 });

				});
			});
	});
}


function buyphotothumb(pkg,pic,date_id,pos) { 
	if($("#vinfo").attr("prodplace") == "1") { 
		clickthumbnail(pic);
	} else { 
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey",pic);
		buyphoto(pkg,pic,date_id);
	}
}
function buyphoto(pkg,pic,date_id,fav_pl) { 
	fixbackground();
	if(fav_pl) { 
		fav_pl = fav_pl;
	} else { 
		fav_pl = '0';
	}
	$("#buybackground").fadeIn(50,function() { 
		if(isslideshow == true) { 
			stopSlideshow();
		}
		fixbackground();
		window.scrollTo(0,0);
		loading();
		if(!pic) { 
			pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		}
		if(!date_id) { 
			date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
			if(typeof date_id === "undefined") { 
				date_id = $("#vinfo").attr("did");
			}
		}
		sub_id = $("#vinfo").attr("sub_id");
		if($("#vinfo").attr("view") == "favorites") { 
			sub_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("subid");
		}

		$("#photoprods").css({"top":50+"px"});
			$.get(tempfolder+"/sy-inc/store/store_photos_buy_v2_list_products.php?pid="+pic+"&date_id="+date_id+"&mobile="+ismobile+"&sub_id="+sub_id+"&color_id="+$("#filter").attr("color_id")+"&packages="+pkg+"&group_id="+$("#vinfo").attr("group-id")+"&kid="+$("#vinfo").attr("kid")+"&keyWord="+$("#vinfo").attr("keyWord")+"&fav_pl="+fav_pl+"&view="+$("#vinfo").attr("view")+"&from_time="+$("#vinfo").attr("from_time")+"&search_date="+$("#vinfo").attr("search_date")+"&passcode="+$("#vinfo").attr("passcode")+"&search_length="+$("#vinfo").attr("search_length"), function(data) {
				$("#photoprodsinner").html(data);

				$("#photoprods").slideDown(200, function() { 
					window.scrollTo(0,0);
					if($("body").width() <= 800) { 
						$("#closebuyphototab").show();
					} else { 
						$("#closebuyphoto").show();
					}
					sizeBuyPhoto();
					loadingdone();

					$('html').click(function() {
					 closebuyphoto();
					 });

			 $('#photoprods').click(function(event){
				 event.stopPropagation();
			 });

				});
			});
	});
}
function showPackage() { 
	$("#buybackground").fadeIn(50);
	if(isslideshow == true) { 
		stopSlideshow();
	}
	loading();
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
	sub_id = $("#vinfo").attr("sub_id");
	if($("#photopackagecontainer").attr("status") == "0") { 
		$("#photopackagecontainer").css({"top":$(window).scrollTop()+50+"px"});
		$("#photopackagecontainer").attr("status", "1");
	}

		$.get(tempfolder+"/sy-inc/store/store_package_photo.php?pid="+pic+"&date_id="+date_id+"&sub_id="+sub_id+"&package_id="+$("#vinfo").attr("package-id")+"&view="+$("#vinfo").attr("view")+"&color_id="+$("#filter").attr("color_id"), function(data) {
			$("#photopackageinner").html(data);
			$("#photopackagecontainer").slideDown(200, function() { 
				$("#closeaddtopackage").show();
				sizeBuyPhoto();
				loadingdone();

				$('html').click(function() {
				 closeaddtopackage();
				 });

			 $('#photopackagecontainer').click(function(event){
				 event.stopPropagation();
			 });

			});
		});

}

function showPackageOne() { 
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
	sub_id = $("#vinfo").attr("sub_id");
	$.get(tempfolder+"/sy-inc/store/store_package_photo_one.php?pid="+pic+"&date_id="+date_id+"&sub_id="+sub_id+"&color_id="+$("#filter").attr("color_id"), function(data) {
		$("#singlephotopackagetabinner").html(data);
	});
}



function closeaddedpackage() { 
	hideMiniCart();
	if($("#vinfo").attr("prodplace") == "0") { 
		closebuyphoto();
	}
	headerheight = $("#ssheader").height();
	$("#ssClose").css({"top":headerheight+"px"});
}

function closeaddtopackage() { 
	$('html').unbind('click');
	$("#closeaddtopackage").hide();
	$("#buybackground").fadeOut(200);
	$("#photopackagecontainer").attr("status", "0");
	if($("#vinfo").attr("has_package_one") == 1 && $("#vinfo").attr("has_package") == "1") { 
		showPackageOne();
	}

	$("#photopackagecontainer").slideUp(200, function() { 
		$("#photopackageinner").html("");
		// $("#thumbpreview").hide();
	});
}



function sizeBuyPhoto() { 
	w = $("#thumbpreview").attr("dw");
	h = $("#thumbpreview").attr("dh");
	padding =$("#photoprodsinner").css('padding-top');
	padding = padding.replace("px", "");

	ow = Math.abs($("#photobuyview").width() - Math.abs(padding));
//	alert(ow+" > "+w);
	if(ow < w) { 
		p = ow / w;
		h = Math.abs($("#thumbpreview").attr("dh")) * p;
		$("#thumbpreview").css({"width":ow+"px", "height":h+"px", "background-size": ow+"px "+h+"px"});
		$("#thumbpreview").attr("dw",ow);
		$("#thumbpreview").attr("dh",h);
	}
	$("#thumbpreview").fadeIn(200, function() { 
	});
}

function filterPhoto(color,colorname,pic) { 
	if(isslideshow == true) { 
		stopSlideshow();
	}
	if(!pic) { 
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");

	}
	$("#filter").children(".filteroption").hide();

	pfid = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pfid");
	cid = $("#photo-"+$("#slideshow").attr("curphoto")).attr("cid");
	if(color == "original") { 
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("src", tempfolder+"/sy-photo.php?thephoto="+pic+"|"+pfid+"|"+cid+"");
		$("#filtername").html("");
		$("#filter").attr("color_id","");
	} else { 
	loading();

	$("#photo-"+$("#slideshow").attr("curphoto")).hide();
	$("#photo-"+$("#slideshow").attr("curphoto")).attr("src", "");

	$("#photo-"+$("#slideshow").attr("curphoto")).attr("src", tempfolder+"/sy-photo.php?thephoto="+pic+"|"+pfid+"|"+cid+"|"+color);
		$("#photo-"+$("#slideshow").attr("curphoto")).imagesLoaded(function() {
		$("#photo-"+$("#slideshow").attr("curphoto")).fadeIn(200);
			//$("#filtername").html(colorname);
			$("#filter").attr("color_id", color);
			loadingdone();
		});
	}
}

function removeFilterPhoto() { 
	if($("#filter").attr("color_id") !=="") { 
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		pfid = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pfid");
		cid = $("#photo-"+$("#slideshow").attr("curphoto")).attr("cid");
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("src", $("#photo-"+$("#slideshow").attr("curphoto")).attr("thissrc"));
		$("#filtername").html("");
		$("#filter").attr("color_id","");
	}
}

function adjustqty(id,dir) {
	if(dir == "up") { 
		val = parseInt($("#"+id).val()) + 1;
	}
	if(dir == "down") { 
		val = parseInt($("#"+id).val()) - 1;
		if(val < 1) { 
			val = 1;
		}
	}
	$("#"+id).val(val);
}   


function cropphoto(pic,photoprod,cart_id,rotate,change,disable) { 
//	$("#buybackground").fadeIn(50);
	hideMiniCart();
	$('html').unbind('click');
	loading();
	if(!pic) { 
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	}

	$("#photocrop").css({"top":$(window).scrollTop()+50+"px"});
		$.get(tempfolder+"/sy-inc/store/store_photo_crop.php?pid="+pic+"&photoprod="+photoprod+"&cart_id="+cart_id+"&rotate="+rotate+"&change="+change+"&disable="+disable, function(data) {
			$("#photocropinner").html(data);
			$("#photocrop").slideDown(200, function() { 
				$("#closephotocrop").show();
	//			sizeBuyPhoto();
				loadingdone();
			});
		});
}

function closecropphoto() { 
	$('html').unbind('click');
	$("#photocrop").slideUp(200, function() { 
		$("#photocropinner").html("");
	});
}

function addphotofav() { 
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	date_id = $("#vinfo").attr("did");
	pos = $("#slideshow").attr("curphoto");

	if($("#photo-"+$("#slideshow").attr("curphoto")).attr("fav") == "1") { 
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removefromfavs&pid="+pic+"&did="+date_id, function(data) {	
			$("#favoritestotaltop").html(data);
			$(".favoritestotaltop").html(data);
		});
		$(".photo-fav").removeClass("icon-heart").addClass("icon-heart-empty");
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("fav", "0");
		$("#photo-fav-"+pos).removeClass("icon-heart").addClass("icon-heart-empty");
	} else { 
		$(".photo-fav").removeClass("icon-heart-empty").addClass("icon-heart");
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("fav", "1");
		$("#photo-fav-"+pos).removeClass("icon-heart-empty").addClass("icon-heart");
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=addtofavs&pid="+pic+"&did="+date_id+"&sub_id="+$("#photo-"+$("#slideshow").attr("curphoto")).attr("subid"), function(data) {
			$("#favoritestotaltop").html(data);
			$(".favoritestotaltop").html(data);
		});
		$(".favoritesviewing").show();
	}
}

function comparephoto() { 
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
	pos = $("#slideshow").attr("curphoto");
	sub_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("subid");

	if($("#photo-"+$("#slideshow").attr("curphoto")).attr("compare") == "1") { 
		$(".checked").hide();
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removecompare&pid="+pic+"&did="+date_id+"&sub_id="+sub_id, function(data) {	
			$("#comparetotal").html(data);
			if(data > 1) { 
				$("#comparebar").slideDown(100, function() { 
					sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
				});
			} else { 
				$("#comparebar").slideUp(100, function() { 
					sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
				});
			}
		});
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("compare", "0");
	} else { 
		$(".checked").show();
		$("#photo-"+$("#slideshow").attr("curphoto")).attr("compare","1");

		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=addtocompare&pid="+pic+"&did="+date_id+"&sub_id="+sub_id, function(data) {
			$("#comparetotal").html(data);
			if(data > 1) { 
				$("#comparebar").slideDown(100, function() { 
					sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
				});
			} else { 
				$("#comparebar").slideUp(100, function() { 
					sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
				});
			}
		});
	}
}

function showcomparephotos() { 
	parent.location.hash = '#compare';
	$("#slideshow").attr("comparephotos","1")
	$("#comparephotos").fadeIn(100);
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=comparephotosshow", function(data) {	
		window.scrollTo(0,0);
		$("body").css({"overflow-y":"hidden"});
		$("#comparephotosdisplay").html("").append(data).css({"margin-top":$("#comparenav").outerHeight()+"px", "top":"0"});
		newh = $(window).height() - $("#comparenav").outerHeight();
		// $("#log").show().html($(window).height()+" outter height:  "+$("#comparenav").outerHeight()+" = "+newh);
		$(".comparetd").css({"height":newh+"px"});
		$("#comparephotosdisplaycontainer").css({"top":$("#comparenav").outerHeight()+"px"});
		$("#comparephotosdisplaycontainer").css({"height":newh+"px"});
		$(".comparephoto").each(function(i){
			if($(this).attr("hh") > newh) { 
				$(this).css({"height":newh+"px"});
			}
		})

		$(".comparetable").css({"height":newh+"px", "display":"block", "top":"0","position":"absolute", "margin":"auto"});
		$("#comparephotosdisplay").fadeIn(100, function() { 
			$(".compareactionscontainer").each(function(i){
				po = $(this).parent().offset().left;
				$(this).css({"position":"absolute", "left":po + 20+"px","top":$(this).parent().find(".comparephoto").position().top + 40+"px"});
				// $("#log").show().append(":  "+$(this).parent().find(".comparephoto").position().top);
			})
			
		});
	});
}
function removecompareview(p,td,key) { 
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removecompareview&p="+p, function(data) {	
		$("#"+td).fadeOut(100, function() { 
			
		if($("#slideshow").attr("curphoto") == $("[pkey="+key+"]").attr("ppos")) { 
			$(".checked").hide();
		}

		$("[pkey="+key+"]").attr("compare","0");

			$("#comparetotal").html(data);
			if(data <=0) { 
				closecomparephotos();
			}
			if(data == 1) { 
				$("#comparebar").slideUp(100, function() { 
					sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
				});
			}
			$(".compareactionscontainer").each(function(i){
				po = $(this).parent().offset().left;
				$(this).css({"position":"absolute", "left":po + 20+"px","top":$(this).parent().find(".comparephoto").position().top + 40+"px"});
				// $("#log").show().append(":  "+$(this).parent().find(".comparephoto").position().top);
			})
			

		});
	});

}

function compareviewclick(u) { 
	window.location.href=u;
}

function closecomparephotos(clear) { 
	$("#slideshow").attr("comparephotos","0");
	if(clear == "1") { 
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removeallcompare", function(data) {	
			$(".checked").hide();
			$(".photofull").each(function(i){
				$(this).attr("compare","0");
			});
			$(".photo").each(function(i){
				$(this).attr("compare","0");
			});

			$("#comparebar").slideUp(100, function() { 
				sizePhoto($("#slideshow").attr("curphoto"));	
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
			});

		});
	}


	$("#comparephotosdisplay").fadeOut(100, function() { 
		$("#comparephotosdisplay").html("");
		$("#comparephotos").fadeOut(100);
		$("body").css({"overflow-y":"auto"});
	});
	

}


function addphotofavthumb(pos,pic_key) { 
	gsbg = $("#gs-gal-id").val();
	if($("#photo-fav-"+pic_key).hasClass("icon-heart")) { 
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removefromfavs&pid="+pic_key+"&did="+$("#photo-fav-"+pic_key).attr("did")+"&gsbg="+gsbg, function(data) {	
			$("#favoritestotaltop").html(data);
			$(".favoritestotaltop").html(data);
		});
		$("#photo-fav-"+pic_key).removeClass("icon-heart").addClass("icon-heart-empty");
		$("#photo-"+pic_key).attr("fav", "0");
	} else { 
		$("#photo-fav-"+pic_key).removeClass("icon-heart-empty").addClass("icon-heart");
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=addtofavs&pid="+pic_key+"&did="+$("#photo-fav-"+pic_key).attr("did")+"&sub_id="+$("#photo-fav-"+pic_key).attr("sub_id")+"&gsbg="+gsbg, function(data) {
			$("#favoritestotaltop").html(data);
			$(".favoritestotaltop").html(data);
		});
	}
}


function hidephoto(pos,pic_key) { 
	gsbg = $("#gs-gal-id").val();
	if($("#photo-hide-"+pic_key).hasClass("on")) { 
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=unhidephoto&pid="+pic_key+"&did="+$("#photo-hide-"+pic_key).attr("did")+"&gsbg="+gsbg, function(data) {	
			// $("#favoritestotaltop").html(data);
			// $(".favoritestotaltop").html(data);
		});
		$("#th-"+pic_key).removeClass("hiddenphoto");
		if($("#photo-hide-"+pic_key).removeClass("on"));
		$(".photo-message-"+pic_key).html("").hide();
		// $("#photo-hide-"+pic_key).attr("title",$("#photo-hide-"+pic_key).attr("data-hide"));
	} else { 
		$("#th-"+pic_key).addClass("hiddenphoto");
		if($("#photo-hide-"+pic_key).addClass("on"));
		$(".photo-message-"+pic_key).html($("#photo-hide-"+pic_key).attr("data-hidden-message")).show();
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=hidephoto&pid="+pic_key+"&did="+$("#photo-hide-"+pic_key).attr("did")+"&sub_id="+$("#photo-fav-"+pic_key).attr("sub_id")+"&gsbg="+gsbg, function(data) {
		//	$("#favoritestotaltop").html(data);
		//	$(".favoritestotaltop").html(data);
		});
	}
}


function hidephotofull(pos,pic_key) { 
	gsbg = $("#gs-gal-id").val();
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	date_id = $("#vinfo").attr("did");

	if(Math.abs($("#photo-"+pic).attr("pic_hide")) > 0) { 
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=unhidephoto&pid="+pic+"&did="+date_id+"&gsbg="+gsbg, function(data) {	
			// $("#favoritestotaltop").html(data);
			// $(".favoritestotaltop").html(data);
		});
		$("#th-"+pic).removeClass("hiddenphoto");
		if($("#photo-hide-"+pic).removeClass("on"));
		if($(".photo-message-"+pic)) { 
			$(".photo-message-"+pic).html("").hide();
		}
		$("#hidden-full-photo").hide();
		$("#photo-"+pic).attr("pic_hide","0")
		// $("#photo-hide-"+pic).attr("title",$("#photo-hide-"+pic).attr("data-hide"));
	} else { 
		$("#th-"+pic).addClass("hiddenphoto");
		if($("#photo-hide-"+pic).addClass("on"));
		if($(".photo-message-"+pic)) { 
			$(".photo-message-"+pic).html($("#photo-"+pic).attr("ht")).show();
		}
		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=hidephoto&pid="+pic+"&did="+date_id+"&gsbg="+gsbg, function(data) {
		$("#hidden-full-photo").show();
		$("#photo-"+pic).attr("pic_hide","1")
		//	$("#favoritestotaltop").html(data);
		//	$(".favoritestotaltop").html(data);
		});
	}
}


function removefavthumb(pos,pic_key) { 
	date_id = $("#vinfo").attr("did");
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=removefromfavs&pid="+pic_key+"&did="+date_id, function(data) {	
		$("#favoritestotal").html(data);
		$("#favoritestotaltop").html(data);
			$(".favoritestotaltop").html(data);
	});
	$("#styledthumb-"+pos).fadeOut(200);

}

function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}


function sharephoto(type,url,appid,name,siteurl,pub) { 
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	url = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pagelink");
	descr = $("#shareoptions").attr("data-share-descr");

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


	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=logshare&did="+$("#vinfo").attr("did")+"&where="+type+"&pic="+pic, function(data) {	});



	if(type == "email") { 
		PopupCenter("mailto:?subject=Photo&body="+url+""+encodeURIComponent(sep)+""+imgvar+"="+pic+"%0D%0A"+descr, type, 640, 480)
	}


	if(type == "facebook") { 
		PopupCenter("http://www.facebook.com/dialog/share?app_id="+appid+"&href="+url+""+encodeURIComponent(sep)+""+imgvar+"="+pic+"&picture="+$("#photo-"+$("#slideshow").attr("curphoto")).attr("sharefile")+"&name="+name+"&description="+descr+"&display=popup&close=true", type, 800, 480)

	}

	if(type == "pinterest") { 
		PopupCenter("//pinterest.com/pin/create/button/?url="+url+""+encodeURIComponent(sep)+""+imgvar+"="+pic+"&media="+$("#photo-"+$("#slideshow").attr("curphoto")).attr("sharefile")+"&description="+name, type, 640, 480)
	}

	if(type == "twitter") { 
		PopupCenter("https://twitter.com/intent/tweet?text="+name+"&url="+url+""+encodeURIComponent(sep)+""+imgvar+"="+pic, type, 640, 480)
	}
}

function sharephotothumb(type,url,pic,appid,name,siteurl,pub, pic_key) { 
//	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
//	url = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pagelink");
	descr = $("#displayThumbnailPage").attr("data-share-descr");

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


	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=logshare&did="+$("#vinfo").attr("did")+"&where="+type+"&pic="+pic_key, function(data) {	});



	if(type == "email") { 
		PopupCenter("mailto:?subject=Photo&body="+url+"%0D%0A"+descr, type, 640, 480)
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
function selectPaymentOption(thisopt,thisform) { 
	$(".payoption").each(function(i){
		var this_id = this.id;
		$("#"+this_id).hide();
	} );
	$("#"+thisform).show();
	if(thisopt == "square"){ 
		sqPaymentForm.setPostalCode($("#zip").val());
		$("#checkout").attr("action",$("#checkout").attr("data-square-action"));
	} else { 
		$("#checkout").attr("action",$("#checkout").attr("data-action"));
	}
}

function selectPaymentFromOrder() { 
	$("#paymentdiv").css({"top":$(window).scrollTop()+"px"});
	if($("#square-sel").attr("checked")) { 
		sqPaymentForm.setPostalCode($("#zip").val());
	}
	$("#paymentdivbg").fadeIn(50, function() { 
		$("#paymentdiv").fadeIn(100, function() { 
			$("#closepaymentdiv").fadeIn(50);
		});
	});
}
function closeSelectPaymentFormOrder() { 
	$("#closepaymentdiv").fadeOut(100);
	$("#paymentdiv").fadeOut(100, function() { 

		$("#paymentdivbg").fadeOut(100);
	});

}

function ppexpresscheckout() { 
	hideMiniCart();
	fixbackground();
	$("#buybackground").fadeIn(50, function() { 
		if(isslideshow) { 
			if(isslideshow == true) { 
				stopSlideshow();
			}
		}
		loading();
		$("#photoprods").css({"top":50+"px", "max-width":"400px","width":"100%","margin-left":"-200px"});
			$.get(tempfolder+"/sy-inc/store/store_express.php", function(data) {
				$("#photoprodsinner").html(data);
				$("#photoprods").slideDown(200, function() { 
					if($("body").width() <= 800) { 
						$("#closebuyphototab").show();
					} else { 
						$("#closebuyphoto").show();
					}
					loadingdone();

					$('html').click(function() {
					 closebuyphoto();
					 });

				 $('#photoprods').click(function(event){
					 event.stopPropagation();
				 });

				});
			});
	});
}
