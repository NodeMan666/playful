<?php require "w-header.php"; ?>
<?php $css = doSQL("ms_css, ms_css2", "*", "WHERE css_id='".$site_setup['css']."' "); ?>
<script language="javascript"  type="text/javascript" src="<?php tempFolder();?>/sy-inc/js/billboard.js?<?php print MD5($site_setup['sytist_version']);?>"></script>
<div class="pc left"><h2>Edit Billboard Slide</h2></div>
<div class="pc right" id="cords"></div>
<div class="clear"></div>
<?php
$slide = doSQL("ms_billboard_slides LEFT JOIN ms_photos  ON ms_billboard_slides.slide_pic=ms_photos.pic_id", "*", "WHERE slide_id='".$_REQUEST['editslide']."' ORDER BY slide_order ASC");
$billboard_ss_id = "ssbill_".$billboard['bill_id']."";
$billboard  = 	doSQL("ms_billboards",  "*","WHERE bill_id='".$slide['slide_billboard']."' ");
$billboard_slides = 1;
if($_REQUEST['action'] == "updateText") { 
	if($setup['demo_mode'] !== true) { 
	$val = str_replace("aLeft","<",$_POST['val']);
	$val = str_replace("aRight",">",$val);
	print_r($_POST);

	print "############ ".$val." ################ WHERE slide_id='".$slide['slide_id']."'  ";
	updateSQL("ms_billboard_slides", "slide_text1='".addslashes(stripslashes($val))."', slide_link='".addslashes(stripslashes($_REQUEST['slide_link']))."' WHERE slide_id='".$slide['slide_id']."' ");
	}
	exit();
}

$totaltext = 0;
$val = array();
$slides = explode("|:|", $slide['slide_text1']);
foreach($slides AS $ss) { 
	if(!empty($ss)) { 
		$ssx = explode("||", $ss);
		foreach($ssx AS $vals) {
			list($id,$v) = explode("::",$vals);
			$val[$id] = $v;
		//	print "<li>v = $v";
		}
		if(!empty($val['text'])) { 
			$effect[$val['id']] = $val['id']."|".$val['slide_text_1_effect']."|".$val['slide_text_1_time'];
		}
	}
}
$slides = explode("|:|", $slide['slide_text1']);
foreach($slides AS $ss) { 
	if(!empty($ss)) { 
		$totaltext++;
	}
}


?>
<style>
#billboardContainer { 
	width: 100%;
	margin: 0 auto 0 auto;
	display: block;
	padding: 0;
	background: #890000;
	text-align: center;
}


#billboard { 
	display: block;
	float: left;
}
.billtextinner { text-align: center; } 
#neatbb { margin: auto; position: relative;} 
#neatbbslides { position: relative;  overflow: hidden; text-align:center;} 

