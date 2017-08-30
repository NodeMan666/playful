<?php 	
if($_REQUEST['cat_id'] > 0) { 
	$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); 
}
?>
<div id="pageTitle"><a href="index.php?do=news">Site Content</a> 
<?php
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
?>
<?php print ai_sep;?>  <a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>"><?php print $cat['cat_name'];?></a> <?php print ai_sep;?> Photo Defaults</div>

<?php

if($def_type=="page") { 
	$setup['news_folder'] = $setup['pages_folder'];
} elseif($def_type == "gal") { 
	$setup['news_folder'] = $setup['photos_folder'];
} else {
	$def_type = "news";
}

if($_REQUEST['subdo'] == "batchUpdate") { 
	$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['cat_id']."' "); 
	updateSQL("ms_calendar", "			blog_location='".$def['blog_location']."',
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
			max_photo_display_width='".$def['max_photo_display_width']."',
			thumb_width='".($photo_setup['blog_th_width'] + 30)."', 
			slideshow_fixed_height='".$def['slideshow_fixed_height']."',
			photo_social_share='".$def['social_share']."',
			jthumb_height='".$def['jthumb_height']."',
			jthumb_margin='".$def['jthumb_margin']."',
			thumbactions='".$def['thumbactions']."',
			disable_filename='".$def['disable_filename']."',
			photo_search='".$def['photo_search']."',
			thumb_scroller_open='".$def['thumb_scroller_open']."', 
			thumb_scroller='".$def['thumb_scroller']."', 
			allow_favs='".$def['allow_favs']."',
			thumb_open_first='".$def['thumb_open_first']."',
			thumb_file='".$def['thumb_file']."',
			add_style='".$def['add_style']."',
			disable_icons='".$def['disable_icons']."', 
			stacked_width='".$def['stacked_width']."', 
			stacked_margin='".$def['stacked_margin']."',
			enable_compare='".$def['enable_compare']."' 
			 WHERE date_cat='".$_REQUEST['cat_id']."' ");
		print "Updated";

		$_SESSION['sm'] = "Updated with the new default settings";
		session_write_close();
		header("location: index.php?do=".$_REQUEST['do']."&action=photoDefaults&cat_id=".$_REQUEST['cat_id']."");
		exit();
}

