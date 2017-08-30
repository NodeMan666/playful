<script>
function deleteemail(id) { 
	$.get("admin.actions.php?action=deleteemail&log_id="+id+"", function(data) {
		$("#email-"+id).slideUp(200);
	});


}

</script>
<div id="pageTitle" class="left"><a href="index.php?do=stats">Stats</a> <?php print ai_sep;?> Email Logs
<?php

if($_REQUEST['action'] == "deleteemails") { 
	$thirty = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-$_REQUEST['when'], date("Y")));
	print "<li>".$thirty;
	$emails = whileSQL("ms_email_logs", "*", "WHERE   log_date<='".$thirty."'  ORDER BY log_id DESC" );
	print "<li>".mysqli_num_rows($emails);
	while($email = mysqli_fetch_array($emails)) { 
		 deleteSQL("ms_email_logs", "WHERE log_id='".$email['log_id']."' ","1");
		$dtotal++;
	}
	$_SESSION['sm'] = $dtotal." emails have been deleted";
	header("location: index.php?do=stats&view=emails");
	session_write_close();
	exit();
}



if(!empty($_REQUEST['q'])) {
	$_REQUEST['q'] = trim($_REQUEST['q']);
	$and_where .= "AND ( log_from LIKE '%".addslashes($_REQUEST['q'])."%'  OR   log_to LIKE '%".addslashes($_REQUEST['q'])."%'  ) ";
	print " ".ai_sep."  search for ".$_REQUEST['q']."";
}


?>
</div>
<div class="right">
	<div class="info">
		<form method="get" name="search" action="index.php" style="padding: 0px; margin: 0px;">
		<input type="hidden" name="do" value="stats">
		<input type="hidden" name="view" value="emails">
		<input type="text"  name="q" size="40" value="<?php  if(!empty($_REQUEST['q'])) {  print $_REQUEST['q']; } else { print "Search for email address";  } ?>" class="defaultfield" title="Search for email address" value="">
		<input type="submit" class="submit" name="submit" value="Search">
		</form>
	</div>
</div>
<div class="clear"></div>
<div class="right textright">Delete emails older than <a href="index.php?do=stats&view=emails&action=deleteemails&when=7">7</a>,  <a href="index.php?do=stats&view=emails&action=deleteemails&when=14">14</a>,  <a href="index.php?do=stats&view=emails&action=deleteemails&when=30">30</a>,  or <a href="index.php?do=stats&view=emails&action=deleteemails&when=60">60</a> days</div>
<div class="clear"></div>

<div class="cssClear">&nbsp;</div>
<div class=padTopBottom><div id="info">These are emails that are sent from or to the website.</div></div>



<?php
$emailtotal = countIt("ms_email_logs", "WHERE log_id>'0' $and_where "); 
if($emailtotal <= 0) { ?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div id="cssMainContainer">
		<div  class=cssRowContainer style="text-align:center;">No data found</div>
	</div>
<?php
} else  {
?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 20;
	$NPvars = array("do=stats", "view=emails", "q=".$_REQUEST['q']."");
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "3%";
	$cw2 = "3%";
	$cw3 = "3%";
	$cw4 = "20%";
	$cw5 = "20%";
	$cw6 = "20%";
	$cw7 = "30%";
	?>
	<div id="cssRowLabelContainer" >
			<div class="cssRowLabel"  style="width: <?php print $cw4;?>;"><B>From</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw5;?>;"><B>To</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw6;?>;"><B>Date</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw7;?>;"><B>Subject</B> (click to view)</div>
			<div  class="cssClear"></div>
		</div>

		<?php
		$emails = whileSQL("ms_email_logs", "*,date_format(DATE_ADD(log_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS log_date", "WHERE log_id>'0' $and_where ORDER BY log_id DESC LIMIT $sq_page,$per_page  ");
		while ($email = mysqli_fetch_array($emails)) {
			$fannum++;
			?>
			<div class="underline" id="email-<?php print $email['log_id'];?>">
				<div style="width: <?php print $cw4;?>;" class="left"><?php if($email['log_cron'] == "1") { ?><span style="background: #777777; padding: 2;" title="Sent Via Cron">&nbsp</span> <?php } ?><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_from']; } ; ?>&nbsp;</div>
				<div style="width: <?php print $cw5;?>;" class="left"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_to']; }  ?></div>
				<div style="width: <?php print $cw6;?>;" class="left"><?php print $email['log_date']; ?>&nbsp;</div>
				<div style="width: <?php print $cw7;?>;" class="left"><a href=""  onclick="pagewindowedit('w-view-email.php?log_id=<?php print $email['log_id'];?>&noclose=1&nofonts=1','','1'); return false;"><?php print $email['log_subject']; ?>&nbsp;</a></div>
				<div class="p10 left textright"><a href="" onclick="deleteemail('<?php print $email['log_id'];?>'); return false;" class="tip" title="<nobr>Delete from log</nobr>">delete</a></div>
				<div class="cssClear"></div>
			</div>
			<?php } ?>
		</div>
		</div>
	<?php 
	if($emailtotal > $per_page) {
		print "<center>".nextprevHTMLMenu($emailtotal, $pg, $per_page,  $NPvars, $_REQUEST)."</center>"; 
		}
?>
<?php } ?>
