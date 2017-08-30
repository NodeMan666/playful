<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(trim($value)));
	}
	updateSQL("ms_email_list_settings", "
	signup_text='".$_REQUEST['signup_text']."',
	signup_text_below='".$_REQUEST['signup_text_below']."',
	signup_success='".$_REQUEST['signup_success']."',
	first_name='".$_REQUEST['first_name']."',
	last_name='".$_REQUEST['last_name']."',
	signup_popup='".$_REQUEST['signup_popup']."',
	first_name_req='".$_REQUEST['first_name_req']."',
	last_name_req='".$_REQUEST['last_name_req']."',
	signup_button='".$_REQUEST['signup_button']."',
	popup_time='".$_REQUEST['popup_time']."',
	popup_background='".$_REQUEST['popup_background']."',
	popup_text='".$_REQUEST['popup_text']."',
	mailchimp_enable='".$_REQUEST['mailchimp_enable']."',
	mailchimp_key='".$_REQUEST['mailchimp_key']."',
	mailchimp_list_id='".$_REQUEST['mailchimp_list_id']."',
	mailchimp_auto='".$_REQUEST['mailchimp_auto']."',
	mailchimp_double_optin='".$_REQUEST['mailchimp_double_optin']."',
	mailchimp_double_optin_checkout='".$_REQUEST['mailchimp_double_optin_checkout']."',
	join_at_checkout_desc='".$_REQUEST['join_at_checkout_desc']."',
	join_at_checkout='".$_REQUEST['join_at_checkout']."',
	blank_fields='".$_REQUEST['blank_fields']."',
	invalid_email='".$_REQUEST['invalid_email']."',
	cancel_link='".$_REQUEST['cancel_link']."',
	join_at_checkout_text='".$_REQUEST['join_at_checkout_text']."',
	double_opt_in='".$_REQUEST['double_opt_in']."',
	send_welcome_email='".$_REQUEST['send_welcome_email']."',
	email_confirmed_title='".$_REQUEST['email_confirmed_title']."',
	email_confirmed_text='".$_REQUEST['email_confirmed_text']."',
	email_removed_title='".$_REQUEST['email_removed_title']."',
	email_removed_text='".$_REQUEST['email_removed_text']."',

	join_at_checkout_default='".$_REQUEST['join_at_checkout_default']."'
	");   		

	$_SESSION['sm'] = "Settings saved";
	session_write_close();
	header("location: index.php?do=people&view=mailListSettings");
	exit();
}
?>	

<?php  $em_settings = doSQL("ms_email_list_settings", "*", "  ");	
if(empty($em_settings['webhook_hash'])) { 
	$key = MD5($setup['url'].makesalt());
	updateSQL("ms_email_list_settings", "webhook_hash='".$key."' ");
	$em_settings = doSQL("ms_email_list_settings", "*", "  ");
}
?>


<?php
/*
$first_name = "Tim";
$last_name = "O'Grissett";
$email = "test5@picturespro.com";
mailchimpsubscribe($email,$first_name,$last_name);
*/
?>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">Mailing List Settings</span></div> 


<?php if($setup['unbranded'] !== true) { ?><div class="right textright"><a href="https://www.picturespro.com/sytist-manual/people/mailing-list/" target="_blank" class="the icons icon-info-circled"><i>Manual</i></a></div><?php } ?>
<div class="clear"></div>
<script>
function showcheckout() { 
	if($("#join_at_checkout").attr("checked")) { 
		$("#checkout").slideDown(200);
	} else { 
		$("#checkout").slideUp(200);
	}
}

function showjoinpopup() { 
	if($("#signup_popup").attr("checked")) { 
		$("#popupsettings").slideDown(200);
	} else { 
		$("#popupsettings").slideUp(200);
	}
}
function showmailchimp() { 
	if($("#mailchimp_enable").attr("checked")) { 
		$("#mailchimpsettings").slideDown(200);
		$("#mailchimp_list_id").addClass("required");
	} else { 
		$("#mailchimpsettings").slideUp(200);
		$("#mailchimp_list_id").removeClass("required");
	}
}

</script>

<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"    onSubmit="return checkForm();">

