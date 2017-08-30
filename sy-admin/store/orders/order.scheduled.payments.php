<div class="pc"><a href="index.php?do=orders">&larr; Orders</a></div>
<div class="pc newtitles"><span>Scheduled Invoice Payments</span></div> 
<div class="buttonsgray">
	<ul>
	<li><a href="index.php?do=orders&action=payments&status=unpaid">UPCOMING</a></li>
	<li><a href="index.php?do=orders&action=payments&status=paid">PAID</a></li>
	<li><a href="index.php?do=orders&action=payments&status=pastdue">PAST DUE</a></li>
	</ul>
</div>
<div class="clear"></div>

<div class="underlinecolumn">
	<div class="left p10">Invoice #</div>
	<div class="left p10">Amount</div>
	<div class="left p20">Name</div>
	<div class="left p10">Due Date</div>
	<div class="clear"></div>
</div>
<?php 
if($_REQUEST['status'] == "unpaid") { 
	$and_where = "AND payment<='0' ";
} 
if($_REQUEST['status'] == "paid") { 
	$and_where = "AND payment>'0' ";
} 
if($_REQUEST['status'] == "pastdue") { 
	$and_where = "AND payment<='0' AND due_date<'".date('Y-m-d')."' ";
} 


$scs = whileSQL("ms_payment_schedule LEFT JOIN ms_orders ON ms_payment_schedule.order_id=ms_orders.order_id", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE id>0 AND order_status<'2' $and_where ORDER BY due_date ASC ");
while($sc = mysqli_fetch_array($scs)) { ?>

<div class="underline">
	<div class="left p10"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $sc['order_id'];?>"><?php print $sc['order_id'];?></a></div>
	<div class="left p10"><?php print showPrice($sc['amount']);?></a></div>
	<div class="left p20"><a href="index.php?do=people&p_id=<?php print $sc['order_customer'];?>"><?php print $sc['order_first_name']." ".$sc['order_last_name'];?></a></div>
	<div class="left p20"><?php print $sc['due_date_show'];?></div>
	<div class="left p20">
	<?php if($sc['payment'] > 0) { ?>
	<span class="paid">PAID</span>
	<?php } else if($sc['due_date'] < date('Y-m-d')) { ?><span class="unpaid">PAST DUE</span>
	<?php } else { ?>Pending<?php } ?>
	</div>
	<div class="left p20"><?php if($sc['payment'] <= 0) { ?><a href="" onclick="invoiceemailreminder('<?php print $sc['id'];?>'); return false;"><span class="the-icons icon-mail"></span>Email Reminder</a><?php } ?>&nbsp;</div>

	<div class="clear"></div>
</div>
<?php } ?>