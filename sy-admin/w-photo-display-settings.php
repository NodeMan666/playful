<?php require "w-header.php"; ?>

<?php 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
if($_REQUEST['subdo']=="updateSettings") {

	if($_REQUEST['thumb_style'] =="0") { 
		$_REQUEST['thumb_file'] = "pic_pic";
	}
	if($_REQUEST['thumb_style'] =="2") { 
		$_REQUEST['thumb_file'] = "pic_pic";
	}

		updateSQL("ms_calendar", "blog_location='".$_REQUEST['blog_location']."', blog_photo_file='".$_REQUEST['blog_photo_file']."', blog_contain='".$_REQUEST['blog_contain']."', blog_seconds='".$_REQUEST['blog_seconds']."', blog_enlarge='".$_REQUEST['blog_enlarge']."' , blog_kill_side_menu='".$_REQUEST['blog_kill_side_menu']."' , blog_progress_bar='".$_REQUEST['blog_progress_bar']."' , blog_next_prev='".$_REQUEST['blog_next_prev']."', blog_play_pause='".$_REQUEST['blog_play_pause']."' , blog_slideshow='".$_REQUEST['blog_slideshow']."' , blog_slideshow_auto_start='".$_REQUEST['blog_slideshow_auto_start']."' , disable_controls='".$_REQUEST['disable_controls']."' , caption_location='".$_REQUEST['caption_location']."', noupsize='".$_REQUEST['noupsize']."', contain_width='".$_REQUEST['contain_width']."', contain_height='".$_REQUEST['contain_height']."' , blog_frame='".$_REQUEST['blog_frame']."', bg_use='".$_REQUEST['bg_use']."' , blog_type='".$_REQUEST['blog_type']."', transition_time='".$_REQUEST['transition_time']."', disable_thumbnails='".$_REQUEST['disable_thumbnails']."', disable_play_pause='".$_REQUEST['disable_play_pause']."', disable_next_previous='".$_REQUEST['disable_next_previous']."', disable_help='".$_REQUEST['disable_help']."', disable_animation_bar='".$_REQUEST['disable_animation_bar']."', disable_photo_slider='".$_REQUEST['disable_photo_slider']."', disable_photo_count='".$_REQUEST['disable_photo_count']."', bg_color='".$_REQUEST['bg_color']."' , slideshow_stop_end='".$_REQUEST['slideshow_stop_end']."' , thumb_type='".$_REQUEST['thumb_type']."', thumb_style='".$_REQUEST['thumb_style']."', thumb_width='".$_REQUEST['thumb_width']."' , max_photo_display_width='".$_REQUEST['max_photo_display_width']."', slideshow_fixed_height='".$_REQUEST['slideshow_fixed_height']."', thumb_scroller_open='".$_REQUEST['thumb_scroller_open']."', photo_social_share='".$_REQUEST['photo_social_share']."', jthumb_height='".$_REQUEST['jthumb_height']."' , jthumb_margin='".$_REQUEST['jthumb_margin']."', photo_align='".$_REQUEST['photo_align']."', thumb_scroller='".$_REQUEST['thumb_scroller']."', allow_favs='".$_REQUEST['allow_favs']."', thumbactions='".$_REQUEST['thumbactions']."', disable_filename='".$_REQUEST['disable_filename']."', photo_search='".$_REQUEST['photo_search']."', thumb_open_first='".$_REQUEST['thumb_open_first']."', add_style='".$_REQUEST['add_style']."' , thumb_file='".$_REQUEST['thumb_file']."', disable_icons='".$_REQUEST['disable_icons']."', stacked_width='".$_REQUEST['stacked_width']."', stacked_margin='".$_REQUEST['stacked_margin']."', enable_compare='".$_REQUEST['enable_compare']."', enable_time_blocks='".$_REQUEST['enable_time_blocks']."', from_time='".$_REQUEST['from_time']."', to_time='".$_REQUEST['to_time']."', search_length='".$_REQUEST['search_length']."' WHERE date_id='".$_REQUEST['date_id']."' ");
		$_SESSION['sm'] = "Settings Saved";
		session_write_close();
		header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&showSuccess=1");
		exit();
}
?>
<?php if($_REQUEST['showSuccess'] == "1") { ?>
		<script>
		showSuccessMessage("Settings Saved");
		setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>

<div class="pageContent"><h1>Photo Display</h1>
Here you can adjust how photos are displayed on the page.</div>

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
<?php if($date['thumb_style'] == "1") { ?>
	thumbtype = "standard";
<?php } ?>
<?php if($date['thumb_style'] == "0") { ?>
	thumbtype = "justified";
<?php } ?>
<?php if($date['thumb_style'] == "2") { ?>
	thumbtype = "stacked";
<?php } ?>

 $(document).ready(function(){
	selectdisplayoption('<?php print $date['blog_type'];?>');
	<?php if($date['blog_type'] == "gallery") { ?>
	selectthumbtype(thumbtype);
	<?php } ?>
 });

	function showmoredisplayoptions() { 
		$("#otheroptions").slideToggle(200);
	}
</script>


<form name="register" action="<?php print $_SERVER['PHP_SELF'];?>" method="post" style="padding:0; margin: 0;" onSubmit="return checkForm('.optrequired');">
<div style="width: 29%; float: left;">
<div class="pc">
<h3>Select how to display the photos</h3>
</div>
<?php if($dcat['cat_type'] !== "registry") { ?>
<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="gallery" id="blog_type_thumbs" <?php if($date['blog_type'] == "gallery") { print "checked"; } ?>  onClick="selectdisplayoption('gallery');">  <label for="blog_type_thumbs">Thumbnail gallery</label></h3>
Thumbnails are shown on the page where you would click to view full screen.</div>
<?php } ?>


<?php if($dcat['cat_type'] == "clientphotos") { ?>
<div class="pc center"><a href="" onclick="showmoredisplayoptions(); return false;">more display options</a></div>
<div>&nbsp;</div>
<?php } ?>


<div id="otheroptions" class="<?php if($dcat['cat_type'] == "clientphotos") { ?>hide<?php } ?>">
<div>&nbsp;</div>
<?php if($dcat['cat_type'] == "clientphotos") { ?>
<div class="pc" style="color: #890000;">The following display options DO NOT offer purchasing options. Do not select if you are selling photos in this gallery.</div>
<div>&nbsp;</div>
<?php } ?>

	<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="nextprevious" id="blog_type_slideshow" <?php if($date['blog_type'] == "nextprevious") { print "checked"; } ?>  onClick="selectdisplayoption('nextprevious');">  <label for="blog_type_slideshow">As a slideshow</label></h3>
	Photos display on the page as a slideshow.</div>
	<div>&nbsp;</div>
	<?php if($dcat['cat_type'] !== "registry") { ?>


	<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="standardlist" id="blog_type_scroller" <?php if($date['blog_type'] == "standardlist") { print "checked"; } ?>  onClick="selectdisplayoption('standardlist');">  <label for="blog_type_scroller">Scroller</label></h3>
	Photos display straight down the page.</div>
	<div>&nbsp;</div>

	<?php if($setup['gif_show'] == true) { ?>
	<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="gifs" id="blog_type_gif" <?php if($date['blog_type'] == "gifs") { print "checked"; } ?>  onClick="selectdisplayoption('gifs');">  <label for="blog_type_gif">GIFs</label></h3>
	Photos display straight down the page.</div>
	<div>&nbsp;</div>
	<?php } ?>

	<?php } ?>
	<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="onpagewithminis" id="blog_type_standard" <?php if($date['blog_type'] == "onpagewithminis") { print "checked"; } ?>  onClick="selectdisplayoption('onpagewithminis');">  <label for="blog_type_standard">Standard photo on page with minis</label></h3>
	This will display the photo on the page and if there is more than one photo, mini thumbnails are shown to the side of the first photo.</div>
	<div>&nbsp;</div>

	<div class="underline"><h3><input type="radio"  class="checkbox" name="blog_type" value="onephoto" id="blog_type_one" <?php if($date['blog_type'] == "onephoto") { print "checked"; } ?>  onClick="selectdisplayoption('onephoto');">  <label for="blog_type_one">One Photo</label></h3>
	Select this option if you are wanting to just display one photo on the page aligned left or right.</div>
	<div>&nbsp;</div>
</div>

<?php if($dcat['cat_type'] !== "registry") { ?>
<?php if($sytist_store == true) { ?>
<div id="sharefav" <?php if(($date['blog_type'] == "standardlist")||($date['blog_type'] == "onpagewithminis")||($date['blog_type'] == "onephoto")==true) { print "style=\"display: none;\""; } ?>>

<div class="pc"><input type="checkbox"  class="checkbox" name="photo_social_share" id="photo_social_share" value="1" <?php if($date['photo_social_share'] == "1") { print "checked"; } ?>> <label for="photo_social_share">Enable social share feature on individual photos. </label></div>
<div class="pc"><input type="checkbox"  class="checkbox" name="allow_favs" id="allow_favs" value="1" <?php if($date['allow_favs'] == "1") { print "checked"; } ?>> <label for="allow_favs">Enable add to favorites.</label> </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="photo_search" id="photo_search" value="1" <?php if($date['photo_search'] == "1") { print "checked"; } ?>> <label for="photo_search">Enable photo search.</label> </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="enable_compare" id="enable_compare" value="1" <?php if($date['enable_compare'] == "1") { print "checked"; } ?>> <label for="enable_compare">Enable compare photos.</label> </div>
<div class="pc"><input type="checkbox"  class="checkbox" name="enable_time_blocks" id="enable_time_blocks" value="1" <?php if($date['enable_time_blocks'] == "1") { print "checked"; } ?>> <label for="enable_time_blocks">Enable time blocks.</label> <div class="moreinfo" info-data="timeblocks"><div class="info"></div></div></div>
<?php // if($date['enable_time_blocks'] <= 0) { print "style=\"display: none;\""; }?>

<div id="time_blocks">
	<div class="pc left">
		<div>Start Time</div>
		<div>
		<select name="from_time" id="from_time">
		<?php $s = 0;
			while($s <= 23) { ?>
			<option value="<?php print $s;?>" <?php if($s == $date['from_time']) { print "selected"; } ?>><?php print date("h:i A", mktime($s, 0, 0, 0, 0, 0)); ?></option>
		
		<?php 	
		$s++;
			}
		?>
		</select>
		</div>
	</div>

	<div class="pc left">
		<div>End Time</div>
		<div>
		<select name="to_time" id="to_time">
		<?php $s = 0;
			while($s <= 23) { ?>
			<option value="<?php print $s;?>" <?php if($s == $date['to_time']) { print "selected"; } ?>><?php print date("h:i A", mktime($s, 0, 0, 0, 0, 0)); ?></option>
		
		<?php 	
		$s++;
			}
		?>
		</select>
		</div>
	</div>
	<div class="clear"></div>

	<div class="pc left">
		<div>Blocks</div>
		<div>
		<select name="search_length" id="search_length">
		<option value="15" <?php if($date['search_length'] == "15") { print "selected"; } ?>>15 Minutes</option>
		<option value="20" <?php if($date['search_length'] == "20") { print "selected"; } ?>>20 Minutes</option>
		<option value="30" <?php if($date['search_length'] == "30") { print "selected"; } ?>>30 Minutes</option>
		<option value="60" <?php if($date['search_length'] == "60") { print "selected"; } ?>>1 Hour</option>
		</select>
		</div>
	</div>
	<div class="clear"></div>

</div>

<?php } ?>











</div>



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
	<option value="after" <?php if($date['blog_location'] == "after") { print "selected"; } ?>>Show photos before text</option>
	<option value="before" <?php if($date['blog_location'] == "before") { print "selected"; } ?>>Show photos after text</option>
	</select>
	&nbsp; 
	</div>

-->



	<div class="underline">
	Photo size to use<br>
	 <input type="radio"  class="checkbox" name="blog_photo_file" id="pic_pic"  value="pic_pic" <?php if($date['blog_photo_file'] == "pic_pic") { print "checked"; } ?> class="checkbox"> <label for="pic_pic">Small</label> &nbsp; 
	<input type="radio"  class="checkbox" name="blog_photo_file"  id="pic_large" value="pic_large" <?php if($date['blog_photo_file'] == "pic_large") { print "checked"; } ?> class="checkbox"> <label for="pic_large">Large</label> &nbsp; 
 
	&nbsp; 
	</div>
		<div class="underline alloptions onephoto"> Align: <select name="photo_align">
		<option value="left" <?php if($date['photo_align'] == "left") { print "selected"; } ?>>Left</option>
		<option value="right" <?php if($date['photo_align'] == "right") { print "selected"; } ?>>Right</option>
		</select>
	</div>

	<div class="alloptions gallery">

	<div class="underline">
	Select thumbnail type<br>
	<div style="float: left; width: 49%;" class="center">
		<div class="pc"> <input type="radio"  class="checkbox" name="thumb_style" id="thumbtypestacked" value="2" <?php if($date['thumb_style'] == "2") { print "checked"; } ?> onchange="selectthumbtype('stacked');">	Stacked thumbnails</div>
		<div class="pc"><label for="thumbtypestacked"><img src="graphics/thumbs-stacked.jpg" style="width: 100%; max-width: 600px;"  border="0"></label></div>
	</div>
	<div style="float: right; width: 49%;" class="center">
		<div class="pc"><input type="radio"  class="checkbox" name="thumb_style"   id="thumbtypejustified" value="0" <?php if($date['thumb_style'] == "0") { print "checked"; } ?> onchange="selectthumbtype('justified');">	Justified</div>
		<div class="pc"><label for="thumbtypejustified"><img src="graphics/thumbs-justified.jpg" style="width: 100%; max-width: 600px;"  border="0"></label></div>
	</div>

		<div style="float: left; width: 49%;" class="center">
		<div class="pc"> <input type="radio"  class="checkbox" name="thumb_style" id="thumbtypestandard" value="1" <?php if($date['thumb_style'] == "1") { print "checked"; } ?> onchange="selectthumbtype('standard');">	Standard thumbnails</div>
		<div class="pc"><label for="thumbtypestandard"><img src="graphics/thumbs-standard.jpg" style="width: 100%; max-width: 600px;"  border="0"></label></div>
	</div>


	<div class="clear"></div>
	</div>

	<div class="underline thumboptions stacked alloptions">
		<div>
		<div class="left" style="width: 50%;">
			<div>Row width</div>
			<div><input type="text" name="stacked_width" id="stacked_width" value="<?php print $date['stacked_width'];?>" size="4" class="center">px.  Example: 250</div>
		</div>

		<div>
			<div>Spacing (margin)</div>
			<div><input type="text" name="stacked_margin" id="stacked_margin" value="<?php print $date['stacked_margin'];?>" size="4" class="center"> Example: 4</div>
		</div>
		<div class="clear"></div>
		</div>
	</div>
	</div>

	<div class="underline thumboptions stacked alloptions">
		<input type="checkbox"  class="checkbox" name="add_style" id="add_style" value="1" <?php if($date['add_style'] == "1") { print "checked"; } ?>> <label for="add_style">Display inside a styled container from your theme. </label>
	</div>

	<div class="underline thumboptions standard  alloptions">
		
		<input type="radio"  class="checkbox" name="thumb_file" id="thumb_file1" value="pic_th" <?php if($date['thumb_file'] == "pic_th") { print "checked"; } ?>> <label for="thumb_file1">Use Thumbnail</label> &nbsp; 
		<input type="radio"  class="checkbox" name="thumb_file" id="thumb_file2" value="pic_pic" <?php if($date['thumb_file'] == "pic_pic") { print "checked"; } ?>> <label for="thumb_file2">Use Small photo</label>

	</div>

	<div class="underline thumboptions standard stacked alloptions">
		<input type="checkbox"  class="checkbox" name="noupsize" id="noupsize" value="1" <?php if($date['noupsize'] == "1") { print "checked"; } ?>> <label for="noupsize">Use hover preview when mouse over thumbnail.</label>
	</div>

	<div class="underline thumboptions standard  alloptions ">
		<input type="checkbox"  class="checkbox" name="thumbactions" id="thumbactions" value="1" <?php if($date['thumbactions'] == "1") { print "checked"; } ?>>	 <label for="thumbactions">Mouseover show  filename & icons under thumbnail. Unchecked will just display them.</label>
	</div>
	<div class="underline thumboptions standard stacked justified alloptions">
		<input type="checkbox"  class="checkbox" name="disable_filename" id="disable_filename" value="1" <?php if($date['disable_filename'] == "1") { print "checked"; } ?>>	<label for="disable_filename">Do not display filename under thumbnail.</label>
	</div>
	<div class="underline thumboptions standard stacked justified alloptions">
		<input type="checkbox"  class="checkbox" name="disable_icons" id="disable_icons" value="1" <?php if($date['disable_icons'] == "1") { print "checked"; } ?>>	<label for="disable_icons">Do not display icons under thumbnail.</label>
	</div>


	<div class="underline thumboptions justified alloptions">
		<div>
		<div class="left" style="width: 50%;">
			<div>Row height</div>
			<div><input type="text" name="jthumb_height" id="jthumb_height" value="<?php print $date['jthumb_height'];?>" size="4" class="center"> Example: 250</div>
		</div>

		<div>
			<div>Spacing (margin)</div>
			<div><input type="text" name="jthumb_margin" id="jthumb_margin" value="<?php print $date['jthumb_margin'];?>" size="4" class="center"> Example: 4</div>
		</div>
		<div class="clear"></div>
		</div>
	</div>
	</div>



	<div class="underline alloptions gallery">
	 <input type="checkbox"  class="checkbox" name="thumb_open_first"  value="1" <?php if($date['thumb_open_first'] == "1") { print "checked"; } ?> id="thumb_open_first"> <label for="thumb_open_first">When page is opened, display first photo and start slideshow</label>
	</div>


	<div class="underline alloptions standardlist">
	Caption location<br>
	 <input type="radio"  class="checkbox" name="caption_location"  value="0" <?php if($date['caption_location'] == "0") { print "checked"; } ?> id="caption_1">	<label for="caption_1">On Photo</label><br>
	 <input type="radio"  class="checkbox" name="caption_location"  value="1" <?php if($date['caption_location'] == "1") { print "checked"; } ?> id="caption_2">	<label for="caption_2">Below Photo</label>
	</div>

	<div class="underline alloptions nextprevious">
	 <input type="checkbox"  class="checkbox" name="blog_slideshow_auto_start"  value="1" <?php if($date['blog_slideshow_auto_start'] == "1") { print "checked"; } ?> id="autostart"> <label for="autostart">Auto start slideshow.</label>
	</div>

	<div class="underline alloptions nextprevious">
	 <input type="checkbox"  class="checkbox" name="slideshow_stop_end"  value="1" <?php if($date['slideshow_stop_end'] == "1") { print "checked"; } ?> id="loop"> <label for="loop">Loop</label>
	</div>


	<div class="underline alloptions nextprevious">
	<input type="checkbox"  class="checkbox" name="disable_controls"  value="1" <?php if($date['disable_controls'] == "1") { print "checked"; } ?> id="disable_controls"> <label for="disable_controls">Disable next / previous / play controls.</label> 
	</div>

	<div class="underline alloptions nextprevious">
	<input type="checkbox"  class="checkbox" name="slideshow_fixed_height"  value="1" <?php if($date['slideshow_fixed_height'] == "1") { print "checked"; } ?> id="slideshow_fixed_height"> <label for="slideshow_fixed_height">Set the height of the slideshow to the height of the first landscape photo.</label> 
	</div>

	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="thumb_scroller"  value="1" <?php if($date['thumb_scroller'] == "1") { print "checked"; } ?> id="thumb_scroller"> <label for="thumb_scroller">Show scrolling thumbnails under slideshow.</label> 
	</div>


	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="thumb_scroller_open"  value="1" <?php if($date['thumb_scroller_open'] == "1") { print "checked"; } ?>> <label for="thumb_scroller_open">Scrolling thumbnails automatically open. Uncheck to keep closed until clicked. </label>
	</div>

	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="bg_use"  value="1" <?php if($date['bg_use'] == "1") { print "checked"; } ?>>	Use colors assigned to photos for backgrounds & borders.
	</div>

	<div class="underline alloptions">
	<input type="checkbox"  class="checkbox" name="disable_next_previous"  value="1" <?php if($date['disable_next_previous'] == "1") { print "checked"; } ?>>	Disable next / previous controls. 
	</div>


	<div class="underline alloptions">
	 <!-- <input type="checkbox"  class="checkbox" name="disable_play_pause"  value="1" <?php if($date['disable_play_pause'] == "1") { print "checked"; } ?>>	Disable play / pause controls -->
	</div>


	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="disable_thumbnails"  value="1" <?php if($date['disable_thumbnails'] == "1") { print "checked"; } ?>>	Disable thumbnail menu.
	</div>

	<div class="underline alloptions">
	 <input type="checkbox"  class="checkbox" name="disable_photo_count"  value="1" <?php if($date['disable_photo_count'] == "1") { print "checked"; } ?>>	Disable photo count (ie: 2 of 10).
	</div>


	<div class="underline alloptions nextprevious standardlist gallery">
	 <input type="checkbox"  class="checkbox" name="blog_kill_side_menu"  value="1" <?php if($date['blog_kill_side_menu'] == "1") { print "checked"; } ?> id="blog_kill_side_menu">	<label for="blog_kill_side_menu">Disable website  side bar (if in use).</label>
	</div>


<div class="underline alloptions nextprevious gallery">Slideshow seconds between photos: 


<select name="blog_seconds">
<?php $s = 1;
while($s <=10) { ?>
<option value="<?php print $s;?>" <?php if($s == $date['blog_seconds']) { print "selected"; } ?>><?php print $s;?></option>
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
<option value="<?php print $s;?>" <?php if($s == $date['transition_time']) { print "selected"; } ?>><?php print number_format($s / 1000,2); ?> </option> 
<?php
	$s = $s + 100;
}
?>
</select> seconds
</div>

<div class="underline" style="text-align: center;"> 
	<input type="hidden" name="do" value="<?php print $_REQUEST['do'];?>">
	<input type="hidden" name="action" value="managePhotos">
	<input type="hidden" name="subdo" value="updateSettings">
	<input type="hidden" name="date_id" value="<?php  print $_REQUEST['date_id'];?>">
	<input type="hidden" name="gal_id" value="<?php  print $pb['gal_id'];?>">
	<input type="submit" name="submit" value="Update Options" class="submit"  id="submitButton">
</div>
</div>

</div>
<div class="clear"></div>


</form>
<div>&nbsp;</div>	
<?php include "infos.php"; ?>


<?php require "w-footer.php"; ?>