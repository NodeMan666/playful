<?php if(!empty($_REQUEST['failedFiles'])) { 
	$filenames = explode("||",$_REQUEST['failedFiles']);
	$totalfailed = count($filenames) - 1;
	?>
	<div class="success center"><?php print $_REQUEST['sm'];?></div>
	<div class="error center"><b><?php print $totalfailed;?> files failed to upload</b></div>
	<?php 
	foreach($filenames AS $filename) { 
		if(!empty($filename)) { ?>
	<div class="underline error" style="padding-left: 16px;"><?php print $filename;?></div>
	<?php }
	}
	?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
<?php } ?>

<?php 
if($_REQUEST['do'] == "orders") { 
	include "store/orders/order.menu.php"; 
}
if($_REQUEST['do'] == "people") { 
	include "people/people.menu.php"; 
}
if($_REQUEST['do'] == "photoprods") { 
	include "store/photoprods/pp.menu.php"; 
}
if($_REQUEST['do'] == "reports") { 
	include "reports/reports.menu.php"; 
}


?>
<?php if($_REQUEST['do'] == "allPhotos") { ?>
<?php include "all_photos_menu.php"; ?>
<?php } ?>
<?php if(($_REQUEST['do'] == "look")==true) { ?>
<ul class="sidemenus">


<?php
if(!empty($mytheme['css_id'])) { ?>
<li><a href="theme-edit.php?css_id=<?php print $mytheme['css_id'];?>">Edit My Theme</a> </li>
<?php } ?>
<li <?php if($_REQUEST['view'] == "css") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=css">All Themes</a> </li>
<li <?php if($_REQUEST['view'] == "header") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=header">Header & Footer</a> </li>
<!-- <li <?php if($_REQUEST['view'] == "otherHeaders") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=otherHeaders">Mobile / Ipad Header & Footer</a></li> -->
<li <?php if($_REQUEST['view'] == "links") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=links">Menu Links</a> </li>
<li <?php if($_REQUEST['view'] == "social") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=social">Social Links</a> </li>
<li <?php if($_REQUEST['view'] == "galex") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=galex">Gallery Exclusive Settings</a> </li>
<li><a href="" onclick="uploadfavicon(); return false;">Favicon</a> </li>

<li <?php if(($_REQUEST['action'] == "billboardsList")||($_REQUEST['action'] == "billboardSlideshow") || (!empty($_REQUEST['editslide']))==true) { print "class=\"on\""; } ?>><?php isFullScreen();?> <a href="index.php?do=look&action=billboardsList">Billboards</a></li>
<li <?php if($_REQUEST['view'] == "sweetness") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=sweetness">CLF-Display Settings</a> </li>

<li <?php if($_REQUEST['view'] == "sidemenu") { print "class=\"on\""; } ?>><?php isFullScreen();?> <a href="index.php?do=look&view=sidemenu">Side Bar</a> </li>
<li <?php if(($_REQUEST['view'] == "layouts")||($_REQUEST['view'] == "editLayout")||($_REQUEST['view'] == "editPageLayout")==true) { print "class=\"on\""; } ?>><a href="index.php?do=look&view=layouts">Page Display & Content Listing Layouts</a></li>


<li <?php if($_REQUEST['view'] == "alltext") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=alltext">Page Text</a> </li>
<li <?php if($_REQUEST['view'] == "miscFiles") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=miscFiles">Misc. Images & Files</a> </li>
<li <?php if($_REQUEST['view'] == "fonts") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=fonts">Font List</a> </li>
<li <?php if($_REQUEST['view'] == "cookies") { print "class=\"on\""; } ?>><a href="index.php?do=look&view=cookies">Cookie Warning</a> </li>
</ul>



<?php } ?>

<?php if($_REQUEST['do'] == "forms") { 
	include "forms/forms.menu.php";
}
?>
<?php if($_REQUEST['do'] == "booking") { 
	include "booking/booking.menu.php";
}
?>


