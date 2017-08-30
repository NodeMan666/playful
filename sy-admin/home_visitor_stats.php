<!-- Start home visitor stats -->

<div class="pc"><h3>Visitors</h3></div>
<div id="homeTodayStats">
		<div id="roundedSide">

	<?php
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
	$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));
	$photoviews = countIt("ms_photo_stats", "WHERE pv_date='$date'");
?>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Visitors</div><div style="width:50%;" class="cssCell"><?php print $visitors;?>
				</div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Page Views</div><div style="width:50%;" class="cssCell"><?php print $pv;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">New Visitors</div><div style="width:50%;" class="cssCell"><?php print $nvisitors;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Returning Visitors</div><div style="width:50%;" class="cssCell"><?php print $rvisitors;?></div>
				<div class="cssClear"></div>
			</div>

		</div>
</div>




<div id="homeYesterdayStats" style="display: none;">
		<div id="roundedSide">

	<?php
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$visitors =countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));
	// $comments = countIt("ms_comments", "WHERE com_date='$date'");
	 $photoviews= countIt("ms_photo_stats", "WHERE pv_date='$date'");
?>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Visitors</div><div style="width:50%;" class="cssCell"><?php print $visitors;?>
				</div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Page Views</div><div style="width:50%;" class="cssCell"><?php print $pv;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">New Visitors</div><div style="width:50%;" class="cssCell"><?php print $nvisitors;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Returning Visitors</div><div style="width:50%;" class="cssCell"><?php print $rvisitors;?></div>
				<div class="cssClear"></div>
			</div>
		</div>
</div>


<div id="homeMonthStats" style="display: none;">

		<div id="roundedSide">

	<?php
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$visitors =countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'  ");
	$pv = countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date)='".date('m')."' AND YEAR(pv_date)='".date('Y')."'   ");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'   AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='".date('m')."' AND YEAR(st_date)='".date('Y')."'   AND st_last_visit ='0000-00-00'"));
	$photoviews = countIt("ms_photo_stats", "WHERE  MONTH(pv_date)='".date('m')."' AND YEAR(pv_date)='".date('Y')."'  ");
?>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Visitors</div><div style="width:50%;" class="cssCell"><?php print $visitors;?>
				</div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Page Views</div><div style="width:50%;" class="cssCell"><?php print $pv;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">New Visitors</div><div style="width:50%;" class="cssCell"><?php print $nvisitors;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Returning Visitors</div><div style="width:50%;" class="cssCell"><?php print $rvisitors;?></div>
				<div class="cssClear"></div>
			</div>

		</div>
	
	</div>



<div id="homeLastMonthStats" style="display: none;">

		<div id="roundedSide">

	<?php
	$lmm  = date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
	$lmy  = date("Y", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
	$visitors =countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='$lmm' AND YEAR(st_date)='$lmy'  ");
	$pv = countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date)='$lmm' AND YEAR(pv_date)='$lmy'  ");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='$lmm' AND YEAR(st_date)='$lmy'   AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date)='$lmm' AND YEAR(st_date)='$lmy'  AND st_last_visit ='0000-00-00'"));
	$photoviews = countIt("ms_photo_stats", "WHERE  MONTH(pv_date)='$lmm' AND YEAR(pv_date)='$lmy'");
?>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Visitors</div><div style="width:50%;" class="cssCell"><?php print $visitors;?>
				</div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Page Views</div><div style="width:50%;" class="cssCell"><?php print $pv;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">New Visitors</div><div style="width:50%;" class="cssCell"><?php print $nvisitors;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Returning Visitors</div><div style="width:50%;" class="cssCell"><?php print $rvisitors;?></div>
				<div class="cssClear"></div>
			</div>

		</div>	
</div>

<div id="homeYearStats" style="display: none;">
		<div id="roundedSide">

	<?php
	$visitors =countIt("ms_stats_site_visitors", "WHERE   YEAR(st_date)='". date("Y")."'  ");
	$pv = countIt("ms_stats_site_pv", "WHERE  YEAR(pv_date)='". date("Y")."'  ");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  YEAR(st_date)='". date("Y")."'   AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE  YEAR(st_date)='". date("Y")."'  AND st_last_visit ='0000-00-00'"));
	$photoviews = countIt("ms_photo_stats", "WHERE  MONTH(pv_date)='".date('m')."' AND YEAR(pv_date)='".date('Y')."'  ");
?>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Visitors</div><div style="width:50%;" class="cssCell"><?php print $visitors;?>
				</div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Page Views</div><div style="width:50%;" class="cssCell"><?php print $pv;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">New Visitors</div><div style="width:50%;" class="cssCell"><?php print $nvisitors;?></div>
				<div class="cssClear"></div>
			</div>
			<div class="roundedSideSep">
				<div style="width:50%;" class="cssCell">Returning Visitors</div><div style="width:50%;" class="cssCell"><?php print $rvisitors;?></div>
				<div class="cssClear"></div>
			</div>

		</div>
</div>


<div class=cssClear></div>
<div class="pageContent">
	<div id="homeTodayStatsLink"  style="display: none; float: left;"><a href="#" onclick="openHomeStats('homeTodayStats','homeYesterdayStats|homeMonthStats|homeYearStats|homeLastMonthStats'); return false;"> Today</a>&nbsp;&nbsp;</div>
	<div id="homeTodayStatsSelected"  style="display: block; float: left;"><b>Today&nbsp; &nbsp;</b> </div>
	<div id="homeYesterdayStatsLink"  style="display: block; float: left;"><a href="#" onclick="openHomeStats('homeYesterdayStats','homeTodayStats|homeMonthStats|homeMonthStats|homeYearStats'); return false;">Yesterday</a>&nbsp;&nbsp; </div>
	<div id="homeYesterdayStatsSelected"  style="display: none; float: left;"><b>Yesterday</b>&nbsp;&nbsp; </div>
	<div id="homeMonthStatsLink"  style="display: block; float: left;"><a href="#" onclick="openHomeStats('homeMonthStats','homeTodayStats|homeYesterdayStats|homeLastMonthStats|homeYearStats'); return false;">This Month</a >&nbsp;&nbsp;</div>
	<div id="homeMonthStatsSelected"  style="display: none; float: left;"><b>This Month </b>&nbsp;&nbsp; </div>

<!--	<div id="homeLastMonthStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeLastMonthStats','homeTodayStats|homeYesterdayStats|homeMonthStats|homeYearStats'); return false;">Last Month</a></div>
	<div id="homeLastMonthStatsSelected" class=homeStatsLinks style="display: none;"> <b>Last Month</b></div>

	<div id="homeYearStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeYearStats','homeTodayStats|homeYesterdayStats|homeMonthStats|homeLastMonthStats'); return false;">This Year</a></div>
	<div id="homeYearStatsSelected" class=homeStatsLinks style="display: none;"> <b>ThisYear</b></div>
-->
	<div style="text-align: right; float: right;"> <a href="index.php?do=stats">go to stats</a></div>
<div class="cssClear"></div></div>
<div class="cssClear"></div>
