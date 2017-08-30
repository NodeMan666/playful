<div id="payjunction"  class="payoption" <?php if($default !== "payjunction") { ?>style="display: none;"<?php } ?>>
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>
	<?php if($order['order_id'] > 0) { 
		include $setup['path']."/sy-inc/store/payment/payment.add.name.address.php"; 
	}
	?>
	<div>
		<div class="pc"><?php print _checkout_credit_cart_number_;?></div>
		<div class="pc"><input type="text" name="creditcardpj" size="20" class="cardenter pj"  id="creditcardpj" value=""  errormessage="You did not enter a credit card number"></div>
	</div>

	<div>
		<div class="pc"><?php print _checkout_card_type_;?></div>
		<div class="pc">
		<select name="card_typepj"  class="cardenter">
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
			 <select name="monthpj" class="textfield cardenter">
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

			 <select name="yearpj"  class="textfield">
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
		<div class="pc"><input type="text" name="cvvpj" id="cvvpj" size="4" value=""  class="cardenter pj"  errormessage="You did not enter a CVV number"></div>
	</div>
<div>&nbsp;</div>


<div class="pc" id="cardSubmit-pj">
	<button type="submit" name="continueCheckout" onClick="return checkForm('pj','<?php print $store['require_terms_conditions'];?>');"  class="checkout"><?php print $payopt['pay_text'];?></button>
	<?php if($paying_invoice == true) { ?>
	<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
	<?php } else { ?>
	<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
	<?php } ?>
</div>

<div class="pc" id="cardSubmitLoading-pj" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>
<?php if($payopt['test_mode'] == "1") { ?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div><?php } ?>

</div>