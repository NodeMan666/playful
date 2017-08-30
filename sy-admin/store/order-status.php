<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#status_name").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 
	if($_REQUEST['status_id'] > 0) { 
		updateSQL("ms_order_status", "
		status_name='".addslashes(stripslashes($_REQUEST['status_name']))."', 
		status_show_order='".addslashes(stripslashes($_REQUEST['status_show_order']))."',
		status_descr='".addslashes(stripslashes($_REQUEST['status_descr']))."' 
		WHERE status_id='".$_REQUEST['status_id']."' ");
		$_SESSION['sm'] = "Status Updated";
		$status_id=$_REQUEST['order_id'];
		header("location: ../index.php?do=orders");
		session_write_close();
		exit();
	} else { 

		$status_id = insertSQL("ms_order_status", "
		status_name='".addslashes(stripslashes($_REQUEST['status_name']))."',
		status_show_order='".addslashes(stripslashes($_REQUEST['status_show_order']))."',
		status_descr='".addslashes(stripslashes($_REQUEST['status_descr']))."' 
		");

		if(!empty($_REQUEST['order_id'])) { 
			updateSQL("ms_orders",  "order_open_status='$status_id' WHERE order_id='".$_REQUEST['order_id']."' ");
		}
		$_SESSION['sm'] = "Status created & applied";
		header("location: ../index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']."");
		session_write_close();
		exit();
	}

}
?>

<script>
function selectsection(id) { 
	$("#"+id).slideToggle(200);
}

</script>

<?php
	if(($_REQUEST['status_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$status = doSQL("ms_order_status", "*", "WHERE status_id='".$_REQUEST['status_id']."' "); 
		if(empty($status['status_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($status AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<div class="pc"><h1>Order Status </h1>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div class="underline">
		<div class="label">Enter status name</div>
		<div><input type="text" name="status_name" id="status_name" class="optrequired field100" value="<?php print $status['status_name'];?>" tabindex="1"></div>
	</div>
	<!-- 
	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="status_descr" id="status_descr" class="field100" cols="3" tabindex="2"><?php print htmlspecialchars($status['status_descr']);?></textarea></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="status_show_order" value="1"  <?php if($status['status_show_order'] == "1") { print "checked"; } ?>> Show this status on the customer order</div>
	</div>

	-->


	<div class="pageContent center">

	<input type="hidden" name="status_id" value="<?php print $_REQUEST['status_id'];?>">
	<input type="hidden" name="order_id" value="<?php print $_REQUEST['order_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['order_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>

<?php require "../w-footer.php"; ?>