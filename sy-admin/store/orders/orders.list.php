<?php 
$no_trim = true;


if(!empty($_REQUEST['date_from'])) { 
	$f = explode("-",$_REQUEST['date_from']);
	$t = explode("-",$_REQUEST['date_to']);
	$date_where = "AND order_payment_date>='".$_REQUEST['date_from']." 00:00:00' AND order_payment_date<='".$_REQUEST['date_to']." 23:59:59' ";
	$sp_date_where = "payment_date>='".$_REQUEST['date_from']." 00:00:00' AND payment_date<='".$_REQUEST['date_to']." 23:59:59' ";
	$and_where = " AND order_status<'2' AND order_payment_status='Completed' ";
	$from = date('l F d, Y',mktime(0,0,0,$f[1],$f[2],$f[0]));
	$to = date('l F d, Y',mktime(0,0,0,$t[1],$t[2],$t[0]));
	$show_from =$from." - ".$to;
}
?>
<div id="pageTitle"><a href="index.php?do=orders&date_id=<?php print $_REQUEST['date_id'];?>">Orders</a> <?php if(!empty($show_from)) { print ai_sep." ".$show_from; } ?>
<?php
$table_where = "ms_orders";
	
if((empty($_REQUEST['q']))&&(empty($_REQUEST['date_from'])) ==true) {

	if($_REQUEST['orderStatus']=="openonly") {
		$and_where .= " AND order_status='0' AND order_open_status='0' ";
		print " > Open Orders Not Assigned to Custom Status";
	}

	if($_REQUEST['orderStatus']=="archived") {
		$and_where .= " AND order_status='1'";
		print " > Archived";
	}
	if($_REQUEST['orderStatus']=="trash") {
		$and_where .= " AND order_status='2' ";
		print " > Trashed";
		if(countIt("ms_orders", "WHERE order_status='2' ")>0) {
			print " (<a href=\"index.php?do=orders&action=emptyTrash\"  onClick=\"return confirm('Are you sure you want to delete all orders in the trash? Deleting these orders will permanently remove the order and can not be reversed! YOU SHOULD  ONLY DELETE TEST ORDERS! ');\">Empty Trash</a>)";
		}
	}
	if($_REQUEST['orderStatus'] == "unpaid") { 
		$table_where = "ms_orders LEFT JOIN ms_payment_schedule ON ms_orders.order_id=ms_payment_schedule.order_id";
		$and_where .= " AND order_status<='1' AND order_payment_date='0000-00-00' AND order_total>'0' AND ms_payment_schedule.id IS NULL ";
		print " > Unpaid";
	}
	if(empty($_REQUEST['orderStatus'])) {
		$and_where .= " AND order_status='0' ";
		if(!empty($_REQUEST['order_open_status'])) { 
			$status = doSQL("ms_order_status", "*", "WHERE status_id='".$_REQUEST['order_open_status']."' ");
			$and_where .= " AND order_open_status='".$_REQUEST['order_open_status']."' ";
			print " > ".$status['status_name'];
		}
	}

	if(!empty($_REQUEST['order_customer'])) { 
		$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['order_customer']."' ");
		$and_where = "AND order_customer='".$p['p_id']."' ";
		print " > <a href=\"index.php?do=people&p_id=".$p['p_id']."\">".$p['p_name']." ".$p['p_last_name']." (".$p['p_email'].")</a>";
	}
	if(!empty($_REQUEST['order_email'])) { 
		$and_where = "AND order_email='".$_REQUEST['order_email']."' ";
		print " > ".$_REQUEST['order_email']."";
	}

}

if(!empty($_REQUEST['q'])) {
	$and_where .= "AND ( order_email LIKE '%".addslashes($_REQUEST['q'])."%'  OR   order_first_name LIKE '%".addslashes($_REQUEST['q'])."%' OR  order_last_name LIKE '%".addslashes($_REQUEST['q'])."%' OR  order_id='".addslashes($_REQUEST['q'])."' OR  order_ip='".addslashes($_REQUEST['q'])."' ) ";
	print " > search for ".$_REQUEST['q']."";
}

