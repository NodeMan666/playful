<?php require "w-header.php"; ?>
<?php include $setup['path']."/".$setup['manage_folder']."/store/options.edit.php"; ?>
<script>
$(document).ready(function(){
	$(".confirmdelete").click(function() { 
		$("#confirm-link").attr("href",$(this).attr("href"));
		$("#confirm-link").attr("target",$(this).attr("target"));
		$("#confirm-title").html($(this).attr("confirm-title"));
		$("#confirm-message").html($(this).attr("confirm-message"));

		$("#pagewindowbgcontainer").fadeIn(100, function() { 
			$("#confirm-link").focus();
				$("#confirmdelete").css({"display":"inline","opacity":"0", "visibility":"visible", "top":"+=60", "z-index":"500"});
				$("#confirmdelete").animate({"opacity":"1", "top":"-=60"},200, "easeOutBack");
		});

		return false;
	});

});
</script>
<?php


if($_POST['action'] == "savecopyoptions") { 
	print "<pre>";
	print_r($_POST);
	foreach($_POST['pp_id'] AS $id) { 
		print "<li>$id";
		$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
		$opt_id = insertSQL("ms_product_options", "opt_name='".addslashes(stripslashes($opt['opt_name']))."' , opt_descr='".addslashes(stripslashes($opt['opt_descr']))."' , opt_type='".addslashes(stripslashes($opt['opt_type']))."' , opt_required='".addslashes(stripslashes($opt['opt_required']))."', opt_photo_prod='".$id."', opt_date='".$opt['opt_date']."' , opt_text_field_size='".$opt['opt_text_field_size']."', opt_price='".$opt['opt_price']."', opt_price_checked='".$opt['opt_price_checked']."', opt_download_size='".$opt['opt_download_size']."', opt_price_download='".$opt['opt_price_download']."', opt_disable_download='".$opt['opt_disable_download']."' ");
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ");
		while($sel = mysqli_fetch_array($sels)) { 
			$in = insertSQL("ms_product_options_sel", "sel_opt='".$opt_id."', sel_name='".addslashes(stripslashes($sel['sel_name']))."', sel_descr='".addslashes(stripslashes($sel['sel_descr']))."', sel_price='".$sel['sel_price']."', sel_order='".$sel['sel_order']."' , sel_default='".$sel['sel_default']."', sel_add_time='".$sel['sel_add_time']."' ");
		}

	}

	$_SESSION['sm'] = "Option copied";
	if($_REQUEST['opt_photo_prod'] > 0) { 
		header("location: index.php?do=photoprods&view=base");
	}
	session_write_close();
	exit();

}
if($_POST['submitit']=="yes") { 
	if(($_REQUEST['opt_date'] > 0) || ($_REQUEST['opt_service'] > 0) == true){  
		$opt_order = ", opt_order='".$_REQUEST['opt_order']."' ";
	}


	if($_REQUEST['opt_id'] > 0) { 
		updateSQL("ms_product_options", "opt_name='".addslashes(stripslashes($_REQUEST['opt_name']))."' , opt_descr='".addslashes(stripslashes($_REQUEST['opt_descr']))."' , opt_label='".addslashes(stripslashes($_REQUEST['opt_label']))."' , opt_type='".addslashes(stripslashes($_REQUEST['opt_type']))."' , opt_required='".addslashes(stripslashes($_REQUEST['opt_required']))."' , opt_text_field_size='".$_REQUEST['opt_text_field_size']."', opt_price='".$_REQUEST['opt_price']."' , opt_price_checked='".$_REQUEST['opt_price_checked']."', opt_photos='".$_REQUEST['opt_photos']."', opt_download_size='".$_REQUEST['opt_download_size']."', opt_price_download='".$_REQUEST['opt_price_download']."', 
		pp_watermark='".$_REQUEST['pp_watermark']."',
		pp_watermark_file='".addslashes(stripslashes($_REQUEST['pp_watermark_file']))."',
		pp_watermark_location='".$_REQUEST['pp_watermark_location']."',
		pp_logo='".$_REQUEST['pp_logo']."',
		pp_logo_file='".addslashes(stripslashes($_REQUEST['pp_logo_file']))."',
		pp_logo_location='".$_REQUEST['pp_logo_location']."',
		opt_service='".$_REQUEST['opt_service']."',
		opt_addition_time='".$_REQUEST['opt_addition_time']."',

		
		opt_disable_download='".$_REQUEST['opt_disable_download']."' $opt_order WHERE opt_id='".$_REQUEST['opt_id']."' ");
		$opt_id = $_REQUEST['opt_id'];
	} else {
		$opt_id = insertSQL("ms_product_options", "opt_name='".addslashes(stripslashes($_REQUEST['opt_name']))."' , opt_descr='".addslashes(stripslashes($_REQUEST['opt_descr']))."' , opt_type='".addslashes(stripslashes($_REQUEST['opt_type']))."' , opt_required='".addslashes(stripslashes($_REQUEST['opt_required']))."', opt_photo_prod='".$_REQUEST['opt_photo_prod']."', opt_package='".$_REQUEST['opt_package']."', opt_date='".$_REQUEST['opt_date']."', opt_label='".addslashes(stripslashes($_REQUEST['opt_label']))."'  , opt_text_field_size='".$_REQUEST['opt_text_field_size']."', opt_price='".$_REQUEST['opt_price']."', opt_price_checked='".$_REQUEST['opt_price_checked']."', opt_photos='".$_REQUEST['opt_photos']."', opt_download_size='".$_REQUEST['opt_download_size']."', opt_price_download='".$_REQUEST['opt_price_download']."', 
		pp_watermark='".$_REQUEST['pp_watermark']."',
		pp_watermark_file='".addslashes(stripslashes($_REQUEST['pp_watermark_file']))."',
		pp_watermark_location='".$_REQUEST['pp_watermark_location']."',
		pp_logo='".$_REQUEST['pp_logo']."',
		pp_logo_file='".addslashes(stripslashes($_REQUEST['pp_logo_file']))."',
		pp_logo_location='".$_REQUEST['pp_logo_location']."',
		opt_service='".$_REQUEST['opt_service']."',
		opt_addition_time='".$_REQUEST['opt_addition_time']."',

		opt_disable_download='".$_REQUEST['opt_disable_download']."' $opt_order");

	}

	deleteSQL2("ms_product_options_sel", "WHERE  sel_opt='" .$_REQUEST['opt_id']. "' ");

	foreach($_REQUEST['sel_name'] AS $id => $opt) {
		$thisCount++;
		print "<li>$opt";
		if(!empty($opt)) {
			if($_REQUEST['sel_default'] == $thisCount) {
				$and_add = ", sel_default='1' ";
			}
			if($_REQUEST['opt_photos'] == "1") { 
				$sel_photos_add = ",  sel_photos='".$_REQUEST['sel_photos'][$id]."' ";
			}
			// print_r($sel_photos);
			// print "<li>sel_photos: ".$sel_photos." and_add: ". $and_add."</li>";
			$in = insertSQL("ms_product_options_sel", "sel_opt='".$opt_id."', sel_name='".addslashes(stripslashes($opt))."', sel_descr='".addslashes(stripslashes($_REQUEST['sel_descr'][$id]))."', sel_price='".$_REQUEST['sel_price'][$id]."', sel_order='".$_REQUEST['sel_order'][$id]."', sel_add_time='".$_REQUEST['sel_add_time'][$id]."' $sel_photos_add $and_add ");
			unset($and_add);
		}
	}
	$_SESSION['sm'] = "Option saved";
	if($_REQUEST['opt_photo_prod'] > 0) { 
		header("location: index.php?do=photoprods&view=base");
	}

	if($_REQUEST['opt_package'] > 0) { 
		$package = doSQL("ms_packages", "*", "WHERE package_id='".$_REQUEST['opt_package']."' ");
		if($package['package_buy_all'] == "1") { 
			header("location: index.php?do=photoprods&view=buyalls&package_id=".$_REQUEST['opt_package']."");
		} else { 
			header("location: index.php?do=photoprods&view=packages&package_id=".$_REQUEST['opt_package']."");
		}
	}
	if($_REQUEST['opt_date'] > 0) { 
		header("location: index.php?do=news&action=addDate&date_id=".$_REQUEST['opt_date']."");
	}
	if($_REQUEST['opt_service'] > 0) { 
		header("location: index.php?do=booking&view=services");
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


<script>
function optiontype() { 
	$(".opttype").slideUp(200);
	$("#requiredcheckbox").slideDown(200);

	if($("#opt_type").val() == "dropdown") { 
		$("#selectoptions").slideDown(200);
	}
	if($("#opt_type").val() == "tabs") { 
		$("#selectoptions").slideDown(200);
	}

	if($("#opt_type").val() == "radio") { 
		$("#selectoptions").slideDown(200);
	}

	if($("#opt_type").val() == "text") { 
		$("#textoption").slideDown(200);
	}
	if($("#opt_type").val() == "checkbox") { 
		$("#checkboxoption").slideDown(200);
		$("#requiredcheckbox").slideUp(200);
	}
	if($("#opt_type").val() == "download") { 
		$("#downloadoption").slideDown(200);
	}


}

function selectoptphotos() { 
	if($("#opt_photos").attr("checked")) { 
		$(".opt_photos").fadeIn(100);
	} else { 
		$(".opt_photos").fadeOut(100);
	}
}

function watermarksettings() { 
	if($("#pp_watermark").attr("checked")) { 
		$(".watermarksettings").show();
	} else { 
		$(".watermarksettings").hide();
	}
}

function logosettings() { 
	if($("#pp_logo").attr("checked")) { 
		$(".logosettings").show();
	} else { 
		$(".logosettings").hide();
	}
}
</script>

<?php if($_REQUEST['do'] == "editOption") { 
	if(($_REQUEST['opt_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
		if(empty($opt['opt_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($opt AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<form name="editoptionform" action="w-product-options.php" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underlinelabel">
	<?php
	if($_REQUEST['opt_photo_prod'] > 0) { 
		$where = "opt_photo_prod='".$_REQUEST['opt_photo_prod']."' "; 
		$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['opt_photo_prod']."' ");
		?>Option for <?php print $prod['pp_name'];?>
		<?php 
	}

	if($_REQUEST['opt_package'] > 0) { 
		$where = "opt_package='".$_REQUEST['opt_package']."' "; 
		$package = doSQL("ms_packages", "*", "WHERE package_id='".$_REQUEST['opt_package']."' ");
		?>Option for <?php print $package['package_name'];?>
		<?php 
	}

	if($_REQUEST['opt_date'] > 0) { 
		$where = "opt_date='".$_REQUEST['opt_date']."' "; 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['opt_date']."' ");
		?>Option for <?php print $date['date_title'];?>
		<?php 
		$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
		if($dcat['cat_type'] == "booking") { 
			$booking_cat = 1;
		}
	}

	if($_REQUEST['opt_service'] > 0) { 
		$where = "opt_service='".$_REQUEST['opt_service']."' "; 
		$service = doSQL("ms_bookings_services", "*", "WHERE service_id='".$_REQUEST['opt_service']."' ");
		?>Option for <?php print $service['service_name'];?>
		<?php 
	}

?>
	</div>

	<?php if(($date['date_id'] > 0)&&($_REQUEST['opt_id'] > 0)==true) { ?>
	<div class="pc"><a  id="removealllink" class="confirmdelete" confirm-title="Really?" confirm-message="Are you sure you want to delete this option?" href="w-product-options.php?action=deleteOption&opt_id=<?php print $_REQUEST['opt_id'];?>">delete option</a>
	</div>

	<?php } ?>
		<div class="underline">
			<div class="label">Option name</div>
			<div><input type="text" name="opt_name" id="opt_name" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt_name']));?>" class="optrequired field100"></div>
		</div>
		<div class="underline">
			<div style="width: 50%; float: left;">
			<div class="label">Option type</div>
			<div>
			<select name="opt_type" id="opt_type" class="optrequired" onchange="optiontype();">
			<option value="">Select</option>
			<?php if($date['date_id'] > 0) { ?>
			<option value="tabs" <?php if($_REQUEST['opt_type'] == "tabs") { print "selected"; } ?>>Tab Menu</option>
			<?php } ?>
			<option value="dropdown" <?php if($_REQUEST['opt_type'] == "dropdown") { print "selected"; } ?>>Dropdown Menu</option>
			<option value="checkbox" <?php if($_REQUEST['opt_type'] == "checkbox") { print "selected"; } ?>>Checkbox</option>
			
			<option value="text" <?php if($_REQUEST['opt_type'] == "text") { print "selected"; } ?>>Text Field</option>
			<?php if(($date['date_id'] <= 0)&&($booking_cat <= 0)&&($_REQUEST['opt_package'] <=0)==true) { ?>
			<option value="download" <?php if($_REQUEST['opt_type'] == "download") { print "selected"; } ?>>Download</option>
			<?php } ?>
			<?php if(($date['date_id'] > 0) &&($booking_cat <= 0) == true) { ?>
			<option value="date" <?php if($_REQUEST['opt_type'] == "date") { print "selected"; } ?>>Date</option>
			<?php } ?>

			<!-- <option value="radio" <?php if($_REQUEST['opt_type'] == "radio") { print "selected"; } ?>>Radio Buttons</option> -->
			</select>
			</div>
			</div>
			<div style="width: 50%; float: left;" id="requiredcheckbox" class="<?php if($_REQUEST['opt_type'] == "checkbox") { ?>hide<?php } ?>">
			<div class="label">Required option</div>
			<div><input type="checkbox" name="opt_required" id="opt_required" value="1" <?php if($_REQUEST['opt_required'] == "1") { print "checked"; } ?>> <label for="opt_required">Check this box if this option is required.</label></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="label">Description (optional)</div>
			<div><textarea name="opt_descr" id="opt_descr" rows="3" cols="20" class="field100"><?php  print htmlspecialchars(stripslashes($_REQUEST['opt_descr']));?></textarea></div>
		</div>
	<div>&nbsp;</div>

	<div id="textoption" class="opttype" <?php if($_REQUEST['opt_type'] !== "text") { ?>style="display: none;"<?php } ?>>
		<div class="underlinelabel">Text option additional price</div>
		<div class="underline">
			<?php if(empty($_REQUEST['opt_price'])) { $_REQUEST['opt_price'] = "0.00"; } ?>
			<div class="label">If anything is entered into this text option, charge this amount: </div>
			<div><input type="text" name="opt_price" id="opt_price" value="<?php print $_REQUEST['opt_price'];?>" size="6" class="center"></div>
		</div>
		<div class="underline">
			<div class="label">Text field size (example: 20)</div>
			<?php if(empty($_REQUEST['opt_text_field_size'])) { $_REQUEST['opt_text_field_size'] = 20; } ?>
			<div><input type="text" name="opt_text_field_size" id="opt_text_field_size" value="<?php print $_REQUEST['opt_text_field_size'];?>" size="6" class="center"></div>
		</div>

	
	</div>
	<div id="checkboxoption" class="opttype" <?php if($_REQUEST['opt_type'] !== "checkbox") { ?>style="display: none;"<?php } ?>>

		<div class="underlinelabel">Price if checked</div>
		<div class="underline">
			<?php if(empty($_REQUEST['opt_price_checked'])) { $_REQUEST['opt_price_checked'] = "0.00"; } ?>
			<div class="label">If the checkbox is checked, charge this amount:</div>
			<div><input type="text" name="opt_price_checked" id="opt_price" value="<?php print $_REQUEST['opt_price_checked'];?>" size="6" class="center"></div>
		</div>

		<?php if($booking_cat > 0) { ?>
			<div class="underlinelabel">Additional Time In Minutes</div>
			<div class="underline">
				<?php if(empty($_REQUEST['opt_addition_time'])) { $_REQUEST['opt_addition_time'] = "0"; } ?>
				<div class="label">If this option requires additional time to be added, enter in minutes.</div>
				<div><input type="text" name="opt_addition_time" id="opt_addition_time" value="<?php print $_REQUEST['opt_addition_time'];?>" size="6" class="center"></div>
			</div>
		


		<?php } ?>

	</div>



	<div id="downloadoption" class="opttype" <?php if($_REQUEST['opt_type'] !== "download") { ?>style="display: none;"<?php } ?>>
		<div class="underlinelabel">Download Photo Options</div>
		<div class="underline">
			<div class="p50 left">
				<?php if(empty($_REQUEST['opt_price_download'])) { $_REQUEST['opt_price_download'] = "0.00"; } ?>
				<div class="label">If the checkbox is checked, charge this amount:</div>
				<div><input type="text" name="opt_price_download" id="opt_price_download" value="<?php print $_REQUEST['opt_price_download'];?>" size="6" class="center"></div>
			</div>

			<div class="p50 left">
				<?php if(empty($_REQUEST['opt_download_size'])) { $_REQUEST['opt_download_size'] = "0"; } ?>
				<div class="label">Max. Pixels</div>
				<div><input type="text" name="opt_download_size" id="opt_download_size" value="<?php print $_REQUEST['opt_download_size'];?>" size="6" class="center"></div>
				<div>This is the size of the longest side of the photo. For the largest available size for download, enter in 0.</div>
		
				<div class="label">
				<input type="checkbox" name="opt_disable_download" id="opt_disable_download" value="1" <?php if($_REQUEST['opt_disable_download'] == "1") { print "checked"; } ?>> <label for="opt_disable_download">Do not allow download until I upload a replacement file or manually approve it.</label> <div class="moreinfo" info-data="nodownloadphoto"><div class="info"></div></div>
				</div>

			</div>
			<div class="clear"></div>

		</div>





		<div class="underline download" >
			<input type="checkbox" name="pp_watermark" id="pp_watermark" value="1" class="" <?php if($_REQUEST['pp_watermark'] == "1") { print "checked"; } ?> onchange="watermarksettings();"> <label for="pp_watermark">Add Watermark</label>
		</div>
	
		<div class="download">
			<div class="watermarksettings <?php if($_REQUEST['pp_watermark'] <= 0 ) { print "hide"; } ?>">
			<?php 
				$wm_folder =$setup['photos_upload_folder']."/watermarks";
				$wm_folder_path = $setup['path']."/".$wm_folder."";
				if(!is_dir($wm_folder_path)) {
					print "<div class=\"underline\">You have not uploaded any watermark files</div>";
				} else {
					?>
					<div class="pc">
					<select name="pp_watermark_file" id="pp_watermark_file">
					<option value="">Default Watermark FIle</option>
					<?php 
					$dir = opendir($wm_folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) { 
							?>
							<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['pp_watermark_file']) { print "selected"; } ?>><?php print $file;?></option>
						<?php 
						}
					}
					print "</select></div>";

				@closedir($dir); 
				}
			?>
				<div class="pc">
					<select name="pp_watermark_location" id="pp_watermark_location">
					<option value="">Default Watermark Location</option>

					<option value="tile" <?php  if($_REQUEST['pp_watermark_location'] == "tile") { print "selected"; } ?>>Tile</option>
					<option value="center" <?php  if($_REQUEST['pp_watermark_location'] == "center") { print "selected"; } ?>>Center</option>
					<option value="bright" <?php  if($_REQUEST['pp_watermark_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
					<option value="bottom" <?php  if($_REQUEST['pp_watermark_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
					<option value="bleft" <?php  if($_REQUEST['pp_watermark_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
					<option value="uright" <?php  if($_REQUEST['pp_watermark_location'] == "uright") { print "selected"; } ?>>Top Right</option>
					<option value="top" <?php  if($_REQUEST['pp_watermark_location'] == "top") { print "selected"; } ?>>Top Middle</option>
					<option value="uleft" <?php  if($_REQUEST['pp_watermark_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
					</select>
				</div>

			</div>
		</div>

		<div class="underline download">
			<input type="checkbox" name="pp_logo" id="pp_logo" value="1"  class="" <?php if($_REQUEST['pp_logo'] == "1") { print "checked"; } ?>  onchange="logosettings();"> <label for="pp_logo">Add Logo</label>
		</div>

		<div class="download">
			<div class="logosettings  <?php if($_REQUEST['pp_logo'] <= 0 ) { print "hide"; } ?>">
			<?php 

				$wm_folder =$setup['photos_upload_folder']."/watermarks";
				$wm_folder_path = $setup['path']."/".$wm_folder."";
				if(!is_dir($wm_folder_path)) {
					print "<div class=\"underline\">You have not uploaded any watermark files</div>";
				} else {
					?>
					<div class="pc">
					<select name="pp_logo_file" id="pp_logo_file" >
					<option value="">Default Logo File</option>

					<?php 
					$dir = opendir($wm_folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) { 
							?>
							<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['pp_logo_file']) { print "selected"; } ?>><?php print $file;?></option>
						<?php 
						}
					}
					print "</select></div>";
				@closedir($dir); 
				}
			?>

		<div class="pc">
			<select name="pp_logo_location" id="pp_logo_location">
			<option value="">Default Logo Location</option>
			<option value="tile" <?php  if($_REQUEST['pp_logo_location'] == "tile") { print "selected"; } ?>>Tile</option>
			<option value="center" <?php  if($_REQUEST['pp_logo_location'] == "center") { print "selected"; } ?>>Center</option>
			<option value="bright" <?php  if($_REQUEST['pp_logo_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
			<option value="bottom" <?php  if($_REQUEST['pp_logo_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
			<option value="bleft" <?php  if($_REQUEST['pp_logo_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
			<option value="uright" <?php  if($_REQUEST['pp_logo_location'] == "uright") { print "selected"; } ?>>Top Right</option>
			<option value="top" <?php  if($_REQUEST['pp_logo_location'] == "top") { print "selected"; } ?>>Top Middle</option>
			<option value="uleft" <?php  if($_REQUEST['pp_logo_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
			</select>
		</div>
	</div>
</div>


	</div>


	<div id="selectoptions" class="opttype" <?php if(($_REQUEST['opt_type'] !== "dropdown")&&($_REQUEST['opt_type'] !== "tabs")&&($_REQUEST['opt_type'] !== "radio")==true) { ?>style="display: none;"<?php } ?>>
	<?php if($booking_cat <= 0) { ?>
	<?php if(($date['date_id'] > 0)||($package['package_select_only'] > 0) == true) { ?>
		<div class="underline"><input type="checkbox" onchange="selectoptphotos();" name="opt_photos" id="opt_photos" value="1" <?php if($opt['opt_photos'] == "1") { print "checked"; } ?>> <label for="opt_photos">This option will determain how many photos are to be selected for this item.</label></div>
	<?php } ?>
	<?php } ?>

	<!--
		<div class="underline">
		<div class="label">Select Label</div>
		<div><input type="text" name="opt_label" id="opt_label" size="20" value="<?php print htmlspecialchars(stripslashes($_REQUEST['opt_label']));?>"></div>
		<div>This is the text of the option when no options are selected. Leave blank to have the default option automatically selected.</div>
		</div>
		-->
		<div class="underlinelabel">Enter the selectable options for this option below</div>
		<div class="underlinecolumn">
			<div style="width: 40%; float: left;">Name</div>
			<div style="width: 20%; float: left;" class="<?php if($opt['opt_photos'] !== "1") { ?>hidden<?php } ?> opt_photos"># photos</div>
			<div style="width: 20%; float: left;" class="textright">Additional Price</div>
			<?php if($booking_cat > 0) { ?>
			<div style="width: 20%; float: left;" class="textright">Additional Minutes</div>
			<?php } ?>
			<div style="width: 20%; float: left;" class="textright">Default</div>
			<div class="clear"></div>
		</div>



	<?php 
		if((empty($_REQUEST['submitit']))AND($_REQUEST['opt_id']>0)==true)  {
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$_REQUEST['opt_id']."' ORDER BY sel_order, sel_id ASC");		
		$pcount = 1;
		while($sel = mysqli_fetch_array($sels)) {
			$_REQUEST['sel_name'][$pcount] = $sel['sel_name'];
			$_REQUEST['sel_price'][$pcount] = $sel['sel_price'];
			$_REQUEST['sel_order'][$pcount] = $sel['sel_order'];
			$_REQUEST['sel_descr'][$pcount] = $sel['sel_descr'];
			$_REQUEST['sel_photos'][$pcount] = $sel['sel_photos'];
			$_REQUEST['sel_add_time'][$pcount] = $sel['sel_add_time'];
			if($sel['sel_default'] == "1") {
				$def = $pcount;
			}
			$pcount++;
		}
		$_REQUEST['pcount'] = $pcount;
	}
	?>

	<?php 
	$lines = $_REQUEST['pcount'] + 8;
	$ct = 1;
	while($ct<=$lines) {
	?>
	<div class="underline">
		<div style="width: 40%; float: left;"><input type="text" name="sel_name[<?php print $ct;?>]" size="40"class="field100" value="<?php print htmlspecialchars(stripslashes($_REQUEST['sel_name'][$ct]));?>"></div>
		<div style="width: 20%; float: left;" class="opt_photos <?php if($opt['opt_photos'] !== "1") { ?>hidden<?php } ?>"><input type="text" name="sel_photos[<?php print $ct;?>]" size="8" value="<?php print htmlspecialchars(stripslashes($_REQUEST['sel_photos'][$ct]));?>"></div>
		<div style="width: 20%; float: left;" class="textright"><input type="text" name="sel_price[<?php print $ct;?>]" size="8" value="<?php print htmlspecialchars(stripslashes($_REQUEST['sel_price'][$ct]));?>"></div>
		<?php if($booking_cat > 0) { ?>
			<div style="width: 20%; float: left;" class="textright"><input type="text" name="sel_add_time[<?php print $ct;?>]" size="8" value="<?php print htmlspecialchars(stripslashes($_REQUEST['sel_add_time'][$ct]));?>"></div>
		<?php } ?>

		<div style="width: 20%; float: left;" class="textright"><?php print "<input type=\"radio\" name=\"sel_default\" value=\"$ct\" "; if($def == $ct) { print "checked"; } print ">"; ?></div>
		<div class="clear"></div>
	</div>
<?php $ct++;
	}
	?>
</div>

<?php if(($date['date_id'] > 0) || ($booking_cat > 0) == true){ ?>
<div class="underline"><input type="text" name="opt_order" size="3" class="center" value="<?php print $opt['opt_order'];?>"> Display order</div>
<?php } ?>
<div>&nbsp;</div>

<div class="pageContent center">

<input type="hidden" name="opt_id" value="<?php print $_REQUEST['opt_id'];?>">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="do" value="editOption">
<input type="hidden" name="opt_photo_prod" value="<?php print $_REQUEST['opt_photo_prod'];?>">
<input type="hidden" name="opt_package" value="<?php print $_REQUEST['opt_package'];?>">
<input type="hidden" name="opt_date" value="<?php print $_REQUEST['opt_date'];?>">
<input type="hidden" name="opt_service" value="<?php print $_REQUEST['opt_service'];?>">


<input type="submit" name="submit" value="<?php 	if($_REQUEST['opt_id'] > 0) { ?>Update Option<?php } else { ?>Create New Option<?php } ?>" class="submit" id="submitButton">
<br><a href="" onclick="closeoptionedit(); return false;">Cancel</a>
</div>

</form>
<?php } ?>

<?php if($_REQUEST['do'] == "copyOption") { 
	if(($_REQUEST['opt_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['opt_id']."' "); 
		if(empty($opt['opt_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
	}
	?>
	<form method="post" name="copyoptionform" action="w-product-options.php"   onSubmit="return checkForm('.copyrequired');">

	<div style="width: 30%; float: left;">
		
		<div class="pc"><h2>Copy option</h2></div>
		<div class="pc">To copy this option to other products in this section, check the checkbox next to the product then click the copy option button below. </div>
		<div class="pc"><?php 	showProductOption($opt,''); ?></div>
		<input type="hidden" name="action" value="savecopyoptions">
		<input type="hidden" name="opt_id" value="<?php print $opt['opt_id'];?>">
		<input type="hidden" name="opt_photo_prod" value="<?php print $_REQUEST['opt_photo_prod'];?>">
		<input type="hidden" name="opt_package" value="<?php print $_REQUEST['opt_package'];?>">
		<input type="submit" name="submit" value="Copy Option" id="submitButton" class="submit">
	</div>


	<div style="width: 70%; float: left;">
		<div class="underlinelabel"><?php if($_REQUEST['pp_type'] == "print") { ?>Prints<?php } ?><?php if($_REQUEST['pp_type'] == "download") { ?>Downloads<?php } ?><?php if($_REQUEST['pp_type'] == "other") { ?>Other<?php } ?></div>

		<?php $prods = whileSQL("ms_photo_products", "*","WHERE pp_type='".$_REQUEST['pp_type']."' AND pp_id!='".$_REQUEST['opt_photo_prod']."' ORDER BY pp_price ASC ");
		if(mysqli_num_rows($prods)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($prod = mysqli_fetch_array($prods)) { ?>
		<div class="underline" id="prod-<?php print $prod['pp_id'];?>" pp_id="<?php print $prod['pp_id'];?>" pp_name="<?php print htmlspecialchars($prod['pp_name']);?>"  pp_download_dem="<?php print htmlspecialchars($prod['pp_download_dem']);?>"  pp_price="<?php print htmlspecialchars($prod['pp_price']);?>"  pp_cost="<?php print htmlspecialchars($prod['pp_cost']);?>"  pp_descr="<?php print htmlspecialchars($prod['pp_descr']);?>" pp_taxable="<?php print htmlspecialchars($prod['pp_taxable']);?>"   pp_width="<?php print htmlspecialchars($prod['pp_width']);?>"  pp_height="<?php print htmlspecialchars($prod['pp_height']);?>" pp_type="<?php print htmlspecialchars($prod['pp_type']);?>" >
		<div style="width: 5%; float: left;">
		<input type="checkbox" name="pp_id[]" value="<?php print $prod['pp_id'];?>">
		</div>
		<div style="width: 25%; float: left;">
		<?php print $prod['pp_name'];?>
		</div>
		<div style="width: 50%; float: left;">

<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_name ASC ");
if(mysqli_num_rows($opts) <= 0 ) {?>
<div class="small muted">No options created</div> 
<?php } ?>

<?php while($opt = mysqli_fetch_array($opts))  { 
	showProductOption($opt,'');
	 } ?>
		</div>
		<div style="width: 20%; float: left; text-align:right;">
		<?php print showPrice($prod['pp_price']);?>
		</div>

			<div class="clear"></div>
		</div>
		<?php } ?>
		<div>&nbsp;</div>
	
	</div>

<div class="clear"></div>
</form>
<?php } ?>

<?php require "w-footer.php"; ?>
