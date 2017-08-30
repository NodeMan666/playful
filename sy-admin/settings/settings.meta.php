<?php 
define("this_do", "settings");
// $ef['cancel_link'] = "manage.php?do=".this_do."&viewPackage=".$_REQUEST['package_id']."";



$ef['encrypt_password_pass'] = "Tim2c0Ol"; // password for aes encryption in the mysql

// print "<li>id: ".$test['id']." - pass: ".$test['ftp_pass']."";



$ef['page_title'] = "<div id=\"pageTitle\">".settingsTree()." ".ai_sep." Meta Data</div>";
$ef['page_text'] = "Your MetaData is not visible on your pages, but is used for search engines.  ";
// $ef['new_message'] = "Fill out the form below to add a new package";
// $ef['edit_message'] = "Use the form below to edit this package";
$ef['table'] = "ms_settings";
$ef['id'] = "id";
$ef['do'] = "".this_do."";
$ef['eaction'] = "action";
$ef['action'] = "meta";
$ef['totalspan'] = "4";
$ef['no_id_form'] = 1;

$ef['new_button'] = "Submit informaiton";
$ef['save_button'] = "Save changes";
$ef['md5_id'] = "0"; // whether or not to encrypt the ID password in the success strin
$ef['success_url'] = "index.php?do=settings&action=meta";
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
	"name" => "Meta title", 
	"field" => "meta_title", 
	"required" => "1",
	"type" => "text",
	"error_message" => " ",
	"colspan" => "1",
	"size" => "30",
	"notes2" => "The Meta Title is be shown at the top of the browser. This should be your website name or something similar.",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Separate meta title character", 
	"field" => "sep_meta_title", 
	"required" => "1",
	"type" => "text",
	"error_message" => " ",
	"colspan" => "1",
	"size" => "2",
	"notes2" => "This separates the page title / category / site name for the meta title. Use something like a dash or | ",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Site name in meta title", 
	"field" => "meta_site_name_location", 
	"required" => "0",
	"type" => "radio",
	"options" => "0|Before page titles,1|After page titles",
	"colspan" => "3",
	"size" => "2",
	"notes2" => " ",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Meta Description", 
	"field" => "meta_descr", 
	"required" => "0",
	"type" => "textarea",
	"error_message" => " ",
	"colspan" => "3",
	"size" => "30",
	"notes2" => "This could be a couple of sentences of description about your site.",
	"td_width" => "25%",
	"end" => ""
	),



	array(
	"name" => "Meta Keywords", 
	"field" => "meta_keys", 
	"required" => "0",
	"type" => "textarea",
	"colspan" => "3",
	"size" => "20",
	"notes2" => "Enter in keywords or phrases separated by a comma. Think about what someone might type in a search engine to find you. Keep it under 200 characters.",
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
