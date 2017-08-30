<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

	$wm_folder =$setup['photos_upload_folder']."/watermarks";
	$wm_folder_path = $setup['path']."/".$wm_folder."";
if($_REQUEST['subdo']=="uploadFile") {
	include "settings.watermark.upload.php";
	exit();
}

if(!empty($_REQUEST['deleteFile'])) { 
	unlink($wm_folder_path."/".$_REQUEST['deleteFile']);
	$_SESSION['sm'] = "Watermarking File Deleted";
	session_write_close();
	header("location: index.php?do=settings&action=watermarking");
	exit();

}



if($_REQUEST['subdo']=="saveSettings") {
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(trim($value)));
	}

	$id = updateSQL("ms_watermarking", " wm_thumbs='".$_REQUEST['wm_thumbs']."', wm_zoom='".$_REQUEST['wm_zoom']."', wm_thumbs_location='".$_REQUEST['wm_thumbs_location']."', wm_images='".$_REQUEST['wm_images']."', wm_images_location='".$_REQUEST['wm_images_location']."', wm_add_logo='".$_REQUEST['wm_add_logo']."', wm_add_logo_location='".$_REQUEST['wm_add_logo_location']."', wm_images_file='".$_REQUEST['wm_images_file']."', wm_thumbs_file='".$_REQUEST['wm_thumbs_file']."', wm_logo_file='".$_REQUEST['wm_logo_file']."', wm_def_wm='".$_REQUEST['wm_def_wm']."', wm_def_logo='".$_REQUEST['wm_def_logo']."' ");   

	$_SESSION['sm'] = "Watermarking Settings Saved";
	session_write_close();
	header("location: index.php?do=settings&action=watermarking");
	exit();
}

?>	
	
	
	
	
<?php regForm(); ?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	if(empty($_REQUEST['check'])) {
		$wm = doSQL("ms_watermarking", "*", " ");
		foreach($wm AS $fname => $val) {
			if(!is_numeric($fname)) {
				$_REQUEST[$fname] = $val;
			}
		}
	}

	$wm_folder =$setup['photos_upload_folder']."/watermarks";
	$wm_folder_path = $setup['path']."/".$wm_folder."";

	?>
<div id="pageTitle"><a href="index.php?do=settings">Settings</a>  <?php print ai_sep;?> 
	<span>Watermarking Settings</span>
</div>
<div class="pc">You have the option to add watermarking and / or a logo file to the photos you upload. You set your default settings below but change turn them off and on when you upload photos.</div>
<div>
<div>&nbsp;</div>
<div style="width: 34%; float: left;">
<div>
	<div class="underlinelabel">Uploaded Watermark Files</div>
	<?php
	if(!is_dir($wm_folder_path)) {
		print "<div class=\"underline\">You have not uploaded any watermark files</div>";
	} else {
		$dir = opendir($wm_folder_path); 
		while ($file = readdir($dir)) { 
			if (($file != ".") && ($file != "..")) { 
				print "<div class=\"underline\"><a href=\"index.php?do=settings&action=watermarking&deleteFile=$file\"   onClick=\"return confirm('Are you sure you want to delete this file?');\">".ai_trash."</a> <a href=\"../$wm_folder"."/"."$file\">$file</a></div>";
			}
		}
	@closedir($dir); 
	}
?>
</div>
		
<div>&nbsp;</div>
<div>
	<div class="underlinelabel">Upload New Watermark File</div>
	<div class="underline">
		<FORM name="fileup" method=POST action="index.php" enctype="multipart/form-data">
	<input type="file" name="image" size=30 > 
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="watermarking">
	<input type="hidden" name="subdo" value="uploadFile">
	<input type="hidden" name="wm_folder" value="<?php  print $wm_folder_path;?>">
	<input type="submit" name="submit" value="upload" class="submitSmall">
	</form>
	</div>
</div>


</div>

