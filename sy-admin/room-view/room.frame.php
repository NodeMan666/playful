<script>
function openframewindow(style_id,img_id) { 
	pagewindowedit("room-view/room-frame-edit.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id+"&img_id="+img_id+"&frameadjust="+style_id);
}



function openframewindow(style_id,img_id) { 
	windowloading();
	// $("#pagewindowbgcontainer").fadeIn(100);
	$("body").append('<div id="frameeditwindow"></div>');

	$("#frameeditwindow").css({"width":"100%", "top":"0", "left":"0","z-index":"200","position":"fixed","height":"100%"});
		$.get("room-view/room-frame-edit.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id+"&img_id="+img_id+"&frameadjust="+style_id, function(data) {
			$("#frameeditwindow").html(data);
			$("#frameeditwindow").slideDown(200, function() { 
				windowloadingdone();
			});
		});
}

function clodeframewindow() { 
	$("#frameeditwindow").fadeOut(200, function() { 
		$("#frameeditwindow").html("");
	});
}

function framesizes(style_id,frame_id) { 
	pagewindowedit("room-view/room-frame-sizes.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id+"&frame_id="+frame_id+"&frameadjust="+style_id);
}

function deleteframesize(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=frame&action=deleteframesize&frame_id="+id, function(data) {
		$("#frame-size-"+id).slideUp(200);
	});
}
function deleteframeimg(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=frame&action=deleteframeimg&img_id="+id, function(data) {
		$("#frame-image-"+id).hide(200);
	});
}
function framedefault(frame_id,style_id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=frame&action=defaultframe&frame_id="+frame_id+"&style_id="+style_id, function(data) {
		showSuccessMessage("Default Frame Updated");
		setTimeout(hideSuccessMessage,4000);
	});
}
function editstyleinfo(style_id) { 
	pagewindowedit("room-view/room-frame-info.php?noclose=1&nofonts=1&nojs=1&style_id="+style_id);
}

function saveframecolor(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=frame&action=framecolorname&img_id="+id+"&img_color="+$("#img-color-"+id).val(), function(data) {
		showSuccessMessage("Color / Name Saved");
		setTimeout(hideSuccessMessage,4000);
	});
}
function showbatchedit() { 
	$(".viewing").toggle();
	$(".editing").toggle();
}

</script>

<?php

if($_REQUEST['action'] == "savebatch") { 
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
		header("location: index.php?do=photoprods&view=roomview&sub=frame&style_id=".$_REQUEST['style_id']."");
		session_write_close();
		exit();
	} else { 

		$items = explode("|", $_POST['frame_ids']);
		foreach($items AS $item) {
			if($item > 0) { 
				// print "<li>$item: ".$_REQUEST['cp_width_'.$item];
			updateSQL("ms_frame_sizes", "frame_width='".$_REQUEST['frame_width_'.$item]."', frame_height='".$_REQUEST['frame_height_'.$item]."', frame_price='".$_REQUEST['frame_price_'.$item]."',  frame_mat_width='".$_REQUEST['frame_mat_width_'.$item]."', frame_mat_price='".$_REQUEST['frame_mat_price_'.$item]."', frame_mat_print_width='".$_REQUEST['frame_mat_print_width_'.$item]."', frame_mat_print_height='".$_REQUEST['frame_mat_print_height_'.$item]."' WHERE frame_id='".$item."' ");

			}
		}

		$_SESSION['sm'] = "Changes Saved";
		header("location: index.php?do=photoprods&view=roomview&sub=frame&style_id=".$_REQUEST['style_id']."");
		session_write_close();
		exit();
	}
}

if($setup['demo_mode'] !== true) { 

	if($_REQUEST['action'] == "framecolorname") { 
		updateSQL("ms_frame_images","img_color='".addslashes(stripslashes(trim($_REQUEST['img_color'])))."' WHERE img_id='".$_REQUEST['img_id']."' ");
		exit();
	}

	if($_REQUEST['action'] == "deleteframesize") { 
		deleteSQL("ms_frame_sizes", "WHERE frame_id='".$_REQUEST['frame_id']."' ","1");
		exit();
	}
	if($_REQUEST['action'] == "deleteframeimg") { 
		$img = doSQL("ms_frame_images","*","WHERE img_id='".$_REQUEST['img_id']."' ");
		if(file_exists($setup['path'].$img['img_small'])) { 
			unlink($setup['path'].$img['img_small']);
		}
		if(file_exists($setup['path'].$img['img_large'])) { 
			unlink($setup['path'].$img['img_large']);
		}
		deleteSQL2("ms_frame_images","WHERE img_id='".$img['img_id']."' ");
		exit();
	}


	if($_REQUEST['action'] == "defaultframe") { 
		updateSQL("ms_frame_sizes", "frame_default='0' WHERE frame_style='".$_REQUEST['style_id']."' ");
		updateSQL("ms_frame_sizes", "frame_default='1' WHERE frame_id='".$_REQUEST['frame_id']."' ");
	}
}


