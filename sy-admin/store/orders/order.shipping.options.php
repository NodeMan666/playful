<?php 
$store = doSQL("ms_store_settings", "*", "");
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".$_REQUEST['order_id']."' "); 
$no_trim = true;
?>

<div id="pageTitle"><a href="index.php?do=orders">Orders</a> <?php print ai_sep;?> Shipped By Options</div>
<div class="pc">These are the shipping options you can select when adding shipping to an order.</div>
<div>&nbsp;</div>

<?php 

if($_REQUEST['subdo'] == "deleteoption") { 
	deleteSQL("ms_shipping_options", "WHERE ship_id='".$_REQUEST['ship_id']."' ","1");
	$_SESSION['sm'] = "Shipping option removed "; 
	header("location: index.php?do=orders&action=shippingoptions");
	session_write_close();
	exit();

}


if($_REQUEST['save'] == "yes") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(empty($_REQUEST['ship_id'])) { 
		insertSQL("ms_shipping_options", "ship_name='".$_REQUEST['ship_name']."', ship_track='".$_REQUEST['ship_track']."' ");
	} else { 
		updateSQL("ms_shipping_options", "ship_name='".$_REQUEST['ship_name']."', ship_track='".$_REQUEST['ship_track']."' WHERE ship_id='".$_REQUEST['ship_id']."' ");
	}
	
	$_SESSION['sm'] = "Shipping option saved "; 
		header("location: index.php?do=orders&action=shippingoptions");
		session_write_close();
		exit();
}
?>
<script>
function getOptionText() { 
	if($("#link_page").val() <= 0) { 
		$("#externallink").show();

	} else { 
		$("#externallink").hide();
		$("#link_text").val($('#link_page option:selected').text());
	}
}

</script>
<div id="roundedFormContain">

<div style="width: 50%; float: left;">
	<div style="padding: 8px;">
	<div class="pc"><h3>Shipping Options</h3></div>
	<div id="roundedForm">
		<?php $ships = whileSQL("ms_shipping_options", "*", "ORDER BY ship_name ASC ");
		while($shipm = mysqli_fetch_array($ships)) { ?>
		<div class="row"><a href="index.php?do=orders&action=shippingoptions&ship_id=<?php print $shipm['ship_id'];?>"><?php print ai_edit;?></a> 
		<a  id="removealllink" class="confirmdelete" confirm-title="Really?" confirm-message="Are you sure you want to delete this?" href="index.php?do=orders&action=shippingoptions&subdo=deleteoption&ship_id=<?php print $shipm['ship_id'];?>"><?php print ai_delete;?></a>

		<h3 style="display: inline;"><?php print $shipm['ship_name'];?></h3><br><?php print $shipm['ship_track'];?></div>
		<?php } ?>
		</div>

	</div>
</div>

<div style="width: 50%; float: left;">
	<?php if(!empty($_REQUEST['ship_id'])) { 
		$ship = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$_REQUEST['ship_id']."' "); 
	}
	?>
	<div style="padding: 8px;">
	<div class="pc"><h3><?php if(empty($_REQUEST['ship_id'])) { print "Add New Shipped By Option"; } else { print "Edit Shipping Option"; } ?></h3></div>
	<form method="post" name="newLink" action="index.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
	<div id="roundedForm">
	<input type="hidden" name="do" value="orders">
	<input type="hidden" name="action" value="shippingoptions">
	<input type="hidden" name="save" value="yes">
	<input type="hidden" name="ship_id" value="<?php print $ship['ship_id'];?>">
	<div class="row">
		<div class="fieldLabel">Name</div>
		<div><input type="text" name="ship_name" id="ship_name" class="field100 required" value="<?php print htmlspecialchars($ship['ship_name']);?>"></div>
	</div>
	<div class="row">
		<div class="fieldLabel">Tracking URL</div>
		<div><input type="text" name="ship_track" id="ship_track" class="field100" value="<?php print htmlspecialchars($ship['ship_track']);?>"></div>
	</div>

	<div class="row center"><input type="submit" name="submit" value="Save" class="submit"></div>
	</div>
	</form>


	</div>
</div>
<div class="clear"></div>
