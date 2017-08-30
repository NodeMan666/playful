<?php adminsessionCheck(); ?>
<?php $date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' "); ?>
<div id="pageTitle"><a href="index.php?do=<?php print $_REQUEST['do'];?>">Site Content</a> 

<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>


<span><?php  if($date['page_home'] == "1") { print "Home Page"; }  else { print "<a href=\"index.php?do=news&action=addDate&date_id=".$date['date_id']."\">".$date['date_title']."</a>"; } ?> </span>



<?php print ai_sep;?>  Splash Window
</div>

<?php
if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
?>
<?php include "news.tabs.php"; ?>
<div id="roundedFormContain">


<?php 
if($_REQUEST['submitit'] == "yes") {
		foreach($_REQUEST AS $id => $value) {
			if(!is_array($_REQUEST[$id])) { 
				$_REQUEST[$id] = addslashes(stripslashes($value));
			}
		}

	updateSQL("ms_calendar", "splash_text='".$_REQUEST['dateembed']."', splash_enable='".$_REQUEST['splash_enable']."', splash_close='".$_REQUEST['splash_close']."' ,  splash_view='".$_REQUEST['splash_view']."' WHERE date_id='".$_REQUEST['date_id']."' ");
	
	if($_REQUEST['splash_default'] == "1") { 
		updateSQL("ms_history", "splash_text='".$_REQUEST['dateembed']."', splash_close='".$_REQUEST['splash_close']."', splash_view='".$_REQUEST['splash_view']."' ");
	}

	$_SESSION['sm'] = "Splash saved";
	header("location: index.php?do=news&action=splash&date_id=".$_REQUEST['date_id']."");
	session_write_close();
	exit();

} else {

?>
<script>
function copyfromhistory() { 
	$("#dateembed").val($("#splash_text_history").val());
	$("#splash_close").val($("#splash_close_history").val());
	$("#splash_view").val($("#splash_view_history").val());

}
</script>
<form method="post" name="emailcustomer" action="index.php"   onSubmit="return checkForm();">
<div class="pc">A splash window is a window that opens up when someone enters the page. You can use this as a welcome message to the page or add HTML, embed code to show a video, etc...
</div>
	<div class="underline">
		<div class="label"><input type="checkbox" class="checkbox" name="splash_enable" value="1" <?php if($date['splash_enable'] == "1") { print "checked"; } ?>> Enable splash window</div>
		<div><a href="" onclick="copyfromhistory(); return false;">Copy code & close text from default</a></div>
	</div>

	<div class="underline">
		<div class="label">Code</div>
		<div><textarea name="dateembed" id="dateembed" cols="40" rows="12" class="field100"><?php print htmlspecialchars(stripslashes($date['splash_text']));?></textarea></div>
	</div>
	<?php		addEditor("dateembed","1", "500", "0"); ?>

	<div class="underline">
		<div class="left p50">
		<div class="label">Close text </div>
		<div><input type="text" name="splash_close" id="splash_close" value="<?php print $date['splash_close'];?>" size="20" ></div>
		</div>
	
		<div class="left p50">
		<div class="label">View text </div>
		<div><input type="text" name="splash_view" id="splash_view" value="<?php print $date['splash_view'];?>" size="20" ></div>
		<div>If you want a link under the page name to view the splash window again after it has been closed, enter the link text here. Example: view splash again. Leave blank to not add a link</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" class="checkbox" name="splash_default" value="1"> Save this as default for future splash windows</div>
	</div>

	<div class="pc center">
	<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
	<input type="hidden" name="do" value="news">
	<input type="hidden" name="action" value="splash">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" class="submit" value="Save" id="submitButton">
	</div>



	</div>
	<div class="clear"></div>
	</form>
	<?php $history = doSQL("ms_history", "*", ""); ?>
	<input type="hidden" name="splash_text_history" id="splash_text_history" value="<?php print htmlspecialchars($history['splash_text']);?>">
	<input type="hidden" name="splash_close_history" id="splash_close_history" value="<?php print htmlspecialchars($history['splash_close']);?>">
	<input type="hidden" name="splash_view_history" id="splash_view_history" value="<?php print htmlspecialchars($history['splash_view']);?>">


<?php } ?>
