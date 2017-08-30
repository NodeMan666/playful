<script>
function unsubscribe(id) { 
	$("#confirmunsub-"+id).html("").addClass("spinner24");
	$.get("admin.actions.php?action=mailListUnsubscribe&id="+id+"", function(data) {
			$("#ml-"+id).removeClass("unconfirmed").addClass("unsubscribed");
			$("#ml-unsub-"+id).hide();
			$("#confirmunsub-"+id).slideUp(200);

	});
}

function maillistdelete(id) { 
	$("#confirmdelete-"+id).html("").addClass("spinner24");
	$.get("admin.actions.php?action=deleteoptout&id="+id+"", function(data) {
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

<div id="pageTitle" ><a href="index.php?do=people">People</a> <?php print ai_sep;?> Opt-Out</div>
<div class="pc">These are people that have clicked to no longer receive emails from an automatted email (like gallery expiring emails).</div>


<?php 

if(empty($_REQUEST['pg'])) {
	$pg = "1";
} else {
	$pg = $_REQUEST['pg'];
}


if($_REQUEST['per_page'] > 0) { 
	$per_page = $_REQUEST['per_page'];
	updateSQL("ms_history", "per_page='".$_REQUEST['per_page']."' ");
	$history['per_page'] = $per_page;
} else { 
	$per_page = $history['per_page'];
}

// $and_where .= "AND em_do_not_send='0' ";
$NPvars = array("do=people", "q=".$_REQUEST['q']."","orderby=".$orderby."", "acdc=".$acdc."", "view=optout");
$sq_page = $pg * $per_page - $per_page;	

if(countIt("ms_people_no_email",  "WHERE id>'0'") <= 0) { ?>
<div>&nbsp;<div>

<div class="center pc"><h3>None found</h3>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<div>&nbsp;<div>
<?php } else { 


$total = countIt("ms_people_no_email",  "WHERE id>'0' "); ?>
<div class="underlinecolumn">
	<div class="p35 left">Email</div>

	<div class="p30 left">Date</div>
	<div class="clear"></div>
</div>

<?php $ems = whileSQL("ms_people_no_email", "*,date_format(date, '".$site_setup['date_format']." %h:%i %p')  AS date_show", "WHERE id>'0'  ORDER BY id DESC LIMIT $sq_page,$per_page"); 
while($em = mysqli_fetch_array($ems)) { 
	if($setup['demo_mode'] == true) { 
		$em['email'] = get_starred($em['email']);
		$em['em_name'] = get_starred($em['em_name']);
		$em['em_last_name'] = get_starred($em['em_last_name']);
	}
	
	?>
<div class="underline rowhover" id="mlrow-<?php print $em['id'];?>">
	<div class="left p35 maillist"><h3 id="ml-<?php print $em['id'];?>"class=""><?php print $em['email'];?></h3>
	<div class="small hovermenu hide" style="margin-top: 4px;">
	<a href="" onclick="deleteshow('<?php print $em['id'];?>'); return false;">remove</a> &nbsp; 
	</div>
	<div id="confirmdelete-<?php print $em['id'];?>" class="hide">By removing this email address, they will start receiving emails again.<br><a href="" onclick="maillistdelete('<?php print $em['id'];?>'); return false;">Yes, remove</a> &nbsp;&nbsp;&nbsp; <a href="" onclick="deleteshow('<?php print $em['id'];?>'); return false;">cancel</a>
	</div>

	</div>
	<div class="left p30"><span class="tip" ><?php print $em['date_show'];?></span>&nbsp;<br><a href="index.php?do=stats&action=recentVisitors&q=<?php print $em['ip'];?>" class="tip" title="IP Address. Click to view in the stats"><?php print $em['ip'];?></a></div>
	<div class="clear"></div>
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