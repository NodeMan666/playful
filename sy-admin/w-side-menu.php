<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>


<?php 
if($_REQUEST['action'] == "save") { 

		if($_REQUEST['side_id'] >0) {
			updateSQL("ms_side_menu", "side_feature='".addslashes(stripslashes($_REQUEST['side_feature']))."'  ,side_cat='".addslashes(stripslashes($_REQUEST['side_cat']))."', side_limit='".addslashes(stripslashes($_REQUEST['side_limit']))."', side_minis='".addslashes(stripslashes($_REQUEST['side_minis']))."' , side_label='".addslashes(stripslashes($_REQUEST['side_label']))."' , side_text='".addslashes(stripslashes($_REQUEST['side_text']))."', side_html='".addslashes(stripslashes($_REQUEST['side_html']))."' , side_show_date='".$_REQUEST['side_show_date']."', side_show_time='".$_REQUEST['side_show_time']."', side_fb_header='".$_REQUEST['side_fb_header']."',  side_fb_faces='".$_REQUEST['side_fb_faces']."', side_fb_stream='".$_REQUEST['side_fb_stream']."',  side_fb_width='".$_REQUEST['side_fb_width']."' ,  side_fb_color='".$_REQUEST['side_fb_color']."',  side_fb_link='".$_REQUEST['side_fb_link']."'  , side_include='".addslashes(stripslashes($_REQUEST['side_include']))."' WHERE side_id='".$_REQUEST['side_id']."' ");
		} else {
			$side_order = doSQL("ms_side_menu", "*", "ORDER BY side_order DESC ");
			$this_side_order = $side_order['side_order'] + 1;
			insertSQL("ms_side_menu", "side_feature='".addslashes(stripslashes($_REQUEST['side_feature']))."' ,side_order='$this_side_order' ,side_cat='".addslashes(stripslashes($_REQUEST['side_cat']))."', side_limit='".addslashes(stripslashes($_REQUEST['side_limit']))."', side_minis='".addslashes(stripslashes($_REQUEST['side_minis']))."' , side_label='".addslashes(stripslashes($_REQUEST['side_label']))."' , side_text='".addslashes(stripslashes($_REQUEST['side_text']))."' , side_html='".addslashes(stripslashes($_REQUEST['side_html']))."', side_show_date='".$_REQUEST['side_show_date']."', side_show_time='".$_REQUEST['side_show_time']."', side_fb_header='".$_REQUEST['side_fb_header']."',  side_fb_faces='".$_REQUEST['side_fb_faces']."', side_fb_stream='".$_REQUEST['side_fb_stream']."',  side_fb_width='".$_REQUEST['side_fb_width']."'  ,  side_fb_color='".$_REQUEST['side_fb_color']."',  side_fb_link='".$_REQUEST['side_fb_link']."' , side_include='".addslashes(stripslashes($_REQUEST['side_include']))."'");
		}
		$_SESSION['sm'] = "Feature saved";
		?>
		<script>
		parent.window.location.href = 'index.php?do=look&view=sidemenu';
		</script>
		<?php 
		exit();
}
?>
<script>
function getOptionText() { 
	if($("#link_page").val() <= 0) { 
		$("#externallink").show();

	} else { 
		$("#externallink").hide();
		$("#link_text").val($('#link_page option:selected').text());
	}
}
function selectLeftRight() { 
	if($("#link_location").val() == "shop") { 
		$("#leftrightmenu").show();
	} else { 
		$("#leftrightmenu").hide();
	}

}

</script>
<?php 
if(!empty($_REQUEST['side_id'])) { 
	$side= doSQL("ms_side_menu", "*", "WHERE side_id='".$_REQUEST['side_id']."' ");
	$_REQUEST['feature'] = $side['side_feature'];
}

if($_REQUEST['feature'] == "recentitems") { 
	$feat_name = "Recent Items From Categegory";
}
if($_REQUEST['feature'] == "textarea") { 
	$feat_name = "Text Area";
}
if($_REQUEST['feature'] == "facebook") { 
	$feat_name = "Facebook Like Box";
}
if($_REQUEST['feature'] == "phpfile") { 
	$feat_name = "Include PHP File";
}
if($_REQUEST['feature'] == "menu") { 
	$feat_name = "Side Bar Menu";
}
if($_REQUEST['feature'] == "phpfile") { 
	$feat_name = "Include PHP File";
}
if($_REQUEST['feature'] == "pagetags") { 
	$feat_name = "Pages Tags";
}

?>

