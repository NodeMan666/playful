<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-control: private"); 
ob_start(); 
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}
$sytist_store = true;
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
adminsessionCheck();
if($sytist_store == true) { 
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
	$store = doSQL("ms_store_settings", "*", "");
}

if($_REQUEST['action'] == "accountlogin") { 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_REQUEST['pid']."' ");
	if(!empty($p['p_id'])) { 
		$_SESSION['loggedin'] = true;
		$_SESSION['pid'] = MD5($p['p_id']);
		$time=time()+3600*24*365*2;
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		SetCookie("persid",MD5($p['p_id']),$time,"/",null);
		session_write_close();
		header("location: ".$setup['temp_url_folder']."/index.php");
		exit();
	} else { 
		die("Unable to find person");
	}
}

if($_REQUEST['action'] == "viewfullsite") { 
	$_SESSION['full_site'] = true;
	session_write_close();
	header("location: index.php");
	exit();
}
if($_REQUEST['action'] == "noviewfullsite") { 
	unset($_SESSION['full_site']);
	session_write_close();
	header("location: index.php");
	exit();
}

if($_REQUEST['action'] == "searchpeople") { 

	$and_where .= "AND ( p_email LIKE '%".addslashes($_REQUEST['pq'])."%'  OR   p_name LIKE '%".addslashes($_REQUEST['pq'])."%'  OR   p_company LIKE '%".addslashes($_REQUEST['pq'])."%'  OR  p_last_name LIKE '%".addslashes($_REQUEST['pq'])."%'  OR   p_city LIKE '%".addslashes($_REQUEST['pq'])."%'   OR   p_state LIKE '%".addslashes($_REQUEST['pq'])."%'  OR   p_address1 LIKE '%".addslashes($_REQUEST['pq'])."%' )";
	?>
		<select name="<?php print $_REQUEST['field_name'];?>" id="<?php print $_REQUEST['field_name'];?>" class=" formfield"  onchange="setpeopleinfo();">
		<?php $ps = whileSQL("ms_people", "*", " WHERE p_id>'0' $and_where ORDER BY p_last_name ASC "); ?>
		<option value=""><?php print mysqli_num_rows($ps);?> results</option>
		<?php 
		while($p = mysqli_fetch_array($ps)) { ?>
		<option value="<?php print $p['p_id'];?>" <?php if($book['book_account'] == $p['p_id']) { print "selected"; } ?> first_name="<?php print htmlspecialchars($p['p_name']);?>"  last_name="<?php print htmlspecialchars($p['p_last_name']);?>"  email="<?php print htmlspecialchars($p['p_email']);?>"  phone="<?php print htmlspecialchars($p['p_phone']);?>" ><?php print $p['p_last_name'].", ".$p['p_name'];?> (<?php print $p['p_email'];?>)</option>
		<?php } ?>
		</select>
<?php 
}

if($_REQUEST['action'] == "checkemail") { 
	if($_REQUEST['p_id'] > 0) {
		$and_where = "AND p_id!='".$_REQUEST['p_id']."' ";
	}
	$ck = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['p_email']."' $and_where");
	if(!empty($ck['p_id'])) { 
		print "exists";
	}
}
if($_REQUEST['action'] == "checkcode") { 
	if($_REQUEST['code_id'] > 0) {
		$and_where = "AND code_id!='".$_REQUEST['code_id']."' ";
	}
	$ck = doSQL("ms_promo_codes", "*", "WHERE code_code='".$_REQUEST['code_code']."' $and_where");
	if(!empty($ck['code_id'])) { 
		print "exists";
	}
}
if($_REQUEST['action'] == "pccode") { 
	if($_REQUEST['pc_id'] > 0) {
		$and_where = "AND pc_id!='".$_REQUEST['pc_id']."' ";
	}
	$_REQUEST['pc_code'] = trim($_REQUEST['pc_code']);
	$ck = doSQL("ms_print_credits", "*", "WHERE pc_code='".$_REQUEST['pc_code']."' $and_where");
	if(!empty($ck['pc_id'])) { 
		print "exists";
	}
}

if($_REQUEST['action'] == "refreshhomestats") { 
	include "home.php";
 } 

 if($_REQUEST['action'] == "contractTemplate") { 
	$contract = doSQL("ms_contracts","*","WHERE contract_id='".$_REQUEST['contract_id']."' ");
	print $contract['title']."||||".$contract['my_name']."||||".$contract['content'];
	exit();
 } 
 if($_REQUEST['action'] == "signContract") { 
	$contract = doSQL("ms_contracts","*","WHERE contract_id='".$_REQUEST['contract_id']."' ");
	if($contract['contract_id'] <= 0 ) { die("Can not find contract"); } 
	updateSQL("ms_contracts","my_signature='".addslashes(stripslashes(trim($_REQUEST['signature'])))."', my_signed_date='".$_REQUEST['sign_date']."',  my_ip_address='".$_REQUEST['sign_ip']."', my_browser_info='".addslashes(stripslashes(trim($_REQUEST['sign_browser'])))."' WHERE contract_id='".$contract['contract_id']."' ");
	exit();
 } 

if($_REQUEST['action'] == "visitortotaltitle") { 
	$date  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
	$visitors = countIt("ms_stats_site_visitors", "WHERE st_date='$date'");
	$pv = countIt("ms_stats_site_pv", "WHERE pv_date='$date'");
	print $site_setup['website_title']." | Admin ".$visitors." visitors / ".$pv." page views";
}

if($_REQUEST['action'] == "photoedittags") { 
	?>
	<div class="underline">
		<div class="label">Tags: Select from existing tags</div>
		<div class="row" style="max-height: 100px; overflow-y: scroll;">
		<?php 
		$tags = whileSQL("ms_photo_keywords", "*", "ORDER BY key_word ASC ");
		if(mysqli_num_rows($tags) <=0) { print "No tags have been created"; } 
		while($tag = mysqli_fetch_array($tags)) { 
			$cktag = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$tag['id']."' AND key_pic_id='".$_REQUEST['pic_id']."' "); 	?>
		<span id="span-tag-<?php print $tag['id'];?>" class="<?php if(!empty($cktag['id'])) { print "tagselected"; } else { print "tagunselected"; }  ?>"><nobr>
		<input type="checkbox" id="e-tag-<?php print $tag['id'];?>" name="e_tags" value="<?php print $tag['id'];?>" class="editinfo checkbox" <?php if(!empty($cktag['id'])) { print "checked"; } ?> onclick="checkTag('<?php print $tag['id'];?>');"> <label for="e-tag-<?php print $tag['id'];?>"><?php print $tag['key_word'];?></label> </nobr></span>, 
		<?php } ?>

	</div>
