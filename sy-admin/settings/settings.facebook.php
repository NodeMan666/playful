<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		//	print "<li>$id = $value";
		}


	updateSQL("ms_fb", "facebook_app_id='".$_REQUEST['facebook_app_id']."',  admin_ids='".$_REQUEST['admin_ids']."', facebook_link='".$_REQUEST['facebook_link']."', like_colorscheme='".$_REQUEST['like_colorscheme']."', pages='".$_REQUEST['pages']."', blogs='".$_REQUEST['blogs']."' , photos='".$_REQUEST['photos']."' , com_pages='".$_REQUEST['com_pages']."' , com_blog='".$_REQUEST['com_blog']."', com_photos='".$_REQUEST['com_photos']."' , email_like='".$_REQUEST['email_like']."'  , email_comments='".$_REQUEST['email_comments']."', share_location='".$_REQUEST['share_location']."', use_like_box='".$_REQUEST['use_like_box']."' , disable_facebook='".$_REQUEST['disable_facebook']."', fb_show_faces='".$_REQUEST['fb_show_faces']."', fb_stream='".$_REQUEST['fb_stream']."',  fb_header='".$_REQUEST['fb_header']."', fb_photo_share='".$_REQUEST['fb_photo_share']."', fb_lang='".$_REQUEST['fb_lang']."', share_type='".$_REQUEST['share_type']."', share_add_like='".$_REQUEST['share_add_like']."', share_text='".$_REQUEST['share_text']."', share_text_placement='".$_REQUEST['share_text_placement']."', page_share_text='".$_REQUEST['page_share_text']."', facebook_app_secret='".$_REQUEST['facebook_app_secret']."', facebook_login='".$_REQUEST['facebook_login']."' ");   		


	$shares = whileSQL("ms_share", "*", "ORDER BY share_name ASC ");
	while($share = mysqli_fetch_array($shares)) {
		updateSQL("ms_share", "share_status='".$_REQUEST[$share['share_id_name']]."' WHERE share_id='".$share['share_id']."' ");
	}

		updateSQL("ms_share", "share_code='".$_REQUEST['share_code']."' WHERE share_add_code='1' ");

		$_SESSION['sm'] = "Settings saved";
		session_write_close();
		header("location: index.php?do=settings&action=fb");
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
	$fb = doSQL("ms_fb", "*", "  ");
	$lang = doSQL("ms_language", "*", "  WHERE lang_default='1' ");
	?>
	<script>
	function likeboxsettings() { 
		if($("#use_like_box").attr("checked")) { 
			$("#likeboxsettings").slideDown(200);
		} else { 
			$("#likeboxsettings").slideUp(200);
		}
	}
	</script>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Facebook & Share</div>

<form name="register" action="index.php" method="post" style="padding:0; margin: 0;" onSubmit="return checkForm('','submitButton');">
<div style="width: 48%; float: left;">



	<div class="underlinelabel"><input type="checkbox" name="facebook_login" id="facebook_login" value="1" <?php if($fb['facebook_login'] == "1") { print "checked"; } ?>> <label for="facebook_login">Use Facebook Login</label></div>
	<div class="underlinespacer">This will allow users to create an account and login with their Facebook account. When they do, you will capture their first name, last name, email address and facebook page link. You must enter in a Facebook APP ID and Secret below for this to work. <?php if($setup['unbranded'] !== true) { ?><a href="https://www.picturespro.com/sytist-manual/articles/login-with-facebook/" target="blank">Learn more about this feature</a>.<?php } ?></div>
	


	<div class="underlinelabel"><input type="checkbox" name="use_like_box" id="use_like_box" value="1" <?php if($fb['use_like_box'] == "1") { print "checked"; } ?> onchange="likeboxsettings();"> <label for="use_like_box">Add Facebook Like Box Tab</label></div>
	<div class="underlinespacer">This will add a tab to the left of the website pages for your Facebook page.</div>

<div id="likeboxsettings" class="<?php if($fb['use_like_box'] <= 0) { print "hide"; } ?>">
	<div class="underline">
		<div><span class="bold">Facebook page link *</span></div>
		<div><input type="text" class="textfield" size="40" name="facebook_link" value="<?php  print htmlspecialchars(stripslashes($fb['facebook_link']));?>"></div>
		<div>Example: http://www.facebook.com/mypagename</div>
	</div>
	<div class="underline"><input type="checkbox" name="fb_show_faces" value="true" <?php if($fb['fb_show_faces'] == "true") { print "checked"; } ?>> Show faces</div>
	<div class="underline"><input type="checkbox" name="fb_stream" value="true" <?php if($fb['fb_stream'] == "true") { print "checked"; } ?>> Show wall posts</div>
	<div class="underline"><input type="checkbox" name="fb_header" value="true" <?php if($fb['fb_header'] == "true") { print "checked"; } ?>> Show header</div>
