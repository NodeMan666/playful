<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

if(!empty($_REQUEST['submitit'])) {

	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}

	updateSQL("ms_gal_exclusive", "
	gal_site_title='".$_REQUEST['gal_site_title']."', 
	gal_contact_page='".$_REQUEST['gal_contact_page']."', 
	gal_sub_gal_limit='".$_REQUEST['gal_sub_gal_limit']."', 
	gal_phone='".$_REQUEST['gal_phone']."', 
	gal_show_cover_password='".$_REQUEST['gal_show_cover_password']."', 
	gal_remove_footer='".$_REQUEST['gal_remove_footer']."', 
	gal_footer='".$_REQUEST['gal_footer']."', 
	gal_site_link='".$_REQUEST['gal_site_link']."',
	gal_redeem_credit='".$_REQUEST['gal_redeem_credit']."',
	redeem_credit='".$_REQUEST['redeem_credit']."',
	gal_redeem_coupon='".$_REQUEST['gal_redeem_coupon']."',
	redeem_coupon='".$_REQUEST['redeem_coupon']."',

	facebook='".$_REQUEST['facebook']."',
	twitter='".$_REQUEST['twitter']."',
	pinterest='".$_REQUEST['pinterest']."',
	email='".$_REQUEST['email']."',
	share_on='".$_REQUEST['share_on']."',
	more='".$_REQUEST['more']."',
	share='".$_REQUEST['share']."',
	my_favorites='".$_REQUEST['my_favorites']."',
	my_cart='".$_REQUEST['my_cart']."',
	my_account='".$_REQUEST['my_account']."',
	contact='".$_REQUEST['contact']."',
	gallery_home='".$_REQUEST['gallery_home']."',
	all_photos='".$_REQUEST['all_photos']."' ,
	page_layout='".$_REQUEST['page_layout']."',
	gal_disable_share='".$_REQUEST['gal_disable_share']."'
	WHERE gal_id='1' ");   		
	$_SESSION['sm'] = "Settings saved";
	session_write_close();
	header("location: index.php?do=look&view=galex");
	exit();
}
?>	

<?php  $ge = doSQL("ms_gal_exclusive", "*", "WHERE gal_id='1'   "); ?>
<script>
function gefooter() { 
	if($("#gal_remove_footer").attr("checked")) { 
		$("#gefooter").slideDown(200);
	} else { 
		$("#gefooter").slideUp(200);
	}
}
function gelang() { 
	$("#gelang").slideToggle(200);
}

</script>
<div id="pageTitle"><a href="index.php?do=look">Design</a> <?php print ai_sep;?> Gallery Exclusive</div>

