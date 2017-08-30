<?php

#### Checking to see if visitor  has access yet to gallery ####

if($date['private']>="1"){ 
	if(isset($_SESSION['office_admin_login'])) { 
		// print "<div class=\"pc center\"><i>This is a password protected page but since you are logged into the admin you have direct access.</i></div>";
	} else if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) && ($person['p_id'] > 0) == true) { 

	} else { 
		if(!is_array($_SESSION['privateAccess'])) {
			$_SESSION['privateAccess'] = array();
		}
		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				$need_password = true;
			} 
		} else { 
			if(!in_array($date['date_id'],$_SESSION['privateAccess'])) {
				$need_password = true;
			}
		}
	}
}
if(!empty($_REQUEST['sub'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
	$ids = explode(",",$sub['sub_under_ids']);
	if(!empty($sub['sub_pass'])){ 
		$pass = $sub['sub_pass'];
		$sub_pass_id = $sub['sub_id'];
	} 
	if(empty($pass)) { 
		foreach($ids AS $val) { 
			if($val > 0) { 
				$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
				if(!empty($upsub['sub_pass'])) { 
					$pass = $upsub['sub_pass'];
					$sub_pass_id = $upsub['sub_id'];
				}
			}
		}
	}

	if(!empty($pass)){ 
		if(isset($_SESSION['office_admin_login'])) { 
			// print "<div class=\"pc center\"><i>This is a password protected page but since you are logged into the admin you have direct access.</i></div>";
		} else if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) && ($person['p_id'] > 0) == true) { 

		} else { 
			if(!is_array($_SESSION['privateAccess'])) {
				$_SESSION['privateAccess'] = array();
			}
			if(customerLoggedIn()) { 
				$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND mp_sub_id='".$sub_pass_id."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
				if(empty($cka['mp_id'])) { 
					$need_password = true;
					// exit();
				} 
			} else { 
				if(!in_array("sub".$sub_pass_id,$_SESSION['privateAccess'])) {
					$need_password = true;
					// exit();
				}
			}
		}
	}
}

############ Check if free downloads are available #################
if(function_exists('zip_open')) { 
	if($date['date_photo_price_list'] > 0) { 
		$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
		$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
	}
	if($sub['sub_price_list'] > 0) { 
		$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$sub['sub_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
		$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$sub['sub_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
	}

	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($person['p_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
			$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$list['list_id']."' AND pp_free='1' AND pp_free_all='1' ");
			$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$list['list_id']."' AND pp_free='1' AND pp_free_all='1' ");
		}
	}

	if(customerLoggedIn()) { 
		$gal_free = doSQL("ms_gallery_free", "*", "WHERE free_person='".$person['p_id']."' AND ((free_gallery='".$date['date_id']."' AND free_sub='0') OR (free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."')) ");
		if($gal_free['free_id'] > 0) {
			$ckfree = 1;
			$prod = doSQL("ms_photo_products", "*","WHERE pp_id='".$gal_free['free_product']."' ");
		}
	}

	if($ckfree > 0) { 
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
			$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
			$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
		}
		$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  ");
		$total_images = mysqli_num_rows($pics);
		if($total_images > 0) { 
			$download_free_zip = true;
		} 
	} 
} 


$total = shoppingCartTotal($mssess);
$photog_favs = countIt("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ",  "WHERE pic_fav_admin='1' AND bp_blog='".$date['date_id']."'  ");
?>

