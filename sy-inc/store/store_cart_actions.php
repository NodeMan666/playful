<?php 
require("../../sy-config.php");
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
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
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
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags(trim($value)))));
		}
	}
}

$_POST['pid'] = sql_safe($_POST['pid']);
$_POST['prod_id'] = sql_safe($_POST['prod_id']);
$_POST['did'] = sql_safe($_POST['did']);
if(isset($_SESSION['pid'])) {
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}

if($_REQUEST['action'] == "checkCoupon") { 
	checkCouponOnePerPerson($_REQUEST['email']);
	print $_REQUEST['email'];
	mysqli_close($dbcon); exit();
}
if($_REQUEST['action'] == "logshare") { 
	$mysqldate = date("Y-m-d H:i:s");
	if(isset($_SESSION['pid'])) {
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	}
	if($_REQUEST['pic'] > 0) { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pic']."' ");
	}
	if((!empty($_REQUEST['where']))&&(!empty($_REQUEST['did'])) == true) { 
		insertSQL("ms_shares", "share_ip='".getUserIP()."', share_person='".$person['p_id']."', share_where='".$_REQUEST['where']."', share_page='".$_REQUEST['did']."', share_date='".$mysqldate."', share_photo='".$pic['pic_id']."' ");
	}
	mysqli_close($dbcon); exit();
}

if($_REQUEST['action'] == "getstatelist") { 
	$states = whileSQL("ms_states", "*", "WHERE state_country='".$_REQUEST['country']."' ");
	if(mysqli_num_rows($states) <= 0) { ?>
	<option value="N/A">N/A</option>
	<?php 	} else { 	?>
	<option value=""><?php print _select_state_;?></option>
	<?php 
		while($state = mysqli_fetch_array($states)) { ?>
		<option value="<?php print $state['state_abr'];?>"><?php print $state['state_name'];?></option>
		<?php 
		}

	}
	exit();
}


if($_POST['action'] == "emailsignup") { 
	joinmailinglist($_POST['enter_email'],$_POST['enter_first_name'],$_POST['enter_last_name'],$_REQUEST['elocation']);
	print "good";
	exit();
}
if($_REQUEST['action'] == "checkgiftcertificate") {
	$_REQUEST['redeem_code'] = trim($_REQUEST['redeem_code']);
	$ck = doSQL("ms_gift_certificates", "*", "WHERE redeem_code='".$_REQUEST['redeem_code']."' AND used_order<='0' ");
	if($ck['id'] > 0) { 
		print "good";
	}
	exit();
}

 if($_REQUEST['action'] == "signContract") { 
	$contract = doSQL("ms_contracts","*","WHERE contract_id='".$_REQUEST['contract_id']."' ");
	if($contract['contract_id'] <= 0 ) { die("Can not find contract"); } 
	$_REQUEST['signature_svg'] = rawurldecode($_REQUEST['signature_svg']);
	$_REQUEST['signature_svg'] = str_replace('width="15cm"',"",$_REQUEST['signature_svg']);
	$_REQUEST['signature_svg'] = str_replace('height="15cm"',"",$_REQUEST['signature_svg']);

	if($_REQUEST['field'] == "signature") { 
		if($setup['demo_mode'] !== true) { 
			updateSQL("ms_contracts","signature='".addslashes(stripslashes(trim($_REQUEST['signature'])))."', signed_date='".currentdatetime()."',  ip_address='".$_REQUEST['sign_ip']."', browser_info='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['sign_browser']))))."', signature_svg='".addslashes(stripslashes(trim($_REQUEST['signature_svg'])))."' WHERE contract_id='".$contract['contract_id']."' ");
		}
		$table = "ms_people";
		$table_id = $contract['person_id'];
		$message = "Contract ".$contract['title']." was signed by ".$contract['signature_name']." ";
		addNote($table,$table_id,$message,0);
		$email_to = $contract['email'];
		$email_to_name = $contract['signature_name'];
	} else if($_REQUEST['field'] == "signature2") { 
		if($setup['demo_mode'] !== true) { 
			updateSQL("ms_contracts","signature2='".addslashes(stripslashes(trim($_REQUEST['signature'])))."', signed_date2='".currentdatetime()."',  ip_address2='".$_REQUEST['sign_ip']."', browser_info2='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['sign_browser']))))."', signature2_svg='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['signature_svg']))))."'  WHERE contract_id='".$contract['contract_id']."' ");
		}
		$table = "ms_people";
		$table_id = $contract['person_id'];
		$message = "Contract ".$contract['title']." was signed by ".$contract['signature_name2']." ";
		addNote($table,$table_id,$message,0);
		$email_to = $contract['email2'];
		$email_to_name = $contract['signature_name2'];
	} else if($_REQUEST['field'] == "my_signature") { 
		if($setup['demo_mode'] !== true) { 
			updateSQL("ms_contracts","my_signature='".addslashes(stripslashes(trim($_REQUEST['signature'])))."', my_signed_date='".currentdatetime()."',  my_ip_address='".$_REQUEST['sign_ip']."', my_browser_info='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['sign_browser']))))."', my_signature_svg='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['signature_svg']))))."'  WHERE contract_id='".$contract['contract_id']."' ");
		}
	}

	$contract = doSQL("ms_contracts","*,date_format(signed_date, '".$site_setup['date_format']." ')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ')  AS signed_date2","WHERE contract_id='".$_REQUEST['contract_id']."' ");


	######## Update contract, replace fields and move to content_signed ################ 
	$content_signed = $contract['content'];
	if(empty($contract['content_signed'])) { 
		updateSQL("ms_contracts", "content_signed='".addslashes(stripslashes($_POST['contractcontent']))."' WHERE contract_id='".$contract['contract_id']."' ");
	}









	if($_REQUEST['make_default'] == "1") { 
		updateSQL("ms_settings", "default_sig='".addslashes(stripslashes(trim($_REQUEST['signature'])))."', default_sig_svg='".addslashes(stripslashes(trim(rawurldecode($_REQUEST['signature_svg']))))."' ");
	}

	if($_REQUEST['field'] == "signature") { 
		print $contract['signed_date'];
	} else if($_REQUEST['field'] == "signature2") { 
		print $contract['signed_date2'];
	} else if($_REQUEST['field'] == "my_signature") { 
		print $contract['my_signed_date'];
	}

	if(!empty($email_to)) { 
		$em = doSQL("ms_emails", "*", "WHERE email_id_name='contractsigned' ");
		$subject = $em['email_subject'];
		$message = $em['email_message'];
		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");
		$message = str_replace("[NAME]",$email_to_name, "$message");
		$message = str_replace("[LAST_NAME]",$person['p_last_name'], "$message");
		$message = str_replace("[EMAIL_ADDRESS]",$email_to, "$message");
		$message = str_replace("[PASSWORD]",$newpass, "$message");

		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]","".$site_setup['website_title']."", "$subject");
		$subject = str_replace("[NAME]",$email_to_name, "$subject");
		$subject = str_replace("[LAST_NAME]",$person['p_last_name'], "$subject");
		$subject = str_replace("[EMAIL_ADDRESS]",$email_to, "$subject");
		if($site_setup['checkout_ssl'] == "1") { 
			$message = str_replace("[CONTRACT_LINK]","<a href=\"".$setup['secure_url'].$setup['temp_url_folder']."/".$site_setup['contract_folder']."/index.php?contract=".$contract['link']."\">", $message);
		} else { 
			$message = str_replace("[CONTRACT_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder']."/".$site_setup['contract_folder']."/index.php?contract=".$contract['link']."\">", $message);
		}

		$content = $contract['content'];
		$content = str_replace("[NAME]",$contract['signature_name'],$content);
		$content = str_replace("[NAME2]",$contract['signature_name2'],$content);
		$content = str_replace("[MY_NAME]",$contract['my_name'],$content);

		$message = str_replace("[CONTRACT]",$_POST['contractcontent'], $message);
		
		$message = str_replace("[DUE_DATE]",$contract['due_date'], $message);
		$message = str_replace("[PIN]",$contract['pin'], $message);
		$message = str_replace("[/LINK]","</a>", $message);

		if(empty($em['email_from_email'])) {
			$from_email = $site_setup['contact_email'];
		} else {
			$from_email = $em['email_from_email'];
		}

		if(empty($em['email_from_name'])) {
			$from_name = $site_setup['website_title'];
		} else {
			$from_name = $em['email_from_name'];
		}
		sendWebdEmail($email_to, $email_to_name, $from_email, $from_name, $subject, $message,"1");
	}

	if(!empty($email_to)) { 

		$subject = "Contract Signed by ".$email_to_name;
		$admin_message = "<p>This is to inform you that ".$email_to_name." has signed the contract titled ".$contract['title'].". Below is the email sent to ".$email_to_name." ".$email_to." which also includes a link to view the contract.</p>";
		$admin_message .= "<p>----------------------------------------------------------</p>";
		$admin_message .= $message;
		
		sendWebdEmail($from_email, $from_name, $from_email, $from_name, $subject, $admin_message,"1");
	}

	exit();
 } 


if($_POST['action'] == "restockemail") { 
	$email = strtolower(trim($_POST['restock_email']));
	$email = sql_safe($email);
	$email = str_replace(" ","",$email);
	$name = trim($_POST['enter_name']);
	$name = sql_safe($name);

	$ck = doSQL("ms_email_list", "*", "WHERE em_email='".$email."' AND em_do_not_send='0' AND em_date_id='".$_REQUEST['restockitem']."' ");
	if(empty($ck['em_id'])) { 
		$id = insertSQL("ms_email_list", "em_email='".$email."' , em_ip='".getUserIP()."', em_date='".date('Y-m-d H:i:s')."', em_date_id='".$_REQUEST['restockitem']."' ");	
	}
	print "good";
	exit();
}
if($_POST['action'] == "emailcollect") { 
	$email = strtolower(trim($_POST['enter_email_popup']));
	$email = str_replace(" ","",$email);
	$email = sql_safe($email);

	$time=time()+3600*24*365*2;
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	SetCookie("myemail",$email,$time,"/",null);
	$_POST['did'] = sql_safe($_POST['did']);

	$cke = doSQL("ms_pre_register", "*", "WHERE LOWER(reg_email)='".$email."' AND reg_date_id='".$_POST['did']."' AND toview='1'  ");
	if(empty($cke['reg_id'])) { 
		insertSQL("ms_pre_register", "toview='1', reg_email='".$email."', reg_date_id='".$_POST['did']."', reg_date='".date('Y-m-d h:i:s')."', reg_ip='".getUserIP()."' ");
	}

	print "good";
	exit();
}
if($_REQUEST['action'] == "setMailingListCookie") { 
	$time=time()+3600*24*365*2;
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	SetCookie("emview","1",$time,"/",null);
	 exit();
}


