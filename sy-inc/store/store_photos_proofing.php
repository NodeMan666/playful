<?php
$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['pic']."' ");
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
// print "<li>pic: ".$pic['pic_id'];
if(!empty($_REQUEST['sub_id'])) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
	$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
} else { 
	if((empty($_REQUEST['kid']))&&(empty($_REQUEST['keyWord']))==true) { 
		$and_sub = "AND bp_sub='0' ";
	}
}

$pics_where = "WHERE bp_blog='".$date['date_id']."' $and_sub ";
$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id LEFT JOIN  ms_photo_keywords_connect ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id";
$picsd = whileSQL("$pics_tables", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "$pics_where $and_where GROUP BY pic_id   ");
$total_images = mysqli_num_rows($picsd);
while($picd = mysqli_fetch_array($picsd)) { 
	if(countIt("ms_proofing",  "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$picd['pic_id']."' ")> 0) { 
		$total_done++;	
	}
}
if($total_done <=0) { 
	$total_done = 0;
}
?>




<script>
function completereview() { 
	$("#buybackground").fadeIn(50, function() { 
		if(isslideshow) { 
			if(isslideshow == true) { 
				stopSlideshow();
			}
		}
		$("#completereview").css({"top":100+"px"});
		$("#completereview").slideDown(200, function() {  });
	});
}
function cancelsendreview() { 
	$("#completereview").slideUp(200, function() {
		$("#buybackground").fadeOut(50);
	});

}

function sendreview() { 
	var fields = {};
	// $("#proof_comment_error").slideUp(200);
	$("#proofselect").attr("disable-keypress","1");
	$("#reviewsendbuttons").hide();
	$("#reviewsendloading").show();
	fields['review_complete_message'] = $("#review_complete_message").val();
	fields['action'] = "proofingcomplete";
	fields['status'] = "1";
	fields['did'] = $("#vinfo").attr("did");
	$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
//		alert(data);
		$("#reviewsendloading").slideUp(100);
		$("#submitreview").hide();

		$("#reviewcomplete").slideDown(100);
	});
}

function closecompletereview() { 
	cancelsendreview();
	closeFullScreen();

}


function approveproof() { 
	$("#revisemessage").slideUp(200);

	$("#proof_comment_error").slideUp(200);
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=proofing&status=1&did="+$("#vinfo").attr("did")+"&pic="+pic, function(data) {
		$("#proofstatusapproved").show();
		$("#proofselect").hide();
		$("#proofselect").attr("disable-keypress","1");
		$("#proof-status-"+pic).html($("#proofbuttons").attr("lang-approved"));

		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=totalproofviewed&did="+$("#vinfo").attr("did")+"&pic="+pic, function(data) {
			num = $.trim(data);
			$("#total-reviewed").html(num);
			if(num == Math.abs($("#proofbuttons").attr("total-images"))) { 
				completereview();
				$("#submitreview").show();
			}
		});
	});
}

function rejectproof() { 
	$("#revisemessage").slideUp(200);

	$("#proof_comment_error").slideUp(200);
	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=proofing&status=3&did="+$("#vinfo").attr("did")+"&pic="+pic, function(data) {
		$("#proofstatusrejected").show();
		$("#proofselect").hide();
		$("#proofselect").attr("disable-keypress","1");
		$("#proof-status-"+pic).html($("#proofbuttons").attr("lang-rejected"));

		$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=totalproofviewed&did="+$("#vinfo").attr("did")+"&pic="+pic, function(data) {
			num = $.trim(data);
			$("#total-reviewed").html(num);
			if(num == Math.abs($("#proofbuttons").attr("total-images"))) { 
				completereview();
				$("#submitreview").show();
			}
		});
	});
}

function savereviseproof() { 
	var fields = {};
	$("#proof_comment_error").slideUp(200);

	pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
	fields['pic'] = pic;
	fields['proof_comment'] = $("#proof_comment").val();
	fields['action'] = "proofing";
	fields['status'] = "2";
	fields['did'] = $("#vinfo").attr("did");
	if($("#proof_comment").val() == "") { 
		$("#proof_comment_error").slideDown(200);
	} else { 
		$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 
	//		alert(data);
			$("#proofselect").attr("disable-keypress","1");
			$("#proof-status-"+pic).html($("#proofbuttons").attr("lang-revise"));

			$("#revisepicmessage").html(data);
			$("#proofstatusrevise").show();
			$("#revisemessage").hide();
			$("#proofselect").hide();
			$.get(tempfolder+"/sy-inc/store/store_cart_actions.php?action=totalproofviewed&did="+$("#vinfo").attr("did")+"&pic="+pic, function(data) {
				num = $.trim(data);
				$("#total-reviewed").html(num);
				if(num == Math.abs($("#proofbuttons").attr("total-images"))) { 
					completereview();
					$("#submitreview").show();
				}
			});

		});
	}
}

