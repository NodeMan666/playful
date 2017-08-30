<div id="pageTitle" class="left"><a href="index.php?do=booking">Calendar</a> <?php print ai_sep;?> Services</div>
<div class="pc buttons textright"><a href="" onclick="editbookingservice(); return false;">Add New Service</a></div>
<div class="clear"></div>

<?php
$services = whileSQL("ms_bookings_services", "*", "ORDER BY service_name ASC ");
if(mysqli_num_rows($services) <= 0) { ?>
<div class="error center">No services created</div>
<?php } ?>
<?php
while($service = mysqli_fetch_array($services)) { 
	?>
<div class="underline">
	<div class="left p5"><a href="" onclick="editbookingservice('<?php print $service['service_id'];?>'); return false;"><?php print ai_edit;?></a> <a href="index.php?do=booking&delete_service=<?php print $service['service_id'];?>" class="confirmdelete" title="Delete Service" confirm-title="Delete <?php print $service['service_name'];?>" confirm-message="Are you sure you want to delete <?php print $service['service_name'];?>?" ><?php print ai_delete;?></a>
	<br><a href="" onclick="editserviceoption('<?php print $service['service_id'];?>'); return false;">Add Option</a>
	</div>
	<div class="left p70">
		<div class="pc"><h3><?php print $service['service_name'];?></h3></div>
		<div class="pc"><?php if($service['service_all_day'] == "1") { ?>All Day Event<?php } else { ?>
		<?php 
	if(($service['service_length_hours'] > 0) || ($service['service_length_minutes'] > 0) == true) { 
		if($service['service_length_hours'] > 0) { 
			if($service['service_length_hours'] == "1") { 
				print $service['service_length_hours']." hour ";
			} else { 
				print $service['service_length_hours']." hours ";
			}
		} 
		if($service['service_length_minutes'] > 0) { 
			print $service['service_length_minutes']." minutes";
		}
	}
	?>
	<?php } ?></div>
		<div class="pc"><?php print $service['service_description'];?></div>
	<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_service='".$service['service_id']."' ORDER BY opt_order ASC "); ?>
			<ul id="sortable-list-<?php print $add;?>" class="sortable-list">


			<?php while($opt = mysqli_fetch_array($opts))  { 
				$total_opts++;
				?>
				<li title="<?php print $opt['opt_id'];?>"><?php showProductOption($opt,$section); ?></li>
				<?php
			 } ?>
			 </ul>
	</div>


	<div class="left p25 textright"><?php print showPrice($service['service_amount']);?> / <?php print $service['service_deposit'] * 1;?>%</div>
	<div class="clear"></div>
</div>
<div>&nbsp;</div>
<?php } ?>