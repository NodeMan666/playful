<?php 
$lang = doSQL("ms_language", "*", "");
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
$full_url = true;
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='$order_id' ");
// $payment = doSQL("ms_payments", "*", "WHERE pay_order='$order_id' ORDER BY pay_id DESC ");

$em = doSQL("ms_emails", "*", "WHERE email_id_name='scheduledpayment' ");

$order_id = $order['order_id'];

if(empty($em['email_from_email'])) {
	$from_email = $site_setup['contact_email'];
} else {
	$from_email = $em['email_from_email'];
}

if(empty($em['email_from_name'])) {
	$from_name = $site_setup['website_title'];
} else {
	$from_name = $em['email_from_name'];
}
$subject = "".$em['email_subject']."";

$to_email = $order['order_email'];
$to_name = $order['order_first_name']." ".$order['order_last_name'];
$message = $em['email_message'];

$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");

$message = str_replace("[PAYMENT_AMOUNT]","".showPrice($payment_amount)."", "$message");
$message = str_replace("[ORDER_NUMBER]",$order['order_id'], "$message");
$message = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$message");
$message = str_replace("[FIRST_NAME]",$order['order_first_name'], "$message");
$message = str_replace("[LAST_NAME]",$order['order_last_name'], "$message");
$message = str_replace("[EMAIL_ADDRESS]",$order['order_email'], "$message");
$message = str_replace("[ADDRESS]",$order['order_address'], "$message");
$message = str_replace("[CITY]",$order['order_city'], "$message");
$message = str_replace("[STATE]",$order['order_state'], "$message");
$message = str_replace("[ZIP]",$order['order_zip'], "$message");
$message = str_replace("[PHONE]",$order['order_phone'], "$message");
$message = str_replace("[COUNTRY]",$order['order_country'], "$message");
	$message = str_replace("[COMPANY_NAME]",$order['order_business_name'], "$message");

if(empty($order['order_shipping_option'])) { 
	$message = str_replace("[SHIP_FIRST_NAME]","N/A", "$message");
	$message = str_replace("[SHIP_LAST_NAME]","", "$message");
	$message = str_replace("[SHIP_ADDRESS]","", "$message");
	$message = str_replace("[SHIP_CITY]","", "$message");
	$message = str_replace("[SHIP_STATE]","", "$message");
	$message = str_replace("[SHIP_ZIP]","", "$message");
	$message = str_replace("[SHIP_COUNTRY]","", "$message");
} else { 
	$message = str_replace("[SHIP_COMPANY_NAME]",$order['order_ship_business'], "$message");
	$message = str_replace("[SHIP_FIRST_NAME]",$order['order_ship_first_name'], "$message");
	$message = str_replace("[SHIP_LAST_NAME]",$order['order_ship_last_name'], "$message");
	$message = str_replace("[SHIP_ADDRESS]",$order['order_ship_address'], "$message");
	$message = str_replace("[SHIP_CITY]",$order['order_ship_city'], "$message");
	$message = str_replace("[SHIP_STATE]",$order['order_ship_state'], "$message");
	$message = str_replace("[SHIP_ZIP]",$order['order_ship_zip'], "$message");
	$message = str_replace("[SHIP_COUNTRY]",$order['order_ship_country'], "$message");
}	

$message = str_replace("[ORDER_TOTAL]","".showPrice($order_total_pay)."", "$message");
$message = str_replace("[ACCOUNT_LINK]","".$setup['url'].$setup['temp_url_folder']."?view=account", "$message");
$message = str_replace("[ORDER_DATE]",$order['order_date'], "$message");
$message = str_replace("[ORDER_ITEMS]",getOrderItems($order), "$message");
$message = str_replace("[TOTAL_ITEMS]",$total_items, "$message");
$message = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$message");
$message = str_replace("[ORDER_LINK]",$setup['url']."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=order&myorder=".$order_id."", "$message");

if($ship_email > 0) { 
	$message = str_replace("[SHIPPING_DESCRIPTION]",$em['email_shipping_descr'], "$message");
} else { 
	$message = str_replace("[SHIPPING_DESCRIPTION]","", "$message");
}
if($download_email > 0) { 
	$message = str_replace("[DOWNLOAD_DESCRIPTION]",$em['email_download_descr'], "$message");
} else { 
	$message = str_replace("[DOWNLOAD_DESCRIPTION]","", "$message");
}


if($order_offline == "1") { 
	$opt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline' ");
	$message = str_replace("[ORDER_PENDING_MESSAGE]",nl2br($opt['pay_offline_descr']), "$message");
} elseif($order_offline == "2") { 
	$opt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline2' ");
	$message = str_replace("[ORDER_PENDING_MESSAGE]",nl2br($opt['pay_offline_descr']), "$message");

} elseif($order_manual == "1") { 
	$opt = doSQL("ms_payment_options", "*", "WHERE pay_option='emailform' ");
	$message = str_replace("[ORDER_PENDING_MESSAGE]",nl2br($opt['pay_offline_descr']), "$message");
} else { 
	$message = str_replace("[ORDER_OFFLINE_PENDING]","", "$message");
}

$message = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$message");


$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
$subject = str_replace("[COMPANY_NAME]",$order['order_business_name'], "$subject");

