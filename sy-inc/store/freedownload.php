<?php 
require("../../sy-config.php");
session_start();
error_reporting(0);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ini_set('max_execution_time',8000);
set_time_limit(50);
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
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


if(!ctype_alnum($_REQUEST['fp'])) { die("Sorry, something is wrong with the order numbers"); }

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

$prod = doSQL("ms_photo_products", "*", "WHERE MD5(pp_id)='".$_REQUEST['fp']."' ");
$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['p']."' ");
$date = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['did']."' ");
if(empty($pic['pic_id'])) {
	die("Photo does not exists");
}
if(empty($prod['pp_id'])) {
	die("An error has occured");
}
if(isset($_SESSION['pid'])) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}
insertSQL("ms_free_downloads", "free_pic='".$pic['pic_id']."', free_date='".date('Y-m-d H:i:s')."', free_date_id='".$date['date_id']."', free_prod='".$prod['pp_id']."', free_person='".$person['p_id']."', free_ip='".getUserIP()."' ");

if(!empty($_REQUEST['gsbgphoto'])) { 
	$green_screen_cart = true;
	$pic_file_select = "pic_full";
	if(!ctype_alnum($_REQUEST['gsbgphoto'])) { die("an error has occurred [5]"); }
	$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_REQUEST['gsbgphoto']."' ");
	require($setup['path']."/sy-inc/gs-photos.php");
	$file_to_download = $image;
} else { 

	$file_to_download = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];
	// print $file_to_download;
	// exit();
	$filename = $pic['pic_org'];

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
}
if(!empty($_REQUEST['dem'])) { 
	if($_REQUEST['dem'] == "org") { 
		$prod['pp_download_dem'] = 0;
	} else { 
		$prod['pp_download_dem'] = $_REQUEST['dem'];
	}
}
if($prod['pp_download_dem'] > 0) { 
	$new = $setup['path']."/".$setup['photos_upload_folder']."/".$prod['pp_download_dem']."-".$filename;
	ResizeImage($file_to_download,$prod['pp_download_dem'],$prod['pp_download_dem'],$new, $photo_setup, $setup);
	$filename = $prod['pp_download_dem']."-".$pic['pic_org'];
	$file_to_download = $new;
	$delete_resize = true;
}
$size= GetImageSize($file_to_download); 

$theImage = $new;
$theImagePath = $file_to_download;
$iwidth=$size[0];
$iheight=$size[1];

// Changes start here ---------------------------------------------------------

// this is never referenced but I'll keep it here, just in case
$im_src = null;

// assume we will NOT need to use GD library
$new = null;

// Changes end here -----------------------------------------------------------

$wm = doSQL("ms_watermarking", "*", "");

