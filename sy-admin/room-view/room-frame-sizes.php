<?php 
$path = "../../";
require "../w-header.php"; 



$style = doSQL("ms_frame_styles", "*",  "WHERE style_id='".$_REQUEST['style_id']."' ");
// $img = doSQL("ms_frame_images", "*", "WHERE img_id='".$_REQUEST['img_id']."' ");

if($_REQUEST['submitit'] == "yes") { 
	if(countIt("ms_frame_sizes", "WHERE frame_style='".$_REQUEST['style_id']."' AND frame_default='1' ") <= 0) { 
		$def = "1";
	}
	$order = doSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['style_id']."' ORDER BY frame_order DESC ");
	$thisorder = $order['frame_order'] + 1;

	if($_REQUEST['frame_id'] > 0) { 
		updateSQL("ms_frame_sizes", "frame_width='".$_REQUEST['frame_width']."', frame_height='".$_REQUEST['frame_height']."', frame_style='".$_REQUEST['style_id']."', frame_price='".$_REQUEST['frame_price']."', frame_mattable='".$_REQUEST['frame_mattable']."', frame_mat_width='".$_REQUEST['frame_mat_width']."', frame_mat_price='".$_REQUEST['frame_mat_price']."', frame_mat_print_width='".$_REQUEST['frame_mat_print_width']."', frame_mat_print_height='".$_REQUEST['frame_mat_print_height']."', frame_shipable='".$_REQUEST['frame_shipable']."', frame_add_shipping='".$_REQUEST['frame_add_shipping']."' WHERE frame_id='".$_REQUEST['frame_id']."' ");
	} else { 
		insertSQL("ms_frame_sizes", "frame_width='".$_REQUEST['frame_width']."', frame_height='".$_REQUEST['frame_height']."', frame_style='".$_REQUEST['style_id']."', frame_price='".$_REQUEST['frame_price']."', frame_mattable='".$_REQUEST['frame_mattable']."', frame_mat_width='".$_REQUEST['frame_mat_width']."', frame_mat_price='".$_REQUEST['frame_mat_price']."', frame_mat_print_width='".$_REQUEST['frame_mat_print_width']."', frame_mat_print_height='".$_REQUEST['frame_mat_print_height']."', frame_default='".$def."', frame_shipable='".$_REQUEST['frame_shipable']."', frame_add_shipping='".$_REQUEST['frame_add_shipping']."', frame_order='".$thisorder."' ");
	}
	$_SESSION['sm'] = "Frame Size Saved";
}
if($_REQUEST['action'] == "deleteframesize") { 
	deleteSQL("ms_frame_sizes", "WHERE frame_id='".$_REQUEST['frame_id']."' ","1");
	exit();
}

if($_REQUEST['action'] == "defaultframe") { 
	updateSQL("ms_frame_sizes", "frame_default='0' WHERE frame_style='".$_REQUEST['style_id']."' ");
	updateSQL("ms_frame_sizes", "frame_default='1' WHERE frame_id='".$_REQUEST['frame_id']."' ");
}
?>

<script>
function savedata(classname) { 
	var fields = {};
	var stop = false;

	$(".optrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('requiredFieldEmpty');
			stop = true;
		} else { 
			$('#'+this_id).removeClass('requiredFieldEmpty');
		}
	} );


	if(stop == true) { 

	} else { 


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
				// alert($this.val());
			}

		});
			
			
		<?php if($setup['demo_mode'] !== true) { ?>
		$.post("room-view/room-frame-sizes.php", fields,	function (data) { 
			//  alert(data);
			// framesizes($("#style_id").val());
			window.location.href="index.php?do=photoprods&view=roomview&sub=frame&style_id="+$("#style_id").val();
			//showSuccessMessage("Saved");
		//	setTimeout(hideSuccessMessage,4000);
		//	$('#saveform').text("Save");
			//$('#saveform').removeClass("submitsaving").addClass("submit");

		});
		<?php } else {  ?>
			// framesizes($("#style_id").val());
			window.location.href="index.php?do=photoprods&view=roomview&sub=frame&style_id="+$("#style_id").val();
		//	showSuccessMessage("Saved");
		//	setTimeout(hideSuccessMessage,4000);
		//	$('#saveform').text("Save");
		//	$('#saveform').removeClass("submitsaving").addClass("submit");

		<?php } ?>
	}
}


function shownewframe() { 
	$("#newframe").slideToggle(200);
}
function deleteframesize(id) { 
	$.get("room-view/room-frame-sizes.php?action=deleteframesize&frame_id="+id, function(data) {
		framesizes($("#style_id").val());
	});
}

