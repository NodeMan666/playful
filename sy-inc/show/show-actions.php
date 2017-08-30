<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
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
require $setup['path']."/".$setup['inc_folder']."/show/show-functions.php";


$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");

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
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
		}
	}
}
$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['featid']."' ");


if($_REQUEST['getfeature'] > 0) { 
	$featured_dates = getfeatureddates($show);
	$date_id = $featured_dates[($_REQUEST['getfeature'] - 1)];
	// print "<li>$date_id";
	showHomeFeature($date_id,$_REQUEST['getfeature'],'');
	exit();
}
if($_REQUEST['getfeatureslide'] > 0) { 
	$featured_slides = getfeaturedslides($show['feat_page_id'],$show,$_REQUEST['sub_id']);

	$pic_id = $featured_slides[($_REQUEST['getfeatureslide'] - 1)];
	// print "<li>$date_id";
	showHomeFeature($show['feat_page_id'],$_REQUEST['getfeatureslide'],$pic_id);
	exit();
}

if($_REQUEST['action'] == "getpages") { 
	$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['show']."' ");
	showrecentitemshome();
}

mysqli_close($dbcon);
?>
