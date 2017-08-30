<?php 
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
require $setup['path']."/".$setup['inc_folder']."/form/form.functions.php";
require "admin.functions.php"; 
require("admin.icons.php");

$dbcon = dbConnect($setup);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php		$style_sheet = "white.css"; ?>
<link rel="stylesheet" href="css/<?php print $style_sheet;?>" type="text/css">
<script language="javascript" src="js/admin.js" type="text/javascript"></script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<?php

/*
print "<pre>";
print_r($_POST);
print "<li>".$_POST['entry_cat'];
print "</pre>";
*/
foreach($_POST AS $v =>$f) {
	if($f == "!form_id!") {
		$this_pre = $v;
	}
}


if($_REQUEST['eAction'] == "checkbox") {
//	print "<li>".$_REQUEST['table_select_from_show'];
	if(!empty($_REQUEST['add'])) {
		if(countIt("".$_REQUEST['table_entry']."",  "WHERE ".$_REQUEST['table_entry_select']."='".$_REQUEST['add']."' AND ".$_REQUEST['table_entry_this']."='".$_REQUEST['table_entry_this_id']."' ")<=0) {
			insertSQL("".$_REQUEST['table_entry']."", "".$_REQUEST['table_entry_select']."='".$_REQUEST['add']."', ".$_REQUEST['table_entry_this']."='".$_REQUEST['table_entry_this_id']."'  ");
		}
		print  "<a href=\"javascript:ajaxpage('field.edit.php?eAction=checkbox&remove=".$_REQUEST['add']."&table_select_from=".$_REQUEST['table_select_from']."&table_select_from_id=".$_REQUEST['table_select_from_id']."&table_select_from_show=".cleanJsQuotes($_REQUEST['table_select_from_show'])."&table_entry=".$_REQUEST['table_entry']."&table_entry_select=".$_REQUEST['table_entry_select']."&table_entry_this=".$_REQUEST['table_entry_this']."&table_entry_this_id=".$_REQUEST['table_entry_this_id']."&".$_REQUEST['table_entry_this']."=".$_REQUEST['table_entry_this_id']."&form_id=".$_REQUEST['form_id']."&table_select_from_id=".$_REQUEST['add']."', '".$_REQUEST['form_id']."-".$_REQUEST['add']."');\" class=ahover>".ai_checkbox_on."</a> ".stripreplaceand($_REQUEST['table_select_from_show'])."";
	}
	if(!empty($_REQUEST['remove'])) {
		if(countIt("".$_REQUEST['table_entry']."",  "WHERE ".$_REQUEST['table_entry_select']."='".$_REQUEST['remove']."' AND ".$_REQUEST['table_entry_this']."='".$_REQUEST['table_entry_this_id']."' ") > 0) {
			deleteSQL("".$_REQUEST['table_entry']."", " WHERE ".$_REQUEST['table_entry_select']."='".$_REQUEST['remove']."' AND ".$_REQUEST['table_entry_this']."='".$_REQUEST['table_entry_this_id']."' ","1");
		}
		print  "<a href=\"javascript:ajaxpage('field.edit.php?eAction=checkbox&add=".$_REQUEST['remove']."&table_select_from=".$_REQUEST['table_select_from']."&table_select_from_id=".$_REQUEST['table_select_from_id']."&table_select_from_show=".cleanJsQuotes($_REQUEST['table_select_from_show'])."&table_entry=".$_REQUEST['table_entry']."&table_entry_select=".$_REQUEST['table_entry_select']."&table_entry_this=".$_REQUEST['table_entry_this']."&table_entry_this_id=".$_REQUEST['table_entry_this_id']."&".$_REQUEST['table_entry_this']."=".$_REQUEST['table_entry_this_id']."&form_id=".$_REQUEST['form_id']."&table_select_from_id=".$_REQUEST['remove']."', '".$_REQUEST['form_id']."-".$_REQUEST['remove']."');\" class=ahover>".ai_checkbox_off."</a> ".stripreplaceand($_REQUEST['table_select_from_show'])."";
	}
	exit();
}

	if($_REQUEST['eAction'] == "donecheckbox") {
		$d['field_type'] = "checkboxes";
		$d['table_select_from'] = $_REQUEST['table_select_from'];
		$d['table_select_from_id'] = $_REQUEST['table_select_from_id'];
		$d['table_select_from_show'] = $_REQUEST['table_select_from_show'];

		$d['table_entry'] = $_REQUEST['table_entry'];
		$d['table_entry_select'] =$_REQUEST['table_entry_select'];
		$d['table_entry_this'] = $_REQUEST['table_entry_this'];
		$d['table_entry_this_id'] = $_REQUEST['table_entry_this_id'];

		$d['allow_other'] = $_REQUEST['allow_other'];
		$d['allow_other_name'] =$_REQUEST['allow_other_name'];


		$d['table_field'] = $d['table_entry_select'];
		$d['field_size'] = $_REQUEST['field_size'];
		print "<script>selectFieldEdit('efd_".$d['table']."_".$d['table_field']."','efe_".$d['table']."_".$d['table_field']."',''); return false;</script>";

		$opts = whileSQL("".$d['table_select_from']." ","*", "ORDER BY ".$d['table_select_from_show']." ASC");
		while($opt = mysqli_fetch_array($opts)) {
			if(countIt("".$d['table_entry']."", "WHERE ".$d['table_entry_this']."='".$d['table_entry_this_id']."' AND ".$d['table_entry_select']."='".$opt[$d['table_select_from_id']]."'")>0) { 
				$commacheck ++;
				if($commacheck > 1) {
					$cd .=", ";
				}
				$cd .= "".$opt[$d['table_select_from_show']]."";
			}
		}

		$d['current_data'] = $cd;
		print editFieldCheckboxes($d);
		exit();
}

	if($_REQUEST['eAction'] == "newcheckbox") {
	//	print "<pre>"; print_r($_REQUEST);
//		print "<li>".$_REQUEST['table_entry']." , ".$_REQUEST['table_entry_select']."='".stripreplaceand($_REQUEST['new_field'])."', ".$_REQUEST['table_entry_this']."='".$cat_id."'  ";
// $new_field = $_REQUEST['new_field'];
// print "<li>".$_REQUEST['table_select_from_id']."='".addslashes(stripslashes($_REQUEST['new_name']))."' ";
//	 	exit();

			$cat_id = insertSQL("".$_REQUEST['table_select_from']."", "".$_REQUEST['table_select_from_show']."='".addslashes(stripslashes($_REQUEST['new_name']))."'  ");

			insertSQL("".$_REQUEST['table_entry']."", "".$_REQUEST['table_entry_select']."='$cat_id', ".$_REQUEST['table_entry_this']."='".$_REQUEST['table_entry_this_id']."'  ");


		$d['field_type'] = "checkboxes";
		$d['table_select_from'] = $_REQUEST['table_select_from'];
		$d['table_select_from_id'] = $_REQUEST['table_select_from_id'];
		$d['table_select_from_show'] = $_REQUEST['table_select_from_show'];

		$d['table_entry'] = $_REQUEST['table_entry'];
		$d['table_entry_select'] =$_REQUEST['table_entry_select'];
		$d['table_entry_this'] = $_REQUEST['table_entry_this'];
		$d['table_entry_this_id'] = $_REQUEST['table_entry_this_id'];

		$d['allow_other'] = $_REQUEST['allow_other'];
		$d['allow_other_name'] =$_REQUEST['allow_other_name'];


		$d['table_field'] = $d['table_entry_select'];
		$d['field_size'] = $_REQUEST['field_size'];
		print "<script>selectFieldEdit('efd_".$d['table']."_".$d['table_field']."','efe_".$d['table']."_".$d['table_field']."',''); return false;</script>";

		$opts = whileSQL("".$d['table_select_from']." ","*", "ORDER BY ".$d['table_select_from_show']." ASC");
		while($opt = mysqli_fetch_array($opts)) {
			if(countIt("".$d['table_entry']."", "WHERE ".$d['table_entry_this']."='".$d['table_entry_this_id']."' AND ".$d['table_entry_select']."='".$opt[$d['table_select_from_id']]."'")>0) {
				$commacheck ++;
				if($commacheck > 1) {
					$cd .=", ";
				}
			$cd .= "".$opt[$d['table_select_from_show']]."  ";
			}
		}

		$d['current_data'] = $cd;
		print editFieldCheckboxes($d);
		exit();
}

