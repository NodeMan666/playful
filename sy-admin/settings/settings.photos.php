<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}



	updateSQL("ms_photo_setup", "blog_width='".$_REQUEST['blog_width']."',  blog_height='".$_REQUEST['blog_height']."' ,  blog_th_width='".$_REQUEST['blog_th_width']."' ,  blog_th_height='".$_REQUEST['blog_th_height']."',  blog_th_crop='".$_REQUEST['blog_th_crop']."' ,  blog_slide_seconds='".$_REQUEST['blog_slide_seconds']."',  blog_link_enlarge='".$_REQUEST['blog_link_enlarge']."',  blog_placement='".$_REQUEST['blog_placement']."',  photo_blog_type='".$_REQUEST['photo_blog_type']."' , blog_large_width='".$_REQUEST['blog_large_width']."' , blog_large_height='".$_REQUEST['blog_large_height']."', def_all_orderby='".$_REQUEST['def_all_orderby']."', def_all_acdc='".$_REQUEST['def_all_acdc']."' , blog_med_height='".$_REQUEST['blog_med_height']."', blog_med_width='".$_REQUEST['blog_med_width']."', preview_width='".$_REQUEST['preview_width']."', preview_height='".$_REQUEST['preview_height']."', mini_size='".$_REQUEST['mini_size']."', preview_crop='".$_REQUEST['preview_crop']."' , cat_width='".$_REQUEST['cat_width']."', cat_height='".$_REQUEST['cat_height']."', ftp_process='".$_REQUEST['ftp_process']."', ftp_rest='".$_REQUEST['ftp_rest']."', zip_limit='".$_REQUEST['zip_limit']."', thumb_limit='".$_REQUEST['thumb_limit']."', resize_quality='".$_REQUEST['resize_quality']."', enable_amazon='".$_REQUEST['enable_amazon']."', awsAccessKey='".$_REQUEST['awsAccessKey']."', awsSecretKey='".$_REQUEST['awsSecretKey']."', awsBucketName='".$_REQUEST['awsBucketName']."', passcode_photos_find='".$_REQUEST['passcode_photos_find']."', no_search_filename='".$_REQUEST['no_search_filename']."', gallery_favicon='".$_REQUEST['gallery_favicon']."'  ");   		

	updateSQL("ms_settings", "amazon_endpoint='".$_REQUEST['amazon_endpoint']."' ");

		$_SESSION['sm'] = "Settings saved";
		session_write_close();
		header("location: index.php?do=settings&action=photos");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	$cset = doSQL("ms_calendar_settings", "*", "  ");
	$lang = doSQL("ms_language", "*", "  WHERE lang_default='1' ");
	?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Photos</div>

<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">


