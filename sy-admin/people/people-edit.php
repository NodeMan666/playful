<?php 
$path = "../../";
require "../w-header.php"; ?>
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
	if($_REQUEST['p_id'] > 0) { 
		if($_REQUEST['p_change_pass'] == "1") { 
		   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
			$salt = '';
			for ($i = 0; $i < 5; $i++) { 
				$salt .= $characters[mt_rand(0, 39)];
			}
			$password = md5($_REQUEST['p_pass'].$salt);
			$add_pass = ", p_pass='".addslashes(stripslashes($password))."', p_salt='".addslashes(stripslashes($salt))."', p_pass_def='".addslashes(stripslashes($_REQUEST['p_pass']))."' ";
		}


		updateSQL("ms_people", "
		p_name='".addslashes(stripslashes($_REQUEST['p_name']))."' , 
		p_last_name='".addslashes(stripslashes($_REQUEST['p_last_name']))."', 
		p_email='".addslashes(stripslashes($_REQUEST['p_email']))."',
		p_company='".addslashes(stripslashes($_REQUEST['p_company']))."',
		p_phone='".addslashes(stripslashes($_REQUEST['p_phone']))."',
		p_city='".addslashes(stripslashes($_REQUEST['p_city']))."',
		p_address1='".addslashes(stripslashes($_REQUEST['p_address1']))."',
		p_state='".addslashes(stripslashes($_REQUEST['p_state']))."',
		p_country='".addslashes(stripslashes($_REQUEST['p_country']))."',
		p_zip='".addslashes(stripslashes($_REQUEST['p_zip']))."'

		$add_pass  
		WHERE p_id='".$_REQUEST['p_id']."' ");
		$_SESSION['sm'] = "Account Saved";
		$p_id=$_REQUEST['p_id'];

	} else {

   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}


	$password = md5($_REQUEST['p_pass'].$salt);

		$p_id = insertSQL("ms_people", "
		p_pass='".addslashes(stripslashes($password))."' , 
		p_salt='".addslashes(stripslashes($salt))."', 
		p_name='".addslashes(stripslashes($_REQUEST['p_name']))."' , 
		p_last_name='".addslashes(stripslashes($_REQUEST['p_last_name']))."', 
		p_email='".addslashes(stripslashes($_REQUEST['p_email']))."',
		p_city='".addslashes(stripslashes($_REQUEST['p_city']))."',
		p_address1='".addslashes(stripslashes($_REQUEST['p_address1']))."',
		p_state='".addslashes(stripslashes($_REQUEST['p_state']))."',
		p_country='".addslashes(stripslashes($_REQUEST['p_country']))."',
		p_zip='".addslashes(stripslashes($_REQUEST['p_zip']))."',
		p_create_by='admin',
		p_date=NOW(),
		p_company='".addslashes(stripslashes($_REQUEST['p_company']))."',
		p_phone='".addslashes(stripslashes($_REQUEST['p_phone']))."',

		p_pass_def='".addslashes(stripslashes($_REQUEST['p_pass']))."'
		");
		$_SESSION['sm'] = "Account Created";

	}
	header("location: ../index.php?do=people&p_id=$p_id");
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


<?php if($_REQUEST['do'] == "editPeople") { 
	if(($_REQUEST['p_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' "); 
		if(empty($p['p_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($p AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	if($setup['demo_mode'] == true) { 
		$p['p_name'] = get_starred($p['p_name']);
		$p['p_last_name'] = get_starred($p['p_last_name']);
		$p['p_email'] = "demo@demo.mode";
	}

	?>
	<div class="pc"><?php if(empty($p['p_id'])) { ?><h1>Create Customer Account</h1><?php } else { ?><h1>Edit Customer</h1><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Company</div>
			<div><input type="text" name="p_company" id="p_company" class="field100" value="<?php print $p['p_company'];?>" tabindex="1"></div>
		</div>
	</div>
	<div class="clear"></div>
	
	
	<div style="width: 48%; float: left;">
	<div class="underline">
		<div class="label">First Name</div>
		<div><input type="text" name="p_name" id="p_name" class="optrequired field100" value="<?php print $p['p_name'];?>" tabindex="2"></div>
	</div>

	<div class="underline">
		<div class="label">Email Address</div>
		<div><input type="text" name="p_email" id="p_email" class="optrequired field100" value="<?php print $p['p_email'];?>" tabindex="4"></div>
		<div>Email address is also used to log in with.</div>
	</div>

	</div>
	<div style="width: 48%; float: right;">

	<div class="underline">
		<div class="label">Last Name</div>
		<div><input type="text" name="p_last_name" id="p_last_name" class="optrequired field100" value="<?php print $p['p_last_name'];?>" tabindex="3"></div>
	</div>

	<?php if($p['p_id'] <=0) { ?>
	<div class="underline">
		<div class="label">Password</div>
		<div><input type="text" name="p_pass" id="p_pass" class="optrequired field100" value="password" autocomplete="off"  tabindex="5"></div>
		<div>This is their default password. You should encourage them to change their password the first time they log in.</div>
	</div>
	<?php } else { ?>
	<div class="underline">
	<div class="label"><input type="checkbox" name="p_change_pass" id="p_change_pass" value="1" onchange="changepass();"> <label for="p_change_pass">Change Password</label></div>  
	<div id="changepass" style="display: none;">
		<div class="label">New Password</div>
		<div><input type="text" name="p_pass" id="p_pass" class="field100" value="" autocomplete="off"></div>
		<div>If you change the password here, you will need to notify them what the new password is.</div>
	</div>
	</div>
	<?php } ?>


	<script>
	function changepass() { 
	if($("#p_change_pass").attr("checked")) { 
			$("#changepass").slideDown(200);
			$("#admin_pass").addClass("optrequired");
		} else { 
			$("#changepass").slideUp(200);
			$("#admin_pass").removeClass("optrequired");
		}
	}

	function adminaccess() { 
	if($("#admin_full_access").attr("checked")) { 
			$("#adminaccess").slideUp(200);
		} else { 
			$("#adminaccess").slideDown(200);
		}
	}

	</script>


	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>


	<div style="width: 48%; float: left;">
		<div class="underline">
			<div class="label">Address</div>
			<div><input type="text" name="p_address1" id="p_address1" class="field100" value="<?php print $p['p_address1'];?>"  tabindex="6"></div>
		</div>

		<div class="underline">
			<div class="label">State</div>
			<div>		
			<select name="p_state" id="p_state" class=""  tabindex="8">
			<option value="">Select State</option>
			<?php $states = whileSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.ship_to='1' AND ms_states.state_ship_to='1' ORDER BY def DESC, country_name, state_name ASC ");
			while($state = mysqli_fetch_array($states)) { ?>
			<option value="<?php print $state['state_abr'];?>" <?php if($p['p_state'] == $state['state_abr']) { print "selected"; } ?>><?php print $state['state_name']." (".$state['abr'].")"; ?></option>
			<?php } ?>
			</select>
			</div>
		</div>

		<div class="underline">
			<div class="label">Zip Code</div>
			<div><input type="text" name="p_zip" id="p_zip" class="" size="10" value="<?php print $p['p_zip'];?>" tabindex="10"></div>
		</div>

	</div>
	<div style="width: 48%; float: right;">

		<div class="underline">
			<div class="label">City</div>
			<div><input type="text" name="p_city" id="p_city" class="field100" value="<?php print $p['p_city'];?>" tabindex="7"></div>
		</div>


		<div class="underline">
			<div class="label">Country</div>
			<div>		
			<select name="p_country"  id="p_country"  class=""  tabindex="9">
			<?php
			$cts = whileSQL("ms_countries", "*", " WHERE ship_to='1' ORDER BY def DESC, country_name ASC");

			while($ct = mysqli_fetch_array($cts)) {
				print "<option value=\"".$ct['country_name']."\" "; if($person['p_country'] == $ct['country_name']) { print " selected"; } print ">".$ct['country_name']."</option>";
			}
			print "</select>";
			?>
			</div>
		</div>
		<div class="underline">
			<div class="label">Phone</div>
			<div><input type="text" name="p_phone" id="p_phone" class="field100" value="<?php print $p['p_phone'];?>" tabindex="11"></div>
		</div>


	</div>
	<div class="clear"></div>








	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $_REQUEST['p_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editPeople">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php } ?>
<?php require "../w-footer.php"; ?>
