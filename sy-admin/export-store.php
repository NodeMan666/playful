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
$sep = ",";
$_REQUEST['dowith'] = "exp";
/*
$exp .= "Product Category".$sep;
$exp .= "Product Name".$sep;
$exp .= "Supplier SKU".$sep;
$exp .= "Main Image".$sep;
$exp .= "Description".$sep;
$exp .= "Supplier Cost".$sep;
$exp .= "Site Price".$sep;
$exp .= "Quantity on Hand".$sep;
$exp .= "UPC".$sep;
$exp .= "Model/Style#".$sep;
$exp .= "Package Weight".$sep;
$exp .= "Item Box Height".$sep;
$exp .= "Item Box Length".$sep;
$exp .= "Ship From ZIP Code".$sep;
$exp .= "Number of Boxes";
*/
if($_REQUEST['dowith'] == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}

	$dates = whileSQL("ms_calendar", "*", "WHERE prod_qty>'0' AND date_id!='1420' ORDER BY prod_qty DESC LIMIT 25");
	while($date = mysqli_fetch_array($dates)) {  

		$exp .= "".$sep;
		$exp .= "".$sep;

		$exp .= "Bracelets".$sep;
		$title = str_replace('"'," ",$date['date_title']);
		$exp .= "".str_replace(","," ",$title)."".$sep."";

		$exp .= "".$date['prod_sku']."".$sep."";
		// image

		$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");

		$exp .= "".$setup['url']."/".$setup['photos_upload_folder']."/".$fphoto['pic_folder']."/".$fphoto['pic_large']."".$sep."";
		$descr = strip_tags($date['date_text']);
		$descr = str_replace('"'," ",$descr);
		$descr = preg_replace('/\s+/', ' ', $descr);
		$descr = str_replace('"'," ",$descr);

		$exp .= "".str_replace(",", " ", $descr)."".$sep;
		// $exp .= " ".$sep;
		## blank description
		
		# Materials
		$exp .= " ".$sep;

		# Option Description
		$exp .= " ".$sep;

		$price = $date['prod_price'] * .6;
		$price = number_format($price,2);

		# Supplier Cost
		$exp .= $price.$sep;

		# Site Price
		$exp .= $date['prod_price'].$sep;

		# Quantity on hand
		$exp .= "".str_replace(",", " ", $date['prod_qty'])."".$sep;

		# UPC
		$exp .= " ".$sep;

		# Blank
		$exp .= " ".$sep;

		# Cost Include Shipping
		$exp .= "N".$sep;

		# Quality
		$exp .= "NEW".$sep;

		# Replenishable
		$exp .= "".$sep;

		# Lead Time
		$exp .= "1".$sep;

		# Date Available
		$exp .= "".$sep;

		# Type of product comarison
		$exp .= "".$sep;

		# Compare at price
		$exp .= "".$sep;

		# Compare at URL
		$exp .= "".$sep;

		# Does compare price include shipping
		$exp .= "".$sep;

		# Low street price
		$exp .= "".$sep;


		# Low street price URL
		$exp .= "".$sep;

		# Compare at comments
		$exp .= "".$sep;


		# Model / Style
		$exp .= "".$sep;

		#package weight
		$exp .= "1".$sep;

		# Item box height
		$exp .= "2".$sep;

		$exp .= "4".$sep;
		$exp .= "4".$sep;
		$exp .= "36350".$sep;
		$exp .= "1".$sep;
		


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
