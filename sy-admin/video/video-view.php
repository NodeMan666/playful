<?php 
$path = "../../";
require "../w-header.php"; ?>
<?php 
$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$_REQUEST['vid_id']."' ");
?>
<center>
	<video controls="" <?php if($vid['vid_width'] > 0) { ?>width="<?php print $vid['vid_width'];?>"<?php } ?> <?php if($vid['vid_height'] > 0) { ?>height="<?php print $vid['vid_height'];?>"<?php } ?> >  
	<source type="video/mp4" src="/<?php print $setup['photos_upload_folder']; ?>/<?php print $vid['vid_folder'];?>/<?php print $vid['vid_file'];?>">
	</source>Your browser does not support the video tag.
</video>
</center>


<?php require "../w-footer.php"; ?>
