<?php require "w-header.php"; ?>
<?php if(!function_exists(msIncluded)) { die("Direct file access denied"); } ?>
<?php 
if(!function_exists(adminsessionCheck)){
	die("no direct access");
}?>

<?php adminsessionCheck(); ?>
<?php
if($_REQUEST['date_id'] > 0) { 
	$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
}

if($_REQUEST['process'] == "1") { 
	?>
<div style="display: none;">
	<?php 
	if($date['date_id'] > 0) { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'  AND pic_amazon<='0' ORDER BY bp_order ASC");

	} else { 
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$_SESSION['heldPhotos'][0]."' ");
	}

	if($pic['pic_amazon'] !== "1"){ 

		// move file
		$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];
		if(file_exists($uploadFile)) { 
			$folderName = $pic['pic_upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'];
		if(file_exists($uploadFile)) { 
			$folderName = $pic['pic_upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'];
		if(file_exists($uploadFile)) { 
			$folderName = $pic['pic_upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}

		$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large'];
		if(file_exists($uploadFile)) { 
			$folderName = $pic['pic_upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}
		$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];
		if((file_exists($uploadFile))&&(!empty($pic['pic_full']))==true) { 
			$folderName = $pic['pic_upload_session'];
			require $setup['path']."/".$setup['manage_folder']."/S3-move.php";
		}

		if($s3error !== true) { 

			if($pic['pic_th_width'] <=0) { 
				$thsize = @GetImageSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']); 
				$and_th = ", pic_th_width='".$thsize[0]."', pic_th_height='".$thsize[1]."' ";
			}
			if($pic['pic_small_width'] <=0) { 
				$thsize = @GetImageSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']); 
				$and_th .= ", pic_small_width='".$thsize[0]."', pic_small_height='".$thsize[1]."' ";
			}
			if($pic['pic_large_width'] <=0) { 
				$thsize = @GetImageSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']); 
				$and_th .= ", pic_large_width='".$thsize[0]."', pic_large_height='".$thsize[1]."' ";
			}

			$pic_filesize= @FileSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full']); 
			$pic_filesize_large= @FileSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large']); 
			$pic_filesize_small= @FileSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic']); 
			$pic_filesize_thumb= @FileSize($setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th']); 

			updateSQL("ms_photos", "pic_amazon='1', pic_bucket='".addslashes(stripslashes(trim($photo_setup['awsBucketName'])))."', pic_bucket_folder='".$folderName."', pic_filesize='".$pic_filesize."', pic_filesize_large='".$pic_filesize_large."', pic_filesize_small='".$pic_filesize_small."', pic_filesize_thumb='".$pic_filesize_thumb."' $and_th WHERE pic_id='".$pic['pic_id']."' ");

			$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];
			if(file_exists($uploadFile)) { 
				unlink($uploadFile);
			}
			$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_th'];
			if(file_exists($uploadFile)) { 
				unlink($uploadFile);
			}
			$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_pic'];
			if(file_exists($uploadFile)) { 
				unlink($uploadFile);
			}
			$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_large'];
			if(file_exists($uploadFile)) { 
				unlink($uploadFile);
			}
			$uploadFile = $setup['path']."/".$setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_full'];
			if(file_exists($uploadFile)) { 
				unlink($uploadFile);
			}
		if($date['date_id'] <=0) { 
			$new_array = array();
			foreach($_SESSION['heldPhotos'] AS $this_pic) { 
				if($this_pic !== $pic['pic_id']) { 
					array_push($new_array,$this_pic);
				}
			}
			$_SESSION['heldPhotos'] = $new_array;
		}
		} else { 
			if($date['date_id'] <=0) { 

				$new_array = array();
				foreach($_SESSION['heldPhotos'] AS $this_pic) { 
					if($this_pic !== $pic['pic_id']) { 
						array_push($new_array,$this_pic);
					}
				}
				$_SESSION['heldPhotos'] = $new_array;
			}
		}
	}
	?>
	<?php print "<li>HELD: ".count($_SESSION['heldPhotos']);?>
	<?php 
	if($date['date_id'] > 0) { 
		$remaining = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE bp_blog='".$date['date_id']."' AND pic_amazon<='0'  ");
	} else { 
		$remaining = count($_SESSION['heldPhotos']);
	}
	if($remaining <= 0) { ?>
	<script>
	 $(document).ready(function(){
		location.reload();
		// pagewindowedit('w-move-amazon.php?noclose=1&nofonts=1&nojs=1&date_id=<?php print $date['date_id'];?>')
		// javascript:ajaxpage("admin.actions.php?do=photos&action=showHeldPhotos", "thePhotos");
	});
	</script>
	<?php 
	} else { 
	?>
		<script>
		 $(document).ready(function(){
			 pagewindowedit('w-move-amazon.php?noclose=1&nofonts=1&nojs=1&date_id=<?php print $date['date_id'];?>&process=1')
		});
		</script>
	<?php } ?>

	</div>

<?php } ?>

<script>
function startProcess() { 
	$("#loading-1").html('<img src="graphics/loading2.gif" align="abs-middle">');
	pagewindowedit('w-move-amazon.php?noclose=1&nofonts=1&nojs=1&date_id=<?php print $date['date_id'];?>&process=1');
}
</script>
<?php 
if($_REQUEST['date_id'] > 0) { 
	$total_photos = countIt("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "WHERE bp_blog='".$date['date_id']."' AND pic_amazon<='0'  ");
} else { 
	$total_photos = count($_SESSION['heldPhotos']); 
}
?>
<div class="pc right textright buttons"><a href="" onclick="startProcess(); return false;">Start Move</a></div>

<div class="pc left"><h2><?php if(!empty($date['date_title'])) { print $date['date_title']." - "; } ?>Move <?php print $total_photos;?> photos to Amazon S3</h2></div>
<div class="clear"></div>
<div class="pc">Bucket name: <?php print $photo_setup['awsBucketName'];?></div>

<?php if($_REQUEST['process'] == "1") { ?>
<div class="pc center"><h3>Working... do not close this window</h3></div>
<?php } ?>
<?php 

if($date['date_id'] > 0) { 
	$pics = whileSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."'  AND pic_amazon<='0'  ORDER BY bp_order ASC LIMIT 10");
	while($pic = mysqli_fetch_array($pics)) { 
		$x++;
		if($pic['pic_amazon'] !== "1") { ?>
		<div class="underline" id="pic-<?php print $pic['pic_id'];?>">
		<div style="width: 10%; text-align: center; float: left;" id="loading-1"><?php if(($_REQUEST['process'] == "1")&&($x == 1)==true) { ?><img src="graphics/loading2.gif" align="abs-middle"><?php } else { print "&nbsp;"; } ?></div>
			<div class="left" style="margin-right: 16px;"><img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];?>"></div>
			<div class="float: left;"><?php print $pic['pic_org'];?></div>
			<div class="clear"></div>
		</div>
		<?php } 


 } 
} else { 
$f = array_slice($_SESSION['heldPhotos'],0,10);
	foreach($f AS $photo) { 
		$x++;
		$pic = doSQL("ms_photos", "*", "WHERE pic_id='".$photo."' ");
		if($pic['pic_amazon'] !== "1") { ?>
		<div class="underline" id="pic-<?php print $pic['pic_id'];?>">
		<div style="width: 10%; text-align: center; float: left;" id="loading-1"><?php if(($_REQUEST['process'] == "1")&&($x == 1)==true) { ?><img src="graphics/loading2.gif" align="abs-middle"><?php } else { print "&nbsp;"; } ?></div>
			<div class="left" style="margin-right: 16px;"><img src="<?php print $setup['temp_url_folder'];?>/<?php print $setup['photos_upload_folder']."/".$pic['pic_folder']."/".$pic['pic_mini'];?>"></div>
			<div class="float: left;"><?php print $pic['pic_org'];?></div>
			<div class="clear"></div>
		</div>
		<?php } 
	}
}
	?>
	<?php
	if(($total_photos - 10) > 0) { ?>
		<div class="pc center">and <?php print $total_photos - 10;?> more</div>
	<?php } ?>



<?php require "w-footer.php"; ?>
