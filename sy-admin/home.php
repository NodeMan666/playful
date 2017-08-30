<?php
/* Fix missing state */
$as = doSQL("ms_states", "*", "WHERE state_country='Australia' AND state_name='Tasmania' ");
if(empty($as['state_id'])) { 
	insertSQL("ms_states", "state_country='Australia', state_name='Tasmania', state_abr='TAS', state_ship_to='1' ");
}



$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
$loggedin = doSQL("ms_admins", "*", "WHERE admin_id='".$_SESSION['office_admin_id']."' ");

$history = doSQL("ms_history", "*", "  ");

$no_trim = true;
?>
<?php 
if(($loggedin['admin_master'] == "0")&&($loggedin['admin_full_access'] == "0")==true) { 
	if($loggedin['stats'] !== "1") { 
		$hide_stats = true;
	}
	if($loggedin['content'] !== "1") { 
		$hide_content = true;
	}
	if($loggedin['orders'] !== "1") { 
		$hide_orders = true;
	}
	if($loggedin['allphotos'] !== "1") { 
		$hide_photos = true;
	}
	if($loggedin['comments'] !== "1") { 
		$hide_comments = true;
	}
	if($loggedin['reports'] !== "1") { 
		$hide_reports = true;
	}
	if($loggedin['people'] !== "1") { 
		$hide_people= true;
	}

}
?>
<div>&nbsp;</div>
<style>
.largebox {  } 
.masterpad { padding: 0px !important;} 
.smallbox { } 

