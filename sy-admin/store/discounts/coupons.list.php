<?php 
if(!empty($_REQUEST['deletePromo'])) { 
	$code = doSQL("ms_promo_codes", "*", "WHERE code_id='".$_REQUEST['deletePromo']."' ");
	if(!empty($code['code_id'])) { 
		deleteSQL("ms_promo_codes", "WHERE code_id='".$code['code_id']."' ","1");
		deleteSQL2("ms_cart", "WHERE cart_coupon='".$code['code_id']."' AND cart_order<='0' ");
	}
	$_SESSION['sm'] = "Coupon Deleted";
	session_write_close();
	header("location: index.php?do=discounts&view=".$_REQUEST['view']."");
	exit();
}

if($_REQUEST['action'] == "deletebatch") { 
	if(!empty($_REQUEST['code_batch'])) { 
		$codes = whileSQL("ms_promo_codes", "*", "WHERE code_batch='".addslashes(stripslashes(urldecode($_REQUEST['code_batch'])))."' ");
		while($code = mysqli_fetch_array($codes)) { 
			deleteSQL("ms_promo_codes", "WHERE code_id='".$code['code_id']."'","1");
			if($code['code_print_credit'] > 0) { 
				deleteSQL("ms_print_credits", "WHERE pc_id='".$code['code_print_credit']."' ","1");
			}
		}
	}
	$_SESSION['sm'] = $_REQUEST['code_batch']." batch deleted";
	header("location: index.php?do=discounts");
	session_write_close();
	exit();
}




?>

<div id="pageTitle" class="left"><a href="index.php?do=discounts">Discounts</a> <?php print ai_sep;?> Coupons</div>

<div class="right">
<form method="get" name="promosearch" action="index.php">
<input type="hidden" name="do" value="discounts">
<input type="text" name="code_code" value="<?php print $_REQUEST['code_code'];?>" size="10">
<input type="submit" name="submit" value="go" class="submitSmall">
</form>
</div>
<div class="clear"></div>
<div class="pc">Coupons are a way to give your customers discounts on their purchases which can be redeemed in their cart or at checkout. <?php if($setup['unbranded'] !== true) { ?>
<a href="https://www.picturespro.com/sytist-manual/coupons/" target="_blank">More information in the manual</a>.
<?php } ?><div>&nbsp;</div>



<div class="pc right textright buttons"><a href="" onclick="editcoupon(''); return false;">Create New Coupon</a>
<a href="" onclick="editbonuscoupon(''); return false;">Create New Bonus Coupon</a>
</div>
<div class="cssClear">&nbsp;</div>
<div >&nbsp;</div>




	<?php
	// This determines the size of the columns 
	$cw1 = "10%";
	$cw2 = "10%";
	$cw3 = "15%";
	$cw4 = "20%";
	$cw5 = "20%";
	$cw7 = "10%";
	$cw8 = "15%";


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
		$orderby = "code_id";
	} else { 
		$orderby = $_REQUEST['orderby'];
	}
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	if(empty($_REQUEST['code_code'])) { 
		if($_REQUEST['status'] == "used") { 
			$and_where = "AND code_use_status='1' ";
		} elseif($_REQUEST['status'] == "expired") { 
			$and_where = "AND code_use_status='0'  AND (code_end_date!='0000-00-00' AND code_end_date<'".date('Y-m-d')."') ";
		} else { 
			$and_where = "AND code_use_status='0' AND (code_end_date='0000-00-00' OR  code_end_date>='".date('Y-m-d')."') ";
		}

	}
	if(!empty($_REQUEST['code_code'])) { 
		$and_where .= "AND LOWER(code_code)='".strtolower($_REQUEST['code_code'])."' ";
	}
	if(!empty($_REQUEST['code_batch'])) { 
		$and_where .= "AND code_batch='".addslashes(stripslashes($_REQUEST['code_batch']))."' ";
	}

	$per_page = 20;
	$NPvars = array("do=discounts", "orderby=".$orderby."", "acdc=".$acdc."", "status=".$_REQUEST['status']."" );
	$sq_page = $pg * $per_page - $per_page;	
	$total = countIt("ms_promo_codes",  " WHERE code_id>'0' $and_where "); 
	
	?>


<script>
function showpcexport(){ 
	$("#exportbatch").slideToggle();
}
</script>

