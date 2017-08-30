<?php include $setup['path']."/".$setup['manage_folder']."/store/options.edit.php"; ?>
<?php
$pack = doSQL("ms_packages", "*", "WHERE package_id='".$_REQUEST['package_id']."' ");
foreach($_REQUEST AS $id => $value) {
	if(!is_array($_REQUEST[$id])) { 
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
}
if($_REQUEST['action'] == "addproduct") { 
	insertSQL("ms_packages_connect", "con_package='".$_REQUEST['package_id']."', con_product='".$_REQUEST['new_package_prod']."', con_qty='".$_REQUEST['new_package_qty']."' ");
	$_SESSION['sm'] = "Product added";
	header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['package_id']."&np=1 ");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "addcollection") { 
	insertSQL("ms_packages_connect", "con_package='".$_REQUEST['package_id']."', con_package_include='".$_REQUEST['new_package_collection']."', con_qty='".$_REQUEST['new_package_qty']."' ");
	$_SESSION['sm'] = "Collection added";
	header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['package_id']."&np=1 ");
	session_write_close();
	exit();
}



if($_REQUEST['action'] == "deletePackageProd") { 
	deleteSQL("ms_packages_connect", "WHERE con_id='".$_REQUEST['con_id']."'","1");
	$_SESSION['sm'] = "Product removed";
	header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['package_id']."");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "deletePackage") { 
	deleteSQL2("ms_photo_products_connect", "WHERE pc_package='".$_REQUEST['package_id']."'");
	deleteSQL2("ms_packages_connect", "WHERE con_package='".$_REQUEST['package_id']."'");
	deleteSQL("ms_packages", "WHERE package_id='".$_REQUEST['package_id']."'", "1");
	$_SESSION['sm'] = "Package removed";
	header("location: index.php?do=photoprods&view=packages");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "duplicatePackage") { 
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$_REQUEST['package_id']."' ");
	$new = insertSQL("ms_packages", "package_name='*** ".addslashes(stripslashes($pack['package_name']))." - COPY',  package_descr='".addslashes(stripslashes($pack['package_descr']))."', package_price='".addslashes(stripslashes($pack['package_price']))."',  package_taxable='".addslashes(stripslashes($pack['package_taxable']))."', package_select_amount='".$pack['package_select_amount']."', package_select_only='".$pack['package_select_only']."', package_require_all='".$pack['package_require_all']."' , package_ship='".$pack['package_ship']."', package_credit='".$pack['package_credit']."' , package_limit='".$pack['package_limit']."', package_add_ship='".$pack['package_add_ship']."', package_no_discount='".$pack['package_no_discount']."', package_internal_name='".$pack['package_internal_name']."', package_collapse_options='".$pack['package_collapse_options']."', package_preview_text='".addslashes(stripslashes($pack['package_preview_text']))."' "); 
	$prods = whileSQL("ms_packages_connect", "*", "WHERE con_package='".$pack['package_id']."' ");
	while($prod = mysqli_fetch_array($prods)) { 
		insertSQL("ms_packages_connect", "con_package='".$new."', con_product='".$prod['con_product']."', con_qty='".$prod['con_qty']."', con_order='".$prod['con_order']."' ");
	}


	$ios = whileSQL("ms_product_options", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_id ASC");
	while($io = mysqli_fetch_array($ios)) { 
		$x = 0;
		$qry = "";
		$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options");
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if($row['Field'] !== "opt_id") { 
					if($x > 0) { $qry.=","; } 
					$x++;
					if($row['Field'] == "opt_package") { 
						$qry .= $row['Field']."='".addslashes(stripslashes($new))."' ";
					} else { 
						$qry .= $row['Field']."='".addslashes(stripslashes($io[$row['Field']]))."' ";
					}
				//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
				}
			}
		}
		$opt_id = insertSQL("ms_product_options", "$qry" );

		############ Product Options Selections ###############
		$optsels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$io['opt_id']."' ORDER BY sel_id ASC");
		while($optsel = mysqli_fetch_array($optsels)) { 
			$x = 0;
			$qry = "";
			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options_sel");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "sel_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "sel_opt") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($opt_id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($optsel[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
			$sel_id = insertSQL("ms_product_options_sel", "$qry" );
		}
	}


	$_SESSION['sm'] = "New package created from duplication and shown below";
	header("location: index.php?do=photoprods&view=packages&package_id=".$new."");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "updatepackage") { 
	updateSQL("ms_packages", "package_name='".$_REQUEST['package_name']."', package_price='".$_REQUEST['package_price']."', package_taxable='".$_REQUEST['package_taxable']."', package_descr='".$_REQUEST['package_descr']."', package_select_amount='".$_REQUEST['package_select_amount']."', package_require_all='".$_REQUEST['package_require_all']."', package_ship='".$_REQUEST['package_ship']."' , package_credit='".$_REQUEST['package_credit']."' , package_limit='".$_REQUEST['package_limit']."', package_add_ship='".$_REQUEST['package_add_ship']."', package_no_discount='".$_REQUEST['package_no_discount']."', package_internal_name='".$_REQUEST['package_internal_name']."', package_collapse_options='".$_REQUEST['package_collapse_options']."', package_preview_text='".addslashes(stripslashes($_REQUEST['package_preview_text']))."' WHERE package_id='".$_REQUEST['package_id']."' ");
	$_SESSION['sm'] = "Package Updated";
	header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['package_id']."");
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

