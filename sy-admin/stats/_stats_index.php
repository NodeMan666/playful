<div id="pageTitle"><a href="index.php?do=stats">Stats</a> <?php print ai_sep;?> <a href="index.php?do=stats">Visitors</a>
</div>
<div style="float: right; padding: 4px;"><form method="get" name="searchc" action="index.php" style="margin: 0; padding: 0;">
<input type="hidden" name="do" value="stats">
<input type="hidden" name="action" value="recentVisitors">
<input type="text" name="q" size="15" value="<?php print $_REQUEST['q'];?>"> <button type="submit">Search</button>
</form>
</div>
<div class="buttonsgray">
	<ul>

<li><a href="index.php?do=stats" class="<?php  if(empty($_REQUEST['action'])) { ?>on <?php } ?>">OVERVIEW</a></li>
<li><a href="index.php?do=stats&action=recentVisitors" class="<?php  if($_REQUEST['action']=="recentVisitors") { ?>on<?php } ?>">RECENT VISITORS</a></li>
<li><a href="index.php?do=stats&action=refReport" class="<?php  if($_REQUEST['action']=="refReport") { ?>on<?php } ?>">REFERRERS</a></li>
<li><a href="index.php?do=stats&action=thirtyDays" class="<?php  if($_REQUEST['action']=="thirtyDays") { ?>on<?php } ?>">LAST 30 DAYS</a></li>
<li><a href="index.php?do=stats&action=pages" class="<?php  if($_REQUEST['action']=="pages") { ?>on<?php } ?>">PAGES</a></li>
<li><a href="index.php?do=stats&action=browserInfo" class="<?php  if($_REQUEST['action']=="browserInfo") { ?>on<?php } ?>">BROWSERS</a></li>
<li><a href="index.php?do=stats&action=bots" class="<?php  if($_REQUEST['action']=="bots") { ?>on<?php } ?>">BOTS</a></li>
<li><a href="index.php?do=stats&action=recentPages" class="<?php  if($_REQUEST['action']=="recentPages") { ?>on<?php } ?>">RECENTLY VIEWED PAGES</a></li>	
	<div class="cssClear"></div>
	</ul>
</div>

<div id="Contain">

<?php

	if(empty($_REQUEST['action'])) {
		$cmonth  = date("m", mktime(0, 0, 0, date("m") -1  ,0, date("Y")));
		$cyear  = date("Y", mktime(0, 0, 0, date("m") -1  ,0, date("Y")));
		$comp = doSQL("ms_stats_compiled", "*", "WHERE comp_month='$cmonth' AND comp_year='$cyear' ");
		$se = countIt("ms_stats_site_visitors", "WHERE MONTH(st_date) = $cmonth AND YEAR(st_date) = $cyear ");
		if((empty($comp['comp_id']))&&($se > 0)==true) {
			header("location: onemomentplease.php?goto=index.php?do=stats&action=compileStats");
		}
	}
//print "<br>";
//print "<a href=\"index.php?do=stats&function=sRef\">x</a>";
//print "<br>";

if($_REQUEST['function'] == "summary28") {
	include "_last_28.php";

} elseif($_REQUEST['action'] == "deleteip") { 
		$stats = whileSQL("ms_stats_site_visitors", "*", "WHERE st_ip='".$_REQUEST['ip']."' ");
		while($stat = mysqli_fetch_array($stats)) { 
			$x++; 
			deleteSQL2("ms_stats_site_pv", "WHERE pv_ref_id='".$stat['st_id']."' ");
			deleteSQL2("ms_stats_site_visitors", "WHERE st_id='".$stat['st_id']."' ");
		}
	$_SESSION['sm'] = "IP address deleted from stats ($x entries)";
	session_write_close();
	header("location: index.php?do=stats");
	exit();


	} elseif($_REQUEST['action'] == "recentVisitors") {
		include "recent_visitors.php";
	} elseif($_REQUEST['action'] == "refReport") {
		include "ref_report.php";
	} elseif($_REQUEST['action'] == "browserInfo") {
		include "browser_info.php";
	} elseif($_REQUEST['action'] == "bots") {
		include "stats.bots.php";
	} elseif($_REQUEST['action'] == "thirtyDays") {
		include "thirty_days.php";
	} elseif($_REQUEST['action'] == "compileStats") {
		include "compile_stats.php";
	} elseif($_REQUEST['action'] == "listEngines") {
		include "engine_info.php";
		listEngines($_REQUEST);
	} elseif($_REQUEST['action'] == "editEngine") {
		include "engine_info.php";
		editEngine($_REQUEST);
	} elseif($_REQUEST['action'] == "channels") {
		include "channels.php";
	} elseif($_REQUEST['action'] == "hourReport") {
		include "hourly_report.php";
	} elseif($_REQUEST['action'] == "engines") {
		include "search_engines.php";
	} elseif($_REQUEST['action'] == "pages") {
		include "popular_pages.php";
	} elseif($_REQUEST['action'] == "recentPages") {
		include "recent_pages.php";
	} elseif($_REQUEST['action'] == "visitordetails") {
		include "visitor_details.php";
		} else {
		homeStats($setup);
	}
