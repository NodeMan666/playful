<?php if($date['blog_kill_side_menu'] == "1") { ?>
<style>
	#sideMenuContainer { 
	display: none !important;
}
#pageContentContainer { 
	width: 100%;
}
</style>
<?php } ?>
<?php
$photo_setup = doSQL("ms_photo_setup", "*", "  ");

if($_REQUEST['view'] == "favorites") { 
	$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
	$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
	$pics_orderby = "pic_org";
	$pics_acdc  = "ASC";
	$pic_fields = "ms_photos.pic_no_dis,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";

} else { 
	if($_REQUEST['view'] == "highlights") { 
		$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
		$pics_where = "WHERE bp_blog='".$date['date_id']."' AND pic_fav_admin='1' ";
		$pics_orderby = "pic_org";
		$pics_acdc  = "ASC";
		$pic_fields = "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";
	} else { 
		$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
		$pics_where = "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ";
		$pics_orderby = "bp_order";
		$pics_acdc  = "ASC";
		$pic_fields = "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";
	}
}
//$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_q ORDER BY bp_order ASC  LIMIT 1");
//$pic_file = $date['blog_photo_file'];
//$pic_file_select = selectPhotoFile($pic_file,$pic);
//$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
$and_where = getSearchString();
$search = getSearchOrder();
if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
	// Is gallery owner
} else { 
	$and_where .= " AND pic_hide!='1' ";
}


if(!empty($date['date_photo_keywords'])) { 
	$and_date_tag = "( ";
	$date_tags = explode(",",$date['date_photo_keywords']);
	foreach($date_tags AS $tag) { 
		$cx++;
		if($cx > 1) { 
			$and_date_tag .= " OR ";
		}
		$and_date_tag .=" key_key_id='$tag' ";
	}
	$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
	$and_date_tag .= " ) ";

	$pics_where = "WHERE $and_date_tag $and_where ";
	$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
	$pics_orderby = $date['date_photos_keys_orderby']; 
	$pics_acdc = $date['date_photos_keys_acdc'];
	$pic_file = $date['blog_photo_file'];
		$pic_fields = "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";

}

$total_images_count = whileSQL("$pics_tables", "$pic_fields", "$pics_where $and_where  $and_q GROUP BY pic_id  ORDER BY  $pics_orderby $pics_acdc  ");
$total_images = mysqli_num_rows($total_images_count);
if($total_images <=1) { 
	$date['disable_controls'] = 1;
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
var loop = <?php print $date['slideshow_stop_end'];?>;
var autostart = <?php print $date['blog_slideshow_auto_start'];?>;
var ssspeed = <?php print $date['blog_seconds'] * 1000;?>;
<?php if($total_images > 1) { ?>
var sstransition = <?php print $date['transition_time'];?>;
<?php } else { ?>
var sstransition = 20;
<?php } ?>
var fullscreenmenu = <?php print $date['blog_enlarge'];?>;
<?php if($date['blog_type'] == "gallery") { ?>
var thumbnails = 1;
<?php } else { ?>
var thumbnails = 0;
<?php } ?>
var thumb_limit = '<?php print $photo_setup['thumb_limit'];?>';
<?php if(($date['thumb_scroller'] == "1" && $date['blog_type'] == "nextprevious")==true) { ?>
var scrollthumbnails = 1;
<?php } else { ?>
var scrollthumbnails = 0;
<?php } ?>
var disablecontrols = <?php print $date['disable_controls'];?>;
<?php if(($ipad == true)||($mobile == true)==true) { ?>
var hoverpreview= 0;
<?php } else { ?>
var hoverpreview= <?php print $date['noupsize'];?>;
<?php } ?>
var add_menu_height = 0;
var SSslideshowtimer;
var add_margin_page = <?php print ($css['photo_page_padding'] + $css['photo_page_border_size']) * 2;?>;
var add_margin_full = <?php print ($css['photo_padding'] + $css['photo_border_size']) * 2;?>;



</script>

<?php if($date['blog_type'] !== "gallery") { ?>
<?php if($sytist_store == true) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($person['p_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
		}
	}

	$get_color_from = "main_container";
	$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
	if($freedownload['pp_id'] > 0) { 
		$free = $freedownload['pp_id'];
	}
	?>

<div class="center pagephotostoreactions"><?php include $setup['path']."/sy-inc/store/store_photos_menu_actions.php"; ?></div>
<?php  } ?>
<?php if($total_images > 0) { ?>
<div id="ssholder" style="width: 100%; text-align: center; "><div style="padding: 150px 0 0 0; " id="loadingss"><div class="loadingspinner"></div></div></div><?php } ?>
<?php } ?>
<div id="showslidehsow"></div>

<?php 
if(($date['passcode_photos'] == "1") && (empty($_REQUEST['passcode'])) == true) { 
	// include $setup['path']."/sy-inc/find_photos.php";
} else { 
	if(($date['blog_type'] == "gallery")==true){ 
		$show_thumbnails = true;
	}
}

