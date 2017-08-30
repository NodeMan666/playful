<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id LEFT JOIN ms_cart ON ms_bookings.book_id=ms_cart.cart_booking LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' AND (order_id IS NULL OR (order_id>'0' AND order_invoice='0'))  GROUP BY book_id   ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { 
	$tt++;
	?>
	<div class="notice">
	<ul>
		<li><a href="" onclick="viewday('0','0','unconfirmed'); return false;">(<?php print mysqli_num_rows($books);?>) Unconfirmed Booking Request<?php if(mysqli_num_rows($books)>1) { print "s"; } ?></a></li>
	</ul>
	</div>
<?php } ?>



<?php 

	$dow = date("l", mktime(0, 0, 0, date('m'), date('d'), date('Y'))); 
	
	$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   ((MONTH(book_date)='".date('m')."' AND DAYOFMONTH(book_date)='".date('d')."' )   OR book_recurring_dow='".$dow."' OR book_recurring_dom='".date('d')."' ) AND book_confirmed='2'  GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { ?>

<div class="notice">
	<ul>
		<li><a href="" onclick="viewday('0','<?php print date('Y')."-".date('m')."-".date('d');?>','0'); return false;">(<?php print mysqli_num_rows($books);?>) Event<?php if(mysqli_num_rows($books)>1) { print "s"; } ?> Today</a></li>
	</ul>
</div>
<?php } ?>

<?php
$tm = date("m", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 
$td = date("d", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 
$ty = date("Y", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 
$dow = date("l", mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'))); 

 

$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   ((MONTH(book_date)='".$tm."' AND DAYOFMONTH(book_date)='".$td."' )   OR book_recurring_dow='".$dow."' OR book_recurring_dom='".$td."' ) AND book_confirmed='2'   GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");

if(mysqli_num_rows($books) > 0) {
	?>
<div class="notice">
	<ul>
		<li><a href="" onclick="viewday('0','<?php print $ty."-".$tm."-".$td;?>','0'); return false;">(<?php print mysqli_num_rows($books);?>) Event<?php if(mysqli_num_rows($books)>1) { print "s"; } ?> Tomorrow</a></li>
	</ul>
</div>
<?php } ?>




<?php 
$eday  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+3, date("Y")));
$exp = whileSQL("ms_calendar", "*", "WHERE date_expire!='0000-00-00' AND date_expire>=NOW() AND date_expire <='".$eday."' ");
if(mysqli_num_rows($exp) > 0) { ?>

	<div class="notice">
	<ul>
		<li><a href="index.php?do=news&date_cat=&showme=expiringsoon">(<?php print mysqli_num_rows($exp);?>) Page<?php if(mysqli_num_rows($exp)>1) { print "s"; } ?> Expiring In The Next 3 Days</a></li>
	</ul>
	</div>

<?php } ?>

<?php 
if($site_setup['backup_reminder'] == "1") { 
if($setup['demo_mode'] !== true) { 
$budate  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")));

if($history['db_backup'] < $budate) { ?>
	<div class="notice">
	<ul>
		<li><a href="index.php?do=settings&action=dbBackup">Time To Backup & Optimize Database</a></li>
	</ul>
</div>
<?php } ?>
<?php } ?>
<?php } ?>

<?php

$scs = whileSQL("ms_payment_schedule LEFT JOIN ms_orders ON ms_payment_schedule.order_id=ms_orders.order_id", "*", "WHERE payment<='0' AND due_date<'".date('Y-m-d')."' AND order_status<'2' ");
if(mysqli_num_rows($scs) > 0) { ?>
	<div class="notice">
	<ul>
		<li><a href="index.php?do=orders&action=payments&status=pastdue">(<?php print mysqli_num_rows($scs);?>) Scheduled Payment<?php if(mysqli_num_rows($scs)>1) { print "s"; } ?> Past Due</a></li>
	</ul>
	</div>
<?php }?>


<?php

$scs = whileSQL("ms_payment_schedule", "*", "WHERE payment<='0' AND due_date='".date('Y-m-d')."' ");
if(mysqli_num_rows($scs) > 0) { ?>
	<div class="notice">
	<ul>
		<li><a href="index.php?do=orders&action=payments&status=unpaid">(<?php print mysqli_num_rows($scs);?>) Scheduled Payment<?php if(mysqli_num_rows($scs)>1) { print "s"; } ?> Due Today</a></li>
	</ul>
	</div>
<?php }?>

<?php
$tm = date("Y-m-d", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 

$scs = whileSQL("ms_payment_schedule", "*", "WHERE payment<='0' AND due_date='".$tm."' ");
if(mysqli_num_rows($scs) > 0) { ?>
	<div class="notice">
	<ul>
		<li><a href="index.php?do=orders&action=payments&status=unpaid">(<?php print mysqli_num_rows($scs);?>) Scheduled Payment<?php if(mysqli_num_rows($scs)>1) { print "s"; } ?> Due Tomorrow</a></li>
	</ul>
	</div>
<?php }?>

<?php $gc = countIt("ms_gift_certificates", "WHERE delivery_date='".date('Y-m-d')."' AND emailed_date='0000-00-00' ");
if($gc > 0) { ?>
	<div class="notice">
	<ul>
		<li><a href="index.php?do=people&view=giftcertificates">(<?php print $gc;?>) eGift Card<?php if($gc > 1) { print "s"; } ?> Needs To Be Sent Today</a></li>
	</ul>
	</div>
<?php } ?>

<?php $gc = countIt("ms_gift_certificates", "WHERE delivery_date<'".date('Y-m-d')."' AND emailed_date='0000-00-00' ");
if($gc > 0) { ?>
	<div class="noticered">
	<ul>
		<li><a href="index.php?do=people&view=giftcertificates"><span class="the-icons icon-attention" style="color: #FFFFFF;"></span>(<?php print $gc;?>) eGift Card<?php if($gc > 1) { print "s Are"; } else { print " Is"; }  ?> Past Sending Date! Send Now.</a></li>
	</ul>
	</div>
<?php } ?>