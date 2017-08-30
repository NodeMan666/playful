<?php 
if(!empty($to_path)) { 
		require $to_path."sy-config.php";
} else { 
	if(!file_exists("../../sy-config.php")) { 
		require "../../../sy-config.php";
	} else { 
		require "../../sy-config.php";
	}
}
$news_page = true;
if((empty($date_id))AND(empty($date_cat_id))AND(empty($tag_id))==true) {
	// header("location: /");
	print "uh oh";
	exit();
}
if((!empty($date_id))AND(!is_numeric($date_id))==true) { die(); }
if((!empty($date_cat_id))AND(!is_numeric($date_cat_id))==true) { die(); }
require $setup['path']."/sy-main.php"; 
	?>
	<?php 
	if($date_id > 0) { 
		require $setup['path']."/sy-inc/page_display.php";
	}
	if($tag_id >0) { 
		$tag = doSQL("ms_tags", "*", "WHERE tag_id='$tag_id' ");
		print "<div class=\"pc\"><h1>".ucfirst($tag['tag_tag'])."</h1></div>";
	}
	if($date_cat_id > 0) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat_id."' ");
		$hash_image = 1;
		if($cat['cat_type'] == "stock") { 
			listContent($date_cat_id,$tag_id,0,0,0,0,0); 
 			include $setup['path']."/".$setup['inc_folder']."/gallery_thumbnails.php";

		} else { 
			if($cat['cat_content'] > 0) { 
				$date_id = $cat['cat_content'];
				$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE date_id='".$date_id."' ");
				require $setup['path']."/sy-inc/page_display.php";
			}
			if($show['disable_page_listing'] <= 0) { 
				listContent($date_cat_id,$tag_id,0,0,0,0,0); 
			}
		}
	}
	if($tag_id > 0) { 
		listContent($date_cat_id,$tag_id,0,0,0,0,0); 
	}
?>
<div id="endpagelistings" style="position: absolute;"></div>
<div id="listingpage-2" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>

<?php  include $setup['path']."/sy-footer.php"; ?>
