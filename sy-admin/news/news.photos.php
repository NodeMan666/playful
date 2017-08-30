<?php
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show, date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE date_id='".$_REQUEST['date_id']."' ");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;
if($date_type=="page") { 
	$setup['content_folder'] = $setup['pages_folder'];
} elseif($date_type == "gal") { 
	$setup['content_folder'] = $setup['photos_folder'];
} else {
	$date_type = "news";
}
if($_REQUEST['sub_id'] > 0) { 
	$thissub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}
 if($photo_setup['gallery_favicon'] == "1") { 
	if($date['cat_type'] == "clientphotos") { 
		if(!file_exists($setup['path']."/sy-photos/favicons/".$date['date_id']."/icon.png")) { 
			include $setup['path']."/".$setup['manage_folder']."/photo.process.functions.php";
			createGalleryIcon($date,0);
		}
	}
 }
?>


<?php // print "<li>".countIt("ms_photos", "WHERE pic_keywords LIKE '%sumtim,%' "); ?>


<?php


if($_REQUEST['subdo'] == "updatefavsettings") { 
	updateSQL("ms_calendar", "
		open_highlights='".$_REQUEST['open_highlights']."', 
		show_star='".$_REQUEST['show_star']."', 
		add_highlight_link='".$_REQUEST['add_highlight_link']."', 
		highlights_text='".addslashes(stripslashes($_REQUEST['highlights_text']))."', 
		star_text='".addslashes(stripslashes($_REQUEST['star_text']))."' 
		WHERE date_id='".$_REQUEST['date_id']."'

	");
	$_SESSION['sm'] = "Settings Updated";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."&view=".$_REQUEST['view']."");
	exit();
}


if($_REQUEST['subdo']=="deleteSubGallery") {

	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND bp_sub='".$_REQUEST['sub_id']."'  ORDER BY ms_blog_photos.bp_order ASC ");
	while($pic = mysqli_fetch_array($pics)) { 
		deleteSQL("ms_blog_photos", "WHERE bp_id='".$pic['bp_id']."' ", "1");
		if($_REQUEST['deletephotos'] == "1") { 
			deleteOnePic($pic);
		}
	}
	deleteSQL("ms_sub_galleries", "WHERE sub_id='".$_REQUEST['sub_id']."' ", "1");
	$_SESSION['sm'] = "Sub gallery deleted";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	exit();
}
if($_REQUEST['subdo'] == "removephotographerfavs") { 
	if($_REQUEST['sub_id'] > 0) {
		$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
	}
	updateSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "pic_fav_admin='0' WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub");
	$_SESSION['sm'] = "Favorites removed";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."");
	exit();
}


if($_REQUEST['subdo']=="clearKeywords") {

	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' ORDER BY ms_blog_photos.bp_order ASC ");
	while($pic = mysqli_fetch_array($pics)) { 
		updateSQL("ms_photos", "pic_keywords='' WHERE pic_id='".$pic['pic_id']."'  ");
	}

	$_SESSION['sm'] = "Keywords deleted";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	exit();

}
if($_REQUEST['subdo']=="removeAllPhotos") {
	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  AND bp_sub='".$_REQUEST['sub_id']."'  ORDER BY ms_blog_photos.bp_order ASC ");
	while($pic = mysqli_fetch_array($pics)) { 
		deleteSQL("ms_blog_photos", "WHERE bp_id='".$pic['bp_id']."' ", "1");
		if($_REQUEST['deletephotos'] == "1") { 
			deleteOnePic($pic);
		}
	}
	$_SESSION['sm'] = "Photos removed";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."");
	exit();


}

