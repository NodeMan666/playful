<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if($_REQUEST['action'] == "submitreg") {
	if(empty($_REQUEST['reg_name'])) {
		$error .= "<div>Your name is blank</div>";
	}
	if(empty($_REQUEST['reg_email'])) {
		$error .= "<div>Your email address is blank</div>";
	}
	if(empty($_REQUEST['reg_site_name'])) {
		$error .= "<div>Your Website is blank</div>";
	}

	if((!empty($_REQUEST['reg_email']))AND($_REQUEST['reg_email']!==$_REQUEST['reg_email_ck'])==true) {
		$error .= "<div>Your email addresses do not match</div>";
	}
	if(empty($_REQUEST['reg_key'])) {
		$error .= "<div>Registration key is blank</div>";
	}
	if(!empty($error)) {
		print "<div class=error>$error</div>";
		print "<div>&nbsp;</div>";
		regForm();
	} else {
		session_write_close();
		header("location: http://www.picturespro.com/register.php?URL=".$_SERVER['HTTP_HOST']."&IP=".getUserIP()."&RETURN=http://".$_SERVER['HTTP_HOST']."".$_SERVER['PHP_SELF']."&reg_name=".$_REQUEST['reg_name']."&reg_email=".$_REQUEST['reg_email']."&reg_site_name=".$_REQUEST['reg_site_name']."&reg_key=".$_REQUEST['reg_key']."&prod_prod_id=sytist1");
		exit();

	}
} elseif(($_REQUEST['status'] == "good")&&($_REQUEST['action'] == "register")&&(!empty($_REQUEST['reg_key']))==true) {
	$date = date('Y-m-d');
	$id = insertSQL("ms_register", "reg_name='".addslashes(stripslashes($_REQUEST['reg_name']))."' , reg_email='".addslashes(stripslashes($_REQUEST['reg_email']))."' , reg_key='".addslashes(stripslashes($_REQUEST['reg_key']))."' , reg_date='$date', reg_keys='".$_REQUEST['regKeys']."' , reg_domain='".$_REQUEST['reg_url']."', reg_dmd='".$_REQUEST['dmd']."' ");

	updateSQL("ms_settings", "contact_email='".addslashes(stripslashes($_REQUEST['reg_email']))."', website_title='".addslashes(stripslashes($_REQUEST['reg_site_name']))."' , header='<p>".addslashes(stripslashes($_REQUEST['reg_site_name']))."</p>' , meta_title='".addslashes(stripslashes($_REQUEST['reg_site_name']))."' ");
	updateSQL("ms_gal_exclusive", "gal_site_title='".addslashes(stripslashes($_REQUEST['reg_site_name']))."' ");

	updateSQL("ms_forms", "form_email_to='".addslashes(stripslashes($_REQUEST['reg_email']))."' ");

	session_write_close();
	header("location: index.php?regstatus=complete");
	die();

} else {
	regForm();
}

?>

<?php function regForm() { 
	global $loggedin;
		?>

<div class="pageContent"><h1>Welcome to your <i>Sytist</i>! Please register below.</h1></div>
<div id="roundedFormContain">

<div class="pageContent">Thank you for purchasing <i>sytist</i>. Before you can go any further you will need to register it. You will only have to do this once.<br><br>
In the email you receive right after completing your purchase you will find your registration key. If is best to copy it from the email and paste it below.
<br><br>
If you have any problems registering, please <a href="http://www.picturespro.com/contact/" target="_blank">contact support</a>.
</div>

<?php  if($_REQUEST['ms'] == "1") { print "<br><div class=error>There is a problem with the registration of this product. Please contact support at <a href=\"mailto:info@sytist.com\">info@sytist.com</a> to resolve this issue.</div><br><br>"; } ?>

<?php
if(empty($_REQUEST['reg_name'])) { 
	$_REQUEST['reg_name'] = $loggedin['admin_name'];
}
if(empty($_REQUEST['reg_email'])) { 
	$_REQUEST['reg_email'] = $loggedin['admin_email'];
}
?>
<div style="width: 550px; margin: auto;">
<form method="post" name="theForm" action="index.php" style="margin: 0; padding: 0;">
	<div id="roundedForm">
		<div class="row">
			<div style="width:50%; float: left;">Your Name</div><div style="width:50%; float: right;"><input type="text" name="reg_name" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_name']));?>" style="width: 97%;"></div>
			<div class="cssClear"></div>
		</div>

		<div class="row">
			<div style="width:50%; float: left;">Your Website Name</div><div style="width:50%; float: right;"><input type="text" name="reg_site_name" size="40"  style="width: 97%;" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_site_name']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="row">
			<div style="width:50%; float: left;">Your Email Address</div><div style="width:50%; float: right;"><input type="text" name="reg_email" size="40"  style="width: 97%;" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_email']));?>"></div>
			<div class="cssClear"></div>
		</div>
		<div class="row">
			<div style="width:50%; float: left;">Re-enter Email Address</div><div style="width:50%; float: right;"><input type="text" name="reg_email_ck"  style="width: 97%;" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_email_ck']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="row">
			<div style="width:50%; float: left;">Registration Key</div><div style="width:50%; float: right;"><input type="text" name="reg_key" size="40" style="width: 97%;"  value="<?php  print htmlspecialchars(stripslashes($_REQUEST['reg_key']));?>"></div>
			<div class="cssClear"></div>
		</div>
		<div class="row">
			<div ><i>Your registration key will be in the email you received when you purchased <i>sytist</i> and on your order.</i></div>
		</div>

		<div class="row" style="text-align: center;">
			<div style="width:100%; float: left;">
			<input type="hidden" name="do" value="register">
			<input type="hidden" name="action" value="submitreg">
			<input type="hidden" name="prod_prod_id" value="photocart">

			<input  type="submit" name="submission" class="submit"  onClick="addCheck(this.form.name);" value="Register Now">
			</div>
			<div class="cssClear"></div>
		</div>






</div>
</form>
</div>



<?php  } ?>

