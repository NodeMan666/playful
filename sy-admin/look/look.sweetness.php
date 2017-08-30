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
		
		
	fields['do'] = "look";
	fields['view'] = "sweetness";

		<?php if($setup['demo_mode'] !== true) { ?>

	$.post("index.php", fields,	function (data) { 

		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save Changes");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	});

	<?php } else {  ?>
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
$show = doSQL("ms_show", "*", "WHERE default_feat='1' "); 
if($_REQUEST['deletelogo'] == "1") { 
	unlink($setup['path']."/sy-misc/sweetness/".$show['logo_file']);
	updateSQL("ms_show", "logo_file='' WHERE default_feat='1' ");
	header("location: index.php?do=look&view=sweetness");
	session_write_close();
	exit();
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
	if($("#enable_side").attr("checked")) { 
		$(".sideitems").show();
	} else { 
		$(".sideitems").hide();
	}
	if($("#feature_type").val() == "getfeatureslide") {
		$(".homecats").hide();
	} else { 
		$(".homecats").show();
	}
}
</script>

<div class="pc"><h1>CLF-Display <?php print $for;?></h1></div>
 <?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="http://www.picturespro.com/sytist-manual/site-design/clf-display/" target="_blank">Click here to learn more about the CLF-Display.</a></div><?php } ?>

<div class="clear"></div>
<div class="pc"></div>

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
		loading_bg_color='".$_REQUEST['loading_bg_color']."',
		loading_font_color='".$_REQUEST['loading_font_color']."',
		loading_text='".$_REQUEST['loading_text']."',
		title_size='".$_REQUEST['title_size']."',
		title_color='".$_REQUEST['title_color']."',
		title_textshadow='".$_REQUEST['title_textshadow']."',
		font_size='".$_REQUEST['font_size']."',
		font_color='".$_REQUEST['font_color']."',
		font_textshadow='".$_REQUEST['font_textshadow']."',
		text_title_width='".$_REQUEST['text_title_width']."',
		text_placement='".$_REQUEST['text_placement']."',
		logo_placement='".$_REQUEST['logo_placement']."',
		title_placement='".$_REQUEST['title_placement']."',
		show_preview_text='".$_REQUEST['show_preview_text']."',
		show_page_text='".$_REQUEST['show_page_text']."',
		show_mini_menu='".$_REQUEST['show_mini_menu']."',
		nav_placement='".$_REQUEST['nav_placement']."',
		sm_bg='".$_REQUEST['sm_bg']."',
		sm_bt='".$_REQUEST['sm_bt']."',
		sm_bb='".$_REQUEST['sm_bb']."',
		sm_font_color='".$_REQUEST['sm_font_color']."',
		sm_font_size='".$_REQUEST['sm_font_size']."',
		smh_bg='".$_REQUEST['smh_bg']."',
		smh_bt='".$_REQUEST['smh_bt']."',
		smh_bb='".$_REQUEST['smh_bb']."',
		smh_font_color='".$_REQUEST['smh_font_color']."',
		side_menu_photo_ratio='".$_REQUEST['side_menu_photo_ratio']."',

		main_menu='".$_REQUEST['main_menu']."',
		main_menu_placement='".$_REQUEST['main_menu_placement']."',
		main_menu_bg='".$_REQUEST['main_menu_bg']."',
		main_menu_font='".$_REQUEST['main_menu_font']."',
		main_link_spacing='".$_REQUEST['main_link_spacing']."',
		main_menu_font_size='".$_REQUEST['main_menu_font_size']."',

		nav_color='".$_REQUEST['nav_color']."',
		nav_bg='".$_REQUEST['nav_bg']."',
		nav_font_size='".$_REQUEST['nav_font_size']."',

		sm_padding='".$_REQUEST['sm_padding']."',
		menu_icon='".$_REQUEST['menu_icon']."',
		sm_title_color='".$_REQUEST['sm_title_color']."',
		sm_text_color='".$_REQUEST['sm_text_color']."',
		sm_find_photos='".$_REQUEST['sm_find_photos']."',
		side_menu_photo_file='".$_REQUEST['side_menu_photo_file']."',
		side_menu_photo_width='".$_REQUEST['side_menu_photo_width']."',
		side_title_placement='".$_REQUEST['side_title_placement']."',
		side_show_date='".$_REQUEST['side_show_date']."',
		side_show_cat_name='".$_REQUEST['side_show_cat_name']."',
		side_show_snippet='".$_REQUEST['side_show_snippet']."',
		side_snippet_length='".$_REQUEST['side_snippet_length']."',
		page_text_preview_length='".$_REQUEST['page_text_preview_length']."',
		side_page_title_color='".$_REQUEST['side_page_title_color']."',
		side_page_title_hover='".$_REQUEST['side_page_title_hover']."',
		nav_display_count='".$_REQUEST['nav_display_count']."',

		show_photos_text_shadow='".$_REQUEST['show_photos_text_shadow']."',
		show_photos_text_size='".$_REQUEST['show_photos_text_size']."',
		show_photos_text='".$_REQUEST['show_photos_text']."',
		show_photos_border='".$_REQUEST['show_photos_border']."',
		show_photos_bg='".$_REQUEST['show_photos_bg']."'

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
		loading_bg_color='".$_REQUEST['loading_bg_color']."',
		loading_font_color='".$_REQUEST['loading_font_color']."',
		loading_text='".$_REQUEST['loading_text']."',
		sm_title_color='".$_REQUEST['sm_title_color']."',
		sm_text_color='".$_REQUEST['sm_text_color']."',
		side_menu_photo_ratio='".$_REQUEST['side_menu_photo_ratio']."',
		show_photos_text_shadow='".$_REQUEST['show_photos_text_shadow']."',
		show_photos_text_size='".$_REQUEST['show_photos_text_size']."',
		show_photos_text='".$_REQUEST['show_photos_text']."',
		show_photos_border='".$_REQUEST['show_photos_border']."',
		show_photos_bg='".$_REQUEST['show_photos_bg']."'

		sm_find_photos='".$_REQUEST['sm_find_photos']."'


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
	$show['enable_logo'] = $dshow['enable_logo'];
	$show['mainphotoratio'] = $dshow['mainphotoratio'];


}
?>
<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
<input type="hidden" name="feat_page_id" id="feat_page_id" value="<?php print $date['date_id'];?>" class="formfield">
<input type="hidden" name="feat_cat_id" id="feat_cat_id" value="<?php print $cat['cat_id'];?>" class="formfield">
<input type="hidden" name="show_id" id="show_id" value="<?php print $show['show_id'];?>" class="formfield">
<input type="hidden" name="action" id="action" value="save" class="formfield">
<div class="underlinelabel">Default Settings</div>


<div style="width: 50%; float: left;">
	<div style="padding: 16px;">
	<!-- 
	<div class="underline">
		<div class="label"><input type="checkbox" name="main_full_screen" id="main_full_screen" value="1" <?php if($show['main_full_screen'] == "1") { print "checked"; } ?> class="formfield"> <label for="main_full_screen"> Full Screen</label></div>
	</div>
	-->

	<div class="underline">
		<div class="label">
		<select name="fullscreen_width" id="fullscreen_width" class="formfield">
		<?php $iop = 50;
		while($iop <= 80) {
		?>
			<option value="<?php print $iop;?>" <?php if($show['fullscreen_width'] == $iop) { print "selected"; } ?>><?php print $iop;?>%</option>
		<?php 
			$iop++;
		} ?>

		</select> Display area width if side menu enabled
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

		</select> Main photo ratio when not displayed full screen
		</div>
	</div>

	-->
	<div class="underline">
		<div class="label"><input type="checkbox" name="enable_nav" id="enable_nav" value="1" <?php if($show['enable_nav'] == "1") { print "checked"; } ?> class="formfield"> <label for="enable_nav"> Enable slide navigation (next / previous)</label></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="nav_display_count" id="nav_display_count" value="1" <?php if($show['nav_display_count'] == "1") { print "checked"; } ?> class="formfield"> <label for="nav_display_count"> Display count in navigation (ie: 5 of 12)</label></div>
	</div>


	<div class="underline">
		<div class="label">
		<select name="initialopacity" id="initialopacity" class="formfield">
		<?php $iop = "0";
		while($iop < .9) {
			$iop = $iop + .1;?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['initialopacity'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php } ?>

		</select>Photo initial opacity
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

		</select> Photo hover opacity
		</div>
	</div>
	

	</div>


</div>
<div style="width: 50%; float: left;">
	<div style="padding: 16px;">




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
<div class="clear"></div>
<div class="underlinelabel">Layout & Design Settings</div>

<div style="width: 50%; float: left;">
	<div style="padding: 16px;">

	<!-- 
	<div class="underlinelabel">Logo</div>
	<input type="file" name="file_upload" id="file_upload" />
	<?php 
	$hash = $site_setup['salt']; 
	$timestamp = date('Ymdhis');
	?>
	<script>
	$(function() {
		$('#file_upload').uploadify({
			 'multi'    : false,
			<?php if($_REQUEST['debug'] == "1") { ?>
			'debug'    : true,	
			<?php } ?>
			'method'   : 'post',
			'fileSizeLimit':'10MB',
			'fileTypeExts' : '*',
			'fileTypeDesc' : 'all files',
			'buttonText' : 'Upload Logo File',
			 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
				 'token' : '<?php echo md5($hash.$timestamp); ?>', 
				'folder':'sweetness',
				 'sweetnesslogo':'1' },
			'onQueueComplete' : function(queueData) {
				window.parent.location.href='index.php?do=look&view=sweetness';
				}, 
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
					alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
				}, 

			'swf'      : 'uploadify/uploadify.swf',
			'uploader' :'misc_upload.php'
			// Put your options here
		});
	});
	</script>
	<?php if(!empty($show['logo_file'])) { ?>
		<img src="<?php print $setup['temp_url_folder']."/sy-misc/sweetness/".$show['logo_file'];?>">
		<br>
		<a href="index.php?do=look&view=sweetness&deletelogo=1">delete</a>
	<?php } ?>

	-->

	<div class="underlinesection">This section controls the look and layout of text over the rotating pages or photos.</div>
	<div class="underlinelabel">Placements</div>

	<div class="underline">
		<div class="label">Section  / cateory name placement (if enabled)</div>
		<div>
		<select name="logo_placement" id="logo_placement" class="formfield">

			<option value="tl" <?php if($show['logo_placement'] == "tl") { print "selected"; } ?>>Top left</option>
			<option value="bl" <?php if($show['logo_placement'] == "bl") { print "selected"; } ?>>Bottom left</option>
			<option value="tr" <?php if($show['logo_placement'] == "tr") { print "selected"; } ?>>Top right</option>
			<option value="br" <?php if($show['logo_placement'] == "br") { print "selected"; } ?>>Bottom right</option>


		</select>
		</div>
	</div>
	<div class="underline">
		<div class="label">Page title & text </div>
		<div>
		<select name="title_placement" id="title_placement" class="formfield">

			<option value="tl" <?php if($show['title_placement'] == "tl") { print "selected"; } ?>>Top left</option>
			<option value="bl" <?php if($show['title_placement'] == "bl") { print "selected"; } ?>>Bottom left</option>
			<option value="tr" <?php if($show['title_placement'] == "tr") { print "selected"; } ?>>Top right</option>
			<option value="br" <?php if($show['title_placement'] == "br") { print "selected"; } ?>>Bottom right</option>


		</select>
		</div>
	</div>

	
	<div class="underline">
		<div class="label">Navigation</div>
		<div>
		<select name="nav_placement" id="nav_placement" class="formfield">

			<option value="tl" <?php if($show['nav_placement'] == "tl") { print "selected"; } ?>>Top left</option>
			<option value="bl" <?php if($show['nav_placement'] == "bl") { print "selected"; } ?>>Bottom left</option>
			<option value="tr" <?php if($show['nav_placement'] == "tr") { print "selected"; } ?>>Top right</option>
			<option value="br" <?php if($show['nav_placement'] == "br") { print "selected"; } ?>>Bottom right</option>


		</select>
		</div>
	</div>
	<div>&nbsp;</div>


	<div class="underlinelabel">Page Title & Text Colors</div>

	<div class="underline left">
		<div class="label">Page title color</div>
		<div>
		<input type="text"  size="8" name="title_color" id="title_color" value="<?php print $show['title_color'];?>" class="color formfield"  thisval="<?php print $show['title_color'];?>">
		</div> 
	</div>



	<div class="underline left">
		<div class="label">Page title text shadow</div>
		<div>
		<input type="text"  size="8" name="title_textshadow" id="title_textshadow" value="<?php print $show['title_textshadow'];?>" class="color formfield"  thisval="<?php print $show['title_textshadow'];?>">
		</div>
	</div>

		<div class="underline left">
		<div class="label">Page title font size</div>
		<div>
		<select name="title_size" id="title_size" class="formfield">
		<?php $iop = 12;
		while($iop < 90) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['title_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select>
		</div>
	</div>


	<div class="clear"></div>


	<div class="underline left">
		<div class="label">Text color</div>
		<div>
		<input type="text"  size="8" name="font_color" id="title_color" value="<?php print $show['font_color'];?>" class="color formfield"  thisval="<?php print $show['font_color'];?>">
		</div>
	</div>

	<div class="underline left">
		<div class="label">Text shadow</div>
		<div>
		<input type="text"  size="8" name="font_textshadow" id="font_textshadow" value="<?php print $show['font_textshadow'];?>" class="color formfield"  thisval="<?php print $show['font_textshadow'];?>">
		</div>
	</div>

	<div class="underline">
		<div class="label">Text size</div>
		<div>
		<select name="font_size" id="font_size" class="formfield">
		<?php $iop = 12;
		while($iop < 90) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['font_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select>
		</div>
	</div>

	<div class="clear"></div>
	<!-- 
	<div class="underline">
		<div class="label"><input type="checkbox" name="show_mini_menu" id="show_mini_menu" value="1" <?php if($show['show_mini_menu'] == "1") { print "checked"; } ?> class="formfield"> <label for="show_mini_menu"> Show mini menu at top of page</label></div>
	</div>
	-->
	<div class="underline">
		<div class="label"><input type="checkbox" name="show_preview_text" id="show_preview_text" value="1" <?php if($show['show_preview_text'] == "1") { print "checked"; } ?> class="formfield"> <label for="show_preview_text"> Show page preview text</label></div>
	</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="3" name="page_text_preview_length" id="page_text_preview_length" value="<?php print $show['page_text_preview_length'];?>" class="formfield"  thisval="<?php print $show['page_text_preview_length'];?>">
		</div> Preview text length (characters). Enter 0 to not shorten preview text
	</div>
<div>&nbsp;</div>


	<div class="underlinelabel">Color Behind Text / Titles</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="behind_text_color" id="behind_text_color" value="<?php print $show['behind_text_color'];?>" class="color formfield"  thisval="<?php print $show['behind_text_color'];?>">
		</div>
	</div>
	<div class="underline">
		<div class="label">
		<select name="text_title_width" id="text_title_width" class="formfield">
		<?php $iop = 20;
		while($iop <= 100) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['text_title_width'] == $iop) { print "selected"; } ?>><?php print $iop;?>%</option>
		<?php
			$iop++;
			} ?>

		</select> Width
		</div>
	</div>


	<div class="underline">
		<div class="label">
		<select name="behind_text_opacity" id="behind_text_opacity" class="formfield">
		<?php $iop = "0";
		while($iop <= 1) {
			?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['behind_text_opacity'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php 
			$iop = $iop + .1;
			} ?>

		</select> Behind text opaccity
		</div>
	</div>


<div>&nbsp;</div>


	<!-- 
	<div class="underlinelabel">Page Text Placement</div>
	<div class="underlinespacer">If display a page that has sub galleries, this controls where the text of the page (if any) is displayed.</div>
	<div class="underline">
		<div class="label">
		<select name="text_placement" id="text_placement" class="formfield">

			<option value="main" <?php if($show['text_placement'] == "main") { print "selected"; } ?>>Over photo under title</option>
			<option value="side" <?php if($show['text_placement'] == "side") { print "selected"; } ?>>In the side menu over sub galleries</option>

		</select> Font Size
		</div>
	</div>

-->


	<div class="underlinelabel">Loading Screen</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="loading_bg_color" id="loading_bg_color" value="<?php print $show['loading_bg_color'];?>" class="color formfield"  thisval="<?php print $show['loading_bg_color'];?>"> Loading Background Color
		</div>
	</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="loading_font_color" id="loading_font_color" value="<?php print $show['loading_font_color'];?>" class="color formfield"  thisval="<?php print $show['loading_font_color'];?>"> Loading Font Color
		</div>
	</div>
	<div class="underline">
		<div class="label">Loading Text</div>
		<div><textarea name="loading_text" id="loading_text" class="field100 formfield"><?php print $show['loading_text'];?></textarea></div>
	</div>
	<div class="underlinelabel">Background Color Behind Photos</div>
	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="main_photo_bg_color" id="main_photo_bg_color" value="<?php print $show['main_photo_bg_color'];?>" class="color formfield"  thisval="<?php print $show['main_photo_bg_color'];?>">
		</div>
	</div>


	<div class="underlinelabel">Scroll to View Photos Bar</div>
	<div class="underline">
		<div class="label left" style="margin-right: 12px;">Background<br>
		<input type="text"  size="8" name="show_photos_bg" id="show_photos_bg" value="<?php print $show['show_photos_bg'];?>" class="color formfield"  thisval="<?php print $show['show_photos_bg'];?>">
		</div>
		<div class="label left">Border<br>
		<input type="text"  size="8" name="show_photos_border" id="show_photos_border" value="<?php print $show['show_photos_border'];?>" class="color formfield"  thisval="<?php print $show['show_photos_border'];?>">
		</div>

	
	<div class="clear"></div>
	</div>

	<div class="underline">
		<div class="label left" style="margin-right: 12px;">Font Color<br>
		<input type="text"  size="8" name="show_photos_text" id="show_photos_text" value="<?php print $show['show_photos_text'];?>" class="color formfield"  thisval="<?php print $show['show_photos_text'];?>">
		</div>
		<div class="label left"  style="margin-right: 12px;">Text Shadow<br>
		<input type="text"  size="8" name="show_photos_text_shadow" id="show_photos_text_shadow" value="<?php print $show['show_photos_text_shadow'];?>" class="color formfield"  thisval="<?php print $show['show_photos_text_shadow'];?>">
		</div>
		<div class="label left">Font Size<br>
		<select name="show_photos_text_size" id="show_photos_text_size" class="formfield">
		<?php $iop = 12;
		while($iop < 90) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['show_photos_text_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select>
		</div>

	
	<div class="clear"></div>
	</div>






	</div>
</div>
<div style="width: 50%; float: left;">
	<div style="padding: 16px;">
	<div class="underlinelabel">Side Menu Links</div>
	<div class="underlinesection">This section controls the look and layout of the side menu items.</div>

	<div class="underline left">
		<div class="label"> Photo display width</div>
		<div>
		<select name="side_menu_photo_width" id="side_menu_photo_width" class="formfield">
		<?php $iop = 20;
		while($iop <= 60) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['side_menu_photo_width'] == $iop) { print "selected"; } ?>><?php print $iop;?>%</option>
		<?php
			$iop++;
			} ?>
			<option value="100" <?php if($show['side_menu_photo_width'] == "100") { print "selected"; } ?>>Fill Container</option>

		</select>
		</div>
	</div>



	<div class="underline left">
		<div class="label"> Photo ratio</div>
		<div>
		<select name="side_menu_photo_ratio" id="side_menu_photo_ratio" class="formfield">
		<?php $iop = ".2";
		while($iop < 1.5) {
			?>
			<option value="<?php print number_format($iop,2);?>" <?php if($show['side_menu_photo_ratio'] == number_format($iop,2)) { print "selected"; } ?>><?php print number_format($iop,2);?></option>
		<?php 
			$iop = $iop + .1;
			} ?>

		</select>
		</div>
	</div>
	<div class="underline left">
		<div class="label">Photo file</div>
		<div>
		<select name="side_menu_photo_file" id="side_menu_photo_file" class="formfield">
			<option value="pic_th" <?php if($show['side_menu_photo_file'] == "pic_th") { print "selected"; } ?>>Thumbnail</option>
			<option value="pic_pic" <?php if($show['side_menu_photo_file'] == "pic_pic") { print "selected"; } ?>>Small photo</option>


		</select>
		</div>
	</div>

	<div class="underline left">
		<div class="label">Padding</div>
		<div>
		<select name="sm_padding" id="sm_padding" class="formfield">
		<?php $iop = 4;
		while($iop < 32) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['sm_padding'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select>
		</div>
	</div>

<div class="clear"></div>

<script>
var side_menu_photo_ratio = '<?php print $show['side_menu_photo_ratio'];?>';

function sizecatphoto(classname) { 
	$("."+classname).each(function(){
		if(classname == "catphoto") { 
			var ratio = catphotoratio;
			var sizecontainer = "catphotocontainerinner";
		}
		if(classname == "mainfeature") { 
			var ratio = mainphotoratio;
			var sizecontainer = "mainfeature";
			if(main_full_screen == 1) { 
			if(showminimenu == "1") { 
				wh = $(window).height() - Math.abs($("#headerAndMenu").height()) - Math.abs($("#shopmenucontainer").height());
			} else { 
				wh = $(window).height();
			}

				var ratio = wh / $("#mainfeature").width();
			}
		}
		if(classname == "featsidemenuitem") { 
			ratio = side_menu_photo_ratio;
		}

		var refW = $(this).width();
		refH = refW * ratio;
		if(classname == "mainphoto") { 
			$("#mainfeature").css({"height":refH+"px","min-height":refH+"px"});
		}

		$(this).css({"height":refH+"px","min-height":refH+"px"});
		var refRatio = refW/refH;
		var imgH = Math.abs($(this).find(".homephotos").attr("hh"));
		var imgW = Math.abs($(this).find(".homephotos").attr("ww"));


		if(imgH > refH) { 
			mt = (imgH - refH) / 2;
		} else { 
			mt = 0;
		}
		pid = $(this).find("img").attr("id");

		if ( (imgW/imgH) < refRatio ) { 
			newH = imgH * (refW / imgW);
			mt = (newH - refH) / 2;
			$(this).find(".homephotos").css({"width":refW+"px","height":"auto", "margin-top":"-"+mt+"px"});		
		} else {
			newW = imgW * (refH / imgH);
			ml = (newW - refW) / 2;
			$(this).find(".homephotos").css({"height":refH+"px","width":"auto", "margin-left":"-"+ml+"px"});
		}
	})

}

function updatepreview() { 
	side_menu_photo_ratio = $("#side_menu_photo_ratio").val();
	$("#previewli a").css({"background-color":"#"+$("#sm_bg").val(),"border-bottom":"solid 1px #"+$("#sm_bb").val(),"border-top":"solid 1px #"+$("#sm_bt").val(),"color":"#"+$("#sm_font_color").val(),"padding":$("#sm_padding").val()+"px"});

	$("#previewli a h3").css({"font-size":$("#sm_font_size").val()+"px","color":"#"+$("#side_page_title_color").val()});


	$("#previewlihover a").css({"background-color":"#"+$("#smh_bg").val(),"border-bottom":"solid 1px #"+$("#smh_bb").val(),"border-top":"solid 1px #"+$("#smh_bt").val(),"color":"#"+$("#smh_font_color").val(),"padding":$("#sm_padding").val()+"px"});

	$("#previewlihover a h3").css({"font-size":$("#sm_font_size").val()+"px","color":"#"+$("#side_page_title_hover").val()});



	if($("#side_menu_photo_width").val() <= 60) { 
		$(".featsidemenuitem").css({"width":$("#side_menu_photo_width").val()+"%", "float":"left"});
	} else { 
		$(".featsidemenuitem").css({"width":"auto", "float":"none"});
	}
	sizecatphoto('featsidemenuitem');

}
$(document).ready(function(){
	sizecatphoto('featsidemenuitem');
	setInterval("updatepreview()",100);
});


</script>

<ul id="previewlist" style="list-style: none; margin: 0; padding: 0; overflow: hidden;">
	<li id="previewli" style=" list-style: none; padding-right: <?php print $show['sm_padding'];?>px;">
	<a href="" onclick="return false;" style="
	background-color: #<?php print $show['sm_bg'];?>; 
	border-bottom: 1px solid  #<?php print $show['sm_bb'];?>;
	border-top: 1px solid  #<?php print $show['sm_bt'];?>;
	color: #<?php print $show['sm_font_color'];?>;
	height: 1%;
	padding: <?php print $show['sm_padding'];?>px;
	float: left; 
	width: 100%;">
	<div style=" overflow: hidden; margin-right: 12px; <?php if($show['side_menu_photo_width'] <= "90") { print "float: left; width: ".$show['side_menu_photo_width']."%;"; } ?>" class="featsidemenuitem">
	<img src="graphics/photo2.jpg" ww="200" hh="150" border="0" class="homephotos" id="sample-preview">
	</div>
	<h3 style="font-size: <?php print $show['sm_font_size'];?>px; display: inline; color: #<?php print $show['side_page_title_color'];?>; text-shadow: none;">This is a sample title of a page</h3>
	<br><?php print date('M d, Y');?>
	<br>This is a preview of the side menu links. You can makes adjustments to this display above and below this area.


	</a>
	</li>
</ul>



	<div class="underline left">
		<div class="label">Page title color</div>
		<div><input type="text"  size="8" name="side_page_title_color" id="side_page_title_color" value="<?php print $show['side_page_title_color'];?>" class="color formfield"  thisval="<?php print $show['side_page_title_color'];?>">
		</div>
	</div>


	
	
	<div class="underline left">
		<div class="label">Font color</div>
		<div>
		<input type="text"  size="8" name="sm_font_color" id="sm_font_color" value="<?php print $show['sm_font_color'];?>" class="color formfield"  thisval="<?php print $show['sm_font_color'];?>">
		</div>
	</div>

	<div class="underline left">
		<div class="label">Background color</div>
		<div>
		<input type="text"  size="8" name="sm_bg" id="sm_bg" value="<?php print $show['sm_bg'];?>" class="color formfield"  thisval="<?php print $show['sm_bg'];?>">
		</div>
	</div>

	<div class="underline left">
		<div class="label">Top border color</div>
		<div>
		<input type="text"  size="8" name="sm_bt" id="sm_bt" value="<?php print $show['sm_bt'];?>" class="color formfield"  thisval="<?php print $show['sm_bt'];?>">
		</div>
	</div>
	<div class="underline left">
		<div class="label">Bottom border color</div>
		<div>
		<input type="text"  size="8" name="sm_bb" id="sm_bb" value="<?php print $show['sm_bb'];?>" class="color formfield"  thisval="<?php print $show['sm_bb'];?>">
		</div>
	</div>

		<div class="underline left">
		<div class="label">Page title font size</div>
		<div>
		<select name="sm_font_size" id="sm_font_size" class="formfield">
		<?php $iop = 12;
		while($iop < 90) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['sm_font_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select>
		</div>
	</div>

	<div class="underline left">
		<div class="label">Page title placement</div>
		<div>
		<select name="side_title_placement" id="side_title_placement" class="formfield">
			<option value="above" <?php if($show['side_title_placement'] == "above") { print "selected"; } ?>>Above Photo</option>
			<option value="below" <?php if($show['side_title_placement'] == "below") { print "selected"; } ?>>To the side or below photo</option>


		</select>
		</div>
	</div>


	<div class="clear"></div>

	<div>&nbsp;</div>

	<div class="underlinelabel">Side Menu Links Hovered</div>


	<ul id="previewlisthover" style="list-style: none; margin: 0; padding: 0; overflow: hidden;">
	<li id="previewlihover" style=" list-style: none; padding-right: <?php print $show['sm_padding'];?>px;">
	<a href="" onclick="return false;" style="
	background-color: #<?php print $show['sm_bg'];?>; 
	border-bottom: 1px solid  #<?php print $show['sm_bb'];?>;
	border-top: 1px solid  #<?php print $show['sm_bt'];?>;
	color: #<?php print $show['sm_font_color'];?>;
	height: 1%;
	padding: <?php print $show['sm_padding'];?>px;
	float: left; 
	width: 100%;">
	<div style=" overflow: hidden; margin-right: 12px; <?php if($show['side_menu_photo_width'] <= "90") { print "float: left; width: ".$show['side_menu_photo_width']."%;"; } ?>" class="featsidemenuitem">
	<img src="graphics/photo2.jpg" ww="200" hh="150" border="0" class="homephotos" id="sample-preview">
	</div>
	<h3 style="font-size: <?php print $show['sm_font_size'];?>px; display: inline; color: #<?php print $show['side_page_title_color'];?>; text-shadow: none;">This is a sample title of a page (hovered)</h3>
	<br><?php print date('M d, Y');?>
	<br>This is a preview of the side menu links when moused over. You can makes adjustments to this display below.


	</a>
	</li>
</ul>
	<div class="underline left">
		<div class="label">Page title color</div>
		<div>
		<input type="text"  size="8" name="side_page_title_hover" id="side_page_title_hover" value="<?php print $show['side_page_title_hover'];?>" class="color formfield"  thisval="<?php print $show['side_page_title_hover'];?>">
		</div>
	</div>


	<div class="underline left">
		<div class="label">Font color</div>
		<div>
		<input type="text"  size="8" name="smh_font_color" id="smh_font_color" value="<?php print $show['smh_font_color'];?>" class="color formfield"  thisval="<?php print $show['smh_font_color'];?>">
		</div>
	</div>
	<div class="underline left">
		<div class="label">Background</div>
		<div>
		<input type="text"  size="8" name="smh_bg" id="smh_bg" value="<?php print $show['smh_bg'];?>" class="color formfield"  thisval="<?php print $show['smh_bg'];?>">
		</div> 
	</div>

	<div class="underline left">
		<div class="label">Top border color</div>
		<div>
		<input type="text"  size="8" name="smh_bt" id="smh_bt" value="<?php print $show['smh_bt'];?>" class="color formfield"  thisval="<?php print $show['smh_bt'];?>">
		</div>
	</div>
	<div class="underline left">
		<div class="label">Bottom border color</div>
		<div>
		<input type="text"  size="8" name="smh_bb" id="smh_bb" value="<?php print $show['smh_bb'];?>" class="color formfield"  thisval="<?php print $show['smh_bb'];?>">
		</div>
	</div>
	<div class="clear"></div>

	<div>&nbsp;</div>
	<div class="underlinelabel">Side Menu Title & Text</div>
	<div class="underlinesection">You have the option to display a title & text above the links. Below adjusts those colors.</div>
	<div class="underline left">

		<div class="label">Title above links color (if enabled)</div>
		<div>
		<input type="text"  size="8" name="sm_title_color" id="sm_title_color" value="<?php print $show['sm_title_color'];?>" class="color formfield"  thisval="<?php print $show['sm_title_color'];?>">
		</div> 
	</div>

	<div class="underline left">
		<div class="label"> Text above links color (if enabled)</div>
		<div>
		<input type="text"  size="8" name="sm_text_color" id="sm_text_color" value="<?php print $show['sm_text_color'];?>" class="color formfield"  thisval="<?php print $show['sm_text_color'];?>">
		</div>
	</div>

	<div class="clear"></div>

	<div>&nbsp;</div>
	<div class="underlinelabel">Other Side Menu Display Options</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="side_show_date" id="side_show_date" value="1" <?php if($show['side_show_date'] == "1") { print "checked"; } ?> class="formfield"> <label for="side_show_date"> Display page date</label></div>
	</div>


	<div class="underline">
		<div class="label"><input type="checkbox" name="side_show_cat_name" id="side_show_cat_name" value="1" <?php if($show['side_show_cat_name'] == "1") { print "checked"; } ?> class="formfield"> <label for="side_show_cat_name"> Display category name before page title</label></div>
	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="side_show_snippet" id="side_show_snippet" value="1" <?php if($show['side_show_snippet'] == "1") { print "checked"; } ?> class="formfield"> <label for="side_show_snippet"> Display preview text or snippet from page</label></div>
	</div>


	<div class="underline">
		<div class="label">
		<input type="text"  size="3" name="side_snippet_length" id="side_snippet_length" value="<?php print $show['side_snippet_length'];?>" class="formfield"  thisval="<?php print $show['side_snippet_length'];?>">
		</div> Preview text length (characters). Enter 0 to not shorten preview text
	</div>

<!-- 
	<div class="underlinelabel">Main Menu (if enabled)</div>

	<div class="underline">
		<div class="label">
		<select name="main_menu_placement" id="main_menu_placement" class="formfield">

			<option value="left" <?php if($show['main_menu_placement'] == "left") { print "selected"; } ?>>Left</option>
			<option value="right" <?php if($show['main_menu_placement'] == "right") { print "selected"; } ?>>Right</option>

		</select> Placement
		</div>
	</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="main_menu_bg" id="main_menu_bg" value="<?php print $show['main_menu_bg'];?>" class="color formfield"  thisval="<?php print $show['main_menu_bg'];?>">
		</div> Background Color
	</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="main_menu_font" id="main_menu_font" value="<?php print $show['main_menu_font'];?>" class="color formfield"  thisval="<?php print $show['main_menu_font'];?>">
		</div> Font Color
	</div>

	<div class="underline">
		<div class="label">
		<select name="main_menu_font_size" id="main_menu_font_size" class="formfield">
		<?php $iop = 12;
		while($iop < 100) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['main_menu_font_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select> Font Size
		</div>
	</div>

	<div class="underline">
		<div class="label">
		<select name="main_link_spacing" id="main_link_spacing" class="formfield">
		<?php $iop = 4;
		while($iop < 30) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['main_link_spacing'] == $iop) { print "selected"; } ?>><?php print $iop;?>%</option>
		<?php
			$iop++;
			} ?>

		</select> Spacing
		</div>
	</div>

-->

	<div>&nbsp;</div>

	<div class="underlinelabel">Navgation (if enabled)</div>



	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="nav_bg" id="nav_bg" value="<?php print $show['nav_bg'];?>" class="color formfield"  thisval="<?php print $show['nav_bg'];?>">
		</div> Background Color
	</div>

	<div class="underline">
		<div class="label">
		<input type="text"  size="8" name="nav_color" id="nav_color" value="<?php print $show['nav_color'];?>" class="color formfield"  thisval="<?php print $show['nav_color'];?>">
		</div> Font Color
	</div>

	<div class="underline">
		<div class="label">
		<select name="nav_font_size" id="nav_font_size" class="formfield">
		<?php $iop = 12;
		while($iop < 30) {
			?>
			<option value="<?php print $iop;?>" <?php if($show['nav_font_size'] == $iop) { print "selected"; } ?>><?php print $iop;?>px</option>
		<?php
			$iop++;
			} ?>

		</select> Font Size
		</div>
	</div>



	</div>
</div>
<div class="clear"></div>
<div class="pc right textright buttons" style="position: fixed; z-index: 1000; right: 0px; bottom: 60px;">
<a href="" id="saveform" onclick="savedata('formfield'); return false;">Save Changes</a>
</div>
<div class="clear"></div>

</form>
