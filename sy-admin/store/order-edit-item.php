<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$('#order_email').change(function() {
	checkemail();
});

function checkemail() { 
	$.get("admin.actions.php?action=checkemail&order_email="+$("#order_email").val()+"&cart_id="+$("#cart_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The email address "+$("#order_email").val()+" already exists for another account. Search for this account or enter in a different email address.");
			$("#order_email").val("")
		}
	});
}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#order_first_name").focus();
 }

</script>
<?php

$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

$cart = doSQL(cart_table, "*", "WHERE cart_id='".$_REQUEST['cart_id']."' "); 

if($_REQUEST['action'] == "deletecartitem") { 
	deleteSQL(cart_table, "WHERE cart_id='".$_REQUEST['cart_id']."' ", "1");
	deleteSQL2(cart_table, "WHERE cart_package_photo='".$_REQUEST['cart_id']."' ");
	header("location: ../index.php?do=orders&action=viewOrder&orderNum=".$cart['cart_order']."#cart-".MD5($cart['cart_id'])."");
	session_write_close();
	exit();


}


if($_POST['submitit']=="yes") { 






	$cos = whileSQL("ms_cart_options","*", "WHERE co_cart_id='".$cart['cart_id']."' ");
	while($co = mysqli_fetch_array($cos)) { 
		updateSQL("ms_cart_options", "co_opt_name='".addslashes(stripslashes($_REQUEST['co_opt_name'][$co['co_id']]))."', co_price='".addslashes(stripslashes($_REQUEST['co_price'][$co['co_id']]))."',co_select_name='".addslashes(stripslashes($_REQUEST['co_select_name'][$co['co_id']]))."' WHERE co_id='".$co['co_id']."' ");
	}
	if($_REQUEST['cart_pic_id'] > 0) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$_REQUEST['cart_pic_id']."' ");
	} 
	updateSQL(cart_table, "
	cart_pic_id='".addslashes(stripslashes($_REQUEST['cart_pic_id']))."' , 
	cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."' , 
	cart_qty='".addslashes(stripslashes($_REQUEST['cart_qty']))."' , 
	cart_product_name='".addslashes(stripslashes($_REQUEST['cart_product_name']))."', 
	cart_store_product='".addslashes(stripslashes($_REQUEST['cart_store_product']))."',
	cart_price='".addslashes(stripslashes($_REQUEST['cart_price']))."',
	cart_sku='".addslashes(stripslashes($_REQUEST['cart_sku']))."',
	cart_photo_prod='".addslashes(stripslashes($_REQUEST['cart_photo_prod']))."'

	WHERE cart_id='".$_REQUEST['cart_id']."' ");
	$_SESSION['sm'] = "Cart Saved";
	$cart_id=$_REQUEST['cart_id'];


	header("location: ../index.php?do=orders&action=viewOrder&orderNum=".$cart['cart_order']."#cart-".MD5($cart['cart_id'])."");
	session_write_close();
	exit();
}
?>


