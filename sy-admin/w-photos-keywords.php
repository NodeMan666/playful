<?php require "w-header.php"; ?>

<?php 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
if($_REQUEST['subdo']=="updateSettings") {

	if(is_array($_REQUEST['e_tags'])) { 
		foreach($_REQUEST['e_tags'] AS $id => $val) {
			$x++;
			$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$id."' ");
			if($x > 1) { 
				$tag_keys .= ",".$tag['id'];
			} else { 
				$tag_keys .= $tag['id'];
			}
		//	insertSQL("ms_tag_connect", "tag_tag_id='".$id."', tag_date_id='".$date['date_id']."' ");
		}
	}

		updateSQL("ms_calendar", "date_photo_keywords='".$tag_keys."', date_photos_keys_acdc='".$_REQUEST['date_photos_keys_acdc']."', date_photos_keys_orderby='".$_REQUEST['date_photos_keys_orderby']."'  WHERE date_id='".$_REQUEST['date_id']."' ");
		$_SESSION['sm'] = "Settings Saved";
		session_write_close();
		header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&showSuccess=1");
		exit();
}
?>
<?php if($_REQUEST['showSuccess'] == "1") { ?>
		<script>
		showSuccessMessage("Settings Saved");
		setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>

<div class="pc"><h1>Photos By Tags (keywords)</h1>
This feature allows you to display photos based on tags (keywords) in photos in your system. By enabling any tags  here, the display order of the photos is based on the settings below. This also disables the use of sub galleries. <a href="https://www.picturespro.com/sytist-manual/articles/creating-galleries-with-photo-keywords/" target="_blank">Please see this article on using this feature</a>.
</div>
<div>&nbsp;</div>
<div class="pc">Select the tags below to display in this gallery.</div>





<form name="register" action="<?php print $_SERVER['PHP_SELF'];?>" method="post" style="padding:0; margin: 0;" onSubmit="return checkForm('.optrequired');">
<div class="underline" style="font-size: 17px;">
<?php 
$date_tags = explode(",",$date['date_photo_keywords']);

$tags = whileSQL("ms_photo_keywords", "*", "ORDER BY key_word ASC ");
if(mysqli_num_rows($tags) <=0) { print "No tags have been created"; } 
while($tag = mysqli_fetch_array($tags)) { 
		?>
<span id="span-tag-<?php print $tag['id'];?>" class="<?php if(in_array($tag['id'], $date_tags)) { print "tagselected"; } else { print "tagunselected"; }  ?>"><nobr>
<input type="checkbox" id="e-tag-<?php print $tag['id'];?>" name="e_tags[<?php print $tag['id'];?>]" value="<?php print $tag['id'];?>" class="editinfo" <?php  if(in_array($tag['id'], $date_tags)) {  print "checked"; } ?> onclick="checkTag('<?php print $tag['id'];?>');"> <label for="e-tag-<?php print $tag['id'];?>"><?php print $tag['key_word'];?> (<?php print countIt("ms_photo_keywords_connect LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "WHERE key_key_id='".$tag['id']."' $and_key ");?>)</label> </nobr></span>, 
<?php } ?>
</div>

<div class="underline">
	Order By:  &nbsp;
	<select name="date_photos_keys_orderby">
	<option value="pic_org" <?php if($date['date_photos_keys_orderby'] == "pic_org") { print "selected"; } ?>>File Name</option>
	<option value="pic_id" <?php if($date['date_photos_keys_orderby'] == "pic_id") { print "selected"; } ?>>Date Uploaded</option>
	<option value="pic_date_taken" <?php if($date['date_photos_keys_orderby'] == "pic_date_taken") { print "selected"; } ?>>Date / Time Taken</option>
	</select>

	<select name="date_photos_keys_acdc">
	<option value="ASC" <?php if($date['date_photos_keys_acdc'] == "ASC") { print "selected"; } ?>>Ascending</option>
	<option value="DESC" <?php if($date['date_photos_keys_acdc'] == "DESC") { print "selected"; } ?>>Decending</option>
	</select>
</div>
<div class="underline" style="text-align: center;"> 
	<input type="hidden" name="do" value="<?php print $_REQUEST['do'];?>">
	<input type="hidden" name="action" value="managePhotos">
	<input type="hidden" name="subdo" value="updateSettings">
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<input type="hidden" name="gal_id" value="<?php  print $pb['gal_id'];?>">
	<input type="submit" name="submit" value="Update Options" class="submit"  id="submitButton">
</div>


</form>
<div>&nbsp;</div>	
<?php include "infos.php"; ?>


<?php require "w-footer.php"; ?>
