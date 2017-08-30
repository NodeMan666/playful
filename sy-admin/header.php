<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" data-back-to="">
<div id="bodycontent">
 <?php if($site_setup['store_status'] == "1") {
		print "<div style=\"border-bottom:solid 1px #DBDB7F; background-color:#E3E3B3; width: 100%; padding: 4px; color: #000000;\" ><center>** Your Website is currently closed to the public. <a href=\"index.php?do=settings&action=status\">Click here to change status</a>**</center></div>";
	 }
?>

<script type="text/javascript">

function checkSearchForm(form) {
	  if (document.getElementById("searchall").search_where.value == "") {
			document.getElementById("search_error").style.display = 'inline';
			javascript:ajaxpage('message.page.php?ck_secure=<?php  print $_SESSION['secure_page'];?>&message=Please select where to search ', 'search_error');
			return false ;
		}
    Form=document.upitprev; 
	Form.submission.disabled = true;
	Form.submission.value = 'Uploading....';
	Form.submit();  

  return true ;
}
</script>



<?php 
	if($_SESSION['printview'] !== "on") {
?>
<div id="container">
	<div id="adminHeader">
		<div style="float: left; width:30%;"><?php if($setup['unbranded'] !== true) { ?><a href="index.php"><img src="graphics/sytistlogo.png" style="border: 0;"></a><?php } else { ?><div style=" padding-top: 16px; "><span style="font-size: 36px;color: #FFFFFF;">WEBSITE ADMIN</span></div><?php } ?></div>
		<div style="float: left; width:40%; text-align:center;" class="hidesmall">
			<div>&nbsp;</div>
	<?php 
		$thishour = date('G')+$site_setup['time_diff'];
		if($thishour<12) { 
			print "Good Morning"; 
		} elseif(($thishour>=12)AND($thishour<17)==true){
			print "Good Afternoon";
		} else {
			print "Good Evening";
		}
		print " ".$loggedin['admin_name'];

		?>
		&nbsp;&nbsp;&bull;&nbsp;&nbsp;
			<?php if($site_setup['index_page'] == "indexnew.php") { ?>
			<a href="<?php tempFolder(); ?>/indexnew.php" target="_blank">
			<?php } else { ?>
			 	<a href="<?php tempFolder(); ?>/" target="_blank">
			<?php } ?>
			View My Website</a>
			

		&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="logout.php">Log Out</a>  
		</div>

		<div style="float: left; width:30%; text-align: right;" class="hidesmall">
		<div>&nbsp;</div>
			<div>

		<?php if($site_setup['index_page'] == "indexnew.php") { ?><a href="index.php?do=activateSite"><b>Go Live</b></a>  &nbsp;&bull;&nbsp; <?php } ?>
	<?php if($setup['unbranded'] !== true) { ?>
	<a href="https://www.picturespro.com/sytist-manual/change-log/" target="_blank">Change Log</a> &nbsp;&bull;&nbsp; 
	 <a href="https://www.picturespro.com/sytist-manual/" target="_blank" title="Manual">Manual</a>  &nbsp;&bull;&nbsp;
	 <a href="https://www.picturespro.com/support-forum/sytist/" target="_blank" title="Support Forum">Support</a>  &nbsp;&bull;&nbsp;
	 <a href="https://www.picturespro.com/" target="_blank">PicturesPro.com</a> &nbsp;&bull;&nbsp;
	 <a href="http://www.facebook.com/4sytist/" target="_blank" title="Sytist Facebook Page" class="the-icons icon-facebook"></a>
	 <?php } ?>
	</div>
	<div>
<?php if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")==true) { ?>
<script>
function changepassword(admin_id) { 
	pagewindowedit("<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/admins/admin-change-password.php?do=editAdmin&noclose=1&nofonts=1&nojs=1&admin_id="+admin_id);
}
</script>
	<div><a href="" onClick="changepassword('<?php print $loggedin['admin_id'];?>'); return false;">Change My Info / Password</a></div>

<?php } else { ?>
<a href="index.php?do=admins">Administrators</a>
<?php } ?>
<?php if((!empty($site_setup['ftp_host_name']))&&(!empty($site_setup['ftp_user']))==true) { ?>
  &nbsp;&bull;&nbsp; <a href="ftp://<?php print $site_setup['ftp_user'];?>:<?php print $site_setup['ftp_pass'];?>@<?php print $site_setup['ftp_host_name'];?>/sy-upload/" target="_blank">FTP to sy-upload</a> 
<?php } ?>
</div>
			</div>
<div style="float: right" class="showsmall hide">
	<div id="mobilemenubutton" onclick="showmobilemenu();"><span class="the-icons icon-menu">MENU</span></div>
</div>
<div class="cssClear"></div>
</div>

<?php
		}
?>


<?php require "admin.top.menu.php"; ?>
