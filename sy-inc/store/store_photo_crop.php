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
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
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
$pic = doSQL("ms_photos", "*", "WHERE  pic_key='".$_REQUEST['pid']."' ");
$size = getimagefiledems($pic,'pic_pic');
$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$_REQUEST['photoprod']."' ");
$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$_REQUEST['cart_id']."' ");
if(($cart['cart_crop_rotate'] > 0) &&($_REQUEST['change'] !== "1")==true){ 
	$_REQUEST['rotate'] = $cart['cart_crop_rotate'];
}
if($size[0] == $size[1]) { 
	$size[0] = $size[0]+1;
}
$width = $prod['pp_width'];
$height = $prod['pp_height'];

if($_REQUEST['rotate'] == "1") { 
	$dem = getCropDems($size[1],$size[0],$width,$height,.50);
} else { 
	$dem = getCropDems($size[0],$size[1],$width,$height,.50);
}

if((($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))&&($_REQUEST['change'] !== "1")==true) { 
	$x1 = $size[0] * ($cart['cart_crop_x1'] / 100);
	$y1 = $size[1] * ($cart['cart_crop_y1'] / 100);
	$x2 = $size[0] * ($cart['cart_crop_x2'] / 100);
	$y2 = $size[1] * ($cart['cart_crop_y2'] / 100);
} else { 
	$x1 = $dem['x1'];
	$y1 = $dem['y1'];
	$x2 = $dem['x2'];
	$y2 = $dem['y2'];
}
/*
print "<li>width: ".$dem['crop_width']."";
print "<li>height: ".$dem['crop_height']."";
print "<li>min width: ".$dem['min_width']."";
print "<li>min_height: ".$dem['min_height']."";
print "<li>x1: ".$dem['x1'];
print "<li>y1: ".$dem['y1'];
print "<li>x2: ".$dem['x2'];
print "<li>y2: ".$dem['y2'];
print "<li>ctop with: ".$dem['crop_width'];
print "<li>crop height: ".$dem['crop_height'];
*/
?>

<script src="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.css" type="text/css" />
<script>
  function showCoords(c) {
      // variables can be accessed here as
      // c.x, c.y, c.x2, c.y2, c.w, c.h
	$("#x1").val(c.x);
	$("#y1").val(c.y);
	$("#x2").val(c.x2);
	$("#y2").val(c.y2);
	$("#w").val(c.w);
	$("#h").val(c.h);

	$("#x1p").val(c.x / $("#pic_width").val() * 100);
	$("#y1p").val(c.y / $("#pic_height").val() * 100);
	$("#x2p").val(c.x2 / $("#pic_width").val() * 100);
	$("#y2p").val(c.y2 / $("#pic_height").val() * 100);

  };


    jQuery(function($) {
        jcrop_api = this;
        $('#croppingphoto').Jcrop({
            onSelect:    showCoords,
            bgColor:     'white',
            bgOpacity:   .2,
			<?php if($_REQUEST['disable'] == "1") { ?>
			disabled: true,
			<?php } ?>
			minSize: [<?php print $dem['min_width'];?>,<?php print $dem['min_height'];?>],
            setSelect:   [ <?php print $x1;?>, <?php print $y1;?>, <?php print $x2;?>, <?php print $y2;?> ],
            aspectRatio: <?php print $dem['crop_width'];?> / <?php print $dem['crop_height'];?>
        });    
	});

	function rotatecrop() {
        jcrop_api = this;

      jcrop_api.setOptions({ aspectRatio: 10/3 });
      jcrop_api.focus();
	}
function submitcrop() { 
	loading();
	$.get("<?php tempFolder(); ?>/sy-inc/store/store_cart_actions.php?action=updatecrop&cart_id="+$("#cart_id").val()+"&x1="+$("#x1p").val()+"&y1="+$("#y1p").val()+"&x2="+$("#x2p").val()+"&y2="+$("#y2p").val()+"&rotate="+$("#rotate").val()+"&pic_key="+$("#pic_key").val(), function(data) {
		$("#ct-"+$("#cart_id").val()).html(data);
		loadingdone();
		closecropphoto();

	});


}

	$('#disable').click(function(e) {
		jcrop_api.disable();

		$('#enable').show();
		$('.requiresjcrop').hide();
	});

</script>
<div class="center"  id="photocroparea">
<div style="position: relative;" class="center">
<div id="croppreview" style="margin: auto; width: <?php print $size[0];?>px; height: <?php print $size[1];?>px;" dw="<?php print $size[0];?>" dh="<?php print $size[1];?>">
<img id="croppingphoto" src="<?php if($cart['cart_color_id'] > 0) { ?><?php tempFolder(); ?><?php print "/sy-photo.php?thephoto=".$pic['pic_key']."|".MD5("pic_pic")."|".MD5($date['date_cat'])."|".$cart['cart_color_id']."";?><?php } else { print getimagefile($pic,'pic_pic'); } ?>"></div>

</div></div>
<?php if($_REQUEST['rotate'] == "1") { 
	$rotate = 0;
} else { 
	$rotate = 1;
}
?>
<div id="croptabs">
<span onclick="closecropphoto(); return false;" class="tab"><?php print _crop_cancel_;?></span> 
<?php if($_REQUEST['disable'] !== "1") { ?>

<span onclick="cropphoto('<?php print $pic['pic_key'];?>','<?php print $prod['pp_id'];?>','<?php print $_REQUEST['cart_id'];?>','<?php print $rotate;?>','1'); return false;" class="tab"><?php print _crop_rotate_;?></span> 
<span onclick="submitcrop(); return false;" class="tab"><?php print _crop_save_;?></span>
<?php } ?>
</div>

<input type="hidden" name="cart_id" id="cart_id" value="<?php print $_REQUEST['cart_id'];?>">
<div>
<input type="hidden" name="x1" id="x1" value="" size="3"> 
<input type="hidden" name="y1" id="y1" value="" size="3"> 
<input type="hidden" name="x2" id="x2" value="" size="3"> 
<input type="hidden" name="y2" id="y2" value="" size="3"> 
<input type="hidden" name="w" id="w" value="" size="3"> 
<input type="hidden" name="h" id="h" value="" size="3"> 
<input type="hidden" name="x1p" id="x1p" value="" size="3"> 
<input type="hidden" name="y1p" id="y1p" value="" size="3"> 
<input type="hidden" name="x2p" id="x2p" value="" size="3"> 
<input type="hidden" name="y2p" id="y2p" value="" size="3"> 
<input type="hidden" name="rotate" id="rotate" value="<?php print $_REQUEST['rotate'];?>" size="3"> 
<input type="hidden" name="pic_width" id="pic_width" value="<?php print $size[0];?>" size="3"> 
<input type="hidden" name="pic_height" id="pic_height" value="<?php print $size[1];?>" size="3"> 
<input type="hidden" name="pic_key" id="pic_key" value="<?php print $pic['pic_key'];?>" size="3"> 
 </div>
<?php  mysqli_close($dbcon); ?>