<div class="pc"><h1><?php if($_REQUEST['side_id'] >0) { ?>Edit Feature <?php print $feat_name;?><?php } else { ?>Add <?php print $feat_name;?><?php } ?></div>


			<form method="post" name="newLink" action="w-side-menu.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
			<div style="width: 49%;" class="left">
			<div class="underline">
				<div class="fieldLabel">Label</div>
				<div><input type="text" name="side_label" value="<?php print $side['side_label'];?>" size="20" class="field100"></div>
			</div>
			<div class="underline">
				<div class="fieldLabel">Text</div>
				<div><textarea name="side_text"  rows="4" cols="20" class="field100"><?php print $side['side_text'];?></textarea></div>
			</div>


			</div>

			<div style="width: 49%;" class="right">

			<?php if(($_REQUEST['feature'] == "recentitems")OR($_REQUEST['feature'] == "popular")==true) { ?>
			<div class="label">Select section to feature recent items from</div>
			<div class="underline">
				<select name="side_cat" id="side_cat">
				<option value="999999999" <?php if($_REQUEST['date_feature_cat'] == "999999999") { print "selected"; } ?>>All Sections</option>
				<?php
				$sections = whileSQL("ms_blog_categories", "*", "WHERE cat_under='0' ORDER BY cat_name ASC ");
				while($section = mysqli_fetch_array($sections)) { ?>
				<option value="<?php print $section['cat_id'];?>" <?php if($side['side_cat'] == $section['cat_id']) { print "selected"; } ?>><?php print $section['cat_name'];?></option>
				<?php } ?>
				</select>
			</div>
			<div class="underline">
			<?php if(empty($side['side_id'])) { $side['side_limit'] = "10"; } ?>
			Show <input type="text" name="side_limit" id="side_limit" value="<?php print $side['side_limit'];?>" size="2" class="center"> items
			</div>
			<div class="underline">
			<input type="checkbox" name="side_minis" value="1" <?php if($side['side_minis'] == "1") { print "checked"; } ?>> Show mini thumbnails next to title
			</div>
			<div class="underline">
			<input type="checkbox" name="side_show_date" value="1" <?php if($side['side_show_date'] == "1") { print "checked"; } ?>> Show date
			</div>
			<div class="underline">
			<input type="checkbox" name="side_show_time" value="1" <?php if($side['side_show_time'] == "1") { print "checked"; } ?>> Show time
			</div>
			<?php } ?>


			<?php if($_REQUEST['feature'] == "search") { ?>
			<?php if(empty($side['side_id'])) { ?>
			<div class="underline">To add the search box click save below.</div>
			<?php } ?>
			<?php } ?>
			<?php if($_REQUEST['feature'] == "pagetags") { ?>
			<?php if(empty($side['side_id'])) { ?>
			<div class="underline">To add the pages tags, click the save button below.</div>
			<?php } ?>
			<?php } ?>

			<?php if($_REQUEST['feature'] == "textarea") { ?>
			<div class="underline">Enter in the text or HTML below you want to use.</div>
			<div class="underline">
			<textarea name="side_html" cols="20" rows="12" class="field100"><?php print $side['side_html'];?></textarea>
			</div>

			<?php } ?>

			<?php if($_REQUEST['feature'] == "phpfile") { ?>
			<div class="underline">This will allow you to include a PHP file (advanced users). You will need to upload the PHP file you want to include to the server somewhere.<br><br>
			Enter in the file path to the php file you want to include below. Example: <?php print $setup['path']."/folder/myphpfile.php"; ?>.
			<div class="underline">
			<input type="text" name="side_include" size="20" class="field100" value="<?php print $side['side_include'];?>">
			</div>

			<?php } ?>


			<?php if($_REQUEST['feature'] == "facebook") { ?>

			<div class="underline">
			<div>Link to your Facebook page</div>
			<div><input type="text" name="side_fb_link" size="20" class="field100" value="<?php print $side['side_fb_link'];?>"></div>
			<div class="muted">Example: http://www.facebook.com/mypagename</div>
			</div>
			<div class="underline">
			<div>Color scheme</div>
			<div>
			<select name="side_fb_color">
			<option value="light" <?php if($side['side_fb_color'] == "light") { print "selected"; } ?>>Light</option>
			<option value="dark" <?php if($side['side_fb_color'] == "dark") { print "selected"; } ?>>Dark</option>
			</select>
			</div>
			</div>
			<?php if($side['side_fb_width'] <=0) { $side['side_fb_width'] = 250; } ?>
			<div class="underline">
			Width: <input type="text" name="side_fb_width" size="4" value="<?php print $side['side_fb_width'];?>">px
			</div>
			<div class="underline">
			<input type="checkbox" name="side_fb_header" value="1" <?php if($side['side_fb_header'] == "1") { print "checked"; } ?>> Show Header
			</div>
			<div class="underline">
			<input type="checkbox" name="side_fb_faces" value="1" <?php if($side['side_fb_faces'] == "1") { print "checked"; } ?>> Show Faces
			</div>
			<div class="underline">
			<input type="checkbox" name="side_fb_stream" value="1" <?php if($side['side_fb_stream'] == "1") { print "checked"; } ?>> Show Stream
			</div>

			<?php } ?>


			<?php if($_REQUEST['feature'] == "menu") { ?>
			<?php if(empty($side['side_id'])) { ?>
			<div class="underline">To add the side bar menu click save below.</div>
			<?php } ?>
			<?php } ?>

			<div class="underline" >
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="side_feature" value="<?php print $_REQUEST['feature'];?>">
			<input type="hidden" name="side_id" value="<?php print $side['side_id'];?>">
			<input type="submit" name="submit" value="Save" class="submit" id="submitButton">

			<div class="cssClear"></div>
		</div>
			</div>
			<div class="clear"></div>



	</form>
	<div >&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>
