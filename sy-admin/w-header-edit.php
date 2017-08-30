<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<style>#framewindow { padding: 0; } </style>
<link rel="stylesheet" href="js/redactor/redactor.css" />
<script src="js/redactor/redactor.js"></script>

<script language="javascript" src="theme-edit.js" type="text/javascript"></script>
<script>
function previewBackground(file) { 
	$("#outside_bg_image").val(file);
}
function selectBackground() { 
	$("#outside_bg_image_old").val($("#outside_bg_image").val());
	$("#bg_image_style_old").val($("#bg_image_style").val());
	$("#bgphotocontainer").html("<img src='"+$("#outside_bg_image").val()+"' style='height: 50px; width: auto;'>");
	closeBackground();
}
function clearBackground() { 
	$("#outside_bg_image").val('');
	$("#bgphotocontainer").html("");

	closeBackground();
}

function cancelBackground(file) { 
	$("#outside_bg_image").val($("#outside_bg_image_old").val());
	$("#bg_image_style").val($("#bg_image_style_old").val());
	closeBackground();
}


</script>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>

<?php
if($_REQUEST['action'] == "upload") {
	include "look/upload.misc.process.php";
	exit();
}
?>

<?php 
if($_REQUEST['action'] == "save") { 
	updateSQL("ms_settings", "header='".addslashes(stripslashes($_REQUEST['header_html']))."' ");
	$new_html = stripslashes($_REQUEST['header_html']);
//	$new_html = str_replace("'", "&#39;", $new_html);
//	$new_html = str_replace('"',"&#34;",$new_html);
	$new_html = str_replace(array("\r\n","\n\r","\r", "\n"), '', addslashes($new_html));
	?>
	<script>
		newhtml = "<?php print $new_html;?>";
//		var escapedString = newhtml.replace(/'/g, "&apos;").replace(/"/g, "&quot;");

//		alert(newhtml);
	window.parent.$("#headercontent").html(newhtml);
	closeHeader();
	</script>
<?php  
		exit();
}


?>
<!-- 
<div style=" background: #f4f4f4; border-bottom: solid 1px #c4c4c4; position: fixed; top: 5%;  width: 600px; left: 50%; margin-left: -299px;">
<div style="padding: 8px;">
<div class="left" style="margin-right: 16px;"><select name="bg_image_style_select" id="bg_image_style_select" onChange="backgroundStyle();">
<option value="">Select Background Styling</option>
<option value="repeat" <?php if($css['bg_image_style'] == "repeat") { print "selected"; } ?>>Tiled</option>
<option value="repeat-x" <?php if($css['bg_image_style'] == "repeat-x") { print "selected"; } ?>>Repeat horizontally</option>
<option value="repeat-y" <?php if($css['bg_image_style'] == "repeat-y") { print "selected"; } ?>>Repeat vertically </option>
<option value="top center no-repeat" <?php if($css['bg_image_style'] == "top center no-repeat") { print "selected"; } ?>>Top center</option>
<option value="no-repeat center center fixed" <?php if($css['bg_image_style'] == "no-repeat center center fixed") { print "selected"; } ?>>Fixed center</option>
<option value="no-repeat top center fixed" <?php if($css['bg_image_style'] == "no-repeat top center fixed") { print "selected"; } ?>>Fixed top center</option>
<option value="topcover" <?php if($css['bg_image_style'] == "topcover") { print "selected"; } ?>>Top cover</option>
<option value="centercover" <?php if($css['bg_image_style'] == "centercover") { print "selected"; } ?>>Center cover</option>
</select>
</div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="selectBackground(); return false;">Apply Background</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="clearBackground(); return false;">Clear Background</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="swapDisplay('uploadnewbackground'); return false;">Upload</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="cancelBackground(); return false;">Cancel</a></div>
<div class="clear"></div>
<div class="pc">Click on the backgrounds below to preview and select the background styling above. Once you have decided on a background, click Add Background above.</div>
</div>
</div>
<div style=" background: #FFFFFF; height: 100px;">&nbsp;</div>
<div class="pc" id="uploadnewbackground" style="display: none;"><iframe src="w-backgrounds-upload-form.php?noclose=1&folder=<?php print $_REQUEST['folder'];?>" frameborder="0" width="100%"></iframe> </div>

