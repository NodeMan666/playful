<div class="buttons textright"><a href="" onclick="editbooking('','edit','<?php print $cyear;?>','<?php print $cmonth;?>','<?php print $xc[$cmonth];?>','<?php print $p['p_id'];?>'); return false;" >Add Event</a></div>

<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m %e ')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE book_account='".$p['p_id']."' AND (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') GROUP BY book_id    ORDER BY book_date_order ASC , book_time ASC ");
if(mysqli_num_rows($books) <= 0) { ?>
<div class="pc center">No dates or events added</div>
<?php } ?>
<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p25"><a href="" onclick="viewday('<?php print $book['book_id'];?>',''); return false;"><?php 
	if(!empty($book['book_recurring_y']))	 { print $book['book_date_recur_year']; } else { print $book['book_date_show']; } ?></a></div>
	<div class="left p10"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	<div class="left p65">
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?>
	</div>
	
	<div class="clear"></div>

</div>
<?php } ?>
<div>&nbsp;</div>
<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m %e ')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE book_account='".$p['p_id']."' AND book_date<'".date('Y-m-d')."' AND book_recurring_y<='0'  GROUP BY book_id    ORDER BY book_date_order DESC ");
if(mysqli_num_rows($books) > 0) { ?>
<div class="underlinelabel">Past Dates or Events</div>
<?php } ?>
<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p25"><a href="" onclick="viewday('<?php print $book['book_id'];?>',''); return false;"><?php 
	if(!empty($book['book_recurring_y']))	 { print $book['book_date_recur_year']; } else { print $book['book_date_show']; } ?></a></div>
	<div class="left p10"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	<div class="left p65">
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?>
	</div>
	
	<div class="clear"></div>

</div>
<?php } ?>

