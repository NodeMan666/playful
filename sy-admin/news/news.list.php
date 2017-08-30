<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>

<?php

$starttime = microtime(true);


if($_REQUEST['action'] == "removeLink") {	
	$page = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	deleteSQL("ms_menu_links", "WHERE link_page='".$page['date_id']."' ", "1");
	$_SESSION['sm'] = "Link to page ".$page['date_title']." has been removed";
	session_write_close();
	header("location: index.php?do=news");
	exit();
}

if($_REQUEST['action'] == "pageOrder") {	
	$page = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	if($_REQUEST['moveTo'] == "up") {
		$rpage = doSQL("ms_calendar", "*", "WHERE page_order<'".$page['page_order']."' ORDER by page_order DESC ");
		updateSQL("ms_calendar", "page_order='".$rpage['page_order']."' WHERE date_id='".$page['date_id']."' ");
		updateSQL("ms_calendar", "page_order='".$page['page_order']."' WHERE date_id='".$rpage['date_id']."' ");
	}
	if($_REQUEST['moveTo'] == "down") {
		$rpage = doSQL("ms_calendar", "*", "WHERE page_order>'".$page['page_order']."' ORDER by page_order ASC ");
		updateSQL("ms_calendar", "page_order='".$rpage['page_order']."' WHERE date_id='".$page['date_id']."' ");
		updateSQL("ms_calendar", "page_order='".$page['page_order']."' WHERE date_id='".$rpage['date_id']."' ");
	}

	$_SESSION['sm'] = "Display order updated";
	session_write_close();
	header("location: index.php?do=news");
	exit();
}


if($_REQUEST['action'] == "pageOrderAlpha") { 


	$spages = whileSQL("ms_calendar", "*,date_format(last_modified, '".$site_setup['date_format']." ')  AS last_modified_show", "WHERE page_under='".$_REQUEST['topPage']."'  AND date_type='page' ORDER BY date_title ASC  ");
	while($spage = mysqli_fetch_array($spages)) {
		$o++;
		updateSQL("ms_calendar", "page_order='$o' WHERE date_id='".$spage['date_id']."' ");
	}

	$_SESSION['sm'] = "Display order updated";
	session_write_close();
	header("location: index.php?do=news");
	exit();

}

?>
<?php if($_REQUEST['new'] == "1") { ?>
<script>
 $(document).ready(function(){
		setTimeout(function() { 
		$("#newsectionmessage").css({"top":"60px"}).animate({"opacity":"1", "top":"-=60"},600, "easeOutBack");
		},500);
});
</script>

<?php } ?>
<div id="pageTitle" class="pc left"><a href="index.php?do=news">Site Content</a> 
<?php
if(!empty($_REQUEST['date_cat'])) { 
	if($_REQUEST['date_cat'] == "none") {
		$and_where = "AND date_cat='0' ";
		print " ".ai_sep." Top Level Pages";
	} else {
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['date_cat']."' ");
		$and_where = "AND  (ms_calendar.date_cat='".$_REQUEST['date_cat']."' OR ms_blog_cats_connect.con_cat='".$_REQUEST['date_cat']."' ) ";

		if(!empty($cat['cat_under_ids'])) { 
			$scats = explode(",",$cat['cat_under_ids']);
			foreach($scats AS $scat) { 
				$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
				print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
			}
		}

		print " ".ai_sep." ";
		if(!empty($cat['cat_password'])) { print ai_lock." "; } 
		print "".$cat['cat_name']." ";
		?>
		<a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>" class="tip" title="Edit Category"><span class="the-icons icon-pencil"></span></a> 
		<a href="<?php tempFolder();?><?php print $setup['content_folder']."".$cat['cat_folder'];?>/" target="_blank" class="tip" title="View On Website"><span class="the-icons icon-globe"></span></a>
		<?php 
	}
}
if(!empty($_REQUEST['blog_frame'])) { 
	$frame = doSQL("ms_blog_frames", "*", "WHERE frame_id='".$_REQUEST['blog_frame']."' ");
	$and_where = "AND blog_frame='".$frame['frame_id']."' ";
	print " ".ai_sep." Using <a href=\"index.php?do=".$_REQUEST['do']."&action=frames\">Frame</a> : <a href=\"index.php?do=".$_REQUEST['do']."&action=frames&viewFrame=".$frame['frame_id']."\">".$frame['frame_name']."</a>";
}
?>

<?php	if($_REQUEST['tag_id'] >0) { 
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$_REQUEST['tag_id']."' ");
	print " ".ai_sep." Tag ".ai_sep." ".ucfirst($tag['tag_tag'])." ";
	print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/tags/".$tag['tag_folder']."/\" target=\"_blank\" class=\"tip\" title=\"View on website\">".ai_web."</a> ";
	print "<a href=\"index.php?do=news&action=deleteTag&tag_id=".$tag['tag_id']."\"  class=\"tip\" title=\"Delete tag\" onClick=\"return confirm('Are you sure you want to delete this tag? Deleting this will permanently remove it and can not be reversed! ');\"  >".ai_delete."</a>";

?>
		<a href="javascript:editTag();"  class="tip" title="Edit tag"><?php print ai_edit;?></a>
		   <div style="foat:left; display:none;" id="edit_ex_tag" >
		   <div class="pageContent bold">Rename tag</div>
		   <div>
		   <form method="post" name="edittag" action="index.php">
			<input type="text" name="new_tag" size="30" id="new_tag" value="<?php print $tag['tag_tag'];?>">
			<input type="hidden" name="tag_id" id="tag_id" value="<?php print $tag['tag_id'];?>">
			<input type="hidden" name="do" value="news">
			<input type="hidden" name="action" value="editTag">
			<input type="submit" name="submit" class="submitSmall" value="save"> <a href="javascript:cancelEditTag();">cancel</a>
			</form>
			</div>
			</div>
<?php } ?>

