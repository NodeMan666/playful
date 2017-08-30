<?php 
$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$site_setup['css']."' "); 
$cset = doSQL("ms_calendar_settings", "*", "");
function addBoxShadow($shadow) { 
	 if(!empty($shadow)) { 
			print "box-shadow: ";
			$bss = explode("+",$shadow);
			foreach($bss AS $bsc) { 
				if(!empty($bsc)) { 
					$ct++;
					$bs = explode("|",$bsc);
					if($ct > 1) { 
						print ",";
					}
					$rgb = hex2rgb($bs[4]); 
					print $bs[0]."px ".$bs[1]."px ".$bs[2]."px ".$bs[3]."px rgba(".$rgb[0].",".$rgb[1].",".$rgb[2].",".$bs[5].") ".$bs[6]."";
				}
			}
			print ";";
		}
}
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
function ieopacity($i) { 
	if($i == "0.10") { 
		return "19";
	}
	if($i == "0.20") { 
		return "33";
	}
	if($i == "0.30") { 
		return "4c";
	}
	if($i == "0.40") { 
		return "66";
	}
	if($i == "0.50") { 
		return "7f";
	}
	if($i == "0.60") { 
		return "99";
	}
	if($i == "0.70") { 
		return "b2";
	}
	if($i == "0.80") { 
		return "cc";
	}
	if($i == "0.90") { 
		return "e5";
	}
	if($i == "1.00") { 
		return "ff";
	}
}

function ie8rgba($color,$opacity) { 
?>
filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#<?php print ieopacity($opacity);?><?php print $color;?>,endColorstr=#<?php print ieopacity($opacity);?><?php print $color;?>);
<?php 
}


if(is_numeric($css['page_width'])) { 
	$css['page_width'] = $css['page_width']."px";
}

if(!empty($css['css_font_family'])) { 
	define("font_family", "".$css['css_font_family']);
} else {
	$check = explode("||", $css['css_font_family_main']);
	if($check[1] == "IMPORT") { 
	?>
@font-face {
    font-family: '<?php print $check[0];?>';
    src: url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.eot');
    src: url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.eot?#iefix') format('embedded-opentype'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.woff') format('woff'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.ttf') format('truetype'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.svg#QuicksandLightOblique') format('svg');
    font-weight: normal;
    font-style: normal;
	}
<?php
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
	?>
@font-face {
    font-family: '<?php print $check[0];?>';
    src: url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.eot');
    src: url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.eot?#iefix') format('embedded-opentype'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.woff') format('woff'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.ttf') format('truetype'),
         url('/sy-themes/fonts/<?php print $check[0]."/".$check[0];?>.svg#QuicksandLightOblique') format('svg');
    font-weight: normal;
    font-style: normal;
	}
<?php
		define("title_font_family", "".$check[0]);

	} else { 
		define("title_font_family", "".$css['css_title_font_family_main']);
	}
} else {
		define("title_font_family", font_family);
}




if($_REQUEST['site_type'] == "mobile") { 
	define("page_width", "100%");
} elseif($_REQUEST['ipad'] == 1) { 
	define("page_width", "1024px");
} else { 
	define("page_width", "".$css['page_width']);
}
define("font_size", "".$css['font_size']."px");
define("outside_bg", "#".$css['outside_bg']);
define("inside_bg","#".$css['inside_bg']);
define("inside_bg_border","#".$css['inside_bg_border']);
define("font_color","#".$css['font_color']);
define("link_color","#".$css['link_color']);
define("link_color_hover","#".$css['link_color_hover']);
define("page_title","#".$css['page_title']);
define("page_title_hover","#".$css['page_title_hover']);
define("menu_color_hover","#".$css['menu_color_hover']);
define("menu_color","#".$css['menu_color']);
define("menu_font_color","#".$css['menu_font_color']);
define("menu_link_color","#".$css['menu_link_color']);
define("menu_link_hover","#".$css['menu_link_hover']);
define("menu_border_a","#".$css['menu_border_a']);
define("menu_border_b","#".$css['menu_border_b']);

define("side_menu_bg_color","#".$css['side_menu_bg_color']);
define("side_menu_bg_hover","#".$css['side_menu_bg_hover']);
define("side_menu_border_color","#".$css['side_menu_border_color']);
define("side_menu_font_color","#".$css['side_menu_font_color']);
define("side_menu_link_color","#".$css['side_menu_link_color']);
define("side_menu_link_hover","#".$css['side_menu_link_hover']);
define("side_menu_link_border_a","#".$css['side_menu_link_border_a']);
define("side_menu_link_border_b","#".$css['side_menu_link_border_b']);
define("side_menu_header_bg","#".$css['side_menu_header_bg']);
define("side_menu_header_text","#".$css['side_menu_header_text']);

define("boxes","#".$css['boxes']);
define("boxes_hover","#".$css['boxes_hover']);
define("boxes_borders","#".$css['boxes_borders']);
define("boxes_padding",$css['boxes_padding']);
define("page_labels","#".$css['page_labels']);
define("page_dates","#".$css['page_dates']);
define("header_bg","#".$css['header_bg']);
define("header_font_color","#".$css['header_font_color']);
define("form_bg","#".$css['form_bg']);
define("form_color","#".$css['form_color']);
define("form_border","#".$css['form_border']);
define("home_page_col","#".$css['home_page_col']);				
define("home_page_col_border","#".$css['home_page_col_border']);				

define("top_menu_bg","#".$css['top_menu_bg']);
define("top_menu_text","#".$css['top_menu_text']);
define("top_menu_link","#".$css['top_menu_link']);
define("top_menu_border","#".$css['top_menu_border']);


define("success_message_bg","#".$css['boxes']);
define("success_message_border","#".$css['boxes_borders']);
define("success_message_text","#".$css['boxes_borders']);

define("thumb_border","#".$css['thumb_border']);

define("fb_bg_color","#".$css['fb_bg_color']);
define("fb_border_color","#".$css['fb_border_color']);

/*
DEFAULT VALUES TO REPLACE
	Form text								#a2a2a2
*/


?>

blockquote {
margin: 0.25em 0;
padding: 0.25em 40px;
}

.the-icons {  font-size: 16px;  line-height: 16px;  color: #<?php print $css['page_icon_color'];?>;  opacity: .8;  text-shadow: #<?php print $css['inside_bg'];?> 0px 0px 2px; }
.the-icons:hover { opacity: 1; cursor: pointer; text-decoration: none;} 
.icon-cancel-circled {padding: 4px; margin-right: 4px; } 
.icon-text { font-size: 12px; color: #<?php print $css['page_icon_color'];?>;cursor: pointer; } 
.icon-text:hover { font-size: 12px; color: #<?php print $css['page_icon_color'];?>;cursor: pointer; text-decoration: none; } 

a.the-icons, a.the-icons:visited  { color:  #<?php print $css['page_icon_color'];?>; } 
.the-icons a, .the-icons a:visited , a.the-icons, a.the-icons:visited, the-icons a:link, a.the-icons:link {  color:  #<?php print $css['page_icon_color'];?>; } 

.the-icons-fullscreen { color: #<?php print $css['back_icon_color'];?>; text-shadow: #<?php print $css['full_screen_background'];?> 0px 0px 2px; } 

#slideshowaudio { background-color: #<?php print $css['full_screen_background'];?>;position: fixed; bottom: 0; left: 4px;  z-index: 10000 } 
#slideshowaudio .the-icons { color: #<?php print $css['back_icon_color'];?>; text-shadow: #<?php print $css['full_screen_background'];?> 0px 0px 2px; } 

.pageshare { width: auto; text-align: center; margin: auto;} 
.pageshare ul { margin: 0px; padding: 0px; list-style: none;}
.pageshare ul li { display: inline; }
.pageshare .the-icons { font-size: 24px; } 


#ssheader { 
	<?php if($css['sm_background_transparent'] !== "1") { ?>
	background: #<?php print $css['sm_background'];?>;
	border-top: solid 1px #<?php print $css['sm_border_top'];?>;
	border-bottom: solid 1px #<?php print $css['sm_border_bottom'];?>;
	 <?php } ?>
	 position: fixed; width: 100%;  left:0; top: 0; z-index:30; display: none; display: none;  display: none;
}
#ssheaderinner { 
	width: <?php print page_width;?>;
	max-width: <?php print $css['page_width_max'];?>px;
	margin: auto;
	color: #<?php print $css['sm_text'];?>;
	font-family: '<?php print $css['sm_font_family'];?>', Arial;
	font-size: <?php print $css['sm_font_size'];?>px;
	position: relative;
	z-index: 3;
<?php if($css['sm_text_shadow_on'] == "1") { print "text-shadow: ".$css['sm_text_shadow'].";"; } ?>
}

#ssheaderinner a { color: #<?php print $css['sm_link_color'];?>;  <?php if($css['sm_underline'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 
#ssheaderinner a:hover { color: #<?php print $css['sm_link_hover'];?>;  <?php if($css['sm_underline_hover'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 


#ssheaderinner #photomenu .the-icons {
  font-size: 24px;
  line-height: 16px;
  color: #<?php print $css['sm_link_color'];?>;
 padding: 4px;
 margin-right: 4px;
 text-shadow: #<?php print $css['sm_background'];?> 0px 0px 2px;
}
#ssheaderinner #photomenu .the-icons:hover { color: #<?php print $css['sm_link_hover'];?>; cursor: pointer; } 
#ssheaderinner #photomenu .icon-text { font-size: 12px;color: #<?php print $css['sm_link_color'];?>; cursor: pointer; } 
#ssheaderinner #photomenu .icon-text:hover { color: #<?php print $css['sm_link_hover'];?>; cursor: pointer; } 



.photo-nav { font-size: 60px; } 
#ssNextPhoto {   opacity: .6; display: none; cursor: pointer; }
#ssPrevPhoto { opacity: .6; display: none; cursor: pointer; }
#ssClose { position: absolute; right: 4px; top: 4px;   z-index: 30; display: none; font-size: 48px; }
#ssClose:hover { opacity: 1; } 
#ssNextPhoto:hover { opacity: 1; } 
#ssPrevPhoto:hover { opacity: 1; } 
#ssPlay {  width: 200px; height: 55px; display: block; margin: auto; text-align: center;}
#ssPause { width: 200px; height: 55px; display: block; margin: auto;  text-align: center;}
#ssPlay:hover { opacity: 1; } 
#ssPause:hover { opacity: 1; } 
#controls {  background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/clear.gif')repeat; display: none;} 
#controls { 
	display: none; 
	color: #<?php print $css['caption_text'];?>;
} 
#controls a { 
	color: #<?php print $css['caption_text'];?>;
} 
.pagephotostoreactions { display: none; } 

html  {
	<?php if(($_REQUEST['admin_edit'] == "1")&&($_REQUEST['header'] == "1")==true) { ?>
	background-color: <?php print header_bg;?>;
	<?php } elseif(($_REQUEST['admin_edit'] == "1")OR($_REQUEST['wbg'] == "1") ==true) { ?>
	background-color: <?php print inside_bg;?>;
	<?php } else { ?>
	background-color: <?php print outside_bg;?>;
	<?php if(!empty($css['outside_bg_image'])) { ?>
		<?php if($css['bg_image_style'] == "topcover") { ?>
			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') no-repeat top center fixed ;
			background-size: cover;
		<?php } else if($css['bg_image_style'] == "centercover") { ?>

			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') no-repeat center center fixed ;
			background-size: cover;

		<?php } else { ?>
			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') <?php print $css['bg_image_style'];?> ;
		<?php } ?>
	<?php } ?>
	<?php } ?>
	-webkit-font-smoothing: antialiased;
}

html, body {
	<?php if(($_REQUEST['admin_edit'] == "1")&&($_REQUEST['header'] == "1")==true) { ?>
	background-color: <?php print header_bg;?>;
	<?php } elseif(($_REQUEST['admin_edit'] == "1")OR($_REQUEST['wbg'] == "1") ==true) { ?>
	background-color: <?php print inside_bg;?>;
	<?php } else { ?>
	background-color: <?php print outside_bg;?>;
	<?php if(!empty($css['outside_bg_image'])) { ?>
		<?php if($css['bg_image_style'] == "topcover") { ?>
			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') no-repeat top center fixed ;
			background-size: cover;
		<?php } else if($css['bg_image_style'] == "centercover") { ?>

			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') no-repeat center center fixed ;
			background-size: cover;

		<?php } else { ?>
			background: <?php print outside_bg;?> url('<?php print $css['outside_bg_image'];?>') <?php print $css['bg_image_style'];?> ;
		<?php } ?>
	<?php } ?>
	<?php } ?>
	height: 101%;
	margin : 0; 
	padding : 0; 
	border : 0; 
	<?php if(($_REQUEST['admin_edit'] == "1")&&($_REQUEST['header'] == "1")==true) { ?>
	color : <?php print header_font_color;?>; 
	<?php } else { ?>
	color : <?php print font_color;?>; 
	<?php } ?>

	font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
	font-size: <?php print font_size;?>; 

<?php if($css['text_shadow_on'] == "1") { print "text-shadow: ".$css['text_shadow'].";"; } ?>
}

* {margin: 0;} 
png { border: none; } 

.center { text-align: center; } 
.left { float: left; } 
.right { float: right; } 
.cssClear, .clear { clear:both;font-size: 0px;line-height: 0px; width: 0px; height: 0px; }
.hide { display: none; } 
.textright { text-align: right; } 
.field100 { width: 97%;}
.padtopbottom { padding: 2px 0; } 
.reg,.normal { font-size: 13px; } 
p { padding: 0 0 12px 0 } 
.bold { font-weight: bold; } 
.large1 { font-size: 21px; } 
.large2 { font-size: 17px; } 
.nofloat { float: none !important; width: 100% !important; margin: auto; text-align: center !important; }
.nofloat div { text-align: center !important;  } 
.nofloatlefttext { float: none !important; width: 100% !important; margin: auto; text-align: left !important; }
.nofloatlefttext div { text-align: left !important;  } 
.from_message_to { display: none; } 
.contactformfields { margin-bottom: 16px; } 
.showsmall { display: none; } 
.p5 { width: 5%; } 
.p10 { width: 10%; } 
.p15 { width: 15%; } 
.p20 { width: 20%; } 
.p25 { width: 25%; } 
.p30 { width: 30%; } 
.p35 { width: 35%; } 
.p40 { width: 40%; } 
.p45 { width: 45%; } 
.p50 { width: 50%; } 
.p55 { width: 55%; } 
.p60 { width: 60%; } 
.p65 { width: 65%; } 
.p70 { width: 70%; } 
.p75 { width: 75%; } 
.p80 { width: 80%; } 
.p85 { width: 85%; } 
.p90 { width: 90%; } 
.p95 { width: 95%; } 
.p100 { width: 100%; } 


.hidesidebar { 
	display: none !important;
}
.hidesidebarmain { 
	width: 100% !important;
}

.packagemore { cursor: pointer; } 

.registryamount { font-size: 21px; } 
.addcartbutton { 
	padding: 4px; 
	display: inline-block;
	margin-right: 8px;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	border-radius: 2px;
	text-shadow: none; 
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
}
.addcartbutton:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	cursor: pointer; 
}

img {
	-ms-interpolation-mode: bicubic;
	image-rendering: optimizeQuality;
	-webkit-user-select:none; 
	-webkit-touch-callout:none;
}

.phototag { margin: 0 60px 0px 0; font-size: 17px; line-height: 60px; white-space:nowrap; float: left;} 
#pagephotosearch { float: right; } 
#gototop {  position:fixed; bottom:8px;  right: 8px;  z-index: 2; display: none; cursor: pointer; } 
#bgFadeContainer { position: fixed; width: 100%; height: 100%; top: 0; left: 0;  z-index: 0; } 
#page-wrapper { 
min-height: 100%;
<?php if($_REQUEST['site_type'] == "mobile") {} ?>
<?php if($_SESSION['previewMobile'] == 1) { ?>width: 480px; margin: auto;<?php } ?>
z-index: 2;
position: relative;
}
#page-wrapper-inner {  }

