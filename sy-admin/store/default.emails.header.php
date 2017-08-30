<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=settings&action=defaultemails">Default Emails</a> <?php print ai_sep;?> Header & Footer</div> 
<div class="pc">
Here you can create a header & footer for all emails sent. The header is shown at the top of the email and the footer shown at the bottom.
<br><br>
When sending emails from the admin area, you won't see the header & footer, but will be added when the email is sent. 
</div>


<?php 
	$email_style = true;	
		$full_file_url = 1;

if($_REQUEST['submitit'] == "yes") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
	$id = updateSQL("ms_settings", "email_header='".$_REQUEST['email_header']."', email_footer='".$_REQUEST['email_footer']."'  ");   
	$_SESSION['sm'] =  "Changes Saved";
	session_write_close();
	header("location: index.php?do=settings&action=emailheader");
	exit();
} else {
	printForm();
}
	?>

<?php 
function printForm() {
	global $_REQUEST,$site_setup, $setup,$asettings;
	?>
	<div>&nbsp;</div>


<div id="roundedFormContain">



<div class="pageContent"><h2>Header</h2></div>
<form name="emh" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data" >

<div class="pageContent">
<textarea name="email_header" id="email_header" cols="40" rows="12" style="width: 98%;"><?php if(!empty($_SESSION['new_logo'])) { print htmlspecialchars(stripslashes($_SESSION['new_logo'])); unset($_SESSION['new_logo']); } else { print htmlspecialchars(stripslashes($site_setup['email_header'])); } ?></textarea>
<?php if($site_setup['html_editor'] !=="1") { ?>
	<?php 
		$email_style = true;
		$full_file_url = 1;

addEditor("email_header", "1", "300", "1"); ?>
<?php } ?>
</div>

<div class="pageContent">
<div class="pageContent"><h2>Footer</h2></div>

<textarea name="email_footer" id="email_footer" cols="40" rows="6"  rows="12" style="width: 98%;"><?php print htmlspecialchars(stripslashes($site_setup['email_footer']));?></textarea>
<?php if($site_setup['html_editor'] !=="1") { ?>
	<?php addEditor("email_footer", "2", "300", "0"); ?>
<?php } ?>
</div>
<div>&nbsp;</div>
<div class="pageContent" style="text-align: center;">
<input type="hidden" name="do" value="settings">
 <input type="hidden" name="action" value="emailheader">
 <input type="hidden" name="submitit" value="yes">
 <input type="submit" name="submit" value="Save Changes" class="submit">
 </div>
</form>
</div>

<div>&nbsp;</div>

<?php  } ?>
