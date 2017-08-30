<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}

if($setup['demo_mode'] == true) { 
	$_SESSION['smerror'] = "Unable to upload file: FILE EXISTS. There is already a file called ".$_FILES['image']['name']." and overwrite file was not checked. File not uploaded";
		session_write_close();
		if($_REQUEST['logo'] == "yes") { 
			if($_REQUEST['from_theme'] == "1") {
				header("location: w-header-edit.php?noclose=1&width=".$_REQUEST['width']."&height=".$_REQUEST['height']."");
			} else { 
				header("location: index.php?do=look&view=header");
			}
		} else { 
			header("location: index.php?do=look&view=miscFiles");
		}
		exit();
}

function ResizeImage($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup) {
	$width = imagesx($imagex);
	$height = imagesy($imagex);
	if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
		if($maxwidth && $width > $maxwidth){
			$widthratio = $maxwidth/$width;
			$RESIZEWIDTH=true;
		}
		if($maxheight && $height > $maxheight){
			$heightratio = $maxheight/$height;
			$RESIZEHEIGHT=true;
		}
		if($RESIZEWIDTH && $RESIZEHEIGHT){
			if($widthratio < $heightratio){
				$ratio = $widthratio;
			}else{
				$ratio = $heightratio;
			}
		}elseif($RESIZEWIDTH){
			$ratio = $widthratio;
		}elseif($RESIZEHEIGHT){
			$ratio = $heightratio;
		}
    	$newwidth = $width * $ratio;
        $newheight = $height * $ratio;
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageJpeg ($newim,$name . "$image_ext", 93);
		ImageDestroy ($newim);
	}else{
		ImageJpeg ($imagex,$name . "$image_ext", 93);
	}
}



// $photo_setup = doSQL("photo_setup", "*", "  ");
// require("../image.data.php");

// chmod("".$setup['path']."/$gal_folder", 0777);
// $ipinfo = doSQL("pc_iptc", "*", "");

$perRow = 3;
// print_r($_POST);
// exit();
if(!is_dir($setup['path']."/".$setup['misc_folder']."")) {
	mkdir("".$setup['path']."/".$setup['misc_folder']."", 0777);
	chmod("".$setup['path']."/".$setup['misc_folder']."", 0777);
}





$FILENAME=$_FILES['image']['name']; 
$RESIZEWIDTH = $_REQUEST['th_width'];
$RESIZEHEIGHT = $_REQUEST['th_height'];
$uploaded_array=explode(".",$FILENAME); 
$image_extension = strtolower($uploaded_array[1]); 
if(!empty($_REQUEST['folder_name'])) {
	$upload_to = $setup['misc_folder']."/".$_REQUEST['folder_name'];
} else {
	$upload_to = $setup['misc_folder'];
}

if(file_exists($setup['path']."/".$upload_to."/".$_FILES['image']['name']."")) {
	if($_REQUEST['overwrite'] == "on") {	
		unlink($setup['path']."/".$upload_to."/".$_FILES['image']['name']."");
	} else {

		$_SESSION['smerror'] = "Unable to upload file: FILE EXISTS. There is already a file called ".$_FILES['image']['name']." and overwrite file was not checked. File not uploaded";
		session_write_close();
		if($_REQUEST['logo'] == "yes") { 
			if($_REQUEST['from_theme'] == "1") {
				header("location: w-header-edit.php?noclose=1&width=".$_REQUEST['width']."&height=".$_REQUEST['height']."");
			} else { 
				header("location: index.php?do=look&view=header&tosave=1");
			}
		} else { 
			header("location: index.php?do=look&view=miscFiles");
		}
		exit();
	}
}

	if(($image_extension == "jpg" )AND($_REQUEST['resize'] == "yes")==true) {
		if($_FILES['image']['size']){
			if($_FILES['image']['type'] == "image/pjpeg"){
				$im = imagecreatefromjpeg($_FILES['image']['tmp_name']);
			}elseif($_FILES['image']['type'] == "image/jpeg"){
				$im = imagecreatefromjpeg($_FILES['image']['tmp_name']);
			}elseif($_FILES['image']['type'] == "image/x-png"){
				$im = imagecreatefrompng($_FILES['image']['tmp_name']);
			}elseif($_FILES['image']['type'] == "image/gif"){
				$im = imagecreatefromgif($_FILES['image']['tmp_name']);
			}
			if($im){
				if(file_exists("$FILENAME")){
					unlink("$FILENAME");
				}
				ResizeImage($im,$RESIZEWIDTH,$RESIZEHEIGHT,"".$setup['path']."/".$upload_to."/".$FILENAME, $photo_setup, $setup);
				ImageDestroy ($im);
			}
		}

	} else {

		$tempFile = $_FILES['image']['tmp_name'];
		$size_upfull = @GetImageSize($_FILES['image']['tmp_name']); 

		$destination = ("".$setup['path']."/".$upload_to."/".$FILENAME);
		print "<li>".$_FILES['image']['name'];

		copy($tempFile, $destination);
	}
	if($_REQUEST['logo'] == "yes") { 
		if($_REQUEST['replace'] == "yes") { 

			$size = @GetImageSize($destination); 

			$new_logo = "<a href=\"/\"><img src=\"".$setup['temp_url_folder']."/".$upload_to."/".$FILENAME."\" border=\"0\" style=\"max-width: ".$size[0]."px; max-height: ".$size[1]."px; width: 100%; height: auto;\"></a>";

//			updateSQL("ms_settings", "header='<a href=\"/\"><img src=\"/".$upload_to."/".$FILENAME."\" border=\"0\"></a>' ");
		} else {
			$header = doSQL("ms_settings", "header", "");
			$new_logo = "<a href=\"/\"><img src=\"".$setup['temp_url_folder']."/".$upload_to."/".$FILENAME."\" border=\"0\"></a><br>".$header['header']."";

			updateSQL("ms_settings", "header='<a href=\"/\"><img src=\"".$setup['temp_url_folder']."/".$upload_to."/".$FILENAME."\" border=\"0\"></a><br>".addslashes($header['header'])." ' ");			
		}
		if($_REQUEST['mobilelogo'] == "yes") { 
			$_SESSION['new_logo_mobile'] = $new_logo;
		} else { 
			$_SESSION['new_logo'] = $new_logo;
		}
		$_SESSION['sm'] = "Logo file has been uploaded";
		session_write_close();
		if($_REQUEST['from_theme'] == "1") {
			header("location: w-header-edit.php?noclose=1&width=".$_REQUEST['width']."&height=".$_REQUEST['height']."");
		} else { 
			header("location: index.php?do=look&view=header");
		}
		exit();
	}

$_SESSION['sm'] = $_FILES['image']['name']." has been uploaded";
session_write_close();
header("location: index.php?do=look&view=miscFiles&folder=".$_REQUEST['folder_name']."");
exit();
?>