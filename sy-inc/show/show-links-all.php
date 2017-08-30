<?php
 if(!customerLoggedIn()) { 
	 $and_link = "AND link_logged_in='0' ";
 }
$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='".$css['menu_use']."' ORDER BY link_order ASC ");
if(mysqli_num_rows($links) > 0) { ?>

<ul class="left featmenulinks nofloatsmall">
<?php 
		while($link = mysqli_fetch_array($links)) { 

		if(!empty($link['link_main'])) { 
			if($link['link_main'] == "cart") { 
				print "<li id=\"viewcartlink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\">".$link['link_text']."</a></li>";
			}
			if($link['link_main'] == "favorites") { 
				print "<li id=\"viewcartlink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">".$link['link_text']."</a></li>";
			}
			if($link['link_main'] == "findphotos") { ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php print $link['link_text'];?></a></li>
			<?php }
			if($link['link_main'] == "checkout") { 
				print "<li><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">".$link['link_text']."</a></li>";
			}
			if($link['link_main'] == "myaccount") { 
				print "<li><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\">".$link['link_text']."</a></li>";
			}
			if($link['link_main'] == "login") {  ?>
			<?php if($link['link_login_page'] == "1") { ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php print $link['link_text'];?></a></li>
			<?php } else { ?><li><a href="" onClick="showLogin(); return false;"><?php print $link['link_text'];?></a></li>
			<?php } ?>
		<?php 	}
		} elseif($link['link_page'] > 0) {
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
			if($lpage['page_home'] == "1") { 
				if($site_setup['index_page'] == "indexnew.php") { ?><li><a href="<?php print removesecure();?>/indexnew.php"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php } else { ?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php }

			} else {
				$top_menu_links[$lpage['date_id']] = array();
				array_push($top_menu_links[$lpage['date_id']],$lpage['date_id']);
				$spages = whileSQL("ms_calendar", "date_link,page_under,date_title,external_link,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
				while($spage = mysqli_fetch_array($spages)) {
					array_push($top_menu_links[$lpage['date_id']],$spage['date_id']);
				}
				if((countIt("ms_calendar", "WHERE page_under='".$lpage['date_id']."' AND date_public='1' ")>0)AND($lpage['page_disable_drop']=="0")==true) {
				?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"><?php print $link['link_text'];?></a></li>
				<?php 
				$spages = whileSQL("ms_calendar", "date_link,page_under,date_title,external_link,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
					while($spage = mysqli_fetch_array($spages)) {
						if(!empty($spage['external_link'])) { ?>
						<li><a href="<?php print $spage['external_link'];?>"> -- <?php print $spage['date_title'];?></a></li>
						<?php } else { ?>
						<li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $spage['date_link'];?>/"> -- <?php print $spage['date_title'];?></a></li>
						<?php 
							// array_push($top_menu_links[$lpage['date_id']],$spage['date_id']);
						}
					}
				?>
				
		<?php 
				} else { 
					?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php print $link['link_text'];?></a></li><?php 
				
					}
			}
				} elseif($link['link_cat'] > 0) {
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
					if((countIt("ms_blog_categories", "WHERE cat_under='".$cat['cat_id']."' AND cat_status='1' ") > 0)==true) { 
						?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li>
						
					<?php $scats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$cat['cat_id']."' AND cat_status='1' ORDER BY cat_name ASC ");
					while($scat = mysqli_fetch_array($scats)) { ?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$scat['cat_folder'];?>/"> -- <?php print $scat['cat_name'];?></a></li><?php 
					} 
					?><?php 	} else {  
						?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php 
					}

					if($link['link_dropdown'] == "pages") { 
						$mdates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  date_public='1' AND date_type='news'  AND  (ms_calendar.date_cat='".$cat['cat_id']."' OR ms_blog_cats_connect.con_cat='".$cat['cat_id']."' $and_sub_cat )  AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND  private<='1'   AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) AND date_id!='".$cat['cat_content']."' GROUP BY date_id   ORDER BY $order_by");
						while($mdate = mysqli_fetch_array($mdates)) { ?>
						<li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."";?>/"> -- <?php print $mdate['date_title'];?></a></li>
						<?php }
				}
		} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) {  print "<li>".$link['link_text']."</li>"; 

		} else { print "<li><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a></li>"; }  
	} ?></ul>


<?php } ?>

<?php
 if(!customerLoggedIn()) { 
	 $and_link = "AND link_logged_in='0' ";
 }
$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='shop' ORDER BY link_order ASC ");
if(mysqli_num_rows($links) > 0) { ?>

<ul class="left featmenulinks nofloatsmall">
<?php 
	while($link = mysqli_fetch_array($links)) { 
	$ml++;
	$spacing = $main_link_spacing * $ml;

	if(!empty($link['link_main'])) { 
		if($link['link_main'] == "cart") { 
			print "<li style=\" top: ".$spacing."%;\" id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">".$link['link_text']." ";
			 if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; }
			 print "</a></li>";
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
<?php 
	$links = whileSQL("ms_social_links", "*", "WHERE link_status='1' ORDER BY link_order ASC");
if(mysqli_num_rows($links) > 0) { ?>

<ul class="left featmenulinks nofloatsmall">
<?php 
	while($link = mysqli_fetch_array($links)) {
		print "<li><a href=\"".$link['link_url']."\" target=\"_blank\" title=\"".$link['link_text']."\" class=\"the-icons icon-".$link['link_name']."\" style=\"opacity: 1;\">".$link['link_text']."</a></li>";
	}
	?>
</ul>
<?php } ?>
<div class="clear"></div>
