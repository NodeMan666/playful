<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>
<?php adminsessionCheck(); ?>
<?php $cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['cat_id']."' "); ?>

<div class="pc"><h1>Rename Category Folder For <?php print $cat['cat_name'];?></h1></div>
<div class="pc">This allows you to rename the folder for this category which is part of the URL</div>

<?php 
if($_REQUEST['action'] == "save") { 


	$page_link = stripslashes(trim(strtolower($_REQUEST['new_folder'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);

	if($cat['cat_under'] > 0) {
		$up_folder = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$cat['cat_under']."' ");
		$page_link = "".$up_folder['cat_folder']."/".$page_link;
	} else { 
		$page_link = "/".$page_link;
	}

	if($page_link == $cat['cat_folder']) { 
		print "<div class=\"pc\"><h3 style=\"color: #890000;\">This is the same folder name, nothing changed.</h3></div>";
		changeForm($error);
	} else { 
		if(file_exists($setup['path']."".$setup['content_folder']."".$page_link)) {

			if($_REQUEST['rename_old'] == "1") { 
				$old = $setup['path']."".$setup['content_folder']."".$page_link;
				$new = $setup['path']."".$setup['content_folder']."".$page_link."-".date("Y-m-d");
				rename($old,$new);

				$old = $setup['path']."".$setup['content_folder']."".$cat['cat_folder'];
				$new = $setup['path']."".$setup['content_folder']."".$page_link;
				rename($old,$new);
				updateSQL("ms_blog_categories", "cat_folder='$page_link' WHERE cat_id='".$cat['cat_id']."' ");
				$_SESSION['sm'] = "Category folder successfully renamed to $page_link";
				?>
				<script>
				parent.window.location.href = "index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>";
				</script>
				<?php 
				exit();

			} else { 
			$error = $page_link;
			changeForm($error);
			}
		} else { 
			$old = $setup['path']."".$setup['content_folder']."".$cat['cat_folder'];
			$new = $setup['path']."".$setup['content_folder']."".$page_link;
			rename($old,$new);
			updateSQL("ms_blog_categories", "cat_folder='$page_link' WHERE cat_id='".$cat['cat_id']."' ");
				$_SESSION['sm'] = "Category folder successfully renamed to $page_link";
				?>
				<script>
				parent.window.location.href = "index.php?do=news&action=editCategory&cat_id=<?php print $cat['cat_id'];?>";
				</script>
				<?php 
				exit();


		}
	}
} else { 
	changeForm($error);
}
?>

<?php function changeForm($error) { 
	global $setup,$cat;
	?>
	<?php if(!empty($error)) { ?>
<div class="pc" >
<h2 style="color: #890000;" >Folder: "<?php print $error;?>" exists</h2>
The folder you are trying to rename to <?php print $error;?> already exists. <a href="<?php print $error;?>" target="_blank">Here is a link to that existing folder</a>.
<br><br>
Enter in a different new folder name.</div>
<div>&nbsp;</div>
	<?php } ?>
<form method="post" name="newfolder" action="w-category-folder.php"   onSubmit="return checkForm();">
<div id="roundedForm">
<div class="row">Current Folder: <?php print $setup['url'].$setup['temp_url_folder']."".$cat['cat_folder'];?></div>
<div class="row">New folder: 
<?php 
$page_link = explode("/",$cat['cat_folder']);
$fds = count($page_link) - 1;
	if(!empty($page_link[$fds])) { 
		$link = $page_link[$fds];
		$x = 0;
		while($x < $fds) { 
			$folder .= $page_link[$x]."/";
			$x++;
		}
	} else {
		$link = $page_link[0];
	}
print $setup['url'].$setup['temp_url_folder']."".$folder;
if(empty($error)) { 
	$_REQUEST['new_folder'] = $link;
}
?>

<input type="text" name="new_folder" id="new_folder" size="20" class="required" value="<?php print $_REQUEST['new_folder'];?>"></div>
<?php if(!empty($error)) {
	$sytistcat = doSQL("ms_blog_categories", "*", "WHERE cat_folder='$error' AND cat_id!='".$cat['cat_id']."' ");
	if(!empty($sytistcat['cat_id'])) { ?>
<div class="row">The folder <?php print $error;?>" is part of your sytist website and can not be renamed. Enter a different name above to rename this category folder.</div> 

	<?php } else { ?>

<div class="row"><input type="checkbox" name="rename_old" value="1"> Check this to rename the existing "<?php print $error;?>" folder as a backup (<?php print $error;?>-<?php print date('y-m-d');?>).</div> 
<?php } ?>
<?php } ?>

<input type="hidden" name="action" value="save">
<input type="hidden" name="cat_id" value="<?php print $cat['cat_id'];?>">

<div class="row center"><input type="submit" name="submit" value="Rename" class="submit"></div>
</div>
</form>
<?php } ?>




<?php require "w-footer.php"; ?>