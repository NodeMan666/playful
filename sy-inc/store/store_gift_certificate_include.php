<script>
function purchasegiftcertificate() { 
	var fields = {};
	var rf = false;
	var stop;
	var mes;
	 $("#gcresponse").removeClass("error").slideUp(100);
	$("#amounterror").slideUp(100);
	if($("#amount").val() == "") { 
		$("#amounterror").slideDown(200);

	} else { 
		$(".gcrequired").each(function(i){
			var this_id = this.id;
			if($('#'+this_id).val() == "") { 
				$('#'+this_id).addClass('inputError');
				rf = true;
			} else { 
				$('#'+this_id).removeClass('inputError');
			}
		} );



		if(rf == true || stop == true) {
			if(rf == true) {
				$("#gcresponse").slideDown(200);
			}
			return false;
		} else { 

			$("#submit").attr("disabled",true);
			$('.gcfield').each(function(){
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
					fields[$this.attr('id')] = $this.val(); 
					//fields[$this.attr('name')] = $this.val(); 
				}
			});
			$.post(tempfolder+'/sy-inc/store/store_gift_certificate.php', fields,	function (data) { 
				data = $.trim(data);
				response = data.split('|');
				updateCartMenu();
				$("#gcform").slideUp(200, function() { 
					$("#gcform").html("");
					$("#gcsuccess").slideDown(200, function() { 
				   $('html').animate({scrollTop:'50'}, 300); 
					$('body').animate({scrollTop:'50'}, 300); 
					});
				});
			});

			return false;
		}
	}
	return false;

}

$(document).ready(function(){
   $(".gcamounts li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#amounterror").slideUp(100);
		   if($(this).attr("id") == "selectamount") { 
				$("#enteramount").slideDown(200);
				$("#amount").focus();

		   } else { 
				$("#enteramount").hide();
		   }
		
		$("#amount").val($(this).attr("data-amount"));
 		if($(this).attr("id") == "selectamount") { 
				$("#giftcardamount").html(priceFormat("0").replace(".00",""));
		} else { 
			$("#giftcardamount").html(priceFormat($(this).attr("data-amount")).replace(".00",""));
		}
   });
	$("#amount").change(function() { 
		amount = $("#amount").val().replace(/[^\d.-]/g, '');
		$("#amount").val(amount);
		$("#giftcardamount").html(priceFormat(amount).replace(".00",""));
	});

});
$(function() {
	var dateToday = new Date();
	var endDate = new Date();
	endDate.setDate(endDate.getDate() + 180);

	$( ".datepicker" ).datepicker({ 
		currentText: "Now",
		dateFormat: 'M d, yy',
		minDate: dateToday,
		maxDate: endDate,
		altField: "#actualDate",
		altFormat: "yy-mm-dd"
	});
});

</script>
<link rel="stylesheet" href="<?php print $setup['temp_url_folder'];?>/sy-inc/css/smoothness/jquery-ui.min.css" type="text/css">

<?php 
$amounts = $glang['amounts'];
$cart['cart_gift_certificate_from_name'] = $person['p_name']." ".$person['p_last_name'];
$cart['cart_gift_certificate_from_email'] = $person['p_email'];
$cart['cart_delivery_date'] = date('Y-m-d');

if(!empty($_REQUEST['cart_id'])) { 
	$cart = doSQL("ms_cart", "*", "WHERE MD5(cart_id)='".$_REQUEST['cart_id']."' ");
}

?>

