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
require $setup['path']."/".$setup['inc_folder']."/store/store_photo_buy_functions.php";
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

if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

?>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_photos_buy_js.js?<?php print MD5($site_setup['sytist_version']); ?>"></script>

<div id="photobuycontainer">
<?php 
$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'].""); 
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$total = shoppingCartTotal($mssess);
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}

$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$date['date_photo_price_list']."' ");
if($sub['sub_price_list'] > 0) { 
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");
}

if($_REQUEST['view'] == "favorites") { 
	$and_buy_all = "AND group_no_favs='0' ";
	if(isset($_SESSION['pid'])) { 
		$fav = doSQL("ms_favs", "*", "WHERE fav_pic='".$pic['pic_id']."' AND MD5(fav_person)='".$_SESSION['pid']."' ");
		$_REQUEST['sub_id'] = $fav['fav_sub_id'];
	}
	$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id='".$pic['pic_id']."' AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ";
	$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id LEFT JOIN ms_sub_galleries ON  ms_favs.fav_sub_id=ms_sub_galleries.sub_id LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic";
	$pics_orderby = "pic_org";

	$favpl = doSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where GROUP BY ms_calendar.date_photo_price_list  ");
	if(!empty($favpl['fav_id'])) { 
		if($pic['bp_pl'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['bp_pl']."' ");
		} elseif($favpl['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['sub_price_list']."' ");
		} else { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$favpl['date_photo_price_list']."' ");
		}
	}
}

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
lppw = 800;

