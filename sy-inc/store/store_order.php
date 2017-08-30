<?php
// print "<pre>"; print_r($_POST); print "</pre>"; 

$my_order_page = true;
/*
if(!empty($_REQUEST['myOrder'])) {
	unset($_SESSION['my_order_id']);
	session_write_close();
	header("location: ".$site_setup['index_page']."");
	exit();
}
*/
$no_trim = true;
if(!empty($_REQUEST['msos'])) {
	if(!is_numeric($_REQUEST['msos'])) { die("Sorry, something is wrong with the order numbers"); }
}
if(!empty($_REQUEST['msok'])) {
	if(!is_numeric($_REQUEST['msok'])) { die("Sorry, something is wrong with the order key  numbers"); }
}
if((!empty($_REQUEST['msos']))&&(!empty($_REQUEST['msok']))&&($_REQUEST['frompp'] == "1") == true) {
	$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE order_session='".$_REQUEST['msos']."' AND order_key='".$_REQUEST['msok']."' ");
	if(!empty($order['order_id'])) {
		$_SESSION['my_order_id'] = $order['order_id'];
		$_SESSION['payment_complete'] = true;
		session_write_close();
		header("location: ".$site_setup['index_page']."?view=order");
		exit();
	} else { 
	?>
	<div class="pc center"><h1>Delay Notice</h1></div>
	<div class="pc center">There appears to be a slight delay receiving payment notification. Wait a few seconds and click the refresh button below</div>
	<div class="pc center"><a href="<?php print $site_setup['index_page'];?>?view=order&msos=<?php print $_REQUEST['msos'];?>&msok=<?php print $_REQUEST['msok'];?>&frompp=1" class="checkout">Refresh</a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<?php 

	}
}

if($_REQUEST['action'] == "new") { 
	unset($_SESSION['my_order_id']);
	session_write_close();
	header("location: ".$site_setup['index_page']."?view=order");
	exit();
}

if($_REQUEST['action'] == "orderk") { 
	$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE MD5(order_id)='".sql_safe($_REQUEST['on'])."' AND MD5(order_email)='".sql_safe($_REQUEST['oe'])."' ");
	if(empty($order['order_id'])) { 
		print "<div class=\"error\">"._store_order_can_not_find_."</div>";
	} else { 
		$_SESSION['my_order_id'] = $order['order_id'];
	session_write_close();
	header("location: ".$site_setup['index_page']."?view=order");
	exit();
	}
}

