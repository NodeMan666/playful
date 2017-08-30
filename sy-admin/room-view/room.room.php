<?php 
if($_REQUEST['action'] == "saveroom") { 
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "<b>Demo Mode On. No changes have been made.</b>";
		header("location: index.php?do=photoprods&view=roomview&sub=room&room_id=".$_REQUEST['room_id']."");
		session_write_close();
		exit();
	} else { 


		$id = updateSQL("ms_wall_rooms", "room_name='".addslashes(stripslashes(trim($_REQUEST['room_name'])))."', room_width='".$_REQUEST['room_width']."', room_center='".$_REQUEST['room_center']."', room_base='".$_REQUEST['room_base']."' WHERE room_id='".$_REQUEST['room_id']."' ");
		$_SESSION['sm'] = "Room settings saved";
		header("location: index.php?do=photoprods&view=roomview&sub=room&room_id=".$_REQUEST['room_id']."");
		session_write_close();
		exit();
	}
}
?>
<style>
#roomcontain { 
	position: relative; 
	margin: auto;
} 
#roombackground { 
	margin: auto; 
	position: absolute; 
	top: 0; 
} 
</style>
<script src="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.css" type="text/css" />
<script>
function setmeasurement() { 
	w = Math.abs($("#w").val());
	h = Math.abs($("#h").val());
	if(w <=0 && h<=0) { 

		$("#roommeasureselecterror").slideDown(200);
		$("#roommeasuresizeerror").slideUp(200);
		$("#roommeasuretitle").slideUp(200);

	} else if($("#rm_h").val() <= 0 && $("#rm_w").val() <= 0) { 
		$("#roommeasureselecterror").slideUp(200);
		$("#roommeasuresizeerror").slideDown(200);
		$("#roommeasuretitle").slideUp(200);

	} else { 

		wroompixels = $("#roombackground").width();
		hroompixels = $("#roombackground").height();
		if($("#rm_w").val() > 0) { 
			p = w / Math.abs($("#rm_w").val());
			total = wroompixels / p;
			// $("#log").show().append(total+" : "+(total / 12)+"<br>");
			$("#room_width").val(total);
		}
		if($("#rm_h").val() > 0) { 
			p = h / Math.abs($("#rm_h").val());
			total = wroompixels / p;
		// 	$("#log").show().append(total+" : "+(total / 12)+"<br>");
			$("#room_width").val(total);
		}
	}
}

function showCoords(c) {
  // variables can be accessed here as
  // c.x, c.y, c.x2, c.y2, c.w, c.h
	$("#x1").val(c.x);
	$("#y1").val(c.y);
	$("#x2").val(c.x2);
	$("#y2").val(c.y2);
	$("#w").val(c.w);
	$("#h").val(c.h);

	$("#x1p").val(c.x / $("#pic_width").val() * 100);
	$("#y1p").val(c.y / $("#pic_height").val() * 100);
	$("#x2p").val(c.x2 / $("#pic_width").val() * 100);
	$("#y2p").val(c.y2 / $("#pic_height").val() * 100);

};

function showRoomMeasure() { 
	$("#roommeasurements").slideDown(200);
}

	function enableselection() { 
		jcrop_api.enable();
	}
	function disableselection() { 
		jcrop_api.release();
		jcrop_api.disable();
	}