<?php } 

if($_REQUEST['action'] == "getphotofile") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$_REQUEST['pic_id']."' ");
	print getimagefile($pic,'pic_large');
}


if($setup['demo_mode'] !== true) { 

	if($_REQUEST['action'] == "mailListUnsubscribe") { 
		$em = doSQL("ms_email_list", "*", "WHERE em_id='".$_REQUEST['em_id']."' ");
		if(!empty($em['em_id'])) { 
			updateSQL("ms_email_list", "em_status='2' WHERE em_id='".$em['em_id']."' ");
			if($em['em_sent_to_mailchimp'] == "1") {
				include $setup['path']."/sy-inc/mail.chimp.functions.php";
				mailchimpunsubscribe($em['em_email'],'','');
			}
		}
		exit();
	}
	if($_REQUEST['action'] == "addToFavs") { 
		if($_REQUEST['sub_id'] > 0) { 
			$and_sub = "AND fav_sub_id='".$_REQUEST['sub_id']."' ";
		}
		$pic = doSQL("ms_photos", "*","WHERE pic_id='".$_REQUEST['pic_id']."'  ");
		if($pic['pic_fav_admin'] == "1") { 
			 updateSQL("ms_photos", "pic_fav_admin='0' WHERE pic_id='".$pic['pic_id']."' ");
			print "removed";
		} else { 
			 updateSQL("ms_photos", "pic_fav_admin='1' WHERE pic_id='".$pic['pic_id']."' ");
			print "added";
		}
		exit();
	}
	if($_REQUEST['action'] == "getFavCount") { 
		$favs = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id  ",  "WHERE pic_fav_admin='1' AND bp_blog='".$_REQUEST['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' ");
		print $favs;
		exit();
	}
	if($_REQUEST['action'] == "mailListDelete") { 
		$em = doSQL("ms_email_list", "*", "WHERE em_id='".$_REQUEST['em_id']."' ");
		if(!empty($em['em_id'])) { 
			deleteSQL("ms_email_list", "WHERE em_id='".$em['em_id']."' ","1");
			if($em['em_sent_to_mailchimp'] == "1") {
				include $setup['path']."/sy-inc/mail.chimp.functions.php";
				mailchimpdelete($em['em_email'],'','');
			}
		}
		exit();
	}

	if($_REQUEST['action'] == "deleteoptout") { 
		$em = doSQL("ms_people_no_email", "*", "WHERE id='".$_REQUEST['id']."' ");
		if(!empty($em['id'])) { 
			deleteSQL("ms_people_no_email", "WHERE id='".$em['id']."' ","1");
		}
		exit();
	}




	if($_REQUEST['action'] == "savelandingpage") { 
		updateSQL("ms_landing_pages", "htmc='".trim(addslashes(stripslashes($_POST['htc'])))."' WHERE id='".$_POST['id']."' ");
		exit();
	}

	if($_REQUEST['action'] == "landingpagegetcode") { 
		$l = doSQL("ms_landing_pages", "*", "WHERE id='".$_REQUEST['id']."' ");
		print $l['htmc'];
		exit();
	}

	if($_REQUEST['action'] == "releasephoto") { 
		$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
		if($order['order_archive_table'] == "1") { 
			define(cart_table,"ms_cart_archive");
		} else { 
			define(cart_table,"ms_cart");
		}

		updateSQL(cart_table, "cart_disable_download='0' WHERE cart_id='".$_REQUEST['cart_id']."' ");
	}

	if($_REQUEST['action'] == "releaseallphotos") { 
		$order = doSQL("ms_orders", "*", "WHERE order_id='".$_REQUEST['order_id']."' ");
		if($order['order_archive_table'] == "1") { 
			define(cart_table,"ms_cart_archive");
		} else { 
			define(cart_table,"ms_cart");
		}

		updateSQL(cart_table, "cart_disable_download='0' WHERE cart_order='".$_REQUEST['order_id']."' ");
	}

	if($_REQUEST['action'] == "enableforum") { 
		updateSQL("ms_history", "forum_feed='1' ");
		$_SESSION['sm'] = "Forum Feed Enabled";
		session_write_close();
		header("location: index.php");
		exit();
	}
	if($_REQUEST['action'] == "disableforum") { 
		updateSQL("ms_history", "forum_feed='0' ");
		$_SESSION['sm'] = "Forum Feed Disabled";
		session_write_close();
		header("location: index.php");
		exit();

	}
	if($_REQUEST['action'] == "homehistory") { 
		$history = doSQL("ms_history", "*", "");
		if($history[$_REQUEST['section']] == "1") { 
			updateSQL("ms_history", "".$_REQUEST['section']."='0' ");
		} else { 
			updateSQL("ms_history", "".$_REQUEST['section']."='1' ");
		}
		exit();

	}


	if($_REQUEST['action'] == "rotate") { 
		foreach($_SESSION['heldPhotos'] AS $photo) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$photo."' ");
			if($_REQUEST['direction'] == "left") { 
				$r = -90;
			}
			if($_REQUEST['direction'] == "right") { 
				$r = 90;
			}
			if(!empty($pic['pic_th'])) { 
				$target = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'];
				$source = imagecreatefromjpeg($target) ;
				$image = imagerotate($source, $r, 0);
				imagejpeg($image,$target,93);
				imagedestroy($source);
				// copy($targetfilepath,$full_name);
			}
			if(!empty($pic['pic_full'])) { 
				$target = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];
				$source = imagecreatefromjpeg($target) ;
				$image = imagerotate($source, $r, 0);
				imagejpeg($image,$target,93);
				imagedestroy($source);
				// copy($targetfilepath,$full_name);
			}
			if(!empty($pic['pic_large'])) { 
				$target = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large'];
				$source = imagecreatefromjpeg($target) ;
				$image = imagerotate($source, $r, 0);
				imagejpeg($image,$target,93);
				imagedestroy($source);
				// copy($targetfilepath,$full_name);
			}
			if(!empty($pic['pic_pic'])) { 
				$target = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'];
				$source = imagecreatefromjpeg($target) ;
				$image = imagerotate($source, $r, 0);
				imagejpeg($image,$target,93);
				imagedestroy($source);
				// copy($targetfilepath,$full_name);
			}
			updateSQL("ms_photos", "pic_width='".$pic['pic_height']."', pic_height='".$pic['pic_width']."', pic_large_width='".$pic['pic_large_height']."', pic_large_height='".$pic['pic_large_width']."', pic_small_width='".$pic['pic_small_height']."', pic_small_height='".$pic['pic_small_width']."' WHERE pic_id='".$pic['pic_id']."' ");



		}
	}

	if($_REQUEST['action'] == "deleteemail") { 
		deleteSQL2("ms_email_logs", "WHERE log_id='".$_REQUEST['log_id']."'  ");
	}

	if($_REQUEST['action'] == "deletecart") { 
		deleteSQL2("ms_cart", "WHERE cart_session='".$_REQUEST['cart_session']."' AND cart_order='0' ");
	}
	if($_REQUEST['action'] == "deletecartclient") { 
		deleteSQL2("ms_cart", "WHERE cart_client='".$_REQUEST['cart_client']."' AND cart_order='0' ");
	}

	if($_REQUEST['action'] == "updatesubgal") { 
		updateSQL("ms_calendar", "hide_sub_gals='".$_REQUEST['hide_sub_gals']."' WHERE date_id='".$_REQUEST['date_id']."' ");
	}


	if($_REQUEST['action'] == "orderLinks") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_menu_links", "link_order = '".($index + 1)."' WHERE link_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderLinksMobile") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_menu_links", "link_mobile_order = '".($index + 1)."' WHERE link_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderFormFields") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_form_fields", "ff_order = '".($index + 1)."' WHERE ff_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderSocialLinks") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_social_links", "link_order = '".($index + 1)."' WHERE link_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderPages") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_calendar", "page_order = '".($index + 1)."' WHERE date_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderCustom") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("custom_data_fig", "display_order = '".($index + 1)."' WHERE fig_id = '$id' ");
		}
	  }
	}


	if($_REQUEST['action'] == "ordersidebar") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_side_menu", "side_order = '".($index + 1)."' WHERE side_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderProds") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_photo_products_connect", "pc_order = '".($index + 1)."' WHERE pc_id = '$id' ");
		}
	  }
	}


	if($_REQUEST['action'] == "orderOptions") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_product_options", "opt_order = '".($index + 1)."' WHERE opt_id = '$id' ");
		}
	  }
	}



	if($_REQUEST['action'] == "orderPackageProducts") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_packages_connect", "con_order = '".($index + 1)."' WHERE con_id = '$id' ");
		}
	  }
	}


	if($_REQUEST['action'] == "orderPackagePhotos") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_blog_photos", "bp_order = '".($index + 1)."' WHERE bp_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderProductPhotos") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_blog_photos", "bp_order = '".($index + 1)."' WHERE bp_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderSubs") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_sub_galleries", "sub_order = '".($index + 1)."' WHERE sub_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderFrameSizes") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_frame_sizes", "frame_order = '".($index + 1)."' WHERE frame_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderFrameStyles") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_frame_styles", "style_order = '".($index + 1)."' WHERE style_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderFrameColors") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_frame_images", "img_order = '".($index + 1)."' WHERE img_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderFrameSizeMatColors") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_frame_mat_colors", "color_order = '".($index + 1)."' WHERE color_id = '$id' ");
		}
	  }
	}
	if($_REQUEST['action'] == "orderRoomPhotos") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_wall_rooms", "room_order = '".($index + 1)."' WHERE room_id = '$id' ");
		}
	  }
	}

	if($_REQUEST['action'] == "orderCanvasPrints") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_canvas_prints", "cp_order = '".($index + 1)."' WHERE cp_id = '$id' ");
		}
	  }
	}


	if($_REQUEST['action'] == "changeOrderNumber") { 
		$last = doSQL("ms_orders", "*", "ORDER BY order_id DESC ");
		if($_REQUEST['new_number'] <= $last['order_id']) { 
			print "fail";
		} else { 
			$sql = stripslashes(addslashes("ALTER TABLE ms_orders AUTO_INCREMENT = ".$_REQUEST['new_number']." "));
			if(mysqli_query($dbcon,$sql)) { print ""; } else { echo(""); }
			print "success";
		}
	}

	if($_REQUEST['action'] == "orderItemsExport") { 
	  $ids = explode(',',$_POST['sort_order']);
	  /* run the update query for each id */
	  foreach($ids as $index=>$id) {
		$id = (int) $id;
		if($id != '') {
			print "<li>$id";
			updateSQL("ms_order_export_items", "display_order = '".($index + 1)."' WHERE id = '$id' ");
		}
	  }
	}


	if($_REQUEST['action'] == "selectmatcolor") { 
		if(empty($_REQUEST['style_id'])) { die(); } 
		updateSQL("ms_frame_styles","style_mat_colors='".$_REQUEST['ids']."' WHERE style_id='".$_REQUEST['style_id']."' ");
		exit();
	}
	if($_REQUEST['action'] == "savewalldesigner") { 
		updateSQL("ms_photo_products_lists","list_wall_designer='".$_REQUEST['list_wall_designer']."' WHERE list_id='".$_REQUEST['list_id']."' ");
		exit();
	}
	if($_REQUEST['action'] == "updatepackageproductqty") { 
		updateSQL("ms_packages_connect", "con_qty='".addslashes(stripslashes(urldecode($_REQUEST['con_qty'])))."' , con_extra_price='".addslashes(stripslashes(urldecode($_REQUEST['con_extra_price'])))."', con_extra_price_new_photo='".addslashes(stripslashes(urldecode($_REQUEST['con_extra_price_new_photo'])))."' WHERE con_id='".$_REQUEST['con_id']."' ");
	}

	if($_REQUEST['action'] == "ordernotes") { 
		updateSQL("ms_orders", "order_admin_notes='".addslashes(stripslashes(urldecode($_REQUEST['order_notes'])))."' WHERE order_id='".$_REQUEST['order_id']."' ");
	}
	if($_REQUEST['action'] == "peoplenotes") { 
		updateSQL("ms_people", "p_notes='".addslashes(stripslashes(urldecode($_REQUEST['p_notes'])))."' WHERE p_id='".$_REQUEST['p_id']."' ");
	}

	if($_REQUEST['action'] == "homenotes") { 
		$notes = str_replace("<br>","{break}", $_REQUEST['notes']);
		$notes = strip_tags($notes);
		$notes = str_replace("{break}","<br>", $notes);


//		print $notes;

		
		$_REQUEST['notes'] = strip_tags($_REQUEST['notes']);
		$_REQUEST['notes'] = nl2br($_REQUEST['notes']);
		updateSQL("ms_settings", "admin_notes='".addslashes(stripslashes(urldecode($notes)))."'  ");
	}

	if($_REQUEST['action'] == "getNewUploadedFile") { 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
		?>
		<a href="" onClick="deleteProductFile('<?php print $date['date_id'];?>'); return false;" class="tip" title="Delete This File"><?php print ai_delete;?></a> <a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['downloads_folder']."/".$date['prod_file'];?>" target="blank"><?php print $date['prod_file'];?></a>
		<?php 
			exit();
	}

	if($_REQUEST['action'] == "getUploadFileSize") { 
		if(!empty($_REQUEST['fn'])) { 
			$size = @GetImageSize("".$setup['path']."/".$_REQUEST['fn']); 
			print $size[0];
		}
		exit();
	}
	if($_REQUEST['action'] == "deleteProductFile") { 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
		if(file_exists($setup['path']."/".$setup['downloads_folder']."/".$date['prod_file'])) { 
			unlink($setup['path']."/".$setup['downloads_folder']."/".$date['prod_file']);
		}
		updateSQL("ms_calendar",  "prod_file='' WHERE date_id='".$date['date_id']."' ");
		print "No file uploaded";
		exit();
	}

	if($_REQUEST['action'] == "makePreviewPhoto")  {
		if($_REQUEST['sub_id'] > 0) { 
			deleteSQL2("ms_blog_photos", "WHERE bp_blog_preview='".$_REQUEST['date_id']."' AND bp_sub_preview='".$_REQUEST['sub_id']."' ");
			insertSQL("ms_blog_photos", "bp_blog_preview='".$_REQUEST['date_id']."', bp_pic='".$_REQUEST['pic_id']."' , bp_sub_preview='".$_REQUEST['sub_id']."'");
		} else { 
			deleteSQL2("ms_blog_photos", "WHERE bp_blog_preview='".$_REQUEST['date_id']."' AND bp_sub_preview<='0'  ");
			insertSQL("ms_blog_photos", "bp_blog_preview='".$_REQUEST['date_id']."', bp_pic='".$_REQUEST['pic_id']."' , bp_sub='".$_REQUEST['sub_id']."'");

			 if($photo_setup['gallery_favicon'] == "1") { 
				$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
				if($date['cat_type'] == "clientphotos") { 
					$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$_REQUEST['pic_id']."' ");
					include $setup['path']."/".$setup['manage_folder']."/photo.process.functions.php";
					createGalleryIcon($date,$pic);
				}				
			 }
		}
	}


	if($_REQUEST['action'] == "addFont") { 
		insertSQL("ms_google_fonts", "theme='".$_REQUEST['css_id']."' , font='".$_REQUEST['font']."' ");
		listFonts();
	}
	if($_REQUEST['action'] == "removeFont") { 
		deleteSQL("ms_google_fonts", "WHERE id='".$_REQUEST['font']."' ", "1");
		listFonts();
	}
	if($_REQUEST['action'] == "updateText") { 

		$field_name = utf8_decode($_POST['field_name']);
		$newtext = $_POST[$field_name];

		// print "".$_POST['table_name']." ,  ".$_POST['field_name']."='".$_POST[$field_name]."' WHERE id='".$_POST['id']."' "; 
		if($_POST['table_name'] == "ms_store_language") { 
			$id_name = "id";
		} else { 
			$id_name = "lang_id";
		}
		updateSQL("".$_POST['table_name']."","".$_POST['field_name']."='".addslashes(stripslashes(urldecode($newtext)))."' WHERE ".$id_name."='".$_POST['id']."' ");
	}



	if($_REQUEST['action'] == "updateWallText") { 
		$field_name = utf8_decode($_POST['field_name']);
		$newtext = $_POST[$field_name];
		updateSQL("".$_POST['table_name']."","".$_POST['field_name']."='".addslashes(stripslashes(urldecode($newtext)))."' ");
	}

	if($_REQUEST['action'] == "updateContractText") { 
		$field_name = utf8_decode($_POST['field_name']);
		$newtext = $_POST[$field_name];
		updateSQL("".$_POST['table_name']."","".$_POST['field_name']."='".addslashes(stripslashes(urldecode($newtext)))."' ");
	}

	if($_REQUEST['action'] == "updateGCText") { 
		$field_name = utf8_decode($_POST['field_name']);
		$newtext = $_POST[$field_name];
		updateSQL("".$_POST['table_name']."","".$_POST['field_name']."='".addslashes(stripslashes(urldecode($newtext)))."' ");
	}

}
if($_REQUEST['action'] == "holdPhoto") { 
	if(!is_array($_SESSION['heldPhotos'])) { 
		$_SESSION['heldPhotos'] = array();
	}
	if(!empty($_REQUEST['pics'])) { 
		$pics = explode(",",$_REQUEST['pics']);
		foreach($pics AS $pic) {
			if(!empty($pic)) { 
				if(is_numeric($pic)) { 
					if(!in_array($pic,$_SESSION['heldPhotos'])) { 
						array_push($_SESSION['heldPhotos'],$pic);
					}
				}
			}
		}
	} else { 
		if(!in_array($_REQUEST['pic_id'],$_SESSION['heldPhotos'])) { 
			array_push($_SESSION['heldPhotos'],$_REQUEST['pic_id']);
		}
	}
	showHeldPhotos();
}

