<?php

function prepaypackage($date,$cart,$order_id) { 

	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$date['date_package']."'  ");
	if(empty($pack['package_id'])) { 
		// print _print_credit_not_valid_;
	} else { 
		$order = doSQL("ms_orders", "*", "WHERE order_id='".$order_id."' ");
		if($pack['package_ship'] =="1") {
			$cart_ship = 1;
		}
		if($con['pc_price'] > 0) { 
			$cart_price = $con['pc_price'];
		} else { 
			$cart_price = $pack['package_price'];
		}
		$product_name = $pack['pc_name'];
		$cart_price = "0";
		$x = 0;
		while($x < $cart['cart_qty']) { 
			$x++;
			if($cart['cart_qty'] > 1) { 
				$package_name = $pack['package_name']." (".$x.")";
			} else { 
				$package_name = $pack['package_name'];
			}

			$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($package_name))." ".addslashes(stripslashes(_pre_paid_))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".MD5($order['order_customer'])."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($pack['pc_code']))."' , cart_group_id='".$group['group_id']."', cart_no_delete='1', cart_pre_reg='".$cart['cart_pre_reg']."' ");

			$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ");
			while($co = mysqli_fetch_array($cos)) { 
				insertSQL("ms_cart_options", "co_opt_id='".$co['co_opt_id']."', co_opt_name='".addslashes(stripslashes($co['co_opt_name']))."', co_select_id='".$co['co_select_id']."', co_select_name='".addslashes(stripslashes($co['co_select_name']))."', co_cart_id='".$cart_id."' ");

			}


			if($pack['package_select_only'] == "1") { 
				$p = 1;
				while($p <= $pack['package_select_amount']) { 
					insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".MD5($order['order_customer'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
					$p++;
				}


			} else { 

				$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
				while($prod = mysqli_fetch_array($prods)) { 
					if($prod['pp_type'] =="download") {
						$cart_download = 1;
						$cart_ship = 0;
						}
					$q = 1;
					while($q <= $prod['con_qty']) { 
						insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".MD5($order['order_customer'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
						$q++;
					}
				}
			}
			if($cart['cart_pre_reg'] > 0) { 
				updateSQL("ms_cart", "cart_store_product='".$cart['cart_pre_reg']."' WHERE cart_id='".$cart['cart_id']."' ");
				$ckreg = doSQL("ms_pre_register", "*", "WHERE reg_email='".$order['order_email']."' ");
				if(empty($ckreg['reg_id'])) { 
					insertSQL("ms_pre_register",  "reg_email='".$order['order_email']."',reg_first_name='".addslashes(stripslashes($order['order_first_name']))."', reg_last_name='".addslashes(stripslashes($order['order_last_name']))."', reg_date_id='".$cart['cart_pre_reg']."', reg_ip='', reg_person='".$order['order_customer']."', reg_date=NOW() ");
				}
			}

			if(customerLoggedIn()) { 
				$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$cart['cart_pre_reg']."'  AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
				if(empty($cka['mp_id'])) { 
					$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
					insertSQL("ms_my_pages", "mp_date_id='".$cart['cart_pre_reg']."', mp_people_id='".$person['p_id']."', mp_date=NOW() ");
				}
			}
		}
	}

}
function buyallphotos() { 
	global $setup,$date,$sub,$person;
	if($_REQUEST['view'] == "favorites") { 
		$and_where = "";
		$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
		$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
		$pics_orderby = "pic_org";

		$favspl = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY ms_calendar.date_photo_price_list  ");
		if(mysqli_num_rows($favspl) == 1) { 
			while($favpl = mysqli_fetch_array($favspl)) { 
				$date['date_photo_price_list'] = $favpl['date_photo_price_list'];
			}
		}
		// print "<h1>".mysqli_num_rows($favspl)." - ".$date['date_photo_price_list']."</h1>";
		if(mysqli_num_rows($favspl) > 1) { 
			$date['date_photo_price_list'] = 0;
		}
		$favspl = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_sub_galleries ON ms_favs.fav_sub_id=ms_sub_galleries.sub_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0' GROUP BY ms_sub_galleries.sub_price_list  ");
		if(mysqli_num_rows($favspl) == 1) { 
			while($favpl = mysqli_fetch_array($favspl)) { 
				if( $favpl['sub_price_list'] > 0) { 
					$date['date_photo_price_list'] = $favpl['sub_price_list'];
				}
			}
		}
		if(mysqli_num_rows($favspl) > 1) { 
			$date['date_photo_price_list'] = 0;
		}
		$fav_photos_pl = $date['date_photo_price_list'];
	}


	if($sub['sub_price_list'] > 0) { 
		$date['date_photo_price_list'] = $sub['sub_price_list'];
	}

	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($person['p_price_list'] > 0) { 
			$date['date_photo_price_list'] = $person['p_price_list'];
		}
	}
	if($date['date_photo_price_list'] > 0) { 
		if($_REQUEST['view'] == "favorites") { 
			$and_buy_all = "AND group_no_favs='0' ";
		}
		if(countIt("ms_photo_products_groups", "WHERE group_list='".$date['date_photo_price_list']."' AND group_buy_all='1' $and_buy_all ") > 0) { 

			if($_REQUEST['view'] == "favorites") { 
				$and_where = "";
				$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
				$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
				$pics_orderby = "pic_org";

			} else { 
				$and_where = getSearchString();
				// print "<pre>"; print_r($_REQUEST); 
				if(!empty($sub['sub_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_id']."' ");
					$and_sub = "AND bp_sub='".$sub['sub_id']."' ";
				} else { 
					if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
						$and_sub = "AND bp_sub='0' ";
					}
				}
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

					$pics_where = "WHERE $and_date_tag $and_where ";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
				} else { 
					$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
					$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
				}
			}
			if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
				// Is gallery owner
			} else { 
				$and_where .= " AND pic_hide!='1' ";
			}

			$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where  AND pic_no_dis<='0' GROUP BY pic_id  ");
			$total_images = mysqli_num_rows($pics);


			if($total_images > 0) { 
					?>
				<div class="right textright checkoutpagebutton buyallthumbnailpage">
				<?php 	print "<a class=\"icon-basket checkoutcart\" href=\"\" onclick=\"buyphoto('1','','','".$fav_photos_pl."'); return false;\">"._buy_all_photos_." (".$total_images.")</a>";  ?>
				</div>
				<div class="clear"></div>
				<div>&nbsp;</div>
				<?php 
			}
		}
	}

}


function checkqtyproddiscounts() { 
	$qcarts= whileSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_store_product>'0' AND ".checkCartSession()."  AND cart_order='0' GROUP BY cart_store_product  ");
	while($qcart = mysqli_fetch_array($qcarts)) { 
		$cart_price = $qcart['cart_price'];
		$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$qcart['cart_store_product']."' ");

		if(countIt("ms_products_discounts","WHERE dis_prod='".$qcart['cart_store_product']."' ") > 0) { 
			$qprice = doSQL("ms_products_discounts", "*", "WHERE dis_prod='".$qcart['cart_store_product']."' AND dis_qty_from<='".$qcart['qty']."' AND (dis_qty_to>='".$qcart['qty']."' OR dis_qty_to='0') ");
			if($cart_price !== $qprice['dis_price']) { 
				$price_change = 1;
			}
			$cart_price  = $qprice['dis_price'];
			if($cart_price <=0) { 
				if($prod['prod_price'] > 0) { 
					$cart_price = $prod['prod_price'];
				} else { 
					$cart_price = $prod['prod_price'];
				}
			}
			updateSQL("ms_cart", "cart_price='".$cart_price."' WHERE ".checkCartSession()." AND cart_order='0' AND cart_store_product='".$qcart['cart_store_product']."'  ");
		}
		unset($price_change);
	}
	return $cart_price;
}



function checkqtydiscounts() { 
	$qcarts= whileSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND cart_photo_prod_connect!='0' AND cart_order='0' GROUP BY cart_photo_prod_connect, cart_dis_on  ");
	while($qcart = mysqli_fetch_array($qcarts)) { 
		$cart_price = $qcart['cart_price'];
		$prod = doSQL("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "*", "WHERE pc_id='".$qcart['cart_photo_prod_connect']."' ");
		if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$qcart['cart_photo_prod_connect']."' ") > 0) { 
			$qprice = doSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$qcart['cart_photo_prod_connect']."' AND dis_qty_from<='".$qcart['qty']."' AND (dis_qty_to>='".$qcart['qty']."' OR dis_qty_to='0') ");
			if($cart_price !== $qprice['dis_price']) { 
				$price_change = 1;
			}
			$cart_price  = $qprice['dis_price'];
			
			if($cart_price <=0) { 
				if($prod['pc_price'] > 0) { 
					$cart_price = $prod['pc_price'];
				} else { 
					$cart_price = $prod['pp_price'];
				}
			}
			updateSQL("ms_cart", "cart_price='".$cart_price."' WHERE ".checkCartSession()." AND cart_order='0' AND cart_photo_prod_connect='".$qcart['cart_photo_prod_connect']."' AND cart_dis_on='".$qcart['cart_dis_on']."' ");
		}
		// print "<li>".$qcart['qty'].": ".$prod['pp_name']." - count: ".$qcart['count']." qty: ".$qcart['qty']." new price: ".$cart_price." - price change: ".$price_change." dis: ".$qprice['dis_price']."";
		unset($price_change);
	}
}

