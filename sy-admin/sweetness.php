<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<script>

function savedata(classname) { 
	var fields = {};
	var stop = false;
	$('#saveform').text("saving...");
	$('#saveform').removeClass("submit").addClass("submitsaving");
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.attr("value"); 
				// alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			} else { 
				fields[$this.attr('name')] = "";
			}
			
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}

	});
		
		
	fields['slide_link'] = $("#slide_link").val();

	<?php if($setup['demo_mode'] !== true) { ?>
	$.post("sweetness.php", fields,	function (data) { 
		//  alert(data);
		sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	});
	<?php } else {  ?>
		sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	<?php } ?>
	}

</script>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>
<?php adminsessionCheck(); ?>
<?php 

if($_REQUEST['action'] == "addPhotos") { 
	$show = doSQL("ms_show", "*", "WHERE show_id='".$_SESSION['selectclf']."' "); 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$show['feat_page_id']."' ");
	$last = doSQL("ms_blog_photos", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_clf='".$show['show_id']."' ORDER BY bp_clf_order DESC ");
	$dorder = $last['bp_clf_order'] + 1;
	foreach($_SESSION['heldPhotos'] AS $photo) { 
		$chk = doSQL("ms_blog_photos","*", "WHERE bp_pic='".$photo."' AND bp_blog='".$date['date_id']."' ");
		if($chk['bp_id'] > 0) { 
			$dorder++;
			updateSQL("ms_blog_photos", "bp_clf='".$show['show_id']."', bp_clf_order='".$dorder."' WHERE bp_blog='".$date['date_id']."' AND bp_pic='".$photo."' ");
		}
	}
	unset($_SESSION['selectclf']);
	unset($_SESSION['heldPhotos']);
	header("location: index.php?do=news&action=managePhotos&date_id=".$date['date_id']."");
	session_write_close();
	exit();
	
}
if($_REQUEST['show_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE show_id='".$_REQUEST['show_id']."' "); 
}


if($_REQUEST['feat_page_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$_REQUEST['feat_page_id']."' "); 
}
if($_REQUEST['feat_cat_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$_REQUEST['feat_cat_id']."' "); 
}

if($show['feat_page_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$show['feat_page_id']."' ");
}
if($show['feat_cat_id'] > 0) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$show['feat_cat_id']."' ");
}

if($_REQUEST['feat_page_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['feat_page_id']."' ");
}
if($_REQUEST['feat_cat_id'] > 0) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['feat_cat_id']."' ");
}


if($_REQUEST['action'] == "setselect") { 
	$_SESSION['selectclf'] = $_REQUEST['show_id'];
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	session_write_close();
	exit();

}



if(!empty($date['date_title'])) { 
	$for = $date['date_title'];
}
if(!empty($cat['cat_name'])) { 
	$for = $cat['cat_name'];
}


?>
<script>
show_id = '<?php print $show['show_id'];?>';
enabled = '<?php print $show['enabled'];?>';
date_id = '<?php print $date['date_id'];?>';
cat_id = '<?php print $cat['cat_id'];?>';

$(document).ready(function(){
	checksideitems();

	$(".color").each(function() {
		this_id = $(this).attr("id");
		thiscolor = $(this).attr("thisval");
		var myPicker = new jscolor.color(document.getElementById(this_id), {})
//		myPicker.fromString("000000")  // now you can access API via 'myPicker' variable
	});
	$(".sweetstatus").hide();

	if(show_id > 0 && enabled == '1') { 
		$("#sweet-"+date_id+"-on").show();
	}
	if(show_id > 0 && enabled == '0') { 
		$("#sweet-"+date_id+"-off").show();
	}

	$(".formfield").change(function() { 
		checksideitems();
	});

});

function checksideitems() { 

	if($("#enable_side").attr("checked") && $("#enable_side").attr("data-subs") == "0") { 
		$(".sideitems").show();
	} else { 
		$(".sideitems").hide();
	}

	if($("#enable_side").attr("checked")) { 
		$(".fullscreen_width").show();
	} else { 
		$(".fullscreen_width").hide();
	}


	if($("#feature_type").val() == "getfeatureslide") {
		$(".homecats").hide();
		$("#show_page_text_div").show();
	} else { 
		$(".homecats").show();
		$("#show_page_text_div").hide();
	}
}
</script>

<div class="pc left"><h1>CLF-Display - <?php print $for;?></h1></div>
<div class="pc right textright buttons">
<?php if($show['show_id'] > 0) { ?>
	<?php if($cat['cat_id'] > 0) { ?>
	<a href="<?php print $setup['temp_url_folder'];?><?php print $cat['cat_folder'];?>/?sweetness=<?php print md5($show['show_id']);?>" target="_blank">Preview</a> 

	<?php } else { ?>
	<a href="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?sweetness=<?php print md5($show['show_id']);?>" target="_blank">Preview</a> 
	<?php } ?>
<?php } ?> 

<a href="" id="saveform" onclick="savedata('formfield'); return false;">Save</a>


</div>
<div class="clear"></div>
 <?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="http://www.picturespro.com/sytist-manual/site-design/clf-display/" target="_blank">Click here to learn more about the CLF-Display.</a></div><?php } ?>

