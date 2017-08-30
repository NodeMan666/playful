<?php 
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags(trim($value)))));
		}
	}
}


$em = doSQL("ms_email_list", "*", "WHERE em_key='".$_REQUEST['eid']."' ");
if(!empty($em['em_id'])) { 
	updateSQL("ms_email_list", "em_status='2' WHERE em_id='".$em['em_id']."' ");
	if($em['em_sent_to_mailchimp'] == "1") {
		include $setup['path']."/sy-inc/mail.chimp.functions.php";
		mailchimpunsubscribe($em['em_email'],'','');
	}
}
?>
<div>&nbsp;</div><div>&nbsp;</div><div class="pc center">
	<h1><?php print $em_settings['email_removed_title'];?></h1>
	<?php print $em_settings['email_removed_text'];?>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div><div>&nbsp;</div>
