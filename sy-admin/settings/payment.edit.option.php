<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
$payopt = doSQL("ms_payment_options", "*", "WHERE pay_option='".$_REQUEST['pay_option']."' ");
?>
<div id="pageTitle"><a href="index.php?do=settings&action=checkout">Checkout & Payment Options</a> > <?php print $payopt['pay_name'];?></div>
<div>&nbsp;</div>
<div id="roundedFormContain">

<?php 

// $ef['cancel_link'] = "index.php?do=settings&action=checkout";
$ef['new_message'] = "Fill out the form below to add a new product";
$ef['edit_message'] = "".nl2br($payopt['pay_descr'])."";
$ef['table'] = "ms_payment_options";
$ef['id'] = "pay_option";
$ef['do'] = "settings";
$ef['eaction'] = "action";
$ef['action'] = "editPaymentOption";
$ef['totalspan'] = "4";
$ef['new_button'] = "Add new product";
$ef['save_button'] = "Save changes";

$ef['success_url'] = "index.php?do=settings&action=editPaymentOption&pay_option=".$_REQUEST['pay_option']."";
$ef['success_message'] = "Payment option has been saved";
$ef['success_replace'] = "pay_name";
$ef['defaults'] = "sp_group|".$group['pg_id'].",sp_dl|0,sp_no_tax|0,sp_add_ship|0.00";
// $ef['defaults'] = "faq_status|1,faq_name|TIMS!";
// $ef['links'] = "";
$ef['error_check'] = 0;


/*  ########### AUTHORIZE SIM ##################### */
if($_REQUEST['pay_option'] == "authorizenetsim") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Authorize.net Login ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "Transaction Key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),

		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Use as Emulator", 
		"field" => "pay_emulator", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you have a merchant company that has a authorize.net emulator, you can enter in their emulator URL here. Otherwise leave blank",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

if($_REQUEST['pay_option'] == "sisow") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Merchant ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "Merchant Key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "30",
		"end" => ""
		),

		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

######## APCO ############### 
if($_REQUEST['pay_option'] == "apco") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Authorize.net Login ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "Transaction Key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),

		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Use as Emulator", 
		"field" => "pay_emulator", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you have a merchant company that has a authorize.net emulator, you can enter in their emulator URL here. Otherwise leave blank",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### STRIPE ##################### */
if($_REQUEST['pay_option'] == "stripe") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Publishable Key", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "30",
		"end" => ""
		),

		array(
		"name" => "Secret Key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "30",
		"end" => ""
		),


		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),
		array(
		"name" => "Credit cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. Leave blank to not display a credit card dropdown list. ",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### SQUARE ##################### */
if($_REQUEST['pay_option'] == "square") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Application ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"end" => ""
		),

		array(
		"name" => "Access Token", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "40",
		"end" => ""
		),


		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),
	/*
		array(
		"name" => "Credit cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. Leave blank to not display a credit card dropdown list. ",
		"end" => ""
		),
*/
		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### AUTHORIZE AIM ##################### */
if($_REQUEST['pay_option'] == "authorizenetaim") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Authorize.net Login ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "Transaction Key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),

		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),
		array(
		"name" => "Credit cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Use as Emulator", 
		"field" => "pay_emulator", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you have a merchant company that has a authorize.net emulator, you can enter in their emulator URL here. Otherwise leave blank",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### PAYPAL STANDARD ##################### */
if($_REQUEST['pay_option'] == "paypalstandard") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Your PayPal Email Address", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "50",
		"colspan" => "3",
		"notes2" => "This is your PayPal account log in email address or another email address you have added to your PayPal account.",
		"end" => ""
		),

		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### PAYPAL EXPORESS ##################### */