<?php if($need_password == true) { ?>
<?php if(($ge['gal_show_cover_password'] == "1") && ($date['date_gallery_exclusive_no_cover'] !== "1") == true){ ?>
	<?php 
	if(!isset($_SESSION['passcode'])) { 
	if(!empty($date['date_id'])) { 
		if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true){ ?>
	<?php $pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 "); 
			if(empty($pic['pic_id'])) { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			}
		}
	?>
	<?php if((!empty($pic['pic_id'])) && (empty($_REQUEST['sub'])) && (empty($_REQUEST['view']))  == true) { ?>
	<img id="bigphotoholder" src="<?php print getimagefile($pic,'pic_large');?>" class="hide">
	<div class="photo-header" id="photo-header" data-gallery-login="1">
		<div class="loadingspinner" id="bigphotoloading" style="position: absolute; top: 30%; left: 50%; margin-left: -30px;"></div>
		<div id="bigphoto1" class="image hide bigphotopic" style='background-image: url("<?php print getimagefile($pic,'pic_large');?>"); transform: translate3d(0px, 0%, 0px); '></div>
	</div>
	<?php } ?>
	<?php }  ?>
	<?php } ?>
	<?php // exit(); ?>
<?php } ?>
<div>&nbsp;</div>
<div class="pc center"><h1><?php print $date['date_title'];?></h1></div>
<?php if(!empty($sub['sub_name'])) { ?><div class="pc center"><h2><?php print $sub['sub_name'];?></h2></div> <?php } ?>
<?php } ?>

<?php if($need_password !== true) { ?>
<div id="galleryshare"  class="gallerypopup">
	<div style="padding: 24px; position: relative;" class="inner">
	<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
	<div class="pc center"><h2><?php print $ge['share_on'];?></h2></div>
	<?php if($date['private'] > 0) { 
	$fb['page_share_text'] = str_replace("[PASSWORD]",$date['password'],$fb['page_share_text']);
	if($date['date_paid_access'] <= 0) { 
	?>
	<div class="pc center"><?php print nl2br($fb['page_share_text']); ?></div>
	<?php
	}
	} ?>
	<?php
	$share_link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/";
	$fb = doSQL("ms_fb", "*", ""); 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		$pic['full_url'] = true;
		$fb_thumb =getimagefile($pic,'pic_large');
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		$pic['full_url'] = true;
		if(!empty($pic['pic_id'])) {
			$fb_thumb =getimagefile($pic,'pic_large');
		}
	}
?>
	<ul>
	<li><a href=""  onclick="sharepage('facebook','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-facebook the-icons"><?php print $ge['facebook'];?></a></li>
	<li><a href=""  onclick="sharepage('pinterest','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"  class="icon-pinterest the-icons"><?php print $ge['pinterest'];?></a></li>
	<li><a href=""   onclick="sharepage('twitter','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-twitter the-icons"><?php print $ge['twitter'];?></a></li>
	<li><a href="" onclick="sharepage('email','<?php print $share_link;?>','','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-mail the-icons"><?php print $ge['email'];?></a></li>
	</ul>	</div>
</div>

<div id="gallerysubs"  class="gallerypopup">
	<div style="padding: 24px; position: relative;" class="inner">
	<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
		<ul><?php
			$msubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ");
			while($msub = mysqli_fetch_array($msubs)) {
			?>
			<li><a href="index.php?sub=<?php print $msub['sub_link'];?>"><?php print $msub['sub_name'];?></a></li>
			<?php }  ?>
		</ul>
	</div>
