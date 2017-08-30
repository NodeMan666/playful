<div id="zerototal">
<?php if(!empty($payopt['pay_description'])) { ?>
	<div class="pc"><?php print nl2br($payopt['pay_description']);?></div>
	<?php } ?>

<div class="pc" id="cardSubmit-cardtestonly">
	<div class="pc"><?php print _place_order_no_payment_text_;?></div>
	<div class="pc">
	<button type="submit" name="continueCheckout" onClick="return checkForm('cardtestonly','<?php print $store['require_terms_conditions'];?>');" class="checkout"><?php print _place_order_no_payment_;?></button>
	<br><a href="" onClick="editInfo(); return false;">&larr; <?php print _cancel_;?></a>
	</div>
</div>

<div class="pc" id="cardSubmitLoading-cardtestonly" style="display: none; height: 50px; ">
	<img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24">
</div>

</div>