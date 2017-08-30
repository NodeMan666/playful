<script>
function selectpp(w) { 
	$("#ppprocreditcard").hide();
	$("#ppproexp").hide();
	if(w == "ppprod") { 
		$("#ppprocreditcard").show();
	}
	if(w == "ppproe") { 
		$("#ppproexp").show();
	}
}
</script>
<div id="paypalpro"  class="payoption" <?php if($default !== "paypalpro") { ?>style="display: none;"<?php } ?>>
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>


<div class="pc">
	<input type="radio" name="pppro" id="ppprod" value="creditcard" checked onclick="selectpp('ppprod');"> <label for="ppprod"><img src="<?php print $setup['temp_url_folder'];?>/sy-misc/creditcards/visamcdiscoveramex.jpg" align="absmiddle"></label><br><br>
	<input type="radio" name="pppro" id="ppproe" value="express" onclick="selectpp('ppproe');"> <label for="ppproe"><img src="<?php print $setup['temp_url_folder'];?>/sy-misc/creditcards/paypal.jpg" align="absmiddle"></label>
</div>

<div id="ppprocreditcard">
	<?php if($order['order_id'] > 0) { 
		include $setup['path']."/sy-inc/store/payment/payment.add.name.address.php"; 
	}
	?>	<div>
		<div class="pc"><?php print _checkout_credit_cart_number_;?></div>
		<div class="pc"><input type="text" name="creditcardpp" size="20" class="cardenter pp"  id="creditcardpp" value=""  errormessage="You did not enter a credit card number"></div>
	</div>

	<div>
		<div class="pc"><?php print _checkout_card_type_;?></div>
		<div class="pc">
		<select name="card_typepp"  class="cardenter">
		<?php $cardtypes = explode("\r\n", $payopt['pay_cards']);
		foreach($cardtypes AS $card) { 
			$card = trim($card);
			if(!empty($card)) { 
			?><option value="<?php print $card;?>"><?php print $card;?></option>
			<?php } 
		}
		?>
		</select>
		</div>
	</div>


	<div>
		<div class="pc"><?php print _checkout_expiration_date_;?></div>
		<div class="pc">
			 <select name="monthpp" class="textfield cardenter">
			 <option value="01">01</option>
			 <option value="02">02</option>
			 <option value="03">03</option>
			 <option value="04">04</option>
			 <option value="05">05</option>
			 <option value="06">06</option>
			 <option value="07">07</option>
			 <option value="08">08</option>
			 <option value="09">09</option>
			 <option value="10">10</option>
			 <option value="11">11</option>
			 <option value="12">12</option>
			 </select>&nbsp;

			 <select name="yearpp"  class="textfield">
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


	<div>
		<div class="pc"><?php print _checkout_cvv_;?></div>
		<div class="pc"><input type="text" name="cvvpp" id="cvvpp" size="4" value=""  class="cardenter pp"  errormessage="You did not enter a CVV number"></div>
	</div>
	<div>&nbsp;</div>


	<div class="pc" id="cardSubmit-pp">
		<button type="submit" name="continueCheckout" onClick="return checkForm('pp','<?php print $store['require_terms_conditions'];?>');"  class="checkout"><?php print $payopt['pay_text'];?></button>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
	</div>

	<div class="pc" id="cardSubmitLoading-pp" style="display: none; height: 50px; ">
		<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
	</div>
	</div>

<div id="ppproexp" style="display: none;">
	<div class="pc" id="cardSubmit-pp">
		<button type="submit" name="continueCheckout" onClick="return checkForm('ppexp','<?php print $store['require_terms_conditions'];?>');"  class="checkout"><?php print $payopt['pay_paypal_exp_button'];?></button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
	</div>

	<div class="pc" id="cardSubmitLoading-pp" style="display: none; height: 50px; ">
		<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
	</div>


</div>

<?php if($payopt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>
</div>