if($_REQUEST['pay_option'] == "paypalexpress") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "PayPal API Username", 
		"field" => "pay_email", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"end" => ""
		),

		array(
		"name" => "PayPal API Password", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"colspan" => "3",
		"end" => ""
		),



		array(
		"name" => "PayPal Signature", 
		"field" => "pay_key", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown below the payment button.",
		"end" => ""
		),
		array(
		"name" => "If customer purchasing non-shipping items, require address (shipping)", 
		"field" => "pay_express_download_address", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "If the customer is only purchasing non-shipping items (downloads, services), PayPal Express will not pass any address information ... only name and email. If you still want to capture an address, check this box and it will return their \"shipping\" address.",
		"end" => ""
		),




	);
}
/*  ########### EMAIL PAYMENT FORM ##################### */
if($_REQUEST['pay_option'] == "emailform") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
			"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Email address to send credit card information to", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "50",
		"colspan" => "3",
		"notes2" => "Half of the credit card information will be sent to this email address",
		"end" => ""
		),



		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Credit cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
			"colspan" => "3",
	"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"colspan" => "3",
		"end" => ""
		),



		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Order pending description shown on order & in order email sent to customer.", 
		"field" => "pay_offline_descr", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "6",
		"notes1" => " This is shown on the order when the order has not been marked paid.",
		"colspan" => "3",
		"end" => ""
		),


	);
}



/*  ########### TEST ONLYFORM ##################### */
if($_REQUEST['pay_option'] == "testonly") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
			"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
			"colspan" => "3",
		"end" => ""
		),




	);
}



/*  ########### EMAIL PAYMENT FORM UK ##################### */
if($_REQUEST['pay_option'] == "emailformuk") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
			"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Email address to send credit card information to", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "50",
		"colspan" => "3",
		"notes2" => "Half of the credit card information will be sent to this email address",
		"end" => ""
		),

		array(
		"name" => "Credit cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
			"colspan" => "3",
	"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),



		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}



/*  ########### eProcessingNetwork ##################### */
if($_REQUEST['pay_option'] == "eprocessingnetwork") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),


		array(
		"name" => "Account Number", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Restrict key", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "40",
		"end" => ""
		),


		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),

		array(
		"name" => "Payment Page Logo", 
		"field" => "pay_page_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "You can have a logo displayed on the eprocessingnetwork payment page if you like. If you have uploaded a logo, enter in the complete URL here.",
		"end" => ""
		),




		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### EWAY ##################### */
if($_REQUEST['pay_option'] == "eway") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),

		array(
		"name" => "API Key", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "API password ", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),

		array(
		"name" => "Encryption Key ", 
		"field" => "pay_private_key", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "To get your Encryption Key, log into your eWay account, go to My Account -> Client Side Encryption.",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),

		array(
		"name" => "Enable valid from date and issue number (for UK users)", 
		"field" => "pay_issue_date", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "This will display the valid from date and issue number when entering in card details.",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### PAY OFFLINE  ##################### */
if($_REQUEST['pay_option'] == "payoffline") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),




		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),

		array(
		"name" => "Description when placing order", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "6",
		"notes1" => "Enter in your directions for paying offline. This is shown when the customer is placing the order.",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Instructions for paying shown on order", 
		"field" => "pay_offline_descr", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "6",
		"notes1" => "Enter in your directions for paying offline. This is shown on the order when the order has not been marked paid.",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Place order button text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the place order button. ",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### PAY OFFLINE 2  ##################### */
if($_REQUEST['pay_option'] == "payoffline2") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"colspan" => "3",
		"notes2" => "",
		"end" => ""
		),




		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),

		array(
		"name" => "Description when placing order", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "6",
		"notes1" => "Enter in your directions for paying offline. This is shown when the customer is placing the order.",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Instructions for paying shown on order", 
		"field" => "pay_offline_descr", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "6",
		"notes1" => "Enter in your directions for paying offline. This is shown on the order when the order has not been marked paid.",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Place order button text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the place order button. ",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### PAYFAST ##################### */
if($_REQUEST['pay_option'] == "payfast") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out.",
		"end" => ""
		),
		array(
		"name" => "Merchant ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "Merchant KEY", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),

		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### PAYJUNCTION ##################### */
if($_REQUEST['pay_option'] == "payjunction") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "You can run this in test mode to test it out. If in test mode no transactions will actually be processed. <a href=\"http://support.payjunction.com/trinity/support/view.action?knowledgeBase.knbKnowledgeBaseId=323\" target=\"_blank\">Test mode info</a>",
		"end" => ""
		),
		array(
		"name" => "PayJunction API login ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"end" => ""
		),
		array(
		"name" => "PayJunction API password ", 
		"field" => "pay_key", 
		"type" => "text",
		"size" => "20",
		"end" => ""
		),


		array(
		"name" => "Credit Cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}

/*  ########### PAYPAL PRO ##################### */
if($_REQUEST['pay_option'] == "paypalpro") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),
		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "PayPal API Username", 
		"field" => "pay_email", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"end" => ""
		),

		array(
		"name" => "PayPal API Password", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"colspan" => "3",
		"end" => ""
		),



		array(
		"name" => "PayPal Signature", 
		"field" => "pay_key", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"colspan" => "3",
		"end" => ""
		),

		array(
		"name" => "Credit Cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Payment option select text", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you are offering more than one payment option, this is the text to select this payment option.",
		"end" => ""
		),
		array(
		"name" => "Pay option select graphic", 
		"field" => "pay_select_graphic", 
		"type" => "text",
		"size" => "40",
		"notes2" => "If you rather use a graphic to use for this payment option, enter in the path to the graphic. ",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Credit Card Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),

		array(
		"name" => "PayPal Pay Button Text", 
		"field" => "pay_paypal_exp_button", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),


		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}



