<?php 
$order_total_pay = $_REQUEST['grand_total'];
$payment_amount = $_REQUEST['grand_total'];
$order_offline = 0;
$order_manual = 1;
$sub_total = $_REQUEST['sub_total'];
$order_fees = "";
$currency = "USD";
$transaction_id = $response['dc_transaction_id'];
$order_ship_amount = $_REQUEST['shipping_price'];
$shipping_option = $_REQUEST['ship_select'];
$order_tax = $_REQUEST['tax_price'];
$tax_percentage = $_REQUEST['tax_percentage'];
$vat = $_REQUEST['vat_price'];
$vat_percentage = $_REQUEST['vat_percentage'];
$taxable_amount = $_REQUEST['taxable_amount'];
$order_discount = $_REQUEST['discount_amount'];
$order_message  = $_REQUEST['customer_message'];
$order_pay_type = "CCM";
$order_payment_status = "Pending";
$order_pending_reason = $_REQUEST['pending_reason'];
$pay_option = "CCM";
$first_name = $_REQUEST['first_name'];
$last_name = $_REQUEST['last_name'];
$email_address = $_REQUEST['email_address'];
$company_name = $_REQUEST['business_name'];
$country = $_REQUEST['country'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$address_status = $_REQUEST['address_status'];
$address = $_REQUEST['address'];
$phone = $_REQUEST['order_phone'];
$order_session = $_SESSION['ms_session'];
$ship_business = $_REQUEST['ship_business'];
$ship_first_name = $_REQUEST['ship_first_name'];
$ship_last_name =  $_REQUEST['ship_last_name'];
$ship_address  = $_REQUEST['ship_address'];
$ship_city = $_REQUEST['ship_city'];
$ship_state = $_REQUEST['ship_state'];
$ship_zip = $_REQUEST['ship_zip'];
$ship_country = $_REQUEST['ship_country'];
$ip_address = getUserIP();
$coupon_id = $_REQUEST['coupon_id'];
$coupon_name = $_REQUEST['coupon_name'];
$credit_amount = $_REQUEST['credit_amount'];
$gift_certificate_amount = $_REQUEST['gift_certificate_amount'];
$gift_certificate_id = $_REQUEST['gift_certificate_id'];
$order_eb_discount = $_REQUEST['eb_amount'];
$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
$customer_id = $person['p_id'];
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




// print "<pre>"; print_r($_REQUEST); print "</pre>";
// exit();
if($_REQUEST['test_decline'] == "1") { 
	$_SESSION['decline_message'] = _checkout_card_declined_."(".$response['dc_response_message'].") ";

	include $setup['path']."/sy-inc/store/payment/payment-declined.php";
} elseif($_REQUEST['order_id'] > 0) { 
	include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
} else { 
	include $setup['path']."/sy-inc/store/payment/payment-complete.php";
	createOrder();
}
?>
