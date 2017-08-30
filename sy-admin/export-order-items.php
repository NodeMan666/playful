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
$fields = whileSQL("ms_order_export_items", "*", "WHERE status='1' ORDER BY display_order ASC ");
while($f = mysqli_fetch_array($fields)) { 
	array_push($cols,array($f['field_name'],$f['label'],$f['function'],$f['separate_products'],$f['product_format'],$f['strip_ext']));
}
if($_REQUEST['dowith'] == "view") { 
	print "<tr>";
}
foreach($cols AS $col) { 
	if($_REQUEST['dowith'] == "view") { 
		print "<td>".$col[1].",</td>";
	} else { 
		$exp .= $col[1].",";
	}
}
if($_REQUEST['dowith'] == "view") { 
	print "</tr>";
} else { 
	$exp .= "\r\n";
}
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
			/*
			$carts = whileSQL($cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND cart_package!='0'  ORDER BY cart_package_no_select DESC, cart_id ASC" );
			while($cart= mysqli_fetch_array($carts)) {
				$pack = doSQL("ms_packages", "*", "WHERE package_id='".$cart['cart_package']."' ");
				// showOrderPackage($pack,$cart);
				$pcarts = whileSQL($cart_table, "*", "WHERE  cart_order='".$order['order_id']."' AND cart_photo_prod!='0' AND cart_package_photo='".$cart['cart_id']."'    ORDER BY cart_pic_org ASC" );
					while($pcart= mysqli_fetch_array($pcarts)) {
					$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$pcart['cart_pic_id']."' ");
					if($pcart['cart_pic_id'] > 0) { 
						print "<tr>";
						foreach($cols AS $col) { 
							if($col[2] == "fieldname") { 
								print "<td>".$order[$col[0]]."</td>";
							}
							if($col[2] == "getproduct") { 
								print "<td>".$pack['package_name']."</td>";
							}
							if($col[2] == "getproductid") { 
								print "<td>".$pack['package_internal_name']."</td>";
							}

							if($col[2] == "getfilename") { 
								print "<td>".$pic['pic_org']."</td>";
							}
							if($col[2] == "getoptions") { 
								$c = 0;
								$cos = whileSQL($cart_table."  LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$pcart['cart_pic_id']."'  ORDER BY co_id ASC ");
								print "<td>";
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										print ", ";
									}
									print $co['co_opt_name']; 
									$c++;
								} 
								$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0' ORDER BY co_id ASC ");
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										print ", ";
									}

									print $co['co_opt_name'].": ".$co['co_select_name']; 
									print "</td>";
									$c++;
								}

								print "</td>";
							}
						}
						print "</tr>";
						// showOrderPhoto($pcart,"1",$cart,$order);
					}
				}

			}
			*/
			

			########### NOT GROUPING BY FILE NAME #######################
			/*
				$carts = whileSQL($cart_table, "*", "WHERE cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0'  ORDER BY cart_pic_org ASC " );
					while($cart= mysqli_fetch_array($carts)) {
						$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_pic_id']."' ");
						print "<tr>";

						foreach($cols AS $col) { 
							if($col[2] == "fieldname") { 
								print "<td>".$order[$col[0]]."</td>";
							}
							if($col[2] == "getproduct") { 
								print "<td>".$cart['cart_product_name']."</td>";
							}
							if($col[2] == "getproductid") { 
								print "<td>".$cart['cart_sku']."</td>";
							}
							if($col[2] == "getpackagename") { 
								if($cart['cart_package_photo'] > 0) { 
									$package = doSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
									print "<td>".$package['package_name']."</td>";
								} else { 
									print "<td>&nbsp;</td>";
								}
							}

							if($col[2] == "getfilename") { 
								print "<td>".$pic['pic_org']."</td>";
							}
							if($col[2] == "getoptions") { 
								$c = 0;
								print "<td>";

								$cos = whileSQL($cart_table."  LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ORDER BY co_id ASC ");
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										print ", ";
									}
									print $co['co_opt_name']; 
									$c++;
								} 


								$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0' ORDER BY co_id ASC ");
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										print ", ";
									}

									print $co['co_opt_name'].": ".$co['co_select_name']; 
									print "</td>";
									$c++;
								}
							}
						}

						print "</tr>";

					// showOrderPhoto($cart,"0","",$order);
				}
	*/

			########### GROUPING BY FILE NAME ######################
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
						if($_REQUEST['dowith'] == "view") { 
							print "</tr>";
						}

						foreach($cols AS $col) { 

							if($col[2] == "getproducts") { 
								$x = 0;
								$pcarts = whileSQL($cart_table." LEFT JOIN ms_photo_products ON ".$cart_table.".cart_photo_prod=ms_photo_products.pp_id", "*, SUM(cart_qty) AS total", "WHERE cart_pic_id='".$cart['cart_pic_id']."' AND cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0'   GROUP BY cart_photo_prod " );
								if($_REQUEST['dowith'] == "view") { 
									print "<td>";
								}

								while($pcart = mysqli_fetch_array($pcarts)) { 
									if($x > 0) { 
										if($_REQUEST['dowith'] == "view") { 
											print $col[3];
										} else { 
											$exp .= $col[3];
										}
									}
									$p = str_replace("[QTY]",($pcart['total'] * 1),$col[4]);
									$p = str_replace("[PRODUCT]",$pcart['pp_name'],$p);
									if($package['package_select_only'] == "1") { 
										$p = "selected";
									}
									if($_REQUEST['dowith'] == "view") { 
										print  $p;
									} else { 
										$exp .= $p;
									}
									$x++;
								}
								if($_REQUEST['dowith'] == "view") { 
									print ",</td>";
								} else { 
									$exp .= ",";
								}
							}

							if($col[2] == "getproductskus") { 
								$x = 0;
								$pcarts = whileSQL($cart_table." LEFT JOIN ms_photo_products ON ".$cart_table.".cart_photo_prod=ms_photo_products.pp_id", "*, SUM(cart_qty) AS total", "WHERE cart_pic_id='".$cart['cart_pic_id']."' AND cart_photo_prod!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' GROUP BY cart_photo_prod " );
								if($_REQUEST['dowith'] == "view") { 
									print "<td>";
								}
								while($pcart = mysqli_fetch_array($pcarts)) { 
									if($x > 0) { 
										if($_REQUEST['dowith'] == "view") {
											print $col[3];
										} else { 
											$exp .= $col[3];
										}
									}
									$p = str_replace("[QTY]",($pcart['total'] * 1),$col[4]);
									$p = str_replace("[PRODUCT]",$pcart['pp_internal_name'],$p);
									if($_REQUEST['dowith'] == "view") { 
										print  $p;
									} else { 
										$exp .= $p;
									}

									$x++;
								}
								if($_REQUEST['dowith'] == "view") { 
									print ",</td>";
								} else { 
									$exp .= ",";
								}
							}



							if($col[2] == "fieldname") { 
								if($_REQUEST['dowith'] == "view") { 
									print "<td>".$order[$col[0]].",</td>";
								} else { 
									$exp .= '"'.$order[$col[0]].'",';
								}
							}

							if($col[2] == "getgalleryname") { 
								$gal = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_pic_date_id']."' ");

								if($_REQUEST['dowith'] == "view") { 
									print "<td> ".$gal['date_title'].",</td>";
								} else { 
									$exp .= '"'.str_replace('"'," ",$gal['date_title']).'",';
								}
							}
							if($col[2] == "getproductnotes") { 
								if($_REQUEST['dowith'] == "view") { 
									print "<td> ".$cart['cart_notes'].",</td>";
								} else { 
									$exp .= '"'.str_replace('"'," ",$cart['cart_notes']).'",';
								}
							}
							if($col[2] == "getsubgalleryname") { 
								$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$cart['cart_sub_gal_id']."' ");

								if($_REQUEST['dowith'] == "view") { 
									print "<td> ".$sub['sub_name'].",</td>";
								} else { 
									$exp .= '"'.str_replace('"'," ",$sub['sub_name']).'",';
								}
							}


							if($col[2] == "gsbackground") { 
								$bg = doSQL("ms_photos", "*", "WHERE pic_id='".$cart['cart_photo_bg']."' ");

								if($_REQUEST['dowith'] == "view") { 
									print "<td> ".$bg['pic_org'].",</td>";
								} else { 
									$exp .= '"'.str_replace('"'," ",$bg['pic_org']).'",';
								}
							}

							if($col[2] == "getproduct") { 
								if($_REQUEST['dowith'] == "view") { 
									print "<td>".$cart['cart_product_name'].",</td>";
								} else { 
									$exp .= $cart['cart_product_name'].",";
								}
							}
							if($col[2] == "getproductid") { 
								if($_REQUEST['dowith'] == "view") { 
									print "<td>".$cart['cart_sku'].",</td>";
								} else { 
									$exp .= $cart['cart_sku'].",";
								}
							}
							if($col[2] == "getcrop") { 
								if($_REQUEST['dowith'] == "view") { 
									print "<td>";
								}
								if((($cart['cart_crop_x1'] > 0)||($cart['cart_crop_y1'] > 0)||($cart['cart_crop_x2'] > 0)||($cart['cart_crop_y2'] > 0))==true) { 
									if($_REQUEST['dowith'] == "view") { 
										print $cart['cart_crop_x1']."x ".$cart['cart_crop_y1']."y ".$cart['cart_crop_x2']." x2 ".$cart['cart_crop_y2']."y2";
									} else { 
										$exp .= $cart['cart_crop_x1']."x ".$cart['cart_crop_y1']."y ".$cart['cart_crop_x2']." x2 ".$cart['cart_crop_y2']."y2,";
									}
								} else { 
									if($_REQUEST['dowith'] == "view") { 
										print "&nbsp;";
									} else { 
										$exp .= ",";
									}
								}

								if($_REQUEST['dowith'] == "view") { 
									print ",</td>";
								} else { 
									$exp .= ",";
								}
							}
							if($col[2] == "getpackagename") { 
								if($cart['cart_package_photo'] > 0) { 
									$package = doSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE cart_id='".$cart['cart_package_photo']."' ");
									if($_REQUEST['dowith'] == "view") { 
										print "<td>".$package['package_name'].",</td>";
									} else { 
										$exp .= $package['package_name'].",";
									}
								} else { 
									if($_REQUEST['dowith'] == "view") { 
										print "<td>&nbsp;</td>";
									} else { 
										$exp .= ",";
									}
								}
							}

							if($col[2] == "getfilename") { 
								if($col[5] == "1") { 
									$pic['pic_org'] = str_replace(".jpg","",$pic['pic_org']);
									$pic['pic_org'] = str_replace(".JPG","",$pic['pic_org']);
									$pic['pic_org'] = str_replace(".Jpg","",$pic['pic_org']);

									$pic['pic_org'] = str_replace(".jpeg","",$pic['pic_org']);
									$pic['pic_org'] = str_replace(".gif","",$pic['pic_org']);
									$pic['pic_org'] = str_replace(".png","",$pic['pic_org']);

								}
								if($_REQUEST['dowith'] == "view") { 
									print "<td>".$pic['pic_org'].",</td>";
								} else { 
									$exp .= $pic['pic_org'].",";
								}
							}
							if($col[2] == "getfilelink") { 
								$pic['full_url'] = true;
								if($_REQUEST['dowith'] == "view") { 
									print "<td>". getimagefile($pic,'pic_full').",</td>";
								} else { 
									$exp .= getimagefile($pic,'pic_full').",";
								}
							}
							if($col[2] == "getoptions") { 
								$c = 0;
								if($_REQUEST['dowith'] == "view") { 
									print "<td>";
								}
								$cos = whileSQL($cart_table."  LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*", "WHERE cart_order='".$order['order_id']."'  AND co_pic_id='".$cart['cart_pic_id']."'  ORDER BY co_id ASC ");
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										if($_REQUEST['dowith'] == "view") { 
											print "| ";
										} else { 
											$exp .= "| ";
										}
									}
									if($_REQUEST['dowith'] == "view") { 
										print $co['co_opt_name']; 
									} else { 
										$exp .= $co['co_opt_name']; 
									}
									$c++;
								} 


								$cos = whileSQL("ms_cart_options", "*", "WHERE co_cart_id='".$cart['cart_id']."' AND co_pic_id<='0' ORDER BY co_id ASC ");
								while($co = mysqli_fetch_array($cos)) {
									if($c > 0) { 
										if($_REQUEST['dowith'] == "view") { 
											print "| ";
										} else { 
											$exp .= "| ";
										}
									}
									if($_REQUEST['dowith'] == "view") { 
										print $co['co_opt_name'].": ".$co['co_select_name']; 
									} else { 
										$exp .= $co['co_opt_name'].": ".$co['co_select_name'];
									}
									$c++;
								}
								if($_REQUEST['dowith'] == "view") { 
									print ",</td>";
								} else { 
									$exp .= ",";
								}

							}
						}
						if($_REQUEST['dowith'] == "view") { 
							print "</tr>";
						} else { 
							$exp .= "\r\n";
						}

					// showOrderPhoto($cart,"0","",$order);
				}







			/*
				$carts = whileSQL($cart_table, "*", "WHERE cart_store_product!='0' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id DESC  " );
				$tracks_total	= mysqli_num_rows($carts);
					while($cart= mysqli_fetch_array($carts)) {
					$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");
					$tracknum++;
					showOrderProduct($date,$cart);
					$pcarts = whileSQL($cart_table, "*", "WHERE  cart_order='".$order['order_id']."'  AND cart_product_photo='".$cart['cart_id']."' AND cart_pic_id!='0'  ORDER BY cart_pic_org ASC" );
						while($pcart= mysqli_fetch_array($pcarts)) {
							$tracknum++;
							showOrderPhoto($pcart,"1",$cart,$order);
						}
					if(mysqli_num_rows($pcarts) > 0) { 
						print "<div>&nbsp;</div><div>&nbsp;</div>";
					}
				}


				$carts = whileSQL($cart_table, "*", "WHERE cart_invoice='1' AND  cart_order='".$order['order_id']."' AND cart_coupon='0' ORDER BY cart_id ASC  " );
					while($cart= mysqli_fetch_array($carts)) {
					showInvoiceItem($cart);
				}

			$carts = whileSQL($cart_table." LEFT JOIN ms_cart_options ON ms_cart_options.co_cart_id=".$cart_table.".cart_id", "*, SUM(co_price) AS this_price, COUNT(co_id) AS total_items", "WHERE cart_order='".$order['order_id']."' AND co_pic_id>'0' GROUP BY co_opt_name  ORDER BY co_id ASC");
			$tracks_total	= mysqli_num_rows($carts);
				while($cart= mysqli_fetch_array($carts)) {
				showOrderImageOptions($cart, "0",$order);
			}
			*/
		}
	}
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
