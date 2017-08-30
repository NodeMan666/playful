<script>
$(document).ready(function(){
	getcommentpostime();
});
</script>
<div id="commentsbackground" onclick="closecomments();"></div>
<div id="commentspos" style="width: 1px; height: 1px;"></div>
<div id="commentscontainer" class="commentsshowpage">
<div id="commentscontainerinner">
<div id="listComments" >
<?php 
if(($coms['use_standard'] == true)AND($coms['com_location'] == "above")==true)  {
	if($coms['com_form_type'] == "short") { 
		 include $setup['path']."/sy-inc/standard.comments.short.php";
		standardCommentsShort($com_table, $com_table_id, $com_title, $com_link,$com_return_link);
	} else {
		 include $setup['path']."/sy-inc/standard.comments.php";
		standardComments($com_table, $com_table_id, $com_title, $com_link,$com_return_link);
	}
}
?>
<?php if($coms['use_facebook'] == true) { ?>
<style>
.fb_iframe_widget,.fbFeedbackContent, .fb-comments iframe[style] {width: 100% !important;}
</style>
<div style="margin: auto;"><center><fb:comments xid="<?php print $com_table."-".$com_table_id;?>" href="<?php print $com_link;?>" title="<?php print $site_setup['meta_title']." - ".$com_title;?>"  css="<?php print $setup['url'].$setup['temp_url_folder']."/sy-style.php?csst=".$site_setup['css']."&cb=".date('ymdhis')."";?>" simple="1" notify='true'  colorscheme="<?php print $coms['fb_color'];?>"></fb:comments></center></div>
<div>&nbsp;</div>
<div class="cssClear"></div>
<?php } ?>


<?php 
if(($coms['use_standard'] == true)AND($coms['com_location'] == "below")==true) { 
	if($coms['com_form_type'] == "short") { 
		 include $setup['path']."/sy-inc/standard.comments.short.php";
		standardCommentsShort($com_table, $com_table_id, $com_title, $com_link,$com_return_link);
	} else {
		 include $setup['path']."/sy-inc/standard.comments.php";
		standardComments($com_table, $com_table_id, $com_title, $com_link,$com_return_link);
	}
}
?>
</div></div></div>