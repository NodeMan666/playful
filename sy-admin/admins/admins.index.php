<?php
if($_REQUEST['view'] == "logins") { 
	include "admin-logins.php";
} elseif($_REQUEST['action'] == "deactivate") { 
	deactivateAccount();
} else { 
	include "admins.list.php";
}


function deleteAccount() { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
	if(!empty($p['p_id'])) { 
		deleteSQL("ms_people","WHERE p_id='".$p['p_id']."' ", "1");
		updateSQL("ms_orders", "order_customer='0' WHERE order_customer='".$p['p_id']."' ");
	}
	$_SESSION['sm'] = "Account for ".$p['p_email']." as been deleted";
	header("location: index.php?do=people");
	session_write_close();
	exit();
}

?>