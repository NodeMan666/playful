<?php
require "sy-config.php";
session_start();
header("Cache-control: private"); 
header('Content-Type: text/html; charset=utf-8');
if($setup['ob_start_only'] == true) { 
	ob_start();  
} else { 
	if ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ) {  
		ob_start( "ob_gzhandler" );  
	}  
	else {  
		ob_start();  
	}  
}

unset($_SESSION['query']);

require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$em_settings = doSQL("ms_email_list_settings","*", ""); 
$photo_setup = doSQL("ms_photo_setup","gallery_favicon","");
date_default_timezone_set(''.$site_setup['time_zone'].'');

if(!isset($_COOKIE['ms_session'])) {
	$time=time()+3600*24*365*2;
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	$ip = str_replace(".", "", getUserIP());
	$cvar = $ip.date('Ymdhis');
	SetCookie("ms_session",$cvar,$time,"/",null);
	$_SESSION['ms_session'] = $cvar;
} else {
	$_SESSION['ms_session'] = $_COOKIE['ms_session'];
}
if((!isset($_SESSION['pid'])) AND(isset($_COOKIE['persid']))==true) {
	$_SESSION['loggedin'] = true;
	$_SESSION['pid'] =$_COOKIE['persid'];
}

if(!empty($_REQUEST['previewTheme'])) { 
	if(!is_numeric($_REQUEST['previewTheme'])) { 
		header("location: /?no=no");
		die();
	}
	$_SESSION['previewTheme'] = $_REQUEST['previewTheme'];
	session_write_close();
	if(!empty($_REQUEST['sweetness'])) { 
		header("location: ".$_SERVER['PHP_SELF']."?sweetness=".$_REQUEST['sweetness']."");
	} else { 
		header("location: ".$_SERVER['PHP_SELF']."");
	}
	exit();
}
if($_REQUEST['endPreview'] == "1") { 
	unset($_SESSION['previewTheme']);
	session_write_close();
	header("location: ".$_SERVER['PHP_SELF']."");
	exit();
}
if($_REQUEST['previewMobile'] == "1") { 
	unset($_SESSION['previewIpad']);
	$_SESSION['previewMobile'] = 1;
	session_write_close();
	header("location: ".$_SERVER['PHP_SELF']."");
	exit();
}
if($_REQUEST['previewIpad'] == "1") { 
	unset($_SESSION['previewMobile']);
	$_SESSION['previewIpad'] = 1;
	session_write_close();
	header("location: ".$_SERVER['PHP_SELF']."");
	exit();
}
if($_REQUEST['endMobile'] == "1") { 
	unset($_SESSION['previewMobile']);
	session_write_close();
	header("location: ".$_SERVER['PHP_SELF']."");
	exit();
}
if($_REQUEST['endIpad'] == "1") { 
	unset($_SESSION['previewIpad']);
	session_write_close();
	header("location: ".$_SERVER['PHP_SELF']."");
	exit();
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

$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}


foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = stripslashes(stripslashes("".$_REQUEST[$id].""));
		}
	}
}


require $setup['path']."/".$setup['inc_folder']."/listing_functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
require $setup['path']."/".$setup['inc_folder']."/icons.php";
if(!empty($_REQUEST['wd'])) { 
	$wall = doSQL("ms_wall_saves LEFT JOIN ms_calendar ON ms_wall_saves.wall_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","*","WHERE wall_link='".$_REQUEST['wd']."' ");
	if($wall['wall_id'] > 0) { 
		header("location: ".$setup['temp_url_folder'].$wall['cat_folder']."/".$wall['date_link']."/?view=room&rw=".$wall['wall_link']."");
		session_write_close();
		exit();
	}
}
if($sytist_store == true) { 
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
	$store = doSQL("ms_store_settings", "*", "");
}
$cset = doSQL("ms_calendar_settings", "*", "");
$wm = doSQL("ms_watermarking", "*", "");

include $setup['path']."/sy-inc/mobile.detect.php";
$detect = new Mobile_Detect;

if($detect->isTablet()){
	$ipad = true;
	$isipad = true;
}

if ($detect->isMobile() && !$detect->isTablet()) {
	$mobile = true;
	$site_type = "mobile";
}

if($_SESSION['previewMobile'] == 1) { 
	$mobile = true;
	$site_type = "mobile";
}
if($_SESSION['previewIpad'] == 1) { 
	$ipad = true;
}

securityCheck();
$fb = doSQL("ms_fb", "*", "");
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

if($setup['affiliate_program'] == true) { 
	if(!empty($_REQUEST['r'])) { 
		$aff_code = sql_safe($_REQUEST['r']);

		$aff = doSQL("ms_affiliate", "*", "WHERE aff_link_code='".$aff_code."' ");
		if(!empty($aff['aff_id'])) { 
			$aff_settings = doSQL("ms_affiliate_settings", "*", "");
			// unset($_COOKIE['msaff']);
			if(!isset($_COOKIE['msaff'])) {
				$time=time()+3600*$aff_settings['cookie_length']*60;
				SetCookie("msaff",$aff['aff_id'],$time,"/",null);
				$cl = insertSQL("ms_affiliate_click", "click_date=NOW(), click_aff='".$aff['aff_id']."', click_ip='".getUserIP()."', click_ref='".addslashes(stripslashes($_SERVER['HTTP_REFERER']))."', click_ref_adword_site='".addslashes(stripslashes($_REQUEST['p']))."' ");
				SetCookie("msaffc",$cl,$time,"/",null);
				$_REQUEST['afc'] = $aff['aff_id'];
				// print "Set cookie";
			} else { 
				$pcl = doSQL("ms_affiliate_click", "*", "WHERE click_id='".$_COOKIE['msaffc']."' ");
				$cl = insertSQL("ms_affiliate_click", "click_date=NOW(), click_aff='".$aff['aff_id']."', click_ip='".addslashes(stripslashes(getUserIP()))."', click_ref='".addslashes(stripslashes($_SERVER['HTTP_REFERER']))."', click_previous='".$pcl['click_id']."', click_ref_adword_site='".addslashes(stripslashes($_REQUEST['p']))."' ");
				// print "Cookie already set: ".$pcl['click_id']."";
				$_REQUEST['afc'] = $aff['aff_id'];
			}
		}
	}
}

?>
<?php if($photo_cart_included !== true) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML  xmlns="http://www.w3.org/1999/xhtml" <?php if($fb['disable_facebook'] !== "1") { ?>xmlns:fb="http://www.facebook.com/2008/fbml"<?php } ?> xml:lang="en" lang="en">
<?php } ?>
 <?php
/* GETTING PHOTO ALBUM DATA */
if($date_id > 0) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE date_id='".$date_id."' ");
	if($date['date_gallery_exclusive'] == "1") {
		$ge = doSQL("ms_gal_exclusive", "*", "WHERE gal_id='".$date['date_gallery_exclusive']."' ");
	}

	if((($date['change_price_list'] > 0) && ($date['change_price_list_date'] !== "0000-00-00")) AND (date('Y-m-d') > $date['change_price_list_date']) AND ($date['change_price_list'] !== $date['date_photo_price_list'])== true) { 
		updateSQL("ms_calendar", "date_photo_price_list='".$date['change_price_list']."' WHERE date_id='".$date['date_id']."' ");
		$date['date_photo_price_list'] = $date['change_price_list'];
	}
	if((($date['change_shipping_group'] > 0) && ($date['change_shipping_group_date'] !== "0000-00-00")) AND (date('Y-m-d') > $date['change_shipping_group_date']) AND ($date['change_shipping_group'] !== $date['shipping_group'])== true) { 
		updateSQL("ms_calendar", "shipping_group='".$date['change_shipping_group']."' WHERE date_id='".$date['date_id']."' ");
		$date['shipping_group'] = $date['change_shipping_group'];
	}


	if($sytist_store == true) { 
		if($date['date_photo_price_list'] > 0) { 
			header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			header('Pragma: no-cache'); // HTTP 1.0.
			header('Expires: 0'); // Proxies.
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
		}
	}
	if($date['cat_content'] == $date['date_id']) { 
		header("location: ".$date['cat_folder']."/");
		session_write_close();
		exit();
	}

	if($_REQUEST['view'] == "contact") { 
		$contact = doSQL("ms_calendar", "*", "WHERE date_id='".$ge['gal_contact_page']."' ");
		$date['date_text'] = $contact['date_text'];
		$date['page_form'] = $contact['page_form'];
		$date['page_fix_width'] = "1024";
	} 
	if(($_REQUEST['view'] == "favorites") || ($_REQUEST['view'] == "account") || ($_REQUEST['view'] == "cart") || ($_REQUEST['view'] == "checkout") || ($_REQUEST['view'] == "order") == true){ 
		$date['date_text'] = "";
	} 

}
if(($_REQUEST['view'] == "checkout") || ($_REQUEST['view'] == "cart") || ($_REQUEST['view'] == "account")  || ($_REQUEST['view'] == "order")  == true) { 
		$date['page_fix_width'] = "1200";
}
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}

