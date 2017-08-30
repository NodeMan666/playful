<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 

if($_REQUEST['subdo'] == "updateNewAccounts") { 

	updateSQL("ms_new_accounts", "
	first_name_ask='".$_REQUEST['first_name_ask']."',
	first_name_req='".$_REQUEST['first_name_req']."',
	last_name_ask='".$_REQUEST['last_name_ask']."',
	last_name_req='".$_REQUEST['last_name_req']."',
	address_ask='".$_REQUEST['address_ask']."',
	address_req='".$_REQUEST['address_req']."',
	city_ask='".$_REQUEST['city_ask']."',
	city_req='".$_REQUEST['city_req']."',
	state_ask='".$_REQUEST['state_ask']."',
	state_req='".$_REQUEST['state_req']."',
	zip_ask='".$_REQUEST['zip_ask']."',
	zip_req='".$_REQUEST['zip_req']."',
	country_ask='".$_REQUEST['country_ask']."',
	country_req='".$_REQUEST['country_req']."',
	company_ask='".$_REQUEST['company_ask']."',
	company_req='".$_REQUEST['company_req']."',
	phone_ask='".$_REQUEST['phone_ask']."',
	phone_req='".$_REQUEST['phone_req']."',

	co_first_name_ask='".$_REQUEST['co_first_name_ask']."',
	co_first_name_req='".$_REQUEST['co_first_name_req']."',
	co_last_name_ask='".$_REQUEST['co_last_name_ask']."',
	co_last_name_req='".$_REQUEST['co_last_name_req']."',
	co_address_ask='".$_REQUEST['co_address_ask']."',
	co_address_req='".$_REQUEST['co_address_req']."',
	co_city_ask='".$_REQUEST['co_city_ask']."',
	co_city_req='".$_REQUEST['co_city_req']."',
	co_state_ask='".$_REQUEST['co_state_ask']."',
	co_state_req='".$_REQUEST['co_state_req']."',
	co_zip_ask='".$_REQUEST['co_zip_ask']."',
	co_zip_req='".$_REQUEST['co_zip_req']."',
	co_country_ask='".$_REQUEST['co_country_ask']."',
	co_country_req='".$_REQUEST['co_country_req']."',
	co_company_ask='".$_REQUEST['co_company_ask']."',
	co_company_req='".$_REQUEST['co_company_req']."',
	co_phone_ask='".$_REQUEST['co_phone_ask']."',
	co_phone_req='".$_REQUEST['co_phone_req']."',
	retype_email='".$_REQUEST['retype_email']."',
	retype_password='".$_REQUEST['retype_password']."'


");
	updateSQL("ms_settings", "email_new_customer='".$_REQUEST['email_new_customer']."' ");
	$_SESSION['sm'] = "Settings updated";
	session_write_close();
	header("location: index.php?do=settings&action=accounts");
	exit();

}
$acc = doSQL("ms_new_accounts", "*", "");
?>

<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> New Account Requirements</div>
<div class="pc">Here you can set what fields to ask for and require when someone creates an account and places an order.</div>
<div>&nbsp;</div>
	<form method="post" name="passdsds" action="index.php">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="accounts">
	<input type="hidden" name="subdo" value="updateNewAccounts">
	<div class="pc"><input type="checkbox" name="email_new_customer" id="email_new_customer" value="1" <?php if($site_setup['email_new_customer'] =="1") { print "checked"; } ?>> <label for="email_new_customer">Email me when someone creates a new account</label></div>

	<div class="pc"><input type="checkbox" name="retype_email" id="retype_email" value="1" <?php if($acc['retype_email'] =="1") { print "checked"; } ?>> <label for="retype_email">Require customers to type in email address twice to confirm it is correct.</label></div>
	<div class="pc"><input type="checkbox" name="retype_password" id="retype_password" value="1" <?php if($acc['retype_password'] =="1") { print "checked"; } ?>> <label for="retype_password">Require customers to type in password twice to confirm it is correct.</label></div>
	<div>&nbsp;</div>
	
	<div class="p50 left">
		<div style="margin-right: 24px;">

		<div class="underlinelabel">Creating A New Account</div>

		<div class="underline">
			<div class="left p25"><h3>First Name</h3></div>
			<div class="left p25"><input type="checkbox" name="first_name_ask" id="first_name_ask" value="1" <?php if($acc['first_name_ask'] == "1") { print "checked"; } ?>> <label for="first_name_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="first_name_req" id="first_name_req" value="1" <?php if($acc['first_name_req'] == "1") { print "checked"; } ?>> <label for="first_name_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Last Name</h3></div>
			<div class="left p25"><input type="checkbox" name="last_name_ask" id="last_name_ask" value="1" <?php if($acc['last_name_ask'] == "1") { print "checked"; } ?>> <label for="last_name_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="last_name_req" id="last_name_req" value="1" <?php if($acc['last_name_req'] == "1") { print "checked"; } ?>> <label for="last_name_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>Address</h3></div>
			<div class="left p25"><input type="checkbox" name="address_ask" id="address_ask" value="1" <?php if($acc['address_ask'] == "1") { print "checked"; } ?>> <label for="address_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="address_req" id="address_req" value="1" <?php if($acc['address_req'] == "1") { print "checked"; } ?>> <label for="address_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>City</h3></div>
			<div class="left p25"><input type="checkbox" name="city_ask" id="city_ask" value="1" <?php if($acc['city_ask'] == "1") { print "checked"; } ?>> <label for="city_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="city_req" id="city_req" value="1" <?php if($acc['city_req'] == "1") { print "checked"; } ?>> <label for="city_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>State</h3></div>
			<div class="left p25"><input type="checkbox" name="state_ask" id="state_ask" value="1" <?php if($acc['state_ask'] == "1") { print "checked"; } ?>> <label for="state_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="state_req" id="state_req" value="1" <?php if($acc['state_req'] == "1") { print "checked"; } ?>> <label for="state_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>Zip Code</h3></div>
			<div class="left p25"><input type="checkbox" name="zip_ask" id="zip_ask" value="1" <?php if($acc['zip_ask'] == "1") { print "checked"; } ?>> <label for="zip_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="zip_req" id="zip_req" value="1" <?php if($acc['zip_req'] == "1") { print "checked"; } ?>> <label for="zip_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Country</h3></div>
			<div class="left p25"><input type="checkbox" name="country_ask" id="country_ask" value="1" <?php if($acc['country_ask'] == "1") { print "checked"; } ?>> <label for="country_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="country_req" id="country_req" value="1" <?php if($acc['country_req'] == "1") { print "checked"; } ?>> <label for="country_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Company</h3></div>
			<div class="left p25"><input type="checkbox" name="company_ask" id="company_ask" value="1" <?php if($acc['company_ask'] == "1") { print "checked"; } ?>> <label for="company_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="company_req" id="company_req" value="1" <?php if($acc['company_req'] == "1") { print "checked"; } ?>> <label for="company_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Phone</h3></div>
			<div class="left p25"><input type="checkbox" name="phone_ask" id="phone_ask" value="1" <?php if($acc['phone_ask'] == "1") { print "checked"; } ?>> <label for="phone_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="phone_req" id="phone_req" value="1" <?php if($acc['phone_req'] == "1") { print "checked"; } ?>> <label for="phone_req">Require</label></div>
			<div class="clear"></div>
		</div>

	</div>
</div>



<div class="p50 left">
	<div style="margin-left: 24px;">
	<div class="underlinelabel">Placing An Order</div>
		<div class="underline">
			<div class="left p25"><h3>First Name</h3></div>
			<div class="left p25"><input type="checkbox" name="co_first_name_ask" id="co_first_name_ask" value="1" <?php if($acc['co_first_name_ask'] == "1") { print "checked"; } ?>> <label for="co_first_name_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_first_name_req" id="co_first_name_req" value="1" <?php if($acc['co_first_name_req'] == "1") { print "checked"; } ?>> <label for="co_first_name_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Last Name</h3></div>
			<div class="left p25"><input type="checkbox" name="co_last_name_ask" id="co_last_name_ask" value="1" <?php if($acc['co_last_name_ask'] == "1") { print "checked"; } ?>> <label for="co_last_name_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_last_name_req" id="co_last_name_req" value="1" <?php if($acc['co_last_name_req'] == "1") { print "checked"; } ?>> <label for="co_last_name_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>Address</h3></div>
			<div class="left p25"><input type="checkbox" name="co_address_ask" id="co_address_ask" value="1" <?php if($acc['co_address_ask'] == "1") { print "checked"; } ?>> <label for="co_address_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_address_req" id="co_address_req" value="1" <?php if($acc['co_address_req'] == "1") { print "checked"; } ?>> <label for="co_address_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>City</h3></div>
			<div class="left p25"><input type="checkbox" name="co_city_ask" id="co_city_ask" value="1" <?php if($acc['co_city_ask'] == "1") { print "checked"; } ?>> <label for="co_city_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_city_req" id="co_city_req" value="1" <?php if($acc['co_city_req'] == "1") { print "checked"; } ?>> <label for="co_city_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>State</h3></div>
			<div class="left p25"><input type="checkbox" name="co_state_ask" id="co_state_ask" value="1" <?php if($acc['co_state_ask'] == "1") { print "checked"; } ?>> <label for="co_state_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_state_req" id="co_state_req" value="1" <?php if($acc['co_state_req'] == "1") { print "checked"; } ?>> <label for="co_state_req">Require</label></div>
			<div class="clear"></div>
		</div>


		<div class="underline">
			<div class="left p25"><h3>Zip Code</h3></div>
			<div class="left p25"><input type="checkbox" name="co_zip_ask" id="co_zip_ask" value="1" <?php if($acc['co_zip_ask'] == "1") { print "checked"; } ?>> <label for="co_zip_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_zip_req" id="co_zip_req" value="1" <?php if($acc['co_zip_req'] == "1") { print "checked"; } ?>> <label for="co_zip_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Country</h3></div>
			<div class="left p25"><input type="checkbox" name="co_country_ask" id="co_country_ask" value="1" <?php if($acc['co_country_ask'] == "1") { print "checked"; } ?>> <label for="co_country_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_country_req" id="co_country_req" value="1" <?php if($acc['co_country_req'] == "1") { print "checked"; } ?>> <label for="co_country_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Company</h3></div>
			<div class="left p25"><input type="checkbox" name="co_company_ask" id="co_company_ask" value="1" <?php if($acc['co_company_ask'] == "1") { print "checked"; } ?>> <label for="co_company_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_company_req" id="co_company_req" value="1" <?php if($acc['co_company_req'] == "1") { print "checked"; } ?>> <label for="co_company_req">Require</label></div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p25"><h3>Phone</h3></div>
			<div class="left p25"><input type="checkbox" name="co_phone_ask" id="co_phone_ask" value="1" <?php if($acc['co_phone_ask'] == "1") { print "checked"; } ?>> <label for="co_phone_ask">Ask</label></div>
			<div class="left p25"><input type="checkbox" name="co_phone_req" id="co_phone_req" value="1" <?php if($acc['co_phone_req'] == "1") { print "checked"; } ?>> <label for="co_phone_req">Require</label></div>
			<div class="clear"></div>
		</div>

	</div>
</div>
<div class="clear"></div>
	<div>&nbsp;</div>
	<div class="row center"><input type="submit" name="submit" value="update" class="submit"></div>



	</form>
	<div>&nbsp;</div>
