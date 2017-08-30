<?php
 if(!customerLoggedIn()) { 
	 $and_link = "AND link_logged_in='0' ";
 }
$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='".$css['menu_use']."' ORDER BY link_order ASC ");
if(mysqli_num_rows($links) > 0) { ?>

<ul class="">
<?php 
	while($link = mysqli_fetch_array($links)) { 
	$ml++;
	$spacing = $main_link_spacing * $ml;

	if(!empty($link['link_main'])) { 
		if($link['link_main'] == "cart") { 
			print "<li style=\" top: ".$spacing."%;\" id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">".$link['link_text']."</a> &nbsp; <a href=\"/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print "><span id=\"cartlinktotal\">"; if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; } print "</span></a></li>";
		}

		if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
			print "<li  style=\" top: ".$spacing."%;\" id=\"favoriteslink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">".$link['link_text']." (<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></li>";
		}
		if($link['link_main'] == "findphotos") { ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php print $link['link_text'];?></a></li>
		<?php }
		if($link['link_main'] == "checkout") { 
			print "<li><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">".$link['link_text']."</a></li>";
		}
		if($link['link_main'] == "printcredit") { ?><li style=" top: <?php print $spacing;?>%; "><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php print $link['link_text'];?></a></li><?php } 

		if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php print $link['link_text'];?></a></li><?php } 


		 if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php print $link['link_text'];?></a></li><?php } 


		if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {   ?>
		<li style=" top: <?php print $spacing;?>%; "><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php print $link['link_text'];?></a></li>
		<?php } ?>
		<?php if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php print $link['link_text'];?></a></li><?php } ?>

	<?php 
		} elseif($link['link_page'] > 0) {
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
			if($lpage['page_home'] == "1") { 
				if($site_setup['index_page'] == "indexnew.php") { ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/indexnew.php"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php } else { ?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php }
			} else {
				?><li style=" top: <?php print $spacing;?>%; "><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php print $link['link_text'];?></a></li><?php 
			}
			} elseif($link['link_cat'] > 0) {
				$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
				if($cat['cat_order_by'] == "pageorder") { 
					$order_by = " page_order ASC";
				} else { 
					$order_by = " date_date DESC,date_time DESC ";
				}
				$time = strtotime("".$site_setup['time_diff']." hours");
				$cur_time =date('Y-m-d H:i:s', $time);

					?><li  style=" top: <?php print $spacing;?>%; "<?php if(!empty($link['link_html'])) { print "class=\"flymenu\""; } ?>><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a>
					<?php if((!empty($link['link_html']))&&($ipad !==true)==true) { ?><div class="flyout" id="fly-<?php print $link['link_id'];?>"><?php print $link['link_html'];?></div><?php } ?>
					</li><?php 
	} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) {  print "<li style=\" top: ".$spacing."%;\" id=\"viewcartlink\" >".$link['link_text']."</li>"; 

	} else { print "<li style=\" top: ".$spacing."%;\" id=\"viewcartlink\" ><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a></li>"; }  
} 
?>
</ul>


<?php } ?>
