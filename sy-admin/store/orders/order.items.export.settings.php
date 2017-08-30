<h1>Order Items Export Settings</h1>
<div class="pc">Select which fields you want on the export. You can drag and drop to arrange the order.</a>.</div>

<?php if($_REQUEST['subdo'] == "save") { 
	foreach($_REQUEST['field_name'] AS $id => $val) {
	//	print "<li>$id - fs='".$_REQUEST['fs'][$id]."' ".$_REQUEST['fn'][$id]." ";
		updateSQL("ms_order_export_items", "label='".addslashes(stripslashes(trim($_REQUEST['label'][$id])))."' , status='".$_REQUEST['status'][$id]."' , separate_products='".addslashes(stripslashes($_REQUEST['separate_products'][$id]))."' , product_format='".addslashes(stripslashes($_REQUEST['product_format'][$id]))."' , strip_ext='".addslashes(stripslashes($_REQUEST['strip_ext'][$id]))."'  WHERE field_name='".$_REQUEST['field_name'][$id]."' ");  
	//	print "<li>".$_REQUEST['fn'][$id]."  ".$_REQUEST['fl'][$id];
	//	print "<li>$id => $val";
	}
	updateSQL("ms_settings", "export_ignore_downloads='".$_REQUEST['export_ignore_downloads']."' ");
	// print "<pre>"; print_r($_REQUEST); 
	$_SESSION['sm'] = "Settings Saved";
	header("location: index.php?do=orders&action=itemsexportsettings");
	session_write_close();
	exit();
}
if($_REQUEST['subdo'] == "addfield") { 
	$last = doSQL("ms_order_export_items", "*", "ORDER BY display_order DESC ");
	$display_order = $last['display_order'] + 1;
	insertSQL("ms_order_export_items","field_name='".$_REQUEST['add_field']."', label='".addslashes(stripslashes(trim($_REQUEST['add_field_label'])))."', function='fieldname', status='1' , display_order='".$display_order."' ");
	$_SESSION['sm'] = "Field Added";
	header("location: index.php?do=orders&action=itemsexportsettings");
	session_write_close();
	exit();
}
$order_fields = array("order_id","order_date","order_total","order_email","order_first_name","order_last_name","order_address","order_address_2","order_city","order_state","order_zip","order_country","order_ip","order_shipping","order_tax","order_ship_first_name","order_ship_last_name","order_ship_address","order_ship_addres_2","order_ship_city","order_ship_state","order_ship_zip","order_ship_country","order_phone","order_fees","order_pay_type","order_payment_status","order_business_name","order_ship_business","order_discount","order_shipping_option","order_sub_total","order_coupon_name","order_tax_percentage","order_taxable_amount","order_shipped_by","order_shipped_date","order_shipped_track","order_vat","order_vat_percentage","order_admin_notes","order_payment","order_payment_date","order_credit","order_notes","order_eb_discount","order_extra_val_1","order_extra_val_2","order_extra_val_3","order_extra_val_4","order_extra_val_5");

$existing_fields = array();
$fields = whileSQL("ms_order_export_items", "*", "ORDER BY display_order ASC ");
while($f = mysqli_fetch_array($fields)) { 
	array_push($existing_fields,$f['field_name']);
}
?>
<div class="right textright">
	<div class="pc">Add additional  fields from the orders table below to be exported.</div>
	<div>
	<form method="post" name="or" id="or" action="index.php"   onSubmit="return checkForm('.optrequired');">
	<input type="hidden" name="do" value="orders">
	<input type="hidden" name="action" value="itemsexportsettings">
	<input type="hidden" name="subdo" value="addfield">
	<div class="left" style="margin-right: 16px;">
		<div>Field</div>
		<div>
		<select name="add_field" id="add_field">
		<?php
		foreach($order_fields AS $field) { 
			if(!in_array($field,$existing_fields)) { ?>
		<option value="<?php print $field;?>"><?php print $field;?></option>
		<?php
			}
		} ?>
		</select> 
		</div>
	</div>
	<div class="left" style="margin-right: 16px;">
		<div>Label Name</div>
		<div>
		<input type="text" name="add_field_label" id="add_field_label" size="12" class="optrequired">
		</div>
	</div>
	<div class="left" style="margin-right: 16px;">
		<div>&nbsp;</div>
		<div>
		<input type="submit" name="submit" id="submit" value="Add" class="submitSmall">
		</div>
	</div>
	<div class="clear"></div>
	</form>
	</div>
