<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>



<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>The Site Content area is where you create & manage all the pages, galleries, etc ...</h3></div>
		<div class="pc">When Sytist is first installed, it is a clean slate. <b>You decide what type of sections / categories you want to have</b>. 
		<br><br><b>In this demo, the <a href="index.php?do=news&date_cat=100"><b>Clients</b></a> section is where all the galleries are created for selling photos</b>. There are also section created for selling <a href="index.php?do=news&date_cat=102">Store Items</a>, <a href="index.php?do=news&date_cat=103">Services</a>, <a href="index.php?do=news&date_cat=111">Project Proofing</a> and a <a href="index.php?do=news&date_cat=101">Blog</a>. When you <a href="index.php?do=news&action=editCategory">create a new section</a>, the type of section determines how the pages works in that section. <a href="index.php?do=news&date_cat=none">Top Level Pages</a> are for pages like about us, contact, etc...</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>




<?php 

if(empty($_REQUEST['month'])) {
	$_REQUEST['month'] = date('n');
}
if(empty($_REQUEST['year'])) {
	$_REQUEST['year'] = date('Y');
}


if($_REQUEST['action'] == "addDate") {
	require "news.edit.php";
} else if(!empty($_REQUEST['deleteCategoryThumb'])) {
	deleteCategoryThumb();
} else if($_REQUEST['action'] == "uploadPhoto") {
	 include "news.upload.image.php";
} else if($_REQUEST['action'] == "settings") {
	 include "news.settings.php";
} else if($_REQUEST['action'] == "language") {
	 include "news.language.php";
} else if($_REQUEST['action'] == "video") {
	 include "news.video.php";
} else if($_REQUEST['action'] == "watermarking") {
	 include "news.watermarking.php";
} else if($_REQUEST['action'] == "customfields") {
	 include "news.custom.fields.php";
} else if($_REQUEST['action'] == "photoTags") {
	 include "news.photos.tags.php";
} else if($_REQUEST['action'] == "photoDefaults") {
	 include "defaults.php";
} else if($_REQUEST['action'] == "categoryThumbnail") {
	 include "news.category.thumbnail.php";
} else if($_REQUEST['action'] == "layout") {
	 include "news.layout.php";
} else if($_REQUEST['action'] == "defaults") {
	 include "defaults.php";
} else if($_REQUEST['action'] == "more") {
	 include "news.more.php";
} else if($_REQUEST['action'] == "email") {
	 include "news.email.php";
} else if($_REQUEST['action'] == "splash") {
	 include "news.splash.php";
} else if($_REQUEST['action'] == "photoBlog") {
	 	require "photos/gallery.view.php";
} else if($_REQUEST['action'] == "managePhotos") {
	 	require "news.photos.php";
} else if($_REQUEST['action'] == "thumbPreview") {
	 	require "news.preview.thumbnail.php";
} else if($_REQUEST['action'] == "subProds") {
	 	require "news.product.subs.php";
} else if($_REQUEST['action'] == "hpfeatures") {
	 	require "news.home.page.features.php";
} else if($_REQUEST['action'] == "editCategory") {
	if((empty($_REQUEST['cat_id']))&&(empty($_REQUEST['cat_under']))==true) { 
	 	require "news.category.wizard2.php";
	} else { 
	 	require "news.category.php";
	}
} else if($_REQUEST['action'] == "newwiz") {
	 	require "news.category.wizard.done.php";

} else if($_REQUEST['action'] == "editTag") {
	 	editTag();
} else if($_REQUEST['action'] == "deleteTag") {
	 	deleteTag();


} else if($_REQUEST['action'] == "createPhotoBlog") {
	 include "news.photo.blog.php";
} else if(!empty($_REQUEST['deletePageThumb'])) {
	deletePageThumb();
} else if(!empty($_REQUEST['deleteBlogCat'])) {
	deleteBlogCat();

} elseif(!empty($_REQUEST['deleteDate'])) {
	deleteDate();
} else {
	require "news.list.php";
}

function deletePageThumb() { 
	global $setup;
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog_preview='".$_REQUEST['deletePageThumb']."'   ORDER BY bp_order ASC LIMIT  1 ");
	deleteSQL("ms_blog_photos", "WHERE bp_blog_preview='".$_REQUEST['deletePageThumb']."' ", "1");
		$page = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['deletePageThumb']."' ");
	if($pic['pic_no_dis'] == "1") { 
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']);
	}
	$_SESSION['sm'] = "Thumbnail  was deleted";
	session_write_close();
	header("location: index.php?do=news&action=thumbPreview&date_id=".$page['date_id']." ");
	exit();

}
function deleteCategoryThumb() { 
	global $setup;
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_cat='".$_REQUEST['deleteCategoryThumb']."'   ORDER BY bp_order ASC LIMIT  1 ");
	deleteSQL("ms_blog_photos", "WHERE bp_cat='".$_REQUEST['deleteCategoryThumb']."' ", "1");
	if($pic['pic_no_dis'] == "1") { 
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_med']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']);
		@unlink( $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']);
	}
	$_SESSION['sm'] = "Preview photo  was deleted";
	session_write_close();
	header("location: index.php?do=news&action=categoryThumbnail&cat_id=".$_REQUEST['deleteCategoryThumb']." ");
	exit();

}


