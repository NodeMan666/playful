<script>
function editPrice(id) { 
	$("#editpricecontainer").css({"position":"absolute","top":$("#price-edit-"+id).parent().position().top+"px","left":$("#price-edit-"+id).parent().position().left+"px"});
	$("#editpricecontainer").show();
	$("#editprice").html($("#price-edit-"+id).html());
}
function cancelEditPrice(id) { 
	$("#editpricecontainer").hide();
}


function editGroup(id) { 
	$("#group-"+id).slideToggle(200);
}
function createGroup() { 
	$("#newgroup").slideToggle(200);
	$("#new_group_name").focus();
}

function updateprodprice(file,classname,id) { 
	var fields = {};
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] += ","+$this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}

		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}
	});
	$.post(file, fields,	function (data) { 
		$("#price-"+id).html(data);
		cancelEditPrice(id);
	 } );
	return false;
}
$(document).ready(function(){


});

function ckrequiredmessage(id) { 
	if($("#group_require_purchase-"+id).attr("checked")) { 
		$("#required_message-"+id).slideDown(200);
	} else { 
		$("#required_message-"+id).slideUp(200);
	}
}

function quantitydiscount(pc_id) { 
	pagewindowedit("<?php print $setup['temp_url_folder'];?>/<?php print $setup['manage_folder'];?>/store/photoprods/w-quantity-discounts.php?noclose=1&nofonts=1&nojs=1&pc_id="+pc_id);
}



</script>
<style>
</style>

<?php 
$no_trim = true;
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' "); 
foreach($_REQUEST AS $id => $value) {
	if(!is_array($_REQUEST[$id])) { 
		$_REQUEST[$id] = addslashes(stripslashes($value));
	}
}
?>
<div id="pageTitle" class="left"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=lists">Price List</a> <?php print ai_sep;?> <?php print $list['list_name'];?></div>
<div class="right textright">
<a href="index.php?do=news&date_photo_price_list=<?php print $list['list_id'];?>"><?php print countIt("ms_calendar", "WHERE date_photo_price_list='".$list['list_id']."' "); ?> Pages Using This Price List</a>
</div>
<div class="clear"></div>

<div class="pc"><a href="" onclick="editpricelist('<?php print $list['list_id'];?>'); return false;">edit settings</a> &nbsp;&nbsp; 
<?php $ios = countIt("ms_image_options", "WHERE opt_list='".$list['list_id']."' "); ?>
<a href="" onclick="editimageoptions('<?php print $list['list_id'];?>'); return false;">image options<?php if($ios > 0) { print " (".$ios.")"; } ?></a> &nbsp;&nbsp; 

<a href="index.php?do=photoprods&action=deletePriceList&list_id=<?php print $list['list_id'];?>"   onClick="return confirm('Are you sure you want to delete this price list? Deleting this will permanently remove it and can not be reversed.');">delete</a> &nbsp;&nbsp; <?php if($list['list_default']!=="1") { ?><a href="index.php?do=photoprods&action=defaultPriceList&list_id=<?php print $list['list_id'];?>" class="tip" title="Make this the default price list when creating galleries">make default</a><?php } else { ?>Default Price List<?php } ?>
 &nbsp;&nbsp; <a href="index.php?do=photoprods&action=duplicate&list_id=<?php print $list['list_id'];?>" class="tip" title="Create a new price list duplicating this one">duplicate</a>
</div>

<?php if($list['list_min_order'] > 0) { ?><div class="pc"><?php print showPrice($list['list_min_order'])." minimum order amount</div>"; } ?>


