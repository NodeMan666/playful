<script>
function openframewindow(style_id,img_id) { 
	pagewindowedit("room-view/room-frame-edit.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id+"&img_id="+img_id+"&frameadjust="+style_id);
}
function framesizes(style_id,frame_id) { 
	pagewindowedit("room-view/room-frame-sizes.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id+"&frame_id="+frame_id+"&frameadjust="+style_id);
}
function editstyleinfo(style_id) { 
	pagewindowedit("room-view/room-frame-info.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id);
}

</script>

<?php
if($_REQUEST['action'] == "addnewframestyle") { 
	$id = insertSQL("ms_frame_styles", "style_name='".addslashes(stripslashes(trim($_REQUEST['style_name'])))."', style_frame_width='".$_REQUEST['style_frame_width']."', style_frame_corners='12-12-12-12' ");
	insertSQL("ms_frame_sizes", "frame_style='".$id."', frame_width='".$_REQUEST['frame_width']."', frame_height='".$_REQUEST['frame_height']."', frame_mat_width='".$_REQUEST['frame_mat_width']."', frame_default='1'  ");
	header("location: index.php?do=photoprods&view=roomview&sub=frames");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "deleteframestyle") { 
	$style = doSQL("ms_frame_styles", "*", "WHERE style_id='".$_REQUEST['style_id']."' ");
	if(!empty($style['style_id'])) { 
		
		deleteSQL("ms_frame_styles", "WHERE style_id='".$style['style_id']."' ","1");
		deleteSQL2("ms_frame_sizes","WHERE frame_style='".$style['style_id']."' ");
		$imgs = whileSQL("ms_frame_images","*","WHERE img_style='".$style['style_id']."' ");
		while($img = mysqli_fetch_array($imgs)) { 
			if(file_exists($setup['path'].$img['img_small'])) { 
				unlink($setup['path'].$img['img_small']);
			}
			if(file_exists($setup['path'].$img['img_large'])) { 
				unlink($setup['path'].$img['img_large']);
			}
			deleteSQL2("ms_frame_images","WHERE img_id='".$img['img_id']."' ");
		}
	}
	$_SESSION['sm'] = "Frame style deleted";
	header("location: index.php?do=photoprods&view=roomview&sub=frames");
	session_write_close();
	exit();
}
?>
<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a> <?php print ai_sep;?>Frames</a> </div>
<div class="pc">Here you can add and manage frame styles and sizes. <a href="https://www.picturespro.com/sytist-manual/wall-designer/frames/" target="_blank">Video tutorial and more information here in the manual</a>.</div>
<div class="right textright buttons">
<a href="" onclick="editstyleinfo('0'); return false;" class="the-icons icon-plus">Add New Frame Style</a>
</div>
<div class="clear"></div>
<div>&nbsp;</div>

<?php if($_REQUEST['copysizes'] > 0) { 
		if($_REQUEST['copyfrom'] > 0) { 
			if($setup['demo_mode'] == true) { 
				$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
				header("location: index.php?do=photoprods&view=roomview&sub=frame&style_id=".$_REQUEST['copysizes']."");
				session_write_close();
				exit();
			} else { 

			if($_REQUEST['replace'] == "1") { 
				deleteSQL2("ms_frame_sizes", "WHERE frame_style='".$_REQUEST['copysizes']."' ");
			}
			$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['copyfrom']."' ORDER BY frame_order ASC");
			while($frame = mysqli_fetch_array($frames)) {
				$lqry  = "";
				$x = 0;
				$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_frame_sizes");
				if (mysqli_num_rows($result) > 0) {
					while ($row = mysqli_fetch_assoc($result)) {
						if(($row['Field'] !== "frame_id")&&($row['Field'] !== "frame_style")==true) { 
							if($x > 0) { $lqry.=","; } 
							$x++;
							$lqry .= $row['Field']."='".addslashes(stripslashes($frame[$row['Field']]))."' ";
							print "<li>".$row['Field']." = ".$frame[$row['Field']]."</li>";
						}
					}
				}

				$lqry .= ",frame_style='".addslashes(stripslashes($_REQUEST['copysizes']))."' ";
				$id = insertSQL("ms_frame_sizes", "$lqry" );

			}
		
		
		$_SESSION['sm'] = "Frame sizes copied";
		header("location: index.php?do=photoprods&view=roomview&sub=frame&style_id=".$_REQUEST['copysizes']."");
		session_write_close();
		exit();
		}
	}
?>
<div class="pc"><h3>Copy Frame Sizes</h3></div>
<div class="pc">Click the Copy link next to the frame style you wish to copy frame sizes from.</div>

<?php } ?>

<script>
jQuery(document).ready(function() {
	sortItems('sortable-list','sort_order','orderFrameStyles');
});
</script>


<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$frames = whileSQL("ms_frame_styles", "*", "ORDER BY style_order ASC ");
while($frame = mysqli_fetch_array($frames)) { 
	$order[] = $frame['frame_id'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>


	<ul id="sortable-list" class="sortable-list">

<?php
$styles = whileSQL("ms_frame_styles", "*", "ORDER BY style_order ASC ");
while($style = mysqli_fetch_array($styles)) { 
	$img = doSQL("ms_frame_images","*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC ");
	?>
<li title="<?php print $style['style_id'];?>">
	<div class="underline">
	<div class="left p15">
		<div class="pc center"><?php if(!empty($img['img_id'])) { ?><a href="index.php?do=photoprods&view=roomview&sub=frame&style_id=<?php print $style['style_id'];?>"><img src="<?php print $setup['temp_url_folder'].$img['img_small'];?>" style="max-height: 100px; width: auto;height: auto;"></a><?php } ?></div>
	
	</div>
	<div class="left p85">
		<div class="pc">
		<a href="index.php?do=photoprods&view=roomview&sub=frame&style_id=<?php print $style['style_id'];?>"><?php print $style['style_name'];?></a>
		</div>



		<div class="pc inlineli">
		<?php 
		$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$style['style_id']."' ORDER BY frame_order ASC");
		while($frame = mysqli_fetch_array($frames)) { ?>
		<?php print ($frame['frame_width'] + 0);?>x<?php print ($frame['frame_height']+ 0);?> &nbsp; 
		<?php } ?>
		</div>
		<?php if(($_REQUEST['copysizes'] > 0) && ($_REQUEST['copysizes'] !== $style['style_id']) == true) { ?>
		<div class="pc bold"><a href="index.php?do=photoprods&view=roomview&sub=frames&copysizes=<?php print $_REQUEST['copysizes'];?>&copyfrom=<?php print $style['style_id'];?>"><b>Copy only</b></a></div>
		<div class="pc bold"><a href="index.php?do=photoprods&view=roomview&sub=frames&copysizes=<?php print $_REQUEST['copysizes'];?>&copyfrom=<?php print $style['style_id'];?>&replace=1"><b>Copy & replace existing frame sizes</b></a></div>
		<?php } ?>


		<div class="pc inlineli">
		<?php 
		$colors = whileSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."'  ORDER BY img_order ASC ");
		if(mysqli_num_rows($colors) > 1) { 
		$bgsizes = explode(",",$style['style_frame_corners']);
		?>
		<ul id="frame-colors-<?php print $style['style_id'];?>" style="display: inline; ">
		<?php 
		while($color = mysqli_fetch_array($colors)) { ?><li><span  id="framecolor-<?php print $style['style_id'];?>-<?php print $color['img_id'];?>" class="framecolorselections"  style="height: 20px; width: 20px; background-image: url('<?php print $setup['temp_url_folder'].$color['img_small'];?>'); background-size: <?php print (100 / $bgsizes[0]) * 100;?>%; display: inline-block"></span></li>

		<?php } ?>
		</ul>
		<?php } ?>

		</div>

	<div class="pc inlineli">

		<ul id="mat-options-<?php print $style['style_id'];?>" style="display: inline; ">
		<?php 
		$matcolors = explode(",",$style['style_mat_colors']);
		$mats = whileSQL("ms_frame_mat_colors", "*", "ORDER BY color_order ASC ");
		while($mat = mysqli_fetch_array($mats)) { 
			if(in_array($mat['color_id'],$matcolors)) { 				
				?>
		<li><span  id="matcolor-<?php print $style['style_id'];?>-<?php print $mat['mat_id'];?>" class="matcolorselections" style="width: 20px; height: 20px; display: inline-block; border: solid 1px #d4d4d4; background: #<?php print $mat['color_color'];?>;">&nbsp;</span></li>

		<?php }
		} ?>
		</ul>

	</div>
	</div>
	<div class="clear"></div>
</div></li>

<?php } ?>
</ul>

<?php if(mysqli_num_rows($frames) > 1)  { ?><div class="pc center">Drag and drop to change the display order</div><?php } ?>