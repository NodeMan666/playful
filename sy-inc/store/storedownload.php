<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
ini_set('upload_max_filesize', "400M");
ini_set('post_max_size', "400M");
ini_set('max_execution_time',8000);
set_time_limit(50);
ini_set('memory_limit', '1024M');
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}

function deletephotodownload() { 
	global $file_to_download,$setup,$delete_resize,$delete_amazon,$delete_amazon_file;
	$path = $setup['path']."/".$setup['photos_upload_folder'] ."";
	if($delete_resize == true) { 
		@unlink($file_to_download);
	}
	if($delete_amazon == true) { 
		@unlink($delete_amazon_file);
	}
}

function ResizeImage($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup) {
	$imagex = imagecreatefromjpeg("$imagex"); 
	$width = imagesx($imagex);
	$height = imagesy($imagex);
	if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
		if($maxwidth && $width > $maxwidth){
			$widthratio = $maxwidth/$width;
			$RESIZEWIDTH=true;
		}
		if($maxheight && $height > $maxheight){
			$heightratio = $maxheight/$height;
			$RESIZEHEIGHT=true;
		}
		if($RESIZEWIDTH && $RESIZEHEIGHT){
			if($widthratio < $heightratio){
				$ratio = $widthratio;
			}else{
				$ratio = $heightratio;
			}
		}elseif($RESIZEWIDTH){
			$ratio = $widthratio;
		}elseif($RESIZEHEIGHT){
			$ratio = $heightratio;
		}
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageJpeg ($newim,$name . "$image_ext", 93);
		ImageDestroy ($newim);
	}else{
		ImageJpeg ($imagex,$name . "$image_ext", 93);
	}
	ImageDestroy ($imagex);
}


if(!ctype_alnum($_REQUEST['syorder'])) { die("Sorry, something is wrong with the order numbers"); }
if(!ctype_alnum($_REQUEST['syok'])) { die("Sorry, something is wrong with the order key  numbers"); }
if(!ctype_alnum($_REQUEST['crtid'])) { die("Sorry, something is wrong with the order key  numbers"); }
if((!empty($_REQUEST['crtoptid']))&&(!ctype_alnum($_REQUEST['crtoptid'])) == true) { die(); } 

$order = doSQL("ms_orders", "*", "WHERE MD5(order_key)='".$_REQUEST['syok']."' AND MD5(order_id)='".$_REQUEST['syorder']."' ");
if(empty($order['order_id'])) {
	die("Unable to find order information");
}
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

$cart = doSQL(cart_table, "*", "WHERE MD5(cart_id)='".$_REQUEST['crtid']."' AND cart_order='".$order['order_id']."' ");
if(empty($cart['cart_id'])) {
	die("Unable to find item information");
}
if(!empty($_REQUEST['crtoptid'])) { 
	$cart_option = doSQL("ms_cart_options", "*", "WHERE MD5(co_id)='".$_REQUEST['crtoptid']."' AND co_cart_id='".$cart['cart_id']."' ");
	if(empty($cart_option['co_id'])) {
		die("Unable to find item information");
	}
}
if($cart['cart_store_product'] > 0) {
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_store_product']."' ");
	if($date['date_video_download'] > 0) { 
		$video = doSQL("ms_videos", "*", "WHERE vid_id='".$date['date_video_download']."' ");
		$file_to_download = $setup['path']."/".$setup['photos_upload_folder']."/".$video['vid_folder']."/".$video['vid_file'];
		$ext = substr($video['vid_file'], strrpos($video['vid_file'], '.') + 1);
	} else { 
		$file_to_download = $setup['path']."/".$setup['downloads_folder']."/".$date['prod_file'];
		$ext = substr($date['prod_file'], strrpos($date['prod_file'], '.') + 1);
	}
	if(!empty($date['prod_version'])) {
		$add_ver = "-".$date['prod_version'];
	}

	if(!empty($date['prod_dl_name'])) { 
		$filename = $date['prod_dl_name'].$add_ver.".".$ext;
	} else { 
		$filename = $date['date_title'].$add_ver.".".$ext;
	}
}

if($cart['cart_photo_prod'] > 0) {
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
	$filename = $pic['pic_org'];

	if((!empty($cart['cart_photo_bg']))&&(!empty($cart['cart_thumb'])) == true) { 
		$green_screen_cart = true;
		$pic_file_select = "pic_full";
		$bgphoto = doSQL("ms_photos","*", "WHERE pic_id='".$cart['cart_photo_bg']."' ");
		require($setup['path']."/sy-inc/gs-photos.php");
		$file_to_download = $image;
	} else { 

		if(empty($pic['pic_id'])) {
			die("Photo no longer exists");
		}
		if($cart_option['co_download'] == "1") { 
			$prod['pp_download_dem'] = $cart_option['co_download_size'];
		}
		$file_to_download = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];

		
		if($pic['pic_amazon'] == "1") { 
			if(ini_get('allow_url_fopen') <= 0) {
				copy_amazon_file("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
			} else {
				copy("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
			}
			$file_to_download = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org'];
			$delete_resize = true;
			$delete_amazon = true;
			$delete_amazon_file = $file_to_download;
		}	
		// exit();

		if($prod['pp_download_dem'] > 0) { 
			$new_name = $setup['path']."/".$setup['photos_upload_folder']."/".$prod['pp_download_dem']."-".$filename;
			ResizeImage($file_to_download,$prod['pp_download_dem'],$prod['pp_download_dem'],$new_name, $photo_setup, $setup);
			$file_to_download = $new_name;
			$delete_resize = true;
			$filename = $prod['pp_download_dem']."-".$pic['pic_org'];

		}
	}
}

@ob_end_clean();


// Set the filename based on the URL's query string
$theFile = $file_to_download;

// Get info about the file
$f = pathinfo($theFile);

// Check the extension against allowed file types

// Make sure the file exists
if (!file_exists($theFile)) exit;
if(($delete_resize == true)||($delete_amazon == true) == true) { 
	register_shutdown_function('deletephotodownload');
}
// Set headers
if((!empty($cart['cart_photo_bg']))&&(!empty($cart['cart_thumb'])) == true) { 
	$filename = str_replace(".png",".jpg",$filename);
	$filename = str_replace(".PNG",".jpg",$filename);
}
header("Pragma: public");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: private");
header("Content-Transfer-Encoding: binary");
// This line causes the browser's "save as" dialog
header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
// Length required for Internet Explorer
header("Content-Length: ".@urldecode(@filesize($theFile)));
if($cart['cart_photo_prod'] > 0) {
	header("Content-Type: image/jpeg"); 
} else { 
	if($ext == "zip") {
		header('Content-Type: application/octet-stream');
	} elseif($ext == "mp4") {
		header('Content-type: video/mp4');
	} elseif($ext == "pdf") {
		header("Content-type:application/pdf");	
	} elseif($ext == "jpg") {
		header("Content-Type: image/jpeg"); 
	} else {
		header("Content-Type: audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
	}
}

// Open file
if (($f = fopen($theFile, 'rb')) === false) exit;

// Push file
while (!feof($f)) {
    echo fread($f, (1*(1024*1024)));
    flush();
    @ob_flush();
}

// Close file
fclose($f);
$download_log = date('M d, Y h:i A')."|".getUserIP()."\r\n".$cart['cart_download_log'];
updateSQL(cart_table, "cart_download_date='".date('Y-m-d H:i:s')."',  cart_download_log='".$download_log."', cart_download_ip='".getUserIP()."'  WHERE cart_id='".$cart['cart_id']."'  ");

exit;

// $image = @readfile("$path/$gallery/".$pic_full);
?>
