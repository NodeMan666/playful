<?php 
$path = "../../";
$booking_invoice = true;
require "../w-header.php"; 
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$book = doSQL("ms_bookings LEFT JOIN ms_people ON ms_bookings.book_account=ms_people.p_id", "*", "WHERE book_id='".$_REQUEST['book_id']."' ");

?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

function saveformdata(classname) { 
	var fields = {};
	var stop = false;
	$('#submitButton').text("saving...");
	$('#submitButton').removeClass("submit").addClass("submitsaving");
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.attr("value"); 
			} else { 
				fields[$this.attr('name')] = "";
			}
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}

		if($this.hasClass("required")) { 
			if($this.val() == "") { 
				$this.addClass("requiredFieldEmpty");
				stop = true;
			}
		}
	});
		
	if(stop !== true) { 
		posttourl = $("#formdata").attr("data-post-url");
		$.post(posttourl, fields,	function (data) { 
			// Get the complete function name from the formdata element 
			var completefunction = $("#formdata").attr("data-complete-function");
			if (typeof window[completefunction] === 'function'){
				funok = window[completefunction]();
				e.preventDefault();
			}
			showSuccessMessage("Saved");
			setTimeout(hideSuccessMessage,4000);
			$('#submitButton').text("Save Changes");
			$('#submitButton').removeClass("submitsaving").addClass("submit");

		});
	} else { 
		$('#submitButton').text("Save Changes");
		$('#submitButton').removeClass("submitsaving").addClass("submit");
	}
}


function editbookingcomplete() { 
	getCalendar($("#bookingcalendar").attr("data-month"),$("#bookingcalendar").attr("data-year"));
	viewday($("#book_id").val(),0,'','','',0,1);
	bookingemailinvoice('0','<?php print $book['book_id'];?>');

}
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#prod_name").focus();
 }

</script>
<?php

