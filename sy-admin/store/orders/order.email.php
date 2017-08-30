<?php 
$store = doSQL("ms_store_settings", "*", "");
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date,date_format(DATE_ADD(order_due_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_due_date_show", "WHERE order_id='".$_REQUEST['order_id']."' "); 
$p = doSQL("ms_people", "*", "WHERE p_id='".$order['order_customer']."' ");
$no_trim = true;
$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
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






?>

<div class="pc"><a href="index.php?do=orders">&larr; Orders</a></div>
<div class="pc newtitles"><span class=""><?php if($order['order_invoice'] == "1") { ?>Invoice<?php } else { ?>Order<?php } ?> # <?php print $order['order_id']; ?></span></div> 

<?php 
if($_REQUEST['send'] == "yes") { 
	$ship = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$_REQUEST['ship_id']."' ");
	if($_REQUEST['ship_id'] == "0") { 
		$ship_name = $_REQUEST['ship_other'];
	} else { 
		$ship_name = $ship['ship_name'];
	}

	$message = str_replace("[SHIPPING_METHOD]",$ship_name, $_REQUEST['email_message']);
	if(!empty($ship['ship_track'])) { 
		$message = str_replace("[TRACKING_INFORMATION]","<a href=\"".$ship['ship_track']."".$_REQUEST['ship_track']."\">".$_REQUEST['ship_track']."</a>", $message);
	} else { 
		$message = str_replace("[TRACKING_INFORMATION]","".$_REQUEST['ship_track']."", $message);
	}
	//print stripslashes($message);
	if($_REQUEST['archive'] == "1") { 
		$and_archive = ", order_status='1' ";
		$and_message .= "Order has been archived. ";
	}
	updateSQL("ms_orders", "order_shipped_by_id='".$ship['ship_id']."',order_shipped_by='".$ship_name."', order_shipped_date='".date('Y-m-d H:i:s')."', order_shipped_track='".$_REQUEST['ship_track']."', order_ship_cost='".$_REQUEST['order_ship_cost']."' $and_archive WHERE order_id='".$order['order_id']."' ");
	if($_REQUEST['send_email'] == "1") { 
		sendWebdEmail($_REQUEST['send_to_email'], stripslashes($_REQUEST['send_to_name']), $_REQUEST['send_from_email'], stripslashes($_REQUEST['send_from_name']), stripslashes($_REQUEST['email_subject']), stripslashes($message),'1');
		$and_message .= "Email sent to ".$_REQUEST['send_to_email'].". '";
	}
	$_SESSION['sm'] = "Invoice has been sent "; 
		if($_REQUEST['archive'] == "1") { 
		header("location: index.php?do=orders");
		} else { 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$order['order_id']."");
		} 
		session_write_close();
		exit();
}
?>
<script>
function getOtherShipping() { 
	if($("#ship_id").val() == "0") { 
		$("#ship_other").show();
	} else { 
		$("#ship_other").hide();
	}
}
</script>
<?php include "order.tabs.php"; ?>
<div id="roundedFormContain">

<form method="post" name="newLink" action="index.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
<?php $ls = doSQL("ms_orders", "*", "WHERE order_shipped_by_id > '0' ORDER BY order_shipped_date DESC ");?>
<div style="width: 30%; float: left;">
	<div id="roundedForm">
	<input type="hidden" name="send_email" id="send_email" value="1" >
	<div class="row">
	Review the email being sent to the right and make any changes to the text if needed.
	</div>
	<div class="row center">
	<input type="hidden" name="do" value="orders">
	<input type="hidden" name="action" value="email">
	<input type="hidden" name="send" value="yes">
	<input type="hidden" name="order_id" value="<?php print $order['order_id'];?>">
	<input type="submit" name="submit" value="Email Invoice" class="submit">
	</div>
	</div>

</div>

<div style="width: 70%; float: left;">

