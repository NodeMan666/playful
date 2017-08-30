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
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");


$total_results = countIt("ms_sub_galleries",  "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ORDER BY sub_order ASC, sub_name ASC  ");
if(empty($_REQUEST['page'])) { 
	$page = 1;
} else {
	$page = $_REQUEST['page'];
}
$per_page = 20;
$sq_page = $page * $per_page - $per_page;


?>
<script language="javascript" src="js/admin.js?v=<?php print $site_setup['sytist_version'];?>" type="text/javascript"></script>
 <script>
$(document).ready(function(){
	 mytips(".tip","tooltip");
	 myinputtips(".inputtip","tooltip");
	 dpmenu();
});
</script>


<?php
function getsubpreview($date,$sub) { 
	global $setup;
	$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE   bp_sub_preview='".$sub['sub_id']."'   ORDER BY bp_order ASC LIMIT  1 ");
	if(!empty($pic['pic_id'])) { 
		$size = getimagefiledems($pic,'pic_th');				
		?>
		<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail" style="max-height: 150px; width: auto;"></a>
	<?php 
	} else { 
		$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
		if(!empty($pic['pic_id'])) {
			$size = getimagefiledems($pic,'pic_th');
			?>
			<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail" style="max-height: 150px; width: auto;"></a>
		<?php 
		} else { 
			$under = doSQL("ms_sub_galleries", "*", "WHERE sub_under='".$sub['sub_id']."' ORDER BY sub_order ASC, sub_name ASC");
			if(!empty($under['sub_id'])) { 
				if(empty($thumb_html)) { 
					$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$under['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
					if(!empty($pic['pic_id'])) {
						$thumb_html  = "1";
						$size = getimagefiledems($pic,'pic_th');
						?>
						<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail" style="max-height: 150px; width: auto;"></a>
						<?php 
						} else { 

						$unders2 = doSQL("ms_sub_galleries", "*", "WHERE sub_under='".$under['sub_id']."' ORDER BY sub_order ASC, sub_name ASC");
						if(empty($thumb_html)) { 
							$pic = doSQL("ms_blog_photos LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$unders2['sub_id']."'  ORDER BY bp_order ASC LIMIT  1 ");
							if(!empty($pic['pic_id'])) {
								$thumb_html  = "1";
								$size = getimagefiledems($pic,'pic_th');
								?>
								<a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><img src="<?php print getimagefile($pic,'pic_th');?>" class="thumbnail" style="max-height: 150px; width: auto;"></a>
								<?php 
								}
						}
					}
				}
			}
		}
	}
}
?>

	<?php 
	$subs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ORDER BY sub_order ASC, sub_name ASC  LIMIT $sq_page,$per_page ");
	while($sub = mysqli_fetch_array($subs)) { ?>
		<li title="<?php print $sub['sub_id'];?>" style="display: inline-block; width: 250px; margin: 8px;text-align: center; ">
		<div class="underline">

		<div style="margin-right: 8px;"><?php getsubpreview($date,$sub);?></div>
		<div><h4><?php if($sub['sub_id'] == $_REQUEST['sub_id']) { print ai_sep." "; } ?><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>"><?php print $sub['sub_name'];?></a> (<?php print countIt("ms_blog_photos",  "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$sub['sub_id']."' ");?>)</h4>
		</div>

		<div class="sub small"><a href="" onclick="pagewindowedit('w-sub-galleries-edit.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;">Edit</a> &nbsp; 
		<a href=""  class="confirmdeleteoptions" confirm-title="Delete Sub Gallery<br><?php print htmlspecialchars($sub['sub_name']);?>" confirm-message="Select from the options below" option-link-1="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&subdo=deleteSubGallery&sub_id=<?php print $sub['sub_id'];?>" option-link-1-text="Delete sub gallery and leave the photos in the system"  option-link-2="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&subdo=deleteSubGallery&sub_id=<?php print $sub['sub_id'];?>&deletephotos=1" option-link-2-text="Delete sub gallery and DELETE THE PHOTOS from the system">Delete</a>  &nbsp; 
		<a href="" onclick="openFrame('w-photos-upload.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>'); return false;" >Upload Photos</a> &nbsp; 
		<?php if($date['green_screen_gallery'] !== "1") { ?>
		<a href="" onclick="pagewindowedit('w-sub-galleries.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&noclose=1&nofonts=1&nojs=1&opt_photo_prod='); return false;"><nobr>Add Sub Galleries</nobr></a>
		<?php } ?>
		</div>	
		<?php if(!empty($sub['sub_pass'])) { ?>
		<div class="pc"><?php print ai_lock;?> <?php print $sub['sub_pass'];?></div>
		<?php } ?>
		<?php if($sub['sub_price_list'] > 0) { 
			$list = doSQL("ms_photo_products_lists", "*", "WHERE list_id='".$sub['sub_price_list']."' ");?>
		<div class="pc">Price List: <a href="index.php?do=photoprods&view=list&list_id=<?php print $list['list_id'];?>"><?php print $list['list_name'];?></a></div>
		<?php } ?>

	</div> 
	</li>
	<?php } ?>


<?php 
if(($total_results / $per_page) > $page) { ?>
<div id="sub-gallery-page-<?php print $page + 1;?>" style="display: none; width: 100%; height: 30px;" class="thumbPageLoading"></div>
<?php } ?>
