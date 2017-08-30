<?php
define("cat_table", "ms_blog_categories");
?>
<?php if((empty($_REQUEST['cat_id']))&&($_REQUEST['cat_under'] <=0) ==true)  { $top_section = true;  } ?>
<?php if((!empty($_REQUEST['cat_id']))&&($cat['cat_under'] == "0" ) ==true) { $top_section = true;  } ?>

<?php 	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); ?>
<div id="pageTitle"><a href="index.php?do=news">Site Content</a> <?php print ai_sep;?> 
<?php if(empty($cat['cat_id'])) { ?>
 Create New <?php if($top_section == true) { print "Section"; } else { print "Category"; } ?>
<?php } else { ?>
  Edit <?php if($top_section == true) { print "Section"; } else { print "Category"; } ?>:  <?php print $cat['cat_name'];?>
<?php } ?>
</div>

<?php if($_REQUEST['newwiz'] == "1") { ?>
<div>&nbsp;</div>
<div class="pc"><span style="font-size: 34px; color: #12B33F;">Your new section has been created!</span></div>
<div class="pc" style="font-size: 21px; color: #000000;">&larr; You will now see your new section list to the left. There you can edit the details of that section, add a new page, add it to your menu and view it on the website.</div>
<div class="pc" style="font-size: 21px; color: #000000;">What would you like to do now? <br>
<a href="">Add a page to this section</a><br>
<a href="">Edit details of this section</a><br>
<a href="">Create another new section</a><br>
</div>

<div>&nbsp;</div>


<?php } ?>


<?php 
if(!empty($_REQUEST['deleteCategoryThumb'])) { 


	$pic = doSQL("ms_photos ", "*", "WHERE ms_photos.pic_id='".$_REQUEST['pic_id']."' ");
	if(!empty($pic['pic_id'])) {
		if(!empty($pic['pic_folder'])) { 
			$pic_folder = $pic['pic_folder'];
		} else { 
			$pic_folder = $pic['gal_folder'];
		}
		if(countIt("ms_photos", "WHERE pic_folder='".$pic['pic_folder']."' AND pic_th='".$pic['pic_th']."' ")<=1) { 
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th']);
			@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini']);
		}
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_med']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full']);
	}
	deleteSQL("ms_photos", "WHERE pic_id='".$pic['pic_id']."' ", "1" );
	deleteSQL2("ms_blog_photos", "WHERE bp_pic='".$pic['pic_id']."' ");

	$_SESSION['sm'] = "Thumbnail Deleted";
	session_write_close();
	header("location: index.php?do=news&action=editCategory&cat_id=".$_REQUEST['deleteCategoryThumb']." ");
	exit();
}

