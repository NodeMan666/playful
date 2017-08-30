function savedata(classname,postfile,returnurl) { 
	var fields = {};
	var stop = false;
	var rf = false;

	$('.required').each(function(i){
		var this_id = this.id;
		if($('#'+this_id).val() == "") { 
			$('#'+this_id).addClass('requiredFieldEmpty');
			rf = true;
		} else { 
			$('#'+this_id).removeClass('requiredFieldEmpty');
		}
	} );
	if(rf == true) { 
		showErrorMessage("You have required fields empty which are highlighted.");	
		setTimeout("hideErrorMessage()", 4000);
		return false;
	} else { 

		$('.saveform').text("saving...");
		$('.saveform').removeClass("submit").addClass("submitsaving");
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

		
		$.post(postfile, fields,	function (data) { 
			if(returnurl) { 
				window.location.href=returnurl;
			} else { 
				//  alert(data);
				// sweetness($("#show_id").val(),$("#feat_page_id").val(),$("#feat_cat_id").val());
				showSuccessMessage("Saved");
				setTimeout(hideSuccessMessage,4000);
				$('.saveform').text("Save");
				$('.saveform').removeClass("submitsaving").addClass("submit");
			}
		});
	}
}
