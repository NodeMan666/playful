<?php 
if(!empty($_REQUEST['deleteEmail'])) { 
	$em= doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['deleteEmail']."' ");
	if(!empty($em['email_id'])) { 
		deleteSQL("ms_emails", "WHERE email_id='".$em['email_id']."' ","1");
		updateSQL("ms_calendar", "prod_sep_order_email='0' WHERE prod_sep_order_email='".$em['email_id']."' ");
	}
	$_SESSION['sm'] = "Email Deleted";
	session_write_close();
	header("location: index.php?do=settings&action=defaultemails");
	exit();
}

if($_REQUEST['action'] == "editEmail") { 
	include "default.emails.edit.php";
} else { 
	listEmails();
}
?>

<?php function listEmails() { 
	global $site_setup;?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a> > <a href="index.php?do=settings&action=defaultemails">Default Emails</a></div>
<div class="buttonsgray">
	<ul>
	<li><a href="index.php?do=settings&action=defaultemailsedit">CREATE NEW DEFAULT EMAIL</a></li>
	<li><a href="index.php?do=settings&action=emailheader">EMAILS HEADER & FOOTER</a></li>
	<div class="clear"></div>
	</ul>
</div>

<div id="roundedFormContain">
<div class="pageContent">These are emails that are sent to the customer when they purchase.</div>


<?php $colsize = array("10","25","20", "45"); ?>

<?php $datas = whileSQL("ms_emails", "*", "WHERE email_id>'0' ORDER BY email_name  ASC  "); ?>
	<div class="roundedFormColContainer" >
			<div  class="roundedFormColLabel" style="width: <?php print $colsize[0];?>%;">Action</div>
			<div  class="roundedFormColLabel" style="width: <?php print $colsize[1];?>%;">Name</div>
			<div  class="roundedFormColLabel" style="width: <?php print $colsize[2];?>%;">From</div>
			<div class="roundedFormColLabel"  style="width: <?php print $colsize[3];?>%;">Description</div>
			<div  class="cssClear"></div>
		</div>
		<div id="">
		<?php
		while ($data = mysqli_fetch_array($datas)) {
			$totalLinks = mysqli_num_rows($datas);
			$rownum++;
			$thisLink++;
			?>
		<div class="underline">
			<div style="width: <?php print $colsize[0];?>%;" class="left"> 
			<a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $data['email_id'];?>" title="Edit Product"><?php print ai_edit;?></a> 
			<?php if($data['email_no_delete'] !== "1") { ?>
			<a href="index.php?do=settings&action=defaultemails&deleteEmail=<?php print $data['email_id'];?>"  onClick="return confirm('Are you sure you want to delete this email? ');" title="Delete Link"><?php print ai_delete;?></a>  
			<?php } ?>
		</div>
			<div style="width: <?php print $colsize[1];?>%; " class="left"><a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $data['email_id'];?>"><?php print $data['email_name'];?></a></div>
			<div style="width: <?php print $colsize[2];?>%; " class="left"><?php if(empty($data['email_from_email'])) { print $site_setup['contact_email']; } else { print $data['email_from_email']; } ?></div>
			<div style="width: <?php print $colsize[3];?>%; " class="left"><?php print $data['email_descr'];?></div>
			<div class="cssClear"></div>
		</div>

			<?php } ?>
		</div>

<div>&nbsp;</div>
</div>
<?php } ?>