<?php if(($css['menu_placement'] == "left")OR($css['menu_placement'] == "right")==true)  { ?>
#headerAndMenu { 
	position: relative;
	vertical-align: bottom; 
	z-index: 5;
	height: <?php print $css['header_height'];?>px; 
<?php if($css['header_transparent'] != "1") { ?>

		<?php $rgb = hex2rgb($css['header_bg']); ?>
<?php ie8rgba($css['header_bg'],$css['header_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['header_opacity'];?>);
	
	<?php } ?> color: #<?php print $css['header_font_color'];?>; 
	 <?php if($css['header_wide'] == 1) { ?>
		width: 100%; 
	 <?php } else { ?>
	 width: <?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	 <?php } ?>
margin: auto;
<?php addBoxShadow($css['header_box_shadow']);?>
} 
<?php } ?>
#headerAndMenuInner { 
	<?php if(($css['menu_placement'] == "left")OR($css['menu_placement'] == "right")==true) {	
	if($css['header_100'] !== "1") { ?>
	width: <?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	margin: auto;
	<?php }
	} ?>

}
#headerContainer {
height: <?php print $css['header_height'];?>px;
<?php if($css['menu_placement'] == "left") { ?>
	float: right;
	width: auto;
<?php } elseif($css['menu_placement'] == "right") { ?>
	float: left;
	width: auto;
<?php } else { ?>
	 <?php if($css['header_wide'] == 1) { ?>
		width: 100%; 
	 <?php } else { ?>
	 width: <?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	 <?php } ?>
<?php } ?>
	 margin-left: auto; margin-right: auto;
<?php if($css['header_transparent'] != "1") { ?>
<?php if($css['menu_placement'] == "below") { ?>
<?php $rgb = hex2rgb($css['header_bg']); ?>
<?php ie8rgba($css['header_bg'],$css['header_opacity']) ?>

background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['header_opacity'];?>);
<?php } ?> 
<?php } ?>color: #<?php print $css['header_font_color'];?>; 
<?php if($css['header_text_shadow_on'] == "1") { print "text-shadow: ".$css['header_text_shadow'].";"; } ?>

	 <?php if($css['header_center'] == "1") { ?>
	text-align: center;
<?php } ?>
<?php if($css['menu_placement'] == "below")  { ?>
<?php addBoxShadow($css['header_box_shadow']);?>
<?php } ?>
}
.mobileheaderbg { background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['header_opacity'];?>); } 

#header {
<?php if($css['menu_placement'] == "left") { ?>
	width: auto;
<?php } elseif($css['menu_placement'] == "right") { ?>
	width: auto;
<?php } else { ?>

	 <?php if($css['header_wide'] == 1) { ?>
	 width: <?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	<?php } else { ?>
	 width:100%; 
	 <?php } ?>
 <?php } ?>

	 margin-left: auto; margin-right: auto;
	 
	 <?php if($css['header_center'] == "1") { ?>
	text-align: center;
<?php } ?>
}

#header .inner { <?php if($_REQUEST['site_type'] !== "mobile") {?> padding-left: <?php print $css['header_padding'];?>px; padding-right: <?php print $css['header_padding'];?>px; padding-top: <?php print $css['header_padding_tb'];?>px; padding-bottom: <?php print $css['header_padding_tb'];?>px;<?php } ?> }
#header img {
	display: inline;
}
.headerName {
	font-size: 40px;
	font-weight: bold;
	padding: 6px;
}

#menucontainerouter { 
<?php if($css['menu_placement'] == "left") { ?>
	float: left;
<?php } elseif($css['menu_placement'] == "right") { ?>
	float: right;
<?php	} ?>
 	position: relative; width:100%; 
} 

/* ################################################ PHOTO PRODUCTS ############################################################### */

#croptabs { text-align: center; margin: 12px 0 ;} 
#croptabs .tab { 
	padding: 8px;
	margin-right: 8px;
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
}
#croptabs .tab:hover { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
}
#croptabs .tabon {
	padding: 8px;
	margin-right: 8px;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
 } 


.discount { display: none; background: <?php print inside_bg;?>;  border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; box-shadow: 0 0 4px rgba(0,0,0,.4); z-index: 4;width: 250px; right: 16px; } 
.discount .inner { padding: 8px; } 
.proddiscount { display: none; background: <?php print inside_bg;?>;  border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; box-shadow: 0 0 4px rgba(0,0,0,.4); z-index: 4;width: 250px; } 
.proddiscount .inner { padding: 8px; } 

#enlargephoto  { display: none; position: absolute; z-index: 200; box-shadow: 0 0 8px rgba(0,0,0,.5); } 


#photoprods, #photocrop, #photopackagecontainer, #paymentdiv  { display: none; background: <?php print inside_bg;?>; width: 80%; left: 50%; margin-left: -40%; border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; z-index: 200; box-shadow: 0 0 8px rgba(0,0,0,.5); } 

#completereview  { display: none; background: <?php print inside_bg;?>;width: 60%; left: 50%; margin-left: -30%;  border: solid 1px #<?php print $css['outside_bg'];?>; position: fixed; z-index: 200; box-shadow: 0 0 8px rgba(0,0,0,.5); } 
#completereview .inner { padding: 24px; } 

#photoproductsnexttophotobg { display: none; position: fixed;  right: 0; top: 0; width: 30%; float: right; background: <?php print inside_bg;?>; height:100%;   z-index: 19; } 
#photoproductsnexttophoto { display: none; position: relative;  right: 0; top: 0; width: 30%; float: right; background: <?php print inside_bg;?>; height:100%;   z-index: 20; } 
#photoproductsnexttophoto .inner { padding: 24px; height:100%; } 
.photowithproducts{ width: 70%; float: left; }
.ssheaderwithproducts { width: 70% !important;  position: relative; }


#photocrop { z-index: 400; } 
#loading { width:300px; position: fixed; left: 50%; margin-left: -174px; padding: 24px; text-align: center; top: 40%; display: none; z-index: 500;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
 } 

 #closebuyphototab { position: fixed; left: 0;width: 100%; text-align: center; top: 0; display: none; z-index: 500;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
 } 
#closebuyphototab .inner { padding: 12px; } 
#paymentdiv .inner { padding: 24px; width: 100%; max-width: 600px; margin: auto; } 
 #photoprodsinner { padding: 24px; } 
 #photocropinner { padding: 16px; } 

#singlephotopackagetab {   display: none; padding: 8px 0;} 

#termsandconditions  { display: none; background: <?php print inside_bg;?>; width: 800px; left: 50%; margin-left: -400px; border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; z-index: 500; box-shadow: 0 0 8px rgba(0,0,0,.5); } 
 #termsandconditionsinner { padding: 24px; } 

#afftermsandconditions  { display: none; background: <?php print inside_bg;?>; width: 800px; left: 50%; margin-left: -400px; border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; z-index: 500; box-shadow: 0 0 8px rgba(0,0,0,.5); margin-bottom: 24px;} 
 #afftermsandconditionsinner { padding: 24px; } 

#commentsbackground { 
  width:100%;
  height:100%;
  min-height: 100%;
	background-color: #<?php print $css['full_screen_background'];?>;
  opacity:<?php print $css['full_screen_opacity'];?>;
  overflow: hidden;
  display: block;
  position: fixed;
  top: 0;
  left: 0;
	display: none;
  z-index: 200;
}


#commentscontainer  {  background: <?php print inside_bg;?>;   } 

.commentsshowpage { display: block; margin: auto;  max-width: 800px; width: 100%; } 
.commentsshowwindow { position: absolute; width: 80%; left: 50%; margin-left: -40%; z-index: 500; box-shadow: 0 0 24px rgba(0,0,0,.6);  border: solid 1px #<?php print $css['outside_bg'];?>; margin-bottom: 50px; } 

 #commentscontainerinner { padding: 24px; } 

.commentstab { 
	position: fixed;
	bottom: 0;
	left: 15%;
	padding: 8px;
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
}


#photopackageinner { padding: 16px; } 
#photopackagetab {  display: none; text-align: center; padding: 8px 0;} 

#addonephoto { 
	padding: 12px; 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
} 
#addonephoto:hover { 
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
}


#grouptabs { margin: 0 0 8px 0; } 
.groupdescr { padding: 4px 0 8px 0 ; } 
#grouptabs .tab { 
	float: left;
	padding: 8px;
	margin: 0px 8px 8px 0px;
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	cursor: pointer;
}
#grouptabs .tab:hover { 
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
}
#grouptabs .tabon {
	float: left;
	padding: 8px;
	margin-right: 8px;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
 } 

.underlineonly { 
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	padding: 12px 8px; 
	margin: 0px 0px  8px 0px;
} 
.nounderlineonly { 
	padding: 12px 8px; 
	margin: 0px 0px  8px 0px;
} 


.nophotopackage { width: 100%; } 
.packageproducts { width: 60%; float: right; } 
.photoprod .name { width: 45%; float: left;} 
.photoprod .namenooptions { width: 45%; float: left;} 
.photoprod .options{padding: 4px 0px;  } 
.photoprod .sub{ padding: 4px 0px; } 
.photoprod .options .inputdropdown { padding: 2px; } 

.photoprod .price { width: 25%; float: left; text-align:right;} 
.photoprod .qty { width: 18%; float: left; text-align:right;} 
.photoprod .qty input { padding: 2px; } 
.photoprod .cartbutton { width: 12%; float: left; text-align:right;}
.photoprod .the-icons { font-size: <?php print $css['h3_size'];?>px; } 

.photoprod {} 
.photoprod .underline { 
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>
	padding: 12px 8px; 
	margin: 0px 0px  8px 0px;
	color: #<?php print $css['boxes_text'];?>; 
	background: #<?php print $css['boxes'];?>; 
} 
.photoprod .underline:hover { background: #<?php print $css['boxes'];?>; color: #<?php print $css['boxes_text'];?>; } 
.photoprod .underline h3 { text-shadow: none; } 
.photoprod .underlinecolumn { border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;  padding: 12px 8px;} 
.photoprod .underline:hover a { color: #<?php print $css['boxes_link'];?>; } 
.photoprod .underline:hover a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 


#splashcontainer { display: none; background: <?php print inside_bg;?>; width: 80%; left: 50%; margin-left: -40%; border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; z-index: 200; box-shadow: 0 0 8px rgba(0,0,0,.5); margin-bottom: 24px; } 
#splashinner { padding: 24px; } 
#storeitemcontainer { display: none; background: <?php print inside_bg;?>; width: 80%; left: 50%; margin-left: -40%; border: solid 1px #<?php print $css['outside_bg'];?>; position: absolute; z-index: 800; box-shadow: 0 0 8px rgba(0,0,0,.5);  margin-bottom: 24px;  } 
#storeiteminner { padding: 24px; } 


#buybackground, #affbackground, #paymentdivbg, #splashbackground, #storeitembackground,#clfthumbsbg { 
  width:100%;
  height:100%;
  min-height: 100%;
	background-color: #<?php print $css['full_screen_background'];?>;
  opacity:<?php print $css['full_screen_opacity'];?>;
  overflow: hidden;
  display: block;
  position: fixed;
  top: 0;
  left: 0;
	display: none;
  z-index: 200;
}

#comparephotos { 
	z-index: 10000; position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none;	background-color: #<?php print $css['full_screen_background'];?>;
}
#comparenav { 
	background: #<?php print $css['sm_background'];?>;
	border-top: solid 1px #<?php print $css['sm_border_top'];?>;
	border-bottom: solid 1px #<?php print $css['sm_border_bottom'];?>;
	position: fixed; left: 0; top: 0; width: 100%; z-index: 10009;
 }
#comparenavinner { 
	padding: 16px; text-align: center; position: relative; z-index: 10010;
}
#comparephotosdisplaycontainer { 
	position: absolute; z-index: 10001;  
}
#comparephotosdisplay { 
top: 0; display: none;
}


#comparenavinner a { color: #<?php print $css['sm_link_color'];?>;  <?php if($css['sm_underline'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 
#comparenavinner a:hover { color: #<?php print $css['sm_link_hover'];?>;  <?php if($css['sm_underline_hover'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 


#comparenavinner .the-icons {
  font-size: 24px;
  line-height: 16px;
  color: #<?php print $css['sm_link_color'];?>;
 padding: 4px;
 margin-right: 4px;
 text-shadow: #<?php print $css['sm_background'];?> 0px 0px 2px;
}
#comparenavinner .the-icons:hover { color: #<?php print $css['sm_link_hover'];?>; cursor: pointer; } 
#comparenavinner.icon-text { font-size: 12px;color: #<?php print $css['sm_link_color'];?>; cursor: pointer; } 
#comparenavinner .icon-text:hover { color: #<?php print $css['sm_link_hover'];?>; cursor: pointer; } 
.compareactionscontainer { 
	position: relative; left: 0; display: none;
}

.compareactions {
	list-style: none;
	margin: auto;
	padding: 0;
	text-align: center;
	top: 40%;
	left: 0;
	position: relative;

}

.compareactions li { 
	float: left;
	padding: 8px;
	margin: 0px 8px 8px 0px;
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	cursor: pointer;
}
.compareactions li:hover { 
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
}


#singlephoto { 
  width:100%;
  height:100%;
  min-height: 100%;
	background-color: #<?php print $css['full_screen_background'];?>;
  opacity:<?php print $css['full_screen_opacity'];?>;
  overflow: hidden;
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 200;
}


/* ############################################### TOP MAIN MENU ##################################################################### */
#mobilemenubutton { 
	padding: 8px;
	font-size: 24px;
}
#mobilemenubutton .icon-menu { font-size: 30px; } 
#mobilemenubutton .menutext { padding: 0px; position: relative; top: -2px; } 
#mobilemenu { 
<?php $rgb = hex2rgb($css['menu_color']); ?>
<?php ie8rgba($css['menu_color'],$css['menu_opacity']) ?>
	background-color: #<?php print $css['menu_color'];?>;
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['menu_opacity'];?>);
	border:1px solid <?php print menu_border_b;?>;
	font-size: 27px;
	margin-bottom: 16px;
	z-index: 20;
	color: <?php print menu_link_color;?>;
	position: relative;
}

#mobilemenu a,  #mobilemenu a:active {   text-decoration: <?php if($css['underline_menu_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print menu_link_color;?>; font-size: 25px;}
#mobilemenu a:link, #mobilemenu a:visited { text-decoration: <?php if($css['underline_menu_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print menu_link_color;?>; font-size: 25px;  }
#mobilemenu a:hover {  color: <?php print menu_link_hover;?>; text-decoration: <?php if($css['underline_menu_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>; font-size: 25px; } 

#mobilemenulinks ul li a { 
	padding: 8px 24px;
	border-top:1px solid <?php print menu_border_b;?>;
	display: block;
	background-color: #<?php print $css['menu_color'];?>;
}
#mobilemenulinks ul   { 
	list-style: none; 
	margin: 0; 
	padding: 0; 
	text-align: left; 
}
#mobilemenubutton .the-icons { text-shadow: none;  color: <?php print menu_link_color;?>} 

#topMainMenuContainer {
	margin: auto;
	display: block;
	font-family: '<?php print $css['menu_font'];?>', Arial;
<?php if($css['menu_placement'] == "left") { ?>
	vertical-align: bottom;
	position: absolute;
	bottom: 0;
	left 0;
	width: auto; 
	vertical-align: bottom;
	 margin-top: <?php print $css['menu_margin'];?>px;
<?php } elseif($css['menu_placement'] == "right") { ?>
	width: auto;
	 margin-top: <?php print $css['menu_margin'];?>px;
	vertical-align: bottom;
	position: absolute;
	bottom: 0;
	right: 0;

<?php } else { ?>
position: abvsolute;
	<?php if($css['main_menu_wide'] == 1) { ?>
	width: 100%;
	<?php } else { ?>
	 width:<?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	<?php } ?>

<?php } ?>