<?php 
if($order['order_invoice'] == "1") { 
	$em = doSQL("ms_emails", "*", "WHERE email_id_name='emailinvoice' "); 
} else { 
	$em = doSQL("ms_emails", "*", "WHERE email_id='21' "); 

}
?>
<?php 
	if($setup['demo_mode'] == true) { 
		$order['order_first_name'] = get_starred($order['order_first_name']);
		$order['order_last_name'] = get_starred($order['order_last_name']);
		$order['order_email'] = "demo@demo.mode";
		$order['order_ship_first_name'] = get_starred($order['order_ship_first_name']);
		$order['order_ship_last_name'] = get_starred($order['order_ship_last_name']);
	}

	$show_prices = true;

	$subject = $em['email_subject'];

	$message = $em['email_message'];
	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");

	$message = str_replace("[PAYMENT_AMOUNT]","".$order['order_amount']."", "$message");
	$message = str_replace("[ORDER_NUMBER]",$order['order_id'], "$message");
	$message = str_replace("[FIRST_NAME]",$order['order_first_name'], "$message");
	$message = str_replace("[LAST_NAME]",$order['order_last_name'], "$message");
	$message = str_replace("[EMAIL_ADDRESS]",$order['order_email'], "$message");
	$message = str_replace("[ADDRESS]",$order['order_address'], "$message");
	$message = str_replace("[CITY]",$order['order_city'], "$message");
	$message = str_replace("[STATE]",$order['order_state'], "$message");
	$message = str_replace("[ZIP]",$order['order_zip'], "$message");
	$message = str_replace("[COUNTRY]",$order['order_country'], "$message");
	$message = str_replace("[COMPANY_NAME]",$order['order_business_name'], "$message");


	$message = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$message");
	$message = str_replace("[INVOICE_TOTAL]",showPrice($order['order_total']), "$message");
	$message = str_replace("[DATE]",$order['order_date'], "$message");
	if($order['order_due_date'] <= 0) { 
		$message = str_replace("[DUE_DATE]","n/a", "$message");
	} else { 
		$message = str_replace("[DUE_DATE]",$order['order_due_date_show'], "$message");
	}


	if($site_setup['checkout_ssl'] == "1") { 
		$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
	} else { 
		$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
	}

	$message = str_replace("[/LINK]","</a>", $message);
	$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");



	$add .= "<p>";
	while($sc = mysqli_fetch_array($scs)) { 
		if($sc['payment'] > 0) { 
			$add .= showPrice($sc['payment']) ." "._paid_." ".$sc['payment_date_show'];
		} else { 
			$add .= showPrice($sc['amount'])." "._due_." ".$sc['due_date_show'];
		}
		$add .= "<br>";
	 }
	$add.= "</p>";

	$message = str_replace("[SCHEDULED_PAYMENTS]",$add, "$message");



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
	
	$message = str_replace("[ORDER_TOTAL]","".showPrice($order['order_amount'])."", "$message");
	$message = str_replace("[ACCOUNT_LINK]","".$setup['url'].$setup['temp_url_folder']."?view=account", "$message");
	$message = str_replace("[ORDER_LINK]",'<a href="'.$setup['url'].$setup['temp_url_folder']."?view=order&myorder=".$order['order_id'].'">'.$setup['url'].$setup['temp_url_folder']."?view=order&myorder=".$order['order_id'].'</a>', "$message");
	$message = str_replace("[ORDER_DATE]",$order['order_date'], "$message");
	$message = str_replace("[ORDER_ITEMS]",getOrderItems($order), "$message");
	$message = str_replace("[TOTAL_ITEMS]",$total_items, "$message");
	$message = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$message");
	$message = str_replace("[SHIPPING_DATE]",date('M d, Y'), "$message");
	if(empty($order['order_shipping_option'])) { 
		$message = str_replace('id="ship1"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship2"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship3"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship4"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
	}

	if(!empty($p['p_pass_def'])) { 
		$new_login = "<br><a href=\"".$setup['url'].$setup['temp_url_folder']."/?view=account\">".$setup['url'].$setup['temp_url_folder']."/?view=account</a><br>";
		$new_login .= $p['p_email']."<br>";
		$new_login .= "Temporary password: ".$p['p_pass_def']."<br>Please change this password after you log in.<br>";
	} else { 
		$new_login ="";
	}

	$message = str_replace("[NEW_LOGIN_INFO]",$new_login, "$message");

	$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");

	$subject = str_replace("[PAYMENT_AMOUNT]","".$order['order_amount']."", "$subject");
	$subject = str_replace("[ORDER_NUMBER]",$order['order_id'], "$subject");
	$subject = str_replace("[COMPANY_NAME]",$order['order_business_name'], "$subject");
	$subject = str_replace("[FIRST_NAME]",$order['order_first_name'], "$subject");
	$subject = str_replace("[LAST_NAME]",$order['order_last_name'], "$subject");
	$subject = str_replace("[EMAIL_ADDRESS]",$order['order_email'], "$subject");
	$subject = str_replace("[ADDRESS]",$order['order_address'], "$subject");
	$subject = str_replace("[CITY]",$order['order_city'], "$subject");
	$subject = str_replace("[STATE]",$order['order_state'], "$subject");
	$subject = str_replace("[ZIP]",$order['order_zip'], "$subject");
	$subject = str_replace("[COUNTRY]",$order['order_country'], "$subject");
	$subject = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$subject");

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
	$subject = str_replace("[ORDER_ITEMS]",getOrderItems($order), "$subject");
	$subject = str_replace("[TOTAL_ITEMS]",$total_items, "$subject");
	$subject = str_replace("[REGISTRATION_KEY]",$email_replace_key, "$subject");
	$subject = str_replace("[SHIPPING_DATE]",date('M d, Y'), "$subject");




?>
	<div id="roundedForm">
		<div class="row">
		<?php 
		if(empty($em['email_from_name'])) { 
			$from_name = $site_setup['website_title'];
		} else { 
			$from_name = $em['email_from_name'];
		}
		if(empty($em['email_from_email'])) { 
			$from_email = $site_setup['contact_email'];
		} else { 
			$from_email = $em['email_from_email'];
		}
		?>
		<input type="hidden" name="send_to_email" value="<?php print $order['order_email'];?>">
		<input type="hidden" name="send_to_name" value="<?php print htmlspecialchars(stripslashes($order['order_first_name']." ".$order['order_last_name']));?>">
		<input type="hidden" name="send_from_email" value="<?php print $from_email;?>">
		<input type="hidden" name="send_from_name" value="<?php print htmlspecialchars(stripslashes($from_name));?>">
		To: <?php print $order['order_first_name']." ".$order['order_last_name'];?> &lt;<?php print $order['order_email'];?>><br>
		From: <?php print $from_name;?> &lt;<?php print $from_email;?>>
		</div>

		<div class="row">
			<input type="text" name="email_subject" id="email_subject" value="<?php print htmlspecialchars($subject);?>" class="field100">
		</div>
		<div class="row">
		<textarea name="email_message" id="email_message" rows="30" cols="40" class="field100"><?php print $message;?></textarea>
			<?php 
			$email_style = true;
			addEditor("email_message", "1", "500", "1"); ?>

		</div>
	</div>
	<div>&nbsp;</div>


</div>
<div class="clear"></div>
<div>&nbsp;</div>
</form>
</div>