function getPagePreview($date,$size) { 
	global $setup;
	if($size == "thumb") { 
		$this_size = "date_thumb";
		$this_pic = "pic_th";
	}
	if($size == "mini") { 
		$this_size = "date_mini";
		$this_pic = "pic_mini";
	}

	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {

		$size = getimagefiledems($pic,$this_pic);
		if($date['prod_no_link'] == "1") { 
			$thumb_html ="<img src=\"".getimagefile($pic,$this_pic)."\" class=\"thumb"; if($layout['layout_css_id'] == "thumbnaillisting") {if($size[0]>=$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";
		} else { 
			$thumb_html ="<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,$this_pic)."\" class=\"thumb"; if($layout['layout_css_id'] == "thumbnaillisting") {if($size[0]>=$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\"></a>";
		}
	
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			$size = getimagefiledems($pic,$this_pic);
			if($date['prod_no_link'] == "1") { 
				$thumb_html ="<img src=\"".getimagefile($pic,$this_pic)."\" class=\"thumb"; if($layout['layout_css_id'] == "thumbnaillisting") {if($size[0]>=$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";
			} else { 
				$thumb_html ="<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,$this_pic)."\" class=\"thumb"; if($layout['layout_css_id'] == "thumbnaillisting") {if($size[0]>=$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\"></a>";
			}
		}
	}
	return $thumb_html;
}



function playTrack($track,$tN,$tTracks) {
	global $setup;
	if((!empty($track['music_file']))AND($track['music_type']=="0")==true) {
	?>
    <div id="flashbanner<?php print $tN;?>" style="float: left;">
<!-- HTML 5 audio player -->
     <div class="html5player" align="center">
<?php 
// Check for iPads and iPods // 
	if($_SESSION['ipad']==true) {  
		if(!empty($track['music_preview_file'])) {
			$play_file = $track['music_preview_file'];
		} else {
			$play_file = $track['music_file'];
		}
		?>
      <div id="playaud-<?php print $tN;?>"><a href="javascript:playMsAudio('aud-<?php print $tN;?>','<?php print $tTracks;?>','<?php print "/".$setup['music_file_location']."/".$play_file;?>','ipad');"><img src="/sy-inc/player/widgetbuttons/play.png" border=0></a></div>
	  <div id="pauseaud-<?php print $tN;?>" style="display: none;"><a href="javascript:pauseMsAudio('aud-<?php print $tN;?>','<?php print $tTracks;?>');"><img src="/sy-inc/player/widgetbuttons/stop.png" border=0></a></div>
	  <audio  id="aud-<?php print $tN;?>" preload="none">
	  </audio>
	<?php } else { ?>
      <div id="playaud-<?php print $tN;?>"><a href="javascript:playMsAudio('aud-<?php print $tN;?>','<?php print $tTracks;?>','<?php print MD5($track['music_id']);?>','enc');"><img src="/sy-inc/player/widgetbuttons/play.png" border=0></a></div>
	  <div id="pauseaud-<?php print $tN;?>" style="display: none;"><a href="javascript:pauseMsAudio('aud-<?php print $tN;?>','<?php print $tTracks;?>');"><img src="/sy-inc/player/widgetbuttons/stop.png" border=0></a></div>
      <audio  id="aud-<?php print $tN;?>" preload="none">
	  </audio>
<?php } ?>
     </div>
<!-- END HTML 5 audio player -->
</div>
	<script type="text/javascript">
      var so<?php print $tN;?> = new SWFObject('/sy-inc/player/player.swf','Player<?php print $tN;?>','30','30','9');
      so<?php print $tN;?>.addParam('wmode','transparent');
      so<?php print $tN;?>.addParam('flashvars','totalplayers=<?php print $tTracks;?>&playerid=<?php print $tN;?>&config_file=/sy-inc/player/config.xml&track=<?php print "".MD5($track['music_id'])."";?>&play_source=fd3&phpPath=/ms_playmp3.php');
      so<?php print $tN;?>.write('flashbanner<?php print $tN;?>');
    </script>
	
		<?php
	} else {
		return "&nbsp;";
	}
}
function productPrice($prod) { 
	global $setup;
	if(($prod['prod_sale_price'] > 0)&&(date('Y-m-d') <=$prod['prod_sale_end'])&&(date('Y-m-d') >=$prod['prod_sale_start'])==true) {
		$pprice['price'] = $prod['prod_sale_price'];
		$pprice['org'] = $prod['prod_price'];
		$pprice['onsale'] = true;
		$pprice['sale_message'] = $prod['prod_price_message'];
	} else { 
		// $this_price = checkqtyproddiscounts();
		// $pprice['price'] = $this_price;
		$pprice['price'] = $prod['prod_price'];
	}
	return $pprice;
}

function productPriceCart($prod) { 
	global $setup;
	if((!empty($_REQUEST['cart_client'])) || (!empty($_REQUEST['cart_session'])) == true) { 
		if(empty($_REQUEST['cart_client'])) {
			$and_cart_where = "cart_session='".$_REQUEST['cart_session']."' AND cart_client='' ";
		} else {
			$and_cart_where = "cart_client='".$_REQUEST['cart_client']."' ";
		}
	} else { 
		$and_cart_where = checkCartSession();
	}
	// print "<li>".$and_cart_where;

	if(($prod['prod_sale_price'] > 0)&&(date('Y-m-d') <=$prod['prod_sale_end'])&&(date('Y-m-d') >=$prod['prod_sale_start'])==true) {
		$pprice['price'] = $prod['prod_sale_price'];
		$pprice['org'] = $prod['prod_price'];
		$pprice['onsale'] = true;
		$pprice['sale_message'] = $prod['prod_price_message'];
	} else { 
		
		$qcarts= whileSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_store_product>'0' AND $and_cart_where  AND cart_store_product='".$prod['date_id']."' AND cart_order='0' GROUP BY cart_store_product  ");
		while($qcart = mysqli_fetch_array($qcarts)) { 
			$cart_price = $qcart['cart_price'];
			$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$qcart['cart_store_product']."' ");

			if(countIt("ms_products_discounts","WHERE dis_prod='".$qcart['cart_store_product']."' ") > 0) { 
				$qprice = doSQL("ms_products_discounts", "*", "WHERE dis_prod='".$qcart['cart_store_product']."' AND dis_qty_from<='".$qcart['qty']."' AND (dis_qty_to>='".$qcart['qty']."' OR dis_qty_to='0') ");
				if($cart_price !== $qprice['dis_price']) { 
					$price_change = 1;
				}
				$cart_price  = $qprice['dis_price'];
				if($cart_price <=0) { 
					if($prod['prod_price'] > 0) { 
						$cart_price = $prod['prod_price'];
					} else { 
						$cart_price = $prod['prod_price'];
					}
				}

				updateSQL("ms_cart", "cart_price='".$cart_price."' WHERE $and_cart_where AND cart_order='0' AND cart_store_product='".$qcart['cart_store_product']."'  ");
			}
			unset($price_change);
		}

		$this_price = $cart_price;
		$pprice['price'] = $this_price;
		// $pprice['price'] = $prod['prod_price'];
	}
	return $pprice;
}


function showPrice($amount) {
	global $tr,$order,$vat,$site_setup,$store,$no_trim,$setup;
	$amount = number_format($amount, 2, '.', '');
	$format = $store['price_format'];
	$html =str_replace("[CURRENCY_SIGN]", "".$store['currency_sign']."", $format);
	$ck = explode(".",$amount);
	if(($ck[1] > 0)&&($store['price_decimals'] == "0")==true) { 
		$dec = 2;
	} else { 
		$dec = $store['price_decimals'];
	}
	if($no_trim == true) { 
		$dec = 2;
	}
	$html =str_replace("[PRICE]", "".number_format($amount,$dec)."", $html);
	if(($no_trim !== true)&&($dec==0)==true) {
		//$html = str_replace(".00", "", $html);
	}
	if($setup['price_comma'] == true) { 
		$html = str_replace(".",",",$html);
	}
	return $html;
}
function shoppingCartActions($track,$action) {
	global $setup;
	$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_order<='0' AND cart_product='".$track['music_id']."' " );
	if(!empty($cart['cart_id'])) {
		if($action == "viewcart") {

			$html.="<div id=\"product-".$track['music_id']."-$s\" style=\"padding: 0px;\"><span id=\"removeFromCart\"><a href=\"".$setup['temp_url_folder']."/".$setup['store_folder']."/ms_cart.php?aAction=cart&remove=".md5($cart['cart_id'])."&fd=product-".$track['music_id']."-$s&mssess=".$_SESSION['ms_session']."&rtcart=yes\">"._icon_remove_from_cart_."</a></span></div>";

		} else {
			$html.="<div id=\"product-".$track['music_id']."-$s\" style=\"padding: 0px;\"><span id=\"inShoppingCart\"><a href=\"".$setup['temp_url_folder']."/".$setup['store_folder']."/index.php?view=cart\">"._icon_in_cart_."</a></span><br><span id=\"removeFromCart\"><a href=\"javascript:cartActions('/".$setup['store_folder']."/ms_cart.php?aAction=cart&remove=".md5($cart['cart_id'])."&fd=product-".$track['music_id']."-$s&mssess=".$_SESSION['ms_session']."', 'product-".$track['music_id']."-$s');\">"._icon_remove_from_cart_."</a></span></div>";
		}
		} else {
			$html.="<div  id=\"product-".$track['music_id']."-$s\" style=\"padding: 0px;\" ><span id=addToCartLink><a href=\"javascript:cartActions('/".$setup['store_folder']."/ms_cart.php?aAction=cart&add=".md5($track['music_id'])."&fd=product-".$track['music_id']."-$s&mssess=".$_SESSION['ms_session']."', 'product-".$track['music_id']."-$s');\">"._icon_add_to_cart_."</a></span></div>";
		}
	return $html;
}
function storeShoppingCartActions($prod,$action) {
	global $setup;
	$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_order<='0' AND cart_store_product='".$prod['date_id']."' " );
	if($action == "viewcart") {
		$html.="<div id=\"product-".$prod['date_id']."-$s\" style=\"padding: 0px;\"><span id=\"removeFromCart\"><a href=\"".$setup['temp_url_folder']."/".$setup['store_folder']."/shop.cart.php?action=removefromcartview&cid=".md5($cart['cart_id'])."\">"._remove_from_cart_."</a></span></div>";
	} else { 
		if(!empty($cart['cart_id'])) {
			$add = "none";
			$remove = "inline";
		} else { 
			$add = "inline";
			$remove = "none";
		}
		$html.="<div  id=\"add-to-cart-".MD5($prod['date_id'])."\" style=\"display: $add;\"><span id=addToCartLink><a href=\"\" onClick=\"addToCart('".$setup['store_folder']."','".md5($prod['date_id'])."'); return false;\"><nobr>".icon_cart." "._add_to_cart_."</nobr></a></span></div>";
		$html.="<div id=\"remove-from-cart-".MD5($prod['date_id'])."\" style=\"display: $remove;\">";
		$html .= "<div class=\"incartcontainer\"><div  id=\"inShoppingCart\" class=\"incart\"><div><a href=\"".$setup['temp_url_folder']."/".$setup['store_folder']."/cart/\">".icon_cart." "._in_my_cart_."</a></div>";
		$html .= "<div class=\"incartmenu\"><div><a href=\"\" onClick=\"removeFromCart('".$setup['store_folder']."','".md5($prod['date_id'])."'); return false;\">"._remove_from_cart_."</a></div><div><a href=\"/".$setup['store_folder']."/cart/\">"._view_cart_."</a></div></div></div>";
		
		$html .= "</div></div>";


		$html .= "<div id=\"add-to-cart-loading-".MD5($prod['date_id'])."\" class=\"addToCartLoading\"><img src=\"/sy-graphics/loading.gif\"></div>";
	}
	return $html;
}



function listMusicStoreProducts($track,$headings,$action,$tt,$tracks_total=0) {
	global $cart,$order,$download_attempts,$setup;
	$com_settings = doSQL("ms_comments_settings", "*", "");

	print "<tr valign=top  class=special>";

foreach($headings AS $label) {

	if($label == "play_button") {
		print "<td class=\"pageContent\">";
		print playTrack($track,$tt,$tracks_total);
		print "</td>";
	}


	if($label == "music_title") {
		print "<td class=\"pageContent\">";
		if((empty($track['music_lyrics']))AND(empty($track['music_descr']))AND(empty($track['music_graphic']))==true) {
			print "<span id=storeRowTextTitle>".$track['music_title']."&nbsp;</span>";  
		} else {
			print "<span id=storeRowTextTitle><a href=\"/".$setup['music_folder']."/".$track['music_link']."/";  if($track['music_explicit'] == "1") { print "?excl=explicit"; } if($track['music_clean'] == "1") { print "?excl=clean"; } print "\">".$track['music_title']."</a>&nbsp;</span>";  
		}	
		if($track['music_explicit'] == "1") { print " "._explicit_icon_.""; }  ?><?php  if($track['music_clean'] == "1") { print " "._clean_icon_.""; }  ?>
		<?php 
			if(date('Y-m-d H:i:s')<= $track['show_as_new_date']) { print " "._new_icon_.""; } 
		print "<div id=storeTrackMoreInfo>";
//		if(!empty($track['music_year'])) {
//			print "".$track['music_year']." ";
//		}
		if($com_settings['songs']=="1") {
			print "<a href=\"/".$setup['music_folder']."/".$track['music_link']."/";  if($track['music_explicit'] == "1") { print "?excl=explicit"; } if($track['music_clean'] == "1") { print "?excl=clean"; } print "#listComments\" >"._comments_."</a>&nbsp; ";
		}

		if(!empty($track['music_lyrics'])) {
			print "<a href=\"/".$setup['music_folder']."/".$track['music_link']."/";  if($track['music_explicit'] == "1") { print "?excl=explicit"; } if($track['music_clean'] == "1") { print "?excl=clean"; } print "\">"._store_lyrics_."</a>&nbsp; ";
		}



//		print "&nbsp; <a href=\"index.php?viewTrack=".MD5($track['music_id'])."\">"._store_more_info_."</a>";
		if(!empty($track['music_alt_store_link'])) {
			print " "._store_also_available_at_." ";
			$astores = explode("\r\n",$track['music_alt_store_link']);
			foreach($astores AS $store) {
				$thisstore = explode(",",$store);
				print "<a href=\"$thisstore[0]\" target=\"_blank\">$thisstore[1]</a>&nbsp;&nbsp;";
			}
		}
		print "</div>";
	print "</td>";
	}
	if($label == "music_album") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">";
		if(empty($track['music_album'])) { 
			print _store_single_;
		} else {
			print $track['music_album'];
		}
		print "</span></td>";
	}
	if($label == "music_artist") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">".$track['music_artist']."&nbsp;</span></td>";
	}
	if($label == "music_time") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">".$track['music_length']."&nbsp;</span></td>";
	}
	if(($label == "music_price")AND(($action !== "ffd")AND($action !== "coupon"))==true) {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">".showPrice($track['music_price'])."</span></td>";
	}
	if($label == "music_year") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">".$track['music_year']."&nbsp;</span></td>";
	}
	if($label == "music_track") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">"; if($track['music_track'] > 0) { print "".$track['music_track'].""; } print "&nbsp;</span></td>";
	}
	if($label == "music_genre") {
		print "<td class=\"pageContent\"><span id=\"storeRowText\">".$track['music_genre']."&nbsp;</span></td>";
	}

}




	if($action == "cart") {
		print "<td class=\"pageContent\" align=right>";
		if($track['music_no_sell'] == "1") { print "&nbsp;"; } else {  print "".shoppingCartActions($track,$action).""; } 
		print "</td>";
	}
	if($action == "viewcart") {
		print "<td class=\"pageContent\" align=right>";
		if($track['music_no_sell'] == "1") { print "&nbsp;"; } else {  print "".shoppingCartActions($track,$action).""; } 
		print "</td>";
	}
	if($action == "coupon") {
		print "<td class=\"pageContent\" align=right>";
		print "<form method=\"POST\" name=\"selectcoup-".$track['music_id']."\" action=\"index.php\" style=\"margin:0px; padding:0px;\">";
		print "<input type=\"hidden\" name=\"first_name\" value=\"".addslashes(stripslashes($_POST['first_name']))."\">";
		print "<input type=\"hidden\" name=\"last_name\" value=\"".addslashes(stripslashes($_POST['last_name']))."\">";
		print "<input type=\"hidden\" name=\"email_address\" value=\"".addslashes(stripslashes($_POST['email_address']))."\">";
		print "<input type=\"hidden\" name=\"address\" value=\"".addslashes(stripslashes($_POST['address']))."\">";
		print "<input type=\"hidden\" name=\"city\" value=\"".addslashes(stripslashes($_POST['city']))."\">";
		print "<input type=\"hidden\" name=\"state\" value=\"".addslashes(stripslashes($_POST['state']))."\">";
		print "<input type=\"hidden\" name=\"country\" value=\"".addslashes(stripslashes($_POST['country']))."\">";
		print "<input type=\"hidden\" name=\"zip\" value=\"".addslashes(stripslashes($_POST['zip']))."\">";
		print "<input type=\"hidden\" name=\"phone\" value=\"".addslashes(stripslashes($_POST['phone']))."\">";
		print "<input type=\"hidden\" name=\"join_ml\" value=\"".addslashes(stripslashes($_POST['join_ml']))."\">";
		$couponcode = strtoupper($_REQUEST['coup1']."-".$_REQUEST['coup2']."-".$_REQUEST['coup3']."-".$_REQUEST['coup4']);
		print "<input type=\"hidden\" name=\"couponcode\" value=\"".addslashes(stripslashes($couponcode))."\">";
		print "<input type=\"hidden\" name=\"action\" value=\"addCouponOrder\">";
		print "<input type=\"hidden\" name=\"music_id\" value=\"".MD5($track['music_id'])."\">";
		print "<input type=\"submit\" name=\"selectTrack\" value=\""._coupon_select_track_."\"  onClick=\"return confirm('"._coupon_confirm_select_popup_." ".addslashes($track['music_title'])."');\">";
		print "</form>";
		print "</td>";
	}

	if($action == "download") {
		print "<td class=\"pageContent\" align=right>";
		if(($download_attempts - $cart['cart_download_attempts'])<=0) {
			print "<span class=\"inShoppingCart\">"._store_download_attempts_exceeded_."</span>";
		} else {
			print "<span id=\"storeDownload\"><a href=\"storedownload.php?msok=".MD5($order['order_key'])."&msorder=".MD5($order['order_id'])."&crtid=".MD5($cart['cart_id'])."\" target=\"_blank\">"._store_download_link_."</a></span><br><span id=\"inShoppingCart\">"._store_download_attempts_left_."";
			print $download_attempts - $cart['cart_download_attempts'];
			print "</span>";
		}
		print "</td>";
	}

	if($action == "ffd") {
		print "<td class=\"pageContent\" align=right><span id=\"storeDownload\"><a href=\"index.php?track=".MD5($track['music_id'])."&downloadCode=".$_REQUEST['downloadCode']."\" onClick=\"return confirm('"._download_confirm_download_popup_." ".addslashes($track['music_title'])."');\">"._download_download_."</a></span></td></tr>";
	}



	print "</tr>";
}


 function getMP3Tags($mp3) {
 
                 //make a array of genres
     $genre_arr = array("Blues","Classic Rock","Country","Dance","Disco","Funk","Grunge",
 "Hip-Hop","Jazz","Metal","New Age","Oldies","Other","Pop","R&B",
 "Rap","Reggae","Rock","Techno","Industrial","Alternative","Ska",
 "Death Metal","Pranks","Soundtrack","Euro-Techno","Ambient",
 "Trip-Hop","Vocal","Jazz+Funk","Fusion","Trance","Classical",
 "Instrumental","Acid","House","Game","Sound Clip","Gospel",
 "Noise","AlternRock","Bass","Soul","Punk","Space","Meditative",
 "Instrumental Pop","Instrumental Rock","Ethnic","Gothic",
 "Darkwave","Techno-Industrial","Electronic","Pop-Folk",
 "Eurodance","Dream","Southern Rock","Comedy","Cult","Gangsta",
 "Top 40","Christian Rap","Pop/Funk","Jungle","Native American",
 "Cabaret","New Wave","Psychadelic","Rave","Showtunes","Trailer",
 "Lo-Fi","Tribal","Acid Punk","Acid Jazz","Polka","Retro",
 "Musical","Rock & Roll","Hard Rock","Folk","Folk-Rock",
 "National Folk","Swing","Fast Fusion","Bebob","Latin","Revival",
 "Celtic","Bluegrass","Avantgarde","Gothic Rock","Progressive Rock",
 "Psychedelic Rock","Symphonic Rock","Slow Rock","Big Band",
 "Chorus","Easy Listening","Acoustic","Humour","Speech","Chanson",
 "Opera","Chamber Music","Sonata","Symphony","Booty Bass","Primus",
 "Porn Groove","Satire","Slow Jam","Club","Tango","Samba",
 "Folklore","Ballad","Power Ballad","Rhythmic Soul","Freestyle",
 "Duet","Punk Rock","Drum Solo","Acapella","Euro-House","Dance Hall");
     
     $filesize = filesize($mp3);
     $file = fopen($mp3, "r");
     fseek($file, -128, SEEK_END);
     
     $tag = fread($file, 3);
     
     
     if($tag == "TAG")
     {
         $data["song"] = trim(fread($file, 30));
         $data["artist"] = trim(fread($file, 30));
         $data["album"] = trim(fread($file, 30));
         $data["year"] = trim(fread($file, 4));
         $data["comment"] = trim(fread($file, 28));
         $data["track"] = trim(fread($file, 2));
        $data["genre"] = $genre_arr[ord(trim(fread($file, 1)))];
     }
//     else
 //        die("MP3 file does not have any ID3 tag!");
     
     fclose($file);
     return $data;
     
 }
function checkCartSession() {
	global $_COOKIE;
	if(empty($_SESSION['pid'])) {
		return "cart_session='".$_SESSION['ms_session']."' AND cart_client='' ";
	} else {
		return "cart_client='".$_SESSION['pid']."' ";
	}
}
function checkPackageComplete() { 
	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_order<='0' AND cart_package_no_select!='1'  ORDER BY cart_pic_org  ASC" );
	while($cart= mysqli_fetch_array($carts)) {
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
		if($pack['package_require_all'] == "1") { 
			$total_select = countIt("ms_cart", "WHERE  ".checkCartSession()." AND  cart_package_photo='".$cart['cart_id']."' ");
			$total_selected = countIt("ms_cart", "WHERE  ".checkCartSession()." AND  cart_package_photo='".$cart['cart_id']."' AND cart_pic_id!='0' ");
			if($total_select !== $total_selected) { 
			//	print "<li>".$pack['package_name']." ".$total_selected." ".$total_select;
				$stop_checkout['stop'] = true;
				$stop_checkout['why'] = "package";
				$stop_checkout['cart_bonus_coupon'] = $cart['cart_bonus_coupon'];
			}
		}
	}
	return $stop_checkout;
}


function shoppingCartTotal($mssess) {
	global $site_setup,$store;
	if(!empty($mssess)) {
		$icarts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE cart_session='".$mssess."' AND cart_order<='0' AND cart_package_photo='0' " );
	} else {
	// AND cart_package_buy_all<='0' added in where condistion 
		$icarts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_order<='0' AND cart_package_buy_all<='0' AND cart_price !='0'  AND cart_package_photo='0' " );
	}
	while($icart= mysqli_fetch_array($icarts)) {
		$pt++;
		if(($icart['cart_store_product'] > 0)&&($icart['cart_account_credit_for'] <=0)==true) { 
			$price = productPriceCart($icart);

			$this_price = $price['price'];
			if(!empty($icart['cart_sub_id'])) { 
				$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$icart['cart_sub_id']."' ");
				$this_price = $this_price + $sub['sub_add_price'];
			}
		} else { 
			$this_price = $icart['cart_price'];
		}
		if($icart['cart_credit'] > 0) { 
			$cart_credit = $cart_credit + $icart['cart_credit'];
		}
		if($icart['cart_photo_prod'] > 0) {
			$photo_prods = $photo_prods + ($icart['cart_qty'] * $icart['cart_price']);
			if($icart['cart_taxable'] == "1") {
				$photo_prods_taxable = $photo_prods_taxable + ($icart['cart_qty'] * $icart['cart_price']);
			}
		}

		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$icart['cart_id']."' AND co_pic_id<='0' AND co_download<='0' ");
		while($co = mysqli_fetch_array($cos)) {
			  $this_price = $this_price + $co['co_price']; 
			//  print "<li>option: ".$co['co_price']; 
			  if($co['co_price'] < 0) { 
				  $photo_prods = $photo_prods + ($co['co_price'] * $icart['cart_qty']);
			  }
		} 
		if(($icart['cart_package_photo']<=0)&&($icart['cart_product_photo']<=0)==true) { 
			$total_items  = $total_items+  $icart['cart_qty'];
		}

		if($icart['cart_package'] > 0) { 
			$ipics = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE   cart_order<='0' AND cart_package_photo='".$icart['cart_id']."' " );
			while($ipic = mysqli_fetch_array($ipics)) { 
				$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$ipic['cart_id']."' ");
				while($co = mysqli_fetch_array($cos)) {
					 $this_price = $this_price + $co['co_price']; 
				} 

			}
		}
		$add_ship = $add_ship + ($icart['cart_extra_ship'] * $icart['cart_qty']);


		//		$itotal  = round($this_price  * $icart['cart_qty'], 2);
		// Removed round for prices with VAT
		$itotal  = $this_price  * $icart['cart_qty'];
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$icart['cart_id']."' AND co_pic_id<='0' AND co_download='1' ");
		while($co = mysqli_fetch_array($cos)) {
			 $itotal = $itotal + $co['co_price'];  
		} 

		if($icart['cart_taxable'] == "1") {
			$tax_total = $tax_total + $itotal;
		}
		if($icart['cart_ship'] == "1") {
			$ship_cal_total = $ship_cal_total + $itotal;
		}
		$sub_total = $sub_total + $itotal;
		$sub_total = $sub_total + $io_total;
		if($icart['cart_no_discount'] !=="1") { 
			$discount_total = $discount_total + $itotal;
			$discount_total = $discount_total + $io_total;
		}
	}



	$iocarts = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE ".checkCartSession()."  AND cart_order<='0' AND co_pic_id>'0' GROUP BY co_opt_name ");
	while($iocart= mysqli_fetch_array($iocarts)) {
		$sub_total = $sub_total + $iocart['this_price'];
		if($iocart['co_taxable'] == "1") {
			$tax_total = $tax_total + $iocart['this_price'];
		}
		if($iocart['co_discountable'] == "1") { 
			$discount_total = $discount_total + $iocart['this_price'];
		}
	}


	if($cart_credit > 0) { 
		if($photo_prods > $cart_credit) {
			$credit = $cart_credit;
		} else { 
			$credit = $photo_prods;
		}
		$sub_total = $sub_total - $credit;

		if($photo_prods_taxable > $cart_credit) {
			$credit = $cart_credit;
		} else { 
			$credit = $photo_prods_taxable;
		}
		$tax_total = $tax_total - $credit;

	}
		$discount_total = $discount_total - $credit; 

	if(!empty($mssess)) {
		$ebs = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id LEFT JOIN ms_promo_codes ON ms_calendar.date_id=ms_promo_codes.code_date_id", "*", "WHERE cart_session='".$mssess."' AND cart_order<='0' AND cart_pic_date_id>'0' AND code_id>'0' AND (code_end_date>='".date('Y-m-d')."' OR code_end_date='0000-00-00') AND cart_no_discount='0' " );
	} else {
		$ebs = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id LEFT JOIN ms_promo_codes ON ms_calendar.date_id=ms_promo_codes.code_date_id", "*", "WHERE ".checkCartSession()." AND cart_order<='0' AND cart_pic_date_id>'0' AND code_id>'0' AND (code_end_date>='".date('Y-m-d')."' OR code_end_date='0000-00-00') AND cart_no_discount='0' " );
	}

	$ebdates = array();
	while($eb = mysqli_fetch_array($ebs)) { 
		if(empty($eb_code_id)) { 
			$eb_code_id = $eb['code_id'];
		}
		if(!in_array($eb['date_id'],$ebdates)) { 
			array_push($ebdates,$eb['date_id']);
		}
		$prod_amount =  $prod_amount + $eb['cart_qty'] * $eb['cart_price'];
		if($eb['cart_credit'] > 0) { 
			$eb_cart_credit = $eb_cart_credit + $eb['cart_credit'];
		}
		if($eb['cart_photo_prod'] > 0) {
			$eb_photo_prods = $eb_photo_prods + ($eb['cart_qty'] * $eb['cart_price']);
		}

	  ########### Get image options for early bird discount ############## 

		$iocarts = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE ".checkCartSession()."  AND cart_order<='0' AND co_pic_id>'0' GROUP BY co_opt_name ");
		while($iocart= mysqli_fetch_array($iocarts)) {
			if($iocart['co_discountable'] == "1") { 
				$prod_amount = $prod_amount + $iocart['this_price'];
			}
		}
		############ Get options for early bird discount ##################

		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$eb['cart_id']."' AND co_pic_id<='0' AND co_download<='0' ");
		while($co = mysqli_fetch_array($cos)) {
			 // print "<li>option: ".$co['co_price']; 
			if($eb['cart_no_discount'] !=="1") { 
				if($co['co_price'] > 0) { 
					$prod_amount = $prod_amount + ($co['co_price'] * $eb['cart_qty']);
				}
			}
		} 




		// print "<li>".$eb['date_title']." - ".$eb['cart_id']." = ".$eb['cart_qty'] * $eb['cart_price'];
	}
	if($eb_cart_credit > 0) { 
		if($eb_photo_prods > $eb_cart_credit) {
			$eb_credit = $eb_cart_credit;
		} else { 
			$eb_credit = $eb_photo_prods;
		}
	}
	$prod_amount = $prod_amount - $eb_credit;
	// print "<li>eb date id: ".$eb_date." prod_amount: ".$prod_amount." - eb_credit: ".$eb_cart_credit." eb photo prods: ".$eb_photo_prods." eb_credit: ".$eb_credit;

		$dis = doSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$eb_code_id."' AND dis_from<='".$prod_amount."' AND dis_to>='".$prod_amount."' ");
		if($dis['dis_flat'] > 0) { 
			$eb_amount = $dis['dis_flat'];
			if($eb_amount > $sub_total) {
				$eb_amount = $sub_total;
			}
		//	print "<li>FLAT: ".$dis['dis_flat'];
		}
		if($dis['dis_percent'] > 0) { 
			$eb_amount =  round((($prod_amount * $dis['dis_percent'])/100),2);
			// $discount['promo_percentage'] = $dis['dis_percent'];
		//	print "<li>PERC: ".$dis['dis_percent'];
		}
	// print "<li>".$prod_amount." - ".showPrice($eb_amount);

