<?php
$path = "../../";
require "../w-header.php"; ?>
<style>
#viewcart .cartitem { filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffF2F2F2,endColorstr=#ffF2F2F2); background-color: rgba(242,242,242,1.00); border: 1px solid #C7C7C7; color: #575757; -moz-border-radius: 2px; border-radius: 2px; height: 1%; margin: 4px 4px 20px 0px; padding: 18px; } #viewcart .cartitem a { color: #000000; } #viewcart .cartitem a:hover { color: #5290C7; } .preview .image { } #viewcart .cartitem .thumbnail { float: left; width: 30%; padding-right: 12px; text-align: center; } #viewcart .cartitem .thumbnail .thumb { border: 1px solid #545454; } #viewcart .cartitem .product { float: left; width: 50%; } #viewcart .cartitem .options { padding: 4px; } #viewcart .cartitem .qty { padding: 4px; } #viewcart .cartitem .name { padding: 4px; font-size: 22px; font-family: 'Oswald', Arial; } #viewcart .cartitem .topname { padding: 4px; } #viewcart .cartitem .price { float: right; width: 10%; text-align: right; } #viewcart .cartitem .extprice { font-size: 22px; font-family: 'Oswald', Arial; } #viewcart .cartitem .remove { padding: 4px; } #orderLogin { width: 60%; margin: auto; } #orderitems .item { filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffF2F2F2,endColorstr=#ffF2F2F2); background-color: rgba(242,242,242,1.00); border: 1px solid #C7C7C7; color: #575757; -moz-border-radius: 2px; border-radius: 2px; height: 1%; margin: 4px 4px 20px 0px; padding: 18px; } #orderitems .item a { color: #000000; } #orderitems .item a:hover { color: #5290C7; }

.cropselect { display: none; } 
.hide { display: none; } 
</style>
<script>
function deletecart(ses) { 
	$.get("admin.actions.php?action=deletecart&cart_session="+ses+"", function(data) {
		location.reload();
	});
}

function deletecartclient(client) { 
	$.get("admin.actions.php?action=deletecartclient&cart_client="+client+"", function(data) {
		location.reload();
	});

}



</script>

<?php
define(_qty_,"qty");
define(_store_cart_title_,"Shopping Cart");
define(_no_photo_selected_,"No photo selected");
define(_item_,"item");
define(_items_,"items");
define(_in_,"in");
define(_collection_credit_cart_message_," credit included to use toward purchasing other photos");
define(_cart_selected_package_photos_,"Selected photos for the above collection.");
define(_remove_from_cart_,"");
define(_adjust_crop_,"");
define(_subtotal_,"Sub total");
define(_promo_code_,"Promo Code");
define(_promo_savings_ ,"With a savings of");
define(_each_,"each");
define(_print_credit_ ,"Print Credit");
define(_of_ ,"of");
define(_selected_ ,"selected");
define(_add_notes_ ,"Notes");
define(_update_cart_note_ ,"");

?>

<div id="storeProductList">

<?php 

$total = homeShoppingCartTotal($_REQUEST['cart_client'],$_REQUEST['cart_session']);
$pay_total = $total['show_cart_total'];

?>

<div class="pc title"><h1><?php print _store_cart_title_; ?> </h1></div>
<?php if($total['total_items'] > 0) { ?>
<div class="pc"><h3 id="viewcartpagetotal"><?php print "".$total['total_items']." "; if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']); ?></h3></div>
<?php } ?>


<?php
function checkCartAdmin() {
	if(empty($_REQUEST['cart_client'])) {
		return "cart_session='".$_REQUEST['cart_session']."' AND cart_client='' ";
	} else {
		return "cart_client='".$_REQUEST['cart_client']."' ";
	}
}

$ckcart = doSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
// print "<li>".$ckcart['cart_session'];
if(!empty($ckcart['cart_session'])) { 
?>
<div class="pc"><a href="" onclick="deletecart('<?php print $ckcart['cart_session'];?>'); return false;" class="tip" title="This will delete this shopping cart">delete shopping cart now</a></div>
<?php } else if(!empty($ckcart['cart_client'])) { ?>
<div class="pc"><a href="" onclick="deletecartclient('<?php print $ckcart['cart_client'];?>'); return false;" class="tip" title="This will delete this shopping cart">delete shopping cart now</a></div>

<?php } ?>
<div id="viewcart">
<?php
$action = "viewcart";
$tracknum = 0;
$no_actions = true;

$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_package!='0' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
$tracks_total	= mysqli_num_rows($carts);
while($cart= mysqli_fetch_array($carts)) {
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
	$tracknum++;
	showPhotoPackage($pack,$cart);
	?>
	<div class="pc"><?php print _cart_selected_package_photos_; ?></div>
	<?php 
	$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_order<='0'  ORDER BY cart_pic_org ASC" );
		while($pcart= mysqli_fetch_array($pcarts)) {
		$tracknum++;
		showPhotoProduct($pcart,"1",$cart);
	}
}

$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_photo_prod!='0' AND cart_package_photo='0' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$tracknum++;
	showPhotoProduct($cart, "0","");
}


$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_store_product!='0' AND cart_order<='0' ORDER BY cart_id DESC" );
$tracks_total	= mysqli_num_rows($carts);
	while($cart= mysqli_fetch_array($carts)) {
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
	$tracknum++;
	showStoreProduct($date,$cart);
	$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartAdmin()." AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0' AND cart_order<='0'  ORDER BY cart_pic_org ASC" );
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
?>
</div>

<?php if($empty_cart!==true) { ?>
<div class="pc textright" id="viewcartpagesubtotal"><?php print _subtotal_." ".showPrice($total['show_cart_total']); ?></div>
<?php } ?>
<?php 
$check_in_cart = doSQL("ms_cart", "cart_id,cart_coupon,cart_session", "WHERE ".checkCartAdmin()." AND cart_coupon!='0' AND cart_order='0'  ");
?>
<?php if(!empty($check_in_cart['cart_id'])) { ?>
<?php // PROMO COUPONS ?>
<?php
$action = "viewcart";
$tracknum = 0;

$carts = whileSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartAdmin()." AND cart_coupon!='0' AND cart_order<='0' AND code_print_credit<='0' " );
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
		<div class="pc"><h3><?php print _promo_code_." ".$promo['code_name']." [".$promo['code_code']."]  "; if($total['promo_discount_amount'] > 0) { if($promo['code_discount_amount'] > 0) { print $promo['code_discount_amount']."% "; } print _promo_savings_." ".showPrice($total['promo_discount_amount'])."" ; }  ?></h3></div>
		<?php if(!empty($promo['code_descr'])) { ?><div class="pc"><?php print nl2br($promo['code_descr']); ?></div><?php } ?>
		<div class="pc"><?php print "<a href=\"/".$setup['temp_url_folder']."".$site_setup['index_page']."?view=cart&action=removePromo&cid=".MD5($cart['cart_id'])."\">"._remove_from_cart_."</a>"; ?></div>
	</div>
		<div class="clear">&nbsp;</div>
	<div>&nbsp;</div>
	<?php } ?>
<?php } ?>
<?php require "../w-footer.php"; ?>