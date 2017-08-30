<?php 
$path = "../../";
require "../w-header.php"; 

function viewevent($book_id) { 
	global $setup,$site_setup;

	$book  = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show ,date_format(DATE_ADD(book_date_added, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." %l:%i %p')  AS book_date_added", "WHERE book_id='".$book_id."' ");  ?>
	<?php if($book['book_confirmed'] == "1") { ?>
		<div class="error center">UNCONFIRMED</div>
	<div class="pc center"><a href="" onclick="confirmbooking('<?php print $book['book_id'];?>'); return false;"><span class="the-icons icon-mail"></span>Confirm Now</a></div>
	<?php } ?>
	<?php if($book['book_confirmed'] == "3") { ?>
	<div class="error center">CANCELED</div>
	<div class="pc">Notes: <br><i><?php print nl2br($book['book_cancel_notes']);?></i></div>
	<div>&nbsp;</div>
	<?php } ?>

	<?php if($book['book_service'] > 0) { ?>
	<div class="pc"><h3><?php print $book['date_title'];?></h3></div>
	<?php } else { ?>
	<div class="pc"><h3><?php print $book['book_event_name'];?></h3></div>
	<?php } ?>
	<div class="pc"><h3><?php if($book['book_google'] == "1") { ?>
		<img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;"  title="Added to Google Calendar">
		<?php } ?><?php print $book['book_date_show'];?> <?php if(($book['book_time'] !== "00:00:00") && ($book['book_all_day'] !== "1") == true) { ?><?php if($book['book_time'] !== "00:00:00") { ?><?php print $book['book_time_show'];?><?php } ?><?php } ?></h3></div>

	<?php if(($book['book_length'] > 0) && ($book['book_all_day'] !== "1") == true) { ?>
	<div class="pc">
		<div class="left p30">Duration</div>
		<div class="left p70">
			<?php 
			$hours = floor($book['book_length'] / 60);  
			$minutes = $book['book_length'] - ($hours * 60);
			if($hours > 0) { 
				if($hours == "1") { 
					print "1 hour ";
				} else { 
					print $hours." hours ";
				}
			}
			if($minutes > 0) { 
				print $minutes." minutes";
			}
			?>

		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>

	<?php if((!empty($book['book_first_name'])) || (!empty($book['book_first_name'])) == true) { ?>
	<div class="pc">
		<div class="left p30">Who</div>
		<div class="left p70">
		<?php if($book['book_account'] > 0) { 
			?><a href="index.php?do=people&p_id=<?php print $book['book_account'];?>" target="_blank"><?php  print $book['book_first_name'];?> <?php print $book['book_last_name'];?></a>
		<?php 
		} else { 
		 print $book['book_first_name'];?> <?php print $book['book_last_name'];?>
		<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>


	<?php if(!empty($book['book_email'])) { ?>
	<div class="pc">
		<div class="left p30">Email</div>
		<?php $email = doSQL("ms_emails", "*", "WHERE email_id_name='bookingreminder' "); ?>
		<div class="left p70">
			<div><?php print $book['book_email'];?></div>
			<?php if($book['date_id'] > 0) { ?>
			<div><a href="" onclick="bookingreminder('<?php print $book['book_id'];?>','<?php print $email['email_id'];?>'); return false;">Send reminder email</a></div>
			<?php if($book['book_confirmed'] == "2") { ?><div><a href="" onclick="confirmbooking('<?php print $book['book_id'];?>'); return false;">Send confirmation email</a></div><?php } ?>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>

	<?php if(!empty($book['book_phone'])) { ?>
	<div class="pc">
		<div class="left p30">Phone</div>
		<div class="left p70"><?php print $book['book_phone'];?></div>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<?php if($book['book_submitted'] == "1") { ?>
	<div class="pc">
		<div class="left p30">Submitted</div>
		<div class="left p70"><?php print $book['book_date_added'];?></div>
		<div class="clear"></div>
	</div>

	<div class="pc">
		<div class="left p30">IP Address</div>
		<div class="left p70"><a href="index.php?do=stats&action=recentVisitors&q=<?php print $book['book_ip'];?>" target="_blank"><?php print $book['book_ip'];?></a></div>
		<div class="clear"></div>
	</div>

	<?php } ?>
	<?php if(!empty($book['book_options'])) { ?>
	<div class="pc">
		<div class="left p30">Options</div>
		<div class="left p70">
		<?php $opts = explode("\n",$book['book_options']);
		foreach($opts AS $opt) { 
			if(!empty($opt)) { 
				$o = explode("|",$opt);
				if(!empty($o[0])) { 
					print $o[0]; if(!empty($o[1])) { print ": ".$o[1];} if($o[2] > 0) { print "  ".showPrice($o[2]); } print "<br>";
				}
			}
		}
		?>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>

	<?php if($book['book_total'] > 0) { ?>
	<div class="pc">
		<div class="left p30">Total</div>
		<div class="left p70"><?php print showPrice($book['book_total']);?></div>
		<div class="clear"></div>
	</div>
	<?php } ?>
<!--
	<?php if($book['book_deposit'] > 0) {
	?>
	<div class="pc">
		<div class="left p30">Deposit</div>
		<div class="left p70"><?php print showPrice($book['book_deposit']);?> 
			
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>
	-->
	<?php 
		$gtotal = doSQL("ms_orders  LEFT JOIN ms_cart ON ms_orders.order_id=ms_cart.cart_order", "SUM(order_payment) AS tot", "WHERE cart_booking='".$book['book_id']."'  AND order_payment_status='Completed' "); 
		?>
		<?php if($gtotal['tot'] > 0) { ?>
		<div class="pc">
		<div class="left p30">Paid</div>
		<div class="left p70">
		<?php print showPrice($gtotal['tot']); ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<?php if($book['book_total'] > 0) { ?>
			<div class="pc"><a href="" onclick="bookinginvoice('<?php print $book['book_id'];?>'); return false;">Create Invoice</a></div>
	<?php } ?>


	<?php $orders = whileSQL("ms_orders LEFT JOIN ms_cart ON ms_orders.order_id=ms_cart.cart_order", "*", "WHERE cart_booking='".$book['book_id']."' ORDER BY order_id ASC ");
	if(mysqli_num_rows($orders) > 0) { ?>

	<div class="pc">
		<div class="left p30">Invoices</div>
		<div class="left p70">
		<?php 
	while($order = mysqli_fetch_array($orders)) { ?>
	<div class="pc"><div class="left"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>" target="_blank"><?php print $order['order_id'];?></a></div>
	<div class="right textright"><?php if(($order['order_payment'] <=0)&&($order['order_total'] > 0)==true) { ?><span style="color: #D22C1A;" class="tip" title="Unpaid"><?php print showPrice($order['order_total']);?></span><a href="" onclick="bookingemailinvoice('<?php print $order['order_id'];?>','<?php print $book['book_id'];?>'); return false;"><span class="the-icons icon-mail"></span></a>
	<?php } else { ?>
	<?php print showPrice($order['order_total']);?>
	<?php } ?>
	</div>
	<div class="clear"></div>
	</div>
	<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<?php if(!empty($book['book_customer_notes'])) { ?>
	<div class="pc">
		<div class="left p30">Comment</div>
		<div class="left p70"><?php print nl2br($book['book_customer_notes']);?></div>
		<div class="clear"></div>
	</div>
	<?php } ?>
	<div>&nbsp;</div>
		<div class="pc">Notes</div>
		<div  contenteditable id="book_notes"  name="book_notes" style="min-height: 30px;" class="notes"><?php print $book['book_notes']; ?>&nbsp;</div>
		<div class="pc" style="height: 16px;">
			<div class="left" id="noteloading" style="display: none;"><img src="graphics/loading2.gif"></div>
			<div class="left" id="noteupdated" style="display: none;">Updated</div>
			<div class="right textright" id="updatenote"><a href="" onClick="updatebooknotes('<?php print $book['book_id'];?>'); return false;">update</a></div>
			<div class="clear"></div>
		</div>
	<div>&nbsp;</div>

	<div class="pc center"><a href="" onclick="editbooking('<?php print $book['book_id'];?>','edit'); return false;">edit</a>   &nbsp; 
	<a href="javascript:deletebooking('<?php print $book['book_id'];?>','edit');" onClick="return confirm('Are you sure you want to delete this? ');" >delete</a>    &nbsp; 
	<?php if($book['book_confirmed'] !== "3") { ?><a href="" onclick="showHide('cancelbooking'); return false;">cancel event</a><?php } ?>
	</div>
	<div>&nbsp;</div>
	<div class="pc center">
	<?php 
	$title = "";
	$bdate = str_replace("-","",$book['book_date']);
	$start = $book['book_date']." ".$book['book_time'];
	$btimestart = date('Hi',strtotime($start))."00";
	$btimeend = date('Hi',strtotime('+'.$book['book_length'].' minutes',strtotime($start)))."00";
	$startDate = $bdate."T".$btimestart;
	$endDate = $bdate."T".$btimeend;
	
	if((!empty($book['book_first_name'])) || (!empty($book['book_last_name'])) == true) { 
		$title = $book['book_first_name']." ".$book['book_last_name']." - ";
	}
	if(!empty($book['book_event_name'])) { 
		$title .= $book['book_event_name'];
	} else { 
		$title .= $book['date_title'];
	}
	if(($book['all_day_event'] == "1") || ($book['book_time'] == "00:00:00") == true) { 
		$start = $book['book_date']." ".$book['book_time'];
		$endDate = date('Ymd',strtotime('+ 1 days',strtotime($start)));
		$date = $bdate."/".$endDate;
	} else { 
		$date = $startDate."/".$endDate;
	}
	$details = "";
	$opts = explode("\n",$book['book_options']);
	foreach($opts AS $opt) { 
		if(!empty($opt)) { 
			$o = explode("|",$opt);
			if(!empty($o[0])) { 
				$details .= $o[0]; if(!empty($o[1])) {$details .= ": ".$o[1];}  $details .= "\r\n";
			}
		}
	}
	// $location = "";
	?>

	<a href="to-google-calendar.php?book_id=<?php print $book['book_id'];?>" target="_blank"><img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;"> Add to Google Calendar</a>
	<br><br><div style="font-size: 12px; line-height: 100%;">When you click add to Google Calendar, it will open a new window for you to add it to your Google Calendar with the information populated. An icon will also appear next to the bookings you have clicked the link to add to Google Calendar.</div>
	<!-- 
	<a href="https://www.google.com/calendar/render?
	action=TEMPLATE
	&text=<?php print urlencode($title);?>
	&dates=<?php print $date;?>
	&details=<?php print urlencode($details);?>
	&location=<?php print urlencode($location);?>
	&sf=true
	&:1k-ad=1
	&output=xml"
	target="_blank" rel="nofollow"><img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;"> Add to Google calendar</a>
	-->
	</div>

	<div id="cancelbooking" class="hide">
		<div class="pc">Canceling the event will leave a record of this in the system for reference. To completely delete it, click delete.</div>
		<div class="pc">Enter reason for cancelation (optional)</div>
		<div class="pc"><textarea name="book_cancel_notes" id="book_cancel_notes" rows="2" class="field100"></textarea></div>
		<div class="pc center"><a href="" onclick="cancelbooking('<?php print $book['book_id'];?>'); return false;">Cancel Now</a></div>
	</div>
<?php } 



function listevents($book,$back,$backto) { 
	global $setup,$site_setup,$booksettings;?>
	
	
	<div class="underline">
		<div class="left p70"  <?php if($book['book_confirmed'] == "3") { ?>style="text-decoration: line-through;"<?php } ?>>
			<div>
			<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><a href="" onclick="viewday('<?php print $book['book_id'];?>','0','0','<?php print $back;?>','<?php print $backto;?>'); return false;">
			<?php if($book['date_id'] > 0) { print $book['date_title']; } elseif (!empty($book['book_event_name']))  { print $book['book_event_name']; } else { print "<i>Untitled</i>"; } ?></a>
			</div>
		</div>
		<div class="left p30 textright"  <?php if($book['book_confirmed'] == "3") { ?>style="text-decoration: line-through;"<?php } ?>><?php if(($book['book_time'] !== "00:00:00") && ($book['book_all_day'] !== "1") == true) { ?><?php if($book['book_time'] !== "00:00:00") { ?><?php print $book['book_time_show'];?> 

			(<?php 
			$hours = floor($book['book_length'] / 60);  
			$minutes = $book['book_length'] - ($hours * 60);
			if($hours > 0) { 
				if($hours == "1") { 
					print "1 hour ";
				} else { 
					print $hours." hours ";
				}
			}
			if($minutes > 0) { 
				print $minutes." mins";
			}
			?>)

		<?php } ?><?php  } else { print "&nbsp;"; } ?></div>
		<div class="clear"></div>
		<div class="left p70">
		<?php if(!empty($book['book_first_name'])) { ?><div><?php print $book['book_first_name']." ".$book['book_last_name'];?></div><?php } ?>
		<?php if($book['book_confirmed'] == "1") { ?><div style="color: #890000;">Unconfirmed</div><?php } ?>
		<?php if($book['book_confirmed'] == "3") { ?><div style="color: #890000;">Canceled</div><?php } ?>
		</div>
		<div class="left p30 textright"><?php print $book['book_date_show'];?></div>
			<div class="clear"></div>

	</div>
	<?php 
}

?>
<script>
 $(document).ready(function(){
	if($("body").attr("book-id") > 0) { 
		if($("body").attr("data-back-to") !== "") { 
			$("#bookingback").show();
		}
	}
});
 </script>

	<div class="left hide" id="bookingback"><a href="" onclick="bookingback(); return false;" class="the-icons icon-left-open" title="back"></a></div>
<!-- 
<?php if($_REQUEST['back'] == "when") { ?>
	<div class="left"><a href="" onclick="viewday('0','0','unconfirmed'); return false;" class="the-icons icon-left-open" title="back"></a></div>
<?php } ?>

<?php if($_REQUEST['back'] == "date") { ?>
	<div class="left"><a href="" onclick="viewday('0','<?php print $_REQUEST['backto'];?>','0'); return false;" class="the-icons icon-left-open" title="back"></a></div>
<?php } ?>

<?php if($_REQUEST['back'] == "person") { ?>
	<div class="left"><a href="" onclick="viewday('0','0','0','','','<?php print $_REQUEST['p_id'];?>'); return false;" class="the-icons icon-left-open" title="back"></a></div>
<?php } ?>
-->
<div class="right textright"><a href="" onclick="sideeditclose(); return false;" class="the-icons icon-cancel" title="close"></a></div>
<div>&nbsp;</div>
<?php 
if($_SESSION['book_id'] > 0) { 
	$_REQUEST['book_id'] = $_SESSION['book_id'];
	?>
	<script>
	$("body").attr("book-id",'<?php print $_SESSION['book_id'];?>');
		if($("body").attr("data-back-to") !== "") { 
			$("#bookingback").show();
		}
	</script>
	<?php 
	unset($_SESSION['book_id']);
}
if($_REQUEST['book_id'] > 0) { 
	viewevent($_REQUEST['book_id']);
	exit();
}
?>



<?php 
$booksettings = doSQL("ms_bookings_settings", "*", "");

if($_REQUEST['date'] > 0) { 

	$d = explode("-",$_REQUEST['date']);
	$day = date("M d, Y", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 
	$dow = date("l", mktime(0, 0, 0, $d[1], $d[2], $d[0])); 
	$du = doSQL("ms_bookings", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_date='".$d[0]."-".$d[1]."-".$d[2]."' AND  book_unavailable_day='1' GROUP BY book_id ORDER BY book_time ASC ");

	?>
	<div class="pc center"><h2><?php print $day;?></h2></div>
	<div class="pc center"><a href="" onclick="editbooking('','edit','<?php print $d[0];?>','<?php print $d[1];?>','<?php print $d[2];?>',''); return false;" ><span class="the-icons icon-calendar"></span>Add Event</a></div>

		<?php if($booksettings[$dow]  == "1") { ?>
		<div class="pc center"><?php if(!empty($du['book_id'])) { ?><a href="" onclick="dateavailable('<?php print $d[0];?>-<?php print $d[1];?>-<?php print $d[2];?>'); return false;" >Unavailable - Make Date Available</a><?php } else { ?><a href="" onclick="dateunavailable('<?php print $d[0];?>-<?php print $d[1];?>-<?php print $d[2];?>'); return false;">Make Date Unavailable</a><?php } ?>
		</div>
		<?php } ?> 

	<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_confirmed>='1' AND ((book_date='".$_REQUEST['date']."' AND book_recurring_dom='' AND book_recurring_dow='') OR book_recurring_dom='".$d[2]."') OR book_recurring_dow='".$dow."'   GROUP BY book_id    ORDER BY book_time ASC ");
	if(mysqli_num_rows($books) <= 0) { ?>
	<div class="pc center">No events found</div>
	<?php } 
	while($book = mysqli_fetch_array($books)) { 
		$t = explode(":",$book['book_time']);
		$timeend = date("g:i A", mktime($t[0], $t[1] + ($book['service_length'] * 60), 0, 0,0,0)); 
		if(mysqli_num_rows($books) == 1) { 
			viewevent($book['book_id']);
		} else {
		 listevents($book,'date',$_REQUEST['date']);
		}
	} 

	?>
	<?php 	if(mysqli_num_rows($books) > 1) { ?>
	<div>&nbsp;</div>
	<div class="pc center"><a href="booking/print.php?date=<?php print $_REQUEST['date'];?>" target="_blank"><span class="the-icons icon-print"></span>Print</a></div>
	<?php 
	}
	exit();
}


if($_REQUEST['p_id'] > 0) { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
	?>
	<div class="underlinelabel"><?php print $p['p_name']." ".$p['p_last_name'];?></div>
	<div class="pc center"><a href="" onclick="editbooking('','edit','<?php print $cyear;?>','<?php print $cmonth;?>','<?php print $xc[$cmonth];?>','<?php print $p['p_id'];?>'); return false;" ><span class="the-icons icon-calendar"></span>Add Event</a></div>
	<?php 
	$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m %e ')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE book_account='".$p['p_id']."' AND book_confirmed>='1'  AND (book_date>='".date('Y-m-d')."' OR book_recurring_y='1')   GROUP BY book_id    ORDER BY book_date_order ASC , book_time ASC ");
	if(mysqli_num_rows($books) <= 0) { ?>
	<div class="pc center">No events found</div>
	<?php } 
	while($book = mysqli_fetch_array($books)) { 
		if(mysqli_num_rows($books) == 1) { 
			viewevent($book['book_id']);
		} else {
		 listevents($book,'person',$p['p_id']);
		}
	}
	exit();
}



if($_REQUEST['when'] !== "0") { 
	if($_REQUEST['when'] == "unconfirmed") {
		 $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id LEFT JOIN ms_cart ON ms_bookings.book_id=ms_cart.cart_booking LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' AND (order_id IS NULL OR (order_id>'0' AND order_invoice='0'))   GROUP BY book_id    ORDER BY book_date ASC, book_time ASC ");
		if(mysqli_num_rows($books) > 1) { ?>
		<div class="underlinelabel">Unconfirmed Booking Requests</div>
		<?php  
		}
		while($book = mysqli_fetch_array($books)) { 
			if(mysqli_num_rows($books) == 1) { 
				viewevent($book['book_id']);
			} else {
			 listevents($book,'when','unconfirmed');
			}
		}
	}


	if($_REQUEST['when'] == "unconfirmedinvoiced") {
		 $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id LEFT JOIN ms_cart ON ms_bookings.book_id=ms_cart.cart_booking LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' AND order_id>'0' AND order_payment_status!='Completed'  GROUP BY book_id    ORDER BY book_date ASC, book_time ASC ");
		if(mysqli_num_rows($books) > 1) { ?>
		<div class="underlinelabel">Booking Requests Awaiting Payment</div>
		<?php  
		}
		while($book = mysqli_fetch_array($books)) { 
			if(mysqli_num_rows($books) == 1) { 
				viewevent($book['book_id']);
			} else {
			 listevents($book,'when','unconfirmed');
			}
		}
	}

	exit();
}



?>












<?php ############ Calendar ############### ?>

<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { ?>
<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homecalendar');return false;">&#149;</span>&nbsp;&nbsp;<a href="index.php?do=booking"><span class="h3">Calendar</span></a> </span></div>
<div id="homecalendar">
<?php 
$closecaldiv = true;
} ?>


<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (book_date>='".date('Y-m-d')."' OR book_recurring_y='1') AND book_confirmed='1' GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");
if(mysqli_num_rows($books) > 0) { 
	$tt++;
	?>
<div class="underlinelabel">Unconfirmed Booking Requests</div>

<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p65">
	<div>
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><a href="" onclick="editbooking('<?php print $book['book_id'];?>',''); return false;"><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?></a>
	</div>
	<?php if(!empty($book['book_first_name'])) { ?>
	<div><?php print $book['book_first_name']." ".$book['book_last_name'];?></div>
	<?php } ?>

	
	</div>


	<div class="left p20"><a href="" onclick="editbooking('<?php print $book['book_id'];?>',''); return false;"><?php 
		if($book['book_date_order'] == date('md')) { 
			print "Today"; 
			} else if($book['book_date_order'] ==  date("md", mktime(0,0,0,date('m'),date('d') + 1,date('Y'))))  {
				print "Tomorrow";
			} else { 

	if(!empty($book['book_recurring_y']))	 { print $book['book_date_recur_year']; } else { print $book['book_date_show']; } 
	
}?></a></div>
	<div class="left p15 textright"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	
	<div class="clear"></div>

</div>
<?php } ?>
<div>&nbsp;</div>
<?php } ?>


<?php $books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (MONTH(book_date)='".date('m')."' AND DAYOFMONTH(book_date)='".date('d')."' ) AND book_confirmed='2' GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");

if(mysqli_num_rows($books) > 0) { 
	$tt++;
	?>
<div class="underlinelabel">Today</div>

<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p85">
	<div>
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><a href="" onclick="editbooking('<?php print $book['book_id'];?>',''); return false;"><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?></a>
	</div>
	<?php if(!empty($book['book_first_name'])) { ?>
	<div><?php print $book['book_first_name']." ".$book['book_last_name'];?></div>
	<?php } ?>
	</div>
	<div class="left p15  textright"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	
	<div class="clear"></div>

</div>
<?php } ?>
<div>&nbsp;</div>
<?php } ?>


<?php 

$tm = date("m", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 
$td = date("d", mktime(0,0,0,date('m'),date('d')+1,date('y'))); 

$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE   (MONTH(book_date)='".$tm."' AND DAYOFMONTH(book_date)='".$td."' )  AND book_confirmed='2'  GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC ");

if(mysqli_num_rows($books) > 0) {
	$tt++?>
<div class="underlinelabel">Tomorrow</div>

<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p85">
	<div>
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><a href="" onclick="editbooking('<?php print $book['book_id'];?>',''); return false;"><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?></a>
	</div>
	<?php if(!empty($book['book_first_name'])) { ?>
	<div><?php print $book['book_first_name']." ".$book['book_last_name'];?></div>
	<?php } ?>
	</div>
	<div class="left p15 textright"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	
	<div class="clear"></div>


</div>
<?php } ?>
<div>&nbsp;</div>
<?php } ?>







<?php

$td = date("md", mktime(0,0,0,date('m'),date('d')+2,date('y'))); 

$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_show, time_format(book_time, '%l:%i %p')  AS book_time_show,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%m%e')  AS book_date_order, date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%M %e ')  AS book_date_recur_year", "WHERE date_format(book_date,'%m%e')>='".$td."' AND ( YEAR(book_date)='".date('Y')."' OR  book_recurring_y='1')  AND book_confirmed='2'  GROUP BY book_id    ORDER BY book_date_order ASC, book_time ASC LIMIT 5 ");
if(mysqli_num_rows($books) > 0) { 
	if($tt > 0) { ?>
<div class="underlinelabel">Upcoming</div>

<?php }  ?>
<?php while($book = mysqli_fetch_array($books)) { ?>
<div class="underline">
	<div class="left p65">
	<div>
	<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?><a href="" onclick="editbooking('<?php print $book['book_id'];?>',''); return false;"><?php if($book['date_id'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?></a>
	</div>
	<?php if(!empty($book['book_first_name'])) { ?>
	<div><?php print $book['book_first_name']." ".$book['book_last_name'];?></div>
	<?php } ?>
	</div>
	<div class="left p20">
	<?php 	if(!empty($book['book_recurring_y']))	 { print $book['book_date_recur_year']; } else { print $book['book_date_show']; } ?></div>
	<div class="left p15  textright"><?php if($book['book_time'] !== "00:00:00") { print $book['book_time_show']; } else { print "&nbsp;"; } ?></div>
	
	<div class="clear"></div>

</div>
<?php } ?>
<?php if($closecaldiv == true) { ?>
<div>&nbsp;</div>
</div>
<?php } ?>
<div>&nbsp;</div>
<?php } ?>







<?php require "../w-footer.php"; ?>