if($_REQUEST['subdo']=="batchKeywords") {
	$add_keys = array();
	$keywords = $_REQUEST['add_keys'];
	$exkeys = explode(",",$keywords);
	foreach($exkeys AS $key) { 
		$key = trim(strtolower($key));
		if(!empty($key)) { 
			if(!in_array($key,$add_keys)) { 
				array_push($add_keys,$key);
			}
		}
	}
	$pics_where = "WHERE pic_gal='".$pb['gal_id']."' ";

	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' ORDER BY ms_blog_photos.bp_order ASC ");
	while($pic = mysqli_fetch_array($pics)) { 
		$pic_keys = array();
		$exkeys = explode(",",$pic['pic_keywords']);
		foreach($exkeys AS $key) { 
			$key = trim(strtolower($key));
			if(!empty($key)) { 
				if(!in_array($key,$pic_keys)) { 
					array_push($pic_keys,$key);
				}
			}
		}
		foreach($add_keys AS $new_key) { 
			if(!in_array($new_key,$pic_keys)) { 
				array_push($pic_keys,$new_key);
			}
		}
		asort($pic_keys);
		foreach($pic_keys AS $key) {
			$sql_key .= "$key,";
			$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($key))."' ");
			if(empty($ck['id'])) { 
				insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($key))."' ");
			}
		}
		updateSQL("ms_photos", "pic_keywords='".addslashes(stripslashes($sql_key))."' WHERE pic_id='".$pic['pic_id']."'  ");
		unset($sql_key);
		unset($pic_keys);
	}

	$_SESSION['sm'] = "Keywords added";
	session_write_close();
	header("location: index.php?do=".$_REQUEST['do']."&action=managePhotos&date_id=".$_REQUEST['date_id']."");
	exit();



}

?>


<?php if($date['green_screen_gallery'] == "1") { ?>
<div id="pageTitle">Green Screen Backgrounds</div>
<?php } ?>
<?php if($date['green_screen_gallery'] !== "1") { ?>
<div id="pageTitle">
<a href="index.php?do=<?php print $_REQUEST['do'];?>">Site Content</a> 

<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>


<span><?php  if($date['page_home'] == "1") { print "Home Page"; }  else { print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">".$date['date_title']."</a>"; } ?> </span>
<?php print ai_sep;?>  Photos 
</div>
<?php } ?>


<?php 	
	
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
?>




<?php if($date['green_screen_gallery'] !== "1") { ?>
<?php include "news.tabs.php"; ?>
<?php } ?>

<div id="roundedFormContain">
<?php 
 
