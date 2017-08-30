<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_photo_buy_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}



if($_REQUEST['promo_action'] == "checkpromo") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
		$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
	}

	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	$check_promo = doSQL("ms_promo_codes", "*", "WHERE code_code='".$_REQUEST['promo_code']."' AND code_use_status='0' AND (code_end_date>='$new_date' OR code_end_date='0000-00-00') ");
	if(empty($check_promo['code_id'])) {
		print  _promo_code_invalid_;
		exit();
	} elseif(!empty($check_promo['code_id'])) {
		$check_in_cart = doSQL("ms_cart", "*", "WHERE  ".checkCartSession()."  AND cart_coupon!='0' AND cart_order='0'  ");
		if(!empty($check_in_cart['cart_id'])) {
			print  _promo_code_exists_in_cart_;
			exit();

		} else {
			insertSQL("ms_cart", "cart_coupon='".addslashes(stripslashes($check_promo['code_id']))."', cart_coupon_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_ip='".getUserIP()."', cart_date=NOW() ");
			$_SESSION['promo_success'] = _promo_code_good_;
			session_write_close();
			print "good";

			exit();
		}
	}
}



?>
<script>
function checkpromo(classname) { 
	var fields = {};

	var rf = false;
	var mes;
	var stop;
	if($("#enter_email").val() == $("#enter_email").attr("default")) { 
		$("#enter_email").val("");
	}

	$(".promorequired").each(function(i){
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
			 $("#accresponse").html('<div class="pc"><div class="error">You have required fields empty</div></div>');
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
				fields[$this.attr('id')] = $this.val(); 
				//fields[$this.attr('name')] = $this.val(); 
			}
		});

			$.post(tempfolder+'/sy-inc/store/store_express.php', fields,	function (data) { 

			data = $.trim(data);
			// alert(data);
			if(data == "good") { 
				ppexpresscheckout();				
			} else { 
				alert(data);
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}

function showcouponform() { 
	$("#couponform").slideToggle();
}

	</script>
<?php
if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE ".checkCartSession()." AND cart_ship='1' AND cart_order<='0' ") > 0) { 
	$ship = true;
}
$total = shoppingCartTotal($mssess);
$pay_total = $total['show_cart_total'];
// print "<pre>"; print_r($total); print "</pre>"; 
?>
<?php 
if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE ".checkCartSession()."  AND cart_order<='0' ") <= 0) {  ?>
<div class="error center">Your shopping cart is empty</div>
<?php 
die();	
} ?>

	 <div class="pc"><div class="left"><h2>Checkout</h2></div> <div class="right textright"><h2>Total: <?php print showPrice($pay_total); ?></h2></div><div class="clear"></div></div>
<?php 

 $express = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalexpress' ");
 if(($express['pay_status'] == "1")&&($credit['tot']<=0)==true) { 
	 ?>

	 <div id="paypalexpress" class="center">
	 <div>
	 <form method="POST" name="expresscheckout" action="<?php tempFolder(); ?>/sy-inc/store/payment/express/process.php">
	<input type="hidden" name="start" value="1">
	<input type="hidden" name="itemprice" id="expressprice" value="<?php print $pay_total; ?>">
	<input type="hidden" name="vid" id="vid" value="<?php print $_SESSION['vid']; ?>">
	<input type="hidden" name="itemname" value="<?php print $site_setup['website_title']; ?> Purchase">
	<input type="hidden" name="itemQty" value="1">
	<?php if(($ship !== true)&&($express['pay_express_download_address'] !=="1")==true) { ?>
	<input type="hidden" name="noshipping" value="1">
	<?php } ?>
	 <input type="submit"   name="submit" border="0" class="submit" value="Checkout With PayPal Express" style="font-size: 21px;width: 100%; background: #0079C1; border: solid 1px #096EA4; padding: 16px 8px;">
	 </form>
	 </div>
	<div style="margin-top: 8px;">
	 <form method="get" name="skt" action="/index.php">
	 <input type="hidden" name="view" value="checkout">
	 <button type="submit"   name="submit" border="0" class="submit"  style="font-size: 21px; width: 100%; background: #242424; border: solid 1px #000000; padding: 16px 8px; ">Checkout With Credit / Debit Card</button>
	 </form>
	 </div>
	<div class="center"><img src="/sy-misc/creditcards/visamcdiscoveramex.jpg"></div>
	 <!-- <div class="pc center"><span class="the-icons icon-up-open" style="font-size: 27px;"></span></div> -->
	<!-- <div class="pc"><b>Securely & quickly checkout with your credit / debit card or PayPal by clicking the Checkout With PayPal button above</b>.</div> -->

	 <?php if($express['test_mode'] == "1") { ?>
	 <!-- <div class="error">TEST MODE ON. Nothing will be processed</div> -->
	 <?php } ?>
	</div>
 <?php } ?>
<div>&nbsp;</div>

<?php 
if(!empty($_SESSION['promo_success'])) { ?>
<div class="pc center success">Coupon redeemed with total discount of <?php print showPrice($total['promo_discount_amount']);?></div>
<?php
unset($_SESSION['promo_success']);
} 

	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	if(countIt("ms_promo_codes",  "WHERE code_use_status='0' AND(code_end_date>='$new_date' OR code_end_date='0000-00-00') ")>0) { 
		$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE ".checkCartSession()." AND cart_coupon!='0' AND cart_order='0'  ");
		if(empty($check_in_cart['cart_id'])) {
	?>
		<div id="promoRedeem" class="center">
			<!-- <div class="pc center"><a href=""  onclick="showcouponform(); return false;">I have a coupon code</a></div> -->
			<div id="couponform" class="">
				<div class="pageContent"> Redeem Coupon, enter code below.</div>
				<div class="pageContent">
				<form method="post" name="promocode" action="<?php tempFolder();?>/<?php print $site_setup['index_page']; ?>" style="padding: 0; margin: 0;" onSubmit="checkpromo('promo'); return false;">
				<input type="text" name="promo_code" size="15" id="promo_code" class="promo promorequired">
				<input type="hidden" name="promo_action" id="promo_action" class="promo" value="checkpromo">
				<input type="submit" name="submit" value="<?php print _promo_button_; ?>" class="submit">
				</form></div>
			<div>&nbsp;</div>
				</div>
	</div>
	<?php } 
	}
	?>
	<div class="clear"></div>
	<?php if(!empty($express['pay_description'])) { ?>
	 <!-- <div class="pc" style="text-align: left; "><?php print nl2br($express['pay_description']); ?></div> -->
	 <div class="pc center"><b>Free Shipping!</b></div>
	 <div class="pc center"><a href="/index.php?view=cart" style="font-size: 17px;">View My Shopping Cart</a></div>
	 <?php } ?>
<?php
		$st_date = date('Y-m-d');
		$st_time = date('H:i:s');
		$pv = insertSQL("ms_stats_site_pv", "pv_date='$st_date', pv_time='$st_time', pv_channel='".$_REQUEST['channel']."', pv_sub_channel='".$_REQUEST['sub_channel']."',  pv_page='$cururl', pv_page_title='".stripslashes($_REQUEST['ptitle'])."', pv_ref_id='".$_SESSION['vid']."', pv_bot='$bot' , page_viewed='store||checkoutexpresswindow||' ");

?>