<div style="width: 49%; float: left;">

	<div class="underline">
		<div class="left p30"><h3>First Name</h3></div>
		<div class="left">
			<div class="pc"><input type="checkbox" name="first_name" id="first_name" value="1" <?php if($em_settings['first_name'] == "1") { print "checked"; } ?>> <label for="first_name">Ask</label></div>
			<div class="pc"><input type="checkbox" name="first_name_req" id="first_name_req" value="1" <?php if($em_settings['first_name_req'] == "1") { print "checked"; } ?>> <label for="first_name_req">Require</label></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="left p30"><h3>Last Name</h3></div>
		<div class="left">
			<div class="pc"><input type="checkbox" name="last_name" id="last_name" value="1" <?php if($em_settings['last_name'] == "1") { print "checked"; } ?>> <label for="last_name">Ask</label></div>
			<div class="pc"><input type="checkbox" name="last_name_req" id="last_name_req" value="1" <?php if($em_settings['last_name_req'] == "1") { print "checked"; } ?>> <label for="last_name_req">Require</label></div>
		</div>
		<div class="clear"></div>
	</div>
<div>&nbsp;</div>
		<?php $dem = doSQL("ms_emails", "*", "WHERE email_id_name='maillistconfirm' ");?>

	<div class="underlinelabel"><input type="checkbox" name="double_opt_in" id="double_opt_in" value="1"  <?php if($em_settings['double_opt_in'] == "1") { print "checked"; } ?>> <label for="double_opt_in">Double Opt-In</label></div>
	<div class="underlinespacer">Selecting this option means the person will be sent an email to click a link to confirm their subscription.	<a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $dem['email_id'];?>">You can edit the email that is sent here</a>. If you are using MailChimp this option will not be used and will use the option in the MailChimp settings.
	</div>

	<div class="underlinelabel"><input type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1"  <?php if($em_settings['send_welcome_email'] == "1") { print "checked"; } ?>> <label for="send_welcome_email">Send Welcome Email</label></div>
	<div class="underlinespacer">This is an email sent to person after they have subscribed. 
			<?php $dem = doSQL("ms_emails", "*", "WHERE email_id_name='maillistwelcome' ");?>
		<a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $dem['email_id'];?>">You can edit the email that is sent here</a>. 
	If you select this option and also using the MailChimp option, check that you are not sending a welcome email through MailChimp also.</div>


	<div>&nbsp;</div>

	<div class="underlinelabel"><input type="checkbox" name="join_at_checkout" id="join_at_checkout" value="1" onchange="showcheckout();" <?php if($em_settings['join_at_checkout'] == "1") { print "checked"; } ?>> <label for="join_at_checkout">Enable Join At Checkout & Create Account</label></div>
	<div class="underlinespacer">This will add a checkbox at checkout and create an account to join your mailing list.</div>
	<div id="checkout" class="<?php if($em_settings['join_at_checkout'] !== "1") { print "hide"; } ?>">
		<div class="underline">
			<div class="label">Join Text (clickable text)</div>
			<div><input type="text" name="join_at_checkout_text" id="join_at_checkout_text" value="<?php print $em_settings['join_at_checkout_text'];?>" size="30" class="required field100"></div>
		</div>
		<div class="underline">
			<div class="label">Join Additional Description</div>
			<div>	<textarea name="join_at_checkout_desc" id="join_at_checkout_desc" rows="4" cols="30" class="field100"><?php print $em_settings['join_at_checkout_desc'];?></textarea></div>
		</div>

		<div class="underline">
			<div class="label">Default Status</div>
			<div><input type="radio" name="join_at_checkout_default" id="join_at_checkout_default0" value="0" <?php if($em_settings['join_at_checkout_default'] == "0") { print "checked"; } ?>> <label for="join_at_checkout_default0">Unchecked</label> &nbsp;&nbsp;&nbsp;
				<input type="radio" name="join_at_checkout_default" id="join_at_checkout_default1" value="1" <?php if($em_settings['join_at_checkout_default'] == "1") { print "checked"; } ?>> <label for="join_at_checkout_default1">Checked</label> 
			</div>
		</div>
	</div>


	<div>&nbsp;</div>
