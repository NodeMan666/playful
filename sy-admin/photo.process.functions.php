<?php 
function createPhotoFolder() { 
	global $setup;
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
	} else {
		$perms = 0755;
	}

	
	$year_folder = date('Y');

	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder")) {
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder", $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder", $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/".$year_folder."/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}
	$month_folder = date('m');

	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder")) {
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder", $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder", $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/".$year_folder."/$month_folder/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}

	$day_folder = date('d');

	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder")) {
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder", $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder", $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/".$year_folder."/$month_folder/$day_folder/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}
	$hour_folder = date('H');

	if(!is_dir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder/$hour_folder")) {
		mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder/$hour_folder", $perms);
		chmod("".$setup['path']."/".$setup['photos_upload_folder']."/$year_folder/$month_folder/$day_folder/$hour_folder", $perms);
		$fp = fopen("".$setup['path']."/".$setup['photos_upload_folder']."/".$year_folder."/$month_folder/$day_folder/$hour_folder/index.php", "w");
		fputs($fp, "$info\n");
		fclose($fp);
	}

	return $year_folder."/".$month_folder."/".$day_folder."/".$hour_folder;
}

function cleanUploadFileName($name) { 
	$image_use_name = $name;
	$image_use_name = str_replace(' ', '_', $image_use_name);
	$image_use_name = str_replace("&", "and", $image_use_name);
	$image_use_name = str_replace("#", "num", $image_use_name);
	$image_use_name = str_replace("?", "", $image_use_name);
	$image_use_name = str_replace('"', "", $image_use_name);
	$image_use_name = str_replace("'", "", $image_use_name);
	$image_use_name = str_replace("/", "", $image_use_name);
	$image_use_name = str_replace("%", "", $image_use_name);
	$image_use_name = stripslashes(trim($image_use_name));
	return $image_use_name;
}
function image_fix_orientation(&$image, $filename) {
    $exif = exif_read_data($image);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3:
				$source = imagecreatefromjpeg($filename) ;
                $image = imagerotate($source, 180, 0);
				return "'1";
                break;

            case 6:
 				$source = imagecreatefromjpeg($filename) ;
               $image = imagerotate($source, -90, 0);
				return "1";
                break;

            case 8:
				$source = imagecreatefromjpeg($filename) ;
                $image = imagerotate($source, 90, 0);
				return "1";
                break;
        }
    
	}
}
function checkfordup($filename,$date_id,$sub_id) { 
	if($_REQUEST['discard_dups'] == "1") { 
		if($date_id > 0) { 
			$ck = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE pic_org='".addslashes(stripslashes($filename))."' AND bp_blog='".$date_id."' AND bp_sub='".$sub_id."'");
		} else { 
			$ck = doSQL("ms_photos", "*", "WHERE pic_org='".addslashes(stripslashes($filename))."' ");
		}
		if(!empty($ck['pic_id'])) { 
			$discard_dup = true;
		}
	}
	return $discard_dup;
}


function watermarkPhoto($image) { 
	global $setup,$photo_setup;
	$wm = doSQL("ms_watermarking", "*", "");
	$location = $wm['wm_images_location'];
	if(!empty($_REQUEST['wm_images_location'])) { 
		$location = $_REQUEST['wm_images_location'];
	}
	$overlay = $setup['path']."/".$wm['wm_images_file'];
	if(!empty($_REQUEST['wm_images_file'])) { 
		$overlay = $setup['path']."/".urldecode($_REQUEST['wm_images_file']);
	}
	$w_offset = 0;
	$h_offset = 0;
	if((file_exists($overlay))&&(!empty($overlay)) == true) {
		$size= GetImageSize($image); 
		$iwidth=$size[0];
		$iheight=$size[1];


		$overlay = imagecreatefrompng($dir . $overlay);
		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);

		$ext = strtolower(substr($image, -4));
		if($ext == ".png") {
			$new = imagecreatefrompng($image);
		} else { 
			$new = imagecreatefromjpeg($image);
		}	 
		 if($location == "tile") { 
			imagesettile($new, $overlay);
			imagefilledrectangle($new, -0, 0, $iwidth, $iheight, IMG_COLOR_TILED);
		 } elseif($location == "center") {
			// center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 } elseif($location == "bright") {
		   // bottom right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bottom") {
			// bottom center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bleft") {
			// bottom left
			imagecopy($new, $overlay, 0, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);

		 } elseif($location == "uright") {
		   // top right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "top") {
			// top center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "uleft") {
			// top left
			imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
		 } else {
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 }
		if($ext == ".png") {
			imagealphablending($new, false);
			imagesavealpha($new, true);

			Imagepng($new,$image);
		} else { 
			ImageJpeg ($new,$image, 90);
		}
		imagedestroy($overlay);
	}
}

