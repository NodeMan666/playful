<?php
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require("../../".$setup['inc_folder']."/functions.php");
$dbcon = dbConnect($setup);
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$site_setup = doSQL("ms_settings", "*", "");
$em_settings = doSQL("ms_email_list_settings", "*", "  ");
$fb = doSQL("ms_fb", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

// print "<pre>"; print_r($_SESSION); print "</pre>";
// exit();
include_once($setup['path']."/sy-inc/facebook/config.php");
//destroy facebook session if user clicks reset
if(!$fbuser){
	if(isset($_SESSION['last_gallery'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		header("location: ".$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/");
	} else { 
		header("location: ".$setup['temp_url_folder']."/");
	}
	session_write_close();
	exit();
}else{
	$user = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture,link');
		print "<pre>"; print_r($user);

	if(!empty($user['email'])) { 
		$p = doSQL("ms_people", "*", "WHERE p_email='".$user['email']."' OR p_fb_id='".$user['id']."' ");
	} else { 
		$p = doSQL("ms_people", "*", "WHERE  p_fb_id='".$user['id']."' ");
	}
	// print "<li>id: ".$p['p_id'];
	if(empty($p['p_id'])) { 

		// Create new person
		print "<h2>Creating new user</h2>";
	   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
		$salt = '';
		for ($i = 0; $i < 5; $i++) { 
			$salt .= $characters[mt_rand(0, 39)];
		}

		$id = insertSQL("ms_people","
		p_name='".addslashes(stripslashes($user['first_name']))."',
		p_last_name='".addslashes(stripslashes($user['last_name']))."',
		p_email='".addslashes(stripslashes($user['email']))."',
		p_create_by ='customer',
		p_date='".date('Y-m-d')."',
		p_last_active='".date('Y-m-d H:i:s')."',
		p_ip='".getUserIP()."',
		p_fb_id='".$user['id']."',
		p_fb_link='".addslashes(stripslashes($user['link']))."',
		p_salt='$salt' ");
		$_SESSION['loggedin'] = true;
		$_SESSION['pid'] = MD5($id);
		$_SESSION['fblogin'] = true;
		$_SESSION['fbjustloggedin'] = true;
		$_SESSION['fbfn'] = $user['first_name'];
		$_SESSION['fbln'] = $user['last_name'];

		$time=time()+3600*24*365*2;
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		SetCookie("persid",MD5($id),$time,"/",null);
		SetCookie("hasloggedin",1,$time,"/",null);
		if(isset($_SESSION['last_gallery'])) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
			if($_SESSION['roomview'] == true) { 
				header("location: ".$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/?view=room");
			} else { 
				header("location: ".$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/");
			}
		} else { 
			header("location: ".$setup['temp_url_folder']."/");
		}
		session_write_close();
		exit();

	} else { 
		print "<h2>Logging in existing user</h2>";
		if((!empty($user['email'])) && ($user['email'] !== $p['p_email']) == true) { 
			$and_update = ", p_email='".addslashes(stripslashes($user['email']))."' ";
		}
		if((!empty($user['first_name'])) && ($user['first_name'] !== $p['p_name']) == true) { 
			$and_update = ", p_name='".addslashes(stripslashes($user['first_name']))."' ";
		}
		if((!empty($user['last_name'])) && ($user['last_name'] !== $p['p_last_name']) == true) { 
			$and_update = ", p_last_name='".addslashes(stripslashes($user['last_name']))."' ";
		}
		if((!empty($user['id'])) && ($user['id'] !== $p['p_fb_id']) == true) { 
			$and_update = ", p_fb_id='".addslashes(stripslashes($user['id']))."' ";
		}
		if((!empty($user['link'])) && ($user['link'] !== $p['p_fb_link']) == true) { 
			$and_update = ", p_fb_link='".addslashes(stripslashes($user['link']))."' ";
		}

		updateSQL("ms_people", "p_last_active='".date('Y-m-d H:i:s')."' $and_update WHERE p_id='".$p['p_id']."' ");
		$_SESSION['loggedin'] = true;
		$_SESSION['pid'] = MD5($p['p_id']);
		$_SESSION['fblogin'] = true;
		$_SESSION['fbjustloggedin'] = true;
		$_SESSION['fbfn'] = $user['first_name'];
		$_SESSION['fbln'] = $user['last_name'];

		$time=time()+3600*24*365*2;
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		SetCookie("persid",MD5($p['p_id']),$time,"/",null);
		SetCookie("hasloggedin",1,$time,"/",null);
		if(isset($_SESSION['last_gallery'])) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
			if($_SESSION['roomview'] == true) { 
				header("location: ".$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/?view=room");
			} else { 
				header("location: ".$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/");
			}
		} else { 
			header("location: ".$setup['temp_url_folder']."/");
		}
		session_write_close();
		exit();
	}



}
?>