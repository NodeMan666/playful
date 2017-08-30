<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-control: private"); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");

if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}
$sytist_store = true;
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
adminsessionCheck();
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$store = doSQL("ms_store_settings", "*", "");


if($_REQUEST['action'] == "refreshhomestats") { 
	include "home.php";
	unset($_SESSION['query']);
	mysqli_close($dbcon);
	exit();
 } 
if($_REQUEST['action'] == "visitortotaltitle") { 
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
	$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	print $site_setup['website_title']." | Admin ".$visitors." visitors / ".$pv." page views";
	unset($_SESSION['query']);
	mysqli_close($dbcon);
	exit();
}
?>
