<?php 
$path = "../../../";
require "../../w-header.php"; ?>
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



</script>

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
		
		
	fields['slide_link'] = $("#slide_link").val();

	<?php if($setup['demo_mode'] !== true) { ?>
	$.post("sweetness.php", fields,	function (data) { 
		//  alert(data);
		sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	});
	<?php } else {  ?>
		sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
		showSuccessMessage("Saved");
		setTimeout(hideSuccessMessage,4000);
		$('#saveform').text("Save");
		$('#saveform').removeClass("submitsaving").addClass("submit");

	<?php } ?>
	}

</script>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>
<?php adminsessionCheck(); ?>
<?php 
if($_REQUEST['show_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE show_id='".$_REQUEST['show_id']."' "); 
}
if($_REQUEST['feat_page_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_page_id='".$_REQUEST['feat_page_id']."' "); 
}
if($_REQUEST['feat_cat_id'] > 0) { 
	$show = doSQL("ms_show", "*", "WHERE feat_cat_id='".$_REQUEST['feat_cat_id']."' "); 
}

if($show['feat_page_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$show['feat_page_id']."' ");
}
if($show['feat_cat_id'] > 0) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$show['feat_cat_id']."' ");
}

if($_REQUEST['feat_page_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['feat_page_id']."' ");
}
if($_REQUEST['feat_cat_id'] > 0) { 
	$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$_REQUEST['feat_cat_id']."' ");
}


if(!empty($date['date_title'])) { 
	$for = $date['date_title'];
}
if(!empty($cat['cat_name'])) { 
	$for = $cat['cat_name'];
}


?>
<script>
show_id = '<?php print $show['show_id'];?>';
enabled = '<?php print $show['enabled'];?>';
date_id = '<?php print $pp['pp_id'];?>';
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
		$("#show_page_text_div").show();
	} else { 
		$(".homecats").show();
		$("#show_page_text_div").hide();
	}
}
</script>

<div class="pc"><h1>Batch Edit Product Prices</h1></div>


<?php 
if($_REQUEST['action'] == "save") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}


	$items = explode("|", $_POST['prodids']);
	foreach($items AS $item) {
		if($item > 0) { 
			$thisCount++;
			print "<li>$thisCount: ".$_REQUEST['date_date'][$item];
			updateSQL("ms_photo_products", "pp_price='".$_REQUEST['pp_price'][$item]."' WHERE pp_id='".$item."' "); 
		}
	}

	header("location: ../../index.php?do=photoprods&view=base");
	session_write_close();
	exit();
	
	
	?>


<?php  } ?>
<script>
 function selectedit(where) { 
	$("."+where).slideDown(100);
	$(".toaddtoall").slideDown(200);
}
function settoall(w) { 
	$(".data-"+w).val($("#"+w).val());
}
</script>


<?php 
$prodids = explode("|",$_REQUEST['prodids']);
if(count($prodids)<= 1) { ?>
<div class="center pc error">You didn't select any products to edit.</div>
<?php 
exit();
}
?>

<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"  >
<input type="hidden" name="prodids" id="prodids" value="<?php print $_REQUEST['prodids'];?>" class="formfield">
<input type="hidden" name="action" id="action" value="save" class="formfield">
<input type="hidden" name="noclose" id="noclose" value="1" class="formfield">
<input type="hidden" name="nofonts" id="nofonts" value="1" class="formfield">
<input type="hidden" name="nojs" id="nojs" value="1" class="formfield">


<div >
	<div style="padding: 16px;">
	<?php
	$prodids = explode("|",$_REQUEST['prodids']);
	foreach($prodids AS $id) { 
		if($id > 0) { 
			$pp = doSQL("ms_photo_products", "*", "WHERE pp_id='".$id."' ");
			$total++;
		?>



	<div>

	<div class="underline" >
		<div class="left"><h3><?php print $pp['pp_name']; ?></h3></div>

		<div class="right textright" >
			<div><input type="text" name="pp_price[<?php print $pp['pp_id'];?>]" value="<?php print $pp['pp_price'];?>" id="pp_id" class="inputtitle" size="12" ></div>
		</div>



		<div class="clear"></div>

	</div>
</div>
<?php }
} ?>




<script>
function confirmdeletepages() { 
	if($("#deleteall").attr("checked")) { 
		$("#confirmdelete").slideDown();
	} else { 
		$("#confirmdelete").slideUp();
	}
}
</script>

	</div>
</div>
<div class="clear"></div>
<div class="pc center"><input type="submit" name="submit" value="Save Changes" class="submit"></div>
</form>




<?php require "../../w-footer.php"; ?>
