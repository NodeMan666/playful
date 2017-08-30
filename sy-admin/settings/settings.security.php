<?php 
define("this_do", "settings");
// $ef['cancel_link'] = "manage.php?do=".this_do."&viewPackage=".$_REQUEST['package_id']."";



$ef['encrypt_password_pass'] = "Tim2c0Ol"; // password for aes encryption in the mysql

// print "<li>id: ".$test['id']." - pass: ".$test['ftp_pass']."";



$ef['page_title'] = "<div id=\"pageTitle\">".settingsTree()." ".ai_sep." Security</div>";
$ef['page_text'] = "This section gives you the ability of blocking visitors by IP address or from visiting from certain websites.";
// $ef['new_message'] = "Fill out the form below to add a new package";
// $ef['edit_message'] = "Use the form below to edit this package";
$ef['table'] = "ms_security";
$ef['id'] = "id";
$ef['do'] = "".this_do."";
$ef['eaction'] = "action";
$ef['action'] = "security";
$ef['totalspan'] = "4";
$ef['no_id_form'] = 1;

$ef['new_button'] = "Save Changes";
$ef['save_button'] = "Save changes";
$ef['md5_id'] = "0"; // whether or not to encrypt the ID password in the success strin
$ef['success_url'] = "index.php?do=settings&action=security";
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
	"name" => "Block IP addresses", 
	"field" => "block_ips", 
	"required" => "0",
	"type" => "textarea",
	"error_message" => " ",
	"colspan" => "3",
	"size" => "30",
	"rows" => "10",
	"notes1" => "If you want to block certain IP addresses from accessing your website, enter in the IP addresses below, 1 per line.",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Block referring domains", 
	"field" => "block_reffers", 
	"required" => "0",
	"type" => "textarea",
	"error_message" => " ",
	"rows" => "10",
	"colspan" => "3",
	"size" => "30",
	"notes1" => "This will block people that are coming to your website from certain domain names. You will need to enter the domain names 1 per line and without the www.. like: blocksite.com.<br><br>Example, if you want to block refferals from google and yahoo, enter in like this: <br>google.com<br>yahoo.com",
	"td_width" => "25%",
	"end" => ""
	),




	array(
	"name" => "Blocked message", 
	"field" => "block_message", 
	"required" => "0",
	"type" => "textarea",
	"error_message" => " ",
	"colspan" => "3",
	"size" => "30",
	"notes1" => "This is the message they will see instead of your website if they are blocked.",
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
