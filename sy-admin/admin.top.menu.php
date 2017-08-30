<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<script>
 $(document).ready(function(){
getmenuTopPosition('topmenu');
 });
</script>
<script>
$(function(){

    $("ul.dropdown li").hover(function(){
    
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
    
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');
    
    });
    
    $("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");

});

function getmenuTopPosition(s){
	setInterval( function() { menuTopPosition(s); }, 100 );
}

 function menuTopPosition(s) { 
	var p = $("#pagesbill").offset().top;
	var w = $(window).scrollTop();
	var h = $("#"+s).height();
	if(w >= p) { 
		$("#"+s).css('position', 'fixed');
		$("#"+s).css('top', '0');
		$("#pagesbill").height(h);
	} else {
		$("#"+s).css('position', 'relative');
		$("#"+s).css('top', 'auto');		
		$("#pagesbill").height(0);
	}
 }

</script>
	<div id="mobilemenu" class="showsmall hide">

		<div id="mobilemenulinks" class="hide">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="index.php?do=look">Design</a></li>
				<li><a href="index.php?do=news">Content</a></li>
				<li><a href="index.php?do=allPhotos">All Photos</a></li>
				<li><a href="index.php?do=forms">Forms</a></li>
				<li><a href="index.php?do=settings">Settings</a></li>
				<li><a href="index.php?do=orders">Orders</a></li>
				<li><a href="index.php?do=people">People</a></li>
				<li><a href="index.php?do=photoprods">Photo Products</a></li>
				<li><a href="index.php?do=discounts">Coupons</a></li>
				<li><a href="index.php?do=stats">Stats</a></li>
				<li><a href="index.php?do=reports">Reports</a></li>
				<li><a href="index.php?do=booking">Calendar</a></li>
				<li><a href="index.php?do=admins">Administrators</a></li>
				<li><a href="logout.php">Log Out</a> </li>
				<li><?php if($site_setup['index_page'] == "indexnew.php") { ?><a href="<?php tempFolder(); ?>/indexnew.php" target="_blank"><?php } else { ?><a href="<?php tempFolder(); ?>/" target="_blank"><?php } ?>View My Website</a></li>
				 <?php if($setup['unbranded'] !== true) { ?>
				<li> <a href="https://www.picturespro.com/sytist-manual/" target="_blank" title="Manual">Manual</a></li>
				<li><a href="https://www.picturespro.com/support-forum/sytist/" target="_blank" title="Support Forum">Support</a></li>
				<li><a href="https://www.picturespro.com/" target="_blank">PicturesPro.com</a></li>
				<li><a href="http://www.facebook.com/4sytist/" target="_blank" title="Sytist Facebook Page">Sytist Facebook</a></li>
			<?php } ?>
			</ul>
		</div>
	</div>

