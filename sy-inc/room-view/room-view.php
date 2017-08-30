<?php 
/*
require("../../sy-config.php");
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');
ob_start(); 
require $setup['path']."/".$setup['inc_folder']."/functions.php";
require $setup['path']."/".$setup['inc_folder']."/store/store_functions.php";
require $setup['path']."/".$setup['inc_folder']."/photos_functions.php";
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
$store = doSQL("ms_store_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$lang = doSQL("ms_language", "*", "");
foreach($lang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
$storelang = doSQL("ms_store_language", "*", " ");
foreach($storelang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}
if($site_setup['include_vat'] == "1") { 
	$def = doSQL("ms_countries", "*", "WHERE def='1' ");
	$site_setup['include_vat_rate'] = $def['vat'];
}

foreach($_REQUEST AS $id => $value) {
	if(!empty($value)) { 
		if(!is_array($value)) { 
			$_REQUEST[$id] = sql_safe("".$_REQUEST[$id]."");
			$_REQUEST[$id] = addslashes(stripslashes(stripslashes(strip_tags(trim($value)))));
		}
	}
}
*/
$wset = doSQL("ms_wall_settings", "*", "  ");
$wdlang = doSQL("ms_wall_language", "*", " ");
foreach($wdlang AS $id => $val) {
	if(!is_numeric($id)) {
		define($id,$val);
	}
}

?>
<style>
#shopmenucontainer { display: none; } 
#headerAndMenu { display: none; } 
#clientGalleries { display: none; } 
#galleryheader { display: none; } 
#mobilemenu { display: none; } 
#footer { display: none; } 
#gallerymobileheader { display: none; } 

</style>
<?php $css = doSQL("ms_css LEFT JOIN ms_css2 ON ms_css.css_id=ms_css2.parent_css_id", "*", "WHERE css_id='".$site_setup['css']."' "); ?>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/room-view/wall-designer.js?<?php print MD5($site_setup['sytist_version']);?>"></script>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/room-view/canvasresize.js?<?php print MD5($site_setup['sytist_version']);?>"></script>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/room-view/exif.js?<?php print MD5($site_setup['sytist_version']);?>"></script>
<script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/room-view/clipboard.js?<?php print MD5($site_setup['sytist_version']);?>"></script>
<script src="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php tempFolder(); ?>/sy-inc/js/crop/jquery.Jcrop.css" type="text/css" />
<!-- <script language="javascript"  type="text/javascript" src="<?php print $setup['temp_url_folder'];?>/sy-inc/room-view/touch.js"></script> -->
<input type="hidden" name="w" id="w" value="" size="3"> 
<input type="hidden" name="h" id="h" value="" size="3"> 
<script>
<?php if((empty($_REQUEST['rw'])) && (empty($_REQUEST['frameadjust'])) == true) { ?>
<?php if(!isset($_COOKIE['wdinfoshow'])) { ?>
$(document).ready(function(){
	setTimeout(function(){
	showhelpbubble()
	},1000);

});
<?php } ?>
<?php } ?>
    
$(document).ready(function(){
	$('body').on('keydown', handleKeys);
	$("#roombackgroundphoto").imagesLoaded(function() {
		$("#roomviewer").fadeIn(200, function() {
			$("#roomcontain .roomphotocontainer").css({"cursor":"move"});
			$("#roomcontain .roomphotocontainer").each(function(i){
				if($(this).attr("id") !== "roomphotocontainer-0000") { 
					$(this).show();
					sizeroomphoto();
					$(this).draggable({grid: [ 4, 4 ], 
					  start: function() {
						$("#roomcontain").attr("data-center-photo","0");
					  }
					});
				}
			});
		});
	  });
	

	selectingphoto();
	$("#fileToUploadButton").click(function(){
	   $("#fileToUpload").click();
	});
	 // For the room photo upload
	 $('input[name=fileToUpload]').change(function(e) {
		 $("#roomphotoloading").show();
		$("#roomphotocontainer").hide();
		$("#roombackgroundphoto").hide();
		$("#roomuploadbackground").hide();
		$("#roomphotoupload").hide();
		var file = e.target.files[0];
		$.canvasResize(file, {
			width: 1200,
			height: 0,
			crop: false,
			quality: 80,
			//rotate: 90,
			callback: function(data, width, height) {
				// alert(data);
				convertImage(data);
				$("#imagepreview").attr('src', data);
			}
		});




	});

    $('#roombackground').mousemove(function(event) { 
        var left = event.pageX - $(this).offset().left;
        var top = event.pageY - $(this).offset().top;
		leftperc = left / $("#roombackground").width()
		topperc = top / $("#roombackground").height()
		// $("#log").show().html(topperc+" X "+leftperc);
    });

	$('#roombackground').click(function(event){ 
		var left = event.pageX - $(this).offset().left;
		var top = event.pageY - $(this).offset().top;
		leftperc = left / $("#roombackground").width()
		topperc = top / $("#roombackground").height()
		// lert(topperc+" X "+leftperc);
	});
	doubleclickopenphotos();

	$.ajax({
	  url: tempfolder+'/sy-inc/room-view/touch.js',
	  dataType: "script"
	});
<?php if($_SESSION['showsaveddialog'] == true) {
	unset($_SESSION['showsaveddialog']);
	?>
	showsaveddialogloggedin();
<?php } ?>
	setTimeout(function(){
	sizeroomphoto();
	},400);

	<?php if((!empty($_REQUEST['photo'])) && (empty($_REQUEST['rw'])) == true) { ?>
	firstphoto();
	<?php } ?>

});
$(window).resize(function() {
	sizeroomphoto();
});


			
</script>
<?php
$time=time()+3600*24*365*2;
$domain = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$cookie_url = ".$domain";
SetCookie("wdinfoshow","1",$time,"/",null);
?>

<?php 
### Updating wall view to new customer login ####
$_SESSION['roomview'] = true;
if(isset($_SESSION['saveandlogin'])) { 
	$_REQUEST['rw'] = $_SESSION['saveandlogin'];
	$saveandlogin = 	$_SESSION['saveandlogin'];
	unset($_SESSION['saveandlogin']);
	if(customerLoggedIn()) { 
		$p = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
		$link = $saveandlogin;
		updateSQL("ms_wall_saves","wall_date='".currentdatetime()."', wall_person='".$p['p_id']."' WHERE wall_link='".$saveandlogin."' "); 
		$_SESSION['showsaveddialog'] = true;
		// print_r($_SESSION);
		header("location: index.php?view=room&rw=".$link);
		session_write_close();
		exit();
	}
}



