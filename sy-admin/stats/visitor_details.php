<?php

	$visitor = doSQL("ms_stats_site_visitors", " *, date_format(st_date, '".$site_setup['date_format']." ')  AS st_date, date_format(st_last_visit, '".$site_setup['date_format']." ')  AS st_last_visit_show, time_format(st_time, '".$site_setup['date_time_format']."') AS st_time", "WHERE st_id='".$_REQUEST['pv_ref_id']."' ");
	?>
	<div class="pageContent">
	<div class="left">
<?php if($visitor['st_member'] > 0) { 
$person = doSQL("ms_people", "*", "WHERE p_id='".$visitor['st_member']."' ");
?>
 <h1><a href="index.php?do=people&p_id=<?php print $person['p_id'];?>"><?php print $person['p_name']." ".$person['p_last_name'];?></a> </h1>
<?php } ?>
</div>
	<div style="float: right;">
	<?php 
	$next = doSQL("ms_stats_site_visitors", "*", "WHERE st_id>'".$visitor['st_id']."' ORDER BY st_id ASC LIMIT 1 ");
	if(!empty($next['st_id'])) {
		print "<a href=\"index.php?do=stats&action=visitordetails&pv_ref_id=".$next['st_id']."\"><< Newer Visitor</a> &nbsp;";
	}
	$prev = doSQL("ms_stats_site_visitors", "*", "WHERE st_id<'".$visitor['st_id']."' ORDER BY st_id DESC LIMIT 1 ");
	if(!empty($prev['st_id'])) {
		print "<a href=\"index.php?do=stats&action=visitordetails&pv_ref_id=".$prev['st_id']."\">Older Visitor >> </a> &nbsp;";
	}
	?>
	</div>
	<div class="cssClear"></div>
	</div>

	<div style="width: 70%; float: left;" class="nofloatsmall">
	<div class="pageContent">
	<h2>IP: <?php print $visitor['st_ip'];?></h2></div>
	<div class="pc"><a href="index.php?do=stats&action=deleteip&ip=<?php print $visitor['st_ip'];?>">delete IP from stats</a></div>
	<div id="">
	<?php
	$lt = "30%";
	$rt = "70%";

	if(strlen($visitor['st_refer']) > 80) {
		$st_refer_show = substr_replace($visitor['st_refer'], "...", 80);
	} else {
		$st_refer_show = $visitor['st_refer'];
	}
?>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Date</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print $visitor['st_date']." @ ".$visitor['st_time'];?></div>
<div class="cssClear"></div>
</div>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">IP Address</div>
<div style="float: left; width: <?php print $rt;?>;"><a href="index.php?do=stats&action=recentVisitors&q=<?php print $visitor['st_ip'];?>"><?php print $visitor['st_ip'];?></a></div>
<div class="cssClear"></div>
</div>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Remote Host</div>
<?php 
if(empty($visitor['st_remote_host'])) { 
	$remote_host = @getHostByAddr($visitor['st_ip']);
	updateSQL("ms_stats_site_visitors", "st_remote_host='".addslashes(stripslashes($remote_host))."' WHERE st_id='".$visitor['st_id']."' ");
} else { 
	$remote_host = $visitor['st_remote_host'];
}
?>

<div style="float: left; width: <?php print $rt;?>;"><?php print $remote_host;?></a></div>
<div class="cssClear"></div>
</div>

<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Browser Info</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print $visitor['st_agent'];?></a></div>
<div class="cssClear"></div>
</div>
<?php
		$str = substr($visitor['st_remote_host'], -4, 4);
		$ct = strstr($str, ".");
?>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Country</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print getCountry($ct, $remote_host);?></a></div>
<div class="cssClear"></div>
</div>

<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Browser</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print getBrowser($visitor['st_agent']);?></a></div>
<div class="cssClear"></div>
</div>

<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Screen Resolution</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print $visitor['st_screen'];?></a></div>
<div class="cssClear"></div>
</div>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Referral Page</div>
<div style="float: left; width: <?php print $rt;?>;"><?php if(empty($visitor['st_refer'])) { print "Direct hit"; } else { print "<a href=\"http://".$visitor['st_refer']."\" target=\"_Blank\" title=\"".$visitor['st_refer']."\">$st_refer_show</a>"; } ?></a></div>
<div class="cssClear"></div>
</div>


<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Last Visit</div>
<div style="float: left; width: <?php print $rt;?>;"><?php 
		if($visitor['st_last_visit'] > 0) {
			print "".$visitor['st_last_visit_show']."";
		} else {
			print "New Visitor";
		}
?></a></div>
<div class="cssClear"></div>
</div>



<?php 
	$last_page = doSQL("ms_stats_site_pv", "*, time_format(pv_time, '%h:%i %p') AS pv_time", " WHERE pv_ref_id='".$visitor['st_id']."' ORDER BY pv_id DESC LIMIT 1");
?>
<div class="underline">
<div style="float: left; width: <?php print $lt;?>;">Last page viewed</div>
<div style="float: left; width: <?php print $rt;?>;"><?php print $last_page['pv_time'];?></a></div>
<div class="cssClear"></div>
</div>
</div>
</div>

<div style="width: 28%; float: right;" class="nofloatsmall">

<div class="pageContent"><h2>Pages viewed</h2></div>
<div id="">

<?php
	$def = doSQL("ms_photos", "*", "WHERE pic_id='".$site_setup['default_photo']."' ");

	$pages = whileSQL("ms_stats_site_pv", "*, date_format(pv_date, '%M %e, %Y ')  AS pv_date, time_format(pv_time, '%h:%i %p') AS pv_time", "WHERE pv_ref_id='".$visitor['st_id']."' ORDER BY pv_id ASC");
		while ( $page = mysqli_fetch_array($pages) ) {
		if(!empty($page['page_viewed'])) { 
			print "<div class=\"underline\">";
			showVisPage($page);
			print "<div class=\"clear\"></div>";
			print "<span class=\"muted\">".$page['pv_date']." ".$page['pv_time']."</span>";
			print "</div>";
		}
	}
	print "</div>";

?>
</div>
<div class="cssClear"></div>
<div>&nbsp;</div>
