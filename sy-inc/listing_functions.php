<?php
 function listContent($date_cat_id,$tag_id, $date_id,$this_cat,$this_layout, $limit,$no_cats) {
	global $site_setup,$setup,$lang,$bcat,$mobile,$top_section,$layout,$css,$ipad,$date_feature_auto_populate;

	// print_r($bcat);
	$com_settings = doSQL("ms_comments_settings", "*", "");
	$cset = doSQL("ms_calendar_settings", "*", "  ");
	// $this_cat comes from home page listings.
	if(!empty($this_cat)) { 
		if($this_cat == "999999999") { 
			$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='$this_layout' ");
			// $bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$this_cat' ");
			$and_where =  " AND date_cat>'0' AND reg_person<='0' ";
		} else { 
			$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='$this_layout' ");
			$fats = explode(",",$this_cat);
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
				$and_where .= ") AND reg_person<='0'";

			}
		}
	} else { 
		if($date_cat_id=='un') { 
				$and_where =  " AND (date_cat='0' ) ";
		} else { 
			if($bcat['cat_id'] > 0) { 
				$and_where =  " AND date_cat='".$bcat['cat_id']."'  ";
			}
		}
	}

	if(!is_numeric($_REQUEST['previewLayout'])) { 
		$_REQUEST['previewLayout'] = 0;
	}
	if($_REQUEST['previewLayout'] > 0) { 
		$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['previewLayout']."' ");
	}
?>



<?php if($layout['layout_css_id'] == "listing-thumbnail") { ?>


<?php } ?>

<?php if($layout['layout_css_id'] == "listing-onphoto") { ?>

 <?php if(($ipad !== true)&&($mobile !== true)==true) { ?>
 <script>
$(document).ready(function(){
	$(".preview").hover(
	  function () {
		$(this).find('.previewtext').slideDown(200);
	  },
	  function () {
		$(this).find('.previewtext').slideUp(200);
	  }
	);
});
</script>

<?php } ?>
<?php } ?>

<?php if(!is_array($tag_id)) { 
	// Checking to make sure tag_id isn't an array for related pages and not show the page title 
	?>

<?php if(($date_id <=0) && ($this_cat <=0)==true){ ?>
<?php if($bcat['cat_private_button'] == "1") { ?>
<div id="findphotos" class="icon-lock the-icons textright right" onclick="findphotos(); return false;"><?php print _access_private_photos_button_;?></div>
<?php } ?>
<?php $topcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$top_section."' "); ?>

<?php if(($bcat['cat_search'] == "1")||($topcat['cat_search'] == "1")==true) { ?>
<div class="icon-search the-icons searchlink textright right"><?php print _search_;?></div>
<?php } ?>



<?php if($bcat['cat_type'] == "forum") { 
	include $setup['path']."/sy-inc/forum/forum.top.menu.php";	
} 
 ?>
	<?php if((!empty($bcat['cat_name']))&&($bcat['cat_show_title'] == "1")==true) { ?>	<div class="pc title"><h1>
	<?php 
		if(!empty($bcat['cat_under_ids'])) { 
			$scats = explode(",",$bcat['cat_under_ids']);
			foreach($scats AS $scat) { 
				$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
				if($tc <=0) { 
					$top_cat_layout = $tcat['cat_cat_layout'];
				}
				$tc++;
				print " <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$tcat['cat_folder']."/\">".$tcat['cat_name']."</a> > ";
			}
		}
	?>
	<?php if(!empty($bcat['cat_name'])) { print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$bcat['cat_folder']."/\">".$bcat['cat_name']."</a>"; } ?> <?php if($date_cat_id=="un") { print " > "._uncategorized_; } ?>
	<?php	if($tag_id >0) { 
		$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$tag_id."' ");
		print " | ".ucfirst($tag['tag_tag']);
	}
	?>
	</h1></div><?php } ?>
	<?php if(!empty($bcat['cat_text'])) { ?><div class="pc categorytext"><?php if($bcat['cat_using_editor'] == "1") { print $bcat['cat_text']; } else { print nl2br($bcat['cat_text']); } ?></div><?php } ?>

<?php } ?>
<?php if($bcat['cat_type'] == "registry") { 
	include $setup['path']."/sy-inc/registry_search.php";	
} 
?>

<?php if($bcat['cat_private_page'] == "1") { ?>
<?php include "find_photos.php"; ?>
<?php } ?>
<?php 
	// Ending the checking if tag_id is an array
}
?>

<?php 
if($bcat['cat_cat_layout']<=0) { 
	$this_cat_layout = $top_cat_layout;
} else { 
	$this_cat_layout = $bcat['cat_cat_layout'];
}
$catlayout =  doSQL("ms_category_layouts", "*", "WHERE layout_id='".$this_cat_layout."' "); ?>


<?php ################################ CATEGORIES ############################################ ?>
<?php if(($bcat['cat_show_sub_cats_page'] == "1") && ($_REQUEST['vp'] <=1) && ($date_id <=0)&&(countIt("ms_blog_categories",  "WHERE cat_under='".$bcat['cat_id']."' AND cat_status='1'  ") > 0)&&($no_cats!==true)==true){ ?>
	<div id="<?php print $catlayout['layout_css_id'];?>">
	<?php
		$cat_thumb_nails = array();
		$cat_max_size = 0;
		$x = 1;
		$mcats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$bcat['cat_id']."' AND cat_status='1' ORDER BY cat_order,cat_name ASC ");
		$total_sub_cats = mysqli_num_rows($mcats);
		while($mcat = mysqli_fetch_array($mcats)) {
			showCatPreview($mcat,$layout,$catlayout);
		 } 
		?>

		<div class="cssClear"></div>
	<div>&nbsp;</div>

<?php if((!empty($bcat['cat_text_under_subs']))&&($total_sub_cats > 0)==true) { ?><div class="pageContent"><?php if($bcat['cat_using_editor'] == "1") { print $bcat['cat_text_under_subs']; } else { print nl2br($bcat['cat_text_under_subs']); } ?></div><div>&nbsp;</div><?php } ?>
</div>
<?php } ?>
<?php ################################ END CATEGORIES ############################################ ?>

