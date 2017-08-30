<?php 
$path = "../../";
require "../w-header.php"; 
$cp_settings = doSQL("ms_canvas_settings", "*", "");


if($_REQUEST['submitit'] == "yes") { 
	updateSQL("ms_canvas_settings", "cp_opt1_use='1', 
	cp_opt2_use='".$_REQUEST['cp_opt2_use']."',
	cp_opt3_use='".$_REQUEST['cp_opt3_use']."',
	cp_opt4_use='".$_REQUEST['cp_opt4_use']."',
	cp_opt5_use='".$_REQUEST['cp_opt5_use']."',
	cp_opt6_use='".$_REQUEST['cp_opt6_use']."',
	cp_opt7_use='".$_REQUEST['cp_opt7_use']."',
	cp_opt8_use='".$_REQUEST['cp_opt8_use']."',
	cp_opt1='".addslashes(stripslashes(trim($_REQUEST['cp_opt1'])))."', 
	cp_opt2='".addslashes(stripslashes(trim($_REQUEST['cp_opt2'])))."',
	cp_opt3='".addslashes(stripslashes(trim($_REQUEST['cp_opt3'])))."',
	cp_opt4='".addslashes(stripslashes(trim($_REQUEST['cp_opt4'])))."',
	cp_opt5='".addslashes(stripslashes(trim($_REQUEST['cp_opt5'])))."',
	cp_opt6='".addslashes(stripslashes(trim($_REQUEST['cp_opt6'])))."',
	cp_opt7='".addslashes(stripslashes(trim($_REQUEST['cp_opt7'])))."',
	cp_opt8='".addslashes(stripslashes(trim($_REQUEST['cp_opt8'])))."',
	canvas_name='".addslashes(stripslashes(trim($_REQUEST['canvas_name'])))."'


	 ");
	$_SESSION['sm'] = "Canvas Settings Saved";
	header("location:../index.php?do=photoprods&view=roomview&sub=canvases");
	session_write_close();
	exit();
}
?>

<script>
$(document).ready(function(){
	setTimeout(function(){ 
		$("#cp_width").focus().select();
	},200);


	$(".optcheckbox").change(function() {
		// alert($(this).attr("data-id"));
		if($(this).attr("checked")) { 
			$("#"+$(this).attr("data-id")).prop('disabled', false).removeClass("disabledinput").addClass("optrequired");
		} else { 
			$("#"+$(this).attr("data-id")).prop('disabled', true).addClass("disabledinput").removeClass("optrequired");
		}
	});
});
</script>

			
<div class="pc"><h3>Canvas Option Settings</h3></div>

<div class="pc">The first option is the default option and price.</div>

<div id="newframe" class="">
	<form method="post" name="famesizes" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
		<div>&nbsp;</div>
		<div class="left p25">
			<div class="label">Option 1 (default)</div>
			<div><input type="text" name="cp_opt1" id="cp_opt1" size="12" class="center  formfield <?php if($cp_settings['cp_opt1_use'] !== "1") { ?>disabledinput<?php } else { ?>optrequired<?php } ?>" value="<?php print htmlspecialchars($cp_settings['cp_opt1']);?>" <?php if($cp_settings['cp_opt1_use'] !== "1") { ?>disabled<?php } ?>></div>
		</div>


		<div class="left p25">
			<div class="label"><input type="checkbox" class="optcheckbox" data-id="cp_opt2"   name="cp_opt2_use" id="cp_opt2_use" value="1" <?php if($cp_settings['cp_opt2_use'] == "1") { ?>checked<?php } ?>> <label for="cp_opt2_use">Option 2</label></div>
			<div><input type="text" name="cp_opt2" id="cp_opt2" size="12" class="center  formfield <?php if($cp_settings['cp_opt2_use'] !== "1") { ?>disabledinput<?php } else { ?>optrequired<?php } ?>" value="<?php print htmlspecialchars($cp_settings['cp_opt2']);?>" <?php if($cp_settings['cp_opt2_use'] !== "1") { ?>disabled<?php } ?>></div>
		</div>


		<div class="left p25">
			<div class="label"><input type="checkbox" class="optcheckbox" data-id="cp_opt3"   name="cp_opt3_use" id="cp_opt3_use" value="1" <?php if($cp_settings['cp_opt3_use'] == "1") { ?>checked<?php } ?>> <label for="cp_opt3_use">Option 3</label></div>
			<div><input type="text" name="cp_opt3" id="cp_opt3" size="12" class="center  formfield <?php if($cp_settings['cp_opt3_use'] !== "1") { ?>disabledinput<?php } else { ?>optrequired<?php } ?>" value="<?php print htmlspecialchars($cp_settings['cp_opt3']);?>" <?php if($cp_settings['cp_opt3_use'] !== "1") { ?>disabled<?php } ?>></div>
		</div>


		<div class="left p25">
			<div class="label"><input type="checkbox" class="optcheckbox"  data-id="cp_opt4"  name="cp_opt4_use" id="cp_opt4_use" value="1" <?php if($cp_settings['cp_opt4_use'] == "1") { ?>checked<?php } ?>> <label for="cp_opt4_use">Option 4</label></div>
			<div><input type="text" name="cp_opt4" id="cp_opt4" size="12" class="center formfield <?php if($cp_settings['cp_opt4_use'] !== "1") { ?>disabledinput<?php } else { ?>optrequired<?php } ?>" value="<?php print htmlspecialchars($cp_settings['cp_opt4']);?>" <?php if($cp_settings['cp_opt4_use'] !== "1") { ?>disabled<?php } ?>></div>
		</div>


		<div class="clear"></div>

	</div>





		<div class="clear"></div>
		<div>&nbsp;</div>

		<div class="underline">
			<div class="label">Canvas  Called</div>
			<div><input type="text" name="canvas_name" id="canvas_name" size="20" class="formfield" value="<?php print htmlspecialchars($cp_settings['canvas_name']);?>"></div>
		</div>

		<div class="pc center buttons">
		<input type="hidden" name="style_id" id="style_id" class="formfield" value="<?php print $_REQUEST['style_id'];?>">
		<input type="hidden" name="submitit" id="submitit" class="formfield" value="yes">
		<input type="hidden" name="cp_id" id="cp_id" class="formfield" value="<?php print $cp['cp_id'];?>">
		
		<input type="submit" name="submit" value="Save" class="submit">
		</div>
		<?php if($_REQUEST['cp_id'] > 0) { ?>
		<div class="pc center"><a href="" onclick="framesizes('<?php print $_REQUEST['style_id'];?>',''); return false;">cancel</a></div>
		<?php } ?>
		<div>&nbsp;</div>

	</form>

</div>



<?php require "../w-footer.php"; ?>
