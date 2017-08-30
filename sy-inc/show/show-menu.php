<?php  $total = shoppingCartTotal($mssess); ?>
<div id="clfthumbsbg"></div>
<div id="clfthumbs"></div>
<style>
#clfmenu { position: fixed; top: 0px; left: 0px; background: #444444; border-bottom: solid 1px #141414; z-index: 5000; width: 100%; } 
#clfmenu ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
	padding: 8px;
	color: #b4b4b4;
	position: relative;
}
#clfmenu .rightside { 
	float: right;
}
#clfmenu .rightside li { 
	border-right: 0px;
}
#clfmenu .leftside { 

	float: left;
}

#clfmenu ul li { 
	display: inline;
	margin-right: 4px;
	border-right: solid 1px #242424;
	padding: 8px; 
	position: relative;
	white-space:nowrap;
}

#clfmenu ul .the-icons { 
	text-shadow: 0px 0px 1px #000000;
	color: #c4c4c4;
}

#clfmenu ul li ul { 
		 position: absolute; top: 100%; left: 0; background: #444444; border-right: solid 1px #242424; z-index: 50; width: 200px;  margin-left: -8px; opacity: 1; padding: 0px; display: none;
}

#clfmenu ul li:hover > ul { display: block; } 
#clfmenu ul li ul li { 
	white-space:nowrap;
	margin-right: 4px;
	border-bottom: solid 1px #242424;
	padding: 8px 8px 8px 24px; 
	color: #FFFFFF; 
	display: block;
	border-right: 0px;

}


#clfmenumain { 
	position: absolute; top: 50px; left: 0px; background: #444444; border-right: solid 1px #242424; z-index: 50; max-width: 300px; width: 100%; display: none;
}
#clfmenumain ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
	color: #b4b4b4;
	opacity: 1;
}
#clfmenumain ul li a { 
	white-space:nowrap;
	margin-right: 4px;
	border-bottom: solid 1px #242424;
	padding: 8px 8px 8px 24px; 
	color: #FFFFFF; 
	display: block;
	opacity: 1;
}
#clfmoremenu li { 
	opacity: 1;
}

</style>

<div id="clfmenu">
<ul class="leftside" id="clfmenuleft">
<li id="showclfmenumain" class="the-icons icon-menu"></li>
<!-- <li><?php print $site_setup['website_title'];?> | <?php print $date['date_title'];?></li> -->
<li  class="the-icons icon-picture showclfthumbs" data-menu-item="allphotosmenu"> all photos</li>
<li class="the-icons icon-heart hideonthumbs" data-menu-item="favsmenu"> favorite</li>
<li  class="the-icons icon-basket hideonthumbs buyclfphoto" data-menu-item="buymenu"> buy</li>
<li class="the-icons icon-share hideonthumbs" data-menu-item="sharemenu"> share
	<ul>
		<li><span class="the-icons icon-facebook"></span> <span class="the-icons icon-facebook"></span> <span class="the-icons icon-facebook"></span> <span class="the-icons icon-facebook"></span></li>
	</ul>
</li>
<li class="the-icons icon-download hideonthumbs clffree" data-menu-item="downloadmenu"> download</li>
<li class="the-icons icon-palette hideonthumbs" data-menu-item="filtermenu"> filter</li>
<li class="the-icons icon-flag hideonthumbs" data-menu-item="comparemenu"> compare</li>
<li class="the-icons icon-info-circled hideonthumbs" data-menu-item="infomenu"> info</li>
<li class="the-icons icon-down-open hideonthumbs moremenu" id="clfmoremenubutton"> more actions<ul id="clfmoremenu">
	<li class="the-icons icon-picture moresub showclfthumbs" data-menu-item="allphotosmenu"> all photos</li>
	<li class="the-icons icon-heart hideonthumbs  moresub" data-menu-item="favsmenu"> favorite</li>
	<li class="the-icons icon-basket hideonthumbs  moresub buyclfphoto" data-menu-item="buymenu"> buy</li>
	<li class="the-icons icon-share hideonthumbs  moresub" data-menu-item="sharemenu"> share</li>
	<li class="the-icons icon-download hideonthumbs  moresub clffree" data-menu-item="downloadmenu"> download</li>
	<li class="the-icons icon-palette hideonthumbs  moresub" data-menu-item="filtermenu"> filter</li>
	<li class="the-icons icon-flag hideonthumbs  moresub" data-menu-item="comparemenu"> compare</li>
	<li class="the-icons icon-info-circled hideonthumbs  moresub" data-menu-item="infomenu"> info</li>
	</ul></li>

</ul>
<ul class="rightside" id="clfmenuright">
<!-- <li><a href="" onclick="viewcart(); return false;">My Cart <?php print $total['total_items'];  if($total['total_items'] > 0) { if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; } ?></a></li> -->
</ul>
<div class="clear"></div>
</div>

