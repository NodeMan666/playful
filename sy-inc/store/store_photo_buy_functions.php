<?php function showProductv2($prod,$list,$con) { 
	global $pic,$date,$setup,$site_setup,$group;
	// If coming from extra photos in package, $con = $cart 
	$ppics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$prod['pp_id']."' ORDER BY bp_order ASC");
	$prod['pp_collapse_options'] = 0;
	if($pic['pic_width'] > 0) { 
		if($pic['pic_width'] < $pic['pic_height']) { 
			$percent = $pic['pic_width'] / $pic['pic_height'];
			$largest = $pic['pic_height'];
		} else { 
			$percent = $pic['pic_height'] / $pic['pic_width'];
			$largest = $pic['pic_width'];
		}
	}
	if($prod['con_id'] > 0) { 
		$con_id = $prod['con_id'];
	} else { 
		$con_id = $con['pc_id'];
	}
	if((($prod['pp_type'] == "download") && (($largest >= $prod['pp_download_dem']) || $prod['pp_disable_download'] == "1")) || ($prod['pp_type']!=="download")==true) {
		?>
		<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC "); ?>
		<?php 
		if($prod['pp_type'] == "download") { $and_opt = " AND opt_downloads='1' "; } 	
		$iopts = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' $and_opt ORDER BY opt_id ASC "); ?>

		<?php
			if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
				$need_login = $list['list_require_login'];
			}

		if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$con['pc_id']."' ") > 0) { 
			$qtydiscounts = 1;
			if($con['pc_qty_on'] == "1") { 
				$qcart =doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_photo_prod_connect ");
				$total_in_cart =$qcart['qty'];
			} else { 
				$qcart = doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' GROUP BY cart_photo_prod_connect  ");
				$total_in_cart = $qcart['qty'];
			}
			$total_in_cart = $total_in_cart + 1;
			$qprice = doSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$con['pc_id']."' AND dis_qty_from<='".$total_in_cart."' AND (dis_qty_to>='".$total_in_cart."' OR dis_qty_to='0') ");
			$dprice = $qprice['dis_price'];
		}
		if($dprice <= 0) { 
			if($con['pc_price'] > 0) { 
				$price = $con['pc_price'];
			} else { 
				$price = $prod['pp_price'];
			}
		} else { 
			$qd = "1";
			$price = $dprice;
		}
		if($prod['con_extra_price'] > 0) { 

			$photo_in_package = countIt("ms_cart", "WHERE cart_package_photo='".$con['cart_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
			$photo_in_package = $photo_in_package + countIt("ms_cart", "WHERE cart_package_photo_extra='".$con['cart_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
			if($photo_in_package > 0) { 
				$price = $prod['con_extra_price'];
			} else { 
				$price = $prod['con_extra_price_new_photo'];
			}
//			print "<li>".$photo_in_package;
		}

		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}
		?>
			<div >



			<div class="center pc"><h2><?php print $prod['pp_name'];?></h2></div>
			
			<?php if(($prod['pp_type'] == "download")&&($prod['pp_no_display_dems'] <= 0)==true) { ?>
			<div class="downloadsize pc center"><?php if($prod['pp_download_dem'] <=0) { print $pic['pic_width']."px X ".$pic['pic_height']."px"; } else { print $prod['pp_download_dem']."px X ".round($percent * $prod['pp_download_dem'])."px"; } 
			$path = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']."";
			$dpi = imageGetDPI($path);
			if(($dpi > 0)&&($dpi !== "Array") && ($prod['pp_download_dem'] <= 0) ==true) { print " &nbsp; <span class=\"dpi\">$dpi DPI</span>"; } 
			?> </div>
			<?php } ?>


		<div class="pc center">
		<h2 id="price-<?php print $con_id;?>" orgprice="<?php print $price;?>"><?php if($qd=="1") { ?>* <?php } ?><?php if($price > 0) { print showPrice($price); } else { print "&nbsp;"; } ?></h2>
		</div>

			<?php if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$con['pc_id']."' ") > 0) { ?>
			
			<div class="pc center" id="qty-message-<?php print $con_id;?>" <?php if($qd!=="1") { ?>style="display: none;"<?php } ?>><?php print _price_based_on_quantity_discount_;?> (<?php print $total_in_cart - 1;?>)</div>


			<div class="pc center" style=" z-index: 4;">
				<div><a href="" onclick="showqtydiscount(); return false;"><span class="the-icons icon-down-open"></span><?php print _quantity_discounts_;?></a></div>
				<div id="qtydiscountprices" class="hide">
					<div class="">
					<?php if(!empty($con['pc_qty_descr'])) { ?>
						<div class="pc"><?php print nl2br($con['pc_qty_descr']);?></div>
						<?php } ?>
						<?php 
						$diss = whileSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$con['pc_id']."' ORDER BY dis_price DESC");
						while($dis = mysqli_fetch_array($diss)) { ?>
						<div class="pc">
							<div style="float: left; width: 50%; " class="textright">
								<div style="padding-right:16px;">
							<?php if($dis['dis_qty_to'] <=0) { ?>
								<?php print $dis['dis_qty_from']." +"; ?>
							<?php } else { ?>
								<?php print $dis['dis_qty_from']." - ".$dis['dis_qty_to']; ?>
							<?php } ?>
							</div>
							</div>
							<div style="float: right; width: 50%;" class="textleft">
								<div style="padding-left:16px;">
							<?php
								if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
									$dis['dis_price'] = $dis['dis_price']+ (($dis['dis_price'] * $site_setup['include_vat_rate']) / 100);
								}
								print showPrice($dis['dis_price']); ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php } ?>



		<?php
		if($need_login !== "2") { ?>


		<input type="hidden" name="qty_discounts" id="qty_discounts"  class="prod-<?php print $con_id;?>" value="<?php print $qtydiscounts;?>">
		<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $con_id;?>" value="<?php print $_REQUEST['color_id'];?>">
		<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $con_id;?>" value="<?php print $_REQUEST['sub_id'];?>">
		<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $con_id;?>" value="<?php print $list['list_id'];?>">
		<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['pc_id'];?>">
		<input type="hidden" name="prod_package_id" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $prod['con_id'];?>">
		<input type="hidden" name="prod_package" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['cart_id'];?>">
		<input type="hidden" name="pid" id="pid"  class="prod-<?php print $con_id;?>" value="<?php print $pic['pic_key'];?>">
		<input type="hidden" name="did" id="did"  class="prod-<?php print $con_id;?>" value="<?php print $date['date_id'];?>">
		<input type="hidden" name="group_require_purchase" id="group_require_purchase"  class="prod-<?php print $con_id;?>" value="<?php print $con['group_require_purchase'];?>">
		<input type="hidden" name="pp_type" id="pp_type"  class="prod-<?php print $con_id;?>" value="<?php print $prod['pp_type'];?>">
		<input type="hidden" name="has_image_options" id="has_image_options"  class="prod-<?php print $con_id;?>" value="<?php if(mysqli_num_rows($iopts) > 0) { print "1"; } else { print "0"; } ?>">

		<input type="hidden" name="group_id" id="group_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['pc_group'];?>">
		<input type="hidden" name="action" id="action"  class="prod-<?php print $con_id;?>" value="addphotoprodtocart">

		<?php } ?>
			<div class="clear"></div>
			
		<?php 
			if(countIt("ms_cart",  "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND cart_pic_id='".$pic['pic_id']."' AND cart_photo_prod='".$prod['pp_id']."' ") > 0) { ?>
			<!-- <div class="pc center"><b><a href="" onclick="viewcart(); return false;"><?php print _in_my_cart_;?></a></b></div> -->
			<?php } 

		?>
			<?php if(!empty($prod['pp_descr'])) { ?>
			<div class="sub pc"><?php print nl2br($prod['pp_descr']);?></div>
			<div>&nbsp;</div>
			<?php } ?>



