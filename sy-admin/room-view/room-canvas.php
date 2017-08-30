<?php 
$path = "../../";
require "../w-header.php"; 
$cp_settings = doSQL("ms_canvas_settings", "*", "");
$wset = doSQL("ms_wall_settings", "*", "");

if($_REQUEST['submitit'] == "yes") { 
	$order = doSQL("ms_canvas_prints", "*", "ORDER BY cp_order DESC ");
	$thisorder = $order['cp_order'] + 1;

	if($_REQUEST['cp_id'] > 0) { 
		updateSQL("ms_canvas_prints", "cp_width='".$_REQUEST['cp_width']."', cp_height='".$_REQUEST['cp_height']."', cp_price='".$_REQUEST['cp_price']."' , cp_shipable='".$_REQUEST['cp_shipable']."', cp_add_shipping='".$_REQUEST['cp_add_shipping']."', cp_opt1='".$_REQUEST['cp_opt1']."', cp_opt2='".$_REQUEST['cp_opt2']."',
		cp_name='".addslashes(stripslashes($_REQUEST['cp_name']))."',
		cp_taxable='".$_REQUEST['cp_taxable']."',
		cp_no_discount='".$_REQUEST['cp_no_discount']."',
		cp_opt3='".$_REQUEST['cp_opt3']."',
		cp_opt4='".$_REQUEST['cp_opt4']."',
		cp_opt5='".$_REQUEST['cp_opt5']."',
		cp_opt6='".$_REQUEST['cp_opt6']."',
		cp_opt7='".$_REQUEST['cp_opt7']."',
		cp_opt8='".$_REQUEST['cp_opt8']."',
		cp_price_product='".$_REQUEST['cp_price_product']."'

		WHERE cp_id='".$_REQUEST['cp_id']."' ");
	} else { 
		insertSQL("ms_canvas_prints", "cp_width='".$_REQUEST['cp_width']."', cp_height='".$_REQUEST['cp_height']."', cp_price='".$_REQUEST['cp_price']."' , cp_shipable='".$_REQUEST['cp_shipable']."', cp_add_shipping='".$_REQUEST['cp_add_shipping']."',  cp_order='".$thisorder."', cp_opt1='".$_REQUEST['cp_opt1']."', cp_opt2='".$_REQUEST['cp_opt2']."',
		cp_name='".addslashes(stripslashes($_REQUEST['cp_name']))."',
		cp_taxable='".$_REQUEST['cp_taxable']."',
		cp_no_discount='".$_REQUEST['cp_no_discount']."',
		cp_opt3='".$_REQUEST['cp_opt3']."',
		cp_opt4='".$_REQUEST['cp_opt4']."',
		cp_opt5='".$_REQUEST['cp_opt5']."',
		cp_opt6='".$_REQUEST['cp_opt6']."',
		cp_opt7='".$_REQUEST['cp_opt7']."',
		cp_opt8='".$_REQUEST['cp_opt8']."'	,
		cp_price_product='".$_REQUEST['cp_price_product']."'

		");
	}
	$_SESSION['sm'] = "Canvas Saved";
}
?>

<script>
function savedata(classname) { 
	var fields = {};
	var stop = false;

	$(".optrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('requiredFieldEmpty');
			stop = true;
		} else { 
			$('#'+this_id).removeClass('requiredFieldEmpty');
		}
	} );


	if(stop == true) { 

	} else { 


		$('#saveform').text("saving...");
		$('#saveform').removeClass("submit").addClass("submitsaving");
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
				// alert($this.val());
			}

		});
			
			
		<?php if($setup['demo_mode'] !== true) { ?>
		$.post("room-view/room-canvas.php", fields,	function (data) { 
			//  alert(data);
			// framesizes($("#style_id").val());
			window.location.href="index.php?do=photoprods&view=roomview&sub=canvases";
			//showSuccessMessage("Saved");
		//	setTimeout(hideSuccessMessage,4000);
		//	$('#saveform').text("Save");
			//$('#saveform').removeClass("submitsaving").addClass("submit");

		});
		<?php } else {  ?>
			// framesizes($("#style_id").val());
			window.location.href="index.php?do=photoprods&view=roomview&sub=frame";
		//	showSuccessMessage("Saved");
		//	setTimeout(hideSuccessMessage,4000);
		//	$('#saveform').text("Save");
		//	$('#saveform').removeClass("submitsaving").addClass("submit");

		<?php } ?>
	}
}

