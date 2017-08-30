<?php
$lang = doSQL("ms_wall_language", "*", " ");
$x = 0;
$textareas = array(
	"_wd_instructions_",
	"_wd_select_photos_error_text_",

	"_wd_review_your_photos_text_",
	"_wd_upload_room_photo_instructions_",
	"_wd_set_room_measurement_instructions_",
	"_wd_enter_width_or_height_"
	);
$ignore = array("_wd_frames_title_",
"_wd_frames_text_",
"_wd_canvases_title_",
"_wd_canvases_text_",
"_wd_collections_title_",
"_wd_collections_text_",
"_wd_wall_designer_tab_",
"_wd_wall_designer_text_",
"_wd_view_",
"_wd_instructions_",
"_wd_instructions_title_"
);
	
?>
<script >

function editwdtext() { 
	$("#wdtexts").slideToggle(200);
}
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
<?php

if($_REQUEST['submitit'] == "yes") { 
	updateSQL("ms_wall_language", "_wd_frames_title_='".addslashes(stripslashes(trim($_REQUEST['_wd_frames_title_'])))."',
	_wd_frames_text_='".addslashes(stripslashes(trim($_REQUEST['_wd_frames_text_'])))."',
	_wd_canvases_title_='".addslashes(stripslashes(trim($_REQUEST['_wd_canvases_title_'])))."',
	_wd_canvases_text_='".addslashes(stripslashes(trim($_REQUEST['_wd_canvases_text_'])))."', 
	_wd_collections_title_='".addslashes(stripslashes(trim($_REQUEST['_wd_collections_title_'])))."', 
	_wd_collections_text_='".addslashes(stripslashes(trim($_REQUEST['_wd_collections_text_'])))."', 
	_wd_wall_designer_tab_='".addslashes(stripslashes(trim($_REQUEST['_wd_wall_designer_tab_'])))."', 
	_wd_wall_designer_text_='".addslashes(stripslashes(trim($_REQUEST['_wd_wall_designer_text_'])))."',
	_wd_view_='".addslashes(stripslashes(trim($_REQUEST['_wd_view_'])))."', 
	_wd_instructions_title_='".addslashes(stripslashes(trim($_REQUEST['_wd_instructions_title_'])))."', 
	_wd_instructions_='".addslashes(stripslashes(trim($_REQUEST['_wd_instructions_'])))."'




	");
	$_SESSION['sm'] = "Settings Saved";
	header("location: index.php?do=photoprods&view=roomview&sub=language");
	session_write_close();
	exit();

}
?>
<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a>   <?php print ai_sep;?> Text / Language</div>

	<form method="post" name="famesizes" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">
<div class="left p50">
	<div style="padding: 16px;">
	<div class="underlinelabel">Frames Title & Text</div>
	<div class="underlinespacer">This is the title and text when someone selects frames..</div>
	<div class="pc" style="max-width: 400px;"><input type="text" name="_wd_frames_title_" id="_wd_frames_title_" class="field100 inputtitle"  value="<?php print $lang['_wd_frames_title_'];?>"></div>
	<div class="pc"  style="max-width: 400px;"><textarea name="_wd_frames_text_" id="_wd_frames_text_" class="field100"   rows="4"><?php print $lang['_wd_frames_text_'];?></textarea></div>
	</div>
</div>
<?php addEditor("_wd_frames_text_","1", "500", "0"); ?>

<div class="left p50">
	<div style="padding: 16px;">
	<div class="underlinelabel">Canvases Title & Text</div>
	<div class="underlinespacer">This is the title and text when someone selects canvases.</div>
	<div class="pc" style="max-width: 400px;"><input type="text" name="_wd_canvases_title_" id="_wd_canvases_title_" class="field100  inputtitle"  value="<?php print $lang['_wd_canvases_title_'];?>"></div>
	<div class="pc"  style="max-width: 400px;"><textarea name="_wd_canvases_text_" id="_wd_canvases_text_" class="field100"   rows="4"><?php print $lang['_wd_canvases_text_'];?></textarea></div>
	</div>
</div>
<?php addEditor("_wd_canvases_text_","1", "500", "0"); ?>
<div class="clear"></div>
<div class="left p50">
	<div style="padding: 16px;">
	<div class="underlinelabel">Collections Title & Text</div>
	<div class="underlinespacer">This is the title and text when someone selects collections.</div>
	<div class="pc" style="max-width: 400px;"><input type="text" name="_wd_collections_title_" id="_wd_collections_title_" class="field100  inputtitle"  value="<?php print $lang['_wd_collections_title_'];?>"></div>
	<div class="pc"  style="max-width: 400px;"><textarea name="_wd_collections_text_" id="_wd_collections_text_" class="field100"   rows="4"><?php print $lang['_wd_collections_text_'];?></textarea></div>
	</div>




<div >
	<div style="padding: 16px;">
	<div class="underlinelabel">Instructions</div>
	<div class="underlinespacer">This is show the first time using the wall designer and when clicking the help icon.</div>
	<div class="pc" style="max-width: 400px;"><input type="text" name="_wd_instructions_title_" id="_wd_instructions_title_" class="field100  inputtitle"  value="<?php print $lang['_wd_instructions_title_'];?>"></div>
	<div class="pc"  style="max-width: 400px;"><textarea name="_wd_instructions_" id="_wd_instructions_" class="field100"   rows="4"><?php print $lang['_wd_instructions_'];?></textarea></div>
	</div>
</div>
<?php addEditor("_wd_instructions_","1", "500", "0"); ?>

</div>
<?php addEditor("_wd_collections_text_","1", "500", "0"); ?>
<div class="left p50">
	<div style="padding: 16px;">
	<div class="underlinelabel">Wall Designer Tab & Description</div>
	<div class="underlinespacer">This is shown in the price list when wall designer is enabled</div>
	<div class="pc" style="max-width: 400px;"><input type="text" name="_wd_wall_designer_tab_" id="_wd_wall_designer_tab_" class="field100  inputtitle"  value="<?php print $lang['_wd_wall_designer_tab_'];?>"></div>
	<div class="pc"  style="max-width: 400px;"><textarea name="_wd_wall_designer_text_" id="_wd_wall_designer_text_" class="field100"   rows="4"><?php print $lang['_wd_wall_designer_text_'];?></textarea></div>
	<div class="pc center" style="max-width: 400px;">Button Text</div>
	<div class="pc center" style="max-width: 400px;"><input type="text" name="_wd_view_" id="_wd_view_" class=" inputtitle field100 center"  size="12"  value="<?php print $lang['_wd_view_'];?>"></div>
	</div>
</div>
<?php addEditor("_wd_wall_designer_text_","1", "500", "0"); ?>

<div class="clear"></div>





<div class="pc center">
	<input type="hidden" name="do" id="do" class="formfield" value="photoprods">
	<input type="hidden" name="submitit" id="submitit" class="formfield" value="yes">
	<input type="hidden" name="view" id="view" class="formfield" value="roomview">
	<input type="hidden" name="sub" id="sub" class="formfield" value="language">
	<input type="submit" name="submit" class="submit" value="Save">
</div>
</form>
<div>&nbsp;</div>
<div class="pc center"><a href="" onclick="editwdtext(); return false;">Edit additional text</a></div>
<div id="wdtexts" class="hide" style="max-width: 800px; margin: auto;">
<div class="pc center"><i>Note each fields below needs to be saved separately.</i></div>
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
			<input type="hidden" name="action" value="updateWallText" id="action" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="field_name" value="<?php print $d['table_field'];?>" id="field_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="table_name" value="ms_wall_language" id="table_name" class="field-<?php print $d['table_field'];?>">
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
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
