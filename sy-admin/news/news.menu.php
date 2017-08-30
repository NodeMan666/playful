<?php 
define("cat_table", "ms_blog_categories"); 
define("cat_connect_table", "ms_blog_cats_connect"); 
define("items_table", "ms_calendar");
define("items_id", "date_id"); 
define("items_cat_field", "date_cat"); 
define("this_do", "news"); 
define("folder", $setup['content_folder']); 
define("cat_field_url", "date_cat"); 
?>
<script>
function newsstats(date_id) { 
	pagewindowedit("news/news-stats.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}
function newsusers(date_id,em) { 
	pagewindowedit("news/news-users.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id+"&email_id="+em);
}
function newsusersproofing(date_id) { 
	pagewindowedit("news/news-users-proofing.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}
function proofingrevise(date_id) { 
	pagewindowedit("news/news-users-proofing.php?revision=1&noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}
function proofingclose(date_id) { 
	pagewindowedit("news/news-users-proofing.php?closeproject=1&noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}
function prodquantitydiscount(date_id) { 
	pagewindowedit("store/prod-quantity-discounts.php?noclose=1&nofonts=1&nojs=1&date_id="+date_id);
}


</script>

<?php
if($_REQUEST['date_id'] > 0) { 
	$prod = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	$_REQUEST['this_date_cat'] = $prod['date_cat'];
} else if($_REQUEST['cat_id'] > 0) { 
	$_REQUEST['this_date_cat'] = $_REQUEST['cat_id'];
} else if($_REQUEST['cat_under'] > 0) { 
	$_REQUEST['this_date_cat'] = $_REQUEST['cat_under'];
} else { 
	$_REQUEST['this_date_cat'] = $_REQUEST['date_cat'];
}
?>
	<div id="sitecontent">
		<div class="info">These are the main sections of your website.</div>

		<?php $hp = doSQL("ms_calendar", "*", "WHERE page_home='1' ");?>
		<div class="rowhover large <?php if($_REQUEST['date_id'] == $hp['date_id']) { ?>rowhoveron<?php } ?>">
		<a href="index.php?do=news&action=addDate&date_id=<?php print $hp['date_id'];?>"><?php print ai_home_page;?> Manage Home Page</a></li>
<div class="submenu"  style="height: 16px;"><div class="hovermenu">&nbsp; </div></div>
		</div>
		<div class="rowhover large">
		<a href="index.php?do=news&action=addDate&date_cat=none" class="tip" title="Add new top level page"><?php print ai_new_page;?></a> 
		<a href="index.php?do=news&<?php print cat_field_url;?>=none">Top Level Pages</a> 
		<?php 		$tcat = countIt("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", " WHERE date_cat='0' AND date_type='news' AND page_under='0' AND page_home='0' AND page_404='0'  ");
		print " (".$tcat.")";
		?>
<div class="submenu"  style="height: 16px;"><div class="hovermenu">&nbsp; </div></div>
		</div>
		<?php blogAdvMenu($top_sub_folder); ?>
		<div>&nbsp;</div>
		<div class="newsection center"><a href="index.php?do=news&action=editCategory">Create New Section</a>
		<div>&nbsp;</div>
</div>
		<?php $p4 = doSQL("ms_calendar", "*" , "WHERE page_404='1' "); ?>
		<div class="row center muted"><a href="index.php?do=news&action=addDate&date_id=<?php print $p4['date_id'];?>">Edit custom 404 page</a></div>
	<div>&nbsp;</div>
	<?php 	$tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC "); 
		if(mysqli_num_rows($tags) > 0) { 
	?>

	<div class="info large">Tags</div>
	<div class="info" style="max-height: 150px; overflow-y: scroll;">
	<?php 
	while($tag = mysqli_fetch_array($tags)) { 
		$cktag = doSQL("ms_tag_connect", "*", "WHERE tag_tag_id='".$tag['tag_id']."' AND tag_date_id='".$date['date_id']."' "); 	?>
	<nobr><a href="index.php?do=<?php print $_REQUEST['do'];?>&tag_id=<?php print $tag['tag_id'];?>"><?php print $tag['tag_tag'];?> (<?php print countIt("ms_tag_connect", "WHERE tag_tag_id='".$tag['tag_id']."' ");?>)</a></nobr>, 
	<?php } ?>
	</div>	
	<?php } ?>
	</div>

<div>&nbsp;</div>





<style>
#triangle {
    border-right: 6px solid; 
    border-bottom: 6px solid;
    width:20px; height: 20px;
    transform: rotate(45deg);
	color: #FFFFFF;
}
</style>


	<?php 
function blogAdvMenu($top_sub_folder) {
	global $setup;
	if(!empty($_REQUEST[''.items_id.''])) {
		$gal = doSQL("".items_table."", "*", "WHERE ".items_id."='".$_REQUEST[''.items_id.'']."' " );
		$_REQUEST['this_date_cat'] = $gal['date_cat'];
	}
	if(!empty($_REQUEST['this_date_cat'])) {
		$cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['this_date_cat']."' " );
		$f_ids = explode(",", $cat['cat_under_ids']);
		$top_sub_folder = $f_ids[0];
		if(empty($top_sub_folder)) {
			$top_sub_folder = $cat['cat_id'];
		}
	}
	if(is_array($f_ids)) {
		array_push($f_ids, $_REQUEST['this_date_cat']);
	}
	$menu_cats= whileSQL("".cat_table."", "*", "WHERE cat_under='0' ORDER BY cat_order,cat_name ASC");
	while($menu_cat = mysqli_fetch_array($menu_cats)) {
		$mpage_url = "index.php?do=".this_do."&".cat_field_url."=".$menu_cat['cat_id']."";
		?>
		<?php if($menu_cat['cat_type'] == "clientphotos") { 
			$new_content = "Create a new gallery in ".$menu_cat['cat_name']."";
			$new_message = "To create a new gallery";
		} elseif($menu_cat['cat_type'] == "proofing") { 
			$new_content = "Create a new proofing project in ".$menu_cat['cat_name']."";
			$new_message = "To create a proofing project";
		} elseif($menu_cat['cat_type'] == "store") { 
			$new_content = "Add a new product to ".$menu_cat['cat_name']."";
			$new_message = "To create a product";
		} elseif($menu_cat['cat_type'] == "registry") { 
			$new_content = "Add a registry in ".$menu_cat['cat_name']."";
			$new_message = "To create a new registry";
		} elseif($menu_cat['cat_type'] == "booking") { 
			$new_content = "Add a service to ".$menu_cat['cat_name']."";
			$new_message = "To create a new service booking";
		} else {
			$new_content = "Create new content in ".$menu_cat['cat_name']."";
			$new_message = "To create a new page ";
		}
		?>
		<?php  if(($_REQUEST['this_date_cat'] == $menu_cat['cat_id']) && ($_REQUEST['action'] !== "addDate") == true){ 
		$subcats = countIt("".cat_table."", "WHERE cat_under='".$menu_cat['cat_id']."' ");
		$totalpages = countIt("ms_calendar",  "WHERE date_cat='".$menu_cat['cat_id']."' ");
		if(($subcats + $totalpages)<= 0) { 
		?>
		<div style="position: relative; background:#5AB4E8; color: #FFFFFF; border: solid 1px #319CDD; padding:16px;  box-shadow: 0px 0px 8px rgba(0,0,0,.5);">
		<div style=" font-size: 15px;">
		<div class="p20 left">
			<div id="triangle"></div>
		</div>
		<div class="p80 left"><?php print ai_new_page;?> <?php print $new_message;?></div>
<!-- 		<div class="pc">edit: Edit settings for this section</div>
		<div class="pc">+ category: add a sub category</div>
		<div class="pc">+menu: add this to your website main menu</div>
		<div class="pc">web: view on website</div>
		<div class="pc">delete: delete this section</div>
		-->
		</div>

		<div class="clear"></div></div>
		<?php } 
		}
		?>


		<div class="<?php  if($_REQUEST['this_date_cat'] == $menu_cat['cat_id']) { ?>rowhoveron<?php } else { ?>rowhover<?php } ?> large" id="menu_cat-<?php print $menu_cat['cat_id'];?>">
		<div>

		<a href="index.php?do=news&action=addDate&date_cat=<?php print $menu_cat['cat_id'];?>" class="tip" title="<?php print $new_content;?>"><?php print ai_new_page;?></a> 
		<?php 	if(!empty($menu_cat['cat_password'])) { print ai_lock." "; }  ?>
		<?php  if($_REQUEST['this_date_cat'] == $menu_cat['cat_id']) { ?>
		<a href="<?php  print "$mpage_url"; ?>" ><?php  print "".$menu_cat['cat_name'].""; ?></a>
		<?php 
		} else { ?>
		<a href="<?php  print "$mpage_url"; ?>" ><?php  print "".$menu_cat['cat_name'].""; ?></a>
		<?php 
		}
		$tcat = countIt("".cat_connect_table."", "WHERE con_cat='".$menu_cat['cat_id']."' "); 
		$bstats = countCatBlogPosts($menu_cat['cat_id']);

			print " (".$bstats['posts'].")";
			$subcats = countIt("".cat_table."", "WHERE cat_under='".$menu_cat['cat_id']."' ");
			if($subcats > 0) {
				print " + ";
			}
			?>
	<?php if($menu_cat['cat_theme'] > 0) { print ai_theme." "; }   if($menu_cat['cat_billboard'] > 0) { print ai_billboard." "; }  ?>

		<?php 
			if($menu_cat['cat_status'] == "0") {
				print  "<span class=\"muted\"><i>Inactive</i></span> ";
			}

		?>

		</div>
	<div class="submenu" style="height: 16px;">
	<div class="hovermenu">
	<a href="index.php?do=<?php print this_do;?>&action=editCategory&cat_id=<?php print $menu_cat['cat_id'];?>" title="Edit section <?php print $menu_cat['cat_name'];?> " class="tip">edit</a>  &nbsp; 

	<a href="index.php?do=news&action=editCategory&cat_under=<?php print $menu_cat['cat_id'];?>" class="tip" title="Add new category to <?php print $menu_cat['cat_name'];?>">+ category</a> &nbsp; 
	<?php if(countIt("ms_menu_links", "WHERE link_cat='".$menu_cat['cat_id']."' ")<=0) { ?><a href="" onclick="openFrame('w-edit-link.php?link_cat=<?php print $menu_cat['cat_id'];?>&link_text=<?php print urlencode($menu_cat['cat_name']);?>&from_menu=1'); return false;" class="tip" title="Add To Website Menu">+ menu</a>	&nbsp; <?php } ?><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder'];?><?php print $menu_cat['cat_folder'];?>/" target="_blank"  class="tip" title="View on website">web</a>  &nbsp; 
	<?php 
		$ccats = countIt("".cat_table."", "WHERE cat_under='".$menu_cat['cat_id']."' ");
		if(($tcat + $ccats + countIt("ms_calendar", "WHERE date_cat='".$menu_cat['cat_id']."' ")) <=0) { ?> <a href="index.php?do=news&deleteBlogCat=<?php print $menu_cat['cat_id'];?>" onClick="return confirm('Are you sure you want to delete this category? Deleting this will permanently remove it and can not be reversed! ');"   title="Delete <?php print $menu_cat['cat_name'];?> " class="tip">delete</a><?php } ?>
		
				</div>
				</div>
		<div class="clear"></div>

		</div>
		<?php 
		if((!empty($top_sub_folder)) AND ($top_sub_folder == $menu_cat['cat_id'])  ==true) {
			$level++;
			blogAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level);
		unset($level);
			}
		}
	}



function blogAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level) {
	global $setup;

	$dashes = $level * 2;
	$sub_menu_cats = whileSQL("".cat_table."", "*", "WHERE cat_under='$top_sub_folder'  ORDER BY cat_order,cat_name ASC");
	while($sub_menu_cat = mysqli_fetch_array($sub_menu_cats)) {
		$mpage_url = "index.php?do=".this_do."&".cat_field_url."=".$sub_menu_cat['cat_id']."";
		?>
		<div class="<?php  if($_REQUEST['this_date_cat'] == $sub_menu_cat['cat_id']) { ?>rowhoveron<?php } else { ?>rowhover<?php } ?> large2" id="menu_cat-<?php print $sub_menu_cat['cat_id'];?>">
		<div>		
		<?php 
		while($dx < $dashes) { print "&nbsp;&nbsp;"; $dx++; }
		$dx = 0;
		$sp = 0;
		?>
		<a href="index.php?do=news&action=addDate&date_cat=<?php print $sub_menu_cat['cat_id'];?>" class="tip" title="Add new content to <?php print $sub_menu_cat['cat_name'];?>"><?php print ai_new_page;?></a>
		<?php if(!empty($sub_menu_cat['cat_password'])) { print ai_lock." "; }  ?>
		<?php  if($_REQUEST['this_date_cat'] == $sub_menu_cat['cat_id']) { ?>
			<a href="<?php  print "$mpage_url"; ?>" class=menu><?php  print "".$sub_menu_cat['cat_name'].""; ?></a>
			<?php 
		} else { ?>
			<a href="<?php  print "$mpage_url"; ?>" class=menu><?php  print "".$sub_menu_cat['cat_name'].""; ?></a>
	<?php 		}

		$tcat = countIt("".cat_connect_table."", "WHERE con_cat='".$sub_menu_cat['cat_id']."' "); 
		// print  " tcat: $tcat - prods: ".countIt("".items_table."", "WHERE ".items_cat_field."='".$sub_menu_cat['cat_id']."' ")."";
		$bstats = countCatBlogPosts($sub_menu_cat['cat_id']);

			print " (".$bstats['posts'].")";
			$subcats = countIt("".cat_table."", "WHERE cat_under='".$sub_menu_cat['cat_id']."' ");
			if($subcats > 0) {
				print " + ";
			}
			?>
	<?php if($sub_menu_cat['cat_theme'] > 0) { print ai_theme." "; }   if($sub_menu_cat['cat_billboard'] > 0) { print ai_billboard." "; }  ?>

		<?php 
			if($sub_menu_cat['cat_status'] == "0") {
				print " <span class=\"muted\"><i>Inactive</i></span> ";
			}

		?>

		</div>
	<div class="submenu" style="height: 16px;">
	<div class="hovermenu">
	<?php
		while($dx < $dashes) { print "&nbsp;&nbsp;"; $dx++; }
		$dx = 0;
		?>
	<a href="index.php?do=<?php print this_do;?>&action=editCategory&cat_id=<?php print $sub_menu_cat['cat_id'];?>" title="Edit category <?php print $sub_menu_cat['cat_name'];?> " class="tip">edit</a>  &nbsp; 

	<a href="index.php?do=news&action=editCategory&cat_under=<?php print $sub_menu_cat['cat_id'];?>" class="tip" title="Add newcategory to <?php print $sub_menu_cat['cat_name'];?>">+ category</a> &nbsp; 
	<a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder'];?><?php print $sub_menu_cat['cat_folder'];?>/" target="_blank" class="tip" title="View on website">web</a>  &nbsp; 
	<?php 
		$ccats = countIt("".cat_table."", "WHERE cat_under='".$sub_menu_cat['cat_id']."' ");
		if(($tcat + $ccats  + countIt("ms_calendar", "WHERE date_cat='".$sub_menu_cat['cat_id']."' ")) <=0) { ?><a href="index.php?do=news&deleteBlogCat=<?php print $sub_menu_cat['cat_id'];?>" onClick="return confirm('Are you sure you want to delete this category? Deleting this will permanently remove it and can not be reversed! ');"   title="Delete <?php print $sub_menu_cat['cat_name'];?> " class="tip">delete</a><?php } ?>
		</div>
		</div>
		<div class="clear"></div>

		</div>
		<?php 
		if ((in_array($sub_menu_cat['cat_id'], $f_ids))==true) { 
			$top_sub_folder = $sub_menu_cat['cat_id'];
			$level++;

			blogAdvSubMenu($menu_cat, $top_sub_folder, $f_ids, $dashes, $level);
		}
		//unset($level);
	}
}


?>
<?php if($tcats > 0) { ?>
<div class="pageContent"><?php print ai_billboard." =Using billboard <br>".ai_theme." = Using different theme"; ?></div>
<?php } ?>
