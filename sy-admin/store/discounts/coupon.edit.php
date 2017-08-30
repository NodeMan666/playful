<div id="pageTitle"><a href="index.php?do=discounts">Discounts</a> <?php print ai_sep;?> Coupons <?php print ai_sep;?> Edit Coupon</div>
<div>&nbsp;</div>


<div id="roundedFormContain">

<?php if($_REQUEST['saveform'] == "yes") { 

	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$req[$id] = addslashes(stripslashes($value));
		} else { 
			$req[$id] = array();
			foreach($_REQUEST[$id] AS $val) { 
				array_push($req[$id],$val);
			}
		}
	}
	if(!empty($req['code_id'])) {
		$and_code = "AND code_id!='".$req['code_id']."' ";
	}

	$ck = doSQL("ms_promo_codes", "*", "WHERE code_code='".$req['code_code']."' $and_code ");
	if(!empty($ck['code_id'])) { 
		print "<div class=\"errorMessage\">That coupon code '".$req['code_code']."' is already in the system. Enter in a different code for this coupon.</div>";
		form();
	} else { 

		if(empty($_REQUEST['code_id'])) { 
			$id = insertSQL("ms_promo_codes", "code_discount_type='percentage', code_name='".$req['code_name']."' , code_code='".$req['code_code']."' , code_discount_amount='".$req['code_discount_amount']."' , code_end_date='".$req['code_end_date']."'   ");
		} else { 

			$id = updateSQL("ms_promo_codes", "code_discount_type='percentage', code_name='".$req['code_name']."' , code_code='".$req['code_code']."' , code_discount_amount='".$req['code_discount_amount']."' , code_end_date='".$req['code_end_date']."'  WHERE code_id='".$_REQUEST['code_id']."' ");
			$id = $req['code_id'];
		}
		$_SESSION['sm'] = "Coupon saved";
		session_write_close();
		header("location: index.php?do=discounts");
		exit();
	}
} else { 
	form();
}
?>
</div>
<?php function form() { 
	global $setup,$site_setup;
	if((!empty($_REQUEST['code_id']))AND(empty($_REQUEST['check']))==true) {
		$prod = doSQL("ms_promo_codes", "*", "WHERE code_id='".$_REQUEST['code_id']."' ");
		foreach($prod AS $fname => $val) {
			if(!is_numeric($fname)) {
				$req[$fname] = $val;
			}
		}
	} else { 
		foreach($_REQUEST AS $id => $value) {
			$req[$id] = addslashes(stripslashes($value));
		}
	}
?>

	<form method="post" name="theform" action="index.php" id="theform"  onSubmit="return checkForm();">
	<input type="hidden" name="submited" id="submited" value="0">
	<input type="hidden" name="do" id="do" value="discounts">
	<input type="hidden" name="action" id="action" value="editCoupon">
	<input type="hidden" name="saveform" id="saveform" value="yes">
	<input type="hidden" name="code_id" id="code_id" value="<?php print $_REQUEST['code_id'];?>">

	<div id="roundedForm">
		<div class="row">
			<div class="left" style="width: 50%;">
				<div class="fieldLabel">Coupon Name</div>
				<div><input type="text" id="code_name" name="code_name" size="40" value="<?php  print htmlspecialchars(stripslashes($req['code_name']));?>"  class="field100 inputtitle required" ></div>
			</div>
			<div class="left" style="width: 50%;">
				<div class="fieldLabel">Coupon Code</div>
				<div><input type="text" id="code_code" name="code_code" size="40" value="<?php  print htmlspecialchars(stripslashes($req['code_code']));?>"  class="field100 inputtitle required" title=""></div>
				<div class="fieldDescription">This is the code the customer will enter to redeem the coupon. Use only letters and numbers and no spaces is best.</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="row">
			<div class="left" style="width: 50%;">
				<div class="fieldLabel">Discount Amount (Percentage).</div>
				<div><input type="text" id="code_discount_amount" name="code_discount_amount" size="10" value="<?php  print htmlspecialchars(stripslashes($req['code_discount_amount']));?>"  class="required" >%</div>
			</div>
			<div class="left" style="width: 50%;">
				<div class="fieldLabel">Expiration Date</div>
				<div><input type="text" id="code_end_date" name="code_end_date" size="20" value="<?php  print htmlspecialchars(stripslashes($req['code_end_date']));?>"  class="datepicker" title=""></div>
				<div class="fieldDescription">YYYY-MM-DD format. Leave blank or 0000-00-00 for no expiration date.</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
		<div>&nbsp;</div>
		<div class="pageContent center">
		<div id=""><input type="submit" name="submit" id="submitButton" value=" Save Now " class="submitBig" ></div>
		<div id="submitButtonLoading" style="display: none;"><?php print ai_loading;?></div>
		</div>



</form>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>

</div>
<?php } ?>