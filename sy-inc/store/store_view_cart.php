<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
date_default_timezone_set(''.$site_setup['time_zone'].'');

checkqtydiscounts();
// checkqtyproddiscounts();
checkCouponOnePerPerson($email);

?>
<div id="storeProductList">
<?php 
if($_SESSION['onepersoncoupon'] == true) { ?>

<div class="error"><?php print _coupon_used_;?></div>
<?php 
unset($_SESSION['onepersoncoupon']);
unset($_SESSION['promo_success']);
} 


if($_SESSION['couponexpired'] == true) { ?>
<div class="error"><?php print _coupon_expired_;?></div>
<?php 
unset($_SESSION['couponexpired']);
unset($_SESSION['promo_success']);
} 

if(!empty($_SESSION['last_gallery'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
	if($date['date_gallery_exclusive'] == "1") { 
		$ge_return_link = $setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/";
	}
}



/* Checking for out of stock items */
$carts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_product>='0' AND prod_inventory_control='1' AND cart_order<='0'  " );
while($cart= mysqli_fetch_array($carts)) {
	if(!empty($cart['cart_sub_id'])) {
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
		if($sub['sub_qty']<=0) { 
			$total_out_of_stock++;
			$out_of_stock .= htmlspecialchars($cart['date_title'])."||";
			deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		}
	} else { 
		if($cart['prod_qty']<=0) { 
			$total_out_of_stock++;
			$out_of_stock .= htmlspecialchars($cart['date_title'])."||";
			deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		}
	}
	if($total_out_of_stock > 0) { ?>
		<div class="pc"><h2>I'm sorry, but the following item(s) in your shopping is no longer available:</h2> </div>
		<?php 
		$ous = explode("||",$out_of_stock);
			foreach($ous AS $ou) { 
				if(!empty($ou)) { 
					$o++;?>
					<div class="pc" style="margin-left: 32px;"><h3><?php print $o;?>) <?php print $ou;?></h3></div>
				<?php 
				}
			}
		?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<?php 
	}
}

?>
<?php
if(!empty($_SESSION['outofstockmessage'])) { 

	?>
	<div class="pc"><h2>I'm sorry, but the following item(s) in your shopping is no longer available:</h2> </div>
	<?php $ous = explode("||",$_SESSION['outofstockmessage']);
	foreach($ous AS $ou) { 
		if(!empty($ou)) { ?>
	<div class="pc" style="margin-left: 32px;"><h3><?php print $ou;?></h3></div>
	<?php }
	}
	?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

<?php 
	unset($_SESSION['outofstock']);
	unset($_SESSION['totaloutofstock']);
	unset($_SESSION['outofstockmessage']);
}


if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE ".checkCartSession()." AND cart_ship='1' AND cart_order<='0' ") > 0) { 
	$ship = true;
}
if($_REQUEST['action'] == "emptycart") {

	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_order<='0' " );
	while($cart= mysqli_fetch_array($carts)) {
		deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ", "1");
		deleteSQL2("ms_cart_options", "WHERE co_cart_id='".$cart['cart_id']."' ");

	}
	if(!empty($ge_return_link)) { 
		header("location: ".$ge_return_link."?view=cart");
	} else { 
		header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart");
	}
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "removePromo") {
	foreach($_REQUEST AS $id => $value) {
		if(!empty($value)) { 
			if(!is_array($value)) { 
				$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
				$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			}
		}
	}

	$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE ".checkCartSession()." AND MD5(cart_id)='".$_REQUEST['cid']."'  AND cart_coupon!='0' ");
	if(!empty($check_in_cart['cart_id'])) {
		deleteSQL("ms_cart", "WHERE cart_id='".$check_in_cart['cart_id']."' ","1");
		session_write_close();
		if(!empty($ge_return_link)) { 
			header("location: ".$ge_return_link."?view=cart");
		} else { 
			header("location: ".$site_setup['index_page']."?view=cart");
		}
		exit();
	} else {
		exit("An error has occured. Press back on your browser to continue");
	}
}