<?php if($bcat['cat_no_list'] <=0) { ?>
	<div id="<?php print $layout['layout_css_id'];?>" class="listingpagepopulate">
	<?php $total_posts =  listContentPages($date_cat_id,$tag_id, $date_id,$this_cat,$this_layout, $limit,$no_cats); ?>
	</div>
	<div class="clear"></div>

	<?php 
	$per_page = $bcat['cat_per_page'];
	if($limit > 0) { 
		$per_page = $limit;
	}
	if(($tag_id >0)||(is_array($tag_id)) == true) { 
		$per_page = 20;
	}
	$NPvars = array();
	if(empty($_REQUEST['vp'])) {
		$vp = "1";
	} else {
		$vp = $_REQUEST['vp'];
	}

		if(($bcat['cat_auto_populate']<=0 )&&($date_feature_auto_populate<=0 )==true) { ?>
		<div class="clear"></div>
		<?php 
			if(($total_posts > $per_page) && ($this_cat !== "999999999")&&($per_page > 0)==true){ ?><div class="pageContent"><?php nextprevposts($total_posts, $vp,$per_page,  $NPvars); ?></div> <?php
			} 
		} else {	 
			?>
			<?php } 
	?>




	<div id="loadingMorePages"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif"><br><?php print _loading_more_pages_;?></div>
	<?php } ?>
