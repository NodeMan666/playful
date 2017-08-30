<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>When orders are made, they are all listed and managed in this section.</h3></div>
		<div class="pc">You can manage the order status and create your own. Print out a packing slip, manually add payment, export photo file names on the orders and more. </div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php
 if($_REQUEST['action'] == "viewOrder") {
	 include "order.view.php";
} else if($_REQUEST['action'] == "addshipping") {
	include "order.shipping.php";
} else if($_REQUEST['action'] == "managephotos") {
	include "order.photos.php";
} else if($_REQUEST['action'] == "email") {
	include "order.email.php";
} else if($_REQUEST['action'] == "exportsettings") {
	include "order.export.settings.php";
} else if($_REQUEST['action'] == "itemsexportsettings") {
	include "order.items.export.settings.php";
} else if($_REQUEST['action'] == "shippingoptions") {
	include "order.shipping.options.php";
} else if($_REQUEST['action'] == "packingslip") {
	include "order.packing.slip.php";
} else if($_REQUEST['view'] == "invoice") {

require "people/people.invoice.php";

} else if($_REQUEST['action'] == "archivetable") {
	include "order.archive.table.php";
} else if($_REQUEST['action'] == "payments") {
	include "order.scheduled.payments.php";
} else if($_REQUEST['action'] == "updateViewed") {
	updateViewed();
} else if($_REQUEST['action'] == "updateUnViewed") {
	updateUnViewed();
} else if($_REQUEST['action'] == "archiveOrder") {
	archiveOrder();
} else if($_REQUEST['action'] == "unarchiveOrder") {
	unarchiveOrder();
} else if($_REQUEST['action'] == "updatestatus") {
	updatestatus();
} else if($_REQUEST['action'] == "deleteorderstatus") {
	deleteorderstatus();
} else if($_REQUEST['action'] == "openOrder") {
	openOrder();
} else if($_REQUEST['action'] == "trashOrder") {
	trashOrder();
} else if($_REQUEST['action'] == "untrashOrder") {
	untrashOrder();
} else if($_REQUEST['action'] == "emptyTrash") {
	emptyTrash();
} else if($_REQUEST['action'] == "batchArchive") {
	batchArchive();
} else if($_REQUEST['action'] == "batchTrash") {
	batchTrash();
} else if($_REQUEST['action'] == "batchToNew") {
	batchToNew();
} else if($_REQUEST['action'] == "deleteOrder") {
	deleteOrder();
} else if($_REQUEST['action'] == "reports") {
	include "orders.reports.php";
} else if(is_numeric($_REQUEST['action'])) { 
	batchToCustomStatus();
} else {
	include "orders.list.php";
}
?>


<?php
function emptyTrash() { 

	$orders = whileSQL("ms_orders", "*", "WHERE order_status='2' ");
	while($order = mysqli_fetch_array($orders)) {
		deleteSQL("ms_orders", "WHERE order_id='".$order['order_id']."' ", "1");
		deleteSQL2("ms_cart", "WHERE cart_order='".$order['order_id']."' ");
	}
	$_SESSION['sm'] = "Trash has been emptied";
	session_write_close();
	header("location: index.php?do=orders&orderq=".$_REQUEST['orderq']." ");
	exit();
}

function batchArchive() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&orderq=".$_REQUEST['orderq']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_orders", "order_status='1',  order_open_status='0'  WHERE order_id='$order_num' ");

		$order = doSQL("ms_orders","*","WHERE order_id='".$order_num."' ");
		archiveordertable($order);
		$total++;
	}
	$_SESSION['sm'] = "$total orders sent to archive";
	session_write_close();
	header("location: index.php?do=orders&orderq=".$_REQUEST['orderq']."&order_open_status=".$_REQUEST['order_open_status']."&date_id=".$_REQUEST['date_id']."");
	exit();
}

function batchTrash() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_orders", "order_status='2',  order_open_status='0'  WHERE order_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total orders sent to trash";
	session_write_close();
	header("location: index.php?do=orders&orderq=".$_REQUEST['orderq']."&order_open_status=".$_REQUEST['order_open_status']."&date_id=".$_REQUEST['date_id']."");
	exit();
}

