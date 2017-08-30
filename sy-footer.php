</div>
<div class="cssClear"></div>
<?php 
if($date['green_screen_backgrounds'] > 0) { 
	$green_screen_backgrounds = $date['green_screen_backgrounds'];
	if($sub['sub_green_screen_backgrounds'] > 0) { 
		$green_screen_backgrounds = $sub['sub_green_screen_backgrounds'];
	}
	if($sub['sub_no_green_screen'] == "1") { 
		$green_screen_backgrounds = 0;
	}
	$pics_where = "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ";
	$and_where = getSearchString();
	$search = getSearchOrder();
	$total_images_count = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "$pics_where $and_where  $and_q GROUP BY pic_id  ORDER BY bp_order ASC  ");
	$total_images = mysqli_num_rows($total_images_count);
}
if($fav_green_screen_backgrounds > 0) { 
	$green_screen_backgrounds = $fav_green_screen_backgrounds;
}
if(($green_screen_backgrounds > 0) && ($total_images > 0) == true) { 
	?>
<?php if(!isset($_COOKIE['gsinfoshow'])) { ?>
<script>
$(document).ready(function() { 
	setTimeout(function() {
		$("#gsinfocontainer").fadeIn(300);
	}, 1000);

});
</script>

<div id="gsinfocontainer" class="hide">
	<div id="gsinfobg"></div>
	<div id="gsinfo">
		<div style="padding: 16px;">
			<div><?php print _select_background_description_;?></div>
			<div style=" margin: 16px;"><span class="submit" style="padding: 12px; cursor: pointer;" onclick="closegsinfo();"><?php print _select_background_got_it_;?></span></div>
			<div><span class="the-icons icon-down" style="font-size: 40px; color: #FFFFFF; text-shadow: 0px 0px 0px; opacity: 1;"></span></div>
		</div>
	</div>
</div>
<?php
$time=time()+3600*24*365*2;
$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$cookie_url = ".$domain";
SetCookie("gsinfoshow","1",$time,"/",null);

}  ?>

<div id="selectgsbackground">
<div style="padding: 8px; text-align: center; font-size: 17px;" onclick="opengsbackground();"><img id="minigsbg" style="max-height: 20px;vertical-align: middle; " > <?php print _select_background_;?></div>
	<div style="overflow-y: scroll; height: 100%;position: relative; max-height: 400px; display: none;" id="selectgsbg">
	<?php
	$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_sub='".$green_screen_backgrounds."' GROUP BY pic_id ORDER BY bp_order ASC");
	while($pic = mysqli_fetch_array($pics)) { 
		$px++;?>
		<a href="" onclick="selectGSbackground('thumbimage',true,'<?php print getimagefile($pic,'pic_large'); ?>','<?php print $pic['pic_key'];?>'); return false;"><img src="<?php print getimagefile($pic,'pic_th');?>" style="max-width: 120px; width: 100%; height: auto;"></a>
	<?php 
		if($px == 1) { 
			$defaultgsbg = getimagefile($pic,'pic_large'); 
			$defaultgsbgid = $pic['pic_key'];
		} 
		} ?>

	</div>
</div>
<script>
$(document).ready(function() { 
	$(".gs-bgimage-id-free").val('<?php print $defaultgsbgid;?>');
});
</script>


<input type="hidden" id="gs-bgimage" value="<?php print $defaultgsbg;?>">
<input type="hidden" id="gs-bgimage-id" value="<?php print $defaultgsbgid;?>">
<input type="hidden" id="gs-gal-id" value="<?php print $green_screen_backgrounds;?>">
<?php } ?>

<?php
if(($_REQUEST['vp'] > 0)&&(is_numeric($_REQUEST['vp'])) == true) { 
	$vp = $_REQUEST['vp'];
}

if($news_page == true){
	if($bcat['cat_id'] > 0) { 
		$page_viewed = "blog||cat||".$bcat['cat_id']."||$vp||".$topic['id']."";
	} else { 
		$page_viewed = "blog";
	}
}



if($date['date_id'] > 0) { 
	if($date['page_404'] ==  "1") { 
		$page_viewed = "page||404||".urlencode(sql_safe($_SERVER['REQUEST_URI']))."";
	} else { 
		$page_viewed = "page||".$date['date_id']."";
	}
}
if($page_id == "1") { 
	$page_viewed = "home||$vp";
}
if($tag_id > 0) { 
	$page_viewed = "tag||".$tag_id."";
}
if((!empty($_REQUEST['view'])) && ($_REQUEST['view'] !== "highlights") && ($_REQUEST['view'] !== "room")== true) { 
	if($_REQUEST['view'] == "cart") { 
		$page_viewed = "store||cart||";
	} elseif($_REQUEST['view'] == "checkout") {  
		$page_viewed = "store||checkout||";
	} elseif($_REQUEST['view'] == "account") { 
		$page_viewed = "store||account||";
	} elseif($_REQUEST['view'] == "newaccount") { 
		$page_viewed = "store||newaccount||";
	} elseif($_REQUEST['view'] == "checkoutexpress") { 
		$page_viewed = "store||expresscheckout||";
	} elseif($_REQUEST['view'] == "order") { 
		$page_viewed = "store||order||";
	} elseif($_REQUEST['view'] == "favorites") { 
		$page_viewed = "favorites";
	} elseif($_REQUEST['view'] == "contact") { 
		$page_viewed = "contact";
	} elseif($_REQUEST['view'] == "search") { 
		$page_viewed = "store||search||".urlencode(sql_safe(stripslashes($_REQUEST['q'])))."";
	} elseif(!empty($_REQUEST['q'])) { 
		$page_viewed = "store||search||".urlencode(sql_safe(stripslashes($_REQUEST['q'])))."";
	}
}

