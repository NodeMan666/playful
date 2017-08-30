<?php 
define("this_do", "settings");
// $ef['cancel_link'] = "manage.php?do=".this_do."&viewPackage=".$_REQUEST['package_id']."";



$ef['encrypt_password_pass'] = "Tim2c0Ol"; // password for aes encryption in the mysql

// print "<li>id: ".$test['id']." - pass: ".$test['ftp_pass']."";



$ef['page_title'] = "<div id=pageTitle>".settingsTree()."</span> ".ai_sep." Admin Settings</div>";
$ef['page_text'] = "";
// $ef['new_message'] = "Fill out the form below to add a new package";
// $ef['edit_message'] = "Use the form below to edit this package";
$ef['table'] = "ms_settings";
$ef['id'] = "id";
$ef['do'] = "".this_do."";
$ef['eaction'] = "action";
$ef['action'] = "add";
$ef['totalspan'] = "4";
$ef['no_id_form'] = 1;

$ef['new_button'] = "Submit informaiton";
$ef['save_button'] = "Save changes";
$ef['md5_id'] = "0"; // whether or not to encrypt the ID password in the success strin
$ef['success_url'] = "index.php?do=settings";
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
	"name" => "Website name", 
	"field" => "website_title", 
	"required" => "1",
	"type" => "text",
	"size" => "30",
	"colspan" => "1",
	"notes1" => "The name of your website.",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Email Address", 
	"field" => "contact_email", 
	"required" => "1",
	"type" => "text",
	"colspan" => "1",
	"size" => "30",
	"notes1" => "This is the default email address used to send emails from and order placed emails are sent to.",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"colspan" => "3",
	"name" => "Email address or addresses to send new order email notifications to.", 
	"field" => "order_emails", 
	"required" => "0",
	"type" => "text",
	"size" => "60",
	"notes2" => "",
	"td_width" => "25%",
	"notes2" => "By default, new order email notifications are sent to the email address above. To send to a different or multiple email addresses, enter those below. When entering multiple email addresses, <b>separate with a semi colon (;).</b>. Example, bob@mywebsite.com;walt@mywebsite.com",
	"end" => ""
	),




	array(
	"name" => "Disable Right Click ", 
	"field" => "disable_right_click", 
	"required" => "0",
	"type" => "radio",
	"options" => "0|No,1|Yes - on photos only,2|Yes - on entire page",
	"td_width" => "25%",
	"colspan" => "4",
	"end" => ""
	),

	array(
	"name" => "Replace space in file / folder names with ", 
	"field" => "sep_page_names", 
	"required" => "1",
	"type" => "radio",
	"options" => "_|Underscore (_),-|Dash (-)",
	"td_width" => "25%",
	"colspan" => "4",
	"end" => ""
	),


	array(
	"name" => "Ignore admin in visitor stats", 
	"field" => "stats_ignore_admin", 
	"required" => "0",
	"type" => "radio",
	"options" => "0|No,1|Yes",
	"notes1" => "Setting this to yes the visitor stats will ignore your visits to the website when you are also logged into the admin.",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Show getting started help on admin home page.", 
	"field" => "hide_help", 
	"required" => "0",
	"type" => "radio",
	"options" => "1|No,0|Yes",
	"td_width" => "25%",
	"end" => ""
	),
	array(
	"name" => "Auto check for updates once a day on home page", 
	"field" => "auto_check_updates", 
	"required" => "0",
	"type" => "radio",
	"options" => "0|No,1|Yes",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "Remind me to backup the database once a week", 
	"field" => "backup_reminder", 
	"required" => "0",
	"type" => "radio",
	"options" => "0|No,1|Yes",
	"td_width" => "25%",
	"end" => ""
	),



		array(
	"name" => "", 
	"field" => "f", 
	"nosave" => "1",
	"required" => "0",
	"type" => "spacer",
	"colspan" => "4",
	"notes2" => "<br><span class=\"bold\">The following is your time adjustment and date  / time formatting.</span>",
	"td_width" => "25%",
	"end" => ""
	),

