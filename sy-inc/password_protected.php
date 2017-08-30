<?php require $setup['path']."/sy-inc/store/store_purchase_access.php"; ?>
<?php
if($_REQUEST['action'] == "checkpass") {
	$_REQUEST['email'] = sql_safe($_REQUEST['email']);
	$_POST['ppdid'] = sql_safe($_POST['ppdid']);

	 if(($cat['cat_require_email'] > 0) &&(!customerLoggedIn()) && (empty($_REQUEST['email'])) == true) {
		print "<div class=\"error\">"._empty_fields_.": "._email_address_."</div>";

	 } else { 
		 if(($cat['cat_require_email'] > 0) && (!empty($_REQUEST['email'])) == true) { 
			$time=time()+3600*24*365*2;
			$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
			$cookie_url = ".$domain";
			SetCookie("myemail",$_REQUEST['email'],$time,"/",null);
		 }
		$ckdate =  doSQL("ms_calendar", "*", "WHERE MD5(date_id)='".$_POST['ppdid']."' ");
		if(strtolower(trim($_POST['gpass'])) == strtolower($ckdate['password'])) {
			if(!is_array($_SESSION['privateAccess'])) {
				$_SESSION['privateAccess'] = array();
			}
			array_push($_SESSION['privateAccess'], $ckdate['date_id']);

			if(customerLoggedIn()) { 
				$cka = doSQL("ms_my_pages", "*", "WHERE mp_date_id='".$ckdate['date_id']."' AND MD5(mp_people_id)='".$_SESSION['pid']."' "); 
				if(empty($cka['mp_id'])) { 
					$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
					insertSQL("ms_my_pages", "mp_date_id='".$ckdate['date_id']."' , mp_people_id='".$person['p_id']."', mp_date=NOW() ");
				}
			}
			 if(($cat['cat_require_email'] > 0) &&(!customerLoggedIn()) && (empty($_REQUEST['email'])) == true) {
				$cke = doSQL("ms_pre_register", "*", "WHERE reg_email='".$_REQUEST['email']."' AND reg_date_id='".$ckdate['date_id']."' AND toview='1'  ");
				if(empty($cke['reg_id'])) { 
					insertSQL("ms_pre_register", "toview='1', reg_email='".$_REQUEST['email']."', reg_date_id='".$ckdate['date_id']."', reg_date='".date('Y-m-d h:i:s')."', reg_ip='".getUserIP()."' ");
				}
			}
			if(($_REQUEST['view'] == "room") && (!empty($_REQUEST['rw'])) == true) { 
				header("location: index.php?view=room&rw=".$_REQUEST['rw']."");
			} else { 
				header("location: index.php");
			}
			session_write_close();
			exit();
		} else {
			print "<div class=error>"._private_gallery_password_incorrect_."</div>";
		//	galPassword($date_id);
	//		exit();
		}
	 }
}


function gainAccessToPage($date_id) { 
	global $setup,$site_setup;
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$date_id."' ");
	if(($date['prod_price'] > 0)&&($date['date_paid_access'] == "1")==true) { 
		productCart();
		$password_page = 1;
		include $setup['path']."/sy-footer.php";
	} else { 
		galPassword($date_id);
	}
}

function galPassword($date_id) {
	global $site_type,$setup,$site_setup,$person,$css,$cat,$date,$ge;
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
	<form method="post" name="galpass" action="index.php" style="padding: 0; margin: 0;">
	<div class="pageContent">
	<input type="password" name="gpass" size="15">
	<input type="hidden" name="action" value="checkpass">
	<input type="hidden" name="view" value="<?php if($_REQUEST['view'] == "room") { ?>room<?php } ?>">
	<?php if(!empty($_REQUEST['rw'])) { ?>
	<input type="hidden" name="rw" value="<?php print $_REQUEST['rw'];?>">
	<?php } ?>
	<input type="hidden" name="ppdid" value="<?php print MD5($date_id);?>">
	<?php if(($cat['cat_require_email'] > 0) &&(!customerLoggedIn())==true){ ?>
	</div>
	<div class="pc center"><?php print _store_find_order_email_;?></div>
	<?php 
	if(customerLoggedIn()) { $email = $person['p_email']; } 
	if((empty($email)) && (isset($_COOKIE['myemail'])) == true) { 
		$email = $_COOKIE['myemail'];	
	}?>

	<div class="pc"><input type="text" name="email" id="email" value="<?php print $email; ?>" size="15"></div>
	<div class="pc center">
	<input  type="submit" name="submit" class="submit" value="<?php print _private_gallery_submit_password_;?>">
	</div>
	<?php } else { ?>
	<input  type="submit" name="submit" class="submit" value="<?php print _private_gallery_submit_password_;?>">
	</div>
	<?php } ?>
	<div class="pageContent"><?php print _private_gallery_text_;?></div>
	</form>

	</div></center>

	<?php if($date['date_owner'] > 0) { ?><div class="pc center passwordloginlink"><a href="" onclick="showgallerylogin('<?php print $_REQUEST['view'];?>','<?php print $sub['sub_link'];?>','','login'); return false;"><?php print _gallery_owner_login_;?></a></div><?php } ?>
	<div style="max-width: 800px; width: 100%; margin: auto;">
	<div class="pc center" id="requestaccesslink"><a href="" onclick="showreqform(); return false;"><?php print _request_access_;?></a></div>
	<div id="reqform" class="hide">
	<div class="pc"><?php print _request_access_text_;?></div>

	<form method=POST name="requestaccess" id="requestaccess" action="<?php print $site_setup['index_page'];?>" onSubmit="requesaccess('reqacc'); return false;" >
	<div style="width: 49%;" class="left nofloatsmallleft">
		<div >
			<div class="pc"><?php print _name_;?></div>
			<div class="pc"><input type="text"  id="req_name" size="20" value="<?php if(!empty($person['p_name'])) { print htmlspecialchars($person['p_name']." ".$person['p_last_name']); } ?>" class="reqacc required field100"></div>
		</div>
	</div>
	<div style="width: 49%; float: right;"  class="nofloatsmallleft">
		<div >
			<div class="pc"><?php print _email_address_;?></div>
			<div class="pc"><input type="text"  id="req_email" size="20" value="<?php print htmlspecialchars($person['p_email']);?>" class="reqacc required field100"></div>
		</div>
	</div>
<div class="cssClear"></div>
	<div class="pc"><?php print _request_access_message_;?></div>
	<div class="pc"><textarea id="req_message" rows="5" cols="40" class="field100 reqacc required"></textarea></div>
	<input type="hidden" class="reqacc" name="action" id="action" value="requestaccess">
	<input type="hidden" class="reqacc"  id="did" name="did" value="<?php print $date_id;?>">
	<div class="pc center"><input type="submit" name="submit" class="submit" value="<?php print _request_access_send_;?>"></div>

	</form>
	</div>
	<div id="reqsuccess" class="hide success center"><?php print _request_access_sent_;?></div>
	</div>


	<?php
		$no_subs = true;
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
?>