// print "<h1>".count($_SESSION['query'])." queries</h1>";
// print "<pre>"; print_r($_SESSION['query']); print "<pre>"; 
if((isset($_COOKIE['msaffc']))&&(!isset($_REQUEST['afc']))==true) { 
	$_REQUEST['afc'] = $_COOKIE['msaffc'];
}

?>
<script>
var pid = '<?php print $_SESSION['pid'];?>';
var date_id = '<?php if((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights")  || ($_REQUEST['view'] == "room") == true) { print $date['date_id']; } ?>';
var afc = '<?php print $_REQUEST['afc'];?>';
var page_viewed = '<?php print htmlentities($page_viewed);?>';
</script>


<script language="javascript">
$(document).ready(new function() {
	var ref=""+escape(top.document.referrer); 
	var colord = window.screen.colorDepth; 
	var res = window.screen.width + "x" + window.screen.height;
	var ptitle=document.title.replace(/&/g,'and'); 	
	var ptitle = addslashes(ptitle);
	var ptitle = escape(ptitle);
	var cururl=location.href;
	var reff=document.referrer;
	var reff=reff.replace("http://",''); 	
	var reff=reff.replace("https://",''); 	
	document.write("<img src=\"<?php print $setup['temp_url_folder'];?>/sy-vstats.php?res=" + res + "&colord=" + colord + "&page_viewed=<?php print htmlentities($page_viewed);?>&date_id=<?php if((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights")  || ($_REQUEST['view'] == "room") == true) { print $date['date_id']; } ?>&reff=" + reff + "&js=yes&pid=<?php print $_SESSION['pid'];?>&afc=<?php print $_REQUEST['afc'];?>\" width=1 height=1 border=0 style=\"opacity: 0;\">");
});
</script><noscript></noscript>

