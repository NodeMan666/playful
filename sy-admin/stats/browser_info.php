<?php
if($site_setup['stats_updated'] == 0) { 
	$stats = whileSQL("ms_stats_site_visitors", "*", "ORDER BY st_id DESC");
	while($stat = mysqli_fetch_array($stats)) { 
		$str = substr($stat['st_remote_host'], -4, 4);
		$ct = strstr($str, ".");
		if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipaq|ipod|j2me|java|midp|mini|mmp|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $stat['st_agent'])) { 
		$st_mobile = '1';
		}
		if((stripos($stat['st_agent'],"iPad")!==false)==true) {  
			$st_ipad = 1;
		}

		updateSQL("ms_stats_site_visitors","st_browser='".addslashes(stripslashes(getBrowser($stat['st_agent'])))."', st_browser_version='".addslashes(stripslashes(getBrowserVersion($stat['st_agent'])))."',st_country='".getCountry($ct, $stat['st_remote_host'])."', st_mobile='$st_mobile' , st_ipad='$st_ipad' WHERE st_id='".$stat['st_id']."' ");
		unset($st_mobile);
		unset($st_ipad);
	}
	updateSQL("ms_settings", "stats_updated='1' ");
	$_SESSION['sm'] = "Older stats updated";
	session_write_close();
	header("location: index.php?do=stats&action=browserInfo");
	exit();
}
?>

<div style="width: 33%; float: left;">
<div style="padding: 8px;">
<?php
$result = @mysqli_query($dbcon,"SELECT  st_browser, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_browser!='' GROUP BY st_browser ORDER BY dups DESC");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
?>
<?php
while ( $row2 = mysqli_fetch_array($result) ) {
	$total = $total + $row2['dups'];
}
$result = @mysqli_query($dbcon,"SELECT  st_browser, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_browser!='' GROUP BY st_browser ORDER BY dups DESC");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
?>
<div id="roundedForm">
<div class="label">Browsers</div>
<!-- <div class="row"><?php print $total;?> visitors in report</div> -->
<?php 

while ( $row = mysqli_fetch_array($result) ) {

	if(!empty($row['st_browser'])) { 
?>	

<div class="row">
<div style="width:30%;" class="cssCell"><?php print round((($row['dups'] / $total) * 100),2)."%"; print " <span class=\"muted\">(".$row["dups"].")</span>";?></div><div style="width:70%;" class="cssCell">

<div><?php	 print $row['st_browser'];	?></div>
<div>
<?php
$sresult = @mysqli_query($dbcon,"SELECT  st_browser, st_browser_version, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_browser='".$row['st_browser']."' GROUP BY st_browser_version ORDER BY dups DESC");
if (!$sresult) {	echo( "Error perforing query" . mysqli_error($sresult) . "that error");	exit(); }
$stotal = mysqli_num_rows($sresult);
if($stotal > 1) { 
while ( $srow = mysqli_fetch_array($sresult) ) { ?>
<div class="muted"><?php print round((($srow['dups'] / $total) * 100),2)."% (".$srow['dups'].") ".$srow['st_browser_version'];?></div>
<?php } 
}
?>
</div>
</div>
		<div class="cssClear"></div>
	</div>
<?php 	}
} ?>
</div>
</div></div>

<div style="width: 33%; float: left;">
<div style="padding: 8px;">
<?php 
$result = @mysqli_query($dbcon,"SELECT  st_country, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_country!='' GROUP BY st_country ORDER BY dups DESC");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
?>
<div id="roundedForm">
<div class="label">Countries</div>
<!-- <div class="row"><?php print $total;?> visitors in report</div> -->
<?php 

while ( $row = mysqli_fetch_array($result) ) {

	if(!empty($row['st_country'])) { 
?>	

<div class="row">
<div style="width:30%;" class="cssCell"><?php print round((($row['dups'] / $total) * 100),2)."%"; print " <span class=\"muted\">(".$row["dups"].")</span>";?></div><div style="width:70%;" class="cssCell">

<?php
			print $row['st_country'];
	?></div>
		<div class="cssClear"></div>
	</div>
<?php 	}
} ?>
</div>



</div></div>

<div style="width: 33%; float: left;">
<div style="padding: 8px;">
<?php
$total_v = countIt("ms_stats_site_visitors", "");
$total_s = countIt("ms_stats_site_visitors", "WHERE st_mobile='0' AND st_ipad='0' ");
$total_i = countIt("ms_stats_site_visitors", "WHERE st_ipad='1' ");
$total_m = countIt("ms_stats_site_visitors", "WHERE st_mobile='1'  ");

?>
<div id="roundedForm">
<div class="label">Devices</div>
<div class="row">
<div style="width:30%;" class="cssCell"><?php print @round((($total_s / $total_v) * 100),2)."%"; print " <span class=\"muted\">(".$total_s.")</span>";?></div><div style="width:70%;" class="cssCell">Computer</div>
<div class="cssClear"></div>
</div>

<div class="row">
<div style="width:30%;" class="cssCell"><?php print @round((($total_i / $total_v) * 100),2)."%"; print " <span class=\"muted\">(".$total_i.")</span>";?></div><div style="width:70%;" class="cssCell">
Tablet</div><div class="cssClear"></div>
</div>
<div class="row">
<div style="width:30%;" class="cssCell"><?php print @round((($total_m / $total_v) * 100),2)."%"; print " <span class=\"muted\">(".$total_m.")</span>";?></div><div style="width:70%;" class="cssCell">
Mobile</div><div class="cssClear"></div>
</div>
</div>
<div>&nbsp;</div>
<div >
<div >
<?php 
$result = @mysqli_query($dbcon,"SELECT  st_screen, COUNT(*) AS dups FROM ms_stats_site_visitors WHERE st_screen!='' GROUP BY st_screen ORDER BY dups DESC");
if (!$result) {	echo( "Error perforing query" . mysqli_error($result) . "that error");	exit(); }
$pagetotal = mysqli_num_rows($result);
?>
<div id="roundedForm">
<div class="label">Screen Resolution</div>
<!-- <div class="row"><?php print $total;?> visitors in report</div> -->
<?php 

while ( $row = mysqli_fetch_array($result) ) {

	if(!empty($row['st_screen'])) { 
?>	

<div class="row">
<div style="width:30%;" class="cssCell"><?php print round((($row['dups'] / $total) * 100),2)."%"; print " <span class=\"muted\">(".$row["dups"].")</span>";?></div><div style="width:70%;" class="cssCell">

<?php
			print $row['st_screen'];
	?></div>
		<div class="cssClear"></div>
	</div>
<?php 	}
} ?>
</div>



</div>
</div></div>
</div>



<div class="cssClear"></div>
