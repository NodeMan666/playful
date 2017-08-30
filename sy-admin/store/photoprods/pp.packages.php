<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> Collections</div>
<div class="pc">Collections (or packages) are a collection of products to be purchased and the customer will select the photos for the collection.  <?php if($setup['unbranded'] !== true) { ?>
<a href="https://www.picturespro.com/sytist-manual/photo-products/collections/" target="_blank">More information in the manual</a>.
<?php } ?></div><script>
function batcheditpages() {
	var pageids = 0;
	$(".batchselect").each(function(i){
		if($(this).attr("checked")) {
			pageids += "|"+$(this).attr("id");
		//	alert($(this).attr("id"));
		}
	});
	pagewindowedit("store/photoprods/packages-batch-edit.php?noclose=1&nofonts=1&nojs=1&prodids="+pageids);
	// alert(pageids);
}

function selectallpages() { 
	$(".batchselect").attr("checked",true);
	hlprods();
}
function deselectallpages() { 
	$(".batchselect").attr("checked",false);
	hlprods();
}
$(document).ready(function(){
	hlprods();

	$(".batchselect").change(function () { 
		if($(this).attr("checked")) { 
			$("#prod-"+$(this).val()).removeClass("underline").addClass("underlinehl");
		} else { 
			$("#prod-"+$(this).val()).removeClass("underlinehl").addClass("underline");
		}
	});
});

function hlprods() { 
	$(".batchselect").each(function() {
		if($(this).attr("checked")) { 
			$("#prod-"+$(this).val()).removeClass("underline").addClass("underlinehl");
		} else { 
			$("#prod-"+$(this).val()).removeClass("underlinehl").addClass("underline");
		}
	});
}

</script>

<?php 
if($_REQUEST['action'] == "deleteOption") { 
	$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
	if(!empty($opt['opt_id'])) { 
		deleteSQL("ms_product_options", "WHERE opt_id='".$opt['opt_id']."' ", "1");
		deleteSQL2("ms_product_options_sel", "WHERE sel_opt='".$opt['opt_id']."' ");
	}
	$_SESSION['sm'] = "Option deleted";
	session_write_close();
	header("location: index.php?do=photoprods&view=packages&package_id=".$opt['opt_package']."");
	exit();

}

if($_REQUEST['action'] == "collapseallpackages") { 
	updateSQL("ms_packages", "package_collapse_options='1' ");
	$_SESSION['sm'] = "All packages have been updated";
	session_write_close();
	header("location: index.php?do=photoprods&view=packages&package_id=".$opt['opt_package']."");
	exit();

}

