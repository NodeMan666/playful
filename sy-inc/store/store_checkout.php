<?php
$require_account = 1;
if(countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_ship='1' AND cart_order<='0' ") > 0) { 
	$ship = true;
}

/* Checking for out of stock items */
$carts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_product>='0' AND prod_inventory_control='1'  AND cart_order<='0' " );
while($cart= mysqli_fetch_array($carts)) {
	if(!empty($cart['cart_sub_id'])) {
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
		if($sub['sub_qty']<=0) { 
			$total_out_of_stock++;
			$out_of_stock .= htmlspecialchars($cart['date_title'])."||";
			deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		}
	} else { 
		if($cart['prod_qty']<=0) { 
			$total_out_of_stock++;
			$out_of_stock .= htmlspecialchars($cart['date_title'])."||";
			deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		}
	}
	if($total_out_of_stock > 0) { 
		$_SESSION['outofstockmessage'] = $out_of_stock;
		$_SESSION['totaloutofstock'] = $total_out_of_stock;
		header("location: index.php?view=cart");
		session_write_close();
		exit();
	}
}


$shipping_group_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_pic_date_id>='0'  AND cart_order<='0' AND shipping_group>'1'  ");
if($shipping_group_check['cart_id'] <= 0) { 
	// check for store items
	$shipping_group_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_store_product>='0'  AND cart_order<='0' AND shipping_group>'1'  ");
}

if($shipping_group_check['cart_id'] > 0) { 
	$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$shipping_group_check['shipping_group']."' ");
} else { 
	$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_default='1' ");
}

if(countIt("ms_shipping_methods",  "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ") <=0) { 
	$ship = false;
}
if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE cart_order<='0' AND ".checkCartSession()." AND prod_type='package' ") > 0) { 
	$store['checkout_account'] = "require";
}
if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE cart_order<='0' AND ".checkCartSession()." AND cart_paid_access='1' ") > 0) { 
	$store['checkout_account'] = "require";
}

$no_trim = true;
$total = shoppingCartTotal($mssess);
$pay_total = $total['total'];
$taxable_total = $total['tax_total'];
$opt = doSQL("ms_payment_options", "*", "WHERE pay_id='2' ");
if(isset($_SESSION['pid'])) { 
	updateSQL("ms_cart", "cart_session='".$_SESSION['ms_session']."' WHERE cart_client='".$_SESSION['pid']."' AND cart_order='0' ");
}

$check_min = doSQL("ms_cart", "*", "WHERE  ".checkCartSession()."  AND cart_order='0' AND cart_min_order>'0' ORDER BY cart_min_order DESC ");
if($check_min['cart_min_order'] > 0) { 
	$store['min_order_amount'] = $check_min['cart_min_order'];
}
$check_reg = doSQL("ms_cart", "*", "WHERE  ".checkCartSession()."  AND cart_account_credit_for>'0' AND cart_order<='0' ");

$expired = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_pic_date_id>='0' AND date_expire!='0000-00-00' AND date_expire<'".date('Y-m-d')."' AND cart_order<='0' ");

if($total['total_items']<=0) { 
	$empty_cart = true;
		print "<div>&nbsp;</div>";

	print "<div class=\"error\">"._store_shopping_cart_empty_."</div></center>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
} else if((($total['show_cart_total'] + $total['promo_discount_amount']) < $store['min_order_amount'])&&(empty($check_reg['cart_id']))==true) { 
	print "<div>&nbsp;</div>";

	print "<center><span  class=pageContent>"._does_not_meet_min_order_." ".showPrice($store['min_order_amount']).".</span></center>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
		
} elseif(!empty($expired['date_id'])) { 

	print "<div>&nbsp;</div>";

	print "<center><div  class=\"error\">"._expired_pages_in_cart_."</div></center>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";

}else { 
	// IF CART NOT EMPTY

if($_POST['submitit'] == "yes") {
//	print "<pre>"; print_r($_POST); print "</pre>";
	//check for empty fields


	if(empty($_REQUEST['first_name'])) {
		$error['first_name'] = ""._first_name_." "._is_blank_."";
		$fclass['first_name'] = "TFtextfielderror";
	}

	if(empty($_REQUEST['last_name'])) {
		$error['last_name']  = ""._last_name_." "._is_blank_."";
		$fclass['last_name'] = "TFtextfielderror";
	}


	// Check email address
	if(empty($_REQUEST['email_address'])) {
		$error['email_address']  = ""._email_address_." "._is_blank_."";
		$fclass['email_address'] = "TFtextfielderror";
	}
	if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['address']))==true) {
			$error['address'] = ""._address_." "._is_blank_."";
			$fclass['address'] = "TFtextfielderror";
	}

	if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['city']))==true) {
			$error['city'] = ""._city_." "._is_blank_."";
			$fclass['city'] = "TFtextfielderror";
	}
	if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['state']))==true) {
			$error['state'] = ""._state_." "._is_blank_."";
			$fclass['state'] = "TFtextfielderror";
	}
	if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['zip']))==true) {
			$error['zip'] = ""._zip_." "._is_blank_."";
			$fclass['zip'] = "TFtextfielderror";
	}


	if(!empty($error)) {
		print "<div class=\"errorMessage\">";
		foreach($error AS $er) {
			print "<div>$er</div>";
		}
		print "</div>";
	} else {
		include $setup['path']."/sy-inc/store/payment/payment.process.php";
	}
}

if($_POST['submitit'] !== "yes") {

if(customerLoggedIn()) { 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id) = '".$_SESSION['pid']."' ");
}


?>

 <script>
tax_shipping = <?php print $store['tax_shipping'];?>;

