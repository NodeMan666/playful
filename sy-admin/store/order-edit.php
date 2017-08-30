<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$('#order_email').change(function() {
	checkemail();
});

function checkemail() { 
	$.get("admin.actions.php?action=checkemail&order_email="+$("#order_email").val()+"&order_id="+$("#order_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The email address "+$("#order_email").val()+" already exists for another account. Search for this account or enter in a different email address.");
			$("#order_email").val("")
		}
	});
}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#order_first_name").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 

	updateSQL("ms_orders", "
	order_date='".addslashes(stripslashes($_REQUEST['order_date']))."' , 
	order_due_date='".addslashes(stripslashes($_REQUEST['order_due_date']))."' , 
	order_first_name='".addslashes(stripslashes($_REQUEST['order_first_name']))."' , 
	order_last_name='".addslashes(stripslashes($_REQUEST['order_last_name']))."', 
	order_email='".addslashes(stripslashes($_REQUEST['order_email']))."',
	order_phone='".addslashes(stripslashes($_REQUEST['order_phone']))."',
	order_city='".addslashes(stripslashes($_REQUEST['order_city']))."',
	order_address='".addslashes(stripslashes($_REQUEST['order_address']))."',
	order_state='".addslashes(stripslashes($_REQUEST['order_state']))."',
	order_country='".addslashes(stripslashes($_REQUEST['order_country']))."',
	order_zip='".addslashes(stripslashes($_REQUEST['order_zip']))."', 

	order_ship_first_name='".addslashes(stripslashes($_REQUEST['order_ship_first_name']))."' , 
	order_ship_last_name='".addslashes(stripslashes($_REQUEST['order_ship_last_name']))."', 
	order_ship_city='".addslashes(stripslashes($_REQUEST['order_ship_city']))."',
	order_ship_address='".addslashes(stripslashes($_REQUEST['order_ship_address']))."',
	order_ship_state='".addslashes(stripslashes($_REQUEST['order_ship_state']))."',
	order_ship_country='".addslashes(stripslashes($_REQUEST['order_ship_country']))."',
	order_ship_zip='".addslashes(stripslashes($_REQUEST['order_ship_zip']))."', 


	order_shipping='".addslashes(stripslashes($_REQUEST['order_shipping']))."', 
	order_shipping_option='".addslashes(stripslashes($_REQUEST['order_shipping_option']))."', 
	
	order_sub_total='".addslashes(stripslashes($_REQUEST['order_sub_total']))."', 
	order_discount='".addslashes(stripslashes($_REQUEST['order_discount']))."', 
	order_coupon_name='".addslashes(stripslashes($_REQUEST['order_coupon_name']))."', 
	order_payment='".addslashes(stripslashes($_REQUEST['order_payment']))."' ,
	order_credit='".addslashes(stripslashes($_REQUEST['order_credit']))."' ,
	order_gift_certificate='".addslashes(stripslashes($_REQUEST['order_gift_certificate']))."' ,
	order_tax='".addslashes(stripslashes($_REQUEST['order_tax']))."' ,
	order_vat='".addslashes(stripslashes($_REQUEST['order_vat']))."',
	order_total='".addslashes(stripslashes($_REQUEST['order_total']))."',
	order_aff='".addslashes(stripslashes($_REQUEST['order_aff']))."',
	order_aff_perc='".addslashes(stripslashes($_REQUEST['order_aff_perc']))."'

	WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Account Saved";
	$order_id=$_REQUEST['order_id'];


	header("location: ../index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']."");
	session_write_close();
	exit();
}
?>

<script>
function selectsection(id) { 
	$("#"+id).slideToggle(200);
}
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>

<?php
	if(($_REQUEST['order_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' "); 
		if(empty($order['order_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($order AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<div class="pc"><h1>Edit <?php if($order['order_invoice'] == "1") { ?>Invoice<?php } else { ?>Order<?php } ?> #<?php print $order['order_id'];?></h1>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div class="underlinelabel"  onclick="selectsection('billing');"  style="cursor: pointer;"><span class="the-icons icon-pencil"></span><span>Billing Address</span></div>
	<div id="billing" class="hidden">
		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Email Address</div>
				<div><input type="text" name="order_email" id="order_email" class="optrequired field100" value="<?php print $order['order_email'];?>" tabindex="3"></div>
			</div>
		</div>
		<div class="clear"></div>
		
		
		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">First Name</div>
				<div><input type="text" name="order_first_name" id="order_first_name" class="optrequired field100" value="<?php print $order['order_first_name'];?>" tabindex="1"></div>
			</div>
		</div>
		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Last Name</div>
				<div><input type="text" name="order_last_name" id="order_last_name" class="optrequired field100" value="<?php print $order['order_last_name'];?>" tabindex="2"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>


		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Address</div>
				<div><input type="text" name="order_address" id="order_address" class="field100" value="<?php print $order['order_address'];?>"  tabindex="5"></div>
			</div>

			<div class="underline">
				<div class="label">State</div>
				<div>		
				<select name="order_state" id="order_state" class=""  tabindex="7">
				<option value="">Select State</option>
				<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY def DESC, country_name, state_name ASC ");
				while($state = mysqli_fetch_array($states)) { ?>
				<option value="<?php print $state['state_abr'];?>" <?php if($order['order_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
				<?php } ?>
				</select>
				</div>
			</div>

			<div class="underline">
				<div class="label">Zip Code</div>
				<div><input type="text" name="order_zip" id="order_zip" class="" size="10" value="<?php print $order['order_zip'];?>" tabindex="9"></div>
			</div>

		</div>
		<div style="width: 48%; float: right;">

			<div class="underline">
				<div class="label">City</div>
				<div><input type="text" name="order_city" id="order_city" class="field100" value="<?php print $order['order_city'];?>" tabindex="6"></div>
			</div>


			<div class="underline">
				<div class="label">Country</div>
				<div>		
				<select name="order_country"  id="order_country"  class=""  tabindex="8">
				<?php
				$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

				while($ct = mysqli_fetch_array($cts)) {
					print "<option value=\"".$ct['country_name']."\" "; if($order['order_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
				}
				print "</select>";
				?>
				</div>
			</div>
			<div class="underline">
				<div class="label">Phone</div>
				<div><input type="text" name="order_phone" id="order_phone" class="field100" value="<?php print $order['order_phone'];?>" tabindex="6"></div>
			</div>


		</div>
		<div class="clear"></div>
	</div>

<!-- SHIPPING -->
	<?php if(!empty($order['order_shipping_option'])) { ?>		

		<?php if($order['order_ship_pickup'] == "1") { ?>
			<div class="underlinelabel"><?php print $order['order_shipping_option'];?></div>
		<?php } else { ?>



		<div class="underlinelabel"  onclick="selectsection('shipping');"  style="cursor: pointer;"><span class="the-icons icon-pencil"></span><span>Shipping Address</span></div>
		<div id="shipping" class="hidden">
			<div style="width: 48%; float: left;">
				<div class="underline">
					<div class="label">First Name</div>
					<div><input type="text" name="order_ship_first_name" id="order_ship_first_name" class="optrequired field100" value="<?php print $order['order_ship_first_name'];?>" tabindex="1"></div>
				</div>
			</div>
			<div style="width: 48%; float: right;">
				<div class="underline">
					<div class="label">Last Name</div>
					<div><input type="text" name="order_ship_last_name" id="order_ship_last_name" class="optrequired field100" value="<?php print $order['order_ship_last_name'];?>" tabindex="2"></div>
				</div>
			</div>
			<div class="clear"></div>
			<div>&nbsp;</div>


			<div style="width: 48%; float: left;">
				<div class="underline">
					<div class="label">Address</div>
					<div><input type="text" name="order_ship_address" id="order_ship_address" class="field100" value="<?php print $order['order_ship_address'];?>"  tabindex="5"></div>
				</div>

				<div class="underline">
					<div class="label">State</div>
					<div>		
					<select name="order_ship_state" id="order_ship_state" class=""  tabindex="7">
					<option value="">Select State</option>
					<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY def DESC, country_name, state_name ASC ");
					while($state = mysqli_fetch_array($states)) { ?>
					<option value="<?php print $state['state_abr'];?>" <?php if($order['order_ship_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
					<?php } ?>
					</select>
					</div>
				</div>

				<div class="underline">
					<div class="label">Zip Code</div>
					<div><input type="text" name="order_ship_zip" id="order_ship_zip" class="" size="10" value="<?php print $order['order_ship_zip'];?>" tabindex="9"></div>
				</div>

			</div>
			<div style="width: 48%; float: right;">

				<div class="underline">
					<div class="label">City</div>
					<div><input type="text" name="order_ship_city" id="order_ship_city" class="field100" value="<?php print $order['order_ship_city'];?>" tabindex="6"></div>
				</div>


				<div class="underline">
					<div class="label">Country</div>
					<div>		
					<select name="order_ship_country"  id="order_ship_country"  class=""  tabindex="8">
					<?php
					$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

					while($ct = mysqli_fetch_array($cts)) {
						print "<option value=\"".$ct['country_name']."\" "; if($order['order_ship_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
					}
					print "</select>";
					?>
					</div>
				</div>

			</div>
			<div class="clear"></div>
			</div>
	<?php } ?>
	<?php } ?>

		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Date</div>
				<div><input type="text" name="order_date" id="order_date"  class="datepicker"  value="<?php print $order['order_date'];?>" ></div>
			</div>
		</div>
		<?php if($order['order_invoice'] == "1") { ?>
		<div style="width: 48%; float: right;">

			<div class="underline">
				<div class="label">Due Date</div>
				<div><input type="text" name="order_due_date" id="order_due_date"  class="datepicker"  value="<?php print $order['order_due_date'];?>" ></div>
			</div>
		</div>
		<?php } ?>
		<div class="clear"></div>
		<div>&nbsp;</div>

		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Discount</div>
				<div><input type="text" name="order_discount" id="order_discount" class="optrequired field100" value="<?php print $order['order_discount'];?>" tabindex="1"></div>
			</div>
		</div>
		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Discount Name</div>
				<div><input type="text" name="order_coupon_name" id="order_coupon_name" class="field100" value="<?php print $order['order_coupon_name'];?>" tabindex="2"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>



		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Tax</div>
				<div><input type="text" name="order_tax" id="order_tax" class="optrequired field100" value="<?php print $order['order_tax'];?>" tabindex="1"></div>
			</div>
		</div>
		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">VAT</div>
				<div><input type="text" name="order_vat" id="order_vat" class="optrequired field100" value="<?php print $order['order_vat'];?>" tabindex="2"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>
		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Sub Total</div>
				<div><input type="text" name="order_sub_total" id="order_sub_total" class="optrequired field100" value="<?php print $order['order_sub_total'];?>" tabindex="1"></div>
			</div>
		</div>


		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Grand Total</div>
				<div><input type="text" name="order_total" id="order_total" class="optrequired field100" value="<?php print $order['order_total'];?>" tabindex="1"></div>
			</div>
		</div>


		<div class="clear"></div>
		<div>&nbsp;</div>

		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Payment Amount</div>
				<div><input type="text" name="order_payment" id="order_payment" class="optrequired field100" value="<?php print $order['order_payment'];?>" tabindex="1"></div>
			</div>
		</div>

		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Credit</div>
				<div><input type="text" name="order_credit" id="order_credit" class=" field100" value="<?php print $order['order_credit'];?>" tabindex="1"></div>
			</div>
		</div>

		<div class="clear"></div>
		<div>&nbsp;</div>

		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Gift Certificate</div>
				<div><input type="text" name="order_gift_certificate" id="order_gift_certificate" class="optrequired field100" value="<?php print $order['order_gift_certificate'];?>" tabindex="1"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>



		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Shipping Amount</div>
				<div><input type="text" name="order_shipping" id="order_shipping" class="optrequired field100" value="<?php print $order['order_shipping'];?>" tabindex="1"></div>
			</div>
		</div>

		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Shipping Option</div>
				<div><input type="text" name="order_shipping_option" id="order_shipping_option" class=" field100" value="<?php print $order['order_shipping_option'];?>" tabindex="1"></div>
			</div>
		</div>

		<div class="clear"></div>
		<div>&nbsp;</div>



	<?php if($setup['affiliate_program'] == 1) { ?>

		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Affiliate</div>
				<div>
				<select name="order_aff">
				<option value="">None</option>
				<?php $affs = whileSQL("ms_affiliate LEFT JOIN ms_people ON ms_affiliate.aff_person=ms_people.p_id", "*", "WHERE aff_person>='0' AND aff_status='1' ORDER BY p_last_name ASC ");
					while($aff = mysqli_fetch_array($affs)) { ?>
				<option value="<?php print $aff['aff_id'];?>" <?php if($order['order_aff'] == $aff['aff_id']) { print "selected"; } ?>><?php print $aff['p_last_name'].", ".$aff['p_name'];?></option>
					<?php } ?>
					</select>
		

				</div>
			</div>
		</div>

		<div style="width: 48%; float: right;">
			<div class="underline">
				<div class="label">Percentage</div>
				<div><input type="text" name="order_aff_perc" id="order_aff_perc" class=" field100" value="<?php print $order['order_aff_perc'];?>" tabindex="1"></div>
			</div>
		</div>


		<div class="clear"></div>
		<div>&nbsp;</div>
<?php } ?>
	<div class="pageContent center">

	<input type="hidden" name="order_id" value="<?php print $_REQUEST['order_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['order_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>

<?php require "../w-footer.php"; ?>
