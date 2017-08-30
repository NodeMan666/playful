<?php
include "editor.php";
function getstoragespace() { 
	global $setup;
	$f = $setup['path']."/sy-photos";
	$io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
	$size = fgets ( $io, 4096);
	$sy_photo_size = substr ( $size, 0, strpos ( $size, "\t" ) );
	pclose ( $io );


	$f = $setup['path']."/sy-misc";
	$io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
	$size = fgets ( $io, 4096);
	$sy_misc_size = substr ( $size, 0, strpos ( $size, "\t" ) );
	pclose ( $io );


	$f = $setup['path']."/sy-downloads";
	$io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
	$size = fgets ( $io, 4096);
	$sy_downloads_size = substr ( $size, 0, strpos ( $size, "\t" ) );
	pclose ( $io );
	$total_size = ($sy_photo_size + $sy_misc_size + $sy_downloads_size) * 1024;
	return $total_size;
}


function url_get_contents ($Url) {
	global $site_setup;
    if (!function_exists('curl_init')){ 
		$output = @file_get_contents($Url);
    }
    if(function_exists('curl_init')){ 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'sytist/php');
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    
		$output = curl_exec($ch);
		curl_close($ch);
	}
	if(empty($output)) { 
		$output = @file_get_contents($Url);
	}
    return $output;
}
function showfilesizefrombytes($b) { 
	if($b > 1073741824) { 
		$bytes  =$b / 1073741824;
		return round($bytes,2). "GB";
	} else if($b > 1048576) {
		$bytes  = $b / 1048576;
		return round($bytes,0). "MB";
	} else {
		$bytes = $b / 1024;
		return round($bytes,0). "KB";
	}
}

function get_starred($str){
    $len = strlen($str);
	if($len > 1) { 
	    return substr($str, 0,1). str_repeat('*',$len - 2) . substr($str, $len - 1 ,1);
	} else { 
		return $str;
	}
}
function createGalleryIcon($date,$pic) { 
	global $setup,$site_setup,$photo_setup;
	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/favicons")) {
		$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/favicons", $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/favicons", $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/favicons/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}

	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id'])) {
		$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id'], $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id'], $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id']."/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}
	if($pic_id > 0) { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE pic_id='".$pic_id."' ");
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
		if(empty($pic['pic_id'])) { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		}
	}
	if(!empty($pic['pic_id'])) { 
		$targetfilepath = $setup['path']."/sy-photos/".$pic['pic_folder']."/".$pic['pic_pic'];

		if($pic['pic_amazon'] == "1") { 
			$targetfilepath = $setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id']."/".$pic['pic_org'];
			if(ini_get('allow_url_fopen') <= 0) {
				copy_amazon_file("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$targetfilepath);
			} else {
				copy("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$targetfilepath);
			}
			$delete_amazon = true;
		}
		$size_original = GetImageSize($targetfilepath,$info); 
		$ext = strtolower(substr($pic['pic_pic'], -4));

		if($ext == ".png") {
			$new_image = imagecreatefrompng($targetfilepath);
		} else {
			$new_image = imagecreatefromjpeg($targetfilepath);
		}

		$icon_name = "icon.png";
		$new_name = $setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id']."/icon-holder.png";
		Imagepng($new_image,$new_name);
		$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id']."/".$icon_name;
		processPhoto($new_name,$size_original,$mini_name,200,200,0,0,1,92,false,true);
		// print '<img src="/'.$setup['photos_upload_folder']."/favicons/".$date['date_id']."/".$icon_name.'">';

		$fsizes = array("180","152","120","76","60","16");
		foreach($fsizes AS $fsize) { 
			$icon_name = "icon-".$fsize.".png";
			Imagepng($new_image,$new_name);
			$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/favicons/".$date['date_id']."/".$icon_name;
			processPhoto($new_name,$size_original,$mini_name,$fsize,$fsize,0,0,1,92,false,true);

			// print '<img src="/'.$setup['photos_upload_folder']."/favicons/".$date['date_id']."/".$icon_name.'">';
		}
		unlink($new_name);
		if($delete_amazon == true) { 
			unlink($targetfilepath);
		}

		print "<div class=\"pc\">Gallery favicon created</div>";
	}
}

function showContractDir() { 
	global $site_setup;
	if(empty($site_setup['contract_folder'])) { 
	?><div class="pc center" style="background: #FFFA02; padding: 16px; color: #000000;">In order to view & sign contracts, a contract directory needs to be created which is part of the URL. <br>This only needs to be done once. <a href="index.php?do=people&view=allcontracts&sub=folder">Click here to create the directory</a>.</div>
<?php 
	}
}


function createNewPage($id) {
	global $site_setup,$setup,$date_type;
	$page = doSQL("ms_calendar", "*", "WHERE date_id='".$id."' ");
	$page_link = stripslashes(trim(strtolower($page['date_title'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	$page_link = str_replace("----","".$site_setup['sep_page_names']."",$page_link);
	$page_link = str_replace("---","".$site_setup['sep_page_names']."",$page_link);
	$page_link = str_replace("--","".$site_setup['sep_page_names']."",$page_link);

		$date_link = $page_link;

	if(!empty($page['date_cat'])) {
		$parent_page = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$page['date_cat']."' ");
		$page_link = $parent_page['cat_folder']."/".$page_link;
	}
	if(!empty($_REQUEST['page_under'])) {
		$parent_page = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['page_under']."' ");
		$ucat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$parent_page['date_cat']."' ");
		if(!empty($ucat['cat_id'])) { 
			$page_link = $ucat['cat_folder']."/".$parent_page['date_link']."/".$page_link;
		} else { 
			$page_link = $parent_page['date_link']."/".$page_link;
		}
		$date_link = $parent_page['date_link']."/".$date_link;
	}

		$date_folder = $setup['content_folder'];

	if(is_dir($setup['path']."".$date_folder."/".$page_link)) {
		$page_link = $page_link."-".$page['date_id'];
		$date_link = $date_link."-".$page['date_id'];
	}
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$date_folder."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
			$perms = 0755;
		print "<li>C";
	}

	print "<li>$parent_permissions<li>$page_link<li>$perms<li>";
	if(!empty($setup['folder_permissions'])) { 
		$perms = $setup['folder_permissions'];
	}

	mkdir("".$setup['path']."".$date_folder."/$page_link", $perms);
	chmod("".$setup['path']."".$date_folder."/$page_link", $perms);
	updateSQL("ms_calendar", "date_link='".$date_link."' WHERE date_id='".$id."' ");
	print "Create: ".$setup['path']."".$date_folder."/".$page_link."/index.php";
//	copy("".$setup['path']."".$date_folder."/default.php", "".$setup['path']."".$date_folder."/".$page_link."/index.php");
	
	
	
	// $old = umask(122);
	$fp = fopen("".$setup['path']."".$date_folder."/".$page_link."/index.php", "w");

	$file_include = "main_index_include.php";

	$add_path .= "../";
	if(!empty($_REQUEST['page_under'])) {
		$add_path .= "../";
		$ucat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$parent_page['date_cat']."' ");
		if(!empty($ucat['cat_id'])) { 
			$add_path .= "../";
			if(!empty($ucat['cat_under_ids'])) { 
				$ids = explode(",",$ucat['cat_under_ids']);
				foreach($ids AS $num) { 
					$add_path .="../";
				}
			}
		}
	}
		if(!empty($page['date_cat'])) { 
			$add_path .= "../";

			if(!empty($parent_page['cat_under_ids'])) { 
				$ids = explode(",",$parent_page['cat_under_ids']);
				foreach($ids AS $num) { 
					$add_path .="../";
				}
			}
			$info =  "<?php\n\$date_id = ".$id."; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/$file_include\";\n?>"; 
		} else { 
			$info =  "<?php\n\$date_id = ".$id."; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/$file_include\";\n?>"; 
		}


	fputs($fp, "$info\n");
	fclose($fp);
	// umask($old);

	if(!empty($setup['file_permissions'])) { 
		chmod($setup['path']."".$date_folder."/".$page_link."/index.php", $setup['file_permissions']);
	}
//	print "<li>". $setup['file_permissions'];
// exit();

}

function adminsessionCheck() {
	if((trim($_SESSION['office_admin_login']) !== "1") OR ($_SESSION['office_admin'] == NULL )==true) {
		header("Location: index.php?do=login");
		die();
	} else { 
		$admin = doSQL("ms_admins", "*", "WHERE admin_id='".$_SESSION['office_admin_id']."' ");
		if(empty($admin['admin_id'])) { 
			session_write_close();
			header("location: logout.php");
			die();
		}
	}
}

