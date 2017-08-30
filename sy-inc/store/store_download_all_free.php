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
				<div class="right textright checkoutpagebutton" style="margin-left: 16px; ">
				<?php if($_REQUEST['view'] == "favorites") { 
					$view = "favorites";
				}
				?>
			<div><a href="" onclick="freedownloadall('<?php print $date['date_id'];?>','<?php print $sub['sub_id'];?>','<?php print $view;?>'); return false;" class="icon-download checkoutcart"><?php print _download_all_free_;?></a></div>
			</div>
<?php } ?>
<?php } ?>
<?php } ?>