$(document).ready(function(){
	pixels = $("#roombackground").width() / Math.abs($("#roomcontain").attr("data-room-width")); // Pixel to inch ratio
	setTimeout(function() { 
		$('#roombackground').Jcrop({
			onSelect:    showRoomMeasure,
			onChange:    showCoords,
			bgColor:     'black',
			bgOpacity:   .2
		}, function () {
			jcrop_api = this;
		});


    $('#roombackground').mousemove(function(event) { 
        var left = event.pageX - $(this).offset().left;
        var top = event.pageY - $(this).offset().top;
		leftperc = left / $("#roombackground").width();
		topperc = top / $("#roombackground").height();
		// $("#log").show().html(topperc+" X "+leftperc+"<br>");
    });

	$('#roombackground').click(function(event){ 
		var left = event.pageX - $(this).offset().left;
		var top = event.pageY - $(this).offset().top;
		leftperc = left / $("#roombackground").width();
		topperc = top / $("#roombackground").height();
		// $("#log").show().append(topperc+" X "+leftperc+"<br>");
		$("#room_center").val(leftperc);
		$("#room_base").val(topperc);
		$("#roomcenter").css({"left":leftperc * 100+"%","top":topperc * 100+"%"});

	});



	$("#roomcenter").draggable({grid: [1, 1 ], 
	  stop: function() {
        var left =($("#roomcenter").position().left + 8);
        var top = ($("#roomcenter").position().top + 8);
		leftperc = left / $("#roombackground").width();
		topperc = top / $("#roombackground").height();
		$("#room_center").val(leftperc);
		$("#room_base").val(topperc);

		// $("#log").show().append(topperc+" X "+leftperc+"<br>");
	  }
	});

	photoleft = (Math.abs($("#roomcontain").attr("data-center")) * $("#roombackground").width()) - 8;
	phototop = (Math.abs($("#roomcontain").attr("data-base")) * $("#roombackground").height()) - 8;
	$("#roomcenter").css({"left":photoleft+"px","top":phototop+"px"});

	/*
	$("#roombackground").mousedown(function() { 
		$("#log").show().append("here<br>");
		$("#roomcontain").append('<div id="measure">XXXXX</div>');
        var left = event.pageX - $(this).offset().left;
        var top = event.pageY - $(this).offset().top;

		$("#measure").css({"left":left+"px","top":top+"px"}).draggable();

	});

*/

	$("#rm_w").focus(function() { 
		$("#rm_h").val("")
	});
	$("#rm_h").focus(function() { 
		$("#rm_w").val("")
	});


	},100);

});

$(window).resize(function() {
	photoleft = (Math.abs($("#roomcontain").attr("data-center")) * $("#roombackground").width()) - 8;
	phototop = (Math.abs($("#roomcontain").attr("data-base")) * $("#roombackground").height()) - 8;
	$("#roomcenter").css({"left":photoleft+"px","top":phototop+"px"});
});


</script>
<style>
#measure { width: 16px; height: 16px; background: #890000; z-index: 500; position: absolute;} 
</style>
<input type="hidden" name="x1" id="x1" value="" size="3"> 
<input type="hidden" name="y1" id="y1" value="" size="3"> 
<input type="hidden" name="x2" id="x2" value="" size="3"> 
<input type="hidden" name="y2" id="y2" value="" size="3"> 
<input type="hidden" name="w" id="w" value="" size="3"> 
<input type="hidden" name="h" id="h" value="" size="3"> 

<div id="pageTitle"><a href="index.php?do=photoprods">Photo Products</a> <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview">Wall Designer</a>   <?php print ai_sep;?> <a href="index.php?do=photoprods&view=roomview&sub=rooms">Room Photos</a> <?php print ai_sep;?> Edit Room Photo</div>
<div class="clear"></div>

<?php
$room = doSQL("ms_wall_rooms", "*", "WHERE room_id='".$_REQUEST['room_id']."' ");

$room_photo = $setup['temp_url_folder'].$room['room_large'];
$data_room_photo_width = $room['room_photo_width'];
$data_room_photo_height = $room['room_photo_height'];
$data_room_width = $room['room_width'];
$data_center = $room['room_center'];
$data_base = $room['room_base'];

	if($data_room_photo_width > 1000) {
		$hp = 1000 / $data_room_photo_width;
		$containheight = $data_room_photo_height * $hp;
	} else { 
		$containheight = $data_room_photo_height;
	}
