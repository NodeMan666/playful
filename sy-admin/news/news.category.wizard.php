<?php
define("cat_table", "ms_blog_categories");
?>
<?php if((empty($_REQUEST['cat_id']))&&($_REQUEST['cat_under'] <=0) ==true)  { $top_section = true;  } ?>
<?php if((!empty($_REQUEST['cat_id']))&&($cat['cat_under'] == "0" ) ==true) { $top_section = true;  } ?>

<?php 	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); ?>
<div id="pageTitle"><a href="index.php?do=news">Site Content</a> <?php print ai_sep;?> 
<?php if(empty($cat['cat_id'])) { ?>
 New Section Wizard
<?php } else { ?>
  Edit <?php if($top_section == true) { print "Section"; } else { print "Category"; } ?>:  <?php print $cat['cat_name'];?>
<?php } ?>
</div>

<?php 
if($_POST['submitit']=="yes") { 

	if(is_array($_REQUEST['e_tags'])) { 
		foreach($_REQUEST['e_tags'] AS $id => $val) {
			$x++;
			$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$id."' ");
			if($x > 1) { 
				$tag_keys .= ",".$tag['id'];
			} else { 
				$tag_keys .= $tag['id'];
			}
		//	insertSQL("ms_tag_connect", "tag_tag_id='".$id."', tag_date_id='".$date['date_id']."' ");
		}
	}

	if($_REQUEST['cat_under'] > 0) {
		$up_folder = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_under']."' ");
		if(empty($up_folder['cat_under_ids'])) {
			$cat_under_ids = ("".$up_folder['cat_id']."");
		} else {
			$cat_under_ids = ("".$up_folder['cat_under_ids'].",".$up_folder['cat_id']."");
		}
	}

	if($_REQUEST['section_id'] !== "wiz-clientgals") { 
		$_REQUEST['cat_expire_days'] = "0";
	}
	if($_REQUEST['section_id'] == "wiz-clientgals") { 
		$_REQUEST['cat_private_button'] = "1";
	}
	if($_REQUEST['section_id'] == "wiz-buy") { 
		$_REQUEST['cat_order_by'] = "pageorder";
	} else { 
		$_REQUEST['cat_order_by'] = "date";
	}
	$_REQUEST['cat_default_status'] = "2";

	if($_REQUEST['section_id'] == "wiz-proofing") { 
		$_REQUEST['cat_default_private'] = "2";
		$_REQUEST['cat_default_status'] = "1";
	}
	if($_REQUEST['section_id'] == "wiz-registry") { 

$cat_reg_amounts = $_REQUEST['cat_reg_amounts'];
$cat_reg_default_text = "Welcome to our registry! If you wish to give us a gift, we would love it to be for our photography so we can cherish these memories in photos and artwork forever.";
$cat_text_under_subs = "To give a gift to this registry, select an amount below and optionally leave a message to be shown in the guestbook below.";

	}


	if(empty($_REQUEST['cat_id'])) { 
		$cat_id = insertSQL("".cat_table."", "cat_name='".addslashes(stripslashes($_REQUEST['cat_name']))."' , cat_text='".addslashes(stripslashes($_REQUEST['cat_text']))."' , cat_text_under_content='".addslashes(stripslashes($_REQUEST['cat_text_under_content']))."' , cat_text_under_subs='".addslashes(stripslashes($cat_text_under_subs))."' , cat_billboard='".$_REQUEST['cat_billboard']."' , cat_status='1' , cat_under='".$_REQUEST['cat_under']."', cat_under_ids='".$cat_under_ids."', cat_password='".addslashes(stripslashes($_REQUEST['cat_password']))."', cat_no_show='".addslashes(stripslashes($_REQUEST['cat_no_show']))."', cat_no_show_posts='".addslashes(stripslashes($_REQUEST['cat_no_show_posts']))."' , cat_theme='".addslashes(stripslashes($_REQUEST['cat_theme']))."', cat_list_sub_cat_posts='".$_REQUEST['cat_list_sub_cat_posts']."', cat_billboard_posts='".$_REQUEST['cat_billboard_posts']."', cat_type='".$_REQUEST['cat_type']."', cat_pic_tags='$tag_keys' , cat_show_sub_cats_page='1', cat_layout='".$_REQUEST['cat_layout']."' , cat_page_layout='".$_REQUEST['cat_page_layout']."', cat_cat_layout='3', cat_per_page='".$_REQUEST['cat_per_page']."', cat_watermark='".$_REQUEST['cat_watermark']."' , cat_logo='".$_REQUEST['cat_logo']."', cat_order='".$_REQUEST['cat_order']."', cat_comments='".$_REQUEST['cat_comments']."', cat_next_prev='".$_REQUEST['cat_next_prev']."', cat_expire_days='".$_REQUEST['cat_expire_days']."', cat_show_title='1', cat_private_button='".$_REQUEST['cat_private_button']."', cat_order_by='".$_REQUEST['cat_order_by']."', cat_default_private='".$_REQUEST['cat_default_private']."', cat_default_status='".$_REQUEST['cat_default_status']."', cat_eb_days='14', cat_reg_amounts='".$cat_reg_amounts."', cat_reg_no_address='1', cat_reg_default_text='".$cat_reg_default_text."' ");
		createCategory($cat_id);

		if($_REQUEST['thumb_style'] == "1") { 
			$thumb_file = "thumb_file='pic_th', ";
		} else { 
			$thumb_file = "thumb_file='pic_pic', ";
		}
		if($_REQUEST['thumb_style'] == "2") { 
			$add_stacked = "stacked_width='300', stacked_margin='4', disable_filename='1', ";

		}


		if($_REQUEST['section_id'] == "wiz-clientgals") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='gallery',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				$thumb_file
				$add_stacked
				transition_time='600',
				blog_photo_file='pic_large',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='".$def['slideshow_fixed_height']."',
				thumb_scroller_open='".$def['thumb_scroller_open']."',
				social_share='".$_REQUEST['photos_social_share']."',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='1', 
				enable_compare='1',

				def_cat_id='".$cat_id."' ");	
			
		}

		if($_REQUEST['section_id'] == "wiz-proofing") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='gallery',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				thumb_file='pic_th',
				stacked_width='300', stacked_margin='4', disable_filename='0',

				transition_time='600',
				blog_photo_file='pic_large',
				bg_use='".$def['bg_use']."',
				disable_play_pause='1',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='1',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='".$def['slideshow_fixed_height']."',
				thumb_scroller_open='".$def['thumb_scroller_open']."',
				social_share='".$_REQUEST['photos_social_share']."',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',
				disable_icons='0',

				def_cat_id='".$cat_id."' ");	
			
		}

		if($_REQUEST['section_id'] == "wiz-blog") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='standardlist',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				caption_location='1',
				transition_time='600',
				blog_photo_file='pic_large',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='".$def['slideshow_fixed_height']."',
				thumb_scroller_open='".$def['thumb_scroller_open']."',
				social_share='".$_REQUEST['photos_social_share']."',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',

				def_cat_id='".$cat_id."' ");	
			
		}
		
			if($_REQUEST['section_id'] == "wiz-albums") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='nextprevious',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				caption_location='0',
				transition_time='600',
				blog_photo_file='pic_large',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='1',
				thumb_scroller_open='0',
				social_share='0',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',
				blog_slideshow_auto_start='1',
				def_cat_id='".$cat_id."' ");	
			
		}
	
		if($_REQUEST['section_id'] == "wiz-store") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='onpagewithminis',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				caption_location='0',
				transition_time='600',
				blog_photo_file='pic_pic',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='1',
				thumb_scroller_open='0',
				social_share='1',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',

				def_cat_id='".$cat_id."' ");	
			
		}

		if($_REQUEST['section_id'] == "wiz-registry") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='nextprevious',
				blog_contain='0',
				blog_seconds='5',
				blog_slideshow_auto_start='1',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				caption_location='0',
				transition_time='1000',
				disable_controls='1',
				blog_photo_file='pic_pic',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='1',
				thumb_scroller_open='0',
				social_share='0',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',

				def_cat_id='".$cat_id."' ");	
			
		}

		if($_REQUEST['section_id'] == "wiz-buy") { 
			insertSQL("ms_defaults", "
				blog_location='".$def['blog_location']."',
				def_type='onpagewithminis',
				blog_contain='0',
				blog_seconds='4',
				blog_enlarge='0',
				blog_kill_side_menu='0',
				caption_location='0',
				transition_time='600',
				blog_photo_file='pic_pic',
				bg_use='".$def['bg_use']."',
				disable_play_pause='".$def['disable_play_pause']."',
				disable_next_previous='".$def['disable_next_previous']."',
				disable_photo_slider='".$def['disable_photo_slider']."',
				thumb_style='".$_REQUEST['thumb_style']."',
				thumb_type='".$def['thumb_type']."',
				max_photo_display_width='".$def['max_photo_display_width']."',
				slideshow_fixed_height='1',
				thumb_scroller_open='0',
				social_share='1',
				jthumb_height='250',
				jthumb_margin='4',
				allow_favs='0',

				def_cat_id='".$cat_id."' ");	
			
		}
		updateSiteMap();
		session_write_close();
		header("location: index.php?do=news&action=newwiz&cat_id=$cat_id");
		exit();

	} else {
		$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
		updateSQL("".cat_table."", " cat_name='".addslashes(stripslashes($_REQUEST['cat_name']))."' , cat_text='".addslashes(stripslashes($_REQUEST['cat_text']))."', cat_text_under_content='".addslashes(stripslashes($_REQUEST['cat_text_under_content']))."', cat_text_under_subs='".addslashes(stripslashes($_REQUEST['cat_text_under_subs']))."'   , cat_billboard='".$_REQUEST['cat_billboard']."' , cat_status='".$_REQUEST['cat_status']."', cat_password='".addslashes(stripslashes($_REQUEST['cat_password']))."', cat_no_show='".addslashes(stripslashes($_REQUEST['cat_no_show']))."', cat_no_show_posts='".addslashes(stripslashes($_REQUEST['cat_no_show_posts']))."' , cat_theme='".addslashes(stripslashes($_REQUEST['cat_theme']))."', cat_list_sub_cat_posts='".$_REQUEST['cat_list_sub_cat_posts']."'  , cat_billboard_posts='".$_REQUEST['cat_billboard_posts']."', cat_type='".$_REQUEST['cat_type']."', cat_pic_tags='$tag_keys' , cat_show_sub_cats_page='1' , cat_layout='".$_REQUEST['cat_layout']."', cat_page_layout='".$_REQUEST['cat_page_layout']."' , cat_per_page='".$_REQUEST['cat_per_page']."', cat_watermark='".$_REQUEST['cat_watermark']."', cat_logo='".$_REQUEST['cat_logo']."', cat_order='".$_REQUEST['cat_order']."' , cat_comments='".$_REQUEST['cat_comments']."', cat_next_prev='".$_REQUEST['cat_next_prev']."', cat_expire_days='".$_REQUEST['cat_expire_days']."', cat_show_title='".$_REQUEST['cat_show_title']."'  WHERE cat_id='".$cat['cat_id']."' ");
		$_SESSION['sm'] = "Category updated";
		session_write_close();
		header("location: index.php?do=news&action=editCategory&cat_id=".$cat['cat_id']."");
		exit();

	}

	exit();
}
if((!empty($_REQUEST['cat_id']))AND(empty($_REQUEST['submitit']))==true) {
	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); 
	if(empty($cat['cat_id'])) {
		showError("Sorry, but there seems to be an error.");
	}
	foreach($cat AS $id => $value) {
		if(!is_numeric($id)) {
			$_REQUEST[$id] = $value;
		}
	}
}
if((!empty($_REQUEST['cat_under']))AND(empty($_REQUEST['cat_id']))==true) { 
	$up_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_under']."' ");
	if(!empty($up_cat['cat_under_ids'])) { 
		$scats = explode(",",$up_cat['cat_under_ids']);
		$top_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scats[0]."' ");
		$cat['cat_type'] = $top_cat['cat_type'];
		$cat_layout = $top_cat['cat_layout'];
		$cat_page_layout= $top_cat['cat_page_layout'];
		$cat['cat_per_page'] = $top_cat['cat_per_page'];
	} else { 
		$cat['cat_type'] = $up_cat['cat_type'];
		$cat_layout = $up_cat['cat_layout'];
		$cat_page_layout= $up_cat['cat_page_layout'];
		$cat['cat_per_page'] = $up_cat['cat_per_page'];
	}
}

