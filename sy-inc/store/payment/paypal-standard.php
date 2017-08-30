<script type="text/javascript">

function SubmitForm()
{
	document.myform.submit();
}

</script>

<?php
$opt = doSQL("ms_payment_options", "*", "WHERE pay_id='2' ");
$debugging = false; // true or false
$testmode = $opt['test_mode'];
	if($debugging) {
		echo "<pre>";
		echo "<b>Posted Data:</b><br><br>";
		print_r($_POST);
		echo "</pre>";

		echo "<b>Soap Request:</b><br>";
		// print_xml($soapData);
		echo "<pre>";
		echo "<b>Paypal Response:</b><br><br>";
		print_r($PaypalResponse);
		echo "</pre>";				
		print "<pre>"; print_r($_SESSION); print "</pre>";
	}

//	$_SESSION["OrderTotal"] = $_POST["paymentAmount"];
//	$_SESSION["OrderNumber"] = $_POST["InvoiceID"];
	
// print "<pre>"; print_r($_SESSION); print_r($_REQUEST);print_r($_POST); print "</pre>";

if(countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_ship='1' AND cart_order<='0' ") > 0) { 
	$ship = true;
}

$customer_ip = getUserIP();
if(!empty($_SESSION['last_gallery'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
	if($date['date_gallery_exclusive'] == "1") { 
		$ge_return_link = $setup['content_folder'].$date['cat_folder']."/".$date['date_link']."";
	}
}

if($testmode == "1") { 
	$paypal_email = "info-facilitator@tsge.net";
	print "<FORM ACTION=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\" name='myform'>";
} else {
	$paypal_email = $opt['pay_num'];
	print "<FORM ACTION=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" name='myform'>";
}
if($store['checkout_ssl'] == "1") { 
	if(!empty($store['checkout_ssl_link'])) { 
		$url =  $store['checkout_ssl_link'];
	} else { 
		$url = "https://".$_SERVER['HTTP_HOST'];
	}
} else { 
	$url = $setup['url'];
}

print "<INPUT TYPE=\"hidden\" NAME=\"custom\" VALUE=\"".$_SESSION['ms_session']."|".$time_stamp."|".$customer_ip."|".$_POST['join_ml']."|".MD5($pend_order)."\">";
print "<INPUT TYPE=\"hidden\" NAME=\"cancel_return\" VALUE=\"".$url.$setup['temp_url_folder'].$ge_return_link."/index.php?view=checkout\">";
print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"_ext-enter\">";
print "<INPUT TYPE=\"hidden\" NAME=\"redirect_cmd\" VALUE=\"_xclick\">";
print "<INPUT TYPE=\"hidden\" NAME=\"business\" VALUE=\"".$paypal_email."\">";
print "<INPUT TYPE=\"hidden\" NAME=\"bn\" VALUE=\"Grissett_SP\">";
print "<INPUT TYPE=\"hidden\" NAME=\"amount\" VALUE=\"". $_POST['grand_total']."\">";
if(!empty($store['currency'])) {
	print "<INPUT TYPE=\"hidden\" NAME=\"currency_code\" VALUE=\"".$store['currency']."\">";
}
print "<INPUT TYPE=\"hidden\" NAME=\"item_name\" VALUE=\"".$site_setup['website_title']." Purchase - ".htmlspecialchars(stripslashes($_POST['first_name']))." ".htmlspecialchars(stripslashes($_POST['last_name']))." - ".$_POST['email_address']."\">";
//			print "<INPUT TYPE=\"hidden\" NAME=\"item_number\" VALUE=\"10000\">";
print "<INPUT TYPE=\"hidden\" NAME=\"quantity\" VALUE=1>";

if($_SESSION['ms_client_id']<= 0) {
	$cart_session = $_SESSION['ms_session'];
} else {
	$cart_session =$_SESSION['ms_client_id'];
}

print "<input type=\"hidden\" name=\"notify_url\" value=\"".$url.$setup['temp_url_folder']."/sy-inc/store/payment/paypal-standard-return.php\">";
if($ship == true) { 
	print "<INPUT TYPE=\"hidden\" NAME=\"shipping\" VALUE=\"0.00\">";
} else { 
	print "<INPUT TYPE=\"hidden\" NAME=\"no_shipping\" VALUE=\"1\">";
}
print "<INPUT TYPE=\"hidden\" NAME=\"tax\" VALUE=\"0\">";
print "<input type=\"hidden\" name=\"return\" value=\"".$url.$setup['temp_url_folder'].$ge_return_link."/?view=order&frompp=1&msos=".$_SESSION['ms_session']."&msok=$time_stamp\">";
print "</form>";


?>

<div>&nbsp;</div>
<div>&nbsp;</div>

<?php 

	if($testmode == "1") { 
		echo "DEBUG MODE ENABLED, AUTOPOST DISABLED: <a href='#' onclick='SubmitForm();'>Click here to POST this page.</a>";
	} else { 
		print "<h1>Going to PayPal.com.......</h1><br> If you are not automatically redirected, <a href='#' onclick='SubmitForm();'>click here to continue.</a>.";
	}
?><div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

</div></div>

<div class="clear"></div>
</div>
<div class="clear"></div>
</div></div>

<?php echo "<script>SubmitForm();</script>"; ?>
