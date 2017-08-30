<?php 
if($_REQUEST['action'] == "duplicatePhotoProduct") { 
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['pp_id']."' ");

	$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_photo_products");
	if (mysqli_num_rows($result) > 0) {

		while ($row = mysqli_fetch_assoc($result)) {
			if($row['Field'] !== "pp_id") { 
				if($x > 0) { $qry.=","; } 
				$x++;
				if($row['Field'] == "pp_name") { 
					$newname = "**** ".$prod[$row['Field']];
					$qry .= $row['Field']."='".addslashes(stripslashes($newname))."' ";
				} else { 
					$qry .= $row['Field']."='".addslashes(stripslashes($prod[$row['Field']]))."' ";
				}
			//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
			}
		}
	}
	$prod_id = insertSQL("ms_photo_products", "$qry" );


	$ios = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_id ASC");
	while($io = mysqli_fetch_array($ios)) { 
		$x = 0;
		$qry = "";
		$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options");
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if($row['Field'] !== "opt_id") { 
					if($x > 0) { $qry.=","; } 
					$x++;
					if($row['Field'] == "opt_photo_prod") { 
						$qry .= $row['Field']."='".addslashes(stripslashes($prod_id))."' ";
					} else { 
						$qry .= $row['Field']."='".addslashes(stripslashes($io[$row['Field']]))."' ";
					}
				//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
				}
			}
		}
		$opt_id = insertSQL("ms_product_options", "$qry" );

		############ Product Options Selections ###############
		$optsels = whileSQL("ms_product_options_sel", "*", "WHERE sel_opt='".$io['opt_id']."' ORDER BY sel_id ASC");
		while($optsel = mysqli_fetch_array($optsels)) { 
			$x = 0;
			$qry = "";
			$result = mysqli_query($dbcon,"SHOW COLUMNS FROM ms_product_options_sel");
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					if($row['Field'] !== "sel_id") { 
						if($x > 0) { $qry.=","; } 
						$x++;
						if($row['Field'] == "sel_opt") { 
							$qry .= $row['Field']."='".addslashes(stripslashes($opt_id))."' ";
						} else { 
							$qry .= $row['Field']."='".addslashes(stripslashes($optsel[$row['Field']]))."' ";
						}
					//    print "<li>".$row['Field']." = ".$group[$row['Field']]."</li>";
					}
				}
			}
			$sel_id = insertSQL("ms_product_options_sel", "$qry" );
		}
	}


	$_SESSION['sm'] = "New product created";
	header("location: index.php?do=photoprods&view=base&prod_id=".$prod_id."");
	session_write_close();
	exit();
}
?>


<?php include $setup['path']."/".$setup['manage_folder']."/store/options.edit.php"; ?>
<?php if($_REQUEST['prod_id'] > 0) { ?>
<script>
 $(document).ready(function(){
	editproduct('<?php print $_REQUEST['prod_id'];?>'); 
	$("#pp_name").focus();
});

</script>
<?php } ?>

<script>

function batcheditpages() {
	var pageids = 0;
	$(".batchselect").each(function(i){
		if($(this).attr("checked")) {
			pageids += "|"+$(this).attr("id");
		//	alert($(this).attr("id"));
		}
	});
	pagewindowedit("store/photoprods/prods-batch-edit.php?noclose=1&nofonts=1&nojs=1&prodids="+pageids);
	// alert(pageids);
}

function selectallpages() { 
	$(".batchselect").attr("checked",true);
	hlprods();
}
function deselectallpages() { 
	$(".batchselect").attr("checked",false);
	hlprods();
}
$(document).ready(function(){
	hlprods();

	$(".batchselect").change(function () { 
		if($(this).attr("checked")) { 
			$("#prod-"+$(this).val()).removeClass("underline").addClass("underlinehl");
		} else { 
			$("#prod-"+$(this).val()).removeClass("underlinehl").addClass("underline");
		}
	});
});

function hlprods() { 
	$(".batchselect").each(function() {
		if($(this).attr("checked")) { 
			$("#prod-"+$(this).val()).removeClass("underline").addClass("underlinehl");
		} else { 
			$("#prod-"+$(this).val()).removeClass("underlinehl").addClass("underline");
		}
	});
}

</script>
<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> Product Base</div>
		<div class="underlinespacer">Your product base are all the products you might want to offer for sale. When you create a price list or collection, you will select from these products. </div>