.neatbbslide { display: none; position: absolute; } 
.neatbbslide1 {position: absolute; display: none; } 
#neatbbmenu { text-align: center; padding: 6px; position: absolute; z-index: 2; bottom: 0; right: 0; } 
#neatbbmenu a {  padding: 1px 4px; margin: 2px; 
	-moz-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-goog-ms-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);

}  
.texthandle { cursor: move; width: 12px; height: 12px; padding: 0px; margin: 0px; background:url('graphics/clear.gif'); } 
.texthandlemove { display: none; width: 12px; height:12px; border-radius: 6px; background: #f4f4f4; border: solid 1px #949494; box-shadow: 1px 1px 2px #000000; } 
#selectgraphic { top: 5%; position: fixed; z-index: 1000; width: 600px; left: 50%; margin-left: -300px; overflow-y: scroll; background: #FFFFFF; border: solid 1px #949494; display: none; height: 90%;  	box-shadow: 0px 0px 20px rgba(0, 0, 0, .8); } 
#selectgraphic.loading { background:  url('graphics/loading1.gif') no-repeat center center; width: 100%; height: 100%;  } 

</style>

<script>
var numslides = <?php print $billboard_slides;?>;
var bbheight = <?php print $billboard['bill_height'];?>;
var bbwidth = <?php print $billboard['bill_width'];?>;
var bbspeed = <?php print $billboard['bill_trans_time'];?>;
var bbtime = <?php print $billboard['bill_seconds']*1000;?>;
var loop = <?php if($billboard['bill_loop'] == "1") { ?>true<?php } else { ?>false<?php } ?>;
var trans = '<?php print $billboard['bill_transition'];?>';
var timeron = true;
var stopbb;
var bbslidetimer;
numtexts = <?php print $totaltext;?>;

$(document).ready(function(){

	$(".incdec").click(
		  function () {
		var targid = $(this).attr("targetid");
		if($("#"+targid).attr("decimal") == "1") { 
			var increment = Math.abs($("#"+targid).attr("increment"));
			var max =  Math.abs($("#"+targid).attr("max"));
			var min =  Math.abs($("#"+targid).attr("min"));
			var val = Math.abs($("#"+targid).val());
		} else { 
			var increment = Math.round($("#"+targid).attr("increment"));
			var max =  Math.round($("#"+targid).attr("max"));
			var min =  Math.round($("#"+targid).attr("min"));
			var val = Math.round($("#"+targid).val());
		}
		if($(this).attr("acdc") == "1") { 
			if((val + increment) <= max) { 
				if($("#"+targid).attr("decimal") == "1") { 
					newval = val + increment;
					n = newval.toFixed(1);
					$("#"+targid).val((n));

				} else { 
					$("#"+targid).val((val + increment));
				}
			}
		} else { 
			if((val - increment) >= min) { 
				if($("#"+targid).attr("decimal") == "1") { 
					newval = val - increment;
					n = newval.toFixed(1);
					$("#"+targid).val((n));
				} else { 
					$("#"+targid).val((val - increment));
				}
			}
		}
	});


	if(numslides > 0) { 
		$('#slide1g').imagesLoaded(function() {
			resizeImgToBillboard($("#slide1g"),"neatbb");
			<?php if(($billboard['bill_fixed'] <=0)&&($billboard['bill_placement'] == "belowmenu")==true) { ?>
//			$("#billtext").css('left', ($(window).width() - <?php print $billboard['bill_width'];?>)/2 + 10);
			<?php } ?>
			$("#slide1").fadeIn(bbspeed, function() {
				previewEffects();
			  });

			$("#neatbbslides").attr("cbb","1");
			if(numslides > 1) { 
				bbslidetimer = setTimeout(function(){   neatbbswap(2) },bbtime);
			}
		});
	}

	$('.textsubmenu').bind('click', function() {
		$(this).find('.textsubopen').fadeIn(100, function() {  
		 $(this).click(function(event){
			 event.stopPropagation();
		 });

		$('html').click(function() {
			 closetextsubmenu();
		 });
		});

	});



	makeitdrag();
	hovermove();
	clickgettextid();
 });

 function hovermove() { 
	$(".billtextinner, .texthandle").hover(
	  function () {
		$(this).find('.texthandlemove').show();
	  },
	  function () {
		$(this).find('.texthandlemove').hide();
	  }
	);


 }

function clickgettextid() { 
	$('.billtextinner, .texthandle').mousedown(function() {
		$("#texteditor").show();
		if($("#text"+$(this).attr("textid")).attr("type") == "text") { 
			$(".texteditor").show();
		} else { 
			$(".texteditor").hide();
		}
		$("#current_text").val($(this).attr("textid"));
		$("#current_text_text").show();
		$("#current_text_num").html($(this).attr("textid"));

		$("#slide_text_1_color").val($("#text"+$(this).attr("textid")).attr("color"));
		if($("#text"+$(this).attr("textid")).attr("font-weight") == "bold") { 
			$("#bold").attr("checked", "checked");
		} else { 
			$("#bold").attr("checked", false);
		}
		if($("#text"+$(this).attr("textid")).attr("font-style") == "italic") { 
			$("#italic").attr("checked", "checked");
		} else { 
			$("#italic").attr("checked", false);
		}

		$("#slide_text_1_font").val($("#text"+$(this).attr("textid")).attr("font-family"));
		$("#slide_text_1_size").val($("#text"+$(this).attr("textid")).attr("font-size"));
		$('#slide_text_1_shadow_h').val($("#text"+$(this).attr("textid")).attr("text-shadow-h"));
		$('#slide_text_1_shadow_v').val($("#text"+$(this).attr("textid")).attr("text-shadow-v"));
		$('#slide_text_1_shadow_b').val($("#text"+$(this).attr("textid")).attr("text-shadow-b"));
		$('#slide_text_1_shadow_c').val($("#text"+$(this).attr("textid")).attr("text-shadow-c"));

		$('#slide_text_1_time').val($("#text"+$(this).attr("textid")).attr("slide_text_1_time"));
		$('#slide_text_1_effect').val($("#text"+$(this).attr("textid")).attr("slide_text_1_effect"));

		$(".color").each(function() {
			this_id = $(this).attr("id");
			thiscolor = $(this).attr("thisval");
			var myPicker = new jscolor.color(document.getElementById(this_id), {})
		});
	});
}

function makeitdrag() { 
$( ".billtextinner" ).draggable({ 
		containment: "#billtext",
		scroll: false,
		handle: ".texthandle",
		drag: function() {
			var $this = $(this);
			var thisPos = $this.position();
			var parentPos = $this.parent().position();

			var x = thisPos.left - parentPos.left;
			var y = thisPos.top - parentPos.top;
			
			xp = (x / $("#billtext").width()) * 100;
			yp = (y / $("#billtext").height()) * 100;
			if(xp < 0) { 
				xp = 0;
			}
			if(yp < 0) { 
				yp = 0;
			}

			 $("#cords").show().html(x + "x " + y);

			$("#text"+$("#current_text").val()).attr("x", xp);
			$("#text"+$("#current_text").val()).attr("y", yp);

			$("#slide_left_margin").val(x);
			$("#slide_top_margin").val(y);
		}		
	
	
	});
}
function closetextsubmenu() { 
	$(".textsubopen").hide();
	$('html').unbind('click');
	delete jscolor.picker.owner;
	document.getElementsByTagName('body')[0].removeChild(jscolor.picker.boxB);
}


$(document).ready(function(){
	$(".color").each(function() {
		this_id = $(this).attr("id");
		thiscolor = $(this).attr("thisval");
		var myPicker = new jscolor.color(document.getElementById(this_id), {})
//		myPicker.fromString("000000")  // now you can access API via 'myPicker' variable
	});

});
function addnewtext() { 
	$("#texteditor").show();
	$(".texteditor").show();
	var newtexts = Math.abs(numtexts)+1;
	$("#current_text_text").show();
	$("#current_text_num").html(newtexts);

	var $html = '<div id="billtext'+newtexts+'" textid="'+newtexts+'"  style="position: absolute; left: 0; top: 0;" class="billtextinner"><div contenteditable  type="text" id="text'+newtexts+'" textid="'+newtexts+'" class="thebbtext left" font-size="21" color="'+$("#slide_text_1_color").val()+'" text-shadow-h="1" text-shadow-v="1" text-shadow-b="1" text-shadow-c="000000" style="font-size: 21px; color: #'+$("#slide_text_1_color").val()+';"><p>Click to edit</p></div><div class="texthandle left"><div class="texthandlemove">&nbsp;</div></div></div>';
	$("#billtext").append($html);
	//$("#billtext"+newtexts).draggable();
	makeitdrag();
	hovermove();
	$("#current_text").val(newtexts);
	$("#text_ids").val($("#text_ids").val()+","+newtexts);
	clickgettextid();

	numtexts = newtexts;
}

function addnewimage(image) { 
	$("#texteditor").show();
	$(".texteditor").hide();

	var newtexts = Math.abs(numtexts)+1;
	$("#current_text_text").show();
	$("#current_text_num").html(newtexts);

	var $html = '<div id="billtext'+newtexts+'" textid="'+newtexts+'" style="position: absolute; left: 0; top: 0;" class="billtextinner"><div type="graphic" id="text'+newtexts+'" textid="'+newtexts+'" class="thebbtext left" font-size="21" color="'+$("#slide_text_1_color").val()+'" text-shadow-h="0" text-shadow-v="0" text-shadow-b="0" text-shadow-c="000000" style="font-size: 21px; color: #'+$("#slide_text_1_color").val()+';"><p><img src="'+image+'" border="0"></p></div></div>';
	$("#billtext").append($html);
	//$("#billtext"+newtexts).draggable();
	makeitdrag();
	hovermove();
	$("#current_text").val(newtexts);
	$("#text_ids").val($("#text_ids").val()+","+newtexts);
	clickgettextid();

	numtexts = newtexts;
}

	function updateBillboardPreview() { 
		//$("#text1a").html($("#slide_text1").val());
		//$("#text1b").html($("#slide_text2").val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '<br />'));

		$("#text"+$("#current_text").val()).css('color',"#"+$("#slide_text_1_color").val());
			$("#text"+$("#current_text").val()).attr("color", $("#slide_text_1_color").val());

		$("#text"+$("#current_text").val()).css('font-family',$("#slide_text_1_font").val());
			$("#text"+$("#current_text").val()).attr("font-family", $("#slide_text_1_font").val());

		$("#text"+$("#current_text").val()).css('font-size',$("#slide_text_1_size").val()+"px");
				$("#text"+$("#current_text").val()).attr("font-size", $("#slide_text_1_size").val());

		if($("#bold").attr("checked")) { 
			$("#text"+$("#current_text").val()).css("font-weight", "bold");
			$("#text"+$("#current_text").val()).attr("font-weight", "bold");
		} else { 
			$("#text"+$("#current_text").val()).css("font-weight", "normal");
			$("#text"+$("#current_text").val()).attr("font-weight", "normal");
		}

		if($("#italic").attr("checked")) { 
			$("#text"+$("#current_text").val()).css("font-style", "italic");
			$("#text"+$("#current_text").val()).attr("font-style", "italic");
		} else { 
			$("#text"+$("#current_text").val()).css("font-style", "normal");
			$("#text"+$("#current_text").val()).attr("font-style", "normal");
		}

		$("#text"+$("#current_text").val()).css('text-shadow', $('#slide_text_1_shadow_h').val()+'px '+ $('#slide_text_1_shadow_v').val()+'px '+$('#slide_text_1_shadow_b').val()+'px  #'+$('#slide_text_1_shadow_c').val());
				$("#text"+$("#current_text").val()).attr("text-shadow-h", $("#slide_text_1_shadow_h").val());
				$("#text"+$("#current_text").val()).attr("text-shadow-v", $("#slide_text_1_shadow_v").val());
				$("#text"+$("#current_text").val()).attr("text-shadow-b", $("#slide_text_1_shadow_b").val());
				$("#text"+$("#current_text").val()).attr("text-shadow-c", $("#slide_text_1_shadow_c").val());

				$("#text"+$("#current_text").val()).attr("slide_text_1_time", $("#slide_text_1_time").val());
				$("#text"+$("#current_text").val()).attr("slide_text_1_effect", $("#slide_text_1_effect").val());


//		$("#billtext").css('text-align',$("#slide_text_align").val());
//		$("#billtextinner").css('top',$("#slide_top_margin").val()+"px");
//		$("#billtextinner").css('left',$("#slide_left_margin").val()+"px");




	}

	function showEffect() { 
		$("#billtext"+$("#current_text").val()).hide();
		var thisspeed1 = Math.abs($("#slide_text_1_time").val());

		showAnimation($("#current_text").val(),thisspeed1,$("#slide_text_1_effect").val())
		
	}

	function previewEffects() { 
		to = 0;
		var timer = new Array();
		$(".billtextinner").hide();
		$(".thebbtext").each(function() {
			tid = $(this).attr("textid");
			time = Math.abs($(this).attr("slide_text_1_time"));
			effect = $(this).attr("slide_text_1_effect");
			previeweffecttimeout(tid,to,time,effect)
			to = to + time;
		});

	}

function previeweffecttimeout(tid,to,time,effect) { 
	var timer = new Array();
	timer[tid] = setTimeout(function() {
		showAnimation(tid,time,effect);
	}, to);
}

function showAnimation(tid,time,effect) { 
		if(effect == "fadeIn") {
			$("#billtext"+tid).fadeIn(time);
		}
		if(effect == "slideDown") {
			$("#billtext"+tid).slideDown(time);
		}
		if(effect == "showleft") {
			$("#billtext"+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "left":"-=60"});
			$("#billtext"+tid).animate({"opacity":"1", "left":"+=60"},time, "easeOutBack");
		}

		if(effect == "showright") {
			$("#billtext"+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "left":"+=60"});
			$("#billtext"+tid).animate({"opacity":"1", "left":"-=60"},time, "easeOutBack");
		}
		if(effect == "showtop") {
			$("#billtext"+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "top":"-=60"});
			$("#billtext"+tid).animate({"opacity":"1", "top":"+=60"},time, "easeOutBack");
		}

		if(effect == "showbottom") {
			$("#billtext"+tid).css({"display":"inline","opacity":"0", "visibility":"visible", "top":"+=60"});
			$("#billtext"+tid).animate({"opacity":"1", "top":"-=60"},time, "easeOutBack");
		}

}