if($_REQUEST['subdo']=="updateSettings") {
$def_id = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['cat_id']."' ");
	if(empty($def_id['def_id'])) { 

		insertSQL("ms_defaults", "blog_location='".$_REQUEST['blog_location']."', blog_photo_file='".$_REQUEST['blog_photo_file']."', blog_contain='".$_REQUEST['blog_contain']."', blog_seconds='".$_REQUEST['blog_seconds']."', blog_enlarge='".$_REQUEST['blog_enlarge']."' , blog_kill_side_menu='".$_REQUEST['blog_kill_side_menu']."' , blog_progress_bar='".$_REQUEST['blog_progress_bar']."' , blog_next_prev='".$_REQUEST['blog_next_prev']."', blog_play_pause='".$_REQUEST['blog_play_pause']."' , blog_slideshow='".$_REQUEST['blog_slideshow']."' , blog_slideshow_auto_start='".$_REQUEST['blog_slideshow_auto_start']."' , disable_controls='".$_REQUEST['disable_controls']."' , caption_location='".$_REQUEST['caption_location']."', noupsize='".$_REQUEST['noupsize']."', contain_width='".$_REQUEST['contain_width']."', contain_height='".$_REQUEST['contain_height']."' , blog_frame='".$_REQUEST['blog_frame']."', bg_use='".$_REQUEST['bg_use']."' , def_type='".$_REQUEST['def_type']."', transition_time='".$_REQUEST['transition_time']."', disable_thumbnails='".$_REQUEST['disable_thumbnails']."', disable_play_pause='".$_REQUEST['disable_play_pause']."', disable_next_previous='".$_REQUEST['disable_next_previous']."', disable_help='".$_REQUEST['disable_help']."', disable_animation_bar='".$_REQUEST['disable_animation_bar']."', disable_photo_slider='".$_REQUEST['disable_photo_slider']."', disable_photo_count='".$_REQUEST['disable_photo_count']."'  , thumb_type='".$_REQUEST['thumb_type']."', thumb_style='".$_REQUEST['thumb_style']."', thumb_width='".$_REQUEST['thumb_width']."', def_cat_id='".$_REQUEST['cat_id']."', slideshow_fixed_height='".$_REQUEST['slideshow_fixed_height']."', thumb_scroller_open='".$_REQUEST['thumb_scroller_open']."', social_share='".$_REQUEST['social_share']."', jthumb_height='".$_REQUEST['jthumb_height']."' , jthumb_margin='".$_REQUEST['jthumb_margin']."' , thumb_scroller='".$_REQUEST['thumb_scroller']."', allow_favs='".$_REQUEST['allow_favs']."', thumbactions='".$_REQUEST['thumbactions']."', disable_filename='".$_REQUEST['disable_filename']."' , photo_search='".$_REQUEST['photo_search']."', thumb_open_first='".$_REQUEST['thumb_open_first']."', add_style='".$_REQUEST['add_style']."', thumb_file='".$_REQUEST['thumb_file']."' , disable_icons='".$_REQUEST['disable_icons']."' , stacked_width='".$_REQUEST['stacked_width']."', stacked_margin='".$_REQUEST['stacked_margin']."', enable_compare='".$_REQUEST['enable_compare']."' ");

	} else { 


		updateSQL("ms_defaults", "blog_location='".$_REQUEST['blog_location']."', blog_photo_file='".$_REQUEST['blog_photo_file']."', blog_contain='".$_REQUEST['blog_contain']."', blog_seconds='".$_REQUEST['blog_seconds']."', blog_enlarge='".$_REQUEST['blog_enlarge']."' , blog_kill_side_menu='".$_REQUEST['blog_kill_side_menu']."' , blog_progress_bar='".$_REQUEST['blog_progress_bar']."' , blog_next_prev='".$_REQUEST['blog_next_prev']."', blog_play_pause='".$_REQUEST['blog_play_pause']."' , blog_slideshow='".$_REQUEST['blog_slideshow']."' , blog_slideshow_auto_start='".$_REQUEST['blog_slideshow_auto_start']."' , disable_controls='".$_REQUEST['disable_controls']."' , caption_location='".$_REQUEST['caption_location']."', noupsize='".$_REQUEST['noupsize']."', contain_width='".$_REQUEST['contain_width']."', contain_height='".$_REQUEST['contain_height']."' , blog_frame='".$_REQUEST['blog_frame']."', bg_use='".$_REQUEST['bg_use']."' , def_type='".$_REQUEST['def_type']."', transition_time='".$_REQUEST['transition_time']."', disable_thumbnails='".$_REQUEST['disable_thumbnails']."', disable_play_pause='".$_REQUEST['disable_play_pause']."', disable_next_previous='".$_REQUEST['disable_next_previous']."', disable_help='".$_REQUEST['disable_help']."', disable_animation_bar='".$_REQUEST['disable_animation_bar']."', disable_photo_slider='".$_REQUEST['disable_photo_slider']."', disable_photo_count='".$_REQUEST['disable_photo_count']."'  , thumb_type='".$_REQUEST['thumb_type']."', thumb_style='".$_REQUEST['thumb_style']."', thumb_width='".$_REQUEST['thumb_width']."' , max_photo_display_width='".$_REQUEST['max_photo_display_width']."', slideshow_fixed_height='".$_REQUEST['slideshow_fixed_height']."', thumb_scroller_open='".$_REQUEST['thumb_scroller_open']."', social_share='".$_REQUEST['social_share']."', jthumb_height='".$_REQUEST['jthumb_height']."' , jthumb_margin='".$_REQUEST['jthumb_margin']."', thumb_scroller='".$_REQUEST['thumb_scroller']."', allow_favs='".$_REQUEST['allow_favs']."', thumbactions='".$_REQUEST['thumbactions']."', disable_filename='".$_REQUEST['disable_filename']."' , photo_search='".$_REQUEST['photo_search']."', thumb_open_first='".$_REQUEST['thumb_open_first']."' , add_style='".$_REQUEST['add_style']."', thumb_file='".$_REQUEST['thumb_file']."', disable_icons='".$_REQUEST['disable_icons']."' , stacked_width='".$_REQUEST['stacked_width']."', stacked_margin='".$_REQUEST['stacked_margin']."' , enable_compare='".$_REQUEST['enable_compare']."' WHERE def_cat_id='".$_REQUEST['cat_id']."' ");
	}
		$_SESSION['sm'] = "Default Settings Saved";
		session_write_close();
		header("location: index.php?do=".$_REQUEST['do']."&action=photoDefaults&cat_id=".$_REQUEST['cat_id']."");
		exit();
}
?>

<?php 			$def = doSQL("ms_defaults", "*", "WHERE def_cat_id='".$_REQUEST['cat_id']."' "); ?>