if($_REQUEST['promo_action'] == "checkpromo") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
		$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
	}
	if(empty($_REQUEST['promo_code'])) {
		session_write_close();
		if(!empty($ge_return_link)) { 
			header("location: ".$ge_return_link."?view=cart");
		} else { 
			header("location: ".$site_setup['index_page']."?view=cart");
		}
		exit();
	}
	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	$check_promo = doSQL("ms_promo_codes", "*", "WHERE code_code='".$_REQUEST['promo_code']."' AND code_use_status='0' AND (code_end_date>='$new_date' OR code_end_date='0000-00-00') ");
	if(empty($check_promo['code_id'])) {
		$_SESSION['promo_error'] = _promo_code_invalid_;
		session_write_close();
		if($_REQUEST['return'] == "checkout") { 
			if(!empty($ge_return_link)) { 
				header("location: ".$ge_return_link."?view=checkout");
			} else { 
				header("location: ".$site_setup['index_page']."?view=checkout");
			}
		} else { 
			if(!empty($ge_return_link)) { 
				header("location: ".$ge_return_link."?view=cart");
			} else { 
				header("location: ".$site_setup['index_page']."?view=cart");
			}
		}
		exit();
	} elseif(!empty($check_promo['code_id'])) {

		$check_in_cart = doSQL("ms_cart", "*", "WHERE  ".checkCartSession()."  AND cart_coupon!='0' AND cart_order='0' AND cart_coupon='".$check_promo['code_id']."'  ");
		if(!empty($check_in_cart['cart_id'])) {
			$_SESSION['promo_error'] = _promo_code_exists_in_cart_;
			session_write_close();
			if($_REQUEST['return'] == "checkout") { 
				if(!empty($ge_return_link)) { 
					header("location: ".$ge_return_link."?view=checkout");
				} else { 
					header("location: ".$site_setup['index_page']."?view=checkout");
				}
			} else { 
				if(!empty($ge_return_link)) { 
					header("location: ".$ge_return_link."?view=cart");
				} else { 
					header("location: ".$site_setup['index_page']."?view=cart");
				}
			}
			exit();

		} else {




			$promoid = insertSQL("ms_cart", "cart_coupon='".addslashes(stripslashes($check_promo['code_id']))."', cart_coupon_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_ip='".getUserIP()."', cart_date=NOW() ");

			if($check_promo['code_print_credit'] > 0) { 

				// ADDING COUPON BONUS
				$pack = doSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_id='".$check_promo['code_print_credit']."' ");
				if($pack['pc_id'] <= 0) { 
					print _print_credit_not_valid_;
				} else { 
					if(empty($pack['package_id'])) { 
						die("Unable to find product");
					}

					if($pack['pc_ship'] =="1") {
						$cart_ship = 1;
					}
					if($con['pc_price'] > 0) { 
						$cart_price = $con['pc_price'];
					} else { 
						$cart_price = $pack['package_price'];
					}
					$product_name = $pack['pc_name'];


					if($pack['package_select_only'] =="2") { 
						$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($check_promo['code_code']))."' , cart_group_id='".$group['group_id']."', cart_package_no_select='1', cart_bonus_coupon='".$promoid."' ");

						$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
						while($prod = mysqli_fetch_array($prods)) { 
							addprintcreditpackages($prod,$cart_id);
						}
					} else { 

						$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($check_promo['code_code']))."' , cart_group_id='".$group['group_id']."', cart_bonus_coupon='".$promoid."' ");


						if($pack['package_select_only'] == "1") { 
							$p = 1;
							while($p <= $pack['package_select_amount']) { 
								insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
								$p++;
							}


						} else { 

							$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
							while($prod = mysqli_fetch_array($prods)) { 
								$cart_download = 0;
								$cart_ship = 0;
								if($prod['pp_type'] =="download") {
									$cart_download = 1;
									$cart_ship = 0;
									}
								$q = 1;
								while($q <= $prod['con_qty']) { 
									insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
									$q++;
								}
							}
						}
					}
					print "good";
				}
				$_SESSION['promo_success_bonus'] = $check_promo['code_id'];
			} else { 
				$_SESSION['promo_success'] = true;

			}

			session_write_close();
			
			if($_REQUEST['return'] == "checkout") { 
				if(!empty($ge_return_link)) { 
					header("location: ".$ge_return_link."?view=checkout");
				} else { 
					header("location: ".$site_setup['index_page']."?view=checkout");
				}
			} else { 
				if(!empty($ge_return_link)) { 
					header("location: ".$ge_return_link."?view=cart");
				} else { 
					header("location: ".$site_setup['index_page']."?view=cart");
				}
			}
			exit();
		}
	}
}

