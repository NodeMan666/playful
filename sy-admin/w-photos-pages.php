<?php require "w-header.php"; ?>
<?php 


define("cat_table", "ms_blog_categories"); 
define("cat_connect_table", "ms_blog_cats_connect"); 
define("items_table", "ms_calendar");
define("items_id", "date_id"); 
define("items_cat_field", "date_cat"); 
define("this_do", "news"); 
define("folder", $setup['content_folder']); 
define("cat_field_url", "date_cat"); 
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}

$num_pics = countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' "); 

if($_REQUEST['action'] == "createpages") { 

	foreach($_SESSION['heldPhotos'] AS $photo) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$photo."' ");		
		$x++;
		print "<li>".$pic['pic_id']." - ".$_REQUEST['date_title'][$x];
			$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['date_cat']."' ");
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");

			if(empty($def['def_id'])) { 
				$def = doSQL("ms_defaults", "*", "ORDER BY def_id DESC");
			}
			$password = substr(md5(date('ymdHis')),0,8);
			if($_REQUEST['date_cat'] <= 0) { 
				$cat['cat_default_status'] = 1;
			}
			$date_date = date('Y-m-d');
			$date_time = date("H:i:s", mktime(date('H'), date('i'), date('s')-$x, date('m') , date('d'), date('Y')));
			$time_now= date('Y-m-d H:i:s');

			$id = insertSQL("ms_calendar", "date_title='".addslashes(stripslashes($_REQUEST['date_title'][$x]))."', date_text='".addslashes(stripslashes(nl2br($_REQUEST['date_text'][$x])))."', date_public='".$cat['cat_default_status']."', date_type='news' , date_where='".$_REQUEST['date_where']."', date_address='".$_REQUEST['date_address']."', date_hp_limit='".$_REQUEST['date_hp_limit']."' , date_date='".$date_date."', date_time='".$date_time."'   , date_snippet='".$_REQUEST['date_snippet']."'  , date_cat='".$_REQUEST['date_cat']."'  , date_cat2='".$_REQUEST['date_cat2']."' , date_cat3='".$_REQUEST['date_cat3']."' , date_cat4='".$_REQUEST['date_cat4']."' ,  date_aff_link='".$_REQUEST['date_aff_link']."', date_aff_text='".$_REQUEST['date_aff_text']."' , private='".$cat['cat_default_private']."', password='".$password."', 
			last_modified='".$time_now."'
			, external_link='".$_REQUEST['external_link']."'
			, page_form='".$_REQUEST['page_form']."' 
			, page_home='".$_REQUEST['page_home']."'
			, page_under='".$_REQUEST['page_under']."'
			, page_use_subpage='".$_REQUEST['page_use_subpage']."'
			, page_title_show='".$_REQUEST['page_title_show']."'
			, page_title_no_show='".$_REQUEST['page_title_no_show']."'
			, page_keywords='".addslashes(stripslashes($_REQUEST['page_keywords']))."'
			, page_list_sub_pages='1'
			, page_billboard='".$_REQUEST['page_billboard']."'
			, page_disable_drop='".$_REQUEST['page_disable_drop']."'
			, page_gallery='".$_REQUEST['page_gallery']."'
			, page_snippet='".$_REQUEST['page_snippet']."'
			, page_disable_fb='".$_REQUEST['page_disable_fb']."'
			, disable_comments='".$_REQUEST['disable_comments']."'
			,date_embed='".$_REQUEST['date_embed']."'
			,date_embed_contain='".$_REQUEST['date_embed_contain']."'
			,date_aff_site='".$_REQUEST['date_aff_site']."'
			,page_layout='".$_REQUEST['page_layout']."'

			,page_theme='".$_REQUEST['page_theme']."',
			blog_location='".$def['blog_location']."',
			blog_type='".$def['def_type']."',
			blog_contain='".$def['blog_contain']."',
			blog_seconds='".$def['blog_seconds']."',
			blog_enlarge='".$def['blog_enlarge']."',
			blog_kill_side_menu='".$def['blog_kill_side_menu']."',
			blog_progress_bar='".$def['blog_progress_bar']."',
			blog_next_prev='".$def['blog_next_prev']."',
			blog_play_pause='".$def['blog_play_pause']."',
			blog_slideshow='".$def['blog_slideshow']."',
			blog_slideshow_auto_start='".$def['blog_slideshow_auto_start']."',
			disable_controls='".$def['disable_controls']."',
			caption_location='".$def['caption_location']."',
			contain_width='".$def['contain_width']."',
			contain_height='".$def['contain_height']."',
			noupsize='".$def['noupsize']."',
			blog_frame='".$def['blog_frame']."',
			disable_thumbnails='".$def['disable_thumbnails']."',
			disable_help='".$def['disable_help']."',
			disable_animation_bar='".$def['disable_animation_bar']."',
			disable_photo_count='".$def['disable_photo_count']."',
			transition_time='".$def['transition_time']."',
			blog_photo_file='".$def['blog_photo_file']."',
			bg_use='".$def['bg_use']."',
			disable_play_pause='".$def['disable_play_pause']."',
			disable_next_previous='".$def['disable_next_previous']."',
			disable_photo_slider='".$def['disable_photo_slider']."',
			thumb_style='".$def['thumb_style']."',
			thumb_type='".$def['thumb_type']."',
			slideshow_fixed_height='".$def['slideshow_fixed_height']."',
			thumb_scroller_open='".$def['thumb_scroller_open']."',
			photo_social_share='".$def['social_share']."',
			jthumb_height='".$def['jthumb_height']."',
			jthumb_margin='".$def['jthumb_margin']."',
			thumbactions='".$def['thumbactions']."',
			allow_favs='".$def['allow_favs']."',
			disable_filename='".$def['disable_filename']."',
			photo_search='".$def['photo_search']."',
			enable_compare='".$def['enable_compare']."',
			reg_person='".$_REQUEST['reg_person']."',

			thumb_open_first='".$def['thumb_open_first']."',
			thumb_file='".$def['thumb_file']."',
			add_style='".$def['add_style']."',
			disable_icons='".$def['disable_icons']."', 
			stacked_width='".$def['stacked_width']."', 
			stacked_margin='".$def['stacked_margin']."' ,

			date_expire='".$_REQUEST['date_expire']."',
			feature_row_1 = '".$feature_row_1."',
			feature_row_2 = '".$feature_row_2."',
			feature_row_3 = '".$feature_row_3."',
			date_feature_title='".$_REQUEST['date_feature_title']."',
			date_feature_text='".$_REQUEST['date_feature_text']."',

			page_include_page='".$_REQUEST['page_include_page']."',
			date_disable_side='".$_REQUEST['date_disable_side']."',
			max_photo_display_width='".$def['max_photo_display_width']."',
			thumb_width='".($photo_setup['blog_th_width'] + 30)."',
			prod_inventory_control='".$_REQUEST['prod_inventory_control']."',
			date_feature_cat='".$date_feature_cats."',
			date_feature_limit='".$_REQUEST['date_feature_limit']."',
			date_feature_layout='".$_REQUEST['date_feature_layout']."',
			audio_file='".$_REQUEST['audio_file']."',
			video_file='".$_REQUEST['video_file']."',
			prod_photos='".$_REQUEST['prod_photos']."',
			prod_order_message='".$_REQUEST['prod_order_message']."',
			date_credit='".$_REQUEST['date_credit']."',
			shipping_group='".$cat['shipping_group']."',

			prod_price='".$_REQUEST['prod_price']."', prod_qty='".$_REQUEST['prod_qty']."', prod_cost='".$_REQUEST['prod_cost']."', prod_create_reg_key='".$_REQUEST['prod_create_reg_key']."', prod_sep_order_email='".$_REQUEST['prod_sep_order_email']."', prod_dl_name='".$_REQUEST['prod_dl_name']."', prod_type='".$_REQUEST['prod_type']."',  prod_download_descr='".$_REQUEST['prod_download_descr']."', prod_version='".$_REQUEST['prod_version']."', prod_cat='".$_REQUEST['prod_cat']."', prod_prod_id='".$_REQUEST['prod_prod_id']."', prod_sale_price='".$_REQUEST['prod_sale_price']."', prod_shipping='".$_REQUEST['prod_shipping']."', prod_add_ship='".$_REQUEST['prod_add_ship']."', prod_taxable='".$_REQUEST['prod_taxable']."', prod_no_link='".$_REQUEST['prod_no_link']."', prod_max_qty='".$_REQUEST['prod_max_qty']."', prod_sale_end='".$_REQUEST['prod_sale_end']."', prod_sale_start='".$_REQUEST['prod_sale_start']."' , prod_sale_message='".$_REQUEST['prod_sale_message']."' ,
			date_photo_price_list='".$_REQUEST['date_photo_price_list']."'




    ");   		
			$_REQUEST['date_id'] = $id;
		$photo_setup = doSQL("ms_photo_setup", "*", "  ");
		$date = doSQL("ms_calendar", "*", "WHERE date_id='$id' ");
		createNewPage($id);
		insertSQL("ms_blog_photos", "bp_pic='".$pic['pic_id']."', bp_blog='".$id."' ");
		
	$ekeys = array();
		if(!empty($_REQUEST['date_tags'])) { 
			$new_tags = explode(",",$_REQUEST['date_tags'][$x]);
			foreach($new_tags AS $tag) { 
				$tag = trim($tag);
				$tag = strtolower($tag);
				if(!in_array($tag,$ekeys)) { 
					$page_keys .= ",$tag";
				}
				if(!empty($tag)) { 
					$cktag = doSQL("ms_tags", "*", "WHERE tag_tag='".$tag."' ");
					if(empty($cktag['tag_id'])) { 
						$tag_keys .= $tag.", ";

						$tag_id = insertSQL("ms_tags", "tag_tag='".addslashes(stripslashes($tag))."' ");
						createTagFolder($tag_id);
					} else { 
						$tag_id = $cktag['tag_id'];
					}
					$ckcon = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag_id."' AND tag_date_id='".$id."' ");
					if(empty($ckcon['id'])) { 
						insertSQL("ms_tag_connect", "tag_tag_id='".$tag_id."', tag_date_id='".$id."' ");
					}
				}
			}
		}
		updateSQL("ms_calendar", "page_keywords='".addslashes(stripslashes($page_keys))."' WHERE date_id='".$_REQUEST['date_id']."'  ");
		unset($page_keys);
	}
	unset($_SESSION['heldPhotos']);
	$_SESSION['sm'] = "Page Created";
	session_write_close();
	header("location: index.php?do=news&date_cat=".$cat['cat_id']."");
	exit();

}

