<?php 
if(empty($path)) { 
	$path = "../";
}
include $path."sy-config.php";
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
ini_set('upload_max_filesize', "400M");
ini_set('post_max_size', "400M");
ini_set('max_execution_time',8000);
set_time_limit(100);
require $path."".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require "admin.icons.php";
require "photos.functions.php";
require "photo.process.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$ipinfo = doSQL("ms_iptc", "*", "");
$processTime = $photo_setup['ftp_process']; 
$restTime = $photo_setup['ftp_rest'];
$discard_small = 0;
$smallest = 700;
$check_rotate = $_REQUEST['check_rotate'];

if($restTime <=0) { 
	$restTime = 8;
}
if($processTime <=0) { 
	$processTime = 10;
}

adminsessionCheck();



?>
<html>
<HEAD>
<TITLE>Processing Photos - Please Wait</TITLE>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<meta http-equiv="refresh" content="22"> 
<link rel="stylesheet" href="css/white.css" type="text/css">
</HEAD>
<BODY bgcolor=#ffffff marginwidth=2 marginheight=0 topmargin=0 leftmargin=2>
<div style="padding: 8px;" align=center>
<h1>Processing photos. Do not leave this window.</h1> 
</div>

<?php 
if(!isset($_SESSION['ftpStartTime'])) {
	$_SESSION['ftpStartTime'] = time();
}
if(!isset($_SESSION['upload_session'])) { 
	$_SESSION['upload_session'] = date('Y-m-d_H-i-s');
}
$_REQUEST['folder'] = rawurldecode(stripslashes(stripslashes($_REQUEST['folder'])));

$_REQUEST['wm_images_file'] = rawurldecode(stripslashes(stripslashes($_REQUEST['wm_images_file'])));
$_REQUEST['wm_logo_file'] = rawurldecode(stripslashes(stripslashes($_REQUEST['wm_logo_file'])));

if(!empty($_REQUEST['folder'])) { 
	if(!empty($_REQUEST['sub_folder'])) { 
		$dir_name = $setup['path']."/sy-upload/".$_REQUEST['folder']."".rawurldecode(stripslashes(stripslashes($_REQUEST['sub_folder'])))."";	
	} else { 
		$dir_name = $setup['path']."/sy-upload/".$_REQUEST['folder']."";	
	}
} else { 
	$dir_name = $setup['path']."/sy-upload";
}
$x = 1;

