<?php
$cp_settings = doSQL("ms_canvas_settings", "*", "");
if($_REQUEST['action'] == "deletecanvas") { 
	if($setup['demo_mode'] !== true) { 
		$cp = doSQL("ms_canvas_prints","*","WHERE cp_id='".$_REQUEST['cp_id']."' ");
		deleteSQL2("ms_canvas_prints","WHERE cp_id='".$cp['cp_id']."' ");
	}
	exit();
}
if($_REQUEST['action'] == "savebatch") { 
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
		header("location: index.php?do=photoprods&view=roomview&sub=canvases");
		session_write_close();
		exit();
	} else { 
		$items = explode("|", $_POST['canvas_ids']);
		foreach($items AS $item) {
			if($item > 0) { 
				print "<li>$item: ".$_REQUEST['cp_width_'.$item];
				updateSQL("ms_canvas_prints","cp_width='".$_REQUEST['cp_width_'.$item]."', cp_height='".$_REQUEST['cp_height_'.$item]."', cp_opt1='".$_REQUEST['cp_opt1_'.$item]."', 	cp_name='".addslashes(stripslashes($_REQUEST['cp_name_'.$item]))."'  WHERE cp_id='".$item."' ");
			}
		}

		$_SESSION['sm'] = "Changes Saved";
		header("location: index.php?do=photoprods&view=roomview&sub=canvases");
		session_write_close();
		exit();
	}
}
?>
<script>
function editcanvas(cp_id) { 
	pagewindowedit("room-view/room-canvas.php?noclose=1&nofonts=1&nojs=1&cp_id="+cp_id);
}
function editcanvassettings() { 
	pagewindowedit("room-view/room-canvas-options.php?noclose=1&nofonts=1&nojs=1");
}
function deletecanvas(id) { 
	$.get("index.php?do=photoprods&view=roomview&sub=canvases&action=deletecanvas&cp_id="+id, function(data) {
		$("#cp-"+id).slideUp(200);
	});
}
function showbatchedit() { 
	$(".viewing").toggle();
	$(".editing").toggle();
}
</script>
<script>
jQuery(document).ready(function() {
	sortItems('sortable-list-canvas','sort_order','orderCanvasPrints');
});
</script>

