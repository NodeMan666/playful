
<?php if($setup['unbranded'] !== true) { ?><div>&nbsp;</div><div class="pc center"><a href="https://www.picturespro.com/sytist-manual/calendar/" target="_blank" class="the icons icon-info-circled"><i>Booking Calendar Information</i></a></div><?php } ?>

<ul class="sidemenus">
<li <?php if((empty($_REQUEST['view']))== true) { print "class=\"on\""; } ?>><a href="?do=booking">Calendar</a></li>
<li><a href="" onclick="editbooking('','edit','<?php print date('Y');?>','<?php print date('m');?>','<?php print date('d');?>',''); return false;" >Add Event</a></li>
<li <?php if($_REQUEST['view'] == "settings"){ print "class=\"on\""; } ?>><a href="?do=booking&view=settings">Booking Settings</a></li>

</ul>
<div>&nbsp;</div>

<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id LEFT JOIN ms_cart ON ms_bookings.book_id=ms_cart.cart_booking LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' AND (order_id IS NULL OR (order_id>'0' AND order_invoice='0')) GROUP BY book_id   ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { 
	$tt++;
	?>
	<div class="notice">
	<ul>
		<li><a href="" onclick="viewday('0','0','unconfirmed'); return false;">(<?php print mysqli_num_rows($books);?>) Unconfirmed Booking Request<?php if(mysqli_num_rows($books)>1) { print "s"; } ?></a></li>
	</ul>
	</div>
<?php } ?>




<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id LEFT JOIN ms_cart ON ms_bookings.book_id=ms_cart.cart_booking LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' AND order_id>'0' AND order_payment_status!='Completed' GROUP BY book_id   ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { 
	$tt++;
	?>
	<div class="notice">
	<ul>
		<li><a href="" onclick="viewday('0','0','unconfirmedinvoiced'); return false;">(<?php print mysqli_num_rows($books);?>) Unconfirm & Awaiting Payment<?php if(mysqli_num_rows($books)>1) { print "s"; } ?></a></li>
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


