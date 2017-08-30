<?php
require "../sy-config.php";
session_start();
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');

$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
//		print "<li>".$id ." => ".$val;
		define($id,$val);
	}
}

if(!empty($_REQUEST['contact'])) { 
	die("Sorry it didn't work out");
}
if(!empty($_REQUEST['comment'])) { 
	die("Sorry it didn't work out");
}
if($_REQUEST['address'] !== "http://") {
	die("Sorry it didn't work out");
}
/* 
print "<pre>";
print_r($_SESSION);
print_r($_POST); 
print "</pre>";
*/
//	exit();
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($value)) {
			$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
		}
		$_REQUEST[$id] = sql_safe(strip_tags("".$_REQUEST[$id].""));
	}
	if(empty($_REQUEST['d_n'])) {
		$error .="<div>"._leave_comment_name_." "._is_blank_."</div>";
	}
	if(empty($_REQUEST['d_e'])) {
		$error .="<div>"._leave_comment_email_." "._is_blank_."</div>";
	}
	if((empty($_REQUEST['d_h']))OR(!is_numeric($_REQUEST['d_h']))==true) {
		$error .="<div>You did not enter a number for the human verification.</div>";
	}
	if(trim($_REQUEST['d_h'])!==trim($_SESSION['humanTotal'])) {
		// print "<li>".$_REQUEST['d_h'] ." = ".$_SESSION['humanTotal'];

		$error .="<div>Your calculation for human verification is incorrect.</div>";
	}

	if(empty($error)) {
		addComment();
	} else {
		print $error;
	}


function addComment() {
	global $setup,$site_setup,$csettings,$com_table,$com_table_id,$com_title,$com_link;
	$com_settings = doSQL("ms_comments_settings", "*", "");

	$remote_host = @getHostByAddr(getUserIP());
if((trim($_SESSION['office_admin_login']) == "1") AND(!empty($_SESSION['office_admin']))==true) { $com_approved='1'; } 
if($com_settings['auto_post']== "1") { 
	$com_approved='1'; 
}
if(customerLoggedIn()) { 
	$com_approved='1'; 
}
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags(trim($value)))));
		}
	}
}

$time_now= date('Y-m-d H:i:s');

$thiscom = insertSQL("ms_comments", "
	com_email='".$_REQUEST['d_e']."',
	com_name='".addslashes(stripslashes($_REQUEST['d_n']))."',
	com_comment='".addslashes(stripslashes(strip_tags($_POST['d_m'])))."',
	com_website='".$_REQUEST['d_w']."',
	com_ip='".getUserIP()."',
	com_date='".$time_now."',
	com_remote_host='$remote_host',
	com_approved='$com_approved',
	com_table='".$_REQUEST['com_table']."',
	com_link='".$_REQUEST['com_link']."',
	com_title='".addslashes(stripslashes($_REQUEST['com_title']))."',
	com_table_id='".$_REQUEST['com_table_id']."'
	 ");


$subject = "New Comment Posted at ".$site_setup['website_title']."";
$to_email = $site_setup['contact_email'];
$to_name = $site_setup['contact_email'];
$from_email = $site_setup['contact_email'];
$from_name = $site_setup['website_title'];
$admin_message = "A new comment has been made on your website \r\n\r\n".$_REQUEST['d_n']." (".$_REQUEST['d_e'].") has just posted the following comment on \"".$_REQUEST['com_title']."\" (".$_REQUEST['com_link']."): \r\n\r\n".$_REQUEST['d_m']."\r\n";
if(!empty($_REQUEST['d_w'])) { $admin_message .= "Website: ".$_REQUEST['d_w']."\r\n"; }
$admin_message .= "\r\n";
if($com_approved == "1") { 
	$admin_message .= "Either because of your settings or ".$_REQUEST['d_e']." is on the white list, this comment was automatically posted.\r\n";
} else {
	$admin_message .= "PENDING APPROVAL\r\nTo approve this comment click here: ".$setup['url'].$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=comments&approve=".$thiscom."\r\n";
}
$admin_message .= "To trash this comment click here: ".$setup['url'].$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=comments&trash=".$thiscom."\r\n\r\n";
$waiting = countIt("ms_comments", "WHERE com_approved='0' ");
if($waiting  > 0) { 
	$admin_message .= "You have $waiting comments pending your approval\r\n";
}
$admin_message .= "Manage comments: ".$setup['url'].$setup['temp_url_folder']."/".$setup['manage_folder']."/index.php?do=comments\r\n\r\n";

stripslashes($admin_message);
stripslashes($subject);
if((trim($_SESSION['office_admin_login']) !== "1") OR ($_SESSION['office_admin'] == NULL )==true) {
	if($com_settings['email_new_comments'] == "1") {
		sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $admin_message,$type);
	}
}
unset($subject);
unset($to_email);
unset($from_email);


$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$cookie_url = ".$domain";


if(($_REQUEST['d_r'] !== "1")AND(isset($_COOKIE['c_d_e']))==true) { 
	$time=time()-3600*24*365;
	SetCookie("c_d_e","",$time,"/",$cookie_url);
	SetCookie("c_d_w","",$time,"/",$cookie_url);
	SetCookie("c_d_n","",$time,"/",$cookie_url);
}


if($_REQUEST['d_r'] == "1") { 
	print "SET";
	$time=time()+3600*24*365;
	SetCookie("c_d_e",$_REQUEST['d_e'],$time,"/",$cookie_url);
	SetCookie("c_d_w",$_REQUEST['d_w'],$time,"/",$cookie_url);
	SetCookie("c_d_n",$_REQUEST['d_n'],$time,"/",$cookie_url);
}

if($com_approved == "1") { 
	print "good";
//	$_SESSION['com_message'] = _leave_comment_approved_message_;
} else {
	print "pending";
//	$_SESSION['com_message'] = _leave_comment_success_message_;
}

/*
session_write_close();
if(!empty($_REQUEST['com_return_link'])) { 
	header("location: ".$_REQUEST['com_return_link']."");
} else { 
	header("location: ".$_REQUEST['com_link']."#success");
}
*/
exit();

?></div></div></div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php exit();
}
?>