function showadditionalshipping() { 
	if($("#cp_shipable").attr("checked")) { 
		$("#add_ship").show();
	} else { 
		$("#add_ship").hide();
	}

}

$(document).ready(function(){
	setTimeout(function(){ 
		$("#cp_width").focus().select();
	},200);
	showadditionalshipping();
});
</script>

			
<div class="pc"><h3><?php if($_REQUEST['cp_id'] > 0) { ?>Edit Canvas<?php } else { ?>Add New Canvas Size<?php } ?></h3></div>

<?php if($_REQUEST['cp_id'] > 0) { 
	$cp = doSQL("ms_canvas_prints", "*", "WHERE cp_id='".$_REQUEST['cp_id']."' "); 
} else { 
	$cp['cp_add_shipping'] = "0.00";
}
?>

<div class="clear"></div>
<div>&nbsp;</div>

<div id="newframe" class="">
	<form method="post" name="famesizes" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
		<div class="left p25">
			<div class="label">Width</div>
			<div><input type="text" name="cp_width" id="cp_width" size="2" class="center optrequired formfield inputtitle" value="<?php print $cp['cp_width'] * 1;?>"> <?php print $wset['size_symbol'];?></div>
		</div>

		<div class="left p25">
			<div class="label">Height</div>
			<div><input type="text" name="cp_height" id="cp_height" size="2" class="center optrequired formfield inputtitle" value="<?php print $cp['cp_height'] * 1;?>"> <?php print $wset['size_symbol'];?></div>
		</div>

		<div class="clear"></div>


		<div>&nbsp;</div>
	<!-- 	<div class="underlinelabel">Prices</div>
		<?php if($cp_settings['cp_opt1_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt1'];?></div>
			<div><input type="text" name="cp_opt1" id="cp_opt1" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt1'] * 1;?>"></div>
		</div>

		<?php } ?>
		<?php if($cp_settings['cp_opt2_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt2'];?></div>
			<div><input type="text" name="cp_opt2" id="cp_opt2" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt2'] * 1;?>"></div>
		</div>
		<?php } ?>


		<?php if($cp_settings['cp_opt3_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt3'];?></div>
			<div><input type="text" name="cp_opt3" id="cp_opt3" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt3'] * 1;?>"></div>
		</div>
		<?php } ?>


		<?php if($cp_settings['cp_opt4_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt4'];?></div>
			<div><input type="text" name="cp_opt4" id="cp_opt4" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt4'] * 1;?>"></div>
		</div>
		<?php } ?>

-->

		<div class="clear"></div>
	</div>
	<div class="underline">
		<div class="left p25">
			<div class="label">Price</div>
			<div><?php print $site_setup['currency_sign'];?> <input type="text" name="cp_opt1" id="cp_opt1" size="6" class="center formfield inputtitle" value="<?php print $cp['cp_opt1'] * 1;?>"></div>
		</div>
		<div class="left p75">
			<div class="label">Or use price from of a product in the product base: </div>
			<div>
			<select name="cp_price_product" id="cp_price_product" class="formfield">
			<option value="">Do not use</option>
			<?php $prods = whileSQL("ms_photo_products", "*", "ORDER BY pp_name ASC ");
			while($prod = mysqli_fetch_array($prods)) { ?>
			<option value="<?php print $prod['pp_id'];?>" <?php if($cp['cp_price_product'] == $prod['pp_id']) { ?>selected<?php } ?>><?php print $prod['pp_name']; if(!empty($prod['pp_internal_name'])) { ?> (<?php print $prod['pp_internal_name'];?>)<?php } ?></option>
			<?php } ?>
			</select>
			</div>
			<div>Selecting a product here will use the price of the product from the price list assigned to the gallery.</div>
		</div>
			<div class="clear"></div>