function showProductOption($opt,$pp_type) { 
	print "<div>&nbsp;</div>";
	if($opt['opt_type'] == "text") { 
		print "<div>"; if($opt['opt_price'] > 0) { print "+ ".showPrice($opt['opt_price']); } print "".$opt['opt_name']."</div>";
		print "<div><input type=\"text\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\"></div>";
	}
	if(($opt['opt_type'] == "checkbox")||($opt['opt_type'] == "download")==true) { 
		print "<div><input type=\"checkbox\" name=\"opt-".$opt['opt_id']."\" value=\"1\"\"> "; if($opt['opt_price_checked'] > 0) { print " + ".showPrice($opt['opt_price_checked']); } print " ".$opt['opt_name']."</div>";
	}
	if($opt['opt_type'] == "dropdown") { 
		print "<div>".$opt['opt_name']."</div>";
		print "<select name=\"opt-".$opt['opt_id']."\">";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_id ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
			print "<option value=\"".$sel['sel_id']."\""; if($sel['sel_default'] == "1") { print "selected"; } print ">".$sel['sel_name'].""; 
			if($sel['sel_price'] > 0) { print " +".showPrice($sel['sel_price']); }  if($sel['sel_price'] < 0) { print " -".showPrice(-$sel['sel_price']); } 
			 print "</option>";
		}
		print "</select>";
	}

	if($opt['opt_type'] == "radio") { 
		print "<div>".$opt['opt_name']."</div>";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
			print "<input type=\"radio\" name=\"opt-".$opt['opt_id']."\" value=\"".$sel['sel_id']."\" ";  if($sel['sel_default'] == "1") { print "checked"; } print "> ".$sel['sel_name'].""; if($sel['sel_price'] > 0) { print " + ".showPrice($sel['sel_price']); } print " ";
		}
	}

	if($opt['opt_service'] > 0) { 
		print "<div class=\"small\"><a href=\"\" onclick=\"editserviceoption('".$opt['opt_service']."','".$opt['opt_id']."'); return false;\">edit</a> <a href=\"index.php?do=booking&deleteOption=".$opt['opt_id']."\"  onClick=\"return confirm('Are you sure you want to delete this option? ')\";>delete</a>  ";
		print "</div>";
	} else { 

		if($_REQUEST['do'] !== "copyOption") { 
			print "<div class=\"small\"><a href=\"\" onclick=\"editoption('0','".$opt['opt_photo_prod']."', '".$opt['opt_id']."'); return false;\">edit</a> <a href=\"index.php?do=".$_REQUEST['do']."&view=".$_REQUEST['view']."&action=deleteOption&opt_id=".$opt['opt_id']."\"  onClick=\"return confirm('Are you sure you want to delete this option? ')\";>delete</a>  ";
			
			if($opt['opt_package'] <= 0) { print "<a href=\"\" onclick=\"copyoption('0','".$opt['opt_photo_prod']."', '".$opt['opt_id']."','".$pp_type."'); return false;\">copy</a>"; } 
			print "</div>";
		}
	}
}




