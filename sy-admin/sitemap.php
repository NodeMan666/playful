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
require $path."".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
$loggedin = doSQL("ms_admins", "*", "WHERE admin_id='".$_SESSION['office_admin_id']."' ");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>
<?php if($_REQUEST['nojs'] !== "1") { ?>
<link rel="stylesheet" href="css/white.css" type="text/css">

<?php } ?>

<?php
function updateSiteMap() { 
	global $setup;

	$html .= '<?xml version="1.0" encoding="UTF-8"?>
		';
	$html .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	';
	$html .='	<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].'</loc>
		</url>

		';
		?>





	<?php $cats = whileSQL("ms_blog_categories", "*", "ORDER BY cat_name ASC ");
	while($cat = mysqli_fetch_array($cats)) {

		$html .='<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].''.$cat['cat_folder'].'/</loc>
		</url>
		
		';

	}
	?>

	<?php $dates = whileSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_public='1' AND private='0' AND page_404='0' ORDER BY date_id DESC ");
	while($date = mysqli_fetch_array($dates)) { 
	$html .='<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].''.$date['cat_folder'].'/'.$date['date_link'].'/</loc>
		</url>

		';

	}
	?>
	<?php $tags = whileSQL("ms_tags", "*", "ORDER BY tag_tag ASC ");
	while($tag = mysqli_fetch_array($tags)) {
		$html .='<url>
			<loc>'.$setup['url'].$setup['temp_url_folder'].'/tags/'.$tag['tag_folder'].'/</loc>
		</url>

		';

	}

	$html .='</urlset>';

	$fp = fopen("".$setup['path']."/sy-sitemap.xml", "w");
	fputs($fp, "$html");
	fclose($fp);
}

updateSiteMap();

?>