$total = shoppingCartTotal($mssess);
$pay_total = $total['show_cart_total'];
// print "<li>credit: ".$total['cart_credit']." <li>photo_prods: ".$total['photo_prods']." <li>Tax total: ".$total['tax_total']."";

if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_order<='0' ")>0) {  ?>
	
	
	<div class="right pc textright checkoutpagebutton">
	<?php if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1") ==true) { 
	?>
	<div class="pc"><a href="" onclick="ppexpresscheckout(); return false;"  class="checkoutcart"><?php print _checkout_now_;?></a></div>
	<?php } else { ?>


	<?php 
	if(countIt("ms_payment_options", "WHERE pay_status='1' AND pay_dev_status='1' AND pay_option!='paypalexpress' ") > 0) { 
		 if($store['checkout_ssl'] == "1") { 
			if(!empty($store['checkout_ssl_link'])) { 
				print "<a class=\"checkoutcart\" href=\"".$store['checkout_ssl_link']."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
			} else { 
				if(!empty($ge_return_link)) { 
					print "<a  class=\"checkoutcart\" href=\"https://".$_SERVER['HTTP_HOST']."".$ge_return_link."?view=checkout\">"._checkout_now_."</a>";
				} else { 
					print "<a  class=\"checkoutcart\" href=\"https://".$_SERVER['HTTP_HOST']."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
				}
			}
		 } else { 
			if(!empty($ge_return_link)) { 
				print "<a  class=\"checkoutcart\" href=\"".$ge_return_link."?view=checkout\">"._checkout_now_."</a>";
			} else { 
				print "<a  class=\"checkoutcart\" href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
			}
		 }
	 }
	 ?>	 
<?php } ?>
<div class="clear"></div>
</div>
<?php
}




$total = shoppingCartTotal($mssess);
$pay_total = $total['show_cart_total'];

?>
<div class="pc title"><h1><?php print _store_cart_title_; ?> </h1></div>
<?php if($total['total_items'] > 0) { ?>
<div class="pc"><h3 id="viewcartpagetotal"><?php print "".$total['total_items']." "; if($total['total_items'] > 1) { print _items_; } else { print _item_; } if($total['show_cart_total']> 0) { print " ".showPrice($total['show_cart_total']); } ?></h3></div>
<?php } ?>
<?php 
if(!empty($_SESSION['promo_success_bonus'])) { 
	$code = doSQL("ms_promo_codes", "*", "WHERE code_id='".$_SESSION['promo_success_bonus']."' "); ?>
	<div>&nbsp;</div><div class="pc"><div class="success"><?php print $code['code_redeem_success'];?></div></div>
	<div class="pc bonuscouponredeemmessage" style="font-weight: bold;">
	<?php $code_message = str_replace("[MIN_AMOUNT]",showPrice($code['code_min']),$code['code_redeem_instructions']); ?>
	<?php print nl2br($code_message);?>
	</div>
	<div>&nbsp;</div>
	<?php 
	} ?>

<?php
 
