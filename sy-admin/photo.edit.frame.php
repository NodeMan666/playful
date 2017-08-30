<?php
include "../sy-config.php";
session_start();
error_reporting(E_ALL ^ E_NOTICE);
header("Cache-control: private"); 
header("HTTP/1.1 200 OK");
require "../".$setup['inc_folder']."/functions.php"; 
require "admin.functions.php"; 
require("admin.icons.php");
require("photos.functions.php");
$dbcon = dbConnect($setup);
$site_setup = doSQL("ms_settings", "*", "");
date_default_timezone_set(''.$site_setup['time_zone'].'');
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
adminsessionCheck();
if(!is_numeric($_REQUEST['pic_id'])) { die(); } 
?>
<script>
$(document).ready(function(){
	setTimeout(focusForm,200);
 });
 function focusForm() { 
	$("#new_tags").focus();
 }
</script>
<?php

$pic = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p ')  AS pic_date_show , date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%M  %e, %Y %h:%i %p')  AS pic_date_taken_show", "WHERE ms_photos.pic_id='".$_REQUEST['pic_id']."' ");
if(!empty($pic['pic_folder'])) { 
	$pic_folder = $pic['pic_folder'];
} else { 
	$pic_folder = $pic['gal_folder'];
}
// print "<pre>"; print_r($_REQUEST); print "</pre>"; 
if($_REQUEST['saveinfo'] == "yes") { 
	foreach($_REQUEST AS $id => $value) {
		$_REQUEST[$id] = addslashes(stripslashes(urldecode($value)));
	}

	deleteSQL2("ms_photo_keywords_connect", "WHERE key_pic_id='".$pic['pic_id']."' ");
	if(!empty($_REQUEST['e_tags'])) { 
		$etags = explode(",",$_REQUEST['e_tags']);

		foreach($etags AS $id) {
			if(is_numeric($id)) { 
				$tag = doSQL("ms_photo_keywords", "*", "WHERE id='".$id."' ");
				$tag_keys .= $tag['key_word'].", ";
				insertSQL("ms_photo_keywords_connect", "key_key_id='".$id."', key_pic_id='".$pic['pic_id']."' ");
			}
		}
	}
	if(!empty($_REQUEST['new_tags'])) { 
		$new_tags = explode(",",$_REQUEST['new_tags']);
		foreach($new_tags AS $tag) { 
			$tag = trim($tag);
			$tag = strtolower($tag);
			$cktag = doSQL("ms_photo_keywords", "*", "WHERE key_word='".$tag."' ");
			if(empty($cktag['id'])) { 
				$tag_keys .= $tag.", ";

				$id = insertSQL("ms_photo_keywords", "key_word='".addslashes(stripslashes($tag))."' ");
//						createTagFolder($id);
			} else { 
				$id = $cktag['id'];
			}
			$ckcon = doSQL("ms_photo_keywords_connect", "*", "WHERE key_key_id='".$id."' AND key_pic_id='".$pic['pic_id']."' ");
			if(empty($ckcon['id'])) { 
				insertSQL("ms_photo_keywords_connect", "key_key_id='".$id."', key_pic_id='".$pic['pic_id']."' ");
			}
		}
	}
	updateSQL("ms_photos", " pic_title='".$_REQUEST['pic_title']."' ,  pic_keywords='".addslashes(stripslashes($_REQUEST['new_tags']))."',  pic_text='".$_REQUEST['pic_text']."' WHERE pic_id='".$_REQUEST['pic_id']."' ");   		

?>
	<script>
	showSuccessMessage("Information Saved");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php 
exit();
}