if(($prod['pp_free_watermark']=="1")AND((!empty($wm['wm_images_file'])) || (!empty($prod['pp_watermark_file'])))==true) {
	 if($remove_watermark!=="1") {
		$location = $wm['wm_images_location'];
		$overlay = $setup['path']."/".$wm['wm_images_file'];

		if(!empty($prod['pp_watermark_file'])) { 
			$overlay = $setup['path']."/".$prod['pp_watermark_file'];
		}
		if(!empty($prod['pp_watermark_location'])) { 
			$location = $prod['pp_watermark_location'];
		}
		$w_offset = 0;
		$h_offset = 0;
		$overlay = imagecreatefrompng($dir . $overlay);
		 
		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);

// Changes start here ---------------------------------------------------------

        // if GD library has NOT been used yet ...
        if($new == null)

            // ... load the original image into GD
            $new = imagecreatefromjpeg($theImagePath);

// Changes end here -----------------------------------------------------------
		 if($location == "tile") { 
			imagesettile($new, $overlay);
			imagefilledrectangle($new, -0, 0, $iwidth, $iheight, IMG_COLOR_TILED);
		 } elseif($location == "center") {
			// center
			imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), ($iheight / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 } elseif($location == "bright") {
		   // bottom right
			imagecopy($new, $overlay, $iwidth- $owidth - $w_offset, $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bottom") {
			// bottom center
			imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bleft") {
			// bottom left
			imagecopy($new, $overlay, 0, $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);

		 } elseif($location == "uright") {
		   // top right
			imagecopy($new, $overlay, $iwidth- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "top") {
			// top center
			imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "uleft") {
			// top left
			imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
		 } else {
			imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), ($iheight / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 }
		imagedestroy($overlay);
	 }
 }

if(($prod['pp_free_logo']=="1")AND((!empty($wm['wm_logo_file'])) || (!empty($prod['pp_logo_file'])))==true) {
	 $location = $wm['wm_add_logo_location'];
	$overlay =$setup['path']."/".$wm['wm_logo_file'];

	if(!empty($prod['pp_logo_file'])) { 
		$overlay = $setup['path']."/".$prod['pp_logo_file'];
	}
	if(!empty($prod['pp_logo_location'])) { 
		$location = $prod['pp_logo_location'];
	}

	$w_offset = 0;
	$h_offset = 0;
	$overlay = imagecreatefrompng($dir . $overlay);
	$owidth = imagesx($overlay);
	$oheight = imagesy($overlay);

// Changes start here ---------------------------------------------------------

    // ... if GD library has NOT been used yet ...
    if($new == null)

        // ... load the original image into GD
        $new = imagecreatefromjpeg($theImagePath);

// Changes end here -----------------------------------------------------------

	 if($location == "center") {
		// center
		imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), ($iheight / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
	 } elseif($location == "bright") {
	   // bottom right
		imagecopy($new, $overlay, $iwidth- $owidth - $w_offset, $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);
	 } elseif($location == "bottom") {
		// bottom center
		imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);
	 } elseif($location == "bleft") {
		// bottom left
		imagecopy($new, $overlay, 0, $iheight - $oheight - $h_offset, 0, 0, $owidth, $oheight);

	 } elseif($location == "uright") {
	   // top right
		imagecopy($new, $overlay, $iwidth- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
	 } elseif($location == "top") {
		// top center
		imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
	 } elseif($location == "uleft") {
		// top left
		imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
	 } else {
		imagecopy($new, $overlay, ($iwidth / 2) - (($owidth - $w_offset) / 2), ($iheight / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
	 }
	imagedestroy($overlay);
 }

$theimage= "".$pic['pic_org']."";
if(!empty($_REQUEST['gsbgphoto'])) { 
	$theimage = str_replace(".png",".jpg",$theimage);
	$theimage = str_replace(".PNG",".jpg",$theimage);
}
if(($delete_resize == true)||($delete_amazon == true) == true) { 
	register_shutdown_function('deletephotodownload');
}
header("Content-Type: application/octet-stream");
header("Content-transfer-encoding: binary\n"); 
header("Content-Type: image/jpeg"); 
header("Content-Disposition: attachment; filename=\"$theimage\" ");
header('Cache-control: no-cache');
// header("Content-Length: ".@urldecode(@filesize($new)));

// Changes start here ---------------------------------------------------------
// if GD library has been used ...
if($new != null) {
    // ... write image to temp file (MUST use a file to preserve the DPI!)
    $tmp = @tempnam("/tmp", "FREE-");
	if(empty($tmp)) { 
	    imageJPEG($new, NULL, 95);
	} else { 
	    imageJPEG($new, $tmp, 95);
    }
    imageDestroy($new);

    // set the original DPI into the copy
    imageSetDPI($tmp, imageGetDPI($theImagePath));
	header("Content-Length: ".@urldecode(@filesize($tmp)));

    // return the copy
    @readfile($tmp);

    // discard the temp file
    @unlink($tmp);
}

// ... otherwise, just return the original (uses NO GD so faster, no memory use)
else
	header("Content-Length: ".@urldecode(@filesize($theImagePath)));

    @readfile($theImagePath);

// Changes end here -----------------------------------------------------------

exit();
?>
