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
if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/rooms")) { 
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
mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/rooms", $perms);
chmod("".$setup['path']."/".$setup['photos_upload_folder']."/rooms", $perms);
$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/rooms/index.html", "w");
fputs($fp, "$info\n");
fclose($fp);
}



$targetFolder = "/".$setup['photos_upload_folder']."/rooms"; // Relative to the root
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
	$room_name = "/".$setup['photos_upload_folder']."/rooms/".date('Ymdhis')."-". $filename;
	$large_name = $setup['path'].$room_name;

	if($ext == ".png") {
		processPhoto($targetfilepath,$size_original,$large_name,1500,1500,0,0,0,92,false,true);
	} else { 
		processPhoto($targetfilepath,$size_original,$large_name,1500,1500,0,0,0,92,false,false);
	}

	$last = doSQL("ms_wall_rooms", "*", "ORDER BY room_order DESC ");
	$order = $last['room_order'] + 1;
	$size = @GetImageSize($large_name,$info); 
	insertSQL("ms_wall_rooms", "room_small='".addslashes(stripslashes(trim($room_name)))."',  room_large='".addslashes(stripslashes(trim($room_name)))."', room_order='".$order."', room_photo_width='".$size[0]."', room_photo_height='".$size[1]."', room_center='.500', room_base='.500' "); 

}
?>