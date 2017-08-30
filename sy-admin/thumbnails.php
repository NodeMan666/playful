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
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
date_default_timezone_set(''.$site_setup['time_zone'].'');

adminsessionCheck();
?>
 <script>
$(document).ready(function(){
	 mytips(".tip","tooltip");
	 myinputtips(".inputtip","tooltip");
	 dpmenu();
	$(".confirmdeleteoptionsthumbs").click(function() { 
		$("#confirm-options-title").html($(this).attr("confirm-title"));
		$("#confirm-options-message").html($(this).attr("confirm-message"));
		$("#option-link-1").attr("href",$(this).attr("option-link-1"));
		$("#option-link-1-text").html($(this).attr("option-link-1-text"));
		$("#option-link-2").attr("href",$(this).attr("option-link-2"));
		$("#option-link-2-text").html($(this).attr("option-link-2-text"));

		$("#pagewindowbgcontainer").fadeIn(100, function() { 
				$("#confirmdeleteoptions").css({"display":"none", "visibility":"visible", "top":"300px", "z-index":"500"});
				$("#confirmdeleteoptions").fadeIn(100);
		});

		return false;
	});


});

function addToFavs(pic_id,date_id,sub_id) { 

	$.get("admin.actions.php?action=addToFavs&pic_id="+pic_id+"&date_id="+date_id+"&sub_id="+sub_id, function(data) {
		if(data == "removed") { 
			$("#fav-pic-on-"+pic_id).hide();
			$("#fav-pic-off-"+pic_id).show();
			$("#pn-container-"+pic_id).removeClass("photoNameContainerBorderFav").addClass("photoNameContainerBorder");
		}
		if(data == "added") { 
			$("#fav-pic-on-"+pic_id).show();
			$("#fav-pic-off-"+pic_id).hide();
			$("#pn-container-"+pic_id).removeClass("photoNameContainerBorder").addClass("photoNameContainerBorderFav");
		}
		getFavCount(pic_id,date_id,sub_id);
	});

}
function getFavCount(pic_id,date_id,sub_id) { 
	$.get("admin.actions.php?action=getFavCount&pic_id="+pic_id+"&date_id="+date_id+"&sub_id="+sub_id, function(data) {
		if(data > 0) { 
			$("#photogfavs").html(data);
			$("#photographerfavs").show();
		} else { 
			$("#photogfavs").html(data);
			$("#photographerfavs").hide();
		}
	});
}
</script>


<?php
 // print "<pre>"; print_r($_REQUEST); print "</pre>";
$starttime = microtime(true);

$and_where = getSearchString();
$search = getSearchOrder();
 // print "<li>".$and_where;
