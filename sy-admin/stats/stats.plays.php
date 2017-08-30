<div class=pagetitle><a href="index.php?do=stats" class=pagetitle>Stats</a> > Song Plays
<?php
if(!empty($_REQUEST['fanq'])) {
	$and_where .= "AND ( fan_email LIKE '%".addslashes($_REQUEST['fanq'])."%'  OR   fan_first_name LIKE '%".addslashes($_REQUEST['fanq'])."%' OR  fan_last_name LIKE '%".addslashes($_REQUEST['fanq'])."%' ) ";
	print " > search for ".$_REQUEST['fanq']."";
}


?>
</div>
<div class="cssClear">&nbsp;</div>
<div style="width: 38%; float: left;">

<div ><b>Most Played</b></div> 
<div class=padTopBottom>
	<div id="homeTodayStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeTodayStats','homeYesterdayStats|homeMonthStats|homeYearStats|homeLastMonthStats|homeAllTimeStats'); return false;"> Today</a></div>
	<div id="homeTodayStatsSelected" class=homeStatsLinks style="display: none;"> <b>Today</b></div>

	<div id="homeYesterdayStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeYesterdayStats','homeTodayStats|homeMonthStats|homeMonthStats|homeYearStats|homeAllTimeStats'); return false;">Yesterday</a></div>
	<div id="homeYesterdayStatsSelected" class=homeStatsLinks style="display: none;"> <b>Yesterday</b></div>

	<div id="homeMonthStatsLink" class=homeStatsLinks style="display: none;"> <a href="#" onclick="openHomeStats('homeMonthStats','homeTodayStats|homeYesterdayStats|homeLastMonthStats|homeYearStats|homeAllTimeStats'); return false;">MTD</a></div>
	<div id="homeMonthStatsSelected" class=homeStatsLinks style="display: block;"> <b>MTD</b></div>

	<div id="homeLastMonthStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeLastMonthStats','homeTodayStats|homeYesterdayStats|homeMonthStats|homeYearStats|homeAllTimeStats'); return false;">Last Month</a></div>
	<div id="homeLastMonthStatsSelected" class=homeStatsLinks style="display: none;"> <b>Last Month</b></div>

	<div id="homeYearStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeYearStats','homeTodayStats|homeYesterdayStats|homeMonthStats|homeLastMonthStats|homeAllTimeStats'); return false;">Year</a></div>
	<div id="homeYearStatsSelected" class=homeStatsLinks style="display: none;"> <b>Year</b></div>

	<div id="homeAllTimeStatsLink" class=homeStatsLinks style="display: block;"> <a href="#" onclick="openHomeStats('homeAllTimeStats','homeTodayStats|homeYesterdayStats|homeMonthStats|homeLastMonthStats|homeYearStats'); return false;">All Time</a></div>
	<div id="homeAllTimeStatsSelected" class=homeStatsLinks style="display: none;"> <b>All Time</b></div>


</div>



<div id="roundeddata"> <div id="tlcorner"><div id="trcorner"><div id="blcorner"><div id="brcorner">
	<div id="menucontain">

		<div id="homeTodayStats" style="display: none;">

	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "WHERE DATE_FORMAT(play_date, '%Y-%m-%d')='".date('Y-m-d')."' GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for today</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>


		<div id="homeYesterdayStats" style="display: none;">
	<?php
	$ydate  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	?>

	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "WHERE DATE_FORMAT(play_date, '%Y-%m-%d')='$ydate'  GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for yesterday</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>

		<div id="homeMonthStats" style="display: block;">
	<?php
	$ydate  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
	?>

	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "WHERE MONTH(play_date)='".date('m')."' AND YEAR(play_date)='".date('Y')."'  GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for this month</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>

		<div id="homeLastMonthStats" style="display: none;">
<?php
$lmm  = date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
$lmy  = date("Y", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y")));
?>
	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "WHERE MONTH(play_date)='".$lmm."' AND YEAR(play_date)='".$lmy."'  GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for last month</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>


		<div id="homeYearStats" style="display: none;">

	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "WHERE  YEAR(play_date)='".date('Y')."' GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for this year</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>


		<div id="homeAllTimeStats" style="display: none;">

	<?php 
	$tops = whileSQL("ms_music_plays","*, COUNT(*) AS dups", "GROUP BY play_track ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for all time</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['play_track']."' ");
		?>
		<div id="roundeddataitem">
			<div style="width:70%;" class="cssCell"><?php if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title']; } ?>&nbsp;</div><div style="width:30%;" class="cssCell"><?php print "".$top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php } ?>
		</div>


		</div>
	</div></div></div></div></div>

</div>
<div style="width: 60%; float: right;">
<div class=padTopBottom><span class=pageTitle>Recent Plays</span></div>



<?php
$playtotal = countIt("ms_music_plays", "WHERE play_id>'0' $and_where "); 
if($playtotal <= 0) { ?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div id="cssMainContainer">
		<div  class=cssRowContainer style="text-align:center;">No data found</div>
	</div>
<?php
} else  {
?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 20;
	$NPvars = array("do=stats", "view=plays");
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "3%";
	$cw2 = "3%";
	$cw3 = "3%";
	$cw4 = "40%";
	$cw5 = "30%";
	$cw6 = "30%";
	$cw7 = "28%";
	?>
	<form method="post" name="listForm" id="listForm" action="index.php" style="margin:0px;padding:0px;">
	<div id="cssMainContainer"> 
	<div id="cssRowLabelContainer" >
			<div class="cssRowLabel"  style="width: <?php print $cw4;?>;"><B>Song</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw5;?>;"><B>Date</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw6;?>;"><B>IP Address</B></div>
			<div  class="cssClear"></div>
		</div>

		<div class="cssRowMain">
		<?php
		$plays = whileSQL("ms_music_plays", "*,date_format(DATE_ADD(play_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS play_date", "WHERE play_id>'0' $and_where ORDER BY play_id DESC LIMIT $sq_page,$per_page  ");
		while ($play = mysqli_fetch_array($plays)) {
			$fannum++;
			?>
			<div id="row-<?php print $fannum;?>"  class="cssRowContainer" onmouseover="this.className='cssRowContainerHover';" onmouseout="this.className='cssRowContainer';">
				<div style="width: <?php print $cw4;?>;" class="cssCell"><?php 
				$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$play['play_track']."' ");
				 if(empty($track['music_id'])) { print "<i>deleted track</i>"; } else { print "".$track['music_title'].""; }?>
				&nbsp;</div>
				<div style="width: <?php print $cw5;?>;" class="cssCell"><?php print $play['play_date']; ?></div>
				<div style="width: <?php print $cw6;?>;" class="cssCell"><?php print $play['play_ip']; ?>&nbsp;</div>
				<div class="cssClear"></div>
			</div>
			<?php } ?>
		</div>
	<!-- 
		<?php 	if($fantotal > 1) { ?>

		<div>
		<input type="checkbox" onclick="checkAll(document.getElementById('listForm'), 'toselect');">Select all
		</div>
		<div>
		<input type="hidden" name="do" value="products">
		<input type="hidden" name="action" value="batchEdit">
		<input type="submit" name="submit" value="Batch edit selected">
		</div>
		<?php } ?>
		-->
		</div></form>
	<?php 
	if($playtotal > $per_page) {
		print "<center>".nextprevHTMLMenu($playtotal, $pg, $per_page,  $NPvars, $_REQUEST)."</center>"; 
		}
?>
</div>
				<div class="cssClear"></div>


<?php } ?>
