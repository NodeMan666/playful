<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-control: private"); 
ob_start(); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}
$sytist_store = true;
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
adminsessionCheck();
if($sytist_store == true) { 
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
	$store = doSQL("ms_store_settings", "*", "");
}

$pics = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE  bp_blog='".$_REQUEST['id']."' ORDER BY pic_org ASC ");
while($pic = mysqli_fetch_array($pics)) { 
	?><div class="jthumb"><img src="/sy-photos/<?php print $pic['pic_folder']."/".$pic['pic_pic'];?>" data-width="<?php print $pic['pic_small_width'];?>" data-height="<?php print $pic['pic_small_height'];?>"></div> 
	<?php 
	// print "<li>".$pic['pic_org'];
}


?>
