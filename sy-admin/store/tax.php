<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 

if($_REQUEST['submitit'] == "submit") {
	foreach ($_REQUEST['state_tax'] AS $id => $order) {
		print "<li>$id - ".$_REQUEST['state_ship_to'][$id]."";
		updateSQL("ms_states", "state_tax='".$_REQUEST['state_tax'][$id]."' WHERE state_id='$id' ");
	}
	foreach ($_REQUEST['vat'] AS $id => $order) {
		print "<li>$id - ".$_REQUEST['state_ship_to'][$id]."";
		updateSQL("ms_countries", "vat='".$_REQUEST['vat'][$id]."'  WHERE country_id='$id' ");
		$and_def = "";
	}
	updateSQL("ms_settings", "include_vat='".$_REQUEST['include_vat']."', download_tax='".$_REQUEST['download_tax']."' ");

	updateSQL("ms_store_settings", "
	tax_discount = '".$_REQUEST['tax_discount']."',
	tax_shipping = '".$_REQUEST['tax_shipping']."',
	tax_address = '".$_REQUEST['tax_address']."',
	pickup_tax_rate='".$_REQUEST['pickup_tax_rate']."' 
	");

	$_SESSION['sm'] = "Taxes updated";
		header ("Location: index.php?do=settings&action=tax&country=".$_REQUEST['country']."");
		exit();
	}
if($_REQUEST['type'] == "zip") {
	require $setup['admin_folder']."tax.zip.codes.php";
} elseif($_REQUEST['type'] == "addStates") {
	require $setup['admin_folder']."/settings/addstates.php";

} else {


?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Tax</div>
<div class="clear"></div>


<form method="post" name="states" action="index.php">

<div style="width: 30%; float: left;">
	<div class="underlinelabel">Tax Options</div>
	<div class="underline">
		<div class="label">Calculate tax on billing or shipping address</div>
		<div><input type="radio" name="tax_address" id="tax_address1" value="billing" <?php if($store['tax_address'] == "billing") { print "checked"; } ?>> <label for="tax_address1">Billing Address</label> &nbsp; &nbsp; 
		<input type="radio" name="tax_address" id="tax_address2" value="shipping" <?php if($store['tax_address'] == "shipping") { print "checked"; } ?>> <label for="tax_address2">Shipping Address</label>
		</div>
	</div>
	<div>&nbsp;</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="tax_shipping" id="tax_shipping" value="1" <?php if($store['tax_shipping'] == "1") { print "checked"; } ?>> <label for="tax_shipping">Include shipping charges in tax calculation</label></div>
	</div>
	<div>&nbsp;</div>

	<div class="underline">
		<div class="label">Calculate tax before or after any discounts</div>
		<div><input type="radio" name="tax_discount" id="tax_discount1" value="before" <?php if($store['tax_discount'] == "before") { print "checked"; } ?>> <label for="tax_discount1">Before Discounts</label> &nbsp; &nbsp; 
		<input type="radio" name="tax_discount" id="tax_discount2" value="after" <?php if($store['tax_discount'] == "after") { print "checked"; } ?>> <label for="tax_discount2">After Discounts</label>
		</div>
	</div>
	<div>&nbsp;</div>

	<div class="underline">
		<div class="label">Tax rate for all pickup orders</div>
		<div><input type="text" name="pickup_tax_rate" id="pickup_tax_rate" value="<?php print $store['pickup_tax_rate'];?>" size="6" class="center">%</div>
		<div>If you want to charge a tax rate on all orders placed that will be picked up, enter in the percentage amount here. This percentage will be charged when they select a pickup option from the <a href="index.php?do=settings&action=states">shipping options</a> and the option " This option is for a pick up " must be checked for that shipping option.</div>
	</div>

	<div>&nbsp;</div>
</div>



<div style="width: 69%; float: right;">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="tax">
	<input type="hidden" name="submitit" value="submit">
	<div style="width: 49%; float: left;">

	<div class="underlinelabel">
		<div style="width: 50%; float: left;">State Tax</div>
		<div style="width: 50%; float: left; text-align: right;">Tax %</div>
		<div class="clear"></div>
	</div>

	<div id="" style="overflow-y: scroll; height: 500px;">
	<?php 
	if($_REQUEST['country'] <=0) { 
		$country = doSQL("ms_countries", "*", "WHERE def='1' ");
	} else { 
		$country = doSQL("ms_countries", "*", "WHERE country_id='".$_REQUEST['country']."' ");
	}
	$states = whileSQL("ms_states", "*","WHERE state_country='".$country['country_name']."' ORDER BY state_name ASC ");
	if(mysqli_num_rows($states) <=0) { ?>
	<div class="underline center">No states available for <?php print $country['country_name'];?></div>
	<?php } ?>
	<?php 
	while($state = mysqli_fetch_array($states)) { ?>
	<div class="underline">
		<div style="width: 50%; float: left;"><?php print $state['state_name'];?></div>
		<div style="width: 50%; float: left; text-align: right;"><input size="4" type="text" name="state_tax[<?php print $state['state_id'];?>]" id="state_tax" value="<?php print $state['state_tax'];?>">%</div>

		<div class="clear"></div>
	</div>
	<?php } ?>
	</div>

	</div>


	<div style="width: 49%; float: right;">
		<div class="underlinelabel">
		<div style="width: 50%; float: left;">VAT</div>
		<div style="width: 50%; float: left; text-align: right;">VAT %</div>
			<div class="clear"></div>
		</div>

		<div id="" style="overflow-y: scroll; height: 500px;">
		<?php 
		$countries = whileSQL("ms_countries", "*","ORDER BY def DESC, country_name ASC");
		while($country = mysqli_fetch_array($countries)) { ?>
		<div class="underline">
			<div style="width: 50%; float: left;"><a href="index.php?do=settings&action=tax&country=<?php print $country['country_id'];?>"><?php print $country['country_name'];?></a></div>
			<div style="width: 50%; float: left; text-align: right;"><input size="4" type="text" name="vat[<?php print $country['country_id'];?>]" id="vat" value="<?php print $country['vat'];?>">%</div>

			<div class="clear"></div>
		</div>
		<?php } ?>
		</div>

	</div>
</div>

<div>&nbsp;</div>
<!--
<div class="pc left">
<div class="pc">Out of state download tax</div>
<div>
<input type="text" name="download_tax" id="download_tax" value="<?php print $site_setup['download_tax'];?>" size="6" class="center">%
</div>
</div>
-->
<div class="clear"></div>


<div class="clear"></div>
</div>
<div>&nbsp;</div>
 <div class="pc center"><i>Note: enter in your tax percentage in 8.7500 format.</i></div>
<div>&nbsp;</div>
<div class="pc center"><input type="checkbox" name="include_vat" id="include_vat" value="1" <?php if($site_setup['include_vat'] == "1") { print "checked"; } ?>> <label for="include_vat">Show prices including VAT</label></div> 
<div class="pc center"><input type="submit" name="submit" value="Update Tax" class="submit"></div>
</form>

<div class="clear"></div>

<div style="max-width: 600px; width: 100%; margin: auto;">
	<div class="underlinelabel">Tax by zip codes</div>
	<?php if(countIt("ms_tax_zips", "") > 0) { ?>
		<div id="" style="overflow-y: scroll; height: 500px;">
		<?php 
		$zips = whileSQL("ms_tax_zips", "*","ORDER BY zip ASC");
		while($zip = mysqli_fetch_array($zips)) { ?>
		<div class="underline">
			<div style="width: 20%; float: left;"><?php print $zip['zip'];?></div>
			<div style="width: 40%; float: left; "><?php print $zip['city'];?>&nbsp;</div>
			<div style="width: 20%; float: left;"><?php print $zip['state'];?>&nbsp;</div>
			<div style="width: 20%; float: left; text-align: right;"><?php print $zip['tax'];?>%</div>

			<div class="clear"></div>
		</div>
		<?php } ?>
		</div>
	<?php } ?>
	<form name="upitprev" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data"   onSubmit="return checkForm();">
	<div class="pc">
	<div class="left"><input type="file" name="image" id="image" class="required" size="20" id="image" ></div>
	<div class="right textright">
	<?php  print "<input type=\"hidden\" name=\"do\" value=\"settings\">"; ?>
	<?php  print "<input type=\"hidden\" name=\"action\" value=\"tax\">"; ?>
	<?php  print "<input type=\"hidden\" name=\"type\" value=\"zip\">"; ?>
	<input type="hidden" name="upload" value="yes">
	<input type="submit" name="submit" id="submit"  value="Upload CSV file" class="submit">
	</div>
	<div class="clear"></div>
	</div>
	</form>
	<div>&nbsp;</div>
	<div class="pc">
	<h3>About tax by zip code</h3>
	This section allows you to charge tax by zip codes. 
	<br><br>
	1) You need a spreadsheet with at least the zip codes and tax rate. All possible options are Zip Code, Tax Rate, City, and State, but only zip code and tax rate are used.
	<br><br>
	2) Each column must be labeled (first row of the spreadsheet) as: zip, tax, city, state. It doesn't matter what order or if there are other columns.
	<br><br>
	3) Save your spread sheet as a CSV file.
	<br><br>
	4) Click the browse button to find the spreadsheet on your computer then click the upload CSV file.
	<br><br>
	If the zip code does not exist in the database, it will add it. If it does exist, then it will update it.
	<br><br>
	If a customer has a zip code that doesn't exist in the database, then it will use the tax percentage you have set for your state.


	</div>
	</div>
<?php  } ?>
