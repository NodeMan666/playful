<div id="pageTitle"><a href="index.php?do=orders&date_id=<?php print $_REQUEST['date_id'];?>">Orders</a> <?php  print ai_sep; ?> Move to Archive</div>
<div class="clear"></div>

<?php
$orders = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id","*","WHERE order_status='1' AND order_archive_table='0' AND cart_booking<='0' GROUP BY order_id ASC ");
$tomove = mysqli_num_rows($orders);
$orders = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id","*","WHERE order_status='1' AND order_archive_table='0' AND cart_booking<='0' GROUP BY order_id ASC LIMIT 400");

 ?>
<div class="pc">There are approx. <?php print $tomove;?> orders than can be moved to the archive table.</div>
<div class="pc"><a href="index.php?do=orders&action=archivetable&doit=yes">Move orders to archive table</a></div>
<?php  
while($order = mysqli_fetch_array($orders)) { 
	?>
<div class="underline"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>"><?php print $order['order_id'];?></a> 

<?php 
	if($_REQUEST['doit'] == "yes") { 
		 print archiveordertable($order);
	}
?>
</div>
<?php } ?>