font-size: <?php print $css['footer_menu_font_size'];?>px;
<?php if($css['menu_transparent']!=="1") { ?>
<?php addBoxShadow($css['top_menu_box_shadow']);?>
<?php $rgb = hex2rgb($css['menu_color']); ?>

<?php ie8rgba($css['menu_color'],$css['menu_opacity']) ?>
		background-color: #<?php print $css['menu_color'];?>;
		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['menu_opacity'];?>);
	border-bottom:1px solid <?php print menu_border_b;?>;
	border-top:1px solid <?php print menu_border_a;?>;
	<?php } ?>
	color: <?php print menu_font_color;?>;
	text-align: center;
<?php if($css['menu_text_shadow_on'] == "1") { print "text-shadow: ".$css['menu_text_shadow'].";"; } ?>
	<?php if($css['main_menu_padding'] > 0) { ?>

	<?php } else { ?>
	line-height: auto;
	<?php } ?>
	text-align: middle;
	z-index: 10;
	padding-top: <?php print $css['main_menu_padding'];?>px;
	padding-bottom: <?php print $css['main_menu_padding'];?>px;
} 
.topMainMenuEnd {
	display:block;
	float:left;
	margin:auto;
	padding:6px;
	position:relative;
	border-left: solid 1px  <?php print menu_border_b;?>;
	text-decoration:none;
}
.mobilemenubg { background-color: #<?php print $css['menu_color'];?>; } 


#topMainMenu {
	list-style:none;
	margin:auto;
	padding:0;
	z-index: 8;
	<?php if($css['menu_upper'] == 1) { ?>
	text-transform:uppercase;
	<?php } ?>

<?php if($css['menu_placement'] == "left") { ?>
	width: auto;
<?php } elseif($css['menu_placement'] == "right") { ?>
	width: auto;
<?php } else { ?>

	<?php if($css['main_menu_wide'] == 1) { ?>
	 width:<?php print page_width;?>; 
	max-width: <?php print $css['page_width_max'];?>px;

	<?php } ?>
	<?php } ?>
	
	<?php if($css['main_menu_padding'] > 0) { ?>

	<?php } else { ?>
	line-height: auto;
	<?php } ?>

}
#topMainMenu a,  #topMainMenu a:active {   text-decoration: <?php if($css['underline_menu_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print menu_link_color;?>;}
#topMainMenu a:link, #topMainMenu a:visited { text-decoration: <?php if($css['underline_menu_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print menu_link_color;?>;   }
#topMainMenu a:hover {  color: <?php print menu_link_hover;?>; text-decoration: <?php if($css['underline_menu_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>;  } 


/* ############## MAIN MENU ########################### */

ul.dropdown    { 
	list-style: none; 
	margin: 0; 
	padding: 0; 
	text-align: left; 
	display: inline; 
	 <?php if($css['menu_center'] !== "1") { ?>float: left; <?php } ?> 
}

ul.dropdown   { position: relative;  z-index: 3;}

ul.dropdown li {  
	padding: 0;
	display: inline; 
	margin: 0 0 0 0px ;
}


ul.dropdown li a {  
	text-align: left; 
	display: inline; 
	zoom: 1; 
	<?php if($css['top_menu_side_borders'] == "1") { ?>
	<?php if($css['top_menu_button_transparent'] !== "1") { ?>
		background: #<?php print $css['top_menu_bg'];?>;
	<?php } ?>
	border-left: solid 1px #<?php print $css['top_menu_border_l'];?>;
	border-right: solid 1px #<?php print $css['top_menu_border_r'];?>;
	<?php } ?>
	padding: <?php print $css['main_menu_padding'];?>px <?php print $css['top_menu_spacing'] / 2;?>px  ;

}

ul.dropdown a:hover		            { 	<?php if($css['top_menu_side_borders'] == "1") { ?>background: #<?php print $css['top_menu_bg_hover'];?><?php } ?>;  }
ul.dropdown a:active                {  }
ul.dropdown li a                    { display: inline; }
ul.dropdown li:last-child a         { border-right: none; } /* Doesn't work in IE */
ul.dropdown li:hover                { position: relative; }
ul.dropdown li.hover a              {  }

/* 
	LEVEL TWO
*/
ul.dropdown ul 						{ 
	line-height: 100%; 
	width: 220px; 
	visibility: hidden; 
	position: absolute; 
	top: 100%; 
	left: 0; 	
	box-shadow: 0px 4px 5px rgba(0, 0, 0, .5); 
	z-index: 2000; 
	list-style: none; 
	margin: 0; 
	padding: 0;
	<?php $rgb = hex2rgb($css['menu_color']); ?>
	<?php ie8rgba($css['menu_color'],$css['menu_opacity']) ?>
	background-color: #<?php print $css['menu_color'];?>;
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['menu_opacity'];?>);
	margin-left: -<?php print $css['top_menu_padding'];?>px;
	}
ul.dropdown ul li  { 
	line-height: 100%; 

	font-weight: normal;
	text-align: left;
	float: none; 
	 margin-left: 0;
}
ul.dropdown ul li:hover			{; float: none; }
		  
                                    /* IE 6 & 7 Needs Inline Block */
ul.dropdown ul li a	{ border-right: none;  display: block; 	 padding: 12px 0px 8px <?php print $css['top_menu_spacing'] / 2;?>px ;} 

/* 
	LEVEL THREE
*/
ul.dropdown ul ul 					{ left: 100%; top: 0; }
ul.dropdown li:hover > ul 			{ visibility: visible; }

/*
	LEVEL 4
*/
ul.dropdown ul ul ul					{ left: 100%; top: 0; }
ul.dropdown li:hover > ul 			{ visibility: visible; }

/* ################# END MAIN MENU ########################### */


#mainmenu{
	margin: 0;
	z-index: 10;
	text-align: center;
	margin: auto;
	 padding: 0px <?php print $css['top_menu_padding'];?>px 0px <?php print $css['top_menu_padding'];?>px  ;
	display: block;
	vertical-align: middle;
}

#mainmenu li {
	margin: 0;
	padding: 0;
	list-style: none;
	<?php if($css['menu_center'] == "1") { ?>
	float: none;
	<?php } else { ?>
	float: left;
	<?php } ?>
	<?php if($css['menu_placement'] == "right") { ?>
	margin: 0 0 0 <?php print $css['top_menu_spacing'];?>px ;
	<?php } else { ?>
	margin: 0 <?php print $css['top_menu_spacing'];?>px 0 0;
	<?php } ?>
	display: inline;
}

#mainmenu li a {	
	color: <?php print menu_link_color;?>;
	text-align: center;
	text-decoration: none;
	<?php if($css['menu_upper'] == 1) { ?>
	text-transform:uppercase;
	<?php } ?>
}

#mainmenu li a:hover {	background: <?php print menu_color_hover;?>}

#photosmenu { 
	display: block;
	visibility: visible;
	position: absolute;
}

#mainmenu div {	
	z-index: 10;
	position: absolute;
	visibility: hidden;
	margin: 0;
	padding: 0;
	border-bottom: solid 1px <?php print menu_border_b;?>;
	border-left: solid 1px <?php print menu_border_b;?>;
	border-right: solid 1px <?php print menu_border_b;?>;
	border-top: solid 0px <?php print menu_border_b;?>;
	color: <?php print font_color;?>;
	background-color: <?php print menu_color_hover;?>;
	line-height: normal;
		<?php $rgb = hex2rgb($css['menu_color']); ?>
<?php ie8rgba($css['menu_color'],$css['menu_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['menu_opacity'];?>);
		display: block;
	-moz-border-radius: <?php print $css['menu_rounded_corners'];?>px;
	border-radius: <?php print $css['menu_rounded_corners'];?>px;
<?php if($css['menu_shadow_size'] > 0) {  ?>
	-moz-box-shadow: 1px 1px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-webkit-box-shadow: 1px 1px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-goog-ms-box-shadow: 1px 1px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	box-shadow: 1px 1px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
<?php } ?>

}
#mainmenu  .photos {	
	padding: 8px;
	display: block;
	clear: right;
}
 #mainmenu span {	
	display: block;
}
#mainmenu div .photos a { 
line-height: 30px;
}

	#mainmenu div a {	
		position: relative;
		padding: 5px 10px;
		text-align: left;
		text-decoration: none;
		clear: right;
	display: block;
	}

	#mainmenu div a:hover {	
		background: <?php print menu_color_hover;?>;
		color: <?php print menu_link_hover;?>;
}

/* ############################################### END TOP MAIN MENU AREA  ################################################# */

/* ############################################### SHOP MENU  ################################################# */

#shopmenucontainer { 
	<?php if($css['sm_background_transparent'] !== "1") { ?>
	background: #<?php print $css['sm_background'];?>;
	border-top: solid 1px #<?php print $css['sm_border_top'];?>;
	border-bottom: solid 1px #<?php print $css['sm_border_bottom'];?>;
	<?php } ?>
	position: relative;
	z-index: 10;
}
#shopmenuinner { 
	width: 100%;
	<?php 	if($css['header_100'] !== "1") { ?>
	max-width: <?php print $css['page_width_max'];?>px;
	<?php } ?>
	margin: auto;
	color: #<?php print $css['sm_text'];?>;
	font-family: '<?php print $css['sm_font_family'];?>', Arial;
	font-size: <?php print $css['sm_font_size'];?>px;
	position: relative;
	z-index: 3;
<?php if($css['sm_text_shadow_on'] == "1") { print "text-shadow: ".$css['sm_text_shadow'].";"; } ?>

}
#shopmenu { 
	padding: <?php print $css['sm_padding_tb'];?>px <?php print $css['sm_padding_lr'];?>px;  text-align: right;
}
#accountmenu { 
	padding: <?php print $css['sm_padding_tb'];?>px <?php print $css['sm_padding_lr'];?>px;  text-align: left; float: left;
}
#accountmenu ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
} 
#accountmenu ul li { 
	display: inline;
	margin-right: <?php print $css['sm_spacing'];?>px;
}
#accountloginresponse, #forgotloginresponse { display: none; font-size: 12px; margin-top: 4px; }
#forgotemailmessage {  font-size: 12px; font-family: '<?php print font_family;?>'; padding: 4px 0; } 

.loginform { float: left; 	padding: <?php print $css['sm_padding_tb'];?>px <?php print $css['sm_padding_lr'];?>px; }
.forgotpassword { font-size: 12px; font-family: '<?php print font_family;?>'; }
#shopmenuinner a { color: #<?php print $css['sm_link_color'];?>;  <?php if($css['sm_underline'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 
#shopmenuinner a:hover { color: #<?php print $css['sm_link_hover'];?>;  <?php if($css['sm_underline_hover'] == "1") { ?>text-decoration: underline; <?php } else { ?>text-decoration: none;<?php } ?> } 


#shopmenu ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
} 
#shopmenu ul li { 
	display: inline;
	margin-left: <?php print $css['sm_spacing'];?>px;
}

#accountloginpage { max-width: 400px; margin: auto; } 


/* ################################################ H1 H2 TITLES & LINKS ############################################################# */
/*  pageTitle - When someone is viewing a page or  track details this is the page title */
h1 {
	font-family: '<?php print title_font_family;?>',Arial ;

	font-size: <?php print $css['title_size'];?>px;
	color: <?php print page_title;?>;
	border-bottom: 0px solid <?php print page_title;?>;
	font-weight: normal;

<?php if($css['title_text_shadow_on'] == "1") { print "text-shadow: ".$css['title_text_shadow'].";"; } ?>
}
.title h1 {
	font-family: '<?php print title_font_family;?>',Arial ;

	font-size: <?php print $css['title_size'];?>px;
	color: <?php print page_title;?>;
	border-bottom: 0px solid <?php print page_title;?>;
	font-weight: normal;
	<?php if($css['h1_upper'] == 1) { ?>
	text-transform:uppercase;
	<?php } ?>

<?php if($css['title_text_shadow_on'] == "1") { print "text-shadow: ".$css['title_text_shadow'].";"; } ?>
}



 a.pageTitle:active,h1 a ,h1 a:active { text-decoration: none; color: <?php print page_title;?>; }
