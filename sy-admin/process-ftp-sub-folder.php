<?php 
if(empty($path)) { 
	$path = "../";
}
include $path."sy-config.php";
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
require $path."".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require "admin.icons.php";
require "photos.functions.php";
require "photo.process.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$ipinfo = doSQL("ms_iptc", "*", "");
$processTime = 15; 
$restTime = 10;
adminsessionCheck();
?>
<html>
<HEAD>
<TITLE>Processing Photos - Please Wait</TITLE>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<meta http-equiv="refresh" content="22"> 
<link rel="stylesheet" href="css/white.css" type="text/css">
</HEAD>
<?php 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$_REQUEST['wm_images_file'] = rawurldecode(stripslashes(stripslashes($_REQUEST['wm_images_file'])));
$_REQUEST['wm_logo_file'] = rawurldecode(stripslashes(stripslashes($_REQUEST['wm_logo_file'])));

if(!empty($_REQUEST['sub_id'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	if(empty($sub['sub_under_ids'])) { 
		$sub_under_ids = $sub['sub_id'];
	} else { 
		$sub_under_ids = $sub['sub_under_ids'].",".$sub['sub_id'];
	}
	$sub_under = $sub['sub_id'];
}

$page_link = MD5($_REQUEST['sub_folder'].$_REQUEST['date_id'].time());

$sub_folder_name = $_REQUEST['sub_folder'];
$pos = strripos($sub_folder_name,"/");
if ($pos === false) {

} else {
	$sub_folder_name = substr($sub_folder_name,$pos);
}

$sub_folder_name = ltrim($sub_folder_name,"/");
$sub_folder_name = str_replace("_"," ",$sub_folder_name);
// $sub_folder_name = utf8_encode($sub_folder_name);
## Now for some reason adding the utf8_encode to sub gallery name is causing issues. ## 

$sub_folder_name = $sub_folder_name;


$cksub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$sub_under."' AND sub_name='".addslashes(stripslashes(trim($sub_folder_name)))."' ");
if(!empty($cksub['sub_id'])) { 
	$new_sub = $cksub['sub_id'];
} else { 
	$new_sub = insertSQL("ms_sub_galleries", "sub_date_id='".$date['date_id']."', sub_name='".addslashes(stripslashes(trim($sub_folder_name)))."', sub_pass='".addslashes(stripslashes(trim($_REQUEST['sub_pass'])))."', sub_under_ids='".$sub_under_ids."' , sub_under='".$sub_under."', sub_link='".$page_link."' ");
}
header("location: process-ftp-upload.php?date_id=".$date['date_id']."&sub_id=".$new_sub."&folder=".rawurlencode($_REQUEST['folder'])."&sub_folder=".rawurlencode($_REQUEST['sub_folder'])."&discard_dups=".$_REQUEST['discard_dups']."&check_rotate=".$_REQUEST['check_rotate']."&watermark_photos=".$_REQUEST['watermark_photos']."&logo_photos=".$_REQUEST['logo_photos']."&wm_images_file=".rawurlencode($_REQUEST['wm_images_file'])."&wm_logo_file=".rawurlencode($_REQUEST['wm_logo_file'])."&wm_images_location=".$_REQUEST['wm_images_location']."&wm_add_logo_location=".$_REQUEST['wm_add_logo_location']."&discard_original=".$_REQUEST['discard_original']."&no_meta=".$_REQUEST['no_meta']."");
session_write_close();
exit();

?>