<div style="width: 49%; float: left;">
	<div class="pageContent"><h2>Photo Sizes</h2></div>
	<div class="pageContent">When photos are uploaded several different sizes are created. Below you can adjust the sizes they are resized to.</div>

	<div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Large max image width</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_large_width" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_large_width']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Large max image height</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_large_height" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_large_height']));?>"></div>
			<div class="cssClear"></div>
		</div>
		<!-- 
		<div class="underline">
			<div style="width:50%;" class="cssCell">Medium max image width</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_med_width" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_med_width']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Medium max image height</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_med_height" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_med_height']));?>"></div>
			<div class="cssClear"></div>
		</div>
	-->

		<div class="underline">
			<div style="width:50%;" class="cssCell">Small max image width</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_width" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_width']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Small max image height</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_height" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_height']));?>"></div>
			<div class="cssClear"></div>
		</div>
			<div class="underline">
			<div style="width:50%;" class="cssCell">Thumbnail max width</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_th_width" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_th_width']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Thumbnail max height</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="blog_th_height" value="<?php  print htmlspecialchars(stripslashes($photo_setup['blog_th_height']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<!-- 
		<div class="underline">
			<div style="width:50%;" class="cssCell">Square crop thumbnails to above width</div>
			<div style="width:50%;" class="cssCell">
			<input type="radio"  class="checkbox" name="blog_th_crop" value="1" <?php if($photo_setup['blog_th_crop'] == "1") { print "checked"; } ?>> Yes<br>
			<input type="radio"  class="checkbox" name="blog_th_crop" value="0" <?php if($photo_setup['blog_th_crop'] == "0") { print "checked"; } ?>> No<br>
			</div>
			<div class="cssClear"></div>
		</div>
		-->
		<div class="underline">
			<div style="width:50%;" class="cssCell">Mini previews</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size=4 name="mini_size" value="<?php  print htmlspecialchars(stripslashes($photo_setup['mini_size']));?>"></div>
			<div class="cssClear"></div>
		</div>

<div>&nbsp;</div>

	<div class="pageContent"><h2>Resize Quality</h2></div>
	<div class="pc">When you upload photos, the photos get resized and optionally watermarked. Here you can set the quality of that resize.<br><br>The higher the number the higher the quality ... but also the higher the file size which will take longer to load. The default setting is 85. You may want to experiment changing the quality and uploading test photos.</div>
	<div class="underline"><input type="text" class="textfield" size=4 name="resize_quality" value="<?php  print htmlspecialchars(stripslashes($photo_setup['resize_quality']));?>"></div>
		

	<div>&nbsp;</div>

	<div class="pageContent"><h2>Amazon S3</h2></div>
	<div class="pc">Host your photos on Amazon S3 service.  <?php if($setup['unbranded'] !== true) { ?>
<a href="http://www.picturespro.com/sytist-manual/articles/hosting-photos-on-amazon-s3/" target="_blank"><b>Information and instructions on using this feature</b></a>.<?php } ?></div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="enable_amazon" id="enable_amazon" value="1" <?php if($photo_setup['enable_amazon'] == "1") { print "checked"; } ?>> <label for="enable_amazon">Enable Amazon S3</label></div>
	</div>
	<div class="underline">
		<div class="label">Access Key</div>
		<div><input type="text" name="awsAccessKey" id="awsAccessKey" value="<?php print $photo_setup['awsAccessKey'];?>" class="field100"></div>
	</div>
	<div class="underline">
		<div class="label">Secrete Key</div>
		<div><input type="text" name="awsSecretKey" id="awsSecretKey" value="<?php print $photo_setup['awsSecretKey'];?>" class="field100"></div>
	</div>
	<div class="underline">
		<div class="label">Bucket Name</div>
		<div><input type="text" name="awsBucketName" id="awsBucketName" value="<?php print $photo_setup['awsBucketName'];?>" class="field100"></div>
	</div>
	<div class="underline">
		<div class="label">Endpoint</div>
		<div><input type="text" name="amazon_endpoint" id="amazon_endpoint" value="<?php print $site_setup['amazon_endpoint'];?>" class="field100"></div>
		<div>Example: s3.amazonaws.com, s3-us-west-2.amazonaws.com .... do not include the http:// or trailing slash (/). </div>
	</div>


	</div>
</div>

<div style="width: 49%; float: right;">

	<div class="pageContent"><h2>Green Screen Backgrounds</h2></div>
	<?php $date = doSQL("ms_calendar", "*", "WHERE green_screen_gallery='1' ORDER BY date_id ASC "); ?>
	<div class="pc"><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>">Click here to manage your backgrounds</a></div>
<div>&nbsp;</div>

	<div class="pageContent"><h2><input type="checkbox" name="gallery_favicon" id="gallery_favicon" value="1" <?php if($photo_setup['gallery_favicon'] == "1") { print "checked"; } ?>> <label for="gallery_favicon">Custom Gallery Favicons</label></h2></div>
	<div class="pc">With this option selected, it will create a custom favicon with the gallery preview photo that is shown in the browser tab, when bookmarked and when added to the home screen of a tablet or phone. This works for galleries created in a section for client galleries / selling photos.</div>

<div class="cssClear"></div>
<div>&nbsp;</div>

	<div class="pageContent"><h2>Thumbnails Per Page</h2></div>
	<div class="pc">The thumbnails automatically populate as you scroll down the page, but after a while it can slow things download if there are a lot of photos in a gallery. Here you can limit how many total populate.</div>

	<div class="underline">
	<select name="thumb_limit">
	<option value="2" <?php if($photo_setup['thumb_limit'] == "2") { print "selected"; } ?>>40</option>
	<option value="5" <?php if($photo_setup['thumb_limit'] == "5") { print "selected"; } ?>>100</option>
	<option value="10" <?php if($photo_setup['thumb_limit'] == "10") { print "selected"; } ?>>200</option>
	<option value="20" <?php if($photo_setup['thumb_limit'] == "20") { print "selected"; } ?>>400</option>
	</select>
	</div>


<div class="cssClear"></div>
	<div>&nbsp;</div>


	<div class="pageContent"><h2>Download Zip File Limit</h2></div>
	<div class="pc">This will set the number of photos to put in a zip file when someone purchased multiple downloads on an order. When there are more photos than the limit, it will create multiple zip files to download. This is done because too many photos in one zip file can cause server errors.</div>

	<div class="underline">
	<input type="text" name="zip_limit" value="<?php print $photo_setup['zip_limit'];?>" size="3" class="center">
	</div>


<div class="cssClear"></div>

	<div>&nbsp;</div>
	<div class="pageContent"><h2><input type="checkbox" name="no_search_filename" id="no_search_filename" value="1" <?php if($photo_setup['no_search_filename'] == "1") { print "checked"; } ?>> <label for="no_search_filename">Do Not Search Filenames</label></h2></div>
	<div class="pc">With this option selected, when someone searches for photos, it will not search in the file name of the photo and only search key words on the front end.</div>

<div class="cssClear"></div>
<div>&nbsp;</div>

	<div>&nbsp;</div>
	<div class="pageContent"><h2>FTP Upload Process Settings</h2></div>
	<div class="pc">If you upload photos via FTP and then have Sytist process  the photos, below sets how long the processing goes and how long it rests. The process needs to rest some to keep the CPU down on the server while processing photos. 10 seconds processing and 10 seconds resting is recommended. </div>

	<div class="underline">
	<input type="text" name="ftp_process" value="<?php print $photo_setup['ftp_process'];?>" size="3" class="center"> seconds processing 
	</div>
	<div class="underline">
	<input type="text" name="ftp_rest" value="<?php print $photo_setup['ftp_rest'];?>" size="3" class="center"> seconds resting
	</div>


<div class="cssClear"></div>
<div>&nbsp;</div>


	<div class="pageContent"><h2>Passcode Photos Search By</h2></div>
	<div class="pc">When using the Passcode Photos feature, below is the setting of where you are going to add the passcode:</div>

	<div class="underline">
	<select name="passcode_photos_find">
	<option value="title" <?php if($photo_setup['passcode_photos_find'] == "title") { print "selected"; } ?>>In the Title</option>
	<option value="keyword" <?php if($photo_setup['passcode_photos_find'] == "keyword") { print "selected"; } ?>>In a Keyword</option>
	<option value="file" <?php if($photo_setup['passcode_photos_find'] == "file") { print "selected"; } ?>>In the File Name</option>
	<option value="filename" <?php if($photo_setup['passcode_photos_find'] == "filename") { print "selected"; } ?>>Just the File Name</option>
	</select>
	</div>


<div class="cssClear"></div>
	<div>&nbsp;</div>
	<div class="pageContent"><h2>Is EXIF enabled in the PHP installation?</h2></div>
	<div class="pc"><?php if(function_exists(exif_read_data)) { ?>Yes, EXIF is enabled and can read metadata from the photos when uploaded.<?php } else { ?>The EXIF function is NOT enabled in PHP. This is needed to read metadata (keywords) from photos when uploaded. If you need that functionality, contact your hosting company to see if they can enable it. <?php } ?></div>
	<div>&nbsp;</div>
	<div class="pageContent"><h2>View All Photos Default</h2></div>
	<div class="pc">This sets the default view sorting when viewing photos in the admin all photos section.</div>
	<div>

		<div class="underline">
			<div style="width:50%;" class="cssCell">Sort by </div><div style="width:50%;" class="cssCell">
			<input type="radio"  class="checkbox" name="def_all_orderby" value="pic_id" <?php if($photo_setup['def_all_orderby'] == "pic_id") { print "checked"; } ?>> Upload Date<br>
			<input type="radio"  class="checkbox" name="def_all_orderby" value="pic_date_taken" <?php if($photo_setup['def_all_orderby'] == "pic_date_taken") { print "checked"; } ?>> Date Taken<br>
			<input type="radio"  class="checkbox" name="def_all_orderby" value="pic_org" <?php if($photo_setup['def_all_orderby'] == "pic_org") { print "checked"; } ?>> File name<br>
			</div>
			<div class="cssClear"></div>
		</div>
		<div class="underline">
			<div style="width:50%;" class="cssCell">Which way</div><div style="width:50%;" class="cssCell">
			<input type="radio"  class="checkbox" name="def_all_acdc" value="ASC" <?php if($photo_setup['def_all_acdc'] == "ASC") { print "checked"; } ?>> Acending<br>
			<input type="radio"  class="checkbox" name="def_all_acdc" value="DESC" <?php if($photo_setup['def_all_acdc'] == "DESC") { print "checked"; } ?>> Decending<br>
			</div>
			<div class="cssClear"></div>
		</div>
</div>



<div>&nbsp;</div>


</div>



	<div>&nbsp;</div>

<div  class="bottomSave">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="photos">
		<input type="hidden" name="submitit" value="yup">
		<input type="hidden" name="date_type" value="news">
		<input type="submit" name="submit" id="submit" class="submit" value="Update Settings">
				</div>
				<div class="cssClear"></div>
		</div>
	</div></div></div></div></div>
			</form>

<?php  } ?>