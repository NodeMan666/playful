<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>


<?php 
if($_REQUEST['action'] == "save") { 
	if(empty($_REQUEST['method_name'])) {
		$error .= "<li>You did not enter a Shipping Method Name";
	}


	if(!empty($_REQUEST['price_amount'])) {
		foreach($_REQUEST['price_amount'] AS $id => $price) {
			if(!empty($_REQUEST['price_amount'][$id])) {
				$thisCount++;
				$last[$thisCount] = $_REQUEST['price_to'][$id];
				if($thisCount>1) {
					$gap = number_format($_REQUEST['price_from'][$id] - $last[$thisCount-1],2);
					if(number_format($_REQUEST['price_from'][$id] - $last[$thisCount-1],2) > .01) {
						$error .= "<li>There is a price gap from ending price range of ".$last[$thisCount-1]." to starting price range of ".$_REQUEST['price_from'][$id].""; 
					}
				}
			}
		}
	}
	if(!empty($error)) {
		print "<div class=errorMessage>You have errors: <ul>$error</ul>Correct these errors and resubmit the form.</span></div><div>&nbsp;</div>";
		printForm();
	} else {



	if($_REQUEST['f'] == "EDIT") {
		$method_id = updateSQL("ms_shipping_methods", "method_name='".addslashes(stripslashes($_REQUEST['method_name']))."' , method_status='".addslashes(stripslashes($_REQUEST['method_status']))."', method_order='".$_REQUEST['method_order']."', method_descr='".addslashes(stripslashes($_REQUEST['method_descr']))."' , method_group='".$_REQUEST['method_group']."', method_pickup='".$_REQUEST['method_pickup']."'  WHERE method_id='".$_REQUEST['method_id']."' ");

		deleteSQL2("ms_shipping_prices", "WHERE price_method='" .$_REQUEST['method_id']. "' ");

		foreach($_REQUEST['price_amount'] AS $id => $price) {
			$thisCount++;
			// print "<li>$price";
			if(!empty($price)) {
				$in = insertSQL("ms_shipping_prices", "price_method='".$_REQUEST['method_id']."', price_amount='$price', price_from='".$_REQUEST['price_from'][$id]."', price_to='".$_REQUEST['price_to'][$id]."' ");
			}
		}
		$method_id = $_REQUEST['method_id'];

	} else {
		$method_id = insertSQL("ms_shipping_methods", "method_name='".addslashes(stripslashes($_REQUEST['method_name']))."' , method_status='".addslashes(stripslashes($_REQUEST['method_status']))."', method_order='".$_REQUEST['method_order']."', method_group='".$_REQUEST['method_group']."', method_descr='".addslashes(stripslashes($_REQUEST['method_descr']))."' , method_pickup='".$_REQUEST['method_pickup']."'");
		foreach($_REQUEST['price_amount'] AS $id => $price) {
			$thisCount++;
			if(!empty($price)) {
				$inprice = insertSQL("ms_shipping_prices", "price_method='$method_id', price_amount='$price', price_from='".$_REQUEST['price_from'][$id]."', price_to='".$_REQUEST['price_to'][$id]."' ");
			}
		}

	}
	$_SESSION['sm'] = "Shipping method saved";
		?>
		<script>
		parent.window.location.href = 'index.php?do=settings&action=states&sg_id=<?php print $_REQUEST['method_group'];?>';
		</script>
		<?php 
		exit();
	}
} else { 
	printForm();
}
?>

