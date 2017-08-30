
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Menu Links</div> 
<?php 
if($_REQUEST['subdo'] == "orderLinks") {	
	foreach($_REQUEST['link_order'] AS $id => $val) {
		updateSQL("ms_menu_links", "link_order='$val' WHERE link_id='$id'");
//		print "<li>$id = $val";
	}
	$_SESSION['sm'] = "Link display order updated";
	session_write_close();
	header("location: index.php?do=look&view=links");
	exit();

}


if($_REQUEST['action'] == "disableMiniLinks") {	
	updateSQL("ms_menu_links", "link_status='0' WHERE link_location='shop' ");
	$_SESSION['sm'] = "Links ".$link['link_text']." has been disabled";
	session_write_close();
	header("location: index.php?do=look&view=links");
	exit();
}

if($_REQUEST['subdo'] == "deleteLink") {	
	$link = doSQL("ms_menu_links", "*", "WHERE link_id='".$_REQUEST['link_id']."' ");
	deleteSQL("ms_menu_links", "WHERE link_id='".$link['link_id']."' ", "1");
	$_SESSION['sm'] = "Link ".$link['link_text']." has been deleted";
	session_write_close();
	header("location: index.php?do=look&view=links");
	exit();
}
if($_REQUEST['subdo'] == "addPage") {
	$formDisplay = "block";
	$formLinkDisplay = "none";
}
if($_REQUEST['action'] == "addPage") {
	$page = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['link_page']."' ");
	if(empty($_REQUEST['link_text'])) {
		$_REQUEST['link_text'] = $page['date_title'];
	}
	$formDisplay = "block";
	$formLinkDisplay = "none";
}


?>

<div class="buttons">
	<a href="" onclick="editlink('w-edit-link.php'); return false;">Add New Link</a>
	<div class="cssClear"></div>
</div>
<div id="Contain">


<div id="addNewLink" style="display: <?php print $formDisplay;?>;">

<div id="message-box" class="pageContent"><?php echo $message; ?></div>

<?php /* SORTING GROUP 1 */ ?>
<div class="left" style="width: 25%;">
These are the links in the menus of your site. There are 3 different sections: Main Menu, Top Mini Menu, Side Bar Menu. 
<br><br>
<h3>Main Menu</h3>
The main menu goes across the top of the page .
<br><br>
<h3>Top Mini Menu</h3>
This is shown at the very top of the pages above any content. <?php if($sytist_store == true) { ?> This is where you would probably want your Cart, Checkout and Account links.<?php  } ?>
<br><br>
<h3>Side Bar Menu</h3>
This menu can be placed in the side bar section of your website if it is in use. <br><br>
To use the side bar menu:
<ul style="list-style: disc; margin-left: 20px;">
<li >Be sure it is not disabled in your <a href="theme-edit.php?css_id=<?php print $mytheme['css_id'];?>">theme</a>.
<li>Add it as a feature in the <a href="index.php?do=look&view=sidemenu">Side Bar Section</a>.
</ul><br>
To change the display order of the links in each section, just click and drag and drop the link name. 
</div>