function reviseproof() {
	$("#revisemessage").slideToggle(200, function() { 
		$("#proofselect").attr("disable-keypress","1");
		$("#proof_comment").focus();
	});
}
function changeproofstatus() { 
	$(".proofstatus").slideUp(200,function() { 
		$("#proofselect").attr("disable-keypress","0");

		$("#proofselect").slideDown();
	});
}
function proofkeypress() {
	$("html").keypress(function(e) {
		//	alert($("#proofselect").attr("disable-keypress")+ " X "+e.which);
		if(e.which == '97') {
			if($("#proofselect").attr("disable-keypress")=="0") {
				e.preventDefault();
				approveproof();
		   }
		}
	});
}

$(document).ready(function(){
	proofkeypress();
	$("#proof_comment").focus(function() { 
		$("#vinfo").attr("disablearrow", "1");
	}) 
	$("#proof_comment").blur(function() { 
		$("#vinfo").attr("disablearrow", "0");
	}) 

});

</script>
<?php $ck = doSQL("ms_proofing", "*", "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$pic['pic_id']."' "); 
$cks = doSQL("ms_proofing_status", "*", "WHERE date_id='".$date['date_id']."' ORDER BY id DESC");
?>
<div class="pc center"><span id="total-reviewed"><?php print $total_done;?></span> / <?php print $total_images;?> <?php print _proof_reviewed_;?>
<span id="submitreview" <?php if(($total_done < $total_images)||($cks['status'] > 0)==true)  { print "class=\"hide\""; } ?>>&nbsp;<a href="" onclick="completereview(); return false;"><?php print _proof_submit_review_;?></a></span>
</div>


<?php if($cks['status'] > 0) { ?>
<?php if($cks['status'] == "1") { ?>
	<div class="pc"><?php print _proof_admin_pending_;?></div>
<?php } ?>
<?php if($cks['status'] == "2") { ?>
	<div class="pc"><?php print _proof_project_closed_;?></div>
<?php } ?>
<?php } else { ?>
<div class="pc center"><?php print _proof_approve_tip_;?></div>
<div class="pc center <?php if(!empty($ck['proof_id'])) { print "hide"; } ?>" id="proofselect" disable-keypress="<?php if(!empty($ck['proof_id'])) { print "1"; } else { print "0"; } ?>">
	<ul class="proofbutton" id="proofbuttons" lang-approved="<?php print _proof_approved_;?>" lang-rejected="<?php print _proof_rejected_;?>" lang-revise="<?php print _proof_revision_requested_;?>" total-images="<?php print $total_images;?>">
	<li onclick="approveproof(); return false;" class="proofapprove"><?php print _proof_approve_;?></li>
	<?php if($date['proofing_disable_revise'] !== "1") { ?><li onclick="reviseproof(); return false;" class="proofrevise"><?php print _proof_revise_;?></li><?php } ?>
	<?php if($date['proofing_disable_reject'] !== "1") { ?><li onclick="rejectproof(); return false;" class="proofreject"><?php print _proof_reject_;?></li><?php } ?>
	</ul>
</div>

<div class="pc center proofstatus <?php if($ck['proof_status']!=="1") { print "hide"; } ?>" id="proofstatusapproved">
	<?php print _proof_approved_; ?>
	<a href="" onclick="changeproofstatus(); return false;"><?php print _proof_change_;?></a>
</div>
<div class="pc center proofstatus <?php if($ck['proof_status']!=="3") { print "hide"; } ?>" id="proofstatusrejected">
	<?php print _proof_rejected_; ?>
	<a href="" onclick="changeproofstatus(); return false;"><?php print _proof_change_;?></a>
</div>

<div class="pc center proofstatus <?php if($ck['proof_status']!=="2") { print "hide"; } ?>" id="proofstatusrevise">
	
	<div><?php print _proof_revision_requested_; ?> (<a href="" onclick="changeproofstatus(); return false;"><?php print _proof_change_;?></a>) "<i><span id="revisepicmessage"><?php print nl2br($ck['proof_comment']);?></i>"</span></div>

</div>

<div id="revisemessage" class="hide">
	<div class="pc"><?php print _proof_revise_message_;?></div>
	<div class="pc"><textarea id="proof_comment" rows="3" cols="30"><?php print $ck['proof_comment'];?></textarea></div>
	<div class="pc"><a href="" onclick="savereviseproof(); return false;"><?php print _proof_revise_save_;?></a></div>
	<div id="proof_comment_error" class="hide center error"><?php print _proof_empty_comment_message_;?></div>
	<div>&nbsp;</div>
</div>
<?php } ?>
