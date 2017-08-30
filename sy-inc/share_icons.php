<?php

$fb = doSQL("ms_fb", "*", ""); 
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$date['date_id']."'  AND bp_sub_preview<='0'  ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) {
		$pic['full_url'] = true;
		$fb_thumb =getimagefile($pic,'pic_large');
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
		$pic['full_url'] = true;
		if(!empty($pic['pic_id'])) {
			$fb_thumb =getimagefile($pic,'pic_large');
		}
	}
if($date['page_home'] == "1") {
	$share_link = $setup['url'];
} else {
	$share_link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/";
}
if(!empty($date['date_meta_title'])) { 
	$date['date_title'] = $date['date_meta_title'];
}
if($bcat['cat_content'] == $date['date_id']) { 
	$share_link = $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$bcat['cat_folder']."/";
}

if(!empty($date['date_meta_title'])) { 
	$share_title = $date['date_meta_title'];
} else { 
	$share_title = $date['date_title']." - ".$site_setup['website_title'];
}
?><div class="pagesharecontainer pc"><?php if($fb['share_add_like'] == "1") { ?>
<div id="fbLike" class="left" style="margin-right: 16px;  line-height: 24px;"><script src="//connect.facebook.net/<?php print $fb['fb_lang'];?>/all.js#xfbml=1"></script><fb:like show_faces="<?php print $fb['like_show_faces'];?>"  layout="button_count" width="90" font="arial"  colorscheme="<?php print $css['fb_color'];?>" ></fb:like></div>
<?php } ?><div class="pageshare left" style=""><?php if((!empty($fb['share_text']))&&($fb['share_text_placement'] == "above")==true) { ?><div class="pc"><span class="sharetext"><?php print $fb['share_text'];?></span></div><?php } ?><ul><?php if((!empty($fb['share_text']))&&($fb['share_text_placement'] == "left")==true) { ?><li class="sharetext"><?php print $fb['share_text'];?></li><?php } ?>
<li><a href=""  onclick="sharepage('facebook','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($share_title);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-facebook the-icons"></a></li>
<li><a href=""  onclick="sharepage('pinterest','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($share_title);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;"  class="icon-pinterest the-icons"></a></li>
<li><a href=""   onclick="sharepage('twitter','<?php print $share_link;?>','<?php print $fb['facebook_app_id'];?>','<?php print urlencode($share_title);?>','<?php print $setup['url'];?>','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-twitter the-icons"></a></li>
<li><a href="" onclick="sharepage('email','<?php print $share_link;?>','','<?php print urlencode($share_title);?>','','<?php print $date['private'];?>','<?php print $fb_thumb;?>','<?php print $date['date_id'];?>');  return false;" class="icon-mail the-icons"></a></li>
<?php if((!empty($fb['share_text']))&&($fb['share_text_placement'] == "right") == true) { ?><li class="sharetext"><?php print $fb['share_text'];?></li><?php } ?>
</ul>
</div>
<?php if((!empty($fb['share_text']))&&($fb['share_text_placement'] == "below")==true) { ?><div class="pc"><span class="sharetext"><?php print $fb['share_text'];?></span></div><?php } ?>
</div>
<div class="clear"></div>