function logoPhoto($image) { 
	global $setup,$photo_setup;
	$wm = doSQL("ms_watermarking", "*", "");
	$location = $wm['wm_add_logo_location'];
	$overlay = $setup['path']."/".$wm['wm_logo_file'];
	$w_offset = 0;
	$h_offset = 0;

	if(!empty($_REQUEST['wm_add_logo_location'])) { 
		$location = $_REQUEST['wm_add_logo_location'];
	}
	if(!empty($_REQUEST['wm_logo_file'])) { 
		$overlay = $setup['path']."/".urldecode($_REQUEST['wm_logo_file']);
	}

	if((file_exists($overlay))&&(!empty($overlay)) == true) {
		$size= GetImageSize($image); 
		$iwidth=$size[0];
		$iheight=$size[1];


		$overlay = imagecreatefrompng($dir . $overlay);
		// Get the size of overlay
		$owidth = imagesx($overlay);
		$oheight = imagesy($overlay);

		$ext = strtolower(substr($image, -4));
		if($ext == ".png") {
			$new = imagecreatefrompng($image);
		} else { 
			$new = imagecreatefromjpeg($image);
		}	 

		 
		 if($location == "tile") { 
			imagesettile($new, $overlay);
			imagefilledrectangle($new, -0, 0, $iwidth, $iheight, IMG_COLOR_TILED);
		 } elseif($location == "center") {
			// center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 } elseif($location == "bright") {
		   // bottom right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bottom") {
			// bottom center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);
		 } elseif($location == "bleft") {
			// bottom left
			imagecopy($new, $overlay, 0, $size[1] - $oheight - $h_offset, 0, 0, $owidth, $oheight);

		 } elseif($location == "uright") {
		   // top right
			imagecopy($new, $overlay, $size[0]- $owidth - $w_offset, 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "top") {
			// top center
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), 0, 0, 0, $owidth, $oheight);
		 } elseif($location == "uleft") {
			// top left
			imagecopy($new, $overlay, 0, 0, 0, 0, $owidth, $oheight);
		 } else {
			imagecopy($new, $overlay, ($size[0] / 2) - (($owidth - $w_offset) / 2), ($size[1] / 2)- (($oheight - $h_offset)/2), 0, 0, $owidth, $oheight);
		 }
		if($ext == ".png") {
			imagealphablending($new, false);
			imagesavealpha($new, true);

			Imagepng($new,$image);
		} else { 
			ImageJpeg ($new,$image, 90);
		}

		imagedestroy($overlay);
	}
}
function ResizeImage($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup,$quality) {
	$imagex = imagecreatefromjpeg("$imagex"); 
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
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageJpeg ($newim,$name . "$image_ext", $quality);
		ImageDestroy ($newim);
	}else{
		ImageJpeg ($imagex,$name . "$image_ext", $quality);
	}
	ImageDestroy ($imagex);
}
function ResizeImageGif($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup,$quality) {
	$imagex = imagecreatefromgif("$imagex"); 
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
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		ImageGif ($newim,$name . "$image_ext", $quality);
		ImageDestroy ($newim);
	}else{
		ImageGif ($imagex,$name . "$image_ext", $quality);
	}
	ImageDestroy ($imagex);
}



