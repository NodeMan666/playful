<?php
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;

if($_REQUEST['subdo'] == "prodConfig") { 
	updateSQL("ms_calendar", "prod_opt1='".addslashes(stripslashes($_REQUEST['prod_opt1']))."' , prod_opt2='".addslashes(stripslashes($_REQUEST['prod_opt2']))."' , prod_opt3='".addslashes(stripslashes($_REQUEST['prod_opt3']))."' , prod_opt4='".addslashes(stripslashes($_REQUEST['prod_opt4']))."' , prod_opt5='".addslashes(stripslashes($_REQUEST['prod_opt5']))."' WHERE date_id='".$_REQUEST['date_id']."' ");
	$_SESSION['sm'] = "Product Configurations Updated";
	session_write_close();
	header("location: index.php?do=news&action=subProds&date_id=".$_REQUEST['date_id']."");
	exit();
}
if($_REQUEST['subdo'] == "deleteSub") { 
	deleteSQL("ms_product_subs", "WHERE sub_id='".$_REQUEST['sub_id']."' ", "1");
	$_SESSION['sm'] = "Product option deleted";
	session_write_close();
	header("location: index.php?do=news&action=subProds&date_id=".$_REQUEST['date_id']."");
	exit();
}


if($_POST['submitit']=="yes") { 
	if(empty($_REQUEST['sub_id'])) { 
		$sub_id = insertSQL("ms_product_subs", "sub_sku='".addslashes(stripslashes($_REQUEST['sub_sku']))."' , sub_add_price='".addslashes(stripslashes($_REQUEST['sub_add_price']))."' , sub_main_prod='".addslashes(stripslashes($_REQUEST['date_id']))."', opt1_value='".addslashes(stripslashes($_REQUEST['opt1_value']))."' , opt2_value='".addslashes(stripslashes($_REQUEST['opt2_value']))."' , opt3_value='".addslashes(stripslashes($_REQUEST['opt3_value']))."' , opt4_value='".addslashes(stripslashes($_REQUEST['opt4_value']))."' , opt5_value='".addslashes(stripslashes($_REQUEST['opt5_value']))."'  ,    sub_qty='".addslashes(stripslashes($_REQUEST['sub_qty']))."' , sub_cost='".$_REQUEST['sub_cost']."', sub_pic_id='".$_REQUEST['sub_pic_id']."' ");

		$_SESSION['sm'] = "Product Added";
		session_write_close();
		header("location: index.php?do=news&action=subProds&date_id=".$_REQUEST['date_id']."");
		exit();

	} else {
		updateSQL("ms_product_subs", "  sub_sku='".addslashes(stripslashes($_REQUEST['sub_sku']))."' , sub_add_price='".addslashes(stripslashes($_REQUEST['sub_add_price']))."' , sub_main_prod='".addslashes(stripslashes($_REQUEST['date_id']))."' , opt1_value='".addslashes(stripslashes($_REQUEST['opt1_value']))."' , opt2_value='".addslashes(stripslashes($_REQUEST['opt2_value']))."' , opt3_value='".addslashes(stripslashes($_REQUEST['opt3_value']))."' , opt4_value='".addslashes(stripslashes($_REQUEST['opt4_value']))."' , opt5_value='".addslashes(stripslashes($_REQUEST['opt5_value']))."' , sub_qty='".$_REQUEST['sub_qty']."' , sub_cost='".$_REQUEST['sub_cost']."', sub_pic_id='".$_REQUEST['sub_pic_id']."' WHERE sub_id='".$_REQUEST['sub_id']."' ");
		$_SESSION['sm'] = "Product Updated";
		session_write_close();
		header("location: index.php?do=news&action=subProds&date_id=".$_REQUEST['date_id']."");
		exit();

	}

	exit();
}