a.pageTitle:link, a.pageTitle:visited,h1 a:link,h1 a:visited { text-decoration: none; color: <?php print page_title;?>; }
a.pageTitle:hover,h1 a:hover { color: <?php print page_title_hover;?>; text-decoration: <?php if($css['underline_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>; } 

.pageSubTitle {
	font-size: <?php print $css['title_size'];?>px;
	color: <?php print page_title;?>;
	border-bottom: 0px solid <?php print page_title;?>;
	width: 100%;
}
 a.pageSubTitle, a.pageSubTitle:active{ text-decoration: none; color: <?php print page_title;?>; }
a.pageSubTitle:link, a.pageSubTitle:visited { text-decoration: none; color: <?php print page_title;?>; }
a.pageSubTitle:hover { color: <?php print page_title_hover;?>; text-decoration: <?php if($css['underline_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>; } 

h2 {
	font-family: '<?php print title_font_family;?>',Arial;
	font-size: <?php print $css['h2_size'];?>px;
	color: <?php print page_title;?>;
	border-bottom: 0px solid <?php print page_title;?>;
	width: 100%;
	font-weight: normal;
<?php if($css['title_text_shadow_on'] == "1") { print "text-shadow: ".$css['title_text_shadow'].";"; } ?>
}
h2 a,h2 a:active{ text-decoration: none; color: <?php print page_title;?>; }
h2 a:link,h2 a:visited { text-decoration: none; color: <?php print page_title;?>; }
h2 a:hover { color: <?php print page_title_hover;?>; text-decoration: <?php if($css['underline_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>; } 

h3, .h3 {
	font-family: '<?php print title_font_family;?>', Arial;
	font-size: <?php print $css['h3_size'];?>px;
	color: <?php print page_title;?>;
	border-bottom: 0px solid <?php print page_title;?>;
	width: 100%;
	font-weight: normal;
<?php if($css['title_text_shadow_on'] == "1") { print "text-shadow: ".$css['title_text_shadow'].";"; } ?>
}
h3 a,h3 a:active{ text-decoration: none; color: <?php print link_color;?>; }
h3 a:link,h3 a:visited { text-decoration: none; color: <?php print link_color;?>; }
h3 a:hover { color: <?php print link_color_hover;?>; text-decoration: <?php if($css['underline_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>; } 

h4 { font-size: 15px; } 

a, a:active { text-decoration: <?php if($css['underline_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print link_color;?>; <?php if($css['link_text_shadow_on'] == "1") { print "text-shadow: ".$css['link_text_shadow'].";"; } ?>}
a:link, a:visited { text-decoration: <?php if($css['underline_links'] == "1") { print "underline"; } else { print "none"; } ?>; color: <?php print link_color;?>;<?php if($css['link_text_shadow_on'] == "1") { print "text-shadow: ".$css['link_text_shadow'].";"; } ?> }
a:hover { color: <?php print link_color_hover;?>; text-decoration: <?php if($css['underline_links_hover'] == "1") { print "underline"; } else { print "none"; } ?>;<?php if($css['link_text_shadow_on'] == "1") { print "text-shadow: ".$css['link_text_shadow'].";"; } ?> } 


#billboardContainer { 
	width: 100%;
	margin: 0 auto 0 auto;
	display: block;
	padding: 0;
}


#billboard { 
	display: block;
	float: left;
}
#neatbb { margin: auto; position: relative;} 
#neatbbslides { position: relative;  overflow: hidden; 
} 

.neatbbslide { display: none; position: absolute;

} 
.neatbbslide1 {position: absolute; display: none; } 
#neatbbmenu { text-align: center; padding: 6px; position: absolute; z-index: 3; bottom: 0; right: 0; } 
#neatbbmenu a {  padding: 1px 4px; margin: 2px; }  
.bn {  padding: 4px; opacity:0.5; }
.bnon {  box-shadow: 0px 0px 2px rgba(0, 0, 0, .5); opacity:1;}





/* ######################################################## END H1 TITLES & LINKS ################################################# */


/* ##################################################### CATEGORY LISTING STYLES  ################################################ */

#listing-blog { } 
#listing-blog .preview .headline { padding: 0 4px;  } 
#listing-blog .preview .sub {padding: 4px;}
#listing-blog .preview .photos { padding: 4px; }
#listing-blog .preview .comments { padding: 4px; }
#listing-blog .preview .previewtext { padding: 4px; }

#listing-first { padding: <?php print boxes_padding;?>px; margin: 4px 4px  20px 0px;} 
#listing-first .content .headline { padding: 0 4px;  } 
#listing-first .content .sub {padding: 4px;}
#listing-first .content .photos { padding: 4px; }
#listing-first .content .comments { padding: 4px; }
#listing-first .content .previewtext { padding: 4px; }


#listing-standard .preview {
	<?php if($css['boxes_transparent'] <=0) { ?>
	<?php $rgb = hex2rgb($css['boxes']); ?>
	<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>

	<?php addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;


	height: 1%;
	margin: 4px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
}
#listing-standard .preview h2 a { color: #<?php print $css['boxes_link'];?>; text-decoration: none;  } 
#listing-standard .preview h2 a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
#listing-standard .headline { font-size: <?php print $css['boxes_title_size'];?>px; font-family: '<?php print title_font_family;?>', Arial; } 



#regguestbook .preview {
	<?php if($css['boxes_transparent'] <=0) { ?>
	<?php $rgb = hex2rgb($css['boxes']); ?>
	<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>

	<?php addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;


	height: 1%;
	margin: 4px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
}
#regguestbook .preview h2 a { color: #<?php print $css['boxes_link'];?>; text-decoration: none;  } 
#regguestbook .preview h2 a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
#regguestbook .headline { font-size: <?php print $css['boxes_title_size'];?>px; font-family: '<?php print title_font_family;?>', Arial; } 



#ebmessage {
	<?php if($css['boxes_transparent'] <=0) { ?>
	<?php $rgb = hex2rgb($css['boxes']); ?>
	<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>

	<?php addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;


	height: 1%;
	margin: 4px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
	text-align: center;
}
#ebmessage h3 { color: #<?php print $css['boxes_text'];?>; text-decoration: none;  } 



#listing-standard .preview .thumbnail, #listing-standard .preview .thumbnailnoborder { max-width: <?php print $css['boxes_img_width'];?>px; height: auto; } 
.preview .image {  }

#listing-standard .preview .thumbnail { 
	border: 1px solid <?php print thumb_border;?>;
	display: block;
	float: left;
	margin: 6px 12px 0 0;
}

#listing-standard .preview .thumbnailnoborder { 
	border: 0px;
	display: block;
	float: left;
	margin: 6px 12px 0 0;
}


#listing-standard .preview .headline { padding: 4px; }
#listing-standard .preview .sub {padding: 4px;  }
#listing-standard .preview .previewtext { padding: 4px; }
#listing-standard .preview .comments { padding: 4px; }


#listing-thumbnail .preview {
	<?php $rgb = hex2rgb($css['thumb_listing_bg']); ?>
	<?php ie8rgba($css['thumb_listing_bg'],$css['thumb_listing_opacity']) ?>

	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['thumb_listing_opacity'];?>);
	border: <?php print $css['thumb_listing_border_size'];?>px <?php print $css['thumb_listing_border_style'];?> #<?php print $css['thumb_listing_border'];?>;
	color: #<?php print $css['thumb_listing_text'];?>;
	height: 1%;
	padding: <?php print $css['thumb_listing_padding'];?>px;
	margin: <?php print $css['thumb_listing_margin'];?>px;
	width: <?php print $css['thumb_listing_width'];?>px;
	height: <?php print $css['thumb_listing_height'];?>px;
	<?php addBoxShadow($css['thumb_listing_box_shadow']);?>
	-moz-border-radius: <?php print $css['thumb_listing_border_radius'];?>px;	border-radius: <?php print $css['thumb_listing_border_radius'];?>px; 
	float: left; 
	text-align: center; 
	display: block;
}
#listing-thumbnail .preview h2 a { color: #<?php print $css['thumb_listing_title'];?>; font-size: <?php print $css['thumb_listing_title_size'];?>px; 	font-family: '<?php print title_font_family;?>',Arial; text-decoration: none; } 
#listing-thumbnail .preview h2 a:hover { color: #<?php print $css['thumb_listing_title_hover'];?>; } 
#listing-thumbnail .text div { 	padding: 4px; } 

#listing-thumbnail .preview .thumbimage { position: relative; text-align: center; vertical-align: bottom; 	margin: auto; width: <?php print $css['thumb_listing_thumb_width'];?>px;	height: <?php print $css['thumb_listing_thumb_height'];?>px; line-height: <?php print $css['thumb_listing_thumb_height'];?>px;
}
#listing-thumbnail .preview .text { padding-top: <?php print $css['thumb_listing_padding'];?>px;padding-bottom: <?php print $css['thumb_listing_padding'];?>px; }
#listing-thumbnail .preview .comments { padding: 4px; }
#listing-thumbnail .preview .thumbnail { margin: auto;vertical-align: bottom; border: 1px solid #<?php print $css['thumb_listing_thumb_border'];?>;} 
#listing-thumbnail .text div { 	padding: 4px; } 


.pt { max-height:<?php print $css['thumb_listing_thumb_height'];?>px; width: auto; } 
.ls { max-width:<?php print $css['thumb_listing_thumb_width'];?>px; height: auto; } 



#thumbsj { }
#thumbsj .thumbcontainer { float: left; clear: right;padding: 0; overflow: hidden;  border: solid 0px #000000; position: relative;} 
#thumbsj .thumbcontainer .iconsinfo { 
	position: absolute; 
	bottom: 0; left: 0;  
	<?php $rgb = hex2rgb($css['on_photo_text_bg']); ?>
	<?php ie8rgba($css['on_photo_text_bg'],$css['on_photo_text_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['on_photo_text_opacity'];?>);
	width: 100%; 
	z-index: 5;
	padding: 8px; 
	display: none;
	color: #<?php print $css['on_photo_title']; ?>;
} 
#thumbsj .thumbcontainer .iconsinfo .thumbnailactions .inner .the-icons  { color: #<?php print $css['on_photo_title']; ?>; opacity: .6;  text-shadow: #<?php print $css['on_photo_text_bg'];?> 0px 0px 2px;} 
#thumbsj .thumbcontainer .iconsinfo .thumbnailactions .inner .the-icons:hover { opacity: 1;} 

#listing-onphoto { } 
#listing-onphoto .preview { 
		border: <?php print $css['on_photo_border_size'];?>px <?php print $css['on_photo_border_style'];?> #<?php print $css['on_photo_border'];?>; 
		border-radius: <?php print $css['on_photo_border_radius'];?>px; 
		background:#<?php print $css['on_photo_bg'];?>;
		float:left;
		position: relative;
		overflow: hidden;
		margin: <?php print $css['on_photo_margin'];?>px;
		padding:0;
		width: <?php print $css['on_photo_width'];?>px;
		height: <?php print $css['on_photo_height'];?>px;
		display: block;
<?php addBoxShadow($css['on_photo_box_shadow']);?>
		}

	#listing-onphoto .preview .text a{ color: #<?php print $css['on_photo_title']; ?>; text-decoration: none; } 
	#listing-onphoto .price {
		font-size: 21px;
		position: absolute;
		left: 8px;
		top: 8px;
		text-shadow: 1px 1px 1px #<?php print $css['on_photo_title']; ?>;
		color: #<?php print $css['on_photo_text_bg'];?>;
	}

	#listing-onphoto .preview .text {
		position: absolute;
		bottom: 0;
		left:<?php print (100 - $css['on_photo_text_width']) / 2; ?>%;
		<?php $rgb = hex2rgb($css['on_photo_text_bg']); ?>
		<?php ie8rgba($css['on_photo_text_bg'],$css['on_photo_text_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['on_photo_text_opacity'];?>);
		width:<?php print $css['on_photo_text_width'];?>%;
		text-align: left;
		margin: auto;
		padding: 12px;
	}

#listing-onphoto .the-icons { 
	color: #<?php print $css['on_photo_title']; ?>;
}
#listing-onphoto .preview h2 {
	font-size: <?php print $css['on_photo_title_size'];?>px;
}

#listing-onphoto .preview .text .headline {
	padding: 2px 8px;
	color: #<?php print $css['on_photo_title']; ?>;
	font-size: <?php print $css['on_photo_title_size'];?>px;
	font-family: '<?php print title_font_family;?>',Arial;
}
#listing-onphoto .preview .text .previewtext {
	color:#<?php print $css['on_photo_text'];?>;
	padding: 2px 8px;
	display: none;
}
#listing-onphoto .onphotophoto { display: none; } 




#listing-stacked { 
	list-style-type: none;
	position: relative; 
	text-align: left;
}
#listing-stacked .preview {
	<?php $rgb = hex2rgb($css['thumb_listing_bg']); ?>
	<?php ie8rgba($css['thumb_listing_bg'],$css['thumb_listing_opacity']) ?>

	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['thumb_listing_opacity'];?>);
	border: <?php print $css['thumb_listing_border_size'];?>px <?php print $css['thumb_listing_border_style'];?> #<?php print $css['thumb_listing_border'];?>;
	color: #<?php print $css['thumb_listing_text'];?>;
	padding: <?php print $css['thumb_listing_padding'];?>px;
	margin: <?php print $css['thumb_listing_margin'];?>px;
	width: <?php print $css['thumb_listing_width'];?>px;
	<?php addBoxShadow($css['thumb_listing_box_shadow']);?>
	-moz-border-radius: <?php print $css['thumb_listing_border_radius'];?>px;	border-radius: <?php print $css['thumb_listing_border_radius'];?>px; 
}

#listing-stacked .preview h2 a { color: #<?php print $css['thumb_listing_title'];?>; font-size: <?php print $css['thumb_listing_title_size'];?>px; 	font-family: '<?php print title_font_family;?>',Arial; text-decoration: none; } 
#listing-stacked .preview h2 a:hover { color: #<?php print $css['thumb_listing_title_hover'];?>; } 

#listing-stacked .preview div { padding: <?php print $css['thumb_listing_padding'] / 2;?>px; }

#listing-stacked  .preview  .prevInner { 
	padding: 16px;
}


#listing-stacked .preview .thumbnail { 
	border: 1px solid <?php print $css['thumb_listing_thumb_border'];?>;
	text-align: center;
	width: 100%;
	display: block;
}


#listing-stacked .preview .headline { padding: 4px; }
#listing-stacked .preview .sub {padding: 4px;  }
#listing-stacked .preview .previewtext { padding: 4px; }
#listing-stacked .preview .comments { padding: 4px; }
.stacked2text { padding: 16px !important; text-align: center; } 

.thumbnailactions { height: 20px;display: block;} 
.thumbnailactions .inner {  position: relative; float: left; text-align: center; width: 100%; } 

.favlogin { display: none; position: absolute;  margin: 0; text-align: left; z-index: 200; } 
.favlogin .inner {  background: #<?php print $css['thumb_listing_bg'];?>; border: solid 1px #<?php print $css['thumb_listing_border'];?>; font-size: 12px; text-shadow: none; padding: 8px; } 

.styledthumbs {
		<?php $rgb = hex2rgb($css['thumb_nails_bg']); ?>
<?php ie8rgba($css['thumb_nails_bg'],$css['thumb_nails_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['thumb_nails_opacity'];?>);
	border: <?php print $css['thumb_nails_border_size'];?>px <?php print $css['thumb_nails_border_style'];?> #<?php print $css['thumb_nails_border'];?>;
	color: #<?php print $css['thumb_nails_text'];?>;
	height: 1%;
	padding: <?php print $css['thumb_nails_padding'];?>px;
	margin: <?php print $css['thumb_nails_margin'];?>px;
	width: <?php print $css['thumb_nails_width'];?>px;
	height: <?php print $css['thumb_nails_height'];?>px;
	<?php addBoxShadow($css['thumb_nails_box_shadow']);?>
	-moz-border-radius: <?php print $css['thumb_nails_border_radius'];?>px;	border-radius: <?php print $css['thumb_nails_border_radius'];?>px; 
	float: left; 
	text-align: center; 
	display: block;
}
.styledthumbs a { color: #<?php print $css['thumb_nails_title'];?>; font-size: <?php print $css['thumb_nails_title_size'];?>px; 	font-family: '<?php print title_font_family;?>',Arial; } 
.styledthumbs a:hover { color: #<?php print $css['thumb_nails_title_hover'];?>; } 

.styledthumbs .thumbimage { position: relative; text-align: center; vertical-align: bottom; 	margin: auto; width: auto; height: <?php print $css['thumb_nails_thumb_height'];?>px; line-height: <?php print $css['thumb_nails_thumb_height'];?>px;
}
.styledthumbs .text { padding-top: <?php print $css['thumb_nails_padding'];?>px;padding-bottom: <?php print $css['thumb_nails_padding'];?>px; }
.styledthumbs .comments { padding: 4px; }
.styledthumbs .thumbnail { margin: auto;vertical-align: bottom; border: 1px solid #<?php print $css['thumb_nails_thumb_border'];?>;} 

#displayThumbnailPage {	
	position: relative; 
	text-align: left;
} 

#displayThumbnailPage .stackedthumbs {
	<?php $rgb = hex2rgb($css['thumb_nails_bg']); ?>
	<?php ie8rgba($css['thumb_nails_bg'],$css['thumb_nails_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['thumb_nails_opacity'];?>);
	border: <?php print $css['thumb_nails_border_size'];?>px <?php print $css['thumb_nails_border_style'];?> #<?php print $css['thumb_nails_border'];?>;
	color: #<?php print $css['thumb_nails_text'];?>;
	padding: <?php print $css['thumb_nails_padding'];?>px;
	margin: <?php print $css['thumb_nails_margin'];?>px;
	width: <?php print $css['thumb_nails_width'];?>px;
	<?php addBoxShadow($css['thumb_nails_box_shadow']);?>
	-moz-border-radius: <?php print $css['thumb_nails_border_radius'];?>px;	border-radius: <?php print $css['thumb_nails_border_radius'];?>px; 
	float: left; 
	text-align: center; 
	display: block;
}
#displayThumbnailPage .stackedthumbs a { color: #<?php print $css['thumb_nails_title'];?>; font-size: <?php print $css['thumb_nails_title_size'];?>px; 	font-family: '<?php print title_font_family;?>',Arial; } 
#displayThumbnailPage .stackedthumbs a:hover { color: #<?php print $css['thumb_nails_title_hover'];?>; } 

#displayThumbnailPage .stackedthumbs .thumbimage { 		
	text-align: center;
}
#displayThumbnailPage .stackedthumbs .text { padding-top: <?php print $css['thumb_nails_padding'];?>px;padding-bottom: <?php print $css['thumb_nails_padding'];?>px; }
#displayThumbnailPage .stackedthumbs .comments { padding: 4px; }
#displayThumbnailPage .stackedthumbs .thumbnail { 
	margin: auto;
	vertical-align: bottom; 
	border: 1px solid #<?php print $css['thumb_nails_thumb_border'];?>;
	text-align: center;
	width: 100%;
} 
.thumbfitcontainer { 	width: 100%; height: auto; } 
.thumbfitcontainerh { 	height 100%; width: auto; } 

.tpt { max-height:<?php print $css['thumb_nails_thumb_height'];?>px; width: auto; } 
.tls { max-width:<?php print $css['thumb_nails_thumb_width'];?>px; height: auto; } 




/* ################################################## END CATEOGRY LISTING STYLES  ##################################################### */

/* ################################################## PAGE DISPLAY STYLES  ##################################################### */

<?php $layouts = whileSQL("ms_category_layouts", "*", "WHERE layout_type='page' ");
while($layout = mysqli_fetch_array($layouts)) { 
	print $layout['layout_css'];
}
?>


/* ################################################## END PAGE DISPLAY STYLES  ##################################################### */


/* ################################################## CHECKOUT PAGE  ##################################################### */

#checkoutside { width: 20%; float: left; } 
#checkoutform { width: 80%; float: left; } 
#checkoutaddresswithship { width: 49%; float: left; } 
#checkoutaddressnoship { width: auto; } 
#shippingaddressside { float: right; width: 49%; } 

/* ################################################## PHOTO DISPLAY STYLES  ##################################################### */

#photoWithMinis { } 
#photoWithMinis .photominis { float: left; width: 15%; text-align: center; }
#photoWithMinis .photominis .pagemini {  border: 1px solid <?php print thumb_border;?>;}
#photoWithMinis .photoContainerOuter { width: 85%; text-align: center; float: left; } 
#photoWithMinis .photoContainerOuterOne {  text-align: center; float: left; width: 100%;  } 
#photoWithMinis .photoContainerOuter .photoContainer {  margin:auto ;  position: relative; text-align: center;   } 
#photoWithMinis .photoContainerOuter .pmphoto, #photoWithMinis .photoContainerOuterOne .pmphoto {  
	border: solid 1px #<?php print $css['photo_page_border_color'];?>; 
	} 




#photoScrollerContainer { text-align: center; } 

#nextPrevContain { 
width: 100%; margin: auto; position: relative;display: block;
}



/* ################################################## END PHOTO DISPLAY STYLES  ##################################################### */



/* ################################################## PAGE MENU NAVIGATION  ##################################################### */


#pageMenu {
	text-align: right;	
   margin: auto;
	padding: 4px;
}
#pageMenu .selectPage {
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_border;?>;
	padding: 4px 8px;
	margin: 2px;
	text-align: center;
	position: relative;
	white-space: nowrap;
	min-width: 16px;
	color: <?php print side_menu_link_color;?>;
}
#pageMenu .selectedPage {
	padding: 4px 8px;
	margin: 2px;
	text-align: center;
	position: relative;
	white-space: nowrap;
	min-width: 16px;
	font-weight: bold;
}
#pageMenu .unavailable {
	padding: 4px 8px;
	margin: 2px;
	text-align: center;
	position: relative;
	white-space: nowrap;
	min-width: 16px;
}