?>
<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Display order updated");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<div class="pc"><h1>Create pages for each individual photo (<?php print count($_SESSION['heldPhotos']);?>)</h1></div>
<div class="pc">This option will create a new page for each photo selected. </div>
<form method="post" name="batchpages" id="batchpages" action="w-photos-pages.php"  onSubmit="return checkForm();">
<div class="underline">
<div class="fieldLabel">Main Section or Category</div>
<div><?php print multiLevelSelect($_REQUEST['date_cat']);?></div>
<input type="hidden" name="old_cat" value="<?php print $_REQUEST['date_cat'];?>">
</div>
	<?php


		foreach($_SESSION['heldPhotos'] AS $photo) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$photo."' ");		
			$size = getimagefiledems($pic,'pic_th');
		unset($kc);
		$pic_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $pic['pic_org']);
		$pic_name = str_replace("-"," ",$pic_name);
		$pic_name = str_replace("_"," ",$pic_name);
		$x++;
	?>

	<div class="underline">
		<div class="left" style="margin-right: 16px;">
		<img id="photoThumb-<?php print $pic['pic_id'];?>-thumb" src="<?php print getimagefile($pic,'pic_mini');?>" border="0"  title="<?php print $pic['pic_org'];?>">
		</div>
		<div class="left"  style="margin-right: 16px;">
			<div>Title</div>
			<div><input type="text" name="date_title[<?php print $x;?>]" id="date_title-<?php print $x;?>" value="<?php print $pic_name;?>" size="30"></div>
			<div>Tags</div>
			<div><input type="text" name="date_tags[<?php print $x;?>]" id="date_tags-<?php print $x;?>" value="<?php 
		$keys= whileSQL("ms_photo_keywords_connect LEFT JOIN ms_photo_keywords ON ms_photo_keywords_connect.key_key_id=ms_photo_keywords.id", "*", "WHERE key_pic_id='".$pic['pic_id']."' ");
		while($key = mysqli_fetch_array($keys)) { 
			if($kc > 0) { print ", "; } print $key['key_word'];
			$kc++;
		} ?>" size="30"></div>

		</div>
		<div class="left">
			<div>Text</div>
			<div><textarea name="date_text[<?php print $x;?>]" id="date_text-<?php print $x;?>" value="<?php print $pic['pic_caption'];?>" rows="4" cols="40" class="field100"></textarea></div>

		</div>
		<div class="clear"></div>
		</div>

		<?php

		  $order[] = $bp_pic['bp_id'];
		}
	  ?>
	<div class="cssClear"></div>
