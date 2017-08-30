<?php 
require("../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require("../".$setup['inc_folder']."/functions.php");
$dbcon = dbConnect($setup);
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = trim("".$_REQUEST[$id]."");
		}
	}
}
if(!empty($_REQUEST['pagepass'])) { 
	$_REQUEST['pagepass'] = strtolower(urldecode($_REQUEST['pagepass']));
	$ck = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE LOWER(password)='".strtolower($_REQUEST['pagepass'])."' ");
	if(empty($ck['date_id'])) { 
		$ck =  doSQL("ms_sub_galleries LEFT JOIN ms_calendar ON ms_sub_galleries.sub_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE  sub_pass='".$_REQUEST['pagepass']."' ");
	}
	if(empty($ck['date_id'])) { 
		if($photo_setup['passcode_photos_find'] == "title") { 
			$and_find = "LOWER(pic_title)='".$_REQUEST['pagepass']."' ";
		}
		if($photo_setup['passcode_photos_find'] == "file") { 
			$findpass = "-".strtolower(urldecode($_REQUEST['pagepass']))."-";
			$and_find = " LOWER(pic_org) LIKE '%".$findpass."%' ";
		}
		if($photo_setup['passcode_photos_find'] == "keyword") { 
			$and_find = " (LOWER(pic_keywords)='".$_REQUEST['pagepass'].",' OR LOWER(pic_keywords)='".$_REQUEST['pagepass']."') ";
		}

		if($photo_setup['passcode_photos_find'] == "filename") { 
			$findpass = strtolower(urldecode($_REQUEST['passcode']));
			$and_find = " LOWER(pic_org) LIKE '%".$findpass."%' ";
		}


		$pic = doSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_blog_photos.bp_blog=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE $and_find AND passcode_photos='1' ORDER BY date_id DESC ");
		if(empty($pic['pic_id'])) { 
			print "bad";
		} else { 
			$_SESSION['passcode'] = $_REQUEST['pagepass'];
			$_SESSION['passcode_did'] = $pic['date_id'];
			if($pic['bp_sub'] > 0) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$pic['bp_sub']."' ");
				$_SESSION['passcode_sid'] = $sub['sub_id'];
				print $setup['temp_url_folder']."".$setup['content_folder']."".$pic['cat_folder']."/".$pic['date_link']."/?sub=".$sub['sub_link']."";
			} else { 
				print $setup['temp_url_folder']."".$setup['content_folder']."".$pic['cat_folder']."/".$pic['date_link']."/";
			}
		}
	} else { 
		if(!is_array($_SESSION['privateAccess'])) {
			$_SESSION['privateAccess'] = array();
		}
		if(!empty($ck['sub_id'])) { 
			array_push($_SESSION['privateAccess'], "sub".$ck['sub_id']);
		} else { 
			array_push($_SESSION['privateAccess'], $ck['date_id']);
		}
		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$ck['date_id']."' AND mp_sub_id='".$ck['sub_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
				insertSQL("ms_my_pages", "mp_date_id='".$ck['date_id']."' , mp_sub_id='".$ck['sub_id']."', mp_people_id='".$person['p_id']."', mp_date=NOW() ");
			}
		}
		if(!empty($ck['sub_link'])) { 
			print $setup['temp_url_folder']."".$setup['content_folder']."".$ck['cat_folder']."/".$ck['date_link']."/?sub=".$ck['sub_link'];
		} else { 
			print $setup['temp_url_folder']."".$setup['content_folder']."".$ck['cat_folder']."/".$ck['date_link']."/";
		}
	}
}
?>