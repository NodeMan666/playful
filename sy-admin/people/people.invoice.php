<!-- <div id="pageTitle"><a href="index.php?do=people">People</a> <?php print ai_sep; ?> <?php print $p['p_name']." ".$p['p_last_name']." (".$p['p_email'].")"; ?> <?php print ai_sep; ?> Generate Invoice</div> -->
<script>
function editinvoice() { 
	$("#previewinvoice").slideUp(150, function() { 
		$("#invoiceform").slideDown(150);
	});
	$("#action").val("previewinvoice");
}

function selectship() { 
	if($("#add_shipping").attr("checked")) { 
		$(".shipoption").show();
		$("#order_shipping_option").addClass("required");
	} else { 
		$(".shipoption").hide();
		$("#order_shipping_option").removeClass("required");
	}
}
</script>

<?php if($_REQUEST['action'] == "generateinvoice") { 
	if($_REQUEST['add_shipping'] <=0) { 
		$_REQUEST['order_shipping_option'] = "";
		$_REQUEST['order_shipping'] = "";
	}
	if($_REQUEST['order_payment'] > 0) { 
		$order_payment_status = "Completed";
	}






	$order_id = insertSQL("ms_orders", "order_date='".$_REQUEST['order_date']."', order_due_date='".$_REQUEST['order_due_date']."', order_first_name='".addslashes(stripslashes($_REQUEST['order_first_name']))."', order_business_name='".addslashes(stripslashes($_REQUEST['order_business']))."', order_ship_business='".addslashes(stripslashes($_REQUEST['order_ship_business']))."', order_last_name='".addslashes(stripslashes($_REQUEST['order_last_name']))."', order_address='".addslashes(stripslashes($_REQUEST['order_address']))."', order_state='".addslashes(stripslashes($_REQUEST['order_state']))."', order_city='".addslashes(stripslashes($_REQUEST['order_city']))."', order_zip='".addslashes(stripslashes($_REQUEST['order_zip']))."', 
	order_country='".addslashes(stripslashes($_REQUEST['order_country']))."', 
	 order_ship_first_name='".addslashes(stripslashes($_REQUEST['order_ship_first_name']))."', order_ship_last_name='".addslashes(stripslashes($_REQUEST['order_ship_last_name']))."', order_ship_address='".addslashes(stripslashes($_REQUEST['order_ship_address']))."', order_ship_state='".addslashes(stripslashes($_REQUEST['order_ship_state']))."', order_ship_city='".addslashes(stripslashes($_REQUEST['order_ship_city']))."', order_ship_zip='".addslashes(stripslashes($_REQUEST['order_ship_zip']))."', order_shipping_option='".$_REQUEST['order_shipping_option']."',
	 order_payment='".$_REQUEST['order_payment']."',
	 order_payment_date = '".$_REQUEST['order_payment_date']."',
	 order_payment_reference = '".$_REQUEST['order_payment_reference']."',
	 order_pay_type='".$_REQUEST['order_pay_type']."',
	 order_payment_status = '".$order_payment_status."',
	 order_credit='".$_REQUEST['order_credit']."',
	 order_vat='".$_REQUEST['vat_amount']."', 
	 order_tax='".$_REQUEST['tax_amount']."', order_taxable_amount='".$_REQUEST['taxable_total']."', order_tax_percentage='".$_REQUEST['tax_percentage']."', order_vat_percentage='".$_REQUEST['vat_percentage']."', order_ship_cost='".$_REQUEST['shipping']."', order_total='".$_REQUEST['order_total']."', order_invoice='1', order_customer='".$p['p_id']."', order_email='".addslashes(stripslashes($_REQUEST['order_email']))."' , order_phone='".addslashes(stripslashes($_REQUEST['order_phone']))."' , order_sub_total='".$_REQUEST['order_sub_total']."', order_shipping='".$_REQUEST['shipping']."' ");
	
	foreach($_REQUEST['qty'] AS $x => $qty) { 
		if($qty > 0) { 
			insertSQL("ms_cart", "cart_qty='".$_REQUEST['qty'][$x]."', cart_product_name='".addslashes(stripslashes($_REQUEST['item_name'][$x]))."', cart_price='".$_REQUEST['price'][$x]."', cart_taxable='".$_REQUEST['tax'][$x]."', cart_order='".$order_id."', cart_invoice='1' "); 
		}
	}

	if($_REQUEST['order_credit'] > 0) { 
		insertSQL("ms_credits", "credit_amount='-".$_REQUEST['order_credit']."', credit_customer='".$p['p_id']."', credit_date='".currentdatetime()."', credit_order='".$order_id."' ");
	}
	$_SESSION['sm'] = "Invoice Created";
	header("location: index.php?do=orders&action=viewOrder&orderNum=".$order_id."");
	session_write_close();
	exit();

	?>
<?php } ?>
<?php if($p['p_id'] <=0) { ?>
<div class="error"><span class="the-icons icon-attention"></span>NOTICE: If you are wanting this invoice linked to an account, you should go to the People section and either create an account for the customer or select from an existing account.</div>

<div>&nbsp;</div>
<?php } ?>
<form method="post" name="invoice" action="index.php"   onSubmit="return checkForm('.required');">
<input type="hidden" name="do" value="<?php print $_REQUEST['do'];?>">
<input type="hidden" name="p_id" value="<?php print $p['p_id'];?>">
<input type="hidden" name="view" value="invoice">
<input type="hidden" name="action" id="action" value="<?php if($_REQUEST['action'] == "previewinvoice") { ?>generateinvoice<?php } else { ?>previewinvoice<?php } ?>">

<?php if($_REQUEST['action'] == "previewinvoice") { ?>
<div id="previewinvoice">

	<div class="left p45">
		<div class="pc">&nbsp;</div>
		<?php if(!empty($_REQUEST['order_business'])) { ?>
		<div class="pc"><?php print $_REQUEST['order_business'];?></div>
		<?php } ?>
		<div class="pc"><?php print $_REQUEST['order_first_name'];?> <?php print $_REQUEST['order_last_name'];?></div>
		<div class="pc"><?php print $_REQUEST['order_address'];?></div>
		<div class="pc"><?php print $_REQUEST['order_city'];?>, <?php print $_REQUEST['order_state'];?> <?php print $_REQUEST['order_zip'];?></div>
	</div>

	<div class="right p45 <?php if($_REQUEST['add_shipping'] <= 0) { print "hidden"; } ?>">
		<div class="pc">Ship To</div>
		<?php if(!empty($_REQUEST['order_ship_business'])) { ?>
		<div class="pc"><?php print $_REQUEST['order_ship_business'];?></div>
		<?php } ?>
		<div class="pc"><?php print $_REQUEST['order_ship_first_name'];?> <?php print $_REQUEST['order_ship_last_name'];?></div>
		<div class="pc"><?php print $_REQUEST['order_ship_address'];?></div>
		<div class="pc"><?php print $_REQUEST['order_ship_city'];?>, <?php print $_REQUEST['order_ship_state'];?> <?php print $_REQUEST['order_ship_zip'];?></div>
	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>


	<div class="underlinecolumn">
		<div class="left p60">Item Name</div>
		<div class="left p10">Qty</div>
		<div class="left p20">Price</div>
		<div class="left p10 center">Taxable</div>
		<div class="clear"></div>
	</div>


<?php foreach($_REQUEST['qty'] AS $x => $qty) { 
	if($qty > 0) { 
		$total_lines++;
	?>
	<div class="underline">
		<div class="left p60"><?php print $_REQUEST['item_name'][$x];?></div>
		<div class="left p10"><?php print $_REQUEST['qty'][$x];?></div>
		<div class="left p20"><?php print showPrice($_REQUEST['price'][$x]);?></div>
		<div class="left p10 center"><?php if($_REQUEST['tax'][$x] == "1") { print "Y"; } else { print "N"; } ;?></div>
		<div class="clear"></div>
	</div>
<?php 
	$subtotal = $subtotal + ($_REQUEST['price'][$x] * $_REQUEST['qty'][$x]);
	if($_REQUEST['tax'][$x] > 0) { 
		$taxable = $taxable + ($_REQUEST['price'][$x] * $_REQUEST['qty'][$x]);
	}
	}
} ?>
<input type="hidden" name="order_sub_total" value="<?php print $subtotal;?>">
<div class="pc right text right" style="font-size: 21px;">Sub total: <?php print showPrice($subtotal);?></div>
<div class="clear"></div>
<?php if($_REQUEST['add_shipping'] > 0) { ?>
<div class="pc right text right" style="font-size: 21px;">Shipping (<?php print $_REQUEST['order_shipping_option'];?>): <?php print showPrice($_REQUEST['shipping']);?></div>
<div class="clear"></div>


<?php } ?>

<?php 
	$zip = doSQL("ms_tax_zips", "*", "WHERE zip='".$_REQUEST['order_zip']."' ");
	if($zip['tax'] > 0) { 
		$percent = $zip['tax'];
	} else { 
		$per = doSQL("ms_states", "*", "WHERE state_abr='".$_REQUEST['order_state']."' ");
		$percent = $per['state_tax'];
	}
	$tax = round($taxable * $percent / 100,2);
	$ct = doSQL("ms_countries", "*", "WHERE country_name='".$_REQUEST['order_country']."' ");
	$vat = $taxable * $ct['vat'] / 100;
	?>
	<input type="hidden" name="taxable_total" value="<?php print $taxable;?>">
	<input type="hidden" name="tax_percentage" value="<?php print $percent;?>">

	<div class="pc right text right" style="font-size: 21px;">Tax: <input type="text" name="tax_amount" id="tax_amount" value="<?php print $tax; ?>" size="6" class="textright"  style="font-size: 21px;"></div>
	<div class="clear"></div>
	<?php if(countIt("ms_countries", "WHERE vat>'0' ") > 0) { ?>
	<input type="hidden" name="vat_percentage" value="<?php print $ct['vat'];?>">
	<div class="pc right text right" style="font-size: 21px;">Vat <input type="text" name="vat_amount" id="vat_amount" value="<?php print $vat; ?>" size="6" class="textright"  style="font-size: 21px;"></div>
	<div class="clear"></div>
	<?php } ?>

<?php $total = $subtotal + $_REQUEST['shipping'] + $tax + $vat; ?>


<?php 
if($setup['no_expired_print_credits'] == true) { 
	$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  "); 
} else { 
	$ctotal = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$p['p_id']."'  AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' )  "); 
}
if($ctotal['tot'] > 0) { 
	if($ctotal['tot'] > $total) { 
		$order_credit = $total;
	} else {
		$order_credit = $ctotal['tot'];
	}
	?>

<div class="pc right text right" style="font-size: 21px;">Credit: <input type="text" name="order_credit" id="order_credit" value="<?php print $order_credit; ?>" size="6" class="textright"  style="font-size: 21px;"></div>
<div class="clear"></div>

<?php 
	$total = $total - $order_credit;
} 
?>
<div class="pc right text right" style="font-size: 21px;">Total: <input type="text" name="order_total" id="order_total" value="<?php print number_format($total,2, '.', ''); ?>" size="6" class="textright"  style="font-size: 21px;"></div>
<div class="clear"></div>
<?php if($_REQUEST['order_payment'] <= 0) { 
		$_REQUEST['order_payment'] = "0.00";
	}
	if(empty($_REQUEST['order_payment_date'])) { 
		// $_REQUEST['order_payment_date'] = date('Y-m-d');
	}

	?>

<div class="pc right textright" style="font-size: 21px;">Payment <input type="text" name="order_payment" id="order_payment" value="<?php print $_REQUEST['order_payment'];?>" size="6" class="textright" style="font-size: 21px;"></div>
<div class="clear"></div>
<div class="pc right textright" style="font-size: 21px;">Payment Type <input type="text" name="order_pay_type" id="order_pay_type" value="<?php print $_REQUEST['order_pay_type'];?>" size="12" class="" style="font-size: 21px;"></div>
<div class="clear"></div>
<div class="pc right textright" style="font-size: 21px;">Payment Date <input type="text" name="order_payment_date" id="order_payment_date" value="<?php print $_REQUEST['order_payment_date'];?>" size="12" class=" datepicker" style="font-size: 21px;"></div>
<div class="clear"></div>
<div class="pc right textright" style="font-size: 21px;">Payment Reference <input type="text" name="order_payment_reference" id="order_payment_reference" value="<?php print $_REQUEST['order_payment_reference'];?>" size="12" class="" style="font-size: 21px;"></div>
<div class="clear"></div>


<div class="pc right textright">
<input  type="submit" onclick="editinvoice(); return false;" class="submit" value="Edit">
<input type="submit" name="submit" value="Generate Invoice" class="submit" id="submitButton">
</div>
</div>

<?php } ?>





