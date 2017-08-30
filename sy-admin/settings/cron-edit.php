<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$('#p_email').change(function() {
	checkemail();
});

function checkemail() { 
	$.get("admin.actions.php?action=checkemail&p_email="+$("#p_email").val()+"&cron_id="+$("#cron_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The email address "+$("#p_email").val()+" already exists for another account. Search for this account or enter in a different email address.");
			$("#p_email").val("")
		}
	});
}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#p_name").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 

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
		$cron_time = $h.":".$_REQUEST['book_time_minute'].":00";
	
	if($_REQUEST['cron_id'] > 0) { 


		updateSQL("ms_crons", "
		cron_status='".$_REQUEST['cron_status']."',
		cron_email='".$_REQUEST['cron_email']."',
		cron_days='".$_REQUEST['cron_days']."',
		cron_time='".$cron_time."',
		cron_gal_access='".$_REQUEST['cron_gal_access']."',
		cron_gal_collected_email='".$_REQUEST['cron_gal_collected_email']."',
		cron_gal_purchased='".$_REQUEST['cron_gal_purchased']."',
		cron_gal_viewed='".$_REQUEST['cron_gal_viewed']."',
		cron_gal_preregister='".$_REQUEST['cron_gal_preregister']."',
		cron_gal_owner='".$_REQUEST['cron_gal_owner']."',
		cron_gal_no_order='".$_REQUEST['cron_gal_no_order']."' 
		WHERE cron_id='".$_REQUEST['cron_id']."' ");

		$_SESSION['sm'] = "Information saved";
		$cron_id=$_REQUEST['cron_id'];

	} else {



		$cron_id = insertSQL("ms_crons", "
		cron_status='".$_REQUEST['cron_status']."',
		cron_email='".$_REQUEST['cron_email']."',
		cron_days='".$_REQUEST['cron_days']."',
		cron_time='".$cron_time."',
		cron_gal_access='".$_REQUEST['cron_gal_access']."',
		cron_gal_collected_email='".$_REQUEST['cron_gal_collected_email']."',
		cron_gal_purchased='".$_REQUEST['cron_gal_purchased']."',
		cron_gal_viewed='".$_REQUEST['cron_gal_viewed']."',
		cron_gal_preregister='".$_REQUEST['cron_gal_preregister']."',
		cron_gal_owner='".$_REQUEST['cron_gal_owner']."',
		cron_gal_no_order='".$_REQUEST['cron_gal_no_order']."' 

		");
		$_SESSION['sm'] = "Account Created";

	}
	header("location: ../index.php?do=settings&action=cron");
	session_write_close();
	exit();
}
?>

<?php
	if(($_REQUEST['cron_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$cron = doSQL("ms_crons", "*", "WHERE cron_id='".$_REQUEST['cron_id']."' "); 
		if(empty($cron['cron_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($cron AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}

	?>
	<?php if($cron['cron_what'] == "galleries") { ?><div class="pc"><h2>Gallery Reminder Email</h2>This is used to send out emails about galleries expiring.</div><?php } ?>
	<?php if($cron['cron_what'] == "booking") { ?><div class="pc"><h2>Booking Reminder Email</h2>This is used to send out reminding for bookings in the booking calendar.</div><?php } ?>
	<?php if($cron['cron_what'] == "earlybird") { ?><div class="pc"><h2>Early Bird Special Reminder Email</h2>
	This is used to send out reminder emails when the early bird special of a gallery is expiring soon.
	</div><?php } ?>
	<?php if($cron['cron_what'] == "payments") {
	$em = doSQL("ms_emails","*", "WHERE email_id_name='paymentreminder' "); ?>
	<div class="pc"><h2>Secheduled Payment Reminder Email</h2>
	This is used to send out a reminder a scheduled payment is due. It will send the <a href="index.php?do=settings&action=defaultemailsedit&email_id=<?php print $em['email_id'];?>" target="_blank"><?php print $em['email_name'];?></a> default email.
	</div><?php } ?>

	<?php if($cron['cron_what'] == "giftcards") { ?><div class="pc"><h2>eGift Cards Email</h2>This will send out eGift Cards if the purchaser selects a date in the future.</div><?php } ?>

	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underlinelabel"><input type="checkbox" name="cron_status" id="cron_status" value="1" <?php if($cron['cron_status'] == "1") { ?>checked<?php } ?>> <label for="cron_status">Enable this email</label></div>

	<?php if(($cron['cron_what'] == "galleries") || ($cron['cron_what'] == "earlybird") == true)  { ?>

	<div class="underline">
		<div class="label">Email to send</div>
	<select name="cron_email" id="cron_email" class="optrequired">
	<option value="">Select Email To Send</option>
	<?php $semails = whileSQL("ms_emails", "*", "WHERE email_id!='6' AND  email_id!='7' AND  email_id!='18' AND  email_id!='7' AND  email_id!='19' AND  email_id!='21'  AND email_id_name!='viewproofs' AND email_id_name!='viewproofsrevised' AND email_id_name!='viewproofsclosed' AND email_id_name!='viewproofsadmin' ORDER BY email_name ASC ");
	while($semail = mysqli_fetch_array($semails)) { ?>
	<option value="<?php print $semail['email_id'];?>" <?php if($cron['cron_email'] == $semail['email_id']) { print "selected"; } ?>><?php print $semail['email_name'];?></option>
	<?php } ?>
	</select>
	</div>
	<div class="underlinespacer">You can create different default emails to select from in <a href="index.php?do=settings&action=defaultemails">Settings -> Default Emails</a></div>
	<div class="underline">
		<div class="label">Send <input type="text" name="cron_days" id="cron_days" class="center" size="3" value="<?php print $cron['cron_days'];?>" > days before the <?php if($cron['cron_what'] == "galleries") { ?>gallery<?php } else { ?>early bird special<?php } ?> expires.</div>
	</div>
	<?php } ?>
	<?php if($cron['cron_what'] == "booking") { ?>

	<div class="underline">
		<div class="label">Send <input type="text" name="cron_days" id="cron_days" class="center" size="3" value="<?php print $cron['cron_days'];?>" > days before the appointment.</div>
	</div>
	<?php } ?>
	<?php if($cron['cron_what'] == "payments") { ?>

	<div class="underline">
		<div class="label">Send <input type="text" name="cron_days" id="cron_days" class="center" size="3" value="<?php print $cron['cron_days'];?>" > days before payment is due.</div>
	</div>
	<?php } ?>


<div class="underline">
		<div class="label">Send at 
			<?php
			$bt = explode(":",$cron['cron_time']);
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
			</div>
		</div>

	<?php if(($cron['cron_what'] == "galleries") || ($cron['cron_what'] == "earlybird") == true)  { ?>
	<div>&nbsp;</div>
	<div class="underlinelabel">Who to send to</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_access" id="cron_gal_access" value="1" <?php if($cron['cron_gal_access'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_access">People with access to the gallery</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_preregister" id="cron_gal_preregister" value="1" <?php if($cron['cron_gal_preregister'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_preregister">People who pre-registered</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_viewed" id="cron_gal_viewed" value="1" <?php if($cron['cron_gal_viewed'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_viewed">People who viewed the gallery</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_owner" id="cron_gal_owner" value="1" <?php if($cron['cron_gal_owner'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_owner">Gallery Owner</label></div>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_purchased" id="cron_gal_purchased" value="1" <?php if($cron['cron_gal_purchased'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_purchased">People who purchased from the gallery</label></div>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_collected_email" id="cron_gal_collected_email" value="1" <?php if($cron['cron_gal_collected_email'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_collected_email">Emails collected to view the gallery</label></div>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="cron_gal_no_order" id="cron_gal_no_order" value="1" <?php if($cron['cron_gal_no_order'] == "1") { ?>checked<?php } ?>> <label for="cron_gal_no_order">People who have not purchased from the gallery..</label><br>
	 To use this option, be sure you select all the options above as this will remove email address of people that have purchased from the emails gathered above	</div>
	</div>


	<?php } ?>


	<div class="pageContent center">

	<input type="hidden" name="cron_id" value="<?php print $_REQUEST['cron_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editPeople">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['cron_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>