<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=packages&packag">Collections</a> <?php print ai_sep;?> <?php print $pack['package_name'];?></div>
<div class="clear"></div>
<div>&nbsp;</div>

<div style="width: 40%;" class="left">

	<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm();">

	<div style="margin: 0 16px 0 0;">
	<div class="underlinelabel">Collection Details</div>
	<div class="underline">
		<div class="label">Collection Name</div>
		<div><input type="text" name="package_name" id="package_name" class="field100 required inputtitle" value="<?php print htmlspecialchars($pack['package_name']);?>"></div>
	</div>
	<div class="underline">
		<div class="label">Internal Name</div>
		<div><input type="text" name="package_internal_name" id="package_internal_name" class="field100" value="<?php print htmlspecialchars($pack['package_internal_name']);?>"></div>
		<div>Used for your reference only</div>
	</div>

	<div class="underline">
		<div class="left" style="margin-right: 16px;">
		<div class="label">Collection Price</div>
		<div><input type="text" name="package_price" id="package_price" class="required" size="6" value="<?php print htmlspecialchars($pack['package_price']);?>"></div>
		</div>
		<div class="left">
		<div class="label">&nbsp;</div>
		<div><input type="checkbox" name="package_taxable" id="package_taxable" value="1" <?php if($pack['package_taxable'] == "1") { print "checked"; } ?>> <label for="package_taxable">Taxable</label></div>

		<div class="right textright"><input type="checkbox" name="package_ship" id="package_ship" value="1" <?php if($pack['package_ship'] == "1") { print "checked"; } ?>> <label for="package_ship">Shippable</label> <div class="moreinfo" info-data="packageshippable"><div class="info"></div></div></div>
		</div>
		
		
		
		<div class="clear"></div>
	</div>
	<?php if($pack['package_select_only'] !== "1") { ?>
		<?php if($pack['package_select_only'] !=="2") { ?>

		<div class="underline">
				<div class="label">Limit Poses</div>
				<div><input type="text" name="package_limit" id="package_limit" class="left" style="margin-right: 16px;" size="6" value="<?php print htmlspecialchars($pack['package_limit']);?>"> Limit the amont of poses that can be selected. Enter 0 to not limit.
				<div class="clear"></div></div>
		</div>
	<?php } ?>
	<?php } ?>

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

	<?php if($pack['package_select_only'] == "1") { ?>
	<div class="underline">
		<div><input type="text" name="package_select_amount" id="package_select_amount" size="4" class="center" value="<?php print $pack['package_select_amount'];?>"> photos to be selected</div>
	</div>
	<?php } ?>
	<?php if($pack['package_select_only'] !=="2") { ?>
	<div class="underline">
		<div><input type="checkbox" name="package_require_all" id="package_require_all"  value="1" <?php if($pack['package_require_all'] == "1") { print "checked"; } ;?>> <label for="package_require_all">Require all products to have photo selected.</label></div>
	</div>
	<?php } ?>
	<div class="underline">
		<div><input type="checkbox" name="package_no_discount" id="package_no_discount" value="1" <?php if($pack['package_no_discount'] == "1") { print "checked"; } ?>> <label for="package_no_discount">Do not allow discounting </label></div>
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



	<div class="underline">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="packages">
		<input type="hidden" name="package_id" value="<?php print $pack['package_id'];?>">
		<input type="hidden" name="action" value="updatepackage">
		<input type="submit" name="submit" value="Update Collection" class="submit" id="submitButton">
	</div>
	<div class="underline">
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deletePackage" onClick="return confirm('Are you sure you want to DELETE this package? Deleting this will permanently remove it and can not be reversed.');">Delete Collection</a> &nbsp; 
		<a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=duplicatePackage" onClick="return confirm('Are you sure you want to DUPLICATE this package to create a new one?');">Duplicate Collection</a> 
		</div>
	</div>
	</form>
	<div>&nbsp;</div>
	<?php if($setup['demo_mode'] !== true) { ?>
	<div class="underlinelabel">Collection Photos</div>
	<div class="pc">Here you can upload a photo or photos to show with the package in the price list.</div>

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
			<li title="<?php print $pic['bp_id'];?>">
			<div><img src="<?php print getimagefile($pic,'pic_th');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail"></div>
			<div><a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deleteThumb&pic_id=<?php print $pic['pic_id'];?>" onClick="return confirm('Are you sure you want to delete this thumbnail? ');" >Delete</a></div>
			</li>
			<?php } ?>
			</ul>
		</div>
		</form>
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
				 'multi'    : true,

				<?php if($_REQUEST['debug'] == "1") { ?>
				'debug'    : true,	
				<?php } ?>
				'method'   : 'post',
				'fileSizeLimit':'20MB',
				'fileTypeExts' : '*.jpg;*.png',
				'fileTypeDesc' : 'jpg;png',
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

