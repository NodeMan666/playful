<?php 
$path = "../../";
require "../w-header.php"; 
$date = doSQL("ms_calendar", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified", "WHERE date_id='".$_REQUEST['date_id']."' ");

?>


<div class="left p45">
<div class="pc"><h1><?php 	print  number_format(countIt("ms_stats_site_pv", "WHERE pv_date BETWEEN CURDATE() - INTERVAL 30 DAY AND (CURDATE()) AND date_id='".$date['date_id']."' "))." views the last 30 days"; ?></h1></div>
<div class="pc">Date Created: <?php print $date['date_show_date']; ?></div>
<div class="pc">Last modified: <?php print $date['last_modified']; ?></div>

<?php $shares = countIt("ms_shares", "WHERE share_page='".$date['date_id']."' ");
if($shares > 0) { ?>
<div class="pc"><a href="index.php?do=stats&view=shares&share_page=<?php print $date['date_id'];?>"><?php print $shares;?> <?php if($shares == "1") { print "share"; } else { print "shares"; } ?></a></div>
<?php } ?>



<?php 
$free_downloads = countIt("ms_free_downloads", "WHERE free_date_id='".$date['date_id']."' ");
if($free_downloads > 0) { ?>
<div class="pc"><h2><?php print $free_downloads;?> Free Downloads</h2></div>
<?php
	$pics = whileSQL("ms_free_downloads LEFT JOIN ms_photos ON ms_free_downloads.free_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(free_date, INTERVAL 0 HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS date_show", "WHERE free_date_id='".$date['date_id']."' AND pic_id>='0' ORDER BY free_date DESC");
	while($pic = mysqli_fetch_array($pics)) {
		// $size = @GetImageSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
		?>
	<div class="underline">
	<div class="left p20"><img src="<?php print getimagefile($pic,'pic_mini');?>"></div>
	<div class="left p40">
	<?php if($pic['free_person'] > 0) { 
		$p = doSQL("ms_people", "*", "WHERE p_id='".$pic['free_person']."' ");
		print "<a href=\"index.php?do=people&p_id=".$p['p_id']."\">".$p['p_name']." ".$p['p_last_name']."</a>";
	} else { 
		print "<a href=\"index.php?do=stats&action=recentVisitors&q=".$pic['free_ip']."\">".$pic['free_ip']."</a>";
	}
	?>
	</div>
	<div class="left p40 textright"><?php print $pic['date_show'];?></div>
	<div class="clear"></div>
	<div><?php print $pic['pic_org'];?> <?php if($pic['free_zip'] == "1") { ?>(zip file)<?php } ?></div>
	</div>

	<?php } ?>


<?php } 
?>



</div>

<?php
function totalSales($date,$sub) { 
	if($sub['sub_id'] > 0) { 
		$and_sub = "AND cart_sub_gal_id='".$sub['sub_id']."' ";
	}
	$order_list = array();
	$carts = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*", "WHERE cart_pic_date_id='".$date['date_id']."' $and_sub AND cart_package>'0'  AND order_payment_status='Completed'  AND order_status<'2' ");
	while($cart = mysqli_fetch_array($carts)) { 
		if(!in_array($cart['order_id'],$order_list)) { 
			array_push($order_list,$cart['order_id']);
		}
		$package_sales = $package_sales + ($cart['cart_price'] * $cart['cart_qty']); 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	 }

	######## Archived Table ########
	$carts = whileSQL("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id", "*", "WHERE cart_pic_date_id='".$date['date_id']."' $and_sub AND cart_package>'0'  AND order_payment_status='Completed'  AND order_status<'2' ");
	while($cart = mysqli_fetch_array($carts)) { 
		if(!in_array($cart['order_id'],$order_list)) { 
			array_push($order_list,$cart['order_id']);
		}
		$package_sales = $package_sales + ($cart['cart_price'] * $cart['cart_qty']); 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	 }










	$carts = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id ", "*", "WHERE cart_pic_date_id='".$date['date_id']."'  $and_sub AND cart_pic_id>'0'  AND cart_product_photo='0' AND cart_package_photo='0' AND order_payment_status='Completed' AND order_status<'2'  ");
	while($cart = mysqli_fetch_array($carts)) {
		if(!in_array($cart['order_id'],$order_list)) { 
			array_push($order_list,$cart['order_id']);
		}
		$photo_sales = $photo_sales + ($cart['cart_price'] * $cart['cart_qty']); 

		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 
	
	######## Archived Table ########
	$carts = whileSQL("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id ", "*", "WHERE cart_pic_date_id='".$date['date_id']."'  $and_sub AND cart_pic_id>'0'  AND cart_product_photo='0' AND cart_package_photo='0' AND order_payment_status='Completed' AND order_status<'2'  ");
	while($cart = mysqli_fetch_array($carts)) {
		if(!in_array($cart['order_id'],$order_list)) { 
			array_push($order_list,$cart['order_id']);
		}
		$photo_sales = $photo_sales + ($cart['cart_price'] * $cart['cart_qty']); 

		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 



	$carts = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE ms_cart.cart_store_product='".$date['date_id']."'  $and_sub  AND ms_orders.order_payment_status='Completed' AND order_status<'2' GROUP BY order_id ");
	while($cart = mysqli_fetch_array($carts)) {
	if($cart['order_id'] > 0) { 
			if(!in_array($cart['order_id'],$order_list)) { 
				array_push($order_list,$cart['order_id']);
			}
		}
		$store_sales = $store_sales + $cart['total']; 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 
	######## Archived Table ########
	$carts = whileSQL("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id", "*,SUM(cart_qty*cart_price) AS total", "WHERE ms_cart_archive.cart_store_product='".$date['date_id']."'  $and_sub  AND ms_orders.order_payment_status='Completed' AND order_status<'2' GROUP BY order_id ");
	while($cart = mysqli_fetch_array($carts)) {
	if($cart['order_id'] > 0) { 
			if(!in_array($cart['order_id'],$order_list)) { 
				array_push($order_list,$cart['order_id']);
			}
		}
		$store_sales = $store_sales + $cart['total']; 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 


	$carts = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id ", "* ,SUM(cart_qty*cart_price) AS total", "WHERE cart_pic_date_id='".$date['date_id']."'  $and_sub AND cart_pic_id>'0'  AND cart_product_photo>'0' AND cart_package_photo='0' AND order_payment_status='Completed' AND order_status<'2' GROUP BY cart_product_photo ");
	while($cart = mysqli_fetch_array($carts)) {
		$scart = doSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id ", "* ,SUM(cart_qty*cart_price) AS total", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		if(!in_array($scart['order_id'],$order_list)) { 
			array_push($order_list,$scart['order_id']);
		}
		$store_photo_sales = $store_photo_sales + $scart['total']; 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 
	######## Archived Table ########
	$carts = whileSQL("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id ", "* ,SUM(cart_qty*cart_price) AS total", "WHERE cart_pic_date_id='".$date['date_id']."'  $and_sub AND cart_pic_id>'0'  AND cart_product_photo>'0' AND cart_package_photo='0' AND order_payment_status='Completed' AND order_status<'2' GROUP BY cart_product_photo ");
	while($cart = mysqli_fetch_array($carts)) {
		$scart = doSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id ", "* ,SUM(cart_qty*cart_price) AS total", "WHERE cart_id='".$cart['cart_product_photo']."' ");
		if(!in_array($scart['order_id'],$order_list)) { 
			array_push($order_list,$scart['order_id']);
		}
		$store_photo_sales = $store_photo_sales + $scart['total']; 
		$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ");
		while($co = mysqli_fetch_array($cos)) {
			$cart_options = $cart_options + ($cart['cart_qty'] * $co['co_price']);
		}
	} 


	foreach($order_list AS $order_id) { 
		$order = doSQL("ms_orders", "*", "WHERE order_id='".$order_id."' ");
		$discounts = $discounts + $order['order_discount'];
		$eb_discounts = $eb_discounts + $order['order_eb_discount'];
		$shipping = $shipping + $order['order_shipping'];
		$tax = $tax + $order['order_tax'];
		$vat = $vat + $order['order_vat'];
		$credit = $credit + $order['order_credit'];
		$gift_certificate = $gift_certificate + $order['order_gift_certificate'];

		$carts = whileSQL("ms_cart LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=ms_cart.cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name ");
			while($cart= mysqli_fetch_array($carts)) {
				$image_options = $image_options + $cart['this_price'];
		}
	}


	$total_sales = $store_sales + $photo_sales + $package_sales + $image_options + $store_photo_sales + $cart_options;
	$total[sales] = $total_sales;
	$total[eb_discounts] = $eb_discounts;
	$total[cart_options] = $cart_options;
	$total[shipping] = $shipping;
	$total[tax] = $tax;
	$total[vat] = $vat;
	$total[credit] = $credit;
	$total[gift_certificate] = $gift_certificate;
	$total[discounts] = $discounts;
	$total[image_options] = $image_options;
	$total[store_sales] = $store_sales;
	$total[store_photo_sales] = $store_photo_sales;
	$total[photo_sales] = $photo_sales;
	$total[package_sales] = $package_sales;
	$total[num_orders] = count($order_list);
	return $total;
}

$total = totalSales($date,$sub);
$total_sales = $total[sales];

if($total[sales] > 0) { ?>
<div class="right p45">
<div class="pc"><h1><a href="index.php?do=orders&date_id=<?php print $date['date_id'];?>"><?php print showPrice($total[sales]);?> Total Sales (<?php print $total[num_orders];?>)</a></h1></div>
<?php if($total[discounts] > 0) { ?><div class="pc">Discounts: (<?php print showPrice($total[discounts]); ?>) &nbsp; </div><?php } ?>
<?php if($total[eb_discounts] > 0) { ?><div class="pc">Early Bird Specials: (<?php print showPrice($total[eb_discounts]);?>)</div><?php } ?>
<?php if($total[credit] > 0) { ?><div class="pc">Credits (<?php print showPrice($total[credit]);?>)</div><?php } ?>
<?php if($total[gift_certificate] > 0) { ?><div class="pc"><?php print _gift_certificate_name_;?> (<?php print showPrice($total[gift_certificate]);?>)</div><?php } ?>
<?php if($total[shipping] > 0) { ?><div class="pc">Shipping: <?php print showPrice($total[shipping]);?></div><?php } ?>
<?php if($total[tax] > 0) { ?><div class="pc">Tax: <?php print showPrice($total[tax]);?></div><?php } ?>
<?php if($total[vat] > 0) { ?><div class="pc">Vat: <?php print showPrice($total[vat]);?></div><?php } ?>


<div class="underline"><a href="index.php?do=orders&date_id=<?php print $date['date_id'];?>">Click here to view purchases</a></div>

<?php 



$qry = mysqli_query($dbcon,"(SELECT * FROM ms_cart LEFT JOIN 
ms_photo_products ON ms_cart.cart_photo_prod=ms_photo_products.pp_id 
LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id 
WHERE cart_pic_date_id='".$date['date_id']."' AND cart_order>'0'  AND cart_photo_prod!='0'  AND order_payment_status='Completed' AND order_status<'2') 
UNION
(SELECT * FROM ms_cart_archive LEFT JOIN 
ms_photo_products ON ms_cart_archive.cart_photo_prod=ms_photo_products.pp_id 
LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id 
WHERE cart_pic_date_id='".$date['date_id']."' AND cart_order>'0'  AND cart_photo_prod!='0'  AND order_payment_status='Completed' AND order_status<'2') ");
if (!$qry) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }
while($total_sold = mysqli_fetch_array($qry)) { 
	$ptotal = $ptotal + $total_sold['cart_qty'];
}

if($ptotal > 0) { 
?>
<div class="pc">Total photos sold: <?php print $ptotal;?></div>
<!-- <div class="pc">Total downloads sold: <?php print $total_download_sold;?></div> -->
<?php 
$prods = mysqli_query($dbcon,"

SELECT *, SUM(dups) AS dups FROM
(
    SELECT *, SUM(cart_qty) AS 'dups' FROM ms_cart 
	LEFT JOIN ms_photo_products ON ms_cart.cart_photo_prod=ms_photo_products.pp_id 
	LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id 
	WHERE cart_pic_date_id='".$date['date_id']."' AND cart_order>'0'  AND cart_photo_prod!='0'  AND order_payment_status='Completed' AND order_status<'2' GROUP BY cart_photo_prod 
  UNION ALL
    SELECT *, SUM(cart_qty) AS 'dups' FROM ms_cart_archive 
	LEFT JOIN ms_photo_products ON ms_cart_archive.cart_photo_prod=ms_photo_products.pp_id 
	LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id 
	WHERE cart_pic_date_id='".$date['date_id']."' AND cart_order>'0'  AND cart_photo_prod!='0'  AND order_payment_status='Completed' AND order_status<'2' GROUP BY cart_photo_prod 
) `x`
GROUP BY cart_photo_prod ORDER BY dups DESC ");
if (!$prods) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

while($prod = mysqli_fetch_array($prods)) { 
	//if(!empty($prod['pp_id'])) { ?>
<div class="underline"><div class="left p15"><?php print ($prod['dups'] * 1);?></div><div class="left  p85"><?php print $prod['pp_name'];?></div>
<div class="clear"></div>
</div>
<?php 
	//}
}
?>

<?php }?>
<?php 
$store_photo_orders = array();
$store_prod = array();

	$carts = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id ", "* ,SUM(cart_qty*cart_price) AS total", "WHERE cart_pic_date_id='".$date['date_id']."'  $and_sub AND cart_pic_id>'0'  AND cart_product_photo>'0' AND cart_package_photo='0' AND order_payment_status='Completed' AND order_status<'2' GROUP BY cart_product_photo ");
	while($cart = mysqli_fetch_array($carts)) {
		if(!in_array($cart['cart_product_photo'],$store_photo_orders)) { 
			array_push($store_photo_orders,$cart['cart_product_photo']);
			$scarts = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*,SUM(cart_qty) AS  dups", "WHERE cart_id='".$cart['cart_product_photo']."' ");
			while($scart = mysqli_fetch_array($scarts)) { 
				if(!in_array($scart['date_id'],$store_prod)) { 
					array_push($store_prod,$scart['date_id']);
					$cart_product_name = $scart['cart_product_name'];
					$prod_total = $prod_total + $scart['dups'];
				}
				?>
		<div class="underline"><div class="left p15"><?php print ($prod_total * 1);?></div><div class="left  p85"><?php print $cart_product_name;?></div>
		<div class="clear"></div>
		</div>
			<?php 
			}
			?>
		<?php } ?>

		<?php 	} ?> 
<?php 
$subs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."'  AND sub_under<='0' ORDER BY sub_name ASC ");
	if(mysqli_num_rows($subs) > 0) { ?> 
	<div>&nbsp;</div>
	<div class="underlinelabel">Sales From Sub Galleries *</div>
	<?php
	$sub_gals = true;} ?>

	<?php 
	while($sub = mysqli_fetch_array($subs)) { 
	$total = totalSales($date,$sub);
	?>
<div class="underline"><div class="left"><?php print $sub['sub_name'];?></div><div class="right textright"><?php print showPrice($total['sales']);?></div><div class="clear"></div></div>

<?php getSubSubs($date,$sub['sub_id']); ?>
<?php } ?>
<?php if($sub_gals == true) { ?><div class="underline">* The sales from sub galleries gives you a general idea and is the total sales of products. It does not take into account any discounts, taxes, shipping, etc ... </div><?php } ?>
</div>
<?php } ?>
<div class="clear"></div>


<?php 
function getSubSubs($date,$sub_id) { 
$subs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$sub_id."' ORDER BY sub_name ASC ");
while($sub = mysqli_fetch_array($subs)) { 
	$i = 0;
	$total = totalSales($date,$sub);
	$ct = explode(",",$sub['sub_under_ids']);
	?>
<div class="underline"><div class="left"><?php while($i < count($ct)) { print "--"; $i++; } print "> ".$sub['sub_name'];?></div><div class="right textright"><?php print showPrice($total['sales']);?></div><div class="clear"></div></div>
<?php
	 getSubSubs($date,$sub['sub_id']);
	} 
}
?>

<?php require "../w-footer.php"; ?>
