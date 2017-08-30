<?php
$a1 = "5%";
$a2 = "15%";
$a3 = "20%";
$a4 = "60%";
?>

<div class="pageContent"><h2>Search Engine Bots</h2></div>
<div id="roundedForm">
<div class="label">
 <div style="width: <?php print $a1;?>; float: left;">#</div>
 <div style="width: <?php print $a2;?>; float: left;">Bot Name</div>
 <div style="width: <?php print $a3;?>; float: left;">Date / Time</div>
 <div style="width: <?php print $a4;?>; float: left;">Page</div>
<div class="cssClear"></div>
</div>

<?php
	$res = countIt("ms_stats_site_pv",  " WHERE pv_bot!='' ");

	$perPage = 20;
	$x = 1;
	if((empty($_REQUEST['pg'])) || ($_REQUEST['pg'] == "1")==true){
		$pg = 1;
		$start = 0;
	} else {
		$pg = $_REQUEST['pg'];
		$start = ($perPage * ($pg-1));
	}
	$end = ($start + $perPage);
	$NPvars = array("do=stats", "action=bots");


	$def = doSQL("ms_photos", "*", "WHERE pic_id='".$site_setup['default_photo']."' ");
	$xl = 1;

	$pages = whileSQL("ms_stats_site_pv", "*, date_format(pv_date, '%M %e, %Y ')  AS pv_date, time_format(pv_time, '%h:%i %p') AS pv_time", "WHERE pv_bot!='' ORDER BY pv_id DESC LIMIT $start,$perPage");
		while ( $page = mysqli_fetch_array($pages) ) {
		$sxl = $xl + ($pg * $perPage) - $perPage;
		$xl++;
		?>
		<div class="row">
		<div style="width: <?php print $a1;?>; float: left;"><?php print $sxl;?></div>
		<div style="width: <?php print $a2;?>; float: left;"><?php print $page['pv_bot'];?>&nbsp;</div>
		<div style="width: <?php print $a3;?>; float: left;"><?php print $page['pv_date']." ".$page['pv_time'];?></div>
		<div style="width: <?php print $a4;?>; float: left;"><?php showVisPage($page); ?></div>
		<div class="cssClear"></div>
		</div>
		<?php 
	}
	print "</div>";

?>
<div class="cssClear"></div>
<div>&nbsp;</div>
<?php 
		print "<table align=center cellpadding=2 cellspacing=0 border=0 width=100%><tr align=bottom><td width=100% align=right>";
			nextprev($_REQUEST, $setting, $mem, $res, $pg, $perPage,  $NPvars, $what);
			print "</td></tr></table>";
?>
