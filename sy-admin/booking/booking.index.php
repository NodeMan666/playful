<?php
$booksettings = doSQL("ms_bookings_settings", "*", "");
if($setup['demo_mode'] !== true) { 

	if(!empty($_REQUEST['delete_service'])) { 
		$service = doSQL("ms_bookings_services", "*", "WHERE service_id='".$_REQUEST['delete_service']."' ");
		if(!empty($service['service_id'])) { 
			deleteSQL("ms_bookings_services", "WHERE service_id='".$service['service_id']."' ","1");
			$_SESSION['sm'] = $service['service_name']." was deleted";
		}
		header("location: index.php?do=booking&view=services");
		session_write_close();
		exit();
	}

	if(!empty($_REQUEST['deleteOption'])) { 
		$opt = doSQL("ms_product_options", "*", "WHERE opt_id='".$_REQUEST['deleteOption']."' "); 
		if(!empty($opt['opt_id'])) { 
			deleteSQL("ms_product_options", "WHERE opt_id='".$opt['opt_id']."' ", "1");
			deleteSQL2("ms_product_options_sel", "WHERE sel_opt='".$opt['opt_id']."' ");
		}
		$_SESSION['sm'] = "Option deleted";
		session_write_close();
		header("location: index.php?do=booking&view=services");
		exit();
	}
	if(!empty($_REQUEST['delete_unavailable'])) { 
		$book = doSQL("ms_bookings", "*", "WHERE book_id='".$_REQUEST['delete_unavailable']."' "); 
		if(!empty($book['book_id'])) { 
			deleteSQL("ms_bookings", "WHERE book_id='".$book['book_id']."' ", "1");
		}
		$_SESSION['sm'] = "Unavailable time deleted";
		session_write_close();
		header("location: index.php?do=booking&view=settings");
		exit();
	}
	if(!empty($_REQUEST['date_unavailable'])) { 
		insertSQL("ms_bookings", "book_date='".$_REQUEST['date_unavailable']."', book_unavailable_day='1' ");
		exit();
	}
	if(!empty($_REQUEST['date_available'])) { 
		deleteSQL("ms_bookings", "WHERE book_date='".$_REQUEST['date_available']."' AND book_unavailable_day='1' ", "1");
		exit();
	}

	if(!empty($_REQUEST['available_day'])) { 
		updateSQL("ms_bookings_settings", " ".$_REQUEST['available_day']."='".$_REQUEST['st']."'  ");
		exit();
	}
	if($_REQUEST['action'] == "available_time") { 
		updateSQL("ms_bookings_settings", " ".$_REQUEST['d']."_start_time='".$_REQUEST[''.$_REQUEST[d].'_start_time']."' ,  ".$_REQUEST['d']."_end_time='".$_REQUEST[''.$_REQUEST[d].'_end_time']."'  ,  ".$_REQUEST['d']."_time_blocks='".$_REQUEST[''.$_REQUEST[d].'_time_blocks']."'  ");
		exit();
	}
}
?>

<?php if(empty($_REQUEST['view'])) { ?>
<script>
 $(document).ready(function(){
	 getCalendar('','');
 });
</script>
<style>
#adminfooter { display: none; }
</style><?php } ?>

<?php
if(isset($_REQUEST['delete_booking'])) { 
	$book = doSQL("ms_bookings", "*", "WHERE book_id='".$_REQUEST['delete_booking']."' ");
	if(!empty($book['book_id'])) { 
		deleteSQL("ms_bookings","WHERE book_id='".$book['book_id']."' ","1");
	}
	exit();
}

if((!empty($_REQUEST['month']))&&(!empty($_REQUEST['year']))==true) { 
	$cmonth = $_REQUEST['month'];
	$cyear = $_REQUEST['year'];
} else { 
	$cmonth = date("m");
	$cyear = date("Y");
}
?>
<div id="bookingcalendar" data-month="<?php print $cmonth;?>" data-year="<?php print $cyear;?>" class="hide left" style="width: 100%;"></div>
<div class="clear"></div>
<?php 
if($_REQUEST['view'] == "services") { 
	include "booking/booking.services.php";
}
if($_REQUEST['view'] == "settings") { 
	include "booking/booking.settings.php";
}

?>