//	$vat = checkVat($tax_total);
//	$discount_total = $sub_total;
	if($store['ship_group_extra_shipping_charge'] == "1") { 
		$add_ship = 0;
		$ppas = whileSQL("ms_cart", "*", "WHERE cart_extra_ship>'0'  AND cart_photo_prod>'0' AND cart_order<='0' AND ".checkCartSession()." GROUP BY cart_photo_prod ");
		while($ppa = mysqli_fetch_array($ppas)) { 
			$add_ship = $add_ship + $ppa['cart_extra_ship'];
		}
		$sas = whileSQL("ms_cart", "*", "WHERE cart_extra_ship>'0'  AND cart_store_product>'0' AND cart_order<='0'  AND ".checkCartSession()." GROUP BY cart_store_product ");
		while($sa = mysqli_fetch_array($sas)) { 
			$add_ship = $add_ship + $sa['cart_extra_ship'];
		}
		$sas = whileSQL("ms_cart", "*", "WHERE cart_extra_ship>'0'  AND cart_package>'0'  AND cart_order<='0' AND ".checkCartSession()." GROUP BY cart_package");
		while($sa = mysqli_fetch_array($sas)) { 
			$add_ship = $add_ship + $sa['cart_extra_ship'];
		}

	}
	$vat =  (($tax_total * $site_setup['include_vat_rate']) / 100);

	/* CHANGE FOR USING A FLAT RATE COUPON FOR SAME AMOUNT AND VAT AND DISCOUNTS AFTER TAX */

	if(($store['tax_discount'] == "after") && ($site_setup['include_vat'] == "1") == true) { 
		$discount_total = $discount_total + $vat - $eb_amount;
	} else { 
		$discount_total = $discount_total - $eb_amount;
	}
	$discount_total = round($discount_total, 2);

	$discount = getCoupon($mssess,$discount_total);
	$total['promo_discount_amount'] = $discount['promo_discount_amount'];
	$total['eb_amount'] = $eb_amount;
	$total['promo_percentage'] = $discount['promo_percentage'];
	$total['photo_prods'] = $photo_prods;
	$total['cart_credit'] = $cart_credit;
	$total['discount_total'] = $discount_total;
	$total['show_cart_total'] = ($sub_total + $vat) - $total['promo_discount_amount'] - $eb_amount;
	$total['total'] = $sub_total;
	$total['vat'] = $vat;
	$total['sub_total'] = $sub_total- $total['promo_discount_amount'];
	$total['tax_total'] = $tax_total;
	$total['add_ship'] = $add_ship;
	$total['ship_cal_total'] = $ship_cal_total;
	$total['total_no_ship'] = $total_no_ship;
	$total['total_items'] = $total_items;
	// print "<li>".$total['total']."";

	return $total;
}

function getCoupon($mssess,$discount_total) { 
	if(!empty($mssess)) {
		$pcart = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE cart_session='".$mssess."' AND cart_order<='0' AND code_print_credit<='0' AND cart_coupon!='0' " );
	} else { 
		$pcart = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()." AND cart_order<='0' AND code_print_credit<='0' AND cart_coupon!='0' " );
	}

	if(!empty($pcart['cart_id'])) {
		$promo = doSQL("ms_promo_codes", "*", "WHERE code_id='".$pcart['cart_coupon']."'  ");
		$dis = doSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$promo['code_id']."' AND dis_from<='".$discount_total."' AND dis_to>='".$discount_total."' ");
		if($dis['dis_flat'] > 0) { 
			if($discount_total > $dis['dis_flat']) { 
				$discount['promo_discount_amount'] = $dis['dis_flat'];
			} else {
				$discount['promo_discount_amount'] = $discount_total;
			}
		}
		if($dis['dis_percent'] > 0) { 
			$discount['promo_discount_amount'] =  round((($discount_total * $dis['dis_percent'])/100),2);
			$discount['promo_percentage'] = $dis['dis_percent'];
		}
		$discount['promo_id'] = $promo['code_id'];
		$discount['promo_name'] = $promo['code_name'];
		$discount['promo_code'] = $promo['code_code'];

	}
	return $discount;
}

function checkCouponOrder($order_id) { 
	$cart = doSQL("ms_cart", "*", "WHERE cart_order='".$order_id."' AND cart_coupon!='0' ");
	if($cart['cart_id'] > 0) { 
		$promo = doSQL("ms_promo_codes", "*", "WHERE code_id='".$cart['cart_coupon']."'  ");
		if($promo['code_use'] == "once") { 
			updateSQL("ms_promo_codes", "code_use_status='1' WHERE code_id='".$promo['code_id']."' ");
		}
	}
}

function checkCouponOnePerPerson($email) { 
	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_order<='0'  AND cart_coupon!='0' " );
	while($cart = mysqli_fetch_array($carts)) { 
		if($cart['cart_id'] > 0) { 
			$promo = doSQL("ms_promo_codes", "*", "WHERE code_id='".$cart['cart_coupon']."'  ");
			if($promo['code_use'] == "onceperson") { 
				if(customerLoggedIn()) { 
					$p = doSQL("ms_people", "*", "WHERE MD5(p_id) = '".$_SESSION['pid']."' ");
					$ckorders = doSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*", "WHERE cart_coupon='".$promo['code_id']."' AND order_email='".$p['p_email']."' AND cart_order>'0' ");
				}
				if(!empty($email)) { 
					$ckorders = doSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*", "WHERE cart_coupon='".$promo['code_id']."' AND order_email='".$email."' AND cart_order>'0' ");
				}
				if(!empty($ckorders['order_id'])) { 
					print "nogood";
					$_SESSION['onepersoncoupon'] = true;
					deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
					$bonuses = whileSQL("ms_cart", "*", "WHERE cart_bonus_coupon='".$cart['cart_id']."' ");
					while($bonus = mysqli_fetch_array($bonuses)) { 
						deleteSQL2("ms_cart", "WHERE cart_package_photo='".$bonus['cart_id']."' ");
						deleteSQL("ms_cart", "WHERE cart_id='".$bonus['cart_id']."'", "1");
					}



					if($_REQUEST['noredirect'] !== "1") { 
						header("location: /index.php?view=cart");
					}
					session_write_close();
					exit();
				}
			}
		}
	}
}