$(document).ready(function(){
$(".newacc").change( function() {
    // Completely strips tags.  Taken from Prototype library.
    var strClean = $(this).val().replace(/<\/?[^>]+>/gi, '');

    // Remove any double and single quotation marks.
    //strClean = strClean.replace(/"/gi, '').replace(/'/gi, '');

    // put the data back in.
//	alert($(this).attr("id"));
    $(this).val(strClean);
});

	$('.newacc').change(function() {
		updateCheckoutAddress();
	});
	$("#ship_billing").attr("checked", "checked")
	$(".shiprequired").attr("readonly", "readonly");
	$(".shiprequired").addClass("disabledinput");

	$('.newacc').keypress(function(e){
		if ( e.which == 13 ) return false;
		//or...
		if ( e.which == 13 ) e.preventDefault();
	});
	$('.cardenter').keypress(function(e){
		if ( e.which == 13 ) return false;
		//or...
		if ( e.which == 13 ) e.preventDefault();
	});
	
});


</script>
<?php
if(!empty($_SESSION['promo_error'])) {
	print "<div class=error>".$_SESSION['promo_error']."</div><div>&nbsp;</div>";
	unset($_SESSION['promo_error']);
}
?>

<?php
if(!empty($_SESSION['promo_success'])) {
	print "<div class=\"success\">"._promo_code_good_."</div><div>&nbsp;</div>";
	unset($_SESSION['promo_success']);
}
?>