$(document).ready(function(){
	setInterval("updateBillboardPreview()",100);

});

function gethtml() { 
		var	str="";
		var num = 1;

		$(".thebbtext").each(function() {
			if($(this).html()!=="") { 
				str = str + "id::"+num+"||color::"+$(this).attr("color")+"||text::"+$(this).html()+"||font-family::"+$(this).attr("font-family")+"||font-size::"+$(this).attr("font-size")+"||font-weight::"+$(this).attr("font-weight")+"||font-style::"+$(this).attr("font-style")+"||text-shadow-h::"+$(this).attr("text-shadow-h")+"||text-shadow-v::"+$(this).attr("text-shadow-v")+"||text-shadow-b::"+$(this).attr("text-shadow-b")+"||text-shadow-c::"+$(this).attr("text-shadow-c")+"||slide_text_1_time::"+$(this).attr("slide_text_1_time")+"||slide_text_1_effect::"+$(this).attr("slide_text_1_effect")+"||x::"+$(this).attr("x")+"||y::"+$(this).attr("y")+"||type::"+$(this).attr("type")+"|:|";
				num = num+1;
			}
		});
		$('#submitButton').val("saving...");
		$('#submitButton').removeClass("submit").addClass("submitsaving");
		str=str.replace(/</g,"aLeft");
		str=str.replace(/>/g,"aRight");
		//str=encodeURIComponent(str);
	//	alert(str);
		var fields = {};

		fields['editslide'] = '<?php print $slide['slide_id'];?>';
		fields['action'] = 'updateText';
		fields['slide_link'] = $("#slide_link").val();
		fields['val'] = str;

		$.post("w-billboard-slide.php", fields,	function (data) { 
			// alert(data);
			showSuccessMessage("Slide Saved");
			setTimeout(hideSuccessMessage,4000);
			$('#submitButton').val("Save");
			$('#submitButton').removeClass("submitsaving").addClass("submit");

		});

	//	alert(str);
	// alert($("#billtext").html());
}