<div id="invoiceform" <?php if($_REQUEST['action'] == "previewinvoice") { print "class=\"hidden\""; } ?>> 
<?php if(empty($_REQUEST['action'])) { 
	$_REQUEST['order_business'] = $p['p_company'];
	$_REQUEST['order_first_name'] = $p['p_name'];
	$_REQUEST['order_last_name'] = $p['p_last_name'];
	$_REQUEST['order_address'] = $p['p_address1'];
	$_REQUEST['order_city'] = $p['p_city'];
	$_REQUEST['order_state'] = $p['p_state'];
	$_REQUEST['order_zip'] = $p['p_zip'];
	$_REQUEST['order_country'] = $p['p_country'];

	$_REQUEST['order_ship_business'] = $p['p_company'];
	$_REQUEST['order_ship_first_name'] = $p['p_name'];
	$_REQUEST['order_ship_last_name'] = $p['p_last_name'];
	$_REQUEST['order_ship_address'] = $p['p_address1'];
	$_REQUEST['order_ship_city'] = $p['p_city'];
	$_REQUEST['order_ship_state'] = $p['p_state'];
	$_REQUEST['order_ship_zip'] = $p['p_zip'];
	$_REQUEST['order_email'] = $p['p_email'];
	$_REQUEST['order_phone'] = $p['p_phone'];

}
?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>
<?php if(empty($_REQUEST['order_date'])) { 
	$_REQUEST['order_date'] = date('Y-m-d');
}
?>
<script>
function changeinvoiceaddress() { 
	$("#customerinfo").slideToggle(200);
}
</script>
<div class="underline">
	<div class="left" style="margin-right: 24px;">
		<div class="label">Date</div>
		<div><input type="text" id="order_date" name="order_date" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['order_date']));?>"  class="datepicker" title="Must be in YYYY-MM-DD format"></div>
	</div>
	<div class="left" style="margin-right: 24px;">
		<div class="label">Due Date</div>
		<div><input type="text" id="order_due_date" name="order_due_date" size="20" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['order_due_date']));?>"  class="datepicker" title="Must be in YYYY-MM-DD format"> (If you are going to set up a payment schedule, do not enter a due date here.)</div>
	</div>
	<div class="clear"></div>