function checkadminaccess() { 
	global $loggedin;
	if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")==true) { 
		if(($_REQUEST['do'] == "settings")&&($loggedin['settings'] !== "1")==true) { 
			$deny = true;
		}

		if(($_REQUEST['do'] == "look")&&($loggedin['design'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "news")&&($loggedin['content'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "comments")&&($loggedin['comments'] !== "1")==true) { 
			$deny = true;
		}

		if(($_REQUEST['do'] == "forms")&&($loggedin['forms'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "comments")&&($loggedin['comments'] !== "1")==true) { 
			$deny = true;
		}

		if(($_REQUEST['do'] == "orders")&&($loggedin['orders'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "people")&&($loggedin['people'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "photoprods")&&($loggedin['photoprods'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "discounts")&&($loggedin['coupons'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "reports")&&($loggedin['reports'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "stats")&&($loggedin['stats'] !== "1")==true) { 
			$deny = true;
		}
		if(($_REQUEST['do'] == "allPhotos")&&($loggedin['allphotos'] !== "1")==true) { 
			$deny = true;
		}
		
		
		if($deny == true) { ?>
		<div class="error">You do not have access to this section</div>
		<?php 
			die();
		}
	}

}


function checkadminaccessmenu($do) { 
	global $loggedin;
	if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")==true) { 
		if(($do == "settings")&&($loggedin['settings'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "look")&&($loggedin['design'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "news")&&($loggedin['content'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "comments")&&($loggedin['comments'] !== "1")==true) { 
			$deny = true;
		}

		if(($do == "forms")&&($loggedin['forms'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "comments")&&($loggedin['comments'] !== "1")==true) { 
			$deny = true;
		}

		if(($do == "orders")&&($loggedin['orders'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "people")&&($loggedin['people'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "photoprods")&&($loggedin['photoprods'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "discounts")&&($loggedin['coupons'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "reports")&&($loggedin['reports'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "stats")&&($loggedin['stats'] !== "1")==true) { 
			$deny = true;
		}
		if(($do == "allPhotos")&&($loggedin['allphotos'] !== "1")==true) { 
			$deny = true;
		}
		
		
		if($deny == true) { 
			return 'style="display: none;"'; 
		}
	}

}
function deleteOnePic($pic) {
	global $setup,$photo_setup;
	if(!empty($pic['pic_id'])) {



		if($pic['pic_amazon'] == "1") { 
			if (!class_exists('S3')) require_once 'S3.php';
			if (!defined('awsAccessKey')) define('awsAccessKey',$photo_setup['awsAccessKey']);
			if (!defined('awsSecretKey')) define('awsSecretKey',$photo_setup['awsSecretKey']);
			$s3 = new S3(awsAccessKey, awsSecretKey);

			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_mini']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_th']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_pic']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_large']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_full']);


		} else { 


			if(!empty($pic['pic_folder'])) { 
				$pic_folder = $pic['pic_folder'];
			} else { 
				$pic_folder = $pic['gal_folder'];
			}
		//	if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".$pic['pic_th']."' ")<=1) { 
			if((!empty($pic['pic_th'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th']);
			}
			if((!empty($pic['pic_mini'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini']);
			}
		//	}

			if((!empty($pic['pic_med'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_med'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_med']);
			}
			if((!empty($pic['pic_large'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large']);
			}
			if((!empty($pic['pic_pic'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic']);
			}
			if((!empty($pic['pic_full'])) && (file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full'])) == true) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full']);
			}
			removedeletephotodirectory($pic['pic_folder']);

		}
	}
	deleteSQL("ms_photos", "WHERE pic_id='".$pic['pic_id']."' ", "1" );
	deleteSQL2("ms_blog_photos", "WHERE bp_pic='".$pic['pic_id']."' ");
	deleteSQL2("ms_billboard_slides", "WHERE slide_pic='".$pic['pic_id']."' ");
	deleteSQL2("ms_photo_keywords_connect", "WHERE key_pic_id='".$pic['pic_id']."' ");
}


function deletePic2() {
	global $_REQUEST,$setup,$photo_setup;


	if(!is_numeric($_REQUEST['pic_id'])) { die(); } 

	$pic = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p ')  AS pic_date_show , date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p')  AS pic_date_taken_show", "WHERE ms_photos.pic_id='".$_REQUEST['pic_id']."' ");
	if(!empty($pic['pic_id'])) {
		if($pic['pic_amazon'] == "1") { 
			if (!class_exists('S3')) require_once 'S3.php';
			if (!defined('awsAccessKey')) define('awsAccessKey',$photo_setup['awsAccessKey']);
			if (!defined('awsSecretKey')) define('awsSecretKey',$photo_setup['awsSecretKey']);
			$s3 = new S3(awsAccessKey, awsSecretKey);

			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_mini']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_th']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_pic']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_large']);
			$s3->deleteObject($pic['pic_bucket'], $pic['pic_bucket_folder']."/".$pic['pic_full']);


		} else { 

			if(!empty($pic['pic_folder'])) { 
				$pic_folder = $pic['pic_folder'];
			} else { 
				$pic_folder = $pic['gal_folder'];
			}
			if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".addslashes(stripslashes($pic['pic_th']))."' ")<=1) { 
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th']);
				@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini']);
			}
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_med']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full']);

		}
	}

	deleteSQL("ms_photos", "WHERE pic_id='".$pic['pic_id']."' ", "1" );
	deleteSQL2("ms_blog_photos", "WHERE bp_pic='".$pic['pic_id']."' ");
	deleteSQL2("ms_billboard_slides", "WHERE slide_pic='".$pic['pic_id']."' ");
	deleteSQL2("ms_photo_keywords_connect", "WHERE key_pic_id='".$pic['pic_id']."' ");
}
	function removedeletephotodirectory($folder) { 
		global $setup;
		if(class_exists('FilesystemIterator')) { 
			$folder_path = $setup['path']."/sy-photos/".$folder."/";
			if(is_dir($folder_path)) { 

				$fi = new FilesystemIterator($setup['path']."/sy-photos/".$folder."/", FilesystemIterator::SKIP_DOTS);
				if(iterator_count($fi) < 3) { 
					// print "lets remove this ";
					$dir = opendir($folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) {
							$file_count++;
							@unlink($folder_path."/".$file);

							//print "<li>File: $file";
						}
					}
				closedir($dir); 
				@rmdir($folder_path);

				$up_folders = explode("/",$folder);
				//print "<li>".count($up_folders);
				//print "<li>".$up_folders[count($up_folders) - 1];
				$x = 1;
				$day_folder = "";
				$hour_folder = $up_folders[count($up_folders)-1];
				while(count($up_folders) > $x) { 
					if($x > 1) {
						$day_folder.="/";
					}
					$day_folder .= $up_folders[$x - 1];
			//		print "add: ".$up_folders[$x - 1]." / ";
					$x++;
				}
				//print "<li>day folder: ".$day_folder;
				//print "<li>";
				//print_r($up_folders);
				// print "<li>".$hour_folder;
				$day_folder_path = $setup['path']."/sy-photos/".$day_folder;
				$day_folder_files = array();
					$dir = opendir($day_folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) {
							$file_count++;
							if((is_dir($day_folder_path."/".$file))&&($file!==$hour_folder)==true) {
								$stop_checking = true;
							//	print "<li>Folder: <a href=\"photos/misc/$file\">$file</a>";
							} else {
								array_push($day_folder_files, $file);

							//	print "<li>File: <a href=\"photos/misc/$file\">$file</a>";
							}
				//			print "<li><a href=\"photos/misc/$file\">$file</a>";
						}
					}
				closedir($dir); 

				if($stop_checking == true) { 
					//print "<li>Found folder so stopping";
				} else { 
					//print "<li>Delting files: ";
					// print "<li>THIS FILE: $tfile";
					@unlink($day_folder_path."/index.php");
					@rmdir($day_folder_path);

		//			print_r($day_folder_files);
				}
				// print iterator_count($fi);
			}
		}
	}
}
function isFullScreen() { 
	global $css;
	if($css['site_type'] == "1") { 
		print ai_no_fs_16;
	}
}
function isFullScreenLarge() { 
	global $css;
	if($css['site_type'] == "1") { 
		print ai_no_fs;
	}
}

function listFonts() { 
	$fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
	print "<div id=\"roundedForm\">";
	if(mysqli_num_rows($fonts) <= 0) { print "<div class=\"row center\">No google fonts selected</div>"; } 
	while($font = mysqli_fetch_array($fonts)) { 
		print "<div class=\"row\" style=\"font-family: '".$font['font']."';\"><a href=\"\" onClick=\"removeFont('".$font['id']."','".$_REQUEST['css_id']."'); return false;\">".ai_delete."</a> <a href=\"w-font-preview.php?font=".$font['font']."&css_id=".$_REQUEST['css_id']."\">".$font['font']."</a></div>";
	}
	print "</div>";
}


function updateNewBillboardSlide($id,$slast) { 
	$slide = doSQL("ms_billboard_slides", "*", "WHERE slide_id='".$id."' ");
	updateSQL("ms_billboard_slides", "
	slide_text_1_color='".$slast['slide_text_1_color']."',
	slide_text_1_size='".$slast['slide_text_1_size']."',
	slide_text_1_shadow='".$slast['slide_text_1_shadow']."',
	slide_text_1_font='".$slast['slide_text_1_font']."',
	slide_text_1_effect='".$slast['slide_text_1_effect']."',
	slide_text_1_time='".$slast['slide_text_1_time']."',
	slide_text_2_color='".$slast['slide_text_2_color']."',
	slide_text_2_size='".$slast['slide_text_2_size']."',
	slide_text_2_shadow='".$slast['slide_text_2_shadow']."',
	slide_text_2_font='".$slast['slide_text_2_font']."',
	slide_text_2_effect='".$slast['slide_text_2_effect']."',
	slide_text_2_time='".$slast['slide_text_2_time']."',
	slide_text_align='".$slast['slide_text_align']."',
	slide_top_margin='".$slast['slide_top_margin']."',
	slide_left_margin='".$slast['slide_left_margin']."'	
	WHERE slide_id='".$slide['slide_id']."'  ");
}

function deleteDateInline() { 
	global $setup;
	$entry = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['deleteDate']."' ");
	if($entry['date_type'] == "gal") { 
		$setup['content_folder'] = $setup['photos_folder'];
		$do = "photos";
	}
	if($entry['date_type'] == "page") { 
		$setup['content_folder'] = $setup['pages_folder'];
		$do = "pages";
	}
	if($entry['date_type'] == "news") { 
		$setup['content_folder'] = $setup['content_folder'];
		$do = "news";
	}
	doDeleteDate($entry);
	exit();
}

function deleteDate() { 
	global $setup;
	$entry = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['deleteDate']."' ");
	if($entry['date_type'] == "news") { 
		$setup['content_folder'] = $setup['content_folder'];
		$do = "news";
	}
	doDeleteDate($entry);

	$_SESSION['sm'] = "".$entry['date_title']." was deleted";
	$dt = explode("-",$entry['date_date']);
	session_write_close();
	if(!empty($_REQUEST['view'])) { 
			header("location: index.php?do=news&view=".$_REQUEST['view']."&date_cat=".$_REQUEST['date_cat']." ");
	} else { 
		if(!empty($entry['date_cat'])) { 
			header("location: index.php?do=news&date_cat=".$entry['date_cat']." ");
		} elseif(!empty($entry['page_under'])) { 
			$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$entry['page_under']."' ");
			if($uppage['date_cat'] > 0) { 
				header("location: index.php?do=news&date_cat=".$uppage['date_cat']."");

			} else { 
				header("location: index.php?do=news&date_cat=none");

			}
		} else { 
			header("location: index.php?do=news&date_cat=none");
		}
	}
	exit();
}

function doDeleteDate($entry) { 
	global $setup;
	if(empty($_REQUEST['deleteDate'])) {
		$_SESSION['smerror'] = "date ID not found";
		session_write_close();
		header("location: index.php?do=calendar");
		exit();
	}

	if(!empty($entry['date_id'])) {
		deleteSQL("ms_calendar", "WHERE date_id='".$entry['date_id']."' ",  "1");
		deleteSQL("ms_menu_links", "WHERE link_page='".$entry['date_id']."' ",  "1");
		deleteSQL2("ms_sub_galleries", "WHERE sub_date_id='".$entry['date_id']."' ");

		if($entry['page_under'] > 0) { 
			$update = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$entry['page_under']."' ");
			$del_path = $setup['path']."".$setup['content_folder']."".$update['cat_folder']."/".$entry['date_link'];
		} else { 
			$del_path = $setup['path']."".$setup['content_folder']."".$entry['cat_folder']."/".$entry['date_link'];		
		}
		$dir = opendir($del_path); 
		while ($file = readdir($dir)) { 
			if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
				unlink("$del_path/$file");
				print "<li>--$del_path/$file";
			}
		}
		rmdir("$del_path");
		deleteSQL2("ms_blog_cats_connect", "WHERE con_prod='".$entry['date_id']."' ");
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$entry['date_id']."' ORDER BY ms_blog_photos.bp_order ASC ");
		while($pic = mysqli_fetch_array($pics)) { 
			deleteSQL("ms_blog_photos", "WHERE bp_id='".$pic['bp_id']."' ", "1");
			if($_REQUEST['deletephotos'] == "1") { 
				updateSQL("ms_photos", "pic_delete='1' WHERE pic_id='".$pic['pic_id']."' ");
				// deleteOnePic($pic);
			}
		}
	}
}

function doDeleteDateBatch($entry) { 
	global $setup;

	if(!empty($entry['date_id'])) {
		deleteSQL("ms_calendar", "WHERE date_id='".$entry['date_id']."' ",  "1");
		deleteSQL("ms_menu_links", "WHERE link_page='".$entry['date_id']."' ",  "1");
		deleteSQL2("ms_sub_galleries", "WHERE sub_date_id='".$entry['date_id']."' ");
		deleteSQL2("ms_tag_connect", "WHERE tag_date_id='".$entry['date_id']."' ");
		if($entry['page_under'] > 0) { 
			$update = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$entry['page_under']."' ");
			$del_path = $setup['path']."".$setup['content_folder']."".$update['cat_folder']."/".$entry['date_link'];
		} else { 
			$del_path = $setup['path']."".$setup['content_folder']."".$entry['cat_folder']."/".$entry['date_link'];		
		}
		$dir = opendir($del_path); 
		while ($file = readdir($dir)) { 
			if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
				unlink("$del_path/$file");
				print "<li>--$del_path/$file";
			}
		}
		rmdir("$del_path");
		deleteSQL2("ms_blog_cats_connect", "WHERE con_prod='".$entry['date_id']."' ");
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$entry['date_id']."' ORDER BY ms_blog_photos.bp_order ASC ");
		while($pic = mysqli_fetch_array($pics)) { 
			deleteSQL("ms_blog_photos", "WHERE bp_id='".$pic['bp_id']."' ", "1");
			if($_REQUEST['deletephotos'] == "1") { 
				// deleteOnePic($pic);
				updateSQL("ms_photos", "pic_delete='1' WHERE pic_id='".$pic['pic_id']."' ");

			}
		}
	}
}

function showVisPage($page) { 
	global $setup,$site_setup;
	$pginfo = explode("||",$page['page_viewed']);

	if($page['date_id'] > 0) { 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$page['date_id']."' ");
		if($date['date_id'] <= 0) { 
			print "Deleted Page";
		} else { 
			if($date['page_under'] > 0) { 
				$up_page = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
				$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$up_page['date_cat']."' ");
			} else { 
				$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
			}

			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\"   style=\"float: left; margin: 0 4px 0 0;max-width: 30px;\" ></a>"; 

			} else {
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" style=\"float: left; margin: 0 4px 0 0; max-width: 30px;\" ></a>"; 
				}
			}

			if($date['page_home'] == "1") { 
				print "<a  href=\"".$setup['temp_url_folder']."/\" target=\"_blank\" title=\"View On Website\">".ai_new_window."</a> <a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">Home Page</a>"; 
				if($pginfo[1] > 1) { print " Page #".$pginfo[1]; } 
			} else if($date['page_404'] == "1") { 
				print "404 - Page Not Found - ".$pginfo[2].""; 
			} else { 

				print "<a  href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link']."/\" target=\"_blank\" title=\"View On Website\">".ai_new_window."</a> <a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">"; 
				if(!empty($dcat['cat_under_ids'])) { 
					$scats = explode(",",$dcat['cat_under_ids']);
					foreach($scats AS $scat) { 
						$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
						print " <span>".$tcat['cat_name']."  > ";
					}
				}
				if(!empty($dcat['cat_id'])) { 
					print "<span>".$dcat['cat_name']." > ";
				}
				if(!empty($up_page['date_title'])) { print $up_page['date_title']." > "; } 

				print "<span>".$date['date_title']."</span></a>";
			}
		}

		} else if($pginfo[1] == "cat") { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$pginfo[2]."' ");
			$pic = doSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_cat='".$cat['cat_id']."' ");
			if(!empty($pic['pic_id'])) { 
				print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" style=\"float: left; margin: 0 4px 0 0; max-width: 30px;\" ></a>"; 
			}
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\" target=\"_blank\" title=\"View On Website\">".ai_new_window."</a> <a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">"; 

			if(!empty($cat['cat_under_ids'])) { 
				$scats = explode(",",$cat['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print " <span>".$tcat['cat_name']." > ";
				}
			}
			print "<span>".$cat['cat_name']."</span></a>";
			if($pginfo[3] > 1) { print " Page #".$pginfo[3]; } 
			if($pginfo[4] > 1) { 
				if($setup['forum'] == true) { 
					$topic = doSQL("ms_forum", "*", "WHERE id='".$pginfo[4]."' ");
					print " > ".$topic['topic'];
				}
			}
			if(!empty($pginfo[5] )) {
				print " > Search: ".$pginfo[5];	
			}



		} elseif($pginfo[0] == "home") { 
			print "<a href=\"".$setup['temp_url_folder']."/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> <span>Home Page</span>";
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$pginfo[2]."' ");
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/".$cat['cat_folder']."/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Blog  > ";
				if(!empty($cat['cat_under_ids'])) { 
				$scats = explode(",",$cat['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print " ".$tcat['cat_name']." > ";
				}
			}
		
			print "".$cat['cat_name']."</span>";		} elseif(($pginfo[0] == "blog")&&($pginfo[1]<=1)==true) {
				print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Home</span>";
		} else { 
		if($pginfo[0] == "page") {

				$date = doSQL("ms_calendar", "*", "WHERE old_page_id='".$pginfo[1]."' ");
				if($pginfo[1] == "1") { 
					print " <a href=\"/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Home page"; 
				} else {
					if(empty($date['date_id'])) { 
						print "<i>Deleted Page</i>";
					} else {
						print "<a href=\"/".$setup['pages_folder']."/".$date['date_link']."/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> <span>".$date['date_title']."</span>";
					}
				} 
		}

		if($pginfo[0] == "tag") {
			$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$pginfo[1]."' ");
			print " <a href=\"".$setup['temp_url_folder']."/tags/".$tag['tag_folder']."\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Tag: ".$tag['tag_tag'].""; 
		}

		if($pginfo[0] == "photocart") {
//			print $page['page_viewed'];
		}


		}

		if($pginfo[0] == "store") {
//			print $page['page_viewed'];
			if($pginfo[1] == "cart") { 
				print "Store - View Cart";
			} elseif($pginfo[1] == "checkout") { 
				print "Store - Checkout";
			} elseif($pginfo[1] == "checkoutexpresswindow") { 
				print "Checkout express window";
			} elseif($pginfo[1] == "checkoutexpresstopaypal") { 
				print "Clicked checkout express to PayPal";
			} elseif($pginfo[1] == "myaccount") { 
				print "Store - My Account";
			} elseif($pginfo[1] == "account") { 
				print "Store - My Account";
			} elseif($pginfo[1] == "newaccount") { 
				print "Store - New Account";
			} elseif($pginfo[1] == "expresscheckout") { 
				print "Store - Express Checkout";
			} elseif($pginfo[1] == "order") { 
				print "Store - View Order";
			} elseif($pginfo[1] == "search") { 
				print "Search: ".stripslashes($pginfo[2])."";
			}
		}
		if($pginfo[0] == "community") {
			if(!empty($pginfo[2])) { 
				print "<span>Community</span>: <a href=\"/community/forums/".$pginfo[1]."/index.php?see=viewTopic&topic=".$pginfo[3]."\" target=\"_blank\">".$pginfo[2]."</a>";
			} elseif(!empty($pginfo[1])) { 
				print "<span>Community</span>: <a href=\"/community/forums/".$pginfo[1]."/\" target=\"_blank\">".$pginfo[1]."</a>";
			} else { 
				print "<span>Community</span>";
			}
		}
		if($pginfo[0] == "favorites") {
			print "Favorites";
		}
		if($pginfo[0] == "contact") {
			print "Contact";
		}

}





	function showVisPageThumb($page) { 
	global $setup,$site_setup,$def;
	if(!empty($def['pic_mini'])) { 
		$def_photo = "<img src=\"".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$def['pic_folder']."/".$def['pic_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0;\">";
	}

		$pginfo = explode("||",$page['page_viewed']);
	//	print $page['page_viewed']." ".$page['date_id'];
		if($pginfo[1] > 1) { 
			if($pginfo[1] == "404") { 
				print "Page Not Found: <a href=\"".$pginfo[2]."\" target=\"_blank\">".$pginfo[2]."</a>";

			} else { 

				$date = doSQL("ms_calendar", "*", "WHERE date_id='".$pginfo[1]."' ");
				if($date['date_type'] == "news") { 
					if(!empty($date['date_mini'])) { 
						print "<img src=\"".$setup['temp_url_folder']."".$setup['content_folder']."/".$date['date_link']."/".$date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0; border: 0;\">"; 
					}

					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/".$date['date_link']."/\" title=\"View on website\" target=\"_blank\">".$date['date_title']."</a>";
				}
				if($date['date_type'] == "page") { 
					if(!empty($date['date_mini'])) { 
						print "<img src=\"".$setup['temp_url_folder']."/"; if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $date['date_link']."/".$date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0; border: 0;\">"; 
					}
					print "<a href=\"/"; if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $date['date_link']."/\" title=\"View on website\" target=\"_blank\">"; if(!empty($date['page_under'])) { $up = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' "); print $up['date_title']." > "; } print $date['date_title']."</a>";
				}
				if($date['date_type'] == "gal") { 
					if(!empty($date['date_mini'])) { 
						print "<img src=\"".$setup['temp_url_folder']."/".$setup['photos_folder']."/".$date['date_link']."/".$date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0; border: 0;\">"; 
					}
					print "<a href=\"".$setup['temp_url_folder']."/".$setup['photos_folder']."/".$date['date_link']."/\" title=\"View on website\" target=\"_blank\">".$date['date_title']."</a>";
				}
			}
		} elseif($pginfo[0] == "home") { 
			$date = doSQL("ms_calendar", "*", "WHERE page_home='1' ");
				if(!empty($date['date_mini'])) { 
					print "<img src=\"".$setup['temp_url_folder']."/"; if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0; border: 0;\">"; 
				}

			print "<a href=\"/\" title=\"View on website\" target=\"_blank\">Home Page</a>";
		} elseif(($pginfo[0] == "blog")&&($pginfo[1]=="cat")==true) {
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$pginfo[2]."' ");
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/".$cat['cat_folder']."/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Blog  > ";
				if(!empty($cat['cat_under_ids'])) { 
				$scats = explode(",",$cat['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print " ".$tcat['cat_name']." > ";
				}
			}
		
			print "".$cat['cat_name']."</span>";
		} elseif(($pginfo[0] == "blog")&&($pginfo[1]<=1)==true) {
				print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/\" title=\"View on website\" target=\"_blank\">Home</a>";
		} else { 
		if($pginfo[0] == "page") {

				$date = doSQL("ms_calendar", "*", "WHERE old_page_id='".$pginfo[1]."' ");
				if($pginfo[1] == "1") { 
					print " <a href=\"/\" title=\"View on website\" target=\"_blank\">".ai_new_window."</a> Home page"; 
				} else {
					if(empty($date['date_id'])) { 
	//					print "<i>Deleted Page</i>";
					} else {
						if(!empty($date['date_mini'])) { 
							print "<img src=\"".$setup['temp_url_folder']."/"; if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $date['date_link']."/".$date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0;\">"; 
					}

						print "<a href=\"/"; if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $date['date_link']."/\" title=\"View on website\" target=\"_blank\">"; if(!empty($date['page_under'])) { $up = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' "); print $up['date_title']; } print $date['date_title']."</a>";
					}
				} 
		}
		if($pginfo[0] == "photos") {
			if(!empty($pginfo[1])) { 
				$date = doSQL("ms_calendar", "*", "WHERE old_gal_id='".$pginfo[1]."' ");
				if(!empty($date['date_mini'])) { 
					print "<img src=\"".$setup['temp_url_folder']."/".$setup['photos_folder']."/".$date['date_link']."/".$date['date_mini']."\" style=\"max-width: 25px; max-height: 25px; float: left; margin: 0 6px 0 0;\">"; 
				}
				print "<a href=\"".$setup['temp_url_folder']."/".$setup['photos_folder']."/".$date['date_link']."/\" title=\"View on website\" target=\"_blank\">".$date['date_title']."</a>";
			}
		}
		if($pginfo[0] == "photocart") {
//			print $page['page_viewed'];
			if(!empty($pginfo[2])) { 
				if($pginfo[2] == "gallery") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[5])) { print $pginfo[5]." > "; } print " <a href=\"/".$pginfo[1]."/index.php?do=photocart&viewGallery=".$pginfo[3]."\" target=\"_blank\">".$pginfo[4]."</a>";
				}
				if($pginfo[2] == "favs") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > ".$pginfo[3]." > Favorites";
				}
				if($pginfo[2] == "accessories") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Accessory Store ";
				}
				if($pginfo[2] == "giftCertificates") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Gift Certificates ";
				}



				if($pginfo[2] == "registry") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Registry ";
				}
				if($pginfo[2] == "help") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Help ";
				}
				if($pginfo[2] == "checkout") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Check Out ";
				}
				if($pginfo[2] == "cart") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "View Cart ";
				}
				if($pginfo[2] == "dp") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "Design Proof ";
				}
				if($pginfo[2] == "myPictures") { 
					print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a> > "; if(!empty($pginfo[3])) { print $pginfo[3]." > "; } print "My Pictures ";
				}
			} else { 
				print "<a href=\"/".$pginfo[1]."/\">Photo Cart</a>";
			}
		}
		}

		if($pginfo[0] == "community") {
			if(!empty($pginfo[2])) { 
				print "Community: <a href=\"/community/forums/".$pginfo[1]."/index.php?see=viewTopic&topic=".$pginfo[3]."\" target=\"_blank\">".$pginfo[2]."</a>";
			} elseif(!empty($pginfo[1])) { 
				print "Community: <a href=\"/community/forums/".$pginfo[1]."/\" target=\"_blank\">".$pginfo[1]."</a>";
			} else { 
				print "Community";
			}
		}
		print "<div class=\"muted small\"><a href=\"index.php?do=stats&action=visitordetails&pv_ref_id=".$page['pv_ref_id']."\">".$page['st_ip']."</a>   ".$page['pv_date']." : ".$page['pv_time']."</div>";
}