$style = doSQL("ms_frame_styles", "*", "WHERE style_id='".$_REQUEST['style_id']."' ");
$img = doSQL("ms_frame_images","*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC ");
?>


<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview&sub=frames">Frames</a>  <?php print ai_sep;?> <?php print $style['style_name'];?></div>
<div class="clear"></div>
<div class="pc"><a href="" onclick="editstyleinfo('<?php print $style['style_id'];?>'); return false;" class="the-icons icon-pencil">Edit name / description / framing width</a> &nbsp; <a href="index.php?do=photoprods&view=roomview&sub=frames&action=deleteframestyle&style_id=<?php print $style['style_id'];?>" class="the-icons icon-trash-empty"  onClick="return confirm('Are you sure you want to delete this frame style completely? ');" >Delete</a>  &nbsp; 
<?php if(countIt("ms_frame_styles","") > 1) { ?>
<a href="index.php?do=photoprods&view=roomview&sub=frames&copysizes=<?php print $style['style_id'];?>" class="the-icons icon-docs">Copy sizes from another frame style</a>
<?php } ?>
</div>

<script>
jQuery(document).ready(function() {
	sortItems('sortable-list','sort_order','orderFrameSizes');
});
</script>


<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['style_id']."' ORDER BY frame_order ASC ");
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
<div>&nbsp;</div>

<div class="right buttons textright"><a href="" onclick="framesizes('<?php print $style['style_id'];?>'); return false;" class="the-icons icon-plus">Add New Frame Size</a></div>
<div class="pc"><h2>Frame Sizes</h2></div>
<div class="pc">Manage frame sizes below</div>
<div class="underlinelabel">
	<div class="left p10">&nbsp;</div>
	<div class="left p10">Width</div>
	<div class="left p10">Height</div>
	<div class="left p10">Mat Size</div>
	<div class="left p10">Mat  Print<br>Width</div>
	<div class="left p10">Mat Print<br>Height</div>
	<div class="left p10">Price</div>
	<div class="left p10">Matted<br>Price</div>
	<div class="left p10">Ship</div>
	<div class="left p10">Default</div>
	<div class="clear"></div>
</div>
<form method="post" name="be" id="be" action="index.php">

	<ul id="sortable-list" class="sortable-list">

<?php

