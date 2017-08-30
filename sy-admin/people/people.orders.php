	<div id="orders">
		<div >
		<div class="underlinelabel">Orders</div>
		<?php $orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date", "WHERE order_customer='".$p['p_id']."' ORDER BY order_id DESC");
		if(mysqli_num_rows($orders) <=0) { print "<div class=\"center pc\">No orders found</div>"; } 
		while($order = mysqli_fetch_array($orders)) { ?>
		<div class="underline">
			<div style="width: <?php print $c[1];?>; " class="left"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id']; ?>" ><?php print $order['order_id']; ?></a></div>
			<div style="width: <?php print $c[2];?>; " class="left"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id']; ?>" ><?php print $order['order_date']; ?></a></div>
			<div style="width: <?php print $c[3];?>; " class="left">
			<?php if($order['order_status'] == "0") { print "Open"; } if($order['order_status'] == "1") { print "Archived"; } if($order['order_status'] == "2") { print "Trash"; } ?></div>
			<div style="width: <?php print $c[4];?>; " class="right textright"><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id']; ?>" ><?php print showPrice($order['order_total'] + $order['order_credit']); ?></a>
			<br>
			
			<?php if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ") > 0) { ?>
			Payment Schedule

			<?php } else { ?>

			<?php if($order['order_credit'] > 0) { print "<span title=\"".showPrice($order['order_credit'])." credit used\" class=\"tip\">(".showPrice($order['order_credit']).")</span>"; } ?>
							<?php if(($order['order_payment'] <=0)&&($order['order_total'] > 0)==true) { ?><span class="unpaid tip" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $order['order_id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;" title="Add Payment">UNPAID</span><?php } ?>
					<?php } ?>
			<?php
			 if($order['order_due_date'] > 0) { 
				 if(($order['order_due_date'] < date('Y-m-d')) && (($order['order_payment'] <=0)&&($order['order_total'] > 0)) == true) { ?><span class="unpaid">PAST DUE</span>
				<?php 
				}
			} ?>

			</div>

			<div class="clear"></div>

			<?php if(!empty($order['order_shipping_option'])) { ?>		
			<div class="sub small">
			<?php if(!empty($order['order_shipped_by'])) { 
			if($order['order_shipped_by_id'] > 0) { 
				$shipped = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$order['order_shipped_by_id']."' ");
			}
			?>
			 Shipped <?php print $order['order_shipped_date'];?> by <?php print $order['order_shipped_by'];?> 
			<?php if(!empty($order['order_shipped_track'])) { ?>
 			<?php  if(!empty($shipped['ship_track'])) { ?>
			<a href="<?php print $shipped['ship_track'];?><?php print $order['order_shipped_track'];?>" target="_blank"><?php print $order['order_shipped_track'];?></a>
			<?php } else { ?>
			<?php print $order['order_shipped_track'];?>
			<?php } ?>
			<?php } ?>

				<?php } else { ?>
				<a href="index.php?do=orders&action=addshipping&order_id=<?php print $order['order_id'];?>">Add Shipping</a>
				<?php } ?>
				</div>
				<?php } ?>
			</div>
	<?php } ?>
	</div>
	</div>