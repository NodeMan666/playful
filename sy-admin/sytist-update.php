<?php require "w-header.php"; ?>
<div class="pc center"><h1>Sytist Update</h1></div>
<script>
	function sytistupdateaction(action) { 
		$(".buttons").hide();
		if(action == "fetchzip") { 
			$("#updatetext").html("Hang tight  ... Fetching the update zip file.");
		}
		if(action == "unzip") { 
			$("#updatetext").html("Unzipping! Please wait .... ");
		}
		if(action == "rename") { 
			$("#updatetext").html("Now here is the fun part, almost done!");
		}
		$("#updatemessage").show();
		pagewindoweditnoloading("sytist-update.php?noclose=1&nofonts=1&nojs=1&action="+action);

	}

	function sytistupdateactionunzip() { 
		sytistupdateaction('unzip');
	}
	function sytistupdateactionunrename() { 
		sytistupdateaction('rename');
	}

</script>
<div class="center" style="font-size: 17px;"><?php
$upgrade_folder = "sytist_UPGRADE_files";
$upgrade_zip_name = "sytist_UPGRADE_files.zip";
$update_folder = "sytist-update";
$zip_to = $update_folder."/files";
$backup_folder = $update_folder."/backup";

if(!is_dir($setup['path']."/".$update_folder."")) {
	$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
	} else {
		$perms = 0755;
	}
	mkdir("".$setup['path']."/".$update_folder."", $perms);
	chmod("".$setup['path']."/".$update_folder."", $perms);
	mkdir("".$setup['path']."/".$update_folder."/files", $perms);
	chmod("".$setup['path']."/".$update_folder."/files", $perms);
}

if(!is_dir($setup['path']."/".$update_folder."")) {
	print "Unable to create a update / backup folder. Your server may not have proper permissions for automatic updates. <a href=\"https://www.picturespro.com/sytist-manual/installation/upgrading/\" target=\"_blank\">Click here for instructions on manually updating</a>.";
	die();
}
if(!is_writable($setup['path']."/".$update_folder."")) {
	print "Backup folder not writable. Your server may not have proper permissions for automatic updates. <a href=\"https://www.picturespro.com/sytist-manual/installation/upgrading/\" target=\"_blank\">Click here for instructions on manually updating</a>.";
	die();
}

if($_REQUEST['action'] == "unzip") { 
	$zip = new ZipArchive;
	$res = $zip->open($setup['path'].'/'.$update_folder.'/'.$upgrade_zip_name.'');
	if ($res === TRUE) {
	  $zip->extractTo($setup['path'].'/'.$update_folder.'/files');
	  $zip->close();
	  echo 'Woot! Moving along ....';
	?>

	<script>
	$(document).ready(function(){
		// sytistupdateactionunrename();
	});
	</script>
	<?php 
	} else {
	  echo 'doh! Something happened and could not unzip the file! Your server may not have proper permissions for automatic updates or does not have the zip extention installed. <a href=\"https://www.picturespro.com/sytist-manual/installation/upgrading/\" target=\"_blank\">Click here for instructions on manually updating</a>';
		die();
	}
	unlink($setup['path'].'/'.$update_folder.'/'.$upgrade_zip_name);
}

