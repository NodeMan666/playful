<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>


<?php 
if($_REQUEST['action'] == "save") { 
	updateSQL("ms_social_links", "link_status='".addslashes(stripslashes($_REQUEST['link_status']))."' ,link_url='".addslashes(stripslashes($_REQUEST['link_url']))."'  WHERE link_id='".$_REQUEST['link_id']."' ");
	$_SESSION['sm'] = "Link Saved";
	header("location: index.php?do=look&view=social");
	session_write_close();
	exit();
}
?>


<?php 
if(!empty($_REQUEST['link_id'])) { 
	$link = doSQL("ms_social_links", "*", "WHERE link_id='".$_REQUEST['link_id']."' ");
	$_REQUEST['link_text'] = $link['link_text'];
	$_REQUEST['link_url'] = $link['link_url'];
	$_REQUEST['link_order'] = $link['link_order'];
	$_REQUEST['link_open'] = $link['link_open'];
	$_REQUEST['link_status'] = $link['link_status'];
	$_REQUEST['link_location'] = $link['link_location'];
	$_REQUEST['link_page'] = $link['link_page'];
	$_REQUEST['link_login_page'] = $link['link_login_page'];
	if($link['link_cat'] > 0) { 
		$_REQUEST['link_page'] = "||".$link['link_cat'];
	}
}
?>

<div class="pc"><h1>Social Link: <?php print $link['link_text'];?></div>


	<div id="roundedForm">
			<form method="post" name="newLink" action="w-social-link.php" style="padding:0; margin:0;"   onSubmit="return checkForm();">
			<div class="row"><input type="checkbox" name="link_status" value="1" <?php if(($_REQUEST['link_status']=="1")OR($link['link_id'] <=0)==true) { print "checked"; } ?>> Check this box to activate this link</div>

			<div class="row">
				<div>Enter in the link to your <?php print $link['link_text'];?> page. Be sure it started with the http://</div>
				<div><input type="text" name="link_url" id="link_url"  value="<?php print $link['link_url']; ?>" size="40" class="field100"></div>
			</div>


			<div class="row" style="text-align: center;">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="link_id" value="<?php print $_REQUEST['link_id'];?>">
			<input type="hidden" name="from_menu" value="<?php print $_REQUEST['from_menu'];?>">
			<input type="submit" name="submit" value="Save Link" class="submit" id="submitButton">

			<div class="cssClear"></div>
		</div>

	</form></div>


	<div >&nbsp;</div>
</div>
<?php require "w-footer.php"; ?>