function nextprev($req, $setting, $mem, $total_results, $page, $per_page,  $NPvars, $what) {
	print "<table cellpadding=4 cellspacing=0 border=0><tr><td class=cells>";
	print "<b>$total_results total $what.</b></td>";
	if(empty($page)) {	$page = "1";		}
	$vw1 = ($page * $per_page) - $per_page + 1; 
	$vw2 = $vw1 + ($per_page - 1);
	if($page * $per_page > $total_results) {
		$vw2 = (($page - 1) * $per_page) + ($total_results - (($page - 1) * $per_page));
	}
	foreach($NPvars AS $vari) {
		$qstring .= "&$vari";
	}

	print "<td class=cells>Viewing $vw1 - $vw2.  Pages: </td>";

	$pages = floor($total_results / $per_page + 1);

	if($page < 4) {
		$np = 1;
	} else {
		$np = $page - 2;
	}
	if(($page > $pages - 2) && ($pages > 5)==true) {
		$np = $page - 3;
	}
	if($np > 1) {
		print "<td class=cells><a href=\"".$_SERVER['PHP_SELF']."?pg=1"."$qstring\" title=\"Go to first page\">First</a></td>";
	}
		if($page > 1) {
		$prev = $page - 1;
		print "<td class=cells><a href=\"".$_SERVER['PHP_SELF']."?pg=$prev" . "$qstring\" title=\"Previous Page\"> &laquo; </a></td>";
	} else {
		print "<td class=cells> &laquo; </td>";
	}

	$pct = 1;
	while($np  < $total_results / $per_page + 1  AND $pct <= 5) {
		if($np == $page) {
			print  "<td class=cells><B>$np</B></td>" ;
		} else {
			print  "<td class=cells><a href=\"".$_SERVER['PHP_SELF']."?pg=$np"."$qstring\" title=\"Page # $np\">$np</a></td>" ;
		}
		$np++;
		$pct++;
	}
	if($page < $total_results / $per_page ) {
		$next = $page + 1;
		print "<td class=cells><a href=\"".$_SERVER['PHP_SELF']."?pg=$next"."$qstring\" title=\"Next Page\"> &raquo; </a></td>";
	} else {
		print "<td class=cells>&nbsp;&raquo;&nbsp;</td>";
	}
	if($page < $pages - 2) {
		print "<td class=cells><a href=\"".$_SERVER['PHP_SELF']."?pg=$pages"."$qstring\" title=\"Go to last page\">Last</a></td>";
	}


	print "</tr></table>";
}




