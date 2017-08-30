<?php
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;

?>
<?php 	
	
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=<?php print $_REQUEST['do'];?>">Sections</a>  
<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>

 	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		 <span>Editing:  <?php if($date['page_home'] == "1") { print "Home Page"; }  else { print $date['date_title']; } ?> </span>
	<?php  }  else { ?>
		 Creating New 
		 <?php if(!empty($_REQUEST['page_under'])) { 
		$udate = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['page_under']."' ");
		print "Under: ".$udate['date_title'];
	}
	?>
	<?php  } ?>
</div>


<?php include "news.tabs.php"; ?>
<div id="roundedFormContain">
The preview photo is the photo shown when content is listed. You can select from existing photos for this page by clicking <?php print ai_photo;?> under the thumbnail. If you wish, you can upload a totally different photo here.
<div>&nbsp;</div>
<div id="">
<div class="underlinelabel">Upload preview photo</div>


	<?php if($setup['demo_mode'] == true) { ?>
	<input type="submit" name="demoupload" value="SELECT & UPLOAD PHOTOS" class="submit" onClick="return confirm('Uploading files has been disabled for security reasons. But this is where you will select photos to upload from your computer. ');">
	<?php } else { ?>



<div class="underline">
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
		'fileTypeExts' : '*.jpg;*.gif;*.png',
		'fileTypeDesc' : 'jpg;gif;png',
		'buttonText' : 'Select Photo',
		 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
			 'token' : '<?php echo md5($hash.$timestamp); ?>', 
			'date_preview_id':'<?php print $date['date_id'];?>',
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
			 'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
			'onQueueComplete' : function(queueData) {
			window.location.href='index.php?do=news&action=thumbPreview&date_id=<?php print $date['date_id'];?>&view=photos&sm=Photo Uploaded';
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
</div>
	<?php } ?>
	<div>&nbsp;</div>



</div>
<div>&nbsp;</div>
<div id="">
<div class="underlinelabel">Current Photo</div>
<div class="underline">
<?php 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		?>
		<?php if($pic['pic_no_dis'] == "1") { ?><div class="pc center">Uploaded Photo</div><?php } ?>
		<div class="pc "><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail"></div>
		<?php 
	if($pic['pic_no_dis'] == "1") { 
			print "<div class=\"pageContent\"><a href=\"index.php?do=news&deletePageThumb=".$date['date_id']."\" onClick=\"return confirm('Are you sure you want to delete this photo?. ');\">delete</a></div>";
		}
} else {
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		?>
		<div class="pc">First photo from photos on this page</div>
		<div class="pc "><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail"></div> 
		<?php 
	}
}
?>
</div>
</div>
</div>
<div>&nbsp;</div>