<div id="clfmenumain">
<ul>
<?php 
$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='".$css['menu_use']."' ORDER BY link_order ASC  ");
while($link = mysqli_fetch_array($links)) { 		if(!empty($link['link_main'])) { 
			if($link['link_main'] == "cart") { 
				print "<li id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">".$link['link_text']."</a><a href=\"/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print "><span id=\"cartlinktotal\">"; if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($total['show_cart_total']).""; } print "</span></a></li>";
			}
			if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
				print "<li id=\"favoriteslink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">".$link['link_text']." (<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></li>";
			}
			if($link['link_main'] == "findphotos") { ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php print $link['link_text'];?></a></li><?php }
			if($link['link_main'] == "checkout") { 
				print "<li><a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">".$link['link_text']."</a></li>";
			}
			if($link['link_main'] == "printcredit") { ?><li><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php print $link['link_text'];?></a></li><?php } 
			if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php print $link['link_text'];?></a></li><?php } 

			 if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php print $link['link_text'];?></a></li><?php } 
			if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {   ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php print $link['link_text'];?></a></li><?php }
			if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php print $link['link_text'];?></a></li><?php }

		} elseif($link['link_page'] > 0) {
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
			if($lpage['page_home'] == "1") { 
				if($site_setup['index_page'] == "indexnew.php") { ?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/indexnew.php"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php } else { ?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a></li><?php }

			} else {
				$top_menu_links[$lpage['date_id']] = array();
				array_push($top_menu_links[$lpage['date_id']],$lpage['date_id']);
				$spages = whileSQL("ms_calendar", "date_link,page_under,date_title,external_link,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
				while($spage = mysqli_fetch_array($spages)) {
					array_push($top_menu_links[$lpage['date_id']],$spage['date_id']);
				}
				if((countIt("ms_calendar", "WHERE page_under='".$lpage['date_id']."' AND date_public='1' ")>0)AND($lpage['page_disable_drop']=="0")AND($mobile!==true)==true) {	?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"><?php print $link['link_text'];?></a>
				<ul>
				<?php 
				$spages = whileSQL("ms_calendar", "date_link,page_under,date_title,external_link,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
					while($spage = mysqli_fetch_array($spages)) {
						if(!empty($spage['external_link'])) { ?>
						<li><a href="<?php print $spage['external_link'];?>"><?php print $spage['date_title'];?></a></li>
						<?php } else { ?>
						<li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $spage['date_link'];?>/"><?php print $spage['date_title'];?></a></li>
						<?php 
							// array_push($top_menu_links[$lpage['date_id']],$spage['date_id']);
						}
					}
				?></ul></li><?php 
				} else { 
					?><li><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?>/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"  class="menulink" id="menu-<?php print $lpage['date_id'];?>" did="<?php print $lpage['date_id'];?>"><?php print $link['link_text'];?></a></li><?php 
				
					}
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


					if((countIt("ms_blog_categories", "WHERE cat_under='".$cat['cat_id']."' ") > 0)AND($mobile!==true)AND($link['link_dropdown'] == "cats")==true) { ?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>" <?php if($link['link_no_click'] == "1") { ?>onclick="return false;" style="cursor: default;"<?php } ?>><?php print $link['link_text'];?></a><ul>
					<?php $scats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$cat['cat_id']."' AND cat_status='1' ORDER BY cat_name ASC ");
					while($scat = mysqli_fetch_array($scats)) { ?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$scat['cat_folder'];?>/"><?php print $scat['cat_name'];?></a></li><?php } ?></ul></li><?php 
					} elseif((countIt("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","WHERE  date_public='1' AND date_type='news'  AND  (ms_calendar.date_cat='".$cat['cat_id']."' OR ms_blog_cats_connect.con_cat='".$cat['cat_id']."' )  AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND  private<='1'   AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) GROUP BY date_id   ORDER BY $order_by") > 0)AND($link['link_dropdown'] == "pages")==true) { ?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>" <?php if($link['link_no_click'] == "1") { ?>onclick="return false;" style="cursor: default;"<?php } ?>><?php print $link['link_text'];?></a><ul>
					<?php 
					$mdates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  date_public='1' AND date_type='news'  AND  (ms_calendar.date_cat='".$cat['cat_id']."' OR ms_blog_cats_connect.con_cat='".$cat['cat_id']."' $and_sub_cat )  AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND  private<='1'   AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) AND date_id!='".$cat['cat_content']."' GROUP BY date_id   ORDER BY $order_by");
					while($mdate = mysqli_fetch_array($mdates)) { ?><li><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."";?>/"><?php print $mdate['date_title'];?></a></li><?php } ?></ul></li><?php 
					} else {  ?><li <?php if(!empty($link['link_html'])) { print "class=\"flymenu\""; } ?>><a href="<?php print removesecure();?><?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/"  target="<?php print $link['link_open'];?>"><?php print $link['link_text'];?></a><?php if((!empty($link['link_html']))&&($ipad !==true)==true) { ?><div class="flyout" id="fly-<?php print $link['link_id'];?>"><?php print $link['link_html'];?></div><?php } ?></li><?php 
					}
		} elseif((empty($link['link_url']))AND(empty($link['link_page']))==true) {  print "<li>".$link['link_text']."</li>"; 

		} else { print "<li><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a></li>"; }  
	 } ?>
</ul>
</div>
