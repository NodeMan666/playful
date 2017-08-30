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
date_default_timezone_set(''.$site_setup['time_zone'].'');
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
<script>

function addonephototopackage(classname,id) { 
	var fields = {};
	var stop = false;
	if(stop == false) { 
		$("#addcart-"+id).hide();
		$("#addcartloading-"+id).show();
		pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
		color_id=$("#filter").attr("color_id");
		date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
		sub_id = $("#vinfo").attr("sub_id");
		date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
		$.get('<?php print $setup['temp_url_folder'];?>/sy-inc/store/store_cart_actions.php?action=addphototopackage&prod_id='+$("#cart_id").val()+'&pid='+pic+'&color_id='+color_id+'&did='+date_id+'', function (data) { 
		showPackageOne();
		 $("#submitinfo").html(data);
		// alert(data);
		updateCartMenu();
		setTimeout(function(){

			$("#addcart-"+id).show();
			$("#addcartloading-"+id).hide();
			},500)
		 } );
	}
	return false;

}

</script>
<div class="center">
<?php 
	$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_package!='0' AND cart_package_no_select!='1'  AND cart_order<='0'  ORDER BY cart_date DESC" );
	while($cart= mysqli_fetch_array($carts)) {
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
		?>
		
		<?php 
		$g++;
		$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  ORDER BY cart_id ASC" );
		if(mysqli_num_rows($pcarts) <=0) { ?>
		<?php } ?>
		<?php 

		if(countIt("ms_cart",  "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='".$pic['pic_id']."'  AND cart_order<='0'  ORDER BY cart_id ASC" ) > 0) { 	print  _photo_selected_." "; } 


		$opcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."' AND cart_pic_id='0' AND cart_order<='0'  ORDER BY cart_id ASC LIMIT 1" );
		while($opcart= mysqli_fetch_array($opcarts)) {
			showProduct($prod,$list,$opcart,"1");
			print " to ";
		}
		
		print " ".$pack['package_name']." "; 
		print " <a href=\"\"  onclick=\"showPackage(); return false;\">".countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' AND cart_pic_id>'0' ")." "._of_." ".countIt("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' ")." "._selected_."</a> "; 

		?>

		<?php } ?>

<?php function showProduct($prod,$list,$pcart,$one) { 
	global  $pic,$date,$setup;
	$ppic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
	?>
	<input type="hidden" name="color_id" id="color_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['color_id'];?>">
	<input type="hidden" name="sub_id" id="sub_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $_REQUEST['sub_id'];?>">
	<input type="hidden" name="list_id" id="list_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $list['list_id'];?>">
	<input type="hidden" name="cart_id" id="cart_id"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pcart['cart_id'];?>">
	<input type="hidden" name="pid" id="pid"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $pic['pic_key'];?>">
	<input type="hidden" name="did" id="did"  class="prod-<?php print $pcart['cart_id'];?>" value="<?php print $date['date_id'];?>">
	<input type="hidden" name="action" id="action"  class="prod-<?php print $pcart['cart_id'];?>" value="addphototopackage">
	<img src="/sy-graphics/loading.gif" border="0" id="addcartloading-<?php print $pcart['cart_id'];?>" style="display: none;">
	  <a href=""  onclick="addonephototopackage('prod-<?php print $pcart['cart_id'];?>','<?php print $pcart['cart_id'];?>'); return false;" id="addcart-<?php print $pcart['cart_id'];?>"><?php print _add_to_package_; ?></a>
<?php } ?>
</div>

<?php  mysqli_close($dbcon); ?>