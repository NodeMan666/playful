<script type="text/javascript">

function SubmitForm()
{
	document.myform.submit();
}


	function selectPaymentOption(thisopt) { 
	$(".payoption").each(function(i){
		var this_id = this.id;
		$("#"+this_id).hide();
	} );
	$("#"+thisopt).show();
}




function checkForm(pay) { 
	var noreturn = false;
	var er_mess = "Your have errors \r\n";

	if (document.getElementById('first_name').value == "") {
		er_mess += "First name is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('last_name').value == "") {
		er_mess += "Last name is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('email_address').value == "") {
		er_mess += "Email address is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('email_address_2').value == "") {
		er_mess += "Re-type email address name is blank \r\n";
		noreturn = true;
	}


	if (document.getElementById('address').value == "") {
		er_mess += "Address is blank \r\n";
		noreturn = true;
	}

	if (document.getElementById('city').value == "") {
		er_mess += "City is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('state').value == "") {
		er_mess += "State is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('zip').value == "") {
		er_mess += "Zip / postal code is blank \r\n";
		noreturn = true;
	}
	if (document.getElementById('country').value == "") {
		er_mess += "Country is blank \r\n";
		noreturn = true;
	}

	if (document.getElementById('email_address').value !== "") { 
		if (document.getElementById('email_address').value !== document.getElementById('email_address_2').value) {
			er_mess += "The email address you entered does not match the re-typed email address. Please check for errors.\r\n";
			noreturn = true;
		}
	}

	if(pay == "card") {
		if (document.getElementById('creditcard').value == "") {
			er_mess += "You did not enter a credit card number \r\n";
			noreturn = true;
		}
		if (document.getElementById('cvv').value == "") {
			er_mess += "You did not enter a CVV code \r\n";
			noreturn = true;
		}


	}


	if(noreturn == true) {
		alert(er_mess);
		return false;
	} else {

	if(pay == "card") {
	document.getElementById('cardSubmit').style.display = "none";
	document.getElementById('cardSubmitLoading').style.display = 'inline';
	}
		return true;
	}
	return true;
}



</script>
<div class=pageContent>
<?php
$total = shoppingCartTotal($mssess);

	$pay_total = $total['show_cart_total'];


	$opt = doSQL("ms_payment_options", "*", "WHERE pay_id='2' ");

if($_POST['submitit'] == "yes") {
//	print "<pre>"; print_r($_POST); print "</pre>";
	//check for empty fields
		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}

	if(empty($_REQUEST['first_name'])) {
		$error['first_name'] = ""._first_name_." "._is_blank_."";
		$fclass['first_name'] = "TFtextfielderror";
	}

	if(empty($_REQUEST['last_name'])) {
		$error['last_name']  = ""._last_name_." "._is_blank_."";
		$fclass['last_name'] = "TFtextfielderror";
	}

	if(empty($_REQUEST['country'])) {
		$error['country'] = ""._country_." "._is_blank_."";
		$fclass['country'] = "TFtextfielderror";
	}

	// Check email address
	if(empty($_REQUEST['email_address'])) {
		$error['email_address']  = ""._email_address_." "._is_blank_."";
		$fclass['email_address'] = "TFtextfielderror";
	}
	if((!empty($_REQUEST['email_address']))AND($_REQUEST['email_address'] !== $_REQUEST['email_address_2'])==true) {
		$error['email_address'] = ""._email_address_." "._checkout_does_not_match_." "._retype_email_address_."";
		$fclass['email_address'] = "TFtextfielderror";
		$fclass['email_address_2'] = "TFtextfielderror";
	}
	 if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $_REQUEST['email_address'])) {
		$error['email_address'] = ""._email_address_." "._checkout_email_format_not_valid_."";
		$fclass['email_address'] = "TFtextfielderror";
	 }

if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['address']))==true) {
		$error['address'] = ""._address_." "._is_blank_."";
		$fclass['address'] = "TFtextfielderror";
}

if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['city']))==true) {
		$error['city'] = ""._city_." "._is_blank_."";
		$fclass['city'] = "TFtextfielderror";
}
if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['state']))==true) {
		$error['state'] = ""._state_." "._is_blank_."";
		$fclass['state'] = "TFtextfielderror";
}
if(($opt['pay_require_address']== "1")AND(empty($_REQUEST['zip']))==true) {
		$error['zip'] = ""._zip_." "._is_blank_."";
		$fclass['zip'] = "TFtextfielderror";
}

	
	if($site_setup['require_agree_terms']=="1") { 
		if(empty($_REQUEST['agree_to_terms'])) {
			$error['agree_to_terms']  = ""._checkout_did_not_agree_to_terms_."";
			$fclass['agree_to_terms'] = "TFtextfielderror";
		}
	}	

