<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
$dbcon = dbConnect($setup);
adminsessionCheck($_SESSION);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$pic = doSQL("ms_photos", "*", " WHERE pic_id='".$_REQUEST['image']."' ");
 if(empty($pic['pic_id'])) { die("Not Found");}
if(!empty($pic['pic_med'])) { 
	$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med'].""); 
	$large_photo = $pic['pic_med'];
} elseif(!empty($pic['pic_large'])) { 
	$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large'].""); 
	$large_photo = $pic['pic_large'];
} else {
	$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 
	$large_photo = $pic['pic_pic'];
}

$theImage = $setup['photos_upload_folder']."/".$pic['pic_folder']."/".$large_photo;
$im_src = imagecreatefromjpeg($setup['path']."/".$theImage);
$new = $im_src;


$theimage= date('Ymdhis').".jpg";

mysqli_close($dbcon);

header("Content-transfer-encoding: binary\n"); 
header("Content-Type: image/jpeg"); 
header("Content-Disposition: filename=\"$theimage\" ");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: private");
ImageJpeg ($new,NULL, 93);
imagedestroy($new);
exit();
?>
