<?php 
$path = "../../";
require "../w-header.php"; 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");


if($_REQUEST['action'] == "save") { 
	updateSQL("ms_calendar", "date_qty_descr='".addslashes(stripslashes($_REQUEST['date_qty_descr']))."' WHERE date_id='".$_REQUEST['date_id']."' ");
	deleteSQL2("ms_products_discounts", "WHERE dis_prod='".$_REQUEST['date_id']."' ");
	foreach($_REQUEST['dis_price'] AS $id => $opt) {
		$thisCount++;
		print "<li>$opt";
		if(!empty($opt)) {
			$in = insertSQL("ms_products_discounts", "dis_prod='".$_REQUEST['date_id']."', dis_price='".addslashes(stripslashes($opt))."', dis_qty_from='".$_REQUEST['dis_qty_from'][$id]."', dis_qty_to='".$_REQUEST['dis_qty_to'][$id]."' ");
		}
	}
	$_SESSION['sm'] = "Quantity discounts updated for ".$date['date_title']."";
	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=news&action=addDate&date_id=".$date['date_id']."");
	session_write_close();
	exit();


	// print "<pre>"; print_r($_REQUEST);

exit();
}

?>
<div class="pc"><h1>Quantity discounts for <?php print $date['date_title'];?></div>
<div style="width: 36%; float: left;">
<div class="pc">
Here you can create discounts based on quantity purchased. 
<br><br>
<h3>Format</h3>
Enter in the quantity from and to and the price for that range. <b>Do not start with 1!</b>. Example: 

</div>
<div class="pc">
	<div style="float: left; width: 25%;">2 - 4</div> 
	<div style="float: left; width: 25%;"><?php print showPrice(10.00);?></div> 
	<div class="clear"></div>

	<div style="float: left; width: 25%;">5 - 8</div> 
	<div style="float: left; width: 25%;"><?php print showPrice(9.00);?></div> 
	<div class="clear"></div>

	<div style="float: left; width: 25%;">9 - 0</div> 
	<div style="float: left; width: 25%;"><?php print showPrice(8.00);?></div> 
	<div class="clear"></div>
</div>
<div class="pc">
<b>IMPORTANT<br></b>
1) Do no overlap numbers. <br>
2) The last quantity discount "qty to" must end with 0 like in the example above
</div>
<div>&nbsp;</div>

</div>

<div style="width: 60%; float: right">
<div class="pc"><h2>Price for 1: <?php print showPrice($date['prod_price']);?></div>

<form method="post" name="qtydis" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"  onSubmit="return checkForm('.optrequired');">
<?php
$diss = whileSQL("ms_products_discounts", "*", "WHERE dis_prod='".$date['date_id']."' ORDER BY dis_price DESC ");

$lines = mysqli_num_rows($diss);
if($lines <=0) { 
	$lines = 5;
} else { 
	$lines = $lines + 2;
}
if($lines < 5) {
	$lines = 5;
}

$ct = 1;
while($dis = mysqli_fetch_array($diss)) { 
	$d['dis_qty_from'][$ct] = $dis['dis_qty_from'];
	$d['dis_qty_to'][$ct] = $dis['dis_qty_to'];
	$d['dis_price'][$ct] = $dis['dis_price'];
	$ct++;
}
$ct = 1;

while($ct<=$lines) { ?>
<div class="underline">
	<div style="float: left; width: 33%;">Qty From: <input type="text" name="dis_qty_from[<?php print $ct;?>]" size="2" class="center" value="<?php print $d['dis_qty_from'][$ct];?>"></div> 
	<div style="float: left; width: 33%;">Qty To:  <input type="text" name="dis_qty_to[<?php print $ct;?>]" size="2" class="center" value="<?php print $d['dis_qty_to'][$ct];?>"></div> 
	<div style="float: left; width: 33%;">Price Each: <input type="text" name="dis_price[<?php print $ct;?>]" size="8" class="center" value="<?php print $d['dis_price'][$ct];?>"></div> 
	<div class="clear"></div>
</div>
<?php 
$ct++;	
} ?>

<div class="underline">
	<div>Description</div>
	<div><textarea name="date_qty_descr" rows="2" cols="20" class="field100"><?php print $date['date_qty_descr'];?></textarea></div>
</div>

<div class="pc center">
<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
<input type="hidden" name="action" value="save">
<input type="submit" name="submit" value="Save" class="submit" id="submitButton">

</form>

</div>
<div class="clear"></div>
<div>&nbsp;</div><div>&nbsp;</div>
<?php require "../w-footer.php"; ?>