</div>

<div style="width: 60%;" class="left">
<?php $num_prods = countIt("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id",  "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
if($num_prods <=0) { $_REQUEST['np'] = "1"; } 
?>
	<div class="underlinelabel">Collection Products</div>
	<?php if($pack['package_select_only'] =="1") { ?><div class="pc">Collection set to selection only. Unable to add other products. </div><?php } ?>


<?php if($pack['package_select_only'] =="2") { ?>

		<div class="underlinespacer buttons"><a href="" onclick="shownewproduct(); return false;">Add new collection to collection</a></div>

		<div class="underline" id="newpackageprod" <?php if($_REQUEST['np'] !== "1") { ?>style="display: none;"<?php } ?>>
			<form method="post" name="newcol" action="index.php"  onSubmit="return checkForm('.newprod','newprodsave');">

			<div class="label">Select collection to add <div class="moreinfo" info-data="addproductstocollection"><div class="info"></div></div></div>

			<div class="left" style="margin-right: 12px;">
			<select name="new_package_collection" id="new_package_collection" class="newprod">
			<option value="">Select Collection</option>
			<?php $prods = whileSQL("ms_packages", "*"," WHERE package_id!='".$pack['package_id']."' ORDER BY package_name ASC ");
			while($prod = mysqli_fetch_array($prods)) { ?>
			<option value="<?php print $prod['package_id'];?>"><?php print $prod['package_name'];?> <?php if($prod['package_buy_all'] == "1") { ?> (Buy All)<?php } ?> <?php if(!empty($prod['package_internal_name'])) { print " (".$prod['package_internal_name'].")"; } ?></option>
			<?php } ?>
			</select>
			</div>
			<div class="left">
				Qty: <input type="text" size="2" name="new_package_qty" id="new_package_qty" value="1" class="center newprod">
			</div>
			<div class="left">&nbsp; 
			<input type="submit" name="submit" value="add" class="submitSmall" id="newprodsave">
			</div>
			<div class="clear"></div>
			<input type="hidden" name="do" value="photoprods">
			<input type="hidden" name="view" value="packages">
			<input type="hidden" name="package_id" value="<?php print $pack['package_id'];?>">
			<input type="hidden" name="action" value="addcollection">
			
			</form>
		</div>
		
		<div id="message-box" class="pageContent"><?php echo $message; ?></div>
		<script>
		jQuery(document).ready(function() {
			sortItems('sortable-list','sort_order','orderPackageProducts');
		});
		</script>
		<form id="dd-form" action="admin.action.php" method="post">
		<input type="hidden" name="action" value="orderPackageProducts">
		<input type="hidden" name="link_location" value="topmain">
		<?php $prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package_include='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			$order[] = $prod['con_id'];
		}
		?>
		<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
		<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
		<p style="display: none;">
		  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
		  <label for="autoSubmit">Automatically submit on drop event</label>
		</p>


		<?php if($num_prods > 0) { ?>
		<div class="underlinecolumn">
			<div class="left" style="width: 10%;">&nbsp;</div>
			<div class="left" style="width: 35%;">Product</div>
			<div class="left" style="width: 5%;">Qty</div>
			<!-- 
			<div class="left center" style="width: 20%;">Extra Photo <div class="moreinfo" info-data="packageextraphoto"><div class="info"></div></div></div>
			<div class="left center" style="width: 20%;">Extra New Photo <div class="moreinfo" info-data="packageextraphotonew"><div class="info"></div></div></div>
			-->
			<div class="left" style="width: 10%;">&nbsp;</div>

			<div class="clear"></div>
			</div>
			<?php } ?>
		<ul id="sortable-list" class="sortable-list">

		<?php $prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { ?>
		<li title="<?php print $prod['con_id'];?>">
		<div class="underline">
			<div class="left" style="width: 10%;"><?php print ai_sort; ?> <a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deletePackageProd&con_id=<?php print $prod['con_id'];?>"><?php print ai_delete;?></a></div>
			<div class="left" style="width: 35%;">
			<div><h3><?php print $prod['package_name'];?>  <?php if($prod['package_buy_all'] == "1") { ?> (Buy All)<?php } ?> </h3></div>
			<?php if(!empty($prod['package_internal_name'])) { print "<div>".$prod['package_internal_name']."</div>"; } ?>	
			</div>
			<div class="left" style="width: 5%;">
			<input type="text" size="2"  name="con_qty-<?php print $prod['con_id'];?>" id="con_qty-<?php print $prod['con_id'];?>" value="<?php print $prod['con_qty'];?>" class="center">
			</div>
			<!-- 
			<div class="left center" style="width: 20%;"><input type="text" id="con_extra_price-<?php print $prod['con_id'];?>" size="5" value="<?php print $prod['con_extra_price'];?>"></div>
			<div class="left center" style="width: 20%;"><input type="text" id="con_extra_price_new_photo-<?php print $prod['con_id'];?>" size="5" value="<?php print $prod['con_extra_price_new_photo'];?>"></div>
			-->
			<div class="left p10 textright"><span  id="update-<?php print $prod['con_id'];?>"><a href="" onclick="updateqty('<?php print $prod['con_id'];?>'); return false;">update</a></span> <span  id="updating-<?php print $prod['con_id'];?>" style="display: none;"><img src="graphics/loading2.gif"> updating ......</span></div>
			<div class="clear"></div>
			</div>
			</li>


		<?php } ?>
		</ul>
		<?php } // End selecting collections ?>

