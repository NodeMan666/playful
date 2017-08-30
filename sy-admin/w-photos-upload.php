<?php require "w-header.php"; ?>
<?php 
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;
$history = doSQL("ms_history", "*", "");

$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
$bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bill_id']."' ");
if(($_REQUEST['replace_photo'] > 0) && ($_REQUEST['order_id'] > 0) == true)  { 
	$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
	if($order['order_archive_table'] == "1") { 
		define(cart_table,"ms_cart_archive");
	} else { 
		define(cart_table,"ms_cart");
	}

	$cart = doSQL(cart_table, "*", "WHERE cart_pic_id='".$_REQUEST['replace_photo']."' AND cart_order='".$_REQUEST['order_id']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
	$photo_setup['discard_original'] = 0;
}



if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
?>
<script>
function updateuploadsettings() { 
	$("#updating").show();
	if($("#watermark_photos").attr("checked")) { 
		watermark = "1";
	} else { 
		watermark = "0";
	}
	if($("#logo_photos").attr("checked")) { 
		logo = "1";
	} else { 
		logo = "0";
	}
	if($("#discard_dups").attr("checked")) { 
		discard_dups = "1";
	} else { 
		discard_dups = "0";
	}
	if($("#check_rotate").attr("checked")) { 
		check_rotate = "1";
	} else { 
		check_rotate = "0";
	}
	if($("#replace_photos").attr("checked")) { 
		replace_photos = "1";
	} else { 
		replace_photos = "0";
	}
	if($("#upload_amazon").attr("checked")) { 
		upload_amazon = "1";
	} else { 
		upload_amazon = "0";
	}
	if($("#discard_original").attr("checked")) { 
		discard_original = "1";
	} else { 
		discard_original = "0";
	}

	if($("#no_meta").attr("checked")) { 
		no_meta = "1";
	} else { 
		no_meta= "0";
	}

	if($("#replace_order_photo").attr("checked")) { 
		replace_order_photo = "1";
	} else { 
		replace_order_photo = "0";
	}
	window.location.href="w-photos-upload.php?changed=1&pic_client="+$('input:radio[name=pic_client]:checked').val()+"&watermark_photos="+watermark+"&discard_dups="+discard_dups+"&check_rotate="+check_rotate+"&replace_photos="+replace_photos+"&discard_original="+discard_original+"&no_meta="+no_meta+"&replace_order_photo="+replace_order_photo+"&replace_photo="+$("#replace_photo").val()+"&upload_amazon="+upload_amazon+"&logo_photos="+logo+"&date_id="+$("#date_id").val()+"&sub_id="+$("#sub_id").val()+"&order_id="+$("#order_id").val()+"&cart_id="+$("#cart_id").val()+"&wm_images_file="+$("#wm_images_file").val()+"&wm_logo_file="+$("#wm_logo_file").val()+"&replace="+$("#replace").val()+"&wm_images_location="+$("#wm_images_location").val()+"&wm_add_logo_location="+$("#wm_add_logo_location").val()+"";
}
</script>
<div id="updating" style="width:300px; position: fixed; left: 50%; margin-left: -150px; padding: 24px; text-align:center; font-size: 21px;  top: 40%; display: none; z-index: 50; background: #545454; color: #ffffff;">Updating</div>

<?php if($_REQUEST['replace'] > 0) { 
	$pic = doSQL("ms_photos", "*","WHERE pic_id='".$_REQUEST['replace']."' "); ?>
<div class="pc"><h1>Replace photo <?php print $pic['pic_org'];?> in <?php print $date['date_title'];?></h1></div>
<input type="hidden" name="replace" id="replace" value="<?php print $_REQUEST['replace'];?>">
<?php } else if($_REQUEST['replace_photo'] > 0)  { 
	$pic = doSQL("ms_photos", "*","WHERE pic_id='".$_REQUEST['replace_photo']."' "); ?>
	<div class="pc"><h1>Replace photo <?php print $pic['pic_org'];?></h1></div>
<input type="hidden" name="replace_photo" id="replace_photo" value="<?php print $_REQUEST['replace_photo'];?>">
<?php } else { ?>
<input type="hidden" name="replace_photo" id="replace_photo" value="0">

<div class="pc"><h1>Upload Photos
<?php if($date['date_id'] > 0) { ?> to <?php print $date['date_title'];?>

			<?php 
			if($sub['sub_id'] > 0) { 	
				$ids = explode(",",$sub['sub_under_ids']);
				foreach($ids AS $val) { 
					if($val > 0) { 
						$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
						print " > ".$upsub['sub_name']." ";
					}
				}
			print " > ".$sub['sub_name'];
			}
			?>

<?php } ?></h1></div>
<?php } ?>


<div style="width: 49%; float: left;">
<?php  if($bill['bill_id'] <= 0) { ?>
<?php 
if($_REQUEST['changed'] > 0) { 
	updateSQL("ms_history", "check_rotate='".$_REQUEST['check_rotate']."' ");
	updateSQL("ms_photo_setup", "upload_amazon='".$_REQUEST['upload_amazon']."', discard_original='".$_REQUEST['discard_original']."',no_meta='".$_REQUEST['no_meta']."' ");
}
if($_REQUEST['changed'] <=0) { 
	if($_REQUEST['order_id'] > 0) {
		$_REQUEST['replace_order_photo'] = 1;
	}
	$wm = doSQL("ms_watermarking", "*", "");
	if($cat['cat_id'] > 0) { 
		$_REQUEST['watermark_photos'] = $cat['cat_watermark'];
		$_REQUEST['logo_photos'] = $cat['cat_logo'];
	} else { 
		$_REQUEST['watermark_photos'] = $wm['wm_def_wm'];
		$_REQUEST['logo_photos'] = $wm['wm_def_logo'];
	}
	if($_REQUEST['date_id'] > 0) { 
		$_REQUEST['pic_client'] = "1";
	}
	$_REQUEST['check_rotate'] = $history['check_rotate'];
	$_REQUEST['upload_amazon'] = $photo_setup['upload_amazon'];
	$_REQUEST['discard_original'] = $photo_setup['discard_original'];
	$_REQUEST['no_meta'] = $photo_setup['no_meta'];

	$_REQUEST['wm_logo_file'] = $wm['wm_logo_file'];
	$_REQUEST['wm_images_file'] = $wm['wm_images_file'];
	$_REQUEST['wm_images_location'] = $wm['wm_images_location'];
	$_REQUEST['wm_add_logo_location'] = $wm['wm_add_logo_location'];
}
?>
<div class="pc">Change any settings below before selecting photos to upload.</div>
<input type="hidden" name="date_id" id="date_id" value="<?php print $_REQUEST['date_id'];?>">
<input type="hidden" name="sub_id" id="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
<input type="hidden" name="order_id" id="order_id" value="<?php print $_REQUEST['order_id'];?>">
<input type="hidden" name="cart_id" id="cart_id" value="<?php print $_REQUEST['cart_id'];?>">
<!-- <div class="pc"><input type="radio" name="pic_client" value="0" <?php if($_REQUEST['pic_client'] <= 0) { print "checked"; } ?> onclick="updateuploadsettings();"> Public Photos</div>
<div class="pc"><input type="radio" name="pic_client" value="1" <?php if($_REQUEST['pic_client'] == 1) { print "checked"; } ?> onclick="updateuploadsettings();"> Client Photos</div>
-->

<?php if($_REQUEST['order_id'] > 0) { ?><div class="pc"><input type="checkbox" name="replace_order_photo" id="replace_order_photo" value="1" <?php if($_REQUEST['replace_order_photo'] == "1") { print "checked"; } ?>  onclick="updateuploadsettings();"> <label for="replace_order_photo"><b>Replace photo on order only</b></label></div>
<div>Selecting this option will only replace the photo on the order and not the original photo in the gallery. Otherwise the photo in the gallery will be replaced also. </div>
<div>&nbsp;</div>
<?php } ?>

<div class="pc"><input type="checkbox" name="watermark_photos" id="watermark_photos" value="1" <?php if($_REQUEST['watermark_photos'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="watermark_photos" <?php if($_REQUEST['watermark_photos'] == "1") { ?>class="bold"<?php } ?>>Watermark Photos</label></div>
<div class="<?php if($_REQUEST['watermark_photos'] !== "1") { print "hide";  }?>">
<?php 
	$wm_folder =$setup['photos_upload_folder']."/watermarks";
	$wm_folder_path = $setup['path']."/".$wm_folder."";
	if(!is_dir($wm_folder_path)) {
		print "<div class=\"underline\">You have not uploaded any watermark files</div>";
	} else {
		?>
		<div class="pc">
		<select name="wm_images_file" id="wm_images_file"  onchange="updateuploadsettings();">
		<?php 
		$dir = opendir($wm_folder_path); 
		while ($file = readdir($dir)) { 
			if (($file != ".") && ($file != "..")) { 
				?>
				<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['wm_images_file']) { print "selected"; } ?>><?php print $file;?></option>
			<?php 
			}
		}
		print "</select></div>";
	@closedir($dir); 
	}
?>
<div class="pc">
		<select name="wm_images_location" id="wm_images_location"  onchange="updateuploadsettings();">
		<option value="tile" <?php  if($_REQUEST['wm_images_location'] == "tile") { print "selected"; } ?>>Tile</option>
		<option value="center" <?php  if($_REQUEST['wm_images_location'] == "center") { print "selected"; } ?>>Center</option>
		<option value="bright" <?php  if($_REQUEST['wm_images_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
		<option value="bottom" <?php  if($_REQUEST['wm_images_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
		<option value="bleft" <?php  if($_REQUEST['wm_images_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
		<option value="uright" <?php  if($_REQUEST['wm_images_location'] == "uright") { print "selected"; } ?>>Top Right</option>
		<option value="top" <?php  if($_REQUEST['wm_images_location'] == "top") { print "selected"; } ?>>Top Middle</option>
		<option value="uleft" <?php  if($_REQUEST['wm_images_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
		</select>
</div>

</div>
		
<div class="pc"><input type="checkbox" name="logo_photos" id="logo_photos" value="1" <?php if($_REQUEST['logo_photos'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="logo_photos" <?php if($_REQUEST['logo_photos'] == "1") { ?>class="bold"<?php } ?>>Apply Logo</label></div>
<div class="<?php if($_REQUEST['logo_photos'] !== "1") { print "hide"; } ?>">
<?php 
	$wm = doSQL("ms_watermarking", "*", " ");

	$wm_folder =$setup['photos_upload_folder']."/watermarks";
	$wm_folder_path = $setup['path']."/".$wm_folder."";
	if(!is_dir($wm_folder_path)) {
		print "<div class=\"underline\">You have not uploaded any watermark files</div>";
	} else {
		?>
		<div class="pc"><select name="wm_logo_file" id="wm_logo_file"   onchange="updateuploadsettings();">
		<?php 
		$dir = opendir($wm_folder_path); 
		while ($file = readdir($dir)) { 
			if (($file != ".") && ($file != "..")) { 
				?>
				<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['wm_logo_file']) { print "selected"; } ?>><?php print $file;?></option>
			<?php 
			}
		}
		print "</select></div>";
	@closedir($dir); 
	}
?>

<div class="pc">
		<select name="wm_add_logo_location" id="wm_add_logo_location"  onchange="updateuploadsettings();">
		<option value="tile" <?php  if($_REQUEST['wm_add_logo_location'] == "tile") { print "selected"; } ?>>Tile</option>
		<option value="center" <?php  if($_REQUEST['wm_add_logo_location'] == "center") { print "selected"; } ?>>Center</option>
		<option value="bright" <?php  if($_REQUEST['wm_add_logo_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
		<option value="bottom" <?php  if($_REQUEST['wm_add_logo_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
		<option value="bleft" <?php  if($_REQUEST['wm_add_logo_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
		<option value="uright" <?php  if($_REQUEST['wm_add_logo_location'] == "uright") { print "selected"; } ?>>Top Right</option>
		<option value="top" <?php  if($_REQUEST['wm_add_logo_location'] == "top") { print "selected"; } ?>>Top Middle</option>
		<option value="uleft" <?php  if($_REQUEST['wm_add_logo_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
		</select>
</div>

</div>

<?php if(($_REQUEST['replace'] <= 0) && ($_REQUEST['replace_photo'] <= 0) && ($_REQUEST['replace_photos'] <= 0) == true) {  ?>
<div class="pc"><input type="checkbox" name="discard_dups" id="discard_dups" value="1" <?php if($_REQUEST['discard_dups'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="discard_dups" <?php if($_REQUEST['discard_dups'] == "1") { ?>class="bold"<?php } ?>>Discard Duplicate File Names</label></div>
<?php } ?>
<div class="pc"><input type="checkbox" name="replace_photos" id="replace_photos" value="1" <?php if($_REQUEST['replace_photos'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="replace_photos" <?php if($_REQUEST['replace_photos'] == "1") { ?>class="bold"<?php } ?>>Replace photos with same file name</label></div>
<div class="pc"><input type="checkbox" name="check_rotate" id="check_rotate" value="1" <?php if($_REQUEST['check_rotate'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="check_rotate" <?php if($_REQUEST['check_rotate'] == "1") { ?>class="bold"<?php } ?>>Rotate Based on EXIF data</label></div>
<div class="pc"><input type="checkbox" name="discard_original" id="discard_original" value="1" <?php if($_REQUEST['discard_original'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="discard_original" <?php if($_REQUEST['discard_original'] == "1") { ?>class="bold"<?php } ?>>Discard original photo files</label><?php if($_REQUEST['discard_original'] == "1") { ?><br><span style="color: #890000; font-weight: bold; font-size: 13px;">With this selected you will not be able to offer free downloads or instant paid downloads.<?php } ?></div>

<div class="pc"><input type="checkbox" name="no_meta" id="no_meta" value="1" <?php if($_REQUEST['no_meta'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="no_meta" <?php if($_REQUEST['no_meta'] == "1") { ?>class="bold"<?php } ?>>Do not capture Title, Caption & Keywords from IPTC data</label></div>



<?php if($photo_setup['enable_amazon'] == "1") { ?>
<div class="pc"><input type="checkbox" name="upload_amazon" id="upload_amazon" value="1" <?php if($_REQUEST['upload_amazon'] == "1") { print "checked"; } ?> onclick="updateuploadsettings();"> <label for="upload_amazon" <?php if($_REQUEST['upload_amazon'] == "1") { ?>class="bold"<?php } ?>>Move to Amazon S3 now</label> (See below for information)</div>
<?php } ?>

	<div>&nbsp;</div>	
	<div class="pc">
	Your watermark & logo settings are set in <a href="index.php?do=settings&action=watermarking" target="_blank">Settings > Watermarking</a>.
	</div>
	<div class="pc">
	Your resize quality is set to <?php print $photo_setup['resize_quality'];?>. This settings is found in <a href="index.php?do=settings&action=photos" target="_blank">Settings -> Photos Sizes & Defaults</a>.
	</div>

	<?php if($date['date_id'] > 0) { ?>
	<div class="pc">You can set your default watermark settings on a per section basis by <a href="index.php?do=news&action=editCategory&cat_id=<?php print $date['date_cat'];?>" target="_parent">editing the section</a> and clicking Default Watermarking.</div>
	<?php } ?>

<?php } ?>
<div>&nbsp;</div>
<?php if($photo_setup['enable_amazon'] == "1") { ?><div class="underlinelabel">Amazon S3</div>
<div class="underlinespacer">Since you have Amazon S3 enabled, you can select to move the photos to the Amazon S3 server now. This will slow down the upload process slightly. Alternatively you can move the photos to Amazon S3 after the upload process has finished.
</div>
<div>&nbsp;</div><?php } ?>
<?php if($setup['sytist_hosted'] !== true) { ?> 
	<div class="underlinelabel">Server Configurations</div>
	<div class="underlinespacer">Below are settings from the PHP (php.ini) on the server. If you have problems uploading large photos, it could be a limit in one of the settings below. These settings can be changed in the php.ini file. If you don't have access to your php.ini file, contact your hosting company.</div>
	<div class="underline">
		<div class="left">Upload Max File Size</div>
		<div class="right"><?php print ini_get('upload_max_filesize'); ?></div>
		<div class="clear"></div>
	</div>




	<div class="underline">
		<div class="left">Memory Limit</div>
		<div class="right"><?php print ini_get('memory_limit'); ?></div>
		<div class="clear"></div>
	</div>



	<div class="underline">
		<div class="left">POST Max Size</div>
		<div class="right"><?php print ini_get('post_max_size'); ?></div>
		<div class="clear"></div>
	</div>
<?php if($photo_setup['enable_amazon'] == "1") { ?>
	<div class="underline">
		<div class="left">allow_url_fopen</div>
		<div class="right"><?php print ini_get('allow_url_fopen'); ?></div>
		<div class="clear"></div>
		<?php if(ini_get('allow_url_fopen') <= 0) { ?>
		<div>If this value is 0 and you are hosting the photos on Amazon S3, the paid & free downloads will not work. You will need to have your host set this value to 1.</div>
		<?php } ?>
	</div>
	<?php } ?>
<?php } ?>
</div>

<div style="width: 49%; float: right;">

<?php 
if($setup['sytist_hosted'] == true) { 
	if((checkstoragelimit() !== true)&&($_REQUEST['upload_amazon'] !== "1")==true) { 
		showSytistSpace();
		?>
		<div class="pc">You have reached your storage limit. To upload more photos, you will need to delete some older photos to use the <a href="https://www.picturespro.com/sytist-manual/articles/hosting-photos-on-amazon-s3/" target="_blank">Amazon S3 feature</a>.</div>
		<?php if(($_REQUEST['replace'] <= 0) && ($_REQUEST['replace_photo'] <= 0) && ($_REQUEST['replace_photos'] <=0) == true) {  ?>

		<?php if($date['date_id'] > 0) { ?><div class="pc"><a href="index.php?do=allPhotos&did=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" target="_parent">Or select from your All Photos section</a> </div>
		<?php } 
		}
		?>
		<?php 
		exit();
	}
}
?>




<div id="uploadingmessage" style="display: none;">
<div class="pc"><h2>Uploading - Do not close this window</h2></div>
<div class="pc">Leave this window open while the files are uploading. If you wish to do something else in the admin while the photos are uploading, <a href="index.php" target="_blank">click here</a> to open the admin in a new tab.
</div>
</div>
<div class="pc" id="uploadmessage">Click the button below to select the photos from your computer to upload. Once you select the photos, the upload process will automatically start.</div>
<?php $upload_session = date('Y-m-d_H-i-s'); ?>


<?php if($setup['demo_mode'] == true) { ?>
<input type="submit" name="demoupload" value="SELECT & UPLOAD PHOTOS" class="submit" onClick="return confirm('Uploading files has been disabled for security reasons. But this is where you will select photos to upload from your computer. ');">
<?php } else { ?>

<div class="pc"><input type="file" name="file_upload" id="file_upload" /></div>
<?php 
$hash = $site_setup['salt']; 
$timestamp = date('Ymdhis');
?>

<script>
$(function() {
	var numFiles = 0;
	var failedFiles = "";
    $('#file_upload').uploadify({
	<?php if((!empty($_REQUEST['slide_id']))||($_REQUEST['replace'] > 0)||(!empty($_REQUEST['replace_photo'])) == true) { ?>
         'multi'    : false,
	<?php } else { ?>
         'multi'    : true,
	<?php } ?>
		'debug'    : false,	
		'method'   : 'post',
		'fileTypeExts' : '*.jpg;*.jpeg;*.gif;*.png',
		'fileTypeDesc' : 'jpg;gif;png',
		'buttonText' : 'Select Photos',
		 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
			 'token' : '<?php echo md5($hash.$timestamp); ?>', 
			 'date_id':'<?php print $date['date_id'];?>', 
			 'replace':'<?php print $_REQUEST['replace'];?>', 
			 'replace_photo':'<?php print $_REQUEST['replace_photo'];?>', 
			 'cart_id':'<?php print $_REQUEST['cart_id'];?>', 
			 'replace_photos':'<?php print $_REQUEST['replace_photos'];?>', 
			 'replace_order_photo':'<?php print $_REQUEST['replace_order_photo'];?>', 
			 'discard_original':'<?php print $_REQUEST['discard_original'];?>', 
			 'no_meta':'<?php print $_REQUEST['no_meta'];?>', 
			 'sub_id':'<?php print $_REQUEST['sub_id'];?>', 
			 'bill_id':'<?php print $bill['bill_id'];?>',
			 'slide_id':'<?php print $_REQUEST['slide_id'];?>', 
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
			 'discard_dups':'<?php print $_REQUEST['discard_dups'];?>',
			 'check_rotate':'<?php print $_REQUEST['check_rotate'];?>',
			 'upload_amazon':'<?php print $_REQUEST['upload_amazon'];?>',

			'wm_images_file':'<?php print urlencode($_REQUEST['wm_images_file']);?>',
			 'wm_images_location':'<?php print $_REQUEST['wm_images_location'];?>',
			 'wm_logo_file':'<?php print $_REQUEST['wm_logo_file'];?>',
			 'wm_add_logo_location':'<?php print $_REQUEST['wm_add_logo_location'];?>',

	
			'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
		      'onUploadSuccess' : function(queueData) {
	            numFiles = numFiles + 1;
			},
			'onUploadError' : function(file, errorCode, errorMsg, errorString) {
				failedFiles = failedFiles+file.name+"||";
				// alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
			}, 

			'onQueueComplete' : function(queueData) {

			<?php if($_REQUEST['order_id'] > 0) { ?>
			window.parent.location.href='index.php?do=orders&action=managephotos&order_id=<?php print $_REQUEST['order_id'];?>&failedFiles='+failedFiles+'&sm='+queueData.uploadsSuccessful+' Photos Uploaded';

			<?php } else if($date['date_id'] > 0) { ?>
			window.parent.location.href='index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&failedFiles='+failedFiles+'&sm='+queueData.uploadsSuccessful+' Photos Uploaded';
			<?php } else if($bill['bill_id'] > 0) { ?>
			window.parent.location.href='index.php?do=look&action=billboardSlideshow&bill_id=<?php print $bill['bill_id'];?>&failedFiles='+failedFiles+'&sm='+queueData.uploadsSuccessful+' Photos Uploaded';
			<?php } else { ?>
			window.parent.location.href='index.php?do=allPhotos&pic_upload_session=<?php print $upload_session;?>&pic_client=0&acdc=ASC&failedFiles='+failedFiles+'&sm='+queueData.uploadsSuccessful+' Photos Uploaded';
			<?php } ?>
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
<div class="pc" style="font-size: 12px; color: #A4A4A4;">If you don't see a Select Photos button above, then you may have Adobe Flash disabled in your browser or not installed which is needed for the uploader.</div>

<?php if(($_REQUEST['replace'] <= 0) && ($_REQUEST['replace_photo'] <= 0) && ($_REQUEST['replace_photos'] <=0) == true) {  ?>

		<?php if($date['date_id'] > 0) { ?><div class="pc"><a href="index.php?do=allPhotos&did=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" target="_parent">Or select from your All Photos section</a> </div>
		<div class="pc">To create sub galleries, close this window and click Add Sub Galleries. You can also batch create sub galleries when uploading via FTP. Refer to the manual about uploading via FTP.</div>
		<?php } ?>

		<?php checkuploadfolder(); ?>

		<?php if($bill['bill_id'] > 0) { ?><div class="pc center"><a href="index.php?do=allPhotos&bid=<?php print $bill['bill_id'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>" target="_parent">Or click here to select from your All Photos section</a></div><?php } ?>

	<?php } ?>
<?php } ?>

<div>&nbsp;</div>	

<!-- 
<div class="pageContent">Your current photo size settings are (max width/height) <br>
<span class="bold">Large: <?php print "".$photo_setup['blog_large_width']."/".$photo_setup['blog_large_height'];?> </span><br>
<span class="bold">Medium: <?php print "".$photo_setup['blog_med_width']."/".$photo_setup['blog_med_height'];?> </span><br>
<span class="bold">Small: <?php print "".$photo_setup['blog_width']."/".$photo_setup['blog_height'];?> </span><br>
<span class="bold">Thumbnail:  <?php print "".$photo_setup['blog_th_width']."/".$photo_setup['blog_th_height'];?> <?php if($photo_setup['blog_th_crop']== "1") { print " (cropping)"; } ?></span><br>
This can be changed in the <a href="index.php?do=settings&action=photos" target="_parent">these settings</a>.
</div>
-->
</div>
<div class="clear"></div>
<div>&nbsp;</div>	
<div style="display: none;"><img src="graphics/loading1.gif"></div>
<script>
function thisProcess(id) { 
	$(".hidelink").hide();
	$("#"+id).html('<img src="graphics/loading1.gif">');
}
</script>
<?php


function checkuploadfolder() { 
	global $setup,$date;
	$dir_name = $setup['path']."/sy-upload";

	$x = 1;

	$dir = opendir($dir_name); 
	$dirList = array();
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..")) { 
			if(is_dir($dir_name."/$file")) {
				$dir_list .= "<li>$file ";
				array_push($dirList, $file);
			}
		} 
	} 

	@closedir($dir); 

	if(count($dirList) > 0) { ?>
	<div class="pc"><h3>Select from FTP Upload</h3></div>
	<?php foreach($dirList AS $dir) { 
		$ct++; ?>
	<div class="underline">
		<div class="left"><h4><?php print $dir;?></h4></div>
		<div class="right" id="folder-<?php print $ct;?>">
		<?php print "<a href=\"process-ftp-upload.php?date_id=".$date['date_id']."&sub_id=".$_REQUEST['sub_id']."&watermark_photos=".$_REQUEST['watermark_photos']."&logo_photos=".$_REQUEST['logo_photos']."&discard_dups=".$_REQUEST['discard_dups']."&discard_original=".$_REQUEST['discard_original']."&no_meta=".$_REQUEST['no_meta']."&check_rotate=".$_REQUEST['check_rotate']."&folder=".rawurlencode($dir)."&wm_images_file=".rawurlencode($_REQUEST['wm_images_file'])."&wm_logo_file=".rawurlencode($_REQUEST['wm_logo_file'])."&wm_images_location=".$_REQUEST['wm_images_location']."&wm_add_logo_location=".$_REQUEST['wm_add_logo_location']."\" target=\"_parent\" onclick=\"thisProcess('folder-".$ct."');\" class=\"hidelink\">Process</a>"; ?>
		</div>
		<div class="clear"></div>
		<?php 
			$thisdir = opendir($dir_name."/".$dir); 

			while ($file = readdir($thisdir)) { 
				$ext = strtolower(substr($file, -4));
				if($ext == ".jpg") {
					$file_count++;
				} 
				if (($file !== ".") && ($file !== "..")) { 
					if(is_dir($dir_name."/".$dir."/$file")) {
						$dir_count++;
					}
				}

			} 
		@closedir($thisdir); 
		?>

		<?php if($file_count > 0) { ?>
		<div><?php print $file_count;?> photos</div>
		<?php } ?>
		<?php $file_count = 0; ?>
		
		</div>
	<?php if(($dir_count > 0)&&(empty($date['date_id']))==true) { ?>
		<div class="error">You have sub folders in the folder above and not uploading to a page. <u>Your sub folder structure WILL BE LOST</u> when not uploading to a page. If you want to keep the folder structure to create sub galleries, go to  the page you want to display them on and upload there.</div>
	<?php 
		unset($dir_count);
		} 
		
		?>

	<?php } ?>
	<?php } ?>
	<?php 
}
?>
<?php require "w-footer.php"; ?>