<?php 
if($_REQUEST['action'] == "addstoreproducts") { 
	if($_REQUEST['store_cat'] > 0) { 
		$dates = whileSQL("ms_calendar", "*", "WHERE date_cat='".$_REQUEST['store_cat']."' ");
		while($date = mysqli_fetch_array($dates)) { 
			$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$_REQUEST['list_id']."' AND pc_group='".$_REQUEST['group_id']."' AND pc_store_item='".$date['date_id']."' ");
			if(empty($ck['pc_id'])) { 
				insertSQL("ms_photo_products_connect", "pc_list='".$_REQUEST['list_id']."', pc_group='".$_REQUEST['group_id']."' , pc_store_item='".$date['date_id']."' ");
			}
		}
	}
	header("location: index.php?do=photoprods&view=list&list_id=".$_REQUEST['list_id']."");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "addtolist") { 
	if($_REQUEST['package_id'] > 0) { 
		$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$_REQUEST['package_id']."'AND pc_group='".$_REQUEST['group_id']."' ");
		if(empty($ck['pc_id'])) { 
			insertSQL("ms_photo_products_connect", "pc_list='".$list['list_id']."', pc_package='".$_REQUEST['package_id']."', pc_group='".$_REQUEST['group_id']."' ");
		}
		$_SESSION['sm'] = "Package added";


	} else { 

		$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$_REQUEST['pp_id']."'AND pc_group='".$_REQUEST['group_id']."' ");
		if(empty($ck['pc_id'])) { 
			insertSQL("ms_photo_products_connect", "pc_list='".$list['list_id']."', pc_prod='".$_REQUEST['pp_id']."', pc_group='".$_REQUEST['group_id']."' ");
		}
		$_SESSION['sm'] = "Product added";

	}


	header("location: index.php?do=photoprods&view=list&list_id=".$list['list_id']."");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "addall") { 

	if($_REQUEST['type'] == "packages") { 
		$packs = whileSQL("ms_packages", "*","WHERE package_buy_all='0' ORDER BY package_name ASC ");
		while($pack= mysqli_fetch_array($packs)) { 
			$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$pack['package_id']."'AND pc_group='".$_REQUEST['group_id']."' ");
			if(empty($ck['pc_id'])) { 
				insertSQL("ms_photo_products_connect", "pc_list='".$list['list_id']."', pc_package='".$pack['package_id']."', pc_group='".$_REQUEST['group_id']."' ");
			}
		}
		$_SESSION['sm'] = "Packageas added";
	} elseif($_REQUEST['type'] == "buyall") { 
		$packs = whileSQL("ms_packages", "*","WHERE package_buy_all='1' ORDER BY package_name ASC ");
		while($pack= mysqli_fetch_array($packs)) { 
			$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$pack['package_id']."'AND pc_group='".$_REQUEST['group_id']."' ");
			if(empty($ck['pc_id'])) { 
				insertSQL("ms_photo_products_connect", "pc_list='".$list['list_id']."', pc_package='".$pack['package_id']."', pc_group='".$_REQUEST['group_id']."' ");
			}
		}
		$_SESSION['sm'] = "Buy Alls added";


	} else { 
		$prods = whileSQL("ms_photo_products", "*","WHERE pp_type='".$_REQUEST['type']."' ORDER BY pp_price ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			$ck = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."'AND pc_group='".$_REQUEST['group_id']."' ");
			if(empty($ck['pc_id'])) { 
				insertSQL("ms_photo_products_connect", "pc_list='".$list['list_id']."', pc_prod='".$prod['pp_id']."', pc_group='".$_REQUEST['group_id']."' ");
			}
		}
		$_SESSION['sm'] = "Products added";
	}
	
	header("location: index.php?do=photoprods&view=list&list_id=".$list['list_id']."");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "removefromlist") { 
	deleteSQL("ms_photo_products_connect", "WHERE  pc_id='".$_REQUEST['pc_id']."' ", "1");
	$_SESSION['sm'] = "Product removed";
	header("location: index.php?do=photoprods&view=list&list_id=".$list['list_id']."");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "newgroup") { 
	if($_REQUEST['group_buy_all'] == "1") { 
		$add_sql=", group_descr='Buy all photos from this gallery or for selected key word.' ,  group_buy_all_favs='Purchase all of your favorites' ";
	}
	insertSQL("ms_photo_products_groups", "group_name='".$_REQUEST['group_name']."', group_list='".$list['list_id']."', group_package='".$_REQUEST['group_package']."', group_buy_all='".$_REQUEST['group_buy_all']."', group_store='".$_REQUEST['group_store']."', group_require_purchase='".$_REQUEST['group_require_purchase']."', group_require_message='A selection of a product from the other section is required to unlock these products.  ' $add_sql  ");
	$_SESSION['sm'] = "New group created";
	header("location: index.php?do=photoprods&view=list&list_id=".$list['list_id']."");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "editgroup") { 
	updateSQL("ms_photo_products_groups", "group_name='".$_REQUEST['group_name']."', group_package='".$_REQUEST['group_package']."', group_order='".$_REQUEST['group_order']."', group_descr='".$_REQUEST['group_descr']."', group_require_purchase='".$_REQUEST['group_require_purchase']."', group_require_message='".$_REQUEST['group_require_message']."', group_buy_all_favs='".$_REQUEST['group_buy_all_favs']."', group_no_favs='".$_REQUEST['group_no_favs']."'  WHERE group_id='".$_REQUEST['group_id']."' ");
	$_SESSION['sm'] = "Group  updated";
	header("location: index.php?do=photoprods&view=list&list_id=".$_REQUEST['list_id']."");
	session_write_close();
	exit();
}

if($_REQUEST['action'] == "deleteGroup") { 
	deleteSQL2("ms_photo_products_connect", "WHERE  pc_group='".$_REQUEST['group_id']."' ");
	deleteSQL("ms_photo_products_groups", "WHERE  group_id='".$_REQUEST['group_id']."' ", "1");
	$_SESSION['sm'] = "Group deleted";
	header("location: index.php?do=photoprods&view=list&list_id=".$_REQUEST['list_id']."");
	session_write_close();
	exit();
}

