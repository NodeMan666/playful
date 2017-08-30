<script>
 $(document).ready(function(){
  $(".tabdropdown").mouseenter(function(){
		$(this).children('.dropdown').show();
		$(this).attr('class', 'formTabOn');
  });

  $(".tabdropdown").mouseleave(function(){
		$(this).children('.dropdown').hide();
 		$(this).attr('class', 'formTab');
 });

 });
</script>
	
<div id="formTabContainer">
	<div class="<?php if($_REQUEST['action']== "viewOrder") { print "formTabOn"; } else { print "formTab"; } ?>"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>">VIEW</a></div>
	<?php if(!empty($order['order_shipping_option'])) { ?>		

	<div class="<?php if($_REQUEST['action'] == "addshipping") { print "formTabOn"; } else { print "formTab"; } ?>"><a href="index.php?do=orders&action=addshipping&order_id=<?php print $order['order_id'];?>">ADD SHIPPING</a></div>
	<?php } ?>

	<div class="formTab tabdropdown" >
	<div ><?php print ai_arrow_down;?> STATUS: <?php 	if($order['order_status']=="0") { print "Open"; } 	if($order['order_status']=="1") { print "Archived"; } 	if($order['order_status']=="2") { print "Trashed"; } ?></div>

	<div id="galMore" class="dropdown" style="display: none; width: 150px;">
		<?php if($order['order_status'] !== "0") { ?>
		<div class="item"><a href="index.php?do=orders&action=openOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to open this order? ');">Open</a></div>
		<?php } ?>
		<?php if($order['order_status'] !== "1") { ?>
		<div class="item"><a href="index.php?do=orders&action=archiveOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to archive this order? ');">Archive</a></div>
		<?php } ?>
		<?php if($order['order_status'] !== "2") { ?>
		<div class="item"><a href="index.php?do=orders&action=trashOrder&order_id=<?php print $order['order_id'];?>"  onClick="return confirm('Are you sure you want to trash this order? Trashed orders  will not be calculated in any sales reports and customers will not be able to download.  ');">Trash</a></div>
		<?php } ?>
	</div>
	</div>
	<div class="formTab tabdropdown" >
	<div ><?php print ai_arrow_down;?> PACKING SLIP</div>
	<div id="galMore" class="dropdown" style="display: none; width: 150px;">

	<div class="item"><a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=1" target="_blank">With Prices</a></div>
	<div class="item"><a href="store/packing-slip.php?orderNum=<?php print $order['order_id'];?>&prices=0" target="_blank">Without Prices</a></div>
	</div>
	
	</div>

	<div class="cssClear"></div>
</div>

