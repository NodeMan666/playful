<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
?>
<?php
if($_REQUEST['action'] == "duplicate") { 
	$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['layout_id']."' ");
	foreach($layout AS $id => $val) {
		if((!is_numeric($id)) AND($id!=="layout_id")AND($id!=="layout_no_delete")==true) {
			if($ct>0) {
				$qry .= ", ";
			}
			if($id == "layout_name") {
				$qry.="$id='$val - COPY' ";
				$named = "$val - COPY";
			} else {
				$qry.="$id='".addslashes(stripslashes($val))."' ";
			}
			$ct++;
		}


	}
	$newlayout = insertSQL("ms_category_layouts", " $qry ");
	$_SESSION['sm'] = "New layout created and named $named";
	session_write_close();
	header("location: index.php?do=look&view=editLayout&layout_id=$newlayout");
	exit();

}
?>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  Page Display & Content Listing Layouts</div> 
<?php isFullScreenLarge();?> 
<div class="pageContent">

</div>
<div>&nbsp;</div>
<div class="buttonsgray">
	<ul>
	<li><a href="index.php?do=look&view=editLayout&layout_type=listing">Create New List Layout</a></li>
	<li><a href="index.php?do=look&view=editPageLayout&layout_type=page">Create New Page Layout</a></li>
	<div class="cssClear"></div>
	</ul>
</div>
<div id="roundedFormContain">
<div style="width: 49%; float: left;" class="nofloatsmall">
	<div class="underlinelabel"><h2>Content Listing Layouts</h2></div>
<?php
	$layouts = whileSQL("ms_category_layouts","*", "WHERE layout_type='listing' ORDER BY layout_name ASC ");
	if(mysqli_num_rows($layouts) <=0) { 
		print "<div class=\"row\">No layouts created</div>";
	}

while($layout = mysqli_fetch_array($layouts)) { ?>
	<div class="underline">
		<div class="label"><h2><a href="index.php?do=look&view=editLayout&layout_id=<?php print $layout['layout_id'];?>"><?php print $layout['layout_name'];?></a></h2></div>
		<div><?php print $layout['layout_description'];?></div>
		<!-- <div><a href="index.php?do=look&view=layouts&action=duplicate&layout_id=<?php print $layout['layout_id'];?>" " onClick="return confirm('Are you sure you want to duplicate this layout?');">Duplicate</a></div> -->
	</div>
	<?php } ?>

</div>
<div style="width: 49%; float: right;" class="nofloatsmall">
	<div class="underlinelabel"><h2>Page Display Layouts</h2></div>

	<div>
<?php
	$layouts = whileSQL("ms_category_layouts","*", "WHERE layout_type='page' ORDER BY layout_name ASC ");
	if(mysqli_num_rows($layouts) <=0) { 
		print "<div class=\"row\">No layouts created</div>";
	}

while($layout = mysqli_fetch_array($layouts)) { ?>
<div class="underline">
		<div class="label"><h2><a href="index.php?do=look&view=editPageLayout&layout_id=<?php print $layout['layout_id'];?>&layout_type=page"><?php print $layout['layout_name'];?></a></h2></div>
		<div><?php print $layout['layout_description'];?></div>
	</div>
	<?php } ?>

</div>
</div>
<div class="clear"></div>
</div>
