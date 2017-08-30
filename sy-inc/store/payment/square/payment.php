<?php
require "../../../../sy-config.php";
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
// require $setup['path']."/".$setup['inc_folder']."/store/payment/paypal-standard-ipn.php"; 

$date = date("D M j G:i:s T Y", time());
$c_purchase = $_POST['payment_gross'] / .5;
$dbloc = "".$setup['pc_db_location']."";
$dbase = "".$setup['pc_db']."";
$dbuser = "".$setup['pc_db_user']."";
$dbpass = "".$setup['pc_db_pass']."";


$sitename = "$HTTP_HOST";
$path = "".$setup['path']."";
$url = "http://".$_SERVER['HTTP_HOST']."";
if(is_array($db_user_array)) {
	$db_user = $db_user_array[ rand( 0, ( sizeof($db_user_array) -1 ) ) ];  
} else {
	$db_user = $setup['pc_db_user'];
}
$dbcon = @mysqli_connect($setup['pc_db_location'],$db_user,$setup['pc_db_pass'],$setup['pc_db'],$setup['db_port'],$setup['db_socket']);
if (!$dbcon) {	echo( "Unable to connect to the database" .mysqli_error($dbcon));	exit(); }
//if (! @mysqli_select_db("".$setup['pc_db']."") ) {	echo( "Unable to locate the database: ".$setup['pc_db']."");	exit(); }
mysqli_query($dbcon,"SET NAMES 'utf8'");
mysqli_query($dbcon,"SET CHARACTER SET utf8");
mysqli_query($dbcon,"SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
mysqli_query($dbcon,"SET SESSION sql_mode = '' ");
// mysqli_query("SET time_zone = '".$site_setup['time_zone']."'");
if(!empty($setup['lc_time_names'])) { 
	mysqli_query($dbcon,"SET lc_time_names = '".$setup['lc_time_names']."' ");
}

$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$ms = doSQL("ms_settings", "*", "  ");
$store = doSQL("ms_store_settings", "*", "");
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
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
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

$approved = false;






$payinfo = doSQL("ms_payment_options", "*", "WHERE pay_option='square' ");



require 'autoload.php';

# Replace these values. You probably want to start with your Sandbox credentials
# to start: https://docs.connect.squareup.com/articles/using-sandbox/

# The ID of the business location to associate processed payments with.
# If you're testing things out, use a sandbox location ID.
#
# See [Retrieve your business's locations](https://docs.connect.squareup.com/articles/getting-started/#retrievemerchantprofile)
# for an easy way to get your business's location IDs.
// $location_id = 'CBASENTiYCdXJMa1IhvR8wqbugEgAQ';

# The access token to use in all Connect API requests. Use your *sandbox* access
# token if you're just testing things out.
$access_token = $payinfo['pay_key'];

if(empty($setup['square_location_id'])) { 
	$location_token = "Bearer ".$access_token;
	$location_api = new \SquareConnect\Api\LocationApi();
	$locations =  $location_api->listLocations($access_token);
	$location_id = $locations["locations"][0]["id"];
} else { 
	$location_id = $setup['square_location_id'];
}

# Helps ensure this code has been reached via form submission
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  error_log("Received a non-POST request");
  echo "Request not allowed";
  http_response_code(405);
  return;
}

# Fail if the card form didn't send a value for `nonce` to the server
$nonce = $_POST['nonce'];
if (is_null($nonce)) {
  echo "Invalid card data";
  http_response_code(422);
  return;
}

$transaction_api = new \SquareConnect\Api\TransactionApi();

$request_body = array (

  "card_nonce" => $nonce,

  # Monetary amounts are specified in the smallest unit of the applicable currency.
  # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
  "amount_money" => array (
    "amount" => $_POST['grand_total']*100,
    "currency" => $store['currency']
  ),

  # Every payment you process with the SDK must have a unique idempotency key.
  # If you're unsure whether a particular payment succeeded, you can reattempt
  # it with the same idempotency key without worrying about double charging
  # the buyer.
  "idempotency_key" => uniqid()
);

# The SDK throws an exception if a Connect endpoint responds with anything besides
# a 200-level HTTP code. This block catches any exceptions that occur from the request.
try {
  $result = $transaction_api->charge($access_token, $location_id, $request_body);
  echo "<pre>";
  print_r($result);
  echo "</pre>";
  $approved = true;

$transaction = $result->getTransaction();
$transactionID = $transaction["tenders"][0]["transaction_id"];
$fee = $transaction["tenders"][0]["processing_fee_money"];
$amount = $transaction["tenders"][0]["amount_money"]["amount"];
$card_brand = $transaction["tenders"][0]["card_details"]["card"]["card_brand"];
$last_four = $transaction["tenders"][0]["card_details"]["card"]["last_4"];


print "<h1>id: ".$transactionID."</h1>"; 
 print "<h1>fee: ".$fee."</h1>"; 
print "<h1>brand: ".$card_brand."</h1>"; 
print "<h1>amount: ".$amount."</h1>"; 
print "<h1>4: ".$last_four."</h1>"; 

} catch (\SquareConnect\ApiException $e) {
	$approved = false;

  echo "Caught exception!<br/>";
  print_r("<strong>Response body:</strong><br/>");
  echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
	$response = $e->getResponseBody();
	$er = $response -> errors;
	$error_detail = $er[0]->detail;


  echo "<br/><strong>Response headers:</strong><br/>";
  echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";
  //die();
}


if($approved == false) { 
	insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['grand_total']."', card='".$last_four."' , card_brand='".$card_brand."' , response_code='".$response->transaction_id."',  response_message='".addslashes(stripslashes($error_detail))."', name='". addslashes(stripslashes($_POST['first_name']))." ". addslashes(stripslashes($_POST['last_name']))." ',  email='".$_POST['email_address']."', order_number='".$_POST['order_number']."', country='".$_POST['country']."', all_data='". addslashes(stripslashes($all_data))."' , declined='1'  ");


	$_SESSION['decline_message'] = _checkout_card_declined_."(".$error_detail .") ";
	include $setup['path']."/sy-inc/store/payment/payment-declined.php";
	exit();
}

if($approved == true) { 
	// exit();
	$order_total_pay = $_POST['grand_total'];
	$payment_amount = $_POST['grand_total'];
	$payment_amout = $order_total_pay;
	$sub_total = $_REQUEST['sub_total'];
	$order_fees = $fee;
	$currency = $store['currency'];
	$transaction_id = $transactionID;
	$order_ship_amount = $_REQUEST['shipping_price'];
	$shipping_option = $_REQUEST['ship_select'];
	$order_tax = $_REQUEST['tax_price'];
	$tax_percentage = $_REQUEST['tax_percentage'];
	$vat = $_POST['vat_price'];
	$vat_percentage = $_POST['vat_percentage'];
	$taxable_amount = $_POST['taxable_amount'];
	$order_discount = $_POST['discount_amount'];
	$order_message  = $_POST['customer_message'];
	$order_pay_type = $card_brand;
	$order_payment_status = "Completed";
	$order_pending_reason = $_POST['pending_reason'];
	$pay_option = "Square";
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
}
?>