.smallbox .inner { margin: 0 12px 16px 12px ;} 
.largebox .inner { margin: 0 36px 16px 36px;} 
.homeunderline { border-bottom: solid 1px #e4e4e4; padding: 8px 0px; } 
.homeh3 { background: #a4a4a4; border-radius: 0px;  padding: 8px; color: #949494; font-size:17px; text-shadow: 0px 0px 1px #949494;  margin: 0px 0px 8px 0px; } 

a.homeh3. a.homeh3: link, a.homeh3:visited { color: #FFFFFF; }
.homeh3 a, .homeh3 a:link, .homeh3 a:visited { color: #FFFFFF; } 
.homeh3 a:hover { color: #000000; text-shadow: 0px 0px 1px #c4c4c4; } 
.styledbox { border: solid 1px #F4F4F4; background: #F9F9F9; padding: 16px 12px; margin-top: -12px;   ; } 
.styledbox .row { padding: 12px 2px; border-bottom: solid 1px #e7e7e7; } 
.homebig1 { font-size: 30px; color: #000000; } 
.homebig2 { font-size: 21px; color: #000000; } 
.homebutton { padding: 4px; background: #ffffff; border: solid 1px #e4e4e4; color: #000000;} 
.homebutton :hover{ padding: 4px; background: #000000; border: solid 1px #e4e4e4; color: #000000;} 
.homelabel { background: #FFFFFF; border: solid 1px #DDDDDD; padding: 8px;  color: #242424;float: left; clear: right; margin-left: 8px; font-family: 'BebasNeue-webfont', arial; font-size: 17px; } 
.homelabel a { color: #000000; } 
.homelabel a:hover { color: #309DF0; text-decoration: none; } 
.homeactionbullet { color: #b4b4b4; font-size: 17px; cursor: pointer; } 
.homeactionbullet:hover { color: #5DBC4C; font-size: 17px; cursor: pointer; font-weight: bold; } 

</style>
<?php
if($_SESSION['showwiz'] == true) {?>
<script>
$(document).ready(function(){
	newwizard();
	stophomerefresh();
});
</script>

<?php 
unset($_SESSION['showwiz']);
} ?>


<div style="padding: 0px 16px;">
		<div class="left nofloatsmall" style="width: 25%; background: #F4F4F4;">
			<div style="padding: 16px;">

		<?php include "home.notices.php"; ?> 
		<?php // print round(((memory_get_usage() / 1024) / 1024),2)." MB"; ?>

		
		
	<div  <?php if($hide_stats == true) { print "style=\"display: none;\""; } ?>>
	<div class="homeh3"><a href="index.php?do=stats">Visitors</a></div>
		<?php
			$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
			$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
			$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
			$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
			$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));
			$photoviews = countIt("ms_photo_stats", "WHERE pv_date='$date'");
		?>
		<div class="homeunderline">
			<div class="pc"><span class="homebig1"><?php print  number_format($visitors,0);?></span> <span class="homebig2">Today</span></div>
			<div class="pc"><div class="left"><?php print  number_format($pv,0);?> Page Views </div> <div class="right textright"><?php print $nvisitors;?> New / <?php print $rvisitors;?> Returning</div>
			<div class="clear"></div>
			</div>
		</div>

		<?php 
		$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
		$visitors =countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
		$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
		$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
		$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));
		?>
		<div class="homeunderline">
			<div class="pc"><span class="homebig1"><?php print  number_format($visitors,0);?></span> <span class="homebig2">Yesterday</span></div>
			<div class="pc"><div class="left"><?php print  number_format($pv,0);?> Page Views </div> <div class="right textright"><?php print $nvisitors;?> New / <?php print $rvisitors;?> Returning</div>
			<div class="clear"></div>
			</div>
		</div>
		<?php
		$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
		$visitors =countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'  ");
		$pv = countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date)='".date('m')."' AND YEAR(pv_date)='".date('Y')."'   ");
		$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'   AND st_last_visit > 0"));
		$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'   AND st_last_visit ='0000-00-00'"));
		$photoviews = countIt("ms_photo_stats", "WHERE  MONTH(pv_date)='".date('m')."' AND YEAR(pv_date)='".date('Y')."'  ");
		?>
		<div class="homeunderline">
			<div class="pc"><span class="homebig1"><?php print  number_format($visitors,0);?></span> <span class="homebig2">This Month</span></div>
			<div class="pc"><div class="left"><?php print number_format($pv,0);?> Page Views </div> <div class="right textright"><?php print $nvisitors;?> New / <?php print $rvisitors;?> Returning</div>
			<div class="clear"></div>
			</div>
		</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>






	<div class="homeh3"><a href="index.php?do=stats&action=recentVisitors">Recent Visitors</a></div>
		<?php

		$viss = whileSQL("ms_stats_site_visitors", "*, date_format(st_date, '%a %b %e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", " $and_mem ORDER BY st_id DESC LIMIT 10");
		if(mysqli_num_rows($viss)<=0) { 
			print "<div class=\"row\">No Data</div>";
		}
		while ( $vis = mysqli_fetch_array($viss) ) {

		?>
	<div class="pc homeunderline ">

	<div><span style="float: left;">
	<div class="pageviews" title="Page Views"><span><?php print countIt("ms_stats_site_pv", "WHERE pv_ref_id='".$vis['st_id']."' ");?></span></div>
<?php
		if($vis['st_last_visit'] > 0) {
			print " <div class=\"returnvis\" title=\"Return Visitor ".$vis['st_last_visit']."\"><span>R</span></div>";
		} else {
			print " <div class=\"newvis\" title=\"New Visitor\"><span>N</span></div>";
		}
		 if($vis['st_mobile'] == "1") { print "<div class=\"mobile\" title=\"Mobile\"><span>M</span></div> &nbsp; "; } if($vis['st_ipad'] == "1") { print "<div class=\"tablet\" title=\"Tablet\"><span>T</span></div> &nbsp; "; } ?>
	<a href="index.php?do=stats&action=visitordetails&pv_ref_id=<?php print $vis['st_id'];?>">
		<?php if($vis['st_member'] > 0) { 
			$person = doSQL("ms_people", "*", "WHERE p_id='".$vis['st_member']."' ");
			if($setup['demo_mode'] == true) { 
				$person['p_name'] = get_starred($person['p_name']);
				$person['p_last_name'] = get_starred($person['p_last_name']);
				$person['p_email'] = "demo@demo.mode";
			}

			?>
			<?php  if((empty($person['p_name'])) && (empty($person['p_last_name'])) == true) { print $person['p_email']; } else { print $person['p_name']." ".$person['p_last_name']; } ?>
			<?php } else { ?><?php  print $vis['st_ip']; ?>
			<?php } ?> 
			</a>

&nbsp;
	</span>  <span style="float: right;"><?php print $vis['st_date']."   ".$vis['st_time'];?></span><div class="cssClear"></div></div>
	<div>
	<span style="float: right;">

<?php 		if(empty($vis['st_refer'])) {
			print "Direct Hit";
		} else {
			$info = explode('//', $vis['st_refer']); 
			if(empty($info[1])) {
				$info = explode('/', $vis['st_refer']); 
				$show_this = str_replace("www.","",$info[0]);
			} else {
				$d1 = $info[1]; 
				$info2 = explode('/', $d1); 
				$d2 = $info2[0]; 
				$show_this = str_replace("www.","",$d2);
			}
			$vis['st_refer'] = str_replace("http://","",$vis['st_refer']);
			print "<a href=\"http://".$vis['st_refer']."\" target=\"_Blank\" class=smr title=\"".$vis['st_refer']."\">".$show_this."</a>";
		}
?>
</span><div class="cssClear"></div>
<?php if($vis['st_aff'] > 0) { 
	$aff = doSQL("ms_affiliate", "*", "WHERE aff_id='".$vis['st_aff']."' "); 
	if($aff['aff_id'] <=0) { 
		$aff = doSQL("ms_affiliate_click LEFT JOIN ms_affiliate ON ms_affiliate_click.click_aff=ms_affiliate.aff_id", "*", "WHERE click_id='".$vis['st_aff']."' ");
	}
	?>
	<div class="pc" style="background: #ffff00;"><?php if($aff['aff_track'] == "0") { print "Affiliate: "; }  print $aff['aff_site'];?></div>
	<?php } ?>

			<?php if($person['p_id'] <=0) { 
				$order = doSQL("ms_orders", "*,date_format(order_date, '".$site_setup['date_format']." %h:%i %p')  AS order_date", "WHERE order_ip='".$vis['st_ip']."' ORDER BY order_id DESC ");
				if(!empty($order['order_id'])) { ?>
				<div class="pc">Purchased on <?php print $order['order_date'];?><br><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>">#<?php print $order['order_id'];?> <?php print $order['order_first_name']." ".$order['order_last_name'];?></a></div>
				<?php } ?>
				<?php } ?>

</div>
 </div>
<?php } ?>
<?php mysqli_free_result($viss); ?>
<div>&nbsp;</div>

<div>&nbsp;</div>


<div class="homeh3"><a href="index.php?do=stats&action=pages">Most Popular Pages Today</a></div>

<?php
		$when = date("Y-m-d");
		$whendo = "pv_date='$when'";

$result = mysqli_query($dbcon,"SELECT  page_viewed, date_id, COUNT(*) AS dups FROM ms_stats_site_pv  WHERE $whendo GROUP BY page_viewed ORDER BY dups DESC LIMIT 10");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
if($pagetotal <= 0) { ?>
<div class="row center homeunderline ">No page views today so far.</div>
<?php } 
while ( $row = mysqli_fetch_array($result) ) {

	if(!empty($row['page_viewed'])) { 
?>	
	<div class="pc homeunderline"><div style="width:10%;" class="left"><?php print $row["dups"];?></div><div style="width:90%;" class="left">

<?php	showVisPage($row);	?></div>
		<div class="cssClear"></div>
	</div>
<?php 	}
} ?>
<?php mysqli_free_result($result); ?>

</div>
<div>&nbsp;</div>



		
		
		
		</div>
	</div>


<!-- START RIGHT SIDE -->
		<div class="right nofloatsmall" style="width: 25%; background: #F4F4F4;">
			<div style="padding: 16px;">





<?php if($sytist_store == true) { ?>
 <div <?php if($hide_reports == true) { print "style=\"display: none;\""; } ?>>

<!-- <div class="pc"><a href="" onclick="editexpense(''); return false;">Enter Expense</a></div> -->



	<div class="homeh3"><a href="index.php?do=reports">Reports</a></div>
		<div class="homeunderline">
		<div class="left">This Month<span class="numorders" title="# of orders"><span class="height_fix"></span><span class="content"><?php print countIt("ms_orders", "WHERE MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."' AND order_status<'2'  ");?></span> </div><div class="right textright">
		<?php 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."' AND order_status<'2' "); 
		$sp_total = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE MONTH(payment_date)='".date('m')."' AND YEAR(payment_date)='".date('Y')."'  "); 
		$stot = $stotal['tot'] + $sp_total['tot'];
			if($stot <=0) { 
				print "<span style=\"font-size: 36px; color: #b4b4b4;\">".showPrice($stot)." </span> ";
			} else { 
				print "<span style=\"font-size: 36px; color: #008900;\">".showPrice($stot)." </span> ";
			}
			?>		

		</div>
		<div class="clear"></div>
		</div>
	

			<div class="homeunderline">
		<div class="left">Today<span class="numorders" title="# of orders"><span class="height_fix"></span><span class="content"><?php print countIt("ms_orders", "WHERE MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."'  AND DAYOFMONTH(order_payment_date)='".date('d')."'  AND order_status<'2'  ");?></span> </div><div class="right textright">
		<?php 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."' AND DAYOFMONTH(order_payment_date)='".date('d')."'   AND order_status<'2' "); 
		$sp_total = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE MONTH(payment_date)='".date('m')."' AND YEAR(payment_date)='".date('Y')."' AND DAYOFMONTH(payment_date)='".date('d')."'    "); 
		$stot = $stotal['tot'] + $sp_total['tot'];
			if($stot <=0) { 
				print "<span style=\"font-size: 21px; color: #b4b4b4;\">".showPrice($stot)." </span> ";
			} else { 
				print "<span style=\"font-size: 21px; color: #008900;\">".showPrice($stot)." </span> ";
			}
			?>		

		</div>
		<div class="clear"></div>
		</div>

	<?php 
			$yd  = date("d", mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
			$ym  = date("m", mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
			$yy  = date("Y", mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
?>
			<div class="homeunderline">
		<div class="left">Yesterday<span class="numorders" title="# of orders"><span class="height_fix"></span><span class="content"><?php print countIt("ms_orders", "WHERE MONTH(order_payment_date)='".$ym."' AND YEAR(order_payment_date)='".$yy."'  AND DAYOFMONTH(order_payment_date)='".$yd."'  AND order_status<'2'  ");?></span> </div><div class="right textright">
		<?php 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".$ym."' AND YEAR(order_payment_date)='".$yy."' AND DAYOFMONTH(order_payment_date)='".$yd."'   AND order_status<'2' "); 
		$sp_total = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE MONTH(payment_date)='".$ym."' AND YEAR(payment_date)='".$yy."' AND DAYOFMONTH(payment_date)='".$yd."' "); 
		$stot = $stotal['tot'] + $sp_total['tot'];
			if($stot <=0) { 
				print "<span style=\"font-size: 21px; color: #b4b4b4;\">".showPrice($stot)." </span> ";
			} else { 
				print "<span style=\"font-size: 21px; color: #008900;\">".showPrice($stot)." </span> ";
			}
			?>		

		</div>
		<div class="clear"></div>
		</div>
		<?php
			$lmm  = date("m", mktime(0, 0, 0, date("m") -1 , 1, date("Y")));
			$lmy  = date("Y", mktime(0, 0, 0, date("m") -1 , 1, date("Y")));
		?>
			<div class="homeunderline">
		<div class="left">Last Month<span class="numorders" title="# of orders"><span class="height_fix"></span><span class="content"><?php print countIt("ms_orders", "WHERE MONTH(order_payment_date)='".$lmm."' AND YEAR(order_payment_date)='".$lmy."' AND order_status<'2' ");?></span> </div><div class="right textright">

		<?php 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".$lmm."' AND YEAR(order_payment_date)='".$lmy."' AND order_status<'2' "); 
		$sp_total = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE MONTH(payment_date)='".$lmm."' AND YEAR(payment_date)='".$lmy."' "); 
		$stot = $stotal['tot'] + $sp_total['tot'];
			if($stot <=0) { 
				print "<span style=\"font-size: 27px; color: #b4b4b4;\">".showPrice($stot)." </span> ";
			} else { 
				print " <span style=\"font-size: 27px; color: #008900;\">".showPrice($stot)."</span>  ";	
			}
		?>
		</div>
		<div class="clear"></div>
		</div>
	
	

		<div class="homeunderline">
		<div class="left">Year To Date<span class="numorders" title="# of orders"><span class="height_fix"></span><span class="content"><?php print countIt("ms_orders", " WHERE YEAR(order_payment_date)='".date('Y')."' AND order_status<'2' ");?></span> </div><div class="right textright">
		
		<?php 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE  YEAR(order_payment_date)='".date('Y')."' AND order_status<'2' "); 
		$sp_total = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE  YEAR(payment_date)='".date('Y')."' "); 
		$stot = $stotal['tot'] + $sp_total['tot'];
			if($stot <=0) { 
				print "<span style=\"font-size: 27px; color: #b4b4b4;\">".showPrice($stot)." </span> ";
			} else { 
				print " <span style=\"font-size: 27px; color: #008900;\">".showPrice($stot)."</span> "; 
				
			}
			?>
		</div>
		<div class="clear"></div>
		</div>


		<div class="homeunderline">
			<div class="left"><?php print date('Y');?> Expenses<br>
			<a href="" onclick="editexpense(''); return false;">Enter Expense</a></div>
			<div class="right textright" style="color: #890000; font-size: 17px;">
		<?php $yexptotal = doSQL("ms_expenses", "SUM(exp_amount) AS tot", "WHERE YEAR(exp_date)='".date('Y')."'   "); 
		print showPrice($yexptotal['tot']);?>
		</div>
		<div class="clear"></div>
		</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>



 <div <?php if($hide_orders == true) { print "style=\"display: none;\""; } ?>>
	<div class="homeh3"><a href="index.php?do=orders">Newest Orders</a></div>

<?php
$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_payment_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."')  AS order_payment_date", "WHERE order_status<'2' ORDER BY order_id DESC LIMIT 5 ");
if(mysqli_num_rows($orders)<=0) { ?>
<div id="roundeddataitem"><div style="text-align:center;" class="cssCell">No new orders</div><div class="cssClear"></div></div>
<?php 
	}
while($order = mysqli_fetch_array($orders)) { 
	if($setup['demo_mode'] == true) { 
		$order['order_first_name'] = get_starred($order['order_first_name']);
		$order['order_last_name'] = get_starred($order['order_last_name']);
		$order['order_email'] = "demo@demo.mode";
	}
	
	?>
<div class="homeunderline">
	<div style="width:40%;" class="left"><?php print "<a href=\"index.php?do=orders&action=viewOrder&orderNum=".$order['order_id']."\">".$order['order_id']."</a>";?></div>
	<div style="width:20%;" class="left textright"><?php print "".showPrice($order['order_total'] + $order['order_credit'] + $order['order_gift_certificate'])."";?> <?php if($order['order_credit'] > 0) { print "<br><span title=\"".showPrice($order['order_credit'])." credit used\" class=\"tip\">(".showPrice($order['order_credit']).")</span>"; } ?> <?php if($order['order_gift_certificate'] > 0) { print "<br><span title=\"".showPrice($order['order_gift_certificate'])." gift certificate\" class=\"tip\">(".showPrice($order['order_gift_certificate']).")</span>"; } ?></div>
	<div style="width:40%;" class="left textright"><?php print "".$order['order_payment_date']."";?></div><div class="clear"></div>

	<div style="width:50%;" class="left"><?php print "".$order['order_first_name']." ".$order['order_last_name']."";?></div>
	<div style="width:50%;" class="left textright">
	<?php if($order['order_payment_status'] == "Pending") { 
			if($order['order_offline'] == "1") { ?>
			<div class="red">Pending Payment</div>
			<div class=""><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $order['order_id'];?>&noclose=1&nofonts=1&nojs=1','700'); return false;">Add Payment</a></div>
			<?php 
			} else { 
			print "<span class=\"red\">".$order['order_payment_status']."</span>"; 
			}
		
		} else { 
		?>
	<?php 
	if($order['order_status']=="0") {	
		if($order['order_open_status'] > 0) { 
			$status = doSQL("ms_order_status", "*", "WHERE status_id='".$order['order_open_status']."' ");
			print "<b>".$status['status_name']."</b>";
		}
	}
	?>
	<?php if($order['order_status'] == "1") { ?><b>Archived</b><?php } ?>
	<?php } ?>
	</div>
<div class="clear"></div>
	<?php if(!empty($order['order_notes'])) { ?><div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div><div class="left"><?php print ai_message;?> Message on order</div><div class="clear"></div><?php } ?>
	<?php $notes = countIt("ms_cart", "WHERE cart_order='".$order['order_id']."' AND cart_notes!='' ");
	if($notes > 0) { ?><div style="width: <?php print $cw1;?>; " class="left">&nbsp;</div><div class="left"><?php print ai_message;?> Message on <?php print $notes;?> <?php if($notes> 1) { print "photos"; } else { print "photo"; } ?></div><div class="clear"></div><?php } ?>

	
<div class="clear"></div>
 </div>
<?php } ?>
<?php mysqli_free_result($orders); ?>
<div>&nbsp;</div>
<div>&nbsp;</div>
</div>
</div>

 <div <?php if($hide_stats == true) { print "style=\"display: none;\""; } ?>>


<?php 
	$ems = countIt("ms_email_list",  "WHERE em_status='0' ORDER BY em_id DESC"); 
	if($ems > 0) { ?>
	<div class="homeh3"><a href="index.php?do=people&view=mailList">Newest Mailing List Subscriptions</a></div>
	<?php $ems = whileSQL("ms_email_list", "*,date_format(em_date, '".$site_setup['date_format']."')  AS em_date", "WHERE em_status='0' ORDER BY em_id DESC LIMIT 5"); 
	while($em = mysqli_fetch_array($ems)) { 
		if($setup['demo_mode'] == true) { 
			$em['em_email'] = get_starred($em['em_email']);
			$em['em_name'] = get_starred($em['em_name']);
			$em['em_last_name'] = get_starred($em['em_last_name']);
		}
		
		?>
	<div class="homeunderline">
		<div class="left p50"><?php print $em['em_email'];?></div>
		<div class="left p50 textright"><?php print $em['em_date'];?></div>
		<div class="clear"></div>
		<?php if($em['em_date_id'] > 0) { 
		$d = doSQL("ms_calendar", "*", "WHERE date_id='".$em['em_date_id']."' ");
		?>
		<div class="pc">Restock Request: <a href="index.php?do=news&action=addDate&date_id=<?php print $d['date_id'];?>"><?php print $d['date_title'];?></a>  (<?php print countIt("ms_email_list", "WHERE em_date_id='".$d['date_id']."' "); ?>)</div>
		<?php } ?>
	</div>
	<?php } ?>
<div>&nbsp;</div>
<?php } ?>


	<div class="homeh3"><a href="index.php?do=stats&view=shares">Recent Shares</a></div>

		<?php
		$shares = whileSQL("ms_shares LEFT JOIN ms_calendar ON ms_shares.share_page=ms_calendar.date_id", "*,date_format(DATE_ADD(share_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS share_date", "WHERE share_id>'0'  ORDER BY share_id DESC LIMIT  5 ");
		if(mysqli_num_rows($shares) <= 0) { ?>
		<div class="pc center">No shares</div>
		<?php 
		}
		while ($share = mysqli_fetch_array($shares)) {
			if(!empty($share['date_id'])) { 

				?>
				<div class="homeunderline">
					<?php if($share['share_photo'] > 0) { 
						$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$share['share_photo']."' ");
						if(!empty($pic['pic_id'])) { 
						?>
						<div class="left" style="margin-right: 16px;"><img src="<?php print getimagefile($pic,'pic_mini');?>"></div>
						<div class="left">
							<div><b>Photo: <?php print $pic['pic_org'];?></b></div>
						</div>
						<div class="clear"></div>
						<?php } ?>

					<?php } else {  ?>
					<?php showVisPage($share);?>
					<?php } ?>
					<div class="clear"></div>
					<div class="right textright"><?php print $share['share_where'];?></div>
					<div class="clear"></div>
				</div>
			<?php
			}
		} ?>

<?php mysqli_free_result($shares); ?>

		</div>

<div>&nbsp;</div>
<div>&nbsp;</div>

<?php if($hide_stats !== true) { ?>
	<div class="homeh3"><a href="index.php?do=stats&view=emails">Email Log</a></div>

		<?php
		$emails = whileSQL("ms_email_logs", "*,date_format(DATE_ADD(log_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS log_date", "WHERE log_id>'0'  ORDER BY log_id DESC LIMIT  5 ");
		if(mysqli_num_rows($emails) <= 0) { ?>
		<div class="pc center">No emails sent</div>
		<?php 
		}
		while ($email = mysqli_fetch_array($emails)) {
			$fannum++;
			?>
			<div class="homeunderline">
				<div style="width: 50%; overflow: hidden;" class="left"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_from']; }  ?>&nbsp;</div>
				<div style="width: 50%;overflow: hidden;" class="right textright"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $email['log_to']; }  ?></div>
				<div class="clear"></div>
				<div><?php print $email['log_date']; ?>&nbsp;</div>
				<div><a href="" onclick="pagewindowedit('w-view-email.php?log_id=<?php print $email['log_id'];?>&noclose=1&nofonts=1','','1'); return false;"><?php print $email['log_subject']; ?></a>&nbsp;</div>
				<div class="cssClear"></div>
			</div>
			<?php } ?>

<?php mysqli_free_result($emails); ?>

<div>&nbsp;</div>
<div>&nbsp;</div>
<?php } ?>




	<?php } ?>




<?php if(($site_setup['index_page'] == "indexnew.php")&&($site_setup['hide_help'] == "1")==true) { ?>

	<div class="homeh3"><a href="index.php?do=activateSite">GO LIVE &rarr;</a></a></div>
	<div class="homeunderline">
		When you get your new website ready and ready to go live with it, <a href="index.php?do=activateSite">click here</a>. (One more step).
	</div>
	<div>&nbsp;</div>
<?php } ?>




	<div>&nbsp;</div>
<?php if($loggedin['admin_master'] == "1") { ?>

 <?php if($setup['unbranded'] !== true) { ?>

	<?php if($setup['sytist_hosted'] !== true) { ?>
	<?php $reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '%M %e, %Y ')  AS reg_date, date_format(DATE_ADD(reg_update_expire, INTERVAL 0 HOUR), '%M %e, %Y ')  AS reg_update_expire_show", ""); ?>

	<div class="homeh3">Sytist Registration</div>
		<div class="homeunderline">Name: <?php print $reg['reg_name'];?></div>
		<div class="homeunderline">Email: <?php print $reg['reg_email'];?></div>
		<?php if($setup['demo_mode'] !== true) { ?>
		<div class="homeunderline">Date: <?php print $reg['reg_date'];?></div>
		<div class="homeunderline">Key: <?php print $reg['reg_key'];?></div>
		<?php 
		if($reg['reg_update_expire'] == "0000-00-00") { 
			$reg = @url_get_contents("https://www.picturespro.com/sytistupdateexpire.php?version=".$site_setup['sytist_version']."&reg=".$reg['reg_key']."");
			if(!empty($reg)) { 
				updateSQL("ms_register", "reg_update_expire='".$reg."' ");
			}
		} else {  ?>
		<div class="homeunderline">Upgrades through: <?php print $reg['reg_update_expire_show'];?></div>

		<?php }?>

		<?php } ?>
		<div class="clear"></div>
	<?php } ?>
<?php } ?>
<?php } ?>
	</div>
</div>


<!-- END RIGHT SIDE -->






<div class="left nofloatsmall" style="width: 50%;">
	<div style=" height: 100%; min-height: 100%;  position: relative; background: #FFFFFF;">
			<div style="padding: 16px; margin: 0px 0px;  box-shadow: 0px 0px 16px rgba(0,0,0,.5); z-index: 2; height: 100%; background: #FFFFFF;min-height: 100%; ">






<div class="largebox">
	<div class="inner">


<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc center"><h1>Welcome to the Sytist admin demo! </h1></div>
		<div class="pc center"><h3>Across the top of the page is the Main Menu with links to the different areas of the admin.</h3></div>
		<div class="pc">
		<ul style="margin: 16px; list-style-type:square; color: #000000; font-size: 15px;">
			<li style="margin-bottom: 16px;">To create & manage <b>sections, pages, client galleries, etc ...</b>, click on the <b><a href="index.php?do=news&date_cat=100"><b>Site Content</b></a></b> link.</li>
			<li style="margin-bottom: 16px;">To <b>design</b> the look, colors, header, etc.., click on the  <b><a href="index.php?do=look"><b>Site Design</b></a></b> link.</li>
			<li style="margin-bottom: 16px;">To create and manage your <b>photos products</b> (prints, downloads, collections, etc...) click on the  <a href="index.php?do=photoprods"><b>Photo Products</b></a> link.</li>
			<li style="margin-bottom: 16px;">To view & manage <b>orders</b> customers have placed, click on the <a href="index.php?do=orders"><b>Orders</b></a> link.</li>

		</ul>
			<div class="center">Explore other links in the main menu to view settings, manage people, view reports, stats and more.</div>

			</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>

<?php if(!empty($_SESSION['editthemeupgrade'])) { ?>
<?php $mytheme= doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ORDER BY css_order ASC  "); ?>
<div class="pc center">To be sure any new CSS from the update is applied, edit your theme and just click Save Changes button. This will save any new CSS.</div>
<div>&nbsp;</div>
<div class="center ubuttons">
<a href="theme-edit.php?css_id=<?php print $mytheme['css_id'];?>">Edit My Theme</a>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php 
// unset($_SESSION['editthemeupgrade']);
} ?>

<?php


if($site_setup['auto_check_updates'] == "1") { 
	if(date('Y-m-d') !== $history['upgrade_check']) { 
		if($setup['sytist_hosted'] !== true) { 
			$reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 2 HOUR), '%M %e, %Y ')  AS reg_date", ""); 
		} else { 
			$sh = 1;
		}
		$updateversion = url_get_contents("https://www.picturespro.com/sytistupdateauto2.php?version=".$site_setup['sytist_version']."&reg=".$reg['reg_key']."&sh=".$sh."");
		
		if(!empty($updateversion)) { 
			$d = explode("|",$updateversion);
			$cv = explode("~",$d[0]);
			$upfile = explode("~",$d[1]);
			$upgradetype = explode("~",$d[2]);
			$upgrademessage = explode("~",$d[3]);

			$uexpire = explode("~",$d[4]);
			if(!empty($uexpire[1])) { 
				updateSQL("ms_register", "reg_update_expire='".$uexpire[1]."' ");
			}
			$updatemessage = "<div class=\"updatemessage center\">";
			$updatemessage.= "<div><b>New Upgrade Available - Version ".$cv[1]."</b></div>";
			$updatemessage.= "<div><a href=\"https://www.picturespro.com/sytist-manual/change-log/\" target=\"_blank\">Learn what's new in the change log</a> </div>";
			if($upgradetype[1] == "free") { 
				$updatemessage.= "<div><a href=\"\" onclick=\"sytistupdate(); return false;\"><b>Click here to get and apply the update</b></a>.</div>";
			}
			if($upgradetype[1] == "paid") { 
				$updatemessage.= "<div>".$upgrademessage[1]."</div>";
				$updatemessage.= '<div><a href="admin.actions.php?action=refreshupdatecheck">refresh</a> &nbsp;&nbsp;&nbsp;<a id="noshow" class="confirmdelete" confirm-title="Disable Update Check & Message?" confirm-message="You can turn the update check back on in Settings -> Admin / Main Settings." href="admin.actions.php?action=hideupdatecheck">do not show again</a></div>';
			}
			$updatemessage.= "</div>";
			print $updatemessage;
			updateSQL("ms_history", "upgrade_check=NOW(), upgrade_message='".addslashes(stripslashes($updatemessage))."' ");
		} else { 
			updateSQL("ms_history", "upgrade_check=NOW(), upgrade_message='' ");
		}

	} else{ 
		if(!empty($history['upgrade_message'])) { 
			print $history['upgrade_message'];
		}
	}
} else { 
	updateSQL("ms_history", "upgrade_check=NOW() ");
}



?>



	<?php 
	if($setup['affiliate_program'] == true) { 
	$affs = whileSQL("ms_affiliate", "*", "WHERE aff_id>'0' AND aff_status='0' AND aff_track='0'  ORDER BY aff_id ASC");
	if(mysqli_num_rows($affs) > 0) { ?>
	<div class="underlinelabel"><a href="index.php?do=affiliates&view=affiliates"><span class="h3">Pending Affiliates</span></a></div>
	<?php 
	while($aff = mysqli_fetch_array($affs)) { ?>
	<div class="underline">
	<div class="left p20"><?php print "<a href=\"index.php?do=affiliates&view=approve&aff_id=".$aff['aff_id']."\">APPROVE</a> "; ?></div>
	<div class="left p30"><?php print $aff['aff_site'];?>&nbsp;</div>
	<div class="left p30"><?php
	$p = doSQL("ms_people", "*", "WHERE p_id='".$aff['aff_person']."' ");
	print "<a href=\"index.php?do=people&p_id=".$p['p_id']."\">".$p['p_name']." ".$p['p_last_name']."</a>";
	?>
	</div>
	<div class="left p20"><?php print $aff['aff_date_created'];?>&nbsp;</div>

	<div class="clear"></div>
	</div>

	<?php } ?>

<?php } ?>
<div>&nbsp;</div>
<?php } ?>
<?php if($setup['sytistsite'] == true) {
		$and_where .= "AND date_completed='0000-00-00 00:00:00' ";
		$acdc = "ASC";
	$cw1 = "35%";
	$cw3 = "30%";
	$cw4 = "35%";

	$installs = whileSQL("installs", "*, date_format(DATE_ADD(date, INTERVAL 2 HOUR), '%M %e, %Y  %l:%i %p')  AS date, date_format(DATE_ADD(date_completed, INTERVAL 2 HOUR), '%M %e, %Y  %l:%i %p')  AS date_completed, aes_decrypt(ftp_pass, 'Tim2c0Ol') AS ftp_pass", "WHERE id>'1' $and_where  ORDER BY id $acdc LIMIT 10 " );
	if(mysqli_num_rows($installs) > 0) { ?>
	<div class="underlinelabel"><a href="index.php?do=customers&action=installs"><span class="h3">Pending Installations</span></a></div>
	<?php } 
	while($install = mysqli_fetch_array($installs)) {
?>
	<div class="underline">
		<div style="width: <?php print $cw1;?>; " class="left"><h3><?php print "<b><a href=\"index.php?do=customers&action=installs&id=".$install['id']."\">".$install['name']."</a></h3></b>"; ?>
		<?php print "<a href=\"/gts.php?url=http://".str_replace("http://", "", $install['domain'])."\" target=\"_blank\">".str_replace("http://", "", $install['domain'])."</a>"; ?>


		</div>
	<div style="width: <?php print $cw3;?>; " class="left"><?php print $install['date']; ?></div>

	<!-- <div style="width: <?php print $cw3;?>; " class="left"><?php print "<a href=\"/gts.php?url=http://".str_replace("http://", "", $install['domain'])."\" target=\"_blank\">".str_replace("http://", "", $install['domain'])."</a>"; ?></div> -->
	<div style="width: <?php print $cw4;?>; " class="left textright">
	<?php 
		if($install['product'] == "photocart") { print "Photo Cart"; } 
		if($install['product'] == "sytist") { print "Sytist"; } 
		if($install['product'] == "photocartupgrade") { print "Photo Cart Upgrade"; } 
?>
</div>
	<div class="clear"></div>
	<?php 

		if(!empty($install['admin_notes'])) {
			print "<div class=\"pageContent\" style=\"background-color: #ffffc9;\">".nl2br($install['admin_notes'])."</div>";
		}
		?>
		</div>
<?php } ?>
<?php if(mysqli_num_rows($installs) > 0) { ?><div>&nbsp;</div><?php } ?>

<?php } ?>

<?php 
if($site_setup['hide_help'] == "0") { ?>
		<div class="">

<?php 	include "new.php";  ?>
</div>
<div>&nbsp;</div>
<?php } ?>







<?php
if($_REQUEST['action'] == "removesubdomainhistory") { 
	updateSQL("ms_history", "installingon='' ");
	header("location: index.php");
	session_write_close();
	exit();
}
?>
<?php 
$total = countIt("ms_comments", "WHERE com_id>'0' AND com_approved='0' "); 
if($total > 0) { ?>
<div  <?php if($hide_comments == true) { print "style=\"display: none;\""; } ?>>
	<div class="underlinelabel"><a href="index.php?do=comments"><?php print $total;?> Pending Comments</a></div>
		<div class="clear"></div>
		<div class="underline">
	<?php pendingComments(); ?>
		</div>
	</div>
		<div>&nbsp;</div>


<?php } ?>
	<script>
	function choosehomesection() { 
		$("#selectsection").slideToggle(200);
	}
	</script>
<?php if($_REQUEST['homeaction'] == "setsection") { 
	updateSQL("ms_history", "home_recent_pages='".$_REQUEST['new_section']."' ");
	header("location: index.php");
	session_write_close();
	exit();
	}

	if($history['home_recent_pages'] > 0) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$history['home_recent_pages']."' ");
		if($cat['cat_id'] > 0) { 
			$and_cat = "AND date_cat='".$history['home_recent_pages']."' ";
		}
	}

?>
<script>
function homepageactions(id) { 
	$.get("admin.actions.php?action=homehistory&section="+id, function(data) {
		$("#"+id).slideToggle(100);
	});
}

</script>

		<?php if($setup['sytist_hosted'] == true) { showSytistSpace(); } ?>
	<?php $ps = whileSQL("ms_people", "*", "ORDER BY p_id DESC limit 5 ");
	if(mysqli_num_rows($ps) > 0) { 
	$c[1] = "50%";
	$c[2] = "50%";
	$c[3] = "25%";
	$c[4] = "20%";
		
		?>

	<?php if($hide_people !== true) { ?>
	<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homenewpeople');return false;">&#149;</span>&nbsp;&nbsp;<a href="index.php?do=people"><span class="h3">Newest People</span></a> </span></div>
	<div id="homenewpeople" class="<?php if($history['homenewpeople'] == "1") { ?>hide<?php }  ?>">
		<?php 
		while($p = mysqli_fetch_array($ps)) { 	$order = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE order_customer='".$p['p_id']."' AND order_status<'2' ");
		if($setup['demo_mode'] == true) { 
			$p['p_name'] = get_starred($p['p_name']);
			$p['p_last_name'] = get_starred($p['p_last_name']);
			$p['p_email'] = "demo@demo.mode";
		}

		?>
	<div class="underline">
		<div class="left" style="width: <?php print $c[1];?>"><div><h3>
		
<?php if(countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "WHERE fav_person='".$p['p_id']."' AND ms_photos.pic_id>'0'  ") > 0) { ?>
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>&view=favorites" title="Favorites"><span class="the-icons icon-heart" style="color: #c44444;"></span><span class="numberCircle"><span class="height_fix"></span><span class="content"><?php print countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "WHERE fav_person='".$p['p_id']."'  AND ms_photos.pic_id>'0'  ");?></span></span></a>

	<?php } ?>
		
		<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_last_name'].", ".$p['p_name'];?></a></h3></div>

		<?php $stotal = homeShoppingCartTotal(MD5($p['p_id']),0);
		if($stotal['total_items'] > 0) { ?>
		<div>Cart: <a href="" onclick="viewcustomercart('<?php print MD5($p['p_id']);?>','0'); return false;"><?php print showPrice($stotal['show_cart_total']);?></a></div>
		<?php } ?>


		</div>
		<div class="left textright" style="width: <?php print $c[2];?>"><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?> <a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?>&email_to_first_name=<?php print addslashes($p['p_name']);?>&email_to_last_name=<?php print addslashes($p['p_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a>  </div>

		<div class="clear"></div>
	</div>

	<?php } ?>
	<div>&nbsp;</div>
</div>
<?php } ?>
<?php } ?>
<?php if($hide_stats !== true) { ?>
<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homeshoppingcarts');return false;">&#149;</span>&nbsp;&nbsp;<a href="index.php?do=stats&view=carts"><span class="h3">Shopping Carts</span></a> </span></div>
<div id="homeshoppingcarts" class="<?php if($history['homeshoppingcarts'] == "1") { ?>hide<?php }  ?>">
<?php
$carts = whileSQL("ms_cart", "*,date_format(DATE_ADD(cart_date, INTERVAL 0 HOUR), '%b %e - ".$site_setup['date_time_format']." ')  AS cart_date", "WHERE  cart_order<='0' AND cart_ip!='' GROUP BY cart_session ORDER BY cart_id DESC LIMIT 10" );
if(mysqli_num_rows($carts)<=0) { ?>
	<div id="underline" style="text-align: center;">No active shopping carts</div>
<?php }
while($cart = mysqli_fetch_array($carts)) {  
	?>
<div class="underline">
	<div class="p20 left"><?php $total = homeShoppingCartTotal($cart['cart_client'],$cart['cart_session']); 
	?>
	<a href="" onclick="viewcustomercart('<?php print $cart['cart_client'];?>','<?php print $cart['cart_session'];?>'); return false;"><?php print showPrice($total['show_cart_total']);?></a></div>
	<div class="p40 left">
	<?php if(!empty($cart['cart_client'])) { 
		$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$cart['cart_client']."' ");
		if($setup['demo_mode'] == true) { 
			$p['p_name'] = get_starred($p['p_name']);
			$p['p_last_name'] = get_starred($p['p_last_name']);
			$p['p_email'] = "demo@demo.mode";
		}

		if(!empty($p['p_email'])) { 
		?><a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $p['p_email']; } ?>&email_to_first_name=<?php print addslashes($p['p_name']);?>&email_to_last_name=<?php print addslashes($p['p_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a>   
	<?php 
		}
		print "<a href=\"index.php?do=people&p_id=".$p['p_id']."\" class=\"tip\" title=\"View Account\">"; if((empty($p['p_name'])) && (empty($p['p_last_name'])) == true) { print $p['p_email']; } else { print $p['p_name']." ".$p['p_last_name']; } print "</a> &nbsp;";
	} else { 
		?>
		<a href="index.php?do=stats&action=recentVisitors&q=<?php print "".$cart['cart_ip'];?>"><?php print $cart['cart_ip'];?></a><?php if(!empty($cart['cart_email'])) { ?> <a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $cart['cart_email']; } ?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class="the-icons icon-mail"></span></a><span title="Collected to view gallery" class="tip"><?php print $cart['cart_email'];?></span><?php } ?>
		<?php } ?>
		</div>
	<div class="p40 left textright"><?php print "".$cart['cart_date']."";?></div>
	<div class="cssClear"></div>
 </div>
<?php } ?>
<div>&nbsp;</div>
</div>
<?php } ?>
<div class="underlinelabel"> <span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homenotes');return false;">&#149;</span>&nbsp;&nbsp;<span class="h3">Notes</span> </span></div>

	<div id="homenotes" class="notes <?php if($history['homenotes'] == "1") { ?>hide<?php }  ?>">
	<div  contenteditable id="admin_notes"  onClick="removeNoNotes();" name="admin_notes" style="min-height: 30px;"><?php if(empty($site_setup['admin_notes'])) { print "<span id=\"nonotes\"><i>Click here to enter notes.</i></span>"; } else { print $site_setup['admin_notes']; } ?></div>
	<div class="pc" style="height: 16px;">
		<div class="left" id="noteloading" style="display: none;"><img src="graphics/loading2.gif"></div>
		<div class="left" id="noteupdated" style="display: none;">Updated</div>
		<div class="right textright" id="updatenote"><a href="" onClick="addhomenotes(); return false;">update</a></div>
		<div class="clear"></div>
		</div>
<div>&nbsp;</div>	</div>

<script>


function stophomerefresh() { 
	clearInterval(homerefresh);
}
</script>

 <?php if($setup['unbranded'] !== true) { ?>

	<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homeforum');return false;">&#149;</span>&nbsp;&nbsp;<a href="https://www.picturespro.com/support-forum/sytist/" target="_blank"><span class="h3">Sytist Support Forum Feed</span></a> </div>
	<div id="homeforum" class="<?php if($history['homeforum'] == "1") { ?>hide<?php }  ?>">

	<?php if($history['forum_feed'] == "1") { ?>
	<div class="pc center">10 most recent posts in the Sytist forum. <a href="https://www.picturespro.com/support-forum/sytist/" target="_blank">Go to support forum</a>.</div>
	<?php $html = ""; 
	$url = "https://www.picturespro.com/sy-sytist-forum-feed.html"; 
	$xml = @simplexml_load_file($url); for($i = 0; $i < 10; $i++){ 
		$title = $xml->channel->item[$i]->title; 
		$date = $xml->channel->item[$i]->date; 
		$link = $xml->channel->item[$i]->link; 
		$description = $xml->channel->item[$i]->description; 
	$pubDate = $xml->channel->item[$i]->pubDate; 

	$html .= "<div class='underline'><a href='$link' target='_blank'>$title</a><br>"; 
	// $html .= "$description"; 
	$html .= "<span class='muted'>". date('D M d  h:i A', strtotime($pubDate)) ." CST</span>";
	$html .= "</div>";
	} 
	echo $html; ?>
	<div class="center"><a href="admin.actions.php?action=disableforum">Disable the Sytist support forum feed</a>.</div>
	<?php } else { ?>
	<div class="center">Disabled. <a href="admin.actions.php?action=enableforum">Enable the Sytist support forum feed to view recent topics</a>.</div>

	<?php } ?>
	<div>&nbsp;</div>
</div>
<?php } ?>

<div  <?php if($hide_content == true) { print "style=\"display: none;\""; } ?>>
	<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homenewpages');return false;">&#149;</span>&nbsp;&nbsp;<a href="index.php?do=news&date_cat=<?php print $cat['cat_id'];?>"><span class="h3">Recently Modified Pages <?php if($cat['cat_id'] > 0) { print "in ".$cat['cat_name']; } ?></span></a> </span>
	</div>

		<div id="homenewpages" class="<?php if($history['homenewpages'] == "1") { ?>hide<?php }  ?>">

		<?php

		$entries = whileSQL("ms_calendar", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified_show ", "WHERE  date_id>'0' $and_cat AND page_404='0'  AND page_under='0' ORDER BY last_modified DESC LIMIT 5");
		while($entry = mysqli_fetch_array($entries)) { ?>
			<div class="underline">
			<div style="float: left; width: 60%;">
			<?php 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$entry['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				print "<a href=\"index.php?do=news&action=addDate&date_id=".$entry['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"img\"  style=\"float: left; margin: 0 12px 0 0; width: ".$photo_setup['mini_size']."px; height: ".$photo_setup['mini_size']."px;\" ></a>"; 

			} else {
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$entry['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					print "<a href=\"index.php?do=news&action=addDate&date_id=".$entry['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" style=\"float: left; margin: 0 12px 0 0; width: ".$photo_setup['mini_size']."px; height: ".$photo_setup['mini_size']."px;\" class=\"img\"></a>"; 
				}
			}
					?>
			<?php if($entry['date_public'] =="2") { print "<div class=\"draft left\" style=\"margin-right: 8px;\">Draft</div>"; } ?>
			<?php 
			print "<div><h3><a href=\"index.php?do=news&action=addDate&date_id=".$entry['date_id']."\" title=\"View / Edit   ".$entry['date_title']."\">";
			if(empty($entry['date_title'])) { print "<b>[no title]</b>";} else { print "".$entry['date_title'].""; }
			print "</a></h3></div>";

					print "<div class=\"muted\">";

	if($entry['page_404']== "1") { print " [Custom 404 - page not found error page]"; } 
	if($entry['page_billboard']>0) { print ai_billboard." ";} 
	if($entry['page_theme']>0) { print ai_theme." ";} 

	if($date_type == "news") { 
		print $entry['date_show_date'];
		if($entry['date_time'] == "00:01:00") { print "&nbsp;";
		} else {
			$tm = explode(":", $entry['date_time']);
			print " @ ".date("h:i a", mktime($tm[0],$tm[1],1,1,1,1))."</nobr>";
		}
	}
		print "<br>Last modified: ".$entry['date_show_date'];
	print "</div>";

			?>
			</div>
			<div style="float: right; text-align: right; width: 40%; " class="muted">
			<?print $entry['date_show_date'];?>
			</div>
			<div class="cssClear"></div>
			</div>
			<?php 
		}
		?>
<div>&nbsp;</div>
	<div class="right textright"><a href="" onclick="choosehomesection(); return false;">Select section</a></div>
	<div class="clear"></div>
	<div style="display: none;" id="selectsection" class="right textright pc">
		<form method="post" name="homesec" id="homesec" action="index.php">
		<select name="new_section" id="new_section" onchange="this.form.submit();">
			<option value="">All Sections</option>
			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
			while($cat = mysqli_fetch_array($cats)) { ?>
			<option value="<?php print $cat['cat_id'];?>" <?php if($cat['cat_id'] == $history['home_recent_pages'])  { print "selected"; } ?>><?php print $cat['cat_name'];?></option>
			<?php } ?>
			</select> <div class="moreinfo" info-data="homerecentpages"><div class="info"></div></div>

			<input type="hidden" name="homeaction" value="setsection">
		</form>
		</div>
			<div class="clear"></div>
		<div>&nbsp;</div>

	</div>
			<div class="clear"></div>


		</div>

<div  <?php if($hide_photos == true) { print "style=\"display: none;\""; } ?>>
	<div class="underlinelabel"><span class="left homeactionbullet tip"  title="Show / hide this section" onclick="homepageactions('homenewphotos');return false;">&#149;</span>&nbsp;&nbsp;<a href="index.php?do=allPhotos&did=&bid=&pic_camera_model=&key_id=&view=&pic_upload_session=&keyWord=Search+tags&orderBy=pic_date&acdc=DESC&orientation=&pic_client=0"><span class="h3">Newest Photos</span></a> </span></div>
			<div class="clear"></div>
	<div id="homenewphotos" class="<?php if($history['homenewphotos'] == "1") { ?>hide<?php }  ?>">

			<div class="underline">
	<?php $pics = whileSQL("ms_photos", "*", "WHERE pic_no_dis='0' ORDER BY pic_id DESC limit 30 ");
		if(mysqli_num_rows($pics) <= 0) { ?><div class="pc center">No Photos Uploaded</div><?php } ?>
		<?php 

	while($pic = mysqli_fetch_array($pics)) { 
	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'].""); 
	?>
	<a href="index.php?do=allPhotos&did=&bid=&pic_camera_model=&key_id=&view=&pic_upload_session=&keyWord=Search+tags&orderBy=pic_date&acdc=DESC&orientation=&pic_client=0#image=<?php print $pic['pic_id'];?>"><img src="<?php print getimagefile($pic,'pic_mini');?>" class="mini" style="display: inline; margin: 1px; <?php print "width: ".$photo_setup['mini_size']."px; height: ".$photo_setup['mini_size']."px;"; ?>" ></a>
				<?php } ?>
				<br>

				 <a href="" onclick="openFrame('w-photos-upload.php'); return false;" ><?php print ai_arrow_up;?> Upload Photos</a>
			</div>
			<div>&nbsp;</div>
			</div>
	</div>

	</div>
</div>
<?php

if(date('Y-m-d') !== $history['upgrade_check']) { 
	if((date('d') == "01") || (date('d') == "15") == true) { 
		// sytistreg(false,false);
	}
}
?>
		</div>
		</div>
		</div>



<div class="clear"></div>
<div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>













<div>&nbsp;</div>
</div>





<?php function pendingComments() { 
	global $site_setup;

	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 20;
	$NPvars = array("do=comments", "comq=".$_REQUEST['comq']."","status=".$_REQUEST['status']." " );
	$sq_page = $pg * $per_page - $per_page;	
	?>


	<div id="roundedSide">

	
		<?php
		$datas = whileSQL("ms_comments", "*,date_format(DATE_ADD(com_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." @ ".$site_setup['date_time_format']."')  AS com_date", "WHERE com_id>'0' AND com_approved='0' ORDER BY com_id  $ACDC  LIMIT $sq_page,$per_page  ");
		while ($data = mysqli_fetch_array($datas)) {
			$rownum++;
			?>


	<div class="roundedSideSep">
		<div style="width:10%;" class="cssCell">
		<?php  if($data['com_approved']=="0") { print "<a href=\"index.php?do=comments&status=".$_REQUEST['status']."&approve=".$data['com_id']."\">".ai_message_approve."</a>"; } 
			if($data['com_approved']=="1") { print "<a href=\"index.php?do=comments&status=".$_REQUEST['status']."&trash=".$data['com_id']."\">".ai_message_delete."</a> "; }
			 if($data['com_approved']=="2") { print "<a href=\"index.php?do=comments&status=".$_REQUEST['status']."&approve=".$data['com_id']."\">".ai_message_approve."</a>"; }
			?>
			</div>
			<div style="width:55%; float: left;">
			<span class="bold"><?php print $data['com_name']; ?> (<?php print $data['com_email']; ?>)</span>
			<div>Comment on: <a href="<?php print $data['com_link'];?>" target="_blank"><?php print $data['com_title'];?></a></div>
			</div>
			
			<div style="width:35%; float: right; text-align: right;"><?php print $data['com_date']; ?></div>
			<div class="cssClear"></div>
			<div class="pc"><?php if(!empty($data['com_comment'])) { ?>"<i><?php print nl2br($data['com_comment']);?></i>"<?php } ?></div>
			</div>

		


			<?php } ?>
			<div class="cssClear"></div>
</div>
<div>&nbsp;</div>
<?php   } ?>
<?php unset($_SESSION['query']); ?>
