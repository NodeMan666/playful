<?php 
$noclose = 1;
require "w-header.php"; ?>
<?php if($site_setup['hide_help'] == "0") { ?>
<div style="" class="pform" id="windowedit">
	<div style="position: absolute; right: 8px; top: 8px; display: none;" id="windoweditclose"><a href="" onclick="closewindowedit(); return false;"><?php print ai_close;?></a></div>
	<div id="windoweditinner" style="padding: 24px;">
	</div>
</div>
<style>
html, body { font-size: 13px; } 
</style>
<script>
function getstarted() { 
	pagewindowedit("new.php");
	}
</script>
<div id="gettingstartedtab" style="" class="sidetab" onclick="getstarted();"><div style="padding: 8px;">G<br>E<br>T<br>T<br>I<br>N<br>G<br><br>S<br>T<br>A<br>R<br>T<br>E<br>D</div></div>
<?php } ?>
<script language="javascript" src="theme-edit.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/redactor/redactor.css" />
<script src="js/redactor/redactor.js"></script>
<?php 
function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

function boxShadows($bscode,$name) { 

	$bss = explode("+",$bscode);
	if(!empty($bss[0])) { 
			$bbs1 = explode("|",$bss[0]);
			$box_shadow_1 = true;
	} else { 
		$bbs1[0] = "4";
		$bbs1[1] = "4";
		$bbs1[2] = "4";
		$bbs1[3] = "0";
		$bbs1[4] = "000000";
		$bbs1[5] = "0.4";
	}
	if(!empty($bss[1])) { 
			$bbs2 = explode("|",$bss[1]);
			$box_shadow_2 = true;
	} else { 
		$bbs2[0] = "4";
		$bbs2[1] = "4";
		$bbs2[2] = "4";
		$bbs2[3] = "0";
		$bbs2[4] = "000000";
		$bbs2[5] = "0.4";
	}

	if(!empty($bss[2])) { 
			$bbs3= explode("|",$bss[2]);
			$box_shadow_3 = true;
	} else { 
		$bbs3[0] = "4";
		$bbs3[1] = "4";
		$bbs3[2] = "4";
		$bbs3[3] = "0";
		$bbs3[4] = "000000";
		$bbs3[5] = "0.4";
	}
?>
	
	<div class="group">
	<div><input type="checkbox" name="<?php print $name;?>-box-shadow-1" id="<?php print $name;?>-box-shadow-1" value="1" <?php if($box_shadow_1 == true) { print "checked"; } ?>> Box Shadow</div>
	<div id="<?php print $name;?>-bs1" <?php if($box_shadow_1 !== true) { print "style=\"display: none;\""; } ?> >
	<div>
	<?php $this_name = $name."-bs1"; ?>
	<?php /* Horizontal Shadow */ ?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal Shadow"  type="text" size="2" name="<?php print $this_name;?>-0" id="<?php print $this_name;?>-0" value="<?php print $bbs1[0];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_up;?></a>


	<?php /* Verticle Shadow */ ?>

 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle Shadow" type="text" size="2" name="<?php print $this_name;?>-1" id="<?php print $this_name;?>-1" value="<?php print $bbs1[1];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_up;?></a>



	<?php /* Blur */ 
		$i = 2
	?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur"  type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs1[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>



	<?php /* Spread */ 
		$i = 3;
	?>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Spread" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs1[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

	</div>
	<div>


	<input type="text"  size=10 name="<?php print $this_name;?>-color" id="<?php print $this_name;?>-color" value="<?php  print $bbs1[4];?>" class="color themeinput"  onchange="changeCaption();">

	<?php /* Opacity */ 
		$i = 5;
	?>

	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs1[$i];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

		<input type="checkbox"  value="inset" name="<?php print $this_name;?>-6" id="<?php print $this_name;?>-6" <?php if($bbs1[6] == "inset") { print "checked"; } ?>> Inset
	</div>
</div>
</div>


	<div class="group">
	<div><input type="checkbox" name="<?php print $name;?>-box-shadow-2" id="<?php print $name;?>-box-shadow-2" value="1"  <?php if($box_shadow_2 == true) { print "checked"; } ?>> Box Shadow 2</div>
	<div id="<?php print $name;?>-bs2"  <?php if($box_shadow_2 !== true) { print "style=\"display: none;\""; } ?>>
	<div>
	<?php $this_name = $name."-bs2"; ?>
	<?php /* Horizontal Shadow */ ?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal Shadow"  type="text" size="2" name="<?php print $this_name;?>-0" id="<?php print $this_name;?>-0" value="<?php print $bbs2[0];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_up;?></a>


	<?php /* Verticle Shadow */ ?>

 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle Shadow" type="text" size="2" name="<?php print $this_name;?>-1" id="<?php print $this_name;?>-1" value="<?php print $bbs2[1];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_up;?></a>



	<?php /* Blur */ 
		$i = 2
	?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur"  type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs2[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>



	<?php /* Spread */ 
		$i = 3;
	?>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Spread" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs2[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

	</div>
	<div>
	<input type="text"  size=10 name="<?php print $this_name;?>-color" id="<?php print $this_name;?>-color" value="<?php  print $bbs2[4];?>" class="color themeinput"  onchange="changeCaption();">

	<?php /* Opacity */ 
		$i =5;
	?>

	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs2[$i];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

		<input type="checkbox"  value="inset" name="<?php print $this_name;?>-6" id="<?php print $this_name;?>-6" <?php if($bbs2[6] == "inset") { print "checked"; } ?>> Inset
	</div>
</div>
</div>



	<div class="group">
	<div><input type="checkbox" name="<?php print $name;?>-box-shadow-3" id="<?php print $name;?>-box-shadow-3" value="1"  <?php if($box_shadow_3 == true) { print "checked"; } ?>> Box Shadow 3</div>
	<div id="<?php print $name;?>-bs3"  <?php if($box_shadow_3 !== true) { print "style=\"display: none;\""; } ?>>
	<div>
	<?php $this_name = $name."-bs3"; ?>
	<?php /* Horizontal Shadow */ ?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal Shadow"  type="text" size="2" name="<?php print $this_name;?>-0" id="<?php print $this_name;?>-0" value="<?php print $bbs3[0];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-0"><?php print ai_arrow_up;?></a>


	<?php /* Verticle Shadow */ ?>

 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle Shadow" type="text" size="2" name="<?php print $this_name;?>-1" id="<?php print $this_name;?>-1" value="<?php print $bbs3[1];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-1"><?php print ai_arrow_up;?></a>



	<?php /* Blur */ 
		$i = 2
	?>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur"  type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs3[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>



	<?php /* Spread */ 
		$i = 3;
	?>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Spread" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs3[$i];?>" min="-200" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

	</div>
	<div>
	<input type="text"  size=10 name="<?php print $this_name;?>-color" id="<?php print $this_name;?>-color" value="<?php  print $bbs3[4];?>" class="color themeinput"  onchange="changeCaption();">

	<?php /* Opacity */ 
		$i = 5;
	?>

	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="<?php print $this_name;?>-<?php print $i;?>" id="<?php print $this_name;?>-<?php print $i;?>" value="<?php print $bbs3[$i];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="<?php print $this_name;?>-<?php print $i;?>"><?php print ai_arrow_up;?></a>

		<input type="checkbox"  value="inset" name="<?php print $this_name;?>-6" id="<?php print $this_name;?>6" <?php if($bbs3[6] == "inset") { print "checked"; } ?>> Inset
	</div>
</div>
</div>
<?php } ?>

