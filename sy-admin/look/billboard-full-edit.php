<?php $bill = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['previewBillboard']."' "); 

$css['sm_pin_top'] = 0;
?>
<script type="text/javascript" src="<?php print $setup['manage_folder'];?>/jscolor/jscolor.js"></script>

<script>
function hex2rgba(x,a) {
  var r=x.replace('#','').match(/../g),g=[],i;
  for(i in r){g.push(parseInt(r[i],16));}g.push(a);
  return 'rgba('+g.join()+')';
}


function headerlook() { 
		$("#headerAndMenu").css({"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),'position': 'absolute', 'width': '100%', 'border': '0px', 'z-index': '50' });
		if($("#billboardedit").attr("data-menu-transparent") !== "1") { 

			$("#topMainMenuContainer").css({"background-color":hex2rgba($("#menu_bg").val(),$("#menu_opacity").val()),"border-top-color":hex2rgba($("#menu_border_a").val(),$("#menu_opacity").val()),"border-bottom-color":hex2rgba($("#menu_border_b").val(),$("#menu_opacity").val()),'z-index': '50' });
		}
}


function abs_header() { 
	if($("#billboardedit").attr("data-header-background") == "") { 
		$("#billboardedit").attr("data-header-background",$("#headerAndMenu").css("background-color")).attr("data-header-width",$("#headerAndMenu").css("width")).attr("data-menu-background",$("#topMainMenu").css("background-color"));
		// alert($("#topMainMenu").css("background-color"));
	}
	if($("#abs_header").attr("checked")) { 
		$("#headerAndMenu").css({"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),'position': 'absolute', 'width': '100%', 'border': '0px', 'z-index': '50' });
		$("#header, #headerContainer").css({"background-color":"transparent"});
		if($("#billboardedit").attr("data-menu-transparent") !== "1") { 
			$("#topMainMenuContainer").css({"background-color":hex2rgba($("#menu_bg").val(),$("#menu_opacity").val()),'z-index': '50' });
		}

		$("#abs_header_options").slideDown(100);
	} else { 
		$("#headerAndMenu").css({'background-color': "#"+$("#header_bg").val(),'position': 'relative', 'width': $("#billboardedit").attr("data-header-width"), 'border': '0px', 'z-index': '50' });
		$("#abs_header_options").slideUp(100);
		if($("#billboardedit").attr("data-menu-transparent") !== "1") { 
			$("#topMainMenuContainer").css({"background-color":"#"+$("#menu_bg").val()});
		}
	}
}

function hide_shop_menu() { 
	if($("#hide_shop_menu").attr("checked")) { 
		$("#shopmenucontainer, #smc").css({"display":"none"});
	} else { 
		$("#shopmenucontainer, #smc").css({"display":"block"});

	}
}


function contentrow() { 
	$("#billcontentrow .billboardbutton")
		.css({"background-color":hex2rgba($("#bc_background_color").val(),$("#bc_opacity").val()),
		"border-color":hex2rgba($("#bc_border_color").val(),$("#bc_opacity").val()), 
		"border-width":$("#bc_border_width").val()+"px", 
		"max-width":$("#bc_width").val()+"px", 
		"margin":$("#bc_margin").val()+"px", 

		"border-radius":""+$("#bc_border_radius").val()+"px" });

	$("#billcontentrow .billboardbuttoninner")
		.css({"padding":$("#bc_padding").val()+"px",
		"color":"#"+$("#bc_font_color").val(),
		"font-family":$("#bc_font_family").val(),
		"font-size":$("#bc_font_size").val()+"px",
		"font-weight":$("#bc_font_weight").val(),
		"letter-spacing":$("#bc_letter_spacing").val()+"px",

		"color":"#"+$("#bc_font_color").val()
	});

	$("#billcontentrow").css({"bottom":$("#bc_bottom").val()+"%"});

	$("#billcontentrow")
		.attr("data-bg-color",$("#bc_background_color").val())
		.attr("data-border-color",$("#bc_border_color").val())
		.attr("data-opacity",$("#bc_opacity").val())
		.attr("data-border-width",$("#bc_border_width").val())
		.attr("data-padding",$("#bc_padding").val())
		.attr("data-font-color",$("#bc_font_color").val())
		.attr("data-font-family",$("#bc_font_family").val())
		.attr("data-font-size",$("#bc_font_size").val())
		.attr("data-font-weight",$("#bc_font_weight").val())
		.attr("data-width",$("#bc_width").val())
		.attr("data-bottom",$("#bc_bottom").val())
		.attr("data-margin",$("#bc_margin").val())
		.attr("data-letter-spacing",$("#bc_letter_spacing").val())
		.attr("data-border-radius",$("#bc_border_radius").val());

}

function previewMobile(id) {  
	window.open('index.php?previewBillboard='+id+'&mobileview=1','Mobile', 'width=410, height=700');
}
    
function addbuttonrow() { 
	$("#billboardedit").attr("data-button-row",1);
	$("#addbuttonrow").hide();
	$("#deletebuttonrow").show();
	$("#deletebuttonrow").show();
	$("#editbuttonstyle").show();
	$("#addnewbutton").show();

	$("#neatbbslides").append($("#billcontentrow-default").clone().prop('id', 'billcontentrow' ));

	$("#billcontentrow #billboardlink").prop('id', 'billboardlink1')

	setTimeout(function(){ 
		setbuttoneidtor();
	 },100);
	addlinkeditoptions();
}

function add_parallax() { 
	if($("#parallax").attr("checked")) { 
		$("#neatbbslides").attr("data-parallax","1")
	} else { 
		$("#neatbbslides").attr("data-parallax","0")
	}
}


function addbutton() { 
	$(".billboardbuttons").each(function(i){
		last_id = $(this).attr("id");
	});

	ct = Math.abs($("#billcontentrow").attr("data-button-count"));
	ct = ct + 1;
	$("#billcontentrow #billcontentinner").append($("#billcontentrow #billcontentinner #"+last_id).clone().prop('id', 'billboardlink'+ct ));
	$("#billcontentrow").attr("data-button-count",ct)
	addlinkeditoptions();
}


function addlinkeditoptions() { 
	ct = $("#billcontentrow #billcontentinner .billboardbuttons").length;
	if(ct > 0) { 
		$("#linkedits").html('<div class="linkeditdescr pc"><b>Mange Buttons</b><br>Click the links below to edit or delete the button.</div>');
	} else { 
		$("#linkedits").html('');
	}
	$("#billcontentrow #billcontentinner .billboardbuttons").each(function(i){
		$("#linkedits").append('<div class="pc"><a href="" onclick="editbuttonlink(\''+$(this).attr("id")+'\'); return false;"><span class="the-icons icon-pencil" style="margin-left: -6px;"></span>Link '+(i+1)+': '+$("#billcontentrow #"+$(this).attr("id")+" .billboardbutton .billboardbuttoninner").html()+'</a></div>');
	});
}
function editbuttonlink(id) { 
	$("#billboardedit").attr("data-editing-link",id);
	$("#bc_text_1").val($("#billcontentrow #"+id+" .billboardbutton .billboardbuttoninner").html());
	$("#bc_link_1").val($("#billcontentrow #"+id).attr("href"));
	$("#bc_link_page_1").val($("#billcontentrow #"+id).attr("href"));
	$(".nolinkedit").slideUp(100);
	$("#linkedit").slideDown(100);
}

function finishlinkedit() {
	$(".nolinkedit").slideDown(100);
	$("#linkedit").slideUp(100);
	addlinkeditoptions();
}

function deletebuttonlink() { 
	// alert($("#billboardedit").attr("data-editing-link"));
	// alert($(".billboardbuttons").length);
	if($(".billboardbuttons").length == 2) { 
		deletebuttonrow();
	}
	$("#"+$("#billboardedit").attr("data-editing-link")).remove();
	$(".nolinkedit").slideDown(100);
	$("#linkedit").slideUp(100);
	addlinkeditoptions();
}

function deletebuttonrow() { 
	$("#billboardedit").attr("data-button-row",0);
	$("#billcontentrow").remove();
	$("#addbuttonrow").show();
	$("#deletebuttonrow").hide();
	$("#deletebuttonrow").hide();
	$("#editbuttonstyle").hide();
	$("#addnewbutton").hide();
	$("#buttonstyle").slideUp(100);
	addlinkeditoptions();
}

function savebillboard(id) { 
	$("#bbsaving").show();
	$("#bbsave").hide();
	var fields = {};
	if($("#abs_header").attr("checked")) { 
		fields['abs_header'] = '1';
	} else { 
		fields['abs_header'] = '0';
	}

	if($("#hide_shop_menu").attr("checked")) { 
		fields['hide_shop_menu'] = '1';
	} else { 
		fields['hide_shop_menu'] = '0';
	}
	if($("#billcontentrow").length > 0) { 
		fields['bill_content_row1'] = $("#billcontentrow")[0].outerHTML;
	}
	fields['bill_id'] = $("#previewBillboard").val();
	fields['previewBillboard'] = $("#previewBillboard").val();
	fields['header_opacity'] = $("#header_opacity").val();
	fields['header_bg'] = $("#header_bg").val();
	if($("#abs_header").attr("checked")) { 

		if($("#parallax").attr("checked")) { 
			fields['bill_parallax'] = "1";
		} else { 
			fields['bill_parallax'] = "0";
		}
	} else { 
		fields['bill_parallax'] = "0";
	}
	fields['previewBillboard'] = $("#previewBillboard").val();

	fields['menu_opacity'] = $("#menu_opacity").val();
	fields['menu_bg'] = $("#menu_bg").val();

	fields['updatebillboard'] = "yes";
	$.post('index.php', fields,	function (data) { 
		$("#bbsaving").hide();
		$("#bbsave").show();
		$("#bbsaved").show().delay(2000).fadeOut(500);
	});

}

function bbhelp(id) { 
	$(".bbhelp").hide();
	$("#bbhelp-"+id).show();
	$("#bbhelp").slideDown(200);
}

function bbhelphide() { 
	$("#bbhelp").slideUp(200, function() { 
		$(".bbhelp").hide();
	});
}

</script>

<?php 
if($setup['demo_mode'] !== true) { 
	if($_REQUEST['updatebillboard'] == "yes") { 
		if(empty($bill['bill_id'])) { die("unable to find billboard ID"); } 
		updateSQL("ms_billboards", "abs_header='".$_POST['abs_header']."', 
		hide_shop_menu='".$_POST['hide_shop_menu']."', 
		header_bg='".$_POST['header_bg']."', 
		header_opacity='".$_POST['header_opacity']."', 
		menu_bg='".$_POST['menu_bg']."', 
		menu_opacity='".$_POST['menu_opacity']."',
		bill_parallax='".$_POST['bill_parallax']."',
		bill_content_row1='".addslashes(stripslashes($_POST['bill_content_row1']))."' 
		WHERE bill_id='".$bill['bill_id']."' ");
		exit();
	}
}
?>
<style>
#shopmenucontainer { width: 85%; float: right; position: relative; } 
#page-wrapper { width: 85%; float: right; } 
#billboardedit { width: 15%; float: left; height: 100vh; display: block; position: fixed; font-family: 'Arial' !important; font-size: 13px !important; } 
#billboardedit .pc, #billboardedit div, #billboardedit label { font-family: 'Arial'; font-size: 13px; } 
#billboardedit input, #billboardedit  select, #billboardedit textarea { padding: 4px; font-size: 13px; font-family: 'Arial'; } 
a.savebillboard { padding: 8px; background: #000000; color: #FFFFFF; width: 100%; display: block; box-sizing:border-box; } 
a.savebillboard:hover { background: #0261d6; color: #FFFFFF; } 
.bbmoreinfo { font-size: 12px;  font-style: italic; cursor: pointer; opacity: .8; } 
.brow { margin: 2px 0px 12px 0px; padding: 4px;  } 
.cursor { cursor: pointer; } 
.bbtitle { font-size: 17px; font-weight: bold; letter-spacing: -1px;  } 
</style>
<div id="billboardedit" style="" data-header-background="" data-header-position="" data-header-width="" data-menu-background="" data-menu-transparent="<?php print $css['menu_transparent'];?>" data-button-row="<?php if(!empty($bill['bill_content_row1'])) { ?>1<?php } else { ?>0<?php } ?>" data-editing-link="" >
	<div style="border-right: solid 2px #000000;height: 100%; display: block;">
		<div style="padding: 24px;height: 100%;">
		<div class="pc "><span class="bbtitle">Full Screen Options</span></div>

		<div id="bbhelp" class="hide pc" style="background: #6dc5f3; border: solid 1px #439dcc; color: #FFFFFF; margin-bottom: 16px; position: relative; padding: 16px 8px 8px 8px; box-sizing:border-box; ">
			<div style="position: absolute; right: 2px; top: 2px;"><span class="the-icons icon-cancel" style="color: #FFFFFF; opacity: 1; font-size: 13px;" onclick="bbhelphide(); return false;"></span></div>
			<div id="bbhelp-header" class="bbhelp">This option will place the billboard behind the header and you have the option to adjust the opacity of the header and menu so you can see part of the image behind the header.</div>
			<div id="bbhelp-mini" class="bbhelp">This option will remove the top mini menu (if in use) on the page this is added to.</div>
			<div id="bbhelp-buttons" class="bbhelp">This option will create a row to add a button or buttons which you can link to pages.</div>
			<div id="bbhelp-parallax" class="bbhelp">This option will make the photos in the billboard scroll at a different rate than the rest of the page creating a nice effect.</div>
		</div>
			<div class="notbuttonstyle nolinkedit">

				<div class="brow">
					<input type="checkbox" id="abs_header" value="1" onchange="abs_header();" <?php if($bill['abs_header'] == "1") { ?>checked<?php } ?>> <label for="abs_header" class="cursor">Billboard Behind Header</label><br> <span onclick="bbhelp('header'); return false;" class="bbmoreinfo">more info</span>

				</div>

				<div id="abs_header_options" <?php if($bill['abs_header'] <= 0 ) { ?>class="hide"<?php } ?>>
				<?php if($css['header_transparent'] == "1") { 
						$header_bg = $css['outside_bg'];
					} else { 
						$header_bg = $css['header_bg'];
					}

					// This is to check if the billboard has been edited and to set default opacity
					if(empty($bill['header_bg'])) { 
						$bill['header_opacity'] = .80;
						$bill['menu_opacity'] = 1.00;
					}
					?>

				<div class="brow">
					<input type="checkbox" id="parallax" value="1" onchange="add_parallax();" <?php if($bill['bill_parallax'] == "1") { ?>checked<?php } ?>> <label for="parallax" class="cursor">Parallax Effect</label> <br><span  class="bbmoreinfo" onclick="bbhelp('parallax'); return false;">more info</span>

				</div>

				<div class="pc">
					<div class="left">Header Opacity </div>
					<div class="right"><select name="header_opacity" id="header_opacity" onChange="headerlook();">
					<?php
					$op = 1;
					while($op >=0) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['header_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op * 100;?></option>
					<?php 
					$op = $op - .05;
					}
					?>
					<option value="0" <?php if($bill['header_opacity'] == "0.00") { ?>selected<?php } ?>>0</option>

					</select>
					</div>
					<div class="clear"></div>
				</div>
			


				<?php if($css['menu_transparent'] !== "1") { ?>
				<div class="pc">
					<div class="left">Menu Opacity </div>
					<div class="right">
					<select name="menu_opacity" id="menu_opacity" onChange="headerlook();">
					<?php
					$op = 1;
					while($op >=0) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['menu_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op * 100;?></option>
					<?php 
					$op = $op - .05;
					}
					?>
					<option value="0" <?php if($bill['menu_opacity'] == "0.00") { ?>selected<?php } ?>>0</option>

					</select>
					</div>
					<div class="clear"></div>
				</div>

					<?php } ?>
				</div>
				<div class="brow" >
					<input type="checkbox" id="hide_shop_menu" value="1" onchange="hide_shop_menu();" <?php if($bill['hide_shop_menu'] == "1") { ?>checked<?php } ?>> <label for="hide_shop_menu" class="cursor">Hide Top Mini Menu</label><br><span  class="bbmoreinfo" onclick="bbhelp('mini'); return false;">more info</span>
				</div>
				<div class="brow <?php if(!empty($bill['bill_content_row1'])) { ?>hide<?php } ?>" id="addbuttonrow" ><span onclick="addbuttonrow(); return false;" class="cursor"><span class="the-icons icon-plus-squared" style="margin-left: -6px;"></span>Add Link Buttons</span>
				<br><span  class="bbmoreinfo" onclick="bbhelp('buttons'); return false;">more info</span></div>
				<!-- <div class="pc <?php if(empty($bill['bill_content_row1'])) { ?>hide<?php } ?>" id="deletebuttonrow"><a href="" onclick="deletebuttonrow(); return false;">Delete All Button Links</a></div> -->
				<div class="brow <?php if(empty($bill['bill_content_row1'])) { ?>hide<?php } ?>" id="editbuttonstyle"><span onclick="editbuttonstyle(); return false;" class="cursor"><span class="the-icons icon-palette" style="margin-left: -6px;"></span>Edit Button Style</span></div>
				<div class="brow <?php if(empty($bill['bill_content_row1'])) { ?>hide<?php } ?>" id="addnewbutton"><span onclick="addbutton(); return false;" class="cursor"><span class="the-icons icon-plus-squared" style="margin-left: -6px;"></span>Add Another Button</span></div>
				<!-- <div class="pc"><a href="" onclick="addlinkeditoptions(); return false;">test link edit</a></div> -->
				<div>&nbsp;</div>
				
				
				<div class="" id="linkedits"></div>
			</div>

			<div>&nbsp;</div>
			<script>


			function setbuttoneidtor() { 
					$("#bc_background_color").val($("#billcontentrow").attr("data-bg-color"));
					$("#bc_border_color").val($("#billcontentrow").attr("data-border-color"));
					$("#bc_border_width").val($("#billcontentrow").attr("data-border-width"));
					$("#bc_opacity").val($("#billcontentrow").attr("data-opacity"));
					$("#bc_font_color").val($("#billcontentrow").attr("data-font-color"));
					$("#bc_padding").val($("#billcontentrow").attr("data-padding"));
					$("#bc_border_radius").val($("#billcontentrow").attr("data-border-radius"));
					$("#bc_width").val($("#billcontentrow").attr("data-width"));
					$("#bc_bottom").val($("#billcontentrow").attr("data-bottom"));
					$("#bc_margin").val($("#billcontentrow").attr("data-margin"));
					$("#bc_letter_spacing").val($("#billcontentrow").attr("data-letter-spacing"));


					$("#bc_font_family").val($("#billcontentrow").attr("data-font-family"));
					$("#bc_font_size").val($("#billcontentrow").attr("data-font-size"));
					$("#bc_font_weight").val($("#billcontentrow").attr("data-font-weight"));
					$("#bc_text_1").val($("#billboardbutton1 .billboardbuttoninner").html());
					$("#bc_text_2").val($("#billboardbutton2 .billboardbuttoninner").html());
					$("#bc_text_3").val($("#billboardbutton3 .billboardbuttoninner").html());

					$('.color').each(function() {
						document.getElementById($(this).attr('id')).color.fromString($(this).val());
					}); 
			}

			$(document).ready(function(){
				setTimeout(function(){ 
					setbuttoneidtor();
					addlinkeditoptions();
				 },600);


				$("#bc_text_1").on('change keydown paste input', function(){
					  $("#billcontentrow #"+$("#billboardedit").attr("data-editing-link")+" .billboardbutton .billboardbuttoninner").html($("#bc_text_1").val());
				});

				$("#bc_link_1").on('change keydown paste input', function(){
					  $("#billcontentrow #"+$("#billboardedit").attr("data-editing-link")).attr("href",$("#bc_link_1").val());
				});

			
			
			});

		function changelink(id) { 
			$("#bc_link_"+id).val($("#bc_link_page_"+id).val());
			$("#billcontentrow #"+$("#billboardedit").attr("data-editing-link")).attr("href",$("#bc_link_1").val());

		}
		function editbuttonstyle() {
			$(".notbuttonstyle").slideUp(100);
			$("#buttonstyle").slideDown(100);
		}
		function finishbuttonstyle() {
			$(".notbuttonstyle").slideDown(100);
			$("#buttonstyle").slideUp(100);
		}

			</script>

			<div id="buttonstyle" class="hide">
			<div class="pc">Adjust the style of the buttons using the options below.</div>
				<div class="pc">
					<div class="left">Width</div>
					<div class="right"><select name="bc_width" id="bc_width" onChange="contentrow();">
					<?php
					$op = 50;
					while($op <= 1200) { 
						?>
					<option value="<?php print $op;?>" ><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>

				<div class="pc">
					<div class="left">Padding </div>
					<div class="right">
					<select name="bc_padding" id="bc_padding" onChange="contentrow();">
					<?php
					$op = 0;
					while($op <= 200) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['menu_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>
					</select>
					</div>
					<div class="clear"></div>
				</div>

				<div class="pc">
					<div class="left">Margin</div>
					<div class="right"><select name="bc_margin" id="bc_margin" onChange="contentrow();">
					<?php
					$op = 0;
					while($op <= 200) { 
						?>
					<option value="<?php print $op;?>" ><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>


				<div class="pc">
					<div class="left">Background</div>
					<div class="right textright"><input type="text" size="6" name="bc_background_color" id="bc_background_color" class="color center" value="<?php print $header_bg;?>" onChange="contentrow();"></div>
					<div class="clear"></div>
				</div>



				<div class="pc">
					<div class="left">Border  Color</div>
					<div class="right"><input type="text" size="6" name="bc_border_color" id="bc_border_color" class="color center" value="<?php print $header_bg;?>" onChange="contentrow();"></div>
					<div class="clear"></div>
				</div>

				<div class="pc">
					<div class="left">Border Size</div>
					<div class="right"><select name="bc_border_width" id="bc_border_width" onChange="contentrow();">
					<?php
					$op = 0;
					while($op <= 100) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['menu_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>

				<div class="pc">
					<div class="left">Border Radius</div>
					<div class="right">
					<select name="bc_border_radius" id="bc_border_radius" onChange="contentrow();">
					<?php
					$op = 0;
					while($op <= 200) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['menu_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>


				<div class="pc">
					<div class="left">Opacity </div>
					<div class="right">
					<select name="bc_opacity" id="bc_opacity" onChange="contentrow();">
					<?php
					$op = 1;
					while($op >=0) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['bc_opacity'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op * 100;?></option>
					<?php 
					$op = $op - .05;
					}
					?>
					<option value="0" <?php if($bill['bc_opacity'] == "0.00") { ?>selected<?php } ?>>0</option>

					</select>
					</div>
					<div class="clear"></div>
				</div>


				<div class="pc">
					<div class="left">Font Color</div>
					<div class="right"><input type="text" size="6" name="bc_font_color" id="bc_font_color" class="color center" value="<?php print $header_bg;?>" onChange="contentrow();"></div>
					<div class="clear"></div>
				</div>

				<div class="pc">
						<div class="left">Font Family</div>
						<div class="right" style="width: 40%; overflow: hidden;">
						<select name="bc_font_family" id="bc_font_family" style="width: 100%;"  onChange="contentrow();">
							<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$css['css_id']."' ORDER BY font ASC ");
							if(mysqli_num_rows($fonts) > 0) { ?>
						<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>

							<?php } 
							while($font = mysqli_fetch_array($fonts)) { 
								$f = explode(":",$font['font']);
								?>
						<option style="font-family: <?php print $f[0];?>;" value="<?php print $f[0];?>" <?php if($slide['slide_text_1_font'] == $f[0]) { print "selected"; } ?>><?php print $f[0];?></option>
						<?php } ?>
						<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
					<?php
					$sfonts = explode("\r\n",$site_setup['standard_fonts']);
					foreach($sfonts AS $sfont) {  ?>
						<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($slide['slide_text_1_font'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
					<?php } ?>
					
						</select>
						</div>
					<div class="clear"></div>
				</div>


				<div class="pc">
					<div class="left">Font Size</div>
					<div class="right"><select name="bc_font_size" id="bc_font_size" onChange="contentrow();">
					<?php
					$op = 9;
					while($op <= 120) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['bc_font_size'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="pc">
					<div class="left">Letter Spacing</div>
					<div class="right"><select name="bc_letter_spacing" id="bc_letter_spacing" onChange="contentrow();">
					<?php
					$op = -100;
					while($op <= 100) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['bc_font_size'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>

				<div class="pc">
					<div class="left">Font Weight</div>
					<div class="right"><select name="bc_font_weight" id="bc_font_weight" onChange="contentrow();">
					<?php
					$op = 100;
					while($op <= 900) { 
						?>
					<option value="<?php print $op;?>" <?php if(($bill['bc_font_size'] * 1) == number_format($op,2)) { ?>selected<?php } ?>><?php print $op;?></option>
					<?php 
					$op = $op + 100;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>




				<div class="pc">
					<div class="left">% From Bottom</div>
					<div class="right"><select name="bc_bottom" id="bc_bottom" onChange="contentrow();">
					<?php
					$op = 0;
					while($op <= 100) { 
						?>
					<option value="<?php print $op;?>" ><?php print $op;?></option>
					<?php 
					$op++;
					}
					?>

					</select>
					</div>
					<div class="clear"></div>
				</div>

			<div>&nbsp;</div>
			<div class="pc center"><a href="" onclick="finishbuttonstyle(); return false;"  class="savebillboard">Done Editing</a></div>
		</div>



		<div id="linkedit" class=" hide">
			<div class="pc">
				<div>Button Text</div>
				<div><textarea id="bc_text_1" value="" class="field100 center" rows="2"></textarea></div>
			</div>

			<div>
				<div class="pc">Link to: </div>
				<div class="pc">
				<select id="bc_link_page_1" onchange="changelink('1');">
				<option value="0">Select Category or Page</option>
				<option value="" disabled>CATEGORY</option>
				<?php $cats = whileSQL("ms_blog_categories", "*", "WHERE cat_under<='0' ORDER BY cat_name ASC ");
				while($cat = mysqli_fetch_array($cats)) { ?>
				<option value="<?php print $setup['temp_url_folder'].$cat['cat_folder'];?>/"><?php print $cat['cat_name'];?></option>

				<?php } ?>
				<option value="" disabled>TOP LEVEL PAGE</option>
				<?php $bdates = whileSQL("ms_calendar", "*", "WHERE date_cat<='0' AND page_404!='1' AND green_screen_gallery!='1' ORDER BY date_title ASC ");
				while($bdate = mysqli_fetch_array($bdates)) {
					
					if($bdate['page_home'] == "1") { 
						$bdate['date_link'] = "";
					}
					?>
				<option value="<?php if($bdate['page_home'] == "1") { print ""; } else { print $setup['temp_url_folder']."/".$bdate['date_link']; } ?>/"><?php print $bdate['date_title'];?></option>

				<?php } ?>
				<option value="" disabled>FUNCTION</option>
				<option value="javascript:scrolltocontent()">* Scroll To Content</option>

				</select>
				</div>
				<div class="pc">Or enter in your own URL</div>
				<div class="pc"><input type="text" id="bc_link_1" value="" class="field100"></div>
			<div class="pc">* <i>The scroll to content option will scroll down to under the photo when button clicked.</i></div>

				<div>&nbsp;</div>

				<div class="pc center"><a href="" onclick="finishlinkedit(); return false;"  class="savebillboard">Done Editing</a></div>

				<div class="pc center"><a href="" onclick="deletebuttonlink(); return false;"><span class="the-icons icon-trash-empty"></span> Delete this link</a></div>
			</div>
		</div>

		<div class="notbuttonstyle nolinkedit">
		<!-- <div class="pc">	<input id="hold" type="text" minlength="5" maxlength="30" placeholder="Name On Card :"  name="hold" class="dark" required title="Please Enter Card Holder"></div> -->
			<input type="hidden" name="previewBillboard" id="previewBillboard" value="<?php print $bill['bill_id'];?>">
			<input type="hidden"  name="header_bg" id="header_bg" value="<?php print $header_bg;?>">
			<input type="hidden"  name="menu_bg" id="menu_bg" value="<?php print $css['menu_color'];?>">
			<input type="hidden"  name="menu_border_a" id="menu_border_a" value="<?php print $css['menu_border_a'];?>">
			<input type="hidden"  name="menu_border_b" id="menu_border_b" value="<?php print $css['menu_border_b'];?>">

			<div class="pc hide center" id="bbsaving"><div class="loadingspinnersmall"></div></div>
			<div class="pc center" id="bbsave" style="margin: 24px 0px; ">
				<a href="" onclick="savebillboard('<?php print $bill['bill_id'];?>'); return false" class="savebillboard">Save Settings</a>
			</div>
			<div id="bbsaved" class="pc center hide" style="color: #008900;">Setting Saved</div>

			<div class="pc center"><a href="" onclick="previewMobile('<?php print $bill['bill_id'];?>'); return false;">Preview Mobile</a><br><span style="font-size: 12px;"><i>Save any changes before previewing mobile.</i></span></div>
			</div>
		</div>
	</div>
</div>


<!-- ## Default content row --> 

<div id="defaultbuttons3" class="hide">
	<div id="billcontentrow-default" style="position: absolute; width: 100%; z-index: 10; bottom: 26%;" data-bg-color="000000" data-border-color="FFFFFF" data-border-width="1" data-opacity="0.65" data-border-radius="0" data-box-shadow="" data-padding="12" data-font-color="FFFFFF" data-font-family="Arial" data-font-size="20" data-font-weight="100" data-width="220" data-bottom="26" data-margin="8" data-letter-spacing="0"  data-button-count="1">
		<div id="billcontentinner" style="position: relative; text-align: center; width: 100%;">
			<a href="/" id="billboardlink" class="billboardbuttons" ><div class="billboardbutton" id="billboardbutton1" style="margin: 8px; background: rgba(0, 0, 0, 0.65098); color: rgb(255, 255, 255); display: inline-block; border: 1px solid rgba(255, 255, 255, 0.65098); border-radius: 0px; max-width: 220px; width: 100%;">
				<div class="billboardbuttoninner" style="padding: 12px; text-align: center; color: rgb(255, 255, 255); font-family: Arial; font-size: 20px; font-weight: 100; letter-spacing: 0px;">BUTTON TEXT</div>
			</div></a></div>
	</div>
</div>


<!-- ## Default Find my photos --> 

<div id="defaultfindphotos" class="hide">
	<div id="billfindphotos-default" style="position: absolute; width: 100%; z-index: 10; bottom: 26%;" data-bg-color="000000" data-border-color="FFFFFF" data-border-width="1" data-opacity="0.65" data-border-radius="0" data-box-shadow="" data-padding="12" data-font-color="FFFFFF" data-font-family="Arial" data-font-size="20" data-font-weight="100" data-width="220" data-bottom="26" data-margin="8" data-letter-spacing="0"  data-button-count="1">
		<div id="billcontentinner" style="position: relative; text-align: center; width: 100%;">

			<div class="billboardbutton" id="billboardbutton1" style="margin: 8px; background: rgba(0, 0, 0, 0.65098); color: rgb(255, 255, 255); display: inline-block; border: 1px solid rgba(255, 255, 255, 0.65098); border-radius: 0px; max-width: 220px; width: 100%;">
				<div class="billboardbuttoninner" style="padding: 12px; text-align: center; color: rgb(255, 255, 255); font-family: Arial; font-size: 20px; font-weight: 100; letter-spacing: 0px;">BUTTON TEXT</div>
			</div></div>
	</div>
</div>