function editField($d) {
	global $setup;
	if($d['field_type'] == "text") {
		// This is for focus / select all of a field
		if(!empty($d['this_table_field'])) {
			$fn = "".$d['table_field']."_".$d['table_id_val']."";
		} else {
			$fn = $d['table_field'];
		}
	}
	$html .= "<div id=\"main_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\">";
	$html .= "<div id=\"efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" onclick=\"selectFieldEdit('efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','$fn'); return false;\" onmouseover=\"this.className='fieldEditOn'\" onmouseout=\"this.className='fieldEdit'\" class=fieldEdit title=\"Click to edit\">";
	if(empty($d['current_data'])) {
		$html .= "<span class=\"clickAddFieldEdit\"><i>click to add</i></span>";
	} else {
		if(!empty($d['add_before_field'])){
			$html .= $d['add_before_field'];
		}

		$html .= $d['current_data'];
		if(!empty($d['add_after_field'])){
			$html .= $d['add_after_field'];
		}
	}
	$html .= "</div>";


	$html .= "<div id=\"efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" style=\"display: none; \">";
	$form_id = "eff_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."";

	foreach($d AS $f => $v) {
//		print "$f => $v ";
		$add_to_ajax .= "$form_id"."_"."$f,";
	}
	$add_to_ajax .= "".$d['table_field']."_".$d['table_id_val'].",";

	// $html .= "$add_to_ajax";
	$html .= "<form class=noFormPad name=\"eff_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" id=\"f_"."$form_id\" method=\"post\"  action=\"javascript:updateFormField('packagecart".$cart['cart_id']."','".$add_to_ajax."".$d['table_field'].",!id_$form_id','main_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."');\">\r";


	$html .= "<input type=\"hidden\" name=\"!id_$form_id\"  id=\"!id_$form_id\" value=\"!form_id!\">\r";

	foreach($d AS $f => $v) {
		if($f !== "current_data") {
			$html .= "<input type=\"hidden\" name=\"$f\" id=\"$form_id"."_"."$f\" value=\"$v\">\r";
		}
	}

	if($d['field_type'] == "text") {
		if(!empty($d['this_table_field'])) {
			$html .= "<input type=\"text\" name=\"".$d['table_field']."_".$d['table_id_val']."\"  id=\"".$d['table_field']."_".$d['table_id_val']."\" value=\"".htmlspecialchars(stripslashes($d['current_data']))."\" size=\"".$d['field_size']."\" class=fieldEditEdit style=\"width: 90%;\">\r";
		} else {
			$html .= "<input type=\"text\" name=\"".$d['table_field']."\"  id=\"".$d['table_field']."\" value=\"".htmlspecialchars(stripslashes($d['current_data']))."\" size=\"".$d['field_size']."\" class=fieldEditEdit style=\"width: 90%;\">\r";
		}
	}

	if($d['field_type'] == "textarea") {
		if(!empty($d['this_table_field'])) {
			$html .= "<textarea name=\"".$d['table_field']."_".$d['table_id_val']."\"  id=\"".$d['table_field']."_".$d['table_id_val']."\"  class=fieldEditEdit  rows=\"".$d['field_size']."\" cols=\"30\" style=\"width: 100%;\">".htmlspecialchars(stripslashes($d['current_data']))."</textarea>\r";
		} else {
			$html .= "<textarea name=\"".$d['table_field']."\"  id=\"".$d['table_field']."\" class=fieldEditEdit  rows=\"".$d['field_size']."\" cols=\"30\" style=\"width: 100%;\">".htmlspecialchars(stripslashes($d['current_data']))."</textarea>\r";
		}
	}



	if($d['field_type'] == "timdropdownstates") {
		$ddm = $d['table_field'];
		$ddmm_size = $d['field_size'];
		$ddmdiv = $d['table_field']."div";
		$ddmo = $ddm."o";
		$ddmm_title = $d['current_data'];
		// $ddmm_title_link = "manage.php?do=reports";
		// $ddmm_var_link = "manage.php?do=photocart&action=galleries&gal_id=[VAR]"; // this is the link when adding a variable. add [VAR] where ID should go
		$ddmm_field_name =  $d['table_field'];
		$default_first = $var['default_first'];
		$allow_other = $d['allow_other'];
		$allow_other_name = $d['allow_other_name'];
		$ddmm_links = array();
		$ddmm_options = array();
		$cts = whileSQL("ms_countries", "*", "WHERE ship_to='1' ORDER BY def DESC, country_name ASC");
		while($ct = mysqli_fetch_array($cts)) {
			$states = whileSQL("ms_states", "*", "WHERE state_country='".$ct['country_name']."' ORDER BY state_name ASC ");
			while($state = mysqli_fetch_array($states)) {
				array_push($ddmm_options, array($state['state_abr'] => $state['state_name']));
			}
		}
		$html .= "<div style=\"clear: both; float: left;\">";
		 $html .= makeDDMenu($ddm,$ddmo,$ddmm_title,$ddmm_title_link,$ddmm_links,$ddmm_field_name,$ddmm_options,$ddmm_var_link,$ddmdiv,$ddmm_size,$default_first,$link_target,$allow_other,$allow_other_name);
		 unset($default_first);
		 $html .= "</div> ";
	}

	if($d['field_type'] == "timdropdown") {
		$ddm = $d['table_field'];
		$ddmm_size = $d['field_size'];
		$ddmdiv = $d['table_field']."div";
		$ddmo = $ddm."o";
		$ddmm_title = $d['current_data'];
		// $ddmm_title_link = "manage.php?do=reports";
		// $ddmm_var_link = "manage.php?do=photocart&action=galleries&gal_id=[VAR]"; // this is the link when adding a variable. add [VAR] where ID should go
		$ddmm_field_name =  $d['table_field'];
		$default_first = $d['default_first'];
		$allow_other = $d['allow_other'];
		$allow_other_name = $d['allow_other_name'];
		$ddmm_links = array();
//		print "<li>"; print_r($d['ddmm_options']);
		$html .= "<div style=\"clear: both; float: left;\">";
		 $html .= makeDDMenu($ddm,$ddmo,$ddmm_title,$ddmm_title_link,$ddmm_links,$ddmm_field_name,$d['ddmm_options'],$ddmm_var_link,$ddmdiv,$ddmm_size,$default_first,$link_target,$allow_other,$allow_other_name);
		 unset($default_first);
		 $html .= "</div> ";
	}


	$html .= "<input type=\"hidden\" name=\"".$d['table_id_val']."\"  id=\"$form_id"."_".$d['table_id_val']."\" value=\"".$d['table_id_val']."\">\r";

	$html .= "<input type=\"hidden\" name=\"faction\" value=\"update\">\r";
	$html .= " <input type=\"image\" name=\"submit\" src=\"".ai_field_update."\" style=\"vertical-align: middle;\" title=\"Save\"> <span  style=\"vertical-align: middle;\" class=cancelFieldEdit  onclick=\"selectFieldEdit('efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."',''); return false;\">cancel</span>";
	if($d['field_type'] == "checkboxes") {
		// Adding closing div to checkboxes so controls can be in cell
		$html .= "</div>";
	}
	$html .= "</form>";
	$html .= "</div>";
	$html .= "</div>";
	return  $html;
}

