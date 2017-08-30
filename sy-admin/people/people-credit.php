<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>
<?php

if($_POST['submitit']=="yes") { 
	if($_REQUEST['credit_id'] > 0) { 

		updateSQL("ms_credits", "
		credit_amount='".addslashes(stripslashes($_REQUEST['credit_amount']))."' , 
		credit_expire='".addslashes(stripslashes($_REQUEST['credit_expire']))."' ,
		credit_notes='".addslashes(stripslashes($_REQUEST['credit_notes']))."',
		credit_reg_message='".addslashes(stripslashes($_REQUEST['credit_reg_message']))."',
		credit_reg_buyer_name='".addslashes(stripslashes($_REQUEST['credit_reg_buyer_name']))."'

		WHERE credit_id='".$_REQUEST['credit_id']."' ");
		$_SESSION['sm'] = "Credit Saved";
		$p_id=$_REQUEST['p_id'];

	} else {
		$credit_id = insertSQL("ms_credits", "
		credit_amount='".addslashes(stripslashes($_REQUEST['credit_amount']))."' , 
		credit_expire='".addslashes(stripslashes($_REQUEST['credit_expire']))."', 
		credit_customer='".addslashes(stripslashes($_REQUEST['p_id']))."' , 
		credit_notes='".addslashes(stripslashes($_REQUEST['credit_notes']))."' , 
		credit_date=NOW()
		");
		$_SESSION['sm'] = "Credit Created";

	}
	header("location: ../index.php?do=people&p_id=".$_REQUEST['p_id']."&view=credits");
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<?php if($_REQUEST['showDeleteSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Deleted");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>


<?php 
	if($_REQUEST['credit_id'] > 0) { 
		$credit = doSQL("ms_credits", "*", "WHERE credit_id='".$_REQUEST['credit_id']."' ");
	}
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' "); 
	if(empty($p['p_id'])) {
		showError("Sorry, but there seems to be an error.");
	}
	if($credit['credit_id'] <= 0) { 
		$credit['credit_amount'] = "0.00";
	}
	?>
	<div class="pc"><?php if(empty($credit['credit_id'])) { ?><h1>Create Credit For <?php print $p['p_name']." ".$p['p_last_name'];?></h1><?php } else { ?><h1>Edit Credit For <?php print $p['p_name']." ".$p['p_last_name'];?></h1><?php } ?>
	<div class="pc">Account credits are like money in a customer's account.  When a customer has a credit in their account, it will come off the order like a payment. If there is a credit balance after they place an order, it will remain in their account. </div>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">

	<div class="underline">
		<div class="label">Credit Amount</div>
		<div><input type="text" name="credit_amount" id="credit_amount" class="optrequired center" value="<?php print $credit['credit_amount'];?>" size="8" tabindex="1"></div>
	</div>


	</div>
	<div style="width: 48%; float: right;">
	<?php if($credit['credit_id'] <=0) { 
		$credit['credit_expire'] = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d") + 365, date("Y")));
	}
		?>

		<div class="underline">
			<div class="label">Expiration Date</div>
			<div><input type="text" name="credit_expire" id="credit_expire"  class="datepicker"  value="<?php print $credit['credit_expire'];?>" tabindex="2"></div>
		</div>

	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>

		<?php if($credit['credit_reg'] > 0) { ?>
		<div class="underline">
			<div class="label">Registry Purchaser Name</div>
			<div><input type="text" name="credit_reg_buyer_name" id="credit_reg_buyer_name"  class="field100"  value="<?php print $credit['credit_reg_buyer_name'];?>"></div>
		</div>


	<div class="underline">
		<div class="label">Registry Message</div>
		<div><textarea name="credit_reg_message" id="credit_reg_message" rows="3" class="field100"><?php print $credit['credit_reg_message'];?></textarea></div>
	</div>
	<?php } ?>


	<div class="underline">
		<div class="label">Notes</div>
		<div><textarea name="credit_notes" id="credit_notes" rows="3" class="field100"><?php print $credit['credit_notes'];?></textarea></div>
	</div>


	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $_REQUEST['p_id'];?>">
	<input type="hidden" name="credit_id" value="<?php print $_REQUEST['credit_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>