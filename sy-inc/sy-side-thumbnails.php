<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/icons.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
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
include $setup['path']."/sy-inc/mobile.detect.php";
$detect = new Mobile_Detect;

if($detect->isTablet()){
	$tablet = true;
	$hide_tips = true;
}

if ($detect->isMobile() && !$detect->isTablet()) {
	$hide_tips = true;
}


// $_REQUEST['view'] = "favorites";




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

if(!empty($_REQUEST['sub_id'])) { 
	if(!is_numeric($_REQUEST['sub_id'])) { die(); } 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	$sub_array = explode(",",$sub['sub_under_ids']);
}


	if($_REQUEST['page'] <= 1) { 
		if($_REQUEST['sub_id'] > 0) { 
			if(!is_numeric($_REQUEST['sub_id'])) { die(); } 
			$and_sub = " AND sub_under='".$_REQUEST['sub_id']."' ";
		} else { 
			$and_sub = " AND sub_under='0' ";
		}

		$sub_gals = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='0' ORDER BY sub_order ASC");
		?>
		<div class="stylelist"><div class="inner"><ul>

		<?php
		if(customerLoggedIn()) {
			$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");	
			if(countIt("ms_favs", "WHERE fav_person='".$p['p_id']."' ") > 0) { 
			?>
			<li><a href="" onclick="sidethumbs('0','1','1','<?php print $date['date_id'];?>'); return false;" <?php if($_REQUEST['view'] == "favorites") { ?>class="bold"<?php } ?>>My Favorites (<?php print countIt("ms_favs", "WHERE fav_person='".$p['p_id']."' "); ?>)</a></li>
			<?php 
			}
		}
		?>
		<?php 	if(($_REQUEST['view'] !== "favorites") || ($_REQUEST['date_id'] > 0) == true) {  ?>
		<li><a href="" onclick="sidethumbs('0','1'); return false;" <?php if(($_REQUEST['sub_id'] <= 0) && ($_REQUEST['view'] !== "favorites") == true) { ?>class="bold"<?php } ?>><?php print $date['date_title'];?></a></li>
		<?php 
			while($subgal = mysqli_fetch_array($sub_gals)) { ?>
			<li><a href="" onclick="sidethumbs('<?php print $subgal['sub_id'];?>','1'); return false;"  <?php if($_REQUEST['sub_id'] == $subgal['sub_id']) { ?>class="bold"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $subgal['sub_name'];?></a></li>

			<?php 
			if($sub['sub_id'] > 0) { 
				if((in_array($subgal['sub_id'],$sub_array)) || ($subgal['sub_id'] == $sub['sub_id']) == true) { 

					foreach($sub_array AS $usub) { 
						if(!empty($usub)) { 
						$us = whileSQL("ms_sub_galleries", "*", "WHERE sub_under='".$usub."' ");
						while($u = mysqli_fetch_array($us)) { 
						?>	
						<li><a href="" onclick="sidethumbs('<?php print $u['sub_id'];?>','1'); return false;"  <?php if($_REQUEST['sub_id'] == $u['sub_id']) { ?>class="bold"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php print	$u['sub_name'];?></a></li>

					<?php 
							}
						}
					}
					$msub_gals = whileSQL("ms_sub_galleries", "*", "WHERE sub_under='".$sub['sub_id']."'  ORDER BY sub_order ASC");
						while($msubgal = mysqli_fetch_array($msub_gals)) { ?>
					<li><a href="" onclick="sidethumbs('<?php print $msubgal['sub_id'];?>','1'); return false;"  <?php if($_REQUEST['sub_id'] == $msubgal['sub_id']) { ?>class="bold"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $msubgal['sub_name'];?></a></li>
					<?php 
						}
					}
				}
			}
		 }
		print "</ul></div></div>";
	}


$and_where = getSearchString();
$search = getSearchOrder();


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
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}

if(!empty($_REQUEST['passcode'])) { 
	// $and_sub = "";
}
// print "<h1>sub: $and_sub </h1>";
if($_REQUEST['view'] == "highlights") { 
	$and_where .= " AND pic_fav_admin='1' ";
}
if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
	// Is gallery owner
} else { 
	$and_where .= " AND pic_hide!='1' ";
}

if((!empty($_REQUEST['date_id'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights"))== true) { 
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
		$piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." ");
		$cx = 0;
	} else { 

		if($_REQUEST['view'] == "highlights") { 
			$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' AND pic_fav_admin='1' $and_where GROUP BY pic_id ORDER BY bp_order ASC");
		} else { 
			$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC");
		}
	}
} elseif(!empty($_REQUEST['key_id'])) { 
	$piccount = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."' AND pic_client='".$_REQUEST['pic_client']."' ORDER BY pic_order DESC ");
} elseif($_REQUEST['view'] ==  "favorites") { 
	$piccount = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE MD5(fav_person)='".$_SESSION['pid']."' AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");

} else  { 
	$piccount = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where  ORDER BY ".$search['orderby']." ".$search['acdc']." $and_acdc");
}
if(!empty($piccount)) { 
	$total_results = mysqli_num_rows($piccount);
}

if((!empty($_REQUEST['date_id'])) && ((empty($_REQUEST['view'])) || ($_REQUEST['view'] == "highlights"))== true) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."'  ");
	if($date['green_screen_backgrounds'] > 0) { 
		$date['thumb_style'] = 2;
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
		$and_date_tag .= " OR bp_blog='".$_REQUEST['date_id']."' ";
		$and_date_tag .= " ) ";
		$pics = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']."  LIMIT $sq_page,$per_page");

	} else { 
		if($_REQUEST['view'] == "highlights") { 
			$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND pic_fav_admin='1'  $and_where GROUP BY pic_id ORDER BY pic_org ASC   LIMIT $sq_page,$per_page");
		} else { 
			$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC   LIMIT $sq_page,$per_page");
		}
	}

	####### New changes ##################

	if($date['date_photo_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
	}

	if($sub['sub_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
	}
	if(($sub['sub_price_list'] <= 0) && ($sub['sub_under'] > 0) == true) { 
		$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_under']."' ");
		if($upsub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$upsub['sub_price_list']."' ");
		}
	}
	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		if($person['p_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
		}
	}
	if($list['list_id'] > 0) { 
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
		$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['cat_id']."' "); 
		$date['thumb_style'] = $def['thumb_style'];
		$date['jthumb_height'] = $def['jthumb_height'];
		$date['jthumb_margin'] = $def['jthumb_margin'];
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
	$date['disable_icons'] = 0;
	$date['thumbactions'] = 0;
}

