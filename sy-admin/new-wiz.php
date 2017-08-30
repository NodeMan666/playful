<?php 
$noclose = 1;
require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<script>

function savedata(classname) { 
	var fields = {};
	var stop = false;
	$('.saveform').text("saving...");
	$('.saveform').removeClass("submit").addClass("submitsaving");
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.attr("value"); 
				// alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			} else { 
				fields[$this.attr('name')] = "";
			}
			
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}

	});
		
		
	fields['slide_link'] = $("#slide_link").val();

	<?php if($setup['demo_mode'] !== true) { ?>
	$.post("new-wiz.php", fields,	function (data) { 
		// alert(data);
		wizchoose(fields['next']);
		fieldtype = fields['fieldtype'];
		$("#"+fields['fieldtype']+"_val").html(fields[fieldtype]);
		$('.saveform').text("Save");
		$('.saveform').removeClass("submitsaving").addClass("submit");

	});
	<?php } else {  ?>
		sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('.saveform').text("Save");
		$('.saveform').removeClass("submitsaving").addClass("submit");

	<?php } ?>
	}

function wizchoose(id) { 
	$(".wizedit").slideUp(400, function() { $(".thisval").show() });	


	setTimeout(function() {
		$("#"+id).slideDown(200, function() { 
			$("."+id).hide();
		});
	}, 400);

}





</script>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>
<?php adminsessionCheck(); ?>
<?php 



?>




<?php 
if($_REQUEST['action'] == "save") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}
	$com = 0;

	if($_POST['fieldtype'] == "contact_email") { 
		updateSQL("ms_settings", "contact_email='".$_REQUEST['contact_email']."' ");

	}

	if($_POST['fieldtype'] == "website_title") { 
		updateSQL("ms_settings", "website_title='".$_REQUEST['website_title']."' ");
	}

	if($_POST['fieldtype'] == "mailing_address") { 
		updateSQL("ms_store_settings", "packing_slip_top='".$_REQUEST['mailing_address']."' ");
	}


	if($_POST['fieldtype'] == "tax_rate") { 
		updateSQL("ms_states", "state_tax='".$_REQUEST['tax_rate']."' WHERE state_abr='".$_REQUEST['state']."' ");
		print_r($_POST);
	}
	if($_POST['fieldtype'] == "shipping_cost") { 
		updateSQL("ms_shipping_prices", "price_amount='".$_REQUEST['shipping_cost']."' ");
		print_r($_POST);
	}



	if($_POST['fieldtype'] == "paypal_email") { 
		trim($_REQUEST['paypal_email']);
		if(!empty($_REQUEST['paypal_email'])) { 
			updateSQL("ms_payment_options", "pay_num='".$_REQUEST['paypal_email']."', pay_status='1', test_mode='0' WHERE pay_option='paypalstandard' ");
		} else { 
			updateSQL("ms_payment_options", "pay_num='', pay_status='0' WHERE pay_option='paypalstandard' ");
		}
	}

	exit();
	
	
	?>


<?php  } ?>
<style>
.thisval { padding-left: 16px; } 
.wizeditclick { cursor: pointer; } 
</style>
<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
<input type="hidden" name="feat_page_id" id="feat_page_id" value="<?php print $date['date_id'];?>" class="formfield">
<input type="hidden" name="feat_cat_id" id="feat_cat_id" value="<?php print $cat['cat_id'];?>" class="formfield">
<input type="hidden" name="show_id" id="show_id" value="<?php print $show['show_id'];?>" class="formfield">
<input type="hidden" name="action" id="action" value="save" class="formfield">
<div class="pc center"><h1>Getting Started Wizard</h1></div>
<div class="pc center" style="font-size: 17px;">Below are some settings to take care of to help you get started. These can be changed at any time & most are found under the Settings menu.</div>