function showCartTotal($mssess) {
	global $setup;
	$total = shoppingCartTotal($mssess);
	if($total['total_items'] > 0) {
		$html  .= "<a href=\"".$setup['temp_url_folder']."/".$setup['store_folder']."/cart/\">"._shopping_cart_link_.": ".showPrice($total['sub_total'])." - "._checkout_menu_link_."</a>";
	} else {
		$html .= _my_cart_empty_;
	}
	return $html;
}

function storeCatAdvMenu($top_sub_folder) {
	global $setup;

	if(!empty($_REQUEST['date_id'])) {
		$gal = doSQL("ms_calendar", "*", "WHERE date_id='".$prod['date_id']."' " );
		$_REQUEST['category'] = $gal['gal_cat'];
	}
	if(!empty($_REQUEST['category'])) {
		$mcat = doSQL("ms_calendar_cats", "*", "WHERE cat_id='".$_REQUEST['category']."' " );
		$f_ids = explode(",", $mcat['cat_under_ids']);
		$top_sub_folder = $f_ids[0];
		if(empty($top_sub_folder)) {
			$top_sub_folder = $mcat['cat_id'];
		}
	}
	if(is_array($f_ids)) {
		array_push($f_ids, $_REQUEST['category']);
	}
	$menu_cats= whileSQL("ms_calendar_cats", "*", "WHERE cat_under='0' ORDER BY cat_order,cat_name ASC");
	while($menu_cat = mysqli_fetch_array($menu_cats)) {
		?>
		<div class="rowhover" id="menu_cat-<?php print $menu_cat['cat_id'];?>">
		<div class="left">		
		<?php  if($_REQUEST['category'] == $menu_cat['cat_id']) { ?>
		<a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['store_folder'];?>/<?php print $menu_cat['cat_folder'];?>/"><B><?php  print "".$menu_cat['cat_name'].""; ?></B></a>
		<?php 
		} else { ?>
		<a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['store_folder'];?>/<?php print $menu_cat['cat_folder'];?>/"><?php  print "".$menu_cat['cat_name'].""; ?></a>
		<?php 
		}
		$tcat = countIt("ms_calendar_cats_connect", "WHERE con_cat='".$menu_cat['cat_id']."' "); 
		$tprods = countIt("ms_calendar", "WHERE prod_cat='".$menu_cat['cat_id']."' ") + $tcat;
			print " (".$tprods.")";
			$subcats = countIt("ms_calendar_cats", "WHERE cat_under='".$menu_cat['cat_id']."' ");
			if($subcats > 0) {
				print " + ";
			}
			?>
		<?php 
			if($menu_cat['cat_status'] == "0") {
				print  "<span class=\"muted\"><i>Inactive</i></span> ";
			}

		?>

		</div>
		<div class="clear"></div>

		</div>
		<?php 
		if((!empty($top_sub_folder)) AND ($top_sub_folder == $menu_cat['cat_id'])  ==true) {
			$level++;
			storeCatAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level);
		unset($level);
			}
		}
	}



function storeCatAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level) {
	global $setup;

	$dashes = $level * 2;
	$sub_menu_cats = whileSQL("ms_calendar_cats", "*", "WHERE cat_under='$top_sub_folder'  ORDER BY cat_order,cat_name ASC");
	while($sub_menu_cat = mysqli_fetch_array($sub_menu_cats)) {
		?>
		<div class="rowhover" id="menu_cat-<?php print $sub_menu_cat['cat_id'];?>">
		<div class="left">		
		<?php 
		while($dx < $dashes) { print "&nbsp;&nbsp;"; $dx++; }
		$dx = 0;
		$sp = 0;
?>
		<?php  if($_REQUEST['category'] == $sub_menu_cat['cat_id']) { ?>
			<a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['store_folder'];?>/<?php print $sub_menu_cat['cat_folder'];?>/"><B><?php  print "".$sub_menu_cat['cat_name'].""; ?></B></a>
			<?php 
		} else { ?>
			<a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['store_folder'];?>/<?php print $sub_menu_cat['cat_folder'];?>/"><?php  print "".$sub_menu_cat['cat_name'].""; ?></a>
	<?php 		}

		$tcat = countIt("ms_calendar_cats_connect", "WHERE con_cat='".$sub_menu_cat['cat_id']."' "); 
		$tprods = countIt("ms_calendar", "WHERE prod_cat='".$sub_menu_cat['cat_id']."' ") + $tcat;
			print " (".$tprods.")";
			$subcats = countIt("ms_calendar_cats", "WHERE cat_under='".$sub_menu_cat['cat_id']."' ");
			if($subcats > 0) {
				print " + ";
			}
			?>
		<?php 
			if($sub_menu_cat['cat_status'] == "0") {
				print " <span class=\"muted\"><i>Inactive</i></span> ";
			}

		?>

		</div>
		<div class="clear"></div>

		</div>
		<?php 
		if ((in_array($sub_menu_cat['cat_id'], $f_ids))==true) { 
			$top_sub_folder = $sub_menu_cat['cat_id'];
			$level++;

			storeCatAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level);
		}
		//unset($level);
	}
}