<div id="pagesbill" style="width:0; height: 0;"></div>
	<div id="topmenu">


        <ul class="dropdown hidesmall">
		<li<?php if(empty($_REQUEST['do'])) { print " class=\"on\""; } ?>><a href="index.php">Home</a></li>



    <li<?php if($_REQUEST['do'] == "look") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("look");?>><a href="index.php?do=look">Design</a>
        <ul class="sub_menu">

		<?php $mytheme= doSQL("ms_css", "*", "WHERE css_id='".$site_setup['css']."' ORDER BY css_order ASC  ");
			if(!empty($mytheme['css_id'])) { ?>
		<li><a href="theme-edit.php?css_id=<?php print $mytheme['css_id'];?>">Edit My Theme</a>
		<?php } ?>
		<li><a href="index.php?do=look&view=css">All Themes</a></li>
		<li><a href="index.php?do=look&view=header">Header & Footer</a></li>
		<li><a href="index.php?do=look&view=links">Menu Links</a></li>
		<li><a href="index.php?do=look&view=galex">Gallery Exclusive Settings</a></li>
		<li><a href="index.php?do=look&view=social">Social Links</a></li>
		<li><a href="" onclick="uploadfavicon(); return false;">Favicon</a> </li>
		<li><a href="index.php?do=look&action=billboardsList"><?php isFullScreen();?> Billboards</a></li>
		<li><a href="index.php?do=look&view=sweetness">CLF-Display Settings</a></li>
		<li><a href="index.php?do=look&view=sidemenu"><?php isFullScreen();?> Side Bar</a></li>
		<li><a href="index.php?do=look&view=layouts">Page Display & Content Listing Layouts</a></li>
		<!-- <li><a href="index.php?do=look&view=otherHeaders">Mobile / Ipad Header & Footer</a></li> -->
		<li><a href="index.php?do=look&view=alltext">Page Text</a></li>
		<!-- <li><a href="index.php?do=look&view=randomBg"><?php isFullScreen();?> Random Background Photos</a></li>-->
		<li><a href="index.php?do=look&view=miscFiles">Misc. Images & Files</a></li>
		<li><a href="index.php?do=look&view=fonts">Font List</a></li>
		<li><a href="index.php?do=look&view=cookies">Cookie Warning</a></li>
	    </ul>
    </li>


    <li<?php if($_REQUEST['do'] == "news") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("news");?>><a href="<?php if($setup['demo_mode'] == true) { ?>index.php?do=news&date_cat=100<?php } else { ?>index.php?do=news<?php } ?>">Site Content</a>
		<ul class="sub_menu">
		<li><a href="index.php?do=news">List All Content</a>
					<?php print multiLevelSelectMenu(false);?></li>
		<li><a href="index.php?do=news&action=addDate">Add New Page</a>
		<?php print multiLevelSelectMenu(true);?></li>
		<li><a href="index.php?do=news&action=editCategory">Create New Section</a></li>
		<?php $hp = doSQL("ms_calendar", "*", "WHERE page_home='1' ");?>
		<li><a href="index.php?do=news&action=addDate&date_id=<?php print $hp['date_id'];?>">Manage Home Page</a></li>
		<?php if($setup['customfields'] == true) { ?>
		<li><a href="index.php?do=news&action=customfields">Custom Fields</a></li>
		<?php } ?>

		</ul>
    </li>

    <li<?php if($_REQUEST['do'] == "allPhotos") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("allPhotos");?>><nobr><a href="index.php?do=allPhotos">All Photos</a></nobr> 
		<ul class="sub_menu">
			<!-- <li><a href="index.php?do=allPhotos">All Photos</a></li>
			<li><a href="index.php?do=video">Video</a></li>
			-->
			<li><a href="" onclick="openFrame('w-photos-upload.php?pic_client=<?php print $_REQUEST['pic_client'];?>'); return false;" >Upload Photos</a></li>
		</ul>
	</li>
	<!-- 
    <li<?php if($_REQUEST['do'] == "comments") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("comments");?>><a href="index.php?do=comments">Comments</a>
        <ul class="sub_menu">
		<li><a href="index.php?do=comments">Pending Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='0' ").")"; ?></a></li>
		<li><a href="index.php?do=comments&status=approved">Approved Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='1' ").")"; ?></a></li>
		<li><a href="index.php?do=comments&status=trash">Trashed Comments <?php print "(".countIt("ms_comments", "WHERE com_approved='2' ").")"; ?></a></li>
		<li><a href="index.php?do=comments&view=settings">Comments Settings</a></li>
		<li><a href="index.php?do=comments&view=text">Comments Page Text</a></li>
        </ul>
    </li>
	-->
    <li<?php if($_REQUEST['do'] == "forms") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("forms");?>><a href="index.php?do=forms">Forms</a>
        <ul class="sub_menu">
		<li><a href="index.php?do=forms">List Forms</a></li>
		<li><a href="" onclick="editform(); return false;">Create New Form</a></li>
		<!-- <li><a href="index.php?do=forms&action=captcha">CAPTCHA Settings</a></li> -->
        </ul>
    </li>

    <li<?php if($_REQUEST['do'] == "settings") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("settings");?>><a href="index.php?do=settings">Settings</a>
        <ul class="sub_menu">
			<li><a href="index.php?do=settings">Admin / Main Settings</a></li>
			<li><a href="index.php?do=settings&action=cron">Automated Emails (cron)</a></li>

			<li><a href="index.php?do=settings&action=checkout">Checkout & Payment</a></li>
			<li><a href="index.php?do=comments&view=settings">Comments</a></li>
			<li><a href="index.php?do=settings&action=dbBackup">Database Backup</a></li>
			<li><a href="index.php?do=settings&action=defaultemails">Default Emails</a></li>

			<li><a href="index.php?do=settings&action=meta">Metadata</a></li>
			<?php if($setup['sytist_hosted'] !== true) { ?>
			<li><a href="index.php?do=settings&action=mail">Mail Sending Settings</a></li>
			<?php } ?>
			<li><a href="index.php?do=settings&action=accounts">New Accounts Require</a></li>
			<li><a href="index.php?do=settings&action=photos">Photo Settings</a></li>
			<li><a href="index.php?do=settings&action=security">Security</a></li>
			<li><a href="index.php?do=settings&action=fb">Share & Facebook</a></li>
			<?php if($sytist_store == true) { ?>
			<li><a href="index.php?do=settings&action=states">Shipping</a></li>
			<?php } ?>

			<li><a href="index.php?do=settings&action=sitemap">Sitemap</a></li>
			<li><a href="index.php?do=settings&action=status">Site Status</a></li>
			<?php if($sytist_store == true) { ?>
			<li><a href="index.php?do=settings&action=tax">Tax</a></li>
			<?php } ?>
			<li><a href="index.php?do=settings&action=watermarking">Watermarking</a></li>
			</ul>
    </li>

