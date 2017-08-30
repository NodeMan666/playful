<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
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

} elseif(!empty($_REQUEST['deleteFile'])) {
	deleteFile();
}

function deleteFile() {
	global $_REQUEST,$setup;
//	print "<li>".$setup['path']."/".$setup['misc_folder']."/".$_REQUEST['deleteFile']."";
	if(file_exists("".$setup['path']."/".$setup['misc_folder']."/".$_REQUEST['deleteFile']."")) {
		unlink("".$setup['path']."/".$setup['misc_folder']."/".$_REQUEST['deleteFile']."");
		$_SESSION['sm'] = "File ".$_REQUEST['deleteFile']." deleted";
session_write_close();
	header("location: index.php?do=look&view=miscFiles&folder=".$_REQUEST['folder']."");
		exit();
	} else {
		$_SESSION['sm'] = "Unable to find file ".$_REQUEST['deleteFile']." ";
session_write_close();
		header("location: index.php?do=look&view=miscFiles&folder=".$_REQUEST['folder']."");
		exit();
	}
}

?>
<?php 
if($history['do_reload'] == "1") { 
	updateSQL("ms_history", "do_reload='0' ");
	?>
<script>
    location.reload(true);
</script>
<?php } ?>
<?php 
function createNewFolder() {
	global $site_setup,$setup;
	$page_link = stripslashes(trim(strtolower($_REQUEST['folder_name'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","_",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	if(file_exists($setup['path']."/".$setup['misc_folder']."/".$page_link)) {
		$page_link = $page_link."_".date('ymd');
	}
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['misc_folder']."")), -4); 
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
	print "<li>$parent_permissions<li>$page_link<li>$perms<li>";
	mkdir("".$setup['path']."/".$setup['misc_folder']."/$page_link", $perms);
	chmod("".$setup['path']."/".$setup['misc_folder']."/$page_link", $perms);
	$fp = fopen("".$setup['path']."/".$setup['misc_folder']."/".$page_link."/index.html", "w");
	fputs($fp, "$info\n");
	fclose($fp);
//	exit();
	$_SESSION['sm'] = "New folder created";
	session_write_close();
	header("location: index.php?do=look&view=miscFiles&folder=$page_link");
	exit();

}


?>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?> Miscellaneous Images & Files</div> 

<div id="roundedFormContain">


<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td width=20%>

<?php
 if(!is_writable($setup['path']."/".$setup['misc_folder']."")) {
	 print "<div class=error>The folder ".$setup['misc_folder']." is not writable and unable to upload to it. Change permissions to this folder so it is writable (0777).</div>";
}
?>
<?php if($setup['demo_mode'] !== true) { ?>



<?php 
if($setup['sytist_hosted'] == true) { 
	if(checkstoragelimit() !== true) { 
		showSytistSpace();
		?>
		<div class="pc">You have reached your storage limit. To upload more photos, you will need to delete some older photos to use the Amazon S3 feature.</div>
		<?php 
		$stop_upload = true;
	}
}
?>
<?php if($stop_upload !== true) { ?>

	<input type="file" name="file_upload" id="file_upload" />
	<?php 
	$hash = $site_setup['salt']; 
	$timestamp = date('Ymdhis');
	?>
	<script>
	$(function() {
		$('#file_upload').uploadify({
			 'multi'    : true,
			<?php if($_REQUEST['debug'] == "1") { ?>
			'debug'    : true,	
			<?php } ?>
			'method'   : 'post',
			'fileTypeExts' : '*',
			'fileTypeDesc' : 'all files',
			'buttonText' : 'Upload File',
			 'formData' : {'timestamp' : '<?php echo $timestamp; ?>', 
				 'token' : '<?php echo md5($hash.$timestamp); ?>', 
				'folder':'<?php print $_REQUEST['folder'];?>',
				 'logo_photos':'<?php print $_REQUEST['logo_photos']?>' },
			'onQueueComplete' : function(queueData) {
				window.parent.location.href='index.php?do=look&view=miscFiles&folder=<?php print $_REQUEST['folder'];?>&sm=File(s) Uploaded';
				}, 
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
					alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
				}, 

			'swf'      : 'uploadify/uploadify.swf',
			'uploader' :'misc_upload.php'
			// Put your options here
		});
	});
	</script>



	<div>&nbsp;</div>


	<div id="folder_error" style="display: none;">Error ....</div>
	<form method="post" name="newfolder"  id="newfolder" action="index.php">
	<div id="roundedForm">
	<div class="label"><?php print ai_folder_new;?> Create new folder</div>
	<div class="row">
	<div>Folder name</div>
	<div><input type="text" name="folder_name" size="12" style="width: 97%;"></div>
	</div>
	<div class="row" style="text-align: center;">
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="view" value="miscFiles">
	<input type="hidden" name="action" value="newFolder">
	<input type="submit" id="submission" onClick="return  checkFolderName(this)" value="Create Folder" class="submit">
	</div>
	</div>
	</form>
	<?php } ?>
<?php } ?>
</td><td width=1%>&nbsp;</td><td width=79%>
<div id="info">
This area is here to allow you to upload and get links for miscellaneous files and images.
</div>


<?php 

$file_exts = array("jpg","jpeg","gif","png","bmp");