if(!empty($_REQUEST['sub'])) { 
	if(!ctype_alnum($_REQUEST['sub'])) { header("location: /?no=no"); die(); } 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_link='".$_REQUEST['sub']."' ");
}

if($page_404==true) {
	$date = doSQL("ms_calendar", "*", "WHERE page_404='1' ");
	$page_theme = $date['page_theme'];
	$add_meta_title .= "".$date['date_title']."";
	$add_meta_keys .= "".$date['page_keywords']."";
	$add_meta_descr = strip_tags($date['date_text']);
	$add_meta_descr = preg_replace('/\s\s+/', ' ', $add_meta_descr);
	$add_meta_descr = (substr_replace(strip_tags(trim($add_meta_descr)), "", 200). "");
}


if($date['page_theme'] > 0) {
	$page_theme = $date['page_theme'];
}
if(!empty($_REQUEST['previewNews'])) {
	if(!is_numeric($_REQUEST['previewNews'])) { 
		header("location: /?no=no");
		die();
	}

	$date = doSQL("ms_calendar", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id='".$_REQUEST['previewNews']."' ");
}



if($date_cat_id > 0){ 
	$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat_id."' ");
}
if($date['date_cat'] > 0) { 
	$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
} 

if($bcat['cat_type'] == "clientphotos") { 
	if($date['date_id'] > 1) { 
		$_SESSION['last_gallery'] = $date['date_id'];
		if($sub['sub_id'] > 0) { 
			$_SESSION['last_gallery_sub'] = $sub['sub_id'];
		} else { 
			unset($_SESSION['last_gallery_sub']);
		}
	}
}

if($date['page_under'] > 0) { 
	$up_date = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($up_date['date_cat'] > 0) { 
		$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$up_date['date_cat']."' ");
	} 
}

if($bcat['cat_id'] > 0) { 
	if(!empty($bcat['cat_meta_title'])) { 
		$cat_meta_title = $bcat['cat_meta_title'];
	} else { 
		$cat_meta_title = $bcat['cat_name'];
	}
	// $cat_meta_title .= " ".$site_setup['sep_meta_title']." ".$bcat['cat_name']."";
	if($bcat['cat_theme'] > 0) { 
		$page_theme = $bcat['cat_theme'];
	}
	if(!empty($bcat['cat_under_ids'])) { 
		$scats = explode(",",$bcat['cat_under_ids']);
		$main_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scats[0]."' ");
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			if($ckcat <=0) { $top_section = $tcat['cat_id']; } 
			if(!empty($tcat['cat_meta_title'])) { 
				$add_meta_title .= "".$tcat['cat_meta_title']."  ".$site_setup['sep_meta_title']." ";
			} else { 
				$add_meta_title .= "".$tcat['cat_name']."  ".$site_setup['sep_meta_title']." ";
			}
			$ckcat++;
		}
	}
	$add_meta_title .= " ".$cat_meta_title."";
}
if(($top_section <=0)&&($bcat['cat_id'] > 0)==true) { 
	$top_section = $bcat['cat_id'];
}

if($tag_id > 0){ 
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$tag_id."' ");
	$cat_meta_title .= " ".$site_setup['sep_meta_title']." ".$tag['tag_tag']."";
	if(empty($date['date_id'])) { 
	$add_meta_title .= "".ucfirst($tag['tag_tag'])."";
	}
}
if($bcat['cat_layout'] <=0) { 
	$topcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$top_section."' ");
	$layout_id = $topcat['cat_layout'];
} else { 
	$layout_id = $bcat['cat_layout'];
}
if($layout_id <=0) { 
	if(!empty($setup['default_listing_layout'])) { 
		$layout_id = $setup['default_listing_layout'];
	} else { 
		$layout_id = 1;
	}
}
$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$layout_id."' ");

$link_count = 0;


if(((!empty($bcat['cat_id']))OR($news_page == true))AND($cset['show_side_menu']==1)==true) { 
	$disable_side = false;
	$link_count++;

}
if($cset['show_side_menu_all'] == "1") {
	$disable_side = false;
	$link_count++;
}

/* if((!empty($date['page_under']))OR(($date['date_id'] > 0)AND(countIt("ms_calendar", "WHERE page_under='".$date['date_id']."' ")>0))==true) {
	$disable_side = false;
	$link_count++; 

} */
if($date['page_under'] > 0) { 
	$up_date = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($up_date['date_disable_side'] == "1") { 
	$disable_side = true;
	}
}
if(($page_id=="1")&&(empty($_REQUEST['view']))==true) { 
	$date = doSQL("ms_calendar", "*", "WHERE page_home='1' ");
	$date_feature_auto_populate = $date['date_feature_auto_populate'];
	if($date['page_theme'] > 0) {
		$page_theme = $date['page_theme'];
	}

} 
if((!empty($add_meta_title)) &&(!empty($date['date_title'])) ==true){ 
	$page_title = $date['date_title'];
	if($sub['sub_id'] > 0) { 

		$ids = explode(",",$sub['sub_under_ids']);
		foreach($ids AS $val) { 
			if($val > 0) { 
				$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
				$page_title .= " ".$site_setup['sep_meta_title']." ".$upsub['sub_name']." ";
			}
		}
		
		$page_title .= " ".$site_setup['sep_meta_title']." ".$sub['sub_name'];
	}

	$add_meta_title = $page_title."  ".$site_setup['sep_meta_title']." ".$add_meta_title;
} else { 
	$add_meta_title .= $date['date_title']."";
}
if(!empty($date['page_under'])) {
	$up_page = doSQL("ms_calendar", "date_title, date_id, page_theme", "WHERE date_id='".$date['page_under']."' ");
	$add_meta_title .= " ".$site_setup['sep_meta_title']." ".$up_page['date_title'];
	if($up_page['page_theme'] > 0) {
		$page_theme = $up_page['page_theme'];
	}
}


if($date['date_id'] > 0) { 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		$pic['full_url'] = true;
		$fb_thumb = getimagefile($pic,'pic_pic');
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			$pic['full_url'] = true;
			$fb_thumb =getimagefile($pic,'pic_pic');
		}
	}
}
if(($bcat['cat_id'] > 0)&&($date['date_id'] <=0)==true) { 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_cat='".$bcat['cat_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		$pic['full_url'] = true;
		$fb_thumb = getimagefile($pic,'pic_pic');
	}
}