<div>&nbsp;</div>

	<div class="underlinelabel"><input type="checkbox" name="signup_popup" id="signup_popup" value="1" onchange="showjoinpopup();" <?php if($em_settings['signup_popup'] == "1") { print "checked"; } ?>> <label for="signup_popup">Enable Signup Popup</label></div>
	<div class="underlinespacer">This is a window that will popup on the screen the first time they visit with the join mailing list form. <a href="<?php print $setup['url'].$setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?previewmlpopup=1" target="_blank">Preview on website</a></div>
	
	<div id="popupsettings" class="<?php if($em_settings['signup_popup'] !== "1") { print "hide"; } ?>">
		<div class="underlinelabel">Timing</div>
		<div class="underline">
			Display popup window after <select name="popup_time" id="popup_time">
			<option value="1" <?php if($em_settings['popup_time'] == "1") { print "selected"; } ?>>1</option>
			<option value="2" <?php if($em_settings['popup_time'] == "2") { print "selected"; } ?>>2</option>
			<option value="3" <?php if($em_settings['popup_time'] == "3") { print "selected"; } ?>>3</option>
			<option value="4" <?php if($em_settings['popup_time'] == "4") { print "selected"; } ?>>4</option>
			<option value="5" <?php if($em_settings['popup_time'] == "5") { print "selected"; } ?>>5</option>
			<option value="6" <?php if($em_settings['popup_time'] == "6") { print "selected"; } ?>>6</option>
			<option value="7" <?php if($em_settings['popup_time'] == "7") { print "selected"; } ?>>7</option>
			<option value="8" <?php if($em_settings['popup_time'] == "8") { print "selected"; } ?>>8</option>
			<option value="9" <?php if($em_settings['popup_time'] == "9") { print "selected"; } ?>>9</option>
			<option value="10" <?php if($em_settings['popup_time'] == "10") { print "selected"; } ?>>10</option>
			</select> seconds of being on the page. 
		</div>

		<div class="underlinelabel">Color</div>
		<div class="underline">
			<div class="left p30">Background</div>
			<div class="left"><input type="text" name="popup_background" id="popup_background" value="<?php print $em_settings['popup_background'];?>" size="12" class="color"></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p30">Text</div>
			<div class="left"><input type="text" name="popup_text" id="popup_text" value="<?php print $em_settings['popup_text'];?>" size="12" class="color"></div>
			<div class="clear"></div>
		</div>

	</div>
<div>&nbsp;</div>
<div>&nbsp;</div>


	<div class="underlinelabel">Join Text Above Form</div>
	<div class="underline">
	<textarea name="signup_text" id="signup_text" rows="4" cols="30" class="field100"><?php print $em_settings['signup_text'];?></textarea>
	</div>

	<div class="underlinelabel">Join Text Below Form</div>
	<div class="underline">
	<textarea name="signup_text_below" id="signup_text_below" rows="4" cols="30" class="field100"><?php print $em_settings['signup_text_below'];?></textarea>
	</div>

	<div class="underlinelabel">Join Success Message</div>
	<div class="underline">
	<textarea name="signup_success" id="signup_success" rows="4" cols="30" class="field100"><?php print $em_settings['signup_success'];?></textarea>
	</div>

	<div class="underlinelabel">Join Button Text</div>
	<div class="underline">
	<input type="text" name="signup_button" id="signup_button" value="<?php print $em_settings['signup_button'];?>" size="30" class="required">
	</div>

