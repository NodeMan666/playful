<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");

date_default_timezone_set(''.$site_setup['time_zone'].'');
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
		}
	}
}
if((!empty($_REQUEST['date_id']))&&(!is_numeric($_REQUEST['date_id']))==true) { 
	print "nofound a";
	die();
}
if((!empty($_REQUEST['sub_id']))&&(!is_numeric($_REQUEST['sub_id']))==true) { 
	print "nofound b";
	die();
}
if($_REQUEST['view'] == "favorites") { 
	$pic = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE pic_key='".$_REQUEST['pic_key']."' AND MD5(fav_person)='".$_SESSION['pid']."' $and_pic ");
	if($pic['fav_sub_id'] > 0) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$pic['fav_sub_id']."' ");
	}
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$pic['fav_date_id']."' ");
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
	if($pic['fav_sub_id'] > 0) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$pic['fav_sub_id']."' ");
		if($sub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
		}
		if(($sub['sub_price_list'] <= 0) && ($sub['sub_under'] > 0) == true) { 
			$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_under']."' ");
			if($upsub['sub_price_list'] > 0) { 
				$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$upsub['sub_price_list']."' ");
			}
		}
	}

} else { 
	if((!empty($_REQUEST['keyWord']))&&($_REQUEST['sub_id'] <=0) ==true) { 
		$and_pic = " AND bp_blog='".$_REQUEST['date_id']."' ";
	} elseif((!empty($_REQUEST['kid']))&&($_REQUEST['sub_id'] <=0) ==true) { 
		$and_pic = " AND bp_blog='".$_REQUEST['date_id']."' ";
	} else { 
		$and_pic = " AND bp_blog='".$_REQUEST['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."'";
	}
	if($_REQUEST['view'] == "highlights") { 
		$and_pic = " AND bp_blog='".$_REQUEST['date_id']."' AND pic_fav_admin='1' ";
	}
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_blog_photos.bp_blog=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id LEFT JOIN ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE pic_key='".$_REQUEST['pic_key']."' $and_pic ");
}

$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if(!empty($date['date_photo_keywords'])) { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pic_key']."' ");
	$pic['blog_photo_file'] = $date['blog_photo_file'];
	$pic['date_id'] = $date['date_id'];
	$pic['date_photo_price_list'] = $date['date_photo_price_list'];
}

if(empty($pic['pic_id'])) { 
	print "notfound";
	exit();
}
############## New Changes #############
if($_REQUEST['view'] !== "favorites") { 
	if($date['date_photo_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
	}

	if((!empty($_REQUEST['sub_id'])) && (is_numeric($_REQUEST['sub_id'])) == true) { 
		$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
		if($sub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
		}
		if(($sub['sub_price_list'] <= 0) && ($sub['sub_under'] > 0) == true) { 
			$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_under']."' ");
			if($upsub['sub_price_list'] > 0) { 
				$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$upsub['sub_price_list']."' ");
			}
		}
	}
}

if($pic['bp_pl'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$pic['bp_pl']."' ");
}
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}

###############################
/*
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
if($sub['sub_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
} else { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$pic['date_photo_price_list']."' ");
}

if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}
*/
$pic_file = $pic['blog_photo_file'];
$pic_file_select = selectPhotoFile($pic_file,$pic);

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
if($mobile == 1) { 
	$pic_file = "pic_pic";
}

$sytist_store = true;
$img = getimagefile($pic,$pic_file);
$pic['full_url'] = true;
$thumb = getimagefile($pic,'pic_th');
$mini = getimagefile($pic,'pic_mini');
$this_pic = getimagefile($pic,$pic_file);



$html  = "<div id=\"photo-".$pic['pic_key']."-container\" class=\"photocontainer\" style=\"position: absolute; left: 0; text-align: center; width: 1px; display: none; \">";

$html .="<img id=\"photo-".$pic['pic_key']."\" pkey=\"".$pic['pic_key']."\" pfid=\"".MD5($pic_file)."\" cid=\"".MD5($cat_id)."\" thumb=\"".$thumb."\"  mini=\"".$mini."\" ";

if(!empty($_SESSION['pid'])) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	$fav = doSQL("ms_favs", "*", "WHERE fav_pic='".$pic['pic_id']."' AND fav_person='".$person['p_id']."' ");
	if(!empty($fav['fav_id'])) { 
		$html .= "fav=\"1\" ";
	}
}
 $pprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$list['list_id']."' AND pp_free!='1' ");
 $packprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$list['list_id']."' AND pc_package>'0' ");
 $storeprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$list['list_id']."' AND pc_store_item>'0' ");

