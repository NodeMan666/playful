<?php function productCart() { 
	global $date,$setup,$store,$site_setup,$from_photo;

	if(countIt("ms_product_subs", "WHERE sub_main_prod='".$date['date_id']."' ") > 0) { 
	 $has_subs = true;
	}
	?>


<div id="purchaseaccess" style="max-width: 800px; margin: auto;">
<div class="pc"><h1><?php print _purchase_access_for_;?> <?php print $date['date_title'];?></h1></div>
<div class="pc"><?php print _purchase_access_message_;?></div>

<script>
$(document).ready(function(){

	$(".qtydiscount").hoverIntent(
	  function () {
		$(this).find('.proddiscount').slideDown(100);
	  },
	  function () {
		$(this).find('.proddiscount').slideUp(100);
	  }
	);

   $(".hval").val("");
   $(".productoption li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#"+$(this).attr('opt')).val($(this).attr('val'));
		// checkStock();

		checkStockNew($(this).attr('id'));

    });

   $(".productoptionselect li").click(function(){
		$(this).parent().children().removeClass('on');
		$(this).addClass('on');
		$("#"+$(this).attr('fid')).val($(this).attr('sel_id'));
		// checkStock();

		checkStockNew($(this).attr('id'));

    });


   $(".inputtabs").each(function(){
		$(this).val($(this).attr("defval"));
   });

});

</script>

<div class="pc">
<?php 
$price = productPrice($date);
if($price['onsale'] == true) { 
	if(($date['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		 $price['onsale'] = $price['onsale']+ (($price['onsale'] * $site_setup['include_vat_rate']) / 100);
	}
	print "<div class=\"onsaleprice\">".showPrice($price['org'])."</div>";
}
	$this_price = $price['price'];
	if(($date['prod_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		 $this_price = $this_price + (($this_price * $site_setup['include_vat_rate']) / 100);
	}
	?>
			<?php if(countIt("ms_products_discounts","WHERE dis_prod='".$date['date_id']."' ") > 0) { ?>
			
			<div class=" qtydiscount sub" style=" z-index: 4;">
				<div><a href="" onclick="return false;"><?php print _quantity_discounts_;?></a></div>
				<div class="proddiscount left">
					<div class="inner">
					<?php if(!empty($date['date_qty_descr'])) { ?>
						<div class="pc"><?php print nl2br($date['date_qty_descr']);?></div>
						<?php } ?>
						<?php 
						$diss = whileSQL("ms_products_discounts", "*", "WHERE dis_prod='".$date['date_id']."' ORDER BY dis_price DESC");
						while($dis = mysqli_fetch_array($diss)) { ?>
						<div class="pc">
							<div style="float: left; width: 60%;">
							<?php if($dis['dis_qty_to'] <=0) { ?>
								<?php print $dis['dis_qty_from']." +"; ?>
							<?php } else { ?>
								<?php print $dis['dis_qty_from']." - ".$dis['dis_qty_to']; ?>
							<?php } ?>
							</div>
							<div style="float: right; width: 40%;" class="textright">
							<?php
								if(($prod['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
									$dis['dis_price'] = $dis['dis_price']+ (($dis['dis_price'] * $site_setup['include_vat_rate']) / 100);
								}
								print showPrice($dis['dis_price'])." "._each_; ?>
							</div>
							<div class="clear"></div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<?php } ?>
</div>
<?php if($price['onsale'] == true) { ?><div class="pageContent"><?php print nl2br($date['prod_sale_message']);?></div><?php } ?>

<div class="producttocart">


<?php if(($date['prod_inventory_control'] == "1")&&($has_subs !== true)&&($date['prod_qty'] <=0)==true) { ?>
<div id="prodmessage" class="pc">Currently out of stock</div>

<?php } else { ?>



<div id="prodmessage" class="pc hide"></div>
<div id="submitinfo"></div>
<div class="error hide" id="min_qty_message" style="margin-bottom: 8px;"><?php print _min_qty_required_?> <?php if($date['qty_min'] > 0) { print $date['qty_min']; } else { print "1"; } ?></div>
<div class="error hide" id="select_option_message" style="margin-bottom: 8px;"><?php print _please_select_option_?></div>

<form name="purchase" action="/" method="post">
	<input type="hidden" name="action" id="action" class="cartoption" value="addToCart">
	<input type="hidden" name="qty_min" id="qty_min" class="cartoption" value="<?php if($date['qty_min']<=0) { print "1"; } else { print $date['qty_min']; } ?>">
	<input type="hidden" name="spid" id="spid" class="cartoption">
	<?php if($from_photo == true) { 
		if((countIt("ms_product_options", "WHERE opt_date='".$date['date_id']."' AND opt_photos='1' ") || $date['prod_photos'] > 0)==true) { 
			if($date['prod_photos'] == "1") { 
				$num_photos = 1;
			} else { 
				$num_photos = 2;
			}
		}
	} ?>
	<?php 
	if($this_price > 0) { 
		print "<div class=\"productprice left\" style=\"margin-right: 16px;\"><span id=\"prodprice\" org=\"".showPrice($price['price'])."\">".showPrice($this_price)."</span></div>";
	}
	?>
	<input type="hidden" name="addaction" id="addaction" value="<?php print gotosecure()."".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout";?>" data-redirect="1" class="cartoption">
	<input type="hidden" name="from_photo" id="from_photo" value="<?php print $num_photos;?>" class="cartoption">
	<input type="hidden" name="did" id="did" value="<?php print MD5($date['date_id']);?>" class="cartoption">
	<input type="hidden" name="prod_qty" id="prod_qty" class="cartoption" value="1">
<div id="addtocartloading"><img src="<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif"></div>
<div id="addtocart" onClick="sendtocart('cartoption'); return false;" style=" <?php if($has_subs == true) { ?>display: none; <?php } ?> float: left; cursor: pointer; " ><?php print _purchase_access_;?></div>
<?php if($has_subs == true) { ?><div id="addtocartdisabled" class="tip" title="<?php print _select_from_options_above_;?>"><?php print _purchase_access_;?></div><?php } ?>

<div class="clear"></div>
<?php if($from_photo == true) { ?>
<div class="pc"><a href="" onclick="closestoreitem(); return false;"><?php print _cancel_;?></a></div>
<?php } ?>
<?php if($date['date_credit'] > 0) { ?>
<div class="pc"><?php print _includes_."  ".showPrice($date['date_credit'])." "._purchase_includes_credit_;?></div>
<?php } ?>
<div>&nbsp;</div>
<?php // } // END NOT MY REGISTRY ?>
</form>
<?php } ?>


</div>
<div class="clear"></div>
<div>&nbsp;</div>
</div>
<?php } // end product option to cart ?>

