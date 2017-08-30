<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/stick-sidebar-scroll.js?".MD5($site_setup['sytist_version'])."" ?>b"></script>
<script>
    $(document).ready(function() {
    $.stickysidebarscroll("#stoponscroll",{offset: {top: 120, bottom: 200}});
    });
</script>
<div id="sideMenuContainer" class="hidesmall">
	<div id="sideMenu">

	<?php

 if(!customerLoggedIn()) { 
	 $and_link = "AND link_logged_in='0' ";
 }
 if(customerLoggedIn()) { 
	 $and_link = "AND link_logged_out='0' ";
 }

	if(($top_section > 0)&&(countIt("ms_blog_categories", "WHERE cat_no_show='0' AND cat_under='".$top_section."' ORDER BY cat_name ASC ") > 0)==true) { 
		?>
		<div class="sidebaritem sidebarcatmenu">
		<?php print categorySideMenu(0,""); ?>
		</div>
	<?php } ?>

	<?php $sides = whileSQL("ms_side_menu", "*", "ORDER BY side_order ASC ");
	while($side = mysqli_fetch_array($sides)) { 
		?>
		<?php  if((!empty($side['side_label']))OR(!empty($side['side_text']))==true) { ?><div class="sidebarlabel"><?php } ?>
		<?php if(!empty($side['side_label'])) { ?><div class="label"><?php print $side['side_label'];?></div><?php } ?>
		<?php if(!empty($side['side_text'])) { ?><div class="text"><?php print nl2br($side['side_text']);?></div><?php } ?>
		<?php  if((!empty($side['side_label']))OR(!empty($side['side_text']))==true) { ?></div><?php } ?>
		<?php 
		if($side['side_feature'] == "recentitems") { 
			showrecentitems($side);
		}
		if($side['side_feature'] == "textarea") { 
			showtextarea($side);
		}
		if($side['side_feature'] == "facebook") { 
			showfblike($side);
		}
		if($side['side_feature'] == "popular") { 
			popularpages($side);
		}
		if($side['side_feature'] == "menu") { 
			sidebarmenu($side);
		}
		if($side['side_feature'] == "phpfile") { 
			showincludedfile($side);
		}
		if($side['side_feature'] == "search") { 
			showsearchform($side);
		}
		if($side['side_feature'] == "pagetags") { 
			sidepagetags($side);
		}


	}


function sidepagetags($side) { 
	$tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC "); 
	if(mysqli_num_rows($tags) > 0) { 
	?>
	<div class="pc sidetags">
	<?php 
	while($tag = mysqli_fetch_array($tags)) { 
		$cktag = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag['tag_id']."' AND tag_date_id='".$date['date_id']."' "); 
		if(countIt("ms_tag_connect", "WHERE tag_tag_id='".$tag['tag_id']."' ") > 0) {
			$ttag++;
			if($ttag > 1) { print ", "; } 
			?>
		<nobr><a href="<?php print $setup['temp_url_folder'];?>/tags/<?php print $tag['tag_folder'];?>/"><?php print $tag['tag_tag'];?></a></nobr>
		<?php } ?>
	<?php } ?>
	</div>	
	<div>&nbsp;</div>
	<?php } ?>
<?php
}

function showsearchform($side) { 
	global $setup,$site_setup;
	?>
	<div class="sidebaritem">
	<form method="get" name="searchform" action="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>">
	<input type="text" name="q" size="20" value="">
	<input type="hidden" name="view" value="search">
	<input type="submit" name="submit" value="Search" class="submit">
	</form>
	</div>
<?php 
	} 