<?php 
function printForm() {
	global $_REQUEST,$tr;

	if((!empty($_REQUEST['method_id']))AND(empty($_REQUEST['check']))==true) {
		$ship = doSQL("ms_shipping_methods", "*", "WHERE method_id='".$_REQUEST['method_id']."' ");
		$_REQUEST['method_name'] = $ship['method_name'];
		$_REQUEST['method_descr'] = $ship['method_descr'];
		$_REQUEST['method_status'] = $ship['method_status'];
		$_REQUEST['method_order'] = $ship['method_order'];
		$_REQUEST['method_group'] = $ship['method_group'];

		$_REQUEST['f'] = "EDIT";

		$prices = whileSQL("ms_shipping_prices", "*", "WHERE price_method='".$_REQUEST['method_id']."' ORDER BY price_amount ASC ");
		$pcount = 1;
		while($price = mysqli_fetch_array($prices)) {
			$_REQUEST['price_amount'][$pcount] = $price['price_amount'];
			$_REQUEST['price_from'][$pcount] = $price['price_from'];
			$_REQUEST['price_to'][$pcount] = $price['price_to'];
			$pcount++;
		}
	}
	if(empty($_REQUEST['method_id'])) { 
		$_REQUEST['method_status'] = "1";
	}

	?>
<div class="pc"><h1><?php if($_REQUEST['method_id'] > 0) { ?>Edit Shipping Method<?php } else { ?>Add New Shipping Method<?php } ?></div>

	<form method="post" name="newLink" action="w-shipping-edit.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
	<div>
	<div class="underline">
		<div style="width: 50%;float: left;">
			<div class="fieldLabel">Shipping Method Name</div>
			<div>
			<input type="text" name="method_name" id="method_name" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['method_name']));?>" class="required">
			</div>
		</div>

		<div style="width: 25%;float: left;">
			<div class="fieldLabel">&nbsp;</div>
			<div>
			<input type="checkbox" name="method_status" value="1" <?php if($_REQUEST['method_status'] == "1") { print "checked"; } ?>> Active
			</div>
		</div>


		<div style="width: 25%;float: left;">
			<div class="fieldLabel">Display Order</div>
			<div>
			<input type="text" name="method_order" size="2" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['method_order']));?>">
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="underline">
	Shipping group: 
	<select name="method_group" id="method_group" onchange='this.form.submit()' class="">
	<?php 
	$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
	while($group = mysqli_fetch_array($groups)) { 
		?>
		<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $_REQUEST['method_group']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
		<?php 
	}
	?>
	</select>
	</div>

	<div class="underline">
		<div class="fieldLabel">Description</div>
		<div>
			<textarea rows="3" cols="40" class="field100" name="method_descr"><?php  print htmlspecialchars(stripslashes($_REQUEST['method_descr']));?></textarea>
		</div>
	</div>
	</div>
	
	<div class="underline">
		<input type="checkbox" name="method_pickup" id="method_pickup" value="1" <?php if($ship['method_pickup'] == "1") { print "checked"; } ?>> This option is for a pick up or no shipping address needed 
	</div>
	<div>&nbsp;</div>



	<div style="width: 50%; float: left;">
		<div class="pc">
		<h3>Creating your shipping chart</h3>
		To the right, you create your shipping costs based on the order total. First, enter the amount for shipping, then enter the price range for that amount.<br><br>
		Some rules:
		<ul>
			<li>Your first "<b>from</b>" price range should start with <b>.00 or .01</b> 
			<li>Your last "<b>to</b>" price range must end with <b>99999.00</b> or more.
			<li>There can not be a gap from ending price range to the next starting price range.
		</ul>
		<div>&nbsp;</div>
		To create free shipping over a certain price, enter in 0.00 (not just 0) in the shipping cost field.
		</div>
	</div>

	<div style="width: 50%; float: right;">
		<div class="underlinelabel">
			<div style="width: 30%; float: left;">Shipping Cost</div>
			<div style="width: 70%; float: left; text-align: center;">Order Price Range</div>
			<div class="clear"></div>
		</div>

		<?php  
		$lines = 8;
		$ct = 1;
		while($ct<=$lines) {
			?><div class="underline">
				<div style="width: 30%; float: left;">
				<?php  print $tr['currency'];?><input type="text" name="price_amount[<?php  print $ct;?>]" size="6" value="<?php  print $_REQUEST['price_amount'][$ct];?>">
				</div>
				<div style="width: 70%; float: left; text-align: center;" ><input type="text" name="price_from[<?php  print $ct;?>]" size="6" value="<?php  print $_REQUEST['price_from'][$ct];?>"> TO <input type="text" name="price_to[<?php  print $ct;?>]" size="6" value="<?php  print $_REQUEST['price_to'][$ct];?>">
				</div>
				<div class="clear"></div>
				</div>
			<?php  $ct++;
		}
		?>

		</div>
		<div class="clear"></div>
	
	
	<div class="row center">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="f" value="<?php  print $_REQUEST['f'];?>">
	<input type="hidden" name="method_group" value="<?php  print $_REQUEST['method_group'];?>">
	<input type="hidden" name="method_id" value="<?php  print $_REQUEST['method_id'];?>">

	<input type="submit" name="submit" value="Save shipping option" class="submit"  id="submitButton"> 
	</div>
</div></form>
<?php  } ?>



	<div >&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>