<?php if((!empty($bcat['cat_text_under_content']))&&($date_id <=0)==true) { ?><div class="pageContent"><?php if($bcat['cat_using_editor'] == "1") { print $bcat['cat_text_under_content'];  } else { print nl2br($bcat['cat_text_under_content']); } ?></div><?php } ?>

		<?php
		}
	


 function listContentPages($date_cat_id,$tag_id, $date_id,$this_cat,$this_layout, $limit,$no_cats) {
	global $site_setup,$dbcon,$setup,$lang,$bcat,$mobile,$top_section,$layout,$css,$ipad,$date_feature_auto_populate,$dbcon;
	?>
<?php 	
if(empty($_REQUEST['vp'])) {
	$vp = "1";
} else {
	$vp = $_REQUEST['vp'];
}
$time = strtotime("".$site_setup['time_diff']." hours");
$cur_time =date('Y-m-d H:i:s', $time);


// $total_results = countIt("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id","WHERE date_id>'0' AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$bcat['cat_id']."'))  AND date_date<=NOW() AND date_public!='2' AND  private<='1'  AND date_type='news'  $and_where");
$per_page = $bcat['cat_per_page'];
if($limit > 0) { 
	$per_page = $limit;
}
if($per_page <= 0) { 
	// $per_page = 20;
}
$NPvars = array();
$sq_page = $vp * $per_page - $per_page;	

if($bcat['cat_order_by'] == "pageorder") { 
	$order_by = " page_order ASC";
} else if($bcat['cat_order_by'] == "title") { 
	$order_by = " date_title ASC";
} else { 
	$order_by = " date_date DESC,date_time DESC ";
}

	if(($tag_id >0)||(is_array($tag_id)) == true) { 
		$per_page = 20;
		if(is_array($tag_id)) { 
			$get_tag = "(";
			foreach($tag_id AS $tid) { 
				$ts++;
				// first in array is the date_id so we don't show a duplicate. 
				if($ts == "1") { 
					$and_no_date = "AND date_id!='".$tid."' ";
				} else { 
					if($ts > 2) { 
						$get_tag .= " OR ";
					}
					$get_tag .= "ms_tag_connect.tag_tag_id='".$tid."' ";
				}
			}
			$get_tag .= ")";
			$group_by = "GROUP BY date_id ";
		} else { 
			$get_tag = "ms_tag_connect.tag_tag_id='".$tag_id."' ";
		}	

		$dates = whileSQL("ms_calendar LEFT JOIN ms_tag_connect ON ms_calendar.date_id=ms_tag_connect.tag_date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE date_id>'0' AND date_public='1' AND  $get_tag  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) $and_no_date $group_by ORDER BY date_date DESC, date_time DESC");
		$total_results = mysqli_num_rows($dates);

		$dates = whileSQL("ms_calendar LEFT JOIN ms_tag_connect ON ms_calendar.date_id=ms_tag_connect.tag_date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show", "WHERE date_id>'0'  AND date_public='1' AND  $get_tag  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) $and_no_date $group_by ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");
		$total_posts = $total_posts + mysqli_num_rows($dates);


	} elseif($bcat['cat_type'] == "proofing") { 

	$dates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."'  AND date_id>'0' AND date_public!='2' AND cat_type='proofing' ORDER BY date_id DESC  LIMIT $sq_page,$per_page");

	} elseif($bcat['cat_type'] == "registry") { 

		if($_REQUEST['ar'] == "search") { 
			if(!empty($_REQUEST['rname'])) { 
				$in_bool = " IN BOOLEAN MODE";
				$_REQUEST['rname'] = strip_tags($_REQUEST['rname']);
				$var = sql_safe(@$_REQUEST['rname']); 
				$var = str_replace("("," ",$var);
				$var = str_replace(")"," ",$var);
				$var = str_replace("="," ",$var);
				$var = str_replace("<"," ",$var);
				$var = str_replace(">"," ",$var);
				$var = str_replace("--"," ",$var);
				$var = str_replace("/"," ",$var);
				$ignore_words = array("by", "and", "of", "the", "for", "why", "in", " ", "&", "to", "how", "i", "have", "been", "a", "if");

				$trimmed = trim($var);
				$trimmed1 = trim($var);
				//separate key-phrases into keywords
				$trimmed_array = explode(" ",$trimmed);
				$trimmed_array1 = explode(" ",$trimmed1);
				 
				// check for an empty string and display a message.
				if ($trimmed == "") {
					$resultmsg =  "<p>Search Error</p><p>Please enter a search...</p>" ;
				}
				 
				// check for a search parameter
				if (!isset($var)){
					$resultmsg =  "<p>Search Error</p><p>We don't seem to have a search parameter! </p>" ;
				}
				$adid_array = array();
				$word_array = array();
				$word_array2 = array();
				$short_words = array();
				// Build SQL Query for each keyword entered
					foreach ($trimmed_array as $trimm){
					if(!in_array(strtolower($trimm), $ignore_words)) { 
						$trimm = str_replace('"', "", $trimm);
						$trimm = str_replace('?', "", $trimm);
						$trimm = trim(stripslashes(stripslashes($trimm)));
						if(!empty($trimm)) { 
							if(strlen($trimm)==3) {
								array_push($short_words, $trimm);
							} else {
								$searching .= " $trimm";
									$word_count ++;

							}
						}
					}
				}

				foreach($short_words AS $tw) { 
					$and_sql .= " AND (date_title LIKE '%".addslashes($tw)."%' OR date_text LIKE '%".addslashes($tw)."%'  OR page_keywords LIKE '%".addslashes($tw)."%')";
				}

				$subject_weight = 3;
				$text_weight = 1;
				$keyword_weight = 2;
				$searching = mysqli_real_escape_string($dbcon,$searching);

				$dates = "SELECT *, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show , MATCH (date_title) AGAINST ('".$searching."' $in_bool)  * $subject_weight + MATCH (date_text) AGAINST ('".$searching."'  $in_bool) * $text_weight  + MATCH (page_keywords) AGAINST ('".$searching."'  $in_bool) * $keyword_weight  AS score FROM ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id WHERE date_type='news' AND cat_type='registry' AND date_public='1' AND private<'2' AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )  $search_what AND  MATCH (date_title, date_text,page_keywords) AGAINST ('+".$searching."'  $in_bool)  ORDER BY score  DESC";
				$dates=mysqli_query($dbcon,$dates);
				if (!$dates) {	echo( "MySQL error: " . mysqli_error($dbcon) . "");	exit(); }
				$total_results = mysqli_num_rows ($dates);


				 if(($total_results < 1)AND(count($short_words)>0)==true){
					$dates = "SELECT *, date_format(reg_event_date, '".$site_setup['date_format']." ')  AS reg_event_date_show FROM ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id WHERE date_type='news'  AND cat_type='registry' AND date_cat='".$bcat['cat_id']."' $search_what AND date_id>'0' AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) AND date_public='1' AND private<'2'  $and_sql  ORDER BY date_id DESC";
					$dates=mysqli_query ($dbcon,$dates);
					if (!$dates) {	//echo( "MySQL error: " . mysqli_error($dbcon) . "");	
					exit(); }
					$total_results = mysqli_num_rows ($dates);
					$do_short = true;
				 }
			}

			if(!empty($_REQUEST['remail'])) { 
				$_REQUEST['remail'] = trim($_REQUEST['remail']);
				$_REQUEST['remail'] = strip_tags($_REQUEST['remail']);
				$_REQUEST['remail'] = sql_safe($_REQUEST['remail']);
				$dates = whileSQL("ms_people LEFT JOIN ms_calendar ON ms_people.p_id=ms_calendar.reg_person LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE p_email='".$_REQUEST['remail']."' ");

			}

			if(!empty($_REQUEST['edate'])) { 
				$_REQUEST['edate'] = trim($_REQUEST['edate']);
				$_REQUEST['edate'] = strip_tags($_REQUEST['edate']);
				$_REQUEST['edate'] = sql_safe($_REQUEST['edate']);
				$dates = whileSQL("ms_people LEFT JOIN ms_calendar ON ms_people.p_id=ms_calendar.reg_person LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE reg_event_date='".$_REQUEST['edate']."' ");
			}

				$_REQUEST['rid'] = trim($_REQUEST['rid']);
				$_REQUEST['rid'] = strip_tags($_REQUEST['rid']);
				$_REQUEST['rid'] = sql_safe($_REQUEST['rid']);
			if(!empty($_REQUEST['rid'])) { 
				$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['rid']."' AND reg_person>'0' ");
			}
			if(@mysqli_num_rows($dates)<=0) { 
				$dates = whileSQL("ms_calendar", "*", "WHERE date_id='0'  ");

				print "<div class=\"pc center\"><h2>"._no_registry_search_results_."</h2></div>";
			}
		} else  { 
			$dates = whileSQL("ms_calendar", "*", "WHERE date_id='0'  ");

		}

	} else if(($bcat['cat_id'] > 0)&&($date_id <=0)==true) { 
		//	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show", "WHERE date_id>'0' AND ((cat_no_show_posts!='1' OR date_cat='0') OR (cat_id='".$bcat['cat_id']."'))  AND date_public!='2' AND date_type='news' $and_where AND private<='1'  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");

		if($bcat['cat_type'] == "forum") { 

		} else { 


			if($bcat['cat_list_sub_cat_posts'] == "1") { 
				$ucats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$bcat['cat_id']."' ");
				while($ucat = mysqli_fetch_array($ucats)) { 
					$and_sub_cat .= "OR date_cat='".$ucat['cat_id']."' ";
				}

			}

			if($bcat['cat_expire_hide'] == "1") { 
				$and_expire = " AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )  ";
			}

			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE  date_public!='2' AND date_type='news'  AND  (ms_calendar.date_cat='".$bcat['cat_id']."' OR ms_blog_cats_connect.con_cat='".$bcat['cat_id']."' $and_sub_cat )  AND (book_special_event_date='0000-00-00' OR book_special_event_date>'".date('Y-m-d')."' ) AND page_under='0'  AND date_id!='".$bcat['cat_content']."' AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' $and_expire GROUP BY date_id   ORDER BY $order_by ");
			$total_posts = $total_posts + mysqli_num_rows($dates);

			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  date_public!='2' AND date_type='news'   AND (book_special_event_date='0000-00-00' OR book_special_event_date>'".date('Y-m-d')."' ) AND  (ms_calendar.date_cat='".$bcat['cat_id']."' OR ms_blog_cats_connect.con_cat='".$bcat['cat_id']."' $and_sub_cat )  AND page_under='0'  AND date_id!='".$bcat['cat_content']."'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND  private<='1'   $and_expire GROUP BY date_id   ORDER BY $order_by  LIMIT $sq_page,$per_page");


	/*	if($bcat['cat_list_sub_cat_posts'] == "1") { 
			$sdates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show", "WHERE date_id>'0'   AND date_public!='2' AND date_type='news' AND ms_blog_categories.cat_under='".$bcat['cat_id']."' AND private<='1'  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  ORDER BY date_date DESC, date_time DESC ");
			$total_posts = $total_posts + mysqli_num_rows($sdates);
			$sdates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show", "WHERE date_id>'0'   AND date_public!='2' AND date_type='news' AND ms_blog_categories.cat_under='".$bcat['cat_id']."' AND private<='1'  AND CONCAT(date_date, ' ', date_time)<='$cur_time'  ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");
		}
		*/
		}
	} elseif($date_id > 0) { 

	 	$dates = whileSQL("ms_calendar", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE page_under='".$date_id."' AND date_public!='2'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )  ORDER BY page_order ASC ");
		$total_posts = $total_posts + mysqli_num_rows($dates);

		$dates = whileSQL("ms_calendar", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE page_under='".$date_id."' AND date_public!='2'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )  ORDER BY page_order ASC");

	} elseif(!empty($this_cat)) { 

		$and_expire = " AND ((cat_expire_hide='1' AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )) OR (cat_expire_hide='0'))  ";

		if($this_cat == "999999999") { 
			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  date_public!='2'  $and_expire AND date_type='news'  AND cat_password='' AND page_under='0' AND date_cat>'0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND reg_person<='0'  GROUP BY date_id   ORDER BY date_date DESC, date_time DESC ");
			$total_posts = $total_posts + mysqli_num_rows($dates);

			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE  date_public!='2'  $and_expire AND date_type='news' AND cat_password=''    AND page_under='0' AND date_cat>'0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1'  AND reg_person<='0'  GROUP BY date_id   ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");
		} else { 
		// $this_cat comes from home page listings.
			$fats = explode(",",$this_cat);
			if(count($fats) > 0) { 
				$fand_where .= "AND (";
				foreach($fats AS $fat) { 
					$bcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='$fat' ");
					if(!empty($bcat['cat_id'])) {
						$fcount ++;
						if($fcount > 1) { 
							$fand_where .= " OR ";
						}

						$fand_where .=  " date_cat='".$bcat['cat_id']."'  ";
					}
				}
				$fand_where .= ")";

			}
			if(count($fats) == "1") { 
				$hpfeatcat = doSQL("ms_blog_categories","*","WHERE cat_id='".$this_cat."' ");
			}
			if($hpfeatcat['cat_order_by'] == "pageorder") { 
				$order_by = " page_order ASC";
			} else if($hpfeatcat['cat_order_by'] == "title") { 
				$order_by = " date_title ASC";
			} else { 
				$order_by = " date_date DESC,date_time DESC ";
			}
			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  date_public!='2'  $and_expire AND date_type='news'  AND cat_password='' AND page_under='0' AND date_cat>'0'  $fand_where AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1'  AND reg_person<='0'  GROUP BY date_id   ORDER BY $order_by ");
			$total_posts = $total_posts + mysqli_num_rows($dates);

			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE  date_public!='2'  $and_expire AND date_type='news' AND cat_password=''    AND page_under='0' AND date_cat>'0' $fand_where AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1'  AND reg_person<='0'  GROUP BY date_id   ORDER BY $order_by LIMIT $sq_page,$per_page");





		}


 } else { 
	 	$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE  date_public!='2' AND date_type='news'  AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) GROUP BY date_id   ORDER BY date_date DESC, date_time DESC ");
		$total_posts = $total_posts + mysqli_num_rows($dates);

		$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '".$site_setup['date_time_format']."')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." ".$site_setup['date_time_format']."')  AS last_modified, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show ", "WHERE  date_public!='2' AND date_type='news'    AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) GROUP BY date_id   ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");

 }

	if(($bcat['cat_type'] == "faq")&&($bcat['cat_id']<=0)&&($tag_id<=0)==true) { 
		$mcats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' AND cat_status='1' ORDER BY cat_name ASC ");
		while($mcat = mysqli_fetch_array($mcats)) { 
			?>
		<div class="pageContent"><h3 class="break"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder'];?><?php print $mcat['cat_folder'];?>/"><?php print $mcat['cat_name'];?></a></h3>
		<?php if(!empty($mcat['cat_text'])) { if($mcat['cat_using_editor'] == "1" ) { print $mcat['cat_text']; } else { print nl2br($mcat['cat_text']); } } ?></div>

			<?php } 
	} elseif($bcat['cat_type'] == "forum") { 
		include $setup['path']."/sy-inc/forum/forum.topics.php";
		?>
<div class="clear"></div>
</div>
<?php
	} else { 
		if(!empty($this_cat)) { 
			$hp = doSQL("ms_calendar", "home_first_layout", "WHERE page_home='1' ");
			if($hp['home_first_layout'] > 0) { 
				$bcat['cat_first_layout'] = $hp['home_first_layout'];
			} else { 
				$bcat['cat_first_layout'] = 0;
			}
		}
			$cp = 0;
			while($page = mysqli_fetch_array($dates)) {
				$cp++;
				if($bcat['cat_type'] == "faq") { 
					showFAQ($page,$total_posts);
				} else { 

				if(($page['prod_inventory_control'] == "1")&&($page['prod_qty'] <=0)&&(empty($setup['show_out_of_stock'])) && ($bcat['cat_type'] == "store")==true) { 

				} else { 

						if(($cp == 1)&&($bcat['cat_first_layout'] > 0)&&($_REQUEST['vp'] <=1)==true) { 
							$first_layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$bcat['cat_first_layout']."' ");
							// $layout['layout_photo_size'] = $first_layout['layout_photo_size'];
								$page['layout_photo_class'] = $first_layout['layout_photo_class'];
								$page['layout_photo_size'] = $first_layout['layout_photo_size'];
								if(!empty($first_layout['layout_folder'])) { 
									include $setup['path']."/".$first_layout['layout_folder']."/".$first_layout['layout_file'];
								} else { 
									include $setup['path']."/sy-layouts/".$first_layout['layout_file'];
								}
								print "<div class=\"clear\"></div>";
						} else { 
							if(($cp == 1) || (($cp == 2) &&($bcat['cat_first_layout'] > 0) && ($_REQUEST['vp'] <=1))==true) { 
						 } 
							if($bcat['cat_first_layout'] > 0) { 
								$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$layout['layout_id']."' ");
							}
							$page['layout_photo_class'] = '';
							$page['layout_photo_size'] = '';
							if(!empty($layout['layout_folder'])) { 
								include $setup['path']."/".$layout['layout_folder']."/".$layout['layout_file'];
							} else { 
								include $setup['path']."/sy-layouts/".$layout['layout_file'];
							}
						}
					}
				}
			}
	return $total_posts;
	 }
 }

