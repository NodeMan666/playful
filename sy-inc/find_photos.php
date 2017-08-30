<?php
if(customerLoggedIn()) { 
	$mdates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."' AND date_id>'0'  ");
	if(mysqli_num_rows($mdates) > 0) {
		$show_my_photos = true;
	}
}
?>
	
<?php if($show_my_photos == true) { ?>
	<div class="left nofloatsmall" style="width: 48%;">
<?php } else { ?>
	<div style=" margin: auto; text-align: center;">
<?php } ?>
<div class="pc title"><h1><?php print _access_private_photos_title_;?></h1></div>
	<div class="pc"><?php print _access_private_photos_text_;?></div>
	<div class="pc">
		<div>
		<form method="post" name="access" action="<?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>" id="accesspageform" onsubmit="accesspage(); return false;" data-one-moment="<?php print _one_moment_please_;?>">
		<div><?php print _access_private_photos_password_;?></div>
		<div><input type="text" size="25" name="pagepass" id="pagepass"> <input type="submit" name="submit" value="<?php print _access_private_photos_submit_;?>" class="submit" id="findsubmit"> </div>
		</form>
		</div>
		<div id="accesspagebad" class="error" style="display: none;"><?php print _access_private_photos_not_found_;?></div>
	</div>
</div>
<?php if($show_my_photos == true) { ?>

<div class="right nofloatsmall" style="width: 48%;">
<?php
if(customerLoggedIn()) { 
	$mdates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE MD5(mp_people_id)='".$_SESSION['pid']."'  AND date_id>'0'  ");
	if(mysqli_num_rows($mdates) > 0) { ?>
<div class="pc title"><h1><?php print _my_photos_;?></h1></div>
	<?php } ?>
	<?php 
	while($mdate = mysqli_fetch_array($mdates)) { 
		if(!empty($mdate['mp_sub_id'])) { 

			$msub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$mdate['mp_sub_id']."' ");
			print "<div class=\"pc\">";

			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_sub_preview='".$msub['sub_id']."' ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) { 
				print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/?sub=".$msub['sub_link']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" border=\"0\"></a>";

			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE  bp_sub='".$msub['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/?sub=".$msub['sub_link']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" border=\"0\"></a>";
				} else { 
					$unders = whileSQL("ms_sub_galleries", "*", "WHERE sub_under='".$msub['sub_id']."' ");
					while($under = mysqli_fetch_array($unders)) { 
						if(empty($thumb_html)) { 
							$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE   bp_sub='".$under['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
							if(!empty($pic['pic_id'])) {
							print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/?sub=".$msub['sub_link']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" border=\"0\"></a>";
							}
						}
					}
				}
			}
			

			print "<h2><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/?sub=".$msub['sub_link']."\">".$msub['sub_name']."</a></h2>";
			print "<div class=\"clear\"></div>";
			print "</div>";



		} else { 

			print "<div class=\"pc\">";
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$mdate['date_id']."'   AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
					print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" border=\"0\"></a>";
				} else { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$mdate['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						$size = @GetImageSize("".$setup['path']."".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
						print "<a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\"mini left\" style=\"margin-right: 8px;\" width=\"50\" height=\"50\" ></a>";
					}
				}
			print "<h2><a href=\"".$setup['temp_url_folder']."".$setup['content_folder']."".$mdate['cat_folder']."/".$mdate['date_link']."/\">".$mdate['date_title']."</a></h2>";
			print "<div class=\"clear\"></div>";
			print "</div>";
		}
	}
}
?>
</div>
<?php } ?>
<div class="clear"></div>
