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
date_default_timezone_set(''.$site_setup['time_zone'].'');
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
if(!empty($_REQUEST['pic_upload_session'])) { 
	$and_key = "AND pic_upload_session='".$_REQUEST['pic_upload_session']."'";
}

?>
<div id="sitecontent">
	<div class="info large left">Tags (<?php $tagcount = whileSQL("ms_photo_keywords LEFT JOIN ms_photo_keywords_connect ON ms_photo_keywords.id=ms_photo_keywords_connect.key_key_id LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id","*", " WHERE ms_photo_keywords.id>'0' $and_key GROUP BY ms_photo_keywords.key_word"); 
	print mysqli_num_rows($tagcount);?>)</div>
	<div class="clear"></div>
	<div class="info">
	<div class="inner">
	<?php
	$keys = whileSQL("ms_photo_keywords", "*",   "ORDER BY key_word ASC");
	if(mysqli_num_rows($keys)<=0) { 
		print "No tags found";
	}
	while($key = mysqli_fetch_array($keys)) {  
		$key_total = countIt("ms_photo_keywords_connect LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "WHERE key_key_id='".$key['id']."' $and_key ");
		if($key_total > 0) {  ?>
		<nobr><a href="index.php?do=allPhotos&did=<?php print $_REQUEST['did'];?>&bid=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&key_id=<?php print htmlspecialchars(stripslashes($key['id']));?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>"><?php print $key['key_word'];?> (<?php print $key_total;?>)</a> </nobr>&nbsp;
		<?php } else {
			//  deleteSQL("ms_photo_keywords", "WHERE id='".$key['id']."' ", "1");
		}
			?>
		<?php 
			unset($key_total);
			} ?>
	</div>
	<?php 
	$untaggeds = whileSQL("ms_photos","*", " WHERE ms_photos.pic_keywords='' $and_key"); 
	
	$untagged = mysqli_num_rows($untaggeds); 

	
	if($untagged > 0) { ?>
	<div class="pc"><a href="index.php?do=allPhotos&did=<?php print $_REQUEST['did'];?>&bid=<?php print $_REQUEST['bid'];?>&slide_id=<?php print $_REQUEST['slide_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&keyWord=untagged">Show untagged photos</a> (<?php print $untagged;?>)</div>
	<?php } ?>
	<div class="pc"><a href="" onclick="openFrame('w-tags-manage.php'); return false;">Manage Tags</a></div>
</div>

<div>&nbsp;</div>
<?php
	$cams = whileSQL("ms_photos", "*, COUNT(*) AS dups", "WHERE pic_id>'0'  $and_key AND pic_camera_model!='' GROUP BY pic_camera_model  ORDER BY dups DESC " );
if(mysqli_num_rows($cams)>0) { ?>
	<div class="info large left">Cameras</div>
	<div class="clear"></div>
	<div class="info">
	<div class="inner">
	<?php 
	while($cam = mysqli_fetch_array($cams)) { 
	?>
	<div class="pageContent"><?php  print "<a href=\"index.php?do=allPhotos&did=".$_REQUEST['did']."&sub_id=".$_REQUEST['sub_id']."&bid=".$_REQUEST['bid']."&slide_id=".$_REQUEST['slide_id']."&pic_upload_session=".$_REQUEST['pic_upload_session']."&pic_camera_model=".$cam['pic_camera_model']."\">".$cam['pic_camera']." -  ".$cam['pic_camera_model']."</a>";  ?> (<?php print $cam['dups'];?>) </div>
	<?php } ?>

	</div>
</div>
<div>&nbsp;</div>
<?php } ?>

<?php
$cams = whileSQL("ms_photos", "*, COUNT(*) AS dups", "WHERE pic_id>'0' AND pic_upload_session!='' GROUP BY pic_upload_session  ORDER BY pic_id DESC LIMIT 10" );
if(mysqli_num_rows($cams)>0) { ?>

	<div class="info large left">Recent Upload Sessions</div>
	<div class="clear"></div>
	<div class="info">
	<div class="inner">
	<?php 
	while($cam = mysqli_fetch_array($cams)) { 
	?>
	<div class="pageContent"><?php print "<a href=\"index.php?do=allPhotos&pic_upload_session=".$cam['pic_upload_session']."&acdc=ASC&pic_client=".$cam['pic_client']."&did=".$_REQUEST['did']."&sub_id=".$_REQUEST['sub_id']."\">".$cam['pic_upload_session']."</a>"; ?> (<?php print $cam['dups'];?>) 
	<?php if($cam['date_id'] > 0) { ?>
	<div style="padding-left: 16px;">
		[<?php print "<a href=\"index.php?do=news&action=managePhotos&date_id=".$cam['date_id']."\" target=\"_parent\">".$cam['date_title']."</a>"; ?>]
		</div>
	<?php } ?>
	</div>
	<?php } ?>

	</div>
</div>
<div>&nbsp;</div>
<?php } ?>


<?php
$ubs = whileSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND  pic_no_dis='0'" );

if(mysqli_num_rows($ubs)>0) { ?>
		<div class="info">
		<a href="index.php?do=allPhotos&view=unblogged"><?php print mysqli_num_rows($ubs);?> Photos Not Shown On Website</a></div>
<?php } ?>



<div class="cssClear"></div>
</div>
