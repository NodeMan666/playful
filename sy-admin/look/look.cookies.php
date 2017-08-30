<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Cookie Warning</div> 
<?php if($_REQUEST['subdo'] == "save") { 
	updateSQL("ms_cookie_warn", "
	message='".addslashes(stripslashes($_REQUEST['message']))."',
	reject_url='".addslashes(stripslashes($_REQUEST['reject_url']))."',
	approve_button='".addslashes(stripslashes($_REQUEST['approve_button']))."',
	reject_link='".addslashes(stripslashes($_REQUEST['reject_link']))."',
	cookie_status='".addslashes(stripslashes($_REQUEST['cookie_status']))."'
	");
	$_SESSION['sm'] = "Fonts Saved";
	session_write_close();
	header("location: index.php?do=look&view=cookies");
	exit();
}
$cookies = doSQL("ms_cookie_warn", "*", "WHERE id='1' ");
?>

<div class="left p50">
	<div style="padding: 16px;">
		<div class="pc">The cookie warning will show a message to the visitor on first visit to your website that your website uses cookies. This is for the compliance of the EU cookie law.<br><br>
		
		</div>
	</div>
</div>

<div class="left p50">
	<div style="padding: 16px;">
		<form method="post" name="cookies" action="index.php">
		<input type="hidden" name="do" value="look">
		<input type="hidden" name="view" value="cookies">
		<input type="hidden" name="subdo" value="save">
		<div class="underlinelabel">
			<input type="checkbox" name="cookie_status" id="cookie_status" value="1" <?php if($cookies['cookie_status'] ==  "1") { print "checked"; } ?>> <label for="cookie_status">Enable cookie warning</label>
		</div>
	
		<div class="underline">
			<div class="label">Message</div>
			<textarea name="message" id="message" rows="5" cols="40" class="field100"><?php print $cookies['message'];?></textarea>
		</div>
	
		<div class="underline">
			<div class="label">Accept Button</div>
			<input type="text" name="approve_button" id="approve_button" size="40" class="field100" value="<?php print $cookies['approve_button'];?>">
		</div>

		<div class="underline">
			<div class="label">Reject Link</div>
			<input type="text" name="reject_link" id="reject_link" size="40" class="field100" value="<?php print $cookies['reject_link'];?>">
		</div>

		<div class="underline">
			<div class="label">Reject URL</div>
			<input type="text" name="reject_url" id="reject_url" size="40" class="field100" value="<?php print $cookies['reject_url'];?>">
		</div>

		<div class="pc">
		<input type="submit" name="submit" value="Save" class="submit">
		</div<
		</form>
	</div>
</div>