$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
if($freedownload['pp_id'] > 0) { 
	$free = $freedownload['pp_id'];
}
if(customerLoggedIn()) { 
	$gal_free = doSQL("ms_gallery_free", "*", "WHERE free_person='".$person['p_id']."' AND ((free_gallery='".$date['date_id']."' AND free_sub='0') OR (free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."')) ");
	if($gal_free['free_id'] > 0) {
		$free = $gal_free['free_product'];
	}
}

 
if(($pprods + $packprods + $storeprods) > 0) {
	if($pic['bp_pl'] > 0) { 
		$html .= "pl=\"".$pic['bp_pl']."\" ";
	} else { 
		$html .= "pl=\"".$list['list_id']."\" ";
	}
 }

 if((is_array($_SESSION['comparephotos']))&&(in_array($pic['pic_id']."|".$pic['date_id']."|".$sub['sub_id'],$_SESSION['comparephotos']))==true) { 
	$html .= "compare=\"1\" ";
 }
if(countIt("ms_photo_products_groups", "WHERE group_package='1' AND group_list='".$list['list_id']."' ") > 0) { 
	$html .= "pkgs=\"1\" ";
}
$html .= "subid=\"".$sub['sub_id']."\" ";
$html .= "did=\"".$pic['date_id']."\" ";
$html .= "share=\"".$pic['photo_social_share']."\" ";
$html .= "ppos=\"".$pic['pic_key']."\" ";
$html .= "sharefile=\"".$this_pic."\" ";

if(!empty($sub['sub_id'])) { 
	$html .= "pagelink=\"".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$pic['cat_folder']."/".$pic['date_link']."/?sub=".$sub['sub_link']."\" ";
	$html .= "pagetitle=\"".htmlspecialchars($pic['date_title'])." > ".htmlspecialchars($sub['sub_name'])."\" ";
} else { 
	$html .= "pagelink=\"".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$pic['cat_folder']."/".$pic['date_link']."/\" ";
	$html .= "pagetitle=\"".htmlspecialchars($pic['date_title'])."\" ";
}
$html .= "org=\"".htmlspecialchars($pic['pic_org'])."\" ";
$html .= "fd=\"".$free."\" ";

if($nosrc !== "1") { 
	$html .= "src=\"".$img."\" ";
	$html .= "thissrc=\"".$img."\" ";
}	 else {
	$html .= "thissrc=\"".$img."\" ";
}
if((!empty($border_color))&&(!empty($border_size))==true) { 
	$border = "border: solid ".$border_size."px ".$border_color.";";
}

if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
	// Is gallery owner
} else { 
	$and_where .= " AND pic_hide!='1' ";
}

if($_REQUEST['view'] == "favorites") { 
	$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
	$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
	$pics_orderby = "pic_org";
	$pics_acdc  = "ASC";
	
	$pic_file = "pic_large";
	$fixed_height = 0;
	$date['thumb_scroller_open'] = 0;
	$position = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id","WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."')  AND pic_org<='".addslashes(stripslashes($pic['pic_org']))."' ORDER BY pic_org ASC");

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
		if($pics_acdc == "ASC") { 
			$sp = "<= ";
		} else { 
			$sp = ">= ";
		}
		$position = countIt("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ","$pics_where $and_where AND $pics_orderby $sp'".addslashes(stripslashes($pic[$pics_orderby ]))."' ");
		// print "<li>POSITION: ".$position;
	} else { 
		if($_REQUEST['view'] !== "highlights") { 

			if(!empty($sub['sub_id'])) { 
				$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
				$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
			} else { 
				if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
					$and_sub = "AND bp_sub='0' ";
				}
			}
		}

		if($_REQUEST['view'] == "highlights") { 
			$pics_where = "WHERE bp_blog='".$pic['date_id']."' AND pic_fav_admin='1' ";
			$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
			$pics_orderby = "pic_org";
			$pics_acdc  = "ASC";
			$pic_file = $pic['blog_photo_file'];

			$position = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_blog_photos.bp_blog=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","WHERE pic_org<='".$pic['pic_org']."' AND bp_blog='".$pic['date_id']."'   AND pic_fav_admin='1'  $and_where");

		//	print "<h1>WHERE pic_org<='".$pic['pic_org']."' AND bp_blog='".$pic['date_id']."'   AND pic_fav_admin='1'  :::::::::: ".$position."</h1>";

		} else { 
			$pics_where = "WHERE bp_blog='".$pic['date_id']."' $and_sub ";
			$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
			$pics_orderby = "bp_order";
			$pics_acdc  = "ASC";
			$pic_file = $pic['blog_photo_file'];
			$position = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_blog_photos.bp_blog=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","WHERE ".$pics_orderby."<='".$pic[$pics_orderby]."' AND bp_blog='".$pic['date_id']."'  AND bp_sub='".$_REQUEST['sub_id']."' $and_where ");
		}
	}
}


$and_where .= getSearchString();
$search = getSearchOrder();
$and_where .= " AND  pic_no_dis='0' ";
// print "<li>XXXXXXXx $and_where";
// print "<pre>"; print_r($_REQUEST); print "</pre>";

