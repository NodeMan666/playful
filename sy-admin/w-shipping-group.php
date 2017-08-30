<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>


<?php 
if($_REQUEST['action'] == "save") { 
	if(empty($_REQUEST['sg_name'])) {
		$error .= "<li>You did not enter a Shipping Method Name";
	}


	if(!empty($error)) {
		print "<div class=errorMessage>You have errors: <ul>$error</ul>Correct these errors and resubmit the form.</span></div><div>&nbsp;</div>";
		printForm();
	} else {



	if($_REQUEST['f'] == "EDIT") {
		$sg_id = updateSQL("ms_shipping_groups", "sg_name='".addslashes(stripslashes($_REQUEST['sg_name']))."'   WHERE sg_id='".$_REQUEST['sg_id']."' ");
		$sg_id = $_REQUEST['sg_id'];

	} else {
		$sg_id = insertSQL("ms_shipping_groups", "sg_name='".addslashes(stripslashes($_REQUEST['sg_name']))."'  ");

	}
	$_SESSION['sm'] = "Shipping group saved";
		?>
		<script>
		parent.window.location.href = 'index.php?do=settings&action=states&sg_id=<?php print $sg_id;?>';
		</script>
		<?php 
		exit();
	}
} else { 
	printForm();
}
?>

<?php 
function printForm() {
	global $_REQUEST,$tr;

	if((!empty($_REQUEST['sg_id']))AND(empty($_REQUEST['check']))==true) {
		$group = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$_REQUEST['sg_id']."' ");
		$_REQUEST['sg_name'] = $group['sg_name'];
		$_REQUEST['sg_default'] = $group['sg_default'];
		$_REQUEST['f'] = "EDIT";
	}
	
	?>
<div class="pc"><h1><?php if($_REQUEST['sg_id'] > 0) { ?>Edit Shipping Group<?php } else { ?>Add New Shipping Group<?php } ?></div>

	<form method="post" name="newLink" action="w-shipping-group.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
	<div>
	<div class="underline">
		<div class="label">Shipping Group Name</div>
		<div>
		<input type="text" name="sg_name" id="sg_name" size="40" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['sg_name']));?>" class="required">
		</div>
	</div>


	
	
	<div class="pc">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="f" value="<?php  print $_REQUEST['f'];?>">
	<input type="hidden" name="sg_id" value="<?php  print $_REQUEST['sg_id'];?>">
	<input type="hidden" name="noclose" value="1">
	<input type="hidden" name="nofonts" value="1">
	<input type="hidden" name="nojs" value="1">

	<input type="submit" name="submit" value="Save shipping option" class="submit"  id="submitButton"> 
	</div>
</div></form>
<?php  } ?>



	<div >&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>