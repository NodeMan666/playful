<?php 
if(!function_exists(msIncluded)) { die("Direct file access denied"); }
if(empty($setup['sy_layouts_custom'])) { 
	$custom_layout_folder = "sy-layouts-custom";
} else { 
	$custom_layout_folder = $setup['sy_layouts_custom'];
}

if(!empty($_REQUEST['deleteBillboard'])) {
	deleteSQL("ms_category_layouts", "WHERE layout_id='".$_REQUEST['deleteBillboard']."' ", "1");
	$_SESSION['sm'] = "Billboard deleted";
	session_write_close();
	header("location: index.php?do=look&action=billboardsList");
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
		$parent_permissions = substr(sprintf('%o', fileperms("".$setup['path']."/sy-photos")), -4); 
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

		if(!is_dir($setup['path']."/$custom_layout_folder")) { 
			mkdir("".$setup['path']."/$custom_layout_folder", $perms);
			chmod("".$setup['path']."/$custom_layout_folder", $perms);
			$fp = fopen("".$setup['path']."/$custom_layout_folder/index.php", "w");
			fputs($fp, "$info\n");
			fclose($fp);

		}

		if(!empty($_REQUEST['layout_id'])) {
			$layout = doSQL("ms_category_layouts", "*", "WHERE layout_id='".$_REQUEST['layout_id']."' ");

			$id = updateSQL("ms_category_layouts", " layout_name='".$_REQUEST['layout_name']."' ,layout_description='".$_REQUEST['layout_description']."' , layout_css_id='".$_REQUEST['layout_css_id']."', layout_photo_size='".$_REQUEST['layout_photo_size']."' , layout_photo_class='".$_REQUEST['layout_photo_class']."' , layout_width='".$_REQUEST['layout_width']."', layout_height='".$_REQUEST['layout_height']."' , layout_photo_width='".$_REQUEST['layout_photo_width']."', layout_photo_height='".$_REQUEST['layout_photo_height']."' , layout_spacing='".$_REQUEST['layout_spacing']."' , layout_per_page='".$_REQUEST['layout_per_page']."' , layout_preview_text_length='".$_REQUEST['layout_preview_text_length']."', layout_css='".$_REQUEST['layout_css']."', layout_folder='$custom_layout_folder' WHERE layout_id='".$_REQUEST['layout_id']."'  ");   		
			$id = $_REQUEST['layout_id'];


			if((!empty($layout['layout_file']))&&(!empty($layout['layout_folder']))==true) {
				unlink($setup['path']."/".$layout['layout_folder']."/".$layout['layout_file']);
			}

			$page_link = stripslashes(trim(strtolower($_REQUEST['layout_name'])));
			$page_link = strip_tags($page_link);
			$page_link = str_replace(" ","-",$page_link);
			$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
			$page_link = "listing-".$page_link."-".date('ymdhis');

			if(file_exists($setup['path']."/$custom_layout_folder/".$page_link.".php")) {
				$page_link = $page_link."".$site_setup['sep_page_names']."-$id";
			}
			$page_link = $page_link.".php";
			$fp = fopen("".$setup['path']."/$custom_layout_folder/".$page_link."", "w");
			$info =  stripslashes($_REQUEST['layout_html']); 
			fputs($fp, "$info\n");
			fclose($fp);
			updateSQL("ms_category_layouts", "layout_file='".$page_link."' WHERE layout_id='$id' ");

		} else {
			$id = insertSQL("ms_category_layouts", "layout_name='".$_REQUEST['layout_name']."' ,layout_description='".$_REQUEST['layout_description']."' , layout_css_id='".$_REQUEST['layout_css_id']."' , layout_photo_size='".$_REQUEST['layout_photo_size']."' , layout_photo_class='".$_REQUEST['layout_photo_class']."' , layout_width='".$_REQUEST['layout_width']."', layout_height='".$_REQUEST['layout_height']."', layout_photo_height='".$_REQUEST['layout_photo_height']."', layout_spacing='".$_REQUEST['layout_spacing']."', layout_per_page='".$_REQUEST['layout_per_page']."' , layout_preview_text_length='".$_REQUEST['layout_preview_text_length']."', layout_css='".$_REQUEST['layout_css']."' , layout_type='listing', layout_folder='$custom_layout_folder' ");   		
			$_REQUEST['layout_id'] = $id;

			$page_link = stripslashes(trim(strtolower($_REQUEST['layout_name'])));
			$page_link = strip_tags($page_link);
			$page_link = str_replace(" ","-",$page_link);
			$page_link = preg_replace("/[^a-z_0-9-]/","", $page_link);
			$page_link = "listing-".$page_link."-".date('ymdhis');

			if(file_exists($setup['path']."/$custom_layout_folder/".$page_link.".php")) {
				$page_link = $page_link."".$site_setup['sep_page_names']."-$id";
			}
			$page_link = $page_link.".php";
			$fp = fopen("".$setup['path']."/$custom_layout_folder/".$page_link."", "w");
			$info =  stripslashes($_REQUEST['layout_html']); 
			fputs($fp, "$info\n");
			fclose($fp);
			updateSQL("ms_category_layouts", "layout_file='".$page_link."' WHERE layout_id='$id' ");

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
	global $tr, $_REQUEST, $setup, $site_setup,$custom_layout_folder;
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
	<ul><?php if(!empty($_REQUEST['layout_id'])) { ?><?php if($page['layout_no_delete'] <=0) { ?>
	<li><?php print "<a href=\"index.php?do=look&view=editPageLayout&deleteLayout=".$page['layout_id']."\" onClick=\"return confirm('Are you sure you want to delete this layout? Deleting this will permanently remove it and can not be reversed! ');\">".ai_delete." Delete This Layout</a>"; ?></li><?php } ?><?php } ?>
	<li><a href="index.php?do=look&view=layouts">List Layouts</a></li>
	
	<div class="cssClear"></div>
	</ul>
</div>
<div id="roundedFormContain">
<form name="register" action="index.php" method="post" style="padding:0; margin: 0;"  onSubmit="return checkForm();">

	<div style="width: 48%; float: left;">
		
			<div class="underline">
				<div class="label">Layout Name</div>
				<div><input type="text" size="40" name="layout_name" id="layout_name" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_name']));?>" class="field100 required"></div>
			</div>
			
			<?php 
				if(!empty($_REQUEST['layout_file'])) { 
					if(empty($page['layout_folder'])) { 
						$file = file_get_contents($setup['path']."/".$setup['layouts_folder']."/".$_REQUEST['layout_file']."", FILE_USE_INCLUDE_PATH);
					} else { 
						$file = file_get_contents($setup['path']."/$custom_layout_folder/".$_REQUEST['layout_file']."", FILE_USE_INCLUDE_PATH);
					}
				}
				?>
			<div class="underline">
				<div class="label">HTML</div>
				<div>This code controls the display of the content. There are PHP functions used to display titles, photos, etc... Refer to the manual for available codes.</div>
				<div><textarea name="layout_html" id="layout_html" rows="12" cols="50" wrap="virtual"  class="field100 required"><?php  print $file;?></textarea></div>
			</div>



			<div class="underline">
				<div class="label">Description</div>
				<div><textarea name="layout_description" id="layout_description" rows="3" cols="50" wrap="virtual" class=textfield style="width: 98%;"><?php  print htmlspecialchars(stripslashes($_REQUEST['layout_description']));?></textarea></div>
			</div>

	</div>

	<div style="width: 48%; float: right;">

			<div class="underline">
				<div class="label">Layout CSS ID</div>
				<div><input type="text" size="20" name="layout_css_id" id="layout_css_id" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_css_id']));?>" class="required"></div>
			</div>

			<div class="underline">
				<div class="label">Photo class</div>
				<div><input type="text" size="20" name="layout_photo_class" id="layout_photo_class" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_photo_class']));?>" class="required"></div>
			</div>

			<div class="underline">
				<div class="label">Size photo to use</div>
				<div>
				<select name="layout_photo_size" id="layout_photo_size">
				<option  value="pic_mini" <?php if($_REQUEST['layout_photo_size'] == "pic_mini") { print "selected"; } ?>>Mini</option>
				<option  value="pic_th" <?php if($_REQUEST['layout_photo_size'] == "pic_th") { print "selected"; } ?>>Thumbnail</option>
				<option  value="pic_pic" <?php if($_REQUEST['layout_photo_size'] == "pic_pic") { print "selected"; } ?>>Small Photo</option>
				<option  value="pic_large" <?php if($_REQUEST['layout_photo_size'] == "pic_large") { print "selected"; } ?>>Large Photo</option>
				</select></div>
			</div>

			<div class="underline">
				<div  class="label">Preview Text Length</div>
				<div><input type="text" size="4" name="layout_preview_text_length" id="layout_preview_text_length" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_preview_text_length']));?>"> characters. Enter 0 to not trim preview text</div>
			</div>

			<div class="underline">
				<div class="label">Width</div>
				<div><input type="text" size="4" name="layout_width" id="layout_width" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['layout_width']));?>"> pixels. Enter 0 to use the width in your theme.</div>
			</div>



			<div class="underline center">
			<input type="hidden" name="do" value="look">
			<input type="hidden" name="view" value="editLayout">
			<input type="hidden" name="submitit" value="yup">
			<input type="hidden" name="layout_id" value="<?php  print $_REQUEST['layout_id'];?>">
			<div><input type="submit" name="submit" id="submitButton" value=" Save Now " class="submitBig" ></div>
			</div>

		<div>&nbsp;</div>


		</div>

		<div class="cssClear"></div></div>
		<div>&nbsp;</div>

		<div class="pc">
		<h3>PHP functions</h3>
		Use the following PHP functions in the HTML section to display information from the pages being listed.
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingPhoto($page); ?>
			</div>
			<div class="left p60">
