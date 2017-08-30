<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$('#em_email').change(function() {
	checkemail();
});

function checkemail() { 
	$.get("admin.actions.php?action=checkemail&em_email="+$("#em_email").val()+"&em_id="+$("#em_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The email address "+$("#em_email").val()+" already exists for another account. Search for this account or enter in a different email address.");
			$("#em_email").val("")
		}
	});
}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#em_name").focus();
 }

</script>
<?php
if($_REQUEST['action'] == "deleteemail") { 
	$em = doSQL("ms_email_list", "*", "WHERE em_id='".$_REQUEST['em_id']."' "); 
	if(!empty($em['em_id'])) { 
		deleteSQL("ms_email_list", "WHERE em_id='".$em['em_id']."' ","1");
	}
	$_SESSION['sm'] = $em['em_email']." has been deleted";
	header("location: ../index.php?do=people&view=mailList");
	session_write_close();
	exit();

}


if($_POST['submitit']=="yes") { 
	if($_REQUEST['em_id'] > 0) { 

		updateSQL("ms_email_list", "
		em_name='".addslashes(stripslashes($_REQUEST['em_name']))."' , 
		em_last_name='".addslashes(stripslashes($_REQUEST['em_last_name']))."' 
		WHERE em_id='".$_REQUEST['em_id']."' ");
		$_SESSION['sm'] = "Saved";
		$em_id=$_REQUEST['em_id'];

	} else {
		$em_id = insertSQL("ms_email_list", "
		em_name='".addslashes(stripslashes($_REQUEST['em_name']))."' , 
		em_last_name='".addslashes(stripslashes($_REQUEST['em_last_name']))."' 
		");
		$_SESSION['sm'] = "Account Created";

	}
	header("location: ../index.php?do=people&view=mailList");
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['do'] == "editPeople") { 
	if(($_REQUEST['em_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$em = doSQL("ms_email_list", "*", "WHERE em_id='".$_REQUEST['em_id']."' "); 
		if(empty($em['em_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($em AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<div class="pc"><h1>Edit <?php print $em['em_email'];?></h1></div>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
	<div class="underline">
		<div class="label">First Name</div>
		<div><input type="text" name="em_name" id="em_name" class="field100" value="<?php print $em['em_name'];?>" tabindex="2"></div>
	</div>
	</div>
	<div style="width: 48%; float: right;">

	<div class="underline">
		<div class="label">Last Name</div>
		<div><input type="text" name="em_last_name" id="em_last_name" class="field100" value="<?php print $em['em_last_name'];?>" tabindex="3"></div>
	</div>


	<script>
	function showdelete() { 
		$("#deletesub").slideToggle(150);
	}
	</script>


	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>



	<div>&nbsp;</div>

	<div class="pageContent center">

	<input type="hidden" name="em_id" value="<?php print $_REQUEST['em_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editPeople">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['em_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php } ?>
<?php require "../w-footer.php"; ?>
