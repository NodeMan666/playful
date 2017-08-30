<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/icons.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$wm = doSQL("ms_watermarking", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
if((empty($_REQUEST['date_id']))&&($_REQUEST['view'] !== "favorites")==true) { 
	die("You do not have access to this page");
}
if($_REQUEST['view'] == "favorites") { 
	if(!isset($_SESSION['pid'])) { 
		die("You do not have access to this page");

	}
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($p['p_id'])) { 
		die("You do not have access to this page");
	}
}

// print "<div style=\"background: #FFFF99; padding: 16px;\"><pre>"; print_r($_REQUEST); print "</pre></div>"; 

foreach($_REQUEST AS $id => $value) {
	if(!is_array($value)) { 
		$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
		$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
	}
}

// print_r($_REQUEST);

if((!empty($_REQUEST['date_id']))&&(!is_numeric($_REQUEST['date_id']))==true) { die(); } 
if(!is_numeric($_REQUEST['css_id'])) { die(); } 
if((!empty($_REQUEST['sub_id']))&&(!is_numeric($_REQUEST['sub_id'])) == true) { die(); } 
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if(!empty($date['date_id'])) { 
	// if($date['date_public'] !== "1") { die(); } 
	if(($date['private'] > 0)&&(!isset($_SESSION['office_admin_login'])) == true) { 
		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				die("no access");
				exit();
			} 
		} else { 
			if(!is_array($_SESSION['privateAccess'])) { die("no access"); } 
			if(!in_array($date['date_id'],$_SESSION['privateAccess'])) {
				die("no access");
				exit();
			}
		}
	}
}


if($date['date_photo_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($person['p_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
		}
	}

	$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
	if($freedownload['pp_id'] > 0) { 
		$free = $freedownload['pp_id'];
	}

}
// print "<li>".$date['date_id'];
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}

$starttime = microtime(true);

$and_where = getSearchString();
$search = getSearchOrder();


if(!empty($_REQUEST['keyWord'])) { 
	$search_words = array();
	$var = sql_safe(@$_REQUEST['keyWord']); 
	$var = str_replace("("," ",$var);
	$var = str_replace(")"," ",$var);
	$var = str_replace("="," ",$var);
	$var = str_replace("<"," ",$var);
	$var = str_replace(">"," ",$var);
	$var = str_replace("--"," ",$var);
	$var = str_replace("/"," ",$var);

	$trimmed = trim($var);
	$trimmed1 = trim($var);
	//separate key-phrases into keywords
	$trimmed_array = explode(" ",$trimmed);
	$trimmed_array1 = explode(" ",$trimmed1);
	foreach ($trimmed_array as $trimm){
		$trimm = str_replace('"', "", $trimm);
		$trimm = str_replace('?', "", $trimm);
		$trimm = trim(stripslashes(stripslashes($trimm)));
		array_push($search_words, $trimm);
	}
	foreach($search_words AS $tw) { 
		$and_subs .= " AND (sub_name LIKE '%".addslashes($tw)."%')";
	}

	$total_subs = countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."'  $and_subs ORDER BY sub_order ASC, sub_name ASC ");

} else { 
	$total_subs = 0;
}






$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$_REQUEST['css_id']."'");
if($_REQUEST['view'] == "favorites") { 
	$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
	$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
	$pics_orderby = "pic_org";
	$pics_acdc  = "ASC";

	$pic_file = "pic_large";
	$fixed_height = 0;
	$date['thumb_scroller_open'] = 0;
	$pic_fields = "ms_photos.pic_no_dis,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";
} else { 


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
		$and_date_tag .= " OR bp_blog='".$_REQUEST['date_id']."' ";
		$and_date_tag .= " ) ";

		$pics_where = "WHERE $and_date_tag $and_where ";
		$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
		$pics_orderby = $date['date_photos_keys_orderby']; 
		$pics_acdc = $date['date_photos_keys_acdc'];
		$pic_file = $date['blog_photo_file'];
		$pic_fields = "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";
	} else { 

		if($_REQUEST['view'] !== "highlights") { 
			if(!empty($_REQUEST['sub_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
				$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
			} else { 
				if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
					$and_sub = "AND bp_sub='0' ";
				}
			}
		}
		$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
		$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
		$pics_orderby = "bp_order";
		$pics_acdc  = "ASC";
		$pic_file = $date['blog_photo_file'];
		$fixed_height = $date['slideshow_fixed_height'];
		$pic_fields = "ms_photos.pic_no_dis,ms_blog_photos.bp_sub,ms_photos.pic_fav_admin,ms_photos.pic_hide,ms_photos.pic_client,ms_photos.pic_id";
	}
}

