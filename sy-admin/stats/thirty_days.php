		<style>
		.visitors { background: #3A73AB; color: #FFFFFF; }
		.pageviews { background: #8DB1D4; color: #FFFFFF; } 
		</style>
		<div>
		<span class="pc visitors">Visitors</span>
		<span class="pc pageviews">Page Views</span>
		</div>

<?php

		$cd = -1;


		while($cd >= -30) {
			$tomorrow_show  = date("M-d-Y", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));
			$tomorrow_sql  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));

			$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$tomorrow_sql'  ");
			$pvs = countIt("ms_stats_site_pv", "WHERE pv_date='$tomorrow_sql' ");

			$vis[$tomorrow_show] = $visitors;
			if($visitors > $highest_v) { $highest_v = $visitors; $highest_vis_day = $tomorrow_show; }
			$pv[$tomorrow_show] = $pvs;
			if($pvs > $highest) { $highest = $pvs; $highest_pv_day = $tomorrow_show; }
			$total['vis'] = $total['vis'] + $visitors;
			$total['pv'] = $total['pv'] + $pvs;

			$cd = $cd - 1;
		}

		$cd = -1;

	print "<table cellpadding=1 cellspacing=0 border=0 align=center width=100%><tr valign=bottom>";

	while($cd >= -30) {
			$tomorrow_show  = date("M-d-Y", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));
			$tomorrow_sql  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));


		$pv_perc = @round(($pv[$tomorrow_show] / $highest) * 100, 0);
		$pv_height = $pv_perc * 2;
		$v_perc = @round(($vis[$tomorrow_show] / $highest) * 100, 0);
		$v_height = $v_perc * 2;
//   
if($cd == -1) { 		print "<td>$tomorrow_show</td>"; }

		print "<td>";
		
		?>
		<div style="">
		<?php 
		print "<div class=\"pageviews tip\" style=\"height:".$pv_height."px;  width:12px; bottom: 0; \"  title=\"$tomorrow_show  ".$pv[$tomorrow_show]." Page Views\">&nbsp;</div>";
		print "<div class=\"visitors tip\" style=\"height:".$v_height."px;  width:12px;  bottom: 0; \" title=\"$tomorrow_show  ".$vis[$tomorrow_show]." Visitors\">&nbsp;</div>";
		?>
		</div>
		<?php 

		
		print "</td>";
		if($cd == -30) { 		print "<td>$tomorrow_show</td>"; }
		$cd = $cd - 1;
	}

	print "</tr></table>";

	?>

	<div style="background: #444444; color: #FFFFFF; font-weight: bold; padding: 8px;">
		<div class="left center p20">Total Visitors</div>
		<div class="left center p20">Total Page Views</div>
		<div class="left center p20">Avg. Visitors / Day</div>
		<div class="left center p20">Avg. Page Views / Day</div>
		<div class="left center p20">Avg. PV / Visitor</div>
		<div class="clear"></div>
	</div>

	<div style="background: #646464; color: #FFFFFF; font-weight: normal; padding: 8px;">
		<div class="left center p20"><?php print $total['vis'];?></div>
		<div class="left center p20"><?php print $total['pv'];?></div>
		<div class="left center p20"><?php print  round($total['vis'] / 30, 2); ?></div>
		<div class="left center p20"><?php print round($total['pv'] / 30, 2);?></div>
		<div class="left center p20"><?php print @round($total['pv'] / $total['vis'], 2);?></div>
		<div class="clear"></div>
	</div>
	<?php $cd = -1; ?>


	<div>&nbsp;</div>
	<div class="pc"><h3>Days of the week</h3></div>
	<div class="pc">This compiles the days over the last 28 days.</div>
	<div class="underlinelabel">
		<div class="left p15">Day</div>
		<div class="left p10">Day</div>
		<div class="left p10">Day</div>
		<div class="clear"></div>
	</div>
	<?php 
	$dd = 1;
	while($dd <= 7) {
		$dow  = date("l", mktime(0, 0, 0, 0 , $dd + 4, 0));

		$tvisitors = countIt("ms_stats_site_visitors", "WHERE  st_date BETWEEN CURDATE() - INTERVAL 28 DAY AND CURDATE() - INTERVAL 1 DAY ");
		$tpvs = countIt("ms_stats_site_pv", "WHERE  pv_date BETWEEN CURDATE() - INTERVAL 28 DAY AND CURDATE() - INTERVAL 1 DAY ");
		
		$visitors = countIt("ms_stats_site_visitors", "WHERE DAYOFWEEK(st_date) = $dd  AND st_date BETWEEN CURDATE() - INTERVAL 28 DAY AND CURDATE() - INTERVAL 1 DAY ");
		$pvs = countIt("ms_stats_site_pv", "WHERE DAYOFWEEK(pv_date) = $dd  AND pv_date BETWEEN CURDATE() - INTERVAL 28 DAY AND CURDATE() - INTERVAL 1 DAY ");
		?>
		<div class="underline">
			<div class="left p15"><?php print $dow;?></div>
			<div class="left p10"><?php print $visitors;?></div>
			<div class="left p10"><?php print $pvs;?></div>
			<div class="left p65">
		<?php 
		if($tvisitors > 0) { 
			$vperc = round(($visitors / $tvisitors) * 100, 2);
		}
		if($tpvs > 0) { 
			$pperc = round(($pvs / $tpvs) * 100, 2);
		}

		?>
		<div style="width: <?php print $vperc;?>%;" class="visitors tip" title="Visitors">&nbsp;</div>
		<div style="width: <?php print $pperc;?>%;" class="pageviews tip" title="Page Views">&nbsp;</div>

		<?php $dd++; ?>
		</div>
		<div class="clear"></div>
	</div>
<?php 
	}
		?>
	<div>&nbsp;</div>
	<div class="pc"><h3>Break down of last 30 days</h3></div>
		<div class="underlinelabel">
			<div class="left p20">Date</div>
			<div class="left p20">Visitors</div>
			<div class="left p20">Page Views</div>
			<div class="left p20">Referrers</div>
			<div class="left p20">Pages</div>
			<div class="clear"></div>
		</div>
		<?php 
		while($cd >= -30) {
			$tomorrow_show  = date("M-d-Y", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));
			$tomorrow_sql  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+$cd, date("Y")));

			?>
			<div class="underline">
				<div class="left p20"><?php print $tomorrow_show;?></div>
				<div class="left p20"><span <?php if($tomorrow_show == $highest_vis_day) { print "style=\"background: #FFFF00; padding: 4px;\""; } ?>><?php print $vis[$tomorrow_show];?></span></div>
				<div class="left p20"><span <?php if($tomorrow_show == $highest_pv_day)  { print "style=\"background: #FFFF00; padding: 4px;\""; } ?>><?php print $pv[$tomorrow_show];?></span></div>
				<div class="left p20"><?php print "<a href=\"index.php?do=stats&action=refReport&date=$tomorrow_sql\">Referrers</a>";;?></div>
				<div class="left p20"><?php print "<a href=\"index.php?do=stats&action=pages&date=$tomorrow_sql\">Pages</a>" ?></div>
				<div class="clear"></div>
			</div>
			<?php 
		$cd = $cd - 1;
	}

?>