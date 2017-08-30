<?php
include "../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
header('Content-Type: text/html; charset=utf-8');
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require "../photo.process.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
//adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');
$discard_small = 0;
$smallest = 300;
$check_rotate = $_REQUEST['check_rotate'];
$amazon = true;
ini_set('upload_max_filesize', "400M");
ini_set('post_max_size', "400M");
ini_set('max_execution_time',8000);
set_time_limit(50);
ini_set('memory_limit', '1024M');




/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/sy-photos'; // Relative to the root
$hash = $site_setup['salt']; 

$verifyToken = md5($hash . $_POST['timestamp']);
// print "################### ". $verifyToken." ----- ".$_POST['token'];

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $setup['path']. $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$name = $_FILES['Filedata']['name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','JPG','gif','GIF','png','PNG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {

		// move_uploaded_file($tempFile,$targetFile);


		$targetfilepath= $tempFile;


		$fsizes = array("180","152","120","76","60","16");

		$backup_folder = "favicons";
		$backup_folder_path = "".$setup['path']."/".$setup['misc_folder']."/$backup_folder";
		if(!is_dir($setup['path']."/".$setup['misc_folder']."/$backup_folder")) {
			$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['misc_folder'])), -4); 
			if($parent_permissions == "0755") {
				$perms = 0755;
			} elseif($parent_permissions == "0777") {
				$perms = 0777;
			} else {
				$perms = 0755;
			}
			mkdir("".$setup['path']."/".$setup['misc_folder']."/$backup_folder", $perms);
			chmod("".$setup['path']."/".$setup['misc_folder']."/$backup_folder", $perms);
			$fp = fopen($setup['path']."/".$setup['misc_folder']."/$backup_folder/index.php", "w");
			$info =  ""; 
			fputs($fp, "$info\n");
			fclose($fp);
		}




		$filename = cleanUploadFileName($name);
		$size_original = @GetImageSize($targetfilepath,$info); 
		$ext = strtolower(substr($full_name, -4));
		$new_image = imagecreatefrompng($targetfilepath);
		// Copy original upload
		$full_name = $setup['path']."/".$setup['misc_folder']."/favicons/icon.png";
		copy($targetfilepath,$full_name);

		foreach($fsizes AS $fsize) { 

			$name = $setup['path']."/".$setup['misc_folder']."/favicons/icon-".$fsize.".png";

			processPhoto($full_name,$size_original,$name,$fsize,$fsize,0,0,0,92,false,true);

		}
		updateSQL("ms_history", "do_reload='1' ");
		echo '1';
	}
}
?>