<div class="pageContent"><a href="index.php?do=<?php print $_REQUEST['do'];?>&action=photoDefaults&subdo=batchUpdate&cat_id=<?php print $cat['cat_id'];?>"  onClick="return confirm('Are you sure you want to update all pages in this categorty with these default settings?');"  >Batch update all  existing pages in this category with these settings</a><br><span class="muted">Note, if you make any changes below you must save them first before performing this action.</span>
</div>
<div>&nbsp;</div>


<?php include "news.category.tabs.php"; ?>


	<div id="roundedFormContain">




<script>


function selectdisplayoption(opt) { 
	$(".alloptions").hide();
	$("."+opt).slideDown(100);

	val = $("input:radio[name=thumb_style]").filter(":checked").val()
	if(val == 0) { 
		thumbtype = "justified";
	}
	if(val == 1) { 
		thumbtype = "standard";
	}
	if(val == 2) { 
		thumbtype = "stacked";
	}
	// alert(val+" = "+thumbtype);
		$(".thumboptions").hide();
	if(opt == "gallery") { 
		selectthumbtype(thumbtype);
	}

}

	function selectthumbtype(opt) { 
		$(".thumboptions").hide();
		$("."+opt).slideDown(100);
	}
<?php if($def['thumb_style'] == "1") { ?>
	thumbtype = "standard";
<?php } ?>
<?php if($def['thumb_style'] == "0") { ?>
	thumbtype = "justified";
<?php } ?>
<?php if($def['thumb_style'] == "2") { ?>
	thumbtype = "stacked";
<?php } ?>

 $(document).ready(function(){
	selectdisplayoption('<?php print $def['def_type'];?>');
	<?php if($def['def_type'] == "gallery") { ?>
	selectthumbtype(thumbtype);
	<?php } ?>
 });
</script>


<form name="register" action="index.php" method="post" style="padding:0; margin: 0;" onSubmit="return checkForm('.optrequired');">
<div style="width: 29%; float: left;">

<?php if($cat['cat_type'] !== "registry") { ?>

<div id="sharefav" <?php if(($def['def_type'] == "standardlist")||($def['def_type'] == "onpagewithminis")||($def['def_type'] == "onephoto")==true) { print "style=\"display: none;\""; } ?>>

<div class="pc"><input type="checkbox"  class="checkbox" name="social_share" id="social_share" value="1" <?php if($def['social_share'] == "1") { print "checked"; } ?>> Enable social share feature on individual photos. </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="allow_favs" id="allow_favs" value="1" <?php if($def['allow_favs'] == "1") { print "checked"; } ?>> Enable add to favorites. </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="photo_search" id="photo_search" value="1" <?php if($def['photo_search'] == "1") { print "checked"; } ?>> Enable photo search. </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="enable_compare" id="enable_compare" value="1" <?php if($def['enable_compare'] == "1") { print "checked"; } ?>> Enable compare photos. </div>
</div>
<div>&nbsp;</div>
<?php } ?>



<div class="pc">
<h3>Select how to display the photos</h3>
</div>
<?php if($cat['cat_type'] !== "registry") { ?>
<div class="underline"><h3><input type="radio"  class="checkbox" name="def_type" value="gallery" id="def_type_thumbs" <?php if($def['def_type'] == "gallery") { print "checked"; } ?>  onClick="selectdisplayoption('gallery');">  <label for="def_type_thumbs">Thumbnail gallery</label></h3>
Thumbnails are shown on the page where you would click to view full screen.</div>
<div>&nbsp;</div>
<?php } ?>
<?php if($cat['cat_type'] == "clientphotos") { ?>
<div class="underlinespacer" style="color: #890000; font-weight: bold;">The following display options DO NOT offer purchasing options. Only select the thumbnail gallery option above if you are selling photos is in this section.</div>
<?php } ?>
<?php if($cat['cat_type'] !== "proofing") { ?>

	<div class="underline"><h3><input type="radio"  class="checkbox" name="def_type" value="nextprevious" id="def_type_slideshow" <?php if($def['def_type'] == "nextprevious") { print "checked"; } ?>  onClick="selectdisplayoption('nextprevious');">  <label for="def_type_slideshow">As a slideshow</label></h3>
	Photos display on the page as a slideshow with next, previous, play, pause and full screen viewing options.</div>
	<div>&nbsp;</div>
	<?php if($cat['cat_type'] !== "registry") { ?>

	<div class="underline"><h3><input type="radio"  class="checkbox" name="def_type" value="standardlist" id="def_type_scroller" <?php if($def['def_type'] == "standardlist") { print "checked"; } ?>  onClick="selectdisplayoption('standardlist');">  <label for="def_type_scroller">Scroller</label></h3>
	Photos display straight down the page.</div>
	<div>&nbsp;</div>
	<?php } ?>
	<div class="underline"><h3><input type="radio"  class="checkbox" name="def_type" value="onpagewithminis" id="def_type_standard" <?php if($def['def_type'] == "onpagewithminis") { print "checked"; } ?>  onClick="selectdisplayoption('onpagewithminis');">  <label for="def_type_standard">Standard photo on page with minis</label></h3>
	This will display the photo on the page and if there is more than one photo, mini thumbnails are shown to the side of the first photo.</div>
	<div>&nbsp;</div>

	<div class="underline"><h3><input type="radio"  class="checkbox" name="def_type" value="onephoto" id="def_type_one" <?php if($def['def_type'] == "onephoto") { print "checked"; } ?>  onClick="selectdisplayoption('onephoto');">  <label for="def_type_one">One Photo</label></h3>
	Select this option if you are wanting to just display one photo on the page aligned left or right.</div>
	<div>&nbsp;</div>
<?php } ?>
<?php if($sytist_store == true) { ?>


<?php } ?>
</div>