function batchToNew() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_orders", "order_status='0' , order_open_status='0' WHERE order_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total orders sent to archive";
	session_write_close();
	header("location: index.php?do=orders&orderq=".$_REQUEST['orderq']."&date_id=".$_REQUEST['date_id']." ");
	exit();
}
function batchToCustomStatus() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	$status = doSQL("ms_order_status", "*", "WHERE status_id='".$_REQUEST['action']."' ");
	if(empty($status['status_id'])) { 
		$_SESSION['smerror'] = "Can not find custom order status";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_orders", "order_status='0', order_open_status='".$status['status_id']."' WHERE order_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total orders sent to archive";
	session_write_close();
	header("location: index.php?do=orders&orderq=".$_REQUEST['orderq']."&date_id=".$_REQUEST['date_id']." ");
	exit();
}

function updateViewed() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&orderq=".$_REQUEST['orderq']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_orders", "order_viewed='1' WHERE order_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total orders updated to viewed";
	session_write_close();
	header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&date_id=".$_REQUEST['date_id']." ");
	exit();
}


function updateUnViewed() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select any orders";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&date_id=".$_REQUEST['date_id']." ");
		exit();
	}
	foreach($_REQUEST['order_id'] AS $id => $order_num) { 
		updateSQL("ms_orders", "order_viewed='0' WHERE order_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total orders updated to viewed";
	session_write_close();
	header("location: index.php?do=orders&pg=".$_REQUEST['pg']."&orderq=".$_REQUEST['orderq']."&date_id=".$_REQUEST['date_id']." ");
	exit();
}

function deleteorderstatus() { 
	deleteSQL("ms_order_status", "WHERE status_id='".$_REQUEST['status_id']."' ", "1");
	updateSQL("ms_orders", "order_open_status='0' WHERE order_open_status='".$_REQUEST['status_id']."' ");

	$_SESSION['sm'] = "Order status deleted";
	session_write_close();
	header("location: index.php?do=orders");
	exit();

}

function updatestatus() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_open_status='".$_REQUEST['status_id']."' WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." status updated";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}


function archiveOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_status='1' , order_open_status='0' WHERE order_id='".$_REQUEST['order_id']."' ");
	$order = doSQL("ms_orders","*","WHERE order_id='".$_REQUEST['order_id']."' ");
	archiveordertable($order);
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." has been archived";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}

function unarchiveOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_status='0' , order_open_status='0' WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." removed from archive";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}
function openOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_status='0', order_open_status='0'  WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." removed from archive";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}

function trashOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_status='2', order_open_status='0'  WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." has been sent to trash";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}

function untrashOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "You did not select an order";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	updateSQL("ms_orders", "order_status='0' WHERE order_id='".$_REQUEST['order_id']."' ");
	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." removed from trash";
	session_write_close();
	if($_REQUEST['bto'] == "list") { 
		header("location: index.php?do=orders&pg=".$_REQUEST['bpg']."&orderStatus=".$_REQUEST['bos']."&q=".$_REQUEST['bq']."&date_id=".$_REQUEST['date_id']."&order_open_status=".$_REQUEST['boos']."#order-".$_REQUEST['order_id']."");

	} else{ 
		header("location: index.php?do=orders&action=viewOrder&orderNum=".$_REQUEST['order_id']." ");
	}
	exit();
}




function deleteOrder() { 
	if(empty($_REQUEST['order_id'])) {
		$_SESSION['smerror'] = "Order ID not found";
		session_write_close();
		header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
		exit();
	}
	deleteSQL("ms_orders", "WHERE order_id='".$_REQUEST['order_id']."' ", "1");
	deleteSQL2("ms_cart", "WHERE cart_order='".$_REQUEST['order_id']."' ");

	$_SESSION['sm'] = "Order ".$_REQUEST['order_id']." was deleted";
	session_write_close();
	header("location: index.php?do=orders&pg=".$_REQUEST['pg']." ");
	exit();
}


?>