<?php function minicalendar() { 
	global $setup,$site_setup;
if(!ctype_alnum($_REQUEST['book_service'])) { die(); } 
$service = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['book_service']."' ");

define(_deposit_,$service['_deposit_']);
define(_booking_comments_or_notes_,$service['_booking_comments_or_notes_']);
define(_booking_send_request_,$service['_booking_send_request_']);
define(_booking_your_information_,$service['_booking_your_information_']);
define(_booking_success_title_,$service['_booking_success_title_']);
define(_booking_success_message_,$service['_booking_success_message_']);
define(_booking_additional_options_,$service['_booking_additional_options_']);
define(_booking_additional_options_message_,$service['_booking_additional_options_message_']);
define(_booking_deposit_required_message_,$service['_booking_deposit_required_message_']);
define(_booking_select_time_,$service['_booking_select_time_']);
define(_learn_more_,$service['_learn_more_']);
define(_book_now_,$service['_book_now_']);
define(_booking_your_information_text_,$service['_booking_your_information_text_']);
define(_booking_your_information_text_above_send_,$service['_booking_your_information_text_above_send_']);


$booksettings = doSQL("ms_bookings_settings", "*", "");
if((!empty($_REQUEST['month'])) && (!is_numeric($_REQUEST['month'])) == true) { die(); } 
if((!empty($_REQUEST['year'])) && (!is_numeric($_REQUEST['year'])) == true) { die(); } 
if((!empty($_REQUEST['day'])) && (!is_numeric($_REQUEST['day'])) == true) { die(); } 

if((!empty($_REQUEST['month']))&&(!empty($_REQUEST['year']))==true) { 
	$cmonth = $_REQUEST['month'];
	$cyear = $_REQUEST['year'];
} else { 
	$cmonth = date("m");
	$cyear = date("Y");
}
// setlocale(LC_TIME, "fr_FR.UTF8");

