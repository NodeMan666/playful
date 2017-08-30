<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
if($setup['ob_start_only'] == true) { 
	ob_start();  
} else { 
	if ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ) {  
		ob_start( "ob_gzhandler" );  
	}  
	else {  
		ob_start();  
	}  
}
$sytist_store = true;
header('Content-Type: text/html; charset=utf-8');
unset($_SESSION['query']);

require "../".$setup['inc_folder']."/functions.php"; 
require "form.functions.php";
require "admin.functions.php"; 
require "admin.icons.php";
if($setup['sytist_hosted'] == true) { 
	require $setup['path']."/sy-hosted.php";
}
$dbcon = dbConnect($setup);
// Add a check to the registration to see if store is valid 

$site_setup = doSQL("ms_settings", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
if($sytist_store == true) { 
	require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
	$store = doSQL("ms_store_settings", "*", "");
}
$history = doSQL("ms_history", "*", "  ");

require("photos.functions.php");
include "upgrades.php";
$booksettings = doSQL("ms_bookings_settings", "*", "");

$css = doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
if($_REQUEST['do']!=="login") {
	$_SESSION['admin_ret'] = $_SERVER["REQUEST_URI"];
}

$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
foreach($lang AS $id => $val) {
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

define(_selected_,"Selected"); 


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<?php		$style_sheet = "white.css"; ?>
<link rel="stylesheet" href="css/<?php print $style_sheet;?>?v=<?php print $site_setup['sytist_version'];?>" type="text/css">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width">
<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$css['css_id']."' ORDER BY font ASC ");
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
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:light,regular,medium,thin,italic,mediumitalic,bold" title="roboto">
<?php } ?>
<script type="text/javascript" src="jscolor/jscolor.js"></script>
<script src="js/jquery-1.8.3.min.js"></script>
  <script type="text/javascript" src="uploadify/jquery.uploadify.js"></script>
 <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script language="javascript" src="js/admin.js?v=<?php print $site_setup['sytist_version'];?>" type="text/javascript"></script>
<script language="javascript" src="js/forms.js?v=<?php print $site_setup['sytist_version'];?>" type="text/javascript"></script>
<link rel="stylesheet" href="js/redactor/redactor.css?v=<?php print $site_setup['sytist_version'];?>" />
<script src="js/redactor/redactor.js?v=<?php print $site_setup['sytist_version'];?>"></script>
<link rel="stylesheet" href="css/smoothness/jquery-ui.min.css" type="text/css">
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/sytist.css?<?php print MD5($site_setup['sytist_version']); ?>">
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/animation.css?<?php print MD5($site_setup['sytist_version']); ?>">
<title><?php print "".$site_setup['website_title'];?>
 | Admin
<?php 
if($_REQUEST['do'] == "products") {
	print " | Music / Products";
}
if($_REQUEST['do'] == "orders") {
	print " | Orders";
}
if($_REQUEST['do'] == "settings") {
	print " | Settings";
}
if($_REQUEST['do'] == "pages") {
	print " | Pages";
}
if($_REQUEST['do'] == "orders") {
	print " | Orders";
}
if($_REQUEST['do'] == "people") {
	print " | People";
}

if($_REQUEST['do'] == "forms") {
	print " | Forms";
}
if($_REQUEST['do'] == "news") {
	print " | Content";
}
if($_REQUEST['do'] == "calendar") {
	print " | Calendar";
}

if($_REQUEST['do'] == "photos") {
	print " | Photo Albums";
}
if($_REQUEST['do'] == "comments") {
	print " | Comments";
}

if($_REQUEST['do'] == "ffd") {
	print " | Fan Free Download";
}
if($_REQUEST['do'] == "stats") {
	print " | Stats";
}
?>

</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if(file_exists($setup['path']."/".$setup['misc_folder']."/favicons/icon.png")) { ?>
<link rel="apple-touch-icon" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-180.png"> 
<link rel="icon"  type="image/png"  href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-16.png">
<?php } ?>

