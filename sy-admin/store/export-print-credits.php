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

$pcs = whileSQL("ms_print_credits", "*", "WHERE pc_batch='".addslashes(stripslashes($_REQUEST['pc_batch']))."' ORDER BY pc_id ASC ");
while($pc = mysqli_fetch_array($pcs)) {  
	if($_POST['pc_name'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['pc_name'])."".$_POST['sep']."";
	}
	if($_POST['pc_code'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['pc_code'])."".$_POST['sep']."";
	}


	if($_POST['pc_expire'] == "1") {
		$exp .= "".str_replace(",", " ", $pc['pc_expire'])."".$_POST['sep']."";
	}
	if($_POST['pc_package'] == "1") {
		$package = doSQL("ms_packages", "*", "WHERE package_id='".$pc['pc_package']."' ");
		$exp .= "".str_replace(",", " ", $package['package_name'])."".$_POST['sep']."";
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

	if($date['date_id'] > 0) { 
		$downloadfile="Print-Credit-Export-".date('Y-m-d').".csv";
	} else { 
		$downloadfile=$site_setup['website_title']."-People-Export-".date('Y-m-d').".csv";
	}
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
