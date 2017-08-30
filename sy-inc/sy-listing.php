<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/icons.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
require $setup['path']."/".$setup['inc_folder']."/listing_functions.php"; 
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$wm = doSQL("ms_watermarking", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$sytist_store = true;
$per_page = 20;
if(!empty($_SESSION['previewTheme'])) { 
	$css_id = $_SESSION['previewTheme'];
} else {
	$css_id = $site_setup['css'];
} 
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}

$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
$fb = doSQL("ms_fb", "*", "");



$sq_page = $subpage * $per_page - $per_page;


$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
$per_page = $bcat['cat_per_page'];

if($bcat['cat_layout'] <=0) { 
	$topcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$top_section."' ");
	$layout_id = $topcat['cat_layout'];
} else { 
	$layout_id = $bcat['cat_layout'];
}
if($layout_id <=0) { 
	$layout_id = 1;
}
$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$layout_id."' ");

if($_REQUEST['page_home'] == "1") { 
	$no_cats = true;
	unset($bcat);
	$date = doSQL("ms_calendar", "*", "WHERE page_home='1' ");
	$date_feature_auto_populate = $date['date_feature_auto_populate'];

	$per_page = $date['date_feature_limit'];
	$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$date['date_feature_layout']."' ");
	$total_posts = listContentPages($date['date_feature_cat'],$tag_id,0,$date['date_feature_cat'],$layout['layout_id'],$date['date_feature_limit'],$no_cats); 

	
} else { 
	$total_posts = listContentPages($bcat['cat_id'],$tag_id,0,0,0,0,0); 
}
if(($total_posts / $per_page) > $_REQUEST['vp']) { ?>
<?php if($layout['layout_css_id'] == "listing-onphoto") { ?>

 <?php if(($ipad !== true)&&($mobile !== true)==true) { ?>
 <script>
$(document).ready(function(){
	$(".preview").hover(
	  function () {
		$(this).find('.previewtext').slideDown(200);
	  },
	  function () {
		$(this).find('.previewtext').slideUp(200);
	  }
	);
});
</script>
<?php } ?>
<?php } ?>
<div id="listingpage-<?php print $_REQUEST['vp'] + 1;?>" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>
<?php } ?>