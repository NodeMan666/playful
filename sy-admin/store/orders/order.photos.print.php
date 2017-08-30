<?php
include "../../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
ob_start(); 
require "../../../".$setup['inc_folder']."/functions.php"; 
require "../../admin.functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*","");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
// Add a check to the registration to see if store is valid 
$sytist_store = true;
adminsessionCheck();
$no_trim = true;
define("ai_email","");
if($_REQUEST['prices'] == "1") { 
	$show_prices = true;
}
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS order_date", "WHERE order_id='".$_REQUEST['orderNum']."' ");
if(empty($order['order_id'])) {
	die("Unable to find order information");
}
	if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
	$cart_table = "ms_cart_archive";
	} else { 
	define(cart_table,"ms_cart");
	$cart_table = "ms_cart";
	}

updateSQL("ms_history", "print_withthumbs='".$_REQUEST['withthumbs']."' , print_invoiceheader='".$_REQUEST['invoiceheader']."' ");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" media="screen,print" href="../css/print.css" >
<script src="../../js/jquery-1.8.3.min.js"></script>
<style type="text/css" media="screen,print">
body,p,div {
	color: #000000;
	font: 12px Arial;
}
body {
	background: #f4f4f4;
	padding: 0;
	margin: 0;
}
.pad, .row { padding: 4px 0px; } 
.center { text-align: center; } 
.left { float: left; } 
.right { float: right; } 
.clear {  clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px; } 
.textright { text-align: right; } 
.field100 { width: 97%;}
.padtopbottom { padding: 2px 0; } 
.reg,.normal { font-size: 13px; } 
p { padding: 8px 0 } 
.green { color: #74a474; } 
.muted { color: #949494; } 
.labels { font-size: 17px; font-weight: bold; padding: 4px 0;} 
#packingslip { 
margin: auto;
margin-top: 12px auto;
border: solid 1px #e4e4e4;
background: #FFFFFF;
padding: 24px;
width: 800px;
}
.highlight {
	padding: 2px;
	background-color: #FDFFC4;
	border: solid 1px #B6BA4C;
}
.billing { float: left; width: 50%; }
.shipping { float: right; width: 50%; }

.headerleft { float: left; width: 50%; }
.headerright{ float: right; width: 50%; text-align: right; }
.underline { border-bottom: solid 1px #e4e4e4; padding: 2px 2px 8px 2px; } 

#products { background-color: #dddddd; margin-top: 20px; } 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold; } 
#products td { background: #FFFFFF; padding: 8px; }

#totals { float: right;  margin-top: 20px; } 
#totals td { padding: 5px; }

</style>

<style media="print">
body {
	background-color: #ffffff;
}
#packingslip { 
	border: none; 
}
#products { background-color: #dddddd; margin-top: 20px !important;} 
#products .top { background: #EEEEEE; padding: 8px; font-weight: bold !important; } 
#products td { background: #FFFFFF; padding: 8px !important;}

#totals { float: right;  margin-top: 20px !important; } 
#totals td { padding: 5px !important;}
.underline { border-bottom: solid 1px #e4e4e4; padding: 2px 2px 8px 2px; } 
.checkbox { display: none;} 
</style>
<div id="photocrop" style=" display: none; background: #FFFFFF; width: 900px; left: 50%; margin-left: -450px; border: solid 1px #949494>; position: absolute; z-index: 200; box-shadow: 0 0 24px rgba(0,0,0,.8);">
<div id="photocropinner" style=" padding: 16px; "></div>
</div>

<script>
function cropphoto(pic,photoprod,cart_id,rotate,change,disable) { 
//	$("#buybackground").fadeIn(50);
	$('html').unbind('click');
	//loading();
	if(!pic) { 
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	}

	$("#photocrop").css({"top":$(window).scrollTop()+50+"px"});
		$.get("<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_photo_crop.php?pid="+pic+"&photoprod="+photoprod+"&cart_id="+cart_id+"&rotate="+rotate+"&change="+change+"&disable="+disable, function(data) {
			$("#photocropinner").html(data);
			$("#photocrop").slideDown(200, function() { 
				$("#closephotocrop").show();
	//			sizeBuyPhoto();
	//			loadingdone();
			});
		});
}

function closecropphoto() { 
	$('html').unbind('click');
	$("#photocrop").slideUp(200, function() { 
		$("#photocropinner").html("");
	});
}
</script>


</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>

<div id="packingslip">
<div class="pad"><h1>Order #<?php print "".$order['order_id'].""; ?></h1></div>

<?php if($_REQUEST['invoiceheader'] == "1") { ?>
	<div id="header">
		<div class="headerleft">
		<?php print nl2br($store['packing_slip_top']); ?>
		</div>

		<div class="headerright">
			<div>Order #<?php print "".$order['order_id'].""; ?></div>
			<div><?php print "".$order['order_date'].""; ?></div>
		<?php if(!empty($order['order_shipping_option'])) { ?>		
			<div>Shipping - <?php print $order['order_shipping_option'];?></div>	
		<?php } ?>


		</div>
		<div class="clear"></div>
	</div>

<hr></h2>

<div id="addresses">
	<div class="billing">
	<div class="labels">Billing Address</div>
	<div>
<?php if((empty($order['order_first_name']))AND(empty($order['order_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print  "".$order['order_first_name']." ".$order['order_last_name'].""; ?><?php  } ?>
		</div>
<?php if(!empty($order['order_address'])) { ?>
		<div class="row">
			<?php print "".$order['order_address'].""; ?>
		</div>
<?php } ?>
<?php if(!empty($order['order_city'])) { ?>
		<div class="row">
			<?php print "".$order['order_city']." "; ?> 
			<?php if(!empty($order['order_state'])) { ?> <?php print "".$order['order_state'].""; ?>, <?php } ?>
			<?php if(!empty($order['order_zip'])) { ?><?php print "".$order['order_zip'].""; ?><?php } ?>
		</div>
<?php } ?>
<?php if(!empty($order['order_country'])) { ?>
		<div class="row">
			<?php print "".$order['order_country'].""; ?>
		</div>
<?php } ?>

		<?php if(!empty($order['order_email'])) { ?><?php print  "".$order['order_email'].""; ?><?php  } ?>
<?php if(!empty($order['order_phone'])) { ?>
		<div class="row">
			<div style="width:50%; float: left;">Phone </div><div style="width:50%; " class="right textright"><?php print "".$order['order_phone'].""; ?></div>
			<div class="cssClear"></div>
		</div>
<?php } ?>

</div>

	</div>
		<?php if(!empty($order['order_shipping_option'])) { ?>

	<div class="shipping">
	<div class="labels">Shipping Address</div>
	<div class="row"><?php if((empty($order['order_ship_first_name']))AND(empty($order['order_ship_last_name']))==true) { ?><i>no name provided</i><?php } else { ?><?php print  "".$order['order_ship_first_name']." ".$order['order_ship_last_name'].""; ?><?php  } ?>
			</div>
	<?php if(!empty($order['order_ship_address'])) { ?>
			<div class="row">
				<?php print "".$order['order_ship_address'].""; ?>
			</div>
	<?php } ?>
			<div class="row">
				<?php print "".$order['order_ship_city']." "; ?> 
				<?php if(!empty($order['order_ship_state'])) { ?> <?php print "".$order['order_ship_state'].""; ?>, <?php } ?>
				<?php if(!empty($order['order_ship_zip'])) { ?><?php print "".$order['order_ship_zip'].""; ?><?php } ?>
			</div>
	<?php if(!empty($order['order_ship_country'])) { ?>
			<div class="row">
				<?php print "".$order['order_ship_country'].""; ?>
			</div>
	<?php } ?>


	</div>
<?php } ?>
	<div class="clear"></div>
	<div>&nbsp;</div>
<?php } ?>

<?php 
	$carts = whileSQL("ms_cart", "*", "WHERE cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_pic_id>'0'  AND cart_coupon='0'  ORDER BY cart_pic_org ASC " );
	while($cart= mysqli_fetch_array($carts)) {
	 if((($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) {	
		 $crop_message = true;
	 }
}
if(($crop_message == true) && ($_REQUEST['withthumbs'] <=0)==true){ 
	?>
<div class="pad" style="text-align: right;">** Indicates a custom crop was selected for that photo.<br>Refer to the photos on the order on the website to view custom cropping.</div>
<div class="clear"></div>
<?php } ?>

<?php include "order.photos.function.php"; ?>
