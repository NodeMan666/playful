<?php
include "../sy-config.php";
session_start();
// error_reporting(0);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
//adminsessionCheck();
if($_POST['sweetnesslogo'] == "1") { 
	if(!is_dir($setup['path']."/".$setup['misc_folder']."/sweetness")) { 
		$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['misc_folder']."")), -4); 
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
	mkdir("".$setup['path']."/".$setup['misc_folder']."/sweetness", $perms);
	chmod("".$setup['path']."/".$setup['misc_folder']."/sweetness", $perms);
	$fp = fopen("".$setup['path']."/".$setup['misc_folder']."/sweetness/index.html", "w");
	fputs($fp, "$info\n");
	fclose($fp);
	}

}


if(!empty($_POST['folder'])) { 
	$targetFolder = '/sy-misc/'.$_POST['folder']; // Relative to the root
} else { 
	$targetFolder = '/sy-misc'; // Relative to the root
}
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
	
	move_uploaded_file($tempFile,$targetFile);
}
	if($_POST['sweetnesslogo'] == "1") { 
		updateSQL("ms_show", "logo_file='".addslashes(stripslashes($_FILES['Filedata']['name']))."' WHERE default_feat='1' ");
	}




	if($_POST['videoupload'] == "1") { 
		require_once('getid3/getid3.php');
		require "photo.process.functions.php"; 
		$pic_folder = createPhotoFolder();
		$add_hash = substr(md5(date('ymdHis')),0,15);
		$org_prefix = "original_" .$add_hash;
		// $targetfilepath = $setup['path']."/sy-upload/".$_REQUEST['vid_file'];
		// $filename = cleanUploadFileName($_REQUEST['vid_file']);

		$full_name =  $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$org_prefix."_". $name;

		// $size_original = @GetImageSize($targetfilepath,$info); 

		// Copy original upload
		$filesize= @FileSize($targetFile); 
		rename($targetFile,$full_name);
		chmod($full_name,0644);

		unlink($targetFile);
		$getID3 = new getID3;
		$file = $getID3->analyze($full_name);

		$key = MD5(date('ymdHis').makesalt());

		$vid_id = insertSQL("ms_videos", "
		vid_file='".addslashes(stripslashes(trim($org_prefix."_". $name)))."', 
		vid_date=NOW(),
		vid_folder='".$pic_folder."', 
		vid_name='".addslashes(stripslashes(trim($name)))."',
		vid_size='".addslashes(stripslashes(trim($file['filesize'])))."',
		vid_width='".$file['video']['resolution_x']."',
		vid_height='".$file['video']['resolution_y']."',
		vid_length='".$file['playtime_string']."',
		vid_key='$key' ");
	}


?>
