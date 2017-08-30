<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  Google Fonts List</div> 
<?php if($_REQUEST['subdo'] == "savefonts") { 
	updateSQL("ms_settings", "google_fonts='".$_REQUEST['google_fonts']."' ");
	$_SESSION['sm'] = "Fonts Saved";
	session_write_close();
	header("location: index.php?do=look&view=fonts");
	exit();
}
?>

<div>&nbsp;</div>
<div class="left p50">
	<div style="padding: 16px;">
		<div class="pc">To the right is a list of Google Fonts you have to choose from when editing your theme. Here you can update the list with new fonts if added or add the weight option.</div>
		<div class="pc">Understand this is the fonts <b>you choose from when editing your theme</b>. So do not delete from this list. </div>
	</div>
</div>

<div class="left p50">
	<div style="padding: 16px;">
		<form method="post" name="gogglefonts" action="index.php">
	<div class="pc">
		<input type="hidden" name="do" value="look">
		<input type="hidden" name="view" value="fonts">
		<input type="hidden" name="subdo" value="savefonts">
		<textarea name="google_fonts" id="google_fonts" rows="40" cols="40"><?php print $site_setup['google_fonts'];?></textarea>
		</div>
		<div class="pc">
		<input type="submit" name="submit" value="Save" class="submit">
		</div<
		</form>
	</div>
</div>