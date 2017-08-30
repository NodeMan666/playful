<script>
 $(document).ready(function(){
	$("#filter").hover(
		function () {
		$(".filteroption").css({"background-color":$("#<?php print $get_color_from;?>").css("background-color")});
		$(this).children(".filteroption").show();
		},
		function () {
		$(this).children(".filteroption").hide();
		}
	);

	$("#shareoptions").hover(
		function () {
		$(".shareoption").css({"background-color":$("#<?php print $get_color_from;?>").css("background-color")});
		$(this).children(".shareoption").show();
		},
		function () {
		$(this).children(".shareoption").hide();
		}
	);
	$("#photo-fav-login").hover(
		function () {
		$(".favlogin").css({"background-color":$("#<?php print $get_color_from;?>").css("background-color")});
		$(this).children(".favlogin").show();
		},
		function () {
		$(this).children(".favlogin").hide();
		}
	);

});
</script>
<div  id="photomenu" style="margin: auto; ">
<ul>
<?php
$fb = doSQL("ms_fb", "*", "");
$total_prods = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$list['list_id']."' AND pp_free!='1' ORDER BY pc_order ASC ");
?>
 <li class="icon-basket the-icons" onclick="buyphoto('0'); return false;" id="buy-photo" <?php if(($list['list_id']<=0 || $total_prods <=0 || $list['list_products_placement']=="1")==true) {  ?>style="display: none;"<?php } ?>><br><span class="icon-text"><?php print _buy_photo_;?></span></li>
 <li class="icon-picture the-icons" onclick="buyphoto('1'); return false;" id="buy-packages" <?php if(countIt("ms_photo_products_groups", "WHERE group_package='1' AND group_list='".$date['date_photo_price_list']."' ") <=0)  { ?>style="display: none;"<?php } ?>><br><span class="icon-text"><?php print _buy_packages_;?></span></li>


 <li class="icon-download the-icons" onclick="freephoto(); return false;" id="free-photo" <?php if($free <= 0) { ?>style="display: none;"<?php } ?>><br><span class="icon-text"><?php print _free_download_;?></span></li>

 <?php if($date['allow_favs'] == "1") { ?>
 <?php if(!isset($_SESSION['pid'])) { ?>
 <li class="icon-heart-empty the-icons photo-fav" id="photo-fav-login" <?php if(!customerLoggedIn()) { ?> onclick="showgallerylogin('favorites','<?php print $sub['sub_link'];?>','<?php print $_REQUEST['pic'];?>',''); return false;" <?php } ?>><br><span class="icon-text"><?php print _favorite_;?></span></li>
 <?php } else { ?>
 <li class="icon-heart-empty the-icons photo-fav" onclick="addphotofav(); return false;" id="photo-fav"><br><span class="icon-text"><?php print _favorite_;?></span></li>
<?php } ?>
<?php } ?>
<?php
if(!empty($date['date_meta_title'])) { 
	$date['date_title'] = $date['date_meta_title'];
}
$date['date_title'] = $date['date_title']." - ".$site_setup['website_title'];
?>
 <li class="icon-share the-icons"  id="shareoptions" data-share-descr="<?php print htmlspecialchars(urlencode($fb['fb_photo_share']));?>" <?php if($date['photo_social_share'] !== "1") { ?>style="display: none;"<?php } ?>><br><span class="icon-text"><?php print _share_;?></span>
 		<div class="shareoption" style="display: none; position: absolute; border: solid 1px #999999; margin: 0; text-align: left; z-index: 200; font-size: 12px; text-shadow: none; padding: 8px;">
		<div class="left"><a href=""  onclick="sharephoto('facebook','<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']);?>','','<?php print $date['private'];?>');  return false;" class="icon-facebook the-icons"></a></div>
		<div class="left"><a href=""  onclick="sharephoto('pinterest','<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>');  return false;"  class="icon-pinterest the-icons"></a></div>
		<div class="left"><a href=""   onclick="sharephoto('twitter','<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($date['date_title']);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>');  return false;" class="icon-twitter the-icons"></a></div>
		<div class="left"><a href="" onclick="sharephoto('email','<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/','','<?php print urlencode($date['date_title']);?>','','<?php print $date['private'];?>');  return false;" class="icon-mail the-icons"></a></div>

		<div class="clear"></div>
		</div>
 </li>

	<?php if(($list['list_filters'] == "1") && (countIt("ms_color_options", "WHERE color_status='1' ") > 0)==true){ ?>
	<?php if($date['green_screen_backgrounds'] <= 0 ) { ?>
		<li id="filter" color_id="" style="position: relative;" class="icon-palette the-icons"><br><span class="icon-text"><?php print _filter_;?></span> <span id="filtername" class="icon-tex"></span>
			<div class="filteroption" style="display: none; position: absolute; background: #890000; margin: 0; text-align: left; z-index: 200; font-size: 12px; text-shadow: none;">
			<div class="pc"><nobr><a href="" onClick="filterPhoto('original'); return false;"><?php print _original_;?></a></nobr></div>
			<?php $opts = whileSQL("ms_color_options", "*", "WHERE color_status='1' ORDER BY color_order ASC ");
			while($opt = mysqli_fetch_array($opts)) { ?>
			<div class="pc"><nobr><a href="" onClick="filterPhoto('<?php print $opt['color_id'];?>','<?php print $opt['color_name'];?>'); return false;"><?php print $opt['color_name'];?></a></nobr></div>
			<?php } ?>
			</div>
		</li>
		<?php } ?>
		<?php } ?>
	 <?php if($date['enable_compare'] == "1") { ?>
	 <li class="icon-flag the-icons" onclick="comparephoto(); return false;" id="compare-photo"><span class="the-icons icon-check checked" style="font-size: 14px; display: none; margin-left: -16px;"></span><br><span class="icon-text"><?php print _compare_;?></span></li>
	<?php } ?>
	<?php if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { ?>
	 <li class="icon-cancel the-icons <?php print $hideclass;?>" onclick="hidephotofull('<?php print $pic['pic_key'];?>','<?php print $pic['pic_key'];?>'); return false;"><br><span class="icon-text"><?php  print _hide_unhide_photo_;  ?></span></li>
	<?php } ?>
	</ul>
</div>
<?php
	// $prod = doSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_product_select_photos='1' AND cart_order<='0' ORDER BY cart_id DESC");
	?>
		<div id="comparebar" class="<?php if(count($_SESSION['comparephotos'])<=1) { print "hide"; } ?>"><div id="comparebarinner"><a href=""  onclick="showcomparephotos(); return false;"><?php print _compare_photos_;?> (<span id="comparetotal"><?php print count($_SESSION['comparephotos']); ?></span>)</a> (<a href="" onclick="closecomparephotos('1'); return false;"><?php print _compare_clear_all_;?></a>) </div></div>
		<div id="singlephotopackagetab"><div id="singlephotopackagetabinner"></div></div>
	<div id="photopackagetab"><a href="" id="photopackagetabinner" onclick="packageopen(); return false;"><?php print _add_photo_to_package_;?></a>

		<!-- <?php if($prod['cart_id'] > 0) { ?>
	<div id="photopackagetab"><a href="" id="photopackagetabinner" onclick="storephotoopen('<?php print $cart['cart_id'];?>'); return false;"><?php print _add_photo_to_package_;?></a>
	<?php } else { ?>
	<div id="photopackagetab"><a href="" id="photopackagetabinner" onclick="packageopen(); return false;"><?php print _add_photo_to_package_;?></a>
	<?php } ?>
	-->
	</div>
