<?php adminsessionCheck(); ?>
<?php $date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' "); ?>

<div id="pageTitle"><a href="index.php?do=<?php print $_REQUEST['do'];?>">Site Content</a> 

<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>


<span><?php  if($date['page_home'] == "1") { print "Home Page"; }  else { print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">".$date['date_title']."</a>"; } ?> </span>
<?php
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
?>



<?php print ai_sep;?>  Email Link 
</div>
<div>&nbsp;</div>

<?php include "news.tabs.php"; ?>
<div id="roundedFormContain">


<?php 
if($_REQUEST['submitit'] == "send") {


	$message = stripslashes($_REQUEST['message']);
	$subject = stripslashes($_REQUEST['subject']);


	if(!empty($_REQUEST['email_tos'])) {
		$to_emails = explode("\r", $_REQUEST['email_tos']);
		foreach($to_emails AS $to_email) {
			$to_email = trim($to_email);
			if(!empty($to_email)) {
				$total_sent++;
				sendWebdEmail("".$to_email."", "".$to_email."", "".$_REQUEST['from_email']."", "".$_REQUEST['from_name']."", $subject, $message,"1");
			}
		}
	}
	if(!empty($_REQUEST['people_emails'])) {
		foreach($_REQUEST['people_emails'] AS $to_email) {
			$to_email = trim($to_email);
			if(!empty($to_email)) {
				sendWebdEmail("".$to_email."", "".$to_email."", "".$_REQUEST['from_email']."", "".$_REQUEST['from_name']."", $subject, $message,"1");
				$total_sent++;
			}
		}
	}
	if($total_sent == "1") { 
		$_SESSION['sm'] = "Email sent to $to_email";
	} elseif($total_sent <=0) { 
		$_SESSION['smerror'] = "No emails entered or selected. Nothing sent.";
	} else { 
		$_SESSION['sm'] = "Email sent to $total_sent people";
	}
	header("location: index.php?do=news&action=email&date_id=".$_REQUEST['date_id']."");
	session_write_close();
	exit();

	?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div class="pc center"><h3>Your message has been sent to <?php print $_REQUEST['to_email'];?></h3><br><br>
	<a href="javascript:closeFrame();">Close this window</a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<?php 

} else {


	$em = doSQL("ms_emails", "*", "WHERE email_id='20' ");
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
	$message = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$message");
	$message = str_replace("[EMAIL]",$to_email, "$message");
	$message = str_replace("[PAGE_TITLE]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$cat['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>", "$message");

	$message = str_replace("[LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$cat['cat_folder']."/".$date['date_link']."/\">".$setup['url'].$setup['temp_url_folder'].$setup['content_folder']."".$cat['cat_folder']."/".$date['date_link']."/</a>", "$message");
	$message = str_replace("[PASSWORD]",$date['password'], "$message");


	$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");

	$subject = str_replace("[FIRST_NAME]",stripslashes($_REQUEST['email_to_first_name']), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($_REQUEST['email_to_last_name']), "$subject");
	$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
?>
<script>
function hidebutton() { 
	$("#submitsend").hide();
	$("#sending").show();
}

</script>
<form method="post" name="emailcustomer" action="index.php">

	<div style="width: 30%; float: left;">
		<div style="padding-right: 16px;">
			<div class="underline">
				<div class="label"><h3>Enter email addresses to send to 1 per line</h3></div>
				<div><textarea name="email_tos" id="emails_tos" rows="6" cols="40" class="field100"></textarea></div>
			</div>
		<div>&nbsp;</div>

		<div class="underline">
			<div class="label"><h3>Select from existing people</h3></div>
			<div>Hold down shift or CTRL and click names to select multiple people. These are <U>NOT</U> selected by default. You have to click the name to select them.</div>
			<div>
			<select name="people_emails[]" size="8" multiple class="field100">
			<?php 
			$ps = whileSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date_show, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active_show", "WHERE p_id>'0'  ORDER BY p_last_name ASC"); 
			while($p = mysqli_fetch_array($ps)) { ?>
			<option value="<?php print $p['p_email'];?>"><?php print $p['p_last_name'].", ".$p['p_name']." &lt;".$p['p_email'].">"; ?></option>
			<?php } ?>
			</select>
			</div>
		</div>

		</div>

		


	</div>
	
	<div style="width: 70%; float: right;">

	<div class="underline">
		<div class="label">From Email</div>
		<div><input type="text" name="from_email" value="<?php print $from_email;?>" size="40"></div>
	</div>

	<div class="underline">
		<div class="label">From name: </div>
		<div><input type="text" name="from_name" value="<?php print $from_name;?>" size="40"></div>
	</div>
	<div class="underline">
		<div class="label">Subject: </div>
		<div><input type="text" name="subject" value="<?php print $subject;?>" size="40" class="field100"></div>
	</div>
	<div class="underline">
		<div class="label">Message:</div> 
		<div>
			<textarea name="message" id="message" cols="40" rows="12"><?php print $message;?></textarea>
			<?php 
			$email_style = true;	
			addEditor("message", "1", "500", "1"); ?>

		</div>
	</div>

	<div class="pc center">
	<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
	<input type="hidden" name="do" value="news">
	<input type="hidden" name="action" value="email">
	<input type="hidden" name="submitit" value="send">
	<input type="submit" name="submit" class="submit" value="Send Message" onClick="hidebutton();" id="submitsend">
	<div id="sending" style="display: none;"><h3>SENDING....</h3></div>
	</div>



	</div>
	<div class="clear"></div>
	</form>
	</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
<?php } ?>