if((!empty($_REQUEST['cat_under']))==true) { 
	$up_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_under']."' ");
	if(!empty($up_cat['cat_under_ids'])) { 
		$scats = explode(",",$up_cat['cat_under_ids']);
		$top_cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scats[0]."' ");
		$cat_layout = $top_cat['cat_layout'];
		$cat_page_layout= $top_cat['cat_page_layout'];
	} else { 
		$cat_layout = $up_cat['cat_layout'];
		$cat_page_layout= $up_cat['cat_page_layout'];
	}
}

?>
<?php if($_SESSION['cat_folder_exists'] == true) { ?>
<div class="pc">
<h1 style="font-size: 30px; color: #890000;">WARNING - Folder Exists</h1>

Your new section has been created, however the folder "<?php print $_SESSION['existing_folder'];?>" already exists on the server. So this section folder was named "<?php print $cat['cat_folder'];?>". This is part of the URL (<?php print $setup['url'].$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder'];?>).
<br><br>
You most likey want to rename this folder. <a href="" onclick="openFrame('w-category-folder.php?cat_id=<?php print $cat['cat_id'];?>'); return false;">Click here to rename this directory.</a>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

<?php 
unset($_SESSION['cat_folder_exists']);
unset($_SESSION['existing_folder']);

} ?>


<style>
.wiz { width: 25%; float: left;  } 
.wiz .wizinner { padding: 12px; border: solid 1px #d4d4d4; background: #f4f4f4; margin: 8px; cursor: pointer;  height: 160px;} 

.wiz .wizinner:hover {  border: solid 1px #a4b4f4; background: #FFFFFF;  } 
.wiz .wizinnerselected {  border: solid 1px #a4b4f4; background: #FFFFFF;  box-shadow: 0px 0px 6px #444444; } 
.wizoptions { display: none; } 
</style>
<script>
	function selectwiz(s) { 
		$(".wizinner").removeClass("wizinnerselected");
		$("#wiz-"+s).addClass("wizinnerselected");
		$("#cat_name").val($("#wiz-"+s).attr("sectionname"));
		$("#cat_layout").val($("#wiz-"+s).attr("cat_layout"));
		$("#cat_page_layout").val($("#wiz-"+s).attr("cat_page_layout"));
		$("#cat_type").val($("#wiz-"+s).attr("cat_type"));
		$("#cat_per_page").val($("#wiz-"+s).attr("cat_per_page"));
		$("#section_id").val($("#wiz-"+s).attr("id"));
		$(".wizoptions").slideUp();
		$("#submitButton").show();
		$("#options-"+s).slideDown(400);
		$("#sectionnamediv").slideDown(200);
		$("#cat_layout_select").show();
		previewlayout();
	}

	function previewlayout() { 
		$(".layoutpreview").hide(10, function() { 
			$(".L"+$("#cat_layout").val()).show();
		});

	}
</script>

<form method="post" name="catwiz" action="index.php"    onSubmit="return checkForm();">

<div class="pc">This will help you quickly create a new section. Anything you select here can be tweaked / changed at any time.</div>

<div style="width: 49%; float: left;">
	<div style="display: none;" id="sectionnamediv">
	<div class="underlinelabel">Name for this section</div>
	<div class="pc"><input type="text" id="cat_name" name="cat_name" value="<?php print $cat['cat_name'];?>" class="inputtitle field100 required" style="background: #ffff99;"></div>
	</div>
	<div class="pc center">
	<input type="submit" name="submit" value="Create New Section Now" id="submitButton" style="display: none;" class="submit" style="font-size: 21px;">
	</div>

	<div id="cat_layout_select" class="hide">
	<div class="pc center"><h3>Select the page listing layout</h3></div>
	<div class="pc center">The page listing layout controls the layout / look when displaying pages in this section. Below is a preview of the layout, but the exact colors, fonts, etc ... depend on the theme you are using. This can be changed at any time by editing this section.</div>
	<div class="center pc">
	<select name="cat_layout" id="cat_layout" onchange="previewlayout();">
	<?php $layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
	while($layout = mysqli_fetch_array($layouts)) { ?>
	<option value="<?php print $layout['layout_id'];?>"><?php print $layout['layout_name'];?></option>
	<?php } ?>
	</select>
	</div>
		<div class="pc center">
		<img src="graphics/layouts/stacked.jpg" style="max-width: 100%;" class="layoutpreview L102 hide">
		<img src="graphics/layouts/blog-1-photo.jpg" style="max-width: 100%;" class="layoutpreview L21 hide">
		<img src="graphics/layouts/onphoto.jpg" style="max-width: 100%;" class="layoutpreview L3 hide">
		<img src="graphics/layouts/product-list-with-add-to-cart.jpg" style="max-width: 100%;" class="layoutpreview L18 hide">
		<img src="graphics/layouts/standard.jpg" style="max-width: 100%;" class="layoutpreview L1 hide">
		<img src="graphics/layouts/thumbnails.jpg" style="max-width: 100%;" class="layoutpreview L4 hide">
		<img src="graphics/layouts/thumbnails.jpg" style="max-width: 100%;" class="layoutpreview L19 hide">
		<img src="graphics/layouts/title-only.jpg" style="max-width: 100%;" class="layoutpreview L100 hide">
		</div>
	</div>
</div>

<div style="width: 49%; float: right;">


	<div class="wizoptions" id="options-clientgals">
		<div class="underlinelabel">Options for Client Galleries / Selling Photos</div>
		<div class="underlinespacer">These options can be changed at any time</div>
		<div class="underline">
			Automatically expire galleries after <input type="text" name="cat_expire_days" id="cat_expire_days" value="30" size="2"> days. If you don't want galleries to expire, change to 0.
		</div>
		<div class="underline"><input name="photos_social_share" value="1" type="checkbox"> Enable social share of photos</div>


		<div class="underline">Display thumbnails: <br><br>
<div style="float: left; width: 49%;" class="center">
		<div class="pc"><input name="thumb_style" id="thumbtypejustified" value="0"  type="radio">	Justified</div>
		<div class="pc"><img src="graphics/thumbs-justified.jpg" border="0" width="250"></div>
	</div>
	<div style="float: left; width: 49%;" class="center">
		<div class="pc"><input name="thumb_style" id="thumbtypestacked" value="2" type="radio" checked>	Stacked</div>
		<div class="pc"><img src="graphics/thumbs-stacked.jpg" width="250" border="0"></div>
	</div>
	<div class="clear"></div>
	<div style="float: left; width: 49%;" class="center">
		<div class="pc"> <input name="thumb_style" id="thumbtypestandard" value="1"  type="radio">	Standard thumbnails</div>
		<div class="pc"><img src="graphics/thumbs-standard.jpg" border="0" width="250"></div>
	</div>
	<div class="clear"></div>



	</div>




</div>

</div>
<div class="clear"></div>

<div class="underlinelabel">Select what type of section you are wanting to create</div>

<div class="wiz">
	<div class="wizinner" onclick="selectwiz('clientgals');" id="wiz-clientgals" sectionname="Clients" cat_type="clientphotos" cat_layout="102" cat_page_layout="22" cat_per_page="24">
		<div><h2>Client Galleries / Selling Photos</h2></div>
		<div>A section for galleries to upload and sell photos.</div>
	</div>
</div>

<div class="wiz">
	<div class="wizinner" onclick="selectwiz('albums');" id="wiz-albums" sectionname="Galleries" cat_type="standard" cat_layout="3" cat_page_layout="23" cat_per_page="24">
		<div><h2>Photo Galleries</h2></div>
		<div>A section for sample photo galleries to feature your photos. This option does not have photo selling features.</div>
	</div>
</div>


<div class="wiz">
	<div class="wizinner" onclick="selectwiz('standard');" id="wiz-standard" sectionname="Info" cat_type="standard" cat_layout="1" cat_page_layout="9" cat_per_page="20">
		<div><h2>Standard Pages</h2></div>
		<div>Standard pages within the section.</div>
	</div>
</div>


<div class="wiz">
	<div class="wizinner" onclick="selectwiz('store');" id="wiz-store" sectionname="Store" cat_type="store" cat_layout="19" cat_page_layout="11" cat_per_page="24">
		<div><h2>Store</h2></div>
		<div>A section to sell physical products, downloads or services (not photos).</div>
	</div>
</div>
<div class="clear"></div>



<div class="wiz">
	<div class="wizinner" onclick="selectwiz('blog');" id="wiz-blog" sectionname="Blog" cat_type="standard" cat_layout="21" cat_page_layout="10" cat_per_page="24">
		<div><h2>Blog</h2></div>
		<div>Create a blog section to post photos, news, updates, etc...</div>
	</div>
</div>

<div class="wiz">
	<div class="wizinner" onclick="selectwiz('buy');" id="wiz-buy" sectionname="Buy" cat_type="store" cat_layout="18" cat_page_layout="11" cat_per_page="24">
		<div><h2>Buy Page</h2></div>
		<div>A section to list items for sale that can be added to cart from the listing.</div>
	</div>
</div>

<div class="wiz">
	<div class="wizinner" onclick="selectwiz('proofing');" id="wiz-proofing" sectionname="Proofing" cat_type="proofing" cat_layout="3" cat_page_layout="23" cat_per_page="24">
		<div><h2>Project Proofing</h2></div>
		<div>A section to create pages and upload photos for your clients to approve or request revisions for designs. This section is not for selling photos.</div>
	</div>
</div>


<div class="wiz">
	<div class="wizinner" onclick="selectwiz('registry');" id="wiz-registry" sectionname="Registry" cat_type="registry" cat_layout="100" cat_page_layout="101" cat_per_page="24">
		<div><h2>Registry</h2></div>
		<div>A registry section like a wedding registry where people can add money to customers' accounts.</div>
	</div>
</div>


<div class="clear"></div>
<div>&nbsp;</div>

<div>&nbsp;</div>
<textarea name="cat_reg_amounts" id="cat_reg_amounts" rows="30" cols="20" style="display: none;">
10
20
25
50
75
100
125
150
175
200
250
300
350
400
450
500</textarea>
<input type="hidden" name="cat_id" value="<?php print $_REQUEST['cat_id'];?>">
<input type="hidden" name="do" value="news">
<input type="hidden" name="action" value="editCategory">
<input type="hidden" name="submitit" value="yes">
<input type="hidden" name="cat_page_layout" id="cat_page_layout" value="">
<input type="hidden" name="cat_type" id="cat_type" value="">
<input type="hidden" name="cat_per_page" id="cat_per_page" value="">
<input type="hidden" name="section_id" id="section_id" value="">

</form>













<script>
function showCatTags() { 
	if($("#cat_type_stock").is(":checked")) { 
		$("#catTags").fadeIn(100);
	} else { 
		$("#catTags").fadeOut(100);
	}
}

function reqLayout() { 
	if($("#cat_under").val() == "0") { 
		$("#plor").hide();
		$("#cat_layout option").eq(0).text("Select");
		$("#cat_layout").addClass("required");

	
		$("#pdor").hide();
		$("#cat_page_layout option").eq(0).text("Select");
		$("#cat_page_layout").addClass("required");
	
	} else { 
		$("#plor").show();
		$("#cat_layout option").eq(0).text("Use top level selection");
		$("#cat_layout").removeClass("required");

		$("#pdor").show();
		$("#cat_page_layout option").eq(0).text("Use top level selection");
		$("#cat_page_layout").removeClass("required");

	}



}


</script>




<div class="clear"></div>
<?php

function createCategory($cat_id) {
	global $site_setup,$setup;
	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$cat_id."' ");
	$cat_id = $cat['cat_id'];
	$page_link = stripslashes(trim(strtolower($cat['cat_name'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);

	if($cat['cat_under'] > 0) {
		$up_folder = doSQL("".cat_table."", "*", "WHERE cat_id='".$cat['cat_under']."' ");
		$page_link = "".$up_folder['cat_folder']."/".$page_link;
	} else { 
		$page_link = "/".$page_link;
	}

	if(file_exists($setup['path']."".$setup['content_folder']."/".$page_link)) {
		$_SESSION['existing_folder'] = $page_link;
		$page_link = $page_link."".$site_setup['sep_page_names']."".$cat['cat_id']."";
		$_SESSION['cat_folder_exists'] = true;
	}



	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
//	print "<li>A";
} elseif($parent_permissions == "0777") {
	$perms = 0777;
//	print "<li>B";
} else {
		$perms = 0755;
//	print "<li>C";
}
//print "<li>$parent_permissions<li>$page_link<li>$perms<li>";

	mkdir("".$setup['path']."".$setup['content_folder']."/$page_link", $perms);
	chmod("".$setup['path']."".$setup['content_folder']."/$page_link", $perms);
	updateSQL("".cat_table."", "cat_folder='".$page_link."' WHERE cat_id='".$cat_id."' ");
	print "Create: ".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php";
//	copy("".$setup['path']."".$setup['content_folder']."/default.php", "".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php");

	$fp = fopen("".$setup['path']."".$setup['content_folder']."/".$page_link."/index.php", "w");
	$add_path .="../";

	if(!empty($cat['cat_under_ids'])) { 
		$ids = explode(",",$cat['cat_under_ids']);
		foreach($ids AS $num) { 
			$add_path .="../";
		}
		$info =  "<?php\n\$date_cat_id = $cat_id; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/main_index_include.php\";\n?>"; 

	} else { 
		$info =  "<?php\n\$date_cat_id = $cat_id; \n\$to_path = \"$add_path\"; \ninclude \"".$add_path."".$setup['inc_folder']."/main_index_include.php\";\n?>"; 
	}
	fputs($fp, "$info\n");
	fclose($fp);

//	exit();

}
?>

<?php

function getMultiCats($fn, $match) {
	global $dbcon;
	print "<select name=\"$fn\" id=\"$fn\" onChange=\"reqLayout();\">";
//	print "<option value=\"0\">Top Level";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	print "<option value=\"".$type["cat_id"]."\" $selected class=fl style=\"font-weight: bold;\">".$type["cat_name"]."";
	unset($selected);
		$parent_id = $type["cat_id"];
			getSubsDrop2($fn, $match, $parent_id, $level, $sec_under);
	}
	print "</select>";
}

function getSubsDrop2($fn, $match, $parent_id, $level, $sec_under) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ".cat_table." WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {
		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];

		?>
		<option  value="<?php  print "$sub_sec_id"; ?>" <?php  if ($match == $sub_sec_id) { print "selected"; } ?>> <?php  
			while($dashes < $level) {
			print "----";
			$dashes++;
		}
		$dashes = 0;
		print "$sub_sec_name"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ".cat_table." WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent_id = $sub_sec_id;
			getSubsDrop2($fn, $match, $parent_id, $level, $sec_under);
		}
	}
		$level = 1;
}
?>