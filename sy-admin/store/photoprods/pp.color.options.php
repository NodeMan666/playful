<script>
function editoption(color_id) { 
	pagewindowedit("store/photoprods/w-color-options-edit.php?do=editOption&noclose=1&nofonts=1&nojs=1&color_id="+color_id);
}

</script>

<?php 
if($_REQUEST['subdo'] == "editColorOption") {
	require $setup['admin_folder']."/settings/settings.color.options.edit.php";
} elseif($_REQUEST['subdo'] == "deleteColorOption") {
	deleteColorOption();
} elseif($_REQUEST['subdo'] == "orderColors") {
	orderColors();
} else {
	listColorOptions();
}
function orderColors() {
	foreach ($_REQUEST['color_order'] AS $id => $order) {
		$sql = updateSQL("ms_color_options", "color_order='$order' WHERE color_id='$id' ");
	}
	$_SESSION['sm'] =  "Display order updated";
	session_write_close();
	header ("Location: index.php?do=photoprods&view=filters");
	exit();
}

function deleteColorOption() {
	$gi = doSQL("ms_color_options", "*", "WHERE color_id='".$_REQUEST['color_id']."' ");
	$sql = deleteSQL("ms_color_options", "WHERE color_id='".$gi['color_id']."' ", "1" );
	$_SESSION['sm'] = "color option ".$gi['color_name']." was deleted";
	session_write_close();
	header("location: index.php?do=photoprods&view=filters");
	exit();
}

function listColorOptions() {
global $tr; ?>
<div id="pageTitle">B&W / Filter Options</div>
<div id=info>
Here you can create color options like black & white preview, sepia tone, etc. How it works is first it will convert the image to black & white. Then it creates a solid graphic of the color settings selected, then lays that color graphic on top of the black & white image. The opacity percentage is how much to add it on to the image. 
<br><br>
You will assign these color options to your price lists.
<br><br>
</div>

<div class="pc"><a href="" onclick="editoption(''); return false;">Add new color filter</a></div>
<div class="underlinecolumn">
	<div style="width: 10%;" class="left">&nbsp;</div>
	<div style="width: 10%;" class="left">Order</div>
	<div style="width: 10%;" class="left">Color Option</div>
	<div style="width: 10%;" class="left">Preview</div>
	<div style="width: 10%;" class="left">B&W</div>
	<div style="width: 10%;" class="left">Red</div>
	<div style="width: 10%;" class="left">Green</div>
	<div style="width: 10%;" class="left">Blue</div>
	<div style="width: 10%;" class="left">Opacity</div>
	<div style="width: 10%;" class="left">Status</div>
	<div class="clear"></div>
</div>

<form method="post" name="filterorder" action="index.php">
<?php 
	$colors = whileSQL("ms_color_options", "*", "ORDER BY color_order ASC " );
	if(mysqli_num_rows($colors) < 1) {
		print "<tr><td colspan=8 align=center class=tdlines>No color options created</td></tr>";
	}
	while($color = mysqli_fetch_array($colors)) {
	?>
	<div class="underline">
	<div style="width: 10%;" class="left"><a href="" onclick="editoption('<?php print $color['color_id'];?>'); return false;"><?php print ai_edit;?></a> <?php if($color['color_id']!=="1") { ?><a href="index.php?do=photoprods&view=filters&subdo=deleteColorOption&color_id=<?php print $color['color_id'];?>"  onClick="return confirm('Are you sure you want to delete this? Deleting this will permanently remove it and can not be reversed.');"><?php print ai_delete; ?></a><?php } ?></div>
	<div style="width: 10%;" class="left"><input type="text" name="color_order[<?php print $color['color_id'];?>]" value="<?php print $color['color_order'];?>" size="2" class="center"></div>
	<div style="width: 10%;" class="left"><?php print $color['color_name'];?></div>
	<div style="width: 10%;" class="left"><div style="background-color: rgb(<?php print "".$color['color_r'].",".$color['color_g'].",".$color['color_b'].""; ?>); width: 60px; height: 20px;"></div></div>
	<div style="width: 10%;" class="left"><?php if($color['color_bw'] == "1") { print "B&W"; } else { print "--"; } ?></div>
	<div style="width: 10%;" class="left"><?php print $color['color_r'];?></div>
	<div style="width: 10%;" class="left"><?php print $color['color_g'];?></div>
	<div style="width: 10%;" class="left"><?php print $color['color_b'];?></div>
	<div style="width: 10%;" class="left"><?php print $color['color_opc'];?></div>
	<div style="width: 10%;" class="left"><?php if($color['color_status'] == "1") { print "Active"; } else { print "Inactive"; } ?></div>
	<div class="clear"></div>
</div>
	<?php } ?>
	<div class="pc">
	<input type="hidden" name="do" value="photoprods">
	<input type="hidden" name="view" value="filters">
	<input type="hidden" name="subdo" value="orderColors">
	<input type="submit" value="Update display order"  name="submit" class="submit">
	</div>
	</form>
<?php }
?>
