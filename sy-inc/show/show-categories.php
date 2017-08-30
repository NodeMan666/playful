			<?php if($main_full_screen <=0) { ?>

			<?php 
			$scats = explode(",",$cats);
			foreach($scats AS $cat) { 
			$cat = doSQL("ms_blog_categories LEFT JOIN ms_calendar ON ms_blog_categories.cat_id=ms_calendar.date_cat", "*", "WHERE cat_id='".$cat."' ");
			$section_name = "cat_name";
				$cdate = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_cat='".$cat['cat_id']."' ORDER BY date_id DESC ");
				if(!empty($cdate['date_id'])) { 
					$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$cdate['date_id']."'  AND bp_sub_preview<='0'   $and_sub ORDER BY bp_order ASC LIMIT  1 ");
					$pic_file_select = "pic_large";
					if(empty($fphoto['pic_id'])) {
						$fphoto = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$cdate['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");
					}
					if(!empty($fphoto['pic_id'])) { 

						$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$fphoto['pic_folder']."/".$fphoto['pic_pic']); 
						?>
						
						<div class="catphotocontainer nofloatsmallleft">
						<div class="catphotocontainerinner">
							<div class="previewtext" style="margin: 8px 8px 0px 8px;">
								<div style=" color: #FFFFFF; text-shadow: 1px 1px 1px #000000; background: #000000; padding: 8px; border-bottom: solid 2px #444444; width: 100%;">
								<span style="text-transform:uppercase; font-weight: bold;"><?php print $cdate[$section_name];?></span></div>
							</div>
							<a href="<?php print $setup['temp_url_folder']."".$setup['content_folder']."".$cdate['cat_folder']."/".$cdate['date_link']."/".$cdate['list_sub_link'];?>">
							<div class="catphoto" >
								<img id="cp-<?php print $fphoto['pic_key'];?>" src="<?php print $setup['photos_upload_folder']."/".$fphoto['pic_folder']."/".$fphoto['pic_pic'];?>" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>" class="homephotos">					
							<div style="position: absolute; bottom: 16px; left: 16px; ">
								<div class="headline" style="font-size: 27px; color: #FFFFFF; text-shadow: 1px 1px 1px #000000; font-family: Bitter; padding: 4px;"><?php print $cdate['date_title'];?></div>
								<!-- <div class="previewtext" style="color: #FFFFFF; text-shadow: 1px 1px 1px #000000;"><span style="background: #445568; padding: 4px;text-transform:uppercase; font-weight: bold;"><?php print $cdate['cat_name'];?></span></div> -->
							</div>
						</div>
						</a>
					</div>

					<div>&nbsp;</div>
					<div style="margin: 8px;">
					<?php $udates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_cat='".$cdate['cat_id']."' ORDER BY date_id DESC LIMIT 1,$catsubrecent ");
					$tf = 0;
					while($udate = mysqli_fetch_array($udates)) { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$udate['date_id']."'  AND bp_sub_preview<='0'   $and_sub ORDER BY bp_order ASC LIMIT  1 ");
					if(empty($pic['pic_id'])) {
						$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$udate['date_id']."' $and_sub  ORDER BY bp_order ASC LIMIT  1 ");
					}
		
					$tf++;
					?>
					<div class="subpagecats">
					<?php 	if(!empty($pic['pic_id'])) { ?>
					<a href="<?php print $setup['temp_url_folder']."".$setup['content_folder']."".$cdate['cat_folder']."/".$udate['date_link']."/".$udate['list_sub_link'];?>"><img src="<?php print $setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];?>" align="absmiddle"></a>
					<?php } ?>
					<a href="<?php print $setup['temp_url_folder']."".$setup['content_folder']."".$cdate['cat_folder']."/".$udate['date_link']."/".$udate['list_sub_link'];?>"><?php print $udate['date_title'];?></a></div>
					<?php } ?>

					<?php if($tf < $catsubrecent) { 
							while($tf < $catsubrecent) { 
								$tf++;
								?>
								<div style="padding: 8px; border-top: solid 1px #ECECEC;"> &nbsp;</div>
							<?php } 
						}
						?>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