<?php 
include $setup['path']."/sy-inc/email_form_popup.php";

 if(!empty($site_setup['footer_ext'])) {
	 include $site_setup['footer_ext'];
  } else {
	  ?>
<?php if($css['sweetness'] !== "1") { ?>

<?php if(($date['date_gallery_exclusive'] == "1") && ($ge['gal_remove_footer'] == "1") == true) { ?>
<div id="gefooter" class="pc">
	<?php print $ge['gal_footer'];?>
</div>
<div id="ge-site-link" class="pc center"><a href="<?php print $setup['temp_url_folder'];?>/"><?php print $site_setup['website_title'];?></a></div>
<?php } ?>

<?php if($css['footer_outside'] == "1") { ?></div></div></div><?php } ?>
	<div id="footer" <?php if(($date['date_gallery_exclusive'] == "1") && ($ge['gal_remove_footer'] == "1") == true) { ?>class="hide"<?php } ?>>
		<div id="footerinner">
	<?php if($setup['affiliate_links'] == 1) { ?>
	 <?php 
		$time = strtotime("".$site_setup['time_diff']." hours");
		$cur_time =date('Y-m-d H:i:s', $time);
		$stags = whileSQL("ms_tags", "*", " ORDER BY tag_tag ASC ");
		if(mysqli_num_rows($stags)>0) {
			print  "<div style=\"text-align: center;\">";
			print  "<div class=\"pageContent\"><h2>All "._tags_."</h2></div>";
			print  "<div class=\"pageContent\">";
			print  "<div class=\"categoryList\">";
			while($stag = mysqli_fetch_array($stags)) { 
				if(countIt("ms_tag_connect", "WHERE tag_tag_id='".$stag['tag_id']."'  ") > 0) { 
					$comma ++ ;
					if($comma > 1) { print  ", "; } 
					print  "<nobr><a href=\"/tags/".$stag['tag_folder']."/\">".$stag['tag_tag']."</a></nobr>";
				}
			}
			print  "</div></div></div>";
		} 
	?>
	<?php } ?>
	<?php 
		 if(!customerLoggedIn()) { 
			 $and_link = "AND link_logged_in='0' ";
		 }
			
		$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' $and_link AND link_location!='favs' AND link_location!='shop' AND link_location!='side' ORDER BY link_order ASC ");
		while($link = mysqli_fetch_array($links)) { 
			if(!empty($link['link_main'])) { 


	//			$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$setup[$link['link_main']]."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 


				if(($link['link_main'] == "favorites")&&(customerLoggedIn())==true) {  
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=favorites\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
				}
				if($link['link_main'] == "findphotos") {
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=findphotos\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				}
				if(($link['link_main'] == "myaccount")&&(customerLoggedIn())==true) { 
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

				 if(($link['link_main'] == "newaccount")&&(!customerLoggedIn())==true) {  
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=newaccount\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 
				if(($link['link_main'] == "login")&&(!customerLoggedIn())==true) {
					$fl .= "<a href=\"".gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=account\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

				if(($link['link_main'] == "logout")&&(customerLoggedIn())==true) { 
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=logout\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 	
				} 

			} elseif($link['link_page'] > 0) {
				$lpage = doSQL("ms_calendar", "*", "WHERE date_id='".$link['link_page']."' ");
				if($lpage['page_home'] == "1") {
					if($site_setup['index_page'] == "indexnew.php") { 
						$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/indexnew.php\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
					} else { 
						$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
					}
				} else {
					$fl .= "<a href=\"".removesecure()."".$setup['temp_url_folder']."/".$lpage['date_link']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; "; 
				}
				} elseif($link['link_cat'] > 0) {
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$link['link_cat']."' ");
				
				$fl .= "<a href=\"".$setup['temp_url_folder']."".$cat['cat_folder']."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp; ";

			} else { $fl .="<a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a> &nbsp;"; } 
	} 
	?>
	<?php 
	$footer = $site_setup['footer'];
	$footer = str_replace("[YEAR]", date('Y'), $footer);
	$footer = preg_replace('#\[SOCIAL_LINKS]#i', showSocialLinks(),$footer);  
	$footer = preg_replace('#\[MENU_LINKS]#i', $fl,$footer);  
	$footer = str_replace("[SITE_NAME]", "<a href=\"".$setup['temp_url_folder']."/\">".$site_setup['website_title']."</a>&nbsp; ", $footer);
	if(($_REQUEST['view'] !== "checkout") && ($_REQUEST['view'] !== "newaccount") == true) { 
		$footer = preg_replace_callback('#\[JOIN_MAILING_LIST]#i',includemailform,$footer);  
	} else { 
		$footer = preg_replace('#\[JOIN_MAILING_LIST]#i',"",$footer);  
	}
	print $footer;
	?>
	</div>
<?php if($css['footer_outside'] !== "1") { ?></div></div></div><?php } ?>
</div><?php } ?>
<!-- <div id="footerSpacer"></div> -->
<?php } ?>



<script>

 $(document).ready(function(){
	if(norightclick == '1') { 
		disablerightclick();
	}
	$(".preview").hover(
	  function () {
		$(this).find('.quickview').show();
	  },
	  function () {
		$(this).find('.quickview').hide();
	  }
	);

	 mytips(".tip","tooltip");
	 $(".searchlink").append($("#searchformhtml").html());
	$(".searchlink").hover(
	  function () {
		$(this).find('.searchform').slideDown(100);
	  },
	  function () {
		$(this).find('.searchform').slideUp(100);
	  }
	);

	adjustsite();
	<?php if($date['date_gallery_exclusive'] <= 0) { ?>
	<?php if(($css['sm_pin_top'] == "1")&&(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='shop' ORDER BY link_order ASC ")>0)==true) { ?>
	menuPosition('shopmenucontainer','smc');
	<?php } ?>
	<?php if($css['header_pin_top'] == "1") { ?>
		<?php if(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='shop' ORDER BY link_order ASC ")>0) { ?>
		menuPosition('shopmenucontainer','smc');
	 <?php } ?>
	 menuPosition('headerAndMenu','hc');
	<?php } ?>
	<?php } ?>
	$(window).resize(function() {
		adjustsite();
		<?php if($date['date_gallery_exclusive'] <= 0) { ?>
		<?php if(($css['sm_pin_top'] == "1")&&(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='shop' ORDER BY link_order ASC ")>0)==true) { ?>
		menuPosition('shopmenucontainer','smc');
		<?php } ?>
		<?php if($css['header_pin_top'] == "1") { ?>
		<?php if(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='shop' ORDER BY link_order ASC ")>0) { ?>
			menuPosition('shopmenucontainer','smc');
		<?php } ?>
		 menuPosition('headerAndMenu','hc');
		<?php } ?>
			<?php } ?>
	});

    $(".flymenu").hoverIntent(function(){
		menuwidth = $(this).find(".flyout").width();
		ml = menuwidth / 2;
		$(this).css({"position":"static"});
		$(this).find(".flyout").css({"margin-left":"-"+ml / 2+"px"});
		$(this).find(".flyout").stop(true,true).fadeIn(100);
    }, function(){    
  		$(this).children(".flyout").stop(true,true).fadeOut(100);
  
    });

	setInterval(function() {
		$.get('<?php tempFolder();?>/sy-inc/refresh.php', function(data) { });
	}, 600000);


	<?php if($layout['layout_width'] > 0) { 
		$width = $layout['layout_width'];
		} else  {
		$width = $css['on_photo_width'];		
		} 
	?>

	onphotoheightperc = <?php print $css['on_photo_height'] / $css['on_photo_width'];?>;
	onphotominwidth = <?php print $width + (($css['on_photo_margin'] + $css['on_photo_border_size'])*2)-8;?>;
	onphotoperrow = Math.floor($("#listing-onphoto").width() / onphotominwidth);
	if(onphotoperrow <=0) { 
		onphotoperrow = 1;
	}

	onphotomargin = <?php print (($css['on_photo_margin'] + $css['on_photo_border_size'] ));?> * (2 * onphotoperrow)+1;
	onphotonewwidth = ($("#listing-onphoto").width() - onphotomargin) / onphotoperrow;
	onphotonewheight = (($("#listing-onphoto").width() - onphotomargin) / onphotoperrow) * onphotoheightperc;
	placeonphoto(onphotoheightperc,onphotominwidth,onphotoperrow,onphotomargin,onphotonewwidth,onphotonewheight);

	<?php if($layout['layout_width'] > 0) { 
		$width = $layout['layout_width'];
		} else  {
		$width = $css['thumb_listing_width'];		
		} 
	?>

	tlheightperc = <?php print $css['thumb_listing_height'] / $css['thumb_listing_width'];?>;
	<?php if(($setup['mobile_layout_min_width'] > 0)&&($mobile == true)==true) { ?>
	tlminwidth = 160;
	<?php } else { ?>
	tlminwidth = <?php print $width + (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['thumb_listing_padding'])*2)-8;?>;
	<?php } ?>

	tlperrow = Math.floor($("#listing-thumbnail").width() / tlminwidth);
	tlmargin = <?php print (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['thumb_listing_padding']));?> * (2 * tlperrow)+1;
	tlnewwidth = ($("#listing-thumbnail").width() - tlmargin) / tlperrow;
	placethumblisting(tlnewwidth);

	<?php if($layout['layout_width'] > 0) { 
		$width = $layout['layout_width'];
		} else  {
		$width = $css['thumb_listing_width'];		
		} 
	?>
	tlheightperc = <?php print $css['thumb_listing_height'] / $css['thumb_listing_width'];?>;
	<?php if(($setup['mobile_layout_min_width'] > 0)&&($mobile == true)==true) { ?>
	tlminwidth = 160;
	<?php } else { ?>
	tlminwidth = <?php print $width + (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['stacked_listing_padding'])*2)-8;?>;
	<?php } ?>
	tlperrow = Math.floor($("#listing-stacked").width() / tlminwidth);
	tlmargin = <?php print (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['stacked_listing_padding']));?> * (2 * tlperrow)+1;
	tltotalmargin = <?php print (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['stacked_listing_padding']) )+8;?>;
	tlnewwidth = ($("#listing-stacked").width() - tlmargin) / tlperrow;
	placestackedlisting(tlnewwidth);


    (function ($){
      var handler = $('#listing-stacked .preview');
      handler.wookmark({
		container: $('#listing-stacked'), // Optional, used for some extra CSS styling
		align: "left",
		offset: <?php print $css['thumb_listing_margin'] * 2;?>, // Optional, the distance between grid items
		outerOffset: 0 // Optional, the distance to the containers border
		// itemWidth: Math.abs(tlnewwidth) + Math.abs(tltotalmargin) // Optional, the width of a grid item
      });
      
    })(jQuery);

	$(window).resize(function() {
	resizelistings();
	});
 });