#pageMenu .totalResults {
	padding: 2px;
	margin: 2px;
	text-align: center;
	position: relative;
	white-space: nowrap;
	font-weight: bold;
}

/* ################################################## END PAGE MENU NAVIGATION  ##################################################### */

/* ################################################## PAGE NEXT PREVIOUS NAVIGATION  ##################################################### */

#pageNextPrevious { 
	text-align: center;
}
#pageNextPrevious a{ 

}
#pageNextPrevious h3{ 
	display: inline;
}

#pageNextPrevious img{ 
	border: 1px solid <?php print thumb_border;?>;
	margin: 2px 10px 2px 10px;
}

#pageNextPrevious .oldnew { 
	padding: 6px 10px 6px 10px;
}


#pageNextPrevious .older { 
	float: right;
	width: 50%;
	padding: <?php print boxes_padding;?>px;	
	text-align: right;
	padding: 6px 0 6px 0;
<?php addBoxShadow($css['boxes_box_shadow']);?>
-moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 
}
<?php if($css['boxes_transparent'] <=0) { ?>
#pageNextPrevious .older a { color: #<?php print $css['boxes_link'];?>; } 
#pageNextPrevious .older a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>

#pageNextPrevious .older .inside { 
	padding: 4px;
}

#pageNextPrevious .older img {
	float: right;
}


#pageNextPrevious .newer { 
	float: left;
	width: 50%;

	padding: <?php print boxes_padding;?>px;	
	text-align: left;
	padding: 6px 0 6px 0;
<?php addBoxShadow($css['boxes_box_shadow']);?>
-moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 
}
<?php if($css['boxes_transparent'] <=0) { ?>
#pageNextPrevious .newer a { color: #<?php print $css['boxes_link'];?>; } 
#pageNextPrevious .newer a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>

#pageNextPrevious .newer .inside { 
	padding: 4px;
}

#pageNextPrevious .newer img { 
float: left;
}


#pageNextPrevious h3{ 
text-align: center;
padding: 4px;
}



/* ################################################## END PAGE NEXT PREVIOUS NAVIGATION  ##################################################### */

/* ################################################## SUB PAGE LISTING  ##################################################### */
#subPages { } 

#subPages .subPage { 
	<?php if($css['boxes_transparent'] <=0) { ?>
		<?php $rgb = hex2rgb($css['boxes']); ?>
<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	border: solid 1px <?php print boxes_borders;?>;
	color: #<?php print $css['boxes_text'];?>;
	<?php } ?>
	height: 1%;
	margin: 0px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
<?php addBoxShadow($css['boxes_box_shadow']);?>
-moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 

}
<?php if($css['boxes_transparent'] <=0) { ?>
#subPages .subPage a { color: #<?php print $css['boxes_link'];?>; } 
#subPages .subPage a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>


#subPages .subPage .mini {
	float: left;
	margin: 0px 12px 0 0;
	border: 1px solid <?php print thumb_border;?>;
	display: block;
	}
#subPages .subPage .title { }

/* ################################################## END SUB PAGE LISTING  ##################################################### */

/* ################################################## FULL SCREEN PHOTO VIEWING ############################################## */
#viewPhotoContainer { z-index: 102; position: fixed; display: none;  width: 1000px; height: 90%; margin: auto; left: 5%; top: 60px; background: #FFFFFF; border: solid 1px #999999; border-radius: 8px; } 
#viewPhoto { position: relative; display: block; width: 100%; height: 100%;  } 
#viewPhotoOuter { width: 100%; height: 80%; text-align: center;  position: absolute; } 

#viewPhotoInfoContainer {width: 100%; top: 0; left: 0;  position: fixed; background: #000000; border: solid 1px 0  #000000; text-align: center; z-index: 103; 	-moz-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-goog-ms-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);} 



#viewPhotoInfo { padding: 4px; } 
.photoViewContainer {position: absolute; float: left;  margin: auto; width: 100%;  } 
<?php 
/* NAVIGATION ON SIDE OF SCREEN */
?>
#prevPhoto {  position: fixed; width: 5%; height: 32px; text-align: left; left: 0; top: 40%; opacity:0.5; cursor: pointer; display: none;} 
#nextPhoto {  position: fixed; width: 5%; height: 32px; text-align: right; right: 0; top: 40%; opacity:0.5;  cursor: pointer; display: none;} 
#prevPhoto:hover {  opacity:1; } 
#nextPhoto:hover  {  opacity:1; } 

#loadingMorePages {   display: none; text-align: center; padding: 16px;	} 
#loadingMore {   position: fixed; bottom: 10px;   width: 200px; padding: 12px; left: 50%; margin-left: -100px; z-index:1000; display: none; text-align: center; 	
background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
} 
#successMessage{ background: #5bbc62; border: solid 1px #41a148;  position: fixed; top: 10px;  color: #FFFFFF; width: 500px; padding: 10px; left: 50%; margin-left: -250px; z-index:1000; display: none; text-align: center;} 
#errorMessage{ background: #d22c1a; border: solid 1px #ac2314;  position: fixed; top: 10px;  color: #FFFFFF; width: 500px; padding: 10px; left: 50%; margin-left: -250px; z-index:1000; display: none; text-align: center;} 
#loadingPage { 
	display: none; 
	top: 0;
	left: 0; 
	position: fixed; 
	z-index: 3000; 
	text-align: center; 
	background: url('<?php print $setup['temp_url_folder'];?>/graphics/loading-page.gif')  no-repeat center center; 
	width: 100%; 
	height: 100%;
}



#photoBGContainer {
  width:100%;
  height:100%;
  min-height: 100%;
  margin:0;
  position: fixed;
  z-index: 20;
  left: 0;
  top: 0;
	display: none;
}
#photoBG  {
  width:100%;
  height:100%;
  min-height: 100%;
	
  opacity:<?php print $css['full_screen_opacity'];?>;
  position: absolute; 
  overflow: hidden;
  display: block;
 }

#photo-loading { 
	display: none; 
	top: 0;
	left: 0; 
	position: fixed; 
	z-index: 30; 
	text-align: center; 
	background: url(<?php print $setup['temp_url_folder'];?>'/sy-graphics/loading2.gif')  no-repeat center center; 
	width: 100%; 
	height: 100%;
}
#photo-main { 
	 width: 100%; height: 100%; top: 0; left: 0; position: fixed; z-index: 25; text-align: center;
}
#photo-insert { 
	display: table-cell; width: 80%; height: 100%; top: 0; left: 0; position: fixed; z-index: 25; text-align: center;
}
#photo-data { 
	width: 19%; height: 100%; top: 0; left: 80%; position: fixed; z-index: 25; text-align: left; background: #FFFFFF; padding: 8px; 
}

#photo-data { 
	overflow: scroll;
}
#photo-filter { 
float: right;
}
#photo-results { 
float: left;
}


/* ################################################## END FULL SCREEN PHOTO VIEWING ############################################## */

/* ##################################################### STORE STUFF ############################################################## */

.checkoutprogressdone {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	size: <?php print font_size;?>;
 padding: 6px; margin: 6px;
}
.checkoutprogress {  
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	size: <?php print font_size;?>;
 padding: 6px; margin: 6px;}


.checkout { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	padding: 6px;
	font-size: 17px;
	cursor: pointer;
	border-radius: 2px;
}

.checkoutcart { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	padding: 4px 6px;
	font-size: 15px;
	cursor: pointer;
	border-radius: 2px;
	text-decoration: none;
}

a.checkout, a.checkoutcart, a.checkout:visited, a.checkoutcart:visited { 	color: #<?php print $css['submit_text'];?>; 	text-decoration: none;
}
.checkout:hover, .checkoutcart:hover{  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	text-decoration: none;
}



ul.proofbutton { list-style: none; margin: 0; padding: 0;  clear: right;}
ul.proofbutton li { 
	padding: 4px; 
	display: inline-block;
	margin-right: 8px;
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
}
ul.proofbutton li:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	cursor: pointer; 
}




ul.productoption { list-style: none; margin: 0; padding: 0;  clear: right;}
ul.productoption li { 
	padding: 4px; 
	float: left; 
	margin-right: 8px;
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
}
ul.productoption li:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	cursor: pointer; 
}
.productoption  .on { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	-moz-box-shadow: 1px 1px 3px rgba(0, 0, 0, .5); 	box-shadow: 1px 1px 3px rgba(0, 0, 0, .5);
 }




ul.productoptionselect { list-style: none; margin: 0; padding: 0;  clear: right;}
ul.productoptionselect li { 
	padding: 4px; 
	float: left; 
	margin-right: 8px;
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
}
ul.productoptionselect li:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	cursor: pointer; 
}
.productoptionselect  .on { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	-moz-box-shadow: 1px 1px 3px rgba(0, 0, 0, .5); 	box-shadow: 1px 1px 3px rgba(0, 0, 0, .5);
 }

.productconfigs { padding: 4px; border-bottom: solid 1px #c4c4c4; } 
.productconfigsoptions { padding: 4px;   } 
.producttocart { display: block; } 
#addtocartloading { display: none; } 
#addtocart, .addtocart { 
	padding: 3px 8px; 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
 }
#addtocart:hover, .addtocart:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
	cursor: pointer; 
}

#addtocartdisabled { 
	padding: 3px 8px; 
	float: left; 
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
	size: <?php print font_size;?>;
}

.onsaleprice{
    text-decoration: line-through; float: left; margin-right: 8px; font-size: 17px;
}
.productprice { font-size: 21px; font-weight: bold;} 


/* ##################################################### END STORES STUFF ############################################################## */



/* ################################################## VIEW CART  CSS ##################################################### */


#viewcart .cartitem {
	<?php if($css['boxes_transparent'] <=0) { ?>
	<?php $rgb = hex2rgb($css['boxes']); ?>
	<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;
	<?php addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	<?php } ?>
	height: 1%;
	margin: 4px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
}
<?php if($css['boxes_transparent'] <=0) { ?>
#viewcart .cartitem a { color: #<?php print $css['boxes_link'];?>; } 
#viewcart .cartitem a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>


.preview .image {  }


#viewcart .cartitem .thumbnail { float: left;  width: 30%;  padding-right: 12px; text-align: center; } 
#viewcart .cartitem .thumbnail .thumb { border: 1px solid <?php print thumb_border;?>;  } 

#viewcart .cartitem .product { float: left; width: 50%; } 
#viewcart .cartitem .options { padding: 4px; } 
#viewcart .cartitem .qty { padding: 4px; } 
#viewcart .cartitem .name { padding: 4px; font-size: <?php print $css['boxes_title_size'];?>px; font-family: '<?php print title_font_family;?>', Arial;  } 
#viewcart .cartitem .topname { padding: 4px;  } 
#viewcart .cartitem .price { float: right;  width: 10%; text-align: right;  } 
#viewcart .cartitem .extprice { font-size: <?php print $css['boxes_title_size'];?>px; font-family: '<?php print title_font_family;?>', Arial; } 
#viewcart .cartitem .remove { padding: 4px;  } 

#orderLogin { width: 60%; margin: auto; } 



#orderitems .item {
	<?php if($css['boxes_transparent'] <=0) { ?>
		<?php $rgb = hex2rgb($css['boxes']); ?>
<?php ie8rgba($css['boxes'],$css['boxes_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['boxes_opacity'];?>);
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;
	<?php addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	<?php } ?>
	height: 1%;
	margin: 4px 4px  20px 0px;
	padding: <?php print boxes_padding;?>px;
}
<?php if($css['boxes_transparent'] <=0) { ?>
#orderitems .item a { color: #<?php print $css['boxes_link'];?>; } 
#orderitems .item a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>


#orderitems .item a.checkout, #orderitems .item a.checkoutcart, #orderitems .item a.checkout:visited, #orderitems .item a.checkoutcart:visited { 	color: #<?php print $css['submit_text'];?>; }

#listCategories {
   margin: auto;
	font-size: <?php print font_size;?>; 
	color: <?php print thumb_text;?>; 
	text-align: center;

}
#listCategories .categoryTitle { } 
#listCategories a,  #listCategories a:active { text-decoration: none; font-size: 15px; color: #000000;  }
#listCategories a:link, #listCategories a:visited {  text-decoration: none;   }
#listCategories a:hover {  text-decoration: underline; color: #009900;  } 

#listCategories .displaygallerya { display: inline; position: relative; } 
#listCategories .displaygalleryb { padding: 16px; margin-bottom: 20px; position: relative; bottom: 0; left: 8px;  display: inline-block; *display:inline; zoom: 1;  float: none; } 
#listCategories .displaygalleryc { display: block; position: relative; bottom: 0; text-align: center; } 
#listCategories .displaygalleryd { position: absolute; bottom: 0;text-align: center; margin: auto; display: block; } 
#listCategories .displaygallerynamecontainer { z-index: 20; postion: relative; }
#listCategories .displaygalleryname { padding: 4px; float: left; width: 100%; text-align: center; }



/* ################################################## END STORE CSS ##################################################### */


/* ############################################## THE FOLLOWING SECTION CONTROLS THE FORM STYLES ################################*/

#cookiewarning { 
position: fixed; bottom: 0; left: 0; 
 padding: 8px; z-index: 1000; width: 100%; text-align: center;
	background-color: <?php print inside_bg;?>;
	color:<?php print font_color;?>;
	font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
	border: solid 1px <?php print outside_bg;?>;

}

textarea, input, select { 
		<?php $rgb = hex2rgb($css['form_bg']); ?>
<?php ie8rgba($css['form_bg'],$css['form_opacity']) ?>

	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['form_opacity'];?>);
	color:<?php print form_color;?>;
	font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
	border: solid 1px <?php print form_border;?>;
	font-weight: normal;
	padding: 8px;
	font-size: <?php print font_size;?>;
	border-radius: 2px;
	}
