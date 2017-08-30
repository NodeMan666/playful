<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
 <?php if(!empty($_SESSION['logoutmessage'])) {  print "<div class=success>You have been logged out.</div>"; unset($_SESSION['logoutmessage']); } ?>
<?php  

if($_POST['laction'] == "forgotLogin") {
	?>
<?php
	$_POST['admin_email'] = sql_safe("".$_POST['admin_email']."");

	$ck = doSQL("ms_admins", "*", "WHERE admin_email='".$_POST['admin_email']."' ");
	if(empty($ck['admin_email'])) {
		print "<div>&nbsp;</div><div>&nbsp;</div><div id=pageTitle style=\"text-align: center;\"><h1>Forgot Username / Password</h1></div><div class=error style=\"text-align: center;\">Sorry, that email address is not found. Press back if you would like to try again.</div>";
	} else {

	$link_code = md5(date('YmdHis'));
	$pin = rand(1000,9999);
	insertSQL("ms_admin_reset", "reset_code='$link_code', reset_pin='".md5($pin)."', reset_admin='".$ck['admin_id']."' ");
		$from_email = $site_setup['contact_email'];
		$to_email = $site_setup['contact_email'];
		$from_name = $site_setup['website_name'];
		$to_name = $site_setup['website_name'];
		$admin_folder = str_replace("../", "", $setup['admin_folder']);

		$subject = "Login reset for ".$site_setup['website_title']."";
		$message2 .= "Hello,\r\nYou or someone with the IP address of ".getUserIP()." has requested to reset the login for your Sytist admin. This email has only been sent to you. \r\n\r\nTo continue with the reset, click on the link below, then enter in the pin number below on that page.\r\n\r\n";
		$message2 .= $setup['url'].$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=reset&rsk=".$link_code."\r\n\r\nPin number: $pin\r\n\r\n\n\n";
		stripslashes($message2);
		sendWebdEmail( $from_email, $from_name, $to_email, $to_name, $subject, $message2,$type);

		print "<div>&nbsp;</div><div>&nbsp;</div><div id=pageTitle style=\"text-align: center;\"><h1>Check your email</h1><br>An email has been sent to you with further instructions.<br><br><a href=\"index.php\">Return to login screen</a></div>";

	}

	?>
	</div></td></tr></table>
	<?php
exit();
}

if($_POST['trylogin'] == "yes") {
	if((empty($_REQUEST['username']))||(empty($_REQUEST['password'])) ==true) { 
		$mess = "You left something blank";
	}
	if(empty($mess)) { 
		$username = sql_safe("".$_REQUEST['username']."");
		$password = sql_safe($_REQUEST['password']);

		$admin = doSQL("ms_admins", "*", "WHERE admin_user='".$username."' ");
		if(empty($admin['admin_id'])) { 
			$failed = true;
		} else { 
			$pass = MD5($_REQUEST['password'].$admin['admin_salt']);
			$admin = doSQL("ms_admins", "*", "WHERE admin_user='".$username."' AND admin_pass='".$pass."' ");
			if(empty($admin['admin_id'])) {
				$failed = true;
			} else { 
				$_SESSION['office_admin_login'] = "1";
				$_SESSION['office_admin'] = $admin['admin_user'];
				$_SESSION['office_admin_id'] = $admin['admin_id'];
				insertSQL("ms_admin_logins",  "date='".date('Y-m-d H:i:s')."', ip='".getUserIP()."', user='".$username."', log_admin_id='".$admin['admin_id']."' ");
				if(!empty($_SESSION['admin_ret'])) {
					session_write_close();
					header("Location: ".$_SESSION['admin_ret']." ");
					exit();
				} else {
					session_write_close();
					header("Location: index.php");
					exit();
				}
			}
		}
	}
}
if($failed == true) { 
	insertSQL("ms_admin_logins",  "date='".date('Y-m-d H:i:s')."', ip='".getUserIP()."', user='".$username."', login_failed='1', login_failed_pass='".addslashes(stripslashes($password))."' ");

	$mess = "Username and or password is incorrect. ";
}


?>

	<br><br>
<body onload="document.login.username.focus()">


<div style="margin:auto; max-width: 480px; ">

<div style="padding: 20px 0 0">
<div class="pc center"><h1><?php if($setup['unbranded'] !== true) { ?>Sytist <?php } ?>Admin Login</h1></span></div>
<?php if(!empty($site_setup['website_title'])) { ?>
<div class="pc center"><h2><?php print $site_setup['website_title'];?></h2></div>
<?php } ?>
 <?php if(!empty($mess)) {  print "<div class=error>$mess</div>"; } ?>

<form name="login" method=POST action="index.php" style="margin: 0px; padding: 0px;">
	<div id="">
	<div class="pc">Username<br><input type=text name="username" size=20 style="width: 98%; font-size: 28px;" <?php if($setup['demo_mode'] == true) { print "value=\"demo\""; } ?>></div>
	<div class="pc">Password<br><input type=password name="password" size=20 style="width: 98%; font-size: 28px;" <?php if($setup['demo_mode'] == true) { print "value=\"demo\""; } ?>></div>

	<div class="pc center">
		<input type="hidden" name="do" value="login">
	<input type="hidden" name="trylogin" value="yes">
	<input  type="submit" name="submit" class="submit" value="Log In">
	</div>
</div>


</form>
<div class="pageContent">&nbsp;</div>
<?php if($setup['demo_mode'] !== true) { ?>
<div id="forgot-open-link"><center><a href="#" onclick="openClose('forgot-open','forgot-open-link'); return false;" style="cursor:pointer;" id="forgot-link" >forgot login / password</a></center></div>
<?php } ?>
<?php if($setup['demo_mode'] == true) {?>
<div class="pc"><h3>DEMO MODE ON</h3>Note many features such as saving changes, uploading files & deleting are disabled for the demo.</div>
<?php } ?>
<div>&nbsp;</div>
<div id="forgot-open" style="display: none">

<form method="post" name="forgotLogin" action="index.php" style="margin: 0px; padding: 0px;">

<div class=padTopBottom>Enter your email address below that is in your <i>sytist</i> admin</div>
<div class=padTopBottom><input type="text" name="admin_email" size="40"></div>
<div class=padTopBottom>
<input type="hidden" name="do" value="login">
<input type="hidden" name="laction" value="forgotLogin">
<button type="submit" name="submit">Continue</button>
</div>
</form>
</div>
</div>


<div width="100%" title="<?php  print "".$reg['reg_key'].""; ?>">&nbsp;</div>
</div>
<script>document.login.username.focus();</script>