function resizelistings() { 
		onphotoheightperc = <?php print $css['on_photo_height'] / $css['on_photo_width'];?>;
		onphotominwidth = <?php print $css['on_photo_width'] + (($css['on_photo_margin'] + $css['on_photo_border_size'])*2)-8;?>;
		onphotoperrow = Math.floor($("#listing-onphoto").width() / onphotominwidth);
		if(onphotoperrow <=0) { 
			onphotoperrow = 1;
		}
		onphotomargin = <?php print (($css['on_photo_margin'] + $css['on_photo_border_size'] ));?> * (2 * onphotoperrow)+1;
		onphotonewwidth = ($("#listing-onphoto").width() - onphotomargin) / onphotoperrow;
		onphotonewheight = (($("#listing-onphoto").width() - onphotomargin) / onphotoperrow) * onphotoheightperc;
		placeonphoto(onphotoheightperc,onphotominwidth,onphotoperrow,onphotomargin,onphotonewwidth,onphotonewheight);

	<?php if($layout['layout_width'] > 0) { 
		$width = $layout['layout_width'];
		} else  {
		$width = $css['thumb_listing_width'];		
		} 
	?>

		tlheightperc = <?php print $css['thumb_listing_height'] / $css['thumb_listing_width'];?>;
		tlminwidth = <?php print $width + (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['thumb_listing_padding'])*2)-8;?>;
		tlperrow = Math.floor($("#listing-thumbnail").width() / tlminwidth);
		tlmargin = <?php print (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['thumb_listing_padding']));?> * (2 * tlperrow)+1;
		tlnewwidth = ($("#listing-thumbnail").width() - tlmargin) / tlperrow;
		placethumblisting(tlnewwidth);

	<?php if($layout['layout_width'] > 0) { 
		$width = $layout['layout_width'];
		} else  {
		$width = $css['thumb_listing_width'];		
		} 
	?>

		tlheightperc = <?php print $css['thumb_listing_height'] / $css['thumb_listing_width'];?>;
		<?php if(($setup['mobile_layout_min_width'] > 0)&&($mobile == true)==true) { ?>
		tlminwidth = 160;
		<?php } else { ?>
		tlminwidth = <?php print $width + (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['stacked_listing_padding'])*2)-8;?>;
		<?php } ?>
		tlperrow = Math.floor($("#listing-stacked").width() / tlminwidth);
		tlmargin = <?php print (($css['thumb_listing_margin'] + $css['thumb_listing_border_size'] + $css['stacked_listing_padding']));?> * (2 * tlperrow)+1;
		tlnewwidth = ($("#listing-stacked").width() - tlmargin) / tlperrow;
		placestackedlisting(tlnewwidth);
		(function ($){
		  var handler = $('#listing-stacked .preview');
		  handler.wookmark({
			container: $('#listing-stacked'), // Optional, used for some extra CSS styling
			align: "left",
			offset: <?php print $css['thumb_listing_margin'] * 2;?>, // Optional, the distance between grid items
			outerOffset: 0 // Optional, the distance to the containers border
			// itemWidth: Math.abs(tlnewwidth) + Math.abs(tltotalmargin) // Optional, the width of a grid item
		  });
		  
		})(jQuery);

}
</script>
<?php if($billboard['bill_id'] > 0) { ?>
<script>
 $(document).ready(function(){
	$(window).resize(function() {
	resizeImgToBillboard($("#slide"+$("#neatbbslides").attr("cbb")+"g"),'neatbb');
	});
});
</script>
<?php } 

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}

