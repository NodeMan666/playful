<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> Price Lists</div>
<div class="pc">When adding products for sale to a gallery, you will select a price list. Your price lists are products from your <a href="index.php?do=photoprods&view=base">product base</a> & <a href="index.php?do=photoprods&view=packages">collections</a>. You can create multiple price lists which allows you to have different sets of products & pricing. <?php if($setup['unbranded'] !== true) { ?>
<a href="https://www.picturespro.com/sytist-manual/photo-products/" target="_blank">More information in the manual</a>.
<?php } ?></div>
<?php 

if($_REQUEST['action'] == "duplicate") { 


	####### PRICE LIST #####
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' "); 

$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_photo_products_lists");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
		if(($row['Field'] !== "list_id")&&($row['Field'] !== "list_default")==true) { 
			if($x > 0) { $lqry.=","; } 
			$x++;
			if($row['Field'] == "list_name") { 
				$list_name = $list[$row['Field']]." - DUPLICATE ".date('M d, Y h:i:s');
				$lqry .= $row['Field']."='".addslashes(stripslashes($list_name))."'  ";
			} else { 
				$lqry .= $row['Field']."='".addslashes(stripslashes($list[$row['Field']]))."' ";
			}
//	        print "<li>".$row['Field']." = ".$list[$row['Field']]."</li>";
		}
	}
}
	$id = insertSQL("ms_photo_products_lists", "$lqry" );
	print "<li><b>".$lqry."</b></li>";
	

####### IMAGE OPTIONS ############### 
		$ios = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' ORDER BY opt_order ASC");
		while($io = mysqli_fetch_array($ios)) { 
			$x = 0;
			$qry = "";
			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_image_options");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "opt_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "opt_list") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($io[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
			$opt_id = insertSQL("ms_image_options", "$qry" );
		}

####### PRODUCT GROUPS ###########
		$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ORDER BY group_order ASC");
			while($group = mysqli_fetch_array($groups)) { 
			$x = 0;
			$qry = "";

			// print "<li>".$group['group_id']." - ".$group['group_name'].""; 

			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_photo_products_groups");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "group_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "group_list") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($group[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
		$group_id = insertSQL("ms_photo_products_groups", "$qry" );

		print "<li><b>".$qry."</b></li>";

		print "<ul>";
		$prods = whileSQL("ms_photo_products_connect", "*","WHERE pc_list='".$list['list_id']."' AND pc_group='".$group['group_id']."' ORDER BY pc_order ASC ");	
		while($prod = mysqli_fetch_array($prods)) {
			$x = 0;
			$qry = "";

			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_photo_products_connect");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "pc_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "pc_list") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($id))."' ";
						} else if($row['Field'] == "pc_group") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($group_id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($prod[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}

			//print "<li>".$prod['pc_id']."";
			print "</li>";
			$con_id = insertSQL("ms_photo_products_connect", "$qry" );

			print "<li><b>".$qry."</b></li>";
			
			print "<ul>";
			$diss = whileSQL("ms_photo_products_discounts", "*","WHERE dis_prod='".$prod['pc_id']."' ");	
			while($dis = mysqli_fetch_array($diss)) {
				$x = 0;
				$qry = "";


				$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_photo_products_discounts");
				if (mysqli_num_rows($result) > 0) {

					while ($row = mysqli_fetch_assoc($result)) {
						if($row['Field'] !== "dis_id") { 
							if($x > 0) { $qry.=","; } 
							$x++;
							if($row['Field'] == "dis_prod") { 
								$qry .= $row['Field']."='".addslashes(stripslashes($con_id))."' ";
							} else { 
								$qry .= $row['Field']."='".addslashes(stripslashes($dis[$row['Field']]))."' ";
							}
						//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
						}
					}
				}
				$dis_id = insertSQL("ms_photo_products_discounts", "$qry" );
				print "<li><b>".$qry."</b></li>";

				// print "<li>Discount: ".$dis['dis_price']."</li>";
			}
			print "</ul>";

		}

		print "</ul>";
		print "</li>";
	}
	$_SESSION['sm'] = "Price list duplicated and saved as ".$list_name."";
	header("location: index.php?do=photoprods&view=list&list_id=".$id."");
	session_write_close();
	exit();

}



if(!empty($_REQUEST['deleteProd'])) { 
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['deleteProd']."' ");
	deleteSQL("ms_photo_products",  "WHERE pp_id='".$prod['pp_id']."' ","1");
	$_SESSION['sm'] = $prod['list_name']." was deleted";
	header("location: index.php?do=photoprods&view=base");
	session_write_close();
	exit();
}