if(empty($_REQUEST['submitit'])) { 
?>
<div id="submitinfo"></div>

<div id="photoUpdate"></div>
	<div id="defaultPhotoSelect" style="display: none;"></div>
	<?php 
// print "".$site_setup['default_photo']." == ".$pic['pic_id'];	
if($site_setup['default_photo'] == $pic['pic_id']) { 
	$don = "inline";
	$doff = "none";
	} else { 
	$don = "none";
	$doff = "inline";
	}
	?>

	<div class="underlinelabel"><?php print $pic['pic_org'];?></div>
	<?php
	/*
	include "photo.process.functions.php";
	$size_original = GetImageSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full'],$info); 
	$photo_info = getphotoinfo($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full'],$info,$pic['pic_id']);
	print "<pre>"; print_r($photo_info); print "</pre>";
	*/
	?>
	<form name="login" method="POST" action="photo.edit.frame.php"  onSubmit="return submitPopupForm('photo.edit.frame.php','editinfo');">
	<div class="underline">
		<div class="label">Title</div>
		<div><input type="text" name="pic_title" id="pic_title" class="field100 editinfo" value="<?php  print htmlspecialchars(stripslashes($pic['pic_title']));?>"></div>
	</div>

	<div class="underline">
		<div class="label">Caption</div>
		<div><textarea name="pic_text" id="pic_text" class="field100 editinfo" rows="6"><?php  print htmlspecialchars(stripslashes($pic['pic_text']));?></textarea></div>
	</div>
	<script>
		function showexistingtags() { 
		$("#photoedittags").append('<img src="graphics/loading2.gif">');
			$.get("admin.actions.php?action=photoedittags&pic_id=<?php print $pic['pic_id'];?>", function(data) {
				$("#photoedittags").html("").append(data);
			});

		}


	$(document).ready(function(){
		// showexistingtags();
	});

	</script>
	<div class="underline">
		<div class="label">Existing Tags</div>
		<div>
		<?php 
		$keys= whileSQL("ms_photo_keywords_connect LEFT JOIN ms_photo_keywords ON ms_photo_keywords_connect.key_key_id=ms_photo_keywords.id", "*", "WHERE key_pic_id='".$pic['pic_id']."' ");
		while($key = mysqli_fetch_array($keys)) { 
			if($kc > 0) { print ", "; } print $key['key_word'];
			$kc++;
		} ?>

		</div>
		<div><a href="" onclick="showexistingtags(); return false;">Select from existing tags</a></div>
	</div>
	<div id="photoedittags"></div>

	<div class="underline">
		<div class="label">Create new tags</div>
		<div><input type="text" name="new_tags" id="new_tags" value="" class="editinfo" size="20"  style="width: 98%;"></div>
		<div>Enter in new tags separated with a comma</div>
	</div>

	<div class="underline center">
		<input type="hidden" name="submitit" value="submit" class="editinfo">
		<input type="hidden" name="saveinfo" id="saveinfo" value="yes" class="editinfo">
		<input type="hidden" name="pic_id" id="pic_id" value="<?php print $pic['pic_id'];?>" class="editinfo">
		<input type="submit" name="submit" value="Save Information" class="submit">
	</div>

	</form>

<div>&nbsp;</div>

	<div id="success" style="display: none; opacity: 0; position: absolute; z-index: 10; width: 100%;">CHANGES SAVED</div>
	<?php 
	$blogs = whileSQL("ms_blog_photos LEFT JOIN ms_calendar ON ms_blog_photos.bp_blog=ms_calendar.date_id", "*", "WHERE ms_blog_photos.bp_pic='".$pic['pic_id']."' AND ms_calendar.date_type='news' ");
	if(mysqli_num_rows($blogs) > 0) {  ?>
	<div class="pageContent"><span class="bold">Shown on<br></span>
	<?php
		while($blog = mysqli_fetch_array($blogs)) { 
		$c++; ?>
		<?php if($c > 1) { print "<br>"; } ?><a href="index.php?do=news&action=managePhotos&date_id=<?php print $blog['date_id'];?>"><?php print $blog['date_title'];?></a> 
		<?php if($blog['bp_sub'] > 0) { 
			$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$blog['bp_sub']."' ");
			?><br> -> Sub Gallery: <a href="index.php?do=news&action=managePhotos&date_id=<?php print $blog['date_id'];?>&sub_id=<?php print $blog['bp_sub'];?>"><?php print $sub['sub_name'];?></a><?php } ?>
		<?php } ?>
		</div>
		<?php } ?>

	<?php if(!empty($pic['pic_upload_session'])) { ?>
	<div>
		<div class="pageContent" style="width: 30%; float: left;">Upload Session</div>
		<div class="pageContent" style="width: 60%; float: left;"><a href="index.php?do=allPhotos&pic_upload_session=<?php print $pic['pic_upload_session'];?>&acdc=ASC&pic_client=<?php print $pic['pic_client'];?>"><?php print $pic['pic_upload_session'];?></a></div>
		<div class="cssClear"></div>
	</div>
	<?php } ?>
	<div>
		<div class="pageContent" style="width: 30%; float: left;">Date Taken</div>
		<div class="pageContent" style="width: 60%; float: left;"><?php if(empty($pic['pic_date_taken_show'])) { print "<span class=\"muted\">unknown</span>"; } else { print $pic['pic_date_taken_show']; } ?></div>
		<div class="cssClear"></div>
	</div>

	<div>
		<div class="pageContent" style="width: 30%; float: left;">Date Uploaded</div>
		<div class="pageContent" style="width: 60%; float: left;"><?php if(empty($pic['pic_date_show'])) { print "<span class=\"muted\">unknown</span>"; } else { print $pic['pic_date_show']; } ?></div>
		<div class="cssClear"></div>
	</div>
	
	
	<div>
		<div class="pageContent" style="width: 30%; float: left;">Camera Model</div>
		<div class="pageContent" style="width: 60%; float: left;"><?php if(empty($pic['pic_camera_model'])) { print "<span class=\"muted\">unknown</span>"; } else { ?>
		<a href="index.php?do=allPhotos&pic_camera_model=<?php print $pic['pic_camera_model'];?>&acdc=ASC"><?php print $pic['pic_camera']." - ".$pic['pic_camera_model']; } ?></a></div>
		<div class="cssClear"></div>
	</div>





