<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}
?>
<div id="photobuycontainer">
<?php 
$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$total = shoppingCartTotal($mssess);
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if($person['p_price_list'] > 0) { 
		$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$person['p_price_list']."' ");
	}
}
?>
<style>
.nophotopackage { width: 100%; } 
.packageproducts { width: 65%; float: right; } 
 input,  select { padding: 2px !important; } 
</style>
<div id="package-photo-preview">
	<div style="padding: 8px; position: relative; display: block;">
	<div id="package-photo-preview-photo" ></div>
	<div id="package-photo-preview-title" class="pc center"></div>
	</div>
</div>

<script>
function packagephotopreview(theclass){
	$(theclass).hoverIntent(
		function() {
		if($(this).attr("mpic_id")) { 
			var this_pic = $(this).attr("mpic_id");
			var this_src = $(this).attr("pic_pic");
			var th_src = $(this).attr("src");
			zindex = parseInt($(this).css('z-index'))-1;
			$("#package-photo-preview").css('z-index', zindex);
			$("#package-photo-preview").stop(true,true).fadeIn(200);
			$("#loaded").remove();
			$("#package-photo-preview-photo").html('');
			$("#package-photo-preview-photo").css({"background-image":"url('"+th_src+"')", "background-size":"100%","width":""+$(this).attr("hw")+"px", "height": ""+$(this).attr("hh")+"px"}); 
			$("#package-photo-preview-title").html("<h3>"+$(this).attr("ptitle")+"</h3>");
			tposition = $("#ssheader").height();
			lposition =  Math.abs($(this).offset().left) -  Math.abs($("#package-photo-preview").width() + 8);
			if(lposition < 0) { 
				lposition = Math.abs($(this).offset().left) +  Math.abs($(this).attr('width')) + 8;
			}

			$("#package-photo-preview").stop(true,true).animate(
				{opacity: 1,left:lposition, top:tposition }, 
				{ duration: 0,	complete: function() {
					$("#package-photo-preview").removeClass('photo-previewloading');
					$("#package-photo-preview-photo").html('<img src="'+this_src+'" id="loaded-'+this_pic+'" style="display: none;">');
					$("#loaded-"+this_pic).load(function() {
						$("#loaded-"+this_pic).stop(true,true).fadeIn(0);
						$("#package-photo-preview-photo").css({"background-image":"none"}); 
					 });
				}
		  });
		}
	},
	function() {
		$("#package-photo-preview").stop(true,true).fadeOut(0);
		$("#loaded").stop(true,true).fadeOut(100);
		$("#package-photo-preview").clearQueue();
		$("#package-photo-preview").removeClass('photo-previewloading');
		}
	);
}

$(document).ready(function(){
		 packagephotopreview(".packagemini");
});

if($("body").width() <= 800) { 
	$("#photobuyview").css({"display":"none"});
	$(".packageproducts").removeClass("packageproducts");
}
</script>
<?php if($_REQUEST['withphoto']!=="1") { ?>
<div class="left" style="width: 35%;" id="photobuyview">
<div style="position: relative;">

<div id="thumbpreview" style="display: none; border: solid 1px #242424; margin: auto; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px; background: url('/<?php print "sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_pic")."|".MD5($date['date_cat'])."|".$_REQUEST['color_id']."";?>') center center no-repeat;"  dw="<?php print $size[0];?>" dh="<?php print $size[1];?>"></div>
<div id="cropmessage" style="position: absolute; width: 100%; top: 0; text-align: center; margin-top: 20px; display: none; color: #FFFFFF; text-shadow:1px 1px 1px #000; font-size: 15px; ">
<div><?php print _center_crop_preview_;?></div>
<div id="cropdems"></div>
</div>
<div>&nbsp;</div>
<div id="cropcartmessage" class="pc center" style="display: none;"><?php print _crop_add_to_cart_;?></div>
</div></div>
<?php } ?>


<div <?php if($_REQUEST['withphoto']!=="1") { ?>class="packageproducts"<?php } ?> id="photobuyproducts">
	<div style="padding-left: 16px;">
<script>
priceformat = "<?php print $store['price_format'];?>";
currency_sign = "<?php print $store['currency_sign'];?>";
decimals = (<?php print $store['price_decimals'];?>);