<?php ########## Good to here so far ###################?>





		<?php 
		$incart = countIt("ms_cart",  "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND cart_pic_id='".$pic['pic_id']."' AND cart_photo_prod='".$prod['pp_id']."' ");
		
		if(($incart > 0) && ($prod['pp_type'] == "download") == true) { 
		/* Download product already in cart */

		 }  else { ?>


		<?php if(mysqli_num_rows($iopts) > 0) { ?>
		<div class="options options-<?php print $con_id;?>" >
		<?php 
		/* IMAGE OPTIONS */
		while($iopt = mysqli_fetch_array($iopts))  { ?>
			<div>
			<?php 
			$ckopt = doSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*", "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND co_pic_id='".$pic['pic_id']."' AND co_opt_id='".$iopt['opt_id']."' ");

			$iopt_price = $iopt['opt_price'] + (($iopt['opt_price'] * $site_setup['include_vat_rate']) / 100);

		?>
		
			<div class="photoproductoption ioptselected<?php print $iopt['opt_id'];?> <?php if(empty($ckopt['co_id'])) { ?>hide<?php } ?>"><?php print $iopt['opt_name'];?> - <?php print _image_option_selected_;?></div>
			<div class="photoproductoption ioptselect<?php print $iopt['opt_id'];?> <?php if(!empty($ckopt['co_id'])) { ?>hide<?php } ?>">

				<div><input type="checkbox" name="iopt-<?php print $iopt['opt_id'];?>" class="prod-<?php print $con_id;?>" id="iopt-<?php print $iopt['opt_id'];?><?php print $con_id;?>" value="1"> <label for="iopt-<?php print $iopt['opt_id'];?><?php print $con_id;?>"><?php print $iopt['opt_name'];?><?php if($iopt['opt_price'] > 0) { print " +".showPrice($iopt_price); } ?></label></div>

				<div><?php print nl2br($iopt['opt_descr']);?></div>

				</div>
			</div>
		<?php } 
		?>
		<div class="clear"></div>
		</div>
		<?php } ?>


		<?php 
		while($opt = mysqli_fetch_array($opts))  { ?>
			<div class="photoproductoption"><?php productOptions($opt,'prod-'.$con_id.'',$prod); ?></div>
		<?php } 
		?>

		
		
		<?php 
			if($need_login == "2") { 
				$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
				$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
				print "<div class=\"pc center logintopurchasemessage\" style=\"margin-bottom: 12px; font-weight: bold;\">".$message."</div>";				
		} else { 
				 ?>
		<?php if($prod['pp_type'] == "download") { ?><input type="hidden" name="qty" id="qty" value="1" class="prod-<?php print $con_id;?>"><?php } else { ?>
		<input type="hidden" name="qty" class="prod-<?php print $con_id;?> center addtocartqty" id="qty" value="1" size="2" >
		<?php } ?>


				<div class="loadingspinnersmall hide" id="addcartloading-<?php print $con['pc_id'];?>"></div>
				<div class="pc center"><span onclick="addphotoprodtocart('prod-<?php print $con_id;?>','<?php print $con_id;?>','<?php print $qtydiscounts;?>','<?php print $pic['pic_key'];?>'); return false;" id="addcart-<?php print $con_id;?>" class="checkout addproducttocart" title="<?php print _add_to_cart_;?>"><span class="the-icons icon-basket"></span><?php print _add_to_cart_;?></span></div>
				<div class="pc center backtoproductlist backtoproductlistbottom"><a href="" class="" onclick="closephotoproduct(); return false;"><span class="the-icons icon-left-open"></span><?php print _back_to_products_; ?></a></div>

			<?php 
			} ?>
		</div>
		<?php } ?>

			<?php } // End download product already in cart ?>
				<?php 
			if(mysqli_num_rows($ppics) > 0) { ?>
			<div>&nbsp;</div>
			<div class="center photoproductphotos">
			<?php 
				while($ppic = mysqli_fetch_array($ppics)) { 
				$size = getimagefiledems($ppic,'pic_pic');
				?>
				<img src="<?php print getimagefile($ppic,'pic_pic');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail" style="margin: 0 12px 12px 0; width: 100%; height: auto; max-width: <?php print $size[0];?>px; ">
				<?php } ?>
				</div>
				<?php } ?>

		</div>
		<div class="clear"></div>
		</div>
<?php }




