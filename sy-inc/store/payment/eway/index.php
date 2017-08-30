<?php
/**
 * Example eWAY Rapid 3.1 Direct Payment
 *
 * This page demonstrates how to use eWAY's Rapid 3.1 API
 * to complete a direct connection payment.
 * Please note, since the data is sent via your server, either
 * eWAY needs to be provided with evidence of PCI compliance or
 * client side encryption needs to be implemented.
 *
 * THIS SCRIPT IS INTENDED AS AN EXAMPLE ONLY
 *
 * @see https://eway.io/api-v3/#direct-connection
 */

// session_start();

function logeway($qry) { 
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

	$lfile = "eway-log-".date('Y-m-d').".txt";

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

$payinfo = doSQL("ms_payment_options", "*", "WHERE pay_option='eway' ");

$api_key = $payinfo['pay_num'];
$api_password = $payinfo['pay_key'];
if(empty($store['currency'])) { 
	$currency_code = "AUD";
} else { 
	$currency_code = $store['currency'];
}
$country = doSQL("ms_countries", "*", "WHERE country_name='".$_POST['country']."' ");
$country_abr = $country['abr'];
// Include RapidAPI Library
require('RapidAPI.php');

$in_page = 'before_submit';

//Payment data submitted
if ( isset($_POST['btnSubmit']) ) {

    // we skip all validation but you should do it in real world

    // Create DirectPayment Request Object
    $request = new eWAY\CreateDirectPaymentRequest();

    // Populate values for Customer Object
    // Note: TokenCustomerID is required when update an exsiting TokenCustomer
    if (!empty($_POST['txtTokenCustomerID'])) {
        $request->Customer->TokenCustomerID = $_POST['txtTokenCustomerID'];
    }

    //$request->Customer->Reference = $_POST['txtCustomerRef'];
	//$request->Customer->Title = $_POST['ddlTitle'];
    $request->Customer->FirstName = $_POST['first_name'];
    $request->Customer->LastName = $_POST['last_name'];
    $request->Customer->CompanyName = $_POST['business_name'];
   // $request->Customer->JobDescription = $_POST['txtJobDescription'];
    $request->Customer->Street1 = $_POST['address'];
    $request->Customer->City = $_POST['city'];
    $request->Customer->State = $_POST['state'];
    $request->Customer->PostalCode = $_POST['zip'];
    $request->Customer->Country = $country_abr;
    $request->Customer->Email = $_POST['email_address'];
    $request->Customer->Phone = $_POST['phone'];
    // $request->Customer->Mobile = $_POST['txtMobile'];
    $request->Customer->Comments = $_POST['txtComments'];
    // $request->Customer->Fax = $_POST['txtFax'];
    // $request->Customer->Url = $_POST['txtUrl'];

    $request->Customer->CardDetails->Name = $_POST['first_name']." ".$_POST['last_name'];
    $request->Customer->CardDetails->Number = $_POST['creditcardeway'];
    $request->Customer->CardDetails->ExpiryMonth = $_POST['montheway'];
    $request->Customer->CardDetails->ExpiryYear = $_POST['yeareway'];
    $request->Customer->CardDetails->StartMonth = $_POST['ddlStartMonth'];
    $request->Customer->CardDetails->StartYear = $_POST['ddlStartYear'];
    $request->Customer->CardDetails->IssueNumber = $_POST['txtIssueNumber'];
    $request->Customer->CardDetails->CVN = $_POST['txtCVN'];

    // Populate values for ShippingAddress Object.
    // This values can be taken from a Form POST as well. Now is just some dummy data.
	/*
    $request->ShippingAddress->FirstName = "John";
    $request->ShippingAddress->LastName = "Doe";
    $request->ShippingAddress->Street1 = "9/10 St Andrew";
    $request->ShippingAddress->Street2 = " Square";
    $request->ShippingAddress->City = "Edinburgh";
    $request->ShippingAddress->State = "";
    $request->ShippingAddress->Country = "gb";
    $request->ShippingAddress->PostalCode = "EH2 2AF";
    $request->ShippingAddress->Email = "your@email.com";
    $request->ShippingAddress->Phone = "0131 208 0321";
    // ShippingMethod, e.g. "LowCost", "International", "Military". Check the spec for available values.
    $request->ShippingAddress->ShippingMethod = "LowCost";
*/
    if ($_POST['ddlMethod'] == 'ProcessPayment' || $_POST['ddlMethod'] == 'Authorise' || $_POST['ddlMethod'] == 'TokenPayment') {
        // Populate values for LineItems
		/*
        $item1 = new eWAY\LineItem();
        $item1->SKU = "SKU1";
        $item1->Description = "Description1";
        $item2 = new eWAY\LineItem();
        $item2->SKU = "SKU2";
        $item2->Description = "Description2";
        $request->Items->LineItem[0] = $item1;
        $request->Items->LineItem[1] = $item2;
		*/
        // Populate values for Payment Object
        $request->Payment->TotalAmount = $_POST['grand_total'] * 100;
        $request->Payment->InvoiceNumber = $_POST['txtInvoiceNumber'];
        $request->Payment->InvoiceDescription = $_POST['txtInvoiceDescription'];
        $request->Payment->InvoiceReference = $_POST['txtInvoiceReference'];
        $request->Payment->CurrencyCode = $currency_code;
    }

    // Populate values for Options (not needed since it's in one script)
    // $opt1 = new eWAY\Option();
    // $opt1->Value = $_POST['txtOption1'];
    // $opt2 = new eWAY\Option();
    // $opt2->Value = $_POST['txtOption2'];
    // $opt3 = new eWAY\Option();
    // $opt3->Value = $_POST['txtOption3'];
    // $request->Options->Option[0]= $opt1;
    // $request->Options->Option[1]= $opt2;
    // $request->Options->Option[2]= $opt3;

    $request->Method = $_POST['ddlMethod'];
    $request->TransactionType = $_POST['ddlTransactionType'];
    $request->CustomerIP = getUserIP();

    // Call RapidAPI
    $eway_params = array();
    if ($_POST['ddlSandbox']) {
        $eway_params['sandbox'] = true;
    }
    $service = new eWAY\RapidAPI($api_key, $api_password, $eway_params);
    $result = $service->DirectPayment($request);
	// print_r($result);
	// exit();


	$eq = "RESPONSE CODE: ".$result -> ResponseCode." | AUTHORIZATION CODE: ".$result->AuthorisationCode." | EMAIL: ".$_POST['email_address']." | NAME: ".$_POST['first_name']." ".$_POST['last_name']." | ERRORS: ".$result->Errors;
	logeway($eq);


	if(($result -> ResponseCode !== "00") && ($result -> ResponseCode !== "08") == true)  { 
		$_SESSION['decline_message'] = _checkout_card_declined_." ".$result -> ResponseMessage;
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";
		exit();

    // Check if any error returns
    } elseif (isset($result->Errors)) {
        // Get Error Messages from Error Code.
        $ErrorArray = explode(",", $result->Errors);
        $lblError = "";
        foreach ( $ErrorArray as $error ) {
            $error = $service->getMessage($error);
            $lblError .= $error . "<br />\n";
        }
		$_SESSION['decline_message'] = _checkout_card_declined_." - ".$error;
		include $setup['path']."/sy-inc/store/payment/payment-declined.php";
		exit();
    } else {


			$order_pay_type =  $_POST['c_typepj']; // paypal, credit cart
			$order_total_pay = $_POST['grand_total'];
			$payment_amount = $_POST['grand_total'];
			$payment_amout = $order_total_pay;
			$sub_total = $_REQUEST['sub_total'];
			$order_fees = "";
			$currency = $currency_code;
			$transaction_id =  $result->AuthorisationCode;
			$order_ship_amount = $_REQUEST['shipping_price'];
			$shipping_option = $_REQUEST['ship_select'];
			$order_tax = $_REQUEST['tax_price'];
			$tax_percentage = $_REQUEST['tax_percentage'];
			$vat = $_REQUEST['vat_price'];
			$vat_percentage = $_REQUEST['vat_percentage'];
			$taxable_amount = $_REQUEST['taxable_amount'];
			$order_discount = $_POST['discount_amount'];
			$order_message  = $_POST['customer_message'];
			$order_pay_type = $response['dc_card_brand'];
			$order_payment_status = "Completed";
			$order_pending_reason = $_POST['pending_reason'];
			$pay_option = "eway";
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email_address = $_POST['email_address'];
			$company_name = $_POST['business_name'];
			$country = $_POST['country'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			$address_status = $_POST['address_status'];
			$address = $_POST['address'];
			$phone = $_POST['order_phone'];
			$order_session = $_SESSION['ms_session'];
			$ship_business = $_POST['ship_business'];
			$ship_first_name = $_POST['ship_first_name'];
			$ship_last_name =  $_POST['ship_last_name'];
			$ship_address  = $_POST['ship_address'];
			$ship_city = $_POST['ship_city'];
			$ship_state = $_POST['ship_state'];
			$ship_zip = $_POST['ship_zip'];
			$ship_country = $_POST['ship_country'];
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$coupon_id = $_REQUEST['coupon_id'];
			$coupon_name = $_REQUEST['coupon_name'];
			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			$credit_amount = $_REQUEST['credit_amount'];
			$order_eb_discount = $_REQUEST['eb_amount'];
			$customer_id = $person['p_id'];
			$order_notes = $_POST['order_notes'];
			$order_extra_field_1 = $_REQUEST['order_extra_field_1'];
			$order_extra_val_1 = $_REQUEST['order_extra_val_1'];
			$order_extra_field_2 = $_REQUEST['order_extra_field_2'];
			$order_extra_val_2 = $_REQUEST['order_extra_val_2'];
			$order_extra_field_3 = $_REQUEST['order_extra_field_3'];
			$order_extra_val_3 = $_REQUEST['order_extra_val_3'];
			$order_extra_field_4 = $_REQUEST['order_extra_field_4'];
			$order_extra_val_4 = $_REQUEST['order_extra_val_4'];
			$order_extra_field_5 = $_REQUEST['order_extra_field_5'];
			$order_extra_val_5 = $_REQUEST['order_extra_val_5'];

			if($_REQUEST['order_id'] > 0) { 
				$order_id = $_REQUEST['order_id'];
				include $setup['path']."/sy-inc/store/payment/payment-complete-pay-invoice.php";
			} else { 
				include $setup['path']."/sy-inc/store/payment/payment-complete.php";
				createOrder();
			}

		exit();


        $in_page = 'view_result';
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>eWAY Rapid Direct Connection Demo</title>
    <link href="../assets/Styles/Site.css" rel="stylesheet" type="text/css" />
    <link href="../assets/Styles/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" />
    <script src="../assets/Scripts/jquery-1.4.4.min.js" type="text/javascript"></script>
    <script src="../assets/Scripts/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
    <script src="../assets/Scripts/jquery.ui.datepicker-en-GB.js" type="text/javascript"></script>
    <script type="text/javascript" src="../assets/Scripts/tooltip.js"></script>
</head>
<body>
    <form method="POST">
    <center>
        <div id="outer">
            <div id="toplinks">
                <img alt="eWAY Logo" class="logo" src="../assets/Images/companylogo.gif" width="960px" height="65px" />
            </div>
            <div id="main">

<?php
    // Display Results Page
    if ($in_page === 'view_result') {
?>

    <div id="titlearea">
        <h2>Sample Response</h2>
        <br><br>
        <b>By default the eWAY Sandbox response will be based on the cents value. <a href="http://go.eway.io/s/article/I-m-testing-in-sandbox-why-are-my-payments-declined" target="_blank">More information</a></b>
    </div>

    <div id="maincontent">
        <div class="response">
            <div class="fields">
                <label for="lblAuthorisationCode">
                    Authorisation Code</label>
                <label id="lblAuthorisationCode"><?php echo isset($result->AuthorisationCode) ? $result->AuthorisationCode:""; ?></label>
            </div>
            <div class="fields">
                <label for="lblInvoiceNumber">
                    Invoice Number</label>
                <label id="lblInvoiceNumber"><?php echo $result->Payment->InvoiceNumber; ?></label>
            </div>
            <div class="fields">
                <label for="lblInvoiceReference">
                    Invoice Reference</label>
                <label id="lblInvoiceReference"><?php echo $result->Payment->InvoiceReference; ?></label>
            </div>
            <div class="fields">
                <label for="lblResponseCode">
                    Response Code</label>
                <label id="lblResponseCode"><?php echo $result->ResponseCode; ?></label>
            </div>
            <div class="fields">
                <label for="lblResponseMessage">
                    Response Message</label>
                <label id="lblResponseMessage">
                 <?php
                        if (isset($result->ResponseMessage)) {
                            //Get Error Messages from Error Code
                            $ResponseMessageArray = explode(",", $result->ResponseMessage);
                            $responseMessage = "";
                            foreach ($ResponseMessageArray as $message) {
                                $real_message = $service->getMessage($message);
                                if ($message != $real_message)
                                    $responseMessage .= $message . " " . $real_message . "<br>";
                                else
                                    $responseMessage .= $message;
                            }
                            echo $responseMessage;
                        }
                 ?>
                </label>
            </div>
            <div class="fields">
                <label for="lblTokenCustomerID">
                    TokenCustomerID
                </label>
                <label id="lblTokenCustomerID"><?php
                    if (isset($result->Customer->TokenCustomerID)) {
                            echo $result->Customer->TokenCustomerID;
                    }
                ?></label>
            </div>
            <div class="fields">
                <label for="lblTotalAmount">
                    Total Amount</label>
                <label id="lblTotalAmount"><?php
                    if (isset($result->Payment->TotalAmount)) {
                        $amount = $result->Payment->TotalAmount;
                        echo '$'.number_format($amount/100, 2);
                    }
                ?></label>
            </div>
            <div class="fields">
                <label for="lblTransactionID">
                    TransactionID</label>
                <label id="lblTransactionID"><?php
                    if (isset($result->TransactionID)) {
                            echo $result->TransactionID;
                    }
                ?></label>
            </div>
            <div class="fields">
                <label for="lblTransactionStatus">
                    Transaction Status</label>
                <label id="lblTransactionStatus"><?php
                    if (isset($result->TransactionStatus) && $result->TransactionStatus && (is_bool($result->TransactionStatus) || $result->TransactionStatus != "false")) {
                        echo 'True';
                    } else {
                        echo 'False';
                    }
                ?></label>
            </div>
            <div class="fields">
                <label for="lblBeagleScore">
                    Beagle Score</label>
                <label id="lblBeagleScore"><?php
                    if (isset($result->BeagleScore)) {
                        echo $result->BeagleScore;
                    }
                ?></label>
            </div>
        </div>
    </div>

        <br />
        <br />
        <a href="index.php">[Start Over]</a>
        <br />
        <br />
        
        <a href="#" id="showraw">[Show raw request & response]</a>
        
        <div id="raw">
            <div style="width: 45%; margin-right: 2em; background: #f3f3f3; float:left; overflow: scroll; white-space: nowrap;">
                <?php echo $service->getLastUrl(); ?><br>
                <pre id="request_dump"></pre>
            </div>
            <div style="width: 45%; margin-right: 2em; background: #f3f3f3; float:left; overflow: scroll; white-space: nowrap;"><pre id="response_dump"></pre></div>
        </div>
        <script>
            jQuery('#raw').hide();
            var request_dump = JSON.stringify(JSON.parse('<?php echo $service->getLastRequest(); ?>'), null, 4); 
            jQuery('#request_dump').html(request_dump);
            var response_dump = JSON.stringify(JSON.parse('<?php echo $service->getLastResponse(); ?>'), null, 4); 
            jQuery('#response_dump').html(response_dump);
            
            jQuery( "#showraw" ).click(function() {     
                if(jQuery('#raw:visible').length)
                    jQuery('#raw').hide();
                else
                    jQuery('#raw').show();        
            });
         </script>
        
    <div id="maincontentbottom">
    </div>

<?php
    // Display payment form
    } else {
?>

    <div id="titlearea">
        <h2>Sample Merchant Page</h2>
    </div>
<?php
    if (isset($lblError)) {
?>
    <div id="error">
        <label style="color:red"><?php echo $lblError ?></label>
    </div>
<?php } ?>
    <div id="maincontent">
        <div class="transactioncustomer">
            <div class="header first">
                Request Options
            </div>
            <div class="fields">
                <label for="APIKey">API Key</label>
                <input id="APIKey" name="APIKey" type="text" value="<?php print $api_key;?>" />
            </div>
            <div class="fields">
                <label for="APIPassword">API Password</label>
                <input id="APIPassword" name="APIPassword" type="password" value="<?php print $api_password;?>"/>
            </div>
            <div class="fields">
                <label for="ddlSandbox">API Sandbox</label>
                <select id="ddlSandbox" name="ddlSandbox">
                <option value="1" selected="selected">Yes</option>
                <option value="">No</option>
                </select>
            </div>
            <div class="fields">
                <label for="ddlMethod">Payment Method</label>
                <select id="ddlMethod" name="ddlMethod" style="width: 140px" onchange="onMethodChange(this.options[this.options.selectedIndex].value)">
                    <option value="ProcessPayment">ProcessPayment</option>
                    <option value="TokenPayment">TokenPayment</option>
                    <option value="CreateTokenCustomer">CreateTokenCustomer</option>
                    <option value="UpdateTokenCustomer">UpdateTokenCustomer</option>
                    <option value="Authorise">Authorise</option>
                </select>
            </div>
            <script>
                function onMethodChange(v) {
                    if (v == 'ProcessPayment' || v == 'Authorise' || v == 'TokenPayment') {
                        jQuery('#payment_details').show();
                    } else {
                        jQuery('#payment_details').hide();
                    }
                }
            </script>

          <div id='payment_details'>
            <div class="header">
                Payment Details
            </div>
            <div class="fields">
                <label for="txtAmount">Amount &nbsp;<img src="../assets/Images/question.gif" alt="Find out more" id="amountTipOpener" border="0" /></label>
                <input id="txtAmount" name="txtAmount" type="text" value="100" />
            </div>
            <div class="fields">
                <label for="txtCurrencyCode">Currency Code </label>
                <input id="txtCurrencyCode" name="txtCurrencyCode" type="text" value="AUD" />
            </div>
            <div class="fields">
                <label for="txtInvoiceNumber">Invoice Number</label>
                <input id="txtInvoiceNumber" name="txtInvoiceNumber" type="text" value="Inv 21540" />
            </div>
            <div class="fields">
                <label for="txtInvoiceReference">Invoice Reference</label>
                <input id="txtInvoiceReference" name="txtInvoiceReference" type="text" value="513456" />
            </div>
            <div class="fields">
                <label for="txtInvoiceDescription">Invoice Description</label>
                <input id="txtInvoiceDescription" name="txtInvoiceDescription" type="text" value="Individual Invoice Description" />
            </div>
            <!-- <div class="header">
                Custom Fields
            </div>
            <div class="fields">
                <label for="txtOption1">Option 1</label>
                <input id="txtOption1" name="txtOption1" type="text" value="Option1" />
            </div>
            <div class="fields">
                <label for="txtOption2">Option 2</label>
                <input id="txtOption2" name="txtOption2" type="text" value="Option2" />
            </div>
            <div class="fields">
                <label for="txtOption3">Option 3</label>
                <input id="txtOption3" name="txtOption3" type="text" value="Option3" />
            </div> -->
          </div> <!-- end for <div id='payment_details'> -->
        </div>
        <div class="transactioncard">
            <div class="header first">
                Customer Details
            </div>
            <div class="fields">
                <label for="txtTokenCustomerID">Token Customer ID &nbsp;<img src="../assets/Images/question.gif" alt="Find out more" id="tokenCustomerTipOpener" border="0" /></label>
                <input id="txtTokenCustomerID" name="txtTokenCustomerID" type="text" />
            </div>
            <div class="fields">
                <label for="ddlTitle">Title</label>
                <select id="ddlTitle" name="ddlTitle">
                <option></option>
                <option value="Mr." selected="selected">Mr.</option>
                <option value="Miss">Miss</option>
                <option value="Mrs.">Mrs.</option>
                </select>
            </div>
            <div class="fields">
                <label for="txtCustomerRef">Customer Reference</label>
                <input id="txtCustomerRef" name="txtCustomerRef" type="text" value="A12345" />
            </div>
            <div class="fields">
                <label for="txtFirstName">First Name</label>
                <input id="txtFirstName" name="txtFirstName" type="text" value="John" />
            </div>
            <div class="fields">
                <label for="txtLastName">Last Name</label>
                <input id="txtLastName" name="txtLastName" type="text" value="Doe" />
            </div>
            <div class="fields">
                <label for="txtCompanyName">Company Name</label>
                <input id="txtCompanyName" name="txtCompanyName" type="text" value="WEB ACTIVE" />
            </div>
            <div class="fields">
                <label for="txtJobDescription">Job Description</label>
                <input id="txtJobDescription" name="txtJobDescription" type="text" value="Developer" />
            </div>
            <div class="header">
                Customer Address
            </div>
            <div class="fields">
                <label for="txtStreet">Street</label>
                <input id="txtStreet" name="txtStreet" type="text" value="15 Smith St" />
            </div>
            <div class="fields">
                <label for="txtCity">City</label>
                <input id="txtCity" name="txtCity" type="text" value="Phillip" />
            </div>
            <div class="fields">
                <label for="txtState">State</label>
                <input id="txtState" name="txtState" type="text" value="ACT" />
            </div>
            <div class="fields">
                <label for="txtPostalcode">Post Code</label>
                <input id="txtPostalcode" name="txtPostalcode" type="text" value="2602" />
            </div>
            <div class="fields">
                <label for="txtCountry">Country</label>
                <input id="txtCountry" name="txtCountry" type="text" value="au" maxlength="2" />
            </div>
            <div class="fields">
                <label for="txtEmail">Email</label>
                <input id="txtEmail" name="txtEmail" type="text" value="" />
            </div>
            <div class="fields">
                <label for="txtPhone">Phone</label>
                <input id="txtPhone" name="txtPhone" type="text" value="1800 10 10 65" />
            </div>
            <div class="fields">
                <label for="txtMobile">Mobile</label>
                <input id="txtMobile" name="txtMobile" type="text" value="1800 10 10 65" />
            </div>
            <div class="fields">
                <label for="txtFax">Fax</label>
                <input id="txtFax" name="txtFax" type="text" value="02 9852 2244" />
            </div>
            <div class="fields">
                <label for="txtUrl">Website</label>
                <input id="txtUrl" name="txtUrl" type="text" value="http://www.yoursite.com" />
            </div>
            <div class="fields">
                <label for="txtComments">Comments</label>
                <textarea id="txtComments" name="txtComments"/>Some comments here</textarea>
            </div>
            <div class="header">
                Customer Card Details
            </div>
            <div class="fields">
                <label for="txtCardName">
                    Card Holder</label>
                <input type='text' name='txtCardName' id='txtCardName' value="TestUser" />
            </div>
            <div class="fields">
                <label for="txtCardNumber">
                    Card Number</label>
                <input type='text' name='txtCardNumber' id='txtCardNumber' value="4444333322221111" />
            </div>
            <div class="fields">
                <label for="ddlCardExpiryMonth">
                    Expiry Date</label>
                <select ID="ddlCardExpiryMonth" name="ddlCardExpiryMonth">
                    <?php
                        $expiry_month = date('m');
                        for($i = 1; $i <= 12; $i++) {
                            $s = sprintf('%02d', $i);
                            echo "<option value='$s'";
                            if ( $expiry_month == $i ) {
                                echo " selected='selected'";
                            }
                            echo ">$s</option>\n";
                        }
                    ?>
                </select>
                /
                <select ID="ddlCardExpiryYear" name="ddlCardExpiryYear">
                    <?php
                        $i = date("y");
                        $j = $i+11;
                        for ($i; $i <= $j; $i++) {
                            echo "<option value='$i'>$i</option>\n";
                        }
                    ?>
                </select>
            </div>
            <div class="fields">
                <label for="ddlStartMonth">
                    Valid From Date</label>
                <select ID="ddlStartMonth" name="ddlStartMonth">
                    <?php
                        $expiry_month = "";//date('m');
                        echo  "<option></option>";

                        for($i = 1; $i <= 12; $i++) {
                            $s = sprintf('%02d', $i);
                            echo "<option value='$s'";
                            if ( $expiry_month == $i ) {
                                echo " selected='selected'";
                            }
                            echo ">$s</option>\n";
                        }
                    ?>
                </select>
                /
                <select ID="ddlStartYear" name="ddlStartYear">
                    <?php
                        $i = date("y");
                        $j = $i-11;
                        echo  "<option></option>";
                        for ($i; $i >= $j; $i--) {
                            $year = sprintf('%02d', $i);
                            echo "<option value='$year'>$year</option>\n";
                        }
                    ?>
                </select>
            </div>
            <div class="fields">
                <label for="txtIssueNumber">
                    Issue Number</label>
                <input type='text' name='txtIssueNumber' id='txtIssueNumber' value="22" maxlength="2" style="width:40px;"/> <!-- This field is optional but highly recommended -->
            </div>
            <div class="fields">
                <label for="txtCVN">
                    CVN</label>
                <input type='text' name='txtCVN' id='txtCVN' value="123" maxlength="4" style="width:40px;"/> <!-- This field is optional but highly recommended -->
            </div>
            <div class="header">
                Others
            </div>
            <div class="fields">
                <label for="ddlTransactionType">Transaction Type</label>
                <select id="ddlTransactionType" name="ddlTransactionType" style="width:140px;">
                <option value="Purchase">Ecommerce</option>
                <option value="MOTO">MOTO</option>
                <option value="Recurring">Recurring</option>
                </select>
            </div>
        </div>
        <div class="button">
            <br />
            <br />
            <input type="submit" id="btnSubmit" name="btnSubmit" value="Make Payment" />
        </div>
    </div>
    <div id="maincontentbottom">
    </div>
    <div id="amountTip" style="font-size: 8pt !important">
        The amount in cents. For example for an amount of $1.00, enter 100
    </div>
    <div id="tokenCustomerTip" style="font-size: 8pt !important">
        If this field has a value, the details of an existing customer will be loaded when the request is sent.
    </div>
    <div id="saveTokenTip" style="font-size: 8pt !important">
        If this field is checked, the details in the customer fields will be used to either create a new token customer, or (if Token Customer ID has a value) update an existing customer.
    </div>

<?php
    }
?>
            </div>
            <div id="footer"></div>
        </div>
    </center>
    </form>

</body>
</html>