<div>&nbsp;</div>





	<?php if($pack['package_select_only'] <=0) { ?>
		<div class="underlinespacer buttons"><a href="" onclick="shownewproduct(); return false;">Add new product to collection</a></div>

		<div class="underline" id="newpackageprod" <?php if($_REQUEST['np'] !== "1") { ?>style="display: none;"<?php } ?>>
			<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm('.newprod','newprodsave');">

			<div class="label">Select product to add <div class="moreinfo" info-data="addproductstocollection"><div class="info"></div></div></div>

			<div class="left" style="margin-right: 12px;">
			<select name="new_package_prod" id="new_package_prod" class="newprod">
			<option value="">Select product</option>
			<?php $prods = whileSQL("ms_photo_products", "*","  WHERE pp_free!='1' ORDER BY pp_name ASC ");
			while($prod = mysqli_fetch_array($prods)) { ?>
			<option value="<?php print $prod['pp_id'];?>"><?php print $prod['pp_name'];?><?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?></option>
			<?php } ?>
			</select>
			</div>
			<div class="left">
				Qty: <input type="text" size="2" name="new_package_qty" id="new_package_qty" value="1" class="center newprod">
			</div>
			<div class="left">&nbsp; 
			<input type="submit" name="submit" value="add" class="submitSmall" id="newprodsave">
			</div>
			<div class="clear"></div>
			<input type="hidden" name="do" value="photoprods">
			<input type="hidden" name="view" value="packages">
			<input type="hidden" name="package_id" value="<?php print $pack['package_id'];?>">
			<input type="hidden" name="action" value="addproduct">
			
			</form>
		</div>
		
		<div id="message-box" class="pageContent"><?php echo $message; ?></div>
		<script>
		jQuery(document).ready(function() {
			sortItems('sortable-list','sort_order','orderPackageProducts');
		});
		</script>
		<form id="dd-form" action="admin.action.php" method="post">
		<input type="hidden" name="action" value="orderPackageProducts">
		<input type="hidden" name="link_location" value="topmain">
		<?php $prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			$order[] = $prod['con_id'];
		}
		?>
		<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
		<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
		<p style="display: none;">
		  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
		  <label for="autoSubmit">Automatically submit on drop event</label>
		</p>


		<?php if($num_prods > 0) { ?>
		<div class="underlinecolumn">
			<div class="left" style="width: 10%;">&nbsp;</div>
			<div class="left" style="width: 35%;">Product</div>
			<div class="left" style="width: 5%;">Qty</div>
			<div class="left center" style="width: 20%;">Extra Photo <div class="moreinfo" info-data="packageextraphoto"><div class="info"></div></div></div>
			<div class="left center" style="width: 20%;">Extra New Photo <div class="moreinfo" info-data="packageextraphotonew"><div class="info"></div></div></div>
			<div class="left" style="width: 10%;">&nbsp;</div>

			<div class="clear"></div>
			</div>
			<?php } ?>
		<ul id="sortable-list" class="sortable-list">

		<?php $prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { ?>
		<li title="<?php print $prod['con_id'];?>">
		<div class="underline">
			<div class="left" style="width: 10%;"><?php print ai_sort; ?> <a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>&action=deletePackageProd&con_id=<?php print $prod['con_id'];?>"><?php print ai_delete;?></a></div>
			<div class="left" style="width: 35%;">
			<div><h3><?php print $prod['pp_name'];?></h3></div>
			<?php if(!empty($prod['pp_internal_name'])) { print "<div>".$prod['pp_internal_name']."</div>"; } ?>	
			</div>
			<div class="left" style="width: 5%;">
			<input type="text" size="2"  name="con_qty-<?php print $prod['con_id'];?>" id="con_qty-<?php print $prod['con_id'];?>" value="<?php print $prod['con_qty'];?>" class="center">
			</div>

			<div class="left center" style="width: 20%;"><input type="text" id="con_extra_price-<?php print $prod['con_id'];?>" size="5" value="<?php print $prod['con_extra_price'];?>"></div>
			<div class="left center" style="width: 20%;"><input type="text" id="con_extra_price_new_photo-<?php print $prod['con_id'];?>" size="5" value="<?php print $prod['con_extra_price_new_photo'];?>"></div>
			<div class="left p10 textright"><span  id="update-<?php print $prod['con_id'];?>"><a href="" onclick="updateqty('<?php print $prod['con_id'];?>'); return false;">update</a></span> <span  id="updating-<?php print $prod['con_id'];?>" style="display: none;"><img src="graphics/loading2.gif"> updating ......</span></div>
			<div class="clear"></div>
			</div>
			</li>


		<?php } ?>
		</ul>
		<?php } // End selecting products ?>
	<?php if(($pack['package_select_only'] <=0) && ($num_prods > 0) == true) { ?>
	<div class="pc textright" style="font-size: 13px; color: #EEAE71">Important: If using the Extra Photo and Extra New Photo option you MUST enter a price for both.</div>
	<?php } ?>
<div>&nbsp;</div>
<div class="underlinelabel">Collection Options</div>
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
