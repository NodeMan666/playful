<?php  
if(empty($path)) { 
	$path = "../../";
}
include $path."sy-config.php";
if($setup['demo_mode'] == true) { 
	die("Disabled for the demo");
}
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
header('Content-Type: text/html; charset=utf-8');
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['manage_folder']."/admin.functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');

$this_dom = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
$info = explode('//', $_SERVER['HTTP_REFERER']); 
$d1 = $info[1]; 
$info2 = explode('/', $d1); 
$d2 = $info2[0]; 
$from_dom = str_replace("www.", "", strtolower($d2));

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$sep = $_REQUEST['sep'];

$pcs = whileSQL("ms_promo_codes", "*", "WHERE code_batch='".addslashes(stripslashes($_REQUEST['code_batch']))."' ORDER BY code_id ASC ");
while($pc = mysqli_fetch_array($pcs)) {  
	if($_POST['code_name'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['code_name'])."".$_POST['sep']."";
	}
	if($_POST['code_code'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['code_code'])."".$_POST['sep']."";
	}


	if($_POST['code_expire'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['code_end_date'])."".$_POST['sep']."";
	}


		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}




if($_REQUEST['dowith'] == "view") {

	print $exp;
} else {


	$filename = date('Ymdhmi')."-".MD5(date('Ymdhmi')).".csv";

	$fp = fopen("".$setup['path']."/sy-tmp/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/sy-tmp/$filename";

	$downloadfile="Coupon-Export-".$_REQUEST['code_batch']."-".date('Y-m-d').".csv";
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download"); 
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"$downloadfile\"");
	header("Content-transfer-encoding: binary\n"); 
	header("Content-length: " . filesize($filecontent) . "\n");

	@readfile($filecontent);

	unlink($filecontent);
}
/*
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-type: text/css; charset=UTF-8"); 
header("Content-Length: ".strlen($filecontent));
header("Cache-Control: cache, must-revalidate");    
header("Pragma: public");
header("Expires: 0");
*/
?>
