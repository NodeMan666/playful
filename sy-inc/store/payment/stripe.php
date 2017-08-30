<?php
 require_once 'anet/AuthorizeNet.php'; // Make sure this path is correct.
require_once("stripe/lib/Stripe.php");
$payinfo = doSQL("ms_payment_options", "*", "WHERE pay_option='stripe' ");


if (!isset($_POST['stripeToken'])) { die('Missing Token'); } 
// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://manage.stripe.com/account
Stripe::setApiKey("".trim($payinfo['pay_key'])."");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

// Create the charge on Stripe's servers - this will charge the user's card
try {
$charge = Stripe_Charge::create(array(
  "amount" => $_POST['grand_total']*100, // amount in cents, again
  "currency" => "".$store['currency']."",
  "card" => $token,
  "description" => "".$_POST['first_name']." ".$_POST['last_name']." ".$site_setup['website_title']." purchase ",
	"metadata" => array("email" => $_POST['email_address'], "website" => $setup['url'].$setup['temp_url_folder'])	  
  )
);
  
  
 } catch(Stripe_CardError $e) {
	/*	$all_data = "REPSONSE FROM PROCESSOR \r\n";
		foreach($response AS $id => $val) {	
			$all_data .= "$id: $val\r\n";
		}
		$all_data .= "\r\nREPSONSE FROM POST \r\n";
		foreach($_POST AS $id => $val) {	
			if(($id!=="creditcard")AND($id!=="month")AND($id!=="year")AND($id!=="cvv")==true) { 
				// $all_data .= "$id: $val\r\n";
			}
		}
		*/

		insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['grand_total']."', card='".$response->account_number."' , card_brand='".$response->card_type."' , response_code='".$response->transaction_id."',  response_message='".$response->error_message."', name='". addslashes(stripslashes($_POST['first_name']))." ". addslashes(stripslashes($_POST['last_name']))." ',  email='".$_POST['email_address']."', order_number='".$_POST['order_number']."', country='".$_POST['country']."', all_data='". addslashes(stripslashes($all_data))."' , declined='1'  ");


		$_SESSION['decline_message'] = _checkout_card_declined_."(".$response->response_reason_text.") ";
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";

  // The card has been declined
  }

if ($charge->paid == true) { 
		insertSQL("ms_pay_attempts", "date=NOW(), remote_host='$remote_host', ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', amount='".$_POST['grand_total']."', card='".$response->account_number."' , card_brand='".$response->card_type."' , response_code='".$response->transaction_id."', name='". addslashes(stripslashes($_POST['first_name']))." ". addslashes(stripslashes($_POST['last_name']))." ',  email='".$_POST['email_address']."', order_number='".$_POST['order_number']."', country='".$_POST['country']."', all_data='". addslashes(stripslashes($all_data))."'  ");


			/* Payjunction response */
			$order_total_pay = $_POST['grand_total'];
			$payment_amount = $_POST['grand_total'];
			$payment_amout = $order_total_pay;
			$sub_total = $_REQUEST['sub_total'];
			$order_fees = "";
			$currency = $store['currency'];
			$transaction_id = $charge->id;
			$order_ship_amount = $_REQUEST['shipping_price'];
			$shipping_option = $_REQUEST['ship_select'];
			$order_tax = $_REQUEST['tax_price'];
			$tax_percentage = $_REQUEST['tax_percentage'];
			$vat = $_POST['vat_price'];
			$vat_percentage = $_POST['vat_percentage'];
			$taxable_amount = $_POST['taxable_amount'];
			$order_discount = $_POST['discount_amount'];
			$order_message  = $_POST['customer_message'];
			$order_pay_type = $charge->brand;
			$order_payment_status = "Completed";
			$order_pending_reason = $_POST['pending_reason'];
			$pay_option = "Stripe";
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
			$credit_amount = $_REQUEST['credit_amount'];
			$gift_certificate_amount = $_REQUEST['gift_certificate_amount'];
			$gift_certificate_id = $_REQUEST['gift_certificate_id'];
			$order_eb_discount = $_REQUEST['eb_amount'];
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

			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			$customer_id = $person['p_id'];

			if($_REQUEST['order_id'] > 0) { 
				$order_id = $_REQUEST['order_id'];
				include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
			} else { 
				include $setup['path']."/sy-inc/store/payment/payment-complete.php";
				createOrder();
			}
			exit();
		

}

?>
