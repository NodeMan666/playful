<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>
<?php 
if($_REQUEST['submitit'] == "send") {

	$message = stripslashes($_REQUEST['message']);
	$subject = stripslashes($_REQUEST['subject']);

	sendWebdEmail("".$_REQUEST['to_email']."", "".$_REQUEST['to_email']."", "".$_REQUEST['from_email']."", "".$_REQUEST['from_name']."", $subject, $message,"1");
	?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div class="pc center"><h3>Your message has been sent to <?php print $_REQUEST['to_email'];?></h3><br><br>
	<a href="javascript:closeFrame();">Close this window</a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<?php 

} else {


	$em = doSQL("ms_emails", "*", "WHERE email_id='3' ");
	if(empty($em['email_from_email'])) {
		$from_email = $site_setup['contact_email'];
	} else {
		$from_email = $em['email_from_email'];
	}

	if(empty($em['email_from_name'])) {
		$from_name = $site_setup['website_title'];
	} else {
		$from_name = $em['email_from_name'];
	}
	$subject = "".$em['email_subject']."";
	

	$to_email = $_REQUEST['email_to'];
	$to_name = stripslashes($_REQUEST['email_to_first_name'])." ".stripslashes($_REQUEST['email_to_last_name']);
	$message = $em['email_message'];


	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
	$message = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$message");
	$message = str_replace("[EMAIL]",$to_email, "$message");

	$subject = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$subject");
	$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
?>
<div id="pageTitle">Send Email to <?php print $_REQUEST['email_to_name']." (".$_REQUEST['email_to'].")";?></div>
<div>&nbsp;</div>

<form method="post" name="emailcustomer" action="w-send-email.php">
	<div id="roundedForm">
	<div class="row">
		<div style="width: 20%;" class="left textright">
			From: &nbsp;&nbsp; 
		</div>
		<div style="width: 80%;" class="left">
			<input type="text" name="from_email" value="<?php print $from_email;?>" size="40">
		</div>
		<div class="clear"></div>
	</div>

	<div class="row">
		<div style="width: 20%;" class="left textright">
			From name: &nbsp;&nbsp; 
		</div>
		<div style="width: 80%;" class="left">
			<input type="text" name="from_name" value="<?php print $from_name;?>" size="40">
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div style="width: 20%;" class="left textright">
			To: &nbsp;&nbsp; 
		</div>
		<div style="width: 80%;" class="left">
			<input type="text" name="to_email" value="<?php print $to_email;?>" size="40">
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div style="width: 20%;" class="left textright">
			Subject: &nbsp;&nbsp; 
		</div>
		<div style="width: 80%;" class="left">
			<input type="text" name="subject" value="<?php print $subject;?>" size="40">
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div style="width: 20%;" class="left textright">
			Message: &nbsp;&nbsp; 
		</div>
		<div style="width: 80%;" class="left">
			<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
			<?php 
			$email_style = true;	
			addEditor("message", "1", "500", "1"); ?>

		</div>
		<div class="clear"></div>
	</div>

	<div class="row center">
	<input type="hidden" name="submitit" value="send">
	<input type="submit" name="submit" class="submit" value="Send Message">
	</div>


	</div>
	</form>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
<?php } ?>

<?php require "w-footer.php"; ?>