function deleteBlogCat() { 
	global $setup;
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['deleteBlogCat']."' ");
	if(!empty($cat['cat_id'])) {
		deleteSQL("ms_blog_categories", "WHERE cat_id='".$cat['cat_id']."' ",  "1");
		deleteSQL2("ms_menu_links", "WHERE link_cat='".$cat['cat_id']."' ");
	}

	if(!empty($cat['cat_folder'])) {
		$del_path = $setup['path']."".$setup['content_folder']."".$cat['cat_folder'];
		$dir = opendir($del_path); 
		while ($file = readdir($dir)) { 
			if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
				unlink("$del_path/$file");
				print "<li>--$del_path/$file";
			}
		}
		rmdir("$del_path");
	}
	$_SESSION['sm'] = "Category ".$cat['cat_name']." was deleted";
	session_write_close();

	header("location: index.php?do=news&date_cat=".$cat['cat_under']."");
	exit();
}

function editTag() { 
	global $setup,$site_setup;
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$_REQUEST['tag_id']."' ");
	if(!empty($tag['tag_id'])) {
		$page_link = stripslashes(trim(strtolower($_REQUEST['new_tag'])));
		$page_link = strip_tags($page_link);
		$page_link = str_replace(" ","".$site_setup['sep_page_names']."",$page_link);
		$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);


		$old = $setup['path']."".$setup['content_folder']."/tags/".$tag['tag_folder'];
		$new  =  $setup['path']."".$setup['content_folder']."/tags/$page_link";


		rename($old,$new);
		updateSQL("ms_tags", "tag_folder='".$page_link."', tag_tag='".addslashes(stripslashes($_REQUEST['new_tag']))."'  WHERE tag_id='".$tag['tag_id']."' ");
		$_SESSION['sm'] = "Tag ".$tag['tag_tag']." was renamed";
		session_write_close();
		header("location: index.php?do=news&tag_id=".$tag['tag_id']."");
		exit();
	} else { 
		$_SESSION['smerror'] = "Unable to find tag ID";
		session_write_close();
		header("location: index.php?do=news&tag_id=".$tag['tag_id']."");
		exit();

	}
}

function deleteTag() { 
	global $setup;
	$tag = doSQL("ms_tags", "*", "WHERE tag_id='".$_REQUEST['tag_id']."' ");
	if(!empty($tag['tag_id'])) {
		deleteSQL("ms_tags", "WHERE tag_id='".$tag['tag_id']."' ",  "1");
		deleteSQL2("ms_tag_connect", "WHERE tag_tag_id='".$tag['tag_id']."' ");
	}
	if(!empty($tag['tag_folder'])) {
		$del_path = $setup['path']."".$setup['content_folder']."/tags/".$tag['tag_folder'];
		if(is_dir($del_path)) { 
			$dir = opendir($del_path); 
			while ($file = readdir($dir)) { 
				if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
					unlink("$del_path/$file");
					print "<li>--$del_path/$file";
				}
			}
			rmdir("$del_path");
		}
	}
	$_SESSION['sm'] = "Tag ".$tag['tag_tag']." was deleted";
	session_write_close();
	header("location: index.php?do=news");
	exit();
}


function deleteGalleryFolder($del_path, $gallery) {
	global $setup;
	print "<li><b>Delete gallery folder</b> ".$gallery['gal_folder']." [".$gallery['gal_id']."";
	$dir = opendir($del_path); 
	while ($file = readdir($dir)) { 
		if((($file != ".") && ($file != "..")) AND (!is_dir($del_path."/".$file))==true){ 
			@unlink("$del_path/$file");
			print "<li>--$del_path/$file";
		}
	}

	rmdir("$del_path");
	$sql = "DELETE FROM ms_galleries WHERE gal_id='" .$gallery['gal_id']. "'  LIMIT 1 ";
	if(@mysqli_query($dbcon,$sql)) { } else { echo("Error adding > " . mysqli_error($dbcon) . " < that error"); }
}


