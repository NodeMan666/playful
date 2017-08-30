<?php  
include "../../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
require "../../../".$setup['inc_folder']."/functions.php"; 
require "../../admin.functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*","");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();

if(($_REQUEST['sepwith'] == "LB") || ($_REQUEST['sepwith'] == "lb") ==true){ 
	if($_REQUEST['dowith'] == "view") { 
		$sepwith = "<br>";
	} else { 
		$sepwith = "\r\n";
	}
} else { 
	$sepwith = $_REQUEST['sepwith'];
}

$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date", "WHERE order_id='".$_REQUEST['order_id']."' ");
if(empty($order['order_id'])) {
	die("Unable to find order information");
}
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

updateSQL("ms_history", "export_dowith='".$_REQUEST['dowith']."' , export_sepwith='".$_REQUEST['sepwith']."' ");

$carts = whileSQL(cart_table, "*", "WHERE (cart_photo_prod!='0' OR cart_product_photo!='0') AND  cart_order='".$order['order_id']."' AND  cart_coupon='0' GROUP BY cart_pic_id ORDER BY cart_pic_org ASC " );
while($cart= mysqli_fetch_array($carts)) {
	if(!empty($cart['cart_pic_org'])) { 
		if($x > 0) { 
			$exp .= $sepwith;
		}
		if($_REQUEST['stripext'] == "1") { 
			$fn = str_replace(".jpg","",$cart['cart_pic_org']);
			$fn = str_replace(".JPG","",$fn);
			$fn = str_replace(".Jpg","",$fn);
		} else { 
			$fn = $cart['cart_pic_org'];
		}
		$exp .= $fn;
		$x++;
	}
}





if($_REQUEST['dowith'] == "view") {

	print $exp;
} else {


	$filename = $order['order_id']."-export.txt";

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