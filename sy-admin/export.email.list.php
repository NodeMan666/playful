<?php  
if(empty($path)) { 
	$path = "../";
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
date_default_timezone_set('America/Los_Angeles');
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

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
// $sep = ",";
$email = "on";
$dowith = "view";

$exp .= "EMAIL".$sep."";
if($dowith == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}


$send_to_emails = array();
	$custs = whileSQL("ms_email_list", "*", "WHERE em_id>'0' AND em_do_not_send='0' AND em_date_id<='0' ORDER BY em_id DESC " );
	print "<p>".mysqli_num_rows($custs)."</p>"; 
	while($cust = mysqli_fetch_array($custs)) {
		$total_export++;
		array_push($send_to_emails, $cust['em_email']);

		if($email == "on") {
			$exp .= "".$cust['em_email']."".$sep."";
		}

		if($_POST['firstlastName'] == "on") {
			$firstlast = $cust['p_name']." ".$cust['p_last_name'];
			$exp .= "".str_replace(",", " ", $firstlast)."".$sep."";
		}
		if($_POST['lastfirstName'] == "on") {
			$lastfirst = $cust['p_last_name']." ".$cust['p_name'];
			$exp .= "".str_replace(",", " ", $lastfirst)."".$sep."";
		}
		
		
		
		if($_POST['firstName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_name'])."".$sep."";
		}
		if($_POST['lastName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_last_name'])."".$sep."";
		}

		if($_POST['phone'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_phone'])."".$sep."";
		}
		if($_POST['address'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_address1'])."".$sep."";
		}
		if($_POST['city'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_city'])."".$sep."";
		}
		if($_POST['state'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_state'])."".$sep."";
		}
		if($_POST['zip'] == "on") {
			$exp .= "".$cust['p_zip']."".$sep."";
		}
		if($_POST['country'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_country'])."".$sep."";
		}
		if($_POST['date'] == "on") {
			$exp .= "".$cust['p_date']."".$sep."";
		}
		if($_POST['gender'] == "on") {
			$exp .= "".$cust['p_gender']."".$sep."";
		}
		if($_POST['age'] == "on") {
			$exp .= "".$cust['p_age']."".$sep."";
		}
		if($_POST['source'] == "on") {
			$exp .= "".$cust['p_refer']."".$sep."";
		}
		if($_POST['status'] == "on") {
			$exp .= "".$cust['p_receive_emails']."".$sep."";
		}

		if($dowith == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}




if($dowith == "view") {

	print $exp;
} else {


	$filename = date('Ymdhmi')."-".MD5(date('Ymdhmi')).".csv";

	$fp = fopen("".$setup['path']."/sy-tmp/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/sy-tmp/$filename";

	if($date['date_id'] > 0) { 
		$downloadfile=$site_setup['website_title']."-".$date['date_title']."-Export-".date('Y-m-d').".csv";
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