<?php if($_REQUEST['do'] == "news") { ?>
	<?php if(!empty($_REQUEST['date_id'])) { 
	}
	include "news/news.menu.php";

?>

<?php } ?>

<?php if($_REQUEST['do'] == "subscriptions") { 
	include "subscriptions/sub.menu.php";
}
?>

<?php if($_REQUEST['do'] == "customers") { 
	include "customers/customers.menu.php";
}
?>
<?php if($_REQUEST['do'] == "admins") { 
	include "admins/admin.menu.php";
}
?>
<?php if($_REQUEST['do'] == "affiliates") { 
	include "aff-admin/aff.menu.php";
}
?>
<?php if($_REQUEST['do'] == "ep") { 
	include "ep/h.menu.php";
}
?>
<?php if($_REQUEST['do'] == "custom") { 
	include "custom/custom.menu.php";
}
?>

<?php if($_REQUEST['do'] == "discounts") { 
	include "store/discounts/coupon.menu.php";
}
?>

<?php if($_REQUEST['do'] == "video") { 
	include "video/video.menu.php";
}
?>

<?php if($_REQUEST['do'] == "comments") { ?>
<ul class="sidemenus">
<li <?php if((empty($_REQUEST['view']))&&(empty($_REQUEST['status']))==true) { print "class=\"on\""; } ?>><a href="index.php?do=comments">Pending Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='0' ").")"; ?></a> </li>
<li <?php if($_REQUEST['status'] == "approved") { print "class=\"on\""; } ?>><a href="index.php?do=comments&status=approved">Approved Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='1' ").")"; ?></a></li>
<li <?php if($_REQUEST['status'] == "trash") { print "class=\"on\""; } ?>><a href="index.php?do=comments&status=trash">Trashed Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='2' ").")"; ?></a></li>
<li <?php if($_REQUEST['view'] == "settings") { print "class=\"on\""; } ?>><a href="index.php?do=comments&view=settings">Comments Settings</a></li>
<li <?php if($_REQUEST['view'] == "text") { print "class=\"on\""; } ?>><a href="index.php?do=comments&view=text">Comments Page Text</a></li>
</ul>
<?php } ?>

