<?php 	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); ?>
<div id="pageTitle"><a href="index.php?do=news">Site Content</a> 
<?php
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
?>
<?php print ai_sep;?>  <a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>"><?php print $cat['cat_name'];?></a> <?php print ai_sep;?> Category Preview Photo</div>


<div>&nbsp;</div>
<?php include "news.category.tabs.php"; ?>
<div id="roundedFormContain">
<?php if($cat['cat_id'] <=0) { ?>
<div class="pageContent">Once you save your new category you will be able to upload a thumbnail.</div>
<?php } else { ?>
	<div class="pageContent">Here you can upload a photo for a preview of the category.</div>
	<?php $upload_session = date('Y-m-d_H-i-s'); ?>


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
			 'date_id':'<?php print $date['date_id'];?>', 
			'cat_id':'<?php print $cat['cat_id'];?>',
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
			window.location.href='index.php?do=news&action=categoryThumbnail&cat_id=<?php print $cat['cat_id'];?>&view=photos&sm=Photo Uploaded';
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
	<div>&nbsp;</div>
	<?php 
	$pic = doSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_cat='".$cat['cat_id']."' ");
	if(!empty($pic['pic_id'])) { 
		$size = getimagefiledems($pic,'pic_th'); 
		?>
	<div class="pageContent center">
		<div><h3>Current Photo</h3></div>
		<div><img src="<?php print getimagefile($pic,'pic_th');?>" <?php print $size[3];?>"></div>
		<div><a href="index.php?do=news&action=editCategory&deleteCategoryThumb=<?php print $cat['cat_id'];?>&pic_id=<?php print $pic['pic_id'];?>"  onClick="return confirm('Are you sure you want to delete this thumbnail? ');" >Delete</a></div>
	</div>
	<?php } ?>

<?php } ?>
	<div>&nbsp;</div>	
</div>
<div class="cssClear"></div>
</div>