<?php
	if(($_REQUEST['cart_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$cart = doSQL(cart_table, "*", "WHERE cart_id='".$_REQUEST['cart_id']."' "); 
		if(empty($cart['cart_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($cart AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<div class="pc"><h1>Edit order item</h1>

	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">


	<?php if($cart['cart_pic_id'] > 0) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
		if(empty($pic['pic_id'])) { ?>Photo does not exist
		<?php } else {
			$size = getimagefiledems($pic,'pic_th');

			?>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
				<img src="<?php print getimagefile($pic,'pic_th');?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" width="<?php print $size[0];?>" height="<?php print $size[1];?>"> 
				</div>
				<div class="left" style="margin-right: 16px;">
					<div><?php print $pic['pic_org'];?></div>
					<div>&nbsp;</div>
					<div>ID: <input type="text" name="cart_pic_id" id="cart_pic_id" class="" value="<?php print $cart['cart_pic_id'];?>" size="6"></div>
					<div>&nbsp;</div>
					<div>To change photo, get the image ID <br>under the thumbnail in the gallery. <br><a href="index.php?do=news&action=managePhotos&date_id=<?php print $cart['cart_pic_date_id'];?>&sub_id=<?php print $cart['cart_sub_gal_id'];?>" target="_blank">Click here to find photo from the gallery.</a><br>(opens in new tab)</div>

				</div>

				<div class="clear"></div>
			</div>


		<?php } ?>
	<?php } ?>


		<?php if($cart['cart_store_product'] > 0) { 
			$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_store_product']."' ");
			?>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Qty</div>
					<div><input type="text" name="cart_qty" id="cart_qty" class="optrequired" value="<?php print $cart['cart_qty'];?>" size="2"></div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Name</div>
					<div><input type="text" name="cart_product_name" id="cart_product_name" class="optrequired" value="<?php print $cart['cart_product_name'];?>" size="20"></div>
				</div>

				<div class="left" style="margin-right: 16px;">
					<div class="label">Product</div>
				
					<div><select name="cart_store_product" id="cart_store_product" class="field100">
					<option value="" disabled>Select Product</option>
						<option value="">None</option>
				<?php 
					$dates = whileSQL("ms_calendar", "*", "WHERE prod_type!='' ORDER BY date_title ASC ");
					while($date = mysqli_fetch_array($dates)) { ?>
					<option value="<?php print $date['date_id'];?>" <?php if($date['date_id'] == $cart['cart_store_product']) { ?>selected<?php } ?>><?php print $date['date_title'];?></option>
					<?php } ?>
					</select>
					</div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">SKU</div>
					<div><input type="text" name="cart_sku" id="cart_sku" class="" value="<?php print $cart['cart_sku'];?>" size="10"></div>
				</div>


				<div class="right" style="margin-right: 16px;">
					<div class="label">Price Each</div>
					<div><input type="text" name="cart_price" id="cart_price" class="optrequired" value="<?php print $cart['cart_price'];?>" size="8"></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php } ?>



		<?php if($cart['cart_photo_prod'] > 0) { 
			$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
			?>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Qty</div>
					<div><input type="text" name="cart_qty" id="cart_qty" class="optrequired" value="<?php print $cart['cart_qty'];?>" size="2"></div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Name</div>
					<div><input type="text" name="cart_product_name" id="cart_product_name" class="optrequired" value="<?php print $cart['cart_product_name'];?>" size="20"></div>
				</div>

				<?php if($cart['cart_photo_prod'] == "99999999") { ?>
				<input type="hidden" name="cart_photo_prod" id="cart_photo_prod" value="<?php print $cart['cart_photo_prod'];?>">
				<?php } else { ?>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Product</div>
				
					<div><select name="cart_photo_prod" id="cart_store_product" class="field100">
					<option value="" disabled>Select Product</option>
						<option value="">None</option>
				<?php 
					$prods = whileSQL("ms_photo_products", "*", "ORDER BY pp_name ASC ");
					while($prod = mysqli_fetch_array($prods)) { ?>
					<option value="<?php print $prod['pp_id'];?>" <?php if($prod['pp_id'] == $cart['cart_photo_prod']) { ?>selected<?php } ?>><?php print $prod['pp_name'];?></option>
					<?php } ?>
					</select>
					</div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">SKU</div>
					<div><input type="text" name="cart_sku" id="cart_sku" class="" value="<?php print $cart['cart_sku'];?>" size="10"></div>
				</div>
				<?php } ?>
				<div class="right" style="margin-right: 16px;">
					<div class="label">Price Each</div>
					<div><input type="text" name="cart_price" id="cart_price" class="optrequired" value="<?php print $cart['cart_price'];?>" size="8"></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php } ?>




		<?php if($cart['cart_package'] > 0) { 
			$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
			?>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Qty</div>
					<div><input type="text" name="cart_qty" id="cart_qty" class="optrequired" value="<?php print $cart['cart_qty'];?>" size="2"></div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Name</div>
					<div><input type="text" name="cart_product_name" id="cart_product_name" class="optrequired" value="<?php print $cart['cart_product_name'];?>" size="20"></div>
				</div>


				<div class="left" style="margin-right: 16px;">
					<div class="label">Product</div>
				
					<div><select name="cart_package" id="cart_package" class="field100">
					<option value="" disabled>Select Product</option>
						<option value="">None</option>
				<?php 
					$prods = whileSQL("ms_packages", "*", "ORDER BY package_name ASC ");
					while($prod = mysqli_fetch_array($prods)) { ?>
					<option value="<?php print $prod['package_id'];?>" <?php if($prod['package_id'] == $cart['cart_package']) { ?>selected<?php } ?>><?php print $prod['package_name'];?> <?php if($prod['package_buy_all'] == "1") { ?> (Buy All)<?php } ?></option>
					<?php } ?>
					</select>
					</div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">SKU</div>
					<div><input type="text" name="cart_sku" id="cart_sku" class="" value="<?php print $cart['cart_sku'];?>" size="10"></div>
				</div>

				<div class="right" style="margin-right: 16px;">
					<div class="label">Price Each</div>
					<div><input type="text" name="cart_price" id="cart_price" class="optrequired" value="<?php print $cart['cart_price'];?>" size="8"></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php } ?>


		<?php if($cart['cart_invoice'] > 0) { 
			?>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Qty</div>
					<div><input type="text" name="cart_qty" id="cart_qty" class="optrequired" value="<?php print $cart['cart_qty'];?>" size="2"></div>
				</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Name</div>
					<div><input type="text" name="cart_product_name" id="cart_product_name" class="optrequired" value="<?php print $cart['cart_product_name'];?>" size="20"></div>
				</div>




				<div class="right" style="margin-right: 16px;">
					<div class="label">Price Each</div>
					<div><input type="text" name="cart_price" id="cart_price" class="optrequired" value="<?php print $cart['cart_price'];?>" size="8"></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php } ?>


		<div class="clear"></div>
		<div>&nbsp;</div>

	<?php $cos = whileSQL("ms_cart_options","*", "WHERE co_cart_id='".$cart['cart_id']."' ");
	while($co = mysqli_fetch_array($cos)) { ?>
	<div class="underline">
		<div class="left" style="margin-right: 16px;">
			<div class="label">Option Name</div>
			<div><input type="text" name="co_opt_name[<?php print $co['co_id'];?>]" id="" class="" value="<?php print $co['co_opt_name'];?>" size="20"></div>
		</div>

		<div class="left" style="margin-right: 16px;">
			<div class="label">Option Selected</div>
			<div><input type="text" name="co_select_name[<?php print $co['co_id'];?>]" id="" class="" value="<?php print $co['co_select_name'];?>" size="20"></div>
		</div>

		<div class="right" style="margin-right: 16px;">
			<div class="label">Price</div>
			<div><input type="text" name="co_price[<?php print $co['co_id'];?>]" id="" class="" value="<?php print $co['co_price'];?>" size="8"></div>
		</div>

		<div class="clear"></div>
	</div>

	<?php } ?>
	<div class="pc center"><a href="<?php print $_SERVER['PHP_SELF'];?>?action=deletecartitem&cart_id=<?php print $cart['cart_id'];?>&order_id=<?php print $cart['cart_order'];?>"  onClick="return confirm('Are you sure you want to delete this? This action can not be reversed!'); return false;" >delete this cart item</a></div>

	<div class="pc center"><b>NOTE: If you change any prices, you will need to update totals, taxes, payments, etc ... by clicking the Edit Order tab when viewing the order.</b></div>
	<div class="pageContent center">

	<input type="hidden" name="cart_id" value="<?php print $_REQUEST['cart_id'];?>">
	<input type="hidden" name="order_id" value="<?php print $_REQUEST['order_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['cart_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>

<?php require "../w-footer.php"; ?>
