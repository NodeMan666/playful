<?php  
if($_POST['createuser'] == "yes") {
	if((empty($_REQUEST['username']))OR(empty($_REQUEST['password']))OR(empty($_REQUEST['passwordck']))==true) {
		die("<div class=cells style=\"text-align: center;\"><br><br><br><span class=error>You left something blank. Press back and try again.</span></div>");
	}
	if($_REQUEST['password']!==$_REQUEST['passwordck']) {
		
		header("location: index.php?err=1");
		session_write_close();
		die("<div class=cells style=\"text-align: center;\"><br><br><br><span class=error>Your passwords do not match. Press back and re-enter your password</span></div>");
	}

	$username = sql_safe("".$_REQUEST['username']."");
	$password = sql_safe("".$_REQUEST['password']."");
	$characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
	$salt = '';
	for ($i = 0; $i < 5; $i++) { 
		$salt .= $characters[mt_rand(0, 39)];
	}
	$password = md5($password.$salt);

	$admin_id = insertSQL("ms_admins", "admin_user='".addslashes(stripslashes($username))."', admin_pass='$password', admin_salt='".addslashes(stripslashes($salt))."', admin_name='".addslashes(stripslashes($_REQUEST['admin_name']))."', admin_email='".addslashes(stripslashes($_REQUEST['admin_email']))."', admin_master='1'  ");

	updateSQL("ms_settings", "contact_email='".addslashes(stripslashes($_REQUEST['admin_email']))."', website_title='".addslashes(stripslashes($_REQUEST['website_name']))."' , header='<p>".addslashes(stripslashes($_REQUEST['website_name']))."</p>' , meta_title='".addslashes(stripslashes($_REQUEST['website_name']))."' ");
	updateSQL("ms_gal_exclusive", "gal_site_title='".addslashes(stripslashes($_REQUEST['website_name']))."' ");

	updateSQL("ms_forms", "form_email_to='".addslashes(stripslashes($_REQUEST['admin_email']))."' ");

	if($_POST['email_me'] == "1") { 
		$message .= "<p><b>Your Sytist Information</b></p>";
		$message .= '<p>Main website link: <a href="'.$setup['url'].'/">'.$setup['url'].'</a></p>';
		$message .= '<p>Your administration link is: <a href="'.$setup['url'].$setup['temp_url_folder'].'/'.$setup['manage_folder'].'/">'.$setup['url'].$setup['temp_url_folder'].'/'.$setup['manage_folder'].'</a></p>';
		$message .= '<p>Admin username: '.$username.'</p>';
		if($_POST['email_me_pass'] == "1") { 
			$message .= '<p>Admin password: '.$_REQUEST['password'].'</p>';
		} else { 
			$message .= '<p>Admin password: *********** </p>';
		}
		$message .= '<p>&nbsp;</p>';
		$message .= '<p><a href="https://www.picturespro.com">PicturesPro.com</a></p>';
		$message .='<p>Do not reply to this email as this email address is not monitored.</p>';
		$subject = "Your Sytist Links & Information - Save This! ";
		sendWebdEmail("".$_REQUEST['admin_email']."", $_REQUEST['admin_name'],"no-reply@picturespro.com","no-reply PicturesPro", $subject, $message,"1");

	}

	$_SESSION['office_admin_login'] = "1";
	$_SESSION['office_admin'] = $username;
	$_SESSION['office_admin_id'] = $admin_id;
	$_SESSION['showwiz'] = true;
	insertSQL("ms_admin_logins",  "date='".date('Y-m-d H:i:s')."', ip='".getUserIP()."', user='".$username."' ");
	$_SESSION['sm'] = "Your log in has successfully been created";
	session_write_close();
	header("Location: index.php");
	exit();


?>
<div style="width: 400px; margin-left: auto; margin-right: auto;"><div class=cells><br><br><br><span class=success>Your login has been created.</span></div>
<div class=cells><a href="index.php">Click here to log in</a></div>
</div>

<?php 


} else {


?>

<div style="margin:auto; width: 480px; ">
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php if($_REQUEST['err'] == "1") { ?><div class="error">Your passwords did not match. Re-enter your log in information</div><?php } ?>
<div>&nbsp;</div>
<div>&nbsp;</div>

<div>
<div class="yellowmessage center"><b>BOOKMARK THIS PAGE NOW!</b><br>This is the link to your administration area.</div>

<div class="underlinelabel">Create Master Admin Username & Password</div>

<form name="login" method=POST action="index.php"  onSubmit="return checkForm();">

	<div id="">
	<?php if($setup['sytist_hosted'] == true) { ?>
	<div class="underline">Website / Business<br><input type=text name="website_name" id="website_name" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	<?php } ?>
	<div class="underline">Your Name<br><input type=text name="admin_name" id="admin_name" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	<div class="underline">Email Address<br><input type=text name="admin_email" id="admin_email" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	<div class="underline">Username (this will be used to log in with)<br><input type=text name="username" id="username" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	<div class="underline">Password<br><input type=password name="password"  id="password" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	<div class="underline">Re-type Password<br><input type=password name="passwordck"  id="passwordck" class="required" size=20 style="width: 98%; font-size: 21px;"></div>
	
		<div class="underline center"><input type="checkbox" name="email_me" id="email_me" value="1" checked> <label for="email_me">Email me my admin link & username</label> <br><input type="checkbox" name="email_me_pass" id="email_me_pass" value="1" checked> <label for="email_me_pass">and include my password</label></div>
	
	</div>
<div>&nbsp;</div>
	<div class="pageContent" style="text-align: center;">
	<input type="hidden" name="createuser" value="yes">
<input type=submit name="submit" class="submit" value="Create Login" id="submitButton">
</div>
<div>&nbsp;</div>

</form>
</div>
<script>document.login.username.focus();</script>
<?php  } ?>