if(!empty($date['date_photo_keywords'])) {
	$cx = 0;
	$and_date_tag = "( ";
	$date_tags = explode(",",$date['date_photo_keywords']);
	foreach($date_tags AS $tag) { 
		$cx++;
		if($cx > 1) { 
			$and_date_tag .= " OR ";
		}
		$and_date_tag .=" key_key_id='$tag' ";
	}
	$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
	$and_date_tag .= " ) ";

	$piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id  ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." "); 
	$num_pics =  mysqli_num_rows($piccount);
} else { 
	$num_pics = countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' "); 
	
}
?>
<div class="buttonsgray">
<ul>
<?php if(($date['green_screen_gallery'] == "1") && ($_REQUEST['sub_id'] <= 0) ==true){ ?>
	<li><a href="" onclick="pagewindowedit('w-sub-galleries.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;">Create Background Folders</a></li>
<?php } ?>
<?php if((($date['green_screen_gallery'] == "1") && ($_REQUEST['sub_id'] > 0)) ||($date['green_screen_gallery'] == "0") ==true){ ?>
<li><a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>'); return false;" >UPLOAD PHOTOS</a></li>
<?php if($date['cat_type'] !== "proofing") { ?><li><a href="" onclick="pagewindowedit('w-photos-keywords.php?date_id=<?php print $date['date_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;" >PHOTOS BY TAGS <?php if(!empty($date['date_photo_keywords'])) { ?><img  src="graphics/icons/green.png" width="16" height="16" align="absmiddle" title="Enabled"><?php } ?></a></li><?php } ?>
<?php } ?>
<?php if($date['green_screen_gallery'] !== "1") { ?>
<li><a href="" onclick="pagewindowedit('w-photo-display-settings.php?date_id=<?php print $date['date_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;" >PHOTO DISPLAY SETTINGS</a></li>
<?php } ?>
<?php if( countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' ") > 0) { ?>
	<li><a href="" onclick="pagewindowedit('w-photos-order.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;">REARRANGE PHOTOS</a></li>

<?php 
	$rhtml .= $date['date_title'];
		if($thissub['sub_id'] > 0) { 	
			$ids = explode(",",$thissub['sub_under_ids']);
			foreach($ids AS $val) { 
				if($val > 0) { 
					$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
					$rhtml .= " > ".$upsub['sub_name'];
				}
			}
		$rhtml .=" > ".$thissub['sub_name'];
		}
?>
	<li><a  href="" id="removealllink" class="confirmdeleteoptions" confirm-title="Remove Photos from<br><?php print htmlspecialchars($rhtml);?>" confirm-message="Select from the options below" option-link-1="index.php?do=<?php print $_REQUEST['do'];?>&action=managePhotos&subdo=removeAllPhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" option-link-1-text="Remove photos from this page only but leave in the system"  option-link-2="index.php?do=<?php print $_REQUEST['do'];?>&action=managePhotos&subdo=removeAllPhotos&deletephotos=1&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" option-link-2-text="Delete these photos completely from the system">REMOVE ALL PHOTOS</a></li>

<!-- 	<li><a href="admin.actions.php?do=<?php print $_REQUEST['do'];?>&action=regenThumbsFromBlog&date_id=<?php print $date['date_id'];?>"  onClick="return confirm('Doing this will regenerate the thumbnails and mini files for the selected photos to your default settings now.\r\n\r\nThumbnails <?php print $photo_setup['blog_th_width'];?>px max width & <?php print $photo_setup['blog_th_height'];?>px max height.\r\nMini thumbs to <?php print $photo_setup['mini_size'];?>px square.\r\n\r\nNOTE: This will change the thumbnails any where the photos below are shown.\r\n\r\nClick OK to continue.'); return false;">Regenerate Thumbnails</a></li> -->
	<?php if($date['passcode_photos'] == "1") { ?>
	<li><a href="" onclick="uploadpasscodes('<?php print $date['date_id'];?>'); return false;">IMPORT PASSCODES</a></li>
	<?php } ?>
	<?php } ?>

	<?php if(($photo_setup['enable_amazon'] == "1")&&($num_pics > 0) == true) { ?>
	<li><a href="" onclick="movetoamazon('<?php print $date['date_id'];?>'); return false;"><img src="graphics/amazon-s3-logo-16.png" align="absmiddle" height="16" width="16"> MOVE TO S3</a></li>
	<?php } ?>
	<li><a href="admin.actions.php?action=selectAllFromPage&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&view=<?php print $_REQUEST['view'];?>">SELECT ALL</a></li>
	<li><a href="" onclick="galleryfreedownload('<?php print $date['date_id'];?>','<?php print $_REQUEST['sub_id'];?>'); return false;"><span class="the-icons icon-user"><?php $frees = countIt("ms_gallery_free LEFT JOIN ms_people ON ms_gallery_free.free_person=ms_people.p_id LEFT JOIN ms_photo_products ON ms_gallery_free.free_product=ms_photo_products.pp_id", "WHERE free_gallery='".$date['date_id']."' AND free_sub='".$_REQUEST['sub_id']."' ");
	if($frees > 0) { print "(".$frees.")"; } ?>
	</span> <span  class="tip" title="Assign Customer To Download Photos For Free">FREE DOWNLOADS</span></a></li>

</ul>
</div>
<div class="clear"></div>

