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
		$("#show_page_text_div").show();
	} else { 
		$(".homecats").show();
		$("#show_page_text_div").hide();
	}
}
</script>

<div class="pc"><h1>Batch Edit</h1></div>


<?php 
if(($_REQUEST['action'] == "save")&&($_REQUEST['deleteall'] == "1") ==true) { 
	$items = explode("|", $_POST['pageids']);
	foreach($items AS $item) {
		if($item > 0) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$item."' ");
			if($date['date_id'] > 0) { 
				doDeleteDateBatch($date);
			}
		}
	}
 header("location: ../index.php?do=news&date_cat=".$_REQUEST['date_cat']."");
	session_write_close();
	exit();
}


if($_REQUEST['action'] == "save") { 
	foreach($_REQUEST AS $id => $value) {
		if(!is_array($_REQUEST[$id])) { 
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}
	}


	$items = explode("|", $_POST['pageids']);
	foreach($items AS $item) {
		if($item > 0) { 
			$thisCount++;
			print "<li>$thisCount: ".$_REQUEST['date_date'][$item];
			updateSQL("ms_calendar", "date_date='".$_REQUEST['date_date'][$item]."', date_expire='".$_REQUEST['date_expire'][$item]."', date_photo_price_list='".$_REQUEST['date_photo_price_list'][$item]."', date_public='".$_REQUEST['date_public'][$item]."' WHERE date_id='".$item."' "); 
		}
	}

	header("location: ../index.php?do=news&date_cat=".$_REQUEST['date_cat']."");
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
$pageids = explode("|",$_REQUEST['pageids']);
if(count($pageids)<= 1) { ?>
<div class="center pc error">You didn't select any pages to edit.</div>
<?php 
exit();
}
?>
<div class="pc">Select what to edit:</div>
<div class="pc">
<a href="" onclick="selectedit('date_date'); return false;">Date</a> &nbsp;  &nbsp; 
<a href="" onclick="selectedit('date_expire'); return false;">Expiration Date</a> &nbsp;  &nbsp; 
<a href="" onclick="selectedit('date_photo_price_list'); return false;">Price List</a> &nbsp;  &nbsp; 
<a href="" onclick="selectedit('date_public'); return false;">Status</a> &nbsp;  &nbsp; 
</div>


<form method="post" name="newfolder" action="<?php print $_SERVER['PHP_SELF'];?>"  >
<input type="hidden" name="pageids" id="pageids" value="<?php print $_REQUEST['pageids'];?>" class="formfield">
<input type="hidden" name="date_cat" id="date_cat" value="<?php print $_REQUEST['date_cat'];?>" class="formfield">
<input type="hidden" name="action" id="action" value="save" class="formfield">
<input type="hidden" name="noclose" id="noclose" value="1" class="formfield">
<input type="hidden" name="nofonts" id="nofonts" value="1" class="formfield">
<input type="hidden" name="nojs" id="nojs" value="1" class="formfield">


<div >
	<div style="padding: 16px;">
	<?php
	$pageids = explode("|",$_REQUEST['pageids']);
	foreach($pageids AS $id) { 
		if($id > 0) { 
			$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*, CONCAT(date_date, ' ', date_time) AS datetime,date_format(date_date, '".$site_setup['date_format']." ')  AS date_show_date , time_format(date_time, '%h:%i %p')  AS date_time_show,date_format(last_modified, '".$site_setup['date_format']." %h:%i %p')  AS last_modified , date_format(date_expire, '".$site_setup['date_format']." ')  AS date_expire_show", "WHERE date_id='".$id."' ");
			$total++;
		}
		?>



	<div class="<?php if($id <= 0) { ?>hidden toaddtoall<?php } ?>">

	<div class="underline" <?php if($id <= 0) { ?>style="background: #F9F9F9;"<?php } ?>>
		<div class="label"><h3><?php if($id <= 0) { print "Apply to all pages"; } else { print $date['date_title']; } ?></h3></div>

		<div class="left  date_date hidden"  style="margin-right: 24px;">
			<div>Date</div>
			<div><input type="text" name="date_date[<?php print $date['date_id'];?>]" value="<?php print $date['date_date'];?>" id="date_date" class="datepicker data-date_date" size="12" <?php if($id <= 0) { ?>onchange="settoall('date_date');"<?php } ?>></div>
		</div>



		<div class="left  date_expire hidden" style="margin-right: 24px;">
			<div>Expiration Date</div>
			<div><input type="text" name="date_expire[<?php print $date['date_id'];?>]" value="<?php print $date['date_expire'];?>" id="date_expire" class="datepicker data-date_expire" size="12" <?php if($id <= 0) { ?>onchange="settoall('date_expire');"<?php } ?>></div>
		</div>

		<?php if(($date['cat_type'] == "clientphotos") || ($id <=0)==true){ ?>
		<div class="left  date_photo_price_list hidden" style="margin-right: 24px;">
			<div>Price List</div>
			<div>
			<select name="date_photo_price_list[<?php print $date['date_id'];?>]" id="date_photo_price_list" <?php if($id <= 0) { ?>onchange="settoall('date_photo_price_list');"<?php } ?> class="data-date_photo_price_list">
			<option value="0">No price list - not for sale</option>
			<?php $lists = whileSQL("ms_photo_products_lists", "*", "ORDER BY list_name ASC ");
				while($list = mysqli_fetch_array($lists)) { ?>
				<option value="<?php print $list['list_id'];?>" <?php if($list['list_id'] == $date['date_photo_price_list']) { print "selected"; } ?>><?php print $list['list_name'];?></option>
				<?php } ?>
			</select>
			</div>
		</div>
		<?php } else {  ?>
		<input type="hidden" name="date_photo_price_list[<?php print $date['date_id'];?>]" value="<?php print $date['date_photo_price_list'];?>">
		<?php } ?>


		<div class="left  date_public hidden" style="margin-right: 24px;">
			<div>Status</div>
			<div>
			<select name="date_public[<?php print $date['date_id'];?>]" id="date_public" <?php if($id <= 0) { ?>onchange="settoall('date_public');"<?php } ?> class="data-date_public">
				<option value="2" <?php if($date['date_public'] == "2") { print "selected"; } ?>>Draft</option>
				<option value="1" <?php if($date['date_public'] == "1") { print "selected"; } ?>>Published</option>
				<?php if($date['cat_type'] == "clientphotos") { ?>
				<option value="3" <?php if($date['date_public'] == "3") { print "selected"; } ?>>Pre-Register</option>
				<?php } ?>
			</select>
			</div>
		</div>


		<div class="clear"></div>

	</div>
<?php if($id <= 0) { ?>
<div class="underlinespacer">Or edit individual pages below.</div>
<?php } ?> 
</div>
<?php } ?>




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
<div class="underline center">
	<input type="checkbox" name="deleteall" id="deleteall" value="1" onchange="confirmdeletepages();"> <label for="deleteall">Delete all pages</label> &nbsp; <input type="checkbox" name="deletephotos" id="deletephotos" value="1"> <label for="deletephotos">and delete assigned photos</label>
</div>
<div class="error hide center" id="confirmdelete">You are about to delete the selected pages. Click save changes to continue.</div>
<div class="clear"></div>
<div class="pc center"><input type="submit" name="submit" value="Save Changes" class="submit"></div>
</form>




<?php require "../w-footer.php"; ?>
