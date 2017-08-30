<?php header("Content-type: text/css"); ?>
<?php
require "../../sy-config.php";
session_start();
require $setup['path']."/".$setup['inc_folder']."/functions.php"; 
$dbcon = dbConnect($setup);
$dshow = doSQL("ms_show", "*", "WHERE default_feat='1' ");
$fullscreen_width = $dshow['fullscreen_width'];
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
	// $fullscreen_width = 100;
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
#leftcolfeat { width: 20%; float: left; background: #000000; height: 100%; border: 0;} 
#leftcolmenu ul  { list-style: none; margin: 0; padding: 0; overflow: hidden;  } 
#leftcolmenu ul li { list-style: none; } 


#leftcolmenu ul li  a, #leftcolmenu ul li a:visited  {  font-size: <?php print $dshow['sm_font_size'];?>px;  background: <?php print "#".$dshow['sm_bg'];?>; color: <?php print "#".$dshow['sm_font_color'];?>; padding: <?php print $dshow['sm_padding'];?>px; border-bottom: solid 1px <?php print "#".$dshow['sm_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['sm_bt'];?>; float: left; width: 100%;} 
#leftcolmenu ul li a img { float: left; margin-right: 16px; } 
#leftcolmenu ul li a:hover { background: <?php print "#".$dshow['smh_bg'];?>; color: <?php print "#".$dshow['smh_font_color'];?>; border-bottom: solid 1px <?php print "#".$dshow['smh_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['smh_bt'];?>;} 