<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$cps = whileSQL("ms_canvas_prints", "*", "ORDER BY cp_order ASC ");
while($cp = mysqli_fetch_array($cps)) {
	$order[] = $cp['cp_order'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>

<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a> <?php print ai_sep;?> Canvas Prints</a> </div>
<div class="right textright buttons">
<a href="" onclick="editcanvas('0'); return false;" class="the-icons icon-plus">Add New Canvas Size</a> 
<!-- <a href="" onclick="editcanvassettings(); return false;" class="the-icons icon-cog">Canvas Option Settings</a> -->
</div>
<div class="pc">These are the sizes of canvas prints you want available in the wall designer. </div>
<div class="clear"></div>
<div class="underlinecolumn">
	<div class="left p10">&nbsp;</div>
	<div class="left p10">Width</div>
	<div class="left p10">Height</div>
	<div class="left p30">Name</div>
	<div class="left p10">Price</div>

<!-- 	<?php if($cp_settings['cp_opt1_use'] == "1") { ?>
	<div class="left p10">
		<?php print $cp_settings['cp_opt1'];?>
	</div>
	<?php } ?>

	<?php if($cp_settings['cp_opt2_use'] == "1") { ?>
	<div class="left p10">
		<?php print $cp_settings['cp_opt2'];?>
	</div>
	<?php } ?>
	<?php if($cp_settings['cp_opt3_use'] == "1") { ?>
	<div class="left p10">
		<?php print $cp_settings['cp_opt3'];?>
	</div>
	<?php } ?>
	<?php if($cp_settings['cp_opt4_use'] == "1") { ?>
	<div class="left p10">
		<?php print $cp_settings['cp_opt4'];?>
	</div>
	<?php } ?>
-->
	<div class="clear"></div>


</div>


<form method="post" name="be" id="be" action="index.php">
<ul id="sortable-list-canvas" class="sortable-list">

<?php $cps = whileSQL("ms_canvas_prints", "*", "ORDER BY cp_order ASC ");
while($cp = mysqli_fetch_array($cps)) { 
	$canvas_ids = $canvas_ids.$cp['cp_id']."|"; 
	
	?>
<li title="<?php print $cp['cp_id'];?>">
	<div class="underline" id="cp-<?php print $cp['cp_id'];?>">
		<div class="left p10"><a href="" onclick="editcanvas('<?php print $cp['cp_id'];?>'); return false;" class="the-icons icon-pencil"></a> 
		<a href="javascript:deletecanvas('<?php print $cp['cp_id'];?>');" class="the-icons icon-trash-empty" onclick="return confirm('Are you sure you want to delete this?');"></a> 

		</div>
		<div class="left p10">
			<div class="viewing"><?php print $cp['cp_width'] * 1;?></div>
			<div class="editing hide"><input type="text" name="cp_width[<?php print $cp['cp_id'];?>" id="cp_width[<?php print $cp['cp_id'];?>" size="4" class="center" value="<?php print $cp['cp_width'] * 1;?>"></div>

		</div>
		<div class="left p10">
			<div class="viewing"><?php print $cp['cp_height'] * 1;?></div>
			<div class="editing hide"><input type="text" name="cp_height[<?php print $cp['cp_id'];?>" id="cp_height[<?php print $cp['cp_id'];?>" size="4" class="center" value="<?php print $cp['cp_height'] * 1;?>"></div>

		</div>

		<div class="left p30">
			<div class="viewing"><?php print $cp['cp_name'];?>&nbsp;</div>
			<div class="editing hide"><input type="text" name="cp_name[<?php print $cp['cp_id'];?>" id="cp_name[<?php print $cp['cp_id'];?>" size="12" class="center" value="<?php print $cp['cp_name'];?>"></div>

		</div>

		<?php if($cp_settings['cp_opt1_use'] == "1") { ?>
		<div class="left p10">
		<?php 
			if($cp['cp_price_product'] > 0) { 
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cp['cp_price_product']."' ");
				$cp['cp_opt1'] = $prod['pp_price'];
			}
			?>
			<div class="viewing"><?php print showPrice($cp['cp_opt1']);?></div>
			<div class="editing hide"><?php print $site_setup['currency_sign'];?> <input type="text" name="cp_opt1[<?php print $cp['cp_id'];?>" id="cp_opt1[<?php print $cp['cp_id'];?>" size="4" class="center" value="<?php print $cp['cp_opt1'] * 1;?>"></div>

		</div>
		<?php } ?>
		<?php if($cp_settings['cp_opt2_use'] == "1") { ?>
		<div class="left p10">
			<?php print showPrice($cp['cp_opt2']);?>
		</div>
		<?php } ?>
		<?php if($cp_settings['cp_opt3_use'] == "1") { ?>
		<div class="left p10">
			<?php print showPrice($cp['cp_opt3']);?>
		</div>
		<?php } ?>
		
			<?php if($cp_settings['cp_opt4_use'] == "1") { ?>
		<div class="left p10">
			<?php print showPrice($cp['cp_opt4']);?>
		</div>
		<?php } ?>

		<div class="clear"></div>
	</div>
</li>
<?php } ?>
</ul>
<div class="pc">
<input type="hidden" name="do" id="do" value="photoprods" class="formfield">
<input type="hidden" name="view" id="view" value="roomview" class="formfield">
<input type="hidden" name="sub" id="sub" value="canvases" class="formfield">
<input type="hidden" name="action" id="action" value="savebatch" class="formfield">
<input type="hidden" name="canvas_ids" id="canvas_ids" value="<?php print $canvas_ids;?>" class="formfield">
<input type="submit" name="submit" value="Save Changes" class="submit editing hide">
</div>
</form>
<div>&nbsp;</div>
<?php if(mysqli_num_rows($cps) > 1) { ?><div class="pc viewing">Drag and drop to change the display order</div>
<div class="pc"><a href="" onclick="showbatchedit(); return false;">Batch Edit</a></div>

<?php } ?>
