
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 

if($_REQUEST['subdo'] == "editPaymentOption") {
	require "settings/payment.edit.option.php";
} elseif($_REQUEST['subdo'] == "updateCurrency") { 

	updateSQL("ms_store_settings", "
	currency='".$_REQUEST['currency']."', 
	currency_sign='".$_REQUEST['currency_sign']."', 
	price_format='".$_REQUEST['price_format']."', 
	min_order_amount='".$_REQUEST['min_order_amount']."', 
	price_decimals='".$_REQUEST['price_decimals']."', 
	checkout_ssl_link='".$_REQUEST['checkout_ssl_link']."', 
	checkout_ssl='".$_REQUEST['checkout_ssl']."',
	terms_conditions = '".addslashes(stripslashes($_REQUEST['terms_conditions']))."',
	terms_conditions_link='".addslashes(stripslashes($_REQUEST['terms_conditions_link']))."',
	require_terms_conditions = '".$_REQUEST['require_terms_conditions']."',
	checkout_account='".$_REQUEST['checkout_account']."',
	coupon_checkout_page='".$_REQUEST['coupon_checkout_page']."',
	login_checkout_page='".$_REQUEST['login_checkout_page']."',
	checkout_notes='".$_REQUEST['checkout_notes']."',

	secure_logins = '".$_REQUEST['secure_logins']."'	
	");
	$_SESSION['sm'] = "Settings updated";
	session_write_close();
	header("location: index.php?do=settings&action=checkout");
	exit();

} else {
	listPaymentOptions();
}
?>
<?php  function listPaymentOptions() { 
	global $site_setup,$store,$setup;
	?>
<div id="pageTitle"><a href="index.php?do=settings&action=checkout">Checkout & Payment Options</a></div>




<div style="float: left; width: 48%;">
	<form method="post" name="passdsds" action="index.php">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="checkout">
	<input type="hidden" name="subdo" value="updateCurrency">
	<div class="underlinelabel">Currency & Price Format</div>
	<div>
		<div class="pc underline">
		<div class="left" style="width: 80%;"><div class="bold">Currency code</div><div class="muted">This is the 3 letter currency code for your country. Example: United Sates: USD, Canada: CAD.</div></div>
		<div class="right textright" style="width: 20%;"><input type="text" size="4" name="currency" id="currency" value="<?php print $store['currency'];?>"></div>
		<div class="clear"></div>
		</div>

		<div class="pc underline">
		<div class="left" style="width: 80%;"><div class="bold">Currency sign</div><div class="muted">Example: $</div></div>
		<div class="right textright" style="width: 20%;"><input type="text" size="4" name="currency_sign" id="currency_sign" value="<?php print $store['currency_sign'];?>"></div>
		<div class="clear"></div>
		</div>

		<div class="pc underline"><div class="left" style="width: 50%;"><div class="bold">Price Format </div><div class="muted">Use: [PRICE] to display the price and [CURRENCY_SIGN] to display the currency sign</div></div>
		<div class="right textright" style="width: 50%;"><input type="text" size="30" class="field100" name="price_format" id="price_format" value="<?php print $store['price_format'];?>"></div>
		<div class="clear"></div>
		</div>
		<div class="pc underline">
		<div class="left" style="width: 80%;"><div class="bold">Price Decimals</div><div class="muted">How many decimal places. Most common 2 (example: 3.99). If you enter 0, it will show like $20 instead of $20.00</div></div>
		<div class="right textright" style="width: 20%;"><input type="text" size="4" name="price_decimals" id="price_decimals" value="<?php print $store['price_decimals'];?>"></div>
		<div class="clear"></div>
		</div>

		<div class="pc underline">
		<div class="left" style="width: 80%;"><div class="bold">Minimum order amount </div><div class="muted"></div></div>
		<div class="right textright" style="width: 20%;"><input type="text" size="4" name="min_order_amount" id="min_order_amount" value="<?php print $store['min_order_amount'];?>"></div>
		<div class="clear"></div>
		</div>

		</div>
		<div>&nbsp;</div>

	<div class="underlinelabel">Secure Pages On Checkout & Accounts</div>
		<div class="underline">In order to use secure pages (which is a https link), you must have a SSL / Security Certificate installed on your website. If you don't have one, you can purchase one from many different companies. Godaddy.com offers reasonable prices on these. Once you purchase one, you will need to have your hosting company install it. Have it installed on your main domain name (not a sub domain).</div>
		<div class="underline">
			<div class="bold"><input type="checkbox" name="checkout_ssl" id="checkout_ssl" value="1" <?php if($store['checkout_ssl'] == "1") { print "checked"; } ?>> <label for="checkout_ssl">Check this box if you have a SSL installed.</label></div>
		</div>


		<div class="underline">
		<div>
		<div class="bold">Fixed SSL link</div>
		<div><input type="text" size="40" class="field100" name="checkout_ssl_link" id="checkout_ssl_link" value="<?php print $store['checkout_ssl_link'];?>"></div>
		<div class="">Most likely leave this blank. If your SSL <u>only</u> works with or without the www. prefix, enter in your SSL link above. Example: https://www.mysite.com (do not use a trailing slash). But your security certificate probably works with and without the www. If you don't know, just leave this blank.</div>
		</div>

	</div>
		<div>&nbsp;</div>

		<div class="underlinelabel">Customer Account Options On Checkout</div>
			<div class="underline">
			<div class="bold"><input type="radio" name="checkout_account" id="checkout_account" value="require" <?php if($store['checkout_account'] == "require") { print "checked"; } ?>> Require create an account</div>
			<div>Your customers will have to create an account at checkout.</div>
			</div>

			<div class="underline">
			<div class="bold"><input type="radio" name="checkout_account" id="checkout_account" value="optional" <?php if($store['checkout_account'] == "optional") { print "checked"; } ?>> Creating an account is optional</div>
			<div>Your customers will have the option to create an account.</div>
			</div>

			<div class="underline">
			<div class="bold"><input type="radio" name="checkout_account" id="checkout_account" value="disabled" <?php if($store['checkout_account'] == "disabled") { print "checked"; } ?>> Disable account creation</div>
			<div>Your customers will only be able to checkout as a guest.</div>
			</div>
		<div>&nbsp;</div>
		<div class="underlinelabel">Allow Customer To Make Notes To Order</div>
		<div class="underline"><div class="label"><input type="checkbox" name="checkout_notes" id="checkout_notes" value="1" <?php if($store['checkout_notes'] == "1") { print "checked"; } ?>> <label for="checkout_notes"> Check this to allow customers to make notes to an order at checkout.</label></div></div>

		<div class="underlinelabel">Redeem Coupon & Login On Checkout Page</div>
		<div class="underline">
			<div class="bold"><input type="checkbox" name="coupon_checkout_page" id="coupon_checkout_page" value="1" <?php if($store['coupon_checkout_page'] == "1") { print "checked"; } ?>> Show redeem coupon form (if any valid coupons are available) on the checkout page. </div>
			<div>Redeem coupon for shows on the view cart page by default. Checking this option will also show the form on the checkout page. </div>
			<div class="bold"><input type="checkbox" name="login_checkout_page" id="login_checkout_page" value="1" <?php if($store['login_checkout_page'] == "1") { print "checked"; } ?>> Show log in form on checkout page if customer is not logged in. </div>

		</div>

		<div>&nbsp;</div>




		<div class="underlinelabel">Terms & Conditions</div>
		<div class="underline">
			<div class="bold"><input type="checkbox" name="require_terms_conditions" id="require_terms_conditions" value="1" <?php if($store['require_terms_conditions'] == "1") { print "checked"; } ?>> Add terms and conditions to payment page.</div>
		</div>
		<div class="underline">This will add the terms & conditions you add below to the payment page and a statement saying by placing this order you agree to the terms & conditions below.</div>
		<div class="underline">
			<div class="label">Terms & Conditions link text</div>
			<div><input type="text" name="terms_conditions_link" id="terms_conditions_link"  class="field100" value="<?php print htmlspecialchars(stripslashes($store['terms_conditions_link']));?>">
			</div>
	</div>

		<div class="underline">
			<div class="bold">Terms & Conditions</div>
			<div><textarea name="terms_conditions" id="terms_conditions" rows="10" cols="40" class="field100"><?php print htmlspecialchars(stripslashes($store['terms_conditions']));?></textarea>
			</div>
	</div>
		<div>&nbsp;</div>
		<div class="row center"><input type="submit" name="submit" value="update" class="submit"></div>



	</form>
	<div>&nbsp;</div>
</div>



<div style="float: right; width: 48%;">
<div class="underlinelabel">Payment Options</div>

	<div class="pc">
	Below are the integrated payment options. Some require a SSL / Security and some do not. A SSL  / Security Certificate is what gives you a secure page (http<b>s</b>) when entering in payment information.
	</div>
	<div>&nbsp;</div>


	<div class="underlinelabel">Payment Options That Do Not Require SSL</div>
	<div class="pc">The following options do not need a SSL because the customer will enter in their payment information on the processor's secure page. </div>
	
	<div>
	<div class="underlinecolumn">
		<div style="width: 5%;" class="left">&nbsp;</div>
		<div style="width: 65%;" class="left">Processor</div>
		<div style="width: 30%;" class="right textright">Status</div>
		<div class="clear"></div>
	</div>


<?php 

	$pays = whileSQL("ms_payment_options", "*", "WHERE pay_dev_status='1' AND pay_ssl='0' ORDER BY pay_name ASC" );
	while($pay = mysqli_fetch_array($pays)) { ?>
	<div class="underline">
		<div style="width: 5%;" class="left"><a href="index.php?do=settings&action=editPaymentOption&pay_option=<?php print $pay['pay_option'];?>"><?php print ai_edit;?></a></div>
		<div style="width: 65%;" class="left"><h3><a href="index.php?do=settings&action=editPaymentOption&pay_option=<?php print $pay['pay_option'];?>"><?php print $pay['pay_name'];?></a></h3>
		<?php
			if(!empty($pay['pay_url'])) {
				print "<a href=\"".$pay['pay_url']."\" target=\"_blank\">Website</a>"; 
			}
			if(!empty($pay['pay_short_description'])) { 
				print "<div>".$pay['pay_short_description']."</div>";
			}
			?>
		</div>
		<div style="width: 30%;" class="right textright"><?php if($pay['pay_status'] == "1") { if($pay['test_mode'] == "1") { print " <span class=\"error\">".ai_alert." Test Mode</span>"; } else { print "<span class=\"good\">Active</span>";  } } else { print "&nbsp;"; } ?></div>
		<div class="clear"></div>
		</div>
	<?php } ?>
	<div>&nbsp;</div>
	<div class="underlinelabel">Payment Options That Do Require SSL</div>
	<div class="pc">The following options require you to have a SSL installed because the customer will enter in their credit card information on your website.
	<br><br>
		 <?php if($setup['sytist_hosted'] == true) { ?>If you need the SSL enabled and it is not yet enabled, <a href="https://www.picturespro.com/contact/" target="_blank">contact us</a> to enable it.
	<br><br>
	 <?php } ?>
	 <?php if($setup['sytist_hosted'] !== true) { ?>
		 If you want to use a payment option that requires an SSL and you don't have one, you can purchase a SSL / Security Certificate from <a href="http://www.dpbolvw.net/click-528373-11789166-1399324789000" target="_blank">Godaddy.com</a>, <a href="https://www.rapidssl.com/" target="_blank">Rapid SSL</a>, and other places. 	 Once you purchase one you generally have your hosting company install it for you.
	 <br><br>
	 <?php } ?>
	 </div>
	<div class="underlinecolumn">
		<div style="width: 5%;" class="left">&nbsp;</div>
		<div style="width: 65%;" class="left">Processor</div>
		<div style="width: 30%;" class="right textright">Status</div>
		<div class="clear"></div>
	</div>

<?php 
	$pays = whileSQL("ms_payment_options", "*", "WHERE pay_dev_status='1' AND pay_ssl='1' ORDER BY pay_name ASC" );
	while($pay = mysqli_fetch_array($pays)) { 
		$testmode = false;
		?>
	<div class="underline">
		<div style="width: 5%;" class="left"><a href="index.php?do=settings&action=editPaymentOption&pay_option=<?php print $pay['pay_option'];?>"><?php print ai_edit;?></a></div>
		<div style="width: 65%;" class="left"><h3><a href="index.php?do=settings&action=editPaymentOption&pay_option=<?php print $pay['pay_option'];?>"><?php print $pay['pay_name'];?></a></h3>
		<?php
			if(!empty($pay['pay_url'])) {
				print "<a href=\"".$pay['pay_url']."\" target=\"_blank\">Website</a>"; 
			}
			if(!empty($pay['pay_short_description'])) { 
				print "<div>".$pay['pay_short_description']."</div>";
			}
			?>
		</div>

	<?php
	if (strpos($pay['pay_num'],'sandbox') !== false) {
		$testmode = true;
	}
	if (strpos($pay['pay_num'],'test') !== false) {
		$testmode = true;
	}

	 ?>

		<div style="width: 30%;" class="right textright"><?php if($pay['pay_status'] == "1") { if(($pay['test_mode'] == "1") || ($testmode == true) == true) { print " <span class=\"error\">".ai_alert." Test Mode</span>"; } else { print "<span class=\"good\">Active</span>";  } } else { print "&nbsp;"; } ?></div>
		<div class="clear"></div>
		<?php if(($pay['pay_option'] == "eway") || ($pay['pay_option'] == "square") == true) {
				if(phpversion() < 5.4) { ?><div style="color: #990000;"><span class="the-icons icon-attention" style="color: #ff0000;"></span>Your PHP version is <?php print phpversion();?>. You need to be using PHP version 5.4 or higher to use this payment option. You can contact your hosting company to update your PHP version.</div>
				<?php 
					} 
			}?>
		</div>
	<?php } ?>
</div>
</div>
<div class="clear"></div>
<?php  } ?>