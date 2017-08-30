<?php 
$page_id = 1;
require "sy-main.php"; 
if(empty($_REQUEST['view'])) { 
	require $setup['path']."/sy-inc/page_home.php";
}
include "sy-footer.php";
?>