function getOrderItems($order) { 
	global $setup, $site_setup,$store,$show_prices;
	$show_prices = true;
	$no_trim = true;
	$full_url = true;
	
	if($order['order_archive_table'] == "1") { 
		define(cart_table,"ms_cart_archive");
	} else { 
		define(cart_table,"ms_cart");
	}

	if(!empty($order['order_notes'])) { 
		$html .='<table width="100%" cellpadding="4" cellspacing="1"><tr><td>'._customer_notes_.': <i>'.nl2br($order['order_notes']).'</i></td></tr></table>';
	}

	
	 if(!empty($order['order_extra_field_1'])) {
		$html .='<table width="100%" cellpadding="4" cellspacing="1" id="products" style="background-color: #dddddd;"><tr><td style="background: #FFFFFF; padding: 8px; width:50%;">'.$order['order_extra_field_1'].'</td><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_val_1'].'</td></tr>
		
		';
			 
		if(!empty($order['order_extra_field_2'])) { 
			$html.='<tr><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_field_2'].'</td><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_val_2'].'</td></tr>
			
			';
		}
		if(!empty($order['order_extra_field_3'])) { 
			$html.='<tr><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_field_3'].'</td><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_val_3'].'</td></tr>
			
			';
		}
		if(!empty($order['order_extra_field_4'])) { 
			$html.='<tr><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_field_4'].'</td><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_val_4'].'</td></tr>
			
			';
		}
		if(!empty($order['order_extra_field_5'])) { 
			$html.='<tr><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_field_5'].'</td><td style="background: #FFFFFF; padding: 8px;">'.$order['order_extra_val_5'].'</td></tr>
			
			';
		}
		$html .='</table>';
		$html .='<br><br>';
	 }
	

	$html .='<table width="100%" cellpadding="4" cellspacing="1" id="products" style="background-color: #dddddd;"><tr><td class="top" style="background: #EEEEEE; padding: 8px; font-weight: bold;">'._product_.'</td>';

	if($show_prices == true) { $html .='<td class="top" style="background: #EEEEEE; padding: 8px; font-weight: bold;">'._price_.'</td>';}
		$html .='<td class="top center" style="background: #EEEEEE; padding: 8px; font-weight: bold; text-align: center;">'._qty_.'</td>';
		if($show_prices == true) { $html .='<td class="top textright" style="background: #EEEEEE; padding: 8px; font-weight: bold; text-align: right;">'._extended_.'</td>'; } 
	$html .='</tr>
	
	';

	// PACKAGES 
		$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."'AND cart_package!='0'  AND cart_coupon='0' ORDER BY cart_package_no_select DESC, cart_id ASC" );
		while($cart= mysqli_fetch_array($carts)) {
			$this_price = $cart['cart_price'];
			$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");

			$html .='<tr><td style="background: #FFFFFF; padding: 8px;">';
			$html .= '<div style="padding: 8px;"><b>'.$cart['cart_product_name'].'</b></div>';
			$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ORDER BY co_id ASC ");
			while($co = mysqli_fetch_array($cos)) { 
				$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '.$co['co_select_name']; 
				
					if($co['co_price'] > 0) { 
						$html .= " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						$html .= " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					
					$this_price = $this_price + $co['co_price']; 
				$html .= '</div>';
			}

			 if($cart['cart_package_buy_all'] == "1") { 
/*			$html .= '<div style="padding: 8px;">';

			$date = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
			$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
			$html .= $date['date_title'];
 
			if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							$html .=' > '.$upsub['sub_name'].'  ';
						}
					}
					
					$html .= " > ".$sub['sub_name'];
			}
			 $html .=' '.countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ").' '._photos_word_photos_; 

			$html .='</div>';
			*/
			} 




			$html .='<div style="clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px;"></div>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">'; if($this_price > 0) { $html.=showPrice($this_price); } $html .='</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'; if($this_price > 0) { $html .=showPrice($cart['cart_qty'] * $this_price); } $html .='</td>';
		 } 
	$html .='</tr>
	
	';

	// PACKAGE  PHOTOS 
		$package_name = $cart['cart_product_name'];
		$pcarts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'    ORDER BY cart_pic_org ASC" );
		while($cart= mysqli_fetch_array($pcarts)) {
			if($cart['cart_pic_id'] > 0) { 

				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
				$pic['full_url'] = true;
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
				$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
				$this_price = $cart['cart_price'];

				$html .='<tr><td style="background: #FFFFFF; padding: 8px;">';
				
				$html .='<div style="float: left; margin-right: 8px;">';
				if(!empty($cart['cart_thumb'])) { 
					$html .='<div><img src="'.$setup['url']."/".$setup['temp_url_folder']."/".$cart['cart_thumb'].'"></div>';
				} else { 
					$html .='<div><img src="'; if($cart['cart_color_id'] > 0) { $html .=''.$setup['url'].$setup['temp_url_folder'].'/sy-photo.php?thephoto='.$pic['pic_key'].'|'.MD5("pic_th").'|'.MD5($date['date_cat']).'|'.$cart['cart_color_id']; } else {  $html .=''.getimagefile($pic,'pic_th'); } $html .='"></div>';
				}
				$html .= '</div>';
				$html .='<div style="float: left;">';
				$html .='<div style="padding: 8px;"><div>'.$package_name.'</div><div><b>'. $cart['cart_product_name'].'</b></div></div>';
				$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."'  ORDER BY co_id ASC");
				while($co = mysqli_fetch_array($cos)) { 
					$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '.$co['co_select_name']; 
					if($co['co_price'] > 0) { 
						$html .= " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						$html .= " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					
							$this_price = $this_price + $co['co_price']; 
					$html .= '</div>';
				}
				if($cart['cart_color_id'] > 0) { 
					$html .='<div style="padding: 8px;">'.$cart['cart_color_name'].'</div>';
				}

				$html .='<div style="padding: 8px;"><b>'.$pic['pic_org'].'</b></div>';

				$html .='<div style="padding: 8px;">'. _in_.' '.$date['date_title'].'';
				if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							$html .=' > '.$upsub['sub_name'].'  ';
						}
					}
					
					$html .= " > ".$sub['sub_name'];
				}
				
				$html .='</div>';
				 if(!empty($cart['cart_notes'])) {
					$html .='<div>&nbsp;</div>';
					$html .='<div style="padding: 8px;"><u>'.nl2br($cart['cart_notes']).'</u></div>';
				} 
	
				if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { 
					$html .='<div  style="padding: 8px;">'._email_custom_crop_message_.'</div>';
				}

				$html .='</div>';
				$html .='<div style="clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px;"></div>';
			$html .='</td>';


			if($show_prices == true) { 
				$html .='<td style="background: #FFFFFF; padding: 8px;">';
				if($this_price > 0) { 
					$html .=''.showPrice($this_price).'&nbsp;';
				} else { 
					$html .= '&nbsp;';
				}
				$html .= '</td>';
			} 
			$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
			if($show_prices == true) {
				$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">';
				if($this_price > 0) { 
					$html .=''.showPrice($cart['cart_qty'] * $this_price).'&nbsp;';
				} else { 
					$html .= '&nbsp;';
				}
				$html .='</td>';
			 } 
	$html .='</tr>
	
	';
	}
	unset($this_price);
	}



	unset($this_price);
	}



	// PHOTOS 
	$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_photo_prod!='0'  AND cart_package_photo='0' AND cart_coupon='0' ORDER BY cart_pic_org ASC " );
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
				$pic['full_url'] = true;

			$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
			$this_price = $cart['cart_price'];
			$html .='<tr><td style="background: #FFFFFF; padding: 8px;">';
			$html .='<div style="float: left; margin-right: 8px;">';
			if(!empty($cart['cart_thumb'])) { 
				$html .='<div><img src="'.$setup['url']."/".$setup['temp_url_folder']."/".$cart['cart_thumb'].'"></div>';
			} else { 

				$html .='<div><img src="'; if($cart['cart_color_id'] > 0) { $html .=''.$setup['url'].$setup['temp_url_folder'].'/sy-photo.php?thephoto='.$pic['pic_key'].'|'.MD5("pic_th").'|'.MD5($date['date_cat']).'|'.$cart['cart_color_id']; } else {  $html .= getimagefile($pic,'pic_th'); } $html .='"></div>';
			}
			$html .='</div>';
			$html .='<div style="float: left;">';




			if($cart['cart_frame_size'] > 0) { 
				$wset = doSQL("ms_wall_language", "*","");
				$wall_settings = doSQL("ms_wall_settings","*","");
				$frame = doSQL("ms_frame_sizes LEFT JOIN ms_frame_styles ON ms_frame_sizes.frame_style=ms_frame_styles.style_id", "*", "WHERE frame_id='".$cart['cart_frame_size']."' ");
				$color = doSQL("ms_frame_images", "*", "WHERE img_id='".$cart['cart_frame_image']."' ");
				$html .='<div style="padding: 8px;">'.  ($frame['frame_width'] * 1).' x '.($frame['frame_height'] * 1).' '.$frame['style_name'].' '.$color['img_color'].'</div>';

				if($cart['cart_mat_size'] > 0) { 
						$html .='<div style="padding: 8px;">'. $wset['_wd_matting_'].' '.($cart['cart_mat_size'] * 1).$wall_settings['size_symbol'].' ';
						$matcolor = doSQL("ms_frame_mat_colors", "*", "WHERE color_color='".$cart['cart_mat_color']."' ");
						$html .= ' '.$matcolor['color_name'];
						
						$html .='</div>';
						$html .='<div style="padding: 8px;">'.  ($frame['frame_mat_print_width'] * 1).' x '.($frame['frame_mat_print_height'] * 1).' '.$wset['_wd_print_'].'</div>';
				}
			} else {	

				$html .='<div style="padding: 8px;">'. $cart['cart_product_name'].'</div>';
			}
			 $cos = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."' ORDER BY co_id ASC  ");
			while($co = mysqli_fetch_array($cos)) {
				$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '._selected_.'</div>'; 
			}


			$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC");
			while($co = mysqli_fetch_array($cos)) { 
				$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '.$co['co_select_name']; 
					if($co['co_price'] > 0) { 
						$html .= " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						$html .= " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					

					if($co['co_download'] <=0) { 
						$this_price = $this_price + $co['co_price']; 
					}
					if($co['co_download'] == "1") { 
						$co_download = $co['co_price'];	
					}
				$html .= '</div>';
			}
			if($cart['cart_color_id'] > 0) { 
				$html .='<div style="padding: 8px;">'.$cart['cart_color_name'].'</div>';
			}
			$html .='<div style="padding: 8px;"><b>'.$pic['pic_org'].'</b></div>';
			$html .='<div style="padding: 8px;">'. _in_.' '.$date['date_title'].'';
			if(!empty($cart['cart_sub_gal_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
				$ids = explode(",",$sub['sub_under_ids']);
				foreach($ids AS $val) { 
					if($val > 0) { 
						$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
						$html .=' > '.$upsub['sub_name'].'  ';
					}
				}
				
				$html .= " > ".$sub['sub_name'];
			}
					
			
			$html .='</div>';

			 if(!empty($cart['cart_notes'])) {
				$html .='<div>&nbsp;</div>';
				$html .='<div style="padding: 8px;"><u>'.nl2br($cart['cart_notes']).'</u></div>';
			} 
			if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { 
				$html .='<div>&nbsp;</div>';
				$html .='<div style="padding: 8px;">'._email_custom_crop_message_.'</div>';
			}

			$html .='</div>';




			$html .='<div style="clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px;"></div>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">'.showPrice($this_price).'</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'.showPrice(($cart['cart_qty'] * $this_price) + $co_download).'</td>';
		 } 
		 $co_download = "";
	$html .='</tr>
	
	';
	unset($this_price);
	}


	// STORE PRODUCTS 

	$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_store_product!='0' AND cart_coupon='0' ORDER BY cart_id DESC" );
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
			$this_price = $cart['cart_price'];

		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
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

		$html .='<tr><td style="background: #FFFFFF; padding: 8px;">';
		


			$html .='<div style="float: left; margin-right: 8px;">';

			if($sub['sub_pic_id'] > 0) { 
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
				if($pic['pic_id'] > 0) { 
				$html .='<img src="'.getimagefile($pic,'pic_mini').'">';
				}
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					$pic['full_url'] = true;
					$html .='<img src="'.getimagefile($pic,'pic_mini').'">';
				}
			} 

			$html .='</div>';


		if($cart['cart_account_credit_for'] > 0) { $html .= _registry_purchase_.'<br>'; }
		 if($cart['cart_paid_access'] > 0) { $html .= _access_to_.'<br>'; } 
		$html .='<b>'.$cart['cart_product_name'].'</b>';

		 if($cart['cart_paid_access'] > 0) { $html .= '<br><a href="'.$setup['url'].$setup['temp_url_folder'].$date['cat_folder'].'/'.$date['date_link'].'/">View This Page</a>'; } 

			if(!empty($sku)) { 
				if($sku !== "sytistinstall") {
					$html .='<div style="padding: 8px;">#'.$sku.'</div>';
				}
			} 
			if(!empty($cart['cart_reg_key'])) { 
				$html .='<div style="padding: 8px;">Registration key:  &nbsp; '.$cart['cart_reg_key'].'</div>';
			} 
			if(!empty($cart['cart_order_message'])) { 
				$html .='<div style="padding: 8px;">';
				$html .=$cart['cart_order_message'];
				$html .='</div>';
			} 


			unset($sku);

			$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC");
			while($co = mysqli_fetch_array($cos)) { 
				$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '.$co['co_select_name']; 
					if($co['co_price'] > 0) { 
						$html .= " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						$html .= " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					
					$this_price = $this_price + $co['co_price']; 
				$html .= '</div>';
			}

			$html .='<div>';
			if(!empty($date['prod_opt1'])) { 
				$html .='<div style="padding: 8px;">'.$date['prod_opt1'].': '.$sub['opt1_value'].'</div>';
			}
			if(!empty($date['prod_opt2'])) { 
				$html .='<div style="padding: 8px;">'.$date['prod_opt2'].': '.$sub['opt2_value'].'</div>';
			}

			if(!empty($date['prod_opt3'])) { 
				$html .='<div style="padding: 8px;">'.$date['prod_opt3'].': '.$sub['op3_value'].'</div>';
			}

			if(!empty($date['prod_opt4'])) { 
				$html .='<div style="padding: 8px;">'.$date['prod_opt4'].': '.$sub['opt4_value'].'</div>';
			}

			if(!empty($date['prod_opt5'])) { 
				$html .='<div style="padding: 8px;">'.$date['prod_opt5'].': '.$sub['opt5_value'].'</div>';
			}
			$html .='</div>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">'.showPrice($this_price).'</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'.showPrice($cart['cart_qty'] * $this_price).'</td>';
		 } 
	$html .='</tr>
	
	';

	// STORE  PHOTOS 
		$pcarts = whileSQL(cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND   cart_product_photo='".$cart['cart_id']."'    ORDER BY cart_pic_org ASC" );
		while($cart= mysqli_fetch_array($pcarts)) {
			if($cart['cart_pic_id'] > 0) { 

				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
				$pic['full_url'] = true;

				$prcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");
				$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$prcart['cart_store_product']."' ");
				$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
				$this_price = $cart['cart_price'];
				$html .='<tr><td style="background: #FFFFFF; padding: 8px;">';
				$html .='<div style="float: left; margin-right: 8px;"><img src="'; if($cart['cart_color_id'] > 0) { $html .=''.$setup['url'].$setup['temp_url_folder'].'/sy-photo.php?thephoto='.$pic['pic_key'].'|'.MD5("pic_th").'|'.MD5($date['date_cat']).'|'.$cart['cart_color_id']; } else {  $html .=''.getimagefile($pic,'pic_th'); } $html .='"></div>';
				$html .='<div style="float: left;">';

				$html .='<div style="padding: 8px;"><div>'.$pdate['date_title'].'</div><div><b>'. $cart['cart_product_name'].'</b></div></div>';
				$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) { 
					$html .= '<div style="padding: 8px;">'.$co['co_opt_name'].': '.$co['co_select_name']; 
					if($co['co_price'] > 0) { 
						$html .= " "._option_add_price_."".showPrice($co['co_price']); 
					}  
					if($co['co_price'] < 0) { 
						$html .= " "._option_negative_price_."".showPrice(-$co['co_price']); 
					}					
					$this_price = $this_price + $co['co_price']; 
					$html .= '</div>';
				}
				if($cart['cart_color_id'] > 0) { 
					$html .='<div style="padding: 8px;">'.$cart['cart_color_name'].'</div>';
				}

				$html .='<div style="padding: 8px;"><b>'.$pic['pic_org'].'</b></div>';

				$html .='<div style="padding: 8px;">'. _in_.' <a href="'.$setup['url'].''.$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder'].'/'.$date['date_link'].'">'.$date['date_title'].'</div>';
				$html .='</div>';
				$html .='<div style="clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px;"></div>';
			$html .='</td>';
			if($show_prices == true) { 
				$html .='<td style="background: #FFFFFF; padding: 8px;">';
				if($this_price > 0) { 
					$html .=''.showPrice($this_price).'';
				} else { 
					$html .= '&nbsp;';
				}
				$html .= '</td>';
			} 
			$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
			if($show_prices == true) {
				$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">';
				if($this_price > 0) { 
					$html .=''.showPrice($cart['cart_qty'] * $this_price).'';
				} else { 
					$html .= '&nbsp;';
				}
				$html .='</td>';
			 } 
	$html .='</tr>
	
	';
	} // END STORE PHOTOS

}


	}






	// INVOICE ITEMS 

	$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_invoice='1' AND cart_coupon='0' ORDER BY cart_id DESC" );
	$tracks_total	= mysqli_num_rows($carts);
		while($cart= mysqli_fetch_array($carts)) {
		$html .='<tr><td style="background: #FFFFFF; padding: 8px;"><b>'.$cart['cart_product_name'].'</b>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">'.showPrice($cart['cart_price']).'</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'.showPrice($cart['cart_qty'] * $cart['cart_price']).'</td>';
		 } 
	$html .='</tr>
	
	';
	}


	// GIFT CERTIFICATES 

	$carts = whileSQL(cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_gift_certificate='1'  ORDER BY cart_id DESC" );
		while($cart= mysqli_fetch_array($carts)) {
		$html .='<tr><td style="background: #FFFFFF; padding: 8px;"><b>'.$cart['cart_product_name'].'</b>';
		$html .='<br>'._gift_certificate_to_.': '.$cart['cart_gift_certificate_to_name'].' ('.$cart['cart_gift_certificate_to_email'].')';
		$html .='<br>'._gift_certificate_from_.': '.$cart['cart_gift_certificate_from_name'].' ('.$cart['cart_gift_certificate_from_email'].')';
		$html .='<br><i>'.nl2br($cart['cart_gift_certificate_message']).'</i>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">'.showPrice($cart['cart_price']).'</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['cart_qty'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'.showPrice($cart['cart_qty'] * $cart['cart_price']).'</td>';
		 } 
	$html .='</tr>
	
	';
	}


	$carts = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name  ORDER BY co_id ASC");
	while($cart= mysqli_fetch_array($carts)) {
		$html .='<tr><td style="background: #FFFFFF; padding: 8px;"><b>'.$cart['co_opt_name'].'</b>';
		$html .='</td>';
		if($show_prices == true) { 
			$html .='<td style="background: #FFFFFF; padding: 8px;">&nbsp;</td>';
		} 
		$html .='<td class="center" style="background: #FFFFFF; padding: 8px; text-align: center;">'.($cart['total_items'] + 0).'</td>';
		if($show_prices == true) {
			$html .='<td class="textright" style="background: #FFFFFF; padding: 8px; text-align:right;">'.showPrice($cart['this_price']).'</td>';
		 } 
	$html .='</tr>
	
	';
	}






	$html .='</table>';

	 if($show_prices == true) {
		$html .='<table id="totals" style="float: right;  margin-top: 12px;">';
			$html .='<tr><td style="padding:4px;">'._subtotal_.'</td>';
				$html .='<td style="text-align: right; padding:4px;">'.showPrice($order['order_sub_total']).'</td>';
				$html .='</tr>
				
				';
			if($order['order_eb_discount'] > 0) { 
			$html .='<tr><td style="padding:4px;">'._early_bird_special_.'</td>';
				$html .='<td style="text-align: right; padding:4px;">('.showPrice($order['order_eb_discount']).')</td>';
				$html .='</tr>
				
				';
			} 
			if($order['order_discount'] > 0) { 
			$html .='<tr><td style="padding:4px;">'._discount_.' ('.$order['order_coupon_name'].')</td>';
				$html .='<td style="text-align: right; padding:4px;">('.showPrice($order['order_discount']).')</td>';
				$html .='</tr>
				
				';
			} 
			if($order['order_tax'] > 0) {
			$html .='<tr valign="top"><td style="padding:4px;">'._tax_.'<br><span style="font-color: #949494;">'.$order['order_tax_percentage'].'% on '.showPrice($order['order_taxable_amount']).'</span>';
				$html .='</td><td style="text-align: right;padding:4px;">'.showPrice($order['order_tax']).'</td></tr>
				
				';
			} 
			if($order['order_vat'] > 0) {
			$html .='<tr valign="top"><td style="padding:4px;">'._vat_.'<br><span style="font-color: #949494;">'.$order['order_vat_percentage'].'% on '.showPrice($order['order_taxable_amount']).'</span>';
				$html .='</td><td style="text-align: right;padding:4px;">'.showPrice($order['order_vat']).'</td></tr>
				
				
				';
			} 

			if(!empty($order['order_shipping_option'])) { 
				$html .='<tr><td style="padding:4px;">'._shipping_.' ('.$order['order_shipping_option'].')</td><td style="text-align: right;padding:4px;">'.showPrice($order['order_shipping']).'</td></tr>
				
				';
			} 

			if($order['order_credit'] > 0) { 
				$html .='<tr><td style="padding:4px;">'._account_credit_.'</td><td style="text-align: right;padding:4px;">('.showPrice($order['order_credit']).')</td></tr>
				
				';
			} 
			if($order['order_gift_certificate'] > 0) { 
				$html .='<tr><td style="padding:4px;">'._gift_certificate_name_.'</td><td style="text-align: right;padding:4px;">('.showPrice($order['order_gift_certificate']).')</td></tr>
				
				';
			} 


			$html .='<tr><td style="padding:4px;">'._grand_total_.'</td><td style="text-align: right; padding:4px; ">'.showPrice($order['order_total']).'</td></tr>
			
			';
		$html .='</table>';
		$html .='<div style="clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px;"></div>';
	 }
	return $html;
}