$add_meta_keys .= "".$date['page_keywords']."";
if($date['date_id'] > 0) { 
	$tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$date['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
	while($tag = mysqli_fetch_array($tags)) { 
		if(!empty($add_meta_keys)) { $add_meta_keys .= ", "; } 
		$add_meta_keys .= $tag['tag_tag'];
	}
}

$add_meta_descr = strip_tags($date['date_text']);
$add_meta_descr = preg_replace('/\s\s+/', ' ', $add_meta_descr);
$add_meta_descr = (substr_replace(strip_tags(trim($add_meta_descr)), "", 200). "");

if(!empty($bcat['cat_password'])) { 
	$password_category = $bcat['cat_id'];
}
if(!empty($main_cat['cat_password'])) { 
	$password_category = $main_cat['cat_id'];
}

if($_REQUEST['view'] == "checkout") { 
	$add_meta_title = _checkout_;
}
if($_REQUEST['view'] == "cart") { 
	$add_meta_title = _view_cart_;
}
if($_REQUEST['view'] == "newaccount") { 
	$add_meta_title = _new_account_page_title_;
}
if($_REQUEST['view'] == "account") { 
	$add_meta_title = _my_account_;
}
if($_REQUEST['view'] == "order") { 
	$add_meta_title = _my_orders_;
}

if($site_setup['meta_site_name_location'] == "0") { 
	if(!empty($add_meta_title)) { 
		$meta_title = $site_setup['meta_title']." ".$site_setup['sep_meta_title']." ".$add_meta_title;
	} else {
		$meta_title = $site_setup['meta_title'];
	}
}
if($site_setup['meta_site_name_location'] == "1") { 
	if(!empty($add_meta_title)) { 
		$meta_title = $add_meta_title." ".$site_setup['sep_meta_title']." ".$site_setup['meta_title'];
	} else {
		$meta_title = $site_setup['meta_title'];
	}
}
if((!empty($_REQUEST['kid'])) && (is_numeric($_REQUEST['kid'])) == true) { 
	$keywordtag = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
	$add_meta_page .= " ".htmlspecialchars($keywordtag['key_word']);
}
if((!empty($_REQUEST['page'])) && (is_numeric($_REQUEST['page'])) == true) { 
	$add_meta_page .= " #".$_REQUEST['page'];
}
?>
<?php if($photo_cart_included !== true) { ?>
<head>
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php 
$com_settings = doSQL("ms_comments_settings", "*", "");
$pic_th = $fbthumb['pic_th'];
$fb_folder=  $fbthumb['listgal_folder'];

if($date['page_home'] == "1") { 
	$url = $setup['url'];
} elseif($date_cat_id > 0){ 
	$url = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$bcat['cat_folder']."/";
} else { 
	$url = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/";
}

$fb_text .= strip_tags($add_meta_descr);
if(!empty($fb_url)) { 
	$url = $fb_url;
}
if(empty($fb_text)) { 
	$fb_text = $site_setup['meta_descr'];
}

if((!empty($bcat['cat_meta_descr'])) && (empty($date['date_meta_descr'])) == true) { 
	$fb_text = $bcat['cat_meta_descr'];
}
trim($date['date_meta_descr']);
if(!empty($date['date_meta_descr'])) {
	$fb_text = $date['date_meta_descr'];
}
?>
<?php if($fb['disable_facebook'] !== "1") { ?>
<meta property="og:title" content="<?php if(!empty($date['date_meta_title'])) { print htmlspecialchars($date['date_meta_title']); } else { print htmlspecialchars($meta_title); }?>">
<meta property="og:type" content="<?php if($date['page_home'] == "1") { print "website"; } else { print "article"; } ?>">
<meta property="og:url" content="<?php print $url;?>">
<?php if(!empty($fb_thumb)) { ?>
<meta property="og:image" content="<?php print $fb_thumb;?>" id="fbthumb">
<?php } ?>
<meta property="og:site_name" content="<?php print "".$site_setup['meta_title'];?>">
<meta property="fb:app_id" content="<?php print $fb['facebook_app_id'];?>"/>
<meta property="og:description"  content="<?php  print trim(htmlspecialchars($fb_text));  ?>" id="sharetext">
 <?php // } ?>
 <?php } ?>
<?php if(!empty($_REQUEST['t'])) { 
$tp = explode("-",$_REQUEST['t']);
if(!is_numeric($tp['0'])) { header("location: /?no=no"); die(); } 

	$topic = doSQL("ms_forum", "*, date_format(DATE_ADD(date, INTERVAL 0 HOUR), '%a %b %d, %y<br>%l:%i %p')  AS date_show, date_format(DATE_ADD(last_edit, INTERVAL 0 HOUR), '%a %b %d, %y %l:%i %p')  AS last_edit_show", "WHERE id='".$tp[0]."' AND deleted='0' ");
	if(!empty($topic['id'])) { 
		$meta_title = $topic['topic']." - ".$meta_title;
		$add_meta_descr = strip_tags($topic['message']);
		$add_meta_descr = preg_replace('/\s\s+/', ' ', $add_meta_descr);
		$add_meta_descr = (substr_replace(strip_tags(trim($add_meta_descr)), "", 200). "");

	}
}


?>
<TITLE><?php if(!empty($date['date_meta_title'])) { print htmlspecialchars($date['date_meta_title']); } else { print htmlspecialchars($meta_title); }?><?php print $add_meta_page;?></TITLE>
<meta http-equiv="Page-Enter" content="blendTrans(duration=0.0)" />
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="<?php if((!empty($bcat['cat_key_words'])) && (empty($date['date_id'])) == true) { print htmlspecialchars($bcat['cat_key_words']); } else { print htmlspecialchars($add_meta_keys); if(!empty($add_meta_keys)) { print ", "; } print htmlspecialchars($site_setup['meta_keys']); } ?> ">
<?php
$add_meta_descr = $add_meta_descr." ".$site_setup['meta_descr'];
$add_meta_descr = (substr_replace(strip_tags(trim($add_meta_descr)), "", 155). "");
if(!empty($add_meta_page)) { 
	$add_meta_page = $add_meta_page." ";
}
?>

<META NAME="Description" CONTENT="<?php print $add_meta_page;?><?php if(!empty($date['date_meta_descr'])) { print htmlspecialchars($date['date_meta_descr']); } elseif((!empty($bcat['cat_meta_descr'])) && (empty($date['date_id'])) == true) {  print htmlspecialchars($bcat['cat_meta_descr']); } else { print htmlspecialchars($add_meta_descr);} ?>">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php 

if(!empty($_SESSION['previewTheme'])) { 
	$css_id = $_SESSION['previewTheme'];
} else {
	if($page_theme > 0) {
		$css_id = $page_theme;	
	} else {
		$css_id = $site_setup['css'];
	}
} 


$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");
if(($css['site_type'] == "1")&&($mobile!==true)&&($ipad!==true)==true) {  
	$and_css .= "&wbg=1"; 
	$site_type = "fullscreen";
}
if(($css['css_id'] <= 0) && ($setup['sytist_hosted'] == true) == true) { 
	$css_id = 1;
	$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='1'");
}
if(countIt("ms_menu_links", "WHERE link_status='1' AND link_location='".$css['side_menu_use']."' ") > 0) { $disable_side = false; 	$link_count++; }

if(($top_section > 0)AND (countIt("ms_blog_categories", "WHERE cat_no_show='0' AND cat_under='".$top_section."' ORDER BY cat_name ASC ") > 0)==true) { $disable_side = false; 	$link_count++; } 


if($date['date_gallery_exclusive'] <= 0) { 
	if(($date['cat_page_billboard'] == "1")&&(empty($_REQUEST['sub'])) == true) { 
		$date['page_bill'] = $date['date_id'];
		$billboard = doSQL("ms_billboards", "*", "WHERE bill_page='1' ");
	}



	if($_REQUEST['previewBillboard'] > 1) { 
		if(!is_numeric($_REQUEST['previewBillboard'])) { header("location: /?no=no"); die(); } 
		  $billboard = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['previewBillboard']."' ");
	} elseif(!empty($date['page_billboard'])) { 
		$billboard = doSQL("ms_billboards", "*", "WHERE bill_id='".$date['page_billboard']."' ");
	} else { 
		if(($bcat['cat_id'] > 0)AND($bcat['cat_billboard'] > 0)==true){ 
			if((($date['date_id'] > 0)&&($bcat['cat_billboard_posts'] == "1")&&($date['page_billboard'] <=0))OR($date['date_id'] <= 0) ==true) { 
			  $billboard = doSQL("ms_billboards", "*", "WHERE bill_id='".$bcat['cat_billboard']."' ");
			}
		}
	}
}
?>
<?php if(($disable_side == true)==true) {$and_css .= "&disable_side=1"; } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width">
<?php if($setup['use_temp_css_file'] == true) { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/sy-style.php?csst=".$css_id."&ipad=$ipad".$and_css."&site_type=$site_type&photo_cart_included=".$photo_cart_included.""; ?>" type="text/css">
<?php } else { ?>
<script type="text/javascript"></script>
<?php if(!empty($_SESSION['previewTheme'])) { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/sy-style.php?csst=".$css_id."&ipad=$ipad".$and_css."&site_type=$site_type&photo_cart_included=".$photo_cart_included.""; ?>" type="text/css">
<?php } else if(empty($css['css_file'])) { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/sy-style.php?csst=".$css_id."&ipad=$ipad".$and_css."&site_type=$site_type&photo_cart_included=".$photo_cart_included.""; ?>" type="text/css">
<?php } else {
if((file_exists($setup['path']."/".$setup['layouts_folder']."/".$css['css_file']))&&(@filesize($setup['path']."/".$setup['layouts_folder']."/".$css['css_file'])>10)==true) {?>
<link rel="stylesheet" type="text/css" href="<?php tempFolder();?><?php print "/".$setup['layouts_folder']."/".$css['css_file']."";?>">
<?php } else { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/sy-style.php?csst=".$css_id."&ipad=$ipad".$and_css."&site_type=$site_type&photo_cart_included=".$photo_cart_included.""; ?>" type="text/css">
<?php } ?>
<?php } ?>
<?php } ?>
<?php if($mobile == "mobile") { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/css/mobile.css?".MD5($site_setup['sytist_version']);?>">
<?php }  ?>
<?php if($ipad == true) { ?>
<?php if(($css['menu_placement'] == "left") || ($css['menu_placement'] == "right")  || ($css['menu_placement'] == "rightleft") == true) { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/css/ipadmenulr.css?".MD5($site_setup['sytist_version']);?>">
<?php } else { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/css/ipad.css?".MD5($site_setup['sytist_version']);?>">
<?php } ?>
<?php }  ?>
<?php if($billboard['bill_id'] > 0) { ?>
<link rel="stylesheet"   type="text/css" media="screen" href="<?php tempFolder();?><?php print "/sy-inc/billboard-style.php?bid=".$billboard['bill_id'];?>&csst=<?php print $css_id;?>&<?php print MD5($site_setup['sytist_version']);?>" type="text/css">
<?php } ?>
<?php if($_SESSION['previewMobile'] == 1) { ?>
<style>#page-wrapper { width: 480px; margin: auto;} </style>
<?php } ?>
<?php if($date['page_fix_width'] > 0) { ?>
<style>#contentUnderMenu { max-width: <?php print $date['page_fix_width'];?>px; margin: auto; } </style>
<?php } ?>
<?php if(($bcat['cat_max_width'] > 0)&&($date['date_id'] <= 0)==true) { ?>
<style>#contentUnderMenu { max-width: <?php print $bcat['cat_max_width'];?>px; margin: auto; } </style>
<?php } ?>
<?php if($_REQUEST['view'] == "cart") { ?>
<style>#contentUnderMenu { max-width: 1024px; margin: auto; } </style>
<?php } ?>

<?php if(!empty($css['css_external'])) { ?>
<link rel="stylesheet" href="<?php print $css['css_external']."?".MD5($site_setup['sytist_version']);?>" type="text/css">
<?php } ?>
<?php if($bcat['cat_type'] == "forum") { ?>
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/forum/forum.css?<?php print MD5($site_setup['sytist_version']); ?>">
<?php } ?>
<?php if(($date['page_home'] == "1") && (!empty($setup['home_css'])) == true) { ?>
<link rel="stylesheet" href="<?php print $setup['home_css'];?>?<?php print MD5($site_setup['sytist_version']); ?>">
<?php } ?>
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/sytist.css?<?php print MD5($site_setup['sytist_version']); ?>">
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/animation.css?<?php print MD5($site_setup['sytist_version']); ?>"><!--[if IE 7]>
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/fontello-ie7.css"><![endif]-->

<!-- <link rel="stylesheet" href="<?php //tempFolder();?>/sy-inc/js/flexslider/css/demo.css?<?php //print MD5($site_setup['sytist_version']); ?>" media="screen"> -->
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/js/flexslider/css/flexslider.css?<?php print MD5($site_setup['sytist_version']); ?>" media="screen">
<!-- <link rel="stylesheet" href="<?php //tempFolder();?>/sy-inc/js/zoom/css/easyzoom.css?<?php //print MD5($site_setup['sytist_version']); ?>" media="screen"> -->

<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='$css_id' ORDER BY font ASC ");
if(mysqli_num_rows($fonts) > 0) { 
	while($font = mysqli_fetch_array($fonts)) { 
		if($f > 0) { 
			$add_fonts .= "|";
		}
		$add_fonts .= str_replace(" ","+",$font['font']);
		$f++;
	}
	?>
<link href='//fonts.googleapis.com/css?family=<?php print $add_fonts;?>&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<?php } ?>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> -->

<script language="javascript"  type="text/javascript" src="<?php tempFolder();?>/sy-inc/js/jquery-1.7.1.min.js"></script>
<script language="javascript" defer  type="text/javascript" src="<?php tempFolder();?>/sy-inc/js/jquery-ui-1.8.18.custom.min.js"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/sytist.js?".MD5($site_setup['sytist_version'])."" ?>d<?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/slideshow.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>

<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/modernizr.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/jquery.flexslider.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/shCore.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/shBrushXml.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/shBrushJScript.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/jquery.easing.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/flexslider/js/jquery.mousewheel.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<!-- <script language="javascript"  type="text/javascript" src="<?php //tempFolder();?><?php //print "/".$setup['inc_folder']."/js/zoom/dist/easyzoom.js?".MD5($site_setup['sytist_version'])."" ?><?php //if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
 --><!-- <script language="javascript"  type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->


<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/gal.js?".MD5($site_setup['sytist_version'])."" ?>a<?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<?php 	if($sytist_store == true) { ?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/store.js?".MD5($site_setup['sytist_version'])."" ?>b<?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<?php } ?>
<?php if($date['date_gallery_exclusive'] == "1") {
?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/gallery.exclusive.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<?php } ?>
<?php if($bcat['cat_type'] == "forum") { ?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/forum/forum.js?".MD5($site_setup['sytist_version'])."" ?>"></script>
<?php } ?>
<?php if($billboard['bill_id'] > 0) { ?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/billboard.js?".MD5($site_setup['sytist_version'])."" ?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<?php } ?>
<?php if($site_setup['disable_right_click'] == "2") { ?><SCRIPT src="<?php tempFolder();?>/sy-inc/js/norightclick.js"></SCRIPT><?php  } ?>
<?php 
if(empty($css['css_id'])) { ?>
<div style="background: #f4f4f4; border: solid 1px 3e4e4e4; box-shadow: 0px 0px 12px #949494; margin: 100px auto; padding: 20px; width: 500px; border-radius: 4px; font-family: arial;">
<h1>Welcome to your new website!</h1><br><br>
You are seeing this because you have not yet selected a theme to use.  In your administration area, go to Site Design -> Themes and select a theme to get started with.
<br><br>Have fun!  ~ <i>sytist</i>
</div>
<?php 
exit();} ?>
<?php print $site_setup['add_head'];?>
<?php
 if(($photo_setup['gallery_favicon'] == "1") && ($date['date_id'] > 0) == true)  { 
	if(file_exists($setup['path']."/sy-photos/favicons/".$date['date_id']."/icon.png")) {
	$gallery_favicon = true;?>
<link rel="apple-touch-icon" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-180.png"> 
<link rel="icon"  type="image/png"  href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder'];?>/favicons/<?php print $date['date_id'];?>/icon-16.png">

	<?php
	}
} 
if((file_exists($setup['path']."/".$setup['misc_folder']."/favicons/icon.png")) && ($gallery_favicon !== true) == true) { ?>
<link rel="apple-touch-icon" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-180.png"> 
<link rel="icon"  type="image/png"  href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-16.png">
<?php } ?>
<?php if(!empty($site_setup['page_template'])) { 
	// this is for google analytics ?>
<script><?php print $site_setup['page_template'];?></script>
<?php } ?>
</head>
<BODY bgcolor="<?php print "#".$css['outside_bg'];?>" >
<?php if(isset($_SESSION['fbjustloggedin'])) { ?>
<script>
$(document).ready(function(){
setTimeout(function() { 
	$("#facebookloggedin").fadeIn(400);
	}, 1000);
setTimeout(function() { 
	$("#facebookloggedin").fadeOut(600);
	}, 5000);
});
</script>
<div id="facebookloggedin"><span class="the-icons icon-facebook"></span><?php print _now_logged_in_facebook_;?> <?php print $_SESSION['fbfn']." ".$_SESSION['fbln'];?></div>
<?php 
unset($_SESSION['fbjustloggedin']);
}

if($list['list_id'] > 0) { 
	if($list['list_products_placement'] == "1") { 
		$ipad = true;
		$list_products_placement = "1";
	}
	$has_package = countIt("ms_cart",  "WHERE ".checkCartSession()." AND (cart_package!='0' OR cart_product_photo!='0') AND cart_package_no_select!='1' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );

	$ocarts = whileSQL("ms_cart", "*",  "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_order<='0'  AND cart_package_no_select!='1' ORDER BY cart_pic_org  ASC" );
	while($ocart = mysqli_fetch_array($ocarts)) { 
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$ocart['cart_package']."' ");
		if($pack['package_select_only'] == "1") { 
			$has_package_one++;
		}
		$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' GROUP BY con_product  ORDER BY con_order ASC ");
		if(mysqli_num_rows($prods)==1) { 
			while($prod = mysqli_fetch_array($prods)) { 
				if(countIt("ms_product_options",  "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ")<=0) { 
					$has_package_one++;
				}
			}
		}
	}
}
if(($list['list_id'] <=0)&&(($date['photo_social_share'] == "1") OR ($date['allow_favs'] == "1"))==true) { 
	$list['list_id'] = "99999999";
}
if($_REQUEST['view'] == "favorites") { 
	$list['list_id'] = "99999999";
}

$list_products_placement = $list['list_products_placement'];

$list['list_products_placement'] = 1;
if($mobile == true) { 
	$list['list_products_placement'] = 0;
}
if($ipad == true) { 
	$view_package_only = 1;
	$list['list_products_placement'] = 0;
}
if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_no_select!='1'  AND cart_package_buy_all='0' AND cart_order<='0'  ORDER BY cart_pic_org  ASC" ) > 0) { 
	$view_package = "1";
} else {
	$view_package = "0";
}
if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_product_photo!='0' AND cart_order<='0' " ) > 0) { 
	$store_product_photo = "1";
} else {
	$store_product_photo = "0";
}