?>
</div>


<?php 

function mainLeft($setup) {

?>
<div id="">
	<div class="underline"><div style="width:40%; float: left;" >&nbsp;</div><div style="width:30%; float: left;"><span  class="bold">Visitors</span></div><div style="width:30%; float: left;"><span  class="bold">Page Views</span></div><div class="cssClear"></div>
 </div>
		<?php
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
	$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));

?>
<div class="underline">
	<div style="width:40%;" class="cssCell">Today</div><div style="width:30%;" class="cssCell"><?php print "<span class=\"bold\">".$visitors."</span> (".$nvisitors."/".$rvisitors.")";?></div><div style="width:30%;" class="cssCell"><?php print "<span class=\"bold\">".$pv."</span>";?></div>
	<div class="cssClear"></div>
 </div>
<?php 
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	$visitors =countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date='$date' AND st_last_visit ='0000-00-00'"));
?>
<div class="underline">
	<div style="width:40%;" class="cssCell">Yesterday</div><div style="width:30%;" class="cssCell"><?php print "<span class=\"bold\">".$visitors."</span> (".$nvisitors."/".$rvisitors.")";?></div><div style="width:30%;" class="cssCell"><?php print "<span class=\"bold\">".$pv."</span>";?></div><div class="cssClear"></div>
 </div>

<?php
	$w_visitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date BETWEEN CURDATE() - INTERVAL 7 DAY AND (CURDATE() -1) "));
	$w_pv = number_format(countIt("ms_stats_site_pv", "WHERE pv_date BETWEEN CURDATE() - INTERVAL 7 DAY AND (CURDATE() -1) "));
	$wrvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date BETWEEN CURDATE() - INTERVAL 7 DAY AND (CURDATE() -1)  AND st_last_visit > 0"));
	$wnvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE st_date BETWEEN CURDATE() - INTERVAL 7 DAY AND (CURDATE() -1)  AND st_last_visit ='0000-00-00'"));
?>
<div class="underline">
	<div style="width:40%;" class="cssCell">Week</div><div style="width:30%;" class="cssCell"><?php print $w_visitors." (".$wnvisitors."/".$wrvisitors.")";?></div><div style="width:30%;" class="cssCell"><?php print "".$w_pv;?></div><div class="cssClear"></div>
 </div>


<?php
	$month  = date("m", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
	$mtd_visitors = countIt("ms_stats_site_visitors", "WHERE  MONTH(st_date) = $month");
	$mtd_pv = countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date) = $month ");
	$rvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE MONTH(st_date) = $month AND st_last_visit > 0"));
	$nvisitors = number_format(countIt("ms_stats_site_visitors", "WHERE MONTH(st_date) = $month AND st_last_visit ='0000-00-00'"));
?>
<div class="underline">
	<div style="width:40%;" class="cssCell">MTD</div><div style="width:30%;" class="cssCell"><?php print $mtd_visitors." (".$nvisitors."/".$rvisitors.")";?></div><div style="width:30%;" class="cssCell"><?php print $mtd_pv;?></div><div class="cssClear"></div>
 </div>




<?php
	$lm   = date("m", mktime(0, 0, 0, date('m') - 1, 10, 0));
	$lm_name   = date("F", mktime(0, 0, 0, $lm, 10, 0));
	$lm_visitors = number_format(countIt("ms_stats_site_visitors", "WHERE MONTH(st_date) = '$lm' "));
	$lm_pv = number_format(countIt("ms_stats_site_pv", "WHERE  MONTH(pv_date) = '$lm'"));