function showStoreProduct($date,$cart) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	if($cart['cart_account_credit_for'] > 0) { 
		$this_price = $cart['cart_price'];
	} else { 

		$price = productPriceCart($date);
		$this_price = $price['price'];
		if(!empty($cart['cart_sub_id'])) {
			$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
			$this_price = $this_price + $sub['sub_add_price'];
		}
	}
	?>
	<div class="cartitem" id="cart-<?php print MD5($cart['cart_id']);?>">
		<?php if($sub['sub_pic_id'] > 0) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
			if($pic['pic_id'] > 0) { ?>
				<div class="thumbnail nofloatsmall"><img src="<?php print getimagefile($pic,'pic_th');?>">	</div>
		<?php 
			}
		
		} else { ?>


			<div class="thumbnail nofloatsmall"><?php print getPagePreview($date,"thumb"); ?></div>

			<?php } ?>

			<div class="product nofloatsmall">
			<?php if($date['prod_no_link'] == "1") { ?>
				<div class="name"><?php print "".$date['date_title']."";?></div>
			<?php } else { ?>
				<div class="name"><?php if($cart['cart_booking'] > 0) { 
				$book = doSQL("ms_bookings", "*,date_format(DATE_ADD(book_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ')  AS book_date, time_format(book_time, '%l:%i %p')  AS book_time", "WHERE book_id='".$cart['cart_booking']."' ");
				 } ?><?php print "<a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">"; if(!empty($cart['cart_product_name'])) { print $cart['cart_product_name']; } else { print $date['date_title']; } print "</a>";?></div>
				<?php } ?>
			<?php if(($cart['cart_account_credit'] > 0) && ($cart['cart_account_credit_for'] <=0)==true) { ?>
			<div class="pc"><?php print _includes_." ".showPrice($cart['cart_account_credit'])." "._credit_;?></div>
			<?php } ?>
			<?php if($cart['cart_account_credit_for'] > 0) { ?>
			<div class="pc"><?php if(!empty($cart['cart_reg_message_name'])) { print "<b>".$cart['cart_reg_message_name']."</b><br>"; } 
			if(!empty($cart['cart_reg_message'])) { print "<i>".nl2br($cart['cart_reg_message'])."</i>"; } ?>
			</div>
			<?php } ?>
			<?php if($book['book_id'] > 0) { ?>
				<div class="pc"><?php print $book['book_date']; if($book['book_all_day'] !== "1") { print $book['book_time']; } ?></div>

			<?php } ?>
				
				<div class="options">
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


				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) {
				$option_price = $co['co_price'];
				if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
					 $option_price =  $option_price+ (($option_price * $site_setup['include_vat_rate']) / 100);
				}
					
					?>
				<div class="options">
				<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
					if($co['co_price'] > 0) { print " "._option_add_price_."".showPrice($option_price); }  if($co['co_price'] < 0) { print " "._option_negative_price_."".showPrice(-$option_price); }					
					$this_price = $this_price + $co['co_price']; 
				 ?>
				</div>
			<?php } ?>

			<div class="qty">

		<div id="cartqty">
		<?php if(($noactions !== true)&&($cart['cart_paid_access'] !== "1")&&($date['prod_max_one'] !== "1") && ($book['book_id'] <=0)==true) { ?>
		<form name="cartqty" action="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php" method="POST">
		<input type="hidden" name="cid" value="<?php print MD5($cart['cart_id']);?>">
		<input type="hidden" name="action" value="updateqty">

		<?php print _qty_;?>: 
		<?php if($date['prod_inventory_control'] == "1") { ?>
		<?php if($_SESSION['outofstock'] == $cart['cart_id']) { ?>Out of stock<?php } else { ?>

		<select name="prod_qty" id="prod_qty" class="cartoption center addtocartqty"  onchange="this.form.submit()">
		<?php if($sub['sub_id'] > 0) { 
			
			if(($sub['sub_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
				$_SESSION['outofstock'] = $cart['cart_id'];
				header("location: /index.php?view=cart");	
				session_write_close();
				exit();
			}
			?>
		<?php 
			$x = 1;
			while($x <= $sub['sub_qty']) { ?>	
			<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
			<?php $x++;
		}
		?>

		<?php } else { ?>
		<?php 
			if(($date['prod_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
				$_SESSION['outofstock'] = $cart['cart_id'];
				header("location: /index.php?view=cart");	
				session_write_close();
				exit();
			}

			$x = 1;
			while($x <= $date['prod_qty']) { ?>	
			<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
			<?php $x++;
		}
		?>


		<?php } ?>
		</select>
		<?php } ?>
		<?php } else { ?>
		<input type="text"  name="prod_qty" id="prod_qty" class="cartoption center addtocartqty" size="2" value="<?php print ($cart['cart_qty'] + 0);?>"  onchange="this.form.submit()">
		<?php } ?>
		</form> 
		<?php } else { ?>
		<?php if(empty($book['book_id'])) { ?>
		<?php print _qty_;?>:  <?php print ($cart['cart_qty'] + 0);?>
		<?php } ?>
		<?php } ?>
		</div>
		



		</div>
		<?php if($noactions !== true) { ?>
		<?php if($cart['cart_product_select_photos'] > 0) { ?>
		<a href="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?cid=<?php print MD5($cart['cart_id']);?>&dcd=<?php print MD5(date('Ymdhis'));?>&co=<?php print MD5("SD SDKSJD");?>&action=removefromcart"><?php print _remove_from_cart_;?></a>
		<?php } else { ?>
		<div class="remove"><a href="" onClick="removeFromCart('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _remove_from_cart_;?></a></div>
		<?php } ?>
		<?php } ?>
		</div>
		<?php $show_price = $this_price;

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
	<?php 	// updateSQL("ms_cart", "cart_price='".$this_price."' WHERE cart_id='".$cart['cart_id']."' "); ?>	
<?php } 




function showPhotoPackage($pack,$cart) {

	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['cart_price'];
	$this_price = $cart['cart_price'];
	$prodary = explode('>', $cart['cart_product_name']);
	$name = $prodary[1];

	?>
	<div class="cartitem" id="cart-<?php print MD5($cart['cart_id']);?>">

		<div class="thumbnail  nofloatsmall"><?php //print getPagePreview($date,"thumb"); ?></div>
		<div class="product  nofloatsmall">
			<div class="name"><?php if(!empty($cart['cart_print_credit'])) { 
				if($cart['cart_bonus_coupon'] > 0) { 
					print _bonus_coupon_.": ";
				} else { 
					print _print_credit_.": "; 
				}
			} ?>
			<?php if($cart['cart_package_include'] > 0) { 
			$mainpack = doSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE cart_id='".$cart['cart_package_include']."' ");
			print $mainpack['package_name']." > "; 
			}
			//print $name;
			print (substr($cart['cart_product_name'], 11));?></div>

			<?php if($cart['cart_pre_reg'] > 0) { ?>
			<div class="pc">
			<?php 
			$pdate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pre_reg']."' ");
			if($pdate['date_public'] =="1") { 
				 print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$pdate['cat_folder']."/".$pdate['date_link']."/\">".$pdate['date_title']."</a>";
			} else { 
				print $pdate['date_title'];
			}
			?>
			</div>
			<?php } ?>

				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) {
				$option_price = $co['co_price'];
				if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
					 $option_price =  $option_price+ (($option_price * $site_setup['include_vat_rate']) / 100);
				}
					
					?>
				<div class="options">
				<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
					if($co['co_price'] !== "0.00") { 
						 $this_price = $this_price + $co['co_price']; 
						if($option_price > 0) { print " "._option_add_price_."".showPrice($option_price); }  if($option_price < 0) { print " "._option_negative_price_."".showPrice(-$option_price); }					
						} ?>
				</div>
			<?php } ?>

				<div class="qty">

			<div id="cartqty">
			<?php if($cart['cart_no_delete'] !== "1") { ?>
				<?php if(($package_photo <= 0) && (empty($cart['cart_print_credit'])) == true) { ?>
				<form name="cartqty" action="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php" method="POST">
				<input type="hidden" name="cid" value="<?php print MD5($cart['cart_id']);?>">
				<input type="hidden" name="action" value="updateqty">

				<?php print _qty_;?>: 
				<?php if($date['prod_inventory_control'] == "1") { ?>
				<?php if($_SESSION['outofstock'] == $cart['cart_id']) { ?>Out of stock<?php } else { ?>

				<select name="prod_qty" id="prod_qty" class="cartoption center addtocartqty"  onchange="this.form.submit()">
				<?php if($sub['sub_id'] > 0) { 
					
					if(($sub['sub_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
						$_SESSION['outofstock'] = $cart['cart_id'];
						header("location: /index.php?view=cart");	
						session_write_close();
						exit();
					}
					?>
				<?php 
					$x = 1;
					while($x <= $sub['sub_qty']) { ?>	
					<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
					<?php $x++;
				}
				?>

				<?php } else { ?>
				<?php 
					if(($date['prod_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
						$_SESSION['outofstock'] = $cart['cart_id'];
						header("location: /index.php?view=cart");	
						session_write_close();
						exit();
					}

					$x = 1;
					while($x <= $date['prod_qty']) { ?>	
					<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
					<?php $x++;
				}
				?>


				<?php } ?>
				</select>
				<?php } ?>
				<?php } else { ?>
				<input type="text"  name="prod_qty" id="prod_qty" class="cartoption center addtocartqty" size="2" value="<?php print ($cart['cart_qty'] + 0);?>"  onchange="this.form.submit()">
				<?php } ?>
				</form> 
				<?php } else { 
				
					if(empty($cart['cart_print_credit'])) {
						print _qty_;?>:  <?php print ($cart['cart_qty'] + 0);
					}
				
				} ?>
				<?php } ?>
				</div>
				</div>
			<?php if($cart['cart_credit'] > 0) { ?>
			<div class="pc"><?php print showPrice($cart['cart_credit'])." "._collection_credit_cart_message_;?></div>
			<?php } ?>

			<?php if($cart['cart_no_delete'] !== "1") { ?>
			<?php if($noactions !== true) { ?>
			<?php
				if($cart['cart_group_id'] > 0) { 
					$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$cart['cart_group_id']."' ");

					if($group['group_require_purchase'] > 0) { 
						if(countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_group_id='".$group['group_id']."' AND cart_order<='0' ") == 1) { 
							$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_order<='0' AND cart_group_id!='".$group['group_id']."' AND cart_package_photo!='".$cart['cart_id']."' ORDER BY cart_id DESC" );
							if(mysqli_num_rows($pcarts) > 0) { 
								$stop_remove = true;
								?>
							<div class="remove"><a href="" onclick="showrequireremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _remove_from_cart_;?></a></div>
							<div id="remove-<?php print MD5($cart['cart_id']);?>" class="error" style="display: none;"><?php print _remove_required_package_;?> <a href="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?action=rp&cid=<?php print MD5($cart['cart_id']);?>"><?php print _yes_;?></a> <a href="" onclick="showrequireremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _no_;?></a></div>
							<?php 
							}
						}
					}
				}

				if($cart['cart_package_include'] > 0) { 
					$stop_remove = true;
				}
				?>
			<?php if($stop_remove !== true) { ?>
				<div class="remove "><a href="" onclick="showpackageremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _remove_from_cart_;?></a></div>
				<div id="remove-package-<?php print MD5($cart['cart_id']);?>" class="error" style="display: none;"><?php print _remove_package_confirm_;?> <a href="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?action=rp&cid=<?php print MD5($cart['cart_id']);?>"><?php print _yes_;?></a> <a href="" onclick="showpackageremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _no_;?></a></div>
			<?php } ?>
			<?php } ?>
			<?php } ?>
		<div class="qty">
		<?php if(($cart['cart_package_buy_all'] >= "1") || ($cart['cart_package_no_select'] == "1")==true) { ?>

		
		<?php } else { ?>
		<?php //print countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ")." "._of_." ".countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' ")." "._selected_.""; ?>
		<?php } ?>
	<?php
		if(($cart['cart_bonus_coupon'] > 0) && (empty($_SESSION['promo_success_bonus'])) == true) { 
			$bc_cart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_bonus_coupon']."' ");
			$bc = doSQL("ms_promo_codes", "*", "WHERE code_id='".$bc_cart['cart_coupon']."' ");
			 $code_message = str_replace("[MIN_AMOUNT]",showPrice($bc['code_min']),$bc['code_redeem_instructions']); 
			//print "<div>".nl2br($code_message)."</div>";
		}
		unset($_SESSION['promo_success_bonus']);

		?>
		</div>
		</div>

	<?php 
			if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
		}
	?>
	<?php if($this_price > 0) { ?>
		<div class="price nofloatsmall"><span class="extprice"><?php print showPrice($cart['cart_qty'] * $this_price);?></span>
		<?php if($cart['cart_qty'] > 1) { ?>
		<div id="eachprice" class="pc">
		<?php print showPrice($this_price);?> <?php print _each_;?>
		</div>
		<?php } ?>
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
<?php   //	updateSQL("ms_cart", "cart_price='".$this_price."' WHERE cart_id='".$cart['cart_id']."' "); ?>	
<?php } 


function cropphotoview($cart,$pic,$prod,$pic_file,$disable) { 
	global $setup,$lang;
	$setup['max_crop_thumb_view_size'] = 200;
	$size = getimagefiledems($pic,$pic_file);
	if($cart['cart_photo_prod'] <= 0) { 
		$cart['cart_photo_prod'] = $prod['pp_id'];
	}
	if($size[1] > 250) { 
		$new_crop_height = 250;
		$size[0] = $size[0] * ($new_crop_height / $size[1]);
		$size[1] = $new_crop_height;
		$cart['cart_crop_x2'] = $cart['cart_crop_x2'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_y1'] = $cart['cart_crop_y1'] * ($new_crop_height / $size[1]);

	}
	if($size[0] > 200) { 
		if($setup['max_crop_thumb_view_size'] > 0) { 
			$new_crop_width = $setup['max_crop_thumb_view_size'];
		} else { 
			$new_crop_width = 200;
		}
		$size[1] = $size[1] * ($new_crop_width / $size[0]);
		$size[0] = $new_crop_width;
		$cart['cart_crop_x2'] = $cart['cart_crop_x2'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_y1'] = $cart['cart_crop_y1'] * ($new_crop_width / $size[0]);
	}
	if((($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { 

	$nw = $size[0] * (($cart['cart_crop_x2'] - $cart['cart_crop_x1']) / 100);
	$nh = $size[1] * (($cart['cart_crop_y2'] - $cart['cart_crop_y1']) / 100);
	$left = $size[0] * ($cart['cart_crop_x1'] / 100);
	$top = $size[1] * ($cart['cart_crop_y1'] / 100);
	} else { 
		$width = $prod['pp_width'];
		$height = $prod['pp_height'];

		if($cart['cart_crop_rotate'] == "1") { 
			$dem = getCropDems($size[1],$size[0],$width,$height,.50);
		} else { 
			$dem = getCropDems($size[0],$size[1],$width,$height,.50);
		}

		$x1 = $dem['x1'];
		$y1 = $dem['y1'];
		$x2 = $dem['x2'];
		$y2 = $dem['y2'];
		$nw = $dem['crop_width'];
		$nh = $dem['crop_height'];
		$left =$dem['x1'];
		$top = $dem['y1'];

	}

	?>
	<div>
		<div id="" style="position: relative;  margin: auto; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px;  background: url('<?php if($cart['cart_color_id'] > 0) { print "".$setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5($pic_file)."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,$pic_file); } ?>') center center/<?php print $size[0];?>px <?php print $size[1];?>px no-repeat;">
			<div style="background: #FFFFFF; opacity: .8; position: absolute;  width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; top: 0; left: 0;"></div>

			<div id="" style="position: absolute;  margin: auto; width: <?php print $nw;?>px; height: <?php print $nh;?>px; top: <?php print $top;?>px; left: <?php print $left;?>px; background: url('<?php if($cart['cart_color_id'] > 0) { print "".$setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5($pic_file)."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,$pic_file); } ?>') center center no-repeat; background-size: <?php print $size[0];?>px <?php print $size[1];?>px; background-position: -<?php print $left;?>px -<?php print $top;?>px;"></div>
		</div>
	</div>
<!-- 	<div class="cropselect">
	<?php if($prod['pp_no_crop'] !== "1") { ?>
		<?php if($disable == 1) { ?>
		<a href="" onclick="cropphoto('<?php print $pic['pic_key'];?>','<?php print $cart['cart_photo_prod'];?>', '<?php print $cart['cart_id'];?>','0','0','1'); return false;"><img src="<?php tempFolder(); ?>/sy-graphics/icons/crop.png" border="0" width="16" height="16" align="absmiddle"> <?php print _view_crop_;?></a>
		<?php } else { ?>
		<a href="" onclick="cropphoto('<?php print $pic['pic_key'];?>','<?php print $cart['cart_photo_prod'];?>', '<?php print $cart['cart_id'];?>','0','0','0'); return false;"><img src="<?php tempFolder(); ?>/sy-graphics/icons/crop.png" border="0" width="16" height="16" align="absmiddle"> <?php print _adjust_crop_;?></a>
		<?php } ?>
	<?php } ?>
	</div> -->
<?php } 

function cropphotoview4($cart,$pic,$prod,$pic_file,$disable,$color_id) {

	$search = getSearchOrder();
	$pics_array = array();
	$pic_file = 'pic_pic';

   /*array getting image info*/
	if(!empty($_REQUEST['date_id'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."'  ");
		if(!empty($date['date_id'])) { 
			// if($date['date_public'] !== "1") { die(); } 
			if(($date['private'] > 0)&&(!isset($_SESSION['office_admin_login'])) == true) { 
				if(customerLoggedIn()) { 
					$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
					if(empty($cka['mp_id'])) { 
						die("no access");
						exit();
					} 
				} else { 
					if(!is_array($_SESSION['privateAccess'])) { die("no access"); } 
					if(!in_array($date['date_id'],$_SESSION['privateAccess'])) {
						die("no access");
						exit();
					}
				}
			}
		}
	}

	if((!empty($_REQUEST['date_id'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights"))== true) { 
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
			$and_date_tag .= " OR bp_blog='".$_REQUEST['date_id']."' ";
			$and_date_tag .= " ) ";
			
			## NOT DONE NEW DATABASE FIELDS SELECTION ## 

			$piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." ");
			$cx = 0;
		} else { 

			if($_REQUEST['view'] == "highlights") { 
				$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' AND pic_fav_admin='1' $and_where GROUP BY pic_id ORDER BY bp_order ASC");
			} else { 
				$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC");
			}
		}
	} elseif(!empty($_REQUEST['key_id'])) { 
		$piccount = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."' AND pic_client='".$_REQUEST['pic_client']."' ORDER BY pic_order DESC ");
	} elseif($_REQUEST['view'] ==  "favorites") { 
		$piccount = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE MD5(fav_person)='".$_SESSION['pid']."' AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");

	} else  { 
		$piccount = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where  ORDER BY ".$search['orderby']." ".$search['acdc']." $and_acdc");
	}
	if(!empty($piccount)) { 
		$total_results = mysqli_num_rows($piccount);
	}


	if((!empty($_REQUEST['date_id'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights"))== true) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."'  ");
		if($date['green_screen_backgrounds'] > 0) { 
			$date['thumb_style'] = 2;
		}

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
			$and_date_tag .= " OR bp_blog='".$_REQUEST['date_id']."' ";
			$and_date_tag .= " ) ";
			$pics = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']."");

		} else { 
			if($_REQUEST['view'] == "highlights") { 
				$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND pic_fav_admin='1'  $and_where GROUP BY pic_id ORDER BY pic_org ASC");
			} else { 
				$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC");
			}
		}
		
	 ###############################

	} elseif(!empty($_REQUEST['key_id'])) { 
		$pics = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."'   AND pic_client='".$_REQUEST['pic_client']."'  ORDER BY ".$search['orderby']." ".$search['acdc']."");

	} elseif($_REQUEST['view'] ==  "favorites") { 
		$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");

	} elseif(!empty($_REQUEST['cat_id'])) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
		if(!empty($cat['cat_pic_tags'])) { 
			$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['cat_id']."' "); 
			$date['thumb_style'] = $def['thumb_style'];
			$date['jthumb_height'] = $def['jthumb_height'];
			$date['jthumb_margin'] = $def['jthumb_margin'];
			$cx = 0;
			$and_cat_tag = "AND ( ";
			$cat_tags = explode(",",$cat['cat_pic_tags']);
			foreach($cat_tags AS $tag) { 
				$cx++;
				if($cx > 1) { 
					$and_cat_tag .= " OR ";
				}
				$and_cat_tag .=" key_key_id='$tag' ";
			}
			$and_cat_tag .= " ) ";
		// print "<li>WHERE pic_id>='0' AND pic_client='0'  $and_cat_tag GROUP BY pic_id ORDER BY pic_order DESC LIMIT $sq_page,$per_page";
		$pics = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE pic_id>='0' AND pic_client='0'  $and_cat_tag GROUP BY pic_id ORDER BY pic_order DESC");
		}
	} else {
		$pics = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where ORDER BY ".$search['orderby']." ".$search['acdc']."");
	}

	if(!empty($pics)) { 
		while($pic = mysqli_fetch_array($pics)) {
			if(!empty($pic['pic_folder'])) { 
				$pic_folder = $pic['pic_folder'];
			} else { 
				$pic_folder = $pic['gal_folder'];
			}
			if($pic['pic_amazon'] == "1") { 
				if($pic_file == "pic_th") { 
					$size[0] = $pic['pic_th_width'];
					$size[1] = $pic['pic_th_height'];
				}
				if($pic_file == "pic_pic") { 
					$size[0] = $pic['pic_small_width'];
					$size[1] = $pic['pic_small_height'];
				}
			} else { 
				$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic[$pic_file].""); 
			}

			if($size[0] > $max_width) { $max_width = $size[0]; } 
			if($size[1] > $max_height) { $max_height = $size[1]; } 
			array_push($pics_array,$pic);
		}
	}?>

	<div class="flexslider">
	  <ul class="slides">
	    <?php 
	    	foreach($pics_array as $pic) {
	    		if(($_REQUEST['view'] !== "favorites") && (empty($date['date_photo_keywords'])) == true) { 
					$pic = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic","*","WHERE pic_id='".$pic['pic_id']."' AND bp_blog='".$date['date_id']."' ");
				}
				
				if(!empty($pic['pic_folder'])) { 
					$pic_folder = $pic['pic_folder'];
				} else { 
					$pic_folder = $pic['gal_folder'];
				}

				if($pic['pic_amazon'] == "1") { 
					$size[0] = $pic['pic_small_width'];
					$size[1] = $pic['pic_small_height'];
					$psize[0] = $pic['pic_small_width'];
					$psize[1] = $pic['pic_small_height'];
				} else { 
					$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic[$pic_file].""); 
					$psize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic'].""); 
				}
				if($date['thumb_style'] == "1") { 
					$thumb_class = "styledthumbs";
				} else { 
					$thumb_class = "stackedthumbs";
				}
				if($date['date_owner'] > 0) { 
					if($pic['pic_hide']>0) { $hideclass = "hiddenphoto"; } else { $hideclass = ""; } 
				}
		?>

	    		<li style="position: relative;">
	    				<img src="<?php print getimagefile($pic,$pic_file);?>" id="th-<?php print $pic['pic_key'];?>" style="width: 40%; margin: auto;">
	    		</li>
	  <?php } ?>
	    </ul>
	</div> 


	<script>
		$('.flexslider').flexslider({
	   		aniamtion: "slide",
	   		slideshow: false,
		});	
	</script>

<?php }

function cropphotoviewv2($cart,$pic,$prod,$pic_file,$disable,$color_id) { 
	global $setup,$lang;
	$size = getimagefiledems($pic,$pic_file);
	if($cart['cart_photo_prod'] <= 0) { 
		$cart['cart_photo_prod'] = $prod['pp_id'];
	}
	if($size[1] > 300) { 
		$new_crop_height = 300;
		$size[0] = $size[0] * ($new_crop_height / $size[1]);
		$size[1] = $new_crop_height;
		$cart['cart_crop_x2'] = $cart['cart_crop_x2'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_height / $size[1]);
		$cart['cart_crop_y1'] = $cart['cart_crop_y1'] * ($new_crop_height / $size[1]);

	}
	if($size[0] > 350) { 
		if($setup['max_crop_thumb_view_size'] > 0) { 
			$new_crop_width = $setup['max_crop_thumb_view_size'];
		} else { 
			$new_crop_width = 350;
		}
		$size[1] = $size[1] * ($new_crop_width / $size[0]);
		$size[0] = $new_crop_width;
		$cart['cart_crop_x2'] = $cart['cart_crop_x2'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_x1'] = $cart['cart_crop_x1'] * ($new_crop_width / $size[0]);
		$cart['cart_crop_y1'] = $cart['cart_crop_y1'] * ($new_crop_width / $size[0]);
	}
	if((($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { 

	$nw = $size[0] * (($cart['cart_crop_x2'] - $cart['cart_crop_x1']) / 100);
	$nh = $size[1] * (($cart['cart_crop_y2'] - $cart['cart_crop_y1']) / 100);
	$left = $size[0] * ($cart['cart_crop_x1'] / 100);
	$top = $size[1] * ($cart['cart_crop_y1'] / 100);
	} else { 
		$width = $prod['pp_width'];
		$height = $prod['pp_height'];

		if($cart['cart_crop_rotate'] == "1") { 
			$dem = getCropDems($size[1],$size[0],$width,$height,.50);
		} else { 
			$dem = getCropDems($size[0],$size[1],$width,$height,.50);
		}

		$x1 = $dem['x1'];
		$y1 = $dem['y1'];
		$x2 = $dem['x2'];
		$y2 = $dem['y2'];
		$nw = $dem['crop_width'];
		$nh = $dem['crop_height'];
		$left =$dem['x1'];
		$top = $dem['y1'];

	}

	?>
	<div>
		<div id="addons1" style="position: relative;  margin: auto; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; background: url('<?php if($color_id> 0) { print "".$setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5($pic_file)."|".MD5($date['date_cat'])."|".$color_id; } else {  print getimagefile($pic,$pic_file); } ?>') center center/<?php print $size[0];?>px <?php print $size[1];?>px no-repeat;">
			<div style="background: #FFFFFF; opacity: .8; position: absolute;  width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; top: 0; left: 0;"></div>

			<div id="addons2" style="position: absolute;  margin: auto; width: <?php print $nw;?>px; height: <?php print $nh;?>px; top: <?php print $top;?>px; left: <?php print $left;?>px; background: url('<?php if($color_id > 0) { print "".$setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5($pic_file)."|".MD5($date['date_cat'])."|".$color_id; } else {  print getimagefile($pic,$pic_file); } ?>') center center no-repeat; background-size: <?php print $size[0];?>px <?php print $size[1];?>px; background-position: -<?php print $left;?>px -<?php print $top;?>px;"></div>
		</div>
	</div>

	<!-- <script>
		$(document).ready(function() {
			var count = 0;
			$('.cropphotoview > div').on('click', function() {
				if(count % 2 == 0) {
					$('.cropphotoview').animate({
						opacity: "1" ,
						zoom: '150%' 
					}, 'medium');		
					count++;
				} else {
					$('.cropphotoview').animate({
						opacity: "1" ,
						zoom: '100%' 
					}, 'medium');		
					count++;
				}
			});
		})
	</script> -->
<?php } 
function showPhotoProduct($cart,$package_photo,$parent) {
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
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
	if($cart['cart_package_photo'] > 0) { 
		$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	}
	if($cart['cart_product_photo'] > 0) { 
		$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$pcart['cart_store_product']."' ");
	}

	if($pic['pic_id'] >0) { 

	?>
		<div class="cartitem" id="cart-<?php print MD5($cart['cart_id']);?>">
		<div class="thumbnail nofloatsmall">

		<?php if($pic['pic_id'] > 0) { ?>
			<?php if(($prod['pp_width'] > 0) && ($cart['cart_photo_bg'] <=0) == true) { ?>
				<div id="ct-<?php print $cart['cart_id'];?>">
				<?php cropphotoview($cart,$pic,$prod,"pic_th",'0'); ?>
				</div>
				<?php } else { 
					$size = getimagefiledems($pic,'pic_th');
				if(!empty($cart['cart_thumb'])) { 
					$size = @GetImageSize("".$setup['path']."/".$cart['cart_thumb']); 
					?>
					<img src="<?php print $setup['temp_url_folder'];?>/<?php print $cart['cart_thumb'];?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
						<?php } else { ?>
					<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
					<?php } ?>
			<?php } ?>
		<?php } ?>

		<?php if(($pic['pic_id'] <=0)&&($cart['cart_package_photo'] > 0) ==true) { 
			print _no_photo_selected_;
		}
		?>
		</div>




				<div class="product  nofloatsmall">
					<?php
					if($cart['cart_frame_size'] > 0) { 
					$wset = doSQL("ms_wall_language", "*","");
					$wall_settings = doSQL("ms_wall_settings","*","");
						$frame = doSQL("ms_frame_sizes LEFT JOIN ms_frame_styles ON ms_frame_sizes.frame_style=ms_frame_styles.style_id", "*", "WHERE frame_id='".$cart['cart_frame_size']."' ");
						$color = doSQL("ms_frame_images", "*", "WHERE img_id='".$cart['cart_frame_image']."' ");
						?>
						<div class="name">
						<?php print ($frame['frame_width'] * 1)." x ".($frame['frame_height'] * 1)." ".$frame['style_name'];?>
						</div>
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
					<?php if($cart['cart_package_photo'] > 0) { ?><div class="topname"><?php print $parent['cart_product_name'];?></div><?php } ?>
					<?php if($cart['cart_product_photo'] > 0) { ?><div class="topname"><?php print $pdate['date_title'];?></div><?php } ?>
					<div class="name"><?php print $cart['cart_product_name'];?></div>
					<?php } ?>

					<?php if($cart['cart_room_view'] > 0) { 
					$wset = doSQL("ms_wall_language", "_wd_wall_designer_tab_","");
					$rv  = doSQL("ms_wall_saves", "*", "WHERE wall_id='".$cart['cart_room_view']."' ");
					?>
					<div class="pc"><a href="<?php print $setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/?view=room&rw=<?php print $rv['wall_link'];?>"><?php print $wset['_wd_wall_designer_tab_'];?></a></div>
					<?php } ?>
					<?php $cos = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*", "WHERE ".checkCartSession()."  AND cart_order<='0' AND co_pic_id='".$pic['pic_id']."'  ORDER BY co_id ASC ");
					while($co = mysqli_fetch_array($cos)) {
					$option_price = $co['co_price'];
						?>
					<div class="options">
					<?php print $co['co_opt_name']." "._selected_; ?>
					</div>
					<?php } ?>





					<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC ");
					while($co = mysqli_fetch_array($cos)) {
					$option_price = $co['co_price'];
					if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
						 $option_price =  $option_price+ (($option_price * $site_setup['include_vat_rate']) / 100);
					}
						
						?>
					<div class="options">
					<?php 
						print $co['co_opt_name'].": ".$co['co_select_name']; 
						if($co['co_price'] > 0) { print " "._option_add_price_."".showPrice($option_price); }  if($co['co_price'] < 0) { print " "._option_negative_price_."".showPrice(-$option_price); }					
						
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
					<div class="options">
					<?php print $cart['cart_color_name'];?>
					</div>
				<?php } ?>
				<div class="qty">

			<div id="cartqty">
			<?php if($package_photo <= 0) { ?>
			<form name="cartqty" action="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php" method="POST">
			<input type="hidden" name="cid" value="<?php print MD5($cart['cart_id']);?>">
			<input type="hidden" name="action" value="updateqty">

			<?php print _qty_;?>: 
			<?php if($date['prod_inventory_control'] == "1") { ?>
			<?php if($_SESSION['outofstock'] == $cart['cart_id']) { ?>Out of stock<?php } else { ?>

			<select name="prod_qty" id="prod_qty" class="cartoption center addtocartqty"  onchange="this.form.submit()">
			<?php if($sub['sub_id'] > 0) { 
				
				if(($sub['sub_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
					$_SESSION['outofstock'] = $cart['cart_id'];
					header("location: /index.php?view=cart");	
					session_write_close();
					exit();
				}
				?>
			<?php 
				$x = 1;
				while($x <= $sub['sub_qty']) { ?>	
				<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
				<?php $x++;
			}
			?>

			<?php } else { ?>
			<?php 
				if(($date['prod_qty']<=0) && (empty($_SESSION['outofstock']))==true){ 
					$_SESSION['outofstock'] = $cart['cart_id'];
					header("location: /index.php?view=cart");	
					session_write_close();
					exit();
				}

				$x = 1;
				while($x <= $date['prod_qty']) { ?>	
				<option value="<?php print $x;?>" <?php if($x == ($cart['cart_qty'] + 0)) { print "selected"; } ?>><?php print $x;?></option>
				<?php $x++;
			}
			?>


			<?php } ?>
			</select>
			<?php } ?>
			<?php } else { 
				if($cart['cart_download'] == "1") { ?>
			<?php print ($cart['cart_qty'] + 0);?>
				<?php } else { 
				?>
			<input type="text"  name="prod_qty" id="prod_qty" class="cartoption center addtocartqty" size="2" value="<?php print ($cart['cart_qty'] + 0);?>"  onchange="this.form.submit()">
			<?php } ?>
			<?php } ?>
			</form> 
			<?php } else { ?>
			<?php print _qty_;?>:  <?php print ($cart['cart_qty'] + 0);?>

			<?php } ?>
			</div>
			</div>
			<?php if($package_photo <= 0) { ?>
			<?php 


			if($cart['cart_group_id'] > 0) { 
				$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$cart['cart_group_id']."' ");

				if($group['group_require_purchase'] > 0) { 
					if(countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_group_id='".$group['group_id']."' AND cart_order<='0' ") == 1) { 
						$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_order<='0' AND cart_group_id!='".$group['group_id']."' AND cart_package_photo!='".$cart['cart_id']."' ORDER BY cart_id DESC" );
						if(mysqli_num_rows($pcarts) > 0) { 
							$stop_remove = true;
							?>
						<div class="remove"><a href="" onclick="showrequireremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _remove_from_cart_;?></a></div>
						<div id="remove-<?php print MD5($cart['cart_id']);?>" class="error" style="display: none;"><?php print _remove_required_package_;?> <a href="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?action=rp&cid=<?php print MD5($cart['cart_id']);?>"><?php print _yes_;?></a> <a href="" onclick="showrequireremove('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _no_;?></a></div>
						<?php 
						}
					}
				}
			}

			if($stop_remove !== true) { 
			?>
			<div class="remove">
			<?php 
			$iocarts = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE ".checkCartSession()."  AND cart_order<='0' AND co_pic_id='".$pic['pic_id']."' GROUP BY co_opt_name  ORDER BY co_id ASC");
			if((countIt("ms_photo_products_discounts","WHERE dis_prod='".$cart['cart_photo_prod_connect']."' ") + mysqli_num_rows($iocarts) + countIt("ms_cart","WHERE cart_package_photo_extra_on='".$pic['pic_id']."' AND ".checkCartSession()." AND cart_order<='0' ")) > 0) { ?>

			<a href="<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?cid=<?php print MD5($cart['cart_id']);?>&dcd=<?php print MD5(date('Ymdhis'));?>&co=<?php print MD5("SD SDKSJD");?>&action=removefromcart"><?php print _remove_from_cart_;?></a>

			<?php } else { ?>
			<a href="" onClick="removeFromCart('<?php print MD5($cart['cart_id']);?>'); return false;"><?php print _remove_from_cart_;?></a>
			<?php } ?>
			</div>
			<?php } ?>

			<?php } ?>

			<?php if($pic['pic_id'] > 0) { ?>
				<div class="pc"><?php print $pic['pic_org'];?></div>
				<div class="pc"><?php print _in_;?> <?php print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>";?> 
				<?php if(!empty($sub['sub_id'])) { ?>

				<?php 
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							print " > <a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$upsub['sub_link']."\">".$upsub['sub_name']."</a>  ";
						}
					}
					
					print " > <a href=\"".$setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link']."\"><b>".$sub['sub_name']."</b></a>";
			?>
				<?php } ?>

				</div>
			<?php } ?>
			<?php if(($pic['pic_id'] > 0)&&($cart['cart_allow_notes'] == "1")==true) { ?>
			<?php cartNotes($cart);?>
			<?php } ?>
			</div>
			<div class="price  nofloatsmall">
			<?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?>
			<span class="extprice"><?php 
				if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
					 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
				}
				print showPrice(($cart['cart_qty'] * $this_price) + $co_download);?></span>
			<?php if($cart['cart_qty'] > 1) { ?>
			<div id="eachprice" class="pc">
			<?php print showPrice($this_price);?> <?php print _each_;?>
			<?php if($co_download > 0) { 
				if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
					 $co_download =  $co_download+ (($co_download * $site_setup['include_vat_rate']) / 100);
				}	
				print "<br>+".showPrice($co_download); } 
				
				$co_download = "";
				?>
			</div>
			<?php } ?>
			<?php } ?>
			</div>
			<div class="clear"></div>
		
		</div>
		<?php } ?>
<?php } 










function showImageOptions($cart,$package_photo,$parent) {
	global $setup,$site_setup,$zip_total,$noactions;
	$action = "viewcart";
	$price = $cart['this_price'];
	$this_price = $cart['this_price'];

	
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['co_pic_id']."' ");
	?>
	<div class="cartitem" id="cart-<?php print MD5($cart['cart_id']);?>">

			<div class="product  nofloatsmall">
				<div class="name"><?php print $cart['co_opt_name'];?></div>




			<div class="qty">

		<div id="cartqty">

		<?php print _qty_;?>:  <?php print ($cart['total_items'] + 0);?>

		</div>
		</div>

		</div>
		<div class="price  nofloatsmall">
		<?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?>
		<span class="extprice"><?php 
			if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
				 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
			}
			print showPrice($this_price);?></span>
		<?php } ?>
		</div>
		<div class="clear"></div>
	
	</div>
