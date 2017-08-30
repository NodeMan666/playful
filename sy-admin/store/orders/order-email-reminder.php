<?php 
$path = "../../../";
require "../../w-header.php"; 

$order = doSQL("ms_payment_schedule LEFT JOIN ms_orders ON ms_payment_schedule.order_id=ms_orders.order_id", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show",  "WHERE id='".$_REQUEST['id']."' ");

if($_REQUEST['action'] == "sendemail") { 
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$tos = explode(";",$_POST['email_to']);
	$tonames = explode(";",$_POST['email_to_name']);
	print "XXXXXXXXXXXXX";
	print_r($tos);
	$x = 0;
	foreach($tos AS $to) { 
		$to = trim($to);
		print "<li>".$to;
		sendWebdEmail("".$to."", "".$tonames[$x]."", "".$_POST['from_email']."", "".$_POST['from_name']."", $subject, $message,"1");
		insertSQL("ms_notes","note_date='".currentdatetime()."', note_table='ms_people', note_table_id='".$person['p_id']."', note_note='Contract \'".$contract['title']."\' was emailed to ".$tonames[$x]." ".$to."' ");
		$x++;
	}

	exit();
}

	$em = doSQL("ms_emails", "*", "WHERE email_id_name='paymentreminder' ");
	if(isset($_SESSION['order_id'])) { 
		$_REQUEST['order_id'] = $_SESSION['order_id'];
		unset($_SESSION['order_id']);
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


		$subject = "".$em['email_subject']."";
		

		$to_email = $order['order_email'];
		$to_name = stripslashes($order['order_first_name']." ".$order['order_last_name']);
		$to_name_show = stripslashes($contract['signature_name']);
		if(!empty($contract['email2'])) { 
			$to_email .="; ".$contract['email2'];
			$to_name .="; ".$contract['signature_name2'];
			$to_name_show = stripslashes($contract['signature_name'])." & ".stripslashes($contract['signature_name2']);
		}
		$message = $em['email_message'];


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
		$message = str_replace("[FIRST_NAME]",stripslashes($order['order_first_name']), "$message");
		$message = str_replace("[LAST_NAME]",stripslashes($order['order_last_name']), "$message");
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

		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[CONTRACT_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/".$site_setup['contract_folder']."/index.php?contract=".$contract['link']."\">", $message);
		} else { 
			$message = str_replace("[CONTRACT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/".$site_setup['contract_folder']."/index.php?contract=".$contract['link']."\">", $message);
		}

		$message = str_replace("[DUE_DATE]",$contract['due_date'], $message);
		$message = str_replace("[PIN]",$contract['pin'], $message);
		$message = str_replace("[NAME]",$to_name_show, $message);

		$message = str_replace("[/INVOICE_LINK]","</a>", $message);
		$message = str_replace("[/LINK]","</a>", $message);
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

		$message = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$message");
		$message = str_replace("[INVOICE_TOTAL]",showPrice($order['order_total']), "$message");
		$message = str_replace("[DATE]",$order['order_date'], "$message");
		if($order['order_due_date'] <= 0) { 
			$message = str_replace("[DUE_DATE]","n/a", "$message");
		} else { 
			$message = str_replace("[DUE_DATE]",$order['order_due_date_show'], "$message");
		}


		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		} else { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		}

		$message = str_replace("[/LINK]","</a>", $message);


		$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");



		$add .= "<p>";
		while($sc = mysqli_fetch_array($scs)) { 
			if($sc['payment'] > 0) { 
				$add .= showPrice($sc['payment']) ." "._paid_." ".$sc['payment_date_show'];
			} else { 
				$add .= showPrice($sc['amount'])." "._due_." ".$sc['due_date_show'];
			}
			$add .= "<br>";
		 }
		$add.= "</p>";

		$message = str_replace("[SCHEDULED_PAYMENTS]",$add, "$message");


		$subject = str_replace("[INVOICE_NUMBER]",$order['order_id'], "$subject");


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
	fields['contract_id'] = $("#contract_id").val();

	if($("#email_confirmed").attr("checked") !== "checked") { 
		alert("Please check the confirm checkbox");
	} else if( $("#to_email").val() == "") { 
		alert("Please enter an email address to send to");

	} else { 
		$("#submitsend").hide();
		$("#checkconfirm").hide();
		$("#sending").show();

		windowloading();

		$.post('people/people-email-contract.php', fields,	function (data) { 
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#sending").hide();

		});
	}
}
</script>

<div class="pc"><h3>Email Scheduled Payment Reminder</h3></div>
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
					<div><input type="text" id="to_email" value="<?php print $to_email;?>" size="40"></div>
				</div>
				<div class="left p50">
					<div class="label">To name: </div>
					<div><input type="text" id="to_name" value="<?php print $to_name;?>" size="40"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="underline">
			To send to multiple email addresses, separate email addresses and names with a semicolon ; 
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
			<input type="hidden" id="contract_id" value="<?php print $contract['contract_id'];?>">
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


<?php require "../../w-footer.php"; ?>