?>
<?php 
$prods = whileSQL("ms_photo_products", "*","WHERE pp_type='download' ORDER BY pp_price ASC ");
while($prod = mysqli_fetch_array($prods)) { 
	if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
		$dltotal++;
	}
}
$prods = whileSQL("ms_photo_products", "*","WHERE pp_type='print' ORDER BY pp_price ASC ");
while($prod = mysqli_fetch_array($prods)) { 
	if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
		$printtotal++;
	}
}
$prods = whileSQL("ms_photo_products", "*","WHERE pp_type='other' ORDER BY pp_price ASC ");
while($prod = mysqli_fetch_array($prods)) { 
	if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
		$othertotal++;
	}
}
$packages = whileSQL("ms_packages", "*","WHERE package_buy_all='0' ");
while($package = mysqli_fetch_array($packages)) { 
	if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$package['package_id']."' ")<=0) { 
		$packagetotal++;
	}
}

$buyalls = whileSQL("ms_packages", "*","WHERE package_buy_all='1' ");
while($buyall = mysqli_fetch_array($buyalls)) { 
	if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$buyall['package_id']."' ")<=0) { 
		$buyalltotal++;
	}
}

?>
<div>&nbsp;</div>
<div style="width: 30%;" class="left">
	<div style="padding: 0 24px 0 0;">

	<script>
	function savewalldesigner(id) { 
		if($("#list_wall_designer").attr("checked")) { 
			list_wall_designer = 1;
		} else { 
			list_wall_designer = 0;
		}
		$.get("admin.actions.php?action=savewalldesigner&list_id="+id+"&list_wall_designer="+list_wall_designer, function(data) {
			showSuccessMessage("Wall designer status updated");
			setTimeout(hideSuccessMessage,4000);
		});
	}
	</script>

		<div class="underlinelabel"><input type="checkbox" name="list_wall_designer" id="list_wall_designer" value="1" <?php if($list['list_wall_designer'] == "1") { ?>checked<?php } ?> onchange="savewalldesigner('<?php print $list['list_id'];?>');"> <label for="list_wall_designer">Enable Wall Designer</label> (<a href="index.php?do=photoprods&view=roomview" style="font-size: 13px;">About</a>)</div>
		<div class="pc">When enabling the Wall Designer, it will add a tab along with the other product group tabs.</div>
		<div class="underlinelabel">Product Base</div>
		<div class="underlinesection">
		<?php if(($dltotal + $printtotal + $othertotal + $package) <= 0) { ?>
		<div class="underline">No products left to add</div>
	
		<?php } ?>
		<?php if($printtotal > 0) { ?>
		
		<div class="underlinelabel">Prints
		<div class="right  addto">
		<span class="the-icons icon-plus"></span> Add All
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">
		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addall&type=print&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div></div>

		</div>

		<?php $prods = whileSQL("ms_photo_products", "*","WHERE pp_type='print' ORDER BY pp_price ASC ");
		if(mysqli_num_rows($prods)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($prod = mysqli_fetch_array($prods)) {
			if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
			showAvailableProds($prod,$list);
			}
		} ?>
		<div>&nbsp;</div>
		<?php } ?>


		<?php
		if($dltotal > 0) { 
		?>

		<div class="underlinelabel">Downloads
		<div class="right  addto">
		<span class="the-icons icon-plus"></span> Add All
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">
		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addall&type=download&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div></div>		
		</div>

		<?php $prods = whileSQL("ms_photo_products", "*","WHERE pp_type='download' ORDER BY pp_price ASC ");
		if(mysqli_num_rows($prods)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($prod = mysqli_fetch_array($prods)) { 
			if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
				showAvailableProds($prod,$list);
			}
		} ?>
		<div>&nbsp;</div>

		<?php } ?>

		<?php if($othertotal > 0) { ?>

		<div class="underlinelabel">Other
		<div class="right  addto">
		<span class="the-icons icon-plus"></span> Add All
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">		
		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addall&type=other&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div></div>		
		</div>

		<?php $prods = whileSQL("ms_photo_products", "*","WHERE pp_type='other'  ORDER BY pp_price ASC ");
		if(mysqli_num_rows($prods)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($prod = mysqli_fetch_array($prods)) { 
			if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ")<=0) { 
				showAvailableProds($prod,$list);
			}
			} ?>
		<div>&nbsp;</div>
		<?php } ?>







		<?php
		if($packagetotal > 0) { 
		?>

		<div class="underlinelabel">Collections
		<div class="right  addto">
		<span class="the-icons icon-plus"></span> Add All
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">
		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addall&type=packages&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div></div>		
		</div>

		<?php $packs = whileSQL("ms_packages", "*","WHERE package_buy_all='0' ORDER BY package_name ASC ");
		if(mysqli_num_rows($packs)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($pack = mysqli_fetch_array($packs)) { 
			if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$pack['package_id']."' ")<=0) { 
				showAvailablePackages($pack,$list);
			}
		} ?>
		<div>&nbsp;</div>

		<?php } ?>



		<?php
		if($buyalltotal > 0) { 
		?>

		<div class="underlinelabel">Buy Alls
		<?php 
		$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' AND group_buy_all='1' ");
		if(mysqli_num_rows($groups) > 0) { ?>
			<div class="right  addto">
			<span class="the-icons icon-plus"></span> Add All
			<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">
			<?php 
			while($group = mysqli_fetch_array($groups)) { ?>
					<div><a href="index.php?do=photoprods&view=list&action=addall&type=buyall&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

			<?php } ?>
			</div></div>		
			<?php }  ?>
		</div>
		<?php 	if(mysqli_num_rows($groups) <= 0) { ?>
		<div class="pc">You must first create a new Product Group marked as a buy all group.</div>
		<?php } ?>
		<?php $packs = whileSQL("ms_packages", "*","WHERE package_buy_all='1' ORDER BY package_name ASC ");
		if(mysqli_num_rows($packs)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($pack = mysqli_fetch_array($packs)) { 
			if(countIt("ms_photo_products_connect", "WHERE pc_list='".$list['list_id']."' AND pc_package='".$pack['package_id']."' ")<=0) { 
				showAvailablePackages($pack,$list);
			}
		} ?>
		<div>&nbsp;</div>

		<?php } ?>

		</div>
	</div>
</div>




<div style="width: 70%;" class="left">
	<div style="padding: 0 0 0 24px;">
		<div class="right textright">
		<div class="pc buttons"><a href="" onClick="createGroup(); return false;">Create Product Group</a>
				<div class="moreinfo" info-data="prodgroups">
			<div class="info"></div>
		</div>

		</div>
		


		<div class="" style="display: none; margin-top: 8px;" id="newgroup">

		
		<form method="post" name="newgroup" action="index.php"  onSubmit="return checkForm();">
		<div class="pc">Enter a name for the group. Example: Downloads

		</div>
		<script>
		function checkproductgroupselect() { 
			if($("#group_store").attr("checked")) { 
				$("#newpgrequire").slideUp(100);
				$("#group_require_purchase").attr("checked",false);

				$("#newpgbuyall").slideUp(100);
				$("#group_buy_all").attr("checked",false);
			} else { 
				$("#newpgrequire").slideDown(100);
				$("#newpgbuyall").slideDown(100);
			}

		}


		</script>
		<div class="pc"><input type="text" name="group_name" id="new_group_name" size="30" class="required">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="list">
		<input type="hidden" name="action" value="newgroup">
		<input type="hidden" name="list_id" value="<?php print $list['list_id'];?>">
		<input type="submit" name="submit" value="Create" id="submitButton" class="submitSmall">
		</div>
		<div class="pc newpgopts" id="newpgrequire"><div class="moreinfo" info-data="prodgroupsrequirepurchase"><div class="info"></div></div> <label for="group_require_purchase">Require a selection from this group before the can select products from other groups</label> <input type="checkbox" name="group_require_purchase" id="group_require_purchase" value="1" onchange="checkproductgroupselect();"> </div>
		<div class="pc newpgopts" id="newpgbuyall"><div class="moreinfo" info-data="prodgroupsbuyall"><div class="info"></div></div> <label for="group_buy_all">I will be placing Buy Alls in this product group</label>.  <input type="checkbox" name="group_buy_all" id="group_buy_all" value="1" onchange="checkproductgroupselect();"> </div>
		<!-- <div class="pc"><input type="checkbox" name="group_package" id="group_package" value="1"> Add to collections tab</div> -->
		<div class="pc" id="newpgstore"><div class="moreinfo" info-data="productgroupstoreitems"><div class="info"></div></div> <label for="group_store">I will be adding items from a store section</label> <input type="checkbox" name="group_store" id="group_store" value="1" onchange="checkproductgroupselect();"> </div>

		</form>
		</div>
		</div>
<div class="clear"></div>
		<div class="underlinelabel">Products In Price List
		
		</div>

<div id="message-box" class="pageContent"><?php echo $message; ?></div>


		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ORDER BY group_order ASC");
		$total_groups = mysqli_num_rows($groups);
		while($group = mysqli_fetch_array($groups)) { ?>
		<div class="underlinelabel">
		<?php print $group['group_name'];?> 
		<?php if($group['group_require_purchase'] == "1") {?>(required)<?php } ?> 
		<?php if($group['group_buy_all'] == "1") { ?>(Buy All Group)<?php } ?> 
		 <?php if($group['group_store'] == "1") {?>(Store Products)<?php } ?>
		<div class="sub">
		<span class="small"><a href="" onclick="editGroup('<?php print $group['group_id'];?>'); return false;">edit group</a>  &nbsp; &nbsp; 
		<?php if($total_groups > 1) { ?>
		<a href="index.php?do=photoprods&view=list&action=deleteGroup&group_id=<?php print $group['group_id'];?>&list_id=<?php print $list['list_id'];?>"  onClick="return confirm('Are you sure you want to delete this group? Deleting this will permanently remove it and can not be reversed.');">delete group</a> 
		<?php } ?>
		</span>
		<span class="right textright small hidden">Display order: <?php print $group['group_order'];?></span>
		</div>
		</div>


		<?php if($group['group_store'] == "1") { ?>
		<div class="underline">
		<div class="label">Select the category you wish to add items from below. This will add all items from that category to this product group. After they are added, you can remove unwanted products. If you need to add new products  from a category, it will not duplicate any existing products. </div>
		<form method="post" name="store-<?php print $group['group_id'];?>" action="index.php">
			<select name="store_cat">
			<option value="">Select which section or category to add products from</option>
			<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_type='store' ");
				while($cat = mysqli_fetch_array($cats)) { ?>
				<option value="<?php print $cat['cat_id'];?>"><?php print $cat['cat_name'];?></option>
				<?php } ?>
			</select> <input type="submit" name="submit" value="Add" class="submitSmall">
			<input type="hidden" name="action" value="addstoreproducts">
			<input type="hidden" name="do" value="photoprods">
			<input type="hidden" name="view" value="list">
			<input type="hidden" name="list_id" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="group_id" value="<?php print $group['group_id'];?>">
		</form>

		</div>
	
		<?php } ?>
		<div id="group-<?php print $group['group_id'];?>" class="underlinesection hidden">
		<form method="post" name="editgroup" action="index.php">
		<div class="underline">
		<div style="width: 60%; float: left;">
			<div class="label">Name</div>
			<div><input type="text" name="group_name" value="<?php print htmlspecialchars($group['group_name']);?>" size="40" class="field100"></div>
		</div>
		<div style="width: 40%; float: left;">
			<div class="label">Order</div>
			<div><input type="text" name="group_order" value="<?php print htmlspecialchars($group['group_order']);?>" size="4" ></div>
		</div>
		<div class="clear"></div>
		</div>
		<!-- <div class="underline">
		<input type="checkbox" name="group_package" id="group_package" value="1" <?php if($group['group_package'] == "1") { print "checked"; } ?>> Add to package tab
		</div> -->

		<!-- <div class="underline"><input type="checkbox" name="group_buy_all" id="group_buy_all-<?php print $group['group_id'];?>" value="1" <?php if($group['group_buy_all'] == "1") { print "checked"; } ?>> I will be placing Buy Alls in this product group. <div class="moreinfo" info-data="prodgroupsbuyall"><div class="info"></div></div></div> -->

		<?php if((countIt("ms_photo_products_groups", "WHERE group_list='".$list['list_id']."' AND group_require_purchase") <=0) || ($group['group_require_purchase'] == "1")==true) { ?> 
		<?php if($group['group_store'] !== "1") { ?>
			<div class="underline">
			<input type="checkbox" name="group_require_purchase" id="group_require_purchase-<?php print $group['group_id'];?>" value="1" <?php if($group['group_require_purchase'] == "1") { print "checked"; } ?> onclick="ckrequiredmessage('<?php print $group['group_id'];?>');"> <label for="group_require_purchase-<?php print $group['group_id'];?>">Require a selection from this group before the can select products from other groups</label> <div class="moreinfo" info-data="prodgroupsrequirepurchase"><div class="info"></div></div>
			</div>
			<div class="underline" id="required_message-<?php print $group['group_id'];?>" <?php  if($group['group_require_purchase'] !== "1") { print "style=\"display: none;\""; } ?>>
				<div class="label">Required selection message</div>
				<div><textarea name="group_require_message" rows="3" cols="40" class="field100"><?php print $group['group_require_message'];?></textarea></div>
				<div class="sub">This is the message shown in the other product groups if a required item has not been added to the shopping cart.</div>
			</div>
		<?php } ?>
		<?php } ?>


		<div class="underline">
			<div class="label">Description</div>
			<div><textarea name="group_descr" rows="3" cols="40" class="field100"><?php print $group['group_descr'];?></textarea></div>
		</div>


		<?php if($group['group_buy_all'] == "1") { ?>
		<div class="underline">
			<div class="label">Buy all favorites description</div>
			<div><textarea name="group_buy_all_favs" rows="3" cols="40" class="field100"><?php print $group['group_buy_all_favs'];?></textarea></div>
		</div>
			<div class="underline">
			<input type="checkbox" name="group_no_favs" id="group_no_favs-<?php print $group['group_id'];?>" value="1" <?php if($group['group_no_favs'] == "1") { print "checked"; } ?> > Do not offer these buy alls when customer ordering from favorites.
			</div>

		<?php } ?>
		<div class="underline center">
		<input type="hidden" name="do" value="photoprods">
		<input type="hidden" name="view" value="list">
		<input type="hidden" name="action" value="editgroup">
		<input type="hidden" name="group_id" value="<?php print $group['group_id'];?>">
		<input type="hidden" name="list_id" value="<?php print $list['list_id'];?>">
		<input type="submit" name="submit" value="Save" class="submit">
		</div>
		</form>

		</div>

	<?php // SORT FUNCTION START
	$add = "group-".$group['group_id'];
	?>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list-<?php print $add;?>','sort_order-<?php print $add;?>','orderProds');
	});
	</script>
	<form id="dd-form-<?php print $add;?>" action="index.php" method="post">
	<input type="hidden" name="group_id" value="<?php print $group['group_id'];?>">
	<?php
	unset($order);
		$prods = whileSQL("ms_photo_products_connect", "*","WHERE pc_list='".$list['list_id']."' AND pc_group='".$group['group_id']."' ORDER BY pc_order ASC ");	
		while($prod = mysqli_fetch_array($prods)) {
		$order[] = $prod['pc_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order-<?php print $add;?>" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>
	</form>


		<?php $cons = whileSQL("ms_photo_products_connect", "*","WHERE pc_list='".$list['list_id']."' AND pc_group='".$group['group_id']."' ORDER BY pc_order ASC ");
		if(mysqli_num_rows($cons)<=0) { ?>
		<div class="underline center">No products added to <?php print $group['group_name'];?></div>
		<?php } else { ?>

		<div class="underlinecolumn">
			<div style="width: 10%; float: left;">&nbsp;</div>
			<div style="width: 30%; float: left;">Name</div>
			<div style="width: 40%; float: left;">Size</div>
			<div style="width: 20%; float: left; text-align: right;">Price</div>
			<div class="clear"></div>
		</div>
	<?php } ?>


	<ul id="sortable-list-<?php print $add;?>" class="sortable-list">

		<?php 
			while($con = mysqli_fetch_array($cons)) { ?>
			<li title="<?php print $con['pc_id'];?>">
			<?php 
			if($con['pc_package'] > 0) { 
				$pack = doSQL("ms_packages", "*", "WHERE package_id='".$con['pc_package']."' ");
				if($pack['package_id'] > 0) { 
					showPackage($pack,$list,$con);
				}
			} elseif($con['pc_store_item'] > 0) { 
				$date = doSQL("ms_calendar", "*", "WHERE date_id='".$con['pc_store_item']."' ");
				if($date['date_id'] > 0) { 
					 showStoreItem($date,$list,$con);
				}
			} else { 
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$con['pc_prod']."' ");
				if($prod['pp_id'] > 0) { 
					showProduct($prod,$list,$con);
				}
			}
			 ?>
			 </li>
			<?php  }	?>
			</ul>
			</form>
			<div>&nbsp;</div>

		<?php } ?>
		<div class="underlinespacer textright small">* indicates overriding default price.</div>


	</div>