<?php 
if($_REQUEST['action'] == "save") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(is_array($_REQUEST['feat_main_cats'])) { 
		foreach($_REQUEST['feat_main_cats'] AS $c) { 
			$com++;
			if($com > 1) { 
				$feat_main_cats.=",";
			}
			print "<li>".$c;
			$feat_main_cats .=$c;
		}
	}
	$com = 0;
	if(is_array($_REQUEST['feat_side_cats'])) { 
		foreach($_REQUEST['feat_side_cats'] AS $c) { 
			$com++;
			if($com > 1) { 
				$feat_side_cats.=",";
			}
			print "<li>".$c;
			$feat_side_cats .=$c;
		}
	}

	if($_REQUEST['show_id'] > 0) { 
		print "UPDATING ::::::::::::::::::::::::: ";
		$show_id = $_REQUEST['show_id'];
		updateSQL("ms_show", "
		feat_page_id='".$_REQUEST['feat_page_id']."',
		main_full_screen='".$_REQUEST['main_full_screen']."',
		feat_cat_id ='".$_REQUEST['feat_cat_id']."',
		feat_main_cats='".$feat_main_cats."',
		feat_main_limit='".$_REQUEST['feat_main_limit']."',
		initialopacity='".$_REQUEST['initialopacity']."',
		hoveropacity='".$_REQUEST['hoveropacity']."',
		behind_text_color='".$_REQUEST['behind_text_color']."',
		behind_text_opacity='".$_REQUEST['behind_text_opacity']."',
		mainfeaturetimer='".$_REQUEST['mainfeaturetimer']."',
		slide_speed='".$_REQUEST['slide_speed']."',
		fullscreen_width='".$_REQUEST['fullscreen_width']."',
		enable_side='".$_REQUEST['enable_side']."',
		feat_side_title='".$_REQUEST['feat_side_title']."',
		feat_side_text	='".$_REQUEST['feat_side_text']."',
		feat_side_limit='".$_REQUEST['feat_side_limit']."',
		feat_side_cats='".$feat_side_cats."',
		feature_type='".$_REQUEST['feature_type']."',
		enabled='".$_REQUEST['enabled']."',
		mainphotoratio='".$_REQUEST['mainphotoratio']."',
		catphotoratio='".$_REQUEST['catphotoratio']."',
		main_photo_bg_color='".$_REQUEST['main_photo_bg_color']."',
		change_effect='".$_REQUEST['change_effect']."',
		enable_nav='".$_REQUEST['enable_nav']."',
		nav_display_count='".$_REQUEST['nav_display_count']."',
		loading_bg_color='".$_REQUEST['loading_bg_color']."',
		loading_font_color='".$_REQUEST['loading_font_color']."',
		loading_text='".$_REQUEST['loading_text']."',
		main_menu='".$_REQUEST['main_menu']."',
		show_cat_name='".$_REQUEST['show_cat_name']."',
		sm_find_photos='".$_REQUEST['sm_find_photos']."',
		show_page_text='".$_REQUEST['show_page_text']."',
		disable_page_listing='".$_REQUEST['disable_page_listing']."',
		contain_portrait='".$_REQUEST['contain_portrait']."',
		contain_landscape='".$_REQUEST['contain_landscape']."',
		show_photos='".$_REQUEST['show_photos']."', 
		show_photos_subs='".$_REQUEST['show_photos_subs']."' 

		WHERE show_id='".$_REQUEST['show_id']."' ");


	} else { 
		insertSQL("ms_show", "
		feat_page_id='".$_REQUEST['feat_page_id']."',
		main_full_screen='".$_REQUEST['main_full_screen']."',
		feat_cat_id ='".$_REQUEST['feat_cat_id']."',
		feat_main_cats='".$feat_main_cats."',
		feat_main_limit='".$_REQUEST['feat_main_limit']."',
		initialopacity='".$_REQUEST['initialopacity']."',
		hoveropacity='".$_REQUEST['hoveropacity']."',
		behind_text_color='".$_REQUEST['behind_text_color']."',
		behind_text_opacity='".$_REQUEST['behind_text_opacity']."',
		mainfeaturetimer='".$_REQUEST['mainfeaturetimer']."',
		slide_speed='".$_REQUEST['slide_speed']."',
		fullscreen_width='".$_REQUEST['fullscreen_width']."',
		enable_side='".$_REQUEST['enable_side']."',
		feat_side_title='".$_REQUEST['feat_side_title']."',
		feat_side_text	='".$_REQUEST['feat_side_text']."',
		feat_side_limit='".$_REQUEST['feat_side_limit']."',
		feat_side_cats='".$feat_side_cats."',
		feature_type='".$_REQUEST['feature_type']."',
		enabled='".$_REQUEST['enabled']."' ,
		mainphotoratio='".$_REQUEST['mainphotoratio']."',
		catphotoratio='".$_REQUEST['catphotoratio']."',
		main_photo_bg_color='".$_REQUEST['main_photo_bg_color']."',
		change_effect='".$_REQUEST['change_effect']."',
		enable_nav='".$_REQUEST['enable_nav']."',
		nav_display_count='".$_REQUEST['nav_display_count']."',
		loading_bg_color='".$_REQUEST['loading_bg_color']."',
		loading_font_color='".$_REQUEST['loading_font_color']."',
		loading_text='".$_REQUEST['loading_text']."',
		main_menu='".$_REQUEST['main_menu']."',
		show_cat_name='".$_REQUEST['show_cat_name']."',
		sm_find_photos='".$_REQUEST['sm_find_photos']."',
		show_page_text='".$_REQUEST['show_page_text']."',
		disable_page_listing='".$_REQUEST['disable_page_listing']."',
		contain_portrait='".$_REQUEST['contain_portrait']."',
		contain_landscape='".$_REQUEST['contain_landscape']."',
		show_photos='".$_REQUEST['show_photos']."', 
		show_photos_subs='".$_REQUEST['show_photos_subs']."' 

		 ");
	}

	exit();
	
	
	?>


<?php  } ?>
<?php if($show['show_id'] <= 0) { 
	$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");
	$show['main_full_screen'] = $dshow['main_full_screen'];
	$show['initialopacity'] = $dshow['initialopacity'];
	$show['hoveropacity'] = $dshow['hoveropacity'];
	$show['main_link_spacing'] = $dshow['main_link_spacing'];
	$show['main_photo_bg_color'] = $dshow['main_photo_bg_color'];
	$show['slide_speed'] = $dshow['slide_speed'];
	$show['mainfeaturetimer'] = $dshow['mainfeaturetimer'];
	$show['fullscreen_width'] = $dshow['fullscreen_width'];
	$show['change_effect'] = $dshow['change_effect'];
	$show['enable_nav'] = $dshow['enable_nav'];
	$show['nav_display_count'] = $dshow['nav_display_count'];
	$show['enable_logo'] = $dshow['enable_logo'];
	$show['mainphotoratio'] = $dshow['mainphotoratio'];
	$show['contain_portrait'] = $dshow['contain_portrait'];
	$show['contain_landscape'] = $dshow['contain_landscape'];



}
?>
<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
<input type="hidden" name="feat_page_id" id="feat_page_id" value="<?php print $date['date_id'];?>" class="formfield">
<input type="hidden" name="feat_cat_id" id="feat_cat_id" value="<?php print $cat['cat_id'];?>" class="formfield">
<input type="hidden" name="show_id" id="show_id" value="<?php print $show['show_id'];?>" class="formfield">
<input type="hidden" name="action" id="action" value="save" class="formfield">

<div style="width: 50%; float: left;">
	<div style="padding: 16px;">

	<div class="underline">
		<div class="label"><input type="checkbox" name="enabled" id="enabled" value="1" <?php if($show['enabled'] == "1") { print "checked"; } ?> class="formfield"> <label for="enabled"> Enabled</label></div>
	</div>

	<input type="hidden" name="main_full_screen" id="main_full_screen" value="1" class="formfield">

	<div class="underline sideitems category fullscreen_width">
		<div class="label">
		<select name="fullscreen_width" id="fullscreen_width" class="formfield">
		<?php $iop = 50;
		while($iop <= 80) {
		?>
			<option value="<?php print $iop;?>" <?php if($show['fullscreen_width'] == $iop) { print "selected"; } ?>><?php print $iop;?>%</option>
		<?php 
			$iop++;
		} ?>

		</select> Width
		</div>
	</div>


	<!-- 
	<div class="underline">
		<div class="label">
		<select name="mainphotoratio" id="mainphotoratio" class="formfield">
		<?php $iop = ".2";
		while($iop < 1.5) {
			?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['mainphotoratio'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php 
			$iop = $iop + .1;
			} ?>

		</select> Main photo ratio
		</div>
	</div>
	-->

	<?php if($date['page_home'] == "1") { ?>
	<div class="underline">
		<div class="label">Select what to display</div>
		<div>
		<select name="feature_type" id="feature_type"  class="formfield">
		<option value="getfeature" <?php if($show['feature_type'] == "getfeature") { print "selected"; } ?>>Pages from sections or categories</option>
		<option value="getfeatureslide" <?php if($show['feature_type'] == "getfeatureslide") { print "selected"; } ?>>Photos select for the page </option>

		</select>
		</div>
	</div>

	<?php } elseif(($date['date_id'] > 0)&&($date['page_home'] !== "1") ==true) {  ?>
	<input type="hidden" name="feature_type" id="feature_type" value="getfeatureslide" class="formfield">

	<?php } ?>
	<div id="show_page_text_div" class="underline">
		<div class="label"><input type="checkbox" name="show_page_text" id="show_page_text" class="formfield" value="1" <?php if($show['show_page_text'] == "1") { print "checked"; } ?>> <label for="show_page_text">Display page text over photos </label>
		</div>
	</div>
	<?php if($date['page_home'] == "1") {  ?>
	<div class="homecats">
		<div class="underlinelabel">Select a category or categories</div>
		<div class="underlinespacer">Select the categories which you want to display pages from</div>
		<div class="underline"><?php print featuredCategories($show,"feat_main_cats");?>
		<div>Hold down your CTRL key to select multiple categories.</div>
		</div>


		<div class="underline">
			<div class="label">
			<input type="text"  size="2" name="feat_main_limit" id="feat_main_limit" value="<?php print $show['feat_main_limit'];?>"  class="formfield center"> Limit number of pages display. Enter 0 to not limit.
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="underline">
		<div class="label"><input type="checkbox" name="enable_nav" id="enable_nav" value="1" <?php if($show['enable_nav'] == "1") { print "checked"; } ?> class="formfield"> <label for="enable_nav"> Enable slide navigation (next / previous)</label></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="nav_display_count" id="nav_display_count" value="1" <?php if($show['nav_display_count'] == "1") { print "checked"; } ?> class="formfield"> <label for="nav_display_count"> Display count in navigation (ie: 5 of 12)</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="contain_portrait" id="contain_portrait" value="1" <?php if($show['contain_portrait'] == "1") { print "checked"; } ?> class="formfield"> <label for="contain_portrait"> Contain portrait photos to viewing area</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="contain_landscape" id="contain_landscape" value="1" <?php if($show['contain_landscape'] == "1") { print "checked"; } ?> class="formfield"> <label for="contain_landscape"> Contain landscape photos to viewing area</label></div>
	</div>

	<div class="underline">
		<div class="label">
		<select name="initialopacity" id="initialopacity" class="formfield">
		<?php $iop = "0";
		while($iop < .9) {
			$iop = $iop + .1;?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['initialopacity'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php } ?>

		</select> Initial Photo Opacity
		</div>
	</div>


	
	<div class="underline">
		<div class="label">
		<select name="hoveropacity" id="hoveropacity" class="formfield">
		<?php $iop = "0";
		while($iop < .9) {
			$iop = $iop + .1;?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['hoveropacity'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php } ?>

		</select> Hover Photo Opacity
		</div>
	</div>
	


	<div class="underline">
		<div class="label">
		<select name="mainfeaturetimer" id="mainfeaturetimer" class="formfield">
		<?php $iop = 2000;
		while($iop < 10000) {
			$iop = $iop + 1000;?>
			<option value="<?php print $iop;?>" <?php if($show['mainfeaturetimer'] == $iop) { print "selected"; } ?>><?php print ($iop / 1000);?>  Seconds</option>
		<?php } ?>

		</select> Time between slides
		</div>
	</div>

	
	<div class="underline">
		<div class="label">
		<select name="change_effect" id="change_effect" class="formfield">
		<option value="slide" <?php if($show['change_effect'] == "slide") { print "selected"; } ?>>Slide</option>
		<option value="fade" <?php if($show['change_effect'] == "fade") { print "selected"; } ?>>Fade</option>
		</select> transition type
		</div>
	</div>


	<div class="underline">
		<div class="label">
		<select name="slide_speed" id="slide_speed" class="formfield">
		<?php $iop = 0;
		while($iop < 2000) {
			$iop = $iop + 500;?>
			<option value="<?php print $iop;?>" <?php if($show['slide_speed'] == $iop) { print "selected"; } ?>><?php print ($iop / 1000);?>  <?php if($iop <= 1000) { print "Second"; } else { print "Seconds"; } ?></option>
		<?php } ?>

		</select> Transition timer
		</div>
	</div>



	</div>
</div>
<?php $show['feat_side_cats'] = str_replace("Array","",$show['feat_side_cats']); ?>


<div style="width: 50%; float: left;">
	<div style="padding: 16px;">

	<?php if($cat['cat_id'] > 0) { ?>
		<input type="hidden" name="feat_side_cats[]" id="feat_side_cats" class="formfield" value="<?php print $cat['cat_id'];?>">
		<input type="hidden" name="feat_main_cats[]" id="feat_main_cats" class="formfield" value="<?php print $cat['cat_id'];?>">
	<input type="hidden" name="feature_type" id="feature_type" value="getfeature" class="formfield">

			<div class="underline hidden">
		</div>
		<div class="underline">
			<div class="label"><input type="checkbox" name="show_cat_name" id="show_cat_name" value="1"  class="formfield" <?php if($show['show_cat_name'] == "1") { print "checked"; } ?>> <label for="show_cat_name">Display Category Name Over Photos</label></div>
		</div>
	<?php } ?>

	<?php if(($date['page_home'] == "1") || ($cat['cat_id'] > 0) == true){ ?>

		<div class="underline">
			<div class="label"><input type="checkbox" name="enable_side" id="enable_side" value="1" <?php if($show['enable_side'] == "1") { print "checked"; } ?> class="formfield" data-subs="0"> <label for="enable_side">Enable side menu</label></div>
		</div>
	<?php } ?>
	<?php if($date['page_home'] == "1") {  ?>

		<div class=" sideitems">
		<div class="underlinelabel">Select a category or categories</div>
		<div class="underlinespacer">Select the categories which you want to display pages from in the side menu</div>
		<div class="underline"><?php print featuredCategories($show,"feat_side_cats");?>
		<div>Hold down your CTRL key to select multiple categories.</div>
		</div>

	<?php } ?>

	<?php if(($date['page_home'] == "1") || ($cat['cat_id'] > 0) == true){ ?>

		<div class="underline">
			<div class="label"><input type="checkbox" name="disable_page_listing" id="disable_page_listing" value="1" <?php if($show['disable_page_listing'] == "1") { print "checked"; } ?> class="formfield"> <label for="disable_page_listing">Disable listing of pages under display</label></div>
		</div>

	<?php } ?>

		<div class="underline sideitems">
			<div class="label"><input type="checkbox" name="sm_find_photos" id="sm_find_photos" value="1" <?php if($show['sm_find_photos'] == "1") { print "checked"; } ?> class="formfield"> <label for="sm_find_photos">Display Find My Photos Button</label></div>
		</div>

		<div class="underline sideitems">
			<div class="label">Title to show in the side menu above the pages (optional) </div>
			<div><input type="text" name="feat_side_title" id="feat_side_title" value="<?php print $show['feat_side_title'];?>" class="field100 formfield"></div>
		</div>

		<div class="underline sideitems">
			<div class="label">Text to show in the side menu above the pages (optional)</div>
			<div><textarea name="feat_side_text" id="feat_side_text" class="field100 formfield"><?php print $show['feat_side_text'];?></textarea></div>
		</div>

	<?php if(($date['page_home'] !== "1") && ($cat['cat_id'] <= 0) == true){ ?>
		<div class="underlinelabel">Select what to display</div>
		<div class="underline">
		<select name="show_photos" id="show_photos"  class="field100 formfield">
		<option value="all" <?php if($show['show_photos'] == "all") { print "selected"; } ?>>All Photos</option>
		<!-- <option value="cover" <?php if($show['show_photos'] == "cover") { print "selected"; } ?>>Cover Photo Only</option> -->
		<option value="selected" <?php if($show['show_photos'] == "selected") { print "selected"; } ?>>I Will Select Photos From The Gallery</option>
		<option value="random" <?php if($show['show_photos'] == "random") { print "selected"; } ?>>Randomly Select Photos</option>
		</select>
		</div>


		<?php if($show['show_photos'] == "selected") {?>
			<div class="underlinelabel">You have selected <?php print countIt("ms_blog_photos", "WHERE bp_clf='".$show['show_id']."' "); ?> photos</div>
			
			<div class="underline"><a href="sweetness.php?action=setselect&show_id=<?php print $show['show_id'];?>&date_id=<?php print $date['date_id'];?>">Click here to select photos</a>
			<?php if(countIt("ms_blog_photos", "WHERE bp_clf='".$show['show_id']."' ") > 0) { ?>
			<div class="right"><a href="" onclick="pagewindowedit('w-clf-photos-order.php?date_id=<?php print $date['date_id'];?>&show_id=<?php print $show['show_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;">Manage Photos</a></div>
			<div class="clear"></div>
			<?php } ?>
			
			</div>
			<div class="underlinespacer">To select photos to display, click the link above and when viewing photos in the gallery or sub galleries for this page, check the checkbox under the thumbnails and in the tray that will open at the bottom click Add Selected Photos when you are finished.</div>
		<?php } ?>

		<div class="underline">
			<div class="label"><input type="checkbox" name="enable_side" id="enable_side" value="1" <?php if($show['enable_side'] == "1") { print "checked"; } ?> class="formfield" data-subs="1"> <label for="enable_side">Show sub galleries in the side menu</label></div>
		</div>

		<div class="underlinelabel">CLF Display in Sub Galleries</div>
		<div class="underline">
		<select name="show_photos_subs" id="show_photos_subs"  class="field100 formfield">
		<option value="none" <?php if($show['show_photos_subs'] == "none") { print "selected"; } ?>>Do Not Display in Sub Galleries</option>
		<option value="main" <?php if($show['show_photos_subs'] == "main") { print "selected"; } ?>>Same Option As Above</option>
		<option value="all" <?php if($show['show_photos_subs'] == "all") { print "selected"; } ?>>All Photos In The Sub Gallery</option>
		<!-- <option value="cover" <?php if($show['show_photos_subs'] == "cover") { print "selected"; } ?>>Cover Photo Only</option> -->
		<option value="random" <?php if($show['show_photos_subs'] == "random") { print "selected"; } ?>>Randomly Select Photos From Sub Gallery</option>
		</select>
		</div>

	<?php } ?>

	</div>
</div>
<div class="clear"></div>

</form>
<?php 
function featuredCategories($show,$field) {
	global $dbcon;
	$cats = explode(",",$show[$field]);

	$fn = "gal_under";
	$match = $_REQUEST['gal_under'];
	$html .=  "<select name=\"".$field."[]\" multiple size=\"6\" class=\"field100 formfield\">";
	$html .=  "<option value=\"999999999\" "; if((in_array("999999999",$cats))||(empty($show[$field]))==true) { $html .= "selected"; } $html .= ">All Sections</option>";

	$resultt = @mysqli_query($dbcon,"SELECT * FROM ms_blog_categories WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error"); 	exit();	}
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($type["cat_id"] == $match) { $selected = "selected"; }
	$html .=  "<option value=\"".$type["cat_id"]."\" id=\"subcatm-".$type['cat_id']."\" class=\"multioption\"  ";  if(in_array($type['cat_id'],$cats)) { $html .= "selected"; } if($_REQUEST[''.items_cat_field.''] == $type['cat_id']) { $html .= " style=\"font-weight: bold; display: none;\""; } else { $html .= " style=\"font-weight: bold; \"";  } $html .= ">".$type["cat_name"]."</option>";
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];

			$html .= featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats);
	}
	$html .=  "</select>";
	return $html;
}

function featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ms_blog_categories WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


		$html .= "<option  value=\"".$sub_sec_id."\" id=\"subcatm-".$sub_sec_id."\" class=\"multioption\" ";  if(in_array($sub_sec_id,$cats)) { $html .= "selected"; } 
		if($_REQUEST[''.items_cat_field.''] == $sub_sec_id) { $html .= "style=\"display: none;\" "; } $html .= ">"; 
  
		$dashes = 0;
		$html .=  "$parent ->  $sub_sec_name</option>"; 

		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ms_blog_categories WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($dbcon) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= featuredCategoriesSubs($fn, $match, $parent_id, $level, $sec_under,$parent,$req,$cats);
		}
	}
		$level = 1;
		return $html;
}



?>


<?php require "w-footer.php"; ?>
