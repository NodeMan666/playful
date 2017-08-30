<?php 
$path = "../../";
require "../w-header.php"; 
if($_REQUEST['action'] == "sendemail") { 
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	sendWebdEmail("".$_POST['email_to']."", "".$_POST['email_to_name']."", "".$_POST['from_email']."", "".$_POST['from_name']."", $subject, $message,"1");
	
	updateSQL("ms_bookings", "book_confirmed='2' WHERE book_id='".$_POST['book_id']."' ");
	exit();
}

	$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '".$site_setup['date_time_format']."')  AS book_time_show ,date_format(DATE_ADD(book_date_added, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS book_date_added",  "WHERE book_id='".$_REQUEST['book_id']."' ");



	if(isset($_REQUEST['email_id'])) { 
		$em = doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['email_id']."' ");
	} else { 
		$message = $book['book_confirm_email'];
		$subject = "".$book['book_confirm_email_subject']."";
	}
		if(empty($em['email_from_email'])) {
			$from_email = $site_setup['contact_email'];
		} else {
			$from_email = $em['email_from_email'];
		}

		if(empty($em['email_from_name'])) {
			$from_name = $site_setup['website_title'];
		} else {
			$from_name = $em['email_from_name'];
		}
		

		$to_email = $_REQUEST['email_to'];
		$to_name = stripslashes($book['book_first_name'])." ".stripslashes($book['book_last_name']);

		$person = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['email_to']."' ");

		if(!empty($person['p_pass_def'])) { 
			$new_login = "<br><a href=\"".$setup['url']."".$setup['temp_url_folder']."/?view=account\">".$setup['url']."".$setup['temp_url_folder']."/?view=account</a><br>";
			$new_login .= $person['p_email']."<br>";
			$new_login .= "Temporary password: ".$person['p_pass_def']."<br>Please change this password after you log in.<br>";
		} else { 
			$new_login ="";
		}

		$message = str_replace("[NEW_LOGIN_INFO]",$new_login, "$message");

		$message = str_replace("[BOOKING_DATE]",$book['book_date'], $message);
		$message = str_replace("[BOOKING_TIME]",$book['book_time_show'], $message);
		if($book['book_service'] <= 0) { 
			$message = str_replace("[BOOKING_SERVICE]",$book['book_event_name'], $message);
		} else { 
			$message = str_replace("[BOOKING_SERVICE]",$book['date_title'], $message);
		}


		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[ACCOUNT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=account\">".$setup['url'].$setup['temp_url_folder']."/index.php?view=account</a>", "$message");
		$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
		$message = str_replace("[FIRST_NAME]",stripslashes($book['book_first_name']), "$message");
		$message = str_replace("[LAST_NAME]",stripslashes($book['book_last_name']), "$message");
		$message = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$message");
		$message = str_replace("[EMAIL]",$to_email, "$message");
		$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");

		$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[link]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[PASSWORD]",$date['password'], "$message");

		$message = str_replace("[PAGE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $message);
		$message = str_replace("[/PAGE_LINK]","</a>", $message);
		$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">", $message);
		$message = str_replace("[/LINK_TO_WEBSITE]","</a>", $message);

		$opts = explode("\n",$book['book_options']);
		foreach($opts AS $opt) { 
			if(!empty($opt)) { 
				$o = explode("|",$opt);
				if(!empty($o[0])) { 
					$options .= $o[0]; if(!empty($o[1])) { $options .= ": ".$o[1];} if($o[2] > 0) { $options .= "  ".showPrice($o[2]); } $options .= "<br>";
				}
			}
		}
		$message = str_replace("[BOOKING_OPTIONS]",$options, $message);




		$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
		$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");
		$subject = str_replace("[BOOKING_DATE]",$book['book_date'], $subject);

		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
		$subject = str_replace("[FIRST_NAME]",stripslashes($book['book_first_name']), "$subject");
		$subject = str_replace("[LAST_NAME]",stripslashes($book['book_last_name']), "$subject");