$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['style_id']."' ORDER BY frame_order ASC ");
if(mysqli_num_rows($frames) <= 0) { ?>
<div class="pc center">You have not added any sizes for this frame style. Click Add New Size to add available sizes.</div>
<?php } ?>
<?php while($frame = mysqli_fetch_array($frames)) {
	$frame_ids = $frame_ids.$frame['frame_id']."|"; 


?>


<li title="<?php print $frame['frame_id'];?>" id="frame-size-<?php print $frame['frame_id'];?>">
<div class="underline">
	<div class="left p10"><a href="" onclick="framesizes('<?php print $frame['frame_style'];?>','<?php print $frame['frame_id'];?>'); return false;" class="the-icons icon-pencil"></a>
	<a href="javascript:deleteframesize('<?php print $frame['frame_id'];?>');"  onClick="return confirm('Are you sure you want to delete this frame size? ');" class="the-icons icon-trash-empty"></a></div>
	<div class="left p10">
		<div class="viewing"><?php print $frame['frame_width'] * 1;?></div>
		<div class="editing hide"><input type="text" name="frame_width[<?php print $frame['frame_id'];?>" id="frame_width[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_width'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php print $frame['frame_height'] * 1;?></div>
		<div class="editing hide"><input type="text" name="frame_height[<?php print $frame['frame_id'];?>" id="frame_height[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_height'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php if($frame['frame_mat_width'] > 0) {  print $frame['frame_mat_width'] * 1; } else { print "&nbsp;"; } ?></div>
		<div class="editing hide"><input type="text" name="frame_mat_width[<?php print $frame['frame_id'];?>" id="frame_mat_width[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_mat_width'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php if($frame['frame_mat_print_width'] > 0) { print $frame['frame_mat_print_width'] * 1; } else { print "&nbsp;"; } ?></div>
		<div class="editing hide"><input type="text" name="frame_mat_print_width[<?php print $frame['frame_id'];?>" id="frame_mat_print_width[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_mat_print_width'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php if($frame['frame_mat_print_height'] > 0) { print $frame['frame_mat_print_height'] * 1; } else { print "&nbsp;"; } ?></div>
		<div class="editing hide"><input type="text" name="frame_mat_print_height[<?php print $frame['frame_id'];?>" id="frame_mat_print_height[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_mat_print_height'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php print showPrice($frame['frame_price']);?></div>
		<div class="editing hide"><input type="text" name="frame_price[<?php print $frame['frame_id'];?>" id="frame_price[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_price'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php if($frame['frame_mat_price'] > 0) { print showPrice($frame['frame_mat_price']); } else { print "&nbsp;"; } ?></div>
		<div class="editing hide"><?php print $site_setup['currency_sign'];?> <input type="text" name="frame_mat_price[<?php print $frame['frame_id'];?>" id="frame_mat_price[<?php print $frame['frame_id'];?>" size="4" class="center" value="<?php print $frame['frame_mat_price'] * 1;?>"></div>

	</div>
	<div class="left p10">
		<div class="viewing"><?php if($frame['frame_shipable'] == "1") { ;?><span class="the-icons icon-check">&nbsp;</span><?php } else { ?>&nbsp;<?php } ?></div>

	</div>
	<div class="left p10">
		<div class="viewing"><input type="radio" name="frame_default" id="frame_default-<?php print $frame['frame_id'];?>" value="1" data-style-id="<?php print $style['style_id'];?>" data-frame-id="<?php print $frame['frame_id'];?>" onchange="framedefault('<?php print $frame['frame_id'];?>','<?php print $style['style_id'];?>');" <?php if($frame['frame_default'] == "1") { ?>checked<?php } ?>></div>

		</div>

	<div class="clear"></div>
</div>
</li>
<?php } ?>
</ul>

<div class="pc">
<input type="hidden" name="do" id="do" value="photoprods" class="formfield">
<input type="hidden" name="view" id="view" value="roomview" class="formfield">
<input type="hidden" name="sub" id="sub" value="frame" class="formfield">
<input type="hidden" name="action" id="action" value="savebatch" class="formfield">
<input type="hidden" name="style_id" id="style_id" value="<?php print $style['style_id'];?>" class="formfield">
<input type="hidden" name="frame_ids" id="frame_ids" value="<?php print $frame_ids;?>" class="formfield">
<input type="submit" name="submit" value="Save Changes" class="submit editing hide">
</div>
</form>

<div class="clear"></div>
<div>&nbsp;</div>

<?php if(mysqli_num_rows($frames) > 1) { ?><div class="pc">Drag and drop to change the display order</div>
<div class="pc"><a href="" onclick="showbatchedit(); return false;">Batch Edit</a></div>

<?php } ?>
<div>&nbsp;</div>






<script>
jQuery(document).ready(function() {
	sortItems('sortable-list-frames','sort_order','orderFrameColors');
});
</script>