function sidebarmenu($side) { 
	global $setup, $site_setup,$total;
	 if(!customerLoggedIn()) { 
		 $and_link = "AND link_logged_in='0' ";
	 }

	if(countIt("ms_menu_links", "WHERE link_status='1' $and_link AND link_location='side' ") > 0) { ?>
		<div class="sidebaritem"><ul>
		<?php $links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location='side' ORDER BY link_order ASC ");
			while($link = mysqli_fetch_array($links)) { 


			 if(!empty($link['link_main'])) { 


				if($link['link_main'] == "cart") { 
					print "<li id=\"viewcartlink\" "; if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print "><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart\""; if($_REQUEST['view'] !== "checkout") { print " onClick=\"viewcart(); return false;\""; } print ">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?> <span id="cartlinktotal"><?php if($total['total_items'] > 0) { print "".$total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } if($total['show_cart_total'] > 0) { print " ".showPrice($total['show_cart_total']).""; } } print "</span></a></li>";
				}
				if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) { 
					print "<li id=\"favoriteslink\"><a href=\"".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?> <?php print "(<span id=\"favoritestotaltop\">".countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ")."</span>)</a></li>";
				}
				if($link['link_main'] == "findphotos") { ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=findphotos"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }
				if($link['link_main'] == "checkout") { 
							print "<li id=\"checkoutlink\" ";  if(($link['link_show_cart'] == "1")&&($total['total_items'] <= 0)==true) { print "style=\"display: none;\""; } print ">";
							if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1")==true) { ?>
							<a href="" onclick="ppexpresscheckout(); return false;"><?php print _checkout_;?></a>
							<?php } else {
							print "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout\">"; ?><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a><?php
							}
							print "</li>";
				}
				if($link['link_main'] == "printcredit") { ?><li><a href="" onclick="redeemprintcredit(); return false;" class="redeemprintcredit"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 
				if($link['link_main'] == "redeemcoupon") { ?><li><a href="" onclick="redeemcoupon('',''); return false;" class="redeemcoupon"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

				if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 

				 if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php } 
				if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {   ?><li><a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }
				if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) {  ?><li><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=logout"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li><?php }
			



			} elseif($link['link_page'] > 0) {
				$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
				if($lpage['page_home'] == "1") {
					if($site_setup['index_page'] == "indexnew.php") { 
					print "<li><a href=\"/indexnew.php\"  target=\"".$link['link_open']."\">"; if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } print $link['link_text']."</a></li>"; 
				 } else { 
					print "<li><a href=\"/\"  target=\"".$link['link_open']."\">"; if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } print $link['link_text']."</a></li>"; 
				 }
				} else {
					?>
					<li><a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>/"  target="<?php print $link['link_open'];?>"><?php if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } ?><?php print $link['link_text'];?></a></li> 
				<?php 
					if(countIt("ms_calendar", "WHERE page_under='".$lpage['date_id']."' ")>0) {
						$side_menu_pages[$link['link_id']] = array();
						array_push($side_menu_pages[$link['link_id']],$lpage['date_id']);

						$spages = whileSQL("ms_calendar", "page_under,date_public,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
						while($spage = mysqli_fetch_array($spages)) {
							array_push($side_menu_pages[$link['link_id']],$spage['date_id']);
						}
						if(in_array($date['date_id'],$side_menu_pages[$link['link_id']])) {
							$spages = whileSQL("ms_calendar", "page_under,date_public,date_id,date_title,date_link,external_link", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
							while($spage = mysqli_fetch_array($spages)) {
								if(!empty($spage['external_link'])) {
									print "<li><div class=\"sub\"><a href=\"".$spage['external_link']."\">".$spage['date_title']."</a></div></li>"; 
								} else { ?>
								<li><div class="sub"><a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $spage['date_link'];?>/"><?php print $spage['date_title'];?></a></div></li> 
								<?php 
								}
							}
						}
					}
				}
			} elseif($link['link_cat'] > 0) {
				$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
				print "<li><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\"  target=\"".$link['link_open']."\">"; if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } print $link['link_text']."</a></li>"; 
			} else { 
				print "<li><a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">"; if(!empty($link['link_icon'])) { ?><span class="the-icons <?php print $link['link_icon'];?>"></span><?php } print $link['link_text']."</a></li>"; 
			}  ?>
		<?php } ?>

	</ul></div>
	<?php } ?>
<?php } ?>	
<?php 

