<?php 
	function createOrder() {
		global $setup, 
			$site_setup, 
			$response,
			$order_total_pay,
			$order_fees,
			$currency,
			$transaction_id,
			$order_ship_amount,
			$order_tax,
			$tax_percentage,
			$vat,
			$vat_percentage,
			$taxable_amount,
			$order_discount,
			$order_message,
			$card_brand,
			$order_payment_status,
			$order_pending_reason,
			$order_payer_status,
			$pay_option,
			$company_name,
			$first_name,
			$last_name,
			$email_address,
			$country,
			$city,
			$state,
			$zip,
			$address_status,
			$address,
			$phone,
			$order_session,
			$ship_business,
			$ship_first_name,
			$ship_last_name,
			$ship_address,
			$ship_city,
			$ship_state,
			$ship_zip,
			$ship_country,
			$ip_address,
			$order_pay_type,
			$order_address_status,
			$order_payer_status,
			$shipping_option,
			$sub_total,
			$coupon_id,
			$coupon_name,
			$order_key,
			$customer_id,
			$payment_amount,
			$order_offline,
			$order_manual,
			$no_redirect,
			$credit_amount,
			$gift_certificate_amount,
			$gift_certificate_id,
			$order_eb_discount,
			$order_notes,
			$anet_sim,
			$order_extra_field_1,
			$order_extra_val_1,
			$order_extra_field_2,
			$order_extra_val_2,
			$order_extra_field_3,
			$order_extra_val_3,
			$order_extra_field_4,
			$order_extra_val_4,
			$order_extra_field_5,
			$order_extra_val_5,
			$order_join_ml;

			$order_total_for_credit = $sub_total  - $order_discount - $order_eb_discount + $order_ship_amount + $order_tax + $order_vat;
			if($credit_amount > $order_total_for_credit) { 
				$credit_amount = $order_total_for_credit;
				$order_total_for_credit = $order_total_for_credit - $credit_amount;
			}
			if($gift_certificate_amount > 0) { 
				if($gift_certificate_amount > $order_total_for_credit) { 
					$gift_certificate_total = $order_total_for_credit;
					$credit_from_gift_certificate = $gift_certificate_amount - $gift_certificate_total;
					insertSQL("ms_credits", "credit_amount='".$credit_from_gift_certificate."', credit_customer='".$customer_id."', credit_date='".currentdatetime()."', credit_order='".$order_id."' ");
				} else { 
					$gift_certificate_total = $gift_certificate_amount;
				}
			}


			date_default_timezone_set(''.$site_setup['time_zone'].'');

			$ship = doSQL("ms_shipping_methods", "*", "WHERE method_id='".$shipping_option."' ");

			$order_id = insertSQL("ms_orders", "order_total='".addslashes(stripslashes($order_total_pay))."', 
			order_date='".currentdatetime()."', 
			order_payment='".$payment_amount."',
			order_key='".addslashes(stripslashes($order_key))."',  
			order_pay_type='".addslashes(stripslashes($order_pay_type))."', 
			order_payment_status='".addslashes(stripslashes($order_payment_status))."', 
			order_customer='".addslashes(stripslashes($customer_id))."', 
			order_currency='".addslashes(stripslashes($currency))."', 
			order_fees='".addslashes(stripslashes($order_fees))."', 
			order_pending_reason='".addslashes(stripslashes($order_pending_reason))."', 
			order_pay_transaction='".addslashes(stripslashes($transaction_id))."',
			order_tax='".addslashes(stripslashes($order_tax))."',
			order_tax_percentage='".addslashes(stripslashes($tax_percentage))."',
			order_vat='".addslashes(stripslashes($vat))."',
			order_vat_percentage='".addslashes(stripslashes($vat_percentage))."',
			order_taxable_amount='".addslashes(stripslashes($taxable_amount))."',
			order_shipping='".addslashes(stripslashes($order_ship_amount))."',
			order_first_name='".addslashes(stripslashes($first_name))."',
			order_last_name='".addslashes(stripslashes($last_name))."',
			order_email='".addslashes(stripslashes($email_address))."',
			order_business_name='".addslashes(stripslashes($company_name))."',
			order_country='".addslashes(stripslashes($country))."',
			order_city='".addslashes(stripslashes($city))."',
			order_state='".addslashes(stripslashes($state))."',
			order_zip='".addslashes(stripslashes($zip))."',
			order_address_status='".addslashes(stripslashes($order_address_status))."',
			order_address='".addslashes(stripslashes($address))."',
			order_phone='".addslashes(stripslashes($phone))."',
			order_ip='". addslashes(stripslashes($ip_address))."',
			order_session='".addslashes(stripslashes($order_session))."',
			order_payment_option = '".addslashes(stripslashes($pay_option))."',
			order_payer_status='".addslashes(stripslashes($order_payer_status))."',
			order_ship_business='".addslashes(stripslashes($ship_business))."',
			order_ship_first_name='".addslashes(stripslashes($ship_first_name))."',
			order_ship_last_name='".addslashes(stripslashes($ship_last_name))."',
			order_ship_address='".addslashes(stripslashes($ship_address))."',
			order_ship_city='".addslashes(stripslashes($ship_city))."',
			order_ship_state='".addslashes(stripslashes($ship_state))."',
			order_ship_zip='".addslashes(stripslashes($ship_zip))."',
			order_ship_country='".addslashes(stripslashes($ship_country))."',
			order_discount='".addslashes(stripslashes($order_discount))."',
			order_shipping_option='".addslashes(stripslashes($ship['method_name']))."',
			order_ship_pickup='".addslashes(stripslashes($ship['method_pickup']))."',
			order_sub_total='".addslashes(stripslashes($sub_total))."',
			order_coupon_id='".addslashes(stripslashes($coupon_id))."',
			order_coupon_name='".addslashes(stripslashes($coupon_name))."',
			order_credit='".addslashes(stripslashes($credit_amount))."',
			order_gift_certificate='".addslashes(stripslashes($gift_certificate_total))."',
			order_gift_certificate_id='".addslashes(stripslashes($gift_certificate_id))."',
			order_eb_discount='".addslashes(stripslashes($order_eb_discount))."',
			order_notes='".addslashes(stripslashes(strip_tags($order_notes)))."',

			order_extra_field_1 ='".addslashes(stripslashes(strip_tags($order_extra_field_1)))."',
			order_extra_val_1 ='".addslashes(stripslashes(strip_tags($order_extra_val_1)))."',
			order_extra_field_2 ='".addslashes(stripslashes(strip_tags($order_extra_field_2)))."',
			order_extra_val_2 ='".addslashes(stripslashes(strip_tags($order_extra_val_2)))."',
			order_extra_field_3 ='".addslashes(stripslashes(strip_tags($order_extra_field_3)))."',
			order_extra_val_3 ='".addslashes(stripslashes(strip_tags($order_extra_val_3)))."',
			order_extra_field_4 ='".addslashes(stripslashes(strip_tags($order_extra_field_4)))."',
			order_extra_val_4 ='".addslashes(stripslashes(strip_tags($order_extra_val_4)))."',
			order_extra_field_5 ='".addslashes(stripslashes(strip_tags($order_extra_field_5)))."',
			order_extra_val_5 ='".addslashes(stripslashes(strip_tags($order_extra_val_5)))."',

			order_offline='".$order_offline."'
			");
			if($gift_certificate_id > 0) { 
				updateSQL("ms_gift_certificates", "used_order='".$order_id."' WHERE redeem_code='".$gift_certificate_id."' ");
			}
			if($order_fees > 0) { 
				$ckexp = doSQL("ms_expenses_tags", "*", "WHERE name='paypal fees' ");
				if(empty($ckexp['tag_id'])) { 
					$exptagid = insertSQL("ms_expenses_tags", "name='paypal fees' ");
				} else { 
					$exptagid = $ckexp['tag_id'];
				}
				$exp = insertSQL("ms_expenses", "exp_amount='".$order_fees."', exp_date=NOW(), exp_order='".$order_id."' ");
				insertSQL("ms_expenses_tags_connect", "con_exp_id='".$exp."', con_tag_id='".$exptagid."' ");
			}
	
	/*
		if($payment_amount > 0) { 
			insertSQL("ms_payments", "pay_order='".$order_id."', pay_amount='".$payment_amount."', pay_type='".$order_pay_type."', pay_date=NOW(), pay_reference='".$transaction_id."', pay_option='".$payment_option."', pay_status='".$order_payment_status."', pay_pending_reason='".$order_pending_reason."', pay_currency='".$currency."' ");
		}
*/
	$ship_email = 0;
	$download_email = 0;
	if($credit_amount > 0) { 
		insertSQL("ms_credits", "credit_amount='-".$credit_amount."', credit_customer='".$customer_id."', credit_date='".currentdatetime()."', credit_order='".$order_id."' ");
	}

	if($customer_id > 0) { 
		$icarts = whileSQL("ms_cart", "*", "WHERE cart_client='".MD5($customer_id)."' AND cart_order<='0' " );
	} else { 
		$icarts = whileSQL("ms_cart", "*", "WHERE cart_session='".$order_session."' AND cart_client='' AND cart_order<='0' " );
	}
	while($icart= mysqli_fetch_array($icarts)) {
		if($icart['cart_ship'] == "1") { 
			$ship_email++;
		}
		if($icart['cart_download'] == "1") { 
			$download_email++;
		}

		updateSQL("ms_cart", "cart_order='".$order_id."' WHERE cart_id='".$icart['cart_id']."' ");
		if(!empty($icart['cart_print_credit'])) { 
			updateSQL("ms_print_credits", "pc_order='".$order_id."' WHERE pc_code='".addslashes(stripslashes($icart['cart_print_credit']))."' ");
		}
		if($order_payment_status == "Completed") { 

			if($icart['cart_paid_access'] > 0) { 
				$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$icart['cart_store_product']."' ");
				insertSQL("ms_my_pages", "mp_date_id='".$icart['cart_store_product']."', mp_people_id='".$customer_id."', mp_date=NOW() ");
				$add_to_credit = ", credit_date_id_only='".$icart['cart_store_product']."' ";
				if($pdate['date_paid_access_unlock'] == "1") { 
					updateSQL("ms_calendar", "private='0', date_paid_access='0'  WHERE date_id='".$pdate['date_id']."' ");
				}
			}

			if($icart['cart_gift_certificate'] > 0) { 

				$redeem_code = date('Y').$icart['cart_id'].rand(1000,9999); 
				$gcid = insertSQL("ms_gift_certificates", "to_email='".addslashes(stripslashes($icart['cart_gift_certificate_to_email']))."', to_name='".addslashes(stripslashes($icart['cart_gift_certificate_to_name']))."', from_email='".addslashes(stripslashes($icart['cart_gift_certificate_from_email']))."', from_name='".addslashes(stripslashes($icart['cart_gift_certificate_from_name']))."', message='".addslashes(stripslashes($icart['cart_gift_certificate_message']))."', date_purchased='".currentdatetime()."', amount='".addslashes(stripslashes($icart['cart_price']))."', purchased_order_id='".$order_id."', redeem_code='".$redeem_code."', delivery_date='".$icart['cart_delivery_date']."' ");
				if($icart['cart_delivery_date'] == date('Y-m-d')) { 
					sendgiftcertificateemail($gcid);
					##  Send email. 
				}
			}


			if($icart['cart_account_credit'] > 0) { 
				if($icart['cart_account_credit_for'] > 0) { 
					insertSQL("ms_credits", "credit_amount='".($icart['cart_qty'] * $icart['cart_account_credit'])."', credit_customer='".$icart['cart_account_credit_for']."', credit_date='".currentdatetime()."', credit_order='".$order_id."', credit_reg_buyer_name='".addslashes(stripslashes($icart['cart_reg_message_name']))."', credit_reg_message='".addslashes(stripslashes($icart['cart_reg_message']))."', credit_reg='".$icart['cart_store_product']."', credit_reg_order='".$order_id."', credit_reg_buyer='".$customer_id."', credit_reg_no_display_amount='".$icart['cart_reg_no_display_amount']."', credit_reg_buyer_email='".addslashes(stripslashes($email_address))."' ");
					sendregistrynotification($icart,$order_id);
				} else { 
					insertSQL("ms_credits", "credit_amount='".($icart['cart_qty'] * $icart['cart_account_credit'])."', credit_customer='".$customer_id."', credit_date='".currentdatetime()."', credit_order='".$order_id."' $add_to_credit ");
				}
			}
			if($icart['cart_subscription'] == "1") { 
				// ADD SUBSCRIPTINO INFORMATION
				$custom = explode(",",$icart['cart_custom']);
				foreach($custom AS $id => $f) { 
					$data = explode("|",$f);
					$sub_info = $cf[$data[0]] = $data[1];
				}

				$sub['sub_start'] = date('Y-m-d');
				$sub['sub_day_of_month'] = date('d');
				$sub['sub_next_due_date'] = date("Y-m-d", mktime(0, 0, 0, date("m")+1 , date('d'), date("Y")));
				$characters = '@#$%^&*(?!(+_)qwertyipAcvvbnmuioHDKFGMNBCXZLywg1234567890';
				$salt = '';
				for ($i = 0; $i < 5; $i++) { 
					$salt .= $characters[mt_rand(0, 50)];
				}
				$sub_key = md5($last_name.date('Ymdhis').$salt);
				$sub_key = substr($sub_key, 0, 16);
				$subdate = doSQL("ms_calendar", "*", "WHERE date_id='".$icart['cart_store_product']."' ");
				insertSQL("ms_subscriptions", "sub_person='".$customer_id."', sub_product='".$icart['cart_store_product']."', sub_product_name='".$icart['cart_product_name']."', sub_price='".$subdate['prod_subscription_price']."', sub_frequency='monthly', sub_start='".$sub['sub_start']."', sub_day_of_month='".$sub['sub_day_of_month']."', sub_next_due_date='".$sub['sub_next_due_date']."', sub_status='0' , sub_order_number='".$order_id."', sub_key='".$sub_key."' ");
			}
			// Check option
			$regopt = doSQL("ms_cart_options LEFT JOIN ms_product_options ON ms_cart_options.co_opt_id=ms_product_options.opt_id", "*", "WHERE co_cart_id='".$icart['cart_id']."' AND opt_type='reg_key' ");
			if(!empty($regopt['co_id'])) { 
				$regkey = doSQL("ms_reg_keys", "*", "WHERE key_key='".$regopt['co_select_name']."' AND key_product='sytist1' ");
				if(!empty($regkey['key_id'])) { 
					$new_expire = date("Y-m-d", mktime(0, 0, 0, date("m") , date('d'), date("Y")+1));
					updateSQL("ms_reg_keys", "key_expire='".$new_expire."' WHERE key_id='".$regkey['key_id']."' ");
				}
			}
		}
		$order_items .= "".$icart['cart_qty']." - ".$icart['cart_product_name']." -".showPrice($icart['cart_price'])."\r\n";
		$total_items = $total_items + $icart['cart_qty'];
		if(!empty($icart['cart_store_product'])) {
			$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$icart['cart_store_product']."' ");
			if($prod['date_package'] > 0) { 
				prepaypackage($prod,$icart,$order_id);
			}
			if($prod['prod_inventory_control'] == "1") { 
				if($icart['cart_sub_id'] > 0) { 
					$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$icart['cart_sub_id']."' ");
					$new_qty = $sub['sub_qty'] - $icart['cart_qty'];
					updateSQL("ms_product_subs", "sub_qty='$new_qty' WHERE sub_id='".$sub['sub_id']."' ");
				} else { 
					$new_qty = $prod['prod_qty'] - $icart['cart_qty'];
					updateSQL("ms_calendar", "prod_qty='$new_qty' WHERE date_id='".$prod['date_id']."' ");
				}
			}
			if($prod['prod_create_reg_key'] == "1") {
				$thekey = substr(md5(date('ymdHis')),0,30).$icart['cart_id'];
				$keyexpire = date("Y-m-d", mktime(0,0,0,date('m') ,date('d'),date('Y') + 1));
				$keyid = insertSQL("ms_reg_keys", "key_key='$thekey', key_name='".addslashes(stripslashes($first_name." ".$last_name))."', key_email='".$email_address."', key_date='".currentdatetime()."', key_expire='".$keyexpire."', key_product='".addslashes(stripslashes($prod['prod_prod_id']))."', key_order='$order_id' ");
				updateSQL("ms_cart", "cart_reg_key='".$thekey."' WHERE cart_id='".$icart['cart_id']."' ");

				if($prod['prod_prod_id'] == "photocartupgrade") {
					$email_replace_key .= "Upgrade code: ".$thekey."\r\n";
				} else {
					$email_replace_key .= "".$prod['prod_name']." registration key: ".$thekey."\r\n";
				}
			}
			if(!empty($prod['prod_sep_order_email'])) {
				sendOrderEmail($prod['prod_sep_order_email'],$_POST['email_address'], $_POST['first_name']);
			}
		}
	}
	if(!empty($order_join_ml)) { 
		joinmailinglist($email_address,$first_name,$last_name,'checkout');
	}
	if(!empty($_REQUEST['join_ml'])) { 
		joinmailinglist($email_address,$first_name,$last_name,'checkout');
	}


	checkCouponOrder($order_id);
	$show_prices = true;
	$no_trim = true;
	include $setup['path']."/sy-inc/store/payment/payment-complete-send-notification.php";
	if($no_redirect !== true) { 
		if($order_offline > 0) { 

		} elseif($order_manual == "1") { 

		} else { 
			$_SESSION['payment_complete'] = true;
		}
		$_SESSION['my_order_id'] = $order_id;
		$_SESSION['check_affiliate'] = true;
		if(!empty($_SESSION['last_gallery'])) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
			if($date['date_gallery_exclusive'] == "1") { 
				if(countIt("ms_cart", "WHERE cart_order='".$order_id."' AND cart_pic_date_id='".$date['date_id']."' ") > 0) { 
					$ge_return_link = $date['cat_folder']."/".$date['date_link']."/";
				}
			}
		}
		if(!empty($ge_return_link)) { 
			header("location: ".$setup['temp_url_folder'].$ge_return_link."?view=order&new=".$order_id."");
		} else { 
			header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=order&new=".$order_id."");
		}
		session_write_close();
	}	

	if($anet_sim == true) { 
		anetSimComplete($order_id,$order_session,$order_key);
	}


	exit();
}