if((!empty($_SESSION['new_room_photo'])) && (empty($_REQUEST['frameadjust'])) == true) { 
	$size = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos/".$_SESSION['new_room_photo'].""); 
		$room_photo = $setup['photos_upload_folder']."/customer-room-photos/".$_SESSION['new_room_photo'];

		$data_room_photo_width = $size[0];
		$data_room_photo_height = $size[1];
		$data_room_width = 84;
		$data_center = ".500";
		$data_base = ".400";
		unset($_SESSION['new_room_photo']);

	} else { 

		## Getting default room photo ##
		if(isset($_SESSION['pid'])) { 
			$p = doSQL("ms_people","*","WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			if($p['p_id'] > 0) { 
				$room_photos = doSQL("ms_wall_rooms", "*", "WHERE room_width>'0' AND room_person='".$p['p_id']."' ORDER BY room_id DESC  ");
			}
		}
		if(empty($room_photos['room_id'])) { 
			$room_photos = doSQL("ms_wall_rooms", "*", "WHERE room_person<='0' ORDER BY room_order ASC ");
		}
		$room_photo = $room_photos['room_large'];
		$room_photo_path_checked = $room_photos['room_large'];
		$data_room_photo_width = $room_photos['room_photo_width'];
		$data_room_photo_height = $room_photos['room_photo_height'];
		$data_room_width = $room_photos['room_width'];
		$data_center = $room_photos['room_center'];
		$data_base = $room_photos['room_base'];
	}
	if($data_room_photo_width > 1000) {
		$hp = 1000 / $data_room_photo_width;
		$containheight = $data_room_photo_height * $hp;
	} else { 
		$containheight = $data_room_photo_height;
	}


	 if(!empty($_REQUEST['rw'])) { 
		$wall = doSQL("ms_wall_saves", "*","WHERE wall_link='".$_REQUEST['rw']."' ");
		if(empty($wall['wall_id'])) { die("Unable to find the data, sorry."); } 
		$rooms = explode("||",$wall['wall_room_data']);

		foreach($rooms AS $room) { 
			$room = trim($room);
			if(!empty($room)) { 
				$x++;
				$i = explode(",",$room);
				foreach($i AS $p) { 
					$t = explode("=",$p);
					// print "\r\n<li>".$p;
					$data[trim($t[0])][$x] = trim($t[1]);
					//	 print "<li>XXX".$t[0]." ".$x." ".$t[1];
				}
			}	
		}

		$room_photo =  $data['data-room-background-photo'][1];
		$data_room_photo_width = $data['data-room-photo-width'][1];
		$data_room_photo_height = $data['data-room-photo-height'][1];
		$data_room_width = $data['data-room-width'][1];
		$data_center = $data['data-center'][1];
		$data_base = $data['data-base'][1];
	 }

		if($data_room_photo_width < 1200) { 
			$max_width = $data_room_photo_width;
		} else { 
			$max_width = 1200;
		}


if(!empty($_REQUEST['frameadjust'])) { 
	$data_room_width = 50;
}
?>


<?php if(empty($_REQUEST['frameadjust'])) { ?>

<div id="roomheader">
	<div class="left p33 textright">
		<div class="pc">&nbsp;<a href="index.php<?php if($_REQUEST['from'] == "favorites") { ?>?view=favorites<?php } else { ?><?php if(!empty($_REQUEST['sub'])) { if(!ctype_alnum($_REQUEST['sub'])) { die("an error has occurred [alnum sub]"); } ?>?sub=<?php print $_REQUEST['sub'];?><?php } ?><?php } ?>" class="the-icons icon-left-open"><?php print _wd_return_to_gallery_;?></a></div>
	</div>

	<div class="left p33 center wallpricecontainer">	
		<div class="pc center" id="pricecontainer"><span id="wallprice"></span></div>
	</div>
	<div class="left p33">
		<div class="pc">
		<?php if($_REQUEST['from'] == "favorites") { 
			$pic = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE MD5(fav_person)='".$_SESSION['pid']."'  AND pic_id>'0'  AND (date_expire='0000-00-00' OR date_expire>='".date('Y-m-d')."' ) AND date_photo_price_list>'0' ");
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$pic['date_photo_price_list']."' ");
		}
		?>
		<!-- <a href="" onclick="saveroom('0','1'); return false;" class="the-icons icon-basket">Add To Cart</a> -->
		<a href="" onclick="roomaddtocart('<?php print $list['list_id'];?>'); return false;" class="the-icons" id="purchaselink"><?php print _wd_purchase_;?><span class="the-icons icon-right-open"></span></a></div>
	</div>
	<div class="clear"></div>

</div>
<?php } ?>

<div id="roomphotoloading"><div class="loadingspinner"></div></div>
<div id="roomuploadbackground"></div>

<div id="roomviewer" >
	<div style="max-width: <?php print $max_width;?>px; margin:auto; width: 100%; position: relative; ">
	<div id="roomview">
	<div id="photoinfo" class="pc center">
		&nbsp;
	</div>
