<?php 
$store = doSQL("ms_store_settings", "*", "");
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".$_REQUEST['order_id']."' "); 
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
<div class="pc newtitles"><span>Order # <?php print $order['order_id']; ?></span></div> 

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
		$and_archive = ", order_status='1', order_open_status='0'  ";
		$and_message .= "Order has been archived. ";
	}
	updateSQL("ms_orders", "order_shipped_by_id='".$ship['ship_id']."',order_shipped_by='".$ship_name."', order_shipped_date='".date('Y-m-d H:i:s')."', order_shipped_track='".$_REQUEST['ship_track']."', order_ship_cost='".$_REQUEST['order_ship_cost']."' $and_archive WHERE order_id='".$order['order_id']."' ");
	if($_REQUEST['send_email'] == "1") { 
		sendWebdEmail($_REQUEST['send_to_email'], stripslashes($_REQUEST['send_to_name']), $_REQUEST['send_from_email'], stripslashes($_REQUEST['send_from_name']), stripslashes($_REQUEST['email_subject']), stripslashes($message),'1');
		$and_message .= "Email sent to ".$_REQUEST['send_to_email'].". '";
	}

	if($_REQUEST['archive'] == "1") { 
		archiveordertable($order);
	}
	$_SESSION['sm'] = "Shipping added to order. $and_message "; 
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
		<div class="row">
			<div class="fieldLabel">Select Shipping Method</div>
			<div>
			<select name="ship_id" id="ship_id" onChange="getOtherShipping();">
			<?php $ships = whileSQL("ms_shipping_options", "*", "ORDER BY ship_name ASC ");
			while($ship = mysqli_fetch_array($ships)) { ?>
			<option value="<?php print $ship['ship_id'];?>" <?php if($ship['ship_id'] == $ls['order_shipped_by_id']) { print "selected"; } ?>><?php print $ship['ship_name'];?></option>
			<?php } ?>
			<option value="0">Other</option>
			</select>
			</div>
	</div>

		<div class="row" id="ship_other" style="display: none;">
			<div class="fieldLabel">Enter in the name of the shipping method.</div>
			<div><input type="text" name="ship_other" id="ship_other" class="field100"></div>
		</div>

		<div class="row">
			<div class="fieldLabel">Tracking Number</div>
			<div><input type="text" name="ship_track" id="ship_track" class="field100"></div>
			<div class="muted">When you send the email the tracking number will be added to the email where the [TRACKING_INFORMATION] code is.</div>
		</div>
	<!-- 
		<div class="row">
			<div class="fieldLabel">Your cost</div>
			<div><input type="text" name="order_ship_cost" id="order_ship_cosr" class="center" size="6"><br>Do not enter the currency sign.</div>
		</div>
-->
	<div class="row">
	<input type="checkbox" name="send_email" id="send_email" value="1" checked> Send email to customer<br>
	</div>
	<div class="row">
	<input type="checkbox" name="archive" id="archive" value="1" checked> Archive Order<br>
	</div>
	<div class="row center">
	<input type="hidden" name="do" value="orders">
	<input type="hidden" name="action" value="addshipping">
	<input type="hidden" name="send" value="yes">
	<input type="hidden" name="order_id" value="<?php print $order['order_id'];?>">
	<input type="submit" name="submit" value="Add Shipping" class="submit">
	</div>
	</div>

</div>

<div style="width: 70%; float: left;">

<?php $em = doSQL("ms_emails", "*", "WHERE email_id='18' "); ?>

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
	$message = str_replace("[SHIPPING_DATE]",date('M d, Y'), "$message");
	if(empty($order['order_shipping_option'])) { 
		$message = str_replace('id="ship1"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship2"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship3"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
		$message = str_replace('id="ship4"', 'style="display: none !important; color: #FFFFFF !important; visibility: hidden !important;"',$message);
	}



	$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",$site_setup['website_title'], "$subject");

	$subject = str_replace("[PAYMENT_AMOUNT]","".$order['order_amount']."", "$subject");
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