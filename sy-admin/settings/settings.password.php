<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Admin Username & Password</div>

<?php  
if($_POST['update'] == "yes") {


	if(empty($_REQUEST['username'])) {
		$error .= "<div>You did not enter a username</div>";
	}
	if(empty($_REQUEST['password'])) {
		$error .= "<div>You did not enter a password</div>";
	}


	if($_REQUEST['password']!==$_REQUEST['passwordck']) {
		$error .= "<div>Your passwords do not match. Re-enter the passwords below. </div>";
	}

if(!empty($error)) {
	print "<div class=error>$error</div><div>&nbsp;</div>";
	passwordForm();
} else {

	$username = sql_safe("".$_REQUEST['username']."");
	$password = md5($_REQUEST['password']);
	$admins = @mysqli_query($dbcon,"UPDATE ms_admin SET admin_user='$username', admin_pass='$password' ");
	if (!$admins) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
?>
<div>&nbsp;</div>
<div id=success><center><b>Your username & password has been changed.</div>
<div>&nbsp;</div><div>&nbsp;</div>

<?php 
}

} else {
	passwordForm();

}


function passwordForm() {
	?>
<div id="roundedFormContain">

		<form name="login" method=POST action="index.php" style="margin: 0px; padding: 0px;">
	<div id="roundedForm">
		<div class="label">Use the form below to change your main admin username and password.</div>



 <?php if(!empty($mess)) {  print "<div class=error>$mess</div>"; } ?>
	<div class="row">
		<div style="width:50%; float: left; text-align: right;">New Username&nbsp; </div><div style="width:50%; float: right; text-align: left;"> <input type=text name="username" size=20 AUTOCOMPLETE="off" value="<?php print $_REQUEST['username'];?>"></div>
	<div class="cssClear"></div></div>

	<div class="row"><div style="width:50%; float: left; text-align: right;" >New Password&nbsp; </div><div style="width:50%; float: right; text-align: left;"><input type=password name="password" size=20 value="" AUTOCOMPLETE="off"></div>
	<div class="cssClear"></div></div>
	<div class="row"><div style="width:50%; float: left; text-align: right;" >Re-type New Password&nbsp; </div><div style="width:50%; float: right; text-align: left;"><input type=password name="passwordck" size=20 AUTOCOMPLETE="off"></div>
	<div class="cssClear"></div></div>

	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="password">
	<input type="hidden" name="update" value="yes">
<div class="row" style="text-align: center;"><input  type="submit" name="submit" class="submit" value=" Change "></div>
	</div>
</div>

</form>

<?php  } ?>