<?php 
$path = "../../";
require "../w-header.php"; ?>
<?php
if($_POST['submitit']=="yes") { 
	if($_REQUEST['form_id'] > 0) { 

		updateSQL("ms_forms", "
		form_name='".addslashes(stripslashes($_REQUEST['form_name']))."' , 
		form_email_to='".addslashes(stripslashes($_REQUEST['form_email_to']))."' ,
		form_subject='".addslashes(stripslashes($_REQUEST['form_subject']))."' ,
		form_button='".addslashes(stripslashes($_REQUEST['form_button']))."' ,
		form_end_message='".addslashes(stripslashes($_REQUEST['form_end_message']))."',
		form_cols='".$_REQUEST['form_cols']."',
		form_max_width='".$_REQUEST['form_max_width']."',
		form_success_url='".$_REQUEST['form_success_url']."',
		form_captcha='".$_REQUEST['form_captcha']."',

		form_empty_fields='".addslashes(stripslashes($_REQUEST['form_empty_fields']))."',
		form_invalid_email='".addslashes(stripslashes($_REQUEST['form_invalid_email']))."',
		form_math_question='".addslashes(stripslashes($_REQUEST['form_math_question']))."',
		form_math_incorrect='".addslashes(stripslashes($_REQUEST['form_math_incorrect']))."'

		WHERE form_id='".$_REQUEST['form_id']."' ");
		$form_id = $_REQUEST['form_id'];
		$_SESSION['sm'] = "Form Saved";

	} else {
		$form_id = insertSQL("ms_forms", "
		form_name='".addslashes(stripslashes($_REQUEST['form_name']))."' , 
		form_email_to='".addslashes(stripslashes($_REQUEST['form_email_to']))."' ,
		form_subject='".addslashes(stripslashes($_REQUEST['form_subject']))."' ,
		form_button='".addslashes(stripslashes($_REQUEST['form_button']))."' ,
		form_end_message='".addslashes(stripslashes($_REQUEST['form_end_message']))."' ,
		form_cols='".$_REQUEST['form_cols']."',
		form_captcha='".$_REQUEST['form_captcha']."',
		form_max_width='".$_REQUEST['form_max_width']."',
		form_empty_fields='".addslashes(stripslashes($_REQUEST['form_empty_fields']))."',
		form_invalid_email='".addslashes(stripslashes($_REQUEST['form_invalid_email']))."',
		form_math_question='".addslashes(stripslashes($_REQUEST['form_math_question']))."',
		form_math_incorrect='".addslashes(stripslashes($_REQUEST['form_math_incorrect']))."',

		form_success_url='".$_REQUEST['form_success_url']."'
		");
		$_SESSION['sm'] = "Form  Created";

	}
	header("location: ../index.php?do=forms&action=viewForm&form_id=".$form_id."&view=credits");
	session_write_close();
	exit();
}
?>


<?php 
	if($_REQUEST['form_id'] > 0) { 
		$form = doSQL("ms_forms", "*", "WHERE form_id='".$_REQUEST['form_id']."' ");
	} else { 
		$form['form_email_to'] = $site_setup['contact_email'];
		$form['form_max_width'] = "800";
		$form['form_button'] = "Send";
		$form['form_end_message'] = "Thank you for your message. We will try to get back to you as soon as possible.";
	}

	?>
	<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#form_name").focus();
 }
</script>
	<div class="pc"><?php if(empty($form['form_id'])) { ?><h1>Create New Form</h1><?php } else { ?><h1>Edit Form</h1><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div>
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Form Name</div>
			<div><input type="text" name="form_name" id="form_name" class="optrequired field100" value="<?php print $form['form_name'];?>" size="20" tabindex="1"></div>
			<div class="sub">This is for your reference.</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Email Results To:</div>
			<div><input type="text" name="form_email_to" id="form_email_to"  value="<?php print $form['form_email_to'];?>" class="optrequired field100" tabindex="2"></div>
			<div class="sub">To send to more than one email address, separate email addresses with a comma (,)</div>
		</div>
	</div>
	<div class="clear"></div>
	</div>
	
		<div>
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Email Subject</div>
			<div><input type="text" name="form_subject" id="form_subject" class="optrequired field100" value="<?php print $form['form_subject'];?>" size="8" tabindex="1"></div>
			<div class="sub">This is the subject of the email sent to you when this form is sent.</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Submit Button Text</div>
			<div><input type="text" name="form_button" id="form_button"  value="<?php print $form['form_button'];?>" size="15" class="optrequired" tabindex="2"></div>
			<div class="sub">The text on the submit button. Example: Submit, Send, etc...</div>
		</div>
	</div>
	<div class="clear"></div>
	</div>
	<div>
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">
			<select name="form_cols" id="form_cols">
			<option value="1" <?php if($form['form_cols'] == "1") { print "selected"; } ?>>1</option>
			<option value="2" <?php if($form['form_cols'] == "2") { print "selected"; } ?>>2</option>
			</select> Columns. You can have the form show in 1 or 2 columns. 
			</div>
		</div>
	</div>


	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Maximum Width</div>
			<div><input type="text" name="form_max_width" id="form_max_width"  value="<?php print $form['form_max_width'];?>" size="4" class="optrequired" tabindex="2"> px</div>
			<div class="sub">Max width of the form container. 800 reccommended</div>
		</div>
	</div>
	<div class="clear"></div>
	</div>



	<div class="underline">
		<div class="label">Success Message</div>
		<div><textarea name="form_end_message" id="form_end_message" rows="3" class="field100 optrequired"><?php print $form['form_end_message'];?></textarea></div>
		<div class="sub">This is the message shown on the screen after someone completes and send the form.</div>
	</div>



	<div class="underline">
		<div class="label">Redirect URL</div>
		<div><input type="text" name="form_success_url" id="form_success_url"  value="<?php print $form['form_success_url'];?>" size="20" class="field100"></div>
		<div class="sub">Instead of a success message, you can redirect the visitor to another page after they submit the form. Enter in the URL to redirect to (example: http://www.mysite.com/page/) otherwise leave blank to display the success message. </div>
	</div>


	<div class="underline">
		<div class="label">Empty Fields Message</div>
		<div><input type="text" name="form_empty_fields" id="form_empty_fields"  value="<?php print htmlspecialchars($form['form_empty_fields']);?>" size="20" class="field100 optrequired"></div>
	</div>

	<div class="underline">
		<div class="label">Invalid Email Message</div>
		<div><input type="text" name="form_invalid_email" id="form_invalid_email"  value="<?php print htmlspecialchars($form['form_invalid_email']);?>" size="20" class="field100 optrequired"></div>
	</div>
	<div class="underline">
		<input type="checkbox" name="form_captcha" id="form_captcha" value="1" <?php if($form['form_captcha'] == "1") { print "checked"; } ?>> <label for="form_captcha">Enable simple math question to answer to help prevent spam bots</label>
	</div>
	
	<div class="underline">
		<div class="label">Math Question </div>
		<div><input type="text" name="form_math_question" id="form_math_question"  value="<?php print htmlspecialchars($form['form_math_question']);?>" size="20" class="field100 optrequired"></div>
	</div>
	<div class="underline">
		<div class="label">Math Incorrect Message</div>
		<div><input type="text" name="form_math_incorrect" id="form_math_incorrect"  value="<?php print htmlspecialchars($form['form_math_incorrect']);?>" size="20" class="field100 optrequired"></div>
	</div>




	<div class="pageContent center">

	<input type="hidden" name="form_id" value="<?php print $_REQUEST['form_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>