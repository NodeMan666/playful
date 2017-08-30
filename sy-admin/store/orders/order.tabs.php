<script>
 $(document).ready(function(){
  $(".tabdropdown").mouseenter(function(){
		$(this).children('.dropdown').show();
  });

  $(".tabdropdown").mouseleave(function(){
		$(this).children('.dropdown').hide();
 });

 });
</script>
<?php 
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

?>
<div class="buttonsgray">
	<ul>
	<li><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>" class="<?php if($_REQUEST['action']== "viewOrder") { print "on"; } ?>">VIEW</a></li>
	<li><a href="index.php?do=orders&action=addshipping&order_id=<?php print $order['order_id'];?>"  class="tip <?php if($_REQUEST['action'] == "addshipping") { print "on"; }  ?>" title="Send shipping notification email">SHIP NOTIFY</a></li>
	<li><a href="index.php?do=orders&action=email&order_id=<?php print $order['order_id'];?>" class="<?php if($_REQUEST['action'] == "email") { print "on"; } ?>">EMAIL INVOICE</a></li>

	<li class="tabdropdown drop">
	<div ><?php print ai_arrow_down;?> STATUS: <?php 	if($order['order_status']=="0") {
		if($order['order_open_status'] > 0) { 
			$status = doSQL("ms_order_status", "*", "WHERE status_id='".$order['order_open_status']."' ");
			print $status['status_name'];
		} else { 
			print "Open";
		}
		} 	if($order['order_status']=="1") { print "Archived"; } 	if($order['order_status']=="2") { print "Trashed"; } ?></div>

	<div id="galMore" class="dropdown" style="display: none; width: 150px; position: absolute;">
		<?php if($order['order_status'] !== "0") { ?>
		<a href="index.php?do=orders&action=openOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to open this order? ');">Open</a>
		<?php } ?>

		<?php if($order['order_status'] == "0") { ?>
		<?php $statuss = whileSQL("ms_order_status", "*", "ORDER BY status_name ASC ");
		while($status = mysqli_fetch_array($statuss)) { 
			if($order['order_open_status'] !== $status['status_id']) { ?>
		<a href="index.php?do=orders&action=updatestatus&order_id=<?php print $order['order_id'];?>&status_id=<?php print $status['status_id'];?>"><?php print $status['status_name'];?></a>
		<?php }
			} ?>
		<?php } ?>
		<?php if($order['order_status'] !== "1") { ?>
		<a href="index.php?do=orders&action=archiveOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to archive this order? ');">Archive</a>
		<?php } ?>
		<?php if($order['order_status'] !== "2") { ?>
		<a href="index.php?do=orders&action=trashOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to trash this order? Trashed orders  will not be calculated in any sales reports and customers will not be able to download.  ');">Trash</a>
		<a href="" onclick="orderstatus('','<?php print $order['order_id'];?>'); return false;"><i>New Status</i></a>
	<?php } ?>


	</div>
	</li>
	<li class="tabdropdown drop" >
	<div ><span class="the-icons icon-print" style="color: #FFFFFF;"></span>PRINT / PACKING SLIP</div>
	<div id="galMore" class="dropdown" style="display: none; width: 300px; position: absolute;">

	<a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=1&thumbnails=1" target="_blank">With Prices & Thumbnails</a>
	<a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=1&thumbnails=0" target="_blank">With Prices &  No Thumbnails</a>
	<a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=0&thumbnails=1" target="_blank">Without Prices & Thumbnails</a>
	<a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=0&thumbnails=0" target="_blank">Without Prices & No Thumbnails</a>
	</div>
	
	</li>
	<?php if(countIt(cart_table, "WHERE cart_order='".$order['order_id']."' AND cart_pic_id>'0' ") > 0) { ?>
	<li><a href="index.php?do=orders&action=managephotos&order_id=<?php print $order['order_id'];?>" class="<?php if($_REQUEST['action'] == "managephotos") { print "on"; }  ?>">MANAGE PHOTOS</a></li>
<?php } ?>

	<li><a href="" onclick="editexpense('','<?php print $order['order_id'];?>'); return false;">ENTER EXPENSE</a></li>
	
	<?php if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ")<= 0 ) { ?>

	<li><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $order['order_id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;">EDIT PAYMENT</a></li>
	<?php } ?>
	<li><a href="" onclick="editorder('<?php print $order['order_id'];?>'); return false;">EDIT <?php if($order['order_invoice'] == "1") { ?>INVOICE<?php } else { ?>ORDER<?php } ?></a></li>
	<?php if($order['order_invoice'] == "1") { ?>
	<li><a href="" onclick="paymentschedule('<?php print $order['order_id'];?>'); return false;">PAYMENT SCHEDULE</a></li>
	<?php } ?>


	<div class="cssClear"></div>
	</ul>
</div>