if($_REQUEST['action'] == "unSelectPhoto") { 
	$new_array = array();
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		if($this_pic !== $_REQUEST['pic_id']) { 
			array_push($new_array,$this_pic);
		}
	}
	$_SESSION['heldPhotos'] = $new_array;
	showHeldPhotos();
}
if($_REQUEST['action'] == "clearHeldPhotos") { 
	unset($_SESSION['heldPhotos']);
}

if($_REQUEST['action'] == "showHeldPhotos") {
	showHeldPhotos();
}

if($_REQUEST['action'] == "selectAllFromPage") { 
	if(!is_array($_SESSION['heldPhotos'])) { 
		$_SESSION['heldPhotos'] = array();
	}
	if($_REQUEST['sub_id'] > 0) { 
		$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
	} else { 
		$and_sub = "AND bp_sub<='0' ";
	}
	if($_REQUEST['view'] == "photographerfavs") { 
		if($_REQUEST['sub_id'] > 0) { 
			$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
		} else { 
			unset($and_sub);
		}
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id  LEFT JOIN ms_favs ON ms_photos.pic_id=ms_favs.fav_pic", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' AND pic_fav_admin='1' $and_sub ");
	} else {
		$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub ");
	}
	while($pic = mysqli_fetch_array($pics)) { 
		if($pic['pic_id'] > 0) { 
			if(!in_array($pic['bp_pic'],$_SESSION['heldPhotos'])) { 
				array_push($_SESSION['heldPhotos'],$pic['bp_pic']);
			}
		}
	}
	session_write_close();
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."&view=".$_REQUEST['view']."");
	exit();
}



