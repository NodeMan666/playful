<?php

/** DoDirectPayment NVP example; last modified 08MAY23.
 *
 *  Process a credit card payment. 
*/
$opt = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalpro' ");
if($opt['test_mode'] == "1") { 
	$environment = 'sandbox';	// or 'beta-sandbox' or 'live'
} else { 
	$environment = 'live';	// or 'beta-sandbox' or 'live'
}
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $nvpStr_,$opt) {
	global $environment,$store;

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($opt['pay_email']);
	$API_Password = urlencode($opt['pay_num']);
	$API_Signature = urlencode($opt['pay_key']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

// Set request-specific fields.
$paymentType = urlencode('Sale');				// or 'Sale'
$firstName = urlencode($_POST['first_name']);
$lastName = urlencode($_POST['last_name']);
$creditCardType = urlencode($_POST['cardtypepp']);
$creditCardNumber = urlencode($_POST['creditcardpp']);
$expDateMonth = $_POST['monthpp'];
// Month must be padded with leading zero
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

$expDateYear = urlencode($_POST['yearpp']);
$cvv2Number = urlencode($_POST['cvvpp']);
$address1 = urlencode($_POST['address']);
$address2 = urlencode($_POST['address2']);
$city = urlencode($_POST['city']);
$state = urlencode($_POST['state']);
$zip = urlencode($_POST['zip']);
$cts = doSQL("ms_countries", "*", "WHERE country_name='".$_POST['country']."' ");
$country = urlencode($cts['abr']);				// US or other valid country code
$amount = urlencode($_POST['grand_total']);
$currencyID = urlencode($store['currency']);							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
// print "<pre> country: ".$country." "; print_r($_POST); print "</pre>"; exit();

// Add request-specific fields to the request string.
$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr,$opt);

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['amount']."', card='".$response['dc_card_number']."' , card_brand='".$response['dc_card_brand']."' , response_code='".$response['dc_response_code']."', response_message='".$response['dc_response_message']."', name='".addslashes(stripslashes($_POST['first_name']))." ".addslashes(stripslashes($_POST['last_name']))." ', email='".$_POST['email_address']."', order_number='".$_POST['order_number']."', country='".addslashes(stripslashes($_POST['country']))."', all_data='". addslashes(stripslashes($all_data))."'  ");

			/* Payjunction response */
			$order_pay_type =  $_POST['c_typepp']; // paypal, credit cart
			$order_total_pay = $_POST['grand_total'];
			$payment_amount = $_POST['grand_total'];
			$payment_amout = $order_total_pay;
			$sub_total = $_REQUEST['sub_total'];
			$order_fees = "";
			$currency = $httpParsedResponseAr['CURRENCYCODE'];
			$transaction_id = $httpParsedResponseAr['TRANSACTIONID'];
			$order_ship_amount = $_REQUEST['shipping_price'];
			$shipping_option = $_REQUEST['ship_select'];
			$order_tax = $_REQUEST['tax_price'];
			$tax_percentage = $_REQUEST['tax_percentage'];
			$taxable_amount = $_REQUEST['taxable_amount'];
			$order_discount = $_POST['discount_amount'];
			$order_message  = $_POST['customer_message'];
			$order_pay_type = $_POST['card_typepp'];
			$order_payment_status = "Completed";
			$order_pending_reason = $_POST['pending_reason'];
			$pay_option = "paypalpro";
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email_address = $_POST['email_address'];
			$company_name = $_POST['business_name'];
			$country = $_POST['country'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			$address_status = $_POST['address_status'];
			$address = $_POST['address'];
			$phone = $_POST['order_phone'];
			$order_session = $_SESSION['ms_session'];
			$ship_business = $_POST['ship_business'];
			$ship_first_name = $_POST['ship_first_name'];
			$ship_last_name =  $_POST['ship_last_name'];
			$ship_address  = $_POST['ship_address'];
			$ship_city = $_POST['ship_city'];
			$ship_state = $_POST['ship_state'];
			$ship_zip = $_POST['ship_zip'];
			$ship_country = $_POST['ship_country'];
			$ip_address = getUserIP();
			$coupon_id = $_REQUEST['coupon_id'];
			$coupon_name = $_REQUEST['coupon_name'];
			$credit_amount = $_REQUEST['credit_amount'];
			$gift_certificate_amount = $_REQUEST['gift_certificate_amount'];
			$gift_certificate_id = $_REQUEST['gift_certificate_id'];
			$order_eb_discount = $_REQUEST['eb_amount'];
			$order_notes = $_POST['order_notes'];
			$order_extra_field_1 = $_REQUEST['order_extra_field_1'];
			$order_extra_val_1 = $_REQUEST['order_extra_val_1'];
			$order_extra_field_2 = $_REQUEST['order_extra_field_2'];
			$order_extra_val_2 = $_REQUEST['order_extra_val_2'];
			$order_extra_field_3 = $_REQUEST['order_extra_field_3'];
			$order_extra_val_3 = $_REQUEST['order_extra_val_3'];
			$order_extra_field_4 = $_REQUEST['order_extra_field_4'];
			$order_extra_val_4 = $_REQUEST['order_extra_val_4'];
			$order_extra_field_5 = $_REQUEST['order_extra_field_5'];
			$order_extra_val_5 = $_REQUEST['order_extra_val_5'];

			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			$customer_id = $person['p_id'];

			if($_REQUEST['order_id'] > 0) { 
				$order_id = $_REQUEST['order_id'];
				include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
			} else { 
				include $setup['path']."/sy-inc/store/payment/payment-complete.php";
				createOrder();
			}


		exit();

//	exit('Direct Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
} else  {
	$_SESSION['decline_message'] = _checkout_card_declined_."(".urldecode($httpParsedResponseAr['L_LONGMESSAGE0']).") ";
	include $setup['path']."/sy-inc/store/payment/payment-declined.php";
	exit('DoDirectPayment failed: ' . print_r($httpParsedResponseAr, true));
}

?>
