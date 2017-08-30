<?php $em_settings = doSQL("ms_email_list_settings","*", ""); ?>
<style>
.fsignupcontainer {  } 
.fsignupcontainerinner { text-align: center;  }

</style>
<?php 
if(empty($em_form_name)) { 
	$em_form_name = "emform";
	$em_form_field_class = "ems";
	$em_form_field_req_class="emrequired";
	$em_form_error_id = "emerror";
	$em_email_field_class = "emerror";
	$em_email_form_container = "emformcontainer";
	$em_email_form_success = "emformsuccess";
$em_form_location = "page";
}
?>
	<div id="fsignupcontainer" class="fsignupcontainer">
		<div class="fsignupcontainerinner">
			<div id="<?php print $em_email_form_container;?>">
				<div class="pc"><?php print $em_settings['signup_text'];?></div>
				<div>
				<form method="POST" name="<?php print $em_form_name;?>" id="<?php print $em_form_name;?>" action="<?php print $site_setup['index_page'];?>" onSubmit="emailsignup('<?php print $em_form_field_class;?>','<?php print $em_form_field_req_class;?>','<?php print $em_form_name;?>','<?php print $em_form_error_id;?>','<?php print $em_email_field_class;?>','<?php print $em_email_form_container;?>','<?php print $em_email_form_success;?>'); return false;" data-required-missing="<?php print htmlspecialchars($em_settings['blank_fields']);?>" data-invalid-email="<?php print htmlspecialchars($em_settings['invalid_email']);?>">
						<div class="pc"><input type="text"  id="enter_email" size="20" value="<?php print _email_address_;?>" class="defaultfield <?php print $em_form_field_class;?> <?php print $em_form_field_req_class;?> field100 <?php print $em_email_field_class;?>" style="max-width: 300px;" default="<?php print _email_address_;?>"> </div>
						<?php if($em_settings['first_name'] == "1") { ?>
						<div class="pc"><input type="text"  id="enter_first_name" size="20" value="<?php print _first_name_;?>" class="defaultfield <?php print $em_form_field_class;?> <?php if($em_settings['first_name_req'] == "1") { ?><?php print $em_form_field_req_class;?><?php } ?> field100" style="max-width: 300px;" default="<?php print _first_name_;?>"> </div>
						<?php } ?>
						<?php if($em_settings['last_name'] == "1") { ?>
						<div class="pc"><input type="text"  id="enter_last_name" size="20" value="<?php print _last_name_;?>" class="defaultfield <?php print $em_form_field_class;?> <?php if($em_settings['last_name_req'] == "1") { ?><?php print $em_form_field_req_class;?><?php } ?> field100" style="max-width: 300px;" default="<?php print _last_name_;?>"> </div>
						<?php } ?>
						<div class="pc submitdiv"><input type="submit" name="submit" class="submit" value="<?php print $em_settings['signup_button'];?>"></div>
						<div class="pc spinner24 submitsaving hide"></div>

					<div class="hide error" id="<?php print $em_form_error_id;?>"></div>
				<div class="cssClear"></div>
				<?php if(!empty($em_settings['signup_text_below'])) { ?>
				<div class="pc"><?php print $em_settings['signup_text_below'];?></div>
				<?php } ?>
				<input type="hidden" class="<?php print $em_form_field_class;?>" name="action" id="action" value="emailsignup">
				<input type="hidden" class="<?php print $em_form_field_class;?>"  id="did" value="<?php print $date_id;?>">
				<input type="hidden" class="<?php print $em_form_field_class;?>" name="elocation" id="elocation" value="<?php print $em_form_location;?>">

				</form>
				</div>
				<?php if($em_form_location == "pop") { ?>
					<div class="center pc"><a href="" onclick="closeemailsignup(); return false;"><?php print $em_settings['cancel_link'];?></a></div>
				<?php } ?>
			</div>
			<div id="<?php print $em_email_form_success;?>" class="hide  center">
				<div class="pc"><?php print $em_settings['signup_success'];?></div>
			<?php if($em_form_location == "pop") { ?>
				<div class="center pc"><a href="" onclick="closeemailsignup(); return false;"><?php print _compare_close_;?></a></div>
				<div>&nbsp;</div>
			<?php } ?>
		</div>
	</div>
</div>
