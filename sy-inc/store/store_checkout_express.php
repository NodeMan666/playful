<?php
if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE ".checkCartSession()." AND cart_ship='1'  AND cart_order<='0' ") > 0) { 
	$ship = true;
}
$no_trim = true;
$total = shoppingCartTotal($mssess);
$pay_total = $total['total'];
$taxable_total = $total['tax_total'];
$opt = doSQL("ms_payment_options", "*", "WHERE pay_id='2' ");
$included = true;
include_once($setup['path']."/sy-inc/store/payment/express/config.php");
include_once($setup['path']."/sy-inc/store/payment/express/paypal.class.php");
if(isset($_REQUEST["token"]) && isset($_REQUEST["PayerID"]))
{
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.

    $token = $_REQUEST["token"];
    $payerid = $_REQUEST["PayerID"];

    //get session variables
    $ItemPrice      = $_SESSION['itemprice'];
    $ItemTotalPrice = $_SESSION['totalamount'];
    $ItemName       = $_SESSION['itemName'];
    $ItemNumber     = $_SESSION['itemNo'];
    $ItemQTY        =$_SESSION['itemQTY'];
	$padata =   '&TOKEN='.urlencode($token).
						'&PAYERID='.urlencode($payerid).
						'&BUTTONSOURCE=Grissett_SP'.
						'&PAYMENTACTION='.urlencode("SALE").
						'&AMT='.urlencode($_REQUEST['grand_total']).
						'&CURRENCYCODE='.urlencode($PayPalCurrencyCode);

	if(!empty($_POST['submitit'])) { 


		//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

		//Check if everything went ok..
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
				echo '<h2>Success</h2>';
				echo 'Your Transaction ID :'.urldecode($httpParsedResponseAr["TRANSACTIONID"]);

					/*
					//Sometimes Payment are kept pending even when transaction is complete.
					//because of Currency change, user choose other payment option or its pending review etc.
					//hence we need to notify user about it and ask him manually approve the transiction
					*/

					if('Completed' == $httpParsedResponseAr["PAYMENTSTATUS"])
					{
						echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
					}
					elseif('Pending' == $httpParsedResponseAr["PAYMENTSTATUS"])
					{
						echo '<div style="color:red">Transaction Complete, but payment is still pending! You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
					}

				echo '<br /><b>Stuff to store in database :</b><br /><pre>';

					$transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
					$nvpStr = "&TRANSACTIONID=".$transactionID;
					$paypal= new MyPayPal();
					$httpParsedResponseAr = $paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

					if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
							 // print "<pre>"; print_r($_POST);  print_r($httpParsedResponseAr); die();
							$order_total_pay = $_POST['grand_total'];
							$payment_amount = $order_total_pay;
							$sub_total = $_REQUEST['sub_total'];
							$order_fees = urldecode($httpParsedResponseAr['FEEAMT']);
							$currency = "USD";
							$transaction_id = $httpParsedResponseAr["TRANSACTIONID"];
							$order_ship_amount = $_REQUEST['shipping_price'];
							$shipping_option = $_REQUEST['ship_select'];
							$order_tax = $_REQUEST['tax_price'];
							$tax_percentage = $_REQUEST['tax_percentage'];
							$vat = $_REQUEST['vat_price'];
							$vat_percentage = $_REQUEST['vat_percentage'];
							$taxable_amount = $_REQUEST['taxable_amount'];
							$order_discount = $_REQUEST['discount_amount'];
							$order_message  = $_REQUEST['customer_message'];
							$order_pay_type = "PayPal - ".$httpParsedResponseAr['TRANSACTIONTYPE'];
							$order_payment_status = $httpParsedResponseAr['PAYMENTSTATUS'];
							$order_pending_reason = $httpParsedResponseAr['PENDINGREASON'];
							$pay_option = "paypalexpress";
							$first_name = $_REQUEST['first_name'];
							$last_name = $_REQUEST['last_name'];
							$email_address = $_REQUEST['email_address'];
							$company_name = $_REQUEST['business_name'];
							$country = $_REQUEST['country'];
							$city = $_REQUEST['city'];
							$state = $_REQUEST['state'];
							$zip = $_REQUEST['zip'];
							$address_status = $_REQUEST['address_status'];
							$address = $_REQUEST['address'];
							$phone = $_REQUEST['phone'];
							$order_session = $_SESSION['ms_session'];
							$ship_business = $_REQUEST['ship_business'];
							$ship_first_name = $_REQUEST['ship_first_name'];
							// $ship_last_name =  $_REQUEST['last_name'];
							$ship_address  = $_REQUEST['ship_address'];
							$ship_city = $_REQUEST['ship_city'];
							$ship_state = $_REQUEST['ship_state'];
							$ship_zip = $_REQUEST['ship_zip'];
							$ship_country = $_REQUEST['ship_country'];
							$ip_address = getUserIP();
							$coupon_id = $_REQUEST['coupon_id'];
							$coupon_name = $_REQUEST['coupon_name'];
							if(!empty($_SESSION['pid'])) { 
								$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
								$customer_id = $person['p_id'];
							} else { 
								$_SESSION['createaccount'] = true;
							}
							// print "<pre>"; print_r($_REQUEST); print "</pre>";
							// exit();
							include $setup['path']."/sy-inc/store/payment/payment-complete.php";
							createOrder();



						echo '<pre>';
						print_r($httpParsedResponseAr);
						echo '</pre>';
					} else  {
						echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
						echo '<pre>';
						print_r($httpParsedResponseAr);
						echo '</pre>';

					}

		}else{
				echo '<div class="error">Your transaction was not successful: >'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				echo "<div>&nbsp;</div>";
				echo "<div>&nbsp;</div>";
				echo "<div class=\"pc center\"><a href=\"/index.php?view=cart\">Click here to return to your shopping cart</a></div>";
				echo "<div>&nbsp;</div>";
				echo "<div>&nbsp;</div>";
				echo "<div>&nbsp;</div>";
				echo "<div>&nbsp;</div>";
				insertSQL("ms_pay_attempts", "date=NOW(), ip='".getUserIP()."', vis_id='".$_SESSION['vid']."', card='PayPal Express', response_message='".addslashes(stripslashes($httpParsedResponseAr["L_LONGMESSAGE0"]))."', email='".$httpParsedResponseAr["EMAIL"]."', name='".addslashes(stripslashes($httpParsedResponseAr["FIRSTNAME"]))." ".addslashes(stripslashes($httpParsedResponseAr["LASTNAME"]))."' ");
				echo '<pre>';
				// print_r($httpParsedResponseAr);
				echo '</pre>';
		}
	} else { 
		$paypal= new MyPayPal();

		$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
//	print "<pre>"; print_r($httpParsedResponseAr); print "</pre>";
if($ship == true) { 
	$shipc = doSQL("ms_countries", "*", "WHERE country_name='".urldecode($httpParsedResponseAr['SHIPTOCOUNTRYNAME'])."' ");
	if($shipc['ship_to'] !== "1") { ?>
		<div class="error">Sorry, but we can not ship to <?php print $shipc['country_name'];?></div>
	<?php 
		$stop_checkout = true;
	}
	?>
	<?php $shipstate = doSQL("ms_states", "*", "WHERE state_abr='".$httpParsedResponseAr['SHIPTOSTATE']."' ");
	if(!empty($shipstate['state_id'])) { 
		if($shipstate['state_ship_to'] !== "1") { ?>
		<div class="error">Sorry, but we can not ship to <?php print $shipstate['state_name'];?></div>
		<?php 
			$stop_checkout = true;
			} 
	}
}
?>
<?php if($stop_checkout == true) { ?>
<div>&nbsp;</div>
<div class="pc"><a href="/index.php?view=cart">Click here to return to your shopping cart</a></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php } ?>
<?php if($stop_checkout !== true) { ?>




<!-- 
		<div id="checkoutinfo" class="large2">
		<?php if($ship == true) { ?>
		<div class="pc"><h3><?php print _shipping_address_;?></h3></div>
		<?php } else { ?>
		<div class="pc"><h3><?php print _billing_address_;?></h3></div>
		<?php } ?>
			<div id="ship_ck_name" class="pc">
				<span id="ship_ck_first_name"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['FIRSTNAME']));?></span> 
				<span id="ship_ck_last_name"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['LASTNAME']));?></span>
			</div>
			<div id="ship_ck_email" class="pc"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['EMAIL']));?></div>
			<div id="ship_ck_address" class="pc"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['SHIPTOSTREET']));?></div>
			<div id="ship_ck_address_3" class="pc">
				<span id="ship_ck_city"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['SHIPTOCITY']));?></span> <span id="ship_ck_state"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['SHIPTOSTATE']));?></span><?php if(!empty($httpParsedResponseAr['SHIPTOSTATE'])) { ?>,<?php } ?> <span id="ship_ck_zip"><?php print htmlspecialchars(urldecode($httpParsedResponseAr['SHIPTOZIP']));?></span>
			</div>
		</div>

	</div>
	<div>&nbsp;</div>