function removetext() { 
	$("#billtext"+$("#current_text").val()).remove();
	$("#texteditor").hide();
	$("#current_text").val("0");
	$("#current_text_text").hide();
	$("#current_text_num").html("");

}



function opengraphics() { 
	$("#selectgraphic").fadeIn('fast', function() {
		$.get("w-billboard-graphics.php?noclose=1", function(data) {
			$("#selectgraphic").html(data);
	//		setTimeout(hideLoadingMore,1000);
		});
	});
}


</script>


<div id="selectgraphic">
<div  class="loading"></div>
</div>


<?php 
$slides = explode("|:|", $slide['slide_text1']);
foreach($slides AS $ss) { 
	if(!empty($ss)) { 
		$ssx = explode("||", $ss);
		foreach($ssx AS $vals) {
			list($id,$v) = explode("::",$vals);
			$val[$id] = $v;
		//	print "<li>v = $v";
		}
		$h .= '<div id="billtext'.$val['id'].'" textid="'.$val['id'].'" style="position: absolute; left: '.$val['x'].'%; top: '.$val['y'].'%; display: none;" class="billtextinner"><div id="text'.$val['id'].'" textid="'.$val['id'].'" type="'.$val['type'].'" color="'.$val['color'].'" font-family="'.$val['font-family'].'" font-size="'.$val['font-size'].'" font-weight="'.$val['font-weight'].'" font-style="'.$val['font-style'].'" text-shadow-h="'.$val['text-shadow-h'].'" text-shadow-v="'.$val['text-shadow-v'].'" text-shadow-b="'.$val['text-shadow-b'].'" text-shadow-c="'.$val['text-shadow-c'].'" slide_text_1_time="'.$val['slide_text_1_time'].'" slide_text_1_effect="'.$val['slide_text_1_effect'].'" x="'.$val['x'].'" y="'.$val['y'].'" class="thebbtext" style="color: #'.$val['color'].'; font-size: '.$val['font-size'].'px; font-family: '.$val['font-family'].'; font-weight: '.$val['font-weight'].'; font-style: '.$val['font-style'].'; text-shadow: '.$val['text-shadow-h'].'px '.$val['text-shadow-v'].'px '.$val['text-shadow-b'].'px #'.$val['text-shadow-c'].'; float: left;" ';
		
		if($val['type'] == "text") { $h.='contenteditable'; } 
		$h.= '>'.$val['text'].'</div>';
		if($val['type'] == "text") { $h.='<div class="texthandle left"><div class="texthandlemove">&nbsp;</div></div>'; }
		$h .= '</div>';

	}
}
?>


