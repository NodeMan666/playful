<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>The People section are the people that have either created an account or made a purchase.</h3></div>
		<div class="pc">Here you can view customers order history, view & export favorites, create invoices, export and more.</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php


if($_REQUEST['type'] == "unregistered") { 
	include "people.list.unregistered.php";
} elseif($_REQUEST['action'] == "deactivate") { 
	deactivateAccount();
} elseif($_REQUEST['action'] == "import") { 
	include "people.import.php";
} elseif($_REQUEST['action'] == "reactivate") { 
	reactivateAccount();
} elseif($_REQUEST['action'] == "removeaccess") { 
	removeAccess();
} elseif($_REQUEST['action'] == "delete") { 
	deleteAccount();
} elseif(($_REQUEST['view'] == "favorites") && ($_REQUEST['p_id'] <= 0) ==true) { 
	include "people.favorites.php";
} elseif($_REQUEST['view'] == "allcontracts") { 
			showContractDir();
	if($_REQUEST['sub'] == "language") { 
		include "contract.language.php";
	} else if($_REQUEST['sub'] == "templates") { 
		include "people.contracts.templates.php";
	} else if($_REQUEST['sub'] == "folder") { 
		include "people.contracts.folder.php";

	} else { 
		include "people.contracts.all.php";
	}
} elseif($_REQUEST['view'] == "export") { 
	include "people-export.php";
} elseif($_REQUEST['view'] == "giftcertificates") { 
	if($_REQUEST['sub'] == "settings") { 
		include "people.gift.certificates.card.php";
	} elseif($_REQUEST['sub'] == "language" ) { 
		include "people.gift.certificates.language.php";
	} elseif($_REQUEST['sub'] == "amounts" ) { 
		include "people.gift.certificates.amounts.php";
	} else { 
		include "people.gift.certificates.php";
	}
} elseif($_REQUEST['view'] == "optout") { 
	include "people.no.email.php";
} elseif($_REQUEST['view'] == "mailList") { 
	include "people.mail.list.php";
} elseif($_REQUEST['view'] == "mailListSettings") { 
	include "people.mail.list.settings.php";
} elseif($_REQUEST['view'] == "mailchimp") { 
	include "people.mail.chimp.php";
} elseif(!empty($_REQUEST['p_id'])) { 
	include "people.view.php";
} else { 
	include "people.list.php";
}

function removeAccess() { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
	deleteSQL("ms_my_pages", "WHERE mp_id='".$_REQUEST['mp_id']."' ", "1");
	$_SESSION['sm'] = "Gallery removed";
	header("location: index.php?do=people&p_id=".$p['p_id']."");
	session_write_close();
	exit();
}

function deactivateAccount() { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
	if(!empty($p['p_id'])) { 
		updateSQL("ms_people","p_deactivated='1' WHERE p_id='".$p['p_id']."' ");
	}
	$_SESSION['sm'] = "Account for ".$p['p_email']." as been deactivated";
	header("location: index.php?do=people&p_id=".$p['p_id']."");
	session_write_close();
	exit();
}
function reactivateAccount() { 
	$p = doSQL("ms_people", "*", "WHERE p_id='".$_REQUEST['p_id']."' ");
	if(!empty($p['p_id'])) { 
		updateSQL("ms_people","p_deactivated='0' WHERE p_id='".$p['p_id']."' ");
	}
	$_SESSION['sm'] = "Account for ".$p['p_email']." as been reactivated";
	header("location: index.php?do=people&p_id=".$p['p_id']."");
	session_write_close();
	exit();
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