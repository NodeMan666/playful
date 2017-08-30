<?php 
$path = "../../";
require "../w-header.php"; 
$date = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(date_expire, '".$site_setup['date_format']."')  AS date_expire", "WHERE date_id='".$_REQUEST['date_id']."' ");

if($_REQUEST['action'] == "addaccess") { 
	$ck = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND mp_people_id='".$_REQUEST['p_id']."' ");
	if(empty($ck['mp_id'])) { 
		insertSQL("ms_my_pages", "mp_date_id='".$date['date_id']."', mp_people_id='".$_REQUEST['p_id']."' , mp_date='".date('Y-m-d H:i:s')."' ");
	}
}

if($_REQUEST['action'] == "removeaccess") { 
	$ck = doSQL("ms_my_pages", "*", "WHERE mp_id='".$_REQUEST['mp_id']."' ");
	if(!empty($ck['mp_id'])) { 
		deleteSQL("ms_my_pages", "WHERE mp_id='".$_REQUEST['mp_id']."' ", "1");
	}
}

if($_REQUEST['action'] == "sendemail") { 
	$ps = explode("|",$_POST['p_ids']);
	foreach($ps AS $p) { 
		if(!empty($p)) { 
			$person = doSQL("ms_people", "*", "WHERE p_id='".$p."' ");
			if(!empty($person['p_email'])) { 
				print "<li>".$person['p_email'];
				$ct++;
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				$subject = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$subject");
				$subject = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$subject");
				$message = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$message");
				$message = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$message");

				if(!empty($person['p_pass_def'])) { 
					$new_login = "<br><a href=\"".$setup['url']."".$setup['temp_url_folder']."/?view=account\">".$setup['url']."".$setup['temp_url_folder']."/?view=account</a><br>";
					$new_login .= $person['p_email']."<br>";
					$new_login .= "Temporary password: ".$person['p_pass_def']."<br>Please change this password after you log in.<br>";
				} else { 
					$new_login ="";
				}

				$message = str_replace("[NEW_LOGIN_INFO]",$new_login, "$message");

				if($ct > 1) { $emails_to.=", "; } 
				$emails_to .= $person['p_email']."";
				sendWebdEmail("".$person['p_email']."", "".$person['p_name']." ".$person['p_last_name']."", "".$_POST['from_email']."", "".$_POST['from_name']."", stripslashes($subject), stripslashes($message),"1");
			}
		}
	}


	if($_REQUEST['revision'] == "1") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
		updateSQL("ms_proofing_status", "status='0' WHERE id='".$cks['id']."' ");
		insertSQL("ms_proofing_status", "status='0', date_id='".$_REQUEST['date_id']."', emailed_to='".$emails_to."', re_opened='".date('Y-m-d H:i:s')."' ");
	} elseif($_REQUEST['closeproject'] == "1") { 
		$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
		updateSQL("ms_proofing_status", "status='2', closed='".date('Y-m-d H:i:s')."' WHERE id='".$cks['id']."' ");
	} else { 
		insertSQL("ms_proofing_status", "status='0', date_id='".$_REQUEST['date_id']."', emailed_to='".$emails_to."', date='".date('Y-m-d H:i:s')."' ");
	}


}


