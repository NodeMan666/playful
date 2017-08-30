<script type="text/javascript">
function SubmitForm() {
	document.anetform.submit();
}
</script>


<?php
//error_reporting(E_ALL & ~E_NOTICE);
/*
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
*/

### TESTING AMOUNT ####
$amount = 10;
$site_setup['website_title'] = "Test Site";
$pend_order = "123456";
$pass = "TEST_PASS";
$url = "http://dev.sytist.com";

$amount = $amount * 100;
	$ll .= str_pad(strlen($amount), 3, '0', STR_PAD_LEFT);
$merchant_name = "EUROEVENTS DOOEL";
	$ll .= str_pad(strlen($merchant_name), 3, '0', STR_PAD_LEFT);
$merchant_id = "1000000807";
	$ll .= str_pad(strlen($merchant_id), 3, '0', STR_PAD_LEFT);
$currency = "MKD";
	$ll .= str_pad(strlen($currency), 3, '0', STR_PAD_LEFT);
$details_one = $site_setup['website_title'];
	$ll .= str_pad(strlen($details_one), 3, '0', STR_PAD_LEFT);
$details_two = $pend_order; // Pending order number from the database
	$ll .= str_pad(strlen($details_two), 3, '0', STR_PAD_LEFT);
$payment_ok_url = $url."/sy-inc/store/payment/cpaymk-return.php";
	$ll .= str_pad(strlen($payment_ok_url), 3, '0', STR_PAD_LEFT);
$payment_fail_url = $url."/sy-inc/store/payment/cpaymk-return.php";
	$ll .= str_pad(strlen($payment_fail_url), 3, '0', STR_PAD_LEFT);
$first_name = "Dejan";
	$ll .= str_pad(strlen($first_name), 3, '0', STR_PAD_LEFT);
$last_name = "Pavlovic" ;
	$ll .= str_pad(strlen($last_name), 3, '0', STR_PAD_LEFT);
$address = "Orce Nikolov 71";
	$ll .= str_pad(strlen($address), 3, '0', STR_PAD_LEFT);
$city = "Skopje";
	$ll .= str_pad(strlen($city), 3, '0', STR_PAD_LEFT);
$zip = "1000";
	$ll .= str_pad(strlen($zip), 3, '0', STR_PAD_LEFT);
$country = "Macedonia";
	$ll .= str_pad(strlen($country), 3, '0', STR_PAD_LEFT);
$phone = "3892125495"; // alphanumeric only 
	$ll .= str_pad(strlen($phone), 3, '0', STR_PAD_LEFT);
$email = "filmfest@mol.com.mk";
	$ll .= str_pad(strlen($email), 3, '0', STR_PAD_LEFT);
/*
$original_amount = $amount;
	$ll .= str_pad(strlen($original_amount), 3, '0', STR_PAD_LEFT);
$original_currency = "EUR";
	$ll .= str_pad(strlen($original_currency), 3, '0', STR_PAD_LEFT);
*/
print $ll;
$total_fields = 16;

$checkSumHeader = $total_fields."AmountToPay,PayToMerchant,MerchantName,AmountCurrency,Details1,Details2,PaymentOKURL,PaymentFailURL,FirstName,LastName,Address,City,Zip,Country,Telephone,Email,".$ll;
$checkSum = $checkSumHeader.$amount.$merchant_name.$merchant_id.$currency.$details_one.$details_two.$payment_ok_url.$payment_fail_url.$first_name.$last_name.$address.$city.$zip.$country.$phone.$email.$pass;
?>

<h1>One moment please</h1>


<!-- LIVE -->
<!-- <form action='https://www.cpay.com.mk/client/Page/default.aspx?xml_id=/mk-MK/.loginToPay/' method='post'> --> 

<!-- TEST MODE-->
<form action='https://www.cpay.com.mk/Client/page/default.aspx?xml_id=/mk-MK/.TestLoginToPay/' method='post'>