if($_POST['action'] == "newaccount") { 
	if(!empty($_REQUEST['from_message_to'])) { 
		die();
	}
	if(empty($_REQUEST['email_address'])) { 
		die("No good");
	}

	if(empty($_REQUEST['newpassword'])) { 
		die("No good");
	}


	$ckacc = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['email_address']."' ");
	if(!empty($ckacc['p_id'])) { 
		print "<div class=\"pc\"><div class=\"error\">"._email_already_exists_."</div></div>";
	} else { 
   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}


	$password = md5($_REQUEST['newpassword'].$salt);

	$pid = insertSQL("ms_people", "
	  p_name='".$_REQUEST['first_name']."',
	  p_last_name='".$_REQUEST['last_name']."',
	  p_email='".$_REQUEST['email_address']."',
	  p_phone='".$_REQUEST['order_phone']."',
	  p_create_by ='customer',
	  p_date='".date('Y-m-d')."',
	  p_address1='".$_REQUEST['address']."',
	  p_address2='".$_REQUEST['address2']."',
	  p_city='".$_REQUEST['city']."',
	  p_state='".$_REQUEST['state']."',
	  p_zip='".$_REQUEST['zip']."',
	  p_country='".$_REQUEST['country']."',
	  p_state2='".$_REQUEST['']."',
	  p_last_active='".date('Y-m-d H:i:s')."',
	  p_ip='".getUserIP()."',
	  p_news_letter='".$_REQUEST['']."',
	  p_company='".$_REQUEST['business_name']."',
	  p_receive_emails='".$_REQUEST['']."',
	  p_pass='$password',
	  p_salt='$salt' ");

	$_SESSION['loggedin'] = true;
	$_SESSION['pid'] = MD5($pid);

	$time=time()+3600*24*365*2;
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	SetCookie("persid",MD5($pid),$time,"/",null);
	SetCookie("hasloggedin",1,$time,"/",null);
	if(!empty($_POST['ms_session'])) { 
		updateSQL("ms_cart", "cart_client='".MD5($pid)."' WHERE cart_session='".$_POST['ms_session']."'  AND cart_order='0' AND cart_client='' ");
	} else { 
		updateSQL("ms_cart", "cart_client='".MD5($pid)."' WHERE cart_session='".$_SESSION['ms_session']."'  AND cart_order='0' AND cart_client='' ");
	}
	print "good";
	 if($site_setup['email_new_customer'] =="1") { 
		$message .= "<p>A new account has been created at ".$setup['url']."</p>";
		$message .= '<table cellpadding="4" cellspacing="0" border="0">';
		if(!empty($_REQUEST['business_name'])) { 
			$message .= '<tr><td>Company</td><td>'.$_REQUEST['business_name'].'</td></tr>';
		}

		$message .= '<tr><td>Name</td><td>'.$_REQUEST['first_name'].' '.$_REQUEST['last_name'].'</td></tr>';
		$message .= '<tr><td>Email</td><td>'.$_REQUEST['email_address'].'</td></tr>';
		if(!empty($_REQUEST['address'])) { 
			$message .= '<tr><td>Address</td><td>'.$_REQUEST['address'].'</td></tr>';
		}
		if(!empty($_REQUEST['city'])) { 
			$message .= '<tr><td>City</td><td>'.$_REQUEST['city'].'</td></tr>';
		}
		if(!empty($_REQUEST['state'])) { 
			$message .= '<tr><td>State</td><td>'.$_REQUEST['state'].'</td></tr>';
		}
		if(!empty($_REQUEST['country'])) { 
			$message .= '<tr><td>Country</td><td>'.$_REQUEST['country'].'</td></tr>';
		}
		if(!empty($_REQUEST['zip'])) { 
			$message .= '<tr><td>Postal Code</td><td>'.$_REQUEST['zip'].'</td></tr>';
		}
		if(!empty($_REQUEST['order_phone'])) { 
			$message .= '<tr><td>Phone</td><td>'.$_REQUEST['order_phone'].'</td></tr>';
		}

		$message .= '<tr><td>IP Address</td><td>'.getUserIP().'</td></tr>';


		$message .= '</table>';
		$subject = "New Account Created at ".$site_setup['website_title']." ".date('m/d/Y');

		$from_email = $site_setup['contact_email'];
		$from_name = $site_setup['website_title'];

		sendWebdEmail("".$from_email."", "".$from_name."", "".$from_email."", "".$from_name."", $subject, $message,"1");
	 }
	$_REQUEST['join_ml'] = str_replace("undefined","",$_REQUEST['join_ml']);
	if($_REQUEST['join_ml'] == "1") { 
		joinmailinglist($_REQUEST['email_address'],$_REQUEST['first_name'],$_REQUEST['last_name'],"account");
	}

	mysqli_close($dbcon); exit();
	}
}

if($_POST['action'] == "updateaccount") { 
	if(!empty($_SESSION['pid'])) { 
		if(!empty($_REQUEST['email_address'])) { 
			$add_email = ", p_email='".$_REQUEST['email_address']."' ";
		}
		$pidup = updateSQL("ms_people", "
		  p_name='".$_REQUEST['first_name']."',
		  p_last_name='".$_REQUEST['last_name']."',
		  p_phone='".$_REQUEST['order_phone']."',
		  p_create_by ='customer',
		  p_address1='".$_REQUEST['address']."',
		  p_address2='".$_REQUEST['address2']."',
		  p_city='".$_REQUEST['city']."',
		  p_state='".$_REQUEST['state']."',
		  p_zip='".$_REQUEST['zip']."',
		  p_country='".$_REQUEST['country']."',
		  p_company='".$_REQUEST['business_name']."' 
		  $add_email 
		  WHERE MD5(p_id)='".$_SESSION['pid']."' 
		  ");
	}
	print "updated";
	mysqli_close($dbcon); exit();
}
if($_POST['action'] == "newaccountexpress") { 
	if(!ctype_alnum($_POST['oid'])) { die("an error has occurred [1]"); }

	$order = doSQL("ms_orders", "*", "WHERE MD5(order_id)='".$_POST['oid']."' ");

	$ckacc = doSQL("ms_people", "*", "WHERE p_email='".$order['order_email']."' ");
	if(!empty($ckacc['p_id'])) { 
		print "<div class=\"pc\"><div class=\"error\">Your email address already exists in an account. Please log into your account using the log in form.</div></div>";
	} else { 
   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
    $salt = '';
    for ($i = 0; $i < 5; $i++) { 
        $salt .= $characters[mt_rand(0, 39)];
	}


	$password = md5($_REQUEST['newpassword'].$salt);

	$pid = insertSQL("ms_people", "
	  p_name='".$order['order_first_name']."',
	  p_last_name='".$order['order_last_name']."',
	  p_email='".$order['order_email']."',
	  p_phone='".$order['order_phone']."',
	  p_create_by ='customer',
	  p_date='".date('Y-m-d')."',
	  p_address1='".$order['order_address']."',
	  p_address2='".$order['order_address_2']."',
	  p_city='".$order['order_city']."',
	  p_state='".$order['order_state']."',
	  p_zip='".$order['order_zip']."',
	  p_country='".$order['order_country']."',
	  p_state2='".$_REQUEST['']."',
	  p_last_active='".date('Y-m-d H:i:s')."',
	  p_ip='".getUserIP()."',
	  p_news_letter='".$_REQUEST['']."',
	  p_company='".$order['order_company']."',
	  p_receive_emails='".$_REQUEST['']."',
	  p_pass='$password',
	  p_salt='$salt' ");
	updateSQL("ms_orders", "order_customer='$pid' WHERE order_id='".$order['order_id']."' ");
	$_SESSION['loggedin'] = true;
	$_SESSION['pid'] = MD5($pid);
		updateSQL("ms_cart", "cart_client='".MD5($pid)."' WHERE cart_session='".$_SESSION['ms_session']."'  AND cart_order='0' AND cart_client='' ");

	print "good";
	mysqli_close($dbcon); exit();
	}
}
if(($_POST['action'] == "login")||($_POST['pageaction'] == "login")==true) { 
	if($_POST['pageaction'] == "login") { 
		$email = sql_safe("".$_REQUEST['loginemailpage']."");
		$password = sql_safe("".$_REQUEST['loginpasswordpage']."");
	} else { 
		$email = sql_safe("".$_REQUEST['loginemail']."");
		$password = sql_safe("".$_REQUEST['loginpassword']."");
	}
	$ckemail = doSQL("ms_people", "*",  " WHERE p_email='".$email."' ");
	$pass = MD5($password."".$ckemail['p_salt']);
	$person = doSQL("ms_people", "*",  " WHERE p_id='".$ckemail['p_id']."' AND p_pass='$pass' ");
	if(empty($person['p_id'])) { 
		print "<div class=\"error\">"._log_in_incorrect_."</div>";
	} else { 
		$_SESSION['loggedin'] = true;
		$_SESSION['pid'] = MD5($person['p_id']);

		$time=time()+3600*24*365*2;
		$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
		$cookie_url = ".$domain";
		SetCookie("persid",MD5($person['p_id']),$time,"/",null);
		SetCookie("hasloggedin",1,$time,"/",null);

		if(!empty($_SESSION['newaccountorder'])) { 
			$order = doSQL("ms_orders", "*", "WHERE MD5(order_id)='".$_SESSION['newaccountorder']."' ");
			if((!empty($order['order_id']))&&($order['order_customer'] <=0)==true) { 
				updateSQL("ms_orders", "order_customer='".$person['p_id']."' WHERE order_id='".$order['order_id']."' ");
			}
			unset($_SESSION['newaccountorder']);
		}
		updateSQL("ms_cart", "cart_client='".MD5($person['p_id'])."' WHERE cart_session='".$_SESSION['ms_session']."'  AND cart_order='0' AND cart_client='' ");

		if(!empty($_SESSION['return_page'])) { ?>
		<script>window.location.href="<?php print $_SESSION['return_page'];?>";</script>
		<?php
			unset($_SESSION['return_page']);
		} else { 		
			print "good";
		}
		}
	mysqli_close($dbcon); exit();
}
if($_POST['action'] == "changepassword") { 
	if(empty($_SESSION['pid'])) { die("no session id"); } 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($p['p_id'])) { die("no account"); } 

	if($_REQUEST['newpass'] !== $_REQUEST['renewpass']) { 
		print "<div class=\"error\">"._passwords_do_not_match_."</div>";
	} else { 
	   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
		$salt = '';
		for ($i = 0; $i < 5; $i++) { 
			$salt .= $characters[mt_rand(0, 39)];
		}
		$password = md5($_REQUEST['newpass'].$salt);
		updateSQL("ms_people", "p_pass='$password', p_salt='$salt', p_pass_def=''  WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		print "good";
	}
	mysqli_close($dbcon); exit();
}

if($_POST['action'] == "changeemail") { 
	if(empty($_SESSION['pid'])) { die("no session id"); } 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($p['p_id'])) { die("no account"); } 

	if($_REQUEST['newemail'] !== $_REQUEST['renewemail']) { 
		print "<div class=\"error\">"._email_addresses_do_not_match_."</div>";
	} else { 
		$ck = doSQL("ms_people", "*", "WHERE p_email='".$_REQUEST['newemail']."' AND  MD5(p_id)!='".$_SESSION['pid']."' ");
		if(!empty($ck['p_id'])) { 
			print "<div class=\"error\">That email address already exists in another account. If this is your email address try logging into that account.</div>";
		} else { 
			updateSQL("ms_people", "p_email='".$_REQUEST['newemail']."' WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			print "good";
		}
	}
	mysqli_close($dbcon); exit();
}
if($_POST['action'] == "changeaddress") { 
	if(empty($_SESSION['pid'])) { die("no session id"); } 
	$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($p['p_id'])) { die("no account"); } 
	updateSQL("ms_people","p_name='".$_REQUEST['first_name']."',
	  p_last_name='".$_REQUEST['last_name']."',
	  p_phone='".$_REQUEST['phone']."',
	  p_address1='".$_REQUEST['address']."',
	  p_address2='".$_REQUEST['address2']."',
	  p_city='".$_REQUEST['city']."',
	  p_state='".$_REQUEST['state']."',
	  p_zip='".$_REQUEST['zip']."',
	  p_country='".$_REQUEST['country']."',
	  p_state2='".$_REQUEST['']."',
	  p_ip='".getUserIP()."',
	  p_company='".$_REQUEST['company']."'  WHERE MD5(p_id)='".$_SESSION['pid']."' ");

	print "good";
	mysqli_close($dbcon); exit();
}


if(($_POST['action'] == "forgotemail")||($_POST['pageaction'] == "forgotemail")==true) { 
	if($_POST['pageaction'] == "forgotemail") { 
		$email = sql_safe("".$_REQUEST['forgotemailpage']."");
	} else { 
		$email = sql_safe("".$_REQUEST['forgotemail']."");
	}
	$person = doSQL("ms_people", "*",  " WHERE p_email='".$email."' ");
	if(empty($person['p_id'])) { 
		print "<div class=\"error\">"._email_address_not_found_."</div>";
	} else { 

	   $characters = '@#$%&*>?!+_abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
		$newpass = '';
		for ($i = 0; $i < 8; $i++) { 
			$newpass .= $characters[mt_rand(0, 60)];
		}
	   $characters = '@#$%^&*(<>?!(+_)qwertyipAHDKFGMNBCXZLywg';
		$salt = '';
		for ($i = 0; $i < 5; $i++) { 
			$salt .= $characters[mt_rand(0, 39)];
		}
		$password = md5($newpass.$salt);

		updateSQL("ms_people", "	  p_pass='$password',  p_salt='$salt'  WHERE p_id='".$person['p_id']."' ");

		print "good";		
		$em = doSQL("ms_emails", "*", "WHERE email_id='19' ");
		$subject = $em['email_subject'];
		$message = $em['email_message'];
		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[WEBSITE_NAME]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">".$site_setup['website_title']."</a>", "$message");
		$message = str_replace("[FIRST_NAME]",$person['p_name'], "$message");
		$message = str_replace("[LAST_NAME]",$person['p_last_name'], "$message");
		$message = str_replace("[EMAIL_ADDRESS]",$person['p_email'], "$message");
		$message = str_replace("[PASSWORD]",$newpass, "$message");

		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]","".$site_setup['website_title']."", "$subject");
		$subject = str_replace("[FIRST_NAME]",$person['p_name'], "$subject");
		$subject = str_replace("[LAST_NAME]",$person['p_last_name'], "$subject");
		$subject = str_replace("[EMAIL_ADDRESS]",$person['p_email'], "$subject");

		if(empty($em['email_from_email'])) {
			$from_email = $site_setup['contact_email'];
		} else {
			$from_email = $em['email_from_email'];
		}

		if(empty($em['email_from_name'])) {
			$from_name = $site_setup['website_title'];
		} else {
			$from_name = $em['email_from_name'];
		}
		sendWebdEmail($person['p_email'], $person['p_name']." ".$person['p_last_name'], $from_email, $from_name, $subject, $message,"1");
	}
	mysqli_close($dbcon); exit();
}