if($_POST['submitit']=="yes") { 
	$sub_total = $_REQUEST['prod_qty'] * $_REQUEST['prod_price'];
	$order_total = $sub_total + $_REQUEST['tax_amount'] + $_REQUEST['vat_amount'];

	$order_id = insertSQL("ms_orders", "order_date='".$_REQUEST['order_date']."', order_first_name='".addslashes(stripslashes($_REQUEST['order_first_name']))."', order_business_name='".addslashes(stripslashes($_REQUEST['order_business']))."', order_ship_business='".addslashes(stripslashes($_REQUEST['order_ship_business']))."', order_last_name='".addslashes(stripslashes($_REQUEST['order_last_name']))."', order_address='".addslashes(stripslashes($_REQUEST['order_address']))."', order_state='".addslashes(stripslashes($_REQUEST['order_state']))."', order_city='".addslashes(stripslashes($_REQUEST['order_city']))."', order_zip='".addslashes(stripslashes($_REQUEST['order_zip']))."', 
	
	 order_ship_first_name='".addslashes(stripslashes($_REQUEST['order_ship_first_name']))."', order_ship_last_name='".addslashes(stripslashes($_REQUEST['order_ship_last_name']))."', order_ship_address='".addslashes(stripslashes($_REQUEST['order_ship_address']))."', order_ship_state='".addslashes(stripslashes($_REQUEST['order_ship_state']))."', order_ship_city='".addslashes(stripslashes($_REQUEST['order_ship_city']))."', order_ship_zip='".addslashes(stripslashes($_REQUEST['order_ship_zip']))."', order_shipping_option='".$_REQUEST['order_shipping_option']."',
	 order_payment='".$_REQUEST['order_payment']."',
	 order_payment_date = '".$_REQUEST['order_payment_date ']."',
	 order_payment_reference = '".$_REQUEST['order_payment_reference']."',
	 order_pay_type='".$_REQUEST['order_pay_type']."',
	 order_payment_status = '".$order_payment_status."',
	 order_due_date='".$_REQUEST['order_due_date']."',
	 order_booking_confirm='".$_REQUEST['order_booking_confirm']."',
	 order_tax='".$_REQUEST['tax_amount']."', 
	 order_vat='".$_REQUEST['vat_amount']."', 
	 order_taxable_amount='".$_REQUEST['taxable_total']."', order_tax_percentage='".$_REQUEST['tax_percentage']."', order_vat_percentage='".$_REQUEST['vat_percentage']."', order_ship_cost='".$_REQUEST['shipping']."', order_total='".$order_total."', order_invoice='1', order_customer='".$book['book_account']."', order_email='".addslashes(stripslashes($_REQUEST['order_email']))."' , order_phone='".addslashes(stripslashes($_REQUEST['order_phone']))."' , order_sub_total='".$sub_total."', order_shipping='".$_REQUEST['shipping']."' ");
	
	insertSQL("ms_cart", "cart_qty='".$_REQUEST['prod_qty']."', cart_product_name='".addslashes(stripslashes($_REQUEST['prod_name']))."', cart_price='".$_REQUEST['prod_price']."', cart_taxable='".$_REQUEST['tax'][$x]."', cart_order='".$order_id."', cart_invoice='1', cart_booking='".$book['book_id']."' "); 
	$_SESSION['order_id'] = $order_id;
	// updateSQL("ms_bookings", "book_confirmed='2' WHERE book_id='".$_POST['book_id']."' ");
	exit();
}
?>
<?php 
	if($_REQUEST['book_id'] > 0) { 
		$book = doSQL("ms_bookings LEFT JOIN ms_people ON ms_bookings.book_account=ms_people.p_id", "*", "WHERE book_id='".$_REQUEST['book_id']."' ");
	}
	?>
	<div class="pc"><h3>Invoice for <?php print $book['book_first_name']." ".$book['book_last_name'];?> <?php print $book['book_email'];?></h3></div>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">




<?php if(empty($_REQUEST['order_date'])) { 
	$_REQUEST['order_date'] = date('Y-m-d');
}
?>
<?php if(empty($_REQUEST['action'])) { 
	$_REQUEST['order_business'] = $book['p_company'];
	$_REQUEST['order_first_name'] = $book['book_first_name'];
	$_REQUEST['order_last_name'] = $book['book_last_name'];
	$_REQUEST['order_address'] = $book['p_address1'];
	$_REQUEST['order_city'] = $book['p_city'];
	$_REQUEST['order_state'] = $book['p_state'];
	$_REQUEST['order_zip'] = $book['p_zip'];

	$_REQUEST['order_ship_business'] = $book['p_company'];
	$_REQUEST['order_ship_first_name'] = $book['p_name'];
	$_REQUEST['order_ship_last_name'] = $book['p_last_name'];
	$_REQUEST['order_ship_address'] = $book['p_address1'];
	$_REQUEST['order_ship_city'] = $book['p_city'];
	$_REQUEST['order_ship_state'] = $book['p_state'];
	$_REQUEST['order_ship_zip'] = $book['p_zip'];
	$_REQUEST['order_email'] = $book['book_email'];
	$_REQUEST['order_phone'] = $book['book_phone'];

}


	$gtotal = doSQL("ms_orders  LEFT JOIN ms_cart ON ms_orders.order_id=ms_cart.cart_order", "SUM(order_sub_total) AS tot", "WHERE cart_booking='".$book['book_id']."'  AND order_payment_status='Completed' "); 
	$dtotal = doSQL("ms_orders  LEFT JOIN ms_cart ON ms_orders.order_id=ms_cart.cart_order", "SUM(order_discount) AS discount", "WHERE cart_booking='".$book['book_id']."'  AND order_payment_status='Completed' "); 
	if($gtotal['tot'] <= 0) {
		if($book['book_deposit'] > 0) { 
			$amount = $book['book_deposit'];
		} else { 
			$amount = $book['book_total'];
		}
	} else { 
		$amount = $book['book_total'] - $gtotal['tot'] - $gtotal['order_discount'] - $dtotal['discount'];
	} 
	if($amount < 0) { 
		$amount = 0;
	}

?>

	<div class="underline">
		<div class="left" style="margin-right: 24px;">
			<div class="label">Date</div>
			<div><input type="text" id="order_date" name="order_date" size="12" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['order_date']));?>"  class="datepicker center formfield" title="Must be in YYYY-MM-DD format"></div>
		</div>
		<div class="left" style="margin-right: 24px;">
		<?php 
		$due  = date("Y-m-d", mktime(0, 0, 0, date("m") , date('d') + 1, date("Y")));
		?>
			<div class="label">Due Date</div>
			<div><input type="text" name="order_due_date" id="order_due_date" class="optrequired  datepicker center formfield" size="12" value="<?php print $due;?>"></div>
		</div>
		<div class="clear"></div>
	</div>

<?php if($book['book_service'] > 0) { 
	$service = doSQL("ms_calendar", "*", "WHERE date_id='".$book['book_service']."' ");
	$prod_name = "Invoice for: ".$service['date_title'];
} else { 
	$prod_name = $book['book_event_name'];
}
?>
	<div class="underline">
		<div class="left p80">
			<div class="label">For</div>
			<div>
			<input type="hidden" name="prod_qty" id="prod_qty" class="optrequired center formfield" size="2" value="1"> 
			<input type="text" name="prod_name" id="prod_name" class="optrequired field100 formfield inputtitle" value="<?php print htmlspecialchars($prod_name);?>">
			</div>
		</div>
		<div class="left p20 textright">
			<div class="label">Amount</div>
			<div>
				<input type="text" name="prod_price" id="prod_price" class="optrequired center formfield inputtitle" size="6" value="<?php print $amount;?>">
			</div>
		</div>
		<div class="clear"></div>
	</div>


