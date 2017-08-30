<?php
include "../../sy-config.php";
session_start();
error_reporting(E_ALL & ~E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
header('Content-Type: text/html; charset=utf-8');
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require "../photo.process.functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
//adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');
$discard_small = 0;
$smallest = 300;
$check_rotate = $_REQUEST['check_rotate'];
$amazon = true;
ini_set('upload_max_filesize', "400M");
ini_set('post_max_size', "400M");
ini_set('max_execution_time',8000);
set_time_limit(50);
ini_set('memory_limit', '1024M');




/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/sy-photos'; // Relative to the root
$hash = $site_setup['salt']; 

$verifyToken = md5($hash . $_POST['timestamp']);
// print "################### ". $verifyToken." ----- ".$_POST['token'];
$what =  date('Y-m-d h:i:s')." HIT THE FILE: ".$_FILES['Filedata']['tmp_name'].""; 
foreach($_FILES  as $file) { 
	if(is_array($file)) { 
		foreach($file AS $f) {
			$what .= $f."\r\n";
		}
	} else { 
		$what .= $file."\r\n";
	}
}
// logdata($what);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $setup['path']. $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	$name = $_FILES['Filedata']['name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','JPG','gif','GIF','png','PNG'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
$what =  date('Y-m-d h:i:s')." PROCESSING THE FILE: ".$_FILES['Filedata']['tmp_name'].""; 
// logdata($what);
	if (in_array($fileParts['extension'],$fileTypes)) {

	// move_uploaded_file($tempFile,$targetFile);


$targetfilepath= $tempFile;


// Create new folder for photo if needed
$pic_folder = createPhotoFolder();

$add_hash = substr(md5(date('ymdHis')),0,15);
$add_hash_small = substr(md5(date('ymdHis')),0,6);
$add_hash_med = substr(md5(date('ymdHis')),0,12);
$add_hash_large = substr(md5(date('ymdHis')),0,9);
$add_hash_th = substr(md5(date('ymdHis')),0,3);

$small_prefix = $setup['add_to_photo_files']."small_" .$add_hash_small;
$large_prefix = $setup['add_to_photo_files']."large_" .$add_hash_large;
$org_prefix = $setup['add_to_photo_files']."original_" .$add_hash;
$mini_prefix = $setup['add_to_photo_files']."mini_".$add_hash_th;
$th_prefix = $setup['add_to_photo_files']."th_".$add_hash_th;

$filename = cleanUploadFileName($name);

/*
if(($filename == "DSC_0638.jpg")||($filename == "DSC_1074.jpg")==true) { 
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	die();
}
*/
$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$mini_prefix."_" . $filename;
$thumb_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename;
$small_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$small_prefix."_" . $filename;
$med_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$med_prefix."_" . $filename;
$large_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/" .$large_prefix."_". $filename;
$full_name =  $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$org_prefix."_". $filename;

$large_width = $photo_setup['blog_large_width'];
$large_height = $photo_setup['blog_large_height'];
$small_width = $_REQUEST['imageWidth'];
$small_height = $_REQUEST['imageHeight'];
$thumb_width = $_REQUEST['th_size'];
$thumb_height = $_REQUEST['th_size_height'];
$mini_size = $_REQUEST['mini_size'];

$discard_dup = false;
$size_original = @GetImageSize($targetfilepath,$info); 

$discard_dup = checkfordup($filename,$_REQUEST['date_id'],$_REQUEST['sub_id']);

if($_REQUEST['replace_order_photo'] > 0) { 
	$_REQUEST['replace_photo'] = 0;
}

$ext = strtolower(substr($full_name, -4));

if($ext == ".png") {
	$new_image = imagecreatefrompng($targetfilepath);
} else {
	$new_image = imagecreatefromjpeg($targetfilepath);
}

if(function_exists(exif_read_data)) { 
	$exif = @exif_read_data($targetfilepath);
}
if($check_rotate == "1") { 
	if (!empty($exif['Orientation'])) {
		switch ($exif['Orientation']) {
			case 3:
				$new_image = imagerotate($new_image, 180, 0);
				$rotate =  "'1";
				break;

			case 6:
				$new_image = imagerotate($new_image, -90, 0);
				$rotate =  "1";
				break;

			case 8:
				$new_image = imagerotate($new_image, 90, 0);
				$rotate =  "1";
				break;
		}
	}
}
if($rotate == "1") { 
	imagejpeg($new_image,$targetfilepath , 99);
//	$targetfilepath = $full_name;

	copy($targetfilepath,$full_name);
} else { 
	// print "<li> ################ NOT ROTATED ".$full_name;

	copy($targetfilepath,$full_name);
}

if((($size_original[0] < $smallest)&&($discard_small == 1))||($discard_dup ==true) ==true) { 
	unlink($targetfilepath);
} else { 

	// Copy original upload
	copy($targetfilepath,$full_name);
	
	
	if(!empty($_REQUEST['bill_id'])) { 
		$large_width = 2200;
		$large_height = 2200;
	}
	if($ext == ".gif") {
		// $th_prefix= "original_".$add_hash;


		$old_file = $targetfilepath;
		// Large Photo
		processPhoto($old_file,$size_original,$large_name,$large_width,$large_height,$_REQUEST['watermark_photos'],$_REQUEST['logo_photos'],0,$photo_setup['resize_quality'],true,false);
		$add_large = $large_prefix."_". $filename;

		// Small Photo
		processPhoto($large_name,$size_original,$small_name,$small_width,$small_height,0,0,0,92,true,false);

		// Thumbnail
		processPhoto($small_name,$size_original,$thumb_name,$thumb_width,$thumb_height,0,0,0,92,true,false);

		// Mini
		processPhoto($thumb_name,$size_original,$mini_name,$mini_size,$mini_size,0,0,1,92,true,false);
		$add_large= $org_prefix."_". $filename;

	} elseif($ext == ".png") {
		// $th_prefix= "original_".$add_hash;


		$old_file = $targetfilepath;
		// Large Photo
		processPhoto($old_file,$size_original,$large_name,$large_width,$large_height,$_REQUEST['watermark_photos'],$_REQUEST['logo_photos'],0,$photo_setup['resize_quality'],false,true);
		$add_large = $large_prefix."_". $filename;

		// Small Photo
		processPhoto($large_name,$size_original,$small_name,$small_width,$small_height,0,0,0,92,false,true);

		// Thumbnail
		processPhoto($small_name,$size_original,$thumb_name,$thumb_width,$thumb_height,0,0,0,92,false,true);

		// Mini
		processPhoto($thumb_name,$size_original,$mini_name,$mini_size,$mini_size,0,0,1,92,false,true);
		// $add_large= "original_".$add_hash."_".$filename;


	} else {  


		$old_file = $targetfilepath;
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


	if(!empty($_REQUEST['cat_id'])) { 
		$no_dis = 1;
	}
	if(!empty($_REQUEST['package_id'])) { 
		$no_dis = 1;
	}
	if(!empty($_REQUEST['pp_id'])) { 
		$no_dis = 1;
	}

	if((!empty($_REQUEST['date_preview_id']))||(!empty($_REQUEST['bp_sub_preview'])) == true) { 
		$no_dis = 1;
	}
	if($no_dis !== 1 ) { 
		$add_session = ", pic_upload_session='".$_REQUEST['upload_session']."' ";
	}

	$key = MD5(date('ymdHis').makesalt());
	$fsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$add_large.""); 
	$ssize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$small_prefix."_" . $filename.""); 
	$thsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$th_prefix."_" . $filename.""); 



	$pic_filesize= @FileSize($full_name); 
	$pic_filesize_large= @FileSize($large_name); 
	$pic_filesize_small= @FileSize($small_name); 
	$pic_filesize_thumb= @FileSize($thumb_name); 

	if(($_REQUEST['discard_original'] == "1")&&($ext !== ".gif")==true) { 

	} else { 
		$pic_full_name = $org_prefix."_". $filename;
	}
	if($_REQUEST['replace_photos'] > 0) { 

		$what =  date('Y-m-d h:i:s')." Replace photo selected for ".$filename.""; 
		// logdata($what);
		if($_REQUEST['date_id'] > 0) { 
			$and_gallery = " AND bp_blog='".$_REQUEST['date_id']."'  ";
		}
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE pic_org='".addslashes(stripslashes(trim($filename)))."' $and_gallery ");
		$what =  date('Y-m-d h:i:s')." Checking if photo: ".$filename." exists for replace photo"; 
		// logdata($what);

		
		if($pic['pic_id'] > 0) { 
			$what =  date('Y-m-d h:i:s')." Photo ".$pic['pic_id']." found"; 
			// logdata($what);

			$replaced = true;
			if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".$pic['pic_th']."' ")<=1) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']);
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
			}
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']);
			$pic_id = $pic['pic_id'];
			updateSQL("ms_photos", "
			pic_pic='".addslashes(stripslashes(trim($small_prefix."_" . $filename)))."', 
			pic_th='".addslashes(stripslashes(trim($th_prefix."_" . $filename)))."', 
			pic_mini='".addslashes(stripslashes(trim($mini_prefix."_" . $filename)))."',
			pic_full='".addslashes(stripslashes(trim($pic_full_name)))."', 
			pic_org='".addslashes(stripslashes(trim($filename)))."', 
			pic_large='".addslashes(stripslashes(trim($add_large)))."', 
			pic_date='".date('Y-m-d H:i:s')."'  ,
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
			pic_client='".$_REQUEST['pic_client']."' WHERE pic_id='".$pic['pic_id']."' ");

			$what =  date('Y-m-d h:i:s')." Updating database with new photo for  ".$pic['pic_id'].""; 
			// logdata($what);

		} else { 
			$what =  date('Y-m-d h:i:s')." Photo: ".$filename." was not found to replace"; 
			// logdata($what);

		}
		$what =  date('Y-m-d h:i:s')." End replace photo for ".$filename.""; 
		// logdata($what);

	}



	if(($_REQUEST['replace_photo'] <= 0) && ($replaced !== true)==true) { 
		$pic_id = insertSQL("ms_photos", "
		pic_pic='".addslashes(stripslashes(trim($small_prefix."_" . $filename)))."', 
		pic_th='".addslashes(stripslashes(trim($th_prefix."_" . $filename)))."', 
		pic_mini='".addslashes(stripslashes(trim($mini_prefix."_" . $filename)))."',
		pic_full='".addslashes(stripslashes(trim($pic_full_name)))."', 
		pic_org='".addslashes(stripslashes(trim($filename)))."', 
		pic_large='".addslashes(stripslashes(trim($add_large)))."', 
		pic_date='".date('Y-m-d H:i:s')."' $add_session ,
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

		if($_REQUEST['replace_order_photo'] > 0) { 
			if($_REQUEST['cart_id'] > 0) { 
				$cart = doSQL("ms_cart","*","WHERE cart_id='".$_REQUEST['cart_id']."' ");
				if($cart['cart_id'] > 0) { 
					updateSQL("ms_cart", "cart_disable_download='0', cart_pic_id='".$pic_id."', cart_pic_org='".addslashes(stripslashes(trim($filename)))."', cart_thumb='' WHERE cart_id='".$_REQUEST['cart_id']."' ");
				} else { 
					$cart = doSQL("ms_cart_archive","*","WHERE cart_id='".$_REQUEST['cart_id']."' ");
					if($cart['cart_id'] > 0) { 
						updateSQL("ms_cart_archive", "cart_disable_download='0', cart_pic_id='".$pic_id."', cart_pic_org='".addslashes(stripslashes(trim($filename)))."', cart_thumb='' WHERE cart_id='".$_REQUEST['cart_id']."' ");
					}

				}
			}

		}
	}
	// Get EXIF / IPTC Data & Keywords


	/* REPLACE PHOTO */
	if(($_REQUEST['replace_photo'] > 0)&&($_REQUEST['replace_order_photo'] <=0)==true) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$_REQUEST['replace_photo']."' ");
		if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".$pic['pic_th']."' ")<=1) { 
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
		}
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']);
		$pic_id = $pic['pic_id'];
		updateSQL("ms_photos", "
		pic_pic='".addslashes(stripslashes(trim($small_prefix."_" . $filename)))."', 
		pic_th='".addslashes(stripslashes(trim($th_prefix."_" . $filename)))."', 
		pic_mini='".addslashes(stripslashes(trim($mini_prefix."_" . $filename)))."',
		pic_full='".addslashes(stripslashes(trim($pic_full_name)))."', 
		pic_org='".addslashes(stripslashes(trim($filename)))."', 
		pic_large='".addslashes(stripslashes(trim($add_large)))."', 
		pic_date='".date('Y-m-d H:i:s')."'  ,
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
		pic_amazon='0',
		pic_no_dis='$no_dis' , 
		pic_client='".$_REQUEST['pic_client']."' WHERE pic_id='".$pic['pic_id']."' ");
		if($_REQUEST['cart_id'] > 0) { 
			$cart = doSQL("ms_cart","*","WHERE cart_id='".$_REQUEST['cart_id']."' ");
			if($cart['cart_id'] > 0) { 
				updateSQL("ms_cart", "cart_disable_download='0', cart_pic_id='".$pic_id."', cart_pic_org='".addslashes(stripslashes(trim($filename)))."', cart_thumb='' WHERE cart_id='".$_REQUEST['cart_id']."' ");
			} else { 
				$cart = doSQL("ms_cart_archive","*","WHERE cart_id='".$_REQUEST['cart_id']."' ");
				if($cart['cart_id'] > 0) { 
					updateSQL("ms_cart_archive", "cart_disable_download='0', cart_pic_id='".$pic_id."', cart_pic_org='".addslashes(stripslashes(trim($filename)))."', cart_thumb='' WHERE cart_id='".$_REQUEST['cart_id']."' ");
				}
			}
		}
	}





	$size_original = @GetImageSize($full_name,$info); 
	if($_REQUEST['no_meta'] !== "1") { 
		$photo_info = getphotoinfo($full_name,$info,$pic_id);
		updateSQL("ms_photos", "pic_title='".addslashes(stripslashes(trim($photo_info['title'])))."'  , 
		pic_text='".addslashes(stripslashes(trim($photo_info['text'])))."' , 
		pic_keywords='".addslashes(stripslashes(trim($photo_info['tags'])))."' , 
		pic_date_taken='".addslashes(stripslashes(trim($photo_info['date_taken'])))."' ,
		pic_camera='".addslashes(stripslashes(trim($photo_info['camera_make'])))."' , 
		pic_camera_model='".addslashes(stripslashes(trim($photo_info['camera_model'])))."' 
		WHERE pic_id='".$pic_id."' ");
	}


	if(($_REQUEST['replace_photo'] <= 0) && ($replaced !== true) && ($_REQUEST['replace_order_photo'] <=0)==true) { 


		if(!empty($_REQUEST['date_id'])) { 
			if($_REQUEST['replace'] > 0) { 
				$tpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*","WHERE bp_pic='".$_REQUEST['replace']."' AND bp_blog='".$_REQUEST['date_id']."' "); 
				deleteSQL("ms_blog_photos", "WHERE bp_id='".$tpic['bp_id']."' ", "1");
				$new_pic = insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_blog='".$_REQUEST['date_id']."', bp_order='".$tpic['bp_order']."', bp_sub='".$_REQUEST['sub_id']."' ");
				insertSQL("ms_proofing_revisions", "rev_prior_pic='".$tpic['pic_id']."', rev_this_pic='".$pic_id."', rev_date_id='".$_REQUEST['date_id']."'  ");
				updateSQL("ms_proofing_revisions", "rev_this_pic='".$pic_id."' WHERE rev_this_pic='".$tpic['pic_id']."' ");
			} else { 
				$last = doSQL("ms_blog_photos", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' ORDER BY bp_order DESC");
				$order = $last['bp_order']+2;
				insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_blog='".$_REQUEST['date_id']."', bp_order='$order', bp_sub='".$_REQUEST['sub_id']."' ");
			}
		}
		if(!empty($_REQUEST['cat_id'])) { 
			deleteSQL2("ms_blog_photos", "WHERE bp_cat='".$_REQUEST['cat_id']."' ");
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_cat='".$_REQUEST['cat_id']."' ");
		}

		if(!empty($_REQUEST['package_id'])) { 
			//deleteSQL2("ms_blog_photos", "WHERE bp_package='".$_REQUEST['package_id']."' ");
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_package='".$_REQUEST['package_id']."' ");
		}
		if(!empty($_REQUEST['pp_id'])) { 
			//deleteSQL2("ms_blog_photos", "WHERE bp_package='".$_REQUEST['package_id']."' ");
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_product='".$_REQUEST['pp_id']."' ");
		}

		if(!empty($_REQUEST['date_preview_id'])) { 
			deleteSQL2("ms_blog_photos", "WHERE bp_blog_preview='".$_REQUEST['date_preview_id']."' AND bp_sub_preview<='0'  ");
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_blog_preview='".$_REQUEST['date_preview_id']."' ");

			 if($photo_setup['gallery_favicon'] == "1") { 
				$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_preview_id']."' ");
				// logdata("HERE: ".$date['date_id']."");

				if($date['cat_type'] == "clientphotos") { 
					$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$pic_id."' ");
					createGalleryIcon($date,$pic);
				}
			 }

		}
		if(!empty($_REQUEST['bp_sub_preview'])) { 
			deleteSQL2("ms_blog_photos", "WHERE bp_sub_preview='".$_REQUEST['bp_sub_preview']."' ");
			insertSQL("ms_blog_photos", "bp_pic='$pic_id', bp_sub_preview='".$_REQUEST['bp_sub_preview']."' ");
		}



		if(!empty($_REQUEST['bill_id'])) { 
			if($_REQUEST['slide_id'] > 0) { 
				updateSQL("ms_billboard_slides", "slide_pic='".$pic_id."' WHERE slide_id='".$_REQUEST['slide_id']."' ");
			} else { 
				$last = doSQL("ms_billboard_slides", "*", "WHERE slide_billboard='".$_REQUEST['bill_id']."' ORDER BY slide_order DESC");
				$order = $last['slide_order']+2;
				$slast = doSQL("ms_billboard_slides", "*", "ORDER BY slide_id DESC");

				$sid = insertSQL("ms_billboard_slides", "
				slide_pic='$pic_id', 
				slide_billboard='".$_REQUEST['bill_id']."', 
				slide_order='$order' ");
				updateNewBillboardSlide($sid,$slast);
			}
		}
	}
	chmod($mini_name,0644);
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
		$what =  date('Y-m-d h:i:s')." File exists: ".$full_name.""; 
		// logdata($what);

		if(!is_dir($full_name)) { 
			chmod($full_name,0644);
		}
	}

	unlink($targetfilepath);
	if(($_REQUEST['discard_original'] == "1")&&($ext !== ".gif")==true) { 
		unlink($full_name);
	}

	if($photo_setup['upload_amazon'] == 1) { 
		if(file_exists($mini_name)) { 
			$uploadFile = $mini_name;
			$folderName = $_REQUEST['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		if(file_exists($thumb_name)) { 
			$uploadFile = $thumb_name;
			$folderName = $_REQUEST['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";

		}
		if(file_exists($small_name)) { 
			$uploadFile = $small_name;
			$folderName = $_REQUEST['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";

		}
		if(file_exists($large_name)) { 
			$uploadFile = $large_name;
			$folderName = $_REQUEST['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";

		}
		if(file_exists($full_name)) { 
			$uploadFile = $full_name;
			$folderName = $_REQUEST['upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		if($s3error !== true) { 
			if(file_exists($mini_name)) { unlink($mini_name); } 
			if(file_exists($thumb_name)) { unlink($thumb_name); } 
			if(file_exists($small_name)) { unlink($small_name); } 
			if(file_exists($large_name)) { unlink($large_name); } 
			if(file_exists($full_name)) { unlink($full_name); } 
			updateSQL("ms_photos", "pic_amazon='1', pic_bucket='".addslashes(stripslashes(trim($photo_setup['awsBucketName'])))."', pic_bucket_folder='".$folderName."' WHERE pic_id='".$pic_id."' ");
		}

	}



}


//		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}
}
?>