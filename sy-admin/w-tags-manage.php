<?php require "w-header.php"; ?>
<?php
if(!empty($_REQUEST['deleteTag'])) { 
	if($setup['demo_mode'] == true) { 
	$_SESSION['sm'] = "Disabled in demo.";

	} else { 
		$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['deleteTag']."' ");
		$photos = whileSQL("ms_photos", "*", "WHERE pic_keywords LIKE '%".addslashes($tag['key_word']).",%' ");
		while($photo = mysqli_fetch_array($photos)) { 
			print "<li>".$photo['pic_keywords']." = ";
			$new = str_replace($tag['key_word'].",", "",$photo['pic_keywords']);
			print "<li>$new";
			updateSQL("ms_photos", "pic_keywords='".addslashes(stripslashes(trim($new)))."' WHERE pic_id='".$photo['pic_id']."' ");
			unset($new);
		}
		deleteSQL("ms_photo_keywords", "WHERE id='".$tag['id']."' ", "1");
		deleteSQL2("ms_photo_keywords_connect", "WHERE key_key_id='".$tag['id']."' ");
		$_SESSION['sm'] = "Tag \"".$tag['key_word']."\" was deleted";

	}

	session_write_close();
	header("location: w-tags-manage.php");
	exit();

}

if($_REQUEST['subdo']=="renameTag") { 
	if($setup['demo_mode'] == true) { 
	$_SESSION['sm'] = "Disabled in demo.";

	} else { 

		$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['tag_id']."' ");
		$photos = whileSQL("ms_photos", "*", "WHERE pic_keywords LIKE '%".addslashes($tag['key_word']).",%' ");
		$new_tag = strtolower(trim($_REQUEST['new_name']));

		while($photo = mysqli_fetch_array($photos)) { 
			print "<li>".$photo['pic_keywords']." = ";
			$new = str_replace($tag['key_word'].",",$new_tag.",",$photo['pic_keywords']);
			print "<li>NEW: $new";
			updateSQL("ms_photos", "pic_keywords='".addslashes(stripslashes(trim($new)))."' WHERE pic_id='".$photo['pic_id']."' ");
			unset($new);
		}
		updateSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes(trim($new_tag)))."' WHERE id='".$tag['id']."' ");
	$_SESSION['sm'] = "Tag \"".$tag['key_word']."\" was renamed to \"$new_tag\" ";
	}

	session_write_close();
	header("location: w-tags-manage.php");
	exit();

}

?>
<div class="windowPadding">

<div class="pageContent" style="float: left;"><h2>Manage Photo Tags</h2></div>
<div class="cssClear"></div>
<div>&nbsp;</div>
<?php if(!empty($_SESSION['sm'])) { ?>
	<script>
	showSuccessMessage("<?php print addslashes($_SESSION['sm']);?>");
	setTimeout(hideSuccessMessage,5000);
	</script>

<?php unset($_SESSION['sm']); 
}
?>
	<div id="roundedFormContain">

<?php
	$keys = whileSQL("ms_photo_keywords", "*",   "ORDER BY key_word ASC");
	while($key = mysqli_fetch_array($keys)) {  
		$key_total = countIt("ms_photo_keywords_connect", "WHERE key_key_id='".$key['id']."' ");
		if($key_total > 0) {  ?>
	<div id="roundedForm">
		<div class="row">
			
			<form method="post" name="rename=<?php print $key['id'];?>" action="w-tags-manage.php">
			<input type="text" name="new_name" size="40" value="<?php print htmlspecialchars(stripslashes($key['key_word']));?>">
			<input type="hidden" name="do" value="news">
			<input type="hidden" name="action" value="photoTags">
			<input type="hidden" name="subdo" value="renameTag">
			<input type="hidden" name="tag_id" value="<?php print $key['id'];?>">
			<input type="submit" name="submit" value="Rename" class="submitSmall">
			&nbsp; <a href="w-tags-manage.php?do=news&action=photoTags&deleteTag=<?php print $key['id'];?>"  onClick="return confirm('Are you sure you want to delete this tag?\r\nThis will remove the tag from the system and remove them from the photos.');">Delete</a> &nbsp; 
			(<?php print $key_total;?>)
			</div>
			</form>
</div>	
		<?php } else {
			deleteSQL("ms_photo_keywords", "WHERE id='".$key['id']."' ", "1");
		}
			?>
		<?php 
			unset($key_total);
			} ?>
	</div>

</div>
<div>&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>