if($date['page_under'] > 0) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$uppage['date_cat']."' "); 
} else { 
	$dcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' "); 
}
?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<div id="pageTitle"><a href="index.php?do=<?php print $_REQUEST['do'];?>">Sections</a>  
<?php 
if(!empty($date['page_under'])) { 
	$uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");
	if($uppage['date_cat'] > 0) { 
		$date_cat = $uppage['date_cat'];
	}
}
if(!empty($date['date_cat'])) { 
	$date_cat = $date['date_cat'];
}
if(!empty($date_cat)) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date_cat."' ");
	if(!empty($cat['cat_under_ids'])) { 
		$scats = explode(",",$cat['cat_under_ids']);
		foreach($scats AS $scat) { 
			$tcat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$scat."' ");
			print " ".ai_sep." <a href=\"index.php?do=news&date_cat=".$tcat['cat_id']."\">".$tcat['cat_name']."</a> ";
		}
	}
	print " ".ai_sep." ";
	if(!empty($cat['cat_password'])) { print ai_lock." "; } 
	print "<a href=\"index.php?do=news&date_cat=".$cat['cat_id']."\">".$cat['cat_name']."</a>";
}
?>
<?php print ai_sep;?>  <?php if(!empty($date['page_under'])) {  $uppage = doSQL("ms_calendar", "*", "WHERE date_id='".$date['page_under']."' ");	?>
		<a href="index.php?do=news<?php if(empty($uppage['date_cat'])) { print "&date_cat=none"; } else { print "&date_cat=".$uppage['date_cat']; } ?>#dateid-<?php print $uppage['date_id'];?>"><?php print $uppage['date_title'];?></a> <?php print ai_sep;?>  
		<?php } ?>

 	<?php  if(!empty($_REQUEST['date_id'])) { ?>
		 <span>Editing:  <?php if($date['page_home'] == "1") { print "Home Page"; }  else { print $date['date_title']; } ?> </span>
	<?php  }  else { ?>
		 Creating New 
		 <?php if(!empty($_REQUEST['page_under'])) { 
		$udate = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['page_under']."' ");
		print "Under: ".$udate['date_title'];
	}
	?>
	<?php  } ?>
</div>
<?php include "news.tabs.php"; ?>
<div id="roundedFormContain">


<div class="info pc">
Sub products are use for selling items that may come with different options. A good example is if you were selling a t-shirt and that t-shirt comes in small, medium & large in red or blue. In this example, you would enter in the product configurations Size & Color. Then you can enter in the the products by the size and color.
</div>

<div style="width: 49%;" class="left">
<div class="underlinelabel"><h2>Products</h2></div>
<div class="underlinecolumn">
	<div>
	<div style="width: 10%;" class="left">&nbsp;</div>
	<div style="width: 10%;" class="left">&nbsp;</div>

	<div style="width: 10%;" class="left">ID</div>
	<div style="width: 10%;" class="left">Stock</div>
	<div style="width: 10%;" class="left">+Price</div>

			<?php if(!empty($date['prod_opt1'])) { ?>
				<div style="width: 10%;" class="left"><?php print $date['prod_opt1'];?></div>
			<?php } ?>
			<?php if(!empty($date['prod_opt2'])) { ?>
				<div style="width: 10%;" class="left"><?php print $date['prod_opt2'];?></div>
			<?php } ?>
			<?php if(!empty($date['prod_opt3'])) { ?>
				<div style="width: 10%;" class="left"><?php print $date['prod_opt3'];?></div>
			<?php } ?>
			<?php if(!empty($date['prod_opt4'])) { ?>
				<div style="width: 10%;" class="left"><?php print $date['prod_opt4'];?></div>
			<?php } ?>
			<?php if(!empty($date['prod_opt5'])) { ?>
				<div style="width: 10%;" class="left"><?php print $date['prod_opt5'];?></div>
			<?php } ?>
		<div class="clear"></div>
	</div>

<?php $subs = whileSQL("ms_product_subs", "*", "WHERE sub_main_prod='".$_REQUEST['date_id']."' ");
if(mysqli_num_rows($subs) <=0) { ?><div class="label center">No products added</div><?php } ?>
<?php
while($sub = mysqli_fetch_array($subs)) { ?>
	<div class="underline">
		<div style="width: 10%;" class="left"><a href="index.php?do=news&action=subProds&date_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><?php print ai_edit;?></a> 
		<a href="index.php?do=news&action=subProds&date_id=<?php print $_REQUEST['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&subdo=deleteSub" onClick="return confirm('Are you sure you want to delete this product option?');"><?php print ai_delete;?></a> 
		</div>
		<div style="width: 10%;" class="left">
		<?php if($sub['sub_pic_id'] > 0) { 
			$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$sub['sub_pic_id']."' ");
			if($pic['pic_id'] > 0) { ?>
				<img src="<?php tempFolder(); ?><?php print "/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];?>">		
	
		<?php 	}
		}
		?>
		&nbsp;</div>

		<div style="width: 10%;" class="left"><?php print $sub['sub_sku'];?> &nbsp;</div>
		<div style="width: 10%;" class="left"><?php print $sub['sub_qty'];?> &nbsp;</div>
		<div style="width: 10%;" class="left"><?php print $sub['sub_add_price'];?> &nbsp;</div>
		<?php if(!empty($date['prod_opt1'])) { ?>
			<div style="width: 10%;" class="left"><?php print $sub['opt1_value'];?> &nbsp;</div>
		<?php } ?>
		<?php if(!empty($date['prod_opt2'])) { ?>
			<div style="width: 10%;" class="left"><?php print $sub['opt2_value'];?> &nbsp;</div>
		<?php } ?>
		<?php if(!empty($date['prod_opt3'])) { ?>
			<div style="width: 10%;" class="left"><?php print $sub['opt3_value'];?> &nbsp;</div>
		<?php } ?>
		<?php if(!empty($date['prod_opt4'])) { ?>
			<div style="width: 10%;" class="left"><?php print $sub['opt4_value'];?> &nbsp;</div>
		<?php } ?>
		<?php if(!empty($date['prod_opt5'])) { ?>
			<div style="width: 10%;" class="left"><?php print $sub['opt5_value'];?> &nbsp;</div>
		<?php } ?>

		<div class="clear"></div>
	</div>

