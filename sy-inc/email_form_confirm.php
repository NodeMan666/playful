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
	updateSQL("ms_email_list", "em_status='0' WHERE em_id='".$em['em_id']."' ");
	sendmailinglistemail($em['em_email'],$em['name'],$em['em_last_name'],$em['em_key'],'maillistwelcome');
}
?>
<div>&nbsp;</div><div>&nbsp;</div><div class="pc center">
	<h1><?php print $em_settings['email_confirmed_title'];?></h1>
	<?php print $em_settings['email_confirmed_text'];?>
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
