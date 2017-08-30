<script>
function editprintcredit(pc_id) { 
	pagewindowedit("store/print-credit-edit.php?noclose=1&nofonts=1&nojs=1&pc_id="+pc_id);
}
function editimageoptions(list_id) { 
	pagewindowedit("store/image-options.php?noclose=1&nofonts=1&nojs=1&list_id="+list_id);
}
function editpricelist(list_id) { 
	pagewindowedit("store/price-list-options.php?noclose=1&nofonts=1&nojs=1&list_id="+list_id);
}

</script>
<ul class="sidemenus">
<li <?php if((empty($_REQUEST['view']))||($_REQUEST['view']=="list") == true) { print "class=\"on\""; } ?>><a href="index.php?do=photoprods">Price Lists</a></li>
<li <?php if($_REQUEST['view'] == "base") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=base">Product Base</a></li>
<li <?php if($_REQUEST['view'] == "packages") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=packages">Collections</a></li>
<li <?php if($_REQUEST['view'] == "buyalls") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=buyalls">Buy Alls</a></li>


<li <?php if($_REQUEST['view'] == "roomview") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=roomview"><?php if($site_setup['sytist_version'] < 1.7) { ?><span style="padding: 4px; font-size: 12px; background: #FFFF00; color: #000000;">NEW</span> <?php } ?>Wall Designer</a></li>
<?php if($_REQUEST['view'] == "roomview") { ?>
<li <?php if($_REQUEST['sub'] == "canvases") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=canvases">Canvas Prints</a></li>
<li <?php if(($_REQUEST['sub'] == "frames") || ($_REQUEST['sub'] == "frame") == true) { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=frames">Frames</a></li>

<li <?php if($_REQUEST['sub'] == "mats") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=mats">Frame Mat Colors</a></li>
<li <?php if(($_REQUEST['sub'] == "rooms") || ($_REQUEST['sub'] == "room") == true) { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=rooms">Room Photos</a></li>
<li <?php if($_REQUEST['sub'] == "language") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=language">Text / Language</a></li>
<li <?php if($_REQUEST['sub'] == "created") { print "class=\"on\""; } ?> style="padding-left: 16px;"><a href="?do=photoprods&view=roomview&sub=created">Customer Created</a></li>
<?php } ?>

<li <?php if($_REQUEST['view'] == "printcredits") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=printcredits">Print Credits</a></li>
<li <?php if($_REQUEST['view'] == "filters") { print "class=\"on\""; } ?>><a href="index.php?do=photoprods&view=filters">B&W / Filter Options</a></li>
</ul>