<?php if($sytist_store == true) { ?>

	<li<?php if($_REQUEST['do'] == "orders") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("orders");?>><a href="index.php?do=orders">Orders</a>
        <ul class="sub_menu">
				<li><a href="index.php?do=orders">Open Orders</a> 
				<li><a href="index.php?do=orders&orderStatus=archived">Archived</a></li>
				<li><a href="index.php?do=orders&orderStatus=trash">Trash</a></li>
				<li><a href="index.php?do=orders&view=invoice">Create Invoice</a></li>
				<li><a href="index.php?do=orders&action=packingslip">Packing Slip Layout</a></li>
				<li><a href="index.php?do=orders&action=shippingoptions">Shipped By Options</a></li>
				<li>&nbsp; &nbsp;Search <form method="post" name="fo" action="index.php" style="display: inline;"><input type="hidden" name="do" value="orders"><input type="text" name="q" size="8"></form></li>
			</ul>
    </li>

	<li<?php if($_REQUEST['do'] == "people") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("people");?>><a href="index.php?do=people">People</a>
        <ul class="sub_menu">
				<li><a href="index.php?do=people">Registered</a> 
				<li><a href="index.php?do=people&type=unregistered">Unregistered</a></li>
				<li><a href="?do=people" onclick="editpeople(); return false;">Create Account</a></li>
				<li><a href="index.php?do=people&view=favorites">Recently Added Favorites</a></li>
				<li><a href="index.php?do=people&view=export">Export</a></li>
				<li><a href="index.php?do=people&view=giftcertificates"><?php if($site_setup['sytist_version'] < 1.8) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?><span style="text-transform: lowercase;">e</span>Gift Cards</a></li>
				<li><a href="index.php?do=people&view=allcontracts"><?php if($site_setup['sytist_version'] < 1.7) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?>Contracts</a></li>
				<li><a href="index.php?do=people&view=mailList">Mailing List</a></li>
				<li><a href="index.php?do=people&view=mailListSettings">Mailing List Settings</a></li>
				<li>&nbsp; &nbsp;Search <form method="post" name="fo" action="index.php" style="display: inline;"><input type="hidden" name="do" value="people"><input type="text" name="q" size="12"></form></li>
			</ul>
    </li>
	<li<?php if($_REQUEST['do'] == "photoprods") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("photoprods");?>><a href="index.php?do=photoprods">Photo Products</a>
        <ul class="sub_menu">
				<li><a href="index.php?do=photoprods">Price Lists</a> </li>
				<li><a href="index.php?do=photoprods&view=base">Product Base</a> </li>
				<li><a href="index.php?do=photoprods&view=packages">Collections</a> </li>
				<li><a href="index.php?do=photoprods&view=buyalls">Buy Alls</a> </li>
				<li><a href="index.php?do=photoprods&view=printcredits">Print Credits</a> </li>
				<li><a href="index.php?do=photoprods&view=roomview"><?php if($site_setup['sytist_version'] < 1.7) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?>Wall Designer</a></li>
				<li><a href="index.php?do=photoprods&view=filters">B&W / Filter Options</a> </li>
			</ul>
    </li>
	<li<?php if($_REQUEST['do'] == "discounts") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("discounts");?>><a href="index.php?do=discounts">Coupons</a>
    </li>

    <li<?php if($_REQUEST['do'] == "stats") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("stats");?>><a href="index.php?do=stats">Stats</a>
        <ul class="sub_menu">
			<li><a href="index.php?do=stats">Site Visitors</a>
			
				<ul>
        					<li><a href="index.php?do=stats">Overview</a></li>
        					<li><a href="index.php?do=stats&action=recentVisitors">Recent Visitors</a></li>
        					<li><a href="index.php?do=stats&action=refReport">Referrers</a></li>
        					<li><a href="index.php?do=stats&action=thirtyDays">Last 30 Days</a></li>
        					<li><a href="index.php?do=stats&action=recentPages">Recently Viewed Pages</a></li>
        					<li><a href="index.php?do=stats&action=pages">Pages</a></li>
        					<li><a href="index.php?do=stats&action=browserInfo">Browsers</a></li>
        					<li><a href="index.php?do=stats&action=bots">Bots</a></li>
        				</ul>
			
			
			
			</li>
			<li><a href="index.php?do=stats&view=emails">Email Logs</a></li>
			<li><a href="index.php?do=stats&view=shares">Shares</a></li>
			<li><a href="index.php?do=stats&view=carts">Shopping Carts</a></li>
			</ul>
    </li>


	<li<?php if($_REQUEST['do'] == "reports") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("reports");?>><a href="index.php?do=reports">Reports</a>
    </li>
	<li<?php if($_REQUEST['do'] == "booking") { print " class=\"on\""; } ?> <?php print checkadminaccessmenu("booking");?>><a href="index.php?do=booking">Calendar</a>
    </li>

<?php } ?>
<?php if($setup['sytistsite'] == true) { ?>
	<li<?php if($_REQUEST['do'] == "customers") { print " class=\"on\""; } ?>><a href="index.php?do=customers">Registrations</a>
        <ul class="sub_menu">
				<li><a href="index.php?do=customers">Registrations <?php print "(".countIt("ms_registrations", " ").")"; ?></a></li>
				<li><a href="index.php?do=customers&action=installs">Installations <?php print "(".countIt("installs", "WHERE date_completed='0000-00-00 00:00:00'  " ).")"; ?></a> </li>
				<li><a href="index.php?do=customers&action=listKeys">Registration Keys</a></li>
				<li><a href="" onclick="regkey('0'); return false;">Create Registration Key</a></li>
				<li><a href="index.php?do=customers&action=export">Export Regitrations</a></li>
				<li><a href="index.php?do=customers&action=reglogs">Regitration Logs</a></li>
			</ul>
    </li>

<?php } ?>


	<?php if($setup['affiliate_links'] == 1) { ?>
    <li><a href="index.php?do=aff">Affiliates</a>
    </li>
	<?php } ?>

	<?php if($setup['affiliate_program'] == 1) { ?>
    <li><a href="index.php?do=affiliates">Affiliates</a>
    </li>
	<?php } ?>
	<?php if($setup['subscriptions'] == true) { ?>
    <li><a href="index.php?do=subscriptions">Subscriptions</a>
    </li>
	<?php } ?>
	<?php if($setup['pls'] == true) { ?>
    <li><a href="index.php?do=pls">PLS</a>
    </li>
	<?php } ?>

	</ul>
	<div class="right textright pc hidesmall">
	<form method="get" name="searchform" action="index.php">
	<input type="text" name="q" size="20" value="<?php print htmlspecialchars($_REQUEST['q']);?>">
	<input type="hidden" name="do" value="search">
	<input type="submit" name="submit" value="Search" class="submitmenu">
	</form>
	</div>
	<div class="clear"></div>