<?php if($_REQUEST['do'] == "settings") { ?>
<ul class="sidemenus">
<li <?php if(empty($_REQUEST['action'])) { print "class=\"on\""; } ?>><a href="index.php?do=settings">Admin / Main Settings</a></li>

<li <?php if($_REQUEST['action'] == "cron") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=cron">Automated Emails (cron)</a></li>


<?php if($sytist_store == true) { ?>
<li <?php if($_REQUEST['action'] == "checkout") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=checkout">Checkout & Payment Options</a></li>

<li><a href="index.php?do=comments&view=settings">Comments</a></li>
<li <?php if($_REQUEST['action'] == "dbBackup") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=dbBackup">Database Backup</a></li>
<li <?php if(($_REQUEST['action'] == "defaultemails")||($_REQUEST['action'] == "defaultemailsedit")==true) { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=defaultemails">Default Emails</a></li>
<?php } ?>
<li <?php if($_REQUEST['action'] == "meta") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=meta">Metadata</a></li>
<?php if($setup['sytist_hosted'] !== true) { ?>
<li <?php if($_REQUEST['action'] == "mail") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=mail">Mail Sending Settings</a></li>
<?php } ?>
<li <?php if($_REQUEST['action'] == "accounts") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=accounts">New Account Requirements</a></li>
<li <?php if($_REQUEST['action'] == "photos") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=photos">Photo Settings</a></li>
<!-- <li <?php if($_REQUEST['action'] == "rss") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=rss">RSS Feed</a></li> -->
<li <?php if($_REQUEST['action'] == "security") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=security">Security</a></li>
<li <?php if($_REQUEST['action'] == "fb") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=fb">Share & Facebook</a></li>
<?php if($sytist_store == true) { ?>
<li <?php if($_REQUEST['action'] == "states") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=states">Shipping</a></li>
<?php } ?>
<li <?php if($_REQUEST['action'] == "sitemap") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=sitemap">Sitemap</a></li>
<li <?php if($_REQUEST['action'] == "status") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=status">Site Status</a></li>
<li <?php if($_REQUEST['action'] == "sitePassword") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=sitePassword">Site Password</a></li>
<?php if($sytist_store == true) { ?>
<li <?php if($_REQUEST['action'] == "tax") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=tax">Tax</a></li>
<?php } ?>
<li <?php if($_REQUEST['action'] == "watermarking") { print "class=\"on\""; } ?>><a href="index.php?do=settings&action=watermarking">Watermarking</a></li>
</ul>
<?php if($setup['sytist_hosted'] !== true) { ?>
<?php if($setup['demo_mode'] !== true) {  ?>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div style="margin: 24px;"> &nbsp; <a href="index.php?do=pinformation" style="font-size: 12px; color: #444444;">PHPinfo</a> &nbsp; <a href="index.php?do=sqry" style="font-size: 12px; color: #444444;">MySQL Query</a> &nbsp; <a href="index.php?do=editconfig" style="font-size: 12px; color: #444444;">Edit Config File</a></div>
<?php } ?>
<?php } ?>
<?php } ?>
<?php if($_REQUEST['do'] == "stats") { ?>
<ul class="sidemenus">
<li <?php if(empty($_REQUEST['view'])) { print "class=\"on\""; } ?>><a href="index.php?do=stats">Site Visitors</a> </li>
<li <?php if($_REQUEST['view']=="emails") { print "class=\"on\""; } ?>><a href="index.php?do=stats&view=emails">Email Logs</a> </li>
<li <?php if($_REQUEST['view']=="shares") { print "class=\"on\""; } ?>><a href="index.php?do=stats&view=shares">Shares</a> </li>
<li <?php if($_REQUEST['view']=="carts") { print "class=\"on\""; } ?>><a href="index.php?do=stats&view=carts">Shopping Carts</a> </li>
</ul>
<?php } ?>

		<?php if($_REQUEST['do'] == "calendar") { ?>
		<div id="roundedSide">
			<div class="label"><a href="index.php?do=calendar"><img src="graphics/calendar.png" border="0" width="16" height="16"></a> <a href="index.php?do=calendar"><nobr>Events Calendar</nobr></a></div>
			</div>
<div>&nbsp;<div>
	<?php 
	if(empty($_REQUEST['year'])) { 
		$year = date('Y');
	} else {
		$year = $_REQUEST['year'];
	}
	?>
	
<div class="pageContent"><div style="width: 50%; float: left;"><h2>Jan <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='01' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>


<div class="pageContent"><div style="width: 50%; float: left;"><h2>Feb <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='02' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>


<div class="pageContent"><div style="width: 50%; float: left;"><h2>Mar <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='03' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Apr <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='04' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>May <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='05' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>
<div class="pageContent"><div style="width: 50%; float: left;"><h2>June <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='06' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>July <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='07' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Aug <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='08' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Sept <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='09' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Oct <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='10' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Nov <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='11' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>

<div class="pageContent"><div style="width: 50%; float: left;"><h2>Dec <?php print $year; ?></h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE MONTH(order_date)='12' AND YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>


<div class="pageContent"><div style="width: 50%; float: left;"><h2><?php print $year; ?> Total</h2></div> 
<div style="width: 50%; float: left;"><h2>
<?php 
	$stotal = doSQL("ms_orders", "SUM(order_total) AS tot", "WHERE  YEAR(order_date)='".$year."' AND order_status<'2' "); 
	print " ".showPrice($stotal['tot'])."";
?>
</h2></div>
<div class="cssClear"></div>
</div>


		<?php } ?>
<div>&nbsp;</div>

