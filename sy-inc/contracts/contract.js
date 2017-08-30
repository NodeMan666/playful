function replacecontractfields() { 
	$(".contractfield").each(function(i){
		if($(this).hasClass("textinput")) { 
			// alert($(this).val());
			if($(this).val() == "") { 
				$(this).replaceWith('<span class="contracttextinput" style="padding: 0px 8px; border-bottom: dashed 1px #000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
			} else { 
				$(this).replaceWith('<span class="contracttextinput" style="padding: 0px 8px; border-bottom: dashed 1px #000000;">'+$(this).val()+'</span>');
			}
		}
		if($(this).hasClass("checkboxinput")) { 
			if($(this).prop('checked') == true) { 
				$(this).replaceWith('<span style="font-size: 12px; padding: 2px; text-align: center; margin-right: 12px; width: 12px; height: 12px; display: inline-block; border: dashed 1px #000000;">X</span>');
			} else { 
				$(this).replaceWith('<span style="font-size: 12px; padding: 2px; text-align: center; margin-right: 12px; width: 12px; height: 12px; display: inline-block; border: dashed 1px #000000;">&nbsp;</span>');
			}
		}


	});
}

function signcontract(id,field) { 
	$("#emptyfieldsmessage").slideUp(100);
	$("#"+field+"-error-empty").slideUp(100);
	$("#"+field+"-error-match").slideUp(100);
	if($("#"+field).val() =="" || $("#"+field).val() == $("#"+field).attr("title")) { 
		$("#"+field+"-error-empty").slideDown(200);
	} else  if(($("#"+field).val() !== $("#"+field).attr("data-name")) && ($("#"+field).attr("data-mismatch") !== "1")) { 
		$("#"+field+"-error-match").slideDown(200);
		$("#"+field).attr("data-mismatch","1")
	} else { 
		$(".contractrequired").removeClass("requiredcontractfieldempty");
		$(".contractrequired").removeClass("requiredcontractfieldemptycheckbox");
		$(".contractrequired").each(function(i){
			if($(this).attr("type") == "checkbox") { 
				if($(this).prop("checked") == false) { 
					$(this).addClass("requiredcontractfieldemptycheckbox");
					stop = true;
				}
			} else { 
				if($(this).val() == "") { 
					$(this).addClass("requiredcontractfieldempty");
					stop = true;
				}
			}
		});
		if(stop == true) { 
			$("#emptyfieldsmessage").slideDown(200);
		}
		if(stop !== true) { 

			var fields = {};
			$("#loadingbg").fadeIn(50);
			fields['action'] = "signContract";
			fields['contract_id'] = id;
			fields['field'] = field;
			fields['signature'] = $("#"+field).val();
			fields['sign_ip'] = $("#"+field+"_ip").val();
			fields['sign_browser'] = encodeURIComponent($("#"+field+"_browser").val());
			fields['field'] = field;
			if($('#make_default').prop('checked') == true) { 
				fields['make_default'] = 1;
			} else { 
				fields['make_default'] = 0;
			}
			replacecontractfields();
			fields['contractcontent'] = $("#contractcontent").html();

			$.post(tempfolder+"/sy-inc/store/store_cart_actions.php", fields,	function (data) { 
				$("#"+field+"-writeform").hide();
				$("#"+field+"-form").hide();
				$("#"+field+"-sig").html($("#"+field).val());
				$("#"+field+"-date").html(data);
				$("#"+field+"-signed").show();
				$("#"+field+"-date-container").show();
				signed = Math.abs($("#contract").attr("data-total-signed"));
				totalsigned = signed + 1;
				$("#loadingbg").fadeOut(50);

				if($("#contract").attr("data-total-sign") == "1") { 
					if(Math.abs($("#contract").attr("data-invoice")) > 0) { 
						showpopup("popupinvoice");
						$("#invoicelink").show();
					} else { 
						showpopup("popupdone");
					}
				} else if(Math.abs($("#contract").attr("data-total-sign")) > 1) { 
					if(totalsigned == $("#contract").attr("data-total-sign")) { 
						if(Math.abs($("#contract").attr("data-invoice")) > 0) { 
							showpopup("popupinvoice");
							$("#invoicelink").show();
						} else { 
							showpopup("popupdone");
						}
					} else { 
						showpopup("popupadditionalsig");
					}
				}
				$("#contract").attr("data-total-signed",totalsigned)
			});
		}
	}
	return false;
}


function savesigsvg(id,field,sigid) { 
	stop = false
	$("#emptyfieldsmessage").slideUp(100);

	$("#"+sigid+"-error").slideUp(100);
	if($("#"+sigid).signature('isEmpty')) { 
		$("#"+sigid+"-error").slideDown(200);
	} else { 
		$(".contractrequired").removeClass("requiredcontractfieldempty");
		$(".contractrequired").removeClass("requiredcontractfieldemptycheckbox");
		$(".contractrequired").each(function(i){
			if($(this).attr("type") == "checkbox") { 
				if($(this).prop("checked") == false) { 
					$(this).addClass("requiredcontractfieldemptycheckbox");
					stop = true;
				}
			} else { 
				if($(this).val() == "") { 
					$(this).addClass("requiredcontractfieldempty");
					stop = true;
				}
			}
		});
		if(stop == true) { 
			$("#emptyfieldsmessage").slideDown(200);
		}
		if(stop !== true) { 
			$("#loadingbg").fadeIn(50);

			var fields = {};
			fields['action'] = "signContract";
			fields['contract_id'] = id;
			fields['field'] = field;
			fields['signature_svg'] = encodeURIComponent($('#'+sigid).signature('toSVG'));

			fields['sign_ip'] = $("#"+field+"_ip").val();
			fields['sign_browser'] = encodeURIComponent($("#"+field+"_browser").val());
			fields['field'] = field;
			if($('#make_default').prop('checked') == true) { 
				fields['make_default'] = 1;
			} else { 
				fields['make_default'] = 0;
			}

			replacecontractfields();
			fields['contractcontent'] = $("#contractcontent").html();


			$.post(tempfolder+"/sy-inc/store/store_cart_actions.php", fields,	function (data) { 
				$("#"+field+"-writeform").hide();
				$("#"+field+"-form").hide();
				$("#"+field+"-sig").html('<div class="svgsign">'+$('#'+sigid).signature('toSVG')+'</div>');
				$("#"+field+"-date").html(data);
				$("#"+field+"-signed").show();
				$("#"+field+"-date-container").show();
				$("#loadingbg").fadeOut(50);

				signed = Math.abs($("#contract").attr("data-total-signed"));
				totalsigned = signed + 1;
				if($("#contract").attr("data-total-sign") == "1") { 
					if(Math.abs($("#contract").attr("data-invoice")) > 0) { 
						showpopup("popupinvoice");
						$("#invoicelink").show();
					} else { 
						showpopup("popupdone");
					}
				} else if(Math.abs($("#contract").attr("data-total-sign")) > 1) { 
					if(totalsigned == $("#contract").attr("data-total-sign")) { 
						if(Math.abs($("#contract").attr("data-invoice")) > 0) { 
							showpopup("popupinvoice");
						$("#invoicelink").show();
						} else { 
							showpopup("popupdone");
						}
					} else { 
						showpopup("popupadditionalsig");
					}
				}
				$("#contract").attr("data-total-signed",totalsigned)
			});
		}
	}
}


$(function() {
	$('#sig').signature({guideline: true, 
    guidelineOffset: 15, guidelineIndent: 20, guidelineColor: '#444444'});
	$('#clear').click(function() {
		$('#sig').signature('clear');
	});
	$('#sig2').signature({guideline: true, 
    guidelineOffset: 15, guidelineIndent: 20, guidelineColor: '#444444'});
	$('#clear2').click(function() {
		$('#sig2').signature('clear');
	});
	$('#sigmy').signature({guideline: true, 
    guidelineOffset: 15, guidelineIndent: 20, guidelineColor: '#444444'});
	$('#clearmy').click(function() {
		$('#sigmy').signature('clear');
	});
});


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
});

function changeform(num) { 
	$("#write-form-"+num).slideUp(100, function() { 
		$("#type-form-"+num).slideDown(200);
	});
}


function showpopup(div) { 

	$("#fadebg").attr("data-window",div);
	$("#fadebg").prop('onclick',null).off('click');
	$("#fadebg").fadeIn(100, function() { 
		if($(window).scrollTop() < 80) { 
			to = 80;
		} else { 
			to = $(window).scrollTop() + 80;
		}
		$("#"+div).css({"top":to+"px"}).fadeIn(200);
		$("#fadebg").bind('click', function() {closepopup() });

	});
}

function closepopup() { 
	$("#"+$("#fadebg").attr("data-window")).fadeOut(100, function() { 
		$("#fadebg").fadeOut(100);
		$("#fadebg").attr("data-window","");
	});

}
function printit() { 
	$("#"+$("#fadebg").attr("data-window")).hide(); 
	$("#fadebg").hide();
	$("#fadebg").attr("data-window","");
	$("#signature2").val("");
	$("#signature").val("");
	window.print();
}
