<?php include $setup['path']."/".$setup['manage_folder']."/store/options.edit.php"; ?>
<?php
$pack = doSQL("ms_packages LEFT JOIN ms_photo_products ON ms_packages.package_buy_all_product=ms_photo_products.pp_id", "*", "WHERE package_id='".$_REQUEST['package_id']."' ");
foreach($_REQUEST AS $id => $value) {
	if(!is_array($_REQUEST[$id])) { 
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
}

if($_REQUEST['action'] == "deletePackage") { 
	deleteSQL2("ms_photo_products_connect", "WHERE pc_package='".$_REQUEST['package_id']."'");
	deleteSQL2("ms_packages_connect", "WHERE con_package='".$_REQUEST['package_id']."'");
	deleteSQL("ms_packages", "WHERE package_id='".$_REQUEST['package_id']."'", "1");
	$_SESSION['sm'] = "Package removed";
	header("location: index.php?do=photoprods&view=buyalls");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "duplicatePackage") { 
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$_REQUEST['package_id']."' ");
	$new = insertSQL("ms_packages", "package_name='*** ".addslashes(stripslashes($pack['package_name']))." - COPY',  package_descr='".addslashes(stripslashes($pack['package_descr']))."', package_price='".addslashes(stripslashes($pack['package_price']))."',  package_taxable='".addslashes(stripslashes($pack['package_taxable']))."', package_select_amount='".$pack['package_select_amount']."', package_select_only='".$pack['package_select_only']."', package_require_all='".$pack['package_require_all']."' , package_ship='".$pack['package_ship']."', package_credit='".$pack['package_credit']."' , package_limit='".$pack['package_limit']."', package_add_ship='".$pack['package_add_ship']."', package_buy_all_set_price='".$pack['package_buy_all_set_price']."', package_buy_all_no_sub_gal='".$pack['package_buy_all_no_sub_gal']."' , package_buy_all_subs_main='".$pack['package_buy_all_subs_main']."', package_collapse_options='".$pack['package_collapse_options']."', package_preview_text='".addslashes(stripslashes($pack['package_preview_text']))."' "); 
	$prods = whileSQL("ms_packages_connect", "*", "WHERE con_package='".$pack['package_id']."' ");
	while($prod = mysqli_fetch_array($prods)) { 
		insertSQL("ms_packages_connect", "con_package='".$new."', con_product='".$prod['con_product']."', con_qty='".$prod['con_qty']."', con_order='".$prod['con_order']."' ");
	}

	$_SESSION['sm'] = "New package created from duplication and shown below";
	header("location: index.php?do=photoprods&view=buyalls&package_id=".$new."");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "updatepackage") { 
	updateSQL("ms_packages", "package_name='".$_REQUEST['package_name']."', package_buy_all_product='".$_REQUEST['package_buy_all_product']."', package_buy_all_price_type='".$_REQUEST['package_buy_all_price_type']."', package_buy_all_each='".$_REQUEST['package_buy_all_each']."', package_taxable='".$_REQUEST['package_taxable']."', package_descr='".$_REQUEST['package_descr']."', package_select_amount='".$_REQUEST['package_select_amount']."', package_require_all='".$_REQUEST['package_require_all']."', package_ship='".$_REQUEST['package_ship']."' , package_credit='".$_REQUEST['package_credit']."' , package_limit='".$_REQUEST['package_limit']."', package_add_ship='".$_REQUEST['package_add_ship']."', package_no_discount='".$_REQUEST['package_no_discount']."', package_buy_all_set_price='".$_REQUEST['package_buy_all_set_price']."' , package_internal_name='".$_REQUEST['package_internal_name']."', package_buy_all_no_sub_gal='".$_REQUEST['package_buy_all_no_sub_gal']."', package_buy_all_subs_main='".$_REQUEST['package_buy_all_subs_main']."', package_collapse_options='".$_REQUEST['package_collapse_options']."', package_preview_text='".addslashes(stripslashes($_REQUEST['package_preview_text']))."' WHERE package_id='".$_REQUEST['package_id']."' ");
	if(!empty($_REQUEST['package_id'])) { 
		deleteSQL2("ms_packages_buy_all", "WHERE ba_package='".$_REQUEST['package_id']."' ");
	}

	foreach($_REQUEST['ba_from'] AS $id => $opt) {
		$thisCount++;
		print "<li>$opt";
		if(!empty($opt)) {
			$in = insertSQL("ms_packages_buy_all", " ba_from='".$_REQUEST['ba_from'][$id]."', ba_to='".$_REQUEST['ba_to'][$id]."', ba_price='".$_REQUEST['ba_price'][$id]."', ba_order='".$_REQUEST['ba_order'][$id]."', ba_package='".$_REQUEST['package_id']."' ");
			unset($and_add);
		}
	}

	$_SESSION['sm'] = "Package Updated";
	header("location: index.php?do=photoprods&view=buyalls&package_id=".$_REQUEST['package_id']."");
	session_write_close();
	exit();
}