if($_REQUEST['action'] == "selectAllFromSession") { 
	if(!is_array($_SESSION['heldPhotos'])) { 
		$_SESSION['heldPhotos'] = array();
	}

	$pics = whileSQL("ms_photos", "*", "WHERE pic_upload_session='".$_REQUEST['pic_upload_session']."' ");
	while($pic = mysqli_fetch_array($pics)) { 
		if(!in_array($pic['pic_id'],$_SESSION['heldPhotos'])) { 
			array_push($_SESSION['heldPhotos'],$pic['pic_id']);
		}
	}
	session_write_close();
	header("location: index.php?do=allPhotos&pic_upload_session=".$_REQUEST['pic_upload_session']."&acsc=ASC&pic_client=".$_REQUEST['pic_client']."&did=".$_REQUEST['did']."&sub_id=".$_REQUEST['sub_id']."");
	exit();
}



if($setup['demo_mode'] == true) { 
	if($_REQUEST['action'] == "regenThumbsFromBlog") { 
		$_SESSION['sm'] = "That function disabled in demo, but thumbnails would have been regenerated";
		header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."");
		exit();
	}


	if($_REQUEST['action'] == "addPhotosToBackground") { 
		$_SESSION['sm'] = "That function disabled in demo, but photos would have been added to the random background feature.";
		session_write_close();
		header("location: index.php?do=look&view=randomBg ");
		exit();

	}
	if($_REQUEST['action'] == "regenThumbs") { 
		$_SESSION['sm'] = "That function disabled in demo, but thumbnails would have been regenerated";
		header("location: index.php?do=allPhotos");
		exit();
	}


	die();
}

