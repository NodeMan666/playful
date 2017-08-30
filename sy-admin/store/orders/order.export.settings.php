<h1>Sales Report Order Export Settings</h1>
<div class="pc">Below you can select what fields from orders are exported on your <a href="index.php?do=reports">sales reports</a>.</div>

<?php if($_REQUEST['subdo'] == "save") { 
foreach($_REQUEST['fn'] AS $id => $val) {
//	print "<li>$id - fs='".$_REQUEST['fs'][$id]."' ".$_REQUEST['fn'][$id]." ";
	updateSQL("ms_order_export", "fl='".$_REQUEST['fl'][$id]."',fo='".$_REQUEST['fo'][$id]."',fs='".$_REQUEST['fs'][$id]."' WHERE fn='".$_REQUEST['fn'][$id]."' ");  
//	print "<li>".$_REQUEST['fn'][$id]."  ".$_REQUEST['fl'][$id];
//	print "<li>$id => $val";
}

	// print "<pre>"; print_r($_REQUEST); 
	$_SESSION['sm'] = "Settings Saved";
	header("location: index.php?do=orders&action=exportsettings");
	session_write_close();
	exit();
}
?>


<form method="post" name="or" id="or" action="index.php">
<input type="hidden" name="do" value="orders">
<input type="hidden" name="action" value="exportsettings">
<input type="hidden" name="subdo" value="save">
<div class="underlinelabel">
	<div class="p20 left">Database Field</div>
	<div class="p10 left">Export</div>
	<div class="p50 left">Fieldname</div>
	<div class="p20 left textright">Order</div>
	<div class="clear"></div>
</div>


<?php 
$x = 0;
$fields = whileSQL("ms_order_export", "*", "ORDER BY fo ASC ");
while($f = mysqli_fetch_array($fields)) { 
	?>

<div class="underline">
	<div class="p20 left"><?php print $f['fn'];?></div>
	<div class="p10 left"><input type="checkbox" name="fs[<?php print $x;?>]" value="1" <?php if($f['fs'] == "1") { print "checked"; } ?>></div>
	<div class="p50 left"><input type="text" name="fl[<?php print $x;?>]" value="<?php print $f['fl']?>" class="field100"></div>
	<div class="p20 left textright"><input type="text" name="fo[<?php print $x;?>]" value="<?php print $f['fo']?>" size="2" class="center"></div>
	<input type="hidden" name="fn[<?php print $x;?>]" value="<?php print $f['fn'];?>">
	<div class="clear"></div>
</div>
<?php
$x++;	
} ?>

<div class="underline"><input type="submit" name="submit" value="Save Changes" class="submit"></div>
</form>

