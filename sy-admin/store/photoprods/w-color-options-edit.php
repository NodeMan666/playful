<?php 
$path = "../../../";
require "../../w-header.php"; ?>
<?php


if($_POST['submitit']=="yes") { 
	if($_REQUEST['color_id'] > 0) { 
		updateSQL("ms_color_options", "color_name='".addslashes(stripslashes($_REQUEST['color_name']))."' , color_r='".addslashes(stripslashes($_REQUEST['color_r']))."' , color_g='".addslashes(stripslashes($_REQUEST['color_g']))."' , color_b='".addslashes(stripslashes($_REQUEST['color_b']))."' , color_opc='".$_REQUEST['color_opc']."', color_status='".$_REQUEST['color_status']."' WHERE color_id='".$_REQUEST['color_id']."' ");
		$color_id = $_REQUEST['color_id'];
	} else {
		$color_id = insertSQL("ms_color_options", "color_name='".addslashes(stripslashes($_REQUEST['color_name']))."' , color_r='".addslashes(stripslashes($_REQUEST['color_r']))."' , color_g='".addslashes(stripslashes($_REQUEST['color_g']))."' , color_b='".addslashes(stripslashes($_REQUEST['color_b']))."' , color_opc='".$_REQUEST['color_opc']."', color_status='".$_REQUEST['color_status']."' ");

	}
	$_SESSION['sm'] = "Option saved";
	header("location: ../../index.php?do=photoprods&view=filters");
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<?php if($_REQUEST['showDeleteSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Deleted");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>


<?php if($_REQUEST['do'] == "editOption") { 
	if(($_REQUEST['color_id']> 0)AND(empty($_REQUEST['submitit']))==true) {
		$opt = doSQL("ms_color_options", "*", "WHERE color_id='".$_REQUEST['color_id']."' "); 
		if(empty($opt['color_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($opt AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	
	?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
	<script>
	function updateColor() { 
		$("#colorpreview").css({"background-color":"rgb("+$("#color_r").val()+","+$("#color_g").val()+","+$("#color_b").val()+")"});
	}
	</script>

	<div class="underlinelabel">Filter Option</div>
		<div class="underline">
			<div class="left" style="width: 60%;">
			<div class="label">Option name</div>
			<div><input type="text" name="color_name" id="color_name" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['color_name']));?>" class="optrequired field100"></div>
			</div>
			<div class="right textright" style="width: 40%;">
			<input type="checkbox" name="color_status" value="1" <?php if($_REQUEST['color_status'] == "1") { print "checked"; } ?>> Active
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left" style="width: 33%;">
			<div class="label">Red</div>
			<div>	
			<select name="color_r" id="color_r" onchange="updateColor();">
			<?php 
				$r = 255;
				while($r>=0) {
				?><option value="<?php  print $r; ?>" <?php  if($_REQUEST['color_r'] == $r) { print "selected"; } ?>><?php  print $r;?></option>
				<?php  
					$r = $r-1;
				} ?>
			</select>
			</div>
			</div>

			<div class="left" style="width: 33%;">
			<div class="label">Green</div>
			<div>	
		<select name="color_g" id="color_g" onchange="updateColor();">
		<?php 
			$g = 255;
		while($g>=0) {
			?>g<option value="<?php  print $g; ?>" <?php  if($_REQUEST['color_g'] == $g) { print "selected"; } ?>><?php  print $g;?></option>
			<?php  
				$g=$g-1;
			} ?>
		</select>

			</div>
			</div>


			<div class="left" style="width: 33%;">
			<div class="label">Blue</div>
			<div>	
			<select name="color_b" id="color_b" onchange="updateColor();">
			<?php 
				$b = 255;
				while($b>=0) {
				?><option value="<?php  print $b; ?>" <?php  if($_REQUEST['color_b'] == $b) { print "selected"; } ?>><?php  print $b;?></option>
				<?php  
					$b=$b-1;
				} ?>
			</select>
			</div>
			</div>

			<div class="clear"></div>
		</div>

		<div class="underline">
			<div style="width: 50%;" class="left">
			<div id="colorpreview" style="background-color: rgb(<?php print "".$_REQUEST['color_r'].",".$_REQUEST['color_g'].",".$_REQUEST['color_b'].""; ?>); width: 60px; height: 20px;"></div>
			</div>
		<div style="width: 50%;" class="left">
			<div class="label">Opacity</div>
			<div><select name="color_opc">
				<?php 
		
			$op = 100;
			while($op >=0) { ?>
			<option value="<?php print $op;?>" <?php if($_REQUEST['color_opc'] == $op) { print "selected"; } ?>><?php print $op;?></option>
			<?php 
				$op = $op - 1;
			}
			?>
			</select>
			</div>
			</div>
			<div class="clear"></div>
		</div>


<div>&nbsp;</div>

<div class="pageContent center">

<input type="hidden" name="color_id" value="<?php print $_REQUEST['color_id'];?>">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="do" value="editOption">
<input type="hidden" name="opt_photo_prod" value="<?php print $_REQUEST['opt_photo_prod'];?>">
<input type="hidden" name="opt_date" value="<?php print $_REQUEST['opt_date'];?>">


<input type="submit" name="submit" value="<?php 	if($_REQUEST['color_id'] > 0) { ?>Update Option<?php } else { ?>Create New Option<?php } ?>" class="submit" id="submitButton">
<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
</div>

</form>
<?php } ?>

<?php require "../../w-footer.php"; ?>