/*
	array(
	"name" => "Time Adjustment", 
	"field" => "time_diff", 
	"required" => "1",
	"type" => "dropdown",
		"colspan" => "3",
"size" => "30",
	"notes2" => "",
	"options" =>		"+24|".date("M d Y h:i a", mktime(date('H')+24,date('i'),date('s'),date('n'),date('d'),date('Y'))).",	+23|".date("M d Y h:i a", mktime(date('H')+23,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+22|".date("M d Y h:i a", mktime(date('H')+22,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+21|".date("M d Y h:i a", mktime(date('H')+21,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+20|".date("M d Y h:i a", mktime(date('H')+20,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+19|".date("M d Y h:i a", mktime(date('H')+19,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+18|".date("M d Y h:i a", mktime(date('H')+18,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+17|".date("M d Y h:i a", mktime(date('H')+17,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+16|".date("M d Y h:i a", mktime(date('H')+16,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+15|".date("M d Y h:i a", mktime(date('H')+15,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+14|".date("M d Y h:i a", mktime(date('H')+14,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+13|".date("M d Y h:i a", mktime(date('H')+13,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+12|".date("M d Y h:i a", mktime(date('H')+12,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+11|".date("M d Y h:i a", mktime(date('H')+11,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+10|".date("M d Y h:i a", mktime(date('H')+10,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+9|".date("M d Y h:i a", mktime(date('H')+9,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+8|".date("M d Y h:i a", mktime(date('H')+8,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+7|".date("M d Y h:i a", mktime(date('H')+7,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+6|".date("M d Y h:i a", mktime(date('H')+6,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+5|".date("M d Y h:i a", mktime(date('H')+5,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+4|".date("M d Y h:i a", mktime(date('H')+4,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+3|".date("M d Y h:i a", mktime(date('H')+3,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+2|".date("M d Y h:i a", mktime(date('H')+2,date('i'),date('s'),date('n'),date('d'),date('Y'))).",+1|".date("M d Y h:i a", mktime(date('H')+1,date('i'),date('s'),date('n'),date('d'),date('Y'))).",0|".date("M d Y h:i a", mktime(date('H'),date('i'),date('s'),date('n'),date('d'),date('Y')))." - Server,-1|".date("M d Y h:i a", mktime(date('H')-1,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-2|".date("M d Y h:i a", mktime(date('H')-2,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-3|".date("M d Y h:i a", mktime(date('H')-3,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-4|".date("M d Y h:i a", mktime(date('H')-4,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-5|".date("M d Y h:i a", mktime(date('H')-5,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-6|".date("M d Y h:i a", mktime(date('H')-6,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-7|".date("M d Y h:i a", mktime(date('H')-7,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-8|".date("M d Y h:i a", mktime(date('H')-8,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-9|".date("M d Y h:i a", mktime(date('H')-9,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-10|".date("M d Y h:i a", mktime(date('H')-10,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-11|".date("M d Y h:i a", mktime(date('H')-11,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-12|".date("M d Y h:i a", mktime(date('H')-12,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-13|".date("M d Y h:i a", mktime(date('H')-13,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-14|".date("M d Y h:i a", mktime(date('H')-14,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-15|".date("M d Y h:i a", mktime(date('H')-15,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-16|".date("M d Y h:i a", mktime(date('H')-16,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-17|".date
("M d Y h:i a", mktime(date('H')-17,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-18|".date("M d Y h:i a", mktime(date('H')-18,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-19|".date("M d Y h:i a", mktime(date('H')-19,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-20|".date("M d Y h:i a", mktime(date('H')-20,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-21|".date("M d Y h:i a", mktime(date('H')-21,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-22|".date("M d Y h:i a", mktime(date('H')-22,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-23|".date("M d Y h:i a", mktime(date('H')-23,date('i'),date('s'),date('n'),date('d'),date('Y'))).",-24|".date("M d Y h:i a", mktime(date('H')-24,date('i'),date('s'),date('n'),date('d'),date('Y')))."",


	"td_width" => "25%",
	"end" => ""
	),
*/
	array(
	"colspan" => "3",
	"name" => "Time Zone: (Current time: ".date('M d, Y H:i:s').") ", 
	"field" => "time_zone", 
	"required" => "1",
	"type" => "text",
	"size" => "40",
	"notes2" => "",
	"td_width" => "25%",
	"notes2" => "<a href=\"http://www.php.net/manual/en/timezones.php\" target=\"_blank\">Here is a complete list of time zones</a>. <br>Eastern Time: <b>America/New_York</b><br>Central Time: <b>America/Chicago</b><br>Mountain Time: <b>America/Denver</b><br>Pacific Time: <b>America/Los_Angeles</b><br>etc... ",
	"end" => ""
	),



	array(
	"name" => "MySQL Date Format", 
	"field" => "date_format", 
	"required" => "1",
	"type" => "text",
	"size" => "20",
	"notes2" => "",
	"td_width" => "25%",
	"notes2" => "<b>%M %e, %Y</b> is default. <br><a href=\"http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html\" target=\"_blank\">MySQL time-date format page</a><br><i>only change these if you know what you are doing</i>",
	"end" => ""
	),

	array(
	"name" => "MySQL Time Format", 
	"field" => "date_time_format", 
	"required" => "1",
	"type" => "text",
	"size" => "20",
	"notes2" => "",
	"td_width" => "25%",
	"notes2" => "%h:%i %p is default",
	"end" => ""
	),


		array(
	"name" => "", 
	"field" => "f", 
	"nosave" => "1",
	"required" => "0",
	"type" => "spacer",
	"colspan" => "4",
	"notes2" => "<br><span class=\"bold\">Create a FTP link from the admin. This will add a FTP link in your admin header to your sy-upload folder. </span>",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"name" => "FTP link to public folder", 
	"field" => "ftp_host_name", 
	"required" => "0",
	"type" => "text",
	"size" => "20",
	"notes2" => "",
	"td_width" => "25%",
	// "notes2" => "".$setup['path']."",
	"end" => ""
	),

	array(
	"name" => "FTP username", 
	"field" => "ftp_user", 
	"required" => "0",
	"type" => "text",
	"size" => "20",
	"notes2" => "",
	"td_width" => "25%",
	"end" => ""
	),

	array(
	"colspan" => "3",
	"name" => "FTP password", 
	"field" => "ftp_pass", 
	"required" => "0",
	"type" => "text",
	"size" => "20",
	"notes2" => "",
	"td_width" => "25%",
	"notes2" => "",
	"end" => ""
	),



	

);

if(!empty($_REQUEST['complete'])) {
	print successForm($ef);
} else {
	print dataForm($ef,$efCols);
}

?>
