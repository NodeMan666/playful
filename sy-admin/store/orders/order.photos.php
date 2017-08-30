<?php
define("_view_crop_", "View Crop");
$no_trim = true;
$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ".$site_setup['date_time_format']." ')  AS order_date, date_format(DATE_ADD(order_shipped_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_shipped_date", "WHERE order_id='".$_REQUEST['order_id']."' ");
if(empty($order['order_id'])) {
	die("Unable to find order information");
}
if($order['order_archive_table'] == "1") { 
	define(cart_table,"ms_cart_archive");
} else { 
	define(cart_table,"ms_cart");
}

if($_REQUEST['orderaction'] == "photostofolder") { 
	include "order.photos.to.folder.php";
}

?>


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
		$.get("/sy-inc/store/store_photo_crop.php?pid="+pic+"&photoprod="+photoprod+"&cart_id="+cart_id+"&rotate="+rotate+"&change="+change+"&disable="+disable, function(data) {
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
<div class="pc"><a href="index.php?do=orders">&larr; Orders</a></div>
<div class="pc newtitles"><span class="">Order # <?php print $order['order_id']; ?></span></div> 

<?php include "order.tabs.php"; ?>
<div id="roundedFormContain">
<div class="pc">

<?php $history = doSQL("ms_history", "*", ""); ?>

<div class="exp left"  style="margin-right: 48px;">
	<div class="pc"><span style="font-size: 21px;">Print Version</span></div>
	<div class="pc">

	<form method="get" name="printthis" action="store/orders/order.photos.print.php"target="_blank"  style="display: inline;">
	<input type="hidden" name="orderNum" value="<?php print $order['order_id'];?>">
	<input type="hidden" name="printview" value="1">
	<input type="checkbox" name="withthumbs" id="withthumbs" value="1"  <?php if($history['print_withthumbs'] == "1") { print "checked"; } ?>> <label for="withthumbs">Thumbnails</label><br> 
	<input type="checkbox" name="invoiceheader" id="invoiceheader" value="1"  <?php if($history['print_invoiceheader'] == "1") { print "checked"; } ?>> <label for="invoiceheader">Invoice Header</label><br>
	<input type="submit" name="submit" value="View" class="submitSmall" style="margin-top: 8px;">
	</form>
	</div>
</div>

<div class="exp left" style="margin-right: 24px;">
	<div class="pc"><span style="font-size: 21px;">Export File Names</span></div>
	<div class="pc">
	<form method="get" name="printthis" target="_blank" action="store/orders/order.export.photos.php" style="display: inline;">
	<input type="hidden" name="order_id" value="<?php print $order['order_id'];?>">
	<input type="radio" name="dowith" id="dowith1" value="view" <?php if($history['export_dowith'] == "view") { print "checked"; } ?>> <label for="dowith1">Print to screen</label> &nbsp; 
	<input type="radio" name="dowith" id="dowith2" value="" <?php if($history['export_dowith'] !== "view") { print "checked"; } ?>> <label for="dowith2">Save As File</label> &nbsp; &nbsp; 
	</div>
	<div class="pc">
	Separate with: <input type="text" size="2" class="center" name="sepwith" value="<?php print $history['export_sepwith'];?>">  &nbsp; &nbsp;
	<input type="checkbox" name="stripext" id="stripext" value="1"> <label for="stripext">Remove file extension</label>
	<input type="submit" name="submit" value="Export" class="submitSmall">
	</form>
	</div>
	<div class="pc small">You can enter LB or separate with a line break.</div>
</div>





<script>
function hidecopybutton() { 
	$("#copybutton").hide();
	$("#copyloading").show();
}
</script>

<div class="pc right">
<?php if(!empty($order['order_photos_folder'])) { ?>
<div class="buttons"><a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']."/order-photos/".$order['order_photos_folder'];?>/" target="_blank">Open Folder</a></div>
<div>&nbsp;</div>
<div><a href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']."/order-photos/".$order['order_photos_folder'];?>/order-<?php print $order['order_id'];?>.zip" target="_blank">Link to zip</a></div>
<?php } else { ?>
<div id="copyloading" class="hidden"><img src="graphics/loading1.gif"></div>
<div class="buttons" id="copybutton"><a href="index.php?do=orders&action=managephotos&order_id=<?php print $order['order_id'];?>&orderaction=photostofolder" onclick="hidecopybutton();">Copy original files to folder</a></div><div>&nbsp;</div><div class="moreinfo right" info-data="copyphotostofolder"><div class="info"></div></div>
<?php } ?>
</div>


</div>
<div class="clear"></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php include "order.photos.function.php"; ?>
</div>


<div class="clear"></div>
</div>