if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
	// Is gallery owner
	$gallery_owner = true;
	$date['disable_icons'] = "0";
} 

?>

<div id="sidethumbcontainer">
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
	}
}


	$pic_file = "pic_th";
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


	$size = getimagefiledems($pic,"pic_pic");

	
	if($date['date_owner'] > 0) { 
		if($pic['pic_hide']>0) { $hideclass = "hiddenphoto"; } else { $hideclass = ""; } 
	}
?>


		<div class="thumbimage" style="position: relative;   float: left; text-align: center;
	width: 25%;">
	<div class="thumbimage" style="
    height: 100px;
	width: 90%;
    vertical-align: top;
    display: inline-block;
    *display: inline;
    zoom: 1;
	margin: 8px;
	cursor: pointer;
	background-image: url('<?php print getimagefile($pic,"pic_th");?>');
    background-repeat: no-repeat;
    background-size: contain;
	background-position: center; 
	" onclick="changephoto('<?php print getimagefile($pic,"pic_pic");?>','<?php print $pic['pic_key'];?>','<?php if($_REQUEST['view'] == "favorites") { print $pic['fav_date_id']; } else { print $date['date_id']; } ?>','<?php if($_REQUEST['view'] == "favorites") { print $pic['fav_sub_id']; } else { print $sub['sub_id']; } ?>','<?php print $size[0];?>','<?php print $size[1];?>'); return false;">
	</div>
	</div>
	<?php 
	}

?>
<div class="clear"></div>

<?php 
if(($total_results / $per_page) > $page) { ?>
<div id="sidethumbpage-<?php print $page;?>" style="width: 100%; height: 100px;" class="sidethumbpopulate" data-next-page="<?php print $page + 1;?>" data-loaded="<?php if($page == "1") { ?>1<?php } ?>"  data-total-photos="<?php print $pic_count;?>" data-total-pages="<?php print ceil($total_results / $per_page);?>" data-this-page="<?php print $page;?>"><div class="loadingspinner"></div></div>
<?php } ?>