<input type="hidden" name="action" value="createpages">
<div class="pc center"><input type="submit" name="submit" value="Create" class="submit"></div>
</form>
<?php 
function multiLevelSelect($match) {
	global $dbcon;
	$fn = "gal_under";
//	$match = $_REQUEST['gal_under'];
	$html .=  "<select name=\"".items_cat_field."\" id=\"".items_cat_field."\"  onchange=\"selectCategory();\" class=\"required\">";
	$html .=  "<option value=\"\" tyle=\"standard\">SELECT CATEGORY";
	$html .=  "<option value=\"0\" tyle=\"standard\">Top Level Page";

	$resultt = mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' AND cat_type!='registry' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	$html .=  "<option value=\"".$type["cat_id"]."\"  ";  if($type["cat_id"] == $match) { $html .= "selected"; } $html .= " style=\"font-weight: bold;\" type=\"".$type['cat_type']."\">".$type["cat_name"]."</option>";
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];


			$html .= multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent);
	}
	$html .=  "</select>";
	return $html;
}

function multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent) {
	global $dbcon;

	$level++;
	$subs = mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id'  AND cat_type!='registry'  ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


			$html .= "<option  value=\"".$sub_sec_id."\" ";  if($row["cat_id"] == $match) { $html .= "selected"; } $html .= "> "; 
  
		$html .=  "$parent -> $sub_sec_name </option>"; 

		$sub2=mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= multiLevelSelectSubs($fn, $match, $parent_id, $level, $sec_under,$parent);
		}
	}
		$level = 1;
		return $html;
}

