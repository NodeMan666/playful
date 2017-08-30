<?php
if($_REQUEST['submitit'] == "yes") { 
	$folder = trim($_REQUEST['folder']);
	$folder = strtolower($_REQUEST['folder']);



	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."".$setup['content_folder']."")), -4); 
	if($parent_permissions == "0755") {
		$perms = 0755;
	//	print "<li>A";
	} elseif($parent_permissions == "0777") {
		$perms = 0777;
	//	print "<li>B";
	} else {
			$perms = 0755;
	//	print "<li>C";
	}
	if(is_dir($setup['path']."".$setup['content_folder']."/".$folder)) { 
		$_SESSION['foldererror'] = "That directory already exists on the server. Enter in a different directory name";
		header("location: index.php?do=people&view=allcontracts&sub=folder");
		session_write_close();
		exit();
	}

	mkdir("".$setup['path']."".$setup['content_folder']."/$folder", $perms);
	chmod("".$setup['path']."".$setup['content_folder']."/$folder", $perms);
	print "Create: ".$setup['path']."".$setup['content_folder']."/".$folder."/index.php";
//	copy("".$setup['path']."".$setup['content_folder']."/default.php", "".$setup['path']."".$setup['content_folder']."/".$folder."/index.php");

	$fp = fopen("".$setup['path']."".$setup['content_folder']."/".$folder."/index.php", "w");
		
	$info =  "<?php\ninclude \"../sy-config.php\";\nrequire \$setup['path'].\"/sy-inc/contracts/contract.php\";?>";

	fputs($fp, "$info\n");
	fclose($fp);
	updateSQL("ms_settings", "contract_folder='".$folder."' ");
	$_SESSION['good'] = "Folder has been created";
	header("location: index.php?do=people&view=allcontracts&sub=folder");
	session_write_close();
	exit();
}
?>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">Contract Directory / Link</span></div> 
<div>&nbsp;</div>
<script>
function showcreatefolder() { 
	$("#makedir").slideToggle(200);
}
</script>
<?php if(isset($_SESSION['good'])) { ?>
<div class="pc center" style="font-size: 21px; color: #008900; font-weight: bold;">Directory successfully created. </div>
<div class="pc center">You can now continue doing what you were doing.</div>
<?php 
}
?>
<?php if(!empty($site_setup['contract_folder'])) { ?>
<?php if(!isset($_SESSION['good'])) { ?>
<div class="pc center"><h3 class="green bold">You have already created a contract directory.</h3></div>
<?php } else { 
unset($_SESSION['good']);

}
?>
<div class="pc center"><a href="" onclick="showcreatefolder(); return false;">Recreate</a></div>
<?php } ?>
<?php if(isset($_SESSION['foldererror'])) { ?>
	<div class="error center"><?php print $_SESSION['foldererror'];?></div>
	<div>&nbsp;</div>

	<?php 
	unset($_SESSION['foldererror']);
}
?>


<div>&nbsp;</div>
<div id="makedir" <?php if(!empty($site_setup['contract_folder'])) { ?>class="hide"<?php } ?>>

<div class="pc center" style="font-size: 17px">You first need to create a new directory for contracts which will be part of the  link sent to clients to view their contract. <br><br>This only needs to be done once.</div>
<div>&nbsp;</div>
<div class="pc center" style="font-size: 17px">No spaces or special characters. Example: contract</div>

<div>&nbsp;</div>
<form method="post" name="cf" id="cf" action="index.php">

<div style="margin: auto; text-align: center; font-size: 19px;">
<?php print $setup['url'].$setup['temp_url_folder'];?>/<input type="text" name="folder" id="folder" value="contract" size="20" style="font-size: 19px;">

<input type="hidden" name="do" value="people">
<input type="hidden" name="view" value="allcontracts">
<input type="hidden" name="sub" value="folder">
<input type="hidden" name="submitit" value="yes">
<input type="submit" name="submit" value="Create" class="submit">

</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

</form>