<?php } 




function cartnotes($cart) { 
	?>
	<script>
	function updatecartnotes(id) { 
		var fields = {};

		fields['action'] = 'updatecartnote';
		fields['cart_note'] = $("#cnotes-"+id).val();
		fields['cart_id'] = id;

		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			// alert(data);

			$("#cartnotes-"+id).html(data);
			$("#cartnotesedit-"+id).slideUp(200);
			$("#cartnotes-"+id).slideDown(200);
		});

	}
	function cartnotes(id) { 
		$("#cartnotesedit-"+id).slideToggle(200);
		$("#cartnotes-"+id).slideToggle(200);
	}
	</script>
	<div class="pc">
		<div><a href="" onclick="cartnotes('<?php print MD5($cart['cart_id']);?>'); return false;"><span class="the-icons icon-feather"></span><?php print _add_notes_;?></a></div>
		<div id="cartnotes-<?php print MD5($cart['cart_id']);?>"><?php print nl2br($cart['cart_notes']);?></div>
		<div id="cartnotesedit-<?php print MD5($cart['cart_id']);?>" class="hide">
			<div><textarea id="cnotes-<?php print MD5($cart['cart_id']);?>" rows="3" cols="30" class="field100"><?php print $cart['cart_notes'];?></textarea></div>
			<div><a href="" onclick="updatecartnotes('<?php print MD5($cart['cart_id']);?>'); return false;"><span class="the-icons icon-floppy"></span><?php print _update_cart_note_;?></a></div>
		</div>
	</div>

