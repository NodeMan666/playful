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
require $setup['path']."/".$setup['inc_folder']."/store/store_photo_buy_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
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
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

?>
<?php if($_REQUEST['packages'] == "1") { 
	$and_where = "AND group_buy_all='1' ";
} else { 
	$and_where = "AND group_package='0' ";
}
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
$pic = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE pic_key='".$_REQUEST['pid']."' AND bp_blog='".$date['date_id']."' ");
if(empty($pic['pic_id'])) { 
	if(!empty($date['date_photo_keywords'])) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	}
}
$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 
$total = shoppingCartTotal($mssess);
if($pic['bp_pl'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$pic['bp_pl']."' ");
} elseif($sub['sub_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
} else { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
}
if($_REQUEST['fav_pl'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['fav_pl']."' ");
}
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}

if($_REQUEST['view'] == "highlights") { 
	// remove buy alls from highlights 
	$and_buy_all = "AND group_buy_all!='1' ";
}
if($_REQUEST['view'] == "favorites") { 
	$and_buy_all = "AND group_no_favs='0' ";

	$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id='".$pic['pic_id']."' AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ";
	$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id LEFT JOIN ms_sub_galleries ON  ms_favs.fav_sub_id=ms_sub_galleries.sub_id LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic";
	$pics_orderby = "pic_org";

	$favpl = doSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where GROUP BY ms_calendar.date_photo_price_list  ");
	if(!empty($favpl['fav_id'])) { 
		if($pic['bp_pl'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['bp_pl']."' ");
		} elseif($favpl['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['sub_price_list']."' ");
		} else { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['date_photo_price_list']."' ");
		}
	}
}

if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}

?>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_photos_buy_js.js?<?php print MD5($site_setup['sytist_version']); ?>"></script>


<script>
priceformat = "<?php print $store['price_format'];?>";
currency_sign = "<?php print $store['currency_sign'];?>";
decimals = (<?php print $store['price_decimals'];?>);



//if($("body").width() <= 800) { 
	$("#photobuyview").css({"display":"none"});
	$(".packageproducts").removeClass("packageproducts");
//}
</script>

<div id="photobuycontainer">
<?php if(($_REQUEST['packages'] !== "1")&&($_REQUEST['withphoto']!=="1")==true) {  ?>
	<div class="left" style="width: 40%;" id="photobuyview">
	<div style="position: relative;">

	<div id="thumbpreview" style="display: none; border: solid 1px #242424; margin: auto; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; background: url('<?php tempFolder(); ?>/<?php print "sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_pic")."|".MD5($date['date_cat'])."|".$_REQUEST['color_id']."";?>') center center no-repeat;"  dw="<?php print $size[0];?>" dh="<?php print $size[1];?>"></div>
	<div id="cropmessage" style="position: absolute; width: 100%; top: 0; text-align: center; margin-top: 20px; display: none; color: #FFFFFF; text-shadow:1px 1px 1px #000; font-size: 15px; ">
	<div><?php print _center_crop_preview_;?></div>
	<div id="cropdems"></div>
	</div>
	<div>&nbsp;</div>
	<div id="cropcartmessage" class="pc center" style="display: none;"><?php print _crop_add_to_cart_;?></div>
	</div></div>
<?php } ?>

