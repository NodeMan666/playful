<?php
function getSearchString() {
	global $setup,$date,$photo_setup;
	$_REQUEST['keyWord'] = sql_safe($_REQUEST['keyWord']);
	$_REQUEST['kid'] = sql_safe($_REQUEST['kid']);
	$photo_setup = doSQL("ms_photo_setup", "*", "  ");
	if((empty($date['date_id']))&&(!empty($_REQUEST['date_id']))==true) { 
		$date = doSQL("ms_calendar", "date_id,passcode_photos", "WHERE date_id='".$_REQUEST['date_id']."' ");
	}
	if((!empty($_REQUEST['keyWord']))AND($_REQUEST['keyWord'] !== ""._default_search_text_."")==true)  { 

		$ck_file = explode(":",$_REQUEST['keyWord']);
		if(trim($ck_file[0]) == "file") { 
			$and_where .= "AND pic_org LIKE '%".trim($ck_file[1])."%' ";
		} else { 
			$keys = explode(" ",$_REQUEST['keyWord']);
			if(count($keys) == 1) { 
				if($photo_setup['no_search_filename'] == "1") { 
					$and_where .= "AND (pic_keywords LIKE '%".$_REQUEST['keyWord']."%' )";
				} else { 
					$and_where .= "AND (pic_keywords LIKE '%".$_REQUEST['keyWord']."%' OR pic_org LIKE '%".$_REQUEST['keyWord']."%' )";
				}
			} else { 
				foreach($keys AS $key) { 
					$key = trim($key);
					if($key !== " ") { 
						$and_where .= "AND pic_keywords LIKE '%".$key."%' ";
					}
				}
			}
		}
		// $and_where .= "AND pic_keywords LIKE '%".$_REQUEST['keyWord'].",%' ";
	}
	if($_REQUEST['keyWord']=="untagged") { 
		$and_where = "AND pic_keywords='' ";
	}
	if($_REQUEST['kid'] > 0) { 
		$and_where .= "AND key_key_id='".$_REQUEST['kid']."' ";
	}
	if((!empty($_REQUEST['passcode']))&&($date['passcode_photos'] == "1") == true) {
		trim($_REQUEST['passcode']);
		$_REQUEST['passcode'] = sql_safe($_REQUEST['passcode']);

		if($photo_setup['passcode_photos_find'] == "title") { 
			$and_where .= "AND (LOWER(pic_title)='".$_REQUEST['passcode']."' OR pic_title='') ";
		}
		if($photo_setup['passcode_photos_find'] == "file") { 
			$findpass = "-".strtolower(urldecode($_REQUEST['passcode']))."-";
			$group = "-group-";
			$and_where .= " AND (LOWER(pic_org) LIKE '%".$findpass."%' OR LOWER(pic_org) LIKE '%".$group."%' )";
		}
		if($photo_setup['passcode_photos_find'] == "keyword") { 
			$and_where .= " AND (LOWER(pic_keywords)='".$_REQUEST['passcode'].",' OR LOWER(pic_keywords)='".$_REQUEST['passcode']."' OR pic_keywords='' ) ";
		}
		if($photo_setup['passcode_photos_find'] == "filename") { 
			$findpass = strtolower(urldecode($_REQUEST['passcode']));
			$group = "-group-";
			$and_where .= " AND (LOWER(pic_org) LIKE '%".$findpass."%' OR LOWER(pic_org) LIKE '%".$group."%' )";
		}

	}

	if(!empty($_REQUEST['pic_camera_model'])) { 
		$and_where .= "AND pic_camera_model LIKE '%".$_REQUEST['pic_camera_model']."%' ";
	}
	if(!empty($_REQUEST['pic_gal'])) { 
		if(!is_numeric($_REQUEST['pic_gal'])) { die(); } 
		$and_where .= "AND pic_gal='".$_REQUEST['pic_gal']."' ";
	}
	if(!empty($_REQUEST['pic_upload_session'])) { 
		$and_where .= "AND pic_upload_session='".$_REQUEST['pic_upload_session']."' ";
	}
	/*
	if($_REQUEST['pic_client']=="1") { 
		$and_where .= "AND pic_client='1' ";
	} else { 
		$and_where .= "AND pic_client='0' ";
	}
	*/
	if(!empty($_REQUEST['orientation'])) { 
		if($_REQUEST['orientation'] == "portrait") { 
			$and_where .= "AND pic_height>pic_width";
		}
		if($_REQUEST['orientation'] == "landscape") { 
			$and_where .= "AND pic_width>pic_height";
		}
		if($_REQUEST['orientation'] == "square") { 
			$and_where .= "AND (pic_height=pic_width AND pic_height>'0')";
		}

	}
	// $and_where .= "AND pic_id > 1000";

	if(!empty($_REQUEST['search_date'])) { 
		if($_REQUEST['search_length'] <= 0) {
			$_REQUEST['search_length'] = 15;
		}
		$_REQUEST['search_date'] = sql_safe($_REQUEST['search_date']);
		$_REQUEST['search_length'] = sql_safe($_REQUEST['search_length']);
		$_REQUEST['from_time'] = sql_safe($_REQUEST['from_time']);

		$sdate = explode("-",$_REQUEST['search_date']);
		if(!empty($_REQUEST['from_time'])) { 
			$stime = explode(":",$_REQUEST['from_time']);
			$from = date("Y-m-d H:i:00", mktime($stime[0], $stime[1], 0, $sdate[1], $sdate[2], $sdate[0]));
			$to = date("Y-m-d H:i:59", mktime($stime[0], $stime[1]+($_REQUEST['search_length']), 59, $sdate[1], $sdate[2], $sdate[0]));
		} else { 
			$from = date("Y-m-d H:i:00", mktime(0, 0, 0, $sdate[1], $sdate[2], $sdate[0]));
			$to = date("Y-m-d H:i:59", mktime(23, 59, 59, $sdate[1], $sdate[2], $sdate[0]));
		}
		$and_where .= " AND pic_date_taken>='$from' AND pic_date_taken <='$to' ";
		// print "<li>$from - $to ";
	}
	$and_where .= " AND pic_no_dis!='1' ";
	return $and_where;
}