if(($_POST['action'] == "updatecartnote")AND(!empty($_POST['cart_id']))AND(!empty($_POST['cart_note']))==true) {
//	print_r($_POST);
	// $note = sql_safe("".$_POST['cart_note']."");
	$note =strip_tags($_POST['cart_note']);
	$cart_id = sql_safe("".$_POST['cart_id']."");
	$cart_id = addslashes(stripslashes(stripslashes(strip_tags($cart_id))));
	updateSQL("ms_cart", "cart_notes='". addslashes(stripslashes($note))."' WHERE MD5(cart_id)='".$cart_id."' ");
	print nl2br($note);
	mysqli_close($dbcon); exit();
}

if(!empty($_POST['cart_photo_bg'])) { 
	if(!ctype_alnum($_POST['cart_photo_bg'])) { die("an error has occurred [5]"); }
	$bgphoto = doSQL("ms_photos","*", "WHERE pic_key='".$_POST['cart_photo_bg']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	if($date['green_screen_backgrounds'] <= 0 ) { 
		$bgphoto['pic_id'] = 0;
	}
}
if(($_POST['action'] == "addToCart")AND(!empty($_POST['did']))==true) {

	if(!ctype_alnum($_POST['did'])) { die("an error has occurred [1]"); }
	if(!is_numeric($_POST['prod_qty'])) { die("an error has occurred [2]"); }
	if(($_POST['cart_pre_reg'] > 0)&&(!is_numeric($_POST['cart_pre_reg']))==true) { die(); } 
	if((!empty($_POST['prod_qty'])) && ($_POST['prod_qty'] <= 0)==true) { die(); }
	$date = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_POST['did']."' ");

	if(empty($date['date_id'])) {
		die("Unable to find this product in the database");
	}
	if($date['prod_type'] =="download") {
		$cart_download = 1;
	}
	if($date['prod_type'] =="ship") {
		$cart_ship = 1;
	}
	if($date['prod_shipping'] =="1") {
		$cart_ship = 1;
	}

	if($date['prod_type'] =="service") {
		$cart_service = 1;
	}
	if($date['prod_type'] =="subscript") {
		$cart_subscription = 1;
	}

	if($_REQUEST['spid'] <=0) { 
		$_REQUEST['spid'] = 0;
	}

	$prod_cost = $date['prod_cost'];
	if($_REQUEST['spid'] > 0) { 
		$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$_REQUEST['spid']."' ");
		if($sub['sub_cost'] > 0) { 
			$prod_cost = $prod_cost + $sub['sub_cost'];
		}
	}
	if((countIt("ms_product_options",  "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ")<=0)||($date['prod_max_one'] == "1") || ($date['prod_inventory_control'] == "1")==true) { 
		$incart = doSQL("ms_cart", "*", "WHERE cart_store_product='".$date['date_id']."'  AND ".checkCartSession()."   AND cart_sub_id='".$_REQUEST['spid']."' AND cart_order='0' ");
	}
	if((!empty($incart['cart_id']))&&($date['reg_person']<=0)&&(empty($_POST['shirt-size'])) ==true) { 
		$new_qty = $incart['cart_qty']+ $_POST['prod_qty'];

		 if($date['prod_inventory_control'] == "1") {
			if(!empty($incart['cart_sub_id'])) {
				$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$incart['cart_sub_id']."' ");
				$onhand = $sub['sub_qty'];
			} else { 
				$onhand = $date['prod_qty'];
			}
			if($new_qty > $onhand) { 
				$new_qty = $onhand;
			}
		 }
		if($date['date_paid_access'] == "1") { 
			$new_qty = 1;
		}
		if($date['prod_max_one'] == "1") { 
			$new_qty = 1;
		}

		updateSQL("ms_cart", "cart_qty='".$new_qty."', cart_date='".date('Y-m-d H:i:s')."' WHERE cart_id='".$incart['cart_id']."' ");
		$_SESSION['addedtocart'] = $incart['cart_id'];

	} else { 
		if($date['reg_person'] >0) { 
			if(!empty($_POST['reg_amount'])) { 
				if(!is_numeric($_POST['reg_amount'])) { die(); } 
			}
			$cart_price = $_REQUEST['reg_amount'];
			$date['date_credit'] = $cart_price;
			$cart_reg_message = trim($_REQUEST['reg_message']);
			$cart_reg_message_name = trim($_REQUEST['reg_message_name']);
			$date['prod_no_discount'] = "1";
		} else { 
			$cart_price = $date['prod_price'];
		}
		
		$_REQUEST['no_show'] = str_replace("undefined","",$_REQUEST['no_show']);
		// print_r($_REQUEST);

		if((!customerLoggedIn()) && (isset($_COOKIE['myemail'])) == true)  { 
			$cart_email = strtolower(trim($_COOKIE['myemail']));
		}
		

		$cart_add_id = insertSQL("ms_cart", "cart_qty='".$_POST['prod_qty']."', cart_store_product='".$date['date_id']."', cart_product_name='".addslashes(stripslashes($date['date_title']))."', cart_order_message='".addslashes(stripslashes($date['prod_order_message']))."', cart_price='".$cart_price."', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$date['prod_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."' , cart_cost='".$prod_cost."', cart_no_discount='".$date['prod_no_discount']."', cart_account_credit='".$date['date_credit']."', cart_account_credit_for='".$date['reg_person']."', 
		cart_reg_message='".addslashes(stripslashes($cart_reg_message))."', 
		cart_reg_message_name='".addslashes(stripslashes($cart_reg_message_name))."', 
		cart_reg_no_display_amount='".$_REQUEST['no_show']."', 
		cart_paid_access='".$date['date_paid_access']."' , 
		cart_pre_reg='".$_POST['cart_pre_reg']."', 
		cart_extra_ship='".$date['prod_add_ship']."', 
		cart_photo_bg='".$bgphoto['pic_id']."',
		cart_subscription='".$cart_subscription."', 
		cart_email='".addslashes(stripslashes($cart_email))."' ");
		$_SESSION['addedtocart'] = $cart_add_id;


		$opts = whileSQL("ms_product_options", "*", "WHERE opt_date='".$date['date_id']."' ORDER BY opt_order ASC ");
		while($opt = mysqli_fetch_array($opts))  {
			if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
				if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")||($opt['opt_type'] == "tabs")==true) { 
					$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
					$opt_price = $sel['sel_price'];
					$opt_select_name = $sel['sel_name'];
					if($sel['sel_photos'] > 0) { 
						$date['prod_photos'] = $sel['sel_photos'];
					}
				}

				if(($opt['opt_type'] == "text") || ($opt['opt_type'] == "date") || ($opt['opt_type'] == "reg_key") == true) { 
					$opt_price = $opt['opt_price'];
					$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
				}
				if($opt['opt_type'] == "checkbox") { 
					$opt_price = $opt['opt_price_checked'];
					$opt_select_name = "selected";
				}
				insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$cart_add_id."' ");
			}

		}

		if(!empty($_POST['shirt-gender'])) { 
			if($_POST['shirt-gender'] == "m") { 
				$gen = "Men's";
			}
			if($_POST['shirt-gender'] == "f") { 
				$gen = "Women's";
			}
			insertSQL("ms_cart_options", "co_opt_id='0', co_opt_name='Gender', co_select_id='', co_select_name='".addslashes(stripslashes($gen))."', co_price='', co_cart_id='".$cart_add_id."' ");
		}
		if(!empty($_POST['shirt-size'])) { 
			insertSQL("ms_cart_options", "co_opt_id='0', co_opt_name='Size', co_select_id='', co_select_name='".addslashes(stripslashes($_REQUEST['shirt-size']))."', co_price='', co_cart_id='".$cart_add_id."' ");
		}
		if(!empty($_POST['shirt-color'])) { 
			insertSQL("ms_cart_options", "co_opt_id='0', co_opt_name='Color', co_select_id='', co_select_name='".addslashes(stripslashes($_REQUEST['shirt-color']))."', co_price='', co_cart_id='".$cart_add_id."' ");
		}

		if($date['prod_photos'] > 0) { 
			updateSQL("ms_cart", "cart_product_select_photos='1' WHERE cart_id='".$cart_add_id."' ");
			$p = 1;
			while($p <= $date['prod_photos']) { 
				if((!empty($_POST['curphoto']))&&($p == 1)==true) { 
					$_POST['curphoto'] = sql_safe($_POST['curphoto']);
					$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_POST['curphoto']."' ");
					$add_pic = ", cart_pic_id='".$pic['pic_id']."', cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."', cart_pic_date_id='".$_POST['curphotodid']."' ";
				}
				$cart_id = insertSQL("ms_cart", "cart_product_photo='$cart_add_id', cart_qty='1', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_photo_bg='".$bgphoto['pic_id']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."' $add_pic ");
				$p++;
				$add_pic = "";

				if(!empty($bgphoto['pic_id'])) { 
					$green_screen_cart = true;
					require($setup['path']."/sy-inc/gs-photos.php");
				}

			}
		}

	
	
	}
	//header("location: index.php");
	mysqli_close($dbcon); exit();

}





/* ADD PHOTO PRODUCT */
if($_REQUEST['action'] == "addphotoprodtocart") { 
	if(!is_numeric($_POST['qty'])) { die("an error has occurred [2]"); }
	//  print_r($_POST);
	if((!empty($_POST['list_id']))&&(!is_numeric($_POST['list_id']))==true) { die(); } 
	if(!empty($_REQUEST['did'])) { 
		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	}
	if(!empty($date['date_id'])) {
		if(empty($date['date_photo_keywords'])) { 
			$and_gal = "AND bp_blog='".$date['date_id']."' ";
		} else { 
			$photos_by_key_words = true;
		}
	}
	if(!empty($_REQUEST['sub_id'])) {
		$sub =doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	}
	if($photos_by_key_words == true) {
		$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_POST['pid']."' $and_gal ");
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE pic_key='".$_POST['pid']."' $and_gal ");
	}

	$ext = strtolower(substr($pic['pic_org'], -4));
	if($ext !== ".png") { 
		$bgphoto['pic_id'] = 0;
	}
	if((empty($sub['sub_id'])) && (!empty($pic['bp_sub'])) == true) { 
		$sub =doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$pic['bp_sub']."' ");
	}
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database - ".$_REQUEST['did']." | ".$_POST['pid']." ");
	}
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_POST['list_id']."' ");

	if($_POST['prod_package_id'] > 0) { 
		$_POST['prod_package_id'] = sql_safe($_POST['prod_package_id']);
		$_POST['prod_package'] = sql_safe($_POST['prod_package']);
		$con = doSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_id='".$_POST['prod_package_id']."' ");
		$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$con['con_product']."' ");
		$add_for_package = ",cart_package_photo_extra='".$_POST['prod_package']."' ";
		$photo_in_package = countIt("ms_cart", "WHERE cart_package_photo='".$_POST['prod_package']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
		$photo_in_package = $photo_in_package + countIt("ms_cart", "WHERE cart_package_photo_extra='".$_POST['prod_package']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_pic_id"); 
		if($photo_in_package > 0) { 
			$cart_price = $con['con_extra_price'];
			$cart_package_photo_extra_on = $pic['pic_id'];
		} else { 
			$cart_price = $con['con_extra_price_new_photo'];
		}
	} else { 
		$con = doSQL("ms_photo_products_connect", "*", "WHERE pc_id='".$_POST['prod_id']."' ");
		$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$con['pc_prod']."' ");
		$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$con['pc_group']."' ");
		if($con['pc_price'] > 0) { 
			$cart_price = $con['pc_price'];
		} else { 
			$cart_price = $prod['pp_price'];
		}
		if($prod['pp_add_ship'] > 0) { 
			$cart_extra_ship = $prod['pp_add_ship'];
		}
	}
	if($prod['pp_id'] > 0) { 
		if(empty($prod['pp_id'])) { 
			die("Unable to find product");
		}

		if($prod['pp_type'] =="download") {
			$cart_download = 1;
		} else { 
			$cart_ship = 1;
		}

		if($prod['pp_no_ship'] =="1") {
			$cart_ship = 0;
		}
		if($prod['pp_include_download'] =="1") {
			$cart_download = 1;
		}


		if(!empty($group['group_name'])) { 
			$product_name = $group['group_name']." > ".$prod['pp_name'];
		} else { 
			$product_name = $prod['pp_name'];
		}

		if($_REQUEST['color_id'] > 0) { 
			$color = doSQL("ms_color_options", "*", "WHERE color_id='".$_REQUEST['color_id']."' ");
		}

		if($con['pc_qty_on'] == "1") { 
			$dis_on = $pic['pic_id'];
		} else { 
			$dis_on = "1";
		}


		$incart = doSQL("ms_cart", "*", "WHERE cart_photo_prod='".$prod['pp_id']."' AND cart_photo_prod_connect='".$con['pc_id']."' AND cart_pic_id='".$pic['pic_id']."' AND ".checkCartSession()."   AND cart_order='0' ");

		if((!customerLoggedIn()) && (isset($_COOKIE['myemail'])) == true)  { 
			$cart_email = strtolower(trim($_COOKIE['myemail']));
		}

		$cart_id = insertSQL("ms_cart", "cart_qty='".$_POST['qty']."', cart_photo_prod='".$prod['pp_id']."', cart_photo_prod_connect='".$con['pc_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."', cart_price='".$cart_price."', cart_ship='$cart_ship', cart_download='$cart_download', cart_disable_download='".$prod['pp_disable_download']."',  cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$prod['pp_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_id='".$pic['pic_id']."', cart_pic_date_id='".$_REQUEST['did']."', cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."', cart_pic_date_org='".addslashes(stripslashes($date['date_title']))."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$sub['sub_id']."', cart_group_id='".$group['group_id']."', cart_allow_notes='".$list['list_allow_notes']."', cart_dis_on='".$dis_on."' $add_for_package, cart_min_order='".$list['list_min_order']."', 
		cart_extra_ship='".$cart_extra_ship."', 
		cart_no_discount='".$prod['pp_no_discount']."', 
		cart_photo_bg='".$bgphoto['pic_id']."',
		cart_package_photo_extra_on='".$cart_package_photo_extra_on."',
		cart_crop_x1='".$_REQUEST['x1p']."', cart_crop_y1='".$_REQUEST['y1p']."', cart_crop_x2='".$_REQUEST['x2p']."', cart_crop_y2='".$_REQUEST['y2p']."', cart_crop_rotate='".$_REQUEST['rotate']."', cart_email='".addslashes(stripslashes($cart_email))."' ");

		if(!empty($bgphoto['pic_id'])) { 
			$green_screen_cart = true;
			require($setup['path']."/sy-inc/gs-photos.php");
		}

		$opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ");
		while($opt = mysqli_fetch_array($opts))  {
			if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
				if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")==true) { 
					$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
					$opt_price = $sel['sel_price'];
					$opt_select_name = $sel['sel_name'];
				}
				if($opt['opt_type'] == "text") { 
					$opt_price = $opt['opt_price'];
					$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
				}
				if($opt['opt_type'] == "checkbox") { 
					$opt_price = $opt['opt_price_checked'];
					$opt_select_name = _selected_;
				}
				if($opt['opt_type'] == "download") { 
					$opt_price = $opt['opt_price_download'];
					$opt_select_name = _selected_;
					$co_download = '1';
					if($opt['opt_disable_download'] > 0) { 
						updateSQL("ms_cart", "cart_disable_download='1' WHERE cart_id='".$cart_id."' ");
					}
				}

				insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$cart_id."', co_download='".$co_download."', co_download_size='".$opt['opt_download_size']."', co_disable_download='".$opt['opt_disable_download']."' ");
				$co_download = "";
				$opt_price = "";
			}

		}

		$iopts = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' ORDER BY opt_id ASC ");
		while($iopt = mysqli_fetch_array($iopts))  {
			if(!empty($_REQUEST['iopt-'.$iopt['opt_id'].''])) { 
				$opt_price = $iopt['opt_price'];
				insertSQL("ms_cart_options", "co_opt_id='".$iopt['opt_id']."', co_discountable='".$iopt['opt_discountable']."' , co_opt_name='".addslashes(stripslashes($iopt['opt_name']))."', co_price='".$opt_price."', co_cart_id='".$cart_id."', co_pic_id='".$pic['pic_id']."', co_taxable='".$iopt['opt_taxable']."' ");
			}

		}


		checkqtydiscounts();

		print "HEY";
		//header("location: index.php");
		mysqli_close($dbcon); exit();
	}
}


