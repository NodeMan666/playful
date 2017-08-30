<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
/*
$germ = "
Baden-Württemberg|
Bayern|
Berlin|
Brandenburg|
Bremen|
Hamburg|
Hessen|
Mecklenburg-Vorpommern|
Niedersachsen|
Nordrhein-Westfalen|
Rheinland-Pfalz|
Saarland|
Sachsen|
Sachsen-Anhalt|
Schleswig-Holstein|
Thüringen|
";
$states = explode("|",$germ);
foreach($states AS $state) { 
	$state = trim($state);
	if(!empty($state)) { 
		$ck = doSQL("ms_states","*", "WHERE state_name='".utf8_encode($state)."' AND state_country='Germany' ");
		if(empty($ck['state_id'])) { 
			// print "<li> insert: ".utf8_encode($state)."";
			insertSQL("ms_states", "state_name='".utf8_encode($state)."', state_abr='".utf8_encode($state)."',state_country='Germany', state_ship_to='1'  ");
		}
	}
}

*/

$list = "
AG,AGRIGENTO,
ALESSANDRIA,
ANCONA,
AOSTA,
AREZZO,
ASCOLI PICENO,
ASTI,
AVELLINO,
BARI,
BARLETTA-ANDRIA-TRANI,
BELLUNO,
BENEVENTO,
BERGAMO,
BIELLA,
BOLOGNA,
BOLZANO,
BRESCIA,
BRINDISI,
CAGLIARI,
CALTANISSETTA,
CAMPOBASSO,
CARBONIA-IGLESIAS,
CASERTA,
CATANIA,
CATANZARO,
CHIETI,
COMO,
COSENZA,
CREMONA,
CROTONE,
CUNEO,
ENNA,
FERMO,
FERRARA,
FIRENZE,
FOGGIA,
FORLI\' CESENA,
FROSINONE,
GENOVA,
GORIZIA,
GROSSETO,
IMPERIA,
ISERNIA,
L\'AQUILA,
LA SPEZIA,
LATINA,
LECCE,
LECCO,
LIVORNO,
LODI,
LUCCA,
MACERATA,
MANTOVA,
MASSA CARRARA,
MATERA,
MEDIO CAMPIDANO,
MESSINA,
MILANO,
MODENA,
MONZA E DELLA BRIANZA,
NAPOLI,
NOVARA,
NUORO,
OGLIASTRA,
OLBIA-TEMPIO,
ORISTANO,
PADOVA,
PALERMO,
PARMA,
PAVIA,
PERUGIA,
PESARO E URBINO,
PESCARA,
PIACENZA,
PISA,
PISTOIA,
PORDENONE,
POTENZA,
PRATO,
RAGUSA,
RAVENNA,
REGGIO CALABRIA,
REGGIO EMILIA,
RIETI,
RIMINI,
ROMA,
ROVIGO,
SALERNO,
SASSARI,
SAVONA,
SIENA,
SIRACUSA,
SONDRIO,
TARANTO,
TERAMO,
TERNI,
TORINO,
TRAPANI,
TRENTO,
TREVISO,
TRIESTE,
UDINE,
VARESE,
VENEZIA,
VERBANO CUSIO OSSOLA,
VERCELLI,
VERONA,
VIBO VALENTIA,
VICENZA,
VITERBO";

deleteSQL2("ms_states", "WHERE state_country='Italy' ");
$prs = explode(",",$list);
foreach($prs AS $pr) { 
	if(!empty($pr)) { 
		$ck = doSQL("ms_states", "*", "WHERE state_name='".addslashes(stripslashes($pr))."' AND state_country='Italy' ");
		if(empty($ck['state_id'])) { 
			insertSQL("ms_states", "state_name='".addslashes(stripslashes($pr))."', state_country='Italy', state_ship_to='1'  ");
			// print "<li>Added: ".$pr;
		}
	}
}
$prs = explode(",",$list);
foreach($prs AS $pr) { 
	if(!empty($pr)) { 
		$ck = doSQL("ms_states", "*", "WHERE state_name='".$pr."' AND state_country='Italy' ");
		if(!empty($ck['state_id'])) { 
			$pr = trim($pr);
			updateSQL("ms_states", "state_name='".$pr."', state_abr='".$pr."', state_country='Italy', state_ship_to='1'  WHERE state_id='".$ck['state_id']."' ");
			 // print "<li>Added: ".$pr;
		}
	}
}



