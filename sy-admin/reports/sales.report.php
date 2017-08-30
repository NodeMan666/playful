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
		window.location="index.php?do=reports&action=reports&date_from="+$(this).attr("date_from")+"&date_to="+$(this).attr("date_to")+"&year="+$(this).attr("year")+"&month="+$(this).attr("month")+"";
	});
	$(".caldaydiv").click(function() { 
		window.location="index.php?do=reports&action=reports&date_from="+$(this).attr("this_date")+"&date_to="+$(this).attr("this_date")+"&year="+$(this).attr("this_year")+"&month="+$(this).attr("this_month");
	});

});
</script>
<style>
.grandtotal { font-size: 40px; color: #008900; font-weight: bold; } 
.expgrandtotal { font-size: 40px; color: #890000; font-weight: bold; } 
.sublabels { font-size: 17px; color: #999999; }
.subtotals {  font-size: 24px; color: #000000; }
.monthname { font-size: 17px; color: #777777; text-align: center;} 
.monthtotal { font-size: 24px; color: #000000; text-align: center;} 
.exptotal { font-size: 17px; color: #890000; text-align: center;} 
.monthdiv { background: #f4f4f4; border: solid 1px #c4c4c4; padding: 12px; } 
.monthdiv:hover { background: #444444; border: solid 1px #242424; padding: 12px; cursor: pointer; } 
</style>

<?php
if(!empty($_REQUEST['date_from'])) { 
	$f = explode("-",$_REQUEST['date_from']);
	$t = explode("-",$_REQUEST['date_to']);
	$date_where = "order_payment_date>='".$_REQUEST['date_from']." 00:00:00' AND order_payment_date<='".$_REQUEST['date_to']." 23:59:59' ";
	$sp_date_where = "payment_date>='".$_REQUEST['date_from']." 00:00:00' AND payment_date<='".$_REQUEST['date_to']." 23:59:59' ";
	$exp_date_where = "exp_date>='".$_REQUEST['date_from']."' AND exp_date<='".$_REQUEST['date_to']."' ";

	$from = date('l F d, Y',mktime(0,0,0,$f[1],$f[2],$f[0]));
	$to = date('l F d, Y',mktime(0,0,0,$t[1],$t[2],$t[0]));
	$show_from =$from." - ".$to;
} elseif(!empty($_REQUEST['year'])) { 
	$date_where = " YEAR(order_payment_date)='".$_REQUEST['year']."'";
	$sp_date_where = " YEAR(payment_date)='".$_REQUEST['year']."'";
	$exp_date_where = "YEAR(exp_date)='".$_REQUEST['year']."'";
	$show_from = $_REQUEST['year'];
} else { 
	$date_where = "MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."'";
	$sp_date_where = "MONTH(payment_date)='".date('m')."' AND YEAR(payment_date)='".date('Y')."'";
	$exp_date_where = "MONTH(exp_date)='".date('m')."' AND YEAR(exp_date)='".date('Y')."'";
	$show_from = "This Month";
	$_REQUEST['date_from'] = date('Y-m')."-01";
	$_REQUEST['date_to'] = date('Y-m-t');
}
print "<h1>".$show_from."</h1>";
?>
<div class="left p30">
	<div class="pc">
	<div>Total Sales</div>
	<div class="grandtotal">
	<?php
		$gtotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE $date_where  AND order_payment_status='Completed' "); 
		$rtotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE $date_where  AND order_status='2' AND order_payment_status='Completed' "); 
		$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 

		// Need to get payment schedule payments 
		$sptotal = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE $sp_date_where  "); 
		print " ".showPrice($stotal['tot']+$sptotal['tot'])." ";
		// print " ".showPrice($sptotal['tot'])." ";
	?>
	</div>
	<?php if($rtotal['tot'] > 0) { ?>
	<div><?php print showPrice($gtotal['tot']); ?> - <?php print showPrice($rtotal['tot']);?></div>
	<?php } ?>

	</div>

</div>

<div class="left p40 center">
	<div class="center">
	<form method="get" name="roer" action="index.php" class="center">
		<input type="hidden" name="do" value="reports">
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
<div class="left p30 textright">
	<div class="pc">
	<div>Total Expenses</div>
	<div class="expgrandtotal">
	<?php
		$exptotal = doSQL("ms_expenses", "SUM(exp_amount) AS tot", "WHERE $exp_date_where  "); 
		print "  ".showPrice($exptotal['tot'])."";
	?>
	</div>


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

$total_orders = countIt("ms_orders", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
$total_schedule_payment = countIt("ms_payment_schedule", "WHERE $sp_date_where  "); 
?>

	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Orders</div>
		<div class="subtotals">
		<a href="index.php?do=orders&date_from=<?php print $_REQUEST['date_from'];?>&date_to=<?php print $_REQUEST['date_to'];?>&year=<?php print $_REQUEST['year'];?>"><?php print $total_orders + $total_schedule_payment;?></a>
		</div>
	</div>

	<?php
	$taxabletotal = doSQL("ms_orders", "SUM(order_taxable_amount) AS tax", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
	?>
	<div class="left center <?php print $colclass;?> ">
		<div class="sublabels">Tax</div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_tax) AS tax", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['tax'])." ";
	?>
		</div>
		<div>on <?php 	print " ".showPrice($taxabletotal['tax'])." "; ?></div>
	</div>
	<?php if($vat == true) { ?>
	<div class="left center <?php print $colclass;?>">
		<div class="sublabels"><?php print _vat_;?></div>
		<div class="subtotals">
		<?php 
				$stotal = doSQL("ms_orders", "SUM(order_vat) AS vat", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
			print " ".showPrice($stotal['vat'])." ";
	?>
		</div>
		<div>on <?php 	print " ".showPrice($taxabletotal['tax'])." "; ?></div>
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
	<?php 	$stotal = doSQL("ms_orders", "SUM(order_credit) AS credit", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
	if($stotal['credit'] > 0) { ?>

	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Credit Used</div>
		<div class="subtotals">
		<?php print " ".showPrice($stotal['credit'])." "; ?>
		</div>
	</div>
	<?php } ?>
	<?php $stotal = doSQL("ms_orders", "SUM(order_gift_certificate) AS credit", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
	if($stotal['credit'] > 0) { ?>
	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">eGift Card Used</div>
		<div class="subtotals">
		<?php 
			print " ".showPrice($stotal['credit'])." ";
	?>
		</div>
	</div>
	<?php } ?>
	<?php $stotal = doSQL("ms_orders", "SUM(order_discount) AS discount", "WHERE $date_where AND order_status<'2' AND order_payment_status='Completed' "); 
	if($stotal['discount'] > 0) { ?>
	<div class="left center <?php print $colclass;?>">
		<div class="sublabels">Discounts</div>
		<div class="subtotals">
		<?php 
				
			print " ".showPrice($stotal['discount'])." ";
	?>
		</div>
	</div>
	<?php } ?>
	<div class="clear"></div>

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
$ytotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE YEAR(order_payment_date)='".$year."' AND order_status<'2'  AND order_payment_status='Completed'"); 
$sp_ytotal = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE YEAR(payment_date)='".$year."' "); 
$yexptotal = doSQL("ms_expenses", "SUM(exp_amount) AS tot", "WHERE YEAR(exp_date)='".$year."'   "); 

?>
<script>
function showexport() { 
	$("#exportreport").slideToggle();
}
</script>
<div class="pc center">
<a href="" onclick="showexport(); return false;" class="" style="font-weight: bold; font-size: 17px;">Export orders on  this report</a>
</div>
<div id="exportreport" class="center underline hidden">
<div class="pc">
<form method="post" name="export" id="export" action="export-reports.php">
Name <input type="text" name="reportname" id="reportname" size="30" value="<?php print $show_from;?>"> Separate with <input type="text" name="sep" id="sep" value="," size="2" class="center"> save as file <input type="text" name="filename" id="filename" value="csv" size="4" class="center"> 
<input type="submit" name="submit" value="Export" class="submit">
<input type="hidden" name="date_from" value="<?php print $_REQUEST['date_from'];?>">
<input type="hidden" name="date_to" value="<?php print $_REQUEST['date_to'];?>">

</form>
</div>
<div class="pc"><a href="index.php?do=orders&action=exportsettings">Click here to adjust what fields are exported</a></div>
</div>


<div style="border-bottom: dashed 1px #b4b4b4;">&nbsp;</div>
<div>&nbsp;</div>



	
<div class="pc center">
<div class="left p20"><h3><a href="index.php?do=reports&action=reports&year=<?php print $year - 1;?>">&larr; <?php print $year - 1;?></a></h3></div>

<div class="left p60"><h2><span style="color: #008900;"><?php print showPrice($ytotal['tot'] + $sp_ytotal['tot']);?></span> &nbsp; &nbsp; &nbsp; <a href="index.php?do=reports&year=<?php print $year;?>"><?php print $year;?></a> &nbsp; &nbsp; &nbsp; <span style="color: #890000;"><?php print showPrice($yexptotal['tot']);?></span></h2></div>

<div class="left p20"><?php if($year < date('Y')) { ?><h3><a href="index.php?do=reports&action=reports&year=<?php print $year + 1;?>"><?php print $year + 1;?> &rarr;</a></h3><?php } ?></div>
<div class="clear"></div>
</div>



<?php if((!empty($_REQUEST['month']))&&(!empty($_REQUEST['year']))==true) { 
	$cmonth = $_REQUEST['month'];
	$cyear = $_REQUEST['year'];

	$num_of_days = date("t", mktime(0,0,0,$_REQUEST['month'],1,$cyear)); 
	$firstdayname[$cmonth] = date("D", mktime(0, 0, 0, $_REQUEST['month'], 1, $cyear)); 
	$cmonth_name[$cmonth] = date("F", mktime(0, 0, 0, $_REQUEST['month'], 1, $cyear)); 
	$firstday[$cmonth] = date("w", mktime(0, 0, 0, $_REQUEST['month'], 1, $cyear)); 
	$lastday = date("t", mktime(0, 0, 0, $_REQUEST['month'], 1, $cyear)); 
	?>



	<style>
		#calHeader { display: block; width: 100%; } 
	.calendarcontainer { padding: 8px; } 
	.caldaycontainer { width: 14.25%; float: left; padding: 2px; } 
	.callabel { background: #F4F4F4; } 
	.calday { background: #F9F9F9; padding: 8px; height: 120px; border-top: solid 1px #FFFFFF;  border-left: solid 1px #FFFFFF;  border-right: solid 1px #dfdfdf;  border-bottom: solid 1px #dfdfdf;} 
	.caldaytoday { background: #F4EED9; padding: 8px;height: 120px;  border-top: solid 1px #FFFFFF;  border-left: solid 1px #FFFFFF;  border-right: solid 1px #EBE2C3;  border-bottom: solid 1px #EBE2C3; } 
	.calsales { color:  #008900; font-weight: bold;  margin-top: 8px;  font-size: 17px;  } 
	.calDateNumber { width:20%; float: left; font-weight: bold; padding: 4px;   border: solid 1px #d4d4d4; background: #FFFFFF; text-align: center; } 

	.calday:hover, .caldaytoday:hover { background: #444444;  cursor: pointer; } 
	.calexpense {color: #890000; text-align: center;} 
	</style>
	<div class="calendarcontainer">
	<div>&nbsp;</div>
	<div class="pc center"><h2><?php print $cmonth_name[$cmonth]." ". $year; ?></h2></div>
	<div id="calHeader">
		<div class="caldaycontainer"><div class="callabel center">Sun</div></div>
		<div class="caldaycontainer"><div class="callabel center">Mon</div></div>
		<div class="caldaycontainer"><div class="callabel center">Tues</div></div>
		<div class="caldaycontainer"><div class="callabel center">Wed</div></div>
		<div class="caldaycontainer"><div class="callabel center">Thurs</div></div>
		<div class="caldaycontainer"><div class="callabel center">Fri</div></div>
		<div class="caldaycontainer"><div class="callabel center">Sat</div></div>
		<div class="cssClear"></div>
	</div>


	<?php 
	$xc[$cmonth] = 1;
	$less_days[$cmonth] = $firstday[$cmonth];
	$x[$cmonth] = $firstday[$cmonth] + 1;
	if($less_days[$cmonth] > 7) {
		$less_days[$cmonth] = $less_days[$cmonth] - 8;
	}
	if($less_days[$cmonth] == 7) { $less_days[$cmonth] = 1; }
	while ($less_days[$cmonth] > 0) {
		print "<div class=\"caldaycontainer\">&nbsp;</div>";
		$less_days[$cmonth] = $less_days[$cmonth] - 1;
	}
	while($xc[$cmonth] <= $num_of_days) {
		?><div class="caldaycontainer caldaydiv" this_date="<?php print $cyear."-".$cmonth."-".$xc[$cmonth];?>" this_month="<?php print $cmonth;?>" this_year="<?php print $cyear;?>">
		<?php 
		if(($xc[$cmonth] == date('d')) AND ($cmonth == date('n')) AND ($cyear == date('Y'))==TRUE) { ?>
		<div class="caldaytoday"> <?php } else { ?>
		<div class="calday"> <?php } ?>

		<div class="calDateNumber"><?php print $xc[$cmonth]; ?></div>
		<div class="cssClear"></div>
		<?php 
			if($xc[$cmonth] <=9) { 
				$thisDay = "0".$xc[$cmonth];
			} else {
				$thisDay = $xc[$cmonth];
			}
		$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE DATE_FORMAT(order_payment_date, '%Y-%m-%d')='".$cyear."-".$cmonth."-".$thisDay."' AND order_status<'2' "); 
		$sp_stotal = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE DATE_FORMAT(payment_date, '%Y-%m-%d')='".$cyear."-".$cmonth."-".$thisDay."' "); 
		$stot = $stotal['tot'] + $sp_stotal['tot'];
		?>
		<div class="center calsales">
		<?php 	if($stot > 0) { print showPrice($stot); } else { print "&nbsp;"; }  ?>
		</div>

			<div class="calexpense">
			<?php 
			$stotal = doSQL("ms_expenses", "SUM(exp_amount) AS tot", "WHERE exp_date='".$cyear."-".$cmonth."-".$thisDay."' "); 
			if($stotal['tot'] > 0) { print " (".showPrice($stotal['tot']).")"; } else { print "&nbsp;"; } ?>
			</div>

		<?php 
		if ($x[$cmonth]%7)  {
		echo "</div></div>";
		}  else  {

		echo "</div></div><div class=\"cssClear\"></div>";
		}

		$xc[$cmonth]++;
		$x[$cmonth]++;

	}
?>
</div>
<div class="clear"></div>
<div>&nbsp;</div>
<?php } ?>
<?php 
while($month <= 12) { 
	$month = sprintf("%02s", $month);
	$num = cal_days_in_month(CAL_GREGORIAN, $month, $year); 
	?>
	<div style="width: 25%; float: left;">
		<div style="padding: 12px;">
			<div class="monthdiv" id="<?php print $month.$year;?>" date_from="<?php print $year."-".$month;?>-01" date_to="<?php print $year."-".$month;?>-<?php print $num;?>" year="<?php print $year;?>" month="<?php print $month;?>">
			<?php 
			$thismonth = date('F',mktime(0,0,0,$month,1,$year));
			?>
			<div class="monthname"><?php print $thismonth;?></div>
			<div class="monthtotal">
			<?php 
			$stotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".$month."' AND YEAR(order_payment_date)='".$year."' AND order_status<'2'  AND order_payment_status='Completed'"); 
			$sp_stotal = doSQL("ms_payment_schedule", "SUM(payment) AS tot", "WHERE MONTH(payment_date)='".$month."' AND YEAR(payment_date)='".$year."' "); 
			$gtotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".$month."' AND YEAR(order_payment_date)='".$year."'  AND order_payment_status='Completed'"); 
			$rtotal = doSQL("ms_orders", "SUM(order_payment) AS tot", "WHERE MONTH(order_payment_date)='".$month."' AND YEAR(order_payment_date)='".$year."' AND order_status='2'  AND order_payment_status='Completed'"); 
			print " ".showPrice($stotal['tot']+$sp_stotal['tot'])."";
			?>
			</div>
			<div class="pc center"><?php if($rtotal['tot'] > 0) { ?>
			<?php print showPrice($gtotal['tot']);?> - <?php print showPrice($rtotal['tot']);?><?php } else { print "&nbsp;"; } ?></div>

			<div class="exptotal">
			<?php 
			$stotal = doSQL("ms_expenses", "SUM(exp_amount) AS tot", "WHERE MONTH(exp_date)='".$month."' AND YEAR(exp_date)='".$year."' "); 
			if($stotal['tot'] > 0) { print " (".showPrice($stotal['tot']).")"; } else { print "&nbsp;"; } ?>
			</div>

			</div>
		</div>
	</div>

	<?php 
		$month++;
}
?>