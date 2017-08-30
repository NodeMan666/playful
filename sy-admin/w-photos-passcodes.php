<?php require "w-header.php"; ?>
<?php 
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

$num_pics = countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' "); 


if($_REQUEST['action'] == "copypastepasscodes") { 
	$codes = explode("\r\n",$_POST['passcodes']);

	foreach($codes AS $code) { 
		$tc = explode(",",$code);
		print "<li>".$tc[0]." -> ".$tc[1];
		$pic = doSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND pic_org='".$tc[0]."' ");
		if(!empty($pic['pic_id'])) { 
			updateSQL("ms_photos", "pic_title='".addslashes(stripslashes(trim($tc[1])))."' WHERE pic_id='".$pic['pic_id']."' ");
			print "<li>Adding passcode";
			$total_add++;
		}
	}

	$_SESSION['sm'] = "Passcodes added to $total_add photos";
	session_write_close();
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."");
	exit();

}



if($_REQUEST['action'] == "importpasscodes") { 

	$imported = file_get_contents_utf8($_FILES['file']['tmp_name']); 
	//$imported = mb_convert_encoding($imported, 'HTML-ENTITIES', "UTF-8");
	$import = explode("\r\n",$imported);
	foreach($import AS $code) {
		$tc = explode(",",$code);
		print "<li>".$tc[0]." -> ".$tc[1];
		$pic = doSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND pic_org='".$tc[0]."' ");
		if(!empty($pic['pic_id'])) { 
			updateSQL("ms_photos", "pic_title='".addslashes(stripslashes(trim($tc[1])))."' WHERE pic_id='".$pic['pic_id']."' ");
			print "<li>Adding passcode";
			$total_add++;
		}
	}
	$_SESSION['sm'] = "Passcodes added to $total_add photos";
	session_write_close();
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."");
	exit();

}

?>
<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Display order updated");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<div class="pc"><h1>Import Photo Passcodes</h1></div>
<div class="pc">Here you can import a CSV file that contains File Name,Passcode. Click below to find the CSV file to upload.</div>
<form name="upp" id="upp" action="<?php print $_SERVER['PHP_SELF'];?>" method="post"  enctype="multipart/form-data">
<div class="pc">
<input type="file" name="file" id="file"> 
<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
<input type="hidden" name="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
<input type="hidden" name="action" value="importpasscodes">
<input type="submit" name="submit" value="Process" class="submitSmall">
</div>
</form>
<div>&nbsp;</div>
<div class="pc">You can also copy and paste the passcodes below. They must be formatted like <b>File name,Passcode</b>, ONE PER LINE.</div>
<form name="orderp" id="orderp" action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
<div class="pc">
<textarea name="passcodes" id="passcodes" rows="12" cols="40" class="field100"></textarea>
</div>
<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
<input type="hidden" name="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
<input type="hidden" name="action" value="copypastepasscodes">
<input type="submit" name="submit" value="Process" class="submitSmall">
</form>
</div>

</div>





<?php require "w-footer.php"; ?>