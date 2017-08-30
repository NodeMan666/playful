<?php
include "../../sy-config.php";
session_start();
header("Cache-control: private"); 
header('Content-Type: text/html; charset=utf-8');
if($setup['ob_start_only'] == true) { 
	ob_start();  
} else { 
	if ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ) {  
		ob_start( "ob_gzhandler" );  
	}  
	else {  
		ob_start();  
	}  
}
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
require $setup['path']."/".$setup['inc_folder']."/show/show-functions.php";


$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
} elseif($parent_permissions == "0777") {
	$perms = 0777;
} else {
	$perms = 0755;
}




if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos")) { 
	mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos", $perms);
	chmod("".$setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos", $perms);
	$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);

}

$filename = "image-".uniqid().".jpg";
$img = $_POST['img'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace('data:image/jpg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $setup['path']."/sy-photos/customer-room-photos/".$filename;
$success = file_put_contents($file, $data);
$size = GetImageSize($file); 
if(!empty($_POST['pid'])) { 
	$p = doSQL("ms_people","*","WHERE MD5(p_id)='".$_POST['pid']."' ");
	if($p['p_id'] > 0) { 
		insertSQL("ms_wall_rooms","room_small='/sy-photos/customer-room-photos/".$filename."', room_large='/sy-photos/customer-room-photos/".$filename."', room_photo_width='".$size[0]."', room_photo_height='".$size[1]."', room_center='.500', room_base='.500', room_person='".$p['p_id']."' ");
	}
}
print "/sy-photos/customer-room-photos/".$filename."|".$size[0]."|".$size[1];
// print $success ? $filename : 'Unable to save the file.';
	$_SESSION['new_room_photo'] = $filename;
//	header("location: /room-view/");
//	session_write_close();
	exit();

?>