if($_REQUEST['subdo'] == "galleryexclusiveon") { 
	updateSQL("ms_calendar","date_gallery_exclusive='1' WHERE date_cat='".$_REQUEST['cat_id']."' ");
	updateSQL("ms_blog_categories", "cat_gallery_exclusive='1' WHERE cat_id='".$_REQUEST['cat_id']."' ");
	$_SESSION['sm'] = "Gallery Mode Enabled";
	session_write_close();
	header("location: index.php?do=news&action=editCategory&cat_id=".$_REQUEST['cat_id']." ");
	exit();
}
	
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
	if($_REQUEST['show_first_layout'] <=0) { 
		$_REQUEST['cat_first_layout'] = 0;
	}
	if(empty($_REQUEST['cat_id'])) { 
		$cat_id = insertSQL("".cat_table."", "cat_name='".addslashes(stripslashes($_REQUEST['cat_name']))."' , cat_meta_title='".addslashes(stripslashes($_REQUEST['cat_meta_title']))."' , cat_text='".addslashes(stripslashes($_REQUEST['cat_text']))."' , cat_text_under_content='".addslashes(stripslashes($_REQUEST['cat_text_under_content']))."' , cat_text_under_subs='".addslashes(stripslashes($_REQUEST['cat_text_under_subs']))."' , cat_billboard='".$_REQUEST['cat_billboard']."' , cat_status='".$_REQUEST['cat_status']."' , cat_under='".$_REQUEST['cat_under']."', cat_under_ids='".$cat_under_ids."', cat_password='".addslashes(stripslashes($_REQUEST['cat_password']))."', cat_no_show='".addslashes(stripslashes($_REQUEST['cat_no_show']))."', cat_no_show_posts='".addslashes(stripslashes($_REQUEST['cat_no_show_posts']))."' , cat_theme='".addslashes(stripslashes($_REQUEST['cat_theme']))."', cat_list_sub_cat_posts='".$_REQUEST['cat_list_sub_cat_posts']."', cat_billboard_posts='".$_REQUEST['cat_billboard_posts']."', cat_type='".$_REQUEST['cat_type']."', cat_pic_tags='$tag_keys' , cat_show_sub_cats_page='".$_REQUEST['cat_show_sub_cats_page']."', cat_layout='".$_REQUEST['cat_layout']."' , cat_page_layout='".$_REQUEST['cat_page_layout']."', cat_cat_layout='".$_REQUEST['cat_cat_layout']."', cat_per_page='".$_REQUEST['cat_per_page']."', cat_watermark='".$_REQUEST['cat_watermark']."' , cat_logo='".$_REQUEST['cat_logo']."', cat_order='".$_REQUEST['cat_order']."', cat_comments='".$_REQUEST['cat_comments']."', cat_next_prev='".$_REQUEST['cat_next_prev']."', cat_expire_days='".$_REQUEST['cat_expire_days']."', cat_show_title='".$_REQUEST['cat_show_title']."' , cat_order_by='".$_REQUEST['cat_order_by']."', cat_private_button='".$_REQUEST['cat_private_button']."', cat_private_page='".$_REQUEST['cat_private_page']."', cat_search='".$_REQUEST['cat_search']."', cat_expire_hide='".$_REQUEST['cat_expire_hide']."', cat_content='".$_REQUEST['cat_content']."' , cat_disable_side='".$_REQUEST['cat_disable_side']."', cat_default_private='".$_REQUEST['cat_default_private']."', cat_forum_categories='".addslashes(stripslashes($_REQUEST['cat_forum_categories']))."', cat_default_status='".$_REQUEST['cat_default_status']."', cat_req_login='".$_REQUEST['cat_req_login']."', cat_eb_days='".$_REQUEST['cat_eb_days']."', cat_page_no_header='".$_REQUEST['cat_page_no_header']."', cat_page_billboard='".$_REQUEST['cat_page_billboard']."', cat_require_email='".$_REQUEST['cat_require_email']."', cat_first_layout='".$_REQUEST['cat_first_layout']."', cat_auto_populate='".$_REQUEST['cat_auto_populate']."', cat_using_editor='1' , cat_meta_descr='".addslashes(stripslashes($_REQUEST['cat_meta_descr']))."' , cat_key_words='".addslashes(stripslashes($_REQUEST['cat_key_words']))."', cat_gallery_exclusive='".$_REQUEST['cat_gallery_exclusive']."', cat_gallery_exclusive_no_cover='".$_REQUEST['cat_gallery_exclusive_no_cover']."'   ");
		createCategory($cat_id);
		addNewCatDefaults($cat_id);
		$_SESSION['sm'] = "Category updated";
		session_write_close();
		header("location: index.php?do=news&action=editCategory&cat_id=$cat_id");
		exit();

	} else {
		$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' ");
		updateSQL("".cat_table."", " cat_name='".addslashes(stripslashes($_REQUEST['cat_name']))."' , cat_meta_title='".addslashes(stripslashes($_REQUEST['cat_meta_title']))."' , cat_text='".addslashes(stripslashes($_REQUEST['cat_text']))."', cat_text_under_content='".addslashes(stripslashes($_REQUEST['cat_text_under_content']))."', cat_text_under_subs='".addslashes(stripslashes($_REQUEST['cat_text_under_subs']))."'   , cat_billboard='".$_REQUEST['cat_billboard']."' , cat_status='".$_REQUEST['cat_status']."', cat_password='".addslashes(stripslashes($_REQUEST['cat_password']))."', cat_no_show='".addslashes(stripslashes($_REQUEST['cat_no_show']))."', cat_no_show_posts='".addslashes(stripslashes($_REQUEST['cat_no_show_posts']))."' , cat_theme='".addslashes(stripslashes($_REQUEST['cat_theme']))."', cat_list_sub_cat_posts='".$_REQUEST['cat_list_sub_cat_posts']."'  , cat_billboard_posts='".$_REQUEST['cat_billboard_posts']."', cat_type='".$_REQUEST['cat_type']."', cat_pic_tags='$tag_keys' , cat_show_sub_cats_page='".$_REQUEST['cat_show_sub_cats_page']."' , cat_layout='".$_REQUEST['cat_layout']."', cat_cat_layout='".$_REQUEST['cat_cat_layout']."', cat_page_layout='".$_REQUEST['cat_page_layout']."' , cat_per_page='".$_REQUEST['cat_per_page']."', cat_watermark='".$_REQUEST['cat_watermark']."', cat_logo='".$_REQUEST['cat_logo']."', cat_order='".$_REQUEST['cat_order']."' , cat_comments='".$_REQUEST['cat_comments']."', cat_next_prev='".$_REQUEST['cat_next_prev']."', cat_expire_days='".$_REQUEST['cat_expire_days']."', cat_show_title='".$_REQUEST['cat_show_title']."' , cat_order_by='".$_REQUEST['cat_order_by']."' , cat_private_button='".$_REQUEST['cat_private_button']."', cat_private_page='".$_REQUEST['cat_private_page']."', cat_search='".$_REQUEST['cat_search']."' , cat_expire_hide='".$_REQUEST['cat_expire_hide']."', cat_content='".$_REQUEST['cat_content']."' , cat_disable_side='".$_REQUEST['cat_disable_side']."', cat_default_private='".$_REQUEST['cat_default_private']."', cat_forum_categories='".addslashes(stripslashes($_REQUEST['cat_forum_categories']))."', cat_default_status='".$_REQUEST['cat_default_status']."', cat_req_login='".$_REQUEST['cat_req_login']."', cat_eb_days='".$_REQUEST['cat_eb_days']."', cat_page_no_header='".$_REQUEST['cat_page_no_header']."', cat_page_billboard='".$_REQUEST['cat_page_billboard']."', cat_first_layout='".$_REQUEST['cat_first_layout']."', cat_require_email='".$_REQUEST['cat_require_email']."', cat_reg_amounts='".$_REQUEST['cat_reg_amounts']."', cat_reg_no_address='".$_REQUEST['cat_reg_no_address']."' , cat_reg_default_text='".addslashes(stripslashes($_REQUEST['cat_reg_default_text']))."', cat_price_list='".$_REQUEST['cat_price_list']."', shipping_group='".$_REQUEST['shipping_group']."', cat_max_width='".$_REQUEST['cat_max_width']."', cat_auto_populate='".$_REQUEST['cat_auto_populate']."', def_passcode_photos='".$_REQUEST['def_passcode_photos']."', cat_no_list='".$_REQUEST['cat_no_list']."', cat_using_editor='1' , cat_meta_descr='".addslashes(stripslashes($_REQUEST['cat_meta_descr']))."' , cat_key_words='".addslashes(stripslashes($_REQUEST['cat_key_words']))."' , cat_gallery_exclusive='".$_REQUEST['cat_gallery_exclusive']."', cat_gallery_exclusive_no_cover='".$_REQUEST['cat_gallery_exclusive_no_cover']."',
		change_price_list='".$_REQUEST['change_price_list']."', change_price_list_days='".$_REQUEST['change_price_list_days']."' ,
		change_shipping_group='".$_REQUEST['change_shipping_group']."', change_shipping_group_days='".$_REQUEST['change_shipping_group_days']."' 
		
		WHERE cat_id='".$cat['cat_id']."' ");
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
		$_REQUEST['cat_order_by'] = $top_cat['cat_order_by'];
		$cat['cat_private_page'] = $top_cat['cat_private_page'];
		$cat['cat_search'] = $top_cat['cat_search'];
		$cat['cat_expire_hide'] = $top_cat['cat_expire_hide'];
		$cat['cat_content'] = $top_cat['cat_content'];
		$cat['cat_disable_side'] = $top_cat['cat_disable_side'];
		$cat['cat_default_private'] = $top_cat['cat_default_private'];
		$cat['cat_forum_categories'] = $top_cat['cat_forum_categories'];
		$cat['cat_default_status'] = $top_cat['cat_default_status'];
		$cat['cat_req_login'] = $top_cat['cat_req_login'];
		$cat['cat_eb_days'] = $top_cat['cat_eb_days'];
		$cat['cat_page_no_header'] = $top_cat['cat_page_no_header'];
		$cat['cat_page_billboard'] = $top_cat['cat_page_billboard'];
		$cat['cat_watermark'] = $top_cat['cat_watermark'];
		$cat['cat_logo'] = $top_cat['cat_logo'];
		$cat['cat_expire_days'] = $top_cat['cat_expire_days'];


	} else { 
		$cat['cat_type'] = $up_cat['cat_type'];
		$cat_layout = $up_cat['cat_layout'];
		$cat_page_layout= $up_cat['cat_page_layout'];
		$cat['cat_per_page'] = $up_cat['cat_per_page'];
		$_REQUEST['cat_order_by'] = $up_cat['cat_order_by'];
		$cat['cat_private_page'] = $up_cat['cat_private_page'];
		$cat['cat_search'] = $up_cat['cat_search'];
		$cat['cat_expire_hide'] = $up_cat['cat_expire_hide'];
		$cat['cat_content'] = $up_cat['cat_content'];
		$cat['cat_disable_side'] = $up_cat['cat_disable_side'];
		$cat['cat_default_private'] = $up_cat['cat_default_private'];
		$cat['cat_forum_categories'] = $up_cat['cat_forum_categories'];
		$cat['cat_default_status'] = $up_cat['cat_default_status'];
		$cat['cat_req_login'] = $up_cat['cat_req_login'];
		$cat['cat_eb_days'] = $up_cat['cat_eb_days'];
		$cat['cat_page_no_header'] = $up_cat['cat_page_no_header'];
		$cat['cat_page_billboard'] = $up_cat['cat_page_billboard'];
		$cat['cat_watermark'] = $up_cat['cat_watermark'];
		$cat['cat_logo'] = $up_cat['cat_logo'];
		$cat['cat_expire_days'] = $up_cat['cat_expire_days'];

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
<div>&nbsp;</div>
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

<?php include "news.category.tabs.php"; ?>


<div id="roundedFormContain">

<div style="width: 50%; float: left;">

<form name="captyion" id="captyion" method="post" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
<div id="">
<div class="underline">
	<div style="<?php if($_REQUEST['cat_under'] > 0) { ?> width: 85%; float: left; <?php } ?>">
		<div class="fieldLabel"><?php if($_REQUEST['cat_under'] <=0) { ?>Section<?php } else { print "Category"; } ?> Name</div>
		<div><input type="text" id="cat_name" name="cat_name" value="<?php print $cat['cat_name'];?>" class="inputtitle field100 required"></div>
		<div class="pc"><input type="checkbox" name="cat_show_title" id="cat_show_title" value="1" <?php if(($cat['cat_show_title'] == "1")||(empty($_REQUEST['cat_id']))==true) { print "checked"; } ?>> <label for="cat_show_title">Show this as the page title</label></div>
	</div>



	<?php if($_REQUEST['cat_under'] > 0) { ?>
		<div style="width: 15%; float: left;">
		<div class="fieldLabel">Display Order</div>
		<div><input type="text" id="cat_order" name="cat_order" value="<?php print $cat['cat_order'];?>" class="inputtitle field100 inputtip" title="Ordered numerically from lowest to highest. Leave all categories at 0 to order alphabetically. "></div>
	</div>
	<?php } ?>
	<div class="clear"></div>

</div>


<?php if((empty($_REQUEST['cat_id']))AND(!empty($_REQUEST['cat_under']))  == true) { ?>
	<div class="underline">
		<div class="label">Place under section or category:</div>
		<div><?php print getMultiCats('cat_under', $_REQUEST['cat_under']);?></div>
	</div>
<?php } ?>




<div class="underline">
	<div class="underlinelabel"><?php if($cat['cat_type'] == "registry") { ?>Text on main registry page<?php } else { ?>Text above content<?php } ?></div>
	<div><textarea   name="cat_text" id="cat_text" class="field100"  rows="8"><?php print $cat['cat_text'];?></textarea></div>
</div>
<?php addEditor("cat_text","1", "500", "0"); ?>

</div>
<div>&nbsp;</div>


<div>
	<div id="">
		<div class="underlinelabel subeditclick">Metadata</div>
		<div class="subedit">
		<div class="pc">Metadata is information about the page that is added to the code but not seen on the page. This is used for search engines.</div>
		<div class="underline">
				<div class="fieldLabel">Meta Title <div class="moreinfo" info-data="catmetatitle"><div class="info"></div></div></div>
				<div><input type="text" id="cat_meta_title" name="cat_meta_title" value="<?php print htmlspecialchars($cat['cat_meta_title']);?>" class="field100"></div>
			</div>

			<div class="underline">
				<div class="fieldLabel">Meta Description </div>
				<div><input type="text" id="cat_meta_descr" name="cat_meta_descr" value="<?php print htmlspecialchars($cat['cat_meta_descr']);?>" class="field100"></div>
			</div>
			<div class="underline">
				<div class="fieldLabel">Meta Keywords </div>
				<div><input type="text" id="cat_key_words" name="cat_key_words" value="<?php print htmlspecialchars($cat['cat_key_words']);?>" class="field100"></div>
			</div>
		</div>
	</div>
</div>

<div>&nbsp;</div>



<div>
	<div id="">
		<div class="underlinelabel <?php if(empty($cat['cat_reg_default_text'])) { print "subeditclick"; } ?>"">Default Page Text When Creating New Pages</div>
		<div class="<?php if(empty($cat['cat_reg_default_text'])) { print "subedit"; } ?>">
		<div class="row"><textarea   name="cat_reg_default_text" id="cat_reg_default_text" class="field100"  rows="4" cols="20"><?php print $cat['cat_reg_default_text'];?></textarea></div>
		<?php addEditor("cat_reg_default_text","3", "500", "0"); ?>

		</div>
	</div>
</div>
<div>&nbsp;</div>




<?php if($cat['cat_type'] == "registry") { ?>
<div>
	<div id="">
		<div class="underlinelabel subeditclick">Registry Amounts</div>
		<div class="subedit">
		<div class="row">Enter in the amounts you want to offer with registries one per line. <br>Do not enter a currency sign.</div>
		<div class="row"><textarea   name="cat_reg_amounts" class="required"  rows="10" cols="20"><?php print $cat['cat_reg_amounts'];?></textarea></div>
		</div>
	</div>
</div>
<div>&nbsp;</div>
<!-- 
<div class="underline"><input type="checkbox" name="cat_reg_no_address" id="cat_reg_no_address" value="1"<?php if($cat['cat_reg_no_address'] == "1") { print "checked"; } ?>> <label for="cat_reg_no_address">Do not require address / phone from registry purchaser.</label>
</div>
<div>&nbsp;</div>
-->
<?php } ?>

<div>
	<div id="">
		<div class="underlinelabel subeditclick">
		<?php if($cat['cat_type'] == "registry") { 
			print "Instructions for adding to registry";
		} else { 
			print "Text under sub categories";
		}
		?></div>
		<div class="subedit">
			<div>
			<div class="row"><textarea   name="cat_text_under_subs" id="cat_text_under_subs" class="field100"  rows="8"><?php print $cat['cat_text_under_subs'];?></textarea></div>
		<?php addEditor("cat_text_under_subs","2", "500", "0"); ?>

			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>







<div>
	<div id="">
		<div class="underlinelabel <?php if(empty($cat['cat_text_under_content'])) { print "subeditclick"; } ?>">Text below all content</div>
		<div class="<?php if(empty($cat['cat_text_under_content'])) { print "subedit"; } ?>">
		<div class="row"><textarea   name="cat_text_under_content" id="cat_text_under_content" class="field100"  rows="8"><?php print $cat['cat_text_under_content'];?></textarea></div>
		<?php addEditor("cat_text_under_content","4", "500", "0"); ?>

	</div>
	</div>
</div>
<div>&nbsp;</div>



<?php
if(($cat['cat_id'] > 0)&&($cat['cat_type']!=="registry")==true) { 
	$dates = whileSQL("ms_calendar", "*", "WHERE date_cat='".$cat['cat_id']."'  ");
	if(mysqli_num_rows($dates)) { ?>
	<div class="underline">
		<div class="label">Show content from a page <div class="moreinfo" info-data="catcontentpage"><div class="info"></div></div></div>
		<div>
		<select name="cat_content" id="cat_content">
		<option value="0">Do not use</option>
		<?php 	while($date = mysqli_fetch_array($dates)) { ?>
		<option value="<?php print $date['date_id'];?>" <?php if($cat['cat_content'] == $date['date_id']) { print "selected"; } ?>><?php print $date['date_title'];?></option>
		<?php } ?>
		</select>
		</div>
	</div>
	<div>&nbsp;</div>
<?php 
	}
} ?>


</div>

<div style="width: 48%; float: right;">

<?php if($_REQUEST['cat_under'] > 0) { ?><div class="underlinelabel"><input type="checkbox" name="cat_status" id="cat_status" value="1" <?php if(($cat['cat_status'] == "1")OR(empty($cat['cat_id']))==true) { print "checked"; } ?>> <label for="cat_status">Active Category</label></div><div>&nbsp;</div><?php } else { ?>
<input type="hidden" name="cat_status" value="1"><?php } ?>

<div>

<script>
function showlistinglayoutdescr() { 
	$(".listingdescr").hide();
	$("#listingdescr-"+$("#cat_layout").val()).show();
}
function showpagelayoutdescr() { 
	$(".pagedescr").hide();
	$("#pagedescr-"+$("#cat_page_layout").val()).show();
}

</script>
<div id="">


<div>
<div class="underlinelabel subeditclick">Content Function
<div>
<?php if(($cat['cat_type'] == "standard")OR(empty($cat['cat_type']))==true) { ?>Standard pages or blog <?php } ?>
<?php if($cat['cat_type'] == "clientphotos") {  ?>Client galleries / selling photos. <?php } ?>
<?php if($cat['cat_type'] == "booking") {  ?>Booking Calendar<?php } ?>
<?php if($cat['cat_type'] == "store") { ?>Selling physical products, product downloads or services. <?php } ?>
<?php if($cat['cat_type'] == "forum") {  ?>Forum <?php } ?>
<?php if($cat['cat_type'] == "faq") { ?>FAQ <?php } ?>
<?php if($cat['cat_type'] == "proofing") { ?>Proofing Project <?php } ?>
<?php if($cat['cat_type'] == "registry") { ?>Registry <?php } ?>
</div>
</div>
	<div class="subedit">
	<div class="pc">This determines how the content in this category functions.</div>
		<div class="underline"><input type="radio" id="cat_typestandard" name="cat_type" value="standard" <?php if(($cat['cat_type'] == "standard")OR(empty($cat['cat_type']))==true) { print "checked"; } ?> onclick="showCatTags();"> <label for="cat_typestandard">Standard pages or blog</label></div>
		<div class="underline"><input type="radio" id="cat_typeclientphotos" name="cat_type" value="clientphotos" <?php if($cat['cat_type'] == "clientphotos") { print "checked"; } ?> onclick="showCatTags();"> <label for="cat_typeclientphotos">Client galleries / selling photos</label></div>
		<div class="underline"><input type="radio" id="cat_typestore" name="cat_type" value="store" <?php if($cat['cat_type'] == "store") { print "checked"; } ?> onclick="showCatTags();"> <label for="cat_typestore">Selling physical products, product downloads or services</label></div>
		<div class="underline"><input type="radio" id="cat_typebooking" name="cat_type" value="booking" <?php if($cat['cat_type'] == "booking") { print "checked"; } ?>> <label for="cat_typebooking">Booking Calendar</label></div>

		<?php if($setup['forum'] == true) { ?>
		<div class="underline"><input type="radio" id="cat_typeforum" name="cat_type" value="forum" <?php if($cat['cat_type'] == "forum") { print "checked"; } ?> > <label for="cat_typeforum">Forum</label></div>
		<?php } ?>
		<!-- 
		<div class="underline"><input type="radio" id="cat_type_stock" name="cat_type" value="stock" <?php if($cat['cat_type'] == "stock") { print "checked"; } ?> onclick="showCatTags();"> I will be displaying photos, not pages, in this category (stock photos).</div>
		-->
		<div class="underline"><input type="radio" id="cat_typefaq" name="cat_type" value="faq" <?php if($cat['cat_type'] == "faq") { print "checked"; } ?>> <label for="cat_typefaq">FAQ</label></div>
		<div class="underline"><input type="radio" id="cat_typeproofing" name="cat_type" value="proofing" <?php if($cat['cat_type'] == "proofing") { print "checked"; } ?>> <label for="cat_typeproofing">Project Proofing</label></div>
		<div class="underline"><input type="radio" id="cat_typeregistry" name="cat_type" value="registry" <?php if($cat['cat_type'] == "registry") { print "checked"; } ?>> <label for="cat_typeregistry">Registry</label></div>
	</div>

		<div id="catTags" style="display: <?php if($cat['cat_type'] == "stock") { print "block"; } else { print "none"; } ?>">
		<div class="pageContent"><h3>Tags</h3></div>
		<div class="underline">
		<?php 
		$cat_tags = explode(",",$cat['cat_pic_tags']);

		$tags = whileSQL("ms_photo_keywords", "*", "ORDER BY key_word ASC ");
		if(mysqli_num_rows($tags) <=0) { print "No tags have been created"; } 
		while($tag = mysqli_fetch_array($tags)) { 
				?>
		<span id="span-tag-<?php print $tag['id'];?>" class="<?php if(in_array($tag['id'], $cat_tags)) { print "tagselected"; } else { print "tagunselected"; }  ?>"><nobr>
		<input type="checkbox" id="e-tag-<?php print $tag['id'];?>" name="e_tags[<?php print $tag['id'];?>]" value="<?php print $tag['id'];?>" class="editinfo" <?php  if(in_array($tag['id'], $cat_tags)) {  print "checked"; } ?> onclick="checkTag('<?php print $tag['id'];?>');"> <?php print $tag['key_word'];?> </nobr></span>, 
		<?php } ?>
	</div>
	</div>
</div>
</div>
<div>&nbsp;</div>






<div>
<div class="underlinelabel subeditclick">Default Settings</div>
	<div class="subedit" style="margin-left: 24px;">
		<div class="pc">These are default settings when creating new pages or galleries within this section.</div>
		<?php if($cat['cat_type'] == "clientphotos") { ?>
		<div class="underline">
		<div><input type="checkbox" name="cat_gallery_exclusive" id="cat_gallery_exclusive" value="1" <?php if($cat['cat_gallery_exclusive'] == "1") { ?>checked<?php } ?>> <label for="cat_gallery_exclusive">Gallery Exclusive Mode</label></div>
		<div><input type="checkbox" name="cat_gallery_exclusive_no_cover" id="cat_gallery_exclusive_no_cover" value="1" <?php if($cat['cat_gallery_exclusive_no_cover'] == "1") { ?>checked<?php } ?>> <label for="cat_gallery_exclusive_no_cover">Do not display opening full screen photo</label></div>


		</div>
		<?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="https://www.picturespro.com/sytist-manual/articles/gallery-exclusive-mode/" target="blank">Learn more about this feature</a></div><?php } ?>
	
		<div class="pc"><a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>&subdo=galleryexclusiveon">Enable and apply to all existing galleries in this section</a>.</div>
		<div>&nbsp;</div>
		<?php } ?>
		<div>
			<div class="underlinelabel subeditclick">Default Expire & Password Protection</div>
			<div class="subedit">
				<div class="underline">You can have pages  in this category expire after so many days. Enter in the default amount of days to keep a page active. You can override this on a per page basis. </div>
				<div class="underline">Expire pages after <input type="text" name="cat_expire_days"size="4" value="<?php print htmlspecialchars(stripslashes($cat['cat_expire_days']));?>"> days.</div>
				<div class="underline">Enter in 0 to not expire pages in this category.</div>
				<div class="underline"><input type="checkbox" value="1" name="cat_expire_hide" id="cat_expire_hide" <?php if($cat['cat_expire_hide'] == "1") { print "checked"; } ?>> Hide expired pages. Unchecked will still display the pages, but when click it will say expired. </div>
				
				<div class="underlinelabel">Password Protect Pages</div>
				<div class="underline hidenew">
				<input type="radio" name="cat_default_private" id="private0" value="0" <?php if(empty($cat['cat_default_private'])) { print " checked"; } if($_REQUEST['private']=="0") { print " checked"; } ?>> <label for="private0">No</label></span>
				</div>
				<div class="underline hidenew">
				<input type="radio" name="cat_default_private" id="private1"  value="1" <?php if($cat['cat_default_private']=="1") { print " checked"; } ?>> <label for="private1">Yes - List on website then ask for password</label></span>
				</div>
				<div class="underline hidenew">
				<input type="radio" name="cat_default_private"  id="private2"  value="2" <?php if($cat['cat_default_private']=="2") { print " checked"; } ?>> <label for="private2">Yes - Do not list on website </label></span>
				</div>

				<div class="underlinelabel">Use Passcode Photos</div>
				<div class="underline">This option is for password protecting individual photos in a gallery. <?php if($setup['unbranded'] !== true) { ?><a href="https://www.picturespro.com/sytist-manual/articles/password-protecting-individual-photos-in-a-gallery/" target="_blank">Click here to learn about this feature</a>.<?php } ?>
				</div>

				<div class="underline hidenew">
				<input type="checkbox" name="def_passcode_photos" id="def_passcode_photos" value="1" <?php if($cat['def_passcode_photos'] == "1") { print "checked"; } ?>> <label for="def_passcode_photos">Enable passcode photos feature</label>
				</div>

			</div>
		</div>
	<div>&nbsp;</div>
	<div>
		<div class="underlinelabel subeditclick">Default Watermarking</div>
		<div class="subedit">
			<div class="underline"><input type="checkbox" name="cat_watermark" value="1" <?php if(($cat['cat_watermark'] == "1")==true) { print "checked"; } ?>> Autocheck to watermark photos uploaded in category</div>
			<div class="underline"><input type="checkbox" name="cat_logo" value="1" <?php if(($cat['cat_logo'] == "1")==true) { print "checked"; } ?>> Autocheck to add logo to photos uploaded inthis category</div>
			<div class="underline"><a href="index.php?do=settings&action=watermarking">Settings > Watermarking to select watermark options</a>.</div>
		</div>
	</div>
<div>&nbsp;</div>

<?php if($cat['cat_type'] == "clientphotos") {  ?>
	<div>
		<div class="underlinelabel subeditclick">Default Price List</div>
		<div class="subedit">
			<div class="underline">
			<select name="cat_price_list" id="cat_price_list">
			<option value="0">Use system default</option>
			<?php $lists = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC");
			while($list = mysqli_fetch_array($lists)) { ?>
			<option value="<?php print $list['list_id'];?>" <?php if($list['list_id'] == $cat['cat_price_list']) { print "selected"; } ?>><?php print $list['list_name'];?></option>
			<?php } ?>
			</select>
			</div>


			<div class="underlinelabel">Change price list after so many days?</div>
			<div class="underlinespacer">If you want the price list to change to a different one after a certain date, enter in how many days after the gallery is created to change and the price list to. Otherwise leave both blank.</div>

			<div class="underline">
		After <input type="text" id="change_price_list_days" name="change_price_list_days" size="4" value="<?php  print htmlspecialchars(stripslashes($cat['change_price_list_days']));?>"  class="center" > days 

		</div>
	
		<div class="underline">
		<div>Change to: </div>
		<div>
			<select name="change_price_list" id="change_price_list">
			<option value="0">Do not use</option>
			<?php $lists = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
				while($list = mysqli_fetch_array($lists)) { ?>
				<option value="<?php print $list['list_id'];?>" <?php if($list['list_id'] == $_REQUEST['change_price_list']) { print "selected"; } ?>><?php print $list['list_name'];?></option>
			<?php } ?>
			</select>
		</div>
		</div>



		</div>
	</div>
<div>&nbsp;</div>

<?php } ?>
		<?php if(($cat['cat_type'] == "clientphotos")||($cat['cat_type'] == "store") == true) { ?> 
	<div>
		<div class="underlinelabel subeditclick">Shipping
			<?php if($_REQUEST['shipping_group'] > 0) {
			$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$cat['shipping_group']."' ");
			print ": ".$sg['sg_name'];
			} else { 
				print ": Default";
			}
			?>
		</div>

			<div class="subedit">
				<div class="underline">When you add a new page, this is the default shipping group used.</div>
				<div class="underline">
				<select name="shipping_group" id="shipping_group">
				<?php 
				$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
				while($group = mysqli_fetch_array($groups)) { 
						?>
						<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $cat['shipping_group']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
						<?php 
					}
					?>
					</select> 
					</div>



			<div class="underlinelabel">Change shipping group after so many days?</div>
			<div class="underlinespacer">If you want the shipping group to change to a different one after a certain date, enter in how many days after the gallery is created to change and the shipping group to change to. Otherwise leave both blank.</div>

			<div class="underline">
		After <input type="text" id="change_shipping_group_days" name="change_shipping_group_days" size="4" value="<?php  print htmlspecialchars(stripslashes($cat['change_shipping_group_days']));?>"  class="center" > days 

		</div>
	
		<div class="underline">
		<div>Change to: </div>
		<div>
			<select name="change_shipping_group" id="change_shipping_group">
			<option value="0">Do not use</option>
			<?php	
			$groups = whileSQL("ms_shipping_groups", "*", "ORDER BY sg_name ASC ");
				while($group = mysqli_fetch_array($groups)) { 
				?>
				<option value="<?php print $group['sg_id'];?>" <?php if($group['sg_id'] == $_REQUEST['change_shipping_group']) { print "selected"; } ?>><?php print $group['sg_name'];?></option>
			<?php } ?>
			</select>
		</div>
		</div>

			</div>
		</div>
			<div>&nbsp;</div>
		<?php } ?>
	<div>
		<div class="underlinelabel subeditclick">Default Page Status</div>
		<div class="subedit">
			<div class="underline">When you add a new page, this is the default status of the page.</div>
			<div class="underline">
			<input type="radio" name="cat_default_status" value="2" <?php if($cat['cat_default_status'] == "2") { print "checked"; } ?>> Draft &nbsp; <input type="radio" name="cat_default_status" value="1" <?php if($cat['cat_default_status'] == "1") { print "checked"; } ?>> Published
			</div>
		</div>
	</div>




	<div>&nbsp;</div>

