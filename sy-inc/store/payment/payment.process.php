<?php 
if(($_REQUEST['payment_method'] == "paypalstandard")||($_REQUEST['payment_method'] == "authorizenetsim")||($_REQUEST['payment_method'] == "worldpay")||($_REQUEST['payment_method'] == "apco")||($_REQUEST['payment_method'] == "sisow")||(($_REQUEST['payment_method'] == "paypalpro")&&($_REQUEST['pppro']=="express"))==true) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	$customer_id = $person['p_id'];

	$time_stamp = date('Ymdhis');
	$pend_order = insertSQL("ms_pending_orders", "
	order_session='".$_SESSION['ms_session']."', 
	order_key='".$time_stamp."',
	order_company='".addslashes(stripslashes($_REQUEST['business_name']))."', 
	order_first_name='".addslashes(stripslashes($_REQUEST['first_name']))."', 
	order_last_name='".addslashes(stripslashes($_REQUEST['last_name']))."', 
	order_email='".addslashes(stripslashes($_REQUEST['email_address']))."', 
	order_address='".addslashes(stripslashes($_REQUEST['address']))."', 
	order_city='".addslashes(stripslashes($_REQUEST['city']))."', 
	order_state='".addslashes(stripslashes($_REQUEST['state']))."', 
	order_country='".addslashes(stripslashes($_REQUEST['country']))."', 
	order_zip='".addslashes(stripslashes($_REQUEST['zip']))."', 
	order_phone='".addslashes(stripslashes($_REQUEST['order_phone']))."' , 
	order_join_ml='".$_REQUEST['join_ml']."',
	order_ship_business='".addslashes(stripslashes($_REQUEST['ship_company_name']))."', 
	order_ship_first_name='".addslashes(stripslashes($_REQUEST['ship_first_name']))."', 
	order_ship_last_name='".addslashes(stripslashes($_REQUEST['ship_last_name']))."', 
	order_ship_address='".addslashes(stripslashes($_REQUEST['ship_address']))."', 
	order_ship_city='".addslashes(stripslashes($_REQUEST['ship_city']))."', 
	order_ship_state='".addslashes(stripslashes($_REQUEST['ship_state']))."', 
	order_ship_country='".addslashes(stripslashes($_REQUEST['ship_country']))."', 
	order_ship_zip='".addslashes(stripslashes($_REQUEST['ship_zip']))."', 
	order_total='".addslashes(stripslashes($_REQUEST['grand_total']))."', 
	order_tax='".addslashes(stripslashes($_REQUEST['tax_price']))."', 
	order_tax_percentage='".$_REQUEST['tax_percentage']."',
	order_vat='".$_REQUEST['vat_price']."', 
	order_vat_percentage='".$_REQUEST['vat_percentage']."',
	order_taxable_amount = '".$_REQUEST['taxable_amount']."',
	order_discount='".$_REQUEST['discount_amount']."', 
	order_shipping='".addslashes(stripslashes($_REQUEST['shipping_price']))."',
	order_shipping_option='".$_REQUEST['ship_select']."',
	order_sub_total='".$_REQUEST['sub_total']."',
	order_coupon_id='".$_REQUEST['coupon_id']."',
	order_coupon_name='".addslashes(stripslashes($_REQUEST['coupon_name']))."',
	order_ip= '".getUserIP()."',
	order_credit = '".$_REQUEST['credit_amount']."',
	order_gift_certificate = '".$_REQUEST['gift_certificate_amount']."',
	order_gift_certificate_id = '".$_REQUEST['gift_certificate_id']."',

	order_eb_discount = '".$_REQUEST['eb_amount']."',
	order_order_id='".$_REQUEST['order_id']."',
	order_notes='".addslashes(stripslashes($_POST['order_notes']))."',
	order_extra_field_1='".addslashes(stripslashes($_POST['order_extra_field_1']))."',
	order_extra_val_1='".addslashes(stripslashes($_POST['order_extra_val_1']))."',

	order_extra_field_2='".addslashes(stripslashes($_POST['order_extra_field_2']))."',
	order_extra_val_2='".addslashes(stripslashes($_POST['order_extra_val_2']))."',

	order_extra_field_3='".addslashes(stripslashes($_POST['order_extra_field_3']))."',
	order_extra_val_3='".addslashes(stripslashes($_POST['order_extra_val_3']))."',

	order_extra_field_4='".addslashes(stripslashes($_POST['order_extra_field_4']))."',
	order_extra_val_4='".addslashes(stripslashes($_POST['order_extra_val_4']))."',

	order_extra_field_5='".addslashes(stripslashes($_POST['order_extra_field_5']))."',
	order_extra_val_5='".addslashes(stripslashes($_POST['order_extra_val_5']))."',

	order_customer='".$customer_id."'
	
	");

	$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE cart_session='".$_SESSION['ms_session']."' AND cart_coupon!='0' AND cart_order='0' ");
	if(!empty($check_in_cart['cart_id'])) {
		updateSQL("ms_cart", "cart_discount_amount='".$total['promo_discount_amount']."' WHERE cart_id='".$check_in_cart['cart_id']."' ");
	}
	if($_REQUEST['payment_method'] == "authorizenetsim") { 
		include $setup['path']."/sy-inc/store/payment/payment-form-authorize-net-sim.php";
	}
	if(($_REQUEST['payment_method'] == "sisow") && ($_REQUEST['pay_total_zero'] !== "1") == true) { 
		$_SESSION['pending_order'] = $pend_order;

		header("location: ".$setup['temp_url_folder']."/sy-inc/store/payment/sisow/index.php");
		session_write_close();
		exit();
	//	include $setup['path']."/sy-inc/store/payment/sisow/index.php";
	}

	if($_REQUEST['payment_method'] == "apco") { 
		include $setup['path']."/sy-inc/store/payment/payment-form-apco.php";
	}

	if($_REQUEST['payment_method'] == "worldpay") { 
		include $setup['path']."/sy-inc/store/payment/payment-form-worldpay.php";
	}
	if($_REQUEST['payment_method'] == "paypalstandard") { 
		include $setup['path']."/sy-inc/store/payment/paypal-standard.php";
	}
	if($_REQUEST['pppro'] == "express") { 
		$included = true;
		$start = true;
		$_SESSION['pending_order'] = $pend_order;
		include $setup['path']."/sy-inc/store/payment/paypal-pro-express.php";
	}

}

