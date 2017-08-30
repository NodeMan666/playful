<?php
include "../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
header('Content-Type: text/html; charset=utf-8');
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*","");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
$no_trim = true;
if($_REQUEST['prices'] == "1") { 
	$show_prices = true;
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>

<style type="text/css" media="screen,print">
body,p,div {
	color: #000000;
	font: 12px Arial;
}
body {
	background: #f4f4f4;
	padding: 0;
	margin: 0;
}
.pad, .row { padding: 4px 0px; } 
.center { text-align: center; } 
.left { float: left; } 
.right { float: right; } 
.clear {  clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px; } 
.textright { text-align: right; } 
.field100 { width: 97%;}
.padtopbottom { padding: 2px 0; } 
.reg,.normal { font-size: 13px; } 
p { padding: 8px 0 } 
.green { color: #74a474; } 
.muted { color: #949494; } 
.labels { font-size: 17px; font-weight: bold; padding: 4px 0;} 
#packingslip { 
width: 640px;
margin: auto;
margin-top: 12px;
border: solid 1px #e4e4e4;
background: #FFFFFF;
padding: 24px;
}

.billing { float: left; width: 50%; }
.shipping { float: right; width: 50%; }

.headerleft { float: left; width: 50%; }
.headerright{ float: right; width: 50%; text-align: right; }

#products { background-color: #dddddd; margin-top: 20px; } 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold; } 
#products td { background: #FFFFFF; padding: 8px; }

#totals { float: right;  margin-top: 20px; } 
#totals td { padding: 5px; }

#payinfo { float: left;  margin-top: 20px; } 
#payinfo td { padding: 5px; }

</style>

<style media="print">
body {
	background-color: #ffffff;
}
#packingslip { 
	border: none;
}
#products { background-color: #dddddd; margin-top: 20px; !important} 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold;!important } 
#products td { background: #FFFFFF; padding: 8px; !important}

#totals { float: right;  margin-top: 20px; !important} 
#totals td { padding: 5px; !important}

</style>


</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<div id="packingslip">

<?php 
if($_REQUEST['date'] > 0) { 

	$d = explode("-",$_REQUEST['date']);
	$day = date("M d, Y", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 
	$dow = date("l", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 
	$du = doSQL("ms_bookings", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_date='".$d[0]."-".$d[1]."-".$d[2]."' AND  book_unavailable_day='1' GROUP BY book_id ORDER BY book_time ASC ");

	?>
	<div class="pc center"><h2><?php print $day;?></h2></div>
<table width="100%" cellpadding="8" cellspacing="2">

	<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_confirmed>='1' AND ((book_date='".$_REQUEST['date']."' AND book_recurring_dom='' AND book_recurring_dow='') OR book_recurring_dom='".$d[2]."') OR book_recurring_dow='".$dow."'   GROUP BY book_id    ORDER BY book_time ASC ");
	if(mysqli_num_rows($books) <= 0) { ?>
	<div class="pc center">No events found</div>
	<?php } 
	while($book = mysqli_fetch_array($books)) { 
		$t = explode(":",$book['book_time']);
		$timeend = date("g:i A", mktime($t[0], $t[1] + ($book['service_length'] * 60), 0, 0,0,0)); 


	
		?>
		<tr valign="top">
			<td><?php print $book['book_time_show'];?></td>
			<td><?php 
			$hours = floor($book['book_length'] / 60);  
			$minutes = $book['book_length'] - ($hours * 60);
			if($hours > 0) { 
				if($hours == "1") { 
					print "1 hour ";
				} else { 
					print $hours." hours ";
				}
			}
			if($minutes > 0) { 
				print $minutes." minutes";
			}
			?>

			</td>
			<td>
			<?php if($book['date_id'] > 0) { print $book['date_title']; } elseif (!empty($book['book_event_name']))  { print $book['book_event_name']; } else { print "<i>Untitled</i>"; } ?>
			<?php if(!empty($book['book_first_name'])) { ?>
			<br><b><?php print $book['book_first_name']." ".$book['book_last_name'];?></b>
			<?php } ?>

			</td>
			<td><?php print $book['book_email'];?></td>
			<td><?php print $book['book_phone'];?></td>
		</tr>
		<?php 
	
	
	
	} 
}
?>

</div>
<div>&nbsp;</div>
