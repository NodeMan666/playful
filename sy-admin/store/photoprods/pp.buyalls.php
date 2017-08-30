<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> Buy Alls</div>
<div class="pc">"Buy Alls" are collections to purchase all photos in a gallery of the same product. Example, buy all photos in this gallery as a 4x6.</div>
<?php 

if($_REQUEST['action'] == "saveproduct") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(!empty($_REQUEST['package_id'])) { 

	} else { 
		$pp = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['package_buy_all_product']."' ");
		$id = insertSQL("ms_packages", "
		package_name='".addslashes(stripslashes($pp['pp_name']))."', package_price='".$_REQUEST['package_price']."', package_taxable='1', package_select_amount='".$_REQUEST['package_select_amount']."', package_buy_all_product='".$_REQUEST['package_buy_all_product']."', package_require_all='1', package_ship='1' , package_buy_all='1', package_buy_all_price_type='1'     ");
		$_SESSION['sm'] = $_REQUEST['package_name']." Saved";
		header("location: index.php?do=photoprods&view=buyalls&package_id=".$id."");
		session_write_close();
		exit();

	} 
}

if($_REQUEST['action'] == "deleteOption") { 
	$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
	if(!empty($opt['opt_id'])) { 
		deleteSQL("ms_product_options", "WHERE opt_id='".$opt['opt_id']."' ", "1");
		deleteSQL2("ms_product_options_sel", "WHERE sel_opt='".$opt['opt_id']."' ");
	}
	$_SESSION['sm'] = "Option deleted";
	session_write_close();
	header("location: index.php?do=photoprods&view=buyalls&package_id=".$opt['opt_package']."");
	exit();

}

?>
<script>
function createPriceList() { 
	$("#newpricelist").slideToggle(200);
}
function editpricelist(id) { 
	$("#pl-"+id).slideToggle(200);
}
function getpackagetype() { 
	if($('input[name=package_select_only]:checked').val() == "1") { 
		$("#package_select_amount_div").slideDown(200);
	} else { 
		$("#package_select_amount_div").slideUp(200);
	}
}
</script>

		<div class="right textright">

		<div class="pc"><span  class="buttons"><a href="" onClick="createPriceList(); return false;">Create New Buy All</a></span></div>
		<div class="" style="display: none;" id="newpricelist">
		<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm();">
			<div class="underline" style="text-align: left;">
			<select name="package_buy_all_product" id="package_buy_all_product" class="newprod required">
			<option value="">Select product</option>
			<?php $prods = whileSQL("ms_photo_products", "*"," WHERE pp_free<='0' ORDER BY pp_name ASC ");
			while($prod = mysqli_fetch_array($prods)) { ?>
			<option value="<?php print $prod['pp_id'];?>"><?php print $prod['pp_name'];?><?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?></option>
			<?php } ?>
			</select>
			</div>

		<div class="pc">Once you create this Buy All, you will be able to set the pricing.</div>
		<div class="pc">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="buyalls">
		<input type="hidden" name="action" value="saveproduct">
		<input type="submit" name="submit" value="Continue" id="submitButton" class="submitSmall">
		</div>
		</form>
		</div>
		</div>
<div class="clear"></div>
<div class="underlinelabel">
	<div class="p50 left">Name</div>
	<div class="p30 left">Price</div>
	<div class="p20 left center">#Sold</div>
	<div class="clear"></div>
</div>


<?php $packs = whileSQL("ms_packages LEFT JOIN ms_photo_products ON ms_packages.package_buy_all_product=ms_photo_products.pp_id", "*","WHERE package_buy_all='1' ORDER BY pp_name ASC ");
if(mysqli_num_rows($packs)<=0) { ?>
<div class="underline center">No buy alls created</div>
<?php } ?>
<div>&nbsp;</div>
<?php 
while($pack = mysqli_fetch_array($packs)) { 
	$x = 0;
	?>
<div class="underline">
	<div style="width: 50%; float: left;">
	<?php 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ");
		if(!empty($pic['pic_id'])) { 
			// $size = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
			?>
		<a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>"><img src="<?php print getimagefile($pic,'pic_mini');?>" border="0" class="thumbnail left" style="margin: 0 8px 8px 0;"></a>
		<?php } ?>

		<div><h2><a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>"><?php print $pack['package_name'];?></a> </h2></div>
		<div class="sub small">
		<a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>">view</a> &nbsp;&nbsp; 
		<a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>&action=deletePackage"   onClick="return confirm('Are you sure you want to delete this package? Deleting this will permanently remove it and can not be reversed.');">delete</a> &nbsp;&nbsp;  
		</div>
	</div>
	<div style="width: 30%; float: left;">
	<div><h3>
	<?php if($pack['package_buy_all_price_type'] == "1") { print showPrice($pack['package_buy_all_each']);?> per photo<?php } ?> 
	<?php if($pack['package_buy_all_price_type'] == "2") { ?>Tiered Pricing<?php } ?> 
	<?php if($pack['package_buy_all_price_type'] == "3") { print showPrice($pack['package_buy_all_set_price']);?><?php } ?> 
	</h3></div>
	<?php if($pack['package_add_ship'] > 0) { ?>
	<div><?php print showPrice($pack['package_add_ship']);?> additional shipping</div>
	<?php } ?>
	</div>

	<div style="width: 20%; float: left; text-align: center;">
		<?php
			$tsold = countIt("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "WHERE cart_package='".$pack['package_id']."' AND order_payment_status='Completed' AND order_status<='1' ") + countIt("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id", "WHERE cart_package='".$pack['package_id']."' AND order_payment_status='Completed' AND order_status<='1' ");
		if($tsold > 0) { 
			print $tsold;	
		} else { 
			print "&nbsp;";
		}
		?>


	</div>
	<div class="clear"></div>
</div>
<?php } ?>
<div>&nbsp;</div>