<div id="checkoutside"  class="nofloatsmallleft">
	<div class="inner">
	<?php if($store['coupon_checkout_page'] == "1") { ?>
	<?php 
	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	if(countIt("ms_promo_codes",  "WHERE code_use_status='0' AND(code_end_date>='$new_date' OR code_end_date='0000-00-00') ")>0) { 
		$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE ".checkCartSession()." AND cart_coupon!='0' AND cart_order='0'  ");
	?>
	<div class="pc" style="margin-bottom: 12px;"><a onclick="redeemcoupon('checkout',''); return false;" class="checkout" style="width: 100%; box-sizing:border-box; display: block; text-align: center;"><?php print _redeem_coupon_;?></a></div>
	<?php } 
	?>
	<?php } ?>
	<?php if((countIt("ms_gift_certificates", "WHERE used_order<='0' ") > 0) && (empty($_REQUEST['gc'])) == true) { ?>
	<div class="pc" style="margin-bottom: 12px;"><a onclick="giftcertificateredeem('checkout',''); return false;" class="checkout" style="width: 100%; box-sizing:border-box; display: block; text-align: center;"><?php print _gift_certificate_redeem_;?></a></div>

	<?php } ?>

	<?php if($store['login_checkout_page'] == "1") { ?>
		<?php if((!customerLoggedIn()) && ($store['checkout_account']!=="disabled")==true){ ?>
		<div class="center">
		<input type="hidden" id="sub" value="">
		<div class="pc checkoutloginlink center"  style="margin-bottom: 12px;"><a href="" onclick="showgallerylogin('checkout','','','login'); return false;"><span class="the-icons icon-user"></span><?php print _log_into_existing_account_; ?></a></div>
		</div>
		<?php } ?>
	<?php } ?>



	<div id="checkouttotals">
	<div class="pc hidesmall"><h3><?php print _my_cart_;?></h3></div>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td class="pc large2"><?php print _items_;?></td><td class="pc textright large2"><?php print $total['total_items'];?></td>
		</tr>
		<tr>
			<td class="pc large2"><?php print _subtotal_;?></td><td class="pc textright large2" id="subtotal" price="<?php print $pay_total;?>"><?php print showPrice($pay_total);?>
			</td>
		</tr>


		<?php 

			if($total['eb_amount'] > 0) { 
				
			if($store['tax_discount'] == "after") { 
				if($taxable_total > 0) { 
					$taxable_total	= $taxable_total - $total['eb_amount'];
				}
			}
			if($taxable_total < 0 ) { $taxable_total = 0; } 
				
				?>
			<tr id="ebrow">
				<td class="pc large2"><?php print _early_bird_special_;?></td><td class="pc textright large2">(<?php print showPrice($total['eb_amount']);?>)</td>
			</tr>
			<?php } ?>

		<?php 
		$discount_total = $total['discount_total'];
		$promo = getCoupon($mssess,$discount_total);
		if(!empty($promo['promo_id'])) { 
			//print "<pre>"; print_r($promo); 
			 // print "<pre>"; print_r($total); 
			//print "$taxable_total";
			//print "</pre>";
			// print "<li>".$total['tax_total'] * ($total['promo_percentage'] / 100);
		?>

		<tr id="couponrow">
			<td class="pc large2"><?php print _promo_code_;?> <?php print $promo['promo_name'];?></td><td class="pc textright large2">
			<?php 
			$discount = $promo['promo_discount_amount'];

			if($store['tax_discount'] == "after") { 
				if($taxable_total > 0) { 
					if(($total['discount_total'] !== $total['tax_total'])  && ($total['promo_percentage'] > 0) && (($total['sub_total'] + $discount) !== $total['tax_total']) == true) { 
						$taxable_total = $total['tax_total'] - ($total['tax_total'] * ($total['promo_percentage'] / 100));			
						// $taxable_total	= $taxable_total - $discount;
					} else { 
						$taxable_total	= $taxable_total - $discount;
					}
				}
			}
			if($taxable_total < 0 ) { $taxable_total = 0; } 

			if($pay_total < $discount) { 
				$discount = $pay_total;
			}
			$coupon_id = $promo['promo_id'];
			$coupon_name = $promo['promo_name']." - ".$promo['promo_code'];
			print "(".showPrice($discount).")";
			?>
			</td>
		</tr>
		<?php } ?>

		<?php if($ship == true) { ?>
		<?php
			if($store['shipping_discount'] == "after") { 
				$ship_on = $total['ship_cal_total'] - $discount;
			} else { 
				$ship_on = $total['ship_cal_total'];
			}
		 if(!empty($p['p_state'])) { 
			$state = doSQL("ms_states", "*", "WHERE state_abr='".addslashes($p['p_state'])."' ");
			$country = doSQL("ms_countries", "*", "WHERE country_name='".$p['p_country']."' ");
			$shipmethod = doSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
			$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$shipmethod['method_id']."' AND price_from<='".$ship_on."' AND price_to>='".$ship_on."' ");
			$price['price_amount'] = $total['add_ship'] + $price['price_amount'];
			$price = $price['price_amount'] + (($price['price_amount'] * $state['state_add_ship_percent']) / 100) + (($price['price_amount'] * $country['add_price']) / 100) ;
			}
			if($store['tax_shipping'] == "1") {
				$add_ship_to_tax =  $price;
			}
			if($shipmethod['method_pickup'] == "1") { 
				$price = 0;
			}

		?>
		<tr <?php if((empty($p['p_state'])) || ($price <= 0 ) == true) { ?> style="display: none;" <?php } ?>  id="shippingrow">
			<td class="pc large2"><?php print _shipping_;?></td><td class="pc textright large2" id="shippingtotal" price="<?php print $price;?>"><?php print showPrice($price);?>
			</td>
		</tr>
		<?php } ?>

		<?php 
		if(!empty($p['p_state'])) { 
			$zip = doSQL("ms_tax_zips", "*", "WHERE zip='".$p['p_zip']."' ");
			if($zip['tax'] > 0) { 
				$tax_percentage = $zip['tax'];
			} else { 
				$per = doSQL("ms_states", "*", "WHERE state_abr='".addslashes($p['p_state'])."' ");
				$tax_percentage = $per['state_tax'];
			}
			$tax = ($taxable_total + $add_ship_to_tax) * $tax_percentage / 100;
			// $taxable_total = $taxable_total + $add_ship_to_tax;
		}
		if($shipmethod['method_pickup'] == "1") { 
			if($store['pickup_tax_rate'] > 0) { 
				$tax_percentage = $store['pickup_tax_rate'];
				$tax = ($taxable_total + $add_ship_to_tax) * $tax_percentage / 100;
			}
		}

		if(isset($_SESSION['tax_percentage'])) { 
			$tax = ($taxable_total + $add_ship_to_tax) * $_SESSION['tax_percentage'] / 100;
			$tax_percentage = $_SESSION['tax_percentage'];
			$per['state_tax'] = $_SESSION['tax_percentage'];
		}

		if($_REQUEST['action'] == "settaxrate") { 
			$_SESSION['tax_percentage'] = $_REQUEST['admin_tax'];
			header("location: index.php?view=checkout");
			session_write_close();
			exit();
		}
		?>
			<tr <?php if(((empty($p['p_state']))||($tax <=0)==true) && (!isset($_SESSION['tax_percentage'])) == true) { ?> style="display: none;" <?php } ?> id="taxrow" valign="top">
		<td class="pc"><span class=" large2"><?php print _tax_;?></span>
			<?php if($taxable_total > 0) { ?>
			<span id="taxon">
			<?php if($pay_total !== $taxable_total) { ?><br><span id="taxonpercent"><?php if($per['state_tax'] > 0) { print $per['state_tax'];?></span>% <?php print _on_;?> <?php print showPrice($taxable_total);?><?php } }?>
			</span>
			<?php } ?></td><td class="pc textright large2" id="taxtotal" price="<?php print $tax;?>">
			<?php if(!empty($p['p_state'])) { print showPrice($tax);	} else { print "TBD"; }  ?>
			</td>
		</tr>
		
		<?php if((!empty($p['p_country']))||($site_setup['include_vat'] == "1")==true) { 
			if(!empty($p['p_country'])) { 
				$per = doSQL("ms_countries", "*", "WHERE country_name='".$p['p_country']."' ");
				$vat_percentage = $per['vat'];
			} else { 
				$def = doSQL("ms_countries", "*", "WHERE def='1' ");
				$vat_percentage = $def['vat'];
			}
			$vat = ($taxable_total + $add_ship_to_tax) * $vat_percentage / 100;
		}?>
		
		<tr <?php if($vat <= 0) { ?> style="display: none;" <?php } ?> id="vatrow" valign="top">
			<td class="pc"><span class=" large2"><?php print _vat_;?></span>
			<?php if($taxable_total > 0) { ?>
			<span id="taxon">
			<?php if($pay_total !== $taxable_total) { ?><br><span id="vatonpercent"><?php print $vat_percentage;?></span>% <?php print _on_;?> <?php print showPrice($taxable_total);?><?php } ?>
			</span>
			<?php } ?></td><td class="pc textright large2" id="vattotal">
			<?php if(!empty($vat)) { print showPrice($vat);	} else { print "TBD"; }  ?>
			</td>
		</tr>

		<?php if(($p['p_id'] > 0) && (countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_account_credit>'0' AND cart_order<='0' ")<=0)==true) { 

			if($setup['no_expired_print_credits'] == true) { 
				$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."' ");
			} else { 
				$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."' AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' ) ");
			}


			if($credit['tot'] > 0) { ?>
		<tr <?php if($credit['tot'] <= 0) { ?> style="display: none;" <?php } ?> id="creditrow" valign="top">
			<td class="pc"><span class=" large2"><?php print _account_credit_;?></span>
			</td><td class="pc textright large2" id="credittotal">
			(<?php print showPrice($credit['tot']); ?>)
			</td>
		</tr>
		<?php } ?>
		<?php if($credit['tot'] < 0) { $credit['tot'] = 0; } ?>
		<?php }?> 

		<?php if(!empty($_REQUEST['gc'])) { 
			$gc = doSQL("ms_gift_certificates", "*", "WHERE redeem_code='".trim($_REQUEST['gc'])."' AND used_order<='0' ");
			$store['checkout_account'] = "require";

			if($gc['id'] > 0) { ?>
				
			<tr  id="gcrow" valign="top">
				<td class="pc"><span class=" large2 bold"><?php print _gift_certificate_name_;?></span>
				<br><?php print $gc['redeem_code']; ?>
				</td><td class="pc textright large2 bold" id="credittotal">
				(<?php print showPrice($gc['amount']); ?>)
				</td>
			</tr>



		<?php 	}
		}
		?>
		<tr id="grandtotalrow" style="display: none; font-weight: bold;" >
			<td class="pc large1"><?php print _total_;?></td><td class="pc textright large1"><span  id="grandtotal"></span><div class="" id="grandtotalloading" style="display: none; position: absolute;"></span>
			</td>
		</tr>

	</table>

	<?php if(($setup['set_tax_rate_at_checkout'] == true) && (isset($_SESSION['office_admin_login'])) == true) { ?>
		<div>&nbsp;</div>
		<div class="pc">
		<form method="post" name="settax" action="index.php"><input type="text" name="admin_tax" id="admin_tax" value="<?php print $_SESSION['tax_percentage'];?>" size="4">
		<input type="hidden" name="action" value="settaxrate">
		<input type="hidden" name="view" value="checkout">
		<input type="submit" name="submit" class="submit" value="Set Tax Rate"></form>
		</div>
		<?php } ?>
	</div>
	<div>&nbsp;</div>


		<div id="checkoutinfo" style="display: none;" class="hidesmall">
		<div class="pc"><h3><?php print _billing_address_;?></h3></div>
			<div id="ck_name" class="pc">
				<span id="ck_first_name"><?php print htmlspecialchars(stripslashes($p['p_name']));?></span> 
				<span id="ck_last_name"><?php print htmlspecialchars(stripslashes($p['p_last_name']));?></span>
			</div>
			<div id="ck_email" class="pc"><?php print htmlspecialchars($p['p_email']);?></div>
			<div id="ck_address" class="pc"><?php print htmlspecialchars($p['p_address1']);?></div>
			<div id="ck_address_3" class="pc">
				<span id="ck_city"><?php print htmlspecialchars($p['p_city']);?></span> <span id="ck_state"><?php print htmlspecialchars($p['p_state']);?></span>, <span id="ck_zip"><?php print htmlspecialchars($p['p_zip']);?></span>
			</div>
		<div>&nbsp;</div>
		<?php if($ship == true) { ?>
		<div class="pc"><h3><?php print _shipping_address_;?></h3></div>
			<div id="ship_ck_name" class="pc">
				<span id="ship_ck_first_name"><?php print htmlspecialchars(stripslashes($p['p_name']));?></span> 
				<span id="ship_ck_last_name"><?php print htmlspecialchars(stripslashes($p['p_last_name']));?></span>
			</div>
			<div id="ship_ck_address" class="pc"><?php print htmlspecialchars($p['p_address1']);?></div>
			<div id="ship_ck_address_3" class="pc">
				<span id="ship_ck_city"><?php print htmlspecialchars($p['p_city']);?></span> <span id="ship_ck_state"><?php print htmlspecialchars($p['p_state']);?></span>, <span id="ship_ck_zip"><?php print htmlspecialchars($p['p_zip']);?></span>
			</div>
		<?php } ?>
		</div>

	</div>
	<div>&nbsp;</div>