function showCalendar($setup, $req, $ms) {

$month = $_REQUEST['month'];
$year = $_REQUEST['year'];

$num_of_days = date("t", mktime(0,0,0,$_REQUEST['month'],1,$year)); 
$firstdayname[$month] = date("D", mktime(0, 0, 0, $_REQUEST['month'], 1, $year)); 
$month_name[$month] = date("F", mktime(0, 0, 0, $_REQUEST['month'], 1, $year)); 
$firstday[$month] = date("w", mktime(0, 0, 0, $_REQUEST['month'], 1, $year)); 
$lastday = date("t", mktime(0, 0, 0, $_REQUEST['month'], 1, $year)); 

print "<table align=center cellpadding=3 cellspacing=0 border=0 width=100%><tr valign=top><td class=pageContent>";
print "<span class=pageTitle>$month_name[$month] $year</span>";
print "</td><td>";
calMenu();
print "</td></tr></table>";

print "<table align=center cellpadding=4 cellspacing=4 border=0 width=100%><tr valign=top>";
print "<td class=calhead width=12%><b>Sun</b></td>";
print "<td class=calhead width=15%><b>Mon</b></td>";
print "<td class=calhead width=15%><b>Tues</b></td>";
print "<td class=calhead width=15%><b>Wed</b></td>";
print "<td class=calhead width=15%><b>Thurs</b></td>";
print "<td class=calhead width=15%><b>Fri</b></td>";
print "<td class=calhead width=13%><b>Sat</b></td>";
print "</tr><tr valign=top>";
$xc[$month] = 1;
$less_days[$month] = $firstday[$month];
$x[$month] = $firstday[$month] + 1;

if($less_days[$month] > 7) {
	$less_days[$month] = $less_days[$month] - 8;
}
if($less_days[$month] == 7) { $less_days[$month] = 1; }
	while ($less_days[$month] > 0) {
		print "<td class=tdmain></td>";
		$less_days[$month] = $less_days[$month] - 1;
	}
	while($xc[$month] <= $num_of_days) {

	if(($xc[$month] == date('d')) AND ($month == date('n')) AND ($year == date('Y'))==TRUE) {

		print "<td id=caltoday >";
	} else {
		print "<td class=cal >";
	}
	print "<table align=center width=100% cellpadding=2 cellspacing=0 border=0><tr valign=top><td width=20%><span id=\"caldate\"><a href=\"index.php?do=calendar&action=viewDate&r_year=$year&r_month=$month&r_day=$xc[$month]\" >$xc[$month]</a></span>";
	print "</td><td width=80% align=right>";	
	print " <a  href=\"index.php?do=calendar&action=addDate&r_year=$year&r_month=$month&r_day=$xc[$month]&date_type=cal\" title=\"Add new calendar entry\">".ai_calendar_add."</a>"; 
	print "</td></tr></table>";

	$cals = whileSQL("ms_calendar", "*,  time_format(date_time, '%h:%i %p')  AS date_show_time", " WHERE DAYOFMONTH(date_date)='$xc[$month]' AND MONTH(date_date)='$month' AND (YEAR(date_date)='$year' OR YEAR(date_date)='0000') AND date_type='cal'");
	while ($cal = mysqli_fetch_array($cals)) {
	print "<div class=callines>";
	if($cal['date_public'] == "2") { print "".ai_not_public.""; } 

	print "<a href=\"index.php?do=calendar&action=viewDate&r_year=$year&r_month=$month&r_day=$xc[$month]\">";
	if($cal['date_time']!=="00:01:00") { print "".$cal['date_show_time']." ";} if(empty($cal['date_title'])) { print "[no title]";} else { print "".$cal['date_title'].""; }
	print "</a>";
	print "</div>";
}
if ($x[$month]%7)  {
echo "</td>";
}  else  {

echo "</td></tr><tr valign=top>";
}

$xc[$month]++;
$x[$month]++;

}
print "</tr></table>";



if(empty($cal_month)) {
	$new_month = date('m');
} else {
	$new_month = $cal_month;
}
if(empty($cal_day)) {
	$new_day= date("d");
} else {
	$new_day = $cal_day + 1;
}
$new_year = $year;

print "</td></tr></table>";


}