function getSearchOrder() { 
	global $photo_setup;
	$_REQUEST['orderBy'] = sql_safe($_REQUEST['orderBy']);

	if(!empty($_REQUEST['orderBy'])) { 
		$search['orderby'] = "".$_REQUEST['orderBy']."";
	} else { 
		$search['orderby'] = "".$photo_setup['def_all_orderby']."";
	}

	if(!empty($_REQUEST['acdc'])) { 
		$search['acdc'] = "".$_REQUEST['acdc']."";
	} else { 
		$search['acdc'] = "".$photo_setup['def_all_acdc']."";
	}
	return $search;
}

function displayPhoto($pic,$pic_file,$wm,$size,$contain,$cssclass,$nosrc,$add_id,$border_color,$border_size,$bg_color,$bg_use, $position, $containerdisplay,$cat_id,$cat_watermark,$cat_logo,$captionwhere,$date,$free,$sub) { 
	global $setup,$site_setup,$ipad,$startHidden,$list,$mobile;
	if($cat_id <=0) { 
		$cat = doSQL("ms_blog_categories", "*", "WHERE cat_id='".$date['date_cat']."' ");
		$cat_watermark = $cat['cat_watermark'];
		$cat_logo = $cat['cat_logo'];
		$cat_id = $cat['cat_id'];
	}
	if($mobile == 1) { 
		$pic_file = "pic_pic";
	}

	$sytist_store = true;

	
	
	$img = getimagefile($pic,$pic_file);
	$pic['full_url'] = true;
	$thumb = getimagefile($pic,'pic_th');
	$mini = getimagefile($pic,'pic_mini');
	$pic['full_url'] = true;
	$this_pic = getimagefile($pic,$pic_file);


	$html  = "<div id=\"photo-".$add_id."-container\" class=\"photocontainer\" style=\"display: $containerdisplay; position: $position; left: 0; text-align: center;  \"><img id=\"photo-".$add_id."\" pkey=\"".$pic['pic_key']."\" pfid=\"".MD5($pic_file)."\" cid=\"".MD5($cat_id)."\" thumb=\"".$thumb."\"  mini=\"".$mini."\" ";
	if($sytist_store == true) { 
		if(!empty($_SESSION['pid'])) { 
			$person = doSQL("ms_people", "*", "WHERE MD5(p_id)='".$_SESSION['pid']."' ");
			$fav = doSQL("ms_favs", "*", "WHERE fav_pic='".$pic['pic_id']."' AND fav_person='".$person['p_id']."' ");
			if(!empty($fav['fav_id'])) { 
				$html .= "fav=\"1\" ";
			}
		}
		 $pprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$list['list_id']."' AND pp_free!='1' ");
		 $packprods = countIt("ms_photo_products_connect LEFT JOIN ms_photo_products ON ms_photo_products_connect.pc_prod=ms_photo_products.pp_id", "WHERE pc_list='".$list['list_id']."' AND pc_package>'0' ");
		 
		 
		if(($pprods + $packprods) > 0) {
			if($pic['bp_pl'] > 0) { 
				$html .= "pl=\"".$pic['bp_pl']."\" ";
			} else { 
				$html .= "pl=\"".$list['list_id']."\" ";
			}
		 }

		 if((is_array($_SESSION['comparephotos']))&&(in_array($pic['pic_id']."|".$date['date_id']."|".$sub['sub_id'],$_SESSION['comparephotos']))==true) { 
			$html .= "compare=\"1\" ";
		 }
		if(countIt("ms_photo_products_groups", "WHERE group_package='1' AND group_list='".$list['list_id']."' ") > 0) { 
			$html .= "pkgs=\"1\" ";
		}
	}
	$html .= "subid=\"".$sub['sub_id']."\" ";
	$html .= "pos=\"".$position."\" ";
	$html .= "did=\"".$date['date_id']."\" ";
	$html .= "share=\"".$date['photo_social_share']."\" ";
	$html .= "ppos=\"".$add_id."\" ";
	$html .= "sharefile=\"".$this_pic."\" ";

	if(!empty($sub['sub_id'])) { 
		$html .= "pagelink=\"".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/?sub=".$sub['sub_link']."\" ";
		$html .= "pagetitle=\"".htmlspecialchars($date['date_title'])." > ".htmlspecialchars($sub['sub_name'])."\" ";
	} else { 
		$html .= "pagelink=\"".$setup['url']."".$setup['temp_url_folder']."".$setup['content_folder']."".$date['cat_folder']."/".$date['date_link']."/\" ";
		$html .= "pagetitle=\"".htmlspecialchars($date['date_title'])."\" ";
	}
	$html .= "org=\"".htmlspecialchars($pic['pic_org'])."\" ";
	$html .= "fd=\"".$free."\" ";

	if($nosrc !== "1") { 
		$html .= "src=\"".$img."\" ";
		$html .= "thissrc=\"".$img."\" ";
	}	 else {
		$html .= "thissrc=\"".$img."\" ";
	}
	if((!empty($border_color))&&(!empty($border_size))==true) { 
		$border = "border: solid ".$border_size."px ".$border_color.";";
	}
	$html .= "alt=\"".htmlspecialchars($date['date_title']." ".$pic['pic_keywords'])."\" ";
	$html .= "title=\"".htmlspecialchars($pic['pic_title'])."\" class=\"".$cssclass." ".$pic['pic_key']."\" ".$size[3]." style=\"position: relative; margin: auto; "; if($bg_use == "1") { $html .= "border-color: ".$border_color."; background: ".$bg_color .";"; }  if($contain == true) { $html .= "width: 100%; height: auto; max-width: ".$size[0]."px; max-height: ".$size[1]."px;  -ms-interpolation-mode: bicubic; "; }  $html .=" \" ww=\"".$size[0]."\" hh=\"".$size[1]."\" bg_color=\"".$pic['pic_bg_color']."\">";
	if($captionwhere !== "below") { 
		if(((!empty($pic['pic_text']))OR(!empty($pic['pic_title']))) == true) {  
			$html .= "<div id=\"caption-".$pic['pic_id']."\"><div class=\"photocaptioncontainer\" id=\"photo-caption-".$add_id."\"><div class=\"inner\"><h3>".$pic['pic_title']."</h3>".nl2br($pic['pic_text'])."</div></div></div>";
		}
	}
	$html .= "</div>\r\n\r\n";
	if($captionwhere == "below") { 
		if(((!empty($pic['pic_text']))OR(!empty($pic['pic_title']))) == true) {  
			$html .= "<div id=\"caption-".$pic['pic_id']."\"><div class=\"photocaptionbelow\" id=\"photo-caption-".$add_id."\"><div class=\"inner\"><h3>".$pic['pic_title']."</h3>".nl2br($pic['pic_text'])."</div></div></div>";
		}
	}
	return $html;

	/* 
	$pic				The pic photo array
	$pic_file		Which photo size to use
	$wm			The watermark data array
	$size			The GetImageSize() array
	$contain		True or False. To contain the width to the container
	$cssclass	The class of the photo
	*/

}