<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$colors = whileSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."'  ");
while($color = mysqli_fetch_array($colors)) { 
	$order[] = $color['img_order'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>
<div class="right">
	<?php if($setup['demo_mode'] == true) { ?>
		Frame Upload Disabled For Demo
	<?php } else { ?>
	<input type="file" name="file_upload-<?php print $style['style_id'];?>" id="file_upload-<?php print $style['style_id'];?>" />
	<?php 
	$hash = $site_setup['salt']; 
	$timestamp = date('Ymdhis');
	?>


	<script>
	$(function() {
		$('#file_upload-<?php print $style['style_id'];?>').uploadify({
			 'multi'    : true,
			<?php if($_REQUEST['debug'] == "1") { ?>
			'debug'    : true,	
			<?php } ?>
			'method'   : 'post',
			'fileTypeExts' : '*',
			'fileTypeDesc' : 'all files',
				'width':'300',
			'buttonText' : 'Upload Frames',
			 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
				 'token' : '<?php echo md5($hash.$timestamp); ?>', 
				'styleid':'<?php print $style['style_id'];?>',
				 'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
			'onQueueComplete' : function(queueData) {
				window.parent.location.href='index.php?do=photoprods&view=roomview&sub=frame&style_id=<?php print $style['style_id'];?>&sm=File(s) Uploaded';
				}, 
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
					alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
				}, 

			'swf'      : 'uploadify/uploadify.swf',
			'uploader' :'room-view/room-frame-upload.php'
			// Put your options here
		});
	});

	</script>
	<?php } ?>
</div>
<div class="pc"><h2>Frame Photos</h2></div>
<div class="pc">Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. Some description here on frame photos. 
</div>
<div class="pc inlineli">
<?php 
$colors = whileSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC  ");
if(mysqli_num_rows($colors) > 0) { 
$bgsizes = explode(",",$style['style_frame_corners']);
?>
<ul id="sortable-list-frames" class="sortable-list">
<?php 
while($color = mysqli_fetch_array($colors)) { ?><li title="<?php print $color['img_id'];?>" id="frame-image-<?php print $color['img_id'];?>" style="display: inline-block; width: 20%; padding: 0px; margin: 0px;">
	<div style="padding: 16px;">
		<div class="pc center"><img src="<?php print $setup['temp_url_folder'].$color['img_small'];?>" style="display: inline-block; width: 100%; height: auto; cursor: move;"></div>

		<div class="pc center">
		<div>Color / Name</div>
		<div>
		<form method="post" id="framecolor-<?php print $color['color_id'];?>" action="index.php"><input type="text" name="img-color-<?php print $color['img_id'];?>" id="img-color-<?php print $color['img_id'];?>" class="field100 center" value="<?php print $color['img_color'];?>"> <a href="" onclick="saveframecolor('<?php print $color['img_id'];?>'); return false;" class="the-icons icon-floppy" title="Save"></a>
		</form>
		</div>
		</div>

		<div class="pc center">	<a href="javascript:deleteframeimg('<?php print $color['img_id'];?>');"  onClick="return confirm('Are you sure you want to delete this frame color? ');" class="the-icons icon-trash-empty">delete</a>
		&nbsp;
		<a href="" onclick="openframewindow('<?php print $style['style_id'];?>','<?php print $color['img_id'];?>'); return false;" class="the-icons icon-move">adjust frame</a>
		</div>

		</div></li><?php } ?>

	</ul>
<?php } ?>

</div>
<div>&nbsp;</div>
<script>
function setmatcolor(id,styleid) {
	if($("#matcolor-"+id).attr("data-selected") == "1") { 
		$("#matcolor-"+id).removeClass("selectedoutline");
		$("#matcolor-"+id).attr("data-selected","0")
	} else { 
		$("#matcolor-"+id).addClass("selectedoutline");
		$("#matcolor-"+id).attr("data-selected","1")
	}
	ids = "";

	$(".matcolorselections").each(function(i){
		if($(this).attr("data-selected") == "1") { 
			ids = ids+$(this).attr("data-mat-id")+",";
		}
	});
	// alert(ids);

	$.get("admin.actions.php?action=selectmatcolor&style_id="+styleid+"&ids="+ids, function(data) {

	});
}
</script>
<div class="pc"><h2>Mat Colors</h2></div>
<div class="pc">Click on the mat colors you want available with this frame style</div>
<div class="pc inlineli">
<ul id="mat-options-<?php print $style['style_id'];?>" style="display: inline; ">

<?php $mats = whileSQL("ms_frame_mat_colors", "*", "ORDER BY color_order ASC ");
if(mysqli_num_rows($mats) <= 0) { ?><div class="pc center">No mat colors have been created</div><?php  } ?>

<?php 
$matcolors = explode(",",$style['style_mat_colors']);

while($mat = mysqli_fetch_array($mats)) { ?>
<li><span  id="matcolor-<?php print $mat['color_id'];?>" data-mat-id="<?php print $mat['color_id'];?>" data-selected="<?php if(in_array($mat['color_id'],$matcolors)) { ?>1<?php } else { ?>0<?php } ?>" class="matcolorselections <?php if(in_array($mat['color_id'],$matcolors)) { ?>selectedoutline<?php } ?>" style="width: 20px; height: 20px; display: inline-block; border: solid 1px #d4d4d4; background: #<?php print $mat['color_color'];?>;" onclick="setmatcolor('<?php print $mat['color_id'];?>','<?php print $style['style_id'];?>'); return false;">&nbsp;</span></li>
<?php } ?>
	</ul>
</div>
<div>&nbsp;</div>