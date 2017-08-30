<?php if($_REQUEST['p_id'] > 0) { 
 $p = doSQL("ms_people", "*,date_format(DATE_ADD(p_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_date, date_format(DATE_ADD(p_last_active, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS p_last_active", "WHERE p_id='".$_REQUEST['p_id']."' ");
 ?>
 <script>
function addpeoplenotes(id) { 
	$("#noteloading").show();
	var notes = $("#p_notes").html();
	$.post("admin.actions.php?action=peoplenotes",{ p_notes: notes, who: "tim", p_id:id },	function (data) { 
		$("#noteloading").hide();
		$("#noteupdated").show();
//		alert(data);
//		$("#"+divid).html(data);
	 } );
	return false;
}
</script>

<?php if(!empty($p['p_notes'])) { ?>
<div style="padding: 16px;">
	<div class="notes">
	<b>Notes for <?php print $p['p_name'];?></b>
	<div  contenteditable id="p_notes"  onClick="removeNoNotes();" name="p_notes" style="min-height: 30px;" p_id="<?php print $p['p_id'];?>" message="Click here to enter notes"><?php if(empty($p['p_notes'])) { print "<span id=\"nonotes\"><i>Click here to enter notes.</i></span>"; } else { print $p['p_notes']; } ?></div>
	</div>
	<div class="pc" style="height: 16px;">
		<div class="left" id="noteloading" style="display: none;"><img src="graphics/loading2.gif"></div>
		<div class="left" id="noteupdated" style="display: none;">Updated</div>
		<div class="right textright" id="updatenote"><a href="" onClick="addpeoplenotes('<?php print $p['p_id'];?>'); return false;">update</a></div>
		<div class="clear"></div>
		</div>

	</div></div>
	<?php } ?>
<?php } ?>

<div id="sitecontent">
	<div class="info">
		<form method="get" name="search" action="index.php" style="padding: 0px; margin: 0px;">
		<input type="hidden" name="do" value="people">
		<input type="text"  name="q" size="20" value="<?php  if(!empty($_REQUEST['q'])) {  print $_REQUEST['q']; } else { print "Search registered people";  } ?>" class="defaultfield" title="Search registered people" value="">
		<input type="submit" class="submitmenu" name="submit" value="Search">
		</form>
	</div>
</div>

<ul class="sidemenus">
<li <?php if((empty($_REQUEST['p_id']))&&(empty($_REQUEST['type'])) &&(empty($_REQUEST['view']))== true) { print "class=\"on\""; } ?>><a href="?do=people">Registered (<?php print countIt("ms_people", "");?>)</a></li>
<li <?php if($_REQUEST['type'] == "unregistered") { print "class=\"on\""; } ?>><a href="?do=people&type=unregistered">Unregistered (<?php 
$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='0' GROUP BY order_email ORDER BY order_id ASC "); 
print mysqli_num_rows($orders); ?>)</a></li>
<li><a href="?do=people" onclick="editpeople(); return false;">Create Account</a></li>
<li <?php if($_REQUEST['view'] == "export")  { print "class=\"on\""; } ?>><a href="?do=people&view=export">Export</a></li>
<li <?php if($_REQUEST['view'] == "mailList")  { print "class=\"on\""; } ?>><a href="?do=people&view=mailList">Mailing List (<?php print countIt("ms_email_list",  "WHERE em_id>'0' AND em_status='0' ");?>)</a></li>
<li <?php if($_REQUEST['view'] == "mailListSettings")  { print "class=\"on\""; } ?>><a href="?do=people&view=mailListSettings">Mailing List Settings</a></li>
<li <?php if($_REQUEST['view'] == "giftcertificates")  { print "class=\"on\""; } ?>><a href="?do=people&view=giftcertificates"><?php if($site_setup['sytist_version'] < 1.8) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?>eGift Cards</a></li>
<li <?php if($_REQUEST['view'] == "allcontracts")  { print "class=\"on\""; } ?>><a href="?do=people&view=allcontracts"><?php if($site_setup['sytist_version'] < 1.7) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?>Contracts</a></li>
<?php if($_REQUEST['view'] == "allcontracts")  {?>
<li <?php if($_REQUEST['sub'] == "templates") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=people&view=allcontracts&sub=templates">Templates</a></li>
<li <?php if($_REQUEST['sub'] == "language") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=people&view=allcontracts&sub=language">Language</a></li>

<?php } ?>
<li <?php if($_REQUEST['view'] == "favorites")  { print "class=\"on\""; } ?>><a href="?do=people&view=favorites">Recently Added Favorites</a></li>

<?php if(countIt("ms_people_no_email",  "WHERE id>'0'")  > 0) { ?>
<li <?php if($_REQUEST['view'] == "optout")  { print "class=\"on\""; } ?>><a href="?do=people&view=optout">Opt-Out Emails (<?php print countIt("ms_people_no_email",  "WHERE id>'0'"); ?>)</a></li>
<?php } ?>
 <?php if($setup['unbranded'] !== true) { ?><li><a href="" onclick="showImport(); return false;">Import From Photo Cart</a></li><?php } ?>
</ul>
<script>
function showImport() { 
	$("#importpc").slideToggle(100);
}
</script>

<div id="importpc" class="hidden">
<div class="pc"><h3>Import customers from your Photo Cart database</h3></div>
<div class="pc">If you have a Photo Cart installation, you can export your customers from Photo Cart to be imported into Sytist. <a href="http://www.picturespro.com/sytist-manual/installation/exporting-customers-from-photo-cart/" target="_blank">Click here for instructions</a>.</div>

<form method="post" name="import" action="index.php" enctype="multipart/form-data">
<div class="pc"><input type="file" name="file" id="file"></div>
<div class="pc">
<input type="hidden" name="do" value="people">
<input type="hidden" name="action" value="import">
<input type="submit" name="submit" class="submit" value="Import">


</div>
</form>
</div>