</div>
<?php $paykey = doSQL("ms_payment_options", "*", "WHERE pay_option='eway' ");?>

<?php // Square URL  $setup['temp_url_folder']."/sy-inc/store/payment/square/payment.php";   ?>

<?php 
	
$and_where = "";
if(($order['order_offline'] > 0)||($order['order_invoice'] == "1")==true) { 
	$and_where = "AND pay_option!='payoffline' AND pay_option!='payoffline2' ";
}

$payopt = doSQL("ms_payment_options", "*", "WHERE pay_status='1' AND pay_option!='paypalexpress'  $and_where ORDER BY pay_order ASC "); 
if($payopt['pay_option'] == "square") { 
	$action = $setup['temp_url_folder']."/sy-inc/store/payment/square/payment.php";
} else { 
	$action = $site_setup['index_page'];
}
?>

<div id="checkoutform" class="nofloatsmallleft">
	<form method=POST name="checkout" data-square-action="<?php print $setup['temp_url_folder']."/sy-inc/store/payment/square/payment.php";?>" data-action="<?php print $site_setup['index_page'];?>" id="checkout" action="<?php print $action;?>" loggedin="<?php if(customerLoggedIn()) { print "1"; } else { print "0"; } ?>" ckpass="1" ship="<?php if($ship == true) { print "1"; } else { print "0"; } ?>" taxwhere="<?php print $store['tax_address'];?>" shipdisc="<?php print $store['shipping_discount'];?>" taxdisc="<?php print $store['tax_discount'];?>" account="<?php print $store['checkout_account'];?>" <?php if(!empty($paykey['pay_private_key'])) { ?>data-eway-encrypt-key="<?php print $paykey['pay_private_key'];?>"<?php } ?>>
	<input type="hidden" name="shipping_price" id="shipping_price" value="<?php print $price;?>">
	<input type="hidden" name="tax_price" id="tax_price" value="<?php print $tax;?>">
	<input type="hidden" name="credit_amount" id="credit_amount" value="<?php print $credit['tot'];?>">
	<input type="hidden" name="gift_certificate_amount" id="gift_certificate_amount" value="<?php print $gc['amount'];?>">
	<input type="hidden" name="gift_certificate_id" id="gift_certificate_id" value="<?php print $gc['redeem_code'];?>">


	<input type="hidden" name="vat_price" id="vat_price" value="<?php print $vat;?>">
	<input type="hidden" name="vat_percentage" id="vat_percentage" value="<?php print $vat_percentage;?>">
	<input type="hidden" name="grand_total" id="grand_total" value="">
	<input type="hidden" name="sub_total" id="sub_total" value="<?php print $pay_total;?>">
	<input type="hidden" name="discount_amount" id="discount_amount" value="<?php print $discount;?>">
	<input type="hidden" name="eb_amount" id="eb_amount" value="<?php print $total['eb_amount'];?>">
	<input type="hidden" name="coupon_id" id="coupon_id" value="<?php print $coupon_id;?>">
	<input type="hidden" name="coupon_name" id="coupon_name" value="<?php print $coupon_name;?>">
	<input type="hidden" name="ms_session" id="ms_session" class="newacc" value="<?php print $_SESSION['ms_session'];?>">
	<input type="hidden" name="taxable_amount" id="taxable_amount" data-org-tax-amount="<?php print $taxable_total;?>" value="<?php print $taxable_total;?>">
	<input type="hidden" name="tax_percentage" id="tax_percentage" value="<?php print $tax_percentage;?>">
	<input type="hidden" name="ship_on" id="ship_on" value="<?php print $ship_on;?>">

	<div>

	<div style="width: <?php if($ship == true) { print "33%"; } else { print "50%;"; } ?>; float: left;" >
		<div class="checkoutprogressdone" id="progressinfo">
		1) <?php print _checkout_progress_your_info_;?>
		</div>
	</div>
	<?php if($ship == true) {?>
	<div style="width: 33%; float: left;">
		<div  class="checkoutprogress" id="progressshipping">
		2) <?php print _checkout_progress_shipping_;?>
		</div>
	</div>
	<?php } ?>
	<div style="width: <?php if($ship == true) { print "33%"; } else { print "50%;"; } ?>; float: left;" >
		<div  class="checkoutprogress" id="progresspayment">
		<?php if($ship == true) {?>3<?php } else { ?>2<?php } ?>) <?php print _checkout_progress_payment_;?>
		</div>
	</div>
