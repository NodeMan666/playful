<?php
if($_REQUEST['action'] == "deleteForm") {
	deleteForm();
} elseif($_REQUEST['action'] == "viewForm") {
	include "forms.view.php";
} elseif($_REQUEST['action'] == "captcha") {
	include "forms.captcha.php";
} else {
	include "forms.list.php";
}
?>

<?php 
if($_REQUEST['subdo'] == "orderLinks") {	
	foreach($_REQUEST['link_order'] AS $id => $val) {
		updateSQL("ms_menu_links", "link_order='$val' WHERE link_id='$id'");
//		print "<li>$id = $val";
	}
	$_SESSION['sm'] = "Link display order updated";
	session_write_close();
	header("location: index.php?do=look&view=links");
	exit();

}
function deleteForm() {	
	$form = doSQL("ms_forms", "*", "WHERE form_id='".$_REQUEST['form_id']."' ");
	deleteSQL2("ms_form_fields", "WHERE ff_form='".$form['form_id']."' ");
	deleteSQL("ms_forms", "WHERE form_id='".$form['form_id']."' ", "1");
	$_SESSION['sm'] = "Form ".$form['form_name']." has been deleted";
	session_write_close();
	header("location: index.php?do=forms");
	exit();
}
if($_REQUEST['subdo'] == "editLink") {
	$formDisplay = "block";
	$formLinkDisplay = "none";
		$link = doSQL("ms_menu_links", "*", "WHERE link_id='".$_REQUEST['link_id']."' ");
	if(empty($_REQUEST['savelink'])) {
		$_REQUEST['link_text'] = $link['link_text'];
		$_REQUEST['link_url'] = $link['link_url'];
		$_REQUEST['link_order'] = $link['link_order'];
		$_REQUEST['link_open'] = $link['link_open'];
		$_REQUEST['link_status'] = $link['link_status'];
		$_REQUEST['link_location'] = $link['link_location'];
	}
	print "<div class=\"pageTitle\">Editing link: ".$_REQUEST['link_text']."</div>";
} else {
	$formDisplay = "none";
	$formLinkDisplay = "block";
}
?>

<?php 
if(($_REQUEST['subdo'] == "addNewLink")OR(($_REQUEST['subdo'] == "editLink")AND($_REQUEST['savelink'] == "yes"))==true) { 
	if((empty($_REQUEST['link_url']))AND(empty($link['link_main']))==true) {
		$error .= "<div>You did not enter a URL</div>";
	}
	if(empty($_REQUEST['link_text'])) {
		$error .= "<div>You did not enter text for your link</div>";
	}
	if(!empty($error)) {
		print "<div class=error>$error</div>";
		$formDisplay = "block";
		$formLinkDisplay = "none";
	} else {
		if($_REQUEST['subdo'] == "editLink") {
			updateSQL("ms_menu_links", "link_status='".addslashes(stripslashes($_REQUEST['link_status']))."' ,link_order='".addslashes(stripslashes($_REQUEST['link_order']))."' ,link_url='".addslashes(stripslashes($_REQUEST['link_url']))."', link_text='".addslashes(stripslashes($_REQUEST['link_text']))."', link_open='".addslashes(stripslashes($_REQUEST['link_open']))."' , link_location='".addslashes(stripslashes($_REQUEST['link_location']))."' WHERE link_id='".$_REQUEST['link_id']."' ");
			$_SESSION['sm'] = "Link ".$_REQUEST['link_text']." Saved";
			session_write_close();
			header("location: index.php?do=look&view=links");
			exit();
		} else {
			insertSQL("ms_menu_links", "link_status='".addslashes(stripslashes($_REQUEST['link_status']))."' ,link_order='".addslashes(stripslashes($_REQUEST['link_order']))."' ,link_url='".addslashes(stripslashes($_REQUEST['link_url']))."', link_text='".addslashes(stripslashes($_REQUEST['link_text']))."', link_open='".addslashes(stripslashes($_REQUEST['link_open']))."' , link_location='".addslashes(stripslashes($_REQUEST['link_location']))."'  ");
			$_SESSION['sm'] = "New Link ".$_REQUEST['link_text']." Added";
			session_write_close();
			header("location: index.php?do=look&view=links");
			exit();
		}
	}
}
?>