<div style="width: 60%; float: right;">

	<form name="watset" action="index.php" method="POST"    onSubmit="return checkForm();">
	<input type="hidden" name="do" value="settings">
	<input type="hidden" name="action" value="watermarking">
	<input type="hidden" name="subdo" value="saveSettings">
	<div>
		<div class="underlinelabel">Watermark Settings</div>
		<div class="underline">
		<div class="cssCell" style="width: 30%; float: left;">File: </div>
		<div class="cssCell" style="width: 60%; float: left;">
			<?php 
			if(!is_dir($wm_folder_path)) {
				print "You have not uploaded any watermark files";
			} else {
				?>
				<select name="wm_images_file">
				<option value="">-------------------------</option>
				<?php 
				$dir = opendir($wm_folder_path); 
				while ($file = readdir($dir)) { 
					if (($file != ".") && ($file != "..")) { 
						print "<option value=\"$wm_folder/$file\""; if($_REQUEST['wm_images_file'] == "$wm_folder/$file") { print "selected"; } print ">".$file."</option>";
					}
				}
			@closedir($dir); 
			}
			?>
			</select>
			</div>
			<div class="cssClear"></div>
	</div>



		<div class="underline">

		<div class="cssCell" style="width: 30%; float: left;">Placement Of Watermark: </div>
		<div class="cssCell" style="width: 60%; float: left;">
		<select name="wm_images_location">
		<option value="tile" <?php  if($_REQUEST['wm_images_location'] == "tile") { print "selected"; } ?>>Tile</option>
		<option value="center" <?php  if($_REQUEST['wm_images_location'] == "center") { print "selected"; } ?>>Center</option>
		<option value="bright" <?php  if($_REQUEST['wm_images_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
		<option value="bottom" <?php  if($_REQUEST['wm_images_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
		<option value="bleft" <?php  if($_REQUEST['wm_images_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
		<option value="uright" <?php  if($_REQUEST['wm_images_location'] == "uright") { print "selected"; } ?>>Top Right</option>
		<option value="top" <?php  if($_REQUEST['wm_images_location'] == "top") { print "selected"; } ?>>Top Middle</option>
		<option value="uleft" <?php  if($_REQUEST['wm_images_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
		</select>
		</div>
		<div class="cssClear"></div>

		</div>



			</div>
		<div class="underline"><input type="checkbox" name="wm_def_wm" value="1" <?php if($_REQUEST['wm_def_wm'] == "1") { print "checked"; } ?>> When uploading to the All Photos section, autocheck to watermark photos. Default settings when uploading to pages / galleries are in the section / category settings. </div>

	<div>&nbsp;</div>

	<div>
		<div class="underlinelabel">Logo Settings</div>


<div class="underline">
		<div class="cssCell" style="width: 30%; float: left;">Logo File:</div>
		<div class="cssCell" style="width: 60%; float: left;">
	
	<?php 
	if(!is_dir($wm_folder_path)) {
		print "You have not uploaded any watermark files";
	} else {
		?>
		<select name="wm_logo_file">
		<option value="">-------------------------</option>
		<?php 
		$dir = opendir($wm_folder_path); 
		while ($file = readdir($dir)) { 
			if (($file != ".") && ($file != "..")) { 
				print "<option value=\"$wm_folder/$file\""; if($_REQUEST['wm_logo_file'] == "$wm_folder/$file") { print "selected"; } print ">".$file."</option>";
			}
		}
	@closedir($dir); 
	}
	?>
	</select>
	</div>
			<div class="cssClear"></div>


	</div>



	<div class="underline">

		<div class="cssCell" style="width: 30%; float: left;">Location Of Logo:</div>
		<div class="cssCell" style="width: 60%; float: left;">
		 
	<select name="wm_add_logo_location">
	<option value="center" <?php  if($_REQUEST['wm_add_logo_location'] == "center") { print "selected"; } ?>>Center</option>
	<option value="bright" <?php  if($_REQUEST['wm_add_logo_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
	<option value="bottom" <?php  if($_REQUEST['wm_add_logo_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
	<option value="bleft" <?php  if($_REQUEST['wm_add_logo_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
	<option value="uright" <?php  if($_REQUEST['wm_add_logo_location'] == "uright") { print "selected"; } ?>>Top Right</option>
	<option value="top" <?php  if($_REQUEST['wm_add_logo_location'] == "top") { print "selected"; } ?>>Top Middle</option>
	<option value="uleft" <?php  if($_REQUEST['wm_add_logo_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
	</select>
			</div>
			<div class="cssClear"></div>

		</div>
	


		</div>

	<div class="underline"><input type="checkbox" name="wm_def_logo" value="1" <?php if($_REQUEST['wm_def_logo'] == "1") { print "checked"; } ?>> When uploading to the All Photos section, autocheck to add logo to photos. Default settings when uploading to pages / galleries are in the section / category settings.</div>

<div class="pageContent" style="text-align: center;">
<input type="submit" name="submitit" value="Save" class="submit" id="submitButton">
</div>



</form>






</div>

<div class="cssClear"></div>





</div><div class="cssClear"></div>
<div>&nbsp;</div>




<?php  } ?>
