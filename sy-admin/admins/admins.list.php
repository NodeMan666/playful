<div id="pageTitle"><a href="index.php?do=admins">Administrators</a></div>
<script>
function editadmin(admin_id) { 
	pagewindowedit("<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/admins/admin-edit.php?do=editAdmin&noclose=1&nofonts=1&nojs=1&admin_id="+admin_id);
}
</script>
<?php if($_REQUEST['action'] == "deleteAdmin") { 
	$admin = doSQL("ms_admins", "*", "WHERE MD5(admin_id)='".$_REQUEST['admin_id']."' ");
	if(!empty($admin['admin_id'])) { 
		deleteSQL("ms_admins", "WHERE admin_id='".$admin['admin_id']."' ", "1");
		$_SESSION['sm'] = "Admin ".$admin['admin_user']." was deleted";
		session_write_close();
		header("location: index.php?do=admins");
	}
}
?>
<div class="pc buttons textright"><a href="" onclick="editadmin(); return false;">Add Administrator</a></div>
<div class="clear"></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div class="clear"></div>
<?php 
$c[1] = "20%";
$c[2] = "20%";
$c[3] = "20%";
$c[4] = "15%";
$c[5] = "15%";
$c[6] = "10%";

$NPvars = array("do=admins", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."" );
$sq_page = $pg * $per_page - $per_page;	

$total = countIt("ms_admins",  "WHERE admin_id>'0' $and_where "); 
?>


<div class="underlinecolumn">
	<div class="left" style="width: <?php print $c[6];?>">&nbsp;</div>
	<div class="left" style="width: <?php print $c[1];?>">Name</div>
	<div class="left" style="width: <?php print $c[2];?>">Email Address</div>
	<div class="left" style="width: <?php print $c[3];?>">Username</div>
	<div class="left" style="width: <?php print $c[4];?>">Access</div>
	<div class="left" style="width: <?php print $c[5];?>">Last Active</div>
	<div class="clear"></div>
</div>
<?php 
$admins = whileSQL("ms_admins", "*", "WHERE admin_id>'0' ORDER BY admin_name ASC"); 
if(mysqli_num_rows($admins) <=0) { ?><div class="pc center">
<h3>No administrators</h3>
<?php } ?>
</div>
<?php 
while($admin = mysqli_fetch_array($admins)) { 
	$ll = doSQL("ms_admin_logins", "*,date_format(DATE_ADD(date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS date_show", "WHERE log_admin_id='".$admin['admin_id']."' ORDER BY id DESC"); 

	?>
<div class="underline">
	<div class="left" style="width: <?php print $c[6];?>"><a href="" onClick="editadmin('<?php print $admin['admin_id'];?>'); return false;"><?php print ai_edit;?></a>
	<?php if($admin['admin_master'] == "0") { ?><a href="index.php?do=admins&action=deleteAdmin&admin_id=<?php print MD5($admin['admin_id']);?>"  class="confirmdelete" confirm-title="Delete Admin" confirm-message="Are you sure you want to delete this administrator? " ><?php print ai_delete;?></a><?php } ?>
	</div>

	<div class="left" style="width: <?php print $c[1];?>"><div><h2><a href="" onClick="editadmin('<?php print $admin['admin_id'];?>'); return false;"><?php print $admin['admin_name'];?></a></h2></div></div>
	<div class="left" style="width: <?php print $c[2];?>"><a href="" onClick="editadmin('<?php print $admin['admin_id'];?>'); return false;"><?php print $admin['admin_email'];?></a></div>
	<div class="left" style="width: <?php print $c[3];?>"><?php print $admin['admin_user'];?></div>
	<div class="left" style="width: <?php print $c[4];?>"><?php if($admin['admin_master'] == "1") { print "Master Admin"; } elseif($admin['admin_full_access'] == "1") { print "Full Access"; } else { print "Limited Access"; } ?></div>
	<div class="left" style="width: <?php print $c[5];?>"><a href="index.php?do=admins&view=logins&admin_id=<?php print $admin['admin_id'];?>"><?php print $ll['date_show'];?></a></div>
	

	<div class="clear"></div>
</div>

<?php } ?>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