<div class="clear"></div>

<div id="maininfo">

<?php if((!empty($_SESSION['decline_message']))||($_REQUEST['status'] == "declined")==true) { ?>
<div>&nbsp;</div>
<div id="paymentdeclined" class="error"><?php if(!empty($_SESSION['decline_message'])) { print $_SESSION['decline_message']; } else { print _checkout_card_declined_; } ;?></div>
<?php 
	unset($_SESSION['decline_message']);
} ?>

<div class="pc"><h1><?php print _checkout_;?> > <?php print _checkout_progress_your_info_;?></h1></div>
<?php
$ef = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id LEFT JOIN ms_photo_products_lists ON ms_calendar.date_photo_price_list=ms_photo_products_lists.list_id", "*", "WHERE ".checkCartSession()." AND cart_pic_date_id>='0'  AND cart_order<='0' AND list_extra_1!='' ");
if(empty($ef['list_extra_1'])) { 
	$ef = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id LEFT JOIN ms_sub_galleries ON ms_cart.cart_sub_gal_id LEFT JOIN ms_photo_products_lists ON ms_sub_galleries.sub_price_list=ms_photo_products_lists.list_id", "*", "WHERE ".checkCartSession()." AND cart_pic_date_id>='0'  AND cart_sub_gal_id>'0' AND cart_order<='0' AND list_extra_1!='' ");
}
if(!empty($ef['list_extra_1'])) { ?>
<div style="width: 49%; float: left;" class="nofloatsmallleft">
	<div class="pc"><?php print $ef['list_extra_1'];?></div>
	<div class="pc">
	<input type="hidden" name="order_extra_field_1" id="order_extra_field_1" value="<?php print htmlspecialchars($ef['list_extra_1']);?>">
	<input type="text" name="order_extra_val_1" id="order_extra_val_1" size="20" value="" class="newacc field100 <?php if($ef['list_extra_1_req'] == "1") { print "required"; } ?>"></div>
</div>
	<?php if(!empty($ef['list_extra_2'])) { ?>
	<div style="width: 49%; float: right;" class="nofloatsmallleft">
		<div class="pc"><?php print $ef['list_extra_2'];?></div>
		<div class="pc">
		<input type="hidden" name="order_extra_field_2" id="order_extra_field_2" value="<?php print htmlspecialchars($ef['list_extra_2']);?>">
		<input type="text" name="order_extra_val_2" id="order_extra_val_2" size="20" value="" class="newacc field100 <?php if($ef['list_extra_2_req'] == "1") { print "required"; } ?>"></div>
	</div>
	<div class="clear"></div>
	<?php } ?>
	<?php if(!empty($ef['list_extra_3'])) { ?>
	<div style="width: 49%; float: left;" class="nofloatsmallleft">
		<div class="pc"><?php print $ef['list_extra_3'];?></div>
		<div class="pc">
		<input type="hidden" name="order_extra_field_3" id="order_extra_field_3" value="<?php print htmlspecialchars($ef['list_extra_3']);?>">
		<input type="text" name="order_extra_val_3" id="order_extra_val_3" size="20" value="" class="newacc field100 <?php if($ef['list_extra_3_req'] == "1") { print "required"; } ?>"></div>
	</div>
	<?php } ?>

	<?php if(!empty($ef['list_extra_4'])) { ?>
	<div style="width: 49%; float: right;" class="nofloatsmallleft">
		<div class="pc"><?php print $ef['list_extra_4'];?></div>
		<div class="pc">
		<input type="hidden" name="order_extra_field_4" id="order_extra_field_4" value="<?php print htmlspecialchars($ef['list_extra_4']);?>">
		<input type="text" name="order_extra_val_4" id="order_extra_val_4" size="20" value="" class="newacc field100 <?php if($ef['list_extra_4_req'] == "1") { print "required"; } ?>"></div>
	</div>
	<div class="clear"></div>

	<?php } ?>

	<?php if(!empty($ef['list_extra_5'])) { ?>
		<div style="width: 49%; float: left;" class="nofloatsmallleft">
		<div class="pc"><?php print $ef['list_extra_5'];?></div>
		<div class="pc">
		<input type="hidden" name="order_extra_field_5" id="order_extra_field_5" value="<?php print htmlspecialchars($ef['list_extra_5']);?>">
		<input type="text" name="order_extra_val_5" id="order_extra_val_5" size="20" value="" class="newacc field100 <?php if($ef['list_extra_5_req'] == "1") { print "required"; } ?>"></div>
	</div>
	<div class="clear"></div>
	<?php } ?>
	<div class="clear"></div>
	<div>&nbsp;</div>

<?php } ?>

