<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>

<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}
adminsessionCheck();
$no_trim = true;
?>
<script> 
$(document).ready(function(){
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});

	$(".monthdiv").click(function() { 
		window.location="index.php?do=orders&action=reports&date_from="+$(this).attr("date_from")+"&date_to="+$(this).attr("date_to")+"";
	});
});
</script>
<style>
.grandtotal { font-size: 40px; color: #008900; font-weight: bold; } 
.sublabels { font-size: 17px; color: #999999; }
.subtotals {  font-size: 24px; color: #000000; }
.monthname { font-size: 17px; color: #777777; text-align: center;} 
.monthtotal { font-size: 24px; color: #000000; text-align: center;} 
.monthdiv { background: #f4f4f4; border: solid 1px #c4c4c4; padding: 12px; } 
.monthdiv:hover { background: #444444; border: solid 1px #242424; padding: 12px; cursor: pointer; } 
</style>

<?php
if(!empty($_REQUEST['date_from'])) { 
	$f = explode("-",$_REQUEST['date_from']);
	$t = explode("-",$_REQUEST['date_to']);
	$date_where = "order_date>='".$_REQUEST['date_from']." 00:00:00' AND order_date<='".$_REQUEST['date_to']." 23:59:59' ";

	$from = date('l F d, Y',mktime(0,0,0,$f[1],$f[2],$f[0]));
	$to = date('l F d, Y',mktime(0,0,0,$t[1],$t[2],$t[0]));
	$show_from =$from." - ".$to;
} else { 
	$date_where = "MONTH(order_date)='".date('m')."' AND YEAR(order_date)='".date('Y')."'";
	$show_from = "This Month";
}
print "<h1>".$show_from."</h1>";
?>
<div class="left p50">
	<div class="pc">
	<div>Total Sales</div>
	<div class="grandtotal">
	<?php
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
		print "  ".showPrice($stotal['tot'])." ";
	?>
	</div>


	</div>

</div>
<div class="right p50 center">
	<div class="center">
	<form method="get" name="roer" action="index.php">
		<input type="hidden" name="do" value="orders">
		<input type="hidden" name="action" value="reports">
		<div class="left center pc">
			<?php 
			if(!empty($_REQUEST['date_from'])) { 
				$date_from = $_REQUEST['date_from'];
			} else { 
				$date_from = date('Y-m-d');
			}
			if(!empty($_REQUEST['date_to'])) { 
				$date_to = $_REQUEST['date_to'];
			} else { 
				$date_to = date('Y-m-d');
			}

			?>
			<div>Date From</div>

			<div><input type="text" name="date_from" id="date_from" class="datepicker center" value="<?php print $date_from;?>" size="12"></div>
		</div>
		
		
		<div class="left center pc ">
			<div>Date To</div>
			<div><input type="text" name="date_to" id="date_to" class="datepicker center" value="<?php print $date_to; ?>" size="12"></div>
		</div>
		<div class="left center pc">
			<div>&nbsp;</div>
			<div><input type="submit" name="submit" id="submit"value="Go" class="submitSmall"></div>
		</div>
		<div class="clear"></div>

	</form>

	</div>

</div>


<div class="clear"></div>
<div>&nbsp;</div>
<?php if(countIt("ms_countries", "WHERE vat>'0' ") > 0) { 
	$vat = true;
	$colclass = "p15";
} else { 
	$colclass = "p20";
}
?>

	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Orders</div>
		<div class="subtotals">
		<?php 
				print countIt("ms_orders", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
	?>
		</div>
	</div>


	<div class="left center <?php print $colclass;?> ">
		<div class="sublabels">Tax</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_tax) AS tax", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['tax'])." ";
	?>
		</div>
	</div>
	<?php if($vat == true) { ?>
	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">VAT</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_vat) AS vat", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['vat'])." ";
	?>
		</div>
	</div>
	<?php } ?>
	
	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Shipping</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_shipping) AS ship", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['ship'])." ";
	?>
		</div>
	</div>

	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Credit Used</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_credit) AS credit", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['credit'])." ";
	?>
		</div>
	</div>

	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Discounts</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_discount) AS discount", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['discount'])." ";
	?>
		</div>
	</div>
	<div class="clear"></div>

<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

<?php 
$months = 12;
$year = date('Y');
$month = 1;
?>

<?php 
if(empty($_REQUEST['year'])) { 
	$year = date('Y');
} else {
	$year = $_REQUEST['year'];
}
$ytotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE YEAR(order_date)='".$year."' AND order_status<'2'  AND order_payment_status='Completed'"); 

?>
	
<div class="pc center"><h2>
<a href="index.php?do=orders&action=reports&year=<?php print $year - 1;?>">&larr;</a> <?php print $year." <span style=\"color: #008900;\">".showPrice($ytotal['tot']);?></span>
<?php if($year < date('Y')) { ?><a href="index.php?do=orders&action=reports&year=<?php print $year + 1;?>">&rarr;</a><?php } ?>
</h2></div>
<?php 
while($month <= 12) { 
	$month = sprintf("%02s", $month);
	$num = cal_days_in_month(CAL_GREGORIAN, $month, $year); 
	?>
	<div style="width: 25%; float: left;">
		<div style="padding: 12px;">
			<div class="monthdiv" id="<?php print $month.$year;?>" date_from="<?php print $year."-".$month;?>-01" date_to="<?php print $year."-".$month;?>-<?php print $num;?>">
			<?php 
			$thismonth = date('F',mktime(0,0,0,$month,1,$year));
			?>
			<div class="monthname"><?php print $thismonth;?></div>
			<div class="monthtotal">
			<?php 
			$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_date)='".$month."' AND YEAR(order_date)='".$year."' AND order_status<'2'  AND order_payment_status='Completed'"); 
			print " ".showPrice($stotal['tot'])."";
			?>
			</div>
			</div>
		</div>
	</div>

	<?php 
		$month++;
}
?>




