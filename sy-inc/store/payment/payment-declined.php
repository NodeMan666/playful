<?php
if($_REQUEST['order_id'] > 0) { 
	header("location: ".$setup['temp_url_folder']."/index.php?view=order&myorder=".$_REQUEST['order_id']."");
	session_write_close();
	exit();

} else { 
	header("location: ".$setup['temp_url_folder']."/index.php?view=checkout");
	session_write_close();
	exit();
}
?>