</div>

<div class="clear"></div>
<div id="editpricecontainer" style="background: #FFFFFF; border: solid 1px #999999; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);">
	<div class="pc"><b>Override Default Price</b></div>
	<div class="pc">Enter in the price you want to use for this product in this price list. To revert to the default price, enter 0.</div>

	<div id="editprice" class="pc"></div>	
</div>

<?php function showProduct($prod,$list,$con) { 	?>
	<div class="underline">
	<div style="width: 10%; float: left;">
	<a href="index.php?do=photoprods&view=list&action=removefromlist&list_id=<?php print $list['list_id'];?>&pc_id=<?php print $con['pc_id'];?>" title="Remove From List" class="tip"><span class="the-icons icon-trash-empty"></span></a> 
	&nbsp;<span class="the-icons icon-sort tip"  title="Drag & Drop To Sort"></span></div>
	<div style="width: 30%; float: left;">
	<h3><?php print $prod['pp_name'];?></h3>
	<?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?>
	</div>
	<div style="width: 40%; float: left;">
	<?php if($prod['pp_type'] == "print") { ?>
	<?php if($prod['pp_width'] > 0) { ?>
		<?php print round($prod['pp_width'],2);?> X <?php print round($prod['pp_height'],2);?>
	<?php } else { print "&nbsp;"; } ?>
	<?php } ?>
	<?php if($prod['pp_type'] == "download") { ?>
		<?php if($prod['pp_download_dem'] <=0) { print "Largest available size"; } else { print $prod['pp_download_dem']." px"; } ?> 
	<?php } ?>
	&nbsp;
	</div>
	<div style="width: 20%; float: left; text-align:right;">
	<?php 
	if($prod['pp_free'] == "1") { 
		print "Free";
	} else { 
	
	if($con['pc_price'] > 0) { 
		$price = $con['pc_price'];
	} else { 
		$price = $prod['pp_price'];
	}
	?>

	<div id="price-<?php print $con['pc_id'];?>" style="display: inline;" >
	<?php if($con['pc_price'] > 0) { print "* "; } ?><?php print showPrice($price);?> </div>
	 <a href="" onclick="editPrice('<?php print $con['pc_id'];?>'); return false;" id="price-edit-link-<?php print $con['pc_id'];?>"  title="Override Price"><span class="the-icons icon-pencil"></span></a>

	<div id="price-edit-<?php print $con['pc_id'];?>" style="display: none; " class="pc">
	<form method="post" name="editprod<?php print $prod['pp_id'];?>" id="editprod<?php print $prod['pp_id'];?>" action="index.php" onSubmit="updateprodprice('admin.actions.php','price-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;">
	<input type="text" name="pp_price" id="pp_price" size="4" class="price-<?php print $con['pc_id'];?>" value="<?php print $price;?>">
	<input type="hidden" name="action" id="action"  value="updatephotoprodprice" class="price-<?php print $con['pc_id'];?>">
	<input type="hidden" name="pc_id" id="pc_id" value="<?php print $con['pc_id'];?>" class="price-<?php print $con['pc_id'];?>">
	<input type="submit" name="submit" id="submit" class="submitSmall" value="save">
	</form>
	<div class="pc"><a href="" onclick="cancelEditPrice('<?php print $con['pc_id'];?>'); return false;" class="small">cancel</a></div>
	</div>
	<div class="pc muted" style="font-size: 12px;"><a href="" onclick="quantitydiscount('<?php print $con['pc_id'];?>'); return false;"><?php if(countIt("ms_photo_products_discounts", "WHERE dis_prod='".$con['pc_id']."' ") > 0) { ?><span  style="color: #008900; font-weight: bold;" title="Quantity discounts added"><?php } else { ?><span style="color: #890000;" title="No quantity discounts added"><?php } ?>quantity discounts</span></a></div>
<?php } ?>

	</div>

		<div class="clear"></div>
		<?php if(!empty($prod['pp_descr'])) { ?>
			<div>
			<div style="width: 10%; float: left;">&nbsp;</div>
			<div class="left"><div class="sub"><?php print nl2br($prod['pp_descr']);?></div></div>
			<div class="clear"></div>
			</div>
		<?php } ?>
	</div>
<?php } ?>