-->
<?php // print "<pre>"; print_r($httpParsedResponseAr); print "</pre>";  ?>
	<div  style="width: 100%; max-width: 600px; margin: auto;" >
	
	<div id="checkouttotals" class="left nofloatsmallleft">
		<div style="margin: auto; padding: 0px 32px;">
		<div class="pc"><h2><?php print _my_cart_;?></h2></div>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="pc large2"><?php print _items_;?></td><td class="pc textright large2"><?php print $total['total_items'];?></td>
			</tr>
			<tr>
				<td class="pc large2"><?php print _subtotal_;?></td><td class="pc textright large2" id="subtotal" price="<?php print $pay_total;?>"><?php print showPrice($pay_total);?>
				</td>
			</tr>
			<?php 
			$discount_total = $total['discount_total'];
			$promo = getCoupon($mssess,$discount_total);
			if(!empty($promo['promo_id'])) { 
							?>

			<tr id="couponrow">
				<td class="pc large2"><?php print _promo_code_;?> <?php print $promo['promo_name'];?></td><td class="pc textright large2">
				<?php 
				$discount = $promo['promo_discount_amount'];

				if($store['tax_discount'] == "after") { 
					if($taxable_total > 0) { 
						$taxable_total	= $taxable_total - $discount;
					}
				}
				if($taxable_total < 0 ) { $taxable_total = 0; } 

				if($pay_total < $discount) { 
					$discount = $pay_total;
				}
				$coupon_id = $promo['promo_id'];
				$coupon_name = $promo['promo_name']." - ".$promo['promo_code'];
				print "(".showPrice($discount).")";
				?>
				</td>
			</tr>
			<?php } ?>
		<script>
			tax_shipping = <?php print $store['tax_shipping'];?>;
		</script>

			<?php if($ship == true) { ?>
			<?php
			 if(!empty($httpParsedResponseAr['SHIPTOSTATE'])) { 
			if($shipping_group_check['cart_id'] > 0) { 
				$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$shipping_group_check['shipping_group']."' ");
			} else { 
				$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_default='1' ");
			}

				$state = doSQL("ms_states", "*", "WHERE state_abr='".$httpParsedResponseAr['SHIPTOSTATE']."' ");
				$shipmethod = doSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
				if($store['shipping_discount'] == "after") { 
					$ship_on = $total['ship_cal_total'] - ($total['ship_cal_total'] * $promo['code_discount_amount']) / 100;
				} else { 
					$ship_on = $total['ship_cal_total'];
				}
				$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$shipmethod['method_id']."' AND price_from<='".$ship_on."' AND price_to>='".$ship_on."' ");
				$price = $price['price_amount'] + (($price['price_amount'] * $state['state_add_ship_percent']) / 100);
		}
			?>
			<tr <?php if(empty($httpParsedResponseAr['SHIPTOSTATE'])) { ?> style="display: none;" <?php } ?>  id="shippingrow">
				<td class="pc large2"><?php print _shipping_;?></td><td class="pc textright large2" id="shippingtotal" price="<?php print $price;?>"><?php print showPrice($price);?>
				</td>
			</tr>
			<?php } ?>

			<tr <?php if(empty($httpParsedResponseAr['SHIPTOSTATE'])) { ?> style="display: none;" <?php } ?> id="taxrow" valign="top">
			<?php if(!empty($httpParsedResponseAr['SHIPTOSTATE'])) { 
				$per = doSQL("ms_states", "*", "WHERE state_abr='".$httpParsedResponseAr['SHIPTOSTATE']."' ");
				$tax = $taxable_total * $per['state_tax'] / 100;
				$tax_percentage = $per['state_tax'];
			}?>
				<td class="pc"><span class=" large2"><?php print _tax_;?></span>
				<?php if($taxable_total > 0) { ?>
				<span id="taxon">
				<?php if($pay_total !== $taxable_total) { ?><br><span id="taxonpercent"><?php print $per['state_tax'];?></span>% <?php print _on_;?> <?php print showPrice($taxable_total);?><?php } ?>
				</span>
				<?php } ?></td><td class="pc textright large2" id="taxtotal" price="<?php print $tax;?>">
				<?php if(!empty($httpParsedResponseAr['SHIPTOSTATE'])) { print showPrice($tax);	} else { print "TBD"; }  ?>
				</td>
			</tr>
			
		<?php $per = doSQL("ms_countries", "*", "WHERE country_name='".urldecode($httpParsedResponseAr['SHIPTOCOUNTRYNAME'])."' "); ?>

			<tr <?php if($per['vat'] <=0) { ?> style="display: none;" <?php } ?> id="vatrow" valign="top">
			<?php if(!empty($httpParsedResponseAr['SHIPTOCOUNTRYNAME'])) { 
				$vat = $taxable_total * $per['vat'] / 100;
				$vat_percentage = $per['vat'];
			}?>
				<td class="pc"><span class=" large2"><?php print _vat_;?></span>
				<?php if($taxable_total > 0) { ?>
				<span id="taxon">
				<?php if($pay_total !== $taxable_total) { ?><br><span id="taxonpercent"><?php print $per['vat'];?></span>% <?php print _on_;?> <?php print showPrice($taxable_total);?><?php } ?>
				</span>
				<?php } ?></td><td class="pc textright large2" id="vattotal">
				<?php if(!empty($httpParsedResponseAr['SHIPTOCOUNTRYNAME'])) { print showPrice($vat);	} else { print "TBD"; }  ?>
				</td>
			</tr>

			<tr id="grandtotalrow" >
				<?php $gtotal = $pay_total  - $discount + $price + $tax + $vat; ?>
				<td class="pc large1"><?php print _total_;?></td><td class="pc textright large1" id="grandtotal"><?php print showPrice($gtotal); ?>
				</td>
			</tr>

		</table>
		</div>
	</div>

	<form method=POST name="checkout" id="checkout" action="<?php print $site_setup['index_page'];?>" loggedin="<?php if(customerLoggedIn()) { print "1"; } else { print "0"; } ?>" ckpass="1" ship="<?php if($ship == true) { print "1"; } else { print "0"; } ?>" taxwhere="<?php print $store['tax_address'];?>" shipdisc="<?php print $store['shipping_discount'];?>" taxdisc="<?php print $store['tax_discount'];?>" account="<?php print $store['checkout_account'];?>">
	<input type="hidden" name="shipping_price" id="shipping_price" value="<?php print $price;?>">
	<input type="hidden" name="tax_price" id="tax_price" value="<?php print $tax;?>">
	<input type="hidden" name="vat_price" id="vat_price" value="<?php print $vat;?>">
	<input type="hidden" name="vat_percentage" id="vat_percentage" value="<?php print $vat_percentage;?>">
	<input type="hidden" name="grand_total" id="grand_total" value="<?php print round($gtotal,2);?>">
	<input type="hidden" name="sub_total" id="sub_total" value="<?php print $pay_total;?>">
	<input type="hidden" name="discount_amount" id="discount_amount" value="<?php print $discount;?>">
	<input type="hidden" name="coupon_id" id="coupon_id" value="<?php print $coupon_id;?>">
	<input type="hidden" name="coupon_name" id="coupon_name" value="<?php print $coupon_name;?>">
	<input type="hidden" name="taxable_amount" id="taxable_amount" data-org-tax-amount="<?php print $taxable_total;?>" value="<?php print $taxable_total;?>">
	<input type="hidden" name="tax_percentage" id="tax_percentage" value="<?php print $tax_percentage;?>">
	<input type="hidden" name="eb_amount" id="eb_amount" value="<?php print $total['eb_amount'];?>">
	<input type="hidden" name="ship_on" id="ship_on" value="<?php print $ship_on;?>">
	<input type="hidden" name="credit_amount" id="credit_amount" value="<?php print $credit['tot'];?>">
	<input type="hidden" name="token" value="<?php print $token;?>">
	<input type="hidden" name="PayerID" value="<?php print $payerid;?>">
	<input type="hidden" name="first_name" id="first_name" value="<?php print urldecode($httpParsedResponseAr['FIRSTNAME']);?>">
	<input type="hidden" name="last_name" id="last_name" value="<?php print urldecode($httpParsedResponseAr['LASTNAME']);?>">
	<input type="hidden" name="email_address" id="email_address" value="<?php print urldecode($httpParsedResponseAr['EMAIL']);?>">

	<input type="hidden" name="address" id="address" value="<?php print urldecode($httpParsedResponseAr['SHIPTOSTREET']);?>">
	<input type="hidden" name="city" id="city" value="<?php print urldecode($httpParsedResponseAr['SHIPTOCITY']);?>">
	<input type="hidden" name="state" id="state" value="<?php print urldecode($httpParsedResponseAr['SHIPTOSTATE']);?>">
	<input type="hidden" name="zip" id="zip" value="<?php print urldecode($httpParsedResponseAr['SHIPTOZIP']);?>">
	<input type="hidden" name="country" id="country" value="<?php print urldecode($httpParsedResponseAr['SHIPTOCOUNTRYNAME']);?>">

	<input type="hidden" name="ship_first_name" id="ship_first_name" value="<?php print urldecode($httpParsedResponseAr['SHIPTONAME']);?>">
	<input type="hidden" name="ship_address" id="ship_address" value="<?php print urldecode($httpParsedResponseAr['SHIPTOSTREET']);?>">
	<input type="hidden" name="ship_city" id="ship_city" value="<?php print urldecode($httpParsedResponseAr['SHIPTOCITY']);?>">
	<input type="hidden" name="ship_state" id="ship_state" value="<?php print urldecode($httpParsedResponseAr['SHIPTOSTATE']);?>">
	<input type="hidden" name="ship_zip" id="ship_zip" value="<?php print urldecode($httpParsedResponseAr['SHIPTOZIP']);?>">
	<input type="hidden" name="ship_country" id="ship_country" value="<?php print urldecode($httpParsedResponseAr['SHIPTOCOUNTRYNAME']);?>">