$(document).ready(function(){
	$(".underline").hover(
	  function () {
		$(this).find('.minipreview').css('background-image','url('+$("#photo-"+$("#slideshow").attr("curphoto")).attr("mini")+')');
	  },
	  function () {
		$(this).find('.minipreview').css('background-image','none');
	  }
	);
});
withphoto = '<?php print $_REQUEST['withphoto'];?>';
$(".tab").click(function() { 
	$(".tab").removeClass("tabon");
	$(".prodgroups").hide();
	$(".group-"+$(this).attr("gid")+withphoto).show();
	$("#vinfo").attr("product-photo-id", $(this).attr("gid"));

	$(this).addClass("tabon");
});

function cropthumbpreview(cw,ch) { 
	orgWidth = Math.abs($("#thumbpreview").attr("dw"));
	orgHeight = Math.abs($("#thumbpreview").attr("dh"));
	cw = Math.abs(cw);
	ch = Math.abs(ch);
	if(cw > ch) {
		cp = cw / ch;
	} else {
		cp = ch / cw;
	}


	if(orgWidth > orgHeight) {
		imp = orgWidth / orgHeight;

		if(imp > cp) {
			height = orgHeight;
			width = Math.round(orgHeight  * cp);
		} else{
			width = orgWidth;
			height = Math.round(orgWidth / cp);
		}
	} else {
		imp = orgHeight / orgWidth;
		if(imp > cp) {
			height =  Math.round(orgWidth * cp);
			width =orgWidth;
		} else {
			height = orgHeight;
			width = Math.round(orgHeight / cp);
		}
	}
//	$("#log").html("imp: "+imp+" cp: "+cp+" width: "+width+" height: "+height+" orgw: "+orgWidth+" orgh: "+orgHeight);
//	alert("cp: "+cp+" "+cw+" X "+ch+" width: "+width+" X height"+height);

		
	$("#thumbpreview").css({"width":width+"px", "height":height+"px"});
}

function cropthumbpreviewclose() { 
	orgWidth = $("#thumbpreview").attr("dw");
	orgHeight = $("#thumbpreview").attr("dh");
	$("#thumbpreview").css({"width":orgWidth+"px", "height":orgHeight+"px"});

}

$('.prodoption').change(function() {
	price = 0;
	addprice = 0;
	$(".prod-"+$(this).attr("prodid")).each(function(){
		if($(this).hasClass('prodoption')) { 

			if($(this).hasClass('inputdropdown')) { 
				var addprice = Math.abs($('option:selected', this).attr("price"));
			}

			if($(this).hasClass('inputradio')) { 
				var addprice = Math.abs($('input:radio[name='+$(this).attr("name")+']:checked').attr("price"));
			}
			if($(this).hasClass('inputcheckbox')) { 
				if($(this).attr("checked")) { 
					var addprice = Math.abs($(this).attr("price"));
				} else { 
					var addprice = 0;
				}
			}
			if($(this).hasClass('inputtext')) { 
				if($(this).val()!=="") { 
					var addprice = Math.abs($(this).attr("price"));
					} else { 
					var addprice = 0;
				}

			}
			price = price + addprice;
		}
	});

	var orgprice = Math.abs($("#price-"+$(this).attr("prodid")).attr("orgprice"));
	var newprice = price + orgprice;


	var checkdecimals = newprice.toString();
	var splitprice = checkdecimals.split('.');
	if(!splitprice[1]) { 
	//	alert(found[0].add_price+" - "+newprice+" - "+splitprice[1]);
		var newprice = newprice.toFixed(decimals);
	} else { 
		var newprice = newprice.toFixed(2);
	}
	newformat = priceformat.replace("[PRICE]", newprice); // value = 9:61
	newformat = newformat.replace("[CURRENCY_SIGN]", currency_sign); // value = 9:61
	
//	alert(price+" + "+orgprice+" = "+newprice+" = "+newformat);

	$("#price-"+$(this).attr("prodid")).html(newformat);
});