function showFAQ($date,$total_posts) { 
	global $setup,$site_setup,$cset;
	?>
<script>
$(document).ready(function(){
	$(".afaqlink").unbind().click(function() { 
		fixbackground();
		$("#photoprodsinner").html($("#faq-"+$(this).attr("faq-id")).html());
		$("#buybackground").fadeIn(50, function() { 
			$("#photoprods").css({"top":50+"px"});
			$("#photoprods").fadeIn(100, function() { 
				$("#closebuyphoto").show();
				$('html').click(function() {
					 closebuyphoto();
				 });
				 $('#photoprods').click(function(event){
					 event.stopPropagation();
				 });
			});

		});
	return false;
	});
});


	</script>
	<?php 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
	?>
	<?php if($total_posts > 1) { ?>
	<div class="pageContent faqlink"><h3><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/" id="faqlink-<?php print $date['date_id'];?>" class="afaqlink" faq-id="<?php print $date['date_id'];?>"><?php print $date['date_title'];?></a></h3></div>
	<?php } ?>
	<div id="faq-<?php print $date['date_id'];?>" class="viewfaqcontainer" <?php if($total_posts == 1) { print "style=\"display: block;\""; } ?>>
	<div class="viewfaq">
		<?php if($total_posts > 1) { ?>
		<div class="pageContent"><h1 class="break"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/"><?php print $date['date_title'];?></a></h1></div>
		<?php } ?>
		<div class="viewfaqtext">
			<?php
		$total_photos = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id",  "WHERE bp_blog='".$date['date_id']."'  ");
		if($total_photos > 0) { ?>
		<div class="viewmedia"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/">Click here to view page with included media.</a></div><div>&nbsp;</div>
		<?php } ?>
	
		<div><?php print $date['date_text'];?></div>
		</div>
		<div>&nbsp;</div>
		<div class="pageContent"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/">Link to this page</a></div>

		<div id="postcattags">


	<?php 
		if(countIt("ms_tag_connect",  "WHERE tag_date_id='".$date['date_id']."' ")>0) { ?>
		<div class="tags pageContent"><?php print _tags_;?> 
		<?php $tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$date['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) { 
			if($tn > 0) { print ", "; } 
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/tags/".$tag['tag_folder']."/\">".$tag['tag_tag']."</a>";
			$tn++;
		}
		?>
		</div>
		<?php } 
	?>
	</div>	</div>

	<div>&nbsp;</div>
	</div>
	<?php 
}



 
  function showCatPreview($cat,$layout,$catlayout) {
	global $fb,$setup,$site_setup,$lang,$cset,$bcat,$top_section;
	$page['list_cat_id'] = $cat['cat_id'];
	$page['list_cat_title'] = "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_cat='".$cat['cat_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		$size = getimagefiledems($pic,$catlayout['layout_photo_size']);
		$thumb_html ="<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\"><img src=\"".getimagefile($pic,$catlayout['layout_photo_size'])."\" class=\"".$catlayout['layout_photo_class'].""; if(($catlayout['layout_css_id'] == "listing-thumbnail")||($catlayout['layout_css_id'] == "listing-stacked")==true) {if($size[0]>=$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$cat['cat_id']."\" border=\"0\"></a>";
	}
	$page['list_cat_photo'] = $thumb_html;

	if(!empty($catlayout['layout_folder'])) { 
		include $setup['path']."/".$catlayout['layout_folder']."/".$catlayout['layout_file'];
	} else { 
		include $setup['path']."/sy-layouts/".$catlayout['layout_file'];
	}
} 

 
function nextprevposts($total_results, $vp, $per_page,  $NPvars) {
	global $lang,$setup;
	$pages = ceil($total_results / $per_page);
	$tpl = 10;
	if($pages<$tpl) {
		$tpl = $pages;
	}


	if(empty($vp)) {	$vp = "1";		}
	$vw1 = ($vp * $per_page) - $per_page + 1; 
	$vw2 = $vw1 + ($per_page - 1);
	if($vp * $per_page > $total_results) {
		$vw2 = (($vp - 1) * $per_page) + ($total_results - (($vp - 1) * $per_page));
	}
	print "<div id=\"pageMenu\">";
	// print "<span class=\"totalResults\">$vw1 - $vw2 ".$lang['_of_']."  $total_results</span>";
	foreach($NPvars AS $vari) {
		$qstring .= "&$vari";
	}
	if(!empty($_REQUEST['q'])) {
		$qstring .= "&q=".$_REQUEST['q']."";
	}
	if(!empty($_REQUEST['ingals'])) {
		$qstring .= "&ingals=".$_REQUEST['ingals']."";
	}

	if($vp <= ($tpl/2)) {
		$np = 1;
	} else {
		$np = $vp - (($tpl/2)-1);
		if($pages > $tpl) {
			print "<a class=\"selectPage\" href=\"".$site_setup['index_page']."?vp=1" . "$qstring\">".$lang['_nav_first_']."</a>";
		}
	}
	if($vp>=($pages - ($tpl/2))) {
		$np = $pages - $tpl + 1;
	}

	if($vp > 1) {
		$prev = $vp - 1;
		print "<a  class=\"selectPage\" href=\"".$site_setup['index_page']."?vp=$prev" . "$qstring\">".$lang['_nav_previous_']."</a>";
	}

	$pct = 1;
	while($np  < $total_results / $per_page + 1 AND $pct <= $tpl) {
		if($np == $vp) {
			print  "<span class=\"selectedPage\">&nbsp;$np&nbsp;</span>" ;
		} else {
			print  "<a class=\"selectPage\"  href=\"".$site_setup['index_page']."?vp=$np" . "$qstring\">$np</a>" ;
		}
		$np++;
		$pct++;
	}
	if($vp < $total_results / $per_page ) {
		$next = $vp + 1;
		print "<a class=\"selectPage\" href=\"".$site_setup['index_page']."?vp=$next" . "$qstring\">".$lang['_nav_next_']."</a>";
	}
	if($np<=$pages) {
		print "<a class=\"selectPage\"  href=\"".$site_setup['index_page']."?vp=$pages" . "$qstring\">".$lang['_nav_last_']."</a>";
	}
	print "</div>";
}


/* THE FUNCTIONS TO DISPLAY PAY INFORMATION */

function listingTitle($date) { 
	global $setup;
	if($date['list_cat_id'] > 0) { 
		print $date['list_cat_title'];
	} elseif($date['list_sub_id'] > 0) { 
		print $date['list_sub_title'];
	} else { 
		if($date['private'] > 0) { 
			print "<span class=\"the-icons icon-lock\"></span>";
		}

		if(!empty($date['external_link'])) { 
			print  "<a href=\"".$date['external_link']."\" target=\"_blank\">".$date['date_title']."</a>";
		} else { 
			print  "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$date['date_title']."</a>";
		}

	}
}

function listingTags($date) { 
	global $setup;
	if(countIt("ms_tag_connect",  "WHERE tag_date_id='".$date['date_id']."' ")>0) { ?>
	<?php $tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$date['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) { 
			if($tn > 0) { print ", "; } 
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/tags/".$tag['tag_folder']."/\">".$tag['tag_tag']."</a>";
			$tn++;
		}
	} 
}

function listingTotalPhotos($date) { 
	global $setup;
	if($date['date_id'] > 0) { 
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

			$pics_where = "WHERE $and_date_tag";
			$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
			$pics_orderby = $date['date_photos_keys_orderby']; 
			$pics_acdc = $date['date_photos_keys_acdc'];
			$pic_file = $date['blog_photo_file'];
			$piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id  ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." "); 	
			$total_images = mysqli_num_rows($piccount);

			} else { 
			$total_images = countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' ");
		}
		if($total_images > 0) { 
			print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".$total_images." "._photos_word_photos_."</a>";
		}
	}
}

