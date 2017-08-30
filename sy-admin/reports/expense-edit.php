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
	$("#exp_amount").focus();
 }


</script>
<?php

if($_POST['submitit']=="yes") { 
	if($_REQUEST['exp_id'] > 0) { 

		updateSQL("ms_expenses", "
		exp_amount='".addslashes(stripslashes($_REQUEST['exp_amount']))."' , 
		exp_date='".addslashes(stripslashes($_REQUEST['exp_date']))."', 
		exp_notes='".addslashes(stripslashes($_REQUEST['exp_notes']))."',
		exp_reference='".addslashes(stripslashes($_REQUEST['exp_reference']))."',
		exp_order='".$_REQUEST['exp_order']."'
		WHERE exp_id='".$_REQUEST['exp_id']."' ");
		$_SESSION['sm'] = "Expense Saved";
		$exp_id=$_REQUEST['exp_id'];

	} else {

   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}


	$password = md5($_REQUEST['p_pass'].$salt);

		$exp_id = insertSQL("ms_expenses", "
		exp_amount='".addslashes(stripslashes($_REQUEST['exp_amount']))."' , 
		exp_date='".addslashes(stripslashes($_REQUEST['exp_date']))."', 
		exp_notes='".addslashes(stripslashes($_REQUEST['exp_notes']))."',
		exp_reference='".addslashes(stripslashes($_REQUEST['exp_reference']))."',
		exp_order='".$_REQUEST['exp_order']."'
		");
		$_SESSION['sm'] = "Expense Added";

	}

	deleteSQL2("ms_expenses_tags_connect", "WHERE con_exp_id='".$exp_id."' ");
	if(is_array($_REQUEST['e_tags'])) { 
		foreach($_REQUEST['e_tags'] AS $tag_id => $val) {
			insertSQL("ms_expenses_tags_connect", "con_tag_id='".$tag_id."',con_exp_id='".$exp_id."' ");
		}
	}

	if(!empty($_REQUEST['new_tags'])) { 
		$new_tags = explode(",",$_REQUEST['new_tags']);
		foreach($new_tags AS $tag) { 
			$tag = trim($tag);
			$tag = strtolower($tag);
			$cktag = doSQL("ms_expenses_tags", "*", "WHERE name='".$tag."' ");
			if(empty($cktag['tag_id'])) { 
				$tag_id = insertSQL("ms_expenses_tags", "name='".addslashes(stripslashes($tag))."' ");
			} else { 
				$tag_id = $cktag['tag_id'];
			}
			$ckcon = doSQL("ms_expenses_tags_connect", "*", "WHERE con_tag_id='".$tag_id."' AND con_exp_id='".$exp_id."' ");
			if(empty($ckcon['con_id'])) { 
				insertSQL("ms_expenses_tags_connect", "con_tag_id='".$tag_id."', con_exp_id='".$exp_id."' ");
			}
		}
	}




	if(!empty($_REQUEST['exp_order'])) { 
		header("location: ../index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['exp_order']."");
	} else { 
		header("location: ../index.php?do=reports&action=expenses&exp_id=$exp_id");
	}
	session_write_close();
	exit();
}
?>


<?php
	if(($_REQUEST['exp_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$exp = doSQL("ms_expenses", "*", "WHERE exp_id='".$_REQUEST['exp_id']."' "); 
		if(empty($exp['exp_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($exp AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	} else { 
		$exp['exp_order'] = $_REQUEST['exp_order'];
	}
	?>
	<div class="pc"><?php if(empty($exp['exp_id'])) { ?><h1>Add Expense</h1><?php } else { ?>
	<h1>Edit Expense</h1>
		<?php } ?>
	</div>

	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Amount</div>
			<div><input type="text" name="exp_amount" id="exp_amount" class="optrequired" size="12" value="<?php print $exp['exp_amount'];?>" tabindex="1"></div>
		</div>
		<?php if(empty($exp['exp_date'])) { 
			$exp['exp_date'] = date('Y-m-d'); 
	}
	?>
		<div class="underline">
			<div class="label">Date</div>
			<div><input type="text" name="exp_date" id="exp_date" class="optrequired datepicker" size="12" value="<?php print $exp['exp_date'];?>" tabindex="2"></div>
		</div>

		<div class="underline">
			<div class="label">Reference</div>
			<div><input type="text" name="exp_reference" id="exp_reference" size="20" class="field100" value="<?php print $exp['exp_reference'];?>" tabindex="3"></div>
			<div>Transaction ID, check number, etc... (optional)</div>
		</div>
		<?php if($exp['exp_order'] > 0) { ?>
			<div class="underline">
			<div class="label">Order Number</div>
			<div><input type="text" name="exp_order" id="exp_order" size="12" class="" value="<?php print $exp['exp_order'];?>" tabindex="4"></div>
			<div></div>
		</div>
		<?php } ?>
		<div class="underline">
			<div class="label">Notes</div>
			<div><textarea name="exp_notes" id="exp_notes" class="field100" rows="4" cols="30"><?php print $exp['exp_notes'];?></textarea></div>
		</div>

	
	</div>
	<div style="width: 48%; float: right;">

	<div>
			<div class="">
				<div class="underlinelabel">Select from existing labels</div>
				<div>
				<?php 
				$tags = whileSQL("ms_expenses_tags", "*", "ORDER BY name ASC ");
				if(mysqli_num_rows($tags) <=0) { print "No labels have been created. You need to create at least one label."; } 
				while($tag = mysqli_fetch_array($tags)) { 
					$cktag = doSQL("ms_expenses_tags_connect", "*", "WHERE con_exp_id='".$exp['exp_id']."' AND con_tag_id='".$tag['tag_id']."' "); 	?>
				<div class="underline"><label for="e-tag-<?php print $tag['tag_id'];?>"><span id="span-tag-<?php print $tag['tag_id'];?>" class="<?php if(!empty($cktag['con_id'])) { print "tagselected"; } else { print "tagunselected"; }  ?>"><nobr><input type="checkbox" id="e-tag-<?php print $tag['tag_id'];?>" name="e_tags[<?php print $tag['tag_id'];?>]" value="<?php print $tag['tag_id'];?>" <?php if(!empty($cktag['con_id'])) { print "checked"; } ?> onclick="checkTag('<?php print $tag['tag_id'];?>');"> <?php print $tag['name'];?> </nobr></span></label>
				</div>
				<?php } ?>

				</div>
			<div>&nbsp;</div>

				<div class="underline">
				<div class="fieldLabel">Create new labels</div>
				<div><input type="text" name="new_tags" value="" size="20"  style="width: 98%;"></div>
				<div class="fieldDescription">Enter in new labels separated with a comma</div>
				</div>
		</div>
	<div>&nbsp;</div>
		</div>

	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>







	<div class="pageContent center">

	<input type="hidden" name="exp_id" value="<?php print $_REQUEST['exp_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editPeople">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['exp_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>


<?php require "../w-footer.php"; ?>
