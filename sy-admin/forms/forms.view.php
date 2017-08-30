<?php
$form = doSQL("ms_forms", "*", "WHERE form_id='".$_REQUEST['form_id']."' ");

if($_REQUEST['subdo'] == "deleteFF") { 
	$ff = doSQL("ms_form_fields", "*", "WHERE ff_id='".$_REQUEST['ff_id']."' ");
	deleteSQL("ms_form_fields", "WHERE ff_id='".$_REQUEST['ff_id']."' ", "1");
	$_SESSION['sm'] = "Form field deleted";
	header("location: index.php?do=forms&action=viewForm&form_id=".$ff['ff_form']."");
	session_write_close();
	exit();
}


if($_REQUEST['subdo'] == "orderFields") {	
	foreach($_REQUEST['ff_order'] AS $id => $val) {
		updateSQL("ms_form_fields", "ff_order='$val' WHERE ff_id='$id'");
//		print "<li>$id = $val";
	}
	$_SESSION['sm'] = "Field display order updated";
	session_write_close();
	header("location: index.php?do=forms&action=viewForm&form_id=".$_REQUEST['form_id']."");
	exit();

}

?>
<script> 
$(document).ready(function(){
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'DD, MM d , yy' });
	});
	$(".showeditdelete").hover(function() { 
		$(this).find('.editdelete').show();
	}, function(){
		$(this).find('.editdelete').hide();

	});

});
</script>
<div id="pageTitle" class="left"><a href="index.php?do=forms">Forms</a> <?php print ai_sep;?> <?php print $form['form_name'];?></div>
<div class="right buttons textright"><br><a href="" onclick="editform('<?php print $form['form_id'];?>'); return false;">Edit Form</a> 

<a href="index.php?do=forms&action=deleteForm&form_id=<?php print $form['form_id'];?>"  onClick="return confirm('Are you sure you want to delete the form <?php print $form['form_name'];?>? ');">Delete Form</a> 
<a href="" onclick="editfield('<?php print $form['form_id'];?>'); return false;">Add Form Field</a></div>
<div class="clear"></div>


<div id="info">Form resuts are emailed to: <?php print $form['form_email_to'];?><br>
<?php if(!empty($form['form_success_url'])) { ?>
Redirects to <?php print $form['form_success_url'];?> on submit<?php } else { ?>
Success message: <?php print $form['form_end_message'];?>
<?php } ?>
<br><br>
<?php
	$formDisplay = "none";
	$formLinkDisplay = "block";
	$mainformDisplay = "none";
	$mainformLinkDisplay = "block";

?>