if($date['cat_type'] == "proofing") { 
	$list['list_id'] = "99999999";
	$proofing = 1;
	$list['list_products_placement'] = 0;
}


$list_require_login = 1;
if((!customerLoggedIn()) && ($list['list_id']>0)&&($list['list_require_login'] > 0) == true){ 
	$need_login = $list['list_require_login'];
}
if(!empty($_REQUEST['page'])) { 
	if(!is_numeric($_REQUEST['page'])) { 
		header("location: /?no=no");
		die();
	}
}
if(!empty($_REQUEST['vp'])) { 
	if(!is_numeric($_REQUEST['vp'])) { 
		header("location: /?no=no");
		die();
	}
}
if((!empty($_REQUEST['view']))&&(!ctype_alnum($_REQUEST['view'])) == true) { header("location: ".$setup['temp_url_folder']."/?no=no"); exit(); }

if($date['page_home'] == "1") { 
	if($date['date_feature_cat'] > 0) { 
		$this_cat_id = $date['date_feature_cat'];
	}
} else { 
	$this_cat_id = $bcat['cat_id'];
}
if(!empty($_SESSION['passcode'])) { 
	$_REQUEST['passcode'] = $_SESSION['passcode'];
}
?>
<div id="vinfo" did="<?php print $date['date_id'];?>" sub_id="<?php print $sub['sub_id'];?>" thumbPageID="<?php if(empty($_REQUEST['page'])) { print "1"; } else { print $_REQUEST['page']; } ;?>" keyWord="<?php print sql_safe($_REQUEST['keyWord']);?>" kid="<?php print sql_safe($_REQUEST['kid']);?>" pic_camera_model="<?php print sql_safe($_REQUEST['pic_camera_model']);?>" pic_upload_session="<?php print sql_safe($_REQUEST['pic_upload_session']);?>" untagged="<?php print sql_safe($_REQUEST['untagged']);?>" view="<?php print $_REQUEST['view'];?>"  disableNav="0" currentViewPhoto="" thumbsPerPage="<?php print $thumbs_per_page;?>" totalPhotos="" orderBy="<?php if(empty($_REQUEST['orderBy'])) {  print $photo_setup['def_all_orderby']; } else { print $_REQUEST['orderBy']; } ?>" acdc="<?php if(empty($_REQUEST['acdc'])) {  print $photo_setup['def_all_acdc']; } else { print $_REQUEST['acdc']; } ?>" orientation="<?php print $_REQUEST['orientation'];?>" pic_client="<?php print $_REQUEST['pic_client'];?>" cat_pic_tags="<?php print $bcat['cat_pic_tags'];?>" cat_id="<?php print $this_cat_id;?>" mcat_id="<?php print MD5($bcat['cat_id']);?>" navtype="" plid="<?php print $list['list_id'];?>" prodplace="<?php print $list['list_products_placement'];?>"  prodplacedefault="<?php print $list['list_products_placement'];?>"  has_package="<?php print $has_package;?>" has_package_one="<?php print $has_package_one;?>" view_package="<?php print $view_package;?>"  view_package_only="<?php print $view_package_only;?>"  group-id="0" package-id="0" viewing-prods="0" view-photo-fixed="0" store_product_photo="<?php print $store_product_photo;?>" viewing-store-photo-prod="<?php print $store_product_photo;?>"  product-photo-id="0" need-login="<?php print $need_login;?>" proofing="<?php print $proofing;?>" search_length="<?php print $_REQUEST['search_length'];?>" passcode="<?php print $_REQUEST['passcode'];?>" passcode_did="<?php $_SESSION['passcode_did'];?>" search_date="<?php print $_REQUEST['search_date'];?>" from_time="<?php print $_REQUEST['from_time'];?>" listingpageid="1" page-home="<?php print $date['page_home'];?>" package_thumb_photo="">
<div id="ssheader"></div>
<div id="viewcarttop"><div id="viewcartinner"></div></div>
<div id="buybackground"></div>
<div id="splashbackground"></div><div id="splashcontainer"><div id="splashinner"></div></div>
<div id="storeitembackground"></div><div id="storeitemcontainer"><div id="storeiteminner"></div></div>
<script>cursign = '<?php print $store['currency_sign'];?>'; dec = '<?php print $store['price_decimals'];?>'; pformat = '<?php print $store['price_format'];?>'; tempfolder = '<?php print $setup['temp_url_folder'];?>'; ismobile = '<?php print $mobile; ?>'; istablet = '<?php print $ipad; ?>'; truetablet = '<?php print $isipad;?>'; hmt = 0; lppw = 800;<?php if($setup['do_not_mobile_menu_when_menu_runs_into_header'] == "1") { ?>do_not_mobile_menu_when_menu_runs_into_header = 1;<?php } else { ?> do_not_mobile_menu_when_menu_runs_into_header = 0;
<?php } ?> menup = '<?php print $css['menu_placement'];?>'; var isslideshow; var norightclick = '<?php print $site_setup['disable_right_click'];?>';</script>
<?php
$site_setup['site_password'] = trim($site_setup['site_password']);
if(!empty($site_setup['site_password'])) { 
	if(($_SESSION['sitePasswordAccess']!==true)OR($_SESSION['sitePassword']!==$site_setup['site_password'])==true) { 
		include $setup['path']."/".$setup['inc_folder']."/password_protected_site.php";
		siteLogin();
		exit();
	}
}