if($_REQUEST['action'] == "removephotofrompackage") { 
	
	$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$_POST['prod_id']."' ");	

	$package = doSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package=ms_packages.package_id", "*", "WHERE con_product='".$cart['cart_photo_prod']."'");
	
	if ($package['package_limit'] == "1") {

		$cart_id = updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."' , cart_sub_id='0', cart_pic_id='0', cart_pic_date_id='0', cart_pic_org='', cart_pic_date_org='', cart_color_id='', cart_color_name='', cart_sub_gal_id='' WHERE cart_package_photo='".$cart['cart_package_photo']."' ");
	} else {
		
		$cart_id = updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."' , cart_sub_id='0', cart_pic_id='0', cart_pic_date_id='0', cart_pic_org='', cart_pic_date_org='', cart_color_id='', cart_color_name='', cart_sub_gal_id='' WHERE cart_id='".$cart['cart_id']."' ");
	}
	
	deleteSQL2("ms_cart_options", "WHERE co_cart_id='".$cart['cart_id']."' "); //let's check after again.
	deleteSQL2("ms_cart", "WHERE cart_package_photo_extra_on='".$cart['cart_pic_id']."'AND cart_order<='0'  AND ".checkCartSession()." ");
	updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."' WHERE cart_id='".$cart['cart_package_photo']."' ");
	mysqli_close($dbcon); exit();
}


if($_REQUEST['action'] == "addphototopackage") { 
	
//	$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$_REQUEST['prod_id']."' ");
	$package = doSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package=ms_packages.package_id", "*", "WHERE con_product='".$_REQUEST['cart_photo_prod']."'");
	
	if($_REQUEST['qty'] <=0) { 
		$qty = 1;
	} else {
		$qty = $_REQUEST['qty'];
	}
	
	/* condition for restriction of the basic package and other package  */
	if ($package['package_limit'] == "1") {
		$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()."  AND cart_product_photo='".$_REQUEST['cart_product_photo']."' AND cart_package_photo='".$_REQUEST['cart_package_photo']."'  AND cart_order<='0' AND cart_pic_id='0'" );	
	} else {
		$carts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()."  AND cart_product_photo='".$_REQUEST['cart_product_photo']."' AND cart_package_photo='".$_REQUEST['cart_package_photo']."'  AND cart_order<='0'  AND cart_photo_prod='".$_REQUEST['cart_photo_prod']."' AND cart_pic_id='0' LIMIT ".$qty." " );
	}
	
	print "<h1>".mysqli_num_rows($carts)."</h1>";

	while($cart = mysqli_fetch_array($carts)) { 
		updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."' WHERE cart_id='".$cart['cart_product_photo']."' ");
		$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
		$ext = strtolower(substr($pic['pic_org'], -4));
		if($ext !== ".png") { 
			$bgphoto['pic_id'] = 0;
		}

		$date = doSQL("ms_calendar", "*", "WHERE date_id='".$cart['cart_product']."' ");
		$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");
		if(empty($pic['pic_id'])) {
			die("Unable to find this photo in the database");
		}
		if($_REQUEST['color_id'] > 0) { 
			$color = doSQL("ms_color_options", "*", "WHERE color_id='".$_REQUEST['color_id']."' ");
		}
		print "<h3>".countIt("ms_cart", "WHERE ".checkCartSession()."   AND cart_order<='0'  AND cart_photo_prod='".$cart['cart_photo_prod']."' AND cart_pic_id!='0' " )." / ".countIt("ms_cart", "WHERE ".checkCartSession()."   AND cart_order<='0'  AND cart_photo_prod='".$cart['cart_photo_prod']."' AND cart_pic_id='0' " )."</h3>";

		updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_id='".$pic['pic_id']."', cart_pic_date_id='".$_REQUEST['did']."', cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."', cart_pic_date_org='".addslashes(stripslashes($date['date_title']))."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', 
		cart_photo_bg='".$bgphoto['pic_id']."',

		cart_sub_gal_id='".$_REQUEST['sub_id']."' WHERE cart_id='".$cart['cart_id']."' ");
		
		if(!empty($bgphoto['pic_id'])) { 
			$cart_id = $cart['cart_id'];
			$green_screen_cart = true;
			require($setup['path']."/sy-inc/gs-photos.php");
		}

		deleteSQL2("ms_cart_options", "WHERE co_cart_id='".$cart['cart_id']."' ");

		$opts = whileSQL("ms_product_options", "*", "WHERE opt_photo_prod='".$prod['pp_id']."' ORDER BY opt_order ASC ");
		while($opt = mysqli_fetch_array($opts))  {
			if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
				if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")==true) { 
					$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
					$opt_price = $sel['sel_price'];
					$opt_select_name = $sel['sel_name'];
				}
				if($opt['opt_type'] == "text") { 
					$opt_price = $opt['opt_price'];
					$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
				}
				if($opt['opt_type'] == "checkbox") { 
					$opt_price = $opt['opt_price_checked'];
					$opt_select_name = "selected";
				}

				if($opt['opt_type'] == "download") { 
					$opt_price = $opt['opt_price_download'];
					$opt_select_name = _selected_;
					$co_download = '1';
					if($opt['opt_disable_download'] > 0) { 
						updateSQL("ms_cart", "cart_disable_download='1' WHERE cart_id='".$cart['cart_id']."' ");
					}
				}

				insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$cart['cart_id']."' , co_download='".$co_download."', co_download_size='".$opt['opt_download_size']."', co_disable_download='".$opt['opt_disable_download']."' ");
				$co_download = "";
				$opt_price = "";

			}

		}
	}
	mysqli_close($dbcon); exit();
}




if($_REQUEST['action'] == "updatecrop") { 
	$pic = doSQL("ms_photos", "*", "WHERE  pic_key='".$_REQUEST['pic_key']."' ");
	$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cart['cart_photo_prod']."' ");

	updateSQL("ms_cart", "cart_crop_x1='".$_REQUEST['x1']."', cart_crop_y1='".$_REQUEST['y1']."', cart_crop_x2='".$_REQUEST['x2']."', cart_crop_y2='".$_REQUEST['y2']."', cart_crop_rotate='".$_REQUEST['rotate']."'  WHERE cart_id='".$_REQUEST['cart_id']."' ");   
	$cart = doSQL("ms_cart", "*", "WHERE cart_id='".$_REQUEST['cart_id']."' ");
	cropphotoview($cart,$pic,$prod,"pic_th",'0');
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "removebonuscoupon") { 
	$bc = doSQL("ms_cart LEFT JOIN ms_promo_codes ON ms_cart.cart_coupon=ms_promo_codes.code_id", "*", "WHERE ".checkCartSession()."  AND cart_coupon>'0' AND code_print_credit>'0' AND cart_order<='0' AND MD5(cart_id)='".$_REQUEST['cp']."' ");
	if(empty($bc['cart_id'])) { 
		die("unable to find coupon");
	} 
	deleteSQL("ms_cart", "WHERE cart_id='".$bc['cart_id']."' ",1);
	deleteSQL2("ms_cart", "WHERE cart_bonus_coupon='".$bc['cart_id']."' ");

	if(!empty($_SESSION['last_gallery'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		if($date['date_gallery_exclusive'] == "1") { 
			$ge_return_link = $setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/";
		}
	}
	if(!empty($ge_return_link)) { 
		header("location: ".$ge_return_link."?view=checkout");
	} else { 
		header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=checkout");
	}
	session_write_close();
	mysqli_close($dbcon); exit();

}

if(($_REQUEST['action'] == "rp")AND(!empty($_REQUEST['cid']))==true) {
	if(!ctype_alnum($_REQUEST['cid'])) { die("an error has occurred [1]"); }

	$cart = doSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE MD5(cart_id)='".$_REQUEST['cid']."' AND  ".checkCartSession()." AND cart_order='0' ");
	if(empty($cart['cart_id'])) {
		die("Unable to find this item in your shopping cart - ".$_REQUEST['cid']."");
	}
	if($cart['cart_group_id'] > 0) { 
		$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$cart['cart_group_id']."' ");
		if($group['group_require_purchase'] > 0) { 
			if(countIt("ms_cart", "WHERE ".checkCartSession()." AND cart_group_id='".$group['group_id']."'AND cart_order<='0'  ") == 1) { 
				$pcarts = whileSQL("ms_cart", "*", "WHERE ".checkCartSession()." AND  cart_order<='0' AND cart_group_id!='".$group['group_id']."' AND cart_package_photo!='".$cart['cart_id']."' ORDER BY cart_id DESC" );
				if(mysqli_num_rows($pcarts) > 0) { 
					while($pcart = mysqli_fetch_array($pcarts)) { 
						deleteSQL("ms_cart", "WHERE cart_id='".$pcart['cart_id']."'", "1");
					}
				}
			}
		}
	}

	deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
	deleteSQL2("ms_cart", "WHERE cart_package_photo='".$cart['cart_id']."' ");
	deleteSQL2("ms_cart", "WHERE cart_package_photo_extra='".$cart['cart_id']."' ");
	if($cart['cart_package_no_select'] == "1") { 
		$pcarts = whileSQL("ms_cart", "*","WHERE cart_package_include='".$cart['cart_id']."' ");
		while($pcart = mysqli_fetch_array($pcarts)) { 
			deleteSQL("ms_cart", "WHERE cart_id='".$pcart['cart_id']."' ","1");
			deleteSQL2("ms_cart", "WHERE cart_package_photo='".$pcart['cart_id']."' ");
			deleteSQL2("ms_cart", "WHERE cart_package_photo_extra='".$pcart['cart_id']."' ");
		}
	}

	if($cart['cart_bonus_coupon'] > 0) { 
		deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_bonus_coupon']."' ","1");
	}
	if(!empty($_SESSION['last_gallery'])) { 
		$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_SESSION['last_gallery']."' ");
		if($date['date_gallery_exclusive'] == "1") { 
			$ge_return_link = $setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."/";
		}
	}
	if(!empty($ge_return_link)) { 
		header("location: ".$ge_return_link."?view=cart");
	} else { 
		header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart");
	}
	session_write_close();
	mysqli_close($dbcon); exit();

}

