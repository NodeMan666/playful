<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if($setup['demo_mode'] == true) { 
	$_SESSION['sm'] = "Uploading disabled in the demo.";
	session_write_close();
	header("location: index.php?do=settings&action=watermarking");
	exit();

}

$uploaded_raw=$_FILES['image']['name']; 
$FILENAME = $uploaded_raw;
$uploaded_array=explode(".",$uploaded_raw); 
if(empty($_FILES['image']['tmp_name'])) {
	die("<span class=error>You did not select a file to upload</span>");

}

	$wm_folder =$setup['photos_upload_folder']."/watermarks";
	$wm_folder_path = $setup['path']."/".$wm_folder."";

if(!is_dir($wm_folder_path)) {
	mkdir($wm_folder_path."", 0777);
	chmod($wm_folder_path."", 0777);
}

if($uploaded_array[1] !== "png") {
	die("<span class=error>This file is not a .png file. Only png files can be used for watermarking.</a><br><br>File name: $FILENAME / Ext: $uploaded_array[1]</span> ");
}

$tempFile = $_FILES['image']['tmp_name'];
$destination = ($wm_folder_path."/".$FILENAME."");
$_FILES['image']['name'];
$test = copy($tempFile, $destination);
unlink($tempfile);
$_SESSION['sm'] = "Watermark file $FILENAME uploaded";
print "<li>Upload successful";
session_write_close();
header("location: index.php?do=settings&action=watermarking");
exit();
?>