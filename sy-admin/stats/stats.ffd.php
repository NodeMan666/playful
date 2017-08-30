<div class=pagetitle><a href="index.php?do=stats" class=pagetitle>Stats</a> > Fan Free Downloads
<?php
if(!empty($_REQUEST['fanq'])) {
	$and_where .= "AND ( fan_email LIKE '%".addslashes($_REQUEST['fanq'])."%'  OR   fan_first_name LIKE '%".addslashes($_REQUEST['fanq'])."%' OR  fan_last_name LIKE '%".addslashes($_REQUEST['fanq'])."%' ) ";
	print " > search for ".$_REQUEST['fanq']."";
}


?>
</div>
<div class="cssClear">&nbsp;</div>
<div style="width: 28%; float: left;">
<div class=padTopBottom><span class=pageTitle>Overview</span></div>
<div>* <i>Based on those who supplied an answer</i></div>

<div id="roundeddata"> <div id="tlcorner"><div id="trcorner"><div id="blcorner"><div id="brcorner">
	<div id="menucontain">
		<div id="roundeddataitem">
			<div style="width:50%;" class="cssCell">Total Fans From FFD&nbsp;</div><div style="width:50%;" class="cssCell"><?php print countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' "); ?>
			</div>
			<div class="cssClear"></div>
		</div>
		<?php
		$total_gen = countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' AND (fan_gender='M' OR fan_gender='F') ");
		$total_f = countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' AND  fan_gender='F' ");
		$total_m = countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' AND fan_gender='M'  ");
		?>

		<div id="roundeddataitem">
			<div style="width:50%;" class="cssCell">* Females&nbsp;</div><div style="width:50%;" class="cssCell"><?php if($total_f > 0) { print round(($total_f / $total_gen)*100)."% ($total_f)"; } else { print "n/a"; } ?>
			</div>
			<div class="cssClear"></div>
		</div>

		<div id="roundeddataitem">
			<div style="width:50%;" class="cssCell">* Males&nbsp;</div><div style="width:50%;" class="cssCell"><?php if($total_m > 0) {  print round(($total_m / $total_gen)*100)."% ($total_m)"; } else { print "n/a"; }?>
			</div>
			<div class="cssClear"></div>
		</div>

		<?php
		$total_age = countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' AND fan_age>'0'  ");
		$stotal = doSQL("ms_fans", "SUM(fan_age) AS tot", "WHERE fan_id>'0' AND fan_download_code!='' AND fan_age>'0'  "); 
		?>

		<div id="roundeddataitem">
			<div style="width:50%;" class="cssCell">* Average Age&nbsp;</div><div style="width:50%;" class="cssCell"><?php if($total_age > 0) { print round($stotal['tot'] / $total_age).""; } else { print "n/a"; }?>
			</div>
			<div class="cssClear"></div>
		</div>



		</div>
	</div></div></div></div></div>
	<div>&nbsp;</div>

<div class=padTopBottom><span class=pageTitle>Most popular downloads</span></div>

<div id="roundeddata"> <div id="tlcorner"><div id="trcorner"><div id="blcorner"><div id="brcorner">
	<div id="menucontain">

	<?php 
	$tops = whileSQL("ms_fans","*, COUNT(*) AS dups", "WHERE fan_id>'0' AND fan_download_code!=''  GROUP BY fan_download_song ORDER BY dups DESC LIMIT 100");
	if(mysqli_num_rows($tops)<=0) { ?>
		<div id="roundeddataitem"><center>No stats for most popular downloads</center></div>
	<?php
	}
	while($top = mysqli_fetch_array($tops)) { 
		$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$top['fan_download_song']."' ");
		?>

		<div id="roundeddataitem">
			<div style="width:50%;" class="cssCell"><?php 	if(empty($track['music_id'])) { print "<span class=statusoff><i>deleted song</i></span>"; } else { print "".$track['music_title'].""; }?>
		</div>	
		<div style="width:50%;" class="cssCell"><?php print $top['dups']; ?>
			</div>
			<div class="cssClear"></div>
		</div>

		<?php } ?>



		</div>
	</div></div></div></div></div>

</div>
<div style="width: 70%; float: right;">
<div class=padTopBottom><span class=pageTitle>Newest Fans from Fan Free Download</span></div>
<div>Click on email address for more details</div>


<?php
$playtotal = countIt("ms_fans", "WHERE fan_id>'0' AND fan_download_code!='' $and_where "); 
if($playtotal <= 0) { ?>
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
	$NPvars = array("do=stats", "view=ffd");
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "3%";
	$cw2 = "3%";
	$cw3 = "20%";
	$cw4 = "25%";
	$cw5 = "25%";
	$cw6 = "30%";
	$cw7 = "28%";
	?>
	<form method="post" name="listForm" id="listForm" action="index.php" style="margin:0px;padding:0px;">
	<div id="cssMainContainer"> 
	<div id="cssRowLabelContainer" >
			<div class="cssRowLabel"  style="width: <?php print $cw3;?>;"><B>Email</B></div>
			<div class="cssRowLabel"  style="width: <?php print $cw4;?>;"><B>Name</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw5;?>;"><B>Date</B></div>
			<div  class="cssRowLabel" style="width: <?php print $cw6;?>;"><B>Song</B></div>
			<div  class="cssClear"></div>
		</div>

		<div class="cssRowMain">
		<?php
		$plays = whileSQL("ms_fans", "*,date_format(DATE_ADD(fan_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS fan_date", "WHERE fan_id>'0' AND fan_download_code!='' $and_where ORDER BY fan_id DESC LIMIT $sq_page,$per_page  ");
		while ($play = mysqli_fetch_array($plays)) {
			$fannum++;
			?>
			<div id="row-<?php print $fannum;?>"  class="cssRowContainer" onmouseover="this.className='cssRowContainerHover';" onmouseout="this.className='cssRowContainer';">
			<div style="width: <?php print $cw3;?>;" class="cssCell"><a href="index.php?do=fans&fanq=<?php print $play['fan_email']; ?>"><?php print $play['fan_email']; ?></a></div>
			<div style="width: <?php print $cw4;?>;" class="cssCell"><?php print $play['fan_first_name']." ".$play['fan_last_name']; ?>&nbsp;</div>
			<div style="width: <?php print $cw5;?>;" class="cssCell"><?php print $play['fan_date'];?>&nbsp;</div>
			<div style="width: <?php print $cw6;?>;" class="cssCell"><?php 
				$track = doSQL("ms_music", "music_id, music_title", "WHERE music_id='".$play['fan_download_song']."' ");
				 if(empty($track['music_id'])) { print "<span class=statusoff><i>has not downloaded</i></span>"; } else { print "".$track['music_title'].""; }?>
				&nbsp;</div>
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