</div>
<div class="clear"></div>
<div>&nbsp;</div>

<script>
jQuery(document).ready(function() {
	sortItems('sortable-list','sort_order','orderItemsExport');
});
</script>

<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$fields = whileSQL("ms_order_export_items", "*", "ORDER BY display_order ASC ");
while($f = mysqli_fetch_array($fields)) { 
	$order[] = $f['id'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>


<form method="post" name="or" id="or" action="index.php">
<input type="hidden" name="do" value="orders">
<input type="hidden" name="action" value="itemsexportsettings">
<input type="hidden" name="subdo" value="save">
<div class="underlinelabel">
	<div class="p5 left">&nbsp;</div>
	<div class="p5 left">Status</div>
	<div class="p20 left">Database Field</div>
	<div class="p20 left">Label</div>
	<div class="clear"></div>
</div>

<ul id="sortable-list" class="sortable-list">

<?php 
$x = 0;
$fields = whileSQL("ms_order_export_items", "*", "ORDER BY display_order ASC ");
while($f = mysqli_fetch_array($fields)) { 
	?>

<li title="<?php print $f['id'];?>"><div class="underline">
	<div class="p5 left"><?php print ai_sort;?></div>
	<div class="p5 left"><input type="checkbox" name="status[<?php print $x;?>]" value="1" <?php if($f['status'] == "1") { print "checked"; } ?>></div>

	<div class="p20 left">
	<?php if($f['field_name'] == "products") { 
		print "Product Names";
	} else if($f['field_name'] == "productskus") { 
		print "Product SKUs (your reference name option) ";		
	} else if($f['field_name'] == "options") { 
		print "Product Options";		
	} else { 
		print $f['field_name'];
	}
	?></div>
	<div class="p20 left"><input type="text" name="label[<?php print $x;?>]" value="<?php print $f['label']?>" class="field100"></div>
	<div class="p25 left">
	<?php if(($f['field_name'] == "products") || ($f['field_name'] == "productskus") == true) { ?>
	Separate products with: <input type="text" name="separate_products[<?php print $x;?>]" value="<?php print $f['separate_products']?>" size="3" class="center">
	<?php } else { ?>
	&nbsp;
	<?php } ?>
	</div>
	<div class="p25 left">
	<?php if(($f['field_name'] == "products") || ($f['field_name'] == "productskus") == true) { ?>
	Format: <input type="text" name="product_format[<?php print $x;?>]" value="<?php print $f['product_format']?>" size="20" class="center">
	<?php } else if($f['function'] == "getfilename") { ?>
	<input type="checkbox" name="strip_ext[<?php print $x;?>]" id="strip_ext_<?php print $x;?>" value="1" <?php if($f['strip_ext'] == "1") { print "checked"; } ?>> <label for="strip_ext_<?php print $x;?>">Remove file extension</label>
	<?php } else { ?>
	&nbsp;
	<?php } ?>
	</div>
	<input type="hidden" name="field_name[<?php print $x;?>]" value="<?php print $f['field_name'];?>">
	<div class="clear"></div>
</div></li>
<?php
$x++;	
} ?>

</ul>
<!-- <div class="underlinelabel"><input type="checkbox" name="export_ignore_downloads" id="export_ignore_downloads" value="1" <?php if($site_setup['export_ignore_downloads'] == "1") { ?>checked<?php } ?>> <label for="export_ignore_downloads">Ignore download products in export</label></div> --> 
<div class="underlinespacer"><input type="submit" name="submit" value="Save Changes" class="submit"></div>
</form>

<div class="underlinespacer">
<p>For the Format, [QTY] shows the quantity of the product and [PRODUCT] displays the product name. Those both need to be in the format field.</p>
<p>If you have created additional fields at checkout in your price list, you can select the order_extra_val_1, order_extra_val_2, etc... from the field list to display those values in the export.</p>
</div>