<?php function showPackage($pack,$list,$con) { 	?>
	<div class="underline">
	<div style="width: 10%; float: left;">
	<a href="index.php?do=photoprods&view=list&action=removefromlist&list_id=<?php print $list['list_id'];?>&pc_id=<?php print $con['pc_id'];?>" title="Remove From List" class="tip"><span class="the-icons icon-trash-empty"></span></a> 
	&nbsp;<span class="the-icons icon-sort tip" title="Drag & Drop To Sort"></span></div>
	<div style="width: 30%; float: left;">
	<?php if($pack['package_buy_all'] == "1") { ?>
	<h3><a href="index.php?do=photoprods&view=buyalls&package_id=<?php print $pack['package_id'];?>"><?php print $pack['package_name'];?></a></h3>
	<?php } else { ?>
	<h3><a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>"><?php print $pack['package_name'];?></a></h3>
	<?php } ?>
	<?php if(!empty($pack['package_internal_name'])) { print $pack['package_internal_name']; } ?>
	</div>
	<div style="width: 40%; float: left;">
	<?php 
	if($pack['package_select_only'] == "1") { 
		print $pack['package_select_amount']." selections";
	} else { 
		$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			if($x > 0) { print ", "; } 
			print "<nobr>".$prod['con_qty'].": ".$prod['pp_name']."</nobr>";
			$x++;
		}
	}
	?> 

	&nbsp;
	</div>
	<div style="width: 20%; float: left; text-align:right;">
	<?php 

	
	if($con['pc_price'] > 0) { 
		$price = $con['pc_price'];
	} else { 
		$price = $pack['package_price'];
	}
	?>

	<?php 
		if($pack['package_buy_all'] == "1") { 	
			?>
		<div id="price-<?php print $con['pc_id'];?>" style="display: inline;" >
		<?php if($pack['package_buy_all_price_type'] == "1") { print showPrice($pack['package_buy_all_each']);?> per photo<?php } ?> 
		<?php if($pack['package_buy_all_price_type'] == "2") { ?>Tiered Pricing<?php } ?> 
		<?php if($pack['package_buy_all_price_type'] == "3") { print showPrice($pack['package_buy_all_set_price']);?><?php } ?> 
			
			</div>
			<?php 
		} else { 	
			?>

	<div id="price-<?php print $con['pc_id'];?>" style="display: inline;" >

		<?php if($con['pc_price'] > 0) { print "* "; } ?><?php print showPrice($price);?> </div>
		 <a href="" onclick="editPrice('<?php print $con['pc_id'];?>'); return false;" id="price-edit-link-<?php print $con['pc_id'];?>"   title="Override Price"><span class="the-icons icon-pencil"></span></a>

		<div id="price-edit-<?php print $con['pc_id'];?>" style="display: none; " class="pc">
		<form method="post" name="editprod<?php print $prod['pp_id'];?>" id="editprod<?php print $prod['pp_id'];?>" action="index.php" onSubmit="updateprodprice('admin.actions.php','price-<?php print $con['pc_id'];?>','<?php print $con['pc_id'];?>'); return false;">
		<input type="text" name="pp_price" id="pp_price" size="4" class="price-<?php print $con['pc_id'];?>" value="<?php print $price;?>">
		<input type="hidden" name="action" id="action"  value="updatephotoprodprice" class="price-<?php print $con['pc_id'];?>">
		<input type="hidden" name="pc_id" id="pc_id" value="<?php print $con['pc_id'];?>" class="price-<?php print $con['pc_id'];?>">
		<input type="submit" name="submit" id="submit" class="submitSmall" value="save">
		</form>
		<div class="pc"><a href="" onclick="cancelEditPrice('<?php print $con['pc_id'];?>'); return false;" class="small">cancel</a></div>
		</div>
	<?php } ?>

	</div>

		<div class="clear"></div>
		<?php if(!empty($prod['pp_descr'])) { ?>
		<div class="sub"><?php print nl2br($prod['pp_descr']);?></div>
		<?php } ?>
	</div>
