<?php header("Content-type: text/css"); ?>
<?php
require "../sy-config.php";
session_start();
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
if((!empty($_REQUEST['bid']))AND(!is_numeric($_REQUEST['bid']))==true) { die("an error has occured"); } 
$billboard = doSQL("ms_billboards", "*", "WHERE bill_id='".$_REQUEST['bid']."' ");
$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$_REQUEST['csst']."' "); 

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


if($billboard['bill_placement'] == "full") { 
	$bill_height = "100VH";
} else { 
	$bill_height = $billboard['bill_height']."px";
}


if($billboard['bill_placement'] == "full") { 
	$billboard['bill_border_size'] = 0;
}
?>
#loadingbb { position: absolute; top: 10%; width: 100%; text-align: center;} 
#billboardOuter { padding: <?php print $billboard['bill_padding'];?>px; } 

#billboardContainer { 
	width: 100%;
	margin: 0 auto 0 auto;
	display: block;
	padding: 0;
}


#billboard { 
	display: block;
	float: left;
	width: 100%;
}
#neatbb { margin: auto; position: relative; width: 100%; } 
#neatbbslides { position: relative; overflow: hidden;height: <?php print $bill_height;?>; border: solid <?php print $billboard['bill_border_size'];?>px #<?php print $billboard['bill_border_color'];?>; } 

.neatbbslide { display: none; position: absolute; } 
.neatbbslide1 {position: absolute; display: none; } 
#neatbbmenu { text-align: center;  position: absolute; z-index: 3; width: 100%; bottom: 0; } 
#neatbbmenu a {  padding: 1px 4px; margin: 2px; }  
.bn {  padding: 4px; opacity:0.5; }
.bnon {  box-shadow: 0px 0px 2px rgba(0, 0, 0, .5); opacity:1;}


.slidenav { margin: 0px; padding: 0px; color: #<?php print $billboard['bill_nav_color'];?>;  font-size: 12px;} 
.slidenav li { border: solid 2px #<?php print $billboard['bill_nav_border'];?>; color: #<?php print $billboard['bill_nav_color'];?>; border-radius:50%; width: 16px; height: 16px; line-height: 16px; display: inline-block; box-shadow: 1px 1px 1px rgba(0,0,0,.8); } 
.slidenav li:hover {  background: #<?php print $billboard['bill_nav_background'];?>; cursor: pointer;} 
.slidenav  .bnon { background: #<?php print $billboard['bill_nav_background'];?>; } 

<?php 
if($billboard['bill_placement'] == "full") { 
	if($css['header_transparent'] == "1") { 
		$header_bg = $css['outside_bg'];
	} else { 
		$header_bg = $css['header_bg'];
	}
	$menu_bg = $css['menu_color'];

	?>

<?php if($css['menu_placement'] == "below") { ?>
#topMainMenuContainer, #menucontainerouter { width: 100%; max-width: 100%;  } 
<?php } ?>

<?php if($billboard['abs_header'] == "1") { 
$rgb = hex2rgb($header_bg);
?>
#hc { display: none; } 
 #headerAndMenu { background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $billboard['header_opacity'];?>); position: absolute; width: 100%; border: 0px; z-index: 50; } 
 #headerContainer { background-color: transparent; } 
@media (max-width: 800px) {
	 #headerAndMenu { background-color: #<?php print $header_bg;?>; position: relative; width: 100%; border: 0px; z-index: 50; } 
	 #headerContainer {  } 
}
<?php if($billboard['hide_shop_menu'] == "1") { ?>
#headerAndMenu { top: 0px !important; } 
<?php } ?>
<?php 
$rgb = hex2rgb($menu_bg); 
$ma_rgb = hex2rgb($css['menu_border_a']); 
$mb_rgb = hex2rgb($css['menu_border_b']); 
?>
<?php if($css['menu_transparent'] !== "1") { ?>
#topMainMenuContainer { background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $billboard['menu_opacity'];?>); border-top-color:  rgba(<?php print $ma_rgb[0];?>,<?php print $ma_rgb[1];?>,<?php print $ma_rgb[2];?>,<?php print $billboard['menu_opacity'];?>); border-bottom-color:  rgba(<?php print $mb_rgb[0];?>,<?php print $mb_rgb[1];?>,<?php print $mb_rgb[2];?>,<?php print $billboard['menu_opacity'];?>);} 
<?php } ?>
<?php } ?>
<?php if($billboard['hide_shop_menu'] == "1") { ?>
#shopmenucontainer, #smc { display: none; } 
<?php } ?>
<?php } ?>