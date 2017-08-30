<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>The Photo Products section is where you create and manage prints, downloads, colections, etc... you want to offer for sale.</h3></div>
		<div class="pc">In Sytist, you have a <a href="index.php?do=photoprods&view=base">Product Base</a> where you create any type of photo products  (prints, downloads) you want to offer for sale or in a <a href="index.php?do=photoprods&view=packages">collection</a> (packages). When you create a <a href="index.php?do=photoprods">Price List</a>, you select the products from your product base you want to offer with the option to override those default prices</a>.</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>

<?php
if($_REQUEST['view'] == "base") { 
	include "pp.products.php";
} elseif($_REQUEST['view'] == "list") { 
	include "pp.list.php";
} elseif(!empty($_REQUEST['package_id'])) {  
	$pack = doSQL("ms_packages LEFT JOIN ms_photo_products ON ms_packages.package_buy_all_product=ms_photo_products.pp_id", "*", "WHERE package_id='".$_REQUEST['package_id']."' ");
	if($pack['package_buy_all'] =="1") { 
		include "pp.buyall.php";
	} else { 
		include "pp.package.php";
	}
} elseif($_REQUEST['view'] == "packages") { 
	include "pp.packages.php";

} elseif($_REQUEST['view'] == "roomview") { 
	include $setup['path']."/".$setup['manage_folder']."/room-view/room.index.php";

} elseif($_REQUEST['view'] == "filters") { 
	include "pp.color.options.php";
} elseif($_REQUEST['view'] == "printcredits") { 
	include "pp.print.credits.php";
} elseif($_REQUEST['view'] == "buyalls") { 
	include "pp.buyalls.php";
} else { 
	include "pp.lists.php";
}
?>