<?php } 

function productOptions($opt,$class,$prod) { 
	global $site_setup,$total_images;
		if($opt['opt_required'] == "1") { 
			$isrequired = "required";
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
		print "<div class=\"left\">".$opt['opt_name']."</div>";
		print "<div class=\"right textright\">";
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		print "</div>";
		print "<div class=\"clear\"></div>";
		print "</div>";
		print "<div><input type=\"text\"  id=\"opt-".$opt['opt_id']."\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\" class=\"$class prodoption field100 inputtext $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" price=\"".$opt['opt_price']."\" ></div>";
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

		print "<div><input type=\"checkbox\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"1\" class=\"$class prodoption inputcheckbox $isrequired\" prodid=\"".$opt['opt_photo_prod']."\"  price=\"".$opt['opt_price_checked']."\" > <label for=\"opt-".$opt['opt_id']."\">".$opt['opt_name']." "; 
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		
		print "</label></div>";
	}

	if($opt['opt_type'] == "dropdown") { 
		print "<div>".$opt['opt_name']."</div>";
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


function productStoreOptions($opt,$class,$prod) { 
	global $site_setup,$total_images,$setup;
	if($opt['opt_required'] == "1") { 
		$isrequired = "itemrequired";
	}
	// print "<h2>".$opt['package_buy_all']." - ".$opt['total_images']."</h2>";
	if(($opt['opt_type'] == "text") || ($opt['opt_type'] == "reg_key") == true){ 
		$price = $opt['opt_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $opt['opt_price'] * $opt['total_images'];
		}

		if(($prod['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}
		print "<div class=\"productconfigs\">"; print "<h3>".$opt['opt_name'];  if($price > 0) { print " +".showPrice($price)." "; } print "</h3></div>";
		print "<div class=\"productconfigsoptions\"><input type=\"text\"  id=\"opt-".$opt['opt_id']."\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\" class=\"$class prodoption inputtext $isrequired field100\" prodid=\"".$opt['opt_photo_prod']."\" price=\"".$opt['opt_price']."\" "; if($opt['opt_type'] == "reg_key") { print "value=\"".$_REQUEST['reg']."\""; } print "></div>";
	}

	if($opt['opt_type'] == "date") { 
		?>
		<link rel="stylesheet" href="<?php print $setup['temp_url_folder'];?>/sy-inc/css/smoothness/jquery-ui.min.css" type="text/css"><script> 
		$(document).ready(function(){
			$(function() {
				$( ".datepicker" ).datepicker({ dateFormat: 'DD, MM d , yy' });
			});
		});
		</script>
		<?php 
		$price = $opt['opt_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $opt['opt_price'] * $opt['total_images'];
		}

		if(($prod['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}
		print "<div class=\"productconfigs\">"; print "<h3>".$opt['opt_name'];  
		
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		
		print "</h3></div>";
		print "<div class=\"productconfigsoptions\"><input type=\"text\"  id=\"opt-".$opt['opt_id']."\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" name=\"opt-".$opt['opt_id']."\" size=\"".$opt['opt_text_field_size']."\" class=\"$class prodoption inputtext $isrequired datepicker\" prodid=\"".$opt['opt_photo_prod']."\" price=\"".$opt['opt_price']."\" ></div>";
	}

	if($opt['opt_type'] == "checkbox") { 
		$price = $opt['opt_price_checked'];
		if($opt['package_buy_all'] == "1") { 
			$price = $opt['opt_price_checked'] * $opt['total_images'];
		}

		if(($prod['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

		print "<div class=\"productconfigs\"><input type=\"checkbox\"  fieldname=\"".htmlspecialchars($opt['opt_name'])."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"1\" class=\"$class prodoption inputcheckbox $isrequired\" prodid=\"".$opt['opt_photo_prod']."\"  price=\"".$opt['opt_price_checked']."\" > <label for=\"opt-".$opt['opt_id']."\"><h3 style=\"display: inline;\">"; print "".$opt['opt_name'];  
		
		if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
		
		print "</h3></label></div>";
	}

	if($opt['opt_type'] == "dropdown") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";
		print "<select name=\"opt-".$opt['opt_id']."\"  id=\"opt-".$opt['opt_id']."\" fieldname=\"".htmlspecialchars($opt['opt_name'])."\"  class=\"$class prodoption inputdropdown $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" style=\"width: 100%; padding: 4px;\">\r\n";
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order, sel_id ASC ");
//		if($opt['opt_required'] =="1") { 
//			print "<option value=\"\" disabled>".$opt['opt_name']."</option>";
//		} else { 
			print "<option value=\"\" price=\"0\">"._select_option_."</option>\r\n";
//		}
		while($sel = mysqli_fetch_array($sels)) { 
		$price = $sel['sel_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $sel['sel_price'] * $opt['total_images'];
		}
		if(($prod['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

			print "<option value=\"".$sel['sel_id']."\" price=\"".$sel['sel_price']."\" "; if($sel['sel_default'] == "1") { print "selected"; } print ">".$sel['sel_name'].""; 
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
			
			
			print "</option>";
		}
		print "</select>";
		print "</div>";
	}




	if($opt['opt_type'] == "tabs") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";
		$dsel = doSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' AND sel_default='1' ORDER BY sel_order, sel_id ASC ");

		print "<input type=\"hidden\"  name=\"opt-".$opt['opt_id']."\"  id=\"opt-".$opt['opt_id']."\" fieldname=\"".htmlspecialchars($opt['opt_name'])."\"  class=\"$class prodoption inputtabs $isrequired\" prodid=\"".$opt['opt_photo_prod']."\" opttype=\"tabs\" value=\"".$dsel['sel_id']."\" defval=\"".$dsel['sel_id']."\">\r\n";
		?>
		<ul class="productoptionselect" id="ul-opt-<?php print $opt['opt_id'];?>">
		<?php 
		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order, sel_id ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
		$price = $sel['sel_price'];
		if($opt['package_buy_all'] == "1") { 
			$price = $sel['sel_price'] * $opt['total_images'];
		}
		if(($prod['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
		}

			print "<li sel_id=\"".$sel['sel_id']."\" fid=\"opt-".$opt['opt_id']."\" price=\"".$sel['sel_price']."\" "; if($sel['sel_default'] == "1") { print "class=\"on\""; } print ">".$sel['sel_name'].""; 
			
			if($price > 0) { print " "._option_add_price_."".showPrice($price); }  if($price < 0) { print " "._option_negative_price_."".showPrice(-$price); }					
			
			print "</li>";
		}
		print "</ul>";
		print "<div class=\"clear\"></div>";
		print "</div>";
	}


	if($opt['opt_type'] == "radio") { 
		print "<div class=\"productconfigs\"><h3>".$opt['opt_name']."</h3></div>";
		print "<div class=\"productconfigsoptions\">";

		$sels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$opt['opt_id']."' ORDER BY sel_order ASC ");
		while($sel = mysqli_fetch_array($sels)) { 
			print "<nobr><input type=\"radio\"  price=\"".$sel['sel_price']."\" id=\"opt-".$opt['opt_id']."\" name=\"opt-".$opt['opt_id']."\" value=\"".$sel['sel_id']."\" ";  if($sel['sel_default'] == "1") { print "checked"; } print " class=\"$class prodoption inputradio\" prodid=\"".$opt['opt_photo_prod']."\"> ".$sel['sel_name'].""; if($sel['sel_price'] > 0) { print " + ".showPrice($sel['sel_price']); } print "</nobr> ";
		}
		print "</div>";
	}
	if(!empty($opt['opt_descr'])) { 
		print "<div class=\"productconfigsoptions\">".nl2br($opt['opt_descr'])."</div>";
	}
}



function getCropDems($orgWidth,$orgHeight,$cropWidth,$cropHeight,$cropMin) {

	if($cropWidth>= $cropHeight) {
		$cp = $cropWidth/ $cropHeight;
	} else {
		$cp = $cropHeight / $cropWidth;
	}
	if($orgWidth >= $orgHeight) {

		$imp = $orgWidth / $orgHeight;
		if($imp > $cp) {
			$height = $orgHeight;
			$width = round($orgHeight  * $cp);
		} else{
			$width = $orgWidth;
			$height = round($orgWidth / $cp);
		}
	} else {
		$imp = $orgHeight / $orgWidth;
		if($imp > $cp) {
			$height =  round($orgWidth * $cp);
			$width =$orgWidth;
		} else {
			$height = $orgHeight;
			$width = round($orgHeight / $cp);
		}
	}
	$min_width = round($width * $cropMin);
	$min_height = round($height  * $cropMin);
	if($width == $orgWidth) {
		$x1 = 0;
		$x2 = $width;
		$y1 = ($orgHeight - $height)/2;
		$y2 = $height + $y1;
	} else {
		$x1 =  ($orgWidth - $width)/2;
		$x2 = $width + $x1;
		$y1 = 0;
		$y2 = $height;
	}
	$dem['x1'] = round($x1);
	$dem['x2'] = round($x2);
	$dem['y1'] = round($y1);
	$dem['y2'] = round($y2);
	$dem['min_width'] = round($min_width);
	$dem['min_height'] = round($min_height);
	$dem['crop_width'] = round($width);
	$dem['crop_height'] = round($height);
	$dem['org_width'] = $orgWidth;
	$dem['org_height'] = $orgHeight;
	return $dem;
}
?>
