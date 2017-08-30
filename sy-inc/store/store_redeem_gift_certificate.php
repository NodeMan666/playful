<?php 
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$store = doSQL("ms_store_settings", "*", "");
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$glang = doSQL("ms_gift_certificate_language", "*", " ");
foreach($glang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = addslashes(stripslashes(strip_tags($value)));
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
		}
	}
}


if(customerLoggedIn()) { 
	$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
}

?>
<script>
function redeemgiftcertificate() { 
	var fields = {};
	var rf = false;
	var stop;
	var mes;
	$("#gcresponse").slideUp(100);
		$(".gcrequired").each(function(i){
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
				$("#gcresponse").slideDown(200);
			}
			return false;
		} else { 

			$('.gcfield').each(function(){
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
				response = data.split('|');
				if(data == "good") { 
					window.location.href="index.php?view=checkout&gc="+$("#redeem_code").val();
				} else { 
					$("#gcresponse").slideDown(200);
				}
			});

			return false;
		}
	return false;

}

$(document).ready(function(){
	setTimeout(function() { 
		$("#redeem_code").focus();
	},50);

});

</script>

<?php 
$amounts = "10,20,25,50,75,100,125,150,200";
$cart['cart_gift_certificate_from_name'] = $person['p_name']." ".$person['p_last_name'];
$cart['cart_gift_certificate_from_email'] = $person['p_email'];
if(!empty($_REQUEST['cart_id'])) { 
	$cart = doSQL("ms_cart", "*", "WHERE MD5(cart_id)='".$_REQUEST['cart_id']."' ");
}

?>

<div style="padding: 24px;" class="inner">
<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closewindowpopup(); return false;" class="the-icons icon-cancel"></a></div>

	<div class="pc center"><h3><?php print _gift_certificate_redeem_title_;?></h3><div>
		<div id="gcform">
			<div class="pc center"><?php print _gift_certificate_redeem_text_;?></div>
			<form method="post" name="redeempc" id="redeempc" action="" onsubmit="redeemgiftcertificate(); return false;">
			<div class="pc center"><input type="text" id="redeem_code" size="6" class="gcrequired gcfield center field100" value=""></div>

			<div id="gcresponse" class="hide pc center "><div class="error"><?php print _gift_certificate_redeem_fail_;?></div></div>

			<input type="hidden" id="action" value="checkgiftcertificate" class="gcfield">
			<div class="pc center"><input type="submit" id="submit" value="<?php  print _gift_certificate_redeem_button_;  ?>" class="submit field100"></div>
			<div class="pc center"><a href="" onclick="closewindowpopup(); return false;"><?php print _cancel_;?></a></div>
			</form>
		</div>
		<div id="gcsuccess" class="hide">
		<div>&nbsp;</div>
			<div class="pc center"><h3><?php print _added_to_cart_;?></h3></div>
			<div class="pc" id="viewcartminilinks">
				<div class="center viewcartminilinks">
				<a href="/<?php print $site_setup['index_page'];?>?view=cart" onClick="viewcart(); return false;"><?php print _view_cart_;?></a>  				<a href="<?php print gotosecure();?><?php print $setup['temp_url_folder'];?>/<?php print $site_setup['index_page'];?>?view=checkout"><?php print _checkout_;?></a>
				<a href="" onclick="giftcertificate(0); return false;"><?php print _gift_certificate_add_another_;?></a>
				</div>
			</div>
		</div>


		</div>

		<div id="addprintcreditphotos" class="hide pc center">
		<br><br>
		</div>
		<div id="redeemcontinue" class="pc hide"><a href="" onclick="closewindowpopup(); return false;"><?php print _continue_;?></a></div>
		<div id="redeemreturnlink" class="pc hide"></div>
	<div>&nbsp;</div>
</div>
<?php  mysqli_close($dbcon); ?>
