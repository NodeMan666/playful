<?php 
$path = "../../../";
require "../../w-header.php";
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".$_REQUEST['order_id']."' ");
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
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}

if($_REQUEST['action'] == "deletePayment") { 
	updateSQL("ms_orders", "order_payment='', order_payment_date='', order_pay_type='', order_payment_reference='', order_payment_status='', order_payment_info=''  WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Payment information deleted"; 
	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']."");
	session_write_close();
	exit();

}

if($_POST['submitit']=="yes") { 
	updateSQL("ms_orders", "order_payment='".addslashes(stripslashes($_REQUEST['order_payment']))."' , order_payment_date='".addslashes(stripslashes($_REQUEST['order_payment_date']))."' , order_pay_type='".addslashes(stripslashes($_REQUEST['order_pay_type']))."' , order_payment_reference='".addslashes(stripslashes($_REQUEST['order_payment_reference']))."', order_payment_status='".$_REQUEST['order_payment_status']."'   WHERE order_id='".$_REQUEST['order_id']."' ");
		$color_id = $_REQUEST['color_id'];
	$_SESSION['sm'] = "Payment saved";
	if($_REQUEST['send_email'] == "1") { 
		sendWebdEmail(stripslashes($_REQUEST['to_email']), stripslashes($_REQUEST['to_name']), stripslashes($_REQUEST['from_email']), stripslashes($_REQUEST['from_name']), stripslashes($_REQUEST['subject']), stripslashes($_REQUEST['message']),"1");
	}

	if($_REQUEST['order_payment_status'] == "Completed") { 
		$icarts = whileSQL("ms_cart", "*", "WHERE cart_order='".$_REQUEST['order_id']."' " );
		while($icart= mysqli_fetch_array($icarts)) {

			if($icart['cart_paid_access'] > 0) { 
				$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$icart['cart_store_product']."' ");
				insertSQL("ms_my_pages", "mp_date_id='".$icart['cart_store_product']."', mp_people_id='".$order['order_customer']."', mp_date=NOW() ");
				$add_to_credit = ", credit_date_id_only='".$icart['cart_store_product']."' ";
				if($pdate['date_paid_access_unlock'] == "1") { 
					updateSQL("ms_calendar", "private='0', date_paid_access='0'  WHERE date_id='".$pdate['date_id']."' ");
				}

			}

			if($icart['cart_gift_certificate'] > 0) { 

				$redeem_code = date('Y').$icart['cart_id'].rand(1000,9999); 
				$gcid = insertSQL("ms_gift_certificates", "to_email='".addslashes(stripslashes($icart['cart_gift_certificate_to_email']))."', to_name='".addslashes(stripslashes($icart['cart_gift_certificate_to_name']))."', from_email='".addslashes(stripslashes($icart['cart_gift_certificate_from_email']))."', from_name='".addslashes(stripslashes($icart['cart_gift_certificate_from_name']))."', message='".addslashes(stripslashes($icart['cart_gift_certificate_message']))."', date_purchased='".currentdatetime()."', amount='".addslashes(stripslashes($icart['cart_price']))."', purchased_order_id='".$order_id."', redeem_code='".$redeem_code."' ");
				sendgiftcertificateemail($gcid);
				##  Send email. 
			}

			if($icart['cart_account_credit'] > 0) { 
				if($icart['cart_account_credit_for'] > 0) { 
					insertSQL("ms_credits", "credit_amount='".($icart['cart_qty'] * $icart['cart_account_credit'])."', credit_customer='".$icart['cart_account_credit_for']."', credit_date='".currentdatetime()."', credit_order='".$order_id."', credit_reg_buyer_name='".addslashes(stripslashes($icart['cart_reg_message_name']))."', credit_reg_message='".addslashes(stripslashes($icart['cart_reg_message']))."', credit_reg='".$icart['cart_store_product']."', credit_reg_order='".$_REQUEST['order_id']."', credit_reg_buyer='".$order['order_customer']."', credit_reg_no_display_amount='".$icart['cart_reg_no_display_amount']."', credit_reg_buyer_email='".addslashes(stripslashes($order['order_email']))."' ");
					sendregistrynotification($icart,$_REQUEST['order_id']);
				} else { 
					insertSQL("ms_credits", "credit_amount='".($icart['cart_qty'] * $icart['cart_account_credit'])."', credit_customer='".$order['order_customer']."', credit_date='".currentdatetime()."', credit_order='".$_REQUEST['order_id']."' $add_to_credit ");
				}
			}
		}
	}





	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']."");
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<?php if($_REQUEST['showDeleteSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Deleted");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>

<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>

	<div class="title"><h1>Add payment to order #<?php print $order['order_id'];?></h1></div>
	<div class="pc"><a  onClick="return confirm('Are you sure you want to delete the payment information for this order?'); return false;"   href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?action=deletePayment&order_id=<?php print $_REQUEST['order_id'];?>" >Delete payment information</a>
	</div>

	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm();">

	<div class="underline">
		<div style="width: 50%; float: left;">
			<div class="label">Amount</div>
			<?php if($order['order_payment'] <= 0) { 
				$order['order_payment'] = $order['order_total'];
			}
	?>
			<div><input type="text" name="order_payment" id="order_payment" value="<?php  print htmlspecialchars(stripslashes($order['order_payment']));?>" class="required"></div>
		</div>
		<div style="width: 50%; float: left;">
			<div class="label">Payment Type</div>
			<div><input type="text" name="order_pay_type" id="order_pay_type" value="<?php  print htmlspecialchars(stripslashes($order['order_pay_type']));?>" class=""></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="underline">
		<div style="width: 50%; float: left;">
			<div class="label">Reference</div>
			<div><input type="text" name="order_payment_reference" id="order_payment_reference" value="<?php  print htmlspecialchars(stripslashes($order['order_payment_reference']));?>" class=""></div>
		</div>
		<div style="width: 50%; float: left;">
		<div class="label">Date</div>
		<?php if($order['order_payment_date'] == "0000-00-00") { 
			$order['order_payment_date'] = date('Y-m-d');
			}
		?>
		<div><input type="text" name="order_payment_date" id="order_payment_date" value="<?php  print htmlspecialchars(stripslashes($order['order_payment_date']));?>" class="datepicker"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="underline center">
	<div class="label">Payment Status</div>
	<div>
	<select name="order_payment_status" id="order_payment_status" class="required">
	<option value="">Select Status</option>
	<option value="Pending">Pending</option>
	<option value="Completed" <?php if($order['order_payment_status'] == "Completed") { print "selected"; } ?>>Completed</option>
	</select>
	</div>
	</div>





	<script>
	function showemail() { 
		$("#emailform").slideToggle(200);
	}
	</script>




<div class="underline center"><input type="checkbox" id="send_email" name="send_email" value="1" onchange="showemail();"> Send order email to customer</div>




<div id="emailform"  class="useroptions <?php if($_REQUEST['email_id'] <=0) { print "hidden"; } ?>">
	<div class="pc"><h2>Send email to <?php print $order['order_email'];?></h2></div>


	<input type="hidden" id="p_ids" value="<?php print $p_ids;?>">
<?php 

$iecarts = whileSQL("ms_cart", "*", "WHERE cart_order='".$order['order_id']."' ");
while($iecart= mysqli_fetch_array($iecarts)) {
	if($iecart['cart_ship'] == "1") { 
		$ship_email++;
	}
	if($iecart['cart_download'] == "1") { 
		$download_email++;
	}
}
if(!empty($_REQUEST['email_id'])) { 
	$em = doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['email_id']."' ");
} else { 
	$em = doSQL("ms_emails", "*", "WHERE email_id='6' ");
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
$message = str_replace("[COUNTRY]",$order['order_country'], "$message");

if(empty($order['order_shipping_option'])) { 
	$message = str_replace("[SHIP_FIRST_NAME]","N/A", "$message");
	$message = str_replace("[SHIP_LAST_NAME]","", "$message");
	$message = str_replace("[SHIP_ADDRESS]","", "$message");
	$message = str_replace("[SHIP_CITY]","", "$message");
	$message = str_replace("[SHIP_STATE]","", "$message");
	$message = str_replace("[SHIP_ZIP]","", "$message");
	$message = str_replace("[SHIP_COUNTRY]","", "$message");
} else { 
	$message = str_replace("[SHIP_FIRST_NAME]",$order['order_ship_first_name'], "$message");
	$message = str_replace("[SHIP_LAST_NAME]",$order['order_ship_last_name'], "$message");
	$message = str_replace("[SHIP_ADDRESS]",$order['order_ship_address'], "$message");
	$message = str_replace("[SHIP_CITY]",$order['order_ship_city'], "$message");
	$message = str_replace("[SHIP_STATE]",$order['order_ship_state'], "$message");
	$message = str_replace("[SHIP_ZIP]",$order['order_ship_zip'], "$message");
	$message = str_replace("[SHIP_COUNTRY]",$order['order_ship_country'], "$message");
}	

$message = str_replace("[ORDER_TOTAL]","".showPrice($order['order_amount'])."", "$message");
$message = str_replace("[ACCOUNT_LINK]","".$setup['url'].$setup['temp_url_folder']."?view=account", "$message");
$message = str_replace("[ORDER_DATE]",$order['order_date'], "$message");
$message = str_replace("[ORDER_ITEMS]",getOrderItems($order), "$message");
$message = str_replace("[TOTAL_ITEMS]",$total_items, "$message");
$message = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$message");
$message = str_replace("[ORDER_LINK]",$setup['url'].$setup['temp_url_folder']."/index.php?view=order&myorder=".$order_id."", "$message");

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

$subject = str_replace("[ORDER_TOTAL]","".showPrice($order['order_amount'])."", "$subject");
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

	?>
		
		<div>
		<div id="emailsend">
			<div class="underline">
				<div class="left p50">
					<div class="label">From Email</div>
					<div><input type="text" id="from_email" name="from_email" value="<?php print $from_email;?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">From name: </div>
					<div><input type="text" id="from_name" name="from_name" value="<?php print $from_name;?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="underline">
				<div class="left p50">
					<div class="label">To Email</div>
					<div><input type="text" id="to_email" name="to_email" value="<?php print $order['order_email'];?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">To name: </div>
					<div><input type="text" id="to_name" name="to_name" value="<?php print $order['order_first_name']." ".$order['order_last_name'];?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="underline">
				<div class="label">Subject: </div>
				<div><input type="text" id="subject" name="subject" value="<?php print $subject;?>" size="40" class="field100"></div>
			</div>
			<div class="underline">
				<div class="label">Message (note [FIRST_NAME] & [LAST_NAME] will be replaced when the email is sent). You can edit this default email in Settings > Default Emails > <?php print $em['email_name'];?>.</div> 
				<div>
					<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
					<?php 
					$email_style = true;	
					addEditor("message", "1", "300", "1"); ?>

				</div>
			</div>
		</div>


	</div>
	<div class="clear"></div>
	</div>

<div class="pageContent center">
<input type="hidden" name="order_id" value="<?php print $_REQUEST['order_id'];?>">
<input type="hidden" name="submitit" value="yes">


<input type="submit" name="submit" value="Add Payment" class="submit" id="submitButton">
<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
</div>

</form>


<?php require "../../w-footer.php"; ?>