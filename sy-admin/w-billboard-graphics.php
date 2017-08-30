<?php require "w-header.php"; ?>
<?php
if(!is_dir("".$setup['path']."/".$setup['graphics_folder']."/billboards")) { 
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
		print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
		print "<li>B";
	} else {
			$perms = 0755;
		print "<li>C";
	}
	mkdir("".$setup['path']."/".$setup['graphics_folder']."/billboards", $perms);
	chmod("".$setup['path']."/".$setup['graphics_folder']."/billboards", $perms);
	$fp = fopen("".$setup['path']."/".$setup['graphics_folder']."/billboards/index.php", "w");
	fputs($fp, "$info\n");
	fclose($fp);
}
?>

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

function closeBackground() { 
	window.parent.$("#selectgraphic").fadeOut('fast', function() { 
		window.parent.$("#selectgraphic").html('<div  class="loading"></div>');
	});
}
function deleteBackground(file,num) { 
	$.get("w-billboard-graphics.php?noclose=1&action=deleteBackground&file="+file, function(data) {
		$("#background-"+num).fadeOut(100);
	});
}

function addnewimageselect(file) { 
	addnewimage(file);
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
//	print "<li>".$setup['path']."/".$setup['graphics_folder']."/billboards/".$_REQUEST['deleteFile']."";
	if(file_exists("".$setup['path']."/".$setup['graphics_folder']."/billboards/".$_REQUEST['file']."")) {
		unlink("".$setup['path']."/".$setup['graphics_folder']."/billboards/".$_REQUEST['file']."");
	}
}

?>
<div class="pc center"><a href="" onclick="closeBackground(); return false;">CLOSE</a></div>
<div class="pc" id="uploadnewbackground"><iframe src="w-billboard-upload-form.php?noclose=1&folder=<?php print $_REQUEST['folder'];?>" frameborder="0" width="100%"></iframe> </div>

<div id="pageTitle">Files</div> 
<div >
<div style= " width: auto; ">


<?php 

$file_exts = array("jpg","jpeg","gif","png","bmp");

if(file_exists("".$setup['path']."/".$setup['graphics_folder']."/billboards")) {
	$theFiles = array();
	$theFolders = array();
	if(!empty($_REQUEST['folder'])) {
		$misc_path = $setup['path']."/".$setup['graphics_folder']."/billboards/".$_REQUEST['folder']."";
	}else {
		$misc_path = $setup['path']."/".$setup['graphics_folder']."/billboards";
	}
	$dir = opendir($misc_path); 
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..") && ($file!= "index.php")) {
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
		print "<div>&nbsp;</div>";
	}
if($file_count<=0) {
	print "<center>No files have been uploaded here.</center>";
} else {

?>
<div>

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
		<div class="underline" id="background-<?php print $this_count;?>" onmouseover="document.getElementById('delete-<?php print $this_count;?>').style.display = 'block';"  onmouseout="document.getElementById('delete-<?php print $this_count;?>').style.display = 'none';">
		<?php
		$file_show = $file;
		if(!empty($_REQUEST['folder'])) {
			$file = $_REQUEST['folder']."/".$file;
		}
		?>
	<div style="width:10%; float: left;">
	&nbsp;
	<span id="delete-<?php print $this_count;?>" style="display: none; float: left; margin-left: 2px; "> <a href="javascript:deleteBackground('<?php print $file;?>','<?php print $this_count;?>')"  onClick="return confirm('Are you sure you want to delete this file?');"><?php print ai_delete;?></a></span>
	</div>


	<div style="width:90%; float: left;">
	<?php
		$size = GetImageSize("".$setup['path']."/".$setup['graphics_folder']."/billboards/".$file.""); ?>
		<a href="" onClick="addnewimageselect('<?php print "/".$setup['graphics_folder']."/billboards/".$file;?>'); return false;">
		<?php print "<img src=\"/".$setup['graphics_folder']."/billboards/$file\""; if($size[1] > 50) { print  " style=\"height: 50px;\""; } print " border=\"0\" title=\"".$file_show."\">"; ?></a>
		<br>
		<a href="" onClick="addnewimageselect('<?php print "/".$setup['graphics_folder']."/billboards/".$file;?>'); return false;"><?php print $file;?></a>

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