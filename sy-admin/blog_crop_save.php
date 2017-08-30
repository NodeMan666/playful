<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

$folder = $setup['content_folder'];

foreach($_REQUEST AS $id => $value) {
	$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
	$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
}
$pic = doSQL("ms_photos", "*", " WHERE pic_id='".$_REQUEST['pic_id']."' ");

	$page_link = "blog_thumbnails";

?>

<?php

if($_REQUEST['action'] == "resizePreview") { 

	
	
if($setup['demo_mode'] == true) { 
	print "DEMO MODE";
	$_SESSION['sm'] = "That function disabled in demo, but that photo would have been made the preview photo.";
	if($date['date_type'] == "gal") { 
		$do = "photos";
	} elseif($date['date_type'] == "page") { 
		$do = "pages";
	} else {
		$do = "news";
	}

	header("location: index.php?do=$do&action=managePhotos&date_id=".$date['date_id']."");		exit();
	die();
}

	
	
	$size_upfull = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 

	$image_use_name=strtolower($date['date_title']."-".$date['date_id']."-$add_to_name"); 
	$image_use_name = strtolower($image_use_name);
	$image_use_name = str_replace(' ', '_', $image_use_name);
	$image_use_name = str_replace("&", "and", $image_use_name);
	$image_use_name = str_replace("#", "num", $image_use_name);
	$image_use_name = str_replace("?", "", $image_use_name);
	$image_use_name = str_replace('"', "", $image_use_name);
	$image_use_name = str_replace("'", "", $image_use_name);
	$image_use_name = str_replace("/", "", $image_use_name);
	$image_use_name = stripslashes(trim($image_use_name));
	$image_use_name .= date('his');

	$RESIZEWIDTHFULL=$photo_setup['preview_width'];
	$RESIZEHEIGHTFULL=$photo_setup['preview_height'];
	$theImage = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder'].""."/".$pic['pic_pic']."";

	$thumb_name = $setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$image_use_name."_thumb.jpg";
	$mini_name = $setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$image_use_name."_mini.jpg";

	ResizeImage("$theImage",$RESIZEWIDTHFULL,$RESIZEHEIGHTFULL,"$thumb_name", $photo_setup, $setup);


	$photo_setup['mini_size'] = $site_setup['blog_mini_size'];

		if($size_upfull[0] >= $size_upfull[1]) {
			$div = ($size_upfull[1] / $photo_setup['mini_size']);

			$RESIZEWIDTH=ceil($size_upfull[0] / $div);
			$RESIZEHEIGHT=$photo_setup['mini_size'] ;
		} else {
			$div = ($size_upfull[0] / $photo_setup['mini_size']);
			$RESIZEWIDTH=$photo_setup['mini_size'] ;
			$RESIZEHEIGHT=ceil($size_upfull[1] / $div);
		}
		if($RESIZEWIDTH<$photo_setup['mini_size']) {
			$add1 = $photo_setup['mini_size'] - $RESIZEWIDTH;
		}
		if($RESIZEHEIGHT<$photo_setup['mini_size']) {
			$add2 = $photo_setup['mini_size'] - $RESIZEHEIGHT;
			if($add2>$add1) {
				$add = $add2;
			} else {
				$add = $add1;
			}
		}
		if($add > 0) {
			print "<li>Adding";
			$RESIZEWIDTH = $RESIZEWIDTH + $add;
			$RESIZEHEIGHT = $RESIZEHEIGHT + $add;
		}
	ResizeImage("$theImage",$RESIZEWIDTH,$RESIZEHEIGHT,"$mini_name", $photo_setup, $setup);

		/* START CROIP */
		$tx = ceil(($RESIZEWIDTH / 2) - ($photo_setup['mini_size'] / 2));
		$ty = ceil(($RESIZEHEIGHT / 2) - ($photo_setup['mini_size'] / 2));
		if($tx< 0) { $tx = 0;}
		if($ty< 0) { $ty = 0;}
		//print "<li>tx: $tx";
		//print "<li>ty: $ty";
		$img = imagecreatetruecolor($photo_setup['mini_size'],$photo_setup['mini_size']);
		$org_img = imagecreatefromjpeg("$mini_name");
		//print "<li>org img: $org_img";
		//$ims = getimagesize($new_thumb);
		if($ty > 10) { $ty_pos = $ty - 10;} else { $ty_pos = $ty; } 
		imagecopy($img,$org_img, 0, 0, $tx, $ty_pos, $photo_setup['mini_size'], $photo_setup['mini_size']);
		imagejpeg($img,"$mini_name",93);
		imagedestroy($img);
		/* END CROP */
	$_SESSION['ty'] = $ty;


		updateSQL("ms_calendar", "date_thumb='".$image_use_name."_thumb.jpg', date_mini='".$image_use_name."_mini.jpg'  WHERE date_id='".$_REQUEST['date_id']."' ");
	$_SESSION['sm'] = "Photo is now blog preview";
	session_write_close();
		if($date['date_type'] == "gal") { 
			$do = "photos";
		} elseif($date['date_type'] == "page") { 
			$do = "pages";
		} else {
			$do = "news";
		}

	header("location: index.php?do=$do&action=managePhotos&date_id=".$date['date_id']."");
exit();

}
if($_REQUEST['action'] == "saveCrop") { 

	if(!empty($date['date_thumb'])) { 

		unlink($setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$date['date_thumb']);
		unlink($setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$date['date_mini']);
	}
	$image_use_name=strtolower($date['date_title']."-".$date['date_id']."-$add_to_name"); 
	$image_use_name = strtolower($image_use_name);
	$image_use_name = str_replace(' ', '_', $image_use_name);
	$image_use_name = str_replace("&", "and", $image_use_name);
	$image_use_name = str_replace("#", "num", $image_use_name);
	$image_use_name = str_replace("?", "", $image_use_name);
	$image_use_name = str_replace('"', "", $image_use_name);
	$image_use_name = str_replace("'", "", $image_use_name);
	$image_use_name = str_replace("/", "", $image_use_name);
	$image_use_name = stripslashes(trim($image_use_name));
	$image_use_name .= date('his');

	copy($setup['path']."/".$_REQUEST['thumb'], $setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$image_use_name."_thumb.jpg");
	copy($setup['path']."/".$_REQUEST['mini'], $setup['path']."".$folder."".$date['cat_folder']."/".$date['date_link']."/".$image_use_name."_mini.jpg");

	unlink($setup['path']."/".$_REQUEST['thumb']);
	unlink($setup['path']."/".$_REQUEST['mini']);
		updateSQL("ms_calendar", "date_thumb='".$image_use_name."_thumb.jpg', date_mini='".$image_use_name."_mini.jpg'  WHERE date_id='".$_REQUEST['date_id']."' ");
		if($date['date_type'] == "gal") { 
			$do = "photos";
		} elseif($date['date_type'] == "page") { 
			$do = "pages";
		} else {
			$do = "news";
		}
?>
<script>window.top.location.href = "index.php?do=<?php print $do;?>&action=managePhotos&date_id=<?php print $date['date_id'];?>";</script> 
<?php exit();	
}

