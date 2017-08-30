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

if($_POST['id'] == "on") {
	$exp .= "ID".$_POST['sep']."";
}
if($_POST['company'] == "on") {
	$exp .= "COMPANY".$_POST['sep']."";
}
if($_POST['email'] == "on") {
	$exp .= "EMAIL".$_POST['sep']."";
}
if($_POST['firstlastName'] == "on") {
	$exp .= "NAME".$_POST['sep']."";
}
if($_POST['lastfirstName'] == "on") {
	$exp .= "NAME".$_POST['sep']."";
}
if($_POST['firstName'] == "on") {
	$exp .= "FIRSTNAME".$_POST['sep']."";
}
if($_POST['lastName'] == "on") {
	$exp .= "LASTNAME".$_POST['sep']."";
}
if($_POST['phone'] == "on") {
	$exp .= "PHONE".$_POST['sep']."";
}
if($_POST['address'] == "on") {
	$exp .= "ADDRESS".$_POST['sep']."";
}
if($_POST['city'] == "on") {
	$exp .= "CITY".$_POST['sep']."";
}
if($_POST['state'] == "on") {
	$exp .= "STATE".$_POST['sep']."";
}
if($_POST['zip'] == "on") {
	$exp .= "ZIP".$_POST['sep']."";
}
if($_POST['country'] == "on") {
	$exp .= "COUNTRY".$_POST['sep']."";
}
if($_POST['date'] == "on") {
	$exp .= "DATE".$_POST['sep']."";
}
if($_POST['gender'] == "on") {
	$exp .= "GENDER".$_POST['sep']."";
}
if($_POST['age'] == "on") {
	$exp .= "AGE".$_POST['sep']."";
}
if($_POST['source'] == "on") {
	$exp .= "SOURCE".$_POST['sep']."";
}
if($_POST['status'] == "on") {
	$exp .= "STATUS".$_POST['sep']."";
}

if($_REQUEST['dowith'] == "view") {
		$exp .= "<br>";
} else {
		$exp .= "\r\n";
}


