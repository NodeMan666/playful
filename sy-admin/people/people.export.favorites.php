<?php  
include "../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*","");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');

if(($_REQUEST['sepwith'] == "LB") || ($_REQUEST['sepwith'] == "lb") ==true){ 
	if($_REQUEST['dowith'] == "view") { 
		$sepwith = "<br>";
	} else { 
		$sepwith = "\r\n";
	}
} else { 
	$sepwith = $_REQUEST['sepwith'];
}


updateSQL("ms_history", "export_dowith='".$_REQUEST['dowith']."' , export_sepwith='".$_REQUEST['sepwith']."' ");
 $p = doSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active", "WHERE p_id='".$_REQUEST['p_id']."' ");
$favs = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0' ORDER BY pic_org ASC");

while($fav= mysqli_fetch_array($favs)) {
	if($x > 0) { 
		$exp .= $sepwith;
	}
	if($_REQUEST['stripext'] == "1") { 
		$fn = str_replace(".jpg","",$fav['pic_org']);
		$fn = str_replace(".JPG","",$fn);
		$fn = str_replace(".Jpg","",$fn);
	} else { 
		$fn = $fav['pic_org'];
	}
	$exp .= $fn;
	$x++;
}




if($_REQUEST['dowith'] == "view") {

	print $exp;
} else {


	$filename = $p['p_name']." ".$p['p_last_name']."-favorites-".date('Y-m-d').".txt";

	$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/".$setup['photos_upload_folder']."/$filename";
	$downloadfile=$filename;

	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download"); 
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"$downloadfile\"");
	header("Content-transfer-encoding: binary\n"); 
	header("Content-length: " . filesize($filecontent) . "\n");

	@readfile($filecontent);

	unlink($filecontent);
}
?>