function calMenu() {
	global $setup, $_REQUEST;
	$menu_next_month = date("F", mktime(0, 0, 0, $_REQUEST['month'] + 1, 1, $_REQUEST['year'])); 
	$menu_next_month_m = date("m", mktime(0, 0, 0, $_REQUEST['month'] + 1, 1, $_REQUEST['year'])); 
	$menu_next_month_y = date("Y", mktime(0, 0, 0, $_REQUEST['month'] + 1, 1, $_REQUEST['year'])); 

	$menu_last_month = date("F", mktime(0, 0, 0, $_REQUEST['month'] - 1, 1, $_REQUEST['year'])); 
	$menu_last_month_m = date("m", mktime(0, 0, 0, $_REQUEST['month'] - 1, 1, $_REQUEST['year'])); 
	$menu_last_month_y = date("Y", mktime(0, 0, 0, $_REQUEST['month'] - 1, 1, $_REQUEST['year'])); 



	print "<table width=100% align=center cellpadding=3 cellspacing=0 border=0><tr><td align=center>";
	print "<table cellpadding=2 cellspacing=0 border=0><tr valign=top><form method=\"get\" name=\"thedates\" action=\"index.php\"><td>";
	print "<input type=\"hidden\" name=\"do\" value=\"calendar\">";
	print "Go to:";
	?>
	<select name="month">
	<option value="">Select
	<?php 
	if ($_REQUEST['month'] == 1) {
		print "<option value=\"1\" selected>January"; } else {
		print "<option value=\"1\">January"; }
	if ($_REQUEST['month'] == 2) {
		print "<option value=\"2\" selected>February"; } else {
		print "<option value=\"2\">February"; }
	if ($_REQUEST['month'] == 3) {
		print "<option value=\"3\" selected>March"; } else {
		print "<option value=\"3\">March"; }
	if ($_REQUEST['month'] == 4) {
		print "<option value=\"4\" selected>April"; } else {
		print "<option value=\"4\">April"; }
	if ($_REQUEST['month'] == 5) {
		print "<option value=\"5\" selected>May"; } else {
		print "<option value=\"5\">May"; }
	if ($_REQUEST['month'] == 6) {
		print "<option value=\"6\" selected>June"; } else {
		print "<option value=\"6\">June"; }
	if ($_REQUEST['month'] == 7) {
		print "<option value=\"7\" selected>July"; } else {
		print "<option value=\"7\">July"; }
	if ($_REQUEST['month'] == 8) {
		print "<option value=\"8\" selected>August"; } else {
		print "<option value=\"8\">August"; }
	if ($_REQUEST['month'] == 9) {
		print "<option value=\"9\" selected>September"; } else {
		print "<option value=\"9\">September"; }
	if ($_REQUEST['month'] == 10) {
		print "<option value=\"10\" selected>October"; } else {
		print "<option value=\"10\">October"; }
	if ($_REQUEST['month'] == 11) {
		print "<option value=\"11\" selected>November"; } else {
		print "<option value=\"11\">November"; }
	if ($_REQUEST['month'] == 12) {
		print "<option value=\"12\" selected>December"; } else {
		print "<option value=\"12\">December"; }
	?>
	</select></td><td>
	<select name="year">
	<option value="">Select

	<?php 
	$y = 	date('Y') - 1;
	$y10  = date('Y') + 10;
	while ($y <= $y10) {
		
	if ($_REQUEST['year'] == "$y") {
		print "<option value=\"$y\" selected>$y"; } else {
		print "<option value=\"$y\">$y"; }
	$y++;
	}

	print "</select></td><td><button type=\"submit\" name=\"submit\"  class=submit>Go</button></td></form></tr></table>";
	print "</td><td align=right>";
	print "<a href=\"index.php?do=calendar&month=$menu_last_month_m&year=$menu_last_month_y\"><B><< $menu_last_month $menu_last_month_y</B></a>";
	print "&nbsp;&nbsp;";
	print "<a href=\"index.php?do=calendar&month=$menu_next_month_m&year=$menu_next_month_y\"><B>$menu_next_month $menu_next_month_y >></B></a>";
	print "</td></tr></table>";


}

function dropMonths($fn, $match) {
	print "<select name=\"$fn\">";
		print "<option value=\"0\">Select"; 
	$tm = 2;
	while($tm <= 13) {

			if(date("m", mktime(0,0,0,$tm,0,0)) == $match) { $selected = "selected"; }
		print "<option value=\"".date("m", mktime(0,0,0,$tm,0,0))."\" $selected>".date("F", mktime(0,0,0,$tm,0,0)).""; 
		unset($selected);
	$tm++;
	}

}

function dropDays($fn, $match) {
	print "<select name=\"$fn\">";
		print "<option value=\"0\">Select"; 
	$tm = 1;
	while($tm <= 31) {

			if($tm == $match) { $selected = "selected"; }
		print "<option value=\"$tm\" $selected>$tm"; 
		unset($selected);
	$tm++;
	}

}
function dropYears($fn, $match) {
	print "<select name=\"$fn\">";
		print "<option value=\"0\">Select"; 
	$tm = date('Y') - 1;
	while($tm <= date('Y')+10) {

			if($tm == $match) { $selected = "selected"; }
		print "<option value=\"$tm\" $selected>$tm"; 
		unset($selected);
	$tm++;
	}

}

?>
