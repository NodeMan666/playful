<?php require "w-header.php"; ?>
<?php include $setup['path']."/".$setup['manage_folder']."/store/options.edit.php"; ?>

<?php



if($_POST['submitit']=="yes") { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	foreach($_SESSION['heldPhotos'] AS $pic_id) { 
		$pic = doSQL("ms_blog_photos", "*", "WHERE bp_pic='".$pic_id."' AND bp_blog='".$_REQUEST['date_id']."' ");
		print "<li>".$pic['bp_id']." - ".$_REQUEST['new_list']." ";
		if((empty($pic['bp_id'])) && (!empty($date['date_photo_keywords'])) == true) { 
			insertSQL("ms_blog_photos", "bp_pic='".$pic_id."', bp_blog='".$date['date_id']."', bp_pl='".$_REQUEST['new_list']."' ");
			print "<li>Inserting ".$pic_id;
		} else {
			updateSQL("ms_blog_photos", "bp_pl='".$_REQUEST['new_list']."' WHERE bp_id='".$pic['bp_id']."' ");
			print "<li>Updating ".$pic_id;
		}
	}
	$_SESSION['sm'] = "Price Lists Updated";
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	session_write_close();
	exit();
}
?>


<?php if($_REQUEST['do'] == "editOption") { 
	
	?>
	<form name="editoptionform" action="w-change-pricelist.php" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underlinelabel">
	Override price list for <?php print count($_SESSION['heldPhotos']);?> photos

	</div>
		<div class="underline">
			<div class="label">Select a price list below</div>
			<div>
			<select name="new_list" id="new_list">
			<option value="">No custom price list</option>
			<?php $ls = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
			while($l = mysqli_fetch_array($ls)) { ?>
			<option value="<?php print $l['list_id'];?>"><?php print $l['list_name'];?></option>
			<?php } ?>
			</select>
			</div>
		</div>


<div>&nbsp;</div>

<div class="pageContent center">

<input type="hidden" name="date_id" value="<?php print $_REQUEST['date_id'];?>">
<input type="hidden" name="submitit" value="yes">


<input type="submit" name="submit" value="Save" class="submit" id="submitButton">
<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
</div>

</form>
<?php } ?>



<?php require "w-footer.php"; ?>
