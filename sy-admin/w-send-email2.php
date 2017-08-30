<?php 
$path = "../";
require "w-header.php"; 
$full_file_url = 1;


if($_REQUEST['action'] == "sendemail") { 

	senddateemail($_REQUEST['email_to'],htmlspecialchars($_REQUEST['email_to_first_name']),htmlspecialchars($_REQUEST['email_to_last_name']));

	print "########## <pre>".$_POST['emails_tos']."</pre> ########### ";
	print $sent_to;
	print "<div id=\"emailresults\">Email sent to $total_sent people. </div>";
	exit();

}


function senddateemail($email,$first_name,$last_name) { 
	global $site_setup;
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$subject = str_replace("[FIRST_NAME]",stripslashes($first_name), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($last_name), "$subject");
	$message = str_replace("[FIRST_NAME]",stripslashes($first_name), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($last_name), "$message");
	sendWebdEmail("".$email."", "".$first_name." ".$last_name."", "".$_POST['from_email']."", "".$_POST['from_name']."", $subject, $message,"1");
}

?>
<script>

function selectemail(id) {
	$(".useroptions").slideUp(200);
	$("#"+id).slideDown(200);
}

function sendemail() { 
	var fields = {};

	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	fields['date_id'] = $("#date_id").val();
	fields['email_to'] = $("#email_to").val();
	fields['email_to_first_name'] = $("#email_to_first_name").val();
	fields['email_to_last_name'] = $("#email_to_last_name").val();
	fields['date_id'] = $("#date_id").val();
	fields['p_ids'] = $("#p_ids").val();
	fields['action'] = "sendemail";
	fields['subject'] = $("#subject").val();
	fields['message'] = $("#message").val();
	fields['subject'] = $("#subject").val();
	fields['from_email'] = $("#from_email").val();
	fields['from_name'] = $("#from_name").val();
	fields['emails_tos'] = $("#emails_tos").val();

	if($("#email_confirmed").attr("checked") !== "checked") { 
		alert("Please check the confirm checkbox");
	} else { 
	$("#submitsend").hide();
	$("#checkconfirm").hide();
	$("#sending").show();

		windowloading();

		$.post('w-send-email2.php', fields,	function (data) { 
 	 //	alert(data);
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#sending").hide();

		});
	}
}
function selectdefaultemail() {
	id = $("#email_id").val();
	windowloading();
	$.get("w-send-email2.php?email_to="+$("#email_to").val()+"&email_to_first_name="+$("#email_to_first_name").val()+"&email_to_last_name="+$("#email_to_last_name").val()+"&email_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}

function toggleid(id) { 
	$("#"+id).slideToggle(200);
}

</script>

<?php 
$emails = array(); 
$peeps = array();
if(!empty($_REQUEST['date_id'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE date_id='".$_REQUEST['date_id']."'  ");
}
?>


<div id="emailform"  class="useroptions">
	<div class="pc"><h2>Send email to: <?php print stripslashes($_REQUEST['email_to_first_name'])." ".stripslashes($_REQUEST['email_to_last_name'])." < ".$_REQUEST['email_to']." >"; ?></h2></div>
	<div class="underline">
	<select name="email_id" id="email_id" onchange="selectdefaultemail();">
	<option value="">Select a different default email</option>
	<?php $semails = whileSQL("ms_emails", "*", "WHERE email_id!='6' AND  email_id!='7' AND  email_id!='18' AND  email_id!='7' AND  email_id!='19' AND  email_id!='21' AND  email_id!='22' AND email_id_name!='viewproofs' AND email_id_name!='viewproofsrevised' AND email_id_name!='viewproofsclosed' AND email_id_name!='viewproofsadmin'  AND email_id_name!='expireemail'  AND email_id_name!='invitepublic'  AND email_id_name!='inviteprivate'  AND email_id_name!='inviteprivate'  AND email_id_name!='inviteprivate'ORDER BY email_name ASC ");
	while($semail = mysqli_fetch_array($semails)) { ?>
	<option value="<?php print $semail['email_id'];?>"><?php print $semail['email_name'];?></option>
	<?php } ?>
	</select>
	</div>


	<input type="hidden" id="p_ids" value="<?php print $p_ids;?>">
	<?php 
		

		if($_REQUEST['email_id']>0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['email_id']."' ");
		} else if(!empty($_REQUEST['email_id_name'])) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='".$_REQUEST['email_id_name']."' ");
		} else { 
			$em = doSQL("ms_emails", "*", "WHERE email_id='3' ");
		}
		if($_REQUEST['order_id'] > 0) { 
			$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
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
		

		$to_email = $_REQUEST['email_to'];
		$to_name = stripslashes($_REQUEST['email_to_first_name'])." ".stripslashes($_REQUEST['email_to_last_name']);
		$message = $em['email_message'];

		$person = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['email_to']."' ");
		if($person['p_id'] > 0) { 
			$_REQUEST['email_to_first_name'] = $person['p_name'];
			$_REQUEST['email_to_last_name'] = $person['p_last_name'];
		}

		if(!empty($person['p_pass_def'])) { 
			$new_login = "<br><a href=\"".$setup['url']."".$setup['temp_url_folder']."/?view=account\">".$setup['url']."".$setup['temp_url_folder']."/?view=account</a><br>";
			$new_login .= $person['p_email']."<br>";
			$new_login .= "Temporary password: ".$person['p_pass_def']."<br>Please change this password after you log in.<br>";
		} else { 
			$new_login ="";
		}

		$message = str_replace("[NEW_LOGIN_INFO]",$new_login, "$message");

		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		} else { 
			$message = str_replace("[INVOICE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		}
		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[ORDER_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		} else { 
			$message = str_replace("[ORDER_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=order&action=orderk&oe=".MD5($order['order_email'])."&on=".MD5($order['order_id'])."\">", $message);
		}
		$message = str_replace("[ORDER_NUMBER]",$order['order_id'], "$message");

		$message = str_replace("[/LINK]","</a>", $message);
		$message = str_replace("[/LINK]","</a>", $message);
		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[ACCOUNT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/index.php?view=account\">".$setup['url'].$setup['temp_url_folder']."/index.php?view=account</a>", "$message");
		$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
		$message = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$message");
		$message = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$message");
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

		$message = str_replace("[/LINK]","</a>", $message);

		$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
		$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");

		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
		$subject = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$subject");
		$subject = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
	?>
	<script>
	function hidebutton() { 
		$("#submitsend").hide();
		$("#sending").show();
	}

	</script>

		
		<div>
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
			<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="email_to" id="email_to"  value="<?php print htmlspecialchars($_REQUEST['email_to']);?>">
			<input type="hidden" name="email_to_first_name" id="email_to_first_name" value="<?php print htmlspecialchars(stripslashes($_REQUEST['email_to_first_name']));?>">
			<input type="hidden" name="email_to_last_name" id="email_to_last_name" value="<?php print htmlspecialchars(stripslashes($_REQUEST['email_to_last_name']));?>">
			<input type="hidden" name="do" value="news">
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

</div>
<?php require "w-footer.php"; ?>