.inputError {
		<?php $rgb = hex2rgb($css['form_bg']); ?>
<?php ie8rgba($css['form_bg'],$css['form_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['form_opacity'];?>);
		color:<?php print form_color;?>;
		font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
		border: solid 1px #890000;
		font-weight: normal;
		padding: 8px;
		font-size: <?php print font_size;?>;
	-moz-box-shadow: 1px 1px 6px rgba(100, 0, 0, .5); 	box-shadow: 1px 1px 6px rgba(100, 0, 0, .5);

}

button { padding: 8px; } 
.submit {
	<?php ie8rgba($css['submit_bg'],"1") ?>

	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	-moz-border-radius: 2px;
	border-radius: 2px;
	size: <?php print font_size;?>;
}

.submit:hover {
	<?php ie8rgba($css['submit_hover_background'],"1") ?>
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	cursor: pointer;
	-moz-border-radius: 2px;
	border-radius: 2px;
	size: <?php print font_size;?>;
}

.imagesubmit {
	background: none;
	border: solid 0px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	size: <?php print font_size;?>;
}

input.checkbox,.toselect ,input.radio{ 
	font-size: <?php print font_size;?>; 
	background-color: transparent;
	border: 0;
	margin: 0;
}
input.image { 
	background-color: transparent;
	border: none;
}
.ff-default-value {
		<?php $rgb = hex2rgb($css['form_bg']); ?>
<?php ie8rgba($css['form_bg'],$css['form_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['form_opacity'];?>);
	color:<?php print form_color;?>;
	font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
	border: solid 1px <?php print form_border;?>;
	font-weight: normal;
	font-style: italic;
}

.disabledinput { 
	background: #<?php print $css['submit_disabled_background'];?>;
	border: solid 1px #<?php print $css['submit_disabled_border'];?>;
	color: #<?php print $css['submit_disabled_text'];?>;
}
.field100 { width: 98%;  } 

.error { 
	background-color: #CF1919;
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #CF1919), color-stop(100%, #AF0909));
	background-image: -webkit-linear-gradient(#CF1919, #AF0909);
	background-image: -moz-linear-gradient(#CF1919, #AF0909);
	background-image: -o-linear-gradient(#CF1919, #AF0909);
	background-image: -ms-linear-gradient(#CF1919, #AF0909);
	background-image: linear-gradient(#CF1919, #AF0909);
	border: solid: 1px #8A2424; 
	color: #FFFFFF;  
	padding: 8px; 
} 
.error a { color: #FFFFFF; text-decoration: underline;  } 

.success { background: #4BAA48; border: solid: 1px #2F922C; color: #FFFFFF; 
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #4BAA48), color-stop(100%, #2F922C));
	background-image: -webkit-linear-gradient(#4BAA48, #2F922C);
	background-image: -moz-linear-gradient(#4BAA48, #2F922C);
	background-image: -o-linear-gradient(#4BAA48, #2F922C);
	background-image: -ms-linear-gradient(#4BAA48, #2F922C);
	background-image: linear-gradient(#4BAA48, #2F922C);
	color: #FFFFFF;  
	padding: 8px; 

 } 

/* ###################################################### END FORM STYLES ##############################################################*/

/* ###################################################### SHARE STYLE  ##############################################################*/

#socialShare { margin: 8px 0; }

#socialShare .item {
	margin: 2px;
	float: left;
}
/* ###################################################### END SHARE  STYLES ##############################################################*/



/* ######################################################### TOP STORE MENU ################################################ */


#viewcarttop {
	background-color: <?php print boxes;?>;
	<?php if($css['boxes_borders_where'] == "1") { ?>
	border-bottom: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } else { ?>
	border: <?php print $css['boxes_borders_size'];?>px <?php print $css['boxes_borders_style'];?> #<?php print $css['boxes_borders'];?>;
	<?php } ?>
	color: #<?php print $css['boxes_text'];?>;
	<?php // addBoxShadow($css['boxes_box_shadow']);?>
	 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;
	border-radius: <?php print $css['boxes_rounded'];?>px; 
	z-index: 801; 	
	width: 400px; float: right; display: none; position: fixed; left: 50%; margin-left: -200px; top:-2px;	
	-moz-box-shadow: 1px 1px 6px rgba(0, 0, 0, .5); 	box-shadow: 1px 1px 6px rgba(0, 0, 0, .5);
}
#viewcartinner {	
	padding: <?php print boxes_padding;?>px;
}

#viewcarttop h3 { color: #<?php print $css['boxes_link'];?>; } 
<?php if($css['boxes_transparent'] <=0) { ?>
#viewcartinner a { color: #<?php print $css['boxes_link'];?>; } 
#viewcartinner a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>




/* ######################################################## END TOP STORE MENU ############################################## */

/* ##################################################### TOOL TIPS ############################################################## */

.tooltip{
    position:fixed;
    z-index:999;
    color:#fff;
    background-color:#444444;
	border: solid 1px #343434;
    padding:1px;
	display: none;
	text-align: left;
	font-size: 13px;
	-moz-box-shadow: 1px 1px 5px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, .5);
	-goog-ms-box-shadow: 1px 1px 5px rgba(0, 0, 0, .5);
	box-shadow: 1px 1px 5px rgba(0, 0, 0, .5);	
	-moz-border-radius: 6px;
	max-width: 300px;
	text-shadow: none;
	font-weight: normal;
}

.tooltip div{
    margin:0;
    padding:4px;
    padding:2px 7px;
	display: block;
	white-space: normal; 
}
/* ##################################################### END TOOL TIPS ############################################################## */



/* ######################################################  SIDE MENU  #################################################################### */

#sideMenuContainer { 
	width: <?php print $css['side_menu_width'];?>%; 
	float: <?php print $css['side_menu_align'];?>; 
	color: #<?php print $css['side_main_font'];?>;
	padding-top: 4px;
}
#sideMenuContainer a,  #sideMenuContainer  a:active {   text-decoration: none; color: #<?php print $css['side_main_link'];?>;}
#sideMenuContainer a:link, #sideMenuContainer a:visited { text-decoration: none; color: #<?php print $css['side_main_link'];?>;   }
#sideMenuContainer  a:hover {  color: #<?php print $css['side_main_link_hover'];?>; text-decoration: underline;  } 
#sideMenuContainer .header { color: #<?php print $css['side_main_header'];?>; } 

#sideMenuContainer #sideMenu { 	
	<?php if($css['side_menu_align'] == "left") { ?>
	margin: 0px <?php print $css['inside_padding'];?>px  0px 0px;
	<?php } else { ?>
	margin: 0px 0px 0px <?php print $css['inside_padding'];?>px  ;
	<?php  } ?>
} 

#sideMenuContainer #sideMenu .label {
	font-family : '<?php print font_family;?>',Arial,Verdana,sans-serif; 
	font-size: <?php print font_size;?>; 
	font-size: <?php print $css['side_menu_label_size'];?>px; 
} 

#sideMenu ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
	<?php if($css['side_menu_transparent'] !== "1") { ?>
	background: #<?php print $css['side_main_bg'];?>;
	border: <?php print $css['side_main_border_size'];?>px <?php print $css['side_main_border_style'];?> #<?php print $css['side_main_border_color'];?>;
	border-radius:<?php print $css['side_main_border_radius'];?>px;
	color: #<?php print $css['side_main_link'];?>;
	padding: <?php print $css['side_main_bg_padding'];?>px;
	<?php } ?>
} 

#sideMenu ul li { 
	border-bottom:<?php print $css['side_menu_link_border_size'];?>px <?php print $css['side_menu_link_border_style'];?> #<?php print $css['side_menu_link_border_b'];?>;
	padding: 0 <?php print $css['side_main_padding'];?>px 0 <?php print $css['side_main_padding'];?>px;
	padding: <?php print $css['side_menu_line_height'];?>px 0;
	font-size: <?php print $css['side_menu_font_size'];?>px; 
}
#sideMenu ul li.last{ 
	border-bottom:0;
	padding: 0 <?php print $css['side_main_padding'];?>px 0 <?php print $css['side_main_padding'];?>px;
	padding: <?php print $css['side_menu_line_height'];?>px  0 0 0;
	font-size: <?php print $css['side_menu_font_size'];?>px; 
}

#sideMenu ul li .sub { padding-left: 20px; }  
#sideMenu ul li .on { font-weight: bold; }  
#sideMenu ul li .mini { border: 1px solid <?php print thumb_border;?>; margin-right: 8px; } 
#sideMenu ul li .date { padding: 2px 0; font-size: <?php print font_size;?>; } 

.sidebaritem { margin: 0 0 24px 0 } 
.sidebarlabel { margin: 0 0 8px 0 } 

<?php 
	$pw = (100 - $css['side_menu_width']); 
?>
#pageContentContainer {
	width: <?php print $pw;?>%;
	display: inline;
	float: <?php if($css['side_menu_align'] == "left") { print "right"; } if($css['side_menu_align'] == "right") { print "left"; } ?>;
	margin: 0 0 0 0;
	min-height: 100%;
	position: relative;
}


<?php 
if(($css['disable_side'] == "1")OR($_REQUEST['disable_side'] == "1")==true) { ?>
#pageContentContainer { width: 100%; margin: 0; } 
#sideMenuContainer { display: none; width: 0; padding: 0; margin: 0; } 
<?php } ?>

#som { size:  <?php print $css['side_main_padding'] ." - ".$css['page_width'];?> } 
#categoryList .item { padding: 4px 0; } 
#categoryList .item .on  { font-weight: bold;  } 



#linksMenuContainer, .blogSideMenuContainer { 
	margin: 0 0 0 0;

}

#linksMenu, #categoryList {
	background: #<?php print $css['side_main_bg'];?>;
	border: <?php print $css['side_main_border_size'];?>px <?php print $css['side_main_border_style'];?> #<?php print $css['side_main_border_color'];?>;
	border-radius:<?php print $css['side_main_border_radius'];?>px;
	color: #<?php print $css['side_main_link'];?>;
	padding: <?php print $css['side_main_bg_padding'];?>px;
}
#sideMenuAdditionalHtmlTop, #sideMenuAdditionalHtml { 
	<?php if($css['side_menu_align'] == "left") { ?>
	margin: 0px <?php print $css['inside_padding'];?>px  0px 0px;
	<?php } else { ?>
	margin: 0px 0px 0px <?php print $css['inside_padding'];?>px  ;
	<?php  } ?>
	color: #<?php print $css['side_main_font'];?>;
}


#linksMenu .menuHeader {
color:<?php print "#".$css['side_main_header'];?>;
padding: 6px;
}

#linksMenu .menuHeader .title {
font-size: 17px;
}

.blogSideMenuContainer .header { 
	<?php if($css['side_menu_align'] == "left") { ?>
	margin: 0px <?php print $css['inside_padding'];?>px  0px 0px;
	<?php } else { ?>
	margin: 0px 0px 0px <?php print $css['inside_padding'];?>px  ;
	<?php  } ?>
	color: #<?php print $css['side_main_font'];?>;
	padding: <?php print $css['side_main_bg_padding'];?>px;

}

#linksMenu .sideMenuItem, #categoryList .item {
	border-bottom:<?php print $css['side_menu_link_border_size'];?>px <?php print $css['side_menu_link_border_style'];?> #<?php print $css['side_menu_link_border_b'];?>;
	line-height: <?php print $css['side_menu_line_height'];?>px;
	padding: 0 <?php print $css['side_main_padding'];?>px 0 <?php print $css['side_main_padding'];?>px;
}

#linksMenu .sideMenuItem  .sub {
	text-indent:20px;
}

#linksMenu .sideMenuItem img {
	float: left;
	margin: 4px 6px 6px 0;
	border: 1px solid <?php print thumb_border;?>;
}
#linksMenu .sideMenuItem .title {
	
}

#linksMenu .menuHeader img {
	float: left;
	margin: 0 6px 6px 0;
	border: 1px solid <?php print thumb_border;?>;
}



/* ####################################################### END SIDE MENU AREA  ##########################################################*/

/* ####################################################### SLIDESHOW   ##########################################################*/

#thumbscroller { } 
.thumbscrollerpage {   background: #<?php print $css['scroller_bg'];?>;  border: solid 1px #<?php print $css['scroller_border'];?>; margin-top: 16px; padding: 4px; color: #<?php print $css['scroller_text'];?>; } 
.thumbscrollerfullscreen {   background: #<?php print $css['scroller_bg'];?>;  border: solid 1px #<?php print $css['scroller_border'];?>; margin-top: 16px; padding: 4px; position: fixed; bottom: 0; width: 100%; left: 0; z-index: 50;} 

#thumbscroller .fullscreen {} 

#scrollbar1 { width: 100%; clear: both; margin: 2px 0 0 0; bottom: 100px;}
#scrollbar1 .viewport { width: 100%; height: 105px; overflow: hidden; position: relative; }
#scrollbar1 .overview { list-style: none; position: absolute; left: 0; top: 0;  }
#scrollbar1 .thumb .end,
#scrollbar1 .thumb { background-color: #<?php print $css['scroller_handle'];?>; border-radius: 4px; height: 16px; cursor: pointer; overflow: hidden; position: absolute; left: 0; top: 2px; box-shadow: 0px 0px 7px 0px rgba(0,0,0,0.2) ,0px 0px 6px 6px rgba(255,255,255,0.3) inset; opacity: .6; }
#scrollbar1 .thumb:hover { opacity: 1; } 
#scrollbar1 .scrollbar{ background: #<?php print $css['scroller_handle_bg'];?>; position: relative; margin: 0 0 0px; clear: both; height: 20px;border-radius: 4px; opacity: .8; }
#scrollbar1 .scrollbar:hover{ background: #<?php print $css['scroller_handle_bg'];?>; position: relative; margin: 0 0 0px; clear: both; height: 20px;border-radius: 4px; opacity: 1; }

#scrollbar1 .track { width: 100%; height:15px; position: relative; }
#scrollbar1 .thumb .end{  overflow: hidden; height: 20px; width: 0px;}
#scrollbar1 .disable{ display: none; }
.noSelect { user-select: none; -o-user-select: none; -moz-user-select: none; -khtml-user-select: none; -webkit-user-select: none; }



.scrollthumbnail {	border: 1px solid <?php print thumb_border;?>; opacity: .6; } 
.scrollthumbnail:hover { opacity: 1; } 
.thumbon {	border: 1px solid <?php print thumb_border;?>; opacity: 1; } 

.photofull {
	margin: auto;
	border: solid <?php print $css['photo_border_size'];?>px #<?php print $css['photo_border_color'];?>; 
	padding: <?php print $css['photo_padding'];?>px; 
	background: #<?php print $css['photo_background'];?>;
<?php addBoxShadow($css['full_screen_photo_box_shadow']);?>
	 position: relative; 
	 margin: auto; 

 }

#ssbackground { 
  width:100%;
  height:100%;
  min-height: 100%;
	background-color: #<?php print $css['full_screen_background'];?>;
  opacity:<?php print $css['full_screen_opacity'];?>;
  overflow: hidden;
  display: block;
  position: fixed;
  top: 0;
  left: 0;
	display: none;
  z-index: 20;
}
#thumbscrollerclick { cursor: pointer; } 

#photomenu ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
} 
#photomenu ul li { 
	display: inline-block;

	margin-left: <?php print $css['sm_spacing'];?>px;
}

.thumbnailactions { text-align: center; } 
.thumbnailactions ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
	text-align: center;
} 
.thumbnailactions ul li { 
	display: inline;
	margin-left: 2px;
}

#sscontainerloading { background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif') center center no-repeat; width: 100%; height: 100%; position: absolute; z-index: 50; display: none;} 

.sscontainerfull { position: fixed; width: 100%; height: 100%; left:0; top: 0; z-index:30; } 



.controlsbg { }
#fullscreen { 	<?php $rgb = hex2rgb($css['caption_background']); ?>
<?php ie8rgba($css['caption_background'],$css['caption_opacity']) ?>

	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['caption_opacity'];?>);
	padding: 6px;
	margin: 4px;
	color: #<?php print $css['caption_text'];?>;
	text-align: center;
	border-radius: 2px; 
}
.controlsbg a { color: #<?php print $css['caption_text'];?>; } 
.photocontainer { position: relative; }
.photocaptioncontainer { 
	z-index: 1602;
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	display: none;
}
.photocaptioncontainer .inner{ 
	<?php $rgb = hex2rgb($css['caption_background']); ?>
	<?php ie8rgba($css['caption_background'],$css['caption_opacity']) ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['caption_opacity'];?>);
	padding: <?php print $css['caption_padding'];?>px;
	color: #<?php print $css['caption_text'];?>;
	text-align: <?php print $css['caption_align'];?>;
}
.photocaptioncontainer .inner h3 { 
	font-family: '<?php print title_font_family;?>',Arial;
	font-size: <?php print $css['h3_size'];?>px;
	text-shadow: none;
	color: #<?php print $css['caption_text'];?>;
	display: block;
}
  .photo { 
	border: solid <?php print $css['photo_page_border_size'];?>px #<?php print $css['photo_page_border_color'];?>; 
	padding: <?php print $css['photo_page_padding'];?>px; 
	background: #<?php print $css['photo_page_background'];?>;
<?php addBoxShadow($css['photos_box_shadow']);?>
}
  .photoHidden { 
  display: none;
	border: solid <?php print $css['photo_page_border_size'];?>px #<?php print $css['photo_page_border_color'];?>; 
	padding: <?php print $css['photo_page_padding'];?>px; 
	background: #<?php print $css['photo_page_background'];?>;
<?php addBoxShadow($css['photos_box_shadow']);?>
}

