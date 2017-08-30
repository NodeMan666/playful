<?php 
if(countIt("ms_payment_schedule", "WHERE order_id='".$_REQUEST['order_id']."' ") > 0) {
	$scheduled_payments = true;
	$paid = $payment_amount;
	$scs = whileSQL("ms_payment_schedule", "*", "WHERE order_id='".$_REQUEST['order_id']."' ORDER BY due_date ASC ");
	while($sc = mysqli_fetch_array($scs)) {
		if($sc['payment'] <= 0) {
			if($paid > 0) { 
				updateSQL("ms_payment_schedule", "payment='".$sc['amount']."', payment_date='".currentdatetime()."', pay_transaction='".addslashes(stripslashes($transaction_id))."', payment_option='".addslashes(stripslashes($pay_option))."', payment_type='".addslashes(stripslashes($order_pay_type))."', payment_ip='".getUserIP()."' WHERE id='".$sc['id']."' ");
				$paid  = $paid - $sc['amount'];
			}
		}
	}

	$order_id = updateSQL("ms_orders", "
	order_session='".$order_session."',
	order_key='".$order_key."'
	WHERE order_id='".$_REQUEST['order_id']."'
	");

	$atotal = doSQL("ms_payment_schedule", "SUM(amount) as total", "WHERE order_id='".$_REQUEST['order_id']."' ");
	$ptotal = doSQL("ms_payment_schedule", "SUM(payment) as total", "WHERE order_id='".$_REQUEST['order_id']."' ");
	if($ptotal >= $atotal) { 

		$order_id = updateSQL("ms_orders", "
		order_payment_status='Completed' 
		WHERE order_id='".$_REQUEST['order_id']."'
		");
	}

} else { 
	$order_id = updateSQL("ms_orders", "
	order_payment='".$payment_amount."',
	order_pay_type='".addslashes(stripslashes($order_pay_type))."', 
	order_payment_status='".addslashes(stripslashes($order_payment_status))."', 
	order_currency='".addslashes(stripslashes($currency))."', 
	order_fees='".addslashes(stripslashes($order_fees))."', 
	order_pending_reason='".addslashes(stripslashes($order_pending_reason))."', 
	order_pay_transaction='".addslashes(stripslashes($transaction_id))."',
	order_payment_option = '".addslashes(stripslashes($pay_option))."',
	order_payer_status='".addslashes(stripslashes($order_payer_status))."',
	order_session='".$order_session."',
	order_key='".$order_key."',
	order_offline='".$order_offline."'
	WHERE order_id='".$_REQUEST['order_id']."'
	");
}

$order_id = $_REQUEST['order_id'];
checkCouponOrder($order_id);
if($order_fees > 0) { 
	$ckexp = doSQL("ms_expenses_tags", "*", "WHERE name='paypal fees' ");
	if(empty($ckexp['tag_id'])) { 
		$exptagid = insertSQL("ms_expenses_tags", "name='paypal fees' ");
	} else { 
		$exptagid = $ckexp['tag_id'];
	}
	$exp = insertSQL("ms_expenses", "exp_amount='".$order_fees."', exp_date=NOW(), exp_order='".$order_id."' ");
	insertSQL("ms_expenses_tags_connect", "con_exp_id='".$exp."', con_tag_id='".$exptagid."' ");
}
	
if($scheduled_payments == true) { 
	include $setup['path']."/sy-inc/store/payment/payment-complete-scheduled-payments.php";
} else { 
	include $setup['path']."/sy-inc/store/payment/payment-complete-send-notification.php";
}

if($anet_sim == true) { 
	anetSimComplete($order_id,$order_session,$order_key);
}

function anetSimComplete($order_id,$order_session,$order_key) { 
	global $setup;
?>
<div style="width: 400px; margin: 100px auto 100px auto;">
<div style="padding: 8px;"><h2>Your transaction is complete</h2></div>
<div style="padding: 8px;"><a href="<?php print $setup['url'].$setup['temp_url_folder']."/index.php?view=order&frompp=1&msos=".$order_session."&msok=".$order_key;?>">Click here to continue</a></div>
</div>
<?php 
}

if($no_redirect !== true) { 
	$_SESSION['payment_complete'] = true;
	header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=order&new=".$order_id."");
	session_write_close();
}
exit();
?>