withphoto = '<?php print $_REQUEST['withphoto'];?>';
$(".tab").click(function() { 
	$(".tab").removeClass("tabon");
	$(".packageprodgroups").hide();
	$(".group-"+$(this).attr("gid")+withphoto).show();
	$("#vinfo").attr("package-id", $(this).attr("gid"));

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
	console.log(fields);
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
			if($("#vinfo").attr("package_from_thumb") !== "1") { 
				packagenexttophoto($("#slideshow").attr("curphoto"));
			}
			if($("#vinfo").attr("prodplace") == "0" && $("#vinfo").attr("view_package_only") == "0") { 
				 showPackage();
			}
			if($("body").width() <= 800 || $("#vinfo").attr("package_from_thumb") == "1") { 
				packageopen();
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
<?php 
if($_REQUEST['view'] == "favorites") { 
	$list['list_products_placement'] = 1;
}
if(($_REQUEST['withphoto']=="1")&&($list['list_id'] > 0)==true) { ?>
	<?php if(($_REQUEST['istablet'] !== "1") && ($list['list_products_placement'] !== "1") == true) { ?>
<div>&nbsp;</div>
<div class="pc backtoproducts"><span class="checkout addproducttocart center" onclick='productsnexttophoto($("#slideshow").attr("curphoto")); return false;'><span class="the-icons icon-left-open"></span><?php print _back_to_products_;?></span></div>
<?php } ?>
<?php
	if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_product_select_photos='1' AND cart_order<='0' " ) > 0) { ?>
	<?php
		$prods = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_product_select_photos='1' AND cart_order<='0' ORDER BY cart_id DESC");
		while($prod = mysqli_fetch_array($prods)) { 
			$gt++;
			?> 
		<div class="pc"><h3><a href="" onclick='storeproductnexttophoto("<?php print $pic['pic_key'];?>","<?php print $prod['cart_id'];?>"); return false;' class="icon-picture the-icons"><?php print $prod['cart_product_name'];?></a></h3></div>
		<?php } ?>
	<?php } ?>

<div>&nbsp;</div>
<?php } ?>
<div id="printdata"></div>
<?php 
if($_REQUEST['package_id'] <= 0) { 
	$thispackage = 1;
} else { 
	$carts = whileSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_no_select!='1'  AND cart_package_buy_all<='0'  AND cart_order<='0'  ORDER BY cart_id DESC" );
	if(mysqli_num_rows($carts) > 1) {
		while($cart = mysqli_fetch_array($carts)) { 
			$gp++;
			if($_REQUEST['package_id'] == $cart['cart_id']) { 
				$thispackage = $gp;
			}
		}
	} else { 
		$thispackage = 1;
	}
}

	$carts = whileSQL("ms_cart LEFT JOIN ms_photo_products_connect ON ms_cart.cart_package=ms_photo_products_connect.pc_package", "*", "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_order<='0' AND (pc_list='".$list['list_id']."' OR cart_print_credit!='' OR cart_pre_reg>'0' OR cart_package_include>'0' OR cart_no_delete>'0' ) AND cart_package_buy_all<='0' AND cart_package_no_select!='1'  GROUP BY cart_id ORDER BY cart_id DESC" );
	if(mysqli_num_rows($carts) <=0) { ?>
	<div class="pc center"><?php print _photo_not_available_for_collection_;?></div>
	<?php } 
		if(mysqli_num_rows($carts) > 1) { ?>
			<div id="grouptabs">
			<?php 
			while($cart= mysqli_fetch_array($carts)) {
				$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
				$gt++;?>
			<div class="tab <?php if($gt == $thispackage) { print "tabon"; } ?>" gid="<?php print $cart['cart_id'];?>">
			<?php if($cart['cart_bonus_coupon'] > 0) { print _bonus_coupon_.": "; } ?><?php print $cart['cart_product_name'];?>
			</div>
			<?php } ?>
			<div class="clear"></div>
			</div>
		<?php } ?>




		<?php 
		
		// AND cart_package_buy_all<='0' deleted in where condition 
	$carts = whileSQL("ms_cart LEFT JOIN ms_photo_products_connect ON ms_cart.cart_package=ms_photo_products_connect.pc_package", "*", "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_order<='0' AND  (pc_list='".$list['list_id']."' OR cart_print_credit!='' OR cart_pre_reg>'0' OR cart_package_include>'0' OR cart_no_delete>'0')  AND cart_package_no_select!='1'  GROUP BY cart_id ORDER BY cart_id DESC" );
			while($cart= mysqli_fetch_array($carts)) {
		$stop_select = false;
		$selected  = 0;

				$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
			$g++;
			?>
		<div <?php if($g!==$thispackage) { ?>style="display: none;"<?php } ?> class="packageprodgroups group-<?php print $cart['cart_id'];?><?php print $_REQUEST['withphoto'];?>">
		<?php
			$packageroute =  explode('>', $cart['cart_product_name']);
			$packagetitle = $packageroute[1];
		 ?>
		<div class="pc"><h3><?php print $packagetitle;?></h3></div>
		<?php if($pack['package_limit'] > 0) { ?>
		<div class="pc"><?php print _poses_selected_;?> <?php 
		$totalsels = whileSQL("ms_cart", "*", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  GROUP BY cart_pic_id"); 
		$totalselected = mysqli_num_rows($totalsels);

		$sels = whileSQL("ms_cart", "*", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id!='0' AND cart_pic_id!='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
		$selected = mysqli_num_rows($sels);?>
		<?php print $totalselected;?> / <?php print $pack['package_limit'];?>
			</div>
		<?php if(($selected >= $pack['package_limit'])&&($pack['package_limit'] > 0)==true){ 
				$stop_select = true;
			}
			?>
		<?php } ?>


		<?php // AVAILABLE PHOTOS ?>
		<div>

		<?php
		$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  GROUP BY cart_photo_prod ORDER BY cart_id ASC" );
//		print "<h3>rows: ".mysqli_num_rows($pcarts)." cart id: ".$cart['cart_id']."</h3>";
		if(mysqli_num_rows($pcarts) <=0) { ?>
		<div class="pc"><h3><?php if($cart['cart_bonus_coupon'] <= 0) {  print _package_complete_; } ?> <a href="<?php print $setup['temp_url_folder'];?>/index.php?view=cart" onclick="viewcart(); return false;"><?php print _view_cart_;?></a></h3> </div>
		<?php if($_REQUEST['withphoto']!=="1") { ?>
		<div class="pc"><h3><a href="" onclick="closeaddtopackage(); return false;"><?php print _close_package_window_;?></a></h3> </div>
		<?php } ?>
		<?php } else {  ?>
			<?php if(($stop_select == true)&&($pack['package_limit'] > 0)==true) { ?>

			<div class="error"><?php print _max_poses_error_message_;?></div>
			<div>&nbsp;</div>
			<?php } else { ?>
		<?php
			$pcartsone = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'  AND cart_order<='0'  GROUP BY cart_photo_prod ORDER BY cart_id ASC" );
			if(mysqli_num_rows($pcartsone) == 1) { 
				$one_instructions = 1;
			} else { 
				$one_instructions = 0;
			}
			if($one_instructions == "1") { 
				while($pcartone = mysqli_fetch_array($pcartsone)) { 
					$opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$pcartone['cart_photo_prod']."' ORDER BY opt_order ASC ");
					if(mysqli_num_rows($opts) > 0) { 
						$one_instructions = 0;
					}
				}
			}

		if(($pack['package_select_only'] == "1") || ($one_instructions == 1)==true) { ?>
			<div class="pc"><?php if($cart['cart_bonus_coupon'] <= 0) { print _package_one_instructions_; } ?></div>
		<?php } else { ?>
			<div class="pc"><?php print _package_instructions_;?></div>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		<?php 
		if($pack['package_select_only'] == "1") { 
			$opcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  ORDER BY cart_id ASC LIMIT 1" );
			while($opcart= mysqli_fetch_array($opcarts)) {
				showPackageProduct($prod,$list,$opcart,"1");
			}
		} else {
			$pcartsone = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'  AND cart_order<='0'  GROUP BY cart_photo_prod ORDER BY cart_id ASC" );
			if(mysqli_num_rows($pcartsone) == 1) { 

				$one = 1;
			} else { 
				$one = 0;
			}
			while($pcart= mysqli_fetch_array($pcarts)) {
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$pcart['cart_photo_prod']."' ");
				if($prod['pp_id'] > 0) { 
					if($prod['pp_free'] <=0) { 
						showPackageProduct($prod,$list,$pcart,$one);
					}
				}
			}
		}
		?>
		</div>
		<?php // SELECTED PHOTOS ?>


<?php // EXTRA PHOTOS 
			// Checking ....
			$show_extra = false;
			$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' AND con_extra_price>'0' ORDER BY con_order ASC ");
			while($prod = mysqli_fetch_array($prods)) { 
				if($prod['con_extra_price'] > 0) { 
					if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND  cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."' AND cart_pic_id!='0' ") >= $prod['con_qty']) { 
						$show_extra = true;
					}
				}
			}

			if($show_extra == true) { 
			?>
			<div>&nbsp;</div>
			<div class="pc"><h3><?php print _package_extra_photos_title_;?></h3><?php print _package_extra_photos_;?></div>
			<?php 
			}
			
			$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' AND con_extra_price>'0' ORDER BY con_order ASC ");
			while($prod = mysqli_fetch_array($prods)) { 
				if($prod['con_extra_price'] > 0) { 
					$prod['pp_collapse_options'] = 0;

					if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND  cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."' AND cart_pic_id!='0' ") >= $prod['con_qty']) { 
						showProduct($prod,$list,$cart);
					}
				}
			}
			?>


		<div>

		<?php
		$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  AND cart_order<='0'  ORDER BY cart_id ASC" );
		if(mysqli_num_rows($pcarts) <=0) { ?>
		<?php } else { ?>
		<div>&nbsp;</div>
		<div class="pc"><h3><?php print _selected_photos_;?></h3></div>
		<?php } ?>
		<?php 
		while($pcart= mysqli_fetch_array($pcarts)) {
			if($pcart['cart_photo_prod'] == "999999") { 
				showPackageProduct(null,$list,$pcart,'0');
			} else { 
				$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$pcart['cart_photo_prod']."' ");
				if($prod['pp_id'] > 0) { 
					if($prod['pp_free'] <=0) { 
						showPackageProduct($prod,$list,$pcart,'0');
					}
				}
			}
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

<?php function showPackageProduct($prod,$list,$pcart,$one) { 
	global  $pic,$date,$setup,$pack,$cart,$stop_select;
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
	if($pack['package_select_only'] !== "1") { 
		$opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ");
		if(mysqli_num_rows($opts) > 0) { 
			$has_options = 1;
		}
	}

	$prod['pp_collapse_options'] = 0;
	?>
	<div class="photoprod">


	<?php if(($one == "1" && $has_options <=0) && ($stop_select !== true) == true) { 
	
?>
	<div class="pc center">
	<div>
			<input type="hidden" name="cart_package_photo" id="cart_package_photo"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $cart['cart_id'];?>">
			<input type="hidden" name="cart_photo_prod" id="cart_photo_prod"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_photo_prod'];?>">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="addphototopackage">
			<div id="addonephoto" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>" class="large2" style="text-align: left;"><img class="packagepreviewphoto left mobilehide" style="display: inline; margin-right: 8px; width: 50px; height: 50px;" src="" align="absmiddle"> <span  id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;" class="the-icons  icon-spin5"></span><?php print _add_to_package_; ?> <?php print _to_;?> <?php print $pack['package_name'];?>

			<?php if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='".$pic['pic_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC" ) > 0) { print "<br>"._photo_selected_." "; } ?>
			<div class="clear"></div></div>
		</div>
		</div>
		<?php if($pack['package_select_only'] !== "1"){ ?>
	<div class="pc center collectioncount"><?php print countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."' AND cart_pic_id>'0'  AND cart_order<='0'  ORDER BY cart_id ASC") ; ?> <?php print _of_;?> 			<?php print countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC") ; ?> <?php print _selected_;?></div><?php } ?>
		<?php 
		if($pack['package_select_only'] == "1") { 
			print "<div class=\"pc center\">".countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ")." "._of_." ".countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' ")." "._selected_."</div>"; 
		}
	?>
	<?php } else { ?>


		<div class="underline" cw="<?php print $prod['pp_width'];?>" ch="<?php print $prod['pp_height'];?>">
		<div style=" float: left;">
<?php if($ppic['pic_id'] > 0) { ?>
	<?php 
	if($ppic['pic_thwidth'] > 0) { 
		$dsize[0] = $ppic['pic_th_width'];
		$dsize[1] = $ppic['pic_th_height'];
	} else { 
		$dsize = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$ppic['pic_folder']."/".$ppic['pic_th'].""); 
	}
	?>
	<?php 
		if(!empty($pcart['cart_photo_bg'])) { 
		$bg = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_photo_bg']."' ");
		?>
		<div style="background: url('<?php  print getimagefile($bg,'pic_th'); ?>'); background-size:cover;background-position:center center;width: 100%; max-width: <?php print $dsize[0];?>px; max-height: <?php print $dsize[1];?>px; margin: auto; padding: 0px;"><img src="<?php if($pcart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$ppic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$pcart['cart_color_id']; } else {  print getimagefile($ppic,'pic_th'); } ?>" style="width: 100%; height: auto; max-width: <?php print $dsize[0];?>px;"></div>
	<?php } else { ?>



	<div class="left" style="margin-right: 16px;"><img src="<?php print getimagefile($ppic,'pic_mini'); ?>" class="thumb packagemini" pic_pic="<?php print getimagefile($ppic,'pic_pic'); ?>" mpic_id="<?php print $ppic['pic_id'];?><?php print $cart['cart_id'];?>" hh="<?php print $dsize[1];?>" hw="<?php print $dsize[0];?>" ptitle="<?php print $ppic['pic_org'];?>"></div>
	<?php } ?>

<?php } ?>
			<div style="padding-right: 16px;" class="left">
				<?php if($pack['package_select_only'] !== "1"){ ?>
				<div><span class="collectionproduct"><?php print $prod['pp_name'];?></span></div>
				<div class="collectioncount"><?php print countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."' AND cart_pic_id>'0'  AND cart_order<='0'  ORDER BY cart_id ASC") ; ?> <?php print _of_;?> 	<?php print countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_photo_prod='".$prod['pp_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC") ; ?> <?php print _selected_;?></div>
				<?php } ?>
				<div class="collectionphotoselected"><?php if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod='
	".$prod['pp_id']."' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='".$pic['pic_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC" ) > 0) { print _photo_selected_." "; } ?>
				</div>


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
		<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC "); ?>




		<?php if($ppic['pic_id'] <= 0) { ?>
		<?php 
			$avail = countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  AND cart_photo_prod='".$pcart['cart_photo_prod']."' " );
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
			<?php if($stop_select !== true) {
			
			?>
			<input type="hidden" name="cart_package_photo" id="cart_package_photo"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $cart['cart_id'];?>">
			<input type="hidden" name="cart_photo_prod" id="cart_photo_prod"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_photo_prod'];?>">
			<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
			<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
			<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
			<input type="hidden" name="prod_id" id="prod_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
			<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
			<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
			<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="addphototopackage">

			<span id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;"  class="the-icons icon-spin5"></span>
			<?php if((mysqli_num_rows($opts)> 0)&&($prod['pp_collapse_options'] == "1")==true) { ?>
			<a href="" onclick="openpackageoptions('<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $con_id;?>" class="icon-menu the-icons" title="<?php print _add_to_package_; ?>"></a>
			<?php } else { ?>
			<a href="" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>" class="the-icons icon-check" title="<?php print _add_to_package_; ?>"></a>
			<?php } ?>
	
			<?php } ?>
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

		<?php if($ppic['pic_id'] <= 0) { ?>

			<div class="options <?php if((mysqli_num_rows($opts)> 0)&&($prod['pp_collapse_options'] == "1") && ($one !== 1)==true) { ?>hide<?php } ?> options-<?php print $pcart['cart_id'];?>">
			<?php $opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ");
			while($opt = mysqli_fetch_array($opts))  { ?>
				<div  style="margin-bottom: 16px;"><?php productOptions($opt,'prod-'.$pcart['cart_id'].'',$prod); ?></div>
			<?php }  ?>
			<div class="clear"></div>
			<?php if((mysqli_num_rows($opts)> 0)&&($prod['pp_collapse_options'] == "1")==true) { ?>
			<div class="pc center"><a href="" onclick="addphototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>" class="the-icons icon-check" title="<?php print _add_to_package_; ?>"><?php print _add_to_package_; ?></a></div>
		<?php } ?>
		</div>


		<?php } ?>

		</div>
		<?php } ?>
		<div class="clear"></div>
		</div>
		<?php } ?>


<?php  mysqli_close($dbcon); ?>