function createTagFolder($tag_id) {
	global $site_setup,$setup;
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$tag_id."' ");

	$page_link = stripslashes(trim(strtolower($tag['tag_tag'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);

	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
			$perms = 0755;
		print "<li>C";
	}
	print "<li>$parent_permissions<li>$page_link<li>$perms<li>";
	$add_path .="../";

	if(!is_dir($setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."")) { 
		mkdir("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."", $perms);
		chmod("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."", $perms);
		$fp = fopen("".$setup['path']."".$setup['content_folder']."/".$setup['tags_folder']."/index.php", "w");
		$info =  "<?php\n\$tag_home = true;  \n\$to_path = \"$add_path\";  \ninclude \"../".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
		fputs($fp, "$info\n");
		fclose($fp);
	}


	$date_folder = $setup['content_folder']."/".$setup['tags_folder']."";

	if(file_exists($setup['path']."".$date_folder."/".$page_link)) {
		$page_link = $tag_id."".$site_setup['sep_page_names']."".$page_link;
	}

	mkdir("".$setup['path']."".$date_folder."/$page_link", $perms);
	chmod("".$setup['path']."".$date_folder."/$page_link", $perms);
	updateSQL("ms_tags", "tag_folder='".$page_link."' WHERE tag_id='".$tag_id."' ");
	print "Create: ".$setup['path']."".$date_folder."/".$page_link."/index.php";
//	copy("".$setup['path']."".$date_folder."/default.php", "".$setup['path']."".$date_folder."/".$page_link."/index.php");
	$add_path .="../";

	$fp = fopen("".$setup['path']."".$date_folder."/".$page_link."/index.php", "w");
	$info =  "<?php\n\$tag_id = $tag_id;  \n\$to_path = \"$add_path\"; \ninclude \"../../".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
	fputs($fp, "$info\n");
	fclose($fp);

//	exit();

}


?>

<?php require "w-footer.php"; ?>
