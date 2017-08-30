<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> Print Credits</div>
	<div class="pc">Print credits allow you to offer prints and / or downloads to your customers at no cost. Basically a print credit is a collection. When you create a new print credit you select from a collection you have created in the collections section. You can give your customer a code to redeem the print credit or you can add it to their account by viewing the account and clicking the credits tab. To enable the link in the menu for customers to click to redeem their print credit, go to <a href="index.php?do=look&view=links">Site Design > Menu Links</a>.</div>
	<div class="pc buttons right textright"><a href="" onClick="editprintcredit('0'); return false;">Add New Print Credit</a></div>
	<div class="clear"></div>
<?php 
if(!empty($_REQUEST['deleteprintcredit'])) { 
	$pc = doSQL("ms_print_credits", "*", "WHERE pc_id='".$_REQUEST['deleteprintcredit']."' ");
	if(!empty($pc['pc_id'])) { 
		deleteSQL("ms_print_credits", "WHERE pc_id='".$pc['pc_id']."'", "1");
	}
	$_SESSION['sm'] = $pc['pc_code']." was deleted";
	header("location: index.php?do=photoprods&view=printcredits");
	session_write_close();
	exit();

}
if($_REQUEST['action'] == "deletebatch") { 
	if(!empty($_REQUEST['pc_batch'])) { 
		deleteSQL2("ms_print_credits", "WHERE pc_batch='".addslashes(stripslashes($_REQUEST['pc_batch']))."' ");
	}
	$_SESSION['sm'] = $_REQUEST['pc_batch']." batch deleted";
	header("location: index.php?do=photoprods&view=printcredits");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "saveproduct") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(!empty($_REQUEST['package_id'])) { 

	} else { 
		$id = insertSQL("ms_packages", "
		package_name='".$_REQUEST['package_name']."', package_price='".$_REQUEST['package_price']."', package_taxable='1', package_select_amount='".$_REQUEST['package_select_amount']."', package_select_only='".$_REQUEST['package_select_only']."', package_require_all='1', package_ship='1'    ");
		$_SESSION['sm'] = $_REQUEST['package_name']." Saved";
		header("location: index.php?do=photoprods&view=packages&package_id=".$id."");
		session_write_close();
		exit();

	} 
}
?>
<script>
function createPriceList() { 
	$("#newpricelist").slideToggle(200);
}
function editpricelist(id) { 
	$("#pl-"+id).slideToggle(200);
}
function getpackagetype() { 
	if($('input[name=package_select_only]:checked').val() == "1") { 
		$("#package_select_amount_div").slideDown(200);
	} else { 
		$("#package_select_amount_div").slideUp(200);
	}
}
</script>

<?php if(!empty($_REQUEST['pc_batch'])) { 
	$and_where = "AND pc_batch='".addslashes($_REQUEST['pc_batch'])."' ";
	$add_link = "&pc_batch=".$_REQUEST['pc_batch'];
}
$and_where .= "AND pc_coupon<='0' ";
?>
<div class="buttonsgray">
	<ul>
		<?php 
		$good = countIt("ms_print_credits", "WHERE pc_id>'0' AND pc_order='' AND (pc_expire>=NOW() OR pc_expire='0000-00-00') $and_where");
		$expired = countIt("ms_print_credits", "WHERE pc_id>'0' AND pc_order='' AND pc_expire<=NOW() AND pc_expire!='0000-00-00'  $and_where ");
		$redeemed = countIt("ms_print_credits", "WHERE pc_id>'0' AND pc_order!=''  $and_where ");
		?>

	<li><a href="index.php?do=photoprods&view=printcredits<?php print $add_link;?>" class="<?php if(empty($_REQUEST['status'])) { print "on"; } ?>">AVAILABLE<?php if($good > 0) { print " ($good)"; } ?></a> </li>
	<li><a href="index.php?do=photoprods&view=printcredits&status=expired<?php print $add_link;?>" class="<?php if($_REQUEST['status']=="expired") { print "on"; } ?>">EXPIRED<?php if($expired > 0) { print " (".$expired.")"; } ?></a></li>
	<li><a href="index.php?do=photoprods&view=printcredits&status=redeemed<?php print $add_link;?>" class="<?php if($_REQUEST['status']=="redeemed") { print "on"; } ?>">REDEEMED<?php if($redeemed > 0) { print " (".$redeemed.")"; } ?></a></li>
	<div class="cssClear"></div>
	</ul>
</div>
<div id="roundedFormContain">

