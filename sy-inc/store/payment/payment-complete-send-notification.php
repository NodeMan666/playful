<?php 
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$full_url = true;
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='$order_id' ");
// $payment = doSQL("ms_payments", "*", "WHERE pay_order='$order_id' ORDER BY pay_id DESC ");
if(($order_offline == "1")OR($order_offline == "2")OR($order_manual == "1") == true) { 
	$em = doSQL("ms_emails", "*", "WHERE email_id='7' ");
} else { 
	$em = doSQL("ms_emails", "*", "WHERE email_id='6' ");
	updateSQL("ms_orders", "order_payment_date='".date('Y-m-d')."' WHERE order_id='".$order['order_id']."' ");
}
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

$message = str_replace("[PAYMENT_AMOUNT]","".$order['order_payment']."", "$message");
$message = str_replace("[ORDER_NUMBER]",$order['order_id'], "$message");
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

$subject = str_replace("[PAYMENT_AMOUNT]","".$order['order_payment']."", "$subject");
$subject = str_replace("[ORDER_NUMBER]",$order['order_id'], "$subject");
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

if(empty($order['order_shipping_option'])) { 
	$message = str_replace('id="ship1"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
	$message = str_replace('id="ship2"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
	$message = str_replace('id="ship3"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
	$message = str_replace('id="ship4"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
}

stripslashes($message);
stripslashes($subject);

$bookcart = countIt("ms_cart", "WHERE cart_order='".$order['order_id']."' AND cart_booking>'0' ");
$totalcart = countIt("ms_cart", "WHERE cart_order='".$order['order_id']."'  ");

if($bookcart > 0) { 
	if($order['order_invoice'] !== "1") { 
		$bcs = whileSQL("ms_cart LEFT JOIN ms_bookings ON ms_cart.cart_booking=ms_bookings.book_id LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show ", "WHERE cart_order='".$order['order_id']."' AND cart_booking>'0' ORDER BY cart_id ASC ");
		while($bc = mysqli_fetch_array($bcs)) { 
			if($bc['book_auto_confirm'] == "1") { 
				updateSQL("ms_bookings", "book_confirmed='2', book_first_name='".addslashes(stripslashes($order['order_first_name']))."', book_last_name='".addslashes(stripslashes($order['order_last_name']))."', book_email='".addslashes(stripslashes($order['order_email']))."', book_phone='".addslashes(stripslashes($order['order_phone']))."', book_order_id='".$order['order_id']."', book_account='".$order['order_customer']."' WHERE book_id='".$bc['book_id']."' ");
				bookingemail($bc['book_id'],showPrice($order_total_pay),"Yes");
				$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_id='".$bc['book_id']."' ");
				bookingconfirmemail($book);

			} else { 
				updateSQL("ms_bookings", "book_confirmed='1', book_first_name='".addslashes(stripslashes($order['order_first_name']))."', book_last_name='".addslashes(stripslashes($order['order_last_name']))."', book_email='".addslashes(stripslashes($order['order_email']))."', book_phone='".addslashes(stripslashes($order['order_phone']))."', book_order_id='".$order['order_id']."', book_account='".$order['order_customer']."' WHERE book_id='".$bc['book_id']."' ");
				bookingemail($bc['book_id'],showPrice($order_total_pay),"No");
			}
		}
	}
}
if(($order['order_invoice'] == "1") && ($order['order_booking_confirm'] == "1") == true) { 
	$bcs = whileSQL("ms_cart LEFT JOIN ms_bookings ON ms_cart.cart_booking=ms_bookings.book_id LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show ", "WHERE cart_order='".$order['order_id']."' AND cart_booking>'0' ORDER BY cart_id ASC ");
	while($bc = mysqli_fetch_array($bcs)) { 
		if($bc['book_confirmed'] == "1") { 
			bookingconfirmemail($bc);
			updateSQL("ms_bookings", "book_confirmed='2' WHERE book_id='".$bc['book_id']."' ");
		}
	}
} 


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

if($order_offline == "1") { 
	$subject = "".$site_setup['website_title']." - New Pending Order  ".showPrice($order_total_pay)." - #".$order_id."  ";
} else { 
	$subject = "".$site_setup['website_title']." - New Order  ".showPrice($order_total_pay)." - #".$order_id."  ";
}
$to_email = $site_setup['contact_email'];
$to_name = $site_setup['contact_email'];
$from_email = $site_setup['contact_email'];
$from_name = $site_setup['website_title'];
$admin_message = "You have a new order! You can also view this order in your admin:<br>".$setup['url']."".$setup['temp_url_folder']."/".$setup['manage_folder']."/ <br><br>";
$admin_message .= "$add_email";
$admin_message .= "<b>Below is the order details and the email that was sent to the customer.</b><br>----------------------------------------------------------------------------------------------------------<br><br>";

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