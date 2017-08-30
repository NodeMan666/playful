<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$('#code_code').change(function() {
	checkcode();
});

function checkcode() { 
	$.get("admin.actions.php?action=checkcode&code_code="+$("#code_code").val()+"&code_id="+$("#code_id").val(), function(data) {
		if(data == "exists") { 
			alert("That redeem code  "+$("#code_code").val()+" already exists for another coupon. Enter in a different redeem code.");
			$("#code_code").val("")
		}
	});
}

$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#code_name").focus();
 }


function selectbatch() { 
	$("#batchcreate").slideToggle();
	if($("#code_batch_select").attr("checked")) { 
		$("#code_batch").addClass("optrequired");
		$("#pc_start").addClass("optrequired");
		$("#pc_end").addClass("optrequired");

	} else { 
		$("#code_batch").removeClass("optrequired");
		$("#pc_start").removeClass("optrequired");
		$("#pc_end").removeClass("optrequired");
	}
}
</script>
<?php

if($_POST['submitit']=="yes") { 
	$_REQUEST['code_code'] = trim($_REQUEST['code_code']);

	if($_REQUEST['code_id'] > 0) { 
		$id = updateSQL("ms_promo_codes", "
		code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
		code_code='".addslashes(stripslashes($_REQUEST['code_code']))."' ,
		code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
		code_end_date='".$_REQUEST['code_end_date']."',
		code_use='".$_REQUEST['code_use']."',
		code_date_id='".$_REQUEST['code_date_id']."',
		code_descr='".addslashes(stripslashes($_REQUEST['code_descr']))."' ,
		code_use_status='0',
		code_min='".addslashes(stripslashes($_REQUEST['code_min']))."',
		code_price='".addslashes(stripslashes($_REQUEST['code_price']))."',
		code_taxable='".addslashes(stripslashes($_REQUEST['code_taxable']))."',
		code_no_discount='".$_REQUEST['code_no_discount']."',
		code_redeem_success='".addslashes(stripslashes($_REQUEST['code_redeem_success']))."',
		code_redeem_instructions='".addslashes(stripslashes($_REQUEST['code_redeem_instructions']))."',
		code_min_amount_error='".addslashes(stripslashes($_REQUEST['code_min_amount_error']))."',
		code_not_selected_error='".addslashes(stripslashes($_REQUEST['code_not_selected_error']))."'

		WHERE code_id='".$_REQUEST['code_id']."' ");

		updateSQL("ms_print_credits", "
		pc_name='".addslashes(stripslashes($_REQUEST['pc_name']))."' , 
		pc_code='".addslashes(stripslashes($_REQUEST['pc_code']))."' , 
		pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
		pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
		pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
		pc_expire='".addslashes(stripslashes($_REQUEST['pc_expire']))."'
		WHERE pc_id='".$_REQUEST['pc_id']."' ");
		$_SESSION['sm'] = "Print Credit Saved";
		$pc_id=$_REQUEST['pc_id'];


			$_SESSION['sm'] = "Coupon Saved";
			header("location: ../index.php?do=discounts");

		} else { 

			if($_REQUEST['code_batch_select'] == "1") { 
				while($x < $_REQUEST['pc_end']) { 
					$pc_id = insertSQL("ms_print_credits", "
					pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
					pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
					pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
					pc_coupon='1'
					");

					$code_id = insertSQL("ms_promo_codes", "
					code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
					code_code='".addslashes(stripslashes($_REQUEST['code_code'].$_REQUEST['pc_start']))."' ,
					code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
					code_end_date='".$_REQUEST['code_end_date']."',
					code_use='".$_REQUEST['code_use']."',
					code_date_id='".$_REQUEST['code_date_id']."',
					code_min='".addslashes(stripslashes($_REQUEST['code_min']))."',
					code_price='".addslashes(stripslashes($_REQUEST['code_price']))."',
					code_taxable='".addslashes(stripslashes($_REQUEST['code_taxable']))."',
					code_no_discount='".$_REQUEST['code_no_discount']."',

					code_print_credit='".addslashes(stripslashes($pc_id))."',
					code_redeem_success='".addslashes(stripslashes($_REQUEST['code_redeem_success']))."',
					code_redeem_instructions='".addslashes(stripslashes($_REQUEST['code_redeem_instructions']))."',
					code_min_amount_error='".addslashes(stripslashes($_REQUEST['code_min_amount_error']))."',
					code_not_selected_error='".addslashes(stripslashes($_REQUEST['code_not_selected_error']))."',
					code_batch='".addslashes(stripslashes($_REQUEST['code_batch']))."'

				");
					$x++;
					$_REQUEST['pc_start']++;
				}
				$_SESSION['sm'] = $_REQUEST['pc_end']." Coupons Created";
				header("location: ../index.php?do=discounts&code_batch=".$_REQUEST['code_batch']."");
			} else { 

				$pc_id = insertSQL("ms_print_credits", "
				pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
				pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
				pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
				pc_coupon='1'
				");

				$code_id = insertSQL("ms_promo_codes", "
				code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
				code_code='".addslashes(stripslashes($_REQUEST['code_code']))."' ,
				code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
				code_end_date='".$_REQUEST['code_end_date']."',
				code_use='".$_REQUEST['code_use']."',
				code_date_id='".$_REQUEST['code_date_id']."',
				code_min='".addslashes(stripslashes($_REQUEST['code_min']))."',
				code_price='".addslashes(stripslashes($_REQUEST['code_price']))."',
				code_taxable='".addslashes(stripslashes($_REQUEST['code_taxable']))."',
				code_no_discount='".$_REQUEST['code_no_discount']."',

				code_print_credit='".addslashes(stripslashes($pc_id))."',
				code_redeem_success='".addslashes(stripslashes($_REQUEST['code_redeem_success']))."',
				code_redeem_instructions='".addslashes(stripslashes($_REQUEST['code_redeem_instructions']))."',
				code_min_amount_error='".addslashes(stripslashes($_REQUEST['code_min_amount_error']))."',
				code_not_selected_error='".addslashes(stripslashes($_REQUEST['code_not_selected_error']))."'

				");

				$_SESSION['sm'] = "Bonus Coupon Created";
				header("location: ../index.php?do=discounts");
			}
		}
	session_write_close();
	exit();
}
?>


<?php 


	if(($_REQUEST['code_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$coupon = doSQL("ms_promo_codes", "*", "WHERE code_id='".$_REQUEST['code_id']."' ");
		$pc = doSQL("ms_print_credits", "*", "WHERE pc_id='".$coupon['code_print_credit']."' "); 
		if(empty($pc['pc_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($pc AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	if(($_REQUEST['code_id'] <= 0) AND(empty($_REQUEST['submitit']))==true) { 
		$last = doSQL("ms_promo_codes", "*", "WHERE code_print_credit>'0' ORDER BY code_id DESC ");
		if(empty($last['code_redeem_success'])) { 
			$coupon['code_redeem_success'] = "Your Bonus Coupon Has Been Redeemed!";
			$coupon['code_redeem_instructions'] = "To select the photo for your bonus coupon, view the photo in your gallery and you will see the option to add the photo to your bonus coupon.

Please note there is a minimum cart total of [MIN_AMOUNT] to place an order with this bonus coupon.";

			$coupon['code_min_amount_error'] = "Sorry, but your cart total does not meet the minimum amount of [MIN_AMOUNT] to redeem your bonus coupon. 

[LINK_RETURN_GALLERY]Return to my gallery to purchase more items[/LINK]

[LINK_REMOVE_COUPON]Remove coupon and continue to checkout[/LINK]";

			$coupon['code_not_selected_error'] = "You have not selected a photo for your bonus coupon.

[LINK_RETURN_GALLERY]Return to my gallery and select a photo[/LINK]

[LINK_REMOVE_COUPON]Remove coupon and continue to checkout[/LINK]";
		} else { 
			$coupon['code_redeem_success'] = $last['code_redeem_success'];
			$coupon['code_redeem_instructions'] = $last['code_redeem_instructions'];
			$coupon['code_min_amount_error'] = $last['code_min_amount_error'];
			$coupon['code_not_selected_error'] = $last['code_not_selected_error'];
		}
	}
	?>
	<div class="pc"><?php if(empty($pc['pc_id'])) { ?><h1>Create Bonus Coupon</h1><?php } else { ?><h1>Edit Bonus Coupon</h1><?php } ?></div>
	<div class="pc"><a href="https://www.picturespro.com/sytist-manual/coupons/" target="_blank">More information in the manual</a></div>
	<div class="pc">A bonus coupon is a coupon for a product or products for free or a set price. When creating a bonus coupon, you are going to be selecting a collection you have created in <a href="index.php?do=photoprods&view=packages">Photo Products -> Collections</a>. If you have not created a collection that has what you want to be included in the bonus coupon, <a href="index.php?do=photoprods&view=packages">do that first</a>.</div>


	<?php $packs = whileSQL("ms_packages", "*", "ORDER BY package_name ASC ");
	if(mysqli_num_rows($packs)<=0) { ?>
		<div class="error">You have not created any collections. You must first create a collection before you can create a bonus coupon.</div>
	<?php } else { ?>



	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">


<div style="width: 48%; float: left;">
	<div class="underline">
		<div class="label">Coupon Name</div>
		<div><input type="text" name="code_name" id="code_name" class="optrequired field100" value="<?php print $coupon['code_name'];?>" size="20"></div>
	</div>
	<?php if($date['date_id'] <= 0) { ?>
	<div class="underline">
		<div class="label">Redeem Code</div>
		<div><input type="text" name="code_code" id="code_code" class="optrequired" value="<?php print $coupon['code_code'];?>" size="12"></div>
	</div>
	<?php } ?>

		<div class="underline">
			<div class="label">Expiration Date</div>
			<div><input type="text" name="code_end_date" id="code_end_date"  class="datepicker"  value="<?php print $coupon['code_end_date'];?>"></div>
		</div>
	<?php if($date['date_id'] <= 0) { ?>

		<div class="underline">
			<div class="label">Usage</div>
			<div>
				<input type="radio" name="code_use" value="unlimited" <?php if(($coupon['code_use'] == "unlimited")||(empty($coupon['code_use']))==true)  { print "checked"; } ?>> Unlimited Use <br>
				<input type="radio" name="code_use" value="once" <?php if($coupon['code_use'] == "once")  { print "checked"; } ?>> Once<br>
				<input type="radio" name="code_use" value="onceperson" <?php if($coupon['code_use'] == "onceperson")  { print "checked"; } ?>> Once Per Person<br>
			</div>
		</div>
	<?php if(($coupon['code_use_status'] == "1")&&($coupon['code_use'] == "once")==true) { ?>
	<div class="underline">This coupon is set to one use and has been used. Click save below to reactivate this coupon.</div>
	<?php } ?>
	<?php } ?>
</div>



	<div style="width: 48%; float: right;">

		<div class="underline">
			<div class="label">Collection</div>
			<div>
			<?php $packs = whileSQL("ms_packages", "*", "WHERE package_buy_all<='0' ORDER BY package_name ASC ");
			if(mysqli_num_rows($packs)<=0) { ?>
			You have not created any collections. You must first create a collection before you can create a bonus coupon.
			<?php } else { ?>
			<select name="pc_package" id="pc_package" class="optrequired">
			<option value="">Select a collection</option>
			<?php while($pack = mysqli_fetch_array($packs)) { ?>
			<option value="<?php print $pack['package_id'];?>" <?php if($pc['pc_package'] == $pack['package_id']) { print "selected"; } ?>><?php print $pack['package_name'];?></option>
			<?php } ?>
			</select>
			<?php } ?>
			</div>
		</div>

		<div class="underline">
			<div class="label">Minimum Cart Amount</div>
			<div><input type="text" name="code_min" id="code_min"  class=" center" size="6"  value="<?php print $coupon['code_min'];?>"></div>
			<div>The minimum amount for the cart total to be able to checkout with this coupon.</div>
		</div>

		<div class="underline">
			<div class="label">Price</div>
			<div><input type="text" name="code_price" id="code_price"  class=" center" size="6"  value="<?php print $coupon['code_price'];?>"></div>
			<div>If you want there to be a charge for this coupon, enter it here. Leave it blank to be completely free.</div>
		</div>

		<div class="underline">
			<div class="label"><input type="checkbox" name="code_taxable" id="code_taxable" value="1" <?php if($coupon['code_taxable'] == "1") { print "checked"; } ?>> <label for="code_taxable">Taxable (if price added)</label></div>
		</div>
		<div class="underline">
			<div class="label"><input type="checkbox" name="code_no_discount" id="code_no_discount" value="1" <?php if($coupon['code_no_discount'] == "1") { print "checked"; } ?>> <label for="code_no_discount">Do not discount?<br>If you set a price for this coupon and don't want it discounted with a standard coupon, check this option.</label></div>
		</div>

		
		<div class="underline">
		<div class="label"><input type="checkbox" name="pc_ship" id="pc_ship" value="1" <?php if($pc['pc_ship'] == "1") { print "checked"; } ?>> <label for="pc_ship">Enable shipping?<br>Select this if you want this coupon to be available for shipping and apply any shipping charges.</label>  </div>
	</div>
</div>
<div class="clear"></div>
<?php 	if($_REQUEST['code_id'] <= 0) { ?>

	<div class="underline">
		<div class="label"><input type="checkbox" name="code_batch_select" id="code_batch_select" value="1" onchange="selectbatch();"> <label for="code_batch_select">Create a batch of Coupons</label>	</div>
	</div>
	<div id="batchcreate" class="hide">
	
		<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Batch Name</div>
			<div><input type="text" name="code_batch" id="code_batch"  class="field100"  value="<?php print $pc['code_batch'];?>"></div>
			<div>For your reference</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Start Number</div>
			<div><input type="text" name="pc_start" id="pc_start" class="" size="8" value="1000" tabindex="2"></div>
			<div>The start number gets added to the end of the redeem code. Example, your redeem code is testing and the start number is 1000, then it will become testing1000,testing1001, etc... Be sure the start number starts with a number higher than 0. Example: 1234</div>
		</div>
	</div>

	<div class="clear"></div>

		<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">How many do you want to create?</div>
			<div><input type="text" name="pc_end" id="pc_end"  class="" size="8"  value="" ></div>
		</div>
	</div>
	<div class="clear"></div>


	</div>
	<?php } ?>


	<div class="underline">
		<div class="label">Redeem Success Title</div>
		<div><input type="text" name="code_redeem_success" id="code_redeem_success" class="optrequired field100" value="<?php print htmlspecialchars($coupon['code_redeem_success']);?>" size="12" ></div>
	</div>
	<div class="underline">
		<div class="label">Instructions to select photos for bonus coupon</div>
		<div><textarea name="code_redeem_instructions" id="code_redeem_instructions" class="optrequired field100" rows="4"><?php print htmlspecialchars($coupon['code_redeem_instructions']);?></textarea></div>
		<div>Use the bracket code: [MIN_AMOUNT] to automatically display the minimum cart amount.</div>
	</div>
	<div class="underline">
		<div class="label">Message if checking out and coupon does not meet minimum order amount</div>
		<div><textarea name="code_min_amount_error" id="code_min_amount_error" class="optrequired field100" rows="4"><?php print htmlspecialchars($coupon['code_min_amount_error']);?></textarea></div>
	</div>
	<div class="underline">
		<div class="label">Message if checking out and photo not selected for coupon</div>
		<div><textarea name="code_not_selected_error" id="code_not_selected_error" class="optrequired field100" rows="4"><?php print htmlspecialchars($coupon['code_not_selected_error']);?></textarea></div>
	</div>
<div class="underlinespacer">In the above 2 fields, use the bracket codes to create links to return to gallery and remove coupon from cart.
<br>[LINK_RETURN_GALLERY]  & [LINK_REMOVE_COUPON] open the links. [/LINK] closes the links. Example: <br>

[LINK_RETURN_GALLERY]Return to my gallery and select a photo[/LINK]
</div>

	<div class="pageContent center">

	<input type="hidden" name="code_id" id="code_id" value="<?php print $coupon['code_id'];?>">
	<input type="hidden" name="pc_id" id="pc_id" value="<?php print $pc['pc_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['pc_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php } ?>
<?php require "../w-footer.php"; ?>