<div style="width: 67%; float: right;">
<div class="pageContent" style="text-align: right;"> 
	<input type="hidden" name="do" value="<?php print $_REQUEST['do'];?>">
	<input type="hidden" name="action" value="managePhotos">
	<input type="hidden" name="subdo" value="updateSettings">
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<input type="hidden" name="gal_id" value="<?php  print $pb['gal_id'];?>">
</div>


	<div id="nextprevious">

	<!-- 
	<div class="underline" id="blogLocation">
	<input type="hidden" name="gal_id" value="<?php print $pb['gal_id'];?>">

	<select name="blog_location">
	<option value="after" <?php if($def['blog_location'] == "after") { print "selected"; } ?>>Show photos before text</option>
	<option value="before" <?php if($def['blog_location'] == "before") { print "selected"; } ?>>Show photos after text</option>
	</select>
	&nbsp; 
	</div>

-->



	<div class="underlinelabel">Photo size to display</div>
	<div class="underline">
	 <input type="radio"  class="checkbox" name="blog_photo_file" id="pic_pic"  value="pic_pic" <?php if($def['blog_photo_file'] == "pic_pic") { print "checked"; } ?> class="checkbox"> <label for="pic_pic">Small</label> &nbsp; 
	<input type="radio"  class="checkbox" name="blog_photo_file"  id="pic_large" value="pic_large" <?php if($def['blog_photo_file'] == "pic_large") { print "checked"; } ?> class="checkbox"> <label for="pic_large">Large</label> &nbsp; 
 
	&nbsp; 
	</div>
		<div class="underline alloptions onephoto"> Align: <select name="photo_align">
		<option value="left" <?php if($def['photo_align'] == "left") { print "selected"; } ?>>Left</option>
		<option value="right" <?php if($def['photo_align'] == "right") { print "selected"; } ?>>Right</option>
		</select>
	</div>

	<div class="alloptions gallery">
	<div class="underlinelabel">Select thumbnail display type</div>
	<div class="underline">
	<div style="float: left; width: 49%;" class="center">
		<div class="pc"> <input type="radio"  class="checkbox" name="thumb_style" id="thumbtypestacked" value="2" <?php if($def['thumb_style'] == "2") { print "checked"; } ?> onchange="selectthumbtype('stacked');">	Stacked thumbnails</div>
		<div class="pc"><label for="thumbtypestacked"><img src="graphics/thumbs-stacked.jpg" style="width: 100%; max-width: 600px;" border="0"></label></div>
	</div>
	<div style="float: right; width: 49%;" class="center">
		<div class="pc"><input type="radio"  class="checkbox" name="thumb_style"   id="thumbtypejustified" value="0" <?php if($def['thumb_style'] == "0") { print "checked"; } ?> onchange="selectthumbtype('justified');">	Justified</div>
		<div class="pc"><label for="thumbtypejustified"><img src="graphics/thumbs-justified.jpg" style="width: 100%; max-width: 600px;" border="0"></label></div>
	</div>

		<div style="float: left; width: 49%;" class="center">
		<div class="pc"> <input type="radio"  class="checkbox" name="thumb_style" id="thumbtypestandard" value="1" <?php if($def['thumb_style'] == "1") { print "checked"; } ?> onchange="selectthumbtype('standard');">	Standard thumbnails</div>
		<div class="pc"><label for="thumbtypestandard"><img src="graphics/thumbs-standard.jpg" style="width: 100%; max-width: 600px;"></label></div>
	</div>


	<div class="clear"></div>
	</div>
	<div class="underline thumboptions stacked alloptions">
		<div>
		<div class="left" style="width: 50%;">
			<div>Row width</div>
			<div><input type="text" name="stacked_width" id="stacked_width" value="<?php print $def['stacked_width'];?>" size="4" class="center">px.  Example: 250</div>
		</div>

		<div>
			<div>Spacing (margin)</div>
			<div><input type="text" name="stacked_margin" id="stacked_margin" value="<?php print $def['stacked_margin'];?>" size="4" class="center"> Example: 4</div>
		</div>
		<div class="clear"></div>
		</div>
	</div>
	</div>

	<div class="underline thumboptions stacked alloptions">
		<input type="checkbox"  class="checkbox" name="add_style" id="add_style" value="1" <?php if($def['add_style'] == "1") { print "checked"; } ?>> <label for="add_style">Display inside a styled container from your theme. </label>
	</div>
	<div class="underline thumboptions standard stacked alloptions">
		
		<input type="radio"  class="checkbox" name="thumb_file" id="thumb_file1" value="pic_th" <?php if($def['thumb_file'] == "pic_th") { print "checked"; } ?>> <label for="thumb_file1">Use Thumbnail</label> &nbsp; 
		<input type="radio"  class="checkbox" name="thumb_file" id="thumb_file2" value="pic_pic" <?php if($def['thumb_file'] == "pic_pic") { print "checked"; } ?>> <label for="thumb_file2">Use Small photo</label>

	</div>

	<div class="underline thumboptions standard stacked alloptions">
		<input type="checkbox"  class="checkbox" name="noupsize" id="noupsize" value="1" <?php if($def['noupsize'] == "1") { print "checked"; } ?>> <label for="noupsize">Use hover preview when mouse over thumbnail.</label>
	</div>

	<div class="underline thumboptions standard stacked alloptions">
		<input type="checkbox"  class="checkbox" name="thumbactions" id="thumbactions" value="1" <?php if($def['thumbactions'] == "1") { print "checked"; } ?>>	 <label for="thumbactions">Mouseover show  filename & icons under thumbnail. Unchecked will just display them.</label>
	</div>
	<div class="underline thumboptions standard stacked justified alloptions">
		<input type="checkbox"  class="checkbox" name="disable_filename" id="disable_filename" value="1" <?php if($def['disable_filename'] == "1") { print "checked"; } ?>>	<label for="disable_filename">Do not display filename under thumbnail.</label>
	</div>
	<div class="underline thumboptions standard stacked justified alloptions">
		<input type="checkbox"  class="checkbox" name="disable_icons" id="disable_icons" value="1" <?php if($def['disable_icons'] == "1") { print "checked"; } ?>>	<label for="disable_icons">Do not display icons under thumbnail.</label>
	</div>

	<div class="underline thumboptions justified alloptions">
		<div>
		<div class="left" style="width: 50%;">
			<div>Row height</div>
			<div><input type="text" name="jthumb_height" id="jthumb_height" value="<?php print $def['jthumb_height'];?>" size="4" class="center"> Example: 250</div>
		</div>

		<div>
			<div>Spacing (margin)</div>
			<div><input type="text" name="jthumb_margin" id="jthumb_margin" value="<?php print $def['jthumb_margin'];?>" size="4" class="center"> Example: 4</div>
		</div>
		<div class="clear"></div>
		</div>
	</div>
	</div>
	<div class="underline alloptions gallery">
	 <input type="checkbox"  class="checkbox" name="thumb_open_first"  value="1" <?php if($def['thumb_open_first'] == "1") { print "checked"; } ?> id="thumb_open_first"> <label for="thumb_open_first">When page is opened, display first photo and start slideshow</label>
	</div>

	<div class="underline alloptions standardlist">
	Caption location<br>
	 <input type="radio"  class="checkbox" name="caption_location"  value="0" <?php if($def['caption_location'] == "0") { print "checked"; } ?> id="caption_1">	<label for="caption_1">On Photo</label><br>
	 <input type="radio"  class="checkbox" name="caption_location"  value="1" <?php if($def['caption_location'] == "1") { print "checked"; } ?> id="caption_2">	<label for="caption_2">Below Photo</label>
	</div>

	<div class="underline alloptions nextprevious">
	 <input type="checkbox"  class="checkbox" name="blog_slideshow_auto_start"  value="1" <?php if($def['blog_slideshow_auto_start'] == "1") { print "checked"; } ?> id="autostart"> <label for="autostart">Auto start slideshow.</label>
	</div>

	<div class="underline alloptions nextprevious">
	 <input type="checkbox"  class="checkbox" name="slideshow_stop_end"  value="1" <?php if($def['slideshow_stop_end'] == "1") { print "checked"; } ?> id="loop"> <label for="loop">Loop</label>
	</div>


	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="disable_controls"  value="1" <?php if($def['disable_controls'] == "1") { print "checked"; } ?> id="disable_controls"> <label for="disable_controls">Disable next / previous / play controls.</label> 
	</div>

	<div class="underline alloptions nextprevious">
	<input type="checkbox"  class="checkbox" name="slideshow_fixed_height"  value="1" <?php if($def['slideshow_fixed_height'] == "1") { print "checked"; } ?> id="slideshow_fixed_height"> <label for="slideshow_fixed_height">Set the height of the slideshow to the height of the first landscape photo.</label> 
	</div>

	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="thumb_scroller"  value="1" <?php if($def['thumb_scroller'] == "1") { print "checked"; } ?> id="thumb_scroller"> <label for="thumb_scroller">Show scrolling thumbnails under slideshow.</label> 
	</div>


	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="thumb_scroller_open"  value="1" <?php if($def['thumb_scroller_open'] == "1") { print "checked"; } ?>> <label for="thumb_scroller_open">Scrolling thumbnails automatically open. Uncheck to keep closed until clicked. </label>
	</div>

	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="bg_use"  value="1" <?php if($def['bg_use'] == "1") { print "checked"; } ?>>	Use colors assigned to photos for backgrounds & borders.
	</div>

	<div class="underline alloptions nextprevious">
	<input type="checkbox"  class="checkbox" name="disable_next_previous"  value="1" <?php if($def['disable_next_previous'] == "1") { print "checked"; } ?>>	Disable next / previous controls. 
	</div>


	<div class="underline alloptions">
	 <!-- <input type="checkbox"  class="checkbox" name="disable_play_pause"  value="1" <?php if($def['disable_play_pause'] == "1") { print "checked"; } ?>>	Disable play / pause controls -->
	</div>


	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="disable_thumbnails"  value="1" <?php if($def['disable_thumbnails'] == "1") { print "checked"; } ?>>	Disable thumbnail menu.
	</div>

	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="disable_photo_count"  value="1" <?php if($def['disable_photo_count'] == "1") { print "checked"; } ?>>	Disable photo count (ie: 2 of 10).
	</div>


	<div class="underline alloptions nextprevious standardlist gallery">
	 <input type="checkbox"  class="checkbox" name="blog_kill_side_menu"  value="1" <?php if($def['blog_kill_side_menu'] == "1") { print "checked"; } ?> id="blog_kill_side_menu">	<label for="blog_kill_side_menu">Disable website  side bar (if in use).</label>
	</div>