?>
<?php if(($date_cat_id > 0) && ($bcat['cat_auto_populate'] > 0) == true) { 	?>
<script> setTimeout(function() { getPageListings()},500); </script>
<?php } ?>
<?php if(($date['date_feature_cat'] > 0)&&($date['date_feature_auto_populate'] == "1")==true) { ?>
<script> setTimeout(function() { getPageListings()}, 500); </script>
<?php } ?>
<?php
	if(($date['hide_sub_gals'] <= 0) || (($date['hide_sub_gals'] == "1") && ($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) && ($person['p_id'] > 0)) == true) { 
		if(!empty($_REQUEST['sub'])) { 
			$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
			if($sub['sub_id'] > 0) { 
				$sub_under = $sub['sub_id'];
			} else { 
				$sub_under = 0;
			}
		}
		if(($date['date_id'] > 0) && (empty($_REQUEST['view'])) == true) { 
		if((countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$sub_under."' ") >  0)&&(empty($_REQUEST['kid'])) && ($no_subs !== true) ==true) { 
		?>
		<script type="text/javascript">
		 $(document).ready(function(){
			$.get("<?php tempFolder();?>/sy-inc/sy-sub-galleries.php?subpage=1&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&cat_id=<?php print $bcat['cat_id'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>&keyWord=<?php print $_REQUEST['keyWord'];?>&view=<?php print $_REQUEST['view'];?>&untagged=<?php print $_REQUEST['untagged'];?>&pic_camera_model=<?php print $_REQUEST['pic_camera_model'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>&hash_image=<?php print $hash_image;?>&orientation=<?php print $_REQUEST['orientation'];?>&kid=<?php print $_REQUEST['kid'];?>&price_list=<?php print $list['list_id'];?>&mobile=<?php print $mobile;?>&from_time=<?php print $_REQUEST['from_time'];?>&search_date=<?php print $_REQUEST['search_date'];?>&search_length=<?php print $_REQUEST['search_length'];?>", function(data) {
				$(".sub-galleries-populate").append(data);
				$("#loadingSubs").fadeOut(100);
				resizelistings();
				setTimeout(hideLoadingMore,1000);
				setTimeout(function() { getSubGalleries()},1000);
				if(norightclick == '1') { 
					disablerightclick();
				}

			});
			$("#vinfo").attr("subPageID",1);
		});

		</script>

		<?php 
		}
	}
}

/*
if(!empty($cat['cat_pic_tags'])) { 
	$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$cat['cat_id']."' "); 
	$date['thumb_style'] = $def['thumb_style'];
	$show_thumbnails = true;
}
*/
  
 if(($show_thumbnails == true) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "favorites") || ($_REQUEST['view'] == "highlights") ) == true){
	if(!empty($_REQUEST['sub'])) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
	}
	?>


