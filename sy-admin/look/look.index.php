<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>The Site Design area is where you design the look of the pages</h3></div>
		<div class="pc">There are many themes included to get your started. These themes are easily edited in the <a href="theme-edit.php?css_id=2">Sytist Theme Editor</a>. You also manage your <a href="index.php?do=look&view=header">Header& Footer</a> where you can upload your logo, manage the <a href="index.php?do=look&view=links">links in your menu</a>, and more. </div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>



<?php
if($_REQUEST['view'] == "header") {
	include "look.header.php";
} elseif(!empty($_REQUEST['deleteLayout'])) { 
	deleteLayout();
} elseif($_REQUEST['view'] == "footer") {
	include "look.footer.php";
} elseif($_REQUEST['view'] == "css") {
	include "look.css.php";
} elseif($_REQUEST['view'] == "css2") {
	$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."' ");
	if($css['site_type'] == "1") { 
		include "look.css.fullscreen.php";
	} else { 
		include "look.css2.php";
	}
} elseif($_REQUEST['view'] == "additionalCSS") {
	include "look.additional.css.php";
} elseif($_REQUEST['view'] == "links") {
	include "look.links.php";
} elseif($_REQUEST['view'] == "alltext") {
	include "look.all.text.php";
} elseif($_REQUEST['view'] == "galex") {
	include "gallery.exclusive.php";
} elseif($_REQUEST['view'] == "sweetness") {
	include "look.sweetness.php";
} elseif($_REQUEST['view'] == "cookies") {
	include "look.cookies.php";
} elseif($_REQUEST['view'] == "frames") {
	include "look.frames.php";
} elseif($_REQUEST['view'] == "exportTheme") {
	include "look.export.theme.php";
} elseif($_REQUEST['view'] == "social") {
	include "look.social.links.php";
} elseif($_REQUEST['view'] == "layouts") {
	include "layouts.list.php";
} elseif($_REQUEST['view'] == "editLayout") {
	include "layouts.listing.edit.php";
} elseif($_REQUEST['view'] == "editPageLayout") {
	include "layouts.pages.edit.php";
} elseif($_REQUEST['view'] == "fonts") {
	include "look.google.fonts.list.php";
} elseif($_REQUEST['view'] == "sidemenu") {
	include "look.side.menu.php";
} elseif($_REQUEST['view'] == "randomBg") {
	include "look.random.bg.php";
} elseif($_REQUEST['view'] == "otherHeaders") {
	include "look.mobile.header.php";
} elseif($_REQUEST['view'] == "import") {
	include "look.import.theme.php";
} elseif($_REQUEST['action'] == "billboards") {
	include "look.billboards.php";
} elseif(!empty($_REQUEST['editslide'])) {
	include "look.billboard.slide.edit.php";
} elseif($_REQUEST['action'] == "billboardsList") {
	include "look.billboards.list.php";
} elseif($_REQUEST['view'] == "labels") {
	include "look.music.labels.php";
} elseif($_REQUEST['view'] == "themes") {
	include "look.themes.php";
} elseif($_REQUEST['view'] == "siteType") {
	include "look.site.type.php";
} elseif($_REQUEST['view'] == "language") {
	include "look.language.php";
} elseif($_REQUEST['view'] == "miscFiles") {
	include "misc.php";
} elseif($_REQUEST['view'] == "passwordText") {
	include "password.protected.text.php";
} else if($_REQUEST['action'] == "billboardSlideshow") {
	 	require "look.billboard.slideshow.php";

} else {
		include "look.css.php";
}
	?>







<?php

function settingsTree() { 
	$html .= "<a href=\"index.php?do=settings\" class=pagetitle>Settings</a> ";
	return $html;
}

function deleteLayout() { 
	global $setup;
	$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['deleteLayout']."' ");
	print "<li>".$layout['layout_id'];
	if($layout['layout_id'] > 0) { 
		if(!empty($layout['layout_file'])) { 
			if(!empty($layout['layout_folder'])) { 
				unlink($setup['path']."/".$layout['layout_folder']."/".$layout['layout_file']);
			}
		}
	deleteSQL("ms_category_layouts", "WHERE layout_id='".$layout['layout_id']."' ", "1");
	}
	$_SESSION['sm'] = "Layout ".$layout['layout_name']." deleted";
	header("location: index.php?do=look&view=layouts");
	session_write_close();
	exit();
}

?>