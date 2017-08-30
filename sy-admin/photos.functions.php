<?php	
define("_default_search_text_", "Search tags"); 
$thumbs_per_page = 30;

function showHeldPhotos() { 
	global $setup,$photo_setup;
	if(is_array($_SESSION['heldPhotos'])) { 
		foreach($_SESSION['heldPhotos'] AS $pic_id) { 
			if($pic_id > 0) { 
				$pic = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p ')  AS pic_date_show , date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p')  AS pic_date_taken_show", "WHERE ms_photos.pic_id='".$pic_id."' ");
				if(!empty($pic['pic_id'])) { 

					?>
					<div class="heldMini">
					<div><a href="#image=<?php print $pic['pic_id'];?>" onclick="updateView('tray'); "><img src="<?php print getimagefile($pic,'pic_mini')?>" class="img" style="<?php print "width: ".$photo_setup['mini_size']."px; height: ".$photo_setup['mini_size']."px;"; ?>" ></a></div>
					<div class="heldMiniRemove"><a href="javascript:unSelectPhoto('<?php print $pic['pic_id'];?>');" title="Remove from tray"><?php print ai_delete;?></a></div>
					</div>
				<?php
				}
			}
		}
	}
}


function getSearchString() {
	if((!empty($_REQUEST['keyWord']))AND($_REQUEST['keyWord'] !== ""._default_search_text_."")==true)  { 

		$ck_file = explode(":",$_REQUEST['keyWord']);
		if(trim($ck_file[0]) == "file") { 
			$and_where .= "AND pic_org LIKE '%".trim($ck_file[1])."%' ";
		} else { 
			$keys = explode(" ",$_REQUEST['keyWord']);
			foreach($keys AS $key) { 
				$key = trim($key);
				if($key !== " ") { 
					$and_where .= "AND pic_keywords LIKE '%".$key."%' ";
				}
			}
		}
		// $and_where .= "AND pic_keywords LIKE '%".$_REQUEST['keyWord'].",%' ";
	}
	if($_REQUEST['keyWord']=="untagged") { 
		$and_where = "AND pic_keywords='' ";
	}
	if(!empty($_REQUEST['passcode'])) { 
		$and_where .= "AND LOWER(pic_title) LIKE '%".$_REQUEST['passcode']."%' ";
	}
	if(!empty($_REQUEST['pic_camera_model'])) { 
		$and_where .= "AND pic_camera_model LIKE '%".$_REQUEST['pic_camera_model']."%' ";
	}
	if(!empty($_REQUEST['pic_gal'])) { 
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
	if(!empty($_REQUEST['search_date'])) { 
		if($_REQUEST['search_length'] <= 0) {
			$_REQUEST['search_length'] = 15;
		}
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


function fileInfo($pic,$pic_file) {
	global $tr;
	$pic_filesize= @FileSize("$pic_file"); 
	$sizeL= @GetImageSize("$pic_file"); 
		if(!empty($pic_filesize)) {
			if($pic_filesize > 1024000) {
				$bytes  = $pic_filesize / 1024000;
				$bytes = round($bytes,2). "MB";
			} else {
				$bytes  = $pic_filesize / 1024;
				$bytes = round($bytes,0). "KB";
			}
			$html .=  "$bytes";
			$html .=  " -   $sizeL[0]  X $sizeL[1] px";
		}
	return $html;
}

function showfilesize($pic_filesize) { 
	if($pic_filesize > 1024000) {
		$bytes  = $pic_filesize / 1024000;
		$bytes = round($bytes,2). "MB";
	} else {
		$bytes  = $pic_filesize / 1024;
		$bytes = round($bytes,0). "KB";
	}
	return $bytes;
}


function jsPhotoVariables() { 
	if((!empty($_REQUEST['keyWord']))AND($_REQUEST['keyWord'] !== ""._default_search_text_."")==true)  { 
		$add_string = "keyWord=".htmlspecialchars(stripslashes($_REQUEST['keyWord']));
	}
	if(!empty($_REQUEST['pic_gal'])) {
		$add_string .= "|pic_gal=".$_REQUEST['pic_gal'];
	}
	if(!empty($_REQUEST['orderBy'])) {
		$add_string .= "|orderBy=".$_REQUEST['orderBy'];
	}
	if(!empty($_REQUEST['acdc'])) {
		$add_string .= "|acdc=".$_REQUEST['acdc'];
	}
	if(!empty($_REQUEST['pic_camera_model'])) {
		$add_string .= "|pic_camera_model=".$_REQUEST['pic_camera_model'];
	}
	if(!empty($_REQUEST['pic_upload_session'])) {
		$add_string .= "|pic_upload_session=".$_REQUEST['pic_upload_session'];
	}

	if(!empty($_REQUEST['pic_upload_session'])) {
		$add_string .= "|pic_upload_session=".$_REQUEST['pic_upload_session'];
	}
	if(!empty($_REQUEST['photo_blog'])) {
		$add_string .= "|photo_blog=".$_REQUEST['photo_blog'];
	}
	if(!empty($_REQUEST['untagged'])) { 
		$add_string .= "|pic_keywords=untagged ";
	}
	if(!empty($_REQUEST['view'])) { 
		$add_string .= "|view=".$_REQUEST['view']."";
	}
	if(!empty($_REQUEST['orientation'])) { 
		$add_string .= "|orientation=".$_REQUEST['orientation']."";
	}

	return $add_string;
}

function inlineDataField($type,$size,$rows,$table,$field,$table_id_name,$table_id,$current_value) {
	// $type = text , textarea
	// $size = text size of field
	// $rows = textarea rows
	// $table = database table name
	// $field = name of the field being updated
	// $table_id_name = name of the ID in the table
	// $table_id = the ID numbder of the row
	// $current_value = the current value of the field
	?>

	<?php if($type == "text") { ?>
	<div style="float: left; position: relative;">

		<div>
	<form method="post" name="editphotoinfo-<?php print $table_id."-".$field;?>" id="editphotoinfo-<?php print $table_id."-".$field;?>" action="javascript:saveInlineData('<?php print $field;?>-<?php print $table_id;?>','<?php print $table;?>','<?php print $field;?>','<?php print $table_id_name;?>','<?php print $table_id;?>','<?php print $field;?>-<?php print $table_id;?>-org','<?php print $field;?>-<?php print $table_id;?>-saved');"> 

		<input type="hidden" name="<?php print $field;?>[<?php print $table_id;?>]-org" value="<?php print htmlspecialchars(stripslashes($current_value));?>" id="<?php print $field;?>-<?php print $table_id;?>-org">

		<input type="text" <?php if($size > 2) { ?>style="width: 98%;"<?php } ?>  name="<?php print $field;?>-[<?php print $table_id;?>]" value="<?php print htmlspecialchars(stripslashes($current_value));?>" id="<?php print $field;?>-<?php print $table_id;?>" size="<?php print $size;?>" onfocus="stopArrowNav();"  onblur="saveInlineDataTab('<?php print $field;?>-<?php print $table_id;?>','<?php print $table;?>','<?php print $field;?>','<?php print $table_id_name;?>','<?php print $table_id;?>','<?php print $field;?>-<?php print $table_id;?>-org','<?php print $field;?>-<?php print $table_id;?>-saved');">
		</form>
	</div>
	<div id="<?php print $field;?>-<?php print $table_id;?>-saved" style="position: absolute; top: 0; left: 0; z-index: 5;"></div>
	</div>
	<div class="cssClear"></div>
	<?php } ?>

	<?php if($type == "textarea") { ?>
	<div style="float: left; position: relative;">
		<input type="hidden" name="<?php print $field;?>[<?php print $table_id;?>]-org" value="<?php print htmlspecialchars(stripslashes($current_value));?>" id="<?php print $field;?>-<?php print $table_id;?>-org">

		<textarea style="width: 98%;"  name="<?php print $field;?>-[<?php print $table_id;?>]" cols="<?php print $size;?>" rows="<?php print $rows;?>" id="<?php print $field;?>-<?php print $table_id;?>"  onfocus="stopArrowNav();"  onblur="saveInlineDataTab('<?php print $field;?>-<?php print $table_id;?>','<?php print $table;?>','<?php print $field;?>','<?php print $table_id_name;?>','<?php print $table_id;?>','<?php print $field;?>-<?php print $table_id;?>-org','<?php print $field;?>-<?php print $table_id;?>-saved');"><?php print htmlspecialchars(stripslashes($current_value));?></textarea>
	<div id="<?php print $field;?>-<?php print $table_id;?>-saved" style="position: absolute; top: 0; left: 0; z-index: 5;"></div>
	</form>
	</div>
	<div class="cssClear"></div>
	<?php } ?>

	<?php //How to use:  inlineDataField('text','40','1','ms_photos','pic_keywords','pic_id',$pic['pic_id'],$pic['pic_keywords']); ?>

<?php
}

function inlineDataFieldColors($type,$size,$rows,$table,$field,$table_id_name,$table_id,$current_value,$class,$onchange) {
	// $type = text , textarea
	// $size = text size of field
	// $rows = textarea rows
	// $table = database table name
	// $field = name of the field being updated
	// $table_id_name = name of the ID in the table
	// $table_id = the ID numbder of the row
	// $current_value = the current value of the field
	?>

	<?php if($type == "text") { ?>
	<div style="float: left; position: relative;">

		<div>
	<form method="post" name="editphotoinfo-<?php print $table_id."-".$field;?>" id="editphotoinfo-<?php print $table_id."-".$field;?>" action="javascript:saveInlineData('<?php print $field;?>-<?php print $table_id;?>','<?php print $table;?>','<?php print $field;?>','<?php print $table_id_name;?>','<?php print $table_id;?>','<?php print $field;?>-<?php print $table_id;?>-org','<?php print $field;?>-<?php print $table_id;?>-saved');"> 

		<input type="hidden" name="<?php print $field;?>[<?php print $table_id;?>]-org" value="<?php print htmlspecialchars(stripslashes($current_value));?>" id="<?php print $field;?>-<?php print $table_id;?>-org">

		<input type="text" <?php if($size > 2) { ?>style="width: 98%;"<?php } ?>  class="<?php print $class;?>" onchange="<?php print $onchange;?>" name="<?php print $field;?>-[<?php print $table_id;?>]" value="<?php print htmlspecialchars(stripslashes($current_value));?>" id="<?php print $field;?>-<?php print $table_id;?>" size="<?php print $size;?>" onfocus="stopArrowNav();"  onblur="saveInlineDataTab('<?php print $field;?>-<?php print $table_id;?>','<?php print $table;?>','<?php print $field;?>','<?php print $table_id_name;?>','<?php print $table_id;?>','<?php print $field;?>-<?php print $table_id;?>-org','<?php print $field;?>-<?php print $table_id;?>-saved');">
		</form>
	</div>
	<div id="<?php print $field;?>-<?php print $table_id;?>-saved" style="position: absolute; top: 0; left: 0; z-index: 5;"></div>
	</div>
	<div class="cssClear"></div>
	<?php } ?>


	<?php //How to use:  inlineDataField('text','40','1','ms_photos','pic_keywords','pic_id',$pic['pic_id'],$pic['pic_keywords']); ?>

<?php
}

?>