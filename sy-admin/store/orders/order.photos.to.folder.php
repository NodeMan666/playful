<?php

$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['photos_upload_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
} elseif($parent_permissions == "0777") {
	$perms = 0777;
} else {
	$perms = 0755;
}




if(!is_dir($setup['path']."/".$setup['photos_upload_folder']."/order-photos")) { 
	mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos", $perms);
	chmod("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos", $perms);
	$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/order-photos/index.php", "w");
	$info =  ""; 
	fputs($fp, "$info\n");
	fclose($fp);

}

mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."", $perms);
chmod("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."", $perms);
$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/index.php", "w");
$info =  ""; 
fputs($fp, "$info\n");
fclose($fp);

$hash_folder  = substr(md5(date('ymdHis').$site_setup['salt']),0,8);
mkdir("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder, $perms);
chmod("".$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder, $perms);
updateSQL("ms_orders", "order_photos_folder='".$order['order_id']."/".$hash_folder."' WHERE order_id='".$order['order_id']."' ");

/* 
$fp = fopen($setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/index.php", "w");
$info =  ""; 
fputs($fp, "$info\n");
fclose($fp);
*/

$zip = new ZipArchive;
$zip_file_name = "order-".$order['order_id'].".zip";

if ($zip->open($setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$zip_file_name, ZIPARCHIVE::CREATE)!==TRUE) {
	exit("cannot open <$zip_file_name>\n");
}



$pics = array();
$carts = whileSQL(cart_table, "*", "WHERE (cart_photo_prod!='0' OR cart_product_photo>'0') AND  cart_order='".$order['order_id']."' AND  cart_coupon='0'  ORDER BY cart_pic_org ASC " );
	while($cart= mysqli_fetch_array($carts)) {
	$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
	if($pic['pic_id'] > 0) { 
		if(!in_array($pic['pic_id'],$pics)) { 
			array_push($pics,$pic['pic_id']);
		}
	}
}
foreach($pics AS $pic) { 
	$p = doSQL("ms_photos", "*", "WHERE pic_id='".$pic."' ");
	$pic_file = $setup['path']."/".$setup['photos_upload_folder']."/".$p['pic_folder']."/".$p['pic_full'];
	if($p['pic_amazon'] == "1") { 
		if(ini_get('allow_url_fopen') <= 0) {
			copy_amazon_file("http://".$site_setup['amazon_endpoint']."/".$pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$p['pic_org']);
		} else {
			@copy("http://".$site_setup['amazon_endpoint']."/".$p['pic_bucket']."/".$p['pic_bucket_folder']."/".$p['pic_full'],$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$p['pic_org']);
		}
		$pic_file = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_org'];
	
	} else { 
		copy($pic_file,$setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$p['pic_org']);
	}
	if(file_exists($setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$p['pic_org'])) { 
		$zip->addFile($setup['path']."/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/".$p['pic_org'], $p['pic_org']);
	}
}
$zip->close();

// 	print "<pre>"; print_r($pics); print "</pre>"; 
print "<a href=\"/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/\">/".$setup['photos_upload_folder']."/order-photos/".$order['order_id']."/".$hash_folder."/</a>";
$_SESSION['sm'] = "Original files copied to folder";
header("location: index.php?do=orders&action=managephotos&order_id=".$order['order_id']."");
session_write_close();
exit();

?>