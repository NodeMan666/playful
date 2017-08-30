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
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

$total = shoppingCartTotal($mssess);
$pay_total = $total['show_cart_total'];

?>

<script>
$(document).ready(function(){
	nofloatsmall()
	/*remove pop up widow text in basic package and midsize package on desktop*/
	if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		$("#viewcartminilinks").css("display","none");
		$("#bundle-one").css("display", "none");
	}
});
</script>

<div class="pageContent"><h3><?php print _added_to_cart_;?> </h3></div>

<?php 
if(!empty($_SESSION['addedtocart'])) { 
	$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_order<='0'  AND cart_id='".$_SESSION['addedtocart']."' ORDER BY cart_id DESC LIMIT 1" );
	unset($_SESSION['addedtocart']);
} else { 
	$cart = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_order<='0' ORDER BY cart_id DESC LIMIT 1" );
}
if($cart['cart_package_photo'] > 0) { 
	$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	showMiniPackage($cart);
} elseif($cart['cart_package'] > 0) { 
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	showMiniPackage($cart);
} elseif($cart['cart_photo_prod'] > 0) { 
	showMiniPhotoProduct($cart);
} else { 
	if($cart['cart_product_select_photos'] > 0) { 
//		$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");			
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
		showMiniProduct($date,$cart);
		?>
	 <div>&nbsp;</div><div class="success"><?php print _select_photos_for_product_message_;?></div>
		
		<?php 
			if($_REQUEST['from_photo'] !== "1") { 
				if(!empty($_SESSION['last_gallery'])) { 
					$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
					if(!empty($date['date_id'])) { ?>
					<div class="pc center"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/"; ?>"><?php print _return_to_last_gallery_page_;?> "<?php print $date['date_title'];?>"</a></div>

				<?php 
					// unset($_SESSION['last_gallery']);
					}
				}
			if(customerLoggedIn()) { 
				$dates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."'  AND date_id>'0' AND date_public='1' AND cat_type!='proofing' ORDER BY date_id DESC ");
				if(mysqli_num_rows($dates) > 0) { ?>
			<div class="pc center"><a href="" onClick="findphotos(); return false;"><?php print _view_my_photos_;?></a></div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<div>&nbsp;</div>
	<?php
	} else { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
		showMiniProduct($date,$cart);
	}
}
?>

<?php if(($_REQUEST['package'] == "1")&&($pack['package_buy_all'] !== "1")&&($pack['package_limit'] !== "1")==true) { ?>
<div id="bundle-one" class="second-pop">
	<div class="pc">
	<?php print _package_added_instructions_;?>
	</div>
	<div class="pc">
	<a href="" onClick="closeaddedpackage(); return false;"><b><?php print _continue_;?></b></a>
	</div>
</div>
<!-- <div class="back-disabled"></div> -->
<?php } else { ?>

<!-- <?php // if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_order<='0' AND cart_package_photo='0' " ) > 1) { ?> -->
<!-- <div class="pc">
<div class="left"><?php // print $total['total_items']; ?> <?php // print _items_in_your_cart_;?></div>
<div class="right textright"><?php // print _subtotal_;?>:  <?php  //print showPrice($pay_total);?></div>
<div class="clear"></div>
</div> -->
<!-- <?php // } ?> -->
<div class="pc" id="viewcartminilinks">
<!-- Hooray!successful added page buttons -->
<div class="center viewcartminilinks"><!-- <a href="/<?php //print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><?php //print _view_cart_;?>&nbsp;&nbsp;</a> -->  
<!-- <a href="" onClick="hideMiniCart(); return false;"><nobr><?php print _continue_shopping_;?></nobr></a></div> -->
<div class="center checkoutminicart  viewcartminilinks">

	<div class="pc">
	<?php print _package_added_instructions_;?>
	</div>
	<div class="pc">
	<a href="" onClick="closeaddedpackage(); return false;"><b><?php print _continue_;?></b></a>
	</div>

	<!-- <?php if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1")==true) { ?>
		<a href="" onclick="ppexpresscheckout(); return false;">Proceed to Checkout</a>
	<?php } else { ?>
		<?php 
		if(!empty($_SESSION['last_gallery'])) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
			if($date['date_gallery_exclusive'] == "1") { 
				$ge_return_link = $setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/";
			}
		}
		if(!empty($ge_return_link)) { ?>
		<a href="<?php print gotosecure();?><?php print $ge_return_link;?>?view=checkout"><?php print _checkout_;?></a>
		<?php	} else {  ?>
		<a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php print _checkout_;?></a>
		<?php } ?>
	<?php } ?> -->  

</div>
<div class="pc center"><?php print _mini_cart_bottom_text_;?></div>

<div class="clear"></div>
<!-- <div class="back-disabled"></div> -->
</div>
<?php } ?>

