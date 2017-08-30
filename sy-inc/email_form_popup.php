<?php if((($main_cat['cat_req_login'] == "1")||($bcat['cat_req_login'] == "1"))&&(!customerLoggedIn())==true) { 
	// Doing this so it doesn't pop up on a category that requires to be logged in and the customer not logged in 
} else { 
	?>
	<?php $em_settings = doSQL("ms_email_list_settings","*", ""); ?>
	<?php if(!isset($_COOKIE['emview'])) { ?>
	<?php if($em_settings['signup_popup'] > 0) { ?>
	<script>
	$(document).ready(function() { 
		setTimeout(function() {
			$.get("<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_cart_actions.php?action=setMailingListCookie", function(data) {	});
			showpopupemailjoin();
		}, <?php print $em_settings['popup_time'] * 1000;?>);
	});
	</script>
	<?php } ?>
	<?php } ?>
	<?php if($_REQUEST['previewmlpopup'] == "1") { ?>
	<script>
	$(document).ready(function() { 
		setTimeout(function() {
			showpopupemailjoin();
		}, <?php print $em_settings['popup_time'] * 1000;?>);
	});
	</script>

	<?php } ?>
	<?php
	$em_form_name = "popemform";
	$em_form_field_class = "popems";
	$em_form_field_req_class="popemrequired";
	$em_form_error_id = "popemerror";
	$em_email_field_class = "popemerror";
	$em_email_form_container = "popemformcontainer";
	$em_email_form_success = "popemformsuccess";
	$em_form_location = "pop";
	?>
		<div id="mlsignuppopup" class="hide">
			<div id="signupcontainerbl" class="signupcontainerbl" style=" background: #<?php print $em_settings['popup_background'];?>;">
				<div class="signupcontainerblinner" style="color: #<?php print $em_settings['popup_text'];?>;">
				<div style="float: right;" id="emailsignupcancel"><span class="the-icons icon-cancel" onclick="closeemailsignup();" style="color: #<?php print $em_settings['popup_text'];?>; "></span></div>
				<div class="clear"></div>
				<?php require "email_form.php"; ?>
				
			</div>
		</div>
	</div>
	<?php

}?>