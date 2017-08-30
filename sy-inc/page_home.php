<?php
if($date['page_add_breaks'] == "1") {
	$html = nl2br($date['date_text']);
} else { 
	$html =  $date['date_text'];
}
$com_settings = doSQL("ms_comments_settings", "*", "");
$on_homepage = true;
require $setup['path']."/".$setup['inc_folder']."/page_display.php";

?>

<?php 
function doHomePage() { 
	
	?>
      <div  class="homePageLeftColumn">
		<?php 
		$items = whileSQL("ms_home_page_items", "*", "WHERE placement='leftcol' ORDER BY dorder ASC ");
		while($item = mysqli_fetch_array($items)) {
			if($item['widget'] == "NEWS") {
				print newsHeadlines();
			}
			if($item['widget'] == "EVENTS") {
				print eventHeadlines();
			}
			if($item['widget'] == "PHOTO_ALBUMS") {
				print photoAlbums();
			}
			if($item['widget'] == "SONGS") {
				print featueredSongs();
			}
			if($item['widget'] == "FACEBOOK_LIKE_BOX") {
				print faceBookLikeBox();
			}
			if($item['widget'] == "PHOTO_CART_LOGIN") {
				print photoCartLogin($item);
			}
			if($item['widget'] == "JUST_HEADLINES") {
				print blogSideMenu($hp['headlines_limit'],"hp");
			}
			if($item['widget'] == "BLOG_MENU") {
				print blogSideMenu($hp['headlines_limit'],"hp");
			}

			if($item['widget'] == "FEATURED_PAGES") {
				print featuredPages();
			}

			if($item['widget'] == "TEXT1") {
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
			if($item['widget'] == "TEXT2") {
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
			if($item['widget'] == "TEXT3") {
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
		}
		?>

		</div>


		<?php 
		$items = whileSQL("ms_home_page_items", "*", "WHERE placement='rightcol' ORDER BY dorder ASC ");
		if(mysqli_num_rows($items) > 0) { ?>
		 <div  class="homePageRightColumn">
		<?php 
		while($item = mysqli_fetch_array($items)) {
			if($item['widget'] == "NEWS") {
				print newsHeadlines();
			}
			if($item['widget'] == "EVENTS") {
				print eventHeadlines();
			}
			if($item['widget'] == "PHOTO_ALBUMS") {
				print photoAlbums();
			}
			if($item['widget'] == "SONGS") {
				print featueredSongs();
			}
			if($item['widget'] == "FACEBOOK_LIKE_BOX") {
				print faceBookLikeBox();
			}
			if($item['widget'] == "PHOTO_CART_LOGIN") {
				print photoCartLogin($item);
			}
			if($item['widget'] == "JUST_HEADLINES") {
				print blogSideMenu($hp['headlines_limit'],"hp");
			}
			if($item['widget'] == "BLOG_MENU") {
				print blogSideMenu($hp['headlines_limit'],"hp");
			}
			if($item['widget'] == "FEATURED_PAGES") {
				print featuredPages();
			}


			if($item['widget'] == "TEXT1") {
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
			if($item['widget'] == "TEXT2") {
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
			if($item['widget'] == "TEXT3") {
				if(!empty($item['feat_label'])) { 
					print  "<div class=pageContent><div id=\"homePageLabels\">".$item['feat_label']."</div></div>";
				}
				$item['item_text'] = preg_replace('#\[FAV_LINKS]#i', favLinks(),$item['item_text']);  
				print "<div class=pageContent>".$item['item_text']."</div>";
			}
		}
		?>
		</div>
		<?php }  ?>


</div>
<div class="cssClear"></div>
<?php

}

?>
