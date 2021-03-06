  <?php
	/*******************************
	Run Card Code Snippet

		1. dc_logon is currently set for the test account (pj-ql-01) in this file. To run live transactions that go to your account, you must change the login to YOUR QuickLink login.
		2. dc_password is currently set for the test account (pj-ql-01p) in this file. To run live transactions that go to your account, you must change the password to YOUR QuickLink password.
		
	NOTE: 
		  If you are using the test login and password, you can check these transactions in the PayJunction test account.
		  To login to the PayJunction test account:
		  1. Go to http://demo.payjunction.com
		  2. Click on "Merchant Login" (top, right side of webpage)
		  3. Login: payjunctiondemo  Password: demo123
		  
		  To view your transactions, go to the History section of the account. Transactions will automatically "batch" every night. If the transactions were run today and have not batched, click on the "View Batch" button. If they have already batched, click on the "Details" link for the appropriate batch.
	*******************************/
	
	
	
	/****************************************************** 
	Configuration values 
	*******************************************************/
	
	$curlpath = "/usr/bin/curl";
	
	$payinfo = doSQL("ms_payment_options", "*", "WHERE pay_option='payjunction' ");


	$pj_login = $payinfo['pay_num'];
	$pj_password = $payinfo['pay_key'];

//  TEST INFO
//	$pj_login = "pj-ql-01";
//	$pj_password = "pj-ql-01p";

	
	if($payinfo['test_mode']== "1") { 
		 $server   = "https://www.payjunctionlabs.com/quick_link";
	} else {
		$server   = "https://payjunction.com/quick_link";
	}
	


	
	/****************************************************** 
	Setup keys and values
	*******************************************************/
	$post_array = array(
		"dc_logon" => "".$pj_login."",
		"dc_password" => "".$pj_password."",
		"dc_transaction_amount" => $_POST['grand_total'],
		"dc_first_name" => trim(stripslashes($_POST['first_name'])),
		"dc_last_name" => trim(stripslashes($_POST['last_name'])),
		"dc_number" =>  $_POST['creditcardpj'],
		"dc_expiration_month" => $_POST['monthpj'],
		"dc_expiration_year" => $_POST['yearpj'],	
		"dc_verification_number" => $_POST['cvvpj'],
		"dc_address" => $_POST['address'],
		"dc_city" => $_POST['city'],
		"dc_state" => $_POST['state'],
		"dc_zipcode" => $_POST['zip'],
		"dc_country" => $_POST['country'],
		"dc_transaction_type" => "AUTHORIZATION_CAPTURE",
		"dc_test" => "No",
		"dc_version" => "1.2"
	);

	/****************************************************** 
	Prepare the POST string to be sent to the PayJunction Server 
	*******************************************************/
	$request = "";
	foreach ($post_array as $key => $val) {
	  	$request .= $key . "=" . urlencode($val) . "&";
	}
	$request = substr($request, 0, -1);
	/****************************************************** 
	Connect and send POST string to PayJunction Server, store reponse in $content
	*****************************************************/
	if (@constant("CURLVERSION_NOW")) { // Use built in PHP methods if available
		$ch = curl_init($server);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  // uncomment if you are not receiving a response from the PayJunction Server.
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);
	} else { // Use shell_exec to execute curl directly.
		$content = shell_exec($curlpath . ' -s -d "' . $request . '" '.$server);
	}
	
	/******************************************** 
	Parse the response from PayJunction
	********************************************/
	$content = explode(chr (28), $content); // The ASCII field seperator character is the delimiter
	foreach ($content as $key_value) {
		list ($key, $value) = explode("=", $key_value);
		$response[$key] = $value;	}

	/******************************************** 
	Display the appropriate response
	********************************************/
	if (strcmp ($response['dc_response_code'], "00") == 0 || strcmp ($response['dc_response_code'], "85") == 0) { // 00 and 85 are transaction approved codes.
/*
	echo "<center><font color=red>Your payment was processed succesfully</font></center><br><br>";
		echo "Your Payment Details<br><br>";
		echo "Response Code: ".$response['dc_response_code']."<br>";
		echo "Name: ".$response['dc_card_name']."<br>";
		echo "Card Number: ".$response['dc_card_number']."<br>";
		echo "Card Address : ".$response['dc_card_address']."<br>";
		echo "City : ".$response['dc_card_city']."<br>";
		echo "Zip Code : ".$response['dc_card_zipcode']."<br>";
		echo "Total : ".$response['dc_base_amount']."<br>";

		print "<pre>";		print_r($response); print_r($_REQUEST);
*/
		$order_pay_amount =  $response['dc_base_amount'];
		$order_pay_ref =  $response['dc_transaction_id']; // transaction id
		$order_payment_type = $response['dc_card_brand']; // visa, mc 
		$order_id =  $_POST['order_number'];
		$order_name =  $response['dc_card_name']; 
//		$order_approve_code = $objSPCharge->approvNum;
//		include "payment.update.php";

		foreach($_POST AS $id => $value) {
			$_POST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_POST[$id] = sql_safe("".$_POST[$id]."");
		}
		$remote_host = @getHostByAddr(getUserIP());

		$all_data = "REPSONSE FROM PROCESSOR \r\n";
		foreach($response AS $id => $val) {	
			$all_data .= "$id: $val\r\n";
		}
		$all_data .= "\r\nREPSONSE FROM POST \r\n";
		foreach($_POST AS $id => $val) {	
			if(($id!=="creditcardpj")AND($id!=="monthpj")AND($id!=="yearpj")AND($id!=="cvvpj")==true) { 
				// $all_data .= "$id: $val\r\n";
			}
		}

		insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['grand_total']."', card='".$response['dc_card_number']."' , card_brand='".$response['dc_card_brand']."' , response_code='".$response['dc_response_code']."', response_message='".$response['dc_response_message']."', name='". addslashes(stripslashes($_POST['first_name']))." ". addslashes(stripslashes($_POST['last_name']))." ', email='".$_POST['email_address']."', order_number='".$_POST['order_number']."', country='".$_POST['country']."', all_data='". addslashes(stripslashes($all_data))."'  ");

			/* Payjunction response */
			$order_pay_type =  $_POST['c_typepj']; // paypal, credit cart
			$order_total_pay = $_POST['grand_total'];
			$payment_amount = $_POST['grand_total'];
			$payment_amout = $order_total_pay;
			$sub_total = $_REQUEST['sub_total'];
			$order_fees = "";
			$currency = "USD";
			$transaction_id = $response['dc_transaction_id'];
			$order_ship_amount = $_REQUEST['shipping_price'];
			$shipping_option = $_REQUEST['ship_select'];
			$order_tax = $_REQUEST['tax_price'];
			$tax_percentage = $_REQUEST['tax_percentage'];
			$taxable_amount = $_REQUEST['taxable_amount'];
			$order_discount = $_POST['discount_amount'];
			$order_message  = $_POST['customer_message'];
			$order_pay_type = $response['dc_card_brand'];
			$order_payment_status = "Completed";
			$order_pending_reason = $_POST['pending_reason'];
			$pay_option = "payjunction";
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email_address = $_POST['email_address'];
			$company_name = $_POST['business_name'];
			$country = $_POST['country'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			$address_status = $_POST['address_status'];
			$address = $_POST['address'];
			$phone = $_POST['order_phone'];
			$order_session = $_SESSION['ms_session'];
			$ship_business = $_POST['ship_business'];
			$ship_first_name = $_POST['ship_first_name'];
			$ship_last_name =  $_POST['ship_last_name'];
			$ship_address  = $_POST['ship_address'];
			$ship_city = $_POST['ship_city'];
			$ship_state = $_POST['ship_state'];
			$ship_zip = $_POST['ship_zip'];
			$ship_country = $_POST['ship_country'];
			$ip_address = getUserIP();
			$coupon_id = $_REQUEST['coupon_id'];
			$coupon_name = $_REQUEST['coupon_name'];
			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			$credit_amount = $_REQUEST['credit_amount'];
			$gift_certificate_amount = $_REQUEST['gift_certificate_amount'];
			$gift_certificate_id = $_REQUEST['gift_certificate_id'];
			$order_eb_discount = $_REQUEST['eb_amount'];
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

			if($_REQUEST['order_id'] > 0) { 
				$order_id = $_REQUEST['order_id'];
				include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
			} else { 
				include $setup['path']."/sy-inc/store/payment/payment-complete.php";
				createOrder();
			}

		exit();

	} else if ($response['dc_response_code']) { // Output the response code and message

//	$order= doSQL("pc_orders", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '%M %e, %Y ')  AS order_date", "WHERE order_id='".$_POST['order_number']."'" );

//	header("location: index.php?do=photocart&action=payment&error=".urlencode($response['dc_response_message'])."&order=".$order['order_key']." ");
//	exit();
$remote_host = @getHostByAddr(getUserIP());


		foreach($_POST AS $id => $value) {
			$_POST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_POST[$id] = sql_safe("".$_POST[$id]."");
		}

		$all_data = "REPSONSE FROM PROCESSOR \r\n";
		foreach($response AS $id => $val) {	
			$all_data .= "$id: $val\r\n";
		}
		$all_data .= "\r\nREPSONSE FROM POST \r\n";
		foreach($_POST AS $id => $val) {	
			if(($id!=="creditcard")AND($id!=="month")AND($id!=="year")AND($id!=="cvv")==true) { 
				$all_data .= "$id: $val\r\n";
			}
		}


		insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['amount']."', card='".$response['dc_card_number']."' , card_brand='".$response['dc_card_brand']."' , response_code='".$response['dc_response_code']."', response_message='".$response['dc_response_message']."', name='".$_POST['first_name']." ".$_POST['last_name']." ', email='".$_POST['email_address']."', declined='1', country='".$_POST['country']."', all_data='". addslashes(stripslashes($all_data))."'  ");

		$_SESSION['decline_message'] = _checkout_card_declined_."(".$response['dc_response_message'].") ";
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";

		print "<div class=\"errorMessage\">"._checkout_card_declined_." (".$response['dc_response_message'].") </div>";
		print "<div>&nbsp;</div>";
// 		echo "<center><font color=red>Your payment did not process successfully</font></center><br><br>";
//		echo "Transaction Details<br><br>";
//		echo "Response Code: ".$response['dc_response_code']."<br>";
//		echo "Response Message: ".$response['dc_response_message']."<br>";
	} else if ($curl_errno) {  // If curl had an error, output the error code and message.
		echo "<center><font color=red>Your payment did not process successfully</font></center><br><br>";
		$ermess .= "Transaction Details<br><br>";
		$ermess .= "Curl error code: $curl_errno <br>";
		$ermess .= "Curl message: $curl_error <br>";

		$_SESSION['decline_message'] = _checkout_card_declined_."(".$ermess.") ";
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";

	} else {
		echo "<center><font color=red>Your payment did not process successfully</font></center><br><br>";
		echo "Transaction Details<br><br>";
		echo "An unknown error has occured.<br>";
		$_SESSION['decline_message'] = _checkout_card_declined_."(Unknown error has occured) ";
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";
	}
?>


 </body>
</html>
