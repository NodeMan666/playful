<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['submitit'])) {

	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		//	print "<li>$id = $value";
		}


	updateSQL("ms_comments_settings", "news_section ='".$_REQUEST['news_section']."',  
	pages ='".$_REQUEST['pages']."', 
	photos='".$_REQUEST['photos']."', 
	use_facebook='".$_REQUEST['use_facebook']."', 
	use_standard ='".$_REQUEST['use_standard']."' , 
	com_form_type ='".$_REQUEST['com_form_type']."' , 
	com_location='".$_REQUEST['com_location']."' , 
	auto_post ='".$_REQUEST['auto_post']."', 
	require_comment ='".$_REQUEST['require_comment']."' , 
	order_by ='".$_REQUEST['order_by']."'  , 
	email_new_comments ='".$_REQUEST['email_new_comments']."',
	fb_color='".$_REQUEST['fb_color']."' ");   		



		$_SESSION['sm'] = "Settings saved";
		session_write_close();
		header("location: index.php?do=comments&view=settings");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	$com = doSQL("ms_comments_settings", "*", ""); 
	$fb = doSQL("ms_fb", "*", "  ");
	$lang = doSQL("ms_language", "*", "  WHERE lang_default='1' ");
	?>
<div id="pageTitle"><a href="index.php?do=comments">Comments</a> <?php print ai_sep;?> Settings</div>
<div class="pageContent">
The Comments feature shows a comment form on pages  where visitors can leave a comment on the page.<br><br>
There are 2 types of comment systems: Facebook & Standard. Facebook comments show the Facebook comment form and they have the option to publish on their page. The standard comments is an internal comment system.<br><br>
<u>When enabling the comments system, you will also have to make sure it is selected in the sections you want to use them. Edit the sections and check the comments section.</u></div>
<div>&nbsp;</div>
<div id="roundedFormContain">
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;">
<div style="width: 48%; float: left;">



	<div id="roundedForm">
		<div class="label">Comment system types</div>
		<div class="row"><input type="checkbox" name="use_facebook" value="1" <?php if($com['use_facebook'] == "1") { print "checked"; } ?>> Facebook Comments</div>
		<div class="row"><input type="checkbox" name="use_standard" value="1" <?php if($com['use_standard'] == "1") { print "checked"; } ?>> Standard Comments</div>
	</div>
<div>&nbsp;</div>
<!-- 
		<div class="label">Standard Comments Form</div>
		<div class="row">
		<input type="radio" name="com_form_type" value="short" <?php if($com['com_form_type'] == "short") { print "checked"; } ?>> Short Form <br>
		<input type="radio" name="com_form_type" value="long" <?php if($com['com_form_type'] == "long") { print "checked"; } ?>> Long Form
	</div>
	-->
	<div id="roundedForm">

		<div class="label">Standard Comments Location</div>

		<div class="row">
		<input type="radio" name="com_location" value="above" <?php if($com['com_location'] == "above") { print "checked"; } ?>> Above Facebook Comments  <br>
		<input type="radio" name="com_location" value="below" <?php if($com['com_location'] == "below") { print "checked"; } ?>> Below Facebook Comments 
	</div>

</div>
<div>&nbsp;</div>


	<div id="roundedForm">

		<div class="label">Facebook Comments Color Scheme</div>

		<div class="row">
		<input type="radio" name="fb_color" value="light" <?php if($com['fb_color'] == "light") { print "checked"; } ?>> Light  <br>
		<input type="radio" name="fb_color" value="dark" <?php if($com['fb_color'] == "dark") { print "checked"; } ?>> Dark 
	</div>

</div>
<div>&nbsp;</div>



</div>

<div style="width: 48%; float: right;">

	<div id="roundedForm">
		<div class="label">Standard Comments Options</div>
		<div class="row"><input type="checkbox" name="email_new_comments" value="1" <?php if($com['email_new_comments'] == "1") { print "checked"; } ?>> Email me when someone makes a comment.</div>
		<div class="row"><input type="checkbox" name="auto_post" value="1" <?php if($com['auto_post'] == "1") { print "checked"; } ?>> Auto post unregistered visitors  comments. If unchecked, you will  manually approve comments posted.</div>
		<!-- <div class="row"><input type="checkbox" name="require_comment" value="1" <?php if($com['require_comment'] == "1") { print "checked"; } ?>> Require a comment to be entered to submit.</div> -->
</div>
<div>&nbsp;</div>

	<div id="roundedForm">
		<div class="label">Display order of site comments</div>

		<div class="row">
		<input type="radio" name="order_by" value="DESC" <?php if($com['order_by'] == "DESC") { print "checked"; } ?>> Newest First  <br>
		<input type="radio" name="order_by" value="ASC" <?php if($com['order_by'] == "ASC") { print "checked"; } ?>> Oldest First 
	</div>


</div>
</div>
<div class="cssClear"></div>

<div>&nbsp;</div>
<div class="pageContent" style="text-align: center;">
	<input type="hidden" name="do" value="comments">
	<input type="hidden" name="view" value="settings">
		<input type="hidden" name="submitit" value="yup">

		<input  type="submit" name="submit" value="Update Settings" class="submit">
	</center>
</div>
			</form></div>


<div>&nbsp;</div>
<div>&nbsp;</div>

<?php  } ?>
