<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header('Expires: 0'); // Proxies.
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header('Content-Type: text/html; charset=utf-8');
header('Pragma: no-cache'); // HTTP 1.0.
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
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


$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($date['date_photo_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
}

if((!empty($_REQUEST['sub_id'])) && (is_numeric($_REQUEST['sub_id'])) == true) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	if($sub['sub_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
	}
	if(($sub['sub_price_list'] <= 0) && ($sub['sub_under'] > 0) == true) { 
		$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_under']."' ");
		if($upsub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$upsub['sub_price_list']."' ");
		}
	}
}
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}


$total = shoppingCartTotal($mssess);
$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
if($freedownload['pp_id'] > 0) { 
	$free = $freedownload['pp_id'];
}
if(customerLoggedIn()) { 
	$gal_free = doSQL("ms_gallery_free", "*", "WHERE free_person='".$person['p_id']."' AND ((free_gallery='".$date['date_id']."' AND free_sub='0') OR (free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."')) ");
	if($gal_free['free_id'] > 0) {
		$free = $gal_free['free_product'];
	}
}


?>

<?php // to hide on standard galleries, add to left hidesmall below .... if((empty($list['list_id']))&&($date['cat_type'] !== "proofing")==true) { print "display: none;"; } ?>

<div id="ssheaderinner">
	<div class="left hidesmall" style="width: 20%;" id="viewphotofullscreencount">
		<div style="padding: 8px 8px 4px 8px ;overflow: hidden;">
			<!-- <nobr><span  id="phototopcount"></span> &nbsp; <span id="photofilename"></span></nobr> -->
			<nobr><span></span> &nbsp; <span></span></nobr>
		</div>
		<?php if($date['date_gallery_exclusive'] <= 0) { ?>
		<?php if($_REQUEST['view'] == "favorites") { ?><div style="padding: 0px 8px;<?php if($_REQUEST['view'] !== "favorites") { print "display: none;"; } ?>" id="photopagelink"></div>&nbsp;<?php } ?>
		<?php } ?>
		<div id="photographerfavoritefull"><span id="photographerfavoritefullstar" class="the-icons icon-star hide"> <?php print $date['star_text'];?></span></div>
	</div>
	<div class="left center nofloatsmall" style="width: 60%; margin-left: -20px; ">

	<?php 
	$get_color_from = "ssheader";
	if($date['cat_type'] == "proofing") { 
		include $setup['path']."/sy-inc/store/store_photos_proofing.php"; 
	} else { 
		include $setup['path']."/sy-inc/store/store_photos_menu_actions.php"; 
	}
	?>
	</div>

	<div style="width: 20%;"  class="right textright hidesmall">
		<?php if($_REQUEST['need-login'] == "1") { 
			$_SESSION['return_page'] = $setup['temp_url_folder']."".$date['cat_folder']."/".$date['date_link']."/"; 
			?>
			<?php
			$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_login_to_buy_photos_);
			$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
			print $message ;				
			?>
		<?php } else { ?>
		<div style="padding: 8px 0 0 0;  <?php if($total['total_items'] <= 0) { print "display: none;"; } ?>"  id="viewphotocarttotal">
		<a href="/<?php print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><?php print _view_cart_;?></a> &nbsp; 
		<a href="/<?php print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><span id="photobuycarttotal"><?php if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; } ?></span></a>
		<?php $total_favs = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC "); ?>
		</div>
		<?php
		if(!empty($_SESSION['last_gallery'])) { 
			$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		}
		?>
		<div class="favoritesviewing <?php if($total_favs <= 0 ) { ?>hide<?php } ?>" style="padding: 8px 0 0 0;"><a href="<?php if((!empty($ldate['date_id'])) && ($date['date_gallery_exclusive'] <= 0) == true) { print $setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites"; } else { print "index.php?view=favorites"; } ?>"><span class="icon-heart the-icons icon-fav"></span><?php print _my_favorites_;?> <?php print "(<span id=\"favoritestotaltop\">".$total_favs."</span>)</a>"; ?></div>

		<?php } ?>
	<div class="clear"></div>

</div>
	<div class="clear"></div>
	<?php if($date['date_owner'] > 0) { ?>
	<div  id="hidden-full-photo"><?php print _hidden_photo_;?></div>
	<?php } ?>
<?php  mysqli_close($dbcon); ?>
