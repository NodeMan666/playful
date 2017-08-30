<?php 
require("../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
require $setup['path']."/".$setup['inc_folder']."/booking-functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			if($id !== "book_customer_notes") { 
				$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			}
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
		}
	}
}
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

if($_REQUEST['action'] == "getcalendar") { 
	minicalendar();
	exit();
}

if($_REQUEST['action'] == "getdatetime") { 
	if(empty($setup['calendar_time_format_a'])) { 
		$setup['calendar_time_format_a'] = "g:i A";
	}
	if(empty($setup['calendar_time_format_b'])) { 
		$setup['calendar_time_format_b'] = "%l:%M %p";
	}

	if($_REQUEST['book_time'] == "1") { 
		$strdate =  strftime("%a %B %e, %Y", strtotime(date("Y-m-d H:i:s", mktime($t[0],$t[1],0,$_REQUEST['book_month'],$_REQUEST['book_day'],$_REQUEST['book_year']))));
		if(empty($strdate)) { 
			print date("D F j, Y ", mktime($t[0],$t[1],0,$_REQUEST['book_month'],$_REQUEST['book_day'],$_REQUEST['book_year']));
		} else { 
			print $strdate;
		}
	} else { 
		$t = explode(":",$_REQUEST['book_time']);
		$strdate =  strftime("%a %B %e, %Y ".$setup['calendar_time_format_b']."", strtotime(date("Y-m-d H:i:s", mktime($t[0],$t[1],0,$_REQUEST['book_month'],$_REQUEST['book_day'],$_REQUEST['book_year']))));
		if(empty($strdate)) { 
			print date("D F j, Y ".$setup['calendar_time_format_a']."", mktime($t[0],$t[1],0,$_REQUEST['book_month'],$_REQUEST['book_day'],$_REQUEST['book_year']));
		} else { 
			print $strdate;
		}
	}
	exit();
}



