<?php require "w-header.php"; ?>
<?php 
$photo_setup = doSQL("ms_photo_setup", "*", "  ");
$width = $photo_setup['blog_width'];
$height = $photo_setup['blog_height'];
$thumb_size = $photo_setup['blog_th_width'];
$thumb_size_height = $photo_setup['blog_th_height'];
$mini_size = $site_setup['blog_mini_size'];
$crop_thumbs = $photo_setup['blog_th_crop'];
$is_blog = 1;
$date = doSQL("ms_calendar", "*", "WHERE date_id='".$_REQUEST['date_id']."' ");
if($_REQUEST['sub_id'] > 0) { 
	$sub = doSQL("ms_sub_galleries", "*", "WHERE sub_id='".$_REQUEST['sub_id']."' ");
}

$num_pics = countIt("ms_blog_photos", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' "); 

if($_REQUEST['action'] == "updateOrder") { 
	$pics = whileSQL("ms_blog_photos  LEFT JOIN ms_photos ON ms_blog_photos.bp_pic=ms_photos.pic_id", "*", "WHERE bp_blog='".$_REQUEST['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."' ORDER BY ".$_REQUEST['order_by']."  ".$_REQUEST['acdc']." ");
	while($pic = mysqli_fetch_array($pics)) { 
		$pc++;
		updateSQL("ms_blog_photos", "bp_order='$pc' WHERE bp_blog='".$date['date_id']."' AND bp_pic='".$pic['pic_id']."' ");
	}
	$_SESSION['sm'] = "Display order updated";
	session_write_close();
	header("location: index.php?do=news&action=managePhotos&date_id=".$_REQUEST['date_id']."&sub_id=".$_REQUEST['sub_id']."");
	exit();

}

?>
<?php if($_REQUEST['showSuccess'] == "1") { ?>
	<script>
	showSuccessMessage("Display order updated");
	setTimeout(hideSuccessMessage,5000);
	</script>
<?php } ?>
<div class="pc"><h1>Change display order of photos</h1></div>
<div class="pc">Use the options below to change the display order of the photos.</div>
<div class="pc">
<form name="orderp" id="orderp" action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
<select name="order_by">
<option value="pic_org">File Name</option>
<option value="pic_id">Upload Date</option>
<option value="pic_date_taken">Date / Time Taken</option>
</select>
<select name="acdc">
<option value="ASC">Ascending</option>
<option value="DESC">Descending</option>
</select>
<input type="hidden" name="date_id" value="<?php print $date['date_id'];?>">
<input type="hidden" name="sub_id" value="<?php print $_REQUEST['sub_id'];?>">
<input type="hidden" name="action" value="updateOrder">
<input type="submit" name="submit" value="Update Order" class="submit">
</form>
</div>

<div class="pc"><a href="" onclick="pagewindowedit('w-photos-order.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&manual_arrange=1&nofonts=1&nojs=1&noclose=1'); return false;">Manually Rearrange Photos</a></div>

<?php if($_REQUEST['manual_arrange'] == "1") {  ?>


<div class="specialMessage"><div class="pc center"><h3><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" target="_parent">When you are done, click here to close and refresh the page to show changes.</a></h3></div></div>
<?php
	$d['table'] = "ms_photos";
	$d['table_id'] = "pic_id";

	$pics = whileSQL("ms_blog_photos", "*", "WHERE bp_blog='".$date['date_id']."' AND bp_sub='".$_REQUEST['sub_id']."'  ORDER BY bp_order ASC");
	$picsy = whileSQL("ms_blog_photos", "*", "WHERE bp_blog='".$date['date_id']."'  AND bp_sub='".$_REQUEST['sub_id']."'  ORDER BY bp_order ASC");
	while ($bp_pic = mysqli_fetch_array($pics)){

		$pici = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE pic_id='".$bp_pic['bp_pic']."' ");

		if(!empty($pici['pic_folder'])) { 
			$pic_folder = $pici['pic_folder'];
		} else { 
			$pic_folder = $pici['gal_folder'];
		}
		$size = getimagefiledems($pici,'pic_th');
		if($size[0] > $max_width) { $max_width = $size[0]; } 
		if($size[1] > $max_height) { $max_height = $size[1]; } 
	}
}
?>








<!-- TESTING AREA -->


<script>
/* when the DOM is ready */
jQuery(document).ready(function() {
	/* grab important elements */
	var sortInput = jQuery('#sort_order');
	var submit = jQuery('#autoSubmit');
	var messageBox = jQuery('#message-box');
	var list = jQuery('#sortable-list');
	/* create requesting function to avoid duplicate code */
	var request = function() {
		jQuery.ajax({
			beforeSend: function() {
				messageBox.text('Updating .....');
			},
			complete: function() {
				messageBox.text('Display order updated.');

			},
			data: 'sort_order=' + sortInput[0].value + '&amp;ajax=' + submit[0].checked + '&amp;do_submit=1&amp;byajax=1&amp;action=updatePhotoBlogOrder', //need [0]?
			type: 'post',
			url: 'admin.actions.php?action=updatePhotoBlogOrder'
		}).done(function( msg ) {
 // alert( "Data Saved: " + msg );
});

	};
	/* worker function */
	var fnSubmit = function(save) {
		var sortOrder = [];
		list.children('li').each(function(){
			sortOrder.push(jQuery(this).data('id'));
		});
		sortInput.val(sortOrder.join(','));
	//	console.log(sortInput.val());
		if(save) {
			request();
		}
	};
	/* store values */
	list.children('li').each(function() {
		var li = jQuery(this);
		li.data('id',li.attr('title')).attr('title','');
	});
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function() {
			fnSubmit(submit[0].checked);
		}
	});
	list.disableSelection();
	/* ajax form submission */
	jQuery('#dd-form').bind('submit',function(e) {
		if(e) e.preventDefault();
		fnSubmit(true);
	});
});

</script>
<script>
$(document).ready(function(){
$('.thumbclick').bind({
  click: function() {
	 this_id = $(this).attr("id");
  //  alert(this_id);
  },
  mouseenter: function() {
    // do something on mouseenter
  }
});
/*
$('.thumbclick').unbind("click").click(function (event) {
	 this_id = $(this).attr("id");
    alert(this_id+" HEY");
    });
*/


});

</script>


<?php if($_REQUEST['manual_arrange'] == "1") { ?>

	<?php if($num_pics >0) { ?>
	<div class="pageContent">Drag and drop the thumbnails to change the display order.</div>
	<div id="message-box" class="pageContent"><?php echo $message; ?>


	</div>

	<?php } ?>


	<form id="dd-form" action="admin.actions.php" method="post">
	<input type="hidden" name="action" value="updatePhotoBlogOrder">
	<input type="hidden" name="date_id" value="<?php print $_REQUEST['date_id'];?>">

	<p style="display: none;">
	  <input type="checkbox" value="1" name="autoSubmit" id="autoSubmit" checked />
	  <label for="autoSubmit">Automatically submit on drop event</label>
	</p>
	<div id="photoGallery">
	<style>
	#sortable-list { 
	list-style-type: none;
	padding: 0px;
	margin: 0px;
	   text-align: center;
	   margin: auto;
	}
	#sortable-list li { 
		float: left;
		display: inline-block; 
	}
	#sortable-list .thumb{ 

		padding: 0px;
		margin: 4px;
		float: left;
		overflow: hidden;
		text-align: center;
		z-index: 0;
	}
	</style>

	<ul id="sortable-list">




	<?php


	$order = array();
	while ($bp_pic = mysqli_fetch_array($picsy)){
		$pic = doSQL("ms_photos", "*, date_format(DATE_ADD(ms_photos.pic_date, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date, date_format(DATE_ADD(ms_photos.pic_last_viewed, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_last_viewed, date_format(DATE_ADD(ms_photos.pic_date_taken, INTERVAL 0 HOUR), '%m/%e/%y %h:%i:%s %p ')  AS pic_date_taken_show", "WHERE pic_id='".$bp_pic['bp_pic']."' ");
		  echo "<li title=\"".$bp_pic['bp_id']."\"  id=\"photoThumb-".$pic['pic_id']."\" >";

		if(empty($pic['pic_id'])) { 
		//		deleteSQL2("ms_blog_photos", "WHERE bp_pic='".$bp_pic['bp_pic']."' ");
		//	print "<li>Deleted";
		}
		if(!empty($pic['pic_folder'])) { 
			$pic_folder = $pic['pic_folder'];
		} else { 
			$pic_folder = $pic['gal_folder'];
		}


			$size = getimagefiledems($pic,'pic_th');

			print "<div class=\"thumb\" >";

	?>

		<div >
	<img id="photoThumb-<?php print $pic['pic_id'];?>-thumb" src="<?php print getimagefile($pic,'pic_mini');?>" border="0"  title="<?php print $pic['pic_org'];?>"> </div>

	<div style="text-align: center; overflow: hidden;">
	<?php // print $pic['pic_org'];?>
	</div>

	</div>
		</li>
		<?php

		  $order[] = $bp_pic['bp_id'];
		}
	  ?>
	</ul>
	</div>
	<div class="cssClear"></div>
	<br />
	<input type="hidden" name="sort_order" id="sort_order" value="<?php echo implode(',',$order); ?>" />
	<input type="submit" name="do_submit" value="Submit Sortation" class="button" style="display: none;"/>
	</form>



<div class="specialMessage"><div class="pc center"><h3><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" target="_parent">When you are done, click here to close and refresh the page to show changes.</a></h3></div></div>
<?php } ?>


<?php require "w-footer.php"; ?>
