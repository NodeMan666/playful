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
if((!empty($_REQUEST['date_id'])) && (!is_numeric($_REQUEST['date_id'])) == true) { die(); } 
if((!empty($_REQUEST['sub_id'])) && (!is_numeric($_REQUEST['sub_id'])) == true) { die(); } 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
?>

<div style="padding: 24px;" class="inner">
	<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>
	<div id="gallerylogincontent">
	
<?php 
$freedownload_all = 1;
if(function_exists('zip_open')) { 

	if($date['date_photo_price_list'] > 0) { 
		$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
		$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$date['date_photo_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
	}
	if($sub['sub_price_list'] > 0) { 
		$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$sub['sub_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
		$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$sub['sub_price_list']."' AND pp_free='1' AND pp_free_all='1' ");
	}

		if(customerLoggedIn()) { 
			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			if($person['p_price_list'] > 0) { 
				$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
				$ckfree = countIt("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "WHERE pc_list='".$list['list_id']."' AND pp_free='1' AND pp_free_all='1' ");
				$prod = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*", "WHERE pc_list='".$list['list_id']."' AND pp_free='1' AND pp_free_all='1' ");

			}
		}
		if(customerLoggedIn()) { 
			$gal_free = doSQL("ms_gallery_free", "*", "WHERE free_person='".$person['p_id']."' AND ((free_gallery='".$date['date_id']."' AND free_sub='0') OR (free_gallery='".$date['date_id']."' AND free_sub='".$sub['sub_id']."')) ");
			if($gal_free['free_id'] > 0) {
				$ckfree = 1;
				$prod = doSQL("ms_photo_products", "*","WHERE pp_id='".$gal_free['free_product']."' ");
			}
		}

		if($ckfree > 0) { 

			if($_REQUEST['view'] == "favorites") { 
				$and_where = "";
				$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
				$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
				$pics_orderby = "pic_org";

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
					$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
					$and_date_tag .= " ) ";
					
					## NOT DONE NEW DATABASE FIELDS SELECTION ## 
					$pics_where = "WHERE $and_date_tag $and_where  ";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic";
					$cx = 0;
				} else { 

					$and_where = getSearchString();

					if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
						// Is gallery owner
					} else { 
						$and_where .= " AND pic_hide!='1' ";
					}

					// print "<pre>"; print_r($_REQUEST); 
					if(!empty($sub['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub['sub_id']."' ");
						$and_sub = "AND bp_sub='".$sub['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}
					$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
					$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
				}
			}
			$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id  ");
			$total_images = mysqli_num_rows($pics);
			if($total_images > 0) { 
				$photo_setup = doSQL("ms_photo_setup", "zip_limit", "");
				$zip_max = $photo_setup['zip_limit'];
				if($zip_max <=0) { 
					$zip_max = 20;
				}
				$total_zip = $total_images;
				?>
				<div class="pc"><h3><?php print $prod['pp_name'];?></h3></div>
				<?php if(!empty($prod['pp_descr'])) { ?><div class="pc"><?php print nl2br($prod['pp_descr']);?></div><?php } ?>



	<?php
		if(!empty($_REQUEST['gsbgphoto'])) { 
			if(!ctype_alnum($_REQUEST['gsbgphoto'])) { die("an error has occurred [5]"); }
			$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_REQUEST['gsbgphoto']."' ");
		}

	if(!is_numeric($prod['pp_download_dem'])) { ?>
	<style>
	.demselected { font-weight: bold; text-decoration: underline; } 
	</style>
	<script>
	function changedem(dem) { 
		$(".dem").val(dem);
		$(".selectdem").removeClass("demselected");
		$("#dem"+dem).addClass("demselected");
		$("#downloadzipwait").slideUp(200);
		$("#downloadzip").slideDown(200);

	}
	</script>
	<ul>
	<?php 

		$thedems = explode("\r\n",$prod['pp_download_dem']);
		foreach($thedems AS $dem) { 
			if(!empty($dem)) { 
				$a++;
				$d = explode(",",$dem);
				$ds = $d[0];
				if($ds == "0") { 
					$ds = "org";
				}
				if($a == 1) { 
					$defdem = $ds;
				}
				?>
		<li><a href="" onclick="changedem('<?php print $ds;?>'); return false;" class="selectdem <?php if($a == 1) { print "demselected"; } ?>" id="dem<?php print $ds;?>"><?php print $d[1];?></a></li>
		<?php } 
		}
		?>
		</ul>
<?php	} else { ?>

<?php }

		if($_REQUEST['view'] == "favorites") { 
			$view = "favorites";
		}
		?>
					<div id="dlall">
						<?php if((!customerLoggedIn())&&($prod['pp_free_req_login'] == "1")==true) { ?>
						<div class="pc"><?php

						$message = str_replace('<a href="/index.php?view=account">','<a href="'.$setup['temp_url_folder'].'/index.php?view=account">',_free_download_login_message_);
						$message = str_replace('<a href="/index.php?view=newaccount">','<a href="'.$setup['temp_url_folder'].'/index.php?view=newaccount">',$message);
						print $message ;?></div>

						<?php } else { ?>
						<?php 
						if($total_zip > $zip_max) { 
						$zips = ceil($total_zip / $zip_max);
						$x = 1;
						?>

						<div class="pc"><?php print "$total_zip "._photos_word_photos_." "._in_." $zips "._zip_files_.""; ?></div>
						<div class="pc"><?php print _download_zip_files_text_;?></div>
						<div id="downloadzipwait" class="hide pc"><?php print _downloading_zip_file_;?></div>

						<?php while($x <= $zips) { ?>
						<div class="pc left">
						<form method="post" name="dlzip" action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_download_free_zip.php" onsubmit="hidedownload('<?php print $x;?>');">
						<input type="hidden" name="did" value="<?php print MD5($date['date_id']);?>">
						<input type="hidden" name="sid" value="<?php print MD5($sub['sub_id']);?>">
						<input type="hidden" name="dd" value="<?php print MD5($date['date_date']);?>">
						<input type="hidden" name="de" value="<?php print MD5($date['date_expire']);?>">

						<input type="hidden" name="dem" class="dem" value="<?php print $defdem;?>">
						<input type="hidden" name="view" value="<?php print $view;?>">
						<input type="hidden" name="gs-bgimage-id-free" class="gs-bgimage-id-free" value="">

						<input type="hidden" name="zip_limit" value="<?php print $x;?>">
						<div id="downloadzip-<?php print $x;?>"><input type="submit" name="submit" value="<?php print $x;?>" class="submit" id="submit-<?php print $x;?>"></div>
						</form>
						</div>
						<?php
							$x++;
						} ?>

						<div class="clear"></div>
						<?php
						} else { 

						?>
						<div>
						<div class="pc"><?php print "$total_zip "._photos_word_photos_.""; ?></div>
						<div>&nbsp;</div>

							<form method="post" name="dlzip" action="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_download_free_zip.php" onsubmit="hidedownload('0');">
							<input type="hidden" name="did" value="<?php print MD5($date['date_id']);?>">
							<input type="hidden" name="dd" value="<?php print MD5($date['date_date']);?>">
							<input type="hidden" name="de" value="<?php print MD5($date['date_expire']);?>">
							<input type="hidden" name="sid" value="<?php print MD5($sub['sub_id']);?>">
						<input type="hidden" name="view" value="<?php print $view;?>">
							<input type="hidden" name="dem" class="dem" value="<?php print $defdem;?>">

							<input type="hidden" name="gs-bgimage-id-free" class="gs-bgimage-id-free" value="">
						<div id="downloadzip"><input type="submit" name="submit" value="<?php print _download_free_now_button_;?>" class="submit"></div>
							<div id="downloadzipwait" class="hide"><?php print _downloading_zip_file_;?></div>
							</form>
						</div>
						<div class="clear"></div>
					<?php } ?>


				<?php } ?>
				</div>
				<div>&nbsp;</div>
				</div>
				<script>
				function hidedownload(id) {
					if(id > 0) { 
						$("#submit-"+id).addClass("disabledinput");
						$("#downloadzipwait").slideDown(100);
					} else { 
						$("#downloadzip").slideUp(100);
						$("#downloadzipwait").slideDown(100);
					}
				}

				function showfreeall() { 
					$("#dlall").slideToggle(400);
				}
				</script>
		<?php } ?>
	<?php } ?>
<?php } ?>