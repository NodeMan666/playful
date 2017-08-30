<?php 	$show_thumbnails = true; 
$total_images = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ");
?>
<div class="pc title"><h1><?php print _my_favorites_;?> (<span id="favoritestotal"><?php print $total_images;?></span>)</h1></div>
<?php
if(!empty($_SESSION['last_gallery'])) { 
	$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
	if((!empty($ldate['date_id']))  && ($date['date_gallery_exclusive'] <= 0) == true){ 
		if($_SESSION['last_gallery_sub'] > 0) { 
			$lsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
			?>
			<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$ldate['cat_folder']."/".$ldate['date_link']."/?sub=".$lsub['sub_link'].""; ?>"><?php print _return_to_last_gallery_page_;?> "<?php print $ldate['date_title'];?> > <?php print $lsub['sub_name'];?>"</a></div>
		<?php } else { ?>
			<div class="pc"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$ldate['cat_folder']."/".$ldate['date_link']."/"; ?>"><?php print _return_to_last_gallery_page_;?> "<?php print $ldate['date_title'];?>"</a></div>


	<?php } ?>

<?php 
	// unset($_SESSION['last_gallery']);
	}
}
?>
<?php if($total_images <= 0) { ?>
<div class="error"><?php print _no_favorites_;?></div>
<?php } else {  ?>
<div id="buyallfavorites" class="right"><?php buyallphotos();?> </div>
<div class="pc">
<a href="<?php print $site_setup['index_page'];?>?view=favorites&action=clearfavs"  onClick="return confirm('<?php print _clear_favorites_confirm_;?>');"><?php print _clear_favorites_;?></a>&nbsp; 
</div>
<div class="clear"></div>

<?php } ?>

<script>
function addfavstocollection(id) { 
	$.get('<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_cart_actions.php?action=addfavstocollection&cart_id='+id,	function (data) { 
		$("#allfavs-"+id).slideUp(200, function() { 
			$("#allfavsdone-"+id).slideDown(200);
		});
	 } );
}
function openfavstocollection(id) { 
	$("#allfavs-"+id).slideToggle(200);
}
</script>

<?php if($total_images > 0) { ?>

<div>
<?php $coltotal = whileSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE cart_order<='0' AND ".checkCartSession()." AND cart_package>'0'     ");  
while($col = mysqli_fetch_array($coltotal)) { 

	if(!empty($col['package_id'])) { 
		$tp = whileSQL("ms_cart", "*", "WHERE cart_package_photo='".$col['cart_id']."' AND cart_pic_id<='0' GROUP BY cart_photo_prod ");
		if(mysqli_num_rows($tp) ==1) { 
			$fav = array();
			$tas = whileSQL("ms_cart", "*", "WHERE cart_package_photo='".$col['cart_id']."' AND cart_pic_id<='0' ORDER BY cart_id ASC");
			$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");
			while($pic = mysqli_fetch_array($pics)) { 
				$ck = doSQL("ms_cart", "*", "WHERE cart_pic_id='".$pic['pic_id']."' AND cart_package_photo='".$col['cart_id']."' ");
				if(empty($ck['cart_id'])) { 
					array_push($fav,$pic['fav_id']);
				}
			}
			if(count($fav) > 0) { 
			$addlink = _add_all_favorites_to_;
			$addlink = str_replace("[COLLECTION_NAME]",$col['package_name'],$addlink);
			
			?>
			<div class="pc"><a href="" onclick="openfavstocollection('<?php print $col['cart_id'];?>'); return false;"><?php print $addlink;?></a></div>
			<div id="allfavs-<?php print $col['cart_id'];?>" class="hide pc">
			<?php 
			$tav =  mysqli_num_rows($tas);
			$num_favs = count($fav); 
			if($num_favs < $tav) { $tav = $num_favs; }
			$message = _add_all_favorites_message_;
			$message = str_replace("[NUMBER_TO_ADD]",$tav,$message);
			$message = str_replace("[NUMBER_OF_FAVORITES]",count($fav),$message);

			?>
			<p><?php print $message;?></p>
			<p><a href="" onclick="addfavstocollection('<?php print $col['cart_id'];?>'); return false;"><?php print _add_all_yes_; ?></a></p>
			<p><a href="" onclick="openfavstocollection('<?php print $col['cart_id'];?>'); return false;"> <?php print _cancel_;?></a></p>

			</div>
			<div id="allfavsdone-<?php print $col['cart_id'];?>" class="hide pc"><?php print _add_all_done_;?> <a href="<?php print $setup['temp_url_folder'];?>/index.php?view=cart"><?php print _view_cart_;?></a>
			</div>

			<?php
				}
			}
			unset($fav);
		}
}



?>
</div>
<?php } ?>
<div class="pc"><?php print _favorites_page_text_;?></div>
<?php if($_REQUEST['action'] == "clearfavs") { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	deleteSQL2("ms_favs", "WHERE fav_person='".$person['p_id']."'", "1");
	header("location: index.php?view=favorites");
	session_write_close();
	exit();


}

$gs = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE MD5(fav_person)='".$_SESSION['pid']."' AND pic_id>'0'  AND fav_bg>'0' ");
if(!empty($gs['fav_bg'])) { 
	$fav_green_screen_backgrounds = $gs['fav_bg'];
}
?>

<script>
  var ipad;
var mobile;
var sytiststore;
<?php if($sytist_store == true) { ?>
var sytiststore = 1;
<?php } ?>
var totalphotos = <?php print $total_images;?>;
var isslideshow = true;
var loop = 0;
var autostart = 0;
var ssspeed = 5000;
var thumb_limit = '<?php print $photo_setup['thumb_limit'];?>';
<?php if($total_images > 1) { ?>
var sstransition = 500;
<?php } else { ?>
var sstransition = 20;
<?php } ?>
var fullscreenmenu = 1;
var thumbnails = 1;
var disablecontrols = 0;
var hoverpreview= 0;
var add_menu_height = 0;
var SSslideshowtimer;
var add_margin_page = <?php print ($css['photo_page_padding'] + $css['photo_page_border_size']) * 2;?>;
var add_margin_full = <?php print ($css['photo_padding'] + $css['photo_border_size']) * 2;?>;
var scrollthumbnails = 0;
first_landscape_width  = '0';
first_landscape_height = '0';



</script>
<script type="text/javascript">
 $(document).ready(function(){
	setTimeout(function(){
	$("#loadingss").fadeIn(100);
	},500);


     var result = null;
     var scriptUrl = "<?php print $setup['temp_url_folder'];?>/sy-inc/sy-slideshow.php?date_id=<?php print $date['date_id'];?>&view=favorites&sub_id=<?php print $sub['sub_id'];?>&cat_id=<?php print $cat['cat_id'];?>&css_id=<?php print $css['css_id'];?>&cat_id=<?php print $bcat['cat_id'];?>&show_thumbnails=<?php print $show_thumbnails;?>";
     $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: true,
        success: function(data) {
			$("#showslidehsow").html(data);
        } 
     });
     return result;


});

</script>
<div id="showslidehsow"></div>

<div id="photoGallery">
	<div id="displayThumbnailPage"></div>
	<div class="clear"></div>
</div>
<div id="endpage" style=""></div>