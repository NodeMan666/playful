<?php $total_results = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE bp_blog='".$date['date_id']."' "); ?>
<script>
 $(document).ready(function(){
	var i = 0;
	var elems = $(".gifimg");
	var total = elems.length;
	// alert( elems.length);
	function loadgifs() { 
	// $("#log").show().append(i+" "+total);
	 if (i >= total) return;
		 $("#gif"+i).attr("src",$("#gif"+i).attr("data-src"));
		$("#gif"+i).imagesLoaded(function() {
			// $("#log").show().append($("#gif"+i).attr("data-src")+" / ");

			$("#gif"+i).show();
			lg = $("#gif"+i).attr("data-lg");
			$("#"+lg).hide();
			i++;
			setTimeout(function() {
				loadgifs();
			}, 1000);
		
		});
	}
		loadgifs();
});

</script>

<div>&nbsp;</div>
<div id="photoScrollerContainer">
<?php
$startHidden = true;

$x = 0;
$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ORDER BY bp_order ASC ");
while ($pic = mysqli_fetch_array($pics)){
	$img_small = getimagefile($pic,"pic_pic");
	$img_large = getimagefile($pic,"pic_full");
	$size = getimagefiledems($pic,'pic_full');
	?>
	<div id="gd<?php print $pic['pic_id'];?>" style="background: url('<?php print $img_small;?>'); background-size: 100% auto; width:<?php print $size[0];?>px; height: <?php print $size[1];?>px; margin: auto;"><img src="/sy-graphics/loading.gif" id="lg<?php print $pic['pic_id'];?>"><img id="gif<?php print $x;?>" data-src="<?php print $img_large;?>" style="max-width:<?php print $size[0];?>px; max-height: <?php print $size[1];?>px; width: 100%; height: auto; margin: auto; display: none;" class="gifimg" data-lg="lg<?php print $pic['pic_id'];?>"></div>

	<div>&nbsp;</div>
	<?php 
	$x++;
}


?>

</div>