<div id="pageTitle">Backgrounds</div> 
<div >
<div id="roundedFormContain" style= " width: auto; ">
-->
<?php 
if($_REQUEST['height'] < 200) { 
	$_REQUEST['height'] = 200;
}
?>
<form method="post" name="headersds" action="w-header-edit.php">
<input type="hidden" name="action" value="save">
<input type="hidden" name="noclose" value="1">
<input type="hidden" name="from_theme" value="1">
<input type="hidden" name="height" value="<?php print $_REQUEST['height'];?>">
<div class="pc center"><input type="submit" name="submit" value="Save & Apply" class="submit"> &nbsp; <a href="" onClick="closeHeader(); return false;" class="submit">Cancel & Close</a></div>
<div class="pc center">
<textarea name="header_html" id="header_html" cols="40" rows="12" style="width: 98%;"><?php if(!empty($_SESSION['new_logo'])) { print htmlspecialchars(stripslashes($_SESSION['new_logo'])); unset($_SESSION['new_logo']); } else { print htmlspecialchars(stripslashes($site_setup['header'])); } ?></textarea>
<?php if($site_setup['html_editor'] !=="1") { ?>
	<?php addEditor("header_html", "1", "".$_REQUEST['height']."", "1"); ?>
<?php } ?>
</div>
</form>

<div class="pc"> This header is applied to all themes. You have more options by going to Design -> Header & footer.</div>
<?php if(!empty($_SESSION['smerror'])) {?><div class="error">Unable to upload file: FILE EXISTS. There is already a file with that name and overwrite file was not checked. File not uploaded</div><?php unset($_SESSION['smerror']); } ?>

<form name="upitprev" id="upitprev"  method="POST" action="index.php" enctype="multipart/form-data" >
<input type="hidden" name="noclose" value="1">
<input type="hidden" name="from_theme" value="1">
<input type="hidden" name="height" value="<?php print $_REQUEST['height'];?>">
<div class="pageContent">
<div class="pageContent"><h2>Upload Logo</h2></div>

<div id="roundedForm">
<div class="row">This allow you to easily upload a logo to add to your header section of your website. Once you upload it, it will be saved to the header section below. You can also insert a logo in the text editor below by clicking the  photo icon.
</div>
<?php 
$line_count = 3;
?>
<div class="row">
<div id="upitprev_error" style="display: none; margin-bottom: 10px;">Error ....</div>
<div style="float: left; padding: 0 20px 0 0;"><input type="file" name="image" size="40" id="image" ></div>

<div style="float: left; padding: 0 20px 0 0;">
<input type="hidden" name="do" value="look">
<input type="hidden" name="view" value="miscFiles">
<input type="hidden" name="logo" value="yes">
<input type="hidden" name="action" value="upload">
<input type="submit" name="submission" id="submission" onClick="return  checkPreviewUpload(this)" value="Upload File" class="submitSmall">
</div>

<div class="cssClear"></div>
</div>

<div class="row">
<div style="float: left; padding: 0 20px 0 0;"><input type=checkbox name="overwrite"> Overwrite existing file with same file name</div>
<div style="float: left; padding: 0 20px 0 0;"><input type=checkbox name="replace" value="yes"> Replace current header content<br><span class="muted">Otherwise it will add the logo before existing content.</span></div>
<div class="cssClear"></div>
</div>
<div class="row">You can upload jpg, png & gif files. Be sure your logo isn't too large. A recommended height for your logo is no more than 150 pixels. You may need to resize your logo file before uploading.</div>
</div>
<div>&nbsp;</div>
</form>
</div>

<?php require "w-footer.php"; ?>