function showProduct($prod,$list,$con) { 
	global $pic,$date,$setup,$site_setup,$group;
	// If coming from extra photos in package, $con = $cart 
	$ppics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$prod['pp_id']."' ORDER BY bp_order ASC");

	if($pic['pic_width'] > 0) { 
		if($pic['pic_width'] < $pic['pic_height']) { 
			$percent = $pic['pic_width'] / $pic['pic_height'];
			$largest = $pic['pic_height'];
		} else { 
			$percent = $pic['pic_height'] / $pic['pic_width'];
			$largest = $pic['pic_width'];
		}
	}
	if($prod['con_id'] > 0) { 
		$con_id = $prod['con_id'];
	} else { 
		$con_id = $con['pc_id'];
	}
	if((($prod['pp_type'] == "download") && (($largest >= $prod['pp_download_dem']) || $prod['pp_disable_download'] == "1")) || ($prod['pp_type']!=="download")==true) {
		?>
		<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC "); ?>
		<?php 
		if($prod['pp_type'] == "download") { $and_opt = " AND opt_downloads='1' "; } 	
		$iopts = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' $and_opt ORDER BY opt_id ASC "); ?>

		<div class="photoprod">
		<div class="underline" cw="<?php print $prod['pp_width'];?>" ch="<?php print $prod['pp_height'];?>">
		<div class="<?php if(mysqli_num_rows($opts)<=0) { print "namenooptions"; } else { print "name"; } ?>">
			<div style="padding-right: 16px;">
			<?php if(((mysqli_num_rows($opts)> 0)||(mysqli_num_rows($iopts) > 0)||(mysqli_num_rows($ppics) > 0))&&($prod['pp_collapse_options'] == "1")==true) { ?>

		<?php $ppic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$prod['pp_id']."' ORDER BY bp_order ASC ");
		if(!empty($ppic['pic_id'])) { 
			// $size = getimagefiledems($ppic,'pic_mini');
			?>
			<img src="<?php print getimagefile($ppic,'pic_mini');?>"  class="thumbnail left" style="margin: 0 12px 12px 0;">
			<?php } ?>
			<div><h4  onclick="openoptions('<?php print $con_id;?>'); return false;" style="cursor: pointer;"><?php print $prod['pp_name'];?></h4></div>
			<?php } else { ?>
			<div><?php print $prod['pp_name'];?></div>
			<?php } ?>
			<?php if(($prod['pp_type'] == "download")&&($prod['pp_no_display_dems'] <= 0)==true) { ?>
			<div class="downloadsize"><?php if($prod['pp_download_dem'] <=0) { print $pic['pic_width']."px X ".$pic['pic_height']."px"; } else { print $prod['pp_download_dem']."px X ".round($percent * $prod['pp_download_dem'])."px"; } 
			$path = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']."";
			$dpi = imageGetDPI($path);
			if(($dpi > 0)&&($dpi !== "Array")==true) { print "<span class=\"dpi\"><br>$dpi DPI</span>"; } 
			?> </div>
			<?php } ?>

			</div>
		</div>
		<?php
			if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
				$need_login = $list['list_require_login'];
			}
			?>

		<?php
		if($need_login !== "2") { ?>
		<div class="qty">
		<?php if($prod['pp_type'] == "download") { ?><input type="hidden" name="qty" id="qty" value="1" class="prod-<?php print $con_id;?>">&nbsp;<?php } else { ?>

		<input type="text" name="qty" class="prod-<?php print $con_id;?> center addtocartqty" id="qty" value="1" size="2">
		<?php } ?>
		</div>

		<div class="cartbutton">
		<?php 
		$incart = countIt("ms_cart",  "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND cart_pic_id='".$pic['pic_id']."' AND cart_photo_prod='".$prod['pp_id']."' ");
		
		if(($incart > 0) && ($prod['pp_type'] == "download") == true) { ?>
			<span class="the-icons icon-check" style="opacity: 0; cursor: default;"></span>
		<?php }  else { ?>
		<input type="hidden" name="qty_discounts" id="qty_discounts"  class="prod-<?php print $con_id;?>" value="<?php print $qtydiscounts;?>">
		<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $con_id;?>" value="<?php print $_REQUEST['color_id'];?>">
		<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $con_id;?>" value="<?php print $_REQUEST['sub_id'];?>">
		<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $con_id;?>" value="<?php print $list['list_id'];?>">
		<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['pc_id'];?>">
		<input type="hidden" name="prod_package_id" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $prod['con_id'];?>">
		<input type="hidden" name="prod_package" id="prod_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['cart_id'];?>">
		<input type="hidden" name="pid" id="pid"  class="prod-<?php print $con_id;?>" value="<?php print $pic['pic_key'];?>">
		<input type="hidden" name="did" id="did"  class="prod-<?php print $con_id;?>" value="<?php print $date['date_id'];?>">
		<input type="hidden" name="group_require_purchase" id="group_require_purchase"  class="prod-<?php print $con_id;?>" value="<?php print $con['group_require_purchase'];?>">
		<input type="hidden" name="pp_type" id="pp_type"  class="prod-<?php print $con_id;?>" value="<?php print $prod['pp_type'];?>">
		<input type="hidden" name="has_image_options" id="has_image_options"  class="prod-<?php print $con_id;?>" value="<?php if(mysqli_num_rows($iopts) > 0) { print "1"; } else { print "0"; } ?>">
		<input type="hidden" name="group_id" id="group_id"  class="prod-<?php print $con_id;?>" value="<?php print $con['pc_group'];?>">
		<input type="hidden" name="action" id="action"  class="prod-<?php print $con_id;?>" value="addphotoprodtocart">
		<span class="the-icons icon-spin5" id="addcartloading-<?php print $con_id;?>" style="display: none;"></span>
		<a href="" onclick="addphotoprodtocart('prod-<?php print $con_id;?>','<?php print $con_id;?>','<?php print $qtydiscounts;?>','<?php print $pic['pic_key'];?>'); return false;" id="addcart-<?php print $con_id;?>" class="icon-basket the-icons" title="<?php print _add_to_cart_;?>"></a>
		<?php } ?>


		</div>

		<?php } ?>

		<!-- new location for price -->
				<div class="price" <?php if($need_login > 0) { ?>style="float: right;"<?php } ?>>
		<?php 

		if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$con['pc_id']."' ") > 0) { 
			$qtydiscounts = 1;
			if($con['pc_qty_on'] == "1") { 
				$qcart =doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_photo_prod_connect ");
				$total_in_cart =$qcart['qty'];
			} else { 
				$qcart = doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' GROUP BY cart_photo_prod_connect  ");
				$total_in_cart = $qcart['qty'];
			}
			$total_in_cart = $total_in_cart + 1;
			$qprice = doSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$con['pc_id']."' AND dis_qty_from<='".$total_in_cart."' AND (dis_qty_to>='".$total_in_cart."' OR dis_qty_to='0') ");
			$dprice = $qprice['dis_price'];
		}
		if($dprice <= 0) { 
			if($con['pc_price'] > 0) { 
				$price = $con['pc_price'];
			} else { 
				$price = $prod['pp_price'];
			}
		} else { 
			$qd = "1";
			$price = $dprice;
		}
		if($prod['con_extra_price'] > 0) { 

			$photo_in_package = countIt("ms_cart", "WHERE cart_package_photo='".$con['cart_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
			$photo_in_package = $photo_in_package + countIt("ms_cart", "WHERE cart_package_photo_extra='".$con['cart_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
			if($photo_in_package > 0) { 
				$price = $prod['con_extra_price'];
			} else { 
				$price = $prod['con_extra_price_new_photo'];
			}
//			print "<li>".$photo_in_package;
		}

		if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}
		?>
		<span id="price-<?php print $con_id;?>" orgprice="<?php print $price;?>"><?php if($qd=="1") { ?>* <?php } ?><?php if($price > 0) { print showPrice($price); } else { print "&nbsp;"; } ?></span>
		</div>


			<div class="clear"></div>
		<?php 
			if(countIt("ms_cart",  "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND cart_pic_id='".$pic['pic_id']."' AND cart_photo_prod='".$prod['pp_id']."' ") > 0) { ?>
			<div class="pc center"><b><a href="" onclick="viewcart(); return false;"><?php print _in_my_cart_;?></a></b></div>
			<?php } 

		?>
			<?php if(!empty($prod['pp_descr'])) { ?>
			<div class="sub pc"><?php print nl2br($prod['pp_descr']);?></div>
			<?php } ?>
		<?php if((mysqli_num_rows($opts)> 0)||(mysqli_num_rows($iopts)> 0)||(mysqli_num_rows($ppics) > 0)==true) { ?>
		<div class="options <?php if(((mysqli_num_rows($opts)> 0)||(mysqli_num_rows($iopts) > 0)||(mysqli_num_rows($ppics) > 0))&&($prod['pp_collapse_options'] == "1")==true) { ?>hide optionsopen<?php } ?> options-<?php print $con_id;?>" >
		<div>
		<?php 
		/* IMAGE OPTIONS */

		while($iopt = mysqli_fetch_array($iopts))  { ?>
			<div  style="margin-bottom: 16px;">
			<?php $ckopt = doSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*", "WHERE  ".checkCartSession()." AND  cart_order<='0'  AND co_pic_id='".$pic['pic_id']."' AND co_opt_id='".$iopt['opt_id']."' ");

						$iopt_price = $iopt['opt_price'] + (($iopt['opt_price'] * $site_setup['include_vat_rate']) / 100);

		?>
		
				<div class="ioptselected<?php print $iopt['opt_id'];?> <?php if(empty($ckopt['co_id'])) { ?>hide<?php } ?>"><?php print $iopt['opt_name'];?> - <?php print _image_option_selected_;?></div>
				<div class="ioptselect<?php print $iopt['opt_id'];?> <?php if(!empty($ckopt['co_id'])) { ?>hide<?php } ?>">
					<div><input type="checkbox" name="iopt-<?php print $iopt['opt_id'];?>" class="prod-<?php print $con_id;?>" id="iopt-<?php print $iopt['opt_id'];?><?php print $con_id;?>" value="1"> <label for="iopt-<?php print $iopt['opt_id'];?><?php print $con_id;?>"><?php print $iopt['opt_name'];?><?php if($iopt['opt_price'] > 0) { print " +".showPrice($iopt_price); } ?></label></div>
					<div><?php print nl2br($iopt['opt_descr']);?></div>

				</div>
			</div>
		<?php } 
		?>
		<div class="clear"></div>
		</div>

				<?php 
			if(mysqli_num_rows($ppics) > 0) { ?>
			<div class="center">
			<?php 
				while($ppic = mysqli_fetch_array($ppics)) { 
				$size = getimagefiledems($ppic,'pic_th');
				?>
				<img src="<?php print getimagefile($ppic,'pic_th');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail" style="margin: 0 12px 12px 0;">
				<?php } ?>
				</div>
				<?php } ?>


		<div>
		<?php 
		while($opt = mysqli_fetch_array($opts))  { ?>
			<div  style="margin-bottom: 16px;"><?php productOptions($opt,'prod-'.$con_id.'',$prod); ?></div>
		<?php } 
		?>
		<div class="clear"></div>
		</div>
		<?php 
			if($need_login == "2") { 
				if($prod['pp_collapse_options'] == "1") { 
				$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
				$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
				print "<div class=\"pc center logintopurchasemessage\" style=\"margin-bottom: 12px;\">".$message."</div>";				
				}
		} else { 
				if(((mysqli_num_rows($opts)> 0)||(mysqli_num_rows($iopts) > 0)||(mysqli_num_rows($ppics) > 0))&&($prod['pp_collapse_options'] == "1")==true) { ?>
				<span class="the-icons icon-spin5" id="addcartloading-<?php print $con_id;?>" style="display: none;"></span>
				<div class="pc center"><a href="" onclick="addphotoprodtocart('prod-<?php print $con_id;?>','<?php print $con_id;?>','<?php print $qtydiscounts;?>','<?php print $pic['pic_key'];?>'); return false;" id="addcart-<?php print $con_id;?>" class="icon-basket the-icons" title="<?php print _add_to_cart_;?>"><?php print _add_to_cart_;?></a></div>
			<?php 
				}
			} ?>
		</div>
		<?php } ?>

			<?php if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$con['pc_id']."' ") > 0) { ?>
			
			<div class="left sub" id="qty-message-<?php print $con_id;?>" <?php if($qd!=="1") { ?>style="display: none;"<?php } ?>><?php print _price_based_on_quantity_discount_;?> (<?php print $total_in_cart - 1;?>)</div>

			<script>
			$(document).ready(function(){

				$(".qtydiscount").hoverIntent(
				  function () {
					$(this).find('.discount').slideDown(100);
				  },
				  function () {
					$(this).find('.discount').slideUp(100);
				  }
				);
			});
			</script>
			<div class="right qtydiscount sub" style=" z-index: 4;">
				<div><a href="" onclick="return false;"><?php print _quantity_discounts_;?></a></div>
				<div class="discount">
					<div class="inner">
					<?php if(!empty($con['pc_qty_descr'])) { ?>
						<div class="pc"><?php print nl2br($con['pc_qty_descr']);?></div>
						<?php } ?>
						<?php 
						$diss = whileSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$con['pc_id']."' ORDER BY dis_price DESC");
						while($dis = mysqli_fetch_array($diss)) { ?>
						<div class="pc">
							<div style="float: left; width: 60%;">
							<?php if($dis['dis_qty_to'] <=0) { ?>
								<?php print $dis['dis_qty_from']." +"; ?>
							<?php } else { ?>
								<?php print $dis['dis_qty_from']." - ".$dis['dis_qty_to']; ?>
							<?php } ?>
							</div>
							<div style="float: right; width: 40%;" class="textright">
							<?php
								if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
									$dis['dis_price'] = $dis['dis_price']+ (($dis['dis_price'] * $site_setup['include_vat_rate']) / 100);
								}
								print showPrice($dis['dis_price']); ?>
							</div>
							<div class="clear"></div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php } ?>
			

		</div>
		<div class="clear"></div>
		</div>
		<?php } ?>
