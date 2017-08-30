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

function selectbatch() { 
	$("#batchcreate").slideToggle();
	if($("#pc_batch_select").attr("checked")) { 
		$("#pc_batch").addClass("optrequired");
		$("#pc_start").addClass("optrequired");
		$("#pc_end").addClass("optrequired");

	} else { 
		$("#pc_batch").removeClass("optrequired");
		$("#pc_start").removeClass("optrequired");
		$("#pc_end").removeClass("optrequired");
	}
}
</script>
<?php

if($_POST['submitit']=="yes") { 
	$_REQUEST['pc_code'] = trim($_REQUEST['pc_code']);
	if($_REQUEST['pc_id'] > 0) { 

		updateSQL("ms_print_credits", "
		pc_name='".addslashes(stripslashes($_REQUEST['pc_name']))."' , 
		pc_code='".addslashes(stripslashes($_REQUEST['pc_code']))."' , 
		pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
		pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
		pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
		pc_expire='".addslashes(stripslashes($_REQUEST['pc_expire']))."'
		WHERE pc_id='".$_REQUEST['pc_id']."' ");
		$_SESSION['sm'] = "Print Credit Saved";
		$pc_id=$_REQUEST['pc_id'];
		header("location: ../index.php?do=photoprods&view=printcredits");

	} else {
		if($_REQUEST['pc_batch_select'] == "1") { 
			while($x < $_REQUEST['pc_end']) { 
				insertSQL("ms_print_credits", "
				pc_name='".addslashes(stripslashes($_REQUEST['pc_name']))."' , 
				pc_code='".addslashes(stripslashes(trim($_REQUEST['pc_code'].$_REQUEST['pc_start'])))."' , 
				pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
				pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
				pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
				pc_expire='".addslashes(stripslashes($_REQUEST['pc_expire']))."',
				pc_batch='".addslashes(stripslashes(trim($_REQUEST['pc_batch'])))."'

				");
				$x++;
				$_REQUEST['pc_start']++;
			}
			$_SESSION['sm'] = "Print Credits Created";
			header("location: ../index.php?do=photoprods&view=printcredits&pc_batch=".$_REQUEST['pc_batch']."");

		} else { 
			$pc_id = insertSQL("ms_print_credits", "

			pc_name='".addslashes(stripslashes($_REQUEST['pc_name']))."' , 
			pc_code='".addslashes(stripslashes(trim($_REQUEST['pc_code'])))."' , 
			pc_package='".addslashes(stripslashes($_REQUEST['pc_package']))."', 
			pc_descr='".addslashes(stripslashes($_REQUEST['pc_descr']))."',
			pc_ship='".addslashes(stripslashes($_REQUEST['pc_ship']))."',
			pc_expire='".addslashes(stripslashes($_REQUEST['pc_expire']))."'
			");
			$_SESSION['sm'] = "Print Credit Created";
			header("location: ../index.php?do=photoprods&view=printcredits");
		}
	}
	session_write_close();
	exit();
}
?>


<?php 
	if(($_REQUEST['pc_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$pc = doSQL("ms_print_credits", "*", "WHERE pc_id='".$_REQUEST['pc_id']."' "); 
		if(empty($pc['pc_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($pc AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<div class="pc"><?php if(empty($pc['pc_id'])) { ?><h1>Create Print Credit</h1><?php } else { ?><h1>Edit Print Credit</h1><?php } ?>
	<div class="pc">When creating a print credit, you are going to be selecting a collection you have created. If you have not created a collection that includes what you want to be included in the print credit, <a href="index.php?do=photoprods&view=packages">do that first</a>.</div>


	<?php $packs = whileSQL("ms_packages", "*", "ORDER BY package_name ASC ");
	if(mysqli_num_rows($packs)<=0) { ?>
		<div class="error">You have not created any collections. You must first create a collection before you can create a print credit.</div>
	<?php } else { ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
		<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Name</div>
			<div><input type="text" name="pc_name" id="pc_name"  class="field100 optrequired"  value="<?php print $pc['pc_name'];?>" tabindex="1"></div>
			<div>This name is seen on the for the print credit in the cart and on the order</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Redeem Code</div>
			<div><input type="text" name="pc_code" id="pc_code" class="optrequired" size="15" value="<?php print $pc['pc_code'];?>" tabindex="2"></div>
			<div>The code they need to enter to redeem their print credit.</div>
		</div>
	</div>

	<div class="clear"></div>
	<div style="width: 48%; float: left;">

			<div class="underline">
			<div class="label">Collection</div>
			<div>
			<?php $packs = whileSQL("ms_packages", "*", "WHERE package_buy_all<='0' ORDER BY package_name ASC ");
			if(mysqli_num_rows($packs)<=0) { ?>
			You have not created any collections. You must first create a collection before you can create a print credit.
			<?php } else { ?>
			<select name="pc_package" id="pc_package" class="optrequired">
			<option value="">Select a collection</option>
			<?php while($pack = mysqli_fetch_array($packs)) { ?>
			<option value="<?php print $pack['package_id'];?>" <?php if($pc['pc_package'] == $pack['package_id']) { print "selected"; } ?>><?php print $pack['package_name'];?></option>
			<?php } ?>
			</select>
			<?php } ?>
			</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Expiration Date</div>
			<div><input type="text" name="pc_expire" id="pc_expire"  class="datepicker"  value="<?php print $pc['pc_expire'];?>" tabindex="2"></div>
		</div>
	</div>
	<div class="clear"></div>
	
		<div class="underline">
		<div class="label"><input type="checkbox" name="pc_ship" id="pc_ship" value="1" <?php if($pc['pc_ship'] == "1") { print "checked"; } ?>> <label for="pc_ship">Enable shipping?</label>  Select this if you want this print credit be available for shipping and apply any shipping charges.</div>
	</div>

<!--
	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="pc_descr" id="pc_descr" rows="3" class="field100"><?php print $pc['pc_descr'];?></textarea></div>
	</div>
-->

<div class="clear"></div>
<?php 	if($_REQUEST['pc_id'] <= 0) { ?>

	<div class="underline">
		<div class="label"><input type="checkbox" name="pc_batch_select" id="pc_batch_select" value="1" onchange="selectbatch();"> <label for="pc_batch_select">Create a batch of Print Credits</label>	</div>
	</div>
	<div id="batchcreate" class="hide">
	
		<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Batch Name</div>
			<div><input type="text" name="pc_batch" id="pc_batch"  class="field100"  value="<?php print $pc['pc_batch'];?>" tabindex="1"></div>
			<div>For your reference</div>
		</div>
	</div>

	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Start Number</div>
			<div><input type="text" name="pc_start" id="pc_start" class="" size="8" value="1000" tabindex="2"></div>
			<div>The start number gets added to the end of the redeem code. Example, your redeem code is testing and the start number is 1000, then it will become testing1000,testing1001, etc... Be sure the start number starts with a number higher than 0. Example: 1234</div>
		</div>
	</div>

	<div class="clear"></div>

		<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">How many do you want to create?</div>
			<div><input type="text" name="pc_end" id="pc_end"  class="" size="8"  value="" ></div>
		</div>
	</div>
	<div class="clear"></div>


	</div>
	<?php } ?>
	<div class="pageContent center">

	<input type="hidden" name="pc_id" id="pc_id" value="<?php print $_REQUEST['pc_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['pc_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php } ?>
<?php require "../w-footer.php"; ?>
