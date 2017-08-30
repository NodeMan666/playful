<script>
function unsubscribe(id) { 
	$("#confirmunsub-"+id).html("").addClass("spinner24");
	$.get("admin.actions.php?action=mailListUnsubscribe&em_id="+id+"", function(data) {
			$("#ml-"+id).removeClass("unconfirmed").addClass("unsubscribed");
			$("#ml-unsub-"+id).hide();
			$("#confirmunsub-"+id).slideUp(200);

	});
}

function maillistdelete(id) { 
	$("#confirmdelete-"+id).html("").addClass("spinner24");
	$.get("admin.actions.php?action=mailListDelete&em_id="+id+"", function(data) {
			$("#mlrow-"+id).slideUp(200);

	});
}

function unsubscribeshow(id) { 
	$("#confirmunsub-"+id).slideToggle(200);
}
function deleteshow(id) { 
	$("#confirmdelete-"+id).slideToggle(200);
}

</script>
<?php if($setup['unbranded'] !== true) { ?><div class="right textright"><a href="https://www.picturespro.com/sytist-manual/people/mailing-list/" target="_blank" class="the icons icon-info-circled"><i>Manual</i></a></div><?php } ?>
<div class="clear"></div>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span class="">Mailing List</span></div> 
<div class="buttonsgray">
<ul>
<?php $subs = countIt("ms_email_list",  "WHERE em_id>'0' AND em_status='0' ");
if($subs > 0) { ?>
	<li><a href="index.php?do=people&view=mailList&status=active" class="<?php if($_REQUEST['status'] == "active") { print "on"; } ?>">Subscribed (<?php print $subs;?>)</a></li>
<?php } ?>
<?php $subs = countIt("ms_email_list",  "WHERE em_id>'0' AND em_status='1' ");
if($subs > 0) { ?>
	<li><a href="index.php?do=people&view=mailList&status=pending" class="<?php if($_REQUEST['status'] == "pending") { print "on"; } ?>">Pending (<?php print $subs;?>)</a></li>
<?php } ?>
<?php $subs = countIt("ms_email_list",  "WHERE em_id>'0' AND em_status='2' ");
if($subs > 0) { ?>
	<li><a href="index.php?do=people&view=mailList&status=unsubs"  class="<?php if($_REQUEST['status'] == "unsubs") { print "on"; } ?>">Unsubscribed (<?php print $subs;?>)</a></li>
<?php } ?>

</ul>
<div class="right textright">
<form method="get" name="mlsearch" id="mlsearch" action="index.php" style="margin: 0;">
<input type="text"  id="mlq" name="mlq" size="30" value="<?php if(!empty($_REQUEST['mlq'])) { print $_REQUEST['mlq']; } else { print "Search Mailing List"; } ?>" class="defaultfield " title="Search Mailing List" style="padding: 6px;"> 
<input type="hidden" name="do" value="people">
<input type="hidden" name="view" value="mailList">
<input type="submit" name="submit" class="submitSmall" value="Go">

</form>
</div>


<div class="clear"></div>
</div>
<?php 

if(!empty($_REQUEST['mlq'])) {
	$and_where .= "AND ( em_email LIKE '%".addslashes($_REQUEST['mlq'])."%'  OR   em_name LIKE '%".addslashes($_REQUEST['mlq'])."%' OR  em_last_name LIKE '%".addslashes($_REQUEST['mlq'])."%'  )";
	// print " > search for ".$_REQUEST['q']."";
}


if(empty($_REQUEST['acdc'])) { 
	$acdc = "DESC";
	$oposit = "ASC";
} else { 
	$acdc = $_REQUEST['acdc'];
	if($acdc == "ASC") { 
		$oposit = "DESC";
	}
	if($acdc == "DESC") { 
		$oposit = "ASC";
	}

}
if(empty($_REQUEST['orderby'])) { 
	$orderby = "em_id";
} else { 
	$orderby = $_REQUEST['orderby'];
}
if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}
if(!empty($_REQUEST['status'])) { 
	if($_REQUEST['status'] == "active") { 
		$and_where .= "AND em_status='0' ";
	}
	if($_REQUEST['status'] == "pending") { 
		$and_where .= "AND em_status='1' ";
	}
	if($_REQUEST['status'] == "unsubs") { 
		$and_where .= "AND em_status='2' ";
	}
}