</div>
<div id="galleryheader" class="galleryheader" data-wall-designer="<?php if($_REQUEST['view'] == "room") { ?>1<?php } ?>">
	<div class="inner">
		<div class="logo">
		<p><?php print $date['date_title'];?></p>
		<p><a href="<?php if(!empty($ge['gal_site_link'])) { print $ge['gal_site_link']; } else { print $setup['temp_url_folder']."/"; } ?>"><?php print $ge['gal_site_title'];?></a></p>
		</div>
		<div class="menuouter">
			<div class="menu">
		<ul class="hidesmall">

			<?php
			$msubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ");
			if(mysqli_num_rows($msubs) <= 0) { ?>
			<?php if(($date['add_highlight_link'] == "1") && ($photog_favs  > 0)  == true) { ?>
			<li><a href="index.php" <?php if(empty($_REQUEST['view'])) { ?>class="on"<?php } ?>><?php print $ge['all_photos'];?></a></li>
			<li><a href="?view=highlights" <?php if($_REQUEST['view'] == "highlights") { ?>class="on"<?php } ?>><?php print $date['highlights_text'];?></a></li>
			<?php } else { ?>
			<li><a href="index.php" <?php if(empty($_REQUEST['view'])) { ?>class="on"<?php } ?>><span class=" the-icons icon-home"></span></a><span  class="title"><?php print $ge['all_photos'];?></span></li>
			<?php } ?>


			<?php } else { ?>

			<?php

			if(($date['open_highlights'] == "1") && ($photog_favs > 0) == true) { 
				$home_added = true;
				?>
			<li><a href="index.php" class="<?php if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true) { ?>on<?php } ?>"><?php print $date['highlights_text'];?></a></li>
			<?php } ?>


			<?php if($home_added !== true) { ?><li><a href="index.php" class="<?php if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true) { ?>on<?php } ?>"><span class=" the-icons icon-home"></span></a><span  class="title"><?php print $ge['gallery_home'];?></span></li><?php } ?>

			<?php } ?>

				<?php 
				while($msub = mysqli_fetch_array($msubs)) {
				$ms++;
				if($ms <= $ge['gal_sub_gal_limit']) { 
				?>
				<li><a href="index.php?sub=<?php print $msub['sub_link'];?>" <?php if($msub['sub_id'] == $sub['sub_id']) { ?>class="on"<?php } ?>><?php print $msub['sub_name'];?></a></li>
				<?php 
					}
				}  
				?>
				<?php if(mysqli_num_rows($msubs) > $ge['gal_sub_gal_limit']) { ?>
				<li><a href="" onclick="showgallerysubs(); return false;"><span class="the-icons icon-down-open" ></span><?php print $ge['more'];?></a></li>
				<?php } ?>
				 <?php if($date['allow_favs'] == "1") { ?>
				<?php $total_favs = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC "); ?>
				<li><a href="index.php?view=favorites" <?php if($_REQUEST['view'] == "favorites") { ?>class="on"<?php } ?> <?php if(!customerLoggedIn()) { ?> onclick="showgallerylogin('favorites','<?php print $sub['sub_link'];?>','',''); return false;" <?php } ?>><span id="" class="the-icons icon-heart"></span><span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print $total_favs + 0;?></span></span></a><span  class="title"><?php print $ge['my_favorites'];?></span></li>
				<?php } ?>
				<?php if(($date['date_photo_price_list'] > 0) && ($date['date_photo_price_list'] !== "99999999") == true) { 
				 $pprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free!='1' ");
				 $packprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$date['date_photo_price_list']."' AND pc_package>'0' "); 
				if(($pprods + $packprods) > 0) {
				?>
				<li id="viewcartlink"><a href="<?php print $setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart"?>" onClick="viewcart(); return false;" <?php if($_REQUEST['view'] == "cart") { ?>class="on"<?php } ?>><span class="the-icons icon-basket"></span><span class="numberCircle"><span class="height_fix"></span><span class="content carttotalcircle"><?php print $total['total_items'] + 0;?></span></span></a><span  class="title"><?php print $ge['my_cart'];?></span></li>
				<?php } ?>
				<?php } ?>
				<?php if($download_free_zip == true) {
				if($_REQUEST['view'] == "favorites") { 
					$view = "favorites";
				}
				?>
				<li><a href="" onclick="freedownloadall('<?php print $date['date_id'];?>','<?php print $sub['sub_id'];?>','<?php print $view;?>'); return false;" ><span class="icon-download the-icons"></span></a><span  class="title"><?php print _download_all_free_;?></span></li>
				<?php } ?>
				<?php if(($date['page_disable_fb'] !== "1") && ($ge['gal_disable_share'] !== "1") == true) { ?>
				<li class="geshare"><a href="" onclick="showgalleryshare(); return false;" ><span class="the-icons icon-share"></span></a><span  class="title"><?php print $ge['share'];?></span></li>
				<?php } ?>
				<li><a href="index.php?view=account" <?php if(!customerLoggedIn()) { ?> onclick="showgallerylogin('<?php print $_REQUEST['view'];?>','<?php print $sub['sub_link'];?>','',''); return false;" <?php } ?> <?php if($_REQUEST['view'] == "account") { ?>class="on"<?php } ?>><span class="the-icons icon-user"></span></a><span  class="title"><?php print $ge['my_account'];?></span></li>
				<?php if($ge['gal_contact_page'] > 0) { ?><li><a href="index.php?view=contact" <?php if($_REQUEST['view'] == "contact") { ?>class="on"<?php } ?>><span class="the-icons icon-mail"></span></a><span  class="title"><?php print $ge['contact'];?></span></li><?php } ?>
				<?php if($ge['gal_redeem_credit'] == "1") { ?>
				<li><a href="" onclick="redeemprintcredit(); return false;" ><?php print $ge['redeem_credit'];?></a></li>
				<?php } ?>
				<?php if($ge['gal_redeem_coupon'] == "1") { ?>
				<li><a href="" onclick="redeemcoupon('<?php print $_REQUEST['view'];?>',''); return false;" ><?php print $ge['redeem_coupon'];?></a></li>
				<?php } ?>

		</ul>
		<ul class="showsmall" style="float: right; ">
			<li style="float: right;"><span onclick="showgallerymobilemenu(); return false;" class="the-icons icon-menu gallerymenuicon"></span></li>
		</ul>
		</div>
	</div>
	<div class="clear"></div>
	</div>
