<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#note_note").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 
	if($_REQUEST['note_id'] > 0) { 
		$table = "ms_people";
		$table_id = $_REQUEST['p_id'];
		$message = trim($_REQUEST['note_note']);
		updateSQL("ms_notes","note_date='".currentdatetime()."', note_table='".$table."', note_table_id='".$table_id."', note_note='".addslashes(stripslashes($message))."', note_ip='".getUserIP()."', note_admin='1', note_is_note='1' WHERE note_id='".$_REQUEST['note_id']."' ");

	} else {

		$table = "ms_people";
		$table_id = $_REQUEST['p_id'];
		$message = trim($_REQUEST['note_note']);
		insertSQL("ms_notes","note_date='".currentdatetime()."', note_table='".$table."', note_table_id='".$table_id."', note_note='".addslashes(stripslashes($message))."', note_ip='".getUserIP()."', note_admin='1', note_is_note='1'  ");
	}
	$_SESSION['sm'] = "Note Saved";
	header("location: ../index.php?do=people&p_id=".$_REQUEST['p_id']."&view=notes");
	session_write_close();
	exit();
}
?>


<?php
	if(($_REQUEST['note_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$note = doSQL("ms_notes", "*", "WHERE note_id='".$_REQUEST['note_id']."' "); 
		if(empty($note['note_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
	}
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");

	?>
	<div class="pc"><?php if(empty($note['note_id'])) { ?><h1>Add Note</h1><?php } else { ?><h1>Edit Note</h1><?php } ?>



	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="pc"><textarea name="note_note" id="note_note" rows="3"><?php print $note['note_note'];?></textarea></div>
	<?php addEditor("note_note","1", "500", "0"); ?>
	
	

	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $p['p_id'];?>">
	<input type="hidden" name="note_id" value="<?php print $_REQUEST['note_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>
