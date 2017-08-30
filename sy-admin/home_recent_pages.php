<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$gallery = doSQL("ms_galleries", "*", "WHERE gal_id='".$_REQUEST['gal_id']."' ");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
?>
<div class="pageContent"><!-- <a href="" onClick="refreshRecentPages(); return false;"><?php print ai_refresh;?></a>--> <span style="float: left;" class="bold">Recently Viewed Pages</span> <span style="float: right;"><a href="index.php?do=stats&action=pages">Most Popular</a></span><div class="cssClear"></div></div>

<div id="roundedForm">
<?php 
	$def = doSQL("ms_photos", "*", "WHERE pic_id='".$site_setup['default_photo']."' ");
$dates = whileSQL("ms_stats_site_pv LEFT JOIN ms_stats_site_visitors ON ms_stats_site_pv.pv_ref_id=ms_stats_site_visitors.st_id", "*, date_format(pv_date, '%M %e, %Y ')  AS pv_date, date_format(st_last_visit, '%M %e, %Y ')  AS st_last_visit_show, time_format(pv_time, '%h:%i %p') AS pv_time", "ORDER BY ms_stats_site_pv.pv_id DESC limit 5");
if(mysqli_num_rows($dates)<=0) { 
	print "<div class=\"row\">No Data</div>";
}

while($date= mysqli_fetch_array($dates)) {  ?>
<div class="row"><?php showVisPageThumb($date);?></div>
<?php  } ?>
</div>