if(($_REQUEST['action'] == "removefromcart")AND(!empty($_REQUEST['cid']))==true) {
	if(!ctype_alnum($_REQUEST['cid'])) { die("an error has occurred [1]"); }

	$cart = doSQL("ms_cart", "*", "WHERE MD5(cart_id)='".$_REQUEST['cid']."' AND  ".checkCartSession()." AND cart_order='0' ");
	if(empty($cart['cart_id'])) {
		header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart");
		session_write_close();
		mysqli_close($dbcon); exit();
	}
	deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
	deleteSQL2("ms_cart_options", "WHERE co_cart_id='".$cart['cart_id']."' ");
	if($cart['cart_pic_id'] > 0) { 
		deleteSQL2("ms_cart", "WHERE cart_package_photo_extra_on='".$cart['cart_pic_id']."'AND cart_order<='0'  AND ".checkCartSession()." ");
	}
	if($cart['cart_product_select_photos'] > 0) { 
		deleteSQL2("ms_cart", "WHERE cart_product_photo='".$cart['cart_id']."' ");
	}
	$total = shoppingCartTotal($mssess);
	if($total['total_items'] <= 0) { 
		print "remove";
	}
	if(!empty($_REQUEST['dcd'])) { 
		header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart");
		session_write_close();
		mysqli_close($dbcon); exit();
	}
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "newqtyprice") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pic_id']."' ");
	$con = doSQL("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "*", "WHERE pc_id='".$_REQUEST['pc_id']."' ");

	if(countIt("ms_photo_products_discounts","WHERE dis_prod='".$con['pc_id']."' ") > 0) { 
		$qtydiscounts = 1;
		if($con['pc_qty_on'] == "1") { 
			$qcart =doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' AND cart_pic_id='".$pic['pic_id']."' GROUP BY cart_photo_prod_connect ");
			$total_in_cart =$qcart['qty'];
		} else { 
			$qcart = doSQL("ms_cart", "*, COUNT(*) AS count, SUM(cart_qty) AS qty", "WHERE cart_photo_prod>'0' AND ".checkCartSession()." AND  cart_order='0' AND cart_photo_prod_connect='".$con['pc_id']."' GROUP BY cart_photo_prod_connect  ");
			$total_in_cart = $qcart['qty'];
		}
		$total_in_cart = $total_in_cart + 1;
		$qprice = doSQL("ms_photo_products_discounts", "*", "WHERE dis_prod='".$con['pc_id']."' AND dis_qty_from<='".$total_in_cart."' AND (dis_qty_to>='".$total_in_cart."' OR dis_qty_to='0') ");
		$dprice = $qprice['dis_price'];
		$new_cart_total = $total_in_cart - 1;
	}
	if($dprice <= 0) { 
		if($con['pc_price'] > 0) { 
			$price = $con['pc_price'];
		} else { 
			$price = $con['pp_price'];
		}
	} else { 
		$qd = "1";
		$price = $dprice;
	}

	if(($con['pp_taxable'] && $site_setup['include_vat'] == "1")==true) { 
		$price = $price+ (($price * $site_setup['include_vat_rate']) / 100);
	}

	if($qd == "1") { print "* "; } print showPrice($price)."|"; if($qd == "1") { print _price_based_on_quantity_discount_." (".$new_cart_total.")"; }
	mysqli_close($dbcon); exit();

}


if($_REQUEST['action'] == "updateqty"){
	if(!ctype_alnum($_REQUEST['cid'])) { die("an error has occurred [1]"); }
	if(!is_numeric($_REQUEST['prod_qty'])) { die("an error has occurred [1]"); }

	$cart = doSQL("ms_cart", "*", "WHERE MD5(cart_id)='".$_REQUEST['cid']."' AND  ".checkCartSession()."");
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$cart['cart_store_product']."'  ");

	 if($date['prod_inventory_control'] == "1") {
		if(!empty($cart['cart_sub_id'])) {
			$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$cart['cart_sub_id']."' ");
			$onhand = $sub['sub_qty'];
		} else { 
			$onhand = $date['prod_qty'];
		}
		if($_REQUEST['prod_qty'] > $onhand) { 
			$_REQUEST['prod_qty'] = $onhand;
		}
	 }
	if($_REQUEST['prod_qty'] < $date['qty_min']) { 
		$_REQUEST['prod_qty'] = $date['qty_min'];
	}
	
	if(empty($cart['cart_id'])) {
		die("Unable to find this item in your shopping cart");
	}
	if($_REQUEST['prod_qty'] <=0) { 
		deleteSQL("ms_cart", "WHERE cart_id='".$cart['cart_id']."' ","1");
	} else { 
		updateSQL("ms_cart", "cart_qty='".$_REQUEST['prod_qty']."' WHERE cart_id='".$cart['cart_id']."' ");
	}
	header("location: ".$setup['temp_url_folder']."/".$site_setup['index_page']."?view=cart");
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "getTax") { 
	if(!empty($_REQUEST['total'])) { 
		if(!is_numeric($_REQUEST['total'])) { die("an error has occurred [2] (".$_REQUEST['total'].")"); }
	}

	$zip = doSQL("ms_tax_zips", "*", "WHERE zip='".$_REQUEST['zip']."' ");
	if($zip['tax'] > 0) { 
		$percent = $zip['tax'];
	} else { 
		$per = doSQL("ms_states", "*", "WHERE state_abr='".$_REQUEST['sid']."' ");
		$percent = $per['state_tax'];
	}
	if(($_REQUEST['pickup'] == "1") && ($store['tax_address'] == "shipping") == true) { 
		$localtax = doSQL("ms_states LEFT JOIN ms_countries ON ms_states.state_country=ms_countries.country_name", "*", "WHERE ms_countries.def='1' AND ms_states.state_tax>'0' ");
		$percent = $localtax['state_tax'];
		if($store['pickup_tax_rate'] > 0) { 
			$percent = $store['pickup_tax_rate'];
		}

	}
	if(isset($_SESSION['tax_percentage'])) { 
		$tax = $_REQUEST['total'] * $_SESSION['tax_percentage'] / 100;
		$percent = $_SESSION['tax_percentage'];
	} else { 
		$tax = $_REQUEST['total'] * $percent / 100;
	}
	$ct = doSQL("ms_countries", "*", "WHERE country_name='".$_REQUEST['country']."' ");
	$vat = $_REQUEST['total'] * $ct['vat'] / 100;
	$tax = round($tax,2);
	print $tax."|".$percent."|".$vat."|".$ct['vat'];
	mysqli_close($dbcon); exit();
}


if($_REQUEST['action'] == "updatecartmenu") {
	$total = shoppingCartTotal($mssess);
	$pay_total = $total['show_cart_total'];
	print $total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($pay_total);
	mysqli_close($dbcon); exit();
}
if($_REQUEST['action'] == "updatecartmenuitems") {
	$total = shoppingCartTotal($mssess);
	$pay_total = $total['show_cart_total'];
	print $total['total_items'];
	mysqli_close($dbcon); exit();
}


/*if($_REQUEST['action'] == "updatecartmobilemenum") {
	$total = shoppingCartTotal($mssess);
	$pay_total = $total['show_cart_total'];
	print $total['total_items']." ";  if($total['total_items'] > 1) { print _items_; } else { print _item_; } print " ".showPrice($pay_total);
	mysqli_close($dbcon); exit();
}
if($_REQUEST['action'] == "updatecartmobilemenuitems") {
	$total = shoppingCartTotal($mssess);
	$pay_total = $total['show_cart_total'];
	print $total['total_items'];
	mysqli_close($dbcon); exit();
}*/

if($_REQUEST['action'] == "updateTotal") {
	$total = shoppingCartTotal($mssess);
	print $total['show_cart_total'];
	mysqli_close($dbcon); exit();
}


if($_REQUEST['action'] == "getShipping") { 
	$total = shoppingCartTotal($mssess);
	if(!empty($_REQUEST['total'])) { 
		if(!is_numeric($_REQUEST['total'])) { die("an error has occurred [SHPB]"); }
	}

	$sg = array();
	$shipping_group_checks = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_pic_date_id=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_pic_date_id>='0'  AND cart_order<='0' AND shipping_group>'1'  ");
	while($shipping_group_check = mysqli_fetch_array($shipping_group_checks)) { 
		array_push($sg,$shipping_group_check['shipping_group']);
	}
		// check for store items
	$shipping_group_checks = whileSQL("ms_cart LEFT JOIN ms_calendar ON ms_cart.cart_store_product=ms_calendar.date_id", "*", "WHERE ".checkCartSession()." AND cart_store_product>='0'  AND cart_order<='0' AND shipping_group>'1'  ");
	while($shipping_group_check = mysqli_fetch_array($shipping_group_checks)) { 
		array_push($sg,$shipping_group_check['shipping_group']);
	}


	foreach($sg AS $group) { 
		$ships = whileSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$group."' ORDER BY method_order ASC ");
		while($ship = mysqli_fetch_array($ships)) { 
			$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$ship['method_id']."' AND price_from<='".$_REQUEST['total']."' AND price_to>='".$_REQUEST['total']."' ");
			if($price['price_amount'] >= $group_price) { 
				$group_price = $price['price_amount'];
				$group_id = $group;
			}
		}
	}

	if($group_id > 0) { 
		$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_id='".$group_id."' ");
	} else { 
		$sg = doSQL("ms_shipping_groups", "*", "WHERE sg_default='1' ");
	}

	if(countIt("ms_shipping_methods",  "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ") <=0) { 
		$ship = false;
	}




	$state = doSQL("ms_states", "*", "WHERE state_abr='".$_REQUEST['state']."' ");
	$ships = whileSQL("ms_shipping_methods", "*", "WHERE method_status='1' AND method_group='".$sg['sg_id']."' ORDER BY method_order ASC ");
	while($ship = mysqli_fetch_array($ships)) { 
		$country = doSQL("ms_countries", "*", "WHERE country_name='".$_REQUEST['country']."' ");
		$price = doSQL("ms_shipping_prices", "*", "WHERE price_method='".$ship['method_id']."' AND price_from<='".$_REQUEST['total']."' AND price_to>='".$_REQUEST['total']."' ");
		$pt++;
		$price['price_amount'] = $total['add_ship'] + $price['price_amount'];
		$price = $price['price_amount'] + (($price['price_amount'] * $state['state_add_ship_percent']) / 100) + (($price['price_amount'] * $country['add_price']) / 100) ;
		if($ship['method_pickup'] == "1") { 
			$price = 0;
		}
		?>
		<div>

		<div class="pc" <?php if($pt <=1) { print "id=\"shipdefault\" price=\"".$price."\""; } ?>><input type="radio" class="checkbox" name="ship_select" id="ship_select-<?php print $ship['method_id'];?>" price="<?php print $price;?>" priceshow="<?php print showPrice($price);?>" onClick="addshipping();" value="<?php print $ship['method_id'];?>" <?php if($pt <=1) { print "checked"; } ?> data-pickup="<?php print $ship['method_pickup'];?>"> <label for="ship_select-<?php print $ship['method_id'];?>"><span class="h3"><?php print $ship['method_name'].": "; if($price <= 0) { print _free_; } else { print showPrice($price); } ?></span></label></div>
		<?php if(!empty($ship['method_descr'])) { ?><div class="pc"><?php print nl2br($ship['method_descr']);?></div><?php } ?>
		</div>
		<div>&nbsp;</div>
	<?php } ?>