<?php if(!empty($_REQUEST['code_batch'])) {  ?>
<div class="pc"><h2>Batch: <?php print $_REQUEST['code_batch'];?></h2>
<a  id="deletebatch" class="confirmdelete tip" confirm-title="Delete All Print Credits In This Batch" confirm-message="Are you sure you want to do this?" href="index.php?do=discounts&code_batch=<?php print urlencode($_REQUEST['code_batch']);?>&action=deletebatch"  title="Delete Batch">delete batch</a> &nbsp; <a href="" onclick="showpcexport(); return false;">export</a></div>
<div id="exportbatch" class="hide">
<form method="POST" action="store/export-coupons.php" target="_blank">
<div class="pc">
<input type="checkbox" name="code_name" id="code_name" value="1" checked> <label for="code_name">Coupon Name</label>  &nbsp; 
<input type="checkbox" name="code_code" id="code_code" value="1" checked> <label for="code_code">Redeem Code</label>  &nbsp; 
<input type="checkbox" name="code_expire" id="code_expire" value="1" checked> <label for="code_expire">Expiration</label>  &nbsp; 
</div>
<div class="pc">
Separate with: <input type="text" name="sep" id="sep" size="2" class="center" value=","> 
</div>
<div class="pc">
<input type="radio"name="dowith" value="exp" id="dowithexp" checked> <label for="dowithexp">Export</label> 
<input type="radio"name="dowith" value="view" id="dowithview"> <label for="dowithview">Print to Screen</label> 
</div>
<div class="pc">
<input type="hidden" name="code_batch" id="code_batch" value="<?php print $_REQUEST['code_batch'];?>">
<input type="submit" name="submit" class="submitSmall" value="GO">
</div>
</form>
<div>&nbsp;</div>

</div>
<?php } ?>




	<?php  $datas = whileSQL("ms_promo_codes", "*, date_format(DATE_ADD(code_end_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."')  AS code_end_date_show", "WHERE code_id>'0' $and_where AND code_date_id='0' ORDER BY $orderby $acdc LIMIT $sq_page,$per_page  ");

		$tmtotal = mysqli_num_rows($datas);
		if($tmtotal <=0) { print "<div class=error><center>No coupons created</center></div>"; } 
		if(mysqli_num_rows($datas)>0) { ?>
			<form method="post" name="listForm" id="listFormSide" action="index.php" style="margin:0px;padding:0px;">
	<div > 
	<div class="roundedFormColContainer" >
			<div  class="roundedFormColLabel" style="width: <?php print $cw1;?>;">Action</div>
			<div  class="roundedFormColLabel" style="width: <?php print $cw2;?>;">Status</div>
			<div  class="roundedFormColLabel" style="width: <?php print $cw3;?>;">Name</div>
			<div class="roundedFormColLabel"  style="width: <?php print $cw4;?>;">Code</div>
			<div class="roundedFormColLabel"  style="width: <?php print $cw5;?>;">Discount </div>
			<div class="roundedFormColLabel textright"  style="width: <?php print $cw7;?>;">Expires</div>
			<div class="roundedFormColLabel textright"  style="width: <?php print $cw8;?>;">Batch</div>
			<div  class="cssClear"></div>
		</div>

		<?php
		while ($data = mysqli_fetch_array($datas)) {
			$totalLinks = mysqli_num_rows($datas);
			$rownum++;
			$thisLink++;
			?>
		<div class="underline">
			
				<div style="width: <?php print $cw1;?>;" class="cssCell"><nobr>
				<?php if($data['code_print_credit'] > 0) { ?>
					<a href="" onclick="editbonuscoupon('<?php print $data['code_id'];?>'); return false;" title="Edit Coupon" class="tip"><span class="the-icons icon-pencil"></span></a> 

				<?php } else { ?>
					<a href="" onclick="editcoupon('<?php print $data['code_id'];?>'); return false;" title="Edit Coupon" class="tip"><span class="the-icons icon-pencil"></span></a> 
				<?php } ?>
					<?php if(empty($data['link_main'])) { ?><a href="index.php?do=discounts&deletePromo=<?php print $data['code_id'];?>&view=<?php print $_REQUEST['view'];?>"  onClick="return confirm('Are you sure you want to delete this coupon? ');" title="Delete" class="tip"><span class="the-icons icon-trash-empty"></a> <?php } ?>
				</nobr></div>
				<div style="width: <?php print $cw2;?>; " class="cssCell">

						<?php 
					if($data['code_use_status'] == "1") {
						print " <span class=\"inactive\">USED</span>";
					} elseif(($data['code_end_date']!=="0000-00-00")AND($data['code_end_date']<date('Y-m-d'))==true) {
						print " <span class=\"inactive\">EXPIRED</span>";
					} else {
						print " <span class=\"green\">Good</span>";
					}
					unset($used);
					$used = countIt("ms_orders", "WHERE order_coupon_id='".$data['code_id']."' ");
					if($used > 0) { 
				?>

				  (<a href="index.php?do=orders&order_coupon_id=<?php print $data['code_id'];?>" class="tip" title="Used on <?php print $used;?> orders"><?php print countIt("ms_orders", "WHERE order_coupon_id='".$data['code_id']."' "); ?></a>)
				  <?php } ?>
				</div>

				<div style="width: <?php print $cw3;?>;" class="cssCell">
				<?php if($data['code_print_credit'] > 0) { ?>
					<a href="" onclick="editbonuscoupon('<?php print $data['code_id'];?>'); return false;" title="Edit Coupon">
				<?php } else { ?>
				<a href="" onclick="editcoupon('<?php print $data['code_id'];?>'); return false;" title="Edit Coupon">
				<?php } ?><?php print $data['code_name']; ?></a></div>
				<div style="width: <?php print $cw4;?>;" class="cssCell"><?php print $data['code_code']; ?></a></div>

				<div style="width: <?php print $cw5;?>;" class="cssCell">
				<?php 
				if($data['code_print_credit'] > 0) { 
					print "<div>Bonus Coupon: </div>";
					print "<div class=\"sub small\">";
				$x = 0;
				$pack = doSQL("ms_print_credits LEFT JOIN ms_packages ON ms_print_credits.pc_package=ms_packages.package_id", "*", "WHERE pc_id='".$data['code_print_credit']."' "); 

				if($pack['package_select_only'] == "1") { print $pack['package_select_amount']." photos to be selected"; } 

				$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
				while($prod = mysqli_fetch_array($prods)) { 
					if($prod['con_qty'] > 0) { 
						if($x > 0) { print ", "; } 
						print "<nobr>".$prod['con_qty'].": ".$prod['pp_name']."</nobr>";
						$x++;
					}
				}
				?> 
				&nbsp; <a href="index.php?do=photoprods&view=packages&package_id=<?php print $pack['package_id'];?>">view collection</a> 
					</div>
					<?php 
					} else { 
					$prices = whileSQL("ms_promo_codes_discounts", "*", "WHERE dis_promo='".$data['code_id']."' ORDER BY dis_id ASC ");
					while($price = mysqli_fetch_array($prices)) {
						print "<div class=\"pc\"><div class=\"left\">".showPrice($price['dis_from'])." - ".showPrice($price['dis_to'])."</div><div class=\"right textright\">";
						if($data['code_discount_type'] == "percentage") { 
							print  $price['dis_percent']."%";
						}
						if($data['code_discount_type'] == "flat") {
							print showPrice($price['dis_flat']);
						}
						print "</div><div class=\"clear\"></div></div>";
					}
				}
			?>

				</div>
				<div style="width: <?php print $cw7;?>;" class="cssCell textright"><?php if($data['code_end_date']!=="0000-00-00") { print $data['code_end_date_show']; } else { print "n/a"; } ?></a></div>
				<div style="width: <?php print $cw8;?>;" class="cssCell textright">
				<?php if(!empty($data['code_batch'])) { ?><a href="index.php?do=discounts&code_batch=<?php print urlencode($data['code_batch']);?>"><?php print $data['code_batch'];?></a><?php } else { print "&nbsp;"; } ?>
				</div>


					<div class="cssClear"></div>
		</div>

			<?php } ?>
		<?php 	
			unset($thisLink);	
			if($tmtotal > 1) { ?>
		<div>&nbsp;</div>
		<?php } ?>

		</div></form>
<div>&nbsp;</div>
<?php } ?>
</div>
<?php if($total > $per_page) {?>

<div class="pc center"><center><?php print nextprevHTMLMenu($total, $pg, $per_page,  $NPvars, $req);?></center></div> 
<?php } ?>