<div>
	<div style="padding: 16px;">

		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('contactemail');">Your Contact Email</div>
			<div class="wizedit hide"  id="contactemail">
				<div class="underline">
					<div class="pc">This will be the email address used to send order notifications to and also the email address emails are sent from to customers.</div>

					<div class="pc"><input type="text" name="contact_email" id="contact_email" value="<?php print $site_setup['contact_email'];?>" class="field100 inputtitle emailfield"></div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="emailfield" type="hidden" id="fieldtype" name="fieldtype" value="contact_email">
				<input class="emailfield" type="hidden" id="action" name="action" value="save">
				<input class="emailfield" type="hidden" id="next" name="next" value="websitename">
				<span href="" class="saveform submit" onclick="savedata('emailfield'); return false;">Save</span>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('emailfield'); return false;">skip</a></div>
				</div>
			</div>
			<div class="contactemail thisval" id="contact_email_val"><?php print $site_setup['contact_email'];?></div>
		</div>
		<div>&nbsp;</div>




		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('websitename');">Website Title</div>
			<div class="wizedit hide"  id="websitename">
				<div class="underline">
					<div class="pc"></div>

					<div class="pc"><input type="text" name="website_title" id="website_title" value="<?php print $site_setup['website_title'];?>" class="field100 inputtitle websitetitlefield"></div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="websitetitlefield" type="hidden" id="fieldtype" name="fieldtype" value="website_title">
				<input class="websitetitlefield" type="hidden" id="action" name="action" value="save">
				<input class="websitetitlefield" type="hidden" id="next" name="next" value="paypal">
				<div><span href="" class="saveform submit" onclick="savedata('websitetitlefield'); return false;">Save</span></div>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('websitetitlefield'); return false;">skip</a></div>

				</div>
			</div>
			<div class="websitename thisval" id="website_title_val"><?php print $site_setup['website_title'];?></div>
		</div>
		<div>&nbsp;</div>

	<?php $payoption = doSQL("ms_payment_options", "*", "WHERE pay_option='paypalstandard' "); ?>

		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('paypal');">PayPal Email Address</div>
			<div class="wizedit"  id="paypal">
				<div class="underline">
					<div class="pc">If you want to use PayPal to accept payments and you have a PayPal Standard Business account, enter in your PayPal email address here. If you want to use a different available payment option, you can set that up in Settings -> Checkout & Payment</div>

					<div class="pc"><input type="text" name="paypal_email" id="paypal_email" value="<?php print $payoption['pay_num'];?>" class="field100 inputtitle paypalfield"></div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="paypalfield" type="hidden" id="fieldtype" name="fieldtype" value="paypal_email">
				<input class="paypalfield" type="hidden" id="action" name="action" value="save">
				<input class="paypalfield" type="hidden" id="next" name="next" value="mailing">
				<span href="" class="saveform submit" onclick="savedata('paypalfield'); return false;">Save</span>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('paypalfield'); return false;">skip</a></div>
				</div>
			</div>
			<div class="paypal thisval" id="paypal_email_val"><?php print $payoption['pay_num'];?></div>
		</div>
		<div>&nbsp;</div>









		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('mailing');">Mailing Address For Packing Slips</div>
			<div class="wizedit hide"  id="mailing">
				<div class="underline">
					<div class="pc">This is the mailing address shown at the top of order packing slips.</div>

					<div class="pc"><textarea type="text" name="mailing_address" id="mailing_address" rows="4" cols="60" class="inputtitle mailingfield"><?php print $store['packing_slip_top'];?></textarea></div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="mailingfield" type="hidden" id="fieldtype" name="fieldtype" value="mailing_address">
				<input class="mailingfield" type="hidden" id="action" name="action" value="save">
				<input class="mailingfield" type="hidden" id="next" name="next" value="tax">
				<span href="" class="saveform submit" onclick="savedata('mailingfield'); return false;">Save</span>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('mailingfield'); return false;">skip</a></div>
				</div>
			</div>
			<div class="mailing thisval" id="mailing_address_val"><?php print $store['packing_slip_top'];?></div>
		</div>
		<div>&nbsp;</div>





		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('tax');">Tax Settings</div>
			<div class="wizedit hide"  id="tax">
				<div class="underline">
					<div class="pc">If you are in the USA and need to set a tax percentage for taxable items for your state, select your state and enter in the percentage. <br><br>If you are outside of the USA or need to set tax by zip codes or set a VAT rate, go to Settings -> Tax to set that up.</div>

					<div class="pc">
					<select name="state" id="state" class="taxfield inputtitle">
					<?php 
					
					$tax_rate = "0.0000";
					$states = whileSQL("ms_states","*","WHERE state_country='United States' ORDER BY state_name ASC");
					while($state = mysqli_fetch_array($states)) { ?>
					<option value="<?php print $state['state_abr'];?>" <?php if($state['state_tax'] > 0) { ?>selected<?php $tax_rate = $state['state_tax']; } ?>><?php print $state['state_name'];?></option>
					<?php } ?>
					</select> <input type="text" name="tax_rate" id="tax_rate" value="<?php print $tax_rate;?>" class="center inputtitle taxfield" size="6">%
					</div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="taxfield" type="hidden" id="fieldtype" name="fieldtype" value="tax_rate">
				<input class="taxfield" type="hidden" id="action" name="action" value="save">
				<input class="taxfield" type="hidden" id="next" name="next" value="shipping">
				<span href="" class="saveform submit" onclick="savedata('taxfield'); return false;">Save</span>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('taxfield'); return false;">skip</a></div>
				</div>
			</div>
			<div class="tax thisval" id="tax_rate_val"><?php  if($tax_rate > 0) { print $tax_rate; } ?></div>
		</div>
		<div>&nbsp;</div>


		<?php 
		if(countIt("ms_shipping_prices", "") == "1") { 
		$ship = doSQL("ms_shipping_prices", "*", ""); ?>
		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('shipping');">Shipping</div>
			<div class="wizedit hide"  id="shipping">
				<div class="underline">
					<div class="pc">Set up a flat rate shipping price here if you like. You can create shipping charts and other shipping options in Settings -> Shipping.</div>

					<div class="pc">
					$<input type="text" name="shipping_cost" id="shipping_cost" value="<?php print $ship['price_amount'];?>" class="center inputtitle shippingfield" size="6">
					</div>
				</div>
				<div>&nbsp;</div>
				<div class="pc center">
				<input class="shippingfield" type="hidden" id="fieldtype" name="fieldtype" value="shipping_cost">
				<input class="shippingfield" type="hidden" id="action" name="action" value="save">
				<input class="shippingfield" type="hidden" id="next" name="next" value="continue">
				<span href="" class="saveform submit" onclick="savedata('shippingfield'); return false;">Save</span>
				<div>&nbsp;</div>
				<div><a href="" onclick="savedata('shippingfield'); return false;">skip</a></div>
				</div>
			</div>
			<div class="shipping thisval" id="shipping_cost_val"><?php  print $ship['price_amount']; ?></div>
		</div>
		<div>&nbsp;</div>
	<?php } ?>


		<div>
			<div class="underlinelabel wizeditclick" onclick="wizchoose('continue');">Continue</div>
			<div class="wizedit hide"  id="continue">
			<?php if($setup['sytist_hosted'] == true) { ?>
			<div class="pc center" style="font-size: 21px;">Great! <br><br>Next, let's create your section for creating galleries. 
			<br><br><a href="index.php?do=news&action=editCategory&cg=1"><b>Click here to continue</b></a>
			</div>
			<?php } else { ?>
			<div class="pc">Now that is out of the way, here are other things to do. This information will also be shown on your admin home page in the getting started section.</div>
		<?php include "new-info.php"; ?>
		<?php } ?>


			</div>
		</div>
		<div>&nbsp;</div>



	</div>
</div>
<div class="clear"></div>

</form>



<?php require "w-footer.php"; ?>