$states = whileSQL("ms_states", "*", "WHERE state_abr='' ");
while($state = mysqli_fetch_array($states)) { 
	updateSQL("ms_states", "state_abr='".$state['state_name']."' WHERE state_id='".$state['state_id']."' ");
}
$states = whileSQL("ms_states", "*", "WHERE state_abr='' ");
while($state = mysqli_fetch_array($states)) { 
	updateSQL("ms_states", "state_abr='".$state['state_name']."' WHERE state_id='".$state['state_id']."' ");
}

###### Adding states to Brazil ############### 

$list = "
Acre,AC|
Alagoas,AL|
Amapá,AP|
Amazonas,AM|
Bahia,BA|
Ceará,CE|
Distrito Federal,DF|
Espírito Santo,ES|
Goiás,GO|
Maranhão,MA|
Mato Grosso,MT|
Mato Grosso do Sul,MS|
Minas Gerais,MG|
Pará,PA|
Paraíba,PB|
Paraná,PR|
Pernambuco,PE|
Piauí,PI|
Rio de Janeiro,RJ|
Rio Grande do Norte,RN|
Rio Grande do Sul,RS|
Rondônia,RO|
Roraima,RR|
Santa Catarina,SC|
São Paulo,SP|
Sergipe,SE|
Tocantins,TO";

$prs = explode("|",$list);
foreach($prs AS $pr) { 
	if(!empty($pr)) { 
		$add = explode(",",$pr);
		$ck = doSQL("ms_states", "*", "WHERE state_name='".utf8_encode($add[0])."' AND state_country='Brazil' ");
		if(empty($ck['state_id'])) { 
			insertSQL("ms_states", "state_name='".utf8_encode($add[0])."', state_abr='".utf8_encode($add[1])."', state_country='Brazil', state_ship_to='1'  ");
			// print "<li>Added: ".utf8_encode($add[0])." - ".$add[1];
		}
	}
}

###### Adding states to Ireland ############### 
// deleteSQL2("ms_states", "WHERE state_country='Ireland' ");
$list = "
Carlow,
Cavan,
Clare,
Cork,
Donegal,
Dublin,
Galway,
Kerry,
Kildare,
Kilkenny,
Laois,
Leitrim,
Limerick,
Longford,
Louth,
Mayo,
Meath,
Monaghan,
Offaly,
Roscommon,
Sligo,
Tipperary,
Waterford,
Westmeath,
Wexford,
Wicklow";

$prs = explode(",",$list);
foreach($prs AS $pr) { 
	if(!empty($pr)) { 
		$ck = doSQL("ms_states", "*", "WHERE state_name='".utf8_encode($pr)."' AND state_country='Ireland' ");
		if(empty($ck['state_id'])) { 
			 insertSQL("ms_states", "state_name='".utf8_encode($pr)."', state_abr='".utf8_encode($pr)."', state_country='Ireland', state_ship_to='1'  ");
			// print "<li>Added: ".utf8_encode($pr);
		}
	}
}

###### Adding states to Switzerland ############### 
// deleteSQL2("ms_states", "WHERE state_country='Ireland' ");
$list = "
Aargau,
Appenzell Ausserrhoden,
Appenzell Innerrhoden,
Basel-Land,
Basel-Stadt,
Bern,
Fribourg,
Genève,
Glarus,
Graubünden,
Jura,
Luzern,
Neuchâtel,
Nidwalden,
Obwalden,
Schaffhausen,
Schwyz,
Solothurn,
St. Gallen,
Thurgau,
Ticino,
Uri,
Valais,
Vaud,
Zug,
Zurich";

$prs = explode(",",$list);
foreach($prs AS $pr) { 
	if(!empty($pr)) { 
		$ck = doSQL("ms_states", "*", "WHERE state_name='".utf8_encode($pr)."' AND state_country='Switzerland' ");
		if(empty($ck['state_id'])) { 
			 insertSQL("ms_states", "state_name='".utf8_encode($pr)."', state_abr='".utf8_encode($pr)."', state_country='Switzerland', state_ship_to='1'  ");
			// print "<li>Added: ".utf8_encode($pr);
		}
	}
}