if($_REQUEST['action'] == "deletePic2") {
	deletePic2();
}

if($_REQUEST['action'] == "updatePhotoBlogOrder") { 
	
  $ids = explode(',',$_POST['sort_order']);
  /* run the update query for each id */
  foreach($ids as $index=>$id) {
    $id = (int) $id;
    if($id != '') {
		print "<li>$id";
		updateSQL("ms_blog_photos", "bp_order = '".($index + 1)."' WHERE bp_id = '$id' ");

    }
  }

		exit();
}
if($_REQUEST['action'] == "deleteque") { 
	$dpics = whileSQL("ms_photos", "*", "WHERE pic_delete='1' ORDER BY pic_id ASC LIMIT 200");
	while($dpic = mysqli_fetch_array($dpics)) { 
		deleteSQL("ms_blog_photos", "WHERE bp_pic='".$dpic['bp_id']."' ", "1");
		// print "<li>".$dpic['pic_id'];
		deleteOnePic($dpic);
	}

?><div class="right pc">Photo delete que: <?php print countIt("ms_photos", "WHERE pic_delete='1' ");?></div><?php
	exit();
}

if($_REQUEST['action'] == "updateclforder") { 
	
  $ids = explode(',',$_POST['sort_order']);
  /* run the update query for each id */
  foreach($ids as $index=>$id) {
    $id = (int) $id;
    if($id != '') {
		print "<li>$id";
		updateSQL("ms_blog_photos", "bp_clf_order = '".($index + 1)."' WHERE bp_id = '$id' ");
    }
  }

		exit();
}

if($_REQUEST['action'] == "removeBlogPhoto") { 
	deleteSQL("ms_blog_photos", "WHERE bp_id='".$_REQUEST['pic_id']."' ", "1");

}
if($_REQUEST['action'] == "defaultPhoto") { 
	updateSQL("ms_settings", "default_photo='".$_REQUEST['pic_id']."' ");
}


if($_REQUEST['action'] == "editBlogLink") {
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
	if($date['date_type'] == "news") { 
		$setup['content_folder'] = $setup['content_folder'];
	}

		$new_link = cleanPageLink($_REQUEST['new_link']);


	if(!empty($date['date_id'])) { 
		$page_link = explode("/",$date['date_link']);
			$fds = count($page_link) - 1;
				//print "<li>$fds <li>$page_link";
				if(!empty($page_link[$fds])) { 
					$link = $page_link[$fds];
					$x = 0;
					while($x < $fds) { 
						$folder .= $page_link[$x]."/";
						$x++;
					}
				} else {
					$link = $page_link[0];
				}
		$folder = $cat['cat_folder'];
		$old = $setup['path']."".$setup['content_folder']."".$folder."/".$date['date_link'];
		$new  =  $setup['path']."".$setup['content_folder']."".$folder."/".$new_link;
	if(is_dir($new)) { 
		print "<span class=\"error\">There is already a folder with that name. Can not rename</span>";
	} else { 
		rename($old,$new);
		updateSQL("ms_calendar", "date_link='".$new_link."' WHERE date_id='".$date['date_id']."' ");
		print " &nbsp; Link has been renamed ";
		print "<a href=\"".$setup['content_folder']."".$folder."/".$new_link."\" target=\"_blank\">view</a>";


	if(countIt("ms_calendar", "WHERE page_under='".$date['date_id']."' ")>0) { 
		$subs = whileSQL("ms_calendar", "*", "WHERE page_under='".$date['date_id']."' ");
		while($sub = mysqli_fetch_array($subs)) { 
			$page_link = explode("/",$sub['date_link']);
			$new  =  $setup['path']."".$setup['content_folder']."/".$new_link."/".$page_link[1];
			updateSQL("ms_calendar", "date_link='".$new_link."/".$page_link[1]."' WHERE date_id='".$sub['date_id']."' ");

		}


	}

	}
	}
	exit();
}



if($_REQUEST['action'] == "deleteDateInline") {
	deleteDateInline();
}




if($_REQUEST['action'] == "deleteHeldPhotos") { 
	$new_array = array();
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		$_REQUEST['pic_id'] = $this_pic;
		if(!empty($this_pic)) { 
			deletePic2();
		}
	}
	unset($_SESSION['heldPhotos']);
}




