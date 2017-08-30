<script type="text/javascript">
function SubmitForm() {
	document.wpf.submit();
}
</script>


<?php

$opt = doSQL("ms_payment_options","*", "WHERE pay_option='worldpay' ");
if($store['checkout_ssl'] == "1") { 
	if(!empty($store['checkout_ssl_link'])) { 
		$url =  $store['checkout_ssl_link'];
	} else { 
		$url = "https://".$_SERVER['HTTP_HOST'];
	}
} else { 
	$url = $setup['url'];
}
$amount = $_POST['grand_total'];

?>
<?php if($opt['test_mode'] == "1") { ?>
<form action="https://secure-test.worldpay.com/wcc/purchase" method="POST" name="wpf">
<input type=hidden name="testMode" value="100">
<?php } else { ?>
<form action="https://secure.worldpay.com/wcc/purchase" method="POST" name="wpf">
<?php } ?>

<!-- This next line contains a mandatory parameter. Put your Installation ID inside the quotes after value= -->
<input type=hidden name="instId" value="<?php print $opt['pay_num'];?>">

<input type="hidden" name="successURL" value="<?php  print $url.$setup['temp_url_folder']."/?view=order&frompp=1&msos=".$_SESSION['ms_session']."&msok=$time_stamp"; ?>">
<input type="hidden" name="failureURL" value="<?php  print $url.$setup['temp_url_folder']."/?view=checkout&status=declined"; ?>">
<input type="hidden" name="pendingURL" value="<?php  print $url.$setup['temp_url_folder']."/?view=order&frompp=1&msos=".$_SESSION['ms_session']."&msok=$time_stamp"; ?>">

<input type="hidden" name="MC_callback" value="<?php  print $url.$setup['temp_url_folder']."/sy-inc/store/payment/worldpay-return.php";?>">
<!-- Another mandatory parameter. Put your own reference identifier for the item purchased inside the quotes after value= -->
<input type=hidden name="cartId" value="<?php  print $pend_order;?>">
<!-- Another mandatory parameter. Put the total cost of the item inside the quotes after value= -->
<input type=hidden name="amount" value="<?php echo $amount?>">

<!-- Another mandatory parameter. Put the code for the purchase currency inside the quotes after value= -->
<?php if(empty($site_setup['currency_code'])) { $site_setup['currency_code'] = "GBP"; } ?>
<input type=hidden name="currency" value="<?php print $site_setup['currency_code'];?>">
<input type=hidden name="desc" value="<?php print "".$site_setup['website_title']." Purchase - ".htmlspecialchars(stripslashes($_POST['first_name']))." ".htmlspecialchars(stripslashes($_POST['last_name']))." - ".$_POST['email_address'].""; ?>">



<input type=hidden name="name" value="<?php  print htmlspecialchars($_REQUEST['first_name']);?> <?php  print htmlspecialchars($_REQUEST['last_name']);?>">
<input type=hidden name="address" value="<?php  print htmlspecialchars($_REQUEST['address']);?>">
<input type=hidden name="postcode" value="<?php  print htmlspecialchars($_REQUEST['zip']);?>">
<?php $ct = doSQL("ms_countries", "*", "WHERE country_name='".$_REQUEST['country']."' "); ?>
<input type=hidden name="country" value="<?php  print htmlspecialchars($ct['abr']);?>">
<input type=hidden name="tel" value="<?php  print htmlspecialchars($_REQUEST['phone']);?>">
<input type=hidden name="email" value="<?php  print htmlspecialchars($_REQUEST['email_address']);?>">
<input type="hidden" name="return" value="<?php  print $url.$setup['temp_url_folder']."/?view=checkout"; ?>">
<center>
<div class="center">If you are not automatically redirected, click the continue button below</div>
<div class="center">
<?php
if(!empty($opt['pay_button'])) {
	print "<input type=\"image\" border=\"0\"  src=\"".$opt['pay_button']."\" title=\"".$opt['pay_text']."\">";
} else {
	print "<input type=\"submit\" border=\"0\" value=\"".$opt['pay_text']."\" class=submit>";
}
?>
</div>
</center>
</form>
<?php echo "<script>SubmitForm();</script>"; ?>