function listingTitleOnly($date) { 
	print $date['date_title'];
}

function listingDate($date) { 
	print $date['date_show_date'];
}
function listingEventDate($date) { 
	print $date['reg_event_date_show'];
}

function listingExpireDate($date) { 
	if(!empty($date['date_expire_show'])) { 
		print $date['date_expire_show'];
	}
}

function listingTime($date) { 
	print $date['date_time_show'];
}
function listingPhoto($date) {
	global $setup,$layout;
	?>
<?php 
	if(!empty($date['layout_photo_class'])) { 
		$layout['layout_photo_class'] = $date['layout_photo_class'];
	}
	if(!empty($date['layout_photo_size'])) { 
		$layout['layout_photo_size'] = $date['layout_photo_size'];
	}

	if($date['list_cat_id'] > 0) { 
		print $date['list_cat_photo'];
	} elseif($date['list_sub_id'] > 0) { 
		print $date['list_sub_photo'];

	} else { 

		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($date['video_file'])) { 
			$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$date['video_file']."' ");
		}
		if((empty($pic['pic_id'])) && (!empty($vid['vid_poster'])) == true) { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$vid['vid_poster']); 

			$thumb_html = "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\"><img src=\"".$setup['temp_url_folder']."/sy-photos/".$vid['vid_poster']."\"  class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\"></a>";

		} else { 
			
			if(!empty($pic['pic_id'])) {
				$size = getimagefiledems($pic,$layout['layout_photo_size']);
				if(!empty($date['external_link'])) { 
					$thumb_html ="<a href=\"".$date['external_link']."\" target=\"_blank\">";
				} else { 
					$thumb_html ="<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">";
				}
				$ext = strtolower(substr($pic['pic_org'], -4));

				if($ext == ".gif") { 
					$img_small = getimagefile($pic,"pic_pic");
					$img_large = getimagefile($pic,"pic_full");
					$size = getimagefiledems($pic,'pic_full');

					$thumb_html .="<img alt=\"".htmlspecialchars($date['date_title'])."\" src=\"".getimagefile($pic,'pic_full')."\" class=\"".$layout['layout_photo_class']." gifimg gif"; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"100%\" height=\"auto\"  id=\"th-".$date['date_id']."\" border=\"0\"  data-lg=\"lg".$pic['pic_id']."\">";

				} else { 

					$thumb_html .= "<img alt=\"".htmlspecialchars($date['date_title'])."\" src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";

				}
				$thumb_html .="</a>";

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

					$pics_where = "WHERE $and_date_tag";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
					$pics_orderby = $date['date_photos_keys_orderby']; 
					$pics_acdc = $date['date_photos_keys_acdc'];
					$pic_file = $date['blog_photo_file'];
					$pic = doSQL("$pics_tables", "*", "$pics_where ORDER BY  $pics_orderby $pics_acdc LIMIT  1 ");
				} else { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				}
				if(!empty($pic['pic_id'])) {
					$size = getimagefiledems($pic,$layout['layout_photo_size']);
					if(!empty($date['external_link'])) { 
						$thumb_html ="<a href=\"".$date['external_link']."\" target=\"_blank\">";
					} else { 
						$thumb_html ="<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">";
					}
					$ext = strtolower(substr($pic['pic_org'], -4));
					if($ext == ".gif") { 
						$img_small = getimagefile($pic,"pic_pic");
						$img_large = getimagefile($pic,"pic_full");
						$size = getimagefiledems($pic,'pic_full');

						$thumb_html .="<img alt=\"".htmlspecialchars($date['date_title'])."\" src=\"".getimagefile($pic,'pic_full')."\" class=\"".$layout['layout_photo_class']." gifimg gif"; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\"  data-lg=\"lg".$pic['pic_id']."\">";

					} else { 

						$thumb_html .="<img alt=\"".htmlspecialchars($date['date_title'])."\" src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";
					}			
					$thumb_html .="</a>";
				}
			}
		}
		print $thumb_html;
	}
}


