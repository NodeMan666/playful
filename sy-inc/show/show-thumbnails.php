<?php
include "../../sy-config.php";
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
$sytist_store = true;
$per_page = 20;
if(!empty($_SESSION['previewTheme'])) { 
	$css_id = $_SESSION['previewTheme'];
} else {
	$css_id = $site_setup['css'];
} 
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
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}

$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
$fb = doSQL("ms_fb", "*", "");


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


if(!empty($_REQUEST['date_id'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."'  ");
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
}
if($date['thumb_file'] == "pic_pic") { 
	$pic_file = "pic_pic";
} else {
	$pic_file = "pic_th";
}

if(($date['add_style'] == "0")&&($date['thumb_style'] == "2")==true) { 
	$no_style = true;
}
if($no_style == true) { 

$css['thumb_nails_border_size'] = 0;
$css['thumb_nails_padding'] = 0;



?>

<style>
#displayThumbnailPage .stackedthumbs {
	border: 0px;
	padding: 0px;
}
#displayThumbnailPage .stackedthumbs .thumbnail {
	border: 0px;
}
</style>


<?php if($date['thumb_style'] == "2") { 
	if($date['stacked_width'] > 0) { 
		$css['thumb_nails_width'] = $date['stacked_width'];
		$css['thumb_nails_margin'] = $date['stacked_margin'];
	}

}
?>


<?php } ?>


<?php
if(!empty($_REQUEST['sub_id'])) { 
	if(!is_numeric($_REQUEST['sub_id'])) { die(); } 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}


$and_where = getSearchString();
$search = getSearchOrder();
// print "<li>".$and_where;
// print "<pre>"; print_r($_REQUEST); print "</pre>";
$pics_array = array();
if(empty($_REQUEST['page'])) { 
	$page = 1;
} else {
	if(!is_numeric($_REQUEST['page'])) { die(); } 

	$page = $_REQUEST['page'];
}
$sq_page = $page * $per_page - $per_page;
$and_where .= " AND pic_no_dis='0' ";