<?php if($_REQUEST['date_photo_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['date_photo_price_list']."' ");
	print ai_sep." <a href=\"index.php?do=photoprods&view=list&list_id=".$list['list_id']."\">Price List:  ".$list['list_name']."</a>";
}
?>
</div>

<div>&nbsp;</div>
	<div id="photo-filter">
		<div class="pageContent">
		<form method="get" name="keys" action="index.php" id="photosearch">
		Sort By: 
		<input type="hidden" name="do" value="news">
		<input type="hidden" name="date_cat" value="<?php print $_REQUEST['date_cat'];?>">

		<select name="orderBy" id="orderBy" onchange="this.form.submit();">
		<option value="" disabled="disabled" style="font-style: italic;">Sort by</option>
		<option value="date" <?php if(($_REQUEST['orderBy'] == "date")  OR (empty($_REQUEST['orderBy']))==true){ print "selected"; } ?>>Date</option>
		<option value="date_title" <?php if($_REQUEST['orderBy'] == "date_title") {print "selected"; } ?>>Name</option>
		<option value="date_expire" <?php if($_REQUEST['orderBy'] == "date_expire") {print "selected"; } ?>>Expiration Date</option>
		<option value="last_modified" <?php if($_REQUEST['orderBy'] == "last_modified") {print "selected"; } ?>>Last Modified</option>
		</select>
		<select name="acdc" id="acdc" onchange="this.form.submit();">
		<option value="ASC" <?php if($_REQUEST['acdc'] == "ASC") {  print "selected"; } ?>>Ascending</option>
		<option value="DESC" <?php if(($_REQUEST['acdc'] == "DESC")  OR(empty($_REQUEST['acdc']))==true) { print "selected"; } ?>>Descending</option>
		</select>
		</form>
		</div>
		<div class="muted center"><a href="index.php?do=news&date_cat=<?php print $_REQUEST['date_cat'];?>&showme=expiringsoon">Show me pages expiring soon</a></div>
	</div>
<div class="cssClear"></div>
<div>&nbsp;</div>
<?php
$and_where .= "AND page_home='0' AND page_under='0' AND page_404='0' AND green_screen_gallery='0' ";

if($_REQUEST['date_photo_price_list'] > 0) { 
	$and_where .= "AND date_photo_price_list='".$_REQUEST['date_photo_price_list']."' ";
}

$time = strtotime("".$site_setup['time_diff']." hours");
$cur_time =date('Y-m-d H:i:s', $time);
$date_type = "news";
$this_type = "date_type='news' ";
if($cat['cat_order_by'] == "pageorder") { 
	$order_by = " page_order ASC";
} else { 
	$order_by = " date_date DESC,date_time DESC ";
}
if($_REQUEST['showme'] == "expiringsoon") { 
	$and_where .= "AND date_expire>='".date('Y-m-d')."' AND date_expire!='0000-00-00' ";
	$order_by = "date_expire ASC ";
}
if(!empty($_REQUEST['orderBy'])) {
	if($_REQUEST['orderBy'] == "date") { 
		$order_by = " date_date ".$_REQUEST['acdc'].",date_time ".$_REQUEST['acdc']." ";
	} else { 
		$order_by = " ".$_REQUEST['orderBy']." ".$_REQUEST['acdc']." ";
	}
}

$this_type = "date_id>'0' ";

$prereg = countIt("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "WHERE $this_type AND  date_public='3' $and_where  "); 

$drafts = countIt("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "WHERE $this_type AND  (date_public='0' OR date_public='2') $and_where  "); 
$publisheds = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime ", "WHERE $this_type AND  date_public='1' AND CONCAT(date_date, ' ', date_time)<='$cur_time' $and_where AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) "); 
$published = mysqli_num_rows($publisheds);
$pendings = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime ", "WHERE $this_type AND  date_public='1' AND CONCAT(date_date, ' ', date_time)>'$cur_time' $and_where "); 
$pending = mysqli_num_rows($pendings);

$expireds = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime ", "WHERE $this_type AND  date_public='1' AND date_expire!='0000-00-00' AND date_expire<'".date('Y-m-d')."'   $and_where "); 
$expired = mysqli_num_rows($expireds);

if(!empty($_REQUEST['view'])) { 
	$view = $_REQUEST['view'];
}

if((empty($_REQUEST['view']))) { 
	// $view = "published";
}


?>



<?php if(($date_type == "news")&&($tag['tag_id'] <=0)==true) { ?>
<?php 
	if(empty($view)) { 
		// print "<li>$and_where";
	$allpages = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime ", "WHERE date_id>'0' $and_where "); 
	$total = mysqli_num_rows($allpages);

}
?>
<div class="buttonsgray">
<ul>
<?php if($view == "drafts") { 
	$and_where .= "AND  (date_public='0' OR date_public='2')";
	$total = $drafts;
	?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=drafts<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>" class="on">DRAFTS <?php if($drafts > 0) { ?>(<span style="color: #890000; font-weight: bold;text-shadow: 1px 1px 0px #FFFFFF;"><?php print $drafts;?></span>)<?php } ?></a></li>
	<?php } else { ?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=drafts<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>">DRAFTS <?php if($drafts > 0) { ?>(<span style="color: #890000; font-weight: bold;text-shadow: 1px 1px 0px #FFFFFF;"><?php print $drafts;?></span>)<?php } ?></a></li>
<?php } ?>

<?php if($view == "published") { 
		$and_where .= "AND  date_public='1' AND CONCAT(date_date, ' ', date_time)<='$cur_time'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )  ";
		$total = $published;
	?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=published<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; }?>" class="on">PUBLISHED (<?php print $published;?>)</a></li>
<?php } else { ?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=published<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; }?>">PUBLISHED (<?php print $published;?>)</a></li>
<?php } ?>


<?php if($view == "pending") { 
		$and_where .= "AND  date_public='1' AND CONCAT(date_date, ' ', date_time)>'$cur_time'  ";
		$total = $pending;
	?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=pending<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>" class="on">PENDING (<?php print $pending;?>)</a></li>
<?php } else { ?>
		<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=pending<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>">PENDING (<?php print $pending;?>)</a></li>
<?php } ?>

<?php if($prereg > 0) { ?>
	<?php if($view == "prereg") { 
			$and_where .= "AND  date_public='3'   ";
			$total = $prereg;
		?>
			<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=prereg<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>" class="on">PRE-REGISTER <?php if($prereg > 0) { ?>(<span style="color: #890000;font-weight: bold; text-shadow: 1px 1px 0px #FFFFFF;"><?php print $prereg;?></span>)<?php } ?></a></li>
	<?php } else { ?>
			<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=prereg<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>">PRE-REGISTER <?php if($prereg > 0) { ?>(<span style="color: #890000;font-weight: bold; text-shadow: 1px 1px 0px #FFFFFF;"><?php print $prereg;?></span>)<?php } ?></a></li>
	<?php } ?>
<?php } ?>


<?php if($expired > 0) { ?>
	<?php if($view == "expired") { 
			$and_where .= "AND  date_public='1' AND  date_expire!='0000-00-00' AND date_expire<'".date('Y-m-d')."'  ";
			$total = $expired;
		?>
			<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=expired<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>" class="on">EXPIRED <?php if($expired > 0) { ?>(<span style="color: #890000;font-weight: bold; text-shadow: 1px 1px 0px #FFFFFF;"><?php print $expired;?></span>)<?php } ?></a></li>
	<?php } else { ?>
			<li><a href="index.php?do=<?php print $_REQUEST['do'];?>&view=expired<?php if(!empty($_REQUEST['date_cat'])) { print "&date_cat=".$_REQUEST['date_cat'].""; } ?><?php if(!empty($_REQUEST['blog_frame'])) { print "&blog_frame=".$_REQUEST['blog_frame'].""; } if(!empty($_REQUEST['date_photo_price_list'])) { print "&date_photo_price_list=".$_REQUEST['date_photo_price_list'].""; } ?>">EXPIRED <?php if($expired > 0) { ?>(<span style="color: #890000;font-weight: bold; text-shadow: 1px 1px 0px #FFFFFF;"><?php print $expired;?></span>)<?php } ?></a></li>
	<?php } ?>
<?php } ?>
	<?php if($cat['cat_type'] == "clientphotos") { 
		$new_page = "ADD NEW GALLERY";
	} elseif($cat['cat_type'] == "proofing") { 
		$new_page = "ADD NEW PROOFING PROJECT";
	} elseif($cat['cat_type'] == "store") { 
		$new_page = "ADD NEW PRODUCT";
	} elseif($cat['cat_type'] == "registry") { 
		$new_page = "CREATE NEW REGISTRY";
	} elseif($cat['cat_type'] == "booking") { 
		$new_page = "CREATE NEW SERVICE";
	} else {
		$new_page = "ADD NEW PAGE";
	}
	?>

	<li><?php print "<a  href=\"index.php?do=".$_REQUEST['do']."&action=addDate&date_cat=".$_REQUEST['date_cat']."\">";?><?php print ai_new_page;?><?php print $new_page;?></a></li>

	</ul>
	<div class="clear"></div>
</div>

<?php } ?>




