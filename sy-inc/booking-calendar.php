<script>
function selectbooking(id,opts) { 
	$("#bookingservices").slideUp(200, function() { 
		$("#bookingloading").fadeIn(200, function() { 
			$.get("<?php print $setup['temp_url_folder'];?>/sy-inc/booking-calendar-options.php?sid="+id+"&action=getoptions", function(data) {
				$("#bookingoptions").attr("data-service",id);
				$("#bookingloading").fadeOut(200, function() { 
					$("#bookingoptions").html(data).slideDown(200);
				});
			});
		});
	});
}
function getCalendar(m,y,d) { 
 	$.get("<?php print $setup['temp_url_folder'];?>/sy-inc/booking-calendar-options.php?action=getcalendar&month="+m+"&year="+y+"&day="+d, function(data) {
		$("#calendarselect").html(data).fadeIn(400);
		$("#calendarselect").attr("data-year",y);
		$("#calendarselect").attr("data-month",m);
	});
}

</script>
<div style="max-width: 1024px; margin: auto;">

	<div id="bookingoptions" class="hide" data-service=""></div>
	<div id="booking">
		<div  id="bookingservices">
			<?php
			$services = whileSQL("ms_bookings_services", "*", "ORDER BY service_name ASC ");
			if(mysqli_num_rows($services) <= 0) { ?>
			<div class="error center">No services created</div>
			<?php } ?>
			<?php
			while($service = mysqli_fetch_array($services)) { 
			?>
				<div class="pc"><h3><?php print $service['service_name'];?></h3></div>
				<div class="pc"><?php print $service['service_description'];?></div>
				<div class="pc"><?php print showPrice($service['service_amount']);?> <a href="" onclick="selectbooking('<?php print MD5($service['service_id']);?>','<?php if(countIt("ms_product_options", "WHERE opt_service='".$service['service_id']."' ") > 0) { ?>1<?php } ?>'); return false;" class="submitbutton">Book</a></div>

				<div>&nbsp;</div>
				<div>&nbsp;</div>
			<?php }
			?>
		</div>
	</div>
	<div id="bookingloading" class="hide"><div class="loadingspinner"></div></div>

	</div>