// print "<pre>"; print_r($_REQUEST); print "</pre>";
$pics_array = array();
if(empty($_REQUEST['page'])) { 
	$page = 1;
} else {
	$page = $_REQUEST['page'];
}
$per_page = $thumbs_per_page;
$sq_page = $page * $per_page - $per_page;
$and_where .= " AND pic_no_dis='0' ";
$and_sub = "AND bp_sub='".$_REQUEST['sub_id']."' ";
if($_REQUEST['view'] == "unblogged") { 
	$piccount = whileSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND pic_no_dis='0' AND pic_client='".$_REQUEST['pic_client']."' " );
	$pic_field = whileSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND pic_no_dis='0'  AND pic_client='".$_REQUEST['pic_client']."' ORDER BY ".$search['orderby']." ".$search['acdc']."  LIMIT $sq_page,$per_page");
} elseif(!empty($_REQUEST['date_id'])) { 
	$date = doSQL("ms_calendar LEFT JOIN ms_blog_categories ON ms_calendar.date_cat=ms_blog_categories.cat_id", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");

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

		$piccount = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']." ");
		$pic_field = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']."   LIMIT $sq_page,$per_page");

	} else { 
		if($_REQUEST['view'] == "photographerfavs") { 
			$and_where .= "AND pic_fav_admin='1' ";
			if(empty($_REQUEST['sub_id'])) { 
				$and_sub = "";
			}
		}
		/* HERE print "<h1>XXXXXXXXXXXXXX</h1>"; */
		$piccount = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_pic,ms_blog_photos.bp_order,ms_blog_photos.bp_blog,ms_blog_photos.bp_sub,ms_photos.pic_id,ms_photos.pic_no_dis,ms_photos.pic_hide", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $and_where ORDER BY bp_order ASC");
		$pic_field = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_pic,ms_blog_photos.bp_order,ms_blog_photos.bp_blog,ms_blog_photos.bp_sub,ms_photos.pic_id,ms_photos.pic_no_dis,ms_photos.pic_hide", "WHERE bp_blog='".$_REQUEST['date_id']."' $and_sub $and_where  ORDER BY bp_order ASC   LIMIT $sq_page,$per_page");
	}
} elseif(!empty($_REQUEST['p_id'])) { 
	$piccount = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0'   ORDER BY pic_org ASC");
	$pic_field = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0'  ORDER BY pic_org ASC LIMIT $sq_page,$per_page");
} elseif($_REQUEST['view'] == "favorites") { 
	$piccount = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE  pic_id>'0'  ORDER BY fav_id ASC");
	$pic_field = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE  pic_id>'0'   ORDER BY fav_id ASC LIMIT $sq_page,$per_page");

} elseif(!empty($_REQUEST['key_id'])) { 
	$piccount = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."' AND pic_client='".$_REQUEST['pic_client']."' ORDER BY pic_order DESC ");
	$pic_field = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."' AND pic_client='".$_REQUEST['pic_client']."' ORDER BY pic_order DESC   LIMIT $sq_page,$per_page");

} else  { 
	$piccount = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where  ORDER BY ".$search['orderby']." ".$search['acdc']." $and_acdc");
	$pic_field = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where ORDER BY ".$search['orderby']." ".$search['acdc']."  LIMIT $sq_page,$per_page");
}

$total_results = mysqli_num_rows($piccount);

while($ppic = mysqli_fetch_array($pic_field)) { 
	$add_val .= $ppic['pic_id'].",";
}
?>
<?php
if($_REQUEST['view'] == "unblogged") { 
	$pics= whileSQL("ms_photos LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE ms_blog_photos.bp_pic IS NULL AND  pic_no_dis='0'  AND pic_client='".$_REQUEST['pic_client']."' ORDER BY ".$search['orderby']." ".$search['acdc']."  LIMIT $sq_page,$per_page");
} elseif(!empty($_REQUEST['date_id'])) { 
	if(!empty($date['date_photo_keywords'])) { 
		$pics = whileSQL("ms_photos LEFT JOIN ms_photo_keywords_connect  ON ms_photos.pic_id=ms_photo_keywords_connect.key_pic_id  LEFT JOIN ms_blog_photos ON ms_photos.pic_id=ms_blog_photos.bp_pic", "*", "WHERE $and_date_tag $and_where GROUP BY pic_id ORDER BY ".$date['date_photos_keys_orderby']." ".$date['date_photos_keys_acdc']."   LIMIT $sq_page,$per_page");
	} else { 
		if($_REQUEST['view'] == "photographerfavs") { 
			$and_where .= "AND pic_fav_admin='1' ";
			if(empty($_REQUEST['sub_id'])) { 
				$and_sub = "";
			}
			$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $and_where GROUP BY pic_id ORDER BY pic_org ASC   LIMIT $sq_page,$per_page");
		} else {  
		 /* print "<h1>HERE  AAAAAAAAAAAAA</h1>";   */

			$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "ms_blog_photos.bp_pic,ms_blog_photos.bp_order,ms_blog_photos.bp_blog,ms_blog_photos.bp_sub,ms_photos.pic_id,ms_photos.pic_no_dis,ms_photos.pic_hide,ms_blog_photos.bp_id,ms_blog_photos.bp_pl", "WHERE bp_blog='".$_REQUEST['date_id']."'  $and_sub $and_where GROUP BY pic_id ORDER BY bp_order ASC   LIMIT $sq_page,$per_page");
		}
	}
} elseif(!empty($_REQUEST['p_id'])) { 
	$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id", "*", "WHERE fav_person='".$_REQUEST['p_id']."'  AND pic_id>'0'  ORDER BY pic_org ASC LIMIT $sq_page,$per_page");
} elseif($_REQUEST['view'] == "favorites") { 

	$pics = whileSQL("ms_favs  LEFT JOIN ms_photos ON ms_favs.fav_pic=ms_photos.pic_id  LEFT JOIN ms_calendar ON ms_favs.fav_date_id=ms_calendar.date_id LEFT JOIN ms_people ON ms_favs.fav_person=ms_people.p_id", "*", "WHERE  pic_id>'0'  ORDER BY fav_id DESC LIMIT $sq_page,$per_page");
	$no_link = true;
} elseif(!empty($_REQUEST['key_id'])) { 
	$pics = whileSQL("ms_photo_keywords_connect  LEFT JOIN ms_photos ON ms_photo_keywords_connect.key_pic_id=ms_photos.pic_id", "*", "WHERE key_key_id='".$_REQUEST['key_id']."'   AND pic_client='".$_REQUEST['pic_client']."'  ORDER BY ".$search['orderby']." ".$search['acdc']."   LIMIT $sq_page,$per_page");
} else {
	$pics = whileSQL("ms_photos", "*",   "WHERE pic_id>'0' $and_where ORDER BY ".$search['orderby']." ".$search['acdc']."  LIMIT $sq_page,$per_page");
}


