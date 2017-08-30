<?php
include "../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
header('Content-Type: text/html; charset=utf-8');
require "../../".$setup['inc_folder']."/functions.php"; 
require "../admin.functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*","");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
$no_trim = true;
if($_REQUEST['prices'] == "1") { 
	$show_prices = true;
}
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>

<style type="text/css" media="screen,print">
body,p,div {
	color: #000000;
	font: 12px Arial;
}
body {
	background: #f4f4f4;
	padding: 0;
	margin: 0;
}
.pad, .row { padding: 4px 0px; } 
.center { text-align: center; } 
.left { float: left; } 
.right { float: right; } 
.clear {  clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px; } 
.textright { text-align: right; } 
.field100 { width: 97%;}
.padtopbottom { padding: 2px 0; } 
.reg,.normal { font-size: 13px; } 
p { padding: 8px 0 } 
.green { color: #74a474; } 
.muted { color: #949494; } 
.labels { font-size: 17px; font-weight: bold; padding: 4px 0;} 
#packingslip { 
width: 640px;
margin: auto;
margin-top: 12px;
border: solid 1px #e4e4e4;
background: #FFFFFF;
padding: 24px;
}

.billing { float: left; width: 50%; }
.shipping { float: right; width: 50%; }

.headerleft { float: left; width: 50%; }
.headerright{ float: right; width: 50%; text-align: right; }

#products { background-color: #dddddd; margin-top: 20px; } 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold; } 
#products td { background: #FFFFFF; padding: 8px; }

#totals { float: right;  margin-top: 20px; } 
#totals td { padding: 5px; }

</style>

<style media="print">
body {
	background-color: #ffffff;
}
#packingslip { 
	border: none;
}
#products { background-color: #dddddd; margin-top: 20px; !important} 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold;!important } 
#products td { background: #FFFFFF; padding: 8px; !important}

#totals { float: right;  margin-top: 20px; !important} 
#totals td { padding: 5px; !important}

</style>