<script type="text/javascript">
 $(document).ready(function(){

	$.get("<?php tempFolder();?>/sy-inc/sy-thumbnails.php?page=<?php print $_REQUEST['page'];?>&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&cat_id=<?php print $bcat['cat_id'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>&keyWord=<?php print $_REQUEST['keyWord'];?>&view=<?php print $_REQUEST['view'];?>&untagged=<?php print $_REQUEST['untagged'];?>&pic_camera_model=<?php print $_REQUEST['pic_camera_model'];?>&pic_upload_session=<?php print $_REQUEST['pic_upload_session'];?>&orderBy=<?php print $_REQUEST['orderBy'];?>&acdc=<?php print $_REQUEST['acdc'];?>&hash_image=<?php print $hash_image;?>&orientation=<?php print $_REQUEST['orientation'];?>&kid=<?php print $_REQUEST['kid'];?>&price_list=<?php print $list['list_id'];?>&mobile=<?php print $mobile;?>&from_time=<?php print $_REQUEST['from_time'];?>&search_date=<?php print $_REQUEST['search_date'];?>&search_length=<?php print $_REQUEST['search_length'];?>&passcode=<?php print $_REQUEST['passcode'];?>", function(data) {
		$("#displayThumbnailPage").append(data);
		<?php if($_REQUEST['page'] > 0) { ?>
		$("#displayThumbnailPage").scrollView();
		<?php } ?>
			if(norightclick == '1') { 
				disablerightclick();
			}

		setTimeout(hideLoadingMore,1000);
		getDivPosition('endpage');
	});
	<?php if(empty($_REQUEST['page'])) { ?>
	$("#vinfo").attr("thumbPageID",1);
	<?php } else { ?>
	$("#vinfo").attr("thumbPageID",<?php print $_REQUEST['page'];?>);
	<?php } ?>
});

</script>
<?php if($date['thumb_style'] == "0") { ?>
<script>
$(window).resize(function() {
	jthumbs();
});
</script>
<?php } ?>

<script>
 $(document).ready(function(){
	$("#thumbPageID").val('<?php print $page;?>');
	$(window).resize(function() {
	});

});
</script>

<script>
 $(document).ready(function(){
	$("#thumbPageID").val('<?php print $page;?>');
	$(window).resize(function() {
	if($("#photo-"+$("#slideshow").attr("curphoto")+"-container").css("display")=="block") { 
		sizePhoto($("#slideshow").attr("curphoto"));
	}
	getSSCaption("photo-"+$("#slideshow").attr("curphoto"));
});




		
});

function checkphotoview() { 
	if(!document.location.hash) { 
		closeFullScreen();
		$("#slideshow").attr("disablenav","0");
	}
	photo = document.location.hash;
	photo = photo.replace("#","");
	p = photo.split("=");
	if(p[0] == "photo" ) { 
		if(p[1] == "thumbs" || p[1] == "") { 
			closeFullScreen();
			$("#slideshow").attr("disablenav","0");
		}
	}

}

 $(document).ready(function(){
	//setInterval("checkphotoview()",200);
	if(document.location.hash) { 
		photo = document.location.hash;
		photo = photo.replace("#","");
		p = photo.split("=");
		if(p[1] !== "thumbs") { 
			if(p[0] == "gti") { 
				closecomparephotos();
			}

			window.setTimeout(function(){   
				loadsytistphoto(p[1])
			},1000);
		}
	}
	jQuery(window).hashchange(function() {

		if(!document.location.hash) { 
			closeFullScreen();

		}

		photo = document.location.hash;
		photo = photo.replace("#","");
		p = photo.split("=");
		if(p[0] == "photo" || p[0] == "gti") { 
			if(p[1] == "thumbs" || p[1] == "") { 
				closeFullScreen();
			} else { 
				 if($("#slideshow").attr("disablenav") !== "1" && ($("#slideshow").attr("curphoto") !== p[1] || $("#slideshow").attr("fullscreen") !== 1 || $("#slideshow").attr("comparephotos") == 1)) { 
					loadsytistphoto(p[1]);
				 }
				closecomparephotos();
			}
		}
	});

});


</script>
<?php }

 if(!empty($_REQUEST['image'])) { 
	$_REQUEST['image'] = addslashes(stripslashes(strip_tags($_REQUEST['image'])));
	$_REQUEST['image'] = sql_safe("".$_REQUEST['image']."");
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['image']."' ");
	if(!empty($pic['pic_id'])) { 
		$size = getimagefiledems($pic,'pic_large');
		?>
		<script>
		function closesinglephoto() { 
			$("#singlephoto").fadeOut(200);
			$("#singlephotocontainer").fadeOut(200);
		}
		</script>
		<div id="singlephoto"  onclick="closesinglephoto(); "></div>
		<div id="singlephotocontainer"  onclick="closesinglephoto(); ">
		<img src="<?php print getimagefile($pic,'pic_large'); ?>" class="photo" style="position: fixed; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; left: 50%; top: 80px; margin-left: -<?php print ($size[0] / 2);?>px; z-index: 201;" onclick="closesinglephoto();">
		<div id="" style="position: fixed; right:0; top: 50px; z-index: 201;"><span  onclick="closesinglephoto(); return false;" class="icon-cancel-circled the-icons" style=" font-size: 48px;"></span></div>
		</div>
		</div>
		<?php 
	}
}


