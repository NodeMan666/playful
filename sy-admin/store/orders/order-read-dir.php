<?php
require "../../../sy-config.php";

if(empty($dir_name)) { 
	exit();
}

$dir_name = $setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$dir_name;

$dir = opendir($dir_name); 
$imageList = array();
while ($file = readdir($dir)) { 

	if (($file != ".") && ($file != "..")) { 
		$ext = strtolower(substr($file, -4));
			if(($ext == ".jpg") || ($ext == ".gif")) {
				$file_count++;
				array_push($imageList, $file);
				$file_list .= "<li>$file"; 
			}
		} 
	} 

@closedir($dir); 
asort($imageList);

?>