
<div class="buttonsgray">
	<ul>


	<?php if(!empty($_REQUEST['cat_id'])) { ?>
		<li><a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>" class="<?php if($_REQUEST['action'] == "editCategory") { print "on"; } ?>">SETTINGS</a></li>

		<li><a href="<?php tempFolder(); ?><?php print $setup['content_folder'];?><?php print $cat['cat_folder'];?>/" target="_blank">VIEW</a></li>
		<li><a href="index.php?do=news&action=categoryThumbnail&cat_id=<?php print $cat['cat_id'];?>" class="<?php if($_REQUEST['action'] == "categoryThumbnail") { print "on"; } ?>">PREVIEW PHOTO</a></li>
		<li><a href="index.php?do=news&action=photoDefaults&cat_id=<?php print $cat['cat_id'];?>" class="<?php if($_REQUEST['action'] == "photoDefaults") { print "on"; } ?>">PHOTO DEFAULTS</a></li>
		<?php if(countIt("ms_blog_categories", "WHERE cat_under='".$cat['cat_id']."' ") <=0) { ?>
		<li><a href="" onclick="openFrame('w-category-folder.php?cat_id=<?php print $cat['cat_id'];?>'); return false;">RENAME FOLDER</a></li>
		<?php } ?>

		<?php /* SWEETNESS */
		$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$cat['cat_id']."' AND enabled='1' AND default_feat<='0' ");

	?>
	<?php } ?>

	<div class="cssClear"></div>
	</ul>
</div>