</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<?php 
$orderNums = explode("|",$_REQUEST['orderNum']);
foreach($orderNums AS $orderNum) { 
	if(!empty($orderNum)) { 
		$totalorders++;
		$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS order_date", "WHERE order_id='".$orderNum."' ");
		if(empty($order['order_id'])) {
			die("Unable to find order information");
		}
		if($order['order_archive_table'] == "1") { 
			$cart_table = "ms_cart_archive";
		} else { 
			$cart_table = "ms_cart";
		}

		?>
		<div id="packingslip">
			<div id="header">
				<div class="headerleft">
					<div>#<?php print "".$order['order_id'].""; ?> <?php print  "".$order['order_first_name']." ".$order['order_last_name'].""; ?> <?php print "".$order['order_date'].""; ?></div>



				</div>
				<div class="clear"></div>
			</div>

		<hr></h2>



		<?php 	if(!empty($order['order_notes'])) { 
			print '<table width="100%" cellpadding="4" cellspacing="1"><tr><td>'._customer_notes_.': <i>'.nl2br($order['order_notes']).'</i></td></tr></table>';
			}
			?>
			<div>&nbsp;</div>
			<?php if(!empty($order['order_admin_notes'])) { ?>
			<div><?php print nl2br($order['order_admin_notes']); ?></div>
			<div>&nbsp;</div><?php } ?>

			<table width="100%" cellpadding="4" cellspacing="1" id="products" >

			<?php

			$carts = whileSQL($cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_package!='0'   ORDER BY cart_package_no_select DESC, cart_id ASC" );
			$tracks_total	= mysqli_num_rows($carts);
			while($cart= mysqli_fetch_array($carts)) {
				$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
				$tracknum++;
				showOrderPackage($pack,$cart,$show_prices);

				$pcarts = whileSQL($cart_table, "*", "WHERE cart_order='".$order['order_id']."' AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'  ORDER BY cart_pic_org ASC" );
					while($pcart= mysqli_fetch_array($pcarts)) {
					$tracknum++;
					$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
					if($pcart['cart_pic_id'] > 0) { 
						showOrderPhoto($pcart,$show_prices,$cart,$order);
					}
				}

			}

			$carts = whileSQL($cart_table, "*", "WHERE cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0'  AND cart_package_photo='0' ORDER BY cart_pic_org ASC " );
			$tracks_total	= mysqli_num_rows($carts);
				while($cart= mysqli_fetch_array($carts)) {
				$tracknum++;
				showOrderPhoto($cart,$show_prices,"",$order);
			}

			$carts = whileSQL($cart_table, "*", "WHERE cart_store_product!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id DESC  " );
			$tracks_total	= mysqli_num_rows($carts);
				while($cart= mysqli_fetch_array($carts)) {
				showOrderProduct($date,$cart,$show_prices);
				$pcarts = whileSQL($cart_table, "*", "WHERE  cart_order='".$order['order_id']."'  AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  ORDER BY cart_pic_org ASC" );
				if(mysqli_num_rows($pcarts) > 0) { 

					}
					while($pcart= mysqli_fetch_array($pcarts)) {
						$tracknum++;
				showOrderPhoto($pcart,$show_prices,$cart,$order);
					}


				}
			$carts = whileSQL($cart_table, "*", "WHERE cart_invoice='1' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id ASC  " );
				while($cart= mysqli_fetch_array($carts)) {
				showInvoiceItems($cart,$show_prices);

				}
				$carts = whileSQL($cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name ");
				$tracks_total	= mysqli_num_rows($carts);
					while($cart= mysqli_fetch_array($carts)) {
					showOrderImageOptions($cart, "0",$order,$show_prices);
				}


			?>
		</table>



</div>
	</div>

	<?php } 
}?>


<?php
		function showInvoiceItems($cart,$show_prices) { 
		global $cart_table;
		?>
		<tr valign="top"><td>
		<b><?php print $cart['cart_product_name'];?></b>
		</td>
		<?php if($show_prices == true) { ?>
			<td><?php print showPrice($cart['cart_price']);?></td>
		<?php } ?>
		<td class="center"><?php print ($cart['cart_qty'] + 0);?></td>
		<?php if($show_prices == true) { ?>
			<td class="textright"><?php print showPrice($cart['cart_qty'] * $cart['cart_price']);?></td>
		<?php } ?>
</tr>
		<?php 
	}
		?>



<?php

		
function showOrderImageOptions($cart,$package_photo,$order,$show_prices) {
	global $setup,$site_setup,$zip_total,$noactions,$cart_table;
	$action = "viewcart";
	$price = $cart['this_price'];
	$this_price = $cart['this_price'];

	
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['co_pic_id']."' ");
	?>

		<tr valign="top"><td>
		<b><?php print $cart['co_opt_name'];?></b>
		</td>
		<?php if($show_prices == true) { ?>
			<td>&nbsp;</td>
		<?php } ?>
		<td class="center"><?php print ($cart['total_items'] + 0);?></td>
		<?php if($show_prices == true) { ?>
			<td class="textright"><?php print showPrice($this_price);?></td>
		<?php } ?>
	</tr>
<?php } 





function showOrderProduct($date,$cart,$show_prices) { 
	global $cart_table;
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

		?>
		<tr valign="top"><td>

			<?php if($_REQUEST['thumbnails'] == "1") { ?>
			<div class="left" style=" width: 30%; text-align: center;">
			<?php 
			if($sub['sub_pic_id'] > 0) { 
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
				if($pic['pic_id'] > 0) { 
				print '<img src="'.getimagefile($pic,'pic_mini').'">';
				}
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					print '<img src="'.getimagefile($pic,'pic_mini').'">';
				}
			} 
			?>
		<?php } ?>
			</div>
			<div class="left">
			<b><?php print $date['date_title'];?></b>
				<?php if(!empty($sku)) { ?>
				<div class="pad">#<?php print $sku;?></div>
				<?php } ?>

				<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."'  AND co_pic_id<='0' ");
				while($co = mysqli_fetch_array($cos)) { ?>
				<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; if($show_prices == true) {  
					
				if($co['co_price'] > 0) { 
					print " "._option_add_price_."".showPrice($co['co_price']); 
				}  
				if($co['co_price'] < 0) { 
					print " "._option_negative_price_."".showPrice(-$co['co_price']); 
				}					

					
					$this_price = $this_price + $co['co_price']; }?></div>
				<?php } ?>

				<div>
				<?php 
				if(!empty($date['prod_opt1'])) { 
					print "<div class=\"pad\">".$date['prod_opt1'].": ".$sub['opt1_value']."</div>";
				}
				if(!empty($date['prod_opt2'])) { 
					print "<div class=\"pad\">".$date['prod_opt2'].": ".$sub['opt2_value']."</div>";
				}

				if(!empty($date['prod_opt3'])) { 
					print "<div class=\"pad\">".$date['prod_opt3'].": ".$sub['opt3_value']."</div>";
				}

				if(!empty($date['prod_opt4'])) { 
					print "<div class=\"pad\">".$date['prod_opt4'].": ".$sub['opt4_value']."</div>";
				}

				if(!empty($date['prod_opt5'])) { 
					print "<div class=\"pad\">".$date['prod_opt5'].": ".$sub['opt5_value']."</div>";
				}
			?>
			</div>
		</td>
		<?php if($show_prices == true) { ?>
			<td><?php print showPrice($this_price);?></td>
		<?php } ?>
		<td class="center"><?php print ($cart['cart_qty'] + 0);?></td>
		<?php if($show_prices == true) { ?>
			<td class="textright"><?php print showPrice($cart['cart_qty'] * $this_price);?></td>
		<?php } ?>
