<?php 
define("this_do", "settings");
// $ef['cancel_link'] = "manage.php?do=".this_do."&viewPackage=".$_REQUEST['package_id']."";



$ef['encrypt_password_pass'] = "Tim2c0Ol"; // password for aes encryption in the mysql

// print "<li>id: ".$test['id']." - pass: ".$test['ftp_pass']."";



$ef['page_title'] = "<div id=\"pageTitle\">".settingsTree()." ".ai_sep." Site Status</div>";
$ef['page_text'] = "Here you can open and close your website.  When you close it YOU will still be able to view the admin when you are logged into the admin, but others will see the message below.";
// $ef['new_message'] = "Fill out the form below to add a new package";
// $ef['edit_message'] = "Use the form below to edit this package";
$ef['table'] = "ms_settings";
$ef['id'] = "id";
$ef['do'] = "".this_do."";
$ef['eaction'] = "action";
$ef['action'] = "status";
$ef['totalspan'] = "4";
$ef['no_id_form'] = 1;

$ef['new_button'] = "Submit informaiton";
$ef['save_button'] = "Save changes";
$ef['md5_id'] = "0"; // whether or not to encrypt the ID password in the success strin
$ef['success_url'] = "index.php?do=settings&action=status";
$ef['success_message'] = "Settings have been saved";
$ef['success_replace'] = "package_name";
$ef['success_page_title'] = "Thank you [name]";
$ef['success_page_text'] = "";
$ef['success_page_replace'] = "name,email";
$ef['defaults'] = "folder_name|photocart,package_no_tax|0";
$ef['add_captcha'] = "0"; // 1 or 0
$ef['captcha_font_color'] = "#a9a9a9"; // 1 or 0
$ef['captcha_bg_color'] = "#292929"; // 1 or 0
$ef['add_captcha_text'] = "Enter the verification code";
$ef['captcha_error_message'] = "The code you entered did not match.";
// $ef['date_fields']= "cust_date_added";  // This is for fields that are in date format.
$ef['error_check'] = "0";
$ef['required_field'] = "(This is a required field)";

// $ef['links'] = "";
// $ef['add_form_function'] = selectForm();
/*
$ef['add_form_submit_function'] = "doCustomerCats";

$cust_cat_options = "";
$cust_cats = whileSQL("office_customer_cats", "*", "ORDER BY cat_name ASC");
while($tcat = mysqli_fetch_array($cust_cats)) {
	if($tc > 0) {
		$cust_cat_options .= ",";
	}
	$tc++;
	$cust_cat_options .= "".$tcat['cat_id']."|".$tcat['cat_name']."";
}

*/
/* 
notes1: Notes under field name
notes2: Notes under textfield
*/


$efCols = array(

	array(
	"name" => "Status", 
	"field" => "store_status", 
	"required" => "0",
	"type" => "radio",
	"size" => "6",
	"colspan" => "3",
	"notes2" => "",
	"td_width" => "25%",
	"options" => "0|Open,1|Closed",
	"end" => ""
	),

	array(
	"name" => "Closed Message", 
	"field" => "store_status_message", 
	"required" => "0",
	"type" => "textarea",
	"error_message" => " ",
	"colspan" => "3",
	"size" => "30",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),







	

);

if(!empty($_REQUEST['complete'])) {
	print successForm($ef);
} else {
	print dataForm($ef,$efCols);
}

?>
