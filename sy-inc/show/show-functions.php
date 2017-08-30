<?php
function showHomeFeature($mdate,$tmd,$slide) { 
	global $setup,$site_setup,$show,$dshow;
	if($slide > 0) { 
		$fphoto = doSQL("ms_photos", "*", "WHERE pic_id='".$slide."' ");
		$fdate = doSQL("ms_calendar", "*", "WHERE date_id='".$mdate."' ");
		if($fdate['date_photo_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$fdate['date_photo_price_list']."' ");
		}
		if($list['list_id'] > 0) { 
			$freedownload = doSQL("ms_photo_products LEFT JOIN ms_photo_products_connect ON ms_photo_products.pp_id=ms_photo_products_connect.pc_prod", "*","WHERE pc_list='".$list['list_id']."' AND pp_free='1' ORDER BY pc_order ASC ");
			if($freedownload['pp_id'] > 0) { 
				$free = $freedownload['pp_id'];
			}
		}

	} else { 

		$date = doSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE date_id='".$mdate."' ");
		$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   $and_sub ORDER BY bp_order ASC LIMIT  1 ");
	}

	
	$pic_file_select = "pic_large";
	if(empty($fphoto['pic_id'])) {
		$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");
	}
	if(!empty($fphoto['pic_id'])) { 
		$dsize = getimagefiledems($fphoto,'pic_large');
	}
		?>

		<div id="main-<?php print $tmd;?>"  mslide="<?php print $tmd;?>" data-picid="<?php print $fphoto['pic_key'];?>" data-pl="<?php print $list['list_id'];?>" data-free="<?php print $free;?>" data-did="<?php print $fdate['date_id'];?>" class="hide maincontainer nofloatsmallleft">
				<div class="mainphotobg" id="mainphotobg-<?php print $tmd;?>" style="position: absolute; left: 0; display: block; background: url('<?php print getimagefile($fphoto,'pic_large');?>') no-repeat center center fixed; background-size: cover;"></div><div class="mainphotobggrid"></div>

			<div class="maincontainerinner" <?php if(!empty($date['date_link'])) { ?>onclick="window.location.href='<?php print $setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/".$date['list_sub_link'];?>'" style="cursor: pointer;"<?php } ?>>
				<div class="mainphoto" id="mainphoto-<?php print $fphoto['pic_id'];?>" >
					<?php if(!empty($fphoto['pic_id'])) { ?>
					<?php if(!empty($date['date_link'])) { ?><a href="<?php print $setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/".$date['list_sub_link'];?>"><?php } ?>

					<!-- <a href="" onclick="clickthumbnail('<?php print $fphoto['pic_key'];?>'); return false;"> -->
					<img id="mp-<?php print $fphoto['pic_key'];?>" data-picid="<?php print $fphoto['pic_key'];?>" src="<?php print getimagefile($fphoto,'pic_large');?>"  ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="homephotos swipe" alt="<?php print htmlspecialchars($date['date_title'])." ".htmlspecialchars($date['page_keywords'])." ".htmlspecialchars($fphoto['pic_keywords']);?>"><?php if(!empty($date['date_link'])) { ?></a><?php } ?>
					<?php } ?>
					<?php if(!empty($date['date_id'])) { ?>
							<div class="mainphotoheadlinetext">
								<div class="mainphotoheadline"><?php print $date['date_title'];?></div>
								<?php if($dshow['show_preview_text'] == "1") {
									?><div class="mainphotopreviewtext">	
									<?php 
									if(!empty($date['date_snippet'])) { 
										$snip = $date['date_snippet'];
									} else { 
										$snip = strip_tags($date['date_text']);
									}
			
									if($dshow['side_snippet_length'] > 0) { 

										if(strlen($snip) > $dshow['side_snippet_length']) { 
											$sub_descr = strip_tags($snip);
											$sub_descr = preg_replace('/\s\s+/', ' ', $sub_descr);
											$sub_descr = (substr_replace(strip_tags(trim($sub_descr)), "", $dshow['side_snippet_length']). "");
											$sub_descr = str_replace('"',"",$sub_descr);
											$sub_descr = str_replace('&nbsp;'," ",$sub_descr);
											$sub_descr = trim($sub_descr);
											print $sub_descr."......";
										} else { 
											$snippet = "".strip_tags($snip).""; 
											$snippet = preg_replace('/\s\s+/', ' ', $snippet);
											$snippet = str_replace('&nbsp;'," ",$snippet);
											print  trim($snippet);
										}
									} else { 
										$snippet = "".strip_tags($snip).""; 
										$snippet = preg_replace('/\s\s+/', ' ', $snippet);
										$snippet = str_replace('&nbsp;'," ",$snippet);
										print  trim($snippet);
									}
			
									?>
									</div>
									<?php 
		}
			?>
							
							</div>
				<?php } ?>
			</div>
		</div>

		</div>
		<div class="clear">&nbsp;</div>
<?php }
	
function getfeatureddates($show) { 
	global $setup,$site_setup;
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s');
	if($show['feat_main_cats'] == "999999999") { 
		$and_where .= "AND date_cat>'0' ";
	} else { 
		$show['feat_main_cats'] = str_replace("Array","",$show['feat_main_cats']);
		$fats = explode(",",$show['feat_main_cats']);
		if(count($fats) > 0) { 
			$and_where .= "AND (";
			foreach($fats AS $fat) { 
				$fbcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$fat' ");
				if(!empty($fbcat['cat_id'])) {
					$count ++;
					if($count > 1) { 
						$and_where .= " OR ";
					}

					$and_where .=  " date_cat='".$fbcat['cat_id']."'  ";
				}
			}
			$and_where .= ")";
		}
	}
	if($show['feat_main_limit'] > 0) { 
		$limit = "LIMIT ".$show['feat_main_limit']." ";
	}
	$featured_dates = array();
	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'  AND date_cat!='0'  AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') $and_where ORDER BY date_date DESC, date_time DESC $limit ");
	while($date = mysqli_fetch_array($dates)) { 
		array_push($featured_dates,$date['date_id']);
	}
	return $featured_dates;
}

function getfeaturedslides($date_id,$show,$sub_id) { 
	global $setup,$site_setup;

	$featured_pics = array();
	if(($show['show_photos'] == "all")||(empty($show['show_photos'])) == true) { 
		$pics_where = "WHERE bp_blog='".$date_id."' ";
		$order_by = "ORDER BY bp_order ASC ";
	}
	if($show['show_photos'] == "selected")  { 
		$pics_where = "WHERE bp_blog='".$date_id."' AND bp_clf='".$show['show_id']."' ";
		$order_by = "ORDER BY bp_clf_order ASC ";
	}
	if($show['show_photos'] == "random") { 
		$pics_where = "WHERE bp_blog='".$date_id."' ";
		$order_by = "ORDER BY RAND()";
	}
	if(!is_numeric($sub_id)) { $sub_id = ""; } 
	if($sub_id > 0) { 
		if($show['show_photos_subs'] == "all") { 
			$pics_where = "WHERE bp_blog='".$date_id."' AND bp_sub='".$sub_id."' ";
			$order_by = "ORDER BY bp_order ASC ";
		}
		if($show['show_photos_subs'] == "random") { 
			$pics_where = "WHERE bp_blog='".$date_id."' AND bp_sub='".$sub_id."' ";
			$order_by = "ORDER BY RAND()  LIMIT 20";
		}

	}


	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id $order_by ");
	while($pic = mysqli_fetch_array($pics)) { 
		array_push($featured_pics,$pic['pic_id']);
	}
	return $featured_pics;
}


function showsubgalshome($date_id) { 
	global $setup,$site_setup,$dshow;
	?>
		<script>
		$(document).ready(function(){

			$(".featsidea a").hover(function() { 
				$(this).parent().addClass("featsidehover");
			},
				function() { 

				$(this).parent().removeClass("featsidehover");
			});
		});

		</script>

		<div >
		<ul class="featside">




	<?php 
	$ssubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date_id."' AND sub_under='0' ORDER BY sub_order ASC, sub_name ASC ");
	while($ssub = mysqli_fetch_array($ssubs)) { 
		?>


		<li class="featsidea" id="li<?php print $ssub['sub_id'];?>"><a href="?sub=<?php print $ssub['sub_link'];?>" class="">
	
		<?php if($dshow['side_title_placement'] == "above") { ?><?php if(!empty($ssub['sub_pass'])) { ?><span class="the-icons icon-lock"></span><?php } ?><h3><?php print $ssub['sub_name'];?></h3><?php } ?>
	
		<div style=" overflow: hidden; margin-right: <?php print $dshow['sm_padding'];?>px; <?php if($dshow['side_menu_photo_width'] <= "90") { print "float: left; width: ".$dshow['side_menu_photo_width']."%;"; } ?>" class="featsidemenuitem">
		<?php 

		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_sub_preview='".$ssub['sub_id']."' ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) { 
			$size = getimagefiledems($pic,$dshow['side_menu_photo_file']);
			print "<img src='".getimagefile($pic,$dshow['side_menu_photo_file'])."'  ww=\"".$size[0]."\" hh=\"".$size[1]."\" border='0' class=\"homephotos\" id=\"sp-".$pic['pic_id']."\">";
		} else { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date_id."' AND bp_sub='".$ssub['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
			$size = getimagefiledems($pic,$dshow['side_menu_photo_file']);
			print "<img src='".getimagefile($pic,$dshow['side_menu_photo_file'])."'  ww=\"".$size[0]."\" hh=\"".$size[1]."\" border='0' class=\"homephotos\" id=\"sp-".$pic['pic_id']."\">";
		}



		?>
		</div>
		<?php if($dshow['side_title_placement'] == "below") { ?><?php if(!empty($ssub['sub_pass'])) { ?><span class="the-icons icon-lock"></span><?php } ?><h3><?php print $ssub['sub_name'];?></h3></h3><?php } ?>



		<div class="clear"></div>
		</a>
		</li>

		<?php  } ?>
		</ul>
		</div>


<?php 
}


function showrecentitemshome() {
	global $setup, $site_setup,$css,$show,$dshow;

	$per_page = 10;
	if(empty($_REQUEST['sp'])) { 
		$page = 1;
	} else {
		if(!is_numeric($_REQUEST['sp'])) { die(); } 

		$page = $_REQUEST['sp'];
	}
	$sq_page = $page * $per_page - $per_page;


	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);
	$show['feat_side_cats'] = str_replace("Array","",$show['feat_side_cats']);

	if(!empty($show['feat_side_cats'])) { 
		if($show['feat_side_cats'] == "999999999") { 
			$and_where .= "AND date_cat>'0' ";
		} else { 
			$fats = explode(",",$show['feat_side_cats']);
			if(count($fats) > 0) { 
				$and_where .= "AND (";
				foreach($fats AS $fat) { 
					$fbcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$fat' ");
					if(!empty($fbcat['cat_id'])) {
						$count ++;
						if($count > 1) { 
							$and_where .= " OR ";
						}

						$and_where .=  " date_cat='".$fbcat['cat_id']."'  ";
					}
				}
				$and_where .= ")";
			}
		}

		$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'  AND date_cat!='0'  AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') $and_where ORDER BY date_date DESC, date_time DESC   LIMIT $sq_page,$per_page ");
		
		?>
		<script>
		$(document).ready(function(){

			$(".featsidea a").hover(function() { 
				$(this).parent().addClass("featsidehover");
			},
				function() { 

				$(this).parent().removeClass("featsidehover");
			});
		});

		</script>

		<div >
		<ul class="featside">
		<?php 
		while($date = mysqli_fetch_array($dates)) { 
		$sn++;

		?>
		<li class="featsidea" id="li<?php print $date['date_id'];?>"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/" class="">
	
		<?php if($dshow['side_title_placement'] == "above") { ?><?php if($date['private'] > 0) { ?><span class="the-icons icon-lock"></span><?php } ?><h3><?php if($dshow['side_show_cat_name'] == "1") { ?><?php print $date['cat_name'];?>: <?php } ?><?php print $date['date_title'];?></h3><?php } ?>
	
		<div style=" overflow: hidden; margin-right: <?php print $dshow['sm_padding'];?>px; <?php if($dshow['side_menu_photo_width'] <= "90") { print "float: left; width: ".$dshow['side_menu_photo_width']."%;"; } ?>" class="featsidemenuitem">
		<?php 

				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					$size = getimagefiledems($pic,$dshow['side_menu_photo_file']);

					print "<img src='".getimagefile($pic,$dshow['side_menu_photo_file'])."'  ww=\"".$size[0]."\" hh=\"".$size[1]."\" border='0' class=\"homephotos\" id=\"sp-".$pic['pic_id']."\">";
				} else { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						$size = getimagefiledems($pic,$dshow['side_menu_photo_file']);
						print "<img src='".getimagefile($pic,$dshow['side_menu_photo_file'])."' ww=\"".$size[0]."\" hh=\"".$size[1]."\"  border='0' class=\"homephotos\"  id=\"sp-".$pic['pic_id']."\">";
					}
				}

		?>
		</div>
		<?php if($dshow['side_title_placement'] == "below") { ?><?php if($date['private'] > 0) { ?><span class="the-icons icon-lock"></span><?php } ?><h3><?php if($dshow['side_show_cat_name'] == "1") { ?><?php print $date['cat_name'];?>: <?php } ?><?php print $date['date_title'];?></h3><?php } ?>
		<?php if($dshow['side_show_date'] == "1") { ?><br><?php print $date['date_show_date'];?><?php } ?>

		<?php if($dshow['side_show_snippet'] == "1") { 
			print "<br>";
			if(!empty($date['date_snippet'])) { 
				$snip = $date['date_snippet'];
			} else { 
				$snip = strip_tags($date['date_text']);
			}
			if($dshow['side_snippet_length'] > 0) { 

				if(strlen($snip) > $dshow['side_snippet_length']) { 
					$sub_descr = strip_tags($snip);
					$sub_descr = preg_replace('/\s\s+/', ' ', $sub_descr);
					$sub_descr = (substr_replace(strip_tags(trim($sub_descr)), "", $dshow['side_snippet_length']). "");
					$sub_descr = str_replace('"',"",$sub_descr);
					$sub_descr = str_replace('&nbsp;'," ",$sub_descr);
					$sub_descr = trim($sub_descr);
					print $sub_descr."......";
				} else { 
					$snippet = "".strip_tags($snip).""; 
					$snippet = preg_replace('/\s\s+/', ' ', $snippet);
					$snippet = str_replace('&nbsp;'," ",$snippet);
					print  trim($snippet);
				}
			} else { 
				$snippet = "".strip_tags($snip).""; 
				$snippet = preg_replace('/\s\s+/', ' ', $snippet);
				$snippet = str_replace('&nbsp;'," ",$snippet);
				print  trim($snippet);
			}

		}
			?>
		
		

		<div class="clear"></div>
		</a>
		</li>

		<?php  } ?>
		</ul>
		</div>
