<?php  $cat = doSQL("".cat_table."", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); 	?>
<div>&nbsp;</div>
<div class="pc"><span style="font-size: 34px; color: #12B33F;">Your new section "<?php print $cat['cat_name'];?>" has been created!</span></div>
<div class="pc" style="font-size: 21px; color: #000000;">&larr; You will now see your new section list to the left. There you can edit the details of that section, add a new page, add it to your menu and view it on the website.</div>
<div class="pc" style="font-size: 17px; color: #000000;">What would you like to do now? <br><br>
<a href="index.php?do=news&action=addDate&date_cat=<?php print $cat['cat_id'];?>">Add a page to this section</a><br><br>
<a href="index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>">Edit details of this section</a><br><br>
<a href="index.php?do=news&action=editCategory">Create another new section</a><br><br>
</div>

<div>&nbsp;</div>
<?php if($_SESSION['cat_folder_exists'] == true) { ?>
<div class="pc">
<h1 style="font-size: 30px; color: #890000;">WARNING - Folder Exists</h1>

Your new section has been created, however the folder "<?php print $_SESSION['existing_folder'];?>" already exists on the server. So this section folder was named "<?php print $cat['cat_folder'];?>". This is part of the URL (<?php print $setup['url'].$setup['temp_url_folder']."".$setup['content_folder']."".$cat['cat_folder'];?>).
<br><br>
You most likey want to rename this folder. <a href="" onclick="openFrame('w-category-folder.php?cat_id=<?php print $cat['cat_id'];?>'); return false;">Click here to rename this directory.</a>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

<?php 
unset($_SESSION['cat_folder_exists']);
unset($_SESSION['existing_folder']);

} ?>
