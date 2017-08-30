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
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
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

foreach($_POST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_POST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_POST[$id] = sql_safe("".$_POST[$id]."");
		}
	}
}
ini_set('upload_max_filesize', "400M");
ini_set('post_max_size', "400M");
ini_set('max_execution_time',8000);
set_time_limit(50);




// if((!empty($_POST['ci_id'])) AND (!is_numeric($_POST['ci_id'])) ==true) { die("an error has occurred"); }
// if((!empty($_POST['order_id'])) AND (!is_numeric($_POST['order_id'])) ==true) { die("an error has occurred"); }


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





function deletezip() {
	global $zip_file_name,$setup,$delete_array;
	$zip_path = $setup['path']."/".$setup['photos_upload_folder'] ."/zips";
	unlink($zip_path."/".$zip_file_name);
	foreach($delete_array AS $file) { 
		unlink($file);
	}
	//    echo 'Script executed with success', PHP_EOL;
}
$zip_path = $setup['path']."/".$setup['photos_upload_folder'] ."/zips";
if(!is_dir($zip_path)) { 
	mkdir($zip_path, 0755);
	chmod($zip_path, 0755);
	$fp = fopen($zip_path."/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);
}
$wm = doSQL("ms_watermarking", "*", "");
$order = doSQL("ms_orders", "*", "WHERE MD5(order_id)='".$_POST['order_id']."' ");
if(empty($order['order_id'])) {
	die("An error has occured");
}
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

$zip_file_name = trim($site_setup['website_title']);
$zip_file_name = str_replace(' ', '_', $zip_file_name);
$zip_file_name = preg_replace('/[^0-9a-z_A-Z-]/', '', $zip_file_name); 
$zip_file_name .= "-order-".$order['order_id'];
if($_POST['zip_limit'] > 0) { 
	$zip_file_name .= "-".$_POST['zip_limit'];
}
$zip_file_name .= ".zip";


if(!empty($order['order_id'])) { 
	$photo_setup = doSQL("ms_photo_setup", "zip_limit", "");
	$zip_max = $photo_setup['zip_limit'];
	if($zip_max <=0) { 
		$zip_max = 20;
	}

	$zip = new ZipArchive;
	if ($zip->open($zip_path."/".$zip_file_name, ZIPARCHIVE::CREATE)!==TRUE) {
		exit("cannot open <$filename>\n");
	}
	$delete_array = array();
	if($_POST['zip_limit'] > 0) { 
		$from = ($_POST['zip_limit'] * $zip_max) -$zip_max;
		$add_sql = "LIMIT ".$from.",".$zip_max." ";
	}

	$pics = whileSQL(cart_table." LEFT JOIN ms_photos ON ".cart_table.".cart_pic_id=ms_photos.pic_id", "*", "WHERE cart_order='".$order['order_id']."' AND cart_download='1' AND cart_pic_id>'0' ORDER BY pic_org ASC  $add_sql ");
	while($pic = mysqli_fetch_array($pics))  { 
		if(!empty($pic['pic_id'])==true) { 
			$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$pic['cart_photo_prod']."' ");

			if((!empty($pic['cart_photo_bg']))&&(!empty($pic['cart_thumb'])) == true) { 
				$green_screen_cart = true;
				$pic_file_select = "pic_full";
				$bgphoto = doSQL("ms_photos","*", "WHERE pic_id='".$pic['cart_photo_bg']."' ");
				require($setup['path']."/sy-inc/gs-photos.php");
				$pic_file = $image;
				array_push($delete_array,$pic_file);

			} else { 

				$pic_file = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];
				if($pic['pic_amazon'] == "1") { 
					if(ini_get('allow_url_fopen') <= 0) {
						copy_amazon_file("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
					} else {
						@copy("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org']);
					}
					$pic_file = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org'];
					$delete_amazon = true;
					$delete_amazon_file = $pic_file;
					array_push($delete_array,$pic_file);
				}	
			}

			if(file_exists($pic_file)) { 

				if($prod['pp_download_dem'] > 0) { 
					$new_name = $setup['path']."/".$setup['photos_upload_folder']."/".$prod['pp_download_dem']."-".$pic['pic_org'];
					ResizeImage($pic_file,$prod['pp_download_dem'],$prod['pp_download_dem'],$new_name, $photo_setup, $setup);
					$pic_file = $new_name;
					array_push($delete_array,$new_name);
					$delete_resize = true;
				}

				 if(($prod['pp_free_watermark']=="1")AND((!empty($wm['wm_images_file'])) || (!empty($prod['pp_watermark_file'])))==true) {
					 if($remove_watermark!=="1") {

						$size= GetImageSize($pic_file); 

						$iwidth=$size[0];
						$iheight=$size[1];

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
							$new = imagecreatefromjpeg($pic_file);

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
						 $wm_tmp = $setup['path']."/".$setup['photos_upload_folder']."/".date('ymdhi').$pic['pic_org'];
						array_push($delete_array,$wm_tmp);
		
						imageJPEG($new,$wm_tmp, 95);
						$pic_file = $wm_tmp;
						unset($new);
					 }
				 }

				 // Adding a logo

				if(($prod['pp_free_logo']=="1")AND((!empty($wm['wm_logo_file'])) || (!empty($prod['pp_logo_file'])))==true) {
					$size= GetImageSize($pic_file); 

					$iwidth=$size[0];
					$iheight=$size[1];
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
						$new = imagecreatefromjpeg($pic_file);

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
					 $logo_tmp = $setup['path']."/".$setup['photos_upload_folder']."/l-".date('ymdhi').$pic['pic_org'];
					array_push($delete_array,$logo_tmp);

					imageJPEG($new,$logo_tmp, 95);
					$pic_file = $logo_tmp;
					unset($new);

				 }




				if(file_exists($pic_file)) { 
					$download_log = "";
					if($green_screen_cart == true) { 
						$pic['pic_org'] = str_replace(".png",".jpg",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".PNG",".jpg",$pic['pic_org']);
					}
					$zip->addFile($pic_file, $pic['pic_org']);
					$pic_count++;

					$download_log = date('M d, Y h:i A')."|".getUserIP()." (ZIP)\r\n".$pic['cart_download_log'];
					updateSQL(cart_table, "cart_download_date='".date('Y-m-d H:i:s')."',  cart_download_log='".$download_log."', cart_download_ip='".getUserIP()."'  WHERE cart_id='".$pic['cart_id']."'  ");

				}
			}
			//print "<li>".$setup['path']."/".$setup['gallery_folder']."/".$pic_file;
		}
	}

	$zip->close();
	// print "<a href=\"zips/".$zip_file_name."\">DOWNLOAD ZIP FILE ".$zip_file_name."</a>";
}
if((!empty($zip_file_name))&&($pic_count > 0)==true) { 
	register_shutdown_function('deletezip');
	$file_to_download="".$zip_path."/".$zip_file_name.""; // the name the file has on the server (or an FTP or HTTP request)


	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$zip_file_name."\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($file_to_download));
	ob_end_flush();

	readfile($file_to_download);
} else { 
	print "Sorry, there has been an error";
}
?>