if($_REQUEST['action'] == "addPhotosToBillboard") { 
	$new_array = array();
	$bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bill_id']."' ");
	$last = doSQL("ms_billboard_slides", "*", "WHERE slide_billboard='".$bill['bill_id']."' ORDER BY slide_order DESC");
	$slast = doSQL("ms_billboard_slides", "*", "ORDER BY slide_id DESC");
	$x = $last['slide_order'];
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		$_REQUEST['pic_id'] = $this_pic;
		if(!empty($this_pic)) { 
			if($_REQUEST['slide_id'] > 0) { 
				$added++;
				updateSQL("ms_billboard_slides", "slide_pic='".$this_pic."' WHERE slide_id='".$_REQUEST['slide_id']."' ");
			} else { 
				$x = $x+2;
				$ck = doSQL("ms_billboard_slides", "*", "WHERE slide_pic='$this_pic' AND slide_billboard='".$bill['bill_id']."' ");
				if(empty($ck['slide_id'])) { 
					$sid = insertSQL("ms_billboard_slides", "slide_pic='$this_pic', slide_billboard='".$bill['bill_id']."', slide_order='$x' ");
					$added++;
					updateNewBillboardSlide($sid,$slast);

				}
			}
		}
	}
	if($_REQUEST['slide_id'] > 0) { 
		$_SESSION['sm'] = "Slide replaced";
	} else { 
		if($added <=0) { 
			$_SESSION['sm'] = "All Photos Already Exist In Billboard";
		} else {
			$_SESSION['sm'] = "$added Photos Added To Billboard";
		}
	}
	unset($_SESSION['heldPhotos']);

	session_write_close();
	header("location: index.php?do=look&action=billboardSlideshow&bill_id=".$bill['bill_id']." ");

	exit();

}




if($_REQUEST['action'] == "addPhotosToBlog") { 
	$new_array = array();
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['blog_id']."' ");
	$last = doSQL("ms_blog_photos", "*", "WHERE bp_blog='".$_REQUEST['blog_id']."' ORDER BY bp_order DESC");
	$x = $last['bp_order'];
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		$_REQUEST['pic_id'] = $this_pic;
		if(!empty($this_pic)) { 
			if($_REQUEST['removefromothers'] == "1") { 
				deleteSQL2("ms_blog_photos","WHERE bp_pic='".$this_pic."' ");
			}

			$x = $x+2;
			$ck = doSQL("ms_blog_photos", "*", "WHERE bp_pic='$this_pic' AND bp_blog='".$_REQUEST['blog_id']."' AND bp_sub='".$_REQUEST['sub_id']."' ");
			if(empty($ck['bp_id'])) { 
				insertSQL("ms_blog_photos", "bp_pic='$this_pic', bp_blog='".$_REQUEST['blog_id']."', bp_order='$x',  bp_sub='".$_REQUEST['sub_id']."'");
				$added++;
			}
		}
	}
	if($added <=0) { 
		$_SESSION['sm'] = "All Photos Already Exist In Blog";
	} else {
		$_SESSION['sm'] = "$added Photos Added To Blog";
	}
	unset($_SESSION['heldPhotos']);

	session_write_close();
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['blog_id']."&sub_id=".$_REQUEST['sub_id']." ");
	exit();

}

if($_REQUEST['action'] == "addPhotosToBackground") { 
	$new_array = array();
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		$_REQUEST['pic_id'] = $this_pic;
		if(!empty($this_pic)) { 
			$x = $x+2;
			$ck = doSQL("ms_random_bg", "*", "WHERE bg_pic='$this_pic' ");
			if(empty($ck['bg_id'])) { 
				insertSQL("ms_random_bg", "bg_pic='$this_pic'");
				$added++;
			}
		}
	}
	if($added <=0) { 
		$_SESSION['sm'] = "All Photos Already Exist In Random Background";
	} else {
		$_SESSION['sm'] = "$added Photos Added To Random Background";
	}
	session_write_close();
	header("location: index.php?do=look&view=randomBg ");
	exit();

}




if($_REQUEST['action'] == "saveInlineData") {
	foreach($_REQUEST AS $id => $value) {
		if(!empty($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
		}
	}

//	print "".$_REQUEST['table']." ,".$_REQUEST['table_field']."='".$_REQUEST['field_value']."' WHERE ".$_REQUEST['table_update_id']."='".$_REQUEST['table_field_id']."' ";


	if($_REQUEST['table_field'] == "pic_keywords") { 
		$add_keys = array();
		$keywords = $_REQUEST['field_value'];
		$exkeys = explode(",",$keywords);
		foreach($exkeys AS $key) { 
			$key = trim(strtolower($key));
			if(!empty($key)) { 
				if(!in_array($key,$add_keys)) { 
					array_push($add_keys,$key);
				}
			}
		}
		asort($add_keys);
		foreach($add_keys AS $key) {
			$sql_key .= "$key,";
			$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($key))."' ");
			if(empty($ck['id'])) { 
				insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($key))."' ");
			}

		}
	$_REQUEST['field_value'] = "$sql_key";
	}

	if($_REQUEST['table_field'] == "pic_bg_color") { 
		$_REQUEST['field_value'] = "#".$_REQUEST['field_value']."";
	}
	if($_REQUEST['table_field'] == "pic_border_in_color") { 
		$_REQUEST['field_value'] = "#".$_REQUEST['field_value']."";
	}
	if($_REQUEST['table_field'] == "pic_border_out_color") { 
		$_REQUEST['field_value'] = "#".$_REQUEST['field_value']."";
	}




	


	updateSQL("".$_REQUEST['table']."","".$_REQUEST['table_field']."='".$_REQUEST['field_value']."' WHERE ".$_REQUEST['table_update_id']."='".$_REQUEST['table_field_id']."' ");
		
	print "<div class=\"successInline\">&nbsp;".ai_check." SAVED&nbsp;&nbsp;</div>";
}