<div>&nbsp;</div>
<?php 
$no_trim = true;
if(!empty($_REQUEST['deleteProd'])) { 
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['deleteProd']."' ");
	deleteSQL("ms_photo_products",  "WHERE pp_id='".$prod['pp_id']."' ","1");
	deleteSQL2("ms_photo_products_connect", "WHERE  pc_prod='".$prod['pp_id']."' ");
	$_SESSION['sm'] = $prod['pp_name']." was deleted";
	header("location: index.php?do=photoprods&view=base");
	session_write_close();
	exit();
}
if($_REQUEST['action'] == "collapseallproducts") { 
	updateSQL("ms_photo_products", "pp_collapse_options='1' ");
	$_SESSION['sm'] = "Products updated";
	header("location: index.php?do=photoprods&view=base");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "saveproduct") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes(trim($value)));
		}
	}

	if(empty($_REQUEST['pp_download_dem_free'])) { 
		$_REQUEST['pp_download_dem_free'] = 0;
	}
	if($_REQUEST['pp_free'] == "1") { 
		$_REQUEST['pp_download_dem'] = $_REQUEST['pp_download_dem_free'];
	}

	if(!empty($_REQUEST['pp_id'])) { 
		updateSQL("ms_photo_products", "
		pp_name='".$_REQUEST['pp_name']."',
		pp_internal_name='".$_REQUEST['pp_internal_name']."',
		pp_price='".$_REQUEST['pp_price']."',
		pp_descr='".$_REQUEST['pp_descr']."',
		pp_width='".$_REQUEST['pp_width']."',
		pp_height='".$_REQUEST['pp_height']."',
		pp_cost='".$_REQUEST['pp_cost']."',
		pp_download_dem='".$_REQUEST['pp_download_dem']."',
		pp_taxable='".$_REQUEST['pp_taxable']."',
		pp_type='".$_REQUEST['pp_type']."',
		pp_free='".$_REQUEST['pp_free']."',
		pp_free_limit='".$_REQUEST['pp_free_limit']."',
		pp_free_req_login='".$_REQUEST['pp_free_req_login']."',
		pp_free_watermark='".$_REQUEST['pp_free_watermark']."',
		pp_free_logo='".$_REQUEST['pp_free_logo']."',
		pp_add_ship='".$_REQUEST['pp_add_ship']."',
		pp_no_discount='".$_REQUEST['pp_no_discount']."',
		pp_no_ship='".$_REQUEST['pp_no_ship']."',
		pp_collapse_options='".$_REQUEST['pp_collapse_options']."',
		pp_include_download='".$_REQUEST['pp_include_download']."',
		pp_disable_download='".$_REQUEST['pp_disable_download']."',
		pp_no_display_dems='".$_REQUEST['pp_no_display_dems']."' ,
		pp_watermark_file='".$_REQUEST['pp_watermark_file']."',
		pp_watermark_location='".$_REQUEST['pp_watermark_location']."',
		pp_logo_file='".$_REQUEST['pp_logo_file']."',
		pp_logo_location='".$_REQUEST['pp_logo_location']."',
		pp_no_crop='".$_REQUEST['pp_no_crop']."',
		pp_free_all='".$_REQUEST['pp_free_all']."' 

		WHERE pp_id='".$_REQUEST['pp_id']."' ");
	} else { 
		insertSQL("ms_photo_products", "
		pp_name='".$_REQUEST['pp_name']."',
		pp_internal_name='".$_REQUEST['pp_internal_name']."',
		pp_price='".$_REQUEST['pp_price']."',
		pp_descr='".$_REQUEST['pp_descr']."',
		pp_width='".$_REQUEST['pp_width']."',
		pp_height='".$_REQUEST['pp_height']."',
		pp_cost='".$_REQUEST['pp_cost']."',
		pp_download_dem='".$_REQUEST['pp_download_dem']."',
		pp_taxable='".$_REQUEST['pp_taxable']."',
		pp_free='".$_REQUEST['pp_free']."',
		pp_free_watermark='".$_REQUEST['pp_free_watermark']."',
		pp_free_limit='".$_REQUEST['pp_free_limit']."',
		pp_free_req_login='".$_REQUEST['pp_free_req_login']."',
		pp_free_logo='".$_REQUEST['pp_free_logo']."',
		pp_add_ship='".$_REQUEST['pp_add_ship']."',
		pp_no_discount='".$_REQUEST['pp_no_discount']."',
		pp_no_ship='".$_REQUEST['pp_no_ship']."',
		pp_collapse_options='".$_REQUEST['pp_collapse_options']."',
		pp_include_download='".$_REQUEST['pp_include_download']."',
		pp_disable_download='".$_REQUEST['pp_disable_download']."',
		pp_no_display_dems='".$_REQUEST['pp_no_display_dems']."',
		pp_free_all='".$_REQUEST['pp_free_all']."', 
		pp_watermark_file='".$_REQUEST['pp_watermark_file']."',
		pp_watermark_location='".$_REQUEST['pp_watermark_location']."',
		pp_logo_file='".$_REQUEST['pp_logo_file']."',
		pp_logo_location='".$_REQUEST['pp_logo_location']."',
		pp_no_crop='".$_REQUEST['pp_no_crop']."',
		pp_type='".$_REQUEST['pp_type']."' ");
	} 
	$_SESSION['sm'] = $_REQUEST['pp_name']." Saved";
	header("location: index.php?do=photoprods&view=base");
	session_write_close();
	exit();
}
?>
	<div class="pc buttons right textright"><a href="" onClick="addnewproduct(); return false;">Add new product</a></div>
	<div class="clear"></div>

<div class="pc"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull; <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Product Prices</a></div>

<div style="width: 100%;" class="left">

<div id="message-box" class="pageContent"><?php echo $message; ?></div>

	<div style="padding: 0 24px 0 0;">

		<div class="underlinelabel">Prints</div>
		<?php listProductBase("print");?>
	
		<div class="underlinelabel">Downloads</div>
		<?php listProductBase("download");?>

		<div class="underlinelabel">Other</div>
			<?php listProductBase("other");?>

	</div>
</div>





		<?php function listProductBase($section) { 
			?>
		<div class="underlinecolumn">
			<div style="width: 10%; float: left;">&nbsp;</div>
			<div style="width: 25%; float: left;">Name</div>
			<div style="width: 15%; float: left;">Size</div>
			<div style="width: 25%; float: left;">Options</div>

			<div style="width: 15%; float: left;" class="center">#Sold</div>
			<div style="width: 10%; float: left; text-align: right;">Price</div>
			<div class="clear"></div>
		</div>


		<?php $prods = whileSQL("ms_photo_products", "*","WHERE pp_type='".$section."' ORDER BY pp_price ASC ");
		if(mysqli_num_rows($prods)<=0) { ?>
		<div class="underline center">No products added</div>
		
		<?php } ?>
		<?php 
		while($prod = mysqli_fetch_array($prods)) { ?>
		<div class="underline" id="prod-<?php print $prod['pp_id'];?>" pp_id="<?php print $prod['pp_id'];?>" pp_name="<?php print htmlspecialchars($prod['pp_name']);?>"  pp_internal_name="<?php print htmlspecialchars($prod['pp_internal_name']);?>"  pp_download_dem="<?php print htmlspecialchars($prod['pp_download_dem']);?>"  pp_price="<?php print htmlspecialchars($prod['pp_price']);?>"  pp_cost="<?php print htmlspecialchars($prod['pp_cost']);?>"  pp_descr="<?php print htmlspecialchars($prod['pp_descr']);?>" pp_taxable="<?php print htmlspecialchars($prod['pp_taxable']);?>"   pp_width="<?php print htmlspecialchars($prod['pp_width']);?>"  pp_height="<?php print htmlspecialchars($prod['pp_height']);?>" pp_type="<?php print htmlspecialchars($prod['pp_type']);?>"  pp_free="<?php print htmlspecialchars($prod['pp_free']);?>"  pp_free_watermark="<?php print htmlspecialchars($prod['pp_free_watermark']);?>"  pp_free_logo="<?php print htmlspecialchars($prod['pp_free_logo']);?>" pp_add_ship="<?php print htmlspecialchars($prod['pp_add_ship']);?>" pp_free_limit="<?php print htmlspecialchars($prod['pp_free_limit']);?>" pp_free_req_login="<?php print htmlspecialchars($prod['pp_free_req_login']);?>" pp_collapse_options="<?php print $prod['pp_collapse_options'];?>" pp_no_discount="<?php print $prod['pp_no_discount'];?>" pp_no_ship="<?php print $prod['pp_no_ship'];?>" pp_include_download="<?php print $prod['pp_include_download'];?>" pp_disable_download="<?php print $prod['pp_disable_download'];?>"  pp_no_display_dems="<?php print $prod['pp_no_display_dems'];?>" pp_free_all="<?php print $prod['pp_free_all'];?>" pp_watermark_file="<?php print htmlspecialchars($prod['pp_watermark_file']);?>" pp_watermark_location="<?php print htmlspecialchars($prod['pp_watermark_location']);?>" pp_logo_file="<?php print htmlspecialchars($prod['pp_logo_file']);?>"  pp_logo_location="<?php print htmlspecialchars($prod['pp_logo_location']);?>"  pp_no_crop="<?php print htmlspecialchars($prod['pp_no_crop']);?>"  > 
		<div style="width: 10%; float: left;">
		<input  name="prod_id" id="<?php print $prod['pp_id'];?>" value="<?php print $prod['pp_id'];?>" class="batchselect inputtip" title="Select to batch edit" type="checkbox" > 
		
		<a href="" onclick="editproduct('<?php print $prod['pp_id'];?>'); return false;" class="tip" title="Edit"><span class="the-icons icon-pencil"></span></a> 
		<a href="index.php?do=photoprods&view=base&deleteProd=<?php print $prod['pp_id'];?>"  onClick="return confirm('Are you sure you want to delete this product? Deleting this will permanently remove it and can not be reversed.');" class="tip" title="Delete"><span class="the-icons icon-trash-empty"></span></a> 
		<a href="" onclick="productphotos('<?php print $prod['pp_id'];?>'); return false;" class="tip" title="Preview Graphic"><span class="the-icons icon-picture"></span></a>
		<a href="index.php?do=photoprods&view=base&action=duplicatePhotoProduct&pp_id=<?php print $prod['pp_id'];?>" onClick="return confirm('Are you sure you want to duplicate this product? ');" class="tip" title="Duplicate Product"><span class="the-icons icon-docs"></span></a>
		</div>
		<div style="width: 25%; float: left;">
		<div>
		<?php 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE bp_product='".$prod['pp_id']."' ORDER BY bp_order ASC");
		if(!empty($pic['pic_id'])) { 
			?>
		<img src="<?php print getimagefile($pic,'pic_mini')?>" border="0" class="thumbnail left" style="margin: 0 8px 8px 0;">
		<?php } ?>
		
		<h3 style="display: inline;"><?php print $prod['pp_name'];?></h3></div>
		<?php if(!empty($prod['pp_internal_name'])) { ?><?php print $prod['pp_internal_name'];?><?php } ?>
		</div>
		<div style="width: 15%; float: left;">
		<?php if($prod['pp_type'] == "print") {  
			if($prod['pp_width'] > 0) { 
				print round($prod['pp_width'],2);?> X <?php print round($prod['pp_height'],2); 
			} else { 
				print "&nbsp;";
			}
		} elseif($prod['pp_type'] == "download") { if($prod['pp_download_dem'] <=0) { print "Largest available size"; } else { print round($prod['pp_download_dem'])." px"; } 
		} else { 
			print "&nbsp;";
		}
		?>
		</div>
		<div style="width: 25%; float: left;">
<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC "); ?>

		<div class="pc subeditclick">Options (<?php print mysqli_num_rows($opts);?>)</div>
				<div class="theseopts hidden subedit">
				<div class="small"><a href="" onclick="editoption('0','<?php print $prod['pp_id'];?>'); return false;" >Create new option</a></div>
				<?php 
				if(mysqli_num_rows($opts) <= 0 ) {?>
				<div class="small">No options created</div> 
				<?php } ?>


			
			
			
			
			<?php // SORT FUNCTION START
			$add = "opt-".$prod['pp_id'];
			?>
			<script>
			jQuery(document).ready(function() {
				sortItems('sortable-list-<?php print $add;?>','sort_order-<?php print $add;?>','orderOptions');
			});
			</script>
			<form id="dd-form-<?php print $add;?>" action="index.php" method="post">
			<input type="hidden" name="prod_id" value="<?php print $prod['pp_id'];?>">
			<?php
			unset($order);
				$tops = whileSQL("ms_product_options", "*","WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC  ");	
				while($top = mysqli_fetch_array($tops)) {
				$order[] = $top['opt_id'];
			}
			?>
			<input type="hidden" name="sort_order" id="sort_order-<?php print $add;?>" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
			<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
			<p style="display: none;">
			  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
			  <label for="autoSubmit">Automatically submit on drop event</label>
			</p>
			</form>




			<ul id="sortable-list-<?php print $add;?>" class="sortable-list">


			<?php while($opt = mysqli_fetch_array($opts))  { 
				$total_opts++;
				?>
				<li title="<?php print $opt['opt_id'];?>"><?php showProductOption($opt,$section); ?></li>
				<?php
			 } ?>
			 </ul>

		<?php if(mysqli_num_rows($opts)  > 1 ) {?><div class="pc">Drag & drop to change display order</div><?php } ?>
			</div>
		</div>
		<div style="width: 15%; float: left;" class="center">
		<?php
			$tsold = countIt("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "WHERE cart_photo_prod='".$prod['pp_id']."' AND order_payment_status='Completed' AND order_status<='1' ") + countIt("ms_cart_archive LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id", "WHERE cart_photo_prod='".$prod['pp_id']."' AND order_payment_status='Completed' AND order_status<='1' ");
		if($tsold > 0) { 
			print $tsold;	
		} else { 
			print "&nbsp;";
		}
		// print showPrice($prod['pp_cost']);?>
		</div>
		<div style="width: 10%; float: left; text-align:right;">
		<div><h3><?php print showPrice($prod['pp_price']);?></h3></div>
		<?php if($prod['pp_add_ship'] > 0) { ?>
		<div><?php print showPrice($prod['pp_add_ship']);?> additional shipping</div>
		<?php } ?>
		</div>

			<div class="clear"></div>
			<?php if(!empty($prod['pp_descr'])) { ?>
			<div class="sub"><?php print nl2br($prod['pp_descr']);?></div>
			<?php } ?>
		</div>
		<?php } ?>
		<div>&nbsp;</div>
<?php } ?>


<script>
function selectphototype() { 
	if($("#pp_type").val() == "download") { 
		$(".download").slideDown(200);
		$(".other").slideUp(200);
	} else { 
		$(".other").slideDown(200);

		$(".download").slideUp(200);
	}

	if($("#pp_type").val() == "print") { 
		$(".prints").slideDown(200, function() { 
		showincludedownload();		
		});
	} else { 
		$(".prints").slideUp(200);
	}

}

function addnewproduct() { 
	var fields = ["pp_name","pp_internal_name","pp_descr","pp_cost","pp_price","pp_type","pp_taxable","pp_download_dem","pp_width","pp_height","pp_id","pp_free"];
	var len = fields.length
	for (var i=0; i<len; ++i) {
	  if (i in fields) {
		var s = fields[i];
		if($("#"+s).attr("type") == "checkbox") { 
			$("#"+s).prop("checked",false);
		} else { 
			$("#"+s).val("");
		}
	  }
	}	

	$("#pp_collapse_options").attr("checked",true);
	$("#editprod").hide();
	$("#newprod").show();
	openedit();
}
function openedit() { 
	$("#prodedit").css({"top":$(window).scrollTop()+50+"px"});
	$("#prodedit").slideDown(200, function() { 
		$("#editclose").show();
		freedownloadsettings();
		watermarksettings();
		logosettings();

	});
	selectphototype();
}


function closeeditproduct() { 
	$("#editclose").hide();

	var fields = ["pp_name","pp_internal_name","pp_descr","pp_cost","pp_price","pp_type","pp_taxable","pp_download_dem","pp_width","pp_height","pp_id"];
	var len = fields.length
	for (var i=0; i<len; ++i) {
	  if (i in fields) {
		var s = fields[i];
		if($("#"+s).attr("type") == "checkbox") { 
			$("#"+s).prop("checked",false);
		} else { 
			$("#"+s).val("");
		}
	  }
	}	

	$("#prodedit").slideUp(200);
}

function editproduct(id) { 
	var fields = ["pp_name","pp_internal_name","pp_descr","pp_cost","pp_price","pp_type","pp_taxable","pp_download_dem","pp_width","pp_height","pp_id","pp_free","pp_free_watermark","pp_free_logo", "pp_add_ship", "pp_free_limit","pp_free_req_login","pp_collapse_options","pp_no_ship","pp_no_discount","pp_include_download","pp_disable_download","pp_no_display_dems","pp_free_all","pp_watermark_file","pp_watermark_location","pp_logo_file","pp_logo_location","pp_no_crop"];
	var len = fields.length
	for (var i=0; i<len; ++i) {
	  if (i in fields) {
		var s = fields[i];
		if($("#"+s).attr("type") == "checkbox") { 
			if($("#prod-"+id).attr(s) == "1") { 
				$("#"+s).attr("checked","checked");
			} else { 
				$("#"+s).attr("checked",false);
			}
		} else { 
			if(s == "pp_download_dem") { 
				$("#pp_download_dem_free").val($("#prod-"+id).attr(s));
			}
			$("#"+s).val($("#prod-"+id).attr(s));
		}
	  }
	}	
	$("#editprod").show();
	$("#newprod").hide();
	openedit();
}


 $(document).ready(function(){
	selectphototype();
 });
</script>


<div class="clear"></div>


<div style="" class="pform hide" id="prodedit">
	<div style="position: absolute; right:8px; top: 8px; display: none;" id="editclose"><a href="" onclick="closeeditproduct(); return false"><?php print ai_close;?></a></div>
	<div style="padding: 24px;">
	<div >

	<div class="underlinelabel hidden" id="editprod">Edit Product</div>
	<div class="underlinelabel hidden" id="newprod">Add New Product</div>

	<form method="post" name="pprods" id="pprods" action="index.php" onSubmit="return checkForm();">

	<div class="underline">
		<div style="width:69%; float: left;">
		<div style="width: 49%; float: left;">
		<div class="label">Product Display Name</div>
		<div><input type="text" name="pp_name" id="pp_name" class="required field100" size="20"  value="<?php print htmlspecialchars($pp['pp_name']);?>"></div>
		<div>Name of the product shown to the customer</div>
		</div>
		<div style="width: 49%; float: right;">
		<div class="label">Your Reference Name</div>
		<div><input type="text" name="pp_internal_name" id="pp_internal_name" class="field100" size="20"  value="<?php print htmlspecialchars($pp['pp_internal_name']);?>"></div>
		<div>This is a name for your reference (optional)</div>
		</div>
		<div class="clear"></div>

		</div>
		<div style="width: 29%; float: right;">
		<div class="label">Product Type</div>
			<div>
			<select name="pp_type" id="pp_type" onChange="selectphototype();">
			<option value="print" <?php if($pp['prod_type'] == "print") { print "Selected"; } ?>>Print</option>
			<option value="download" <?php if($pp['prod_type'] == "download") { print "Selected"; } ?>>Download</option>
			<option value="other" <?php if($pp['prod_type'] == "other") { print "Selected"; } ?>>Other</option>
			</select>
		</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="underline">
		<div class="left" style="width:30%;" >
			<div class="label">Default Price</div>
			<div><input type="text" name="pp_price" id="pp_price" class="" size="8" value="<?php print htmlspecialchars($pp['pp_price']);?>"></div>
		</div>
		<!-- 
		<div class="left" style="width: 30%;">
			<div class="label">Your Cost</div>
			<div><input type="text" name="pp_cost" id="pp_cost" class="" size="8"  value="<?php print htmlspecialchars($pp['pp_cost']);?>"></div>
		</div>
		-->
		<div class="left" style="width: 30%;">
			<div class="label"><input type="checkbox" name="pp_taxable" id="pp_taxable" value="1" <?php if($pp['pp_taxable'] == "1") { print "checked"; } ?>> <label for="pp_taxable">Taxable</label></div>
		</div>

		<div class="clear"></div>
	</div>

	<script>
	function showincludedownload() { 
		if($("#pp_include_download").attr("checked")) { 
			$(".download").slideDown(200);
			$(".selectfree").hide();
		} else { 
			$(".download").slideUp(200);
			$(".selectfree").show();
		}
	}
	</script>
	<div class="underline prints">
		<div class="label"><input type="checkbox" name="pp_include_download" id="pp_include_download" onchange="showincludedownload();"   value="1"  <?php if($pp['pp_include_download'] == "1") { print "checked"; } ?>> <label for="pp_include_download">Include a download photo with this product</label></div>
		<div>Selecting this option will allow the customer to also download a file of the image.</div>	
	</div>



	<div class="underline download nofree">
	<div class="label">
	<input type="checkbox" name="pp_disable_download" id="pp_disable_download" value="1" <?php if($prod['pp_disable_download'] == "1") { print "checked"; } ?>> <label for="pp_disable_download">Do not allow download until I upload a replacement file or manually approve it.</label> <div class="moreinfo" info-data="nodownloadphoto"><div class="info"></div></div></div>
	</div>
	<div class="underline download nofree">
		<div class="label selectfree">
		<input type="checkbox" name="pp_no_display_dems" id="pp_no_display_dems" value="1" <?php if($prod['pp_no_display_dems'] == "1") { print "checked"; } ?>> <label for="pp_no_display_dems">Do not display download photos sizes with product</label> 
		</div>
	</div>

	<div  id="downloadsize" class="download underline">
		<div id="maxpixpaid">
			<div class="left" style="width: 30%;">
				<div class="label">Max. Pixels</div>
				<div><input type="text" name="pp_download_dem" id="pp_download_dem" class="center" size="4"  value="<?php print htmlspecialchars($pp['pp_download_dem']);?>"> px</div>
			</div>
			<div class="left" style="width: 70%;">
			This is the size of the longest side of the photo. Example: 1200. For the largest available size for download, enter in 0.
			</div>
		<div class="clear"></div>
		</div>
	

		<div id="maxpixfree">
			<div class="left" style="width: 40%;">
				<div class="label">Max. Pixels</div>
				<div><textarea name="pp_download_dem_free" id="pp_download_dem_free" class="" rows="4" cols="30"><?php print htmlspecialchars($pp['pp_download_dem']);?></textarea></div>
			</div>

			<div class="left" style="width: 60%;">
			<p>Enter in the size for the longest side of the photo. Example: 1200. For the largest available size for download, enter in 0.</p>
			<p><b>To offer multiple sizes</b>, enter in the format as <b>size,name, one per line</b>. Example: </p>
			<p>0,Large<br>
				1600,Medium<br>
				800,Small<br>
				</p>
				<p>The above example will offer 3 different sizes. 0,Large being the largest available (original upload) called "Large". 1600,Medium: a download at 1600 pixels on the longest side called "Medium".
			</div>
		<div class="clear"></div>
		</div>
	</div>


	<script>
	function freedownloadsettings() { 
		if($("#pp_free").attr("checked")) { 
			$("#maxpixfree").show();
			$("#maxpixpaid").hide();
			$(".freesettings").show();
		} else { 
			$(".freesettings").hide();
			$("#maxpixfree").hide();
			$("#maxpixpaid").show();

		}
	}
	function watermarksettings() { 
		if($("#pp_free_watermark").attr("checked")) { 
			$(".watermarksettings").show();
		} else { 
			$(".watermarksettings").hide();
		}
	}

	function logosettings() { 
		if($("#pp_free_logo").attr("checked")) { 
			$(".logosettings").show();
		} else { 
			$(".logosettings").hide();
		}
	}


	</script>
	<div class="underline download">
		<div class="label selectfree">
			<input type="checkbox" name="pp_free" id="pp_free" value="1" onchange="freedownloadsettings();"  class="check-with-label"> <label for="pp_free" class="label-for-check">Free Download</label><div class="moreinfo" info-data="freedownload"><div class="info"></div></div>

		</div>
		<div class="underline freesettings" >
			<input type="checkbox" name="pp_free_req_login" id="pp_free_req_login" value="1"  class=""> <label for="pp_free_req_login">Require customer to create an account and be logged in</label>
		</div>
		<div class="underline freesettings">
			<input type="checkbox" name="pp_free_all" id="pp_free_all" value="1"  class=""> <label for="pp_free_all">Allow customer to download all in zip files</label>
		</div>
		<div class="underline freesettings">Limit the number of free downloads per gallery to <input type="text" name="pp_free_limit" id="pp_free_limit" size="3" class="center"> per person.</div>
		<div class="underline freesettings">Enter 0 to not limit the amount of free downloads.</div>
	</div>

	

		<div class="underline download" >
			<input type="checkbox" name="pp_free_watermark" id="pp_free_watermark" value="1" class=""  onchange="watermarksettings();"> <label for="pp_free_watermark">Add Watermark</label>
		</div>
	
		<div class="download">
			<div class="watermarksettings">
			<?php 
				$wm_folder =$setup['photos_upload_folder']."/watermarks";
				$wm_folder_path = $setup['path']."/".$wm_folder."";
				if(!is_dir($wm_folder_path)) {
					print "<div class=\"underline\">You have not uploaded any watermark files</div>";
				} else {
					?>
					<div class="pc">
					<select name="pp_watermark_file" id="pp_watermark_file">
					<option value="">Default Watermark FIle</option>
					<?php 
					$dir = opendir($wm_folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) { 
							?>
							<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['wm_images_file']) { print "selected"; } ?>><?php print $file;?></option>
						<?php 
						}
					}
					print "</select></div>";

				@closedir($dir); 
				}
			?>
				<div class="pc">
					<select name="pp_watermark_location" id="pp_watermark_location">
					<option value="">Default Watermark Location</option>

					<option value="tile" <?php  if($_REQUEST['wm_images_location'] == "tile") { print "selected"; } ?>>Tile</option>
					<option value="center" <?php  if($_REQUEST['wm_images_location'] == "center") { print "selected"; } ?>>Center</option>
					<option value="bright" <?php  if($_REQUEST['wm_images_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
					<option value="bottom" <?php  if($_REQUEST['wm_images_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
					<option value="bleft" <?php  if($_REQUEST['wm_images_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
					<option value="uright" <?php  if($_REQUEST['wm_images_location'] == "uright") { print "selected"; } ?>>Top Right</option>
					<option value="top" <?php  if($_REQUEST['wm_images_location'] == "top") { print "selected"; } ?>>Top Middle</option>
					<option value="uleft" <?php  if($_REQUEST['wm_images_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
					</select>
				</div>

			</div>
		</div>

		<div class="underline download">
			<input type="checkbox" name="pp_free_logo" id="pp_free_logo" value="1"  class=""   onchange="logosettings();"> <label for="pp_free_logo">Add Logo</label>
		</div>

		<div class="download">
			<div class="logosettings">
			<?php 

				$wm_folder =$setup['photos_upload_folder']."/watermarks";
				$wm_folder_path = $setup['path']."/".$wm_folder."";
				if(!is_dir($wm_folder_path)) {
					print "<div class=\"underline\">You have not uploaded any watermark files</div>";
				} else {
					?>
					<div class="pc">
					<select name="pp_logo_file" id="pp_logo_file" >
					<option value="">Default Logo File</option>

					<?php 
					$dir = opendir($wm_folder_path); 
					while ($file = readdir($dir)) { 
						if (($file != ".") && ($file != "..")) { 
							?>
							<option value="<?php print $wm_folder."/".$file;?>" <?php if($wm_folder."/".$file == $_REQUEST['wm_logo_file']) { print "selected"; } ?>><?php print $file;?></option>
						<?php 
						}
					}
					print "</select></div>";
				@closedir($dir); 
				}
			?>

		<div class="pc">
			<select name="pp_logo_location" id="pp_logo_location">
			<option value="">Default Logo Location</option>
			<option value="tile" <?php  if($_REQUEST['wm_add_logo_location'] == "tile") { print "selected"; } ?>>Tile</option>
			<option value="center" <?php  if($_REQUEST['wm_add_logo_location'] == "center") { print "selected"; } ?>>Center</option>
			<option value="bright" <?php  if($_REQUEST['wm_add_logo_location'] == "bright") { print "selected"; } ?>>Bottom Right</option>
			<option value="bottom" <?php  if($_REQUEST['wm_add_logo_location'] == "bottom") { print "selected"; } ?>>Bottom Middle</option>
			<option value="bleft" <?php  if($_REQUEST['wm_add_logo_location'] == "bleft") { print "selected"; } ?>>Bottom Left</option>
			<option value="uright" <?php  if($_REQUEST['wm_add_logo_location'] == "uright") { print "selected"; } ?>>Top Right</option>
			<option value="top" <?php  if($_REQUEST['wm_add_logo_location'] == "top") { print "selected"; } ?>>Top Middle</option>
			<option value="uleft" <?php  if($_REQUEST['wm_add_logo_location'] == "uleft") { print "selected"; } ?>>Top Left</option>
			</select>
		</div>
	</div>
</div>


	<div class="underline prints">
		<div style="float: left; width: 50%;">
		<div class="label">Print Dimensions</div>
		<div><input type="text" name="pp_width" id="pp_width" class="center" size="3" value="<?php print htmlspecialchars($pp['pp_width']);?>">  X <input type="text" name="pp_height" id="pp_height" class="center" size="3" value="<?php print htmlspecialchars($pp['pp_height']);?>"></div>
		<div>Entering in print dimensions allows customers to set their own crop.</div>
		</div>
		
		<div style="float: left; width: 50%;">
			<div><input type="checkbox" name="pp_no_crop" id="pp_no_crop" value="1" <?php if($pp['pp_no_crop'] == "1") { ?>checked<?php } ?>> <label for="pp_no_crop">Do not allow cropping</label></div>
			<div>Select this option IF you have entered in Print Dimensions to the left BUT you don't want to allow the customer to crop. This will allow the customer to see the center crop, but not select their own crop.</div>
		</div>
		<div class="clear"></div>
		

	</div>
	<div class="underline">
		<div class="label">Additional shipping charge</div>
		<div><input type="text" name="pp_add_ship" id="pp_add_ship" class="center" size="8" value="<?php print htmlspecialchars($pp['pp_add_ship']);?>"> </div>
		<div>This amount will be added to the shipping calculation.</div>

	</div>

	<div class="underline">
		<div class="label"><input type="checkbox" name="pp_collapse_options" id="pp_collapse_options"   value="1"  <?php if($pp['pp_collapse_options'] == "1") { print "checked"; } ?>> <label for="pp_collapse_options">Collapse Options & Descriptions</label></div>
		<div>Select this option for a cleaner product list when people are viewing available products where they will click the product to view any options, descriptions and product photos. Recommended if there are any options, image options and long descriptions.</div>	
	</div>

	<div class="underline nofree">
		<div class="label"><input type="checkbox" name="pp_no_discount" id="pp_no_discount"   value="1"  <?php if($pp['pp_no_discount'] == "1") { print "checked"; } ?>> <label for="pp_no_discount">Do not allow discounting <div class="moreinfo" info-data="prodnodiscount"><div class="info"></div></div></label></div>
	</div>
	<div class="underline other">
		<div class="label"><input type="checkbox" name="pp_no_ship" id="pp_no_ship"   value="1"  <?php if($pp['pp_no_ship'] == "1") { print "checked"; } ?>> <label for="pp_no_ship">Do not calculate shipping <div class="moreinfo" info-data="prodnoshipping"><div class="info"></div></div></label></div>
	</div>

	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="pp_descr" id="pp_descr" rows="3" cols="40" class="field100"><?php print htmlspecialchars($pp['pp_descr']);?></textarea></div>
	</div>

	<div class="underline center">
	<input type="hidden" name="do" value="photoprods">
	<input type="hidden" name="view" value="base">
	<input type="hidden" name="action" value="saveproduct">
	<input type="hidden" name="pp_id" id="pp_id" value="<?php print $pp['pp_id'];?>">
	<input type="submit" name="submit" value="Save" id="submitButton" class="submit">
	</div>
	</form>
	</div>


	</div>

</div>
<div class="pc"><a href="" onclick="selectallpages(); return false;">Select All</a>  &bull; <a href="" onclick="deselectallpages(); return false;">Deselect All</a>   &bull;   <a href="" onclick="batcheditpages(); return false;">Batch Edit Product Prices</a></div>
<div>&nbsp;</div>
<div class="pc"><a href="index.php?do=photoprods&view=base&action=collapseallproducts"  onClick="return confirm('Are you sure? Click OK to continue.');">Collapse All Product Options & Descriptions</a><br>
This option will update all existing products to collapse options & descriptions. This was added if you are updating to the 1.9 update with the new product lists.</div>
<div>&nbsp;</div>
