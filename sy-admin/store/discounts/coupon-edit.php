<?php 
$path = "../../../";
require "../../w-header.php"; 
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

?>
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
	if($_REQUEST['code_id'] > 0) { 

		updateSQL("ms_promo_codes", "
		code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
		code_code='".addslashes(stripslashes($_REQUEST['code_code']))."' ,
		code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
		code_end_date='".$_REQUEST['code_end_date']."',
		code_use='".$_REQUEST['code_use']."',
		code_date_id='".$_REQUEST['code_date_id']."',
		code_descr='".addslashes(stripslashes($_REQUEST['code_descr']))."' ,
		code_use_status='0' 

		WHERE code_id='".$_REQUEST['code_id']."' ");
		if($_REQUEST['code_date_id'] > 0) { 
			$_SESSION['sm'] = "Early Bird Saved";
		} else { 
			$_SESSION['sm'] = "Coupon Saved";
		}
		$code_id = $_REQUEST['code_id'];
		$sql = "DELETE FROM ms_promo_codes_discounts WHERE dis_promo='" .$_REQUEST['code_id']. "'";
		if(@mysqli_query($dbcon,$sql)) { } else { echo("Error adding > " . mysqli_error($dbcon) . " < that error"); }

		foreach($_REQUEST['dis_amount'] AS $id => $price) {
			$thisCount++;
			//print "<li>$price";
			if(!empty($price)) {
				if($_REQUEST['code_discount_type'] == "percentage") { 
					$in = insertSQL("ms_promo_codes_discounts", " dis_percent='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
				}
				if($_REQUEST['code_discount_type'] == "flat") { 
					$in = insertSQL("ms_promo_codes_discounts", " dis_flat='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
				}
			}
		}

	} else {
		if($_REQUEST['code_batch_select'] == "1") { 
			while($x < $_REQUEST['pc_end']) { 
				$code_id = insertSQL("ms_promo_codes", "
				code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
				code_code='".addslashes(stripslashes($_REQUEST['code_code'].$_REQUEST['pc_start']))."' ,
				code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
				code_end_date='".$_REQUEST['code_end_date']."',
				code_use='".$_REQUEST['code_use']."',
				code_date_id='".$_REQUEST['code_date_id']."',
				code_batch='".addslashes(stripslashes($_REQUEST['code_batch']))."'

				");
				$x++;
				$_REQUEST['pc_start']++;
				foreach($_REQUEST['dis_amount'] AS $id => $price) {
					$thisCount++;
					//print "<li>$price";
					if(!empty($price)) {
						if($_REQUEST['code_discount_type'] == "percentage") { 
							$in = insertSQL("ms_promo_codes_discounts", " dis_percent='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
						}
						if($_REQUEST['code_discount_type'] == "flat") { 
							$in = insertSQL("ms_promo_codes_discounts", " dis_flat='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
						}
					}
				}
			}
			$_SESSION['sm'] = $_REQUEST['pc_end']." Coupons Created";
			header("location: ../../index.php?do=discounts&code_batch=".$_REQUEST['code_batch']."");
			exit();
		} else { 
			$code_id = insertSQL("ms_promo_codes", "
			code_name='".addslashes(stripslashes($_REQUEST['code_name']))."' , 
			code_code='".addslashes(stripslashes($_REQUEST['code_code']))."' ,
			code_discount_type='".addslashes(stripslashes($_REQUEST['code_discount_type']))."',
			code_end_date='".$_REQUEST['code_end_date']."',
			code_use='".$_REQUEST['code_use']."',
			code_date_id='".$_REQUEST['code_date_id']."',
			code_descr='".addslashes(stripslashes($_REQUEST['code_descr']))."' 
			");

			$sql = "DELETE FROM ms_promo_codes_discounts WHERE dis_promo='" .$_REQUEST['code_id']. "'";
			if(@mysqli_query($dbcon,$sql)) { } else { echo("Error adding > " . mysqli_error($dbcon) . " < that error"); }

			foreach($_REQUEST['dis_amount'] AS $id => $price) {
				$thisCount++;
				//print "<li>$price";
				if(!empty($price)) {
					if($_REQUEST['code_discount_type'] == "percentage") { 
						$in = insertSQL("ms_promo_codes_discounts", " dis_percent='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
					}
					if($_REQUEST['code_discount_type'] == "flat") { 
						$in = insertSQL("ms_promo_codes_discounts", " dis_flat='$price', dis_from='".$_REQUEST['dis_from'][$id]."', dis_to='".$_REQUEST['dis_to'][$id]."', dis_promo='".$code_id."' ");
					}
				}
			}


			if($_REQUEST['code_date_id'] > 0) { 
				$_SESSION['sm'] = "Early Bird Created";
			} else { 
				$_SESSION['sm'] = "Coupon Created";
			}
		}

	}

	if($_REQUEST['code_date_id'] > 0) { 
		header("location: ../../index.php?do=news&action=addDate&date_id=".$_REQUEST['code_date_id']."");
	} else { 
		header("location: ../../index.php?do=discounts");
	}
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<?php if($_REQUEST['showDeleteSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Deleted");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>


<?php 
if($_REQUEST['code_date_id'] > 0) { 
	$date = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['code_date_id']."' ");
}
	if($_REQUEST['code_id'] > 0) { 
		$coupon = doSQL("ms_promo_codes", "*", "WHERE code_id='".$_REQUEST['code_id']."' ");


		$prices = whileSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$coupon['code_id']."' ORDER BY dis_id ASC ");
		$pcount = 1;
		while($price = mysqli_fetch_array($prices)) {
			if($coupon['code_discount_type'] == "percentage") { 
				$_REQUEST['dis_amount'][$pcount] = $price['dis_percent'];
			}
			if($coupon['code_discount_type'] == "flat") {
				$_REQUEST['dis_amount'][$pcount] = $price['dis_flat'];
			}

			$_REQUEST['dis_from'][$pcount] = $price['dis_from'];
			$_REQUEST['dis_to'][$pcount] = $price['dis_to'];
			$pcount++;
		}

	}
	?>
	<?php if($date['date_id'] > 0) { ?>
	<div class="pc"><h1>Early Bird Special for <?php print $date['date_title'];?></h1></div>
	<?php if($coupon['code_id'] <= 0 ) { ?><div class="pc specialMessage">Make any changes below and click Save to create the Early Bird Special.</div><?php } ?>
	<?php } else { ?>
	<div class="pc"><h1>Edit Coupon</h1></div>
	<?php } ?>
	<?php if((empty($coupon['code_name']))&&(!empty($date['date_id']))==true) {
		$lc = doSQL("ms_promo_codes", "*", "WHERE code_date_id>'0' ORDER BY code_id DESC ");
		if(!empty($lc['code_name'])) { 
			$coupon['code_name'] = $lc['code_name'];
			$coupon['code_end_date']  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + $date['cat_eb_days'], date("Y")));
			$coupon['code_discount_type'] =  $lc['code_discount_type'];
			$coupon['code_descr'] = _early_bird_special_text_;
			$prices = whileSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$lc['code_id']."' ORDER BY dis_id ASC ");
			$pcount = 1;
			while($price = mysqli_fetch_array($prices)) {
				if($coupon['code_discount_type'] == "percentage") { 
					$_REQUEST['dis_amount'][$pcount] = $price['dis_percent'];
				}
				if($coupon['code_discount_type'] == "flat") {
					$_REQUEST['dis_amount'][$pcount] = $price['dis_flat'];
				}

				$_REQUEST['dis_from'][$pcount] = $price['dis_from'];
				$_REQUEST['dis_to'][$pcount] = $price['dis_to'];
				$pcount++;
			}

		} else { 
			$coupon['code_name'] = _early_bird_special_;
			$coupon['code_end_date']  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + $date['cat_eb_days'], date("Y")));
			$coupon['code_descr'] = _early_bird_special_text_;
		}
	}
	?>

	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
	<div class="underline">
		<div class="label">Coupon Name</div>
		<div><input type="text" name="code_name" id="code_name" class="optrequired field100" value="<?php print $coupon['code_name'];?>" size="20" tabindex="1"></div>
	</div>
	<?php if($date['date_id'] <= 0) { ?>
	<div class="underline">
		<div class="label">Redeem Code</div>
		<div><input type="text" name="code_code" id="code_code" class="optrequired" value="<?php print $coupon['code_code'];?>" size="12" tabindex="3"></div>
	</div>
	<?php } ?>

		<div class="underline">
			<div class="label">Expiration Date</div>
			<div><input type="text" name="code_end_date" id="code_end_date"  class="datepicker"  value="<?php print $coupon['code_end_date'];?>" tabindex="4"></div>
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


	<script>
	function selectdiscounttype() { 
		if($("#dis_typeflat").attr("checked")) { 
			$(".flat").show();
			$(".percentage").hide();
		} else { 
			$(".flat").hide();
			$(".percentage").show();
		}
	}
	 $(document).ready(function(){
		selectdiscounttype();
	 });
	</script>



	<?php if(empty($coupon['code_discount_type'])) { 
		$coupon['code_discount_type'] = "percentage";
	}
	?>
	<div style="width: 48%; float: right;">
	<div class="pc"><h3>Discount Structure</h3></div>
	<div class="underline">
		<input type="radio" name="code_discount_type" id="dis_typepercent" value="percentage" onchange="selectdiscounttype();" <?php if($coupon['code_discount_type'] == "percentage") { print "checked"; } ?>> Percentage Discount  &nbsp; 
		<input type="radio" name="code_discount_type" id="dis_typeflat" value="flat" onchange="selectdiscounttype();"<?php if($coupon['code_discount_type'] == "flat") { print "checked"; } ?>> Flat Rate Dollar Discount
	</div>
	<div class="underlinelabel">
		<div class="left p70 center">Cart total from / to</div>
		<div class="left p30 center">Discount Amount</div>
		<div class="clear"></div>
	</div>

		<?php  
		$lines = 5;
		$ct = 1;
		while($ct<=$lines) {
			if($ct == 1) { 
				if(empty($_REQUEST['dis_from'][$ct])) { 
					$_REQUEST['dis_from'][$ct] = "0.00";
				}
				if(empty($_REQUEST['dis_to'][$ct])) { 
					$_REQUEST['dis_to'][$ct] = "999999.00";
				}

			}
			?><div class="underline">
				<div class="left p70 center"><?php  print $store['currency_sign'];?><input type="text" name="dis_from[<?php  print $ct;?>]" id="dis_from<?php  print $ct;?>" size="10" value="<?php  print $_REQUEST['dis_from'][$ct];?>" <?php if($ct == 1) { print "class=\"optrequired\""; } ?>> TO 
				<?php  print $store['currency_sign'];?><input type="text" name="dis_to[<?php  print $ct;?>]" id="dis_to<?php  print $ct;?>" size="10" value="<?php  print $_REQUEST['dis_to'][$ct];?>" <?php if($ct == 1) { print "class=\"optrequired\""; } ?>>
				</div>

				<div class="left p30 center">

				<span class="flat"><?php  print $store['currency_sign'];?></span><input type="text" name="dis_amount[<?php  print $ct;?>]" id="dis_amount<?php  print $ct;?>"  size="6" value="<?php  print $_REQUEST['dis_amount'][$ct];?>" <?php if($ct == 1) { print "class=\"optrequired\""; } ?>><span class="percentage">%</span>
				</div>

				<div class="clear"></div>
				</div>
			<?php  $ct++;
		}
		?>
	<div class="pc">For the cart total "to", the last one needs to end at 99999.00 or more.</div>

	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>

	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="code_descr" id="code_descr" rows="3" class="field100"><?php print $coupon['code_descr'];?></textarea></div>
		<?php if($date['date_id'] > 0) { ?>
		<div>In the description, enter in [DATE] to display the expiration date and [DISCOUNT_AMOUNT] to display the discounting. You can also edit it to change it up as you like.</div> 
		<?php } ?>
			<?php addEditor("code_descr", "1", "300", "1"); ?>

	</div>

<?php 	if($_REQUEST['code_id'] <= 0) { ?>
		<?php if($date['date_id'] <= 0) { ?>

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
	<?php } ?>



	<div class="pageContent center">
	<input type="hidden" name="code_id" id="code_id" value="<?php print $_REQUEST['code_id'];?>">
	<input type="hidden" name="code_date_id" id="code_date_id" value="<?php print $_REQUEST['code_date_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../../w-footer.php"; ?>