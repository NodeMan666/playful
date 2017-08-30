<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$standard_per_page = 10;
$wm = doSQL("ms_watermarking", "*", "");


// securityCheck();
?>
<?php

anString($_REQUEST['thephotos']); // date_id
anString($_REQUEST['page']); // page number


$date = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['thephotos']."' ");

if(empty($_REQUEST['page'])) { 
	$page = 1;
} else {
	$page = $_REQUEST['page'];
}
$sq_page = $page * $standard_per_page - $standard_per_page;

$pic_file = $date['blog_photo_file'];

if($_REQUEST['mobile'] == 1) { 
	$pic_file = "pic_pic";
	$mobile = true;
}
if($_REQUEST['ipad'] == 1) { 
	$pic_file = "pic_med";
	$ipad = true;
}
$total_results = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE MD5(bp_blog)='".$_REQUEST['thephotos']."' ");

$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE MD5(bp_blog)='".$_REQUEST['thephotos']."' ORDER BY bp_order ASC  LIMIT $sq_page,$standard_per_page ");
while ($pic = mysqli_fetch_array($pics)){
$pic_file_select = selectPhotoFile($pic_file,$pic);
$x++;
	//	print "<li>$pic_file_select - ".$pic['pic_large']."";

		
	$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
	print "<div class=\"blogPhoto\">";
	if(!empty($date['date_aff_link'])) {
		print "<a href=\"/viewsite.php?site=".base64_encode($date['date_aff_link'])."\" target=\"_blank\"><img src=\"/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']."\" ".$dsize[3]." class=\"photo\" ></a>";
	} else {
		if($date['blog_contain'] == "1") { 
			$contain = true;
		} else {
			$contain = false;
		}
			$contain = true;

			print displayPhoto($pic,$pic_file_select,$wm,$dsize,$contain,'photo','0',$x,$border_color,$border_size,$bg_color,$bg_use);
	unset($pic_file_select);
	}

	print "</div>";
	$last_pic = $pic['bp_order'];
}


?>
<?php 
if(($total_results / $standard_per_page) > $page) { ?>
<div id="page-<?php print $page + 1;?>">
<div class="loadMore" onclick="loadStandardPhotos('<?php print $_REQUEST['thephotos']; ?>','<?php print $page + 1;?>','<?php print $_REQUEST['mobile'];?>', '<?php print $_REQUEST['ipad'];?>', 'page-<?php print $page + 1;?>');";>LOAD MORE</div>
<div>&nbsp;</div></div>
<?php } ?>