$send_to_emails = array();
if($_REQUEST['registered'] == "1") { 
	$custs = whileSQL("ms_people", "*", "WHERE p_id>'0' $and_where ORDER BY ".$_POST['order_by']." ".$_POST['acdc']." " );
	while($cust = mysqli_fetch_array($custs)) {
		$total_export++;
		array_push($send_to_emails, $cust['p_email']);

		if($_POST['id'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_id'])."".$_POST['sep']."";
		}
		if($_POST['company'] == "on") {
			$exp .= "".$cust['p_company']."".$_POST['sep']."";
		}
		if($_POST['email'] == "on") {
			$exp .= "".$cust['p_email']."".$_POST['sep']."";
		}

		if($_POST['firstlastName'] == "on") {
			$firstlast = $cust['p_name']." ".$cust['p_last_name'];
			$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
		}
		if($_POST['lastfirstName'] == "on") {
			$lastfirst = $cust['p_last_name']." ".$cust['p_name'];
			$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
		}
		
		
		
		if($_POST['firstName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_name'])."".$_POST['sep']."";
		}
		if($_POST['lastName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_last_name'])."".$_POST['sep']."";
		}

		if($_POST['phone'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_phone'])."".$_POST['sep']."";
		}
		if($_POST['address'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_address1'])."".$_POST['sep']."";
		}
		if($_POST['city'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_city'])."".$_POST['sep']."";
		}
		if($_POST['state'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_state'])."".$_POST['sep']."";
		}
		if($_POST['zip'] == "on") {
			$exp .= "".$cust['p_zip']."".$_POST['sep']."";
		}
		if($_POST['country'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_country'])."".$_POST['sep']."";
		}
		if($_POST['date'] == "on") {
			$exp .= "".$cust['p_date']."".$_POST['sep']."";
		}
		if($_POST['gender'] == "on") {
			$exp .= "".$cust['p_gender']."".$_POST['sep']."";
		}
		if($_POST['age'] == "on") {
			$exp .= "".$cust['p_age']."".$_POST['sep']."";
		}
		if($_POST['source'] == "on") {
			$exp .= "".$cust['p_refer']."".$_POST['sep']."";
		}
		if($_POST['status'] == "on") {
			$exp .= "".$cust['p_receive_emails']."".$_POST['sep']."";
		}

		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}
}

if($_REQUEST['unregistered'] == "1") { 
	$orders = whileSQL("ms_orders", "*,date_format(DATE_ADD(order_date, INTERVAL ".$site_setup['time_diff']." HOUR), '".$site_setup['date_format']." ')  AS order_date", "WHERE order_customer='0' GROUP BY order_id ORDER BY order_id DESC "); 
	while($cust = mysqli_fetch_array($orders)) {
		if(!in_array($cust['order_email'],$send_to_emails)) { 
			$total_export++;
			array_push($send_to_emails, $cust['order_email']);

			if($_POST['id'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_id'])."".$_POST['sep']."";
			}
			if($_POST['company'] == "on") {
				$exp .= "".$cust['p_company']."".$_POST['sep']."";
			}

			if($_POST['email'] == "on") {
				$exp .= "".$cust['order_email']."".$_POST['sep']."";
			}

			if($_POST['firstlastName'] == "on") {
				$firstlast = $cust['order_first_name']." ".$cust['order_last_name'];
				$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
			}
			if($_POST['lastfirstName'] == "on") {
				$lastfirst = $cust['order_last_name']." ".$cust['order_first_name'];
				$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
			}
			
			
			
			if($_POST['firstName'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_first_name'])."".$_POST['sep']."";
			}
			if($_POST['lastName'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_last_name'])."".$_POST['sep']."";
			}

			if($_POST['phone'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_phone'])."".$_POST['sep']."";
			}
			if($_POST['address'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_address'])."".$_POST['sep']."";
			}
			if($_POST['city'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_city'])."".$_POST['sep']."";
			}
			if($_POST['state'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_state'])."".$_POST['sep']."";
			}
			if($_POST['zip'] == "on") {
				$exp .= "".$cust['order_zip']."".$_POST['sep']."";
			}
			if($_POST['country'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_country'])."".$_POST['sep']."";
			}
			if($_POST['date'] == "on") {
				$exp .= "".$cust['order_date']."".$_POST['sep']."";
			}
			if($_REQUEST['dowith'] == "view") {
					$exp .= "<br>";
			} else {
					$exp .= "\r\n";
			}
		}
	}
}


if($_REQUEST['mailinglist'] == "1") { 
	$ems = whileSQL("ms_email_list", "*", "WHERE em_id>'0' AND em_status='0' ORDER BY em_id DESC"); 	
	while($cust = mysqli_fetch_array($ems)) {
		if(!in_array($cust['em_email'],$send_to_emails)) { 
			$total_export++;
			array_push($send_to_emails, $cust['em_email']);

			if($_POST['email'] == "on") {
				$exp .= "".$cust['em_email']."".$_POST['sep']."";
			}

			if($_POST['firstlastName'] == "on") {
				$firstlast = $cust['em_name']." ".$cust['em_last_name'];
				$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
			}
			if($_POST['lastfirstName'] == "on") {
				$lastfirst = $cust['em_last_name']." ".$cust['em_name'];
				$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
			}
			
			
			
			if($_POST['firstName'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['em_name'])."".$_POST['sep']."";
			}
			if($_POST['lastName'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['em_last_name'])."".$_POST['sep']."";
			}

			if($_POST['phone'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_phone'])."".$_POST['sep']."";
			}
			if($_POST['address'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_address'])."".$_POST['sep']."";
			}
			if($_POST['city'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_city'])."".$_POST['sep']."";
			}
			if($_POST['state'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_state'])."".$_POST['sep']."";
			}
			if($_POST['zip'] == "on") {
				$exp .= "".$cust['order_zip']."".$_POST['sep']."";
			}
			if($_POST['country'] == "on") {
				$exp .= "".str_replace(",", " ", $cust['order_country'])."".$_POST['sep']."";
			}
			if($_POST['date'] == "on") {
				$exp .= "".$cust['order_date']."".$_POST['sep']."";
			}
			if($_REQUEST['dowith'] == "view") {
					$exp .= "<br>";
			} else {
					$exp .= "\r\n";
			}
		}
	}
}




if($_REQUEST['date_id'] > 0) { 
	$emails = array(); 
	$peeps = array();
	$preregs = array();
	$orders = array();
	$date = doSQL("ms_calendar  LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, date_format(DATE_ADD(date_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS date_show_date,date_format(date_expire, '".$site_setup['date_format']."')  AS date_expire", "WHERE date_id='".$_REQUEST['date_id']."' ");

	if($_REQUEST['xsend_access'] == "1") { 
		$ps = whileSQL("ms_my_pages LEFT JOIN ms_people ON ms_my_pages.mp_people_id=ms_people.p_id", "*, date_format(DATE_ADD(mp_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS mp_date", "WHERE mp_date_id='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
		while($p = mysqli_fetch_array($ps)) { 
			if(!in_array($p['p_email'],$emails)) { 
				array_push($emails,$p['p_email']);
			}
			if(!in_array($p['p_id'],$peeps)) { 
				$p_ids = $p_ids."|".$p['p_id'];
				array_push($peeps,$p['p_id']);
			}
		}
	}
	if($_REQUEST['xsend_viewed'] == "1") { 
		$ps = whileSQL("ms_view_page LEFT JOIN ms_people ON ms_view_page.v_person=ms_people.p_id", "*, date_format(DATE_ADD(v_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS v_date", "WHERE v_page='".$date['date_id']."' AND p_id>'0' ORDER BY p_last_name ASC ");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['p_email'],$emails)) { 
				array_push($emails,$p['p_email']);
			}
			if(!in_array($p['p_id'],$peeps)) { 
				$p_ids = $p_ids."|".$p['p_id'];
				array_push($peeps,$p['p_id']);
			}
		}
	}
	if($_REQUEST['xsend_pre_reg'] == "1") { 
		$ps = whileSQL("ms_pre_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."' AND toview<='0' ORDER BY reg_id DESC");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['reg_email'],$emails)) { 
				array_push($emails,$p['reg_email']);
			}
			if(!in_array($p['reg_id'],$preregs)) { 
				// $p_ids = $p_ids."|".$p['p_id'];
				array_push($preregs,$p['reg_id']);
			}
		}
	}
	if($_REQUEST['xsend_emails_collected'] == "1") { 
		$ps = whileSQL("ms_pre_register", "*, date_format(DATE_ADD(reg_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS reg_date", "WHERE reg_date_id='".$date['date_id']."' AND toview='1' ORDER BY reg_id DESC");
		while($p = mysqli_fetch_array($ps)) {
			if(!in_array($p['reg_email'],$emails)) { 
				array_push($emails,$p['reg_email']);
			}
			if(!in_array($p['reg_id'],$preregs)) { 
				// $p_ids = $p_ids."|".$p['p_id'];
				array_push($preregs,$p['reg_id']);
			}
		}
	}

	if($_REQUEST['xsend_purchased'] == "1") { 

		// $ps = whileSQL("ms_cart LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id", "*, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date", "WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  GROUP BY order_email ORDER BY order_last_name ASC ");


		$ps = mysqli_query($dbcon,"
		SELECT *, date_format(DATE_ADD(order_date, INTERVAL 0 HOUR), '".$site_setup['date_format']."  ')  AS order_date FROM (
		SELECT *  FROM ms_cart 
		 LEFT JOIN ms_orders ON ms_cart.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2'  

	  UNION ALL

		SELECT *  FROM ms_cart_archive 
		 LEFT JOIN ms_orders ON ms_cart_archive.cart_order=ms_orders.order_id
		WHERE (cart_pic_date_id='".$date['date_id']."' OR cart_store_product='".$date['date_id']."') AND order_id>'0' AND order_email!='' AND order_payment_status='Completed' AND order_status!='2' 
		) 
		x 
		GROUP BY order_email ORDER BY order_id DESC
		");

		if (!$ps) {	logmysqlerrors(mysqli_error($dbcon));	die("<div class=\"error\">MYSQL ERROR:   " . mysqli_error($dbcon) . " <br><br>Query: SELECT $what FROM $table $where</div>"); }


		while($p = mysqli_fetch_array($ps)) {
			if(!empty($p['order_email'])) { 

				if(!in_array($p['order_email'],$emails)) { 
					array_push($emails,$p['order_email']);
				
					if(!in_array($p['order_email'],$orders)) { 
						// $p_ids = $p_ids."|".$p['p_id'];
						array_push($orders,$p['order_email']);
					}
				}
			}
		 } 
	}


	foreach($peeps AS $id) { 
		$cust = doSQL("ms_people", "*", "WHERE p_id='".$id."' " );
		$total_export++;
		array_push($send_to_emails, $cust['p_email']);

		if($_POST['id'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_id'])."".$_POST['sep']."";
		}
		if($_POST['company'] == "on") {
			$exp .= "".$cust['p_company']."".$_POST['sep']."";
		}

		if($_POST['email'] == "on") {
			$exp .= "".$cust['p_email']."".$_POST['sep']."";
		}

		if($_POST['firstlastName'] == "on") {
			$firstlast = $cust['p_name']." ".$cust['p_last_name'];
			$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
		}
		if($_POST['lastfirstName'] == "on") {
			$lastfirst = $cust['p_last_name']." ".$cust['p_name'];
			$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
		}
		
		
		
		if($_POST['firstName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_name'])."".$_POST['sep']."";
		}
		if($_POST['lastName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_last_name'])."".$_POST['sep']."";
		}

		if($_POST['phone'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_phone'])."".$_POST['sep']."";
		}
		if($_POST['address'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_address1'])."".$_POST['sep']."";
		}
		if($_POST['city'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_city'])."".$_POST['sep']."";
		}
		if($_POST['state'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_state'])."".$_POST['sep']."";
		}
		if($_POST['zip'] == "on") {
			$exp .= "".$cust['p_zip']."".$_POST['sep']."";
		}
		if($_POST['country'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_country'])."".$_POST['sep']."";
		}
		if($_POST['date'] == "on") {
			$exp .= "".$cust['p_date']."".$_POST['sep']."";
		}
		if($_POST['gender'] == "on") {
			$exp .= "".$cust['p_gender']."".$_POST['sep']."";
		}
		if($_POST['age'] == "on") {
			$exp .= "".$cust['p_age']."".$_POST['sep']."";
		}
		if($_POST['source'] == "on") {
			$exp .= "".$cust['p_refer']."".$_POST['sep']."";
		}
		if($_POST['status'] == "on") {
			$exp .= "".$cust['p_receive_emails']."".$_POST['sep']."";
		}

		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}

	foreach($orders AS $id) { 
		$order = doSQL("ms_orders", "*", "WHERE order_email='".$id."' " );
		$total_export++;
		array_push($send_to_emails, $order['order_email']);

		if($_POST['id'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_id'])."".$_POST['sep']."";
		}
		if($_POST['company'] == "on") {
			$exp .= "".$cust['p_company']."".$_POST['sep']."";
		}

		if($_POST['email'] == "on") {
			$exp .= "".$order['order_email']."".$_POST['sep']."";
		}

		if($_POST['firstlastName'] == "on") {
			$firstlast = $order['order_first_name']." ".$order['order_last_name'];
			$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
		}
		if($_POST['lastfirstName'] == "on") {
			$lastfirst = $order['order_last_name']." ".$order['order_first_name'];
			$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
		}
		
		
		
		if($_POST['firstName'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_first_name'])."".$_POST['sep']."";
		}
		if($_POST['lastName'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_last_name'])."".$_POST['sep']."";
		}

		if($_POST['phone'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_phone'])."".$_POST['sep']."";
		}
		if($_POST['address'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_address'])."".$_POST['sep']."";
		}
		if($_POST['city'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_city'])."".$_POST['sep']."";
		}
		if($_POST['state'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_state'])."".$_POST['sep']."";
		}
		if($_POST['zip'] == "on") {
			$exp .= "".$order['order_zip']."".$_POST['sep']."";
		}
		if($_POST['country'] == "on") {
			$exp .= "".str_replace(",", " ", $order['order_country'])."".$_POST['sep']."";
		}
		if($_POST['date'] == "on") {
			$exp .= "".$order['order_date']."".$_POST['sep']."";
		}
		if($_POST['gender'] == "on") {
			$exp .= "".$order['p_gender']."".$_POST['sep']."";
		}
		if($_POST['age'] == "on") {
			$exp .= "".$order['p_age']."".$_POST['sep']."";
		}
		if($_POST['source'] == "on") {
			$exp .= "".$order['p_refer']."".$_POST['sep']."";
		}
		if($_POST['status'] == "on") {
			$exp .= "".$order['p_receive_emails']."".$_POST['sep']."";
		}

		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}

	foreach($preregs AS $id) { 
		$cust = doSQL("ms_pre_register", "*", "WHERE reg_id='".$id."' " );
		$total_export++;
		array_push($send_to_emails, $cust['reg_email']);

		if($_POST['id'] == "on") {
			$exp .= "".str_replace(",", " ", '')."".$_POST['sep']."";
		}
		if($_POST['company'] == "on") {
			$exp .= "".$cust['p_company']."".$_POST['sep']."";
		}

		if($_POST['email'] == "on") {
			$exp .= "".$cust['reg_email']."".$_POST['sep']."";
		}

		if($_POST['firstlastName'] == "on") {
			$firstlast = $cust['reg_first_name']." ".$cust['reg_last_name'];
			$exp .= "".str_replace(",", " ", $firstlast)."".$_POST['sep']."";
		}
		if($_POST['lastfirstName'] == "on") {
			$lastfirst = $cust['reg_last_name']." ".$cust['reg_first_name'];
			$exp .= "".str_replace(",", " ", $lastfirst)."".$_POST['sep']."";
		}
		
		
		
		if($_POST['firstName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['reg_first_name'])."".$_POST['sep']."";
		}
		if($_POST['lastName'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['reg_last_name'])."".$_POST['sep']."";
		}

		if($_POST['phone'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_phone'])."".$_POST['sep']."";
		}
		if($_POST['address'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_address1'])."".$_POST['sep']."";
		}
		if($_POST['city'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_city'])."".$_POST['sep']."";
		}
		if($_POST['state'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_state'])."".$_POST['sep']."";
		}
		if($_POST['zip'] == "on") {
			$exp .= "".$cust['p_zip']."".$_POST['sep']."";
		}
		if($_POST['country'] == "on") {
			$exp .= "".str_replace(",", " ", $cust['p_country'])."".$_POST['sep']."";
		}
		if($_POST['date'] == "on") {
			$exp .= "".$cust['p_date']."".$_POST['sep']."";
		}
		if($_POST['gender'] == "on") {
			$exp .= "".$cust['p_gender']."".$_POST['sep']."";
		}
		if($_POST['age'] == "on") {
			$exp .= "".$cust['p_age']."".$_POST['sep']."";
		}
		if($_POST['source'] == "on") {
			$exp .= "".$cust['p_refer']."".$_POST['sep']."";
		}
		if($_POST['status'] == "on") {
			$exp .= "".$cust['p_receive_emails']."".$_POST['sep']."";
		}

		if($_REQUEST['dowith'] == "view") {
				$exp .= "<br>";
		} else {
				$exp .= "\r\n";
		}
	}
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
