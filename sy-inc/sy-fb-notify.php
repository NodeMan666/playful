<?php
require "../sy-config.php";
session_start();
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
if(!is_referer()) { 
	die();
}
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
//		print "<li>".$id ." => ".$val;
		define($id,$val);
	}
}
if(empty($_SESSION['ms_session'])) {
//	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "Missing session", "Missing the ms_session",$type); 
	exit();
}
if(empty($_REQUEST['ses'])) {
//	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "Missing ses", "Missing the ms_session",$type); 
	exit();
}
if(empty($_REQUEST['link'])) {
//	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "Missing link", "Missing the ms_session",$type); 
	exit();
}
if(empty($_REQUEST['type'])) {
//	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "Missing type", "Missing the ms_session",$type); 
	exit();
}

if(MD5($_SESSION['ms_session']) !== $_REQUEST['ses']) {
//	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "ms_session does not equal ses", "Missing the ms_session",$type); 
	//  die("Sorry, session didn't happen");
}

/* 
print "<pre>";
print_r($_SESSION);
print "</pre>";
*/

	foreach($_REQUEST AS $id => $value) {
		if(!is_array($value)) {
			$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
		}
		$_REQUEST[$id] = sql_safe(strip_tags("".$_REQUEST[$id].""));
	}

	if($_REQUEST['type'] == "Comment"){
		$subject = "New Comment Posted on ".$setup['url'].$setup['temp_url_folder']." ";
		$message = "Someone has just made a comment via Facebook on the following page on your website: \r\n\r\n".$_REQUEST['link']."\r\n\r\nThis is an automated email, do not reply.";
	}
	if($_REQUEST['type'] == "Like"){
		$subject = "New \"Like\" on ".$setup['url'].$setup['temp_url_folder']." ";
		$message = "Someone has just Facebook \"Liked\" the following page on your website: \r\n\r\n".$_REQUEST['link']."\r\n\r\nThis is an automated email, do not reply.";
	}


?>
 <?php 
 if((!empty($_REQUEST['type']))AND(!empty($_REQUEST['ses']))AND(!empty($_REQUEST['link']))==true) { 
	  sendWebdEmail("".$site_setup['contact_email']."", "".$site_setup['website_title']."", "".$site_setup['contact_email']."", "".$site_setup['website_title']."", "$subject", "$message ",$type); 
}
?>
