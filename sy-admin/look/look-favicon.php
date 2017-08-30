<?php 
$path = "../../";
require "../w-header.php"; ?>


<div class="pc"><h2>Favicon & Touch Icons</h2></div>
<div class="pc"><img src="graphics/favicons.JPG"></div>
<div class="pc">The Favicon is the icon shown in the browser tab and touch icons are icons shown when someone bookmarks your site on a phone or tablet. </div>
<div class="pc">Here you can upload your own favicon. 

<ul>
	<li><b>In must be a PNG file  at 256 x 256 pixels</b>
</ul>

<div>&nbsp;</div>
<div class="clear"></div>
<div style="margin-right: 32px; ">
<input type="file" name="file_upload" id="file_upload" />
<?php 
$hash = $site_setup['salt']; 
$timestamp = date('Ymdhis');
?>
<script>
$(function() {
    $('#file_upload').uploadify({
         'multi'    : false,

		'debug'    : false,	
		'method'   : 'post',
		'fileSizeLimit':'20MB',
		'buttonText' : 'Upload Favicon',
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
			window.location.href='index.php?do=look&view=miscFiles&folder=favicons&reload=1';
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
        'uploader' : 'uploadify/sy-upload-favicon.php'
        // Put your options here
    });
});
</script>
</div>


<div>
<?php if(file_exists($setup['path']."/".$setup['misc_folder']."/favicons/icon.png")) { ?>
<div>Your current favicon</div>
<div>
<img src="<?php print $setup['temp_url_folder']."/".$setup['misc_folder']."/favicons/icon-120.png";?>">
</div>
<?php } ?>
<div class="pc">When you upload a favicon it will automatically be resized to the sizes needed for touch icons & favicon and will be stored in the Misc. Files -> favicons folder.</div>
</div>
<div class="clear"></div>
<?php require "../w-footer.php"; ?>