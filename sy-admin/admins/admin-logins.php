<div id="pageTitle"><a href="index.php?do=admins">Administrators</a> <?php print ai_sep;?> Log in logs


<?php
if(!empty($_REQUEST['admin_id'])) {
	$admin = doSQL("ms_admins", "*", "WHERE admin_id='".$_REQUEST['admin_id']."' ");
	$and_where .= "AND log_admin_id='".$_REQUEST['admin_id']."' ";
	print " ".ai_sep." ".$admin['admin_name'];
}
if($_REQUEST['failed'] == "1") { 
	$and_where .= " AND login_failed='1' ";
} else { 
	$and_where .= "AND login_failed='0' ";
}

?>
</div>
<?php 
if($_REQUEST['admin_id'] <=0) { 
$failed = countIt("ms_admin_logins", "WHERE login_failed='1' ");
if($failed > 0) { ?>
<div class="pc buttons textright"><a href="index.php?do=admins&view=logins&failed=1">Failed Logins (<?php print $failed;?>)</a></div>
<div class="clear"></div>
<div>&nbsp;</div>
<?php }
}?>

<?php 
$c[1] = "20%";
$c[2] = "20%";
$c[3] = "20%";
$c[4] = "20%";
$c[5] = "20%";
$c[6] = "10%";
if(empty($_REQUEST['acdc'])) { 
	$acdc = "DESC";
	$oposit = "ASC";
} else { 
	$acdc = $_REQUEST['acdc'];
	if($acdc == "ASC") { 
		$oposit = "DESC";
	}
	if($acdc == "DESC") { 
		$oposit = "ASC";
	}

}
if(empty($_REQUEST['orderby'])) { 
	$orderby = "id";
} else { 
	$orderby = $_REQUEST['orderby'];
}
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}

$per_page = 20;
$NPvars = array("do=admins", "view=".$_REQUEST['view']."","failed=".$_REQUEST['failed']."","orderby=".$orderby."", "acdc=".$acdc."" );
$sq_page = $pg * $per_page - $per_page;	

?>
<div class="underlinecolumn">
	<div class="left" style="width: <?php print $c[1];?>"><?php if($_REQUEST['failed'] !== "1") { print "Name"; } ?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[2];?>">Username</div>
	<div class="left" style="width: <?php print $c[3];?>">IP Address</div>
	<div class="left" style="width: <?php print $c[4];?>">Date</div>
	<div class="left" style="width: <?php print $c[5];?>"><?php if($_REQUEST['failed'] == "1") { print "Password Tried"; } ?>&nbsp;</div>
	<div class="clear"></div>
</div>
<?php 
$total = countIt("ms_admin_logins LEFT JOIN ms_admins ON ms_admin_logins.log_admin_id=ms_admins.admin_id", "WHERE id>'0' $and_where ORDER BY $orderby $acdc "); 

$admins = whileSQL("ms_admin_logins LEFT JOIN ms_admins ON ms_admin_logins.log_admin_id=ms_admins.admin_id", "*,date_format(DATE_ADD(date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS date_show", "WHERE id>'0' $and_where ORDER BY $orderby $acdc LIMIT $sq_page,$per_page "); 
if(mysqli_num_rows($admins) <=0) { ?><div class="pc center">
<h3>No data</h3>
<?php } ?>
<?php 
while($admin = mysqli_fetch_array($admins)) { 
	?>
<div class="underline">
	<div class="left" style="width: <?php print $c[1];?>"><?php print $admin['admin_name'];?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[2];?>"><?php print $admin['user'];?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[3];?>"><?php print $admin['ip'];;?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[4];?>"><?php print $admin['date_show'];?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[5];?>"><?php print $admin['login_failed_pass'];?>&nbsp;</div>

	<div class="clear"></div>
</div>

<?php } ?>
<div>&nbsp;<div>
<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<div>&nbsp;<div>
<div>&nbsp;<div>