if(!empty($error)) {
	print "<div class=\"errorMessage\">";
	foreach($error AS $er) {
		print "<div>$er</div>";
	}
	print "</div>";
} else {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
		$_REQUEST[$id] = sql_safe(strip_tags("".$_REQUEST[$id].""));
	}

	$pend_order = insertSQL("ms_pending_orders", "order_session='".$_SESSION['ms_session']."', order_first_name='".$_REQUEST['first_name']."', order_last_name='".$_REQUEST['last_name']."', order_email='".$_REQUEST['email_address']."', order_address='".$_REQUEST['address']."', order_city='".$_REQUEST['city']."', order_state='".$_REQUEST['state']."', order_country='".$_REQUEST['country']."', order_zip='".$_REQUEST['zip']."', order_phone='".$_REQUEST['phone']."' , order_join_ml='".$_REQUEST['join_ml']."' ");
	
	$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE cart_session='".$_SESSION['ms_session']."' AND cart_coupon!='0' AND cart_order='0' ");
	if(!empty($check_in_cart['cart_id'])) {
		updateSQL("ms_cart", "cart_discount_amount='".$total['promo_discount_amount']."' WHERE cart_id='".$check_in_cart['cart_id']."' ");
	}


flush();




if($_REQUEST['payment_method'] == "paypalstandard") { 

	print "<div>&nbsp;</div><div style=\"text-algin: center;\">One moment please while you are directed to PayPal for secure payment processing. If you are not automatically redirected, <a href='#' onclick='SubmitForm();'>click here to continue.</a></div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div></div>";
	$opt = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalstandard' ");

	$time_stamp = date('Ymdhis');
	$customer_ip = getUserIP();

	print "<table  cellpadding=2 cellspacing=0 border=0><tr valign=top>";
	if($opt['test_mode'] == "1") { 
		$paypal_email = "test@sytist.com";
		print "<FORM ACTION=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\" name='myform'>";
	} else {
		$paypal_email = $opt['pay_num'];
		print "<FORM ACTION=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" name='myform'>";
	}
			print "<td>";

			print "<INPUT TYPE=\"hidden\" NAME=\"custom\" VALUE=\"".$_SESSION['ms_session']."|".$time_stamp."|".$customer_ip."|".$_POST['join_ml']."|".MD5($pend_order)."\">";


			print "<INPUT TYPE=\"hidden\" NAME=\"cancel_return\" VALUE=\"".$setup['url'].$setup['temp_url_folder']."/".$setup['store_folder']."/cart/\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"_ext-enter\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"redirect_cmd\" VALUE=\"_xclick\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"business\" VALUE=\"".$paypal_email."\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"amount\" VALUE=\"".$pay_total."\">";
			if(!empty($store['currency'])) {
				print "<INPUT TYPE=\"hidden\" NAME=\"currency_code\" VALUE=\"".$store['currency']."\">";
			}

			print "<INPUT TYPE=\"hidden\" NAME=\"item_name\" VALUE=\"".$site_setup['website_title']." sale\">";
//			print "<INPUT TYPE=\"hidden\" NAME=\"item_number\" VALUE=\"10000\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"quantity\" VALUE=1>";

			if($_SESSION['ms_client_id']<= 0) {
				$cart_session = $_SESSION['ms_session'];
			} else {
				$cart_session =$_SESSION['ms_client_id'];
			}

			print "<input type=\"hidden\" name=\"notify_url\" value=\"".$setup['url'].$setup['temp_url_folder']."/".$setup['checkout_folder']."/paypal_order.php\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"no_shipping\" VALUE=\"1\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"shipping\" VALUE=\"0.00\">";
			print "<INPUT TYPE=\"hidden\" NAME=\"tax\" VALUE=\"0\">";
			print "<input type=\"hidden\" name=\"return\" value=\"".$setup['url'].$setup['temp_url_folder']."/".$setup['store_folder']."/myorder/index.php?frompp=1&msos=".$_SESSION['ms_session']."&msok=$time_stamp\">";
			print "</td></form></tr></table>";

		echo "<script>SubmitForm();</script>";

}

if($_REQUEST['payment_method'] == "payjunction") { 


		$pj_user = $payopt['pay_num'];
		$pj_pass = $payopt['pay_key'];
include "payjunction.php";

}



















	}
}
?>
<div class=pageContent>
<div style="width: 25%; float: left;">
<div class="pageContent" id="checkoutSide">
<?php print _checkout_side_text_;?>

</div>
</div>