if(!empty($_REQUEST['sub_id'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
} else { 
	if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
		$and_sub = "AND bp_sub='0' ";
	}
}
// print "<h1>sub: $and_sub </h1>";

if(!empty($_REQUEST['date_id'])) { 
	$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC");
} elseif(!empty($_REQUEST['key_id'])) { 
	$piccount = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."' AND pic_client='".$_REQUEST['pic_client']."' ORDER BY pic_order DESC ");
} elseif(!empty($_REQUEST['cat_id'])) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
	if(!empty($cat['cat_pic_tags'])) { 
		$and_cat_tag = "AND ( ";
		$cat_tags = explode(",",$cat['cat_pic_tags']);
		foreach($cat_tags AS $tag) { 
			$cx++;
			if($cx > 1) { 
				$and_cat_tag .= " OR ";
			}
			$and_cat_tag .=" key_key_id='$tag' ";
		}
		$and_cat_tag .= " ) ";
		$piccount = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE pic_id>='0' AND pic_client='0' $and_cat_tag GROUP BY pic_id   ORDER BY pic_order DESC ");
	}
} elseif($_REQUEST['view'] ==  "favorites") { 

	$piccount = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE MD5(fav_person)='".$_SESSION['pid']."' AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");

} else  { 
	$piccount = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where  ORDER BY ".$search['orderby']." ".$search['acdc']." $and_acdc");
}
if(!empty($piccount)) { 
	$total_results = mysqli_num_rows($piccount);
}
?>
<?php
if(!empty($_REQUEST['date_id'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."'  ");
	$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC   LIMIT $sq_page,$per_page");
	if($date['date_photo_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
		if($sub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
		}
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

} elseif(!empty($_REQUEST['key_id'])) { 
	$pics = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."'   AND pic_client='".$_REQUEST['pic_client']."'  ORDER BY ".$search['orderby']." ".$search['acdc']."   LIMIT $sq_page,$per_page");

} elseif($_REQUEST['view'] ==  "favorites") { 
	$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC LIMIT $sq_page,$per_page");

} elseif(!empty($_REQUEST['cat_id'])) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
	if(!empty($cat['cat_pic_tags'])) { 
		$cx = 0;
		$and_cat_tag = "AND ( ";
		$cat_tags = explode(",",$cat['cat_pic_tags']);
		foreach($cat_tags AS $tag) { 
			$cx++;
			if($cx > 1) { 
				$and_cat_tag .= " OR ";
			}
			$and_cat_tag .=" key_key_id='$tag' ";
		}
		$and_cat_tag .= " ) ";
	// print "<li>WHERE pic_id>='0' AND pic_client='0'  $and_cat_tag GROUP BY pic_id ORDER BY pic_order DESC LIMIT $sq_page,$per_page";
	$pics = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE pic_id>='0' AND pic_client='0'  $and_cat_tag GROUP BY pic_id ORDER BY pic_order DESC LIMIT $sq_page,$per_page");
	}
} else {
	$pics = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where ORDER BY ".$search['orderby']." ".$search['acdc']."  LIMIT $sq_page,$per_page");
}
// $_REQUEST['mobile']  = "1";

if($_REQUEST['mobile'] == "1") { 
	$date['thumb_style'] = 1;
}
?>
 <script>
$(document).ready(function(){
	 mytips(".tip","tooltip");
	if(hoverpreview == 1) { 
		 photopreview(".thumbnail");
		 photopreview(".thumbnailhidden");
	}

 <?php if($date['thumbactions'] == "1") { ?>
	$(".thumb").hover(
	  function () {
		$(this).find('.inner').show();
	  },
	  function () {
		$(this).find('.inner').hide();
	  }
	);
	$(".styledthumbs").hover(
	  function () {
		$(this).find('.inner').show();
	  },
	  function () {
		$(this).find('.inner').hide();
	  }
	);
	$(".stackedthumbs").hover(
	  function () {
		$(this).find('.inner').show();
	  },
	  function () {
		$(this).find('.inner').hide();
	  }
	);
	<?php } ?>
});

 $(document).ready(function(){
	tlheightperc = <?php print $css['thumb_nails_height'] / $css['thumb_nails_width'];?>;
	tlminwidth = <?php print $css['thumb_nails_width'] + (($css['thumb_nails_margin'] + $css['thumb_nails_border_size'] + $css['thumb_nails_padding'])*2)-8;?>;
	tlperrow = Math.floor($("#displayThumbnailPage").width() / tlminwidth);
	tlmargin = <?php print (($css['thumb_nails_margin'] + $css['thumb_nails_border_size'] + $css['thumb_nails_padding']));?> * (2 * tlperrow)+1;
	tlnewwidth = ($("#displayThumbnailPage").width() - tlmargin) / tlperrow;

	<?php if($date['thumb_style'] == "1") { ?>
	placestyledthumbs(tlnewwidth);
	<?php } else { ?>
	placestackedthumbs(tlnewwidth);

		(function ($){
		  var handler = $('#displayThumbnailPage .stackedthumbs');
		handler.wookmark({
			autoResize: true, // This will auto-update the layout when the browser window is resized.
			resizeDelay: 200,

			//flexibleWidth: true,
			container: $('#displayThumbnailPage'), // Optional, used for some extra CSS styling
			align: "center",
			offset: <?php print $css['thumb_nails_margin'] * 2;?>, // Optional, the distance between grid items
			outerOffset: 0  // Optional, the distance to the containers border
			//itemWidth: tlnewwidth // Optional, the width of a grid item
		});
		})(jQuery);
	<?php } ?>
	$(window).resize(function() {
		tlheightperc = <?php print $css['thumb_nails_height'] / $css['thumb_nails_width'];?>;
		tlminwidth = <?php print $css['thumb_nails_width'] + (($css['thumb_nails_margin'] + $css['thumb_nails_border_size'] + $css['thumb_nails_padding'])*2)-8;?>;
		tlperrow = Math.floor($("#displayThumbnailPage").width() / tlminwidth);
		tlmargin = <?php print (($css['thumb_nails_margin'] + $css['thumb_nails_border_size'] + $css['thumb_nails_padding']));?> * (2 * tlperrow)+1;
		tlnewwidth = ($("#displayThumbnailPage").width() - tlmargin) / tlperrow;
		<?php if($date['thumb_style'] == "1") { ?>
		placestyledthumbs(tlnewwidth);
		<?php } else { ?>
		placestackedthumbs(tlnewwidth);
		<?php } ?>

		
		(function ($){
		  var handler = $('#displayThumbnailPage .stackedthumbs');
		handler.wookmark({
			autoResize: true, // This will auto-update the layout when the browser window is resized.
			resizeDelay: 200,

			//flexibleWidth: true,
			container: $('#displayThumbnailPage'), // Optional, used for some extra CSS styling
			align: "center",
			offset: <?php print $css['thumb_nails_margin'] * 2;?>, // Optional, the distance between grid items
			outerOffset: 0  // Optional, the distance to the containers border
			//itemWidth: tlnewwidth // Optional, the width of a grid item
		});
		})(jQuery);
		
		});
 });

</script>
<script>
 $(document).ready(function(){

	var elems = $(".thumbnail");
	var lastID = elems.length - 1;
	$("#ssholder").hide();


	$("#displayThumbnailPage .thumbnail").each(function(i){
		var this_id = this.id;
       	var this_src =  $("#"+this_id).attr("src"); 
		var image = new Image();
		image.onload = function() {
			$("#thumbsloading").hide();
			
		$("#"+this_id).fadeIn(200, function() { 
			if($("#"+this_id).height() > $("#"+this_id).width()) { 
				// $("#"+this_id).addClass("thumbfitcontainer"); 
			} else { 
				// $("#"+this_id).addClass("thumbfitcontainer"); 
			}
		});
		};
		image.src =this_src;
      }).promise().done( function(){  });

 });

 function thumbactionshare(pic) { 
	$("#thumbactionsadd-"+pic).html($("#thumb-share-"+pic).html());
		$("#thumbactionsadd-"+pic).show();

	$("#thumbcontainer-"+pic).bind('mouseleave', function() {
		$("#thumbactionsadd-"+pic).hide();
	});

 }
 function thumbactionfav(pic) { 
	$("#thumbactionsadd-"+pic).html($("#thumb-fav-"+pic).html());
		$("#thumbactionsadd-"+pic).show();

	$("#thumbcontainer-"+pic).bind('mouseleave', function() {
		$("#thumbactionsadd-"+pic).hide();
	});

 }



</script>

<?php

if(!empty($pics)) { 
	while($pic = mysqli_fetch_array($pics)) { 
		if(!empty($pic['pic_folder'])) { 
			$pic_folder = $pic['pic_folder'];
		} else { 
			$pic_folder = $pic['gal_folder'];
		}
		if($pic['pic_amazon'] == "1") { 
			if($pic_file == "pic_th") { 
				$size[0] = $pic['pic_th_width'];
				$size[1] = $pic['pic_th_height'];
			}
			if($pic_file == "pic_pic") { 
				$size[0] = $pic['pic_small_width'];
				$size[1] = $pic['pic_small_height'];
			}
		} else { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic[$pic_file].""); 
		}

		if($size[0] > $max_width) { $max_width = $size[0]; } 
		if($size[1] > $max_height) { $max_height = $size[1]; } 
		array_push($pics_array,$pic);
		?>

		


	<?php
	}
}




