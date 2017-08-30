<?php 
$path = "../../";
require "../w-header.php"; 

$style = doSQL("ms_frame_styles", "*",  "WHERE style_id='".$_REQUEST['style_id']."' ");

if($_REQUEST['submitit'] == "yes") { 
	$order = doSQL("ms_frame_styles", "*", " ORDER BY style_order DESC ");
	$thisorder = $order['style_order'] + 1;

	if($_REQUEST['style_id'] > 0) { 
		$style_id = updateSQL("ms_frame_styles", "style_name='".addslashes(stripslashes(trim($_REQUEST['style_name'])))."', style_frame_width='".addslashes(stripslashes(trim($_REQUEST['style_frame_width'])))."', style_descr='".addslashes(stripslashes(trim($_REQUEST['style_descr'])))."', style_taxable='".$_REQUEST['style_taxable']."', style_no_discount='".$_REQUEST['style_no_discount']."' WHERE style_id='".$_REQUEST['style_id']."' ");
		$style_id = $_REQUEST['style_id'];
	} else { 
		$style_id = insertSQL("ms_frame_styles", "style_name='".addslashes(stripslashes(trim($_REQUEST['style_name'])))."', style_frame_width='".addslashes(stripslashes(trim($_REQUEST['style_frame_width'])))."', style_descr='".addslashes(stripslashes(trim($_REQUEST['style_descr'])))."',  style_order='".$thisorder."', style_taxable='".$_REQUEST['style_taxable']."', style_no_discount='".$_REQUEST['style_no_discount']."' ");

		insertSQL("ms_frame_sizes", "frame_width='".$_REQUEST['frame_width']."', frame_height='".$_REQUEST['frame_height']."', frame_style='".$style_id."', frame_price='".$_REQUEST['frame_price']."', frame_mattable='".$_REQUEST['frame_mattable']."', frame_mat_width='".$_REQUEST['frame_mat_width']."', frame_mat_price='".$_REQUEST['frame_mat_price']."', frame_mat_print_width='".$_REQUEST['frame_mat_print_width']."', frame_mat_print_height='".$_REQUEST['frame_mat_print_height']."', frame_default='1', frame_shipable='".$_REQUEST['frame_shipable']."', frame_add_shipping='".$_REQUEST['frame_add_shipping']."', frame_order='1' ");

	}
	$_SESSION['sm'] = "Frame Style  Saved";
	header("location: ../index.php?do=photoprods&view=roomview&sub=frame&style_id=".$style_id."");
	session_write_close();
	exit();
}
?>


<div class="pc"><h3><?php if($_REQUEST['style_id'] > 0) { ?>Edit Frame Style<?php } else { ?>Add New Frame Style<?php } ?></h3></div>

<?php if($_REQUEST['style_id'] > 0) { 
	$style = doSQL("ms_frame_styles", "*", "WHERE style_id='".$_REQUEST['style_id']."' "); 
}
?>

<div class="clear"></div>
<div>&nbsp;</div>

<div id="newframe" class="">
	<form method="post" name="famesizes" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
		<div class="label">Name</div>
		<div><input type="text" name="style_name" id="style_name" size="20" class="inputtitle optrequired formfield field100" value="<?php print $style['style_name'];?>"></div>
	</div>


	<div class="underline">
		<div class="left p20">
			<div class="label">Framing Width</div>
			<div><input type="text" name="style_frame_width" id="style_frame_width" size="2" class="center optrequired formfield" value="<?php print $style['style_frame_width'] * 1;?>"></div>
		</div>
		<div class="left p80">
			<div class="left" style="margin-right: 16px;"><img src="graphics/frame-width.jpg"></div>
			<div class="left">This is the width of the framing, <br>not the complete width of the entire frame. <br>Refer to this graphic.</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	
	</div>

	<div class="underline">
		<div class="label">Description</div>
		<div><textarea  name="style_descr" id="style_descr"   class="formfield field100"><?php print $style['style_descr'];?></textarea></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="style_taxable" id="style_taxable" value="1" <?php if(($style['style_taxable'] == "1" || $_REQUEST['style_id'] <= 0) == true) { ?>checked<?php } ?>> <label for="style_taxable">Taxable</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="style_no_discount" id="style_no_discount" value="1" <?php if(($style['style_no_discount'] == "1" || $_REQUEST['style_id'] <= 0) == true) { ?>checked<?php } ?>> <label for="style_no_discount">Do not allow discounting</label></div>
	</div>

	<?php addEditor("style_descr","1", "500", "0"); ?>

	<?php if($_REQUEST['style_id'] <= 0) { ?>
	<div>&nbsp;</div>
	<div class="pc"><h2>First Frame</h2></div>
	<div class="pc">Create the first frame size in this style. After this style is created, you can create additional frame sizes.</div>
	<div class="underline">
		<div class="left p25">
			<div class="label">Frame Width</div>
			<div><input type="text" name="frame_width" id="frame_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_width'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Frame Height</div>
			<div><input type="text" name="frame_height" id="frame_height" size="2" class="center optrequired formfield" value="<?php print $frame['frame_height'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Price</div>
			<div><input type="text" name="frame_price" id="frame_price" size="4" class="center optrequired formfield" value="<?php print $frame['frame_price'] * 1;?>"></div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>
		<div class="left p25">
			<div class="label">Mat Width</div>
			<div><input type="text" name="frame_mat_width" id="frame_mat_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_width'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Matted Print Width</div>
			<div><input type="text" name="frame_mat_print_width" id="frame_mat_print_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_print_width'] * 1;?>"></div>
		</div>
		<div class="left p25">
			<div class="label">Matted Print Height</div>
			<div><input type="text" name="frame_mat_print_height" id="frame_mat_print_height" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_print_height'] * 1;?>"></div>
		</div>
		<div class="left p25">
			<div class="label">Matted Price</div>
			<div><input type="text" name="frame_mat_price" id="frame_mat_price" size="4" class="center optrequired formfield" value="<?php print ($frame['frame_mat_price'] * 1);?>"></div>
		</div>

		<div class="clear"></div>
	</div>
		<div>&nbsp;</div>

		<div class="left p25">
			<div><input type="checkbox" class="formfield" name="frame_shipable" id="frame_shipable"  value="1" <?php if($frame['frame_shipable'] == "1") { ?>checked<?php } ?>> <label for="frame_shipable">Eligible for shipping</label></div>
		</div>
		<div class="left p75">
			<div>If eligible for shipping, enter any additional shipping amount: <input type="text" name="frame_add_shipping" id="frame_add_shipping" size="2" class="center formfield" value="<?php print $frame['frame_add_shipping'];?>"></div>
		</div>
		<div class="clear"></div>

		<div>&nbsp;</div>
	<?php } ?>


		<div>&nbsp;</div>

		<div class="pc center buttons">
		<input type="hidden" name="style_id" id="style_id" class="formfield" value="<?php print $_REQUEST['style_id'];?>">
		<input type="hidden" name="submitit" id="submitit" class="formfield" value="yes">
		<input type="hidden" name="style_id" id="style_id" class="formfield" value="<?php print $style['style_id'];?>">
		<input type="submit" name="submit" class="submit" value="Save">
		</div>
		<?php if($_REQUEST['style_id'] > 0) { ?>
		<div class="pc center"><a href="" onclick="closewindowedit(); return false;">cancel</a></div>
		<?php } ?>
		<div>&nbsp;</div>

	</form>

</div>



<?php require "../w-footer.php"; ?>
