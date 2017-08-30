<?php
require "../sy-config.php";
session_start();
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
//		print "<li>".$id ." => ".$val;
		define($id,$val);
	}
}

foreach($_REQUEST AS $id => $value) {
	if(!is_array($value)) {
		$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
	}
	$_REQUEST[$id] = sql_safe(strip_tags("".$_REQUEST[$id].""));
}

$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE date_id='".$_REQUEST['date_id']."' ");

$coms = numFBComments($setup['url']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/"); 
$scoms = countIt("ms_comments", "WHERE com_table='ms_calendar' AND com_table_id='".$date['date_id']."' AND com_approved='1' ");
$tcoms = $coms + $scoms;
print $tcoms;



?>