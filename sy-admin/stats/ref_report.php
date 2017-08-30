<?php

	if(!empty($_REQUEST['date'])) {
		$whendo = "st_date='".$_REQUEST['date']."'";
		$when_show = "".$_REQUEST['date']."";
	} else {

	$today = date("Y-m-d");
	$yesterday = "CURDATE() - INTERVAL 1 DAY";
	$mdays = date(d);

	if($_REQUEST['when'] == "yesterday") {
		$when = date("Y-m-d", time()-24*3600);
		$whendo = "st_date = CURDATE() - INTERVAL 1 DAY";
		$when_show = "Yesterday";
	}

	if(($_REQUEST['when'] == NULL) OR ($_REQUEST['when'] == "today")) {
		$when = date("Y-m-d");
		$whendo = "st_date='$when'";
		$when_show = "Today";
	}
	if($_REQUEST['when'] == "week") {
		$whendo = "st_date BETWEEN $yesterday - INTERVAL 7 DAY AND $yesterday ";
		$when = "Past Seven Days";
		$when_show = "Last 7 days";
	}
	if($_REQUEST['when'] == "mtd") {
		$whendo = "st_date BETWEEN CURDATE() - INTERVAL $mdays DAY AND CURDATE() ";
		$when = "Month to date";
		$when_show = "Month to date";
	}


	if(empty($when_show)) {
		$when_show ="Today";
	}

	}





	$engines = whileSQL("ms_stats_engines", "*", "");
	while($engine = mysqli_fetch_array($engines)) {
		if($addand >= 1) {
			$nl .= " AND st_refer NOT LIKE '%".$engine['engine_check']."%' ";
		} else {
			$nl .= " st_refer NOT LIKE '%".$engine['engine_check']."%' ";
			$addand++;
		}
	}

	print "<div class=\"pageContent\"><h2>Report for $when_show</h2></div>";

	print "<div class=\"pageContent\">";
	print "<a href=\"index.php?do=stats&action=refReport&when=today\">Today</a>";
	print " | <a href=\"index.php?do=stats&action=refReport&when=yesterday\">Yesterday</a>";
	print " | <a href=\"index.php?do=stats&action=refReport&when=week\">Past Week</a>";
	print " | <a href=\"index.php?do=stats&action=refReport&when=mtd\">Month to Date</a>";
	print "</div>";
	?>
	
	<div style="width: 49%; float: left;">
	<?php 
	$result = @mysqli_query($dbcon,"SELECT  st_refer, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE $whendo AND $nl  GROUP BY st_refer ORDER BY dups DESC");
	if (!$result) {		echo( "Error perforing query" . mysqli_error($result) . "that error");		exit();	}
	$pt = @mysqli_query($dbcon,"SELECT  * FROM ms_stats_site_visitors WHERE $whendo AND $nl  ");
	if (!$pt) {		echo( "Error perforing query" . mysqli_error($pt) . "that error");		exit();	}
	$pagetotal = mysqli_num_rows($pt);
?>
<div id="roundedSide">
	<div class="label">Webites / Emails / Direct Hits: total <?php print $pagetotal;?></div>

<?php
	while ( $row = mysqli_fetch_array($result) ) {
		$st_refer = $row["st_refer"];
		$dups = $row["dups"];

	if(strlen($st_refer) > 60) {
		$st_refer_show = substr_replace($st_refer, " ....", 56);
	} else {
		$st_refer_show = $st_refer;
	}
	?>
	<div class="roundedSideSep"><div style="width:10%;" class="cssCell"><?php print $dups;?></div><div style="width:90%;" class="cssCell">
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
</div>


	<div style="width: 49%; float: right;">

<?php 
// *********** DO SEARCH ENGINES *(*********
	$engines = whileSQL("ms_stats_engines", "*", " ORDER BY engine_id ASC");
	while($engine = mysqli_fetch_array($engines)) {

	//	foreach($sv AS $line => $var) {
		$sterms = array();
		$check = $engine['engine_check'];
		$result_msn = @mysqli_query($dbcon,"SELECT  *  FROM ms_stats_site_visitors WHERE $whendo AND (st_refer LIKE '%$check%') ");
		if (!$result_msn) {	echo( "Error perforing query" . mysqli_error($result_msn) . "that error");	exit(); }
		$pagetotal = mysqli_num_rows($result_msn);
		if($pagetotal > 0) { ?>
			<?php 
			$query_str = $engine['engine_query_str'];

			while ( $row = mysqli_fetch_array($result_msn) ) {
				if($engine['engine_check'] == "google") { 
					if(strpos($row["st_refer"],'imgres') === false) {
			//		print "<li>".$row["st_refer"];
				$this_count++;

					$st_refer = $row["st_refer"];
					$qr = strstr($st_refer, "?");
					$qr = str_replace("?", "&", "$qr");
					$qr = parse_str($qr);
					if(!empty($$query_str)) { 
		//				print "<li>".$$query_str;
						$sterms[stripslashes(trim($$query_str))]++;
					} else { 
						$img_query_str = "imgurl";

						$st_refer = $row["st_refer"];
						$qr = strstr($st_refer, "?");
						$qr = str_replace("?", "&", "$qr");
						$qr = parse_str($qr);
						//print "<li>IMG ".$st_refer;
						$sterms[stripslashes(trim("unknown"))]++;

					}
					}
			} else { 
					$this_count++;

					$st_refer = $row["st_refer"];
					$qr = strstr($st_refer, "?");
					$qr = str_replace("?", "&", "$qr");
					$qr = parse_str($qr);
					$sterms[stripslashes(trim($$query_str))]++;


			}
			unset($$query_str);
				}
				arsort($sterms, SORT_NUMERIC); 
				?>
	<div id="roundedForm">
		<div class="label"><?php print $engine['engine_name']." : total $this_count";?></div>
		<?php
				foreach($sterms AS $term => $ct) {
					if((!empty($term)) AND ($term !=='')==true) {
					?>
			<div class="row"><div style="width:10%;" class="cssCell"><?php print $ct;?></div><div style="width:90%;" class="cssCell"><?php if($term == "unknown") { print "Unknown URL or Image redirect"; } else { print "<a href=\"".$engine['engine_search_url']."$term\" target=\"_Blank\">$term </a>"; } ?></div>
				<div class="cssClear"></div>
			</div>

				<?php 
					}
				}
			print "</div>";
			print "<div>&nbsp;</div>";

			}
			unset($this_count);
			unset($sterms);
	}

?>
</div>
<div class="cssClear">&nbsp;</div>
