<?php if(customerLoggedIn()) { 
	header("location:".$setup['temp_url_folder']."/index.php?view=account");
	session_write_close();
	exit();
}
?>
<div id="newaccountsuccess" style="display: none;"><h1><?php print _new_account_success_;?></h1><br>
<?php

$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_new_account_success_message_);
$message = str_replace('<a href="/">','<a href="'.$setup['temp_url_folder'].'/">',$message);

print $message;				

?>
<div>&nbsp;</div>
<?php
if(!empty($_SESSION['last_gallery'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
	if(!empty($date['date_id'])) { 
		if($_SESSION['last_gallery_sub'] > 0) { 
			$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
			?>
			<div><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link'].""; ?>"><?php print _return_to_last_gallery_page_;?> "<?php print $date['date_title'];?> > <?php print $sub['sub_name'];?>"</a></div>
		<?php } else { ?>
			<div><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>"><?php print _return_to_last_gallery_page_;?> "<?php print $date['date_title'];?>"</a></div>


	<?php } ?>

<?php 
	// unset($_SESSION['last_gallery']);
	}
}
?>

<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
</div>
<?php $acc = doSQL("ms_new_accounts", "*", ""); ?>
	<div id="newaccount" style="max-width: 900px; margin:auto; width: 100%;">
	<form method=POST name="checkout" id="checkout" action="<?php print $site_setup['index_page'];?>" onSubmit="createaccountonly('newacc', '<?php if(customerLoggedIn()) { print "1"; } else { print "0"; } ?>','1'); return false;" >
<?php facebookLogin(); ?>

	<div id="maininfo">
	<div class="pc"><h1><?php print _new_account_page_title_;?></h1></div>
	<div class="pc"><?php print _new_account_page_message_;?></div>


	<div style="width: 50%; float: left;" <?php if(($acc['company_ask'] == "0")&&($acc['company_req'] == "0")==true) { print "class=\"hide\""; } ?> class="nofloatsmallleft">
		<div >
			<div class="pc"><?php print _company_;?></div>
			<div class="pc"><input type="text"  id="business_name" size="20" value="" class="newacc field100 <?php if($acc['company_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div style="width: 50%; float: left;" class="nofloatsmallleft">
		<div <?php if(($acc['first_name_ask'] == "0")&&($acc['first_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text"  id="first_name" size="20" value="<?php print htmlspecialchars($p['p_name']);?>" class="newacc field100 <?php if($acc['first_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<div style="width: 50%; float: right;" class="nofloatsmallleft">
		<div <?php if(($acc['last_name_ask'] == "0")&&($acc['last_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" id="last_name" size="20" value="<?php print htmlspecialchars($p['p_last_name']);?>" class="newacc field100 <?php if($acc['last_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>

	<div style="width: 50%; float: left;" class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _email_address_;?></div>
			<div class="pc"><input type="text"  id="email_address"  data-invalid-email="<?php print htmlspecialchars( _invalid_email_format_);?>" data-check="<?php print $acc['retype_email'];?>" size="40" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>

	<?php if($acc['retype_email'] == "1") { ?>
	<div style="width: 50%; float: right;" class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _retype_email_address_;?></div>
			<div class="pc"><input type="text"  id="email_address_2" size="40" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>
	<?php } ?>

	<?php if(($acc['retype_email'] == "0") && ($acc['retype_password'] == "1") == true) { ?><div class="clear"></div><?php } ?>

		<div id="passes">
		<div id="accountpasswords">
		<div style="width: 50%; float: left;" class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _password_;?></div>
				<div class="pc"><input type="password" id="newpassword" data-check="<?php print $acc['retype_password'];?>" autocomplete="off" size="40" value="<?php print $_REQUEST['newpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>

	<?php if($acc['retype_password'] == "1") { ?>

		<div style="width: 50%; float: right;" class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _re_type_password_;?></div>
				<div class="pc"><input type="password" id="renewpassword" autocomplete="off" size="40" value="<?php print $_REQUEST['renewpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>
		<?php } ?>
	<div class="cssClear"></div>
	</div>
		<div>&nbsp;</div>
		</div>


	<div>
		<div  <?php if(($acc['address_ask'] == "0")&&($acc['address_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _address_;?></div>
			<div class="pc"><input type="text"  id="address" size="40"    value="<?php print htmlspecialchars($person['p_address1']);?>" class="newacc field100 <?php if($acc['address_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>


	<div >
		<div <?php if(($acc['city_ask'] == "0")&&($acc['city_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _city_;?></div>
			<div class="pc"><input type="text"  id="city"  size="30"    value="<?php print htmlspecialchars($person['p_city']);?>" class="newacc field100 <?php if($acc['city_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>

	<?php 
		$ct = doSQL("ms_countries", "*", " WHERE ship_to='1' AND def='1' ");
	?>
	<div style="float: left;"  <?php if(($acc['state_ask'] == "0")&&($acc['state_req'] == "0")==true) { print "class=\"hide\""; } ?>  class="nofloatsmallleft">
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="state" id="state" class="newacc <?php if($acc['state_req'] == "1") { print "required"; } ?>">
			<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE state_country='".addslashes(stripslashes($ct['country_name']))."' ORDER BY state_name ASC ");
			if(mysqli_num_rows($states) <= 0) { ?>
			<option value="N/A">N/A</option>
			<?php } else { ?>
			<option value=""><?php print _select_state_;?></option>
				<?php 
				while($state = mysqli_fetch_array($states)) { ?>
				<option value="<?php print $state['state_abr'];?>" <?php if($person['p_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name'].""; ?></option>
				<?php } 
			}
			?>
			</select>
		</div>
		</div>

		<div style="float: left;"  <?php if(($acc['zip_ask'] == "0")&&($acc['zip_req'] == "0")==true) { print "class=\"hide\""; } ?>  class="nofloatsmallleft">
			<div class="pc"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="zip" id="zip" size="8" value="<?php print htmlspecialchars($person['p_zip']);?>" class="newacc <?php if($acc['zip_req'] == "1") { print "required"; } ?>"  ></div>
		</div>
		<div class="cssClear"></div>

	<div>
		<div  <?php if(($acc['country_ask'] == "0")&&($acc['country_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select  id="country"  class="newacc <?php if($acc['country_req'] == "1") { print "required"; } ?>" onChange="getstates(this.value,'0');">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($person['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
		<div>&nbsp;</div>
</div>

	<div style="width: 50%; float: left;" <?php if(($acc['phone_ask'] == "0")&&($acc['phone_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div >
			<div class="pc"><?php print _phone_;?></div>
			<div class="pc"><input type="text"  id="order_phone" size="20" value="" class="newacc field100 <?php if($acc['phone_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>

<?php if($em_settings['join_at_checkout'] == "1") { ?>
<div class="pc center"><input type="checkbox" name="join_ml" id="join_ml" value="1" class="newacc" <?php if($em_settings['join_at_checkout_default'] == "1") { print "checked"; } ?>> <label for="join_ml"><?php print $em_settings['join_at_checkout_text'];?></label>
<?php if(!empty($em_settings['join_at_checkout_desc'])) { ?><br><?php print $em_settings['join_at_checkout_desc'];?><?php } ?></div>
		<div>&nbsp;</div>
<?php } ?>


<div id="accresponse" class="hide" mismatchemail="<?php print htmlspecialchars(_email_addresses_do_not_match_);?>" samefirstlastname="<?php print htmlspecialchars(_can_not_have_same_first_last_name_);?>" passwordsnomatch="<?php print htmlspecialchars(_passwords_do_not_match_);?>" emptyfields="<?php print htmlspecialchars(_empty_fields_);?>"></div>
<input type="hidden" class="newacc" name="action" id="action" value="newaccount">
<input type="text" name="from_message_to" id="from_message_to" size="40" class="from_message_to" em="<?php print str_replace("@"," at ", $site_setup['contact_email']);?>" >
<div class="pc center"><input type="submit" name="submit" value="<?php print _create_account_button_;?>" class="submit"></div>

	</div>
<div class="cssClear"></div>



<div>&nbsp;</div>
</form>
</div>
<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#first_name").focus();
 }
</script>