/*  ########### SECURE PAY ##################### */
if($_REQUEST['pay_option'] == "securepay") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "Merchant ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "20",
		"colspan" => "3",
		"end" => ""
		),



		array(
		"name" => "Credit Cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### USAEPAY ##################### */
if($_REQUEST['pay_option'] == "usaepay") { 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"colspan" => "3",
		"end" => ""
		),


		array(
		"name" => "USA ePay Transaction Key", 
		"field" => "pay_key", 
		"required" => "0",
		"type" => "text",
		"size" => "60",
		"colspan" => "3",
		"end" => ""
		),



		array(
		"name" => "Credit Cards to accept", 
		"field" => "pay_cards", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"colspan" => "3",
		"notes2" => "Enter in the type of credit cards to accept, ONE PER LINE. ",
		"end" => ""
		),


		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}


/*  ########### worldpay ##################### */
if($_REQUEST['pay_option'] == "worldpay") { 
	?>
	<div class="pc"><span class="bold">Important - You also have to configure your WorldPay account for this to work. Follow the numbered steps on the  <a href="http://www.worldpay.com/support/kb/bg/paymentresponse/pr5502.html" target="_blank">Configuring World Pay Payment Response</a> page.</span></div>
<?php 
	$efCols = array(
		array(
		"name" => "Check this box to activate ".$payopt['pay_name']."", 
		"field" => "pay_status", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "Test Mode", 
		"field" => "test_mode", 
		"required" => "0",
		"type" => "checkbox",
		"value" => "1",
		"notes2" => "",
		"end" => ""
		),

		array(
		"name" => "WorldPay Installation ID", 
		"field" => "pay_num", 
		"required" => "0",
		"type" => "text",
		"size" => "60",
		"colspan" => "3",
		"notes2" => "In your WorldPay account, click on Installations to get your installation ID.",
		"end" => ""
		),



		array(
		"name" => "Title for payment option", 
		"field" => "pay_title", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Description", 
		"field" => "pay_description", 
		"required" => "0",
		"type" => "textarea",
		"rows" => "3",
		"notes2" => "This is shown above the payment button.",
		"end" => ""
		),

		array(
		"name" => "Pay Button Text", 
		"field" => "pay_text", 
		"required" => "1",
		"type" => "text",
		"size" => "40",
		"notes2" => "This is the text shown on the actual button that is clicked. ",
		"end" => ""
		),
		array(
		"name" => "Pay Button Graphic", 
		"field" => "pay_button", 
		"required" => "0",
		"type" => "text",
		"size" => "40",
		"notes2" => "If you would like to use a graphic instead of text for the button, enter in the URL for that graphic. ",
		"end" => ""
		),

		array(
		"name" => "Display order", 
		"field" => "pay_order", 
		"required" => "0",
		"type" => "text",
		"size" => "2",
		"notes2" => "If you are using more than one payment method, this display order defines in what order the payment options show. From lowest number, left to right",
		"colspan" => "3",
		"end" => ""
		),



	);
}








print dataForm($ef,$efCols);
?>
</div>