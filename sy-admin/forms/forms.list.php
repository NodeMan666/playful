<?php
	$formDisplay = "none";
	$formLinkDisplay = "block";
	if($_REQUEST['action'] == "editForm") {
		$formDisplay = "block";
		$formLinkDisplay = "none";
	}

?>
<?php
if($_REQUEST['action'] == "addForm") {
	if(empty($_REQUEST['form_name'])) {
		$error .= "<div>You did not enter a name for this form.</div>";
	}
	if(empty($_REQUEST['form_subject'])) {
		$error .= "<div>You did not enter a subject for this form</div>";
	}
	if(empty($_REQUEST['form_email_to'])) {
		$error .= "<div>You did not enter an email address to email the form to.</div>";
	}
	if(empty($_REQUEST['form_button'])) {
		$error .= "<div>You did not enter text for the submit button.</div>";
	}

	if(empty($_REQUEST['form_end_message'])) {
		$error .= "<div>You did not enter a success message</div>";
	}

	if(!empty($error)) {
		print "<div class=error>$error</div>";
		$formDisplay = "block";
		$formLinkDisplay = "none";
	} else {

		$id = insertSQL("ms_forms", "form_name='".addslashes(stripslashes($_REQUEST['form_name']))."' ,form_email_to='".addslashes(stripslashes($_REQUEST['form_email_to']))."' ,form_subject='".addslashes(stripslashes($_REQUEST['form_subject']))."', form_captcha='".addslashes(stripslashes($_REQUEST['form_captcha']))."', form_end_message='".addslashes(stripslashes($_REQUEST['form_end_message']))."', form_button='".addslashes(stripslashes($_REQUEST['form_button']))."'  ");
		$_SESSION['sm'] = "New form ".$_REQUEST['form_name']." created";
		session_write_close();
		header("location: index.php?do=forms&action=viewForm&form_id=$id");
		exit();
	}

}
?>
<div id="pageTitle" class="left"><a href="index.php?do=forms">Forms</a></div>
<div class="right buttons textright"><br><a href="" onclick="editform(); return false;">Create New Form</a></div>

<div class="clear">&nbsp;</div>
<div id="info">Forms are used for you to add to pages to have your visitors fill out and results emailed to you. Most common use of the forms are for contact forms. Once you create a form you will need to add it to a page by creating or editing a page and selecting the form.</div>

<div >&nbsp;</div>
<div id="addNewLink" style="display: <?php print $formDisplay;?>;">
<?php
if($_REQUEST['subdo'] !== "editLink") {
	print "<div class=padTopBottom><div class=\"pageTitle\">Add new form below</div></div>";
}
?>
			<form method="post" name="newLink" action="index.php" style="padding:0; margin:0;"  onSubmit="return checkForm();">
			<div id="roundedForm">
			<div class="row">
				<div style="width:30%; float: left;">Form Name</div><div style="width:70%; float: right;">
					<input type="text" name="form_name" id="form_name" value="<?php if(!empty($_REQUEST['form_name'])) { print $_REQUEST['form_name']; } ?>" size="50" class="required">
					</div>
				<div class="cssClear"></div>
			</div>

			<div class="row">
				<div style="width:30%; float: left;">Email Results To: </div><div style="width:70%; float: right;"><input type="text" name="form_email_to"  id="form_email_to" value="<?php if(!empty($_REQUEST['form_email_to'])) { print $_REQUEST['form_email_to']; } ?>" size="80"  class="required">
				<br>To send to more than one email address, separate email addresses with a comma (,)</div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:30%; float: left;">Email Subject:  </div><div style="width:70%; float: right;"><input type="text" name="form_subject" id="form_subject" value="<?php if(!empty($_REQUEST['form_subject'])) { print $_REQUEST['form_subject']; } ?>" size="50"  class="required"></div>
				<div class="cssClear"></div>
			</div>

			<div class="row">
				<div style="width:30%; float: left;">Submit Form Button Text</div><div style="width:70%; float: right;">
					<input type="text" name="form_button" id="form_button" value="<?php if(!empty($_REQUEST['form_button'])) { print $_REQUEST['form_button']; } ?>" size="50"  class="required">
					</div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:30%; float: left;">Form Success Message</div><div style="width:70%; float: right;">
					<textarea name="form_end_message" id="form_end_message"  cols="50" rows="10"  class="required"><?php print $_REQUEST['form_end_message'];  ?></textarea>
					</div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:30%; float: left;"></div><div style="width:70%; float: right;">
				<input type="checkbox" name="form_captcha" <?php if($_REQUEST['form_captcha']=="1") { print "checked"; } ?>> Enable CAPTCHA</div>
				<div class="cssClear"></div>
			</div>

			<div class="row" style="text-align: center;">
			<input type="hidden" name="do" value="forms">
			<input type="hidden" name="action" value="addForm">
					<input type="hidden" name="saveform" value="yes">
				<input  type="submit" name="submit" class="submit" value="Continue"><br>
				<a href="#" onclick="openClose('pageSubMenus','addNewLink'); return false;">Cancel</a>
			<div class="cssClear"></div>
		</div>
		</div>
	</form>

	<div >&nbsp;</div>	<div >&nbsp;</div>
</div>


<?php
$total = countIt("ms_forms", "WHERE form_id>'0' $and_where "); 
if($total <= 0) { ?>
	<div id="cssMainContainer">
		<div  class=cssRowContainer style="text-align:center;">No forms found</div>
	</div>
<?php } else { ?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 40;
	$NPvars = array("do=forms", "orderq=".$_REQUEST['orderq']."" );
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "10%";
	$cw2 = "20%";
	$cw3 = "30%";
	$cw4 = "30%";
	$cw5 = "17%";
	$cw6 = "30%";
	$cw7 = "30%";
	?>

		<?php
		$datas = whileSQL("ms_forms", "*", "WHERE form_id>'0'   ");
		while ($data = mysqli_fetch_array($datas)) {
			$rownum++;
			?>
			<div id="roundedForm">
				<div class="row">
				<div style="width: <?php print $cw1;?>;" class="cssCell"><nobr>
					<a href="index.php?do=forms&action=deleteForm&form_id=<?php print $data['form_id'];?>"  onClick="return confirm('Are you sure you want to delete the form <?php print $data['form_name'];?>? ');"><?php print ai_delete;?></a> 

				</nobr></div>
				<div style="width: <?php print $cw2;?>;" class="cssCell"><h3><a href="index.php?do=forms&action=viewForm&form_id=<?php print $data['form_id'];?>"><?php print $data['form_name']; ?></h3></a>&nbsp;</div>

				<div style="width: <?php print $cw3;?>;" class="cssCell">Email to: <br><?php print $data['form_email_to']; ?>&nbsp;</div>
					<div style="width: <?php print $cw3;?>;" class="cssCell">Subject: <br>
					<?php print $data['form_subject']; ?>&nbsp;</div>
				<div class="cssClear"></div>
		</div>
	</div>
	<div>&nbsp;</div>
			<?php } ?>
		<div>&nbsp;</div>
		<?php } ?>
