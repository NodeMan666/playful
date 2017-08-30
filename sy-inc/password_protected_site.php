<?php
	if($_REQUEST['action'] == "checkpass") {
		$pass =  doSQL("ms_settings", "*", "");
		if($_REQUEST['spass'] == $pass['site_password']) {
		$_SESSION['sitePasswordAccess'] = true;
		$_SESSION['sitePassword'] = $pass['site_password'];
		session_write_close();
		header("location: index.php");
		exit();
	} else {
		print "<div class=errorMessage>Incorrect Password</div>";
	//	galPassword($date_id);
//		exit();
	}
}

function siteLogin() {
	global $site_type;
	//print_r($_SESSION);
	?>
	<div id="passwordSite">
	<center><div style="margin: auto;"><div class="pageContent"><h1>Please enter the password below to enter the site.</h1></div>
	<form method="post" name="galpass" action="index.php" style="padding: 0; margin: 0;">
	<div class="pageContent">
	<input type="password" name="spass" size="15">
	<input type="hidden" name="action" value="checkpass">
	<input type="hidden" name="ppdid" value="<?php print MD5($date_id);?>">
	<input  type="submit" name="submit" value="Enter" class="submit">
	</div>
	</form>
	</div></center>
	</div>
	<?php
}
?>