<?php if($cat['cat_type'] == "clientphotos") {  ?>
<div class="underline">Early bird special default days: <input type="text" name="cat_eb_days" size="3" value="<?php print htmlspecialchars(stripslashes($cat['cat_eb_days']));?>"></div>

<?php } ?>

</div>
</div>

<div>&nbsp;</div>

<div>
<div class="underlinelabel subeditclick">Layouts</div>
	<div class="subedit" style="margin-left: 24px;">

		<div class="underlinelabel <?php if($top_section !== true) { print "subeditclick"; } ?>">Page Listing  Layout <div><?php if($top_section !== true) {  if($cat['cat_layout'] > 0) {  $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat['cat_layout']."' "); if($_REQUEST['cat_under'] > 0) {print "Override -"; } print " ".$layout['layout_name'].""; } else {  $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat_layout."' "); print "Section default - ".$layout['layout_name'].""; } }?></div></div>
		<div class="<?php if($top_section !== true) { print "subedit"; } ?>">

		<div class="underline">This determines how page listings are displayed in this category. <a href="index.php?do=look&view=layouts">See page listing layout</a>.</div>
			<div class="underline">
			<select name="cat_layout" id="cat_layout" <?php  if($_REQUEST['cat_under'] <=0) { ?>class="required"<?php } ?> onchange="showlistinglayoutdescr();">
			<?php if($_REQUEST['cat_under'] <=0) { ?>
			<option value="" id="plselect">Select</option>
			<?php } else { ?>
			<option value="" id="pltop">Use top level selection</option>
			<option value="" disabled  id="plor">--- or select a different layout below ---</option>
			<?php } ?>

			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($cat['cat_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
			</select>
			</div>
			<div class="underline">
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<div id=\"listingdescr-".$layout['layout_id']."\" class=\"listingdescr\" ";  if($cat['cat_layout'] !== $layout['layout_id']) { print "style=\"display: none;\""; } print ">".$layout['layout_description']."</div>";
			}
			?>
			</div>
		<div class="underline"><input type="text" size="2" name="cat_per_page" id="cat_per_page" class="required" value="<?php if($_REQUEST['cat_id'] <=0) { print "20"; } else { print $cat['cat_per_page']; } ?>"> items to show per page.</div>
		<div class="underline"><input type="checkbox" name="cat_auto_populate" id="cat_auto_populate" value="1" <?php if($cat['cat_auto_populate'] == "1") { print "checked"; } ?>> <label for="cat_auto_populate"> Auto populate content when scrolling. Uncheck will display next / previous page navigation.</label> </div>

		<div class="underline">Order pages by <select name="cat_order_by" id="cat_order_by">
		<option value="date" <?php if($_REQUEST['cat_order_by'] == "date") { print "selected"; } ?>>Date - Newest First</option>
		<option value="pageorder" <?php if($_REQUEST['cat_order_by'] == "pageorder") { print "selected"; } ?>>I Will Manually Arrange The Order</option>
		<option value="title" <?php if($_REQUEST['cat_order_by'] == "title") { print "selected"; } ?>>Page / Gallery Title - Ascending</option>
		</select>
		</div>
	
		<div class="underline"><input type="checkbox" name="cat_no_list" id="cat_no_list" value="1" <?php if($cat['cat_no_list'] == "1") { print "checked"; } ?>> <label for="cat_no_list"> Do not list pages in this section.</label> <div class="moreinfo" info-data="nolistcatpages"><div class="info"></div></div></div>

	</div>

<div>&nbsp;</div>
<div>
	<div id="">
		<div class="underlinelabel <?php if($top_section !== true) { print "subeditclick"; } ?>">Page Display Layout <div><?php if($top_section !== true) {  if($cat['cat_page_layout'] > 0) {  $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat['cat_page_layout']."' "); if($_REQUEST['cat_under'] > 0) { print "Override -"; } print " ".$layout['layout_name'].""; } else {  $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat_page_layout."' "); print "Section default - ".$layout['layout_name'].""; } }?></div></div>
		<div class="<?php if($top_section !== true) { print "subedit"; } ?>">
			<div class="underlinerow">This determines how pages are displayed in this category. <a href="index.php?do=look&view=layouts">See page displaylayout</a>.</div>
			<div class="underlinerow">
			<select name="cat_page_layout" id="cat_page_layout" <?php  if($_REQUEST['cat_under'] <=0) { ?>class="required"<?php } ?> onchange="showpagelayoutdescr();">
			<?php if($_REQUEST['cat_under'] <=0) { ?>
			<option value="">Select</option>
			<?php } else { ?>
			<option value="">Use top level selection</option>
			<option value="" disabled id="pdor">--- or select a different layout below ---</option>
			<?php } ?>
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='page' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($cat['cat_page_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
			</select>
		</div>
			<div class="underlinerow">
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='page' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<div id=\"pagedescr-".$layout['layout_id']."\" class=\"pagedescr\" ";  if($cat['cat_page_layout'] !== $layout['layout_id']) { print "style=\"display: none;\""; } print ">".$layout['layout_description']."</div>";
			}
			?>
			</div>

	</div>
</div>
</div>
<div>&nbsp;</div>


	<div id="">
		<div class="underlinelabel subeditclick">Category Listing  Layout <?php if($top_section !== true) {  if($cat['cat_cat_layout'] > 0) {  $layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$cat['cat_cat_layout']."' ");  print "<div>".$layout['layout_name']."</div>"; } }?></div>
		<div class="<?php if(($cat['cat_id'] > 0)||($_REQUEST['cat_under'] > 0)==true) {  print "subedit"; } ?>">
		<div class="underline">This determines how categories are displayed this section. <a href="index.php?do=look&view=layouts">See page listing layout</a>.</div>
			<div class="underline">
			<select name="cat_cat_layout" id="cat_cat_layout" <?php  if($_REQUEST['cat_under'] <=0) { ?>class="required"<?php } ?> onchange="showlistinglayoutdescr();">
			<?php if($_REQUEST['cat_under'] <=0) { ?>
			<option value="" id="plselect">Select</option>
			<?php } else { ?>
			<option value="" id="pltop">Use top level selection</option>
			<option value="" disabled  id="plor">--- or select a different layout below ---</option>
			<?php } ?>

			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' AND layout_css_id!='listing-stacked' AND layout_id!='19' AND layout_id!='18' AND layout_id!='21' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<option value=\"".$layout['layout_id']."\" "; if($cat['cat_cat_layout'] == $layout['layout_id']) { print "selected"; } print ">".$layout['layout_name']."</optoin>";
			}
			?>
			</select>
			</div>
			<div class="underline">
			<?php 
			$layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
			while($layout = mysqli_fetch_array($layouts)) { 
				print "<div id=\"listingdescr-".$layout['layout_id']."\" class=\"listingdescr\" ";  if($cat['cat_cat_layout'] !== $layout['layout_id']) { print "style=\"display: none;\""; } print ">".$layout['layout_description']."</div>";
			}
			?>
			</div>
			<?php 
			if(empty($cat['cat_id'])) { 
				$cat['cat_show_sub_cats_page'] = 1; 
				$cat['cat_list_sub_cat_posts'] = 0;
			} 
			?>
		<div class="underline"><input type="checkbox" name="cat_show_sub_cats_page" value="1" <?php if(($cat['cat_show_sub_cats_page'] == "1")==true) { print "checked"; } ?>> Show sub categories on page above any content.</div>
		<div class="underline"><input type="checkbox" name="cat_list_sub_cat_posts" value="1" <?php if($cat['cat_list_sub_cat_posts'] == "1") { print "checked"; } ?>> Show pages from sub categories</div>
	</div>
