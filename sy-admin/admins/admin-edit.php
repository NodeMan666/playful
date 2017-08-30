<?php 
$path = "../../";
require "../w-header.php"; ?>
<?php


if($_POST['submitit']=="yes") { 
	if($_REQUEST['admin_id'] > 0) { 
		if($_REQUEST['admin_change_pass'] == "1") { 
		   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
			$salt = '';
			for ($i = 0; $i < 5; $i++) { 
				$salt .= $characters[mt_rand(0, 39)];
			}
			$password = md5($_REQUEST['admin_pass'].$salt);
			$add_pass = ", admin_pass='".addslashes(stripslashes($password))."', admin_salt='".addslashes(stripslashes($salt))."' ";
		}


		updateSQL("ms_admins", "
		admin_name='".addslashes(stripslashes($_REQUEST['admin_name']))."' , 
		admin_email='".addslashes(stripslashes($_REQUEST['admin_email']))."', 
		admin_user='".addslashes(stripslashes($_REQUEST['admin_user']))."',
		admin_full_access='".$_REQUEST['admin_full_access']."',
		design='".$_REQUEST['design']."',
		comments='".$_REQUEST['comments']."',
		forms='".$_REQUEST['forms']."',
		settings='".$_REQUEST['settings']."',
		orders='".$_REQUEST['orders']."',
		people='".$_REQUEST['people']."',
		photoprods='".$_REQUEST['photoprods']."',
		coupons='".$_REQUEST['coupons']."',
		stats='".$_REQUEST['stats']."',
		content='".$_REQUEST['content']."',
		contentpublish='".$_REQUEST['contentpublish']."',
		contentdelete='".$_REQUEST['contentdelete']."',
		uploadphotos='".$_REQUEST['uploadphotos']."',
		deletephotos='".$_REQUEST['deletephotos']."',
		reports='".$_REQUEST['reports']."',
		allphotos='".$_REQUEST['allphotos']."'

		$add_pass  
		WHERE admin_id='".$_REQUEST['admin_id']."' ");
		$color_id = $_REQUEST['color_id'];
	} else {

   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}


	$password = md5($_REQUEST['admin_pass'].$salt);

		$admin_id = insertSQL("ms_admins", "
		admin_name='".addslashes(stripslashes($_REQUEST['admin_name']))."', 
		admin_email='".addslashes(stripslashes($_REQUEST['admin_email']))."', 
		admin_user='".addslashes(stripslashes($_REQUEST['admin_user']))."' , 
		admin_pass='".addslashes(stripslashes($password))."' , 
		admin_salt='".addslashes(stripslashes($salt))."', 
		admin_full_access='".$_REQUEST['admin_full_access']."',

		design='".$_REQUEST['design']."',
		comments='".$_REQUEST['comments']."',
		forms='".$_REQUEST['forms']."',
		settings='".$_REQUEST['settings']."',
		orders='".$_REQUEST['orders']."',
		people='".$_REQUEST['people']."',
		photoprods='".$_REQUEST['photoprods']."',
		coupons='".$_REQUEST['coupons']."',
		stats='".$_REQUEST['stats']."',
		content='".$_REQUEST['content']."',
		contentpublish='".$_REQUEST['contentpublish']."',
		contentdelete='".$_REQUEST['contentdelete']."',
		uploadphotos='".$_REQUEST['uploadphotos']."',
		deletephotos='".$_REQUEST['deletephotos']."',
		reports='".$_REQUEST['reports']."',
		allphotos='".$_REQUEST['allphotos']."'


		");

	}
	$_SESSION['sm'] = "Administrator saved";
	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=admins");
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


<?php if($_REQUEST['do'] == "editAdmin") { 
	if(($_REQUEST['admin_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$admin = doSQL("ms_admins", "*", "WHERE admin_id='".$_REQUEST['admin_id']."' "); 
		if(($admin['admin_master'] == "1")AND($loggedin['admin_master'] !== "1")==true) { 
			print "<div class=\"error\">You do not have permission to edit this.</div>";
			die();
		}
		if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")&&($admin['admin_id'] !== $loggedin['admin_id'])==true) { 
			print "<div class=\"error\">You do not have permission to edit this.</div>";
			die();
		}
		if(empty($admin['admin_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($admin AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	} else { 
		if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")==true) { 
			print "<div class=\"error\">You do not have permission to edit this.</div>";
			die();
		}


	}
	
	?>
	<div class="pc"><?php if(empty($admin['admin_id'])) { ?><h1>Add Administrator</h1><?php } else { ?><h1>Edit Administrator</h1><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<div style="width: 48%; float: left;">
	<div class="underline">
		<div class="label">Name</div>
		<div><input type="text" name="admin_name" id="admin_name" class="optrequired field100" value="<?php print $admin['admin_name'];?>"></div>
	</div>

	<div class="underline">
		<div class="label">Email Address</div>
		<div><input type="text" name="admin_email" id="admin_email" class="optrequired field100" value="<?php print $admin['admin_email'];?>"></div>
	</div>

	</div>
	<div style="width: 48%; float: right;">

		<div class="underline">
		<div class="label">Username</div>
		<div><input type="text" name="admin_user" id="admin_user" class="optrequired field100" value="<?php print $admin['admin_user'];?>" autocomplete="off"></div>
	</div>
	<script>
	function changepass() { 
	if($("#admin_change_pass").attr("checked")) { 
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

	<?php if($admin['admin_id'] <=0) { ?>
	<div class="underline">
		<div class="label">Password</div>
		<div><input type="text" name="admin_pass" id="admin_pass" class="optrequired field100" value="" autocomplete="off"></div>
	</div>
	<?php } else { ?>
	<div class="underline">
	<div class="label"><input type="checkbox" name="admin_change_pass" id="admin_change_pass" value="1" onchange="changepass();"> <label for="admin_change_pass">Change Password</label></div>  
	<div id="changepass" style="display: none;">
		<div class="label">New Password</div>
		<div><input type="text" name="admin_pass" id="admin_pass" class="field100" value="" autocomplete="off"></div>
	</div>
	</div>
	<?php } ?>

	</div>
	<div class="clear"></div>
	<div>&nbsp;</div>
	<?php if($admin['admin_master'] == "1") { ?>
	<div class="pc center"><h3>Master Administrator</h3></div>
	<?php } else { ?>


	<div class="pc"><h3>Access</h3></div>
	<div class="underline"><input type="checkbox" name="admin_full_access" id="admin_full_access" value="1" onchange="adminaccess();" <?php if($admin['admin_full_access'] == "1") { print "checked"; } ?>> <label for="admin_full_access">Full Access - Check this box to have full access to the admin like the master administrator</label></div>	
	<div id="adminaccess" <?php if($admin['admin_full_access'] == "1") { print "style=\"display: none;\""; } ?>>
		<div class="pc"><h3>Select sections for this administrator to have access to</h3></div>
		<div style="width: 48%; float: left;">
			<div class="underline"><input type="checkbox" name="design" id="design" value="1" <?php if($admin['design'] == "1") { print "checked"; } ?>><label for="design"> Site Design</label></div>
			<div class="underline"><input type="checkbox" name="content" id="content" value="1" <?php if($admin['content'] == "1") { print "checked"; } ?>><label for="content"> Site Content</label></div>
			<div class="underline"><input type="checkbox" name="allphotos" id="allphotos" value="1" <?php if($admin['allphotos'] == "1") { print "checked"; } ?>><label for="allphotos"> All Photos</label></div>
			<div class="underline"><input type="checkbox" name="comments" id="comments" value="1" <?php if($admin['comments'] == "1") { print "checked"; } ?>><label for="comments"> Comments</label></div>
			<div class="underline"><input type="checkbox" name="forms" id="forms" value="1" <?php if($admin['forms'] == "1") { print "checked"; } ?>><label for="forms"> Forms</label></div>
			<div class="underline"><input type="checkbox" name="reports" id="reports" value="1" <?php if($admin['reports'] == "1") { print "checked"; } ?>><label for="forms"> Reports</label></div>
		</div>

		<div style="width: 48%; float: right;">
				<div class="underline"><input type="checkbox" name="settings" id="settings" value="1" <?php if($admin['settings'] == "1") { print "checked"; } ?>><label for="settings"> Settings</label></div>
			<div class="underline"><input type="checkbox" name="orders" id="orders" value="1" <?php if($admin['orders'] == "1") { print "checked"; } ?>><label for="orders"> Orders</label></div>
			<div class="underline"><input type="checkbox" name="people" id="people" value="1" <?php if($admin['people'] == "1") { print "checked"; } ?>><label for="people"> People</label></div>
			<div class="underline"><input type="checkbox" name="photoprods" id="photoprods" value="1" <?php if($admin['photoprods'] == "1") { print "checked"; } ?>><label for="photoprods"> Photo Products</label></div>
			<div class="underline"><input type="checkbox" name="coupons" id="coupons" value="1" <?php if($admin['coupons'] == "1") { print "checked"; } ?>><label for="coupons"> Coupons</label></div>
			<div class="underline"><input type="checkbox" name="stats" id="stats" value="1" <?php if($admin['stats'] == "1") { print "checked"; } ?>><label for="stats"> Stats</label></div>
		<!-- <div>
				<div class="underline" style="padding-left: 20px;"><input type="checkbox" name="contentpublish" id="contentpublish" value="1" <?php if($admin['contentpublish'] == "1") { print "checked"; } ?>> <label for="contentpublish"> Publish Content</label></div>
				<div class="underline" style="padding-left: 20px;"><input type="checkbox" name="contentdelete" id="contentdelete" value="1" <?php if($admin['contentdelete'] == "1") { print "checked"; } ?>> <label for="contentdelete"> Delete Content</label></div>
			</div>
			-->
			<!-- <div class="underline"><input type="checkbox" name="uploadphotos" id="uploadphotos" value="1" <?php if($admin['uploadphotos'] == "1") { print "checked"; } ?>><label for="uploadphotos"> Upload Photos</label></div>
			<div class="underline"><input type="checkbox" name="deletephotos" id="deletephotos" value="1" <?php if($admin['deletephotos'] == "1") { print "checked"; } ?>><label for="deletephotos"> Delete Photos</label></div>
			-->
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>

	<div class="pageContent center">

	<input type="hidden" name="admin_id" value="<?php print $_REQUEST['admin_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editAdmin">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['admin_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php } ?>

<?php require "../w-footer.php"; ?>