if($_REQUEST['subdo'] == "deleteshippinggroup") { 
	$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$_REQUEST['sg_id']."' ");
	deleteSQL("ms_shipping_groups", "WHERE sg_id='".$sg['sg_id']."' ", "1");
	deleteSQL2("ms_shipping_methods", "WHERE method_group='".$sg['sg_id']."' ");
	$_SESSION['sm'] = "Shipping group ".$sg['sg_name']." was deleted";
	session_write_close();
	header("location: index.php?do=settings&action=states");
	exit();

}
if($_REQUEST['deleteShippingMethod'] > 0) { 
	$ship = doSQL("ms_shipping_methods", "*", " WHERE method_id='".$_REQUEST['deleteShippingMethod']. "' ");
	if(!empty($ship['method_id'])) {
		$sql = deleteSQL("ms_shipping_methods", "WHERE method_id='".$ship['method_id']."' ", "1" );
		$sql = "DELETE FROM ms_shipping_prices WHERE price_method='" .$ship['method_id']. "'";
		if(@mysqli_query($dbcon,$sql)) { } else { echo("Error adding > " . mysqli_error($dbcon) . " < that error"); }
		$_SESSION['sm'] = "Shipping method ".$ship['method_name']." deleted";
		session_write_close();
		header("location: index.php?do=settings&action=states");
		exit();
	}
}


if($_REQUEST['submitit'] == "submit") {
	foreach ($_REQUEST['state_add_ship_percent'] AS $id => $order) {
		print "<li>$id - ".$_REQUEST['state_ship_to'][$id]."";
		updateSQL("ms_states", "state_ship_to='".$_REQUEST['state_ship_to'][$id]."', state_add_ship_percent='".$_REQUEST['state_add_ship_percent'][$id]."'  WHERE state_id='$id' ");
	}
	updateSQL("ms_countries", "def='0' ");
	foreach ($_REQUEST['add_price'] AS $id => $order) {
		if($_REQUEST['def'] == "$id") { 
			$and_def = ", def='1' ";
		}
		print "<li>$id - ".$_REQUEST['state_ship_to'][$id]."";
		updateSQL("ms_countries", "ship_to='".$_REQUEST['ship_to'][$id]."', add_price='".$_REQUEST['add_price'][$id]."' $and_def  WHERE country_id='$id' ");
		$and_def = "";
	}
	updateSQL("ms_store_settings", "ship_only_billing_message='".$_REQUEST['ship_only_billing_message']."', ship_only_billing='".$_REQUEST['ship_only_billing']."', ship_mail_billing_default='".$_REQUEST['ship_mail_billing_default']."', shipping_discount='".$_REQUEST['shipping_discount']."', ship_group_extra_shipping_charge='".$_REQUEST['ship_group_extra_shipping_charge']."' ");

		$_SESSION['sm'] = "Settings updated";
		header ("Location: index.php?do=settings&action=states&country=".$_REQUEST['country']."");
		exit();
	}


if($_REQUEST['subdo'] == "enableallcountries") { 
	updateSQL("ms_countries", "ship_to='1' ");
	$_SESSION['sm'] = "All countries enabled";
	header ("Location: index.php?do=settings&action=states");
	exit();
}
if($_REQUEST['subdo'] == "disableallcountries") { 
	updateSQL("ms_countries", "ship_to='0' ");
	$_SESSION['sm'] = "All countries disabled";
	header ("Location: index.php?do=settings&action=states");
	exit();
}