</tr>
		<?php 
	}
		?>

<?php
function showOrderPhoto($cart,$show_prices,$parent,$order) { 
		global $setup,$site_setup,$cart_table;
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
		$pcart = doSQL($cart_table, "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
	}
	if($cart['cart_product_photo'] > 0) { 
		$pcart = doSQL($cart_table, "*", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$pcart['cart_store_product']."' ");
	}

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
		<tr valign="top"><td>

			<?php if($_REQUEST['thumbnails'] == "1") { ?>
			<div class="left" style=" width: 30%; text-align: center;">

			<?php 
					$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'].""); 
					?>
					<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb packingslipthumb" style=" max-width: 150px; width: 100%; height: auto;" >

					</div>

				<?php } ?>
					<div class="left">
						<div style="padding: 0 0 0 8px;">
						<?php if($cart['cart_package_photo'] > 0) { ?><div class="pc"><?php print $parent['cart_product_name'];?></div><?php } ?>
					<?php if($cart['cart_product_photo'] > 0) { ?><div class="pc"><?php print $pdate['date_title'];?></div><?php } ?>
							<div class="pc"><b><?php print $cart['cart_product_name'];?></b></div>

						<?php $cos = whileSQL($cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ");
						while($co = mysqli_fetch_array($cos)) {
						?>
						<div class="pc"><?php print $co['co_opt_name'];?> <?php print _selected_;?></div>

						<?php
					}

					?>

						<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."'  AND co_pic_id<='0' ");
						while($co = mysqli_fetch_array($cos)) { ?>
						<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; if($show_prices == true) {  
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
		
								
							}?></div>
						<?php } ?>

					<?php if($cart['cart_color_id'] > 0) { ?>
						<div class="pc">
						<?php print $cart['cart_color_name'];?>
						</div>
					<?php } ?>
					<div class="pc"><b><?php print $cart['cart_pic_org'];?></b></div>


					<div class="pc">In <?php print "".$date['date_title']."";?>
					
					<?php if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							print " > ".$upsub['sub_name']."  ";
						}
					}
					
					print " > ".$sub['sub_name']."";

				}
				?>
					
					
					
					</div>
				<?php if(!empty($cart['cart_notes'])) { ?>
				<div>&nbsp;</div>
				<div class="pc highlight"><span><img src="../graphics/icons/message.png"> <u> <?php print nl2br($cart['cart_notes']);?></u></span></div>
				<?php } ?>

				<?php 
				if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { ?>
					<div>&nbsp;</div><div class="pc highlight"><img src="../../sy-graphics/icons/crop.png" align="absmiddle"> <?php print _email_custom_crop_message_;?></div>
				<?php } 	?>

				</div>
			</div>
			<div class="clear"></div>
		
		</td>
		<?php if($show_prices == true) { ?>
			<td><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice($cart['cart_price']);?><?php } ?></td>
		<?php } ?>
		<td class="center"><?php print ($cart['cart_qty'] + 0);?></td>
		<?php if($show_prices == true) { ?>
			<td class="textright"><?php if((($cart['cart_package_photo'] > 0)||($cart['cart_product_photo'] > 0))&&($this_price <=0)==true) { ?>&nbsp;<?php } else { ?><?php print showPrice(($cart['cart_qty'] * $this_price) + $co_download);?><?php } ?></td>
		<?php } ?>
</tr>
		<?php 
	}


function showOrderPackage($pack,$cart,$show_prices) { 
	global $setup,$site_setup,$cart_table;
	$price = $cart['cart_price'];
	$this_price = $cart['cart_price'];
		?>
		<tr valign="top"><td>


					<div class="left">
						<div style="padding: 0 0 0 8px;">
						<div class="pc"><b><?php print $cart['cart_product_name'];?></b></div>
						<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' ");
						while($co = mysqli_fetch_array($cos)) { ?>
						<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; if($show_prices == true) {  
						if($co['co_price'] > 0) { 
							print " "._option_add_price_."".showPrice($co['co_price']); 
						}  
						if($co['co_price'] < 0) { 
							print " "._option_negative_price_."".showPrice(-$co['co_price']); 
						}					

							
							$this_price = $this_price + $co['co_price'];   } ?></div>
						<?php } ?>








				</div>
			</div>
			<div class="clear"></div>
		
		</td>
		<?php if($show_prices == true) { ?>
			<td><?php if($cart['cart_price'] > 0) { print showPrice($cart['cart_price']); } ?></td>
		<?php } ?>
		<td class="center"><?php print ($cart['cart_qty'] + 0);?></td>
		<?php if($show_prices == true) { ?>
			<td class="textright"><?php if($cart['cart_price'] > 0) { print showPrice($cart['cart_qty'] * $cart['cart_price']); } ?></td>
		<?php } ?>
</tr>
		<?php 
	}
		?>



<div>&nbsp;</div>
