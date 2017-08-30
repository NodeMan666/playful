<?php 
$path = "../../";
require "../w-header.php";
$email_style = true;

?>
<script>

$('#p_email').change(function() {
	checkemail();
});

function checkemail() { 
	$.get("admin.actions.php?action=checkemail&p_email="+$("#p_email").val()+"&p_id="+$("#p_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The email address "+$("#p_email").val()+" already exists for another account. Search for this account or enter in a different email address.");
			$("#p_email").val("")
		}
	});
}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#p_name").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 
	if($_REQUEST['contract_id'] > 0) { 
		updateSQL("ms_contracts", "
		title='".addslashes(stripslashes($_REQUEST['title']))."',
		due_date='".addslashes(stripslashes($_REQUEST['due_date']))."',
		content='".addslashes(stripslashes($_REQUEST['content']))."',
		my_name='".addslashes(stripslashes($_REQUEST['my_name']))."',
		person_id='".addslashes(stripslashes($_REQUEST['person_id']))."',
		first_name='".addslashes(stripslashes($_REQUEST['first_name']))."',
		last_name='".addslashes(stripslashes($_REQUEST['last_name']))."',
		signature_name='".addslashes(stripslashes(trim($_REQUEST['signature_name'])))."',
		signature_name2='".addslashes(stripslashes(trim($_REQUEST['signature_name2'])))."',
		email='".addslashes(stripslashes(trim($_REQUEST['email'])))."',
		email2='".addslashes(stripslashes(trim($_REQUEST['email2'])))."',
		pin='".$_REQUEST['pin']."',
		template='1',
		signature2='',
		signed_date='',
		signed_date2='',
		ip_address='',
		ip_address2='',
		browser_info='',
		browser_info2='',
		my_signature='',
		my_signature_svg='',
		my_signed_date='',
		signature_svg='',
		signature2_svg='',
		last_modified='".currentdatetime()."'

		WHERE contract_id='".$_REQUEST['contract_id']."' ");
		$_SESSION['sm'] = "Account Saved";
		$p_id=$_REQUEST['p_id'];
		$contract_id = $_REQUEST['contract_id'];
	} else {

	   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
		$salt = '';
		for ($i = 0; $i < 5; $i++) { 
			$salt .= $characters[mt_rand(0, 39)];
		}
		$link = md5(date('Ymdhis').$salt);



		$contract_id =	insertSQL("ms_contracts", "
		title='".addslashes(stripslashes($_REQUEST['title']))."',
		due_date='".addslashes(stripslashes($_REQUEST['due_date']))."',
		content='".addslashes(stripslashes($_REQUEST['content']))."',
		my_name='".addslashes(stripslashes($_REQUEST['my_name']))."',
		person_id='".addslashes(stripslashes($_REQUEST['person_id']))."',
		first_name='".addslashes(stripslashes($_REQUEST['first_name']))."',
		last_name='".addslashes(stripslashes($_REQUEST['last_name']))."',
		signature_name='".addslashes(stripslashes(trim($_REQUEST['signature_name'])))."',
		signature_name2='".addslashes(stripslashes(trim($_REQUEST['signature_name2'])))."',
		email='".addslashes(stripslashes(trim($_REQUEST['email'])))."',
		email2='".addslashes(stripslashes(trim($_REQUEST['email2'])))."',
		link='".$link."',
		pin='".$_REQUEST['pin']."',
		template='1',

		last_modified='".currentdatetime()."'
		");

	}
	$_SESSION['sm'] = "Contract Template Saved";
	header("location: ../index.php?do=people&p_id=".$_REQUEST['p_id']."&view=allcontracts&sub=templates");
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
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

function getTemplate() { 
	if($("#get_template").val() > 0) { 
		$.get("admin.actions.php?action=contractTemplate&contract_id="+$("#get_template").val()+"", function(data) {
			info = data.split("||||");
			$('#content').redactor('code.set', info[2]);
			$("#title").val(info[0]);
			$("#my_name").val(info[1]);
			// $('.redactor-editor').redactor(data);
			// $("#content").html(data);
		});
	}
}
</script>

<?php
	if(($_REQUEST['contract_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$contract = doSQL("ms_contracts", "*", "WHERE contract_id='".$_REQUEST['contract_id']."' "); 
		if(empty($contract['contract_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		if($contract['person_id'] > 0) { 
			$p = doSQL("ms_people", "*", "WHERE p_id='".$contract['person_id']."' ");
			foreach($p AS $id => $value) {
				if(!is_numeric($id)) {
					$_REQUEST[$id] = $value;
				}
			}
		}
	} else { 
		$contract['due_date'] = date('Y-m-d');
	}
	if($_REQUEST['p_id'] > 0) { 
		$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
		if($_REQUEST['contract_id'] <= 0) { 
			$contract['signature_name'] = $p['p_name']." ".$p['p_last_name'];
			$contract['email'] = $p['p_email'];
		}
	}
	if($setup['demo_mode'] == true) { 
		$p['p_name'] = get_starred($p['p_name']);
		$p['p_last_name'] = get_starred($p['p_last_name']);
		$p['p_email'] = "demo@demo.mode";
	}

	?>
	<div class="pc"><?php if(empty($p['p_id'])) { ?><h1>Create Contract Template</h1><?php } else { ?><h1>Edit Contract Template</h1><?php } ?>


	<?php if((!empty($contract['signature'])) || (!empty($contract['signature2'])) || (!empty($contract['signature_svg'])) || (!empty($contract['signature2_svg'])) == true) { ?>
	<div class="error center"><span class="the-icons icon-attention" style="color: #FFFFFF;"></span> Editing this contract will remove all signatures and will have to be resigned!</div>
	<?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
		<div class="underline">
			<div class="left p50">
				<div class="label">Title</div>
					<div><input type="text" name="title" id="title" class="field100 optrequired" value="<?php print $contract['title'];?>" tabindex="1"></div>
				</div>
				<!-- 
				<div class="left p50">
					<div class="label">Templates</div>
					<div>
					<select name="get_template" id="get_template" onchange="getTemplate(); return false;">
					<option value="">Select</option>
					<?php $temps = whileSQL("ms_contracts", "*", "WHERE template='1' ORDER BY title ASC ");
					if(mysqli_num_rows($temps) <= 0 ) { ?>
					<option value="" disabled>No templates created</option>
					<?php } ?>
					<?php while($temp = mysqli_fetch_array($temps)) { ?>
					<option value="<?php print $temp['contract_id'];?>"><?php print $temp['title'];?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			-->
			<div class="clear"></div>
		</div>


		<div class="underline">

			<div class="left p50">
				<div class="label">My Name</div>
				<div><input type="text" name="my_name" id="my_name" class="field100 optrequired" value="<?php print $contract['my_name'];?>" tabindex="1"></div>
			</div>
			<div class="clear"></div>

		</div>


		<div class="underline">
			<div class="label">Contract</div>
			<div><textarea  name="content" id="content"   class="formfield field100"><?php print $contract['content'];?></textarea></div>
		</div>
		<?php addEditor("content","1", "500", "0"); ?>
	<div>&nbsp;</div>
	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $p['p_id'];?>">
	<input type="hidden" name="person_id" value="<?php print $p['p_id'];?>">
	<input type="hidden" name="contract_id" value="<?php print $_REQUEST['contract_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

		<div style="width: 100%; border-bottom: solid 1px #bbbbbb;">&nbsp;</div>
		<div class="underlinespacer">
		You can enter in the following bracket codes which will be automatically replaced in the contract.
		<ul>
		<li>[NAME] = The person's name</li>
		<li>[NAME2] = The second person's name</li>
		<li>[MY_NAME] = Your name</li>
		</ul>
		</div>
	
	<div class="underlinespacer">
	<p><b>Adding Form Fields</b></p>
<p>You can add form fields to this contract to be filled out by the customer who is signing the contract. To add these form fields, add the bracket codes below in the content of the contract where you want them to appear. </p>

<p>[TEXT_INPUT_OPTIONAL]<br>
This will be replaced with an optional form field to be filled out with the size of 20.
</p>
<p>[TEXT_INPUT_REQUIRED]<br>
This will be replaced with a REQUIRED form field to be filled out with the size of 20.
</p>
<p>[TEXT_INPUT_SHORT_OPTIONAL]<br>
This will be replaced with a small optional form field to be filled out with the size of 4 (initials for example).
</p>
<p>[TEXT_INPUT_SHORT_REQUIRED]<br>
This will be replaced with a small REQUIRED form field to be filled out with the size of 4 (initials for example).
</p>
<p>[CHECKBOX_OPTIONAL]<br>
This will be replaced with a checkbox that is optional.
</p>
<p>[CHECKBOX_REQUIRED]<br>
This will be replaced with a REQUIRED checkbox.
</p>
</div>



	</form>
<?php require "../w-footer.php"; ?>