.photocaptionbelow { padding: 4px 0 24px; text-align: left; } 

















#ssContainer { text-align: center; background: #890000;}
.SSphotoViewContainer { position: absolute;   margin: auto; width: 100%;  } 
#ssViewPhotoOuter { position: relative; text-align: center;   display: block;  }
#ssViewPhotoOuterFixed { position: fixed; text-align: center; width: 100%;  display: block; }

.SSphotoView { 

	margin: auto; 
	display: none;
	border: solid <?php print $css['photo_page_border_size'];?>px #<?php print $css['photo_page_border_color'];?>; 
	padding: <?php print $css['photo_page_padding'];?>px; 
	background: #<?php print $css['photo_page_background'];?>;
<?php addBoxShadow($css['photos_box_shadow']);?>
}

#ssControls { 
	padding: 6px;
}
#ssControls ul { 
	list-style-type: none;
	padding: 0px;
	margin: 0px; 
	text-align: center;
} 
#ssControls ul  li { margin: 4px; display: inline; }






.loadMore { 
display: block;  padding: 12px; text-align: center; 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	cursor: pointer;
	font-size: 17px;
}

.zoomCur { 
	cursor: url('<?php print $setup['temp_url_folder'];?>/sy-graphics/magnify.cur'), pointer; 
}

.zoomClose { 
	cursor: pointer; 
}


#video {  } 

#passwordSite { 
background:<?php print inside_bg;?>; 
padding: 20px;
margin-top: 30px;

}

#slideShowDContainer { position: relative; background: url('<?php print $setup['temp_url_folder'];?>/sy-graphics/loading.gif') center center no-repeat;  }
#slideShowDContainer .slideShowDPic { position: absolute; z-index: 2; text-align: center;}

#slideShowDContainer .slideShowDPic img { margin: auto; } 

.viewfaq { 
	background-color: <?php print boxes;?>;
	padding: 8px;	
	text-align: left;
	 -moz-border-radius: 2px;	border-radius: 2px; 
	 color: #000000;
}
.viewfaqcontainer { display: none; } 
.faqlink { font-size: 15px; } 
.viewfaqtext { padding: 20px;  background: #FFFFFF; } 
.viewmedia { text-align: center; font-weight: bold; font-size: 17px;} 

li { margin: 0;}






.blogPhotoView { 
	position: relative; 
	z-index: 3000;
	float: left;
}

.photoNextPrevious { 


}


.blogPhotoCaptionOnPhoto { 
	color: #<?php print $css['caption_text'];?>;
	background:#<?php print $css['caption_background'];?>; 
	opacity: <?php print $css['caption_opacity'];?>;   
	filter:alpha(opacity=<?php print ($css['caption_opacity'] * 100);?>);
	z-index: 1602;
	position: absolute;  bottom: 0px;   z-index: 9; text-align: left; margin: auto; left: 50%; display: none;

}



.blogPhotoCaptionFS { 
	color: #<?php print $css['caption_text'];?>;
		width: <?php print $css['caption_width'];?>%; position: absolute; <?php if($css['caption_placement'] == "1") { ?>top:<?php print $css['caption_top'];?>px;   <?php } else { ?>bottom:<?php print $css['caption_bottom'];?>px; <?php } ?>margin: 0 100px 0 0;  left: <?php print $css['caption_left'];?>%; z-index: 10; 
		background:#<?php print $css['caption_background'];?>; 
		padding: 4px; 
		display: block; -moz-border-radius: <?php print $css['caption_rounded'];?>px;	
		border-radius: <?php print $css['caption_rounded'];?>px; 
		opacity: <?php print $css['caption_opacity'];?>;   
		filter:alpha(opacity=<?php print ($css['caption_opacity'] * 100);?>);
		overflow: hidden; 
		border: solid 1px  #<?php print $css['caption_border'];?>;
		<?php if($css['caption_shading_size'] > 0) {  ?>

	-moz-box-shadow: 0px 1px <?php print $css['caption_shading_size'];?>px #<?php print $css['caption_shading'];?>;
	-webkit-box-shadow: 0px 1px <?php print $css['caption_shading_size'];?>px #<?php print $css['caption_shading'];?>;
	-goog-ms-box-shadow: 0px 1px <?php print $css['caption_shading_size'];?>px #<?php print $css['caption_shading'];?>;
	box-shadow: 0px 1px <?php print $css['caption_shading_size'];?>px #<?php print $css['caption_shading'];?>;
	<?php } ?>
	position: fixed;  text-align: left;display: none;
  overflow: hidden; 
	z-index: 1602;



}


.blogPhotoCaptionText { 
	filter:alpha(opacity=100); opacity: 1; padding: 4px;  z-index: 50; position: relative;  padding: 16px;	
}

<?php 
/* ##########################################################################
FULL SCREEN SLIDESHOW CSS
############################################################################ */
?>
#overlay1, #overlay2 { z-index: 1600; position: fixed; width: 100%; height: 100%;}
#fullScreenContainer { 
<?php if($_REQUEST['ipad']==true) { ?>

<?php } else { ?>

	display: none;
<?php } ?>
}

#logo { 
	position: fixed; z-index: 5000;
<?php if($css['header_transparent'] != "1") { ?>background: #<?php print $css['header_bg'];?>; border: solid 1px #<?php print $css['header_border_color'];?>; <?php } ?> color: #<?php print $css['header_font_color'];?>; padding: <?php print $css['header_padding'];?>px;
<?php if($css['header_location'] == "topleft") { print "top: 0; left: 0;"; } ?> 
<?php if($css['header_location'] == "bottomleft") { print "bottom: 0; left: 0;"; } ?>
<?php if($css['header_location'] == "bottomright") { print "bottom: 0; right: 5%;"; } ?>
<?php if($css['header_location'] == "topright") { print "top: 0; right: 5%;"; } ?>
<?php if($css['header_location'] == "textcontent") { print "display: none;"; } ?> 
<?php if($css['header_text_shadow_on'] == "1") { print "text-shadow: ".$css['header_text_shadow'].";"; } ?>
}
#fsTextHeader { 
<?php if($css['header_transparent'] != "1") { ?>background: #<?php print $css['header_bg'];?>; border: solid 1px #<?php print $css['header_border_color'];?>; <?php } ?> color: #<?php print $css['header_font_color'];?>; padding: <?php print $css['header_padding'];?>px;
<?php if($css['header_location'] !== "textcontent") { print "display: none;"; } ?> 
<?php if($css['header_text_shadow_on'] == "1") { print "text-shadow: ".$css['header_text_shadow'].";"; } ?>
}


#hideText {padding: 6px;   text-align: center; cursor: pointer;} 


#comContainer, #fsHelp { 
	margin: auto;
	width: 700px;
	height: 520px;
	background-color: <?php print inside_bg;?>;
	border: solid 1px <?php print boxes_borders;?>;
	color: <?php print font_color;?>;
	border: solid 2px  #999999;
	padding: 12px;
	position: fixed;
	top: 3%;
	left: 50%;
	margin-left: -350px;
	display: none;
	-moz-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	-goog-ms-box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	box-shadow: 1px 1px 10px rgba(0, 0, 0, .5);
	z-index: 5500;
	-moz-border-radius: 6px;
	border-radius: 6px;

}



#thumbnailsContainer { 
	position: fixed;  
	display: none; 
	overflow: hidden;
	bottom: 0;
}
#frameContainer { 
	width: 100%; 
	height: 100%; 
	position: fixed; 
	left: 0; 
	top: 0;
	display: block; 
	margin: auto; 
	overflow: hidden; 
	z-index: 1599;
	background: <?php print inside_bg;?>;
}




#showThumbnailMenu { 
	background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/siteicons/<?php print $css['icons_folder'];?>/tn-show-thumbs.png') no-repeat; 
	width: 36px; 
	height: 36px; 
	border: none;
	cursor: pointer;
	display: block;
	float: left;
}
#closeThumbnailMenu { 
	background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/siteicons/<?php print $css['icons_folder'];?>/tn-close-thumbs.png') no-repeat; 
	width: 36px; 
	height: 36px; 
	border: none;
	cursor: pointer;
	display: none;
	float: left;
}

.stopSlideshow { 
	background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/siteicons/<?php print $css['icons_folder'];?>/tn-pause.png') no-repeat; 
	width: 36px; 
	height: 36px; 
	border: none;
	cursor: pointer;
	display: block;
	float: left;
}
.startSlideshow { 
	background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/siteicons/<?php print $css['icons_folder'];?>/tn-play.png') no-repeat; 
	width: 36px; 
	height: 36px; 
	border: none;
	cursor: pointer;
	display: block;
	float: left;
}
.loadingbarsmall { 
	 width: 100%; float: left; display: block;
}

.loadingbar { 
	position: absolute; width: 95%; float: left; bottom: 0; margin-bottom: -6px;
}
.loadingbar .inner { 
	float: left; 
	width: 100%;
}
.loadingbar .inner .bar { 
	border: 0; width: 1px; height: 10px;
}

.displayPhotoContainer { 
	text-align: center; margin: auto; position: relative;
}

.displayPhotoContainerOuter { 
	width: 100%;margin: auto;position: absolute;
}



#loadingPage { 
	background: transparent url('<?php print $setup['temp_url_folder'];?>/sy-graphics/loading-page.gif') center center no-repeat; width: 100%; height: 100%; position: fixed; z-index: 1600; display: none;
}
#fsBottomMainMenu {
	list-style:none;
	margin:auto;
	padding:0;
	position:relative;
	text-align:top;
	z-index: 1602;
}
#fsBottomMainMenu a,  #fsBottomMainMenu a:active {   text-decoration: none; color: <?php print menu_link_color;?>;}
#fsBottomMainMenu a:link, #fsBottomMainMenu a:visited { text-decoration: none; color: <?php print menu_link_color;?>;   }
#fsBottomMainMenu a:hover {  color: <?php print menu_link_hover;?>; text-decoration: underline;  } 

#fsBottomMainMenu #mainmenu li a,  #fsBottomMainMenu #mainmenu li a:active {   text-decoration: none; color: <?php print menu_link_color;?>; <?php if($css['menu_text_shadow_on'] == "1") { print "text-shadow: ".$css['menu_text_shadow'].";"; } ?>
	<?php if($css['menu_upper'] == 1) { ?>
	text-transform:uppercase;
	<?php } ?>

}
#fsBottomMainMenu #mainmenu li a:link, #fsBottomMainMenu #mainmenu li a:visited { text-decoration: none; color: <?php print menu_link_color;?>;   }
#fsBottomMainMenu #mainmenu li a:hover {  color: <?php print menu_link_hover;?>; text-decoration: underline;  } 


#fsBottomMainMenu #mainmenu{
	margin: 0;
	padding: 0;
	z-index: 1602;
}

#fsBottomMainMenu #mainmenu li {
	list-style: none;
	float: left;
	margin: 0 1px 0 0;
	padding: 4px 8px 0 20px;
	z-index: 1602;
}


#fsBottomMainMenu #photosmenu { 
	display: block;
	visibility: visible;
	position: absolute;
	z-index: 1602;
}

#fsBottomMainMenu #mainmenu div {	
<?php	if($css['menu_location'] =="bottom") { ?>
bottom: 0;
	border-top:1px solid <?php print menu_border_a;?>;
	border-left:1px solid <?php print menu_border_a;?>;
	border-right:1px solid <?php print menu_border_a;?>;
	margin: 0 0 10px -10px;

<?php if($css['menu_shadow_size'] > 0) {  ?>
	-moz-box-shadow: 0px -2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-webkit-box-shadow: 0px -2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-goog-ms-box-shadow: 0px -2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	box-shadow: 0px -2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
<?php } ?>

<?php } ?>


<?php	if($css['menu_location'] =="top") { ?>

	border-bottom:1px solid <?php print menu_border_a;?>;
	border-left:1px solid <?php print menu_border_a;?>;
	border-right:1px solid <?php print menu_border_a;?>;
	margin: 0 0 10px -10px;

<?php if($css['menu_shadow_size'] > 0) {  ?>
	-moz-box-shadow: 0px 2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-webkit-box-shadow: 0px 2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	-goog-ms-box-shadow: 0px 2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
	box-shadow: 0px 2px <?php print $css['menu_shadow_size'];?>px #<?php print $css['menu_shadow_color'];?>;
<?php } ?>

<?php } ?>



	z-index: 1602;
	position: absolute;
	visibility: hidden;
	padding: 0;
	border: 0;
	color: <?php print font_color;?>;
	background: <?php print menu_color;?>;
	display: block;
	-moz-border-radius: <?php print $css['menu_rounded_corners'];?>px;
	border-radius: <?php print $css['menu_rounded_corners'];?>px;
}

#fsBottomMainMenu #mainmenu span {	
	display: block;
	z-index: 1602;
}
#fsBottomMainMenu #mainmenu  .photos {	
	display: block;
	z-index: 1602;
}

#fsBottomMainMenu #mainmenu .thumbnailsContainer { 
	padding-top: 6px; float: left; 
	z-index: 1602;
}

#fsBottomMainMenu #mainmenu div a {	
	text-align: left;
	text-decoration: none;
	z-index: 1602;
}

#fsBottomMainMenu #mainmenu div a:hover {	
	background: <?php print menu_color_hover;?>;
	color: <?php print menu_link_hover;?>;
	z-index: 1602;
}

#slideshowCount { 
	position: fixed;
	width: 100%;
<?php	if($css['menu_location'] =="top") { ?>
	bottom: 2px;
<?php } ?>
<?php	if(($css['menu_location'] =="bottom")OR(empty($css['menu_location']))==true) { ?>
	top: 5px;
<?php } ?>
	text-align: right;
	left: 0;
	height: 23px;
	z-index: 1600;
	color: #FFFFFF;
text-shadow: #000000 1px 1px 1px; 
}

#facebookLikeBoxFS { 
position: fixed; left: 0; top: 250px; z-index: 8; margin-left: -292px;
}
#likeBoxInner { 
float: left; width: 292px;background: <?php print inside_bg;?>;
}

#facebookTabInner{
float: left; position: relative;  display: block; width: 60px; height: 289px;
}

#facebookTabInnerTab{
background: #3B5998; position: absolute; bottom: 0; padding: 8px; cursor: pointer; color: #FFFFFF; border-top-right-radius: 5px; -moz-border-radius-topRight: 5px;border-bottom-right-radius: 5px; -moz-border-radius-bottomRight: 5px;
}
<?php 
/* ##########################################################################
END - FULL SCREEN SLIDESHOW CSS
############################################################################ */
?>

#sideShare { 
position: fixed; right: 0; top: 250px; z-index: 8000;
background: #890000;
}



<?php if(empty($_REQUEST['admin_edit'])) { ?>

#main_container { 
	width: <?php print page_width;?>;
	max-width: <?php print $css['page_width_max'];?>px;

	margin: auto;
	margin-top: <?php print $css['inside_margin_top'];?>px;
	<?php if($css['inside_bg_transparent'] <=0) { ?>
		<?php $rgb = hex2rgb($css['inside_bg']); ?>
<?php ie8rgba($css['inside_bg'],$css['inside_opacity']) ?>

		background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $css['inside_opacity'];?>);
	border: <?php print $css['inside_bg_border_size'];?>px <?php print $css['inside_bg_border_style'];?> #<?php print $css['inside_bg_border'];?>;
	<?php } ?>
<?php addBoxShadow($css['content_box_shadow']);?>

}

