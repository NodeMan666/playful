<?php 
/* TESTING */
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");
$feature_type = $show['feature_type']; // featured photos slideshow
// $feature_type = 'getfeature'; // featured pages
$catphotoratio = $show['catphotoratio'];
$mainphotoratio = $show['mainphotoratio'];
$catsubrecent = $show['catsubrecent'];
if($feature_type == "getfeatureslide") { 
	$initialopacity = 1;
	$hoveropacity = 1;
} else { 
	$initialopacity = $show['initialopacity'];
	$hoveropacity = $show['hoveropacity'];
}
$main_full_screen = $show['main_full_screen'];
$main_link_spacing = $dshow['main_link_spacing'];
$main_photo_bg_color = "#".$dshow['main_photo_bg_color'];
$slide_speed = $show['slide_speed'];
$mainfeaturetimer = $show['mainfeaturetimer'];
$change_effect = $show['change_effect'];
$loading_text = $dshow['loading_text'];
if($show['enable_side'] <=0) { 
	$show['fullscreen_width'] = 100;
}

if($feature_type == "getfeatureslide") { 
	$featured_slides = getfeaturedslides($date['date_id'],$show,$sub['sub_id']);
	$total_features = count($featured_slides);
} else { 
	$featured_dates = getfeatureddates($show);
	$total_features = count($featured_dates);
}


?>

<script>
var totalmslides = '<?php print $total_features;?>';
var slide_speed = Math.abs('<?php print $slide_speed;?>');
var slide_show_slide_speed = Math.abs('<?php print $slide_speed;?>');
var mainfeaturetimer = '<?php print $mainfeaturetimer;?>';
var featid = '<?php print MD5($show['show_id']);?>';
var change_effect = '<?php print $change_effect;?>';
var slide_show_change_effect = '<?php print $change_effect;?>';
var gettingfeature = '<?php print $feature_type;?>';
var side_menu_photo_ratio = '<?php print $dshow['side_menu_photo_ratio'];?>';
var contain_portrait = '<?php print $show['contain_portrait'];?>';
var contain_landscape = '<?php print $show['contain_landscape'];?>';
var hoverpreview= 0;
var thumb_limit = '<?php print $photo_setup['thumb_limit'];?>';
</script>


<div id="loadingstuff">
	<?php print $loading_text;?>
	<br><img src="<?php print $setup['temp_url_folder']?>/sy-graphics/loading.gif"> 
</div>

<?php 
if($date['date_id'] > 0) { 
	$piccount =countIt("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."'");
}
if($piccount > 0) { 
	?>
<script>
 $(document).ready(function(){
	 setTimeout("scrolltoview()",1600);
});

function scrolltoview(){
	thumbpopulate = setInterval("scrolltoviewphotos()",200);
}

function scrolltoviewphotos(){
	if($(window).scrollTop() > 150) { 
		$("#scrolltoviewphotos").slideUp(200);
	} else { 
		$("#scrolltoviewphotos").slideDown(200);
	}
}
</script>
<!-- <div id="scrolltoviewphotos" onclick=" showclfthumbs();"><span class="the-icons icon-down"  onclick=" showclfthumbs();"><?php print _scroll_down_to_view_photos_;?></span></div> -->
<?php } ?>


<div  id="maincol" class="nofloatsmallleft">
	<div class="inner">
		<div class="mainfeature" id="mainfeature" data-cur="1" data-width="<?php print $show['fullscreen_width'];?>" data-enable-side="<?php print $show['enable_side'];?>">
			
			<div id="mainfeaturelogo">				
				<?php if((!empty($bcat['cat_name']))&&($show['show_cat_name'] == "1")==true) { ?>
				<div class="featcatname"><?php print $bcat['cat_name'];?></div>
				<div class="featpagetext"><?php print $bcat['cat_text']; ?></div>

				<?php } ?>
			</div>


			<?php if($dshow['menu_icon'] == "1") { ?>
			<div id="featmenumain">
				<div style="padding: 0px 16px; ">
				<?php require $setup['path']."/".$setup['inc_folder']."/show/show-links-all.php"; ?>
				</div>
			</div>
			<?php } ?>


			<div id="pagefeattitletext">
				<?php if((!empty($date['date_title']))&&($date['page_home'] !== "1")==true) { ?>
				<div class="featpagetitle"><?php print $date['date_title'];?></div>
				<?php } ?>
				<?php if(($show['show_page_text'] == "1")&&($show['feature_type'] == "getfeatureslide")==true) { ?>
				<div class="featpagetext"><?php print $date['date_text']; ?></div>
				<?php } ?>
			</div>


			<?php if($show['main_menu'] == "1") {  require $setup['path']."/".$setup['inc_folder']."/show/show-links.php"; } ?>
		</div><?php // END mainfeature ?>

			
		<?php if($show['show_photos'] !== "random") { ?>
			<?php if($show['enable_nav'] == "1") { ?>
			<div class="mainfeaturenavcontainer hidesmall">
				<a href="" onclick="changem('prev','','click'); return false" class="the-icons icon-left-open mainfeaturenav"></a><?php if($show['nav_display_count'] == "1") { ?>
				<span id="mscount">1</span> <?php print _of_;?> <span id="mstotal"><?php print $total_features;?></span>
				<?php } ?><a href="" onclick="changepp(); return false;" class="the-icons icon-pause" id="mainfeaturepp"></a><a href="" onclick="changem('next','','click'); return false" class="the-icons icon-right-open mainfeaturenav"></a>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>








<div class="clear"></div>
