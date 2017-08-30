<?php
session_start();
header("Cache-control: private"); 
header('Content-Type: text/html; charset=utf-8');
if($setup['ob_start_only'] == true) { 
	ob_start();  
} else { 
	if ( substr_count( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) ) {  
		ob_start( "ob_gzhandler" );  
	}  
	else {  
		ob_start();  
	}  
}

unset($_SESSION['query']);

require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$em_settings = doSQL("ms_email_list_settings","*", ""); 
$photo_setup = doSQL("ms_photo_setup","gallery_favicon","");
date_default_timezone_set(''.$site_setup['time_zone'].'');

if(!isset($_COOKIE['ms_session'])) {
	$time=time()+3600*24*365*2;
	$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
	$cookie_url = ".$domain";
	$ip = str_replace(".", "", getUserIP());
	$cvar = $ip.date('Ymdhis');
	SetCookie("ms_session",$cvar,$time,"/",null);
	$_SESSION['ms_session'] = $cvar;
} else {
	$_SESSION['ms_session'] = $_COOKIE['ms_session'];
}
if((!isset($_SESSION['pid'])) AND(isset($_COOKIE['persid']))==true) {
	$_SESSION['loggedin'] = true;
	$_SESSION['pid'] =$_COOKIE['persid'];
}

if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = stripslashes(stripslashes("".$_REQUEST[$id].""));
		}
	}
}


include $setup['path']."/sy-inc/mobile.detect.php";
$detect = new Mobile_Detect;

if($detect->isTablet()){
	$ipad = true;
	$isipad = true;
}

if ($detect->isMobile() && !$detect->isTablet()) {
	$mobile = true;
	$site_type = "mobile";
}

if($_SESSION['previewMobile'] == 1) { 
	$mobile = true;
	$site_type = "mobile";
}
if($_SESSION['previewIpad'] == 1) { 
	$ipad = true;
}

securityCheck();



if(!ctype_alnum($_REQUEST['contract'])) {  die("Sorry, that is not a valid link"); } 

