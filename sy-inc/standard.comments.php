<?php function standardComments($com_table, $com_table_id, $com_title, $com_link,$com_return_link) {
global $setup,$site_setup,$coms;
	$coms = doSQL("ms_comments_settings", "*", "");

?>
<div id="commentsform">
<?php if(!empty($_SESSION['com_message'])) { ?><div class="pageContent" id="success"><div class="successMessage"><?php print $_SESSION['com_message']; unset($_SESSION['com_message']); ?></div></div><?php } ?>
<?php if(customerLoggedIn()) { 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}
?>
	<div class="pc"><h2><?php print _leave_comment_title_; ?></h2></div>
	<div class="pc"><?php print _leave_comment_text_; ?></div>

	<div id="theFormError" style="display: none;"></div>
	<div>
	<form method="post" name="theForm" action="<?php print $setup['url'].$setup['temp_url_folder'];?>/sy-inc/sy-make-comment.php"  onsubmit="makecomment('makecomment'); return false;">
		<input type="hidden" name="com_table" id="com_table" value="<?php print $com_table;?>" class="makecomment">
	<input type="hidden" name="com_table_id" id="com_table_id" value="<?php print $com_table_id;?>" class="makecomment">
	<input type="hidden" name="com_title" id="com_title" value="<?php print $com_title;?>" class="makecomment">
	<input type="hidden" name="com_link" id="com_link" value="<?php print $com_link;?>" class="makecomment">
	<input type="hidden" name="com_ip" id="com_ip" value="<?php print $com_table;?>" class="makecomment">
	<input type="hidden" name="com_return_link" id="com_return_link" value="<?php print $com_return_link;?>" class="makecomment">
	
	<div style="display: none">
	If you can read this, don't fill out the following 3 fields.
	<input type="text" name="address" id="address"  class="makecomment" value="http://"><br>
	<input type="text" name="contact" value="" id="contact"  class="makecomment"><br>
	<textarea cols="40" rows="6" name="comment" id="comment"  class="makecomment"></textarea>
	</div>	

	<input type="hidden" name="action" value="redeem" id="action"  class="makecomment">
<div style="width: 49%; float: left;" class="nofloatsmallleft">

		<div class="pageContent"><div><?php print _leave_comment_name_;  ?></div>
		<input type="text" name="d_n" id="d_n" size="25" style="width: 98%;" TABINDEX=1 maxlength="40" value="<?php if(!empty($p['p_id'])) { print $p['p_name']." ".$p['p_last_name']; }  ?>" class="makecomment comrequired"></div>
		<div class="pageContent commentwebsite"><div><?php  print _leave_comment_website_;  ?></div><div>
		<input type="text" name="d_w" id="d_w" size="25" style="width: 98%;" TABINDEX=3  value="<?php 	if(isset($_COOKIE['c_d_w'])) { print $_COOKIE['c_d_w']; } ?>" class="makecomment"></div>
		</div>
</div>
	<div style="width: 49%; float: right;" class="nofloatsmallleft">

			<div class="pageContent"><div><?php  print _leave_comment_email_;  ?></div>
		<div><input type="text" name="d_e" id="d_e" size="25" style="width: 98%;" TABINDEX=2  value="<?php if(!empty($p['p_id'])) { print $p['p_email']; }  ?>" class="makecomment comrequired"></div>
		</div>
</div>

<div class="cssClear"></div>

<div class="pageContent" style="vertical-align: middle;">
	<div><?php print _leave_comment_comment_;  ?></div>
	<div><textarea name="d_m" id="d_m"  cols="40" rows="6" style="width: 99%;" TABINDEX=4  class="makecomment comrequired"></textarea></div>
	<div class="pageContent" style="vertical-align: middle;">
	<!-- 
		<div style="text-align: right; float: left;vertical-align: middle;">
		<input type="checkbox" name="d_r" value="1" <?php 	if(isset($_COOKIE['c_d_e'])) { print "checked"; } ?>> remember me
		</div>
		-->
		<div style=" float: right; text-align: left;vertical-align: middle;">

	<?php
		$fn = rand(1,4);
		$ln = rand(1,4);
		$total = $fn+$ln;
		$_SESSION['humanTotal'] = $total;
		$_SESSION['fn'] = $fn;
		$_SESSION['ln'] = $ln;
	?>
	<?php print $_SESSION['fn']." + ".$_SESSION['ln'];?> = <input type="text" size="2" name="d_h"  id="d_h" value="" TABINDEX=5  class="makecomment comrequired">
	</div>
	<div class="cssClear"></div>
	</div>







	<div class="pageContent" style="text-align: right;">
	<input  type="submit" name="submit" value="<?php print _leave_comment_button_;?>" class="submit"  TABINDEX=6>
	</div>


<div class="cssClear"></div>
	<?php 	if((trim($_SESSION['office_admin_login']) == "1") AND(!empty($_SESSION['office_admin']))==true) {print "<div class=\"pageContent\"><i>You are logged into the admin so your comments will automatically be posted.</i></div>"; } ?>
	
	</div></form>
	</div>

<div>&nbsp;</div>
</div>
	<div id="commentapproved" class="hide success"><?php print _leave_comment_approved_message_;?></div>
	<div id="commentpending" class="hide success"><?php print _leave_comment_success_message_;?></div>
	<div id="commenterror" class="hide error">There was an error with adding your comment</div>

<div id="listStandardCommentsFull">
<?php
		$comments = whileSQL("ms_comments", "*,date_format(DATE_ADD(com_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS com_date", "WHERE com_table='$com_table' AND com_table_id='$com_table_id' AND com_approved='1' ORDER BY com_id ".$coms['order_by']." ");
		$total_comments = mysqli_num_rows($comments);
		?>
	<div class="pageContent"><h2><?php print _leave_comment_comments_;	if($total_comments > 0) { print " ($total_comments)"; } ?></h2></div>
	<?php
		if($total_comments <= 0) { print " <div class=\"pageContent\">"._leave_comment_no_comments_."</div>"; } ?>
		<?php 
		while($comment = mysqli_fetch_array($comments)) { ?>
		<div class="showComment">
		<div class="pc"><h3 style="display: inline;"><?php if(!empty($comment['com_website'])) { print "<a href=\"http://".str_replace("http://", "", $comment['com_website'])."\" target=\"_blank\">".stripslashes($comment['com_name'])."</a>"; } else { print $comment['com_name']; }?></h3> on <?php print $comment['com_date'];?></div>
		<div class="pc">
		<?php if(!empty($comment['com_comment'])) {  print nl2br(stripslashes($comment['com_comment'])); } ?></div>
		</div>
	<?php } ?>


</div>
<div class="cssClear"></div>





<?php } ?>
