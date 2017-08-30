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
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['bp_sub_preview'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['bp_sub_preview']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$sub['sub_date_id']."' ");
}

if($_REQUEST['action'] == "deletesubthumb") { 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_sub_preview='".$_REQUEST['bp_sub_preview']."'   ORDER BY bp_order ASC LIMIT  1 ");
	deleteSQL("ms_blog_photos", "WHERE bp_sub_preview='".$_REQUEST['bp_sub_preview']."' ", "1");
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']);
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med']);
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']);
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']);
	@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']);
	session_write_close();
	header("location: w-photos-sub-preview.php?bp_sub_preview=".$_REQUEST['bp_sub_preview']."& back_sub=".$_REQUEST['back_sub']." ");
	exit();
}



?>

<div class="pc"><h2>Preview Photo for <?php print $sub['sub_name'];?></h2></div>
<div class="pc">Here you can upload a photo to show for the preview photo of the sub gallery.</div>
<div>&nbsp;</div>
<div class="left p30 center">

<?php if($setup['demo_mode'] == true) { ?>
<input type="submit" name="demoupload" value="SELECT & UPLOAD PHOTOS" class="submit" onClick="return confirm('Uploading files has been disabled for security reasons. But this is where you will select photos to upload from your computer. ');">
<?php } else { ?>


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
			 'bp_sub_preview':'<?php print $sub['sub_id'];?>', 
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
			window.parent.location.href='index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['back_sub'];?>&sm=Photo Uploaded';
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
	<?php } ?>
</div>
<div class="right center p70"><?php 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_sub_preview='".$sub['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		?>
		<?php if($pic['pic_no_dis'] == "1") { ?><div class="pc center">Uploaded Preview Photo</div><?php } ?>
		<div class="pc center"><img src="<?php tempFolder();?>/<?php print $setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'];?>" class="thumbnail"></div>
		<?php if($pic['pic_no_dis'] == "1") { ?>
		<?php print "<div class=\"pageContent\"><a href=\"w-photos-sub-preview.php?bp_sub_preview=".$_REQUEST['bp_sub_preview']."& back_sub=".$_REQUEST['back_sub']."&action=deletesubthumb\" onClick=\"return confirm('Are you sure you want to delete this?');\">delete</a></div>";
		}
}
?>
</div>
<div class="clear"></div>
	<div>&nbsp;</div>


<?php require "w-footer.php"; ?>