$contract = doSQL("ms_contracts", "*,date_format(signed_date, '".$site_setup['date_format']." ')  AS signed_date,date_format(my_signed_date, '".$site_setup['date_format']." ')  AS my_signed_date,date_format(signed_date2, '".$site_setup['date_format']." ')  AS signed_date2", "WHERE link='".$_REQUEST['contract']."' ");
if($contract['invoice'] > 0) { 
	$order = doSQL("ms_orders", "*", "WHERE order_id='".$contract['invoice']."' ");
}
if(empty($contract['contract_id'])) { die("Sorry, unable to find contract"); } 
?>
<?php 
$clang = doSQL("ms_contracts_language", "*", " ");
foreach($clang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML  xmlns="http://www.w3.org/1999/xhtml" <?php if($fb['disable_facebook'] !== "1") { ?>xmlns:fb="http://www.facebook.com/2008/fbml"<?php } ?> xml:lang="en" lang="en">
<TITLE><?php print _contract_header_;?></TITLE>
<meta http-equiv="Page-Enter" content="blendTrans(duration=0.0)" />
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php 
$css_id = $site_setup['css'];
$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");
if(($css['site_type'] == "1")&&($mobile!==true)&&($ipad!==true)==true) {  
	$and_css .= "&wbg=1"; 
	$site_type = "fullscreen";
}
if(($css['css_id'] <= 0) && ($setup['sytist_hosted'] == true) == true) { 
	$css_id = 1;
	$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='1'");
}

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="True" name="HandheldFriendly">
<meta name="viewport" content="width=device-width">
<link rel="apple-touch-icon" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-60.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-76.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-120.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-180.png"> 
<link rel="icon"  type="image/png"  href="<?php print $setup['temp_url_folder'];?>/<?php print $setup['misc_folder'];?>/favicons/icon-16.png">
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/sytist.css?<?php print MD5($site_setup['sytist_version']); ?>">
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/animation.css?<?php print MD5($site_setup['sytist_version']); ?>"><!--[if IE 7]>
<link rel="stylesheet" href="<?php tempFolder();?>/sy-inc/icons/svg/css/fontello-ie7.css"><![endif]-->
<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='$css_id' ORDER BY font ASC ");
if(mysqli_num_rows($fonts) > 0) { 
	while($font = mysqli_fetch_array($fonts)) { 
		if($f > 0) { 
			$add_fonts .= "|";
		}
		$add_fonts .= str_replace(" ","+",$font['font']);
		$f++;
	}
	?>
<link href='//fonts.googleapis.com/css?family=<?php print $add_fonts;?>&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<?php } ?>
<link href='//fonts.googleapis.com/css?family=Satisfy' rel='stylesheet' type='text/css'>

<!-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/south-street/jquery-ui.css" rel="stylesheet"> -->

<script src="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/jquery-1.11.0.js"></script>
<script src="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/jquery-ui.min-1.10.3.js"></script>
<script src="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/jquery.signature.js"></script>
<script src="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/touch.js"></script>


<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/sytist.js?".MD5($site_setup['sytist_version'])."" ?>d"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/slideshow.js?".MD5($site_setup['sytist_version'])."" ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/gal.js?".MD5($site_setup['sytist_version'])."" ?>a"></script>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?><?php print "/".$setup['inc_folder']."/js/store.js?".MD5($site_setup['sytist_version'])."" ?>b"></script>
<script src="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/contract.js?<?php print MD5($site_setup['sytist_version']);?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>"></script>
<script>
tempfolder = '<?php print $setup['temp_url_folder'];?>';
</script>
<link href="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/jquery.signature.css" rel="stylesheet">
<link href="<?php tempFolder();?><?php print "/".$setup['inc_folder'];?>/contracts/contract.css?<?php print MD5($site_setup['sytist_version']);?><?php if($setup['dev'] == true) { print "dev".rand(); } ?>" rel="stylesheet">



</head>

<body bgcolor="#FFFFFF" >
<div id="loadingbg"><div class="loadingspinner"></div></div>


<?php 
if(!isset($_SESSION['office_admin_login'])) { 
	if((!isset($_SESSION['contractpin'])) || ($_SESSION['contractpin'] !== $contract['pin'])== true) { 

?>

	<div id="enterpin">
		<div class="inner">
		<?php 
		if($_REQUEST['action'] == "checkpin") { 
			$pin = trim($_REQUEST['pin']);
			if($pin !== $contract['pin']) { 
				?><div class="error"><?php print _incorrect_pin_;?></div>
		<?php } else { 
				$_SESSION['contractpin'] = $pin;
				header("location: index.php?contract=".$contract['link']."");
				session_write_close();
				exit();
				}
			}
		?>
<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#pin").focus();
 }
</script>
		<p><?php print _enter_pin_message_;?></p>
		<form method="post" name="pin" action="index.php">
		<p class="center"><input type="text" name="pin" id="pin" size="6" class="center pinfield" <?php if($setup['demo_mode'] == true) { ?>value="8888"<?php } ?>> <input type="submit" name="submit" value="<?php print _submit_pin_;?>" class="submit" >
		<input type="hidden" name="contract" id="contract" value="<?php print $contract['link'];?>">
		<input type="hidden" name="action" id="action" value="checkpin">
		</form>
		</div>
	</div>

	<?php
	die();
	}
} 

if(!isset($_SESSION['office_admin_login'])) { 
	if(customerLoggedIn()) { 
		$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		$table = "ms_people";
		$table_id = $person['p_id'];
		$message = "Contract ".$contract['title']." was viewed by ".$person['p_name']." ".$person['p_last_name']." ";
		addNote($table,$table_id,$message,0);
	} else { 
		$table = "ms_people";
		$table_id = $contract['person_id'];
		$message = "Contract ".$contract['title']." was viewed by someone  ";
		addNote($table,$table_id,$message,0);
	}
}
?>



<div id="fadebg"></div>

<div id="popupdone" class="popup">
	<div class="inner">
		<div class="close"><a href=""  onclick="closepopup(); return false;" class="the-icons icon-cancel"></a></div>
	<p><h2><?php print _success_title_;?></h2></p>
	<p><?php print _success_message_;?></p>
	<p><span onclick="printit(); return false;" class="submit"><?php print _print_contract_;?></span></p>
	</div>
</div>

<div id="popupadditionalsig" class="popup">
	<div class="inner">
		<div class="close"><a href=""  onclick="closepopup(); return false;" class="the-icons icon-cancel"></a></div>
	<p><h2><?php print _success_title_;?></h2></p>
	<p><?php print _success_message_additional_signature_;?></p>
	<p><span onclick="printit(); return false;" class="submit"><?php print _print_contract_;?></span></p>
	</div>
</div>

<div id="popupinvoice" class="popup">
	<div class="inner">
		<div class="close"><a href=""  onclick="closepopup(); return false;" class="the-icons icon-cancel"></a></div>
	<p><h2><?php print _success_title_;?></h2></p>
	<p><?php print _success_message_with_invoice_;?></p>
	<p><span onclick="printit(); return false;" class="submit"><?php print _print_contract_;?></span>  <a  href="../index.php?view=order&action=orderk&oe=<?php print MD5($order['order_email']);?>&on=<?php print MD5($order['order_id']);?>" class="submit"><?php print _pay_invoice_link_;?></a></p>
	</div>
</div>

<?php 

$content = $contract['content'];
$content = str_replace("[NAME]",$contract['signature_name'],$content);
$content = str_replace("[NAME2]",$contract['signature_name2'],$content);
$content = str_replace("[MY_NAME]",$contract['my_name'],$content);
$content = preg_replace_callback('~\[TEXT_INPUT_OPTIONAL\]~', "replacetextinputoption", $content);
$content = preg_replace_callback('~\[TEXT_INPUT_REQUIRED\]~', "replacetextinputrequired", $content);
$content = preg_replace_callback('~\[TEXT_INPUT_SHORT_OPTIONAL\]~', "replacetextinputshortoption", $content);
$content = preg_replace_callback('~\[TEXT_INPUT_SHORT_REQUIRED\]~', "replacetextinputshortrequired", $content);

$content = preg_replace_callback('~\[CHECKBOX_OPTIONAL\]~', "replacecheckboxoption", $content);
$content = preg_replace_callback('~\[CHECKBOX_REQUIRED\]~', "replacecheckboxoptionrequired", $content);

if(!empty($contract['content_signed'])) { 
	$content = $contract['content_signed'];
}

$sign_form = "write"; // write or type 
$total_signed = 0;
$total_sign = 1;
if(!empty($contract['signature_name2'])) { $total_sign = 2; } 
if((!empty($contract['signature'])) || (!empty($contract['signature_svg'])) == true) {
 $total_signed++;
}
if((!empty($contract['signature2'])) || (!empty($contract['signature2_svg'])) == true) {
 $total_signed++;
}

?>
<div class="contractheader">
	<div class="title"><?php print _contract_header_;?></div>
	<div class="print"><span onclick="printit(); return false;" class="submit"><?php print _print_;?></span>
	<?php
	if($order['order_id'] > 0) { ?>
	 <a id="invoicelink" href="../index.php?view=order&action=orderk&oe=<?php print MD5($order['order_email']);?>&on=<?php print MD5($order['order_id']);?>" class="submit"><?php print _pay_invoice_link_;?></a>
	<?php } ?>
	</div>
	<div class="clear"></div>
</div>
<div class="contractinfo"><?php print _contract_instructions_;?></div>
<div>&nbsp;</div>


<div id="contract" data-total-sign="<?php print $total_sign;?>" data-total-signed="<?php print $total_signed;?>" data-invoice="<?php print $order['order_id'];?>">
	<div class="inner">
	<div class="pc">
	<div id="contractcontent"><?php print $content;?></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	</div>
<div id="emptyfieldsmessage" class="hide error"><?php print _required_fields_empty_;?></div>


	<?php if(!empty($contract['signature_name'])) { ?>
	<div class="">
		<div id="signature-signed" class="<?php if((empty($contract['signature'])) && (empty($contract['signature_svg'])) == true) { ?>hide<?php } ?>">
			<div class="signaturecontainer">
			<div id="signature-sig" class=" signature">
			<?php if(!empty($contract['signature'])) { ?>
			<?php print $contract['signature'];?>
			<?php } ?>
			<?php if(!empty($contract['signature_svg'])) { ?>
				<div class="svgsign"><?php print $contract['signature_svg'];?></div>
			<?php } ?>
			</div>
			</div>
			<div class="clear"></div>
		</div>


		<div id="signature-form">
		<?php if((empty($contract['signature'])) && (empty($contract['signature_svg'])) == true) { ?>
			<form name="signing1" id="signingcontract1" action="index.php" method="POST"  onSubmit="return signcontract('<?php print $contract['contract_id'];?>','signature');">
			<div id="signature-error-empty" class="error hide"><?php print _name_is_blank_; ?></div>
			<div id="signature-error-match" class="error hide"><?php print _name_doesnt_match_; ?></div>
			
			
			<div id="write-form-1" class="<?php if($sign_form !== "write") { ?>hide<?php } ?>">
				<div class="noprint writename"><?php print _write_your_name_;?></div>
				<div id="sig-error" class="error hide"><?php print _did_not_sign_name_error_; ?></div>
				<div class="sigcontainer" id="signature-writeform">
					<div id="sig"></div>
					<div style="float: right;" class=" noprint">
						<a href="" onclick="changeform('1'); return false;"><?php print _type_;?></a> &nbsp;
						<a href="" onclick="return false;" id="clear"><?php print _clear_;?></a>  &nbsp;
						<input type="submit" onclick="savesigsvg('<?php print $contract['contract_id'];?>','signature','sig'); return false;" class="submit noprint" value="<?php print _done_;?>">
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<div id="type-form-1" class="<?php if($sign_form !== "type") { ?>hide<?php } ?>">
				 <nobr><input type="text" name="signature" id="signature" data-name="<?php print $contract['signature_name'];?>" class="defaultfield sign" size="40" value="<?php print _type_your_name_;?>"  title="<?php print _type_your_name_;?>"> <input type="submit" name="submit" class="submit  noprint" value="<?php print _sign_contract_; ?>"></nobr>
			</div>

			<input type="hidden" name="signature_date" id="signature_date" value="<?php print currentdatetime();?>">
			<input type="hidden" name="signature_ip" id="signature_ip" value="<?php print getUserIP();?>">
			<input type="hidden" name="signature_browser" id="signature_browser" value="<?php print $_SERVER['HTTP_USER_AGENT'];?>">
			</form>

			 <?php } ?>

	</div>

		<div class="namelabel"><?php print $contract['signature_name'];?></div>
		<div id="signature-date-container" class="signeddatecontainer <?php if((empty($contract['signature'])) && (empty($contract['signature_svg'])) == true) { ?>hide<?php } ?>">
			<div id="signature-date" class="signeddate"><?php print _signed_on_;?> <?php print $contract['signed_date'];?></div>
		</div>
	<div>&nbsp;</div>



	<?php } ?>


	<?php if(!empty($contract['signature_name2'])) { ?>
	<div class="">
		<div id="signature2-signed" class="<?php if((empty($contract['signature2'])) && (empty($contract['signature2_svg'])) == true) { ?>hide<?php } ?>">
			<div class="signaturecontainer">
				<div id="signature2-sig" class=" signature">
				<?php if(!empty($contract['signature2'])) { ?>
				<?php print $contract['signature2'];?>
				<?php } ?>
				<?php if(!empty($contract['signature2_svg'])) { ?>
					<div class="svgsign"><?php print $contract['signature2_svg'];?></div>
				<?php } ?>

				</div>
			</div>
			<div class="clear"></div>
		</div>


		<div id="signature2-form">

		<?php if((empty($contract['signature2'])) && (empty($contract['signature2_svg'])) == true) { ?>
			<form name="signing2" id="signingcontract2" action="index.php" method="POST"  onSubmit="return signcontract('<?php print $contract['contract_id'];?>','signature2');">
			<div id="signature2-error-empty" class="error hide"><?php print _name_is_blank_; ?></div>
			<div id="signature2-error-match" class="error hide"><?php print _name_doesnt_match_; ?></div>


			<div id="write-form-2" class="<?php if($sign_form !== "write") { ?>hide<?php } ?>">
			<div class="noprint writename"><?php print _write_your_name_;?></div>
			<div id="sig2-error" class="error hide"><?php print _did_not_sign_name_error_; ?></div>
			<div class="sigcontainer" id="signature2-writeform">
				<div id="sig2"></div>
				<div style="float: right;" class="noprint">
					<a href="" onclick="changeform('2'); return false;"><?php print _type_;?></a> &nbsp;
					<a href="" onclick="return false;" id="clear2"><?php print _clear_;?></a>  &nbsp;
					<input type="submit" onclick="savesigsvg('<?php print $contract['contract_id'];?>','signature2','sig2'); return false;" class="submit" value="<?php print _done_;?>">
					
					</div>
					<div class="clear"></div>
			</div>

			</div>


			<div id="type-form-2" class="<?php if($sign_form !== "type") { ?>hide<?php } ?>">
			<nobr><input type="text" name="signature2" id="signature2" data-name="<?php print $contract['signature_name2'];?>" class="defaultfield sign" size="40" value="<?php print _type_your_name_;?>"  title="<?php print _type_your_name_;?>"> <input type="submit" name="submit" class="submit  noprint" value="<?php print _sign_contract_;?>"></nobr>
			</div>


			<input type="hidden" name="signature2_date" id="signature2_date" value="<?php print currentdatetime();?>">
			<input type="hidden" name="signature2_ip" id="signature2_ip" value="<?php print getUserIP();?>">
			<input type="hidden" name="signature2_browser" id="signature2_browser" value="<?php print $_SERVER['HTTP_USER_AGENT'];?>">
			</form>
			<?php } ?>
		</div>
		</div>
			<div class="namelabel"><?php print $contract['signature_name2'];?></div>
			<div id="signature2-date-container" class="signeddatecontainer <?php if((empty($contract['signature2'])) && (empty($contract['signature2_svg'])) == true) { ?>hide<?php } ?>">
				<div id="signature2-date" class="signeddate"><?php print _signed_on_." ".$contract['signed_date2'];?></div>
			</div>

	<div>&nbsp;</div>
	<?php } ?>


















	<?php if(!empty($contract['my_name'])) { ?>
	<div class="">
		<div id="my_signature-signed" class="<?php if((empty($contract['my_signature'])) && (empty($contract['my_signature_svg'])) == true) { ?>hide<?php } ?>">
			<div class="signaturecontainer">
				<div id="my_signature-sig" class=" signature">
				<?php if(!empty($contract['my_signature'])) { ?>
				<?php print $contract['my_signature'];?>
				<?php } ?>
				<?php if(!empty($contract['my_signature_svg'])) { ?>
					<div class="svgsign"><?php print $contract['my_signature_svg'];?></div>
				<?php } ?>

				</div>
			</div>
			<div class="clear"></div>
		</div>


		<div id="my_signature-form">


		<?php if((empty($contract['my_signature'])) && (empty($contract['my_signature_svg'])) == true) { ?>
		
		
		<?php if(isset($_SESSION['office_admin_login'])) { ?>

			<form name="signing2" id="signingcontract2" action="index.php" method="POST"  onSubmit="return signcontract('<?php print $contract['contract_id'];?>','my_signature');">
			<div id="my_signature-error-empty" class="error hide"><?php print _name_is_blank_; ?></div>
			<div id="my_signature-error-match" class="error hide"><?php print _name_doesnt_match_; ?></div>


			<div id="write-form-my" class="<?php if($sign_form !== "write") { ?>hide<?php } ?>">
			<div class="noprint writename"><?php print _write_your_name_;?></div>
			<div id="sigmy-error" class="error hide"><?php print _did_not_sign_name_error_; ?></div>
			<div class="sigcontainer" id="my_signature-writeform">
				<div id="sigmy"></div>
				<div style="float: right;" class="noprint">
					<a href="" onclick="changeform('my'); return false;"><?php print _type_;?></a> &nbsp;
					<a href="" onclick="return false;" id="clearmy"><?php print _clear_;?></a>  &nbsp;
					<input type="submit" onclick="savesigsvg('<?php print $contract['contract_id'];?>','my_signature','sigmy'); return false;" class="submit" value="<?php print _done_;?>">
					
					</div>
					<div class="clear"></div>
			</div>

			</div>


			<div id="type-form-my" class="<?php if($sign_form !== "type") { ?>hide<?php } ?>">
			<nobr><input type="text" name="my_signature" id="my_signature" data-name="<?php print $contract['my_name'];?>" class="defaultfield sign" size="40" value="<?php print _type_your_name_;?>"  title="<?php print _type_your_name_;?>"> <input type="submit" name="submit" class="submit  noprint" value="Sign Contract"></nobr>
			</div>


			<input type="hidden" name="my_signature_date" id="my_signature_date" value="<?php print currentdatetime();?>">
			<input type="hidden" name="my_signature_ip" id="my_signature_ip" value="<?php print getUserIP();?>">
			<input type="hidden" name="my_signature_browser" id="my_signature_browser" value="<?php print $_SERVER['HTTP_USER_AGENT'];?>">
			</form>
			<?php if(isset($_SESSION['office_admin_login'])) { ?>
			<div style="margin: 8px 0px;"><input type="checkbox" name="make_default" id="make_default" value="1"> <label for="make_default">Make this my default signature</label></div>
			<?php } ?>
			<?php } else { ?>
			<nobr><input type="text" name="my_signature" id="my_signature" data-name="<?php print $contract['my_name'];?>" class=" sign" size="40" value="" disabled></nobr>

			<?php } ?>

			<?php } ?>
		</div>
		</div>
			<div class="namelabel"><?php print $contract['my_name'];?></div>
			<div id="my_signature-date-container" class="signeddatecontainer <?php if((empty($contract['my_signature'])) && (empty($contract['my_signature_svg'])) == true) { ?>hide<?php } ?>">
				<div id="my_signature-date" class="signeddate"><?php print _signed_on_." ".$contract['my_signed_date'];?></div>
			</div>

	<div>&nbsp;</div>
	<?php } ?>




	</div>
</div>
</div>
<div class="noprint">
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	<div class="center noprint"><a href="<?php print $setup['url'];?>"><?php print $site_setup['website_title'];?></a></div>
	<div>&nbsp;</div>
	<div>&nbsp;</div>
</div>
</body>
</html>