if($_REQUEST['action'] == "confirmbooking") { 
	if(!is_numeric($_REQUEST['book_year'])) { die(); }
	if(!is_numeric($_REQUEST['book_month'])) { die(); }
	if(!is_numeric($_REQUEST['book_day'])) {  die(); }
	if(!ctype_alnum($_REQUEST['book_service'])) { die(); } 

	$service = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['book_service']."' ");
	$book_price = $service['prod_price'];
	$opts = whileSQL("ms_product_options", "*", "WHERE opt_date='".$service['date_id']."' ORDER BY opt_order ASC ");
	while($opt = mysqli_fetch_array($opts))  {
		if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
			if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")||($opt['opt_type'] == "tabs")==true) { 
				$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
				$book_options .= $opt['opt_name']."|".$sel['sel_name']."|".$sel['sel_price']."\r\n";
				$add_time = $add_time + $sel['sel_add_time'];
				$book_price = $book_price + $sel['sel_price'];
			}
			if($opt['opt_type'] == "checkbox") { 
				$opt_price = $opt['opt_price_checked'];
				$opt_select_name = "selected";
				$book_options .= $opt['opt_name']."||".$opt['opt_price_checked']."\r\n";
				$add_time = $add_time + $opt['opt_addition_time'];
				$book_price = $book_price + $opt['opt_price_checked'];
			}

			if($opt['opt_type'] == "text") { 
				$opt_price = $opt['opt_price'];
				$opt_select_name = "selected";
				$book_options .= $opt['opt_name']."|".$_REQUEST['opt-'.$opt['opt_id'].'']."|".$opt['opt_price']."\r\n";
				$add_time = $add_time + $opt['opt_addition_time'];
				$book_price = $book_price + $opt['opt_price'];
			}

		}
	}

	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(!empty($person['p_id'])) { 
		$p_id = $person['p_id'];
	}

	$book_date = $_REQUEST['book_year']."-".$_REQUEST['book_month']."-".$_REQUEST['book_day'];
	$book_time = $_REQUEST['book_time'];
	if($service['book_deposit_flat'] > 0) { 
		$book_deposit = $service['book_deposit_flat'];
	} else { 
		$book_deposit = $book_price * ($service['deposit'] / 100);
	}
	if($service['book_all_day'] == "1") { 
		$book_time = "00:00:00";
	}

	if($service['book_once_a_day'] == "1") { 
		$book_time = $service['book_once_a_day_time'];
	}



	if(($service['deposit'] > 0) && ($service['deposit'] < 100) == true) { 
		$cart_product_name = $service['_deposit_'].": ".$service['date_title'];
	} else { 
		$cart_product_name = $service['date_title'];
	}

	$book_length = ($service['book_length_hours'] * 60) + $service['book_length_minutes'] + $add_time;
	$id = insertSQL("ms_bookings", "book_date='".$book_date."', book_time='".$book_time."', book_length='".$book_length."', book_first_name='".addslashes(stripslashes($_REQUEST['book_first_name']))."', book_last_name='".addslashes(stripslashes($_REQUEST['book_last_name']))."', book_email='".addslashes(stripslashes($_REQUEST['book_email']))."', book_phone='".addslashes(stripslashes($_REQUEST['book_phone']))."', book_service='".addslashes(stripslashes($service['date_id']))."' , book_customer_notes='".addslashes(stripslashes($_REQUEST['book_customer_notes']))."', book_all_day='".$service['book_all_day']."', book_options='".addslashes(stripslashes($book_options))."', book_submitted='1', book_date_added='".currentdatetime()."', book_total='".addslashes(stripslashes($_REQUEST['book_total']))."', book_deposit='".addslashes(stripslashes($book_deposit))."', book_account='".$p_id."', book_ip='".getUserIP()."'  ");

	if($service['book_require_deposit'] == "1") { 
		$cart_add_id = insertSQL("ms_cart", "cart_qty='1', cart_store_product='".$service['date_id']."', cart_product_name='".addslashes(stripslashes($cart_product_name ))."', cart_order_message='".addslashes(stripslashes($service['prod_order_message']))."', cart_price='".$book_deposit."', cart_ship='0', cart_download='0', cart_service='1', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$service['prod_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."' , cart_cost='".$prod_cost."', cart_no_discount='".$service['prod_no_discount']."', cart_account_credit='".$service['date_credit']."', cart_account_credit_for='".$date['reg_person']."', 
		cart_reg_message='".addslashes(stripslashes($cart_reg_message))."', 
		cart_reg_message_name='".addslashes(stripslashes($cart_reg_message_name))."', 
		cart_reg_no_display_amount='".$_REQUEST['no_show']."', 
		cart_paid_access='".$date['date_paid_access']."' , 
		cart_pre_reg='".$_POST['cart_pre_reg']."', 
		cart_extra_ship='".$date['prod_add_ship']."', 
		cart_photo_bg='".$bgphoto['pic_id']."',
		cart_subscription='".$cart_subscription."',
		cart_booking='".$id."' ");
	} else { 

		if($service['book_confirm_no_deposit'] == "1") { 
			$book = doSQL("ms_bookings LEFT JOIN ms_calendar ON ms_bookings.book_service=ms_calendar.date_id", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '%a ".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time_show", "WHERE book_id='".$id."' ");
			bookingconfirmemail($book);
			bookingemail($book['book_id'],'None',"Yes");

			updateSQL("ms_bookings", "book_confirmed='2' WHERE book_id='".$id."' ");
		} else { 
			updateSQL("ms_bookings", "book_confirmed='1' WHERE book_id='".$id."' ");
			bookingemail($id,"N/A","No");
		}
	}
	print "good";
	exit();
}



if(!ctype_alnum($_REQUEST['book_service'])) { die(); } 
$service = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['book_service']."' ");
if(empty($service['date_id'])) { die("Sorry, there seems to be an error."); } 
define(_deposit_,$service['_deposit_']);
define(_booking_comments_or_notes_,$service['_booking_comments_or_notes_']);
define(_booking_send_request_,$service['_booking_send_request_']);
define(_booking_your_information_,$service['_booking_your_information_']);
define(_booking_success_title_,$service['_booking_success_title_']);
define(_booking_success_message_,$service['_booking_success_message_']);
define(_booking_additional_options_,$service['_booking_additional_options_']);
define(_booking_additional_options_message_,$service['_booking_additional_options_message_']);
define(_booking_deposit_required_message_,$service['_booking_deposit_required_message_']);
define(_booking_select_time_,$service['_booking_select_time_']);
define(_learn_more_,$service['_learn_more_']);
define(_book_now_,$service['_book_now_']);

define(_booking_confirm_button_,$service['_booking_confirm_button_']);
define(_booking_auto_confirm_text_,$service['_booking_auto_confirm_text_']);


define(_booking_your_information_text_,$service['_booking_your_information_text_']);
define(_booking_your_information_text_above_send_,$service['_booking_your_information_text_above_send_']);