Preview photo
			</div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p40">
&lt;?php listingTitle($page); ?>
			</div>
			<div class="left p60">
Page title with link to page
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingTitleOnly($page); ?>
			</div>
			<div class="left p60">
Page title only
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingTotalPhotos($page); ?>
			</div>
			<div class="left p60">
Total photos for page
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingDate($page); ?>
			</div>
			<div class="left p60">
Page date
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingTime($page); ?>
			</div>
			<div class="left p60">
Page time
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingPrice($page); ?>
			</div>
			<div class="left p60">
Product price
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingExpireDate($page); ?>
			</div>
			<div class="left p60">
Page expiration date
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingCategories($page); ?>
			</div>
			<div class="left p60">
Categories page assigned to
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingPreviewText($page); ?>
			</div>
			<div class="left p60">
Preview text or snippet of page
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingFullText($page); ?> 
			</div>
			<div class="left p60">
Complete text of page
			</div>
			<div class="clear"></div>
		</div>

		<div class="underline">
			<div class="left p40">
&lt;?php listingFirstPhoto($page); ?>
			</div>
			<div class="left p60">
 Showing first photo from page
			</div>
			<div class="clear"></div>
		</div>
		<div class="underline">
			<div class="left p40">
&lt;?php listingAddToCart($page); ?> 
			</div>
			<div class="left p60">
Add to cart link for products
			</div>
			<div class="clear"></div>
		</div>



	</form>
</div>

<?php  } ?>