if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
	// Is gallery owner
} else { 
	$and_where .= " AND pic_hide!='1' ";
}
if($_REQUEST['view'] == "highlights") { 
	$and_where .= " AND pic_fav_admin='1' ";
}

$pics = whileSQL("$pics_tables", "$pic_fields", "$pics_where $and_where GROUP BY pic_id  ORDER BY $pics_orderby $pics_acdc  ");
$total_images = mysqli_num_rows($pics);

if($_REQUEST['show_thumbnails'] !== "1") { 

	$fp = doSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where  AND pic_width>pic_height ORDER BY $pics_orderby $pics_acdc  ");
	if(!empty($fp['pic_id'])) { 
		$pic_file_select = selectPhotoFile($pic_file,$fp);
		if($fp['pic_amazon'] == "1") { 
			if($pic_file_select == "pic_large") { 
				$first_landscape_width = $fp['pic_large_width'];
				$first_landscape_height= $fp['pic_large_height'];
			}
			if($pic_file_select == "pic_pic") { 
				$first_landscape_width = $fp['pic_small_width'];
				$first_landscape_height= $fp['pic_small_height'];
			}

		} else { 
			$flsize = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$fp['pic_folder']."/".$fp[$pic_file_select].""); 
			$first_landscape_width = $flsize[0];
			$first_landscape_height= $flsize[1];
		}
	}
}

?>
<script>
<?php if($fixed_height  == "1" && $date['blog_type'] == "nextprevious") { ?>
use_first_lanscape_height = 1;
<?php } else { ?>
use_first_lanscape_height = 0;
<?php } ?>
first_landscape_width  = <?php if($first_landscape_width > 0) { print $first_landscape_width; } else { print "0"; } ?>;
first_landscape_height = <?php if($first_landscape_height > 0) { print $first_landscape_height; } else { print "0"; } ?>;
<?php if((!empty($_REQUEST['keyWord']))||(!empty($_REQUEST['from_time']))==true) { ?>
keyWord = true;
<?php } else { ?>
keyWord = false;
<?php } ?>
totalphotos = <?php print $total_images;?>;
totalsubs = <?php print $total_subs;?>;
<?php if(($total_images == "1") && ($_REQUEST['view'] !== "favorites") == true) { ?>
thumb_open_first = '1';
<?php } else { ?>
thumb_open_first = '<?php print $date['thumb_open_first']; ?>';
<?php } ?>
<?php if($date['green_screen_backgrounds'] > 1) { ?>
disable_enlarge = '1';
<?php } else { ?>
disable_enlarge = '0';
<?php } ?>
if(totalphotos <= 0 && keyWord == true && totalsubs <= 0) { 
	$("#tagresults").hide();
	$("#tagnotfound").show();
	$("#thumbsloading").hide();
	$("#nophotosfound").html("<div class='error'><?php print htmlspecialchars(_no_photos_found_);?></div>").slideDown(300);
}

 $(document).ready(function(){
	$("#ssholder").hide();
	enableenlargephoto();

	 if(thumbnails == 1 && thumb_open_first == 1 && totalphotos > 0) { 
		 clickthumbnail('<?php print $pic['pic_key'];?>');
		 if(totalphotos > 1) { 
			SSslideshowtimer = window.setTimeout("navSlides('next')",ssspeed);
			$("#slideshow").attr("sson","1");
			$("#ssPlay").hide();
			$("#ssPause").show();
		 }
	 }
		 
	 if(fullscreenmenu == 1) { 
		 $("#fullscreen").show();
	 } else {
		 $("#fullscreen").hide();
	 }
	if(thumbnails !== 1) { 
		arrowNavSS();
		checkforpackagesone();
		checkforpackages();
		$("#ssPrevPhoto").hide();
		$("#slideshow").css("height","<?php print $fsize[1];?>px");
		$("#photo-1").imagesLoaded(function() {
			sizePhoto("1");

			$("#photo-1-container").fadeIn(1, function() { 
				$("#thumbsloading").hide();
				$("#photo-1").fadeIn(400);
				$(".pagephotostoreactions").fadeIn(400);
			sizeContainer();
			getSSCaption("photo-1");
			placeNav("1");
			if(autostart == 1 && totalphotos > 1) { 
				SSslideshowtimer = window.setTimeout("navSlides('next')",ssspeed);
				$("#slideshow").attr("sson","1");
				$("#ssPlay").hide();
				$("#ssPause").show();
			} else { 
				$("#ssPlay").show();
				$("#ssPause").hide();
			}

			setTimeout(function(){
				$("#controls").children().fadeOut(200, function() {	});
			},2000);


			});
			$("#photo-2").attr("src", $("#photo-2").attr("thissrc"));

		});

		$(window).resize(function() {
		placeNav($("#slideshow").attr("curphoto"));
		sizePhoto($("#slideshow").attr("curphoto"));
		getSSCaption("photo-"+$("#slideshow").attr("curphoto"));

		});
	 $("#controls").mouseover(function(){
		 $(this).children().show();
		}).mouseout(function(){ 
		 $(this).children().hide();});
	}

	$(".styledthumbs").hover(
	  function () {
		$(this).find('.inner').show();
	  },
	  function () {
		$(this).find('.inner').hide();
	  }
	);
 });

