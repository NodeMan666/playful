<?php
require "../../../sy-config.php";
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";

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
$curlVerify = 0;

$ms = doSQL("ms_settings", "*", "  ");
$store = doSQL("ms_store_settings", "*", "");
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

$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$site_setup['css']."'");
?>
<html>
<head>
<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='$css_id' ORDER BY font ASC ");
if(mysqli_num_rows($fonts) > 0) { 
	while($font = mysqli_fetch_array($fonts)) { 
		if($f > 0) { 
			$add_fonts .= "|";
		}
		$add_fonts .= str_replace(" ","+",$font['font']);
		$f++;
	}
	?>
<link href='//fonts.googleapis.com/css?family=<?php print $add_fonts;?>&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<?php } ?>
<style>
<?php include $setup['path']."/sy-inc/sy-style-include.php"; ?>
</style>

</head>
<body>

<?php 
if($_POST['x_response_code']!== "1") {
	?>
<div style="width: 400px; margin: 100px auto 100px auto;">
<div style="padding: 8px;"><h2>Sorry, your transaction was not approved.</h2></div>
<div style="padding: 8px;"><a href="<?php print $setup['url'].$setup['temp_url_folder']."/index.php?view=checkout";?>">Click here to return and try again.</a></div>
</div>

	<?php 
	// Declined
} else {
	//APPROVED
	$pending_order = doSQL("ms_pending_orders", "*", "WHERE order_id='".$_POST['x_invoice_num']."' ORDER BY order_id DESC");
	if(!empty($pending_order['order_id'])) {
		$payment_amount = $pending_order['order_total'];
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

		$order_tax_percentage = $pending_order['order_tax_percentage'];
		$order_taxable_amount= $pending_order['order_taxable_amount'];
		$order_key= $pending_order['order_key'];
		$customer_id= $pending_order['order_customer'];
		$order_session = $pending_order['order_session'];
		$credit_amount= $pending_order['order_credit'];
		$gift_certificate_amount= $pending_order['gift_certificate_amount'];
		$gift_certificate_id= $pending_order['gift_certificate_id'];
		$order_notes= $pending_order['order_notes'];
		$order_eb_discount = $pending_order['order_eb_discount'];

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
		$currency = "USD";
		$transaction_id = $_POST['x_trans_id'];
		$order_ship_amount = $order_ship_amount;
		$order_tax = $order_tax;
		$tax_percentage = $order_tax_percentage;
		$taxable_amount= $order_taxable_amount;
		$vat = $order_vat;
		$vat_percentage = $order_vat_percentage;
		$order_discount = $order_discount;
		$order_message  = $_POST['customer_message'];
		$order_pay_type = $_POST['x_card_type'];
		$order_payment_status = "Completed";
		$order_pending_reason = $_POST['pending_reason'];
		$pay_option = "authorizenetsim";
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
		$anet_sim = true;
		if($pending_order['order_order_id'] > 0) { 
			$_REQUEST['order_id'] = $pending_order['order_order_id'];
			$order_id = $pending_order['order_order_id'];
			include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
		} else { 
			include $setup['path']."/sy-inc/store/payment/payment-complete.php";
			createOrder();
		}
	}


}
?>