<?php if(($date['cat_type'] !== "proofing") && ($date['date_cat'] > 0)==true) { ?>

<div class="buttonsgray">
	<ul>
	<li><a href="" onclick="pagewindowedit('w-sub-galleries.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;">Add Sub Galleries</a></li>

	<?php if( countIt("ms_sub_galleries", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ") > 0) { ?>
	<li><a href="" onclick="pagewindowedit('w-sub-galleries-order.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&nofonts=1&nojs=1&noclose=1'); return false;">Rearrange Sub Galleries</a></li>
	<?php } ?>


	<?php 
	if($_REQUEST['sub_id'] <= 0) { 
		$favs = countIt("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ",  "WHERE pic_fav_admin='1' AND bp_blog='".$_REQUEST['date_id']."'  ");
	} else { 
		$favs = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id  ",  "WHERE pic_fav_admin='1' AND bp_blog='".$_REQUEST['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' ");
	}
	?>
	<li <?php if($favs <= 0) { ?>class="hide"<?php } ?> id="photographerfavs"><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&view=photographerfavs"><?php print ai_fav_on;?> Photographer Favorites (<span id="photogfavs"><?php print $favs;?></span>)</a> &nbsp; <a href="" onclick="showfavsettings(); return false;">Favorites Settings</a></li>


	</ul>
</div>
<?php } ?>
<div class="clear"></div>



<script>
function startext() { 
	if($("#show_star").attr("checked")) { 
		$("#startext").slideDown(200);
	} else { 
		$("#startext").slideUp(200);
	}
}
function showfavsettings() { 
	$("#favsettings").slideToggle(200);
}
</script>


<div id="favsettings" class="hide" style="max-width: 800px; margin: auto;">
		<?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="https://www.picturespro.com/sytist-manual/articles/photographer-favorites/" target="blank">Learn more about this feature</a></div><?php } ?>

<?php if(empty($date['highlights_text'])) { 
		$ldate = doSQL("ms_calendar", "*", "WHERE highlights_text!='' ORDER BY date_id DESC ");
		if(empty($ldate['date_id'])) { 
			$date['highlights_text'] = "Highlights";
			$date['star_text'] = "Photographer Favorite";
		} else { 
			$date['highlights_text'] = $ldate['highlights_text'];
			$date['star_text'] = $ldate['star_text'];
			$date['open_highlights'] = $ldate['open_highlights'];
			$date['add_highlight_link'] = $ldate['add_highlight_link'];
			$date['show_star'] = $ldate['show_star'];
			$fav_message = "These settings need to be saved to take effect";
		}
	}
	?>

	<form method="post" name="favss" id="favss" action="index.php">
	<div>&nbsp;</div>

	
	<?php if(countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ") > 0) { ?>
		<div class="underline <?php if($date['date_gallery_exclusive'] !== "1") { ?>hide<?php } ?>">
			<div class="label"><input type="checkbox" name="open_highlights" id="open_highlights" value="1" <?php if($date['open_highlights'] == "1") { ?>checked<?php } ?>> <label for="open_highlights">Show photographer favorites when gallery is opened.</label></div>
			<div>This will show all photographer favorites from the sub galleries in one gallery when the gallery is entered under the cover photo.</div>
		</div>
		<?php } ?>
		<?php if(countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' ORDER BY sub_order ASC, sub_name ASC  ") <= 0) { ?>
		<div class="underline" <?php if($date['date_gallery_exclusive'] !== "1") { ?>hide<?php } ?>>
			<div class="label"><input type="checkbox" name="add_highlight_link" id="add_highlight_link" value="1" <?php if($date['add_highlight_link'] == "1") { ?>checked<?php } ?>> <label for="add_highlight_link">Add a link to in menu to view photographer favorites</label></div>
		</div>
		<?php } ?>
		<div class="underline <?php if($date['date_gallery_exclusive'] !== "1") { ?>hide<?php } ?>">
			<div class="label">Photographer Favorites Called:</div>
			<div><input type="text" name="highlights_text" id="highlights_text" value="<?php print $date['highlights_text']; ?>" size="30"> </div>
			<div>Example: Highlights, Photographer Favorites</div>
		</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="show_star" id="show_star" value="1" <?php if($date['show_star'] == "1") { ?>checked<?php } ?> onchange="startext();"> <label for="show_star">Show star on photographer favorite photos</label></div>
	</div>
	<div class="underline <?php if($date['show_star'] !== "1") { ?>hide<?php } ?>" id="startext">
		<div class="label">Star text</div>
		<div><input type="text" name="star_text" id="star_text" value="<?php print $date['star_text']; ?>" size="30"> </div>
		<div>Example: Photographer Favorite</div>
	</div>
	<?php if(!empty($fav_message)) { ?><div class="pc" style="color: #890000; font-weight: bold;"><?php print $fav_message;?></div><?php } ?>
	<div class="pc">
	<input type="hidden" name="do" id="do" value="news">
	<input type="hidden" name="date_id" id="date_id" value="<?php print $date['date_id'];?>">
	<input type="hidden" name="sub_id" id="sub_id" value="<?php print $sub['sub_id'];?>">
	<input type="hidden" name="view" id="view" value="<?php print $_REQUEST['view'];?>">
	<input type="hidden" name="action" id="action" value="managePhotos">
	<input type="hidden" name="subdo" id="subdo" value="updatefavsettings">
	<input type="submit" name="submit" value="Save Settings" class="submit">
	</div>

	</form>