function framedefault(frame_id,style_id) { 
	$.get("room-view/room-frame-sizes.php?action=defaultframe&frame_id="+frame_id+"&style_id="+style_id, function(data) {
		showSuccessMessage("Default Frame Updated");
		setTimeout(hideSuccessMessage,4000);
	});
}

$(document).ready(function(){
	setTimeout(function(){ 
		$("#frame_width").focus().select();
	},200);
});
</script>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list','sort_order','orderFrameSizes');
	});
	</script>


	<form id="dd-form" action="index.php" method="post">
	<?php
	unset($order);
	$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$_REQUEST['style_id']."' ORDER BY frame_order ASC ");
	while($frame = mysqli_fetch_array($frames)) { 
		$order[] = $frame['frame_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>
	</form>

			
<div class="pc"><h3><?php if($_REQUEST['frame_id'] > 0) { ?>Edit Frame Size<?php } else { ?>Add New Frame Size<?php } ?></h3></div>

<?php if($_REQUEST['frame_id'] > 0) { 
	$frame = doSQL("ms_frame_sizes", "*", "WHERE frame_id='".$_REQUEST['frame_id']."' "); 
} else { 
	$frame['frame_mat_width'] = 0;
	$frame['frame_mat_price'] = "0.00";
	$frame['frame_mat_print_width'] = 0;
	$frame['frame_mat_print_height'] = 0;
	$frame['frame_add_shipping'] = "0.00";
}
?>

<div class="clear"></div>
<div>&nbsp;</div>

<div id="newframe" class="">
	<form method="post" name="famesizes" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   onSubmit="return checkForm('.optrequired');">

	<div class="underline">
		<div class="left p25">
			<div class="label">Width</div>
			<div><input type="text" name="frame_width" id="frame_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_width'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Height</div>
			<div><input type="text" name="frame_height" id="frame_height" size="2" class="center optrequired formfield" value="<?php print $frame['frame_height'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Price</div>
			<div><input type="text" name="frame_price" id="frame_price" size="4" class="center optrequired formfield" value="<?php print $frame['frame_price'] * 1;?>"></div>
		</div>
		<div class="clear"></div>
		<div>&nbsp;</div>
		<div class="left p25">
			<div class="label">Mat Width</div>
			<div><input type="text" name="frame_mat_width" id="frame_mat_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_width'] * 1;?>"></div>
		</div>

		<div class="left p25">
			<div class="label">Matted Print Width</div>
			<div><input type="text" name="frame_mat_print_width" id="frame_mat_print_width" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_print_width'] * 1;?>"></div>
		</div>
		<div class="left p25">
			<div class="label">Matted Print Height</div>
			<div><input type="text" name="frame_mat_print_height" id="frame_mat_print_height" size="2" class="center optrequired formfield" value="<?php print $frame['frame_mat_print_height'] * 1;?>"></div>
		</div>
		<div class="left p25">
			<div class="label">Matted Price</div>
			<div><input type="text" name="frame_mat_price" id="frame_mat_price" size="4" class="center optrequired formfield" value="<?php print ($frame['frame_mat_price'] * 1);?>"></div>
		</div>

		<div class="clear"></div>
	</div>
		<div>&nbsp;</div>

		<div class="left p25">
			<div><input type="checkbox" class="formfield" name="frame_shipable" id="frame_shipable"  value="1" <?php if($frame['frame_shipable'] == "1") { ?>checked<?php } ?>> <label for="frame_shipable">Eligible for shipping</label></div>
		</div>
		<div class="left p75">
			<div>If eligible for shipping, enter any additional shipping amount: <input type="text" name="frame_add_shipping" id="frame_add_shipping" size="2" class="center formfield" value="<?php print $frame['frame_add_shipping'];?>"></div>
		</div>
		<div class="clear"></div>

		<div>&nbsp;</div>


		<div class="pc center buttons">
		<?php if($setup['demo_mode'] == true) { ?>
			<a href="" onclick="return false;">Save Disabled For Demo</a>
		<?php } else { ?>
			<input type="hidden" name="style_id" id="style_id" class="formfield" value="<?php print $_REQUEST['style_id'];?>">
			<input type="hidden" name="submitit" id="submitit" class="formfield" value="yes">
			<input type="hidden" name="frame_id" id="frame_id" class="formfield" value="<?php print $frame['frame_id'];?>">
			<a href="" id="saveform" onclick="savedata('formfield'); return false;">Save</a>
			</div>
		<?php } ?>
		<?php if($_REQUEST['frame_id'] > 0) { ?>
		<div class="pc center"><a href="" onclick="closewindowedit(); return false;">cancel</a></div>
		<?php } ?>
		<div>&nbsp;</div>

	</form>

</div>



<?php require "../w-footer.php"; ?>
