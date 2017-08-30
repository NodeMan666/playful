<style>
	#sideMenuContainer { 
	display: none !important;
}
#pageContentContainer { 
	width: 100%;
}
</style>
<?php
	if($_REQUEST['action'] == "checkpass") {
		$ckdate =  doSQL("ms_blog_categories", "*", "WHERE MD5(cat_id)='".$_REQUEST['ppdid']."' ");
	if($_REQUEST['gpass'] == $ckdate['cat_password']) {
		if(!is_array($_SESSION['privateCatAccess'])) {
			$_SESSION['privateCatAccess'] = array();
		}
		array_push($_SESSION['privateCatAccess'], $ckdate['cat_id']);
		header("location: index.php");
		session_write_close();
		exit();
	} else {
		print "<div class=errorMessage>"._private_gallery_password_incorrect_."</div>";
	//	catPassword($date_id);
//		exit();
	}
}

function catPassword($cat_id) {
	global $site_type,$setup,$site_setup;
	?>
	<center><div style="margin: auto;"><div class="pageContent"><h1><?php print _private_gallery_enter_password_;?></h1></div>
	<form method="post" name="galpass" action="index.php" style="padding: 0; margin: 0;">
	<div class="pageContent">
	<input type="password" name="gpass" size="15">
	<input type="hidden" name="action" value="checkpass">
	<input type="hidden" name="ppdid" value="<?php print MD5($cat_id);?>">
	<input  type="submit" name="submit" class="submit" value="<?php print _private_gallery_submit_password_;?>">
	</div>
	<div class="pageContent"><?php print _private_gallery_text_;?></div>
	</form>
	</div></center>
	<?php
	if($site_type !== "fullscreen") {  
		include $setup['path']."/sy-footer.php";
	}
}
?>


<div class="photoMessageContainer" id="photoMessageContainer"><div class="photoMessage" id="photoMessage"> Please try again</div></div>

<?php 

	$pics_where = "WHERE bp_blog='".$date['date_id']."' ";
	$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id";

if(($gallery['gal_status'] == "0")AND($page_gallery !== true)==true) {
	print "<div class=errorMessage>An error has occured</div>";
	include $setup['path']."/sy-footer.php";
	exit();
}