</head>
<?php 
if(!empty($_REQUEST['sm'])) {
	$_SESSION['sm'] = $_REQUEST['sm'];
}
if($_REQUEST['regstatus'] == "complete") {
	$_SESSION['sm'] = "REGISTRATION COMPLETE! THANK YOU! YOU ARE READY TO GET STARTED";
}

if(!empty($_SESSION['smerror'])) {?>
<script>
 $(document).ready(function(){
setTimeout(showErrorMessage,400);
setTimeout(hideErrorMessage,8000);

});
</script>

<?php 
	$error_message = $_SESSION['smerror'];
	unset($_SESSION['smerror']);
}

if(!empty($_SESSION['sm'])) {
	?>
<script>
 $(document).ready(function(){
setTimeout(showSuccessMessage,400);
setTimeout(hideSuccessMessage,5000);

});
</script>

	<?php 
	$success_message = $_SESSION['sm'];
	unset($_SESSION['sm']);
}
?>
<div id="successMessage"><?php  print stripslashes($success_message);?></div>
<div id="errorMessage"><?php  print stripslashes($error_message);?></div>


<?php
if(countIt("ms_admins","")<=0) {
	include "admin.create.php";
	exit();
}

if($_REQUEST['do']=="login") {
	include "admin.login.php";
	include "footer.php";
	exit();
}

if($_REQUEST['do'] == "reset") {
	include "admin.reset.php";
	die();
}

if(getUserIp() == "188.226.226.40") { 
	?><div class="error" style="font-size: 50px;">Something is wrong<br><br>
	If you think this is in error, email info@picturespro.com</div>
	<?php 
	sendWebdEmail("info@picturespro.com", "Tim", "install@picturespro.com", "Stolen ".$_SERVER['HTTP_HOST']."", " Stolen ".$_SERVER['HTTP_HOST']." ".getUserIP()."", "".$_SERVER['HTTP_HOST']."\r\n".$setup['url']."".$setup['temp_url_folder']."/".$setup['manage_folder']."\r\rIP address: ".getUserIP()."",$type);
	die();
}

// Checking to see if the admin is logged in
adminsessionCheck();
$loggedin = doSQL("ms_admins", "*", "WHERE admin_id='".$_SESSION['office_admin_id']."' ");
if($setup['sytist_hosted'] !== true) { 
	require "admin.php";
}
require "header.php";


?>
<div id="sideeditbg" style="position: fixed; width: 100%; height: 100%; left; 0; top: 0; background: rgba(0,0,0,.5); z-index: 2; display: none;"></div>
<div id="sideedit" style="position: absolute; right: 0; z-index: 10; display: none; width: 100%; max-width: 480px;">
	<div id="sideeditinner" style=" background: #FFFFFF;">
	</div>
</div>


<div id="log" style="display: none; position: fixed; top:0; left: 0; background: #890000; color: #FFFFFF; z-index: 1000;"></div>
<div id="windowloading" style="width:300px; position: fixed; left: 50%; margin-left: -150px; padding: 24px; text-align: center; top: 40%; display: none; z-index: 500; background: #545454; color: #ffffff;">Loading...</div>
<div style="" class="pform" id="windowedit">
	<div style="position: absolute; right:8px; top: 8px; display: none; z-index: 500;" id="windoweditclose"><a href="" onclick="closewindowedit(); return false;" class="the-icons icon-cancel" style="font-size: 40px;"></a></div>
	<div id="windoweditinner" style="padding: 24px;">
	</div>
</div>

<?php 
if(!empty($_REQUEST['editslide'])) { 
	require "billboard.preview.php";
}

if($setup['demo_mode'] == true) { 
	require "_demo_mode.php";
}