<?php 
if(!empty($_REQUEST['submitit'])) {
	if($setup['demo_mode'] == true) { 
		$_SESSION['sm'] = "DEMO MODE - Theme not saved in demo mode";
		session_write_close();
		header("location: ".$_SERVER['PHP_SELF']."?css_id=".$_REQUEST['css_id']."");
		exit();

	}



	if(!empty($error)) {
		print "<div class=error>$error</div><div>&nbsp;</div>";
		regForm();
	} else {

		foreach($_REQUEST AS $id => $value) {
			$_REQUEST[$id] = addslashes(stripslashes($value));
		}

		if(!empty($_REQUEST['menu_font_other'])) { 
			$_REQUEST['menu_font'] = $_REQUEST['menu_font_other'];
		}

		if(!empty($_REQUEST['css_id'])) {
			$css = doSQL("ms_css", "*", "WHERE css_id='".$_REQUEST['css_id']."' ");

			$new_css = "css-".$_REQUEST['css_id']."-".date('ymdhis').".css";
			if(!empty($css['css_file'])) { 
				@unlink("".$setup['path']."/".$setup['layouts_folder']."/".$css['css_file']."");
			}

			$header_text_shadow = $_REQUEST['header_text_shadow_h']."px ".$_REQUEST['header_text_shadow_v']."px ".$_REQUEST['header_text_shadow_b']."px #".$_REQUEST['header_text_shadow_c'];
			$text_shadow = $_REQUEST['text_shadow_h']."px ".$_REQUEST['text_shadow_v']."px ".$_REQUEST['text_shadow_b']."px #".$_REQUEST['text_shadow_c'];
			$menu_text_shadow = $_REQUEST['menu_text_shadow_h']."px ".$_REQUEST['menu_text_shadow_v']."px ".$_REQUEST['menu_text_shadow_b']."px #".$_REQUEST['menu_text_shadow_c'];
			$title_text_shadow = $_REQUEST['title_text_shadow_h']."px ".$_REQUEST['title_text_shadow_v']."px ".$_REQUEST['title_text_shadow_b']."px #".$_REQUEST['title_text_shadow_c'];
			$link_text_shadow = $_REQUEST['link_text_shadow_h']."px ".$_REQUEST['link_text_shadow_v']."px ".$_REQUEST['link_text_shadow_b']."px #".$_REQUEST['link_text_shadow_c'];
			$footer_text_shadow= $_REQUEST['footer_text_shadow_h']."px ".$_REQUEST['footer_text_shadow_v']."px ".$_REQUEST['footer_text_shadow_b']."px #".$_REQUEST['footer_text_shadow_c'];
	
				$sm_text_shadow = $_REQUEST['sm_text_shadow_h']."px ".$_REQUEST['sm_text_shadow_v']."px ".$_REQUEST['sm_text_shadow_b']."px #".$_REQUEST['sm_text_shadow_c'];

			if($_REQUEST['boxes-box-shadow-1'] == "1") { 
				$boxes_box_shadow = $_REQUEST['boxes-bs1-0']."|".$_REQUEST['boxes-bs1-1']."|".$_REQUEST['boxes-bs1-2']."|".$_REQUEST['boxes-bs1-3']."|".$_REQUEST['boxes-bs1-color']."|".$_REQUEST['boxes-bs1-5']."|".$_REQUEST['boxes-bs1-6']."+";
			}

			if($_REQUEST['boxes-box-shadow-2'] == "1") { 
				$boxes_box_shadow .= $_REQUEST['boxes-bs2-0']."|".$_REQUEST['boxes-bs2-1']."|".$_REQUEST['boxes-bs2-2']."|".$_REQUEST['boxes-bs2-3']."|".$_REQUEST['boxes-bs2-color']."|".$_REQUEST['boxes-bs2-5']."|".$_REQUEST['boxes-bs2-6']."+";
			}

			if($_REQUEST['boxes-box-shadow-3'] == "1") { 
				$boxes_box_shadow .= $_REQUEST['boxes-bs3-0']."|".$_REQUEST['boxes-bs3-1']."|".$_REQUEST['boxes-bs3-2']."|".$_REQUEST['boxes-bs3-3']."|".$_REQUEST['boxes-bs3-color']."|".$_REQUEST['boxes-bs3-5']."|".$_REQUEST['boxes-bs3-6']."";
			}


			if($_REQUEST['topmenu-box-shadow-1'] == "1") { 
				$top_menu_box_shadow = $_REQUEST['topmenu-bs1-0']."|".$_REQUEST['topmenu-bs1-1']."|".$_REQUEST['topmenu-bs1-2']."|".$_REQUEST['topmenu-bs1-3']."|".$_REQUEST['topmenu-bs1-color']."|".$_REQUEST['topmenu-bs1-5']."|".$_REQUEST['topmenu-bs1-6']."+";
			}

			if($_REQUEST['topmenu-box-shadow-2'] == "1") { 
				$top_menu_box_shadow .= $_REQUEST['topmenu-bs2-0']."|".$_REQUEST['topmenu-bs2-1']."|".$_REQUEST['topmenu-bs2-2']."|".$_REQUEST['topmenu-bs2-3']."|".$_REQUEST['topmenu-bs2-color']."|".$_REQUEST['topmenu-bs2-5']."|".$_REQUEST['topmenu-bs2-6']."+";
			}

			if($_REQUEST['topmenu-box-shadow-3'] == "1") { 
				$top_menu_box_shadow .= $_REQUEST['topmenu-bs3-0']."|".$_REQUEST['topmenu-bs3-1']."|".$_REQUEST['topmenu-bs3-2']."|".$_REQUEST['topmenu-bs3-3']."|".$_REQUEST['topmenu-bs3-color']."|".$_REQUEST['topmenu-bs3-5']."|".$_REQUEST['topmenu-bs3-6']."";
			}


			if($_REQUEST['header-box-shadow-1'] == "1") { 
				$header_box_shadow = $_REQUEST['header-bs1-0']."|".$_REQUEST['header-bs1-1']."|".$_REQUEST['header-bs1-2']."|".$_REQUEST['header-bs1-3']."|".$_REQUEST['header-bs1-color']."|".$_REQUEST['header-bs1-5']."|".$_REQUEST['header-bs1-6']."+";
			}

			if($_REQUEST['header-box-shadow-2'] == "1") { 
				$header_box_shadow .= $_REQUEST['header-bs2-0']."|".$_REQUEST['header-bs2-1']."|".$_REQUEST['header-bs2-2']."|".$_REQUEST['header-bs2-3']."|".$_REQUEST['header-bs2-color']."|".$_REQUEST['header-bs2-5']."|".$_REQUEST['header-bs2-6']."+";
			}

			if($_REQUEST['header-box-shadow-3'] == "1") { 
				$header_box_shadow .= $_REQUEST['header-bs3-0']."|".$_REQUEST['header-bs3-1']."|".$_REQUEST['header-bs3-2']."|".$_REQUEST['header-bs3-3']."|".$_REQUEST['header-bs3-color']."|".$_REQUEST['header-bs3-5']."|".$_REQUEST['header-bs3-6']."";
			}


			if($_REQUEST['content-box-shadow-1'] == "1") { 
				$content_box_shadow = $_REQUEST['content-bs1-0']."|".$_REQUEST['content-bs1-1']."|".$_REQUEST['content-bs1-2']."|".$_REQUEST['content-bs1-3']."|".$_REQUEST['content-bs1-color']."|".$_REQUEST['content-bs1-5']."|".$_REQUEST['content-bs1-6']."+";
			}

			if($_REQUEST['content-box-shadow-2'] == "1") { 
				$content_box_shadow .= $_REQUEST['content-bs2-0']."|".$_REQUEST['content-bs2-1']."|".$_REQUEST['content-bs2-2']."|".$_REQUEST['content-bs2-3']."|".$_REQUEST['content-bs2-color']."|".$_REQUEST['content-bs2-5']."|".$_REQUEST['content-bs2-6']."+";
			}

			if($_REQUEST['content-box-shadow-3'] == "1") { 
				$content_box_shadow .= $_REQUEST['content-bs3-0']."|".$_REQUEST['content-bs3-1']."|".$_REQUEST['content-bs3-2']."|".$_REQUEST['content-bs3-3']."|".$_REQUEST['content-bs3-color']."|".$_REQUEST['content-bs3-5']."|".$_REQUEST['content-bs3-6']."";
			}

			if($_REQUEST['fs-photos-box-shadow-1'] == "1") { 
				$full_screen_photo_box_shadow = $_REQUEST['fs-photos-bs1-0']."|".$_REQUEST['fs-photos-bs1-1']."|".$_REQUEST['fs-photos-bs1-2']."|".$_REQUEST['fs-photos-bs1-3']."|".$_REQUEST['fs-photos-bs1-color']."|".$_REQUEST['fs-photos-bs1-5']."|".$_REQUEST['fs-photos-bs1-6']."+";
			}

			if($_REQUEST['fs-photos-box-shadow-2'] == "1") { 
				$full_screen_photo_box_shadow .= $_REQUEST['fs-photos-bs2-0']."|".$_REQUEST['fs-photos-bs2-1']."|".$_REQUEST['fs-photos-bs2-2']."|".$_REQUEST['fs-photos-bs2-3']."|".$_REQUEST['fs-photos-bs2-color']."|".$_REQUEST['fs-photos-bs2-5']."|".$_REQUEST['fs-photos-bs2-6']."+";
			}

			if($_REQUEST['fs-photos-box-shadow-3'] == "1") { 
				$full_screen_photo_box_shadow .= $_REQUEST['fs-photos-bs3-0']."|".$_REQUEST['fs-photos-bs3-1']."|".$_REQUEST['fs-photos-bs3-2']."|".$_REQUEST['fs-photos-bs3-3']."|".$_REQUEST['fs-photos-bs3-color']."|".$_REQUEST['fs-photos-bs3-5']."|".$_REQUEST['fs-photos-bs3-6']."";
			}

			if($_REQUEST['photos-box-shadow-1'] == "1") { 
				$photos_box_shadow = $_REQUEST['photos-bs1-0']."|".$_REQUEST['photos-bs1-1']."|".$_REQUEST['photos-bs1-2']."|".$_REQUEST['photos-bs1-3']."|".$_REQUEST['photos-bs1-color']."|".$_REQUEST['photos-bs1-5']."|".$_REQUEST['photos-bs1-6']."+";
			}

			if($_REQUEST['photos-box-shadow-2'] == "1") { 
				$photos_box_shadow .= $_REQUEST['photos-bs2-0']."|".$_REQUEST['photos-bs2-1']."|".$_REQUEST['photos-bs2-2']."|".$_REQUEST['photos-bs2-3']."|".$_REQUEST['photos-bs2-color']."|".$_REQUEST['photos-bs2-5']."|".$_REQUEST['photos-bs2-6']."+";
			}

			if($_REQUEST['photos-box-shadow-3'] == "1") { 
				$photos_box_shadow .= $_REQUEST['photos-bs3-0']."|".$_REQUEST['photos-bs3-1']."|".$_REQUEST['photos-bs3-2']."|".$_REQUEST['photos-bs3-3']."|".$_REQUEST['photos-bs3-color']."|".$_REQUEST['photos-bs3-5']."|".$_REQUEST['photos-bs3-6']."";
			}

			if($_REQUEST['onphotos-box-shadow-1'] == "1") { 
				$on_photo_box_shadow = $_REQUEST['onphotos-bs1-0']."|".$_REQUEST['onphotos-bs1-1']."|".$_REQUEST['onphotos-bs1-2']."|".$_REQUEST['onphotos-bs1-3']."|".$_REQUEST['onphotos-bs1-color']."|".$_REQUEST['onphotos-bs1-5']."|".$_REQUEST['onphotos-bs1-6']."+";
			}

			if($_REQUEST['onphotos-box-shadow-2'] == "1") { 
				$on_photo_box_shadow .= $_REQUEST['onphotos-bs2-0']."|".$_REQUEST['onphotos-bs2-1']."|".$_REQUEST['onphotos-bs2-2']."|".$_REQUEST['onphotos-bs2-3']."|".$_REQUEST['onphotos-bs2-color']."|".$_REQUEST['onphotos-bs2-5']."|".$_REQUEST['onphotos-bs2-6']."+";
			}

			if($_REQUEST['onphotos-box-shadow-3'] == "1") { 
				$on_photo_box_shadow .= $_REQUEST['onphotos-bs3-0']."|".$_REQUEST['onphotos-bs3-1']."|".$_REQUEST['onphotos-bs3-2']."|".$_REQUEST['onphotos-bs3-3']."|".$_REQUEST['onphotos-bs3-color']."|".$_REQUEST['onphotos-bs3-5']."|".$_REQUEST['onphotos-bs3-6']."";
			}

			if($_REQUEST['thumblisting-box-shadow-1'] == "1") { 
				$thumb_listing_box_shadow = $_REQUEST['thumblisting-bs1-0']."|".$_REQUEST['thumblisting-bs1-1']."|".$_REQUEST['thumblisting-bs1-2']."|".$_REQUEST['thumblisting-bs1-3']."|".$_REQUEST['thumblisting-bs1-color']."|".$_REQUEST['thumblisting-bs1-5']."|".$_REQUEST['thumblisting-bs1-6']."+";
			}

			if($_REQUEST['thumblisting-box-shadow-2'] == "1") { 
				$thumb_listing_box_shadow .= $_REQUEST['thumblisting-bs2-0']."|".$_REQUEST['thumblisting-bs2-1']."|".$_REQUEST['thumblisting-bs2-2']."|".$_REQUEST['thumblisting-bs2-3']."|".$_REQUEST['thumblisting-bs2-color']."|".$_REQUEST['thumblisting-bs2-5']."|".$_REQUEST['thumblisting-bs2-6']."+";
			}

			if($_REQUEST['thumblisting-box-shadow-3'] == "1") { 
				$thumb_listing_box_shadow .= $_REQUEST['thumblisting-bs3-0']."|".$_REQUEST['thumblisting-bs3-1']."|".$_REQUEST['thumblisting-bs3-2']."|".$_REQUEST['thumblisting-bs3-3']."|".$_REQUEST['thumblisting-bs3-color']."|".$_REQUEST['thumblisting-bs3-5']."|".$_REQUEST['thumblisting-bs3-6']."";
			}

			if($_REQUEST['thumb_nails-box-shadow-1'] == "1") { 
				$thumb_nails_box_shadow = $_REQUEST['thumb_nails-bs1-0']."|".$_REQUEST['thumb_nails-bs1-1']."|".$_REQUEST['thumb_nails-bs1-2']."|".$_REQUEST['thumb_nails-bs1-3']."|".$_REQUEST['thumb_nails-bs1-color']."|".$_REQUEST['thumb_nails-bs1-5']."|".$_REQUEST['thumb_nails-bs1-6']."+";
			}

			if($_REQUEST['thumb_nails-box-shadow-2'] == "1") { 
				$thumb_nails_box_shadow .= $_REQUEST['thumb_nails-bs2-0']."|".$_REQUEST['thumb_nails-bs2-1']."|".$_REQUEST['thumb_nails-bs2-2']."|".$_REQUEST['thumb_nails-bs2-3']."|".$_REQUEST['thumb_nails-bs2-color']."|".$_REQUEST['thumb_nails-bs2-5']."|".$_REQUEST['thumb_nails-bs2-6']."+";
			}

			if($_REQUEST['thumb_nails-box-shadow-3'] == "1") { 
				$thumb_nails_box_shadow .= $_REQUEST['thumb_nails-bs3-0']."|".$_REQUEST['thumb_nails-bs3-1']."|".$_REQUEST['thumb_nails-bs3-2']."|".$_REQUEST['thumb_nails-bs3-3']."|".$_REQUEST['thumb_nails-bs3-color']."|".$_REQUEST['thumb_nails-bs3-5']."|".$_REQUEST['thumblisting-bs3-6']."";
			}


			$id = updateSQL("ms_css", "
				css_font_family='".$_REQUEST['css_font_family']."',
				font_size='".$_REQUEST['font_size']."',
				page_width='".$_REQUEST['page_width']."',
				css_name='".$_REQUEST['css_name']."',
				outside_bg='".$_REQUEST['outside_bg']."',
				outside_bg_image='".$_REQUEST['outside_bg_image']."',
				descr='".$_REQUEST['descr']."',
				inside_bg= '".$_REQUEST['inside_bg']."',
				inside_bg_border= '".$_REQUEST['inside_bg_border']."',
				font_color= '".$_REQUEST['font_color']."',
				link_color= '".$_REQUEST['link_color']."',
				link_color_hover= '".$_REQUEST['link_color_hover']."',
				page_title= '".$_REQUEST['page_title']."',
				page_title_hover= '".$_REQUEST['page_title_hover']."',
				header_bg= '".$_REQUEST['header_bg']."',
				header_font_color= '".$_REQUEST['header_font_color']."',
				top_menu_bg= '".$_REQUEST['top_menu_bg']."',
				top_menu_text= '".$_REQUEST['top_menu_text']."',
				top_menu_link= '".$_REQUEST['top_menu_link']."',
				top_menu_border= '".$_REQUEST['top_menu_border']."',
				menu_color= '".$_REQUEST['menu_color']."',
				menu_font_color= '".$_REQUEST['menu_font_color']."',
				menu_link_color= '".$_REQUEST['menu_link_color']."',
				menu_border_a= '".$_REQUEST['menu_border_a']."',
				menu_border_b= '".$_REQUEST['menu_border_b']."',
				boxes= '".$_REQUEST['boxes']."',
				boxes_hover= '".$_REQUEST['boxes_hover']."',
				boxes_borders= '".$_REQUEST['boxes_borders']."',
				page_labels= '".$_REQUEST['page_labels']."',
				page_dates= '".$_REQUEST['page_dates']."',
				form_bg= '".$_REQUEST['form_bg']."',
				form_color= '".$_REQUEST['form_color']."',
				form_border= '".$_REQUEST['form_border']."',
				menu_link_hover='".$_REQUEST['menu_link_hover']."',
				home_page_col ='".$_REQUEST['home_page_col']."',
				home_page_col_border ='".$_REQUEST['home_page_col_border']."',
				side_menu_bg_color ='".$_REQUEST['side_menu_bg_color']."',
				side_menu_border_color ='".$_REQUEST['side_menu_border_color']."',
				side_menu_font_color ='".$_REQUEST['side_menu_font_color']."',
				side_menu_link_color ='".$_REQUEST['side_menu_link_color']."',
				side_menu_link_hover ='".$_REQUEST['side_menu_link_hover']."',
				side_menu_bg_hover ='".$_REQUEST['side_menu_bg_hover']."',
				side_menu_link_border_a ='".$_REQUEST['side_menu_link_border_a']."',
				side_menu_link_border_b ='".$_REQUEST['side_menu_link_border_b']."',
				side_menu_width ='".$_REQUEST['side_menu_width']."',
				side_menu_align='".$_REQUEST['side_menu_align']."',
				side_menu_header_bg ='".$_REQUEST['side_menu_header_bg']."',
				side_menu_header_text='".$_REQUEST['side_menu_header_text']."',
				thumb_border ='".$_REQUEST['thumb_border']."',
				disable_side ='".$_REQUEST['disable_side']."',
				codeid ='".$_REQUEST['codeid']."',
				bg_image_style ='".$_REQUEST['bg_image_style']."',
				fb_bg_color ='".$_REQUEST['fb_bg_color']."',
				fb_border_color ='".$_REQUEST['fb_border_color']."',
				fb_color ='".$_REQUEST['fb_color']."',
				css_order='".$_REQUEST['css_order']."',
				menu_font='".$_REQUEST['menu_font']."',
				menu_color_hover='".$_REQUEST['menu_color_hover']."',
			 hide_menu_galleries='".$_REQUEST['hide_menu_galleries']."' ,  hide_menu_pages='".$_REQUEST['hide_menu_pages']."' ,  hide_menu_blog='".$_REQUEST['hide_menu_blog']."' ,  hide_menu_blog_listing='".$_REQUEST['hide_menu_blog_listing']."' ,  hide_menu_gallery_listing='".$_REQUEST['hide_menu_gallery_listing']."', move_portrait_left='".$_REQUEST['move_portrait_left']."', move_landscape_left='".$_REQUEST['move_landscape_left']."' , textarea_width='".$_REQUEST['textarea_width']."' , textarea_left='".$_REQUEST['textarea_left']."' , textarea_max_height='".$_REQUEST['textarea_max_height']."' , text_area_bottom='".$_REQUEST['text_area_bottom']."' , add_menu_height_to_screen='1'  , round_corners='".$_REQUEST['round_corners']."',

			textarea_shadow_color='".$_REQUEST['textarea_shadow_color']."',
			textarea_shadow_size='".$_REQUEST['textarea_shadow_size']."',
			text_area_top='".$_REQUEST['text_area_top']."',
			text_area_placement='".$_REQUEST['text_area_placement']."',
			header_location='".$_REQUEST['header_location']."',
			boxes_padding='".$_REQUEST['boxes_padding']."',
			menu_location='".$_REQUEST['menu_location']."',
			menu_rounded_corners='".$_REQUEST['menu_rounded_corners']."',
			header_padding='".$_REQUEST['header_padding']."',
			photo_border_size='".$_REQUEST['photo_border_size']."',
			photo_border_color='".$_REQUEST['photo_border_color']."',
			photo_background='".$_REQUEST['photo_background']."',
			photo_padding='".$_REQUEST['photo_padding']."',
			header_border_color='".$_REQUEST['header_border_color']."',
			header_transparent='".$_REQUEST['header_transparent']."',

			header_text_shadow_on='".$_REQUEST['header_text_shadow_on']."',
			header_text_shadow='$header_text_shadow',

			text_shadow_on='".$_REQUEST['text_shadow_on']."',
			text_shadow='$text_shadow',

			menu_text_shadow_on='".$_REQUEST['menu_text_shadow_on']."',
			menu_text_shadow='$menu_text_shadow',

			title_text_shadow_on='".$_REQUEST['title_text_shadow_on']."',
			title_text_shadow='$title_text_shadow',

			link_text_shadow_on='".$_REQUEST['link_text_shadow_on']."',
			link_text_shadow='$link_text_shadow',

			footer_text_shadow_on='".$_REQUEST['footer_text_shadow_on']."',
			footer_text_shadow='$footer_text_shadow',

			caption_background='".$_REQUEST['caption_background']."' ,
			caption_opacity='".$_REQUEST['caption_opacity']."',
			caption_text = '".$_REQUEST['caption_text']."',
			footer_menu_font_size='".$_REQUEST['footer_menu_font_size']."',
			submit_bg='".$_REQUEST['submit_bg']."',
			submit_text='".$_REQUEST['submit_text']."',
			submit_border='".$_REQUEST['submit_border']."',

			photo_page_border_size='".$_REQUEST['photo_page_border_size']."',
			photo_page_border_color='".$_REQUEST['photo_page_border_color']."',
			photo_page_background='".$_REQUEST['photo_page_background']."',
			photo_page_padding='".$_REQUEST['photo_page_padding']."',
			side_main_bg='".$_REQUEST['side_main_bg']."',
			side_main_font='".$_REQUEST['side_main_font']."',
			side_main_link='".$_REQUEST['side_main_link']."',
			side_main_link_hover='".$_REQUEST['side_main_link_hover']."',
			side_main_header='".$_REQUEST['side_main_header']."',
			side_main_border_color='".$_REQUEST['side_main_border_color']."',
			side_main_padding='".$_REQUEST['side_main_padding']."',
			icons_folder='".$_REQUEST['icons_folder']."',
			inside_bg_transparent='".$_REQUEST['inside_bg_transparent']."',
			side_menu_transparent='".$_REQUEST['side_menu_transparent']."',
			boxes_transparent='".$_REQUEST['boxes_transparent']."',
			main_menu_wide='".$_REQUEST['main_menu_wide']."',
			main_menu_padding='".$_REQUEST['main_menu_padding']."',
			header_wide='".$_REQUEST['header_wide']."',
			css_font_family_main='".$_REQUEST['css_font_family_main']."',
			css_title_font_family_main='".$_REQUEST['css_title_font_family_main']."',
			css_title_font_family='".$_REQUEST['css_title_font_family']."',
			h2_size='".$_REQUEST['h2_size']."',
			h3_size='".$_REQUEST['h3_size']."',
			menu_upper='".$_REQUEST['menu_upper']."',
			use_random_bg='".$_REQUEST['use_random_bg']."',
			side_menu_line_height='".$_REQUEST['side_menu_line_height']."',
			menu_transparent='".$_REQUEST['menu_transparent']."',
			menu_center='".$_REQUEST['menu_center']."',
			header_center='".$_REQUEST['header_center']."',
			menu_placement='".$_REQUEST['menu_placement']."',
			menu_margin='".$_REQUEST['menu_margin']."',
			change_color_bg='".$_REQUEST['change_color_bg']."',
			boxes_rounded='".$_REQUEST['boxes_rounded']."',
			menu_use='".$_REQUEST['menu_use']."',
			pics_use='".$_REQUEST['pics_use']."',
			side_menu_use='".$_REQUEST['side_menu_use']."',
			theme_screen='".$_REQUEST['theme_screen']."',
			css_file='$new_css',
			inside_padding='".$_REQUEST['inside_padding']."',
			top_menu_padding='".$_REQUEST['top_menu_padding']."',
			title_size='".$_REQUEST['title_size']."',
			boxes_text='".$_REQUEST['boxes_text']."',
			boxes_link='".$_REQUEST['boxes_link']."',
			boxes_link_hover='".$_REQUEST['boxes_link_hover']."',
			submit_hover_background='".$_REQUEST['submit_hover_background']."',
			submit_hover_text='".$_REQUEST['submit_hover_text']."',
			submit_hover_border='".$_REQUEST['submit_hover_border']."',

			submit_disabled_background='".$_REQUEST['submit_disabled_background']."',
			submit_disabled_text='".$_REQUEST['submit_disabled_text']."',
			submit_disabled_border='".$_REQUEST['submit_disabled_border']."',
			background_cover='".$_REQUEST['background_cover']."',
			top_menu_spacing='".$_REQUEST['top_menu_spacing']."',
			full_screen_background='".$_REQUEST['full_screen_background']."',
			full_screen_opacity='".$_REQUEST['full_screen_opacity']."',
			full_screen_photo_box_shadow='$full_screen_photo_box_shadow',
			photos_box_shadow='$photos_box_shadow',
			boxes_box_shadow='$boxes_box_shadow',
			top_menu_box_shadow='$top_menu_box_shadow',
			header_box_shadow='$header_box_shadow',
			content_box_shadow='$content_box_shadow',
			css_external='".$_REQUEST['css_external']."',
			add_css='".$_REQUEST['add_css']."'

			WHERE css_id='".$_REQUEST['css_id']."'  ");   		

		$ck = doSQL("ms_css2", "*", "WHERE parent_css_id='".$_REQUEST['css_id']."' ");
		if($ck['css2_id'] <=0) { 
			insertSQL("ms_css2", "parent_css_id='".$_REQUEST['css_id']."' ");
		}


		$rgb = hex2rgb("#cc0");
		updateSQL("ms_css2", "
		 on_photo_border='".$_REQUEST['on_photo_border']."',
		on_photo_border_radius='".$_REQUEST['on_photo_border_radius']."',
		on_photo_bg='".$_REQUEST['on_photo_bg']."',
		on_photo_margin='".$_REQUEST['on_photo_margin']."',
		on_photo_width='".$_REQUEST['on_photo_width']."',
		on_photo_height='".$_REQUEST['on_photo_height']."',
		on_photo_text_width='".$_REQUEST['on_photo_text_width']."',
		on_photo_text_opacity='".$_REQUEST['on_photo_text_opacity']."',
		on_photo_title='".$_REQUEST['on_photo_title']."',
		on_photo_title_size='".$_REQUEST['on_photo_title_size']."',
		on_photo_text='".$_REQUEST['on_photo_text']."',
		on_photo_text_bg='".$_REQUEST['on_photo_text_bg']."',
		on_photo_box_shadow='".$on_photo_box_shadow."',
		thumb_listing_bg='".$_REQUEST['thumb_listing_bg']."',
		thumb_listing_border='".$_REQUEST['thumb_listing_border']."',
		thumb_listing_width='".$_REQUEST['thumb_listing_width']."',
		thumb_listing_height='".$_REQUEST['thumb_listing_height']."',
		thumb_listing_thumb_width='".$_REQUEST['thumb_listing_thumb_width']."',
		thumb_listing_thumb_height='".$_REQUEST['thumb_listing_thumb_height']."',
		thumb_listing_margin='".$_REQUEST['thumb_listing_margin']."',
		thumb_listing_padding='".$_REQUEST['thumb_listing_padding']."',
		thumb_listing_thumb_border='".$_REQUEST['thumb_listing_thumb_border']."',
		thumb_listing_border_radius='".$_REQUEST['thumb_listing_border_radius']."',
		thumb_listing_title='".$_REQUEST['thumb_listing_title']."',
		thumb_listing_title_size='".$_REQUEST['thumb_listing_title_size']."',
		thumb_listing_title_hover='".$_REQUEST['thumb_listing_title_hover']."',
		thumb_listing_text='".$_REQUEST['thumb_listing_text']."',
		thumb_listing_border_size='".$_REQUEST['thumb_listing_border_size']."',
		boxes_borders_size='".$_REQUEST['boxes_borders_size']."',
		on_photo_border_size='".$_REQUEST['on_photo_border_size']."',
		on_photo_border_style='".$_REQUEST['on_photo_border_style']."',
		thumb_listing_border_style='".$_REQUEST['thumb_listing_border_style']."',
		boxes_title_size='".$_REQUEST['boxes_title_size']."',
		boxes_borders_style='".$_REQUEST['boxes_borders_style']."',
		boxes_borders_where='".$_REQUEST['boxes_borders_where']."',
		thumb_listing_box_shadow='$thumb_listing_box_shadow',
		inside_bg_border_size='".$_REQUEST['inside_bg_border_size']."',
		inside_bg_border_style='".$_REQUEST['inside_bg_border_style']."',
		header_height='".$_REQUEST['header_height']."',
		side_main_bg_padding='".$_REQUEST['side_main_bg_padding']."',
		side_main_border_size='".$_REQUEST['side_main_border_size']."',
		side_main_border_style='".$_REQUEST['side_main_border_style']."',
		side_menu_link_border_size='".$_REQUEST['side_menu_link_border_size']."',
		side_menu_link_border_style='".$_REQUEST['side_menu_link_border_style']."',
		side_main_border_radius='".$_REQUEST['side_main_border_radius']."',
			inside_margin_top='".$_REQUEST['inside_margin_top']."',
		stacked_listing_padding='".$_REQUEST['stacked_listing_padding']."',

		thumb_nails_bg='".$_REQUEST['thumb_nails_bg']."',
		thumb_nails_border='".$_REQUEST['thumb_nails_border']."',
		thumb_nails_width='".$_REQUEST['thumb_nails_width']."',
		thumb_nails_height='".$_REQUEST['thumb_nails_height']."',
		thumb_nails_thumb_width='".$_REQUEST['thumb_nails_thumb_width']."',
		thumb_nails_thumb_height='".$_REQUEST['thumb_nails_thumb_height']."',
		thumb_nails_margin='".$_REQUEST['thumb_nails_margin']."',
		thumb_nails_padding='".$_REQUEST['thumb_nails_padding']."',
		thumb_nails_thumb_border='".$_REQUEST['thumb_nails_thumb_border']."',
		thumb_nails_border_radius='".$_REQUEST['thumb_nails_border_radius']."',
		thumb_nails_title='".$_REQUEST['thumb_nails_title']."',
		thumb_nails_title_size='".$_REQUEST['thumb_nails_title_size']."',
		thumb_nails_title_hover='".$_REQUEST['thumb_nails_title_hover']."',
		thumb_nails_text='".$_REQUEST['thumb_nails_text']."',
		thumb_nails_border_size='".$_REQUEST['thumb_nails_border_size']."',
		thumb_nails_border_style='".$_REQUEST['thumb_nails_border_style']."',
		thumb_nails_box_shadow='$thumb_nails_box_shadow',
		caption_align='".$_REQUEST['caption_align']."',
		caption_padding='".$_REQUEST['caption_padding']."',
		underline_links='".$_REQUEST['underline_links']."',
		underline_links_hover='".$_REQUEST['underline_links_hover']."',
		underline_menu_links='".$_REQUEST['underline_menu_links']."',
		underline_menu_links_hover='".$_REQUEST['underline_menu_links_hover']."',
		scroller_bg='".$_REQUEST['scroller_bg']."',
		scroller_border='".$_REQUEST['scroller_border']."',
		scroller_text='".$_REQUEST['scroller_text']."',
		scroller_handle='".$_REQUEST['scroller_handle']."',
		scroller_handle_bg='".$_REQUEST['scroller_handle_bg']."',
		scroller_handle_border='".$_REQUEST['scroller_handle_border']."',
		header_padding_tb='".$_REQUEST['header_padding_tb']."',
		boxes_img_width='".$_REQUEST['boxes_img_width']."',
		sm_background='".$_REQUEST['sm_background']."',
		sm_background_transparent='".$_REQUEST['sm_background_transparent']."',
		sm_border_bottom='".$_REQUEST['sm_border_bottom']."',
		sm_border_top='".$_REQUEST['sm_border_top']."',
		sm_text='".$_REQUEST['sm_text']."',
		sm_link_color='".$_REQUEST['sm_link_color']."',
		sm_link_hover='".$_REQUEST['sm_link_hover']."',
		sm_underline='".$_REQUEST['sm_underline']."',
		sm_underline_hover='".$_REQUEST['sm_underline_hover']."',
		sm_pin_top='".$_REQUEST['sm_pin_top']."',

		sm_font_family='".$_REQUEST['sm_font_family']."',
		sm_font_size='".$_REQUEST['sm_font_size']."',
		sm_text_shadow='".$sm_text_shadow."',
		sm_padding_lr='".$_REQUEST['sm_padding_lr']."',
		sm_padding_tb='".$_REQUEST['sm_padding_tb']."',
		sm_spacing='".$_REQUEST['sm_spacing']."',
		sm_border_top='".$_REQUEST['sm_border_top']."',
		sm_text_shadow_on='".$_REQUEST['sm_text_shadow_on']."',
		inside_opacity='".$_REQUEST['inside_opacity']."',
		header_font='".$_REQUEST['header_font']."',
		header_font_size='".$_REQUEST['header_font_size']."',

		header_opacity='".$_REQUEST['header_opacity']."',
		menu_opacity='".$_REQUEST['menu_opacity']."',
		form_opacity='".$_REQUEST['form_opacity']."',
		boxes_opacity='".$_REQUEST['boxes_opacity']."',
		thumb_listing_opacity='".$_REQUEST['thumb_listing_opacity']."',
		thumb_nails_opacity='".$_REQUEST['thumb_nails_opacity']."' ,
		side_menu_font_size='".$_REQUEST['side_menu_font_size']."',
		side_menu_label_size='".$_REQUEST['side_menu_label_size']."',
		page_icon_color='".$_REQUEST['page_icon_color']."',
		back_icon_color='".$_REQUEST['back_icon_color']."',
		h1_upper='".$_REQUEST['h1_upper']."',
		page_width_max='".$_REQUEST['page_width_max']."',

		add_bg_overlay='".$_REQUEST['add_bg_overlay']."',
		header_pin_top='".$_REQUEST['header_pin_top']."',
		header_code='".$_REQUEST['header_code']."',
		top_menu_side_borders='".$_REQUEST['top_menu_side_borders']."',
		top_menu_bg_hover='".$_REQUEST['top_menu_bg_hover']."',
		top_menu_border_l='".$_REQUEST['top_menu_border_l']."',
		top_menu_border_r='".$_REQUEST['top_menu_border_r']."',
		top_menu_button_transparent='".$_REQUEST['top_menu_button_transparent']."',

		menu_sep='".$_REQUEST['menu_sep']."',
		footer_outside='".$_REQUEST['footer_outside']."',
		footer_bg='".$_REQUEST['footer_bg']."',
		footer_text_color='".$_REQUEST['footer_text_color']."',
		footer_link_color='".$_REQUEST['footer_link_color']."',
		footer_link_hover='".$_REQUEST['footer_link_hover']."',
		footer_padding='".$_REQUEST['footer_padding']."',
		footer_font_size='".$_REQUEST['footer_font_size']."',
		header_100='".$_REQUEST['header_100']."',
		mobile_header_height='".$_REQUEST['mobile_header_height']."'



		WHERE parent_css_id='".$_REQUEST['css_id']."' ");

	if($_SERVER['HTTPS'] == "on") { 
		$setup['url'] = str_replace("http://","https://",$setup['url']);
	}
	$url = $setup['url']."".$setup['temp_url_folder']."/sy-style.php?csst=".$_REQUEST['css_id'];
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt ($ch, CURLOPT_USERAGENT, 'sytist/php');
    curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	$contents = curl_exec($ch);
	if (curl_errno($ch)) {
	  echo curl_error($ch);
	  echo "\n<br />";
	  $contents = '';
	} else {
	  curl_close($ch);
	}


	if (!is_string($contents) || !strlen($contents)) {
	echo "Failed to get contents.";
	$contents = file_get_contents($url);
	}

	// echo $contents;


$contents = preg_replace( '/\s+/', ' ', $contents );

		$fp = fopen("".$setup['path']."/".$setup['layouts_folder']."/".$new_css."", "w");
		$info =  stripslashes($contents); 
		fputs($fp, "$info\n");
		fclose($fp);

		
		
		}
		if($_SESSION['editthemeupgrade'] == "1") { 
			unset($_SESSION['editthemeupgrade']);
			$_SESSION['sm'] = "Theme Saved";
			session_write_close();
			 header("location: index.php");
			exit();

		} else { 
			$_SESSION['sm'] = "Theme Saved";
			session_write_close();
			 header("location: ".$_SERVER['PHP_SELF']."?css_id=".$_REQUEST['css_id']."");
			exit();
		}
	?>
	<?php 
	}
	} else {
	regForm();
}
?>	
<?php
if(!empty($_SESSION['sm'])) {
	?>
<script>
 $(document).ready(function(){
setTimeout(showSuccessMessage,400);
setTimeout(hideSuccessMessage,4000);

});
</script>

	<?php 
	$success_message = $_SESSION['sm'];
	unset($_SESSION['sm']);
}
?>
<div id="successMessage"><?php  print stripslashes($success_message);?></div>
<div id="errorMessage"><?php  print stripslashes($error_message);?></div>
<div id="loadingeditor" class="center"><h1>Loading Editor</h1></div>

<script type="text/javascript" src="jscolor/jscolor.js"></script>
<?php  if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 	$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$_REQUEST['css_id']."' "); ?>
 <script>
$(document).ready(function(){
	setInterval("changeLook()",100);
	setTimeout(function(){$("#themepreview").fadeIn(200)},200)
	setTimeout(function(){$("#themeeditarea").fadeIn(200)},200)
	setTimeout(function(){$("#editorcontainer").fadeIn(200)},200)
	setTimeout(function(){$("#intro").fadeIn(200)},200)
	setTimeout(function(){$("#loadingeditor").fadeOut(100)},100)


	 mytips(".tip","tooltip");
	 myinputtips(".inputtip","tooltip");

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

	/* ######### ON PHOTO PREVIEWS ################## */

	$(".preview").hover(
	  function () {
		$(this).find('.previewtext').slideDown(200);
	  },
	  function () {
		$(this).find('.previewtext').slideUp(200);
	  }
	);

	 resizeOnPhotoPhoto();

//	$(document).bind("mouseup", Kolich.Selector.mouseup);

});
window.highlight = function(id) {
//	alert(id);
    var selection = window.getSelection().getRangeAt(0);
    var selectedText = selection.extractContents();
    var span = document.createElement("span");
	$(span).css({"font-wieght":"bold", "background":"#890000", "font-size":$("#"+id).val()+"px"});
//    span.style.backgroundColor = "yellow";
    span.appendChild(selectedText);
    span.onclick = function (ev) {
        this.parentNode.insertBefore(document.createTextNode(this.innerHTML), this);
        this.parentNode.removeChild(this);
    }
    selection.insertNode(span);
}

</script>
<style>
#themeeditheader { position: fixed; top: 0; left: 10%; background: #c4c4c4; width: 90%; height: 40px; 
-moz-box-shadow: inset 0px -2px 5px #888;
-webkit-box-shadow: inset 0px -2px 5px #888;
box-shadow: inset 0px -2px 5px #888;
} 
#themeeditheader .inner { padding: 8px; } 

