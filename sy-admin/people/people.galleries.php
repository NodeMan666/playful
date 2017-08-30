		<div class="underlinelabel">Galleries</div>
	<div class="underlinespacer">These are password protected galleries <?php print $p['p_name']." ".$p['p_last_name'];?> has access to. A customer can get access to a gallery either by using the gallery password or being assigned to the gallery from the gallery in the admin.</div>
<?php 
	$dates = whileSQL("ms_my_pages LEFT JOIN ms_calendar ON ms_my_pages.mp_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE mp_people_id='".$p['p_id']."' AND date_id>'0' ");
	if(mysqli_num_rows($dates) > 0) { ?>
	<?php } ?>
	<?php 
	if(mysqli_num_rows($dates) <= 0) { ?><div class="pc center">No galleries found</div><?php } 

	while($date = mysqli_fetch_array($dates)) {
		if($date['mp_sub_id'] > 0) { 
			$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$date['mp_sub_id']."' ");
			$and_sub = "AND bp_sub='".$sub['sub_id']."' ";
			$and_sub_preview = "AND bp_sub='".$sub['bp_sub_preview']."' ";
		} else { 
			$and_sub = "AND bp_sub='0' ";
			$and_sub_preview = "AND bp_sub='0' ";
		}
		print "<div class=\"underline\">";
			$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'   $and_sub_preview  ORDER BY bp_order ASC LIMIT  1 ");
			if(!empty($pic['pic_id'])) {
			//	$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
				print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\" left\" style=\"margin-right: 8px;\" ".$size[3]." border=\"0\"></a>";
			} else { 
				$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");
				if(!empty($pic['pic_id'])) {
			//		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']); 
					print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\"><img src=\"".getimagefile($pic,'pic_mini')."\" class=\" left\" style=\"margin-right: 8px;\" ".$size[3]." ></a>";
				}
			}
		print "<h3><a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$sub['sub_id']."\">".$date['date_title']." ";
		if($sub['sub_id'] > 0) { 
			$ids = explode(",",$sub['sub_under_ids']);
			foreach($ids AS $val) { 
				if($val > 0) { 
					$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
					print' > '.$upsub['sub_name'].'  ';
				}
			}	
			print " > ".$sub['sub_name'].""; 
			
		} 
		print "</a></h3>";

		?>
		<a href="index.php?do=people&action=removeaccess&p_id=<?php print $p['p_id'];?>&mp_id=<?php print $date['mp_id'];?>" onClick="return confirm('Are you sure you want to remove access to this gallery? If you remove access, they can still enter in the password to view the page. So you may want to change the password to the page also. ');">Remove Access</a> 

		<?php 
		print "<div class=\"clear\"></div>";
		print "</div>";
	}

?>