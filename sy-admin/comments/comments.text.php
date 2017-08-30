<?php 
define("this_do", "comments");
// $ef['cancel_link'] = "manage.php?do=".this_do."&viewPackage=".$_REQUEST['package_id']."";



$ef['encrypt_password_pass'] = "Tim2c0Ol"; // password for aes encryption in the mysql

// print "<li>id: ".$test['id']." - pass: ".$test['ftp_pass']."";



$ef['page_title'] = "<div id=\"pageTitle\"><a href=\"index.php?do=comments\">Comments</a>".ai_sep." Page Text</div>";
$ef['page_text'] = "This is the text on the page for the blog comments section.";
// $ef['new_message'] = "Fill out the form below to add a new package";
// $ef['edit_message'] = "Use the form below to edit this package";
$ef['table'] = "ms_language";
$ef['id'] = "id";
$ef['do'] = "".this_do."";
$ef['eaction'] = "view";
$ef['action'] = "text";
$ef['totalspan'] = "4";
$ef['no_id_form'] = 1;

$ef['new_button'] = "Submit informaiton";
$ef['save_button'] = "Save changes";
$ef['md5_id'] = "0"; // whether or not to encrypt the ID password in the success strin
$ef['success_url'] = "index.php?do=comments&view=text";
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
	"name" => "Title", 
	"field" => "_leave_comment_title_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"colspan" => "3",
	"end" => ""
	),
	array(
	"name" => "Description", 
	"field" => "_leave_comment_text_", 
	"required" => "0",
	"type" => "textarea",
	"size" => "40",
	"notes1" => "",
	"colspan" => "3",
	"td_width" => "25%",
	"end" => ""
	),



	array(
	"name" => "Your Name", 
	"field" => "_leave_comment_name_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),
	array(
	"name" => "Your Email", 
	"field" => "_leave_comment_email_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),
	
	array(
	"name" => "Your Website Address", 
	"field" => "_leave_comment_website_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),
	array(
	"name" => "Your Comment", 
	"field" => "_leave_comment_comment_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Submit Button", 
	"field" => "_leave_comment_button_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),
	array(
	"name" => "Word for \"Comments\"", 
	"field" => "_comments_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Word for \"Comment\"", 
	"field" => "_comment_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Word for \"No Comments\"", 
	"field" => "_no_comments_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),


	array(
	"name" => "Comment posted / pending", 
	"field" => "_leave_comment_success_message_", 
	"required" => "0",
	"type" => "textarea",
	"size" => "40",
	"notes1" => "",
	"colspan" => "3",
	"td_width" => "25%",
	"end" => ""
	),
	array(
	"name" => "Comment posted / automatically approved", 
	"field" => "_leave_comment_approved_message_", 
	"required" => "0",
	"type" => "textarea",
	"size" => "40",
	"notes1" => "",
	"colspan" => "3",
	"td_width" => "25%",
	"end" => ""
	),


	array(
	"name" => "Who has commented", 
	"field" => "_leave_comment_comments_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
	"notes1" => "",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "No comments message", 
	"field" => "_leave_comment_no_comments_", 
	"required" => "0",
	"type" => "text",
	"size" => "40",
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
