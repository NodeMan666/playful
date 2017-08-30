<div id="sitecontent">
	<div class="info">
		<form method="get" name="search" action="index.php" style="padding: 0px; margin: 0px;">
		<input type="hidden" name="do" value="orders">
		<input type="text"  name="q" size="20" value="<?php print $_REQUEST['q'];?>">
		<input type="submit" class="submitmenu" name="submit" value="Search">
		</form>
	</div>
</div>

<?php 	
if($_REQUEST['date_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' "); ?>
<div id="sitecontent">
	<div class="info">
	You are viewing orders from: 
	<h2><a href="index.php?do=news&action=addDate&date_id=<?php print $date['date_id'];?>"><?php print $date['date_title'];?></a>
	</div>
</div>
<?php } ?>

<ul class="sidemenus">
<li <?php if((empty($_REQUEST['orderStatus']))&&(empty($_REQUEST['action'])) == true) { print "class=\"on\""; } ?>><a href="?do=orders&date_id=<?php print $_REQUEST['date_id'];?>">Open Orders  (<?php 
if(!empty($_REQUEST['date_id'])) {

	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND order_status='0'

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND order_status='0' ) 
	x 
	GROUP BY order_id
	");

	if (!$morders) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

	// $datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND order_status='0'  GROUP BY order_id ORDER BY order_id DESC ");
	print  mysqli_num_rows($morders);
} else { 
	print countIt("ms_orders", "WHERE order_status='0' "); } ?>)</a></li>

<?php
$statuss = whileSQL("ms_order_status", "*", "ORDER BY status_name ASC "); ?>
<?php if(mysqli_num_rows($statuss) > 0) { ?>
<li <?php if($_REQUEST['orderStatus'] == "openonly") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=orders&orderStatus=openonly&date_id=<?php print $_REQUEST['date_id'];?>">Open Orders Not Assigned (<?php 
if(!empty($_REQUEST['date_id'])) {

	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='0' AND order_open_status<='0'

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='0' AND order_open_status<='0' ) 
	x 
	GROUP BY order_id
	");




	// $datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='0' AND order_open_status<='0' GROUP BY order_id ORDER BY order_id DESC ");
	print  mysqli_num_rows($morders);
} else { 
	print countIt("ms_orders", "WHERE order_status='0' AND order_open_status<='0' ");
}?>)</a></li>

<?php	while($status = mysqli_fetch_array($statuss)) { ?>
<li class="rowhover" <?php if($_REQUEST['order_open_status'] == $status['status_id']) { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="index.php?do=orders&order_open_status=<?php print $status['status_id'];?>&date_id=<?php print $_REQUEST['date_id'];?>"><?php print $status['status_name'];?> (<?php
if(!empty($_REQUEST['date_id'])) {
	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_open_status='".$status['status_id']."' 

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_open_status='".$status['status_id']."'  ) 
	x 
	GROUP BY order_id
	");


//	$datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_open_status='".$status['status_id']."' GROUP BY order_id ORDER BY order_id DESC ");
	print  mysqli_num_rows($morders);
} else { 
	print countIt("ms_orders", "WHERE order_open_status='".$status['status_id']."' ");
}?>)</a>
<div class="submenu" style="padding-left: 18px; height: 8px; line-height: 2px; "><div class="hovermenu" ><span  style="font-size: 13px; cursor: pointer;"  onclick="orderstatus('<?php print $status['status_id'];?>','0'); return false;">edit</span> 
<span style="font-size: 13px; cursor: pointer;"   class="confirmdelete" confirm-title="Really?" confirm-message="Are you sure you want to delete this status?" href="index.php?do=orders&action=deleteorderstatus&status_id=<?php print $status['status_id'];?>" >delete</span>
</li>
	<?php } ?>

<?php } ?>
<li <?php if($_REQUEST['orderStatus'] == "archived") { print "class=\"on\""; } ?>><a href="?do=orders&orderStatus=archived&date_id=<?php print $_REQUEST['date_id'];?>">Archived (<?php 
if(!empty($_REQUEST['date_id'])) {

	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='1'  

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='1' ) 
	x 
	GROUP BY order_id
	");

	if (!$morders) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

	print  mysqli_num_rows($morders);

	// $datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='1' GROUP BY order_id ORDER BY order_id DESC ");
	// print  mysqli_num_rows($datas);
} else { 
	print countIt("ms_orders", "WHERE order_status='1' ");
}?>)</a></li>
<li <?php if($_REQUEST['orderStatus'] == "trash") { print "class=\"on\""; } ?>><a href="?do=orders&orderStatus=trash&date_id=<?php print $_REQUEST['date_id'];?>">Trashed (<?php 
if(!empty($_REQUEST['date_id'])) {

	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='2'  

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='2' ) 
	x 
	GROUP BY order_id
	");

	if (!$morders) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

//	$datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status='2'  GROUP BY order_id ORDER BY order_id DESC ");
	print  mysqli_num_rows($morders);
} else { 
	print countIt("ms_orders", "WHERE order_status='2' ");
}
?>)</a></li>
<?php 
if(!empty($_REQUEST['date_id'])) {
	$datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' AND  order_status<='1' AND order_payment_date='0000-00-00' AND order_total>'0'  GROUP BY order_id ORDER BY order_id DESC ");
	$unpaid = mysqli_num_rows($datas);
} else { 
	$unpaid = countIt("ms_orders", "WHERE  order_status<='1' AND order_payment_date='0000-00-00' AND order_total>'0'  ");

$unpaid = countIt("ms_orders LEFT JOIN ms_payment_schedule ON ms_orders.order_id=ms_payment_schedule.order_id", "WHERE  order_status<='1' AND order_payment_date='0000-00-00' AND order_total>'0' AND ms_payment_schedule.id IS NULL  ");

}
if($unpaid > 0) { ?>
<li <?php if($_REQUEST['orderStatus'] == "unpaid") { print "class=\"on\""; } ?>><a href="?do=orders&orderStatus=unpaid&date_id=<?php print $_REQUEST['date_id'];?>">Unpaid (<?php print $unpaid;?>)</a></li>
<?php } ?>

<?php if(countIt("ms_payment_schedule", "" ) > 0) { ?>
<li <?php if($_REQUEST['action'] == "payments") { print "class=\"on\""; } ?>><a href="index.php?do=orders&action=payments">Scheduled Payments</a></li>
<?php } ?>
<li><a href="index.php?do=orders&view=invoice">Create Invoice</a></li>
<li <?php if($_REQUEST['action'] == "packingslip") { print "class=\"on\""; } ?>><a href="?do=orders&action=packingslip">Packing Slip Layout</a> </li>
<li <?php if($_REQUEST['action'] == "shippingoptions") { print "class=\"on\""; } ?>><a href="?do=orders&action=shippingoptions">Shipped By Options</a> </li>
<li><a href="" onClick="showHide('newnum'); return false;">Change Starting Order Number</a> 
<div id="newnum" style="display: none; padding: 12px 16px;">
<?php 
$result = mysqli_query($dbcon,"
    SHOW TABLE STATUS LIKE 'ms_orders'
");
$data = mysqli_fetch_assoc($result);
$next_increment = $data['Auto_increment'];
?>
<div>
<form method="post" name="ordernum" action="index.php" onSubmit="setOrderNumber(); return false;">
	<input type="text" name="new_number" id="new_number" size="6" value="<?php print $next_increment;?>">
	<input type="submit" name="submit" value="Change" class="submit">
</form>
</div>
<div>This action will change the number of the next order and then will increment by 1. It must be higher than <?php print $next_increment; ?>.
</div>
</div>

</li>

</ul>
