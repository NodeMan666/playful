<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

if(isset($_REQUEST['testemail'])) { 
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}

	insertSQL("ms_cron_emails", "to_email='".$_REQUEST['to_email']."', to_name='".$_REQUEST['to_email']."', from_email='".$site_setup['contact_email']."', from_name='".$site_setup['websiite_title']."', subject='*TEST EMAIL* - From Cron on ".addslashes(stripslashes($site_setup['website_title']))."' , test_email='1', content='If you have received this email, then the cron job is working properly for ".$setup['url']."' ");

	$_SESSION['sm'] = "Test Email Qued";
	session_write_close();
	header("location: index.php?do=settings&action=cron");
	exit();
}
if($_REQUEST['subdo'] == "deleteque") { 
	deleteSQL2("ms_cron_emails", "WHERE id>'0' ");
	$_SESSION['sm'] = "All pending emails deleted.";
	session_write_close();
	header("location: index.php?do=settings&action=cron");
	exit();
}

if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}

		updateSQL("ms_settings", "cron_site_url='".$_REQUEST['cron_site_url']."',
		cron_enabled='".$_REQUEST['cron_enabled']."',
		cron_emails_per='".$_REQUEST['cron_emails_per']."',
		cron_sleep='".$_REQUEST['cron_sleep']."',
		cron_test_mode='".$_REQUEST['cron_test_mode']."',
		cron_unsubscribe='".$_REQUEST['cron_unsubscribe']."',
		unsubscribe_text='".$_REQUEST['unsubscribe_text']."'
		
		");

		$_SESSION['sm'] = "Settings saved";
		session_write_close();
		header("location: index.php?do=settings&action=cron");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	$cset = doSQL("ms_calendar_settings", "*", "  ");
	$lang = doSQL("ms_language", "*", "  WHERE lang_default='1' ");
	?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Automated Emails (cron)</div>



<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">




<div style="width: 49%; float: left;">
	<div class="info pc">Here you can set up automated emails to send out gallery expiring emails & booking reminders automatically. Also with this enabled, when you send out gallery information emails, it will be sent out using this feature.
	<br><br>
	This is done by setting up a cron job in your hosting control panel. <a href="https://www.picturespro.com/sytist-manual/settings/automated-emails/" target="_blank">Click here for detailed information on creating a cron job</a>.
	</div>
<div>&nbsp;</div>
	<div class="underlinelabel"><input type="checkbox" name="cron_enabled" id="cron_enabled" value="1" <?php if($site_setup['cron_enabled'] == "1") { print "checked"; } ?>> <label for="cron_enabled"><b>Enable Automated Emails</b></label></div>
	<div class="underlinespacer">Check this option if you have set up the cron job in your hosting control panel.</div>

	<div class="underlinelabel"><input type="checkbox" name="cron_test_mode" id="cron_test_mode" value="1" <?php if($site_setup['cron_test_mode'] == "1") { print "checked"; } ?>> <label for="cron_test_mode">Test Mode</label></div>
	<div class="underlinespacer">Check Test Mode to only sent out test emails added with the test cron job option.</div>

	<div>&nbsp;</div>
	<?php if($setup['demo_mode'] !== true) { ?>
	<div class="underlinelabel">The path for the cron file</div>
	<div class="underlinespacer">When setting up the cron job, you need to set it up to run every minute. You will also need to enter in the php binary path and the server path to the file which at best guess is this:</div>
	
	<div class="underlinespacer"><?php print $setup['path'];?>/<?php print $setup['manage_folder'];?>/sy-cron.php</div>
	<div class="underlinespacer">So it will look something like the following. You can copy this line and paste it into the command section when setting up the cron job.</div>

	<div class="underlinespacer">
		<b>/usr/bin/php -q <?php print $setup['path'];?>/<?php print $setup['manage_folder'];?>/sy-cron.php</b>
	</div>
	<?php } ?>




	<div class="underlinelabel">
	
		<select name="cron_emails_per">
	<option value="1" <?php if($site_setup['cron_emails_per'] == "1") { print "selected"; } ?>>1</option>
	<option value="2" <?php if($site_setup['cron_emails_per'] == "2") { print "selected"; } ?>>2</option>
	<option value="5" <?php if($site_setup['cron_emails_per'] == "5") { print "selected"; } ?>>5</option>
	<option value="10" <?php if($site_setup['cron_emails_per'] == "10") { print "selected"; } ?>>10</option>
	<option value="15" <?php if($site_setup['cron_emails_per'] == "15") { print "selected"; } ?>>15</option>
	<option value="20" <?php if($site_setup['cron_emails_per'] == "20") { print "selected"; } ?>>20</option>
	<option value="25" <?php if($site_setup['cron_emails_per'] == "25") { print "selected"; } ?>>25</option>
	<option value="30" <?php if($site_setup['cron_emails_per'] == "30") { print "selected"; } ?>>30</option>
	</select> 

		Emails to send out at a time</div>
	<div class="underlinespacer">Every minute the file is called to send out emails. This sets how many emails are sent out each minute.</div>

<div class="cssClear"></div>
	<div>&nbsp;</div>

	<div class="underlinelabel">
	<select name="cron_sleep">
	<option value="0" <?php if($site_setup['cron_sleep'] == "0") { print "selected"; } ?>>0</option>
	<option value="1" <?php if($site_setup['cron_sleep'] == "1") { print "selected"; } ?>>1</option>
	<option value="2" <?php if($site_setup['cron_sleep'] == "2") { print "selected"; } ?>>2</option>
	<option value="3" <?php if($site_setup['cron_sleep'] == "3") { print "selected"; } ?>>3</option>
	<option value="4" <?php if($site_setup['cron_sleep'] == "4") { print "selected"; } ?>>4</option>
	<option value="5" <?php if($site_setup['cron_sleep'] == "5") { print "selected"; } ?>>5</option>
	</select> 
	Sleep time between emails in seconds </div>
	<div class="underlinespacer"></div>


<div class="cssClear"></div>
	<div>&nbsp;</div>




<div>&nbsp;</div>


</div>


<div style="width: 49%; float: right;">


	<div>&nbsp;</div>
<?php if(($site_setup['cron_enabled'] == "1") && ($site_setup['cron_test_mode'] == "1") == true) { ?>
<div class="error">With test mode on, these emails won't send.</div>
<?php } ?>

<div class="underline">
	<div class="left p10">&nbsp;</div>
	<div class="left p30">Email</div>
	<div class="left p20">Days Ahead</div>
	<div class="left p20">Time</div>
	<div class="left p20">Status</div>
	<div class="clear"></div>
</div>

<?php $crons = whileSQL("ms_crons LEFT JOIN ms_emails ON ms_crons.cron_email=ms_emails.email_id","*, time_format(cron_time, '%l:%i %p')  AS cron_time", "ORDER BY cron_id ASC ");
while($cron = mysqli_fetch_array($crons)) { ?>
<div class="underline">
	<div class="left p10"><a href="" onclick="cronedit('<?php print $cron['cron_id'];?>'); return false;"><?php print ai_edit;?></a></div>
	<div class="left p30"><?php if($cron['cron_what'] == "galleries") { ?><a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $cron['email_id'];?>"><?php print $cron['email_name'];?></a>
	<?php } ?>
	<?php if($cron['cron_what'] == "earlybird") { ?>Early Bird Special Reminder<?php } ?>
	<?php if($cron['cron_what'] == "booking") { ?>Appointment Reminder<?php } ?>
	<?php if($cron['cron_what'] == "payments") { ?>Scheduled Payment Reminder<?php } ?>
	<?php if($cron['cron_what'] == "giftcards") { ?>eGift Cards<?php } ?>

	</div>
	<div class="left p20"><?php print $cron['cron_days'];?></div>
	<div class="left p20"><?php print $cron['cron_time'];?></div>
		<div class="left p20">
	<?php if($site_setup['cron_enabled'] == "1") { ?><?php if($cron['cron_status'] == "1") { ?><span class="good">Enabled</span><?php } else { ?><span class="error">Disabled</span><?php } ?><?php } ?>
	</div>

	<div class="clear"></div>
</div>

<?php } ?>

	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<div class="underlinelabel">Your Sytist URL</div>

	<div class="underline">
	<div>
	<input type="text" name="cron_site_url" id="cron_site_url" size="50" value="<?php if(empty($site_setup['cron_site_url'])) { print $setup['url'].$setup['temp_url_folder']; } else { print $site_setup['cron_site_url']; } ?>">
	</div>
	<div>Looks like this should be: <?php print  $setup['url'].$setup['temp_url_folder']; ?>. This must start with http:// or https://</div>.
	</div>


<div class="cssClear"></div>
	<div>&nbsp;</div>


	<div class="underlinelabel">Unsubscribe Text</div>
	<div class="underlinespacer">With the automated gallery expiring emails, it will add an unsubscribe option at the bottom of the email where they can opt to not receive any more emails.</div>

	<div class="underline">
	<div>
	<textarea name="cron_unsubscribe" id="cron_unsubscribe" class="field100 required" rows="4"><?php print $site_setup['cron_unsubscribe'];?></textarea>
	</div>
	<div>Use the bracket code [UNSUBSCRIBE_LINK] to open the link and [/UNSUBSCRIBE_LINK] to close it.</div>
	</div>
	<div>&nbsp;</div>

	<div class="underlinelabel">Unsubscribed Message</div>
	<div class="pc">If someone clicks to unsubscribe, this is the message that is shown.</div>

	<div class="underline">
	<div>
	<textarea name="unsubscribe_text" id="unsubscribe_text" class="field100 required" rows="4"><?php print $site_setup['unsubscribe_text'];?></textarea>
	</div>
	</div>



<div>&nbsp;</div>

<div>&nbsp;</div>
<?php if(countIt("ms_cron_emails", "") > 0) { ?>
<div class="underlinelabel"><?php print countIt("ms_cron_emails", ""); ?> Email(s) waiting (<a href="index.php?do=settings&action=cron&subdo=deleteque" onClick="return confirm('Are you sure you want to delete all pending emails? ');">delete all</a>) </div>
<?php if($site_setup['cron_test_mode'] == "1") { ?>
<div class="underlinelabel">Emails not marked as test emails won't send until test mode is disabled.</div>
<?php } ?>
<?php } ?>

</div>
<div class="cssClear"></div>

	<div  class="bottomSave">
		<input type="hidden" name="do" value="settings">
		<input type="hidden" name="action" value="cron">
		<input type="hidden" name="submitit" value="yup">
		<input type="hidden" name="date_type" value="news">
		<input type="submit" name="submit" id="submit" class="submit" value="Update Settings">
	</div>
			</form>



<div class="underlinelabel">Test Cron Job</div>
<div class="underlinespacer">Here you can add a test email. If the cron job is working properly, you should receive the email within a couple of minutes.</div>
<form name="crontest" action="index.php" method="post" style="padding:0; margin: 0;">
<div class="underline">
<input type="text" name="to_email" id="to_email" value="<?php print $site_setup['contact_email'];?>" size="30">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="cron">
	<input type="hidden" name="testemail" value="yup">
	<input type="submit" name="submit" id="submit" class="submitSmall" value="Add Test Email">
</div>
</form>


<?php  } ?>