<?php }
	} 




function showpopularhome() {
	global $setup, $site_setup,$css;
	$yesterday = "CURDATE()";
	$whendo = "pv_date BETWEEN $yesterday - INTERVAL 7 DAY AND $yesterday ";
	$time = strtotime("".$site_setup['time_diff']." hours");
	$cur_time =date('Y-m-d H:i:s', $time);

	$dates = whileSQL("ms_stats_site_pv LEFT JOIN ms_calendar ON ms_stats_site_pv.date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, COUNT(*) AS dups", "WHERE $whendo AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'   AND page_under='0' $and_cat AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') GROUP BY page_viewed ORDER BY dups DESC LIMIT 5 ");
	$pagetotal = mysqli_num_rows($dates);	?>
	<div class="sidebaritem">
	<ul>
	<li>MOST POPULAR</li>
	<?php 
	while($date = mysqli_fetch_array($dates)) { 
	$sn++;
	?>
	<li>
	<?php 

			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
				print "<a href='".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/'><img src='".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."' class='mini left' width=\"50\" height=\"50\"  border='0'></a>";
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
					print "<a href='".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/'><img src='".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']."' class='mini left' width=\"50\" height=\"50\"   border='0' ></a>";
				}
			}

	?>

	
	
	<a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/"><?php print $date['date_title'];?></a>
	<div class="date">
	<?php print $date['date_show_date'];  if($side['side_show_time'] == "1") { print " ".$date['date_time_show']; } 
	?>
	</div>
	<div class="clear"></div></li>

	<?php  } ?>
	</ul>
	</div>
<?php } ?>
<?php
	function homepagetags($side) { 
	$tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC "); 
	if(mysqli_num_rows($tags) > 0) { 
	?>
	<div class="pc"><h2>Popular Tags</h3></div>
	<div class="pc sidetags">
	<?php 
	while($tag = mysqli_fetch_array($tags)) { 
		$cktag = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag['tag_id']."' AND tag_date_id='".$date['date_id']."' "); 
		if(countIt("ms_tag_connect", "WHERE tag_tag_id='".$tag['tag_id']."' ") > 0) {
			$ttag++;
			if($ttag > 1) { print ", "; } 
			?>
		<nobr><a href="<?php print $setup['temp_url_folder'];?>/tags/<?php print $tag['tag_folder'];?>/"><?php print $tag['tag_tag'];?></a></nobr>
		<?php } ?>
	<?php } ?>
	</div>	
	<div>&nbsp;</div>
	<?php } ?>
<?php
}

?>
