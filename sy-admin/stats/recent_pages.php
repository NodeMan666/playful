<?php
$pages = whileSQL("ms_stats_site_pv LEFT JOIN ms_stats_site_visitors ON ms_stats_site_pv.pv_ref_id=ms_stats_site_visitors.st_id", "*, date_format(DATE_ADD(pv_date, INTERVAL 0 HOUR), '%m/%e')  AS pv_date,  time_format(pv_time, '%h:%i %p')  AS pv_time", "ORDER BY pv_id DESC limit 100 ");
while($page = mysqli_fetch_array($pages)) { 
	$x++;?>
<div class="underline">
<div class="p5 left"><?php print $x;?></div>
<div class="p15 left"><?php print $page['pv_date']." ".$page['pv_time'];?></div>
<div class="p20 left"><?php 
if($page['st_member'] > 0) { 
	$person = doSQL("ms_people", "*", "WHERE p_id='".$page['st_member']."' ");
	?>
	<a href="index.php?do=people&p_id=<?php print $person['p_id'];?>"><?php print $person['p_name']." ".$person['p_last_name'];?></a><br>
	<?php } ?><a href="index.php?do=stats&action=visitordetails&pv_ref_id=<?php print $page['st_id'];?>"><?php print $page['st_ip'];?></a>
</div>

<div class="p60 left"><?php showVisPage($page); ?></div>
<div class="clear"></div>
</div>
<?php } ?>