<div <?php if(($_REQUEST['packages'] !== "1")&&($_REQUEST['withphoto']!=="1")==true) {  ?> class="packageproducts"<?php } ?> id="photobuyproducts">
	<div style="padding-left: 16px;">

		<?php $req = doSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' AND group_require_purchase='1' ");
			if(!empty($req['group_id'])) { 
				$required_group = $req['group_id'];
				$cons = whileSQL("ms_photo_products_connect LEFT JOIN ms_photo_products_groups ON ms_photo_products_connect.pc_group=ms_photo_products_groups.group_id", "*","WHERE pc_list='".$list['list_id']."' AND pc_group='".$required_group."' ORDER BY pc_order ASC ");
				while($con = mysqli_fetch_array($cons)) { 
					if($con['pc_package'] > 0) { 
						$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_package='".$con['pc_package']."' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
						if($cart['cart_id'] > 0) { 
							$stop_require = true;
						}
					} else {
						$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod='".$con['pc_prod']."' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
						if($cart['cart_id'] > 0) { 
							$stop_require = true;
						}
					}
				}
				if($stop_require == true) { 
					$required_group = 0;
				}
			}
			if($_REQUEST['group_id'] <= 0) { 
				$thisgroup = 1;
			} else { 
				$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' $and_where $and_buy_all ORDER BY group_order ASC");
				if(mysqli_num_rows($groups) > 1) {
					while($group = mysqli_fetch_array($groups)) { 
						$gp++;
						if($_REQUEST['group_id'] == $group['group_id']) { 
							$thisgroup = $gp;
						}
					}
				} else { 
					$thisgroup = 1;
				}
			}
			if($thisgroup <= 0) { 
				$thisgroup = 1;
			}
			?>

		<?php 
	

		$has_package = countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_buy_all='0' AND cart_package_no_select!='1' AND cart_bonus_coupon<='0'  AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );	
		$has_coupon = countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_buy_all='0'   AND cart_bonus_coupon>'0'  AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );	

		 if((($has_package > 0) && ($has_coupon<= 0)) && ($_REQUEST['withphoto'] == "1")==true) { ?>
		<div>&nbsp;</div>	
		<div class="pc"><h3><a href="" onclick='packagenexttophoto($("#slideshow").attr("curphoto")); return false;' class="icon-picture the-icons"><?php print _view_my_collection_;?></a></h3></div>
	<?php }
		 if((($has_package <= 0) && ($has_coupon > 0)) && ($_REQUEST['withphoto'] == "1")==true) { ?>
		<div>&nbsp;</div>	
		<div class="pc"><h3><a href="" onclick='packagenexttophoto($("#slideshow").attr("curphoto")); return false;' class="icon-picture the-icons"><?php print _view_my_bonus_coupon_	;?></a></h3></div>
	<?php }

		 if((($has_package > 0) && ($has_coupon > 0)) && ($_REQUEST['withphoto'] == "1")==true) { ?>
		<div>&nbsp;</div>	
		<div class="pc"><h3><a href="" onclick='packagenexttophoto($("#slideshow").attr("curphoto")); return false;' class="icon-picture the-icons"><?php print _view_my_collection_and_coupon_	;?></a></h3></div>
	<?php }


	if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_product_select_photos='1' AND cart_order<='0' " ) > 0) { ?>
	<?php
		$prods = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_product_select_photos='1' AND cart_order<='0' ORDER BY cart_id DESC");
		if((mysqli_num_rows($prods) > 0) && ($_REQUEST['withphoto'] == "1")==true) { 
		while($prod = mysqli_fetch_array($prods)) { 
			$gt++;
			?>
		<div class="pc"><h3><a href="" onclick='storeproductnexttophoto($("#slideshow").attr("curphoto"),"<?php print $prod['cart_id'];?>"); return false;' class="icon-picture the-icons"><?php print $prod['cart_product_name'];?></a></h3></div>
		<?php } 
		}?>
	<?php } ?>
			<div>&nbsp;</div>
		<?php 
		if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
			$_SESSION['return_page'] = $setup['temp_url_folder']."".$date['cat_folder']."/".$date['date_link']."/"; 
			$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
			$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
			print "<div class=\"pc center logintopurchasemessage\" style=\"margin-bottom: 12px; font-weight: bold;\">".$message."</div>";			
			if($list['list_require_login'] == "1") { exit(); } 
		}
			?>

		<?php 

		$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' $and_where $and_buy_all  ORDER BY group_order ASC");
		if((mysqli_num_rows($groups) > 1) || ($list['list_wall_designer'] == "1") == true) { 
			$wdlang = doSQL("ms_wall_language", "*", "");
			?>
		<div id="grouptabs">


		<?php 

		while($group = mysqli_fetch_array($groups)) { 
			$gtab++;?>
		<div class="tab <?php if($gtab == $thisgroup) { print "tabon"; } ?>" gid="<?php print $group['group_id'];?>">
		<?php print $group['group_name'];?>
		</div>
		<?php } ?>
		<?php if(($list['list_wall_designer'] == "1") && ($date['green_screen_backgrounds'] <= 0) == true) { ?>
			<div class="tab" gid="walldesigner"><?php print $wdlang['_wd_wall_designer_tab_'];?></div>		
		<?php } ?>
		<div class="clear"></div>
		</div>
		<?php } ?>
		<?php if($list['list_wall_designer'] == "1") { ?>
		<div class="group-walldesigner prodgroups hide">
			<div class="pc">
			<?php print $wdlang['_wd_wall_designer_text_'];?>
			</div>
			<div class="pc center"><a href="index.php?view=room<?php if($sub['sub_id'] > 0) { ?>&sub=<?php print $sub['sub_link'];?><?php } ?>&photo=<?php print $pic['pic_key'];?>&return=1<?php if($_REQUEST['view'] == "favorites") { ?>&from=favorites<?php } ?>" class="checkout"><?php print $wdlang['_wd_view_'];?></a></div>
		</div>
		<?php } ?>

		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' $and_where $and_buy_all  ORDER BY group_order ASC");
		while($group = mysqli_fetch_array($groups)) { 
			$g++;
			?>
		<div id="" <?php if($g !== $thisgroup) { ?>style="display: none;"<?php } ?> class="prodgroups group-<?php print $group['group_id'];?>">
		<?php if(mysqli_num_rows($groups) == 1) { ?><div><h2><?php print $group['group_name'];?></h2></div><?php } ?>
		<?php if(($required_group > 0)&&($group['group_id'] !== $required_group)==true) { ?>
		<div class="pc"><?php print $req['group_require_message'];?></div>

		<?php }  else {	?>

		<?php if(($group['group_buy_all'] == "1")&&($_REQUEST['view'] == "favorites") ==true){ ?>
		<div class="groupdescr"><?php print nl2br($group['group_buy_all_favs']);?></div><div class="clear"></div>
		<?php } else { ?>
		<?php if(!empty($group['group_descr'])) { ?><div class="groupdescr"><?php print nl2br($group['group_descr']);?></div><div class="clear"></div><?php } ?>
		<?php } ?>
			<?php 
				if($group['group_buy_all'] == "1") { 
					if(!empty($_REQUEST['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
						$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}
					if($_REQUEST['view'] == "favorites") { 
						$and_where = "";
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

					print "<div>";
					if($_REQUEST['view'] == "favorites") { 

					} else { 

						print $date['date_title'];
						if(!empty($sub['sub_id'])) { 
							$ids = explode(",",$sub['sub_under_ids']);
							foreach($ids AS $val) { 
								if($val > 0) { 
									$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
									print " > ".$upsub['sub_name']."  ";
								}
							}
							print " > ".$sub['sub_name'];
						}
						if(!empty($_REQUEST['kid'])) { 
							if(!is_numeric($_REQUEST['kid'])) { die(); } 
							$keyword = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
						}
						if(!empty($keyword['key_word'])) { 
							print " > "._with_key_word_.": <b>'".$keyword['key_word']."'</b> ";
						}
						if(!empty($_REQUEST['keyWord'])) { 
							print " > "._with_key_word_.": <b>'".$_REQUEST['keyWord']."'</b> ";
						}
					}
					print "<br><h3> ".$total_images." "._photos_word_photos_."</h3></div>";
			}
			print "<div>&nbsp;</div>";
			?>




			<?php $cons = whileSQL("ms_photo_products_connect LEFT JOIN ms_photo_products_groups ON ms_photo_products_connect.pc_group=ms_photo_products_groups.group_id", "*","WHERE pc_list='".$list['list_id']."' AND pc_group='".$group['group_id']."' ORDER BY pc_order ASC ");
			if(mysqli_num_rows($cons)<=0) { ?>
			<div class="underline center">No products added to <?php print $group['group_name'];?></div>
			<?php } ?>

			<?php 
				while($con = mysqli_fetch_array($cons)) { 
					if($con['pc_package'] > 0) {
						$pack = doSQL("ms_packages", "*","WHERE package_id='".$con['pc_package']."' ");
						showPackage($pack,$list,$con);

					} elseif($con['pc_store_item'] > 0) { 

						############## STORE ITEM ###############################
						$sdate = doSQL("ms_calendar", "*", "WHERE date_id='".$con['pc_store_item']."' ");
						if($sdate['date_id'] > 0) { 
						?>
				<div class="photoprod packageinfo">
					<div class="underline" cw="0" ch="0" >
						<div class="left" style="padding-right: 16px;">
						<?php 

						$spic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$sdate['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
						if(!empty($spic['pic_id'])) {
							// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$spic['pic_folder']."/".$spic['pic_mini']); 
							$thumb_html ="<a href=\"\" onclick=\"showstoreitem('".$sdate['date_id']."'); return false;\"><img src=\"".getimagefile($spic,'pic_mini')."\"  id=\"th-".$sdate['date_id']."\" border=\"0\"></a>";
						} else { 
							$spic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$sdate['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
							if(!empty($spic['pic_id'])) {
								// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$spic['pic_folder']."/".$spic['pic_mini']); 
								$thumb_html ="<a href=\"\" onclick=\"showstoreitem('".$sdate['date_id']."'); return false;\"><img src=\"".getimagefile($spic,'pic_mini')."\"  id=\"th-".$sdate['date_id']."\" border=\"0\"></a>";
							}
						}
						print $thumb_html;

						?>
						</div>
						<div class="left">
						<h3><a href="" onclick="showstoreitem('<?php print $sdate['date_id'];?>'); return false;"><?php print $sdate['date_title'];?></a></h3>
						</div>
						<?php if($sdate['prod_price'] > 0) {
						

						?>
						<div class="right">
						<h3><a href="" onclick="showstoreitem('<?php print $sdate['date_id'];?>'); return false;">
					<?php 
					$price = productPrice($sdate);
					if($price['onsale'] == true) { 
						if(($sdate['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
							 $price['onsale'] = $price['onsale']+ (($price['onsale'] * $site_setup['include_vat_rate']) / 100);
						}
						print "<div class=\"onsaleprice\">".showPrice($price['org'])."</div>";
					}
					$this_price = $price['price'];
					if(($sdate['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
						 $this_price = $this_price + (($this_price * $site_setup['include_vat_rate']) / 100);
					}
						if($this_price > 0) { 
						print "<div class=\"productprice\"><span id=\"prodprice\" org=\"".showPrice($price['price'])."\">".showPrice($this_price)."</span></div>";
					}
				?>
				</a></h3>
						</div>
						<?php } ?>
									<div class="clear"></div>

				</div>
				</div>
				<div class="clear"></div>
	

					<?php 
						}
					} else { 
						$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$con['pc_prod']."' ");
						if($prod['pp_id'] > 0) { 
							if($prod['pp_free'] <=0) { 
								showProduct($prod,$list,$con);
							}
						}
					}
				 } 
				?>

			<?php } ?>
				<div>&nbsp;</div>
				</div>
		<?php } ?>
</div>
</div>
<div class="clear"></div>
</div>




<script>

function showpackagedetails() { 
	$(this).find('.hiddeninfo').slideDown(100);
	$(this).addClass("packageopen");
	if(istablet == 1) { 
		$(this).slideDown(200);
	} else { 
		$(this).css({"box-shadow":"0px 0px 12px rgba(0,0,0,.5)"});
		$(this).animate({"width":"+=200px", "position":"relative", "margin-left":"-=200px"}, 200);
	}
}

function hidepackagedetails() { 
	$(this).find('.hiddeninfo').slideUp(100);
	$(this).removeClass("packageopen");
	if(istablet == 1) { 
		// $(this).slideUp(200);
	} else { 
		$(this).css({"box-shadow":"none"});
		$(this).animate({"width":"-=200px", "position":"relative", "margin-left":"+=200px"}, 200);
	}
}



$(document).ready(function(){

	$(".qtydiscount").hoverIntent(
	  function () {
		$(this).find('.discount').slideDown(100);
	  },
	  function () {
		$(this).find('.discount').slideUp(100);
	  }
	);


	$(".packagemore").click(function() { 
		if($(this).closest(".packageinfo").hasClass("packageopen")) { 
			$(this).closest(".packageinfo").find('.hiddeninfo').slideUp(100);
			$(this).closest(".packageinfo").removeClass("packageopen");
			$(this).closest(".packageinfo").css({"box-shadow":"none"});
			<?php if($_REQUEST['packages'] !== "1") { ?>
			if(istablet != 1 &&  ismobile != 1) { 	

				$(this).closest(".packageinfo").animate({"width":"-=200px", "position":"relative", "margin-left":"+=200px"}, 200);
			}
			return false;
			<?php } ?>
		} else { 
			$(this).closest(".packageinfo").find('.hiddeninfo').slideDown(100);
			$(this).closest(".packageinfo").addClass("packageopen");
			$(this).closest(".packageinfo").css({"box-shadow":"0px 0px 12px rgba(0,0,0,.5)"});
			<?php if($_REQUEST['packages'] !== "1") { ?>
			if(istablet != 1 &&  ismobile != 1) { 	
				$(this).closest(".packageinfo").animate({"width":"+=200px", "position":"relative", "margin-left":"-=200px"}, 200);
			}
			<?php } ?>
			return false;

		}
	});

/*
	$(".packageinfo").hoverIntent(
	  function () {
		if($(this).find(".hiddeninfo").html().trim() !="") { 
			$(this).find('.hiddeninfo').slideDown(100);
			if(istablet == 1) { 
				$(this).slideDown(200);
			} else { 
				$(this).css({"box-shadow":"0px 0px 12px rgba(0,0,0,.5)"});
				$(this).animate({"width":"+=200px", "position":"relative", "margin-left":"-=200px"}, 200);
			}
		}
	  },
	  function () {
		if($(this).find(".hiddeninfo").html().trim() !="") { 
			$(this).find('.hiddeninfo').slideUp(100);
			if(istablet == 1) { 
				// $(this).slideUp(200);
			} else { 
				$(this).css({"box-shadow":"none"});
				$(this).animate({"width":"-=200px", "position":"relative", "margin-left":"+=200px"}, 200);
			}
		}
	  }
	);
*/
});
</script>

<?php  mysqli_close($dbcon); ?>