<div>&nbsp;</div>
	<div id="">

	<?php
	/*	
		if(($date_type == "news")&&($_REQUEST['date_cat'] !== "none")&&($tag['tag_id'] <=0)==true) { 
		$cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under='".$_REQUEST['date_cat']."' ");
		if(mysqli_num_rows($cats) > 0) { ?>
		<div class="pageContent"><h2>Categories</h2></div>
		<?php  }

		while($cat = mysqli_fetch_array($cats)) { ?>
		<div id="roundedForm">
			<div class="row">
				<div style="width: 5%;" class="left">&nbsp;</div>
				<div style="width: 95%" class="left"><h3><a href="index.php?do=<?php print $_REQUEST['do'];?>&date_cat=<?php print $cat['cat_id'];?>"><?php print $cat['cat_name'];?></a></h3></div>
				<div class="clear"></div>
			</div>
		</div>
		<div>&nbsp;</div>
		<?php } 
		
		?>



	<?php }
	
	*/ ?>
	<?php if((countIt("ms_blog_categories", "")<=0) && ($_REQUEST['date_cat'] !== "none") == true) { ?>
<div style="background: #FFFFFF; border: solid 1px 3e4e4e4; box-shadow: 0px 0px 12px #949494; margin: 0 0 20px 0; padding: 20px;  border-radius: 4px; font-family: arial; font-size: 21px;">
	<p><h2>No Sections Created</h2></p>
	<p>
		Sections are basically top level categories which you create content in. The section determines which features and functions are available for the content in those sections.
		</p>
<p>
		Examples of sections are "Client Galleries", "Blog", Store", "Photo Albums", "Project Proofing", etc... Once you create a section you can add content to it.  
		</p>
		<p>
		<a href="index.php?do=news&action=editCategory">Click here to create a new section</a>.
		</p>
		<p>You can also create top level pages that don't need to be in a section by clicking <?php print ai_new_page;?> next to Top Level Pages in the left menu. Those would be pages like Contact Us & About Us, pages that don't need to be categorized.
	</div>
	<div>&nbsp;</div>

	<?php } ?>
	
		<?php

if((countIt("ms_calendar", "")<=3) &&  ($_REQUEST['date_cat'] == "none") == true){ ?>
<div style="background: #FFFFFF; border: solid 1px 3e4e4e4; box-shadow: 0px 0px 12px #949494; margin: 0 0 20px 0; padding: 20px;  border-radius: 4px; font-family: arial;">
	<p><h2>No Top Level Pages Created</h2></p>
	<p>
		Top level pages are simple pages like Contact Us, About Us, etc... pages that don't need to be categories. To create something like galleries, you need to create that within a section.
	</p>
	<p>
		<a href="index.php?do=news&action=addDate&date_cat=">Click here to create a new page now</a>.
</p>
	</div>
	<?php } ?>
	

	

	<?php if(countIt("ms_calendar", "WHERE $this_type ")<=0) { ?>

	<?php } else { ?>
	<?php if($date_type !="page") { ?>
	<?php if($view == "drafts") { ?>
	<div class="pageContent">The following have not been published on your website</div>
	<?php } ?>

	<?php if($view == "published") { ?>
	<div class="pageContent">The following are published on your website</div>
	<?php } ?>

	<?php if($view == "pending") { ?>
	<div class="pageContent">The following will be published on your website on the dates entered.</div>
	<?php } ?>
<?php } ?>
	<?php } ?>
<?php if($total > 1) { ?>
<div class="pc left"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull;  <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Selected Pages</a></div>
<?php } ?>

	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	if($_REQUEST['per_page'] > 0) { 
		$per_page = $_REQUEST['per_page'];
		updateSQL("ms_history", "per_page='".$_REQUEST['per_page']."' ");
		$history['per_page'] = $per_page;
	} else { 
		$per_page = $history['per_page'];
	}

	$NPvars = array("do=".$_REQUEST['do']."", "view=".$view."", "date_cat=".$_REQUEST['date_cat']."", "orderBy=".$_REQUEST['orderBy']."","acdc=".$_REQUEST['acdc']."");
	$sq_page = $pg * $per_page - $per_page;	

