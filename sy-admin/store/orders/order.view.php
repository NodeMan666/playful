<?php
define("_view_crop_", "View Crop");
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}
$no_trim = true;
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS order_date,date_format(DATE_ADD(order_payment_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']."')  AS order_payment_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date,date_format(DATE_ADD(order_due_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']."')  AS order_due_date_show", "WHERE order_id='".$_REQUEST['orderNum']."' ");
if(empty($order['order_id'])) {
	die("Unable to find order information");
}
if($setup['demo_mode'] == true) { 
	$order['order_first_name'] = get_starred($order['order_first_name']);
	$order['order_last_name'] = get_starred($order['order_last_name']);
	$order['order_email'] = "demo@demo.mode";
	$order['order_ship_first_name'] = get_starred($order['order_ship_first_name']);
	$order['order_ship_last_name'] = get_starred($order['order_ship_last_name']);
}

updateSQL("ms_orders", "order_viewed='1' WHERE order_id='".$order['order_id']."' ");
if($_REQUEST['subdo'] == "deletepaymentcard") { 
	updateSQL("ms_orders", "order_payment_info='' WHERE order_id='".$order['order_id']."' ");
	$_SESSION['sm'] = "Card information deleted";
	header("location: index.php?do=orders&action=viewOrder&orderNum=".$order['order_id']." ");
	session_write_close();
	exit();
}
?>
<div id="photocrop" style=" display: none; background: #FFFFFF; width: 900px; left: 50%; margin-left: -450px; border: solid 1px #949494>; position: absolute; z-index: 200; box-shadow: 0 0 24px rgba(0,0,0,.8);">
<div id="photocropinner" style=" padding: 16px; "></div>
</div>

<script>
function cropphoto(pic,photoprod,cart_id,rotate,change,disable) { 
//	$("#buybackground").fadeIn(50);
	$('html').unbind('click');
	//loading();
	if(!pic) { 
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	}

	$("#photocrop").css({"top":$(window).scrollTop()+50+"px"});
		$.get("<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_photo_crop.php?pid="+pic+"&photoprod="+photoprod+"&cart_id="+cart_id+"&rotate="+rotate+"&change="+change+"&disable="+disable, function(data) {
			$("#photocropinner").html(data);
			$("#photocrop").slideDown(200, function() { 
				$("#closephotocrop").show();
	//			sizeBuyPhoto();
	//			loadingdone();
			});
		});
}

function closecropphoto() { 
	$('html').unbind('click');
	$("#photocrop").slideUp(200, function() { 
		$("#photocropinner").html("");
	});
}
</script>
<div style="float: right;">
<?php
	$next = doSQL("ms_orders", "order_id", "WHERE order_id>'".$order['order_id']."' ORDER BY order_id ASC LIMIT 1 ");
	if(!empty($next['order_id'])) {
		print "<a href=\"index.php?do=orders&action=viewOrder&orderNum=".$next['order_id']."\"><< Newer</a> &nbsp;";
	}
	$prev = doSQL("ms_orders", "order_id", "WHERE order_id<'".$order['order_id']."' ORDER BY order_id DESC LIMIT 1 ");
	if(!empty($prev['order_id'])) {
		print "<a href=\"index.php?do=orders&action=viewOrder&orderNum=".$prev['order_id']."\">Older >></a>";
	}
	?>
</div>
<div class="pc"><a href="index.php?do=orders">&larr; Orders</a></div>
<div class="pc newtitles"><span><?php if($order['order_invoice'] == "1") { ?>Invoice<?php } else { ?>Order<?php } ?> # <?php print $order['order_id']; ?></span></div> 
<div class="clear"></div>
<?php include "order.tabs.php"; ?>
	<div id="roundedFormContain">



<div style="width: 25%; float: left;">
	<div style="padding-right: 32px;">
	<div class="underlinelabel"><span class="left">Order #<?php print "".$order['order_id'].""; ?> <a href="../index.php?view=order&action=orderk&oe=<?php print MD5($order['order_email']);?>&on=<?php print MD5($order['order_id']);?>" target="_blank" class="the-icons icon-globe tip" title="View on website"></a></span><span class="right textright"><?php print showPrice($order['order_total'] + $order['order_credit'] + $order['order_gift_certificate']);?></span><div class="clear"></div></div>	
	<div>
		<div class="underline">
			<div style="width:20%; float: left;">Date</div><div style="width:80%; " class="right textright"><?php print "".$order['order_date'].""; ?></div>
			<div class="cssClear"></div>
		</div>
		<?php if($order['order_due_date'] > 0) { ?>
		<div class="underline">
			<div style="width:20%; float: left;">Due Date</div><div style="width:80%; " class="right textright"><?php print "".$order['order_due_date_show'].""; ?>
			<?php
			 if($order['order_due_date'] > 0) { 
				 if(($order['order_due_date'] < date('Y-m-d')) && (($order['order_payment'] <=0)&&($order['order_total'] > 0)) == true) { ?><span class="unpaid">PAST DUE</span>
				<?php 
				}
			} ?>
			</div>
			<div class="cssClear"></div>
		</div>

		<?php } ?>
		<div class="underline">
			<div class="left">Sub Total</div>
			<div class="right textright"><?php print showPrice($order['order_sub_total']);?></div>
			<div class="clear"></div>
		</div>

		<?php if($order['order_eb_discount'] > 0) { ?>
		<div class="underline">
			<div class="left pc">Early Bird Special</div>
			<div class="right textright pc">(<?php print showPrice($order['order_eb_discount']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>

		<?php if($order['order_discount'] > 0) { ?>
		<div class="underline">
			<div class="left">Discount (<?php print $order['order_coupon_name'];?>)</div>
			<div class="right textright">(<?php print showPrice($order['order_discount']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<div class="underline">
			<div class="left">Tax
			<br>
			<?php print (float)$order['order_tax_percentage']."% on ".showPrice($order['order_taxable_amount']);?>
			</div>
			<div class="right textright"><?php print showPrice($order['order_tax']);?></div>
			<div class="clear"></div>
		</div>

		<?php if($order['order_vat'] > 0) { ?>

		<div class="underline">
			<div class="left"><?php print _vat_;?>
			<br>
			<?php print ($order['order_vat_percentage'] * 1)."% on ".showPrice($order['order_taxable_amount']);?>
			</div>
			<div class="right textright"><?php print showPrice($order['order_vat']);?></div>
			<div class="clear"></div>
		</div>

		<?php } ?>
		<?php if((!empty($order['order_shipping_option']))||($order['order_shipping'] > 0)==true) { ?>		

			<?php if($order['order_ship_pickup'] == "1") { ?>
			<div class="underline">
			<div class="left"><?php print $order['order_shipping_option']?></div>
			<div class="right textright"><?php print showPrice($order['order_shipping']);?></div>
			<div class="clear"></div>
			</div>
			<?php } else { ?>
			<div class="underline">
			<div class="left">Shipping (<?php print $order['order_shipping_option']?>)</div>
			<div class="right textright"><?php print showPrice($order['order_shipping']);?></div>
			<div class="clear"></div>
			</div>
		<?php } ?>
		<?php } ?>
		
		<?php if($order['order_credit'] > 0) { ?>
		<div class="underline">
			<div class="left">Account Credit</div>
			<div class="right textright">(<?php print showPrice($order['order_credit']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>

		<?php if($order['order_gift_certificate'] > 0) { ?>
		<div class="underline">
			<div class="left"><?php print _gift_certificate_name_;?></div>
			<div class="right textright">(<?php print showPrice($order['order_gift_certificate']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>


		<div class="underline">
			<div class="left">Grand Total</div>
			<div class="right textright"><?php print showPrice($order['order_total']);?></div>
			<div class="clear"></div>
		</div>

	</div>
	<div>&nbsp;</div>
	<div class="underlinelabel">Payment</div>	
<script>
function scpayinfo(id) { 
	$("#payment-"+id).slideToggle(200);
	}
</script>

		<?php if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ") > 0) { ?>
		<?php 
		$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");
		while($sc = mysqli_fetch_array($scs)) { ?>

		<div class="pc">
		<div class="left">
		<?php if($sc['payment'] > 0) { ?><a href="" onclick="scpayinfo('<?php print $sc['id'];?>'); return false;"><?php print showPrice($sc['amount']);?></a>
		<?php } else { ?>
		<a href="" onclick="invoiceemailreminder('<?php print $sc['id'];?>'); return false;"  class="the-icons icon-mail tip" title="Email Reminder"></a><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-schedule-payment.php?order_id=<?php print $order['order_id'];?>&id=<?php print $sc['id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;"  class="the-icons icon-plus tip" title="Add Payment"></a><?php print showPrice($sc['amount']);?>
		<?php } ?>
		</div>
		<div class="right textright">
		<?php if($sc['payment'] > 0) { 
		?><span class="green">Paid <?php print $sc['payment_date_show'];?></span><?php } else { ?> Due <?php print $sc['due_date_show']; ?><?php } ?>
		</div>
		<div class="clear"></div>
	
		
		
		<div id="payment-<?php print $sc['id'];?>" class="hide">
				<div class="pc"><?php print $sc['payment_option'];?></div>
				<div class="pc">IP: <?php print $sc['payment_ip'];?></div>

			<div class="pc">
				<div class="left">Payment type</div>
				<div class="right textright"><?php print $sc['payment_type'];?></div>
				<div class="clear"></div>
			</div>

			<?php if(($sc['payment_option'] == "paypalstandard")||($sc['payment_option'] == "paypalpro")||($sc['payment_option'] == "paypalexpress")==true) { ?>
				<div class="pc">
					<div class="left">PayPal Trans. ID </div>
					<div class="right textright"><?php print  "<a href=\"".$site_setup['paypal_trans_link']."".$sc['pay_transaction']."\" target=\"_blank\">".$sc['pay_transaction']."</a>"; ?>&nbsp;</div>
					<div class="clear"></div>
				</div>
				<?php } else if($sc['payment_option'] == "Stripe") { ?>
				<div class="pc">
					<div class="left">Transaction ID </div>
					<div class="right textright"><?php print  "<a href=\"https://manage.stripe.com/payments/".$sc['pay_transaction']."\" target=\"_blank\">".$sc['pay_transaction']."</a>"; ?>&nbsp;</div>
					<div class="clear"></div>
				</div>

				<?php } else { ?>
				<div class="pc">
					<div class="left">Transaction ID </div>
					<div class="right textright"><?php print  "".$sc['pay_transaction'].""; ?>&nbsp;</div>
					<div class="clear"></div>
				</div>
				<div class="pc center"><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-schedule-payment.php?order_id=<?php print $order['order_id'];?>&id=<?php print $sc['id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;"  class="the-icons icon-pencil">edit payment</a></div>
				<?php } ?>
				<div class="cssClear"></div>
			</div>

		</div>

		
		<?php } ?>

		<?php } else { ?>


		<?php if($order['order_payment'] <= 0) { ?>
		<div class="underline center">No payment data</div>
		<?php } else { ?>

		<div class="underline">
			<div style="width:50%; float: left;">Amount </div><div style="width:50%; " class="right textright"><?php print  showPrice($order['order_payment']); ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%; float: left;">Date</div><div style="width:50%; " class="right textright"><?php print  $order['order_payment_date']; ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>

		<div class="underline">
			<div style="width:50%; float: left;">Payment Type </div><div style="width:50%; " class="right textright">
				<?php 
				if($order['order_offline'] == "1") { 
					$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline' ");
					print $payopt['pay_title'];
				} elseif($order['order_offline'] == "2") { 
					$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline2' ");
					print $payopt['pay_title'];
				} else { 
				print  $order['order_pay_type']; 
				}
				?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
		<div class="underline">
			<div style="width:50%; float: left;">Payment Status </div><div style="width:50%; " class="right textright"><?php print  $order['order_payment_status']; ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
<?php if($order['order_pending_reason'] > 0) { ?>
		<div class="underline">
			<div style="width:50%; float: left;">Pending Reason </div><div style="width:50%; " class="right textright"><?php print  $order['order_pending_reason']; ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
<?php } ?>
<?php if(!empty($order['order_payment_reference'])) { ?>
		<div class="underline">
			<div style="width:50%; float: left;">Reference</div><div style="width:50%; " class="right textright"><?php print $order['order_payment_reference'];?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
<?php } ?>

<?php if(!empty($order['order_pay_transaction'])) { ?>
		<div class="underline">
		<?php if(($order['order_payment_option'] == "paypalstandard")||($order['order_payment_option'] == "paypalpro")||($order['order_payment_option'] == "paypalexpress")==true) { ?>
			<div style="width:50%; float: left;">PayPal Trans. ID </div><div style="width:50%; " class="right textright"><?php print  "<a href=\"".$site_setup['paypal_trans_link']."".$order['order_pay_transaction']."\" target=\"_blank\">".$order['order_pay_transaction']."</a>"; ?>&nbsp;</div>
			<?php } else if($order['order_payment_option'] == "Stripe") { ?>
			<div>Transaction ID </div><div><?php print  "<a href=\"https://manage.stripe.com/payments/".$order['order_pay_transaction']."\" target=\"_blank\">".$order['order_pay_transaction']."</a>"; ?>&nbsp;</div>

			<?php } else { ?>
			<div style="width:50%; float: left;">Transaction ID </div><div style="width:50%; " class="right textright"><?php print  "".$order['order_pay_transaction'].""; ?>&nbsp;</div>
			<?php } ?>
			<div class="cssClear"></div>
		</div>
		<div class="underline"><?php print $order['order_payment_option'];?></div>
<?php } ?>
<?php if($order['order_fees'] > 0) { ?>
		<div class="underline">
			<div style="width:50%; float: left;">Fees </div><div style="width:50%; " class="right textright"><?php print  showPrice($order['order_fees']); ?>&nbsp</div>
			<div class="cssClear"></div>
		</div>
<?php } ?>

<?php if($order['order_payer_status'] > 0) { ?>
		<div class="underline">
			<div style="width:50%; float: left;">Payer PayPal Status </div><div style="width:50%; " class="right textright"><?php print  $order['order_payer_status']; ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
<?php } ?>

<?php if($order['order_address_status'] > 0) { ?>
		<div class="underline">
			<div style="width:50%; float: left;">Payer Address Status</div><div style="width:50%; " class="right textright"><?php print  $order['order_address_status']; ?>&nbsp;</div>
			<div class="cssClear"></div>
		</div>
<?php } ?>
<?php if(!empty($order['order_payment_info'])) { ?>
		<div class="underline">
		<?php print nl2br($order['order_payment_info']);?>
		<div><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $order['order_id'];?>&subdo=deletepaymentcard"  class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete this information" >delete this card information</a></div>
		</div>


<?php } ?>
<?php } ?>
<div class="pc right"><a href="" onclick="pagewindowedit('<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/orders/order-add-payment.php?order_id=<?php print $order['order_id'];?>&noclose=1&nofonts=1&nojs=1',''); return false;">Edit Payment</a></div>
<div class="clear"></div>
<?php } ?>
<div>&nbsp;</div>
<div class="underlinelabel">Expenses</div>	
	<div>
	<?php $exps = whileSQL("ms_expenses", "*", "WHERE exp_order='".$order['order_id']."' ORDER BY exp_id ASC ");
	if(mysqli_num_rows($exps) <=0) { ?>
		<div class="row center">None entered</div>
		<?php }

	while($exp = mysqli_fetch_array($exps)) { ?>
<div class="underline">
	<div class="left p50"><a href="" onclick="editexpense('<?php print $exp['exp_id'];?>'); return false;"><?php print showPrice($exp['exp_amount']);?></a></div>
	<div class="left p50 textright">
	<a href="" onclick="editexpense('<?php print $exp['exp_id'];?>'); return false;"><?php print ai_edit;?></a> <a  id="deleteexpense" class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to delete this expense?" href="index.php?do=reports&action=deleteExpense&exp_id=<?php print $exp['exp_id'];?>&year=<?php print $_REQUEST['year'];?>&tag_id=<?php print $thistag['tag_id'];?>" ><?php print ai_delete;?></a></div>

	<div class="clear"></div>
</div>
	<?php } ?>
</div>
<div class="pc textright"><a href="" onclick="editexpense('','<?php print $order['order_id'];?>'); return false;">Enter Expense</a></div>
<div>&nbsp;</div>





	<div class="notes">
	<h2>NOTES</h2>
	<div  contenteditable id="order_note"  onClick="removeNoNotes();" name="order_note" style="min-height: 30px;" order_id="<?php print $order['order_id'];?>" message="Click here to enter notes"><?php if(empty($order['order_admin_notes'])) { print "<span id=\"nonotes\"><i>Click here to enter notes.</i></span>"; } else { print $order['order_admin_notes']; } ?></div>
	</div>
	<div class="pc" style="height: 16px;">
		<div class="left" id="noteloading" style="display: none;"><img src="graphics/loading2.gif"></div>
		<div class="left" id="noteupdated" style="display: none;">Updated</div>
		<div class="right textright" id="updatenote"><a href="" onClick="addnotes('<?php print $order['order_id'];?>'); return false;">update</a></div>
		<div class="clear"></div>
		</div>

	</div>



</div>





<div style="width: 75%; float: left;">
	<?php if(!empty($order['order_notes'])) { ?>
	<div class="pc"  style="padding: 0px 16px;">
	<div class="pc"><?php print ai_message;?><b>This order has a message from the customer</b></div>
	<div class="highlight" style="padding: 16px;"><?php print nl2br($order['order_notes']);?></div>
	</div>

	<?php } ?>
	<div style="width: 50%; float: left;">
		<div style="padding: 0px 16px;">
	<div class="underlinelabel">
	<div class="left">Customer</div>
	<div class="right textright">
	<?php if($order['order_customer'] > 0) { ?>
	<a href="index.php?do=people&p_id=<?php print $order['order_customer'];?>"><span class="the-icons icon-user"></span>View account</a>
	<?php } ?>

	</div>
	<div class="clear"></div>
	</div>	
	<div class="infoboxes">
		<div class="infoinner">
	<?php if(!empty($order['order_business_name'])) { ?>
			<?php print "".$order['order_business_name'].""; ?><br>
<?php } ?>
		
<?php if((empty($order['order_first_name']))AND(empty($order['order_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print  "".$order['order_first_name']." ".$order['order_last_name'].""; ?><?php  } ?>
		<br>
<?php if(!empty($order['order_address'])) { ?>
			<?php print "".$order['order_address'].""; ?><br>
<?php } ?>
<?php if(!empty($order['order_city'])) { ?>
			<?php if(!empty($order['order_city'])) { ?><?php print "".$order['order_city'].""; ?>, <?php } ?>
			<?php if(!empty($order['order_state'])) { ?> <?php print "".$order['order_state'].""; ?><?php } ?>
			<?php if(!empty($order['order_zip'])) { ?><?php print "".$order['order_zip'].""; ?><?php } ?>

<?php } ?>
<?php if(!empty($order['order_country'])) { ?>
			<?php print "".$order['order_country'].""; ?>
<?php } ?>


</div>
</div>

<div class="pc">
<a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?>&email_to_first_name=<?php print addslashes($order['order_first_name']);?>&email_to_last_name=<?php print addslashes($order['order_last_name']);?>&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip"><span class=" the-icons icon-mail"></span><?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; }  ?></a> &nbsp; 

<?php if(!empty($order['order_phone'])) { ?>
	<a href="tel:<?php print $order['order_phone']; ?>"><span class="the-icons icon-phone"></span><?php print $order['order_phone']; ?></a> &nbsp; 

<?php } ?>
<?php if(!empty($order['order_ip'])) { ?>IP: <a href="index.php?do=stats&action=recentVisitors&q=<?php print $order['order_ip'];?>"><?php print "".$order['order_ip'].""; ?></a><?php } ?>
</div>
		<?php showOrderExtraFields($order); ?>
	</div>
	</div>


	<div style="width: 50%; float: left;">
		<div style="padding: 0px 16px;">
		<?php if(!empty($order['order_shipping_option'])) { ?>		
	<?php if($order['order_ship_pickup'] == "1") { ?>
			<div class="underlinelabel"><?php print $order['order_shipping_option'];?></div>
		<?php } else { ?>

			<div class="underlinelabel">Shipping - <?php print $order['order_shipping_option'];?></div>	
			<div class="infoboxes">
				<div class="infoinner">

	<?php if(!empty($order['order_ship_business'])) { ?>
			<?php print "".$order['order_ship_business'].""; ?><br>
	<?php } ?>
					
				<?php if((empty($order['order_ship_first_name']))AND(empty($order['order_ship_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print  "".$order['order_ship_first_name']." ".$order['order_ship_last_name'].""; ?><?php  } ?><br>
				<?php if(!empty($order['order_ship_address'])) { ?>
							<?php print "".$order['order_ship_address'].""; ?><br>
				<?php } ?>
				<?php if(!empty($order['order_ship_city'])) { ?>
							<?php if(!empty($order['order_ship_city'])) { ?><?php print "".$order['order_ship_city'].""; ?>, <?php } ?> 
							<?php if(!empty($order['order_ship_state'])) { ?> <?php print "".$order['order_ship_state'].""; ?> <?php } ?>
							<?php if(!empty($order['order_ship_zip'])) { ?><?php print "".$order['order_ship_zip'].""; ?><?php } ?>
				<?php } ?>
				<?php if(!empty($order['order_ship_country'])) { ?>
							<?php print "".$order['order_ship_country'].""; ?>
				<?php } ?>

			</div>
		</div>
			<?php if(!empty($order['order_shipped_by'])) { 
					if($order['order_shipped_by_id'] > 0) { 
						$shipped = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$order['order_shipped_by_id']."' ");
					}
						?>
					<div class="underline">Shipped <?php print $order['order_shipped_date'];?> by <?php print $order['order_shipped_by'];?></div>
					<?php if(!empty($order['order_shipped_track'])) { ?>
					<div class="underline">
					<?php  if(!empty($shipped['ship_track'])) { ?>
					<a href="<?php print $shipped['ship_track'];?><?php print $order['order_shipped_track'];?>" target="_blank"><?php print $order['order_shipped_track'];?></a>
					<?php } else { ?>
					<?php print $order['order_shipped_track'];?>
					<?php } ?>
					</div>
					<?php if($order['order_ship_cost'] > 0) { ?>
					<div class="underline"><?php print showPrice($order['order_ship_cost']);?></div>
					<?php } ?>
					<?php } ?>

			<?php } ?>
			<?php } ?>
		<?php } ?>




		</div>
	</div>
	<div class="clear"></div>



	<?php 
	if($order['order_archive_table'] == "1") { 
		define(cart_table,"ms_cart_archive");
	} else { 
		define(cart_table,"ms_cart");
	}
	?>

	<div class="rowspacer">&nbsp;</div>
		<div style="padding: 0px 16px;">
			<div class="underlinelabel"><span class="left">Products</span>
				<span class="right">
					<?php
					$downs = whileSQL(cart_table, "*,SUM(cart_qty) AS total", "WHERE cart_order='".$order['order_id']."' AND cart_photo_prod>'0' AND cart_download='1'  AND cart_pic_id>'0' GROUP BY cart_download "); 
					while($down = mysqli_fetch_array($downs)) { 
						if($down['total'] > 0) { ?>
					 Downloads: <?php print $down['total'] * 1;?> &nbsp;
					<?php
						$dt = $down['total'];
						}
					}
					?>
					<?php
					$prints = whileSQL(cart_table, "*,SUM(cart_qty) AS total", "WHERE cart_order='".$order['order_id']."' AND cart_photo_prod>'0' AND cart_download='0'  AND cart_pic_id>'0' GROUP BY cart_download "); 
					while($print = mysqli_fetch_array($prints)) { 
						if($print['total'] > 0) { ?>
					Prints: <?php print $print['total'] * 1;?> &nbsp; 
					<?php
						$pt = $print['total'];
						} 
					}
					if((($dt * 1)+ ($pt * 1)) > 1) { 
						$unip = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_pic_id>'0'  GROUP BY cart_pic_id ");
						if(mysqli_num_rows($unip) > 0) { 
							print "Unique Images: ".mysqli_num_rows($unip);
						}
					}

					?>
				</span>
				<div class="clear"></div>
			</div>	
		<div>
			<div class="label">
				<div style="width: 50%;" class="left">&nbsp;</div>
				<div style="width: 20%;" class="left textright">Price</div>
				<div style="width: 10%;" class="left  textright">Qty</div>
				<div style="width: 20%;" class="left textright">Extended</div>
				<div class="clear"></div>
			</div>
	<?php

$carts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND cart_package!='0'  ORDER BY cart_package_no_select DESC, cart_id ASC" );
$tracks_total	= mysqli_num_rows($carts);
while($cart= mysqli_fetch_array($carts)) {
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	$tracknum++;
	showOrderPackage($pack,$cart);
	?>

	<?php 
	$pcarts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'    ORDER BY cart_pic_org ASC" );
	if(mysqli_num_rows($pcarts) > 0) { ?>
	<div class="pc">Below are the selected photos for this package.</div>
	<?php 
	}

		while($pcart= mysqli_fetch_array($pcarts)) {
		$tracknum++;
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
		if($pcart['cart_pic_id'] > 0) { 
			showOrderPhoto($pcart,"1",$cart,$order);
		}
	}

}


	$carts = whileSQL(cart_table, "*", "WHERE cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_package_photo='0' AND cart_coupon='0'  ORDER BY cart_pic_org ASC " );
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
		$tracknum++;
		showOrderPhoto($cart,"0","",$order);
	}
	?>


	<?php

	$carts = whileSQL(cart_table, "*", "WHERE cart_store_product!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id DESC  " );
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
		$tracknum++;
		showOrderProduct($date,$cart);
		$pcarts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."'  AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  ORDER BY cart_pic_org ASC" );
		if(mysqli_num_rows($pcarts) > 0) { 
			?>	<div class="pc">Photos selected for the above product.</div>
			<?php 
			}
			while($pcart= mysqli_fetch_array($pcarts)) {
				$tracknum++;
				showOrderPhoto($pcart,"1",$cart,$order);
			}
		if(mysqli_num_rows($pcarts) > 0) { 
			print "<div>&nbsp;</div><div>&nbsp;</div>";
		}
	}
	?>

	<?php

	$carts = whileSQL(cart_table, "*", "WHERE cart_invoice='1' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id ASC  " );
		while($cart= mysqli_fetch_array($carts)) {
		showInvoiceItem($cart);
	}

	## Gift certificates

	$carts = whileSQL("ms_cart", "*", "WHERE  cart_gift_certificate='1'  AND cart_order='".$order['order_id']."'  ORDER BY cart_id DESC" );
	while($cart= mysqli_fetch_array($carts)) {
	?>
	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 50%;" class="left">

			<div class="left">
				<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
				<div class="pc">From: <?php print $cart['cart_gift_certificate_to_name'];?> (<?php print $cart['cart_gift_certificate_to_email'];?>)</div>
				<div class="pc">To: <?php print $cart['cart_gift_certificate_from_name'];?> (<?php print $cart['cart_gift_certificate_from_email'];?>)</div>
				<div class="pc"><?php 
				if(!empty($cart['cart_gift_certificate_message'])) { print "<i>".nl2br($cart['cart_gift_certificate_message'])."</i>"; } ?>
			</div><div>
			</div>
			</div>
			<div class="clear"></div>
		</div>
			<div style="width: 20%" class="left textright"><?php print showPrice($cart['cart_price']);?></div>
			<div style="width: 10%" class="left textright"><?php print ($cart['cart_qty'] + 0);?></div>
			<div style="width: 20%" class="left textright"><?php print showPrice($cart['cart_qty'] * $cart['cart_price']);?></div>
			<div class="clear"></div>
			<div class="pc"><a href="" onclick="editorderitem('<?php print $cart['cart_id'];?>','<?php print $cart['cart_order'];?>'); return false;">edit</a></div>

		</div>



	<?php
	}

	$carts = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name  ORDER BY co_id ASC");
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
		showOrderImageOptions($cart, "0",$order);
	}

	?>


</div>
</div>



</div>


<div class="clear"></div>
</div>



<?php 
function showInvoiceItem($cart) {
	global $setup,$site_setup,$zip_total,$no_trim;
	$action = "viewcart";
	$price = productPrice($date);
	$this_price = $cart['cart_price'];
	?>

	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 50%;" class="left">

			<div class="left">
				<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
				<?php if(!empty($sku)) { ?>
				<div class="pc">#<?php print $sku;?></div>
				<?php } ?>
				<div>
			</div>
			</div>
			<div class="clear"></div>
		</div>
			<div style="width: 20%" class="left textright"><?php print showPrice($this_price);?></div>
			<div style="width: 10%" class="left textright"><?php print ($cart['cart_qty'] + 0);?></div>
			<div style="width: 20%" class="left textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></div>
			<div class="clear"></div>
			<div class="pc"><a href="" onclick="editorderitem('<?php print $cart['cart_id'];?>','<?php print $cart['cart_order'];?>'); return false;">edit</a></div>

		</div>

	
<?php } ?>

<?php 
function showOrderProduct($date,$cart) {
	global $setup,$site_setup,$zip_total,$no_trim;
	$action = "viewcart";
	$this_price = $cart['cart_price'];
	if(!empty($date['prod_prod_id'])) { 
		$sku = $date['prod_prod_id'];
	}
	if(!empty($cart['cart_sub_id'])) {
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
		$this_price = $this_price + $sub['sub_add_price'];
		if(!empty($sub['sub_sku'])) { 
			$sku = $sub['sub_sku'];
		}
	}

	?>

	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 50%;" class="left">
			<?php if($date['date_id'] > 0) { ?>

			<?php if($sub['sub_pic_id'] > 0) { 
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
				if($pic['pic_id'] > 0) { ?>
					<div class="left" style="margin-right: 8px; width: 30%; text-align: center;" ><img src="<?php print getimagefile($pic,'pic_mini');?>">	</div>
			<?php 
				}
			
			} else { ?>

			<div class="left" style="margin-right: 8px; width: 30%; text-align: center;"><?php print getPagePreview($date,"mini"); ?></div>
			<?php } ?>

			<?php } ?>

			<div class="left">
				<div class="pc">
					<?php if($cart['cart_account_credit_for'] > 0) { ?><h3><?php print _registry_purchase_; ?></h3><?php } ?>
				<?php if($cart['cart_paid_access'] > 0) { ?><h3><?php print _access_to_;?></h3><?php } ?>
			
				<h3><?php print $cart['cart_product_name'];?></h3></div>
			<?php if($cart['cart_pre_reg'] > 0) { ?>
			<div class="pc">
			<?php 
			$pdate = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pre_reg']."' ");
			print "<a href=\"index.php?do=news&action=addDate&date_id=".$pdate['date_id']."\">".$pdate['date_title']."</a>";
			?>
			</div>
			<?php } ?>

				<?php if(!empty($sku)) { ?>
				<div class="pc">#<?php print $sku;?></div>
				<?php } ?>
				<?php if(($cart['cart_account_credit'] > 0) && ($cart['cart_account_credit_for'] <=0)==true) { ?>
				<div class="pc"><?php print _includes_." ".showPrice($cart['cart_account_credit'])." "._credit_;?></div>
				<?php } ?>
				<?php if($cart['cart_account_credit_for'] > 0) { ?>
				<div class="pc"><?php if(!empty($cart['cart_reg_message_name'])) { print "<b>".$cart['cart_reg_message_name']."</b><br>"; } 
				if(!empty($cart['cart_reg_message'])) { print "<i>".nl2br($cart['cart_reg_message'])."</i>"; } ?>
				</div>
				<?php } ?>

				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; 

				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					

				$this_price = $this_price + $co['co_price']; ?></div>
				<?php } ?>

				<div>
				<?php 
				if(!empty($date['prod_opt1'])) { 
					print "<div class=\"pc\">".$date['prod_opt1'].": ".$sub['opt1_value']."</div>";
				}
				if(!empty($date['prod_opt2'])) { 
					print "<div class=\"pc\">".$date['prod_opt2'].": ".$sub['opt2_value']."</div>";
				}

				if(!empty($date['prod_opt3'])) { 
					print "<div class=\"pc\">".$date['prod_opt3'].": ".$sub['opt3_value']."</div>";
				}

				if(!empty($date['prod_opt4'])) { 
					print "<div class=\"pc\">".$date['prod_opt4'].": ".$sub['opt4_value']."</div>";
				}

				if(!empty($date['prod_opt5'])) { 
					print "<div class=\"pc\">".$date['prod_opt5'].": ".$sub['opt5_value']."</div>";
				}
			?>
			</div>
			<?php prodDownloads($cart); ?>
			</div>
			<div class="clear"></div>
		</div>
			<div style="width: 20%" class="left textright"><?php print showPrice($this_price);?></div>
			<div style="width: 10%" class="left textright"><?php print ($cart['cart_qty'] + 0);?></div>
			<div style="width: 20%" class="left textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></div>
			<div class="clear"></div>
			<?php	
				if(!empty($cart['cart_reg_key'])) { ?>
			<div class="pc">Registration key: &nbsp; <pre style="display: inline;"><?php print $cart['cart_reg_key'];?></pre></div>
			<?php } ?>
			<div class="pc"><a href="" onclick="editorderitem('<?php print $cart['cart_id'];?>','<?php print $cart['cart_order'];?>'); return false;">edit</a></div>
		</div>

	
<?php } ?>
<?php

function showOrderImageOptions($cart,$package_photo,$order) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['this_price'];
	$this_price = $cart['this_price'];

	
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['co_pic_id']."' ");
	?>
	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">
		<div class="left" style="width: 50%"><h3><?php print $cart['co_opt_name'];?></h3></div>
		<div style="width: 20%" class="left textright">&nbsp;</div>
		<div style="width: 10%" class="left textright"><?php print ($cart['total_items'] + 0);?></div>
		<div style="width: 20%" class="left textright"><?php print showPrice($this_price);?></div>
		<div class="clear"></div>	
	</div>
<?php } 

function showOrderPhoto($cart,$package_photo,$parent,$order) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['cart_price'];
	$this_price = $cart['cart_price'];
	if(!empty($cart['cart_sub_id'])) {
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
		$this_price = $this_price + $sub['sub_add_price'];
	}
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
	if($cart['cart_package_photo'] > 0) { 
		$pcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	}
	if($cart['cart_product_photo'] > 0) { 
		$pcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$pcart['cart_store_product']."' ");
	}

	?>

	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 50%;" class="left">

			<div class="left" style=" width: 40%; text-align: center;">

			<?php if($pic['pic_id'] > 0) { ?>
				<?php if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { ?>
					<div id="ct-<?php print $cart['cart_id'];?>">
					<?php cropphotoview($cart,$pic,$prod,"pic_th",'1'); ?>
					<div style="background: #ffff00;">Custom Crop</div>
					</div>
					<?php } else { 
						$size = getimagefiledems($pic,'pic_th');
						if(!empty($cart['cart_photo_bg'])) { 
							$bg = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_photo_bg']."' ");
						}
					if(!empty($cart['cart_thumb'])) { 
					$size = @GetImageSize("".$setup['path']."/".$cart['cart_thumb']); 
					?>
					<img src="<?php print $setup['temp_url_folder'];?>/<?php print $cart['cart_thumb'];?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
						<?php } else { ?>
						<div>
						<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" width="<?php print $size[0];?>" height="<?php print $size[1];?>">
						</div>
						<?php } ?>
						<?php if($prod['pp_width'] > 0) { ?>
						<div>No custom crop</div>
						<?php } ?>
					<?php } ?>
				

				<?php } else { ?>
				File not found
				<?php } ?>
			</div>


			<div class="left" style="width: 60%; ">
			<div style="padding: 0 0 0 8px ;">
			<?php 
				if($cart['cart_frame_size'] > 0) { 
					$wset = doSQL("ms_wall_language", "*","");
					$wall_settings = doSQL("ms_wall_settings","*","");
						$frame = doSQL("ms_frame_sizes LEFT JOIN ms_frame_styles ON ms_frame_sizes.frame_style=ms_frame_styles.style_id", "*", "WHERE frame_id='".$cart['cart_frame_size']."' ");
						$color = doSQL("ms_frame_images", "*", "WHERE img_id='".$cart['cart_frame_image']."' ");
						?>
						<div class="pc"><h3><?php print ($frame['frame_width'] * 1)." x ".($frame['frame_height'] * 1)." ".$frame['style_name'];?></h3></div>
						<?php if($cart['cart_mat_size'] > 0) { ?><div class="pc"><?php print ($frame['frame_mat_print_width'] * 1)." x ".($frame['frame_mat_print_height'] * 1);?> print</div><?php } ?>

						<div>
						<?php
							if(empty($color['img_corners'])) { 
								$corners = $frame['style_frame_corners'];
							} else { 
								$corners = $color['img_corners'];
							}

							$bgsizes = explode(",",$corners);
						?>
						<div class="pc"><span  style="height: 20px; width: 20px; background-image: url('<?php print $setup['temp_url_folder'].$color['img_small'];?>'); background-size: <?php print (100 / $bgsizes[0]) * 100;?>%; display: inline-block">&nbsp;</span> <?php print $color['img_color'];?></div>
						<?php if($cart['cart_mat_size'] > 0) { ?>
						<div class="pc"> <?php print $wset['_wd_matting_'];?>  <?php print $cart['cart_mat_size'] * 1;?><?php print $wall_settings['size_symbol'];?> <span  id="matcolor-<?php print $style['style_id'];?>-<?php print $mat['mat_id'];?>" class="matcolorselections" style="width: 20px; height: 20px; display: inline-block; border: solid 1px #d4d4d4; background: #<?php print $cart['cart_mat_color'];?>;">&nbsp;</span> 

						<?php 
						$matcolor = doSQL("ms_frame_mat_colors", "*", "WHERE color_color='".$cart['cart_mat_color']."' ");
						print $matcolor['color_name'];
						?>
						</div>
						<?php } ?>

					</div>
					<?php } else {	?>

			<?php if($cart['cart_package_photo'] > 0) { ?><div class="pc"><?php print $parent['cart_product_name'];?></div><?php } ?>
			<?php if($cart['cart_product_photo'] > 0) { ?><div class="pc"><?php print $pdate['date_title'];?></div><?php } ?>
				<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
				<?php } ?>
					<?php if($cart['cart_room_view'] > 0) { 
					$wset = doSQL("ms_wall_language", "_wd_wall_designer_tab_","");
					$rv  = doSQL("ms_wall_saves", "*", "WHERE wall_id='".$cart['cart_room_view']."' ");
					?>
					<div class="pc"><a href="<?php print $setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/?view=room&rw=<?php print $rv['wall_link'];?>" target="_blank"><b>Wall Designer</b></a><br>View the wall designer for orientation and any cropping.</div>
					<?php } ?>

				<?php $cos = whileSQL(cart_table."  LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) {
				$option_price = $co['co_price'];
					?>
				<div class="pc">
				<?php print $co['co_opt_name']." "._selected_; ?>
				</div>
				<?php } ?>




				<?php if(!empty($cart['cart_sku'])) { ?><div class="pc"><?php print $cart['cart_sku'];?></div><?php } ?>
				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0' ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; 
				
					if($co['co_price'] > 0) { 
						print " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						print " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					


					if($co['co_download'] <=0) { 
						$this_price = $this_price + $co['co_price']; 
					}
					if($co['co_download'] == "1") { 
						$co_download = $co['co_price'];	
					}

					
					 ?></div>
				<?php } ?>

			<?php if($cart['cart_color_id'] > 0) { ?>
				<div class="pc">
				<?php print $cart['cart_color_name'];?>
				</div>
			<?php } ?>
				<div class="pc"><b><?php print $cart['cart_pic_org'];?></b></div>
				<?php if(!empty($bg['pic_id'])) { ?><div class="pc"><b>Background: <?php print $bg['pic_org'];?></b></div><?php } ?>
				<div class="pc">In 
				<?php 
				if(!empty($date['cat_under_ids'])) { 
				$scats = explode(",",$date['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print "<a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> > ";
				}
			}
				print "<a href=\"index.php?do=news&date_cat=".$date['cat_id']."\">".$date['cat_name']."</a> > ";
				print "<a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."\">".$date['date_title']."</a>";
				if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							print " > <a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$upsub['sub_id']."\">".$upsub['sub_name']."</a>  ";
						}
					}
					print " > <a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$sub['sub_id']."\">".$sub['sub_name']."</a>";
				}
				?>
				</div>
				<?php if(!empty($cart['cart_notes'])) { ?>
				<div class="pc highlight"><span><?php print ai_message;?> <?php print nl2br($cart['cart_notes']);?></span></div>
				<?php } ?>

			
				<?php prodDownloads($cart); ?>
		
			</div>
			<div class="clear"></div>

		</div>
		</div>


			<div style="width: 20%" class="left textright"><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice($this_price);?><?php } ?></div>
			<div style="width: 10%" class="left textright"><?php print ($cart['cart_qty'] + 0);?></div>
			<div style="width: 20%" class="left textright"><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice(($cart['cart_qty'] * $this_price) + $co_download);?><?php } ?></div>
			<div class="clear"></div>
			<div class="pc"><a href="" onclick="editorderitem('<?php print $cart['cart_id'];?>','<?php print $cart['cart_order'];?>'); return false;">edit</a></div>
		</div>

<?php } ?>


<?php 
function showOrderPackage($pack,$cart) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['cart_price'];
	$this_price = $cart['cart_price'];


	?>

	<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">
		<div style="width: 50%;" class="left">
			<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
				<?php if(!empty($cart['cart_sku'])) { ?><div class="pc"><?php print $cart['cart_sku'];?></div><?php } ?>

				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."'  ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; 
				
				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					

				
				$this_price = $this_price + $co['co_price'];  ?></div>
				<?php } ?>


				<?php if($cart['cart_package_buy_all'] == "1") { ?>
			<?php } else { ?>
			<div class="pc"><?php print countIt(cart_table, "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ")." of ".countIt(cart_table, "WHERE cart_package_photo='".$cart['cart_id']."' ")." selected"; ?></div>

		<?php } ?>

		<?php if($cart['cart_credit'] > 0) { ?>
		<div class="pc"><b><?php print showPrice($cart['cart_credit'])."  credit included to use toward purchasing other photos.</b>"; ?></div>
		<?php } ?>

		</div>
		<div style="width: 20%" class="left textright"><?php if($this_price > 0) {  print showPrice($this_price); } ?></div>
		<div style="width: 10%" class="left textright"><?php print ($cart['cart_qty'] + 0);?></div>
		<div style="width: 20%" class="left textright"><?php if($this_price > 0) { print showPrice($cart['cart_qty'] * $this_price); } ?></div>
		<div class="clear"></div>
			<div class="pc"><a href="" onclick="editorderitem('<?php print $cart['cart_id'];?>','<?php print $cart['cart_order'];?>'); return false;">edit</a></div>
	</div>
<?php } ?>



<?php function prodDownloads($cart) { 
	if(!empty($cart['cart_download_log'])) { 
		print "<div style=\"margin: 0 0 0 20px;\">";
		print "<div><b>Downloads</b></div>";
		$dls = explode("\r\n",$cart['cart_download_log']);
		foreach($dls AS $dl) { 
			$dl = trim($dl);
			if(!empty($dl)) { 
				$d = explode("|",$dl);
				print "<div>".$d[0]." - ".$d[1]."</div>";
			}
		}
		print "</div>";
	}
}
?>