<?php
$total = countIt("ms_form_fields", "WHERE ff_id>'0' AND ff_form='".$form['form_id']."' ORDER BY ff_order ASC "); 
if($total <= 0) { ?>
		<div class="pc center"><h3>You haven't added any form fields yet. Click the <a href="" onclick="editfield('<?php print $form['form_id'];?>'); return false;">Add Form Field</a> button to add a new form field.</div>
<?php } else { ?>
	<div class="pc center">Mouse over the form fields to edit / delete. Drag & drop the field names to change the display order.</div>
	<?php 	
	if(empty($_REQUEST['pg'])) {
		$pg = "1";
	} else {
		$pg = $_REQUEST['pg'];
	}

	$per_page = 40;
	$NPvars = array("do=forms", "orderq=".$_REQUEST['orderq']."" );
	$sq_page = $pg * $per_page - $per_page;	
	?>
	<?php
	// This determines the size of the columns 
	$cw1 = "10%";
	$cw2 = "20%";
	$cw3 = "30%";
	$cw4 = "30%";
	$cw5 = "17%";
	$cw6 = "30%";
	$cw7 = "30%";
	?>





<div id="message-box" class="pageContent"><?php echo $message; ?></div>
	<script>
	jQuery(document).ready(function() {
		sortItems('sortable-list','sort_order','orderFormFields');
		oldval = $("#sort_order").val();
		changed = false;
		setInterval(function() { orderchanged()}, 100);
	});


	function orderchanged() { 
		if($("#sort_order").val() !== oldval) { 
			if(changed == false) { 
				refreshform();
				changed = true;
			}
		}
	}
	function refreshform() { 
		// alert("hi");
		window.location.href="index.php?do=forms&action=viewForm&form_id=<?php print $_REQUEST['form_id'];?>";
	}

	</script>
	<form id="dd-form" action="admin.action.php" method="post">
	<input type="hidden" name="action" value="orderFormFields">
	<?php
	unset($order);
		$datas = whileSQL("ms_form_fields", "*", "WHERE ff_id>'0' AND ff_form='".$form['form_id']."' ORDER BY ff_order ASC  ");
		while ($data = mysqli_fetch_array($datas)) {
		$order[] = $data['ff_id'];
	}
	?>
	<input type="hidden" name="sort_order" id="sort_order" value="<?php if(is_array($order)) { echo implode(',',$order);} ?>"/>
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>

	
	<div> 


		<?php 
			
		$cols = $form['form_cols']; 
		if($cols < 1) { $cols = 1; } 
		$x = 1;
		if($form['form_max_width'] <=0) { 
			$form['form_max_width'] = 800;
		}
		?>

		<ul id="sortable-list" class="sortable-list" style="width: 100%; max-width: <?php print $form['form_max_width'];?>px; margin: auto;">

		<?php
		$datas = whileSQL("ms_form_fields", "*", "WHERE ff_id>'0' AND ff_form='".$form['form_id']."' ORDER BY ff_order ASC  ");
		while ($data = mysqli_fetch_array($datas)) {
			$rownum++;
			?>
			<?php if($data['ff_span_across'] == "1") { 
				$x = 2; ?>
			<li class="clear" title="clear">&nbsp;</li>
			<li title="<?php print $data['ff_id'];?>" style="width: 100%;">
			<?php } else { ?>
			<li title="<?php print $data['ff_id'];?>"  style="<?php if($cols == "2") { print "width: 50%; float: left;"; } else { print "width: auto;"; } ?>">
			<?php } ?>


			<div class="pc showeditdelete">
				<div class="pc">
					<span class="editdelete hidden"><a href="" onClick="editfield('<?php print $form['form_id'];?>', '<?php print $data['ff_id'];?>'); return false;"><?php print ai_edit;?></a> 
					<a href="index.php?do=forms&action=viewForm&subdo=deleteFF&ff_id=<?php print $data['ff_id'];?>"  onClick="return confirm('Are you sure you want to delete this form field <?php print $data['ff_name'];?>? ');"><?php print ai_delete;?></a> </span>


			<?php if($data['ff_type'] == "checkbox") { 
						print "<input type=\"checkbox\" name=\"".$form['form_id']."-".$data['ff_id']."\"> ";
					} ?>

					<span style="cursor: move;"><b><?php print $data['ff_name']; ?></b></span>
				</div>
				<?php  if($data['ff_type'] !== "checkbox") { ?>
				<div class="pc"><?php print showFormField($form,$data);?>&nbsp;</div>
				<?php } ?>
				<?php if(!empty($data['ff_descr'])) { ?><div class="pc"><?php print nl2br($data['ff_descr']);?></div><?php } ?>
			</div>
		</li>

		<?php 
		if($x == $cols) {
			print "<li class=\"clear\" title=\"clear\"></li>";
			$x = 0;
		}
		$x++;
		}
		?>
		</ul>
		</form>
		</div>		
		<div class="clear"></div>
		<div>&nbsp;</div>
		<div class="pc center"><input type="submit" name="submit" class="submit" onclick="alert('This would send the form'); return false;" value="<?php print $form['form_button'];?>"></div>
<?php } ?>





		<?php
		function showFormField($form,$data) {
			if($data['ff_type'] == "text") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" value=\"".$_REQUEST[$form['form_id']."-".$data['ff_id']]."\" size=\"".$data['ff_size']."\"  style=\"width: 100%; max-width:".($data['ff_size'] * 10)."px;\">";
			}
			if($data['ff_type'] == "email") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" value=\"".$_REQUEST[$form['form_id']."-".$data['ff_id']]."\" size=\"".$data['ff_size']."\"  style=\"width: 100%; max-width:".($data['ff_size'] * 10)."px;\">";
			}

			if($data['ff_type'] == "textarea") { 
				$html .= "<textarea name=\"".$form['form_id']."-".$data['ff_id']."\"  rows=\"".$data['ff_rows']."\" cols=\"".$data['ff_cols']."\"  style=\"width: 100%; max-width:".($data['ff_cols'] * 10)."px;\">".$_REQUEST[$form['form_id']."-".$data['ff_id']]."</textarea>";
			}
			if($data['ff_type'] == "dropdown") { 
				$html .= "<select  name=\"".$form['form_id']."-".$data['ff_id']."\">";
				$html .= "<option>".$data['ff_label']."</option>";
				$opts = explode("\r\n", $data['ff_opts']);
				foreach($opts AS $option) { 
					$html .= "<option value='$option'>$option</optoin>";
				}
				$html .= "</select>";
			}
			if($data['ff_type'] == "radio") { 
				$opts = explode("\r\n", $data['ff_opts']);
				foreach($opts AS $option) { 
					$html .= "<input type=\"radio\" name=\"".$form['form_id']."-".$data['ff_id']."\"  value='$option'> $option &nbsp; &nbsp; ";
				}
			}

			if($data['ff_type'] == "date") { 
				$html .= "<input type=\"text\" name=\"".$form['form_id']."-".$data['ff_id']."\" id=\"".$form['form_id']."-".$data['ff_id']."\"  value=\"".date('l, F d, Y')."\" size=\"24\" class=\""; if($data['ff_required'] == "1") { $html .= "required"; } $html .= " datepicker\">";
			}


			return $html;
		}
		?>