<div id=""  class="right nofloatsmallleft">
	<div style="margin: auto; padding: 0px 32px;">
		<?php if($ship == true) { ?>
		<div class="pc"><h2><?php print _checkout_progress_shipping_;?></h2></div>
		<div id="shippingselect">

		<?php
			
			if($shipping_group_check['cart_id'] > 0) { 
				$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$shipping_group_check['shipping_group']."' ");
			} else { 
				$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_default='1' ");
			}


				$ships = whileSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
				while($ship = mysqli_fetch_array($ships)) { 
					$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$ship['method_id']."' AND price_from<='$ship_on' AND price_to>='$ship_on' ");
					$pt++;
					?>
					<div>
					<div class="pc"><input type="radio" class="checkbox" name="ship_select" id="ship_select-<?php print $price['price_id'];?>" price="<?php print $price['price_amount'];?>" priceshow="<?php print showPrice($price['price_amount']);?>" onClick="addshipping();" value="<?php print $ship['method_id'];?>" <?php if($pt <=1) { print "checked"; } ?>> <label for="ship_select-<?php print $price['price_id'];?>"><?php print $ship['method_name'].": "; if($price['price_amount'] <= 0) { print _free_; } else { print showPrice($price['price_amount']); } ?></label></div>
					<?php if(!empty($ship['method_descr'])) { ?><div class="pc"><?php print nl2br($ship['method_descr']);?></div><?php } ?>
					</div>
					<div>&nbsp;</div>
				<?php } ?>
				</div>

			<?php } ?>
		</div>
</div>
<div class="clear"></div>
<div class="center"  style="padding: 0px 32px; text-align: center;">
			<div class="pc center">
			<input type="submit" name="submit" id="placeexpress" value="Place Order" style="font-size: 38px;" class="checkout" onClick="expressPlaceOrder();">
			<div id="expressloading" style="display: none;"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif" width="160" height="24"></div>
			<div class="clear"></div>
			</div>
			<div class="pc center"><?php print _pp_express_place_your_order_description_;?></div>
			<div>&nbsp;</div>

	</div>
</div>

<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div id="paymentselect" style="display: none;">
<script type="text/javascript">

function SubmitForm()
{
	document.myform.submit();
}


	function selectPaymentOption(thisopt,thisform) { 
	$(".payoption").each(function(i){
		var this_id = this.id;
		$("#"+this_id).hide();
	} );
	$("#"+thisform).show();
}

</script>
<input type="hidden" name="view" value="checkoutexpress">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="amount" value="<?php print $pay_total;?>">
</center>
</div>
</form>
<div class="clear"></div>


<?php }  // end stop checkout?>

<?php } ?>

<?php } ?>