<?php } ?>

</div>


<div>&nbsp;</div>
<form name="configs" action="index.php" method="post">
<div id="roundedForm">
<div class="label">Product Configurations</div>
<div class="row">Enter in any configuration options you need to use. Example, if a size is to be selected (small, medium, large), enter in Size below and then you will be able to add sizes to the products.</div>
<div class="row">Option 1: <input type="text" name="prod_opt1" id="prod_opt1" value="<?php  print htmlspecialchars(stripslashes($date['prod_opt1']));?>"></div>
<div class="row">Option 2: <input type="text" name="prod_opt2" id="prod_opt2" value="<?php  print htmlspecialchars(stripslashes($date['prod_opt2']));?>"></div>
<div class="row">Option 3: <input type="text" name="prod_opt3" id="prod_opt3" value="<?php  print htmlspecialchars(stripslashes($date['prod_opt3']));?>"></div>
<div class="row">Option 4: <input type="text" name="prod_opt4" id="prod_opt4" value="<?php  print htmlspecialchars(stripslashes($date['prod_opt4']));?>"></div>
<div class="row">Option 5: <input type="text" name="prod_opt5" id="prod_opt5" value="<?php  print htmlspecialchars(stripslashes($date['prod_opt5']));?>"></div>
<div class="row center">
<input type="hidden" name="do" value="news">
<input type="hidden" name="action" value="subProds">
<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
<input type="hidden" name="subdo" value="prodConfig">
<input type="submit" name="submit" value="Update Configuration" class="submit" >
</div>
</div>
</form>
</div>


