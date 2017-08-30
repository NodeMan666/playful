<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#pc_name").focus();
 }
$('#pc_code').change(function() {
	checkpccode();
});

function checkpccode() { 
	$.get("admin.actions.php?action=pccode&pc_code="+$("#pc_code").val()+"&pc_id="+$("#pc_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The redeem code "+$("#pc_code").val()+" is already being used for another print credit. Please enter a different redeem code.");
			$("#pc_code").val("")
		}
	});
}

</script>
<?php
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' ");

if($_POST['submitit']=="yes") { 
	updateSQL("ms_photo_products_lists", "
	list_name='".addslashes(stripslashes($_REQUEST['list_name']))."',
	list_filters='".$_REQUEST['list_filters']."',
	list_require_login='".$_REQUEST['list_require_login']."',
	list_show_crop='".$_REQUEST['list_show_crop']."',
	list_products_placement='".$_REQUEST['list_products_placement']."',
	list_allow_notes='".$_REQUEST['list_allow_notes']."',
	list_min_order='".$_REQUEST['list_min_order']."',
	list_extra_1='".addslashes(stripslashes($_REQUEST['list_extra_1']))."',
	list_extra_1_req='".$_REQUEST['list_extra_1_req']."',
	list_extra_2='".addslashes(stripslashes($_REQUEST['list_extra_2']))."',
	list_extra_2_req='".$_REQUEST['list_extra_2_req']."',
	list_extra_3='".addslashes(stripslashes($_REQUEST['list_extra_3']))."',
	list_extra_3_req='".$_REQUEST['list_extra_3_req']."',
	list_extra_4='".addslashes(stripslashes($_REQUEST['list_extra_4']))."',
	list_extra_4_req='".$_REQUEST['list_extra_4_req']."',
	list_extra_5='".addslashes(stripslashes($_REQUEST['list_extra_5']))."',
	list_extra_5_req='".$_REQUEST['list_extra_5_req']."'

	WHERE list_id='".$_REQUEST['list_id']."' ");
	$_SESSION['sm'] = $_REQUEST['list_name']." Saved";
	header("location: ../index.php?do=photoprods&view=list&list_id=".$_REQUEST['list_id']."");
	session_write_close();
	exit();
}
?>



	<div class="pc"><h1>Price List Options for  <?php print $list['list_name'];?></h1></div>
	<div class="pc"></div>



	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
	<input type="text" name="list_name" value="<?php print $list['list_name'];?>" size="40"> 
	</div>
	<div class="underline"><input type="checkbox" name="list_filters" id="list_filters" value="1" <?php if($list['list_filters'] == "1") { print "checked"; } ?>> <label for="list_filters">Use B&W / filter options</label> </div>
	<div class="underline">
		<div class="label">Require customer to be log into their account to add to cart?</div>
		<div><input type="radio" name="list_require_login" id="list_require_login0" value="0" <?php if($list['list_require_login'] == "0") { print "checked"; } ?>> <label for="list_require_login0">No</label></div>
		<div><input type="radio" name="list_require_login" id="list_require_login1" value="1" <?php if($list['list_require_login'] == "1") { print "checked"; } ?>> <label for="list_require_login1">Yes and do not display product</label></div>
		<div><input type="radio" name="list_require_login" id="list_require_login2" value="2" <?php if($list['list_require_login'] == "2") { print "checked"; } ?>> <label for="list_require_login2">Yes and display products</label></div>
	</div>
	<div class="underline"><input type="checkbox" name="list_allow_notes" id="list_allow_notes" value="1" <?php if($list['list_allow_notes'] == "1") { print "checked"; } ?>> <label for="list_allow_notes">Allow customers to make notes on photos in the cart</label> </div>
	<div class="underline"><input type="text" name="list_min_order" value="<?php print $list['list_min_order'];?>" size="8"> Minimum order amount</div>
	<div class="underline"><input type="checkbox" name="list_products_placement" id="list_products_placement" value="1" <?php if($list['list_products_placement'] == "1") { print "checked"; } ?>> <label for="list_products_placement">Show buy icon above photo instead of displaying the products to the right of the photo.</label></div>
	<div>&nbsp;</div>
	<div class="underlinelabel">Additional Fields at Checkout</div>
	<div class="underlinespacer">You can add up to 5 additional fields for your customers to enter when checking out.</div>

	<div class="underlinecolumn">
		<div class="p10 left">&nbsp;</div>
		<div class="p80 left">Field Name</div>
		<div class="p10 left">Required</div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="p10 left">#1</div>
		<div class="p80 left"><input type="text" name="list_extra_1" value="<?php print $list['list_extra_1'];?>" size="40" class="field100"></div>
		<div class="p10 left center"><input type="checkbox" value="1" name="list_extra_1_req" id="list_extra_1_req" <?php if($list['list_extra_1_req'] == "1") { ?>checked<?php } ?>></div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="p10 left">#2</div>
		<div class="p80 left"><input type="text" name="list_extra_2" value="<?php print $list['list_extra_2'];?>" size="40" class="field100"></div>
		<div class="p10 left center"><input type="checkbox" value="1" name="list_extra_2_req" id="list_extra_2_req" <?php if($list['list_extra_2_req'] == "1") { ?>checked<?php } ?>></div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="p10 left">#3</div>
		<div class="p80 left"><input type="text" name="list_extra_3" value="<?php print $list['list_extra_3'];?>" size="40" class="field100"></div>
		<div class="p10 left center"><input type="checkbox" value="1" name="list_extra_3_req" id="list_extra_3_req" <?php if($list['list_extra_3_req'] == "1") { ?>checked<?php } ?>></div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="p10 left">#4</div>
		<div class="p80 left"><input type="text" name="list_extra_4" value="<?php print $list['list_extra_4'];?>" size="40" class="field100"></div>
		<div class="p10 left center"><input type="checkbox" value="1" name="list_extra_4_req" id="list_extra_4_req" <?php if($list['list_extra_4_req'] == "1") { ?>checked<?php } ?>></div>
		<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="p10 left">#5</div>
		<div class="p80 left"><input type="text" name="list_extra_5" value="<?php print $list['list_extra_5'];?>" size="40" class="field100"></div>
		<div class="p10 left center"><input type="checkbox" value="1" name="list_extra_5_req" id="list_extra_5_req" <?php if($list['list_extra_5_req'] == "1") { ?>checked<?php } ?>></div>
		<div class="clear"></div>
	</div>


	<div class="underline center">
	<input type="hidden" name="list_id" id="list_id" value="<?php print $list['list_id'];?>">
	<input type="hidden" name="submitit" id="submitit" value="yes">
	<input type="submit" name="submit" value="Save" class="submit" id="submitButton">

	</div>
	</form>

<?php require "../w-footer.php"; ?>