#maincol { width: 60%; float: left;} 
#maincol .inner { padding: 16px; } 
#maincol a, #maincol a:visited {  } 
.subpagecats { padding: 8px; border-top: solid 1px #ECECEC; font-size: 19px; white-space:nowrap; overflow: hidden; clear: right;} 
.subpagecats a, .subpagecats a:visited { color: #000000; } 
.subpagecats a:hover { color: #000089; } 
.subpagecats img { max-width: 25px; max-height: 25px; height: 25px; width: 25px;  } 


.firstitem { border: solid 1px #e4e4e4; } 
.firstitem .headline { background: #242424; color: #FFFFFF; font-size: 21px; font-weight: bold; padding: 8px; } 
.firstitem .firstphoto {  } 
.catphotocontainer { width:50%; float: left; } 
.catphotocontainerinner { overflow: hidden; position: relative; } 
.catphoto { margin: 0px 8px 8px 8px ; width: 100%; height: 100%;  background-color: #000000; overflow: hidden;} 
.catphoto img { } 

.homephotos { opacity: <?php print $initialopacity;?>;} 

#mainfeature {   position: relative; display: block;  clear: both;  overflow: hidden; } 
.homefeaturerecent { width: <?php print (100 - $fullscreen_width);?>%; float: right;  } 
.homefeaturerecent .inner { padding: 16px; } 
.mainfeaturenavcontainer { padding: 4px; text-align: right; } 

<?php if($main_full_screen == 1) { ?>
#mainfeature {   position: fixed; display: block;  clear: both;  overflow: hidden; width: 60%; height: 100%; left:20%; top: 0; z-index: 100;  background-color: <?php print $main_photo_bg_color;?>; } 
#headerAndMenu, #footer { display: none; } 
.homefeaturerecent { width: <?php print (100 - $fullscreen_width);?>%; float: right; z-index: 1000; position: absolute; right: 0; top: 0px; background: <?php print "#".$dshow['sm_bg'];?>; height: 100%;} 
#homefeature { background: <?php print "#".$dshow['sm_bg'];?>;  } 
#main_container { margin-top: 0px; padding: 0px; max-width: 100%; width: 100%;} 
#contentUnderMenu, #page-wrapper, #page-wrapper-inner { padding: 0px; max-width: 100%; width: 100%; } 
.homefeaturerecent .inner { padding: 0px; } 

<?php if($dshow['nav_placement'] == "tl") { ?>
.mainfeaturenavcontainer { padding: 4px; position: fixed; top: 8px; left: 0; z-index: 101; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none; } 
<?php } ?>
<?php if($dshow['nav_placement'] == "bl") { ?>
.mainfeaturenavcontainer { padding: 4px;  position: fixed; bottom: 8px; left: 0; z-index: 101; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>

<?php if($dshow['nav_placement'] == "tr") { ?>
.mainfeaturenavcontainer { padding: 4px; text-align: right; position: fixed; top: 8px; right: <?php print (100 - $fullscreen_width);?>%; z-index: 101; background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>

<?php if($dshow['nav_placement'] == "br") { ?>
.mainfeaturenavcontainer { padding: 4px; text-align: right; position: fixed; bottom: 8px; right: <?php print (100 - $fullscreen_width);?>%; z-index: 101;background: <?php print "#".$dshow['nav_bg'];?>; color: <?php print "#".$dshow['nav_color'];?>; font-size: <?php print $dshow['nav_font_size'];?>px; text-shadow: none;  } 
<?php } ?>
.mainfeaturenavcontainer .the-icons { color: <?php print "#".$dshow['nav_color'];?> !important; text-shadow: none; } 
<?php } ?>
.mslide { float: left; top: 0; position: absolute;}
.maincontainer { position: absolute; left: 0px; width: 100%;  overflow: hidden; } 
.maincontainerinner { overflow: hidden; position: relative; } 
.mainphoto {  width: 100%; height: 100%;  background-color: <?php print $main_photo_bg_color;?>; overflow: hidden; } 
<?php if($dshow['logo_placement'] == "tl") { ?>
#mainfeaturelogo { top: 8px; left: 8px;  position: absolute; z-index: 101;} 
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


#pagefeattitletext { position: absolute; z-index: 101;} 
.featmenu { font-size: 50px; padding: 0px !important; text-shadow: 0px 0px 2px #000000; color: #FFFFFF ; font-family: '<?php print $css['css_title_font_family_main'];?>'; } 
.featmenu img { max-height: 40px; } 
#featmenumain { display: none; position: absolute; top: 0; left: 80px; z-index: 7000; } 
.featmenulinks  { list-style: none; margin: 0; padding: 0; overflow: hidden; width: 300px; } 
.featmenulinks li { list-style: none; } 

.featmenulinks li  a, .featmenulinks li a:visited  {  font-size: <?php print $dshow['sm_font_size'];?>px;  background: <?php print "#".$dshow['sm_bg'];?>; color: <?php print "#".$dshow['sm_font_color'];?>; padding: <?php print $dshow['sm_padding'];?>px; border-bottom: solid 1px <?php print "#".$dshow['sm_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['sm_bt'];?>; float: left; width: 100%;} 
.featmenulinks li a img { float: left; margin-right: 16px; } 
.featmenulinks li a:hover { background: <?php print "#".$dshow['smh_bg'];?>; color: <?php print "#".$dshow['smh_font_color'];?>; border-bottom: solid 1px <?php print "#".$dshow['smh_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['smh_bt'];?>;} 


<?php $rgb = hex2rgb($dshow['behind_text_color']); ?>

.mainphotoheadlinetext { position: absolute; padding: 16px; width: <?php print $dshow['text_title_width'];?>%;  background-color: rgba(<?php print $rgb[0];?>,<?php print $rgb[1];?>,<?php print $rgb[2];?>,<?php print $dshow['behind_text_opacity'];?>);}

.mainphotoheadline {  font-size: <?php print $dshow['title_size'];?>px; color: <?php print "#".$dshow['title_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['title_textshadow'];?>;  font-family: <?php print $css['css_title_font_family_main'];?>;padding: 4px; } 
.mainphotopreviewtext, .featpagetext {font-size: <?php print $dshow['font_size'];?>px; color: <?php print "#".$dshow['font_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['font_textshadow'];?>;   padding: 4px; } 
/*.mainphotoheadlinetext { position: absolute; bottom: 0px; left: 0x; padding: 16px; width: 100%;  background-color: rgba(0,0,0,.5);  box-shadow: 0px 0px 48px rgba(0,0,0,.4) inset;} */


.featpagetitle, .featcatname { font-size: <?php print $dshow['title_size'];?>px; color: <?php print "#".$dshow['title_color'];?>; text-shadow: 1px 1px 1px <?php print "#".$dshow['title_textshadow'];?>; font-family: <?php print $css['css_title_font_family_main'];?>; padding: 4px;} 


#loadingstuff { position: fixed; width: 100%; height: 100%; top: 0; left: 0; background: <?php print "#".$dshow['loading_bg_color'];?>; color: <?php print "#".$dshow['loading_font_color'];?>; z-index: 6000; text-align: center; padding-top: 20%; font-size: 30px } 
#loadingmoreitems { background: #f4f4f4; border: solid 1px #c4c4c4; padding: 16px; text-align:center;  display: none; } 

	






.mainbreaking { background: #990000; color: #FFFFFF; position: absolute; top: 0; padding: 8px; width: 100%; z-index: 10; } 


.mainlinktabs li { position: absolute; <?php print $dshow['main_menu_placement'];?>: 0;}
.mainlinktabs li a { list-style: none; position: absolute; <?php print $dshow['main_menu_placement'];?>: 0; padding: 8px; font-size: <?php print $dshow['main_menu_font_size'];?>px; z-index: 101; opacity: .7; background: <?php print "#".$dshow['main_menu_bg'];?>; color: <?php print "#".$dshow['main_menu_font'];?>; display: block; white-space:nowrap; } 
.mainlinktabs li a:hover { opacity: 1; }  

.featside  { list-style: none; margin: 0; padding: 0; overflow: hidden;} 
.featside li { list-style: none; } 

.featside li  a, .featside li a:visited  { font-size: <?php print $dshow['sm_font_size'];?>px;  background: <?php print "#".$dshow['sm_bg'];?>; color: <?php print "#".$dshow['sm_font_color'];?>; padding: <?php print $dshow['sm_padding'];?>px; border-bottom: solid 1px <?php print "#".$dshow['sm_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['sm_bt'];?>; float: left; width: 100%;} 
.featside li a img { float: left; margin-right: 16px; } 
.featside li a:hover { background: <?php print "#".$dshow['smh_bg'];?>; color: <?php print "#".$dshow['smh_font_color'];?>; border-bottom: solid 1px <?php print "#".$dshow['smh_bb'];?>;  border-top: solid 1px <?php print "#".$dshow['smh_bt'];?>;} 
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
