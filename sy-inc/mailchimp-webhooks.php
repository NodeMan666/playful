<?php 
require("../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require("../".$setup['inc_folder']."/functions.php");
require("../".$setup['inc_folder']."/mail.chimp.functions.php");
$dbcon = dbConnect($setup);
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$site_setup = doSQL("ms_settings", "*", "");
$em_settings = doSQL("ms_email_list_settings", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');


if(empty($_REQUEST['mch'])) { die(); } 
if($_REQUEST['mch'] !== $em_settings['webhook_hash']) { die(); } 

if($_POST['type'] == "unsubscribe") { 
	$em = doSQL("ms_email_list", "*", "WHERE em_email='".$_POST[data][email]."' ");
	if(!empty($em['em_id'])) { 
		updateSQL("ms_email_list", "em_status='2' WHERE em_id='".$em['em_id']."' ");
	}
}
if($_POST['type'] == "upemail") { 
	$ck = doSQL("ms_email_list", "*", "WHERE em_email='".$_POST[data][old_email]."' ");
	if(!empty($ck['em_id'])) { 
		updateSQL("ms_email_list", "em_email='".$_POST[data][new_email]."' WHERE em_id='".$ck['em_id']."' ");
	}
}
if($_POST['type'] == "subscribe") { 
	$em = doSQL("ms_email_list", "*", "WHERE em_email='".$_POST[data][email]."' ");
	if(!empty($em['em_id'])) { 
		updateSQL("ms_email_list", "em_status='0' WHERE em_id='".$em['em_id']."' ");

		if($em_settings['send_welcome_email'] == "1"){ 
			sendmailinglistemail($em['em_email'],$em['first_name'],$em['last_name'],$em['em_key'],'maillistwelcome');
		}

	}
}
?>