<?php } ?>



<?php function showStoreItem($date,$list,$con) { 	
	?>
	<div class="underline">
		<div style="width: 10%; float: left;">
		<a href="index.php?do=photoprods&view=list&action=removefromlist&list_id=<?php print $list['list_id'];?>&pc_id=<?php print $con['pc_id'];?>" title="Remove From List" class="tip"><span class="the-icons icon-trash-empty"></span></a> 
		&nbsp;<span class="the-icons icon-sort tip"  title="Drag & Drop To Sort"></span></div>
		<div style="width: 30%; float: left;"><h3><a href="index.php?do=news&action=addDate&date_id=<?php print $date['date_id'];?>"><?php print $date['date_title'];?></a></h3>	</div>
		<div style="width: 40%; float: left;">
		&nbsp;
		</div>
		<div style="width: 20%; float: left; text-align:right;"><div id="price-<?php print $con['pc_id'];?>" style="display: inline;" ><?php print showPrice($date['prod_price']);?> </div></div>

		<div class="clear"></div>
	</div>
<?php } ?>


<script>
 $(document).ready(function(){
	$(".addto").hover(
		function () {
		$(this).children(".addtoopt").show();
		},
		function () {
		$(this).children(".addtoopt").hide();
		}
		);
});
</script>

<?php function showAvailableProds($prod,$list) { ?>
		<div class="underline">
		<div style="width: 10%; float: left;" class="addto">
		<span class="the-icons icon-plus"></span> &nbsp;
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">		
		<?php $groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addtolist&list_id=<?php print $list['list_id'];?>&pp_id=<?php print $prod['pp_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div>
		</div>
		<div style="width: 50%; float: left;">
		<?php print $prod['pp_name'];?> <?php if(!empty($prod['pp_internal_name'])) { print " (".$prod['pp_internal_name'].")"; } ?>
		<div class="clear"></div>
		</div>
		<div style="width: 40%; float: left; text-align:right;">
		<?php print showPrice($prod['pp_price']);?>
		</div>

			<div class="clear"></div>
			<?php if(!empty($prod['pp_descr'])) { ?>
			<div class="sub"><?php print nl2br($prod['pp_descr']);?></div>
			<?php } ?>
		</div>
<?php } ?>
<?php function showAvailablePackages($pack,$list) { ?>
		<div class="underline">
		<?php 
		if($pack['package_buy_all'] == "1") { 
			$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' AND group_buy_all='1' ");
		} else { 
			$groups = whileSQL("ms_photo_products_groups", "*", "WHERE group_list='".$list['list_id']."' ");
		}
		if(mysqli_num_rows($groups) > 0) { 
		?>

		<div style="width: 10%; float: left;" class="addto">
		<span class="the-icons icon-plus"></span> &nbsp;
		<div style="position: absolute; border: solid 1px #c4c4c4; background: #f0f0f0; padding: 8px; display: none; box-shadow: 0px 0px 4px rgba(0,0,0,.5);" class="addtoopt">		
		<?php 
		while($group = mysqli_fetch_array($groups)) { ?>
				<div><a href="index.php?do=photoprods&view=list&action=addtolist&list_id=<?php print $list['list_id'];?>&package_id=<?php print $pack['package_id'];?>&group_id=<?php print $group['group_id']?>">Add to <?php print $group['group_name'];?></a></div>

		<?php } ?>
		</div>
		</div>
		<?php } ?>
		<div style="width: 50%; float: left;">
		<?php print $pack['package_name'];?>
		<?php if(!empty($pack['package_internal_name'])) { print $pack['package_internal_name']; } ?>

		</div>
		<div style="width: 40%; float: left; text-align:right;">
		<?php
		if($pack['package_buy_all'] == "1") { 	
		?>			
		<?php if($pack['package_buy_all_price_type'] == "1") { print showPrice($pack['package_buy_all_each']);?> per photo<?php } ?> 
		<?php if($pack['package_buy_all_price_type'] == "2") { ?>Tiered Pricing<?php } ?> 
		<?php if($pack['package_buy_all_price_type'] == "3") { print showPrice($pack['package_buy_all_set_price']);?><?php } ?> 
		<?php
		} else { 	
			print showPrice($pack['package_price']);
		}
		?>
		</div>

			<div class="clear"></div>
			<?php if(!empty($pack['package_descr'])) { ?>
			<div class="sub"><?php print nl2br($pack['package_descr']);?></div>
			<?php } ?>
		</div>
<?php } ?>