if($_REQUEST['type'] == "zip") {
	require $setup['admin_folder']."/upload.tax.form.php";
} elseif($_REQUEST['type'] == "addStates") {
	require $setup['admin_folder']."/settings/addstates.php";

} else {


?>

<script>
function editshipping(method_id) { 
	pagewindowedit("w-shipping-edit.php?method_id="+method_id+"&method_group="+$("#sg_id").val()+"&noclose=1&nofonts=1&nojs=1");
}
function editshippinggroup(sg_id) { 
	pagewindowedit("w-shipping-group.php?sg_id="+sg_id+"&noclose=1&nofonts=1&nojs=1");
}

</script>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> <?php print ai_sep;?> Shipping</div>

<div style="width: 30%; float: left;">
<div class="pc">Below are your available shipping methods your customers can select from.</div>
<div class="pc"><a href="" onclick="editshippinggroup('0'); return false;">Create New Shipping Group</a></div>

<?php 
if(empty($_REQUEST['sg_id'])) { 
	$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_default='1' ");
} else { 
	$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$_REQUEST['sg_id']."' ");
}
?>

<div class="pc">
<form method="GET" name="sg" action="index.php">
<input type="hidden" name="do" value="settings">
<input type="hidden" name="action" value="states">
Shipping Group<br>
<select name="sg_id" id="sg_id" onchange='this.form.submit()' class="inputtitle">
<?php 
$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
while($group = mysqli_fetch_array($groups)) { 
	?>
	<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $sg['sg_id']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
	<?php 
}
?>
</select> <?php if($sg['sg_default'] !== "1") { ?><a href="" onclick="editshippinggroup('<?php print $sg['sg_id'];?>'); return false;">edit</a>  &nbsp;  <a  id="removealllink" class="confirmdelete" confirm-title="Really?" confirm-message="Are you sure you want to delete this? This will delete this shipping group and shipping methods included." href="index.php?do=<?php print $_REQUEST['do'];?>&action=states&subdo=deleteshippinggroup&sg_id=<?php print $sg['sg_id'];?>" >delete</a>
   <?php } ?>
</form>
</div>
<div class="pc"><a href="" onclick="editshipping('0'); return false;">Add New Shipping Method</a></div>

