<script type="text/javascript">
function SubmitForm() {
	document.anetform.submit();
}
</script>


<?php
require_once 'anet/AuthorizeNet.php'; // Include the SDK you downloaded in Step 2

$opt = doSQL("ms_payment_options","*", "WHERE pay_option='authorizenetsim' ");
if($store['checkout_ssl'] == "1") { 
	if(!empty($store['checkout_ssl_link'])) { 
		$url =  $store['checkout_ssl_link'];
	} else { 
		$url = "https://".$_SERVER['HTTP_HOST'];
	}
} else { 
	$url = $setup['url'];
}

if(!empty($_REQUEST['order_id'])) { 
	$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
	$_REQUEST['first_name'] = $order['order_first_name'];
	$_REQUEST['last_name'] = $order['order_last_name'];
	$_REQUEST['address'] = $order['order_address'];
	$_REQUEST['city'] = $order['order_city'];
	$_REQUEST['state'] = $order['order_state'];
	$_REQUEST['zip'] = $order['order_zip'];
	$_REQUEST['phone'] = $order['order_phont'];
	$_REQUEST['email_address'] = $order['order_email'];
	$_REQUEST['country'] = $order['order_country'];


}

$api_login_id = $opt['pay_num'];
$transaction_key = $opt['pay_key'];
$amount = $_POST['grand_total'];
$fp_timestamp = time();
$fp_sequence = $pend_order . time(); // Enter an invoice or other unique number.
$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id,
  $transaction_key, $amount, $fp_sequence, $fp_timestamp)
?>

<?php
if($opt['test_mode']=="1") { ?>
	<FORM action="https://test.authorize.net/gateway/transact.dll" method="POST" name="anetform">
<?php } else { 
	if(!empty($opt['pay_emulator'])) { ?>
	<FORM action="<?php print $opt['pay_emulator']; ?>" method="POST" name="anetform">
	<?php } else { ?>
	<FORM action="https://secure.authorize.net/gateway/transact.dll" method="POST" name="anetform">
	<?php } ?>
<?php } ?>

<INPUT type="hidden" name="x_relay_response" value="TRUE">
<INPUT TYPE=HIDDEN NAME="x_relay_url" VALUE="<?php  print $url.$setup['temp_url_folder']."/sy-inc/store/payment/authorize-net-sim-return.php";?>">
<INPUT type="hidden" name="x_receipt_link_method" value="LINK">
<INPUT type="hidden" name="x_receipt_link_text" value="Thank you. Click here to continue">
<INPUT type="hidden" name="x_receipt_link_url" value="<?php  print $url.$setup['temp_url_folder']."/?view=order&frompp=1&msos=".$_SESSION['ms_session']."&msok=$time_stamp"; ?>">
<INPUT type="hidden" name="x_show_form" value="PAYMENT_FORM">

<INPUT type="hidden" name="x_header_html_payment_form " value='<?php print $site_setup['website_title'];?>'>
<INPUT type="hidden" name="x_color_background" value='#<?php print $css['inside_bg'];?>'>
<INPUT type="hidden" name="x_color_link" value='#<?php print $css['link_color'];?>'>
<INPUT type="hidden" name="x_color_text" value='#<?php print $css['font_color'];?>'>
<INPUT type="hidden" name="x_cancel_url" value='#<?php  print $url.$setup['temp_url_folder']."/index.php?view=checkout";?>'>
<INPUT type="hidden" name="x_cancel_url_text" value='Cancel'>




<INPUT type="hidden" name="x_first_name" value="<?php  print htmlspecialchars($_REQUEST['first_name']);?>">
<INPUT type="hidden" name="x_last_name" value="<?php  print htmlspecialchars($_REQUEST['last_name']);?>">
<INPUT type="hidden" name="x_address" value="<?php  print htmlspecialchars($_REQUEST['address']);?>">
<INPUT type="hidden" name="x_city" value="<?php  print htmlspecialchars($_REQUEST['city']);?>">
<INPUT type="hidden" name="x_state" value="<?php  print htmlspecialchars($_REQUEST['state']);?>">
<INPUT type="hidden" name="x_zip" value="<?php  print htmlspecialchars($_REQUEST['zip']);?>">
<INPUT type="hidden" name="x_phone" value="<?php  print htmlspecialchars($_REQUEST['phone']);?>">
<INPUT type="hidden" name="x_email" value="<?php  print htmlspecialchars($_REQUEST['email_address']);?>">
<INPUT type="hidden" name="x_country" value="<?php  print htmlspecialchars($_REQUEST['country']);?>">

<input type='hidden' name="x_login" value="<?php echo $api_login_id?>" />
<input type='hidden' name="x_fp_hash" value="<?php echo $fingerprint?>" />
<input type='hidden' name="x_amount" value="<?php echo $amount?>" />
<input type='hidden' name="x_fp_timestamp" value="<?php echo $fp_timestamp?>" />
<input type='hidden' name="x_fp_sequence" value="<?php echo $fp_sequence?>" />
<input type='hidden' name="x_version" value="3.1">
<input type='hidden' name="x_test_request" value="false" />
<input type='hidden' name="x_method" value="CC">
<INPUT type="hidden" name="x_invoice_num" value="<?php  print $pend_order;?>">

<input type='submit' value="Click here for the secure payment form">
</form>



<?php  echo "<script>SubmitForm();</script>"; ?>