?>
<?php
if($_REQUEST['keyWord'] == ""._default_search_text_."") { 
	$_REQUEST['keyWord'] = "";
}
?>
<?php 
if(is_array($_SESSION['heldPhotos'])) { 
	foreach($_SESSION['heldPhotos'] AS $pic_id) { 
		$held_photos .= "$pic_id,";
	}
}
?>
<div id="vinfo" did="<?php print $_REQUEST['date_id'];?>" sub_id="<?php print $_REQUEST['sub_id'];?>" subGalleryPageID="1" thumbPageID="<?php if(empty($_REQUEST['page'])) { print "1"; } else { print $_REQUEST['page']; } ;?>" keyWord="<?php print sql_safe($_REQUEST['keyWord']);?>" key_id="<?php print sql_safe($_REQUEST['key_id']);?>" pic_camera_model="<?php print sql_safe($_REQUEST['pic_camera_model']);?>" pic_upload_session="<?php print sql_safe($_REQUEST['pic_upload_session']);?>" untagged="<?php print sql_safe($_REQUEST['untagged']);?>" view="<?php print $_REQUEST['view'];?>"  disableNav="0" currentViewPhoto="" thumbsPerPage="<?php print $thumbs_per_page;?>" totalPhotos="" orderBy="<?php if(empty($_REQUEST['orderBy'])) {  print $photo_setup['def_all_orderby']; } else { print $_REQUEST['orderBy']; } ?>" acdc="<?php if(empty($_REQUEST['acdc'])) {  print $photo_setup['def_all_acdc']; } else { print $_REQUEST['acdc']; } ?>" orientation="<?php print $_REQUEST['orientation'];?>" pic_client="<?php print $_REQUEST['pic_client'];?>" p_id="<?php print $_REQUEST['p_id'];?>" search_length="<?php print $_REQUEST['search_length'];?>" search_date="<?php print $_REQUEST['search_date'];?>" passcode="<?php print $_REQUEST['passcode'];?>" from_time="<?php print $_REQUEST['from_time'];?>" > 

<input type="hidden" name="currentCoverPhoto" id="currentCoverPhoto" value="<?php if(empty($gallery['gal_prev_image'])) { print $gallery['gal_preview']; } ?>">
<input type="hidden" name="heldPhotos" id="heldPhotos" value="<?php print $held_photos;?>">

<div id="pagewindowbgcontainer"><div id="pagewindowbg"></div></div>
<div id="photoBGContainer" onClick="closeBGContainer();"><div id="photoBG" ></div></div>
<div id="viewPhotoContainer">
<div id="viewPhotoExit"  onClick="closeBGContainer();"></div>
	<div id="prevPhoto" onclick="navPhotos('prev'); return false;"><?php print ai_large_nav_left;?></div>
	<div id="nextPhoto" onclick="navPhotos('next'); return false;"><?php print ai_large_nav_right;?></div>

	<div id="viewPhotoOuter"><div id="viewPhoto"></div></div>
	<div id="viewPhotoInfoContainer"><div id="viewPhotoInfo"></div></div>
</div>
<div id="loadingPage"></div>
<div id="loadingMore">Loading More</div>
<div id="actions" style="display: none;"></div>
<div id="hovercontainer" class="hovercontainer"></div>
<?php if(($site_setup['hide_help'] == "0")  && ($setup['unbranded'] !== true) == true){ ?>
<script>
function getstarted() { 
	pagewindowedit("new.php");
	}
</script>
<div id="gettingstartedtab" style="" class="sidetab hidesmall" onclick="getstarted();"><div style="padding: 8px;">G<br>E<br>T<br>T<br>I<br>N<br>G<br><br>S<br>T<br>A<br>R<br>T<br>E<br>D</div></div>
<?php } ?>
 
<script>

	function dodeleteque() { 
		$("#deleteque").show().html("").append('<img src="graphics/loading2.gif" align="absmiddle"> Deleting photos from the delete que');
		$.get("admin.actions.php?action=deleteque", function(data) {
			$("#deleteque").html(data);
		});
	}

