<?php 
$path = "../../";
require "../w-header.php"; 
?>
<script>
tempfolder = "<?php print $setup['temp_url_folder'];?>";
cursign = '<?php print $store['currency_sign'];?>'; 
dec = '<?php print $store['price_decimals'];?>'; 
pformat = '<?php print $store['price_format'];?>'; 
function priceFormat(price) { 
	price = parseFloat(price).toFixed(2);
	fprice  = pformat.replace("[CURRENCY_SIGN]", cursign);
	fprice  = fprice.replace("[PRICE]", price);
	return fprice;
}
</script>

<?php 
include $setup['path']."/sy-inc/room-view/room-view.php";


require "../w-footer.php"; ?>