function addphototopackage(classname,id) { 
	var fields = {};
	var stop = false;
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
	//		alert($this.attr('name')+" = "+$this.val());
		}
		fields['color_id'] = $("#filter").attr("color_id");
		fields['cart_photo_bg'] = $("#gs-bgimage-id").val();

		if($this.hasClass("required")) { 
			if($this.val() == "") { 
				alert("Please select: "+$this.attr("fieldname"));
				stop = true;
			}
		}


	});
	if(stop == false) { 
		$("#addcart-"+id).hide();
		$("#addcartloading-"+id).show();

		$.post('<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
			storeproductnexttophoto('',$("#cart_product_photo").val(),$("#pid").val(),$("#did").val());
			if($("#vinfo").attr("prodplace") == "0" && $("#vinfo").attr("view_package_only") == "0") { 
				showPackage();
			}
			 $("#submitinfo").html(data);
			updateCartMenu();
			setTimeout(function(){
			// $("#printdata").html(data);

			$("#addcart-"+id).show();
			$("#addcartloading-"+id).hide();
			},500)
		 } );
	}
	return false;
	

}


</script>
	<div>&nbsp;</div>	
	<?php if($_REQUEST['istablet'] !== "1") { ?>
<div class="pc"><h3><a href="" onclick='productsnexttophoto($("#slideshow").attr("curphoto")); return false;' class="the-icons icon-left-open"><?php print _back_to_products_;?></a></h3></div>
<?php } ?>
<?php 
	$has_package = countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_no_select!='1'  AND cart_order<='0'  ORDER BY cart_pic_org  ASC" );	
		 if(($has_package > 0) ==true) { ?>
		<div class="pc"><h3><a href="" onclick='packagenexttophoto("<?php print $pic['pic_key'];?>"); return false;' class="icon-picture the-icons"><?php print _view_my_collection_;?></a></h3></div>
	<?php } ?>
<div>&nbsp;</div>

<div id="printdata"></div>
<?php 
if($_REQUEST['cart_id'] <= 0) { 
	$thisproduct = 1;
} else { 
	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_product_select_photos='1'  AND cart_order<='0'  ORDER BY cart_id DESC" );
	if(mysqli_num_rows($carts) > 1) {
		while($cart = mysqli_fetch_array($carts)) { 
			$gp++;
			if($_REQUEST['cart_id'] == $cart['cart_id']) { 
				$thisproduct = $gp;
			}
		}
	} else { 
		$thisproduct = 1;
	}
}


	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_product_select_photos='1'  AND cart_order<='0'  ORDER BY cart_id DESC" );
	if(mysqli_num_rows($carts) <=0) { ?>
	<div class="pc center">Sorry, this photo is not available for your collection.</div>
	<?php } 

		if(mysqli_num_rows($carts) > 1) { ?>
			<div id="grouptabs">
			<?php 
			while($cart= mysqli_fetch_array($carts)) {
				$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_store_product']."' ");
				$gt++;?>
			<!-- <div class="tab <?php if($gt == $thisproduct) { print "tabon"; } ?>" gid="<?php print $cart['cart_id'];?>"> -->
			<?php print $pdate['date_title'];?>
			</div>
			<?php } ?>
			<div class="clear"></div>
			</div>
		<?php } ?>




		<?php 
		$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_product_select_photos='1'  AND cart_order<='0'  ORDER BY cart_id DESC" );
		while($cart= mysqli_fetch_array($carts)) {
			$pdate = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_store_product']."' ");
			$g++;
			?>
		<div id="" <?php if($g!==$thisproduct) { ?>style="display: none;"<?php } ?> class="prodgroups group-<?php print $cart['cart_id'];?><?php print $_REQUEST['withphoto'];?>">
		<div class="pc"><h2><?php print $pdate['date_title'];?></h2></div>		

		<?php // AVAILABLE PHOTOS ?>
		<div>

		<?php
		$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_product_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  GROUP BY cart_photo_prod ORDER BY cart_id ASC" );
	
		if(mysqli_num_rows($pcarts) <=0) { ?>
		<div class="pc"><h3><?php print _product_complete_;?></h3> </div>
		<div class="pc"><h3><a href="<?php print $setup['temp_url_folder'];?>/index.php?view=cart" onclick="viewcart(); return false;"><?php print _view_cart_;?></a></h3> </div>
		<?php if($_REQUEST['withphoto']!=="1") { ?>
		<div class="pc"><h3><a href="" onclick="closeaddtopackage(); return false;"><?php print _close_package_window_;?></a></h3> </div>
		<?php } ?>
		<?php } else {  ?>
			<div class="pc"><?php print _add_photo_product_instructions_;?></div>

		<?php } ?>
		<?php 
			$opcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  ORDER BY cart_id ASC LIMIT 1" );
			while($opcart= mysqli_fetch_array($opcarts)) {
				showProduct($prod,$list,$opcart,"1");
			}
		?>
		</div>
		<div>&nbsp;</div>
		<?php // SELECTED PHOTOS ?>

		<div >

		<?php
		$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()."  AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  AND cart_order<='0'  ORDER BY cart_id ASC" );
		if(mysqli_num_rows($pcarts) <=0) { ?>
		<?php } else { ?>
		<div class="pc"><h3><?php print _selected_photos_;?></h3></div>
		<?php } ?>
		<?php 
		while($pcart= mysqli_fetch_array($pcarts)) {
			showProduct(null,$list,$pcart,'0');
		}
		?>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>
		</div>

		<?php } ?>
