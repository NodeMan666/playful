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
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');
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

<script>
pic = '<?php print $_REQUEST['pid'];?>';
date_id = '<?php print $_REQUEST['date_id'];?>';
fav_pl = '';
function closephotoproduct(){ 
	$("#productsloading").show();
	curphoto = $("#slideshow").attr("curphoto");;
	if($("#vinfo").attr("prodplace") == "1") { 
		productsnexttophoto(curphoto);
	} else { 
		buyphoto('',pic,date_id,fav_pl);
	}
}

function showqtydiscount() { 
	$("#qtydiscountprices").slideToggle(200);
}

function cropphotobuy(pic,photoprod,con_id,rotate,change,disable) { 
//	$("#buybackground").fadeIn(50);
	loading();

		$.get(tempfolder+"/sy-inc/store/store_photo_crop_v2.php?pid="+pic+"&photoprod="+photoprod+"&con_id="+con_id+"&rotate="+rotate+"&change="+change+"&disable="+disable+"&color_id="+$("#filter").attr("color_id"), function(data) {
			$("#cropdiv").html(data);
			$(".cropphotoview").hide();
			$("#cropdiv").slideDown(200, function() { 
				loadingdone();
			});
		});
}

</script>
<div class="pc center backtoproductlist backtoproductlisttop"><a href="" class="center" onclick="closephotoproduct(); return false;"><span class="the-icons icon-left-open"></span><?php print _back_to_products_; ?></a></div>



<?php 
if(!is_numeric($_REQUEST['con_id'])) { die("that is not a number"); } 

$con = doSQL("ms_photo_products_connect LEFT JOIN ms_photo_products_groups ON ms_photo_products_connect.pc_group=ms_photo_products_groups.group_id", "*","WHERE pc_id='".$_REQUEST['con_id']."' ");
$pack = doSQL("ms_packages", "*", "WHERE package_id='".$con['pc_package']."' ");
$pic = doSQL("ms_photos","*","WHERE pic_key='".$_REQUEST['pid']."' ");
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$con['pc_list']."' ");
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
?>





<div class="center">
	<?php if(($prod['pp_width'] > 0) && ($prod['pp_type'] !== "download") == true) { ?>

	<div class="cropphotoview"><?php cropphotoviewv2($cart,$pic,$prod,"pic_th",'1',$_REQUEST['color_id']); ?></div>
	<div id="cropdiv" class="hide" style="margin: auto; position: relative;"></div>
	<?php if($prod['pp_no_crop'] !== "1") { ?><div class="pc center"><a href="" class="cropphotoview" onclick="cropphotobuy('<?php print $pic['pic_key'];?>','<?php print $prod['pp_id'];?>','<?php print $con['pc_id'];?>','0','0','0'); return false;"><span class="the-icons icon-crop"></span><?php print _adjust_crop_;?></a></div><?php } ?>





	<?php } else { ?>
	
	<?php 
		/*
			$size = getimagefiledems($pic,'pic_th');		
			if(!empty($cart['cart_thumb'])) { 
				$size = @GetImageSize("".$setup['path']."/".$cart['cart_thumb']); 
				?>
				<img src="<?php print $setup['temp_url_folder'];?>/<?php print $cart['cart_thumb'];?>" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
				<?php } else { ?>
			<img src="<?php if($cart['cart_color_id'] > 0) { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_th")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']; } else {  print getimagefile($pic,'pic_th'); } ?>" class="thumb" style="width: 100%; height: auto; max-width: <?php print $size[0];?>px;" <?php print $size[3];?>>
			<?php } ?>
	
<?php
*/
} ?>
</div>
<?php showPackagev2($pack,$list,$con); ?>
