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
$lang = doSQL("ms_language", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
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

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
?>
<?php 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if(empty($date['date_photo_keywords'])) { 
	$and_gallery = "AND bp_blog='".$date['date_id']."' ";
}
$pic = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE pic_key='".$_REQUEST['pid']."' $and_gallery");
if($pic['pic_org'] =="") { 
	die("There is no file available to create a download");
}
$size = getimagefiledems($pic,'pic_pic');
if($date['date_photo_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
}

if($_REQUEST['sub_id']> 0) { 
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
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}

if($pic['bp_pl'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$pic['bp_pl']."' ");
}
if($list['list_id'] <= 0) { 
	die("No download available");
}
$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
if($freedownload['pp_id'] > 0) { 
	$free = $freedownload['pp_id'];
} else { 
	$gal_free = doSQL("ms_gallery_free", "*", "WHERE free_person='".$person['p_id']."' AND ((free_gallery='".$date['date_id']."' AND free_sub='0') OR (free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."')) ");
	if($gal_free['free_id'] > 0) {
		$freedownload = doSQL("ms_photo_products", "*","WHERE pp_id='".$gal_free['free_product']."' ");
	} else { 
		die("No download available");
	}
}

$free_pic = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['free_id']."' ");
$size = getimagefiledems($pic,'pic_th');

?>
<script>
$(document).ready(new function() {
	selectGSbackground('freepreview',false);
});
</script>
<div style="padding: 24px;" class="inner">
	<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
	<div id="gallerylogincontent">
		<div class="pc"><h3><?php print $free_pic['pp_name'];?></h3></div>
		<?php if((!customerLoggedIn())&&($free_pic['pp_free_req_login'] == "1")==true) { ?>
		<div class="pc"><?php

		$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_free_download_login_message_);
		$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
		print $message ;?></div>

		<?php } else { 
		
		if($free_pic['pp_free_limit'] > 0) { 
			if(customerLoggedIn()) { 
				$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
				$dls = countIt("ms_free_downloads", "WHERE free_date_id='".$date['date_id']."' AND free_person='".$person['p_id']."' OR free_ip='".getUserIP()."' ");
			} else { 
				$dls = countIt("ms_free_downloads", "WHERE free_date_id='".$date['date_id']."' AND free_ip='".getUserIP()."' ");
			}
			if($dls >= $free_pic['pp_free_limit']) { 
				$stop = true;
			}
			?>
		<?php } ?>

		<?php if($stop == true) { ?>
		<div class="pc">You have reached the maximum free downloads from this gallery.</div>
		<?php } else { ?>

	<div class="pc"><?php print nl2br($free_pic['pp_descr']);?></div>

	<div class="">
	<?php
	if(!is_numeric($freedownload['pp_download_dem'])) { ?>
	<div class="stylelist">
		<div class="inner">
		<ul>
	<?php 
		if(!empty($_REQUEST['gsbgphoto'])) { 
			if(!ctype_alnum($_REQUEST['gsbgphoto'])) { die("an error has occurred [5]"); }
			$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_REQUEST['gsbgphoto']."' ");
		}

		$thedems = explode("\r\n",$freedownload['pp_download_dem']);
		foreach($thedems AS $dem) { 
			if(!empty($dem)) { 
				$a++;
				$d = explode(",",$dem);
				$ds = $d[0];
				if($ds == "0") { 
					$ds = "org";
				}
				if($pic['pic_width'] > 0) { 
					if($pic['pic_width'] < $pic['pic_height']) { 
						$percent = $pic['pic_width'] / $pic['pic_height'];
						$largest = $pic['pic_height'];
					} else { 
						$percent = $pic['pic_height'] / $pic['pic_width'];
						$largest = $pic['pic_width'];
					}
				}

				if(($largest >= $ds) || ($ds == "org") == true) { 
				?><li><a href="<?php print $setup['temp_url_folder'];?>/sy-inc/store/freedownload.php?p=<?php print $pic['pic_key'];?>&fp=<?php print MD5($free_pic['pp_id']);?>&did=<?php print MD5($date['date_id']);?>&dem=<?php print $ds;?>&gsbgphoto=<?php print $bgphoto['pic_key'];?>"><span class="the-icons icon-download"></span><?php print $d[1];?> &nbsp; 
				(<?php if($ds == "org") {
				print $pic['pic_width']." X ".$pic['pic_height']."px"; } else { if($pic['pic_width'] > $pic['pic_height']) { print $ds." X ".round($percent * $ds)."px";} else { print round($percent * $ds)." X ".$ds."px "; } } ?>)</a></li>
				<?php }
			}
				}
		?>
		</ul>
		</div></div>
	<?php } else { ?>

	<?php ########## REGULAR ############ ?>

	<div>&nbsp;</div>
	<div class="center">
	<form method="post" name="fd" action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/freedownload.php">
	<input type="hidden" name="p" value="<?php print $pic['pic_key'];?>">
	<input type="hidden" name="fp" value="<?php print MD5($free_pic['pp_id']);?>">
	<input type="hidden" name="did" value="<?php print MD5($date['date_id']);?>">
	<input type="hidden" name="did" value="<?php print MD5($date['date_id']);?>">
	<?php
	if(!empty($_REQUEST['gsbgphoto'])) { 
		if(!ctype_alnum($_REQUEST['gsbgphoto'])) { die("an error has occurred [5]"); }
		$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_REQUEST['gsbgphoto']."' ");
	?>
		<input type="hidden" name="gsbgphoto" value="<?php print $bgphoto['pic_key'];?>">
	<?php } ?>
	<input type="submit" name="submit" class="submit" value="<?php print _download_free_now_button_;?>" style="font-size: 24px;">
	</form>
	</div>
	<?php } ?>
	</div>
	<?php } ?>
	<?php } ?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	</div>
</div>
<?php  mysqli_close($dbcon); ?>
