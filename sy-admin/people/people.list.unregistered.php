<div id="pageTitle"><a href="index.php?do=people">People</a></div>

<?php 
$c[1] = "25%";
$c[2] = "20%";
$c[3] = "25%";
$c[4] = "20%";
$c[4] = "10%";


if(empty($_REQUEST['acdc'])) { 
	$acdc = "DESC";
	$oposit = "ASC";
} else { 
	$acdc = $_REQUEST['acdc'];
	if($acdc == "ASC") { 
		$oposit = "DESC";
	}
	if($acdc == "DESC") { 
		$oposit = "ASC";
	}

}
if(empty($_REQUEST['orderby'])) { 
	$orderby = "order_id";
} else { 
	$orderby = $_REQUEST['orderby'];
}
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}

$per_page = 20;
$NPvars = array("do=people", "type=unregistered", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."" );
$sq_page = $pg * $per_page - $per_page;	

$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='0' $and_where  GROUP BY order_email ORDER BY $orderby $acdc "); 
$total = mysqli_num_rows($orders);

?>

<div class="underlinecolumn">
	<div class="left" style="width: <?php print $c[1];?>"><a href="index.php?do=people&type=unregistered&q=<?php print $_REQUEST['q'];?>&orderby=order_last_name&acdc=<?php if($orderby == "order_last_name") { print $oposit; } else { print "ASC"; } ?>">Name</a></div>
	<div class="left" style="width: <?php print $c[2];?>">Email</div>
	<div class="left" style="width: <?php print $c[3];?>">Location</div>
	<div class="left" style="width: <?php print $c[4];?>">Last Order</div>
	<div class="right textright" style="width: <?php print $c[5];?>">Sales</div>
	<div class="clear"></div>
</div>
<?php 
$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='0' $and_where  GROUP BY order_email ORDER BY $orderby $acdc LIMIT $sq_page,$per_page "); 
if(mysqli_num_rows($orders) <=0) { ?><div class="pc center"><h3>You have no unregistered people</h3></div><?php } ?>
<?php 
while($order = mysqli_fetch_array($orders)) { 
	$orderstotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE order_email='".$order['order_email']."' ");
	if($setup['demo_mode'] == true) { 
		$order['order_first_name'] = get_starred($order['order_first_name']);
		$order['order_last_name'] = get_starred($order['order_last_name']);
	}

	?>
<div class="underline">
	<div class="left" style="width: <?php print $c[1];?>"><h2><a href="index.php?do=orders&order_email=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?>"><?php print $order['order_last_name'].", ".$order['order_first_name'];?></a></h2></div>
	<div class="left" style="width: <?php print $c[2];?>"><a href="" onclick="openFrame('w-send-email.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?>&email_to_first_name=<?php print addslashes($order['order_first_name']);?>&email_to_last_name=<?php print addslashes($order['order_last_name']);?>'); return false;" title="Send email" class="tip"><?php print ai_email;?> <?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?></a></div>
	<div class="left" style="width: <?php print $c[3];?>"><?php print $order['order_city'];?><?php if(!empty($order['order_city'])) { print ", "; } ?> <?php print $order['order_state'];?> <?php print $order['order_zip'];?>  <?php print $order['order_country'];?>&nbsp;</div>
	<div class="left" style="width: <?php print $c[4];?>"><?php print $order['order_date'];?></div>
	<div class="right textright" style="width: <?php print $c[5];?>"><a href="index.php?do=orders&order_email=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?>">(<?php print countIt("ms_orders", "WHERE order_email='".$order['order_email']."' ");?>) <?php print showPrice($orderstotal['tot']);?></a></div>

	<div class="clear"></div>
</div>

<?php } ?>
<div>&nbsp;<div>
<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<div>&nbsp;<div>
<div>&nbsp;<div>