?>
<?php 
	$show_text = false;
	if($total > $per_page) {
		print "<div class=\"right textright\">".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</div>"; 
	}
	$show_text = true;
?>
<div class="clear"></div>
<?php if($cat['cat_order_by'] == "pageorder") { ?>
<div id="message-box" class="pageContent"><?php echo $message; ?></div>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list','sort_order','orderPages');
	});
	</script>
	<form id="dd-form" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderPages">
	<input type="hidden" name="link_location" value="topmain">
	<?php
	unset($order);
	$entries = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified_show , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  $this_type  $and_where  AND page_under='0' GROUP BY date_id ORDER BY $order_by LIMIT $sq_page,$per_page");

	while($entry = mysqli_fetch_array($entries)) {		
		$order[] = $entry['date_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>
<?php } ?>






<?php 
if($_REQUEST['action'] == "search") { 
	include "search.php";
} else { 

	if(($total <= 0)&&($tag['tag_id'] <=0)&&($_REQUEST['action'] !== "search") && (is_numeric($_REQUEST['date_cat'])) && ($_REQUEST['date_cat'] !== "0")==true) { ?>
	<div style="height: 800px; position: relative; ">
	<div id="newsectionmessage" style="position: absolute; width: 100%; text-align: center; <?php if($_REQUEST['new'] == "1") { ?>opacity: 0;<?php } ?>">
	<div style="font-size: 40px;" class="pc center" >Success! Your new <?php print $cat['cat_name'];?> section has been created.</div>


		<?php if($cat['cat_type'] == "clientphotos") { 
			$new_content = "To create a new gallery, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top. <br><br>To create and manage photo products like prints & downloads, go to the Photo Products section in the main menu at the top of the screen.";
		} elseif($cat['cat_type'] == "proofing") { 
			$new_content = "To create a new proofing project, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top.";
		} elseif($cat['cat_type'] == "store") { 
			$new_content = "To create a new product, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top.";
		} elseif($cat['cat_type'] == "registry") { 
			$new_content = "To create a new registry for a client, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top.";
		} elseif($cat['cat_type'] == "booking") { 
			$new_content = "To create a new session or service to be booked online, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top.";
		} else {
			$new_content = "To create a new page or gallery, you can either click the ".ai_new_page." icon to the left, or ".$new_page." at the top";
		}
		?>

	<div style="font-size: 21px;" class="pc center"><?php print $new_content;?></div>
	<!-- <div class="pageContent"><h3>Nothing found in this section.</h3></div> -->
	</div>
	</div>
	<?php  } else { ?>

	<?php 
		if($tag['tag_id'] > 0) { 
			$entries = whileSQL("ms_calendar LEFT JOIN ms_tag_connect ON ms_calendar.date_id=ms_tag_connect.tag_date_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show", "WHERE date_id>'0' AND  ms_tag_connect.tag_tag_id='".$tag['tag_id']."'   ORDER BY date_date DESC, date_time DESC  LIMIT $sq_page,$per_page");

	} else { 
		$entries = whileSQL("ms_calendar LEFT JOIN ms_blog_cats_connect ON ms_calendar.date_id=ms_blog_cats_connect.con_prod  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified_show , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE  $this_type  $and_where  AND page_under='0' GROUP BY date_id ORDER BY $order_by LIMIT $sq_page,$per_page");
	}
	?>
	<ul id="sortable-list" class="sortable-list">
	<?php 
	while($entry = mysqli_fetch_array($entries)) {
		listEntry($entry);
	}
	?>
</ul>
<?php } ?>

</form>
<?php if($total > 1) { ?>
<div class="pc"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull;  <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Selected Pages</a></div>
</div>
<?php } ?>
</div>

</div><div class="clear"></div>


<script>
function batcheditpages() {
	var pageids = 0;
	$(".batchselect").each(function(i){
		if($(this).attr("checked")) {
			pageids += "|"+$(this).attr("id");
		//	alert($(this).attr("id"));
		}
	});
	pagewindowedit("news/news-batch-edit.php?noclose=1&nofonts=1&nojs=1&date_cat=<?php print $_REQUEST['date_cat'];?>&pageids="+pageids);
	// alert(pageids);
}

function selectallpages() { 
	$(".batchselect").attr("checked",true);
	hlitems();
}
function deselectallpages() { 
	$(".batchselect").attr("checked",false);
	hlitems();
}


</script>

<?php 
	if($total > $per_page) {
		print "<center>".nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $_REQUEST)."</center>"; 
		}
?>
<?php } ?>
<div>&nbsp;</div>
<?php 
foreach($_GET AS $gn => $gr) { 
	if($gn !== "per_page") { 
		$strv .= "&".$gn."=".$gr;	
	}
}
if($total > $per_page) {
?>
<div class="pc center">
	Show per page: <?php if($history['per_page'] !== "20") { print "<a href=\"index.php?per_page=20".$strv."\" class=\"np\">20</a>"; } else { print "<span class=\"np\">20</span>"; } ?>
	<?php if($history['per_page'] !== "50") { print "<a href=\"index.php?per_page=50".$strv."\" class=\"np\">50</a>"; } else { print "<span class=\"np\">50</span>"; } ?>
	<?php if($history['per_page'] !== "100") { print "<a href=\"index.php?per_page=100".$strv."\" class=\"np\">100</a>"; } else { print "<span class=\"np\">100</span>"; } ?>
</div>
<?php } ?>

<script>
	$(document).ready(function(){
		hlitems();

		$(".batchselect").change(function () { 
			if($(this).attr("checked")) { 
				$("#page-"+$(this).val()).removeClass("underline").addClass("underlinehl");
			} else { 
				$("#page-"+$(this).val()).removeClass("underlinehl").addClass("underline");
			}
		});
	});
		

	function hlitems() { 
		$(".batchselect").each(function() {
			if($(this).attr("checked")) { 
				$("#page-"+$(this).val()).removeClass("underline").addClass("underlinehl");
			} else { 
				$("#page-"+$(this).val()).removeClass("underlinehl").addClass("underline");
			}
		});
}
</script>