function ResizeImagePng($imagex,$maxwidth,$maxheight,$name, $photo_setup, $setup,$quality) {
	$imagex = imagecreatefrompng("$imagex"); 
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
    	$newwidth = @ceil($width * $ratio);
        $newheight = @ceil($height * $ratio);
		if(function_exists("imagecopyresampled")){
      		$newim = imagecreatetruecolor($newwidth, $newheight);
			imagealphablending($newim, false);
			imagesavealpha($newim, true);
			// $black = imagecolorallocate($newim, 0, 0, 0);
			// Make the background transparent
			// imagecolortransparent($newim, $black);

      		imagecopyresampled($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}else{
			$newim = imagecreate($newwidth, $newheight);
			imagealphablending($newim, false);
			imagesavealpha($newim, true);
      		imagecopyresized($newim, $imagex, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		Imagepng ($newim,$name . "$image_ext");
		ImageDestroy ($newim);
	}else{
		Imagepng ($imagex,$name . "$image_ext");
	}
	ImageDestroy ($imagex);
}



function output_iptc_data( $image_path, $info ) {
$ipinfo = doSQL("ms_iptc", "*", "");

	$iptcTags = array (

		"2#000" => "Record Version",
		"2#005" => "Title",
		"2#025" => "Keywords",
		"2#120" => "Caption",
		"2#003" => "Object Type Reference",
		"2#007" => "Edit Status",
		"2#008" => "Editorial Update",
		"2#010" => "Urgency",
		"2#012" => "Subject Reference",
		"2#015" => "Category",
		"2#020" => "Supplemental Category",
		"2#022" => "Fixture Identifier",
		"2#026" => "Content Location Code",
		"2#027" => "Content Location Name",
		"2#030" => "Release Date",
		"2#035" => "Release Time",
		"2#037" => "Expiration Date",
		"2#035" => "Expiration Time",
		"2#040" => "Special Instructions",
		"2#042" => "Action Advised",
		"2#045" => "Reference Service",
		"2#047" => "Reference Date",
		"2#050" => "Reference Number",
		"2#055" => "Date Created",
		"2#060" => "Time Created",
		"2#062" => "Digital Creation Date",
		"2#063" => "Digital Creation Time",
		"2#065" => "Originating Program",
		"2#070" => "Program Version",
		"2#075" => "Object Cycle",
		"2#080" => "By-Line (Author)",
		"2#085" => "By-Line Title (Author Position) [Not used in Photoshop 7]",
		"2#090" => "City",
		"2#092" => "Sub-Location",
		"2#095" => "Province/State",
		"2#100" => "Country/Primary Location Code",
		"2#101" => "Country/Primary Location Name",
		"2#103" => "Original Transmission Reference",
		"2#105" => "Headline",
		"2#110" => "Credit",
		"2#115" => "Source",
		"2#116" => "Copyright Notice",
		"2#118" => "Contact",
		"2#122" => "Caption Writer/Editor",
		"2#125" => "Rasterized Caption",
		"2#130" => "Image Type",
		"2#131" => "Image Orientation",
		"2#135" => "Language Identifier",
		"2#150" => "Audio Type",
		"2#151" => "Audio Sampling Rate",
		"2#152" => "Audio Sampling Resolution",
		"2#153" => "Audio Duration",
		"2#154" => "Audio Outcue",
		"2#200" => "ObjectData Preview File Format",
		"2#201" => "ObjectData Preview File Format Version",
		"2#202" => "ObjectData Preview Data"

	);
	if((!empty($image_path)) && (empty($info))==true) {
		$size = getimagesize ( $image_path, $info);
	}
		$iptc = iptcparse($info["APP13"]);
		// print "<pre>{{{ IPTC Data }}} : "; print_r($iptc); print "</pre>";
		if(is_array($iptc)) {

			foreach (array_keys($iptc) as $s) {
				$c = count ($iptc[$s]);
				for ($i=0; $i <$c; $i++) {
					$idata[$iptcTags[$s]]= $iptc[$s][$i];
//					 echo '<li> '.$iptcTags[$s].' = '.$iptc[$s][$i].'';
					if($iptcTags[$s] == $ipinfo['title']) {
						$imageData['Title'] = $iptc[$s][$i];
					}
					if($iptcTags[$s] == $ipinfo['text']) {
						$imageData['Caption'] = $iptc[$s][$i];
					}
					if($iptcTags[$s] == $ipinfo['tags']) {
						$imageData['Keywords'] .= ",".$iptc[$s][$i];
					}
					if($iptcTags[$s] == $ipinfo['category']) {
						$imageData['Category'] = $iptc[$s][$i];
					}
					if($iptcTags[$s] == $ipinfo['location']) {
						$imageData['Location'] = $iptc[$s][$i];
					}
					if($iptcTags[$s] == "Date Created") {
						$imageData['Date'] = $iptc[$s][$i];
					}

				}
			}
		}

return $imageData;
}

function showImageData() {
	global $_REQUEST,$settings, $setup, $entry;

	if(!empty($entry['image_dl'])) {

	}
	$exifList = array("Make", "Model", "DateTime", "ExposureTime", "ISOSpeedRatings", "ImageType", "FNumber", "Orientation", "ApertureValue", "ShutterSpeedValue", "DateTimeOriginal", "ExposureBiasValue", "MaxApertureValue", "MeteringMode", "LightSource", "Flash", "FocalLength");

	print "<table align=center cellpadding=4 cellspacing=0 border=0 class=listbox width=100%>";
	print "<tr><td class=tdrows1>Dimensions</td><td class=tdrows1>".$entry['image_dl_width']." X ".$entry['image_dl_height']."</td></tr>";
	print "<tr><td class=tdrows1>File size</td><td class=tdrows1>".size_hum_read("".$entry['image_dl_size']."")."</td></tr>";


	$exif = @read_exif_data ("".$setup['path']."/".$entry['entry_folder']."/".$entry['image_dl']."");
	foreach($exifList AS $what) {
		if(!empty($exif[$what])) {
			print "<tr><td class=tdrows1>$what</td><td class=tdrows1>".$exif[$what]."</td></tr>";
		}
	}
	print "</table>";
	/*
// while(list($k,$v)=each($exif)) {
	foreach($exif AS $id => $data) {
		if($id!=="MakerNote") {
			if(is_array($data)) {
				print "<li>$id";
				print_r($data);
			} else {
				print "<li>$id : $data";
			}
		}
//   echo "$k: $v<br>\n";

 }

 */
}


function processPhoto($old_file,$size_original,$new_file_name,$new_width,$new_height,$watermark,$logo,$crop,$quality,$gif,$png) { 
	global $setup,$photo_setup;


	if($crop == "1") {
		if($size_original[0] >= $size_original[1]) {
			$div = ($size_original[1] / $new_height);

			$RESIZEWIDTH=ceil($size_original[0] / $div);
			$RESIZEHEIGHT=$new_height ;
		} else {
			$div = ($size_original[0] / $new_width);
			$RESIZEWIDTH=$new_width ;
			$RESIZEHEIGHT=ceil($size_original[1] / $div);
		}
		if($RESIZEWIDTH<$new_width) {
			$add1 = $new_width - $RESIZEWIDTH;
		}
		if($RESIZEHEIGHT<$new_height) {
			$add2 = $new_height - $RESIZEHEIGHT;
			if($add2>$add1) {
				$add = $add2;
			} else {
				$add = $add1;
			}
		}
		if($add > 0) {
			$RESIZEWIDTH = $RESIZEWIDTH + $add;
			$RESIZEHEIGHT = $RESIZEHEIGHT + $add;
		}
	} else {
			$RESIZEWIDTH=$new_width;
			$RESIZEHEIGHT=$new_height;
	}


	if(($size_original[0] > $new_width)OR($size_original[1] > $new_height) OR ($gif == true)==true) { 
		if($gif == true) { 
			ResizeImageGif($old_file,$RESIZEWIDTH,$RESIZEHEIGHT,$new_file_name, $photo_setup, $setup,$quality);
		} elseif($png == true) { 
			ResizeImagePng($old_file,$RESIZEWIDTH,$RESIZEHEIGHT,$new_file_name, $photo_setup, $setup,$quality);
		} else { 
			ResizeImage($old_file,$RESIZEWIDTH,$RESIZEHEIGHT,$new_file_name, $photo_setup, $setup,$quality);
		}
		if($crop == "1") {
			// START CROIP 
			$tx = ceil(($RESIZEWIDTH / 2) - ($new_width / 2));
			$ty = ceil(($RESIZEHEIGHT / 2) - ($new_height / 2));
			if($tx< 0) { $tx = 0;}
			if($ty< 0) { $ty = 0;}
			//print "<li>tx: $tx";
			//print "<li>ty: $ty";
			$img = imagecreatetruecolor($new_width,$new_height);
			if($gif == true) { 
				$org_img = imagecreatefromgif($new_file_name);
			} elseif($png == true) { 
				$org_img = imagecreatefrompng($new_file_name);
			} else { 
				$org_img = imagecreatefromjpeg($new_file_name);
			}
			if($ty > 25) { $ty_pos = $ty - 25;} else { $ty_pos = $ty; } 
			imagecopy($img,$org_img, 0, 0, $tx, $ty_pos, $new_width, $new_height);
			if($gif == true) { 
				imagegif($img,$new_file_name,$quality);
			} elseif($png == true) { 
				imagepng($img,$new_file_name);
			} else { 
				imagejpeg($img,$new_file_name,$quality);
			}
			imagedestroy($img);
			// END CROP
		$_SESSION['ty'] = $ty;
		}
	} else { 
		copy($old_file,$new_file_name);
	}
	if($watermark == "1") { 
		watermarkPhoto($new_file_name);
	}
	if($logo == "1") { 
		logoPhoto($new_file_name);
	}
}


function getphotoinfo($file,$info,$pic_id) {
	global $ipinfo;
	$ipinfo = doSQL("ms_iptc", "*", "");
	ini_set('exif.decode_unicode_motorola', 'UCS-2LE');
	// $file = file name ... path to file
	// $info = comes from getImageSize

	if(function_exists('read_exif_data')) {
		$exif = @read_exif_data ($file);

		if(is_array($info)) {
			$idata = output_iptc_data($file, $info );
		}

		/* Get tags and add to database if doesn't exist */
		// print_r($idata);
		$tags = explode("".$ipinfo['tags_seperate']."",$idata[$ipinfo['tags']]);

		if(is_array($tags)) {
			foreach($tags AS $name => $tag) {
				$tag = trim($tag);
				if($tag!='') {
					$findme = "=";
					//$tag = utf8_encode($tag);

					$pos = strpos($tag, $findme);
					if ($pos === false) {
						// Not found, do nothing.
					

						$idata_tag .="$tag,";
						$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($tag))."' ");
						if(empty($ck['id'])) { 
							$key_id = insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($tag))."' ");
						} else { 
							$key_id = $ck['id'];
						}
						$ckcon = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$key_id."' AND key_pic_id='".$pic_id."' ");
						if(empty($ckcon['id'])) { 
							insertSQL("ms_photo_keywords_connect", "key_key_id='".$key_id."', key_pic_id='".$pic_id."' ");
						}
					}
				}
			}
		}	

		if((empty($pic_title)) AND (!empty($idata['Title']))==true) {
			$photo_info['title'] = $idata['Title'];
		}
		if((empty($pic_title))AND(!empty($exif['Title']))==true) { 
			$photo_info['title'] = $exif['Title'];
		}

		if((empty($pic_text)) AND (!empty($idata['Caption']))==true) {
			$photo_info['text'] = $idata['Caption'];
		}

		if((empty($pic_text))AND(!empty($exif['Comments']))==true) { 
			$photo_info['text'] = $exif['Comments'];
		}

		if((empty($pic_keywords)) AND (!empty($idata_tag))==true) {
			$photo_info['tags'] = $idata_tag;
		}
		if((empty($pic_keywords))AND(!empty($exif['Keywords']))==true) { 

			$tags = explode(";",$exif['Keywords']);

			if(is_array($tags)) {
				foreach($tags AS $name => $tag) {
					$tag = mb_strtolower(trim($tag));
					if($tag!='') {
						$idata_tag .="$tag,";
						$ck = doSQL("ms_photo_keywords", "*", "WHERE key_word='".addslashes(stripslashes($tag))."' ");
						if(empty($ck['id'])) { 
							$key_id = insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($tag))."' ");
						} else { 
							$key_id = $ck['id'];
						}
					}
					$ckcon = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$key_id."' AND key_pic_id='".$pic_id."' ");
					if(empty($ckcon['id'])) { 
						insertSQL("ms_photo_keywords_connect", "key_key_id='".$key_id."', key_pic_id='".$pic_id."' ");
					}

				}
			}	
			$photo_info['tags'] = $idata_tag;
		}
		$photo_info['date_taken'] = $exif['DateTimeOriginal'];
		$photo_info['camera_make'] =$exif['Make'];
		$photo_info['camera_model'] = $exif['Model'];
	}
	return $photo_info;
}

?>