if(!empty($_REQUEST['date_id'])) {
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	// print " > <a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">".$date['date_title']."</a>";
}
if(!empty($_REQUEST['order_aff'])) { 
	$and_where .= " AND order_aff='".$_REQUEST['order_aff']."' ";
}
if(!empty($_REQUEST['order_coupon_id'])) { 
	$and_where = " AND order_coupon_id='".$_REQUEST['order_coupon_id']."' ";
	$coupon = doSQL("ms_promo_codes","*","WHERE code_Id='".$_REQUEST['order_coupon_id']."' ");
	print " > Coupon: ".$coupon['code_name'];
}
?>
</div>
<div class="cssClear">&nbsp;</div>
<div >&nbsp;</div>



<div id="">



<?php
if(!empty($_REQUEST['date_id'])) {
	
	
	$morders = mysqli_query($dbcon,"
    SELECT * FROM (
	SELECT *  FROM ms_cart 
	 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' $and_where

  UNION ALL

    SELECT *  FROM ms_cart_archive 
	 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
	WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' $and_where ) 
	x 
	GROUP BY order_id
	");

	if (!$morders) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }
	
	
	
	
	// $datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' $and_where GROUP BY order_id ORDER BY order_id DESC ");
	$total = mysqli_num_rows($morders);
} else { 
	$total = countIt($table_where, "WHERE ms_orders.order_id>'0' $and_where $date_where"); 
	$order = doSQL($table_where, "*", "WHERE ms_orders.order_id>'0' $and_where $date_where"); 
}


if(($total == 1)&&(!empty($_REQUEST['q'])) ==true){ 
	header("location: index.php?do=orders&action=viewOrder&orderNum=".$order['order_id']."");
	session_write_close();
	exit();
}
if($total <= 0) { ?>
	
		<div  class="pageContent center bold">No orders found</div>

<?php } ?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	if($_REQUEST['per_page'] > 0) { 
		$per_page = $_REQUEST['per_page'];
		updateSQL("ms_history", "per_page='".$_REQUEST['per_page']."' ");
		$history['per_page'] = $per_page;
	} else { 
		$per_page = $history['per_page'];
	}
	$NPvars = array("do=orders", "q=".$_REQUEST['q']."","orderStatus=".$_REQUEST['orderStatus']."", "order_email=".$_REQUEST['order_email']."", "order_customer=".$_REQUEST['order_customer']."", "date_from=".$_REQUEST['date_from']."","date_to=".$_REQUEST['date_to']."", "order_open_status=".$_REQUEST['order_open_status']."", "date_id=".$date['date_id']."","order_coupon_id=".$_REQUEST['order_coupon_id']."");
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "3%";
	$cw2 = "8%";
	$cw3 = "17%";
	$cw4 = "10%";
	$cw5 = "17%";
	$cw6 = "15%";
	$cw7 = "15%";
	$cw8 = "15%";
	?>

<?php 
	$show_text = false;
	if($total > $per_page) {
		print "<div class=\"right textright\">".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</div>"; 
		?>
<div class="clear"></div>
<div>&nbsp;</div>
<?php }
	$show_text = true;
?>
<?php 
		if((!empty($_REQUEST['date_from'])) && (empty($_REQUEST['date_id'])) == true) { 
			$sps = whileSQL("ms_payment_schedule","*", "WHERE $sp_date_where ");
			if(mysqli_num_rows($sps) > 0) { ?>
				<div class="pc">Scheduled payments made on the following orders in this time frame: 
				<?php while($sp = mysqli_fetch_array($sps)) { 
				if($spx > 0) { print ", "; } 
				?>
				<a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $sp['order_id'];?>"><?php print $sp['order_id'];?></a>
				<?php 
				$spx++;
				}
				?>
				</div>
				<div>&nbsp;</div>
				<?php 
			}
		}
?>
	<form method="post" name="listForm" id="listForm" action="index.php" style="margin:0px;padding:0px;">
	<?php if($total > 0) { ?>
		<div class="underlinecolumn" >
			<div  class="roundedFormColLabel" style="width: <?php print $cw1;?>;">&nbsp;</div>
			<div  class="roundedFormColLabel" style="width: <?php print $cw2;?>;">Order #</div>
			<div  class="roundedFormColLabel" style="width: <?php print $cw3;?>;">Amount</div>
			<div  class="roundedFormColLabel" style="width: <?php print $cw4;?>;">Type</div>
			<div class="roundedFormColLabel"  style="width: <?php print $cw5;?>;">Date</div>
			<div class="roundedFormColLabel"  style="width: <?php print $cw6;?>;">Name</div>
			<div class="roundedFormColLabel"  style="width: <?php print $cw7;?>;">&nbsp</div>
				<div class="roundedFormColLabel"  style="width: <?php print $cw8;?>;">Shipping</div>
		<div  class="cssClear"></div>
		</div>
		<div >
		<?php } ?>
		<?php
		
	
		if(!empty($_REQUEST['date_id'])) {
		
		$datas = mysqli_query($dbcon,"
			SELECT * FROM (
			SELECT *  FROM ms_cart 
			 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
			WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' $and_where

		  UNION ALL

			SELECT *  FROM ms_cart_archive 
			 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
			WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart_archive.cart_store_product='".$date['date_id']."' ) AND cart_order>'0' $and_where ) 
			x 
			GROUP BY order_id ORDER BY order_id DESC LIMIT $sq_page,$per_page
			");

			if (!$datas) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }
	
		
		//	$datas = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE (cart_pic_date_id='".$date['date_id']."' OR ms_cart.cart_store_product='".$date['date_id']."' ) $and_where AND cart_order>'0'  GROUP BY order_id ORDER BY order_id DESC LIMIT $sq_page,$per_page");

		} else { 
			$datas = whileSQL($table_where, "*,date_format(DATE_ADD(order_payment_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_payment_date,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date, ms_orders.order_id AS order_id", "WHERE ms_orders.order_id>'0' $and_where $date_where ORDER BY ms_orders.order_id DESC LIMIT $sq_page,$per_page  ");
		}
		while ($data = mysqli_fetch_array($datas)) {
			$rownum++;
			if($setup['demo_mode'] == true) { 
				$data['order_first_name'] = get_starred($data['order_first_name']);
				$data['order_last_name'] = get_starred($data['order_last_name']);
				$data['order_email'] = "demo@demo.mode";
			}
	
			?>
		<div class="underline" style=" <?php if($data['order_viewed'] == "0") { ?>font-weight: bold; color: #000000;<?php } ?>" id="order-<?php print $data['order_id'];?>">

				<div style="width: <?php print $cw1;?>; " class="left">
					<?php
						print "<input  name=\"order_id[]\" value=\"".$data['order_id']."\" id=\"order_id_".$data['order_id']."\" class=\"toselect\" type=\"checkbox\" style=\"padding: 0px; margin: 0px; verticle-align: middle;\">";
					?>
				</div>
				<div style="width: <?php print $cw2;?>; <?php if($data['order_viewed'] == "0") { ?>font-weight: bold;<?php } ?>" class="left" >
				<h3><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $data['order_id']; ?>" ><?php print $data['order_id']; ?></a></h3>
				<?php if($data['order_status'] == "1") { print " <span class=inarchive>ARCHIVED</span>"; } ?>
				<?php if($data['order_status'] == "2") { print " <span class=intrash>TRASH</span>"; } ?>
				&nbsp;</div>
				<div style="width: <?php print $cw3;?>;  "class="left" ><h3><?php print showPrice($data['order_total'] + $data['order_credit'] + $data['order_gift_certificate']); ?>&nbsp; <?php if($data['order_credit'] > 0) { print "<span title=\"".showPrice($data['order_credit'])." credit used\" class=\"tip\">(".showPrice($data['order_credit']).")</span>"; } ?> <?php if($data['order_gift_certificate'] > 0) { print "<span title=\"".showPrice($data['order_gift_certificate'])." "._gift_certificate_name_."\" class=\"tip\">(".showPrice($data['order_gift_certificate']).")</span>"; } ?></h3>
				<?php if($data['order_aff'] > 0) { 
				
					$aff = doSQL("ms_affiliate LEFT JOIN ms_people ON ms_affiliate.aff_person=ms_people.p_id", "*", "WHERE aff_id='".$data['order_aff']."' ");
					?>
				<div style="background: #ffff00; padding: 4px;"><?php print $aff['aff_site'];?></div>
				<?php } ?>
				</div>
				<div style="width: <?php print $cw4;?>;  " class="left" >
				<?php 
					if($data['order_offline'] == "1") { 
					$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline' ");
					print $payopt['pay_title'];
				} elseif($data['order_offline'] == "2") { 
					$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline2' ");
					print $payopt['pay_title'];
				} else { 
				print  $data['order_pay_type']; 
				}
				?>&nbsp;</div>
				<div style="width: <?php print $cw5;?>;  " class="left" ><?php if(empty($data['order_payment_date'])) { print $data['order_date']; } else { print $data['order_payment_date']; }?>&nbsp;</div>
				<div style="width: <?php print $cw6;?>;  " class="left" >
				<?php if(!empty($data['order_customer'])) { 
					$p = doSQL("ms_people", "*", "WHERE p_id='".$data['order_customer']."' ");?>
					<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $data['order_first_name']." ".$data['order_last_name']; ?></a><br><span class="muted"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $data['order_email']; }  ?>
					<?php } else { ?>
					<?php print $data['order_first_name']." ".$data['order_last_name']; ?><br><span class="muted"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $data['order_email']; }  ?></span>
					<?php } ?>
					 <!-- (<?php print $data['order_country'];?>) -->
					</div>
				<div style="width: <?php print  $cw7;?>" class="left">


	<div class="dpmenucontainer left">
	<div class="dpmenu bold">	
	<?php
	if($data['order_status']=="0") {	
		if($data['order_open_status'] > 0) { 
			$status = doSQL("ms_order_status", "*", "WHERE status_id='".$data['order_open_status']."' ");
			print $status['status_name'];
		} else { 
			print "Open";
		}
	}