$page_gallery = true;
$this_page = $_REQUEST['page'];
if($this_page <=0) { 
	$this_page = 1;
}
$per = 20 * $photo_setup['thumb_limit'];
$pages = ceil($total_images / $per);
$p = 1;
if(ceil($this_page / $photo_setup['thumb_limit']) == $pages) { 
	$no_next = true;
	$mclass = "";
} else { 
	$mclass = "hide";
}

//print_r($_REQUEST);
// print "<li>here: ".ceil($this_page / $photo_setup['thumb_limit']);
// print "<h1>  $this_page == $pages  ".($this_page + 10)." /  ".$photo_setup['thumb_limit']." *  $per   ".(((($this_page + 10) / $photo_setup['thumb_limit']) * $per) + 20)." >= ".$total_images."</h1>";


?>
	<div id="photoGallery">
	<?php if(($total_images > 0) && ($show_thumbnails == true) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "favorites") || ($_REQUEST['view'] == "highlights")) == true){ ?>
	 <div id="thumbsloading" class="center" style="height: 1000px; position: relative; display: block;"><div style="position: absolute; left: 50%; margin: auto; height: 100%; margin-left: -30px;"><div class="loadingspinner"></div></div></div> 
	<?php } ?>

		<div id="displayThumbnailPage"  data-share-descr="<?php print htmlspecialchars(urlencode($fb['fb_photo_share']));?>"></div>
		<div class="clear"></div>

		<?php if($show_thumbnails == true) { ?>
			<div>&nbsp;</div>
			<?php if($pages > 1) { ?>
			<div id="navthumbpages" class=" <?php print $mclass;?>">
				<div class="pc">
					<?php if($this_page > 1) { ?><a href="index.php?keyWord=<?php print $_REQUEST['keyWord'];?>&kid=<?php print $_REQUEST['kid'];?>&page=<?php print ($this_page-$photo_setup['thumb_limit']);?><?php if(!empty($_REQUEST['sub'])) { print "&sub=".$sub['sub_link']; } ?>" class="checkout"> &larr; <?php print _nav_previous_;?></a>&nbsp;<?php } ?>
					<?php if($no_next !== true) { ?><a href="index.php?keyWord=<?php print $_REQUEST['keyWord'];?>&kid=<?php print $_REQUEST['kid'];?>&page=<?php print ($this_page+$photo_setup['thumb_limit']);?><?php if(!empty($_REQUEST['sub'])) { print "&sub=".$sub['sub_link']; } ?>" class="checkout"><?php print _nav_next_;?> &rarr;</a><?php } ?>
				</div>

				<div class="pc">
					<a href="index.php?page=1<?php if(!empty($_REQUEST['sub'])) { print "&sub=".$sub['sub_link']; } ?>">1</a> 
					<?php
						while($p < $pages) {  
							$tp = ($p+($photo_setup['thumb_limit']*$p) - $p) + 1;
							?>
						<?php if($this_page == $tp) { ?>
							<?php print $p+1;?>
			 
						<?php } else { ?>
							<a href="index.php?keyWord=<?php print $_REQUEST['keyWord'];?>&kid=<?php print $_REQUEST['kid'];?>&page=<?php print $tp;?><?php if(!empty($_REQUEST['sub'])) { print "&sub=".$sub['sub_link']; } ?>"><?php print $p+1;?></a> 
						<?php } ?>
						<?php 
							$p++;
						}
					?>
				</div>



			</div>
			
			<?php } ?>
			
		<?php } ?>
		<div class="clear"></div>
	</div>
		<div class="clear"></div>
<div id="endpage" style=""></div>
<div class="clear"></div>
<?php 
if($_REQUEST['view'] == "favorites") { 
	$view = "favorites";
}
if($_REQUEST['view'] == "highlights") { 
	$view = "highlights";
}

?>
<script type="text/javascript">
 $(document).ready(function(){
	setTimeout(function(){
	$("#loadingss").fadeIn(100);
	},500);


     var result = null;
     var scriptUrl = "<?php tempFolder();?>/sy-inc/sy-slideshow.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&cat_id=<?php print $cat['cat_id'];?>&css_id=<?php print $css['css_id'];?>&cat_id=<?php print $bcat['cat_id'];?>&show_thumbnails=<?php print $show_thumbnails;?>&keyWord=<?php print $_REQUEST['keyWord'];?>&kid=<?php print $_REQUEST['kid'];?>&mobile=<?php print $mobile;?>&ipad=<?php print $ipad;?>&search_length=<?php print $_REQUEST['search_length'];?>&passcode=<?php print $_REQUEST['passcode'];?>&from_time=<?php print $_REQUEST['from_time'];?>&search_date=<?php print $_REQUEST['search_date'];?>&view=<?php print $view;?>";
     $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: true,
        success: function(data) {
			$("#showslidehsow").html(data);

			if(norightclick == '1') { 
				disablerightclick();
			}


        } 
     });
     return result;


});

</script><div class="clear"></div>