?>
<div class="buttons">
<a href="" onclick="selectemail('peoplelist'); return false;">Clients</a> 
<a href="" onclick="selectemail('emailform'); return false;">Send Email</a> 
</div>
<div>&nbsp;</div>
<script>
function addaccess() { 
	if($("#mp_people_id").val()<=0) { 
		alert("Select a person");
	} else { 
		windowloading();
		$.get("news/news-users-proofing.php?action=addaccess&date_id="+$("#date_id").val()+"&p_id="+$("#mp_people_id").val()+"&revision="+$("#revision").val()+"&closeproject="+$("#closeproject").val()+"&noclose=1&nofonts=1&nojs=1", function(data) {
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
	$.get("news/news-users-proofing.php?action=removeaccess&date_id="+$("#date_id").val()+"&revision="+$("#revision").val()+"&closeproject="+$("#closeproject").val()+"&mp_id="+id+"&noclose=1&nofonts=1&nojs=1", function(data) {
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
	fields['revision'] = $("#revision").val();
	fields['closeproject'] = $("#closeproject").val();

	if($("#email_confirmed").attr("checked") !== "checked") { 
		alert("Please check the confirm checkbox");
	} else { 
	$("#submitsend").hide();
	$("#sending").show();

		windowloading();

		$.post('news/news-users-proofing.php', fields,	function (data) { 
//			alert(data);
			windowloadingdone();
			$("#emailcomplete").slideDown(200);		
			$("#sending").hide();
			setTimeout(function(){location.reload(true)},1500);

		});
	}
}

</script>

<?php 
$emails = array(); 
$peeps = array();
?>
<div id="peoplelist" class="useroptions  <?php if(($_REQUEST['revision'] == "1")||($_REQUEST['closeproject'] == "1")==true) { print "hidden"; } ?>">
	<div class="pc"><h2>Clients with access to <?php print $date['date_title'];?></h2></div>
	<?php 
	$ps = whileSQL("ms_people", "*", "WHERE p_id>'0' ORDER BY p_last_name ASC ");
	if(mysqli_num_rows($ps) > 0) { ?>
	<div class="underline">
	<input type="hidden" id="date_id" value="<?php print $date['date_id'];?>">
	<select name="mp_people_id" id="mp_people_id">
	<option value="">Add Access</option>
	<?php 
		while($p = mysqli_fetch_array($ps)) { 
			if(countIt("ms_my_pages", "WHERE mp_date_id='".$date['date_id']."' AND mp_people_id='".$p['p_id']."' ")<=0) { ?>
			<option value="<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email']; ?>)</option>
			<?php } ?>
		<?php } ?>
		</select>
		<a href="" onclick="addaccess(); return false;">Add</a>
	</div>
	<?php } ?>

	<?php 	
	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	if(mysqli_num_rows($ps) <=0) { ?>
	<div class="pc">No clients added</div>
	<?php } ?>

	<?php 
	while($p = mysqli_fetch_array($ps)) { 
		$first_email = $p['p_email'];
		$first_name = $p['p_name']." ".$p['p_last_name'];
		if(!in_array($p['p_email'],$emails)) { 
			array_push($emails,$p['p_email']);
		}
		if(!in_array($p['p_id'],$peeps)) { 
			$p_ids = $p_ids."|".$p['p_id'];
			array_push($peeps,$p['p_id']);
		}

		?>
	<div class="underline">
		<div class="left p10"><a href="" onclick="removeaccess('<?php print $p['mp_id'];?>'); return false;">remove</a></div>
		<div class="left p35"><a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?></a></div>
		<div class="left p35"><?php print $p['p_email'];?></div>
		<div class="left p20 textright"><?php print $p['mp_date'];?></div>
		<div class="clear"></div>
	</div>

	<?php } ?>
	<div>&nbsp;</div>


</div>




<div id="emailform"  class="useroptions <?php if(($_REQUEST['revision'] !== "1")&&($_REQUEST['closeproject'] !== "1")==true) { print "hidden"; } ?>">
<?php if(count($emails) <=0) { ?>
<div class="error">No people to send to</div>
<?php } else { ?>

	<div class="pc"><h2>Send email to 
	<?php if(count($emails) == "1") { ?>
	<?php print $first_name." &lt".$first_email.">";?>
	<?php } else { ?>
	<?php print count($emails); ?> people
	<?php } ?>
	<?php if($_REQUEST['revision'] == "1") { print " & re-open project for review"; } ?>
	</h2></div>
	<input type="hidden" id="p_ids" value="<?php print $p_ids;?>">
	<?php 

		if($_REQUEST['revision'] == "1") { 			
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='viewproofsrevised' ");
		} elseif($_REQUEST['closeproject'] == "1") { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='viewproofsclosed' ");
		} else { 
			$em = doSQL("ms_emails", "*", "WHERE email_id_name='viewproofs' ");
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
		// $message = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$message");
		// $message = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$message");
		$message = str_replace("[EXPIRATION_DATE]",$date['date_expire'], "$message");
		$message = str_replace("[EMAIL]",$to_email, "$message");
		$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");
		$message = str_replace("[ACCOUNT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."/?view=account\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."/?view=account</a>", "$message");

		$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/</a>", "$message");
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

		// $subject = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$subject");
		// $subject = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$subject");
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
		<div id="emailcomplete" class="hidden success">Emails are on the way!</div>
	
			<div class="pc center">
				<input type="checkbox" id="email_confirmed" value="1"> Check to confirm sending this message 
			</div>
			<div class="pc center">
			<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="do" value="news">
			<input type="hidden" name="action" value="email">
			<input type="hidden" id="revision" value="<?php print $_REQUEST['revision'];?>">
			<input type="hidden" id="closeproject" value="<?php print $_REQUEST['closeproject'];?>">
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
	<?php } ?>
</div>
<?php require "../w-footer.php"; ?>