if($_REQUEST['action']=="deleteThumb") { 
	$pic = doSQL("ms_photos ", "*", "WHERE ms_photos.pic_id='".$_REQUEST['pic_id']."' ");
	if(!empty($pic['pic_id'])) {
		if(!empty($pic['pic_folder'])) { 
			$pic_folder = $pic['pic_folder'];
		} else { 
			$pic_folder = $pic['gal_folder'];
		}
		if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".$pic['pic_th']."' ")<=1) { 
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini']);
		}
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_med']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full']);
	}
	deleteSQL("ms_photos", "WHERE pic_id='".$pic['pic_id']."' ", "1" );
	deleteSQL2("ms_blog_photos", "WHERE bp_pic='".$pic['pic_id']."' ");

	$_SESSION['sm'] = "Thumbnail Deleted";
	session_write_close();
	header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['package_id']." ");
	exit();
}



?>
<script>
function updateqty(id) { 
	$("#update-"+id).hide();
	$("#updating-"+id).show();

	$.get("admin.actions.php?action=updatepackageproductqty&con_id="+id+"&con_qty="+$("#con_qty-"+id).val()+"&con_extra_price="+$("#con_extra_price-"+id).val()+"&con_extra_price_new_photo="+$("#con_extra_price_new_photo-"+id).val()+"", function(data) {
		$("#update-"+id).show();
		$("#updating-"+id).hide();
	});
}
function shownewproduct() { 
	$("#newpackageprod").slideToggle(200);
}
function updatename() { 
	$("#package_name").val($('#package_buy_all_product option:selected').text());
}
$(document).ready(function(){
	$("#package_collapse_options").change(function() { 
		if($("#package_collapse_options").attr("checked")) { 
			$(".previewtext").slideDown(200);
		} else { 
			$(".previewtext").slideUp(200);
		}
	});
});

</script>

<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=buyalls&packag">Buy Alls</a> <?php print ai_sep;?>  <?php print $pack['pp_name'];?></div>
<div class="clear"></div>
<div>&nbsp;</div>