function popularpages($side) { 
	global $setup,$site_setup;
	$yesterday = "CURDATE()";
	$whendo = "pv_date BETWEEN $yesterday - INTERVAL 7 DAY AND $yesterday ";
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);

	?>
	<div class="sidebaritem"><ul>
	<?php
	if($side['side_cat'] !== "999999999") { 
		$and_cat = "AND date_cat='".$side['side_cat']."' ";
	} else { 
		$and_cat = "AND date_cat!='0' ";
	}
	$dates = whileSQL("ms_stats_site_pv LEFT JOIN ms_calendar ON ms_stats_site_pv.date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, COUNT(*) AS dups", "WHERE $whendo AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'   AND page_under='0' $and_cat AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') GROUP BY page_viewed ORDER BY dups DESC LIMIT ".$side['side_limit']."");
	$pagetotal = mysqli_num_rows($dates);
	while ( $date = mysqli_fetch_array($dates) ) {
		$sn++;
		?>
		<li <?php if(($sn == $pagetotal) &&($css['side_menu_transparent'] !== "1")==true){ print "class=\"last\" "; } ?>>
		<?php 
			if($side['side_minis'] == "1") { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					//$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"  border=\"0\" ></a>";
				} else { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						//$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
						print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"   border=\"0\" ></a>";
					}
				}
			}
		?>

		
		
		<a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/"><?php print $date['date_title'];?></a>
		<?php if(($side['side_show_date'] == "1")||($side['side_show_time'] == "1") == true) { ?>
		<div class="date">
		<?php if($side['side_show_date'] == "1") { print $date['date_show_date']; } if($side['side_show_time'] == "1") { print " ".$date['date_time_show']; } 
		?>
		</div>
		<?php } ?>
		<div class="clear"></div></li>

		<?php  } 
	?>
	</ul></div>
<?php 
}


function showfblike($side) { 
	if($side['side_fb_faces']=="0") { $faces = "false"; } 
	if($side['side_fb_stream']=="0") {  $stream = "false"; } 
	if($side['side_fb_header']=="0") {  $header = "false"; } 
	print "<div class=\"sidebaritem\"><div style=\"margin: auto;width: ".$side['side_fb_width']."px; text-align: center;\" id=\"facebookLikeBox\"><center><script src=\"http://connect.facebook.net/en_US/all.js#xfbml=1\"></script><fb:like-box href=\"".$side['side_fb_link']."\" width=\"".$side['side_fb_width']."\" colorscheme=\"".$side['side_fb_color']."\" show_faces=\"".$faces."\" stream=\"".$stream."\" header=\"".$header."\"></fb:like-box></center></div></div>";
}

function showtextarea($side) { 
	$side['side_html'] = preg_replace_callback('#\[JOIN_MAILING_LIST]#i',includemailform,$side['side_html']);  
	print "<div class=\"sidebaritem\">".$side['side_html']."</div>";
}

function showincludedfile($side) { 
	print "<div class=\"sidebaritem\">";
	include $side['side_include'];
	print "</div>";
}

function showrecentitems($side) {
	global $setup, $site_setup,$css;
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);

	if($side['side_cat'] == "999999999") { 
		$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'  AND date_cat!='0'  AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') ORDER BY date_date DESC, date_time DESC  LIMIT ".$side['side_limit']." ");
	} else { 
		$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND date_cat='".$side['side_cat']."'AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'  AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') ORDER BY date_date DESC, date_time DESC  LIMIT ".$side['side_limit']." ");
	}
	?>
	<div class="sidebaritem">
	<ul>
	<?php 
	while($date = mysqli_fetch_array($dates)) { 
	$sn++;
	?>
	<li <?php if(($sn == mysqli_num_rows($dates)) &&($css['side_menu_transparent'] !== "1")==true){ print "class=\"last\" "; } ?>>
	<?php 
		if($side['side_minis'] == "1") { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
			//	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
				print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"  border=\"0\" ></a>";
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					//$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\"   border=\"0\" ></a>";
				}
			}
		}
	?>

	
	
	<a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/"><?php print $date['date_title'];?></a>
	<?php if(($side['side_show_date'] == "1")||($side['side_show_time'] == "1") == true) { ?>
	<div class="date">
	<?php if($side['side_show_date'] == "1") { print $date['date_show_date']; } if($side['side_show_time'] == "1") { print " ".$date['date_time_show']; } 
	?>
	</div>
	<?php } ?>
	<div class="clear"></div></li>

	<?php  } ?>
	</ul>
	</div>
