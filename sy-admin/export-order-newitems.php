<?php  
if(empty($path)) { 
	$path = "../";
}
include $path."sy-config.php";
if($setup['demo_mode'] == true) { 
	die("Disabled for the demo");
}
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
header('Content-Type: text/html; charset=utf-8');
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
adminsessionCheck();
date_default_timezone_set(''.$site_setup['time_zone'].'');

$this_dom = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
$info = explode('//', $_SERVER['HTTP_REFERER']); 
$d1 = $info[1]; 
$info2 = explode('/', $d1); 
$d2 = $info2[0]; 
$from_dom = str_replace("www.", "", strtolower($d2));

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");


/* 
if($_REQUEST['dowith'] == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}
*/

function orderfield($order,$name) { 
	print $order[$name];
}
// $_REQUEST['dowith'] = "view";

############ NOTES #################### 
##	option to discard download items
##	Single pose packages 
##	Link to full size file 
##	Additional fields from price list option 
##	B&W Option
##	green screen background 
##	Do a preview then download option 
##	 Option to group prducts by filename 
##	ORders: 100148|100150|100153
##	Cropping .... X Y Zoom 
#######################################

############# Start getting items ########################
if($_REQUEST['dowith'] == "view") { 
	?>
<style>
table td { padding: 8px; } 
body { font-family: Arial; } 
</style>
<table cellspacing="1">
<?php 
}
$group_products_by_filename = true;


#### NOT GROUPING BY FILE NAME ############ 
/*
$cols = array(
	array("order_id","ORDER #","fieldname"),
	array("order_first_name","FIRST NAME","fieldname"),
	array("order_last_name","LAST NAME","fieldname"),
	array("order_extra_val_1","STUDENT","fieldname"),
	array("order_extra_val_2","SCHOOL","fieldname"),
	array("file_name","FILENAME","getfilename"),
	array("productname","PRODUCT NAME","getproduct"),
	array("packagename","PACKAGE","getpackagename"),
	array("productid","PRODUCT ID","getproductid"),
	array("options","OPTIONS","getoptions")
);

*/
#### GROUPING BY FILE NAME ##############
/*
$cols = array(
	array("order_id","ORDER #","fieldname"),
	array("order_first_name","FIRST NAME","fieldname"),
	array("order_last_name","LAST NAME","fieldname"),
	array("order_extra_val_1","STUDENT","fieldname"),
	array("order_extra_val_2","SCHOOL","fieldname"),
	array("file_name","FILENAME","getfilename"),
	array("products","PRODUCTS","getproducts"),
	//array("packagename","PACKAGE","getpackagename"),
	//array("productid","PRODUCT ID","getproductid"),
	array("options","OPTIONS","getoptions"),
	array("crop","CROP","getcrop")
);

*/

$cols = array();
$cols_header = array();

if(!empty($_REQUEST['orderNum'])) { 
	$orderNums = explode("|",$_REQUEST['orderNum']);
	foreach($orderNums AS $orderNum) { 
		if(!empty($orderNum)) { 

			$order = doSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_id='".$orderNum."' ");
			if($order['order_archive_table'] == "1") { 
				$cart_table = "ms_cart_archive";
			} else { 
				$cart_table = "ms_cart";
			}
			
				if($site_setup['export_ignore_downloads'] == "1") { $and_sql = " AND cart_download<='0' "; } 

				$carts = whileSQL($cart_table, "*", "WHERE cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0'  GROUP BY cart_pic_id ORDER BY cart_pic_org ASC " );
					while($cart= mysqli_fetch_array($carts)) {
						$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
						if($cart['cart_package_photo'] > 0) { 
							$pcart = doSQL("ms_cart", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
							$package = doSQL("ms_packages", "*", "WHERE package_id='".$pcart['cart_package']."' ");
						} else { 
							unset($package);
						}



						$pcarts = whileSQL($cart_table." LEFT JOIN ms_photo_products ON ".$cart_table.".cart_photo_prod=ms_photo_products.pp_id", "*, SUM(cart_qty) AS total", "WHERE cart_pic_id='".$cart['cart_pic_id']."' AND cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0'   GROUP BY cart_photo_prod " );

						while($pcart = mysqli_fetch_array($pcarts)) { 
							
							if($pcart['pp_name'] != "Digital Download") {
								$cols_header[] = $pcart['pp_name'];
								}
							$p = str_replace("[QTY]",($pcart['total'] * 1),$col[4]);
							$p = str_replace("[PRODUCT]",$pcart['pp_name'],$p);

							if($package['package_select_only'] == "1") { 
								$p = "selected";
							}
						}

						$pic['pic_org'] = str_replace(".jpg","",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".JPG","",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".Jpg","",$pic['pic_org']);

						$pic['pic_org'] = str_replace(".jpeg","",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".gif","",$pic['pic_org']);
						$pic['pic_org'] = str_replace(".png","",$pic['pic_org']);

						$cols[] = $pic['pic_org'];
							

					// showOrderPhoto($cart,"0","",$order);
				}

		}
	}
}
echo '<tr>';
foreach($cols_header as $header) {
	echo '<td>'.$header.'</td>';
}
echo '</tr>';
foreach($cols  as $col) {
	echo '<tr>
	<td>'.$col.'</td>
	</tr>';
}
########### End getting items ################### 
if($_REQUEST['dowith'] == "view") { 
	?>
	</table>
	<?php 
}

if($_REQUEST['dowith'] == "view") {

//	print $exp;
} else {


	$filename = date('Ymdhmi')."-".MD5(date('Ymdhmi')).".csv";

	$fp = fopen("".$setup['path']."/sy-tmp/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/sy-tmp/$filename";

	if($date['date_id'] > 0) { 
		$downloadfile=$site_setup['website_title']."-".$date['date_title']."-Export-".date('Y-m-d').".csv";
	} else { 
		$downloadfile=$site_setup['website_title']."-People-Export-".date('Y-m-d').".csv";
	}
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download"); 
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"$downloadfile\"");
	header("Content-transfer-encoding: binary\n"); 
	header("Content-length: " . filesize($filecontent) . "\n");

	@readfile($filecontent);

	unlink($filecontent);
}
/*
header("Content-disposition: attachment; filename=$downloadfile");
header("Content-type: text/css; charset=UTF-8"); 
header("Content-Length: ".strlen($filecontent));
header("Cache-Control: cache, must-revalidate");    
header("Pragma: public");
header("Expires: 0");
*/
?>