if($_REQUEST['action'] == "fetchzip") { 
	if($setup['sytist_hosted'] !== true) { 
		$reg = doSQL("ms_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 2 HOUR), '%M %e, %Y ')  AS reg_date", ""); 
	} else { 
		$sh = 1;
	}

	$updateversion = url_get_contents("https://www.picturespro.com/sytistupdateauto2.php?version=".$site_setup['sytist_version']."&reg=".$reg['reg_key']."&sh=".$sh."");
	if(!empty($updateversion)) { 
		$d = explode("|",$updateversion);
		$cv = explode("~",$d[0]);
		$upfile = explode("~",$d[1]);
		$file = $upfile[1];
	}

	$newfile = $setup['path'].'/'.$update_folder.'/'.$upgrade_zip_name.'';
	if ( @copy($file, $newfile) ) {
		//echo "Success! Update zip file was downloaded via copy. ";
		if(FileSize($newfile) < 100000) { 
			$ch = curl_init($file);
			$fp = fopen($newfile, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt ($ch, CURLOPT_USERAGENT, 'sytist/php');
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    
			curl_exec($ch);
			// echo 'Curl error: ' . curl_error($ch);
			curl_close($ch);

			fclose($fp);

		}
	}else{
		// If copy failes, try using cURL ...... 
		$ch = curl_init($file);
		$fp = fopen($newfile, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'sytist/php');
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
		clearstatcache();
		//	print "<li>File size for ".$newfile.": ".FileSize($newfile) ."</li>";

	if(FileSize($newfile) < 100000) { 
		print  "Sorry, unable to fetch the update files. Try again later or <a href=\"https://www.picturespro.com/sytist-manual/installation/upgrading/\" target=\"_blank\">click here for instructions on manually updating</a>. ";
		die();
	}

	if(file_exists($newfile)) { 
		print "Success! The zip file was downloaded ";
		$zip_download_success = true;
	} else { 
		print  "Sorry, unable to fetch the update files. Try again later or <a href=\"https://www.picturespro.com/sytist-manual/installation/upgrading/\" target=\"_blank\">click here for instructions on manually updating</a>.";
	}

	$pic_filesize= @FileSize($newfile); 
		if(!empty($pic_filesize)) {
			if($pic_filesize > 1024000) {
				$bytes  = $pic_filesize / 1024000;
				$bytes = round($bytes,2). "MB";
			} else {
				$bytes  = $pic_filesize / 1024;
				$bytes = round($bytes,0). "KB";
			}
			print "(".$bytes.")";
		}

	rrmdir($setup['path']."/".$backup_folder);
	?>
	<script>
	$(document).ready(function(){
		// sytistupdateactionunzip();
	});
	</script>
	<?php 
}

if($_REQUEST['action'] == "rename") { 

	if(!is_dir($setup['path']."/".$backup_folder."")) {
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/".$backup_folder."", $perms);
		chmod("".$setup['path']."/".$backup_folder."", $perms);
	}


	if(file_exists("".$setup['path']."/".$zip_to."")) {
		$theFiles = array();
		$theFolders = array();
		$misc_path = $setup['path']."/".$zip_to."/sytist_UPGRADE_files";
		$dir = opendir($misc_path); 
		while ($file = readdir($dir)) { 
			if (($file != ".") && ($file != "..")) {
				$file_count++;
				if($file == "sy-admin") { 
					@rename($setup['path']."/".$setup['manage_folder'],$setup['path']."/".$backup_folder."/".$setup['manage_folder']);
					@rename($setup['path']."/".$zip_to."/".$upgrade_folder."/".$file,$setup['path']."/".$setup['manage_folder']);

				} else { 
					@rename($setup['path']."/".$file,$setup['path']."/".$backup_folder."/".$file);
					@rename($setup['path']."/".$zip_to."/".$upgrade_folder."/".$file,$setup['path']."/".$file);
				}
					// print "<li>Rename : ".$setup['path']."/".$zip_to."/".$upgrade_folder."/".$file." to ".$setup['path']."/".$file;
			}
		}
		closedir($dir); 
	}

	rmdir($misc_path);
	include "upgrades.php";
	$_SESSION['editthemeupgrade'] = 1;
	print "Cool! Looks like you are almost done. Refresh this page to apply database changes.<br><br> After that, go to Site Design -> Edit My Theme and then click the Save Changes button (so we can load any new CSS). Once you do that, you can exit the editor and you are done.";
//	header("location: index.php");
//	session_write_close();
//	exit();
}


 function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 }
?>
</div>
<?php if(empty($_REQUEST['action'])) { ?>
<div class="pc center">A new version of Sytist is available. When updating you will not lose any data. To get started, click the Fetch Update button below.</div>
<?php } ?>
<div id="updatemessage" class="hidden pc center" style="font-size: 21px;"><img src="graphics/loading1.gif" align="absmiddle"><span id="updatetext"></span></div>
<div>&nbsp;</div>
<style>
.ubuttons a  {  background: #E7933F; color: #FFFFFF; padding: 8px;   text-shadow:0px 0px 1px #CD731E; font-size: 21px;} 
.ubuttons a:hover  {  background: #F1A863; color: #FFFFFF; padding: 8px;   text-shadow:0px 0px 1px #CD731E; font-size: 21px;} 

</style>

<div class="center ubuttons">
<?php if(empty($_REQUEST['action'])) { ?>
<a href="" onclick="sytistupdateaction('fetchzip'); return false;">Fetch Update</a>
<br><br>1 / 4
<?php } ?>
<?php if(($_REQUEST['action'] == "fetchzip")&&($zip_error !== true)==true) { ?>
 <a href="" onclick="sytistupdateaction('unzip'); return false;">Unzip This</a>
<br><br>2 / 4
<?php } ?>
<?php if(($_REQUEST['action'] == "unzip")&&($unzip_error !== true)==true) { ?>

<a href="" onclick="sytistupdateaction('rename'); return false;">Make it Happen!</a>
<br><br>3 / 4
<?php } ?>
<?php if(($_REQUEST['action'] == "rename")&&($unzip_error !== true)==true) { ?>

<?php $mytheme= doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ORDER BY css_order ASC  ");
	if(!empty($mytheme['css_id'])) { ?>
	<a href="index.php">Refresh Page</a>
<?php } ?>

<br><br>4/4
<?php } ?>

</div>
<?php require "w-footer.php"; ?>