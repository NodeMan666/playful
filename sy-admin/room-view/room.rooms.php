<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a>   <?php print ai_sep;?> Room Photos</div>
<div class="clear"></div>
<script>
function deleteroomimg(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=rooms&action=deleteroomimg&room_id="+id, function(data) {
		$("#room-image-"+id).hide(200);
	});
}

</script>
<?php 
if($_REQUEST['action'] == "deleteroomimg") { 

	if($setup['demo_mode'] !== true) { 

		$room = doSQL("ms_wall_rooms","*","WHERE room_id='".$_REQUEST['room_id']."' ");
		if(file_exists($setup['path'].$room['room_small'])) { 
			unlink($setup['path'].$room['room_small']);
		}
		if(file_exists($setup['path'].$room['room_large'])) { 
			unlink($setup['path'].$room['room_large']);
		}
		deleteSQL2("ms_wall_rooms","WHERE room_id='".$room['room_id']."' ");
	}
	exit();
}
?>
<script>
jQuery(document).ready(function() {
	sortItems('sortable-list-frames','sort_order','orderRoomPhotos');
});
</script>

<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$rooms = whileSQL("ms_wall_rooms", "*", "ORDER BY room_order ASC");
while($room = mysqli_fetch_array($rooms)) { 
	$order[] = $room['room_order'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>
<div class="right">

	<?php if($setup['demo_mode'] == true) { ?>
		Room Upload Disabled For Demo
	<?php } else { ?>

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
				'fileTypeExts' : '*',
				'fileTypeDesc' : 'all files',
				'buttonText' : 'Upload Room Photo',
					'width':'300',
				 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
					 'token' : '<?php echo md5($hash.$timestamp); ?>', 
					'styleid':'<?php print $style['style_id'];?>',
					 'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
				'onQueueComplete' : function(queueData) {
					window.parent.location.href='index.php?do=photoprods&view=roomview&sub=rooms&sm=File(s) Uploaded';
					}, 
						'onUploadError' : function(file, errorCode, errorMsg, errorString) {
						alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
					}, 

				'swf'      : 'uploadify/uploadify.swf',
				'uploader' :'room-view/room-room-upload.php'
				// Put your options here
			});
		});
		</script>
	<?php } ?>
</div>

<div class="pc">These are the room photos available to select from in the Wall Designer. You can upload as many room photos as your like. After you have uploaded room photos, you will need to edit then and set the size and adjust the center. <a href="https://www.picturespro.com/sytist-manual/wall-designer/room-photos/" target="_blank">Video tutorial and more information here in the manual</a>.</div>
<div class="pc inlineli">
<?php 
$rooms = whileSQL("ms_wall_rooms", "*", "WHERE room_person<='0' ORDER BY room_order ASC");
if(mysqli_num_rows($rooms) > 0) { 

?>
<ul id="sortable-list-frames" class="sortable-list">
<?php 
while($room = mysqli_fetch_array($rooms)) { ?><li title="<?php print $room['room_id'];?>" id="room-image-<?php print $room['room_id'];?>" style="display: inline-block; width: 25%; padding: 0px; margin: 0px;">
	<div style="padding: 16px;">
		<div class="pc center"><?php if($room['room_width']<= 0) { ?><div class="error" style="width: 100%;">Width needs to be set</div><?php } else { ?><?php print $room['room_name'];?><?php } ?></div>
		<div class="pc center"><a href="index.php?do=photoprods&view=roomview&sub=room&room_id=<?php print $room['room_id'];?>"><img src="<?php print $setup['temp_url_folder'].$room['room_small'];?>" style="display: inline-block; width: 100%; height: auto;"></a></div>
		<div class="pc center">	
		<a href="index.php?do=photoprods&view=roomview&sub=room&room_id=<?php print $room['room_id'];?>" class="the-icons icon-pencil">edit</a> 
		<a href="javascript:deleteroomimg('<?php print $room['room_id'];?>');"  onClick="return confirm('Are you sure you want to delete this room photo? ');" class="the-icons icon-trash-empty">delete</a>
		</div>

		</div></li><?php } ?>

	</ul>
<?php } ?>

</div>
<div>&nbsp;</div>