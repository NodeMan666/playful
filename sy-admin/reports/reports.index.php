<?php if($setup['demo_mode'] == true) { ?>
<div id="demomode">
	<div style="padding: 16px;">
		<div class="pc"><h3>View Sales & Expense Reports</h3></div>
		<div class="pc">This section shows you reports on order totals and you can even enter and track your expenses.</div>
	</div>
</div>
<div>&nbsp;</div>
<?php } ?>
<?php
if(!empty($_SESSION['ms_lang'])) {
	$lang = doSQL("ms_language", "*", "WHERE lang_id='".$_SESSION['ms_lang']."' ");
} else {
	$lang = doSQL("ms_language", "*", "WHERE lang_default='1' ");
}
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$sytist_store = true;
if($sytist_store == true) { 
	$storelang = doSQL("ms_store_language", "*", " ");
	foreach($storelang AS $id => $val) {
		if(!is_numeric($id)) {
			define($id,$val);
		}
	}
}


if($_REQUEST['type'] == "unregistered") { 
	include "people.list.unregistered.php";
} elseif($_REQUEST['action'] == "deleteExpense") { 
	deleteExpense();
} elseif($_REQUEST['action'] == "deleteTag") { 
	deleteTag();
} elseif($_REQUEST['action'] == "expenses") { 
	include "expenses.list.php";
} else { 
	include "sales.report.php";
}


function deleteExpense() { 
	$exp = doSQL("ms_expenses", "*", "WHERE exp_id='".$_REQUEST['exp_id']."' ");
	if(!empty($exp['exp_id'])) { 
		deleteSQL("ms_expenses","WHERE exp_id='".$exp['exp_id']."' ", "1");
	}
	$_SESSION['sm'] = "Expense Deleted";
	header("location: index.php?do=reports&action=expenses&year=".$_REQUEST['year']."&tag_id=".$_REQUEST['tag_id']."");
	session_write_close();
	exit();
}
function deleteTag() { 
	$tag = doSQL("ms_expenses_tags", "*", "WHERE tag_id='".$_REQUEST['tag_id']."' ");
	if(!empty($tag['tag_id'])) { 
		deleteSQL("ms_expenses_tags","WHERE tag_id='".$tag['tag_id']."' ", "1");
	}
	$_SESSION['sm'] = "Label Deleted";
	header("location: index.php?do=reports&action=expenses&year=".$_REQUEST['year']."&tag_id=".$_REQUEST['tag_id']."");
	session_write_close();
	exit();
}

?>