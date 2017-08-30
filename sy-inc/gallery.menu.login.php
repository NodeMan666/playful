<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/icons.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$fb = doSQL("ms_fb", "*", "");
$sytist_store = true;
$per_page = 20;
if(!empty($_SESSION['previewTheme'])) { 
	$css_id = $_SESSION['previewTheme'];
} else {
	$css_id = $site_setup['css'];
} 
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}
$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}

?>
<script>
function showloginform() { 
	$("#gallerymenuaccountform").slideUp(200, function() { 
		$("#gallerymenuloginform").slideDown(200);
	});
}

function showaccountform() { 
	$("#gallerymenuloginform").slideUp(200, function() { 
		$("#gallerymenuaccountform").slideDown(200);
	});
}
$(document).ready(function(){
	$(".defaultfield").bind('focus', function() { 
		if($(this).val() == $(this).attr("default")) { 
			$(this).val("");
		}
	});
	$('.defaultfield').bind('blur', function() { 
		if($(this).val() == "") { 
			$(this).val($(this).attr("default"));
		}
	});
});

</script>
<div style="padding: 24px;" class="inner">
<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
<div id="gallerylogincontent">

<div id="favoritesmessage" class="pc"><?php print _manage_favorites_login_;?></div>
<?php facebookLogin(); ?>
<div id="gallerymenuloginform" class="<?php if($_REQUEST['form'] !== "login") { if(!isset($_COOKIE['hasloggedin'])) { ?>hide<?php } } ?>">
	<div class="pc"><h2><?php print _log_in_;?></h2></div>
	<div class="pc"><?php print _log_in_text_;?></div>
	<form method="post" name="login" style="margin:0; padding: 0;" action="<?php print $site_setup['index_page'];?>"  onSubmit="customerlogin('login','<?php print $_REQUEST['view'];?>','<?php print $site_setup['index_page'];?>','<?php if(!empty($sub['sub_id'])) { print $sub['sub_link']; } ?>'); return false;" >
	<div class="pc">
		<div><?php print _email_address_;?></div>
		<div><input type="text" name="loginemailpage" size="20" id="loginemailpage" class="login  loginrequired" style="width: 100%; box-sizing:border-box;"></div>
	</div>
	<div class="pc">
		<div><?php print _password_;?></div>
		<div><input type="password" name="loginpasswordpage" size="20" id="loginpasswordpage" class="login  loginrequired"  style="width: 100%; box-sizing:border-box;"></div>
	</div>
	<div id="loginresponse" class="pc"></div>
	<div class="pc">
	<input type="hidden" name="pageaction" id="pageaction" class="login" value="login">
	<input type="hidden" name="sub" id="sub" class="login" value="<?php print $_REQUEST['sub'];?>">
	<div id="loginloadingpage" style="display: none;"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
	<input type="submit" name="submit" value="<?php print _log_in_button_;?>" class="submit" id="loginsubmitpage"  style="width: 100%; box-sizing:border-box;">
	<?php // if($add_create_account == true) { ?>
	<!-- <?php print _or_;?> <a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php print _create_an_account_;?></a> -->
	<?php // } ?>
	</div>
	<div class="pc center" id="forgotpasswordpagelink"><a href="" onClick="forgotpasswordpageform(); return false;" class="forgotpasswordpage"><?php print _forgot_password_;?></a></div>
	<div class="pc center" id=""><a href="<?php print $site_setup['index_page'];?>?view=newaccount" onclick="showaccountform(); return false;"><?php print _create_an_account_;?></a></div>

	</form>

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
</div>
<?php $acc = doSQL("ms_new_accounts", "*", ""); ?>
<div id="gallerymenuaccountform" class="<?php if($_REQUEST['form'] !== "account") {  if(isset($_COOKIE['hasloggedin'])) { ?>hide <?php } if($_REQUEST['form'] == "login") { ?>hide<?php } } ?>">
	<form method="POST" name="checkout" id="checkout" action="<?php print $site_setup['index_page'];?>" onSubmit="createaccountonly('newacc', '<?php if(customerLoggedIn()) { print "1"; } else { print "0"; } ?>','1','1','<?php if(isset($_SESSION['saveandlogin'])) { ?>1<?php } ?>','<?php print $_SESSION['saveandlogin'];?>'); return false;" >

	<div id="maininfo">

	<div class="pc"><h2><?php print _new_account_page_title_;?></h2></div>
	<div class="pc"><?php print _new_account_page_message_;?></div>


	<div  <?php if(($acc['company_ask'] == "0")&&($acc['company_req'] == "0")==true) { print "class=\"hide\""; } ?> class="nofloatsmallleft">
		<div >
			<div class="pc"><?php print _company_;?></div>
			<div class="pc"><input type="text"  id="business_name" size="20" value="" class="newacc field100 <?php if($acc['company_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<div class="cssClear"></div>


	<div  class="nofloatsmallleft">
		<div <?php if(($acc['first_name_ask'] == "0")&&($acc['first_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _first_name_;?></div>
			<div class="pc"><input type="text"  id="first_name" size="20" value="<?php print htmlspecialchars($p['p_name']);?>" class="newacc field100 <?php if($acc['first_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
	<div  class="nofloatsmallleft">
		<div <?php if(($acc['last_name_ask'] == "0")&&($acc['last_name_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _last_name_;?></div>
			<div class="pc"><input type="text" id="last_name" size="20" value="<?php print htmlspecialchars($p['p_last_name']);?>" class="newacc field100 <?php if($acc['last_name_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>

	<div  class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _email_address_;?></div>
			<div class="pc"><input type="text"  id="email_address" data-invalid-email="<?php print htmlspecialchars( _invalid_email_format_);?>" data-check="<?php print $acc['retype_email'];?>" size="40" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>

	<?php if($acc['retype_email'] == "1") { ?>
	<div  class="nofloatsmallleft">
		<div>
			<div class="pc"><?php print _retype_email_address_;?></div>
			<div class="pc"><input type="text"  id="email_address_2" size="40" value="<?php print htmlspecialchars($p['p_email']);?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
		</div>
	</div>
	<?php } ?>

	<?php if(($acc['retype_email'] == "0") && ($acc['retype_password'] == "1") == true) { ?><div class="clear"></div><?php } ?>

		<div id="passes">
		<div id="accountpasswords">
		<div  class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _password_;?></div>
				<div class="pc"><input type="password" id="newpassword" data-check="<?php print $acc['retype_password'];?>" autocomplete="off" size="40" value="<?php print $_REQUEST['newpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>

	<?php if($acc['retype_password'] == "1") { ?>

		<div  class="nofloatsmallleft">
			<div>
				<div class="pc"><?php print _re_type_password_;?></div>
				<div class="pc"><input type="password" id="renewpassword" autocomplete="off" size="40" value="<?php print $_REQUEST['renewpassword'];?>" <?php print "class=\"newacc field100 required\"";  ?>></div>
			</div>
		</div>
		<?php } ?>
	<div class="cssClear"></div>
	</div>
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


	<div style="float: left;"  <?php if(($acc['state_ask'] == "0")&&($acc['state_req'] == "0")==true) { print "class=\"hide\""; } ?>  class="nofloatsmallleft">
			<div class="pc"><?php print _state_;?></div>
			<div class="pc">
			<select name="state" id="state" class="newacc <?php if($acc['state_req'] == "1") { print "required"; } ?>" onChange="getTax();">
			<option value=""><?php print _select_state_;?></option>
			<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY def DESC, country_name, state_name ASC ");
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($person['p_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
			<?php } ?>
			</select>
		</div>
		</div>

		<div style="float: left;"  <?php if(($acc['zip_ask'] == "0")&&($acc['zip_req'] == "0")==true) { print "class=\"hide\""; } ?>  class="nofloatsmallleft">
			<div class="pc"><?php print _zip_;?></div>
			<div class="pc"><input type="text" name="zip" id="zip" size="8" value="<?php print htmlspecialchars($person['p_zip']);?>" class="newacc <?php if($acc['zip_req'] == "1") { print "required"; } ?>"  onChange="getTax();"></div>
		</div>
		<div class="cssClear"></div>

	<div>
		<div  <?php if(($acc['country_ask'] == "0")&&($acc['country_req'] == "0")==true) { print "class=\"hide\""; } ?>>
			<div class="pc"><?php print _country_;?></div>
			<div class="pc">
			<select  id="country"  class="newacc <?php if($acc['country_req'] == "1") { print "required"; } ?>"  onChange="getTax();">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($person['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
		</div>
		</div>
</div>

	<div  <?php if(($acc['phone_ask'] == "0")&&($acc['phone_req'] == "0")==true) { print "class=\"hide\""; } ?>>
		<div >
			<div class="pc"><?php print _phone_;?></div>
			<div class="pc"><input type="text"  id="order_phone" size="20" value="" class="newacc field100 <?php if($acc['phone_req'] == "1") { print "required"; } ?>"></div>
		</div>
	</div>
<div class="cssClear"></div>

<?php 
$em_settings = doSQL("ms_email_list_settings","*", ""); 
if($em_settings['join_at_checkout'] == "1") { ?>
<div class="pc center"><input type="checkbox" name="join_ml" id="join_ml" value="1" class="newacc" <?php if($em_settings['join_at_checkout_default'] == "1") { print "checked"; } ?>> <label for="join_ml"><?php print $em_settings['join_at_checkout_text'];?></label>
<?php if(!empty($em_settings['join_at_checkout_desc'])) { ?><br><?php print $em_settings['join_at_checkout_desc'];?><?php } ?></div>
		<div>&nbsp;</div>
<?php } ?>


<div class="pc"><div id="accresponse" class="hide" mismatchemail="<?php print htmlspecialchars(_email_addresses_do_not_match_);?>" samefirstlastname="<?php print htmlspecialchars(_can_not_have_same_first_last_name_);?>" passwordsnomatch="<?php print htmlspecialchars(_passwords_do_not_match_);?>" emptyfields="<?php print htmlspecialchars(_empty_fields_);?>"></div></div>
<input type="hidden" class="newacc" name="action" id="action" value="newaccount">
<input type="text" name="from_message_to" id="from_message_to" size="40" class="from_message_to" em="<?php print str_replace("@"," at ", $site_setup['contact_email']);?>" >
<div class="pc center">
<div id="nasubmit"><input  type="submit" name="submit" value="<?php print _create_account_button_;?>" class="submit" style="width: 100%; box-sizing:border-box;"></div>
<div id="nasubmitloading" class="hide center"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
</div>
	<div class="pc center"><a href="" onclick="showloginform(); return false;"><?php print _log_into_existing_account_;?></a></div>

	</div>
<div class="cssClear"></div>
</form>

</div>
</div>
</div>