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
	window.location.href="index.php?do=booking&view=settings";
}

function eventname() { 
	if($("#book_service").val() !== "") { 
		//$("#book_event_name").val("THIS");
		$("#book_event_name").attr("disabled",true);
		$("#event_name").slideUp(100);
	} else { 
		// $("#book_event_name").val("");
		$("#book_event_name").attr("disabled",false);
		$("#event_name").slideDown(100);
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

</script>

<style>
.disabledrow { color: #d4d4d4; } 
</style>
<?php

if($_REQUEST['savedata']=="yes") { 

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

	if($_REQUEST['book_id'] > 0) { 
		updateSQL("ms_bookings", "
		book_first_name='".addslashes(stripslashes($_REQUEST['book_first_name']))."' , 
		book_last_name='".addslashes(stripslashes($_REQUEST['book_last_name']))."', 
		book_email='".addslashes(stripslashes($_REQUEST['book_email']))."',
		book_event_name='".addslashes(stripslashes($_REQUEST['book_event_name']))."',
		book_recurd='".addslashes(stripslashes($_REQUEST['book_event_name']))."',
		book_recurring_dow='".addslashes(stripslashes($_REQUEST['book_recurring_dow']))."',
		book_recurring_dom='".addslashes(stripslashes($_REQUEST['book_recurring_dom']))."',
		book_recurring_y='".addslashes(stripslashes($_REQUEST['book_recurring_y']))."',

		book_date='".addslashes(stripslashes($_REQUEST['book_date']))."',
		book_time='".addslashes(stripslashes($book_time))."',
		book_phone='".addslashes(stripslashes($_REQUEST['book_phone']))."',
		book_account='".addslashes(stripslashes($_REQUEST['book_account']))."',
		book_service='".addslashes(stripslashes($_REQUEST['book_service']))."',
		book_unavailable='".addslashes(stripslashes($_REQUEST['book_unavailable']))."',
		book_length='".addslashes(stripslashes($_REQUEST['book_length']))."' ,
		book_confirmed='2'


		WHERE book_id='".$_REQUEST['book_id']."' ");
		$_SESSION['sm'] = "Account Saved";
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

		book_time='".addslashes(stripslashes($book_time))."',
		book_phone='".addslashes(stripslashes($_REQUEST['book_phone']))."',
		book_account='".addslashes(stripslashes($_REQUEST['book_account']))."',
		book_service='".addslashes(stripslashes($_REQUEST['book_service']))."',
		book_unavailable='".addslashes(stripslashes($_REQUEST['book_unavailable']))."',
		book_length='".addslashes(stripslashes($_REQUEST['book_length']))."',
		book_confirmed='2'


		");
		// $_SESSION['sm'] = "Account Created";
		// $_SESSION['book_id'] = $book_id;
	}
	// header("location: ../index.php?do=booking");
	// session_write_close();
	exit();
}
?>
<div class="right textright"><a href="" onclick="sideeditclose(); return false;" class="the-icons icon-cancel" title="close"></a></div>
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
	<div id="formdata" data-post-url="booking/booking-unavailable.php" data-complete-function="editbookingcomplete"></div>


	<div class="pc"><?php if(empty($book['book_id'])) { ?><h2>Unavailable Time</h2><?php } else { ?><h2>Edit Unavailable Time</h2><?php } ?>	</div>
		<div class="underline">
			<div id="event_name" style="margin-top: 12px;">
				<div>Reason</div>
				<div><input type="text" name="book_event_name" id="book_event_name" class="formfield field100 inputtitle required" value="<?php print $book['book_event_name'];?>"></div>
			</div>
		</div>
		<div class="underline">
			<div class="label">Time</div>
			<div>
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
			</div>	
		</div>

		<div class="underline">
			<div class="label">Length (in minutes)</div>
			<div>

			<select name="book_length" id="book_length" class="formfield">
			<?php $m = 0;
			while($m < 240) { 
				if($m < 10) { 
					$m = "0".$m;
				}?>
			<option value="<?php print $m; ?>" <?php if($m == $book['book_length']) { ?>selected<?php } ?>><?php print $m;?></option>
			<?php
			$m = $m+5;
			}
			?>
			</select>

			</div>
		</div>


	<div class="pageContent center">

	<input type="hidden" name="book_id" id="book_id" class="formfield" value="<?php print $_REQUEST['book_id'];?>">
	<input type="hidden" name="book_unavailable" id="book_unavailable" class="formfield" value="1">
	<input type="hidden" name="savedata" value="yes" class="formfield">
	<a href="" id="submitButton" onclick="saveformdata('formfield','formdata'); return false;">Save Changes</a>

	<br><a href="" onclick="sideeditclose(); return false;">Cancel</a>
	</div>

<?php } else { ?>

<?php 
if(isset($_SESSION['book_id'])) { 
	$book_id = $_SESSION['book_id'];
	unset($_SESSION['book_id']);
} else { 
	$book_id = $_REQUEST['book_id'];
}

$book  = doSQL("ms_bookings LEFT JOIN ms_bookings_services ON ms_bookings.book_service=ms_bookings_services.service_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_id='".$book_id."' ");  ?>
<div>Who</div>
<div class="pc"><h2><?php print $book['book_first_name'];?> <?php print $book['book_last_name'];?></h2></div>
<div>&nbsp;</div>
<div>What </div>
<div class="pc"><h2><?php print $book['service_name'];?></h2></div>
<div>&nbsp;</div>
<div>When </div>
<div class="pc"><h2><?php print $book['book_date'];?> <?php print $book['book_time_show'];?></h2></div>
<div>&nbsp;</div>
<div>Duration </div>
<div class="pc"><h2><?php if($book['service_length'] < 1) { print ($book['service_length'] * 60)." Minutes"; } else { print $book['service_length']; if($book['service_length'] == "1") { print "Hour"; } else { print " Hours";} }?></h2></div>
<div>&nbsp;</div>
<?php if(!empty($book['book_email'])) { ?>
<div>Email </div>
<div class="pc"><h2><?php print $book['book_email'];?></h2></div>
<div>&nbsp;</div>
<?php } ?>
<?php if(!empty($book['book_phone'])) { ?>
<div>Phone </div>
<div class="pc"><h2><?php print $book['book_phone'];?></h2></div>
<div>&nbsp;</div>
<?php } ?>

<?php if(!empty($book['book_customer_notes'])) { ?>
<div>Customer Notes </div>
<div class="pc"><h2><?php print nl2br($book['book_customer_notes']);?></h2></div>
<div>&nbsp;</div>
<?php } ?>


<div class="pc center"><a href="" onclick="editbooking('<?php print $book['book_id'];?>','edit'); return false;">edit</a>   
<a href="" onclick="deletebooking('<?php print $book['book_id'];?>','edit'); return false;">delete</a>
<a href="" onclick="return false;">duplicate</a>

</div>




<?php } ?>
<?php require "../w-footer.php"; ?>