if($_REQUEST['pay_total_zero'] == "1") { 
	include $setup['path']."/sy-inc/store/payment/zero-total.php";
} else { 
	if($_REQUEST['payment_method'] == "payjunction") { 
		$pj_user = $payopt['pay_num'];
		$pj_pass = $payopt['pay_key'];
		include $setup['path']."/sy-inc/store/payment/payjunction.php";
	}
	if($_REQUEST['payment_method'] == "testonly") { 
		include $setup['path']."/sy-inc/store/payment/test-only.php";
	}

	if($_REQUEST['payment_method'] == "eway") { 
		include $setup['path']."/sy-inc/store/payment/eway/index.php";
	}

	if($_REQUEST['payment_method'] == "authorizenetaim") { 
		include $setup['path']."/sy-inc/store/payment/anetaim.php";
	}
	if($_REQUEST['payment_method'] == "stripe") { 
		include $setup['path']."/sy-inc/store/payment/stripe.php";
	}
	if($_REQUEST['payment_method'] == "square") { 
		include $setup['path']."/sy-inc/store/payment/square/payment.php";
	}

	if($_REQUEST['payment_method'] == "emailform") { 
		include $setup['path']."/sy-inc/store/payment/manual.php";
	}

	if($_REQUEST['payment_method'] == "payoffline") { 
		include $setup['path']."/sy-inc/store/payment/pay-offline.php";
	}
	if($_REQUEST['payment_method'] == "payoffline2") { 
		include $setup['path']."/sy-inc/store/payment/pay-offline2.php";
	}

	if(($_REQUEST['payment_method'] == "paypalpro")&&($_REQUEST['pppro'] == "creditcard")==true){ 
		include $setup['path']."/sy-inc/store/payment/paypal-pro-direct-payment.php";
	}

}

exit();
?>