if(($css['use_random_bg'] == 1)==true) {

$total = countIt("ms_random_bg", "") - 1;
$rand = rand(0,$total);
if(!empty($date['date_id'])) { 
	$bgpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'  AND pic_width>pic_height  ORDER BY bp_order ASC LIMIT  1 ");
	if(empty($bgpic['pic_id'])) { 
		$bgpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id","*", "WHERE bp_blog='".$date['date_id']."' AND pic_width>pic_height AND pic_width>='1200' ORDER BY bp_order ASC LIMIT 1");
	}
}
if((!empty($bcat['cat_id'])) && (empty($bgpic['pic_id']))==true) { 
	$bgpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id","*", "WHERE bp_cat='".$bcat['cat_id']."' AND pic_width>pic_height AND pic_width>='1200' ORDER BY bp_order ASC LIMIT 1");
}


if(empty($bgpic['pic_id'])) {
	$bgpic = doSQL("ms_random_bg LEFT JOIN ms_photos ON ms_random_bg.bg_pic=ms_photos.pic_id","*", "ORDER BY bg_id ASC LIMIT $rand,1");
}
if($bgpic['pic_id'] > 0) { 

	$pic_file = $bgpic['pic_large'];
	if(empty($bgpic['pic_large'])) {
		$pic_file = $bgpic['pic_full'];
	}
	$bgsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$bgpic['pic_folder']."/".$pic_file.""); 
	$bg_pic_file = "/".$setup['photos_upload_folder']."/".$bgpic['pic_folder']."/".$pic_file."";
	?>
	<script>
	$(document).ready(function(){
		var image = new Image();
		image.onload = function() {
		resizeBg($("#bg-1"),"bgContainer");
		$("#bgContainer").css({"display":"block", "opacity":"0"});
		$("#bgContainer").animate({opacity: 0.50});
		$(window).resize(function() {
			resizeBg($("#bg-1"),"bgContainer");
		}); 
		}
		image.src = "<?php print $bg_pic_file;?>";
	});

	</script>

	<div id="bgContainer"  style="position: fixed; width: 100%; height: 100%; top: 0; left: 0; display: none"><div ><img src="<?php print "$bg_pic_file";?>" id="bg-1" ww="<?php print $bgsize[0];?>" hh="<?php print $bgsize[1];?>"></div></div>
<?php } ?>
<?php } ?>
<?php if(($site_type !== "mobile")&&($ipad !== true)==true) { ?>
<?php if($css['add_bg_overlay']!=="0") { ?>

<?php } ?>

<?php if($css['add_bg_overlay']!=="0") { ?>
<div id="bgFadeContainer" ><div id="bgFade" style="background: transparent; box-shadow: 0px 0px <?php print $css['add_bg_overlay'];?>px 0px rgb(0, 0, 0) inset; width: 100%; height: 100%;" ></div></div>
<?php } ?>
<?php } ?>
<div id="loadingPage"></div>
<?php if(!empty($_SESSION['previewTheme'])) { 
	?>
	<div style="z-index: 10000; position: fixed; bottom: 0; width: 700px; left: 50%; margin-left: -350px; text-align: center; background: #747474; border: solid 1px #545454; color: #FFFFFF;">
		<div style="padding: 4px;">
		Select Theme to Preview: <form method="get" name="themepreview" action="<?php print $_SERVER['PHP_SELF'];?>" style="display: inline;">
		<select name="previewTheme" onchange="this.form.submit()" style="padding: 0px;">
		<?php 
		$pthemes = whileSQL("ms_css", "*", "ORDER BY css_name ASC   ");
		while($ptheme = mysqli_fetch_array($pthemes)) { ?>
		<option value="<?php print $ptheme['css_id'];?>" <?php if($_SESSION['previewTheme'] == $ptheme['css_id']) { print "selected"; } ?>><?php print $ptheme['css_name'];?></option>
		<?php } ?>
		</select>
		</form>	
	<?php print "<a href=\"".$_SERVER['PHP_SELF']."?endPreview=1\" style=\"color: #FFFFFF; text-decoration: underline;\">End & Close Theme Preview</a>";?>
	</div></div>
	<?php 
}
if(!empty($_SESSION['previewMobile'])) { 
	print "<div class=\"specialMessage\" style=\"z-index: 10000; position: fixed; bottom: 0;\">You are viewing mobile site. <a href=\"".$_SERVER['PHP_SELF']."?endMobile=1\">Click here to end mobile preview mode</a></div>";
}
if(!empty($_SESSION['previewIpad'])) { 
	print "<div class=\"specialMessage\" style=\"z-index: 10000; position: fixed; bottom: 0;\">You are viewing iPad site. <a href=\"".$_SERVER['PHP_SELF']."?endIpad=1\">Click here to end iPad preview mode</a></div>";
}

