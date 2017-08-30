<?php
require "../../../sy-config.php";
error_reporting(E_ALL & ~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/payment/paypal-standard-ipn.php"; 
function logspaypal() { 
	global $setup;

	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "paypal-log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	$info =  date('Y-m-d h:i:s')." ".getUserIP()." ".$_SERVER['HTTP_USER_AGENT']." \r\n "; 
	foreach($_REQUEST AS $var => $val) { 
		$info .= $var.": ".$val."\r\n";
	}

	$info .= "URL: ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}

// logspaypal();

$date = date("D M j G:i:s T Y", time());
$c_purchase = $_POST['payment_gross'] / .5;
$dbloc = "".$setup['pc_db_location']."";
$dbase = "".$setup['pc_db']."";
$dbuser = "".$setup['pc_db_user']."";
$dbpass = "".$setup['pc_db_pass']."";
if(empty($_POST['custom'])) {
	 die();
}

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
$curlVerify = 0;
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

$from =explode(".com", $_SERVER['HTTP_REFERER']);
$fromb =explode("https://", $from[0]);
// $fromb =explode("http://", $from[0]);
/*
foreach($HTTP_POST_VARS AS $this1 => $that1) {
	$message.= "$this1 => $that1 \r\n";
}
$subject = "TESTING";
$additional_headers = "From: \"$fromname\" <$from>\nReply-To: $from";
mail("tsgenet@gmail.com",$subject,$message,$additional_headers);
*/


$paypal_info = $_POST;

if($curlVerify == 1) {
	$paypal_ipn = new paypal_ipn($paypal_info);
	$paypal_ipn->error_email = "".$ms['contact_email']."";
	$paypal_ipn->send_response();
	if( !$paypal_ipn->is_verified() ) {
		$paypal_ipn->error_out("Bad order (PayPal says it's invalid)");
	}
	switch( $paypal_ipn->get_payment_status() )
	{
		case 'Completed':
		break;

		case 'Pending':
			$paypal_ipn->error_out("Pending Payment");
		break;

		case 'Failed':
			$paypal_ipn->error_out("Failed Payment");
		break;

		case 'Denied':
			$paypal_ipn->error_out("Denied Payment");
		break;

		default:
			$paypal_ipn->error_out("Unknown Payment Status" . $paypal_ipn->get_payment_status());
		break;
	}
}


	if($curlVerify == 0) {
		if((empty($_POST['txn_id']))OR(empty($_POST['payment_type']))==true) {
			die();
		}
	}

// Completed Transaction

if($_POST['payment_status'] == "Completed") { 
	if(empty($_POST['payment_gross'])) {
		$payment_amount = $_POST['mc_gross'];
	} else {
		$payment_amount = $_POST['payment_gross'];
	}

	$payment_fees = $_POST['mc_fee_ x'] + $_POST['mc_fee'];
	$order_infos = explode("|", $_POST['custom']);
	$order_session = $order_infos[0];
	$order_key = $order_infos[1];

	$pending_order = doSQL("ms_pending_orders", "*", "WHERE MD5(order_id)='".$order_infos[4]."' AND order_session='$order_session' ORDER BY order_id DESC");
	if(!empty($pending_order['order_id'])) {
		$order_first_name=$pending_order['order_first_name'];
		$order_last_name=$pending_order['order_last_name'];
		$order_email=$pending_order['order_email'];
		$order_address=$pending_order['order_address'];
		$order_city=$pending_order['order_city'];
		$order_state=$pending_order['order_state'];
		$order_country=$pending_order['order_country'];
		$order_zip=$pending_order['order_zip'];
		if(!empty($pending_order['order_join_ml'])) {
			$order_join_ml=$pending_order['order_email'];
		}
		$order_phone=$pending_order['order_phone'];
		$order_company=$pending_order['order_company'];

		$order_ship_business=$pending_order['order_ship_business'];
		$order_ship_first_name=$pending_order['order_ship_first_name'];
		$order_ship_last_name=$pending_order['order_ship_last_name'];
		$order_ship_email=$pending_order['order_ship_email'];
		$order_ship_address=$pending_order['order_ship_address'];
		$order_ship_city=$pending_order['order_ship_city'];
		$order_ship_state=$pending_order['order_ship_state'];
		$order_ship_country=$pending_order['order_ship_country'];
		$order_ship_zip=$pending_order['order_ship_zip'];

		$order_ship_amount = $pending_order['order_shipping'];
		$order_tax = $pending_order['order_tax'];
		$order_discount = $pending_order['order_discount'];
		$order_sub_total = $pending_order['order_sub_total'];
		$order_shipping_option = $pending_order['order_shipping_option'];
		$order_coupon_id= $pending_order['order_coupon_id'];
		$order_coupon_name = $pending_order['order_coupon_name'];
		$order_vat = $pending_order['order_vat'];
		$order_vat_percentage = $pending_order['order_vat_percentage'];
		$order_eb_discount = $pending_order['order_eb_discount'];

		$order_tax_percentage = $pending_order['order_tax_percentage'];
		$order_taxable_amount= $pending_order['order_taxable_amount'];
		$order_key= $pending_order['order_key'];
		$customer_id= $pending_order['order_customer'];
		$credit_amount= $pending_order['order_credit'];
		$gift_certificate_amount= $pending_order['gift_certificate_amount'];
		$gift_certificate_id= $pending_order['gift_certificate_id'];
		$order_notes= $pending_order['order_notes'];
		$order_extra_field_1 = $pending_order['order_extra_field_1'];
		$order_extra_val_1 = $pending_order['order_extra_val_1'];
		$order_extra_field_2 = $pending_order['order_extra_field_2'];
		$order_extra_val_2 = $pending_order['order_extra_val_2'];
		$order_extra_field_3 = $pending_order['order_extra_field_3'];
		$order_extra_val_3 = $pending_order['order_extra_val_3'];
		$order_extra_field_4 = $pending_order['order_extra_field_4'];
		$order_extra_val_4 = $pending_order['order_extra_val_4'];
		$order_extra_field_5 = $pending_order['order_extra_field_5'];
		$order_extra_val_5 = $pending_order['order_extra_val_5'];

		deleteSQL("ms_pending_orders", "WHERE order_id='".$pending_order['order_id']."' ","1");

		$sub_total = $order_sub_total;
		$shipping_option = $order_shipping_option;
		$coupon_id = $order_coupon_id;
		$coupon_name = $order_coupon_name;
		$order_key=$order_key;
		$order_total_pay = $payment_amount;
		$order_fees = $payment_fees;
		$currency = $_POST['mc_currency'];
		$transaction_id = $_POST['txn_id'];
		$order_ship_amount = $order_ship_amount;
		$order_tax = $order_tax;
		$tax_percentage = $order_tax_percentage;
		$taxable_amount= $order_taxable_amount;
		$vat = $order_vat;
		$vat_percentage = $order_vat_percentage;
		$order_discount = $order_discount;
		$order_message  = $_POST['customer_message'];
		$order_pay_type = "PayPal - ".$_POST['payment_type']." ";
		$order_payment_status = $_POST['payment_status'];
		$order_pending_reason = $_POST['pending_reason'];
		$pay_option = "paypalstandard";
		$first_name = $order_first_name;
		$last_name = $order_last_name;
		$email_address = $order_email;
		$company_name = $order_company;
		$country = $order_country;
		$city = $order_city;
		$state = $order_state;
		$zip = $order_zip;
		$address = $order_address;
		$phone = $order_phone;
		$order_session = $order_session;
		$ship_business = $order_ship_business;
		$ship_first_name = $order_ship_first_name;
		$ship_last_name =  $order_ship_last_name;
		$ship_address  = $order_ship_address;
		$ship_city = $order_ship_city;
		$ship_state = $order_ship_state;
		$ship_zip = $order_ship_zip;
		$ship_country = $order_ship_country;
		$order_address_status = $_POST['address_status'];
		$order_payer_status = $_POST['payer_status'];
		
		$ip_address = $pending_order['order_ip'];
		$no_redirect = true;
		if($pending_order['order_order_id'] > 0) { 
			$_REQUEST['order_id'] = $pending_order['order_order_id'];
			$order_id = $pending_order['order_order_id'];
			include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
		} else { 
			include $setup['path']."/sy-inc/store/payment/payment-complete.php";
			createOrder();
		}

	} else {
		/*
		$order_first_name=$_POST['first_name'];
		$order_last_name=$_POST['last_name'];
		$order_email=$_POST['payer_email'];
		$order_address=$_POST['address_street'];
		$order_city=$_POST['address_city'];
		$order_state=$_POST['address_state'];
		$order_country=$_POST['address_country'];
		$order_zip=$_POST['address_zip'];
		$order_join_ml=$order_infos[3];
		$order_phone=$_POST['contact_phone'];
		$order_company=$_POST['payer_business_name'];
		*/
	}

}


/* CLEARED ECHECKS TO DO 

	$check_order = doSQL("ms_orders", "*", "WHERE order_key='".$order_key."' AND order_session='".$order_session."' ");
	if(!empty($check_order['order_id'])) {
		// Cleared echeck
		$order_id = $check_order['order_id'];

		updateSQL("ms_orders", "order_total='".$payment_amount."', 
		order_date=NOW(), 
		order_key='".$order_key."', 
		order_pay_type='PayPal - ".$_POST['payment_type']."', 
		order_payment_status='".$_POST['payment_status']."', 
		order_currency='".$_POST['mc_currency']."', 
		order_fees='".$payment_fees."', 
		order_pending_reason='".$_POST['pending_reason']."', 
		order_pay_transaction='".$_POST['txn_id']."',
		order_first_name='".addslashes(stripslashes($order_first_name))."',
		order_last_name='".addslashes(stripslashes($order_last_name))."',
		order_email='".addslashes(stripslashes($order_email))."',
		order_business_name='".addslashes(stripslashes($order_company))."',
		order_country='".addslashes(stripslashes($order_country))."',
		order_city='".addslashes(stripslashes($order_city))."',
		order_state='".addslashes(stripslashes($order_state))."',
		order_zip='".addslashes(stripslashes($order_zip))."',
		order_address_status='".$_POST['address_status']."',
		order_address='".addslashes(stripslashes($order_address))."',
		order_phone='".addslashes(stripslashes($order_phone))."',
		order_ip='". $order_infos[2]."',
		order_session='".$order_session."',
		order_payer_status='".$_POST['payer_status']."' WHERE order_id='".$check_order['order_id']."' ");


	} else {

		$order_id = insertSQL("ms_orders", "order_total='".$payment_amount."', 
		order_date=NOW(), 
		order_key='".$order_key."', 
		order_pay_type='PayPal - ".$_POST['payment_type']."', 
		order_payment_status='".$_POST['payment_status']."', 
		order_currency='".$_POST['mc_currency']."', 
		order_fees='".$payment_fees."', 
		order_pending_reason='".$_POST['pending_reason']."', 
		order_pay_transaction='".$_POST['txn_id']."',
		order_first_name='".addslashes(stripslashes($order_first_name))."',
		order_last_name='".addslashes(stripslashes($order_last_name))."',
		order_email='".addslashes(stripslashes($order_email))."',
		order_business_name='".addslashes(stripslashes($order_company))."',
		order_country='".addslashes(stripslashes($order_country))."',
		order_city='".addslashes(stripslashes($order_city))."',
		order_state='".addslashes(stripslashes($order_state))."',
		order_zip='".addslashes(stripslashes($order_zip))."',
		order_address_status='".$_POST['address_status']."',
		order_address='".addslashes(stripslashes($order_address))."',
		order_phone='".addslashes(stripslashes($order_phone))."',
		order_ip='". $order_infos[2]."',
		order_session='".$order_session."',
		order_payer_status='".$_POST['payer_status']."' ");
	}
*/

?>