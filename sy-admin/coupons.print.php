<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$csettings = doSQL("ms_coupons_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
 <HEAD>
  <TITLE>Print Coupons</TITLE>
  <META NAME="Author" CONTENT="">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<STYLE type="text/css"><!--  
<?php print $csettings['coupon_print_css']; ?>

--></style>
</head>
<body>
<?php
$perRow = 8;
$coupons = whileSQL("ms_coupons", "*", "WHERE coup_session='".$_REQUEST['batch']."'");
while($coupon = mysqli_fetch_array($coupons)) {
	$x++;
	print "<div id=coupon>";
	$mess = str_replace("[DOWNLOAD_CODE]", $coupon['coup_code'],$coupon['coup_descr']);
	$mess = str_replace("[URL]", $setup['url'].$setup['temp_url_folder'],$mess);
	print nl2br($mess);
	print "</div>";

	if($x%$perRow) { 
	} else {
		print "<div id=pageSpacer>&nbsp;</div>";
	}
}

?>
</body></html>
