<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>

<?php
$_REQUEST['rsk'] = sql_safe("".$_REQUEST['rsk']."");


if(!empty($_REQUEST['submitit'])) {
	$_REQUEST['reset_pin'] = trim($_REQUEST['reset_pin']);
	$_REQUEST['reset_pin'] = sql_safe("".$_REQUEST['reset_pin']."");
	if(empty($_REQUEST['reset_pin'])) { 
		$error .= "<div>The pin number is empty</div>";
	}
	if(empty($_REQUEST['new_pass'])) { 
		$error .= "<div>Password is empty</div>";
	}
	if(empty($_REQUEST['new_pass2'])) { 
		$error .= "<div>Connfirm password is empty</div>";
	}
	if(empty($error)) { 
		if($_REQUEST['new_pass'] !== $_REQUEST['new_pass2']) { 
			$error .= "<div>Confirm password did not match your password. Please enter them again</div>";
		}
	}	
	if(empty($error)) { 
		$reset = doSQL("ms_admin_reset", "*", "WHERE reset_code='".$_REQUEST['rsk']."' AND reset_pin='".md5($_REQUEST['reset_pin'])."' ");
		if(empty($reset['reset_id'])) { 
			$error .= "<div>Pin number is incorrect.</div>";
		} 
	}

	if(empty($error)) { 
		$admin = doSQL("ms_admins", "*", "WHERE admin_id='".$reset['reset_admin']."' ");
		if(empty($admin['admin_id'])) { 
			die("<div class=\"error\">An unexpected error has occured [nam]</div>");
		}

		$characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
		$salt = '';
		for ($i = 0; $i < 5; $i++) { 
			$salt .= $characters[mt_rand(0, 39)];
		}
		$password = md5($_REQUEST['new_pass'].$salt);
		updateSQL("ms_admins", "admin_pass='".addslashes(stripslashes($password))."', admin_salt='".addslashes(stripslashes($salt))."' WHERE admin_id='".$admin['admin_id']."' ");
		deleteSQL("ms_admin_reset", "WHERE reset_id='".$reset['reset_id']."'", "1");
		$_SESSION['sm'] = "Your password has been updated";
		session_write_close();
		header("location: index.php");
		exit();


	}
} 
?>
<div style="margin:auto; width: 480px; ">
	<div style="padding: 40px 0 0 40px;">
	<?php if(!empty($error)) { ?>
	<div  class="error"><?php print $error;?></div>
	<div>&nbsp;</div>
	<?php } ?>
	<div class="pc"><h1>Reset Password</h1></span></div>
	
	<?php 
	$reset = doSQL("ms_admin_reset", "*", "WHERE reset_code='".$_REQUEST['rsk']."' ");
	if(empty($reset['reset_id'])) { ?>
	<div class="error">That reset code is not found. Make sure the URL is not cut off in the email.</div>
	<?php } else {
	$admin = doSQL("ms_admins", "*", "WHERE admin_id='".$reset['reset_admin']."' ");
	?>
	<form method="post" name="reset" action="index.php"   onSubmit="return checkForm('.required');">
	<div class="pc"><h2>Your username: <b><?php print $admin['admin_user'];?></b></h2></div>
	<div class="underline">
		<div class="label">New Password</div>
		<div><input type="password" name="new_pass" id="new_pass" class="field100 required"  autocomplete="off"></div>
	</div>
	<div class="underline">
		<div class="label">Confirm Password</div>
		<div><input type="password" name="new_pass2" id="new_pass2" class="field100 required"  autocomplete="off"></div>
	</div>
	<div class="underline">
		<div class="label">Pin number from the email sent to you</div>
		<div><input type="text" name="reset_pin" id="reset_pin" class="required"  autocomplete="off" size="6"></div>
	</div>


	<div class="pc center">
		<input type="hidden" name="rsk" id="rsk" value="<?php print $_REQUEST['rsk'];?>">
		<input type="hidden" name="submitit" id="submitit" value="yes">
		<input type="hidden" name="do" id="do" value="reset">
		<div><input type="submit" name="submit" class="submit" value="Save"></div>
	</div>


	</form>
	<?php } ?>