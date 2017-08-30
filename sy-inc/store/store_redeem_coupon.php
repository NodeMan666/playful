<?php 
require("../../sy-config.php");
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
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
function addprintcreditpackages($pack,$cart_id) { 

	$product_name = $pack['package_name'];
	$p_cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$date['date_id']."', cart_cost='".$pack['package_cost']."' , cart_group_id='".$group['group_id']."', cart_credit='".$pack['package_credit']."', cart_extra_ship='".$cart_extra_ship."', cart_package_buy_all='".$pack['package_buy_all']."', cart_no_discount='".$pack['package_no_discount']."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_sku='".addslashes(stripslashes($pack['package_internal_name']))."', cart_package_include='".$cart_id."' ");



	if($pack['package_select_only'] == "1") { 
		$p = 1;
		while($p <= $pack['package_select_amount']) { 
			insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
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
				insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
				$q++;
			}
		}
	}
}
if($_REQUEST['action'] == "checkpromo") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
		$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
	}

	$new_date = date("Y-m-d", mktime(date('H')+ $site_setup['time_diff'], date('i'), date('s'), date('m') , date('d'), date('Y')));
	$check_promo = doSQL("ms_promo_codes", "*", "WHERE code_code='".$_REQUEST['promo_code']."' AND code_use_status='0' AND (code_end_date>='$new_date' OR code_end_date='0000-00-00') ");
	if(empty($check_promo['code_id'])) {
		print  _promo_code_invalid_;
		exit();
	} elseif(!empty($check_promo['code_id'])) {

		$check_same_in_cart = doSQL("ms_cart", "*", "WHERE  ".checkCartSession()."  AND cart_coupon!='0' AND cart_order='0' AND cart_coupon='".$check_promo['code_id']."'  ");
		
		if($check_promo['code_print_credit'] > 0) { 
			$check_same_type_in_cart = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE  ".checkCartSession()."  AND cart_coupon!='0' AND cart_order='0' AND code_print_credit>'0'  ");
		} else { 
			$check_same_type_in_cart = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE  ".checkCartSession()."  AND cart_coupon!='0' AND cart_order='0' AND code_print_credit<='0'  ");
		}

		if((!empty($check_same_in_cart['cart_id'])) || (!empty($check_same_type_in_cart['cart_id'])) == true) {
			if($check_same_in_cart['cart_id'] > 0) { 
				print  _promo_code_exists_in_cart_;
			} else { 
				print _coupon_type_exists_in_cart_;
			}
			session_write_close();
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
						$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_price='".$check_promo['code_price']."', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$check_promo['code_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($check_promo['code_code']))."' , cart_group_id='".$group['group_id']."', cart_no_discount='".$check_promo['code_no_discount']."', cart_package_no_select='1', cart_bonus_coupon='".$promoid."' ");

						$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
						while($prod = mysqli_fetch_array($prods)) { 
							addprintcreditpackages($prod,$cart_id);
						}
					} else { 

						$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($check_promo['code_name']))."', cart_price='".$check_promo['code_price']."', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$check_promo['code_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($check_promo['code_code']))."' , cart_group_id='".$group['group_id']."', cart_no_discount='".$check_promo['code_no_discount']."', cart_bonus_coupon='".$promoid."' ");


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
				}
				// $_SESSION['promo_success_bonus'] = $check_promo['code_id'];
				if(!empty($_SESSION['last_gallery'])) { 
					$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
					if(!empty($ldate['date_id'])) { 
						if($_SESSION['last_gallery_sub'] > 0) { 
							$lsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
							$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/?sub=".$lsub['sub_link'];
							 } else {
							$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/";
						}  
					}
				}

			} else { 
				// $_SESSION['promo_success'] = true;
				if($_REQUEST['where'] == "checkout") {
					$where = "checkout";
				} else { 
					$_SESSION['promo_success'] = true;
					$where = "cart";
				}
			}
			$code_message = str_replace("[MIN_AMOUNT]",showPrice($check_promo['code_min']),$check_promo['code_redeem_instructions']); 
			print "good|".$check_promo['code_redeem_success']."|<b>".$check_promo['code_name']."</b><br>".nl2br($code_message)."|".$where."|<b><a href=\"".$l_link."\">"._return_to_last_gallery_page_." ".$ldate['date_title']."</a></b>";
			exit();
		}
	}
}