<div class="underline alloptions nextprevious gallery">Slideshow seconds between photos: 


<select name="blog_seconds">
<?php $s = 1;
while($s <=10) { ?>
<option value="<?php print $s;?>" <?php if($s == $def['blog_seconds']) { print "selected"; } ?>><?php print $s;?></option>
<?php
	$s++;
}
?>
</select> seconds
</div>

<div class="underline alloptions nextprevious gallery"> Transition time 


<select name="transition_time">
<?php $s = 100;
while($s <=5000) { ?>
<option value="<?php print $s;?>" <?php if($s == $def['transition_time']) { print "selected"; } ?>><?php print number_format($s / 1000,2); ?> </option> 
<?php
	$s = $s + 100;
}
?>
</select> seconds
</div>

<div>&nbsp;</div>

<div class="pageContent" style="text-align: center;"> 
	<input type="hidden" name="do" value="news">
	<input type="hidden" name="action" value="photoDefaults">
	<input type="hidden" name="subdo" value="updateSettings">
	<input type="hidden" name="cat_id" value="<?php print $_REQUEST['cat_id'];?>">
<input type="submit" name="submit" value="Update Default Settings" class="submit" id="submitButton">
</div>


</div>
<div class="clear"></div>
</form>
<div>&nbsp;</div>	


</div>



<div class="cssClear"></div>
</div>