if(file_exists("".$setup['path']."/".$setup['misc_folder']."")) {
	$theFiles = array();
	$theFolders = array();
	if(!empty($_REQUEST['folder'])) {
		$misc_path = $setup['path']."/".$setup['misc_folder']."/".$_REQUEST['folder']."";
	}else {
		$misc_path = $setup['path']."/".$setup['misc_folder']."";
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
		print "<h2>Folder: ".$_REQUEST['folder']."</h2>";
		print "<div>&nbsp;</div>";
	}
if($file_count<=0) {
	print "<center>No files have been uploaded</center>";
} else {

?>
<div id="roundedForm">

<?php 
	if(!empty($_REQUEST['folder'])) {
?>
		<div class="row">
			<div style="width:10%; float: left;"><a href="index.php?do=look&view=miscFiles"><?php print ai_folder_up;?></a></div><div style="width:90%; float: right;"><a href="index.php?do=look&view=miscFiles">up a level</a></div>
			<div class="cssClear"></div>
		</div>
<?php	} ?>

<?php 	
	asort($theFolders);
	foreach($theFolders AS $id => $file) {
		?>
		<div class="row">
			<div style="width:10%; float: left;"><a href="index.php?do=look&view=miscFiles&folder=<?php print $file;?>" ><?php print ai_folder;?></a></div><div style="width:90%; float: right;"><a href="index.php?do=look&view=miscFiles&folder=<?php print $file;?>"  class=pageSubTitle><?php print $file;?></a></div>
			<div class="cssClear"></div>
		</div>
	<?php } ?>

<?php 
	asort($theFiles);
	foreach($theFiles AS $id => $file) {
		$this_count++;
		?>
		<div class="underline" onmouseover="document.getElementById('delete-<?php print $this_count;?>').style.display = 'block';"  onmouseout="document.getElementById('delete-<?php print $this_count;?>').style.display = 'none';">
		<?php
		$file_show = $file;
		if(!empty($_REQUEST['folder'])) {
			$file = $_REQUEST['folder']."/".$file;
		}
		?>
			<div style="width:10%; float: left;">
			<span style="float: left; "><?php
			$ext = pathinfo("".$setup['path']."/".$setup['misc_folder']."/".$file."", PATHINFO_EXTENSION);
			if(in_array($ext,$file_exts)) {
			print ai_photo;
		} else {
			print ai_document;
		}
	?></span>
	<span id="delete-<?php print $this_count;?>" style="display: none; float: left; margin-left: 2px; "> <a href="index.php?do=look&view=miscFiles&deleteFile=<?php print urlencode(stripslashes($file));?>&folder=<?php print $_REQUEST['folder'];?>"  onClick="return confirm('Are you sure you want to delete this file?');"><?php print ai_delete;?></a></span>
	</div>
	<div style="width:20%; float: left;">
	<?php
	if(in_array($ext,$file_exts)) {
		$size = @GetImageSize("".$setup['path']."/".$setup['misc_folder']."/".stripslashes($file).""); 
		?>
		<img src="<?php tempFolder();?>/<?php print $setup['misc_folder']."/".stripslashes($file).""; ?>" <?php if($size[0] > 80) { print  " style=\"width: 80px;\""; } ?>>
		<?php
	} else {
		print "&nbsp;";
	}
	?>
	</div>
	<div style="width:70%; float: left;"><div ><a href="<?php tempFolder();?><?php print "/".$setup['misc_folder']."/".stripslashes($file)."";?>" class="pageSubTitle"><?php print stripslashes($file_show);?></a></div>

	<div id="link-open-<?php print $this_count;?>"><a href="javascript: openClose('links-<?php print $this_count;?>','link-open-<?php print $this_count;?>');">get links / codes</a></div>

	<div style="display: none;" id="links-<?php print $this_count;?>">
	<div ><a href="javascript: openClose('link-open-<?php print $this_count;?>','links-<?php print $this_count;?>');">get links / codes</a></div>

	<div class="cells">Local Link: <input type="text" name="<?php print $rc;?>" size="50"  value="<?php print $setup['temp_url_folder']."/".$setup['misc_folder']."/".$file;?>" style="width: 95%"></div>
	<div class="cells">Full Link: <input type="text" name="<?php print $rc;?>" size="50"  value="<?php print $setup['url'].$setup['temp_url_folder']."/".$setup['misc_folder']."/".$file;?>" style="width: 95%"></div>
	<?php 
	if(in_array($ext,$file_exts)) { ?>
	<div class="cells">IMG SRC: <input type="text" name="<?php print $rc;?>" size="50"  value='<img src="<?php print $setup['url'].$setup['temp_url_folder']."/".$setup['misc_folder']."/".$file;?>">' style="width: 95%"></div>
	<?php } ?>
	<?php if($ext == "php") { ?>
	<div class="cells">Server path: <input type="text" name="<?php print $rc;?>" size="50"  value='<?php print $setup['path']."/".$setup['misc_folder']."/".$file;?>' style="width: 95%"></div>

	<?php } ?>

		</div>

	</div>
			<div class="cssClear"></div>
		</div>

<?php 
	}

}
	?>
</td></tr></table>
</div>



<?php 
function file_extension($filename)
   {
       $path_info = pathinfo($filename);
       return $path_info['extension'];
}
   ?>