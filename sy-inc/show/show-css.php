<?php header("Content-type: text/css"); ?>
<?php
require "../../sy-config.php";
session_start();
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");
$_REQUEST['sid'] = sql_safe($_REQUEST['sid']);
$show = doSQL("ms_show", "*", "WHERE MD5(show_id)='".$_REQUEST['sid']."' ");
$site_setup = doSQL("ms_settings", "*", "");
if(!empty($_SESSION['previewTheme'])) { 
	$css_id = $_SESSION['previewTheme'];
} else {
	if($page_theme > 0) {
		$css_id = $page_theme;	
	} else {
		$css_id = $site_setup['css'];
	}
} 


$css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$css_id."'");
$fullscreen_width = $show['fullscreen_width'];
$feature_type = $show['feature_type']; // featured photos slideshow
// $feature_type = 'getfeature'; // featured pages
$catphotoratio = $show['catphotoratio'];
$mainphotoratio = $show['mainphotoratio'];
$catsubrecent = $show['catsubrecent'];
if($feature_type == "getfeatureslide") { 
	$initialopacity = 1;
	$hoveropacity = 1;
} else { 
	$initialopacity = $show['initialopacity'];
	$hoveropacity = $show['hoveropacity'];
}
$main_full_screen = 1;
$main_link_spacing = $dshow['main_link_spacing'];
$main_photo_bg_color = "#".$dshow['main_photo_bg_color'];
$slide_speed = $show['slide_speed'];
$mainfeaturetimer = $show['mainfeaturetimer'];
$change_effect = $show['change_effect'];
$loading_text = $dshow['loading_text'];
if($show['enable_side'] <=0) { 
	 $fullscreen_width = 100;
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
?>
body { }
#headerAndMenu { z-index: 20; }
<?php // Added display and height to hide . z-index needed for everything else ?>

#scrolltoviewphotos { display: none; position: fixed; bottom: 0px; left: 0%; background: #<?php print $dshow['show_photos_bg'];?>; border-top: solid 1px #<?php print $dshow['show_photos_border'];?>; width: 100%; padding: 8px; z-index: 500; text-align: center; font-size: <?php print $dshow['show_photos_text_size'];?>px; color: #<?php print  $dshow['show_photos_text'];?>; text-shadow: 1px 1px 1px #<?php print  $dshow['show_photos_text_shadow'];?>; } 

#scrolltoviewphotos .the-icons { font-size: <?php print  $dshow['show_photos_text_size'];?>px; color: #<?php print $dshow['show_photos_text'];?>; text-shadow: 1px 1px 1px #<?php print  $dshow['show_photos_text_shadow'];?>; cursor: default; } 

#clfthumbscontainer { position: relative; } 
#clfthumbs  { display: none; width: 100%; left: 0%; position: absolute; z-index: 201; padding: 0px; } 


#maincol { width: 80%; height: 100%; display: inline-block;} 
#maincol .inner { padding: 16px; } 
#maincol a, #maincol a:visited {  } 
.subpagecats { padding: 8px; border-top: solid 1px #ECECEC; font-size: 19px; white-space:nowrap; overflow: hidden; clear: right;} 
.subpagecats a, .subpagecats a:visited { color: #000000; } 
.subpagecats a:hover { color: #000089; } 
.subpagecats img { max-width: 25px; max-height: 25px; height: 25px; width: 25px;  } 


.firstitem { border: solid 1px #e4e4e4; } 
.firstitem .headline { background: #242424; color: #FFFFFF; font-size: 21px; font-weight: bold; padding: 8px; } 
.firstitem .firstphoto {  } 
.catphotocontainer { width:33.3%; float: left; } 
.catphotocontainerinner { overflow: hidden; position: relative; } 
.catphoto { margin: 0px 8px 8px 8px ; width: 100%; height: 100%;  background-color: #000000; overflow: hidden;} 
.catphoto img { } 

.homephotos { opacity: <?php print $initialopacity;?>;} 

#mainfeature {   position: relative; display: block;  clear: both;  overflow: hidden; } 
.homefeaturerecent .inner { padding: 16px; } 
.mainfeaturenavcontainer { padding: 4px; text-align: right; } 

<?php if($main_full_screen == 1) { ?>
#mainfeature {   position: absolute; display: block;  clear: both;  overflow: hidden; width: <?php print $fullscreen_width;?>%; height: 100%; left:0%; top: 0; z-index: 1;  background-color: <?php print $main_photo_bg_color;?>; } 
.homefeaturerecent { width: <?php print 100 - $fullscreen_width;?>%; float: right; z-index: 10; position: absolute; right: 0; top: 0px; background: <?php print "#".$dshow['sm_bg'];?>; } 
#homefeature { background: <?php print "#".$dshow['sm_bg'];?>;  } 

.homefeaturerecent .inner { padding: 0px; } 

<?php if($dshow['nav_placement'] == "tl") { ?>
.mainfeaturenavcontainer { padding: 4px; position: absolute; top: 8px; left: 0; z-index:10; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none; } 
<?php } ?>
<?php if($dshow['nav_placement'] == "bl") { ?>
.mainfeaturenavcontainer { padding: 4px;  position: absolute;  left: 0; z-index: 10; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>

<?php if($dshow['nav_placement'] == "tr") { ?>
.mainfeaturenavcontainer { padding: 4px; text-align: right; position: absolute; top: 8px; right: <?php print 100 - $fullscreen_width;?>%; z-index: 10; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>

<?php if($dshow['nav_placement'] == "br") { ?>
.mainfeaturenavcontainer { padding: 4px; text-align: right; height: auto; position: absolute;  right: <?php print (100 - $fullscreen_width);?>%; z-index: 10;background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>
.mainfeaturenavcontainer .the-icons { color: <?php print "#".$dshow['nav_color'];?> !important; text-shadow: none; } 
<?php } ?>
.mslide { float: left; top: 0; position: absolute;}
.maincontainer { position: absolute; left: 0px; width: 100%;  overflow: hidden; } 
.maincontainerinner { overflow: hidden; position: relative; } 
.mainphoto {  width: 100%; height: 100%;  overflow: hidden; } 
.mainphotobg {width: 100%; height: 100%; overflow: hidden;opacity: .5; } 
.mainphotobggrid {  background: url('<?php print $setup['temp_url_folder'];?>/sy-graphics/overlay2.png') repeat; opacity: .6; width: 100%; height: 100%; position: absolute; } 





<?php if($dshow['logo_placement'] == "tl") { ?>
#mainfeaturelogo { top: 0px; left: 0px;  position: absolute; z-index: 101;} 
<?php } ?>
<?php if($dshow['logo_placement'] == "bl") { ?>
#mainfeaturelogo { bottom: 8px; left: 8px;  position: absolute; z-index: 101;} 
<?php } ?>

<?php if($dshow['logo_placement'] == "tr") { ?>
#mainfeaturelogo { top: 8px; right: 8px;  position: absolute; z-index: 101; text-align: right;} 
<?php } ?>

<?php if($dshow['logo_placement'] == "br") { ?>
#mainfeaturelogo { bottom: 8px; right: 8px;  position: absolute; z-index: 101; text-align: right;} 
<?php } ?>


#pagefeattitletext { position: absolute; z-index: 101; padding: 0px 16px;} 
.featmenu { font-size: 50px; padding: 0px !important; text-shadow: 0px 0px 2px #000000; color: #FFFFFF ; font-family: '<?php print $css['css_title_font_family_main'];?>'; } 
.featmenu img { max-height: 40px; } 
#featmenumain { display: none; position: absolute; top: 0; left: 80px; z-index: 7000; } 
.featmenulinks  { list-style: none; margin: 0; padding: 0; overflow: hidden; width: 300px; } 
.featmenulinks li { list-style: none; } 






.sidefeattitle h2 { color: #<?php print $dshow['sm_title_color'];?>; } 
.sidefeattext { color: #<?php print $dshow['sm_text_color'];?>; } 

.featmenulinks li  a, .featmenulinks li a:visited  {  font-size: <?php print $dshow['sm_font_size'];?>px;  background: <?php print "#".$dshow['sm_bg'];?>; color: <?php print "#".$dshow['sm_font_color'];?>; padding: <?php print $dshow['sm_padding'];?>px; border-bottom: solid 1px <?php print "#".$dshow['sm_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['sm_bt'];?>; float: left; width: 100%;} 
.featmenulinks li a img { float: left; margin-right: 16px; } 
.featmenulinks li a:hover { background: <?php print "#".$dshow['smh_bg'];?>; color: <?php print "#".$dshow['smh_font_color'];?>; border-bottom: solid 1px <?php print "#".$dshow['smh_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['smh_bt'];?>;} 


<?php $rgb = hex2rgb($dshow['behind_text_color']); ?>

.mainphotoheadlinetext { position: absolute; padding: 16px; width: <?php print $dshow['text_title_width'];?>%;  background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $dshow['behind_text_opacity'];?>);}

.mainphotoheadline {  font-size: <?php print $dshow['title_size'];?>px; color: <?php print "#".$dshow['title_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['title_textshadow'];?>;  font-family: <?php print $css['css_title_font_family_main'];?>;padding: 4px; } 
.mainphotopreviewtext, .featpagetext {font-size: <?php print $dshow['font_size'];?>px; color: <?php print "#".$dshow['font_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['font_textshadow'];?>;   padding: 0px 16px; } 
/*.mainphotoheadlinetext { position: absolute; bottom: 0px; left: 0x; padding: 16px; width: 100%;  background-color: rgba(0,0,0,.5);  box-shadow: 0px 0px 48px rgba(0,0,0,.4) inset;} */


.featpagetitle, .featcatname { font-size: <?php print $dshow['title_size'];?>px; color: <?php print "#".$dshow['title_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['title_textshadow'];?>; font-family: <?php print $css['css_title_font_family_main'];?>; padding: 0px 16px; } 


#loadingstuff { position: fixed; width: 100%; height: 100%; top: 0; left: 0; background: <?php print "#".$dshow['loading_bg_color'];?>; color: <?php print "#".$dshow['loading_font_color'];?>; z-index: 6000; text-align: center; padding-top: 20%; font-size: 30px } 
#loadingmoreitems { background: #f4f4f4; border: solid 1px #c4c4c4; padding: 16px; text-align:center;  display: none; } 

	



.smfindphotos { 
	background: #<?php print $css['submit_bg'];?>;
	border: solid 1px #<?php print $css['submit_border'];?>;
	color: #<?php print $css['submit_text'];?>;
	padding: 4px 6px;
	margin: <?php print $dshow['sm_padding'];?>px;
	font-size: 15px;
	cursor: pointer;
	border-radius: 2px;
	text-decoration: none;
	text-align: center;
}

a.smfindphotos, a.smfindphotos:visited  { 	color: #<?php print $css['submit_text'];?>; 	text-decoration: none; }
.smfindphotos:hover {  
	background: #<?php print $css['submit_hover_background'];?>;
	border: solid 1px #<?php print $css['submit_hover_border'];?>;
	color: #<?php print $css['submit_hover_text'];?>;
	text-decoration: none;
}



.mainlinktabs li { position: absolute; <?php print $dshow['main_menu_placement'];?>: 0;}
.mainlinktabs li a { list-style: none; position: absolute; <?php print $dshow['main_menu_placement'];?>: 0; padding: 8px; font-size: <?php print $dshow['main_menu_font_size'];?>px; z-index: 101; opacity: .7; background: <?php print "#".$dshow['main_menu_bg'];?>; color: <?php print "#".$dshow['main_menu_font'];?>; display: block; white-space:nowrap; } 
.mainlinktabs li a:hover { opacity: 1; }  

.featside  { list-style: none; margin: 0; padding: 0; overflow: hidden;} 
.featside li { list-style: none; padding-right: <?php print $dshow['sm_padding'];?>px;} 



.featsidea a  {
	<?php $rgb = hex2rgb($dshow['sm_bg']); ?>
	background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,1);
	border-bottom: 1px solid  #<?php print $dshow['sm_bb'];?>;
	border-top: 1px solid  #<?php print $dshow['sm_bt'];?>;
	color: #<?php print $dshow['sm_font_color'];?>;
	height: 1%;
	padding: <?php print $dshow['sm_padding'];?>px;
	float: left; width: 100%;
	text-decoration: none;
}
.featsidea h3 { font-size: <?php print $dshow['sm_font_size'];?>px; display: inline; color: #<?php print $dshow['side_page_title_color'];?>;} 
.featside .the-icons { text-shadow: none; } 


.featsidea  img { float: left; margin-right: 16px; } 
.featsidehover a  { text-decoration: none; background: <?php print "#".$dshow['smh_bg'];?>; border-bottom: solid 1px <?php print "#".$dshow['smh_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['smh_bt'];?>; text-decoration: none; color: #<?php print $dshow['smh_font_color'];?>; } 
.featsidehover a h3  { color: <?php print "#".$dshow['side_page_title_hover'];?>; } 


.sidepadding { padding: <?php print $dshow['sm_padding'];?>px;  }



.container img {
    display: block;
}

.portrait img {
    width: 100%;
}
.landscape img {
    height: 100%;
}