<?php
	if(!file_exists($setup['path'].$room_photo)) { ?><div style="max-width: 600px; width: 100%; margin: auto;" id="missingroomphoto"><div class="error">Sorry, the room photo is no longer available.<br><br>Click Change Room to select a different room photo.</div></div><?php } ?>

		<div id="roomcontain" style="width: 100%; max-width: <?php print $max_width;?>px; margin: auto; height:<?php print $containheight;?>px;" data-date-id="<?php print $date['date_id'];?>" data-sub-id="<?php print $sub['sub_id'];?>" data-room-photo-width="<?php print $data_room_photo_width;?>" data-room-photo-height="<?php print $data_room_photo_height;?>"   data-room-width="<?php print $data_room_width;?>" data-center="<?php print $data_center;?>" data-base="<?php print $data_base;?>" data-center-photo="1"   data-room-background-photo="<?php print $room_photo;?>" data-selected-photo="" data-last-selected-photo="" data-from="<?php if($_REQUEST['from'] == "favorites") { ?>favorites<?php } ?>" data-url="<?php print $setup['url'].$setup['temp_url_folder'];?>/" data-approx-width="<?php print _wd_total_width_with_frame_;?>" data-print-size="<?php print _wd_print_size_;?>" data-no-price="<?php if(($wall['wall_no_price'] == "1") && (!isset($_SESSION['office_admin_login'])) == true) { ?>1<?php }?>">
		<div id="roombackground"><img id="roombackgroundphoto" src="<?php print $setup['temp_url_folder'].$room_photo;?>" style="width: 100%; max-width: <?php print $data_room_photo_width;?>px; height: auto;"></div>
		<?php 
		$photos = array("6fc9b4874fe77c6509cc8e41b299e669","e7f33fbbbcde9e7c385af2b0726ceecf","6c126e3c9f89bfb12adf39a1f0c93eed","b49961ec3300eb7c82c87be986377959","a5142a9aeeddda68abb782380dfca0e6","dc100edc83347097465626425f7e296c","3a89d3c45534d3486fb814d35f20d658","3969100b6fbb3f45d69cafe99ea720c8","2a676bddd4c8a36ca40f35d313cc389c","298c4d7ad2ff0586a31d93145ec56149","81a99c5b465374639a0194ebcb634c38");

		if(!empty($wall['wall_id'])) { 
			$wallitems = $wall['wall_items'];
		} else { 
			if(!empty($_REQUEST['photo'])) { 
				if(!ctype_alnum($_REQUEST['photo'])) { die("an error has occurred [alnum]"); }
				if($_REQUEST['from'] == "favorites") { 
					$pic = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", " WHERE pic_key='".$_REQUEST['photo']."' ");
					$date['date_id'] = $pic['date_id'];
					$sub['sub_id'] = $pic['fav_sub_id'];
				} else { 
					$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$_REQUEST['photo']."' ");
				}
				$data_photo_file = getimagefile($pic,'pic_pic');
				$data_pic_key = $pic['pic_key'];
				
			// if($pic['pic_id'] <= 0) { die("Unable to find photo"); } 
			// $psize = getimagefiledems($pic,'pic_pic');
			} else { 
				$data_photo_filoe = $setup['temp_url_folder']."/sy-inc/room-view/click-me-for-options.jpg";
			}
			if($wset['math_type'] == "inches") { 
				$def_size = 12;
			} else { 
				$def_size = 30;
			}
			$wallitems = "data-width=".$def_size.",	data-height=".$def_size.",	data-show-width=16.00,	data-show-height=20.00,	data-from-center=0,	data-from-base=0,	data-photo-number=1,	data-price=,	data-style-id=0,	data-frame-id=0,	data-color-id=0,	data-frame-width=0,	data-frame-mat-size=0,	data-mat-color=,	data-mat-print-width=0,	data-mat-print-height=0,	data-frame-corners=0,	data-canvas-depth=,	data-frame-file=,	data-photo-file=".$data_photo_file.",	data-pic-key=".$data_pic_key.",	data-canvas-edge=,data-date-id=".$date['date_id'].",data-sub-id=".$sub['sub_id'].",data-default=1||
			";
		}

			if(!empty($_REQUEST['frameadjust'])) { 
				$sampleimage = $setup['temp_url_folder']."/sy-inc/room-view/frames/frame-adjust-photo.jpg";

				$style = doSQL("ms_frame_styles", "*",  "WHERE style_id='".$_REQUEST['frameadjust']."' ");
				$img = doSQL("ms_frame_images", "*", "WHERE img_id='".$_REQUEST['img_id']."' ORDER BY img_order ASC  ");
				$fsize = doSQL("ms_frame_sizes", "*", "WHERE frame_style='".$style['style_id']."' AND frame_default='1' ");
				if(empty($img['img_corners'])) { 
					$corners = $style['style_frame_corners'];
				} else { 
					$corners = $img['img_corners'];
				}

				if(empty($corners)) { 
					$corners = "20-20-20-20";
				}
				if(empty($fsize['frame_id'])) { 
					$fsize['frame_width'] = 11;
					$fsize['frame_height'] = 14;
				}
				$wallitems = "data-width=".$fsize['frame_width'].",	data-height=".$fsize['frame_height'].",	data-show-width=".$fsize['frame_width'].",	data-show-height=".$fsize['frame_height'].",	data-from-center=0,	data-from-base=0,	data-photo-number=1,	data-price=".$fsize['frame_price'].",	data-style-id=".$style['style_id'].",	data-frame-id=".$fsize['frame_id'].",	data-color-id=".$img['img_id'].",	data-frame-width=".$style['style_frame_width'].",	data-frame-mat-size=,	data-mat-color=,	data-mat-print-width=".$fsize['frame_mat_print_width'].",	data-mat-print-height=".$fsize['frame_mat_print_height'].",	data-frame-corners=".$corners.",	data-canvas-depth=,	data-frame-file=".$setup['temp_url_folder'].$img['img_small'].",	data-photo-file=,	data-pic-key=,	data-canvas-edge= ||
			";
			

			}

			$items = explode("||",$wallitems);
			$x = 0;
			foreach($items AS $item) { 
				$item = trim($item);
				if(!empty($item)) { 
					$x++;
					$i = explode(",",$item);
					foreach($i AS $p) { 
						$t = explode("=",$p);
						// print "\r\n<li>".$p;
						$data[trim($t[0])][$x] = trim($t[1]);
						// print "<li>XXX".$t[0]." ".$x." ".$t[1];
					}
				}	
			}

				$i = 0;
				while($i < $x) { 

				$i++;
				$p = explode(",",$print);
				// $pic = doSQL("ms_photos", "*", "WHERE pic_key='".$photos[$i]."' ");
				?>
					<div id="roomphotocontainer-<?php print $i; ?>" class="roomphotocontainer hide <?php if($data['data-frame-id'][$i] > 0) { ?>frameshadow<?php } else { ?>canvasshadow<?php } ?>"  
					data-width="<?php print $data['data-width'][$i];?>" 
					data-height="<?php print $data['data-height'][$i];?>" 
					data-show-width="<?php print $data['data-show-width'][$i];?>" 
					data-show-height="<?php print $data['data-show-height'][$i];?>" 
					data-from-center="<?php print $data['data-from-center'][$i];?>" 
					data-from-base="<?php print $data['data-from-base'][$i];?>" 
					data-photo-number="<?php print $i; ?>" 
					data-price="<?php print $data['data-price'][$i];?>"  
					data-color-id="<?php print $data['data-color-id'][$i];?>" 
					data-style-id="<?php print $data['data-style-id'][$i];?>" 
					data-canvas-id="<?php print $data['data-canvas-id'][$i];?>" 
					data-frame-id="<?php print $data['data-frame-id'][$i];?>"  
					data-frame-file="<?php print $data['data-frame-file'][$i];?>"  
					data-print-product-id="<?php print $data['data-print-product-id'][$i];?>"  
					data-frame-mat-size="<?php print $data['data-frame-mat-size'][$i];?>"  
					data-mat-color="<?php print $data['data-mat-color'][$i];?>"
					data-mat-color-id="<?php print $data['data-mat-color-id'][$i];?>"
					data-frame-width="<?php print $data['data-frame-width'][$i];?>" 
					data-mat-print-width="<?php print $data['data-mat-print-width'][$i];?>" 
					data-mat-print-height="<?php print $data['data-mat-print-height'][$i];?>" 
					data-frame-corners="<?php print $data['data-frame-corners'][$i];?>" 
					data-canvas-depth="<?php print $data['data-canvas-depth'][$i];?>" 
					data-canvas-edge="<?php print $data['data-canvas-edge'][$i];?>" 
					data-default="<?php print $data['data-default'][$i];?>" 
					<?php 
					if(!empty($data['data-pic-key'][$i])) { 
						$pic = doSQL("ms_photos", "*", "WHERE pic_key='".$data['data-pic-key'][$i]."' "); 
						$size = getimagefiledems($pic,"pic_pic");
					} 
					?>

					<?php if($data['data-frame-id'][$i] > 0) { 
						$corner = explode("-",$data['data-frame-corners'][$i]);
					?> style="border-image: url('<?php print $data['data-frame-file'][$i];?>') <?php print $corner[0];?>% <?php print $corner[1];?>% <?php print $corner[2];?>% <?php print $corner[3];?>%  round round;"
					<?php } ?>>
						<div id="roomphotomatte-<?php print $i; ?>" class="roomphotomatte <?php if($data['data-frame-mat-size'][$i] > 0) { ?>matshadow<?php } ?>" style="background: #<?php print $data['data-mat-color'][$i];?>;">
							<div id="roomphoto-<?php print $i; ?>" class="roomphoto <?php if($data['data-frame-id'][$i] > 0) { ?>photoshadow<?php } ?>" 
							style="background-image: url('<?php
								if(!empty($_REQUEST['frameadjust'])) { 
									print $sampleimage;
								} else { 
								if(!empty($data['data-photo-file'][$i])) { if($data['data-bw'][$i] == "1") { print $setup['temp_url_folder']."/sy-photo.php?thephoto=".$data['data-pic-key'][$i]."|84b70c81232d746dbd3c18d1f036be7f|0|1";   } else { print $data['data-photo-file'][$i]; } } else { print getimagefile($pic,'pic_pic'); } 
								}
								?>');" 
							data-photo-file="<?php  print getimagefile($pic,'pic_pic'); ?>" data-pic-key="<?php  print $pic['pic_key']; ?>" 
							data-bw="<?php print $data['data-bw'][$i];?>"  
							<?php if(!empty($_REQUEST['frameadjust'])) { ?>
							data-pic-width="500" 
							data-pic-height="575" 
							<?php } else { ?>
							data-pic-width="<?php print $size[0];?>" 
							data-pic-height="<?php print $size[1];?>" 
							<?php } ?>
							data-y-pos="<?php print $data['data-y-pos'][$i];?>" 
							data-x-pos="<?php print $data['data-x-pos'][$i];?>" 
							data-date-id="<?php print $data['data-date-id'][$i];?>"  
							data-sub-id="<?php print $data['data-sub-id'][$i];?>"
							data-zoom="<?php print $data['data-zoom'][$i];?>"
							>
							</div>
						</div>
						<div class="adjustphoto">
							<div class="roomphotomoveup"><span class="the-icons icon-down"   onclick="adjustselectedphotoposition('up'); return false;"></span></div>
							<div class="roomphotomovedown"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('down'); return false;"></span></div>
							<div class="roomphotomoveleft"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('left'); return false;"></span></div>
							<div class="roomphotomoveright"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('right'); return false;"></span></div>
						</div>
					</div>

				<?php 
			}
		?>
		</div>
	</div>

		<div id="roomsize" style="position: absolute; background: #000000;  text-align: center; z-index: 5; width: 100%; bottom: 0%; display: none;">
			<div style="padding: 12px;">
				<div class="pc center">How many feet across is the room in the photo?</div>
				<div class="pc center">
				<a href="" onclick="changeroominches('down'); return false;">-</a> 
				<input type="text" id="roominches" value="8" size="2" style="text-align: center;">
				<a href="" onclick="changeroominches('up'); return false;">+</a> 
				</div>
				<div class="pc center"><a href="" onclick="hidechangeroominches(); return false;">OK</a></div>
			</div>
		</div>
