<?php
require "../../../../sy-config.php";
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
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings","*","");

$store = doSQL("ms_store_settings", "*", "");
if($store['checkout_ssl'] == "1") { 
	if(!empty($store['checkout_ssl_link'])) { 
		$url =  $store['checkout_ssl_link'];
	} else { 
		$url = "https://".$_SERVER['HTTP_HOST'];
	}
} else { 
	$url = $setup['url'];
}
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

function logsisow($qry) { 
	global $setup;
	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if(!is_dir($setup['path']."/sy-logs")) { 
		// print "No direcory";
		$parent_permissions = substr(sprintf('%o', @fileperms("".$setup['path']."/sy-photos")), -4); 
		if($parent_permissions == "0755") {
			$perms = 0755;
		} elseif($parent_permissions == "0777") {
			$perms = 0777;
		} else {
			$perms = 0755;
		}
		mkdir("".$setup['path']."/sy-logs", $perms);
		chmod("".$setup['path']."/sy-logs", $perms);
		$fp = fopen("".$setup['path']."/sy-logs/index.php", "w");
		fputs($fp, "Nope");
		fclose($fp);
	}

	$lfile = "sisow-log-".date('Y-m-d').".txt";

	if(!file_exists($setup['path']."/sy-logs/".$lfile)) { 
		$fp = fopen("".$setup['path']."/sy-logs/".$lfile, "w");
		fputs($fp,"");
		fclose($fp);
	}

	$info =  date('Y-m-d h:i:s')." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['HTTP_USER_AGENT']."  DATA: ".$qry.""; 
	// $info .=  " ".$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI'])." | REFERER: ".$_SERVER['HTTP_REFERER'].""; 

	$info.= "\r\n";

	file_put_contents($setup['path']."/sy-logs/".$lfile, $info, FILE_APPEND);

}



$pending_order = doSQL("ms_pending_orders", "*", "WHERE order_id='".$_SESSION['pending_order']."' ORDER BY order_id DESC");
	// if(empty($pending_order['order_id'])) { die("Sorry, something went wrong"); } 
$payment_amount = $pending_order['order_total'];

$pay_opt = doSQL("ms_payment_options", "*", "WHERE pay_option='sisow' ");

require_once "sisow.cls5.php";

$merchantid = $pay_opt['pay_num'];
$merchantkey = $pay_opt['pay_key'];
$shopid = "";