<?php

// GET NEXT / PREVIOUS PHOTOS

$and_where = getSearchString();
$search = getSearchOrder();
$and_where .= " AND  pic_no_dis='0' ";
// print "<li>XXXXXXXx $and_where";
// print "<pre>"; print_r($_REQUEST); print "</pre>";

if($search['acdc'] == "ASC") { 
	$next_acdc = "DESC";
	$next_a = "<";
	$prev_acdc = "ASC";
	$prev_a = ">";
} else {
	$next_acdc = "ASC";
	$next_a = ">";
	$prev_acdc = "DESC";
	$prev_a = "<";
}
$order_field = $search['orderby'];

if($_REQUEST['view'] == "tray") { 

	print "<div id=\"bluenotice\" style=\"text-align:center;\">You are viewing  photos held in tray.</div>";

	$this_pic = array_search($pic['pic_id'],$_SESSION['heldPhotos']);
	$pp = $this_pic+1;
	$np = $this_pic-1;
	if(!empty($_SESSION['heldPhotos'][$np])) { 
		$next = doSQL("ms_photos", "*", "WHERE pic_id='".$_SESSION['heldPhotos'][$np]."' ");
	}
	if(!empty($_SESSION['heldPhotos'][$pp])) { 
		$prev = doSQL("ms_photos", "*", "WHERE pic_id='".$_SESSION['heldPhotos'][$pp]."' ");
	}
} elseif(!empty($_REQUEST['date_id'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
	$blog_pic = doSQL("ms_blog_photos", "*", "WHERE bp_pic='".$_REQUEST['pic_id']."' AND bp_blog='".$date['date_id']."'  ");

	$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."'";

	if(!empty($date['date_photo_keywords'])) { 
		$and_date_tag = "( ";
		$date_tags = explode(",",$date['date_photo_keywords']);
		foreach($date_tags AS $tag) { 
			$cx++;
			if($cx > 1) { 
				$and_date_tag .= " OR ";
			}
			$and_date_tag .=" key_key_id='$tag' ";
		}
		$and_date_tag .= " OR bp_blog='".$_REQUEST['date_id']."' ";
		$and_date_tag .= " ) ";

		$pics_where = "WHERE $and_date_tag $and_where ";
		$pics_tables = "ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic ";
		$pics_orderby = $date['date_photos_keys_orderby']; 
		$pics_acdc = $date['date_photos_keys_acdc'];

		if($pics_acdc == "ASC") { 
			$pp = "< ";
			$np = ">";
		} else { 
			$pp = "> ";
			$np = "<";
		}
		if($pics_acdc == "ASC") { 
			$prev_orderby = "DESC";
		} else { 
			$prev_orderby = "ASC";
		}
		$next = doSQL("$pics_tables", "*", "$pics_where $and_where AND $pics_orderby $pp'".addslashes(stripslashes($pic[$pics_orderby ]))."' ORDER BY $pics_orderby $prev_orderby LIMIT 1 ");
		$prev = doSQL("$pics_tables", "*", "$pics_where $and_where AND $pics_orderby $np'".addslashes(stripslashes($pic[$pics_orderby ]))."' ORDER BY $pics_orderby $pics_acdc LIMIT 1 ");
	} else { 

		if($_REQUEST['view'] == "photographerfavs") { 
			$fav_where .= " AND pic_fav_admin='1' ";
			if($_REQUEST['sub_id'] <= 0) {
				$and_sub = "";
			}
			$next = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $fav_where AND pic_org<'".$pic['pic_org']."' ORDER BY pic_org DESC LIMIT 1 ");
			$prev = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $fav_where AND pic_org>'".$pic['pic_org']."' ORDER BY pic_org ASC LIMIT 1 ");
		} else { 
			$next = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $fav_where AND bp_order<'".$blog_pic['bp_order']."' ORDER BY ms_blog_photos.bp_order DESC LIMIT 1 ");
			$prev = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $fav_where AND bp_order>'".$blog_pic['bp_order']."' ORDER BY ms_blog_photos.bp_order ASC LIMIT 1 ");
		}
	}

} elseif(!empty($_REQUEST['p_id'])) { 
	$blog_pic = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id ", "*", "WHERE fav_pic='".$_REQUEST['pic_id']."' ");

	$next = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0' AND fav_id<'".$blog_pic['fav_id']."' ORDER BY fav_id DESC LIMIT 1 ");
	$prev = doSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0' AND fav_id>'".$blog_pic['fav_id']."' ORDER BY fav_id ASC LIMIT 1 ");


} elseif($_REQUEST['view'] == "unblogged") { 
	print "<div id=\"bluenotice\" style=\"text-align:center;\">You are viewing  photos not shown on website.</div>";

	$next = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND  pic_no_dis='0'  AND pic_client='".$_REQUEST['pic_client']."' AND ms_photos.".$search['orderby']."".$next_a."'".$pic[$search['orderby']]."' ORDER BY ".$search['orderby']." $next_acdc  LIMIT 1 ");
	$prev = doSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND  pic_no_dis='0'  AND pic_client='".$_REQUEST['pic_client']."' AND ms_photos.".$search['orderby']."".$prev_a."'".$pic[$search['orderby']]."'  ORDER BY ".$search['orderby']." $prev_acdc  LIMIT 1 ");
} elseif(!empty($_REQUEST['key_id'])) { 
	$next = doSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."'  $and_where  AND ms_photos.".$search['orderby']."".$next_a."'".$pic[$search['orderby']]."' ORDER BY ".$search['orderby']." $next_acdc  LIMIT 1 ");
	$prev = doSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."'  $and_where  AND ms_photos.".$search['orderby']."".$prev_a."'".$pic[$search['orderby']]."'  ORDER BY ".$search['orderby']." $prev_acdc  LIMIT 1 ");


} else {

	$next = doSQL("ms_photos", "*", "WHERE pic_id>'0' $and_where  AND ms_photos.".$search['orderby']."".$next_a."'".$pic[$search['orderby']]."' ORDER BY ".$search['orderby']." $next_acdc  LIMIT 1 ");
	$prev = doSQL("ms_photos", "*", "WHERE pic_id>'0' $and_where  AND ms_photos.".$search['orderby']."".$prev_a."'".$pic[$search['orderby']]."'  ORDER BY ".$search['orderby']." $prev_acdc  LIMIT 1 ");
}
?>

