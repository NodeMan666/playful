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
			if(data == "good") { 
				$("#reqform").slideUp(200, function() { 
					$("#regsuccess").slideDown();
					$("#requestaccesslink").hide();
				});
			}
			 // $("#accresponse").html(data);

		});
	}
	return false;
}
</script>

<div style="margin: auto;">
	<div class="pc center"><h1><?php print $date['date_title'];?></h1><h2><?php print _pre_register_title_;?></h2></div>
	<div style="max-width: 800px; width: 100%; margin: auto;">
		<div id="reqform">
			<div class="pc center"><?php print _pre_register_message_;?></div>

			<form method=POST name="requestaccess" id="requestaccess" action="<?php print $site_setup['index_page'];?>" onSubmit="requesaccess('regacc'); return false;" >
				<div style="width: 49%; float: left;">
					<div >
						<div class="pc"><?php print _first_name_;?></div>
						<div class="pc"><input type="text"  id="reg_first_name" size="20" value="<?php if(!empty($person['p_name'])) { print htmlspecialchars($person['p_name']); } ?>" class="regacc required field100"></div>
					</div>
				</div>
				<div style="width: 49%; float: right;">
					<div >
						<div class="pc"><?php print _last_name_;?></div>
						<div class="pc"><input type="text"  id="reg_last_name" size="20" value="<?php if(!empty($person['p_last_name'])) { print htmlspecialchars($person['p_last_name']); } ?>" class="regacc required field100"></div>
					</div>
				</div>

				<div class="cssClear"></div>

				<div style="width: 49%; float: left;">
					<div >
						<div class="pc"><?php print _email_address_;?></div>
						<div class="pc"><input type="text"  id="reg_email" size="20" value="<?php print htmlspecialchars($person['p_email']);?>" class="regacc required field100"></div>
					</div>
				</div>
				<div class="cssClear"></div>

	
				<input type="hidden" class="regacc" name="action" id="action" value="preregister">
				<input type="hidden" class="regacc"  id="did" value="<?php print $date_id;?>">
				<div class="pc center"><input type="submit" name="submit" class="submit" value="<?php print _pre_register_send_;?>"></div>

			</form>
		</div>
		<div id="regsuccess" class="hide success center"><?php print _pre_register_success_;?></div>
	</div>
</div>

	<?php
	if(isset($_SESSION['office_admin_login'])) { 
		print "<div>&nbsp;</div>";
		print "<div class=\"pc center success\"><i>This page is set to pre-register,  but since you are logged into the admin you have direct access to the photos below. Your visitors only see the form above.</i></div>";
		print "<div>&nbsp;</div>";
	} else { 
	
		$password_page = 1;
		include $setup['path']."/sy-footer.php";
		exit();
	}
?>