<div>&nbsp;</div>
</div>

 <div class="underlinelabel">Facebook App ID</span></div>
		<div class="underline"><input type="text" class="textfield" size="40" name="facebook_app_id" value="<?php  print htmlspecialchars(stripslashes($fb['facebook_app_id']));?>">
		<?php if($setup['sytist_hosted'] == true) { ?>
			<div>The default Facebook APP ID is: 1563375620579878</div>
		<?php } ?>
		 <?php if($setup['unbranded'] !== true) { ?><div><a href="http://www.picturespro.com/sytist-manual/articles/getting-facebook-app-id/" target="_blank">Article on creating a Facebook App ID</a></div><?php } ?>
		</div>
 
 <div class="underlinelabel">Facebook App Secret</span></div>
		<div class="underline"><input type="text" class="textfield" size="40" name="facebook_app_secret" value="<?php  print htmlspecialchars(stripslashes($fb['facebook_app_secret']));?>">
		<?php if($setup['sytist_hosted'] == true) { ?>
			<div>The default Facebook APP secret is: 4bab944daa18ebff22deec4fd9cb59fc</div>
		<?php } ?>
		<div>This is needed for the Facebook Login feature.</div>
		</div>

 <div class="underlinelabel">Facebook Language</span></div>
		<div class="underline"><input type="text" class="textfield" size="5" maxlength="5"  name="fb_lang" value="<?php  print htmlspecialchars(stripslashes($fb['fb_lang']));?>"> <a href="http://www.picturespro.com/sytist-manual/articles/changing-facebook-comments-language/" target="_blank">Click here to get the language code if you need to change the language</a>.</div>


<div>&nbsp;</div>
<div class="pageContent"><h3>* Facebook Page Link</h3>
To use the Like Box Tab, you must have a Facebook "Page" for your business. This will not work with your personal profile. If you don't have a Facebook "Page", go to <a href="http://www.facebook.com/pages/" target="_blank">http://www.facebook.com/pages/</a> to get started.</div>

<div>&nbsp;</div>

	<div class="pageContent"><input type="checkbox" name="disable_facebook" value="1" <?php if($fb['disable_facebook'] == "1") { print "checked"; } ?>> Disable all Facebook features.<br>
	This will remove any code associated with Facebook which can sometimes slow down the website.</div>


</div>


<div style="width: 48%; float: right;">
<script>
function selectshareoptions() { 
	if($("#share_type1").attr("checked")) { 
		$(".share1").slideDown(200);
		$(".share2").slideUp(200);
	}
	if($("#share_type2").attr("checked")) { 
		$(".share2").slideDown(200);
		$(".share1").slideUp(200);
	}

}
</script>

<div class="underlinelabel">Page Share Options</div>
<div class="underlinespacer">Select which style of share to use</div>
<div class="underline"><input type="radio" name="share_type" id="share_type1" value="0" <?php if($fb['share_type'] == "0") { print "checked"; } ?> onchange="selectshareoptions();"> <label for="share_type1"><img src="graphics/share1.JPG" align="absmiddle"></label>
<br>This uses the share buttons that are generated through Facebook, Pinterest, etc ... This option <b>does not log share actions</b>.</div>
<div class="underline"><input type="radio" name="share_type" id="share_type2" value="1" <?php if($fb['share_type'] == "1") { print "checked"; } ?> onchange="selectshareoptions();"> <label for="share_type2"><img src="graphics/share2.JPG" align="absmiddle"></label><br>This uses icons on the site and looks cleaner. This option <b>does log share actions</b>.</div>



<div class="share1 <?php if($fb['share_type'] == "1") {?>hidden<?php } ?>">
	<div class="underlinespacer">Select the share options you would like to use</div>
	<div>
		<?php $shares = whileSQL("ms_share", "*", "WHERE share_add_code='0' ORDER BY share_name ASC ");
				while($share = mysqli_fetch_array($shares)) { ?>
		<div class="underline"><input type="checkbox" name="<?php print $share['share_id_name'];?>" value="1" <?php if($share['share_status'] == "1") { print "checked"; } ?>> <?php print $share['share_name'];?></div>
		<?php } ?>
	</div>
