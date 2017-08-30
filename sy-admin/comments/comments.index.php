<?php
 if($_REQUEST['view'] == "settings") {
	 include "comments.settings.php";
} elseif($_REQUEST['view'] == "text") {
	 include "comments.text.php";
} else if($_REQUEST['action'] == "batchApprove") {
	batchApprove();
} else if($_REQUEST['action'] == "emptyTrash") {
	emptyTrash();
} else if($_REQUEST['action'] == "batchTrash") {
	batchTrash();
} else if(!empty($_REQUEST['approve'])) {
	approveComment();
} else if(!empty($_REQUEST['trash'])) {
	trashComment();
} else {
	include "comments.list.php";
}
?>


<?php

function approveComment() { 
	updateSQL("ms_comments", "com_approved='1' , com_approved_date=NOW() WHERE com_id='".$_REQUEST['approve']."' ");
	$_SESSION['sm'] = "Comment has been approved and now live on the website";
	session_write_close();
	header("location: index.php?do=comments&status=".$_REQUEST['status']."");
	exit();
}
function trashComment() { 
	updateSQL("ms_comments", "com_approved='2' , com_approved_date=NOW() WHERE com_id='".$_REQUEST['trash']."' ");
	$_SESSION['sm'] = "Comment has been moved to trash";
	session_write_close();
	header("location: index.php?do=comments&status=".$_REQUEST['status']."");
	exit();
}


function emptyTrash() { 

	$orders = whileSQL("ms_comments", "*", "WHERE com_approved='2' ");
	while($order = mysqli_fetch_array($orders)) {
		deleteSQL("ms_comments", "WHERE com_id='".$order['com_id']."' ", "1");
	}
	$_SESSION['sm'] = "Trash has been emptied";
	session_write_close();
	header("location: index.php?do=comments");
	exit();
}

function batchApprove() { 
	if(empty($_REQUEST['com_id'])) {
		$_SESSION['smerror'] = "You did not select any comments";
		session_write_close();
		header("location: index.php?do=comments&status=".$_REQUEST['status']."&pg=".$_REQUEST['pg']." ");
		exit();
	}
	foreach($_REQUEST['com_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_comments", "com_approved='1', com_approved_date=NOW() WHERE com_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total commments approved";
	session_write_close();
	header("location: index.php?do=comments&status=".$_REQUEST['status']."");
	exit();
}

function batchTrash() { 
	if(empty($_REQUEST['com_id'])) {
		$_SESSION['smerror'] = "You did not select any comments";
		session_write_close();
		header("location: index.php?do=comments&status=".$_REQUEST['status']."&pg=".$_REQUEST['pg']." ");
		exit();
	}
	foreach($_REQUEST['com_id'] AS $id => $order_num) { 
		print "<li>$order_num";
		updateSQL("ms_comments", "com_approved='2' WHERE com_id='$order_num' ");
		$total++;
	}
	$_SESSION['sm'] = "$total commments trashed";
	session_write_close();
	header("location: index.php?do=comments&status=".$_REQUEST['status']."");
	exit();
}

?>
