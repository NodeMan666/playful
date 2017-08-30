<?php
	if(!empty($_REQUEST['share_page'])) { 
		$and_where .= "AND share_page='".$_REQUEST['share_page']."' ";
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['share_page']."' ");
	}
?>
<div id="pageTitle" class="left"><a href="index.php?do=stats">Stats</a> <?php print ai_sep;?>Shares <?php if(!empty($date['date_id'])) { print ai_sep." ".$date['date_title']; } ?></div>
<div class="cssClear">&nbsp;</div>
<div class=padTopBottom><div id="info">These are social shares made by visitors. These are recorded when someone clicks to share a page using the icon shares and when someone shares a photo. This does not mean they actually completed the process of sharing.</div></div>



<?php
$sharetotal = countIt("ms_shares", "WHERE share_id>'0' $and_where "); 
if($sharetotal <= 0) { ?>
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
	$NPvars = array("do=stats", "view=shares", "q=".$_REQUEST['q']."");
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw4 = "50%";
	$cw5 = "20%";
	$cw6 = "10%";
	$cw7 = "20%";
	?>
	<div id="underlinelabelContainer" >
			<div class="underlinelabel">
				<div class="left" style="width: <?php print $cw4;?>;"><B>Shared</B></div>
				<div class="left" style="width: <?php print $cw5;?>;"><B>Who</B></div>
				<div class="left" style="width: <?php print $cw6;?>;"><B>Where</B></div>
				<div class="left" style="width: <?php print $cw7;?>;"><B>Date</B></div>
			<div  class="cssClear"></div>
			</div>
		</div>

		<?php
		$shares = whileSQL("ms_shares LEFT JOIN ms_calendar ON ms_shares.share_page=ms_calendar.date_id", "*,date_format(DATE_ADD(share_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS share_date", "WHERE share_id>'0' $and_where ORDER BY share_id DESC LIMIT $sq_page,$per_page  ");
		while ($share = mysqli_fetch_array($shares)) {
			if(!empty($share['date_id'])) { 
			$fannum++;
			?>
			<div class="underline">
				<div style="width: <?php print $cw4;?>;" class="left">
				<?php if($share['share_photo'] > 0) { 
					$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$share['share_photo']."' ");
					if(!empty($pic['pic_id'])) { 
					?>
					<div class="left" style="margin-right: 16px;"><img src="<?php print getimagefile($pic,'pic_mini');?>"></div>
					<div class="left">
					<div><b>Photo: <?php print $pic['pic_org'];?></b></div>
					<div><?php showVisPage($share);?></div>
					</div>
					<div class="clear"></div>
					<?php } ?>

				<?php } else {  ?>
				<?php showVisPage($share);?>
				<?php } ?>
				</div>
				<div style="width: <?php print $cw5;?>;" class="left">
				<?php if($share['share_person'] > 0) { 
					$p = doSQL("ms_people", "*", "WHERE p_id='".$share['share_person']."' ");
					?>
					<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_name']." ".$p['p_last_name'];?></a>
					<?php } else { ?>
					<a href="index.php?do=stats&action=recentVisitors&q=<?php print $share['share_ip'];?>"><?php print $share['share_ip'];?></a>
					<?php } ?>
				</div>
				<div style="width: <?php print $cw6;?>;" class="left"><?php print $share['share_where'];?></div>
				<div style="width: <?php print $cw7;?>;" class="left"><?php print $share['share_date'];?> </div>
				<div class="cssClear"></div>
			</div>
			<?php } 
		}?>
		</div>
		</div>
	<?php 
	if($sharetotal > $per_page) {
		print "<center>".nextprevHTMLMenu($sharetotal, $pg, $per_page,  $NPvars, $_REQUEST)."</center>"; 
		}
?>
<?php } ?>
