<?php 
$path = "../../";
require "../w-header.php"; ?>
<?php
if($_POST['submitit']=="yes") { 
	if($_REQUEST['ff_id'] > 0) { 

		updateSQL("ms_form_fields", "
		ff_name='".addslashes(stripslashes($_REQUEST['ff_name']))."' , 
		ff_form='".addslashes(stripslashes($_REQUEST['ff_form']))."' , 
		ff_type='".addslashes(stripslashes($_REQUEST['ff_type']))."' , 
		ff_email='".addslashes(stripslashes($_REQUEST['ff_email']))."' , 
		ff_required='".addslashes(stripslashes($_REQUEST['ff_required']))."' , 
		ff_descr='".addslashes(stripslashes($_REQUEST['ff_descr']))."' , 
		ff_size='".addslashes(stripslashes($_REQUEST['ff_size']))."' , 
		ff_rows='".addslashes(stripslashes($_REQUEST['ff_rows']))."' , 
		ff_cols='".addslashes(stripslashes($_REQUEST['ff_cols']))."' , 
		ff_opts='".addslashes(stripslashes($_REQUEST['ff_opts']))."' , 
		ff_label='".addslashes(stripslashes($_REQUEST['ff_label']))."' ,
		ff_span_across='".$_REQUEST['ff_span_across']."'
		WHERE ff_id='".$_REQUEST['ff_id']."' ");
		$_SESSION['sm'] = "Field Saved";
		
	} else {
		$ff_id = insertSQL("ms_form_fields", "
		ff_name='".addslashes(stripslashes($_REQUEST['ff_name']))."' , 
		ff_form='".addslashes(stripslashes($_REQUEST['ff_form']))."' , 
		ff_type='".addslashes(stripslashes($_REQUEST['ff_type']))."' , 
		ff_email='".addslashes(stripslashes($_REQUEST['ff_email']))."' , 
		ff_required='".addslashes(stripslashes($_REQUEST['ff_required']))."' , 
		ff_descr='".addslashes(stripslashes($_REQUEST['ff_descr']))."' , 
		ff_size='".addslashes(stripslashes($_REQUEST['ff_size']))."' , 
		ff_rows='".addslashes(stripslashes($_REQUEST['ff_rows']))."' , 
		ff_cols='".addslashes(stripslashes($_REQUEST['ff_cols']))."' , 
		ff_opts='".addslashes(stripslashes($_REQUEST['ff_opts']))."' , 
		ff_label='".addslashes(stripslashes($_REQUEST['ff_label']))."' ,
		ff_span_across='".$_REQUEST['ff_span_across']."'

		");
		$_SESSION['sm'] = "Field Created";

	}
	header("location: ../index.php?do=forms&form_id=".$_REQUEST['ff_form']."&action=viewForm");
	session_write_close();
	exit();
}
?>


<?php 
	if($_REQUEST['ff_id'] > 0) { 
		$field = doSQL("ms_form_fields", "*", "WHERE ff_id='".$_REQUEST['ff_id']."' ");
	} else { 
		$field['ff_size'] = "40";
		$field['ff_cols'] = "40";
		$field['ff_rows'] = "4";
		$field['ff_required'] = "1";
	}
	$form = doSQL("ms_forms", "*", "WHERE form_id='".$_REQUEST['ff_form']."' ");
	?>

	<div class="pc"><?php if(empty($field['ff_id'])) { ?><h1>Create Form Field For <?php print $form['form_name'];?></h1><?php } else { ?><h1>Edit Form Field For <?php print $form['form_name'];?></h1><?php } ?>


	
	<script>
	function selectfieldtype() { 
		$(".fieldoptions").hide();
		$(".fieldopt").removeClass("optrequired").removeClass("requiredFieldEmpty");
		$(".f_"+$("#ff_type").val()).addClass("optrequired");

		$("."+$("#ff_type").val()).show();
	}
	 $(document).ready(function(){
		selectfieldtype();
	 });

	</script>
	<form name="editoptionform" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">



	<div class="underline">
		<div style="width: 48%; float: left;">
		<div class="label">Select what type of field</div>
		<div>
		<select name="ff_type" id="ff_type" onchange="selectfieldtype();">
		<option value="text" <?php if($field['ff_type'] == "text") { print "selected"; } ?>>Text Field</option>
		<option value="email" <?php if($field['ff_type'] == "email") { print "selected"; } ?>>Email Address</option>
		<option value="textarea" <?php if($field['ff_type'] == "textarea") { print "selected"; } ?>>Text Area</option>
		<option value="dropdown" <?php if($field['ff_type'] == "dropdown") { print "selected"; } ?>>Drop Down Menu</option>
		<option value="radio" <?php if($field['ff_type'] == "radio") { print "selected"; } ?>>Radio Button</option>
		<option value="checkbox" <?php if($field['ff_type'] == "checkbox") { print "selected"; } ?>>Check Box</option>
		<option value="date" <?php if($field['ff_type'] == "date") { print "selected"; } ?>>Date</option>
		</select>
		</div>
	</div>

	<div style="width: 48%; float: right;">
	<div>&nbsp;</div>
		<div class="label"><input type="checkbox" name="ff_required" id="ff_required" <?php if($field['ff_required'] == "1") { print "checked"; } ?> value="1"> This is a required field</div>
	</div>

<div class="clear"></div>
</div>

	<div>
		<div style="width: 48%; float: left;">
			<div class="underline">
				<div class="label">Field Name</div>
				<div><input type="text" name="ff_name" id="ff_name" class="optrequired field100" value="<?php print $field['ff_name'];?>" size="8" tabindex="1"></div>
			</div>
		</div>

		<div style="width: 48%; float: right;">
			<div class="underline text email fieldoptions" >

				<div class="label">Field Size</div>
				<div><input type="text" name="ff_size" id="ff_size"  value="<?php print $field['ff_size'];?>" size="4" tabindex="2" class="fieldopt f_email f_text"></div>
			
			</div>
		</div>

		<div style="width: 48%; float: right;">
			<div class="underline textarea fieldoptions" >

				<div class="label">Text Area Size</div>
				<div>Size: <input type="text" name="ff_cols" id="ff_cols"  value="<?php print $field['ff_cols'];?>" size="4" tabindex="2"> &nbsp; Rows: <input type="text" name="ff_rows" id="ff_rows"  value="<?php print $field['ff_rows'];?>" size="4" tabindex="2"></div>
			
			</div>
		</div>

	<div class="clear"></div>
	</div>




	<div>
		<div style="width: 48%; float: left;">

			<div class="underline fieldoptions dropdown">
				<div class="label">Select Label</div>
				<div><input type="text" name="ff_label" id="ff_label" class="optrequired field100 fieldopt f_dropdown" value="<?php print $field['ff_label'];?>" size="8" tabindex="1"></div>
				<div>This is the text on the drop down menu when an option hasn't been select. Enter something like "Please Select".</div>
			</div>


			<div class="underline fieldoptions dropdown radio">
				<div class="label">Options</div>
				<div><textarea name="ff_opts" id="ff_opts" class="optrequired field100  fieldopt f_dropdown f_radio" rows="8"  size="30" tabindex="1"><?php print $field['ff_opts'];?></textarea></div>
				<div class="sub">Enter in the options to select from <b>1 per line</b>.</div>
			</div>
		</div>


 <!-- 

 -->
	<div class="clear"></div>
	</div>

	<div>&nbsp;</div>
	<div class="underline">
		<div class="label">Description</div>
		<div><textarea name="ff_descr" id="ff_descr" rows="2" class="field100"><?php print $field['ff_descr'];?></textarea></div>
	</div>
	<div class="underline">
		<div class="label"><input type="checkbox" name="ff_span_across" id="ff_span_across" value="1" <?php if($field['ff_span_across'] == "1") { print "checked"; } ?>> If using a 2 column layout, check this box to make this field span across both columns.</div>
	</div>





	<div class="pageContent center">

	<input type="hidden" name="ff_form" value="<?php print $_REQUEST['ff_form'];?>">
	<input type="hidden" name="ff_id" value="<?php print $_REQUEST['ff_id'];?>">
	<input type="hidden" name="submitit" value="yes">
	<input type="submit" name="submit" value="<?php 	if($_REQUEST['p_id'] > 0) { ?>Save Changes<?php } else { ?>Save<?php } ?>" class="submit" id="submitButton"  tabindex="10">
	<br><a href="" onclick="closewindowedit(); return false;">Cancel</a>
	</div>

	</form>
<?php require "../w-footer.php"; ?>