<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
if(is_array($db_user_array)) {
	$db_user = $db_user_array[ rand( 0, ( sizeof($db_user_array) -1 ) ) ];  
} else {
	$db_user = $setup['pc_db_user'];
}
$dbcon = @mysqli_connect($setup['pc_db_location'],$db_user,$setup['pc_db_pass'],$setup['pc_db'],$setup['db_port'],$setup['db_socket']);
if (! @mysqli_select_db("".$setup['pc_db']."") ) {	$dberror .="<div>Unable to locate the database: \"".$setup['pc_db']."\". Make sure you have entered in the database name correctly.</div>";	}
if (!$dbcon) {	$dberror = "<div>Unable to connect to the database: " .mysqli_error($dbcon)."</div>"; }
date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>sytist Installation Complete!</title>
<link rel="stylesheet" href="css/white.css" type="text/css">
<script type="text/javascript">
function addCheck(theForm)

{ 
    Form=document.theForm; 
	Form.submission.disabled = true;
	Form.submission.value = 'please wait .....';
	Form.submit();  
}
</script>

</head>
<?php 
// if(!is_dir($setup['path']."/install")) { die(); } 
?>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
	<div style="width: 600px; margin: auto;">
<div class="pageContent"><h1>Good to Go! </h1></div>

<?php

if(is_dir($setup['path']."/install")) {
	$del_path = $setup['path']."/install";

	$dir = opendir($del_path); 
	while ($file = readdir($dir)) { 
		if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
			@unlink("$del_path/$file");
		//	print "<li>--$del_path/$file";
		}
	}
	if(rmdir("$del_path")) {

	} else {
		print "<div class=\"errorMessage\">Unable to remove the \"install\" directory. Please delete the \"install\" directory  via your FTP program.</div>";
	}
} else {
	//	print "<div class=\"pageContent\"><h3>The install directory has already been removed.</h3></div>";
}
if(($_REQUEST['installingon'] !== "subdomain")&&($_REQUEST['installingon'] !== "root")==true) { 
	include "createhtaccess.php";
}
if($_REQUEST['installingon'] !== "root") { 

	if(file_exists($setup['path']."/index.php")) { 
//		print "rename index.php";
		rename($setup['path']."/index.php", $setup['path']."/index.php-".date('Ymdhis'));
	}
	if(file_exists($setup['path']."/index.htm")) { 
//		print "rename index.htm";
		rename($setup['path']."/index.htm", $setup['path']."/index.htm-".date('Ymdhis'));
	}
	if(file_exists($setup['path']."/index.html")) { 
//		print "rename index.html";
		rename($setup['path']."/index.html", $setup['path']."/index.html-".date('Ymdhis'));
	}
	if(file_exists($setup['path']."/indexnew.php")) { 
//		print "rename indexnew to index";
		rename($setup['path']."/indexnew.php", $setup['path']."/index.php");
	}
	updateSQL("ms_settings", "index_page='index.php'");
}

$_SESSION['installingon'] = $_REQUEST['installingon'];
updateSQL("ms_settings","css='1' ");

header("location: index.php?installingon=".$_REQUEST['installingon']."");
session_write_close();
exit();

?>

<div class="pageContent"><h3>Now we have that out of the way, let's create our administration username and password! </h3>
</div>
<div class="pageContent">
<form name="theForm" action="index.php" method="GET">
<input type="hidden" name="installingon" value="<?php print $_REQUEST['installingon'];?>">
<input type="button" value="Create My Admin Login" class=submit  name="submission" onClick="addCheck(this.form.name);">
</form>

</div>
</body>
</html>
