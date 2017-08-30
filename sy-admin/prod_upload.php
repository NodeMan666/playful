<?php

include "../sy-config.php";
session_start();
error_reporting(0);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
//adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');

$prefix = date('Ymdhis')."_";
if(!is_dir($setup['path']."/".$setup['downloads_folder'])) { 
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
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
	mkdir("".$setup['path']."/".$setup['downloads_folder'], $perms);
	chmod("".$setup['path']."/".$setup['downloads_folder'], $perms);
	$fp = fopen("".$setup['path']."/".$setup['downloads_folder']."/index.php", "w");
	$info = "<h1>Directory Listing Denied</h1>";
	fputs($fp, "$info\n");
}

$targetFolder = "/".$setup['downloads_folder'];


$hash = $site_setup['salt']; 
$verifyToken = md5($hash . $_POST['timestamp']);
	print "################# CHECK TOKEN ######  ";
	print "################# ".$_POST['token']."  = $verifyToken ######  ";

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	print "################# TOKEN PASSED ######  ";
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $setup['path']. $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $prefix.$_FILES['Filedata']['name'];
	$name = $_FILES['Filedata']['name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','JPG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	print "################# ".$targetFile;
	move_uploaded_file($tempFile,$targetFile);


	if(!empty($_POST['date_id'])) {
		$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$_POST['date_id']."' ");
		if(!empty($prod['prod_file'])){
			if(file_exists($setup['path']."/".$setup['downloads_folder']."/".$prod['prod_file'])) { 
				unlink( $setup['path']."/".$setup['downloads_folder']."/".$prod['prod_file']);
			}
		}
		updateSQL("ms_calendar", "prod_file='".$prefix.$_FILES['Filedata']['name']."' WHERE date_id='".$_POST['date_id']."' ");
	}
}

?>