<input id='AmountToPay' name='AmountToPay' value='<?php echo $amount; ?>' type='text' /><!-- Amount to pay multiplied by 100 - without decimal and separators, i.e. amount 1,00 will be value 100-->
<input id='PayToMerchant' name='PayToMerchant' value='<?php print $merchant_id;?>' type='text' />		<!-- DO NOT CHANGE! Merchant ID - assigned manually for the time being -->
<input id='MerchantName' name='MerchantName' value='<?php print htmlspecialchars($merchant_name);?>' type='text' /> <!-- DO NOT CHANGE! Merchant name -->
<input id='AmountCurrency' name='AmountCurrency' value='<?php print $currency;?>' type='text' /><!-- DO NOT CHANGE! Currency of payment -->
<input id='Details1' name='Details1' value='<?php print htmlspecialchars($details_one);?>' type='text' /><!--Userfriendly details of payment-->
<input id='Details2' name='Details2' value='<?php  print $details_two;?>' type='text' /><!-- Merchant specific details (OrderID - unique for each payment)-->
<input id='PaymentOKURL' size='80' name='PaymentOKURL' value='<?php  print $payment_ok_url; ?>' type='text' /><!-- Merchant PaymentSentOK redirect URL-->
<input id='PaymentFailURL' size='80' name='PaymentFailURL' value='<?php  print $payment_fail_url; ?>' type='text' /><!-- Merchant PaymentFailed redirect URL-->
<!-- <input id='CheckSumHeader' name='CheckSumHeader' value='18AmountToPay,PayToMerchant,MerchantName,AmountCurrency,Details1,Details2,PaymentOKURL,PaymentFailURL,FirstName,LastName,Address,City,Zip,Country,Telephone,Email,OriginalAmount,OriginalCurrency,004010016003012009024026005008015006004009014019003003' type='hidden' />
-->
<div><?php print $checkSumHeader;?></div>
<div><?php print $checkSum;?></div>
<input id='CheckSumHeader' name='CheckSumHeader' value='<?php print $checkSumHeader;?>' type='text' />





<!-- CheckSum header should contain : 
1. the number of parameters sent in Redirect Code. The number should be represented as a 2-digit field, right-justified, padded left with zeros.
2. a comma-separated list of parameters names. There also have to be a comma after the last parameter name.
3. a list of lenghts of parameters VALUEs. Each lenght should be represented as a 3-digit field, right-justified, padded left with zeros.
NOTE ! In the CheckSumHeader should be listed ONLY parameters which have values, 
i.e. if there is no value for some parameter, it should NOT be included in the CheckSumHeader.
And vice versa - if a value is sent for a parameter, which is not listed in the CheckSumHeader, it is considered as a fraud
and an error is raised
NOTE !! It does not matter the order in which parameters are listed in the CheckSumHeader,
it only matters that the VALUES of the parameters in the Input string (for calculating the checksum) are included in the order EXACTLY the same as parameters names in the CheckSumHeader
<input id='CheckSum' name='CheckSum' value='862A2BAA85600539DD1F3D43E9FDB28D' type='hidden' />
-->


<input id='CheckSum' name='CheckSum' value='<?php print MD5($checkSum);?>' type='text' />


<!-- CheckSum should be generated according to the algorithm explained in the specification.
For the generation of the sample CheckSum here is used the test merchant password - TEST_PASS -->

<input id='FirstName' size='80' name='FirstName' value='<?php print htmlspecialchars($first_name);?>' type='text' /><!-- Customer's first name (Optional) -->
<input id='LastName' size='80' name='LastName' value='<?php print htmlspecialchars($last_name);?>' type='text' /><!-- Customer's last name (Optional) -->
<input id='Address' size='80' name='Address' value='<?php print htmlspecialchars($address);?>' type='text' /><!-- Customer's billing address (Optional) -->
<input id='City' size='80' name='City' value='<?php print htmlspecialchars($city);?>' type='text' /><!-- Customer's city (Optional) -->
<input id='Zip' size='80' name='Zip' value='<?php print htmlspecialchars($zip);?>' type='text' /><!-- Customer's Zip (Optional) -->
<input id='Country' size='80' name='Country' value='<?php print htmlspecialchars($country);?>' type='text' /><!-- Customer's country (Optional) -->
<input id='Telephone' size='80' name='Telephone' value='<?php print htmlspecialchars($phone);?>' type='text' /><!-- Customer's phone number (Optional) -->
<input id='Email' size='80' name='Email' value='<?php print htmlspecialchars($email);?>' type='text' /><!-- Customer's email (Optional) -->
 

<input class='button' value='Pay' type='submit' /> 
</form>





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



<?php // echo "<script>SubmitForm();</script>"; ?>