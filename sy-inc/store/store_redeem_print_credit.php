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

			if($prod['pp_include_download'] =="1") {
				$cart_download = 1;
			}

			$q = 1;
			while($q <= $prod['con_qty']) { 
				insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
				$q++;
			}
		}
	}
}
if($_POST['action'] == "checkprintcredit") { 
	
	$code = sql_safe($_POST['print_credit_code']);
	$code = strip_tags(trim($code));
	$ck = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_print_credit='".$code."' AND cart_order<='0' ");
	if(!empty($ck['cart_id'])) { 
		print _print_credit_already_in_cart_;
	} else { 

		$pack = doSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_code='$code' AND pc_order<='0' AND (pc_expire>=NOW() OR pc_expire='0000-00-00') AND pc_order<='0' AND pc_coupon<='0' AND pc_code!='' ");
		if($pack['pc_id'] <= 0) { 
			print _print_credit_not_valid_;
		} elseif($pack['pc_id'] > 0) { 
			$opts = whileSQL("ms_product_options LEFT JOIN ms_packages ON ms_product_options.opt_package=ms_packages.package_id", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC ");
			if((mysqli_fetch_array($opts) > 0) && ($_REQUEST['optionsselect'] !== "yes") == true) { 

				## test if credit has options 
				print "options|".$pack['pc_code'];
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

				if($pack['package_add_ship'] > 0) { 
					$cart_extra_ship = $pack['package_add_ship'];
				}

				if($pack['package_select_only'] =="2") { 
					$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($pack['pc_code']))."' , cart_group_id='".$group['group_id']."', cart_package_no_select='1', cart_extra_ship='".$cart_extra_ship."' ");

					$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
					while($prod = mysqli_fetch_array($prods)) { 
						addprintcreditpackages($prod,$cart_id);
					}
				} else { 

					$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($pack['pc_code']))."' , cart_group_id='".$group['group_id']."', cart_extra_ship='".$cart_extra_ship."' ");


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
								if($prod['pp_include_download'] =="1") {
									$cart_download = 1;
								}

							$q = 1;
							while($q <= $prod['con_qty']) { 
								insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
								$q++;
							}
						}
					}
				}

				
				## Lets get the print credit options 

				$opts = whileSQL("ms_product_options", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC ");
				while($opt = mysqli_fetch_array($opts))  {
					if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
						if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")==true) { 
							$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
							$opt_price = $sel['sel_price'];
							if($pack['package_buy_all'] == "1") { 
								if($pack['package_buy_all_price_type'] == "3") { 
									$opt_price = $sel['sel_price'];
								} else  { 
									$opt_price = $sel['sel_price']* $total_images;
								}

							}
							$opt_select_name = $sel['sel_name'];
							if($sel['sel_photos'] > 0) { 
								$pack['package_select_amount'] = $sel['sel_photos'];
							}

						}
						if($opt['opt_type'] == "text") { 
							$opt_price = $opt['opt_price'];
							if($pack['package_buy_all'] == "1") { 
								if($pack['package_buy_all_price_type'] == "3") { 
									$opt_price = $sel['sel_price'];
								} else  { 
									$opt_price = $sel['sel_price']* $total_images;
								}
							}
							$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
						}
						if($opt['opt_type'] == "checkbox") { 
							$opt_price = $opt['opt_price_checked'];
							if($pack['package_buy_all'] == "1") { 
								if($pack['package_buy_all_price_type'] == "3") { 
									$opt_price = $opt['opt_price_checked'];
								} else  { 
									$opt_price = $opt['opt_price_checked'] * $total_images;
								}
							}
							$opt_select_name = _selected_;
						}

						if($opt['opt_type'] == "download") { 
							$opt_price = $opt['opt_price_download'] * $total_images;;
							$opt_select_name = _selected_;
							$co_download = '1';
							if($opt['opt_disable_download'] > 0) { 
								updateSQL("ms_cart", "cart_disable_download='1' WHERE cart_id='".$cart_id."' ");
							}
						}

						insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$cart_id."' ");

						if($pack['package_buy_all'] == "1") { 
							$bcarts = whileSQL("ms_cart","*", "WHERE cart_package_photo='".$cart_id."' ");
							while($bcart = mysqli_fetch_array($bcarts)) { 
								insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_cart_id='".$bcart['cart_id']."', co_download='".$co_download."', co_download_size='".$opt['opt_download_size']."', co_disable_download='".$opt['opt_disable_download']."'  ");

							}
							$co_download = "";
							$opt_price = "";

						}
					}

				}
				## End get print credit options 

				print "good";
			}
		}
	}
	exit();
}