</div>


<div id="gallerymobileheader" class="showsmall">
	<div class="inner"><ul>
				 <?php if($date['allow_favs'] == "1") { ?>
				<?php $total_favs = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC "); ?>
				<li><a href="index.php?view=favorites" <?php if($_REQUEST['view'] == "favorites") { ?>class="on"<?php } ?> <?php if(!customerLoggedIn()) { ?> onclick="showgallerylogin('favorites','<?php print $sub['sub_link'];?>','',''); return false;" <?php } ?>><span id="" class="the-icons icon-heart"></span><span class="numberCircle"><span class="height_fix"></span><span class="content favoritestotaltop"><?php print $total_favs + 0;?></span></span></a></li>
				<?php } ?>
				<?php if(($date['date_photo_price_list'] > 0) && ($date['date_photo_price_list'] !== "99999999") == true) {
				if(($pprods + $packprods) > 0) {				
				?>
				<li id="viewcartlink"><a href="<?php print $setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart"?>" onClick="viewcart(); return false;" <?php if($_REQUEST['view'] == "cart") { ?>class="on"<?php } ?>><span class="the-icons icon-basket"></span><span class="numberCircle"><span class="height_fix"></span><span class="content carttotalcircle"><?php print $total['total_items'] + 0;?></span></span></li>
				<?php } ?>
				<?php } ?>
				<?php if($download_free_zip == true) {
				if($_REQUEST['view'] == "favorites") { 
					$view = "favorites";
				}
				?>
				<li><a href="" onclick="freedownloadall('<?php print $date['date_id'];?>','<?php print $sub['sub_id'];?>','<?php print $view;?>'); return false;" ><span class="icon-download the-icons"></span></a></li>
				<?php } ?>

				<?php if(($date['page_disable_fb'] !== "1") && ($ge['gal_disable_share'] !== "1") == true) { ?>
				<li><a href="" onclick="showgalleryshare(); return false;" ><span class="the-icons icon-share"></span></a></li>
				<?php } ?>
				<li><a href="index.php?view=account" <?php if(!customerLoggedIn()) { ?> onclick="showgallerylogin('<?php print $_REQUEST['view'];?>','<?php print $sub['sub_link'];?>','',''); return false;" <?php } ?> <?php if($_REQUEST['view'] == "account") { ?>class="on"<?php } ?>><span class="the-icons icon-user"></span></a></li>
				<?php if($ge['gal_contact_page'] > 0) { ?><li><a href="index.php?view=contact" <?php if($_REQUEST['view'] == "contact") { ?>class="on"<?php } ?>><span class="the-icons icon-mail"></span></a></li><?php } ?>
				<?php if(!empty($ge['gal_phone'])) { ?><li><a href="tel:<?php print $ge['gal_phone'];?>"><span class="the-icons icon-phone"></span></a></li><?php } ?>
				</ul>
	</div>
</div>

