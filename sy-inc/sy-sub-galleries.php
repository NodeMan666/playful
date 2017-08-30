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
require $setup['path']."/".$setup['inc_folder']."/listing_functions.php"; 
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

if(($date['cat_layout'] <=0)&&($date['cat_under'] > 0)==true) { 
	$upcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['cat_under']."' ");
	$cat_layout = $upcat['cat_layout'];
	if($cat_layout <= 0) { 
		$upcat2 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat['cat_under']."' ");
		$cat_layout = $upcat2['cat_layout'];
		if($cat_layout <= 0) { 
			$upcat3 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat2['cat_under']."' ");
			$cat_layout = $upcat3['cat_layout'];
			if($cat_layout <= 0) { 
				$upcat4 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat3['cat_under']."' ");
				$cat_layout = $upcat4['cat_layout'];
				if($cat_layout <= 0) { 
					$upcat5 = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$upcat4['cat_under']."' ");
					$cat_layout = $upcat5['cat_layout'];
				}
			}
		}

	}
} else { 
	$cat_layout = $date['cat_layout'];
}

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
		$and_sql .= " AND (sub_name LIKE '%".addslashes($tw)."%')";
	}
} else { 
	// $and_sub_under = "AND sub_under='".$sub['sub_id']."'";
}



if(!empty($_REQUEST['sub_id'])) { 
	if(!is_numeric($_REQUEST['sub_id'])) { die(); } 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
if(empty($_REQUEST['subpage'])) { 
	$subpage = 1;
} else {
	if(!is_numeric($_REQUEST['subpage'])) { die(); } 

	$subpage = $_REQUEST['subpage'];
}

if(empty($_REQUEST['keyWord'])) { 
	$and_sub_under = "AND sub_under='".$sub['sub_id']."'";
}

$total_results = countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' $and_sub_under $and_sql ORDER BY sub_order ASC, sub_name ASC ");


$sq_page = $subpage * $per_page - $per_page;

$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat_layout."' ");
$html .= $layout['layout_html'];
			?>
			
			
			<?php 
			$ssubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' $and_sub_under $and_sql ORDER BY sub_order ASC, sub_name ASC  LIMIT $sq_page,$per_page ");
			while($ssub = mysqli_fetch_array($ssubs)) { 
				$html = $layout['layout_html'];
				if($setup['do_not_get_subgallery_photo'] !== true) { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_sub_preview='".$ssub['sub_id']."' ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) { 
						$size = getimagefiledems($pic,$layout['layout_photo_size']);
						$thumb_html ="<a href=\"?sub=".$ssub['sub_link']."\"><img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$ssub['sub_id']."\" border=\"0\"></a>";

					} else { 
						$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$ssub['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
						if(!empty($pic['pic_id'])) {
							$size = getimagefiledems($pic,$layout['layout_photo_size']);
							$thumb_html ="<a href=\"?sub=".$ssub['sub_link']."\"><img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$ssub['sub_id']."\" border=\"0\"></a>";
						} else { 
							$under = doSQL("ms_sub_galleries", "*", "WHERE sub_under='".$ssub['sub_id']."'  ORDER BY sub_order ASC, sub_name ASC");
							if(!empty($under['sub_id'])) { 
								if(empty($thumb_html)) { 
									$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$under['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
									if(!empty($pic['pic_id'])) {
										$size = getimagefiledems($pic,$layout['layout_photo_size']);
										$thumb_html ="<a href=\"?sub=".$ssub['sub_link']."\"><img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$ssub['sub_id']."\" border=\"0\"></a>";
									} else { 
										$under2 = doSQL("ms_sub_galleries", "*", "WHERE sub_under='".$under['sub_id']."'  ORDER BY sub_order ASC, sub_name ASC");
										if(empty($thumb_html)) { 
											$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$under2['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
											if(!empty($pic['pic_id'])) {
												$size = getimagefiledems($pic,$layout['layout_photo_size']);
												$thumb_html ="<a href=\"?sub=".$ssub['sub_link']."\"><img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$ssub['sub_id']."\" border=\"0\"></a>";
											}

										}
									}
								}
							}
						}
					}
				}

				$page['list_sub_photo'] = $thumb_html;


				$page['list_sub_id'] = $ssub['sub_id'];
				$page['list_sub_title'] = "<a href=\"?sub=".$ssub['sub_link']."\">".$ssub['sub_name']."</a>";
				if(!empty($ssub['sub_pass'])) { 
					$page['list_sub_title'] ="<span class=\"the-icons icon-lock\"></span>".$page['list_sub_title'];
				}
				$page['list_sub_link'] = "?sub=".$ssub['sub_link'];
				$page['date_link'] = $date['date_link']; 
				$page['cat_folder'] = $date['cat_folder']; 

				if(!empty($layout['layout_folder'])) { 
					include $setup['path']."/".$layout['layout_folder']."/".$layout['layout_file'];
				} else { 
					include $setup['path']."/sy-layouts/".$layout['layout_file'];
				}
				unset($thumb_html);

			}

		?>

<?php 
if(($total_results / $per_page) > $subpage) { ?>
<div id="subpage-<?php print $subpage + 1;?>" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>
<?php } ?>
