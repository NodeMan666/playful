<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

</script>
<?php
function addprintcreditpackages($pack,$cart_id) { 

	$product_name = $pack['package_name'];
	$p_cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$date['date_id']."', cart_cost='".$pack['package_cost']."' , cart_group_id='".$group['group_id']."', cart_credit='".$pack['package_credit']."', cart_extra_ship='".$cart_extra_ship."', cart_package_buy_all='".$pack['package_buy_all']."', cart_no_discount='".$pack['package_no_discount']."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_sku='".addslashes(stripslashes($pack['package_internal_name']))."', cart_package_include='".$cart_id."' ");



	if($pack['package_select_only'] == "1") { 
		$p = 1;
		while($p <= $pack['package_select_amount']) { 
			insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
			$p++;
		}


	} else { 

		$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
		while($prod = mysqli_fetch_array($prods)) { 
			$cart_download = 0;
			$cart_ship = 0;
			if($prod['pp_type'] =="download") {
				$cart_download = 1;
				$cart_ship = 0;
				}
			$q = 1;
			while($q <= $prod['con_qty']) { 
				insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
				$q++;
			}
		}
	}
}
	

if($_POST['submitit']=="yes") { 

		$pack = doSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_id='".$_REQUEST['pc_id']."' AND pc_order<='0' ");
		if($pack['pc_id'] <= 0) { 
			print _print_credit_not_valid_;
		} else { 
			if(empty($pack['package_id'])) { 
				die("Unable to find product");
			}

			if($pack['pc_ship'] =="1") {
				$cart_ship = 1;
			}
			if($con['pc_price'] > 0) { 
				$cart_price = $con['pc_price'];
			} else { 
				$cart_price = $pack['package_price'];
			}
			$product_name = $pack['pc_name'];


			if($pack['package_select_only'] =="2") { 
				$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($pack['pc_code']))."' , cart_group_id='".$group['group_id']."', cart_package_no_select='1', cart_no_delete='1'  ");

				$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
				while($prod = mysqli_fetch_array($prods)) { 
					addprintcreditpackages($prod,$cart_id);
				}
			} else { 

				$cart_id = insertSQL("ms_cart", "cart_qty='1', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='0.00', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$_REQUEST['did']."', cart_cost='".$pack['package_cost']."', cart_print_credit='".addslashes(stripslashes($pack['pc_code']))."' , cart_group_id='".$group['group_id']."'");


				if($pack['package_select_only'] == "1") { 
					$p = 1;
					while($p <= $pack['package_select_amount']) { 
						insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
						$p++;
					}


				} else { 

					$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
					while($prod = mysqli_fetch_array($prods)) { 
						$cart_download = 0;
						$cart_ship = 0;
						if($prod['pp_type'] =="download") {
							$cart_download = 1;
							$cart_ship = 0;
							}
						$q = 1;
						while($q <= $prod['con_qty']) { 
							insertSQL("ms_cart", "cart_package_photo='$cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service' , cart_client='".MD5($_REQUEST['p_id'])."' , cart_date=NOW(), cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_disable_download='".$prod['pp_disable_download']."' ");
							$q++;
						}
					}
				}
			}
		}
	$_SESSION['sm'] = "Print credited added to their cart";

	header("location: ../index.php?do=people&p_id=".$_REQUEST['p_id']."&view=credits");
	session_write_close();
	exit();
}
?>

<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<?php if($_REQUEST['showDeleteSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Option Deleted");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>


<?php 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' "); 
	if(empty($p['p_id'])) {
		showError("Sorry, but there seems to be an error.");
	}
	?>
	<div class="pc"><h1>Add a print credit for <?php print $p['p_name']." ".$p['p_last_name'];?></h1></div>
	<div class="pc">This will add a print credit to this person's cart for them to select photos for. If you decide you want to remove it, you will need to delete their shopping cart.</div>
	<?php if($setup['unbranded'] !== true) { ?><div class="pc"><a href="https://www.picturespro.com/sytist-manual/photo-products/print-credits/" target="_blank">Print credits in the manual</a></div><?php } ?>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
		<div class="label">Select Print Credit To Add</div>
		<div>
		<?php $pcs = whileSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_order<='0' ORDER BY pc_id DESC ");
		if(mysqli_num_rows($pcs) <= 0) { ?>
		<div class="pc">You must first create a print credit in <a href="index.php?do=photoprods&view=printcredits">Photo Products -> Print Credits</a></div>

	<?php 
	} else { 
		?>
		<select name="pc_id" id="pc_id" class="optrequired">
		<option value="">Select here</option>
		<?php 
		while($pc = mysqli_fetch_array($pcs)) { ?>
		<option value="<?php print $pc['pc_id'];?>"><?php print $pc['pc_name'];?> (<?php print $pc['package_name'];?>)</option>
		<?php } ?>
		</select>
		<?php } ?>
		</div>
	</div>






	<div class="pageContent center">

	<input type="hidden" name="p_id" value="<?php print $_REQUEST['p_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="Add Print Credit" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>