if($search['acdc'] == "ASC") { 
	$next_acdc = "DESC";
	$next_a = "<";
	$prev_acdc = "ASC";
	$prev_a = ">";
} else {
	$next_acdc = "ASC";
	$next_a = ">";
	$prev_acdc = "DESC";
	$prev_a = "<";
}
$order_field = $search['orderby'];




if($_REQUEST['view'] == "favorites") { 
	$prev = doSQL("$pics_tables", "*", "$pics_where $and_where AND pic_org<'".addslashes(stripslashes($pic['pic_org']))."' ORDER BY $pics_orderby DESC LIMIT 1 ");
	$next = doSQL("$pics_tables", "*", "$pics_where $and_where AND pic_org>'".addslashes(stripslashes($pic['pic_org']))."'  ORDER BY $pics_orderby ASC LIMIT 1 ");
} else { 
	if(!empty($date['date_photo_keywords'])) { 
		if($pics_acdc == "ASC") { 
			$pp = "< ";
			$np = ">";
		} else { 
			$pp = "> ";
			$np = "<";
		}
		if($pics_acdc == "ASC") { 
			$prev_orderby = "DESC";
		} else { 
			$prev_orderby = "ASC";
		}
		$prev = doSQL("$pics_tables", "*", "$pics_where $and_where AND $pics_orderby $pp'".addslashes(stripslashes($pic[$pics_orderby ]))."' ORDER BY $pics_orderby $prev_orderby LIMIT 1 ");
		$next = doSQL("$pics_tables", "*", "$pics_where $and_where AND $pics_orderby $np'".addslashes(stripslashes($pic[$pics_orderby ]))."' ORDER BY $pics_orderby $pics_acdc LIMIT 1 ");
	} else { 
		if($_REQUEST['view'] == "highlights") { 
			$prev = doSQL("$pics_tables", "*", "$pics_where $and_where AND pic_org<'".$pic['pic_org']."' ORDER BY $pics_orderby DESC LIMIT 1 ");
			$next = doSQL("$pics_tables", "*", "$pics_where $and_where AND pic_org>'".$pic['pic_org']."' ORDER BY $pics_orderby ASC LIMIT 1 ");
		} else { 
			$prev = doSQL("$pics_tables", "*", "$pics_where $and_where AND bp_order<'".$pic['bp_order']."' ORDER BY $pics_orderby DESC LIMIT 1 ");
			$next = doSQL("$pics_tables", "*", "$pics_where $and_where AND bp_order>'".$pic['bp_order']."' ORDER BY $pics_orderby ASC LIMIT 1 ");
		}
	}
}

$html .= " data-next=\"".$next['pic_key']."\" data-previous=\"".$prev['pic_key']."\" ";
$html .= "pos=\"".$position."\" ";
if(($pic['pic_fav_admin'] == "1") && ($date['show_star'] == "1") == true) {
	$html .= "photofav=\"".$pic['pic_fav_admin']."\" ";
}
if($pic['pic_hide'] == "1") { 
	$html .= "pic_hide=\"1\" ";
}
if($date['date_owner'] > 0) { 
	$html .= "ht=\""._hidden_photo_."\" ";
}
$html .= "title=\"".htmlspecialchars($pic['pic_title'])."\" class=\"photo enlarge ".$pic['pic_key']."\" ".$size[3]." style=\"position: relative; margin: auto; "; if($bg_use == "1") { $html .= "border-color: ".$border_color."; background: ".$bg_color .";"; }  if($contain == true) { $html .= "width: 100%; height: auto; max-width: ".$size[0]."px; max-height: ".$size[1]."px;  -ms-interpolation-mode: bicubic; "; }  $html .=" \" ww=\"".$size[0]."\" hh=\"".$size[1]."\" bg_color=\"".$pic['pic_bg_color']."\">";


/*display picture text and picture title */

/*if($captionwhere !== "below") { 
	if(((!empty($pic['pic_text']))OR(!empty($pic['pic_title']))) == true) {  
		$html .= "<div id=\"caption-".$pic['pic_id']."\"><div class=\"photocaptioncontainer\" id=\"photo-caption-".$add_id."\"><div class=\"inner\"><h3>".$pic['pic_title']."</h3>".nl2br($pic['pic_text'])."</div></div></div>";
	}
}

 $html .= "</div>\r\n\r\n";
if($captionwhere == "below") { 
	if(((!empty($pic['pic_text']))OR(!empty($pic['pic_title']))) == true) {  
		$html .= "<div id=\"caption-".$pic['pic_id']."\"><div class=\"photocaptionbelow\" id=\"photo-caption-".$add_id."\"><div class=\"inner\"><h3>".$pic['pic_title']."</h3>".nl2br($pic['pic_text'])."</div></div></div>";
	}
}*/
print $html;

/* 
$pic				The pic photo array
$pic_file		Which photo size to use
$wm			The watermark data array
$size			The GetImageSize() array
$contain		True or False. To contain the width to the container
$cssclass	The class of the photo
*/



?>