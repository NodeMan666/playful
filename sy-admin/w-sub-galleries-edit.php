<?php require "w-header.php"; ?>
<?php $date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' "); ?>

<?php
if($_POST['submitit']=="yes") { 
	$_REQUEST['sub_pass'] = trim($_REQUEST['sub_pass']);
	updateSQL("ms_sub_galleries", "sub_name='".addslashes(stripslashes($_REQUEST['sub_name']))."', sub_descr='".addslashes(stripslashes($_REQUEST['sub_descr']))."', sub_pass='".addslashes(stripslashes($_REQUEST['sub_pass']))."', sub_price_list='".$_REQUEST['sub_price_list']."', sub_green_screen_backgrounds='".$_REQUEST['sub_green_screen_backgrounds']."' , sub_no_green_screen='".$_REQUEST['sub_no_green_screen']."'  WHERE sub_id='".$_REQUEST['sub_id']."' ");

	$_SESSION['sm'] = "Sub gallery updated";
	//header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	//session_write_close();
	//exit();
}
?>

<?php 
$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
?>


	<div style="width: 500px; margin: auto;">
	<div class="pc"><h1>	<?php if($date['green_screen_gallery'] == "1") { ?>Edit Folder<?php } else { ?>Edit sub gallery<?php } ?></h1></div>

		<div class="underline">
			<div class="label">Name</div>
			<div><input type="text" name="sub_name" id="sub_name" size="20"  class="required field100 formfield" value="<?php  print htmlspecialchars(stripslashes($sub['sub_name']));?>"></div>
		</div>

		<?php if($date['green_screen_gallery'] !== "1") { ?>

		<div class="underline">
			<div class="label">Description</div>
			<div><textarea name="sub_descr" id="sub_descr" cols="20" rows="3" class="field100 formfield"><?php  print htmlspecialchars(stripslashes($sub['sub_descr']));?></textarea></div>
		</div>

		<div class="underline">
			<div class="label">Password</div>
			<div><input type="text" name="sub_pass" id="sub_pass" size="20"  class="field100 formfield" value="<?php  print htmlspecialchars(stripslashes($sub['sub_pass']));?>"></div>
		</div>

		<div class="underline">
			<div class="label">Price List</div>
			<div>
			<select name="sub_price_list" id="sub_price_list" class="formfield">
			<option value="0">Use default price list</option>
			<?php $pls = whileSQL("ms_photo_products_lists","*", "ORDER BY list_name ASC ");
			while($pl = mysqli_fetch_array($pls)) { ?>
			<option value="<?php print $pl['list_id'];?>" <?php if($pl['list_id'] == $sub['sub_price_list']) { print "selected"; } ?>><?php print $pl['list_name'];?></option>
			<?php } ?>
			</select>
			</div>
		</div>
	<?php } ?>
		<?php if($date['green_screen_backgrounds'] > 1) { ?>
		<div class="underline">
			<div class="label">Green Screen Backgrounds</div>
			<div>
				<select name="sub_green_screen_backgrounds" id="sub_green_screen_backgrounds" class="formfield">
				<option value="">Use Default</option>
				<?php $gs = whileSQL("ms_sub_galleries LEFT JOIN ms_calendar ON ms_sub_galleries.sub_date_id=ms_calendar.date_id", "*", "WHERE  ms_calendar.green_screen_gallery='1'  ORDER BY sub_name ASC ");
				while($g = mysqli_fetch_array($gs)) { ?>
				<option value="<?php print $g['sub_id'];?>" <?php if($sub['sub_green_screen_backgrounds'] == $g['sub_id']) { print "selected"; } ?>><?php print $g['sub_name'];?></option>
				<?php } ?>
				</select>
			</select>
			</div>
			<div>or</div>
			<div><input type="checkbox" name="sub_no_green_screen" id="sub_no_green_screen" class="formfield" value="1" <?php if($sub['sub_no_green_screen'] == "1") { print "checked"; } ?>> <label for="sub_no_green_screen">Do not use green screen backgrounds in this gallery</label></div>
		</div>

		<?php } ?>


		<div class="clear"></div>
		<div class="pc center">
		<input type="hidden" name="submitit" value="yes" class="formfield">
		<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>" class="formfield">
		<input type="hidden" name="sub_id" value="<?php print $sub['sub_id'];?>" class="formfield">
		</div>
		</div>
		<div class="pc center">
		<span class="saveform" onclick="savedata('formfield','<?php print $_SERVER['PHP_SELF'];?>','index.php?do=news&action=managePhotos&date_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>'); return false;">Save</span>
		</div>
<?php require "w-footer.php"; ?>
