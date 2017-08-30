<?php 
$path = "../../../";
require "../../w-header.php";

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

	exit();
}


?>
<script>
function deleteproductphoto(pic_id) { 
	$.get("store/photoprods/w-product-photos.php?action=deleteThumb&pic_id="+pic_id+"", function(data) {
		$("#pic-"+pic_id).hide();
	});


}
</script>
	<div class="underlinelabel">Product Preview Photo</div>
	<div class="pc">Here you can upload a photo or photos to show with the product in the price list. After you have uploaded a photo or made changes, <a href="index.php?do=photoprods&view=base">click here to view changes in the product base</a>.</div>
	<?php $pp = doSQL("ms_photo_products","*", "WHERE pp_id='".$_REQUEST['pp_id']."' "); ?>

	<?php if($setup['demo_mode'] !== true) { ?>

	<script>
		jQuery(document).ready(function() {
			sortItems('sortable-list-pics','sort_order-pics','orderProductPhotos');
		});
		</script>
		<form id="dd-form" action="admin.action.php" method="post">
		<input type="hidden" name="action" value="orderProductPhotos">
		<?php
			$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$pp['pp_id']."' ORDER BY bp_order ASC");

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
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$pp['pp_id']."' ORDER BY bp_order ASC");
		if(mysqli_num_rows($pics) <=0) { ?>
		<div class="pc">			No photos uploaded</div>
		<?php } ?>
	<ul id="sortable-list-pics" class="sortable-list center">
	<?php 

		while($pic = mysqli_fetch_array($pics)) { 
			$size = getimagefiledems($pic,'pic_th');	
			?>
			<li title="<?php print $pic['bp_id'];?>" style="display: inline;" id="pic-<?php print $pic['pic_id'];?>">
			<div><img src="<?php print getimagefile($pic,'pic_th');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail"></div>
			<div><a href="" onClick="deleteproductphoto('<?php print $pic['pic_id'];?>'); return false;" >Delete</a></div>
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
					'pp_id':'<?php print $pp['pp_id'];?>',
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
					productphotos('<?php print $pp['pp_id'];?>');
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


<?php require "../../w-footer.php"; ?>