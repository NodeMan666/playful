<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
 function focusForm() { 
	$("#book_first_name").focus();
 }
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>

<script>

function saveformdata(classname) { 
	var fields = {};
	var stop = false;
	$('#submitButton').text("saving...");
	$('#submitButton').removeClass("submit").addClass("submitsaving");
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.attr("value"); 
			} else { 
				fields[$this.attr('name')] = "";
			}
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}

		if($this.hasClass("required")) { 
			if($this.val() == "") { 
				$this.addClass("requiredFieldEmpty");
				stop = true;
			}
		}
	});
		
	if(stop !== true) { 
		posttourl = $("#formdata").attr("data-post-url");
		$.post(posttourl, fields,	function (data) { 
			// Get the complete function name from the formdata element 
			var completefunction = $("#formdata").attr("data-complete-function");
			if (typeof window[completefunction] === 'function'){
				funok = window[completefunction]();
				e.preventDefault();
			}
			showSuccessMessage("Saved");
			setTimeout(hideSuccessMessage,4000);
			$('#submitButton').text("Save Changes");
			$('#submitButton').removeClass("submitsaving").addClass("submit");

		});
	} else { 
		$('#submitButton').text("Save Changes");
		$('#submitButton').removeClass("submitsaving").addClass("submit");
	}
}


function editbookingcomplete() { 
	getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
	viewday($("#book_id").val(),0,'','','',0,1);
}

function eventname() { 
	if($("#book_service").val() !== "") { 
		//$("#book_event_name").val("THIS");
		$("#book_event_name").attr("disabled",true);
		$("#event_name").slideUp(100);
		$("#book_length_hours").val($('option:selected', $("#book_service")).attr('hours'));
		$("#book_length_minutes").val($('option:selected', $("#book_service")).attr('minutes'));
	} else { 
		// $("#book_event_name").val("");
		$("#book_event_name").attr("disabled",false);
		$("#event_name").slideDown(100);
		$("#book_length").val("0");
	}
}



function selectrecur(m) { 
	if(m != "1") { 
		if($("#book_recurring_y").attr("checked")) { 
			$("#book_date").fadeIn(100);

			$("#book_recurring_dom").val("").attr("disabled",true).removeClass("required");
			$("#book_recurring_dow").val("").attr("disabled",true).removeClass("required");
			$("#recurw").attr("checked",false);
			$("#recurd").attr("checked",false);
			$("#recurdom").addClass("disabledrow");
			$("#recurdow").addClass("disabledrow");
		}
	}
	if($("#recurw").attr("checked")) { 
		$("#recurdom").addClass("disabledrow");
		$("#recurdow").removeClass("disabledrow");
		$("#book_recurring_dom").val("").attr("disabled",true).removeClass("required");
		$("#book_recurring_dow").val("").attr("disabled",false).addClass("required");
		$("#book_recurring_y").attr("checked",false);
			$("#book_date").fadeOut(100);
	}
	if($("#recurd").attr("checked")) { 
		$("#recurdow").addClass("disabledrow");
		$("#recurdom").removeClass("disabledrow");
		$("#book_recurring_dow").val("").attr("disabled",true).removeClass("required");
		$("#book_recurring_dom").val("").attr("disabled",false).addClass("required");
		$("#book_recurring_y").attr("checked",false);
			$("#book_date").fadeOut(100);
	}
}

function showrecur() { 
	$("#recur").slideDown(200);
	$("#book_date").fadeOut(100);
}

function cancelrecur() { 
	$("#book_recurring_dow").val("");
	$("#book_recurring_dom").val("");
	$("#recurd").attr("checked",false);
	$("#recurw").attr("checked",false);
	$("#book_recurring_y").attr("checked",false);

	$("#recur").slideUp(200);
	$("#book_date").fadeIn(100);
}

$("#book_recurring_dom").focus(function() {
	$("#recurd").attr("checked",true);
	selectrecur();
});

$("#book_recurring_dow").focus(function() {
	$("#recurw").attr("checked",true);
	selectrecur();
});
function reminderonly(){ 
	if($("#reminder_only").attr("checked")) { 
		$("#book_length").fadeOut(100);
		$("#book_length_minutes").val("");
		$("#book_length_hours").val("");
	} else { 
		$("#book_length").fadeIn(100);
	}
}

