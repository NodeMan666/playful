<?php 
require "../setup.php"; 
session_start();
error_reporting(E_ALL ^ E_NOTICE);
require "../_functions.php"; 
require "../_pc_functions.php"; 
require "_admin_functions.php";
adminsessionCheck();
$this_dom = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
$info = explode('//', $_SERVER['HTTP_REFERER']); 
$d1 = $info[1]; 
$info2 = explode('/', $d1); 
$d2 = $info2[0]; 
$from_dom = str_replace("www.", "", strtolower($d2));
asort($_POST['dis_order']);

/* testing area */
/*
foreach($_POST['dis_order'] AS $id => $order) {
//	print "<li>$id => $order";
	if($_POST['use_field'][$id] == "on") {
		print "<li>".$_POST['label'][$id];
	}

}
*/
// print "<pre>"; print_r($_POST);
// exit();

/* end testing area */

if($from_dom !== $this_dom) {
	die("You are not authorized to do this. Your IP address (".getUserIP().") has been sent to the website owner.");
}

$dbcon = dbConnect($setup);
$site_setup = doSQL("pc_settings", "*", "");
$asettings = doSQL("pc_admin_settings", "*", "");





	if($_POST['save_settings'] == "on") {
		updateSQL("pc_report_export", "status='0' ");
		if($_POST['add_labels'] == "on") {
			$new_add_labels = "1";
		} else {
			$new_add_labels = "0";
		}
		updateSQL("pc_report_export", "sep='".$_POST['sepby']."', sep2='".addslashes(stripslashes($_POST['sepby2']))."', headers='$new_add_labels', order_by='".$_REQUEST['order_by']."', acdc='".$_POST['acdc']."' WHERE id='1' ");
		foreach($_POST['dis_order'] AS $id => $order) {
			updateSQL("pc_report_export", "dis_order='$order', label_name='".addslashes(stripslashes($_POST['label'][$id]))."' WHERE id='$id' ");
			if($_POST['use_field'][$id] == "on") {
				updateSQL("pc_report_export", "status='1' WHERE id='$id' ");
			}
		}
	}







