<script type="text/javascript">
function SubmitForm() {
	document.anetform.submit();
}
</script>


<?php
//error_reporting(E_ALL & ~E_NOTICE);


require_once 'apco/includes/configs.php'; // Include the SDK you downloaded in Step 2

$opt = doSQL("ms_payment_options","*", "WHERE pay_option='apco' ");
if($store['checkout_ssl'] == "1") { 
	if(!empty($store['checkout_ssl_link'])) { 
		$url =  $store['checkout_ssl_link'];
	} else { 
		$url = "https://".$_SERVER['HTTP_HOST'];
	}
} else { 
	$url = $setup['url'];
}



$api_login_id = $opt['pay_num'];
$transaction_key = $opt['pay_key'];
$amount = $_POST['grand_total'];


$profileID = "641028BF1C1B403B9FE3AA83B2328DF0";
$secretWord = "db2782564c";
$merchantCode = "8192";
$merchantPassword = "pisullip";

?>

<h1>One moment please</h1>
<form name="anetform" method="post" action="<?php print $url;?>/sy-inc/store/payment/apco/callFastPay.php">
<input type="hidden" class="field" name="hasTestCard" value="true"/>


<input type="hidden" class="field" name="profileID" value="<?php echo $profileID; ?>"/>
<input type="hidden" class="field" name="secretWord" value="<?php echo $secretWord; ?>"/>
<input type="hidden" class="field" name="amount" value="<?php echo $amount?>"/>
<input type="hidden" class="field" name="language" value="en"/>
<input type="hidden" class="field" name="currency" value="978"/>


<?php // pass session and timestamp as Order Reference
?>
<input type="hidden" class="field" name="orderReference" value="<?php echo $_SESSION['ms_session'] . "-" . $time_stamp;?>"/> 

<input type="hidden" class="field" name="hasPspID" value="true"/>
<input type="hidden" class="field" name="PspID" value="<?php  print $pend_order;?>"/>


<input type="hidden" class="field" name="udf1" value="<?php  print $pend_order;?>"/>

<input type="hidden" class="field" name="udf2" value="UDF 2"/>
<input type="hidden" class="field" name="udf3" value="UDF 3"/>
<input type="hidden" class="field" name="hasCSSTemplate" value="true"/>
<input type="hidden" class="field" name="CSSTemplate" value="bdlbooks"/>

<?php // send to apco-return.php if successful transaction
?>
<input type="hidden" class="field" name="redirectionURL" value="<?php  print $url."/sy-inc/store/payment/apco-return.php" ; ?>"/>

<input type="hidden" class="field" name="hasNoRetry" value="true"/>
<input type="hidden" class="field" name="hasFailedRedirectionURL" value="true"/>
<input type="hidden" class="field" name="failedRedirectionURL" value="<?php  print $url."/sy-inc/store/payment/apco-return.php" ; ?>"/>
 
<input type="hidden" class="field" name="hasStatusURL" value="true"/>
<input type="hidden" class="field" name="statusURL" value="<?php  print $url."/sy-inc/store/payment/apco/listener.php"; ?>"/>

<input type="hidden" class="field" name="actionType" value="1"/>
<INPUT type="hidden" name="hasAddress" value="true">
<INPUT type="hidden" name="address" value="<?php  print htmlspecialchars($_REQUEST['address']);?>">

<INPUT type="hidden" name="hasEmail" value="true">
<INPUT type="hidden" name="email" value="<?php  print htmlspecialchars($_REQUEST['email_address']);?>">
<!-- <INPUT type="hidden" name="hasCountry" value="true">
<INPUT type="hidden" name="country" value="<?php  print htmlspecialchars($_REQUEST['country']);?>">
-->


<!-- <input type='submit' value="Click here for the secure payment form"> -->
</form>



<?php  echo "<script>SubmitForm();</script>"; ?>