</div>
<?php # End room viewer ?>

<?php # Default code for new print ?>
		<div id="roomphotocode" class="hide">
			<div id="roomphotocontainer-0000" class="roomphotocontainer hide  canvasshadow"  
			data-width="10" 
			data-height="10" 
			data-show-width="10"
			data-show-height="10" 
			data-from-center="0" 
			data-from-base="-4" 
			data-photo-number="0000" 
			data-color-id="0" 
			data-style-id="0" 
			data-canvas-id="0"
			data-frame-id="0" 
			data-frame-mat-size="0" 
			data-frame-file=""
			data-mat-color="" 
			data-mat-color-id="" 
			data-frame-width="" 
			data-price="" 
			data-mat-print-width="" 
			data-mat-print-height="" 
			data-frame-corners="" 
			data-canvas-depth="" 
			data-canvas-edge=""
			data-print-product-id="" 
			>
				<div id="roomphotomatte-0000" class="roomphotomatte">
					<div id="roomphoto-0000" class="roomphoto" style="background-image: url('');" data-photo-file="" data-pic-key="" 
					data-date-id="" 
					data-sub-id="" 
					data-zoom=""
					data-y-pos="50" 
					data-x-pos="50" 
					>
						<div id="roomphotoinsert-0000" class="roomphotoinsert"  style="background-image: url('');"></div>
						<div id="roomphotoinfo-0000" class="roomphotoinfo">
							<div id="roomphotoinfoinner-0000" class="roomphotoinfoinner"></div>
						</div>
					</div>
				</div>
				<div class="adjustphoto">
					<div class="roomphotomoveup"><span class="the-icons icon-down"   onclick="adjustselectedphotoposition('up'); return false;"></span></div>
					<div class="roomphotomovedown"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('down'); return false;"></span></div>
					<div class="roomphotomoveleft"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('left'); return false;"></span></div>
					<div class="roomphotomoveright"><span class="the-icons icon-down"  onclick="adjustselectedphotoposition('right'); return false;"></span></div>
				</div>
			</div>
		</div>

		<?php # End default code for new print ?>
	
	<div id="roomphotoupload" class="gallerypopup hide">
		<div id="roomphotouploadcontainer" class="center">
			<div style="padding: 24px;" class="inner">
			<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closeshowphotoupload(); return false;" class="the-icons icon-cancel"></a></div>
				<div class="pc center"><?php print _wd_upload_room_photo_instructions_;?></div>
				<div>&nbsp;</div>
			  <form id="form1" enctype="multipart/form-data" method="post">
				<div>
				  <label for="fileToUpload" class="checkout"><?php print _wd_upload_or_take_photo_;?></label><br />
				  <input class="hide" type="file" name="fileToUpload" id="fileToUpload" accept="image/*" />
				</div> 
				<div id="progress"></div>
			  </form>
			 </div>
		</div>
	</div>
	<div>&nbsp;</div>
</div>


	<!-- START OF OPTIONS -->
<?php if(!empty($_REQUEST['frameadjust'])) { ?>
<script>
function showframeadjustmenu() { 
  	$("#roomuploadbackground").fadeIn(100, function() { 
		$("#roomuploadbackground").prop('onclick',null).off('click');
		$('.roomphoto').unbind('dblclick');
		$("#roomcontain").attr("data-selected-photo",1);
		$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).css({"z-index":"200"});
		$("#roommenu").slideUp(200, function() { 
			$("#frameadjustmenu").slideDown(200);
		});
	});
}