if($_REQUEST['action'] == "saveproduct") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(!empty($_REQUEST['package_id'])) { 

	} else { 
		$id = insertSQL("ms_packages", "
		package_name='".$_REQUEST['package_name']."', package_price='".$_REQUEST['package_price']."', package_taxable='1', package_select_amount='".$_REQUEST['package_select_amount']."', package_select_only='".$_REQUEST['package_select_only']."', package_require_all='1', package_ship='1',package_collapse_options='1' ");
		$_SESSION['sm'] = $_REQUEST['package_name']." Saved";
		header("location: index.php?do=photoprods&view=packages&package_id=".$id."");
		session_write_close();
		exit();

	} 
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

		<div class="pc"><span  class="buttons"><a href="" onClick="createPriceList(); return false;">Create New Collection</a></span></div>
		<div class="" style="display: none;" id="newpricelist">
		<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm();">
		<div class="underline" style="text-align: left;">
			<div class="left" style="margin-right: 8px;">
				<div class="label">Enter a name for this collection</div>
				<div><input type="text" name="package_name" id="pacakge_name" class="required field100" size="20"  value="<?php print htmlspecialchars($pp['list_name']);?>"></div>
			</div>
			<div class="left" style="margin-right: 8px;">
				<div class="label">Price</div>
				<div><input type="text" name="package_price" id="pacakge_price" class="" size="8"  value="0.00"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline" style="text-align: left;">
			<div class="label"><input type="radio" name="package_select_only" id="package_select_only_0" value="0" onchange="getpackagetype();" checked> I will assign products to the collection <div class="moreinfo" info-data="assignphotospackage"><div class="info"></div></div></div>
		</div>
		<div class="underline" style="text-align: left;">
			<div class="label"><input type="radio" name="package_select_only" id="package_select_only_1" value="1" onchange="getpackagetype();"> Making selections only <div class="moreinfo" info-data="selectonlypackage"><div class="info"></div></div></div>
		</div>
		<div class="underline" style="text-align: left; display: none;" id="package_select_amount_div">
			<div class="label"><input type="text" name="package_select_amount" id="package_select_amount" size="4" class="center" value="10"> photos to select</div>
		</div>
		<div class="underline" style="text-align: left;">
			<div class="label"><input type="radio" name="package_select_only" id="package_select_only_2" value="2" onchange="getpackagetype();"> Including other collections <div class="moreinfo" info-data="collectionincludecollection"><div class="info"></div></div></div>
		</div>

		<div class="pc">Once you create you will be able to select products for the collection.</div>
		<div class="pc">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="packages">
		<input type="hidden" name="action" value="saveproduct">
		<input type="submit" name="submit" value="Create" id="submitButton" class="submitSmall">
		</div>
		</form>
		</div>
		</div>
<div class="clear"></div>
<?php 
$packs = whileSQL("ms_packages", "*","WHERE package_buy_all='0' ORDER BY package_name ASC ");
if(mysqli_num_rows($packs) > 1) { ?>
<div class="pc"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull; <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Collection Prices</a></div>
<?php } ?>

<div class="underlinelabel">
	<div class="p5 left">&nbsp;</div>
	<div class="p30 left">Name</div>
	<div class="p15 left">Price</div>
	<div class="p40 left">Products</div>
	<div class="p10 left center">#Sold</div>
	<div class="clear"></div>
</div>
<?php if(mysqli_num_rows($packs)<=0) { ?>
<div class="underline center">No collections created</div>
<?php } ?>
<div>&nbsp;</div>
<?php 
while($pack = mysqli_fetch_array($packs)) { 
	$x = 0;
	?>
<div class="underline" id="prod-<?php print $pack['package_id'];?>">
	<div class="p5 left"><input  name="prod_id" id="<?php print $pack['package_id'];?>" value="<?php print $pack['package_id'];?>" class="batchselect inputtip" title="Select to batch edit" type="checkbox" > </div>
	<div style="width: 30%; float: left;">
	<?php 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ");
		if(!empty($pic['pic_id'])) { 
			// $size = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
			?>
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>"><img src="<?php print getimagefile($pic,'pic_mini')?>" border="0" class="thumbnail left" style="margin: 0 8px 8px 0;"></a>
		<?php } ?>

		<div><h2><a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>"><?php print $pack['package_name'];?></a> </h2>
		<?php if(!empty($pack['package_internal_name'])) { print $pack['package_internal_name']; } ?></div>
		<div class="sub small">
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>">view</a> &nbsp;&nbsp; 
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deletePackage"   onClick="return confirm('Are you sure you want to delete this package? Deleting this will permanently remove it and can not be reversed.');">delete</a> &nbsp;&nbsp;  
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=duplicatePackage"   onClick="return confirm('Are you sure you want to DUPLICATE this package?');">duplicate</a> &nbsp;&nbsp;  
		</div>
	</div>
	<div style="width: 15%; float: left;">
	<div><h3><?php print showPrice($pack['package_price']);?></h3></div>
	<?php if($pack['package_add_ship'] > 0) { ?>
	<div><?php print showPrice($pack['package_add_ship']);?> additional shipping</div>
	<?php } ?>
	</div>

	<div style="width: 40%; float: left;">
	<?php 
	$x = 0;
	if($pack['package_select_only'] == "1") { print $pack['package_select_amount']." photos to be selected"; 
	} elseif($pack['package_select_only'] == "2") { 

		$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			if($x > 0) { print ", "; } 
			print "<nobr><a href=\"index.php?do=photoprods&view=packages&package_id=".$prod['package_id']."\">".$prod['package_name']."</a></nobr>";
			$x++;
		}

		print "&nbsp;";
	} else { 
		$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			if($x > 0) { print ", "; } 
			print "<nobr>".$prod['con_qty'].": ".$prod['pp_name']."</nobr>";
			$x++;
		}
	}
		?> 

	</div>
	<div style="width: 10%; float: left; text-align: center;">
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
<?php if(mysqli_num_rows($packs) > 1) { ?>
<div class="pc"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull; <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Collection Prices</a></div>
<?php } ?>
<div>&nbsp;</div>
<div class="pc"><a href="index.php?do=photoprods&view=packages&action=collapseallpackages"  onClick="return confirm('Are you sure? Click OK to continue.');">Collapse All Collection Options & Descriptions</a><br>
This option will update all existing collections to collapse options & descriptions. This was added if you are updating to the 1.9 update with the new product lists.</div>
<div>&nbsp;</div>
