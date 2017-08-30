<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Website Password</div>

<?php  
if($_POST['update'] == "yes") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}

	updateSQL("ms_settings", "site_password='".$_REQUEST['site_password']."' ");

	$_SESSION['sm'] = "Site Password Changes Saved";
	session_write_close();
	header("location: index.php?do=settings&action=sitePassword");
	exit();


} else {
	passwordForm();

}


function passwordForm() {
	global $site_setup;
	?>
<div id="roundedFormContain">
<div class="pageContent">By entering in a password below, this will password protect the ENTIRE website. This means anyone visiting the website will have to enter in the correct password to view the site.</div>
<div>&nbsp;</div>
		<form name="login" method=POST action="index.php" style="margin: 0px; padding: 0px;">
	<div id="roundedForm">



 <?php if(!empty($mess)) {  print "<div class=error>$mess</div>"; } ?>
	<div class="row">
		<div style="width:50%; float: left; text-align: right;">Site Password&nbsp; </div><div style="width:50%; float: right; text-align: left;"> <input type=text name="site_password" size=20 AUTOCOMPLETE="off" value="<?php print $site_setup['site_password'];?>"></div>
	<div class="cssClear"></div></div>


	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="sitePassword">
	<input type="hidden" name="update" value="yes">
<div class="row" style="text-align: center;"><input  type="submit" name="submit" class="submit" value=" Change "></div>
	</div>
</div>

</form>

<?php  } ?>