</script>
<?php 
if((!empty($_REQUEST['cat_id']))&&(!is_numeric($_REQUEST['cat_id']))==true) { die(); } 
$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
?>
<div id="ssheader"></div>
<div id="ssbackground"></div>
<div id="slideshowcontainer">
<div id="sscontainerloading"></div>
<span onclick="closeFullScreenPhoto(); return false;" id="ssClose" class="icon-cancel-circled the-icons photo-nav"></span>
	<span onclick="navSlides('prev','1'); return false;" id="ssPrevPhoto" class="icon-left-open the-icons photo-nav ssnavigation"></span>   
	<span onclick="navSlides('next','1'); return false;" id="ssNextPhoto" class="icon-right-open the-icons photo-nav ssnavigation"></span>
	<div id="controls" class="ssnavigation"><div style="position: relative;">
	<span onclick="startSlideshow(); return false;" id="ssPlay"  class="icon-play the-icons photo-nav"></span>
	<span onclick="stopSlideshow(); return false;" id="ssPause" class="icon-pause the-icons photo-nav"></span>
	<br><a href="" onclick="fullScreen(); return false;" id="fullscreen"><?php print _full_screen_;?></a>
	</div></div>
<div style="position: relative; text-align: center; <?php if($_REQUEST['show_thumbnails'] !== "1") { ?>height: <?php print $flsize[1];?>px; <?php } ?>" id="slideshow" curphoto="1" disablenav="0" sson="" fullscreen="0" scrollthumbs="<?php print $date['thumb_scroller'];?>">

<?php
$qryendtime = microtime(true);
$duration = $qryendtime - $starttime;
// print "<h1>Query Time: ".$duration."</h1>";

$mobile = $_REQUEST['mobile'];
$ipad = $_REQUEST['ipad'];
if($_REQUEST['show_thumbnails'] !== "1") { 
	$images_array = array();
	while ($bpic = mysqli_fetch_array($pics)){
		$pic = doSQL("ms_photos","*","WHERE pic_id='".$bpic['pic_id']."' ");

		// print "<li>".$pic['pic_id'];
		if($_REQUEST['view'] == "favorites") { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$pic['fav_date_id']."' ");
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
			if(customerLoggedIn()) { 
				$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
				if($person['p_price_list'] > 0) { 
					$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
				}
			}

			$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
			if($freedownload['pp_id'] > 0) { 
				$free = $freedownload['pp_id'];
			} else { 
				$free = 0;
			}
			if($pic['fav_sub_id'] > 0) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$pic['fav_sub_id']."' ");
			} else { 
				unset($sub);
			}
		}

		$pic_file_select = selectPhotoFile($pic_file,$pic);
		$ssi++;
		if(($ssi == 1)&&($_REQUEST['show_thumbnails'] !== "1")==true) {  
			$pc = "photoHidden";
			$nosrc = "0";
		} else {
			$pc = "photo";
			$nosrc = "1";
		}
		if($_REQUEST['show_thumbnails'] == "1") { 
			$pc = $pc."  enlarge";
		}
		if($pic['pic_large_width'] <=0) { 
			$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
		} else { 
			if($pic_file_select == "pic_large") { 
				$dsize[0] = $pic['pic_large_width'];
				$dsize[1] = $pic['pic_large_height'];
			}
			if($pic_file_select == "pic_pic") { 
				$dsize[0] = $pic['pic_small_width'];
				$dsize[1] = $pic['pic_small_height'];
			}
		}
		print displayPhoto($pic,$pic_file_select,$wm,$dsize,$contain,$pc,$nosrc,$ssi,$border_color,$border_size,$bg_color,$bg_use,"absolute", "none", $cat['cat_id'],$cat['cat_watermark'],$cat['cat_logo'],$captionwhere,$date,$free,$sub);
		array_push($images_array,$pic);
	}

}
?>
<div class="clear"></div>
</div>
</div>

<div class="clear"></div>

 <script language="javascript"  type="text/javascript" src="<?php tempFolder(); ?><?php print "/".$setup['inc_folder']."/js/scroller.js" ?>"></script>
<?php $scroll_thumbs_per_page = 50; 
$pages = ceil($total_images / $scroll_thumbs_per_page); ?>
<script>