$dir = opendir($dir_name); 
$imageList = array();
while ($file = readdir($dir)) { 

	if (($file != ".") && ($file != "..")) { 

		if(is_dir($dir_name."/$file")) {
			$dir_list .= "<li>$file ";
			if(file_exists($dir_name."/$file/password.txt")) { 
				$pass = file_get_contents($dir_name."/$file/password.txt");
			}
			if(file_exists($dir_name."/$file/access.lddi")) { 
				$pass = file_get_contents($dir_name."/$file/access.lddi");
			}

			header("location: process-ftp-sub-folder.php?folder=".rawurlencode(stripslashes($_REQUEST['folder']))."&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."&discard_dups=".$_REQUEST['discard_dups']."&discard_original=".$_REQUEST['discard_original']."&no_meta=".$_REQUEST['no_meta']."&check_rotate=".$_REQUEST['check_rotate']."&watermark_photos=".$_REQUEST['watermark_photos']."&logo_photos=".$_REQUEST['logo_photos']."&wm_images_file=".rawurlencode($_REQUEST['wm_images_file'])."&wm_logo_file=".rawurlencode($_REQUEST['wm_logo_file'])."&wm_images_location=".$_REQUEST['wm_images_location']."&wm_add_logo_location=".$_REQUEST['wm_add_logo_location']."&sub_pass=".rawurlencode($pass)."&sub_folder=".rawurlencode(stripslashes($_REQUEST['sub_folder']))."/".rawurlencode($file)."");
			session_write_close();
			exit();

			if($scount<=0) {
				$dir_array_list .="$file";
			} else {
				$dir_array_list .=",$file";
			}
			$scount++;
		}


		$ext = strtolower(substr($file, -4));
			if(($ext == ".jpg") || ($ext == ".gif") || ($ext == ".png") || ($ext == ".jpeg")) {
				$file_count++;
				array_push($imageList, $file);
				$file_list .= "<li>$file"; 
			}
		} 
	} 

@closedir($dir); 
asort($imageList);
$start_time = time();


print "<div align=center>"; 
foreach($imageList AS $file) {

	$pic_folder = createPhotoFolder();

	$add_hash = substr(md5(date('ymdHis').rand()),0,15);
	$add_hash_small = substr(md5(date('ymdHis').rand()),0,6);
	$add_hash_med = substr(md5(date('ymdHis').rand()),0,12);
	$add_hash_large = substr(md5(date('ymdHis').rand()),0,9);
	$add_hash_th = substr(md5(date('ymdHis').rand()),0,3);

	$small_prefix = "small_" .$add_hash_small;
	$large_prefix = "large_" .$add_hash_large;
	$org_prefix = "original_" .$add_hash;
	$mini_prefix = "mini_".$add_hash_th;
	$th_prefix = "th_".$add_hash_th;

	$filename = cleanUploadFileName($file);

	$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$mini_prefix."_" . $filename;
	$thumb_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename;
	$small_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$small_prefix."_" . $filename;
	$med_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$med_prefix."_" . $filename;
	$large_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/" .$large_prefix."_". $filename;
	$full_name =  $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$org_prefix."_". $filename;

	$large_width = $photo_setup['blog_large_width'];
	$large_height = $photo_setup['blog_large_height'];
	$small_width = $photo_setup['blog_width'];
	$small_height = $photo_setup['blog_height'];
	$thumb_width = $photo_setup['blog_th_width'];
	$thumb_height = $photo_setup['blog_th_height'];
	$mini_size = $photo_setup['mini_size'];

	$targetfilepath = $dir_name."/".$file;
	$size_original = @GetImageSize($targetfilepath,$info); 
	$discard_dup = false;
	$discard_dup = checkfordup($filename,$_REQUEST['date_id'],$_REQUEST['sub_id']);

	if(((($size_original[0] < $smallest)&&($size_original[1] < $smallest))&&($discard_small == 1))||($discard_dup == true)==true) { 
		$_SESSION['total_discard']++;
		unlink($targetfilepath);
	} else { 
	$_SESSION['imagesProcessed']++;

		// Copy original upload

	if(function_exists(exif_read_data)) { 
		$exif = @exif_read_data($targetfilepath);
	}
	$r = 0;
	if($check_rotate == 1) { 
		if (!empty($exif['Orientation'])) {
			if($exif['Orientation'] == "3") { 
				$source = imagecreatefromjpeg($targetfilepath) ;
				$image = imagerotate($source, 180, 0);
				imagejpeg($image,$full_name,93);
				imagedestroy($source);
				$r = 1;

			}
			if($exif['Orientation'] == "6") { 
				
				$source = imagecreatefromjpeg($targetfilepath) ;
				$image = imagerotate($source, -90, 0);
				imagejpeg($image,$full_name,93);
				imagedestroy($source);

				$r = 1;
			
			}   

			if($exif['Orientation'] == "8") { 
				$source = imagecreatefromjpeg($targetfilepath) ;
					$image = imagerotate($source, 90, 0);
					imagejpeg($image,$full_name,93);
					imagedestroy($source);

					$r = 1;
				}    
		}
	}
	if($r == 1) { 
		// print "<li>".$exif['Orientation'];
		 // print "<img style=\"margin: 6px; width: 200px; height: auto;\" src=\"/".$setup['photos_upload_folder']."/".$pic_folder."/".$org_prefix."_" . $filename."\"> ";
		// exit();
	}
		if($r !== 1) { 
			//die("here - ".$full_name);
			copy($targetfilepath,$full_name);
		}
		$ext = strtolower(substr($file, -4));

		if($ext == ".gif") {
			$th_prefix= "original_".$add_hash;
			$small_prefix= "original_".$add_hash;
			$mini_prefix= "original_".$add_hash;
			$add_large= "original_".$add_hash."_".$filename;
		} elseif($ext == ".png") { 
			$old_file = $full_name;
			// Large Photo
			processPhoto($old_file,$size_original,$large_name,$large_width,$large_height,$_REQUEST['watermark_photos'],$_REQUEST['logo_photos'],0,$photo_setup['resize_quality'],false,true);
			$add_large = $large_prefix."_". $filename;

			// Small Photo
			processPhoto($large_name,$size_original,$small_name,$small_width,$small_height,0,0,0,92,false,true);

			// Thumbnail
			processPhoto($small_name,$size_original,$thumb_name,$thumb_width,$thumb_height,0,0,0,92,false,true);

			// Mini
			processPhoto($thumb_name,$size_original,$mini_name,$mini_size,$mini_size,0,0,1,92,false,true);
		} else {  

			$old_file = $full_name;
			// Large Photo
			processPhoto($old_file,$size_original,$large_name,$large_width,$large_height,$_REQUEST['watermark_photos'],$_REQUEST['logo_photos'],0,$photo_setup['resize_quality'],false,false);
			$add_large = $large_prefix."_". $filename;

			// Small Photo
			processPhoto($large_name,$size_original,$small_name,$small_width,$small_height,0,0,0,92,false,false);

			// Thumbnail
			processPhoto($small_name,$size_original,$thumb_name,$thumb_width,$thumb_height,0,0,0,92,false,false);

			// Mini
			processPhoto($thumb_name,$size_original,$mini_name,$mini_size,$mini_size,0,0,1,92,false,false);
		}
		$key = MD5(date('ymdHis').rand().makesalt());
		$fsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$add_large.""); 
		$ssize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$small_prefix."_" . $filename.""); 


		$thsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename.""); 



		$pic_filesize= @FileSize($full_name); 
		$pic_filesize_large= @FileSize($large_name); 
		$pic_filesize_small= @FileSize($small_name); 
		$pic_filesize_thumb= @FileSize($thumb_name); 

		if($_REQUEST['discard_original'] == "1") { 

		} else { 
			$pic_full_name = $org_prefix."_". $filename;
		}


		$pic_id = insertSQL("ms_photos", "
		pic_pic='".addslashes(stripslashes(trim($small_prefix."_" . $filename)))."', 
		pic_th='".addslashes(stripslashes(trim($th_prefix."_" . $filename)))."', 
		pic_mini='".addslashes(stripslashes(trim($mini_prefix."_" . $filename)))."',
		pic_full='".addslashes(stripslashes(trim($pic_full_name)))."', 
		pic_org='".addslashes(stripslashes(trim($filename)))."', 
		pic_large='".addslashes(stripslashes(trim($add_large)))."', 
		pic_date='".date('Y-m-d H:i:s')."',
		pic_upload_session='".$_SESSION['upload_session']."',
		pic_folder='".$pic_folder."', 
		pic_width='".$size_original[0]."', 
		pic_height='".$size_original[1]."', 
		pic_large_width='".$fsize[0]."', 
		pic_large_height='".$fsize[1]."', 
		pic_small_width='".$ssize[0]."', 
		pic_small_height='".$ssize[1]."', 
		pic_th_width='".$thsize[0]."', 
		pic_th_height='".$thsize[1]."', 
		pic_filesize='".$pic_filesize."', 
		pic_filesize_large='".$pic_filesize_large."', 
		pic_filesize_small='".$pic_filesize_small."', 
		pic_filesize_thumb='".$pic_filesize_thumb."',


		pic_no_dis='$no_dis' , 
		pic_client='".$_REQUEST['pic_client']."', 
		pic_key='$key' ");

		// Get EXIF / IPTC Data & Keywords
		if($_REQUEST['no_meta'] !== "1") { 
			$ipinfo = doSQL("ms_iptc", "*", "");
			$photo_info = getphotoinfo($full_name,$info,$pic_id);
			updateSQL("ms_photos", "pic_title='".addslashes(stripslashes(trim($photo_info['title'])))."'  , 
			pic_text='".addslashes(stripslashes(trim($photo_info['text'])))."' , 
			pic_keywords='".addslashes(stripslashes(trim($photo_info['tags'])))."' , 
			pic_date_taken='".addslashes(stripslashes(trim($photo_info['date_taken'])))."' ,
			pic_camera='".addslashes(stripslashes(trim($photo_info['camera_make'])))."' , 
			pic_camera_model='".addslashes(stripslashes(trim($photo_info['camera_model'])))."' 
			WHERE pic_id='".$pic_id."' ");
		}

		if(!empty($_REQUEST['date_id'])) { 
			$last = doSQL("ms_blog_photos", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' ORDER BY bp_order DESC");
			$order = $last['bp_order']+2;
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_blog='".$_REQUEST['date_id']."', bp_order='$order', bp_sub='".$_REQUEST['sub_id']."' ");
		}


		if(file_exists($mini_name)) { 
			chmod($mini_name,0644);
		}
		if(file_exists($thumb_name)) { 
			chmod($thumb_name,0644);
		}
		if(file_exists($small_name)) { 
			chmod($small_name,0644);
		}

		if(file_exists($med_name)) { 
			if(!is_dir($med_name)) { 
				chmod($med_name,0644);
			}
		}
		if(file_exists($large_name)) { 
			if(!is_dir($large_name)) { 
				chmod($large_name,0644);
			}
		}
		if(file_exists($full_name)) { 
			if(!is_dir($full_name)) { 
				chmod($full_name,0644);
			}
		}

		unlink($targetfilepath);

	}
	if($_REQUEST['discard_original'] == "1") { 
		@unlink($full_name);
	}

	if($photo_setup['upload_amazon'] == 1) { 
		$s3error = false;
		print "<div style=\"display: none;\">";
		if(file_exists($mini_name)) { 
			$uploadFile = $mini_name;
			$folderName = $_SESSION['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		if(file_exists($thumb_name)) { 
			$uploadFile = $thumb_name;
			$folderName = $_SESSION['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		if(file_exists($small_name)) { 
			$uploadFile = $small_name;
			$folderName = $_SESSION['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}

		if(file_exists($large_name)) { 
			$uploadFile = $large_name;
			$folderName = $_SESSION['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		if(file_exists($full_name)) { 
			$uploadFile = $full_name;
			$folderName = $_SESSION['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		print "</div>";
		if($s3error !== true) { 
			if(file_exists($mini_name)) { unlink($mini_name); } 
			if(file_exists($thumb_name)) { unlink($thumb_name); } 
			if(file_exists($small_name)) { unlink($small_name); } 
			if(file_exists($large_name)) { unlink($large_name); } 
			if(file_exists($full_name)) { unlink($full_name); } 
			updateSQL("ms_photos", "pic_amazon='1', pic_bucket='".addslashes(stripslashes(trim($photo_setup['awsBucketName'])))."', pic_bucket_folder='".$folderName."' WHERE pic_id='".$pic_id."' ");
		}

	
	}



	if($discard_dup !== true) { 
		if($_REQUEST['subProcess'] == "yes") {
			print "<div>Processed in Gallery <B>".$gallery['gal_title']."</B>: ".$setup['url']."/$gal_folder/".  "$image_use_name</div>";
		} else {
			if(file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename)) { 
				print "<img style=\"margin: 6px\" src=\"".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename."\"> ";
			}
		}
	}
	flush();
	if((time() - $start_time) >= $processTime) {
		flush();
		ob_flush();
		print "<div align=center>Photos processed: ".$_SESSION['imagesProcessed'].". Still working but pausing for $restTime seconds.  Please wait ....</div>";
		flush();
		print "<meta http-equiv=\"refresh\" content=\"$restTime\"> ";
		exit();
		flush();
		ob_flush();
		exit();
	}
	flush();
	ob_flush();
	}
	flush();
	ob_flush();

	print "<div>";
	if($_REQUEST['subProcess'] == "yes") {
		exit();

	} else {
		$subcount = $_SESSION['subgalleryCount'];
		if(!empty($subcount)) {
			$subcount_message = "$subcount subgalleries created. ";
		}
		$processed = $_SESSION['imagesProcessed'];
		$duration = time() - $_SESSION['ftpStartTime'];
		unset($_SESSION['ftp_message']);
		unset($_SESSION['ftpStartTime']);
		unset($_SESSION['subgalleryCount']);
		if($duration >=60) {
			$tm = floor($duration / 60);
			$scs = $duration  - ($tm * 60);
			$timemessage = " in $tm minutes $scs seconds"; 
		} else {
			$timemessage = "in $duration seconds"; 
		}
		if(!empty($subcount)) {
			$subcount_message = "$subcount subgalleries created. ";
		}
		$_SESSION['sm'] = "$subcount_message $processed photos processed $timemessage and have been added to the gallery";
		if($_SESSION['total_discard'] > 0) { 
			$_SESSION['sm'] .= " and ".$_SESSION['total_discard']." discarded";
			unset($_SESSION['total_discard']);
		}

	if(file_exists($dir_name."/passwords.csv")) { 

		print "<h2>Processing Passwords</h2>";
		$handle = fopen($dir_name."/passwords.csv", "r");

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";
			$row++;
			for ($c=0; $c < $num; $c++) {
				if($row <= 2) {
					if(strtolower(trim($data[$c])) == "folder") {
						$folderCol = $c;
					}
					if(strtolower(trim($data[$c])) == "password") {
						$passwordCol = $c;
					}
				} 
				// echo $data[$c] . "<br />\n";

			}
				if($row > 1) {
					if((!empty($data[$folderCol]))&(!empty($data[$passwordCol]))==true) {
						// print "<li>folder: ".$data[$folderCol]." - pass: ".$data[$passwordCol].""; 
						$upsub['sub_id'] = "";
						$ups = explode("/",$data[$folderCol]);
						$tf = count($ups) - 1;
						if(!empty($ups[1])) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$_REQUEST['date_id']."' AND sub_name='".addslashes(stripslashes(trim($ups[0])))."' ");
							// print "<h2>".$upsub['sub_name']."</h2>";
							$and_up = " AND sub_under='".$upsub['sub_id']."'  AND sub_name='".addslashes(stripslashes(trim($ups[$tf])))."'";
						} else { 
							$and_up = "AND sub_name='".addslashes(stripslashes(trim($data[$folderCol])))."'";
						}
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$_REQUEST['date_id']."' $and_up  ");
						if(!empty($sub['sub_id'])) {
							updateSQL("ms_sub_galleries", "sub_pass='".addslashes(stripslashes(trim($data[$passwordCol])))."' WHERE sub_id='".$sub['sub_id']."'  "); 
						}
						print "</tr>";
						$totalAdded++;
				}
			}
		}
			fclose($handle);
	//	exit();
		unlink($dir_name."/passwords.csv");
		}


		if(!empty($_REQUEST['folder'])) {
			$dir = opendir($dir_name); 
			while ($file = readdir($dir)) { 
				if (($file != ".") && ($file != "..")) { 
					unlink($dir_name."/".$file);	
				}
			}
			rmdir($dir_name);
		}

		if(!empty($_REQUEST['sub_folder'])) { 

			$pos = strripos($_REQUEST['sub_folder'],"/");
			if ($pos === false) {

			} else {
				$sub_folder = substr($_REQUEST['sub_folder'], 0, $pos);
			}
			if(!empty($_REQUEST['sub_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
			}
			print "<meta http-equiv=\"refresh\" content=\"0;url=process-ftp-upload.php?folder=".rawurlencode($_REQUEST['folder'])."&date_id=".$_REQUEST['date_id']."&discard_dups=".$_REQUEST['discard_dups']."&check_rotate=".$_REQUEST['check_rotate']."&discard_original=".$_REQUEST['discard_original']."&no_meta=".$_REQUEST['no_meta']."&watermark_photos=".$_REQUEST['watermark_photos']."&logo_photos=".$_REQUEST['logo_photos']."&wm_images_file=".rawurlencode($_REQUEST['wm_images_file'])."&wm_logo_file=".rawurlencode($_REQUEST['wm_logo_file'])."&wm_images_location=".$_REQUEST['wm_images_location']."&wm_add_logo_location=".$_REQUEST['wm_add_logo_location']."&sub_folder=".rawurlencode($sub_folder)."&sub_id=".$sub['sub_under']."\"> ";


		} else {  
			unset($_SESSION['imagesProcessed']);
			$pic_upload_session = $_SESSION['upload_session'];
			unset($_SESSION['upload_session']);
			if(!empty($_REQUEST['date_id'])) { 
				print "<meta http-equiv=\"refresh\" content=\"0;url=index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."&duration=$duration\"> ";
			} else { 
				print "<meta http-equiv=\"refresh\" content=\"0;url=index.php?do=allPhotos&duration=$duration&pic_upload_session=".$pic_upload_session."\"> ";
			}
		}
	}
ob_end_flush();


?>
