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
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");
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
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}


if($_REQUEST['action'] == "addcgtocart") {

	if(!empty($_REQUEST['cart_id'])) { 
		$cart_add_id = updateSQL("ms_cart", "cart_qty='1', 
		cart_store_product='', 
		cart_product_name='".addslashes(stripslashes(_gift_certificate_name_))."', 
		cart_order_message='".addslashes(stripslashes($date['prod_order_message']))."', 
		cart_price='".addslashes(stripslashes($_POST['amount']))."', 
		cart_ship='0', 
		cart_session='".$_SESSION['ms_session']."' , 
		cart_client='".$_SESSION['pid']."' , 
		cart_date='".date('Y-m-d H:i:s')."', 
		cart_taxable='0', 
		cart_ip='".getUserIP()."' ,
		cart_no_discount='1', 
		cart_gift_certificate='1',
		cart_delivery_date='".addslashes(stripslashes(trim($_POST['actualDate'])))."', 
		cart_gift_certificate_to_name='".addslashes(stripslashes(trim($_POST['to_name'])))."', 
		cart_gift_certificate_to_email='".addslashes(stripslashes(trim($_POST['to_email'])))."', 
		cart_gift_certificate_from_name='".addslashes(stripslashes(trim($_POST['from_name'])))."', 
		cart_gift_certificate_from_email='".addslashes(stripslashes(trim($_POST['from_email'])))."', 
		cart_gift_certificate_message='".addslashes(stripslashes(trim($_POST['message'])))."'	
		WHERE MD5(cart_id)='".$_REQUEST['cart_id']."'
		");
		
	} else { 
		
		$cart_add_id = insertSQL("ms_cart", "cart_qty='1', 
		cart_store_product='', 
		cart_product_name='".addslashes(stripslashes(_gift_certificate_name_))."', 
		cart_order_message='".addslashes(stripslashes($date['prod_order_message']))."', 
		cart_price='".addslashes(stripslashes($_POST['amount']))."', 
		cart_ship='0', 
		cart_session='".$_SESSION['ms_session']."' , 
		cart_client='".$_SESSION['pid']."' , 
		cart_date='".date('Y-m-d H:i:s')."', 
		cart_taxable='0', 
		cart_ip='".getUserIP()."' ,
		cart_no_discount='1', 
		cart_gift_certificate='1',
		cart_delivery_date='".addslashes(stripslashes(trim($_POST['actualDate'])))."', 
		cart_gift_certificate_to_name='".addslashes(stripslashes(trim($_POST['to_name'])))."', 
		cart_gift_certificate_to_email='".addslashes(stripslashes(trim($_POST['to_email'])))."', 
		cart_gift_certificate_from_name='".addslashes(stripslashes(trim($_POST['from_name'])))."', 
		cart_gift_certificate_from_email='".addslashes(stripslashes(trim($_POST['from_email'])))."', 
		cart_gift_certificate_message='".addslashes(stripslashes(trim($_POST['message'])))."'	
		");
	}
}
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}
?>
<div style="padding: 24px;" class="inner">
<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
<?php 
include $setup['path']."/sy-inc/store/store_gift_certificate_include.php";
?>
<div class="pc center"><a href="" onclick="closewindowpopup(); return false;"><?php print _cancel_;?></a></div>
</div>