<div class="pageContent" style="text-align: center;">
	<?php 
		if(is_array($_SESSION['heldPhotos'])) { 
			if(in_array($pic['pic_id'],$_SESSION['heldPhotos'])) { $add = "none"; $remove = "inline"; } else { $add = "inline"; $remove = "none";  } 
		} else { 
			$add = "inline"; $remove = "none";
		}
		?>

		<span id="select-photo-<?php print $pic['pic_id'];?>-large" style="display: <?php print $add;?>;"><a href="javascript:selectPhoto('<?php print $pic['pic_id'];?>','1');" title="Click to hold in tray"><?php print ai_checkbox_off;?></a></span>
		<span id="select-photo-<?php print $pic['pic_id'];?>-on-large"  style="display: <?php print $remove;?>;"><a href="javascript:unSelectPhoto('<?php print $pic['pic_id'];?>','1');"  title="Click to remove from tray"><?php print ai_checkbox_on;?></a></span>

</div>

<div><a href="javascript:deletePhoto('<?php print $pic['pic_id'];?>','close');"  onClick="return confirm('Are you sure you want to delete this photo?\r\nThis will permanently delete this from your system and from any blog posts!! '); return false;"><?php print ai_delete;?> delete photo</a></div>

<div>&nbsp;</div>
	<div class="pc">
		<span id="default-photo-<?php print $pic['pic_id'];?>" style="display: <?php print $doff;?>;"><a href="javascript:defaultPhoto('<?php print $pic['pic_id'];?>','1');" title="Make Default Photo"   onClick="return confirm('Are you sure you want to make this your default photo?\r\nThis is the photo that will show in the background of full screen theme pages when no other photo is chosen. '); return false;"><?php print ai_checkbox_off;?></a></span>
		<span id="default-photo-<?php print $pic['pic_id'];?>-on"  style="display: <?php print $don;?>;"><?php print ai_checkbox_on;?></span> <span class="bold">Default Background Photo </span>
	</div>