<script>
function showpcexport(){ 
	$("#exportbatch").slideToggle();
}
</script>
<?php if(!empty($_REQUEST['pc_batch'])) {  ?>
<div class="pc"><h2>Batch: <?php print $_REQUEST['pc_batch'];?></h2>
<a  id="deletebatch" class="confirmdelete tip" confirm-title="Delete All Print Credits In This Batch" confirm-message="Are you sure you want to do this?" href="index.php?do=photoprods&view=printcredits&pc_batch=<?php print $_REQUEST['pc_batch'];?>&action=deletebatch"  title="Delete Batch">delete batch</a> &nbsp; <a href="" onclick="showpcexport(); return false;">export</a></div>
<div id="exportbatch" class="hide">
<form method="POST" action="store/export-print-credits.php" target="_blank">
<div class="pc">
<input type="checkbox" name="pc_name" id="pc_name" value="1" checked> <label for="pc_name">Print Credit Name</label>  &nbsp; 
<input type="checkbox" name="pc_code" id="pc_code" value="1" checked> <label for="pc_code">Redeem Code</label>  &nbsp; 
<input type="checkbox" name="pc_expire" id="pc_expire" value="1" checked> <label for="pc_expire">Expiration</label>  &nbsp; 
<input type="checkbox" name="pc_package" id="pc_package" value="1" checked> <label for="pc_package">Collection Name</label>  &nbsp; 
</div>
<div class="pc">
Separate with: <input type="text" name="sep" id="sep" size="2" class="center" value=","> 
</div>
<div class="pc">
<input type="radio"name="dowith" value="exp" id="dowithexp" checked> <label for="dowithexp">Export</label> 
<input type="radio"name="dowith" value="view" id="dowithview"> <label for="dowithview">Print to Screen</label> 
</div>
<div class="pc">
<input type="hidden" name="pc_batch" id="pc_batch" value="<?php print $_REQUEST['pc_batch'];?>">
<input type="submit" name="submit" class="submitSmall" value="GO">
</div>
</form>
<div>&nbsp;</div>

</div>
<?php } ?>


	<div class="underlinecolumn">
		<div class="p5 left">&nbsp;</div>
		<div class="p15 left">Name</div>
		<div class="p15 left">Redeem Code</div>
		<div class="p40 left">Collection</div>
		<div class="p10 left">Batch</div>
		<div class="p15 left"><?php if($_REQUEST['status'] == "redeemed") { ?>Order #<?php } else { ?>Expires<?php } ?></div>
		<div class="clear"></div>
	<div>

	<?php 
	if($_REQUEST['status'] == "redeemed") { 
		$and_where .= "AND pc_order!='' ";
	} elseif($_REQUEST['status'] == "expired") { 
		$and_where .= "AND pc_order='' AND pc_expire<=NOW() AND pc_expire!='0000-00-00' ";
	} else { 
		$and_where .= "AND pc_order='' AND (pc_expire>=NOW() OR pc_expire='0000-00-00') ";
	}

	$packs = whileSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*,date_format(DATE_ADD(pc_expire, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS pc_expire_show","WHERE pc_id>'0'  $and_where ORDER BY pc_id DESC ");
	if(mysqli_num_rows($packs)<=0) { ?>
	<div class="error center">No print credits created</div>
	<?php } ?>
	<?php 
	while($pack = mysqli_fetch_array($packs)) { 
		$x = 0;
		?>
	<div class="underline">
		<div style="width: 5%; float: left;">
		<?php if($_REQUEST['status'] !== "redeemed") { ?><a href="" onclick="editprintcredit('<?php print $pack['pc_id'];?>'); return false;"><span class="the-icons icon-pencil"></span></a> <?php } ?><a  id="removealllink" class="confirmdelete" confirm-title="Really?" confirm-message="Are you sure you want to delete this print credit?" href="index.php?do=<?php print $_REQUEST['do'];?>&view=printcredits&deleteprintcredit=<?php print $pack['pc_id'];?>"><span class="the-icons icon-trash-empty"></span></a>
	</div>
		<div style="width: 15%; float: left;"><h3><?php print $pack['pc_name'];?></h3></div>
		<div style="width: 15%; float: left;"><h3><?php print $pack['pc_code'];?></h3></div>
		<div style="width: 40%; float: left;">
			<div><h3><?php print $pack['package_name'];?> </h3></div>
		</div>
		<div style="width: 10%; float: left;">
			<div><a href="index.php?do=photoprods&view=printcredits&pc_batch=<?php print $pack['pc_batch'];?>"><?php print $pack['pc_batch'];?></a>&nbsp;</div>
		</div>



		<div style="width: 15%; float: left;">
		<?php 
		if(!empty($pack['pc_order'])) { 
			?><a href="index.php?do=orders&action=viewOrder&orderNum=<?php print $pack['pc_order'];?>">#<?php print $pack['pc_order'];?></a>
			<?php 
		} else { 
			if(($pack['pc_expire'] !== "0000-00-00")&&($pack['pc_expire'] < date('Y-m-d'))==true) { ?><span class="expired"><?php print $pack['pc_expire_show'];  ?></span><?php } else { ?><h3><?php print $pack['pc_expire_show'];  ?></h3><?php } ?>
		<?php } ?>
		</div>

		<div class="clear"></div>
		<div style="width: 35%; float: left;">&nbsp;</div>
		<div style="width: 65%; float: left;">
			<div class="sub small">
			<div>
			<?php 
				if($pack['package_select_only'] == "1") { print $pack['package_select_amount']." photos to be selected"; } 

				$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
				while($prod = mysqli_fetch_array($prods)) { 
					if($x > 0) { print ", "; } 
					print "<nobr>".$prod['con_qty'].": ".$prod['pp_name']."</nobr>";
					$x++;
				}
				?> 
				&nbsp; <a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>">view collection</a> 
				</div>
				<div><?php print nl2br($pack['pc_descr']);?></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php } ?>
</div>
</div>
<div>&nbsp;</div>
