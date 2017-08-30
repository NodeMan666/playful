<?php 
require("../sy-config.php");
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
$store = doSQL("ms_store_settings", "*", "");
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

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
?>
<div id="closestoreitem" style="display: none; position: absolute; right: -32px; top: -12px; z-index: 5000;"><span  onclick="closestoreitem(); return false;" class="icon-cancel-circled the-icons" style=" font-size: 48px;"></span></div>

<?php 
if(!is_numeric($_REQUEST['did'])) { die(); } 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
$date['blog_type'] = "onpagewithminis";
$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
$date_id = $date['date_id'];
$from_photo = true;

if($date['private']>="1"){ 
	if(isset($_SESSION['office_admin_login'])) { 
		print "<div class=\"pc center\"><i>This is a password protected page but since you are logged into the admin you have direct access.</i></div>";
	} else { 
		if(!is_array($_SESSION['privateAccess'])) {
			$_SESSION['privateAccess'] = array();
		}
		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				include $setup['path']."/".$setup['inc_folder']."/password_protected.php";
				galPassword($date['date_id']);
				exit();
			} 
		} else { 
			if(!in_array($date['date_id'],$_SESSION['privateAccess'])) {
				include $setup['path']."/".$setup['inc_folder']."/password_protected.php";
				galPassword($date['date_id']);
				exit();
			}
		}
	}
}

if($bcat['cat_id'] > 0) { 
	if(!empty($bcat['cat_meta_title'])) { 
		$cat_meta_title = $bcat['cat_meta_title'];
	} else { 
		$cat_meta_title = $bcat['cat_name'];
	}
	// $cat_meta_title .= " ".$site_setup['sep_meta_title']." ".$bcat['cat_name']."";
	if($bcat['cat_theme'] > 0) { 
		$page_theme = $bcat['cat_theme'];
	}
	if(!empty($bcat['cat_under_ids'])) { 
		$scats = explode(",",$bcat['cat_under_ids']);
		$main_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scats[0]."' ");
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			if($ckcat <=0) { $top_section = $tcat['cat_id']; } 
			if(!empty($tcat['cat_meta_title'])) { 
				$add_meta_title .= "".$tcat['cat_meta_title']."  ".$site_setup['sep_meta_title']." ";
			} else { 
				$add_meta_title .= "".$tcat['cat_name']."  ".$site_setup['sep_meta_title']." ";
			}
			$ckcat++;
		}
	}
	$add_meta_title .= " ".$cat_meta_title."";
}
if(($top_section <=0)&&($bcat['cat_id'] > 0)==true) { 
	$top_section = $bcat['cat_id'];
}


require $setup['path']."/sy-inc/page_display.php";


?>

<?php  mysqli_close($dbcon); ?>
