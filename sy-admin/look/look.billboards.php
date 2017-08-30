<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(!empty($_REQUEST['deleteBillboard'])) {
	deleteSQL("ms_billboards", "WHERE bill_id='".$_REQUEST['deleteBillboard']."' ", "1");
	$_SESSION['sm'] = "Billboard deleted";
	session_write_close();
	header("location: index.php?do=look&action=billboardsList");
	exit();
}
if(!empty($_REQUEST['submitit'])) {
	if(empty($_REQUEST['bill_name'])) {
		$error = "<div>Please enter an billboard name</div>";
	}
	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
		$_REQUEST['bill_html'] = trim($_REQUEST['bill_html']);

			if($_REQUEST['bill_html'] == "<br />") {
				$_REQUEST['bill_html'] = "";
			}

		if(!empty($_REQUEST['bill_id'])) {
			$id = updateSQL("ms_billboards", " bill_html='".$_REQUEST['bill_html']."', bill_name='".$_REQUEST['bill_name']."' ,bill_editor='".$_REQUEST['bill_editor']."'  WHERE bill_id='".$_REQUEST['bill_id']."'  ");   		
			$id = $_REQUEST['bill_id'];
		} else {
			$id = insertSQL("ms_billboards", "bill_html='".$_REQUEST['bill_html']."', bill_name='".$_REQUEST['bill_name']."' ,bill_editor='".$_REQUEST['bill_editor']."' ");   		
			$_REQUEST['bill_id'] = $id;
		}

		$_SESSION['sm'] = "Billboard saved";
		session_write_close();
		header("location: index.php?do=look&action=billboards&subdo=editBillboard&bill_id=".$_REQUEST['bill_id']."");
		exit();
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	

<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup;
	if((!empty($_REQUEST['bill_id']))AND(empty($_REQUEST['submit']))==true) {
		$page = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bill_id']."' ");
		if(empty($page['bill_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($page AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	?>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  <a href="index.php?do=look&action=billboardsList">Billboards</a> <?php print ai_sep;?>  
 	<?php  if(!empty($_REQUEST['bill_id'])) { ?>
		 Editing Billboard
	<?php  }  else { ?>
		 Adding Billboard
	<?php  } ?>
</div> 
<?php isFullScreenLarge();?> 

<div id="formTabContainer">
	<div class="formTabRight"><?php if(!empty($_REQUEST['bill_id'])) { print "<a href=\"index.php?do=look&action=billboards&deleteBillboard=".$page['bill_id']."\" onClick=\"return confirm('Are you sure you want to delete this billboard? Deleting this will permanently remove it and can not be reversed! ');\">".ai_delete." Delete This Billboard</a>"; } ?></div>
	<div class="cssClear"></div>
</div>
<div id="roundedFormContain">
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;">
	<div id="roundedForm">

			<div class="row">
				<div style="width:20%;" class="cssCell">Billboard Name</div><div style="width:80%;" class="cssCell"><input type="text" class="textfield" size=40 name="bill_name" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['bill_name']));?>" style="width: 98%;"></div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:20%;" class="cssCell">Editor</div><div style="width:80%;" class="cssCell">
				<input type="radio" name="bill_editor" value="0" <?php if(($_REQUEST['bill_editor'] == "0")OR(empty($_REQUEST['bill_editor']))==true) { print "checked"; } ?>>Use WYSIWYG HTML Editor<br>
				<input type="radio" name="bill_editor" value="1" <?php if($_REQUEST['bill_editor'] == "1") { print "checked"; } ?>>Use plain text area
			</div>
				<div class="cssClear"></div>
			</div>
<?php
	if($_REQUEST['bill_html'] == "<br />") {
	$_REQUEST['bill_html'] = "";
	}

?>
			<div class="row">
				<div style="width:20%;" class="cssCell">Billboard Content
				</div><div style="width:80%;" class="cssCell"><textarea name="bill_html" id="bill_html" rows="12" cols="50" wrap="virtual" class=textfield style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['bill_html']));?></textarea>

			<?php 
				if($_REQUEST['bill_editor']!=="1") { 
			$style_sheet = "/sy-style.php?csst=".$site_setup['css']."&admin_edit=1";
			?>

				<script>
				var oEdit1 = new InnovaEditor("oEdit1");
				oEdit1.width="100%";
				oEdit1.height="550px";
				oEdit1.css="<?php print $style_sheet;?>";
				oEdit1.btnStyles=true;
				oEdit1.cmdAssetManager="modalDialogShow('<?php print "/".$setup['manage_folder'];?>/assetmanager/assetmanager.php',640,445);";
				oEdit1.REPLACE("bill_html");
				</script>
				<?php } ?>

				</div>
				<div class="cssClear"></div>
			</div>


			<div class="row">
				<div style="width:100%;" class="cssCell" style="text-align: center;">



	<center>
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="action" value="billboards">
	<input type="hidden" name="subdo" value="editBillboard">

		<input type="hidden" name="submitit" value="yup">

	<input type="hidden" name="bill_id" value="<?php  print $_REQUEST['bill_id'];?>">
	<?php  if(!empty($_REQUEST['bill_id'])) { ?>
		<input  type="submit" name="submit" class="submit" value="Update Billboard">
	<?php  } else { ?>
		<input  type="submit" name="submit" class="submit" value="Add Billboard">
	<?php  } ?>
	</center>
				</div>
				<div class="cssClear"></div>

			</form>
</div>

<?php  } ?>



