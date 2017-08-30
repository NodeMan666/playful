<?php
if($green_screen_cart !== true) { 
	require("../sy-config.php");
	session_start();
	error_reporting(E_ALL ^ E_NOTICE);
	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: text/html; charset=utf-8');
	ob_start(); 
	require $setup['path']."/".$setup['inc_folder']."/functions.php";
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
	require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
	$dbcon = dbConnect($setup);
	$site_setup = doSQL("ms_settings", "*", "");
	$store = doSQL("ms_store_settings", "*", "");
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");

	date_default_timezone_set(''.$site_setup['time_zone'].'');

	foreach($_REQUEST AS $id => $value) {
		if(!empty($value)) { 
			if(!is_array($value)) { 
				$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
				$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
			}
		}
	}


	if(!empty($_REQUEST['cart_id'])) { 
		if(!is_numeric($_REQUEST['cart_id'])) { die(); } 
		$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$_REQUEST['cart_id']."' ");
	}
	if(!ctype_alnum($_REQUEST['bg_photo'])) { die(); }
	if(!ctype_alnum($_REQUEST['photo'])) { die(); }

	$bg_pic = doSQL("ms_photos", "*", "WHERE pic_id='".$bgphoto['pic_id']."' ");
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['photo']."' ");
} else { 
	$bg_pic = doSQL("ms_photos", "*", "WHERE pic_id='".$bgphoto['pic_id']."' ");
	// $pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['photo']."' ");
}

if(!empty($pic_file_select)) { 
	$bg_file = $pic_file_select;
	$over_file = $pic_file_select;
} else { 
	$bg_file = "pic_pic";
	$over_file = "pic_th";
}
if($bg_pic['pic_amazon'] == "1") { 
	copy("https://".$site_setup['amazon_endpoint']."/".$bg_pic['pic_bucket']."/".$bg_pic['pic_bucket_folder']."/".$bg_pic[$bg_file],$setup['path']."/".$setup['photos_upload_folder']."/".$bg_pic['pic_org']);
	$background = $setup['path']."/".$setup['photos_upload_folder']."/".$bg_pic['pic_org'];
	$delete_resize = true;
	$delete_amazon = true;
	$delete_amazon_bg_file = $background;
} else { 
	$background = $setup['path']."/".$setup['photos_upload_folder']."/".$bg_pic['pic_folder']."/".$bg_pic[$bg_file];
}
if($pic['pic_amazon'] == "1") { 
	copy("https://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic[$over_file],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
	$image = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org'];
	$delete_resize = true;
	$delete_amazon = true;
	$delete_amazon_file = $image;
} else { 
	$image = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$over_file];
}

$overlay_image = imagecreatefrompng($image);
// Get the size of overlay
$overlay_width = imagesx($overlay_image);
$overlay_height = imagesy($overlay_image);

$size= GetImageSize($background); 
//$size = getimagefiledems($background,'pic_pic');

$background_width=$size[0];
$background_height=$size[1];
$w_offset = 0;
$h_offset = 0;



$ext = strtolower(substr($background, -4));
if($ext == ".png") {
	$new_background = imagecreatefrompng($background);
} else { 
	$new_background = imagecreatefromjpeg($background);
}	 
$left = 0;
$top = 0;
$crop_width = $overlay_width;
$crop_height = $overlay_height;
$canvas = imagecreatetruecolor($crop_width, $crop_height); // size of the png photo

$resize_percent = $overlay_width / $background_width;
$new_width = $background_width * $resize_percent;
$new_height = $background_height * $resize_percent;
if($new_height < $overlay_height) { 
	$new_height = $overlay_height;
	$perc = $new_height / $background_height;
	$new_width = $background_width * $perc;
//	print $new_width." x ".$new_height;
//	exit();
}
$left = ($new_width - $overlay_width) / 2;


$bg = imagecreatetruecolor($new_width, $new_height); // for the background photo

$current_image = imagecreatefromjpeg($background);

imagecopyresized($bg, $current_image, 0, 0, 0, 0, $new_width, $new_height, $background_width, $background_height);

imagecopy($canvas, $bg, 0, 0, $left, $top, $new_width, $new_height);

imagecopy($canvas, $overlay_image, ($overlay_width / 2) - (($overlay_width - $w_offset) / 2), ($overlay_height / 2)- (($overlay_height - $h_offset)/2), 0, 0, $overlay_width, $overlay_height);


$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
} elseif($parent_permissions == "0777") {
	$perms = 0777;
} else {
	$perms = 0755;
}

if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs")) { 
	mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs", $perms);
	chmod("".$setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs", $perms);
	$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);
}
$today = date('Y-m-d');

if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs/".$today."")) { 
	mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs/".$today."", $perms);
	chmod("".$setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs/".$today."", $perms);
	$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/gs-thumbs/".$today."/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);
}

$new_name = $setup['photos_upload_folder']."/gs-thumbs/".$today."/".$cart_id."-".$pic['pic_key']."-".$bg_pic['pic_key'].".jpg";
$image = $setup['path']."/".$new_name;


if($ext == ".png") {
	imagealphablending($canvas, false);
	imagesavealpha($canvas, true);
	Imagepng($canvas,$image);
} else { 
	ImageJpeg ($canvas,$image, 90);
}
if(!empty($delete_amazon_bg_file)) { 
	unlink($delete_amazon_bg_file);
}
if(!empty($delete_amazon_file)) { 
	unlink($delete_amazon_file);
}

imagedestroy($canvas);
if($green_screen_cart == true) {
	if($cart_id > 0)  { 
		updateSQL("ms_cart", "cart_thumb='".$new_name."' WHERE cart_id='".$cart_id."' ");
	}
} else { 
	header("Content-transfer-encoding: binary\n"); 
	header("Content-Type: image/jpeg"); 
	header("Content-Disposition: filename=\"$theimage\" ");
	header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: private");
	ImageJpeg ($canvas,NULL, 93);
	imagedestroy($canvas);
	exit();
}
?>