function checkscrollthumbs() { 
	pages = <?php print $pages;?>;
	pages = Math.abs(pages);
	newpage = $("#scrollbar1").attr("thpage");
	var n = Math.abs(newpage) + 1;
	if(n <= pages) { 
		p = Math.abs(newpage) * <?php print $scroll_thumbs_per_page;?>;
		sl= $("#scrollbar1").offset().left;
		tid = "th-"+p;
		sp = $("#"+tid).offset().left;
		sw= $("#scrollbar1").width();
		cw = $("#overview").width();
		han = $("#handle").offset().left;
		sw= $("#scrollbar1").width();
		place =  (Math.abs(cw) - Math.abs(sw));

		if((sp - 100) < (Math.abs(sl) + Math.abs(sw))) { 
		 $("#scrollbar1").attr("thpage", n);
		clearInterval(ckst);
		$.get("<?php tempFolder();?>/sy-inc/sy-thumbnails.php?date_id=<?php print $date['date_id'];?>&view=<?php print $_REQUEST['view'];?>&css_id=<?php print $css['css_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&scroller=1&mobile=<?php print $_REQUEST['mobile'];?>&ipad=<?php print $_REQUEST['ipad'];?>&page="+n, function(data) {
			$("#scrollthumbscontainer").append(data);
			var oScrollbar = $('#scrollbar1');
			oScrollbar.tinyscrollbar({ axis: 'x'});
			oScrollbar.tinyscrollbar_update(place);

				setTimeout(function(){
				ckst = setInterval("checkscrollthumbs()",200);
				},1000);

		});

		}
	}

}

$(document).ready(function(){
	$('#scrollbar1').tinyscrollbar({ axis: 'x'});
	<?php if(($date['thumb_scroller'] == "1" && $date['blog_type'] == "nextprevious")==true) { ?>
	ckst = setInterval("checkscrollthumbs()",200);
	<?php } ?>
	 mytips(".tip","tooltip");
	<?php if($date['thumb_scroller_open'] == "0") { ?>
	$('#thumbscrollerclick').bind('click', showthumbsscroller); 
	<?php } else { ?>
	$('#thumbscrollerclick').bind('click', hidethumbsscroller); 
	<?php } ?>
});
</script>
<!--   --> 


<?php if(($date['thumb_scroller'] == "1" && $date['blog_type'] == "nextprevious")==true) { ?>
<div id="thumbscroller" class="thumbscrollerpage" <?php if(($date['blog_type'] == "gallery")OR($date['thumb_scroller'] == "0")OR($total_images <=1)OR($_REQUEST['view'] == "favorites")==true) { ?>style="display: none;" <?php } ?>>
<div >
<div style="width: 30%;" class="pc left" id="photocount">1/<?php print $total_images;?></div>
<div style="width: 40%;" class="pc left center"><span id="thumbscrollerclick"><img src="<?php tempFolder();?>/sy-graphics/icons/nav_up.png" border="0" align="absmiddle" id="navarrowthumbs"> Thumbnails</span></div>
<div style="width: 30%;" class="pc left"></div>
<div class="clear"></div>
</div>
<div id="thumbscrollerinner" <?php if($date['thumb_scroller_open'] == "0") { ?>style="opacity: 0; height: 0px; overflow: hidden;"<?php } ?>>
<div id="scrollbar1" thpage="1">
    <div class="viewport">
        <div class="overview" id="overview">
		<div id="scrollthumbs">
		<div><nobr id="scrollthumbscontainer">
<?php
	$pos = 0;
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	$pics = whileSQL("$pics_tables", "*", "$pics_where ORDER BY $pics_orderby $pics_acdc LIMIT ".$scroll_thumbs_per_page."");
	while($pic = mysqli_fetch_array($pics)) { 
		$pos++;
		$size = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'].""); 
		$per = 100 / $size[1];

	?>
	<a href="" onClick="navSlides('<?php print $pos;?>', '1'); return false;"><img src="<?php tempFolder();?><?php print "/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'];?>" id="th-<?php print $pos;?>" class="scrollthumbnail<?php if($pos == 1) { print " thumbon"; } ?>" style="" width="<?php print $size[0] * $per;?>" height="<?php print $size[1] * $per;?>"></a>

	<?php } 
	if($total_images <= $scroll_thumbs_per_page) { ?>
<div id="scrollend" style="display: inline;">&nbsp;</div>
<?php }  ?>
</nobr>
	</div></div>
        </div>
    </div>
    <div class="scrollbar"><div class="track"><div class="thumb" id="handle"><div class="end"></div></div></div></div>
</div>
</div>
</div>
<div id="scrollthumbscontainer"></div>

<?php } ?>
<div id="nophotosfound" class="hide"></div>
