<?php
error_reporting(E_ALL ^ E_NOTICE);
if($included !== true) { 
	require "../../../sy-config.php";
	session_start();
	require $setup['path']."/".$setup['inc_folder']."/functions.php";
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
	$dbcon = dbConnect($setup);
	$store = doSQL("ms_store_settings", "*", "");
	$site_setup = doSQL("ms_settings", "*", "");

	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
	if(!empty($_SESSION['ms_lang'])) {
		$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
	} else {
		$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
	}
	foreach($lang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
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

$express = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalpro' ");
if($express['test_mode'] == "1") { 
	$PayPalMode         = 'sandbox'; // sandbox or live
}
$PayPalApiUsername  = trim($express['pay_email']); //PayPal API Username
$PayPalApiPassword  = trim($express['pay_num']); //Paypal API password
$PayPalApiSignature     = trim($express['pay_key']); //Paypal API Signature
$PayPalCurrencyCode     = $store['currency']; //Paypal Currency Code
$PayPalReturnURL    = $url."".$setup['temp_url_folder']."/sy-inc/store/payment/paypal-pro-express.php"; //Point to process.php page
$PayPalCancelURL    = $setup['url'].$setup['temp_url_folder']."/index.php?view=cart"; //Cancel URL if user clicks cancel
include_once("express/paypal.class.php");

if($start == true) {
    //Mainly we need 4 variables from an item, Item Name, Item Price, Item Number and Item Quantity.
    $ItemName = $site_setup['website_title']." Purchase"; //Item Name
    $ItemPrice = $_POST["grand_total"]; //Item Price
    $ItemNumber = ""; //Item Number
    $ItemQty = "1"; // Item Quantity
    $ItemTotalPrice = ($ItemPrice*$ItemQty); //(Item Price x Quantity = Total) Get total amount of product;
    //Data to be sent to paypal
    $padata =   '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&PAYMENTACTION=Sale'.
                '&ALLOWNOTE=0'.
                  '&SOLUTIONTYPE=Sole'.
	
              '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
                '&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
                '&NOSHIPPING=1'.
               '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
                '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
                '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
                '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
                '&AMT='.urlencode($ItemTotalPrice).
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL).
				 '&PAYMENTREQUEST_0_CUSTOM='.$pend_order;

        //We need to execute the "SetExpressCheckOut" method to obtain paypal token
        $paypal= new MyPayPal();
        $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        //Respond according to message we receive from Paypal
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {

                // If successful set some session variable we need later when user is redirected back to page from paypal.
                $_SESSION['itemprice'] =  $ItemPrice;
                $_SESSION['totalamount'] = $ItemTotalPrice;
                $_SESSION['itemName'] =  $ItemName;
                $_SESSION['itemNo'] =  $ItemNumber;
                $_SESSION['itemQTY'] =  $ItemQty;

                if($PayPalMode=='sandbox')
                {
                    $paypalmode     =   '.sandbox';
                }
                else
                {
                    $paypalmode     =   '';
                }
                //Redirect user to PayPal store with Token received.
                $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
                header('Location: '.$paypalurl);

        }else{
            //Show error message
            echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
            echo '<pre>';
            print_r($httpParsedResponseAr);
            echo '</pre>';
        }

}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_REQUEST["token"]) && isset($_REQUEST["PayerID"]))
{
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.
	$pending_order = doSQL("ms_pending_orders", "*", "WHERE  order_id='".$_SESSION['pending_order']."' ORDER BY order_id DESC");
    $token = $_REQUEST["token"];
    $payerid = $_REQUEST["PayerID"];

    //get session variables
    $ItemPrice      = $pending_order['order_total'];
    $ItemTotalPrice = $pending_order['order_total'];
    $ItemName       = $_SESSION['itemName'];
    $ItemNumber     = $_SESSION['itemNo'];
    $ItemQTY        = 1;


		$padata =   '&TOKEN='.urlencode($token).
							'&PAYERID='.urlencode($payerid).
							'&BUTTONSOURCE=Grissett_SP'.
							'&PAYMENTACTION='.urlencode("SALE").
							'&AMT='.urlencode( $ItemTotalPrice).
							'&CURRENCYCODE='.urlencode($PayPalCurrencyCode);

		//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

		//Check if everything went ok..
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
				//echo '<h2>Success</h2>';
				//echo 'Your Transaction ID :'.urldecode($httpParsedResponseAr["TRANSACTIONID"]);

					/*
					//Sometimes Payment are kept pending even when transaction is complete.
					//because of Currency change, user choose other payment option or its pending review etc.
					//hence we need to notify user about it and ask him manually approve the transiction
					*/

					if('Completed' == $httpParsedResponseAr["PAYMENTSTATUS"])
					{
						//echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
					}
					elseif('Pending' == $httpParsedResponseAr["PAYMENTSTATUS"])
					{
						//echo '<div style="color:red">Transaction Complete, but payment is still pending! You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
					}

				//echo '<br /><b>Stuff to store in database :</b><br /><pre>';

					$transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
					$nvpStr = "&TRANSACTIONID=".$transactionID;
					$paypal= new MyPayPal();
					$httpParsedResponseAr = $paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

					if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

						$pending_order = doSQL("ms_pending_orders", "*", "WHERE  order_id='".$_SESSION['pending_order']."' ORDER BY order_id DESC");
						if(!empty($pending_order['order_id'])) {
							$order_first_name=$pending_order['order_first_name'];
							$order_last_name=$pending_order['order_last_name'];
							$order_email=$pending_order['order_email'];
							$order_address=$pending_order['order_address'];
							$order_city=$pending_order['order_city'];
							$order_state=$pending_order['order_state'];
							$order_country=$pending_order['order_country'];
							$order_zip=$pending_order['order_zip'];
							if(!empty($pending_order['order_join_ml'])) {
								$order_join_ml=$pending_order['order_email'];
							}
							$order_phone=$pending_order['order_phone'];
							$order_company=$pending_order['order_company'];

							$order_ship_business=$pending_order['order_ship_business'];
							$order_ship_first_name=$pending_order['order_ship_first_name'];
							$order_ship_last_name=$pending_order['order_ship_last_name'];
							$order_ship_email=$pending_order['order_ship_email'];
							$order_ship_address=$pending_order['order_ship_address'];
							$order_ship_city=$pending_order['order_ship_city'];
							$order_ship_state=$pending_order['order_ship_state'];
							$order_ship_country=$pending_order['order_ship_country'];
							$order_ship_zip=$pending_order['order_ship_zip'];

							$order_ship_amount = $pending_order['order_shipping'];
							$order_tax = $pending_order['order_tax'];
							$order_discount = $pending_order['order_discount'];
							$order_sub_total = $pending_order['order_sub_total'];
							$order_shipping_option = $pending_order['order_shipping_option'];
							$order_coupon_id= $pending_order['order_coupon_id'];
							$order_coupon_name = $pending_order['order_coupon_name'];
							$order_vat = $pending_order['order_vat'];
							$order_vat_percentage = $pending_order['order_vat_percentage'];

							$order_tax_percentage = $pending_order['order_tax_percentage'];
							$order_taxable_amount= $pending_order['order_taxable_amount'];
							$order_eb_discount = $pending_order['order_eb_discount'];
							$order_key= $pending_order['order_key'];
							$customer_id= $pending_order['order_customer'];
							$credit_amount= $pending_order['order_credit'];
							$gift_certificate_amount= $pending_order['gift_certificate_amount'];
							$gift_certificate_id= $pending_order['gift_certificate_id'];
							$order_notes= $pending_order['order_notes'];

							$order_extra_field_1 = $pending_order['order_extra_field_1'];
							$order_extra_val_1 = $pending_order['order_extra_val_1'];
							$order_extra_field_2 = $pending_order['order_extra_field_2'];
							$order_extra_val_2 = $pending_order['order_extra_val_2'];
							$order_extra_field_3 = $pending_order['order_extra_field_3'];
							$order_extra_val_3 = $pending_order['order_extra_val_3'];
							$order_extra_field_4 = $pending_order['order_extra_field_4'];
							$order_extra_val_4 = $pending_order['order_extra_val_4'];
							$order_extra_field_5 = $pending_order['order_extra_field_5'];
							$order_extra_val_5 = $pending_order['order_extra_val_5'];


							$order_session= $pending_order['order_session'];
							deleteSQL("ms_pending_orders", "WHERE order_id='".$pending_order['order_id']."' ","1");

							$sub_total = $order_sub_total;
							$shipping_option = $order_shipping_option;
							$coupon_id = $order_coupon_id;
							$coupon_name = $order_coupon_name;
							$order_key=$order_key;
							$order_total_pay = $pending_order['order_total'];
							$payment_amount = $pending_order['order_total'];
							$order_fees = $payment_fees;
							$currency = $httpParsedResponseAr['CURRENCYCODE'];
							$transaction_id = $httpParsedResponseAr['TRANSACTIONID'];
							$order_ship_amount = $order_ship_amount;
							$order_tax = $order_tax;
							$tax_percentage = $order_tax_percentage;
							$taxable_amount= $order_taxable_amount;
							$vat = $order_vat;
							$vat_percentage = $order_vat_percentage;
							$order_discount = $order_discount;
							$order_message  = $_POST['customer_message'];
							$order_pay_type = "PayPal Express ";
							$order_payment_status = "Completed";
							$order_pending_reason = $_POST['pending_reason'];
							$pay_option = "paypalproexpress";
							$first_name = $order_first_name;
							$last_name = $order_last_name;
							$email_address = $order_email;
							$company_name = $order_company;
							$country = $order_country;
							$city = $order_city;
							$state = $order_state;
							$zip = $order_zip;
							$address = $order_address;
							$phone = $order_phone;
							$order_session = $order_session;
							$ship_business = $order_ship_business;
							$ship_first_name = $order_ship_first_name;
							$ship_last_name =  $order_ship_last_name;
							$ship_address  = $order_ship_address;
							$ship_city = $order_ship_city;
							$ship_state = $order_ship_state;
							$ship_zip = $order_ship_zip;
							$ship_country = $order_ship_country;
							$order_address_status = $_POST['address_status'];
							$order_payer_status = $_POST['payer_status'];
							
							$ip_address = $pending_order['order_ip'];
							// $no_redirect = true;
							if($pending_order['order_order_id'] > 0) { 
								$_REQUEST['order_id'] = $pending_order['order_order_id'];
								$order_id = $pending_order['order_order_id'];
								include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
							} else { 
								unset($_SESSION['pending_order']);
								include $setup['path']."/sy-inc/store/payment/payment-complete.php";
								createOrder();
							}
						}
					} else  {
						$_SESSION['decline_message'] = _checkout_card_declined_."(".urldecode($httpParsedResponseAr['L_LONGMESSAGE0']).") ";
						include $setup['path']."/sy-inc/store/payment/payment-declined.php";

						echo '<div style="color:red">A - <b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
						echo '<pre>';
						print_r($httpParsedResponseAr);
						echo '</pre>';

					}

		}else{
			$_SESSION['decline_message'] = _checkout_card_declined_."(".urldecode($httpParsedResponseAr['L_LONGMESSAGE0']).") ";
			include $setup['path']."/sy-inc/store/payment/payment-declined.php";

				echo '<div style="color:red">B - <b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				echo '<pre>';
				print_r($httpParsedResponseAr);
				echo '</pre>';
		}
	} else { 
	}
?>