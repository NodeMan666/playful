<script>
function thisProcess(id) { 
	$(".hidelink").hide();
	$("#"+id).html('Please Wait');
}
</script>
<?php

if($_REQUEST['action'] == "addVideo") { 
	require_once('getid3/getid3.php');

	require "photo.process.functions.php"; 
	$pic_folder = createPhotoFolder();
	$add_hash = substr(md5(date('ymdHis')),0,15);
	$org_prefix = "original_" .$add_hash;
	$targetfilepath = $setup['path']."/sy-upload/".$_REQUEST['vid_file'];
	$filename = cleanUploadFileName($_REQUEST['vid_file']);

	$full_name =  $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$org_prefix."_". $filename;

	// $size_original = @GetImageSize($targetfilepath,$info); 

	// Copy original upload
	$filesize= @FileSize($targetfilepath); 
	rename($targetfilepath,$full_name);
	chmod($full_name,0644);

	unlink($targetfilepath);
	$getID3 = new getID3;
	$file = $getID3->analyze($full_name);

	$key = MD5(date('ymdHis').makesalt());

	$vid_id = insertSQL("ms_videos", "
	vid_file='".addslashes(stripslashes(trim($org_prefix."_". $filename)))."', 
	vid_date=NOW(),
	vid_folder='".$pic_folder."', 
	vid_name='".$filename."',
	vid_size='".$file['filesize']."',
	vid_width='".$file['video']['resolution_x']."',
	vid_height='".$file['video']['resolution_y']."',
	vid_length='".$file['playtime_string']."',
	vid_key='$key' ");


	header("location: index.php?do=video");
	$_SESSION['sm'] = "Video Added";
	session_write_close();
	exit();
}

	$dir_name = $setup['path']."/sy-upload";
	$x = 1;
	$dir = opendir($dir_name); 
	$dirList = array();
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..")) { 
			if(!is_dir($dir_name."/$file")) {
				$path = $dir_name."/$file";
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				if($ext == "mp4") { 
					$dir_list .= "<li>$file - $ext ";
					array_push($dirList, $file);
				}
			}
		} 
	} 

	@closedir($dir); 

	if(count($dirList) > 0) { ?>
	<div style="padding: 24px;">
	<h3>Pending Videos</h3>
	<?php foreach($dirList AS $dir) { 
		$ct++; ?>
		<div class="sideunderline">
		<div class="left"><?php print $dir;?></div>
		<div class="right" id="folder-<?php print $ct;?>">
		<?php print "<a href=\"index.php?do=video&action=addVideo&vid_file=".urlencode($dir)."\" target=\"_parent\" onclick=\"thisProcess('folder-".$ct."');\" class=\"hidelink\">Process</a>"; ?>
		</div>
		<div class="clear"></div></div>

	<?php } ?>
	</ul>
</div>
<?php } ?>
<div class="newsection">
<div style="padding: 24px;">

	<div class="underlinelabel">Logo</div>
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
			'fileTypeExts' : '*',
			'fileTypeDesc' : 'all files',
			'buttonText' : 'Upload Video File',
			 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
				 'token' : '<?php echo md5($hash.$timestamp); ?>', 
				'folder':'',
				 'videoupload':'1' },
			'onQueueComplete' : function(queueData) {
				window.parent.location.href='index.php?do=video';
				}, 
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
					alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
				}, 

			'swf'      : 'uploadify/uploadify.swf',
			'uploader' :'misc_upload.php'
			// Put your options here
		});
	});
	</script>



<h3>Uploading Videos</h3>
To upload videos, you will need to FTP the video directly to the "sy-upload" folder on the server. Once it has uploaded, return to this page to add the video to the database.
<br><br>
<a href="index.php?do=video">refresh</a>
</div>
</div>