<?php 
function showMiniProduct($date,$cart) {
	global $setup,$site_setup,$zip_total,$store;
	if(!empty($cart['cart_sub_id'])) { 
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
	}
	?>
	<div class="storeProduct">
		<?php if($sub['sub_pic_id'] > 0) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
			if($pic['pic_id'] > 0) { ?>
				<div class="left nofloatsmall pc" style="margin: 0 8px 8px 0;"><img src="<?php print getimagefile($pic,'pic_mini');?>">	</div>
		<?php 
			}
		
		} else { ?>

			<div class="left nofloatsmall pc" style="margin: 0 8px 8px 0;"><?php print getPagePreview($date,"mini"); ?></div>
		<?php } ?>
		<div class="left nofloatsmall">
		<div class="pc">
		<b><?php print ($cart['cart_qty'] + 0).") ".$date['date_title'];?></b>
		</div>
		<div class="pc">
		<?php 
		if($cart['cart_account_credit_for'] > 0) { 
			$this_price = $cart['cart_price'];
		} else { 
			$price = productPriceCart($date);
			$this_price = $price['price'];
			if(!empty($cart['cart_sub_id'])) { 
				$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
				$this_price = $this_price + $sub['sub_add_price'];
			}
				if($date['reg_person'] <=0) { 
					updateSQL("ms_cart", "cart_price='".$price['price']."' WHERE cart_id='".$cart['cart_id']."' ");
				}
				if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
					 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
				}
		}
			if($this_price > 0) { 
				print "<div>".showPrice($this_price)."</div>";
			}
			?>
			</div>
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

		<div class="pc">
		<?php 
			if(!empty($date['prod_opt1'])) { 
				print $sub['opt1_value']." ";
			}
			if(!empty($date['prod_opt2'])) { 
				print $sub['opt2_value']." ";
			}

			if(!empty($date['prod_opt3'])) { 
				print $sub['opt3_value']." ";
			}

			if(!empty($date['prod_opt4'])) { 
				print $sub['opt4_value']." ";
			}

			if(!empty($date['prod_opt5'])) { 
				print $sub['opt5_value']." ";
			}
		?>

		</div>
	</div>
	<div class="cssClear">&nbsp;</div>
<?php } ?>



<?php 
function showMiniPackage($cart) {
	global $setup,$site_setup,$zip_total,$store;
	if($cart['cart_package_photo'] > 0) { 
		$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	} else { 
		$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_id']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	}
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$pcart['cart_pic_date_id']."' ");

	$packageroute =  explode('>', $pcart['cart_product_name']);
	$packagetitle = $packageroute[1];

 	?>
	<div class="storeProduct">
		<div class="center">
		<div class="pc">
			<!-- <b><?php //print ($pcart['cart_qty'] + 0).") ".$packagetitle;?></b> -->
			<b><?php print $packagetitle;?></b>
		</div>
		<div class="pc">
		<?php 
		$price = $pcart['cart_price'];
		$this_price = $pcart['cart_price'];
//			updateSQL("ms_cart", "cart_price='".$price['price']."' WHERE cart_id='".$cart['cart_id']."' ");
			if(($pcart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
				 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
			}

			print "<div>".showPrice($this_price)."</div>";
			?>
		</div>
		<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$pcart['cart_id']."' ");
			while($co = mysqli_fetch_array($cos)) { ?>
		<div class="pc">
			<?php print $co['co_opt_name'].": ".$co['co_select_name']; 
				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					
			
			$this_price = $this_price + $co['co_price']; ?>
			</div>
		<?php } ?>

	</div>
	<div class="cssClear">&nbsp;</div>
<?php } ?>


<?php 

function showMiniPhotoProduct($cart) {
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
	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'].""); 

	?>
	<div class="storeProduct">
		<div class="pc nofloatsmall center" style="margin: 0;">
		
	<?php if(($prod['pp_width'] > 0) && ($cart['cart_photo_bg'] <=0) == true) { ?>
	<div id="ct-<?php print $cart['cart_id'];?>">
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
		
		</div>
		<div class="nofloatsmall center">
		<div class="pc">
		<b><?php print substr($cart['cart_product_name'], 10);?></b>
		</div>
		<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ");
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
		<div class="pc">
	<?php 
				
			if(($cart['cart_taxable'] && $site_setup['include_vat'] == "1")==true) { 
				 $this_price =  $this_price+ (($this_price * $site_setup['include_vat_rate']) / 100);
			}

			
			print showPrice($cart['cart_qty'] * $this_price);?>
		</div>

	</div>
	<div class="clear"></div>
	<?php if(($pic['pic_id'] > 0)&&($cart['cart_allow_notes'] == "1")==true) { ?>
	<?php cartNotes($cart);?>
	<?php } ?>
<?php } 
mysqli_close($dbcon);
?>