if($_REQUEST['action'] == "findOrder") { 
	$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".sql_safe($_REQUEST['orderNumber'])."' AND order_email='".sql_safe($_REQUEST['orderEmail'])."' AND order_zip='".sql_safe($_REQUEST['orderZip'])."'");
	if(empty($order['order_id'])) { 
		print "<div class=\"error\">"._store_order_can_not_find_."</div>";
	} else { 
		$_SESSION['my_order_id'] = $order['order_id'];
	}
}
if(!empty($_SESSION['my_order_id'])) { 
	$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date,date_format(DATE_ADD(order_due_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_due_date_show", "WHERE order_id='".$_SESSION['my_order_id']."' ");
	if(empty($order['order_id'])) {
//		print "<div class=\"pageContent\">"._store_order_can_not_find_."</div>";
	}
}

if((!empty($_REQUEST['myorder']))&&(isset($_SESSION['pid']))==true) { 
	if(!is_numeric($_REQUEST['myorder'])) { 	die(); }
	$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date,date_format(DATE_ADD(order_due_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_due_date_show", "WHERE order_id='".$_REQUEST['myorder']."' AND MD5(order_customer)='".$_SESSION['pid']."' ");
	if($order['order_id'] <=0) { print "<div class=\"error\">"._unable_to_find_order_."</div>"; } else { $_SESSION['my_order_id'] = $order['order_id']; } 
	
}


?>

<?php if(empty($order['order_id'])) { ?>
<div class="pageContent"><h1><?php print _store_find_order_; ?></h1></div>
<div class="pageContent"><?php 
$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_store_find_order_text_);
$message = str_replace('<a href=/index.php?view=account>','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',$message);
print $message ;				

?></div>

<div id="orderLogin">
<form method="post" name="order" action="<?php print $site_setup['index_page'];?>" id="order">
<input type="hidden" name="view" value="order">
<input type="hidden" name="action" value="findOrder">
<div class="pageContent">
<div><?php print _store_find_order_number_;?></div>
<div><input type="text" name="orderNumber" id="orderNumber" size="20"></div>
</div>

<div class="pageContent">
<div><?php print _store_find_order_email_;?></div>
<div><input type="text" name="orderEmail" id="orderEmail" size="40"></div>
</div>
<div class="pageContent">
<div><?php print _store_find_order_zip_;?></div>
<div><input type="text" name="orderZip" id="orderZip" size="10"></div>
</div>

<div class="pageContent center">
<div><input type="submit" name="submit" id="submot" value="<?php print _store_find_order_button_;?>" class="submit"></div>
</div>
</form>

</div>


<?php } ?>

<?php 	
if(!empty($order['order_id'])) { 
	if($order['order_archive_table'] == "1") { 
		define(cart_table,"ms_cart_archive");
	} else { 
		define(cart_table,"ms_cart");
	}
	$downloads = countIt(cart_table,  "WHERE  cart_order='".$order['order_id']."' AND cart_coupon='0' AND cart_download='1' ");
	$ships = countIt(cart_table, "WHERE  cart_order='".$order['order_id']."' AND cart_coupon='0' AND cart_ship='1' ");
	$services = countIt(cart_table, "WHERE  cart_order='".$order['order_id']."' AND cart_coupon='0' AND cart_service='1' ");
	$photodownloads= countIt(cart_table, "WHERE  cart_order='".$order['order_id']."' AND cart_coupon='0' AND cart_download='1' AND cart_pic_id>'0'  AND cart_disable_download<='0' ");

	?>
	<?php if((($order['order_payment_status'] !== "Completed")&&($order['order_offline'] > 0))OR(($order['order_payment_status'] !== "Completed")&&($order['order_payment'] <=0)&&($order['order_invoice'] =="1"))&&($order['order_total'] > 0)==true) {
	if(countIt("ms_payment_options", "WHERE pay_status='1' AND pay_option!='paypalexpress'  AND pay_option!='payoffline' AND pay_option!='payoffline2' ORDER BY pay_order ASC ") > 0) {  
	
		?>
		<div id="paymentdivselect" style="float: right;" class="">
		<div>&nbsp;</div>	<div>&nbsp;</div>
		<?php if($_SESSION['payment_complete'] !== true) { ?>
		<?php if(($setup['do_not_show_pay_now_button_for_pay_offline_orders'] == true) && ($order['order_invoice'] !== "1") == true) { 

		} else { 
			?>
		<span class="checkout"  onclick="selectPaymentFromOrder(); return false;"><?php if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ") > 0) { ?><?php print _make_payment_;?><?php } else { ?><?php print _pay_invoice_;?><?php } ?></span>
		<?php } ?>
		<?php } ?>
		
		</div>
		
		<?php if($_REQUEST['action'] == "processpayment") { 
			include $setup['path']."/sy-inc/store/payment/payment.process.php";
			exit();
		}
	}
	?>
<?php $store['require_terms_conditions'] = 0;
	$paying_invoice = true;
		
	?>
	<div id="paymentdivbg"></div>
	<div id="paymentdiv" class="gallerypopup">
	<div style="padding: 16px;">
	<div id="closepaymentdiv" style="display: none; position: absolute; right: 8px;"><span  onclick="closeSelectPaymentFormOrder(); return false;" class="icon-cancel the-icons" ></span></div>

		<div >
		<?php 	//if(!is_numeric($_REQUEST['myorder'])) { 	die(); } ?>
	<?php
	$payopt = doSQL("ms_payment_options", "*", "WHERE pay_status='1' AND pay_option!='paypalexpress'  $and_where ORDER BY pay_order ASC "); 
	if($payopt['pay_option'] == "square") { 
		$action = $setup['temp_url_folder']."/sy-inc/store/payment/square/payment.php";
	} else { 
		$action = $site_setup['index_page'];
	}
	?>
		<form method=POST name="checkout" data-square-action="<?php print $setup['temp_url_folder']."/sy-inc/store/payment/square/payment.php";?>" data-action="<?php print $site_setup['index_page'];?>" id="checkout" action="<?php print $action;?>">

		<input type="hidden" name="view" id="view" value="order">
		<?php if(!empty($_REQUEST['myorder'])) { ?>
			<input type="hidden" name="myorder" id="order_id" value="<?php print $order['order_id'];?>">
		<?php } ?>
		<input type="hidden" name="order_id" id="order_id" value="<?php print $order['order_id'];?>">
		<input type="hidden" name="action" id="action" value="processpayment">

		<script>
		function updatepaymentamount() { 
			total = 0;
			$(".scpayment").each(function(i){
				if($(this).attr("checked")) { 
					total = total + Math.abs($(this).attr("data-amount"));
				}
			});
			$("#grand_total").val(total);
		}

		</script>
<?php 
/*
$payment_amount = 600;
if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ") > 0) {
	$paid = $payment_amount;
	$scs = whileSQL("ms_payment_schedule", "*", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");
	while($sc = mysqli_fetch_array($scs)) {
		if($sc['payment'] <= 0) {
			print "<li>A";
			if($paid > 0) { 
				print "<li>paymemt amount: ".$payment_amount." : sc amount: ".$sc['amount']." paid: ".$paid;
			//	updateSQL("ms_payment_schedule", "payment='".$sc['amount']."', payment_date='".currentdatetime()."', pay_transaction='".addslashes(stripslashes($transaction_id))."', payment_option='".addslashes(stripslashes($pay_option))."' WHERE id='".$sc['id']."' ");
				$paid  = $paid - $sc['amount'];
			}
		}
	}
}
*/
?>

		<?php if(countIt("ms_payment_schedule", "WHERE order_id='".$order['order_id']."' ") > 0) { ?>
		<div class="pc"><h3><?php print _select_payment_amount_;?></h3></div>
		<?php 
		$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");
		while($sc = mysqli_fetch_array($scs)) {
			if(($sc['due_date'] <= date('Y-m-d')) && ($sc['payment'] <= 0) == true) { 
				$cp++;
				?>
				<div class="pc"><input type="checkbox" class="scpayment" name="sp" id="sp<?php print $sc['id'];?>" value="<?php print $sc['id'];?>" data-amount="<?php print $sc['amount'];?>" <?php if($cp == "1") { ?>checked<?php } ?> disabled onchange="updatepaymentamount();"> <label for="sp<?php print $sc['id'];?>"><?php print showPrice($sc['amount']);?> &nbsp;<?php print _due_;?>  <?php print $sc['due_date_show'];?></label></div>
				<?php 
				$to_pay = $sc['amount'] + $to_pay;
			} else if($sc['payment'] == $sc['amount']) { 
				?>
				<div class="pc"><?php print showPrice($sc['amount'])." "._paid_." ".$sc['payment_date_show']; ?></div>
				<?php } else { 
				$cp++ ?>
				<div class="pc"><input type="checkbox" name="sp" class="scpayment" id="sp<?php print $sc['id'];?>" value="<?php print $sc['id'];?>"   <?php if($cp == "1") { ?> checked disabled <?php } ?> data-amount="<?php print $sc['amount'];?>"  onchange="updatepaymentamount();"> <label for="sp<?php print $sc['id'];?>"><?php print showPrice($sc['amount']);?> &nbsp;<?php print _due_;?> <?php print $sc['due_date_show'];?></label></div>
				<?php 
				if($cp == "1") { 
					$to_pay = $sc['amount'] + $to_pay;
				}

			}
			?>
		<?php } ?>
		<br><br>
		<input type="hidden" name="grand_total" id="grand_total" value="<?php print $to_pay;?>">
		<?php } else { ?>
		<div><h2><?php print showPrice($order['order_total']);?></h2></div>

		<input type="hidden" name="grand_total" id="grand_total" value="<?php print $order['order_total'];?>">
		<?php } ?>
		<?php include $setup['path']."/sy-inc/store/payment/payment.select.php"; ?>

		</form>
		</div>
	</div>
	</div>
<?php } ?>




<?php if($_SESSION['payment_complete'] == true) { ?>
<div class="success"><?php print _your_transaction_is_complete_;?></div>

		<?php if($order['order_invoice'] == "1") { ?>
		<div class="pageContent"><h1><?php print _ty_for_payment_; ?></h1></div>
		<?php } else { ?>
		<div class="pageContent"><h1><?php print _store_order_completed_title_; ?></h1></div>
		<div class="pageContent"><?php print _store_order_completed_text_;?> </div>
		<?php } ?>
		<?php if($downloads > 0) { ?>
		<div class="pageContent"><?php print _store_order_download_;?> </div>
		<?php } ?>
		<?php if($ships > 0) { ?>
		<div class="pageContent"><?php print _store_order_shipping_;?> </div>
		<?php } ?>
		<?php if($services > 0) { ?>
		<div class="pageContent"><?php print _store_order_service_;?> </div>
		<?php } ?>

		<div>&nbsp;</div>
<?php include $setup['path']."/sy-inc/store/store_conversions.php"; ?>
<?php unset($_SESSION['payment_complete']); ?>
<?php if(($_SESSION['createaccount'] == true) && ($store['checkout_account'] !== "disabled")==true){ ?>
<!-- 
<?php $_SESSION['newaccountorder'] = MD5($order['order_id']);?>
<div class="pc"><h2><?php print _pp_express_create_account_;?></h2></div>
<div class="pc"><?php print _pp_express_create_account_description_;?></div>
<div class="pc">
<form method="POST" name="newaccount" action="<?php print $site_setup['index_page'];?>" onsubmit="newAccountExpress('newaccountexpress'); return false;">
Password: <input type="password" name="newpassword" id="newpassword" size="20" class="narequired newaccountexpress">
<input type="hidden" name="action" id="action" value="newaccountexpress" class="newaccountexpress">
<input type="hidden" name="oid" value="<?php print MD5($order['order_id']);?>" id="oid" class="newaccountexpress">
<input type="submit" name="submit" value="Create Account" class="submit">
</form>
</div>
<div class="pc"><a href="/<?php print $site_setup['index_page'];?>?view=account"><?php print _pp_express_log_in_;?></a></div>
<div id="newaccountexpressresponse"></div>
<div>&nbsp;</div>
-->

<?php unset($_SESSION['createaccount']); ?>

<?php } ?>



<?php } ?>
<?php if(!empty($_SESSION['decline_message'])) { ?>
<div>&nbsp;</div>
<div id="paymentdeclined" class="error"><?php print $_SESSION['decline_message'];?></div>
<?php 
	unset($_SESSION['decline_message']);
} ?>
<div>&nbsp;</div>

	<?php if($order['order_payment_status'] == "Completed") { ?>
	<?php } else {  ?>
	<?php if($order['order_invoice'] == "1") { ?>
		<div class="pageContent"><h1><?php print _invoice_; ?> #<?php print "".$order['order_id'].""; ?></h1></div>
	<?php } else { ?>
	<?php if(($order['order_offline'] == "1")&&($order['order_payment'] <=0)==true) { 
		$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline' "); 
		?>
		<div class="pageContent"><h1><?php print _store_order_pending_title_; ?> #<?php print "".$order['order_id'].""; ?></h1></div>
		<div class="pageContent"><b><?php print nl2br($payopt['pay_offline_descr']);?></b></div>
		<div>&nbsp;</div>

	<?php } elseif(($order['order_offline'] == "2")&&($order['order_payment'] <=0)==true) { 
		$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='payoffline2' "); 
		?>
		<div class="pageContent"><h1><?php print _store_order_pending_title_; ?> #<?php print "".$order['order_id'].""; ?></h1></div>
		<div class="pageContent"><b><?php print nl2br($payopt['pay_offline_descr']);?></b></div>
		<div>&nbsp;</div>
	<?php } elseif(($order['order_payment_status'] == "Pending")&&($order['order_pay_type'] == "CCM")==true) { 
		$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='emailform' "); 
		?>
		<div class="pageContent"><h1><?php print _store_order_pending_title_; ?> #<?php print "".$order['order_id'].""; ?></h1></div>
		<div class="pageContent"><b><?php print nl2br($payopt['pay_offline_descr']);?></b></div>


	<?php } else  { ?>
		<div class="pageContent"><h1><?php print _store_order_pending_title_; ?> #<?php print "".$order['order_id'].""; ?></h1></div>
		<div class="pageContent"><?php // print _store_order_pending_text_; ?></div>
	<?php } ?>

	<?php } ?>
	<?php } ?>


<?php 
if(($_SESSION['check_affiliate'] == true)&&($setup['affiliate_program'] == true)==true) { 
	if(isset($_COOKIE['msaff'])) { 
		$aff = doSQL("ms_affiliate", "*", "WHERE aff_id='".$_COOKIE['msaff']."' ");
		updateSQL("ms_orders", "order_aff='".$aff['aff_id']."', order_aff_perc='".$aff['aff_payout']."' WHERE order_id='".$order['order_id']."' ");
	}
	unset($_SESSION['check_affiliate']);
}
?>

<?php if($setup['subscriptions'] == true) { 
	$sub = doSQL("ms_subscriptions", "*", "WHERE sub_order_number='".$order['order_id']."' AND sub_status='0' ");
	if(!empty($sub['sub_id'])) { ?>
	<div class="pc center"><a href="<?php print $setup['subscription_start_page'];?>?sid=<?php print $sub['sub_key'];?>" class="actionbuttons" style="font-size: 21px; font-weight: bold;">Click here to get started setting up your Sytist Hosted!</a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<?php } 
	?>


<div class="clear"></div>
<?php } ?>





















	<div style="width: 30%;" class="left nofloatsmallleft">
	<div class="pc"><h3><?php print "".$order['order_date'].""; ?></h3></div>
	<?php // ORDER INFO ?>
		<div class="pageContent">
			<div style="width:40%; float: left;"><?php if($order['order_invoice'] == "1") { print _invoice_; } else { print _order_order_number_; } ?> </div><div style="width:60%; float: right;"><?php print "".$order['order_id'].""; ?></div>
			<div class="cssClear"></div>
		</div>
	
			<div class="pageContent">
				<div style="width:40%; float: left;"><?php print _store_order_total_;?> </div><div style="width:60%; float: right;"><?php print  showPrice($order['order_total']); ?></div>
				<div class="cssClear"></div>
			</div>

		<?php 
		$scs = whileSQL("ms_payment_schedule", "*,date_format(due_date, '".$site_setup['date_format']."')  AS due_date_show,date_format(payment_date, '".$site_setup['date_format']."')  AS payment_date_show", "WHERE order_id='".$order['order_id']."' ORDER BY due_date ASC ");

		if(mysqli_num_rows($scs) > 0) { 

			while($sc = mysqli_fetch_array($scs)) { ?>
			<div class="pc"><?php if($sc['payment'] > 0) { ?><?php print showPrice($sc['payment']) ." "._paid_." ".$sc['payment_date_show'];?><?php } else { print showPrice($sc['amount'])." "._due_." ".$sc['due_date_show']; } ?></div>
			<?php }

		} else { 

			if(empty($order['order_pay_type'])) {  
				if($order['order_due_date'] > 0) { ?>
				<div class="pc">
				<div style="width:40%; float: left;"><?php print _due_;?> </div><div style="width:60%; float: right;"><?php print $order['order_due_date_show'];?></div>
				<div class="clear"></div>
				</div>

			<?php 
				}
			}
			
			 if(!empty($order['order_pay_type'])) { ?>
			<div class="pageContent">
				<div style="width:40%; float: left;"><?php print _store_order_payment_type_;?> </div><div style="width:60%; float: right;">
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
				?>
				
				
				&nbsp;</div>
				<div class="cssClear"></div>
			</div>

			<?php } ?>

 
	<?php if(!empty($order['order_coupon'])) { ?>
			<div class="pageContent">
				<div style="width:40%; float: left;"><?php print _store_order_coupon_;?> </div><div style="width:60%; float: right;"><?php print  $order['order_coupon']; ?>&nbsp
				</div>
				<div class="cssClear"></div>
			</div>
	<?php } ?>
	<?php if(!empty($order['order_payment_status'])) { ?>
			<div class="pageContent">
				<div style="width:40%; float: left;"><?php print _store_order_payment_status_;?> </div><div style="width:60%; float: right;">
				<?php 
					if($order['order_payment_status'] == "Pending") { 
						print _pending_; 
					} elseif($order['order_payment_status'] == "Completed") { 
						print _completed_;
					} else { 
						print $order['order_payment_status'];
					}
					?>&nbsp;</div>
				<div class="cssClear"></div>
			</div>
		<?php } ?>
	<?php if($order['order_pending_reason'] > 0) { ?>
			<div class="pageContent">
				<div style="width:40%; float: left;"><?php print _store_order_pending_reason_;?> </div><div style="width:60%; float: right;"><?php print  $order['order_pending_reason']; ?>&nbsp;</div>
				<div class="cssClear"></div>
			</div>
	<?php } ?>

			<?php } ?>

	</div>

	<div style="width: 70%;" class="left nofloatsmallleft">
		<div style="width: 50%;" class="left nofloatsmallleft">
		<?php // BILLING ADDRESS?>
			<div class="pageContent"><h3><?php print _billing_address_;?></h3></div>
	<?php if(!empty($order['order_business_name'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_business_name'].""; ?>
			</div>
	<?php } ?>

			<div class="pageContent">
				
	<?php if((empty($order['order_first_name']))AND(empty($order['order_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print "".$order['order_first_name']." ".$order['order_last_name'].""; ?><?php  } ?>
			</div>
			<div class="pageContent">				
				<?php print "".$order['order_email'].""; ?>
			</div>
	<?php if(!empty($order['order_phone'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_phone'].""; ?>
			</div>
	<?php } ?>


	<?php if(!empty($order['order_address'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_address'].""; ?>
			</div>
	<?php } ?>
	<?php if(!empty($order['order_city'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_city'].""; ?> <?php print "".$order['order_state'].""; ?>, <?php print "".$order['order_zip'].""; ?>
			</div>
	<?php } ?>
	<?php if(!empty($order['order_country'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_country'].""; ?>
			</div>
	<?php } ?>
		</div>

		<?php if(!empty($order['order_shipping_option'])) { ?>		

		<div style="width: 50%;" class="left nofloatsmallleft">
		<?php // SHIPPING ADDRESS ?>

	<?php if($order['order_ship_pickup'] == "1") { ?>
			<div class="pc"><h3><?php print $order['order_shipping_option'];?></h3></div>
		<?php } else { ?>
			<div class="pageContent"><h3><?php print _shipping_address_;?> </h3></div>

	<?php if(!empty($order['order_ship_business'])) { ?>
			<div class="pageContent">
				<?php print "".$order['order_ship_business'].""; ?>
			</div>
	<?php } ?>

			<div class="pageContent">
				
		<?php if((empty($order['order_ship_first_name']))AND(empty($order['order_ship_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print "".$order['order_ship_first_name']." ".$order['order_ship_last_name'].""; ?><?php  } ?>
				</div>

		<?php if(!empty($order['order_ship_address'])) { ?>
				<div class="pageContent">
					<?php print "".$order['order_ship_address'].""; ?>
				</div>
		<?php } ?>
		<?php if(!empty($order['order_ship_city'])) { ?>
				<div class="pageContent">
					<?php print "".$order['order_ship_city'].""; ?> <?php print "".$order['order_ship_state'].""; ?>, <?php print "".$order['order_ship_zip'].""; ?>
				</div>
		<?php } ?>
		<?php if(!empty($order['order_ship_country'])) { ?>
				<div class="pageContent">
					<?php print "".$order['order_ship_country'].""; ?>
				</div>
		<?php } ?>

				<?php if(!empty($order['order_shipped_by'])) { 
						if($order['order_shipped_by_id'] > 0) { 
							$shipped = doSQL("ms_shipping_options", "*", "WHERE ship_id='".$order['order_shipped_by_id']."' ");
						}
							?>
						<div class="pc"><?php print _shipped_;?> <?php print $order['order_shipped_date'];?> <?php print _by_;?> <?php print $order['order_shipped_by'];?></div>
						<?php if(!empty($order['order_shipped_track'])) { ?>
						<div class="pc">
						<?php  if(!empty($shipped['ship_track'])) { ?>
						<a href="<?php print $shipped['ship_track'];?><?php print $order['order_shipped_track'];?>" target="_blank"><?php print $order['order_shipped_track'];?></a>
						<?php } else { ?>
						<?php print $order['order_shipped_track'];?>
						<?php } ?>
						</div>
						<?php } ?>

				<?php } ?>
			<?php } ?>
	
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>


	<div>&nbsp;</div>

	<?php if(!empty($order['order_notes'])) { ?>
	<div class="pc"  style="padding: 0px 16px;">
	<div class="pc"><span  class="the-icons icon-feather"></span><?php print _customer_notes_;?></div>
	<div class="pc" style="padding-left: 24px;"><i><?php print nl2br($order['order_notes']);?></i></div>
	</div>

	<?php } ?>
		<?php showOrderExtraFields($order); ?>

<?php

if($order['order_payment_status'] == "Completed") {
	$action = "download";
	} else {
	$action = "pending";
}
?>

<?php 
if(function_exists('zip_open')) { 
	
	if(($photodownloads > 1)&&($action == "download")==true) { 
	$photo_setup = doSQL("ms_photo_setup", "zip_limit", "");
	$zip_max = $photo_setup['zip_limit'];
	if($zip_max <=0) { 
		$zip_max = 20;
	}
	$total_zip = countIt(cart_table." LEFT JOIN ms_photos ON ".cart_table.".cart_pic_id=ms_photos.pic_id", "WHERE cart_order='".$order['order_id']."' AND cart_download='1' AND cart_pic_id>'0' AND cart_disable_download<='0' ");
	if($total_zip > $zip_max) { 
	$zips = ceil($total_zip / $zip_max);
	$x = 1;
	?>
	<div class="pc"><h2><?php print _download_zip_file_;?></h2></div>
	<div class="pc"><?php print "$total_zip "._photos_word_photos_." "._in_." $zips "._zip_files_.""; ?></div>
	<div class="pc"><?php print _download_zip_files_text_;?></div>
	<div id="downloadzipwait" class="hide"><?php print _downloading_zip_file_;?></div>

	<?php while($x <= $zips) { ?>
	<div class="pc left">
	<form method="post" name="dlzip" action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_download_zip2.php" onsubmit="hidedownload('<?php print $x;?>');">
	<input type="hidden" name="order_id" value="<?php print MD5($order['order_id']);?>">
	<input type="hidden" name="zip_limit" value="<?php print $x;?>">
	<div id="downloadzip-<?php print $x;?>"><input type="submit" name="submit" value="<?php print $x;?>" class="submit" id="submit-<?php print $x;?>"></div>
	</form>
	</div>
	<?php
		$x++;
	} ?>

	<div class="clear"></div>
	<?php
	} else { 

	?>
	<div class="pc">
	<form method="post" name="dlzip" action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_download_zip2.php" onsubmit="hidedownload('0');">
	<input type="hidden" name="order_id" value="<?php print MD5($order['order_id']);?>">
	<div id="downloadzip"><input type="submit" name="submit" value="<?php print _download_zip_file_;?>" class="submit"></div>
	<div id="downloadzipwait" class="hide"><?php print _downloading_zip_file_;?></div>
	</form>
	</div>

	<?php } 
	}?>

<script>
function hidedownload(id) {
	if(id > 0) { 
		$("#submit-"+id).addClass("disabledinput");
		$("#downloadzipwait").slideDown(100);
	} else { 
		$("#downloadzip").slideUp(100);
		$("#downloadzipwait").slideDown(100);
	}
}
</script>
<?php } ?>


<?php if(countIt(cart_table,  "WHERE cart_order='".$order['order_id']."' ")>0) { ?>

<div class="pc">
	<div style="width: 55%;" class="left"><?php print _product_;?></div>
	<div style="width: 15%;" class="left">&nbsp;</div>
	<div style="width: 10%;" class="left textright"><?php print _price_;?></div>
	<div style="width: 10%;" class="left  textright"><?php print _qty_;?></div>
	<div style="width: 10%;" class="left textright"><?php print _extended_;?></div>
	<div class="clear"></div>
</div>
<div id="orderitems">
<?php
$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_package!='0' ORDER BY cart_package_no_select DESC, cart_id ASC " );
$tracks_total	= mysqli_num_rows($carts);
while($cart= mysqli_fetch_array($carts)) {
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	$tracknum++;
	showOrderPackage($pack,$cart,$order,$action);
	?>
	<?php 
	$pcarts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'  ORDER BY cart_pic_org ASC" );
	if(mysqli_num_rows($pcarts) > 0) { ?>
	<div class="pc">
	<?php if($cart['cart_bonus_coupon'] > 0) { ?>

	<?php	} else { 
		?>
		<div class="pc"><?php print _cart_selected_package_photos_; ?></div>
		<?php 
	}
		?>
	</div>
	<?php }

		while($pcart= mysqli_fetch_array($pcarts)) {
		$tracknum++;
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
		if($pcart['cart_pic_id'] > 0) { 
			showOrderPhoto($pcart,$action,$order,$cart);
		}
	}

}


$carts = whileSQL(cart_table, "*", "WHERE  cart_photo_prod!='0' AND cart_order='".$order['order_id']."'  AND cart_package_photo='0' AND cart_coupon='0' ORDER BY cart_pic_org ASC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$tracknum++;
	showOrderPhoto($cart,$action,$order,"");
}
	#######################  Gift Certificates #########################################

	$carts = whileSQL("ms_cart", "*", "WHERE cart_gift_certificate='1'  AND cart_order='".$order['order_id']."'  ORDER BY cart_id DESC" );
	while($cart= mysqli_fetch_array($carts)) {
	?>

	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 55%;" class="left" >
			<div class="left">
				<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
			<div class="pc"><?php print _gift_certificate_to_;?>: <?php print $cart['cart_gift_certificate_to_name'];?> (<?php print $cart['cart_gift_certificate_to_email'];?>)</div>
				<div class="pc"><?php print _gift_certificate_from_;?>: <?php print $cart['cart_gift_certificate_from_name'];?> (<?php print $cart['cart_gift_certificate_from_email'];?>)</div>
				<div class="pc"><?php 
				if(!empty($cart['cart_gift_certificate_message'])) { print "<i>".nl2br($cart['cart_gift_certificate_message'])."</i>"; } ?>
			</div>
			<div class="clear"></div>
			</div>
		</div>

			<div style="width: 15%" class="left center">
			&nbsp;
			</div>
			<div style="width: 10%" class="left textright"><?php print showPrice($cart['cart_price']);?></div>
			<div style="width: 10%" class="left textright"><?php print $cart['cart_qty'] + 0;?></div>
			<div style="width: 10%" class="left textright"><?php print showPrice($cart['cart_qty'] * $cart['cart_price']);?></div>
			<div class="clear"></div>
		</div>











	<?php


	##################################################
}

$carts = whileSQL(cart_table, "*", "WHERE  cart_store_product!='0' AND cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id DESC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
		$pdate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
	$tracknum++;
	showOrderProduct($pdate,$cart,$action,$order);

	$pcarts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."'  AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  ORDER BY cart_pic_org ASC" );
	if(mysqli_num_rows($pcarts) > 0) { 
		?>	<div class="pc"><?php print _cart_selected_product_photos_; ?></div>
		<?php 
		}
		while($pcart= mysqli_fetch_array($pcarts)) {
			$tracknum++;
			showOrderPhoto($pcart,$action,$order,$cart);
		}
	if(mysqli_num_rows($pcarts) > 0) { 
		print "<div>&nbsp;</div><div>&nbsp;</div>";
	}

}

$carts = whileSQL(cart_table, "*", "WHERE cart_invoice='1' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id ASC  " );
	while($cart= mysqli_fetch_array($carts)) {
	showInvoiceItem($cart);
}
$carts = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name  ORDER BY co_id ASC ");
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	showOrderImageOptions($cart, "0",$order);
}

?>


</div>

<?php } ?>

<div class="right">
		<div class="pc">
			<div class="left pc"><?php print _subtotal_;?></div>
			<div class="right textright pc"><?php print showPrice($order['order_sub_total']);?></div>
			<div class="clear"></div>
		</div>
		<?php if($order['order_eb_discount'] > 0) { ?>
		<div class="pc">
			<div class="left pc"><?php print _early_bird_special_;?></div>
			<div class="right textright pc">(<?php print showPrice($order['order_eb_discount']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if($order['order_discount'] > 0) { ?>
		<div class="pc">
			<div class="left pc"><?php print _discount_;?> (<?php print $order['order_coupon_name'];?>)</div>
			<div class="right textright pc">(<?php print showPrice($order['order_discount']);?>)</div>
			<div class="clear"></div>
		</div>
		<?php } ?>

		<?php if($order['order_tax'] > 0) { ?>
		<div class="pc">
			<div class="left pc"><?php print _tax_;?>
			<br>
			<?php print ($order['order_tax_percentage']+0)."% on ".showPrice($order['order_taxable_amount']);?>
			</div>
			<div class="right textright pc"><?php print showPrice($order['order_tax']);?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>

		<?php if($order['order_vat'] > 0) { ?>
		<div class="pc">
			<div class="left pc"><?php print _vat_;?>
			<br>
			<?php print ($order['order_vat_percentage'] + 0)."% on ".showPrice($order['order_taxable_amount']);?>
			</div>
			<div class="right textright pc"><?php print showPrice($order['order_vat']);?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if(!empty($order['order_shipping_option'])) { ?>		
			<?php if($order['order_ship_pickup'] == "1") { ?>
		<div class="pc">
			<div class="left pc"><?php print $order['order_shipping_option']?></div>
			<div class="right textright pc"><?php print showPrice($order['order_shipping']);?></div>
			<div class="clear"></div>
		</div>

	<?php } else { ?>
		<div class="pc">
			<div class="left pc"><?php print _shipping_;?> (<?php print $order['order_shipping_option']?>)</div>
			<div class="right textright pc"><?php print showPrice($order['order_shipping']);?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<?php } ?>

		<?php if($order['order_credit'] > 0) { ?>
			<div class="pc">
				<div class="left pc"><?php print _account_credit_;?></div>
				<div class="right textright pc">(<?php print showPrice($order['order_credit']);?>)</div>
				<div class="clear"></div>
			</div>

		<?php } ?>
		<?php if($order['order_gift_certificate'] > 0) { ?>
			<div class="pc">
				<div class="left pc"><?php print _gift_certificate_name_;?></div>
				<div class="right textright pc">(<?php print showPrice($order['order_gift_certificate']);?>)</div>
				<div class="clear"></div>
			</div>

		<?php } ?>

		<div class="pc">
			<div class="left pc"><?php print _grand_total_;?></div>
			<div class="right textright pc"><?php print showPrice($order['order_total']);?></div>
			<div class="clear"></div>
		</div>
</div>
<?php } ?>

<div class="clear"></div>
<?php if(!isset($_SESSION['pid'])) { ?>
<div class="pageContent"><a href="<?php print $site_setup['index_page'];?>?view=order&action=new"><?php print _order_find_another_;?></a></div>
<?php } ?>





<div class="pageContent"><?php print _store_order_bottom_text_; ?></div>

<?php 
function showInvoiceItem($cart) {
	global $setup,$site_setup,$zip_total,$no_trim;
	$action = "viewcart";
	$price = productPrice($date);
	$this_price = $cart['cart_price'];
	?>
	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 55%;" class="left" >
			<div class="left">
				<div class="pc"><h3><?php print $cart['cart_product_name'];?></h3></div>
			<div class="clear"></div>
			</div>
		</div>

			<div style="width: 15%" class="left center">
			&nbsp;
			</div>
			<div style="width: 10%" class="left textright"><?php print showPrice($this_price);?></div>
			<div style="width: 10%" class="left textright"><?php print $cart['cart_qty'] + 0;?></div>
			<div style="width: 10%" class="left textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></div>
			<div class="clear"></div>
		</div>
		
<?php } 


function showOrderImageOptions($cart,$package_photo,$order) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['this_price'];
	$this_price = $cart['this_price'];

	
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['co_pic_id']."' ");
	?>
	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">
		<div class="left" style="width: 80%"><h3><?php print $cart['co_opt_name'];?></h3></div>
		<div style="width: 10%" class="left textright"><?php print ($cart['total_items'] + 0);?></div>
		<div style="width: 10%" class="left textright"><?php print showPrice($this_price);?></div>
		<div class="clear"></div>	
	</div>
<?php } 


function showOrderProduct($date,$cart,$action,$order) {
	global $setup,$site_setup,$zip_total;
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

	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 55%;" class="left" >
			<?php if($date['date_id'] > 0) { ?>

		<?php if($sub['sub_pic_id'] > 0) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
			if($pic['pic_id'] > 0) { ?>
				<div class="left" style="margin-right: 8px; width: 30%; text-align: center;" ><img src="<?php print getimagefile($pic,'pic_mini');?>">	</div>
		<?php 
			}
		
		} else { ?>


			<div class="left" style="margin-right: 8px; width: 30%; text-align: center;" ><?php print getPagePreview($date,"mini"); ?></div>

			<?php } ?>


			<?php } ?>
			<div class="left">
				<div class="pc">
				<?php if($cart['cart_account_credit_for'] > 0) { ?><h3><?php print _registry_purchase_; ?></h3><?php } ?>
				<?php if($cart['cart_paid_access'] > 0) { ?><h3><?php print _access_to_;?></h3><?php } ?>
				<h3>
<?php if($cart['cart_booking'] > 0) { 
				$book = doSQL("ms_bookings", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time", "WHERE book_id='".$cart['cart_booking']."' ");
				 } ?>

				<?php if(!empty($date['date_id'])) { ?><a  href="<?php print $setup['temp_url_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>"><?php } ?>
				<?php print $cart['cart_product_name'];?><?php if(!empty($date['date_id'])) { ?></a><?php } ?></h3></div>
				<?php if($cart['cart_paid_access'] > 0) { ?>
				<div class="checkoutpagebutton pc"><a  href="<?php print $setup['temp_url_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>" class="checkoutcart"><?php print _purchase_view_page_;?></a></div>
				<?php } ?>
			<?php if($cart['cart_pre_reg'] > 0) { ?>
			<div class="pc">
			<?php 
			$pdate = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pre_reg']."' ");
			if($pdate['date_public'] =="1") { 
				 print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$pdate['cat_folder']."/".$pdate['date_link']."/\">".$pdate['date_title']."</a>";
			} else { 
				print $pdate['date_title'];
			}
			?>
			</div>
			<?php } ?>
				<?php if(($cart['cart_account_credit'] > 0) && ($cart['cart_account_credit_for'] <=0)==true) { ?>
				<div class="pc"><?php print _includes_." ".showPrice($cart['cart_account_credit'])." "._credit_;?></div>
				<?php } ?>
				<?php if($cart['cart_account_credit_for'] > 0) { ?>
				<div class="pc"><?php if(!empty($cart['cart_reg_message_name'])) { print "<b>".$cart['cart_reg_message_name']."</b><br>"; } 
				if(!empty($cart['cart_reg_message'])) { print "<i>".nl2br($cart['cart_reg_message'])."</i>"; } ?>
				</div>
				<?php } ?>
				<?php 
					if(!empty($date['prod_version'])) { 
						print "<div class=\"pc\">Curent version: ".$date['prod_version']."</div>";
					}
					?>
			<?php if($book['book_id'] > 0) { ?>
				<div class="pc"><?php print $book['book_date'];?><?php if($book['book_all_day'] !== "1") { print $book['book_time']; } ?></div>

			<?php } ?>
				


			<?php 
			if($order['order_status'] !== "2") { 
				if(!empty($cart['cart_reg_key'])) {
					$reg = doSQL("ms_reg_keys", "*,date_format(key_expire,'".$site_setup['date_format']."')  AS key_expire_show", "WHERE key_key='".$cart['cart_reg_key']."' ");
					if(date('Y-m-d') >= $reg['key_expire']) { 
						// Add the following line back for paid upgrades. 
						 $no_download_key = true;
					}

				?>
			<div class="pc">Registration key: &nbsp; <pre style="display: inline;"><?php print $cart['cart_reg_key'];?></pre></div>
			<div class="pc">Includes upgrades through <?php print $reg['key_expire_show'];?></div>
			<?php }
			}
			?>
			<?php
			if(!empty($cart['cart_order_message'])) { 			
				?>
			<div class="pc"><?php print $cart['cart_order_message'];?></div>
			<?php } ?>



				<?php if(!empty($sku)) { 
					if($sku !== "sytistinstall") { ?>
					<div class="pc">#<?php print $sku;?></div>
					<?php 
					}
				
				} ?>


				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc">
				<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					
				$this_price = $this_price + $co['co_price'];  
				?>
				</div>
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
			<div class="clear"></div>
			</div>
		</div>

			<div style="width: 15%" class="left center">
			<div>
			<?php 
			if($order['order_status'] !== "2") { 
				if($action == "download") {
					if($date['prod_type'] == "download") { 
					//	if(($download_attempts - $cart['cart_download_attempts'])<=0) {
					//		print "<span class=\"inShoppingCart\">"._store_download_attempts_exceeded_."</span>";
					//	} else {
						if($no_download_key !== true) { 
							print "<span id=\"storeDownload\"><a href=\"".$setup['temp_url_folder']."/sy-inc/store/storedownload.php?syok=".MD5($order['order_key'])."&syorder=".MD5($order['order_id'])."&crtid=".MD5($cart['cart_id'])."\" ><span id=\"\" class=\"the-icons icon-download\"> "._store_download_link_."";
							print "</a></span>";
							print "</span>";
						} else { 
							print "<b>Your upgrade period has expired.<br><a href=\"https://www.picturespro.com/buy/sytist-upgrade/?reg=".$cart['cart_reg_key']."\"><u>Purchase another year of upgrades</u>.</a> </b><br>If you need the installation files for the last version you are eligible for, <a href=\"/contact/\">contact us</a>";
						}

					//	}
					}
				}
			}
			?>
			</div>
			<?php if(!empty($date['prod_download_descr'])) { print "<div class=\"pageContent\">".nl2br($date['prod_download_descr'])."</div>"; } ?>
			&nbsp;
			</div>
			<div style="width: 10%" class="left textright"><?php print showPrice($this_price);?></div>
			<div style="width: 10%" class="left textright"><?php print $cart['cart_qty'] + 0;?></div>
			<div style="width: 10%" class="left textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></div>
			<div class="clear"></div>

		</div>

	
<?php } ?>

<?php 
function showOrderPhoto($cart,$action,$order,$parent) {
	global $setup,$site_setup,$zip_total;
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
	if(($pic['pic_id'] > 0)&&(file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']))==true) { 
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'].""); 
	}
	if($cart['cart_package_photo'] > 0) { 
		$pcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	}
	if($cart['cart_product_photo'] > 0) { 
		$pcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$pcart['cart_store_product']."' ");
	}

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

	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 70%;" class="left " >

			<div class="left nofloatsmall" style=" width: 50%; text-align: center;">
			<?php 	if($pic['pic_id'] > 0) { ?>


		<?php if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0)) && ($cart['cart_photo_bg'] <=0)==true) { ?>
	<div id="ct-<?php print $cart['cart_id'];?>">
	<?php cropphotoview($cart,$pic,$prod,"pic_th",'1'); ?>
	</div>

	<?php } else { 
			$size = getimagefiledems($pic,'pic_th');	 		
				if(!empty($cart['cart_thumb'])) { 
					if(file_exists($setup['path']."/".$cart['cart_thumb'])) { 
					$size = @GetImageSize("".$setup['path']."/".$cart['cart_thumb']); 
					?>
					<img src="<?php print $setup['temp_url_folder'];?>/<?php print $cart['cart_thumb'];?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
					<?php } ?>
				<?php } else { ?>

			<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
			<?php } ?>
<?php } ?>


			<?php } else { ?>
			Photo Removed
			<?php } ?>
			</div>

			<div class="left nofloatsmall" style="width: 50%;">
			<?php
					if($cart['cart_frame_size'] > 0) { 
					$wset = doSQL("ms_wall_language", "*","");
					$wall_settings = doSQL("ms_wall_settings","*","");
						$frame = doSQL("ms_frame_sizes LEFT JOIN ms_frame_styles ON ms_frame_sizes.frame_style=ms_frame_styles.style_id", "*", "WHERE frame_id='".$cart['cart_frame_size']."' ");
						$color = doSQL("ms_frame_images", "*", "WHERE img_id='".$cart['cart_frame_image']."' ");
						?>
						<div class="name"><h3><?php print ($frame['frame_width'] * 1)." x ".($frame['frame_height'] * 1)." ".$frame['style_name'];?></h3></div>
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
						<div><?php print ($frame['frame_mat_print_width'] * 1)." x ".($frame['frame_mat_print_height'] * 1);?> <?php print $wset['_wd_print_'];?></div>
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
					<div class="pc"><a href="<?php print $setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/?view=room&rw=<?php print $rv['wall_link'];?>"><?php print $wset['_wd_wall_designer_tab_'];?></a></div>
					<?php } ?>

				<?php $cos = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) {
				$option_price = $co['co_price'];
					?>
				<div class="pc">
				<?php print $co['co_opt_name']." "._selected_; ?>
				</div>
				<?php } ?>



				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC");
				while($co = mysqli_fetch_array($cos)) { 
					if($co['co_download'] == "1") { 
						$opt_download = "&crtoptid=".MD5($co['co_id'])."";
					}?>
				<div class="pc">
				<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
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
					
					 ?>
				</div>
			<?php } ?>
			<?php if($cart['cart_color_id'] > 0) { ?>
				<div class="pc">
				<?php print $cart['cart_color_name'];?>
				</div>
			<?php } ?>

				<div class="pc"><b><?php print $cart['cart_pic_org'];?></b></div>

				<div class="pc"><?php print _in_;?> 
				<?php print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>";?>
					<?php if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							print " > <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$upsub['sub_link']."\">".$upsub['sub_name']."</a>  ";
						}
					}
					
					print " > <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link']."\">".$sub['sub_name']."</a>";

				}
				?>
				</div>
				<?php if(!empty($cart['cart_notes'])) { ?>
				<div class="pc"><span class="the-icons icon-user"></span><?php print nl2br($cart['cart_notes']);?></div>
				<?php } ?>
			<div class="clear"></div>
			<div>
			<?php 
			if($cart['cart_disable_download'] == "1") { ?> 
			<div class="pc"><b><?php print _download_pending_;?></b></div>
			<?php } 
			if($action == "download") {
				if((($cart['cart_download'] == "1"))AND($cart['cart_disable_download']<=0)==true) { 
					print "<span id=\"\" class=\"the-icons icon-download\"><a href=\"".$setup['temp_url_folder']."/sy-inc/store/storedownloadphoto.php?syok=".MD5($order['order_key'])."&syorder=".MD5($order['order_id'])."&crtid=".MD5($cart['cart_id'])."\">"._store_download_link_."</a></span><br><span id=\"inShoppingCart\">";
					print "</span>";
					$opt_download = "";
				}

				$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0' AND co_download='1'  ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) { 
					print "<span id=\"\" class=\"the-icons icon-download\"><a href=\"".$setup['temp_url_folder']."/sy-inc/store/storedownloadphoto.php?syok=".MD5($order['order_key'])."&syorder=".MD5($order['order_id'])."&crtid=".MD5($cart['cart_id'])."&crtoptid=".MD5($co['co_id'])."\">"._store_download_link_." ".$co['co_opt_name']."</a></span><br><span id=\"inShoppingCart\">";
					print "</span>";
					}
			}
			?>
			</div>
			</div>

		</div>

			<div style="width: 10%" class="left textright"><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice($this_price);?><?php } ?></div>
			<div style="width: 10%" class="left textright"><?php print $cart['cart_qty'] + 0;?></div>
			<div style="width: 10%" class="left textright"><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice(($cart['cart_qty'] * $this_price) + $co_download);
			?><?php } ?></div>
			<div class="clear"></div>
		</div>

	
<?php } ?>

<?php 
function showOrderPackage($pack,$cart,$order,$action) {
	global $setup,$site_setup,$zip_total;
	$this_price = $cart['cart_price'];
	if(!empty($date['prod_prod_id'])) { 
		$sku = $date['prod_prod_id'];
	}
	?>

	<div class="item" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div style="width: 55%;" class="left" >
			<div class="pc"><h3><?php if(!empty($cart['cart_print_credit'])) { 
				if($cart['cart_bonus_coupon'] > 0) { 
					print _bonus_coupon_.": ";
				} else { 
					print _print_credit_.": "; 
				}
			 } ?><?php print $cart['cart_product_name'];?></h3></div>

				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc">
				<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
				
				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					


				$this_price = $this_price + $co['co_price'];  ?>
				</div>
			<?php } ?>


		<?php if(($cart['cart_package_buy_all'] >= "1") || ($cart['cart_package_no_select'] == "1")==true) { ?>

			<?php } else { ?>
			<div class="pc"><?php print countIt(cart_table, "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ")." "._of_." ".countIt(cart_table, "WHERE cart_package_photo='".$cart['cart_id']."' ")." "._selected_.""; ?></div>
			<?php } ?>

		<?php if($cart['cart_credit'] > 0) { ?>
		<div class="pc"><?php print showPrice($cart['cart_credit'])." "._collection_credit_cart_message_;?></div>
		<?php } ?>

			</div>
			<div style="width: 15%" class="left">&nbsp;</div>
			<?php if($cart['cart_package_include'] <= 0) { ?>
			<div style="width: 10%" class="left textright"><?php print showPrice($this_price);?></div>
			<div style="width: 10%" class="left textright"><?php print $cart['cart_qty'] + 0;?></div>
			<div style="width: 10%" class="left textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></div>
			<?php } ?>
			<div class="clear"></div>
		</div>

	
<?php } ?>
