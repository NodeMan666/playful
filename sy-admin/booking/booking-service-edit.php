<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>

$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#service_name").focus();
 }

</script>
<?php
$booksettings = doSQL("ms_bookings_settings", "*", "");

if($_POST['submitit']=="yes") { 
	if($_REQUEST['service_id'] > 0) { 
		updateSQL("ms_bookings_services", "
		service_name='".addslashes(stripslashes($_REQUEST['service_name']))."' , 
		service_length_hours='".addslashes(stripslashes($_REQUEST['service_length_hours']))."', 
		service_length_minutes='".addslashes(stripslashes($_REQUEST['service_length_minutes']))."',
		service_all_day='".addslashes(stripslashes($_REQUEST['service_all_day']))."',
		service_description='".addslashes(stripslashes($_REQUEST['service_description']))."',
		service_taxable='".addslashes(stripslashes($_REQUEST['service_taxable']))."',

		service_amount='".addslashes(stripslashes($_REQUEST['service_amount']))."',
		service_deposit='".addslashes(stripslashes($_REQUEST['service_deposit']))."'

		WHERE service_id='".$_REQUEST['service_id']."' ");
		$_SESSION['sm'] = "Account Saved";
		$service_id=$_REQUEST['service_id'];

	} else {
		$service_id = insertSQL("ms_bookings_services", "
		service_name='".addslashes(stripslashes($_REQUEST['service_name']))."' , 
		service_length_hours='".addslashes(stripslashes($_REQUEST['service_length_hours']))."', 
		service_length_minutes='".addslashes(stripslashes($_REQUEST['service_length_minutes']))."',
		service_all_day='".addslashes(stripslashes($_REQUEST['service_all_day']))."',
		service_description='".addslashes(stripslashes($_REQUEST['service_description']))."',
		service_taxable='".addslashes(stripslashes($_REQUEST['service_taxable']))."',

		service_amount='".addslashes(stripslashes($_REQUEST['service_amount']))."',
		service_deposit='".addslashes(stripslashes($_REQUEST['service_deposit']))."'

		");
		$_SESSION['sm'] = "Service Saved";

	}
	header("location: ../index.php?do=booking&view=services");
	session_write_close();
	exit();
}
?>

<?php
if(($_REQUEST['service_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$service = doSQL("ms_bookings_services", "*", "WHERE service_id='".$_REQUEST['service_id']."' "); 
		if(empty($service['service_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($service AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	if( $service['service_amount'] <= 0) { 
		 $service['service_amount'] = "00.00";
	}
	if( $service['service_deposit'] <= 0) { 
		 $service['service_deposit'] = "00.00";
	}

	?>
	<div class="pc"><?php if(empty($service['service_id'])) { ?><h1>Create New Service</h1><?php } else { ?><h1>Edit Service</h1><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
		<div class="underline">
			<div class="label">Service Name</div>
			<div><input type="text" name="service_name" id="service_name" class="optrequired field100" value="<?php print $service['service_name'];?>" ></div>
		</div>
		<div class="underline">
			<div class="p50 left">
				<div class="label">Price</div>
				<div><?php print $site_setup['currency_sign'];?><input type="text" name="service_amount" id="service_amount" size="8" class="center" value="<?php print $service['service_amount'];?>"></div>
			</div>
			<div class="p50 left">
				<div class="label">Deposit Percentage</div>
				<div><input type="text" name="service_deposit" id="service_deposit" size="8" class="center" value="<?php print $service['service_deposit'];?>">%</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<input type="checkbox" name="service_taxable" id="service_taxable" value="1" <?php if($service['service_taxable'] == "1") { ?>checked<?php } ?>> <label for="service_taxable">Taxable</label>
		</div>
		<div class="underline">
			<div class="left p20">
			<div>&nbsp;</div>
				<div class="label">Length</div>
			</div>
			<div class="left p80">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Hours</div>
					<div>
					<select name="service_length_hours"  id="service_length_hours" class="">
						<?php
						$h = 0;
						while($h <=12) { ?>
						<option value="<?php print $h;?>" <?php if($h == $service['service_length_hours']) { ?>selected<?php } ?>><?php print $h;?></option>
						<?php
						$h++;
						} ?>
						</select>
					</div>
				</div>
				<div class="left"  style="margin-right: 48px;">
					<div class="label">Minutes</div>
					<div>
						<select name="service_length_minutes"  id="service_length_minutes" class="">
						<?php
						$m = 0;
						while($m < 60) { ?>
						<option value="<?php print $m;?>" <?php if($m == $service['service_length_minutes']) { ?>selected<?php } ?>><?php print $m;?></option>
						<?php
						$m = $m + $booksettings['time_blocks'];
;
						} ?>
						</select>
					</div>
				</div>

				<div class="left">
					<div class="label">&nbsp;</div>

					<div>Or <input type="checkbox" name="service_all_day" id="service_all_day" value="1" <?php if($service['service_all_day'] == "1") { ?>checked<?php } ?>> <label for="service_all_day">All day event</label></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>

		</div>

	<div class="underline">
		<div class="label">Description</div>
		<div>
		<textarea name="service_description" id="service_description" cols="40" rows="12"><?php print $service['service_description'];?></textarea>
		</div>
		<?php 
		addEditor("service_description", "1", "300", "1"); ?>
	</div>




	<div class="pageContent center">

	<input type="hidden" name="service_id" value="<?php print $_REQUEST['service_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="hidden" name="do" value="editPeople">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['service_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="12">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>