function allday() { 
	if($("#all_day_event").attr("checked")) { 
		$("#book_time").hide();
		$("#book_length").hide();
		$("#book_length_minutes").val("");
		$("#book_length_hours").val("");

	} else { 
		$("#book_time").show();
		$("#book_length").show();
	}
}

function bookingsection(id) { 
	$(".bookingsection").slideUp(200, function() { 
		$(".bst").removeClass("bookingsectiontabon").addClass("bookingsectiontab");
	});

	setTimeout(function() { 
		$("#"+id).slideDown(200, function() { 
			$("#"+id+"tab").removeClass("bookingsectiontab").addClass("bookingsectiontabon");
		});
	},200);
}
</script>

<style>
.disabledrow { color: #d4d4d4; } 
.bookingsection { margin-bottom: 16px; } 
.bookingsectiontab { background: #747474; color: #FFFFFF; cursor: pointer; margin-bottom: 8px; } 
.bookingsectiontab:hover { background: #000000;  } 
.bookingsectiontab .inner { padding: 8px; text-align: center; } 
.bookingsectiontabon {  background: #000000; color: #FFFFFF; cursor: pointer; margin-bottom: 8px; }
.bookingsectiontabon .inner { padding: 8px; text-align: center; } 
</style>
<?php
$booksettings = doSQL("ms_bookings_settings", "*", "");
if($_REQUEST['updatenotes'] == "yes") { 
	updateSQL("ms_bookings","book_notes='".addslashes(stripslashes($_REQUEST['book_notes']))."' WHERE book_id='".$_REQUEST['book_id']."' ");
	exit();
}

if($_REQUEST['cancelbooking'] == "yes") { 
	updateSQL("ms_bookings","book_cancel_notes='".addslashes(stripslashes($_REQUEST['book_cancel_notes']))."', book_confirmed='3' WHERE book_id='".$_REQUEST['book_id']."' ");
	exit();
}


if($_REQUEST['savedata']=="yes") { 
	if($_REQUEST['book_confirmed'] !== "2") {
		$_REQUEST['book_confirmed'] = "1";
	}
	$book_length = ($_REQUEST['book_length_hours'] * 60) + $_REQUEST['book_length_minutes'];

	if($_REQUEST['all_day_event'] =="1") { 
		$book_time = "00:00:00";
	} else { 
		if($_REQUEST['book_time_apm'] == "pm") { 
			if($_REQUEST['book_time_hour'] < 12) { 
				$h = $_REQUEST['book_time_hour'] + 12;
			} else { 
				$h = 12;
			}
		} else { 
			if($_REQUEST['book_time_hour'] == "12") { 
				$h = 0;
			} else { 
				$h = $_REQUEST['book_time_hour'];
			}
		}
		$book_time = $h.":".$_REQUEST['book_time_minute'].":01";
	}

	if($_REQUEST['book_id'] > 0) { 
		updateSQL("ms_bookings", "
		book_first_name='".addslashes(stripslashes($_REQUEST['book_first_name']))."' , 
		book_last_name='".addslashes(stripslashes($_REQUEST['book_last_name']))."', 
		book_email='".addslashes(stripslashes($_REQUEST['book_email']))."',
		book_event_name='".addslashes(stripslashes($_REQUEST['book_event_name']))."',
		book_recurring_dow='".addslashes(stripslashes($_REQUEST['book_recurring_dow']))."',
		book_recurring_dom='".addslashes(stripslashes($_REQUEST['book_recurring_dom']))."',
		book_recurring_y='".addslashes(stripslashes($_REQUEST['book_recurring_y']))."',

		book_deposit='".addslashes(stripslashes($_REQUEST['book_deposit']))."',
		book_options='".addslashes(stripslashes($_REQUEST['book_options']))."',

		book_reminder='".addslashes(stripslashes($_REQUEST['book_reminder']))."',


		book_total='".addslashes(stripslashes($_REQUEST['book_total']))."',

		book_date='".addslashes(stripslashes($_REQUEST['book_date']))."',
		book_time='".addslashes(stripslashes($book_time))."',
		book_phone='".addslashes(stripslashes($_REQUEST['book_phone']))."',
		book_account='".addslashes(stripslashes($_REQUEST['book_account']))."',
		book_service='".addslashes(stripslashes($_REQUEST['book_service']))."',
		book_unavailable='".addslashes(stripslashes($_REQUEST['book_unavailable']))."',
		book_length='".addslashes(stripslashes($book_length))."', 
		book_confirmed='".addslashes(stripslashes($_REQUEST['book_confirmed']))."' 

		WHERE book_id='".$_REQUEST['book_id']."' ");
		// $_SESSION['sm'] = "Account Saved";
		$book_id=$_REQUEST['book_id'];

	} else {
		$book_id = insertSQL("ms_bookings", "
		book_first_name='".addslashes(stripslashes($_REQUEST['book_first_name']))."' , 
		book_last_name='".addslashes(stripslashes($_REQUEST['book_last_name']))."', 
		book_email='".addslashes(stripslashes($_REQUEST['book_email']))."',
		book_event_name='".addslashes(stripslashes($_REQUEST['book_event_name']))."',
		book_date='".addslashes(stripslashes($_REQUEST['book_date']))."',
		book_recurring_dow='".addslashes(stripslashes($_REQUEST['book_recurring_dow']))."',
		book_recurring_dom='".addslashes(stripslashes($_REQUEST['book_recurring_dom']))."',
		book_recurring_y='".addslashes(stripslashes($_REQUEST['book_recurring_y']))."',
		book_reminder='".addslashes(stripslashes($_REQUEST['book_reminder']))."',

		book_deposit='".addslashes(stripslashes($_REQUEST['book_deposit']))."',
		book_options='".addslashes(stripslashes($_REQUEST['book_options']))."',
		book_total='".addslashes(stripslashes($_REQUEST['book_total']))."',

		book_time='".addslashes(stripslashes($book_time))."',
		book_phone='".addslashes(stripslashes($_REQUEST['book_phone']))."',
		book_account='".addslashes(stripslashes($_REQUEST['book_account']))."',
		book_service='".addslashes(stripslashes($_REQUEST['book_service']))."',
		book_unavailable='".addslashes(stripslashes($_REQUEST['book_unavailable']))."',
		book_length='".addslashes(stripslashes($book_length))."',
		book_confirmed='".addslashes(stripslashes($_REQUEST['book_confirmed']))."' 



		");
		// $_SESSION['sm'] = "Account Created";
		$_SESSION['book_id'] = $book_id;
	}
	// header("location: ../index.php?do=booking");
	// session_write_close();
	exit();
}
?>
<div class="right textright"><a href="" onclick="sideeditclose(); return false;" class="the-icons icon-cancel" title="close"></a></div>
<div>&nbsp;</div>

<?php if($_REQUEST['action'] == "edit") { 
	if(($_REQUEST['book_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$book  = doSQL("ms_bookings", "*", "WHERE book_id='".$_REQUEST['book_id']."' "); 
		if(empty($book['book_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($book AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	} else { 
		if($_REQUEST['day'] < 10) { 
			$day = "0".$_REQUEST['day'];
		} else { 
			$day = $_REQUEST['day'];
		}
		$book['book_date'] = $_REQUEST['year']."-".$_REQUEST['month']."-".$day;
		$book['book_time'] = "09:00:01";
		if(empty($_REQUEST['month'])) { 
			$book['book_date'] = date("Y")."-".date("m")."-".date("d");
		}

		$lastbook = doSQL("ms_bookings", "*", "WHERE book_submitted<='0' ORDER BY book_id DESC ");
		// $book['book_time'] = $lastbook['book_time'];
		$book['book_reminder'] = $lastbook['book_reminder'];

	}
	if($setup['demo_mode'] == true) { 
		$book['book_first_name'] = get_starred($book['book_first_name']);
		$book['book_last_name'] = get_starred($book['book_last_name']);
		$book['book_email'] = "demo@demo.mode";
	}

	if((!empty($book['book_recurring_dow'])) || (!empty($book['book_recurring_dom']))  || (!empty($book['book_recurring_y'])) == true) { 
		$recur = true;	
		if(!empty($book['book_recurring_y'])) { 
			$recur_y = true;
		}
	} ?>


<?php if($book['book_confirmed'] == "3") { ?>
<div class="error">You are editing a canceled event. If you make changes, it will uncancel the event.</div>
<div>&nbsp;</div>
<?php } ?>

	<div id="formdata" data-post-url="booking/booking-edit.php" data-complete-function="editbookingcomplete"></div>	
	<div  class="bookingsectiontabon bst" onclick="bookingsection('bookingwhat');" id="bookingwhattab">
		<div class="inner">Service or Event</div>
	</div>
	<div id="bookingwhat" class="bookingsection">
		<div class="underline">
		<?php
		 $services = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE cat_type='booking' ORDER BY date_title ASC ");
		if(mysqli_num_rows($services) > 0) { ?>
			<div class="label">Service</div>
			<div>
			<select name="book_service" id="book_service" class="formfield field100 " onchange="eventname();">
			<option value="">Select</option>
			<?php
			while($service = mysqli_fetch_array($services)) { ?>
			<option value="<?php print $service['date_id'];?>" <?php if($book['book_service'] == $service['date_id']) { ?>selected<?php } ?> hours="<?php print $service['book_length_hours'];?>" minutes="<?php print  $service['book_length_minutes'];?>"><?php print $service['date_title'];?></option>
			<?php } ?>
			</select>
			</div>
			<?php } ?>
			<div id="event_name" <?php if($book['book_service'] > 0) { ?>class="hide"<?php } ?> style="margin-top: 12px;">
				<div><?php if(mysqli_num_rows($services) > 0) { ?>Or <?php } ?>Enter Event Name</div>
				<div><input type="text" name="book_event_name" id="book_event_name" class="formfield field100 " value="<?php print $book['book_event_name'];?>"></div>
			</div>
		</div>
		<div class="underline">
			<div class="label">Date & Time</div>
			<div>	<input type="text" name="book_date" id="book_date" size="12" class="datepicker formfield required center <?php if(($recur == true) && ($recur_y !== true) == true){ ?>hide<?php } ?>" value="<?php print $book['book_date'];?>">
			<span id="book_time" class="<?php if($book['book_time'] == "00:00:00") { ?>hide<?php } ?>">
			<?php
			$bt = explode(":",$book['book_time']);
			$bh = $bt[0];
			if($bh >= 12) {
				$bamp = "pm";
			} else { 
				$bamp = "am";
			}
			if($bh > 12) { 
				$bh = $bh - 12;
			} else if($bh == 0) {
				$bh = 12;
			}
			$bm = $bt[1];
			?>
			<select name="book_time_hour" id="book_time_hour" class="formfield center">
			<?php $h = 1;
			while($h <= 12) { ?>
			<option value="<?php print $h; ?>" <?php if($h == $bh) { ?>selected<?php } ?>><?php print $h;?></option>
			<?php
			$h++;
			}
			?>
			</select>
			<select name="book_time_minute" id="book_time_minute" class="formfield">
			<?php $m = 0;
			while($m <= 55) { 
				if($m < 10) { 
					$m = "0".$m;
				}?>
			<option value="<?php print $m; ?>" <?php if($m == $bm) { ?>selected<?php } ?>><?php print $m;?></option>
			<?php
			$m = $m+5;
			}
			?>
			</select>

			<select name="book_time_apm" id="book_time_apm" class="formfield">
			<option value="am" <?php if($bamp == "am") { ?>selected<?php } ?>>AM</option>
			<option value="pm" <?php if($bamp == "pm") { ?>selected<?php } ?>>PM</option>
			</select>
			</span>
			<!-- 
			<select name="book_time" class="formfield required <?php if($book['book_time'] == "00:00:00") { ?>hide<?php } ?>" id="book_time">
			<option value="00:00:00">Select Time </option> 
			<?php 
			while($tm < 24) {
				while($tmm < 60) {
					if(date("H:i:s", mktime($tm,$tmm,1,1,1,1)) == $book['book_time']) { $selected = "selected"; }
				print "<option value=\"".date("H:i:s", mktime($tm,$tmm,1,1,1,1))."\" $selected>".date("h:i a", mktime($tm,$tmm,1,1,1,1))."</option>"; 
				unset($selected);
				$tmm = $tmm + 10;
				}
			$tm++;
			$tmm = 0;
			}

		?></select> 
		
		-->
			
			</div>
				<div class="pc"><input type="checkbox" id="all_day_event" name="all_day_event" class="formfield" value="1" <?php if($book['book_time'] == "00:00:00") { ?>checked<?php } ?> onchange="allday();"> <label for="all_day_event">All Day</label></div>
				<div class="pc"><input type="checkbox" name="book_reminder" class="formfield"  id="book_reminder" value="1" <?php if($book['book_reminder'] == "1") { print "checked"; } ?>> <label for="book_reminder">Reminder Only</label></div>

	
		</div>


		<div  id="book_length" class="underline <?php if($book['book_time'] == "00:00:00") {  print "hide"; } ?>">
		<?php 
		$hours = floor($book['book_length'] / 60);  
		$minutes = $book['book_length'] - ($hours * 60);
		
		if(($book['book_id'] > 0) && ($book['book_length'] <= 0) == true) { 
			$reminder_only = true;
		}
		?>

			<div >
			<div class="label">
				<div class="left">Length</div>
				<div class="clear"></div>
			</div>
				<div class="left" style="margin-right: 16px;">
					<div class="label">Hours</div>
					<div>
					<select name="book_length_hours"  id="book_length_hours" class="formfield">
						<?php
						$h = 0;
						while($h <=12) { ?>
						<option value="<?php print $h;?>" <?php if($h == $hours) { ?>selected<?php } ?>><?php print $h;?></option>
						<?php
						$h++;
						} ?>
						</select>
					</div>
				</div>
				<div class="left"  style="margin-right: 48px;">
					<div class="label">Minutes</div>
					<div>
						<select name="book_length_minutes"  id="book_length_minutes" class="formfield">
						<?php
						$m = 0;
						while($m < 60) { ?>
						<option value="<?php print $m;?>" <?php if($m == $minutes) { ?>selected<?php } ?>><?php print $m;?></option>
						<?php
						$m = $m + 5;
;
						} ?>
						</select>
					</div>
			</div>

			<div class="clear"></div>
		</div>
		</div>

			<div class="underline"><a href="" onclick="showrecur(); return false;">Create recurring event</a></div>

		<div id="recur" class="underline  <?php if($recur !== true) { ?>hide<?php } ?>">


			<div id="recury" <?php if(empty($book['book_recurring_y'])) { ?>class="disabledrow"<?php } ?>>
			<input type="checkbox" name="book_recurring_y" id="book_recurring_y" class="formfield " value="1" <?php if($book['book_recurring_y'] == "1") { ?>checked<?php } ?>  onchange="selectrecur();"> <label for="book_recurring_y">This date yearly</label></div>
			<div>or</div>



			<div id="recurdom" <?php if(empty($book['book_recurring_dom'])) { ?>class="disabledrow"<?php } ?>><input type="radio" name="recurd" id="recurd" class="formfield" value="dom"  onchange="selectrecur('1');" <?php if(!empty($book['book_recurring_dom'])) { ?>checked<?php } ?>> On the <input type="text" name="book_recurring_dom" id="book_recurring_dom" class="formfield center  " value="<?php print $book['book_recurring_dom'];?>" size="2"> day of each month</div>
			<div>or</div>
			<div id="recurdow" <?php if(empty($book['book_recurring_dow'])) { ?>class="disabledrow"<?php } ?>><input type="radio" name="recurd" id="recurw"  class="formfield" value="dow" onchange="selectrecur('1');" <?php if(!empty($book['book_recurring_dow'])) { ?>checked<?php } ?>> Every 
			<select name="book_recurring_dow" id="book_recurring_dow" class=" formfield">
			<option value="">select day</option>
			<option value="Sunday" <?php if($book['book_recurring_dow'] == "Sunday") { print "selected"; } ?>>Sunday</option>
			<option value="Monday" <?php if($book['book_recurring_dow'] == "Monday") { print "selected"; } ?>>Monday</option>
			<option value="Tuesday" <?php if($book['book_recurring_dow'] == "Tuesday") { print "selected"; } ?>>Tuesday</option>
			<option value="Wednesday" <?php if($book['book_recurring_dow'] == "Wednesday") { print "selected"; } ?>>Wednesday</option>
			<option value="Thursday" <?php if($book['book_recurring_dow'] == "Thursday") { print "selected"; } ?>>Thursday</option>
			<option value="Friday" <?php if($book['book_recurring_dow'] == "Friday") { print "selected"; } ?>>Friday</option>
			<option value="Saturday" <?php if($book['book_recurring_dow'] == "Saturday") { print "selected"; } ?>>Saturday</option>
			</select>
			each week.</div>
			<div><a href="" onclick="cancelrecur(); return false;">cancel</a></div>

		</div>
		</div>



	<div class="bookingsectiontab bst" onclick="bookingsection('bookingpricing');" id="bookingpricingtab">
		<div class="inner">Pricing & Options</div>
	</div>
	<div id="bookingpricing" class="bookingsection hide">
		<div class="underline">
			<div class="label">Options</div>
			<div><textarea  name="book_options" id="book_options" class="formfield  field100 " rows="3"><?php print $book['book_options'];?></textarea></div>
		</div>

		<div class="underline">
			<div class="label">Total</div>
			<div><input type="text" name="book_total" id="book_total" class="formfield  field100 " value="<?php print $book['book_total'];?>"></div>
		</div>

		<div class="underline">
			<div class="label">Deposit</div>
			<div><input type="text" name="book_deposit" id="book_deposit" class="formfield  field100 " value="<?php print $book['book_deposit'];?>"></div>
		</div>
	</div>

	<div class="bookingsectiontab bst" onclick="bookingsection('bookingperson');" id="bookingpersontab">
		<div class="inner">Person</div>
	</div>
	<div id="bookingperson" class="bookingsection hide">
			<div class="underline">
			<?php if(($book['book_id'] <= 0) && ($_REQUEST['p_id'] > 0) == true) { 
				$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
				$book['book_account'] = $p['p_id'];
				$book['book_first_name'] = $p['p_name'];
				$book['book_last_name'] = $p['p_last_name'];
				$book['book_email'] = $p['p_email'];
				$book['book_phone'] = $p['p_phone'];
			}
			?>
			<div id="peopleselect">
			<select name="book_account" id="book_account" class=" formfield" onchange="setpeopleinfo();">
			<option value="">Account....</option>
			<?php $ps = whileSQL("ms_people", "*", "ORDER BY p_last_name ASC ");
			while($p = mysqli_fetch_array($ps)) { ?>
			<option value="<?php print $p['p_id'];?>" <?php if($book['book_account'] == $p['p_id']) { print "selected"; } ?> first_name="<?php print htmlspecialchars($p['p_name']);?>"  last_name="<?php print htmlspecialchars($p['p_last_name']);?>"  email="<?php print htmlspecialchars($p['p_email']);?>"  phone="<?php print htmlspecialchars($p['p_phone']);?>" ><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email'];?>)</option>
			<?php } ?>
			</select>
			</div>
			<div><a href="" onclick="showHide('peoplesearch','book_account'); return false;">Search</a></div>
			<div id="peoplesearch" class="hide">
				<div class="p80 left">
				<input type="text" name="pq" id="pq" class="field100"></div>
				<div class="p20 center left"><a href="" onclick="searchpeople('peopleselect','book_account'); return false;">go</a></div>
				<div class="clear"></div>
			</div>
			</div>
			<div class="underline">
				<div class="label">First Name</div>
				<div><input type="text" name="book_first_name" id="book_first_name" class="formfield field100 " value="<?php print $book['book_first_name'];?>"></div>
			</div>
			<div class="underline">
				<div class="label">Last Name</div>
				<div><input type="text" name="book_last_name" id="book_last_name" class="formfield  field100 " value="<?php print $book['book_last_name'];?>"></div>
			</div>
			<div class="underline">
				<div class="label">Email Address</div>
				<div><input type="text" name="book_email" id="book_email" class="formfield  field100 " value="<?php print $book['book_email'];?>"></div>
			</div>
			<div class="underline">
				<div class="label">Phone</div>
				<div><input type="text" name="book_phone" id="book_phone" class="formfield  field100 " value="<?php print $book['book_phone'];?>"></div>
			</div>

		</div>

		<?php if($book['book_submitted'] == "1") { ?>
		<div class="underline center">
			<div><input type="checkbox" name="book_confirmed" id="book_confirmed" class="formfield" value="2" <?php if($book['book_confirmed'] == "2") { ?>checked<?php } ?>> <label for="book_confirmed">Confirmed</label></div>
		</div>
		<?php } else { ?>
		<input type="hidden" name="book_confirmed" id="book_confirmed" class="formfield" value="2">
		<?php } ?>
	<div class="pc center buttons">
	<input type="hidden" name="book_id" id="book_id" class="formfield" value="<?php print $_REQUEST['book_id'];?>">
	<input type="hidden" name="savedata" value="yes" class="formfield">

	<a href="" id="submitButton" onclick="saveformdata('formfield','formdata'); return false;" class="submit"><?php if(empty($book['book_id'])) { ?>Add Event<?php } else { ?>Save Changes<?php } ?></a>
	</div>
	<?php if($book['book_id'] > 0) { ?>
	<div class="pc center"><a href="" onclick="viewday('<?php print $book['book_id'];?>'); return false;">Cancel</a></div>
	<?php } else { ?>
	<div class="pc center"><a href="" onclick="sideeditclose(); return false;">Cancel</a></div>

	<?php } ?>
<?php } else { ?>



<?php } ?>
<?php require "../w-footer.php"; ?>
