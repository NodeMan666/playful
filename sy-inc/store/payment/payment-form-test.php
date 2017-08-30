<div id="cardtestonly"  class="payoption" <?php if($default !== "cardtestonly") { ?>style="display: none;"<?php } ?>>
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>
	<?php if($order['order_id'] > 0) { 
		include $setup['path']."/sy-inc/store/payment/payment.add.name.address.php"; 
	}
	?>
	<div>
		<div class="pc"><?php print _checkout_credit_cart_number_;?></div>
		<div class="pc"><input type="text" name="creditcardcardtestonly" size="20" class="cardenter cardtestonly"  id="creditcardcardtestonly" errormessage="You did not enter a credit card number" value="4081000011112222"></div>
	</div>

	<div>
		<div class="pc"><?php print _checkout_card_type_;?></div>
		<div class="pc">
		<select name="card_type"  class="cardenter">
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
			 <select name="monthcardtestonly" class="textfield cardenter cardtestonly">
			 <option value="01" >01</option>
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
			 <option value="12" selected>12</option>
			 </select>&nbsp;

			 <select name="yearcardtestonly"  class="textfield">
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
		<div class="pc"><input type="text" name="cvvcardtestonly" id="cvvcardtestonly" size="4" value="123"  class="cardenter cardtestonly" errormessage="You did not enter a CVV number" ></div>
	</div>
<div>&nbsp;</div>

<div class="pc"><input type="checkbox" name="test_decline" value="1"> Test declined transaction</div>
<div class="pc" id="cardSubmit-cardtestonly">
	<button type="submit" name="continueCheckout" onClick="return checkForm('cardtestonly','<?php print $store['require_terms_conditions'];?>');" class="checkout">Process Order</button>
		<?php if($paying_invoice == true) { ?>
		<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
		<?php } else { ?>
		<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
		<?php } ?>
</div>

<div class="pc" id="cardSubmitLoading-cardtestonly" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

</div>