if($_REQUEST['action']=="deletePriceList") { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' ");
	deleteSQL("ms_photo_products_lists",  "WHERE list_id='".$list['list_id']."' ","1");
	deleteSQL2("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' ");
	$dates = whileSQL("ms_calendar", "*", "WHERE date_photo_price_list='".$list['list_id']."' ");
	while($date = mysqli_fetch_array($dates)) { 
		updateSQL("ms_calendar", "date_photo_price_list='0' WHERE date_id='".$date['date_id']."' ");
	}

	$_SESSION['sm'] = $list['list_name']." was deleted";
	header("location: index.php?do=photoprods");
	session_write_close();
	exit();
}
if($_REQUEST['action']=="defaultPriceList") { 
	updateSQL("ms_photo_products_lists", "list_default='0' ");
	updateSQL("ms_photo_products_lists", "list_default='1' WHERE list_id='".$_REQUEST['list_id']."' ");
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' ");

	$_SESSION['sm'] = $list['list_name']." has been made default";
	header("location: index.php?do=photoprods");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "saveproduct") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}

	if(!empty($_REQUEST['list_id'])) { 
		updateSQL("ms_photo_products_lists", "
		list_name='".$_REQUEST['list_name']."',
		list_filters='".$_REQUEST['list_filters']."',
		list_require_login='".$_REQUEST['list_require_login']."',
		list_show_crop='".$_REQUEST['list_show_crop']."',
		list_products_placement='".$_REQUEST['list_products_placement']."',
		list_allow_notes='".$_REQUEST['list_allow_notes']."',
		list_min_order='".$_REQUEST['list_min_order']."'
		WHERE list_id='".$_REQUEST['list_id']."' ");
		$_SESSION['sm'] = $_REQUEST['list_name']." Saved";
		header("location: index.php?do=photoprods&view=lists");
		session_write_close();
		exit();
	} else { 
		$id = insertSQL("ms_photo_products_lists", "
		list_name='".$_REQUEST['list_name']."', list_filters='".$_REQUEST['list_filters']."', list_products_placement='0'  ");
		insertSQL("ms_photo_products_groups", "group_name='Products', group_list='".$id."' ");
		$_SESSION['sm'] = $_REQUEST['list_name']." Saved";
		header("location: index.php?do=photoprods&view=list&list_id=".$id."");
		session_write_close();
		exit();

	} 
}
?>
<script>
function createPriceList() { 
	$("#newpricelist").slideToggle(200);
}

</script>

<div class="underlinelabel">Price Lists
		<div class="right textright">

		<div class="pc buttons"><a href="" onClick="createPriceList(); return false;">Create New Price List</a></div>
		<div class="" style="display: none; margin-top: 8px;" id="newpricelist">
		<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm();">
		<div class="pc">
		<div>Name</div>
		<div><input type="text" name="list_name" id="list_name" class="required" size="40"  value="<?php print htmlspecialchars($pp['list_name']);?>">
		</div>
		</div>
		<div class="pc">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="lists">
		<input type="hidden" name="action" value="saveproduct">
		<input type="hidden" name="list_id" value="<?php print $pp['list_id'];?>">
		<input type="submit" name="submit" value="Create" id="submitButton" class="submitSmall">
		</div>
		<div class="pc">Enter a name for this price list for your reference (not seen by customers).</div>
		</form>
		</div>
		</div>
<div class="clear"></div>


</div>
<?php $lists = whileSQL("ms_photo_products_lists", "*","ORDER BY list_name ASC ");
if(mysqli_num_rows($lists)<=0) { ?>
<div class="underline center">No price lists created</div>
<?php } ?>
<div>&nbsp;</div>
<?php 
while($list = mysqli_fetch_array($lists)) { ?>
<div class="underline">
<div><h2><a href="index.php?do=photoprods&view=list&list_id=<?php print $list['list_id'];?>"><?php print $list['list_name'];?></a> </h2></div>
<div class="sub small"><a href="index.php?do=photoprods&view=list&list_id=<?php print $list['list_id'];?>">view / add products</a> &nbsp;&nbsp; <a href="" onclick="editpricelist('<?php print $list['list_id'];?>'); return false;">edit settings</a> &nbsp;&nbsp; 
<?php $ios = countIt("ms_image_options", "WHERE opt_list='".$list['list_id']."' "); ?>
<a href="" onclick="editimageoptions('<?php print $list['list_id'];?>'); return false;">image options<?php if($ios > 0) { print " (".$ios.")"; } ?></a> &nbsp;&nbsp; 

<a href="index.php?do=photoprods&action=deletePriceList&list_id=<?php print $list['list_id'];?>"   onClick="return confirm('Are you sure you want to delete this price list? Deleting this will permanently remove it and can not be reversed.');">delete</a> &nbsp;&nbsp; <?php if($list['list_default']!=="1") { ?><a href="index.php?do=photoprods&action=defaultPriceList&list_id=<?php print $list['list_id'];?>" class="tip" title="Make this the default price list when creating galleries">make default</a><?php } else { ?>Default Price List<?php } ?>
 &nbsp;&nbsp; <a href="index.php?do=photoprods&action=duplicate&list_id=<?php print $list['list_id'];?>" class="tip" title="Create a new price list duplicating this one">duplicate</a>
</div>

<?php if($list['list_min_order'] > 0) { ?><div class="pc"><?php print showPrice($list['list_min_order'])." minimum order amount</div>"; } ?>

</div>
<?php } ?>
<div>&nbsp;</div>
