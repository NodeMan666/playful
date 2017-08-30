<?php
$lang = doSQL("ms_gift_certificate_language", "*", " ");
$x = 0;
$textareas = array(
	"_gift_certificate_text_",
	"_gift_certificate_bottom_text_",

	"_gift_certificate_redeem_text_"	
	);

$ignore = array("_wd_frames_title_",
"_wd_frames_text_",
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
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span>eGift Card Text & Language</span></div> 
<div class="buttonsgray">
<ul>
	<li><a href="index.php?do=people&view=giftcertificates" <?php if(empty($_REQUEST['sub'])) { ?>class="on"<?php } ?>>eGIFT CARDS</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=settings">eCARD STYLE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=language"  class="on">LANGUAGE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=amounts">AMOUNTS & SETTINGS</a></li>

</ul>
</div>
<div class="clear"></div>
<div class="pc">Below is the language shown when someone is purchasing or redeeming an eGift Card.</div>
<div>&nbsp;</div>
<div id="wdtexts" class="" style="max-width: 800px; margin: auto;">
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
			<form method="post" name="form-<?php print $d['table_field'];?>" action="index.php"   onSubmit="return submitPopupForm('admin.actions.php','field-<?php print $d['table_field'];?>');">
			<input type="hidden" name="action" value="updateGCText" id="action" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="field_name" value="<?php print $d['table_field'];?>" id="field_name" class="field-<?php print $d['table_field'];?>">
			<input type="hidden" name="table_name" value="ms_gift_certificate_language" id="table_name" class="field-<?php print $d['table_field'];?>">
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