function listingVersion($date) { 
	if(!empty($date['prod_version'])) { 
		print "Version: ".$date['prod_version']."";
	}
}

function listingPhotoNoLink($date) {
	global $setup,$layout;

	if($date['list_cat_id'] > 0) { 
		print $date['list_cat_photo'];
	} elseif($date['list_sub_id'] > 0) { 
		print $date['list_sub_photo'];

	} else { 

		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			$size = getimagefiledems($pic,$layout['layout_photo_size']);
			$thumb_html ="<img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";
		} else { 
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
				$size = getimagefiledems($pic,$layout['layout_photo_size']);
				$thumb_html ="<img src=\"".getimagefile($pic,$layout['layout_photo_size'])."\" class=\"".$layout['layout_photo_class'].""; if($layout['layout_css_id'] == "listing-thumbnail") {if($size[0]>$size[1]) { $thumb_html .= " ls"; } else { $thumb_html .= " pt"; } } $thumb_html .="\" width=\"".$size[0]."\" height=\"".$size[1]."\"  id=\"th-".$date['date_id']."\" border=\"0\">";
			}
		}
		print $thumb_html;
	}
}
function listingPhotoURLOnly($date) {
	global $setup,$layout;

	if($date['list_cat_id'] > 0) { 
		print $date['list_cat_photo'];
	} elseif($date['list_sub_id'] > 0) { 
		print $date['list_sub_photo'];

	} else { 

		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($date['video_file'])) { 
			$vid = doSQL("ms_videos", "*", "WHERE vid_id='".$date['video_file']."' ");
		}
		if((empty($pic['pic_id'])) && (!empty($vid['vid_poster'])) == true) { 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$vid['vid_poster']); 

			$thumb_html =  $setup['temp_url_folder']."/sy-photos/".$vid['vid_poster'];
		} else { 
			if(!empty($pic['pic_id'])) {
				$size = getimagefiledems($pic,$layout['layout_photo_size']);
				$thumb_html =getimagefile($pic,$layout['layout_photo_size']);
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					$size = getimagefiledems($pic,$layout['layout_photo_size']);
					$thumb_html =getimagefile($pic,$layout['layout_photo_size']);
				}
			}
		}
		print $thumb_html;
	}
}

