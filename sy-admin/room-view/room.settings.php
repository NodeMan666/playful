<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
	if($_REQUEST['math_type'] == "centimetre") { 
		$size_symbol = "cm";
	} else { 
		$size_symbol = '"';
	}
	updateSQL("ms_wall_settings", "math_type='".$_REQUEST['math_type']."', size_symbol='".addslashes(stripslashes($size_symbol))."', admin_link='".$_REQUEST['admin_link']."', 
	offer_bw='".$_REQUEST['offer_bw']."', 
	offer_frames='".$_REQUEST['offer_frames']."',  
	offer_canvas='".$_REQUEST['offer_canvas']."' 
	");   		

	if($_REQUEST['math_type'] == "centimetre") { 
		updateSQL("ms_wall_rooms","room_width='457' WHERE room_id='16'");
	} else { 
		updateSQL("ms_wall_rooms","room_width='180' WHERE room_id='16'");
	}
	$_SESSION['sm'] = "Settings saved";
	session_write_close();
	header("location: index.php?do=photoprods&view=roomview");
	exit();
}
?>

<?php 	$wset = doSQL("ms_wall_settings", "*", "  "); ?>
<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a></div>
<div class="pc">The Wall Designer lets you and your customers  view their photos as framed or canvas prints on the wall of a room with the option to purchase the items added. <a href="https://www.picturespro.com/sytist-manual/wall-designer/" target="_blank">Video overview of the Wall Designer</a> and <a href="https://www.picturespro.com/sytist-manual/wall-designer/admin-overview/">video overview of the admin side</a>.</div>
<div>&nbsp;</div>

<div class="left p50">
	<div style="padding: 24px;">

	<div><img src="graphics/wall-designer.jpg" style="width: 100%; max-width: 600px; height: auto; margin: auto;"></div>
	</div>
</div>

<div class="left p50">
	<div style="padding: 24px;">
		<div class="pc"><h3>A few settings</h3></div>
		<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm('','submit');">
			<div class="underline">
				<div class="label">Inches or Centimetres</div>
				<div>
				<select name="math_type" id="math_type">
				<option value="inches" <?php if($wset['math_type'] == "inches") { ?>selected<?php } ?>>Inches</option>
				<option value="centimetre" <?php if($wset['math_type'] == "centimetre") { ?>selected<?php } ?>>Centimetres</option>
				</select>
				</div>
			</div>

			<div class="underline">
				<div class="label"><input type="checkbox" name="offer_frames" id="offer_frames" value="1" <?php if($wset['offer_frames'] == "1") { ?>checked<?php } ?>> <label for="offer_frames">Enable Framed Prints</label></div>
			</div>

			<div class="underline">
				<div class="label"><input type="checkbox" name="offer_canvas" id="offer_canvas" value="1" <?php if($wset['offer_canvas'] == "1") { ?>checked<?php } ?>> <label for="offer_canvas">Enable Canvas Prints</label></div>
			</div>

			<div class="underline">
				<div class="label"><input type="checkbox" name="offer_bw" id="offer_bw" value="1" <?php if($wset['offer_bw'] == "1") { ?>checked<?php } ?>> <label for="offer_bw">Offer Black & White option for photos in the Wall Designer</label></div>
			</div>
			<div class="underline">
				<div class="label"><input type="checkbox" name="admin_link" id="admin_link" value="1" <?php if($wset['admin_link'] == "1") { ?>checked<?php } ?>> <label for="admin_link">Add a link to the Wall Designer to admin galleries</label></div>
				<div>This will add a tab to galleries in the admin to access the wall designer.</div>
			</div>

			<div class="underlinespacer bold">
			To enable the Wall Designer for customers to use, check the option to enable it when viewing your price lists.
			</div>

			<div class="pc">
				<input type="hidden" name="do" value="photoprods">
				<input type="hidden" name="view" value="roomview">
				<input type="hidden" name="sub" value="settings">
				<input type="hidden" name="submitit" value="yes">
				<input type="submit" name="submit" id="submit" class="submitSmall" value="Update">
			</div>
			<div class="pc"><i>Note that the Wall Designer is not currently available for green screen galleries.</i></div> 
			<div>&nbsp;</div>
		</form>
	</div>
</div>
<div class="clear"></div>
<div>&nbsp;</div>
<div class="pc center"><h3>&larr;Select from the left menu to manage the available canvas prints, frames, room, etc... </h3></div>