?>
<div class="underline">
	<div style="width:40%;" class="cssCell"><?php print $lm_name;?></div><div style="width:30%;" class="cssCell"><?php print $lm_visitors;?></div><div style="width:30%;" class="cssCell"><?php print $lm_pv;?></div><div class="cssClear"></div>
 </div>

<?php

	$pms = whileSQL("ms_stats_compiled", "*", "ORDER BY comp_year DESC, comp_month DESC");
	while ( $pm = mysqli_fetch_array($pms) ) {
	?>
	<div class="underline">
	<div style="width:40%;" class="cssCell"><?php print $pm['comp_month']."/".$pm['comp_year'];?></div><div style="width:30%;" class="cssCell"><?php print "".number_format($pm['comp_visitors']);?></div><div style="width:30%;" class="cssCell"><?php print $pm['comp_pvs'];?></div><div class="cssClear"></div>
 </div>


<?php		}
?>

<div class="cssClear"></div>


</div>

<div class="pageContent"><i>Visitors (NEW / RETURNING)</i></div>
<?php
}


function homeStats($setup) {
	global $site_setup,$dbcon;

		print "<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td width=100%>";
		print "<table align=center cellpadding=0 cellspacing=0 border=0 width=100%><tr valign=top><td width=50%>";
		mainLeft($setup);
		?>



<div style="clear: both;">&nbsp;</div>


</td><td width=50% style="padding-left: 25px; ">


<div id="">
<div  class="underlinelabel">Top 10 referrers for today</div>

		<?php
		
		$result = @mysqli_query($dbcon,"SELECT  st_refer, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_date=CURDATE() GROUP BY st_refer ORDER BY dups DESC LIMIT 10");
		if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
		$pagetotal = mysqli_num_rows($result);
		if($pagetotal<=0) { 
			print "<div class=\"underline\">No data</div>";
		}

		while ( $row = mysqli_fetch_array($result) ) {
			$st_refer = $row["st_refer"];
			$dups = $row["dups"];
			$totalpv = $totalpv + $dups;

		if(strlen($st_refer) > 60) {
			$st_refer_show = substr_replace($st_refer, " ....", 60);
		} else {
			$st_refer_show = $st_refer;
		}

?>	
<div class="underline">
<div style="width:10%;" class="cssCell"><?php print $dups;?></div><div style="width:90%;" class="cssCell">
	<?php
			if(empty($st_refer)) {
			print "Direct Hit";
		} else {
			print "<a href=\"http://"."$st_refer\" target=\"_Blank\" title=\"$st_refer\">$st_refer_show</a>";
		}
?>
</div>
		<div class="cssClear"></div>
	</div>
<?php 	} ?>
</div>

<div>&nbsp;</div>
<div id="">
<div  class="underlinelabel">Top 10 pages viewed today</div>

<?php
$result = @mysqli_query($dbcon,"SELECT  *, COUNT(*) AS dups FROM ms_stats_site_pv  WHERE pv_date=CURDATE() GROUP BY page_viewed ORDER BY dups DESC LIMIT 10");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
		if($pagetotal<=0) { 
			print "<div class=\"underline\">No data</div>";
		}

while ( $row = mysqli_fetch_array($result) ) {

	if(!empty($row['page_viewed'])) { 
?>	
<div class="underline">
<div style="width:10%;" class="cssCell"><?php print $row["dups"];?></div><div style="width:90%;" class="cssCell">

<?php
			showVisPage($row);
?></div>
		<div class="cssClear"></div>
	</div>
<?php 	}
} ?>



</div>
<div>&nbsp;</div>

<div class="underlinelabel">Last 10 Visitors - <a href="index.php?do=stats&action=recentVisitors">all recent visitors</a></div>
<div class="cssClear"></div>
<div id="">

<?php
// print "<li>".$site_setup['time_diff'];
		$viss = whileSQL("ms_stats_site_visitors", "*, date_format(DATE_ADD(st_date, INTERVAL 0 HOUR), '%m/%e')  AS st_date,  time_format(st_time, '%h:%i %p')  AS st_time", " $and_mem ORDER BY st_id DESC LIMIT 10");
		while ( $vis = mysqli_fetch_array($viss) ) {

	?>




<div class="underline">
	<div style="width:30%;" class="cssCell"><a href="index.php?do=stats&action=visitordetails&pv_ref_id=<?php print $vis['st_id'];?>"><?php  print $vis['st_ip']; ?></a></div><div style="width:10%;" class="cssCell"><?php print countIt("ms_stats_site_pv", "WHERE pv_ref_id='".$vis['st_id']."' ");?></div><div style="width:30%;" class="cssCell"><?php print $vis['st_date']." @ ".$vis['st_time'];?></div><div style="width:30%;" class="cssCell"><?php 		if(empty($vis['st_refer'])) {
			print "Direct Hit";
		} else {
/*
			$d1 = $info[1]; 
			$info2 = explode('/', $d1); 
			$d2 = $info2[0]; 
			$rd = str_replace("www.","",$d2);
*/
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
			print "<a href=\"http://".$vis['st_refer']."\" target=\"_Blank\" class=smr title=\"".$vis['st_refer']."\">".$show_this."</a>";
		}
?></div><div class="cssClear"></div>
 </div>
<?php } ?>

</div></div>
</td></tr></table></td></tr></table>
</div>
<div class="clear"></div>
</div><div class="clear"></div>
<?php }  ?>