if(!empty($_POST['sepby2'])) {
	$sep = $_POST['sepby2'];
} else {
	$sep = $_POST['sepby'];
}
if($sep == "lb") { $sep = "\r\n"; } 
if($sep == "tab") { $sep = "\t"; } 

	if(!empty($_REQUEST['date'])) {
		if($_REQUEST['date'] == "today") {
			$and_where .= " AND  (( order_pay_date!='0000-00-00' AND MONTH(order_pay_date)='".date('m')."' AND YEAR(order_pay_date)='".date('Y')."' AND DAYOFMONTH(order_pay_date)='".date('d')."') OR (order_pay_date='0000-00-00' AND  MONTH(order_date)='".date('m')."' AND YEAR(order_date)='".date('Y')."' AND DAYOFMONTH(order_date)='".date('d')."')) ";
			$and_show .= "  Today";
		}
		if($_REQUEST['date'] == "yesterday") {
			$datey  = date("Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$datem  = date("m", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$dated  = date("d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$and_where .= " AND (( order_pay_date!='0000-00-00' AND MONTH(order_pay_date)='".$datem."' AND YEAR(order_pay_date)='".$datey."' AND DAYOFMONTH(order_pay_date)='".$dated."') OR ( order_pay_date='0000-00-00' AND  MONTH(order_date)='".$datem."' AND YEAR(order_date)='".$datey."' AND DAYOFMONTH(order_date)='".$dated."' )) ";
			$and_show .= "Yesterday";
		}
		if($_REQUEST['date'] == "month") {
			$and_where .= " AND (( order_pay_date!='0000-00-00' AND MONTH(order_pay_date)='".date('m')."' AND YEAR(order_pay_date)='".date('Y')."' ) OR (order_pay_date='0000-00-00' AND  MONTH(order_date)='".date('m')."' AND YEAR(order_date)='".date('Y')."')) ";
			$and_show .= "  This month";
		}
		if($_REQUEST['date'] == "lastmonth") {
			$lm  = date("m", mktime(0, 0, 0, date("m") -1 , 1, date("Y")));
			$lmy  = date("Y", mktime(0, 0, 0, date("m") -1 , 1, date("Y")));

			$and_where .= " AND (( order_pay_date!='0000-00-00' AND  MONTH(order_pay_date)='$lm' AND YEAR(order_pay_date)='$lmy' ) OR ( order_pay_date='0000-00-00' AND MONTH(order_date)='$lm' AND YEAR(order_date)='$lmy' ))";
			$and_show .= "  Last month";
		}
		if($_REQUEST['date'] == "thisyear") {
			$lmy  = date("Y", mktime(0, 0, 0, date("m") , date("d"), date("Y")));

			$and_where .= "  AND  (( order_pay_date!='0000-00-00' AND  YEAR(order_pay_date)='$lmy' ) OR (  order_pay_date='0000-00-00' AND  YEAR(order_date)='$lmy' )) ";
			$and_show .= "  This year - $lmy";
		}

 		if($_REQUEST['date'] == "lastyear") {
			$lmy  = date("Y", mktime(0, 0, 0, date("m") , date("d"), date("Y") - 1));
			$and_where .= "  AND (( order_pay_date!='0000-00-00' AND YEAR(order_pay_date)='$lmy') OR ( order_pay_date='0000-00-00' AND YEAR(order_date)='$lmy')) ";
			$and_show .= "  Last Year - $lmy";
		}

		if($_REQUEST['date'] == "alltime") {
			$and_show .= "All time";
		}
		if($_REQUEST['date'] == "week") {
			$today = date('Y-m-d');
			$yesterday  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$and_where .= " AND  (( order_pay_date!='0000-00-00' AND  order_pay_date >= CURDATE()  - INTERVAL 7 DAY ) OR (order_pay_date='0000-00-00' AND  order_date >= CURDATE()  - INTERVAL 7 DAY ))";
			$and_show .= "Last 7 days";
		}
	}

	if(!empty($_REQUEST['f_month'])) {
		$from  = date("Y-m-d", mktime(0, 0, 0, $_REQUEST['f_month'], $_REQUEST['f_day'], $_REQUEST['f_year']));
		$froms  = date("M d, Y", mktime(0, 0, 0, $_REQUEST['f_month'], $_REQUEST['f_day'], $_REQUEST['f_year']));
		$to  = date("Y-m-d", mktime(0, 0, 0, $_REQUEST['t_month'], $_REQUEST['t_day'], $_REQUEST['t_year']));
		$tos  = date("M d, Y", mktime(0, 0, 0, $_REQUEST['t_month'], $_REQUEST['t_day'], $_REQUEST['t_year']));
		$and_show = "From $froms to $tos";
		$and_where .= " AND (( order_pay_date!='0000-00-00' AND  order_pay_date >='$from' AND order_pay_date <='$to' ) OR (order_pay_date='0000-00-00' AND  order_date >='$from' AND order_date <='$to'))";
	}
	if((empty($_REQUEST['date']))AND(empty($_REQUEST['f_month']))==true) {
		$and_where .= " AND (( order_pay_date!='0000-00-00' AND MONTH(order_pay_date)='".date('m')."' AND YEAR(order_pay_date)='".date('Y')."') OR ( order_pay_date='0000-00-00'  AND MONTH(order_date)='".date('m')."' AND YEAR(order_date)='".date('Y')."')) ";
		$and_show .= "  This month";
		
	}

	$and_where .= " AND (order_pay_amount!='0.00' OR order_credit!='0.00' OR order_gc_amount!='0.00') ";


	$total_results = countIt("pc_orders", "WHERE order_id>'0' $and_where " );
	
	$sq_page = $page * $per_page - $per_page;
	if($_POST['add_labels'] == "on") {
		foreach($_POST['dis_order'] AS $id => $order) {
		//	print "<li>$id => $order";
			if($_POST['use_field'][$id] == "on") {
	//			print "<li>".$_POST['label'][$id];
				$exp .= "".str_replace(",", " ", $_POST['label'][$id])."$sep";
			}

		}
		$exp .= "\r\n";
	}


/*
	if($_POST['order_num'] == "on") {
		$exp .= "".str_replace(",", " ", "Order Number")."$sep";
	}
	if($_POST['show_date'] == "on") {
		$exp .= "".str_replace(",", " ", "Date")."$sep";
	}
	if($_POST['customer'] == "on") {
		$exp .= "".str_replace(",", " ", "Customer")."$sep";
	}
	if($_POST['amount'] == "on") {
		$exp .= "".str_replace(",", " ", "Amount")."$sep";
	}
	if($_POST['tax'] == "on") {
		$exp .= "".str_replace(",", " ", "Tax")."$sep";
	}
	if($_POST['report_vat'] == "on") {
		$exp .= "".str_replace(",", " ", "Vat")."$sep";
	}
	if($_POST['report_gst'] == "on") {
		$exp .= "".str_replace(",", " ", "GST")."$sep";
	}
	if($_POST['shipping'] == "on") {
		$exp .= "".str_replace(",", " ", "Shipping")."$sep";
	}
	if($_POST['discount'] == "on") {
		$exp .= "".str_replace(",", " ", "Discount")."$sep";
	}
	if($_POST['eb_discount'] == "on") {
		$exp .= "".str_replace(",", " ", "E.B. Discount")."$sep";
	}
	if($_REQUEST['dowith'] == "view") {
			$exp .= "<br>";
	} else {
			$exp .= "\r\n";
	}
*/

	$orders = whileSQL("pc_orders LEFT JOIN pc_clients on pc_orders.order_client=pc_clients.client_id", "*, date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']."')  AS order_show_date, date_format(DATE_ADD(order_pay_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']."')  AS order_pay_show_date", "WHERE order_id>'0'  $and_where ORDER BY ".$_POST['order_by']." ".$_POST['acdc']." ");
	while ( $order = mysqli_fetch_array($orders) ) {

	foreach($_POST['dis_order'] AS $id => $dorder) {
	//	print "<li>$id => $order";
		if($_POST['use_field'][$id] == "on") {
//			print "<li>".$_POST['label'][$id];
			$field = $_POST['field_name'][$id];
			if($field == "order_total") {
				$order_total = orderTotal("".$order['order_id']);
				$exp .= "".str_replace(",", " ", $order_total)."$sep";
			} elseif($field == "client_address") {
				$address = "".$order['client_address1']."  ".$order['client_city'].", ".$order['client_state']." ".$order['client_zip']."";
				$exp .= "".str_replace(",", " ", $address)."$sep";
			} elseif($field == "order_credit") {
				$exp .= "".str_replace(",", " ", $order['order_credit'] + $order['order_gc_amount'])."$sep";
			} else {
				$exp .= "".str_replace(",", " ", $order[$field])."$sep";
			}
		}
		unset($field);
		unset($order_total);
		unset($address);
	}



	if($_POST['order_num'] == "on") {
		$exp .= "".str_replace(",", " ", "".$order['order_id']."")."$sep";
	}
	if($_POST['show_date'] == "on") {
		if($order['order_pay_date']!=="0000-00-00") { 
			$exp .= "".str_replace(",", " ", "".$order['order_pay_show_date']."")."$sep";
		} else {
			$exp .= "".str_replace(",", " ", "".$order['order_show_date']."")."$sep";
		}
	}
	if($_POST['customer'] == "on") {
		$cust = doSQL("pc_clients", "*", "WHERE client_id='".$order['order_client']."' ");
		$exp .= "".str_replace(",", " ", "".$cust['client_last_name'].", ".$cust['client_name']." ")."$sep";
	}

	if($_POST['amount'] == "on") {
//		$exp .= "".str_replace(",", " ", "".number_format($order['order_pay_amount'] + $order['order_credit'], 2, '.', '')."")."$sep";
		$exp .= "".str_replace(",", " ", "".number_format($order['order_pay_amount'], 2, '.', '')."")."$sep";
	}
	if($_POST['tax'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_tax'], 2, '.', '')."")."$sep";
	}
	if($_POST['report_vat'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_vat'], 2, '.', '')."")."$sep";
	}
	if($_POST['report_gst'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_gst'], 2, '.', '')."")."$sep";
	}
	if($_POST['shipping'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_ship'], 2, '.', '')."")."$sep";
	}
	if($_POST['discount'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_discount'], 2, '.', '')."")."$sep";
	}
	if($_POST['eb_discount'] == "on") {
		$exp .= "".str_replace(",", " ", "".number_format($order['order_eb_amount'], 2, '.', '')."")."$sep";
	}
	if($_REQUEST['dowith'] == "view") {
			$exp .= "<br><br>";
	} else {
			$exp .= "\r\n";
	}
}
	

if($_REQUEST['dowith'] == "view") {

	print $exp;
} else {

	$filename = date('Ymdhmi')."-".MD5(date('Ymdhmi')).".".$_POST['save_as']."";

	$fp = fopen("".$setup['path']."/".$setup['gallery_folder']."/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/".$setup['gallery_folder']."/$filename";
	$downloadfile="report-".$and_show.".".$_POST['save_as']."";

	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download"); 
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=\"$downloadfile\"");
	header("Content-transfer-encoding: binary\n"); 
	header("Content-length: " . filesize($filecontent) . "\n");

	@readfile($filecontent);

	unlink($filecontent);
}

?>
