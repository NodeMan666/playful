<?php 
$path = "../../";
require "../w-header.php"; ?>

<?php 
$booksettings = doSQL("ms_bookings_settings", "*", "");

if((!empty($_REQUEST['month']))&&(!empty($_REQUEST['year']))==true) { 
	$cmonth = $_REQUEST['month'];
	$cyear = $_REQUEST['year'];
} else { 
	$cmonth = date("m");
	$cyear = date("Y");
}
$num_of_days = date("t", mktime(0,0,0,$cmonth,1,$cyear)); 
$firstdayname[$cmonth] = date("D", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
$cmonth_name[$cmonth] = date("F", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
$firstday[$cmonth] = date("w", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
$lastday = date("t", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
?>

<script>
function changemonth() {
	var my = $("#changemonth").val();
	my = my.split("-");
	getCalendar(my[0],my[1]);
}
</script>
	<style>
		#calHeader { display: block; width: 100%; } 
	.calendarcontainer { padding: 8px; } 
	.dayscontainer {  border-right: solid 1px #e4e4e4;  border-bottom: solid 1px #e4e4e4; } 

	.caldaycontainer { width: 14.28%; float: left; padding: 0px; height: 160px; overflow: hidden;} 
	/* 	.caldaycontainer { width: 14.28%; float: left; padding-bottom: 14.28%; overflow: hidden; position: relative;} */

	@media (max-width: 800px) {
		.caldaycontainer {  height: 100px; } 
	}
	.calthismonthday:hover { cursor: pointer;  background: #FaFaF1 }

	.caldaycontainerday { width: 14.28%; float: left; padding: 0px;   overflow: hidden;} 
	.callabel { color: #000000; margin-bottom: 8px; } 
	.calday { background: #FFFFFF; padding: 8px;  height: 100%;   border-top: solid 1px #e4e4e4;  border-left: solid 1px #e4e4e4; } 
	.today { background: #D1E6FF; } 
	.event { border-bottom: solid 1px #D4D4D4; margin-bottom: 8px; } 
	.notmonth { background: #f1f1f1; } 
	.unavailableday { background: #EDD8D7; } 
	.inthepast { background: #f6f6f6; } 
	.calDateNumber { width:20%; float: left;  color: #000000;} 
	.calavailable { width: 60%; float: left; text-align: center; display: none;}
	.calDateNumberNotMonth { width:20%; float: left;  color: #999999;} 
	.calAdd { width:20%; float: right; text-align: right;  font-size: 15px; z-index: 3; } 

	#bookingcalendar  { font-size: 13px; } 
	#bookingcalendar , #bookingcalendar a { color: #242424; } 
	#bookingcalendar .inthepast a { color: #d4d4d4; } 
	#bookingcalendar .inthepast .the-icons { color: #d4d4d4; } 

	#bookingcalendar .the-icons { color: #949494; font-size: 13px; } 
	#bookingcalendar .unconfirmed { color: #c48888; } 
	#bookingcalendar .canceled { color: #F4B4B4; text-decoration: line-through;} 

	</style>
	<div class="calendarcontainer">
	<div>&nbsp;</div>
	<div class="pc center">
	<div class="left p30 center">
	<?php 
	$lmm = date("m", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
	$lmy = date("Y", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
	$lmn = date("F", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
	?>
	<h3><a href="" onclick="getCalendar('<?php print $lmm;?>','<?php print $lmy;?>'); return false;">&larr; <?php print $lmn; ?></a></h3>

	</div>
	<div class="left p40 center">
	<!-- <div><h2><?php print $cmonth_name[$cmonth]." ". $cyear; ?></h2></div> -->
	<select name="changemonth" id="changemonth" onchange="changemonth();" class="inputtitle center" style="font-size: 27px; margin-top: -16px; border: 0px;">
	<?php 
	$cmyear = date("Y") - 2;
	$cmtoyear = date("Y") + 5;
	while($cmyear <= $cmtoyear) { 
		$month = 1;
		while($month <= 12) {
			$month_name = date('F',mktime(0,0,0,$month,1,$cyear));
			if($month < 10) { 
				$tmonth = "0".$month;
			} else { 
				$tmonth = $month;
			}
			?>
			<option value="<?php print $tmonth."-".$cmyear;?>" <?php if(($cmonth == $tmonth) && ($cyear == $cmyear) == true) { ?>selected<?php } ?> <?php if(($tmonth == date("m")) && (date("Y") == $cmyear) == true) { ?>style="background: #D1E6FF;"<?php } ?>><?php print $month_name." ".$cmyear;?></option>
			<?php 
			$month++;
			}
		$cmyear++;
	}
	?>
	</select>
	</div>
	<div class="left p30 center">
	<?php 
	$nmm = date("m", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 
	$nmy = date("Y", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 
	$nmn = date("F", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 
	?>
	<h3><a href="" onclick="getCalendar('<?php print $nmm;?>','<?php print $nmy;?>'); return false;"><?php print $nmn; ?> &rarr;</a></h3>
	</div>
	<div class="clear"></div>
	</div>
	<div>&nbsp;</div>


	<script>
 $(document).ready(function(){
	setTimeout(function(){
	 	$(".caldaycontainer").each(function(i){
		//	offset = $(this).offset();
			if($(this).prop('scrollHeight') > $(this).innerHeight()) { 
				$(this).css({"overflow-y":"scroll"});
			}
			//	$("#log").show().append($(this).prop('scrollHeight')+" > "+$(this).innerHeight()+" , ");
			});
	},200);


    $(".calday").hover(function(){    
        $(this).find(".calavailable").show();
    
    }, function(){
        $(this).find(".calavailable").hide();
    
    });



 });
</script>


	<div id="calHeader">
		<div class="caldaycontainerday"><div class="callabel center">Sun</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Mon</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Tues</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Wed</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Thurs</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Fri</div></div>
		<div class="caldaycontainerday"><div class="callabel center">Sat</div></div>
		<div class="cssClear"></div>
	</div>
		<div class="dayscontainer">

	<?php 
	$xc[$cmonth] = 1;
	$less_days[$cmonth] = $firstday[$cmonth];
	$x[$cmonth] = $firstday[$cmonth] + 1;
	if($less_days[$cmonth] > 7) {
		$less_days[$cmonth] = $less_days[$cmonth] - 8;
	}
	if($less_days[$cmonth] == 7) { $less_days[$cmonth] = 1; }
	while ($less_days[$cmonth] > 0) { ?>
	<div class="caldaycontainer caldaydiv" id="date-<?php print $cmonth."-".$xc[$cmonth];?>">
		<div class="calday notmonth">
			<div class="calDateNumberNotMonth">
		<?php 
		$last_num_of_days = date("t", mktime(0,0,0,$cmonth - 1,1,$cyear)); 
		print date("d", mktime(0, 0, 0, $cmonth - 1, $last_num_of_days - ($less_days[$cmonth] - 1), $cyear)); 
		?>
		</div>
		<div class="clear"></div>
		</div>
	</div>
	<?php 
		$less_days[$cmonth] = $less_days[$cmonth] - 1;
	}
	while($xc[$cmonth] <= $num_of_days) {
		$rd++; 
		$du = doSQL("ms_bookings", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_date='".$cyear."-".$cmonth."-".$xc[$cmonth]."' AND  book_unavailable_day='1' GROUP BY book_id ORDER BY book_time ASC ");
		if(!empty($du['book_id'])) {
			$add_class = "unavailableday";
		}

		?>
		<?php 
		$dow = date("l", mktime(0, 0, 0, $cmonth, $xc[$cmonth], $cyear)); 
		if($booksettings[$dow]  <= 0) { 
			$add_class = "unavailableday";
		}
			if($xc[$cmonth] <= 9) { 
				$d = "0".$xc[$cmonth];
			} else { 
				$d = $xc[$cmonth];
			}

		if(($cyear."-".$cmonth."-".$d) < date("Y-m-d")) { 
			$unavailableday = true;
			$add_class = "inthepast";
		}

		?>
		
		<div class="caldaycontainer caldaydiv <?php if(($xc[$cmonth] == date('d')) AND ($cmonth == date('n')) AND ($cyear == date('Y'))==TRUE) { ?>today<?php } else { print $add_class; } ?>" this_date="<?php print $cyear."-".$cmonth."-".$xc[$cmonth];?>" this_month="<?php print $cmonth;?>" this_year="<?php print $cyear;?>"  id="date-<?php print $cmonth."-".$xc[$cmonth];?>" onclick="viewday('0','<?php print $cyear."-".$cmonth."-".$xc[$cmonth];?>','0','0','0'); return false;">
		<div class="calday calthismonthday <?php if(($xc[$cmonth] == date('d')) AND ($cmonth == date('n')) AND ($cyear == date('Y'))==TRUE) { ?>today<?php } else { print $add_class; }  ?>">
		<div class="calDateNumber"><?php print $xc[$cmonth]; ?></div>
		<div class="calAdd">
		<!--
		<?php if($booksettings[$dow]  == "1") { ?>
		<?php if(!empty($du['book_id'])) { ?><a href="" onclick="dateavailable('<?php print $cyear;?>-<?php print $cmonth;?>-<?php print $xc[$cmonth];?>'); return false;"  class="tip" title="make available">&bull;</a><?php } else { ?><a href="" onclick="dateunavailable('<?php print $cyear;?>-<?php print $cmonth;?>-<?php print $xc[$cmonth];?>'); return false;" class="tip" title="Make Unavailable">x</a><?php } ?>
		<?php } ?> 
		<a href="" onclick="editbooking('','edit','<?php print $cyear;?>','<?php print $cmonth;?>','<?php print $xc[$cmonth];?>',''); return false;" class="tip" title="Add Event">+</a>
		-->
		</div>


		<div class="cssClear"></div>
		<?php 
		$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE ((book_date='".$cyear."-".$cmonth."-".$xc[$cmonth]."' AND book_recurring_dom='' AND book_recurring_dow='') OR book_recurring_dom='".$xc[$cmonth]."' OR book_recurring_dow='".$dow."' OR (book_recurring_y='1' AND MONTH(book_date)='".$cmonth."' AND DAYOFMONTH(book_date)='".$xc[$cmonth]."') ) AND book_unavailable_day!='1' AND book_confirmed>'0' GROUP BY book_id ORDER BY book_time ASC ");
		while($book = mysqli_fetch_array($books)) { ?>
		<div class="event <?php if($book['book_confirmed'] == "1") { ?>unconfirmed<?php } ?> <?php if($book['book_confirmed'] == "3") { ?>canceled<?php } ?>" <?php if($book['book_confirmed'] == "1") { ?>title="Unconfirmed"<?php } ?> <?php if($book['book_confirmed'] == "3") { ?>title="Canceled"<?php } ?>>
		<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?>
		<?php if($book['book_google'] == "1") { ?>
		<img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;"  title="Added to Google Calendar">
		<?php } ?>
		<?php if(($book['book_time'] !== "00:00:00") && ($book['book_all_day'] !== "1") && ($book['book_once_a_day'] !== "1") == true) {  print $book['book_time_show']; } ?> 
		<?php if($book['book_service'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?> 
		<?php if(!empty($book['book_first_name'])) { print $book['book_first_name']; } 
					if(!empty($book['book_last_name'])) { print " ".$book['book_last_name'];} ?>
		</div>
		<?php 

		}
		unset($add_class);
		?>
		<?php 
		if ($x[$cmonth]%7)  { ?>
		</div></div>
		<?php 
		}  else  {
		$rd = 0;
		?>
		</div></div><div class="clear"></div>
		<?php 
		}
		$xc[$cmonth]++;
		$x[$cmonth]++;
	}
?>

<?php if(($rd < 7)  == true) {
while($rd < 7) { 
	$nm++;

?>
	<div class="caldaycontainer caldaydiv">
		<div class="calday notmonth">
			<div class="calDateNumberNotMonth">
		<?php 
		$last_num_of_days = date("t", mktime(0,0,0,$cmonth - 1,1,$cyear)); 
		$nextmonth = date("m", mktime(0, 0, 0, $cmonth +1, 1, date('Y')));
		$nextyear = date("Y", mktime(0, 0, 0, $cmonth +1, 1, date('Y')));
		if($nm > 10) { 
			$nextday = "0".$nm;
		} else {
			$nextday = $nm;
		}
		$dow = date("l", mktime(0, 0, 0, $nextmonth, $nextday, $nextyear)); 

		print $nm; 

		
		?>
		</div>
		<div class="clear"></div>




		<?php 
		$books = whileSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE ((book_date='".$nextyear."-".$nextmonth."-".$nextday."' AND book_recurring_dom='' AND book_recurring_dow='') OR book_recurring_dom='".$nextday."' OR book_recurring_dow='".$dow."' OR (book_recurring_y='1' AND MONTH(book_date)='".$nextmonth."' AND DAYOFMONTH(book_date)='".$nextday."') ) AND book_unavailable_day!='1' AND book_confirmed>'0' GROUP BY book_id ORDER BY book_time ASC ");
		while($book = mysqli_fetch_array($books)) { ?>
		<div class="event <?php if($book['book_confirmed'] == "1") { ?>unconfirmed<?php } ?>" <?php if($book['book_confirmed'] == "1") { ?>title="Unconfirmed"<?php } ?>>
		<?php if((!empty($book['book_recurring_dom'])) || (!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_y'])) == true) { ?><span class="the-icons icon-arrows-cw" title="Recurring Event"></span><?php } ?>
		<?php if($book['book_google'] == "1") { ?>
		<img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;">
		<?php } ?>
		<a href="" onclick="viewday('<?php print $book['book_id'];?>','0','0','0','0'); return false;"><?php if(($book['book_time'] !== "00:00:00") && ($book['book_all_day'] !== "1") == true) {  print $book['book_time_show']; } ?> 
		<?php if($book['book_service'] > 0) { print $book['date_title']; } else { print $book['book_event_name']; } ?> 
		<?php if(!empty($book['book_first_name'])) { print $book['book_first_name']; } 
					if(!empty($book['book_last_name'])) { print " ".$book['book_last_name'];} ?>
		</a>
		</div>
		<?php 

		}
		unset($add_class);
		?>








		</div>
	</div>
	<?php 
	$rd++;
	}		
} ?>
<div class="clear"></div>
</div>
</div>
<div class="clear"></div>
<div>&nbsp;</div>