function listingPrice($date) { 
	global $site_setup;
	if($date['prod_price'] > 0) { 
		$price = productPrice($date);
		if($price['onsale'] == true) { 
			print "<s class=\"salestrike\">".showPrice($price['org'])."</s> &nbsp; ";
		}

		if(($date['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
			 $this_price = $price['price'] + (($price['price'] * $site_setup['include_vat_rate']) / 100);
		} else { 
			 $this_price = $price['price'];
		}
		print showPrice($this_price);
		if(($price['onsale'] == true)&&(!empty($date['prod_sale_message']))==true) { 
			print "<br>".$date['prod_sale_message']."";
		}
	}
}

function listingCategories($date) { 
	global $setup;
	if($date['date_cat'] > 0) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
		$categories .= " <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";

		if($date['date_cat2'] > 0) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat2']."' ");
			$categories .= ", <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
		}
		if($date['date_cat3'] > 0) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat3']."' ");
			$categories .= ", <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
		}
		if($date['date_cat4'] > 0) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat4']."' ");
			$categories .= ", <a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/\">".$cat['cat_name']."</a>";
		}
	} 
	print $categories;
}

function listingPreviewText($date) { 
	global $layout,$setup;
	if($date['date_public'] == "3") { 
		$snippet  = "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">"._pre_register_preview_text_."</a>";
	} else { 
		$prev_len = $layout['layout_preview_text_length'];
		if(!empty($date['date_snippet'])) { 
			$snippet .= "".nl2br($date['date_snippet']).""; 
		} else { 
			if($prev_len > 0) { 
				if(strlen($date['date_text']) > $prev_len) { 
					$sub_descr = strip_tags($date['date_text']);
					$sub_descr = preg_replace('/\s\s+/', ' ', $sub_descr);
					$sub_descr = (substr_replace(strip_tags(trim($sub_descr)), "", $prev_len). "");
					$sub_descr = str_replace('"',"",$sub_descr);
					$sub_descr = str_replace('&nbsp;'," ",$sub_descr);
					$sub_descr = trim($sub_descr);
					$snippet .= "$sub_descr ......"; 
				} else {
					$snippet .= "".strip_tags($date['date_text']).""; 
					$snippet = preg_replace('/\s\s+/', ' ', $snippet);
					$snippet = str_replace('&nbsp;'," ",$snippet);
					$snippet = trim($snippet);

				}
			} else { 
				$snippet .= "".$date['date_text'].""; 
			}
			if($snippet == "<br>") { 
				$snippet = "";
			}
		}
	}
	print $snippet;
}

function listingFullText($date) { 
	print $date['date_text'];
}

