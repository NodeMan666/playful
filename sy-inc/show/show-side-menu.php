<?php 
if(!empty($_REQUEST['sweetness'])) { 
	$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['sweetness']."'  ");
} else if($date['date_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$date['date_id']."' AND enabled='1' ");
} else if(($bcat['cat_id'] > 0) && ($date['date_id'] <=0)==true)  { 
	$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$bcat['cat_id']."' AND enabled='1' ");
}
?>
<?php if(($show['show_id'] > 0)&&($show['enable_side'] == "1")==true) { ?>
<div class="homefeaturerecent" id="homefeaturerecent">
	<div class="inner">
		<div id="homefeature">
		<?php if($dshow['text_placement'] == "side") { ?><div class="sidepadding"><?php print $date['date_text']; ?></div><?php  } ?>
		<?php if(($date['date_id'] > 0)&& ($date['page_home'] !== "1")==true) { 
			// showsubgalshome($show['feat_page_id']); 
		} ?>
		<div id="sidefeaturepages">

		<?php 
		// $show['feat_side_cats'] = $date['date_feature_cat'];
		$time = strtotime("".$site_setup['time_diff']." hours");
		$cur_time =date('Y-m-d H:i:s', $time);
		if(!empty($show['feat_side_cats'])) { 
			$show['feat_side_cats'] = str_replace("Array","",$show['feat_side_cats']);

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
		}

			$dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND page_under='0'  AND CONCAT(date_date, ' ', date_time)<='$cur_time' AND private<='1' AND date_public='1'  AND date_cat!='0'  AND cat_password='' AND (date_expire='0000-00-00' OR date_expire >='".date("Y-m-d")."') $and_where ORDER BY date_date DESC, date_time DESC ");
		$total_side = mysqli_num_rows($dates);
		$total_pages = ceil($total_side / 10);

		?>
		<?php if((!empty($show['feat_side_title']))||(!empty($show['feat_side_text']))==true) { ?>
		<div class="sidepadding sidefeattitle sidefeattext">
		<?php if(!empty($show['feat_side_title'])) { ?><h2><?php print $show['feat_side_title'];?></h2><?php } ?>
		<?php if(!empty($show['feat_side_text'])) { ?><?php print nl2br($show['feat_side_text']);?><?php } ?>
		</div>
		<?php } ?>
		<?php if($show['sm_find_photos'] == "1") { ?>
		<div class="smfindphotos" onclick="findphotos(); return false;"><?php print _view_my_photos_;?></div>
		<?php } ?>
		<?php
			//if($date['date_id'] <=0 ) { 
				showrecentitemshome(); 
	//	}?>
		<?php 
			if($date['date_id'] > 0) { 
				showsubgalshome($date['date_id']);
		}
		?>
		
		</div>
		<div id="siderecentpagesmarker" curpage="1" total="<?php print $total_side;?>" show="<?php print MD5($show['show_id']);?>" pages="<?php print $total_pages;?>"></div>
		<script>
		$(document).ready(function(){
			sideitemsposition();
			sizecatphoto('featsidemenuitem');
		});
		</script>
		<?php // homepagetags($side);?>
		</div>
		</div>

</div>
<?php } ?>