<div id="billboardContainer" style="width: 1024px; margin: auto;">
<div id="billboard" style="width: 100%;"><div id="neatbb" style="width: 100%;">
	<div id="neatbbslides" data-admin="1" data-parallax="<?php print $billboard['bill_parallax'];?>" cbb="" timeron="1" numslides="<?php print $billboard_slides;?>"  bbheight="<?php print $billboard['bill_height'];?>" bbwidth="<?php print $billboard['bill_width'];?>" bbspeed="<?php print $billboard['bill_trans_time'];?>" bbtime="<?php print $billboard['bill_seconds']*1000;?>" loopslides="<?php if($billboard['bill_loop'] == "1") { ?>true<?php } else { ?>false<?php } ?>" trans="<?php print $billboard['bill_transition'];?>" innermaxwidth="<?php print $css['page_width_max'];?>" bh="<?php print $billboard['bill_height'];?>" bill_placement = "<?php print $billboard['bill_placement'];?>" style="height: <?php print $billboard['bill_height'];?>px;">
	<?php 
	$s = 1;
		if(($billboard['bill_cat'] > 0)||($billboard['bill_page'] > 0)==true) {
			$dsize = @GetImageSize("".$setup['path']."/".$setup['manage_folder']."/graphics/photo-large.jpg"); 
		} else { 
			$dsize = getimagefiledems($slide,selectPhotoFile($billboard['bill_pic'],$slide));
		}
		?>
		<div id="slide<?php print $s;?>" class="<?php if($s == 1) { print "neatbbslide"; } else { print "neatbbslide"; } ?>" bbspeed="400">
			<img id="slide<?php print $s;?>g" src="<?php if(($billboard['bill_cat'] > 0)||($billboard['bill_page'] > 0)==true) { ?>graphics/photo-large.jpg<?php } else { ?><?php print getimagefile($slide,selectPhotoFile($billboard['bill_pic'],$slide));?><?php } ?>" width="<?php print $dsize[0];?>" height="<?php print $dsize[1];?>" border="0" ww="<?php print $dsize[0];?>" hh="<?php print $dsize[1];?>">
		</div>
		<?php 
			$s++;

	
	 ?>

	</div>
	<?php if(($billboard['bill_show_nav'] == "1") &&($billboard_slides > 1)==true){ ?>
	<div id="neatbbmenu">
	<?php 
	$x = 1;
	while($x <= $billboard_slides) { ?>
	<a href="" onClick="neatbbswapclick('<?php print $x;?>'); return false;" id="bn<?php print $x;?>" class="bn <?php if($x == 1) { print "bnon"; } ?>"><?php print $x;?></a> 
	<?php $x++;
	} ?>
	</div>
	<?php } ?>
	<div id="billtext" style="position: absolute; top: 0; left: 0;z-index: 2; width: 1024px; height: <?php print $billboard['bill_height'];?>px;  margin: auto; border: solid 1px #890000; text-align: center;">
		<?php print $h;?>		

	</div>