<div class="left" style="width: 25%;">
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-topmain','sort_order-topmain','orderLinks');
	});
	</script>
	<form id="dd-form-topmain" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderLinks">
	<input type="hidden" name="link_location" value="topmain">
	<?php
	unset($order);
	$links = whileSQL("ms_menu_links", "*", "WHERE link_id>'0' $and_where AND link_location='topmain' ORDER BY link_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		$order[] = $link['link_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-topmain" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>

	<div style="padding: 8px;">
	<div class="pc"><h2>Main Menu</h2></div>
	<div class="pc">The main menu located below or beside the header.</div>
	<div id="">
	<?php listLinks("topmain");?>
	</div>
	</div>
	</form>
</div>
<?php if($sytist_store == true) { ?>

<div class="left" style="width: 25%;">
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-shop','sort_order-shop','orderLinks');
	});
	</script>
	<form id="dd-form-shop" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderLinks">
	<input type="hidden" name="link_location" value="shop">
	<?php
	unset($order);
	$links = whileSQL("ms_menu_links", "*", "WHERE link_id>'0' $and_where AND link_location='shop' ORDER BY link_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		$order[] = $link['link_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-shop" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>


	<div style="padding: 8px;">
	<div class="pc"><h2>Top Mini Menu</h2></div>
	<div class="pc">This is the small top menu at the very top of the page. <a href="index.php?do=look&view=links&action=disableMiniLinks">Disable all mini menu links</a>.</div>
	<div id="">
	<?php listLinks("shop");?>
	</div>
	</div>
	</form>
</div>
<?php } ?>
<div class="left" style="width: 25%;">
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-mobile','sort_order-mobile','orderLinksMobile');
	});
	</script>
	<form id="dd-form-mobile" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderLinksMobile">
	<input type="hidden" name="link_location" value="mobile">
	<?php
	unset($order);
	$links = whileSQL("ms_menu_links", "*", "WHERE link_id>'0' $and_where AND link_status='1'  ORDER BY link_mobile_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		$order[] = $link['link_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-mobile" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>

	<div style="padding: 8px;">
	<div class="pc"><h2>Mobile Menu Order</h2></div>
	<div class="pc">The mobile menu is the combination of the main & top mini menus. Here you can adjust the order of those menu items in the mobile menu.</div>
	<div id="">
	<?php listLinks("mobile");?>
	</div>
	</div>
	</form>
</div>



<div class="clear"></div>


<div class="left" style="width: 25%;">
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-side','sort_order-side','orderLinks');
	});
	</script>
	<form id="dd-form-side" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderLinks">
	<input type="hidden" name="link_location" value="side">
	<?php
	unset($order);
	$links = whileSQL("ms_menu_links", "*", "WHERE link_id>'0' $and_where AND link_location='side' ORDER BY link_order ASC  ");
	while($link = mysqli_fetch_array($links)) {
		$order[] = $link['link_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-side" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>

	<div style="padding: 8px;">
	<div class="pc"><h2>Side Bar  Menu</h2></div>
	<div id="">
	<?php listLinks("side");?>
	</div>
	</div>
	</form>
</div>

<?php
function listLinks($section) { 
	global $setup,$site_setup;
	if($section == "mobile") { 
		$link_location = "";
		$order_by = "link_mobile_order";
		$and_link_status = " AND link_status='1' ";
	} else { 
		$link_location = "AND link_location='".$section."' ";
		$order_by = "link_order";
	}
	$datas = whileSQL("ms_menu_links", "*", "WHERE link_id>'0' $and_where $link_location $and_link_status ORDER BY $order_by ASC  ");
		$tmtotal = mysqli_num_rows($datas);
			if(mysqli_num_rows($datas)<=0) { ?>
			<div class="pageContent">No links added</div>
<div>&nbsp;</div>
<?php } 

		if(mysqli_num_rows($datas)>0) { ?>

	<ul id="sortable-list-<?php print $section;?>" class="sortable-list">
		<?php
		while ($data = mysqli_fetch_array($datas)) {
			$totalLinks = mysqli_num_rows($datas);
			$rownum++;
			$thisLink++;
			?>
			<li title="<?php print $data['link_id'];?>">

			<div id="row-<?php print $rownum;?>"  class="underline">
			
				<div class="pc">
					<?php 
					if($data['link_status'] == "1") {
						print " <span title=\"Active\">".ai_green."</span>";
					} else {
						print " <span title=\"Inactive\">".ai_red."</span>";
					}
				?>
					<?php if($section !== "mobile") { ?><a href="" onclick="editlink('<?php print $data['link_id'];?>'); return false;" title="Edit"><span class="the-icons icon-pencil"></span></a>
					<?php if($data['link_no_delete'] <=0) { ?><a href="index.php?do=look&view=links&subdo=deleteLink&link_id=<?php print $data['link_id'];?>"  onClick="return confirm('Are you sure you want to delete the link <?php print strip_tags(addslashes($data['link_text']));?>? ');" title="Delete Link"><span class="the-icons icon-trash-empty"></span></a><?php } ?>
					<?php } ?>
					
					<span class="the-icons icon-sort" title="Drag & Drop To Sort"></span>

				</nobr> <span class="h3">
				<?php if(!empty($data['link_icon'])) { ?><span class="the-icons <?php print $data['link_icon'];?>"></span><?php } ?>
				<?php print $data['link_text']; ?></span>&nbsp;</div>

				<div  class="pc muted <?php if($section == "mobile") { ?>hide<?php } ?>">
				<?php 
				if($section == "shop") { 
					if($data['link_shop_menu'] == "accountmenu") { print "Left side - "; } 
					if($data['link_shop_menu'] == "shopmenu") { print "Right side - "; } 
				}
					
				if(!empty($data['link_main'])) { 
				if($data['link_main'] == "cart") { 
					print "View Cart Page";
				}
				if($data['link_main'] == "checkout") { 
					print "Checkout Page";
				}
				if($data['link_main'] == "myaccount") { 
					print "My Account Page";
				}
				if($data['link_main'] == "findphotos") { 
					print "Find Photos Page";
				}
				if($data['link_main'] == "login") { 
					print "Log In";
				}
				if($data['link_main'] == "newaccount") { 
					print "New Account Page";
				}
				if($data['link_main'] == "logout") { 
					print "Log out link";
				}
				if($data['link_main'] == "printcredit") { 
					print "Redeem print credit";
				}
				if($data['link_main'] == "redeemcoupon") { 
					print "Redeem coupon";
				}

				} elseif($data['link_page'] > 0) {
					$page = doSQL("ms_calendar", "*", "WHERE date_id='".$data['link_page']."' ");
					if($page['page_home'] == "1") {
						print "<a href=\"".$setup['temp_url_folder']."/\" target=\"_blank\">".$setup['temp_url_folder']."/</a>"; 
					} else {
						print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."/".$page['date_link']."\" target=\"_blank\">".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."/".$page['date_link']."/</a>"; 
					}
				} elseif($data['link_cat'] > 0) {
					$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$data['link_cat']."' ");
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."\" target=\"_blank\">".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder']."/</a>"; 

				} elseif(empty($data['link_url'])) {
					print "<i>Label</i>";
				} else {
					print "<a href=\"".$data['link_url']."\" target=\"_blank\">".$data['link_url']."</a>"; 
				}  
				
				?>&nbsp;</div>

			</div>
		</li>

			<?php } ?>
		</ul>
<div>&nbsp;</div>
<?php } ?>
<?php } ?>