<div <?php if(($ship ==true)&&($store['ship_only_billing'] !== "1") == true){ ?>  id="checkoutaddresswithship" <?php } else { ?> id="checkoutaddressnoship" <?php } ?>  class="nofloatsmallleft">

<div class="pc"><h3><?php print _billing_address_;?></h3></div>
<?php if(($store['ship_only_billing'] == "1")&&($ship == true) == true) { ?>
<?php if(!empty($store['ship_only_billing_message'])) { ?><div class="pc"><?php print nl2br($store['ship_only_billing_message']);?></div><?php } ?>
<?php } ?>
<div class="pc">&nbsp;</div>
<?php $acc = doSQL("ms_new_accounts", "*", ""); ?>

	<div <?php if(($acc['co_company_ask'] == "0")&&($acc['co_company_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div  class="nofloatsmallleft">
			<div class="pc"><?php print _company_;?></div>
			<div class="pc"><input type="text" name="business_name" id="business_name" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_company']));?>" class="newacc field100 <?php if($acc['co_company_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>


	<div style="width: 49%; float: left;" class="nofloatsmallleft">
		<div <?php if(($acc['co_first_name_ask'] == "0")&&($acc['co_first_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text" name="first_name" id="first_name" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_name']));?>" class="newacc field100 <?php if($acc['co_first_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<div style="width: 49%; float: right;"  class="nofloatsmallleft">
		<div <?php if(($acc['co_last_name_ask'] == "0")&&($acc['co_last_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" name="last_name" id="last_name" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_last_name']));?>" class="newacc field100 <?php if($acc['co_last_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>

	<div   class="nofloatsmallleft">
		<div <?php if(($acc['co_address_ask'] == "0")&&($acc['co_address_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _address_;?></div>
			<div class="pc"><input type="text" name="address" id="address" size="40"    value="<?php print htmlspecialchars($p['p_address1']);?>" class="newacc field100 <?php if($acc['co_address_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div <?php if(($acc['co_city_ask'] == "0")&&($acc['co_city_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div   class="nofloatsmallleft">
			<div class="pc"><?php print _city_;?></div>
			<div class="pc"><input type="text" name="city" id="city"  size="30"    value="<?php print htmlspecialchars($p['p_city']);?>" class="newacc field100 <?php if($acc['co_city_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<?php 
	if(!empty($p['p_country'])) { 
		$ct = doSQL("ms_countries", "*", " WHERE country_name='".addslashes(stripslashes($p['p_country']))."' ");
	} else { 
		$ct = doSQL("ms_countries", "*", " WHERE ship_to='1' AND def='1' ");
	}
	?>

	<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE state_country='".addslashes(stripslashes($ct['country_name']))."'  ORDER BY state_name ASC");
	if(mysqli_num_rows($states) > 0) { ?>
	<div  style="float: left;" <?php if(($acc['co_state_ask'] == "0")&&($acc['co_state_req'] == "0")==true) { print "class=\"hide nofloatsmallleft\"";  } else { print "class=\"nofloatsmallleft\""; }  ?>>
		<div  class="nofloatsmallleft">
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="state" id="state" class="newacc <?php if($acc['co_state_req'] == "1") { print "required"; } ?>" onChange="getTax();">
			<option value=""><?php print _select_state_;?></option>
			<?php 
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($p['p_state'] == $state['state_abr']) { print "selected"; } ?> class="ct-<?php print str_replace(" ","_",$state['country_name']);?> allstates" ><?php print $state['state_name'].""; ?></option>
			<?php } ?>
			<option value="N/A">N/A</option>
			</select>
			</div>
		</div>
	</div>
	<?php } ?>
		<div   style="float: left;" <?php if(($acc['co_zip_ask'] == "0")&&($acc['co_zip_req'] == "0")==true) { print "class=\"hide nofloatsmallleft\""; } else { print "class=\"nofloatsmallleft\""; } ?>>
			<div class="pc nofloatsmallleft"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="zip" id="zip" size="8" value="<?php print htmlspecialchars($p['p_zip']);?>" class="newacc <?php if($acc['co_zip_req'] == "1") { print "required"; } ?>"  onChange="getTax();"></div>
		</div>


		<div class="cssClear"></div>

	<div>


		
	<div <?php if(($acc['co_country_ask'] == "0")&&($acc['co_country_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div   class="nofloatsmallleft">
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select name="country"  id="country"  class="newacc <?php if($acc['co_country_req'] == "1") { print "required"; } ?>"  onChange="getstates(this.value,'1');">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($p['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
			</div>
		</div>
	</div>


		<div <?php if(($acc['co_phone_ask'] == "0")&&($acc['co_phone_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div   class="nofloatsmallleft">
				<div class="pc"><?php print _phone_;?></div>
				<div class="pc"><input type="text" name="order_phone" id="order_phone" size="20" value="<?php print htmlspecialchars($p['p_phone']);?>" class="newacc <?php if($acc['co_phone_req'] == "1") { print "required"; } ?>"></div>
			</div>
		</div>

		<div>&nbsp;</div>
		<?php if((!customerLoggedIn()) || (empty($p['p_email'])) == true) { ?>
	<div id="emails">
	<div class="pc"><h3><?php print _contact_;?></h3></div>

	<div style="width: 49%; float: left;"  class="nofloatsmallleft" >
		<div>
			<div class="pc"><?php print _email_address_;?></div>
			<div class="pc"><input type="text" name="email_address" id="email_address" size="40"  autocomplete="off" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>


	<div style="width: 49%; float: right;"  class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _retype_email_address_;?></div>
			<div class="pc"><input type="text" name="email_address_2" id="email_address_2"  autocomplete="off" size="40" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>

<div class="cssClear"></div>
	</div>
		<?php } else { ?>
	<input type="hidden" name="email_address" id="email_address" value="<?php print htmlspecialchars($p['p_email']);?>" data-stripe="email">

	<?php } ?>		

<?php if($em_settings['join_at_checkout'] == "1") { ?>
		<div>&nbsp;</div>
<div class="pc"><input type="checkbox" name="join_ml" id="join_ml" value="1" <?php if($em_settings['join_at_checkout_default'] == "1") { print "checked"; } ?>> <label for="join_ml"><?php print $em_settings['join_at_checkout_text'];?></label>
<?php if(!empty($em_settings['join_at_checkout_desc'])) { ?><br><?php print $em_settings['join_at_checkout_desc'];?><?php } ?></div>
<?php } ?>
		<div>&nbsp;</div>

		<?php if($store['checkout_account'] !== "disabled") { ?>
		<?php if(!customerLoggedIn()) { ?>

		<div id="passes">
		<div class="pc"><h3><?php print _create_an_account_;?></h3></div>
		<div class="pc"><?php print _create_an_account_message_;?></div>
		<?php if($store['checkout_account'] == "optional") { ?>
			<div class="pc"><input type="checkbox" name="no_account" id="no_account" class="newacc checkbox" onClick="noAccount();"> <label for="no_account"><?php print _create_account_no_account_checkbox_;?></label></div>
		<?php } ?>
		<div id="accountpasswords">
		<div style="width: 49%; float: left;" class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _password_;?></div>
				<div class="pc"><input type="password" name="newpassword" id="newpassword" autocomplete="off" size="40" value="<?php print $_REQUEST['newpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>


		<div style="width: 49%; float: right;" class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _re_type_password_;?></div>
				<div class="pc"><input type="password" name="renewpassword" id="renewpassword" autocomplete="off" size="40" value="<?php print $_REQUEST['renewpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>
	<div class="cssClear"></div>
	</div>
		<div>&nbsp;</div>
		</div>
<?php } ?>
<?php } ?>
<div id="accresponse" class="hide"  mismatchemail="<?php print htmlspecialchars(_email_addresses_do_not_match_);?>" samefirstlastname="<?php print htmlspecialchars(_can_not_have_same_first_last_name_);?>" passwordsnomatch="<?php print htmlspecialchars(_passwords_do_not_match_);?>" emptyfields="<?php print htmlspecialchars(_empty_fields_);?>"></div>



	</div>
<div class="cssClear"></div>




</div>
<?php #################### SHIPPING ADDRESS ############################# ?>
<div id="shippingaddressside" style="<?php if(($ship !==true)||($store['ship_only_billing'] == "1")==true) { ?> display: none;<?php } ?>"  class="nofloatsmallleft">
<div class="pc"><h3><?php print _shipping_address_;?></h3></div>
<?php if($store['ship_only_billing'] == "1") { ?>
<div class="pc"><?php print nl2br($store['ship_only_billing_message']);?></div>
<div style="display: none;"><input type="checkbox" name="ship_billing" id="ship_billing" checked class="newacc checkbox" ></div>
<?php } else { ?>
<div class="pc"><input type="checkbox" name="ship_billing" id="ship_billing" checked class="newacc checkbox" onClick="toggleShippingAddress();"> <label for="ship_billing"><?php print _ship_to_mailing_address_;?></label></div>
<?php } ?>

	<div <?php if(($acc['co_company_ask'] == "0")&&($acc['co_company_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div  class="nofloatsmallleft">

		<div class="pc"><?php print _company_;?></div>
		<div class="pc"><input type="text" name="ship_business" id="ship_business" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_company']));?>" <?php print "class=\"newacc field100\"";  ?>></div>
	</div>
	</div>

	<!-- Shipping address -->
	<div id="shippingaddress" >
	<div style="width: 49%; float: left;"  class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text" name="ship_first_name" id="ship_first_name" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_name']));?>" class="newacc field100 shiprequired <?php if($acc['co_first_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<div style="width: 49%; float: right;"  class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" name="ship_last_name" id="ship_last_name" size="20" value="<?php print htmlspecialchars(stripslashes($p['p_last_name']));?>" class="newacc field100 shiprequired <?php if($acc['co_last_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>
	<div>
		<div>
			<div class="pc"><?php print _address_;?></div>
			<div class="pc"><input type="text" name="ship_address" id="ship_address" size="40"    value="<?php print htmlspecialchars($p['p_address1']);?>" class="newacc field100 shiprequired <?php if($acc['co_address_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div>
		<div>
			<div class="pc"><?php print _city_;?></div>
			<div class="pc"><input type="text" name="ship_city" id="ship_city"  size="30"    value="<?php print htmlspecialchars($p['p_city']);?>" class="newacc field100 shiprequired <?php if($acc['co_city_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<?php 
	if(!empty($p['p_country'])) { 
		$ct = doSQL("ms_countries", "*", " WHERE country_name='".addslashes(stripslashes($p['p_country']))."' ");
	} else { 
		$ct = doSQL("ms_countries", "*", " WHERE ship_to='1' AND def='1' ");
	}
	?>
	<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE state_country='".addslashes(stripslashes($ct['country_name']))."'  ORDER BY state_name ASC ");
	if(mysqli_num_rows($states) > 0) { ?>
	<div  style="float: left;" <?php if(($acc['co_state_ask'] == "0")&&($acc['co_state_req'] == "0")==true) { print "class=\"hide nofloatsmallleft\"";  } else { print "class=\"nofloatsmallleft\""; }  ?>>
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="ship_state" id="ship_state" class="newacc shiprequired <?php if($acc['co_state_req'] == "1") { print "required"; } ?>" onChange="getTax();">
			<option value=""><?php print _select_state_;?></option>
			<?php 
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($p['p_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
			<?php } ?>
			<option value="N/A">N/A</option>
			</select>
		</div>
	</div>
	<?php } ?>
		<div style="float: left;" class="nofloatsmallleft">
			<div class="pc"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="ship_zip" id="ship_zip" size="8" value="<?php print htmlspecialchars($p['p_zip']);?>" class="newacc shiprequired <?php if($acc['co_zip_req'] == "1") { print "required"; } ?>"  onChange="getTax();"></div>
		</div>
<div class="cssClear"></div>

	<div>
		<div>
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select name="ship_country"  id="ship_country"  class="newacc shiprequired <?php if($acc['co_country_req'] == "1") { print "required"; } ?>"  onChange="getstates(this.value,'0','1');">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($p['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
		</div>
		<div>&nbsp;</div>
		</div>

		<!-- End Shipping Address -->


</div>
<div class="clear"></div>
<?php if($store['checkout_notes'] == "1") { ?>
<div class="pc ordernotesection center">
<a href="" onclick="addordernotes(); return false;" class="the-icons icon-feather"><?php print _add_note_to_order_;?></a>
<textarea name="order_notes" id="order_notes" class="field100 hide" rows="3"></textarea>
</div>
<?php } ?>
<div>&nbsp;</div>

<div class="pc center">
<input type="hidden" class="newacc" name="action" id="action" value="newaccount">
<span class="checkout"  onClick="createaccount('newacc', '<?php if(customerLoggedIn()) { print "1"; } else { print "0"; } ?>','1'); return false;" ><?php print _next_;?> &rarr;</span>

</div>
</div>

<div id="shippingcontainer" style="display: none;">
<?php if($ship == true) { ?>
<div class="pc"><h1><?php print _checkout_;?> > <?php print _checkout_progress_shipping_;?></h1></div>
<div id="shippingselect">

<?php $ships = whileSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
		while($ship = mysqli_fetch_array($ships)) { 
			$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$ship['method_id']."' AND price_from<='$pay_total' AND price_to>='$pay_total' ");
			$price = $price['price_amount'] + (($price['price_amount'] * $state['state_add_ship_percent']) / 100) + (($price['price_amount'] * $country['add_price']) / 100) ;
			if($price > 0) { 
				$price = $price + $total['add_ship'];
			}
			if($ship['method_pickup'] == "1") { 
				$price = 0;
			}

			$pt++;
			?>
			<div>
			<div class="pc"><input type="radio" class="checkbox" name="ship_select" id="ship_select-<?php print $price['price_id'];?>" price="<?php print $price['price_amount'];?>" priceshow="<?php print showPrice($price['price_amount']);?>" onClick="addshipping();" value="<?php print $price['price_id'];?>" <?php if($pt <=1) { print "checked"; } ?>  data-pickup="<?php print $ship['method_pickup'];?>"> <label for="ship_select-<?php print $price['price_id'];?>"><?php print $ship['method_name'].": "; if($price['price_amount'] <= 0) { print _free_; } else { print showPrice($price['price_amount']); } ?></label></div>
			<?php if(!empty($ship['method_descr'])) { ?><div class="pc"><?php print nl2br($ship['method_descr']);?></div><?php } ?>
			</div>
			<div>&nbsp;</div>
		<?php } ?>
		</div>

		<div>&nbsp;</div>

	<?php } ?>
</div>
<div class=" checkoutnavloading center" style="display: none; position: relative;"><div class="loadingspinnersmall"></div></div>

<div id="paymentselect" style="display: none;">




<script type="text/javascript">
function SubmitForm() {
	document.myform.submit();
}
</script>

<input type="hidden" name="view" value="checkout">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="amount" value="<?php print $pay_total;?>">



<div id="paymentSelect" class="pc">
<div class="pc"><h1><?php print _checkout_;?> > <?php print _checkout_progress_payment_;?></h1></div>


<?php 
$disabled_payment_options = array();
$pay_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id","*","WHERE ".checkCartSession()." AND disabled_payment_options!=''  AND cart_order<='0'  ");
if(!empty($pay_check['date_id'])) { 
	// print "<li>DISABLE: ".$pay_check['disabled_payment_options'];
	$disabled_payment_options = explode(",",$pay_check['disabled_payment_options']);
	foreach($disabled_payment_options AS $po) { 
		$and_disabled .= " AND pay_option!='".$po."' ";
	}
}
$pay_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id","*","WHERE ".checkCartSession()." AND disabled_payment_options!='' AND cart_order<='0' ");
if(!empty($pay_check['date_id'])) { 
	$disabled_payment_options = explode(",",$pay_check['disabled_payment_options']);
	foreach($disabled_payment_options AS $po) { 
		$and_disabled .= " AND pay_option!='".$po."' ";
	}
}

if(countIt("ms_payment_options",  "WHERE pay_status='1'  AND pay_option!='paypalexpress' $and_disabled ORDER BY pay_order ASC ") > 1) { ?>
<div class="pc paymentoptionsavailable"><h3><?php print _checkout_how_would_you_like_to_pay_;?></h3></div>
<?php } ?>


<?php include $setup['path']."/sy-inc/store/payment/payment.select.php"; ?>

<div>&nbsp;</div>


</div>

</center>


</div>
</form>
<?php if(!empty($paykey['pay_private_key'])) { ?><script src="https://secure.ewaypayments.com/scripts/eCrypt.js"></script><?php } ?>
<div class="clear"></div>

</div></div>
<div class="pc center" id="buttonsShipping" style="display:none;">
		<span class="checkout"   onClick="editInfo(); return false;">&larr; <?php print _back_;?></span>
		<span class="checkout"  onClick="saveshipping(); return false;"><?php print _next_;?> &rarr;</span>
		<div class="clear"></div>
		</div>
<?php } ?>
<?php } // END IF CART NOT EMPTY ?>