function tcregck() { 
$reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 2 HOUR), '%M %e, %Y ')  AS reg_date", "");
if((empty($reg['reg_key']))&&($_REQUEST['do']!=="register")==true) { session_write_close(); header("location: index.php?do=register&md=1"); die(); } 
if((MD5($reg['reg_key'])!==$reg['reg_keys'])&&($_REQUEST['do']!=="register")==true) { die("<div class=error>There is an error with the registration of this product. Please contact support at <a href=\"mailto:info@picturespro.com\">info@picturespro.com</a></div>"); }	
 } 

function editFieldCheckboxes($d) {
	global $setup;
// table_select_from=".$d['table_select_from']."&table_select_from_id=".$d['table_select_from_id']."&table_select_from_show=".$d['table_select_from_show']."&table_entry=".$d['table_entry']."&table_entry_select=".$d['table_entry_select']."&table_entry_this=".$d['table_entry_this']."&table_entry_this_id=".$d['table_entry_this_id']."

	$html .= "<div id=\"main_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" >";
	$html .= "<div id=\"efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" onclick=\"selectFieldEdit('efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','$fn'); return false;\" onmouseover=\"this.className='fieldEditOn'\" onmouseout=\"this.className='fieldEdit'\" class=fieldEdit>";
	if(empty($d['current_data'])) {
		$html .= "<span class=\"clickAddFieldEdit\"><i>click to add</i></span>";
	} else {
		$html .= $d['current_data'];
	}
	$html .= "</div>";


	$html .= "<div id=\"efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" style=\"display: none; \">";
	$form_id = "eff_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."";

	// $html .= "$add_to_ajax";

		$html .= "<div class=\"checkboxwindow\" >";
		$opts = whileSQL("".$d['table_select_from']." ","*", "ORDER BY ".$d['table_select_from_show']." ASC");
		while($opt = mysqli_fetch_array($opts)) {
			$html .= "<div class=cells>";
			if(countIt("".$d['table_entry']."", "WHERE ".$d['table_entry_this']."='".$d['table_entry_this_id']."' AND ".$d['table_entry_select']."='".$opt[$d['table_select_from_id']]."' ")>0) { 
				$html .=   "<div  id=$form_id-".$opt[$d['table_select_from_id']]." style=\"display: block; clear: right; position: relative;\"><a href=\"javascript:ajaxpage('field.edit.php?eAction=checkbox&remove=".$opt[$d['table_select_from_id']]."&table_select_from=".$d['table_select_from']."&table_select_from_id=".$d['table_select_from_id']."&table_entry=".$d['table_entry']."&table_entry_select=".$d['table_entry_select']."&table_entry_this=".$d['table_entry_this']."&table_entry_this_id=".$d['table_entry_this_id']."&".$d['table_entry_this']."=".$d['table_entry_this_id']."&table_select_from_show=".cleanJsQuotes($opt[$d['table_select_from_show']])."&form_id=".$form_id."&table_select_from_id=".$d['table_select_from_id']."', '$form_id-".$opt[$d['table_select_from_id']]."');\" class=ahover>".ai_checkbox_on."</a> ".$opt[$d['table_select_from_show']]."</div>";
			} else {
				$html .=   "<div  id=$form_id-".$opt[$d['table_select_from_id']]." style=\"display: block; clear: right; position: relative;\"><a href=\"javascript:ajaxpage('field.edit.php?eAction=checkbox&add=".$opt[$d['table_select_from_id']]."&table_select_from=".$d['table_select_from']."&table_select_from_id=".$d['table_select_from_id']."&table_entry=".$d['table_entry']."&table_entry_select=".$d['table_entry_select']."&table_entry_this=".$d['table_entry_this']."&table_entry_this_id=".$d['table_entry_this_id']."&".$d['table_entry_this']."=".$d['table_entry_this_id']."&table_select_from_show=".cleanJsQuotes($opt[$d['table_select_from_show']])."&form_id=".$form_id."&table_select_from_id=".$d['table_select_from_id']."', '$form_id-".$opt[$d['table_select_from_id']]."');\" class=ahover>".ai_checkbox_off."</a> ".$opt[$d['table_select_from_show']]."</div>";
			}
			$html .= "</div>";


//			$html .= "<input type=\"checkbox\" name=\"".$d['table_entry_select']."[]\" value=\"".$opt[$d['table_select_from_id']]."\" "; if(countIt("".$d['table_entry']."", "WHERE ".$d['table_entry_this']."='".$d['table_entry_this_id']."' ")>0) { $html .= "checked "; } $html .= ">".$opt[$d['table_select_from_show']]."</div>";
		}
//	 doneEditCheckbox('open' 'close' 'ajaxlink' 'ajaxfield');
	$html .= "<div style=\"vertical-align: middle; text-align: center;\">";
	if($d['allow_other']) {
		$html .= "<div id=\"main_new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" >";
		$html .= "<div id=\"new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\"   class=fieldEdit>";
		$html .= "<a href=\"javascript:selectFieldEdit('new_open_efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."_new_cat');\"  class=\"doneEditCheckbox\">".$d['allow_other_name']."</a>";
		$html .= "</div>";


		$html .= "<div id=\"new_open_efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" style=\"display: none; \">";
		$html .= "<form class=noFormPad name=\"new_eff_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."\" id=\"f_"."$form_id\" method=\"post\"  action=\"javascript:doneNewCheckbox('efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','field.edit.php?eAction=newcheckbox&table_select_from=".$d['table_select_from']."&table_select_from_id=".$d['table_select_from_id']."&table_entry=".$d['table_entry']."&table_entry_select=".$d['table_entry_select']."&table_entry_this=".$d['table_entry_this']."&table_entry_this_id=".$d['table_entry_this_id']."&".$d['table_entry_this']."=".$d['table_entry_this_id']."&table_select_from_show=".$d['table_select_from_show']."&form_id=".$form_id."&new_field=new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."_new_cat&table_select_from_id=".$d['table_select_from_id']."&allow_other=".$d['allow_other']."&allow_other_name=".$d['allow_other_name']."','main_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."_new_cat');\">\r";
		$html .= "<input type=\"text\" name=\"new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."_new_cat\" id=\"new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."_new_cat\" value=\"enter new category\" size=\"12\">";
		$html .= " <input type=\"image\" name=\"submit\" src=\"".ai_field_update."\" style=\"vertical-align: middle;\"> <span  style=\"vertical-align: middle;\" class=cancelFieldEdit  onclick=\"selectFieldEdit('new_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','new_open_efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."',''); return false;\">cancel</span>";

		$html .= "</form>";

		$html .= "</div>";
		$html .= "</div>";
	}


	$html .= "<span  style=\"vertical-align: middle; text-align: center;\"><a class=doneEditCheckbox   href=\"javascript:doneEditCheckbox('efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','efe_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."','field.edit.php?eAction=donecheckbox&table_select_from=".$d['table_select_from']."&table_select_from_id=".$d['table_select_from_id']."&table_entry=".$d['table_entry']."&table_entry_select=".$d['table_entry_select']."&table_entry_this=".$d['table_entry_this']."&table_entry_this_id=".$d['table_entry_this_id']."&".$d['table_entry_this']."=".$d['table_entry_this_id']."&table_select_from_show=".$d['table_select_from_show']."&form_id=".$form_id."&table_select_from_id=".$d['table_select_from_id']."&allow_other=".$d['allow_other']."&allow_other_name=".$d['allow_other_name']."','main_efd_".$d['table']."_".$d['table_field']."_".$d['table_id_val']."'); \">[done]</a></span>";
	$html .= "</div>";
	$html .= "</div>";
	$html .= "</div>";
	$html .= "</div>";
	return $html;
}

