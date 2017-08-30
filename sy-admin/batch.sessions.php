<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 

?>

<?php
if($_REQUEST['action'] == "holdPhoto") { 
	if(!is_array($_SESSION['heldPhotos'])) { 
		$_SESSION['heldPhotos'] = array();
	}
	if(!in_array($_REQUEST['pic_id'],$_SESSION['heldPhotos'])) { 
		array_push($_SESSION['heldPhotos'],$_REQUEST['pic_id']);
	}
}
?>
