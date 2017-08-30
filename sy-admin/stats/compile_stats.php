<?php
	$month  = date("m - Y", mktime(0, 0, 0, date("m") -1  , 0, date("Y")));
	$month_name  = date("F", mktime(0, 0, 0, date("m") -1  , 0, date("Y")));

	$lmonth = 1;
			$mexists = "yes";
	while($mexists == "yes") {
		$month  = date("m", mktime(0, 0, 0, date("m") -$lmonth  ,0, date("Y")));
		$year  = date("Y", mktime(0, 0, 0, date("m") -$lmonth  ,0, date("Y")));
		$month_name  = date("F", mktime(0, 0, 0, date("m") -$lmonth  , 0, date("Y")));

		$se = countIt("ms_stats_site_visitors", "WHERE MONTH(st_date) = $month AND YEAR(st_date) = $year ");
		if($se > 0) {
			$comp = doSQL("ms_stats_compiled", "*", "WHERE comp_month='$month' AND comp_year='$year' ");
			if(empty($comp['comp_id'])) {

				$pvs = countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date) = $month AND YEAR(pv_date) = $year");
				$insert = insertSQL("ms_stats_compiled", "comp_year='$year', comp_month='$month', comp_visitors='$se', comp_pvs='$pvs'");
				$sql = "DELETE FROM ms_stats_site_visitors WHERE MONTH(st_date)='$month' AND YEAR(st_date)='$year'  ";
				if(mysqli_query($dbcon,$sql)) { } else { echo("Error  > " . mysqli_error($dbcon) . " < that error"); }
				$sql2 = "DELETE FROM ms_stats_site_pv WHERE MONTH(pv_date)='$month' AND YEAR(pv_date)='$year'  ";
				if(mysqli_query($dbcon,$sql2)) { } else { echo("Error  > " . mysqli_error($dbcon) . " < that error"); }
			}

		print "<li>$month / $year = $month_name";
		print " <b>$se | $pvs </b>";

			$mexists = "yes";
		} else {
			$mexists = "no";
		}
		unset($se);
		$lmonth++;

	}
		print "<br><br><B></center>Previous months stats have been compiled.<br><br><a href=\"index.php?do=stats\">Click here to continue</a></center></B>";




?>