if(!empty($_SESSION['last_gallery'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
	if((!empty($date['date_id']))  && ($date['date_gallery_exclusive'] <= 0) == true) { 
		if($_SESSION['last_gallery_sub'] > 0) { 
			$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
			?>
			<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link'].""; ?>">Continue Shopping</a></div>
		<?php } else { ?>
			<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>">Continue Shopping</a></div>


	<?php } ?>

<?php 
	// unset($_SESSION['last_gallery']);
	}
}
?>
<div class="clear"></div>
<?php 
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			if($setup['no_expired_print_credits'] == true) { 
				$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$person['p_id']."' ");
			} else { 
				$credit = doSQL("ms_credits", "SUM(credit_amount) AS tot", "WHERE credit_customer='".$person['p_id']."' AND (credit_expire='0000-00-00' OR credit_expire>='".date('Y-m-d')."' ) ");
			}
	if($credit['tot'] > 0) { ?>
<div class="pc creditmessage"><?php print _your_have_credit_in_account_; ?> <?php print showPrice($credit['tot']);?>.</div>
<?php } 
}
?>

<?php
if(!empty($_SESSION['promo_error'])) {
	print "<div>&nbsp;</div><div class=error>".$_SESSION['promo_error']."</div><div>&nbsp;</div>";
	unset($_SESSION['promo_error']);
}
?>

<?php
if(!empty($_SESSION['promo_success'])) {
	print "<div>&nbsp;</div><div class=pageContent><div class=\"success\">"._promo_code_good_."</div></div><div>&nbsp;</div>";
	unset($_SESSION['promo_success']);
}
?>
<?php 
if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_order<='0' ")<=0) { 
	$empty_cart = true;
}
print "<div  id=\"cartisempty\" "; if($empty_cart !== true) { print "style=\"display: none;\""; } print ">";
print "<div>&nbsp;</div>";
print "<div class=\"error\">"._store_shopping_cart_empty_."</div></center>";
print "<div>&nbsp;</div>";
print "<div>&nbsp;</div>";

print "<div>&nbsp;</div>";
print "</div>";

if($empty_cart!==true) { ?>
	<div class="pc" id="storecarttext"><?php print _store_cart_text_; ?></div>
<?php 
} ?>

<div id="viewcart">
<?php
	
$action = "viewcart";
$tracknum = 0;

$carts = whileSQL("ms_cart", "*", " WHERE ".checkCartSession()." AND cart_package!='0' AND cart_order<='0' ORDER BY cart_package_no_select DESC, cart_id ASC" );
$tracks_total	= mysqli_num_rows($carts);
while($cart= mysqli_fetch_array($carts)) {
	
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	$tracknum++;
if($cart['cart_price'] != 0){
showPhotoPackage($pack,$cart);

}
	/*if($pack['package_select_only'] !== "2") { 
	if($cart['cart_bonus_coupon'] > 0) { ?>

		<div class="pc"><?php print _selected_photos_; ?></div>

	<?php	} else { 
		?>
		<div class="pc"><?php print _cart_selected_package_photos_; ?></div>
		<?php 
	}
	}*/
	$cart_id = doSQL("ms_cart", "cart_id", "WHERE cart_package_include='".$cart['cart_id']."' ");
	if(isset($cart_id['cart_id'])){

	$cart['cart_id'] =  $cart_id['cart_id'];
	}
	$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_order<='0'  ORDER BY cart_pic_org ASC" );
		while($pcart= mysqli_fetch_array($pcarts)) {
		$tracknum++;
		showPhotoProduct($pcart,"1",$cart);
	}
}

$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='0' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$tracknum++;
	showPhotoProduct($cart, "0","");
}

