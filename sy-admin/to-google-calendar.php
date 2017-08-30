<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-control: private"); 
ob_start(); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}
$sytist_store = true;
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
if(!is_numeric($_REQUEST['book_id'])) { die("something went wrong"); } 

$book  = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show ,date_format(DATE_ADD(book_date_added, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." %l:%i %p')  AS book_date_added", "WHERE book_id='".$_REQUEST['book_id']."' "); 
if(empty($book['book_id'])) { die("unable to find booking"); } 
updateSQL("ms_bookings",  "book_google='1' WHERE book_id='".$book['book_id']."' ");


$title = "";
$bdate = str_replace("-","",$book['book_date']);
$start = $book['book_date']." ".$book['book_time'];
$btimestart = date('Hi',strtotime($start))."00";
$btimeend = date('Hi',strtotime('+'.$book['book_length'].' minutes',strtotime($start)))."00";
$startDate = $bdate."T".$btimestart;
$endDate = $bdate."T".$btimeend;

if((!empty($book['book_first_name'])) || (!empty($book['book_last_name'])) == true) { 
	$title = $book['book_first_name']." ".$book['book_last_name']." - ";
}
if(!empty($book['book_event_name'])) { 
	$title .= $book['book_event_name'];
} else { 
	$title .= $book['date_title'];
}
if(($book['all_day_event'] == "1") || ($book['book_time'] == "00:00:00") == true) { 
	$start = $book['book_date']." ".$book['book_time'];
	$endDate = date('Ymd',strtotime('+ 1 days',strtotime($start)));
	$date = $bdate."/".$endDate;
} else { 
	$date = $startDate."/".$endDate;
}
$details = "";
$opts = explode("\n",$book['book_options']);
foreach($opts AS $opt) { 
	if(!empty($opt)) { 
		$o = explode("|",$opt);
		if(!empty($o[0])) { 
			$details .= $o[0]; if(!empty($o[1])) {$details .= ": ".$o[1];}  $details .= "\r\n";
		}
	}
}

$url = "https://www.google.com/calendar/render?action=TEMPLATE&text=".urlencode($title)."&dates=".$date."&details=".urlencode($details)."&location=".urlencode($location)."&sf=true&output=xml";



header("location: ".$url);
session_write_close();
exit();
?>