</div>
<?php if($_REQUEST['view'] == "photographerfavs") { ?>
<div class="pc">Viewing Photographer Favorites. <a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>">View all photos</a>. <a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&subdo=removephotographerfavs">Clear all favorites</a>.</div>
<?php } ?>
<div>&nbsp;</div>

<?php

$d['table'] = "ms_photos";
$d['table_id'] = "pic_id";
/*
$pics = whileSQL("ms_blog_photos", "*", "WHERE bp_blog='".$date['date_id']."' ORDER BY bp_order ASC");
$picsy = whileSQL("ms_blog_photos", "*", "WHERE bp_blog='".$date['date_id']."' ORDER BY bp_order ASC");
while ($bp_pic = mysqli_fetch_array($pics)){
	$pici = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE pic_id='".$bp_pic['bp_pic']."' ");

	if(!empty($pici['pic_folder'])) { 
		$pic_folder = $pici['pic_folder'];
	} else { 
		$pic_folder = $pici['gal_folder'];
	}
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pici['pic_th'].""); 
		if($size[0] > $max_width) { $max_width = $size[0]; } 
		if($size[1] > $max_height) { $max_height = $size[1]; } 
}
*/
?>
<?php if(($num_pics <=0) && (countIt("ms_sub_galleries", "WHERE sub_date_id='".$date['date_id']."' ") <=0)==true){ ?>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php if($date['green_screen_gallery'] !== "1") { ?>

<div class="pageContent center"><h2>No photos added. Click <a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>'); return false;" >Upload Photos</a> or select photos from the <a href="index.php?do=allPhotos&did=<?php print $date['date_id'];?>">All Photos</a> section.</h2></div>
<?php } else { ?>
<div class="pc center"><h2>First you need to create a folder or folders for your backgrounds. Click the Create Background Folders button above to do so. Once you do you can upload your background files.</h2></div>
<?php } ?>
<?php } ?>