</div></div></div>
<div class="clear"></div>










<?php if($billboard['bill_cat'] > 0) { ?><div class="pc center">[TITLE] will be replaced with the page title. [TEXT] will be replaced with the page preview text</div><?php } ?>

<?php if($billboard['bill_placement'] == "full") { ?>
<div>&nbsp;</div><div class="error center">This billboard is set to full screen. It is recommended that you don't add text to slides with the full screen option because it does not have a fixed ratio. If you do add text, only add one line.</div><?php } ?> 

<div style="margin: 8px; 0;">
<div style="float: left; width: 33%;"><a href="" onclick="addnewtext(); return false;">Add Text</a>  &nbsp;  <!-- <a href="" onclick="opengraphics(); return false;">Add Graphic</a> --></div>
<div style="float: left; width: 34%; text-align: center;">
<span id="current_text_text" style="display: none;">Editing  #<span id="current_text_num"></span> <a href="" onclick="removetext(); return false;">delete</a></span> &nbsp; 
</div>

<div style="float: left; width: 33%; text-align: right;">

<a href="" onclick="previewEffects(); return false;">Preview Animations</a>
<input type="hidden" size="12" id="current_text" name="current_text" value="0">
<input type="hidden" size="12" id="text_ids" name="text_ids" value="1">
</div>

<div class="clear"></div>
</div>



	<form name="register" action="index.php" method="post" style="padding:0; margin: 0;">


		<div>
			<div class="" id="texteditor" style="display: none; background: #e4e4e4; border: solid 1px #d4d4d4; padding: 8px; text-align: center;">

				<div style="float: left; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; " class="texteditor">
				<div >Color</div>
				<div><input type="text"  size="10" name="slide_text_1_color" id="slide_text_1_color" value="c4c4c4" class="color center" thisval="c4c4c4"></div>
				</div>

				<div style="float: left; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; " class="texteditor">
					<div>Font Family</div>
					<div>
					<select name="slide_text_1_font" id="slide_text_1_font">
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
				</div>

				<div style="float: left; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; " class="texteditor">

				<div>Font Size</div>
				<div>
				<a href="" onClick="return false;" class="incdec" acdc="0" targetid="slide_text_1_size"><?php print ai_arrow_down;?></a><input class="center" title="" type="text" size="2" name="slide_text_1_size" id="slide_text_1_size" value="30" max="200" min="1" increment="1" decimal="0"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="slide_text_1_size"><?php print ai_arrow_up;?></a>
				</div>
			</div>
				<div style="float: left; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; " class="texteditor">
				<div><input type="checkbox" id="bold" name="bold" value="1"> Bold</div>
				<div><input type="checkbox" id="italic" name="italic" value="1"> Italic</div>
				</div>

			<div style="float: left; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; " class="texteditor">
			<div>Text Shadow</div>
			<div>
			<?php 
			$ts = explode(" ",$slide['slide_text_1_shadow']);
			if(is_array($ts)) { 
				$slide_text_1_shadow_h = $ts[0];
				$slide_text_1_shadow_v = $ts[1];
				$slide_text_1_shadow_b = $ts[2];
				$slide_text_1_shadow_c = $ts[3];
			}
			?>

			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="slide_text_1_shadow_h"><?php print ai_arrow_down;?></a><input class="inputtip center" title="Horizontal Offset" type="text" size="2" name="slide_text_1_shadow_h" id="slide_text_1_shadow_h" value="1" max="30" min="-30" increment="1" decimal="0"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="slide_text_1_shadow_h"><?php print ai_arrow_up;?></a>


			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="slide_text_1_shadow_v"><?php print ai_arrow_down;?></a><input class="inputtip center" title="Verticle Offset" type="text" size="2" name="slide_text_1_shadow_v" id="slide_text_1_shadow_v" value="1" max="30" min="-30" increment="1" decimal="0"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="slide_text_1_shadow_v"><?php print ai_arrow_up;?></a>


			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="slide_text_1_shadow_b"><?php print ai_arrow_down;?></a><input class="inputtip center" title="Blur" type="text" size="2" name="slide_text_1_shadow_b" id="slide_text_1_shadow_b" value="1" max="30" min="-30" increment="1" decimal="0"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="slide_text_1_shadow_b"><?php print ai_arrow_up;?></a>


			 <input type="text"  size=10 name="slide_text_1_shadow_c" id="slide_text_1_shadow_c" value="000000" class="color"  thisval="000000">
			</div>
				
			</div>
			<div style="float: right; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; ">
				<div>Time</div>
				<div>
					<select name="slide_text_1_time" id="slide_text_1_time"  onChange="showEffect();">
					<?php $s = 0;
					while($s <=5000) { ?>
					<option value="<?php print $s;?>" <?php if($s == 1000) { print "selected"; } ?>><?php print number_format($s / 1000,2); ?> </option> 
					<?php
						$s = $s + 100;
					}
					?>
					</select> seconds
			</div>
		</div>

				<div style="float: right; margin-right: 10px; padding-right: 8px; border-right: solid 1px #b4b4b4; ">
					<div>Animation</div>
					<div>
					<select name="slide_text_1_effect" id="slide_text_1_effect" onChange="showEffect();">
					<option value="fadeIn" <?php if($slide['slide_text_1_effect'] == "fadeIn") { print "selected"; } ?>>Fade In</option>
					<option value="slideDown" <?php if($slide['slide_text_1_effect'] == "slideDown") { print "selected"; } ?>>Slide Down</option>
					<option value="showleft" <?php if($slide['slide_text_1_effect'] == "showleft") { print "selected"; } ?>>Show From Left</option>
					<option value="showright" <?php if($slide['slide_text_1_effect'] == "showright") { print "selected"; } ?>>Show From Right</option>
					<option value="showtop" <?php if($slide['slide_text_1_effect'] == "showtop") { print "selected"; } ?>>Show From Top</option>
					<option value="showbottom" <?php if($slide['slide_text_1_effect'] == "showbottom") { print "selected"; } ?>>Show From Bottom</option>
					</select>
					</div>
				</div>	


	<div class="clear"></div>
	</div>



			<div class="pc">
			<?php if($billboard['bill_placement'] !== "full") { ?>
			<div class="left">
				<div class="fieldLabel">Link slide to URL: </div>
				<div><input type="text" name="slide_link" id="slide_link" class="" size="20" value="<?php print $slide['slide_link'];?>"></div>
			</div>
			<?php } ?>
			<div class="right">
			<input type="submit" id="submitButton" name="save" class="submit" onclick="gethtml(); return false;" value="Save">  
			</div>
			<div class="clear"></div>
			</div>
		</div>




</div>
<div class="clear"></div>
		
			


</form>
<?php require "w-footer.php"; ?>
