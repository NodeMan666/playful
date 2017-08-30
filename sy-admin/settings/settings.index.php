<?php
if($_REQUEST['action'] == "hideHelp") { 
	updateSQL("ms_settings", "hide_help='1' ");
	$_SESSION['sm'] = "Getting started message updated. You can always turn this back on in Settings > Admin / Main Settings ";
	header("location: index.php");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "countries") {
	include "settings.countries.php";
} elseif($_REQUEST['action'] == "checkout") {
	include "payment.options.php";
} elseif($_REQUEST['action'] == "editPaymentOption") {
	include "payment.edit.option.php";
} elseif($_REQUEST['action'] == "states") {
	include "store/shipping.php";
} elseif($_REQUEST['action'] == "accounts") {
	include "settings.new.accounts.php";
} elseif($_REQUEST['action'] == "tax") {
	include "store/tax.php";
} elseif($_REQUEST['action'] == "dbBackup") {
	include "db.backup.php";
} elseif($_REQUEST['action'] == "sitemap") {
	include "settings.sitemap.php";
} elseif($_REQUEST['action'] == "look") {
	include "settings.look.php";
} elseif($_REQUEST['action'] == "defaultemails") {
	include "store/default.emails.php";
} elseif($_REQUEST['action'] == "emailheader") {
	include "store/default.emails.header.php";
} elseif($_REQUEST['action'] == "defaultemailsedit") {
	include "store/default.emails.edit.php";
} elseif($_REQUEST['action'] == "payment") {
	include "settings.payment.php";
} elseif($_REQUEST['action'] == "meta") {
	include "settings.meta.php";
} elseif($_REQUEST['action'] == "rss") {
	include "settings.rss.php";
} elseif($_REQUEST['action'] == "security") {
	include "settings.security.php";
} elseif($_REQUEST['action'] == "fullscreen") {
	include "settings.fullscreen.php";
} elseif($_REQUEST['action'] == "watermarking") {
	include "settings.watermarking.php";
} elseif($_REQUEST['action'] == "mail") {
	include "settings.mail.php";
} elseif($_REQUEST['action'] == "cron") {
	include "settings.cron.php";
} elseif($_REQUEST['action'] == "sitePassword") {
	include "settings.site.password.php";
} elseif($_REQUEST['action'] == "fb") {
	include "settings.facebook.php";
} elseif($_REQUEST['action'] == "photos") {
	include "settings.photos.php";
} elseif($_REQUEST['action'] == "download") {
	include "settings.download.php";
} elseif($_REQUEST['action'] == "password") {
	include "settings.password.php";
} elseif($_REQUEST['action'] == "menuLinks") {
	include "settings.links.php";
} elseif($_REQUEST['action'] == "terms") {
	include "settings.terms.php";
} elseif($_REQUEST['action'] == "status") {
	include "settings.store.status.php";

} else {
	include "settings.main2.php";
}
?>


<?php

function settingsTree() { 
	$html .= "<a href=\"index.php?do=settings\">Settings</a> ";
	return $html;
}

?>