?>
<script>
function checkprintcredit() { 
	var fields = {};
	var rf = false;
	var stop;
	var mes;
	 $("#printcreditresponse").removeClass("error").html("").slideUp(100);

	$(".couponrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );



	if(rf == true || stop == true) {
		if(rf == true) {
			 // $("#printcreditresponse").html('<div class="pc"><div class="error">You have required fields empty</div></div>').show();
		}
		return false;
	} else { 
		$('.printcredit').each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('id')] = $this.val(); 
				//fields[$this.attr('name')] = $this.val(); 
			}
		});
		$.post(tempfolder+'/sy-inc/store/store_redeem_coupon.php', fields,	function (data) { 
			data = $.trim(data);
			response = data.split('|');
			if(response[0] == "good") { 
				if(response[3] == "checkout") { 
					window.location.href="index.php?view=checkout";
				} else if(response[3] == "cart") { 
					window.location.href="index.php?view=cart";
				} else { 
					updateCartMenu();
					$("#redeemform").slideUp(200, function() { 
						$("#addprintcreditphotos").html(response[2]).slideDown(200);
					});
					 $("#printcreditresponse").removeClass("error").addClass("success").html(response[1]).slideDown(100);
					 if(response[4] !== "") { 
						$("#redeemreturnlink").html(response[4]).slideDown(100);
					 } else { 
						$("#redeemcontinue").slideDown(100);
					 }
					totalpackage = Math.abs($("#vinfo").attr("has_package"));

					$("#vinfo").attr("has_package",totalpackage + 1);
					$("#vinfo").attr("view_package","1");
				}
			} else { 
			 $("#printcreditresponse").removeClass("success").addClass("error").html(data).slideDown(100);
			}
		});



		return false;
	}
	return false;

}
</script>
<div style="padding: 24px;" class="inner">
<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>

	<div class="pc center"><h3><?php print _enter_pomo_code_;?></h3><div>
	<?php 
	
	$carts = whileSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()." AND cart_coupon!='0' AND cart_order<='0'  " );
	if((mysqli_num_rows($carts) > 0) && ($site_setup['coupon_use_both'] == "0") == true) { ?>
	<div class="pc center"><?php print _coupon_in_cart_error_;?></div>
		<div id="redeemcontinue" class="pc"><a href="" onclick="closewindowpopup(); return false;"><?php print _continue_;?></a></div>

	<?php } else { ?>
		<div id="redeemform">
			<div class="pc center"><?php print _enter_pomo_code_text_;?></div>
			<form method="post" name="redeempc" id="redeempc" action="" onsubmit="checkprintcredit(); return false;">
			<div class="pc center"><input type="text" id="promo_code" size="20" class="couponrequired printcredit" value="<?php print $_REQUEST['code'];?>"></div>
			<input type="hidden" id="action" value="checkpromo" class="printcredit">
			<input type="hidden" id="where" value="<?php print $_REQUEST['where'];?>" class="printcredit">
			<div class="pc center"><input type="submit" id="submit" value="<?php print _promo_button_;?>" class="submit"></div>
			<div>&nbsp;</div>
			<div class="pc center"><a href="" onclick="closewindowpopup(); return false;"><?php print _cancel_;?></a></div>
			</form>
		</div>


		<div id="printcreditresponse" class="hide pc center"></div>
		<div id="addprintcreditphotos" class="hide pc center">
		<br><br>
		</div>
		<div id="redeemcontinue" class="pc hide"><a href="" onclick="closewindowpopup(); return false;"><?php print _continue_;?></a></div>
		<div id="redeemreturnlink" class="pc hide"></div>
		<?php } ?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
</div>
<?php  mysqli_close($dbcon); ?>