<div id="giftcertificatecontainer">
	<div class="pc center"><h3><?php print _gift_certificate_title_;?></h3><div>
		<div id="gcform">

			<div class="pc"><?php print _gift_certificate_text_;?></div>

		<div class="pc center">
		<?php 
		$card = $glang['gift_card_style'];
		$card = str_replace("[REDEEM_CODE]","TBD-123456789", $card);
		$card = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], $card);
		$card = str_replace("[AMOUNT]",showPrice($cart['cart_price']), $card);
		$card = str_replace("contenteditable","", $card);
		print $card; 
	?>
		</div>

			<form method="post" name="redeempc" id="redeempc" action="" onsubmit="purchasegiftcertificate(); return false;">
			<div class="pc center"><?php print _gift_certificate_select_amount_;?></div>
			<div class="pc center">
			<ul class="productoptionselect gcamounts">
			<?php $amount = explode(",",$amounts);
			foreach($amount AS $am) { 
				$am = trim($am);
				if(!empty($am)) {  
					$x++;
					if($cart['cart_id'] > 0) { 
						if(($cart['cart_price'] * 1) == $am) { $edit_selected = true; } 
					}
					?>
				<li id="gcamount-<?php print $x;?>" data-amount="<?php print $am;?>" <?php if(($cart['cart_price'] * 1) == $am) { ?>class="on"<?php } ?>><?php print showPrice(($am * 1));?></li>
				<?php 
				}
			}
			?>
			<?php if($glang['other_amount'] == "1") { ?>
			<li  id="selectamount"><?php print _gift_certificate_enter_amount_;?></li>
			<?php } ?>
			<div class="clear"></div>
			</ul>
			</div>

			<div class="pc center hide" id="amounterror"><div class="error"><?php print _gift_certificate_select_amount_error_;?></div></div>

			<div class="pc center <?php if(($cart['cart_id'] > 0) && ($edit_selected !== true) == true) { } else { ?>hide<?php } ?>" id="enteramount"><?php print $store['currency_sign'];?> <input type="text" id="amount" size="6" class="gcrequired gcfield center" value="<?php if($cart['cart_price'] > 0) { print ($cart['cart_price'] * 1); } ?>"></div>

			<div class="pc textleft">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_to_name_;?></div>
				<div><input type="text" id="to_name" size="20" class="gcrequired gcfield field100" value="<?php print htmlspecialchars($cart['cart_gift_certificate_to_name']);?>"></div>
			</div>

			<div class="pc">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_to_email_;?></div>
				<div><input type="text" id="to_email" size="20" class="gcrequired gcfield field100" value="<?php print htmlspecialchars($cart['cart_gift_certificate_to_email']);?>"></div>
			</div>


			<div class="pc">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_from_name_;?></div>
				<div><input type="text" id="from_name" size="20" class="gcrequired gcfield field100" value="<?php print htmlspecialchars($cart['cart_gift_certificate_from_name']);?>"></div>
			</div>

			<div class="pc">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_from_email_;?></div>
				<div><input type="text" id="from_email" size="20" class="gcrequired gcfield field100" value="<?php print htmlspecialchars($cart['cart_gift_certificate_from_email']);?>"></div>
			</div>

			<div class="pc">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_message_;?></div>
				<div><textarea id="message" cols="20" rows="4"class="field100 gcfield"><?php print htmlspecialchars($cart['cart_gift_certificate_message']);?></textarea></div>
			</div>
			<?php if($glang['show_send_date'] == "1") { ?>
			<div class="pc">
				<div class="textleft" style="margin: 4px 0px;"><?php print _gift_certificate_delivery_date_;?></div>
				<div class="textleft"><input readonly="readonly" type="text" id="delivery_date" size="12" class="gcrequired gcfield datepicker giftcarddatefield" style="background-image: url('<?php print $setup['temp_url_folder'];?>/sy-inc/icons/calendar-icon.png');    background-repeat: no-repeat; background-position: 4px center; padding-left: 32px;" value="<?php print _today_;?>">
				<input type="hidden" id="actualDate" class=" gcfield" value="<?php print htmlspecialchars($cart['cart_delivery_date']);?>"></div>
			</div>
			<?php } else { ?>
				<input type="hidden" id="actualDate" class=" gcfield" value="<?php print htmlspecialchars($cart['cart_delivery_date']);?>">
			<?php } ?>


		<div id="gcresponse" class="hide pc center "><div class="error"><?php print _empty_fields_;?></div></div>

			<input type="hidden" id="action" value="addcgtocart" class="gcfield">
			<input type="hidden" id="cart_id" value="<?php if($cart['cart_id'] > 0) { print MD5($cart['cart_id']); } ?>" class="gcfield">
			<div class="pc center"><input type="submit" id="submit" value="<?php if($cart['cart_id'] > 0) { print _update_; } else { print _add_to_cart_; } ?>" class="submit field100"></div>
			<div class="pc"><?php print _gift_certificate_bottom_text_;?></div>
			</form>
		</div>
		<div id="gcsuccess" class="hide">
		<div>&nbsp;</div>
			<div class="pc center"><h3><?php print _added_to_cart_;?></h3></div>
			<div class="pc" id="viewcartminilinks">
				<div class="center viewcartminilinks">
				<a href="/<?php print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><?php print _view_cart_;?></a>  	
				<a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php print _checkout_;?></a>
				<a href="" onclick="giftcertificate(0); return false;"><?php print _gift_certificate_add_another_;?></a>
				</div>
			</div>
		</div>


		</div>

		<div id="addprintcreditphotos" class="hide pc center">
		<br><br>
		</div>
		<div id="redeemcontinue" class="pc hide"><a href="" onclick="closewindowpopup(); return false;"><?php print _continue_;?></a></div>
		<div id="redeemreturnlink" class="pc hide"></div>
	<div>&nbsp;</div>
</div>
</div>