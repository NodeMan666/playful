<?php
include "sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$pinfo = explode("|",$_REQUEST['thephoto']);
anString($pinfo[0]);
if(!empty($pinfo[1])) { 
	anString($pinfo[1]);
}
anString($pinfo[2]);

if(empty($pinfo[1])) { 
	$photo_file = "pic_pic";
}
if($pinfo[1] == MD5("pic_pic")) { 
	$photo_file = "pic_pic";
}
if($pinfo[1] == MD5("pic_large")) { 
	$photo_file = "pic_large";
}
if($pinfo[1] == MD5("pic_full")) { 
	$photo_file = "pic_full";
}
if($pinfo[1] == MD5("pic_med")) { 
	$photo_file = "pic_med";
}
if($pinfo[1] == MD5("pic_th")) { 
	$photo_file = "pic_th";
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

$pic = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p ')  AS pic_date_show , date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p')  AS pic_date_taken_show", "WHERE ms_photos.pic_key='".$pinfo[0]."' ");

if(empty($pic['pic_id'])) { die("Not Found");}
$cat = doSQL("ms_blog_categories", "*", "WHERE MD5(cat_id)='".$pinfo[2]."' ");


if($photo_file == "pic_th") {
	$remove_watermark = "1";
}


if($pic['pic_amazon'] == "1") { 
	copy("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic[$photo_file],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
	$theImagePath = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org'];
	$theImage = "/".$setup['photos_upload_folder']."/".$pic['pic_org'];
	$delete_resize = true;
	$delete_amazon = true;
	$delete_amazon_file = $theImagePath;
} else { 
	$pic_file_select = selectPhotoFile($photo_file,$pic);
	$theImage = "".$setup['gallery_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder'].""."/".$pic[$pic_file_select]."";
	$theImagePath = "".$setup['path']."/".$theImage."";

}



$size= @GetImageSize($theImagePath); 
$filename="th" . "_" . "".$pic['pic_id']."" . ".jpg";
$size= GetImageSize("$theImagePath"); 
$iwidth=$size[0];
$iheight=$size[1];
$ext = strtolower(substr($theImagePath, -4));

if($ext == ".png") {
	$im_src = imagecreatefrompng($theImagePath);
} else { 
	$im_src = imagecreatefromjpeg($theImagePath);
}	 

// $im_src = imagecreatefromjpeg($theImagePath);
$new = $im_src;
if(!empty($crop)) {
	$cropping = doSQL("pc_crops", "*", "WHERE MD5(crop_id)='$crop' ");
	if(!empty($cropping['crop_id'])) {
		$new = cropImage($cropping['x1'],$cropping['y1'],$cropping['width'], $cropping['height'], $size, $new);
	}

}




$wm = doSQL("ms_watermarking", "*", "");



 if(($cat['cat_watermark']=="1")AND(!empty($wm['wm_images_file']))AND($remove_watermark!=="1")==true) {
	$location = $wm['wm_images_location'];
	$overlay = $setup['path']."/".$wm['wm_images_file'];
	$w_offset = 0;
	$h_offset = 0;
	if(file_exists($overlay)) {
		$overlay = imagecreatefrompng($dir . $overlay);
		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);
		 
		 if($location == "tile") { 
			imagesettile($new, $overlay);
			imagefilledrectangle($new, -0, 0, $iwidth, $iheight, IMG_COLOR_TILED);
		 } elseif($location == "center") {
			// center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 } elseif($location == "bright") {
		   // bottom right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bottom") {
			// bottom center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bleft") {
			// bottom left
			imagecopy($new, $overlay, 0, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);

		 } elseif($location == "uright") {
		   // top right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "top") {
			// top center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "uleft") {
			// top left
			imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
		 } else {
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 }
		imagedestroy($overlay);
	}
 }

 if(($cat['cat_logo']=="1")AND(!empty($wm['wm_logo_file']))AND($remove_watermark!=="1")==true) {
	 $location = $wm['wm_add_logo_location'];
	$overlay =$setup['path']."/".$wm['wm_logo_file'];
	if(file_exists($overlay)) {
		$w_offset = 0;
		$h_offset = 0;
		$overlay = imagecreatefrompng($dir . $overlay);
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);
		 if($location == "center") {
			// center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 } elseif($location == "bright") {
		   // bottom right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bottom") {
			// bottom center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bleft") {
			// bottom left
			imagecopy($new, $overlay, 0, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);

		 } elseif($location == "uright") {
		   // top right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "top") {
			// top center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "uleft") {
			// top left
			imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
		 } else {
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 }
		imagedestroy($overlay);
	}
 }