<?php }

 
 
 
 function showPackage($pack,$list,$con) { 
	global $pic,$date,$setup,$site_setup,$photo_setup,$person;
	if($pack['package_select_only'] == "1") { 
		$has_package_one = 1;
	}
	$pack['package_collapse_options'] = 0;
	$opts = whileSQL("ms_product_options LEFT JOIN ms_packages ON ms_product_options.opt_package=ms_packages.package_id", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC "); 
	$ppics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC");
	 ?>

	<div class="photoprod packageinfo">
		<div class="underline" cw="0" ch="0" >
		<div class="name">
			<div style="padding-right: 16px;">


		<?php $ppic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC ");
		if(!empty($ppic['pic_id'])) { 
			// $size = getimagefiledems($ppic,'pic_mini');
			?>
			<img src="<?php print getimagefile($ppic,'pic_mini');?>"  class="thumbnail left 	<?php if($pack['package_collapse_options'] == "1") { ?> packagemore<?php } ?>" style="margin: 0 12px 12px 0;" 	<?php if($pack['package_collapse_options'] == "1") { ?> title="<?php print _view_package_details_?>"<?php } ?>>
			<?php } ?>
			

			<div class="<?php if($pack['package_collapse_options'] == "1") { ?> packagemore<?php } ?>" <?php if($pack['package_collapse_options'] == "1") { ?> title="<?php print _view_package_details_?>"<?php } ?>><?php print $pack['package_name'];?></div>

			<?php 
				if($pack['package_buy_all'] == "1") { 
					if(!empty($_REQUEST['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
						$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}

					if($_REQUEST['view'] == "favorites") { 
						$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
						$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
						$pics_orderby = "pic_org";
					} else { 

						if(!empty($date['date_photo_keywords'])) { 
							$and_date_tag = "( ";
							$date_tags = explode(",",$date['date_photo_keywords']);
							foreach($date_tags AS $tag) { 
								$cx++;
								if($cx > 1) { 
									$and_date_tag .= " OR ";
								}
								$and_date_tag .=" key_key_id='$tag' ";
							}
							$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
							$and_date_tag .= " ) ";
							$and_where = getSearchString();
							$pics_where = "WHERE $and_date_tag $and_where ";
							$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
						} else { 

							$and_where = getSearchString();
							$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
							$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
						}
					}

					if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
						// Is gallery owner
					} else { 
						$and_where .= " AND pic_hide!='1' ";
					}

					$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  ");
					$total_images = mysqli_num_rows($pics);



					if($pack['package_buy_all_price_type'] == "1") { 
						$price = $total_images * $pack['package_buy_all_each'];
					} elseif($pack['package_buy_all_price_type'] == "2") { 
						$getprice = doSQL("ms_packages_buy_all", "*", "WHERE ba_package='".$pack['package_id']."' AND ((ba_from<='$total_images' AND ba_to>='$total_images' ) OR (ba_from<='$total_images' AND ba_to='0'))  ");
						$price = $getprice['ba_price'] * $total_images;
					} elseif($pack['package_buy_all_price_type'] == "3") { 
						$price = $pack['package_buy_all_set_price'];
					}

					// print "<div>";
					// print $date['date_title'];
					if(!empty($sub['sub_id'])) { 
						$ids = explode(",",$sub['sub_under_ids']);
						foreach($ids AS $val) { 
							if($val > 0) { 
								$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
								// print " > ".$upsub['sub_name']."  ";
							}
						}
						// print " > ".$sub['sub_name'];
					}
					if(!empty($_REQUEST['kid'])) { 
						if(!is_numeric($_REQUEST['kid'])) { die(); } 
						$keyword = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
					}
					if(!empty($keyword['key_word'])) { 
						// print " with key word: '".$keyword['key_word']."' ";
					}
					if(!empty($_REQUEST['keyWord'])) { 
						// print " with key word: '".$_REQUEST['keyWord']."' ";
					}

					// print "<br><b> ".$total_images." "._photos_word_photos_."</b></div>";
			
				} else { 
					if($con['pc_price'] > 0) { 
						$price = $con['pc_price'];
					} else { 
						$price = $pack['package_price'];
					}
				}


			?>


			</div>
		</div>
		<?php 
		if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
			$need_login = $list['list_require_login'];
		}
		?>

		<?php
		if($need_login !== "2") { ?>
			<div  class="qty">
			<input type="text" name="qty" id="qty" value="1" size="1" class="prod-<?php print $con['pc_id'];?> center addtocartqty">&nbsp;
			</div>
			<div  class="cartbutton">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $con['pc_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="kid" id="kid"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['kid'];?>">
			<input type="hidden" name="keyWord" id="keyWord"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['keyWord'];?>">

			<input type="hidden" name="from_time" id="from_time"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['from_time'];?>">
			<input type="hidden" name="search_date" id="search_date"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['search_date'];?>">
			<input type="hidden" name="search_length" id="search_length"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['search_length'];?>">
			<input type="hidden" name="passcode" id="passcode"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['passcode'];?>">

			
			<input type="hidden" name="buyall" id="buyall"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $pack['package_buy_all'];?>">
			<input type="hidden" name="view" id="view"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['view'];?>">
			<input type="hidden" name="package_select_only" id="package_select_only-<?php print $con['pc_id'];?>"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $has_package_one;?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="group_id" id="group_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $con['pc_group'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $con['pc_id'];?>" value="addpackagetocart">


			<span class="the-icons icon-spin5" id="addcartloading-<?php print $con['pc_id'];?>" style="display: none;"></span>
			<?php if($pack['package_buy_all'] == "1") { ?>
			<a href="" onclick="addbuyalltocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="icon-basket the-icons" title="<?php print _add_to_cart_;?>"></a>

			<?php } else { ?>
			<a href="" onclick="addpackagetocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="icon-basket the-icons" title="<?php print _add_to_cart_;?>"></a>
			<?php } ?>
			</div>

		<div class="price">
		<?php 
		if(($pack['package_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price + (($price * $site_setup['include_vat_rate']) / 100);
		}
		?>
		<span id="price-<?php print $con['pc_id'];?>" orgprice="<?php print $price;?>"><?php print showPrice($price);?></span>
		</div>


			<?php } ?>
			<div class="clear"></div>
			<div class="pc packageincludes">
			<?php 
				$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' AND con_product>'0'  ORDER BY con_order ASC ");
				if(mysqli_num_rows($prods) > 0) { print _package_includes_." ";
					while($prod = mysqli_fetch_array($prods)) { 
						if($prod['con_qty'] > 0) { 
							if($x > 0) { print ", "; } 
							print "<nobr>".$prod['con_qty'].": ".$prod['pp_name']."</nobr>";
							$x++;
						}
					}

					$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' GROUP BY con_product  ORDER BY con_order ASC ");
					if(mysqli_num_rows($prods)==1) { 
						while($prod = mysqli_fetch_array($prods)) { 
							if(countIt("ms_product_options",  "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ")<=0) { 
								$has_package_one = 1;
							}
						}
					}
				}

			?> 
		<?php if($pack['package_limit'] > 0) { if($x > 0) { print ", "; } $x++; print " ".$pack['package_limit']." "._poses_;?><?php } ?>
		<?php if($pack['package_credit'] > 0) { if($x > 0) { print ", "; } print " ".showPrice($pack['package_credit'])." "._credit_;?><?php } ?>

			<div class="clear"></div>
		

			</div>


		<?php // if(($_REQUEST['mobile'] !== "1")||(($pack['package_buy_all'] == "1") && ($_REQUEST['mobile']==1))==true) { ?>

		<div class="hiddeninfo" <?php if($pack['package_collapse_options'] == "1") { ?>style="display: none;"<?php } ?>>


		<?php if(mysqli_num_rows($opts)> 0) { ?>
		<div class="options <?php if($pack['package_collapse_options'] == "1") { ?> optionsopen<?php } ?> options-<?php print $con['pc_id'];?>">
		<div>
		<?php 
		while($opt = mysqli_fetch_array($opts))  { 
			$opt['total_images'] = $total_images;
			$prod['pp_taxable'] = $pack['package_taxable'];
			?>
			<div  style="margin-bottom: 16px;"><?php productOptions($opt,'prod-'.$con['pc_id'].'',$prod); ?></div>
		<?php } 
		?>
		<div class="clear"></div>
		</div>
		</div>
		<?php } ?>


			<?php if($pack['package_collapse_options'] == "1") { ?>
			<span class="the-icons icon-spin5" id="addcartloading-<?php print $con['pc_id'];?>" style="display: none;"></span>
			<?php if($pack['package_buy_all'] == "1") { ?>
			<div class="pc center"><span href="" onclick="addbuyalltocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="icon-basket the-icons addcartbutton" title="<?php print _add_to_cart_;?>"><?php print _add_to_cart_;?></span></div>
			<?php } else { ?>
			<?php if($need_login == "2") {
				$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
				$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
				print "<div class=\"pc center logintopurchasemessage\" style=\"margin-bottom: 12px;\">".$message."</div>";				
			} else { 
			?>

			<?php if($buyallprod['pp_type'] == "download") { ?>			
 			<input type="hidden" name="qty" class="prod-<?php print $con['pc_id'];?> center addtocartqty" id="qty" value="1" size="2" >
 			<?php } else { ?>
				<div class="center adjustqty"><a href="" onclick="adjustqty('qty','down'); return false;" class="the-icons icon-minus"></a> <input type="text" name="qty" class="prod-<?php print $con['pc_id'];?> center addtocartqty" id="qty" value="1" size="2" > <a href="" onclick="adjustqty('qty','up'); return false;" class=" the-icons icon-plus"></a> </div>		 
	
					<div class="pc center"><span href="" onclick="addpackagetocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="icon-basket the-icons addcartbutton" title="<?php print _add_to_cart_;?>"><?php print _add_to_cart_;?></span></div>
			<?php } ?>
			<?php } ?>
		<?php } ?>

			<?php if(!empty($pack['package_descr'])) { ?>
			<div class="pc"><?php print nl2br($pack['package_descr']);?></div>
			<?php } ?>
			<?php 
			if(mysqli_num_rows($ppics) > 0) { ?>
			<div class="center photoproductphotos">
			<?php 
				while($ppic = mysqli_fetch_array($ppics)) { 
				$size = getimagefiledems($ppic,'pic_th');
				?>
				<img src="<?php print getimagefile($ppic,'pic_th');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail" style="margin: 0 12px 12px 0;">
				<?php } ?>
				</div>
				<?php } ?>
			</div>

		<?php  } ?>
		</div>
		<div class="clear"></div>
		</div>
<?php } 

 function showPackagev2($pack,$list,$con) { 
	global $pic,$date,$setup,$site_setup,$photo_setup,$person;
	if($pack['package_select_only'] == "1") { 
		$has_package_one = 1;
	}
	$pack['package_collapse_options'] = 0;
	$opts = whileSQL("ms_product_options LEFT JOIN ms_packages ON ms_product_options.opt_package=ms_packages.package_id", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC "); 
	$ppic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC");
	if($ppic['pic_id'] > 0) { 
		$size = getimagefiledems($ppic,'pic_pic');
		?>
		<div class="collectionmainphoto center"><img src="<?php print getimagefile($ppic,'pic_pic');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px; margin: 0px auto 12px auto;"></div>
		<?php } ?>

	<div class="photoprod packageinfo">
		<div>


		<?php $ppic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC "); ?>
			
			<?php 
				if($pack['package_buy_all'] == "1") { 
				$buyallprod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$pack['package_buy_all_product']."' ");
					if(!empty($_REQUEST['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
						$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}

					if($_REQUEST['view'] == "favorites") { 
						$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
						$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
						$pics_orderby = "pic_org";
					} else { 

						if(!empty($date['date_photo_keywords'])) { 
							$and_date_tag = "( ";
							$date_tags = explode(",",$date['date_photo_keywords']);
							foreach($date_tags AS $tag) { 
								$cx++;
								if($cx > 1) { 
									$and_date_tag .= " OR ";
								}
								$and_date_tag .=" key_key_id='$tag' ";
							}
							$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
							$and_date_tag .= " ) ";
							$and_where = getSearchString();
							$pics_where = "WHERE $and_date_tag $and_where ";
							$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
						} else { 

							$and_where = getSearchString();
							$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
							$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
						}
					}

					if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
						// Is gallery owner
					} else { 
						$and_where .= " AND pic_hide!='1' ";
					}

					$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  ");
					$total_images = mysqli_num_rows($pics);



					if($pack['package_buy_all_price_type'] == "1") { 
						$price = $total_images * $pack['package_buy_all_each'];
					} elseif($pack['package_buy_all_price_type'] == "2") { 
						$getprice = doSQL("ms_packages_buy_all", "*", "WHERE ba_package='".$pack['package_id']."' AND ((ba_from<='$total_images' AND ba_to>='$total_images' ) OR (ba_from<='$total_images' AND ba_to='0'))  ");
						$price = $getprice['ba_price'] * $total_images;
					} elseif($pack['package_buy_all_price_type'] == "3") { 
						$price = $pack['package_buy_all_set_price'];
					}

					// print "<div>";
					// print $date['date_title'];
					if(!empty($sub['sub_id'])) { 
						$ids = explode(",",$sub['sub_under_ids']);
						foreach($ids AS $val) { 
							if($val > 0) { 
								$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
								// print " > ".$upsub['sub_name']."  ";
							}
						}
						// print " > ".$sub['sub_name'];
					}
					if(!empty($_REQUEST['kid'])) { 
						if(!is_numeric($_REQUEST['kid'])) { die(); } 
						$keyword = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
					}
					if(!empty($keyword['key_word'])) { 
						// print " with key word: '".$keyword['key_word']."' ";
					}
					if(!empty($_REQUEST['keyWord'])) { 
						// print " with key word: '".$_REQUEST['keyWord']."' ";
					}

					// print "<br><b> ".$total_images." "._photos_word_photos_."</b></div>";
			
				} else { 
					if($con['pc_price'] > 0) { 
						$price = $con['pc_price'];
					} else { 
						$price = $pack['package_price'];
					}
				}


			?>

		<?php 
		if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
			$need_login = $list['list_require_login'];
		}
		?>

		<?php 

		if(($pack['package_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price + (($price * $site_setup['include_vat_rate']) / 100);
		}

		?>

				<div class="center pc"><h2><?php print $pack['package_name'];?></h2></div>
			<?php if($pack['package_buy_all'] == "1") { ?><div class="pc center"><?php print $total_images." "._photos_word_photos_;?></div><?php } ?>
			<div class="pc center"><h2 id="price-<?php print $con_id;?>" orgprice="<?php print $price;?>"><?php if($qd=="1") { ?>* <?php } ?><?php if($price > 0) { print showPrice($price); } else { print "&nbsp;"; } ?></h2></div>
			<?php if(!empty($pack['package_descr'])) { ?>
			<div class="pc"><?php print nl2br($pack['package_descr']);?></div>
			<?php } ?>



			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $con['pc_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="kid" id="kid"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['kid'];?>">
			<input type="hidden" name="keyWord" id="keyWord"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['keyWord'];?>">

			<input type="hidden" name="from_time" id="from_time"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['from_time'];?>">
			<input type="hidden" name="search_date" id="search_date"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['search_date'];?>">
			<input type="hidden" name="search_length" id="search_length"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['search_length'];?>">
			<input type="hidden" name="passcode" id="passcode"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['passcode'];?>">

			
			<input type="hidden" name="buyall" id="buyall"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $pack['package_buy_all'];?>">
			<input type="hidden" name="view" id="view"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $_REQUEST['view'];?>">
			<input type="hidden" name="package_select_only" id="package_select_only-<?php print $con['pc_id'];?>"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $has_package_one;?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="group_id" id="group_id"  class="prod-<?php print $con['pc_id'];?>" value="<?php print $con['pc_group'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $con['pc_id'];?>" value="addpackagetocart">

			
		<?php if(mysqli_num_rows($opts)> 0) { ?>
		<div class="options <?php if($pack['package_collapse_options'] == "1") { ?> optionsopen<?php } ?> options-<?php print $con['pc_id'];?>">
		<div>
		<?php 
		while($opt = mysqli_fetch_array($opts))  { 
			$opt['total_images'] = $total_images;
			$prod['pp_taxable'] = $pack['package_taxable'];
			?>
			<div class="photoproductoption"><?php productOptions($opt,'prod-'.$con['pc_id'].'',$prod); ?></div>
		<?php } 
		?>
		<div class="clear"></div>
		</div>
		</div>
		<?php } ?>

		<div class="clear"></div>
			<?php 
			if($need_login == "2") { 
				$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
				$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
				print "<div class=\"pc center logintopurchasemessage\" style=\"margin-bottom: 12px; font-weight: bold;\">".$message."</div>";				
		} else { 
				 ?>
				 <?php if($buyallprod['pp_type'] == "download") { ?>
			<input type="hidden" name="qty" class="prod-<?php print $con['pc_id'];?> center addtocartqty" id="qty" value="1" size="2" >
			<?php } else { ?>
			  <input type="hidden" name="qty" class="prod-<?php print $con['pc_id'];?> center addtocartqty" id="qty" value="1" size="2" > 
				<div class="loadingspinnersmall hide" id="addcartloading-<?php print $con['pc_id'];?>"></div>

			<?php if($pack['package_buy_all'] == "1") { ?>

				<div class="pc center"><span onclick="addbuyalltocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="checkout addproducttocart" title="<?php print _add_to_cart_;?>"><span class="the-icons icon-basket"></span><?php print _add_to_cart_;?></span></div>

			<?php } else { ?>


				<div class="pc center"><span onclick="addpackagetocart('prod-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;" id="addcart-<?php print $con['pc_id'];?>" class="checkout addproducttocart" title="<?php print _add_to_cart_;?>"><span class="the-icons icon-basket"></span><?php print _add_to_cart_;?></span></div>
				<div class="pc center backtoproductlist backtoproductlistbottom"><a href="" class="" onclick="closephotoproduct(); return false;"><span class="the-icons icon-left-open"></span><?php print _back_to_products_; ?></a></div>


			<?php } ?>
		<?php } ?>


			<div>&nbsp;</div>

			<?php 
				$ppics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_package='".$pack['package_id']."' ORDER BY bp_order ASC LIMIT 1,100");
				if(mysqli_num_rows($ppics) > 0) { ?>
				<div class="center photoproductphotos">
					<?php 
					while($ppic = mysqli_fetch_array($ppics)) { 
					$size = getimagefiledems($ppic,'pic_pic');
					?>
					<img src="<?php print getimagefile($ppic,'pic_pic');?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>" class="thumbnail" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px; margin: 0px auto 12px auto;">
					<?php } ?>
					</div>
				<?php } ?>
			</div>

		<?php  } ?>
		</div>
		<div class="clear"></div>
		</div>
<?php }  


/* showing package content included */
function showpackagecontents($pack,$total_images) { 
	global $setup,$date;
	$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' AND con_product>'0'  ORDER BY con_order ASC ");


	if(($pack['package_select_only'] == "1") && ($pack['package_select_amount'] > 0) == true){ 
	?>
			<div class="left p10">
				<div style="padding-right: 16px; text-align: right;">
				<!-- <?php //print $pack['package_select_amount'];?>:  -->
				</div>
			</div>
			<div class="left">
				<div style="padding-left: 16px;">
				<!-- <?php //print _photos_word_photos_;?> -->
				</div>
			</div>
			<div class="clear"></div>
	<?php 

	}

	if($pack['package_buy_all'] == "1") { 
			if(!empty($_REQUEST['sub_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
				$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
			} else { 
				if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
					$and_sub = "AND bp_sub='0' ";
				}
			}

			if($_REQUEST['view'] == "favorites") { 
				$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
				$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
				$pics_orderby = "pic_org";
			} else { 

				if(!empty($date['date_photo_keywords'])) { 
					$and_date_tag = "( ";
					$date_tags = explode(",",$date['date_photo_keywords']);
					foreach($date_tags AS $tag) { 
						$cx++;
						if($cx > 1) { 
							$and_date_tag .= " OR ";
						}
						$and_date_tag .=" key_key_id='$tag' ";
					}
					$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
					$and_date_tag .= " ) ";
					$and_where = getSearchString();
					$pics_where = "WHERE $and_date_tag $and_where ";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
				} else { 

					$and_where = getSearchString();
					$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
					$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
				}
			}

			if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
				// Is gallery owner
			} else { 
				$and_where .= " AND pic_hide!='1' ";
			}

			$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  ");
			$total_images = mysqli_num_rows($pics);
	?>
			<div class="left p10">
				<div style="padding-right: 16px; text-align: right;">
				<!-- <?php // print $total_images;?>:  -->
				</div>
			</div>
			<div class="left">
				<div style="padding-left: 16px;">
				<!-- <?php //print _photos_word_photos_;?> -->
				</div>
			</div>
			<div class="clear"></div>
	<?php 

	}

	if(mysqli_num_rows($prods) > 0) { 
		?>
		<div class="collectionitems" >
		<?php 
			while($prod = mysqli_fetch_array($prods)) { 
				if($prod['con_qty'] > 0) { 
					?>
					<div class="left p10">
						<!-- first numbers  -->
						<!-- <div style="padding-right: 16px; text-align: right;"> -->
						<!-- <php print $prod['con_qty'];>:  -->
						<!-- </div> -->
					</div>
					<div class="left">
						<div style="padding-left: 16px;">
						<?php print $prod['pp_name'];?>
						</div>
					</div>
					<div class="clear"></div>
					<?php 
					$x++;
				}
			}
			?>
		<?php if($pack['package_limit'] > 0) { ?>
			<div class="left p10">
				<!-- first numbers  -->
				<!-- <div style="padding-right: 16px; text-align: right;"> -->
				<!-- <php print $pack['package_limit'];>:  -->
				<!-- </div> -->
			</div>
			<div class="left">
				<div style="padding-left: 16px;">
				<?php print _poses_;?>
				</div>
			</div>
			<div class="clear"></div>				
		<?php } ?>
	<?php if($pack['package_credit'] > 0) { ?>
		<div class="left p10">
			<!-- first numbers  -->
			<!-- <div style="padding-right: 16px; text-align: right;">
				&nbsp; 
				</div>
			</div> -->
			<div class="left">
				<div style="padding-left: 16px;">
				<?php print " ".showPrice($pack['package_credit'])." "._credit_;?>
				</div>
			</div>
			<div class="clear"></div>				
		<?php } ?>
	
		</div>

	<?php 
	}
}?>
