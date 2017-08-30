<?php
if($included !== true) { 
	require "../../../../sy-config.php";
	require $setup['path']."/".$setup['inc_folder']."/functions.php";
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
	$dbcon = dbConnect($setup);
	$store = doSQL("ms_store_settings", "*", "");
	if($store['checkout_ssl'] == "1") { 
		if(!empty($store['checkout_ssl_link'])) { 
			$url =  $store['checkout_ssl_link'];
		} else { 
			$url = "https://".$_SERVER['HTTP_HOST'];
		}
	} else { 
		$url = $setup['url'];
	}
}

$express = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalexpress' ");
if($express['test_mode'] == "1") { 
	$PayPalMode         = 'sandbox'; // sandbox or live
}
$PayPalApiUsername  = trim($express['pay_email']); //PayPal API Username
$PayPalApiPassword  = trim($express['pay_num']); //Paypal API password
$PayPalApiSignature     = trim($express['pay_key']); //Paypal API Signature
$PayPalCurrencyCode     = $store['currency']; //Paypal Currency Code
$PayPalReturnURL    = $url.$setup['temp_url_folder']."/index.php?view=checkoutexpress"; //Point to process.php page
$PayPalCancelURL    = $setup['url'].$setup['temp_url_folder']."/index.php?view=cart"; //Cancel URL if user clicks cancel
?>