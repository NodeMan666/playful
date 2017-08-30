
<?php if($date['blog_kill_side_menu'] == "1") { ?>
<style>
	#sideMenuContainer { 
	display: none !important;
}
#pageContentContainer { 
	width: 100%;
}
</style>
<?php } ?>
<?php $total_results = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE bp_blog='".$date['date_id']."' "); ?>


<script>
var isslideshow = false;
<?php if($date['blog_enlarge'] == "1") { ?>
var clickenlarge = true;
<?php } else { ?>
	var clickenlarge = false;
<?php } ?>
	var add_margin_page = <?php print ($css['photo_page_padding'] + $css['photo_page_border_size']) * 2;?>;

	if(clickenlarge == true) { 
/*
	 $(document).ready(function(){
		$(".photoHidden").click(function() {
				var this_id = this.id;
				showImage(this_id);

		});

	 });
*/
}
</script>

<script>
 $(document).ready(function(){

	var elems = $(".photo");
	var lastID = elems.length - 1;


	$(".photo").each(function(i){
		var this_id = this.id;
		$("#"+this_id).imagesLoaded(function() {
			getCaption(this_id);
		});
 	});
});

</script>

<script>
 $(document).ready(function(){

	var elems = $(".photocontainer");
	var lastID = elems.length - 1;



 });

</script>

<div>&nbsp;</div>
<?php
$load_standard_photos = true;
$standard_per_page = 10;
$pic_file = $date['blog_photo_file'];

if($mobile == true) { 
	$pic_file = "pic_pic";
	$mobile = true;
}
if($ipad == true) { 
	$pic_file = "pic_med";
	$ipad = true;
}
?>
<div id="photoScrollerContainer">
<?php
$startHidden = true;
if($date['caption_location'] == "1") { 
	$captionwhere = "below";
}
if($mobile == 1) { 
	$captionwhere = "below";
}
// print "<h1>Mobile: $mobile</h1>";

$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ORDER BY bp_order ASC ");
while ($pic = mysqli_fetch_array($pics)){
$pic_file_select = selectPhotoFile($pic_file,$pic);
// $pic['pic_text'] = $pic_file_select;
$x++;
	//	print "<li>$pic_file_select - ".$pic['pic_large']."";

		
	// $dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
		$dsize = getimagefiledems($pic,$pic_file_select);
	
	print "<div class=\"blogPhoto\" ";
	if($date['date_aff_site'] > 0) { 
		$site = doSQL("ms_aff_info", "*", "WHERE aff_id='".$date['date_aff_site']."' "); 
	print " onClick=\"window.open('/viewsite.php?site=".MD5($site['aff_id'])."&l=pic&p=".$date['date_id']."')\" style=\"cursor: pointer;\"";	}
	print ">";

	$contain = true;
	if(($date['date_aff_site'] > 0)&&($total_results == $x)==true) { ?>
 <div id="playVideoPromo">
	<div style="width: 100%; height: 600px; position: relative; background: transparent url('<?php print "/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""; ?>') center center no-repeat;">
	<div><img src="/sy-misc/play-movie.png" style="width: 100%; height: 600px;"></div>
	<div style="position: absolute; float: left; top: 0; left: 0; width: 100%; height: 600px; text-align: center;">
	<div style="margin-top: 400px; background: #000000; opacity: .7;"><h1 style="color: #FFFFFF;"><?php print $site['aff_video_overlay_text'];?></h1></div>
	</div>
	</div>
 </div>
<?php } else { 

			print displayPhoto($pic,$pic_file_select,$wm,$dsize,$contain,'photo','0',$x,$border_color,$border_size,$bg_color,$bg_use,"relative", "block", $bcat['cat_id'],$bcat['cat_watermark'],$bcat['cat_logo'],$captionwhere,$date,$free,$sub);
	}
	//print "<div><a href=\"\" onclick=\"buyphoto('".$pic['pic_key']."'); return false;\" class=\"checkout\">Buy Photo</a></div>";
	print "</div>";
	print "<div>&nbsp;</div>";
	$last_pic = $pic['bp_order'];
}


?>

<!-- 
<div id="page-2" style="display: none;">
<?php 
if($total_results >  $standard_per_page){ ?>

<div class="loadMore" onclick="loadStandardPhotos('<?php print md5($date['date_id']); ?>','2','<?php print $_REQUEST['mobile'];?>', '<?php print $_REQUEST['ipad'];?>', 'page-2');";>LOAD MORE</div>
<div>&nbsp;</div>
<?php } ?>
</div>
-->
<div id="listPhotos"></div>
<div id="endpage"></div>
</div>
<div id="photoBGContainer" style="display: none;" ><div id="photoBG" ></div></div>