<?php } ?>



<?php
// SUB PAGE MENU
if(is_array($top_menu_links)) {
	if(count($top_menu_links) > 1) { 
		foreach($top_menu_links AS $main => $sub) {
			if(in_array($date['date_id'],$sub)){
				if((countIt("ms_calendar", "WHERE date_type='page' AND page_under='".$main."' ")>0)&&(countIt("ms_menu_links", "WHERE link_status='1' $and_link AND link_location='".$css['side_menu_use']."' AND link_page='$main' ") <= 0)==true)  {

				//	print "YEP! and $main is main page";
		?>	
		
		<div id="linksMenuContainer">
		<div id="linksMenu">
		<?
			$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$main."' ");
			$link = doSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link  AND link_page='".$lpage['date_id']."' ");
			?>
			<div class="menuHeader">
			<?php if(!empty($lpage['page_mini'])) { 
				if(file_exists($setup['path']."/".$setup['misc_folder']."/pages_thumbnails/".$lpage['page_mini'])) {
					$size = @GetImageSize("".$setup['path']."/".$setup['misc_folder']."/pages_thumbnails/".$lpage['page_mini']); 
				?>
				<a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>"><img src="/<?php print $setup['misc_folder']."/pages_thumbnails/".$lpage['page_mini'];?>" class="thumbnail" <?php print $size[3];?>></a><?php }  } ?>
				<div class="title"><a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $lpage['date_link'];?>"><?php print $link['link_text'];?></a></div>
				<?php if(!empty($lpage['page_snippet'])) { 
					print "<div>".$lpage['page_snippet']."</div>";
				}
				?>
				<div class="cssClear"></div>
				</div>
			<?php 
						if(countIt("ms_calendar", "WHERE page_under='".$lpage['date_id']."' ")>0) {

							$side_menu_pages[$link['link_id']] = array();
							array_push($side_menu_pages[$link['link_id']],$lpage['date_id']);

							$spages = whileSQL("ms_calendar", "page_under,date_public,date_id", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
							while($spage = mysqli_fetch_array($spages)) {
								array_push($side_menu_pages[$link['link_id']],$spage['date_id']);
							}

							if(in_array($date['date_id'],$side_menu_pages[$link['link_id']])) {
								$spages = whileSQL("ms_calendar", "page_under,date_public,date_id,date_title,date_link,external_link", "WHERE page_under='".$lpage['date_id']."' AND date_public='1'   ORDER BY page_order ASC " );
								while($spage = mysqli_fetch_array($spages)) {
									if(!empty($spage['external_link'])) {
										print "<div class=\"sideMenuItem\"><div class=\"sub\"><a href=\"".$spage['external_link']."\">".$spage['date_title']."</a></div></div>\r"; 
									} else {
										if($date_id == $spage['date_id']) { ?>
											<div class="sideMenuItem"><div class="sub"><a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $spage['date_link'];?>/" class="sideMenuLinksOn"><?php print $spage['date_title'];?></a></div></div>
										<?php } else { ?>
											print "<div class="sideMenuItem"><div class="sub"><a href="/<?php if(!empty($setup['pages_folder'])) { print $setup['pages_folder']."/"; } print $spage['date_link'];?>/"><?php print $spage['date_title'];?></a></div></div>
										<?php 
										}
									}
								}
							}
						}
						?>
					</div></div>
					<div>&nbsp;</div>
						<?php 
				}
			}
		}
	}
}
?>

</div>
</div>

<?php
function categorySideMenu($limit,$where) { 
	global $setup, $site_setup,$lang,$date_cat_id,$date_id,$bcat,$top_section;
	$cset = doSQL("ms_calendar_settings", "*", "");
	$topcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$top_section."' ");
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);
	$scats = whileSQL("ms_blog_categories", "*", "WHERE cat_no_show='0' AND cat_under='".$top_section."' AND cat_status='1' ORDER BY cat_order ASC, cat_name ASC ");
	if(mysqli_num_rows($scats)>0) {
//	$html .= "<div class=\"header\"><a href=\"".$setup['content_folder']."".$topcat['cat_folder']."/\">".$topcat['cat_name']."</a></div>";
	$html .= "<ul>";
	$uncats = countIt("ms_calendar", "WHERE date_cat='0' AND date_public='1' AND date_type='news'  ");
	if($date_cat_id > 0) { 
		$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat_id."' ");
	}
	if($date_id > 0) { 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$date_id."' ");
		$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
	}
	$cat_array = array();
	if(!empty($bcat['cat_under_ids'])) { 
		$cat_array = explode(",",$bcat['cat_under_ids']);
	}

	while($scat = mysqli_fetch_array($scats)) { 

			$html .= "<li><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$scat['cat_folder']."/\""; if(($bcat['cat_id'] == $scat['cat_id'])OR(in_array($scat['cat_id'],$cat_array))==true) { $html .= " class=\"on\""; } $html .= ">".$scat['cat_name']."";
			if($cset['show_cat_count'] == "1") { 
				$bstats = countCatBlogPosts($scat['cat_id']);
				$html .= " (".$bstats['posts'].")";
			}
			$html .= "</a></li>";
			$html .= getBlogSubCats($cat_array,$date_cat_id,$scat,$bcat);
/*			
		if(countIt("ms_calendar", "WHERE (date_cat='".$scat['cat_id']."' OR date_cat2='".$scat['cat_id']."' OR date_cat3='".$scat['cat_id']."' OR date_cat4='".$scat['cat_id']."' ) AND date_public='1' AND  private<='1'  AND date_type='news' ")) { 

			$comma ++ ;
			if($comma > 1) { $html .= ", "; } 
			$html .= "<a href=\"".$setup['content_folder']."/".$scat['cat_folder']."/\">".$scat['cat_name']."</a>";
			if($cset['show_cat_count'] == "1") { $html .= " (".countIt("ms_calendar", "WHERE (date_cat='".$scat['cat_id']."' OR date_cat2='".$scat['cat_id']."' OR date_cat3='".$scat['cat_id']."' OR date_cat4='".$scat['cat_id']."' ) AND date_public='1' AND date_type='news' AND  private<='1' ").")"; } 
		}
*/
	}
	$html .= "</ul>";
	} 



	return $html;
}

	function getBlogSubCats($cat_array,$date_cat_id,$scat,$bcat) { 
		global $setup,$site_setup,$cset;
		if(($scat['cat_id'] == $bcat['cat_id'])OR(in_array($scat['cat_id'],$cat_array))==true) { 
			$sscats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$scat['cat_id']."' ");
			while($sscat = mysqli_fetch_array($sscats)) { 
				$html .= "<li>";
				$uids = explode(",",$sscat['cat_under_ids']);
				$level = count($uids);
				while($dx < $level) { $html .=  "&nbsp;&nbsp;"; $dx++; }
				$dx = 0;
				$html .= "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$sscat['cat_folder']."/\""; if($bcat['cat_id'] == $sscat['cat_id']) { $html .= " class=\"on\""; } $html .= ">".$sscat['cat_name']."";
				if($cset['show_cat_count'] == "1") { 
					$bstats = countCatBlogPosts($sscat['cat_id']);
					$html .= " (".$bstats['posts'].")";
				}
				$html .= "</a></li>";
				$html .= getBlogSubCats($cat_array,$date_cat_id,$sscat,$bcat);
			}
		}
		return $html;
	}
	?>