<?php 
$ships = whileSQL("ms_shipping_methods", "*", "WHERE method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
if(mysqli_num_rows($ships) <= 0 ) { ?><div class="error">No shippping methods added to <?php print $sg['sg_name'];?>.</div><?php } ?>
<?php 
while($ship = mysqli_fetch_array($ships)) { ?>
<div id="">
	<div class="underline">
		<div >
			<a href="" onclick="editshipping('<?php print $ship['method_id'];?>'); return false;"><?php print ai_edit;?></a> <a href="index.php?do=settings&action=states&deleteShippingMethod=<?php print $ship['method_id'];?>"  onClick="return confirm('Are you sure you want to delete this?');"><?php print ai_delete;?></a> <a href="" onclick="editshipping('<?php print $ship['method_id'];?>'); return false;"><h3 style="display: inline;"><?php print $ship['method_name'];?></h3></a>
			<?php if($ship['method_status'] <=0) { ?><span class="inactive">Inactive</span><?php } ?>

		</div>

		<div>
		<?php 
		$prices = whileSQL("ms_shipping_prices", "*", "WHERE price_method='".$ship['method_id']."' ORDER BY price_amount ASC ");
		while($price = mysqli_fetch_array($prices)) {
			print "<div class=\"pc\">".showPrice("".$price['price_amount']."")." for orders ".$price['price_from']." - ".$price['price_to']."</div>";
		}
		?>
		</div>
	<div class="clear"></div>
</div>

<?php if(!empty($ship['method_descr'])) { ?><div class="row"><?php print $ship['method_descr'];?></div><?php } ?>
</div>

<?php } ?>
<div>&nbsp;</div>
<form method="post" name="states" action="index.php">
<input type="hidden" name="do" value="settings">
<input type="hidden" name="action" value="states">
<input type="hidden" name="submitit" value="submit">

<div class="pc"><h3>Options</h3></div>
<div id="">
<div class="underline">
	<div class="fieldLabel">Calculate shipping before or after any discounts</div>
	<div><input type="radio" name="shipping_discount" id="shipping_discount" value="before" <?php if($store['shipping_discount'] == "before") { print "checked"; } ?>> Before Discounts &nbsp; &nbsp; 
	<input type="radio" name="shipping_discount" id="shipping_discount" value="after" <?php if($store['shipping_discount'] == "after") { print "checked"; } ?>> After Discounts
	</div>
</div>

<div class="underline"><input type="checkbox" name="ship_group_extra_shipping_charge" value="1" <?php if($store['ship_group_extra_shipping_charge'] == "1") { print "checked"; } ?>> <b>Group extra shipping charges for products</b>.<br>Selecting this option, if you have extra shipping charges set for a product and the customer orders more than 1 quantity to only add the extra shipping charge for that product once.</div>

<div class="underline"><input type="checkbox" name="ship_only_billing" value="1" <?php if($store['ship_only_billing'] == "1") { print "checked"; } ?>> Only shipping to billing address</div>
<div class="underline">
<div>Message about only shipping to billing address</div>
<div><textarea name="ship_only_billing_message" rows="2" cols="30" class="field100"><?php print $store['ship_only_billing_message'];?></textarea></div>
</div>
<!-- <div class="row"><input type="checkbox" name="ship_mail_billing_default" value="1" <?php if($store['ship_mail_billing_default'] == "1") { print "checked"; } ?>> Auto check ship to billing address.</div> -->
</div>


</div>

<div style="width: 68%; float: right;">
<div class="right textright">
<input type="submit" name="submit" value="Update Countries & States" class="submit"  id="submitButton">
</div>

	<div style="width: 49%; float: left;">
	<div class="pc"><a href="index.php?do=settings&action=states&subdo=enableallcountries">Enable All Countries</a> &nbsp; <a href="index.php?do=settings&action=states&subdo=disableallcountries">Disable All Countries</a></div>
	<div class="pc">
	<div style="width: 15%; float: left;">Ship To</div>
	<div style="width: 15%; float: left;">Default</div>
	<div style="width: 40%; float: left;">Country</div>
	<div style="width: 30%; float: left; text-align: right;">+ Shipping %</div>
	<div class="clear"></div>
</div>

<div id="" style="overflow-y: scroll; height: 500px;">
<?php 
$countries = whileSQL("ms_countries", "*","ORDER BY def DESC, country_name ASC");
while($country = mysqli_fetch_array($countries)) { ?>
<div class="underline">
	<div style="width: 15%; float: left;"><input type="checkbox" name="ship_to[<?php print $country['country_id'];?>]" value="1" <?php if($country['ship_to'] == "1") { print "checked"; } ?>></div>
	<div style="width: 15%; float: left;"><input type="radio" name="def" value="<?php print $country['country_id'];?>" <?php if($country['def'] == "1") { print "checked"; } ?>></div>
	<div style="width: 40%; float: left;"><a href="index.php?do=settings&action=states&country=<?php print $country['country_id'];?>"><?php print $country['country_name'];?></a></div>
	<div style="width: 30%; float: left; text-align: right;"><input size="4" type="text" name="add_price[<?php print $country['country_id'];?>]" id="add_price" value="<?php print $country['add_price'];?>">%</div>

	<div class="clear"></div>
</div>
<?php } ?>
</div>






	</div>
	<div style="width: 49%; float: right;">
	<div>&nbsp;</div>
<div class="pc">
	<div style="width: 15%; float: left;">Ship To</div>
	<div style="width: 45%; float: left;">State</div>
	<div style="width: 40%; float: left; text-align: right;">+ Shipping %</div>
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
<div class="row center">No states available for <?php print $country['country_name'];?></div>
<?php } ?>
<?php 
while($state = mysqli_fetch_array($states)) { ?>
<div class="underline">
	<div style="width: 15%; float: left;"><input type="checkbox" name="state_ship_to[<?php print $state['state_id'];?>]" value="1" <?php if($state['state_ship_to'] == "1") { print "checked"; } ?>></div>
	<div style="width: 45%; float: left;"><?php print $state['state_name'];?></div>
	<div style="width: 40%; float: left; text-align: right;"><input size="4" type="text" name="state_add_ship_percent[<?php print $state['state_id'];?>]" id="state_add_ship_percent" value="<?php print $state['state_add_ship_percent'];?>">%</div>

	<div class="clear"></div>
</div>
<?php } ?>
</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<div>&nbsp;</div>
<div class="pc">
<input type="submit" name="submit" value="Save" class="submit">
</div>
</form>

<div class="clear"></div>



<?php  } ?>