if($data['order_status']=="1") { print "Archived"; } 	if($data['order_status']=="2") { print "Trashed"; } 
	?>
	<div class="dpinner">
	<div class="pc"></div>

		<?php if($data['order_status'] !== "0") { ?>
		<div class="pc"><a href="index.php?do=orders&action=openOrder&order_id=<?php print $data['order_id'];?>&bq=<?php print $_REQUEST['q'];?>&bpg=<?php print $_REQUEST['pg'];?>&bos=<?php print $_REQUEST['orderStatus'];?>&boos=<?php print $_REQUEST['order_open_status'];?>&bto=list&date_id=<?php print $date['date_id'];?>"  onClick="return confirm('Are you sure you want to open this order? ');">Open</a></div>
		<?php } ?>

		<?php if($data['order_status'] == "0") { ?>
		<?php $statuss = whileSQL("ms_order_status", "*", "ORDER BY status_name ASC ");
		while($status = mysqli_fetch_array($statuss)) { 
			if($data['order_open_status'] !== $status['status_id']) { ?>
		<div class="pc"><a href="index.php?do=orders&action=updatestatus&order_id=<?php print $data['order_id'];?>&status_id=<?php print $status['status_id'];?>&bq=<?php print $_REQUEST['q'];?>&bpg=<?php print $_REQUEST['pg'];?>&boos=<?php print $_REQUEST['order_open_status'];?>&bos=<?php print $_REQUEST['orderStatus'];?>&bto=list&date_id=<?php print $date['date_id'];?>"><?php print $status['status_name'];?></a></div>
		<?php }
			} ?>
		<?php } ?>
		<?php if($data['order_status'] !== "1") { ?>
		<div class="pc"><a href="index.php?do=orders&action=archiveOrder&order_id=<?php print $data['order_id'];?>&bq=<?php print $_REQUEST['q'];?>&bpg=<?php print $_REQUEST['pg'];?>&bos=<?php print $_REQUEST['orderStatus'];?>&boos=<?php print $_REQUEST['order_open_status'];?>&bto=list&date_id=<?php print $date['date_id'];?>"  onClick="return confirm('Are you sure you want to archive this order? ');">Archive</a></div>
		<?php } ?>
		<?php if($data['order_status'] !== "2") { ?>
		<div class="pc"><a href="index.php?do=orders&action=trashOrder&order_id=<?php print $data['order_id'];?>&bq=<?php print $_REQUEST['q'];?>&bpg=<?php print $_REQUEST['pg'];?>&bos=<?php print $_REQUEST['orderStatus'];?>&boos=<?php print $_REQUEST['order_open_status'];?>&bto=list&date_id=<?php print $date['date_id'];?>"  onClick="return confirm('Are you sure you want to trash this order? Trashed orders  will not be calculated in any sales reports and customers will not be able to download.  ');">Trash</a></div>
		<div class="pc"><a href="" onclick="orderstatus('','<?php print $data['order_id'];?>'); return false;"><span style="color: #a4a4a4;">New Status</span></a></div>
	<?php } ?>


	
	
	</div></div> 
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	</div>
	<div class="clear"></div>

			<?php if(countIt("ms_payment_schedule", "WHERE order_id='".$data['order_id']."' ") > 0) { ?>
			Payment Schedule

			<?php } else { ?>

				<?php if(($data['order_payment'] <=0)&&($data['order_total'] > 0)==true) { ?><span class="unpaid tip" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $data['order_id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;" title="Add Payment">UNPAID</span><?php } ?>
				<?php if($data['order_due_date'] > 0) { 
				if(($data['order_due_date'] < date('Y-m-d')) && (($data['order_payment'] <=0)&&($data['order_total'] > 0)) == true) { ?><span class="unpaid">PAST DUE</span>
				<?php 
				}
				} ?>

				<?php if(($data['order_payment_status'] == "Pending")&&($data['order_payment'] > 0)==true) { ?>
						<span class="unpaid tip" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $data['order_id'];?>&noclose=1&nofonts=1&nojs=1','700'); return false;" title="Edit Payment">PENDING</span>
					
				<?php 	} ?>
				
				<?php } ?>
				
				
				&nbsp;</div>

				<div style="width: <?php print  $cw8;?>" class="left">
						<?php if(!empty($data['order_shipping_option'])) { ?>

			<?php if(!empty($data['order_shipped_by'])) { 
					if($data['order_shipped_by_id'] > 0) { 
						$shipped = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$data['order_shipped_by_id']."' ");
					}
						?>
					 Shipped <?php print $data['order_shipped_date'];?> by <?php print $data['order_shipped_by'];?>
					<?php if(!empty($data['order_shipped_track'])) { ?>
					<br>
					<?php  if(!empty($shipped['ship_track'])) { ?>
					<a href="<?php print $shipped['ship_track'];?><?php print $data['order_shipped_track'];?>" target="_blank"><?php print $data['order_shipped_track'];?></a>
					<?php } else { ?>
					<?php print $data['order_shipped_track'];?>
					<?php } ?>
					<?php } ?>

				<?php } else { ?>
						<?php if($data['order_ship_pickup'] !== "1") { ?>
						<div><?php print $data['order_shipping_option'];?></div>
						<div><a href="index.php?do=orders&action=addshipping&order_id=<?php print $data['order_id'];?>" class="tip" title="Send shipping notification email">Ship Notify</a></div>
						<?php } ?>
						<?php } ?>
						<?php } ?></div>
					<div class="cssClear"></div>
					<!-- ######################### -->

				<?php if(!empty($data['order_notes'])) { ?><div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div><div class="left"><?php print ai_message;?> Message on order</div><div class="clear"></div><?php } ?>
				<?php $notes = countIt("ms_cart", "WHERE cart_order='".$data['order_id']."' AND cart_notes!='' ");
				if($notes > 0) { ?><div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div><div class="left"><?php print ai_message;?> Message on <?php print $notes;?> <?php if($notes> 1) { print "photos"; } else { print "photo"; } ?></div><div class="clear"></div><?php } ?>
					
					
					<?php 
					if($data['order_archive_table'] == "1") { 
						$photos = whileSQL("ms_cart_archive", "*", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_pic_id>'0'   "); 
					} else { 
						$photos = whileSQL("ms_cart", "*", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_pic_id>'0'   "); 
					}
					if(mysqli_num_rows($photos) > 0) { 
						$dt = "";
						$pt = "";
					?>
					<div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div><div class="left">
					<?php

					if($data['order_archive_table'] == "1") { 
						$downs = whileSQL("ms_cart_archive", "*,SUM(cart_qty) AS total", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_download='1'  AND cart_pic_id>'0'  GROUP BY cart_download"); 
					} else { 
						$downs = whileSQL("ms_cart", "*,SUM(cart_qty) AS total", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_download='1'  AND cart_pic_id>'0'  GROUP BY cart_download"); 
					}
						while($down = mysqli_fetch_array($downs)) { 
						if($down['total'] > 0) { ?>
					 Download Photos: <?php print $down['total'] * 1;?> &nbsp;
					<?php 
						$dt = $down['total'];
						}
					}
					?>
					<?php
					if($data['order_archive_table'] == "1") { 
						$prints = whileSQL("ms_cart_archive", "*,SUM(cart_qty) AS total", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_download='0'  AND cart_pic_id>'0' GROUP BY cart_download "); 
					} else { 
						$prints = whileSQL("ms_cart", "*,SUM(cart_qty) AS total", "WHERE cart_order='".$data['order_id']."' AND cart_photo_prod>'0' AND cart_download='0'  AND cart_pic_id>'0' GROUP BY cart_download "); 
					}
					while($print = mysqli_fetch_array($prints)) { 
						if($print['total'] > 0) { ?>
					Prints: <?php print $print['total'] * 1;?> &nbsp; 
					<?php 
					$pt = $print['total'];
						} 
					}
						$tt = $dt + $pt;

					if((($dt * 1)+ ($pt * 1)) > 1) { 
						if($data['order_archive_table'] == "1") { 
							$unip = whileSQL("ms_cart_archive", "*", "WHERE cart_order='".$data['order_id']."' AND cart_pic_id>'0'  GROUP BY cart_pic_id ");
						} else { 
							$unip = whileSQL("ms_cart", "*", "WHERE cart_order='".$data['order_id']."' AND cart_pic_id>'0'  GROUP BY cart_pic_id ");
						}
						print "Unique Images: ".mysqli_num_rows($unip);
					}
					?>
					</div>
					<div class="clear"></div>
					<?php } ?>
					<?php if(countIt("ms_cart", "WHERE cart_disable_download='1' AND cart_order='".$data['order_id']."' ") > 0) { ?>
						<div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div>
										<div class="yellowmessage left" style="padding: 8px;"><a href="index.php?do=orders&action=managephotos&order_id=<?php print $data['order_id'];?>">This order has downloads pending. Click here to manage photos.</a></div>
										<div class="clear"></div>
					<?php } ?>
					
					<!-- ################# -->
		</div>
			<?php } ?>
		</div>

<script>
	$(document).ready(function(){
		hlitems();

		$(".toselect").change(function () { 
			if($(this).attr("checked")) { 
				$("#order-"+$(this).val()).removeClass("underline").addClass("underlinehl");
			} else { 
				$("#order-"+$(this).val()).removeClass("underlinehl").addClass("underline");
			}
		});
	});
		
	function selectallorders() { 
	$(".toselect").attr("checked",true);
		hlitems();
	}
	function deselectallorders() { 
		$(".toselect").attr("checked",false);
		hlitems();
	}


	function hlitems() { 
		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				$("#order-"+$(this).val()).removeClass("underline").addClass("underlinehl");
			} else { 
				$("#order-"+$(this).val()).removeClass("underlinehl").addClass("underline");
			}
		});
	}
	var ordernums = "";

	function exportOrders(){ 

		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				ordernums += "|"+$(this).val();
			}
		});
		if(ordernums == "") { 
			alert("No orders selected");
		} else { 
			window.open("export-reports.php?orderNum="+ordernums+"&filename="+$("#filename").val()+"&reportname="+$("#reportname").val()+"&sep="+$("#sep").val(),'_top');
		}

	}

	function exportOrdersPrints(){ 

		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				ordernums += "|"+$(this).val();
			}
		});
		if(ordernums == "") { 
			alert("No orders selected");
		} else { 
			if($("#dowithprint").attr("checked")) { 
				dowith = 'view';
			} else {
				dowith = '';
			}
			window.open("export-order-items.php?orderNum="+ordernums+"&filename="+$("#filename").val()+"&reportname="+$("#reportname").val()+"&sep="+$("#sep").val()+"&dowith="+dowith,'_top');
		}

	}

	function exportOrdersPrintsNew(){ 

		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				ordernums += "|"+$(this).val();
			}
		});
		if(ordernums == "") { 
			alert("No orders selected");
		} else { 
			if($("#dowithprint").attr("checked")) { 
				dowith = 'view';
			} else {
				dowith = '';
			}
			window.open("export-order-newitems.php?orderNum="+ordernums+"&filename="+$("#filename").val()+"&reportname="+$("#reportname").val()+"&sep="+$("#sep").val()+"&dowith="+dowith,'_top');
		}

	}

	function printAllOrders(prices,thumbnails) { 
		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				ordernums += "|"+$(this).val();
			}
		});
		window.open("store/packing-slip.php?orderNum="+ordernums+"&prices="+prices+"&thumbnails="+thumbnails,'_blank');
	}

	function printAllOrdersSimple(prices,thumbnails) { 
		$(".toselect").each(function() {
			if($(this).attr("checked")) { 
				ordernums += "|"+$(this).val();
			}
		});
		window.open("store/order-photos.php?orderNum="+ordernums+"&prices="+prices+"&thumbnails="+thumbnails,'_blank');
	}

	function toggleprint() { 
		$("#printoptions").slideToggle(100);
	}

	function showexport() { 
		$("#exportreport").slideToggle(100);
	}
	function showexportprints() { 
		$("#exportprints").slideToggle(100);
	}
	function newshowexportprints() {
		$("#newexportprints").slideToggle(100);	
	}


		</script>

	
		<?php 	if($total > 0) { ?>
		<div>&nbsp;</div>
		<div>
	<div style="float: right;">
		<?php 
		if($total > $per_page) {
			print "<center>".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</center>"; 
			}
		?>
		<div>&nbsp;</div>
		<?php 
		foreach($_GET AS $gn => $gr) { 
			if($gn !== "per_page") { 
				$strv .= "&".$gn."=".$gr;	
			}
		}
		?>
		<div class="pc center">
			Show per page: <?php if($history['per_page'] !== "20") { print "<a href=\"index.php?per_page=20".$strv."\" class=\"np\">20</a>"; } else { print "<span class=\"np\">20</span>"; } ?>
			<?php if($history['per_page'] !== "50") { print "<a href=\"index.php?per_page=50".$strv."\" class=\"np\">50</a>"; } else { print "<span class=\"np\">50</span>"; } ?>
			<?php if($history['per_page'] !== "100") { print "<a href=\"index.php?per_page=100".$strv."\" class=\"np\">100</a>"; } else { print "<span class=\"np\">100</span>"; } ?>
		</div>
		<div class="clear"></div>
	</div>		


	<div style="float: left;">
		<div class="pc"><a href="" onclick="selectallorders(); return false;">Select All</a> &nbsp; <a href="" onclick="deselectallorders(); return false;">Deselect All</a></div>
		<div class="pc">
		<input type="hidden" name="do" value="orders">
		<input type="hidden" name="pg" value="">
		<input type="hidden" name="q" value="<?php print $_REQUEST['q'];?>">
		<input type="hidden" name="order_open_status" value="<?php print $_REQUEST['order_open_status'];?>">
		<input type="hidden" name="date_id" value="<?php print $_REQUEST['date_id'];?>">
		<select name="action">
		<option value="">Action for selected items</option>
		<option value="updateViewed">Mark as viewed</option>
		<option value="updateUnViewed">Mark as unviewed</option>
		
		<?php $statuss = whileSQL("ms_order_status", "*", "ORDER BY status_name ASC ");
		while($status = mysqli_fetch_array($statuss)) { ?>
		<option value="<?php print $status['status_id'];?>">Send to <?php print $status['status_name'];?></option>
		<?php } ?>

	
		<option value="batchArchive">Send to archive</option>
		<option value="batchTrash">Send to trash</option>
		<option value="batchToNew">Send to open / unassigned</option>
		
		</select>
		<input type="submit" name="submit" class="submitSmall" value="Go">
		</div>

</form>


		<div class="pc"><a href="" onclick="toggleprint(); return false;" class="the-icons icon-print">Print Selected Orders</a></div>
		<div id="printoptions" class="hide">
			<div class="pc"> &nbsp; &bull; <a href="" onclick="printAllOrders('1','1'); return false;">With Prices & Thumbnails</a></div>
			<div class="pc"> &nbsp; &bull; <a href="" onclick="printAllOrders('0','1'); return false;">Without Prices & Thumbnails</a></div>
			<div class="pc"> &nbsp; &bull; <a href="" onclick="printAllOrders('1','0'); return false;">With Prices No Thumbnails</a></div>
			<div class="pc"> &nbsp; &bull; <a href="" onclick="printAllOrders('0','0'); return false;">Without Prices No Thumbnails</a></div>
			<div>&nbsp;</div>
		</div>
		
		<div class="pc"><a href="" onclick="printAllOrdersSimple('0','0'); return false;"  class="the-icons icon-print">Print Simple Photo List For Selected Orders</a></div>
		
		<?php } ?>
	<?php if($total > 0) { ?>

<div class="pc ">
<a href="" onclick="showexport(); return false;" class="the-icons icon-export" >Report Export For Selected Orders</a>
</div>
<div id="exportreport" class="underline hidden">
<div class="pc">
<form method="post" name="export" id="export" action="export-reports.php" onsubmit="exportOrders(); return false;">
File Name <input type="text" name="reportname" id="reportname" size="30" value="Order export <?php print date('Y-m-d');?>"> Separate with <input type="text" name="sep" id="sep" value="," size="2" class="center"> save as file <input type="text" name="filename" id="filename" value="csv" size="4" class="center"> 
<input type="submit" name="submit" value="Export" class="submitSmall">
</form>
</div>
<div class="pc"><a href="index.php?do=orders&action=exportsettings" class="the-icons icon-cog">Settings</a></div>
			<div>&nbsp;</div>
</div>
 <div class="pc"><?php if($site_setup['sytist_version'] < 1.8) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?><a href="" onclick="showexportprints(); return false;" class="the-icons icon-export">Photo Product Export For Selected Orders</a> </div>
<div id="exportprints" class="hide">
	<div class="pc">
		<form method="post" name="export" id="export" action="export-reports.php" onsubmit="exportOrdersPrints(); return false;">
		<div class="pc">This allows you to export the photo file names on selected orders with the products selected for those photos.</div>
		<div class="pc">
		<!-- Separate with <input type="text" name="printsep" id="printsep" value="," size="2" class="center"> --> <input type="radio" name="dowith" id="dowithprint" value="1"> <label for="dowithprint">Print to screen</label> &nbsp; or &nbsp;  <input type="radio" name="dowith" id="dowithsave" value="2" checked> <label for="dowithsave">save as file</label>  &nbsp;  File Name <input type="text" name="printreportname" id="printreportname" size="30" value="Order product export <?php print date('Y-m-d');?>"> .<input type="text" name="printfilename" id="printfilename" value="csv" size="4" class="center"> 
		<input type="submit" name="submit" value="Export" class="submitSmall">
		</div>
		</form>
	</div>
	<div class="pc"><a href="index.php?do=orders&action=itemsexportsettings"  class="the-icons icon-cog">Settings</a></div>
			<div>&nbsp;</div>
</div>
<div class="pc"><?php if($site_setup['sytist_version'] < 1.8) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?><a href="" onclick="newshowexportprints(); return false;" class="the-icons icon-export">Filter Photo Product Export For Selected Orders</a> </div>
<div id="newexportprints" class="hide">
	<div class="pc">
		<form method="post" name="export" id="new-export" action="export-reports-new.php" onsubmit="exportOrdersPrintsNew(); return false;">
		<div class="pc">This allows you to export the photo file names on selected orders with the products selected for those photos.</div>
		<div class="pc">
		<!-- Separate with <input type="text" name="printsep" id="printsep" value="," size="2" class="center"> --> <input type="radio" name="dowith" id="dowithprint" value="1"> <label for="dowithprint">Print to screen</label> &nbsp; or &nbsp;  <input type="radio" name="dowith" id="dowithsave" value="2" checked> <label for="dowithsave">save as file</label>  &nbsp;  File Name <input type="text" name="printreportname" id="printreportname" size="30" value="Order product export <?php print date('Y-m-d');?>"> .<input type="text" name="printfilename" id="printfilename" value="csv" size="4" class="center"> 
		<input type="submit" name="submit" value="Export" class="submitSmall">
		</div>
		</form>
	</div>
	<div class="pc"><a href="index.php?do=orders&action=itemsexportsettings"  class="the-icons icon-cog">Settings</a></div>
			<div>&nbsp;</div>
</div>
</div>
<div class="clear"></div>
</div>
<?php } ?>
<div>&nbsp;</div>
		