</div>


<?php 
function multiLevelSelectMenu($new_page) {
	global $dbcon;
	$fn = "gal_under";
//	$match = $_REQUEST['gal_under'];
	$html .=  "<ul>";
	if($new_page == true) { 
		$html .=  "<li><a href=\"index.php?do=news&action=addDate&date_cat=none\">Top Level Page</a></li>";
	} else { 
		$html .=  "<li><a href=\"index.php?do=news&date_cat=none\">Top Level Pages</a></li>";
	}
	$resultt = @mysqli_query($dbcon,"SELECT * FROM ms_blog_categories WHERE cat_under='0' ORDER BY cat_name ASC");
	if (!$resultt) {	echo( "Error perforing query" . mysqli_error($resultt) . "that error"); 	exit();	}
	$close_this = mysqli_num_rows($resultt);
	while ( $type = mysqli_fetch_array($resultt) ) {
	if($new_page == true) { 
		$html .=  "<li><a href=\"index.php?do=news&action=addDate&date_cat=".$type['cat_id']."\">".$type["cat_name"]."</a>";
	} else { 
		$html .=  "<li><a href=\"index.php?do=news&date_cat=".$type['cat_id']."\">".$type["cat_name"]."</a>";

	}
	unset($selected);
		$parent_id = $type["cat_id"];
		$parent = $type['cat_name'];
		if(countIt("ms_blog_categories", "WHERE cat_under='".$type['cat_id']."' ") > 0) { 
			$html .= "<ul>";
			$html .= multiLevelSelectSubsMenu($fn, $new_page, $parent_id, $level, $sec_under,$parent);
			$html .= "</ul>";
		}
	}
	if($close_this < 0) { 
		$html .= "</li>\r\n";
	}
	$html .=  "</ul>\r\n";
	return $html;
}

