<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
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
}
adminsessionCheck();
if($_REQUEST['action'] == "upload") {
	include "upload.misc.process.php";
	exit();
} elseif($_REQUEST['action'] == "newFolder") {
	createNewFolder();

} elseif($_REQUEST['action'] == "deleteBackground") {
	deleteBackground();
}

function deleteBackground() {
	global $_REQUEST,$setup;
	if($setup['demo_mode'] !== true) {
		if(file_exists("".$setup['path']."/".$setup['graphics_folder']."/backgrounds/".$_REQUEST['file']."")) {
			unlink("".$setup['path']."/".$setup['graphics_folder']."/backgrounds/".$_REQUEST['file']."");
		}
	}
}

?>
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
<div class="left" style="margin-right: 16px;"><a href="" onClick="selectBackground(); return false;" class="bluelink">Apply Background</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="clearBackground(); return false;" class="bluelink">Clear Background</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="swapDisplay('uploadnewbackground'); return false;" class="bluelink">Upload</a></div>
<div class="left" style="margin-right: 16px;"><a href="" onClick="cancelBackground(); return false;" class="bluelink">Cancel</a></div>
<div class="clear"></div>
<div class="pc">Click on the backgrounds below to preview and select the background styling above. Once you have decided on a background, click Add Background above.</div>
</div>
</div>
<div style=" background: #FFFFFF; height: 100px;">&nbsp;</div>
<div class="pc" id="uploadnewbackground" style="display: none;"><iframe src="w-backgrounds-upload-form.php?noclose=1&folder=<?php print $_REQUEST['folder'];?>" frameborder="0" width="100%"></iframe> </div>

<div id="pageTitle">Backgrounds</div> 
<div >
<div id="roundedFormContain" style= " width: auto; ">


<?php 

$file_exts = array("jpg","jpeg","gif","png","bmp");

if(file_exists("".$setup['path']."/".$setup['graphics_folder']."/backgrounds")) {
	$theFiles = array();
	$theFolders = array();
	if(!empty($_REQUEST['folder'])) {
		$misc_path = $setup['path']."/".$setup['graphics_folder']."/backgrounds/".$_REQUEST['folder']."";
	}else {
		$misc_path = $setup['path']."/".$setup['graphics_folder']."/backgrounds";
	}
	$dir = opendir($misc_path); 
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..")) {
			$file_count++;
			if(is_dir($misc_path."/".$file)) {
//				print "<li>Folder: <a href=\"photos/misc/$file\">$file</a>";
				array_push($theFolders, $file);
			} else {
//				print "<li>File: <a href=\"photos/misc/$file\">$file</a>";
				array_push($theFiles, $file);
			}
//			print "<li><a href=\"photos/misc/$file\">$file</a>";
		}
	}
	closedir($dir); 
}
	if(!empty($_REQUEST['folder'])) {
		print "<div class=\"pc\"><h1>Folder: ".$_REQUEST['folder']."</h1></div>";
		if($_REQUEST['folder'] == "subtlepatterns") { 
			print "<div class=\"pc\">The following patterns are credited to SubtlePatterns.com</div>";
		}
		print "<div>&nbsp;</div>";
	}
if($file_count<=0) {
	print "<center>No files have been uploaded here.</center>";
} else {

?>
<div id="roundedForm">

<?php 
	if(!empty($_REQUEST['folder'])) {
?>
		<div class="row">
			<div ><h2><a href="" onClick="openBackgroudFolder(''); return false;"><?php print ai_folder_up;?> Up a level</a></h2></div>
		</div>
<?php	} ?>

<?php 	
	asort($theFolders);
	foreach($theFolders AS $id => $file) {
		?>
		<div class="row">
			<div><h2><a href="" onClick="openBackgroudFolder('<?php print $file;?>'); return false;"><?php print ai_folder;?>  <?php print $file;?></a></h2></div>
		</div>
	<?php } ?>

<?php 
	asort($theFiles);
	foreach($theFiles AS $id => $file) {
		$this_count++;
		?>
		<div class="row" id="background-<?php print $this_count;?>" onmouseover="document.getElementById('delete-<?php print $this_count;?>').style.display = 'block';"  onmouseout="document.getElementById('delete-<?php print $this_count;?>').style.display = 'none';">
		<?php
		$file_show = $file;
		if(!empty($_REQUEST['folder'])) {
			$file = $_REQUEST['folder']."/".$file;
		}
		?>
	<div style="width:10%; float: left;">
	&nbsp;
	
	<span id="delete-<?php print $this_count;?>" style="display: none; float: left; margin-left: 2px; "> <?php if($setup['demo_mode'] !== true) { ?><a href="javascript:deleteBackground('<?php print $file;?>','<?php print $this_count;?>')"  onClick="return confirm('Are you sure you want to delete this file?');"><?php print ai_delete;?></a><?php } ?></span>
	</div>


	<div style="width:40%; float: left;">
	<?php
		$size = GetImageSize("".$setup['path']."/".$setup['graphics_folder']."/backgrounds/".$file.""); ?>
		<a href="" onClick="previewBackground('<?php print "".$setup['temp_url_folder']."/".$setup['graphics_folder']."/backgrounds/".$file;?>'); return false;"> <?php print "<img src=\"".$setup['temp_url_folder']."/".$setup['graphics_folder']."/backgrounds/$file\""; if($size[0] > 120) { print  " width=120px;"; } print " title=\"".$file_show."\">"; ?></a>
	</div>
	<div style="width:50%; float: left;">
</div>
			<div class="cssClear"></div>
		</div>


<?php 
	}

}
	?>
</div>
</div>


<?php 
function file_extension($filename)
   {
       $path_info = pathinfo($filename);
       return $path_info['extension'];
}
   ?>

<?php require "w-footer.php"; ?>