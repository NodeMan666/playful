	<script>
		function releasephoto(id,order_id) { 
		$.get("admin.actions.php?action=releasephoto&cart_id="+id+"&order_id="+order_id, function(data) {
			$("#pending-"+id).slideUp(100, function() { 
				$("#releasephoto-"+id).fadeOut(50);
			});
		});
	}
		function releaseallphotos(id) { 
		$("#releasall").slideUp(100);
		$.get("admin.actions.php?action=releaseallphotos&order_id="+id+"", function(data) {
			$(".pending").slideUp(100, function() { 
				$(".releasephoto").fadeOut(50);
			});
		});
	}

	function highlightcart() { 

		$(".checkbox").change(
		  function () {
			if($(this).attr("checked")) { 
				$("#cart-"+$(this).val()).addClass("cartselected");
			} else { 
				$("#cart-"+$(this).val()).removeClass("cartselected");

			}
		  });
		}
	$(document).ready(function(){
		highlightcart();
	});

	</script>
	<style>
	.cartselected { background: #e4e4e4; border-bottom: solid 2px #999999; color: #545454;} 
	.cartselected h3 { color: #545454; } 
	</style>

<div class="pc"><?php print $order['order_first_name'];?> <?php print $order['order_last_name'];?> <a href="" onclick="pagewindowedit('w-send-email2.php?email_to=<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; } ?>&email_to_first_name=<?php print addslashes($order['order_first_name']);?>&email_to_last_name=<?php print addslashes($order['order_last_name']);?>&order_id=<?php print $order['order_id'];?>&email_id_name=downloadsready&noclose=1&nofonts=1&nojs=1'); return false;" title="Send email" class="tip the-icons icon-mail">Send email to notify photos are ready to download (<?php if($setup['demo_mode'] == true) { print "demo@mode"; } else { print $order['order_email']; }  ?>).</a>  
</div>
<?php if(countIt(cart_table, "WHERE cart_disable_download='1' AND cart_order='".$order['order_id']."' ") > 0) { ?>
<div class="pc" id="releasall"><a href="" onclick="releaseallphotos('<?php print $order['order_id'];?>'); return false;">Allow all photos to be downloaded now</a></div>
<?php } ?>
<?php
$carts = whileSQL(cart_table, "*", "WHERE (cart_photo_prod!='0' OR cart_product_photo!='0') AND  cart_order='".$order['order_id']."' AND cart_pic_id>'0'  AND cart_coupon='0'  ORDER BY cart_pic_org ASC " );
	while($cart= mysqli_fetch_array($carts)) {
	$tracknum++;
	 showOrderPhoto($cart,"0",$order);
}
?>

<?php
function showOrderPhoto($cart,$package_photo,$order) {
global $setup,$site_setup,$zip_total,$noactions;
$action = "viewcart";
$price = $cart['cart_price'];
$this_price = $cart['cart_price'];
if(!empty($cart['cart_sub_id'])) {
	$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
	$this_price = $this_price + $sub['sub_add_price'];
}
$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");
if($cart['cart_package_photo'] > 0) { 
	$pcart = doSQL(cart_table, "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
}

?>

<div class="underline" id="cart-<?php print MD5($cart['cart_id']);?>">
<?php if(($_REQUEST['printview'] == "1")&&($_REQUEST['withthumbs'] <= 0)==true) { 
	$no_thumb = true;
}
?>
<?php if($no_thumb !== true) { ?>
	<div style="width: 20%;" class="left center">

		<?php if($pic['pic_id'] > 0) { ?>
			<?php if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { ?>
				<div id="ct-<?php print $cart['cart_id'];?>">
				<?php cropphotoview($cart,$pic,$prod,"pic_th",'1'); ?>
				<div style="background: #ffff00;">Custom Crop</div>
				</div>
					<div class="pc center"><a href="" onclick="openFrame('w-photos-upload.php?replace_photo=<?php print $pic['pic_id'];?>&order_id=<?php print $order['order_id'];?>&cart_id=<?php print $cart['cart_id'];?>'); return false;" class="tip" title="Replace Photo"><?php print ai_replace; ?> Replace Photo</a></div>
				<?php } else { 
					$size = getimagefiledems($pic,'pic_th');
					if(!empty($cart['cart_photo_bg'])) { 
						$bg = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_photo_bg']."' ");
					}

				if(!empty($cart['cart_thumb'])) { 
					$size = @GetImageSize("".$setup['path']."/".$cart['cart_thumb']); 
					?>
					<img src="<?php print $setup['temp_url_folder'];?>/<?php print $cart['cart_thumb'];?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
					<?php } else { ?>

					<div>
					<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
					</div>
					<?php } ?>
					<?php if($cart['cart_disable_download'] == "1") { ?>
					<div class="highlight pending" id="pending-<?php print $cart['cart_id'];?>" >Download pending your action</div>
					<?php } ?>
					<div class="pc center"><a href="" onclick="openFrame('w-photos-upload.php?replace_photo=<?php print $pic['pic_id'];?>&order_id=<?php print $order['order_id'];?>&cart_id=<?php print $cart['cart_id'];?>'); return false;" class="tip" title="Replace Photo"><?php print ai_replace; ?> Replace Photo</a>
					<?php if($cart['cart_disable_download'] == "1") { ?>
					<br><a id="releasephoto-<?php print $cart['cart_id'];?>" href="" onclick="releasephoto('<?php print $cart['cart_id'];?>','<?php print $order['order_id'];?>'); return false;" class="releasephoto">Allow Download Now</a>
					<?php } ?>
					</div>
					<?php if($prod['pp_width'] > 0) { ?>
					<div>No custom crop</div>
					<?php } ?>
				<?php } ?>
			

			<?php } else { ?>
			<div class="pc center">File not found</div>

					<div class="pc center"><a href="" onclick="openFrame('w-photos-upload.php?replace_photo=<?php print $pic['pic_id'];?>&order_id=<?php print $order['order_id'];?>&cart_id=<?php print $cart['cart_id'];?>'); return false;" class="tip" title="Replace Photo"><?php print ai_replace; ?> Replace Photo</a></div>

			<?php } ?>
		</div>
		<?php } ?>
		<div class="left" style="width: <?php if($no_thumb == true) { print "60%"; } else { print "40%"; } ?>;">

		<div style="padding: 0 0 0 8px ;">
			<div class="pc"><h3><?php print $cart['cart_pic_org'];?> </h3></div>
			<?php if(!empty($bg['pic_id'])) { ?><div class="pc"><b>Background: <?php print $bg['pic_org'];?></b></div><?php } ?>

				<div class="pc">In 
				<?php 
				if(!empty($date['cat_under_ids'])) { 
				$scats = explode(",",$date['cat_under_ids']);
				foreach($scats AS $scat) { 
					$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
					print "<a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> > ";
				}
			}
				print "<a href=\"index.php?do=news&date_cat=".$date['cat_id']."\">".$date['cat_name']."</a> > ";
				print "<a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."\">".$date['date_title']."</a>";
				if(!empty($cart['cart_sub_gal_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							print " > <a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$upsub['sub_id']."\">".$upsub['sub_name']."</a>  ";
						}
					}
					print " > <a href=\"index.php?do=news&action=managePhotos&date_id=".$date['date_id']."&sub_id=".$sub['sub_id']."\">".$sub['sub_name']."</a>";
				}
				?>
				</div>
				<?php if(!empty($cart['cart_notes'])) { ?>
				<div class="pc highlight"><span><?php print nl2br($cart['cart_notes']);?></span></div>
				<?php } ?>
					<?php if($cart['cart_room_view'] > 0) { 
					$wset = doSQL("ms_wall_language", "_wd_wall_designer_tab_","");
					$rv  = doSQL("ms_wall_saves", "*", "WHERE wall_id='".$cart['cart_room_view']."' ");
					?>
					<div class="pc"><a href="<?php print $setup['temp_url_folder'].$setup['content_folder']."".$date['cat_folder']."/".$date['date_link'];?>/?view=room&rw=<?php print $rv['wall_link'];?>" target="_blank"><b>Wall Designer</b></a><br>View the wall designer for orientation and any cropping.</div>
					<?php } ?>

			<?php // prodDownloads($cart); ?>
	
		</div>
		<div class="clear"></div>
	</div>


		<div style="width: 25%" class="left">

			<?php 
				if($cart['cart_frame_size'] > 0) { 
					$wset = doSQL("ms_wall_language", "*","");
					$wall_settings = doSQL("ms_wall_settings","*","");
						$frame = doSQL("ms_frame_sizes LEFT JOIN ms_frame_styles ON ms_frame_sizes.frame_style=ms_frame_styles.style_id", "*", "WHERE frame_id='".$cart['cart_frame_size']."' ");
						$color = doSQL("ms_frame_images", "*", "WHERE img_id='".$cart['cart_frame_image']."' ");
						?>
						<div class="pc"><h3><?php print ($frame['frame_width'] * 1)." x ".($frame['frame_height'] * 1)." ".$frame['style_name'];?></h3></div>
						<?php if($cart['cart_mat_size'] > 0) { ?><div class="pc"><?php print ($frame['frame_mat_print_width'] * 1)." x ".($frame['frame_mat_print_height'] * 1);?> print</div><?php } ?>

						<div>
						<?php
							if(empty($color['img_corners'])) { 
								$corners = $frame['style_frame_corners'];
							} else { 
								$corners = $color['img_corners'];
							}

							$bgsizes = explode(",",$corners);
						?>
						<div class="pc"><span  style="height: 20px; width: 20px; background-image: url('<?php print $setup['temp_url_folder'].$color['img_small'];?>'); background-size: <?php print (100 / $bgsizes[0]) * 100;?>%; display: inline-block">&nbsp;</span> <?php print $color['img_color'];?></div>
						<?php if($cart['cart_mat_size'] > 0) { ?>
						<div class="pc"> <?php print $wset['_wd_matting_'];?>  <?php print $cart['cart_mat_size'] * 1;?><?php print $wall_settings['size_symbol'];?> <span  id="matcolor-<?php print $style['style_id'];?>-<?php print $mat['mat_id'];?>" class="matcolorselections" style="width: 20px; height: 20px; display: inline-block; border: solid 1px #d4d4d4; background: #<?php print $cart['cart_mat_color'];?>;">&nbsp;</span> 
						<?php 
						$matcolor = doSQL("ms_frame_mat_colors", "*", "WHERE color_color='".$cart['cart_mat_color']."' ");
						print $matcolor['color_name'];
						?>

						</div>
						<?php } ?>

					</div>
					<?php } else {	?>



			<div class="pc"><h3><?php print $cart['cart_product_name'];?> <?php if(($prod['pp_width'] > 0)&&(($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) {
			 ?>**<?php } ?></h3></div>
			 <?php } ?>
			<?php if(!empty($cart['cart_sku'])) { ?><div class="pc"><?php print $cart['cart_sku'];?></div><?php } ?>
			<?php if($cart['cart_package_photo'] > 0) { ?><div class="pc"><?php print $pack['package_name'];?></div><?php } ?>
				<?php $cos = whileSQL(cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ORDER BY co_id ASC ");
				while($co = mysqli_fetch_array($cos)) {
				?>
				<div class="pc"><?php print $co['co_opt_name'];?> <?php print _selected_;?></div>

				<?php
			}

			?>

			<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0'  ORDER BY co_id ASC ");
			while($co = mysqli_fetch_array($cos)) { ?>
			<div class="pc"><?php print $co['co_opt_name'].": ".$co['co_select_name']; if($co['co_price'] > 0) { print " +".showPrice($co['co_price']); $this_price = $this_price + $co['co_price']; } ?></div>
			<?php } ?>

		<?php if($cart['cart_color_id'] > 0) { ?>
			<div class="pc">
			<?php print $cart['cart_color_name'];?>
			</div>
		<?php } ?>


	</div>
		<div style="width: 10%" class="left textright"><h3><?php print ($cart['cart_qty'] + 0);?></h3></div>
		<div style="width: 5%" class="left center"><input type="checkbox" id="check-<?php print $cart['cart_id'];?>" value="<?php print MD5($cart['cart_id']);?>" class="checkbox"></div>
		<div class="clear"></div>
	</div>

<?php } ?>