if($_REQUEST['mobile'] == "1") { 
	?>
	<script>
	$(".photo-fav-login").hover(
	  function () {
		ml= $(this).parent().parent().parent().parent().parent().css("padding-left").replace("px", "");
		ml = Math.abs(ml);
		$(this).find(".favlogin").css({"background":$("#main_container").css("background-color"),"left":"-"+ml / 2+"px", "width":"100%"});
		$(this).find('.favlogin').show();
	  },
	  function () {
		$(this).find('.favlogin').hide();
	  }
	);
	$(".shareoptions").hover(
	  function () {

		ml= $(this).parent().parent().parent().parent().parent().css("padding-left").replace("px", "");
		ml = Math.abs(ml);
		if($("#main_container").css("background-color") == "transparent") { 
			sharebg = $("html").css("background-color");
		} else { 
			sharebg = $("#main_container").css("background-color");
		}
		$(this).find(".shareoption").css({"background":sharebg,"left":"-"+ml / 2+"px", "opacity":"1"});
		$(this).find('.shareoption').show();
	  },
	  function () {
		$(this).find('.shareoption').hide();
	  }
	);
</script>
<style>
.the-icons { font-size: 24px; } 
</style>
<?php 
	$pic_file = "pic_pic";
	$pos = ($page * $per_page) - $per_page;
	foreach($pics_array AS $pic) { 
	$pos++;

	if(!empty($pic['pic_folder'])) { 
		$pic_folder = $pic['pic_folder'];
	} else { 
		$pic_folder = $pic['gal_folder'];
	}

	if($pic['pic_amazon'] == "1") { 
		$size[0] = $pic['pic_small_width'];
		$size[1] = $pic['pic_small_height'];
		$psize[0] = $pic['pic_small_width'];
		$psize[1] = $pic['pic_small_height'];
	} else { 
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic[$pic_file].""); 
		$psize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic'].""); 
	}
	if($date['thumb_style'] == "1") { 
		$thumb_class = "styledthumbs";
	} else { 
		$thumb_class = "stackedthumbs";
	}
?>
<div class="center">
	<div class="thumbimage"><img src="<?php print getimagefile($pic,$pic_file);?>" id="th-<?php print $pic['pic_key'];?>" class="thumbnail" style="cursor: pointer; <?php if($date['thumb_style'] == "1") { ?>display: none; <?php } ?> max-width: <?php print $size[0];?>px; max-height: <?php print $size[1];?>px; width: 100%; height: auto; margin: auto;" width="<?php print $size[0];?>" height="<?php print $size[1];?>" pic_id="<?php print $pic['pic_id'];?>" mpic_id="<?php print $pic['pic_key'];?>" hw="<?php print $psize[0];?>" pic_pic="<?php tempFolder();?><?php print "/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic'];?>" hh="<?php print $psize[1];?>" ptitle="<?php print htmlspecialchars($pic['pic_title']);?>" pcaption="<?php print htmlspecialchars($pic['pic_text']);?>" pic_org="<?php print htmlspecialchars($pic['pic_org']);?>" keywords="<?php print htmlspecialchars($pic['pic_keywords']);?>"></a></div>
		<?php if($sytist_store == true) { 
			if($date['disable_icons'] !== "1") { 

				if($date['allow_favs'] == "1") { 
					$show_under = true;
				}
				if($date['date_photo_price_list'] > 0) { 
					$show_under = true;
				}
				if($date['photo_social_share'] == "1") { 
					$show_under = true;
				}
			}
			if($date['disable_filename'] !== "1") { 
				$show_under = true;
			}
			if($_REQUEST['view'] == "favorites") { 
				$show_under = true;
			}
			if($_REQUEST['mobile'] == "1") { 
				$show_under = true;
			}

			if($show_under == true) { 


			?>
		<div class="text">
		<?php thumbstoreactions($pic,$date,$fb,$setup,$lang,$pos,"0",$list,$free); ?>
		</div>
		</div>
		<div>&nbsp;</div>
		<div class="clear"></div>
		<?php }

		}
	}
} else if($date['thumb_style'] == "0") { 

	$row_height = $date['jthumb_height'];

	?>

	<script>
	var jrowheight = <?php print $row_height;?>;
	var jcmargin = <?php print $date['jthumb_margin'];?>;

	$(document).ready(function(){
		jthumbs();
		$(".thumbcontainer").hover(
		  function () {
			$(this).find('.iconsinfo').slideDown(100, function() { 
				$(this).find('.thumbnailactions .inner').fadeIn(100);
			});
		  },
		  function () {
			$(this).find('.iconsinfo').slideUp(100);
		  }
		);
	var ct = 1;
	$(".jthumb").each(function() {
		$(this).load(function() {
		ct = ct + 1;
			if(ct == 6) { 
				jthumbs();
			}
			$("#thumbsloading").hide();
			$(this).fadeIn(1000, function() { 
				// $("#log").show();
				// $("#log").append("ct: "+ct+" ");
		
				
			});
		});

	});


	});
	

$(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
           jthumbs();
        }, 200);
    });


	</script>


	<div id="thumbsj" style="width: 100%; display: block;">
	<?php 
	$pos = ($page * $per_page) - $per_page;
	foreach($pics_array AS $pic) { 
	$pos++;



	$psize = getimagefiledems($pic,'pic_pic');

	if($psize[1] > 0) { 
		$height_perc = $row_height / $psize[1];
	}
	$new_width = $psize[0] * $height_perc;
	?>
	
	<div class="thumbcontainer" id="thumbcontainer-<?php print $pic['pic_key'];?>" style="margin: <?php print $date['jthumb_margin'];?>px">
	

		<img <?php if($date['date_aff_site'] > 0) { $site = doSQL("ms_aff_info", "*", "WHERE aff_id='".$date['date_aff_site']."' "); print "onclick=\"window.location.href='/viewsite.php?site=".MD5($site['aff_id'])."&l=pic&p=".$date['date_id']."'\" "; } ?>src="<?php print getimagefile($pic,'pic_pic');?>" id="th-<?php print $pic['pic_key'];?>" style="cursor: pointer; display: none; height: <?php print $row_height;?>px; width: auto;" class="jthumb" ww="<?php print $psize[0];?>" hh="<?php print $psize[1];?>" ow="0" aw="<?php print $new_width;?>" <?php if(($_REQUEST['mobile'] !== "1")&&($date['date_aff_site']<= 0)==true) { ?> onClick="clickclfthumb('<?php print $pos;?>'); return false;"<?php } ?> width="<?php print $psize[0];?>" height="<?php print $psize[1];?>" alt="<?php print htmlspecialchars($date['date_title']." ".$pic['pic_keywords']);?>"><?php if($date['date_aff_site'] > 0) { ?></a><?php } ?>
		<?php 
		if($sytist_store == true) { 
			if($date['disable_icons'] !== "1") { 
				if($date['allow_favs'] == "1") { 
					$show_under = true;
				}
				if($date['date_photo_price_list'] > 0) { 
					$show_under = true;
				}
				if($date['photo_social_share'] == "1") { 
					$show_under = true;
				}
			}
			if($date['disable_filename'] !== "1") { 
				$show_under = true;
			}

			if($_REQUEST['view'] == "favorites") { 
				$show_under = true;
			}
			if($_REQUEST['mobile'] == "1") { 
				$show_under = true;
			}
		if($show_under == true) { 
		?>
		<div class="iconsinfo" id="iconsinfo-<?php print $pic['pic_id'];?>">
		<?php thumbstoreactions($pic,$date,$fb,$setup,$lang,$pos,"1",$list,$free); ?>
		</div>
		<?php 
			}
		
		} ?>
	</div>
<?php } ?>
	</div>