function doCustomerCats() {
	if(!empty($_REQUEST['cust_cat-textinput'])) {
		$cat_id = insertSQL("office_customer_cats", "cat_name='".sqlslash($_REQUEST['cust_cat-textinput'])."' ");
	} else {
		$cat_id = $_REQUEST['cust_cat'];
	}
	insertSQL("office_customer_cats_entry", "entry_cat='$cat_id', entry_customer='".$_REQUEST['cust_id']."' ");
}


/**
 * "Latinize" a String.
 * @param $String String to process
 * @return "Latinized" String
 */
 $CHARSET = "CP1252";
function latinize($string) {
    global    $CHARSET;
    // assume String is not UTF-8 
	/*
    $result = $string;
 
    // if String is UTF-8 AND can be converted ...
    if(preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]            # ASCII
          | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
          |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
          | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
          |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
          |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
          | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
          |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            )*$%xs', $string) && function_exists("iconv")) {
 
        // ... convert the String
        $result = iconv("UTF-8", "$CHARSET//IGNORE//TRANSLIT", $string);  
    }
   
    // return the String
	*/ 
	// return $result;
	return $string;

}

function addNewCatDefaults($cat_id) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$cat_id."' ");
	if(!empty($cat['cat_under'])) { 
		$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$cat['cat_under']."' ");
	}
	if(empty($def['def_id'])) { 
		$def = doSQL("ms_defaults", "*", "ORDER BY def_id ASC");
	}

	insertSQL("ms_defaults", "
			blog_location='".$def['blog_location']."',
			def_type='".$def['def_type']."',
			blog_contain='".$def['blog_contain']."',
			blog_seconds='".$def['blog_seconds']."',
			blog_enlarge='".$def['blog_enlarge']."',
			blog_kill_side_menu='".$def['blog_kill_side_menu']."',
			blog_progress_bar='".$def['blog_progress_bar']."',
			blog_next_prev='".$def['blog_next_prev']."',
			blog_play_pause='".$def['blog_play_pause']."',
			blog_slideshow='".$def['blog_slideshow']."',
			blog_slideshow_auto_start='".$def['blog_slideshow_auto_start']."',
			disable_controls='".$def['disable_controls']."',
			caption_location='".$def['caption_location']."',
			contain_width='".$def['contain_width']."',
			contain_height='".$def['contain_height']."',
			noupsize='".$def['noupsize']."',
			blog_frame='".$def['blog_frame']."',
			disable_thumbnails='".$def['disable_thumbnails']."',
			disable_help='".$def['disable_help']."',
			disable_animation_bar='".$def['disable_animation_bar']."',
			disable_photo_count='".$def['disable_photo_count']."',
			transition_time='".$def['transition_time']."',
			blog_photo_file='".$def['blog_photo_file']."',
			bg_use='".$def['bg_use']."',
			disable_play_pause='".$def['disable_play_pause']."',
			disable_next_previous='".$def['disable_next_previous']."',
			disable_photo_slider='".$def['disable_photo_slider']."',
			thumb_style='".$def['thumb_style']."',
			thumb_type='".$def['thumb_type']."',
			max_photo_display_width='".$def['max_photo_display_width']."',
			slideshow_fixed_height='".$def['slideshow_fixed_height']."',
			thumb_scroller_open='".$def['thumb_scroller_open']."',
			social_share='".$def['social_share']."',
			jthumb_height='".$def['jthumb_height']."',
			jthumb_margin='".$def['jthumb_margin']."',

			def_cat_id='".$cat['cat_id']."' ");
}

function updateSiteMap() { 
	global $setup;

	$html .= '<?xml version="1.0" encoding="UTF-8"?>
		';
	$html .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	';
	$html .='	<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].'</loc>
		</url>

		';
	 $cats = whileSQL("ms_blog_categories", "*", "ORDER BY cat_name ASC ");
	while($cat = mysqli_fetch_array($cats)) {

		$html .='<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].''.$cat['cat_folder'].'/</loc>
		</url>
		
		';

	}
	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_public='1' AND private='0' AND page_404='0' AND page_home='0' ORDER BY date_id DESC ");
		while($date = mysqli_fetch_array($dates)) { 
		$html .='<url>
				<loc>'.$setup['url'].$setup['temp_url_folder'].''.$date['cat_folder'].'/'.$date['date_link'].'/</loc>
			</url>

			';

		}
	 $tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) {
			$html .='<url>
				<loc>'.$setup['url'].$setup['temp_url_folder'].'/tags/'.$tag['tag_folder'].'/</loc>
			</url>

			';

		}
	$html .='</urlset>';
	$fp = @fopen("".$setup['path']."/sy-sitemap.xml", "w");
	@fputs($fp, "$html");
	@fclose($fp);
}


function getAdminCoupon($cart_client,$mssess,$discount_total) { 
	if(!empty($cart_client)) {
		$pcart = doSQL("ms_cart", "*", "WHERE cart_client='".$cart_client."' AND cart_order<='0'  AND cart_coupon!='0' " );
	} else { 
		$pcart = doSQL("ms_cart", "*", "WHERE cart_session='".$mssess."'  AND cart_order<='0'  AND cart_coupon!='0' " );
	}

	if(!empty($pcart['cart_id'])) {
		$promo = doSQL("ms_promo_codes", "*", "WHERE code_id='".$pcart['cart_coupon']."'  ");
		$dis = doSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$promo['code_id']."' AND dis_from<='".$discount_total."' AND dis_to>='".$discount_total."' ");
		if($dis['dis_flat'] > 0) { 
			$discount['promo_discount_amount'] = $dis['dis_flat'];
		}
		if($dis['dis_percent'] > 0) { 
			$discount['promo_discount_amount'] =  round((($discount_total * $dis['dis_percent'])/100),2);
			$discount['promo_percentage'] = $dis['dis_percent'];
		}
		$discount['promo_id'] = $promo['code_id'];
		$discount['promo_name'] = $promo['code_name'];
		$discount['promo_code'] = $promo['code_code'];

	}
	return $discount;
}



