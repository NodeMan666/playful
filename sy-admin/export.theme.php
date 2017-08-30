<?php  
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
adminsessionCheck();

$this_dom = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
$info = explode('//', $_SERVER['HTTP_REFERER']); 
$d1 = $info[1]; 
$info2 = explode('/', $d1); 
$d2 = $info2[0]; 
$from_dom = str_replace("www.", "", strtolower($d2));
if($from_dom !== $this_dom) {
//	die("You are not authorized to do this. Your IP address (".getUserIP().") has been sent to the website owner.");
}

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');


$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."'");
$qry = "INSERT INTO ms_css  SET ";
foreach($css AS $id => $val) {
	if(!is_numeric($id)) {
		if($id!=="css_id") {
			if($fn>0) {
				$qry .= ", ";
			}
			$qry .= "$id='".addslashes($val)."'";
			$fn++;
		}
	}
}
$qry .="||";

$fn = 0;
$css2 = doSQL("ms_css2", "*", "WHERE parent_css_id='".$_REQUEST['css_id']."'");
$qry .= "INSERT INTO ms_css2  SET ";
foreach($css2 AS $id => $val) {
	if(!is_numeric($id)) {
		if(($id!=="css2_id")&&($id !== "parent_css_id")==true) {
			if($fn>0) {
				$qry .= ", ";
			}
			$qry .= "$id='".addslashes($val)."'";
			$fn++;
		}
	}
}
$qry .="||\r\n";
// print $qry;

$fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ");
while($font = mysqli_fetch_array($fonts)) { 
	 $f .= $font['font']."-";
}
$qry .= "$f";


$filename = $css['css_name']."-Theme.txt";

$fp = fopen("".$setup['path']."/".$setup['misc_folder']."/$filename", "w");
$info =  "$qry"; 
fputs($fp, "$info\n");
fclose($fp);



$filecontent="".$setup['path']."/".$setup['misc_folder']."/$filename";
$downloadfile=$filename;

header("Content-Type: application/octet-stream");
header("Content-Type: application/download"); 
header("Content-Type: text");
header("Content-Disposition: attachment; filename=\"$downloadfile\"");
header("Content-transfer-encoding: binary\n"); 
//header("Content-length: " . filesize($filecontent) . "\n");

@readfile($filecontent);

unlink($filecontent);
/*
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-type: text/css; charset=UTF-8"); 
header("Content-Length: ".strlen($filecontent));
header("Cache-Control: cache, must-revalidate");    
header("Pragma: public");
header("Expires: 0");
*/
?>
