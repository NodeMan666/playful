<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');


foreach($_REQUEST AS $id => $value) {
	$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
	$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
}
$pic = doSQL("ms_photos", "*", " WHERE pic_id='".$_REQUEST['pic_id']."' ");
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

if($_REQUEST['recrop'] == "yes") { 
	unlink($setup['path']."/".$_REQUEST['thumb']);
	unlink($setup['path']."/".$_REQUEST['mini']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php
$theImage = "/".$setup['photos_upload_folder']."/".$pic['pic_folder'].""."/".$pic['pic_pic']."";
$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 

?>

<title>Colorsa</title>
<link rel="stylesheet" href="css/white.css" type="text/css">
<script src="js/admin.js" type="text/javascript"></script>
<script type="text/javascript" src="jscolor/jscolor.js"></script>
</head>
<body>
<div style="padding: 20px;">

	<div style="float: right;" class="pageContent"><a href="" onClick="closeBlogCropping(); return false;" >Close <img src="graphics/close.png" border=0 align=absmiddle></a> </div>
	<div class="pageContent">
<h1>Adjust Photo Colors</h1>
This option allows you to adjust the background and border colors for the photo when you have selected a display type that uses the colors assigned to the photos. <br><br>
Once your make an adjustment to a color, it is automatically saved.
</div>
<div class="cssClear"></div>

<div>
			<div class="pageContent" style="float: left; width: 31%;">
			<div>Screen Background Color</div>
			<?php inlineDataFieldColors('text','8','1','ms_photos','pic_bg_color','pic_id',$pic['pic_id'],$pic['pic_bg_color'],'color','document.getElementById(\'photoThumb-'.$pic['pic_id'].'-bg\').style.backgroundColor = \'#\'+this.color'); ?>
			</div>

			<div class="pageContent" style="float: left; width: 31%;">
			<div>Outside Border Color</div>
			<?php inlineDataFieldColors('text','8','1','ms_photos','pic_border_in_color','pic_id',$pic['pic_id'],$pic['pic_border_in_color'],'color','document.getElementById(\'photoThumb-'.$pic['pic_id'].'-thumb\').style.borderColor = \'#\'+this.color'); ?>
			</div>


			<div class="pageContent" style="float: left; width: 31%;">
			<div>Inside Border Color</div>
			<?php inlineDataFieldColors('text','8','1','ms_photos','pic_border_out_color','pic_id',$pic['pic_id'],$pic['pic_border_out_color'],'color','document.getElementById(\'photoThumb-'.$pic['pic_id'].'-thumb\').style.backgroundColor = \'#\'+this.color'); ?>
			</div>
<div class="cssClear"></div>
</div>
</div>
<div id="photoThumb-<?php print $pic['pic_id'];?>-bg" style="width: 100%; height: 100%; padding: 12px; background: <?php print $pic['pic_bg_color'];?>;">

	<div style="text-align: center;" class="pageContent">
	<img id="photoThumb-<?php print $pic['pic_id'];?>-thumb" src="<?php print $theImage;?>" style="border: solid 10px <?php print $pic['pic_border_in_color'];?>; background: <?php print $pic['pic_border_out_color'];?>; padding: 2px;">
	</div>
</div>

</body>
</html>

