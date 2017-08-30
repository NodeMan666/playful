<?php

	if(!empty($_REQUEST['date'])) {
		$whendo = "pv_date='".$_REQUEST['date']."'";
		$when_show = "".$_REQUEST['date']."";
	} else {

	$today = date("Y-m-d");
	$yesterday = "CURDATE() - INTERVAL 1 DAY";
	$mdays = date(d);

	if($_REQUEST['when'] == "yesterday") {
		$when = date("Y-m-d", time()-24*3600);
		$whendo = "pv_date = CURDATE() - INTERVAL 1 DAY";
		$when_show = "Yesterday";
	}

	if(($_REQUEST['when'] == NULL) OR ($_REQUEST['when'] == "today")) {
		$when = date("Y-m-d");
		$whendo = "pv_date='$when'";
		$when_show = "Today";
	}
	if($_REQUEST['when'] == "week") {
		$whendo = "pv_date BETWEEN $yesterday - INTERVAL 7 DAY AND $yesterday ";
		$when = "Past Seven Days";
		$when_show = "Last 7 days";
	}
	if($_REQUEST['when'] == "mtd") {
		$whendo = "pv_date BETWEEN CURDATE() - INTERVAL $mdays DAY AND CURDATE() ";
		$when = "Month to date";
		$when_show = "Month to date";
	}


	if(empty($when_show)) {
		$when_show ="Today";
	}

	}




/*
	$engines = whileSQL("ms_stats_engines", "*", "");
	while($engine = mysqli_fetch_array($engines)) {
		if($addand >= 1) {
			$nl .= " AND st_refer NOT LIKE '%".$engine['engine_check']."%' ";
		} else {
			$nl .= " st_refer NOT LIKE '%".$engine['engine_check']."%' ";
			$addand++;
		}
	}
*/

	print "<div class=\"pageContent\"><h2>Report for $when_show</h2></div>";

	print "<div class=\"pageContent\">";
	print "<a href=\"index.php?do=stats&action=pages&when=today\">Today</a>";
	print " | <a href=\"index.php?do=stats&action=pages&when=yesterday\">Yesterday</a>";
	print " | <a href=\"index.php?do=stats&action=pages&when=week\">Past Week</a>";
	print " | <a href=\"index.php?do=stats&action=pages&when=mtd\">Month to Date</a>";
	print "</div>";
?>
	
		<div id="">

<?php
$result = @mysqli_query($dbcon,"SELECT  page_viewed, date_id, COUNT(*) AS dups FROM ms_stats_site_pv  WHERE $whendo GROUP BY page_viewed ORDER BY dups DESC");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
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

