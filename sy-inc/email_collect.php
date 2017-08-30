<?php  //if(!isset($_COOKIE['myemail'])) { ?>

<script>
function requesaccess(classname) { 
	var fields = {};

	var rf = false;
	var mes;
	var stop;
	$(".emrequired").each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('inputError');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('inputError');
		}
	} );

if( !isValidEmailAddress($("#enter_email_popup").val())) { 
	alert("The email address you entered does not seem to be formatted correctly. Please check your email address.");
	stop = true;
}
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
				$("#reqformpopup").slideUp(200, function() { 
					closeemailsignup();
					<?php if($date['splash_enable'] == "1") { ?>

					window.location="#splash";

				<?php } ?>
				});
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}

function closeemailsignup() { 
	$("#emailsignuppopup").fadeOut(200);
}


$(document).ready(function() { 
	$("#emailsignuppopup").fadeIn(200);

});

	</script>
	<div id="emailsignuppopup" class="hide">
		<div id="signupbg" class="signupbg""></div>

		<div id="signupcontainer" class="signupcontainer">
			<div class="signupcontainerinner">
			<div  id="reqformpopup">
				<div class="pc"><?php print _enter_email_address_to_view_gallery_;?></div>

				<form method=POST name="requestaccess" id="requestaccess" action="<?php print $site_setup['index_page'];?>" onSubmit="requesaccess('emspop'); return false;" >
					<div >
						<div class="pc"><?php print _email_address_;?></div>
						<div class="pc"><input type="text"  id="enter_email_popup" size="20" class="emspop emrequired field100" style="max-width: 300px;" value="<?php print $_COOKIE['myemail'];?>"> </div>
						<div class="pc"><input type="submit" name="submit" class="submit" value="<?php print _enter_email_address_to_view_submit_;?>"></div>
						<div class="pc"><a href="<?php print $setup['temp_url_folder'];?>/"><?php print _cancel_;?></a></div>
					</div>

				<div class="cssClear"></div>
				<input type="hidden" class="emspop" name="action" id="action" value="emailcollect">
				<input type="hidden" class="emspop" name="elocation" id="elocation" value="pop">
				<input type="hidden" class="emspop"  id="did" value="<?php print $date['date_id'];?>">

				</form>
			</div>
		</div>
	</div>
</div>
<?php
//$time=time()+3600*24*365*2;
//$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
//$cookie_url = ".$domain";
//SetCookie("myemail","1",$time,"/",null);
// }  ?>