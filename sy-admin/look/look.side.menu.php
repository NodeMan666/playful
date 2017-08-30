<?php if(!empty($_REQUEST['deleteFeature'])) { 
	deleteSQL("ms_side_menu", "WHERE side_id='".$_REQUEST['deleteFeature']."' ", "1");
	$_SESSION['sm'] = "Feature deleted";
	header("location: index.php?do=look&view=sidemenu");
	session_write_close();
	exit();
}
?>

<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Side Bar</div>
<div class="pc">The side bar is a section that can be show on the side of your pages. When editing your theme, you have the option to place it to the left or right side of the site and also disable the sidebar completely. <br><br>
Below are the features you can easily add to your side bar. Click the + sign to add a feature.</div>
<div>&nbsp;</div>

<div style="width: 29%; float: left;">
<div class="underlinelabel">Add a feature</div>
<div class="pc">Below are features you can add to your side bar section.</div>
<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=recentitems'); return false;" class="bold"><?php print ai_add;?> Recent Pages </a></div>
	<div>This will display the most recent pages added to your website.</div>
</div>

<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=popular'); return false;" class="bold"><?php print ai_add;?> Popular Pages</a></div>
	<div>This will display the most viewed pages over the last week.</div>
</div>
<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=menu'); return false;" class="bold"><?php print ai_add;?> Side Bar Menu</a></div>
	<div>The side bar menu from your <a href="index.php?do=look&view=links">Menu Links</a> section.</div>
</div>


<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=textarea'); return false;" class="bold"><?php print ai_add;?> Text Area for HTML</a></div>
	<div>Enter in your own HTML.</div>
</div>

<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=facebook'); return false;" class="bold"><?php print ai_add;?> Facebook Like Box</a></div>
	<div>Add the Facebook Like Box for your Facebook page. (page, not your personal profile)</div>
</div>

<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=phpfile'); return false;" class="bold"><?php print ai_add;?> Include PHP File</a></div>
	<div>(Advanced users) Include a PHP file.</div>
</div>
<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=search'); return false;" class="bold"><?php print ai_add;?> Search Form</a></div>
	<div>This add the search form for site search.</div>
</div>
<div class="underline">
	<div><a href="" onclick="openFrame('w-side-menu.php?feature=pagetags'); return false;" class="bold"><?php print ai_add;?> Pages Tags</a></div>
	<div>Show a list of tags you have added to pages.</div>
</div>



</div>

<div style="width: 69%; float: right;">


	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-sides','sort_order-sides','ordersidebar');
	});
	</script>
	<form id="dd-form-sides" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="ordersidebar">
	<input type="hidden" name="link_location" value="sides">
	<?php
	unset($order);
	$sides = whileSQL("ms_side_menu", "*", "WHERE side_id>'0'  ORDER BY side_order ASC  ");
	while($side = mysqli_fetch_array($sides)) {
		$order[] = $side['side_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-sides" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>




<div class="underlinelabel">Current Features In Use</div>
<div class="pc">These are the features you have added to the sidebar.</div>
<div id="message-box" class="pageContent"><?php echo $message; ?></div>

<ul id="sortable-list-sides" class="sortable-list">

<?php $sides = whileSQL("ms_side_menu", "*", "ORDER BY side_order ASC ");
if(mysqli_num_rows($sides) <=0) { ?><div class="underline center">No features added</div><?php } ?>


<?php while($side = mysqli_fetch_array($sides)) { 
if($side['side_feature'] == "recentitems") { 
	$feat_name = "Recent Items From";
}
if($side['side_feature'] == "textarea") { 
	$feat_name = "Text Area";
}
if($side['side_feature'] == "facebook") { 
	$feat_name = "Facebook Like Box";
}
if($side['side_feature'] == "phpfile") { 
	$feat_name = "Include PHP File";
}
if($side['side_feature'] == "pagetags") { 
	$feat_name = "Pages Tags";
}

	?>
<li title="<?php print $side['side_id'];?>">
<div class="underline">
<div style="width: 10%;" class="left">
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;"><?php print ai_edit;?></a> 
<a href="index.php?do=look&view=sidemenu&deleteFeature=<?php print $side['side_id'];?>" onClick="return confirm('Are you sure you want to delete this feature?');" ><?php print ai_delete;?></a> 
<?php print ai_sort; ?>
</div>
<div style="width: 30%;" class="left">
<?php if($side['side_feature'] == "recentitems") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Recent Pages  
<?php if($side['side_cat'] > 0) { $cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$side['side_cat']."' "); print $cat['cat_name']; } 
if(($side['side_feature'] == "recentitems")&&($side['side_cat'] == "999999999") ==true ){ print "All Sections"; } ?></a>
<?php } ?>

<?php if($side['side_feature'] == "popular") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Popular Pages 
<?php if($side['side_cat'] > 0) { $cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$side['side_cat']."' "); print $cat['cat_name']; } 
if($side['side_cat'] == "999999999") { print "All Sections"; } ?></a>
<?php } ?>

<?php if($side['side_feature'] == "facebook") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Facebook Like Box </a>
<?php } ?>
<?php if($side['side_feature'] == "textarea") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Text Area </a>
<?php } ?>
<?php if($side['side_feature'] == "menu") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Side Bar Menu </a>
<?php } ?>
<?php if($side['side_feature'] == "phpfile") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Included PHP file</a>
<?php } ?>
<?php if($side['side_feature'] == "search") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Search Form</a>
<?php } ?>
<?php if($side['side_feature'] == "pagetags") { ?>
<a href=""  onclick="openFrame('w-side-menu.php?side_id=<?php print $side['side_id'];?>'); return false;" class="bold">Pages Tags</a>
<?php } ?>

</div>
<div style="width: 30%;" class="left">
<?php if(($side['side_feature'] == "popular")|| ($side['side_feature'] == "recentitems")==true) { ?>
<?php print $side['side_limit'];?> items 
<?php if($side['side_minis'] == "1") { print " with mini thumbnails"; } ?>
<?php } ?>
&nbsp;
</div>
<div style="width: 30%;" class="left">

</div>
<div class="clear"></div>
</div>
</li>
<?php } ?>
</ul>
	</form>



</div>
<div class="clear"></div>