</div>
	<div class="clear"></div>


		<div>&nbsp;</div>



		<?php if($cp_settings['cp_opt5_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt5'];?></div>
			<div><input type="text" name="cp_opt5" id="cp_opt5" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt5'] * 1;?>"></div>
		</div>

		<?php } ?>
		<?php if($cp_settings['cp_opt6_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt6'];?></div>
			<div><input type="text" name="cp_opt6" id="cp_opt6" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt6'] * 1;?>"></div>
		</div>
		<?php } ?>


		<?php if($cp_settings['cp_opt7_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt7'];?></div>
			<div><input type="text" name="cp_opt7" id="cp_opt7" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt7'] * 1;?>"></div>
		</div>
		<?php } ?>


		<?php if($cp_settings['cp_opt8_use'] == "1") { ?>
		<div class="left p25">
			<div class="label"><?php print $cp_settings['cp_opt8'];?></div>
			<div><input type="text" name="cp_opt8" id="cp_opt8" size="2" class="center optrequired formfield" value="<?php print $cp['cp_opt8'] * 1;?>"></div>
		</div>
		<?php } ?>



		<div class="clear"></div>
	</div>


	<?php if($_REQUEST['cp_id'] <= 0) { 
	$last = doSQL("ms_canvas_prints","*","ORDER BY cp_id DESC ");
	$cp['cp_name'] = $last['cp_name'];
	}
	?>
	<div class="underline">
		<div class="label">Name</div>
		<div><input type="text" name="cp_name" id="cp_name" size="20" class="optrequired formfield inputtitle" value="<?php print $cp['cp_name'];?>"></div>
		<div>Enter just "Canvas" or "Canvas Print". This is added with the size to the cart and order.</div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="cp_taxable" id="cp_taxable" class="formfield" value="1" <?php if(($cp['cp_taxable'] == "1" || $_REQUEST['cp_id'] <= 0) == true) { ?>checked<?php } ?>> <label for="cp_taxable">Taxable</label>	</div>
	</div>
	<div class="underline">
			<div class="label"><input type="checkbox" name="cp_no_discount" id="cp_no_discount" class="formfield" value="1" <?php if(($cp['cp_no_discount'] == "1" || $_REQUEST['cp_id'] <= 0) == true) { ?>checked<?php } ?>> <label for="cp_no_discount">Do not allow discounting</label></div>
	</div>


		<div class="underline">
			<div class="left p25">
				<div class="label"><input type="checkbox" onchange="showadditionalshipping();" class="formfield" name="cp_shipable" id="cp_shipable"  value="1" <?php if($cp['cp_shipable'] == "1") { ?>checked<?php } ?>> <label for="cp_shipable">Eligible for shipping</label></div>
			</div>
			<div class="left p75" id="add_ship">
				<div>If eligible for shipping, enter any additional shipping amount: <?php print $site_setup['currency_sign'];?><input type="text" name="cp_add_shipping" id="cp_add_shipping" size="6" class="center formfield" value="<?php print $cp['cp_add_shipping'] * 1;?>"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div>&nbsp;</div>


		<div class="pc center buttons">
		<?php if($setup['demo_mode'] == true) { ?>
		<a href="" onclick="return false;">Save Disabled For Demo</a>
		<?php } else { ?>
		<input type="hidden" name="style_id" id="style_id" class="formfield" value="<?php print $_REQUEST['style_id'];?>">
		<input type="hidden" name="submitit" id="submitit" class="formfield" value="yes">
		<input type="hidden" name="cp_id" id="cp_id" class="formfield" value="<?php print $cp['cp_id'];?>">
		<a href="" id="saveform" onclick="savedata('formfield'); return false;">Save</a>
		<?php } ?>
		</div>
		<?php if($_REQUEST['cp_id'] > 0) { ?>
		<div class="pc center"><a href="" onclick="closewindowedit(); return false;">cancel</a></div>
		<?php } ?>
		<div>&nbsp;</div>

	</form>

</div>



<?php require "../w-footer.php"; ?>
