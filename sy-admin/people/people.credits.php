	<div id="credits">
	<div class="pc buttons textright"><a href="" onclick="editcredit('<?php print $p['p_id'];?>'); return false;">Add Account Credit</a> <a href="" onclick="addprintcredit('<?php print $p['p_id'];?>'); return false;">Add Print Credit</a></div>
	<div class="clear"></div>
		<div class="underlinelabel">Credits</div>

		<div class="underlinecolumn">			
			<div class="left" style="width: 10%;">&nbsp;</div>
			<div class="left" style="width: 15%;">Amount</div>
			<div class="left" style="width: 15%;">Date</div>
			<div class="left" style="width: 10%;">Order</div>
			<div class="left" style="width: 20%;">Expires</div>
			<div class="left" style="width: 30%;">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<?php $credits  = whileSQL("ms_credits", "*,date_format(DATE_ADD(credit_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS credit_date,date_format(DATE_ADD(credit_expire, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS credit_expire_show", "WHERE credit_customer='".$p['p_id']."' ORDER BY credit_id DESC ");
		if(mysqli_num_rows($credits) <= 0) { ?>
			<div class="pc center"><h3>No credits</h3></div>
		<?php } ?>
		<?php 
		while($credit = mysqli_fetch_array($credits)) { ?>
		<div class="underline">			
			<div style="width: 10%;" class="left"><a href="" onclick="editcredit('<?php print $p['p_id'];?>', '<?php print $credit['credit_id'];?>'); return false;"><?php print ai_edit;?></a> 

			<a  id="removealllink" class="confirmdelete" confirm-title="Delete Credit" confirm-message="Are you sure you want to delete this credit?" href="index.php?do=<?php print $_REQUEST['do'];?>&p_id=<?php print $p['p_id'];?>&action=deleteCredit&credit_id=<?php print $credit['credit_id'];?>" ><?php print ai_delete;?></a> 
			</div>

			<div style="width: 15%;" class="left"><h3><?php print showPrice($credit['credit_amount']);?></h3></div>
			<div style="width: 15%;" class="left"><?php print $credit['credit_date'];?></div>
			<div style="width: 10%;" class="left"><?php if($credit['credit_order'] > 0) { ?><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $credit['credit_order'];?>"><?php print $credit['credit_order'];?></a><?php } ?>&nbsp;</div>
			<div style="width: 20%;" class="left"><?php if(!empty($credit['credit_expire_show'])) { print "<span "; if($credit['credit_expire'] < date('Y-m-d')) { print "class=\"error\"";  } print ">".$credit['credit_expire_show']."</span>"; } ?>&nbsp;</div>


			<div style="width: 25%;" class="left">
			<?php if($credit['credit_reg'] > 0) { 
				$date = doSQL("ms_calendar", "*", "WHERE date_id='".$credit['credit_reg']."' ");
				?>
			<a href="index.php?do=news&action=addDate&date_id=<?php print $date['date_id'];?>" title="Registry"><?php print $date['date_title'];?></a>
			<?php } else { ?>
			&nbsp;
			<?php } ?>
			</div>
			<div class="clear"></div>
			<?php if((!empty($credit['credit_reg_message']))||(!empty($credit['credit_reg_buyer_name'])) == true) {
				print "<div class=\"pc\" style=\"padding-left: 10%;\">";
				if(!empty($credit['credit_reg_buyer_name'])) { print "<b>".$credit['credit_reg_buyer_name']."</b><br>"; } 
				if(!empty($credit['credit_reg_message'])) { print "<i>".nl2br($credit['credit_reg_message'])."</i>"; } 
				print "</div>"; 
			} 
			?>
			<?php if(!empty($credit['credit_notes'])) { print "<div class=\"pc\" style=\"padding-left: 10%;\">".nl2br($credit['credit_notes'])."</div>"; } ?>
		</div>
		<?php } ?>


	</div>