function homeShoppingCartTotal($cart_client, $mssess) {
	global $site_setup;
	if(!empty($cart_client)) {
		$icarts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE cart_client='".$cart_client."' AND cart_order<='0' ORDER BY cart_id DESC" );
	} else {
		$icarts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE cart_session='".$mssess."' AND cart_order<='0' ORDER BY cart_id DESC" );
	}
	while($icart= mysqli_fetch_array($icarts)) {
		if($icart['cart_store_product'] > 0) { 
			$price = productPrice($icart);
			$this_price = $price['price'];
			if(!empty($icart['cart_sub_id'])) { 
				$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$icart['cart_sub_id']."' ");
				$this_price = $this_price + $sub['sub_add_price'];
			}
		} else { 
			$this_price = $icart['cart_price'];
		}
		if($icart['cart_credit'] > 0) { 
			$cart_credit = $cart_credit + $icart['cart_credit'];
		}
		if($icart['cart_photo_prod'] > 0) {
			$photo_prods = $photo_prods + ($icart['cart_qty'] * $icart['cart_price']);
			if($icart['cart_taxable'] == "1") {
				$photo_prods_taxable = $photo_prods_taxable + ($icart['cart_qty'] * $icart['cart_price']);
			}
		}

		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$icart['cart_id']."' ");
		while($co = mysqli_fetch_array($cos)) {
			if($co['co_price'] > 0) {  $this_price = $this_price + $co['co_price']; } 
		} 
		if(($icart['cart_package_photo']<=0)&&($icart['cart_product_photo']<=0)==true) { 
			$total_items  = $total_items+  $icart['cart_qty'];
			$add_ship = $add_ship + $icart['cart_extra_ship'];
		}

		$itotal  = round($this_price  * $icart['cart_qty'], 2);
		if($icart['cart_taxable'] == "1") {
			$tax_total = $tax_total + $itotal;
		}
		if($icart['cart_ship'] == "1") {
			$ship_cal_total = $ship_cal_total + $itotal;
		}
		$sub_total = $sub_total + $itotal;
		$sub_total = $sub_total + $io_total;
		if($icart['cart_no_discount'] !=="1") { 
			$discount_total = $discount_total + $itotal;
			$discount_total = $discount_total + $io_total;
		}
	}


	if($cart_credit > 0) { 
		if($photo_prods > $cart_credit) {
			$credit = $cart_credit;
		} else { 
			$credit = $photo_prods;
		}
		$sub_total = $sub_total - $credit;

		if($photo_prods_taxable > $cart_credit) {
			$credit = $cart_credit;
		} else { 
			$credit = $photo_prods_taxable;
		}
		$tax_total = $tax_total - $credit;

	}
	
//	$vat = checkVat($tax_total);
//	$discount_total = $sub_total;
	$discount = getAdminCoupon($cart_client,$mssess,$discount_total);
	$total['promo_discount_amount'] = $discount['promo_discount_amount'];

	$total['promo_percentage'] = $discount['promo_percentage'];
	$total['photo_prods'] = $photo_prods;
	$total['cart_credit'] = $cart_credit;
	$total['discount_total'] = $discount_total;
	$total['show_cart_total'] = ($sub_total + $vat) - $total['promo_discount_amount'];
	$total['total'] = $sub_total;
	$total['vat'] = $vat;
	$total['sub_total'] = $sub_total- $total['promo_discount_amount'];
	$total['tax_total'] = $tax_total;
	$total['add_ship'] = $add_ship;
	$total['ship_cal_total'] = $ship_cal_total;
	$total['total_no_ship'] = $total_no_ship;
	$total['total_items'] = $total_items;
//	print "<li>".$total['total_items']."";

	return $total;
}



function optimizeDatabaseTables() {
	global $dbcon;
	$result = mysqli_query($dbcon,"SHOW TABLE STATUS ");
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row["Data_free"] > 0) {
			// print "<li>Optimized table: ".$row['Name']." - ".$row["Data_free"];
			mysqli_query($dbcon,"OPTIMIZE TABLE ".$row['Name']."");
		}
	}
}

function addemailtocron($to_email,$to_first_name,$to_last_name,$from_email,$from_name,$subject,$message,$priority) { 
	global $setup,$site_setup;
	insertSQL("ms_cron_emails", "
	to_email='".addslashes(stripslashes($to_email))."', 
	to_name='".addslashes(stripslashes($to_first_name." ".$to_last_name))."', 
	from_email='".addslashes(stripslashes($from_email))."', 
	from_name='".addslashes(stripslashes($from_name))."', 
	subject='".addslashes(stripslashes($subject))."', 
	content='".addslashes(stripslashes($message))."', 
	date_time_to_send='".addslashes(stripslashes($date_time_to_send))."', 
	from_date_id='".addslashes(stripslashes($date_id))."', 
	from_book_id='".addslashes(stripslashes($book_id))."', 
	from_email_id='".addslashes(stripslashes($email_id))."', 
	priority='".addslashes(stripslashes($priority))."'
	");
}



function addEditorOld($field, $num, $height, $header) {  
	global $setup, $site_setup,$email_style;
	if($email_style == true) { 
		$style_sheet = "/".$setup['manage_folder']."/css/plain.css";
	} else { 
		$style_sheet = $setup['temp_url_folder']."/sy-style.php?csst=".$site_setup['css']."&admin_edit=1&header=$header";
	}
	?>
	<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$site_setup['css']."' ORDER BY font ASC ");
	if(mysqli_num_rows($fonts) > 0) { 
	while($font = mysqli_fetch_array($fonts)) { 
	if($f > 0) { 
	$add_fonts .= "|";
	}
	$f = explode(":",$font['font']);
	$add_fonts .=  str_replace(" ","+",$font['font']);
	$editor_fonts .= "\"".$f[0]."\",";
	$f++;
	}
	?>
	<?php } ?>


	<script language="javascript" type="text/javascript">
	var oEdit<?php print $num;?> = new InnovaEditor("oEdit<?php print $num;?>");
	oEdit<?php print $num;?>.width = "100%";
	oEdit<?php print $num;?>.height = <?php print $height;?>;
	oEdit<?php print $num;?>.groups = [
	["group1", "", ["Bold", "Italic", "Underline", "FontName", "ForeColor", "FontSize", "Paragraph", "RemoveFormat"]],
	["group2", "", ["Bullets", "Numbering", "JustifyLeft", "JustifyCenter", "JustifyRight", "Indent", "Outdent"]],
	["group3", "", ["LinkDialog","InternalLink",  "ImageDialog", "TableDialog", "Emoticons"]],
	["group4", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
	];
	oEdit<?php print $num;?>.fileBrowser = "<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/assetmanager/asset.php";
	oEdit<?php print $num;?>.css="<?php print $style_sheet;?>";
	oEdit<?php print $num;?>.css = ["<?php print $style_sheet;?>","http://fonts.googleapis.com/css?family=<?php print $add_fonts;?>"];
	oEdit<?php print $num;?>.enableFlickr = false;
	oEdit<?php print $num;?>.enableCssButtons = true;
	oEdit<?php print $num;?>.cmdInternalLink = "modalDialog('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/scripts/common/weblocallink.php',600,450)";

	oEdit<?php print $num;?>.arrFontName = [<?php print $editor_fonts; ?>"Impact, Charcoal, sans-serif", "Palatino Linotype, Book Antiqua, Palatino, serif",
	"Tahoma, Geneva, sans-serif", "Century Gothic, sans-serif",
	"Lucida Sans Unicode, Lucida Grande, sans-serif",
	"Times New Roman, Times, serif", "Arial Narrow, sans-serif",
	"Verdana, Geneva, sans-serif", "Copperplate Gothic Light, sans-serif",
	"Lucida Console, Monaco, monospace", "Gill Sans MT, sans-serif",
	"Trebuchet MS, Helvetica, sans-serif", "Courier New, Courier, monospace",
	"Arial, Helvetica, sans-serif", "Georgia, Serif", "Garamond, Serif"
	];

	oEdit<?php print $num;?>.REPLACE("<?php print $field;?>");
	</script>
<?php } 


function archiveordertable($order) { 
	global $setup,$dbcon;
	$pass = false;
	if($order['order_id'] > 0) { 
		$booking = doSQL("ms_cart","*","WHERE cart_booking>'0' AND cart_order='".$order['order_id']."' ");
		if($booking['cart_id'] <= 0) { 

			$carts = whileSQL("ms_cart", "*", "WHERE cart_order='".$order['order_id']."' ORDER BY cart_id ASC");
			while($cart = mysqli_fetch_array($carts)) {
				$chk = doSQL("ms_cart_archive","*","WHERE cart_id='".$cart['cart_id']."' ");
				if($chk['cart_id'] > 0) { 
					$pass = true;
				}
			}


			if($pass !== true) { 
				$carts = whileSQL("ms_cart", "*", "WHERE cart_order='".$order['order_id']."' ORDER BY cart_id ASC");
				while($cart = mysqli_fetch_array($carts)) {
					$lqry  = "";
					$x = 0;
					$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_cart");
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							if($x > 0) { $lqry.=","; } 
							$x++;
							$lqry .= $row['Field']."='".addslashes(stripslashes($cart[$row['Field']]))."' ";
							// print "<li>".$row['Field']." = ".$cart[$row['Field']]."</li>";
							if($row['Field'] == "cart_id") { 
								$cart_id = $cart[$row['Field']];
							}
						}
					}
				// $lqry .= ",frame_style='".addslashes(stripslashes($_REQUEST['copysizes']))."' ";
					$id = insertSQL("ms_cart_archive", "$lqry" );
					deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
					updateSQL("ms_orders", "order_archive_table='1' WHERE order_id='".$order['order_id']."' ");
				}
				return "moved";
			} else { 
			}
		} else { 
			return "skipped";
		}
	}
}
?>