$(document).ready(function(){
	 dpmenu();
	<?php if($_SESSION['full_site'] !== true) { ?>
	adjustsite();
	<?php } ?>
	$(".moreinfo").hover(
	  function () {
		$(this).find('.info').html($("#"+$(this).attr("info-data")).html());
		$(this).find('.info').css({"margin-top":"16px", "margin-left":"-"+$(this).find('.info').width()+"px"}).stop(true,true).fadeIn(100, function() { 
			if($(this).position().left < $(this).width()) { 
				//alert($(this).position().left+" X "+$(this).width());
				$(this).css("margin-left","-"+$(this).position().left+"px");
			}
		});
	  },
	  function () {
		$(this).find('.info').stop(true,true).fadeOut(100);
	  }
	);

	$(".rowhover").hover(
	  function () {
		$(this).find('.hovermenu').show();
	  },
	  function () {
		$(this).find('.hovermenu').hide();
	  }
	);
	 mytips(".tip","tooltip");
	 myinputtips(".inputtip","tooltip");
	$("#leftside").css("height", $("#rightside").height());
	$(".subeditclick").click(
	  function () {
		$(this).parent().children('.subedit').slideToggle(200);
		$(this).toggleClass("subediton");

	  });


	$(".iconhover").hover(
	  function () {
		$(this).attr("src", $(this).attr("hover"));
	  },
	  function () {
		$(this).attr("src", $(this).attr("off"));
	  }
	);
	$(".iconhoverclose").hover(
	  function () {
		$(this).attr("src", $(this).attr("hover"));
	  },
	  function () {
		$(this).attr("src", $(this).attr("off"));
	  }
	);

	$(window).resize(function() {
		<?php if($_SESSION['full_site'] !== true) { ?>
		adjustsite();
		<?php } ?>
	});


	<?php
	$delete_que = countIt("ms_photos", "WHERE pic_delete='1' ");
	if($delete_que > 0) { ?>
	dodeleteque();
	deletingphotos = setInterval("dodeleteque()",30000);
	<?php } ?>

});
</script>

<?php checkadminaccess(); ?>

<div id="contentArea">
<div id="sidemenubgcontainer" data-side-open="0"></div>
<div id="sidemenuclose"><span class="the-icons icon-cancel" onclick="showhidesidemenu();"></span></div>
<?php if(!empty($_REQUEST['do'])) { ?>
	<div id="leftside" class="hidesmall">
	<div class="inner">
	<?php include "admin.menu.php"; ?>
	</div>
</div>
<?php } ?>
<?php if(!empty($_REQUEST['do'])) { ?>
<div id="rightside" class="smallfull">
	<div class="inner">
	<div id="deleteque" class="hide right" style="height: 20px;"></div><div class="clear"></div>
	<?php





?>
<?php } else { ?>
<div class="home">

<?php } ?>
<?php
print "<div class=masterpad>";
?>

<?php 
if(is_dir('../install/')) {
	print "<div><div class=error><center>The \"install\" directory still exists on the server. Please delete the \"install\" directory.</center></div></div>";
}