function listingAddToCart($date) {
	global $setup;
	if($date['qty_min']<=0) { $min = 1; } else { $min = $date['qty_min']; }
	?>
	<script>
	$(document).ready(function(){
		$('.cartoption').keypress(function(e){
			if ( e.which == 13 ) return false;
			//or...
			if ( e.which == 13 ) e.preventDefault();
		});
	});

	</script>
	<?php if(countIt("ms_product_options",  "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ") > 0) { ?>
	<div class="addtocart" onclick="showstoreitem('<?php print $date['date_id'];?>');"><span ><?php print _select_on_product_configurations_;?></span></div>        
	<?php } else { ?>

	<form name="purchase" action="/" method="post">
	<input type="hidden" name="action" id="action" class="cartoption-<?php print $date['date_id'];?>" value="addToCart">
	<input type="hidden" name="spid" id="spid" class="cartoption-<?php print $date['date_id'];?>">
	<input type="hidden" name="did" id="did" value="<?php print MD5($date['date_id']);?>" class="cartoption-<?php print $date['date_id'];?>">
	<input type="hidden" name="qty_min" id="qty_min" class="cartoption-<?php print $date['date_id'];?>" value="<?php print $min;?>">
	<?php if($date['prod_max_one'] == "1") { ?>
	<input type="hidden"  name="prod_qty" id="prod_qty" class="cartoption cartoption-<?php print $date['date_id'];?> center"  value="1">
	<?php } else { ?>
	<div id="qty" style="margin-bottom: 8px;"><?php print _qty_;?>: 
	<input type="text"  name="prod_qty" id="prod_qty" class="cartoption cartoption-<?php print $date['date_id'];?> center" size="2" value="<?php print $min;?>">
	</div>
	<?php } ?>

	<?php if(($date['prod_type'] == "package")&&($date['date_package_pre_reg'] == "1") == true){ ?>
	<div>
	<select name="cart_pre_reg" id="cart_pre_reg-<?php print $date['date_id'];?>" class="cartoption-<?php print $date['date_id'];?> itemrequired-<?php print $date['date_id'];?>">
	<option value=""><?php print _select_prereg_page_;?></option>
	<?php $pdates = whileSQL("ms_calendar", "*", "WHERE date_public='3' ORDER BY date_title ASC ");
	while($pdate = mysqli_fetch_array($pdates)) { ?>
	<option value="<?php print $pdate['date_id'];?>"><?php print $pdate['date_title'];?></option>
	<?php } ?>
	</select>
	</div>
	<div>&nbsp;</div>
	<?php } ?>
	<?php if(!empty($date['add_to_cart_redirect'])) {?>
	<input type="hidden" name="addaction" id="addaction-<?php print $date['date_id'];?>" value="<?php print $date['add_to_cart_redirect'];?>" data-redirect="1" class="cartoption-<?php print $date['date_id'];?>">
	<?php } ?>

	<div id="addtocartloading<?php print $date['date_id'];?>" style="display: none;"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
	<div id="addtocart<?php print $date['date_id'];?>" onClick="sendtocartlist('cartoption-<?php print $date['date_id'];?>','<?php print $date['date_id'];?>'); return false;" style="cursor: pointer; display: inline; " class="addtocart"><?php if(!empty($date['add_to_cart_text'])) { print $date['add_to_cart_text'];} else { print _add_to_cart_; } ?></div>
	<div class="clear"></div><div class="error hide" id="min_qty_message"><?php print _min_qty_required_;?> <?php print $min;?></div>
	<div>&nbsp;</div>

	</form>
	<?php } ?>
	<?php 
}

function listingFirstPhoto($date) { 
	global $setup,$layout;
	if(!empty($date['layout_photo_class'])) { 
		$layout['layout_photo_class'] = $date['layout_photo_class'];
	}
	if(!empty($date['layout_photo_size'])) { 
		$layout['layout_photo_size'] = $date['layout_photo_size'];
	}

	$contain = true;
	if($date['list_sub_id'] > 0) { 
		$and_sub = " AND bp_sub='".$date['list_sub_id']."' ";
	}

	$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'   $and_sub ORDER BY bp_order ASC LIMIT  1 ");
	$pic_file_select = $layout['layout_photo_size'];
	if(!empty($fphoto['pic_id'])) {
		$dsize = getimagefiledems($fphoto,$pic_file_select);

		$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$fphoto['pic_folder']."/".$fphoto[$layout['layout_photo_size']]); 
		$first_photo .= "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/".$date['list_sub_link']."\" title=\"View ".$date['date_title']."\">".displayPhoto($fphoto,$pic_file_select,$wm,$dsize,$contain,'photo','0',$x,$border_color,$border_size,$bg_color,$bg_use,"relative", "block",$bcat['cat_id'],$bcat['cat_watermark'],$bcat['cat_logo'],$captionwhere,$date,$free,$sub)."</a>";
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

			$pics_where = "WHERE $and_date_tag";
			$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
			$pics_orderby = $date['date_photos_keys_orderby']; 
			$pics_acdc = $date['date_photos_keys_acdc'];

			$fphoto = doSQL("$pics_tables", "*", "$pics_where ORDER BY  $pics_orderby $pics_acdc LIMIT  1 ");
		} else { 
			$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");
		}
		if(!empty($fphoto['pic_id'])) {
		$dsize = getimagefiledems($fphoto,$pic_file_select);
		$first_photo .= "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/".$date['list_sub_link']."\" title=\"View ".$date['date_title']."\">".displayPhoto($fphoto,$pic_file_select,$wm,$dsize,$contain,'photo','0',$x,$border_color,$border_size,$bg_color,$bg_use,"relative", "block",$bcat['cat_id'],$bcat['cat_watermark'],$bcat['cat_logo'],$captionwhere,$date,$free,$sub)."</a>";
		}
	}
	print $first_photo;
}

function listingPhotos($date) { 
	global $setup,$layout;
	if($date['caption_location'] == "1") { 
		$captionwhere = "below";
	}
	if($mobile == 1) { 
		$captionwhere = "below";
	}

	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE bp_blog='".$date['date_id']."' ORDER BY bp_order ASC ");
	while ($pic = mysqli_fetch_array($pics)){
		$pic_file_select = selectPhotoFile($layout['layout_photo_size'],$pic);
		$x++;
		$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic[$pic_file_select].""); 
		$photos .= "<div class=\"blogPhoto\">";

		$contain = true;

		$photos .= "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\">".displayPhoto($pic,$pic_file_select,$wm,$dsize,$contain,'photo','0',$x,$border_color,$border_size,$bg_color,$bg_use,"relative", "block",$bcat['cat_id'],$bcat['cat_watermark'],$bcat['cat_logo'],$captionwhere,$date,$free,$sub)."</a>";
		$photos .= "</div>";
		$last_pic = $pic['bp_order'];
	}
	print $photos;
}

function listingPhotosType($date) { 
	if($date['blog_type'] == "nextprevious") { 
		print "<span class=\"photostype\">Slideshow</span>";
	}
	if($date['blog_type'] == "gallery") { 
		if($date['thumb_style'] == "1") { 
			print "<span class=\"photostype\">Standard Thumbnail Gallery</span>";
		}
		if($date['thumb_style'] == "0") { 
			print "<span class=\"photostype\">Justified Thumbnail Gallery</span>";
		}
		if($date['thumb_style'] == "2") { 
			print "<span class=\"photostype\">Stacked Thumbnail Gallery</span>";
		}
	}
	if($date['blog_type'] == "standardlist") { 
		print "<span class=\"photostype\">Scroller</span>";
	}
	if($date['blog_type'] == "onpagewithminis") { 
		print "<span class=\"photostype\">Standard Photo With Minis</span>";
	}
	if($date['blog_type'] == "onephoto") { 
		print "<span class=\"photostype\">One Photo</span>";
	}


}
?>
