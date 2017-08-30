<?php
		$photo_setup = doSQL("ms_photo_setup", "*", "  ");
		$width = $photo_setup['blog_width'];
		$height = $photo_setup['blog_height'];
		$thumb_size = $photo_setup['blog_th_width'];
		$thumb_size_height = $photo_setup['blog_th_height'];
		$mini_size = $site_setup['blog_mini_size'];
//		$crop_thumbs = $site_setup['blog_crop_thumb'];
?>
<div  class="uploadphotos"><a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>&pic_client=<?php print $_REQUEST['pic_client'];?>'); return false;" >Upload Photos</a></div>

<div>&nbsp;</div>
<div id="photo-tags"></div>
<div>&nbsp;</div>
