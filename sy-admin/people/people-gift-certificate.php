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
	if($_REQUEST['id'] > 0) { 

		updateSQL("ms_gift_certificates", "
		amount='".addslashes(stripslashes($_REQUEST['amount']))."' , 
		from_name='".addslashes(stripslashes($_REQUEST['from_name']))."' ,
		from_email='".addslashes(stripslashes($_REQUEST['from_email']))."',
		to_name='".addslashes(stripslashes($_REQUEST['to_name']))."' ,
		to_email='".addslashes(stripslashes($_REQUEST['to_email']))."',
		redeem_code='".addslashes(stripslashes($_REQUEST['redeem_code']))."',
		delivery_date='".addslashes(stripslashes($_REQUEST['delivery_date']))."',
		message='".addslashes(stripslashes($_REQUEST['message']))."'
		WHERE id='".$_REQUEST['id']."' ");
		$_SESSION['sm'] = "Gift Certificate Saved";
		$p_id=$_REQUEST['p_id'];

	} else {
		$id = insertSQL("ms_gift_certificates", "
		amount='".addslashes(stripslashes($_REQUEST['amount']))."' , 
		from_name='".addslashes(stripslashes($_REQUEST['from_name']))."' ,
		from_email='".addslashes(stripslashes($_REQUEST['from_email']))."',
		to_name='".addslashes(stripslashes($_REQUEST['to_name']))."' ,
		to_email='".addslashes(stripslashes($_REQUEST['to_email']))."',
		redeem_code='".addslashes(stripslashes($_REQUEST['redeem_code']))."',
		message='".addslashes(stripslashes($_REQUEST['message']))."',
		delivery_date='".addslashes(stripslashes($_REQUEST['delivery_date']))."',
		admin_created='1',
		date_purchased='".currentdatetime()."'
		");
		$_SESSION['sm'] = "Gift Certificate Created";

	}
	header("location: ../index.php?do=people&view=giftcertificates");
	session_write_close();
	exit();
}
?>

<?php 
	if($_REQUEST['id'] > 0) { 
		$gc = doSQL("ms_gift_certificates", "*", "WHERE id='".$_REQUEST['id']."' ");
	} else { 
		$gc['amount'] = "0.00";
		$gc['from_name'] = $site_setup['website_title'];
		$gc['from_email'] = $site_setup['contact_email'];

		$gc['redeem_code'] = date('Y').rand(1000000,9999999);
	}

	?>
	<div class="pc"><?php if(empty($gc['id'])) { ?><h1>Create eGift Card</h1><?php } else { ?><h1>Edit eGift Card</h1><?php } ?>
	<?php if($_REQUEST['id'] <=0 ) { ?><div class="pc">eGift Cards are designed to be purchased from your visitors to send to other people. You can create one here if you wish, but you would need to email the code to who you are wanting to receive it. Adding a credit to a customer's account will work the same way except they won't have to redeem a code.</div><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Amount</div>
			<div><input type="text" name="amount" id="amount" class="optrequired center inputtitle" value="<?php print $gc['amount'];?>" size="8"></div>
		</div>
	</div>
	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">Redeem Code</div>
			<div><input type="text" name="redeem_code" id="redeem_code" class="optrequired center inputtitle" value="<?php print $gc['redeem_code'];?>" size="20"></div>
		</div>
	</div>

	<div class="clear"></div>
	<div>&nbsp;</div>

	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">To Name</div>
			<div><input type="text" name="to_name" id="to_name" class="optrequired  field100" value="<?php print $gc['to_name'];?>" size="8"></div>
		</div>
	</div>
	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">To Email</div>
			<div><input type="text" name="to_email" id="to_email" class="optrequired  field100" value="<?php print $gc['to_email'];?>" size="8"></div>
		</div>
	</div>

	<div class="clear"></div>
	<div>&nbsp;</div>

	
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">From Name</div>
			<div><input type="text" name="from_name" id="from_name" class="optrequired  field100" value="<?php print $gc['from_name'];?>" size="20"></div>
		</div>
	</div>
	<div style="width: 48%; float: right;">
		<div class="underline">
			<div class="label">From Email</div>
			<div><input type="text" name="from_email" id="from_email" class="optrequired  field100" value="<?php print $gc['from_email'];?>" size="20"></div>
		</div>
	</div>

	<div class="clear"></div>
	<div>&nbsp;</div>

	<div class="underline">
		<div class="label">Message</div>
		<div><textarea name="message" id="message" rows="3" class="field100"><?php print $gc['message'];?></textarea></div>
	</div>

	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Delivery Date</div>
			<?php if(empty($gc['delivery_date'])) { $gc['delivery_date'] = date('Y-m-d'); } ?>
			<div><input type="text" name="delivery_date" id="delivery_date" class="optrequired  datepicker" value="<?php print $gc['delivery_date'];?>" size="10"></div>
		</div>
	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>


	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $_REQUEST['p_id'];?>">
	<input type="hidden" name="id" value="<?php print $_REQUEST['id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>