<?php 
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "addfavstocollection") { 
	$fav = array();
	if(empty($_SESSION['pid'])) { die(); } 
	if(empty($_REQUEST['cart_id'])) { die(); } 

	$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC");
	while($pic = mysqli_fetch_array($pics)) { 
		$ck = doSQL("ms_cart", "*", "WHERE cart_pic_id='".$pic['pic_id']."' AND cart_package_photo='".$_REQUEST['cart_id']."' ");
		if(empty($ck['cart_id'])) { 
			array_push($fav,$pic['fav_id']);
		}
	}
	$x = 0;
	$tps = whileSQL("ms_cart", "*", "WHERE cart_package_photo='".$_REQUEST['cart_id']."' AND cart_pic_id<='0' ORDER BY cart_id ASC");
	//print "<Li>Total available: ".mysqli_num_rows($tps);
	while($tp = mysqli_fetch_array($tps)) { 
	//	print "<li>".$tp['cart_id'];
		if(!empty($fav[$x])) { 
			$pic = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND fav_id='".$fav[$x]."' ");
			if(!empty($pic['pic_id'])) { 
		//		print "<li>".$pic['pic_org'];
		updateSQL("ms_cart", "cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."' , cart_sub_id='".$pic['fav_sub_id']."', cart_pic_id='".$pic['pic_id']."', cart_pic_date_id='".$pic['fav_date_id']."', cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."', cart_pic_date_org='".addslashes(stripslashes($fav['date_title']))."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."',  cart_photo_bg='".$bgphoto['pic_id']."',cart_sub_gal_id='".$fav['fav_sub_id']."' WHERE cart_id='".$tp['cart_id']."' ");
			}
		}
		$x++;
	}
}



if($_REQUEST['action'] == "addtofavs") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($person['p_id'])) {
		die("Unable to find this person in the database");
	}
	$ckfav = doSQL("ms_favs", "*", "WHERE fav_pic='".$pic['pic_id']."' AND fav_person='".$person['p_id']."' ");
	if(empty($ckfav['fav_id'])) { 
		insertSQL("ms_favs", "fav_pic='".$pic['pic_id']."', fav_person='".$person['p_id']."', fav_date_time='".date('Y-m-d H:i:s')."', fav_date_id='".$_REQUEST['did']."', fav_sub_id='".$_REQUEST['sub_id']."', fav_bg='".$_REQUEST['gsbg']."' ");
	}
	$total_images = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ");
	print $total_images;
	mysqli_close($dbcon); exit();

}
if($_REQUEST['action'] == "removefromfavs") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($person['p_id'])) {
		die("Unable to find this person in the database");
	}
	deleteSQL("ms_favs", "WHERE fav_pic='".$pic['pic_id']."' AND fav_person='".$person['p_id']."'", "1");
	$total_images = countIt("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) ORDER BY pic_org ASC ");
	print $total_images;
	mysqli_close($dbcon); exit();
}

if($_REQUEST['action'] == "hidephoto") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($person['p_id'])) {
		die("Unable to find this person in the database");
	}
	if(empty($_REQUEST['did'])) {
		die("Unable to find gallery");
	}
	if(!is_numeric($_REQUEST['did'])) { die("not numeric"); } 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	if($date['date_owner'] !== $person['p_id']) { die(" not gallery owner"); } 

	updateSQL("ms_photos", "pic_hide='1', pic_hide_by='".$person['p_id']."' WHERE pic_id='".$pic['pic_id']."' ");
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "unhidephoto") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	if(empty($person['p_id'])) {
		die("Unable to find this person in the database");
	}
	if(empty($_REQUEST['did'])) {
		die("Unable to find gallery");
	}
	if(!is_numeric($_REQUEST['did'])) { die("not numeric"); } 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	if($date['date_owner'] !== $person['p_id']) { die("not gallery owner"); } 

	updateSQL("ms_photos", "pic_hide='0', pic_hide_by='0' WHERE pic_id='".$pic['pic_id']."' ");
	mysqli_close($dbcon); exit();

}

if($_REQUEST['action'] == "addtocompare") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	if(!is_array($_SESSION['comparephotos'])) { 
		$_SESSION['comparephotos'] = array();
	}
	if(!in_array($pic['pic_id']."|".$_REQUEST['did']."|".$_REQUEST['sub_id'],$_SESSION['comparephotos'])) { 
		array_push($_SESSION['comparephotos'],$pic['pic_id']."|".$_REQUEST['did']."|".$_REQUEST['sub_id']);
	}
	print count($_SESSION['comparephotos']);
	mysqli_close($dbcon); exit();
}

if($_REQUEST['action'] == "removecompare") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pid']."' ");
	if(empty($pic['pic_id'])) {
		die("Unable to find this product in the database");
	}
	if(!is_array($_SESSION['comparephotos'])) { 
		$_SESSION['comparephotos'] = array();
	}
	$key = array_search($pic['pic_id']."|".$_REQUEST['did']."|".$_REQUEST['sub_id'],$_SESSION['comparephotos']);
	if($key >= 0){
		unset($_SESSION['comparephotos'][$key]);
	}	
	print count($_SESSION['comparephotos']);
	mysqli_close($dbcon); exit();
}
if($_REQUEST['action'] == "removecompareview") { 
	if(!is_array($_SESSION['comparephotos'])) { 
		$_SESSION['comparephotos'] = array();
	}
	$key = array_search($_REQUEST['p'],$_SESSION['comparephotos']);
	if($key >= 0){
		unset($_SESSION['comparephotos'][$key]);
	}	
	print count($_SESSION['comparephotos']);
	mysqli_close($dbcon); exit();
}


if($_REQUEST['action'] == "removeallcompare") { 
	unset($_SESSION['comparephotos']);
	mysqli_close($dbcon); exit();
}

if($_REQUEST['action'] == "comparephotosshow") { 
	
	?>
	<script>
	$(document).ready(function(){
		$(".comparetd").hover(
		  function () {
			$(this).find('.compareactionscontainer').show();
		  },
		  function () {
			$(this).find('.compareactionscontainer').hide();
		  }
		);
	});
	</script>
		<table class="comparetable">
			<tr valign="middle" id="comparetr">
			<?php foreach($_SESSION['comparephotos'] AS $p) {
				$tp = explode("|",$p);
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$tp[0]."' ");
				$date = doSQL("ms_calendar", "*", "WHERE date_id='".$tp[1]."' ");
				if(!empty($tp[2])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$tp[2]."' ");
				}

				$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
				$size = getimagefiledems($pic,'pic_large');

				?>
				<td class="comparetd" id="ctd-<?php print $pic['pic_id'];?>"><a href="<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/<?php if(!empty($tp[2])) { ?>?sub=<?php print $sub['sub_link'];?><?php } ?>#photo=<?php print $pic['pic_key'];?>"><img id="cp-<?php print $pic['pic_id'];?>" src="<?php print getimagefile($pic,'pic_large');?>" style=" max-height: <?php print $size[1];?>px; height: 100%;"  class="comparephoto photo" ww="<?php print $size[0];?>" hh="<?php print $size[1];?>"></a>
				<div class="compareactionscontainer" id="pa-<?php print $pic['pic_key'];?>">
					<ul class="compareactions">
					<li onclick="removecompareview('<?php print $p;?>','ctd-<?php print $pic['pic_id'];?>', '<?php print $pic['pic_key'];?>'); return false;"><?php print _compare_remove_;?></li>
					<li onclick="compareviewclick('<?php print $setup['temp_url_folder'];?><?php print $setup['content_folder']."".$cat['cat_folder']."/".$date['date_link'];?>/<?php if(!empty($tp[2])) { ?>?sub=<?php print $sub['sub_link'];?><?php } ?>#photo=<?php print $pic['pic_key'];?>'); return false;"><?php print _compare_view_;?></li>
					</ul>
				</div>
				</td>
				<?php } ?>

			</tr>
		</table>


	<?php 
}




if($_POST['action'] == "preregister") { 
	if(empty($_REQUEST['reg_first_name'])) { 
		die();
	}
	if(empty($_REQUEST['reg_last_name'])) { 
		die("No good");
	}
	if(empty($_REQUEST['reg_email'])) { 
		die("No good");
	}
	if(empty($_POST['did'])) { 
		die("No good");
	}
	if(!is_numeric($_POST['did'])) { 
		die();
	}
	$_POST['reg_email'] = strip_tags($_POST['reg_email']);
	$_POST['reg_first_name'] = strip_tags($_POST['reg_first_name']);
	$_POST['reg_last_name'] = strip_tags($_POST['reg_last_name']);

	$_POST['reg_email'] = sql_safe($_POST['reg_email']);
	$_POST['reg_first_name'] = sql_safe($_POST['reg_first_name']);
	$_POST['reg_last_name'] = sql_safe($_POST['reg_last_name']);

	$ck = doSQL("ms_pre_register", "*", "WHERE reg_date_id='".addslashes(stripslashes($_POST['did']))."' AND reg_email='".addslashes(stripslashes($_POST['reg_email']))."' ");
	if(empty($ck['reg_id'])) { 
		insertSQL("ms_pre_register", "reg_email='".addslashes(stripslashes($_POST['reg_email']))."', reg_first_name='".addslashes(stripslashes($_POST['reg_first_name']))."', reg_last_name='".addslashes(stripslashes($_POST['reg_last_name']))."', reg_date_id='".addslashes(stripslashes($_POST['did']))."', reg_date='".date('Y-m-d H:i:s')."', reg_ip='".getUserIP()."' ");
	}
	print "good";
	mysqli_close($dbcon); exit();
}





if($_POST['action'] == "requestaccess") { 
	if(empty($setup['send_from_email'])) { 
		$setup['send_from_email'] = $site_setup['contact_email'];
	}
	if(empty($_REQUEST['req_name'])) { 
		die();
	}
	if(empty($_REQUEST['req_email'])) { 
		die("No good");
	}
	if(empty($_POST['did'])) { 
		die("No good");
	}
	if(!is_numeric($_POST['did'])) { 
		die();
	}
	$from_email = $_REQUEST['req_email'];
	$from_name = $_REQUEST['req_name'];

	foreach($_POST AS $id => $value) {
		$_POST[$id] = sql_safe("".$_POST[$id]."");
		$_POST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
	}


	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_POST['did']."'  ");
	if(!empty($_POST['sub_id'])) { 
		$date = doSQL("ms_sub_galleries LEFT JOIN ms_calendar ON ms_sub_galleries.sub_date_id=ms_calendar.date_id LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE sub_id='".$_POST['sub_id']."'  ");
	}

	// Checking for gallery owner // 
	if($date['date_owner'] > 0) { 

		############ SEND TO GALLERY OWNER ###############

		$person = doSQL("ms_people", "*", "WHERE p_id='".$date['date_owner']."' ");
		$em = doSQL("ms_emails", "*", "WHERE email_id_name='requestaccess' ");
		$from_email = $_POST['req_email'];
		$from_name = $_POST['req_name']." via ".stripslashes($site_setup['website_title']);
		$setup['send_from_email'] = $site_setup['contact_email'];
		$to_email = $person['p_email'];
		$to_name = stripslashes($person['p_name']);
		$subject = "".$em['email_subject']."";
		$message = $em['email_message'];

		$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
		$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
		$message = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$message");
		$message = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$message");
		$message = str_replace("[EMAIL]",$person['p_email'], "$message");
		$message = str_replace("[PAGE_TITLE]",$date['date_title'], "$message");
		$message = str_replace("[PAGE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $message);
		$message = str_replace("[/PAGE_LINK]","</a>", $message);
		$message = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">", $message);
		$message = str_replace("[/LINK_TO_WEBSITE]","</a>", $message);

		$message = str_replace("[FROM_NAME]",stripslashes($_POST['req_name']), "$message");
		$message = str_replace("[FROM_EMAIL]",$_POST['req_email'], "$message");
		$message = str_replace("[FROM_MESSAGE]",stripslashes(nl2br($_POST['req_message'])), "$message");


		$subject = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$subject");
		$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");
		$subject = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$subject");
		$subject = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$subject");
		$subject = str_replace("[EMAIL]",$person['p_email'], "$subject");
		$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
		$subject = str_replace("[PAGE_LINK]","<a href=\"".$setup['url'].$setup['temp_url_folder'].$setup['content_folder'].$date['cat_folder']."/".$date['date_link']."\">", $subject);
		$subject = str_replace("[/PAGE_LINK]","</a>", $subject);
		$subject = str_replace("[LINK_TO_WEBSITE]","<a href=\"".$setup['url'].$setup['temp_url_folder']."\">", $subject);
		$subject = str_replace("[/LINK_TO_WEBSITE]","</a>", $subject);
		$subject = str_replace("[FROM_NAME]",stripslashes($_POST['req_name']), "$subject");
		$subject = str_replace("[FROM_EMAIL]",$_POST['req_email'], "$subject");
		$subject = str_replace("[FROM_MESSAGE]",stripslashes($_POST['req_message']), "$subject");



		sendWebdEmail($person['p_email'], $person['p_name']." ".$person['p_last_name'], $from_email, $from_name, $subject, $message,"1");
	} else { 

		############ SEND TO ADMIN ###############


		$subject = $_POST['req_name']." has requested access to \"".$date['date_title']."\"";
		if(!empty($date['sub_name'])) { 
			$subject .= " > ".$date['sub_name'];
		}

		$message = $_POST['req_name']."  (".$_POST['req_email'].")  has requested access to the page \"".$date['date_title']."\"";
		if(!empty($date['sub_name'])) { 
			$message .= " > ".$date['sub_name'];
		}

		$message .= ":\r\n\r\n";
		if(!empty($date['sub_link'])) { 
			$message .= $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$date['sub_link']."\r\n";
		} else { 
			$message .= $setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."\r\n";
		}
		$message  .= "They also sent the following message:\r\n\r\n-------------------------------------------------------------------------------------\r\n\r\n".$_POST['req_message'];
		$message .= "\r\n-------------------------------------------------------------------------------------\r\n\r\n\r\n";


		$to_email = $site_setup['contact_email'];
		$to_name = $site_setup['website_title'];

		stripslashes($message);
		stripslashes($subject);

		sendWebdEmail($to_email, $to_name, $from_email, $from_name, $subject, $message,0);
	}
	print "good";
	mysqli_close($dbcon); exit();

}


