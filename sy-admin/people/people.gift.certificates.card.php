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
function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function savecard() { 
	var fields = {};
	fields['giftcardstyle'] = $("#giftcardcontainer").html();
	fields['do'] = "people";
	fields['view'] = "giftcertificates";
	fields['sub'] = "settings";
	fields['action'] = "savestyle";

	$.post('index.php', fields,	function (data) { 
		$("#stylesaved").slideDown(250);
		setTimeout(function() { 
			$("#stylesaved").slideUp(150);
		},2000);
	});
}

function editstyle() { 
	$("#giftcardcontainer").hide()
	$("#giftcardcode").val($("#giftcardcontainer").html());
	$("#giftcardhtml").show();
	$("#styleform").hide();
}
function editstyleback() { 
	$("#giftcardcontainer").html($("#giftcardcode").val());
	$("#giftcardhtml").hide()
	$("#giftcardcontainer").show()
	$("#styleform").show();
	setTimeout(function() { 
		// $("#gcbg").val(rgb2hex($("#giftcard").css("background-color")));
		// $("#gctext").val(rgb2hex($("#giftcard").css("color")));
	},50);
}
 $(document).ready(function(){
	$("#gcbg").change(function() {
		// $("#log").show().append($("#gcbg").val()+"<br>");
		$("#giftcard").css({"background-color":"#"+$("#gcbg").val()});
	});
	$("#gctext").change(function() {
		$("#giftcard").css({"color":"#"+$("#gctext").val()});
	});

	$('.edittext').mousedown(function() {
		// $("#log").show().append($(this).attr("id")+"<br>");
	});
	$("#gcbg").val(rgb2hex($("#giftcard").css("background-color")));
	$("#gctext").val(rgb2hex($("#giftcard").css("color")));

	 // $("#log").show().append($("#giftcard").css("color"));

 });
</script>
<?php
if($_REQUEST['action'] == "savestyle") { 
	$_POST['giftcardstyle'] = trim($_POST['giftcardstyle']);
	updateSQL("ms_gift_certificate_language", "gift_card_style='".addslashes(stripslashes($_POST['giftcardstyle']))."' ");
	exit();
}

?>
<div class="pc"><a href="index.php?do=people">&larr; People</a></div>
<div class="pc newtitles"><span>eGift Card Style</span></div> 
<div class="buttonsgray">
<ul>
	<li><a href="index.php?do=people&view=giftcertificates" <?php if(empty($_REQUEST['sub'])) { ?>class="on"<?php } ?>>eGIFT CARDS</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=settings" class="on">eCARD STYLE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=language">LANGUAGE</a></li>
	<li><a href="index.php?do=people&view=giftcertificates&sub=amounts">AMOUNTS & SETTINGS</a></li>
</ul>
</div>
<div class="clear"></div>
<div class="pc">Here you can style the eGift Card that is sent in an email to the person the eGift Card was purchased for.  </div>
<!-- DEFAULT CODE -->
<!-- 
<div id="defaultstyle" class="hide">
	<div id="giftcard" style="width: 100%; max-width: 360px; height: 240px; background-color: #000000; border-radius: 12px;margin: auto; text-align: center; color: #a4a4a4;">
		<div id="giftcardinner" style="padding: 24px;">
			<div id="giftcardsitename" class="edittext" style="padding: 4px;" contenteditable>WEBSITE NAME</div>
			<div id="giftcardtitle"  class="edittext" style="padding: 4px;"  contenteditable>eGift Card</div>
			<div id="giftcardamount"  class="edittext" style="padding: 4px; font-size: 40px; ">AMOUNT</div>
			<div id="giftcardredeem"  class="edittext" style="padding: 4px;"  contenteditable>Redeem Code: </div>
			<div id="giftcardredeemcode"  class="edittext" style="padding: 4px;" >[REDEEM_CODE]</div>
		</div>
	</div>
</div>
-->
<div class="p50 left">
	<div style="padding: 24px;">
<div id="giftcardcontainer">
<?php print $lang['gift_card_style']; ?>		
</div>
		<div id="giftcardhtml" class="hide">
		<div class="pc">You can change the code for the eGift Card, but keep in the replace codes shown below. </div>
			<div class="pc"><textarea id="giftcardcode" rows="12" cols="40" class="field100"></textarea></div>
			<div class="pc"><a href="" onclick="editstyleback(); return false;">Apply</a></div>
		</div>
		<div>&nbsp;</div>
		<div class="pc">
		<ul>
			<li>[WEBSITE_NAME] will be replaced with your website name. You can click and edit that above.</li>
			<li>[AMOUNT] will be replaced with the amount purchased.</li>
			<li>[REDEEM_CODE] will be replaced with the redeem code generated when purchased.</li>
			<li>You can click and edit the words for eGift Card and Redeem code above.</li>
		</ul>
		</div>
	</div>
</div>

<div class="p50 left">
	<div style="padding: 24px;">
	<div id="stylesaved" class="successMessage">Style Saved</div>
		<div id="styleform">
			<form method="post" name="gc" id="gc" action="index.php">
			<div class="underlinelabel">Style eGift Card</div>
			<div class="underline">
				<div class="left" style="margin-right: 16px;">
					<div class="label">Background Color</div>
					<div><input type="text" size="8" id="gcbg" value="" class="color"></div>
				</div>

				<div class="left"  style="margin-right: 16px;">
					<div class="label">Font Color</div>
					<div><input type="text" size="8" id="gctext" value="" class="color"></div>
				</div>
				<div class="left"  style="margin-right: 16px;">
					<div class="label">&nbsp;</div>
					<div><a href="" onclick="editstyle(); return false;">Enter my own code</a></div>
				</div>


				<div class="clear"></div>
			</div>
			<div>&nbsp;</div>
			<div class="pc buttons">
			<a href="" onclick="savecard(); return false;" class="submit">Save Style</a>
			</div>
			</form>
		</div>

	</div>
</div>
<div class="clear"></div>


<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
