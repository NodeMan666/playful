<?php $em = doSQL("ms_emails","*","WHERE email_id='".$_REQUEST['email_id']."' ");
			$email_style = true;
			$full_file_url = 1;

?>
<?php if($_REQUEST['save'] == "yes") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}
	if(empty($_REQUEST['email_id'])) { 
		$email_id = insertSQL("ms_emails", "
		email_name='".$_REQUEST['email_name']."', 
		email_subject='".$_REQUEST['email_subject']."',
		email_descr='".$_REQUEST['email_descr']."',
		email_from_email='".$_REQUEST['email_from_email']."',
		email_from_name='".$_REQUEST['email_from_name']."',
		email_message='".$_REQUEST['email_message']."',
		email_shipping_descr='".$_REQUEST['email_shipping_descr']."',
		email_download_descr='".$_REQUEST['email_download_descr']."',
		email_codes='".$_REQUEST['email_codes']."'");

	} else { 
		updateSQL("ms_emails", "
		email_name='".$_REQUEST['email_name']."', 
		email_subject='".$_REQUEST['email_subject']."',
		email_descr='".$_REQUEST['email_descr']."',
		email_from_email='".$_REQUEST['email_from_email']."',
		email_from_name='".$_REQUEST['email_from_name']."',
		email_message='".$_REQUEST['email_message']."',
		email_shipping_descr='".$_REQUEST['email_shipping_descr']."',
		email_download_descr='".$_REQUEST['email_download_descr']."',
		email_codes='".$_REQUEST['email_codes']."',
		email_offline_pending='".$_REQUEST['email_offline_pending']."',
		email_paypal_pending='".$_REQUEST['email_paypal_pending']."'
		WHERE email_id='".$_REQUEST['email_id']."' ");
		$email_id = $_REQUEST['email_id'];
	}	
	$_SESSION['sm'] = "Email Saved";
	header("location: index.php?do=settings&action=defaultemailsedit&email_id=".$email_id."");
	session_write_close();
	exit();
}
$hide_descr = true;

?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> > <a href="index.php?do=settings&action=defaultemails">Default Emails</a> > <?php if(empty($_REQUEST['email_id'])) { print "Create New Default Email"; } else { print $em['email_name']; } ?></div>
<div>&nbsp;</div>
<?php define("this_do", "store"); ?>
<div class="buttonsgray">
	<ul>

	<li><a href="index.php?do=settings&action=defaultemails">LIST EMAILS</a></li>
<div class="clear"></div>
</ul>
</div>
<div id="roundedFormContain">
<form method="post" name="defaultemail" action="index.php" onSubmit="return checkForm();">
<div id="roundedForm">
<?php if($hide_descr == true) { ?><div class="row"><?php print $em['email_descr'];?></div><?php } ?>

	<div class="row">
		<div class="fieldLabel">Email Name</div>
		<div><input type="text" name="email_name" id="email_name" size="60" class=" required" value="<?php print  htmlspecialchars(stripslashes($em['email_name']));?>"></div>
	</div>
	<div <?php if($hide_descr == true) { ?>style="display: none;" <?php } ?>>

	<div class="row">
		<div class="fieldLabel">Description</div>
		<div><input type="text" name="email_descr" id="email_descr" size="60" class="" value="<?php print  htmlspecialchars(stripslashes($em['email_descr']));?>"></div>
	</div>
	</div>
	<div class="row">
		<div style="float: left; width: 49%;">
			<div class="fieldLabel">From Email (your email address)</div>
			<div><input type="text" name="email_from_email" id="email_from_email" size="60" class="" value="<?php print  htmlspecialchars(stripslashes($em['email_from_email']));?>"></div>
			<div>Leave blank to use your default email address: <B><?php print $site_setup['contact_email'];?></B></div>
		</div>
		<div style="float: right; width: 49%;">
			<div class="fieldLabel">From Name</div>
			<div><input type="text" name="email_from_name" id="email_from_name" size="60" class="" value="<?php print  htmlspecialchars(stripslashes($em['email_from_name']));?>"></div>
			<div>Leave blank to use your default email address: <B><?php print $site_setup['website_title'];?></B></div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="row">
		<div class="fieldLabel">Subject</div>
		<div><input type="text" name="email_subject" id="email_subject" size="60" class="required" value="<?php print  htmlspecialchars(stripslashes($em['email_subject']));?>"></div>
	</div>

	<div class="row">
		<div class="fieldLabel">Message</div>
		<div style="float: left; width: 70%;">
			<div class="pc">
			<textarea name="email_message" id="email_message" rows="30" cols="60" class="field100"><?php print $em['email_message'];?></textarea>
			<?php 
			addEditor("email_message", "1", "500", "1"); ?>
			</div>
		</div>
		<div style="width: 30%; float: left;">
		<div class="pc"><h3>Replace codes</h3>Below are the codes to add to the emails  that will be replaced with real informaiton when the email is sent. </div>
			<div class="pc">
			<?php if(empty($em['email_codes'])) { ?>
			[FIRST_NAME] = Customer's first name<br>
			[LAST_NAME] = Customer's last name<br>
			[URL] = The URL to your store<br>
			[WEBSITE_NAME] = The name of your website

			<?php } ?>
			<?php print nl2br($em['email_codes']);?>
			</div>
		</div>
		<div class="clear"></div>
		</div>
	<?php if($em['email_id'] == "6") { ?>
	<div class="row">
		<div class="fieldLabel">Download description</div>
		<div>
		<textarea name="email_download_descr" id="email_download_descr" rows="3" cols="40" class="field100"><?php print $em['email_download_descr'];?></textarea>
		</div>
		<div>This will replace the [DOWNLOAD_DESCRIPTION] code in the email.</div>
	</div>
	<div class="row">
		<div class="fieldLabel">Shipping description</div>
		<div>
		<textarea name="email_shipping_descr" id="email_shipping_descr" rows="3" cols="40" class="field100"><?php print $em['email_shipping_descr'];?></textarea>
		</div>
		<div>This will replace the [SHIPPING_DESCRIPTION] code in the email.</div>
	</div>
	<?php } ?>


<!-- 	<?php if($em['email_id'] == "7") { ?>
	<div class="row">
		<div class="fieldLabel">Offline payment pending description</div>
		<div>
		<textarea name="email_offline_pending" id="email_offline_pending" rows="5" cols="40" class="field100"><?php print $em['email_offline_pending'];?></textarea>
		</div>
		<div>This will replace the [ORDER_OFFLINE_PENDING]  code in the email.</div>
	</div>
	<div class="row">
		<div class="fieldLabel">PayPal echeck pending description</div>
		<div>
		<textarea name="email_paypal_pending" id="email_paypal_pending" rows="5" cols="40" class="field100"><?php print $em['email_paypal_pending'];?></textarea>
		</div>
		<div>This will replace the [ORDER_PAYPAL_PENDING] code in the email.</div>
	</div>
	<?php } ?>
-->
	<div <?php if($hide_descr == true) { ?>style="display: none;" <?php } ?>>

	<div class="row">
		<div class="fieldLabel">Replace Codes</div>
		<div>
		<textarea name="email_codes" id="email_codes" rows="30" cols="40" class="field100"><?php print $em['email_codes'];?></textarea>
		</div>
	</div>
	</div>
	</div>

</div>

<div  class="bottomSave">
<input type="hidden" name="do" value="settings">
<input type="hidden" name="action" value="defaultemailsedit">
<input type="hidden" name="email_id" value="<?php print $em['email_id'];?>">
<input type="hidden" name="save" value="yes">
<input type="submit" name="submit" class="submit" value="Save Changes" id="submit">
</div>
</form>

