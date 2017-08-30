$(".tab").click(function() { 
	var picinfo = $('.flexslider').find('li.flex-active-slide').attr('rel');
	/*$.get(tempfolder+"/sy-inc/store/store_package_photo.php?pid="+picinfo+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&view="+$("#vinfo").attr("view")+"&package_id="+$("#vinfo").attr("package-id")+"&color_id="+$("#filter").attr("color_id")+"&istablet=1", function(data) {
		//$.html($('#tab-groups', data));
		if($('#pack_limited',data).length > 0) {
				if($('.error').length == 0) {
					$(".group-"+$('.tabon')[0].getAttribute('gid') + "1").find('.message').prepend('<div class="error">You have reached the limit of poses for this package</div>');
					$('.the-addbutton').css('display', 'none');
				} else {
					return false;	
				}
			} else {
			$('.error').remove();
			$('.the-addbutton').css('display','block');
		}
	});*/
	var type = $("#tabtype").val();
	if (type == 1) {
		if($(this).attr('class').indexOf('tabon') === -1)
		{
			$(this).addClass('disabled');
			$("#msg").fadeIn();
			setTimeout(function(){ $("#msg").fadeOut(); }, 5000);
		}
		return;	
	}
	$(".tab").removeClass("tabon");
	$(".prodgroups").hide();
	$(".group-"+$(this).attr("gid")).show();
	$("#vinfo").attr("group-id", $(this).attr("gid"));
	$(this).addClass("tabon");
	var elProdList = $(".prodgroups")[1];
	$(".checkoutpagebutton.onlistpage").css("display",($(elProdList).css("display")));
	
});

$(".tab").mouseover(function() { 
	var type = $("#tabtype").val();
	if (type == 1) {
		if($(this).attr('class').indexOf('tabon') === -1)
		{
			$(this).addClass('disabled');
		}
		return;	
	}
});

$('.flexslider').flexslider({
	    aniamtion: "slide",
	    slideshow: false,
	    after: function() {
	    	var picinfo = $('.flexslider').find('li.flex-active-slide').attr('rel');
	    	$.get(tempfolder+"/sy-inc/store/store_package_photo.php?pid="+picinfo+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&view="+$("#vinfo").attr("view")+"&package_id="+$("#vinfo").attr("package-id")+"&color_id="+$("#filter").attr("color_id")+"&istablet=1", function(data) {
	    		$('#tab-groups').html($('#tab-groups', data).html());
	    		/*if($('#pack_limited',data).length > 0) {
	   				if($('.error').length == 0) {
	   					$(".group-"+$('.tabon')[0].getAttribute('gid') + "1").find('.message').prepend('<div class="error">You have reached the limit of poses for this package</div>');
    					$('.the-addbutton').css('display', 'none');
	   				} else {
	   					return false;	
	   				}
	 			} else {
	    			$('.error').remove();
	    			$('.the-addbutton').css('display','block');
	    		}*/
	    	});
	    },
});
function cropthumbpreview(cw,ch) { 
	orgWidth = Math.abs($("#thumbpreview").attr("dw"));
	orgHeight = Math.abs($("#thumbpreview").attr("dh"));
	cw = Math.abs(cw);
	ch = Math.abs(ch);
	if(cw > ch) {
		cp = cw / ch;
	} else {
		cp = ch / cw;
	}


	if(orgWidth > orgHeight) {
		imp = orgWidth / orgHeight;

		if(imp > cp) {
			height = orgHeight;
			width = Math.round(orgHeight  * cp);
		} else{
			width = orgWidth;
			height = Math.round(orgWidth / cp);
		}
	} else {
		imp = orgHeight / orgWidth;
		if(imp > cp) {
			height =  Math.round(orgWidth * cp);
			width =orgWidth;
		} else {
			height = orgHeight;
			width = Math.round(orgHeight / cp);
		}
	}
//	$("#log").html("imp: "+imp+" cp: "+cp+" width: "+width+" height: "+height+" orgw: "+orgWidth+" orgh: "+orgHeight);
//	alert("cp: "+cp+" "+cw+" X "+ch+" width: "+width+" X height"+height);

		
	$("#thumbpreview").css({"width":width+"px", "height":height+"px"});
}

function cropthumbpreviewclose() { 
	orgWidth = $("#thumbpreview").attr("dw");
	orgHeight = $("#thumbpreview").attr("dh");
	$("#thumbpreview").css({"width":orgWidth+"px", "height":orgHeight+"px"});

}

$('.prodoption').change(function() {
	price = 0;
	addprice = 0;
	$(".prod-"+$(this).attr("prodid")).each(function(){
		if($(this).hasClass('prodoption')) { 

			if($(this).hasClass('inputdropdown')) { 
				var addprice = Math.abs($('option:selected', this).attr("price"));
			}

			if($(this).hasClass('inputradio')) { 
				var addprice = Math.abs($('input:radio[name='+$(this).attr("name")+']:checked').attr("price"));
			}
			if($(this).hasClass('inputcheckbox')) { 
				if($(this).attr("checked")) { 
					var addprice = Math.abs($(this).attr("price"));
				} else { 
					var addprice = 0;
				}
			}
			if($(this).hasClass('inputtext')) { 
				if($(this).val()!=="") { 
					var addprice = Math.abs($(this).attr("price"));
					} else { 
					var addprice = 0;
				}

			}
			price = price + addprice;
		}
	});

	var orgprice = Math.abs($("#price-"+$(this).attr("prodid")).attr("orgprice"));
	var newprice = price + orgprice;


	var checkdecimals = newprice.toString();
	var splitprice = checkdecimals.split('.');
	if(!splitprice[1]) { 
	//	alert(found[0].add_price+" - "+newprice+" - "+splitprice[1]);
		var newprice = newprice.toFixed(decimals);
	} else { 
		var newprice = newprice.toFixed(2);
	}
	newformat = priceformat.replace("[PRICE]", newprice); // value = 9:61
	newformat = newformat.replace("[CURRENCY_SIGN]", currency_sign); // value = 9:61
	
//	alert(price+" + "+orgprice+" = "+newprice+" = "+newformat);

	$("#price-"+$(this).attr("prodid")).html(newformat);
});