if($_REQUEST['action'] == "proofing") { 
	$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pic']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	// print "HERE";
	if((!empty($_REQUEST['status'])) && (!is_numeric($_REQUEST['status'])) == true) { 
		die();
	}
	if(($_REQUEST['status'] == "1") || ($_REQUEST['status'] == "3") == true) { 
		$ck = doSQL("ms_proofing", "*", "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$pic['pic_id']."' ");
		if(empty($ck['proof_id'])) { 
			insertSQL("ms_proofing", "proof_date_id='".$date['date_id']."', proof_pic_id='".$pic['pic_id']."', proof_person='".$person['p_id']."', proof_status='".$_REQUEST['status']."', proof_date='".date('Y-m-d H:i:s')."' ");
		} else { 
			updateSQL("ms_proofing", "proof_status='".$_REQUEST['status']."', proof_date='".date('Y-m-d H:i:s')."' WHERE proof_id='".$ck['proof_id']."' ");
		}
	}

	if($_REQUEST['status'] == "2") { 
		$message =strip_tags($_POST['proof_comment']);
		$ck = doSQL("ms_proofing", "*", "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$pic['pic_id']."' ");
		if(empty($ck['proof_id'])) { 
			insertSQL("ms_proofing", "proof_date_id='".$date['date_id']."', proof_pic_id='".$pic['pic_id']."', proof_date='".date('Y-m-d H:i:s')."', proof_person='".$person['p_id']."', proof_status='2', proof_comment='". addslashes(stripslashes($message))."' ");
		} else { 
			updateSQL("ms_proofing", "proof_status='2', proof_date='".date('Y-m-d H:i:s')."',  proof_comment='". addslashes(stripslashes($message))."' WHERE proof_id='".$ck['proof_id']."' ");
		}
		print nl2br($message);
	}
	mysqli_close($dbcon); exit();

}
if($_POST['action'] == "proofingcomplete") { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
	$message =strip_tags($_POST['review_complete_message']);

	insertSQL("ms_proofing_status", "date_id='".$date['date_id']."', person='".$person['p_id']."', date='".date('Y-m-d H:i:s')."', ip='".getUserIP()."', notes='".addslashes(stripslashes($message))."', status='1'  ");

	$em = doSQL("ms_emails", "*", "WHERE email_id_name='viewproofsadmin' ");
	if(empty($em['email_from_email'])) {
		$from_email = $site_setup['contact_email'];
	} else {
		$from_email = $em['email_from_email'];
	}

	if(empty($em['email_from_name'])) {
		$from_name = $site_setup['website_title'];
	} else {
		$from_name = $em['email_from_name'];
	}
	$subject = "".$em['email_subject']."";
	$message = $em['email_message'];

	$message = str_replace("[URL]",$setup['url'].$setup['temp_url_folder'], "$message");
	$message = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$message");
	$message = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$message");
	$message = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$message");
	$message = str_replace("[EMAIL]",$person['p_email'], "$message");
	$message = str_replace("[PAGE_TITLE]",$date['date_title'], "$message");


	$subject = str_replace("[FIRST_NAME]",stripslashes($person['p_name']), "$subject");
	$subject = str_replace("[LAST_NAME]",stripslashes($person['p_last_name']), "$subject");
	$subject = str_replace("[EMAIL]",$person['p_email'], "$subject");
	$subject = str_replace("[PAGE_TITLE]",$date['date_title'], "$subject");
	$subject = str_replace("[WEBSITE_NAME]",stripslashes($site_setup['website_title']), "$subject");

	$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
	$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id ";
	$picsd = whileSQL("$pics_tables", "*", "$pics_where $and_where GROUP BY pic_id   ");
	$total_images = mysqli_num_rows($picsd);
	while($picd = mysqli_fetch_array($picsd)) { 
		if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='1' ")> 0) { 
			$total_done++;	
		}
		if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='2' ")> 0) { 
			$total_rev++;	
		}
		if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' AND proof_status='3' ")> 0) { 
			$total_rejected++;	
		}

	}

	if($total_rev <=0) { 
		$total_rev = "0";
	}
	if($total_done <=0) { 
		$total_done = "0";
	}
	if($total_rejected <=0) { 
		$total_rejected = "0";
	}

	$message = str_replace("[TOTAL_APPROVED]",$total_done, "$message");
	$message = str_replace("[TOTAL_REVISIONS]",$total_rev, "$message");
	$message = str_replace("[TOTAL_REJECTED]",$total_rejected, "$message");
	sendWebdEmail($from_email,$from_name,$from_email,$from_name,$subject,$message,"1");

}

if($_REQUEST['action'] == "totalproofviewed") { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
	$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
	$picsd = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id   ");
	$total_images = mysqli_num_rows($picsd);
	while($picd = mysqli_fetch_array($picsd)) { 
		if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' ")> 0) { 
			$total_done++;	
		}
	}
	print $total_done;
}
if($_POST['action'] == "saveregmessage") { 
	foreach($_POST AS $id => $value) {
		$_POST[$id] = sql_safe("".$_POST[$id]."");
		$_POST[$id] = addslashes(stripslashes(stripslashes(strip_tags($value))));
	}
	$new_message = nl2br($_POST['newm']);

	$date = doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_POST['did']."' AND MD5(reg_person)='".$_SESSION['pid']."' ");
	if(empty($date['date_id'])) { die("not found"); } 
	updateSQL("ms_calendar", "date_text='".addslashes(stripslashes($new_message))."' WHERE date_id='".$date['date_id']."' ");
	print $new_message;
	mysqli_close($dbcon); exit();
}

if($_REQUEST['action'] == "getpagecontent") { 
	if(!is_numeric($_REQUEST['did'])) { die(); } 
	if(empty($_REQUEST['did'])) { die(); } 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['did']."' ");
	print $date['date_text'];
}

#### Create a function for adding packages to cart ##########

function addpackagetocart($pack,$package_include,$cart_package_include) { 
	global $setup,$site_setup,$bgphoto,$list,$person;
	$con = doSQL("ms_photo_products_connect", "*", "WHERE pc_id='".$_POST['prod_id']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_POST['did']."' ");

	if($con['pc_package'] > 0) { 
	// 	$pack = doSQL("ms_packages", "*", "WHERE package_id='".$pack['pack']."' ");
		$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$con['pc_group']."' ");
		if(empty($pack['package_id'])) { 
			die("Unable to find product");
		}

		if($pack['package_ship'] =="1") {
			$cart_ship = 1;
		}
		if($con['pc_price'] > 0) { 
			$cart_price = $con['pc_price'];
		} else { 
			$cart_price = $pack['package_price'];
		}

		if($pack['package_buy_all'] == "1") { 

			if($_REQUEST['view'] == "favorites") { 
				$and_where = "";
				$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
				$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
				$pics_orderby = "pic_org";
			} else { 

				if(!empty($date['date_photo_keywords'])) { 
					$and_date_tag = "( ";
					$date_tags = explode(",",$date['date_photo_keywords']);
					foreach($date_tags AS $tag) { 
						$cx++;
						if($cx > 1) { 
							$and_date_tag .= " OR ";
						}
						$and_date_tag .=" key_key_id='$tag' ";
					}
					$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
					$and_date_tag .= " ) ";
					$and_where = getSearchString();
					$pics_where = "WHERE $and_date_tag $and_where ";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
				} else { 

					$and_where = getSearchString();
					// print "<pre>"; print_r($_REQUEST); 
					if(!empty($_REQUEST['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
						$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}
					$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
					$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
				}
			}
			if(($date['date_owner'] >'0') && ($date['date_owner'] == $person['p_id']) == true) { 
				// Is gallery owner
			} else { 
				$and_where .= " AND pic_hide!='1' ";
			}


			$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where   AND pic_no_dis<='0' GROUP BY pic_id ");
			$total_images = mysqli_num_rows($pics);

				if($pack['package_buy_all_price_type'] == "1") { 
					$cart_price = $total_images * $pack['package_buy_all_each'];
				} elseif($pack['package_buy_all_price_type'] == "2") { 
					$getprice = doSQL("ms_packages_buy_all", "*", "WHERE ba_package='".$pack['package_id']."' AND ((ba_from<='$total_images' AND ba_to>='$total_images' ) OR (ba_from<='$total_images' AND ba_to='0'))  ");
					$cart_price = $getprice['ba_price'] * $total_images;
				} elseif($pack['package_buy_all_price_type'] == "3") { 
					$cart_price = $pack['package_buy_all_set_price'];
				}
			print "cart price: ".showPrice($cart_price)." ---- ";
		}


		if(!empty($group['group_name'])) { 
			$product_name = $group['group_name']." > ".$pack['package_name'];
		} else { 
			$product_name = $pack['package_name'];
		}

		 if($pack['package_buy_all'] == "1") { 
			if($_REQUEST['view'] == "favorites") { 
				$product_name = _purchase_all_favorites_;
			} else { 
				$product_name =  $date['date_title']; 
				 if(!empty($_REQUEST['sub_id'])) { 
					$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
					$ids = explode(",",$sub['sub_under_ids']);
					foreach($ids AS $val) { 
						if($val > 0) { 
							$upsub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$val."' ");
							$product_name .= " > ".$upsub['sub_name']."  ";
						}
					}
					
					$product_name .= " > ".$sub['sub_name'];
				}

				if(!empty($_REQUEST['kid'])) { 
					if(!is_numeric($_REQUEST['kid'])) { die(); } 
					$keyword = doSQL("ms_photo_keywords", "*", "WHERE id='".$_REQUEST['kid']."' ");
				}
				if(!empty($keyword['key_word'])) { 
					$product_name .= " > "._with_key_word_.": '".$keyword['key_word']."' ";
				}
				if(!empty($_REQUEST['keyWord'])) { 
					$product_name .= " > "._with_key_word_.": '".$_REQUEST['keyWord']."' ";
				}
			}
			$product_name .= " (".$total_images." "._photos_word_photos_.") ".$pack['package_name'].""; 
		 }

		if($pack['package_add_ship'] > 0) { 
			$cart_extra_ship = $pack['package_add_ship'];
		}
		if($package_include == true) { 
			$cart_price = "0.00";
			$cart_ship = "0";
			if($pack['package_buy_all'] !== "1") { 
				$product_name = $pack['package_name'];
			}
		}
		if($cart_package_include > 0) { 
		//	$mainpack = doSQL("ms_cart LEFT JOIN ms_packages ON ms_cart.cart_package=ms_packages.package_id", "*", "WHERE cart_id='".$cart_package_include."' ");
		//	$product_name = $mainpack['package_name']." > ".$product_name; 
		}

		$_POST['qty'] = sql_safe($_POST['qty']);

		if((!customerLoggedIn()) && (isset($_COOKIE['myemail'])) == true)  { 
			$cart_email = strtolower(trim($_COOKIE['myemail']));
		}
		

		$p_cart_id = insertSQL("ms_cart", "cart_qty='".$_POST['qty']."', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='".$cart_price."', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$date['date_id']."', cart_cost='".$pack['package_cost']."' , cart_group_id='".$group['group_id']."', cart_credit='".$pack['package_credit']."', cart_extra_ship='".$cart_extra_ship."', cart_package_buy_all='".$pack['package_buy_all']."', cart_no_discount='".$pack['package_no_discount']."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_sku='".addslashes(stripslashes($pack['package_internal_name']))."', cart_package_include='".$cart_package_include."', cart_email='".addslashes(stripslashes($cart_email))."'  ");


		if($pack['package_buy_all'] == "1") { 

			$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$pack['package_buy_all_product']."'  ");
			if($_REQUEST['view'] == "favorites") { 
				$and_where = "";
				$pics_where = "WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' )";
				$pics_tables = "ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id";
				$pics_orderby = "pic_org";
			} else { 
		
				if(!empty($date['date_photo_keywords'])) { 
					$cx = 0;
					$and_date_tag = "( ";
					$date_tags = explode(",",$date['date_photo_keywords']);
					foreach($date_tags AS $tag) { 
						$cx++;
						if($cx > 1) { 
							$and_date_tag .= " OR ";
						}
						$and_date_tag .=" key_key_id='$tag' ";
					}
					$and_date_tag .= " OR bp_blog='".$date['date_id']."' ";
					$and_date_tag .= " ) ";
					$and_where = getSearchString();
					$pics_where = "WHERE $and_date_tag $and_where ";
					$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
				} else { 

					if(!empty($_REQUEST['sub_id'])) { 
						$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
						$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
					} else { 
						if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
							$and_sub = "AND bp_sub='0' ";
						}
					}

					$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
					$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
				}
			}
			$pics = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where  AND pic_no_dis<='0' GROUP BY pic_id ");

			$bg_photo_pic_id = $bgphoto['pic_id'];

			while($pic = mysqli_fetch_array($pics)) { 

				$ext = strtolower(substr($pic['pic_org'], -4));
				if($ext !== ".png") { 
					$bgphoto['pic_id'] = 0;
				} else { 
					$bgphoto['pic_id'] = $bg_photo_pic_id;
				}

				$cart_download = 0;
				$cart_ship = 0;
				if($prod['pp_type'] =="download") {
					$cart_download = 1;
				} else { 
					$cart_ship = 1;
				}
				if($prod['pp_include_download'] =="1") {
					$cart_download = 1;
				}

				if($_REQUEST['view'] == "favorites") { 
					$cart_id = insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$pack['package_buy_all_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_disable_download='".$prod['pp_disable_download']."', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_allow_notes='".$list['list_allow_notes']."', cart_sub_gal_id='".$pic['fav_sub_id']."', cart_pic_id='".$pic['pic_id']."', 
					cart_photo_bg='".$bgphoto['pic_id']."',
					cart_pic_date_id='".$pic['date_id']."' , cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."'  ");
	
				} else { 
					$cart_id = insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$pack['package_buy_all_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_disable_download='".$prod['pp_disable_download']."', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_allow_notes='".$list['list_allow_notes']."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_pic_id='".$pic['pic_id']."', 
					cart_photo_bg='".$bgphoto['pic_id']."',
					cart_pic_date_id='".$date['date_id']."' , cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."' ");
				}
				if(!empty($bgphoto['pic_id'])) { 
					$green_screen_cart = true;
				  require($setup['path']."/sy-inc/gs-photos.php");
				}

				$q++;
			}


		}

		$opts = whileSQL("ms_product_options", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC ");
		while($opt = mysqli_fetch_array($opts))  {
			if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
				if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")==true) { 
					$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
					$opt_price = $sel['sel_price'];
					if($pack['package_buy_all'] == "1") { 
						if($pack['package_buy_all_price_type'] == "3") { 
							$opt_price = $sel['sel_price'];
						} else  { 
							$opt_price = $sel['sel_price']* $total_images;
						}

					}
					$opt_select_name = $sel['sel_name'];
					if($sel['sel_photos'] > 0) { 
						$pack['package_select_amount'] = $sel['sel_photos'];
					}

				}
				if($opt['opt_type'] == "text") { 
					$opt_price = $opt['opt_price'];
					if($pack['package_buy_all'] == "1") { 
						if($pack['package_buy_all_price_type'] == "3") { 
							$opt_price = $sel['sel_price'];
						} else  { 
							$opt_price = $sel['sel_price']* $total_images;
						}
					}
					$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
				}
				if($opt['opt_type'] == "checkbox") { 
					$opt_price = $opt['opt_price_checked'];
					if($pack['package_buy_all'] == "1") { 
						if($pack['package_buy_all_price_type'] == "3") { 
							$opt_price = $opt['opt_price_checked'];
						} else  { 
							$opt_price = $opt['opt_price_checked'] * $total_images;
						}
					}
					$opt_select_name = _selected_;
				}

				if($opt['opt_type'] == "download") { 
					$opt_price = $opt['opt_price_download'] * $total_images;;
					$opt_select_name = _selected_;
					$co_download = '1';
					if($opt['opt_disable_download'] > 0) { 
						updateSQL("ms_cart", "cart_disable_download='1' WHERE cart_id='".$p_cart_id."' ");
					}
				}

				insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$p_cart_id."' ");

				if($pack['package_buy_all'] == "1") { 
					$bcarts = whileSQL("ms_cart","*", "WHERE cart_package_photo='".$p_cart_id."' ");
					while($bcart = mysqli_fetch_array($bcarts)) { 
						insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_cart_id='".$bcart['cart_id']."', co_download='".$co_download."', co_download_size='".$opt['opt_download_size']."', co_disable_download='".$opt['opt_disable_download']."'  ");

					}
					$co_download = "";
					$opt_price = "";

				}
			}

		}
		if($pack['package_select_only'] == "1") { 
			$p = 1;
			while($p <= $pack['package_select_amount']) { 
				insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='999999', cart_product_name='', cart_sku='',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_disable_download='".$prod['pp_disable_download']."', cart_color_id='".$color['color_id']."', 
				cart_photo_bg='".$bgphoto['pic_id']."',
				cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_allow_notes='".$list['list_allow_notes']."', cart_sub_gal_id='".$_REQUEST['sub_id']."' ");
				$p++;
			}


		} else { 

		if($pack['package_limit'] == "-1") { 
			 $pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_POST['pid']."' ");

			$ext = strtolower(substr($pic['pic_org'], -4));
			if($ext !== ".png") { 
				$bgphoto['pic_id'] = 0;
			}

			if(empty($pic['pic_id'])) {
				die("Unable to find this product in the database");
			}
			$add_pic = $pic['pic_id']; 
			$cart_pic_date_id = $date['date_id'];
		}

			$prods = whileSQL("ms_packages_connect LEFT JOIN ms_photo_products ON ms_packages_connect.con_product=ms_photo_products.pp_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");
			while($prod = mysqli_fetch_array($prods)) { 
				//var_dump($p)
				$cart_download = 0;
				$cart_ship = 0;
				if($pack['package_ship'] =="1") {
					$cart_ship = 1;
				}
				if($prod['pp_type'] =="download") {
					$cart_download = 1;
				}
				if($prod['pp_include_download'] =="1") {
					$cart_download = 1;
				}

				$q = 1;
				while($q <= $prod['con_qty']) { 
					$cart_id = insertSQL("ms_cart", "cart_package_photo='$p_cart_id', cart_qty='1', cart_photo_prod='".$prod['con_product']."', cart_product_name='".addslashes(stripslashes($prod['pp_name']))."', cart_sku='".addslashes(stripslashes($prod['pp_internal_name']))."',cart_price='0', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_pic_date_id='".$cart_pic_date_id."', cart_disable_download='".$prod['pp_disable_download']."', cart_allow_notes='".$list['list_allow_notes']."', cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_ip='".getUserIP()."', cart_cost='".$prod['pp_cost']."', cart_color_id='".$color['color_id']."', 
					cart_photo_bg='".$bgphoto['pic_id']."',
					cart_color_name='".addslashes(stripslashes($color['color_name']))."', cart_sub_gal_id='".$_REQUEST['sub_id']."', cart_pic_id='".$pic['pic_id']."', cart_pic_org='".addslashes(stripslashes($pic['pic_org']))."' ");
					$q++;


					if(!empty($bgphoto['pic_id'])) { 
						$green_screen_cart = true;
					  require($setup['path']."/sy-inc/gs-photos.php");
					}

				}
			}
		}

	}
}