$theimage= date('Ymdhis').".jpg";



if($pinfo[3] == "original") {
	unset($_SESSION['color']);
} elseif(!empty($pinfo['3'])) {
	$thiscolor = doSQL("ms_color_options", "*", "WHERE color_id='".$pinfo[3]."' ");
	if(!empty($thiscolor['color_id'])) { 

	//	$_SESSION['color'] = $thiscolor['color_id'];
		if($thiscolor['color_bw'] == "1") {
			$bw = 1;
		} else {
			$colorize = 1;
			$opct = $thiscolor['color_opc'];
			$r = $thiscolor['color_r'];
			$g = $thiscolor['color_g'];
			$b = $thiscolor['color_b'];
			$color_type="".$thiscolor['color_r']."-".$thiscolor['color_g']."-".$thiscolor['color_b']."-".$thiscolor['color_opc']."";
		}
	}
} elseif($_SESSION['color'] > 0) {
	$thiscolor = doSQL("ms_color_options", "*", "WHERE color_id='".$_SESSION['color']."' ");
	if(!empty($thiscolor['color_id'])) { 
		$_SESSION['color'] = $thiscolor['color_id'];
		if($thiscolor['color_bw'] == "1") {
			$bw = 1;
		} else {
			$colorize = 1;
				$opct = $thiscolor['color_opc'];
				$r = $thiscolor['color_r'];
				$g = $thiscolor['color_g'];
				$b = $thiscolor['color_b'];

			$color_type="".$thiscolor['color_r']."-".$thiscolor['color_g']."-".$thiscolor['color_b']."-".$thiscolor['color_opc']."";
		}
	}
}


mysqli_close($dbcon);


if($colorize == "1") {
	$bwf = bwImage($size, $new);	$theimage= "co_".date('Ymdhis').".jpg";

	$new = colorizeImageLayover($size, $bwf, $r, $g, $b,$opct);
}

if($bw == "1") {
	$new = bwImage($size, $new);
	$theimage= "bw_".date('Ymdhis').".jpg";
}

if($pinfo[1] == "z") {
//	unset($_SESSION['color']);
}

function cropImage($x1,$y1,$width, $height, $size, $im_src) {
	$new = imagecreatetruecolor($width,$height);
	$dest_x = $x1;
	$dest_y = $y1;
imagecopyresampled($new, $im_src, 0, 0, $x1, $y1, $width, $height, $width, $height);
	return $new;
}

function bwImage($size, $im_src) {
	$new = ImageCreate(imagesx($im_src),imagesy($im_src));
	$background = imagecolorallocate($new, 255,255,255);
	for ($c = 0; $c <= 255; $c++) {
		 ImageColorAllocate($new, $c,$c,$c);
	}
	ImageCopyMerge($new,$im_src,0,0,0,0, imagesx($im_src), imagesy($im_src),100);
	return $new;
}

function colorizeImageLayover($size, $bwf, $r, $g, $b,$opct) {
	$new = ImageCreate(imagesx($bwf),imagesy($bwf));
	imagecolorallocate($new, $r,$g,$b);
	ImageCopyMerge($new,$bwf,0,0,0,0, imagesx($bwf), imagesy($bwf),$opct);
	return $new;
}
if(($delete_resize == true)||($delete_amazon == true) == true) { 
	register_shutdown_function('deletephotodownload');
}

header("Content-transfer-encoding: binary\n"); 
header("Content-Type: image/jpeg"); 
header("Content-Disposition: filename=\"$theimage\" ");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: private");
ImageJpeg ($new,null, 90);
imagedestroy($new);
exit();
?>
