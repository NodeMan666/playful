<script>

function savedata(classname) { 
	var fields = {};
	var stop = false;
	$('#saveform').text("saving...");
	$('#saveform').removeClass("submit").addClass("submitsaving");
	$('.'+classname).each(function(){
		var $this = $(this);
		if( $this.attr('type') == "radio") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.val(); 
//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			}
		} else if($this.attr('type') == "checkbox") { 
			if($this.attr("checked")) { 
				fields[$this.attr('name')] = $this.attr("value"); 
				// alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
			} else { 
				fields[$this.attr('name')] = "";
			}
			
		} else { 
			fields[$this.attr('name')] = $this.val(); 
		}

	});
		
		
	fields['do'] = "news";
	fields['action'] = "customfields";

	
	$.post("index.php", fields,	function (data) { 
		alert($("#datasave").html());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save Changes");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	});
}

</script>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>
<?php adminsessionCheck(); ?>

<script>
show_id = '<?php print $show['show_id'];?>';
enabled = '<?php print $show['enabled'];?>';
date_id = '<?php print $date['date_id'];?>';
cat_id = '<?php print $cat['cat_id'];?>';

$(document).ready(function(){
	checksideitems();

	$(".color").each(function() {
		this_id = $(this).attr("id");
		thiscolor = $(this).attr("thisval");
		var myPicker = new jscolor.color(document.getElementById(this_id), {})
//		myPicker.fromString("000000")  // now you can access API via 'myPicker' variable
	});
	$(".sweetstatus").hide();

	if(show_id > 0 && enabled == '1') { 
		$("#sweet-"+date_id+"-on").show();
	}
	if(show_id > 0 && enabled == '0') { 
		$("#sweet-"+date_id+"-off").show();
	}

	$(".formfield").change(function() { 
		checksideitems();
	});

});

function checksideitems() { 
	if($("#enable_side").attr("checked")) { 
		$(".sideitems").show();
	} else { 
		$(".sideitems").hide();
	}
	if($("#feature_type").val() == "getfeatureslide") {
		$(".homecats").hide();
	} else { 
		$(".homecats").show();
	}
}
</script>

<div class="pc left"><h1>Custom Fields</h1></div>

<div class="clear"></div>
<div class="pc"></div>
<div id="datasave">
<?php 
if($_REQUEST['save'] == "yes") { 
print "SAVING";
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}
	if($_REQUEST['field_id'] > 0) { 
		print "UPDATING ::::::::::::::::::::::::: ";
		$show_id = $_REQUEST['show_id'];
		updateSQL("ms_custom_fields", "
		field_name='".$_REQUEST['field_name']."',
		field_type='".$_REQUEST['field_type']."'

		WHERE show_id='".$_REQUEST['show_id']."' ");


	} else { 
		$field_data_id = strtolower($_REQUEST['field_name']);
		$field_data_id = str_replace("'","",$field_data_id);
		$field_data_id = str_replace('"',"",$field_data_id);
		$field_data_id = str_replace(" ","_",$field_data_id);

		print "INSERTING :::::::::::::::: ";
		insertSQL("ms_custom_fields", "
		field_name='".$_REQUEST['field_name']."',
		field_type='".$_REQUEST['field_type']."',
		field_data_id='".$field_data_id."'
		 ");
	}

	exit();
	
	
	?>

<?php  } ?>
</div>


<div style="width: 50%; float: left;">
	<div style="padding: 16px;">
	<div class="underlinelabel">Custom Fields</div>
	<?php $fields = whileSQL("ms_custom_fields", "*","ORDER BY field_name ASC ");
	while($field = mysqli_fetch_array($fields)) { ?>
	<div class="underline">
		<div class="left p40"><?php print $field['field_name'];?></div>
		<div class="left p40">&lt?php customField('<?php print $field['field_data_id'];?>'); ?></div>
		<div class="clear"></div>
		</div>
	<?php }?>
	
	</div>
</div>
<div style="width: 50%; float: left;">
	<div style="padding: 16px;">
	<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"   onSubmit="return checkForm();">
	<input type="hidden" name="feat_page_id" id="feat_page_id" value="<?php print $date['date_id'];?>" class="formfield">
	<input type="hidden" name="feat_cat_id" id="feat_cat_id" value="<?php print $cat['cat_id'];?>" class="formfield">
	<input type="hidden" name="show_id" id="show_id" value="<?php print $show['show_id'];?>" class="formfield">
	<input type="hidden" name="save" id="save" value="yes" class="formfield">
	<div class="underlinelabel">Create New Field</div>

	<div class="underline">
		<div class="label">Field Name</div>
		<div>
		<input type="text"  size="15" name="field_name" id="field_name" value="<?php print $f['field_name'];?>" class="formfield"">
		</div> 
	</div>

	<div class="pc buttons">
	<a href="" id="saveform" onclick="savedata('formfield'); return false;">Save Changes</a>
	</div>
	<div class="clear"></div>

</form>

	</div>


</div>


<div class="clear"></div>

