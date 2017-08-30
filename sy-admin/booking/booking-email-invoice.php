<?php 
$path = "../../";
require "../w-header.php"; 
if($_REQUEST['action'] == "sendemail") { 
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	sendWebdEmail("".$_POST['email_to']."", "".$_POST['email_to_name']."", "".$_POST['from_email']."", "".$_POST['from_name']."", $subject, $message,"1");
	
	// updateSQL("ms_bookings", "book_confirmed='2' WHERE book_id='".$_POST['book_id']."' ");
	exit();
}

	$em = doSQL("ms_emails", "*", "WHERE email_id_name='bookinginvoice' ");
	if(isset($_SESSION['order_id'])) { 
		$_REQUEST['order_id'] = $_SESSION['order_id'];
		unset($_SESSION['order_id']);
	}
	$order = doSQL("ms_orders", "*, date_format(order_due_date, '".$site_setup['date_format']."') AS order_due_date_show", "WHERE order_id='".$_REQUEST['order_id']."' ");

	$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show ,date_format(DATE_ADD(book_date_added, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." %l:%i %p')  AS book_date_added",  "WHERE book_id='".$_REQUEST['book_id']."' ");
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
		$subject = "".$em['email_subject']."";
		

		$to_email = $_REQUEST['email_to'];
		$to_name = stripslashes($book['book_first_name'])." ".stripslashes($book['book_last_name']);
		$message = $em['email_message'];

		$person = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['email_to']."' ");

		if(!empty($person['p_pass_def'])) { 
			$new_login = "<br><a href=\"".$setup['url']."".$setup['temp_url_folder']."/?view=account\">".$setup['url']."".$setup['temp_url_folder']."/?view=account</a><br>";
			$new_login .= $person['p_email']."<br>";
			$new_login .= "Temporary password: ".$person['p_pass_def']."<br>Please change this password after you log in.<br>";
		} else { 
			$new_login ="";
		}

		$message = str_replace("[NEW_LOGIN_INFO]",$new_login, "$message");
		if(($book['book_once_a_day'] == "1") || ($book['book_all_day'] == "1") == true) { 
			$message = str_replace("[BOOKING_DATE_TIME]",$book['book_date'], $message);
		} else { 
			$message = str_replace("[BOOKING_DATE_TIME]",$book['book_date']." ".$book['book_time_show'], $message);
		}

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
		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		} else { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		}
		$message = str_replace("[/INVOICE_LINK]","</a>", $message);
		$message = str_replace("[INVOICE_DUE]",$order['order_due_date_show'], $message);
		$message = str_replace("[INVOICE_TOTAL]","".showPrice($order['order_total'])."", "$message");
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

		if(($book['book_once_a_day'] == "1") || ($book['book_all_day'] == "1") == true) { 
			$subject = str_replace("[BOOKING_DATE_TIME]",$book['book_date'], $subject);
		} else { 
			$subject = str_replace("[BOOKING_DATE_TIME]",$book['book_date']." ".$book['book_time_show'], $subject);
		}

		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
		$subject = str_replace("[FIRST_NAME]",stripslashes($book['book_first_name']), "$subject");
		$subject = str_replace("[LAST_NAME]",stripslashes($book['book_last_name']), "$subject");
		$subject = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$subject");
		$subject = str_replace("[INVOICE_TOTAL]","".showPrice($order['order_total'])."", "$subject");

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

		$.post('booking/booking-email-invoice.php', fields,	function (data) { 
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#sending").hide();
			// editbooking($("#book_id").val(),'');
			viewday($("#book_id").val(),'0','0');
			getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));


		});
	}
}
</script>

<div class="pc"><h3>Confirm Booking & Send Email</h3></div>
		<div id="emailsend">
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
				<div class="label">You can edit this default email in Settings > Default Emails > <?php print $em['email_name'];?>.</div> 
				<div>
					<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
					<?php 
					$email_style = true;	
					addEditor("message", "1", "300", "1"); ?>

				</div>
			</div>
			<div id="emailcomplete" class="hidden successMessage">Emails are on the way! <a href="" onclick="closewindowedit(); return false;">Close window</a></div>

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
