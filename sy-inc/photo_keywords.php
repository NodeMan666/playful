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

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
?>

<?php 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
if(empty($date['date_id'])) { die("An error has occured"); } 
if(!empty($_REQUEST['sub_id'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
}

?>
<div class="pc"><h2><?php print $date['date_title'];?><?php if(!empty($sub['sub_id'])) { ?> > <?php print $sub['sub_name'];?><?php } ?></h2></div>
<div class="pc"><?php print _view_all_photo_tags_descr_;?></div>

<?php 
$key_words = array();
$keycount = array();

$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'  $and_where $and_sub ORDER BY bp_order ASC ");
while($pic = mysqli_fetch_array($pics)) { 
	$keys= whileSQL("ms_photo_keywords_connect LEFT JOIN ms_photo_keywords ON ms_photo_keywords_connect.key_key_id=ms_photo_keywords.id", "*", "WHERE key_pic_id='".$pic['pic_id']."' ");
	while($key = mysqli_fetch_array($keys)) { 
		$keycount[$key['key_word']]++;
		if(!in_array($key['key_word'],$key_words)) {
			array_push($key_words,$key['key_word']);
		}
//		print "<li>".$key['key_word'];
	}
}

if(!empty($date['date_photo_keywords'])) { 
	$date_tags = explode(",",$date['date_photo_keywords']);
	foreach($date_tags AS $tag) { 
		$key= doSQL("ms_photo_keywords", "*", "WHERE id='".$tag."' ");
		array_push($key_words,$key['key_word']);
	}
}

asort($key_words);
?>
<div class="pc">
<?php if(count($key_words) <= 0 ) { ?>
<div><b>No tags found.</b></div>
<?php } ?>
<?php 
foreach($key_words AS $key) { 
	$thiskey = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes($key)."' ");
	print "<span class=\"phototag\"><a href=\"index.php?kid=".$thiskey['id'].""; if($sub['sub_id'] > 0) { print "&sub=".$sub['sub_link']; } print "\">".$key; if($keycount[$key] > 0) { print " (".$keycount[$key].") "; }  print "</a></span>";
		
		/* countIt("ms_photo_keywords 
		LEFT JOIN ms_photo_keywords_connect ON ms_photo_keywords.id=ms_photo_keywords_connect.key_key_id 
		LEFT JOIN ms_photos ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id 
		LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "WHERE key_word='".$key."' AND ms_blog_photos.bp_blog='".$date['date_id']."' "); */
}

?>
<?php  mysqli_close($dbcon); ?>