function billboardSlideShow($bill_id,$width,$height,$name,$id) { 
	global $setup;
	$divid = $name."_".$id;

	print "<div id=\"slideShowDContainer\" style=\"width: ".$width."; height: ".$height."; margin: auto;\">";
	$ssimgs = whileSQL("ms_photos LEFT JOIN ms_billboard_slides ON ms_photos.pic_id=ms_billboard_slides.slide_pic", "*", "WHERE slide_billboard='".$bill_id."' ORDER BY slide_order ASC ");
	while($ssimg = mysqli_fetch_array($ssimgs)) { 
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$ssimg['pic_folder']."/".$ssimg['pic_pic'].""); 

		$ssi++;
		print "<div id=\"".$divid."".$ssi."\" class=\"slideShowDPic\"  style=\"display: none; opacity:0; filter:alpha(opacity=0); width: 100%;margin: auto;\">";
		if(!empty($ssimg['slide_link'])) { 
			print "<a href=\"".$ssimg['slide_link']."\"><img src=\"/".$setup['photos_upload_folder']."/".$ssimg['pic_folder']."/".$ssimg['pic_pic']."\" style=\"border: none;\"></a>";
		} else { 
			print "<img src=\"/".$setup['photos_upload_folder']."/".$ssimg['pic_folder']."/".$ssimg['pic_pic']."\" style=\"border: none;\">";
		}
		print "</div>";
	}
	print "</div>";
}


