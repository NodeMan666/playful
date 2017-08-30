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

$this_dom = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
$info = explode('//', $_SERVER['HTTP_REFERER']); 
$d1 = $info[1]; 
$info2 = explode('/', $d1); 
$d2 = $info2[0]; 
$from_dom = str_replace("www.", "", strtolower($d2));

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

// $_REQUEST['dowith'] = "view";
$sep = $_REQUEST['sep'];

if(!empty($_REQUEST['date_from'])) { 
	$f = explode("-",$_REQUEST['date_from']);
	$t = explode("-",$_REQUEST['date_to']);
	$date_where = "order_payment_date>='".$_REQUEST['date_from']." 00:00:00' AND order_payment_date<='".$_REQUEST['date_to']." 23:59:59' ";
	$exp_date_where = "exp_date>='".$_REQUEST['date_from']."' AND exp_date<='".$_REQUEST['date_to']."' ";

	$from = date('l F d, Y',mktime(0,0,0,$f[1],$f[2],$f[0]));
	$to = date('l F d, Y',mktime(0,0,0,$t[1],$t[2],$t[0]));
	$show_from =$from." - ".$to;
} elseif(!empty($_REQUEST['year'])) { 
	$date_where = " YEAR(order_payment_date)='".$_REQUEST['year']."'";
	$exp_date_where = "YEAR(exp_date)='".$_REQUEST['year']."'";
	$show_from = $_REQUEST['year'];
} else { 
	$date_where = "MONTH(order_payment_date)='".date('m')."' AND YEAR(order_payment_date)='".date('Y')."'";
	$exp_date_where = "MONTH(exp_date)='".date('m')."' AND YEAR(exp_date)='".date('Y')."'";
	$show_from = "This Month";
	$_REQUEST['date_from'] = date('Y-m')."-01";
	$_REQUEST['date_to'] = date('Y-m-t');
}

$fs = whileSQL("ms_order_export", "*", "WHERE fs='1' ORDER BY fo ASC ");
while($f = mysqli_fetch_array($fs)) { 
	$exp .= "".$f['fl'].$sep;
}
if($_REQUEST['dowith'] == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}




if(!empty($_REQUEST['orderNum'])) { 
	$orderNums = explode("|",$_REQUEST['orderNum']);
	foreach($orderNums AS $orderNum) { 
		if(!empty($orderNum)) { 

			$order = doSQL("ms_orders", "*", "WHERE order_id='".$orderNum."' ");
			$gal_name = "";
			$tx = 0;
			$fs = whileSQL("ms_order_export", "*", "WHERE fs='1' ORDER BY fo ASC ");
			while($f = mysqli_fetch_array($fs)) { 
				if($f['fn'] == "PAGENAME") { 

				$gals = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id", "*", "WHERE cart_order='".$order['order_id']."' AND cart_pic_date_id>='0' GROUP BY cart_pic_date_id");
			//	print "<li>".mysqli_num_rows($gals);

				while($gal = mysqli_fetch_array($gals)) { 
					if($tx > 0) { $gal_name .= " | "; } 
					$gal_name .= $gal['date_title'];
					$tx++;
				
				}

					$exp.= '"'.str_replace('"'," ",$gal_name).'"'.$sep;
				} elseif($f['fn'] == "EXPENSES") { 
					$expenses = doSQL("ms_expenses", "*,SUM(exp_amount) AS total", "WHERE exp_order='".$order['order_id']."' ");
					$exp.= '"'.str_replace('"'," ",$expenses['total']).'"'.$sep;
				} else { 
					$exp.= '"'.str_replace('"'," ",$order[$f['fn']]).'"'.$sep;
				}
			}
			if($_REQUEST['dowith'] == "view") {
					$exp .= "<br>";
			} else {
					$exp .= "\r\n";
			}

		}
	}



} else { 
	$orders = whileSQL("ms_orders", "*", "WHERE $date_where  AND order_payment_status='Completed' AND order_status<'2'  ORDER BY order_id ASC ");
	while($order = mysqli_fetch_array($orders)) { 

		$gal_name = "";
		$tx = 0;
		$fs = whileSQL("ms_order_export", "*", "WHERE fs='1' ORDER BY fo ASC ");
		while($f = mysqli_fetch_array($fs)) { 
			if($f['fn'] == "PAGENAME") { 

			$gals = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id", "*", "WHERE cart_order='".$order['order_id']."' AND cart_pic_date_id>='0' GROUP BY cart_pic_date_id");
		//	print "<li>".mysqli_num_rows($gals);

			while($gal = mysqli_fetch_array($gals)) { 
				if($tx > 0) { $gal_name .= " | "; } 
				$gal_name .= $gal['date_title'];
				$tx++;
			
			}

				$exp.= '"'.str_replace('"'," ",$gal_name).'"'.$sep;
			} elseif($f['fn'] == "EXPENSES") { 
				$expenses = doSQL("ms_expenses", "*,SUM(exp_amount) AS total", "WHERE exp_order='".$order['order_id']."' ");
				$exp.= '"'.str_replace('"'," ",$expenses['total']).'"'.$sep;
			} else { 
				$exp.= '"'.str_replace('"'," ",$order[$f['fn']]).'"'.$sep;
			}
		}
		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}

	}
}
if($_REQUEST['dowith'] == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}



if($_REQUEST['dowith'] == "view") {

	print $exp;
} else {


	$filename = date('Ymdhmi')."-".MD5(date('Ymdhmi')).".csv";

	$fp = fopen("".$setup['path']."/sy-tmp/$filename", "w");
	$info =  "$exp"; 
	fputs($fp, "$info\n");
	fclose($fp);



	$filecontent="".$setup['path']."/sy-tmp/$filename";
	$downloadfile=$_REQUEST['reportname'].".".$_REQUEST['filename'];
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
