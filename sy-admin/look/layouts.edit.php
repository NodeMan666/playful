<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }

if(!empty($_REQUEST['deleteLayout'])) {
	deleteSQL("ms_category_layouts", "WHERE layout_id='".$_REQUEST['deleteLayout']."' ", "1");
	$_SESSION['sm'] = "Layout deleted";
	session_write_close();
	header("location: index.php?do=look&view=layouts");
	exit();
}
if(!empty($_REQUEST['submitit'])) {
	if(empty($_REQUEST['layout_name'])) {
		$error = "<div>Please enter a layout name</div>";
	}
	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
		$_REQUEST['layout_html'] = trim($_REQUEST['layout_html']);

			if($_REQUEST['layout_html'] == "<br />") {
				$_REQUEST['layout_html'] = "";
			}

		if(!empty($_REQUEST['layout_id'])) {
			$id = updateSQL("ms_category_layouts", " layout_html='".$_REQUEST['layout_html']."', layout_name='".$_REQUEST['layout_name']."' ,layout_description='".$_REQUEST['layout_description']."' , layout_css_id='".$_REQUEST['layout_css_id']."', layout_photo_size='".$_REQUEST['layout_photo_size']."' , layout_photo_class='".$_REQUEST['layout_photo_class']."' , layout_width='".$_REQUEST['layout_width']."', layout_height='".$_REQUEST['layout_height']."' , layout_photo_width='".$_REQUEST['layout_photo_width']."', layout_photo_height='".$_REQUEST['layout_photo_height']."' , layout_spacing='".$_REQUEST['layout_spacing']."' , layout_per_page='".$_REQUEST['layout_per_page']."' , layout_preview_text_length='".$_REQUEST['layout_preview_text_length']."' WHERE layout_id='".$_REQUEST['layout_id']."'  ");   		
			$id = $_REQUEST['layout_id'];
		} else {
			$id = insertSQL("ms_category_layouts", "layout_html='".$_REQUEST['layout_html']."', layout_name='".$_REQUEST['layout_name']."' ,layout_description='".$_REQUEST['layout_description']."' , layout_css_id='".$_REQUEST['layout_css_id']."' , layout_photo_size='".$_REQUEST['layout_photo_size']."' , layout_photo_class='".$_REQUEST['layout_photo_class']."' , layout_width='".$_REQUEST['layout_width']."', layout_height='".$_REQUEST['layout_height']."', layout_photo_height='".$_REQUEST['layout_photo_height']."', layout_spacing='".$_REQUEST['layout_spacing']."', layout_per_page='".$_REQUEST['layout_per_page']."' , layout_preview_text_length='".$_REQUEST['layout_preview_text_length']."', layout_type='listing'  ");   		
			$_REQUEST['layout_id'] = $id;
		}

		$_SESSION['sm'] = "Layout saved";
		session_write_close();
		header("location: index.php?do=look&view=editLayout&layout_id=".$_REQUEST['layout_id']."");
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
	if((!empty($_REQUEST['layout_id']))AND(empty($_REQUEST['submit']))==true) {
		$page = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['layout_id']."' ");
		if(empty($page['layout_id'])) {
			showError("Sorry, but there seems to be an error.");
		}
		foreach($page AS $id => $value) {
			if(!is_numeric($id)) {
				$_REQUEST[$id] = $value;
			}
		}
	}
	?>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  <a href="index.php?do=look&view=layouts">Category & Section Layouts</a> <?php print ai_sep;?>  
 	<?php  if(!empty($_REQUEST['layout_id'])) { ?>
		 Editing Layout
	<?php  }  else { ?>
		 Adding Layout
	<?php  } ?>
</div> 

<div class="buttonsgray">
	<ul>
<?php if(!empty($_REQUEST['layout_id'])) { ?>
<?php if($page['layout_no_delete'] <=0) { ?><li><?php print "<a href=\"index.php?do=look&view=editLayout&deleteLayout=".$page['layout_id']."\" onClick=\"return confirm('Are you sure you want to delete this layout? Deleting this will permanently remove it and can not be reversed! ');\">".ai_delete." Delete This Layout</a>"; ?></li><?php } ?><?php } ?>


<li><a href="index.php?do=look&view=layouts">List Layouts</a></li>

	<div class="cssClear"></div>
</ul>
</div>
<div id="roundedFormContain">
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm();">
	<div id="roundedForm">

			<div class="row">
				<div style="width:20%;" class="cssCell">Layout Name</div><div style="width:80%;" class="cssCell">
				<input type="text" size="40" name="layout_name" id="layout_name" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_name']));?>" class="field100 required"></div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:20%;" class="cssCell">Layout HTML
				</div><div style="width:80%;" class="cssCell"><textarea name="layout_html" id="layout_html" rows="12" cols="50" wrap="virtual"  class="field100 required"><?php  print htmlspecialchars(stripslashes($_REQUEST['layout_html']));?></textarea>

				</div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:20%;" class="cssCell">Description
				</div><div style="width:80%;" class="cssCell"><textarea name="layout_description" id="layout_description" rows="12" cols="50" wrap="virtual" class=textfield style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['layout_description']));?></textarea>

				</div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:20%;" class="cssCell">Layout CSS ID</div><div style="width:80%;" class="cssCell">
				<input type="text" size="20" name="layout_css_id" id="layout_css_id" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_css_id']));?>" class="required"></div>
				<div class="cssClear"></div>
			</div>
			<div class="row">
				<div style="width:20%;" class="cssCell">Photo class</div><div style="width:80%;" class="cssCell">
				<input type="text" size="20" name="layout_photo_class" id="layout_photo_class" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_photo_class']));?>" class="required"></div>
				<div class="cssClear"></div>
			</div>

			<div class="row">
				<div style="width:20%;" class="cssCell">Preview Text Length</div><div style="width:80%;" class="cssCell">
				<div><input type="text" size="4" name="layout_preview_text_length" id="layout_preview_text_length" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_preview_text_length']));?>" class="required"></div>
				<div>Enter 0 to not trim preview text</div>
				
				</div>
				<div class="cssClear"></div>
			</div>


			<div class="row">
				<div style="width:20%;" class="cssCell">Size photo to use</div><div style="width:80%;" class="cssCell">
				<select name="layout_photo_size" id="layout_photo_size">
				<option  value="pic_th" <?php if($_REQUEST['layout_photo_size'] == "pic_th") { print "selected"; } ?>>Thumbnail</option>
				<option  value="pic_pic" <?php if($_REQUEST['layout_photo_size'] == "pic_pic") { print "selected"; } ?>>Small Photo</option>
				</select></div>
				<div class="cssClear"></div>
			</div>



			<div class="row">
				<div style="width:100%;" class="cssCell" style="text-align: center;">




		<div class="row center">
		<input type="hidden" name="do" value="look">
		<input type="hidden" name="view" value="editLayout">
		<input type="hidden" name="submitit" value="yup">
		<input type="hidden" name="layout_id" value="<?php  print $_REQUEST['layout_id'];?>">
		<div id="submitButton"><input type="submit" name="submit" id="submit" value=" Save Now " class="submitBig" ></div>
		<div id="submitButtonLoading" style="display: none;"><?php print ai_loading;?></div>
		</div>
		</div>
		<div class="cssClear"></div>

	</form>
</div>

<?php  } ?>