<?php 
	if($service['prod_taxable'] == "1") { 
		$taxable = $amount;
		$zip = doSQL("ms_tax_zips", "*", "WHERE zip='".$book['p_zip']."' ");
		if($zip['tax'] > 0) { 
			$percent = $zip['tax'];
		} else { 
			$per = doSQL("ms_states", "*", "WHERE state_abr='".$book['p_state']."' ");
			$percent = $per['state_tax'];
		}
		$tax = round($taxable * $percent / 100,2);
		$ct = doSQL("ms_countries", "*", "WHERE country_name='".$book['p_country']."' ");
		$vat = $taxable * $ct['vat'] / 100;
	}
	?>
	<input type="hidden" name="taxable_total" class="formfield" value="<?php print $amount;?>">
	<input type="hidden" name="tax_percentage" class="formfield" value="<?php print $percent;?>">
	<input type="hidden" name="vat_percentage" class="formfield" value="<?php print $ct['vat'];?>">


	<div class="underline">
		<div class="left p80">
			&nbsp;
		</div>

		<div class="left p20 textright">
			<div class="label">Tax Amount</div>
			<div>
				<input type="text" name="tax_amount" id="tax_amount" class="center formfield inputtitle" size="6" value="<?php print $tax;?>">
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<?php if(countIt("ms_countries", "WHERE vat>'0' ") > 0) { ?>
	<div class="underline">
		<div class="left p80">
			&nbsp;
		</div>

		<div class="left p20 textright">
			<div class="label">VAT Amount</div>
			<div>
				<input type="text" name="vat_amount" id="vat_amount" class="center formfield inputtitle" size="6" value="<?php print $vat;?>">
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<?php } ?>


<div>


<input type="hidden" name="order_business" id="order_business"  value="<?php print htmlspecialchars($_REQUEST['order_business']); ?>" class="formfield">
<input type="hidden" name="order_first_name" id="order_first_name"  value="<?php print htmlspecialchars($_REQUEST['order_first_name']); ?>" class="formfield required">
<input type="hidden" name="order_last_name"  value="<?php print htmlspecialchars($_REQUEST['order_last_name']); ?>" class="formfield">
<input type="hidden" name="order_address"  value="<?php print htmlspecialchars($_REQUEST['order_address']); ?>" class="formfield">
<input type="hidden" name="order_city"  value="<?php print htmlspecialchars($_REQUEST['order_city']); ?>" class="formfield">
<input type="hidden" name="order_state"  value="<?php print htmlspecialchars($_REQUEST['order_state']); ?>" class="formfield">
<input type="hidden" name="order_zip"  value="<?php print htmlspecialchars($_REQUEST['order_zip']); ?>" size="6" class="formfield">
<input type="hidden" name="order_email"  value="<?php print htmlspecialchars($_REQUEST['order_email']); ?>" size="40" class="formfield">
<input type="hidden" name="order_phone"  value="<?php print htmlspecialchars($_REQUEST['order_phone']); ?>" size="40" class="formfield">

<?php if($book['book_confirmed'] == "1") { ?>
 <div class="underline center">
	<input type="checkbox" name="order_booking_confirm" id="order_booking_confirm" value="1" class="formfield" checked> <label for="order_booking_confirm">Auto confirm booking when payment is made before due date (if unconfirmed).</label>
	</div>
<?php } ?>

	<div>&nbsp;</div>
	<div class="pc buttons center">
	<input type="hidden" name="book_id" id="book_id" class="formfield" value="<?php print $book['book_id'];?>">
	<input type="hidden" name="submitit" value="yes"  class="formfield" >
	<input type="hidden" name="nojs" value="1"  class="formfield" >

	<div id="formdata" data-post-url="booking/booking-invoice.php" data-complete-function="editbookingcomplete"></div>	
	<a href="" id="submitButton" onclick="saveformdata('formfield','formdata'); return false;" class="submit">Generate Invoice</a>
	</div>
<div class="pc center">	<br>After you generate the invoice, you will have the option to email the invoice on the following screen.</div>
<div class="pc center">	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a></div>
</div>
<div class="clear"></div>

	</form>
<?php require "../w-footer.php"; ?>