<?php if(!empty($next['pic_id'])) { 	?>
<div  id="prevPhotoInfo" pid="<?php print $next['pic_id'];?>">
	<div style="display: none;"><img src="<?php print getimagefile($next,'pic_large');?>"></div>
	<div class="clear"></div>
</div>
<?php  } else { ?>
<div id="prevPhotoInfo" pid="0"></div>
<?php } ?>
<?php if(!empty($prev['pic_id'])) {  ?>
<div id="nextPhotoInfo" pid="<?php print $prev['pic_id'];?>" >
	<div style="display: none;"><img src="<?php print getimagefile($prev,'pic_large');?>"></div>
</div>

<?php  } else {  ?>
<div id="nextPhotoInfo" pid="0"></div>
<?php } ?>
<div class="cssClear"></div>
<div>&nbsp;</div>



<div class="pageContent"><h3>Photo Files</h3></div>
<?php if(!empty($pic['pic_full'])) { ?>
	<div class="pageContent" style="width: 32%; float: left;">
		<a href="<?php print getimagefile($pic,'pic_full') ?>" target="_blank"><?php print ai_new_window;?>Original</a></div>
		<div class="pageContent" style="width: 60%; float: left;">
		
			<?php 
			if($pic['pic_filesize'] > 0) { 
				print showfilesize($pic['pic_filesize'])." - ".$pic['pic_width']." x ".$pic['pic_height'];
			} else { 
				print fileInfo($pic,$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_full']);
			}
			?>
		</div>
	<div class="cssClear"></div>


</div>
<?php } ?>

<?php if(!empty($pic['pic_large'])) { ?>
	<div class="pageContent" style="width: 32%; float: left;">
		<a href="<?php print getimagefile($pic,'pic_large');?>" target="_blank"><?php print ai_new_window;?>Large</a></div>
		<div class="pageContent" style="width: 60%; float: left;">
		
			<?php 
			if($pic['pic_filesize_large'] > 0) { 
				print showfilesize($pic['pic_filesize_large'])." - ".$pic['pic_large_width']." x ".$pic['pic_large_height'];
			} else { 
				print fileInfo($pic,$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_large']);
			}
			?>
		</div>
	<div class="cssClear"></div>
<?php } ?>

	<div class="pageContent" style="width: 32%; float: left;">
		<a href="<?php print getimagefile($pic,'pic_pic');?>" target="_blank"><?php print ai_new_window;?>Small</a></div>
		<div class="pageContent" style="width: 60%; float: left;">
		
			<?php 
			if($pic['pic_filesize_large'] > 0) { 
				print showfilesize($pic['pic_filesize_small'])." - ".$pic['pic_small_width']." x ".$pic['pic_small_height'];
			} else { 
				print fileInfo($pic,$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_pic']);
			}
			?>
		</div>
	<div class="cssClear"></div>

<!-- 
	<div class="pageContent" style="width: 32%; float: left;">
		<a href="<?php print "".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'];?>" target="_blank"><?php print ai_new_window;?>Thumb</a>
	</div>
		<div class="pageContent" style="width: 60%; float: left;">	
			<?php 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'].""); 
			print fileInfo($pic,$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th']);
			?>
		</div>

	<div class="cssClear"></div>

<div>
	<div class="pageContent" style="width: 32%; float: left;">
		<a href="<?php print "".$setup['temp_url_folder']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini'];?>" target="_blank"><?php print ai_new_window;?>Mini</a></div>
		<div class="pageContent" style="width: 60%; float: left;">
		
			<?php 
			$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini'].""); 
			print fileInfo($pic,$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_mini']);
			?>
		</div>
	<div class="cssClear"></div>

-->

<div class="pc">Key: <?php print $pic['pic_key'];?></div>

<?php } //end if empty submitit ?>
