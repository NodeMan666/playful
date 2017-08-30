	<div id="pagelogin">
<?php if($_REQUEST['view']!=="checkout") { facebookLogin(); } ?>
	<div class="pc"><h3><?php print _log_in_;?></h3></div>
	<div class="pc"><?php print _log_in_text_;?></div>
	<?php if(($_REQUEST['view']!=="checkout")&&($_REQUEST['view'] !== "account")==true) { 
		$_REQUEST['view'] = "";
	}
	if(!empty($_REQUEST['d'])) { 
		foreach($_REQUEST AS $id => $value) {
			if(!empty($value)) { 
				if(!is_array($value)) { 
					$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
					$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
				}
			}
		}
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(date_id)='".$_REQUEST['d']."' ");
		if(!empty($date['date_id'])) { 
			$_SESSION['return_page'] = $setup['temp_url_folder']."".$date['cat_folder']."/".$date['date_link']."/";
		}
	}
	?>
	<form method="post" name="login" action="<?php print $site_setup['index_page'];?>"  onSubmit="customerlogin('login','<?php print $_REQUEST['view'];?>','<?php print $site_setup['index_page'];?>'); return false;" >
	<div class="pc">
		<div><?php print _email_address_;?></div>
		<div><input type="text" name="loginemailpage" size="20" id="loginemailpage" class="login field100 loginrequired"></div>
	</div>
	<div class="pc">
		<div><?php print _password_;?></div>
		<div><input type="password" name="loginpasswordpage" size="20" id="loginpasswordpage" class="login field100 loginrequired"></div>
	</div>
	<div id="loginresponse" class="pc"></div>
	<div class="pc">
	<input type="hidden" name="pageaction" id="pageaction" class="login" value="login">
	<input type="hidden" id="sub" value="<?php print $sub['sub_link'];?>">
	<div id="loginloadingpage" style="display: none;"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
	<input type="submit" name="submit" value="<?php print _log_in_button_;?>" class="submit" id="loginsubmitpage">
	<?php // if($add_create_account == true) { ?>
	<!-- <?php print _or_;?> <a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php print _create_an_account_;?></a> -->
	<?php // } ?>
	</div>
	<div class="pc" id="forgotpasswordpagelink"><a href="" onClick="forgotpasswordpageform(); return false;" class="forgotpasswordpage"><?php print _forgot_password_;?></a></div>
	<div class="pc" id=""><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php print _create_an_account_;?></a></div>

	</form>
	<div>&nbsp;</div>
	</div>

		<div id="forgotpasswordpage" style="foat: left; display: none;">
		<form method="post" name="login" action="<?php print $site_setup['index_page'];?>"  onSubmit="newpasswordpage('forgotemailpage'); return false;" >
		<div class="pc"><h3><?php print _forgot_password_;?></h3></div>
			<div class="pc">
			<input type="text" name="forgotemailpage" size="30" id="forgotemailpage" class="forgotemailpage feprequired defaultfield" default="<?php print _email_address_;?>" value="<?php print _email_address_;?>"> 
			</div>
			<div class="pc">
			<input type="hidden" name="pageaction" id="pageaction" class="forgotemailpage" value="forgotemail">
			<input type="submit" name="submit" value="<?php print _forgot_password_send_;?>" class="submit">  <a href="" onclick="cancelforgotpassword(); return false;"><?php print _cancel_;?></a>
			</div>
			<div id="forgotemailmessagepage" class="pc"><?php print _forgot_password_instructions_;?></div>
	</form>

	</div>
			<div id="forgotloginresponsepage" class="hide" success="<?php print htmlspecialchars(_check_your_email_forgot_password_);?>"></div>
	<div>&nbsp;</div>

<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#loginemailpage").focus();
 }
</script>