$subject = str_replace("[PAYMENT_AMOUNT]","".showPrice($payment_amount)."", "$subject");
$subject = str_replace("[ORDER_NUMBER]",$order['order_id'], "$subject");
$subject = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$subject");
$subject = str_replace("[FIRST_NAME]",$order['order_first_name'], "$subject");
$subject = str_replace("[LAST_NAME]",$order['order_last_name'], "$subject");
$subject = str_replace("[EMAIL_ADDRESS]",$order['order_email'], "$subject");
$subject = str_replace("[ADDRESS]",$order['order_address'], "$subject");
$subject = str_replace("[CITY]",$order['order_city'], "$subject");
$subject = str_replace("[STATE]",$order['order_state'], "$subject");
$subject = str_replace("[ZIP]",$order['order_zip'], "$subject");
$subject = str_replace("[COUNTRY]",$order['order_country'], "$subject");

if(empty($order['order_shipping_option'])) { 
	$subject = str_replace("[SHIP_FIRST_NAME]","N/A", "$subject");
	$subject = str_replace("[SHIP_LAST_NAME]","", "$subject");
	$subject = str_replace("[SHIP_ADDRESS]","", "$subject");
	$subject = str_replace("[SHIP_CITY]","", "$subject");
	$subject = str_replace("[SHIP_STATE]","", "$subject");
	$subject = str_replace("[SHIP_ZIP]","", "$subject");
	$subject = str_replace("[SHIP_COUNTRY]","", "$subject");
} else { 
	$subject = str_replace("[SHIP_FIRST_NAME]",$order['order_ship_first_name'], "$subject");
	$subject = str_replace("[SHIP_LAST_NAME]",$order['order_ship_last_name'], "$subject");
	$subject = str_replace("[SHIP_ADDRESS]",$order['order_ship_address'], "$subject");
	$subject = str_replace("[SHIP_CITY]",$order['order_ship_city'], "$subject");
	$subject = str_replace("[SHIP_STATE]",$order['order_ship_state'], "$subject");
	$subject = str_replace("[SHIP_ZIP]",$order['order_ship_zip'], "$subject");
	$subject = str_replace("[SHIP_COUNTRY]",$order['order_ship_country'], "$subject");
}	

$subject = str_replace("[ORDER_TOTAL]","".showPrice($order_total_pay)."", "$subject");
$subject = str_replace("[ACCOUNT_LINK]","".$setup['url'].$setup['temp_url_folder']."?view=account", "$subject");
$subject = str_replace("[ORDER_DATE]",$order['order_date'], "$subject");
$subject = str_replace("[TOTAL_ITEMS]",$total_items, "$subject");
$subject = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$subject");



$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");



	while($sc = mysqli_fetch_array($scs)) { 
		$add .= "<p>";
		if($sc['payment'] > 0) { 
			$add .= showPrice($sc['payment']) ." "._paid_." ".$sc['payment_date_show'];
		} else { 
			$add .= showPrice($sc['amount'])." "._due_." ".$sc['due_date_show'];
		}
		$add.= "</p>";
	 }

$message = str_replace("[SCHEDULED_PAYMENTS]",$add, "$message");
$message = str_replace("[PAYMENT_AMOUNT]", showPrice($payment_amount), "$message");


sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");
	// ADD THIS EMAIL NOTICATION!!!!!!!!!!!!!!!!!!!!!

if($order_manual == "1") { 
	$cl = strlen($_REQUEST['creditcard']);
	$half1 = round($cl /2);
	$half2 = $cl - $half1;

	$c1 = substr($_REQUEST['creditcard'], 0,  $half1);
	$c2 = substr($_REQUEST['creditcard'], -$half2, $half2);
	for ($c = 0; $c < $half1; $c++) {
	$xs = "$xs"."X";
	}
	$add_email .= "############################################################<br>";
	$add_email .= "<b>Manual process payment option used</b><br>Credit card half: $c1"."$xs <br>";
	$add_email .= "CVV: ".$_REQUEST['cvv']."<br>";
	$add_email .= "The rest of the payment information is in the admin with the order<br>";
	$add_email .= "############################################################<br><br>";
	$dbCnotes .= "credit card half: $xs"."$c2\r\n";
	$dbCnotes .= "Expiration: ".$_REQUEST['month']."/".$_REQUEST['year']."\r\n";
	$dbCnotes .= "Card type: ".$_REQUEST['card_type']."\r\n\r\n";
	$dbCnotes .= "The rest of the payment information is in the order email sent to you.";
	updateSQL("ms_orders", "order_payment_info='$dbCnotes' WHERE order_id='$order_id' ");
}


$subject = "".$site_setup['website_title']." - Payment of  ".showPrice($payment_amount)." made on invoice #".$order_id."  ";
$to_email = $site_setup['contact_email'];
$to_name = $site_setup['contact_email'];
$from_email = $site_setup['contact_email'];
$from_name = $site_setup['website_title'];
$admin_message = "A payment has been made in the amount of ".showPrice($payment_amount)." on invoice ".$order_id.", ".$order['order_first_name']." ".$order['order_last_name'].". You can also view this invoice  in your admin:<br>".$setup['url']."".$setup['temp_url_folder']."/".$setup['manage_folder']."/ <br><br>";
$admin_message .= "$add_email";
$admin_message .= "<b>Below is the email that was sent to the client.</b><br>----------------------------------------------------------------------------------------------------------<br><br>";

$admin_message .= "$message";
stripslashes($admin_message);
stripslashes($subject);

$ems = trim($site_setup['order_emails']);
if(!empty($ems)) { 
	$emsto = explode(";",$ems);
	foreach($emsto AS $to_email) { 
		$to_email = trim($to_email);
		if(!empty($to_email)) { 
			sendWebdEmail($to_email, $to_email, $from_email, $from_name, $subject, $admin_message,"1");
		}
	}
} else { 
	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $admin_message,"1");
}

unset($subject);
unset($to_email);
unset($from_email);

?>