<?php 
//
function timeDiff($setup, $req) {

	if($_REQUEST['display'] == "y") {

		$vist = whileSQL("ms_stats_site_visitors", "*", "WHERE st_date BETWEEN CURDATE() - INTERVAL 14 DAY AND (CURDATE() -1)  ORDER BY st_id DESC");
		$vtotal = mysqli_num_rows($vist);

		while($vis = mysqli_fetch_array($vist)) {
	//		print "<li>".$vis['st_date']." ".$vis['st_time']."";

			list($hour,$min,$sec) = split(':',$vis['st_time']); 
			list($year,$month,$day) = split('-',$vis['st_date']); 

			$first = mktime($hour,$min,$sec,$month,$day,$year);
	//		print  "{ $first } ";



			$pv = doSql("ms_stats_site_pv", "*", "WHERE pv_ref_id='".$vis['st_id']."' ORDER BY pv_id DESC LIMIT 1 ");
	//		print " ( ".$pv	['pv_time']." ) ";
			list($hour,$min,$sec) = split(':',$pv['pv_time']); 
			list($year,$month,$day) = split('-',$pv['pv_date']); 
			$last = mktime($hour,$min,$sec,$month,$day,$year);
	//		print  "{ $last } ";
			$td = $last - $first;
	//		print "<b>secs: $td || Min: ".round(($td) / 60, 2) ."</b>";
			if($td <= 0) { $timetotal['30']++; }
			if($td > 0 && $td <= 120) { $timetotal['120']++; }
			if($td > 120 && $td <= 240) { $timetotal['240']++; }
			if($td > 120 && $td <= 300) { $timetotal['300']++; }
			if($td > 300 && $td <= 600) { $timetotal['600']++; }
			if($td > 600 && $td <= 1200) { $timetotal['1200']++; }
			if($td > 1200 && $td <= 1800) { $timetotal['1800']++; }
			if($td > 1800) { $timetotal['2000']++; }

			$total_secs = $total_secs + ($last - $first);
		}

		print "<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td width=40%>";
		print "<b><a class=head>Visitor Duration</a></b></td><td width=60% align=right><a class=head>For the past 14 days</a></td></tr></table>";

		print "<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td width=100%>";
		print "<b>";
		$over2 = 100 - round((($timetotal['30'] + $timetotal['120'])  / $vtotal) * 100, 2);
		print "$over2"."% of visitors stay on site for 2 minutes or more.";
		print "</b></td></tr></table>";
		print "<br><br>";
		print "<table cellpadding=4 cellspacing=0  border=1 bordercolor=#999999 align=center>";
		print "<tr><td bgcolor=#cccccc><b>One page view</td><td bgcolor=#cccccc><b>0-2 Minutes</td><td bgcolor=#cccccc><b>2-5 Minutes</td><td bgcolor=#cccccc><b>5-10 Minutes</td><td bgcolor=#cccccc><b>10-20 Minutes</td><td bgcolor=#cccccc><b>20-30 Minutes</td><td bgcolor=#cccccc><b>30+ Minutes</td></tr>";
		print "<tr>";
		print "<td>".round(($timetotal['30'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['120'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['300'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['600'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['1200'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['1800'] / $vtotal) * 100, 2)."%";
		print "</td><td>".round(($timetotal['2000'] / $vtotal) * 100, 2)."%";
		print "</td></tr></table>";
	} else {

		header("location: /onemomentplease.php?goto=/index.php?do=stats&function=timeDiff&display=y");

	}





}




?>

