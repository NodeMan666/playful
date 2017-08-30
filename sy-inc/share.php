<?php if(($bcat['cat_comments'] == "1") && ($date['disable_comments'] == "0")==true){
	$coms = doSQL("ms_comments_settings", "*", "");
	if($coms['use_facebook'] == "1") { ?>
	<script>
	$(document).ready(function(){
		// getcommenttotal('<?php print $date['date_id'];?>');
	});
	</script>
	<?php } ?>
		
<div class="sharecomment" style="text-align: top; position: relative; display: inline;"><span onclick="showcomments();" class="the-icons icon-comment" style="font-size: 30px;"></span>

<span style="verticle-align: super; top: 0; position: absolute; margin-left: -12px;" onclick="showcomments();" id="commenttotal-<?php print $date['date_id'];?>"><?php // print countIt("ms_comments", "WHERE com_table='ms_calendar' AND com_table_id='".$date['date_id']."' AND com_approved='1' ");?></span>
</div>
<?php } ?>

<?php 
$shares = whileSQL("ms_share", "*", "WHERE share_status='1' ");
if($date['page_home'] == "1") { 
	$date['date_link'] = "";
}
while($share = mysqli_fetch_array($shares)) { ?>

<?php if($share['share_id_name'] == "google") { ?>
<script type="text/javascript" src="//apis.google.com/js/plusone.js"></script>
<g:plusone></g:plusone>
<?php } ?>



<?php 
if($share['share_id_name'] == "like") { ?>
<div id="fbLike" class="item"><script src="//connect.facebook.net/<?php print $fb['fb_lang'];?>/all.js#xfbml=1"></script><fb:like show_faces="<?php print $fb['like_show_faces'];?>"  layout="button_count" width="90" font="arial"  colorscheme="<?php print $css['fb_color'];?>" ></fb:like></div>
	<div id="fbShare" class="item"><div class="fb-share-button item" style="padding: 0px; margin: 0px;" data-href="<?php print $setup['url'].$setup['temp_url_folder'].$date['cat_folder']."/".$date['date_link'];?>/" data-layout="button"></div></div>

<?php } 
		if($share['share_id_name'] == "twitter") { ?>
		<div class="item"><a href="//twitter.com/share" class="twitter-share-button" data-count="none" data-via="">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></div>
	<?php } 

		if($share['share_id_name'] == "stumbleupon") { ?>

	<div class="item"><!-- Place this tag where you want the su badge to render -->
<su:badge layout="4"></su:badge>

<!-- Place this snippet wherever appropriate --> 
 <script type="text/javascript"> 
 (function() { 
     var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true; 
     li.src = window.location.protocol + '//platform.stumbleupon.com/1/widgets.js'; 
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s); 
 })(); 
 </script>
</div>
<?php }
if($share['share_id_name'] == "email") { ?>
<div class="item"><a href="mailto:?body=Check out this page: <?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/" target="_blank"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/mail.png" border="0" style="border: 0;"></a></div>
<?php } 

if($share['share_id_name'] == "addthis") { 
	print "<div class=\"item\">".$share['share_code']."</div>";
}
if($share['share_id_name'] == "pinterest") {
	$pinterest_thumb = getPageThumbnail($date,"small");
	$pinterest_descr = strip_tags($date['date_text']);
	$pinterest_descr = preg_replace('/\s\s+/', ' ', $pinterest_descr);
	$pinterest_descr = (substr_replace(strip_tags(trim($pinterest_descr)), "", 400). "");

	?>
	<div class="item"><a href="//pinterest.com/pin/create/button/?url=<?php print $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>&media=<?php print $pinterest_thumb;?>&description=<?php print htmlspecialchars($pinterest_descr);?>" class="pin-it-button" count-layout="none">Pin It</a>
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script></div>
<?php } ?>


<?php } ?>