if($_REQUEST['do'] == "forms") {
	include "forms/forms.index.php";
} elseif($_REQUEST['do'] == "search") {
	include "search.php";
} elseif($_REQUEST['do'] == "people") {
	include "people/people.index.php";
} elseif($_REQUEST['do'] == "subscriptions") {
	include "subscriptions/sub.index.php";
} elseif($_REQUEST['do'] == "ep") {
	include "ep/h.index.php";
} elseif($_REQUEST['do'] == "sqry") {
	include "sqry.php";
} elseif($_REQUEST['do'] == "pinformation") {
	include "pinformation.php";
} elseif($_REQUEST['do'] == "editconfig") {
	include "editconfig.php";
} elseif($_REQUEST['do'] == "custom") {
	include "custom/custom.index.php";
} elseif($_REQUEST['do'] == "checkmove") {
	include "checkmove.php";
} elseif($_REQUEST['do'] == "video") {
	include "video/video.index.php";
} elseif($_REQUEST['do'] == "reports") {
	include "reports/reports.index.php";
} elseif($_REQUEST['do'] == "admins") {
	include "admins/admins.index.php";
} elseif($_REQUEST['do'] == "comments") {
	include "comments/comments.index.php";
} elseif($_REQUEST['do'] == "activateSite") {
	include "activate.site.php";
} elseif($_REQUEST['do'] == "allPhotos") {
	include "all_photos.php";
} elseif($_REQUEST['do'] == "photoprods") {
	include "store/photoprods/pp.index.php";
} elseif($_REQUEST['do'] == "news") {
	include "news/news.index.php";
} elseif($_REQUEST['do'] == "settings") {
	include "settings/settings.index.php";
} elseif($_REQUEST['do'] == "h") {
	include "h/h.index.php";
} elseif($_REQUEST['do'] == "stats") {
	include "stats/stats.index.php";
} elseif ($_REQUEST['do']=="register") {
	include "admin.register.php";
} elseif($_REQUEST['do'] == "look") {
	include "look/look.index.php";
} elseif($_REQUEST['do'] == "aff") {
	include "aff/aff.index.php";
} elseif($_REQUEST['do'] == "pls") {
	include "pls/pls.index.php";

} elseif($_REQUEST['do'] == "orders") {
	include "store/orders/orders.index.php";
} elseif($_REQUEST['do'] == "discounts") {
	include "store/discounts/discounts.index.php";
} elseif($_REQUEST['do'] == "customers") {
	include "customers/customer.index.php";

} elseif($_REQUEST['do'] == "booking") {
	include "booking/booking.index.php";
} elseif($_REQUEST['do'] == "affiliates") {
	include "aff-admin/aff.index.php";
} elseif($_REQUEST['do'] == "horses") {
	include "horses/horses.index.php";
} elseif($_REQUEST['do'] == "coupons") {
	include "store/music/coupons/coupons.index.php";
} elseif($_REQUEST['do'] == "emailCustomer") {
	include "email.customer.php";
} else { ?>
<div id="refreshloading" style=" display: none; position: fixed; text-align: center; width: 200px; left: 50%; margin-left: -100px; top: 45%; background: #FFFFFF; padding: 8px;"><img src="graphics/loading1.gif" align="absmiddle"> refreshing</div>
<script>
	var refreshtotal = 0;
function refreshadminstats() { 
	$("#refreshloading").show();
	$.get("admin.home.stats.php?action=refreshhomestats", function(data) {
		$("#dashboard").html("").append(data);
		$("#refreshloading").hide();
		<?php if($_SESSION['full_site'] !== true) { ?>
		adjustsite();
		<?php } ?>
		 mytips(".tip","tooltip");
	});
	$.get("admin.home.stats.php?action=visitortotaltitle", function(data) {
		$("title").html(data);
	});
	refreshtotal = refreshtotal + 1;
	// $("#log").show().html(refreshtotal+" times");
	if(refreshtotal < 240) { 
      homerefresh = setTimeout(refreshadminstats, 30000);
	}
}
 $(document).ready(function(){
       homerefresh = setTimeout(refreshadminstats, 30000);
 });


</script>
<?php 
	print "<div id=\"dashboard\">";
	require "home.php";
	print "</div>";
}
?>



<div id="confirmdelete" style="display: none; background: #FFFFFF; border: solid 1px #c4c4c4; padding: 16px; position: fixed; width: 400px; left: 50%; margin-left: -200px; top: 30%; z-index: 200; box-shadow: 0px 0px 8px rgba(0,0,0,.5);">
<div class="pc"><h3 id="confirm-title"></h3></div>
<div class="pc" id="confirm-message"></div>
<div>&nbsp;</div>
<div class="pc textright buttons"><a href="" id="confirm-link">Yes</a> &nbsp;&nbsp; <a href="" onclick="confirmdeletecancel(); return false;">Cancel</a></div>
</div>

<div id="confirmdeleteoptions" style="display: none; background: #FFFFFF; border: solid 1px #c4c4c4; padding: 16px; position: fixed; width: 400px; left: 50%; margin-left: -200px; top: 30%; z-index: 200; box-shadow: 0px 0px 8px rgba(0,0,0,.5);">
<div class="pc"><h3 id="confirm-options-title"></h3></div>
<div class="pc" id="confirm-options-message"></div>

<div class="pc"><a href="" id="option-link-1"><span id="option-link-1-text"></span></a></div>
<div class="pc"><a href="" id="option-link-2"><span id="option-link-2-text"></span></a></div>

<div class="pc textright"><a href="" onclick="confirmdeleteoptionscancel(); return false;">Cancel</a></div>
</div>


<?php include "infos.php"; ?>

<?php include "footer.php"; ?>
</div>
</div>
<?php  ob_end_flush(); ?>