if($_REQUEST['action']=="addKeyWords") {
	$add_keys = array();
	if(!empty($_REQUEST['pic_upload_session'])) { 
		$new_keywords = $_REQUEST['add_keywords_2'];
	} else { 
		$new_keywords = $_REQUEST['add_keywords'];
	}
	$new_keywords = urldecode($new_keywords);
	// print_r($_REQUEST);
	if(empty($new_keywords)) { 
			print "<div class=\"error\">&nbsp;NO TAGS ENTERED&nbsp;&nbsp;</div>";
	} else { 
		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
		}

		$keywords = $new_keywords;
		$exkeys = explode(",",$keywords);
		foreach($exkeys AS $key) { 
			$key = trim(strtolower($key));
			if(!empty($key)) { 
				if(!in_array($key,$add_keys)) { 
					array_push($add_keys,$key);
				}
			}
		}

		if(!empty($_REQUEST['pic_upload_session'])) { 
			$pics = whileSQL("ms_photos", "*", "WHERE pic_upload_session='".$_REQUEST['pic_upload_session']."' ");
			while($pic = mysqli_fetch_array($pics)) { 

				$pic_keys = array();
				$exkeys = explode(",",$pic['pic_keywords']);
				foreach($exkeys AS $key) { 
					$key = trim(strtolower($key));
					if(!empty($key)) { 
						if(!in_array($key,$pic_keys)) { 
							array_push($pic_keys,$key);
						}
					}
				}
				foreach($add_keys AS $new_key) { 
					if(!in_array($new_key,$pic_keys)) { 
						array_push($pic_keys,$new_key);
					}
				}
				asort($pic_keys);
				foreach($pic_keys AS $key) {
					$sql_key .= "$key,";
					$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($key))."' ");
					if(empty($ck['id'])) { 
						$id = insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($key))."' ");
					} else { 
						$id = $ck['id'];
					}
					$ckcon = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$id."' AND key_pic_id='".$pic['pic_id']."' ");
					if(empty($ckcon['id'])) { 
						insertSQL("ms_photo_keywords_connect", "key_key_id='".$id."', key_pic_id='".$pic['pic_id']."' ");
					}

				}
				updateSQL("ms_photos", "pic_keywords='".addslashes(stripslashes($sql_key))."' WHERE pic_id='".$pic['pic_id']."'  ");
				unset($sql_key);
				unset($pic_keys);


			}

		} else { 

			foreach($_SESSION['heldPhotos'] AS $this_pic) { 
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='$this_pic' ");
				$pic_keys = array();
				$exkeys = explode(",",$pic['pic_keywords']);
				foreach($exkeys AS $key) { 
					$key = trim(strtolower($key));
					if(!empty($key)) { 
						if(!in_array($key,$pic_keys)) { 
							array_push($pic_keys,$key);
						}
					}
				}
				foreach($add_keys AS $new_key) { 
					if(!in_array($new_key,$pic_keys)) { 
						array_push($pic_keys,$new_key);
					}
				}
				asort($pic_keys);
				foreach($pic_keys AS $key) {
					$sql_key .= "$key,";
					$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($key))."' ");
					if(empty($ck['id'])) { 
						$id = insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($key))."' ");
					} else { 
						$id = $ck['id'];
					}
					$ckcon = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$id."' AND key_pic_id='".$pic['pic_id']."' ");
					if(empty($ckcon['id'])) { 
						insertSQL("ms_photo_keywords_connect", "key_key_id='".$id."', key_pic_id='".$pic['pic_id']."' ");
					}

				}
				updateSQL("ms_photos", "pic_keywords='".addslashes(stripslashes($sql_key))."' WHERE pic_id='".$pic['pic_id']."'  ");
				unset($sql_key);
				unset($pic_keys);
			}
		}
		print "<div class=\"successInline\">&nbsp;".ai_check." TAGS ADDED&nbsp;&nbsp;</div>";
		exit();
	}
}




if($_REQUEST['action'] == "regenThumbs") { 
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	foreach($_SESSION['heldPhotos'] AS $this_pic) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='$this_pic' ");
		$theImage = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']."";
		$thumb_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']."";
		$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."";
		$thumb_height = $photo_setup['blog_th_height'];
		$thumb_width = $photo_setup['blog_th_width'];
		$thumb_crop = $photo_setup['blog_th_crop'];
		$mini_size = $site_setup['blog_mini_size'];
		regenerateThumbnails($theImage,$thumb_name,$mini_name,$thumb_width,$thumb_height,$thumb_crop,$mini_size);
	}
	
	$_SESSION['sm'] = "Thumbnails & Mini Files have been regenerated";
	session_write_close();
	header("location: index.php?do=allPhotos&keyWord=".$_REQUEST['keyWord']."&orderBy=".$_REQUEST['orderBy']."&acdc=".$_REQUEST['acdc']."&pic_camera_model=".$_REQUEST['pic_camera_model']."&page=".$_REQUEST['page']."");
	exit();
}
if($_REQUEST['action'] == "regenThumbsFromBlog") { 
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	$pics_where = "WHERE bp_blog='".$_REQUEST['date_id']."' ";

	$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id";
	$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_q ORDER BY bp_order ASC $limit ");
		while($pic = mysqli_fetch_array($pics)) { 
	
		$theImage = "".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']."";
		print "<li>$theImage";
		$thumb_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']."";
		$mini_name = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."";
		$thumb_height = $photo_setup['blog_th_height'];
		$thumb_width = $photo_setup['blog_th_width'];
		$thumb_crop = $photo_setup['blog_th_crop'];
		$mini_size = $site_setup['blog_mini_size'];
		regenerateThumbnails($theImage,$thumb_name,$mini_name,$thumb_width,$thumb_height,$thumb_crop,$mini_size);
	}
	
	$_SESSION['sm'] = "Thumbnails & Mini Files have been regenerated";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	exit();
}


if($_REQUEST['action'] == "updatephotoprodprice") { 
	$no_trim = true;
	if($_REQUEST['pp_price'] <= 0) { 
		$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_id='".$_REQUEST['pc_id']."' ");
		$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$ck['pc_prod']."' ");
		print showPrice($prod['pp_price']);
	} else { 
		print "* ".showPrice($_REQUEST['pp_price']);
	}
	updateSQL("ms_photo_products_connect", "pc_price='".$_REQUEST['pp_price']."' WHERE pc_id='".$_REQUEST['pc_id']."' ");

}

if($_REQUEST['action'] == "updateCustomerPriceList") { 
	updateSQL("ms_people", "p_price_list='".$_REQUEST['p_price_list']."' WHERE p_id='".$_REQUEST['p_id']."' ");
}


if($_REQUEST['action'] == "updateproofingoptions") { 
	updateSQL("ms_calendar", "".$_REQUEST['pf']."='".$_REQUEST['f']."' WHERE date_id='".$_REQUEST['date_id']."' ");
	print "done";
	exit();
}

if($_REQUEST['action'] == "refreshupdatecheck") { 
	updateSQL("ms_history", "upgrade_check='' ");
	session_write_close();
	header("location: index.php");
	exit();
}
if($_REQUEST['action'] == "hideupdatecheck") { 
	updateSQL("ms_history", "upgrade_check='' ");
	updateSQL("ms_settings", "auto_check_updates='0' ");
	session_write_close();
	header("location: index.php");
	exit();
}