<div style="width: 74%; float: right;">
<div class="pageContent"><h2><?php print _checkout_your_information_;?></h2></div>
	<form method=POST name="checkout" action="index.php">
	<div style="width: 49%; float: left;">
		<div>
			<div class="pageContent"><?php print _first_name_;?></div>
			<div class="pageContent"><input type="text" name="first_name" id="first_name" size="20" value="<?php print $_REQUEST['first_name'];?>" <?php print "class=\"".$fclass['first_name']."\"";  ?> style="width: 97%;"></div>
		</div>
	</div>
	<div style="width: 49%; float: right;">
		<div>
			<div class="pageContent"><?php print _last_name_;?></div>
			<div class="pageContent"><input type="text" name="last_name" id="last_name" size="20" value="<?php print $_REQUEST['last_name'];?>" <?php print "class=\"".$fclass['last_name']."\"";  ?> style="width: 97%;"></div>
		</div>
	</div>
<div class="cssClear"></div>
	<div style="width: 49%; float: left;">
		<div>
			<div class="pageContent"><?php print _email_address_;?></div>
			<div class="pageContent"><input type="text" name="email_address" id="email_address" size="40" style="width: 97%;" value="<?php print $_REQUEST['email_address'];?>" <?php print "class=\"".$fclass['email_address']."\"";  ?>></div>
		</div>
	</div>


	<div style="width: 49%; float: right;">
		<div>
			<div class="pageContent"><?php print _retype_email_address_;?></div>
			<div class="pageContent"><input type="text" name="email_address_2" id="email_address_2" size="40" style="width: 97%;" value="<?php print $_REQUEST['email_address_2'];?>" <?php print "class=\"".$fclass['email_address_2']."\"";  ?>></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div>
		<div>
			<div class="pageContent"><?php print _address_;?></div>
			<div class="pageContent"><input type="text" name="address" id="address" size="40" style="width: 98%;"   value="<?php print $_REQUEST['address'];?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div style="width: 49%; float: left;">
		<div>
			<div class="pageContent"><?php print _city_;?></div>
			<div class="pageContent"><input type="text" name="city" id="city"  size="30" style="width: 98%;"   value="<?php print $_REQUEST['city'];?>"></div>
		</div>
	</div>


	<div style="width: 49%; float: right;">
		<div style="width: 60%; float: left;">
			<div class="pageContent"><?php print _state_;?></div>
			<div class="pageContent"><input type="text" name="state" id="state"  size="14" value="<?php print $_REQUEST['state'];?>"></div>
		</div>

		<div style="width: 40%; float: left;">
			<div class="pageContent"><?php print _zip_;?></div>
			<div class="pageContent"><input type="text" name="zip" id="zip" size="15" value="<?php print $_REQUEST['zip'];?>"></div>
		</div>
		<div class="cssClear"></div>
	</div>