?>



<?php

$x1      = $_REQUEST['x1'];
$y1      = $_REQUEST['y1'];
$width   = $_REQUEST['width'];
$height  = $_REQUEST['height'];
$theImage = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder'].""."/".$pic['pic_pic']."";


if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/temp")) {
	@mkdir($setup['path']."/".$setup['photos_upload_folder']."/temp", 0777);
	@chmod($setup['path']."/".$setup['photos_upload_folder']."/temp", 0777);
}


$srcImg  = imagecreatefromjpeg($theImage);
$newImg  = imagecreatetruecolor($width, $height);

imagecopyresampled($newImg, $srcImg, 0, 0, $x1, $y1, $width, $height, $width, $height);
$cropped_image_name = "cropped-".date('ymdhis')."-".$pic['pic_pic']."";





imagejpeg($newImg, "".$setup['path']."/".$setup['photos_upload_folder']."/temp/$cropped_image_name");

$thumb_name = "".$setup['photos_upload_folder']."/temp/th_".$cropped_image_name;
$mini_name = "".$setup['photos_upload_folder']."/temp/mini_".$cropped_image_name;
ResizeImage("".$setup['path']."/".$setup['photos_upload_folder']."/temp/".$cropped_image_name."",$photo_setup['preview_width'],$photo_setup['preview_width'],$setup['path']."/".$thumb_name, $photo_setup, $setup);

ResizeImage("".$setup['path']."/".$setup['photos_upload_folder']."/temp/".$cropped_image_name."",$site_setup['blog_mini_size'],$site_setup['blog_mini_size'],$setup['path']."/".$mini_name, $photo_setup, $setup);

unlink("".$setup['path']."/".$setup['photos_upload_folder']."/temp/".$cropped_image_name."");
// $id = insertSQL("pc_crops", "crop_pic='".$pic['pic_id']."', crop_url='$cropped_image_name',x1='$x1',y1='$y1', width='$width', height='$height' ");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<script src="js/admin.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/white.css" type="text/css">	<title>Cropping</title>
</head>
<body>

<?php $size= @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$cropped_image_name.""); ?>

    <div class="pageContent"><h3>Your cropped image</h3></div>
    <div class="pageContent"></div>

	<div class="pageContent" style="text-align: center; width: <?php print $size[0];?>px; margin: auto;">   
	<a href="blog_crop_save.php?action=saveCrop&pic_id=<?php print $_REQUEST['pic_id'];?>&date_id=<?php print $_REQUEST['date_id'];?>&thumb=<?php print $thumb_name;?>&mini=<?php print $mini_name;?>">Save</a>  &nbsp;&nbsp; 

	<a href="blog_crop_preview.php?pic_id=<?php print $pic['pic_id'];?>&date_id=<?php print $date['date_id'];?>&thumb=<?php print $thumb_name;?>&mini=<?php print $mini_name;?>&recrop=yes">Re-crop</a>   &nbsp;&nbsp; 

	<a href="" onClick="closeBlogCropping(); return false;" >Cancel</a> </div>



	<div style="margin: auto; text-align: center; width: <?php print $width;?>px; height: <?php print $height;?>px;">

			<img src="<?php print "/$thumb_name";?>">

	</div>
</body>
</html>

<?php
function ResizeImage($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup) {
	$imagex = imagecreatefromjpeg("$imagex"); 
	$width = imagesx($imagex);
	$height = imagesy($imagex);
	if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
		if($maxwidth && $width > $maxwidth){
			$widthratio = $maxwidth/$width;
			$RESIZEWIDTH=true;
		}
		if($maxheight && $height > $maxheight){
			$heightratio = $maxheight/$height;
			$RESIZEHEIGHT=true;
		}
		if($RESIZEWIDTH && $RESIZEHEIGHT){
			if($widthratio < $heightratio){
				$ratio = $widthratio;
			}else{
				$ratio = $heightratio;
			}
		}elseif($RESIZEWIDTH){
			$ratio = $widthratio;
		}elseif($RESIZEHEIGHT){
			$ratio = $heightratio;
		}
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageJpeg ($newim,$name . "$image_ext", 93);
		ImageDestroy ($newim);
	}else{
		ImageJpeg ($imagex,$name . "$image_ext", 93);
	}
	ImageDestroy ($imagex);
}
?>
