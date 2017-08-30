<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}


	updateSQL("ms_language", "_captcha_background_color_='".$_REQUEST['_captcha_background_color_']."' , _captcha_text_color_='".$_REQUEST['_captcha_text_color_']."', _captcha_description_='".$_REQUEST['_captcha_description_']."' , _captcha_not_correct_='".$_REQUEST['_captcha_not_correct_']."'  ");   		

		$_SESSION['sm'] = "Language saved";
		session_write_close();
		header("location: index.php?do=forms&action=captcha");
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
	$lang = doSQL("ms_language", "*", "  WHERE lang_default='1' ");
	?>
<div id="pageTitle"><a href="index.php?do=forms">Forms</a> <?php print ai_sep;?> CAPTCHA Settings</div>
<div class="pageContent">Here you can adjust the CAPTCHA colors and text.</div>
<div>&nbsp;</div>
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;">



	<div id="roundedForm">







		<div class="row">
			<div style="width:50%;" class="cssCell">Background Color</div><div style="width:50%;" class="cssCell"><input type="text" class="textfield" size="10" name="_captcha_background_color_" value="<?php  print htmlspecialchars(stripslashes($lang['_captcha_background_color_']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="row">
			<div style="width:50%;" class="cssCell">Text Color</div><div style="width:50%;" class="cssCell"><input type="text" name="_captcha_text_color_" size="10" value="<?php  print htmlspecialchars(stripslashes($lang['_captcha_text_color_']));?>"></div>
			<div class="cssClear"></div>
		</div>

		<div class="row">
			<div style="width:50%;" class="cssCell">Description</div><div style="width:50%;" class="cssCell"><textarea  class="textfield" size="40" name="_captcha_description_" rows="3" cols="40" style="width: 98%;"><?php  print htmlspecialchars(stripslashes($lang['_captcha_description_']));?></textarea></div>
			<div class="cssClear"></div>
		</div>
		<div class="row">
			<div style="width:50%;" class="cssCell">CAPTCHA not Correct</div><div style="width:50%;" class="cssCell"><textarea class="textfield" size="40" name="_captcha_not_correct_" rows="3" cols="40" style="width: 98%;"><?php  print htmlspecialchars(stripslashes($lang['_captcha_not_correct_']));?></textarea></div>
			<div class="cssClear"></div>
		</div>



	<div>&nbsp;</div>





	</div>
<div class="cssClear"></div>
	<div>&nbsp;</div>




<div style="width:100%;" class="cssCell" style="text-align: center;">



	<center>
	<input type="hidden" name="do" value="forms">
	<input type="hidden" name="action" value="captcha">
		<input type="hidden" name="submitit" value="yup">
		<input  type="submit" name="submit" value="Update Settings" class="submit">
	</center>
	</div>
</form>

<?php  } ?>