<?php
if(empty($path)) { 
	$path = "../";
}
include $path."sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
header('Content-Type: text/html; charset=utf-8');
require $path."".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}

$dbcon = dbConnect($setup);

$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
$loggedin = doSQL("ms_admins", "*", "WHERE admin_id='".$_SESSION['office_admin_id']."' ");
if($sytist_store == true) { 
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
	$store = doSQL("ms_store_settings", "*", "");
}

$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php print $site_setup['website_title'];?></title>
<?php if($_REQUEST['nojs'] !== "1") { ?>
<link rel="stylesheet" href="css/white.css" type="text/css">
 <script language="javascript" src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
 <script type="text/javascript" src="uploadify/jquery.uploadify.js"></script>
 <script language="javascript" src="js/admin.js" type="text/javascript"></script>
 <script language="javascript" src="js/forms.js" type="text/javascript"></script>
 <script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="jscolor/jscolor.js"></script>
<link rel="stylesheet" href="js/redactor/redactor.css" />
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/sytist.css?<?php print MD5($site_setup['sytist_version']); ?>">
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/animation.css?<?php print MD5($site_setup['sytist_version']); ?>">
<script src="js/redactor/redactor.js"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />
<?php } ?>
<?php 
if($_REQUEST['nofonts'] !== 1) { 
	if($_REQUEST['css_id'] > 0) { 

		$fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' GROUP BY font ORDER BY font ASC ");
		if(mysqli_num_rows($fonts) > 0) { 
			while($font = mysqli_fetch_array($fonts)) { 
				if($f > 0) { 
					$add_fonts .= "|";
				}
				$add_fonts .= str_replace(" ","+",$font['font']);
				$f++;
			}
			?>
		<link href='//fonts.googleapis.com/css?family=<?php print $add_fonts;?>' rel='stylesheet' type='text/css'>
		<?php } 
	}
}?>
</head>
 <script>
$(document).ready(function(){

	 mytips(".tip","tooltip");
	 myinputtips(".inputtip","tooltip");
	$(".iconhover").hover(
	  function () {
		$(this).attr("src", $(this).attr("hover"));
	  },
	  function () {
		$(this).attr("src", $(this).attr("off"));
	  }
	);


	$(".moreinfo").hover(
	  function () {
		$(this).find('.info').html($("#"+$(this).attr("info-data")).html());
		$(this).find('.info').css({"margin-top":"16px", "margin-left":"-"+$(this).find('.info').width()+"px"}).stop(true,true).fadeIn(100);
	  },
	  function () {
		$(this).find('.info').stop(true,true).fadeOut(100);
	  }
	);

});
</script>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<div id="framewindow">
<?php if(($_REQUEST['noclose'] <=0)AND($noclose<=0)==true) { ?>
<div style="float: right; clear: right; padding: 4px;"><a href="javascript:closeFrame();"><?php print ai_exit;?></a></div>
<?php } ?>
<?php
if($setup['demo_mode'] == true) { 
	require "_demo_mode.php";
}
?>