function anetSimComplete($order_id,$order_session,$order_key) { 
	global $setup;
?>
<div style="width: 400px; margin: 100px auto 100px auto;">
<div style="padding: 8px;"><h2>Your transaction is complete</h2></div>
<div style="padding: 8px;"><a href="<?php print $setup['url'].$setup['temp_url_folder']."/index.php?view=order&frompp=1&msos=".$order_session."&msok=".$order_key;?>">Click here to continue</a></div>
</div>
<?php 
}
function sendOrderEmail($email_id,$to_email, $to_name) {
	global $site_setup,$order_id,$setup,$order_address,$order_city,$order_state,$order_country,$order_zip,$order_join_ml,$order_phone,$order_company,$email_replace_key;
		$em = doSQL("ms_emails", "*", "WHERE email_id='$email_id' ");
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

	$message = $em['email_message'];

	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[PAYMENT_AMOUNT]","".$order_total_pay."", "$message");
	$message = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$message");
	$message = str_replace("[ORDER_NUMBER]",$order_id, "$message");
	$message = str_replace("[FIRST_NAME]",$_POST['first_name'], "$message");
	$message = str_replace("[LAST_NAME]",$_POST['last_name'], "$message");
	$message = str_replace("[EMAIL_ADDRESS]",$_POST['email_address'], "$message");
	$message = str_replace("[ORDER_TOTAL]","".showPrice($order_total_pay)."", "$message");
	$message = str_replace("[ORDER_LINK]","".$setup['url'].$setup['temp_url_folder']."/".$setup['store_folder']."/myorder/", "$message");
	$message = str_replace("[ORDER_DATE]",date('m/d/Y'), "$message");
	$message = str_replace("[ORDER_ITEMS]",$order_items, "$message");
	$message = str_replace("[TOTAL_ITEMS]",$total_items, "$message");

	$message = str_replace("[UPGRADE_CODE]",$email_replace_key, "$message");



	$subject = str_replace("[FIRST_NAME]",$_POST['first_name'], "$subject");
	$subject = str_replace("[LAST_NAME]",$_POST['last_name'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");
	$subject = str_replace("[ORDER_NUMBER]",$order_id, "$subject");
	stripslashes($message);
	stripslashes($subject);

	sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,"1");

}



?>