</div>
<div class="share2 <?php if($fb['share_type'] == "0") {?>hidden<?php } ?>" >
	<div class="underline">
		<div class="label">Add share text</div>
		<div><input type="text" name="share_text" id="share_text" value="<?php print htmlspecialchars($fb['share_text']);?>"></div>
	</div>
	<div class="underline">
	Share text placement: <select name="share_text_placement" id="share_text_placement">
	<option value="left" <?php if($fb['share_text_placement'] == "left") { print "selected"; } ?>>Left of icons</option>
	<option value="above" <?php if($fb['share_text_placement'] == "above") { print "selected"; } ?>>Above icons</option>
	<option value="right" <?php if($fb['share_text_placement'] == "right") { print "selected"; } ?>>Right of icons</option>
	<option value="below" <?php if($fb['share_text_placement'] == "below") { print "selected"; } ?>>Below icons</option>
	</select>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="share_add_like" id="share_add_like" value="1" <?php if($fb['share_add_like'] == "1") { print "checked"; } ?>> <label for="share_add_like">Add Facebook Like button under the share options</label></div>
	</div>

</div>


	<div class="underlinelabel">AddThis.com or other third party share service</div>
	<div class="underlinespacer">If you are using a third party share serivce like addthis.com, they will supply you with some code to add to your pages. You can copy and paste that code below.</div>
	<?php $sharecode = doSQL("ms_share", "*", "WHERE share_add_code='1' ORDER BY share_name ASC ");
?>
	<div class="underline"><input type="checkbox" name="<?php print $sharecode['share_id_name'];?>" value="1" <?php if($sharecode['share_status'] == "1") { print "checked"; } ?>> <?php print $sharecode['share_name'];?></div>
	<div class="underline"><textarea name="share_code" rows="5" cols="40" style="width: 97%;"> <?php print htmlspecialchars(stripslashes($sharecode['share_code']));?></textarea></div>



<div>&nbsp;</div>
<div class="underlinelabel">Private Gallery Share Description</div>
<div class="underlinespacer">If a private gallery is shared, this message is added to the description to include the password. Use [PASSWORD] where you want the password to display.</div>

	<div class="underline"><textarea name="page_share_text" rows="5" cols="40" style="width: 97%;"><?php print htmlspecialchars(stripslashes($fb['page_share_text']));?></textarea></div>


<div>&nbsp;</div>
<div class="underlinelabel">Photo Share Description</div>
<div class="underlinespacer">You can enter in a general description that is added when a photo is shared.</div>

	<div class="underline"><textarea name="fb_photo_share" rows="5" cols="40" style="width: 97%;"><?php print htmlspecialchars(stripslashes($fb['fb_photo_share']));?></textarea></div>


<div>&nbsp;</div>



	<div class="underline">
		<div class="label"><input type="checkbox" name="email_like" value="1" <?php if($fb['email_like'] == "1") { print "checked"; } ?>> Check this box to receive email notification when someone "Likes" a page.</div>
		</div>
<div>&nbsp;</div>



</div>
<div class="cssClear"></div>
<div  class="bottomSave">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="fb">
		<input type="hidden" name="submitit" value="yup">

		<input  type="submit" name="submit" value="Update Settings" class="submit" id="submitButton">
	</center>
</div>
			</form>


<div>&nbsp;</div>
<!-- 
<div style="width: 48%; float: left;">

<div class="pageContent"><h3>Getting your Facebook APP ID</h3>
A facebook APP ID is required to use the Facebook features. To get your APP ID: </div>
<div class="pageContent">
1) GO here: <a href="http://developers.facebook.com/setup" target="_blank">http://developers.facebook.com/setup</a><br>
<br>
2) Once you complete that page you will now have some info which includes your APP ID<br>
<br>
XXXXXXXXXXX is now registered with Facebook. You can edit your app settings at any time in your Developer Dashboard.<br>
App Name:&nbsp;&nbsp;&nbsp; XXXXXXXX<br>
App URL:&nbsp;&nbsp;&nbsp; http://www.XXXXXXXXXX.com/<br>

App ID:&nbsp;&nbsp;&nbsp; <span style="font-weight: bold;">123456789123456</span><br>
App Secret:&nbsp;&nbsp;&nbsp; XXXXXXXXXXXXXXXXX
</div>
<div>&nbsp;</div>
<div class="pageContent"><h3>Getting your Facebook ADMIN ID</h3>
The admin ID is not required, but you can use it to add additional admins. Finding your admin ID can be difficult. You may want to google how to get it. </div>
<div class="pageContent">

</div>
</div>

<div style="width: 48%; float: right;">
<div class="pageContent">

</div>


</div>
<div class="cssClear"></div>
-->
<div>&nbsp;</div>

<?php  } ?>