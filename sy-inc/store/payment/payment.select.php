<?php	
$and_where = "";
if(($order['order_offline'] > 0)||($order['order_invoice'] == "1")==true) { 
	$and_where = "AND pay_option!='payoffline' AND pay_option!='payoffline2' ";
}
$disabled_payment_options = array();
$pay_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id","*","WHERE ".checkCartSession()." AND disabled_payment_options!='' AND cart_order<='0' ");
if(!empty($pay_check['date_id'])) { 
	$disabled_payment_options = explode(",",$pay_check['disabled_payment_options']);
	foreach($disabled_payment_options AS $po) { 
		$and_disabled .= " AND pay_option!='".$po."' ";
	}
}
$pay_check = doSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id","*","WHERE ".checkCartSession()." AND disabled_payment_options!='' AND cart_order<='0' ");
if(!empty($pay_check['date_id'])) { 
	$disabled_payment_options = explode(",",$pay_check['disabled_payment_options']);
	foreach($disabled_payment_options AS $po) { 
		$and_disabled .= " AND pay_option!='".$po."' ";
	}
}

?>

<div class="pc paymentoptionsavailable">
<?php $payopts = whileSQL("ms_payment_options", "*", "WHERE pay_status='1' AND pay_option!='paypalexpress'  $and_where $and_disabled ORDER BY pay_order ASC "); 
	while($payopt = mysqli_fetch_array($payopts)) { 
		if(mysqli_num_rows($payopts) == "1") { ?>
		<input type="radio" name="payment_method" id="<?php print $payopt['pay_option'];?>-sel" value="<?php print $payopt['pay_option'];?>" checked style="display: none;">
		<?php 
			$default = $payopt['pay_form'];
		} else { 
		$x++;
		if($x == 1) { 
			$default = $payopt['pay_form'];
		}
			?>
		<nobr><input name="payment_method" id="<?php print $payopt['pay_option'];?>-sel" <?php if($x == 1) { print "checked=\"checked\""; } ?> value="<?php print $payopt['pay_option'];?>" type="radio" onClick="selectPaymentOption('<?php print $payopt['pay_option'];?>','<?php print $payopt['pay_form'];?>');">
		<label for="<?php print $payopt['pay_option'];?>-sel">
		<?php if(!empty($payopt['pay_select_graphic'])) { ?>
		<img src="<?php print $payopt['pay_select_graphic'];?>"  align="absmiddle">
		<?php } else { ?>
		<span class="large2"><b><?php print $payopt['pay_title'];?></b></span>
		<?php } ?>
		</label> &nbsp; </nobr>
	<?php } ?>
<?php  } ?>

</div>
<?php if($store['require_terms_conditions'] == "1") { ?>
	<div class="pc"><input type="checkbox" id="agreeterms" name="agreeterms" value="1"> <a href="" onclick="viewtermsconditions(); return false;" style="text-decoration: underline;"><?php print $store['terms_conditions_link'];?></a></div>
	<?php } ?>

<div id="paypalstandard"  class="payoption paymentoptionsavailable" <?php if($default !== "paypalstandard") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalstandard'  "); ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>


<div class="pc paymentoptionsavailable">
	<div class="pc" id="cardSubmit-paypal">
		<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('paypal', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
	
	</div>
	<div class="pc" id="cardSubmitLoading-paypal" style="display: none; height: 50px; ">
		<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
	</div>
<?php if($paypal_opt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

</div>
</div>


<div id="sisow"  class="payoption paymentoptionsavailable" <?php if($default !== "sisow") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='sisow'  "); ?>
<?php if($paypal_opt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>
<div class="pc">
<div class="pc" id="cardSubmit-sisow">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('sisow', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-sisow" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

	
	
</div>
</div>


<div id="authorizenetsim"  class="payoption paymentoptionsavailable" <?php if($default !== "authorizenetsim") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='authorizenetsim'  "); ?>
<?php if($paypal_opt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>
<div class="pc">
<div class="pc" id="cardSubmit-authorizenetsim">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('authorizenetsim', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-authorizenetsim" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

	
	
</div>
</div>



<div id="apco"  class="payoption paymentoptionsavailable" <?php if($default !== "apco") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='apco'  "); ?>
<?php if($paypal_opt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>
<div class="pc">
<div class="pc" id="cardSubmit-apco">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('apco', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-apco" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

	
	
</div>
</div>







<div id="payoffline"  class="payoption paymentoptionsavailable" <?php if($default !== "payoffline") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline'  "); ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>


<div class="pc paymentoptionsavailable" id="cardSubmit-payoffline">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('payoffline', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-payoffline" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

</div>



<div id="payoffline2"  class="payoption paymentoptionsavailable" <?php if($default !== "payoffline2") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline2'  "); ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>


<div class="pc paymentoptionsavailable" id="cardSubmit-payoffline2">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('payoffline2', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-payoffline2" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

</div>




<div id="worldpay"  class="payoption paymentoptionsavailable" <?php if($default !== "worldpay") { ?>style="display: none;"<?php } ?>>
<div class="pc">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='worldpay'  "); ?>
<?php if($paypal_opt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

<?php print nl2br($paypal_opt['pay_description']);?>
</div>
<div>&nbsp;</div>
<div class="pc">
<div class="pc" id="cardSubmit-worldpay">
	<button type="submit" name="continueCheckout" class="checkout" onClick="return checkForm('worldpay', '<?php print $store['require_terms_conditions'];?>');"><?php print $paypal_opt['pay_text'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>
<div class="pc" id="cardSubmitLoading-authorizenetsim" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

	
	
</div>
</div>









<div id="paymentoptionsavailable" class="paymentoptionsavailable">
<?php $payopts = whileSQL("ms_payment_options", "*", "WHERE pay_status='1' AND pay_option!='paypalexpress'  $and_where ORDER BY pay_order ASC "); 
	while($payopt = mysqli_fetch_array($payopts)) { 
		if($payopt['pay_option'] == "emailform") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-manual.php";
		}
		if($payopt['pay_option'] == "testonly") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-test.php";
		}
		if($payopt['pay_option'] == "payjunction") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-payjunction.php";
		}
		if($payopt['pay_option'] == "paypalpro") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-paypal-pro-direct.php";
		}
		if($payopt['pay_option'] == "authorizenetaim") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-anet-aim.php";
		}
		if($payopt['pay_option'] == "eway") { 
			require $setup['path']."/sy-inc/store/payment/eway/payment-form-eway.php";
		}

		if($payopt['pay_option'] == "stripe") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-stripe.php";
		}
		if($payopt['pay_option'] == "square") { 
			require $setup['path']."/sy-inc/store/payment/payment-form-square.php";
		}

	}
	?>
</div>
<div id="payzerototal" style="display: none;">
<?php require $setup['path']."/sy-inc/store/payment/payment-no-pay.php"; ?>
<input type="hidden" name="pay_total_zero" id="pay_total_zero" value="0">
</div>