<div class="cssClear"></div>

	<div>
		<div>
			<div class="pageContent"><?php print _country_;?></div>
			<div class="pageContent">
			<select name="country"  id="country" <?php print "class=\"".$fclass['country']."\"";  ?>>
			<?php
			$cts = whileSQL("ms_countries", "*", " ORDER BY def DESC, country_name ASC");
			print "<option value=\"\">"._select_country_."</option>";

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['abr']."\" "; if($_REQUEST['country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
	</div>
<div class="cssClear"></div>



<div>&nbsp;</div>

<div>
<input type="hidden" name="view" value="checkout">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="amount" value="<?php print $pay_total;?>">
<div>



<div id="paymentSelect" class="pageContent">
<div class="pageContent">
<div><h2><?php print _checkout_order_total_;?> <?php print showPrice($pay_total);?> <?php print $store['currency'];?></h2></div>
<div><h3><?php print _checkout_how_would_you_like_to_pay_;?></h3></div>
</div>


<div class="pageContent">
<?php $payopts = whileSQL("ms_payment_options", "*", "WHERE pay_status='1' ORDER BY pay_order ASC "); 
	while($payopt = mysqli_fetch_array($payopts)) { 
		$x++;
		if($x == 1) { 
			$default = $payopt['pay_option'];
		}
		?>
<input name="payment_method" id="<?php print $payopt['pay_option'];?>-sel" <?php if($x == 1) { print "checked=\"checked\""; } ?> value="<?php print $payopt['pay_option'];?>" type="radio" onClick="selectPaymentOption('<?php print $payopt['pay_option'];?>');">
<label for="<?php print $payopt['pay_option'];?>-sel">
<img src="<?php print $payopt['pay_select_graphic'];?>"   onClick="selectCardForm();" align="absmiddle">
</label>
<?php  } ?>

</div>
</div>
<div id="paypalstandard"  class="payoption" <?php if($default !== "paypalstandard") { ?>style="display: none;"<?php } ?>>
<div class="pageContent">
<?php $paypal_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalstandard'  ");
print $paypal_opt['pay_description'];?>

</div>

<div class="pageContent">
	<button type="submit" name="continueCheckout"  style="	background: #2F5D99; border: solid 1px #244E85; padding: 6px; margin: 0; cursor: pointer; color: #FFFFFF;  -moz-border-radius: 4px; border-radius: 4px; font-size: 17px;" onClick="return checkForm('paypal');"><?php print $paypal_opt['pay_text'];?></button>
</div>


</div>

<div id="payjunction"  class="payoption" <?php if($default !== "payjunction") { ?>style="display: none;"<?php } ?>>
	<div>
		<div>
			<div class="pageContent"><?php print _checkout_credit_cart_number_;?></div>
			<div class="pageContent"><input type="text" name="creditcard" size="40" style="width: 98%;"  id="creditcard" value="<?php print $_REQUEST['creditcard'];?>"></div>
		</div>
	</div>

	<div>
		<div>
			<div class="pageContent"><?php print _checkout_card_type_;?></div>
			<div class="pageContent">
			<select name="card_type">
			<option value="visa">Visa</option>
			<option value="mc">Master Card</option>
			<option value="discover">Discover</option>
			</select>
			</div>
		</div>
	</div>


	<div>
		<div>
			<div class="pageContent"><?php print _checkout_expiration_date_;?></div>
			<div class="pageContent">
                 <select name="month" class="textfield">
                 <option value="01" <?php  if($_REQUEST['month'] == "01") { print "selected"; } ?>>01</option>
                 <option value="02" <?php  if($_REQUEST['month'] == "02") { print "selected"; } ?>>02</option>
                 <option value="03" <?php  if($_REQUEST['month'] == "03") { print "selected"; } ?>>03</option>
                 <option value="04" <?php  if($_REQUEST['month'] == "04") { print "selected"; } ?>>04</option>
                 <option value="05" <?php  if($_REQUEST['month'] == "05") { print "selected"; } ?>>05</option>
                 <option value="06" <?php  if($_REQUEST['month'] == "06") { print "selected"; } ?>>06</option>
                 <option value="07" <?php  if($_REQUEST['month'] == "07") { print "selected"; } ?>>07</option>
                 <option value="08" <?php  if($_REQUEST['month'] == "08") { print "selected"; } ?>>08</option>
                 <option value="09" <?php  if($_REQUEST['month'] == "09") { print "selected"; } ?>>09</option>
                 <option value="10" <?php  if($_REQUEST['month'] == "10") { print "selected"; } ?>>10</option>
                 <option value="11" <?php  if($_REQUEST['month'] == "11") { print "selected"; } ?>>11</option>
                 <option value="12" <?php  if($_REQUEST['month'] == "12") { print "selected"; } ?>>12</option>
                 </select>&nbsp;

                 <select name="year"  class="textfield">
				 <?php 
				 $startYear = date('Y');
				 $endYear = date('Y') + 10;
				 while($startYear<=$endYear) {
					print "<option value=\"$startYear\""; if($_REQUEST['year'] == $startYear) { print "selected"; } print ">$startYear</option>";
					$startYear++;
				 }
				?>

                </select>&nbsp;&nbsp;
			</div>
		</div>
	</div>


	<div>
		<div>
			<div class="pageContent"><?php print _checkout_cvv_;?></div>
			<div class="pageContent"><input type="text" name="cvv" id="cvv" size="4" value="<?php print $_REQUEST['cvv'];?>"></div>
		</div>
	</div>



<div class="pageContent" id="cardSubmit">
	<button type="submit" name="continueCheckout" onClick="return checkForm('card');" style="	background: #2F5D99; border: solid 1px #244E85; padding: 6px; margin: 0; cursor: pointer; color: #FFFFFF;  -moz-border-radius: 4px; border-radius: 4px; font-size: 17px;"><?php print _checkout_process_order_;?></button>
</div>

<div class="pageContent" id="cardSubmitLoading" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

</div>


<!-- 
<table align="center" cellpadding="4" cellspacing="0" border=0><tr>
<td align=center>

	<button type="submit" name="continueCheckout" value="creditcard" style="border: 0; background: none; padding: 0; margin: 0; cursor: pointer;"><img src="/graphics/credit_cards.jpg" width="200" height="50" ></button>
</td><td align=center>

<div><img src="/graphics/pay_method.jpg"></div>
</td><td align=center>

<button type="submit" name="continueCheckout" value="payPal" style="border: 0; background: none; padding: 0; margin: 0; cursor: pointer;"><img src="/graphics/paypal_logo.gif" width="200" height="50" ></button>
</td></tr></table>

-->

<?php if(!empty($site_setup['terms_conditions'])) { ?>
	<div class="pageContent"><h3><?php print _checkout_terms_conditions_;?></h3> <a href="terms_and_conditions.php" target="_blank"><img src="/sy-graphics/print_icon.png" border=0></a></span></div>
	<div class="pageContent"><textarea name="terms_conditions" cols="30" rows="6" style="width: 97%;"><?php print $site_setup['terms_conditions'];?></textarea></div>
	<?php } ?>



</div>

</center>

</div>



</div>
<div class="cssClear"></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>





</div></div>