$sisow = new Sisow($merchantid, $merchantkey, $shopid);
if (isset($_POST["issuerid"])) {
	$sisow->purchaseId = $_POST["purchaseid"];
	$sisow->description = $_POST["description"];
	$sisow->amount = $_POST["amount"];
	$sisow->payment = $_POST["payment"];
	$sisow->issuerId = $_POST["issuerid"];
	$sisow->returnUrl = $url.$setup['temp_url_folder']."/?view=order&frompp=1&msos=".$_SESSION['ms_session']."&msok=".$pending_order['order_key']."";
	$sisow->notifyUrl = $url.$setup['temp_url_folder']."/sy-inc/store/payment/sisow/index.php";
	if (($ex = $sisow->TransactionRequest()) < 0) {
		header("Location: index.php?ex=" . $ex . "&ec=" . $sisow->errorCode . "&em=" . $sisow->errorMessage);
		exit;
	}
	header("Location: " . $sisow->issuerUrl);
}
else if (isset($_GET["trxid"])) {

	if(isset($_GET['notify']) || isset($_GET['callback']))
	{
		$sisow->StatusRequest($_GET["trxid"]);
		
		if($sisow->status == "Success")
		{

	## Sending pending order number with purchaseid, but for some reason it is returning as ec ... so we will try that.

	$pending_order = doSQL("ms_pending_orders", "*", "WHERE order_id='".$_REQUEST['ec']."' ORDER BY order_id DESC");

	if(!empty($pending_order['order_id'])) {
		$payment_amount = $pending_order['order_total'];
		$order_first_name=$pending_order['order_first_name'];
		$order_last_name=$pending_order['order_last_name'];
		$order_email=$pending_order['order_email'];
		$order_address=$pending_order['order_address'];
		$order_city=$pending_order['order_city'];
		$order_state=$pending_order['order_state'];
		$order_country=$pending_order['order_country'];
		$order_zip=$pending_order['order_zip'];
		if(!empty($pending_order['order_join_ml'])) {
			$order_join_ml=$pending_order['order_email'];
		}
		$order_phone=$pending_order['order_phone'];
		$order_company=$pending_order['order_company'];

		$order_ship_business=$pending_order['order_ship_business'];
		$order_ship_first_name=$pending_order['order_ship_first_name'];
		$order_ship_last_name=$pending_order['order_ship_last_name'];
		$order_ship_email=$pending_order['order_ship_email'];
		$order_ship_address=$pending_order['order_ship_address'];
		$order_ship_city=$pending_order['order_ship_city'];
		$order_ship_state=$pending_order['order_ship_state'];
		$order_ship_country=$pending_order['order_ship_country'];
		$order_ship_zip=$pending_order['order_ship_zip'];

		$order_ship_amount = $pending_order['order_shipping'];
		$order_tax = $pending_order['order_tax'];
		$order_discount = $pending_order['order_discount'];
		$order_sub_total = $pending_order['order_sub_total'];
		$order_shipping_option = $pending_order['order_shipping_option'];
		$order_coupon_id= $pending_order['order_coupon_id'];
		$order_coupon_name = $pending_order['order_coupon_name'];
		$order_vat = $pending_order['order_vat'];
		$order_vat_percentage = $pending_order['order_vat_percentage'];

		$order_tax_percentage = $pending_order['order_tax_percentage'];
		$order_taxable_amount= $pending_order['order_taxable_amount'];
		$order_key= $pending_order['order_key'];
		$customer_id= $pending_order['order_customer'];
		$order_session = $pending_order['order_session'];
		$credit_amount= $pending_order['order_credit'];
		$order_notes= $pending_order['order_notes'];
		$order_eb_discount = $pending_order['order_eb_discount'];

		$order_extra_field_1 = $pending_order['order_extra_field_1'];
		$order_extra_val_1 = $pending_order['order_extra_val_1'];
		$order_extra_field_2 = $pending_order['order_extra_field_2'];
		$order_extra_val_2 = $pending_order['order_extra_val_2'];
		$order_extra_field_3 = $pending_order['order_extra_field_3'];
		$order_extra_val_3 = $pending_order['order_extra_val_3'];
		$order_extra_field_4 = $pending_order['order_extra_field_4'];
		$order_extra_val_4 = $pending_order['order_extra_val_4'];
		$order_extra_field_5 = $pending_order['order_extra_field_5'];
		$order_extra_val_5 = $pending_order['order_extra_val_5'];

		deleteSQL("ms_pending_orders", "WHERE order_id='".$pending_order['order_id']."' ","1");

		$sub_total = $order_sub_total;
		$shipping_option = $order_shipping_option;
		$coupon_id = $order_coupon_id;
		$coupon_name = $order_coupon_name;
		$order_key=$order_key;
		$order_total_pay = $payment_amount;
		$order_fees = $payment_fees;
		$currency = "USD";
		$transaction_id = $_POST['x_trans_id'];
		$order_ship_amount = $order_ship_amount;
		$order_tax = $order_tax;
		$tax_percentage = $order_tax_percentage;
		$taxable_amount= $order_taxable_amount;
		$vat = $order_vat;
		$vat_percentage = $order_vat_percentage;
		$order_discount = $order_discount;
		$order_message  = $_POST['customer_message'];
		$order_pay_type = "iDeal";
		$order_payment_status = "Completed";
		$order_pending_reason = $_POST['pending_reason'];
		$pay_option = "sisow";
		$first_name = $order_first_name;
		$last_name = $order_last_name;
		$email_address = $order_email;
		$company_name = $order_company;
		$country = $order_country;
		$city = $order_city;
		$state = $order_state;
		$zip = $order_zip;
		$address = $order_address;
		$phone = $order_phone;
		$order_session = $order_session;
		$ship_business = $order_ship_business;
		$ship_first_name = $order_ship_first_name;
		$ship_last_name =  $order_ship_last_name;
		$ship_address  = $order_ship_address;
		$ship_city = $order_ship_city;
		$ship_state = $order_ship_state;
		$ship_zip = $order_ship_zip;
		$ship_country = $order_ship_country;
		$order_address_status = $_POST['address_status'];
		$order_payer_status = $_POST['payer_status'];
		
		$ip_address = $pending_order['order_ip'];
		$no_redirect = true;
		$anet_sim = true;
		if($pending_order['order_order_id'] > 0) { 
			$_REQUEST['order_id'] = $pending_order['order_order_id'];
			$order_id = $pending_order['order_order_id'];
			include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
		} else { 
			include $setup['path']."/sy-inc/store/payment/payment-complete.php";
			createOrder();
		}
	}
		}
	}
	else
	{

		//stuur klant naar de juiste pagina
		if($_GET['status'] == "Success")
			$url = "succesurl";
		else
			$url = $url.$setup['temp_url_folder'].$ge_return_link."/index.php?view=checkout";
			
		header("Location: " . $url);
	}
	exit;
}
else {
	// there are 2 methods for filling the available issuers in the select/dropdown
	// below, the REST method DirectoryRequest is used
	if($pay_opt['test_mode'] == "1") { 
		$testmode = true; //true = testmode
	}
	$sisow->DirectoryRequest($select, true, $testmode);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" >
 
<head><title>
	Sisow
</title>

<link rel="stylesheet" href="https://www.sisow.nl/Sisow/css/styleopd.css" type="text/css" />
<link rel="stylesheet" href="https://www.sisow.nl/Sisow/css/style.css" type="text/css" />
<link rel="stylesheet" href="https://www.sisow.nl/Sisow/css/style_table.css" type="text/css" />
<link href="../images/sisow_blauw.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript">
    function betaal() {
        _form = document.getElementById("body_form");
        _form.submit();
    }
</script>
</head>

<body>

<form name="body_form" method="post" id="body_form">
 
<table cellpadding="0" cellspacing="0" id="body_table" width="980" height="100%" align="center">
 
  <tr>
    <td class="logo" height="200" width="274" background="https://www.sisow.nl/Sisow/images/header/logo.jpg" valign="top" />
    <td class="top_info2" height="200" width="339" background="https://www.sisow.nl/Sisow/images/header/midden.jpg" valign="top">
      <span class="welkom">Welkom</span>
    </td>
    <td class="menu" height="200" width="367" background="https://www.sisow.nl/Sisow/images/header/menu.jpg" valign="top" style="padding-top: 20px; text-align: center; vertical-align: middle;">
      &nbsp;
    </td>
  </tr>
  
  <tr>
    <td colspan="3">
      <h2>Sisow betaling</h2>
      <img src="https://www.sisow.nl/Sisow/images/header/line.jpg" width="980" height="1" /><br />
    </td>
  </tr>
 
  
  <tr>
    <td colspan="3" class="content">
      <br />
      <div id="uplinks">
	
  
      <table cellpadding="0" cellspacing="0" width="525" align="center" class="detail_table">
        <tr>
          <td class="top"><div style="color: #008ed0;">&euro;</div></td>
        </tr>
        <tr>
          <td class="header"><div style="color: #008ed0;">iDEAL betaling</div></td>
        </tr>
        <tr>
          <td class="row" align="left">
          <table cellpadding="0" cellspacing="0" width="93%" align="left" class="detail_row">
            <tr>
              <td>
                <table cellpadding="0" cellspacing="0" width="100%">
                    <input type="hidden" name="purchaseid" value="<?php print $pending_order['order_id'];?>" maxlength="16" />
					<input type="hidden" name="description" value="Omschrijving" maxlength="32">
					<input type="hidden" name="amount" value="<?php print $pending_order['order_total'];?>" maxlength="10" />
                  <tr><td colspan="3">&nbsp;</td></tr>
 
                  <tr>
                    <td>Betaalmethode</td>
                    <td>
                      <select name="payment" style="width: 200px; color: #008ed0">
                        <option value="">iDEAL</option>
                        <option value="sofort">DIRECTebanking</option>
                        <option value="mistercash">MisterCash</option>
                        <option value="webshop">WebShop GiftCard</option>
                        <option value="podium">Podium Cadeaukaart</option>
                      </select>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
 
                  <tr><td colspan="3">&nbsp;</td></tr>
 
                  <tr>
                    <td>Bank</td>
                    <td><?php echo $select ?></td>
					<!-- below the other method for filling the available issuers
					  <select name="issuerid">
						<script type="text/javascript" src="https://www.sisow.nl/Sisow/iDeal/issuers.js"></script>
					  </select>
					-->
                    <td>&nbsp;</td>
                  </tr>
 
                  <tr><td colspan="3">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td class="footer" valign="top">
            <table cellpadding="0" cellspacing="0" style="width: 500px; font-family: Verdana; font-size: 10px;">
              <tr style="height: 30px;">
                <td style="text-align: right; xwidth: 100px;">
		  <input type="button" onclick="this.disabled=true;document.body_form.submit()" value="Ga verder" title="Betaal" />
                  <!--<a href="javascript:this.href='';betaal();" id="aBetaal" style="color: #008ed0; text-decoration: none;">Betaal</a>
                  <img src="../images/table/pijltje_b.jpg" alt="" />-->
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
 
</div>
    </td>
  </tr>
 
 
  <tr>
    <td class="bg_bottom2" height="19" colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="bottom_left" height="25" colspan="2" align="left">&nbsp;</td>
    <td class="bottom_right" height="25" align="right">&copy; Copyright - sisow</td>
  </tr>
 
</table>

</form>
 
</body>
 
</html>
