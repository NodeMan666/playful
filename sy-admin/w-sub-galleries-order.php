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

if($_REQUEST['action'] == "updateOrder") { 
	$ssubs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ORDER BY sub_name ".$_REQUEST['acdc']." ");
	while($ssub = mysqli_fetch_array($ssubs)) { 
		$pc++;
		updateSQL("ms_sub_galleries", "sub_order='$pc' WHERE sub_id='".$ssub['sub_id']."' ");
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

<div class="pc"><h1>Change display order of sub galleries</h1></div>

<div class="pc left  buttons">
<a href="<?php print $_SERVER['PHP_SELF'];?>?acdc=ASC&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&action=updateOrder">By Name Ascending</a>
<a href="<?php print $_SERVER['PHP_SELF'];?>?acdc=DESC&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $sub['sub_id'];?>&action=updateOrder">By Name Descending</a>

</div>
<div class="clear"></div>
<div>&nbsp;</div>
<div class="pc"><a href="" onclick="pagewindowedit('w-sub-galleries-order.php?date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>&manual_arrange=1&nofonts=1&nojs=1&noclose=1'); return false;">Or Manually Rearrange Sub Galleries</a></div>

<?php if($_REQUEST['manual_arrange'] == "1") {  ?>
	<div class="specialMessage"><div class="pc center"><h3><a href="index.php?do=news&action=managePhotos&date_id=<?php print $date['date_id'];?>&sub_id=<?php print $_REQUEST['sub_id'];?>" target="_parent">When you are done, click here to close and refresh the page to show changes.</a></h3></div></div>

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
			data: 'sort_order=' + sortInput[0].value + '&amp;ajax=' + submit[0].checked + '&amp;do_submit=1&amp;byajax=1&amp;action=orderSubs', //need [0]?
			type: 'post',
			url: 'admin.actions.php?action=orderSubs'
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

	<div class="pageContent">Drag and drop the sub galleries to change the display order.</div>
	<div id="message-box" class="pageContent"><?php echo $message; ?></div>



	<form id="dd-form" action="admin.actions.php" method="post">
	<input type="hidden" name="action" value="orderSubs">
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
	$subs = whileSQL("ms_sub_galleries", "*", "WHERE sub_date_id='".$date['date_id']."' AND sub_under='".$_REQUEST['sub_id']."' ORDER BY sub_order ASC, sub_name ASC ");
	while($sub = mysqli_fetch_array($subs)) {
		?>
		<li class="underline" style="display: block;" title="<?php print $sub['sub_id']; ?>"  id="<?php print $sub['sub_id']; ?>">
		<div class="left" style="margin-right: 16px;"><?php print ai_sort;?></div>
		<div class="left"><?php print $sub['sub_name'];?></div>
		<div class="clear"></div>
		</li>


		<?php

		  $order[] = $sub['sub_id'];
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