if($room['room_width'] <= 0) { 
	$room['room_width'] = "";
}
?>
<div class="left p25">
	<div style="padding: 16px;">

		<form method="post" action="index.php" id="DSDS" name="DSDS"  onSubmit="return checkForm();">
			<div class="underlinelabel">Room Name</div>
			<div class="underline">
				<div><input type="text" name="room_name" id="room_name" size="20" class="field100" value="<?php print $room['room_name'];?>"></div>
			</div>
			<div class="underlinelabel">Room Width</div>
			<div class="underline">
				<div><input type="text" name="room_width" id="room_width" size="5" class="required" value="<?php print $room['room_width'];?>"> <?php if($wset['math_type'] == "inches") { ?>inches<?php } else { ?>centimetres<?php } ?></div>
			</div>
				<div class="underlinespacer">To set the room width,  click and drag on the picture to an object closest to the wall to set the measurements of a specific area and it will calculate the total width.<br><br>
				You can also just enter in the total width of the room in <?php if($wset['math_type'] == "centimetre") { ?>centimetres<?php } else { ?>inches<?php } ?> the photo.</div>

			<div>&nbsp;</div>
			<div class="underlinelabel">Mark center of the wall</div>
			<div class="underline">
				<div class="">Move the yellow square to the center of the wall</div>
			</div>
			<div>&nbsp;</div>
			<div class="pc center">
			<input type="hidden" name="room_center" id="room_center" size="20" class="field100" value="<?php print $room['room_center'];?>">
			<input type="hidden" name="room_base" id="room_base" size="20" class="field100" value="<?php print $room['room_base'];?>">
			<input type="hidden" name="do" id="do" value="photoprods">
			<input type="hidden" name="view" id="view" value="roomview">
			<input type="hidden" name="sub" id="sub" value="room">
			<input type="hidden" name="action" id="action" value="saveroom">
			<input type="hidden" name="room_id" id="room_id" value="<?php print $room['room_id'];?>">
			<input type="submit" name="submit"  value="Save All Changes" class="submit">
			</div>

		</form>
	</div>
</div>
<div class="left p75">
	<div style="padding: 16px;">
		<div id="roommeasurements" class="pc center hide">
			<div class="pc center" id="roommeasuretitle"><h2>Set Room Measurement</h2></div>
			<div class="pc center error hide" id="roommeasureselecterror">Please select an area on the photo</div>
			<div class="pc center error hide" id="roommeasuresizeerror">Please enter a width or height of the selected area</div>

		<div class="pc bold">Enter the width OR the height of the selected area below in "<?php if($wset['math_type'] == "centimetre") { ?>centimetres<?php } else { ?>inches<?php } ?>" and click Calculate.</div>
			Width: <input type="text" name="rm_w" id="rm_w" size="6"> or Height: <input type="text" name="rm_h" id="rm_h" size="6">  <a href="" onclick="setmeasurement(); return false;" >Calculate</a>
		</div>
		<!-- <div class="pc center" id="roommeasure"><a href="" onclick="disableselection(); return false;">disable</a>  <a href="" onclick="enableselection(); return false;">enable</a></div> -->


		<div id="roomcontain" style="width: 100%; max-width: <?php print $max_width;?>px; margin: auto; height:<?php print $containheight;?>px;" data-room-photo-width="<?php print $data_room_photo_width;?>" data-room-photo-height="<?php print $data_room_photo_height;?>"   data-room-width="<?php print $data_room_width;?>" data-center="<?php print $data_center;?>" data-base="<?php print $data_base;?>" data-center-photo="1"   data-room-background-photo="<?php print $room_photo;?>" data-selected-photo="" data-last-selected-photo="">

			<div id="roombackground"><img id="roombackgroundphoto" src="<?php print $room_photo;?>" style="width: 100%; max-width: <?php print $data_room_photo_width;?>px; height: auto;"></div>
			<div id="roomcenter" style="width: 14px; height: 14px; display: block; position: absolute; background: #FFFF00;cursor: move; left: <?php print $room['room_center'] * 100; ?>%; top: <?php print $room['room_base'] * 100;?>%; border: solid 1px #000000; box-shadow: 0 0 1px rgba(0,0,0,.5); z-index: 1000;"></div>
		</div>

	</div>
</div>