<div style="width: 40%;" class="left">

	<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm('','submitButton');">

	<div style="margin: 0 16px 0 0;">
	<div class="underlinelabel">Buy All for <?php print $pack['pp_name'];?></div>

	<div class="underline">
		<div class="label">Product</div>
		<div>
		<select name="package_buy_all_product" id="package_buy_all_product" class="newprod required" onchange="updatename();">
		<option value="">Select product</option>
		<?php $prods = whileSQL("ms_photo_products", "*"," ORDER BY pp_name ASC ");
		while($prod = mysqli_fetch_array($prods)) { ?>
		<option value="<?php print $prod['pp_id'];?>" <?php if($pack['package_buy_all_product'] == $prod['pp_id']) { print "selected"; } ?>><?php print $prod['pp_name'];?><?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?></option>
		<?php } ?>
		</select>
		</div>
	</div>

	<div class="underline">
		<div class="label">Product Name</div>
		<div><input type="text" name="package_name" id="package_name" class="field100 required inputtitle" value="<?php print htmlspecialchars($pack['package_name']);?>"></div>
	</div>
	<div class="underline">
		<div class="label">Internal Name</div>
		<div><input type="text" name="package_internal_name" id="package_internal_name" class="field100" value="<?php print htmlspecialchars($pack['package_internal_name']);?>"></div>
		<div>Used for your reference only</div>
	</div>


	<div class="underline">
		<div class="left">
		<div class="label">&nbsp;</div>
		<div><input type="checkbox" name="package_taxable" id="package_taxable" value="1" <?php if($pack['package_taxable'] == "1") { print "checked"; } ?>> <label for="package_taxable">Taxable</label></div>
		<div class="right textright"><input type="checkbox" name="package_ship" id="package_ship" value="1" <?php if($pack['package_ship'] == "1") { print "checked"; } ?>> <label for="package_ship">Shippable</label> <div class="moreinfo" info-data="packageshippable"><div class="info"></div></div></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="label">Credit</div>
		<div><input type="text" name="package_credit" id="package_credit"  class="left" style="margin-right: 16px;" size="6" value="<?php print htmlspecialchars($pack['package_credit']);?>"> Here you can enter in a credit amount that can be used to purchase other photo products.
		<div class="clear"></div>
		</div>

	</div>
	<div class="underline">
		<div class="label">Additional Shipping</div>
		<div><input type="text" name="package_add_ship" id="package_add_ship"  class="left" style="margin-right: 16px;" size="6" value="<?php print htmlspecialchars($pack['package_add_ship']);?>"> This amount will be added to the shipping calculation.
		<div class="clear"></div>
		</div>

	</div>
	<div class="underline">
		<div><input type="checkbox" name="package_no_discount" id="package_no_discount" value="1" <?php if($pack['package_no_discount'] == "1") { print "checked"; } ?>> <label for="package_no_discount">Do not allow discounting <div class="moreinfo" info-data="prodnodiscount"><div class="info"></div></div></label></div>
	</div>

	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="package_descr" id="package_descr" class="field100" rows="4" cols="30"><?php print htmlspecialchars($pack['package_descr']);?></textarea></div>
	</div>

	<div class="underline">
		<div><input type="checkbox" name="package_collapse_options" id="package_collapse_options" value="1" <?php if($pack['package_collapse_options'] == "1") { print "checked"; } ?>> <label for="package_collapse_options">Collapse Options & Description</label></div>
		<div>This option to hide the product options and description until the customer clicks the product menu.</div>
	</div>

	<div class="underline previewtext <?php if($pack['package_collapse_options'] !== "1") {?>hide<?php } ?>">
		<div class="label">Preview Description</div>
		<div><input type="text" name="package_preview_text" id="package_preview_text" class="field100" value="<?php print htmlspecialchars($pack['package_preview_text']);?>"></div>
		<div>Since you have selected to collapse options & description, you can enter in a short description here which will show under the collection name before viewing full details.</div>
	</div>

	<!-- 
	<div class="underlinelabel">
	If Gallery has Sub Galleries
	</div>
	<div class="underline">
		<div><input type="checkbox" name="package_buy_all_no_sub_gal" id="package_buy_all_no_sub_gal" value="1" <?php if($pack['package_buy_all_no_sub_gal'] == "1") { print "checked"; } ?>> <label for="package_buy_all_no_sub_gal">Do not offer in sub galleries </label></div>
	</div>

	<div class="underline">
		<div><input type="checkbox" name="package_buy_all_subs_main" id="package_buy_all_subs_main" value="1" <?php if($pack['package_buy_all_subs_main'] == "1") { print "checked"; } ?>> <label for="package_buy_all_subs_main">Offer in main gallery for all photos included in the sub galleries  </label></div>
	</div>
	-->
	<div class="bottomSave">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="packages">
		<input type="hidden" name="package_id" value="<?php print $pack['package_id'];?>">
		<input type="hidden" name="action" value="updatepackage">
		<input type="submit" name="submit" value="Update Buy All" class="submit" id="submitButton">
	</div>
	<div class="underline">
		<a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>&action=deletePackage" onClick="return confirm('Are you sure you want to DELETE this? Deleting this will permanently remove it and can not be reversed.');">Delete Buy All</a> &nbsp; 
		</div>
	</div>

	<div>&nbsp;</div>

