<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<?php $payopt['pay_num'] = trim($payopt['pay_num']); ?>
<script type="text/javascript">
  // This identifies your website in the createToken call below
  Stripe.setPublishableKey('<?php print trim($payopt['pay_num']);?>');
  // ...
	var stripeResponseHandler = function(status, response) {
	  var $form = $('#checkout');

	  if (response.error) {
		// Show the errors on the form
		$form.find('.payment-errors').text(response.error.message).addClass("error");
		$form.find('button').prop('disabled', false).removeClass("disabledinput");
	  } else {
		// token contains id, last4, and card type
		var token = response.id;
		// Insert the token into the form so it gets submitted to the server
		$form.append($('<input type="hidden" name="stripeToken" />').val(token));
		// and submit
		$form.get(0).submit();
	  }
	};

  jQuery(function($) {
  $('#checkout').submit(function(event) {
	  if($("#stripe-sel").attr("checked")) { 
		var $form = $(this);
		$form.find('.payment-errors').text("").removeClass("error");
		// Disable the submit button to prevent repeated clicks
		$form.find('button').prop('disabled', true).addClass("disabledinput");
		Stripe.card.createToken($form, stripeResponseHandler);
		// Prevent the form from submitting with the default action
		return false;
	  }
  });
});

</script>

<div class="payment-errors"></div>
<div id="stripe"  class="payoption" <?php if($default !== "stripe") { ?>style="display: none;"<?php } ?>>
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>
	<?php if($order['order_id'] > 0) { 
		include $setup['path']."/sy-inc/store/payment/payment.add.name.address.php"; 
	}
	?>
	<input type="hidden" data-stripe="name" id="stripe-name">

	<div>
		<div class="pc"><?php print _checkout_credit_cart_number_;?></div>
		<div class="pc"><input type="text" name="creditcardstripe" size="20" class="cardenter stripe" data-stripe="number" id="creditcardstripe" value=""  errormessage="You did not enter a credit card number"></div>
	</div>

	<?php if(!empty($payopt['pay_cards'])) { ?>
	<div>
		<div class="pc"><?php print _checkout_card_type_;?></div>
		<div class="pc">
		<select name="card_typeanet"  class="cardenter">
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
	<?php } ?>

	<div>
		<div class="pc"><?php print _checkout_expiration_date_;?></div>
		<div class="pc">
			 <select name="monthstripe" class="textfield cardenter"  data-stripe="exp-month">
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

			 <select name="yearstripe"  class="textfield" data-stripe="exp-year">
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
		<div class="pc"><input type="text" name="cvvstripe" id="cvvstripe" size="4" value=""  class="cardenter stripe"  errormessage="You did not enter a CVV number"  data-stripe="cvc"></div>
	</div>
<div>&nbsp;</div>


<div class="pc" id="cardSubmit-anet">
	<button type="submit" name="continueCheckout" onClick="return checkForm('stripe','<?php print $store['require_terms_conditions'];?>');"  class="checkout"><?php print $payopt['pay_text'];?></button>
	<?php if($paying_invoice == true) { ?>
	<br><a href="" onclick="closeSelectPaymentFormOrder(); return false;"><?php print _cancel_;?></a>
	<?php } else { ?>
	<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
	<?php } ?>
</div>

<div class="pc" id="cardSubmitLoading-anet" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>
<?php
if (strpos($payopt['pay_num'],'test') !== false) {
?><div class="error">TEST MODE ON - NO PAYMENT WILL BE PROCESSED</div>
<?php } ?>

</div>