function multiLevelSelectSubsMenu($fn, $new_page, $parent_id, $level, $sec_under,$parent) {
	global $dbcon;
	$level++;
	$subs = @mysqli_query($dbcon,"SELECT *  FROM ms_blog_categories WHERE cat_under='$parent_id' ORDER BY cat_name ASC");
	if (!$subs) {	echo( "Error perforing query" . mysqli_error($subs) . "that error");	exit(); }
	while($row = mysqli_fetch_array($subs)) {

		$sub_sec_id = $row["cat_id"];
		$sub_sec_name = $row["cat_name"];
		$sub_sec_folder = $row["cat_folder"];


  
		if($new_page == true) { 
			$html .=  "<li><a href=\"index.php?do=news&action=addDate&date_cat=".$row['cat_id']."\">".$sub_sec_name."</a>"; 
		} else { 
			$html .=  "<li><a href=\"index.php?do=news&date_cat=".$row['cat_id']."\">".$sub_sec_name."</a>"; 
		}
		$sub2=@mysqli_query($dbcon,"SELECT COUNT(*) AS how_many FROM ms_blog_categories WHERE cat_under='$sub_sec_id'");
		if (!$sub2) {	echo( "Error perforing query" . mysqli_error($sub2) . "that error");	exit(); }
		$row = mysqli_fetch_array($sub2);
		$how_many= $row["how_many"];
		if(!empty($how_many)) { 
				$html .= "<ul>";
			$parent = $parent." -> ".$sub_sec_name;
			$parent_id = $sub_sec_id;
			$html .= multiLevelSelectSubsMenu($fn, $new_page, $parent_id, $level, $sec_under,$parent);
			$html .= "</ul>";
		}
		$html .= "</li>\r\n";
	}
		$level = 1;
		return $html;
}



?>