$this_pre = str_replace("!id_", "", $this_pre);

// print "<li>this pre : $this_pre";
$table_field = $this_pre."_table_field";
$table = $this_pre."_table";
$table_id = $this_pre."_table_id";
$table_id_val = $this_pre."_table_id_val";
$field_type = $this_pre."_field_type";
$field_size = $this_pre."_field_size";
$this_table_field = $this_pre."_this_table_field";

$table_field_sql = str_replace($this_pre."_", "", $table_field);
$table_field_sql = $_REQUEST[$table_field];
// print "<li>".$table_field_sql;
// print "<li>".$_REQUEST['cust_last_name'];
// print "<li>UPDATE ".$_REQUEST[$table]." ".$_REQUEST[$table_field]."='".$_REQUEST[$table_field_sql]."' WHERE ".$_REQUEST[$table_id]."='".$_REQUEST[$table_id_val]."' ";
// print "<pre>"; print_r($_REQUEST);
	$get_field = $_REQUEST[$this_table_field];
if(!empty($_REQUEST[$get_field])) {
	$add_this_val = urldecode($_REQUEST[$get_field]);
// 	print "<li>RESULT: get_field: $get_field | add_this_val = $add_this_val";
} else {
	$add_this_val = urldecode($_REQUEST[$table_field_sql]);
}

if($_REQUEST[$table_no_id]!="1") {
	$where_to = "WHERE ".$_REQUEST[$table_id]."='".$_REQUEST[$table_id_val]."' ";
}
updateSQL("".$_REQUEST[$table].""," ".$_REQUEST[$table_field]."='".$add_this_val."' $where_to");
$d['table'] = $_REQUEST[$table];
$d['table_field'] = $_REQUEST[$table_field];
$d['table_id'] = $_REQUEST[$table_id];
$d['table_id_val'] = $_REQUEST[$table_id_val];
$d['field_type'] = $_REQUEST[$field_type];
$d['field_size'] = $_REQUEST[$field_size];
$d['current_data'] = $add_this_val;
// print "<li>".$_REQUEST[$table_id_val];
print editField($d);



// print "<li>HERE: ".$_REQUEST['cust_first_name'];
?>