</div>
</div>
<div class="clear"></div>
</div>
	<script>
		function photobyclass(pic_key) { 
			pid = $("."+pic_key).attr("id");
			p = pid.split("-");
			if($("#slideshow").attr("curphoto") !== p[1]) { 
				navSlides(p[1],"1");
			}
	}
	</script>

<?php function showProduct($prod,$list,$pcart,$one) { 
	global  $pic,$setup,$date,$cart,$pdate;
	$ppic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
	if($pic['pic_id'] > 0) { 
		if($pic['pic_width'] < $pic['pic_height']) { 
			$percent = $pic['pic_width'] / $pic['pic_height'];
			$largest = $pic['pic_height'];
		} else { 
			$percent = $pic['pic_height'] / $pic['pic_width'];
			$largest = $pic['pic_width'];
		}
	}
	?>
	<div class="photoprod">


	<?php if($one == "1") { ?>
	<div class="pc center">
	<div>
			<input type="hidden" name="cart_product_photo" id="cart_product_photo"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $cart['cart_id'];?>">
			<input type="hidden" name="cart_photo_prod" id="cart_photo_prod"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_photo_prod'];?>">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="addphototopackage">
			<div id="addonephoto" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>" class="large2" style="text-align: left;"><img class="packagepreviewphoto left mobilehide" style="display: inline; margin-right: 8px; width: 50px; height: 50px;" src="" align="absmiddle"> <span  id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;" class="the-icons icon-spin5"></span><?php print _add_to_package_; ?>  <?php print $pdate['date_title'];?>

			<?php if(countIt("ms_cart",  "WHERE ".checkCartSession()."   AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id='".$pic['pic_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC" ) > 0) { print "<br>"._photo_selected_." "; } ?>
			<div class="clear"></div></div>
		</div>
		</div>
		<?php 

		countIt("ms_cart",  "WHERE ".checkCartSession()." AND  cart_product_photo='".$cart['cart_id']."'  AND cart_order<='0' " );


			print "<div class=\"pc center\">".countIt("ms_cart",  "WHERE ".checkCartSession()." AND  cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  AND cart_order<='0' " )." of ".countIt("ms_cart",  "WHERE ".checkCartSession()." AND  cart_product_photo='".$cart['cart_id']."'  AND cart_order<='0' " )." selected</div>"; 
	?>
	<?php } else { ?>


		<div class="underline" cw="<?php print $prod['pp_width'];?>" ch="<?php print $prod['pp_height'];?>">
		<div style=" float: left;">
<?php if($ppic['pic_id'] > 0) { ?>
	<?php 
		if(!empty($pcart['cart_photo_bg'])) { 
		$bg = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_photo_bg']."' ");
		?>
		<div style="background: url('<?php  print getimagefile($bg,'pic_th'); ?>'); background-size:cover;background-position:center center;width: 100%; max-width: <?php print $dsize[0];?>px; max-height: <?php print $dsize[1];?>px; margin: auto; padding: 0px;"><img src="<?php if($pcart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$ppic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$pcart['cart_color_id']; } else {  print getimagefile($ppic,'pic_th'); } ?>" style="width: 100%; height: auto; max-width: <?php print $dsize[0];?>px;"></div>
	<?php } else { ?>
	<?php 
	// $size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$ppic['pic_folder']."/".$ppic['pic_mini'].""); 
	$dsize = getimagefiledems($ppic,'pic_pic');
	?>
	<div class="left" style="margin-right: 16px;"><img src="<?php print getimagefile($ppic,'pic_mini'); ?>" class="thumb packagemini" pic_pic="<?php print getimagefile($ppic,'pic_pic'); ?>" mpic_id="<?php print $pic['pic_id'];?><?php print $cart['cart_id'];?>" hh="<?php print $dsize[1];?>" hw="<?php print $dsize[0];?>" ptitle="<?php print $ppic['pic_org'];?>" ></div>
	<?php } ?>
<?php } else { ?>
	<div class="left minipreview" style="margin-right: 16px; width: 50px; height: 50px; text-align: center; ">&nbsp;</div>

<?php } ?>
			<div style="padding-right: 16px;" class="left">
				<div><h3><?php print $prod['pp_name'];?></h3></div>
				<?php if($ppic['pic_id'] > 0) { ?>
				<div style="padding: 4px 0 ;"><?php print $ppic['pic_org'];?></div>

					<?php $cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$pcart['cart_id']."' ");
					while($co = mysqli_fetch_array($cos)) { ?>
					<div class="options">
					<?php print $co['co_opt_name'].": ".$co['co_select_name']; if($co['co_price'] > 0) { print " +".showPrice($co['co_price']); $this_price = $this_price + $co['co_price']; } ?>
					</div>
				<?php } ?>
			<?php if($pcart['cart_color_id'] > 0) { ?>
				<div class="options">
				<?php print $pcart['cart_color_name'];?>
				</div>
			<?php } ?>

			<?php } ?>



			</div>
		</div>

		<div style=" float: right; text-align:right;">
		<?php if($ppic['pic_id'] <= 0) { ?>

	


			<div>
			<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ");
			if(mysqli_num_rows($opts)<=0) {} 

			while($opt = mysqli_fetch_array($opts))  { ?>
				<div  style="margin-bottom: 16px;"><?php productOptions($opt,'prod-'.$pcart['cart_id'].'',$prod); ?></div>
			<?php }  ?>
			<div class="clear"></div>
			</div>
		<?php } ?>





		<?php if($ppic['pic_id'] <= 0) { ?>
		<?php 
			$avail = countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  AND cart_photo_prod='".$pcart['cart_photo_prod']."' " );
			if($avail == 1) { ?>
			<input type="hidden" name="qty" id="qty"  class="prod-<?php print $pcart['cart_id'];?>" value="1">

			<?php } else { ?>
			<select name="qty" id="qty"  class="prod-<?php print $pcart['cart_id'];?>">
			<?php
				$q =1;
			while($q <= $avail) { ?>
			<option value="<?php print $q;?>"><?php print $q; ?></option>
			<?php 
				$q++;
			} ?>
			</select>
			<?php } ?>

			<input type="hidden" name="cart_product_photo" id="cart_product_photo"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $cart['cart_id'];?>">
			<input type="hidden" name="cart_photo_prod" id="cart_photo_prod"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_photo_prod'];?>">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="addphototopackage">
			<span id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;"  class="the-icons icon-spin5"></span>
			<a href="" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>"><?php print _add_to_package_; ?></a>
		
		<?php } else  { ?>

			<div style="padding: 4px 0 ;">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="removephotofrompackage">
			<span id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;"  class="the-icons icon-spin5"></span>
			<a href="" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>" class="the-icons icon-cancel" title="<?php print _remove_photo_from_package_; ?>"></a>
			</div>

		<?php } ?>

		</div>

			<div class="clear"></div>
			<?php if(!empty($prod['pp_descr'])) { ?>
			<div class="sub"><?php print nl2br($prod['pp_descr']);?></div>

			<?php } ?>

		</div>
		<?php } ?>
		<div class="clear"></div>
		</div>
		<?php } ?>


<?php  mysqli_close($dbcon); ?>
