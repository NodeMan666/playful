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

$date = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_POST['did']."' AND MD5(date_date)='".$_POST['dd']."' AND MD5(date_expire)='".$_POST['de']."' ");

if(empty($date['date_id'])) {
	die("An error has occured");
}
$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free='1' AND pp_free_all='1' ");

if(!empty($_POST['sid'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE MD5(sub_id)='".$_POST['sid']."' ");
}

$zip_file_name = trim($site_setup['website_title']);
$zip_file_name = str_replace(' ', '_', $zip_file_name);
$zip_file_name = preg_replace('/[^0-9a-z_A-Z-]/', '', $zip_file_name); 
$zip_file_name .= "-".preg_replace('/[^0-9a-z_A-Z-]/', '',$date['date_title']);
if($_POST['zip_limit'] > 0) { 
	$zip_file_name .= "-".$_POST['zip_limit'];
}
$zip_file_name .= ".zip";

if(isset($_SESSION['pid'])) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}

if(!empty($date['date_id'])) { 
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

	if($_REQUEST['view'] == "favorites") { 
		$and_where = "";
		$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
		$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
		$pics_orderby = "pic_org";

	} else { 

		if(!empty($date['date_photo_keywords'])) { 
			$and_date_tag = "( ";
			$date_tags = explode(",",$date['date_photo_keywords']);
			foreach($date_tags AS $tag) { 
				$cx++;
				if($cx > 1) { 
					$and_date_tag .= " OR ";
				}
				$and_date_tag .=" key_key_id='$tag' ";
			}
			$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
			$and_date_tag .= " ) ";
			
			## NOT DONE NEW DATABASE FIELDS SELECTION ## 
			$pics_where = "WHERE $and_date_tag $and_where  ";
			$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic";
			$cx = 0;
		} else { 

			$and_where = getSearchString();
			if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
				// Is gallery owner
			} else { 
				$and_where .= " AND pic_hide!='1' ";
			}
			if(!empty($sub['sub_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_id']."' ");
				$and_sub = "AND bp_sub='".$sub['sub_id']."' ";
			} else { 
				if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
					$and_sub = "AND bp_sub='0' ";
				}
			}
			$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
			$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
		}
	}

	if($_POST['zip_limit'] > 0) { 
		$from = ($_POST['zip_limit'] * $zip_max) -$zip_max;
		$add_sql = "LIMIT ".$from.",".$zip_max." ";
	}
	$delete_array = array();

	$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  $add_sql ");
	while($pic = mysqli_fetch_array($pics))  { 
		if(!empty($pic['pic_id'])==true) { 
			if(!empty($_POST['gs-bgimage-id-free'])) { 

				$green_screen_cart = true;
				$pic_file_select = "pic_full";
				$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_POST['gs-bgimage-id-free']."' ");
				require($setup['path']."/sy-inc/gs-photos.php");
				$pic_file = $image;
				$delete_amazon = true;
				$delete_amazon_file = $pic_file;
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
				// print "<li>file exists"; 
				if(!empty($_REQUEST['dem'])) { 
					if($_REQUEST['dem'] == "org") { 
						$prod['pp_download_dem'] = 0;
					} else { 
						$prod['pp_download_dem'] = $_REQUEST['dem'];
					}
				}

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
					if(!empty($_POST['gs-bgimage-id-free'])) { 
						$pic['pic_org'] = str_replace(".png",".jpg",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".PNG",".jpg",$pic['pic_org']);
					}
					// print "<li>exists  ".$pic['pic_org'].": ".$pic_file;

					$zip->addFile($pic_file, $pic['pic_org']);
					$pic_count++;
					insertSQL("ms_free_downloads", "free_pic='".$pic['pic_id']."', free_date='".date('Y-m-d H:i:s')."', free_date_id='".$date['date_id']."', free_prod='".$prod['pp_id']."', free_person='".$person['p_id']."', free_ip='".getUserIP()."', free_zip='1'  ");

				}
			}
		}
	}

	$zip->close();
	// print "count: ".$pic_count." <a href=\"zips/".$zip_file_name."\">DOWNLOAD ZIP FILE ".$zip_file_name."</a>";
}
//exit();
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