/* COOKIE WARNING */ 
$cs = doSQL("ms_cookie_warn", "*", "");
if($cs['cookie_status'] == "1") { 
	if(!isset($_COOKIE['sycookiewarn'])) {
		$time=time()+3600*24*365*2;
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		$cvar = $ip.date('Ymdhis');
		SetCookie("sycookiewarn",'1',$time,"/",null);
		?>
		<div id="cookiewarning">
		<div class="pc"><?php print nl2br($cs['message']);?>  &nbsp;  &nbsp; <a href="" onclick="acceptcookie(); return false"><?php print $cs['approve_button'];?></a> &nbsp; <a href="<?php print $cs['reject_url'];?>"><?php print $cs['reject_link'];?></a></div>
		</div>
		<script>
		function acceptcookie() { 
			$("#cookiewarning").slideUp(300);
		}
		</script>
		<?php 
	}
}
 if($setup['affiliate_links'] == 1) { ?>
	<?php if($date['date_aff_site'] > 0) { 
		$site = doSQL("ms_aff_info", "*", "WHERE aff_id='".$date['date_aff_site']."' "); 
		if(!empty($site['aff_main_link'])) { 
		?>
	<div id="bottomBanner"><div class="inner"><h2>
		<?php print "<a href=\"\"  onClick=\"window.open('/viewsite.php?site=".MD5($site['aff_id'])."&l=bb&p=".$date['date_id']."'); return false;\">";
		if(!empty($date['date_aff_text'])) { print $date['date_aff_text']; } else { print $site['aff_ad_text_2']; } 
		print "</a>";  ?>
	</h2></div>
	</div>
	<?php }
		} 
 } 
 ?><div id="loadingMore"><?php print _loading_more_;?></div>
<div id="gototop" onclick="gototop();"><?php print _scroll_to_top_;?></div>
<div id="photo-preview">
	<div style="padding: 8px; position: relative; display: block;">
	<div id="photo-preview-title" class="pc"></div>
	<div id="photo-preview-photo" ></div>
	<div id="photo-preview-caption" class="pc"></div>
	<div id="photo-preview-filename" class="pc"></div>
	<div id="photo-preview-keywords" class="pc"></div>
	</div>
</div>
</div>
<div id="comparephotos">
		<div id="comparenav">
			<div id="comparenavinner"><a href="" onclick="closecomparephotos(); return false;"><?php print _compare_close_;?></a> | <a href="" onclick="closecomparephotos('1'); return false;"><?php print _compare_close_and_clear_;?></a></div>
		</div>
	</div>
	<div id="comparephotosdisplaycontainer" class="compareinner">
		<div id="comparephotosdisplay">
		</div>
	</div>
	<?php 
	if(($_REQUEST['view'] == "checkoutexpress")||($_REQUEST['view'] == "checkout")==true) { 

	 } else { 
		if((($mobile == true)||($ipad == true))&&($setup['add_mobile_footer_checkout'] == true)==true) { ?>
		<div id="footercheckout" class="<?php if(countIt("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "WHERE ".checkCartSession()."  AND cart_order<='0' ") <= 0) {  ?>hide<?php } ?>" style=" background: #000000; position: fixed;  bottom: 0px; left: 0px; width: 100%; z-index: 10; color: #FFFFFF; font-size: 27px; text-align: center;" >
		 <div style="padding: 16px;">
		 <div style="float: left; width: 50%; text-align: center;"><span  onclick="window.location.href='/index.php?view=cart'">View Cart </span></div>
		 <?php if((countIt("ms_payment_options", "WHERE pay_option='paypalexpress' AND pay_status='1' ") =="1")==true) { ?>
			<div style="float: left; width: 50%; text-align: center;"><span  onclick="ppexpresscheckout(); return false;">Checkout</span></div>
			<?php } else { ?>
		 <div style="float: left; width: 50%; text-align: center;"><span  onclick="window.location.href='/index.php?view=checkout'">View Cart </span></div>
			<?php } ?>
		<div class="clear"></div>	 
		 </div>
		</div>
	<?php } ?>
<?php } ?>


<?php 
if(!empty($_REQUEST['previewLayout'])) { 
	?>
	<div style="z-index: 10000; position: fixed; bottom: 0; width: 700px; left: 50%; margin-left: -350px; text-align: center; background: #747474; border: solid 1px #545454; color: #FFFFFF;">
		<div style="padding: 4px;">
		Select Layout to Preview: <form method="get" name="layoutpreview" action="<?php print $_SERVER['PHP_SELF'];?>" style="display: inline;">
		<select name="previewLayout" onchange="this.form.submit()" style="padding: 0px;">
		<?php 
		$pls = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC   ");
		while($pl = mysqli_fetch_array($pls)) { ?>
		<option value="<?php print $pl['layout_id'];?>" <?php if($_REQUEST['previewLayout'] == $pl['layout_id']) { print "selected"; } ?>><?php print $pl['layout_name'];?></option>
		<?php } ?>
		</select>
		</form>	
	</div></div>
	<?php 
}
?>

<div id="photoproductsnexttophotobg"></div>
<div id="photoproductsnexttophoto">
	<div class="inner photoproductsnexttophoto"></div>
 </div>