$(".underline").hover(
	function() {
		cw = $(this).attr("cw");
		ch = $(this).attr("ch");
		if(cw > 0) { 

		$("#cropcartmessage").show();
		}
},
function() {
	cropthumbpreviewclose();
	$("#cropmessage").hide();
		$("#cropcartmessage").hide();
	}
);


function addphotoprodtocart(classname,id,qty_discount,pic_id) { 

	var detectFlag = false;
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		detectFlag = true;

 		var picinfoStr = $('.flexslider').find('li.flex-active-slide').find('img').attr('id');
    	var picinfoAry = picinfoStr.split('-');
    	var picinfo = picinfoAry[1];
	}


  
  	var fields = {};
	var stop = false;
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
			if((detectFlag == true) && ($this.attr('name') == 'pid'))  {
				fields['pid'] = picinfo;	
			} else {
				fields[$this.attr('name')] = $this.val(); 
			} 
		//	alert($this.attr('name')+" = "+$this.val());
		}
		fields['color_id'] = $("#filter").attr("color_id");
		fields['cart_photo_bg'] = $("#gs-bgimage-id").val();


	//	fields['pid'] = $("#photo-"+pos).attr("pkey");
	//	fields['did'] = $("#photo-"+pos).attr("did");
	//	fields['sub_id'] = $("#vinfo").attr("sub_id");
		fields['group_id'] = $("#vinfo").attr("group-id");



		if($this.hasClass("required")) { 
			if($this.val() == "") { 
				alert("Please select: "+$this.attr("fieldname"));
				stop = true;
			}
		}


	});

	if(fields['qty'] < 1) { 
		alert("Quantity can not be less than 1");
		stop = true;
	}

	if(stop == false) { 
		$("#addcart-"+id).hide();
		$("#addcartloading-"+id).show();

		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			showMiniCart();
			//alert(data);
			$(".optionsopen").slideUp(200);
			if(qty_discount == "1") { 
				$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=newqtyprice&pc_id="+id+"&pic_id="+pic_id+"", function(data) {
					data = $.trim(data);
					info = data.split('|'),
					$("#price-"+id).html(info[0]);
					$("#qty-message-"+id).html(info[1]);
					$("#qty-message-"+id).show();
				});
			}

			 $("#submitinfo").html(data);
			updateCartMenu();
			//updateCartMobileMenu();
			setTimeout(function(){
				pos = $("#slideshow").attr("curphoto");
			if(fields['prod_package_id']>0) {
				packagenexttophoto(pos);
			} else if($("#vinfo").attr("view_package_only") == 1 && $("#vinfo").attr("has_package") > 0) { 
				// packagenexttophoto(pos);
			} else {
				if($("#vinfo").attr("prodplace") == "0") { 
					buyphoto('',fields['pid']);
				}
				productsnexttophoto(pos);
			}

			$("#addcart-"+id).show();
			$("#addcartloading-"+id).hide();
			},500)
		 } );
	}
	return false;
	

}

function addpackagetocart(classname,id) { 
	var fields = {};
	var stop = false;
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
			//	alert(fields[$this.attr('name')]);
			}

		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}
		if($this.hasClass("required")) { 
			if($this.val() == "") { 
				alert("Please select: "+$this.attr("fieldname"));
				stop = true;
			}
		}


	});
	fields['cart_photo_bg'] = $("#gs-bgimage-id").val();

	if(fields['qty'] < 1) { 
		alert("Quantity can not be less than 1");
		stop = true;
	}

	if(stop == false) { 
		$("#addcart-"+id).hide();
		$("#addcartloading-"+id).show();

		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			showMiniCart("1");
			totalpackage = Math.abs($("#vinfo").attr("has_package"));

			$("#vinfo").attr("has_package",totalpackage + 1);
		//	alert($("#package_select_only-"+id).val()+" && "+totalpackage);
			
			if($("#slideshow").attr("fullscreen") == "1") { 
				packagenexttophoto($("#slideshow").attr("curphoto"));
					sizePhoto($("#slideshow").attr("curphoto"));		
				}
				if($("body").width() <= lppw) { 
					$("#photopackagetab").fadeIn(100);
				}
			$("#vinfo").attr("view_package","1")
				$(".packagethumb").show();
			// alert(data);
			 // $("#submitinfo").html(data);
			updateCartMenu();
			setTimeout(function(){

			$("#addcart-"+id).show();
			$("#addcartloading-"+id).hide();
			},500)
		 } );
	}
	return false;
	

}

function addbuyalltocart(classname,id) { 
	var fields = {};
	var stop = false;
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
	if($this.hasClass("required")) { 
		if($this.val() == "") { 
			alert("Please select: "+$this.attr("fieldname"));
			stop = true;
		}
	}


	});

	fields['cart_photo_bg'] = $("#gs-bgimage-id").val();

	if(stop == false) { 
		$("#addcart-"+id).hide();
		$("#addcartloading-"+id).show();

		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
		//	alert(data);
			showMiniCart("1");
			updateCartMenu();
			setTimeout(function(){
				pos = $("#slideshow").attr("curphoto");

			productsnexttophoto(pos);

			$("#addcart-"+id).show();
			$("#addcartloading-"+id).hide();
			},500)
		 } );
	}
	return false;
	

}