<?php 
} else { ?>






<script>
	$(".photo-fav-login").hover(
	  function () {
		ml= $(this).parent().parent().parent().parent().parent().css("padding-left").replace("px", "");
		ml = Math.abs(ml);
		$(this).find(".favlogin").css({"background":$("#main_container").css("background-color"),"left":"-"+ml / 2+"px", "width":"100%"});
		$(this).find('.favlogin').show();
	  },
	  function () {
		$(this).find('.favlogin').hide();
	  }
	);
	$(".shareoptions").hover(
	  function () {

		ml= $(this).parent().parent().parent().parent().parent().css("padding-left").replace("px", "");
		ml = Math.abs(ml);
		if($("#main_container").css("background-color") == "transparent") { 
			sharebg = $("html").css("background-color");
		} else { 
			sharebg = $("#main_container").css("background-color");
			sharebg = '#<?php print $css['inside_bg'];?>';
		}
		$(this).find(".shareoption").css({"background":sharebg,"left":"-"+ml / 2+"px", "opacity":"1"});
		$(this).find('.shareoption').show();
	  },
	  function () {
		$(this).find('.shareoption').hide();
	  }
	);



</script>

<?php
	$pos = ($page * $per_page) - $per_page;
	foreach($pics_array AS $pic) { 
	$pos++;

	if(!empty($pic['pic_folder'])) { 
		$pic_folder = $pic['pic_folder'];
	} else { 
		$pic_folder = $pic['gal_folder'];
	}
	if($pic['pic_amazon'] == "1") { 
		if($pic_file == "pic_th") { 
			$size[0] = $pic['pic_th_width'];
			$size[1] = $pic['pic_th_height'];
			$psize[0] = $pic['pic_th_width'];
			$psize[1] = $pic['pic_th_height'];
		}
		if($pic_file == "pic_pic") { 
			$size[0] = $pic['pic_small_width'];
			$size[1] = $pic['pic_small_height'];
			$psize[0] = $pic['pic_small_width'];
			$psize[1] = $pic['pic_small_height'];
		}

	} else { 
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic[$pic_file].""); 
		$psize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic'].""); 
	}
	if($date['thumb_style'] == "1") { 
		$thumb_class = "styledthumbs";
	} else { 
		$thumb_class = "stackedthumbs";
	}
?>
<div class="<?php print $thumb_class ;?>" id="styledthumb-<?php print $pos;?>">
	<div class="thumbimage"><?php if($_REQUEST['mobile'] !== "1") { ?>
		<?php if($_REQUEST['hash_image'] == 1) { ?><a href="#<?php print $pic['pic_key'];?>"><?php } else { ?><a href="" onClick="clickclfthumb('<?php print $pos;?>'); return false;"><?php } ?>
		<?php } ?><img src="<?php print getimagefile($pic,$pic_file);?>" id="th-<?php print $pic['pic_key'];?>" class="thumbnail" style="cursor: pointer; <?php if($date['thumb_style'] == "1") { ?>display: none; <?php } ?>bottom: 0; max-width: <?php print $size[0];?>px; max-height: <?php print $size[1];?>px;" width="<?php print $size[0];?>" height="<?php print $size[1];?>" pic_id="<?php print $pic['pic_id'];?>" mpic_id="<?php print $pic['pic_key'];?>" pic_pic="<?php print getimagefile($pic,'pic_pic');?>" hw="<?php print $psize[0];?>" hh="<?php print $psize[1];?>" ptitle="<?php print htmlspecialchars($pic['pic_title']);?>" pcaption="<?php print htmlspecialchars($pic['pic_text']);?>" pic_org="<?php print htmlspecialchars($pic['pic_org']);?>" keywords="<?php print htmlspecialchars($pic['pic_keywords']);?>"></a></div>
		<?php if($sytist_store == true) { 
			if($date['disable_icons'] !== "1") { 

				if($date['allow_favs'] == "1") { 
					$show_under = true;
				}
				if($date['date_photo_price_list'] > 0) { 
					$show_under = true;
				}
				if($date['photo_social_share'] == "1") { 
					$show_under = true;
				}
			}
			if($date['disable_filename'] !== "1") { 
				$show_under = true;
			}
			if($_REQUEST['view'] == "favorites") { 
				$show_under = true;
			}
			if($_REQUEST['mobile'] == "1") { 
				$show_under = true;
			}

			if($show_under == true) { 


			?>
		<div class="text">

		<?php thumbstoreactions($pic,$date,$fb,$setup,$lang,$pos,"0",$list,$free); ?>
		</div>
		<?php }
		} ?>

</div>



<?php } ?>
<?php } ?>



