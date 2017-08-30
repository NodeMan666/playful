<?php 
$path = "../../../";
require "../../w-header.php";
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".$_REQUEST['order_id']."' ");
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}


if($_POST['submitit']=="yes") { 

	if(is_array($_REQUEST['id'])) { 
		foreach($_REQUEST['id'] AS $x => $id) { 
			if($id > 0) { 

				if($_REQUEST['amount'][$x] <=0 ) { 
					deleteSQL("ms_payment_schedule", "WHERE id='".$id."' ","1");
				}
			}
		}
	}
	print "<pre>";
	print_r($_POST);
	foreach($_REQUEST['amount'] AS $x => $amount) { 
		if($amount > 0) { 
			print "<li>ID: ".$_REQUEST['id'][$x];
			if($_REQUEST['id'][$x] > 0) { 
				print "<li>UPDATE: ".$x." => ".$amount;
				updateSQL("ms_payment_schedule","order_id='".$_REQUEST['order_id']."', amount='".$_REQUEST['amount'][$x]."', due_date='".$_REQUEST['due_date'][$x]."' WHERE id='".$_REQUEST['id'][$x]."' "); 
			} else { 
				print "<li>".$x." => ".$amount;
				insertSQL("ms_payment_schedule","order_id='".$_REQUEST['order_id']."', amount='".$_REQUEST['amount'][$x]."', due_date='".$_REQUEST['due_date'][$x]."' "); 
			}
		}
	}
	$_SESSION['sm'] = "Payment Schedule Updated";
	header("location: ".$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']."");
	session_write_close();
	exit();
}
?>

<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>

<div class="title"><h1>Payment Schedule For  #<?php print $order['order_id'];?></h1></div>
<div class="pc">Here you can create a payment schedule so your customers can make payments toward this invoice. It is very important that the amounts total the invoice total.</div>

<div class="pc"><h3>Invoice total: <?php print showPrice($order['order_total']); ?></h3></div>
<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm();">
<div class="underlinecolumn">
	<div class="left p40">Amount</div>
	<div class="left p20">Due Date</div>
	<div class="clear"></div>
</div>
<?php
$x = 1;
$scs = whileSQL("ms_payment_schedule", "*", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");
while($sc = mysqli_fetch_array($scs)) { ?>

<div class="underline">
<input type="hidden" name="id[<?php print $x;?>]" value="<?php print $sc['id'];?>">
	<div class="left p40"><?php print $site_setup['currency_sign'];?> <input type="text" name="amount[<?php print $x;?>]"  value="<?php print $sc['amount'] * 1;?>" size="8" class="center"></div>
	<div class="left p20"><input type="text" name="due_date[<?php print $x;?>]"  value="<?php print $sc['due_date'];?>" size="10" class="center datepicker"></div>
	<div class="clear"></div>
</div>


<?php
	$x++;
$total_lines++;
} ?>
<?php 
$lines = 12 - $total_lines ;
while($x <= $lines) { ?>
<div class="underline">
	<div class="left p40"><?php print $site_setup['currency_sign'];?> <input type="text" name="amount[<?php print $x;?>]"  value="" size="8" class="center"></div>
	<div class="left p20"><input type="text" name="due_date[<?php print $x;?>]"  value="" size="10" class="center datepicker"></div>
	<div class="clear"></div>
</div>





<?php 
	$x++;
}
?>


<div class="pageContent center">
<input type="hidden" name="order_id" value="<?php print $_REQUEST['order_id'];?>">
<input type="hidden" name="submitit" value="yes">


<input type="submit" name="submit" value="Save" class="submit" id="submitButton">
<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
</div>

</form>


<?php require "../../w-footer.php"; ?>