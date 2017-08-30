<?php 
$path = "../../";
require "../w-header.php"; ?>
<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#pc_name").focus();
 }
$('#pc_code').change(function() {
	checkpccode();
});

function checkpccode() { 
	$.get("admin.actions.php?action=pccode&pc_code="+$("#pc_code").val()+"&pc_id="+$("#pc_id").val()+"", function(data) {
		if(data == "exists") { 
			alert("The redeem code "+$("#pc_code").val()+" is already being used for another print credit. Please enter a different redeem code.");
			$("#pc_code").val("")
		}
	});
}

</script>
<?php
$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$_REQUEST['list_id']."' ");

if($_POST['submitit']=="yes") { 
	print "<pre>"; print_r($_POST); 
	deleteSQL2("ms_image_options", "WHERE opt_list='".$_REQUEST['list_id']."' ");

	foreach($_REQUEST['opt_name'] AS $id => $opt) {
		$thisCount++;
		print "<li>$opt - $id - ".$_REQUEST['opt_price'][$id]."";
		if(!empty($opt)) {
			$in = insertSQL("ms_image_options", "opt_name='".addslashes(stripslashes($opt))."', opt_price='".$_REQUEST['opt_price'][$id]."', opt_taxable='".$_REQUEST['opt_taxable'][$id]."', opt_descr='".addslashes(stripslashes($_REQUEST['opt_descr'][$id]))."', opt_list='".$_REQUEST['list_id']."', opt_downloads='".$_REQUEST['opt_downloads'][$id]."', opt_discountable='".$_REQUEST['opt_discountable'][$id]."'  ");
		}
	}

	$_SESSION['sm'] = "Image options for ".$list['list_name']." updated";
	header("location: ../index.php?do=photoprods&view=list&list_id=".$_REQUEST['list_id']."");
	session_write_close();
	exit();
}
?>



	<div class="pc"><h1>Image Options for <?php print $list['list_name'];?></h1></div>
	<div class="pc">Image options are options that are <b>charged once per image</b>, no matter how many prints are purchased. Example use would be touch up fees. These are designed to be used on prints. If you also want to offer these options on downloads, select the offer on download option, otherwise they won't be presented on download products. </div>



	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<?php
	$opts = whileSQL("ms_image_options", "*", "WHERE opt_list='".$list['list_id']."' ORDER BY opt_id ASC ");

	$lines = mysqli_num_rows($opts);
	if($lines <=0) { 
		$lines = 3;
	} else { 
		$lines = $lines + 3;
	}
	if($lines < 5) {
//		$lines = 3;
	}

	$ct = 1;
	while($opt = mysqli_fetch_array($opts)) { 
		$d['opt_name'][$ct] = $opt['opt_name'];
		$d['opt_price'][$ct] = $opt['opt_price'];
		$d['opt_taxable'][$ct] = $opt['opt_taxable'];
		$d['opt_downloads'][$ct] = $opt['opt_downloads'];
		$d['opt_discountable'][$ct] = $opt['opt_discountable'];
		$d['opt_descr'][$ct] = $opt['opt_descr'];
		$ct++;
	}
	$ct = 1;

	while($ct<=$lines) { ?>
	<div class="underline">
		<div style="float: left; width: 60%;">
			<div style="padding-right: 24px;">
			<div>Option Name</div>
			<div><input type="text" name="opt_name[<?php print $ct;?>]" size="20" class="field100 inputtitle" value="<?php print $d['opt_name'][$ct];?>"></div>
			</div>
		</div> 
		
		<div style="float: right; text-align: right; width: 40%;">
			<div>Price</div>
			<div><input type="text" name="opt_price[<?php print $ct;?>]" size="8" value="<?php print $d['opt_price'][$ct];?>" class="inputtitle" style="text-align: right !important;"></div>
		</div> 


		<div class="clear"></div>
		<div>
				<div style="float: left; margin-right: 32px;">
			<div>&nbsp;</div>
			<div><input type="checkbox" name="opt_taxable[<?php print $ct;?>]" id="opt_taxable_<?php print $ct;?>"  value="1" <?php if($d['opt_taxable'][$ct] == "1") { print "checked"; } ;?>> <label for="opt_taxable_<?php print $ct;?>"> Taxable</label></div>
		</div> 

		<div style="float: left; margin-right: 32px;">
			<div>&nbsp;</div>
			<div><input type="checkbox" name="opt_downloads[<?php print $ct;?>]" id="opt_downloads_<?php print $ct;?>"  value="1" <?php if($d['opt_downloads'][$ct] == "1") { print "checked"; } ;?>> <label for="opt_downloads_<?php print $ct;?>"> Offer on downloads</label></div>
		</div> 

		<div style="float: left;margin-right: 32px;">
			<div>&nbsp;</div>
			<div><input type="checkbox" name="opt_discountable[<?php print $ct;?>]" id="opt_discountable_<?php print $ct;?>"  value="1" <?php if($d['opt_discountable'][$ct] == "1") { print "checked"; } ;?>> <label for="opt_discountable_<?php print $ct;?>"> Discountable</label></div>
		</div> 
		<div class="clear"></div>
		</div>
		<div>&nbsp;</div>
		<div>Description</div>
		<div><textarea name="opt_descr[<?php print $ct;?>]" rows="2" cols="20" class="field100"><?php print $d['opt_descr'][$ct];?></textarea></div>
	
	</div>
	<?php 
	$ct++;	
	} ?>

	<div class="underline center">
	<input type="hidden" name="list_id" id="list_id" value="<?php print $list['list_id'];?>">
	<input type="hidden" name="submitit" id="submitit" value="yes">
	<input type="submit" name="submit" value="Save" class="submit" id="submitButton">

	</div>
	</form>

<?php require "../w-footer.php"; ?>