?>
<div style="padding: 24px;" class="inner">
<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
<?php
	if(($service['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		 $service['prod_price'] = $service['prod_price'] + (($service['prod_price'] * $site_setup['include_vat_rate']) / 100);
	}
	?>

<div class="pc"><h2><?php print $service['date_title'];?></h2></div>
<div class="pc <?php if($service['prod_price'] <= 0) { ?>hide<?php } ?>" id="bookingprice" data-total-price="<?php print $service['prod_price'];?>" data-default-price="<?php print $service['prod_price'];?>">
<?php print showPrice($service['prod_price']);?></div>
<?php

if($_REQUEST['action'] == "getoptions") { 
	print "options here";

}
if($_REQUEST['action'] == "getdate") { 

}

if($service['book_special_event'] == "1") { 
	$sd = explode("-",$service['book_special_event_date']);
	$cmonth = $sd[1];
	$_REQUEST['day'] = $sd[2];
	$cyear = $sd[0];
}
minicalendar();
?>
<div id="bookingdatetime" class="hide pc"></div>
<div id="bookdeposit" class="hide">
	<div class="pc"><?php print _booking_deposit_required_message_;?> </div>
	<div class="pc" id="depositamount"></div>
	<div class="pc center" style="margin: 4px 0px;" ><span class="addtocart" style="padding: 8px;" onclick="bookingdeposit('bookfield'); return false;"><?php print _continue_;?></span></div>
	<div class="pc center"><a href="" onclick="showbookingcalendar('<?php print MD5($service['date_id']);?>'); return false;"><?php print _back_;?></a></div>

</div>


<?php $opts = whileSQL("ms_product_options LEFT JOIN ms_calendar ON ms_product_options.opt_date=ms_calendar.date_id", "*", "WHERE opt_date='".$service['date_id']."' ORDER BY opt_order ASC "); ?>
	<div id="bookingoptions" class="hide" data-total-options="<?php print mysqli_num_rows($opts);?>">
	<?php if(mysqli_num_rows($opts) > 0) { ?>
	<div class="pc"><h3><?php print _booking_additional_options_;?></h3>
	<p><?php print _booking_additional_options_message_;?></p>

	</div>
	<div>
	<?php 
	while($opt = mysqli_fetch_array($opts))  { ?>
		<div  style="margin-bottom: 16px;"><?php bookingOptions($opt,'bookfield',$service); ?></div>
	<?php } ?>

	</div>
	<div class="pc center"  style="margin: 4px 0px;"><span class="addtocart" style="padding: 8px;" onclick="bookingcheckoptions(); return false;"><?php print _continue_;?></span></div>
	<div class="pc center"><a href="" onclick="showbookingcalendar('<?php print MD5($service['date_id']);?>'); return false;"><?php print _back_;?></a></div>
	<?php } ?>
</div>


<div id="bookingcomplete" class="hide">
	<div class="pc"><h3><?php print _booking_success_title_;?></h3>
	<p><?php
	
	if($service['book_confirm_no_deposit'] == "1") { 
		print _booking_auto_confirm_text_;	
	} else { 
		print _booking_success_message_;
	}
	?></p>
	</div>
</div>

<div id="bookinginfo" class="hide">
	<div class="pc"><h3><?php print _booking_your_information_;?></h3>
	<p><?php print _booking_your_information_text_;?></p>


	<input type="hidden" id="book_require_deposit" class="bookfield" value="<?php print $service['book_require_deposit'];?>" data-amount="<?php print $service['deposit'];?>" data-flat-rate="<?php print $service['book_deposit_flat'];?>">

	<input type="hidden" id="book_all_day" class="bookfield" value="<?php print $service['book_all_day'];?>">
	<input type="hidden" id="book_once_a_day" class="bookfield" value="<?php print $service['book_once_a_day'];?>">
	<input type="hidden" id="has_options" class="bookfield" value="<?php print countIt("ms_product_options", "WHERE opt_date='".$service['date_id']."' ");?>">

	<input type="hidden" id="book_year" class="bookfield" value="<?php print $sd[0];?>">
	<input type="hidden" id="book_month" class="bookfield" value="<?php print $sd[1];?>">
	<input type="hidden" id="book_day" class="bookfield" value="<?php print $sd[2];?>">
	<input type="hidden" id="book_service" class="bookfield" value="">
	<input type="hidden" id="book_time" class="bookfield" value="">
	<input type="hidden" id="action" class="bookfield" value="confirmbooking">
	<input type="hidden" id="book_total" class="bookfield" value="<?php print $service['prod_price'];?>">
	<?php 
	if(isset($_SESSION['pid'])) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	}
	?>

	<div class="pc">
		<div><?php print _first_name_;?></div>
		<div><input type="text" class="field100 itemrequired bookfield" id="book_first_name" value="<?php print $person['p_name'];?>"></div>
	</div>
	<div class="pc">
		<div><?php print _last_name_;?></div>
		<div><input type="text" class="field100 itemrequired bookfield" id="book_last_name" value="<?php print $person['p_last_name'];?>"></div>
	</div>

	<div class="pc">
		<div><?php print _email_address_;?></div>
		<div><input type="text" class="field100 itemrequired bookfield" id="book_email"  value="<?php print $person['p_email'];?>"></div>
	</div>

	<div class="pc">
		<div><?php print _phone_;?></div>
		<div><input type="text" class="field100 itemrequired bookfield" id="book_phone" value="<?php print $person['p_phone'];?>"></div>
	</div>
	<div class="pc">
		<div><?php print _booking_comments_or_notes_;?></div>
		<div><textarea class="field100 bookfield" id="book_customer_notes" rows="3"></textarea></div>
	</div>

	<div class="pc"><p><?php print _booking_your_information_text_above_send_;?></p></div>

	<div id="sendloading" class="loadingspinnersmall hide"></div>
	<div class="pc center" style="margin: 4px 0px;" id="sendrequest"><span class="addtocart" style="padding: 8px;"  onclick="confirmbooking('bookfield'); return false;"><?php if($service['book_confirm_no_deposit'] == "1") { 
print _booking_confirm_button_; } else { print _booking_send_request_; } ?></span></div>
		<div class="pc center"><a href="" onclick="showbookingcalendar('<?php print MD5($service['date_id']);?>'); return false;"><?php print _back_;?></a></div>

</div>
</div>

<?php  mysqli_close($dbcon); ?>
