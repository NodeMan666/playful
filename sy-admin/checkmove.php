<div class="pc"><h1>Moving from sub domain</h1></div>
<?php
if($_REQUEST['action'] == "createhtaccess") { 
	include "createhtaccess.php";
	print "<div class=\"success\">Your htaccess file has been created. Your move should be complete.</div>";
	updateSQL("ms_history", "installingon='' ");
	$history = doSQL("ms_history", "*", "" );
}
?>

<div class="pc">
If you have installed Sytist on a sub domain and ready go live and make it your main website, you need to move the files and folders up a level to the domain folder. <br><br>
If you plan on leaving Sytist on this sub domain, <a href="index.php?action=removesubdomainhistory"  class="confirmdelete" confirm-title="Are you sure?" confirm-message="Are you sure you want to hide this message?">click here to remove this for now</a>. Do not click the htaccess option below.
<br><br>
To move the files, connect to your website via FTP. Create a backup folder. Any files or folders in the main website folder (example: public_html) in red below, move into that backup folder. After you have moved them refresh this page to doouble check.
<br><br>
Then go into your sub domain folder you have Sytist installed in. Select ALL FILES AND FOLDERS and drag them up one level. You should have an option in your FTP program to drag them into another folder.
<br><br>
<span class="bold">IMPORTANT: After you have move to the main folder, return to this page to create a .htaccess file that needs to be created</span>. <br><br><span class="bold">If you have completed the move of your site into the main folder, <a href="index.php?do=checkmove&action=createhtaccess">click here to create that htaccess file now</a></span>.
</div>
<div>&nbsp;</div>
<?php
	$dir_name = "../";

	$up_dir_name = "../../";
	$x = 1;


	// print "<h1>Up a level</h1>";


	$dir = opendir($up_dir_name); 
	$upDirList = array();
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..")) { 
		//	if(is_dir($dir_name."/$file")) {
	//			print  "<li>$dir_name/$file/";
				array_push($upDirList, $file);
		//	}
			if(!is_dir($dir_name."/$file")) {
			//	print "<li>$file";
			}
		} 
	} 

	@closedir($dir); 
	asort($upDirList);

	// print "<h1>This directorey</h1>";

	$dir = opendir($dir_name); 
	$dirList = array();
	while ($file = readdir($dir)) { 
		if (($file != ".") && ($file != "..")) { 
//			if(is_dir($dir_name."/$file")) {
//				print  "<li>/$file/";
				array_push($dirList,$file);
//			}
			if(!is_dir($dir_name."/$file")) {
			//	print "<li>$file";
			}
		} 
	} 

	@closedir($dir); 
	asort($dirList);

foreach($dirList AS $d) { 
	if(in_array($d,$upDirList)) { 
		print "<li class=\"error\">$d</li>";
	} else { 
		print "<li class=\"success\">$d</li>";
	}
}
// print "<pre>";
// print_r($dirList);
// print_r($upDirList);



?>