#themeeditarea { position: fixed; top: 40px; left: 10%; background: #e4e4e4; width: 100%; height: 118px; border-top: solid 1px #f4f4f4; border-bottom: solid 1px #949494;  } 
#themeeditarea .inner { padding: 8px;  } 

#themeeditarea .inner .group { float: left; border-right: solid 1px #f4f4f4; margin-right: 16px; padding-right: 8px; height: 104px; } 
#themeeditarea .inner .group div { padding: 2px 0; } 

#thememenu { position: fixed; left: 0; top: 0; background: #121212; height: 100%; width: 10%; color: #c4c4c4; } 
#thememenu .inner { padding: 0; } 
#thememenu ul { list-style: none; } 
#thememenu ul li {  } 

#thememenu ul li a  { display: block;   color: #c4c4c4; padding: 2px 8px; border-top: solid 1px #242424; border-bottom: solid 1px #000000; }
#thememenu ul li a:hover  { background: #414141; text-decoration: none; border-top: solid 1px #646464; border-bottom: solid 1px #010101; color: #F4F4F4}
.menuon { background: #545454; text-decoration: none; border-top: solid 1px #646464; border-bottom: solid 1px #010101; color: #F4F4F4; }

#themepreview { position: fixed; top: 160px; left: 10%; width: 90%; height: 80%; overflow-y: scroll;  display: none;} 
#selectbackground { top: 5%; position: fixed; z-index: 100; width: 600px; left: 50%; margin-left: -300px; overflow-y: scroll; background: #FFFFFF; border: solid 1px #949494; display: none; height: 90%;  	box-shadow: 0px 0px 20px rgba(0, 0, 0, .8); } 
#selectbackground .loading { background:  url('graphics/loading1.gif') no-repeat center center; width: 100%; height: 100%;  } 

.editoption { display: none; } 
.incdec { margin: 0; } 
.themeinput { margin: 0; text-align: center; padding: 2px; box-shadow: none; } 
#fullscreenbackground { position: fixed; top: 160px; left: 10%; width: 90%; height: 80%; background: #890000;  opacity: .8; z-index: 5;} 

#topnav ul { list-style: none; } 
#topnav ul li { display: inline; } 
#topnav ul li a  { float: right;  color: #c4c4c4; padding: 8px; border: solid 1px #242424; background: #000000; margin-left: 8px; }
#topnav ul li a:hover  { background: #414141; text-decoration: none; border-top: solid 1px #646464; border-bottom: solid 1px #010101; color: #F4F4F4}
a.savechanges { color: #FFFFFF; font-weight: bold; } 
</style>
<?php  
function regForm() {
	global $tr, $_REQUEST, $setup, $site_setup,$sytist_store;
	$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$_REQUEST['css_id']."' "); ?>
<div id="selectbackground">
<div  class="loading"></div>
</div>

<div id="editorcontainer" style="display: none;">



<div id="thememenu">
<div class="inner">
<div style="padding: 8px;">
<form method="get" action="<?php print $_SERVER['PHP_SELF'];?>" name="themename">
<select name="css_id"  onchange="this.form.submit();" style="max-width: 100%;">
<?php 
	$mcsss = whileSQL("ms_css", "*", "ORDER BY css_name ASC ");
while($mcss = mysqli_fetch_array($mcsss)) { ?>
<option value="<?php print $mcss['css_id'];?>" <?php if($mcss['css_id'] == $css['css_id']) { print "selected"; } ?>><?php print $mcss['css_name'];?></option>
<?php } ?>
</select>
</form>

</div>
<div>&nbsp;</div>

<ul>
<li><a href="" onClick="editArea('bgmenu','mainarea'); return false;" id="menu-bgmenu" class="menu-item">Background</a></li>
<li><a href="" onClick="editArea('content','mainarea'); return false;" id="menu-content" class="menu-item">Content Area</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li><a href="" onClick="editAreaSub('contentbs','mainarea'); return false;" id="menu-contentbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>
<li><a href="" onClick="editArea('mainfont', 'mainarea'); return false;" id="menu-mainfont" class="menu-item">Fonts & Size</a></li>
<li><a href="" onClick="editArea('tccolors', 'mainarea'); return false;" id="menu-tccolors" class="menu-item">Text & Link Colors</a></li>

<li><a href="" onClick="editArea('headerlocal','mainarea'); return false;" id="menu-headerlocal" class="menu-item">Header</a>

<ul class="sub" style="display: none; text-indent: 20px;">
<li><a href="" onClick="editAreaSub('headerbs','mainarea'); return false;" id="menu-headerbs" class="menu-item">Box Shadow</a></li>
</ul>

</li>
<li><a href="" onClick="editArea('mainmenuedit','mainarea'); return false;" id="menu-mainmenuedit" class="menu-item">Top Main Menu</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li><a href="" onClick="editAreaSub('menubs','mainarea'); return false;" id="menu-menubs" class="menu-item">Box Shadow</a></li>
</ul>
</li>
<li><a href="" onClick="editArea('shopmenuedit','mainarea'); return false;" id="menu-shopmenuedit" class="menu-item">Top Mini Menu</a></li>
<li><a href="" onClick="editArea('sidemenuoptions',''); return false;" id="menu-sidemenuoptions" class="menu-item">Side Bar</a></li>

<li><a href="" onClick="editArea('forms', 'formarea'); return false;" id="menu-forms" class="menu-item">Forms</a></li>
<li><a href="" onClick="editArea('photos','photosarea'); return false;" id="menu-photos" class="menu-item">Photos</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li><a href="" onClick="editAreaSub('photosbs','photosarea'); return false;" id="menu-photosbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>
<li><a href="" onClick="editArea('fullscreenphotos','fullscreenphotosarea'); return false;" id="menu-fullscreenphotos" class="menu-item">Full Screen Photos</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li><a href="" onClick="editAreaSub('fs-photosbs','fullscreenphotosarea'); return false;" id="menu-fs-photosbs" class="menu-item">Box Shadow</a></li>
</ul>

</li>
<li><a href="" onClick="editArea('boxed','boxedarea'); return false;" id="menu-boxed" class="menu-item">Cart / Page Listing</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li id="boxessub"><a href="" onClick="editAreaSub('boxesbs','boxedarea'); return false;" id="menu-boxesbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>

<li><a href="" onClick="editArea('onphotopreviewsedit','onphotopreviewarea'); return false;" id="menu-onphotopreviewsedit" class="menu-item">On Photo Listing</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li id="boxessub"><a href="" onClick="editAreaSub('onphotopreviewbs','onphotopreviewarea'); return false;" id="menu-onphotopreviewbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>

<li><a href="" onClick="editArea('thumbnaillistingedit','thumbnaillistingarea'); return false;" id="menu-thumbnaillistingedit" class="menu-item">Thumbnail Listing</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li id="boxessub"><a href="" onClick="editAreaSub('thumbnaillistingbs','thumbnaillistingarea'); return false;" id="menu-thumbnaillistingbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>

<li><a href="" onClick="editArea('thumb_nailsedit','thumb_nailsarea'); return false;" id="menu-thumb_nailsedit" class="menu-item">Styled Thumbnail Gallery</a>
<ul class="sub" style="display: none; text-indent: 20px;">
<li id="boxessub"><a href="" onClick="editAreaSub('thumb_nailsbs','thumb_nailsarea'); return false;" id="menu-thumb_nailsbs" class="menu-item">Box Shadow</a></li>
</ul>
</li>

<li><a href="" onClick="editArea('footeredit','mainarea'); return false;" id="menu-thumbnaillistingedit" class="menu-item">Footer</a></li>

<li><a href="" onClick="editadditionalcss(); return false;">Additional CSS</a></li>

</ul>
<div>&nbsp;</div>
<ul>
<li><a href="" onClick="editArea('theme_info', 'mainarea'); return false;" id="menu-theme_info" class="menu-item">Rename Theme</a></li>
<li><a href="export.theme.php?css_id=<?php print $css['css_id']; ?>" >Export</a>
<li><a href="index.php?do=look&view=css&subdo=duplicateCss&css_id=<?php print $css['css_id']; ?>"  onClick="return confirm('Are you sure you want to duplicate the theme  <?php print strip_tags($css['css_name']);?>  and create a new one ? ');">Duplicate</a>
<li><a href="index.php?do=look&view=css&subdo=deleteCss&css_id=<?php print $css['css_id'];?>"   onClick="return confirm('Are you sure you want to delete the <?php print strip_tags($css['css_name']);?> theme?');" >Delete</a></li>
</ul>
</div></div>
<script>
	function saveForm() { 
		$("#themeeditor").submit();
	}
	function submitloading() { 
	$("#savechanges").html('Saving...........');
	}
</script>


<form name="themeeditor" id="themeeditor" action="<?php print $_SERVER['PHP_SELF'];?>" method="post" style="padding:0; margin: 0;"  onSubmit="return submitloading();">
<div id="themeeditheader">
<div class="inner">
<div class="left" style="width: 30%;">
<h1> <?php if($setup['unbranded'] !== true) { ?><i>sytist</i> <?php } ?>Theme Editor</h1>
</div>
<div class="left center"style="width: 10%;">
<div id="log" style="z-index: 1000; background: #FFFFFF; position: fixed;"></div>

</div>
<div class="right textright" style="width: 60%;">
<input type="hidden"  size=30 name="css_order" value="<?php  print htmlspecialchars(stripslashes($css['css_order']));?>">
<input type="hidden"  size=30 name="codeid" value="<?php  print htmlspecialchars(stripslashes($css['codeid']));?>">
<div id="topnav"">
<ul >
<li><a href="#" onclick="$(this).parents('form').submit();" class="savechanges" id="savechanges">Save Changes</a></li>
<li><a href="<?php tempFolder();?>/<?php print $site_setup['index_page'];?>?previewTheme=<?php print $css['css_id'];?>" target="_blank">Preview On Website</a></li>
<?php if($site_setup['css'] !== $css['css_id']) { ?> 
<li><a href="index.php?do=look&view=css&subdo=setCss&css_id=<?php print $css['css_id']; ?>" onClick="return confirm('Are you sure you want to set your  theme to this theme? ');">Make This My Theme</a></li>
<?php } ?>
<li><a href="index.php?do=look&view=css">Exit</a></li>
</ul>
</div>
</div>
<div class="clear"></div>
</div>
</div>

<div id="themeeditarea" style="display: none;">
<div class="inner">
<div>

<div class="editoption" id="intro">
Welcome to the theme editor. Be sure you click the Save Changes button before leaving this page or your edits will not be saved!
<br><br>
Click the sections to the left to edit. You will see your changes below in the preview area. 
</div>

<div id="bgmenu" class="editoption">
<div class="group ">
<div>Background Color</div>
<div><input type="text"  size=10 id="outsideBg" name="outside_bg" value="<?php  print $css['outside_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
</div>


<!--<div class="group ">
 <div><input type="checkbox" name="change_color_bg" id="change_color_bg" value="1" <?php if($css['change_color_bg'] == "1") { print "checked"; } ?>> Changing Color Background</div>
<div><input type="checkbox" name="use_random_bg" id="use_random_bg" value="1" <?php if($css['use_random_bg'] == "1") { print "checked"; } ?> onchange="changeLook();"> <b>Random background photo feature</b><br>This will take the preview photo from a page or category or the first landscape photo from page photos<br>and make it the background photo of the page. <br>When using this you should upload a background photo to the right you want to be your default <br>when there is no available photo to show. It should be around 1600 - 2000 pixels in width.</div>
</div>
 -->
<div id="backgroundOptions">
<div class="group ">
<div>Background Image</div>
<div>
<a href="" onClick="openBackgroud(); return false;" class="bluelink">Select Background</a>
<input type="hidden"  size=20 class="field100" name="outside_bg_image" id="outside_bg_image" value="<?php  print $css['outside_bg_image'];?>">
<input type="hidden"name="outside_bg_image_old" id="outside_bg_image_old" value="<?php  print $css['outside_bg_image'];?>"></div>
<div id="bgphotocontainer"><?php if(!empty($css['outside_bg_image'])) { ?><img src="<?php  print $css['outside_bg_image'];?>" style="height: 50px; width: auto;" id="bgphoto"><?php } ?></div>
<div>
<input type="hidden"name="bg_image_style" id="bg_image_style" value="<?php  print $css['bg_image_style'];?>">
<input type="hidden"name="bg_image_style_old" id="bg_image_style_old" value="<?php  print $css['bg_image_style'];?>">


</div>
</div>
</div>


<div class="group">
<div>Add Vignette Overlay</div>
<div>
<select name="add_bg_overlay" id="add_bg_overlay">
<option value="0">No Overlay</option>
<option value="25" <?php if($css['add_bg_overlay'] == "25") { print "selected"; } ?>> 25</option>
<option value="50" <?php if($css['add_bg_overlay'] == "50") { print "selected"; } ?>>50</option>
<option value="75" <?php if($css['add_bg_overlay'] == "75") { print "selected"; } ?>>75</option>
<option value="100" <?php if($css['add_bg_overlay'] == "100") { print "selected"; } ?>>100</option>
<option value="125" <?php if($css['add_bg_overlay'] == "125") { print "selected"; } ?>>125</option>
<option value="150" <?php if($css['add_bg_overlay'] == "150") { print "selected"; } ?>>150</option>
<option value="175" <?php if($css['add_bg_overlay'] == "175") { print "selected"; } ?>>175</option>
<option value="200" <?php if($css['add_bg_overlay'] == "200") { print "selected"; } ?>>200</option>
</select>
</div>
</div>

</div>
<div id="content" class="editoption">

<div class="group ">
<div>Content Page Width</div>
<div>
<select name="page_width" id="page_width">
<option value="" disabled>Percentage Width</option>
<?php 
$pw = 100;
while($pw >= 90) { ?>
<option value="<?php print $pw;?>%" <?php if($css['page_width'] == $pw."%") { print "selected"; } ?>><?php print $pw;?>%</option>
<?php 
	$pw--; } 
?>

</select>
</div>
<div>Maximum width in pixels</div>
<div> 	
<a href="" onClick="return false;" class="incdec" acdc="0" targetid="page_width_max"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Max Width" type="text" size="6" name="page_width_max" id="page_width_max" value="<?php print $css['page_width_max'];?>" min="800" max="2000"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="page_width_max"><?php print ai_arrow_up;?></a>

</div>
</div>

<div class="group">
<div><input type="checkbox" name="inside_bg_transparent" id="inside_bg_transparent" value="1" <?php if($css['inside_bg_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent  Background</div>
	<div  id="inside_bg_options" style="display: <?php if($css['inside_bg_transparent'] == "1") { print "none"; } else { print "block"; } ?>;">
	<div class="left">
	<div>Background Color</div>
	<div><input type="text"  size=10 name="inside_bg" id="insideBg" value="<?php  print $css['inside_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>
	<div class="left" style="margin-left: 8px;">
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="inside_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="inside_opacity" id="inside_opacity" value="<?php print $css['inside_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="inside_opacity"><?php print ai_arrow_up;?></a>
	</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>
	<div class="group">
	<div>Border</div><div><input type="text"  size=10 name="inside_bg_border" id="inside_bg_border" value="<?php  print $css['inside_bg_border'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="inside_bg_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="inside_bg_border_size" id="inside_bg_border_size" value="<?php print $css['inside_bg_border_size'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="inside_bg_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="inside_bg_border_style" id="inside_bg_border_style" class="themeinput">
	<option value="solid" <?php if($css['inside_bg_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['inside_bg_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['inside_bg_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['inside_bg_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['inside_bg_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['inside_bg_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['inside_bg_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['inside_bg_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>
	</div>

<div class="group">
<div>Content Padding</div>
<div>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="inside_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Content Padding" type="text" size="2" name="inside_padding" id="inside_padding" value="<?php print $css['inside_padding'];?>" min="0" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="inside_padding"><?php print ai_arrow_up;?></a>
</div>
</div>
<div class="group">
<div>Content Margin Top</div>
<div>
 	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="inside_margin_top"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Content Padding" type="text" size="2" name="inside_margin_top" id="inside_margin_top" value="<?php print $css['inside_margin_top'];?>" min="0" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="inside_margin_top"><?php print ai_arrow_up;?></a>
</div>
</div>

<div class="group">
<div>Icon Color</div>
<div><input type="text"  size=10 name="page_icon_color" id="page_icon_color" value="<?php  print $css['page_icon_color'];?>" class="color themeinput" onchange="changeLook();"></div>
</div>


</div>
<div id="contentbs" class="editoption">
<?php boxShadows($css['content_box_shadow'],"content"); ?>
</div>





<?php #########################################  FONT ################################################## ?>

<div id="mainfont" class="editoption">
<div class="group">
<div><a href="" onclick="openFrame('w-font-preview.php?css_id=<?php print $_REQUEST['css_id'];?>'); return false;" class="bluelink"><?php print ai_edit;?> Manage Google Fonts</a></div>
<div>Web fonts by Google.</div>
</div>
	<div class="group ">
	<div >Main Font Family</div><div >
	<select name="css_font_family_main" id="cssFontFamilyMain" onchange="changeLook();"  class="themeinput">
	<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
	if(mysqli_num_rows($fonts) > 0) { ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>
			<?php } 
			while($font = mysqli_fetch_array($fonts)) { 
				$font = explode(":", $font['font']);

				?>
		<option style="font-family: <?php print $font[0];?>;" value="<?php print  $font[0];?>" <?php if($css['css_font_family_main'] == $font[0]) { print "selected"; } ?>><?php print  $font[0];?></option>
		<?php } ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
	<?php
	$sfonts = explode("\r\n",$site_setup['standard_fonts']);
	foreach($sfonts AS $sfont) {  ?>
		<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($css['css_font_family_main'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
	<?php } ?>
	</select>
</div>
<!-- 
	<div >Or enter font name here</div>
	<div ><input type="text"  size=15 class="field100 themeinput" name="css_font_family" id="cssFontFamily" value="<?php  print htmlspecialchars(stripslashes($css['css_font_family']));?>" onchange="changeLook();"></div>
	-->
	<div>Font Size</div>
	<div>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="fontSize"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Font Size" type="text" size="2" name="font_size" id="fontSize" value="<?php print $css['font_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="fontSize"><?php print ai_arrow_up;?></a>
	</div>
</div>


<div class="group ">
	<div >Page Titles Font Family</div>
	<div >
	<select name="css_title_font_family_main" id="css_title_font_family_main" onchange="changeLook();"  class="themeinput">

		<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
		if(mysqli_num_rows($fonts) > 0) { ?>
			<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>

				<?php } 
				while($font = mysqli_fetch_array($fonts)) { 
				$font = explode(":", $font['font']);	
					?>
			<option style="font-family: <?php print $font[0];?>;" value="<?php print $font[0];?>" <?php if($css['css_title_font_family_main'] == $font[0]) { print "selected"; } ?>><?php print $font[0];?></option>
			<?php } ?>
			<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
		<?php
		$sfonts = explode("\r\n",$site_setup['standard_fonts']);
		foreach($sfonts AS $sfont) {  ?>
			<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($css['css_title_font_family_main'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
		<?php } ?>
		
	</select>
</div>
<!-- 
	<div >Or enter font name here</div>
	<div ><input type="text"  size=15 class="field100 themeinput" name="css_title_font_family" id="css_title_font_family" value="<?php  print htmlspecialchars(stripslashes($css['css_title_font_family']));?>" onchange="changeLook();"></div>
	-->
		<div><input type="checkbox" name="h1_upper" id="h1_upper" value="1" <?php if($css['h1_upper'] == "1") { print "checked"; } ?> onchange="changeLook();"> Uppercase Page Titles</div>

</div>
<div class="group ">
		<div >H1 Size</div>
		<div >
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="titleSize"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Header Size" type="text" size="2" name="title_size" id="titleSize" value="<?php print $css['title_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="titleSize"><?php print ai_arrow_up;?></a>

	</div>

		<div >H2 Size</div>
		<div >
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="h2_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Header Size" type="text" size="2" name="h2_size" id="h2_size" value="<?php print $css['h2_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="h2_size"><?php print ai_arrow_up;?></a>

	</div>

	</div>

	<div class="group ">
		<div >H3 Size</div>
		<div >
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="h3_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Header Size" type="text" size="2" name="h3_size" id="h3_size" value="<?php print $css['h3_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="h3_size"><?php print ai_arrow_up;?></a>
		</div>
	</div>
	<div class="group ">
		<div>Top Menu Font Family</div>
		<div>
		<select name="menu_font" id="menu_font" onchange="changeLook();"  class="themeinput">
		<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
		if(mysqli_num_rows($fonts) > 0) { ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>

		<?php } 
		while($font = mysqli_fetch_array($fonts)) { 
			$font = explode(":", $font['font']);	

			?>
		<option style="font-family: <?php print $font[0];?>;" value="<?php print $font[0];?>" <?php if($css['menu_font'] == $font[0]) { print "selected"; } ?>><?php print $font[0];?></option>
		<?php } ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
		<?php
		$sfonts = explode("\r\n",$site_setup['standard_fonts']);
		foreach($sfonts AS $sfont) {  ?>
		<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($css['menu_font'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
		<?php } ?>

		</select>
		</div>
		<div>Font Size</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="footer_menu_font_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Font Size" type="text" size="2" name="footer_menu_font_size" id="footer_menu_font_size" value="<?php print $css['footer_menu_font_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="footer_menu_font_size"><?php print ai_arrow_up;?></a>

		</div>
		<!-- 
		<div>Or enter font name here</div>
		<div><input type="text"  size=15 class="field100 themeinput" name="menu_font_other" id="cssFontFamily" value="<?php  print htmlspecialchars(stripslashes($css['menu_font_other']));?>" onchange="changeLook();"></div> -->
	</div>
	<div class="group ">
		<div>Top Mini Menu Font Family</div>
		<div>
		<select name="sm_font_family" id="sm_font_family" onchange="changeLook();"  class="themeinput">
		<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
		if(mysqli_num_rows($fonts) > 0) { ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>

		<?php } 
		while($font = mysqli_fetch_array($fonts)) { 
				$font = explode(":", $font['font']);	
			?>
		<option style="font-family: <?php print $font[0];?>;" value="<?php print $font[0];?>" <?php if($css['sm_font_family'] == $font[0]) { print "selected"; } ?>><?php print $font[0];?></option>
		<?php } ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
		<?php
		$sfonts = explode("\r\n",$site_setup['standard_fonts']);
		foreach($sfonts AS $sfont) {  ?>
		<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($css['sm_font_family'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
		<?php } ?>

		</select>
		</div>
		<!-- 
		<div>Or enter font name here</div>
		<div><input type="text"  size=15 class="field100 themeinput" name="sm_font_family_other" id="sm_font_family_other" value="<?php  print htmlspecialchars(stripslashes($css['sm_font_family_other']));?>" onchange="changeLook();"></div> -->
		<div>Font Size</div>
		<div>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_font_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Font Size" type="text" size="2" name="sm_font_size" id="sm_font_size" value="<?php print $css['sm_font_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_font_size"><?php print ai_arrow_up;?></a>



		</div>
		</div>


	<div class="group ">
		<div>Header Font</div>
		<div>
		<select name="header_font" id="header_font" onchange="changeLook();"  class="themeinput">
		<?php $fonts = whileSQL("ms_google_fonts", "*", "WHERE theme='".$_REQUEST['css_id']."' ORDER BY font ASC ");
		if(mysqli_num_rows($fonts) > 0) { ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Google Fonts</option>

		<?php } 
		while($font = mysqli_fetch_array($fonts)) { 
				$font = explode(":", $font['font']);	
			?>
		<option style="font-family: <?php print $font[0];?>;" value="<?php print $font[0];?>" <?php if($css['header_font'] == $font[0]) { print "selected"; } ?>><?php print $font[0];?></option>
		<?php } ?>
		<option value="" disabled style="color: #890000; font-weight: bold;">Standard Fonts</option>
		<?php
		$sfonts = explode("\r\n",$site_setup['standard_fonts']);
		foreach($sfonts AS $sfont) {  ?>
		<option style="font-family: <?php print $sfont;?>;" value="<?php print $sfont;?>" <?php if($css['header_font'] == $sfont) { print "selected"; } ?>><?php print $sfont;?></option>
		<?php } ?>

		</select>
		</div>
		<!-- 
		<div>Or enter font name here</div>
		<div><input type="text"  size=15 class="field100 themeinput" name="header_font_other" id="header_font_other" value="<?php  print htmlspecialchars(stripslashes($css['header_font_other']));?>" onchange="changeLook();"></div> -->
		<div>Font Size</div>
		<div>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="header_font_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Font Size" type="text" size="2" name="header_font_size" id="header_font_size" value="<?php print $css['header_font_size'];?>" min="6" max="120"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="header_font_size"><?php print ai_arrow_up;?></a>



		</div>
		</div>

</div>


<?php #########################################  HEADER  ################################################## ?>


<div id="headerlocal" class="editoption">

	<div class="group ">
		<div><input type="checkbox" name="header_wide" id="header_wide" value="1" <?php if($css['header_wide'] == "1") { print "checked"; } ?> onchange="changeLook();"> Make Background 100% Wide</div>
		<div><input type="checkbox" name="header_100" id="header_100" value="1" <?php if($css['header_100'] == "1") { print "checked"; } ?> onchange="changeLook();"> Make Entire Header 100% Wide</div>


		<div><input type="checkbox" name="header_transparent" id="header_transparent" value="1" <?php if($css['header_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent Background</div>
		<div><a href="" onClick="openHeader(); return false;" class="bluelink">Edit Header Content</a></div>
	</div>
		<div id="headerColors" style="<?php if($css['header_transparent'] == "1") { print "display: none; "; } ?>">
			<div class="group ">
				<div><span >Background Color</span></div>
				<div><input type="text"  size=10 name="header_bg" id="header_bg" value="<?php  print $css['header_bg'];?>" class="color themeinput"  onchange="changeLook();"></div>
				<div>Opacity</div>
				<div>
				<a href="" onClick="return false;" class="incdec" acdc="0" targetid="header_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="header_opacity" id="header_opacity" value="<?php print $css['header_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="header_opacity"><?php print ai_arrow_up;?></a>
				</div>

			</div>
		</div>
	<div class="group ">
	<div>Height</div>
	<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="header_height"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Height" type="text" size="2" name="header_height" id="header_height" value="<?php print $css['header_height'];?>" min="0" max="600"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="header_height"><?php print ai_arrow_up;?></a>
	</div>
	<div><input type="checkbox" name="header_pin_top" id="header_pin_top" value="1" <?php if($css['header_pin_top'] == "1") { print "checked"; } ?>> Pin to top of screen</div>
		<div><input type="checkbox"  name="header_center" id="header_center"  value="1" <?php if($css['header_center'] == "1") { print " checked"; } ?> onChange="changeLook();"> Center Header Content</div>






	</div>
	<div class="group">
		<div>Padding Left / Right</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="header_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding" type="text" size="2" name="header_padding" id="header_padding" value="<?php print $css['header_padding'];?>" min="0" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="header_padding"><?php print ai_arrow_up;?></a>
		</div>
		<div>Padding Top / Bottom</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="header_padding_tb"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding" type="text" size="2" name="header_padding_tb" id="header_padding_tb" value="<?php print $css['header_padding_tb'];?>" min="0" max="200"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="header_padding_tb"><?php print ai_arrow_up;?></a>
		</div>

	</div>
	<div class="group ">
		<div>Text Color</div>
		<div><input type="text"  size=10 name="header_font_color" id="header_font_color" value="<?php  print $css['header_font_color'];?>" class="color themeinput"  onchange="changeLook();"></div>
	</div>

	<div class="group ">
	<div>Mobile Height</div>
	<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="mobile_header_height"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Height" type="text" size="2" name="mobile_header_height" id="mobile_header_height" value="<?php print $css['mobile_header_height'];?>" min="0" max="600"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="mobile_header_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group ">
		<div><input type="checkbox" name="header_text_shadow_on" id="header_text_shadow_on" value="1" <?php if($css['header_text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"> Text Shadow</div>

		<?php $ts = explode(" ",$css['header_text_shadow']);
		if(is_array($ts)) { 
			$header_text_shadow_h = $ts[0];
			$header_text_shadow_v = $ts[1];
			$header_text_shadow_b = $ts[2];
			$header_text_shadow_c = $ts[3];
		}
		?>
		<div id="headerts" style="<?php if($css['header_text_shadow_on'] != "1") { print "display: none;"; } ?>">
		
		<div>
		<select name="header_text_shadow_h" id="header_text_shadow_h"  onchange="changeLook();" title="Horizontal offset">
		<?php 
		$x = -10;
		while($x <=10) { ?>
		<option value="<?php print$x;?>" <?php if($x == $header_text_shadow_h) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
		<?php
			$x = $x + 1;
		}
		?>
		</select>

		<select name="header_text_shadow_v" id="header_text_shadow_v"  onchange="changeLook();" title="Verticle offset">
		<?php 
		$x = -10;
		while($x <=10) { ?>
		<option value="<?php print$x;?>" <?php if($x == $header_text_shadow_v) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
		<?php
			$x = $x + 1;
		}
		?>
		</select>

		<select name="header_text_shadow_b" id="header_text_shadow_b"  onchange="changeLook();" title="Blur">
		<?php 
		$x = 0;
		while($x <=10) { ?>
		<option value="<?php print$x;?>" <?php if($x == $header_text_shadow_b) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
		<?php
			$x = $x + 1;
		}
		?>
		</select>

	 <input type="text"  size=10 name="header_text_shadow_c" id="header_text_shadow_c" value="<?php  print $header_text_shadow_c;?>" class="color themeinput"  onchange="changeLook();">
		</div>
	</div>
	</div>


	</div>

<div id="headerbs" class="editoption">
<?php boxShadows($css['header_box_shadow'],"header"); ?>
</div>



<?php ################################################ MAIN MENU ######################################################## ?>

<div id="mainmenuedit"  class="editoption">
	<div class="group ">
		<div>Menu Placement</div><div>
		<select name="menu_placement" id="menu_placement"  onchange="changeLook();"  class="themeinput">
		<option value="below" <?php if($css['menu_placement'] == "below") { print "selected"; } ?>>Below header / logo</option>
		<option value="left" <?php if($css['menu_placement'] == "left") { print "selected"; } ?>>Left of header / logo</option>
		<option value="right" <?php if($css['menu_placement'] == "right") { print "selected"; } ?>>Right header / logo Float Right</option>
		<option value="rightleft" <?php if($css['menu_placement'] == "rightleft") { print "selected"; } ?>>Right header / logo Float Left</option>
		</select>
		</div>
		<div>
		<input type="hidden" name="menu_use" id="menu_use" value="topmain">
		</div>
		<div>Spacing</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="top_menu_spacing"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="top_menu_spacing" id="top_menu_spacing" value="<?php print $css['top_menu_spacing'];?>" max="200" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="top_menu_spacing"><?php print ai_arrow_up;?></a>
		</div>
		</div>




	<div class="group ">
		<div>Padding T/B</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="main_menu_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding top & bottom" type="text" size="2" name="main_menu_padding" id="main_menu_padding" value="<?php print $css['main_menu_padding'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="main_menu_padding"><?php print ai_arrow_up;?></a>

		</div>


		<div>Padding L/R</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="top_menu_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding left & right" type="text" size="2" name="top_menu_padding" id="top_menu_padding" value="<?php print $css['top_menu_padding'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="top_menu_padding"><?php print ai_arrow_up;?></a>


		</div>

	</div>

	<div class="group ">
		<div><input type="checkbox" name="menu_transparent" id="menu_transparent" value="1" <?php if($css['menu_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent Background</div>
		<div><input type="checkbox" name="main_menu_wide" id="main_menu_wide" value="1" <?php if($css['main_menu_wide'] == "1") { print "checked"; } ?> onchange="changeLook();"> Make Background 100% Wide</div>
		<div><input type="checkbox" name="menu_upper" id="menu_upper" value="1" <?php if($css['menu_upper'] == "1") { print "checked"; } ?> onchange="changeLook();"> Upper Case All Menu Text</div>
		<div><input type="checkbox" name="menu_center" id="menu_center" value="1" <?php if($css['menu_center'] == "1") { print "checked"; } ?> onchange="changeLook();"> Center Menu Text</div>
</div>

	<div id="menu_options" style="display: <?php if($css['menu_transparent'] == "1") { print "none"; } else { print "block"; } ?>;">
		<div class="group ">
			<div>Background Color</div><div><input type="text"  size="6" name="menu_color" id="menu_color" value="<?php  print $css['menu_color'];?>" class="color themeinput" onchange="changeLook();"></div>
				<div>Opacity</div>
				<div>
				<a href="" onClick="return false;" class="incdec" acdc="0" targetid="menu_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="menu_opacity" id="menu_opacity" value="<?php print $css['menu_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="menu_opacity"><?php print ai_arrow_up;?></a>
				</div>

		</div>
		<div class="group ">
			<div>Border Top</div>
			<div><input type="text"  size="6" name="menu_border_a" id="menu_border_a" value="<?php  print $css['menu_border_a'];?>" class="color themeinput" onchange="changeLook();"></div>
			<div>Border Bottom</div>
			<div><input type="text"  size="6" name="menu_border_b" id="menu_border_b"  value="<?php  print $css['menu_border_b'];?>" class="color themeinput" onchange="changeLook();"></div>
		</div>
	</div>


<!-- 	<div class="group ">
		<div>Text Color</div>
		<div><input type="text"  size="6" id="menu_font_color" name="menu_font_color" value="<?php  print $css['menu_font_color'];?>" class="color themeinput"  onchange="changeLook();"></div>
	</div>
-->

<div class="group">
<div><input type="checkbox" name="underline_menu_links" id="underline_menu_links" value="1" <?php if($css['underline_menu_links'] == "1") { print "checked"; } ?>> Underline Links </div>
<div><input type="checkbox" name="underline_menu_links_hover" id="underline_menu_links_hover" value="1" <?php if($css['underline_menu_links_hover'] == "1") { print "checked"; } ?>> Underline Links On Hover</div>

		<div><input type="checkbox" name="menu_text_shadow_on" id="menu_text_shadow_on" value="1" <?php if($css['menu_text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"> Text Shadow</div>
		<?php $ts = explode(" ",$css['menu_text_shadow']);
		if(is_array($ts)) { 
		$menu_text_shadow_h = str_replace("px","",$ts[0]);
		$menu_text_shadow_v = str_replace("px","",$ts[1]);
		$menu_text_shadow_b = str_replace("px","",$ts[2]);
		$menu_text_shadow_c = str_replace("px","",$ts[3]);
		}
		?>
		<div id="menuts" style="<?php if($css['menu_text_shadow_on'] != "1") { print "display: none;"; } ?>">
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="menu_text_shadow_h"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal Offset" type="text" size="2" name="menu_text_shadow_h" id="menu_text_shadow_h" value="<?php print $menu_text_shadow_h;?>" min="-50" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="menu_text_shadow_h"><?php print ai_arrow_up;?></a>


		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="menu_text_shadow_v"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle Offset" type="text" size="2" name="menu_text_shadow_v" id="menu_text_shadow_v" value="<?php print $menu_text_shadow_v;?>" min="-50" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="menu_text_shadow_v"><?php print ai_arrow_up;?></a>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="menu_text_shadow_b"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur" type="text" size="2" name="menu_text_shadow_b" id="menu_text_shadow_b" value="<?php print $menu_text_shadow_b;?>" min="0" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="menu_text_shadow_b"><?php print ai_arrow_up;?></a>

		<input type="text"  size="6" name="menu_text_shadow_c" id="menu_text_shadow_c" value="<?php  print $menu_text_shadow_c;?>" class="color themeinput"  onchange="changeLook();"></div>
		</div>

	</div>
	<div class="group ">
		<div>Link Color</div>
		<div><input type="text"  size="6" name="menu_link_color" id="menuLinkColor" value="<?php  print $css['menu_link_color'];?>" class="color themeinput" onchange="changeLook();"></div>

		<div>Link Hover</div>
		<div><input type="text"  size="6" name="menu_link_hover" id="menuLinkHover" value="<?php  print $css['menu_link_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>

	<div class="group">
		<div><input type="checkbox" name="top_menu_side_borders" id="top_menu_side_borders" value="1" <?php if($css['top_menu_side_borders'] == "1") { print "checked"; } ?>> Buttons</div>
		<div>&nbsp;</div>
		<div>Separate Links With</div>
		<div><input type="text" size="2" name="menu_sep" id="menu_sep" value="<?php print $css['menu_sep'];?>"></div>
	</div>

		<div id="topmenubgs">
			<div class="group">
				<div>Menu Link Background</div>
				<div><input type="text"  size="6" name="top_menu_bg" id="top_menu_bg" value="<?php  print $css['top_menu_bg'];?>" class="color themeinput"  onchange="changeLook();">
				 or <input type="checkbox" name="top_menu_button_transparent" id="top_menu_button_transparent"  value="1" <?php if($css['top_menu_button_transparent'] == "1") { print "checked"; } ?>>Transparent
				</div>
				<div>Menu Link Background Hover</div>
				<div><input type="text"  size="6" name="top_menu_bg_hover" id="top_menu_bg_hover" value="<?php  print $css['top_menu_bg_hover'];?>" class="color themeinput"  onchange="changeLook();"></div>
			</div>

			<div class="group">
				<div>Border Left</div>
				<div><input type="text"  size="6" name="top_menu_border_l" id="top_menu_border_l" value="<?php  print $css['top_menu_border_l'];?>" class="color themeinput"  onchange="changeLook();"></div>
				<div>Borer Right</div>
				<div><input type="text"  size="6" name="top_menu_border_r" id="top_menu_border_r" value="<?php  print $css['top_menu_border_r'];?>" class="color themeinput"  onchange="changeLook();"></div>
			</div>
		</div>
	</div>
</div>

<div id="menubs" class="editoption">
<?php boxShadows($css['top_menu_box_shadow'],"topmenu"); ?>
</div>

<?php ################################################ Top Mini Menu ######################################################## ?>

<div id="shopmenuedit"  class="editoption">

	<div class="group">
		<div>Spacing</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_spacing"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="sm_spacing" id="sm_spacing" value="<?php print $css['sm_spacing'];?>" max="200" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_spacing"><?php print ai_arrow_up;?></a>
		</div>
	</div>



	<div class="group ">
		<div>Padding Top / Bottom </div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_padding_tb"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="sm_padding_tb" id="sm_padding_tb" value="<?php print $css['sm_padding_tb'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_padding_tb"><?php print ai_arrow_up;?></a>

		</div>


		<div>Padding Left / Right</div>
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_padding_lr"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="sm_padding_lr" id="sm_padding_lr" value="<?php print $css['sm_padding_lr'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_padding_lr"><?php print ai_arrow_up;?></a>


		</div>

	</div>


		<div class="group ">
		<div><input type="checkbox" name="sm_background_transparent" id="sm_background_transparent" value="1" <?php if($css['sm_background_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent Background</div>

			<div>Background Color</div><div><input type="text"  size=10 name="sm_background" id="sm_background" value="<?php  print $css['sm_background'];?>" class="color themeinput" onchange="changeLook();"></div>
		</div>

		<div class="group ">
			<div>Border Top</div>
			<div><input type="text"  size=10 name="sm_border_top" id="sm_border_top" value="<?php  print $css['sm_border_top'];?>" class="color themeinput" onchange="changeLook();"></div>
			<div>Border Bottom</div>
			<div><input type="text"  size=10 name="sm_border_bottom" id="sm_border_bottom"  value="<?php  print $css['sm_border_bottom'];?>" class="color themeinput" onchange="changeLook();"></div>
		</div>


	<div class="group ">
		<div>Text Color</div>
		<div><input type="text"  size=10 id="sm_text" name="sm_text" value="<?php  print $css['sm_text'];?>" class="color themeinput"  onchange="changeLook();"></div>
	</div>

	<div class="group ">
		<div>Link Color</div>
		<div><input type="text"  size=10 name="sm_link_color" id="sm_link_color" value="<?php  print $css['sm_link_color'];?>" class="color themeinput" onchange="changeLook();"></div>

		<div>Link Hover Color</div>
		<div><input type="text"  size=10 name="sm_link_hover" id="sm_link_hover" value="<?php  print $css['sm_link_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>

<div class="group">
<div><input type="checkbox" name="sm_underline" id="sm_underline" value="1" <?php if($css['sm_underline'] == "1") { print "checked"; } ?>> Underline Links </div>
<div><input type="checkbox" name="sm_underline_hover" id="sm_underline_hover" value="1" <?php if($css['sm_underline_hover'] == "1") { print "checked"; } ?>> Underline Links On Hover</div>
<div><input type="checkbox" name="sm_pin_top" id="sm_pin_top" value="1" <?php if($css['sm_pin_top'] == "1") { print "checked"; } ?>> Pin to top of screen</div>
</div>



	<div class="group ">
		<div><input type="checkbox" name="sm_text_shadow_on" id="sm_text_shadow_on" value="1" <?php if($css['sm_text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"> Text Shadow</div>
		<?php $ts = explode(" ",$css['sm_text_shadow']);
		if(is_array($ts)) { 
		$sm_text_shadow_h = str_replace("px","",$ts[0]);
		$sm_text_shadow_v = str_replace("px","",$ts[1]);
		$sm_text_shadow_b = str_replace("px","",$ts[2]);
		$sm_text_shadow_c = str_replace("px","",$ts[3]);
		}
		?>
		<div id="smmenuts" style="<?php if($css['menu_text_shadow_on'] != "1") { print "display: none;"; } ?>">
		<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_text_shadow_h"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal Offset" type="text" size="2" name="sm_text_shadow_h" id="sm_text_shadow_h" value="<?php print $sm_text_shadow_h;?>" min="-50" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_text_shadow_h"><?php print ai_arrow_up;?></a>


		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_text_shadow_v"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle Offset" type="text" size="2" name="sm_text_shadow_v" id="sm_text_shadow_v" value="<?php print $sm_text_shadow_v;?>" min="-50" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_text_shadow_v"><?php print ai_arrow_up;?></a>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="sm_text_shadow_b"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur" type="text" size="2" name="sm_text_shadow_b" id="sm_text_shadow_b" value="<?php print $sm_text_shadow_b;?>" min="0" max="50"  increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="sm_text_shadow_b"><?php print ai_arrow_up;?></a>

		<input type="text"  size=10 name="sm_text_shadow_c" id="sm_text_shadow_c" value="<?php  print $sm_text_shadow_c;?>" class="color themeinput"  onchange="changeLook();"></div>
	</div>
</div>
</div>




<?php ################################################## TEXT & LINK COLORS ########################################################## ?>

<div id="tccolors" class="editoption">
	<div class="group">
<div>Main Font Color</div>
<div><input type="text"  size=10 name="font_color" id="fontColor" value="<?php  print $css['font_color'];?>" class="color themeinput" onchange="changeLook();"></div>
</div>

<!-- 
<div class="group">
<div>Main Font Text Shadow</div><div><input type="checkbox" name="text_shadow_on" id="text_shadow_on" value="1" <?php if($css['text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"> </div>

<div class="cssClear"></div>
<?php $ts = explode(" ",$css['text_shadow']);
if(is_array($ts)) { 
$text_shadow_h = $ts[0];
$text_shadow_v = $ts[1];
$text_shadow_b = $ts[2];
$text_shadow_c = $ts[3];
}
?>
<div id="textts" style="<?php if($css['text_shadow_on'] != "1") { print "display: none;"; } ?>">
<div>
<select name="text_shadow_h" id="text_shadow_h"  onchange="changeLook();" title="Horizontal offset">
<?php 
$x = -10;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $text_shadow_h) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>

<select name="text_shadow_v" id="text_shadow_v"  onchange="changeLook();" title="Verticle offset">
<?php 
$x = -10;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $text_shadow_v) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>

<select name="text_shadow_b" id="text_shadow_b"  onchange="changeLook();" title="Blur">
<?php 
$x = 0;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $text_shadow_b) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>
</div>
<div>

<input type="text"  size=10 name="text_shadow_c" id="text_shadow_c" value="<?php  print $text_shadow_c;?>" class="color themeinput"  onchange="changeLook();">

</div>
<div class="cssClear"></div>
</div>
<div class="cssClear"></div>

</div>

-->
<div class="group">
<div>Link Color</div>
<div><input type="text"  size=10 name="link_color" id="linkColor" value="<?php  print $css['link_color'];?>" class="color themeinput" onchange="changeLook();"></div>
<div>Link Hover Color</div>
<div><input type="text"  size=10 name="link_color_hover" id="linkColorHover" value="<?php  print $css['link_color_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
</div>


<div class="group">
<div><input type="checkbox" name="underline_links" id="underline_links" value="1" <?php if($css['underline_links'] == "1") { print "checked"; } ?>> Underline Links </div>
<div><input type="checkbox" name="underline_links_hover" id="underline_links_hover" value="1" <?php if($css['underline_links_hover'] == "1") { print "checked"; } ?>> Underline Links On Hover</div>
</div>


<!-- 
<div class="row">
<div> Link Shadow</div><div><input type="checkbox" name="link_text_shadow_on" id="link_text_shadow_on" value="1" <?php if($css['link_text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"></div>

<div class="cssClear"></div>
<?php $ts = explode(" ",$css['link_text_shadow']);
if(is_array($ts)) { 
$link_text_shadow_h = $ts[0];
$link_text_shadow_v = $ts[1];
$link_text_shadow_b = $ts[2];
$link_text_shadow_c = $ts[3];
}
?>
<div id="linkts" style="<?php if($css['link_text_shadow_on'] != "1") { print "display: none;"; } ?>">
<div>
<select name="link_text_shadow_h" id="link_text_shadow_h"  onchange="changeLook();" title="Horizontal offset">
<?php 
$x = -10;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $link_text_shadow_h) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>

<select name="link_text_shadow_v" id="link_text_shadow_v"  onchange="changeLook();" title="Verticle offset">
<?php 
$x = -10;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $link_text_shadow_v) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>

<select name="link_text_shadow_b" id="link_text_shadow_b"  onchange="changeLook();" title="Blur">
<?php 
$x = 0;
while($x <=10) { ?>
<option value="<?php print$x;?>" <?php if($x == $link_text_shadow_b) { print " selected=\"selected\""; } ?>"><?php print $x;?></option>
<?php
$x = $x + 1;
}
?>
</select>
</div>
<div>

<input type="text"  size=10 name="link_text_shadow_c" id="link_text_shadow_c" value="<?php  print $link_text_shadow_c;?>" class="color themeinput"  onchange="changeLook();">

</div>
<div class="cssClear"></div>
</div>
<div class="cssClear"></div>

</div>
-->









<div class="group ">
<div>Page Title</div><div><input type="text"  size=10 name="page_title" id="pageTitleColor" value="<?php  print $css['page_title'];?>" class="color themeinput" onchange="changeLook();"></div>
<div class="cssClear"></div>
<div>Page Title Hover</div><div><input type="text"  size=10 name="page_title_hover" id="pageTitleHover" value="<?php  print $css['page_title_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
<div class="cssClear"></div>
</div>



<div class="group ">
<div><input type="checkbox" name="title_text_shadow_on" id="title_text_shadow_on" value="1" <?php if($css['title_text_shadow_on'] == "1") { print "checked"; } ?> onchange="changeLook();"> Page Title Text Shadow</div>
<?php $ts = explode(" ",$css['title_text_shadow']);
if(is_array($ts)) { 
$title_text_shadow_h = str_replace("px","",$ts[0]);
$title_text_shadow_v = str_replace("px","",$ts[1]);
$title_text_shadow_b = str_replace("px","",$ts[2]);
$title_text_shadow_c = str_replace("px","",$ts[3]);
}
?>
<div id="titlets" style="<?php if($css['title_text_shadow_on'] != "1") { print "display: none;"; } ?>">
<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="title_text_shadow_h"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Horizontal offset" type="text" size="2" name="title_text_shadow_h" id="title_text_shadow_h" value="<?php print $title_text_shadow_h;?>" max="50" min="-50" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="title_text_shadow_h"><?php print ai_arrow_up;?></a>


		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="title_text_shadow_v"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Verticle offset" type="text" size="2" name="title_text_shadow_v" id="title_text_shadow_v" value="<?php print $title_text_shadow_v;?>" max="50" min="-50" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="title_text_shadow_v"><?php print ai_arrow_up;?></a>

		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="title_text_shadow_b"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Blur" type="text" size="2" name="title_text_shadow_b" id="title_text_shadow_b" value="<?php print $title_text_shadow_b;?>" max="50" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="title_text_shadow_b"><?php print ai_arrow_up;?></a>


</div>
<div>&nbsp;</div>
<div><input type="text"  size=10 name="title_text_shadow_c" id="title_text_shadow_c" value="<?php  print $title_text_shadow_c;?>" class="color themeinput"  onchange="changeLook();"></div>
</div>
</div>
<!-- 
<div class="group ">
<div>Sub Text / Post Dates</div><div><input type="text"  size=10 name="page_dates" id="pageDates" value="<?php  print $css['page_dates'];?>" class="color themeinput" onchange="changeLook();"></div>
<div class="cssClear"></div>
<div>Home Page Labels</div><div><input type="text" size=10 name="page_labels" value="<?php  print $css['page_labels'];?>" class="color themeinput" onchange="document.getElementById('page_labels').style.color = '#'+this.color"></div>
<div class="cssClear"></div>
</div>
-->
</div>


<?php ########################################################## FORMS ############################################################# ?>
<div id="forms" class="editoption">
	<div class="group ">
	<div>Form Inputs Background</div><div><input type="text"  size=10 name="form_bg" id="form_bg" value="<?php  print $css['form_bg'];?>" class="color themeinput"   onchange="changeLook();"></div>
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="form_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="form_opacity" id="form_opacity" value="<?php print $css['form_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="form_opacity"><?php print ai_arrow_up;?></a>
	</div>

	</div>
	<div class="group ">
	<div>Form Inputs Borders</div><div><input type="text"  size=10 name="form_border" id="form_border" value="<?php  print $css['form_border'];?>" class="color themeinput"   onchange="changeLook();"></div>
	<div>Form Inputs Text</div><div><input type="text"  size=10 name="form_color" id="form_color" value="<?php  print $css['form_color'];?>" class="color themeinput"  onchange="changeLook();"></div>
	</div>

	<div class="group ">
	<div>Submit Background</div><div><input type="text"  size=10 name="submit_bg" id="submit_bg" value="<?php  print $css['submit_bg'];?>" class="color themeinput"   onchange="changeLook();"></div>
	<div>Submit Border</div><div><input type="text"  size=10 name="submit_border" id="submit_border" value="<?php  print $css['submit_border'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>
	<div class="group ">
	<div>Submit Text</div><div><input type="text"  size=10 name="submit_text" id="submit_text" value="<?php  print $css['submit_text'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>

	<div class="group ">
	<div>Submit Disabled Background</div><div><input type="text"  size=10 name="submit_disabled_background" id="submit_disabled_background" value="<?php  print $css['submit_disabled_background'];?>" class="color themeinput"   onchange="changeLook();"></div>
	<div>Submit Disabled Border</div><div><input type="text"  size=10 name="submit_disabled_border" id="submit_disabled_border" value="<?php  print $css['submit_disabled_border'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>
	<div class="group ">
	<div>Submit Disabled Text</div><div><input type="text"  size=10 name="submit_disabled_text" id="submit_disabled_text" value="<?php  print $css['submit_disabled_text'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>

	<div class="group ">
	<div>Submit Hover Background</div><div><input type="text"  size=10 name="submit_hover_background" id="submit_hover_background" value="<?php  print $css['submit_hover_background'];?>" class="color themeinput"   onchange="changeLook();"></div>
	<div>Submit Hover Border</div><div><input type="text"  size=10 name="submit_hover_border" id="submit_hover_border" value="<?php  print $css['submit_hover_border'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>
	<div class="group ">
	<div>Submit Hover Text</div><div><input type="text"  size=10 name="submit_hover_text" id="submit_hover_text" value="<?php  print $css['submit_hover_text'];?>" class="color themeinput"   onchange="changeLook();"></div>
	</div>

</div>

<?php ##########################################################  PHOTOS  ########################################################### ?>

<div id="photos" class="editoption">

	<div class="group ">
	<div>Photo Border</div>
	<div><input type="text"  size=10 name="photo_page_border_color" id="photo_page_border_color" value="<?php  print $css['photo_page_border_color'];?>" class="color themeinput" onChange="changeLook();"></div>
	<div>Size</div>
	<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="photo_page_border_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="photo_page_border_size" id="photo_page_border_size" value="<?php print $css['photo_page_border_size'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="photo_page_border_size"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group ">
	<div>Background Color</div><div><input type="text"  size=10 name="photo_page_background" id="photo_page_background" value="<?php  print $css['photo_page_background'];?>" class="color themeinput" onChange="changeLook();"></div>

	<div>Padding</div>
	<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="photo_page_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="photo_page_padding" id="photo_page_padding" value="<?php print $css['photo_page_padding'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="photo_page_padding"><?php print ai_arrow_up;?></a>

	</div>
	</div>

	<div class="group ">
	<div>Thumbnails Border</div><div><input type="text"  size=10 name="thumb_border" id="thumb_border" value="<?php  print $css['thumb_border'];?>" class="color themeinput"  onChange="changeLook();"></div>
	</div>


<?php ###########################################################   PHOTO CAPTIONS  ######################################################################## ?>


	<div class="group">
	<div>Caption Background</div><div><input type="text"  size=10 name="caption_background" id="caption_background" value="<?php  print $css['caption_background'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="caption_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="caption_opacity" id="caption_opacity" value="<?php print $css['caption_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="caption_opacity"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group">
	<div>Caption Text Color</div><div><input type="text"  size=10 name="caption_text"  id="caption_text"  value="<?php  print $css['caption_text'];?>" class="color themeinput"></div>
	<div>Align</div>
	<div>
	<select name="caption_align" id="caption_align">
	<option value="left" <?php if($css['caption_align'] == "left") { print "selected"; } ?>>Left</option>
	<option value="center" <?php if($css['caption_align'] == "center") { print "selected"; } ?>>Center</option>
	<option value="right" <?php if($css['caption_align'] == "right") { print "selected"; } ?>>Right</option>
	</select>
	</div>
		</div>
	<div class="group">
	<div>Caption Padding</div>
	<div>
		<a href="" onClick="return false;" class="incdec" acdc="0" targetid="caption_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="caption_padding" id="caption_padding" value="<?php print $css['caption_padding'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="caption_padding"><?php print ai_arrow_up;?></a>
	</div>
	</div>
	<!-- 
	<div class="group">
	<div>Scroller Background</div><div><input type="text"  size=10 name="scroller_bg" id="scroller_bg" value="<?php  print $css['scroller_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Scroller Border</div><div><input type="text"  size=10 name="scroller_border" id="scroller_border" value="<?php  print $css['scroller_border'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>

	<div class="group">
	<div>Scroller Text</div><div><input type="text"  size=10 name="scroller_text" id="scroller_text" value="<?php  print $css['scroller_text'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Slider Background</div><div><input type="text"  size=10 name="scroller_handle_bg" id="scroller_handle_bg" value="<?php  print $css['scroller_handle_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>

	<div class="group">
	<div>Slider</div><div><input type="text"  size=10 name="scroller_handle" id="scroller_handle" value="<?php  print $css['scroller_handle'];?>" class="color themeinput" onchange="changeLook();"></div>
<div>Slider Border </div><div><input type="text"  size=10 name="scroller_handle_border" id="scroller_handle_border" value="<?php  print $css['scroller_handle_border'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>
-->




</div>
<div id="photosbs" class="editoption">
<?php boxShadows($css['photos_box_shadow'],"photos"); ?>
</div>

<?php ##########################################################  FULL SCREEN PHOTOS  ########################################################### ?>

<div id="fullscreenphotos" class="editoption">

	<div class="group">
	<div>Screen Background</div>
	<div><input type="text"  size=10 name="full_screen_background" id="full_screen_background" value="<?php  print $css['full_screen_background'];?>" class="color themeinput" onChange="changeLook();"></div>
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="full_screen_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="full_screen_opacity" id="full_screen_opacity" value="<?php print $css['full_screen_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="full_screen_opacity"><?php print ai_arrow_up;?></a>
	</div>
	</div>

	<div class="group">
	<div>Photo Border</div>
	<div><input type="text"  size=10 name="photo_border_color" id="photo_border_color" value="<?php  print $css['photo_border_color'];?>" class="color themeinput" onChange="changeLook();"></div>
	

	<div>Size</div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="photo_border_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="photo_border_size" id="photo_border_size" value="<?php print $css['photo_border_size'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="photo_border_size"><?php print ai_arrow_up;?></a>
	<div>
	</div>
	</div>


	<div class="group">
	<div>Photo Background Color</div>
	<div><input type="text"  size=10 name="photo_background" id="photo_background" value="<?php  print $css['photo_background'];?>" class="color themeinput" onchange="document.getElementById('photo_border').style.backgroundColor = '#'+this.color"></div>
	<div>Padding</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="photo_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="photo_padding" id="photo_padding" value="<?php print $css['photo_padding'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="photo_padding"><?php print ai_arrow_up;?></a>
	</div>
	</div>
<div class="group">
<div>Icon Color</div>
<div><input type="text"  size=10 name="back_icon_color" id="back_icon_color" value="<?php  print $css['back_icon_color'];?>" class="color themeinput" onchange="changeLook();"></div>
</div>



</div>

<div id="fs-photosbs" class="editoption">
<?php boxShadows($css['full_screen_photo_box_shadow'],"fs-photos"); ?>
</div>

<?php ###########################################################   BOXED CONTENT   ################################################# ?>

<div id="boxed" class="editoption">

	<div class="group">
	<div><input type="checkbox" name="boxes_transparent" id="boxes_transparent" value="1" <?php if($css['boxes_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent</div>
	</div>

	<div id="boxes_options" <?php if($css['boxes_transparent'] == "1") { print "style=\"display: none;\""; } ?>>

			<div class="group">
			<div>Background</div><div><input type="text"  size=10 name="boxes" id="boxes_bg" value="<?php  print $css['boxes'];?>" class="color themeinput" onchange="changeLook();"></div>
			<div>Opacity</div>
			<div>
			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="boxes_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="boxes_opacity" id="boxes_opacity" value="<?php print $css['boxes_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="boxes_opacity"><?php print ai_arrow_up;?></a>
			</div>
			</div>
			<div class="group">
			<div>Border</div><div><input type="text"  size=10 name="boxes_borders" id="boxes_borders" value="<?php  print $css['boxes_borders'];?>" class="color themeinput" onchange="changeLook();">

			<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="boxes_borders_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="boxes_borders_size" id="boxes_borders_size" value="<?php print $css['boxes_borders_size'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="boxes_borders_size"><?php print ai_arrow_up;?></a>
			</div>

			<div>
			<select name="boxes_borders_where" id="boxes_borders_where" class="themeinput">
			<option value="0" <?php if($css['boxes_borders_where'] == "0") { print "selected"; } ?>>Boxed</option>
			<option value="1" <?php if($css['boxes_borders_where'] == "1") { print "selected"; } ?>>Bottom Only</option>
			</select>
			</div>


			<div>
			<select name="boxes_borders_style" id="boxes_borders_style" class="themeinput">
			<option value="solid" <?php if($css['boxes_borders_style'] == "solid") { print "selected"; } ?>>solid</option>
			<option value="dashed" <?php if($css['boxes_borders_style'] == "dashed") { print "selected"; } ?>>dashed</option>
			<option value="dotted" <?php if($css['boxes_borders_style'] == "dotted") { print "selected"; } ?>>dotted</option>
			<option value="double" <?php if($css['boxes_borders_style'] == "double") { print "selected"; } ?>>double</option>
			<option value="groove" <?php if($css['boxes_borders_style'] == "groove") { print "selected"; } ?>>groove</option>
			<option value="ridge" <?php if($css['boxes_borders_style'] == "ridge") { print "selected"; } ?>>ridge</option>
			<option value="inset" <?php if($css['boxes_borders_style'] == "inset") { print "selected"; } ?>>inset</option>
			<option value="outset" <?php if($css['boxes_borders_style'] == "outset") { print "selected"; } ?>>outset</option>
			</select>
			</div>

			</div>
			<div class="group">
			<div>Padding</div>
			<div>

			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="boxes_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="boxes_padding" id="boxes_padding" value="<?php print $css['boxes_padding'];?>" max="200" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="boxes_padding"><?php print ai_arrow_up;?></a>
			</div>
			<div>Rounded Corners</div>
			<div>
			<a href="" onClick="return false;" class="incdec" acdc="0" targetid="boxes_rounded"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="boxes_rounded" id="boxes_rounded" value="<?php print $css['boxes_rounded'];?>" max="200" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="boxes_rounded"><?php print ai_arrow_up;?></a>
			</div>

			</div>
	</div>


	<div class="group">
	<div>Link / Title Color</div><div><input type="text"  size=10 name="boxes_link" id="boxes_link" value="<?php  print $css['boxes_link'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Link Hover</div><div><input type="text"  size=10 name="boxes_link_hover" id="boxes_link_hover" value="<?php  print $css['boxes_link_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>



	<div class="group">
	<div>Title Size</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="boxes_title_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="boxes_title_size" id="boxes_title_size" value="<?php print $css['boxes_title_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="boxes_title_size"><?php print ai_arrow_up;?></a>
	</div>

	<div>Text Color</div><div><input type="text"  size=10 name="boxes_text" id="boxes_text" value="<?php  print $css['boxes_text'];?>" class="color themeinput" onchange="changeLook();"></div>

	</div>


	<div class="group">
	<div>Image Max Width</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="boxes_img_width"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="boxes_img_width" id="boxes_img_width" value="<?php print $css['boxes_img_width'];?>" max="800" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="boxes_img_width"><?php print ai_arrow_up;?></a>
	</div>

	</div>


</div>
<?php ###########################################################   BOXED CONTENT  BOX SHADOW  ################################################# ?>

<div id="boxesbs" class="editoption">
<?php boxShadows($css['boxes_box_shadow'],"boxes"); ?>
</div>




<?php ###########################################################   ON PHOTO PREVIEWS   ################################################# ?>

<div id="onphotopreviewsedit" class="editoption">

	<div class="group">
	<div>Background</div><div><input type="text"  size=10 name="on_photo_bg" id="on_photo_bg" value="<?php  print $css['on_photo_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>
	<div class="group">
	<div>Border</div><div><input type="text"  size=10 name="on_photo_border" id="on_photo_border" value="<?php  print $css['on_photo_border'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="on_photo_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="on_photo_border_size" id="on_photo_border_size" value="<?php print $css['on_photo_border_size'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="on_photo_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="on_photo_border_style" id="on_photo_border_style" class="themeinput">
	<option value="solid" <?php if($css['on_photo_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['on_photo_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['on_photo_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['on_photo_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['on_photo_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['on_photo_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['on_photo_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['on_photo_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>
	</div>

	<div class="group">
	<div>Width</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="on_photo_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="on_photo_width" id="on_photo_width" value="<?php print $css['on_photo_width'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="on_photo_width"><?php print ai_arrow_up;?></a>
	</div>
	<div>Height</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="on_photo_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="on_photo_height" id="on_photo_height" value="<?php print $css['on_photo_height'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="1" targetid="on_photo_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group">
	<div>Spacing</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="on_photo_margin"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="on_photo_margin" id="on_photo_margin" value="<?php print $css['on_photo_margin'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="on_photo_margin"><?php print ai_arrow_up;?></a>
	</div>
		<div>Rounded Corners</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="on_photo_border_radius"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Borde Radius" type="text" size="2" name="on_photo_border_radius" id="on_photo_border_radius" value="<?php print $css['on_photo_border_radius'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="on_photo_border_radius"><?php print ai_arrow_up;?></a>
		</div>
</div>
	<div class="group">
	<div>Title Color</div><div><input type="text"  size=10 name="on_photo_title" id="on_photo_title" value="<?php  print $css['on_photo_title'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Size</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="on_photo_title_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="on_photo_title_size" id="on_photo_title_size" value="<?php print $css['on_photo_title_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="on_photo_title_size"><?php print ai_arrow_up;?></a>
</div>
	</div>
	<div class="group">
	<div>Text Color</div><div><input type="text"  size=10 name="on_photo_text" id="on_photo_text" value="<?php  print $css['on_photo_text'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>

	<div class="group">
	<div>Text Background</div><div><input type="text"  size=10 name="on_photo_text_bg" id="on_photo_text_bg" value="<?php  print $css['on_photo_text_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Opacity</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="on_photo_text_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="on_photo_text_opacity" id="on_photo_text_opacity" value="<?php print $css['on_photo_text_opacity'];?>" max="1" min="0" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="on_photo_text_opacity"><?php print ai_arrow_up;?></a>
</div>
	</div>

	<div class="group">
	<div>Text Backgroud Width %</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="on_photo_text_width"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="on_photo_text_width" id="on_photo_text_width" value="<?php print $css['on_photo_text_width'];?>" max="100" min="50" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="on_photo_text_width"><?php print ai_arrow_up;?></a>
</div>
	</div>



</div>
<?php ###########################################################   ON PHOTO PREVIEW  BOX SHADOW  ################################################# ?>

<div id="onphotopreviewbs" class="editoption">
<?php boxShadows($css['on_photo_box_shadow'],"onphotos"); ?>
</div>


<?php ###########################################################   THUMBNAIL LISTINGS ################################################# ?>

<div id="thumbnaillistingedit" class="editoption">

	<div class="group">
	<div>Background</div><div><input type="text"  size=10 name="thumb_listing_bg" id="thumb_listing_bg" value="<?php  print $css['thumb_listing_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_listing_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="thumb_listing_opacity" id="thumb_listing_opacity" value="<?php print $css['thumb_listing_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_listing_opacity"><?php print ai_arrow_up;?></a>
	</div>
	</div>
	<div class="group">
	<div>Border</div><div><input type="text"  size=10 name="thumb_listing_border" id="thumb_listing_border" value="<?php  print $css['thumb_listing_border'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_listing_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_listing_border_size" id="thumb_listing_border_size" value="<?php print $css['thumb_listing_border_size'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_listing_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="thumb_listing_border_style" id="thumb_listing_border_style" class="themeinput">
	<option value="solid" <?php if($css['thumb_listing_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['thumb_listing_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['thumb_listing_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['thumb_listing_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['thumb_listing_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['thumb_listing_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['thumb_listing_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['thumb_listing_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>

	</div>

	<div class="group">
	<div>Width</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_listing_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_listing_width" id="thumb_listing_width" value="<?php print $css['thumb_listing_width'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_listing_width"><?php print ai_arrow_up;?></a>
	</div>
	<div>Height</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_listing_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_listing_height" id="thumb_listing_height" value="<?php print $css['thumb_listing_height'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="1" targetid="thumb_listing_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>

	<div class="group">
	<div>Thumb Area Width</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_listing_thumb_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_listing_thumb_width" id="thumb_listing_thumb_width" value="<?php print $css['thumb_listing_thumb_width'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_listing_thumb_width"><?php print ai_arrow_up;?></a>
	</div>
	<div>Thumb Area Height</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_listing_thumb_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_listing_thumb_height" id="thumb_listing_thumb_height" value="<?php print $css['thumb_listing_thumb_height'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="1" targetid="thumb_listing_thumb_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group">
	<div>Spacing</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_listing_margin"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="thumb_listing_margin" id="thumb_listing_margin" value="<?php print $css['thumb_listing_margin'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_listing_margin"><?php print ai_arrow_up;?></a>
	</div>

		
	<div>Padding</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_listing_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding" type="text" size="2" name="thumb_listing_padding" id="thumb_listing_padding" value="<?php print $css['thumb_listing_padding'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_listing_padding"><?php print ai_arrow_up;?></a>
		</div>
	</div>
	<div class="group">
		<div>Thumbnail Border</div><div><input type="text"  size=10 name="thumb_listing_thumb_border" id="thumb_listing_thumb_border" value="<?php  print $css['thumb_listing_thumb_border'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Rounded Corners</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_listing_border_radius"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Borde Radius" type="text" size="2" name="thumb_listing_border_radius" id="thumb_listing_border_radius" value="<?php print $css['thumb_listing_border_radius'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_listing_border_radius"><?php print ai_arrow_up;?></a>
		</div>
	</div>
	<div class="group">
	<div>Stacked Padding</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="stacked_listing_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding for 'Stacked' layouts" type="text" size="2" name="stacked_listing_padding" id="stacked_listing_padding" value="<?php print $css['stacked_listing_padding'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="stacked_listing_padding"><?php print ai_arrow_up;?></a>
		</div>


</div>
	<div class="group">
	<div>Title Color</div><div><input type="text"  size=10 name="thumb_listing_title" id="thumb_listing_title" value="<?php  print $css['thumb_listing_title'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Size</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_listing_title_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="thumb_listing_title_size" id="thumb_listing_title_size" value="<?php print $css['thumb_listing_title_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_listing_title_size"><?php print ai_arrow_up;?></a>
</div>
	</div>
	<div class="group">
	<div>Title Hover Color</div><div><input type="text"  size=10 name="thumb_listing_title_hover" id="thumb_listing_title_hover" value="<?php  print $css['thumb_listing_title_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Text Color</div><div><input type="text"  size=10 name="thumb_listing_text" id="thumb_listing_text" value="<?php  print $css['thumb_listing_text'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>


</div>
<?php ###########################################################   THUMBNAIL LISTING  BOX SHADOW  ################################################# ?>

<div id="thumbnaillistingbs" class="editoption">
<?php boxShadows($css['thumb_listing_box_shadow'],"thumblisting"); ?>
</div>

<?php ###########################################################   SIDE MENU ######################################################################## ?>

<div id="sidemenuoptions" class="editoption">
	<div class="group">
<div><input type="checkbox"  name="disable_side" id="disable_side"  value="1" <?php if($css['disable_side'] == "1") { print " checked"; } ?> onChange="changeLook();"> Disable side bar</div> 
<div><input type="checkbox" name="side_menu_transparent" id="side_menu_transparent" value="1" <?php if($css['side_menu_transparent'] == "1") { print "checked"; } ?> onchange="changeLook();"> Transparent  Background</div>
<div>
<input type="hidden" name="side_menu_use" id="side_menu_use" value="side">
</div>
</div>
<div id="sideMenuOptions" <?php if($css['disable_side'] == "1") {print "style=\"display: none;\""; } ?>>
	<div class="group">
	<div>Width %</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_menu_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="Width" type="text" size="2" name="side_menu_width" id="side_menu_width" value="<?php print $css['side_menu_width'];?>" max="50" min="10" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_menu_width"><?php print ai_arrow_up;?></a>
	</div>

	<div>Placement</div><div>
	<select name="side_menu_align" id="side_menu_align" onChange="changeLook();">
	<option value="left" <?php if($css['side_menu_align'] == "left") { print "selected"; } ?>>Left</option>
	<option value="right" <?php if($css['side_menu_align'] == "right") { print "selected"; } ?>>Right</option>
	</select>
	</div>
</div>

<div id="side_menu_options" <?php if($css['side_menu_transparent'] == "1") { print "style=\"display: none;\""; } ?>>
	<div class="group">
	<div>Background </div><div><input type="text" size=10 name="side_main_bg" id="side_main_bg" value="<?php  print $css['side_main_bg'];?>" class="color themeinput" onChange="changeLook();"></div>
	<div>Inside Padding</div>
	<div>	
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_main_bg_padding"><?php print ai_arrow_down;?></a><input class="themeinput" title="Padding" type="text" size="2" name="side_main_bg_padding" id="side_main_bg_padding" value="<?php print $css['side_main_bg_padding'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_main_bg_padding"><?php print ai_arrow_up;?></a>
	</div>
</div>
	<div class="group">
	<div>Border</div><div><input type="text"  size=10 name="side_main_border_color" id="side_main_border_color" value="<?php  print $css['side_main_border_color'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_main_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_main_border_size" id="side_main_border_size" value="<?php print $css['side_main_border_size'];?>" max="1000" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_main_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="side_main_border_style" id="side_main_border_style" class="themeinput">
	<option value="solid" <?php if($css['side_main_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['side_main_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['side_main_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['side_main_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['side_main_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['side_main_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['side_main_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['side_main_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>
	<div>Radius  
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_main_border_radius"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_main_border_radius" id="side_main_border_radius" value="<?php print $css['side_main_border_radius'];?>" max="1000" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_main_border_radius"><?php print ai_arrow_up;?></a>
	</div>


	</div>



</div>
	<div class="group">
	<div>Menu Separator</div><div><input type="text"  size=10 name="side_menu_link_border_b" id="side_menu_link_border_b" value="<?php  print $css['side_menu_link_border_b'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_menu_link_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_menu_link_border_size" id="side_menu_link_border_size" value="<?php print $css['side_menu_link_border_size'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_menu_link_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="side_menu_link_border_style" id="side_menu_link_border_style" class="themeinput">
	<option value="solid" <?php if($css['side_menu_link_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['side_menu_link_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['side_menu_link_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['side_menu_link_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['side_menu_link_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['side_menu_link_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['side_menu_link_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['side_menu_link_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>



</div>
	<div class="group">
<div>Padding Left / Right</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_main_padding"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_main_padding" id="side_main_padding" value="<?php print $css['side_main_padding'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_main_padding"><?php print ai_arrow_up;?></a>
</div>
<div>Padding Top / Bottom</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_menu_line_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_menu_line_height" id="side_menu_line_height" value="<?php print $css['side_menu_line_height'];?>" max="100" min="0" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_menu_line_height"><?php print ai_arrow_up;?></a>
</div>
</div>

	<div class="group">
	<div>Text </div>
	<div><input type="text" size=10 name="side_main_font" id="side_main_font" value="<?php  print $css['side_main_font'];?>" class="color themeinput" onChange="changeLook();"></div>
</div>

	<div class="group">
	<div>Link </div><div><input type="text" size=10 name="side_main_link" id="side_main_link" value="<?php  print $css['side_main_link'];?>" class="color themeinput" onChange="changeLook();"></div>
	<div>Link Hover </div><div><input type="text" size=10 name="side_main_link_hover" id="side_main_link_hover" value="<?php  print $css['side_main_link_hover'];?>" class="color themeinput" onChange="changeLook();"></div>
	</div>

<div class="group">
	<div>Font size</div>
	<div><a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_menu_font_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_menu_font_size" id="side_menu_font_size" value="<?php print $css['side_menu_font_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_menu_font_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>Label size</div>
	<div><a href="" onClick="return false;" class="incdec" acdc="0" targetid="side_menu_label_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="side_menu_label_size" id="side_menu_label_size" value="<?php print $css['side_menu_label_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;"  class="incdec" acdc="1" targetid="side_menu_label_size"><?php print ai_arrow_up;?></a>
	</div>

</div>

</div>

</div>

<?php ###########################################################   THUMBNAIL GALLERY ################################################# ?>

<div id="thumb_nailsedit" class="editoption">

	<div class="group">
	<div>Background</div><div><input type="text"  size=10 name="thumb_nails_bg" id="thumb_nails_bg" value="<?php  print $css['thumb_nails_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Opacity</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_nails_opacity"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Opacity" type="text" size="2" name="thumb_nails_opacity" id="thumb_nails_opacity" value="<?php print $css['thumb_nails_opacity'];?>" max="1" min=".1" increment=".1" decimal="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_nails_opacity"><?php print ai_arrow_up;?></a>
	</div>
	</div>
	<div class="group">
	<div>Border</div><div><input type="text"  size=10 name="thumb_nails_border" id="thumb_nails_border" value="<?php  print $css['thumb_nails_border'];?>" class="color themeinput" onchange="changeLook();">
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_nails_border_size"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_nails_border_size" id="thumb_nails_border_size" value="<?php print $css['thumb_nails_border_size'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_nails_border_size"><?php print ai_arrow_up;?></a>
	</div>
	<div>
	<select name="thumb_nails_border_style" id="thumb_nails_border_style" class="themeinput">
	<option value="solid" <?php if($css['thumb_nails_border_style'] == "solid") { print "selected"; } ?>>solid</option>
	<option value="dashed" <?php if($css['thumb_nails_border_style'] == "dashed") { print "selected"; } ?>>dashed</option>
	<option value="dotted" <?php if($css['thumb_nails_border_style'] == "dotted") { print "selected"; } ?>>dotted</option>
	<option value="double" <?php if($css['thumb_nails_border_style'] == "double") { print "selected"; } ?>>double</option>
	<option value="groove" <?php if($css['thumb_nails_border_style'] == "groove") { print "selected"; } ?>>groove</option>
	<option value="ridge" <?php if($css['thumb_nails_border_style'] == "ridge") { print "selected"; } ?>>ridge</option>
	<option value="inset" <?php if($css['thumb_nails_border_style'] == "inset") { print "selected"; } ?>>inset</option>
	<option value="outset" <?php if($css['thumb_nails_border_style'] == "outset") { print "selected"; } ?>>outset</option>
	</select>
	</div>

	</div>

	<div class="group">
	<div>Width</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_nails_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_nails_width" id="thumb_nails_width" value="<?php print $css['thumb_nails_width'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_nails_width"><?php print ai_arrow_up;?></a>
	</div>
	<div>Height</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_nails_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_nails_height" id="thumb_nails_height" value="<?php print $css['thumb_nails_height'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="1" targetid="thumb_nails_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>

	<div class="group">
	<div>Thumb Area Width</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_nails_thumb_width"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_nails_thumb_width" id="thumb_nails_thumb_width" value="<?php print $css['thumb_nails_thumb_width'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto();return false;" class="incdec" acdc="1" targetid="thumb_nails_thumb_width"><?php print ai_arrow_up;?></a>
	</div>
	<div>Thumb Area Height</div>
	<div>
	<a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="0" targetid="thumb_nails_thumb_height"><?php print ai_arrow_down;?></a><input class="themeinput" title="" type="text" size="2" name="thumb_nails_thumb_height" id="thumb_nails_thumb_height" value="<?php print $css['thumb_nails_thumb_height'];?>" max="1000" min="0" increment="1" onChange="resizeOnPhotoPhoto();"><a href="" onClick="resizeOnPhotoPhoto(); return false;" class="incdec" acdc="1" targetid="thumb_nails_thumb_height"><?php print ai_arrow_up;?></a>
	</div>
	</div>


	<div class="group">
	<div>Spacing</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_nails_margin"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="thumb_nails_margin" id="thumb_nails_margin" value="<?php print $css['thumb_nails_margin'];?>" max="60" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_nails_margin"><?php print ai_arrow_up;?></a>
	</div>

		
	<div>Padding</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_nails_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Padding" type="text" size="2" name="thumb_nails_padding" id="thumb_nails_padding" value="<?php print $css['thumb_nails_padding'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_nails_padding"><?php print ai_arrow_up;?></a>
		</div>
	</div>
	<div class="group">
		<div>Thumbnail Border</div><div><input type="text"  size=10 name="thumb_nails_thumb_border" id="thumb_nails_thumb_border" value="<?php  print $css['thumb_nails_thumb_border'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>
	<div class="group">
	<div>Rounded Corners</div>
	<div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_nails_border_radius"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="Borde Radius" type="text" size="2" name="thumb_nails_border_radius" id="thumb_nails_border_radius" value="<?php print $css['thumb_nails_border_radius'];?>" max="100" min="0" increment="1" ><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_nails_border_radius"><?php print ai_arrow_up;?></a>
		</div>
</div>
	<div class="group">
	<div>Title Color</div><div><input type="text"  size=10 name="thumb_nails_title" id="thumb_nails_title" value="<?php  print $css['thumb_nails_title'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Size</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="thumb_nails_title_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="thumb_nails_title_size" id="thumb_nails_title_size" value="<?php print $css['thumb_nails_title_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="thumb_nails_title_size"><?php print ai_arrow_up;?></a>
</div>
	</div>
	<div class="group">
	<div>Title Hover Color</div><div><input type="text"  size=10 name="thumb_nails_title_hover" id="thumb_nails_title_hover" value="<?php  print $css['thumb_nails_title_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Text Color</div><div><input type="text"  size=10 name="thumb_nails_text" id="thumb_nails_text" value="<?php  print $css['thumb_nails_text'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>


</div>
<?php ###########################################################   THUMBNAIL LISTING  BOX SHADOW  ################################################# ?>

<div id="thumb_nailsbs" class="editoption">
<?php boxShadows($css['thumb_nails_box_shadow'],"thumb_nails"); ?>
</div>

<?php ################################ FOOTER ################################################## ?>


<div id="footeredit" class="editoption">

	<div class="group">
	<div>Background</div><div><input type="text"  size=10 name="footer_bg" id="footer_bg" value="<?php  print $css['footer_bg'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Placement</div>
	<div>
	<select name="footer_outside" id="footer_outside" class="themeinput">
	<option value="0" <?php if($css['footer_outside'] == "0") { print "selected"; } ?>>Inside Content Area</option>
	<option value="1" <?php if($css['footer_outside'] == "1") { print "selected"; } ?>>Outside Content Area</option>
	</select>
	</div>
	</div>

	<div class="group">
	<div>Padding</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="footer_padding"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="footer_padding" id="footer_padding" value="<?php print $css['footer_padding'];?>" max="1000" min="0" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="footer_padding"><?php print ai_arrow_up;?></a>
	</div>
	</div>

	<div class="group">
	<div>Font Color</div><div><input type="text"  size=10 name="footer_text_color" id="footer_text_color" value="<?php  print $css['footer_text_color'];?>" class="color themeinput" onchange="changeLook();"></div>

	<div>Font Size</div><div>
	<a href="" onClick="return false;" class="incdec" acdc="0" targetid="footer_font_size"><?php print ai_arrow_down;?></a><input class="inputtip themeinput" title="" type="text" size="2" name="footer_font_size" id="footer_font_size" value="<?php print $css['footer_font_size'];?>" max="100" min="8" increment="1"><a href="" onClick="return false;" class="incdec" acdc="1" targetid="footer_font_size"><?php print ai_arrow_up;?></a>
</div>
	</div>
	<div class="group">
	<div>Link Color</div><div><input type="text"  size=10 name="footer_link_color" id="footer_link_color" value="<?php  print $css['footer_link_color'];?>" class="color themeinput" onchange="changeLook();"></div>
	<div>Link Hover Color</div><div><input type="text"  size=10 name="footer_link_hover" id="footer_link_hover" value="<?php  print $css['footer_link_hover'];?>" class="color themeinput" onchange="changeLook();"></div>
	</div>


</div>


<?php ###########################################################   THEME INFO ######################################################################## ?>
<div id="theme_info" class="editoption">
	<div class="group">
		<div>Theme Name</div>
		<div><input type="text"  size=30 class="field100" name="css_name" value="<?php  print htmlspecialchars(stripslashes($css['css_name']));?>"></div>
		<div>Preview Thumb</div><div><input type="text"  size=30 class="field100" name="theme_screen" value="<?php  print htmlspecialchars(stripslashes($css['theme_screen']));?>"></div>
	</div>

	<div class="group">
		<div>Description</div><div><textarea name="descr" cols="60" rows="3" class="field100"><?php print $css['descr'];?></textarea></div>
	</div>

</div>

<div id="iconsfb" class="editoption">

			<div class="row">
				<div>Facebook features color scheme </div><div>
				<select name="fb_color">
					<option value="light" <?php if($css['fb_color'] == "light") { print "selected"; } ?>>Light</option>
					<option value="dark" <?php if($css['fb_color'] == "dark") { print "selected"; } ?>>Dark</option>
				</select>
				</div>
				<div class="cssClear"></div>
			</div>

</div>

</div>




















</div>
</div>




<div id="themepreview">


<div>

<?php
if(!empty($css['css_font_family'])) { 
	define("font_family", "".$css['css_font_family']);
} else {
	$check = explode("||", $css['css_font_family_main']);
	if($check[1] == "IMPORT") { 
		define("font_family", "".$check[0]);
	} else { 
		define("font_family", "".$css['css_font_family_main']);
	}
}



if(!empty($css['css_title_font_family'])) { 
	define("title_font_family", "".$css['css_title_font_family']);
} elseif(!empty($css['css_title_font_family_main'])) { 
	$check = explode("||", $css['css_title_font_family_main']);
	if($check[1] == "IMPORT") { 
		define("title_font_family", "".$check[0]);
	} else { 
		define("title_font_family", "".$css['css_title_font_family_main']);
	}
} else {
	define("title_font_family", font_family);
}
?>

<?php include "theme-edit-layout.php"; ?>


	<center>
	<input type="hidden" name="do" value="look">
	<input type="hidden" name="view" value="css2">
	<input type="hidden" name="submitit" value="yup">

	<input type="hidden" name="css_id" value="<?php  print $css['css_id'];?>">
	</center>























</div>



		</div>
<div>&nbsp;</div>
			</form>
			</div>
<?php  } ?>

</div>
<div id="shadepagecontainer" style="display: none;" ><div id="shadepage" ></div></div>
<div id="shadepagecontent" style="display: none;" ><div id="shadepageenter" style="display: none;"></div></div>
<?php require "w-footer.php"; ?>