## Add in editor for full screen billboard ## 
if($_REQUEST['previewBillboard'] > 1) { 
	if(!is_numeric($_REQUEST['previewBillboard'])) { header("location: /?no=no"); die(); } 
	  $billboard = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['previewBillboard']."' ");
	  if(($billboard['bill_placement'] == "full") && (isset($_SESSION['office_admin_login'])) && ($_REQUEST['mobileview'] !== "1") == true) {
		require $setup['path']."/".$setup['manage_folder']."/look/billboard-full-edit.php";  
	  }
 }


if($date['date_gallery_exclusive'] <= "0") { 
	 include $setup['path']."/sy-inc/menu_shop.php"; 
}
 ?>
<div id="page-wrapper" st="0" <?php if(($css['change_color_bg'] == "1") OR(($date['bg_use']==1)AND($date['blog_type'] == "nextprevious")) ==true){ print "style=\"background: #".$css['outside_bg'].";\""; } ?>>
<div id="page-wrapper-inner">
 <?php if($site_setup['store_status'] == "1") {
	 if(($_SESSION['office_admin_login']=="1")AND(!empty($_SESSION['office_admin']))==true) {
		print "<div style=\"border:solid 1px #999999; background-color:#545454;  padding: 8px; color: #FFFFFF; font-size: 13px; position: fixed; bottom: 0; left: 0; z-index: 5;\" ><center>** Your website is currently closed.**</center></div>";
	 } else {
		 print "<br><br><br><br><br><br><br><br><br><br><br><br><div><center>".nl2br($site_setup['store_status_message'])."</center></div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		die();
	 }
 }


if((($main_cat['cat_req_login'] == "1")||($bcat['cat_req_login'] == "1"))&&(!customerLoggedIn())==true) { 
	$show_login_form = true;
	
}



// CHECK FOR A BILLBOARD 

if($fb['disable_facebook'] !== "1") { 
	if($fb['use_like_box'] == "1") { ?>
<script>
aspeed = 500;
$(document).ready(function(){
	$("#facebookTabInnerTab").bind('click', function() { showLikeBox() });
});
</script>
<div id="facebookLikeBoxFS" class="hidesmall">
	<div id="likeBoxInner"><?php print faceBookLikeBox();?></div>
	<div id="facebookTabInner">
		<div id="facebookTabInnerTab"><?php print $lang['facebook_tab'];?></div>
	</div>
	<div class="cssClear"></div>
</div>
<?php } ?>
<?php } ?>
 <?php if((countIt("ms_menu_links", "WHERE link_status='1' AND link_location='top' ")>0)OR(countIt("ms_language", "WHERE lang_status='1' ")>1)==true) { ?>
	<div id="menu_slider" style="display: <?php if((countIt("ms_menu_links", "WHERE link_status='1' AND link_location='top' ")>0)OR(countIt("ms_language", "WHERE lang_status='1' ")>1)==true) { print "block"; } else { print " none"; } ?>;">
		<div id="menu_top_container">
			<div id="menu_top">
	<?php 
		$links = whileSQL("ms_menu_links", "*", "WHERE link_status='1' AND link_location='top' ORDER BY link_order ASC ");
		while($link = mysqli_fetch_array($links)) { ?>
		<div id="menu_item"><?php if(!empty($link['link_main'])) { print "<a href=\"/".$setup[$link['link_main']]."/\"  target=\"".$link['link_open']."\">".$link['link_text']."</a>"; } else { print "<a href=\"".$link['link_url']."\" target=\"".$link['link_open']."\">".$link['link_text']."</a>"; }  ?></div>
	<?php } ?>
			</div>
	  </div>
	  <div id="menu_top_spacer">&nbsp;</div>
	  </div>
	<div class="clear"></div>
<?php } ?>
<?php if($css['menu_placement'] == "above") { ?>
<?php include $setup['path']."/".$setup['inc_folder']."/menu_top.php"; ?>
<?php } ?>
<div id="hc" style="width:0; height: 0;"></div>
<div id="gallerysharebg"  data-window="" onclick="closewindowpopup(); return false;" ><div id="accloading"><div class="loadingspinner"></div></div></div>
<?php 
if(empty($date['date_id'])) { 
	// include $setup['path']."/sy-misc/listing.full.php"; 
}
if(($date['date_gallery_exclusive'] > 0) && (!empty($date['date_id'])) == true) { 
	include $setup['path']."/sy-inc/gallery.menu.php"; 
} else { 
?>
<div id="headerAndMenu" class="<?php if($css['mobile_header_height'] > 0) { ?>adjustheight <?php } ?><?php if(($css['menu_placement'] == "left")OR($css['menu_placement'] == "right")OR($css['menu_placement'] == "rightleft")==true)  { ?>hlr headerheight <?php } ?><?php if(($date['date_id'] > 0)&&($date['cat_page_no_header'] == "1")==true) { print "hide"; } ?>">
	<div id="headerAndMenuInner">
		<div id="headerContainer" class="headerheight">
			<div id="header">
				<div class="inner <?php if($site_setup['mobile_header_use'] == "1") { ?>hidesmall<?php } ?>">
				<?php 
				 if(!empty($site_setup['header_ext'])) {
					 include $site_setup['header_ext'];
					} else { 
						if(!empty($css['header_code'])) { print $css['header_code']; } else { 
							if(!empty($bcat['cat_name'])) { 
								$site_setup['header'] = str_replace("[SECTION_NAME]", " | ".$bcat['cat_name'], $site_setup['header']);
							} else { 
								$site_setup['header'] = str_replace("[SECTION_NAME]", "", $site_setup['header']);
							}
							if(($ipad == true)&&($site_setup['ipad_header_use'] == "1")==true) { print $site_setup['ipad_header']; } else { print $site_setup['header']; } 
						} 
						
		}?></div>
			<?php if($site_setup['mobile_header_use'] == "1") { ?>
				<div class="inner showsmall">
				<?php print $site_setup['mobile_header'];?>
				</div>
				<?php } ?>
			</div>
		</div>
		<div id="menucontainerouter">
		<?php include $setup['path']."/".$setup['inc_folder']."/menu_top.php"; ?>
		</div>
<div id="mobilemenubuttontop" onclick="showmobilemenu();" style="float: right; position: absolute; right: 8px;"><span class="the-icons icon-menu"></span><span class="menutext"><?php print _menu_mobile_;?></span></div>

		</div>
	</div>
<?php } ?>
<!-- <div class="cssClear"></div> -->
<?php 
if(empty($setup['menu_mobile_location'])) { 
	if(($date['date_gallery_exclusive'] > 0) && (!empty($date['date_id'])) == true) { 
	} else { 
		if(!empty($setup['custom_mobile_menu'])) { 
			include $setup['custom_mobile_menu'];
		} else { 
			include $setup['path']."/sy-inc/menu_mobile.php"; 
		}
	}
}
?>
<?php 
	if((!empty($billboard['bill_id']))&&(($billboard['bill_placement'] == "belowmenu") || ($billboard['bill_placement'] == "full"))==true) { 
	  if($billboard['bill_slideshow'] == "1") { 
		  if(($billboard['bill_cat'] > 0) || ($billboard['bill_page'] > 0)==true){ 
			include $setup['path']."/sy-inc/billboard_recent.php";
		  } else { 
			include $setup['path']."/sy-inc/billboard.php";
		  }
	  } else { 
		  print "<div id=\"billboardContainer\"><div id=\"billboard\">".$billboard['bill_html']."</div></div>"; 
	  }
	  print "<div class=\"cssClear\"></div>";
	}

 ?>

