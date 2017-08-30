	<?php 
	$c[1] = "10%";
	$c[2] = "45%";
	$c[3] = "45%";
	$c[4] = "25%";
	$c[5] = "10%";
	?>

	<div id="emails">
		<div>
		<div class="underlinelabel">Email Log</div>
		<div class="underlinespacer">These are emails that have been sent to the email address in this account like order notifications, gallery invites, etc...</div>
		<?php $emails = whileSQL("ms_email_logs", "*,date_format(DATE_ADD(log_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS log_date", "WHERE (log_from='".$p['p_email']."' OR log_to='".$p['p_email']."') ORDER BY log_id DESC ");
		if(mysqli_num_rows($emails) <=0) { print "<div class=\"pc center\">No emails found</div>"; } 
			while($email = mysqli_fetch_array($emails)) { ?>
			<div class="underline">
			<div style="width: <?php print $c[1];?>; " class="left"><?php if($p['p_email'] == $email['log_from']) { print "From"; } else { print "To"; } ?></div>
			<div style="width: <?php print $c[2];?>; " class="left"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?></div>
			<div style="width: <?php print $c[3];?>; " class="right textright"><?php print $email['log_date'];?></div>
			<div class="clear"></div>
			<div class="sub"><a href=""  onclick="pagewindowedit('w-view-email.php?log_id=<?php print $email['log_id'];?>&noclose=1&nofonts=1','','1'); return false;" ><?php print $email['log_subject'];?></a></div>
			</div>
			<?php } ?>

		</div>
	</div>