function imageGetDPI($path) {
    $result = 0;

    // if Path was specified ...
    if(strlen($path)) {

        // ... load enough of the JPEG Header to determine DPI
        $file = @fopen($path, "r");  
        $header = @fread($file, 18);  
        @fclose($file);

        // if image is JPEG/JFIF (APP0) format ...
        if(ord($header[2]) == 0xff && ord($header[3]) == 0xe0) {

            // ... if resolution is DPI ...
            if(ord($header[13]) == 1) { 

                // ... extract the DPIs
                $xdpi = (ord($header[14]) * 256);
                $xdpi += ord($header[15]);
                $ydpi = (ord($header[16]) * 256);
                $ydpi += ord($header[17]);

                // if X and Y DPI are different ...
                if($xdpi != $ydpi)
                    
                    // ... return both DPIs (as array of integer)
                    $result = array($xdpi, $ydpi);

                // ... otherwise, return common DPI (as integer)
                else
                    $result = $xdpi;
            }
        }

        // ... otherwise, if image is JPEG/EXIF (APP1) format ...
        else if(ord($header[2]) == 0xff && ord($header[3]) == 0xe1) { 

            // ... read EXIF data
			if(function_exists(exif_read_data)) { 
				$exif = @exif_read_data($path, "IFD0");

				// if EXIF data exists using DPI ...
				if($exif !== false && $exif['ResolutionUnit'] == 2) {

					// ... extract the DPIs
					list($xdpi) = explode('/', $exif['XResolution']);
					list($ydpi) = explode('/', $exif['YResolution']);

					// if X and Y DPI are different ...
					if($xdpi != $ydpi)

						// ... return both DPIs (as array of integer)
						$result = array((int) $xdpi, (int) $ydpi);

					// ... otherwise, return common DPI (as integer)
					else
						$result = (int) $xdpi;
				}
			}
		}
    }
	$result = str_replace("0000","",$result);


    // return the DPI (OR zero)
    return $result;
}

/**
 * Sets the DPI in the specified JPEG image header.
 * @param $path Path of the JPEG image
 * @param $dpi DPI value(s) to be set
 */
function imageSetDPI($path, $dpi) {

    // if Path and DPI were specified ...
    if(strlen($path) && $dpi !== null) {

        // ... if DPI is an array ...
        if(is_array($dpi)) {

            // ... get X (and possibly Y) DPI
            $xdpi = $ydpi = $dpi[0];
            if(count($dpi) > 1)
                $ydpi = $dpi[1];
        }

        // ... otherwise, use common DPI
        else
            $xdpi = $ydpi = $dpi;

        // if X and Y DPI are numeric ...
        if(is_int($xdpi) && is_int($ydpi)) {
			
            // ... load the JPEG Image
            $size = @filesize($path);
            $image = @file_get_contents($path);

            // if Image data loaded ...
            if(strlen($image) >= 18) {

                // ... if image is JPEG/JFIF (APP0) format ...
                if(ord($image[2]) == 0xff && ord($image[3]) == 0xe0) { 

                    // ... update DPI information in the JPEG header
                    $image[13] = chr(1);
                    $image[14] = chr(floor($xdpi/256));
                    $image[15] = chr($xdpi%256);
                    $image[16] = chr(floor($ydpi/256));
                    $image[17] = chr($ydpi%256);
  				
                    // re-write the Image
                    $file = @fopen($path, "w");
                    @fwrite($file, $image, $size);
                    @fclose($file);
                }
            }
        }
    }
}
?>