</div>

<div class="pc"><a href="" onclick="changeinvoiceaddress(); return false;">Change address or add shipping address</a></div>

<div id="customerinfo" class="<?php if($p['p_id'] > 0) { ?>hide<?php } ?>">
	<div class="left p45">

	<div class="pc">Customer Information</div>
		<div class="underline">
			<div class="label">Company</div>
			<div><input type="text" name="order_business" id="order_business"  value="<?php print htmlspecialchars($_REQUEST['order_business']); ?>" class="p90"></div>
		</div>
		<div class="underline">
			<div class="left p50">
			<div class="label">First Name</div>
			<div><input type="text" name="order_first_name" id="order_first_name"  value="<?php print htmlspecialchars($_REQUEST['order_first_name']); ?>" class="p90 required"></div>
			</div>
			<div class="right p50">
			<div class="label">Last Name</div>
			<div><input type="text" name="order_last_name"  value="<?php print htmlspecialchars($_REQUEST['order_last_name']); ?>" class="90"></div>
			</div>

			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="label">Address</div>
			<div><input type="text" name="order_address"  value="<?php print htmlspecialchars($_REQUEST['order_address']); ?>" class="p90"></div>
		</div>
		<div class="underline">
			<div class="left p60">
				<div class="label">City</div>
				<div><input type="text" name="order_city"  value="<?php print htmlspecialchars($_REQUEST['order_city']); ?>" class="p90"></div>
			</div>
			<div class="left p20">
				<div class="label">State</div>
				<div>
				<select name="order_state" id="order_state" class="p95">
				<option value="">Select State</option>
				<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY state_name ASC ");
				while($state = mysqli_fetch_array($states)) { ?>
				<option value="<?php print $state['state_abr'];?>" <?php if($_REQUEST['order_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
				<?php } ?>
				</select>


			</div>
			</div>
			<div class="left p20">
				<div class="label">Zip</div>
				<div><input type="text" name="order_zip"  value="<?php print htmlspecialchars($_REQUEST['order_zip']); ?>" size="6" class="p95"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
				<div class="label">Country</div>
				<div>
				<select name="order_country" id="order_country" class="p95">
				<?php
				$cts = whileSQL("ms_countries", "*", " ORDER BY def DESC, country_name ASC");

				while($ct = mysqli_fetch_array($cts)) {
					print "<option value=\"".$ct['country_name']."\" "; if($_REQUEST['order_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
				}
				print "</select>";
				?>


			</div>
			</div>

				<div class="underline">
					<div class="label">Email Address</div>
					<div><input type="text" name="order_email"  value="<?php print htmlspecialchars($_REQUEST['order_email']); ?>" size="40" class="p95"></div>
				</div>

				<div class="underline">
					<div class="label">Phone</div>
					<div><input type="text" name="order_phone"  value="<?php print htmlspecialchars($_REQUEST['order_phone']); ?>" size="40" class="p95"></div>
				</div>


	</div>



	<div class="right p45">
	<div class="pc">&nbsp;</div>
	<div class="pc">&nbsp;</div>
	<div class="pc"><input type="checkbox" name="add_shipping" id="add_shipping" value="1" onchange="selectship();" <?php if($_REQUEST['add_shipping'] == "1") { print "checked"; } ?>> <label for="add_shipping">Shipping</label></div>
		<div id="shippinginfo" class="<?php if($_REQUEST['add_shipping'] <= 0) { print "hidden"; } ?> shipoption">
		<div class="underline">
			<div class="label">Company</div>
			<div><input type="text" name="order_ship_business" id="order_ship_business"  value="<?php print htmlspecialchars($_REQUEST['order_ship_business']); ?>" class="p90"></div>
		</div>
			<div class="underline">
				<div class="left p50">
				<div class="label">First Name</div>
				<div><input type="text" name="order_ship_first_name"  value="<?php print htmlspecialchars($_REQUEST['order_ship_first_name']); ?>" class="p90"></div>
				</div>
				<div class="right p50">
				<div class="label">Last Name</div>
				<div><input type="text" name="order_ship_last_name"  value="<?php print htmlspecialchars($_REQUEST['order_ship_last_name']); ?>" class="90"></div>
				</div>

				<div class="clear"></div>
			</div>
			<div class="underline">
				<div class="label">Address</div>
				<div><input type="text" name="order_ship_address"  value="<?php print htmlspecialchars($_REQUEST['order_ship_address']); ?>" class="p90"></div>
			</div>
			<div class="underline">
				<div class="left p60">
					<div class="label">City</div>
					<div><input type="text" name="order_ship_city"  value="<?php print htmlspecialchars($_REQUEST['order_ship_city']); ?>" class="p90"></div>
				</div>
				<div class="left p20">
					<div class="label">State</div>
					<div>
					<select name="order_ship_state" id="order_state" class="p95">
					<option value="">Select State</option>
					<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY state_name ASC ");
					while($state = mysqli_fetch_array($states)) { ?>
					<option value="<?php print $state['state_abr'];?>" <?php if($_REQUEST['order_ship_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
					<?php } ?>
					</select>


				</div>
				</div>
				<div class="left p20">
					<div class="label">Zip</div>
					<div><input type="text" name="order_ship_zip"  value="<?php print htmlspecialchars($_REQUEST['order_ship_zip']); ?>" size="6" class="p95"></div>
				</div>

				<div class="clear"></div>
			</div>
		</div>



	</div>
</div>



<div class="clear"></div>
<div>&nbsp;</div>
<div class="underlinecolumn">
	<div class="left p60">Item Name</div>
	<div class="left p10">Qty</div>
	<div class="left p20">Price</div>
	<div class="left p10 center">Taxable</div>
	<div class="clear"></div>
</div>

<?php 
$lines = $total_lines  +5 ;
$x = 1;
while($x <= $lines) { ?>
<div class="underline">
	<div class="left p60"><input type="text" size="20" class="p90 <?php if($x == 1) { print "required"; } ?>"  id="item_name[<?php print $x;?>]" name="item_name[<?php print $x;?>]" value="<?php print htmlspecialchars($_REQUEST['item_name'][$x]);?>"></div>
	<div class="left p10"><input type="text" size="2" class="center line<?php print $x;?>" name="qty[<?php print $x;?>]" value="<?php print $_REQUEST['qty'][$x];?>"></div>
	<div class="left p20"><input type="text" size="8" class="center" name="price[<?php print $x;?>]" value="<?php print htmlspecialchars($_REQUEST['price'][$x]);?>"></div>
	<div class="left p10 center"><input type="checkbox" name="tax[<?php print $x;?>]" value="1" <?php if($_REQUEST['tax'][$x] == "1") { print "checked"; } ?>></div>

	<div class="clear"></div>
</div>
<?php 
	$x++;
}
?>
<div class="pc">If you need to enter in more lines, click Continue then edit.</div>

<div class="p50 right textright shipoption <?php if($_REQUEST['add_shipping'] <=0 ) { print "hidden"; } ?>">
	<div class="pc">Shipping Method</div>
	<div class="pc"><input size="20" type="text" name="order_shipping_option" id="order_shipping_option" size="8" value="<?php print $_REQUEST['order_shipping_option'];?>" class="<?php if($_REQUEST['add_shipping'] == "1") { print "required"; } ?> center"></div>
	<div class="clear"></div>
	<div class="pc">Shipping Price</div>
	<?php if($_REQUEST['shipping'] <=0) { $_REQUEST['shipping'] = "0.00"; } ?>
	<div class="pc"><input type="text" name="shipping" size="8" value="<?php print $_REQUEST['shipping'];?>" class="center"></div>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="pc textright">
	<input type="submit" name="submit" value="Continue" class="submit">
</div>
</div>
</form>