$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_gift_certificate='1' AND cart_order<='0'  ORDER BY cart_id DESC" );
	while($cart= mysqli_fetch_array($carts)) {
	################################################################
	?>

	<div class="cartitem" id="cart-<?php print MD5($cart['cart_id']);?>">
			<div class="product nofloatsmall">
			<div class="name"><?php print "".$cart['cart_product_name']."";?></div>
			<div class="pc"><?php print _gift_certificate_to_;?>: <?php print $cart['cart_gift_certificate_to_name'];?> (<?php print $cart['cart_gift_certificate_to_email'];?>)</div>
			<div class="pc"><?php print _gift_certificate_from_;?>: <?php print $cart['cart_gift_certificate_from_name'];?> (<?php print $cart['cart_gift_certificate_from_email'];?>)</div>
			<div class="pc"><?php 
			if(!empty($cart['cart_gift_certificate_message'])) { print "<i>".nl2br($cart['cart_gift_certificate_message'])."</i>"; } ?>
			</div>
			<div class="qty">


		</div>
		<?php if($noactions !== true) { ?>
		<div class="remove addsASSA"><a href="" onclick="giftcertificate('<?php print MD5($cart['cart_id']);?>'); return false;" class="the-icons icon-pencil"><?php print _edit_;?></a> <a href="" onClick="removeFromCart('<?php print MD5($cart['cart_id']);?>'); return false;"  class="the-icons icon-trash-empty"><?php print _remove_from_cart_;?></a></div>
		<?php } ?>
		</div>

		<?php 
		$show_price = $cart['cart_price'];
			if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			 $show_price =  $show_price+ (($show_price * $site_setup['include_vat_rate']) / 100);
		}
		?>

		<div class="price nofloatsmall"><span class="extprice"><?php print showPrice($cart['cart_qty'] * $show_price);?></span>
		<?php if($cart['cart_qty'] > 1) { ?>
		<div id="eachprice" class="pc">
		<?php print showPrice($show_price);?> <?php print _each_;?>
		</div>
		<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
	<?php


	##################################################
}

$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_store_product!='0' AND cart_order<='0' ORDER BY cart_id DESC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
	$tracknum++;
	showStoreProduct($date,$cart);
	$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0' AND cart_order<='0'  ORDER BY cart_pic_org ASC" );
	if(mysqli_num_rows($pcarts) > 0) { 
		?>	<div class="pc"><?php print _cart_selected_product_photos_; ?></div>
		<?php 
		}
		while($pcart= mysqli_fetch_array($pcarts)) {
			$tracknum++;
			showPhotoProduct($pcart,"1",$cart);
		}
	if(mysqli_num_rows($pcarts) > 0) { 
		print "<div>&nbsp;</div><div>&nbsp;</div>";
	}
}

$carts = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE ".checkCartSession()."  AND cart_order<='0' AND co_pic_id>'0' GROUP BY co_opt_name ");
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$tracknum++;
	showImageOptions($cart, "0","");
}

?>
</div>
<?php // PROMO COUPONS ?>
<?php
$action = "viewcart";
$tracknum = 0;

$carts = whileSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()." AND cart_coupon!='0' AND cart_order<='0' AND code_print_credit<='0' " );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$promo = doSQL("ms_promo_codes", "*", "WHERE code_id='".$cart['cart_coupon']."'  ");
	if(($promo['code_end_date']!=="0000-00-00")AND($promo['code_end_date']<date('Y-m-d'))==true) {
		$_SESSION['couponexpired'] = true;
		deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
		header("location: /index.php?view=cart");
		session_write_close();
		exit();

	}
	?>
	<div class="storeProduct left">
		<div class="pc"><h3><?php print _promo_code_." ".$promo['code_name']." "; if($total['promo_discount_amount'] > 0) { if($promo['code_discount_amount'] > 0) { print $promo['code_discount_amount']."% "; } print _promo_savings_." ".showPrice($total['promo_discount_amount'])."" ; }  ?></h3></div>
		<?php if(!empty($promo['code_descr'])) { ?><div class="pc"><?php print $promo['code_descr']; ?></div><?php } ?>
		<div class="pc"><a href="<?php print $setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart&action=removePromo&cid=".MD5($cart['cart_id']);?>"><?php print _remove_from_cart_;?></a></div>
	</div>
		<div class="clear">&nbsp;</div>
	<div>&nbsp;</div>
	<?php } ?>

