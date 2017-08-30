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
date_default_timezone_set(''.$site_setup['time_zone'].'');
if(!is_referer()) { 
	die();
}


$hash = $site_setup['salt']; 
$verifyToken = md5($hash . $_REQUEST['timestamp']);
if($_REQUEST['token'] !== $verifyToken) { die("can not verify token"); } 
if(!$_FILES) { die("Files is empty"); } 


if(!empty($_REQUEST['folder'])) { 
	$backup_folder = $_REQUEST['folder'];
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
}


$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
if(!empty($_REQUEST['folder'])) { 
	$folder ="sy-misc/".$_REQUEST['folder'];
} else { 
	$folder ="sy-misc";
}
$dir = $setup['path']."/".$folder."";

$uploaded_raw=$_FILES['file']['name']; 
$FILENAME = date('Ymdhis')."-".strtolower($uploaded_raw);

$tempFile = $_FILES['file']['tmp_name'];
$destination = ($dir."/".$FILENAME."");

$test = copy($tempFile, $destination);
$size = getImageSize($destination);
unlink($tempfile);
if($_REQUEST['full_file_url'] == "1") { 
	$add_url = $setup['url'];
}
$array = array(
	'filelink' => ''.$add_url.$setup['temp_url_folder'].'/'.$folder.'/'.$FILENAME,
	'filename' => $FILENAME,
	"width" => $size[0],
	"height" => $size[1]
);

echo stripslashes(json_encode($array));
?>