if($_REQUEST['action'] == "addpackagetocart") { 
	if(!is_numeric($_POST['qty'])) { die("an error has occurred [2] - qty"); }
	 // print_r($_POST);
	if((!empty($_POST['list_id']))&&(!is_numeric($_POST['list_id']))==true) { die(); } 
	$_POST['qty'] = sql_safe($_POST['qty']);
	$con = doSQL("ms_photo_products_connect", "*", "WHERE pc_id='".$_POST['prod_id']."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_POST['did']."' ");
	$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_POST['list_id']."' ");

	if($con['pc_package'] > 0) { 
		$pack = doSQL("ms_packages", "*", "WHERE package_id='".$con['pc_package']."' ");
		if($pack['package_select_only'] =="2") { 
			$group = doSQL("ms_photo_products_groups", "*", "WHERE group_id='".$con['pc_group']."' ");
			if(empty($pack['package_id'])) { 
				die("Unable to find product");
			}

			if($pack['package_ship'] =="1") {
				$cart_ship = 1;
			}
			if($con['pc_price'] > 0) { 
				$cart_price = $con['pc_price'];
			} else { 
				$cart_price = $pack['package_price'];
			}

			if(!empty($group['group_name'])) { 
				$product_name = $group['group_name']." > ".$pack['package_name'];
			} else { 
				$product_name = $pack['package_name'];
			}


			if($pack['package_add_ship'] > 0) { 
				$cart_extra_ship = $pack['package_add_ship'];
			}

			$cart_id = insertSQL("ms_cart", "cart_qty='".$_POST['qty']."', cart_package='".$pack['package_id']."', cart_product_name='".addslashes(stripslashes($product_name))."', cart_price='".$cart_price."', cart_ship='$cart_ship', cart_download='$cart_download', cart_service='$cart_service', cart_session='".$_SESSION['ms_session']."' , cart_client='".$_SESSION['pid']."' , cart_date='".date('Y-m-d H:i:s')."', cart_taxable='".$pack['package_taxable']."', cart_ip='".getUserIP()."' , cart_sub_id='".$_REQUEST['spid']."', cart_pic_date_id='".$date['date_id']."', cart_cost='".$pack['package_cost']."' , cart_group_id='".$group['group_id']."', cart_credit='".$pack['package_credit']."', cart_extra_ship='".$cart_extra_ship."', cart_package_buy_all='".$pack['package_buy_all']."', cart_no_discount='".$pack['package_no_discount']."', cart_sub_gal_id='".$_REQUEST['sub_id']."', 
			cart_photo_bg='".$bgphoto['pic_id']."',
			cart_sku='".addslashes(stripslashes($pack['package_internal_name']))."', cart_package_no_select='1'  ");
			$_SESSION['addedtocart'] = $cart_id;
			$prods = whileSQL("ms_packages_connect LEFT JOIN ms_packages ON ms_packages_connect.con_package_include=ms_packages.package_id", "*", "WHERE con_package='".$pack['package_id']."' ORDER BY con_order ASC ");

			$opts = whileSQL("ms_product_options", "*", "WHERE opt_package='".$pack['package_id']."' ORDER BY opt_order ASC ");
			while($opt = mysqli_fetch_array($opts))  {
				if(!empty($_REQUEST['opt-'.$opt['opt_id'].''])) { 
					if(($opt['opt_type'] == "dropdown")||($opt['opt_type'] == "radio")==true) { 
						$sel = doSQL("ms_product_options_sel", "*", "WHERE sel_id='".$_REQUEST['opt-'.$opt['opt_id'].'']."' ");
						$opt_price = $sel['sel_price'];
						if($pack['package_buy_all'] == "1") { 
							if($pack['package_buy_all_price_type'] == "3") { 
								$opt_price = $sel['sel_price'];
							} else  { 
								$opt_price = $sel['sel_price']* $total_images;
							}

						}
						$opt_select_name = $sel['sel_name'];
						if($sel['sel_photos'] > 0) { 
							$pack['package_select_amount'] = $sel['sel_photos'];
						}

					}
					if($opt['opt_type'] == "text") { 
						$opt_price = $opt['opt_price'];
						if($pack['package_buy_all'] == "1") { 
							if($pack['package_buy_all_price_type'] == "3") { 
								$opt_price = $sel['sel_price'];
							} else  { 
								$opt_price = $sel['sel_price']* $total_images;
							}
						}
						$opt_select_name = $_REQUEST['opt-'.$opt['opt_id'].''];
					}
					if($opt['opt_type'] == "checkbox") { 
						$opt_price = $opt['opt_price_checked'];
						if($pack['package_buy_all'] == "1") { 
							if($pack['package_buy_all_price_type'] == "3") { 
								$opt_price = $sel['sel_price'];
							} else  { 
								$opt_price = $sel['sel_price']* $total_images;
							}
						}
						$opt_select_name = _selected_;
					}

					if($opt['opt_type'] == "download") { 
						$opt_price = $opt['opt_price_download'] * $total_images;;
						$opt_select_name = _selected_;
						$co_download = '1';
						if($opt['opt_disable_download'] > 0) { 
							updateSQL("ms_cart", "cart_disable_download='1' WHERE cart_id='".$p_cart_id."' ");
						}
					}

					insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_price='".$opt_price."', co_cart_id='".$cart_id."' ");

					if($pack['package_buy_all'] == "1") { 
						$bcarts = whileSQL("ms_cart","*", "WHERE cart_package_photo='".$p_cart_id."' ");
						while($bcart = mysqli_fetch_array($bcarts)) { 
							insertSQL("ms_cart_options", "co_opt_id='".$opt['opt_id']."', co_opt_name='".addslashes(stripslashes($opt['opt_name']))."', co_select_id='".$sel['sel_id']."', co_select_name='".addslashes(stripslashes($opt_select_name))."', co_cart_id='".$bcart['cart_id']."', co_download='".$co_download."', co_download_size='".$opt['opt_download_size']."', co_disable_download='".$opt['opt_disable_download']."'  ");

						}
						$co_download = "";
						$opt_price = "";

					}
				}

			}
			while($prod = mysqli_fetch_array($prods)) { 
				addpackagetocart($prod,true,$cart_id);
			}
		} else { 	
			addpackagetocart($pack,false,'');
		}
	}
	print "HEY";
	//header("location: index.php");
	mysqli_close($dbcon); exit();
}

mysqli_close($dbcon);
?>
