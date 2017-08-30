<?php
include "../../sy-config.php";
session_start();
// error_reporting(0);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require "../photo.process.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
//adminsessionCheck();
if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/frames")) { 
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
		$perms = 0755;
	print "<li>C";
	}
mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/frames", $perms);
chmod("".$setup['path']."/".$setup['photos_upload_folder']."/frames", $perms);
$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/frames/index.html", "w");
fputs($fp, "$info\n");
fclose($fp);
}



$targetFolder = "/".$setup['photos_upload_folder']."/frames"; // Relative to the root
$hash = $site_setup['salt']; 
$verifyToken = md5($hash . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $setup['path']. $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$name = $_FILES['Filedata']['name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','JPG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	// move_uploaded_file($tempFile,$targetFile);
	$filename = cleanUploadFileName($name);

	$targetfilepath= $tempFile;
	$size_original = @GetImageSize($targetfilepath,$info); 

	$ext = strtolower(substr($filename, -4));
	if($ext == ".png") {
		$new_image = imagecreatefrompng($targetfilepath);
	} else {
		$new_image = imagecreatefromjpeg($targetfilepath);
	}
	$frame_name = "/".$setup['photos_upload_folder']."/frames/".date('Ymdhis')."-". $filename;
	$large_name = $setup['path'].$frame_name;

	if($ext == ".png") {
		processPhoto($targetfilepath,$size_original,$large_name,800,800,0,0,0,92,false,true);
	} else { 
		processPhoto($targetfilepath,$size_original,$large_name,800,800,0,0,0,92,false,false);
	}

	$last = doSQL("ms_frame_images", "*", "WHERE img_style='".$_REQUEST['styleid']."' ORDER BY img_order DESC ");
	$order = $last['img_order'] + 1;
	$size = @GetImageSize($large_name,$info); 
	insertSQL("ms_frame_images", "img_style='".$_REQUEST['styleid']."', img_small='".addslashes(stripslashes(trim($frame_name)))."',  img_large='".addslashes(stripslashes(trim($frame_name)))."', img_order='".$order."' "); 

}
?>