<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">
	<div class="underline">
		<div class="label">Your Website Name</div>
		<div><input type="text" class="textfield" size="40" name="gal_site_title" id="gal_site_title" value="<?php  print htmlspecialchars(stripslashes($ge['gal_site_title']));?>"></div>
		<div class="pc">This will be your website name that is linked to your website under the gallery name at the top of the page.</div>
	</div>

	<div class="underline">
		<div class="label">Your Website Link</div>
		<div><input type="text" class="textfield" size="40" name="gal_site_link" id="gal_site_link" value="<?php  print htmlspecialchars(stripslashes($ge['gal_site_link']));?>"></div>
		<div class="pc">Leave this blank to link to your Sytist home page. If you want it to link to a page outside of Sytist, enter that URL here. It MUST start with http:// or https://</div>
	</div>

	<div class="underline">
		<div class="label">Your Contact Page</div>
		<div>
		<select name="gal_contact_page" id="gal_contact_page">
		<option value="">Do not use</option>
		<?php $dates = whileSQL("ms_calendar", "*", "WHERE date_cat<='0' AND page_home!='1' AND page_404!='1' AND green_screen_gallery!='1' ORDER BY date_title ASC");
		while($date = mysqli_fetch_array($dates)) { ?>
		<option value="<?php print $date['date_id'];?>" <?php if($date['date_id'] == $ge['gal_contact_page']) { ?>selected<?php } ?>><?php print $date['date_title'];?></option>
		<?php } ?>
		</select>
		</div>
		<div class="pc">Select the page that is your Contact page in Sytist and this will add a email / contact icon to the menu.</div>
	</div>


	<div class="underline">
		<div class="label">Your Phone Number</div>
		<div><input type="text" class="textfield" size="40" name="gal_phone" id="gal_phone" value="<?php  print htmlspecialchars(stripslashes($ge['gal_phone']));?>"></div>
		<div class="pc">By entering in your phone number here, it will create a phone icon on mobile view where it can be touched to call you.<br>Be sure it is properly formatted like: 1-888-555-1234</div>
	</div>

	<div class="underline">
		<div class="label">Sub Gallery Limit</div>
		<div>
		<select name="gal_sub_gal_limit" id="gal_sub_gal_limit">
		<option value="3" <?php if($ge['gal_sub_gal_limit'] == "3") { ?>selected<?php } ?>>3</option>
		<option value="4" <?php if($ge['gal_sub_gal_limit'] == "4") { ?>selected<?php } ?>>4</option>
		<option value="5" <?php if($ge['gal_sub_gal_limit'] == "5") { ?>selected<?php } ?>>5</option>
		<option value="6" <?php if($ge['gal_sub_gal_limit'] == "6") { ?>selected<?php } ?>>6</option>
		<option value="7" <?php if($ge['gal_sub_gal_limit'] == "7") { ?>selected<?php } ?>>7</option>
		<option value="8" <?php if($ge['gal_sub_gal_limit'] == "8") { ?>selected<?php } ?>>8</option>
		</select>
		</div>
		<div class="pc">If your galleries have sub galleries, it will list them in the menu across the top. If there are too many, it can push menu items to another row which we really don't want.<br>
		Select how many sub galleries to list until it shows a more icon to list all of them.</div>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="gal_show_cover_password" id="gal_show_cover_password" value="1" <?php if($ge['gal_show_cover_password'] == "1") { ?>checked<?php } ?>> <label for="gal_show_cover_password">Show cover photo if password protected and asking for password</label></div>
		<div class="pc">If the gallery is password protected, it will ask for a password. Check this option to still show the cover photo when asking for the password.</div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="gal_disable_share" id="gal_disable_share" value="1" <?php if($ge['gal_disable_share'] == "1") { ?>checked<?php } ?>> <label for="gal_disable_share">Disable Social Share of Gallery</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="gal_redeem_credit" id="gal_redeem_credit" value="1" <?php if($ge['gal_redeem_credit'] == "1") { ?>checked<?php } ?>> <label for="gal_redeem_credit">Add Redeem Print Credit To Menu</label></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="gal_redeem_coupon" id="gal_redeem_coupon" value="1" <?php if($ge['gal_redeem_coupon'] == "1") { ?>checked<?php } ?>> <label for="gal_redeem_coupon">Add Redeem Coupon To Menu</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="gal_remove_footer" id="gal_remove_footer" value="1" <?php if($ge['gal_remove_footer'] == "1") { ?>checked<?php } ?> onchange="gefooter();"> <label for="gal_remove_footer">Remove the main website footer</label></div>
		<div class="pc">This option will remove your website footer. If you check this, you will have the option to create a different footer below.</div>
	</div>


	<div class="underline <?php if($ge['gal_remove_footer'] <= 0) { ?>hide<?php } ?>" id="gefooter">
		<div class="label">Footer</div>
		<div><textarea   name="gal_footer" id="gal_footer" class="field100"  rows="4" cols="20"><?php print $ge['gal_footer'];?></textarea></div>
		<?php addEditor("gal_footer","3", "500", "0"); ?>
	</div>

	<div class="underline">
		<div class="label">Page Layout</div>
		<div>
		<select name="page_layout" id="page_layout">
		<option value="0">Use default layout (recommended)</option>
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='page' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($ge['page_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
		</select>
		</div>
	</div>



	<div class="pc"><a href="" onclick="gelang(); return false;">Edit text / language for menu items</a></div>
	<div id="gelang" class="hide">
		<div class="underline">
			<div class="label">Gallery Home</div>
			<div><input type="text" class="textfield" size="40" name="gallery_home" id="gallery_home" value="<?php  print htmlspecialchars(stripslashes($ge['gallery_home']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">All Photos</div>
			<div><input type="text" class="textfield" size="40" name="all_photos" id="all_photos" value="<?php  print htmlspecialchars(stripslashes($ge['all_photos']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">More</div>
			<div><input type="text" class="textfield" size="40" name="more" id="more" value="<?php  print htmlspecialchars(stripslashes($ge['more']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">My Favorites</div>
			<div><input type="text" class="textfield" size="40" name="my_favorites" id="my_favorites" value="<?php  print htmlspecialchars(stripslashes($ge['my_favorites']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">My Cart</div>
			<div><input type="text" class="textfield" size="40" name="my_cart" id="my_cart" value="<?php  print htmlspecialchars(stripslashes($ge['my_cart']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">My Account</div>
			<div><input type="text" class="textfield" size="40" name="my_account" id="my_account" value="<?php  print htmlspecialchars(stripslashes($ge['my_account']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Contact</div>
			<div><input type="text" class="textfield" size="40" name="contact" id="contact" value="<?php  print htmlspecialchars(stripslashes($ge['contact']));?>"></div>
		</div>

		<div class="underline">
			<div class="label">Share</div>
			<div><input type="text" class="textfield" size="40" name="share" id="share" value="<?php  print htmlspecialchars(stripslashes($ge['share']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Share on</div>
			<div><input type="text" class="textfield" size="40" name="share_on" id="share_on" value="<?php  print htmlspecialchars(stripslashes($ge['share_on']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Facebook</div>
			<div><input type="text" class="textfield" size="40" name="facebook" id="facebook" value="<?php  print htmlspecialchars(stripslashes($ge['facebook']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Twitter</div>
			<div><input type="text" class="textfield" size="40" name="twitter" id="twitter" value="<?php  print htmlspecialchars(stripslashes($ge['twitter']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Pinterest</div>
			<div><input type="text" class="textfield" size="40" name="pinterest" id="pinterest" value="<?php  print htmlspecialchars(stripslashes($ge['pinterest']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Email</div>
			<div><input type="text" class="textfield" size="40" name="email" id="email" value="<?php  print htmlspecialchars(stripslashes($ge['email']));?>"></div>
		</div>

		<div class="underline">
			<div class="label">Redeem Print Credit</div>
			<div><input type="text" class="textfield" size="40" name="redeem_credit" id="redeem_credit" value="<?php  print htmlspecialchars(stripslashes($ge['redeem_credit']));?>"></div>
		</div>
		<div class="underline">
			<div class="label">Redeem Coupon</div>
			<div><input type="text" class="textfield" size="40" name="redeem_coupon" id="redeem_coupon" value="<?php  print htmlspecialchars(stripslashes($ge['redeem_coupon']));?>"></div>
		</div>

		<div class="underline">
			<div class="label">Email</div>
			<div><input type="text" class="textfield" size="40" name="email" id="email" value="<?php  print htmlspecialchars(stripslashes($ge['email']));?>"></div>
		</div>
	
	</div>

	<div>&nbsp;</div>

	<div  class="bottomSave">
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="view" value="galex">
	<input type="hidden" name="submitit" value="yup">
	<input type="submit" name="submit" id="submit" class="submit" value="Update Settings">
	</div>
</form>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

