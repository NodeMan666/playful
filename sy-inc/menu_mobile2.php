<style>
#mobile-menu {background: #<?php print $css['menu_color'];?>; color: #<?php print $css['menu_font_color'];?>; width: 100%; 
	font-size: 17px;
	margin-bottom: 0px;
} 

#mobile-menu-inner { border-bottom: solid 1px #<?php print $css['menu_border_a'];?>; border-top: solid 1px #<?php print $css['menu_border_a'];?>; } 
#mobile-links { display: none; } 

.mobile-menu-left { float: left; border-right: solid 1px #<?php print $css['menu_border_a'];?>; text-align: center;color: #<?php print $css['menu_link_color'];?>; }
.mobile-menu-left .menuinner { padding: 12px; } 
.mobile-menu-text { font-size: 11px; } 
.mobile-menu-icon { } 
.mobile-menu-fb-like { height: 100%; margin-top: 4px;line-height: auto; } 

.mobile-menu-right { float: right; border-left: solid 1px #<?php print $css['menu_border_a'];?>; text-align: center; color: #<?php print $css['menu_link_color'];?>;}  
.mobile-menu-right .menuinner { padding: 12px; } 

#mobile-menu .menu ul { margin: 0px; padding: 0px; list-style: none;  } 
#mobile-menu .menu ul li {  margin: 0px;  } 
#mobile-menu .menu ul li a {  display:block; margin: 0px 0px 0px 0px; background: #<?php print $css['menu_color'];?>; border-bottom: solid 1px #<?php print $css['menu_border_a'];?>; padding: 16px; color: #<?php print $css['menu_link_color'];?>;  font-size: 17px} 
#mobile-menu .menu ul li a .the-icons { color: #<?php print $css['menu_link_color'];?>; text-shadow: none; } 

#mobile-menu .the-icons { color:#<?php print $css['menu_link_color'];?>; text-shadow: none; 	white-space: nowrap; font-size: 21px;} 
#mobileshare { display: none; } 
.mobileCartQty {
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    padding: 4px;
    font-size: 12px;
    line-height: 1em;
    position: relative;
	display: inline-block;
}
.mobileCartQty .height_fix {
    margin-top: 100%;
}
.mobileCartQty .content {
    position: absolute;
    left: 0;
    top: 50%;
    height: 100%;
    width: 100%;
    text-align: center;
    margin-top: -5px; /* Note, this must be half the font size */
	color: #<?php print $css['menu_link_color'];?>;
}
</style>
<script>
function showmobilemenu() { 
	$("#mobileshare").hide();
	$("#mobile-links").slideToggle(200);
}

function viewcarttouch() { 
	window.location.href="/index.php?view=cart";
}

function hometouch() { 
	window.location.href="/";
}

function showgalleryshare() { 
	$("#mobile-links").hide();
	$("#mobileshare").slideToggle(200);
}
</script>
<div id="mobilemenu" class="hide">
	<div id="mobile-menu">
		<div id="mobile-menu-inner">

			<div class="mobile-menu-left">
				<div class="menuinner" onclick="showmobilemenu(); return false;">
					<div class="mobile-menu-icon"><span class="the-icons icon-menu"></span></div>
					<div class="mobile-menu-text"><span >MENU</span></div>
				</div>
			</div>


			<div class="mobile-menu-left">
				<div class="menuinner" onclick="hometouch(); return false;">
					<div class="mobile-menu-icon"><span class="the-icons icon-home"></span></div>
					<div class="mobile-menu-text"><span >HOME</span></div>
				</div>
			</div>
			<div class="mobile-menu-left">
				<div class="menuinner" onclick="showgalleryshare(); return false;">
					<div class="mobile-menu-icon"><span class="the-icons icon-share"></span></div>
					<div class="mobile-menu-text"><span >SHARE</span></div>
				</div>
			</div>

			<!-- 
			<div class="mobile-menu-left">
				<div class="menuinner" onclick="showgalleryshare(); return false;">

					<div class="mobile-menu-fb-like"><script src="//connect.facebook.net/<?php print $fb['fb_lang'];?>/all.js#xfbml=1"></script><fb:like show_faces="<?php print $fb['like_show_faces'];?>"  layout="button" width="90" font="arial"  colorscheme="<?php print $css['fb_color'];?>" ></fb:like></div>

				</div>
			</div>
			-->
			<div class="mobile-menu-right">
				<div class="menuinner" onclick="viewcarttouch(); return false;">
					<div  class="mobile-menu-icon"><span class="the-icons icon-basket"></span><span class="mobileCartQty" style="font-size: 12px !important; line-height: 1em;"><span class="height_fix"></span><span class="content carttotalcircle" style="font-size: 12px; line-height: 1em;"><?php if($total['total_items']  > 0) {  print $total['total_items'] + 0; } ?></span></span></div>
					<div class="mobile-menu-text"><span>CART</span></div>
				</div>
			</div>
			<div class="mobile-menu-right">
				<div class="menuinner" onclick="viewcarttouch(); return false;">
					<div  class="mobile-menu-icon"><span class="the-icons icon-user"></span></div>
					<div class="mobile-menu-text"><span>ACCOUNT</span></div>
				</div>
			</div>

			<div class="clear"></div>
		</div>
		<div class="menu" id="mobile-links">
			<ul>
			<?php 
			$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1'  ORDER BY link_mobile_order,link_order ASC ");

			while($link = mysqli_fetch_array($links)) { ?>
			<?php 		
			if($link['link_page'] > 0) {
				$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
				if($lpage['page_home'] == "1") { ?>
					<li><a href="<?php print $setup['temp_url_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a>
					<?php 
				} else {  ?>

						<li><a href="<?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li> 
					<?php			
				}
					} elseif($link['link_cat'] > 0) {
						$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
						?>
							<li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li> 

						<?php 
					} elseif(!empty($link['link_main'])) { 

						if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
							print "<li id=\"favoriteslink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?> <?php print " (<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></li>";
						}

						if($link['link_main'] == "cart") { 
							print "<li id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text']." <span id=\"cartlinktotal\" class=\"cartlinktotal\">"; if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; } print "</span></a></li>";
						}
						if($link['link_main'] == "findphotos") { ?>

						<li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li>
						<?php }
					 if($link['link_main'] == "printcredit") { ?><li><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

					if($link['link_main'] == "giftcertificates") { ?>
					<li><a href="" onclick="giftcertificate(0); return false;" class="giftcertificates"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }

					 if($link['link_main'] == "redeemcoupon") { ?><li><a href="" onclick="redeemcoupon('',''); return false;" class="redeemcoupon"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 


						if($link['link_main'] == "checkout") { 
							print "<li id=\"checkoutlink\" ";  if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print ">"; 
							if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1")==true) { ?><a href="" onclick="ppexpresscheckout(); return false;"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a><?php } else { ?><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a><?php }	?></li>
						<?php 
						}
						if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { 
							print "<li id=\"myaccountlink\"><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?><?php print "</a></li>";
						}
						if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {  ?>
						<li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li>

					<?php 	}
					if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 



				 if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

			} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) {  ?><li><a href="" onclick="return false;"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text']."</a></li>"; 

			} else { print "<li><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">";?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text']."</a></li>"; }  ?>

		<?php } ?>
			</ul>
		</div>

	<!-- Sharing with mobile menu -->

	<div id="mobileshare">
		<div  class="menu">
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
		<li><a href=""  onclick="sharepage('facebook','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"><span class="icon-facebook the-icons"></span>Facebook</a></li>
		<li><a href=""  onclick="sharepage('pinterest','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"><span class="icon-pinterest the-icons"></span>Pinterest</a></li>
		<li><a href=""   onclick="sharepage('twitter','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"><span  class="icon-twitter the-icons"></span>Twitter</a></li>
		<li><a href="" onclick="sharepage('email','<?php print $share_link;?>','','<?php print urlencode($date['date_title']." - ".$site_setup['website_title']);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"><span  class="icon-mail the-icons"></span>Send Email</a></li>
		</ul>	</div>
	</div>
	<!-- // Sharing with mobile menu -->

	</div>
</div>