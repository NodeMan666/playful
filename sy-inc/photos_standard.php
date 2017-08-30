<?php
if($site_type == "mobile") { 
$mini_place = "bottom";
} else { 
$mini_place = "left";
}
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$pic_file = $date['blog_photo_file'];
$pics_where = "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ";
$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_q ORDER BY bp_order ASC  ");
$total_images = mysqli_num_rows($pics);
$pics_array = array();
while ($pic = mysqli_fetch_array($pics)){
	$pic_file_select = selectPhotoFile($date['blog_photo_file'],$pic);
	if($first_pic <=0) { 
		$first_pic = $pic['pic_id'];
	}
	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
	if($size[0] > $max_width) { $max_width = $size[0]; } 
	if($size[1] > $max_height) { $max_height = $size[1]; } 

	array_push($pics_array,$pic);
}
?>

<script>
$(document).ready(function(){
	$(".pagemini").hover(
	  function () {
		// removephotozoom();

		var this_id = this.id;
		$(".pmphoto").hide();

		$("#"+$("#"+this_id).attr("show")).show();
	  },
	  function () {
		
	  }
	);
});

</script>



<div id="photoWithMinis">
<?php if($total_images > 1) { ?>
	<div class="photominis hidesmall">
		<div style="padding: 8px;">

	<?php 
		foreach($pics_array AS $pic) { 
			// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
			?>
			<img id="th-<?php print $pic['pic_id'];?>" src="<?php print getimagefile($pic,'pic_mini');?>" class="pagemini" show="p-<?php print $pic['pic_id'];?>" cap="cap-<?php print $pic['pic_id'];?>" ptitle="<?php print htmlspecialchars($pic['pic_title']);?>" pcaption="<?php print htmlspecialchars($pic['pic_text']);?>" >
			<?php } ?>
		</div>	
	</div>
	<?php } ?>



	<?php if($total_images > 1) { ?><div class="photoContainerOuter nofloatsmall" ><?php  } else { ?><div class="photoContainerOuterOne" ><?php } ?>
		<div style="padding: 8px;">
		<?php
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_q ORDER BY bp_order ASC  ");
		while($pic = mysqli_fetch_array($pics)) { 
			$p++;
			$size = getimagefiledems($pic,$pic_file_select);


		?>
		<img id="p-<?php print $pic['pic_id'];?>" src="<?php print getimagefile($pic,$pic_file_select); ?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px; <?php if($p > 1) { print "display: none;"; } ?>" class="pmphoto">
		<?php } ?>
		</div>
	</div>

	<div class="clear"></div>
</div>
<?php if($total_images > 1) { ?>
	<div class="photominis hidden showsmall">
		<div style="padding: 8px;">

	<?php 
		foreach($pics_array AS $pic) { 
			// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
			?>
			<img id="th-<?php print $pic['pic_id'];?>" src="<?php print getimagefile($pic,'pic_mini');?>" class="pagemini" show="p-<?php print $pic['pic_id'];?>" cap="cap-<?php print $pic['pic_id'];?>" ptitle="<?php print htmlspecialchars($pic['pic_title']);?>" pcaption="<?php print htmlspecialchars($pic['pic_text']);?>" >
			<?php } ?>
		</div>	
		</div>
	<?php } ?>