$num_of_days = date("t", mktime(0,0,0,$cmonth,1,$cyear)); 
$cmonth_name[$cmonth] = strftime("%B", strtotime($cyear."-".$cmonth."-1"));
$firstday[$cmonth] = date("w", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
$lastday = date("t", mktime(0, 0, 0, $cmonth, 1, $cyear)); 
$lmy = date("Y", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
$lmn = strftime("%b", strtotime(date("Y-m-d", mktime(0, 0, 0, $cmonth - 1, 1, $cyear))));

$lmm = date("m", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
$lmy = date("Y", mktime(0, 0, 0, $cmonth - 1, 1, $cyear)); 
$nmm = date("m", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 
$nmy = date("Y", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 

$nmn = strftime("%b", strtotime(date("Y-m-d", mktime(0, 0, 0, $cmonth + 1, 1, $cyear))));
//date("Y-m-d", mktime(0, 0, 0, $cmonth + 1, 1, $cyear)); 

$sun = strftime("%a", strtotime("2010-01-03"));
$mon = strftime("%a", strtotime("2010-01-04"));
$tues = strftime("%a", strtotime("2010-01-05"));
$wed = strftime("%a", strtotime("2010-01-06"));
$thurs = strftime("%a", strtotime("2010-01-07"));
$fri = strftime("%a", strtotime("2010-01-08"));
$sat = strftime("%a", strtotime("2010-01-02"));


?>

<div id="calendarselect">
	<div id="thecalendar">
		<div class="pc center <?php if($service['book_special_event'] == "1") { ?>hide<?php } ?>">
			<div class="left p30 center"><a href="" onclick="getCalendar('<?php print $lmm;?>','<?php print $lmy;?>',''); return false;">&larr; <?php print $lmn; ?></a></div>
			<div class="left p40 center"><b><?php print $cmonth_name[$cmonth]." ". $cyear; ?></b></div>
			<div class="left p30 center"><a href="" onclick="getCalendar('<?php print $nmm;?>','<?php print $nmy;?>',''); return false;"><?php print $nmn; ?> &rarr;</a></div>
			<div class="clear"></div>
		<div>&nbsp;</div>
		</div>
		<div class="<?php if($service['book_special_event'] == "1") { ?>hide<?php } ?>">
		<div id="calHeader">
			<div class="caldaycontainerday"><div class="callabel center"><?php print $sun;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $mon;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $tues;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $wed;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $thurs;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $fri;?></div></div>
			<div class="caldaycontainerday"><div class="callabel center"><?php print $sat;?></div></div>
			<div class="cssClear"></div>
		</div>
		</div>
		<div class="dayscontainer <?php if($service['book_special_event'] == "1") { ?>hide<?php } ?>">

		<?php 
		$xc[$cmonth] = 1;
		$less_days[$cmonth] = $firstday[$cmonth];
		$x[$cmonth] = $firstday[$cmonth] + 1;
		if($less_days[$cmonth] > 7) {
			$less_days[$cmonth] = $less_days[$cmonth] - 8;
		}
		if($less_days[$cmonth] == 7) { $less_days[$cmonth] = 1; }
		while ($less_days[$cmonth] > 0) { ?>
		<div class="caldaycontainer">
			<div class="calday notmonth">
			<?php 
			$last_num_of_days = date("t", mktime(0,0,0,$cmonth - 1,1,$cyear)); 
			print date("d", mktime(0, 0, 0, $cmonth - 1, $last_num_of_days - ($less_days[$cmonth] - 1), $cyear)); 
			?>
			</div>
		</div>
		<?php 
		$less_days[$cmonth] = $less_days[$cmonth] - 1;
		}
		while($xc[$cmonth] <= $num_of_days) {
			$rd++; 
			$all_day_slots = $service['book_per_time'];
			if($all_day_slots  <= 0) { 
				$all_day_slots  = 1;
			}

			$dus = whileSQL("ms_bookings", "*, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_date='".$cyear."-".$cmonth."-".$xc[$cmonth]."' AND (book_confirmed='1' OR book_confirmed='2' OR book_unavailable_day='1' ) AND  (book_time='00:00:00' AND book_reminder<='0')  GROUP BY book_id ORDER BY book_time ASC ");
			$du = mysqli_num_rows($dus);
			if($du >= $all_day_slots) { 
				$unavailableday = true;
				$add_class = "unavailableday";
			}

			if($service['custom_book_days'] > 0) { 
				$dow = date("l", mktime(0, 0, 0, $cmonth, $xc[$cmonth], $cyear)); 
				if($service[$dow]  <= 0) { 
					$unavailableday = true;
					$add_class = "unavailableday";
				}
			} else { 
				$dow = date("l", mktime(0, 0, 0, $cmonth, $xc[$cmonth], $cyear)); 
				if($booksettings[$dow]  <= 0) { 
					$unavailableday = true;
					$add_class = "unavailableday";
				}
			}
			if($xc[$cmonth] <= 9) { 
				$d = "0".$xc[$cmonth];
			} else { 
				$d = $xc[$cmonth];
			}
			if(($cyear."-".$cmonth."-".$d) < date("Y-m-d")) { 
				$unavailableday = true;
				$add_class = "unavailableday";
			}
			if($service['book_max_days'] > 0) { 
				$end = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + $service['book_max_days'], date('Y'))); 
			}
			$start = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + $service['book_lead_time'], date('Y'))); 
			if(($cyear."-".$cmonth."-".$d) < $start) { 
				$unavailableday = true;
				$add_class = "unavailableday";
			}
			if($service['book_max_days'] > 0) { 
				if(($cyear."-".$cmonth."-".$d) > $end) { 
					$unavailableday = true;
					$add_class = "unavailableday";
				}
			}
			if($booksettings[''.$dow.'_ado'] == "1") {
				if($service['book_all_day'] !== "1") { 
					$unavailableday = true;
					$add_class = "unavailableday";
				}
			}

			if($service['book_once_a_day'] == "1") { 
				$obook = doSQL("ms_bookings", "*", "WHERE book_service='".$service['date_id']."' AND book_date='".$cyear."-".$cmonth."-".$xc[$cmonth]."' AND (book_confirmed='1' OR book_confirmed='2') ");
				if($obook['book_id'] > 0) { 
					$unavailableday = true;
					$add_class = "unavailableday";
				}
			}

			$specialevent = doSQL("ms_calendar", "*", "WHERE book_special_event='1' AND book_special_event_date='".$cyear."-".$cmonth."-".$xc[$cmonth]."' AND date_public='1'  ");
			if($specialevent['date_id'] > 0) { 
				$unavailableday = true;
				$add_class = "unavailableday";
			}
			?>
			<div class="caldaycontainer">
				<div class="calday <?php print $add_class;?> <?php if(($xc[$cmonth] == $_REQUEST['day']) AND ($cmonth == $_REQUEST['month']) AND ($cyear == $_REQUEST['year'])==TRUE) { ?>today<?php } ?>">
			<?php if($unavailableday == true) { ?><span><?php print $xc[$cmonth]; ?></span><?php } else { ?>
			<?php if(($xc[$cmonth] == $_REQUEST['day']) AND ($cmonth == $_REQUEST['month']) AND ($cyear == $_REQUEST['year'])==TRUE) { } else { ?>
			<a href="" onclick="getCalendar('<?php print $cmonth;?>','<?php print $cyear;?>','<?php print $xc[$cmonth];?>'); return false;" class="selectdate"><?php } ?><?php print $xc[$cmonth]; ?></a><?php } ?>
				</div>
			</div>
			<?php 

			unset($unavailableday);
			unset($add_class);
			if ($x[$cmonth]%7)  { 

			}  else  {
			$rd = 0;
			?>
			<div class="clear"></div>
			<?php 
			}
			$xc[$cmonth]++;
			$x[$cmonth]++;
		}

		if(($rd < 7)  == true) {
			while($rd < 7) { 
				$nm++;
			?>
			<div class="caldaycontainer">
			<div class="calday notmonth">
			<?php 
			$last_num_of_days = date("t", mktime(0,0,0,$cmonth - 1,1,$cyear)); 
			print date("d", mktime(0, 0, 0, $cmonth +1, $nm, $cyear)); 
			?>
			</div>
		</div>
		<?php 
		$rd++;
		}		
	} 
	?>
	<div class="clear"></div>
	</div>
	<div class="clear"></div>

	</div>
	<div class="<?php if($service['book_special_event'] == "1") { ?>hide<?php } ?>">&nbsp;</div>
	<div class="nofloatsmall" >
	<?php if($_REQUEST['day'] > 0) { 
		$dow = date("l", mktime(0, 0, 0, $cmonth, $_REQUEST['day'], $cyear)); 
	?>
	<div class="pc center bookingselecttime"><?php print _booking_select_time_;?></div>
	<div style="width: 49%; float: left;">
	<?php 
	if($service['book_special_event'] == "1") {

		$book_length = ($service['book_length_hours'] * 60) + $service['book_length_minutes'] + $add_time;

		$tbsplit = round(($service['book_special_event_end'] - $service['book_special_event_start']) * (60 / $book_length) / 2);
	} else { 
		$book_length = ($service['book_length_hours'] * 60) + $service['book_length_minutes'] + $add_time;
		$tbsplit = round(($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) * (60 / $booksettings[''.$dow.'_time_blocks']) / 2);
	}
	// print "total hours: ".($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time'])." , blocks: ".$booksettings[''.$dow.'_time_blocks']." || "; print ($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) * (60 / $booksettings[''.$dow.'_time_blocks']);

	if($service['book_special_event'] == "1") { 
		$splittime = explode(":",$service['book_special_event_start']);
		// print "<br>split time: ".date("H:i:s", mktime($splittime[0],$splittime[1] + ((($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) / 2) * 60),1,1,1,1));
		$splittimecol = date("H:i:s", mktime($splittime[0],$splittime[1] + ((($service['book_special_event_end'] - $service['book_special_event_start']) / 2) * 60),1,1,1,1));



	} else { 
		if($service['custom_book_days'] > 0) { 
			$splittime = explode(":",$service['book_start_time']);
			// print "<br>split time: ".date("H:i:s", mktime($splittime[0],$splittime[1] + ((($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) / 2) * 60),1,1,1,1));
			$splittimecol = date("H:i:s", mktime($splittime[0],$splittime[1] + ((($service['book_end_time'] - $service['book_start_time']) / 2) * 60),1,1,1,1));
		} else { 
			$splittime = explode(":",$booksettings[''.$dow.'_start_time']);
			// print "<br>split time: ".date("H:i:s", mktime($splittime[0],$splittime[1] + ((($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) / 2) * 60),1,1,1,1));
			$splittimecol = date("H:i:s", mktime($splittime[0],$splittime[1] + ((($booksettings[''.$dow.'_end_time'] - $booksettings[''.$dow.'_start_time']) / 2) * 60),1,1,1,1));
		}
	}
		?>
	<ul>
	<?php
	if($service['book_special_event'] == "1") {
		$t = explode(":",$service['book_special_event_start']);
		$end_time = $service['book_special_event_end'];
		$sd = explode("-",$service['book_special_event_date']);
		$cmonth = $sd[1];
		$_REQUEST['day'] = $sd[2];
		$cyear = $sd[0];
	} else { 

		if($service['custom_book_days'] > 0) { 
			$t = explode(":",$service['book_start_time']);
			$end_time = $service['book_end_time'];
		} else { 
			$t = explode(":",$booksettings[''.$dow.'_start_time']);
			$end_time = $booksettings[''.$dow.'_end_time'];
		}
	}
	$h = $t[0];
	$m = $t[1];

	if($service['custom_book_days'] > 0) { 
		$booksettings[''.$dow.'_time_blocks'] = $service['book_blocks'];
	}


	while(date("H:i:s", mktime($h,$m,1,1,1,1)) < $end_time) { 
		$tb++;
			if($service['book_special_event'] == "1") {
					$add_check_time = $book_length;
			} else { 
				if($book_length >  $booksettings[''.$dow.'_time_blocks']) { 
					$add_check_time = $book_length;
				} else {
					$add_check_time =  $booksettings[''.$dow.'_time_blocks'];
				}
			}
			
			$slots = $service['book_per_time'];
			if($slots <= 0) { 
				$slots = 1;
			}
			$bookeds = whileSQL("ms_bookings", "*", "WHERE (book_date='".$cyear."-".$cmonth."-".$_REQUEST['day']."' OR book_recurring_dow='".$dow."' OR book_recurd='".$_REQUEST['day']."' OR book_unavailable='1' ) AND 
			(book_time='".date("H:i:s", mktime($h,$m,1,1,1,1))."' OR (book_time>'".date("H:i:s", mktime($h,$m,1,1,1,1))."' AND book_time<'".date("H:i:s", mktime($h,$m + $add_check_time,1,1,1,1))."')) AND book_length>'0' AND (book_confirmed='1' OR book_confirmed='2')  ");
			$booked = mysqli_num_rows($bookeds);
			if($booked >= $slots) { 

				
				$booking = doSQL("ms_bookings", "*", "WHERE (book_date='".$cyear."-".$cmonth."-".$_REQUEST['day']."' OR book_recurring_dow='".$dow."' OR book_recurd='".$_REQUEST['day']."' OR book_unavailable='1' ) AND 
				(book_time='".date("H:i:s", mktime($h,$m,1,1,1,1))."' OR (book_time>'".date("H:i:s", mktime($h,$m,1,1,1,1))."' AND book_time<'".date("H:i:s", mktime($h,$m + $add_check_time,1,1,1,1))."')) AND book_length>'0' AND (book_confirmed='1' OR book_confirmed='2')  ");

				$bt = explode(":",$booking['book_time']);
				$b_end_time = date("H:i:s", mktime($bt[0],$bt[1] + $booking['book_length'],1,1,1,1));
				$b_next_time = date("H:i:s", mktime($h,$m + + $booking['book_length'],1,1,1,1));
				$workingHours = (strtotime($b_end_time) - strtotime($b_next_time));
				$wh = ($workingHours / 60);
				if($wh < 0) { 
					$wh = 0;
				}
				// print "<li>BL: ".$booking['book_length']." ".$booking['book_event_name']." Book Time: ".date("H:i:s", mktime($h,$m,1,1,1,1));
				$m = $m + $booking['book_length'] + $wh;
			} else { 

				if(empty($setup['calendar_time_format_a'])) { 
					$setup['calendar_time_format_a'] = "g:i A";
				}
			?>
			<li><a href="" onclick="picktime('<?php print date("H:i:s", mktime($h,$m,1,1,1,1));?>'); return false;" <?php if($slots > 1) { print "title=\"".$booked." / ".$slots."\""; } ?>><?php print  date($setup['calendar_time_format_a'], mktime($h,$m,1,1,1,1));?> </a></li>

				<?php
			if($service['book_special_event'] == "1") {
					$m = $m + $book_length;
				} else { 
					$m = $m + $booksettings[''.$dow.'_time_blocks']; 
				}
			}
			if($service['book_special_event'] !== "1") {

				if(($m% $booksettings[''.$dow.'_time_blocks']) > 0) { 
					$m = $m + ($booksettings[''.$dow.'_time_blocks'] - ($m%$booksettings[''.$dow.'_time_blocks']));
				}
			}
		

		if(date("H:i:s", mktime($h,$m,1,1,1,1)) >= $splittimecol) { 
			if($splitted !== true) { 
				$splitted = true;?>
			</ul></div>
			<div style="width: 49%; float: right;">
				<ul>
		<?php 
			}
		}
	} ?>
	</ul>
	<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<?php } ?>
	</div>




</div>

<?php } 

function bookingOptions($opt,$class,$prod) { 
	global $site_setup,$total_images,$setup;

	?>

<script>
$(document).ready(function(){

   $(".productoptionselect li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#"+$(this).attr('fid')).val($(this).attr('sel_id')).attr("price",$(this).attr('price'));
		setTimeout(function(){
			updatebookingprice();
		}, 100 );

		
    });
});

</script>
<?php 
	if($opt['opt_required'] == "1") { 
		$isrequired = "bookoptionrequired";
	}
	// print "<h2>".$opt['package_buy_all']." - ".$opt['total_images']."</h2>";
	if(($opt['opt_type'] == "text") || ($opt['opt_type'] == "reg_key") == true){ 
		$price = $opt['opt_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $opt['opt_price'] * $opt['total_images'];
		}

		if(($opt['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
			$opt['opt_price'] = $opt['opt_price'] + (($opt['opt_price'] * $site_setup['include_vat_rate']) / 100);
		}
		print "<div class=\"productconfigs\">"; print "<h3>".$opt['opt_name'];  if($price > 0) { print " +".showPrice($price)." "; } print "</h3></div>";
		print "<div class=\"productconfigsoptions\"><input type=\"text\"  id=\"opt-".$opt['opt_id']."\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\" class=\"$class bookoption inputtext $isrequired field100\" prodid=\"".$opt['opt_photo_prod']."\" price=\"".$opt['opt_price']."\" "; if($opt['opt_type'] == "reg_key") { print "value=\"".$_REQUEST['reg']."\""; } print "></div>";
	}


	if($opt['opt_type'] == "checkbox") { 
		$price = $opt['opt_price_checked'];
		if($opt['package_buy_all'] == "1") { 
			$price = $opt['opt_price_checked'] * $opt['total_images'];
		}

		if(($opt['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
			$opt['opt_price_checked'] = $opt['opt_price_checked']+ (($opt['opt_price_checked'] * $site_setup['include_vat_rate']) / 100);
		}
		?>
		<div class="productconfigs"><input type="checkbox" onchange="updatebookingprice();" fieldname="<?php print htmlspecialchars($opt['opt_name']);?>" id="opt-<?php print $opt['opt_id'];?>" name="opt-<?php print $opt['opt_id'];?>" value="1" class="<?php print $class;?> bookoption inputcheckbox <?php print $isrequired;?>" prodid="<?php print $opt['opt_photo_prod'];?>"  price="<?php print $opt['opt_price_checked'];?>" > <label for="opt-<?php print $opt['opt_id'];?>"><h3 style="display: inline;"><?php print $opt['opt_name'];  		
		if($price > 0) { print " ".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); } ?>		
		
		</h3></label></div>
		<?php 
	}

	if($opt['opt_type'] == "dropdown") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";
		print "<select name=\"opt-".$opt['opt_id']."\"  id=\"opt-".$opt['opt_id']."\" fieldname=\"".htmlspecialchars($opt['opt_name'])."\"  class=\"$class dropdown bookoption inputdropdown $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" style=\"width: 100%; padding: 4px;\"  onchange=\"updatebookingprice();\">\r\n";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order, sel_id ASC ");
//		if($opt['opt_required'] =="1") { 
//			print "<option value=\"\" disabled>".$opt['opt_name']."</option>";
//		} else { 
			print "<option value=\"\" price=\"0\">"._select_option_."</option>\r\n";
//		}
		while($sel = mysqli_fetch_array($sels)) { 
		$price = $sel['sel_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $sel['sel_price'] * $opt['total_images'];
		}
		if(($opt['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
			$sel['sel_price'] = $sel['sel_price'] + (($sel['sel_price'] * $site_setup['include_vat_rate']) / 100);
		}

			print "<option value=\"".$sel['sel_id']."\" price=\"".$sel['sel_price']."\" "; if($sel['sel_default'] == "1") { print "selected"; } print ">".$sel['sel_name'].""; 
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
			
			
			print "</option>";
		}
		print "</select>";
		print "</div>";
	}




	if($opt['opt_type'] == "tabs") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";
		$dsel = doSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' AND sel_default='1' ORDER BY sel_order, sel_id ASC ");

		print "<input type=\"hidden\"  onchange=\"updatebookingprice();\" name=\"opt-".$opt['opt_id']."\"  id=\"opt-".$opt['opt_id']."\" fieldname=\"".htmlspecialchars($opt['opt_name'])."\"  class=\"$class bookoption inputtabs $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" opttype=\"tabs\" price=\"0\" value=\"".$dsel['sel_id']."\" defval=\"".$dsel['sel_id']."\">\r\n";
		?>
		<ul class="productoptionselect" id="ul-opt-<?php print $opt['opt_id'];?>">
		<?php 
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order, sel_id ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
		$price = $sel['sel_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $sel['sel_price'] * $opt['total_images'];
		}
		if(($opt['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
			$sel['sel_price'] = $sel['sel_price'] + (($sel['sel_price'] * $site_setup['include_vat_rate']) / 100);
		}

			print "<li sel_id=\"".$sel['sel_id']."\" fid=\"opt-".$opt['opt_id']."\" price=\"".$sel['sel_price']."\" class=\""; if($sel['sel_default'] == "1") { print "on"; } print " taboption\">".$sel['sel_name'].""; 
			
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
			
			print "</li>";
		}
		print "</ul>";
		print "<div class=\"clear\"></div>";
		print "</div>";
	}


	if($opt['opt_type'] == "radio") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";

		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
			print "<nobr><input type=\"radio\"  price=\"".$sel['sel_price']."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"".$sel['sel_id']."\" ";  if($sel['sel_default'] == "1") { print "checked"; } print " class=\"$class bookoption inputradio\" prodid=\"".$opt['opt_photo_prod']."\"> ".$sel['sel_name'].""; if($sel['sel_price'] > 0) { print " + ".showPrice($sel['sel_price']); } print "</nobr> ";
		}
		print "</div>";
	}
	if(!empty($opt['opt_descr'])) { 
		print "<div class=\"productconfigsoptions\">".nl2br($opt['opt_descr'])."</div>";
	}
}

?>