<div id="main_container">
<div id="contentUnderMenu">
<?php 
/*
if(isset($_REQUEST['landingpage'])) { 
	if(!is_numeric($_REQUEST['landingpage'])) { die(); } 
	$landingpage = doSQL("ms_landing_pages", "*", "WHERE id='".$_REQUEST['landingpage']."' ");
}
if(!empty($landingpage['id'])) { 
	include $setup['path']."/sy-inc/sycontain/sytist-landing-page.php";
}
*/
?>
<?php 
if(empty($_REQUEST['view'])) { 
	if(!empty($_REQUEST['sweetness'])) { 
		$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['sweetness']."'  ");
	} else if($date['date_id'] > 0) { 
		$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$date['date_id']."' AND enabled='1' AND default_feat<='0' ");
	} elseif($bcat['cat_id'] > 0) { 
		$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$bcat['cat_id']."' AND enabled='1' AND default_feat<='0' ");
	}
	if(($sub['sub_id'] > 0)&&($show['show_photos_subs'] == "none")==true) { 
		$no_show = true;
	}
	
	if(($show['show_id'] > 0)&&($no_show !== true)==true) { 
	
	require $setup['path']."/".$setup['inc_folder']."/show/show-functions.php"; ?>
	<link rel="stylesheet" href="<?php tempFolder();?>/<?php print $setup['inc_folder'];?>/show/show-css.php?sid=<?php print md5($show['show_id']);?>&<?php print MD5($site_setup['sytist_version']); ?>">
	<?php $dshow = doSQL("ms_show", "*", "WHERE default_feat='1' "); ?>
	<script>
	var catphotoratio = '<?php print $catphotoratio;?>';
	var mainphotoratio = '<?php print $mainphotoratio;?>';
	var catminwidth = '<?php print $catminwidth; ?>';
	var catmaxwidth = '<?php print $catmaxwidth; ?>';
	var catmaxrow = '<?php print $catmaxrow;?>';
	var initialopacity ='<?php print $show['initialopacity'];?>';
	var hoveropacity = '<?php print $show['hoveropacity'];?>';
	var mslide = 1;
	var main_full_screen = '1';
	var gettingfeature;
	var featid;
	var showminimenu = '<?php print $dshow['show_mini_menu'];?>';
	var logoplacement = '<?php print $dshow['logo_placement'];?>';
	var titleplacement = '<?php print $dshow['title_placement'];?>';
	var navplacement = '<?php print $dshow['nav_placement'];?>';
	var showingsweet = 1;
	</script>
	<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/show/show-js.js?".MD5($site_setup['sytist_version'])."" ?>"></script>
		<?php 
		if((!empty($date['date_id']))&&($date['page_home'] !== "1")==true) { ?>
		<style>
			#headerAndMenu { z-index: 20; display: none; height: 0px; }
			#shopmenucontainer { display: none; height: 0px; } 
		</style>
		<?php require $setup['path']."/".$setup['inc_folder']."/show/show-menu.php"; 
		} 
		?>
		<div id="clfdisplay">
		<?php 
		require $setup['path']."/".$setup['inc_folder']."/show/show.php";
		require $setup['path']."/".$setup['inc_folder']."/show/show-side-menu.php";
		?>
		</div>
		<?php 
	}
}
?>
<div class="clear" id="tmmb"></div>
<?php 
if($show_login_form == true) {  ?>
	<div style="max-width: 400px; margin: auto;">
	<?php 
	$add_create_account = true; 
	require $setup['path']."/sy-inc/store/store_login.php";  ?>

	<?php 
	$password_page = 1;
	include $setup['path']."/sy-footer.php";
	exit();
}

if($date['date_public'] == "3") { 
	include $setup['path']."/".$setup['inc_folder']."/preregister.php";
}


if($show['show_id'] <= 0) { 

	if((!empty($billboard['bill_id']))&&($billboard['bill_placement'] == "insidecontainer")&&($_REQUEST['vp']<=1)==true) { 
	  if($billboard['bill_slideshow'] == "1") { 
		  if(($billboard['bill_cat'] > 0) || ($billboard['bill_page'] > 0)==true){ 
			include $setup['path']."/sy-inc/billboard_recent.php";
		  } else { 
			include $setup['path']."/sy-inc/billboard.php";
		  }
	  } else { 
		  print "<div id=\"billboardContainer\"><div id=\"billboard\">".$billboard['bill_html']."</div></div>"; 
	  }
	  print "<div class=\"cssClear\"></div>";
	}
}

if($setup['menu_mobile_location'] == "inner") { 
	if(!empty($setup['custom_mobile_menu'])) { 
		include $setup['custom_mobile_menu'];
	} else { 
		include $setup['path']."/sy-inc/menu_mobile.php"; 
	}
}


if(countIt("ms_side_menu",  "ORDER BY side_order ASC ")<=0)  { 
	$disable_side = true;
}

if(($top_section > 0)&&(countIt("ms_blog_categories", "WHERE cat_no_show='0' AND cat_under='".$top_section."' ORDER BY cat_name ASC ") > 0)==true) { 
	$disable_side = false;
}
if($_REQUEST['view'] == "checkout") { 
	$disable_side = true;
}
if($_REQUEST['view'] == "checkoutexpress") { 
	$disable_side = true;
}
if($_REQUEST['view'] == "cart") { 
	$disable_side = true;
}
if($_REQUEST['view'] == "newaccount") { 
	$disable_side = true;
}
if($_REQUEST['view'] == "account") { 
	$disable_side = true;
}
if($_REQUEST['view'] == "order") { 
	$disable_side = true;
}

if($date['date_disable_side'] == "1") { 
	$disable_side = true;
}
if(($bcat['cat_disable_side'] == "1")||($main_cat['cat_disable_side'] == "1")==true) { 
	$disable_side = true;
}

if(($css['disable_side'] <=0) &&($disable_side !== true)==true) { ?>
<?php include $setup['path']."/".$setup['inc_folder']."/menu_side.php"; ?>
	<?php } else { ?>
<style>
	#pageContentContainer { width: 100%; margin: 0; } 
</style>
	<?php } ?>