<div id="searchformhtml" class="hide">
	<div class="searchform hide">
	<form method="get" name="searchform" action="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>">
	<input type="text" name="q" size="20" value="">
	<input type="hidden" name="view" value="search">
	<input type="submit" name="submit" value="<?php print _search_button_;?>" class="submit">
	</form>
	</div>
</div>

<div id="log" style="display: none; position: fixed; top:0; left: 0; background: #000000; color: #FFFFFF; z-index: 10001;"></div>

<?php if($date['cat_type'] == "proofing") { ?>
<div id="completereview">
	<div class="inner">
		<div class="pc"><h2><?php print _proof_review_complete_;?></h2></div>
		<div class="pc"><?php print _proof_review_complete_message_;?></div>
		<div class="pc"><textarea id="review_complete_message" rows="3" cols="40" class="field100"></textarea></div>
		<div class="pc" id="reviewsendbuttons"><span ><a href="" onclick="sendreview(); return false;" class="checkout"><?php print _proof_send_review_;?></a></span> <a href="" onclick="cancelsendreview(); return false;"><?php print _proof_cancel_send_review_;?></a> </div>
		<div class="pc hide the-icons icon-spin5" id="reviewsendloading">&nbsp;</div>
		<div class="hide" id="reviewcomplete">
			<div class="pc"><?php print _proof_review_submited_message_;?></div>
			<div class="pc"><a href="" onclick="closecompletereview(); return false;"><?php print _proof_close_review_complete_;?></a></div>		
		</div>
	</div>
</div>

<?php } ?>

<div id="enlargephoto" class="zoomCur"></div>
<div id="photoprods">
<div id="closebuyphototab" class="closewindow" onclick="closebuyphoto(); return false;"><div class="inner"><?php print _close_package_window_;?></div></div>
<div id="closebuyphoto"  class="closewindow" style="display: none; position: absolute; right: -32px; top: -12px;"><span  onclick="closebuyphoto(); return false;" class="icon-cancel-circled the-icons" style=" font-size: 48px;"></span></div>
<div id="photoprodsinner"></div>
</div>
<div id="photocrop">
<div id="photocropinner"></div>
</div>
<?php if($_REQUEST['view'] == "checkout") { ?>
<div id="termsandconditions">
	<div id="termsandconditionsinner">
	<div class="pc"><?php print nl2br($store['terms_conditions']);?></div>
	<div class="pc center"><a href="" onclick="agreetoterms(); return false;"><?php print _i_agree_;?></a> &nbsp; <a href="" onclick="donotagreetoterms(); return false;"><?php print _i_do_not_agree_;?></a></div>
	</div>
</div>
<?php } ?>
<div id="loading"><?php print _loading_;?></div>
<div id="photopackagecontainer" status="0">
<div id="closeaddtopackage" style="display: none; position: absolute; right: -32px; top: -12px;"><span  onclick="closeaddtopackage(); return false;" class="icon-cancel-circled the-icons" style=" font-size: 48px;"></span></div>

	<div id="photopackageinner"></div>
</div>
<?php if($fb['disable_facebook'] !== "1") {
	?>
<div id="fbnotify"></div>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId  : '<?php print $fb['facebook_app_id'];?>', // CHANGE THIS APP ID
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml  : true  // parse XFBML
    });
	<?php 	if($com_settings['email_new_comments'] == "1")  { ?>
	FB.Event.subscribe('comment.create', function(response) {
		sendFacebookNotify('Comment','<?php print $url;?>','<?php print MD5($_SESSION['ms_session']);?>');
	});
	<?php } ?>
	<?php if($fb['email_like'] == "1") { ?>
	FB.Event.subscribe('edge.create', function(response) {
		sendFacebookNotify('Like', '<?php print $url;?>','<?php print MD5($_SESSION['ms_session']);?>');
	});
	<?php } ?>

};
  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/<?php print $fb['fb_lang'];?>/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
<?php } ?>

<div id="roomuploadbackground"></div>
<div id="sidethumbsbg"></div>
<div id="sidethumbs">
<div  id="sidethumbsclose" onclick="sidethumbsclose();" >
	<div style="padding: 8px;"><span class="the-icons icon-cancel"><?php print _compare_close_; ?></span></div>
</div>
	<div id="sidethumbsinner">
	
	</div>
	<div class="clear"></div>
</div>

</body>
<?php if((!empty($_REQUEST['viewPhoto']))OR($slideShowD == true)OR($load_standard_photos == true)==true) { ?>
<script type="text/javascript">
function loadAll() {
<?php if($slideShowD == true) { ?>
slideShowD('<?php print $billboard_ss_id ;?>','<?php print $billboard_slides;?>',<?php print $billboard_seconds;?>);
<?php } ?>
<?php if(($load_standard_photos == true)AND(countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE bp_blog='".$date['date_id']."' ") > 10)==true) {  ?>
document.getElementById('curPage').value = '1';
<?php	} ?>
}
</script>
<script>
window.onload=loadAll;
window.onunload = function(){};
</script>
<?php } ?>
</html>