function adjustframecorners() { 
	fc = $("#frame-corner-a").val()+"-"+$("#frame-corner-b").val()+"-"+$("#frame-corner-c").val()+"-"+$("#frame-corner-d").val();
	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-frame-corners",fc);
	file = $("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-frame-file");
	framewidth = parseFloat($("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).attr("data-frame-width"));

	$("#roomphotocontainer-"+$("#roomcontain").attr("data-selected-photo")).addClass("borderframe").css({"border-image":"url('"+file+"') "+$("#frame-corner-a").val()+"% "+$("#frame-corner-b").val()+"% "+ $("#frame-corner-c").val()+"% "+ $("#frame-corner-d").val()+"% round", "-webkit-border-image":"url('"+file+"') "+$("#frame-corner-a").val()+"% "+$("#frame-corner-b").val()+"% "+ $("#frame-corner-c").val()+"% "+ $("#frame-corner-d").val()+"% round", "-o-border-image":"url('"+file+"') "+$("#frame-corner-a").val()+"% "+$("#frame-corner-b").val()+"% "+ $("#frame-corner-c").val()+"% "+ $("#frame-corner-d").val()+"% round","border-width":framewidth* pixels+"px"}).removeClass("canvasshadow").addClass("frameshadow");
	sizeroomphoto();
}


function frameadjustvalue(id,dir) {
	if(dir == "up") { 
		val = Math.abs($("#"+id).val()) + .5;
	}
	if(dir == "down") { 
		val = Math.abs($("#"+id).val()) - .5;
	}
	$("#"+id).val(val);
	adjustframecorners();
}

function saveframeadjust(styleid,imgid) { 
	if($("#setasdefault").attr("checked")) { 
		setasdefault = 1;
	} else { 
		setasdefault = 0;
	}
	fc = $("#frame-corner-a").val()+"-"+$("#frame-corner-b").val()+"-"+$("#frame-corner-c").val()+"-"+$("#frame-corner-d").val();
	$.get(tempfolder+"/sy-inc/room-view/room-view-actions.php?action=saveframeadjust&styleid="+styleid+"&imgid="+imgid+"&setasdefault="+setasdefault+"&corners="+fc, function(data) {
		showSuccessMessage("Frame settings saved");
		setTimeout(hideSuccessMessage,4000);
	});
}
$(document).ready(function(){
	showframeadjustmenu();
	setTimeout(function(){
	sizeroomphoto();
	},200);
	$('.frameadjustvalues').change(function(e) {
		adjustframecorners();
	});
});
</script>

<?php } ?>

	<div id="frameadjustmenu" class="roomviewmenus" style="display: none;">
		<div style="padding: 16px;">
			<div class="pc center">Adjust the values below so the photo fits in the frame.</div>
			<div class="inlineli center" style="margin-bottom: 12px;">
			<?php $corner = explode("-",$corners); ?>

				<div style="display: inline-block; margin: auto; padding: 16px;">
					<div class="pc center">Left</div>
					<div class="pc center">
						<a href="" onclick="frameadjustvalue('frame-corner-d','down'); return false;" style="font-size: 17px;">-</a>
						<input type="text" id="frame-corner-d" class="frameadjustvalues center"  value="<?php print $corner[3];?>" size="3"> 
						<a href="" onclick="frameadjustvalue('frame-corner-d','up'); return false;" style="font-size: 17px;">+</a>
					</div>
				</div>

				<div style="display: inline-block; margin: auto; padding: 16px;">
					<div class="pc center">Top</div>
					<div class="pc center">
						<a href="" onclick="frameadjustvalue('frame-corner-a','down'); return false;" style="font-size: 17px;">-</a>
						<input type="text" id="frame-corner-a" class="frameadjustvalues center" value="<?php print $corner[0];?>" size="3"> 
						<a href="" onclick="frameadjustvalue('frame-corner-a','up'); return false;" style="font-size: 17px;">+</a>
					</div>
				</div>

				<div style="display: inline-block; margin: auto; padding: 16px;">
					<div class="pc center">Right</div>
					<div class="pc center">
					<a href="" onclick="frameadjustvalue('frame-corner-b','down'); return false;" style="font-size: 17px;">-</a>
					<input type="text" id="frame-corner-b" class="frameadjustvalues center"  value="<?php print $corner[1];?>" size="3"> 
					<a href="" onclick="frameadjustvalue('frame-corner-b','up'); return false;" style="font-size: 17px;">+</a>
					</div>
				</div>

				<div style="display: inline-block; margin: auto; padding: 16px;">
					<div class="pc center">Bottom</div>
					<div class="pc center">
					<a href="" onclick="frameadjustvalue('frame-corner-c','down'); return false;" style="font-size: 17px;">-</a>
					<input type="text" id="frame-corner-c" class="frameadjustvalues center"  value="<?php print $corner[2];?>" size="3"> 
					<a href="" onclick="frameadjustvalue('frame-corner-c','up'); return false;" style="font-size: 17px;">+</a>
					</div>
				</div>
			
				<div class="clear"></div>
				<div class="pc center"><input type="checkbox" name="setasdefault" id="setasdefault" value="1"> <label for="setasdefault">Set as default settings for all frame colors in this style that have not been adjusted.</label></div>
				<div class="pc center buttons"><a href="" onclick="saveframeadjust('<?php print $style['style_id'];?>','<?php print $img['img_id'];?>'); return false;" class="">Save</a></div>
				<div class="pc center">
				&nbsp;<a href="" onclick="clodeframewindow(); return false;">Close</a>
				</div>

			</div>
		</div>
	</div>




	<div id="adjustmenu" class="roomviewmenus hide" >
		<div style="padding: 16px 0px;">
			<div class="inlineli" style="margin-bottom: 12px;">
				<ul >
				<li><a href="" onclick="setroomphotoposition(); return false;" class="the-icons icon-resize-full-alt"><?php print _wd_reset_;?></a></li>

				<li><a href="" onclick="adjustzoom('in'); return false;" class="the-icons icon-zoom-in"><?php print _wd_zoom_in_;?></a></li>
				<li><a href="" onclick="adjustzoom('out'); return false;" class="the-icons icon-zoom-out"><?php print _wd_zoom_out_;?></a></li>
				<li><a href="" onclick="unzoomphotoadjust(); return false;" class="the-icons icon-check-1"><?php print _wd_done_;?></a></li>

				</ul>

			</div>

				<div class="inlineli hide" id="mobileadjust">
				<ul>
					<li><span class="the-icons icon-up-open"   onclick="adjustselectedphotoposition('up'); return false;"></span></li>
					<li><span class="the-icons icon-down-open"  onclick="adjustselectedphotoposition('down'); return false;"></span></li>
					<li><span class="the-icons icon-left-open"  onclick="adjustselectedphotoposition('left'); return false;"></span></li>
					<li><span class="the-icons icon-right-open"  onclick="adjustselectedphotoposition('right'); return false;"></span></li>
				</ul>
			</div>
			<div>&nbsp;</div>
		</div>
	</div>
	<div id="roommeasurements" class="roomviewmenus hide">
		<div style="padding: 16px 0px;">
			<div class="pc center" id="roommeasuretitle"><h2><?php print _wd_set_room_measurement_;?></h2></div>
			<div class="pc center error hide" id="roommeasureselecterror"><?php print _wd_set_room_measurement_select_area_error_;?></div>
			<div class="pc center error hide" id="roommeasuresizeerror"><?php print _wd_set_room_measurement_enter_size_error_;?></div>
			
			<?php 
			if($wset['math_type'] == "centimetre") { 
				$set_room_texta = str_replace("[INCHES_CENTIMETRES]",$wdlang['_wd_centimeters_'],$wdlang['_wd_set_room_measurement_instructions_']);
				$set_room_textb = str_replace("[INCHES_CENTIMETRES]",$wdlang['_wd_centimeters_'],$wdlang['_wd_enter_width_or_height_']);
			} else { 
				$set_room_texta = str_replace("[INCHES_CENTIMETRES]",$wdlang['_wd_inches_'],$wdlang['_wd_set_room_measurement_instructions_']);
				$set_room_textb = str_replace("[INCHES_CENTIMETRES]",$wdlang['_wd_inches_'],$wdlang['_wd_enter_width_or_height_']);
			}
			?>
			<div class="pc"><?php print $set_room_texta;?></div>
			<div class="pc bold"><?php print $set_room_textb;?></div>
			<div class="pc center"><?php print _wd_width_;?><input type="text" name="rm_w" id="rm_w" size="4" class="center"> <?php print _wd_or_height_;?> <input type="text" name="rm_h" id="rm_h" size="4" class="center">  <a href="" onclick="setmeasurement(); return false;" class="checkout"><?php print _wd_set_;?></a></div>
			<div class="pc center" id="measurecancel"><a href="" onclick="disableselection(); return false;"><?php print _cancel_;?></a></div>
		</div>
	</div>


	<div id="roommenu" class="roomviewmenus"  style="<?php if(($wall['wall_no_edit'] == "1") && (!isset($_SESSION['office_admin_login'])) == true) { ?>display: none;<?php } ?>">
		<div style="padding: 16px 2px;">
		<div id="photomenuactionscontainer"   style="margin: 0px auto 12px auto; text-align: center;">
			<div  id="photomenuactions" class="hide inlineli center">
			<ul>
			<?php if($wset['offer_frames'] == "1") { ?>
			<li><a id="framechoicemenuselect" onclick="openframemenu(); return false;" class="the-icons icon-down-open"><?php print _wd_framed_print_;?></a></li>
			<?php } ?>
			<?php if($wset['offer_canvas'] == "1") { ?>
			<li><a id="sizemenuselectedsize" onclick="opensizemenu(); return false;"  class="the-icons icon-down-open "><?php print _wd_canvas_print_;?></a></li>
			<?php } ?>
			<li><a href="" onclick="turnselectedphoto(); return false;" class="the-icons icon-arrows-cw"><?php print _wd_rotate_;?></a></li>
			<?php if($wset['offer_bw'] == "1") { ?>
			<li id="bwbutton"><a href="" onclick="blackwhite(); return false;" id="bwbutton" class="the-icons icon-palette"><?php print _wd_bw_;?></a></li>
			<li id="bwbuttonoriginal" class="hide"><a href="" onclick="blackwhiteoriginal(); return false;"  class="the-icons icon-palette"><?php print _wd_original_;?></a></li>
			<?php } ?>
			<li><a href="" onclick="zoomphotoadjust(); return false;" class="the-icons icon-crop"><?php print _wd_adjust_;?></a></li>
			<li><a href="" onclick="sidethumbs('0'); return false;" class="the-icons icon-picture"><?php print _wd_choose_photo_;?></a></li>
			<li id="removelink"><a href="" onclick="removeprint(); return false;" class="the-icons icon-trash-empty"><?php print _wd_remove_;?></a></li>
			</ul>
			</div>
		<div class=""  id="photomenuactionsclickphoto"><b><?php print _wd_click_photo_on_wall_for_options_;?></b></div>
		<div class="clear"></div>
	</div>
	<div id="frameoptionsmenucontainer"   style="margin-bottom: 12px; text-align: center;">
		<div  id="frameoptionsmenu" class="inlineli center">
		</div>
	</div>

		<div class="inlineli" style="margin-bottom: 12px;">
			<ul >
			<li><a  id="roommeasurelink" href="" onclick="addmeasureing(); return false;" class="the-icons icon-ruler hide"><?php print _wd_room_measurement_;?></a> </li>
			<li><a href="" onclick="openroommenu(); return false;" class="the-icons icon-down-open"><?php print _wd_change_room_;?></a></li>
			
			<li <?php if(countIt("ms_wall_saves","WHERE wall_collection='1' ORDER BY wall_collection_order ASC ") <= 0) { ?>style="display: none;"<?php } ?> id="collectionsmenu"><a href="" onclick="opencollagemenu(); return false;"  class="the-icons icon-down-open" ><?php print _wd_wall_collections_;?></a></li>
			<li><a href="" onclick="addnewprint(); return false;"  class="the-icons icon-plus"><?php print _wd_add_new_print_;?></a></li>
			<?php if(!isset($_SESSION['pid'])) { ?>
			<li><a href="" onclick="saveroom('1'); return false;" class="the-icons icon-floppy"><?php print _wd_save_;?></a></li>
			<?php } else { ?>
			<li><a href="" onclick="savedialog(); return false;" class="the-icons icon-floppy"><?php print _wd_save_;?></a></li>
			<?php } ?>

			<?php 	if(customerLoggedIn()) { ?>
			<li id="mysavedlink"><a href="" onclick="showmysavedrooms('<?php print $_SESSION['pid'];?>','<?php if($_REQUEST['return'] == "1") { ?>1<?php } ?>','<?php print $_REQUEST['sub'];?>'); return false;" class="the-icons icon-user"><?php print _wd_my_saved_rooms_;?></a></li>
			<?php } ?>
			<li><a href="" onclick="showhelpbubble(); return false;" class="the-icons icon-help-circled"></a></li>
			<!-- <li><a href="" onclick="showframeadjustmenu(); return false;">Frame Adjust</a></li> -->
			</ul>
		</div>

	<div class="clear"></div>
	</div>
</div>
<!-- END OF OPTIONS -->







	<div id="collagemenu" class="gallerypopup hide">
		<div style="padding: 24px;" class="inner">
			<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
			<?php if(!empty($wdlang['_wd_collections_title_'])) { ?><div class="pc"><h3><?php print $wdlang['_wd_collections_title_'];?></h3></div><?php } ?>
			<?php if(!empty($wdlang['_wd_collections_text_'])) { ?><div class="pc"><?php print $wdlang['_wd_collections_text_'];?></div><?php } ?>
			<div id="collagemenucontent">

			</div>
		</div>
	</div>


	<div id="roomchoicemenu" class="gallerypopup hide">
		<div style="padding: 24px;" class="inner">
		<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
		<div class="pc center"><a href="" onclick="showphotoupload(); return false;" class="the-icons icon-camera"><?php print _wd_upload_your_own_photo_;?></a></div>
		<?php
		if(isset($_SESSION['pid'])) { 
			$p = doSQL("ms_people","*","WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			if($p['p_id'] > 0) { 

			$rooms = whileSQL("ms_wall_rooms", "*", "WHERE room_width>'0' AND room_person='".$p['p_id']."' ORDER BY room_id DESC ");
			if(mysqli_num_rows($rooms) > 0) { 
				?><div class="pc center"><?php print _wd_my_room_photos_;?></div>
				<?php 
				while($room = mysqli_fetch_array($rooms)) { ?>
				<div  id="croom-<?php print $room['room_id'];?>">
					<div class="pc center"><a href="" onclick="changeroom('<?php print $room['room_large'];?>', '<?php print $room['room_photo_width'];?>','<?php print $room['room_photo_height'];?>','<?php print $room['room_width'];?>','<?php print $room['room_center'];?>','<?php print $room['room_base'];?>','1'); return false;"><img src="<?php print $setup['temp_url_folder'].$room['room_small'];?>" style="width: 100%; height: auto;"></a></div>
					<div class="pc center"><a href="javascript:deleteroomphoto('<?php print $room['room_id'];?>','<?php print $room['room_person'];?>');" onclick="return confirm('Are you sure you want to delete this?');" class="the-icons icon-trash-empty">delete</a></div>
				</div>
			<?php } 
			print "<div>&nbsp;</div>";
				}
			}
		}
		if(!empty($_SESSION['new_room_photo'])) { 
			$size = GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/customer-room-photos/".$_SESSION['new_room_photo'].""); 

		?>
		<div class="pc center"><a href="" onclick="changeroom('<?php print "/sy-photos/customer-room-photos/".$_SESSION['new_room_photo'];?>','<?php print $size[0];?>','<?php print $size[1];?>','112','.500','.500'); return false;"><img src="<?php print $setup['temp_url_folder']."/sy-photos/customer-room-photos/".$_SESSION['new_room_photo'];?>"  style="width: 100%; height: auto;"></a></div>


		<?php } ?>
		<div class="pc center"><?php print _wd_stock_room_photos_;?></div>
		<?php
		$rooms = whileSQL("ms_wall_rooms", "*", "WHERE room_width>'0' AND room_person<='0' ORDER BY room_order ASC  ");
		while($room = mysqli_fetch_array($rooms)) { ?>
		<div class="pc center"><a href="" onclick="changeroom('<?php print $room['room_large'];?>', '<?php print $room['room_photo_width'];?>','<?php print $room['room_photo_height'];?>','<?php print $room['room_width'];?>','<?php print $room['room_center'];?>','<?php print $room['room_base'];?>','0'); return false;"><img src="<?php print $setup['temp_url_folder'].$room['room_small'];?>" style="width: 100%; height: auto;"></a></div>
		<?php } ?>

		</div>
	</div>

		<div id="roomaddtocart" class="gallerypopup hide">
			<div style="padding: 24px;" class="inner">
				<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
				<div id="roomaddtocartcontent">
				</div>
			</div>
		</div>

		<div id="roomaddtocartsuccess" class="gallerypopup hide">
			<div style="padding: 24px;" class="inner">
				<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
				<div id="roomaddtocartsuccesscontent">
				</div>
			</div>
		</div>


		<div id="sizemenu" class="gallerypopup hide">
			<div style="padding: 24px;" class="inner">
				<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
				<?php if(!empty($wdlang['_wd_canvases_title_'])) { ?><div class="pc"><h3><?php print $wdlang['_wd_canvases_title_'];?></h3></div><?php } ?>
				<?php if(!empty($wdlang['_wd_canvases_text_'])) { ?><div class="pc"><?php print $wdlang['_wd_canvases_text_'];?></div><?php } ?>
				<div class="menu">

				<?php $cps = whileSQL("ms_canvas_prints", "*", "ORDER BY cp_order ASC ");
					while($cp = mysqli_fetch_array($cps)) {
						if($cp['cp_price_product'] > 0) { 
							$prod = doSQL("ms_photo_products", "*", "WHERE pp_id='".$cp['cp_price_product']."' ");
							$con = doSQL("ms_photo_products_connect", "*", "WHERE pc_list='".$list['list_id']."' AND pc_prod='".$prod['pp_id']."' ");
							if($con['pc_price'] > 0) { 
								$cp['cp_opt1'] = $con['pc_price'];
							} else { 
								$cp['cp_opt1'] = $prod['pp_price'];
							}
						}
						if(($cp['cp_taxable'] == "1") && ($site_setup['include_vat'] == "1")==true) { 
							$cp['cp_opt1'] = $cp['cp_opt1'] + (($cp['cp_opt1']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt2'] = $cp['cp_opt2'] + (($cp['cp_opt2']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt3'] = $cp['cp_opt3'] + (($cp['cp_opt3']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt4'] = $cp['cp_opt4'] + (($cp['cp_opt4']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt5'] = $cp['cp_opt5'] + (($cp['cp_opt5']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt6'] = $cp['cp_opt6'] + (($cp['cp_opt6']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt7'] = $cp['cp_opt7'] + (($cp['cp_opt7']* $site_setup['include_vat_rate']) / 100);
							$cp['cp_opt8'] = $cp['cp_opt8'] + (($cp['cp_opt8']* $site_setup['include_vat_rate']) / 100);
						}
					
					?>
					<div class="underlinemenu pointer" onclick="selectphotosize('<?php print $cp['cp_width'];?>','<?php print $cp['cp_height'];?>','<?php print $cp['cp_opt1'];?>','<?php print $cp['cp_id'];?>'); return false;">
						<div class="left">
							<?php print $cp['cp_width'] * 1;?> x <?php print $cp['cp_height'] * 1;?> <?php print $cp['cp_name'];?>
						</div>
						<div class="right textright">
							<?php print showPrice($cp['cp_opt1']); ?>
						</div>
						<div class="clear"></div>
					</div>
				<?php } ?>

				</div>
			</div>
		</div>




		<div id="framechoicemenu" class="gallerypopup hide">
			<div style="padding: 24px;" class="inner">
				<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
				<?php if(!empty($wdlang['_wd_frames_title_'])) { ?><div class="pc"><h3><?php print $wdlang['_wd_frames_title_'];?></h3></div><?php } ?>
				<?php if(!empty($wdlang['_wd_frames_text_'])) { ?><div class="pc"><?php print $wdlang['_wd_frames_text_'];?></div><?php } ?>

				<?php $styles = whileSQL("ms_frame_styles","*","ORDER BY style_order ASC ");
				while($style = mysqli_fetch_array($styles)) { 
					$img = doSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC");
					$def = doSQL("ms_frame_sizes", "*", "WHERE frame_style='".$style['style_id']."' AND frame_default='1' ");
					if(empty($img['img_corners'])) { 
						$corners = $style['style_frame_corners'];
					} else { 
						$corners = $img['img_corners'];
					}
					if(($style['style_taxable'] == "1") && ($site_setup['include_vat'] == "1")==true) { 
						$def['frame_price'] = $def['frame_price'] + (($def['frame_price'] * $site_setup['include_vat_rate']) / 100);
					}

					?>
				<div>
					<div class="left p35">
						<div style="padding: 8px;">
						<?php
							if(empty($img['img_corners'])) { 
								$corners = $style['style_frame_corners'];
							} else { 
								$corners = $img['img_corners'];
							}

							$bgsizes = explode(",",$corners);
							?>
							<div><span href=""  onclick="selectframestyle('<?php print $setup['temp_url_folder'].$img['img_small'];?>','<?php print $def['frame_width'];?>','<?php print $def['frame_height'];?>','<?php print $style['style_frame_width'];?>','0','<?php print $corners;?>','<?php print $def['frame_id'];?>','<?php print $style['style_id'];?>','<?php print $img['img_id'];?>','<?php print $def['frame_price'];?>'); return false;"
							style="cursor: pointer; height: 80px; width: 80px; background-image: url('<?php print $setup['temp_url_folder'].$img['img_small'];?>'); background-size: <?php print ((100 / $bgsizes[0]) * 100) / 2;?>%; display: inline-block">
							
							</span>
							</div>
							<div class="clear"></div>
							<?php 
							$colors = whileSQL("ms_frame_images", "*", "WHERE img_style='".$style['style_id']."' ORDER BY img_order ASC ");
							if(mysqli_num_rows($colors) > 1) { 
							?>
							<div  style="margin: 8px 0px;" >
								<div class="inlineli">
								<ul id="frame-colors-<?php print $style['style_id'];?>" style="display: inline; ">
								<?php 
								while($color = mysqli_fetch_array($colors)) {
									if(empty($color['img_corners'])) { 
										$corners = $style['style_frame_corners'];
									} else { 
										$corners = $color['img_corners'];
									}

									$bgsizes = explode(",",$corners);
									if(empty($color['img_corners'])) { 
										$corners = $style['style_frame_corners'];
									} else { 
										$corners = $color['img_corners'];
									}

									?><li ><span  id="framecolor-<?php print $style['style_id'];?>-<?php print $color['img_id'];?>" class="framecolorselections"  onclick="selectframestyle('<?php print $setup['temp_url_folder'].$color['img_small'];?>','<?php print $def['frame_width'];?>','<?php print $def['frame_height'];?>','<?php print $style['style_frame_width'];?>','0','<?php print $corners;?>','<?php print $def['frame_id'];?>','<?php print $style['style_id'];?>','<?php print $color['img_id'];?>','<?php print $def['frame_price'];?>'); return false;"  style="height: 20px; width: 20px; background-image: url('<?php print $setup['temp_url_folder'].$color['img_small'];?>'); background-size: <?php print (100 / $bgsizes[0]) * 100;?>%; display: inline-block"></span></li><?php } ?>
								</ul>
								</div>
								</div>
							<?php } ?>

						</div>
					</div>
					<div class="left p65">
						<div style="padding: 4px;" class="inlineli">
							<div  style="margin: 0px 0px; " class="pc">

					<?php
					$frames = whileSQL("ms_frame_sizes", "*", "WHERE frame_style='".$style['style_id']."' ORDER BY frame_order ASC ");
						while($frame = mysqli_fetch_array($frames)) { 
						if(empty($img['img_corners'])) { 
							$corners = $style['style_frame_corners'];
						} else { 
							$corners = $img['img_corners'];
						}
						
						if(($style['style_taxable'] == "1") && ($site_setup['include_vat'] == "1")==true) { 
							$frame['frame_price'] = $frame['frame_price'] + (($frame['frame_price'] * $site_setup['include_vat_rate']) / 100);
							$frame['frame_mat_price'] = $frame['frame_mat_price'] + (($frame['frame_mat_price'] * $site_setup['include_vat_rate']) / 100);
						}
						?>
						<span style="white-space: nowrap;"><a id="frame-<?php print $style['style_id'];?>-<?php print $frame['frame_id'];?>" class="frameselections" 
						data-mat-width="<?php print $frame['frame_mat_width'];?>" 
						data-frame-width="<?php print $frame['frame_width'];?>" 
						data-frame-height="<?php print $frame['frame_height'];?>" 
						data-frame-price="<?php print $frame['frame_price'];?>" 
						data-frame-mat-price="<?php print $frame['frame_mat_price'];?>" 
						data-mat-print-width="<?php print $frame['frame_mat_print_width'];?>"
						data-mat-print-height="<?php print $frame['frame_mat_print_height'];?>"

						href="" onclick="selectframestyle('<?php print $setup['temp_url_folder'].$img['img_small'];?>','<?php print $frame['frame_width'];?>','<?php print $frame['frame_height'];?>','<?php print $style['style_frame_width'];?>','0','<?php print $corners;?>','<?php print $frame['frame_id'];?>','<?php print $style['style_id'];?>','<?php print $img['img_id'];?>','<?php print $frame['frame_price'];?>'); return false;"><?php print ($frame['frame_width'] + 0);?>x<?php print ($frame['frame_height']+ 0);?></a> &nbsp;</span>
						<?php } ?>
							</div>
							
							<div class="pc"><h3><?php print $style['style_name'];?></h3><?php print $style['style_descr'];?></div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<?php } ?>
		</div>
	</div>

<div id="savedialog" class="gallerypopup hide">
	<div style="padding: 24px;" class="inner">
		<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
		<form name="saveroomvoew" id="saveroomview" onsubmit="return false;">
		<div class="pc"><?php print _wd_save_view_;?></div>
		<div id="saveform">
			<div class="pc center"><input type="text" id="wall_name" name="wall_name" class="field100" value="<?php if(empty($wall['wall_name'])) { print "New Save"; } else {  print $wall['wall_name']; } ?>"></div>
			<div id="saveasoption" class="pc center">
			<input type="radio" name="saveas" id="saveassave" value="save" checked> <label for="saveassave"><?php print _wd_save_;?></label>
			<input type="radio" name="saveas" id="saveasnew" value="new"> <label for="saveasnew"><?php print _wd_save_as_new_;?></label>
			</div>
			<?php if(isset($_SESSION['office_admin_login'])) { ?>
			<div>&nbsp;</div>
			<div class="pc center"><h3>Admin Options</h3></div>
			<div class="pc">These options are only available because you are logged into the admin.</div>
			<div>&nbsp;</div>

			<div class="pc"><input type="checkbox" name="save_as_collection" id="save_as_collection" value="1" <?php if($wall['wall_collection'] == "1") { ?>checked<?php } ?>> <label for="save_as_collection">Save as a Wall Collection</label></div>
			<div class="pc">Saving as a wall collection will make this current view available in the wall collection menu. Only the products will display and not the photos in the menu.</div>
			<div>&nbsp;</div>
			<div class="pc center"><b>If sharing with client</b></div>
			<div class="pc"><input type="checkbox" name="wall_no_edit" id="wall_no_edit" value="1" <?php if($wall['wall_no_edit'] == "1") { ?>checked<?php } ?>> <label for="wall_no_edit">Do not allow editing</label></div>
			<div class="pc">Check this option if you plan on sharing this with a client and you do not want them to be able to make adjustments. You will still be able to make adjustments.</div>
			<div>&nbsp;</div>
			<div class="pc"><input type="checkbox" name="wall_no_price" id="wall_no_price" value="1" <?php if($wall['wall_no_price'] == "1") { ?>checked<?php } ?>> <label for="wall_no_price">Do not show price</label></div>
			<div class="pc">Check this option if you plan on sharing this with a client and you do not want the price to display.</div>
			<?php } ?>
			<div>&nbsp;</div>
			<div class="pc center">
			<input type="hidden" id="wall_id" value="<?php if($wall['wall_cart'] !== "1") { print $wall['wall_link']; } ?>">
			<input type="hidden" id="pid" value="<?php print $_SESSION['pid'];?>">
			<a href="" onclick="saveroom(); return false;" class="checkout"><?php print _wd_save_;?></a>
			</div>
			</div>
		</form>
	</div>
</div>

<div id="saveddialog" class="gallerypopup hide">
	<div style="padding: 24px;" class="inner">
		<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
		<form name="savedroom" id="savedroom" onsubmit="return false;">

		<div id="saveddialongcontent">
			<div class="pc center"><?php print _wd_view_saved_;?></div>
			<div id="collectionsaved" class="pc center hide">This will now be available in the collections menu.</div>
			<div id="wdsharelink">
				<div class="pc center"><?php print _wd_view_saved_text_;?></div>
				<div class="pc center"><a  id="room_link" href="<?php print $setup['url'].$setup['temp_url_folder'];?>/index.php?wd=<?php print $wall['wall_link'];?>"><?php print $setup['url'].$setup['temp_url_folder'];?>/index.php?wd=<?php print $wall['wall_link'];?></a></div>
				<div class="pc center"><a href="" onclick="copylink(); return false;"><?php print _wd_copy_link_;?></a> <span id="linkcopied" class="hide the-icons icon-check"></span></div>
			</div>
			<div>&nbsp;</div>
			<div class="pc center"><a href="" onclick="closesstuffwindow(); return false;" class="checkout"><?php print _wd_close_;?></a></div>
		
		</div>

	</div>
</div>

<div id="myrooms" class="gallerypopup hide">
	<div style="padding: 24px;" class="inner">
		<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closesstuffwindow(); return false;" class="the-icons icon-cancel"></a></div>
		<div id="myroomscontent">

		</div>
	</div>
</div>

<div class="speech-bubble" id="helpbubble">
	<div style="display: block;">
		<div>
		<div style="position: absolute; right: 8px; top: 8px;"><a href=""  onclick="closehelpbubble(); return false;" class="the-icons icon-cancel"></a></div>

		<h3><?php print _wd_instructions_title_;?></h3>
		<?php print _wd_instructions_;?>

		</div>
	</div>
  <div class="arrow bottom "></div>
</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php 
/*
*/
?>