<div id="pageContentContainer">
<?php
if($date['private']>="1"){ 
	if(isset($_SESSION['office_admin_login'])) { 
		print "<div class=\"pc center\"><i>This is a password protected page but since you are logged into the admin you have direct access.</i></div>";
	} else { 
		if(!is_array($_SESSION['privateAccess'])) {
			$_SESSION['privateAccess'] = array();
		}
		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$date['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				include $setup['path']."/".$setup['inc_folder']."/password_protected.php";
				gainAccessToPage($date['date_id']);
				exit();
			} 
		} else { 
			if(!in_array($date['date_id'],$_SESSION['privateAccess'])) {
				include $setup['path']."/".$setup['inc_folder']."/password_protected.php";
				gainAccessToPage($date['date_id']);
				exit();
			}
		}
	}

	if($date['page_under'] > 0) { 
		$up_date = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
		if($up_date['private']>="1"){ 
			if(!is_array($_SESSION['privateAccess'])) {
				$_SESSION['privateAccess'] = array();
			}
			if(!in_array($up_date['date_id'],$_SESSION['privateAccess'])) {
				include $setup['path']."/".$setup['inc_folder']."/password_protected.php";
				gainAccessToPage($up_date['date_id']);
				exit();
			}
		}
	}
}



if($password_category > 0) { 
	if(!is_array($_SESSION['privateCatAccess'])) {
		$_SESSION['privateCatAccess'] = array();
	}
	if(!in_array($password_category,$_SESSION['privateCatAccess'])) {
		$pics_where = "";
		$password_protect = true;
		include $setup['path']."/".$setup['inc_folder']."/password_protected_category.php";
		catPassword($password_category);
		exit();
	}
}
if(customerLoggedIn()==true) { 
	$ck = doSQL("ms_view_page", "*", "WHERE v_page='".$date['date_id']."' AND v_person='".$person['p_id']."' ");
	if(!empty($ck['v_id'])) { 
		updateSQL("ms_view_page", "v_date=NOW() WHERE v_id='".$ck['v_id']."' ");
	} else { 
		insertSQL("ms_view_page", "v_person='".$person['p_id']."', v_page='".$date['date_id']."', v_date=NOW() ");
	}
}

if($sytist_store == true) { 
	if($_REQUEST['view'] == "cart") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_view_cart.php";
	}
	if($_REQUEST['view'] == "checkout") { 
		$stop = checkPackageComplete();
		if($stop['stop'] == true) { 
			if($stop['cart_bonus_coupon'] > 0) { 
				$bc = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE cart_id='".$stop['cart_bonus_coupon']."'  ");	
				if(!empty($_SESSION['last_gallery'])) { 
					$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
					if(!empty($ldate['date_id'])) { 
						if($_SESSION['last_gallery_sub'] > 0) { 
							$lsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
							$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/?sub=".$lsub['sub_link'];
							 } else {
							$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/";
						}  
					}
				}
				$code_not_selected_error = str_replace("[MIN_AMOUNT]",showPrice($bc['code_min']),$bc['code_not_selected_error']);
				$code_not_selected_error = str_replace("[LINK_REMOVE_COUPON]","<a href=\"".$setup['temp_url_folder']. $setup['content_folder']."/sy-inc/store/store_cart_actions.php?action=removebonuscoupon&cp=".MD5($bc['cart_id'])."\">",$code_not_selected_error);
				$code_not_selected_error = str_replace("[LINK_RETURN_GALLERY]","<a href=\"".$l_link."\">",$code_not_selected_error);
				$code_not_selected_error = str_replace("[/LINK]","</a>",$code_not_selected_error);
				print "<div class=\"pc\"><h1>"._checkout_stop_title_."</h1></div>";
				print "<div class=\"pc\">".nl2br($code_not_selected_error) ."</div>";
				$stop = true;


			} else { 

				print "<div class=\"pc\"><h1>"._checkout_stop_title_."</h1></div>";
				print "<div class=\"pc\">"._checkout_stop_package_incomplete_."</div>";
				print "<div>&nbsp;</div>";
				if(!empty($_SESSION['last_gallery'])) { 
					$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
					if(!empty($ldate['date_id'])) { 
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
			}
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";
			print "<div>&nbsp;</div>";


		} else { 
			## Checking for bonus coupon and min. order amount ###
			$bc = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()."  AND cart_coupon>'0' AND code_print_credit>'0' AND cart_order<='0'  ");
			if($bc['cart_id'] > 0) { 
				if($total['total'] < $bc['code_min']) { 


					if(!empty($_SESSION['last_gallery'])) { 
						$ldate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
						if(!empty($ldate['date_id'])) { 
							if($_SESSION['last_gallery_sub'] > 0) { 
								$lsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_SESSION['last_gallery_sub']."' ");
								$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/?sub=".$lsub['sub_link'];
	 							 } else {
								$l_link = $setup['temp_url_folder']. $setup['content_folder'].$ldate['cat_folder']."/".$ldate['date_link']."/";
							}  
						}
					}
					$code_min_amount_error = str_replace("[MIN_AMOUNT]",showPrice($bc['code_min']),$bc['code_min_amount_error']);
					$code_min_amount_error = str_replace("[LINK_REMOVE_COUPON]","<a href=\"".$setup['temp_url_folder']. $setup['content_folder']."/sy-inc/store/store_cart_actions.php?action=removebonuscoupon&cp=".MD5($bc['cart_id'])."\">",$code_min_amount_error);
					$code_min_amount_error = str_replace("[LINK_RETURN_GALLERY]","<a href=\"".$l_link."\">",$code_min_amount_error);
					$code_min_amount_error = str_replace("[/LINK]","</a>",$code_min_amount_error);
					print "<div class=\"pc\"><h1>"._checkout_stop_title_."</h1></div>";
					print "<div class=\"pc\">".nl2br($code_min_amount_error) ."</div>";
					$stop = true;
				}
			}

			if($stop !== true) { 
				include $setup['path']."/".$setup['inc_folder']."/store/store_checkout.php";
			}
		}
	}
	if($_REQUEST['view'] == "checkoutexpress") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_checkout_express.php";
	}
	if($_REQUEST['view'] == "room") { 
		include $setup['path']."/".$setup['inc_folder']."/room-view/room-view.php";
	}

	if($_REQUEST['view'] == "order") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_order.php";
	}
	if($_REQUEST['view'] == "account") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_my_account.php";
	}
	if($_REQUEST['view'] == "newaccount") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_new_account.php";
	}
	if($_REQUEST['view'] == "favorites") { 
		include $setup['path']."/".$setup['inc_folder']."/store/store_favorites.php";
	}
	if($_REQUEST['view'] == "findphotos") { 
		include $setup['path']."/".$setup['inc_folder']."/find_photos.php";
	}
	if($_REQUEST['view'] == "removeem") { 
		include $setup['path']."/".$setup['inc_folder']."/email_form_remove.php";
	}
	if($_REQUEST['view'] == "confirmemail") { 
		include $setup['path']."/".$setup['inc_folder']."/email_form_confirm.php";
	}

}
if($_REQUEST['view'] == "search") { 
	include $setup['path']."/".$setup['inc_folder']."/search.php";
}
if($_REQUEST['view'] == "unsubscribenotices") { 
	$ck = doSQL("ms_people_no_email", "*", "WHERE LOWER(email)='".strtolower($_REQUEST['em'])."' ");
	if(empty($ck['id'])) { 
		insertSQL("ms_people_no_email", "email='".strtolower($_REQUEST['em'])."', date='".currentdatetime()."', ip='".getUserIP()."' ");
	}
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div class=\"pc center\"><h3>".$site_setup['unsubscribe_text']."</h3></div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
	print "<div>&nbsp;</div>";
}


if($_REQUEST['view'] == "logout") { 
	if(!isset($_SESSION['fblogin'])) { 
		include_once($setup['path']."/sy-inc/facebook/config.php");
		$facebook->destroySession();
		unset($_SESSION['userdata']);
		unset($_SESSION['fblogin']);
	}
	unset($_SESSION['pid']);
	unset($_SESSION['loggedin']);
	unset($_SESSION['page_return']);
	unset($_SESSION['my_order_id']);
	unset($_SESSION['ms_session']);
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	SetCookie("persid","",time()-3600,"/",null);
	session_write_close();
	header ("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."");
	exit();
}

?>