</div>

</div>
</div>
<div>&nbsp;</div>




<div>
	<div class="underlinelabel subeditclick">Design Options</div>
	<div class="subedit" style="margin-left: 24px;">
			<div>
				<div id="">
					<div class="underlinelabel subeditclick">Billboard <?php if($cat['cat_billboard'] > 0) { ?><img src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled"><?php } ?></div>
					<div class="subedit">
					<div class="underline">This will show a billboard when the category is viewed.</div>
					<div class="underline">
					<select name="cat_billboard">
					<option value="0">No</option>
					<?php 
					$bills = whileSQL("ms_billboards", "*", "ORDER BY bill_name ASC ");
					while($bill = mysqli_fetch_array($bills)) { 
						print "<option value=\"".$bill['bill_id']."\" "; if($cat['cat_billboard'] == $bill['bill_id']) { print "selected"; } print ">".$bill['bill_name']."</optoin>";
					}
					?>
					</select>
				</div>
				<div class="pc"><input type="checkbox" name="cat_billboard_posts" value="1" <?php if(($cat['cat_billboard_posts'] == "1")OR(empty($cat['cat_id']))==true) { print "checked"; } ?>> Show billboard on all pages in this category as well.</div>
				</div>
				</div>
			<div>&nbsp;</div>


			<div>
				<div class="underlinelabel subeditclick">CLF-Display 
					<img class="sweetstatus <?php if($show['enabled'] !== "1")  { ?>hidden<?php } ?>" id="sweet-<?php print $cat['cat_id'];?>-on" src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled">
				</div>
				<div class="subedit"><a href="" onclick="sweetness('<?php print $show['show_id'];?>','','<?php print $cat['cat_id'];?>'); return false;">Edit CLF-Display</a></div>
			</div>
			<div>&nbsp;</div>




			<div>
				<div class="underlinelabel subeditclick">Theme</div>
				<div class="subedit">
					<div class="underline">Selecting a theme here will <b>override your main theme</b> and apply to items in this category. <b>Do not select a theme here to use your main website theme</b>.</div>
					<div class="underline">
						<select name="cat_theme">
						<option value="0">Use default</option>
						<?php 
						$themes = whileSQL("ms_css", "*", "ORDER BY css_name ASC ");
						while($theme = mysqli_fetch_array($themes)) { 
							print "<option value=\"".$theme['css_id']."\" "; if($cat['cat_theme'] == $theme['css_id']) { print "selected"; } print ">".$theme['css_name']."</optoin>";
						}
						?>
						</select>
					</div>
				</div>
			</div>

			<div>&nbsp;</div>

			<div>
				<div class="underlinelabel subeditclick">Page Max Width</div>
				<div class="subedit ">
					<div class="underlinespacer">Here you can set the max width of this page if you want it smaller than the width of the content area of your theme.</div>
					<div class="underline hidenew">
					<select name="cat_max_width" id="cat_max_width">
					<option value="0" <?php if($_REQUEST['cat_max_width'] == "0") { print "selected"; } ?>>No Fixed Width</option>
					<option value="1024" <?php if($_REQUEST['cat_max_width'] == "1024") { print "selected"; } ?>>1024px</option>
					<option value="1200" <?php if($_REQUEST['cat_max_width'] == "1200") { print "selected"; } ?>>1200px</option>
					<option value="1400" <?php if($_REQUEST['cat_max_width'] == "1400") { print "selected"; } ?>>1400px</option>
					</select> 
					</div>
				</div>
			</div>

			<div>&nbsp;</div>	
			<div class="underline"><input type="checkbox" name="cat_page_no_header" value="1" <?php if(($cat['cat_page_no_header'] == "1")==true) { print "checked"; } ?>> Remove your header & menu when viewing pages in this section.</div>

			<div class="underline"><input type="checkbox" name="cat_page_billboard" value="1" <?php if(($cat['cat_page_billboard'] == "1")==true) { print "checked"; } ?>> Display a masthead (1 billboard slide) of the first photo or cover photo on pages displayed in this section. You can edit the layout of it in the <a href="index.php?do=look&action=billboardsList">Site Design -> Billboards</a> section.</div>

			<div id="">
			<div class="underline"><input type="checkbox" name="cat_disable_side" value="1" <?php if(($cat['cat_disable_side'] == "1")==true) { print "checked"; } ?>> Disable side bar for this section and pages in this section (if in use).</div>
			</div>

		</div>
	</div>
	<div>&nbsp;</div>	



	<div>
		<div class="underlinelabel subeditclick">Private Pages Settings</div>
		<div class="subedit">
			<div class="underline">If you are going to have private pages in this category (private galleries), you can add an access private button or form to this page.</div>
			<div class="underline"><input type="checkbox" name="cat_private_button" value="1" <?php if(($cat['cat_private_button'] == "1")==true) { print "checked"; } ?>> Show access private pages button</div>
			<div class="underline"><input type="checkbox" name="cat_private_page" value="1" <?php if(($cat['cat_private_page'] == "1")==true) { print "checked"; } ?>> Show access private pages form</div>
		</div>
	</div>
		<div>&nbsp;</div>

	<div>
		<div class="underlinelabel subeditclick">Require To View</div>
		<div class="subedit">
			<div class="underline"><input type="checkbox" name="cat_req_login" id="cat_req_login" value="1" <?php if(($cat['cat_req_login'] == "1")==true) { print "checked"; } ?>> <label for="cat_req_login">Require visitors to be logged in to view pages in this section.</label></div>
			<div class="underline">Or</div>
			<div class="underline"><input type="checkbox" name="cat_require_email" id="cat_require_email" value="1" <?php if($cat['cat_require_email'] == "1") { print "checked"; } ?>> <label for="cat_require_email">Require Email Address</label> </div>
		</div>
	</div>
		<div>&nbsp;</div>




	<div>
		<div class="underlinelabel subeditclick">Password Protect Entire Category</div>
		<div class="subedit">
			<div class="underline">By entering a password below, this will password protect this category AND any pages  with this category selected as the main category.</div>
			<div class="underline"><span style="color: #890000; font-weight: bold;">Important: </span>If you create any categories under this, you will need to password protect them as well.</div>
			<div class="underline">Password: <input type="text" name="cat_password"size="20" value="<?php print htmlspecialchars(stripslashes($cat['cat_password']));?>"></div>
			<div class="underline"><input type="checkbox" name="cat_no_show" value="1" <?php if($cat['cat_no_show'] == "1") { print "checked"; } ?>> Check this box to not show category when categories are listed.</div>
			<div class="underline"><input type="checkbox" name="cat_no_show_posts" value="1" <?php if($cat['cat_no_show_posts'] == "1") { print "checked"; } ?>> Check this box to not show posts in this category when all posts are listed.</div>
		</div>
	</div>

	<div>&nbsp;</div>





<div class="underline"><input type="checkbox" name="cat_comments" value="1" <?php if(($cat['cat_comments'] == "1")==true) { print "checked"; } ?>> Allowing commenting on pages. This is also based on your settings in <a href="index.php?do=comments&view=settings">Comments > Settings</a>.</div>



<?php if($cat['cat_type'] !== "registry") { ?>
<div id="">
<div class="underline"><input type="checkbox" name="cat_search" value="1" <?php if(($cat['cat_search'] == "1")==true) { print "checked"; } ?>> Add search icon for people to search for pages within this section.</div>
</div>
<?php } ?>




<div>&nbsp;</div>


<?php if(($setup['forum'] == true)&&($cat['cat_type'] == "forum")==true) { ?>
<div id="">
	<div class="underlinelabel subeditclick">Forum Categories</div>
		<div class="subedit">
			<div class="underline">
			<textarea name="cat_forum_categories" id="cat_forum_categories" cols="40" rows="10" class="field100"><?php print $cat['cat_forum_categories'];?></textarea>
			</div>
		</div>
	</div>
	<div>&nbsp;</div>


<?php } ?>

</div>

<script>
function showCatTags() { 
	if($("#cat_type_stock").is(":checked")) { 
		$("#catTags").fadeIn(100);
	} else { 
		$("#catTags").fadeOut(100);
	}
}
function showFirtLayout() { 
	if($("#show_first_layout").is(":checked")) { 
		$("#first_layout_form").slideDown(100);
	} else { 
		$("#first_layout_form").slideUp(100);
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




<input type="hidden" name="cat_id" value="<?php print $_REQUEST['cat_id'];?>">
<input type="hidden" name="do" value="news">
<input type="hidden" name="action" value="editCategory">
<input type="hidden" name="submitit" value="yes">
<div class="bottomSave">
<input type="submit" name="submit" value="Save Category" class="submit"  id="submitButton">
</div>
</form>

</div>
</div>
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

	$resultt = mysqli_query($dbcon,"SELECT * FROM ".cat_table." WHERE cat_under='0' ORDER BY cat_name ASC");
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
