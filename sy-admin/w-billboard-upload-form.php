<?php require "w-header.php"; ?>
<style>#framewindow { padding: 0px; } </style>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}
adminsessionCheck();
?>

<?php if($_REQUEST['action'] == "upload") { 
	$FILENAME=$_FILES['image']['name']; 
	$tempFile = $_FILES['image']['tmp_name'];
	$size_upfull = @GetImageSize($_FILES['image']['tmp_name']); 
	if(!empty($_REQUEST['folder'])) { 
		$upload_to = $setup['graphics_folder']."/billboards/".$_REQUEST['folder']."";
	} else { 
		$upload_to = $setup['graphics_folder']."/billboards";
	}
	$destination = ("".$setup['path']."/".$upload_to."/".$FILENAME);
	print "<li>".$_FILES['image']['name'];

	copy($tempFile, $destination);

	$_SESSION['sm'] = "Background file has been uploaded";
	session_write_close();
	header("location: w-billboard-upload-form.php?folder=".$_REQUEST['folder']."&file=".$FILENAME."&refresh=1&noclose=1");
	exit();
}
?>

<?php if($_REQUEST['refresh'] == "1") { ?>
<script>
function openBackgroudFolder(folder) { 
	$.get("w-billboard-graphics.php?noclose=1&folder="+folder, function(data) {
		window.parent.$("#selectgraphic").html(data);
	});
}

	
openBackgroudFolder('<?php print $_REQUEST['folder'];?>');</script>
<?php } ?>

<form name="upitprev" id="upitprev"  method="POST" action="<?php print $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" >
	<div id="roundedForm">
		<div class="label">Upload New File</div>
<?php 
$line_count = 3;
?>
<div class="row">
<div id="upitprev_error" style="display: none;">Error ....</div>
<div class="left"><input type="file" name="image" size="40" id="image" ></div>
<div class="right"><input type="submit" name="submission" id="submission"  value="Upload File" class="submit"></div>
<div class="clear"></div>

<input type="hidden" name="folder" value="<?php print $_REQUEST['folder'];?>">
<?php  print "<input type=\"hidden\" name=\"action\" value=\"upload\">"; ?>
</div>
<div class="row">
<!-- <div class="left"><input type="checkbox" name="overwrite"> Overwrite existing file</div> -->
</div>
</div>
</form>
<?php require "w-footer.php"; ?>
