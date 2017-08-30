<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php adminsessionCheck(); ?>
<?php if($setup['demo_mode'] == true) { die("No access to this page in demo mode."); } ?>
<?php if($setup['sytist_hosted'] == true) { die(); } ?>
<div class="pc"><h1>Edit sy-config.php file</h1>
This is the configuration file for your Sytist installation. <b><br>ONLY EDIT THIS FILE IF YOU HAVE BEEN INSTRUCTED TO OR KNOW WHAT YOU ARE DOING. <br>ANY ERRORS IN THIS FILE CAN CAUSE THE WHOLE SITE TO GO DOWN!!</b><br>A backup file will be created when making any changes to this file.
</div>
<div>&nbsp;</div>

<?php if($_POST['action'] == "saveconfig") { 
	$add_hash= substr(md5(date('ymdHis').$site_setup['salt']),0,10);

	$backupfile = $setup['path']."/sy-config.php-BAK--Date--".date('Y-m-d-h-i-s')."--".$add_hash;
	if(!copy($setup['path']."/sy-config.php",$backupfile)) { 
		die("Unable to create a backup file so no changes have been saved. You will need to manually change this file by downloading via FTP and editing or through the file manager in your hosting control panel");
	}
	$fp = fopen($setup['path']."/sy-config.php", "w");
	$info =  stripslashes(trim($_REQUEST['configcontent'])); 
	fputs($fp, "$info");
	fclose($fp);
	$_SESSION['sm'] = "Config FIle Updated";
	$_SESSION['backupfile'] = $backupfile;
	session_write_close();
	header("location: index.php?do=editconfig");
	exit();
}
$file = file_get_contents($setup['path']."/sy-config.php", FILE_USE_INCLUDE_PATH);
if(!empty($_SESSION['backupfile'])) { ?>
<div class="pc center">A backup file was made of the previous version here: : <?php print $_SESSION['backupfile'];?></div>
<?php 
unset($_SESSION['backupfile']);
} ?>

<form method="post" name="dso" action="index.php">
<input type="hidden" name="do" value="editconfig">
<div class="pc center">
<textarea name="configcontent" rows="40" cols="50" class="field100"><?php print $file;?></textarea>
</div>
<div class="pc center">
<input type="hidden" name="action" value="saveconfig">
<input type="submit" name="submit" value="Save" class="submit">
</div>
</form>