?>
<script>
function sendemail() { 
	var fields = {};
	fields['book_id'] = $("#book_id").val();
	fields['email_to'] = $("#to_email").val();
	fields['email_to_name'] = $("#to_name").val();
	fields['action'] = "sendemail";
	fields['subject'] = $("#subject").val();
	fields['message'] = $("#message").val();
	fields['from_email'] = $("#from_email").val();
	fields['from_name'] = $("#from_name").val();

	if($("#email_confirmed").attr("checked") !== "checked") { 
		alert("Please check the confirm checkbox");
	} else { 
		$("#submitsend").hide();
		$("#checkconfirm").hide();
		$("#sending").show();

		windowloading();

		$.post('booking/booking-confirm.php', fields,	function (data) { 
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#addtogooglecal").slideDown(200);
			$("#sending").hide();
			viewday($("#book_id").val(),'0','0');
			getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));


		});
	}
}


function selectdefaultemail() {
	id = $("#email_id").val();
	windowloading();
	$.get("booking/booking-confirm.php?book_id="+$("#book_id").val()+"&email_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}



</script>

<div class="pc"><h3>Confirm Booking & Send Email</h3></div>
		<div id="emailsend">

<!--		<div class="underline">
		<select name="email_id" id="email_id" onchange="selectdefaultemail();">
		<option value="">Select a different default email</option>
		<?php $semails = whileSQL("ms_emails", "*", "WHERE email_id!='6' AND  email_id!='7' AND  email_id!='18' AND  email_id!='7' AND  email_id!='19' AND  email_id!='21' AND  email_id!='22' AND email_id_name!='viewproofs' AND email_id_name!='viewproofsrevised' AND email_id_name!='viewproofsclosed' AND email_id_name!='viewproofsadmin'  AND email_id_name!='expireemail'  AND email_id_name!='invitepublic'  AND email_id_name!='inviteprivate'  AND email_id_name!='inviteprivate'  AND email_id_name!='inviteprivate'ORDER BY email_name ASC ");
		while($semail = mysqli_fetch_array($semails)) { ?>
		<option value="<?php print $semail['email_id'];?>" <?php if($_REQUEST['email_id'] == $semail['email_id']) { ?>selected<?php } ?>><?php print $semail['email_name'];?></option>
		<?php } ?>
		</select>
		</div>
		-->
			<div class="underline">
				<div class="left p50">
					<div class="label">From Email</div>
					<div><input type="text" id="from_email" value="<?php print $from_email;?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">From name: </div>
					<div><input type="text" id="from_name" value="<?php print $from_name;?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="underline">
				<div class="left p50">
					<div class="label">To Email</div>
					<div><input type="text" id="to_email" value="<?php print $book['book_email'];?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">To name: </div>
					<div><input type="text" id="to_name" value="<?php print $book['book_first_name']." ".$book['book_last_name'];?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>



			<div class="underline">
				<div class="label">Subject: </div>
				<div><input type="text" id="subject" value="<?php print $subject;?>" size="40" class="field100"></div>
			</div>
			<div class="underline">
				<div>
					<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
					<?php 
					$email_style = true;	
					addEditor("message", "1", "300", "1"); ?>

				</div>
			</div>
			<div id="emailcomplete" class="hidden successMessage">Emails are on the way! <a href="" onclick="closewindowedit(); return false;">Close window</a></div>
			<div id="addtogooglecal" class="pc center hide">
			<div>&nbsp;</div>
			<div><a href="to-google-calendar.php?book_id=<?php print $book['book_id'];?>" target="_blank"><img src="graphics/google-calendar-icon.png" style="width: 16px; height: 16px; border: none;"> <b>Add to Google Calendar</b></a>
			<br><br></div><div style="font-size: 12px; line-height: 100%;">When you click add to Google Calendar, it will open a new window for you to add it to your Google Calendar with the information populated. An icon will also appear next to the bookings you have clicked the link to add to Google Calendar.</div>
			</div>
			<div class="pc center" id="checkconfirm">
				<input type="checkbox" id="email_confirmed" value="1"> <label for="email_confirmed">Check to confirm sending this message </label>
			</div>
			<div class="pc center">
			<input type="hidden" id="book_id" value="<?php print $book['book_id'];?>">
			<input type="hidden" name="action" value="email">
			<input type="hidden" name="submitit" value="send">
			<input type="submit" name="submit" class="submit" value="Send Message" onClick="sendemail();" id="submitsend">
			<div id="sending" style="display: none;"><h3>SENDING....</h3></div>
			</div>
		</div>


		</div>
		<div class="clear"></div>
		</div>
		<div>&nbsp;</div>
		<div>&nbsp;</div>


<?php require "../w-footer.php"; ?>