<div>&nbsp;</div>

	<div class="underlinelabel">Other Text</div>
	<div class="underline">
		<div class="label">You have blank fields</div>
		<div><input type="text" name="blank_fields" id="blank_fields" value="<?php print $em_settings['blank_fields'];?>" size="30" class="required field100"></div>
	</div>

	<div class="underline">
		<div class="label">Invalid email format</div>
		<div><input type="text" name="invalid_email" id="invalid_email" value="<?php print $em_settings['invalid_email'];?>" size="30" class="required field100"></div>
	</div>
	<div class="underline">
		<div class="label">Cancel link</div>
		<div><input type="text" name="cancel_link" id="cancel_link" value="<?php print $em_settings['cancel_link'];?>" size="30" class="required field100"></div>
	</div>

	<div class="underline">
		<div class="label">Email address confirmred title</div>
		<div><input type="text" name="email_confirmed_title" id="email_confirmed_title" value="<?php print $em_settings['email_confirmed_title'];?>" size="30" class="required field100"></div>
	</div>

		<div class="underline">
			<div class="label">Email Address confirmed text</div>
			<div>	<textarea name="email_confirmed_text" id="email_confirmed_text" rows="4" cols="30" class="field100"><?php print $em_settings['email_confirmed_text'];?></textarea></div>
		</div>

	<div class="underline">
		<div class="label">Email address removed title</div>
		<div><input type="text" name="email_removed_title" id="email_removed_title" value="<?php print $em_settings['email_removed_title'];?>" size="30" class="required field100"></div>
	</div>

		<div class="underline">
			<div class="label">Email Address removed text</div>
			<div>	<textarea name="email_removed_text" id="email_removed_text" rows="4" cols="30" class="field100"><?php print $em_settings['email_removed_text'];?></textarea></div>
		</div>

	<div>&nbsp;</div>

	<div  class="bottomSave">
		<input type="hidden" name="do" value="people">
		<input type="hidden" name="view" value="mailListSettings">
		<input type="hidden" name="submitit" value="yup">
		<input type="submit" name="submit" class="submit" value="Update Settings">
	</div>
</div>

<div style="width: 49%; float: right;">
	<div class="underlinelabel">
	<?php if(phpversion() >= 5.3) { ?><input type="checkbox" name="mailchimp_enable" id="mailchimp_enable" value="1" onchange="showmailchimp();" <?php if($em_settings['mailchimp_enable'] == "1") { print "checked"; } ?>><?php } ?> <label for="mailchimp_enable"> <img src="graphics/mailchimp.png" style="width: 20px; height: auto; margin-left: 8px;" align="absmiddle"> Connect with MailChimp.com</label></div>
	<div class="underlinespacer"><a href="http://eepurl.com/bMKmoT" target="_blank">MailChimp.com</a> is an email marketing service to send bulk emails. If you have fewer than 2,000 subscribers, you can send up to 12,000 emails per month absolutely free. <a href="http://eepurl.com/bMKmoT" target="_blank">Sign up for MailChimp if you don't already have an account</a>.
	<br><br>When using this option it will automatically send the subscriber to your MailChimp account. <a href="https://www.picturespro.com/sytist-manual/articles/mailchimp/" target="_blank">Learn how to set this up</a>.
	</div>
<?php if($setup['demo_mode'] !== true) { ?>
<?php if(phpversion() < 5.3) { ?>
<div class="center" style="color: #890000; font-weight: bold;">PHP VERSION NOT COMPATIBLE (<?php print phpversion();?>)</div>
<div class="pc">In order to use the MailChimp features, the PHP version on your hosting needs to be version 5.3 or higher. You may be able to change the PHP version in your hosting control panel. If you are unable to do so, contact your hosting company and ask them to change the PHP version to version 5.3 or higher.</div>
<?php }  else { 
	include "people.mail.chimp.settings.php";
}
?>
<?php } ?>
	<div>&nbsp;</div>






		<div>&nbsp;</div>

		<div class="underlinelabel">Adding The Mailing List Subscription Form</div>
		<div class="underlinespacer">
		<p>To the left you have the options to enable joining the mailing list during checkout & account creation and also the option to add a popup to join on the page. You can also add the form to a regular page or the footer.</p>
		<p>To add the join form on a page or in the footer, add the following bracket code <b>EXACTLY</b> as it appears below to the page text or footer text:</p>
		<p class="bold">[JOIN_MAILING_LIST]</p>
		<p>You can also add that to the sidebar in a Text Area section.</p>
		<p>To add a link to your menu or as a link on a page that will show the popup join, use the following link as the URL:</p>
		<p class="bold">javascript:showpopupemailjoin();</p>

		</div>
</div>
</form>
<div>&nbsp;</div>
<div class="cssClear"></div>