<div style="width: 49%;" class="right">
<?php
if((!empty($_REQUEST['sub_id']))AND(empty($_REQUEST['submitit']))==true) {
	$sub = doSQL("ms_product_subs", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' "); 
	if(empty($sub['sub_id'])) {
		showError("Sorry, but there seems to be an error.");
	}
	foreach($sub AS $id => $value) {
		if(!is_numeric($id)) {
			$_REQUEST[$id] = $value;
		}
	}
}
?>
<?php if(empty($date['prod_opt1'])) { ?>
<div class="pc"><h2>Create sub products / options</h2></div>
<div class="pc">To create new product options, first enter in an option or options in the Product Configurations section. Example: color, size, etc...<br><br>Once you add an option, you will be able to create the sub products.</div> 

<?php } ?>

<?php if(!empty($date['prod_opt1'])) { ?>


<div class="pc"><h3><?php 	if(empty($sub['sub_id'])) { ?>Add new product option<?php } else { ?>Edit Product Option<?php } ?></h3></div>


	<form method="post" name="addprods" action="index.php"   onSubmit="return checkForm();">
	<div id="roundedForm">
		<div class="row">
			<div style="width: 50%; float:left;">
			<div class="fieldLabel">Product ID / SKU</div>
			<div><input type="text" name="sub_sku" id="sub_sku" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['sub_sku']));?>"></div>
			</div>
			<div style="width: 50%; float:left;">

		<div class="fieldLabel">Additional Price</div>
		<div><input type="text" name="sub_add_price" id="sub_add_price" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['sub_add_price']));?>"></div>
		</div>
		<div class="clear"></div>
		</div>
		<div class="row">
		<!-- 
			<div style="width: 50%; float:left;">	
			<div class="fieldLabel">Cost</div>
			<div><input type="text" name="sub_cost" id="sub_cost" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['sub_cost']));?>"></div>
			</div>
		-->
			<div style="width: 50%; float:left;">	
				<div class="fieldLabel">Inventory</div>
				<div><input type="text" name="sub_qty" id="sub_qty" size="4" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['sub_qty']));?>"></div>
			</div>
			<div class="clear"></div>
		</div>

		<?php
		$spics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' " );
		if(mysqli_num_rows($spics) > 0) { ?>
		<div class="row">
			<div class="fieldLabel">Photo</div>
			<select name="sub_pic_id" id="sub_pic_id">
			<option value="">None</option>
			<?php while($spic = mysqli_fetch_array($spics)) { ?>
			<option value="<?php print $spic['pic_id'];?>" <?php if($spic['pic_id'] == $_REQUEST['sub_pic_id']) { print "selected"; } ?>><?php print $spic['pic_org'];?></option>
			<?php } ?>
			</select>
		</div>
		
		<?php  } ?>

		

		<?php if(!empty($date['prod_opt1'])) { ?>
		<div class="label"><?php print $date['prod_opt1'];?></div>
		<div class="row">
		<?php if(strtolower($date['prod_opt1']) == "size") { ?>
		<select name="opt1_value" id="opt1_value">
		<option value="">N/A</option>
		<option value="X-Small" <?php if($_REQUEST['opt1_value'] == "X-Small") { print "selected"; } ?>>X-Small</option>
		<option value="Small" <?php if($_REQUEST['opt1_value'] == "Small") { print "selected"; } ?>>Small</option>
		<option value="Medium" <?php if($_REQUEST['opt1_value'] == "Medium") { print "selected"; } ?>>Medium</option>
		<option value="Large" <?php if($_REQUEST['opt1_value'] == "Large") { print "selected"; } ?>>Large</option>
		<option value="X-Large" <?php if($_REQUEST['opt1_value'] == "X-Large") { print "selected"; } ?>>X-Large</option>
		<option value="XX-Large" <?php if($_REQUEST['opt1_value'] == "XX-Large") { print "selected"; } ?>>XX-Large</option>
		<option value="1XL" <?php if($_REQUEST['opt1_value'] == "1XL") { print "selected"; } ?>>1XL</option>
		<option value="2XL" <?php if($_REQUEST['opt1_value'] == "2XL") { print "selected"; } ?>>2XL</option>
		<option value="3XL" <?php if($_REQUEST['opt1_value'] == "3XL") { print "selected"; } ?>>3XL</option>
		</select>
		<?php } else { ?>
		<input type="text" name="opt1_value" id="opt1_value" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt1_value']));?>">
		<?php } ?>	
		</div>
		<?php } ?>


		<?php if(!empty($date['prod_opt2'])) { ?>
		<div class="label"><?php print $date['prod_opt2'];?></div>
		<div class="row">	<input type="text" name="opt2_value" id="opt2_value" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt2_value']));?>"></div>
		<?php } ?>


		<?php if(!empty($date['prod_opt3'])) { ?>
		<div class="label"><?php print $date['prod_opt3'];?></div>
		<div class="row">	<input type="text" name="opt3_value" id="opt3_value" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt3_value']));?>"></div>
		<?php } ?>


		<?php if(!empty($date['prod_opt4'])) { ?>
		<div class="label"><?php print $date['prod_opt4'];?></div>
		<div class="row">	<input type="text" name="opt4_value" id="opt4_value" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt4_value']));?>"></div>
		<?php } ?>


		<?php if(!empty($date['prod_opt5'])) { ?>
		<div class="label"><?php print $date['prod_opt5'];?></div>
		<div class="row">	<input type="text" name="opt5_value" id="opt5_value" value="<?php  print htmlspecialchars(stripslashes($_REQUEST['opt5_value']));?>"></div>
		<?php } ?>





	</div>
	<input type="hidden" name="date_id" value="<?php print $_REQUEST['date_id'];?>">
	<input type="hidden" name="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
	<input type="hidden" name="do" value="news">
	<input type="hidden" name="action" value="subProds">
	<input type="hidden" name="submitit" value="yes">
	<div class="pageContent center">
	<input type="submit" name="submit" value="<?php 	if(empty($sub['sub_id'])) { ?>Save New Product<?php } else { ?>Update Product<?php } ?>" class="submit" >
	<?php 	if(!empty($sub['sub_id'])) { ?><br><a href="index.php?do=news&action=subProds&date_id=<?php print $_REQUEST['date_id'];?>">Cancel</a><?php } ?>
	</div>

	</form>
<?php } ?>
</div>
<div class="clear"></div>

</div>
<div>&nbsp;</div>