<?php 
if(($total_results / $per_page) > $page) { ?>
<div id="page-<?php print $page + 1;?>" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>
<?php } ?>

<?php function thumbstoreactions($pic,$date,$fb,$setup,$lang,$pos,$click,$list,$free) { 
	global $sub;
	if($_REQUEST['view'] == "favorites") { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$pic['fav_date_id']."' ");
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
		if($sub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
		}

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



?>
<div class="thumbnailactions"><div class="inner" <?php if(($date['thumbactions'] == "1")&&($_REQUEST['view'] !== "favorites")==true) { ?>style="display: none;"<?php } ?>>
<div id="thumbactionsadd-<?php print $pic['pic_id'];?>" style="padding: 8px; display: none;"></div>
<?php 
if($_REQUEST['mobile'] == "1") { 
	$date['disable_icons'] = 0;
}
if($_REQUEST['view'] == "favorites") { 
	$date['disable_icons'] = 0;
}
?>
<?php if($date['cat_type'] == "proofing") { ?>
<div id="proof-status-<?php print $pic['pic_key'];?>" class="pc center">
<?php 
$ckp = doSQL("ms_proofing", "*", "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$pic['pic_id']."' "); 
if($ckp['proof_id'] <= 0) { print _proof_pending_review_; }
if($ckp['proof_status'] == 1) { print _proof_approved_; }
if($ckp['proof_status'] == 2) { print _proof_revision_requested_; }
?></div>
<?php } ?>

<?php 
if($date['disable_icons'] !== "1") { ?>
	<ul>
	<?php if($date['date_photo_price_list'] > 0) { 
		if(countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free!='1' ") > 0) { ?>
	 <li class="icon-basket the-icons" onclick="buyphotothumb('0','<?php print $pic['pic_key'];?>', '<?php print $date['date_id'];?>', '<?php print $pos;?>'); return false;"></li>
	 <?php } ?>
	 <?php if($free > 0) { ?>
		<li class="icon-down the-icons tip" onclick="freephoto('<?php print $pic['pic_key'];?>', '<?php print $date['date_id'];?>','<?php print $free;?>'); return false;" title="Free Download"></li>
	<?php } ?>
	 <?php } ?>
	 <?php if($date['allow_favs'] == "1") { ?>

	 <?php if(!isset($_SESSION['pid'])) { ?>
	 <li class="icon-heart-empty the-icons photo-fav photo-fav-login"  <?php if($click == 1) { ?>onClick="thumbactionfav('<?php print $pic['pic_id'];?>');"<?php } ?>>
  		<div class="favlogin"   id="thumb-fav-<?php print $pic['pic_id'];?>" >
			<div class="inner">
				<div class="pc"><?php print _to_add_to_favorites_;?> </div>
				<div class="pc"><a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=account&d=<?php print MD5($date['date_id']);?>"><?php print _log_in_;?></a> <?php print _or_;?> <a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=newaccount&d=<?php print MD5($date['date_id']);?>"><?php print _create_account_;?></a>.</div>
				<div class="clear"></div>
			</div>
		</div>
 </li>
 <?php } else { ?>
 <?php 
	$fav = doSQL("ms_people LEFT JOIN ms_favs ON ms_people.p_id=ms_favs.fav_person", "*", "WHERE MD5(fav_person)='".$_SESSION['pid']."' AND fav_pic='".$pic['pic_id']."' ");
	if($fav['fav_id']>0) { $tclass = "icon-heart"; } else { $tclass = "icon-heart-empty"; } 
 ?>
 <?php if($_REQUEST['view'] == "favorites") { ?>
 <li class="<?php print $tclass;?> the-icons tip" onclick="removefavthumb('<?php print $pos;?>','<?php print $pic['pic_key'];?>'); return false;" id="photo-fav-<?php print $pic['pic_key'];?>" title="<?php print _remove_;?>"></li>
 <?php } else { ?>
 <li class="<?php print $tclass;?> the-icons" onclick="addphotofavthumb('<?php print $pic['pic_key'];?>','<?php print $pic['pic_key'];?>'); return false;" id="photo-fav-<?php print $pic['pic_key'];?>" did="<?php print $date['date_id'];?>" sub_id="<?php print $sub['sub_id'];?>"></li>
<?php } ?>

<?php } ?>
<?php } ?>
<?php if($date['photo_social_share'] == "1") { ?>
<?php
	if($date['private'] == "0") { 
		if($sub['sub_id'] > 0) { 
			$link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link']."#photo=".$pic['pic_key'];
		} else { 
			$link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/#photo=".$pic['pic_key'];
		}
	} else { 

		if($sub['sub_id'] > 0) { 
			$link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link']."&image=".$pic['pic_key'];
		} else { 
			$link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?image=".$pic['pic_key'];
		}
	}
	$pic['full_url'] = true;
	?>
 <li class="icon-share the-icons shareoptions" <?php if($click == 1) { ?>onClick="thumbactionshare('<?php print $pic['pic_id'];?>');"<?php } ?> title="<?php print _share_;?>">
 		<div class="shareoption" style="display: none; position: absolute; right: 0; width: 100%;border: solid 1px #999999; margin: 0; text-align: center; z-index: 200; font-size: 12px; text-shadow: none; padding: 8px;"  id="thumb-share-<?php print $pic['pic_id'];?>" >
		<a href="http://www.facebook.com/dialog/feed?app_id=<?php print $fb['facebook_app_id'];?>&link=<?php print urlencode($link);?>&picture=<?php print getimagefile($pic,'pic_pic');?>&name=<?php print urlencode($date['date_title']);?>&redirect_uri=<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/&description=<?php print urlencode($fb['fb_photo_share']);?>" class="icon-facebook the-icons"></a>

		<a href="//pinterest.com/pin/create/button/?url=<?php print urlencode($link);?>&media=<?php print getimagefile($pic,'pic_pic');?>&description=<?php print urlencode($date['date_title']);?>" target="_blank" class="icon-pinterest the-icons"></a>

		<a href="https://twitter.com/intent/tweet?text=<?php print urlencode($date['date_title']);?>&url=<?php print urlencode($link);?>" class="icon-twitter the-icons" target="_blank"></a>

		<a href="mailto:?subject=Photo&body=<?php print urlencode($link);?>%0D%0A %0D%0A<?php print urlencode($fb['fb_photo_share']);?>" target="_blank" class="icon-mail the-icons"></a>

		</div>
 </li>
 <?php } ?>
</ul>
<?php } ?>
	<?php if($date['disable_filename'] !== "1") { ?><div class="pc" style="overflow: hidden;"><span title="<?php print $pic['pic_org'];?>"><?php print $pic['pic_org'];?></span></div><?php } ?>

		</div></div>
<?php } ?>