$endtime = microtime(true);
$duration = $endtime - $starttime;
// print "<h1>Time: ".$duration."</h1>";

while($bpic = mysqli_fetch_array($pics)) { 
	$pic = doSQL("ms_photos","*","WHERE pic_id='".$bpic['pic_id']."' ");
	$pic['bp_id'] = $bpic['bp_id'];
	$pic['bp_pl'] = $bpic['bp_pl'];
	$pic['p_last_name'] = $bpic['p_last_name'];
	$pic['p_name'] = $bpic['p_name'];
	$pic['p_id'] = $bpic['p_id'];

if(!empty($pic['pic_folder'])) { 
		$pic_folder = $pic['pic_folder'];
	} else { 
		$pic_folder = $pic['gal_folder'];
	}

	if($pic['pic_th_width'] > 0) { 
		$size[0] = $pic['pic_th_width'];
		$size[1] = $pic['pic_th_height'];
	} else { 
		$size = @GetImageSize("".$setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'].""); 
	}



	if($size[0] > $max_width) { $max_width = $size[0]; } 
	if($size[1] > $max_height) { $max_height = $size[1]; } 
	array_push($pics_array,$pic);

	if($max_height > 200) { 
		$max_height = 200;
	}
	if($max_width> 200) { 
		$max_width= 200;
	}

	?>

	


<?php
}
?>
<?php
$picnum = $page * $per_page; 
foreach($pics_array AS $pic) { 
	if(!empty($pic['pic_folder'])) { 
		$pic_folder = $pic['pic_folder'];
	} else { 
		$pic_folder = $pic['gal_folder'];
	}
	$picnum++;
	if($pic['pic_th_width'] > 0) { 
		$size[0] = $pic['pic_th_width'];
		$size[1] = $pic['pic_th_height'];
	} else { 
		$size = getimagefiledems($pic,'pic_th');
	}


	if($date['cat_type'] == "proofing") { 
		/* #### PROOFING PHOTOS ######## */

		?>
		<div style="border-bottom: solid 1px #d4d4d4; padding: 8px;" id="photoThumb-<?php print $pic['pic_id'];?>">
			<div class="left" style=" width: 35%;">


			
			<a href="#image=<?php print $pic['pic_id'];?>">
			
			<img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail <?php if($pic['pic_hide'] == "1") { ?>hiddenphoto<?php } ?>" style="cursor: pointer; bottom: 0; <?php if($size[1] > $max_height) { print "height: ".$max_height."px; width: auto;"; } ?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>"></a>
			<?php if(empty($_REQUEST['p_id'])) { ?>
			<div class="photoNameContainer">
			<?php 
				if(is_array($_SESSION['heldPhotos'])) { 
					if(in_array($pic['pic_id'],$_SESSION['heldPhotos'])) { $add = "none"; $remove = "inline"; } else { $add = "inline"; $remove = "none";  } 
				} else { 
					$add = "inline"; $remove = "none";
				}
				?>
				<span id="<?php print $picnum;?>-pic" pic_id="<?php print $pic['pic_id'];?>"></span><span id="select-photo-<?php print $pic['pic_id'];?>" style="display: <?php print $add;?>; cursor: pointer;" onClick="javascript:selectPhoto('<?php print $pic['pic_id'];?>','0', '<?php print $picnum;?>');" title="Click to hold in tray" class="tip"><?php print ai_checkbox_off;?></span>
				<span id="select-photo-<?php print $pic['pic_id'];?>-on"  style="display: <?php print $remove;?>;cursor: pointer;" onClick="javascript:unSelectPhoto('<?php print $pic['pic_id'];?>','0','<?php print $picnum;?>');"  title="Click to remove from tray" class="tip"><?php print ai_checkbox_on;?></span>

				<?php 
					if(!empty($_REQUEST['date_id'])) { ?>
						<a href="javascript:makePreviewPhoto('<?php print $pic['pic_id'];?>','<?php print $date['date_id'];?>', '<?php print $_REQUEST['sub_id'];?>');" title="Make this photo the preview" class="tip"><?php print ai_photo;?></a> 
						<!-- <a href="javascript:photoColors('<?php print $pic['pic_id'];?>');" title="Adjust Background & Border Colors" class="tip"><?php print ai_photo_colors;?></a> -->
						
						<a  href="" id="ppic-<?php print $pic['pic_id']?>" class="confirmdeleteoptionsthumbs" confirm-title="Delete Photo" confirm-message="Select from the options below" option-link-1="javascript:removePhotoFromBlog('<?php print $pic['pic_id'];?>','<?php print $pic['bp_id'];?>');" option-link-1-text="Remove photo from this gallery but leave in the system"  option-link-2="javascript:deletePhoto('<?php print $pic['pic_id'];?>');" option-link-2-text="Delete photo completely"><?php print ai_delete;?></a>
					<?php } else { ?>
				<!-- <a href="javascript:photoColors('<?php print $pic['pic_id'];?>');" title="Adjust Background & Border Colors" class="tip"><?php print ai_photo_colors;?></a>  -->
				<a href="javascript:deletePhoto('<?php print $pic['pic_id'];?>');"  onClick="return confirm('Are you sure you want to delete this?'); return false;" title="Delete" class="tip"><?php print ai_delete;?></a>

				<?php } ?> 
				<?php if($pic['pic_client'] == "1") { print "<span class=\"tip\" title=\"Private Photo\">".ai_lock."</span>"; } ?>
			</div>
			<?php } ?>
			<div><span class="tip" title="<?php print $pic['pic_org'];?>"><?php print $pic['pic_org'];?></span></div>
			<div><span>ID: <?php print $pic['pic_id'];?></span></div>
			 <?php if(($date['passcode_photos'] == "1")&&(!empty($pic['pic_title'])) == true) { ?><div>Passcode: <?php print $pic['pic_title'];?></div><?php } ?>
			</div>

			<div class="left" style="text-align: left; width: 65%;">

			<?php 
			unset($p);
			$ck = doSQL("ms_proofing", "*, date_format(proof_date, '".$site_setup['date_format']." %h:%i %p')  AS proof_date", "WHERE proof_date_id='".$date['date_id']."' AND proof_pic_id='".$pic['pic_id']."' ");  
			if(!empty($ck['proof_id'])) { 
				$p = doSQL("ms_people", "*", "WHERE p_id='".$ck['proof_person']."' ");
			}
			if(empty($ck['proof_id'])) { ?><div class="pc"><h3>Unreviewed</h3></div><?php } ?>
			<?php if($ck['proof_status']== "1") { ?><div class="pc"><h3 class="success">Approved</h3></div><?php } ?>
			<?php if($ck['proof_status']== "3") { ?><div class="pc"><h3 class="error">Rejected</h3></div><?php } ?>

			<?php if($ck['proof_status']== "2") { ?><div class="pc"><h3 class="revise">Revision Requested</h3></div>
			<div class="pc"><?php print ai_message;?> <?php print nl2br($ck['proof_comment']);?></div>
			<?php } ?>
			<?php if(!empty($p['p_id'])) { ?>
			<div class="pc">By <a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><b><?php print $p['p_name']." ".$p['p_last_name']."</b></a> on ".$ck['proof_date'];?></div>
			<?php } ?>
			<div class="pc"><a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>&replace=<?php print $pic['pic_id'];?>'); return false;" class="tip" title="Upload Photos">Upload revised file</a></div>
			<?php $revs = whileSQL("ms_proofing_revisions LEFT JOIN ms_proofing ON ms_proofing_revisions.rev_prior_pic=ms_proofing.proof_pic_id", "*, date_format(proof_date, '".$site_setup['date_format']." %h:%i %p')  AS proof_date", "WHERE rev_this_pic='".$pic['pic_id']."' AND rev_date_id='".$date['date_id']."' ORDER BY rev_id DESC ");
			if(mysqli_num_rows($revs) > 0) { ?>
			<div class="pc">Previous files</div>
			<?php } 
			while($rev = mysqli_fetch_array($revs)) { 
				$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$rev['rev_prior_pic']."' ");
				$p = doSQL("ms_people", "*", "WHERE p_id='".$rev['proof_person']."' ");
				?>
			<div class="underline muted">
				<?php if(file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'])) { ?><a href="#image=<?php print $pic['pic_id'];?>"><img src="<?php tempFolder(); ?><?php print "/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];?>" class="mini" style="cursor: pointer; bottom: 0; float: left; margin-right: 16px;  "></a><?php } ?>

			<?php 
				if(empty($rev['proof_id'])) { ?>
				Was not reviewed
				<?php } else { ?>

				<a href="index.php?do=people&p_id=<?php print $p['p_id'];?>"><b><?php print $p['p_name']." ".$p['p_last_name']."</b></a> on ".$rev['proof_date'];?> 
				
				<?php if($rev['proof_status'] == "1") { ?>Approved the file 
				<?php } else { ?>
				Requested revision & 
					said <?php print nl2br($rev['proof_comment']);?>
					
					<?php } ?>
					

				<?php } ?>
						<div class="clear"></div>
					</div>

				<?php 
			}
			?>
			</div>
			<div class="clear"></div>

		</div>


		<?php 
	} else { 

		print "<div class=\"thumbOuter\" id=\"photoThumb-".$pic['pic_id']."\" style=\"width: ".($max_width + 20)."px; height: ".($max_height + 70)."px; margin: auto; display: inline; \" >";
		print "<div class=\"thumb\"   style=\"bottom: 0; left: 8px;  display: inline-block; *display:inline; zoom: 1; width: ".($max_width + 20)."px;float: none;\" >";

	?>
<div>
	<?php if($pic['pic_amazon'] == "1") { ?>

	<?php if($no_link !== true) { ?><a href="#image=<?php print $pic['pic_id'];?>"><?php } ?><img src="//<?php print $site_setup['amazon_endpoint'];?>/<?php print $pic['pic_bucket']."/".$pic['pic_bucket_folder']."/".$pic['pic_th'];?>" class="thumbnail <?php if($pic['pic_hide'] == "1") { ?>hiddenphoto<?php } ?>" style=" bottom: 0; <?php if($size[1] > $max_height) { print "height: ".$max_height."px; width: auto;"; } ?>" width="<?php print $pic['pic_th_width'];?>" height="<?php print $pic['pic_th_height'];?>"></a>

	<?php } else { ?>

	<?php if(file_exists($setup['path']."/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'])) { ?>
	<?php if($no_link !== true) { ?><a href="#image=<?php print $pic['pic_id'];?>"><?php } ?><img src="<?php tempFolder(); ?><?php print "/".$setup['photos_upload_folder']."/".$pic_folder."/".$pic['pic_th'];?>" class="thumbnail <?php if($pic['pic_hide'] == "1") { ?>hiddenphoto<?php } ?>" style=" bottom: 0; <?php if($size[1] > $max_height) { print "height: ".$max_height."px; width: auto;"; } ?>" width="<?php print $size[0];?>" height="<?php print $size[1];?>"></a>
	
	<?php }
	} ?></div>

<?php if($pic['pic_hide'] == "1") { 
$person = doSQL("ms_people", "*", "WHERE p_id='".$pic['pic_hide_by']."' ");?>
<div class="pc">Hidden by <a href="index.php?do=people&p_id=<?php print $person['p_id'];?>"><?php print $person['p_name']." ".$person['p_last_name'];?></a></div>
<?php } ?>


	<?php if(empty($_REQUEST['p_id'])) { ?>
	<div class="photoNameContainer <?php if($pic['pic_fav_admin'] == "1") { ?>photoNameContainerBorderFav<?php } else { ?>photoNameContainerBorder<?php } ?>" id="pn-container-<?php print $pic['pic_id'];?>">
	<?php 
		if(is_array($_SESSION['heldPhotos'])) { 
			if(in_array($pic['pic_id'],$_SESSION['heldPhotos'])) { $add = "none"; $remove = "inline"; } else { $add = "inline"; $remove = "none";  } 
		} else { 
			$add = "inline"; $remove = "none";
		}
		?>
		<span id="<?php print $picnum;?>-pic" pic_id="<?php print $pic['pic_id'];?>"></span><span id="select-photo-<?php print $pic['pic_id'];?>" style="display: <?php print $add;?>; cursor: pointer;" onClick="javascript:selectPhoto('<?php print $pic['pic_id'];?>','0', '<?php print $picnum;?>');" title="Click to hold in tray" class="tip"><?php print ai_checkbox_off;?></span>
		<span id="select-photo-<?php print $pic['pic_id'];?>-on"  style="display: <?php print $remove;?>;cursor: pointer;" onClick="javascript:unSelectPhoto('<?php print $pic['pic_id'];?>','0','<?php print $picnum;?>');"  title="Click to remove from tray" class="tip"><?php print ai_checkbox_on;?></span>

		<?php 
			if(!empty($_REQUEST['date_id'])) { 
			?>
				<!-- <a href="javascript:photoColors('<?php print $pic['pic_id'];?>');" title="Adjust Background & Border Colors" class="tip"><?php print ai_photo_colors;?></a> -->
				<a  href="" id="ppic-<?php print $pic['pic_id']?>" class="confirmdeleteoptionsthumbs tip" confirm-title="Delete Photo" confirm-message="Select from the options below" option-link-1="javascript:removePhotoFromBlog('<?php print $pic['pic_id'];?>','<?php print $pic['bp_id'];?>');" option-link-1-text="Remove photo from this gallery but leave in the system"  option-link-2="javascript:deletePhoto('<?php print $pic['pic_id'];?>');" option-link-2-text="Delete photo completely" title="Delete"><?php print ai_delete;?></a>
				<?php if($pic['bp_pl'] > 0) { 
					$ol = doSQL("ms_photo_products_lists", "list_id,list_name", "WHERE list_id='".$pic['bp_pl']."' ");
					print "<span class=\"tip\" title=\"Overriding price list with: ".$ol['list_name']."\">".ai_price_list_override."</span>"; } ?>

			<?php } else { ?>
		<!-- <a href="javascript:photoColors('<?php print $pic['pic_id'];?>');" title="Adjust Background & Border Colors" class="tip"><?php print ai_photo_colors;?></a>  -->
		<a href="javascript:deletePhoto('<?php print $pic['pic_id'];?>');"  onClick="return confirm('Are you sure you want to delete this?'); return false;" title="Delete" class="tip"><?php print ai_delete;?></a>
		<?php } ?> 
				<a href="" onclick="openFrame('w-photos-upload.php?replace_photo=<?php print $pic['pic_id'];?>&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>'); return false;" class="tip" title="Replace Photo"><?php print ai_replace; ?></a>

		<?php if($pic['pic_client'] == "1") { print "<span class=\"tip\" title=\"Private Photo\">".ai_lock."</span>"; } ?>
		<?php if($pic['pic_amazon'] == "1") { ?><span class="tip" title="Hosted on Amazon S3"><img src="graphics/amazon-s3-logo-16.png" width="16" height="16" align="absmiddle"></span><?php } ?>
	
			<?php 
			if(!empty($_REQUEST['date_id'])) { ?>
			<a href=""  onClick="addToFavs('<?php print $pic['pic_id'];?>','<?php print $date['date_id'];?>','<?php print $_REQUEST['sub_id'];?>'); return false;" title="Photographer Favorite" class="tip"><img src="graphics/icons/fav_off.png" width="16" height="16"  align="absmiddle" id="fav-pic-off-<?php print $pic['pic_id'];?>" class="<?php if($pic['pic_fav_admin'] == "1") { ?>hide<?php } ?>"><img src="graphics/icons/fav_on.png" width="16" height="16"  align="absmiddle" id="fav-pic-on-<?php print $pic['pic_id'];?>" class="<?php if($pic['pic_fav_admin'] == "0") { ?>hide<?php } ?>"></a>
			<?php 
				if($_REQUEST['sub_id'] > 0) { ?>


			<div class="dpmenucontainerb" style="display: inline; margin-left: 8px;">
				<div class="dpmenub bold" style="text-align: left;">	<?php print ai_photo;?>
					<div class="dpinnerb">
						<div class="pc textleft"><a href="javascript:makePreviewPhoto('<?php print $pic['pic_id'];?>','<?php print $date['date_id'];?>', '<?php print $_REQUEST['sub_id'];?>');">Make Preview Photo For This Sub Gallery</a></div>
						<div class="pc textleft"><a href="javascript:makePreviewPhoto('<?php print $pic['pic_id'];?>','<?php print $date['date_id'];?>', '');" >Preview Photo For Main Gallery</a></div>
					</div>
				</div>
			</div>
			<?php } else { ?>
				<a href="javascript:makePreviewPhoto('<?php print $pic['pic_id'];?>','<?php print $date['date_id'];?>', '<?php print $_REQUEST['sub_id'];?>');" title="Make this photo the preview" class="tip"><?php print ai_photo;?></a>
			<?php } ?>


			<?php } ?>
	
	
	</div>
	<?php } ?>
	<div><span class="tip" title="<?php print $pic['pic_org'];?>"><?php print $pic['pic_org'];?></span></div>
				<div><span>ID: <?php print $pic['pic_id'];?></span></div>
			<?php if(($date['passcode_photos'] == "1")&&(!empty($pic['pic_title'])) == true) { ?><div>Passcode: <a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&passcode=<?php print $pic['pic_title'];?>"><?php print $pic['pic_title'];?></a></div><?php } ?>

		<div><a href="index.php?do=people&p_id=<?php print $pic['p_id'];?>&view=favorites"><?php print $pic['p_name']." ".$pic['p_last_name'];?></a></div>

</div>
</div>

<?php } ?>


<?php } ?>
<?php 
if(($total_results / $per_page) > $page) { ?>
<div id="page-<?php print $page + 1;?>" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>
<?php } ?>