#mobileContainer {  }
#mobileContainer .inner { padding: 10px; }









#contentUnderMenu { 
	padding: <?php print $css['inside_padding'];?>px; 
}
#mainmenu  .photos { width: 250px; } 




.toolTip { /* This is the hook that the jQuery script will use */
	position: relative; /* This contains the .toolTipWrapper div that is absolutely positioned  */
}

.toolTipWrapper { /* The wrapper holds its insides together */
	padding: 4px;;
	position: absolute; /* Absolute will make the tooltip float above other content in the page */
	top: -30px;
	display: none; /* It has to be displayed none so that the jQuery fadein and fadeout functions will work */
	border: solid 1px <?php print menu_border_b;?>;
	background:<?php print menu_color;?>;
	color:<?php print menu_link_color;?>;
	font-weight: bold;
	font-size: 9pt; /* A font size is needed to maintain consistancy */
	z-index: 10000;
	white-space:nowrap;
	-moz-border-radius: 2px;
	border-radius: 2px;
}






/* MAIN CONTENT AREA - The following controls the main content on the pages */

.pageContent, .pc { 
	padding: 4px;
}
.pc h3 {
	padding-bottom: 30px;
}
.pageContentBold { 
	font-weight: bold;
	padding: 4px;
}

/* errorMessage - This is when an error is displayed */
.errorMessage {
	background-color: #f99999;
        border: solid 1px #890000;
        font-weight: normal;
        color: #490000;
		padding: 4px;
}
/* successMessage - This is when a success message is displayed */
.successMessage {
	background-color: #7AC494;
        border: solid 1px #469160;
        font-weight: normal;
        color: #000000;
		padding: 4px;
}

/* END  MAIN CONTENT AREA */




/* THE FOLLOWING CONTROLS THE HOME PAGE */

#homePageLabels {
	font-size: <?php print font_size;?>;
	color: <?php print page_labels;?>;
}
#homePageMore {
	width: 100%;
	text-align: right;
}
#homePageMore a,  #homePageMore a:active {  text-decoration: none;  }
#homePageMore a:link, #homePageMore a:visited {  text-decoration: none;     }
#homePageMore a:hover {  text-decoration: underline;    } 

#newsSeparator {
	width: 95%;
	margin: auto;
	line-height: 10px;
}
#newsHome {

}
	
/* The #newsHeadlines is the font or link wiht news headlines */
#newsHeadlines { color: <?php print page_title;?>; font-size: 17px; }
#newsHeadlines a,  #newsHeadlines a:active {  text-decoration: none; color: <?php print page_title;?>;}
#newsHeadlines a:link, #newsHeadlines a:visited {   text-decoration: none; color: <?php print page_title;?>;   }
#newsHeadlines a:hover {   color: <?php print page_title_hover;?>; text-decoration: underline;  } 

.newsDate { color: <?php print page_dates;?>;  font-size: 11px;  } 

#subMenu { color: <?php print font_color;?>; font-size: 10px; padding: 4px 0 0 0; }
#subMenu a,  #subMenu a:active {  text-decoration: none; }
#subMenu a:link, #subMenu a:visited {  ; text-decoration: none; }
#subMenu a:hover {   text-decoration: underline;  } 






/* PHOTO GALLERY STYLES */


#photo-preview {  position: absolute; 
	background-color: <?php print inside_bg;?>;
	border: <?php print $css['inside_bg_border_size'];?>px <?php print $css['inside_bg_border_style'];?> #<?php print $css['inside_bg_border'];?>;
	z-index: 200; box-shadow: 1px 1px 10px rgba(0, 0, 0, .5); display: none; 
 } 
#package-photo-preview {  position: fixed; 
	background-color: <?php print inside_bg;?>;
	border: <?php print $css['inside_bg_border_size'];?>px <?php print $css['inside_bg_border_style'];?> #<?php print $css['inside_bg_border'];?>;
	box-shadow: 1px 1px 10px rgba(0, 0, 0, .5); display: none; z-index: 2000; 
 } 


#stackedThumbnails { 
list-style-type: none;
position: relative; 
margin: 10px 0;
text-align: center;
}

#stackedThumbnails .thumb {
<?php if($_REQUEST['ipad']!=="1") { ?>
	display: none;
	<?php } ?>
	margin: 0;

}

#stackedThumbnails .styled {
	<?php if($css['boxes_transparent'] <=0) { ?>
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_borders;?>;
	color: #<?php print $css['boxes_text'];?>;
	<?php } ?>
<?php addBoxShadow($css['boxes_box_shadow']);?>
-moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 

}

<?php if($css['boxes_transparent'] <=0) { ?>
#stackedThumbnails .styled a { color: #<?php print $css['boxes_link'];?>; } 
#stackedThumbnails .styled a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>
#stackedThumbnails .thumb .inner{
 padding: 12px;
}

#photoGallery {
   text-align: center;
   margin: auto;
}

#listGalleries {
   text-align: center;
   margin: auto;
}




#photoGallery .thumb {
	padding: 4px;
	margin: 4px;
	float: left;
	overflow: hidden;
	text-align: center;
	z-index: 0;
}
#photoGallery .styled { 
	<?php if($css['boxes_transparent'] <=0) { ?>
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_borders;?>;
	color: #<?php print $css['boxes_text'];?>;
	<?php } ?>
<?php if($_REQUEST['ipad']!=="1") { ?>
	<?php } ?>
	margin: 0;
<?php addBoxShadow($css['boxes_box_shadow']);?>
-moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 
}

 #photoGallery .thumbContainer { 
	<?php if($css['boxes_transparent'] <=0) { ?>
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_borders;?>;
	color: #<?php print $css['boxes_text'];?>;
	<?php } ?>
<?php if($_REQUEST['ipad']!=="1") { ?>
	<?php } ?>
	margin: 0;
	<?php if($css['boxes_shading_size'] > 0) {  ?>
	-moz-box-shadow: 0px 1px <?php print $css['boxes_shading_size'];?>px #<?php print $css['boxes_shading_color'];?>;
	-webkit-box-shadow: 0px 1px <?php print $css['boxes_shading_size'];?>px #<?php print $css['boxes_shading_color'];?>;
	-goog-ms-box-shadow: 0px 1px <?php print $css['boxes_shading_size'];?>px #<?php print $css['boxes_shading_color'];?>;
	box-shadow: 0px 1px <?php print $css['boxes_shading_size'];?>px #<?php print $css['boxes_shading_color'];?>;
<?php } ?>
 -moz-border-radius: <?php print $css['boxes_rounded'];?>px;	border-radius: <?php print $css['boxes_rounded'];?>px; 
	padding: 8px;
	margin: 8px;
}

<?php if($css['boxes_transparent'] <=0) { ?>
#photoGallery .styled a { color: #<?php print $css['boxes_link'];?>; } 
#photoGallery .styled a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
 #photoGallery .thumbContainer a { color: #<?php print $css['boxes_link'];?>; } 
 #photoGallery .thumbContainer a:hover { color: #<?php print $css['boxes_link_hover'];?>; } 
<?php } ?>

#photoGallery .thumbContainer td { text-align: center; } 

#photoGallery .thumbnail { border: solid 1px <?php print thumb_border;?>; margin: auto;  } 
#photoGallery .thumbContainer .thumbname { white-space: nowrap; overflow: hidden; padding: 4px;} 

#photoGallery .contain { 
	margin:0;
	position: relative;
	vertical-align: middle;
	padding: 10px;
}






.photoAlbumOuter { 

	margin: auto; display: inline;
 }

.photoAlbum{
	display: none;
	margin: 8px;
	text-align: center;
	color: <?php print font_color;?>;
}
.photoAlbum a,  .photoAlbum a:active { text-decoration: none; }
.photoAlbum a:link, .photoAlbum a:visited {  text-decoration: none;   }
.photoAlbum a:hover {  text-decoration: underline;  } 

.photoAlbum .thumbnails {
	border: 1px solid <?php print thumb_border;?>;

}
.photoAlbum a {
	font-size: 17px;
}





#photoShowBGContainer {
  width:100%;
  height:100%;
  min-height: 100%;
  margin:0;
  position: fixed;
  z-index: 2000;
  left: 0;
  top: 0;
}
#photoShowBG
  {
  width:100%;
  height:100%;
  min-height: 100%;
  background-color:<?php print outside_bg;?>;
  /* for IE */
  filter:alpha(opacity=90);
  /* CSS3 standard */
  opacity:0.9;
  position: absolute; 
  overflow: hidden;
  display: block;
  }

#photoShowImage {
	color:<?php print font_color;?>;
	width: 100%;
	position: absolute;
	display: block;
	z-index: 21;
	left: 0;
	top: 35px;
	margin: auto;
	text-align: center;
  }


  .mainImage { 
	margin: auto;
	border: solid <?php print $css['photo_border_size'];?>px #<?php print $css['photo_border_color'];?>; 
	padding: <?php print $css['photo_padding'];?>px; 
	background: #<?php print $css['photo_background'];?>;
<?php if($css['photo_shadow_size'] > 0) {  ?>
	-moz-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	-webkit-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	-goog-ms-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
<?php } ?>  
}

.fsPhoto{ 
	margin: auto;
	border: solid <?php print $css['photo_border_size'];?>px #<?php print $css['photo_border_color'];?>; 
	padding: <?php print $css['photo_padding'];?>px; 
	background: #<?php print $css['photo_background'];?>;
<?php if($css['photo_shadow_size'] > 0) {  ?>
	-moz-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	-webkit-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	-goog-ms-box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
	box-shadow: 0px 0px <?php print $css['photo_shadow_size'];?>px #<?php print $css['photo_shadow_color'];?>;
<?php } ?>  
}
 
  
  .theMainImage {
	margin: auto;
  }

.thumbnails {
	border: 1px solid <?php print thumb_border;?>;
	padding: 0px;
	margin: 0px;
	z-index: 0;
}

.homePageThumb  {
	padding:0;
	margin: 4px 8px 4px 4px;
	float: left;
	text-align: center;
	border: 1px solid <?php print thumb_border;?>;
}

/* END PHOTO GALLREY STYLES */
#commentsContainer { 

}
#standardComments {
	margin: auto;
	display: block;
	padding: 4px;
}

#leaveCommentForm {
	margin: auto;
	padding: 4px;
	width: 65%;
	float: left;
}
#listStandardComments {
	float: right;
	width: 33%;

}

.showComment { 
	padding: 4px;
	border-bottom: solid 1px <?php print boxes_borders;?>;
}


#comments{
	padding: 4px;
	margin: 0px;
	overflow-y: scroll;
	height: 200px;
}
#photoCartLogin{
	<?php if($css['boxes_transparent'] <=0) { ?>
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_borders;?>;
	<?php } ?>
	padding: 4px;
	margin: 0px;
	color: <?php print font_color;?>;
}


#facebookLikeBox {
   margin: auto;
}




.footerSpacer {
	height: 50px;
}
#footer {
padding: <?php print $css['inside_padding'];?>px 0; 
}
#footer .the-icons { 
	font-size: 32px;
}
#footerSpacer { height: 30px; } 

#footer img {
border: 0;
margin: 0 4px 0 4px;

}


#footer a,  #footer a:active { text-decoration: none; color: <?php print link_color;?>;}
#footer a:link, #footer a:visited {  text-decoration: none; color: <?php print link_color;?>;   }
#footer a:hover {  color: <?php print link_color_hover;?>; text-decoration: underline;  } 


<?php
if(!empty($css['add_css'])) {
	print $css['add_css'];
}?>

<?php } ?>

<?php 
if(!empty($_REQUEST['admin_edit'])) { ?>
	body{ 
		background:url(''); 
		padding: 10px;
	}
<?php } ?>

<?php if($_REQUEST['ipad']==true) { ?>
body, html { background-image: none; min-width: 1024px; } 
<?php } ?>

.faqs{
	background-color: <?php print boxes;?>;
	border: solid 1px <?php print boxes_borders;?>;
	padding: 4px;
	margin: 0px;
	color: <?php print font_color;?>;
}

.specialMessage {
	background-color: #EDE5AB;
    border: solid 1px #BAB066;
    font-weight: normal;
    color: #000000;
	padding: 4px;
	text-align: center;
	clear: both;
}
.specialMessage a {
color: #890000;
font-weight: bold;
}

.photoShowBGContainer {
  width:100%;
  height:100%;
  min-height: 100%;
  margin:0;
  position: fixed;
  z-index: 2000;
  left: 0;
  top: 0;
}
#photoShowBGSolid
  {
  width:200%;
  height:200%;
  min-height: 100%;
  background-color:#000000;
  position: absolute; 
  display: block;
  }
 .photoShowBGImageContainer {
  width:100%;
  height:100%;
  min-height: 100%;
  margin:0;
  position: fixed;
  z-index: 2200;
  left: 0;
  top: 0;
}

.photoBG2 {
	position: fixed;   
	display: block; 
	width: 100%; 
	height: 100%; 
	min-height: 100%;
	left: 0;
	top: 0;
}
.mainImageDiv {
	 z-index: 23; width: 100%;   left: 0; top: 0; position: absolute;
}
.mainImageDivInner { 
	margin: 30px auto 0 auto; text-align: center;
}


.imageCaption { 
	position: absolute; bottom: 4px; left: 4px; background-color:#FFFFFF;z-index: 50;  filter:alpha(opacity=40); opacity:0.4; 	text-align: left; margin: auto; 
}


.imageCaptionText { 
	filter:alpha(opacity=100); opacity: 0.9; padding: 4px;  color: #000000;  
}

 
 
 .photoMessageContainer {
	margin: 200px auto 0 auto; z-index: 2999; position: fixed; width: 100%; left: 0; display: none;
}

.fb_ltr{
    position: absolute !important;
	left: 0;
    z-index: 1;
}



.photoMessage { width: 200px; padding: 10px; background-color:#FFFFFF; z-index: 50;  filter:alpha(opacity=80); opacity:0.8; 	text-align: center; margin: auto; color: #000000; } 

/* Comemnts box text color */
div.comment_body div.composer div.connected {color:<?php print font_color;?>;}

#listComments  {    }

.connect_widget_not_connected_text {color:<?php print font_color;?>;}
div.comment_body div.composer div.connected div.UIImageBlock_Content {color<?php print font_color;?>;}
div.comment_body div.composer div.connected span.namelink a {color:<?php print link_color;?>;}
div.comment_body div.post_area div.connected label {color:<?php print font_color;?>;}
div.comment_body div.show_connected a.editsettings {color:<?php print link_color;?>;}
div.comment_body div.wallkit_postcontent h4 {color:<?php print font_color;?>e;}
div.comment_body div.wallkit_postcontent h4 a {color:<?php print link_color;?>;}
div.comment_body div.wallkit_postcontent h4 span.wall_time {color:<?php print font_color;?>;}
div.comment_body div.wallkit_postcontent div {color:<?php print font_color;?>;}
div.comment_body div.wallkit_postcontent div a {color:<?php print link_color;?>;}
div.comment_body div.wallkit_actionset a {color:<?php print font_color;?> !important;}
div.comment_body div.wallkit_subtitle div.post_counter {color:<?php print font_color;?>;}
div.comment_body div.wallkit_subtitle div.pager a {color:<?php print link_color;?>;}
div.comment_body div.wallkit_subtitle div.pager a:hover {color:<?php print font_color;?>;}
div.comment_body div.connect_area div.or {color:<?php print font_color;?>;}
div.comment_body div.connect_area div.connect_button_text {color:<?php print font_color;?>;}
div.comment_body div.wall_captcha {color:<?php print font_color;?>;}
div.comment_body div.wall_captcha h3 {color:<?php print font_color;?>;}
div.comment_body div.wall_captcha a {color:<?php print link_color;?>;}
div.comment_body div.wall_captcha label {color:<?php print font_color;?>;}

<?php if($_REQUEST['site_type'] == "mobile") {?>
#page-wrapper { left: auto; margin: auto; float: none;
<?php if($_SESSION['previewMobile'] == 1) { ?>width: 480px; <?php } else { ?>width: 100%; <?php } ?>
<?php } ?>