<div class="clear"></div>

<?php 

function listEntry($entry) { 
	global $site_setup,$setup, $date_type,$photo_setup,$dbcon;
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$entry['date_cat']."' ");
	$cat_folder = $dcat['cat_folder'];
	?>
	<li title="<?php print $entry['date_id'];?>">
	<div  id="dateid-<?php print $entry['date_id'];?>" name="dateid-<?php print $entry['date_id'];?>">
	<div>
		<div class="underline"  id="page-<?php print $entry['date_id'];?>">
		<div style="width: 2%; float: left;"><?php if(($dcat['cat_order_by'] == "pageorder")&&(!empty($_REQUEST['date_cat']))==true) { ?><span class="the-icons icon-sort" title="Srag & Drop To Sort"></span><?php } else { print "&nbsp;"; }  ?>

		<div><input type="checkbox" name="date_id" id="<?php print $entry['date_id'];?>" value="<?php print $entry['date_id'];?>" class="batchselect inputtip" title="Select to batch edit"></div>

		</div>
			<div style="width: 10%; float: left">
			
			<div class="dpmenucontainer left hidesmall">
				<div class="dpmenu bold">	Actions
					<div class="dpinner">
						<div class="pc"><a href="index.php?do=news&action=addDate&date_id=<?php print $entry['date_id'];?>">Edit</a></div>
						<div class="pc">
						<?php 
						if(($entry['page_404']!=="1")&&(countIt("ms_calendar", "WHERE page_under='".$entry['date_id']."'  ")<=0)==true)   { 
							?>
						<a href=""  class="confirmdeleteoptions" confirm-title="Delete Page: <?php print htmlspecialchars($entry['date_title']);?>" confirm-message="Select from the options below" option-link-1="index.php?do=news&deleteDate=<?php print $entry['date_id'];?>&view=<?php print $_REQUEST['view'];?>&date_cat=<?php print $entry['date_cat'];?>"  option-link-1-text="Delete page and leave photos in the system"  option-link-2="index.php?do=news&deleteDate=<?php print $entry['date_id'];?>&deletephotos=1&view=<?php print $_REQUEST['view'];?>&date_cat=<?php print $entry['date_cat'];?>" option-link-2-text="Delete page and delete all photos assigned to the page from the system">Delete</a>
							<?php 
						}
						?>
						</div>
						<div class="pc"><a  href="<?php tempFolder(); ?><?php print $setup['content_folder']."".$dcat['cat_folder']."/".$entry['date_link'];?>/" target="_blank" title="View On Website">View On Website</a></div>
						<div class="pc"><a href="index.php?do=news&action=managePhotos&date_id=<?php print $entry['date_id'];?>">Manage Photos</a></div>
						<div class="pc"><a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $entry['date_id'];?>'); return false;">Upload Photos</a></div>
						<div class="pc"><a href="" onclick="pagewindowedit('w-photo-display-settings.php?date_id=<?php print $entry['date_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;">Photo Display Settings</a></div>
						<div class="pc"><a href="index.php?do=news&action=splash&date_id=<?php print $entry['date_id'];?>">Splash Window</a></div>
						<div class="pc"><?php if($entry['cat_type'] !== "proofing") { 	?><a href="" onclick="newsstats('<?php print $entry['date_id'];?>'); return false;">Stats</a><?php } ?> </div>
						<div class="pc"><a href="index.php?do=news&action=thumbPreview&date_id=<?php print $entry['date_id'];?>">Preview Photo</a></div>
						<div class="pc"><a href="" onclick="duplicatepage('<?php print $entry['date_id'];?>'); return false;">Duplicate This Page</a></div>
						<?php if($dcat['cat_type'] == "registry") { ?>
						<div class="pc"><a href="" onclick="sendregistry('<?php print $entry['date_id'];?>'); return false;">Email Registry Information</a></div>
						<?php } ?>
						<?php if($dcat['cat_type'] == "clientphotos") { 
							$eb = doSQL("ms_promo_codes", "*, date_format(DATE_ADD(code_end_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS exp_show", "WHERE code_date_id='".$entry['date_id']."' ");
							?>
						<div class="pc"><a href="" onclick="editcoupon('<?php print $eb['code_id'];?>','<?php print $entry['date_id'];?>'); return false;">Early Bird Special</a></div>
						<?php } ?>

					</div>
				</div>
			</div>

	</div>
	<div style="width: 38%; float: left;">
	<?php 
	if(!empty($entry['date_photo_keywords'])) { 
		$bpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_blog_preview,ms_blog_photos.bp_sub_preview,ms_blog_photos.bp_order,ms_photos.pic_id", "WHERE bp_blog_preview='".$entry['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($bpic['pic_id'])) {
			$pic = doSQL("ms_photos","*","WHERE pic_id='".$bpic['pic_id']."' ");
		} else { 
			$and_date_tag = "( ";
			$date_tags = explode(",",$entry['date_photo_keywords']);
			foreach($date_tags AS $tag) { 
				$cx++;
				if($cx > 1) { 
					$and_date_tag .= " OR ";
				}
				$and_date_tag .=" key_key_id='$tag' ";
			}
			$and_date_tag .= " OR bp_blog='".$entry['date_id']."' ";
			$and_date_tag .= " ) ";

			$pics_where = "WHERE $and_date_tag";
			$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
			$pics_orderby = $entry['date_photos_keys_orderby']; 
			$pics_acdc = $entry['date_photos_keys_acdc'];
			$pic_file = $entry['blog_photo_file'];
			$pic = doSQL("$pics_tables", "*", "$pics_where ORDER BY  $pics_orderby $pics_acdc LIMIT  1 ");
		}
	} else { 
		$pics = countIt("ms_blog_photos",  "WHERE bp_blog='".$entry['date_id']."' LIMIT  1 ");

		if($pics > 0) { 
			$bpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_blog_preview,ms_blog_photos.bp_sub_preview,ms_blog_photos.bp_order,ms_photos.pic_id", "WHERE bp_blog_preview='".$entry['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($bpic['pic_id'])) {
				$pic = doSQL("ms_photos","*","WHERE pic_id='".$bpic['pic_id']."' ");
			} else {
				$bpic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_blog,ms_blog_photos.bp_order,ms_photos.pic_id", "WHERE bp_blog='".$entry['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
				if($bpic['pic_id'] > 0) { 
					$pic = doSQL("ms_photos","*","WHERE pic_id='".$bpic['pic_id']."' ");
				}
			}
		}
	}
	if(!empty($pic['pic_id'])) {
		print "<a href=\"index.php?do=".$_REQUEST['do']."&action=addDate&date_id=".$entry['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" style=\"float: left; margin: 0 16px 0 0; width: ".$photo_setup['mini_size']."px; height: ".$photo_setup['mini_size']."px;\" class=\"img\"></a>"; 
	}


	print "<div>";
	if(($entry['date_expire'] !='0000-00-00') &&($entry['date_expire'] < date('Y-m-d'))==true) { ?><span class="expired">Expired</span><?php } 
	if($entry['date_public'] == "2") { ?><span class="draft">Draft</span><?php } 
	if($entry['date_public'] == "3") { ?><span class="prereg">Pre-Register</span><?php } 

	print "<h3 style='display: inline;'>"; if($entry['private'] > 0) { ?><span class="the-icons icon-lock"></span><?php } print "<a href=\"index.php?do=".$_REQUEST['do']."&action=addDate&date_id=".$entry['date_id']."\" title=\"View / Edit  Entry  ".$entry['date_title']."\">";
	if(empty($entry['date_title'])) { print "<b>[no title]</b>";} else { print "".$entry['date_title'].""; }
	print "</a>";
	print "</h3></div>";
		print "<div class=\"muted\">";

	if($entry['page_404']== "1") { print " [Custom 404 - page not found error page]"; } 
	if($entry['page_billboard']>0) { print ai_billboard." ";} 
	if($entry['page_theme']>0) { print ai_theme." ";} 

	// print "Last modified: ".$entry['last_modified'];
	print "</div>";
	print "<div class=\"muted\">";
	if($setup['do_no_list_views'] !== true) { 
		print  number_format(countIt("ms_stats_site_pv", "WHERE pv_date BETWEEN CURDATE() - INTERVAL 30 DAY AND (CURDATE()) AND date_id='".$entry['date_id']."' "))." views the last 30 days ";
	}
	?>
	<br>
	<?php $shares = countIt("ms_shares", "WHERE share_page='".$entry['date_id']."' ");
	if($shares > 0) { ?>
	<a href="index.php?do=stats&view=shares&share_page=<?php print $entry['date_id'];?>"><?php print $shares;?> <?php if($shares == "1") { print "share"; } else { print "shares"; } ?></a><br>
	<?php } ?>
	<?php 
	$sold = doSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*,SUM(cart_qty) AS total", "WHERE cart_store_product='".$entry['date_id']."' AND order_payment_status='Completed' AND order_status!='2' GROUP BY cart_store_product");	
	$soldarchived = doSQL("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id", "*,SUM(cart_qty) AS total", "WHERE cart_store_product='".$entry['date_id']."' AND order_payment_status='Completed' AND order_status!='2' GROUP BY cart_store_product");

	$total = $sold['total'] + $soldarchived['total'];
	if($total > 0) { print round($total)." sold "; } 
	$regs = doSQL("ms_credits", "*,SUM(credit_amount) AS total", "WHERE credit_reg='".$entry['date_id']."' GROUP BY credit_reg ");
	if($regs['total'] > 0) { print "<h3><a href=\"index.php?do=people&p_id=".$entry['reg_person']."&view=credits\">".showPrice($regs['total'])."</a></h3>"; } 

	?>
	<?php 
	//if($entry['cat_type'] == "clientphotos") { 
	$emails = array(); 

	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$entry['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	while($p = mysqli_fetch_array($ps)) { 
		if(!in_array($p['p_email'],$emails)) { 
			array_push($emails,$p['p_email']);
		}
	}
	$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$entry['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	while($p = mysqli_fetch_array($ps)) {
		if(!in_array($p['p_email'],$emails)) { 
			array_push($emails,$p['p_email']);
		}
	}


	// $ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$entry['date_id']."' OR cart_store_product='".$entry['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");

		$ps = mysqli_query($dbcon,"
		SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
		SELECT *  FROM ms_cart 
		 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$entry['date_id']."' OR cart_store_product='".$entry['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

	  UNION ALL

		SELECT *  FROM ms_cart_archive 
		 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$entry['date_id']."' OR cart_store_product='".$entry['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
		) 
		x 
		GROUP BY order_email ORDER BY order_last_name ASC
		");

		if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }

		while($p = mysqli_fetch_array($ps)) {
			if(!empty($p['order_email'])) { 
				if(!in_array($p['order_email'],$emails)) { 
					array_push($emails,$p['order_email']);
				}
			}
		}				
			if($entry['private'] > 0) {
				$emid = doSQL("ms_emails", "*", "WHERE email_id_name='inviteprivate' ");
				$em = $emid['email_id'];
			} else { 
				$emid = doSQL("ms_emails", "*", "WHERE email_id_name='invitepublic' ");
				$em = $emid['email_id'];
			}

	$ps = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$entry['date_id']."'  AND toview<='0'  ORDER BY reg_id DESC");
	while($p = mysqli_fetch_array($ps)) {
		if(!in_array($p['reg_email'],$emails)) { 
			array_push($emails,$p['reg_email']);
		}
	}

	$eo = whileSQL("ms_pre_register ", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$entry['date_id']."'  AND toview='1'  ORDER BY reg_id DESC");
	while($e = mysqli_fetch_array($eo)) {
		if(!in_array($e['reg_email'],$emails)) { 
			array_push($emails,$e['reg_email']);
		}
	}

	if((count($emails) > 0)||($entry['cat_type'] == "clientphotos")== true) { 

	?>
	&nbsp; <a href="" onclick="newsusers('<?php print $entry['date_id'];?>','<?php print $em;?>'); return false;" class="tip" title="View / Manage People">people(<?php print count($emails);?>)</a> <a href="" onclick="newsusers('<?php print $entry['date_id'];?>','<?php print $em;?>'); return false;"><span class="the-icons icon-mail"></span></a>
	<?php } ?>
	<?php // } ?>


	<?php 
	if($entry['cat_type'] == "proofing") { 

	$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$entry['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
	while($p = mysqli_fetch_array($ps)) { 
		$tp++;
		if($tp > 1) { print ", "; } 
		print "<a href=\"index.php?do=people&p_id=".$p['p_id']."\">".$p['p_name']." ".$p['p_last_name']."</a>";
	}
	?>
	<?php } ?>

	<?php 
		print "</div>";
	if((countIt("ms_menu_links", "WHERE link_page='".$entry['date_id']."' ")<=0) &&($entry['date_cat'] <=0)==true){ ?><div class="muted"><a href="" onclick="pagewindowedit('w-edit-link.php?link_page=<?php print $entry['date_id'];?>&link_text=<?php print urlencode($entry['date_title']);?>'); return false;" class="tip" title="Add To Website Menu">+ menu</a>	&nbsp; </div><?php } ?>
	<?php 


	?>
	</div>
	<div style="width: 15%; float: left">
	<?php 
		if($entry['page_404']!=="1") { 

	$cx = 0;
	if($setup['do_no_list_total_photos'] !== true) { 

		if(!empty($entry['date_photo_keywords'])) { 
			$and_date_tag = "( ";
			$date_tags = explode(",",$entry['date_photo_keywords']);
			foreach($date_tags AS $tag) { 
				$cx++;
				if($cx > 1) { 
					$and_date_tag .= " OR ";
				}
				$and_date_tag .=" key_key_id='$tag' ";
			}
			$and_date_tag .= " OR bp_blog='".$entry['date_id']."' ";
			$and_date_tag .= " ) ";

			$pics_where = "WHERE $and_date_tag";
			$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
			$pics_orderby = $entry['date_photos_keys_orderby']; 
			$pics_acdc = $entry['date_photos_keys_acdc'];
			$pic_file = $entry['blog_photo_file'];
			$piccount = whileSQL("$pics_tables", "*", "$pics_where  AND pic_no_dis<='0' GROUP BY pic_id  ");
			$total_images = mysqli_num_rows($piccount);
		} else { 

			$total_images = countIt("ms_blog_photos", "WHERE bp_blog='".$entry['date_id']."' ");
		}
			if($total_images > 0) { 
				print "<h3>";
				print "<a href=\"index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$entry['date_id']."\">".$total_images." Photos</a>";
				print "</h3>";
				$subs = countIt("ms_sub_galleries", "WHERE sub_date_id='".$entry['date_id']."' ");
				if($subs > 0) { 
					print "<a href=\"index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$entry['date_id']."\">in $subs sub galleries</a>";
				}
			} else { 
				print "&nbsp;";
			}
	} else { 
		print "&nbsp;";
	}
		if($entry['cat_type'] == "proofing") { 
			$pics_where = "WHERE bp_blog='".$entry['date_id']."' $and_sub ";
			$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id ";
			$picsd = whileSQL("$pics_tables", "*", "$pics_where $and_where GROUP BY pic_id   ");
			$total_images = mysqli_num_rows($picsd);
			while($picd = mysqli_fetch_array($picsd)) { 
				if(countIt("ms_proofing",  "WHERE proof_date_id='".$entry['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='1' ")> 0) { 
					$total_done++;	
				}
				if(countIt("ms_proofing",  "WHERE proof_date_id='".$entry['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='2' ")> 0) { 
					$total_rev++;	
				}
				if(countIt("ms_proofing",  "WHERE proof_date_id='".$entry['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='3' ")> 0) { 
					$total_reject++;	
				}

			}
			if($total_done > 0) { 
				print $total_done." Approved ";
			} 
			if($total_rev > 0) { 
				print ", ".$total_rev." Revisions Requested";
			}
			if($total_reject > 0) { 
				print ", ".$total_reject." Rejected";
			}
		}


	} else { 
		print "&nbsp;";
	}
	?>

	</div>


	<div style="width: 20%; float: left">
	<?php
		if($date_type == "news") { 
		?>
			<div class="pageContent bold">
			<?php 
			if($entry['date_cat'] > 0) { 
			$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$entry['date_cat']."' ");
			if(!empty($cat['cat_under_ids'])) { 
				$scats = explode(",",$cat['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print " <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\" class=\"bold\">".$tcat['cat_name']."</a> > ";
				}
			}


			print "<a href=\"index.php?do=".$_REQUEST['do']."&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
		} else {
			print "&nbsp;";
		}
		?>

		<?php $scats = whileSQL("ms_blog_cats_connect LEFT JOIN ms_blog_categories ON ms_blog_cats_connect.con_cat=ms_blog_categories.cat_id", "*", "WHERE con_prod='".$entry['date_id']."' "); 
		while($scat = mysqli_fetch_array($scats)) { ?>
		<div>
		<?php	if(!empty($scat['cat_under_ids'])) { 
				$sscats = explode(",",$scat['cat_under_ids']);
				foreach($sscats AS $sscat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$sscat."' ");
					print " <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\" >".$tcat['cat_name']."</a> > ";
				}
			}
			print "<a href=\"index.php?do=".$_REQUEST['do']."&date_cat=".$scat['cat_id']."\" >".$scat['cat_name']."</a>";
			?>
		</div>
		<?php } ?>
		</div>



		<?php if(countIt("ms_tag_connect",  "WHERE tag_date_id='".$entry['date_id']."' ")>0) { ?>
		<div class="pageContent">Tags: 
		<?php $tags = whileSQL("ms_tag_connect LEFT JOIN ms_tags ON ms_tag_connect.tag_tag_id=ms_tags.tag_id", "*", "WHERE tag_date_id='".$entry['date_id']."' ORDER BY ms_tags.tag_tag ASC ");
		while($tag = mysqli_fetch_array($tags)) { 
			if($tn > 0) { print ", "; } 
			print "<a href=\"index.php?do=".$_REQUEST['do']."&tag_id=".$tag['tag_id']."\">".$tag['tag_tag']."</a>";
			$tn++;
		}
		?>

		</div>
		<?php } 


	}
	?>
	</div>

	<div style="width: 15%;" class="left textright">
	<?php 
		if($entry['cat_type'] == "proofing") { 
			$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$entry['date_id']."' ORDER BY id DESC");
			if((empty($cks['status'])) || ($cks['status']== 0)==true) { 
				print "<div class=\"blue center\">Pending review</div>";
			}
			if($cks['status']== "1") { 
				print "<div class=\"yellowmessage center\">Awaiting your action</div>";
			}
			if($cks['status']== "2") { 
				print "<div class=\"greymessage center\">Closed</div>";
			}
		}
	?>

	<?php if($entry['date_expire'] !== "0000-00-00") { ?>
	<div class="pc">Expires: <?php print $entry['date_expire_show'];?></div>
	<?php } ?>
	<?php
	if($entry['cat_type'] !== "proofing") { 
		if($entry['private'] > 0) { ?>
	<div class="pc"><?php if($entry['date_paid_access'] == "1") { ?><span class="tip" title="Paid access <?php print showPrice($entry['date_credit']);?> credit"><?php print showPrice($entry['prod_price']);?></span><?php } ?> 
	<span class="the-icons icon-lock"></span> <?php print $entry['password'];?></div>
	
	<?php } 
	}
	?>
	<?php if($entry['passcode_photos'] == "1") { ?>
	<?php print ai_lock;?> Passcode Photos
	<?php } ?>

	<?php 
	if((!empty($entry['prod_type']))&&($entry['prod_price'] > 0)==true) { 
		$price = productPrice($entry);
		print "<h2>";
		if($price['onsale'] == true) { 
			print "<span class=\"muted\"><s>".showPrice($price['org'])."</s></span> &nbsp;";
		}
		print " ".showPrice($price['price'])."</h2>";	
		if($price['onsale'] == true) { 
			print "Sale through ".$entry['prod_sale_end'];
		}
		if($entry['date_credit'] > 0) { ?>
		<div>(<?php print showPrice($entry['date_credit']);?> credit)</div>
		<?php } ?>
	<?php } 
	 
	
	 ?>


	 <?php 
		$subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$entry['date_id']."' ");
		if(mysqli_num_rows($subs) > 0) {
			?>			<table class="right" onClick="window.location='index.php?do=news&action=subProds&date_id=<?php print $entry['date_id'];?>'" style="cursor: pointer;">
			<?php
			while($sub = mysqli_fetch_array($subs)) { ?>
				<tr>
		<td class="pc"><?php print $sub['sub_qty'];?></td>
		<?php if(!empty($entry['prod_opt1'])) { ?>
			<td class="pc"><?php print $sub['opt1_value'];?></td>
		<?php } ?>
		<?php if(!empty($entry['prod_opt2'])) { ?>
			<td class="pc"><?php print $sub['opt2_value'];?></td>
		<?php } ?>
		<?php if(!empty($entry['prod_opt3'])) { ?>
			<td class="pc"><?php print $sub['opt3_value'];?></td>
		<?php } ?>
		<?php if(!empty($entry['prod_opt4'])) { ?>
			<td class="pc"><?php print $sub['opt4_value'];?></td>
		<?php } ?>
		<?php if(!empty($entry['prod_opt5'])) { ?>
			<td class="pc"><?php print $sub['opt5_value'];?></td>
		<?php } ?>
		</tr>
		<div class="clear"></div>
		</div>

<?php } ?>
	</table>

	 <?php } else { ?>
		<?php if(($entry['prod_inventory_control'] == "1")&&($entry['prod_type'] == "ship")&&($cat['cat_type'] == "store")==true) { ?>
		qty: <?php print $entry['prod_qty'];?>
		<?php } ?>
	 <?php } ?>
	</div>
	<div class="cssClear"></div>
	</div>

<?php
	$spages = whileSQL("ms_calendar", "*,date_format(last_modified, '".$site_setup['date_format']." ')  AS last_modified_show", "WHERE page_under='".$entry['date_id']."'  ORDER BY page_order ASC  ");
	$totalsub = mysqli_num_rows($spages);
	if($totalsub > 1) { 
		print "<div class=\"pageContent\"><a href=\"index.php?do=news&action=pageOrderAlpha&topPage=".$entry['date_id']."\">Order sub pages alphabetically</a></div>";
	}
	while ($spage = mysqli_fetch_array($spages)) {
		$thisPage++;
		?>
		<div style="margin-left: 5%;" class="row">
		<div style="width: 10%; float: left;">



		<?php 
			print "<a href=\"index.php?do=".$_REQUEST['do']."&deleteDate=".$spage['date_id']."\" onClick=\"return confirm('Are you sure you want to delete this page? Deleting this will permanently remove it and can not be reversed! ');\"  title=\"Delete\">".ai_page_delete."</a> ";
			print "<a href=\"".$setup['content_folder']."".$dcat['cat_folder']."/".$spage['date_link']."/\" target=\"_blank\" title=\"View on website\">".ai_page_view."</a> ";
			if($thisPage > 1) {
				print "<a href=\"index.php?do=news&action=pageOrder&date_id=".$spage['date_id']."&moveTo=up\" title=\"Move Up\">".ai_page_up."</a> ";
			}
			if($thisPage!==$totalsub) {
				print "<a href=\"index.php?do=news&action=pageOrder&date_id=".$spage['date_id']."&moveTo=down\" title=\"Move Down\">".ai_page_down."</a> ";
			}
			?>
			</nobr>
			</div>
			<div style="float: left;">
			<?php 
			if(!empty($spage['date_thumb'])) { print ai_photo." ";} 
			if($spage['page_billboard']>0) { print ai_billboard." ";} 

			if($spage['date_public']!=="1") { print " <span class=\"inactive\">inactive</span> "; } 

			print "<a href=\"index.php?do=news&action=addDate&date_id=".$spage['date_id']."&pg=".$_REQUEST['pg']."\" title=\"Edit\">".$spage['date_title']."</a>";
			?>
			</div>
			<div class="cssClear"></div>
		</div>
<?php 	} ?> 

</div>
<div>&nbsp;</div>
</div>
<?php  } 



$endtime = microtime(true);
$duration = $endtime - $starttime;
?>
<!-- <div style="font-size: 11px; color: #999999; font-decoration: italic; padding: 2px; text-align: center;"><?php print number_format($duration,4);?> seconds</div> -->


<?php if($date_type == "page") { ?>
<div class="pageContent"><?php print ai_page_new." = Add sub page &nbsp;  ".ai_page_up ." ".ai_page_down." = change sub page order &nbsp; ".ai_billboard." = page using billboard &nbsp; ".ai_theme." = using different theme"; ?></div>
<?php } ?>