?>
<script>
function checkprintcredit() { 
	var fields = {};
	var rf = false;
	var stop;
	var mes;
	 $("#printcreditresponse").removeClass("error").html("").slideUp(100);

	$(".pcrequired").each(function(i){
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
		$.post(tempfolder+'/sy-inc/store/store_redeem_print_credit.php', fields,	function (data) { 

			data = $.trim(data);
			response = data.split('|');

			// alert(data);
			if(response[0] == "options") { 
				redeemprintcredit(response[1]);
			} else if(response[0] == "good") { 
				updateCartMenu();
				$("#redeemform").slideUp(200, function() { 
					$("#addprintcreditphotos").slideDown(200);
				});
				 $("#printcreditresponse").removeClass("error").addClass("success").html($("#printcreditresponse").attr("success")).slideDown(100);

				totalpackage = Math.abs($("#vinfo").attr("has_package"));

				$("#vinfo").attr("has_package",totalpackage + 1);
				
				if($("#slideshow").attr("fullscreen") == "1") { 
					packagenexttophoto($("#slideshow").attr("curphoto"));
				}
				if(istablet == "1") { 
					$("#vinfo").attr("view_package_only","1");
					if($("body").width() <= 800) { 
					$("#photopackagetab").html("Please rotate your device to add photos to your package");
						$("#photopackagetab").fadeIn(100);

					}
				}
				$("#vinfo").attr("view_package","1")




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
	<div class="pc center"><h3><?php print _redeem_print_credit_;?></h3><div>
	<div id="redeemform">


		<?php
		if(!empty($_REQUEST['pc_code'])) {
			$pack = doSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_code='".$_REQUEST['pc_code']."' AND pc_order<='0' AND (pc_expire>=NOW() OR pc_expire='0000-00-00') AND pc_order<='0' AND pc_coupon<='0' AND pc_code!='' ");
			if($pack['pc_id'] > 0) { 
				$pc_code = $pack['pc_code'];

			?>

				<?php 	$opts = whileSQL("ms_product_options LEFT JOIN ms_packages ON ms_product_options.opt_package=ms_packages.package_id", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC "); ?>
				<?php if(mysqli_num_rows($opts)> 0) {
				
				$pic_options = true;
				?>
				<div class="pc center"><?php print _print_credit_select_from_options_below_;?></div>
				<div>&nbsp;</div>
				<input type="hidden" id="optionsselect" value="yes" class="printcredit">

				<div style="text-align: left;">
				<?php 
				while($opt = mysqli_fetch_array($opts))  { 
					$opt['total_images'] = $total_images;
					$prod['pp_taxable'] = $pack['package_taxable'];
					?>
					<div  style="margin-bottom: 16px;"><?php printCreditOptions($opt,'printcredit',$pack); ?></div>
				<?php } 
				?>
				<div class="clear"></div>
				</div>
				<?php } 
				}	
				
			}
			?>

		<?php if($pic_options == true) { ?>
		<?php } else { ?>
		<div class="pc center"><?php print _redeem_print_credit_instructions_;?></div>
		<?php } ?>

		<form method="post" name="redeempc" id="redeempc" action="" onsubmit="checkprintcredit(); return false;">
		<?php if($pic_options == true) { ?>
		<div class="pc center"><input type="hidden" id="print_credit_code" size="20" class="pcrequired printcredit" value="<?php print $pc_code;?>"></div>
		<?php } else { ?>
		<div class="pc center"><input type="text" id="print_credit_code" size="20" class="pcrequired printcredit" value="<?php print $pc_code;?>"></div>
		<?php } ?>
		<input type="hidden" id="action" value="checkprintcredit" class="printcredit">
		<div class="pc center"><input type="submit" id="submit" value="<?php print _redeem_print_credit_button_;?>" class="submit"></div>
		<div>&nbsp;</div>
		<div class="pc center"><a href="" onclick="closewindowpopup(); return false;"><?php print _cancel_;?></a></div>
		</form>
	</div>


	<div id="printcreditresponse" class="hide pc center" success="<?php print htmlspecialchars(_redeem_print_credit_good_);?>"></div>
	<div>&nbsp;</div>
	<div id="addprintcreditphotos" class="hide pc center"><b><?php print _redemm_print_credit_add_photos_;?></b>
	<br><br>
	<a href="" onclick="closewindowpopup(); return false;"><?php print _continue_;?></a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
</div>

<?php
function printCreditOptions($opt,$class,$prod) { 
	global $site_setup,$total_images;
		if($opt['opt_required'] == "1") { 
			$isrequired = "pcrequired";
		}
		// print "<h2>".$opt['package_buy_all']." - ".$opt['total_images']."</h2>";
	if($opt['opt_type'] == "text") { 
		$price = $opt['opt_price'];
		if($opt['package_buy_all'] == "1") { 
			if($opt['package_buy_all_price_type'] == "3") { 
				$price = $sel['sel_price'];
			} else  { 
				$price = $sel['sel_price'] * $opt['total_images'];
			}
		}

		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}
		print "<div>"; 
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		
		print "".$opt['opt_name']."</div>";
		print "<div><input type=\"text\"  id=\"opt-".$opt['opt_id']."\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\" class=\"$class prodoption inputtext $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" price=\"".$opt['opt_price']."\" ></div>";
	}
	if($opt['opt_type'] == "checkbox") { 
		$price = $opt['opt_price_checked'];
		if($opt['package_buy_all'] == "1") { 
			if($opt['package_buy_all_price_type'] == "3") { 
				$price = $opt['opt_price_checked'];
			} else  { 
				$price = $opt['opt_price_checked'] * $opt['total_images'];
			}
		}

		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

		print "<div><input type=\"checkbox\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"1\" class=\"$class prodoption inputcheckbox $isrequired\" prodid=\"".$opt['opt_photo_prod']."\"  price=\"".$opt['opt_price_checked']."\" > <label for=\"opt-".$opt['opt_id']."\">"; 		
		print " ".$opt['opt_name']." ";
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
	
		print "</label></div>";
	}

	if($opt['opt_type'] == "download") { 
		$price = $opt['opt_price_download'];
		if($opt['package_buy_all'] == "1") { 
			if($opt['package_buy_all_price_type'] == "3") { 
				$price = $sel['sel_price'];
			} else  { 
				$price = $sel['sel_price'] * $opt['total_images'];
			}
		}

		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

		print "<div><input type=\"checkbox\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"1\" class=\"$class prodoption inputcheckbox $isrequired\" prodid=\"".$opt['opt_photo_prod']."\"  price=\"".$opt['opt_price_checked']."\" > <label for=\"opt-".$opt['opt_id']."\">"; 
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		
		print " ".$opt['opt_name']."</label></div>";
	}

	if($opt['opt_type'] == "dropdown") { 
		print "<div><h4>".$opt['opt_name']."</h4></div>";
		print "<select name=\"opt-".$opt['opt_id']."\"  id=\"opt-".$opt['opt_id']."\" fieldname=\"".htmlspecialchars($opt['opt_name'])."\"  class=\"$class prodoption inputdropdown $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" style=\"width: 100%; padding: 4px;\">\r\n";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_id ASC ");
//		if($opt['opt_required'] =="1") { 
//			print "<option value=\"\" disabled>".$opt['opt_name']."</option>";
//		} else { 
			print "<option value=\"\" price=\"0\">"._select_option_."</option>\r\n";
//		}
		while($sel = mysqli_fetch_array($sels)) { 
		$price = $sel['sel_price'];
		if($opt['package_buy_all'] == "1") { 
			if($opt['package_buy_all_price_type'] == "3") { 
				$price = $sel['sel_price'];
			} else  { 
				$price = $sel['sel_price'] * $opt['total_images'];
			}
		}
		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

			print "<option value=\"".$sel['sel_id']."\" price=\"".$sel['sel_price']."\" "; if($sel['sel_default'] == "1") { print "selected"; } print ">".$sel['sel_name'].""; 
			
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					

 
			print "</option>";
		}
		print "</select>";
	}

	if($opt['opt_type'] == "radio") { 
		print "<div>".$opt['opt_name']."</div>";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
			print "<nobr><input type=\"radio\"  price=\"".$sel['sel_price']."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"".$sel['sel_id']."\" ";  if($sel['sel_default'] == "1") { print "checked"; } print " class=\"$class prodoption inputradio\" prodid=\"".$opt['opt_photo_prod']."\"> ".$sel['sel_name'].""; if($sel['sel_price'] > 0) { print " + ".showPrice($sel['sel_price']); } print "</nobr> ";
		}
	}
	if(!empty($opt['opt_descr'])) { 
		print "<div style=\"padding: 4px 0;\">".nl2br($opt['opt_descr'])."</div>";
	}
}
?>
<?php  mysqli_close($dbcon); ?>
