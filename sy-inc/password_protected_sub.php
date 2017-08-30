<?php
if($_REQUEST['action'] == "checkpass") {
	$cksub =  doSQL("ms_sub_galleries", "*", "WHERE MD5(sub_id)='".$_REQUEST['subid']."' AND MD5(sub_date_id)='".$_REQUEST['ppdid']."' ");
	$ckdate =  doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_REQUEST['ppdid']."' ");
	if($_REQUEST['gpass'] == $cksub['sub_pass']) {
		if(!is_array($_SESSION['privateAccess'])) {
			$_SESSION['privateAccess'] = array();
		}
		array_push($_SESSION['privateAccess'], "sub".$cksub['sub_id']);

		if(customerLoggedIn()) { 
			$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$ckdate['date_id']."' AND mp_sub_id='".$cksub['sub_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
			if(empty($cka['mp_id'])) { 
				$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
				insertSQL("ms_my_pages", "mp_date_id='".$ckdate['date_id']."' , mp_sub_id='".$cksub['sub_id']."', mp_people_id='".$person['p_id']."', mp_date=NOW() ");
			}
		}

		session_write_close();
		header("location: index.php?sub=".$sub['sub_link']."");
		exit();
	} else {
		print "<div class=error>"._private_gallery_password_incorrect_."</div>";
	//	galPassword($date_id);
//		exit();
	}
}

function galPassword($date_id,$sub_id) {
	global $site_type,$setup,$site_setup,$person,$css;
	$thissub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$sub_id."' ");
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$date_id."' ");
	if($date['date_gallery_exclusive'] == "1") {
		$ge = doSQL("ms_gal_exclusive", "*", "WHERE gal_id='".$date['date_gallery_exclusive']."' ");
	}
	?>
	<script>

function requesaccess(classname) { 
	var fields = {};

	var rf = false;
	var mes;
	var stop;
	$(".required").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );

	if(rf == true || stop == true) {
		if(rf == true) {
			 $("#accresponse").html('<div class="pc"><div class="error">You have required fields empty</div></div>');
		}
		return false;
	} else { 

		$('.'+classname).each(function(){
			var $this = $(this);
			if( $this.attr('type') == "radio") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] = $this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}
			} else if($this.attr('type') == "checkbox") { 
				if($this.attr("checked")) { 
					fields[$this.attr('name')] += ","+$this.val(); 
	//				alert($this.attr('name')+': '+ $this.attr('type')+' CHECKED- '+$this.val());
				}

			} else { 
				fields[$this.attr('id')] = $this.val(); 
				//fields[$this.attr('name')] = $this.val(); 
			}
		});

			$.post(tempfolder+'/sy-inc/store/store_cart_actions.php', fields,	function (data) { 

			data = $.trim(data);
			// alert(data);
			if(data == "good") { 
				$("#reqform").slideUp(200, function() { 
					$("#reqsuccess").slideDown();
					$("#requestaccesslink").hide();
				});
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}
function showreqform() { 
	$("#reqform").slideToggle(200);

}

	</script>
	<center><div style="margin: auto;"><div class="pageContent"><h2><?php print _private_gallery_enter_password_;?></h2></div>
	<form method="POST" name="galpass" action="index.php" style="padding: 0; margin: 0;">
	<div class="pageContent">
	<input type="password" name="gpass" size="15">
	<input type="hidden" name="action" value="checkpass">
	<input type="hidden" name="ppdid" value="<?php print MD5($date_id);?>">
	<input type="hidden" name="subid" value="<?php print MD5($thissub['sub_id']);?>">
	<input type="hidden" name="sub" value="<?php print $thissub['sub_link'];?>">
	<input  type="submit" name="submit" class="submit" value="<?php print _private_gallery_submit_password_;?>">
	</div>
	<div class="pageContent"><?php print _private_gallery_text_;?></div>
	</form>

	</div></center>
	<div style="max-width: 800px; width: 100%; margin: auto;">
	<div class="pc center" id="requestaccesslink"><a href="" onclick="showreqform(); return false;"><?php print _request_access_;?></a></div>
	<div id="reqform" class="hide">
	<div class="pc"><?php print _request_access_text_;?></div>

	<form method=POST name="requestaccess" id="requestaccess" action="<?php print $site_setup['index_page'];?>" onSubmit="requesaccess('reqacc'); return false;" >
	<div style="width: 49%; float: left;">
		<div >
			<div class="pc"><?php print _name_;?></div>
			<div class="pc"><input type="text"  id="req_name" size="20" value="<?php if(!empty($person['p_name'])) { print htmlspecialchars($person['p_name']." ".$person['p_last_name']); } ?>" class="reqacc required field100"></div>
		</div>
	</div>
	<div style="width: 49%; float: right;">
		<div >
			<div class="pc"><?php print _email_address_;?></div>
			<div class="pc"><input type="text"  id="req_email" size="20" value="<?php print htmlspecialchars($person['p_email']);?>" class="reqacc required field100"></div>
		</div>
	</div>
<div class="cssClear"></div>
	<div class="pc"><?php print _request_access_message_;?></div>
	<div class="pc"><textarea id="req_message" rows="5" cols="40" class="field100 reqacc required"></textarea></div>
	<input type="hidden" class="reqacc" name="action" id="action" value="requestaccess">
	<input type="hidden" class="reqacc"  id="did" value="<?php print $date_id;?>">
	<input type="hidden" class="reqacc"  id="sub_id" value="<?php print $sub_id;?>">
	<div class="pc center"><input type="submit" name="submit" class="submit" value="<?php print _request_access_send_;?>"></div>

	</form>
	</div>
	<div id="reqsuccess" class="hide success center"><?php print _request_access_sent_;?></div>
	</div>


	<?php
		$password_page = 1;
		include $setup['path']."/sy-footer.php";
}
?>


<div class="photoMessageContainer" id="photoMessageContainer"><div class="photoMessage" id="photoMessage"> Please try again</div></div>

<?php 

	$pics_where = "WHERE bp_blog='".$date['date_id']."' ";
	$pics_tables = "ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id";

if(($gallery['gal_status'] == "0")AND($page_gallery !== true)==true) {
	print "<div class=errorMessage>An error has occured</div>";
	include $setup['path']."/sy-footer.php";
	exit();
}
