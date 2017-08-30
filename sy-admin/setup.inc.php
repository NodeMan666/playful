<?php
include "../../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
ob_start(); 
require "../../sy-inc/functions.php"; 
require "../admin.icons.php";

$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
include "../admin.functions.php"; 
adminsessionCheck();
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../css/white.css" type="text/css">
 <script src="../../sy-inc/jquery-1.7.1.min.js"></script>
 <script language="javascript" src="../js/admin.js" type="text/javascript"></script>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<div class="windowPadding">