<?php
if($pay_total > 0) {
	if(!empty($_SESSION['last_gallery'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		if((!empty($date['date_id'])) && ($date['date_gallery_exclusive'] <= 0) == true) { 
			if($_SESSION['last_gallery_sub'] > 0) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
				?>
				<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link'].""; ?>">Continue Shopping</a></div>
			<?php } else { ?>
				<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>">Continue Shopping</a></div>


		<?php } ?>

	<?php 
		// unset($_SESSION['last_gallery']);
		}
	}
}
?>

<?php if($empty_cart!==true) { ?>
<div class="pc textright" id="viewcartpagesubtotal"><?php print _subtotal_." ".showPrice($total['show_cart_total']); ?></div>
<?php } ?>



<div class="right pc textright checkoutpagebutton">

<?php 
	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	if(countIt("ms_promo_codes",  "WHERE code_use_status='0' AND(code_end_date>='$new_date' OR code_end_date='0000-00-00') ")>0) { 
		$check_in_cart = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()." AND cart_coupon!='0' AND code_print_credit<='0' AND cart_order='0'  ");
	?>
		<a onclick="redeemcoupon('cart',''); return false;" class="checkoutcart" style=""><?php print _redeem_coupon_;?></a>
	<?php } ?>

<?php if($pay_total > 0) { ?>

	<?php 
	if(countIt("ms_payment_options", "WHERE pay_status='1' AND pay_dev_status='1' AND pay_option!='paypalexpress' ") > 0) { 
		 if($store['checkout_ssl'] == "1") { 
			if(!empty($store['checkout_ssl_link'])) { 
				print "<a class=\"checkoutcart\" href=\"".$store['checkout_ssl_link']."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
			} else { 
				if(!empty($ge_return_link)) { 
					print "<a  class=\"checkoutcart\" href=\"https://".$_SERVER['HTTP_HOST']."".$ge_return_link."?view=checkout\">"._checkout_now_."</a>";
				} else { 
					print "<a  class=\"checkoutcart\" href=\"https://".$_SERVER['HTTP_HOST']."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
				}
			}
		 } else { 
			if(!empty($ge_return_link)) { 
				print "<a  class=\"checkoutcart\" href=\"".$ge_return_link."?view=checkout\">"._checkout_now_."</a>";
			} else { 
				print "<a  class=\"checkoutcart\" href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"._checkout_now_."</a>";
			}
		 }
	 }
	 ?>	 
	 
	<?php } ?>
	 </div>
	 <div class="clear"></div>
<?php if(($total['total_items'] > 0) && (countIt("ms_cart", "WHERE ".checkCartSession()."  AND cart_order<='0' AND cart_no_delete='1' ")<=0)==true){ ?>
<div class="pc right"><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=cart&action=emptycart" onClick="return confirm('<?php print _empty_cart_confirm_;?>');"><?php print _empty_cart_;?></a></div>
<?php } ?>


<div class="clear"></div>


<div>&nbsp;</div>
<div>&nbsp;</div>
<?php 
function paypalstandard($opt,$pay_total) {
	global $setup,$site_setup;

	print "<input type=\"hidden\" name=\"pay_test_mode\" value=\"".$opt['pay_test_mode']."\">";
	print "<input type=\"hidden\" name=\"totalamount\" value=\"$pay_total\">";
	print "<input type=\"hidden\" name=\"item_name\" value=\"".$site_setup['website_title']." sale\">";
	print "<input type=\"hidden\" name=\"cart_session\" value=\"".$_SESSION['ms_session']."\">";
	if(!empty($opt['pay_button'])) {
		print "<input type=\"image\" border=\"0\" name=\"submit\" src=\"".$opt['pay_button']."\" title=\"".$opt['pay_text']."\" class=\"image\">";
	} else {
		print "<button type=\"submit\" border=\"0\" name=\"submit\">".$opt['pay_text']."</button>";
	}
}
?>
</div>
<div class="pc"><?php print _store_cart_bottom_text_; ?></div>
