<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>
<?php $email  = doSQL("ms_email_logs", "*,date_format(DATE_ADD(log_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS log_date", "WHERE log_id='".$_REQUEST['log_id']."' "); ?>
<div class="clear"></div>
<div class="underline">
	<div style="width: 20%;" class="left">From</div>
	<div style="width: 80%;" class="left"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_from']; } ?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div style="width: 20%;" class="left">To</div>
	<div style="width: 80%;" class="left"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_to']; } ;?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div style="width: 20%;" class="left">Date</div>
	<div style="width: 80%;" class="left"><?php print $email['log_date'];?></div>
	<div class="clear"></div>
</div>

<div class="underline">
	<div style="width: 20%;" class="left">Subject</div>
	<div style="width: 80%;" class="left"><?php print $email['log_subject'];?></div>
	<div class="clear"></div>
</div>
<div class="pc">
	<?php print $email['log_text'];?>
</div>

<?php require "w-footer.php"; ?>