if($_REQUEST['per_page'] > 0) { 
	$per_page = $_REQUEST['per_page'];
	updateSQL("ms_history", "per_page='".$_REQUEST['per_page']."' ");
	$history['per_page'] = $per_page;
} else { 
	$per_page = $history['per_page'];
}

// $and_where .= "AND em_do_not_send='0' ";
$NPvars = array("do=people", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."", "view=mailList");
$sq_page = $pg * $per_page - $per_page;	

if(countIt("ms_email_list",  "WHERE em_id>'0'") <= 0) { ?>
<div>&nbsp;<div>

<div class="center pc"><h3>You have no subscribers to your mailing list. <a href="index.php?do=people&view=mailListSettings">Get started in the mailing list settings</a>.</h3>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<?php } else { 


$total = countIt("ms_email_list",  "WHERE em_id>'0' $and_where ORDER BY $orderby $acdc"); ?>
<div class="underlinecolumn">
	<div class="p5 left">&nbsp;</div>
	<div class="p35 left">Email</div>

	<div class="p20 left">First Name</div>
	<div class="p20 left">Last Name</div>
	<div class="p15 left">Date</div>
	<div class="p5 left">&nbsp;</div>
	<div class="clear"></div>
</div>

<?php $ems = whileSQL("ms_email_list", "*,date_format(em_date, '".$site_setup['date_format']."')  AS em_date,date_format(em_date, '".$site_setup['date_format']." %h:%i %p')  AS em_date_time", "WHERE em_id>'0' $and_where ORDER BY $orderby $acdc LIMIT $sq_page,$per_page"); 
while($em = mysqli_fetch_array($ems)) { 
	if($setup['demo_mode'] == true) { 
		$em['em_email'] = get_starred($em['em_email']);
		$em['em_name'] = get_starred($em['em_name']);
		$em['em_last_name'] = get_starred($em['em_last_name']);
	}
	
	?>
<div class="underline rowhover" id="mlrow-<?php print $em['em_id'];?>">
	<div class="left p5 center"><?php if($em['em_sent_to_mailchimp'] =="1") { ?><img src="graphics/mailchimp.png" style="width: 16px; height: auto;" title="Sent to MailChimp"><?php } ?>&nbsp;</div>
	<div class="left p35 maillist"><h3 id="ml-<?php print $em['em_id'];?>"class="<?php if($em['em_status'] == "1") { ?>unconfirmed<?php }  if($em['em_status'] == "2") { ?>unsubscribed<?php } ?>"><?php print $em['em_email'];?></h3>
	<div class="small hovermenu hide" style="margin-top: 4px;">
	<a href="" onclick="editmaillist('<?php print $em['em_id'];?>'); return false;">edit</a> &nbsp; 
	<a href="" onclick="unsubscribeshow('<?php print $em['em_id'];?>'); return false;" id="ml-unsub-<?php print $em['em_id'];?>"  class="<?php if($em['em_status'] !== "0") { print "hide"; } ?>">unsubscribe</a>  &nbsp; 
	<a href="" onclick="deleteshow('<?php print $em['em_id'];?>'); return false;">delete</a> &nbsp; 
	<?php if($em['em_status'] == "1") { ?>unconfirmed<?php }  if($em['em_status'] == "2") { ?>unsubscribed<?php } ?>
	</div>
	<div id="confirmunsub-<?php print $em['em_id'];?>" class="hide">Are you sure you want to unsubscribe this email?<br><a href="" onclick="unsubscribe('<?php print $em['em_id'];?>'); return false;">Yes</a> <a href="" onclick="unsubscribeshow('<?php print $em['em_id'];?>'); return false;">No</a>
	</div>
	<div id="confirmdelete-<?php print $em['em_id'];?>" class="hide">Are you sure you want to completely delete this email? Delete completely removes the subscriber from your list. If you delete someone, they can re-subscribe or accidentally be re-imported even if they previously unsubscribed.<br><a href="" onclick="maillistdelete('<?php print $em['em_id'];?>'); return false;">Yes</a> <a href="" onclick="deleteshow('<?php print $em['em_id'];?>'); return false;">No</a>
	</div>

	</div>
	<div class="left p20"><?php print $em['em_name'];?>&nbsp;</div>
	<div class="left p20"><?php print $em['em_last_name'];?>&nbsp;</div>
	<div class="left p15"><span class="tip" title="<?php print $em['em_date_time'];?>"><?php print $em['em_date'];?></span>&nbsp;<br><a href="index.php?do=stats&action=recentVisitors&q=<?php print $em['em_ip'];?>" class="tip" title="IP Address. Click to view in the stats"><?php print $em['em_ip'];?></a></div>
	<div class="left p5">
	<?php if($em['em_location'] == "pop") { ?><img src="graphics/em-purple.png" style="width: 16px; height: auto; float: left; margin-right: 8px;" title="Signed Up on Popup Form" ><?php } ?>
	<?php if($em['em_location'] == "page") { ?><img src="graphics/em-gray.png" style="width: 16px; height: auto; float: left; margin-right: 8px;" title="Signed Up on Page Form" ><?php } ?>
	<?php if($em['em_location'] == "checkout") { ?><img src="graphics/em-green.png" style="width: 16px; height: auto; float: left; margin-right: 8px;" title="Signed Up At Checkout" ><?php } ?>
	<?php if($em['em_location'] == "account") { ?><img src="graphics/em-blue.png" style="width: 16px; height: auto; float: left; margin-right: 8px;" title="Signed Up While Creating Account" ><?php } ?>
	<?php if(empty($em['em_location'])) { ?><img src="graphics/em-gray.png" style="width: 16px; height: auto; float: left; margin-right: 8px;" title="Signed Up on Page Form" ><?php } ?>
	</div>
	<div class="clear"></div>
	<?php if($em['em_date_id'] > 0) { 
	$d = doSQL("ms_calendar", "*", "WHERE date_id='".$em['em_date_id']."' ");
	?>
	<div class="pc">Restock Request: <a href="index.php?do=news&action=addDate&date_id=<?php print $d['date_id'];?>"><?php print $d['date_title'];?></a>  (<?php print countIt("ms_email_list", "WHERE em_date_id='".$d['date_id']."' "); ?>)</div>
	<?php } ?>
</div>
<?php } ?>
<div>&nbsp;</div>

<div class="textright pc">Signed up using pop-up form <img src="graphics/em-purple.png" style="width: 16px; height: auto; " align="absmiddle" title="Signed Up on Popup Form" ></div>
<div class="textright pc">Signed up using form on page or footer <img src="graphics/em-gray.png" style="width: 16px; height: auto; " align="absmiddle" title="Signed Up on Page Form" ></div>
<div class="textright pc">Signed up at checkout <img src="graphics/em-green.png" style="width: 16px; height: auto; " align="absmiddle" title="Signed Up At Checkout" ></div>
<div class="textright pc">Signed up while creating an account <img src="graphics/em-blue.png" style="width: 16px; height: auto; " align="absmiddle" title="Signed Up While Creating Account" ></div>

<div>&nbsp;<div>
<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<div>&nbsp;<div>
<?php 
foreach($_GET AS $gn => $gr) { 
	if($gn !== "per_page") { 
		$strv .= "&".$gn."=".$gr;	
	}
}

?>
<div class="pc center">
	Show per page: <?php if($history['per_page'] !== "20") { print "<a href=\"index.php?per_page=20".$strv."\" class=\"np\">20</a>"; } else { print "<span class=\"np\">20</span>"; } ?>
	<?php if($history['per_page'] !== "50") { print "<a href=\"index.php?per_page=50".$strv."\" class=\"np\">50</a>"; } else { print "<span class=\"np\">50</span>"; } ?>
	<?php if($history['per_page'] !== "100") { print "<a href=\"index.php?per_page=100".$strv."\" class=\"np\">100</a>"; } else { print "<span class=\"np\">100</span>"; } ?>
</div>
<div>&nbsp;<div>
<?php } ?>