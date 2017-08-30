<?php 
if($_REQUEST['p_id'] <= 0) { 
	$p = doSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active", "WHERE p_id='".$_REQUEST['p_id']."' ");
	$no_trim = true;?>
	<div id="pageTitle"><a href="index.php?do=people">People</a> <?php if($p['p_id'] > 0) { ?><?php print ai_sep;?> <a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><?php print $p['p_name']." ".$p['p_last_name']." (".$p['p_email'].")"; ?></a><?php } ?> <?php print ai_sep;?> Favorites</div> 

	<div class="pc">Photos customers have added to their favorites.</div>
<?php } ?>
<?php if($p['p_id'] > 0) { ?>
<div class="pc">Photos that have been added to their favorites</div>
<div>
	<div class="pc"><span style="font-size: 21px;">Export File Names</span></div>
	<div class="pc" style="">
	<form method="get" name="printthis" action="people/people.export.favorites.php" style="display: inline;" target="_blank">
	<input type="hidden" name="p_id" value="<?php print $p['p_id'];?>">
	<input type="hidden" name="order_id" value="<?php print $order['order_id'];?>">
	<input type="radio" name="dowith" id="dowith1" value="view" <?php if($history['export_dowith'] == "view") { print "checked"; } ?>> <label for="dowith1">Print to screen</label> &nbsp; 
	<input type="radio" name="dowith" id="dowith2" value="" <?php if($history['export_dowith'] !== "view") { print "checked"; } ?>> <label for="dowith2">Save As File</label> &nbsp; &nbsp; 

	Separate with: <input type="text" size="2" class="center" name="sepwith" value="<?php print $history['export_sepwith'];?>">  &nbsp; &nbsp;

	<input type="checkbox" name="stripext" id="stripext" value="1"> <label for="stripext">Remove file extension</label>
	<input type="submit" name="submit" value="Export" class="submitSmall">
	</form>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php  $show_thumbnails = true;?>
<div id="photoGallery">
	<div id="showThumbnails"></div>
</div>
<div class="cssClear"></div>

<div id="endpage" style="position: absolute;"></div>