function regenerateThumbnails($theImage,$thumb_name,$mini_name,$thumb_width,$thumb_height,$thumb_crop,$mini_size) { 
	global $setup, $site_setup, $photo_setup;
	$size_upfull = GetImageSize($theImage,$info); 

	// thumbnail image
	if($thumb_crop == "1") {
		$thumb_height = $thumb_width;
		print "<li>CROP THUMBS?";
		if($size_upfull[0] >= $size_upfull[1]) {
			$div = ($size_upfull[1] / $thumb_height);

			$RESIZEWIDTH=ceil($size_upfull[0] / $div);
			$RESIZEHEIGHT=$thumb_height ;
		} else {
			$div = ($size_upfull[0] / $thumb_width);
			$RESIZEWIDTH=$thumb_width ;
			$RESIZEHEIGHT=ceil($size_upfull[1] / $div);
		}
		if($RESIZEWIDTH<$thumb_width) {

			$add1 = $thumb_width - $RESIZEWIDTH;
	//		print "<li>add 1 : $add1";

		}
		if($RESIZEHEIGHT<$thumb_height) {
			$add2 = $thumb_height - $RESIZEHEIGHT;
		//	print "<li>add 2 : $add2";
			if($add2>$add1) {
				$add = $add2;
			} else {
				$add = $add1;
			}
		}
		if($add > 0) {
//			print "<li>Adding";
			$RESIZEWIDTH = $RESIZEWIDTH + $add;
			$RESIZEHEIGHT = $RESIZEHEIGHT + $add;
		}
	} else {
	//	print "<li>HERE?????";
			$RESIZEWIDTH=$thumb_width;
			$RESIZEHEIGHT=$thumb_height;
	}
	//print "<li>".$RESIZEWIDTH." X ".$RESIZEHEIGHT;

	ResizeImage2("$theImage",$RESIZEWIDTH,$RESIZEHEIGHT,"$thumb_name", $photo_setup, $setup);
/*	print "<li>RESIZEWIDTH ".$RESIZEWIDTH;
	print "<li>RESIZEHEIGHT ".$RESIZEHEIGHT;

	print "<li>theImage: $theImage ";
	print "<li>resize: $thumb_name";
*/
	if($photo_setup['blog_th_crop'] == "1") {
		/* START CROIP */
		$tx = ceil(($RESIZEWIDTH / 2) - ($thumb_width / 2));
		$ty = ceil(($RESIZEHEIGHT / 2) - ($thumb_height / 2));
		if($tx< 0) { $tx = 0;}
		if($ty< 0) { $ty = 0;}
		//print "<li>tx: $tx";
		//print "<li>ty: $ty";
		$img = imagecreatetruecolor($thumb_width,$thumb_height);
		$org_img = imagecreatefromjpeg("$thumb_name");
		//print "<li>org img: $org_img";
		//$ims = getimagesize($new_thumb);
		if($ty > 25) { $ty_pos = $ty - 25;} else { $ty_pos = $ty; } 
		imagecopy($img,$org_img, 0, 0, $tx, $ty_pos, $thumb_width, $thumb_height);
		imagejpeg($img,"$thumb_name",93);
		imagedestroy($img);
		/* END CROP */
	$_SESSION['ty'] = $ty;
	}




/* MINI IMAGE */
	// thumbnail image
	$_REQUEST['crop_mini'] = "1";

	if($_REQUEST['crop_mini'] == "1") {
		if($size_upfull[0] >= $size_upfull[1]) {
			$div = ($size_upfull[1] / $mini_size);

			$RESIZEWIDTH=ceil($size_upfull[0] / $div);
			$RESIZEHEIGHT=$mini_size ;
		} else {
			$div = ($size_upfull[0] / $mini_size);
			$RESIZEWIDTH=$mini_size ;
			$RESIZEHEIGHT=ceil($size_upfull[1] / $div);
		}
		if($RESIZEWIDTH<$mini_size) {
			$add1 = $mini_size - $RESIZEWIDTH;
		}
		if($RESIZEHEIGHT<$mini_size) {
			$add2 = $mini_size - $RESIZEHEIGHT;
			if($add2>$add1) {
				$add = $add2;
			} else {
				$add = $add1;
			}
		}
		if($add > 0) {
			print "<li>Adding";
			$RESIZEWIDTH = $RESIZEWIDTH + $add;
			$RESIZEHEIGHT = $RESIZEHEIGHT + $add;
		}
	} else {
			$RESIZEWIDTH=$mini_size;
			$RESIZEHEIGHT=$mini_size;
	}
	ResizeImage2("$theImage",$RESIZEWIDTH,$RESIZEHEIGHT,"$mini_name", $photo_setup, $setup);

	if($_REQUEST['crop_mini'] == "1") {
		/* START CROIP */
		$tx = ceil(($RESIZEWIDTH / 2) - ($mini_size / 2));
		$ty = ceil(($RESIZEHEIGHT / 2) - ($mini_size / 2));
		if($tx< 0) { $tx = 0;}
		if($ty< 0) { $ty = 0;}
		//print "<li>tx: $tx";
		//print "<li>ty: $ty";
		$img = imagecreatetruecolor($mini_size,$mini_size);
		$org_img = imagecreatefromjpeg("$mini_name");
		//print "<li>org img: $org_img";
		//$ims = getimagesize($new_thumb);
		if($ty > 10) { $ty_pos = $ty - 10;} else { $ty_pos = $ty; } 
		imagecopy($img,$org_img, 0, 0, $tx, $ty_pos, $mini_size, $mini_size);
		imagejpeg($img,"$mini_name",93);
		imagedestroy($img);
		/* END CROP */
	$_SESSION['ty'] = $ty;
	}
		print "<img src=\"/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']."\">";
		print "<img src=\"/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."\">";
	}





function ResizeImage2($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup) {
	$imagex = imagecreatefromjpeg("$imagex"); 
	$width = imagesx($imagex);
	$height = imagesy($imagex);
	if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
		if($maxwidth && $width > $maxwidth){
			$widthratio = $maxwidth/$width;
			$RESIZEWIDTH=true;
		}
		if($maxheight && $height > $maxheight){
			$heightratio = $maxheight/$height;
			$RESIZEHEIGHT=true;
		}
		if($RESIZEWIDTH && $RESIZEHEIGHT){
			if($widthratio < $heightratio){
				$ratio = $widthratio;
			}else{
				$ratio = $heightratio;
			}
		}elseif($RESIZEWIDTH){
			$ratio = $widthratio;
		}elseif($RESIZEHEIGHT){
			$ratio = $heightratio;
		}
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageJpeg ($newim,$name . "$image_ext", 93);
		ImageDestroy ($newim);
	}else{
		ImageJpeg ($imagex,$name . "$image_ext", 93);
	}
	ImageDestroy ($imagex);
}


?>