<?php ## Mobile Menu ### ?>
<div id="gallerymobilemenu" class="showsmall">
	<div class="menu hide" style="display: none; ">
	<ul>
		<?php if($ge['gal_redeem_credit'] == "1") { ?>
		<li><a href="" onclick="redeemprintcredit(); return false;" ><?php print $ge['redeem_credit'];?></a></li>
		<?php } ?>
		<?php if($ge['gal_redeem_coupon'] == "1") { ?>
		<li><a href="" onclick="redeemcoupon('<?php print $_REQUEST['view'];?>',''); return false;" ><?php print $ge['redeem_coupon'];?></a></li>
		<?php } ?>
			<?php
			$msubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ");
			if(mysqli_num_rows($msubs) <= 0) { ?>
			<?php if(($date['add_highlight_link'] == "1") && ($photog_favs  > 0)  == true) { ?>
			<li><a href="index.php" <?php if(empty($_REQUEST['view'])) { ?>style="font-weight: bold;"<?php } ?>><?php print $ge['all_photos'];?></a></li>
			<li><a href="?view=highlights" <?php if($_REQUEST['view'] == "highlights") { ?>class="on"<?php } ?>><?php print $date['highlights_text'];?></a></li>
			<?php } else { ?>
			<li><a href="index.php" <?php if(empty($_REQUEST['view'])) { ?>style="font-weight: bold;"<?php } ?>><span class=" the-icons icon-home"></span> <?php print $ge['all_photos'];?></a></li>
			<?php } ?>


			<?php } else { ?>

			<?php

			if(($date['open_highlights'] == "1") && ($photog_favs > 0) == true) { 
				$home_added = true;
				?>
			<li><a href="index.php"<?php if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true) { ?>style="font-weight: bold;"<?php } ?>><?php print $date['highlights_text'];?></a></li>
			<?php } ?>


			<?php if($home_added !== true) { ?><li><a href="index.php" <?php if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true) { ?>style="font-weight: bold;"<?php } ?>><span class=" the-icons icon-home"></span></a><span  class="title"><?php print $ge['gallery_home'];?></span></li><?php } ?>

			<?php } ?>
			<?php 
			while($msub = mysqli_fetch_array($msubs)) {
			$ms++;?>
			<li><a href="index.php?sub=<?php print $msub['sub_link'];?>" <?php if($msub['sub_id'] == $sub['sub_id']) { ?>style="font-weight: bold;"<?php } ?>><?php print $msub['sub_name'];?></a></li>
			<?php }  ?>
		<?php $total_favs = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC "); ?>
	</ul>
	</div>
</div>

<?php 
if(!isset($_SESSION['passcode'])) { 
if(!empty($date['date_id'])) { 
	if((empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) == true){ ?>
<?php $pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 "); 
		if(empty($pic['pic_id'])) { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		}
	}
?>
<?php if((!empty($pic['pic_id'])) && (empty($_REQUEST['sub'])) && (empty($_REQUEST['view']))  && ($date['date_gallery_exclusive_no_cover'] !== "1")  == true) { ?>
<img id="bigphotoholder" src="<?php print getimagefile($pic,'pic_large');?>" class="hide">
<div class="photo-header" id="photo-header">
	<div class="loadingspinner" id="bigphotoloading" style="position: absolute; top: 30%; left: 50%; margin-left: -30px;"></div>
	<div id="bigphoto1" class="image hide bigphotopic" style='background-image: url("<?php print getimagefile($pic,'pic_large');?>"); transform: translate3d(0px, 0%, 0px); '></div>
	<div class="title" style="opacity: 0;" >
		<h1><?php print $date['date_title'];?></h1>
		<p class="hide"><?php print $date['date_snippet'];?></p>
		<span class="the-icons icon-down-open hide" onclick="scrolltophotos(); return false;"></span>
		</div>	
</div>
<?php } ?>
<?php }  ?>
<?php } ?>
<?php if(($date['open_highlights'] == "1") && (empty($_REQUEST['sub'])) && (empty($_REQUEST['view'])) && ($photog_favs  > 0)== true) { 
	$_REQUEST['view'] = "highlights";	
	?>
	<script>
	$(document).ready(function(){
		$("#vinfo").attr("view","highlights");
	});
	</script>
	<?php 
	// print "<div class=\"pc center highlightstitle\"><h1>".$date['highlights_text']."</h1></div>";
}
?>
<?php } // End need password ?>