</div>
<script>
function getbuyalltype() { 
	$(".buyalloption").slideUp(200);
	if($('input[name=package_buy_all_price_type]:checked').val() == "1") { 
		$(".perphoto").slideDown(200);
	}
	if($('input[name=package_buy_all_price_type]:checked').val() == "2") { 
		$(".rangepricing").slideDown(200);
	}
	if($('input[name=package_buy_all_price_type]:checked').val() == "3") { 
		$(".flatprice").slideDown(200);
	}
}

</script>

<div style="width: 60%;" class="left">
<?php $num_prods = countIt("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id",  "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
if($num_prods <=0) { $_REQUEST['np'] = "1"; } 
?>
	<div class="underlinelabel">Pricing</div>
	<div class="underline">
		<div class="left p35">
			<div style="padding-right: 24px;">
				<input type="radio" name="package_buy_all_price_type" id="package_buy_all_price_type-per" value="1" <?php if($pack['package_buy_all_price_type'] == "1") { print "checked"; } ?>  onchange="getbuyalltype();"> <label for="package_buy_all_price_type-per"> <h3 style="display: inline;">Price Per Photo</h3><br>This will calculate the price of a flat price per photo in the gallery. </label>
			</div>
		</div>
		<div class="left p35">
			<input type="radio" name="package_buy_all_price_type" id="package_buy_all_price_type-range" value="2" <?php if($pack['package_buy_all_price_type'] == "2") { print "checked"; } ?>  onchange="getbuyalltype();"> <label for="package_buy_all_price_type-range"> <h3 style="display: inline;">Tiered Pricing</h3><br>Create a tiered pricing structure where your can charge less per photo depending on the amount of photos in the gallery.</label>
		</div>
		<div class="left p30">
			<input type="radio" name="package_buy_all_price_type" id="package_buy_all_price_type-flat" value="3" <?php if($pack['package_buy_all_price_type'] == "3") { print "checked"; } ?>  onchange="getbuyalltype();"> <label for="package_buy_all_price_type-flat"> <h3 style="display: inline;">Set Price</h3><br>One set price no matter how many photos in the gallery.</label>
		</div>


	<div class="clear"></div>
</div>
<div class="flatprice buyalloption underline <?php if($pack['package_buy_all_price_type'] !== "3") { print "hidden"; } ?>">
Set price of: <input type="text" name="package_buy_all_set_price" id="package_buy_all_set_price" value="<?php print $pack['package_buy_all_set_price'];?>" size="8"  class="center inputtitle">.
</div>

<div class="perphoto buyalloption underline <?php if($pack['package_buy_all_price_type'] !== "1") { print "hidden"; } ?>">
Charge <input type="text" name="package_buy_all_each" id="package_buy_all_each" value="<?php print $pack['package_buy_all_each'];?>" size="8" class="center"> per photo in the gallery.
</div>
<div class="rangepricing buyalloption <?php if($pack['package_buy_all_price_type'] !== "2") { print "hidden"; } ?>">


	<div class="pc">Here you can create a tiered pricing structure. For example, if a gallery has 100 photos, you can charge less per photo than if a gallery has 50 photos.</div>


	<div class="pc"><a href="javascript:showHide('example');">Show example</a></div>

	<div class="pc" id="example" style="display: none;">
		<div style="width: 50%; float: left;">
			<table cellpadding="4" cellspacing="4" width="90%" align="center">
			<tr>
			<td>Qty From</td><td>Qty To</td><td>Price</td>
			</tr>
			<tr><td>1</td><td>4</td><td><?php print showPrice(5.00);?> each photo</td></tr>
			<tr><td>5</td><td>10</td><td><?php print showPrice(4.5);?> each photo</td></tr>
			<tr><td>11</td><td>20</td><td><?php print showPrice(4.00);?> each photo</td></tr>
			<tr><td>21</td><td>40</td><td><?php print showPrice(3.5);?> each photo</td></tr>
			<tr><td>41</td><td>0</td><td><?php print showPrice(3.00);?> each photo</td></tr>
			</table>
		</div>
		<div style="width: 50%; float: left;">
			<div class="pc">If there are 19 photos, the price would be 19 X $4.00 =  <?php print showPrice(76.00);?>. </div>
			<div class="pc">If there are 45 photos, the price would be 45 X $3.00 =  <?php print showPrice(135.00);?>. </div>
			<div class="pc">Notice that in the last range, the qty to is 0. This means that is the last one and anything above that from quantity will be charged that price.</div>
		</div>
		<div class="clear"></div>

	<div class="pc">IMPORTANT:
		<ul>
			<li>Be sure there is no gapping in your ranges. Example: from: 10 to 19 ... the next from has to start at 20. 
			<li>End you last qty to range with a 0.
		</ul>
	</div>

	<div class="pc">If you need more lines to enter, save  and more will be available.</div>
	</div>
	<div class="underlinelabel">
		<div class="left p25">Qty From</div>
		<div class="left p25">Qty To</div>
		<div class="left p50">Price Per Photo</div>
		<div class="clear"></div>
	</div>


<?php 
	$sels = whileSQL("ms_packages_buy_all", "*", "WHERE ba_package='".$_REQUEST['package_id']."' ORDER BY ba_from ASC ");
	$pcount = 1;
	while($sel = mysqli_fetch_array($sels)) {
		$req['ba_from'][$pcount] = $sel['ba_from'];
		$req['ba_to'][$pcount] = $sel['ba_to'];
		$req['ba_price'][$pcount] = $sel['ba_price'];
		$req['ba_order'][$pcount] = $sel['ba_order'];
		if($sel['sel_def'] == "1") {
			$def = $pcount;
		}
		$pcount++;
	}
	$req['pcount'] = $pcount;

	if($req['pcount'] <= 1) { 
		$lines = 6;
	} else { 
		$lines = $req['pcount'] + 1;
	}
	$ct = 1;
	while($ct<=$lines) {
	if($ct%2) {
		$rowclass= "tdrows1";
	} else {
		$rowclass = "tdrows2";
	}
	?>
	<div class="underline">
		<div class="left p25"><input type="text" name="ba_from[<?php print $ct;?>]" size="4" value="<?php print htmlspecialchars(stripslashes($req['ba_from'][$ct]));?>"></div>
		<div class="left p25"><input type="text" name="ba_to[<?php print $ct;?>]" size="4" value="<?php print htmlspecialchars(stripslashes($req['ba_to'][$ct]));?>"></div>
		<div class="left p50"><input type="text" name="ba_price[<?php print $ct;?>]" size="12" value="<?php print htmlspecialchars(stripslashes($req['ba_price'][$ct]));?>"></div>
		<div class="clear"></div>
	</div>
	<?php 
		 $ct++;
	}
	print "<input type=\"hidden\" name=\"pcount\" value=\"".$req['pcount']."\">";

?>


</div>
		</form>

<div>&nbsp;</div>
<div class="underlinelabel">Buy All Options</div>
<div class="underlinespacer">Options created here are priced <b>per photo</b> IF Buy All is set to Price Per Photo or Tiered Pricing. Example, if you create an option for a paper type and the additional cost is $1.00, that will be $1.00 per photo in the gallery.<br><br>If the Buy All is set to <b>Set price</b>, then the price of the option is the price you enter. </div>

<div class="pc"><a href="" onclick="editoption('0','0','0','<?php print $pack['package_id'];?>'); return false;" >Create new option</a></div>
<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC ");
if(mysqli_num_rows($opts) <= 0 ) {?>
<div class="small">No options created</div> 
<?php } ?>
	<?php // SORT FUNCTION START
	$add = "opt-".$prod['pp_id'];
	?>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-<?php print $add;?>','sort_order-<?php print $add;?>','orderOptions');
	});
	</script>
	<form id="dd-form-<?php print $add;?>" action="index.php" method="post">
	<input type="hidden" name="prod_id" value="<?php print $prod['pp_id'];?>">
	<?php
	unset($order);
		$tops = whileSQL("ms_product_options", "*","WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC  ");	
		while($top = mysqli_fetch_array($tops)) {
		$order[] = $top['opt_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-<?php print $add;?>" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>
	</form>




	<ul id="sortable-list-<?php print $add;?>" class="sortable-list">




<?php while($opt = mysqli_fetch_array($opts))  { 
	?>
	<li title="<?php print $opt['opt_id'];?>"><div class="underline"><?php showProductOption($opt,$section); ?></div></li>
	<?php   } ?>
	</ul>
<?php if(mysqli_num_rows($opts)  > 1 ) {?><div class="pc">Drag & drop to change display order</div><?php } ?>

</div>
<div class="clear"></div>
	<?php if($setup['demo_mode'] !== true) { ?>
	<div class="underlinelabel">Thumbnail Photo</div>
	<div class="pc">Here you can upload a photo or photos to show with this in the price list.</div>

	<script>
		jQuery(document).ready(function() {
			sortItems('sortable-list-pics','sort_order-pics','orderPackagePhotos');
		});
		</script>
		<form id="dd-form" action="admin.action.php" method="post">
		<input type="hidden" name="action" value="orderPackagePhotos">
		<?php
			$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC");

		while($pic = mysqli_fetch_array($pics)) { 
			$order[] = $pic['bp_id'];
		}
		?>
		<input type="hidden" name="sort_order" id="sort_order-pics" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
		<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
		<p style="display: none;">
		  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
		  <label for="autoSubmit">Automatically submit on drop event</label>
		</p>





	<div class="pc center">
	<?php
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC");
		if(mysqli_num_rows($pics) <=0) { ?>
		<div class="pc">			No photos uploaded</div>
		<?php } ?>
	<ul id="sortable-list-pics" class="sortable-list center">
	<?php 

		while($pic = mysqli_fetch_array($pics)) { 
			$size = getimagefiledems($pic,'pic_th');
			?>
			<li title="<?php print $pic['bp_id'];?>" style="display: inline;">
			<div><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail"></div>
			<div><a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deleteThumb&pic_id=<?php print $pic['pic_id'];?>" onClick="return confirm('Are you sure you want to delete this thumbnail? ');" >Delete</a></div>
			</li>
			<?php } ?>
			</ul>
		</div>
		<div>


		<?php
		$photo_setup = doSQL("ms_photo_setup", "*", "  ");
		$width = $photo_setup['blog_width'];
		$height = $photo_setup['blog_height'];
		$thumb_size = $photo_setup['blog_th_width'];
		$thumb_size_height = $photo_setup['blog_th_height'];
		$mini_size = $site_setup['blog_mini_size'];
		$crop_thumbs = $photo_setup['blog_th_crop'];

		?>
		<input type="file" name="file_upload" id="file_upload" />
		<?php 
		$hash = $site_setup['salt']; 
		$timestamp = date('Ymdhis');
		?>
		<script>
		$(function() {
			$('#file_upload').uploadify({
				 'multi'    : false,

				<?php if($_REQUEST['debug'] == "1") { ?>
				'debug'    : true,	
				<?php } ?>
				'method'   : 'post',
				'fileSizeLimit':'20MB',
				'fileTypeExts' : '*.jpg',
				'fileTypeDesc' : 'jpg',
				'buttonText' : 'Select Photo',
				 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
					 'token' : '<?php echo md5($hash.$timestamp); ?>', 
					'package_id':'<?php print $pack['package_id'];?>',
					 'upload_session':'<?php print $upload_session;?>', 
					 'imageWidth':'<?php print $width;?>',
					 'imageHeight':'<?php print $height;?>',
					 'th_size':'<?php print $thumb_size;?>',
					 'th_size_height':'<?php print $thumb_size_height;?>',
					 'crop_thumbs':'<?php print $crop_thumbs;?>',
					 'crop_mini':'1',
					'mini_size':'<?php print $mini_size;?>',
					 'pic_client':'0',
					 'fileType':'<?php print $_REQUEST['fileType'];?>',
					 'watermark_photos':'<?php print $_REQUEST['watermark_photos'];?>',
					 'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
					'onQueueComplete' : function(queueData) {
					window.location.href='index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&sm=Photo Uploaded';
					}, 
						'onUploadError' : function(file, errorCode, errorMsg, errorString) {
						alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
					}, 

					'onUploadStart' : function(file) {
						$("#uploadmessage").slideUp(200, function() { 
						$("#uploadingmessage").slideDown(200);
						$('#file_upload-button').hide();
						});
					},


				'swf'      : 'uploadify/uploadify.swf',
				'uploader' : 'uploadify/sy-upload-photofy.php'
				// Put your options here
			});
		});
		</script>
		<div>&nbsp;</div>
	</div>

<?php } ?>