<?php 


function createNewPage() {
	global $site_setup,$setup;
	$page = doSQL("ms_pages", "*", "WHERE bill_id='".$_REQUEST['bill_id']."' ");
	$id = $page['bill_id'];
	$page_link = stripslashes(trim(strtolower($page['bill_name'])));
	$page_link = strip_tags($page_link);
	$page_link = str_replace(" ","_",$page_link);
	$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
	if(!empty($_REQUEST['page_under'])) {
		$parent_page = doSQL("ms_pages", "*", "WHERE bill_id='".$_REQUEST['page_under']."' ");
		$page_link = $parent_page['page_link']."/".$page_link;
	}


	if(file_exists($setup['path']."/".$setup['pages_folder']."/".$page_link)) {
		$page_link = $page_link."_".$page['bill_id'];
	}
	$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/".$setup['pages_folder']."")), -4); 
if($parent_permissions == "0755") {
	$perms = 0755;
	print "<li>A";
} elseif($parent_permissions == "0777") {
	$perms = 0777;
	print "<li>B";
} else {
		$perms = 0755;
	print "<li>C";
}
print "<li>$parent_permissions<li>$page_link<li>$perms<li>";

	mkdir("".$setup['path']."/".$setup['pages_folder']."/$page_link", $perms);
	chmod("".$setup['path']."/".$setup['pages_folder']."/$page_link", $perms);
	updateSQL("ms_pages", "page_link='".$page_link."' WHERE bill_id='".$page['bill_id']."' ");
	print "Create: ".$setup['path']."/".$setup['pages_folder']."/".$page_link."/index.php";
//	copy("".$setup['path']."/".$setup['pages_folder']."/default.php", "".$setup['path']."/".$setup['pages_folder']."/".$page_link."/index.php");

	$fp = fopen("".$setup['path']."/".$setup['pages_folder']."/".$page_link."/index.php", "w");
	if(!empty($_REQUEST['page_under'])) {
		$info =  "<?php\n\$bill_id = $id; \ninclude \"../../index.php\";\n?>"; 
	} else {
		$info =  "<?php\n\$bill_id = $id; \ninclude \"../index.php\";\n?>"; 
	}
	fputs($fp, "$info\n");
	fclose($fp);

//	exit();

}

?>