<?php  $show_thumbnails = true;?>
<?php 
	if((countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ORDER BY sub_order ASC, sub_name ASC ") > 0) ||($_REQUEST['sub_id'] > 0)==true){ 
		$has_subs = true
		?>
<div style="">


	<?php // SORT FUNCTION START
	$add = "sub";
	?>

	<script>
	function updatesubgal(date_id) { 
		$("#subgload").show();
		$("#subgupdates").hide();
		if($("#hide_sub_gals").attr("checked")) { 
			val = 1;
		} else { 
			val = 0;
		}
		$.get("admin.actions.php?action=updatesubgal&date_id="+date_id+"&hide_sub_gals="+val, function(data) {
			$("#subgload").hide();
			$("#subgupdates").show();
		});
	}
	</script>

	<?php if($date['green_screen_gallery'] == "1") { ?>
	<div class="pc left"><h2>Background Folders</h2></div>
	<?php } else { ?>
	<div class="pc left"><h2>Sub Galleries</h2></div>
	<div class="pc right textright"><input type="checkbox" name="hide_sub_gals" id="hide_sub_gals" value="1" <?php  if($date['hide_sub_gals'] == "1") { print "checked"; } ?> onchange="updatesubgal('<?php print $date['date_id'];?>');"> <label for="hide_sub_gals">Do not display sub galleries</label><div class="moreinfo" info-data="nosubgals"><div class="info"></div></div>

	<br><span id="hideupdate"><span id="subgload" class="hidden"><?php print ai_loadingsmall;?></span><span id="subgupdates" class="hidden">changes saved</span></span></div>
	<?php } ?>
	<div class="clear"></div>
<?php 
	if($_REQUEST['sub_id'] > 0) { 
?>
		<div class="pc">
		<h3>
		<?php if($thissub['sub_under'] > 0) { ?>
		<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $thissub['sub_under'];?>" class="tip" title="Up a level"><?php print ai_folder_up;?></a> 
		<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>">Main</a> > 
			<?php $ids = explode(",",$thissub['sub_under_ids']);
			foreach($ids AS $val) { 
				if($val > 0) { 
					$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
					print "<a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$upsub['sub_id']."\">".$upsub['sub_name']."</a> > ";
				}
			}
			?>
			 
			<?php } else { ?>
			<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>" class="tip" title="Up a level"><?php print ai_folder_up;?></a> 		
			<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>">Main</a> > 

			<?php } ?>
			<?php print  $thissub['sub_name'];?></h3></div>
		<?php } ?>
	
		<?php if($thissub['sub_id'] > 0) { ?>
		<div class="pc">
		<a href="" onclick="pagewindowedit('w-sub-galleries-edit.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $thissub['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;">Edit</a> &nbsp; 

		<a href="" id="sub-<?php print $thissub['sub_id'];?>" class="confirmdeleteoptions" confirm-title="Delete Sub Gallery<br><?php print htmlspecialchars($thissub['sub_name']);?>" confirm-message="Select from the options below" option-link-1="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&subdo=deleteSubGallery&sub_id=<?php print $thissub['sub_id'];?>" option-link-1-text="Delete sub gallery and leave the photos in the system"  option-link-2="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&subdo=deleteSubGallery&sub_id=<?php print $thissub['sub_id'];?>&deletephotos=1" option-link-2-text="Delete sub gallery and DELETE THE PHOTOS from the system">Delete</a>  &nbsp; 
		<?php if($date['green_screen_gallery'] !== "1") { ?>

		<a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $thissub['sub_id'];?>'); return false;" >Upload Photos</a> &nbsp; 
		<a href="" onclick="pagewindowedit('w-sub-galleries.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $thissub['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;">Add Sub Galleries</a>  &nbsp; 
		  <a href="" onclick="openFrame('w-photos-sub-preview.php?bp_sub_preview=<?php print $thissub['sub_id'];?>&back_sub=<?php print $thissub['sub_id'];?>'); return false;" title="Upload Preview Photo">Upload Preview Photo</a> &nbsp; 
		  <a  href="<?php tempFolder();?><?php print $setup['content_folder']."".$dcat['cat_folder']."/".$date['date_link'];?>/?sub=<?php print $thissub['sub_link'];?>" target="_blank">View On Website</a>
		  <?php } ?>
		</div>
		<?php if(!empty($thissub['sub_pass'])) { ?>
		<div class="pc"><?php print ai_lock;?> <?php print $thissub['sub_pass'];?></div>
		<?php } ?>
		<?php } ?>
	<ul id="sub-gallery-list" class="sortable-list center">





	</ul>
<div class="cssClear"></div>

<div id="endsubgalleries" style="position: absolute;"></div>

</div>
<?php } ?>
<div class="clear"></div>
<div>&nbsp;</div>

<div id="photoGallery">
	<div id="showThumbnails"></div>
</div>
<div class="cssClear"></div>

<div id="endpage" style="position: absolute;"></div>
<?php if($date['green_screen_gallery'] !== "1") { ?>
<?php if($num_pics > 1) { ?>
<div class="muted pc center"><div>Click the <?php print ai_photo;?> icon to make that photo the preview photo.</div></div>
<?php } ?>
<?php } ?>



<div class="cssClear"></div>
</div>
