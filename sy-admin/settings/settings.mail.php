<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Mail Sending Settings</div>
<?php 
$debugmail = true;
if(!empty($_REQUEST['submitit'])) {

	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}

	updateSQL("ms_settings", "mail_type='".$_REQUEST['mail_type']."',
	mail_return_path='".$_REQUEST['mail_return_path']."',
	smtp_host='".$_REQUEST['smtp_host']."',
	smtp_username='".$_REQUEST['smtp_username']."',
	smtp_password='".$_REQUEST['smtp_password']."',
	smtp_secure='".$_REQUEST['smtp_secure']."', 
	smtp_port='".$_REQUEST['smtp_port']."'

	");

	$_SESSION['sm'] = "Settings saved";
	session_write_close();
	header("location: index.php?do=settings&action=mail");
	exit();
}
?>	
<script>
function selectmailfunction() { 
	if($("#mail_type1").attr("checked")) { 
		$("#phpmailsetting").slideUp(200);
		$("#smtpsetting").slideDown(200);
	} else { 
		$("#phpmailsetting").slideDown(200);
		$("#smtpsetting").slideUp(200);
	}

}
</script>


<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">
<div class="left" style="width: 48%;">

<div class="underlinelabel">Mail Sending Function</div>
<div class="underlinespacer">The PHP mail() function is most commonly used.</div>
<div class="underline">
<input type="radio" name="mail_type" id="mail_type0" value="0" <?php if($site_setup['mail_type'] == "0") { print "checked"; } ?> onchange="selectmailfunction();"> <label for="mail_type0">PHP mail() function</label> &nbsp;&nbsp; 
<input type="radio" name="mail_type" id="mail_type1" value="1" <?php if($site_setup['mail_type'] == "1") { print "checked"; } ?>  onchange="selectmailfunction();"> <label for="mail_type1"> SMTP PHPMailer</label> 
</div>

	<div id="phpmailsetting" class="<?php if($site_setup['mail_type'] == "1") { print "hide"; } ?>">
		<div class="underlinelabel">Add return path field in email headers</div>
		<div class="underlinespacer">With the PHP mail() function sometimes the return path of an email is set to a server address and not yours so you do not receive bounced emails. But this option is here incase the addition of the return path parameter causing problems with emails sending. Set to Yes unless you are having problems with emails going out.</div>
		<div class="underline">
		<input type="radio" name="mail_return_path" id="mail_return_path0" value="0" <?php if($site_setup['mail_return_path'] == "0") { print "checked"; } ?>> <label for="mail_return_path0">No</label> &nbsp;&nbsp; 
		<input type="radio" name="mail_return_path" id="mail_return_path1" value="1" <?php if($site_setup['mail_return_path'] == "1") { print "checked"; } ?>> <label for="mail_return_path1"> Yes</label> 
		</div>
	</div>

	<div id="smtpsetting"  class="<?php if($site_setup['mail_type'] == "0") { print "hide"; } ?>">
		<div class="underlinelabel">SMTP Settings</div>
		<div class="underlinespacer"></div>

		<div class="underline">
			<div class="label">SMTP Host</div>
			<div><input type="text" name="smtp_host" id="smtp_host" value="<?php print $site_setup['smtp_host'];?>" size="30"></div>
		</div>

		<div class="underline">
			<div class="label">SMTP Username</div>
			<div><input type="text" name="smtp_username" id="smtp_username" value="<?php print $site_setup['smtp_username'];?>" size="30"></div>
		</div>

		<div class="underline">
			<div class="label">SMTP Password</div>
			<div><input type="text" name="smtp_password" id="smtp_password" value="<?php print $site_setup['smtp_password'];?>" size="30"></div>
		</div>

		<div class="underline">
			<div class="label">SMTP Encryption</div>
			<div><input type="text" name="smtp_secure" id="smtp_secure" value="<?php print $site_setup['smtp_secure'];?>" size="6"></div>
			<div>This will be something like "ssl" or "tls"</div>
		</div>

		<div class="underline">
			<div class="label">SMTP Port</div>
			<div><input type="text" name="smtp_port" id="smtp_port" value="<?php print $site_setup['smtp_port'];?>" size="6"></div>
			<div>Example: 587</div>
		</div>
	</div>

	<div  class="bottomSave">
		<input type="hidden" name="do" value="settings">
		<input type="hidden" name="action" value="mail">
		<input type="hidden" name="submitit" value="yup">
		<input type="submit" name="submit" id="submit" class="submit" value="Update Settings">
	</div>

</div>
</form>




<div class="right" style="width: 48%;">
<?php
if($_REQUEST['subdo'] == "testEmail") {
	?>
	<div class="pc">
	<h3>Sending email. Message (if any): </h3>
	<p><pre>
	<?php 
	$from_email = $site_setup['contact_email'];
	$from_name = $site_setup['website_title'];
	$subject = "Test email from $from_name";
	$message  = "Since you have received this email your email function is working correctly for ".$setup['url'].$setup['temp_url_folder']."";
	sendWebdEmail("".$_REQUEST['to_email']."", "".$_REQUEST['to_email']."", "$from_email", "$from_name", $subject, $message,$type);
	?>
	</pre>
	</p>
	</div>
	<div>&nbsp;</div>
	<?php 
}

?>

	<div class="underlinelabel">Send test email</div>
	
	<div class="underline">
	<div class="label">Enter an email address to send a test email to:</div>
	 <div>
	<form method="post" name="testemail" action="index.php"><input type="text" name="to_email" size="30" value="<?php print $site_setup['contact_email'];?>">

	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="mail">
	<input type="hidden" name="subdo" value="testEmail">
	<button type="submit" name="submit" class="submitSmall">Send test email</button>
	</form>
		</div>
	</div>

	<div>&nbsp;</div>
	<div class="pc center">If you are having trouble getting emails sent from the site, <a href="https://www.picturespro.com/sytist-manual/settings/mail-sending-settings/" target="_blank">see trouble shooting information here</a>.</div>

</div>
<div class="cssClear"></div>
</div>
<div class="cssClear"></div>
