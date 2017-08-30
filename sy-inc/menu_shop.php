<?php
if($css['menu_center'] == 1) {
	$disable_drops = true;
}
if($sytist_store == true) {
	$total = shoppingCartTotal($mssess);
}
 if(!customerLoggedIn()) { 
	 $and_link = "AND link_logged_in='0' ";
 }
 if(customerLoggedIn()) { 
	 $and_link = "AND link_logged_out='0' ";
 }

$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='shop' ORDER BY link_order ASC ");
if(mysqli_num_rows($links) > 0) { ?>
<div class="hidesmall">
<div id="smc" style="width:0; height: 0;"></div><div id="shopmenucontainer" >
	<div id="shopmenuinner">
	<?php // if($store['checkout_account']!=="disabled"){ ?>
	<div id="accountmenu">
	<ul>
	<?php if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_deactivated'] == "1") { 
		unset($_SESSION['pid']);
		unset($_SESSION['loggedin']);
		unset($_SESSION['page_return']);
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		SetCookie("persid","",time()-3600,"/",null);
		session_write_close();
		header ("location: /");
		exit();
	}
	updateSQL("ms_people", "p_last_active='".date('Y-m-d H:i:s')."', p_last_active_ip='".getUserIP()."' WHERE p_id='".$person['p_id']."' ");
	?><li class="mobilehide"><?php print _welcome_top_;?> <?php print $person['p_name'];?></li><?php } 
	$alinks = whileSQL("ms_menu_links", "*", "WHERE link_location='shop'  $and_link  AND link_shop_menu='accountmenu' AND link_status='1'  ORDER BY link_order ASC"); 
	while($alink = mysqli_fetch_array($alinks)) { 
		if($alink['link_page'] > 0) {
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$alink['link_page']."' ");
			if($lpage['page_home'] == "1") { ?>
				<li><a href="<?php print $setup['temp_url_folder'];?>/"  target="<?php print $alink['link_open'];?>"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a>
				<?php 
			} else {  ?>
			<li><a href="<?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $alink['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li> 
				<?php			
			}
				} elseif($alink['link_cat'] > 0) {
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$alink['link_cat']."' ");
?>
<li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $alink['link_open'];?>"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li> <?php 
} elseif(!empty($alink['link_main'])) { 
if(($alink['link_main'] == "login")&&(!customerLoggedIn())==true) { ?>
<li><?php if($alink['link_login_page'] == "1") { ?><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a>
<?php } else {  ?><a href="" onclick="showgallerylogin('<?php print $_REQUEST['view'];?>','<?php print $sub['sub_link'];?>','','login'); return false;"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a>
<?php } ?></li><?php } 
 if(($alink['link_main'] == "myaccount")&&(customerLoggedIn())==true) { ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if(($alink['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if(($alink['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?>
<li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if($alink['link_main'] == "findphotos") { ?>
<li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if($alink['link_main'] == "printcredit") { ?>
<li><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if($alink['link_main'] == "giftcertificates") { ?>
<li><a href="" onclick="giftcertificate(0); return false;" class="giftcertificates"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>
<?php if($alink['link_main'] == "redeemcoupon") { ?>
<li><a href="" onclick="redeemcoupon('',''); return false;" class="redeemcoupon"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php } ?>

<?php if($alink['link_main'] == "cart") { ?>
<li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=cart" <?php if($_REQUEST['view'] !== "checkout") { ?>onClick="viewcart(); return false;"<?php } ?>><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?> 
<span id="cartlinktotal"><?php  if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } }
if($total['show_cart_total'] > 0) { 
	print " ".showPrice($total['show_cart_total']).""; }  print "</span></a></li>";  } ?>
<?php if($alink['link_main'] == "checkout") { ?><li>
<?php if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1") && (countIt("ms_payment_options", "WHERE pay_status='1' ") == "1")==true) { ?>
<a href="" onclick="ppexpresscheckout(); return false;"><?php print _checkout_;?></a>
	<?php } else { ?>
<a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a>
<?php } ?>
</li><?php } 
	 if(($alink['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
			print "<li id=\"favoriteslink\" class=\"favoriteslink\"><nobr><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">";   if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?><?php print " (<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></nobr></li>";
		}
	} elseif((empty($alink['link_url']))AND(empty($alink['link_page']))==true) {  ?>
	<li><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></li><?php 

	} else { print "<li><a href=\"".$alink['link_url']."\" target=\"".$alink['link_open']."\">";?><?php if(!empty($alink['link_icon'])) { ?><span class="the-icons <?php print $alink['link_icon'];?>"></span><?php } ?><?php print $alink['link_text'];?></a></li><?php }  ?>
<?php } ?>
</ul>
</div>
<?php // } ?>
<div id="shopmenuitems" style="float:right;">
<div id="shopmenu">
<ul>
<?php 
		$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1'  $and_link  AND link_location='shop' AND (link_shop_menu='shopmenu' OR link_shop_menu='') ORDER BY link_order ASC ");

		while($link = mysqli_fetch_array($links)) { 

		if($link['link_page'] > 0) {
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
			if($lpage['page_home'] == "1") { ?>
				<li><a href="<?php print $setup['temp_url_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php 
			} else {  ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php			
			}
				} elseif($link['link_cat'] > 0) {
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
					?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php 
				} elseif(!empty($link['link_main'])) { 

					if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
						print "<li id=\"favoriteslink\" class=\"favoriteslink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a><?php print " (<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></li>";
					}

					if($link['link_main'] == "cart") { 
						print "<li id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?> <span id="cartlinktotal"><?php  if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } if($total['show_cart_total'] > 0) { print " ".showPrice($total['show_cart_total'])."";}  } print "</span></a></li>";
					}
					if($link['link_main'] == "findphotos") { ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }
				 if($link['link_main'] == "printcredit") { ?><li><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

			 if($link['link_main'] == "giftcertificates") { ?>
			<li><a href="" onclick="giftcertificate(0); return false;" class="giftcertificates"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

				 if($link['link_main'] == "redeemcoupon") { ?><li><a href="" onclick="redeemcoupon('',''); return false;" class="redeemcoupon"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

					if($link['link_main'] == "checkout") { 
						print "<li id=\"checkoutlink\" ";  if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print ">";
						if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1") && (countIt("ms_payment_options", "WHERE pay_status='1' ") == "1")==true) { ?>
						<a href="" onclick="ppexpresscheckout(); return false;"><?php print _checkout_;?></a>
						<?php } else {
						print "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text']."</a>";
						}
						print "</li>";
					}
					if($link['link_main'] == "myaccount") { 
						print "<li id=\"myaccountlink\"><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"><?php } ?><?php print $link['link_text']."</a></li>";
					}
					if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {  ?><?php if($link['link_login_page'] == "1") { 
						 ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } else {  ?><li><a href="" onclick="showgallerylogin('<?php print $_REQUEST['view'];?>','<?php print $sub['sub_link'];?>','','login'); return false;"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }
				 	}
				if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

			 if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

		} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) { ?><li><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></li><?php 

		} else { print "<li><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }  
		} ?>
</ul></div>
</div>
<div class="clear"></div></div></div>
</div>
<?php } ?>
