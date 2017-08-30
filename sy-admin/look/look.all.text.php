
<div id="updatemessage"></div>
<div id="pageTitle"><a href="index.php?do=look">Site Design</a> <?php print ai_sep;?>  Page Text</div> 
<div class="pageContent" ><p>Below is the text in the database for the text on your pages that you don't actually add.</p>
<p>The muted text above the fields are the field names in the database. </p>
<p>Tip: To find a certain word, hold down CTRL on your keyboard and press "F" to search the page for a word.</p>
</div>
<div class="clear"></div>
<div style="width: 49%; float: left;">


<?php
$textareas = array(
	"_leave_comment_text_",
	"_leave_comment_success_message_",

	"_private_gallery_text_",
	"_checkout_card_declined_",
	"_store_find_order_text_",
	"_store_order_can_not_find_",
	"_store_order_pending_text_",
	"_store_order_service_",
	"_store_order_shipping_",
	"_store_order_completed_text_",
	"_store_cart_bottom_text_",
	"_new_account_success_message_",
	"_new_account_page_message_",
	"_create_an_account_message_",
	"_favorites_page_text_",
	"_log_in_text_",

	"_no_orders_found_",
	"_select_photos_for_product_message_",
	"_add_photo_product_instructions_",
	"_not_available_mobile_",
	"_coupon_expired_",
	"_coupon_used_",
	"_place_order_no_payment_text_",
	"_checkout_stop_package_incomplete_",
	"_remove_package_confirm_",
	"_remove_required_package_",
	"_package_added_instructions_",
	"_package_instructions_",
	"_expired_message_",
	"_store_order_bottom_text_",

	"pre_reg_message"

	);
$ignore = array(
	"lang_status",
	"lang_name",
	"lang_default",
	"_no_image_available_",
	"language",
	"lang_id",
	"_captcha_description_",
	"_captcha_not_correct_",
	"_captcha_background_color_",
	"_captcha_text_color_",
	"_cart_"
);
if(!empty($_REQUEST['q'])) { 
	$and_q .= " $fld LIKE '%".addslashes($_REQUEST['q'])."%' "; 
}

if(empty($_SESSION['pc_language'])) {
	$lang_id = "1";
} else {
	$lang_id = $_SESSION['pc_language'];
}
$lang = doSQL("ms_language", "*", "WHERE lang_id='1' ");
$x = 0;
?>
<div class="pageContent"><h3>Site Text</h3></div>
<?php 

foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		if(($id!=="id")AND($id!=="status")AND($id!=="default")AND(!in_array($id,$ignore))==true) {
			$fieldnum++;
	//		print "<li>".$id ." => ".$val;
			define($id,$val);
			$d['table_field'] = "$id";
			if(in_array($id,$textareas)) {
				$d['field_size'] = "5";
				$d['field_type'] = "textarea";
			} else {
				$d['field_size'] = "40";
				$d['field_type'] = "text";
			}
			$d['current_data'] = $lang[$d['table_field']];
			?>
			<div class="pageContent">
			<div style="padding: 2px 0;"><span  class="muted"><?php print "".$d['table_field'].""; ?></span></div>
			<div>
			<form method="post" name="form-<?php print $d['table_field'];?>" action="manage.php"   onSubmit="return submitPopupForm('admin.actions.php','field-<?php print $d['table_field'];?>');">
			<input type="hidden" name="action" value="updateText" id="action" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="field_name" value="<?php print $d['table_field'];?>" id="field_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="table_name" value="ms_language" id="table_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="id" value="<?php print $lang_id;?>" id="id" class="field-<?php print $d['table_field'];?>">
			<div style="width: 80%; float: left">
				<?php 
			if(in_array($id,$textareas)) { ?>
			<textarea cols="30" rows="3" name="<?php print $d['table_field'];?>" id="<?php print $d['table_field'];?>" class="field-<?php print $d['table_field'];?> field100"  tabindex="<?php print $x++;?>"><?php print htmlspecialchars($d['current_data']);?></textarea>
			<?php } else { ?>
			<input type="text" size="30" name="<?php print $d['table_field'];?>" id="<?php print $d['table_field'];?>" class="field-<?php print $d['table_field'];?> field100" value="<?php print htmlspecialchars($d['current_data']);?>" tabindex="<?php print $x++;?>">
			<?php } ?>
			</div>
			<div style="width: 20%;" class="left">
			<input type="submit" name="submit" value="save" class="submit">
			</div>
			<div class="clear"></div>
			</form>
			</div>
			</div>
			<?php 
		}
	}
}
?>


</div>
<?php if($sytist_store == true) { ?>
<div style="width: 49%; float: right;">
<table width="100%" cellpadding=0 cellspacing=0 border=0   class=TFtable>

<?php 
if(empty($_SESSION['pc_language'])) {
	$lang_id = "1";
} else {
	$lang_id = $_SESSION['pc_language'];
}
$lang = doSQL("ms_store_language", "*", "WHERE id='1' ");
?>
<div class="pageContent"><h3>Store Text</h3></div>
<?php 

foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		if(($id!=="id")AND($id!=="status")AND($id!=="default")AND(!in_array($id,$ignore))==true) {
			$fieldnum++;
	//		print "<li>".$id ." => ".$val;
			define($id,$val);
			$d['table_field'] = "$id";
			if(in_array($id,$textareas)) {
				$d['field_size'] = "5";
				$d['field_type'] = "textarea";
			} else {
				$d['field_size'] = "40";
				$d['field_type'] = "text";
			}
			$d['current_data'] = $lang[$d['table_field']];
			?>
			<div class="pageContent">
			<div style="padding: 2px 0;"><span  class="muted"><?php print "".$d['table_field'].""; ?></span></div>
			<div>
			<form method="post" name="form-<?php print $d['table_field'];?>" action="manage.php"   onSubmit="return submitPopupForm('admin.actions.php','field-<?php print $d['table_field'];?>');">
			<input type="hidden" name="action" value="updateText" id="action" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="field_name" value="<?php print $d['table_field'];?>" id="field_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="table_name" value="ms_store_language" id="table_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="id" value="<?php print $lang_id;?>" id="id" class="field-<?php print $d['table_field'];?>">
			<div style="width: 80%; float: left">
				<?php 
			if(in_array($id,$textareas)) { ?>
			<textarea cols="30" rows="3" name="<?php print $d['table_field'];?>" id="<?php print $d['table_field'];?>" class="field-<?php print $d['table_field'];?> field100"  tabindex="<?php print $x++;?>"><?php print htmlspecialchars($d['current_data']);?></textarea>
			<?php } else { ?>
			<input type="text" size="30" name="<?php print $d['table_field'];?>" id="<?php print $d['table_field'];?>" class="field-<?php print $d['table_field'];?> field100" value="<?php print htmlspecialchars($d['current_data']);?>" tabindex="<?php print $x++;?>">
			<?php } ?>
			</div>
			<div style="width: 20%;" class="left">
			<input type="submit" name="submit" value="save" class="submit">
			</div>
			<div class="clear"></div>
			</form>
			</div>
			</div>
			<?php 
		}
	}
}
?>


</div>
<?php } ?>
<div class="clear"></div>



<script >
function submitPopupForm(file,classname) { 
	var fields = {};
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}
	});
	$.post(file, fields,	function (data) { 
		$("#updatemessage").html(data);
		showSuccessMessage('Text updated');
 		setTimeout(hideSuccessMessage,3000);

	 } );
	return false;
}

</script>