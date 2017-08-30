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

if(!empty($_REQUEST['rc'])) {
	$old_crop = doSQL("pc_crops", "*", "WHERE MD5(crop_id)='".$_REQUEST['rc']."' ");
	if(!empty($old_crop['crop_id'])) {
		@unlink( $setup['path']."/".$setup['gallery_folder']."/crops/".$old_crop['crop_url']);
	}
}

$dem = getCropDems($size[0],$size[1],$photo_setup['preview_width'],$photo_setup['preview_width'],.40);
/*
print "<li>NEW width: ".$dem['crop_width'];
print "<li>org width: ".$dem['org_width'];
print "<li>org height: ".$dem['org_height'];
print "<li>width: ".$dem['crop_width']."";
print "<li>height: ".$dem['crop_height']."";
print "<li>min width: ".$dem['min_width']."";
print "<li>min_height: ".$dem['min_height']."";
print "<li>x1: $x1";
print "<li>y1: $y1";
*/
?>

<title>Cropping</title>
<link rel="stylesheet" href="css/white.css" type="text/css">
<script src="js/admin.js" type="text/javascript"></script>
<script src="js/cp/scripts/prototype.js" type="text/javascript"></script>
<script src="js/cp/scripts/scriptaculous.js?load=effects,builder,dragdrop" type="text/javascript"></script>
<script src="js/cp/scripts/cropper.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
Event.observe ( 
	window, 
	'load', 
	function() { 
		new Cropper.Img ( 
			'theImageCrop',
			{
				minWidth: <?php print $dem['min_width'];?>, 
				minHeight: <?php print $dem['min_height']?>,
				ratioDim: { x:<?php print $dem['crop_width'];?>, y: <?php print $dem['crop_height'];?>},
				onEndCrop: saveCoords,
				onloadCoords: { x1: <?php print $dem['x1'];?>, y1: <?php print $dem['y1'];?>, x2: <?php print $dem['x2'];?>, y2: <?php print $dem['y2']; ?> },
				displayOnInit: true
			}
		) 
	}
);
        
function saveCoords (coords, dimensions)
{
	$( 'x1' ).value = coords.x1;
	$( 'y1' ).value = coords.y1;
	$( 'width' ).value = dimensions.width;
	$( 'height' ).value = dimensions.height;
}
</script>
</head>
<body>
    <form action="blog_crop_save.php" method="post" name="cropForm" id="cropForm">
    <div class="pageContent"><h3>Crop Blog Preview</h3></div>
	<div class="pageContent"></div>

	<div class="pageContent" style="text-align: center; width: <?php print $dem['org_width'];?>px; margin: auto;">   
	<a href="" onClick="document.cropForm.submit(); return false;"  >Apply</a>  &nbsp;&nbsp;&nbsp;
	<a href="" onClick="closeBlogCropping(); return false;" >Cancel</a> </div>

    <div style="margin: auto; text-align: center; width: <?php print $dem['org_width'];?>px; height: <?php print $dem['org_height'];?>px;">
	<center>
        <img src="<?php print $theImage;?>" id="theImageCrop" alt="" width="<?php print $dem['org_width'];?>" height="<?php print $dem['org_height'];?>" />
		</center>
    </div>
       
    <input type="hidden" name="x1" id="x1" value="">
    <input type="hidden" name="y1" id="y1" value="">
    <input type="hidden" name="width" id="width" value="">
    <input type="hidden" name="height" id="height" value="">
    <input type="hidden" name="date_id" id="date_id" value="<?php print $date['date_id'];?>">
    <input type="hidden" name="pic_id" id="pic_id" value="<?php print $pic['pic_id'];?>">
	<div class="pageContent" style="text-align: center; width: <?php print $dem['org_width'];?>px; margin: auto;">   
	<a href="" onClick="document.cropForm.submit(); return false;" >Apply</a>  &nbsp;&nbsp;&nbsp;
	<a href="" onClick="closeBlogCropping(); return false;" >Cancel</a> 
	</div>
    </form>
</body>
</html>

<?php
function getCropDems($orgWidth,$orgHeight,$cropWidth,$cropHeight,$cropMin) {

	if($cropWidth> $cropHeight) {
		$cp = $cropWidth/ $cropHeight;
	} else {
		$cp = $cropHeight / $cropWidth;
	}
	if($orgWidth > $orgHeight) {

		$imp = $orgWidth / $orgHeight;
		if($imp > $cp) {
			$height = $orgHeight;
			$width = round($orgHeight  * $cp);
		} else{
			$width = $orgWidth;
			$height = round($orgWidth / $cp);
		}
	} else {
		$imp = $orgHeight / $orgWidth;
		if($imp > $cp) {
			$height =  round($orgWidth * $cp);
			$width =$orgWidth;
		} else {
			$height = $orgHeight;
			$width = round($orgHeight / $cp);
		}
	}
	$min_width = round($width * $cropMin);
	$min_height = round($height  * $cropMin);
	if($width == $orgWidth) {
		$x1 = 0;
		$x2 = $width;
		$y1 = ($orgHeight - $height)/2;
		$y2 = $height + $y1;
	} else {
		$x1 =  ($orgWidth - $width)/2;
		$x2 = $width + $x1;
		$y1 = 0;
		$y2 = $height;
	}
	$dem['x1'] = round($x1);
	$dem['x2'] = round($x2);
	$dem['y1'] = round($y1);
	$dem['y2'] = round($y2);
	$dem['min_width'] = round($min_width);
	$dem['min_height'] = round($min_height);
	$dem['crop_width'] = round($width);
	$dem['crop_height'] = round($height);
	$dem['org_width'] = $orgWidth;
	$dem['org_height'] = $orgHeight;
	return $dem;
}
?>
