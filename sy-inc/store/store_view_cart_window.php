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
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

?>
<script>
$(document).ready(function(){
nofloatsmall()
});
</script>

<?php include "store_view_cart.php"; ?>
<?php  mysqli_close($dbcon); ?>