<?php 
if($_REQUEST['action'] == "addnewcolor") { 
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
		header("location: index.php?do=photoprods&view=roomview&sub=mats");
		session_write_close();
		exit();
	} else { 

		if($_REQUEST['color_id'] > 0) { 
			$id = updateSQL("ms_frame_mat_colors", "color_name='".addslashes(stripslashes(trim($_REQUEST['style_name'])))."', color_color='".$_REQUEST['color_color']."' WHERE color_id='".$_REQUEST['color_id']."' ");
		} else { 
			$id = insertSQL("ms_frame_mat_colors", "color_name='".addslashes(stripslashes(trim($_REQUEST['style_name'])))."', color_color='".$_REQUEST['color_color']."'  ");
		}
		$_SESSION['sm'] = "Mat color saved";
		header("location: index.php?do=photoprods&view=roomview&sub=mats");
		session_write_close();
		exit();
	}
}

if($_REQUEST['action'] == "deletecolor") { 
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
		header("location: index.php?do=photoprods&view=roomview&sub=mats");
		session_write_close();
		exit();
	} else { 

		deleteSQL("ms_frame_mat_colors", "WHERE color_id='".$_REQUEST['color_id']."' ","1");
		$_SESSION['sm'] = "Mat color deleted";
		header("location: index.php?do=photoprods&view=roomview&sub=mats");
		session_write_close();
		exit();
	}
}


?>
<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview&sub=frames">Frames</a>  <?php print ai_sep;?> Mat Colors</div>
<div class="clear"></div>
<div class="pc">Here you can add the colors you want to be available for frame matting. Each frame style you add, you will select from these available colors.</div>
<div>&nbsp;</div>
<?php if($_REQUEST['color_id'] > 0) { 
$color = doSQL("ms_frame_mat_colors", "*", "WHERE color_id='".$_REQUEST['color_id']."' ");
?>
<div class="pc"><h3>Edit mat color</h3></div>
<?php } else { ?>
<div class="pc"><h3>Add new mat color</h3></div>
<?php } ?>
<form method="post" name="newframestyle" action="index.php" method="POST" onSubmit="return checkForm('.optrequired');"> 
<div class="pc left">
	<div>Name</div>
	<div>
	<input type="text" name="style_name" id="style_name" size="10" class="optrequired" value="<?php print $color['color_name'];?>">
	</div>
</div>
<div class="pc left">
	<div>Color</div>
	<div>
	<input type="text" name="color_color" id="color_color" size="6" class="color optrequired" value="<?php print $color['color_color'];?>">
	</div>
</div>
<div class="pc left">
<div>&nbsp;</div>
	<div>
	<input type="hidden" name="do" value="photoprods">
	<input type="hidden" name="view" value="roomview">
	<input type="hidden" name="sub" value="mats">
	<input type="hidden" name="color_id" value="<?php print $color['color_id'];?>">
	<input type="hidden" name="action" value="addnewcolor">
	<input type="submit" name="submit" class="submitSmall" value="<?php if($_REQUEST['color_id'] > 0) { ?>Save<?php } else { ?>Add<?php } ?>">
	</div>
</div>
<div class="clear"></div>
</form>
<div>&nbsp;</div>

<script>
jQuery(document).ready(function() {
	sortItems('sortable-list','sort_order','orderFrameSizeMatColors');
});
</script>


<form id="dd-form" action="index.php" method="post">
<?php
unset($order);
$colors = whileSQL("ms_frame_mat_colors", "*", "ORDER BY color_order ASC ");
while($color = mysqli_fetch_array($colors)) { 
	$order[] = $color['color_id'];
}
?>
<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
<p style="display: none;">
  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
  <label for="autoSubmit">Automatically submit on drop event</label>
</p>
</form>


<?php $colors = whileSQL("ms_frame_mat_colors", "*", "ORDER BY color_order ASC ");
if(mysqli_num_rows($colors) <= 0) { ?><div class="pc center">No mat colors have been added</div><?php  } ?>
	<ul id="sortable-list" class="sortable-list">

<?php
while($color = mysqli_fetch_array($colors)) { ?>

<li title="<?php print $color['color_id'];?>">
<div class="underline">

	<div class="left p10">
		<a href="index.php?do=photoprods&view=roomview&sub=mats&color_id=<?php print $color['color_id'];?>" class="the-icons icon-pencil"></a> 
		<a href="index.php?do=photoprods&view=roomview&sub=mats&action=deletecolor&color_id=<?php print $color['color_id'];?>" class="the-icons icon-trash-empty" onClick="return confirm('Are you sure you want to delete thismat color?');" ></a> 

	</div>

	<div class="left p10">
		<div style="display: block; width: 60px; height: 60px; background: #<?php print $color['color_color'];?>; border: solid 1px #949494;">&nbsp;</div>
	</div>
	<div class="left p30">
		<?php print $color['color_name'];?>
	</div>
	<div class="clear"></div>

</div></li>
<?php } ?>
</ul>
<?php if(mysqli_num_rows($colors) > 1) { ?><div class="pc">Drag & drop to change the display order</div><?php } ?>
<div>&nbsp;</div>
