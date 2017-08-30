<?php  
if($_REQUEST['action'] == "doit") {
	if(file_exists($setup['path']."/index.php")) { 
//		print "rename index.php";
		rename($setup['path']."/index.php", $setup['path']."/index.php-OLD");
	}
	if(file_exists($setup['path']."/index.htm")) { 
//		print "rename index.htm";
		rename($setup['path']."/index.htm", $setup['path']."/index.htm-OLD");
	}
	if(file_exists($setup['path']."/index.html")) { 
//		print "rename index.html";
		rename($setup['path']."/index.html", $setup['path']."/index.html-OLD");
	}
	if(file_exists($setup['path']."/indexnew.php")) { 
//		print "rename indexnew to index";
		rename($setup['path']."/indexnew.php", $setup['path']."/index.php");
	}
	updateSQL("ms_settings", "index_page='index.php'");
	session_write_close();
	header("location: index.php?do=activateSite&action=complete");
	exit();
}


if($_REQUEST['action'] == "complete") { ?>
	<div class="pc"><h1>Activate your website!</h1></div>
	<div id="success">It looks like  that was a  success!</div>
	<div>&nbsp;</div>
	<div class="pc">
	If by chance see errors above then you may have to manually rename the indexnew.php to index.php through your FTP program. If you don't see errors then you should be good to go. <br><br><a href="<?php print $setup['temp_url_folder'];?>/" target="_blank">Click here to see your new website</a>. You may have to refresh the page after clicking the link.</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

<?php
	} else {
?>

<div class="pc"><h1>Activate your website!</h1></div>
<?php if($site_setup['index_page'] == "indexnew.php") { ?> 
<div class="pc">So are you ready to go live with your new website? If so, lets do it!</div>
<div>&nbsp;</div>

<div class="pc"><h2>What is going to happen.</2></div>
<div class="pc">Your current link to your <i>sytist</i> site is /indexnew.php. When you activate your website that file will be renamed to /index.php. That will make your <i>sytist</i> your main website.
<br><br>
If you currently have a index.php or index.html  file for an old site it will be not deleted,  but renamed to index.php-OLD.
</div>
<div>&nbsp;</div>
<div style="width: 300px; padding: 10px; float: left; text-align: center;" id=success>
<form method="post" name="activate" action="index.php">
<input type="hidden" name="do" value="activateSite">
<input type="hidden" name="action" value="doit">
<button type="submit" style="font-size: 21px;"  onClick="return confirm('Are you sure you are ready? Click OK to continue. ');">Activate Site Now</button>
</form>
</div>
<div class="clear"></div>
<?php }  else { ?>

<div class=error>It appears your site has already been activated. Nothing else to do here.</div>
<?php } ?>





<?php } ?>
