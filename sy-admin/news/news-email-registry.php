<?php 
$path = "../../";
require "../w-header.php"; 

$date = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(date_expire, '".$site_setup['date_format']."')  AS date_expire", "WHERE date_id='".$_REQUEST['date_id']."' ");


if($_REQUEST['action'] == "sendemail") { 
	$emails = array();

	if(!empty($_POST['emails_tos'])) {
		$to_emails = explode(",", $_POST['emails_tos']);
		foreach($to_emails AS $to_email) {
			$to_email = trim($to_email);
			if(!empty($to_email)) {
				if(!in_array($to_email,$emails)) { 
					$total_sent++;
					$sent_to .= "<li>".$to_email;
					senddateemail($to_email,"","");
				}
			}
		}
	}
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
function addaccess() { 
	if($("#mp_people_id").val()<=0) { 
		alert("Select a person");
	} else { 
		windowloading();
		$.get("news/news-users.php?action=addaccess&date_id="+$("#date_id").val()+"&p_id="+$("#mp_people_id").val()+"&noclose=1&nofonts=1&nojs=1", function(data) {
			$("#windoweditinner").html(data);
			$("#windowedit").slideDown(200, function() { 
				$("#windoweditclose").show();
				windowloadingdone();
			});
		});
	}
}
function removeaccess(id) { 
	windowloading();
	$.get("news/news-users.php?action=removeaccess&date_id="+$("#date_id").val()+"&mp_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
		$("#windoweditinner").html(data);
		$("#windowedit").slideDown(200, function() { 
			$("#windoweditclose").show();
			windowloadingdone();
		});
	});
}
function selectemail(id) {
	$(".useroptions").slideUp(200);
	$("#"+id).slideDown(200);
}

function sendemail() { 
	var fields = {};

	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
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
	$("#sending").show();

		windowloading();

		$.post('news/news-email-registry.php', fields,	function (data) { 
 		// alert(data);
			windowloadingdone();
			$("#emailcomplete").append($("#emailresults").html()).slideDown(200);		
			$("#sending").hide();

		});
	}
}
function selectdefaultemail() {
	id = $("#email_id").val();
	windowloading();
	$.get("news/news-users.php?date_id="+$("#date_id").val()+"&email_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
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
$p = doSQL("ms_people", "*", "WHERE p_id='".$date['reg_person']."' ");
?>


<div id="emailform"  class="useroptions ">
	<div class="pc"><h2>Send email to <?php print $p['p_name']." ".$p['p_last_name'];?></h2></div>

	<div class="underline">
	<select name="email_id" id="email_id" onchange="selectdefaultemail();">
	<option value="">Select a different default email</option>
	<?php $semails = whileSQL("ms_emails", "*", "WHERE email_id!='6' AND  email_id!='7' AND  email_id!='18' AND  email_id!='7' AND  email_id!='19' AND  email_id!='21'  AND email_id_name!='viewproofs' AND email_id_name!='viewproofsrevised' AND email_id_name!='viewproofsclosed' AND email_id_name!='viewproofsadmin' ORDER BY email_name ASC ");
	while($semail = mysqli_fetch_array($semails)) { ?>
	<option value="<?php print $semail['email_id'];?>"><?php print $semail['email_name'];?></option>
	<?php } ?>
	</select>
	</div>

	<div class="pc"><a href="" onclick="toggleid('otheremails'); return false;">Enter additional email addresses</a></div>


		<div id="otheremails" class="hidden">
			<div class="underline">
				<div class="label"><h3>Enter email addresses to send to separated by a comma (,)</h3></div>
				<div><input type="text" name="emails_tos" id="emails_tos"  class="field100" value="<?php print $p['p_email'];?>"></textarea></div>
			</div>
		<div>&nbsp;</div>
		</div>
	<input type="hidden" id="p_ids" value="<?php print $p_ids;?>">
	<?php 
		if($_REQUEST['email_id']>0) { 
			$em = doSQL("ms_emails", "*", "WHERE email_id='".$_REQUEST['email_id']."' ");
		} else { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='registry' ");
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


		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
		$message = str_replace("[FIRST_NAME]",stripslashes($p['p_name']), "$message");
		$message = str_replace("[LAST_NAME]",stripslashes($p['p_last_name']), "$message");
		$message = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$message");
		$message = str_replace("[EMAIL]",$to_email, "$message");
		$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");

		$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[link]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
		$message = str_replace("[PASSWORD]",$date['password'], "$message");

		$message = str_replace("[LINK_TO_PAGE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">", "$message");
		$message = str_replace("[/LINK_TO_PAGE]","</a>", "$message");

		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(empty($pic['pic_id'])) {
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		}
		$pic['full_url'] = true;
		$sizel = getimagefiledems($pic,'pic_large');
		$sizes = getimagefiledems($pic,'pic_pic');
		$sizet = getimagefiledems($pic,'pic_th');
		$message = str_replace("[IMAGE_LARGE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_large')."\" style=\"width:100%; max-width: ".$sizel[0]."px; height: auto;\"></a>", $message);
		$message = str_replace("[IMAGE_SMALL]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_pic')."\" style=\"width:100%; max-width: ".$sizes[0]."px; height: auto;\"></a>", $message);
		$message = str_replace("[IMAGE_THUMBNAIL]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_th')."\" style=\"width:100%; max-width: ".$sizet[0]."px; height: auto;\"></a>", $message);

		$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
		$subject = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$subject");

		$subject = str_replace("[FIRST_NAME]",stripslashes($p['p_name']), "$subject");
		$subject = str_replace("[LAST_NAME]",stripslashes($p['p_name']), "$subject");
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
				<div class="label">Message (note [FIRST_NAME] & [LAST_NAME] will be replaced when the email is sent). You can edit this default email in Settings > Default Emails > <?php print $em['email_name'];?>.</div> 
				<div>
					<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
					<?php 
					$email_style = true;	
					addEditor("message", "1", "300", "1"); ?>

				</div>
			</div>
			<div id="emailcomplete" class="hidden success">Emails are on the way! <a href="" onclick="closewindowedit(); return false;">Close</a></div>

			<div class="pc center">
				<input type="checkbox" id="email_confirmed" value="1"> Check to confirm sending this message 
			</div>
			<div class="pc center">
			<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
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
<?php require "../w-footer.php"; ?>